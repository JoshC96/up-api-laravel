<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiClient
{
    protected $url;
    protected $body;

    public function __construct($url = null, $body = null)
    {
        $this->url = $url;
        $this->body = $body;
    }

    /**
     * Send a GET request to the specified URL.
     *
     * @param array $params
     *
     * @return mixed
     */
    public function get(array $params = [])
    {
        return Http::get($this->url, $params)->throw()->json();
    }

    /**
     * Send a POST request to the specified URL.
     *
     * @param array $params
     *
     * @return mixed
     */
    public function post(array $params = [])
    {
        return Http::post($this->url, $params)->throw()->json();
    }

    /**
     * Send a PUT request to the specified URL.
     *
     * @param array $params
     *
     * @return mixed
     */
    public function put(array $params = [])
    {
        return Http::put($this->url, $params)->throw()->json();
    }

    /**
     * Send a PATCH request to the specified URL.
     *
     * @param array $params
     *
     * @return mixed
     */
    public function patch(array $params = [])
    {
        return Http::patch($this->url, $params)->throw()->json();
    }
}
