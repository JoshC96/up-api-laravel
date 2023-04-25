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
use Illuminate\Support\Facades\Cache;

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
    public function store(StoreTransactionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
