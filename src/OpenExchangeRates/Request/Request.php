<?php

namespace OpenExchangeRates\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use OpenExchangeRates\Config;
use OpenExchangeRates\Exception;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Request
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var FilesystemAdapter
     */
    private $cache;

    //@TODO Allow config to set default use_cache, but allow requests to bypass cache if required

    /**
     * @var string
     */
    protected $endpoint = 'openexchangerates.org/api/';

    /**
     * Constructor. Store the config, set up a Guzzle client, and cache object.
     * Also updates the endpoint based on the config's use_ssl value.
     *
     * @param  Config  $config  An OpenExchangeRates\Config object.
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->client = new Client();
        $this->cache  = new FilesystemAdapter(
            'leewillis77-open-exchange-rates-api',
            0,
            $this->config->cache_dir
        );
        if ($this->config->use_ssl) {
            $this->endpoint = 'https://' . $this->endpoint;
        } else {
            $this->endpoint = 'http://' . $this->endpoint;
        }
    }

    /**
     * Helper function that makes an HTTP GET request.
     *
     * @param  string  $path  Additional URL fragments to be appended to the base endpoint.
     *
     * @return mixed        The response from the request.
     */
    public function get($path, $args = [])
    {
        $args['app_id'] = $this->config->app_id;
        $url            = $this->endpoint . $path . '?' . http_build_query($args);
        $request        = new Psr7Request('GET', $url);

        return $this->getFromCacheOrSend($request);
    }

    /**
     * Get the results from the cache, or make a fresh request if not already in the cache.
     * Results will be added to the cache if they are retrieved.
     *
     * @param  Request  $request  The Guzzle HTTP Request object.
     * @param  array  $args
     *
     * @return mixed            The response to the request.
     * @throws Exception
     */
    private function getFromCacheOrSend(Psr7Request $request)
    {
        // Only GET requests are cached. Other methods are invoked directly.
        if ($request->getMethod() != 'GET') {
            throw new \Exception('Invalid cache request');
        }

        // @TODO - check if this request wants caching, or if the global config wants caching
        // If not, bail here.

        // Generate a cache key
        $cacheKey = md5($request->getRequestTarget());

        // If it's in the cache, grab it and return it
        $cacheItem = $this->cache->getItem($cacheKey);
        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }
        // Otherwise send the request, cache it, and return it.
        $cacheItem->set($this->doSend($request));
        $cacheItem->expiresAfter(60 * 60 * 12);
        $this->cache->save($cacheItem);

        return $cacheItem->get();
    }

    /**
     * Make a request, and return the response.
     *
     * @param  Psr7Request  $request  The Guzzle HTTP Request object.
     *
     * @return mixed            The response to the request.
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function doSend(Psr7Request $request)
    {
        try {
            $response = $this->client->send($request);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e, $e->getResponse());
        }
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 200 && $statusCode < 300) {
            $body = $response->getBody();
            $body = json_decode($body);

            return $body;
        } else {
            throw new Exception('Unexpected API response', 0, null, $response);
        }
    }
}
