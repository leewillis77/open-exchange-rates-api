<?php

namespace OpenExchangeRates\Request;

use OpenExchangeRates\Config;
use OpenExchangeRates\Entity\RateList;

class RateListRequest
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
        $this->request->setEndpoint('latest.json');
    }

    /**
     * Get the latest rate list.
     *
     * @return RateList A RateList object representing the current rates.
     */
    public function get($args = array())
    {
        $this->request->setArgs($args);
        $response = $this->request->send('get', '');
        if ($response) {
            return new RateList($response);
        } else {
            return $response;
        }
    }
}
