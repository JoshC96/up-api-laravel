<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'remote_id',
        'status',
        'raw_text',
        'description',
        'message',
        'is_categorizable',
        'amount_currency_code',
        'amount_value',
        'amount_base_unit_value',
        'foreign_currency_code',
        'foreign_value',
        'foreign_base_unit_value',
        'holdinfo_amount_currency_code',
        'holdinfo_amount_value',
        'holdinfo_amount_base_unit_value',
        'holdinfo_foreign_currency_code',
        'holdinfo_foreign_value',
        'holdinfo_foreign_base_unit_value',
        'roundup_amount_currency_code',
        'roundup_amount_value',
        'roundup_amount_base_unit_value',
        'roundup_boost_portion_currency_code',
        'roundup_boost_portion_value',
        'roundup_boost_portion_base_unit_value',
        'cashback_description',
        'cashback_amount_currency_code',
        'cashback_amount_value',
        'cashback_amount_base_unit_value',
        'card_purchase_method',
        'card_number_suffix',
        'remote_settled_at',
        'remote_created_at',
        'account_id',
        'transfer_account_id',
        'category_id',
        'parent_category_id',
        'user_id'
    ];

    public function toArray()
    {
        return [
            'id' => $this->id,
            'remote_id' => $this->remote_id,
            'status' => $this->status,
            'raw_text' => $this->raw_text,
            'description' => $this->description,
            'message' => $this->message,
            'is_categorizable' => $this->is_categorizable,
            'amount' => [
                'currency_code' => $this->amount_currency_code,
                'value' => $this->amount_value,
                'base_unit_value' => $this->amount_base_unit_value,
            ],
            'foreign_amount' => [
                'currency_code' => $this->foreign_currency_code,
                'value' => $this->foreign_value,
                'base_unit_value' => $this->foreign_base_unit_value,
            ],
            'holdinfo_amount' => [
                'currency_code' => $this->holdinfo_amount_currency_code,
                'value' => $this->holdinfo_amount_value,
                'base_unit_value' => $this->holdinfo_amount_base_unit_value,
            ],
            'holdinfo_foreign_amount' => [
                'currency_code' => $this->holdinfo_foreign_currency_code,
                'value' => $this->holdinfo_foreign_value,
                'base_unit_value' => $this->holdinfo_foreign_base_unit_value,
            ],
            'roundup_amount' => [
                'currency_code' => $this->roundup_amount_currency_code,
                'value' => $this->roundup_amount_value,
                'base_unit_value' => $this->roundup_amount_base_unit_value,
            ],
            'roundup_boost_portion' => [
                'currency_code' => $this->roundup_boost_portion_currency_code,
                'value' => $this->roundup_boost_portion_value,
                'base_unit_value' => $this->roundup_boost_portion_base_unit_value,
            ],
            'cashback' => [
                'description' => $this->cashback_description,
                'amount' => [
                    'currency_code' => $this->cashback_amount_currency_code,
                    'value' => $this->cashback_amount_value,
                    'base_unit_value' => $this->cashback_amount_base_unit_value,
                ],
            ],
            'card_purchase_method' => [
                'method' => $this->card_purchase_method,
                'cardNumberSuffix' => $this->card_number_suffix,
            ],
            'settled_at' => $this->remote_settled_at,
            'created_at' => $this->remote_created_at,
            'account_id' => $this->account_id,
            'transfer_account_id' => $this->transfer_account_id,
            'category_id' => $this->category_id,
            'parent_category_id' => $this->parent_category_id,
            'user_id' => $this->user_id,
        ];
    }


    /**
     * @return BelongsTo 
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return HasOne 
     */
    public function category(): HasOne
    {
        return $this->hasOne(Category::class);
    }
    
}
