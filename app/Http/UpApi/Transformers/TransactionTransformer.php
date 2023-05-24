<?php

namespace App\Http\UpApi\Transformers;

use App\Http\Resources\V1\TransactionCollection;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TransactionTransformer
{
    public function __construct(public User $user)
    {
        $this->user = $user;
    }

    public function transform(array $data): TransactionCollection
    {
        $transformedData = [];

        foreach ($data['data'] as $transactionData) {
            $transaction = Transaction::firstOrNew([
                'id' => $transactionData['id'],
            ]);

            // if ($transaction->wasRecentlyCreated) {
                $this->populateTransactionData($transaction, $transactionData);
            // }

            $transformedData[] = $transaction;
        }

        return new TransactionCollection($transformedData);
    }

    private function populateTransactionData(Transaction $transaction, array $transactionData): Transaction
    {
        $transaction->id = Arr::get($transactionData, 'id');
        $transaction->remote_id = Arr::get($transactionData, 'id');
        $transaction->status = Arr::get($transactionData, 'attributes.status');
        $transaction->raw_text = Arr::get($transactionData, 'attributes.rawText');
        $transaction->description = Arr::get($transactionData, 'attributes.description');
        $transaction->message = Arr::get($transactionData, 'attributes.message');
        $transaction->is_categorizable = Arr::get($transactionData, 'attributes.isCategorizable');

        $transaction->amount_currency_code = Arr::get($transactionData, 'attributes.amount.currencyCode');
        $transaction->amount_value = Arr::get($transactionData, 'attributes.amount.value');
        $transaction->amount_base_unit_value = Arr::get($transactionData, 'attributes.amount.valueInBaseUnits');

        $transaction->foreign_currency_code = Arr::get($transactionData, 'attributes.foreignAmount.currencyCode');
        $transaction->foreign_value = Arr::get($transactionData, 'attributes.foreignAmount.value');
        $transaction->foreign_base_unit_value = Arr::get($transactionData, 'attributes.foreignAmount.valueInBaseUnits');

        $transaction->holdinfo_amount_currency_code = Arr::get($transactionData, 'attributes.holdInfo.amount.currencyCode');
        $transaction->holdinfo_amount_value = Arr::get($transactionData, 'attributes.holdInfo.amount.value');
        $transaction->holdinfo_amount_base_unit_value = Arr::get($transactionData, 'attributes.holdInfo.amount.valueInBaseUnits');

        $transaction->holdinfo_foreign_currency_code = Arr::get($transactionData, 'attributes.holdInfo.foreignAmount.currencyCode');
        $transaction->holdinfo_foreign_value = Arr::get($transactionData, 'attributes.holdInfo.foreignAmount.value');
        $transaction->holdinfo_foreign_base_unit_value = Arr::get($transactionData, 'attributes.holdInfo.foreignAmount.valueInBaseUnits');

        $transaction->roundup_amount_currency_code = Arr::get($transactionData, 'attributes.roundUp.amount.currencyCode');
        $transaction->roundup_amount_value = Arr::get($transactionData, 'attributes.roundUp.amount.value');
        $transaction->roundup_amount_base_unit_value = Arr::get($transactionData, 'attributes.roundUp.amount.valueInBaseUnits');

        $transaction->roundup_boost_portion_currency_code = Arr::get($transactionData, 'attributes.roundUp.boostPortion.currencyCode');
        $transaction->roundup_boost_portion_value = Arr::get($transactionData, 'attributes.roundUp.boostPortion.value');
        $transaction->roundup_boost_portion_base_unit_value = Arr::get($transactionData, 'attributes.roundUp.boostPortion.valueInBaseUnits');

        $transaction->cashback_description = Arr::get($transactionData, 'attributes.cashback.description');
        $transaction->cashback_amount_currency_code = Arr::get($transactionData, 'attributes.cashback.amount.currencyCode');
        $transaction->cashback_amount_value = Arr::get($transactionData, 'attributes.cashback.amount.value');
        $transaction->cashback_amount_base_unit_value = Arr::get($transactionData, 'attributes.cashback.amount.valueInBaseUnits');

        
        $transaction->card_purchase_method = Arr::get($transactionData, 'attributes.cardPurchaseMethod.method');
        $transaction->card_number_suffix = Arr::get($transactionData, 'attributes.cardPurchaseMethod.card_number_suffix');

        if (Arr::get($transactionData, 'attributes.settledAt') !== null) {
            $transaction->remote_settled_at = Carbon::createFromFormat(
                'Y-m-d\TH:i:sP',
                Arr::get($transactionData, 'attributes.settledAt')
            )->toDateTimeString();   
        }

        $transaction->remote_created_at = Carbon::createFromFormat('Y-m-d\TH:i:sP', 
            Arr::get($transactionData, 'attributes.createdAt')
        )->toDateTimeString();

        $related_account = Account::firstWhere('remote_id', Arr::get($transactionData, 'relationships.account.data.id'));
        if ($related_account) {
            $transaction->account_id = $related_account->remote_id;
        }

        $transfer_account = Account::firstWhere('remote_id', Arr::get($transactionData, 'relationships.transferAccount.data.id'));
        if ($transfer_account) {
            $transaction->transfer_account_id = $transfer_account->remote_id;
        }

        $category = Category::firstWhere('remote_id', Arr::get($transactionData, 'relationships.category.data.id'));
        if ($category) {
            $transaction->category_id = $category->remote_id;
        }

        $parent_category = Category::firstWhere('remote_id', Arr::get($transactionData, 'relationships.parentCategory.data.id'));
        if ($parent_category) {
            $transaction->parent_category_id = $parent_category->remote_id;
        }

        $transaction->user_id = $this->user->id;
        $transaction->save();

        return $transaction;
    }
}
