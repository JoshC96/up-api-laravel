<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'remoteId' => $this->remote_id,
            'status' => $this->status,
            'rawText' => $this->raw_text,
            'description' => $this->description,
            'message' => $this->message,
            'isCategorizable' => $this->is_categorizable,
            'amount' => [
                'currencyCode' => $this->amount_currency_code,
                'value' => $this->amount_value,
                'baseUnitValue' => $this->amount_base_unit_value,
            ],
            'foreignAmount' => [
                'currencyCode' => $this->foreign_currency_code,
                'value' => $this->foreign_value,
                'baseUnitCalue' => $this->foreign_base_unit_value,
            ],
            'holdinfoAmount' => [
                'currencyCode' => $this->holdinfo_amount_currency_code,
                'value' => $this->holdinfo_amount_value,
                'baseUnitValue' => $this->holdinfo_amount_base_unit_value,
            ],
            'holdinfo_foreign_amount' => [
                'currencyCode' => $this->holdinfo_foreign_currency_code,
                'value' => $this->holdinfo_foreign_value,
                'baseUnitValue' => $this->holdinfo_foreign_base_unit_value,
            ],
            'roundup_amount' => [
                'currencyCode' => $this->roundup_amount_currency_code,
                'value' => $this->roundup_amount_value,
                'baseUnitValue' => $this->roundup_amount_base_unit_value,
            ],
            'roundup_boost_portion' => [
                'currencyCode' => $this->roundup_boost_portion_currency_code,
                'value' => $this->roundup_boost_portion_value,
                'baseUnitValue' => $this->roundup_boost_portion_base_unit_value,
            ],
            'cashback' => [
                'description' => $this->cashback_description,
                'amount' => [
                    'currencyCode' => $this->cashback_amount_currency_code,
                    'value' => $this->cashback_amount_value,
                    'baseUnitValue' => $this->cashback_amount_base_unit_value,
                ],
            ],
            'cardPurchaseMethod' => [
                'method' => $this->card_purchase_method,
                'cardNumberSuffix' => $this->card_number_suffix,
            ],
            'settledAt' => $this->remote_settled_at,
            'createdAt' => $this->remote_created_at,
            'accountId' => $this->account_id,
            'transferAccountId' => $this->transfer_account_id,
            'categoryId' => $this->category_id,
            'parentCategoryId' => $this->parent_category_id,
            'userId' => $this->user_id,
        ];
    }
}
