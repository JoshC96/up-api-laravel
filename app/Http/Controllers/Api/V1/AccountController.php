<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreAccountRequest;
use App\Http\Requests\V1\UpdateAccountRequest;
use App\Http\UpApi\Transformers\AccountTransformer;
use App\Http\UpApi\UpApi;
use App\Http\UpApi\Util;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query_params = $request->query();
        $request_params['page[size]'] = $query_params['size'] ?? 5;

        if (isset($query_params['next'])) {
            $request_params['page[after]'] = $query_params['next'];
        }

        if (isset($query_params['prev'])) {
            $request_params['page[before]'] = $query_params['prev'];
        }

        // $client = new UpApi(Auth::getUser()->up_bank_token);
        $client = new UpApi(env('UP_TEST_TOKEN'));
        $raw_accounts = $client->getAccounts($request_params);

        $transformer = new AccountTransformer();
        $accounts = $transformer->transform($raw_accounts);

        return response()->json([
            'data' => $accounts,
            'page_links' => Util::getPaginationKeys($raw_accounts['links']),
            'count' => $accounts->count()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        //
    }
}
