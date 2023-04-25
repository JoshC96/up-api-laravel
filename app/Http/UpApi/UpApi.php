<?php

namespace App\Http\UpApi;

use App\Services\ApiClient;
use Illuminate\Support\Facades\Http;

class UpApi extends ApiClient
{
    protected $token;

    public function __construct($token, $url = null, $body = null)
    {
        $baseUrl = env('UP_API_URL');
        $url = $url ?? $baseUrl;

        parent::__construct($url, $body);

        $this->token = $token;
    }

    /**
     * Send a GET request to the specified URL endpoint with the bearer token added to headers.
     *
     * @param array $params
     *
     * @return mixed
     */
    private function makeRequest(string $endpoint, array $params = [])
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json'
        ])->get($this->url . $endpoint, $params)->throw()->json();
    }

    
    public function getAccounts(array $params = [])
    {
        return $this->makeRequest('/accounts', $params);
    }

    public function getCategories(array $params = [])
    {
        return $this->makeRequest('/categories', $params);
    }

    public function getTransactions(array $params = [])
    {
        return $this->makeRequest('/transactions', $params);
    }



}
