<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'remote_id',
        'display_name',
        'account_type',
        'ownership_type',
        'balance_currency_code',
        'balance_value',
        'balance_base_unit_value',
        'remote_created_at'
    ];

    /**
     * @return HasMany 
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
