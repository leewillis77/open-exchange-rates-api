<?php

namespace OpenExchangeRates\Request;

use Guzzle\Http\Client;
use OpenExchangeRates\Config;
use OpenExchangeRates\Exception;

class Request
{
    private $client;
    private $args;
    protected $endpoint = 'openexchangerates.org/api/';

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->client = new Client();
        if ($this->config->use_ssl) {
            $this->endpoint = 'https://' . $this->endpoint;
        } else {
            $this->endpoint = 'http://' . $this->endpoint;
        }
    }

    public function setEndpoint($endpoint)
    {
        $this->endpoint .= $endpoint;
    }

    public function setArgs($args)
    {
        $this->args = $args;
    }

    public function send($method, $path, $data = null)
    {
        switch($method) {
            case 'get':
                return $this->get($path);
            break;
            default:
                throw new \Exception('Invalid request type');
            break;
        }
    }

    private function get($path)
    {
        $request = $this->client->get($this->endpoint . $path);
        $this->applyArgs($request);
        return $this->doSend($request);
    }


    private function applyArgs($request)
    {
        if (!count($this->args)) {
            return;
        }
        $query = $request->getQuery();
        foreach ($this->args as $key => $value) {
            if ($value === null) {
                return;
            }
            $query->set($key, $value);
        }
    }

    private function doSend($request)
    {
        // Add API key to request
        $query = $request->getQuery();
        $query->set('app_id', $this->config->app_id);
        try {
            $response = $request->send();
        } catch (\Guzzle\Http\Exception\BadResponseException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e, $e->getResponse());
        }
        if ($response->isSuccessful()) {
            $body = $response->getBody();
            $body = json_decode($body);
            return $body;
        } else {
            throw new Exception('Unexpected API response', 0, null, $response);
        }
    }
}
