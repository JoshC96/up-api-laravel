<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreAccountRequest;
use App\Http\Requests\V1\UpdateAccountRequest;
use App\Http\UpApi\Transformers\AccountTransformer;
use App\Http\UpApi\UpApi;
use App\Http\UpApi\Util;
use App\Models\Account;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query_params = $request->query();
        $user = $request->user();
        $request_params['page[size]'] = $query_params['size'] ?? 5;

        if (isset($query_params['next'])) {
            $request_params['page[after]'] = $query_params['next'];
        }

        if (isset($query_params['prev'])) {
            $request_params['page[before]'] = $query_params['prev'];
        }

        $client = new UpApi($user);
        $raw_accounts = $client->getAccounts($request_params);

        $transformer = new AccountTransformer($user);
        $accounts = $transformer->transform($raw_accounts);

        return response()->json([
            'data' => $accounts,
            'page_links' => Util::getPaginationKeys($raw_accounts['links']),
            'count' => $accounts->count()
        ]);
    }

    /**
     * @param Request $request 
     * @return JsonResponse 
     * @throws BindingResolutionException 
     */
    public function getNetValue(Request $request): JsonResponse
    {
        $user = $request->user();
        $accounts = Account::query()
            ->where('user_id', '=',  $user->id)
            ->get();

        $net_value = $accounts->sum('balance_base_unit_value');

        return response()->json([
            'status' => 200,
            'net_value' => $net_value,
            'accounts_count' => $accounts->count()
        ]);
    }

}
