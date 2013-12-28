<?php

namespace OpenExchangeRates\Request;

use Guzzle\Http\Client;
use OpenExchangeRates\Config;
use OpenExchangeRates\Exception;
use Doctrine\Common\Cache\FilesystemCache;

class Request
{
    private $client;
    private $args;
    private $cache;
    //@TODO Allow config to set default use_cache, but allow requests to bypass cache if required
    protected $endpoint = 'openexchangerates.org/api/';

    /**
     * Constructor. Store the config, set up a Guzzle client, and cache object.
     * Also updates the endpoint based on the config's use_ssl value.
     *
     * @param Config $config An OpenExchangeRates\Config object.
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->client = new Client();
        $this->cache = new FilesystemCache($this->config->cache_dir, '.txt');
        if ($this->config->use_ssl) {
            $this->endpoint = 'https://' . $this->endpoint;
        } else {
            $this->endpoint = 'http://' . $this->endpoint;
        }
    }

    /**
     * Set the base endpoint for this request.
     *
     * @param string $endpoint The endpoint for the request.
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint .= $endpoint;
    }

    /**
     * Store arguments so that they can be applied to the request when it exists.
     *
     * @param Array $args The args to be added to the request.
     */
    public function setArgs($args)
    {
        $this->args = $args;
    }

    /**
     * Invoke a request.
     *
     * @param  string $method The HTTP method to be used. Currently only GET is supported here.
     * @param  string $path   Additional URL fragments to be appended to the base endpoint.
     * @return mixed          The response from the request.
     */
    public function send($method, $path)
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

    /**
     * Helper function that makes an HTTP GET request.
     *
     * @param  string $path Additional URL fragments to be appended to the base endpoint.
     * @return mixed        The response from the request.
     */
    private function get($path)
    {
        $request = $this->client->get($this->endpoint . $path);
        $this->applyArgs($request);
        return $this->getFromCacheOrSend($request);
    }


    /**
     * Apply stored arguments to the request.
     *
     * @param  Request $request The Guzzle HTTP Request object.
     */
    private function applyArgs($request)
    {
        // Add API key to request
        $query = $request->getQuery();
        $query->set('app_id', $this->config->app_id);

        if (!count($this->args)) {
            return;
        }
        foreach ($this->args as $key => $value) {
            if ($value === null) {
                return;
            }
            $query->set($key, $value);
        }
    }

    /**
     * Get the results from the cache, or make a fresh request if not already in the cache.
     * Results will be added to the cache if they are retrieved.
     * @param  Request $request The Guzzle HTTP Request object.
     * @return mixed            The response to the request.
     */
    private function getFromCacheOrSend($request)
    {
        // Only GET requests are cached. Other methods are invoked directly.
        if ($request->getMethod() != 'GET') {
            return doSend($request);
        }

        // @TODO - check if this request wants caching, or if the global config wants caching
        // If not, bail here.

        // Generate a cache key
        $cache_key = $request->getUrl();

        // If it's in the cache, grab it and return it
        $result = $this->cache->fetch($cache_key);
        if ($result) {
            return $result;
        }
        $result = $this->doSend($request);
        $this->cache->save($cache_key, $result, 60 * 60 * 12);
        return $result;
    }

    /**
     * Make a request, and return the response.
     *
     * @param  Request $request The Guzzle HTTP Request object.
     * @return mixed            The response to the request.
     */
    private function doSend($request)
    {
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
