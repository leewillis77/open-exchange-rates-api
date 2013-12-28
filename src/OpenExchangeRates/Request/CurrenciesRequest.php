<?php

namespace OpenExchangeRates\Request;

use OpenExchangeRates\Config;
use OpenExchangeRates\Collection\CurrencyCollection;

class CurrenciesRequest
{
    private $request;

    /**
     * Constructor. Set the default endpoint, and create a request ready for processing.
     *
     * @param Config $config An OpenExchangeRates config object
     */
    public function __construct(Config $config)
    {
        $this->request = new Request($config);
        $this->request->setEndpoint('currencies.json');
    }

    /**
     * Get the latest currency list.
     *
     * @return CurrencyList A CurrencyList object representing the known currencies.
     */
    public function get($args = array())
    {
        $this->request->setArgs($args);
        $response = $this->request->send('get', '');
        if ($response) {
            return new CurrencyCollection($response);
        } else {
            return $response;
        }
    }
}
