<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreTransactionRequest;
use App\Http\Requests\V1\UpdateTransactionRequest;
use App\Http\UpApi\UpApi;
use App\Models\Transaction;
use App\Http\UpApi\Transformers\TransactionTransformer;
use App\Http\UpApi\Util;
use App\Jobs\FetchTransactionHistory;
use Carbon\Exceptions\EndLessPeriodException;
use DateTime;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TransactionController extends Controller
{
    /**
     * @param Request $request 
     * @return LeadCollection 
     * @throws InvalidArgumentException 
     */
    public function index(Request $request)
    {
        $query_params = $request->query();
        $request_params['page[size]'] = $query_params['size'] ?? 5;
        $user = $request->user();

        if (isset($query_params['dateFrom'])) {
            $datefrom = DateTime::createFromFormat("d M Y", $query_params['dateFrom']);
            $datefrom->setTime(0,0,0);
            $request_params['filter[since]'] = $datefrom->format(DateTime::RFC3339);
        }
        if (isset($query_params['dateTo'])) {
            $dateto = DateTime::createFromFormat("d M Y", $query_params['dateTo']);
            $dateto->setTime(23, 59, 59);
            $request_params['filter[until]'] = $dateto->format(DateTime::RFC3339);
        }

        if (isset($query_params['next'])) {
            $request_params['page[after]'] = $query_params['next'];
        }

        if (isset($query_params['prev'])) {
            $request_params['page[before]'] = $query_params['prev'];
        }

        $client = new UpApi($user);
        $raw_transactions = $client->getTransactions($request_params);

        $transformer = new TransactionTransformer($user);
        $transactions = $transformer->transform($raw_transactions);

        return response()->json([
            'data' => $transactions,
            'page_links' => Util::getPaginationKeys($raw_transactions['links']),
            'count' => $transactions->count(),
            'user' => $user->id
        ]);
    }


    /**
     * @param Request $request 
     * @return void 
     * @throws BindingResolutionException 
     */
    public function queueFetchTransactionHistory(Request $request)
    {
        $user = $request->user();
        FetchTransactionHistory::dispatchSync($user);

        return response()->json([
            'success' => true,
            'message' => 'Queued to fetch Transaction history'
        ]);
    }

    /**
     * @param Request $request 
     * @return JsonResponse 
     * @throws EndLessPeriodException 
     * @throws InvalidArgumentException 
     * @throws BindingResolutionException 
     */
    public function getSpentValueByDateRange(Request $request): JsonResponse
    {
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
        $user = $request->user();

        $transactions = Transaction::query()
            ->whereBetween('remote_created_at', [$weekStartDate, $weekEndDate])
            ->where('description', 'not like', "%Round Up%")
            ->where('description', 'not like', "%Transfer to%")
            ->where('description', 'not like', "%Transfer from%")
            ->where('amount_base_unit_value', '<', 0) // Spent money is always negative, money-in is positive and we don't want that here
            ->where('user_id', '=',  $user->id);

        $total_spent = $transactions->sum('amount_base_unit_value');

        return response()->json([
            'status' => 200,
            'date_start' => $weekStartDate,
            'date_end' => $weekEndDate,
            'transaction_data' => $total_spent,
            'transaction_count' => $transactions->count(),
            'user' => $user->id
        ]);
    }

    /**
     * @param Request $request 
     * @return JsonResponse 
     * @throws InvalidArgumentException 
     * @throws BindingResolutionException 
     */
    public function getNewMerchants(Request $request): JsonResponse
    {
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
        $user = $request->user();

        $merchants = Transaction::query()
            ->select('description')
            ->whereBetween('remote_created_at', [$weekStartDate, $weekEndDate])
            ->where('user_id', '=',  $user->id)
            ->where('description', 'not like', "%Round Up%")
            ->where('description', 'not like', "%Transfer to%")
            ->where('description', 'not like', "%Transfer from%")
            ->whereRaw('(SELECT COUNT(*) FROM transactions t WHERE t.description = transactions.description) = 0')
            ->get();

        return response()->json([
            'status' => 200,
            'date_start' => $weekStartDate,
            'date_end' => $weekEndDate,
            'merchant_data' => $merchants,
            'merchant_count' => $merchants->count(),
            'user' => $user->id
        ]);
    }

    /**
     * @param Request $request 
     * @return JsonResponse 
     * @throws InvalidArgumentException 
     * @throws BindingResolutionException 
     */
    public function getRoundUpTotal(Request $request): JsonResponse
    {
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
        $user = $request->user();

        $transactions = Transaction::query()
            ->whereBetween('remote_created_at', [$weekStartDate, $weekEndDate])
            ->where('description', '=', "Round Up")
            ->where('user_id', '=',  $user->id)
            ->get();

        $total_roundup = $transactions->sum('amount_base_unit_value');

        return response()->json([
            'status' => 200,
            'date_start' => $weekStartDate,
            'date_end' => $weekEndDate,
            'total_roundup' => $total_roundup,
            'round_up_count' => $transactions->count(),
            'user' => $user->id
        ]);
    }

    /**
     * @param Request $request 
     * @return JsonResponse 
     * @throws BindingResolutionException 
     */
    public function getTotalSumByCategory(Request $request): JsonResponse
    {
        $user = $request->user();

        $transactions = Transaction::query()
            ->select('category_id', DB::raw('SUM(amount_base_unit_value) as total_amount'), DB::raw('COUNT(*) as transaction_count'))
            ->where('user_id', '=',  $user->id)
            ->groupBy('category_id')
            ->orderBy('total_amount', 'asc')
            ->limit(5)
            ->get()
            ->keyBy('category_id')
            ->map(function ($item) {
                return [
                    'total_amount' => $item->total_amount,
                    'transaction_count' => $item->transaction_count
                ];
            });

        return response()->json([
            'status' => 200,
            'total_by_category' => json_decode($transactions),
            'user' => $user->id
        ]);
    }

}
