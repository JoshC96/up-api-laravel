<?php

namespace App\Http\UpApi\Transformers;

use App\Http\Resources\V1\AccountCollection;
use App\Models\Account;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AccountTransformer
{
    public function transform(array $data): AccountCollection
    {
        $transformedData = [];

        foreach ($data['data'] as $accountData) {
            $account = Account::firstOrNew([
                'id' => $accountData['id'],
            ]);

            // if ($account->wasRecentlyCreated) {
                $this->populateAccountData($account, $accountData);
            // }

            $transformedData[] = $account;
        }

        return new AccountCollection($transformedData);
    }

    private function populateAccountData(Account $account, array $accountData): Account
    {
        $account->id = Arr::get($accountData, 'id');
        $account->remote_id = Arr::get($accountData, 'id');
        $account->display_name = Arr::get($accountData, 'attributes.displayName');
        $account->account_type = Arr::get($accountData, 'attributes.accountType');
        $account->ownership_type = Arr::get($accountData, 'attributes.ownershipType');

        $account->balance_currency_code = Arr::get($accountData, 'attributes.balance.currencyCode');
        $account->balance_value = Arr::get($accountData, 'attributes.balance.value');
        $account->balance_base_unit_value = Arr::get($accountData, 'attributes.balance.valueInBaseUnits');

        $account->remote_created_at = Carbon::createFromFormat('Y-m-d\TH:i:sP', 
            Arr::get($accountData, 'attributes.createdAt')
        )->toDateTimeString();


        $account->user_id = Auth::getUser() ? 
            Auth::getUser()->id : 
            User::where('email', 'josh.campbell4000@gmail.com')->first()->id;

        $account->save();

        return $account;
    }
}
