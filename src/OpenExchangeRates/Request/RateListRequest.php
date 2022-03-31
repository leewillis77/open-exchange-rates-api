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
    }

    /**
     * Get the latest rate list.
     *
     * @return RateList A RateList object representing the current rates.
     */
    public function get($args = array())
    {
        $response = $this->request->get('latest.json');
        if ($response) {
            return new RateList($response);
        } else {
            return $response;
        }
    }
}
