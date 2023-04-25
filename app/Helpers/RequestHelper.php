<?php

namespace App\Helpers; // Your helpers namespace 
use Illuminate\Http\Request;

class RequestHelper
{

    /**
     * 
     * @var string[][]
     */
    protected $allowedFields = [
        'id' => ['=', '<', '>'],
        'firstName' => ['=', 'like'],
        'lastName' => ['=', 'like'],
        'email' => ['=', 'like'], // would like to add an 'ilike' operator but doesn't work on MySQL
        'phone' => ['='],
        'electricBill' => ['=', '<', '>'],
    ];

    /**
     * 
     * @var string[]
     */
    protected $columnMap = [
        'electricBill' => 'electric_bill',
        'firstName' => 'first_name',
        'lastName' => 'last_name',
    ];

    /**
     * Handles when a user queries for specific field data. 
     * Example: 
     *      This URL will be tranformed into an eloquent query to fetch leads
     *      that are "like" josh@test.com.
     *      `/api/v1/leads?email[like]=josh@test.com`
     * Fields and Operators can be found in $allowedFields
     * 
     * @param Request $request 
     * @return array 
     */
    public function transform(Request $request): array
    {
        $eloQuery = [];

        foreach ($this->allowedFields as $field => $operators) {
            $query = $request->query($field);

            if (!isset($query)) continue;

            $column = $this->columnMap[$field] ?? $field;

            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $eloQuery[] = [$column, $operator, $query[$operator]];
                }
            }
        }

        return $eloQuery;
    }
}
