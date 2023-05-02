<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreTransactionRequest;
use App\Http\Requests\V1\UpdateTransactionRequest;
use App\Http\UpApi\UpApi;
use App\Models\Transaction;
use App\Http\UpApi\Transformers\TransactionTransformer;
use App\Http\UpApi\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
        $value = Cache::get('key');

        if (isset($query_params['next'])) {
            $request_params['page[after]'] = $query_params['next'];
        }

        if (isset($query_params['prev'])) {
            $request_params['page[before]'] = $query_params['prev'];
        }

        // $client = new UpApi(Auth::getUser()->up_bank_token);
        $client = new UpApi(env('UP_TEST_TOKEN'));
        $raw_transactions = $client->getTransactions($request_params);

        $transformer = new TransactionTransformer();
        $transactions = $transformer->transform($raw_transactions);

        return response()->json([
            'data' => $transactions, 
            'page_links' => Util::getPaginationKeys($raw_transactions['links']),
            'count' => $transactions->count()
        ]);
    }

    public function getSpentValueByDateRange(Request $request)
    {
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');

// var_dump($request->user());die;

        $transactions = DB::table('transactions')
            ->whereBetween('remote_created_at', [$weekStartDate, $weekEndDate])
            ->where('description', 'not like', "%Round Up%")
            ->where('description', 'not like', "%Transfer to%")
            ->where('description', 'not like', "%Transfer from%");
            // ->where('user_id', '=',  $request->user()->id);

        $total_spent = $transactions->sum('amount_base_unit_value');

        return response()->json([
            'status' => 200,
            'date_start' => $weekStartDate,
            'date_end' => $weekEndDate,
            'data' => $total_spent,
            'transaction_count' => $transactions->count()
        ]);
    }

    



}
