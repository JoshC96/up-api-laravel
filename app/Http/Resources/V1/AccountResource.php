<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'remoteId' => $this->remote_id,
            'displayName' => $this->display_name,
            'type' => $this->account_type,
            'ownershipType' => $this->ownership_type,
            'balance' => [
                'currencyCode' => $this->balance_currency_code,
                'value' => $this->balance_value,
                'baseUnitValue' => $this->balance_base_unit_value,
            ],
            'createdAt' => $this->remote_created_at,
            'userId' => $this->user_id,
        ];
    }
}
