<?php

namespace OpenExchangeRates\Request;

use OpenExchangeRates\Config;
use OpenExchangeRates\Exception;
use OpenExchangeRates\Request\RateListRequest;

class ConversionRequest
{

	private $config;

    /**
     * Constructor. Store the config for subsequent requests.
     *
     * @param Config $config An OpenExchangeRates config object
     */
    public function __construct(Config $config)
    {
    	$this->config = $config;
    }

    /**
     * Convert an amount from a specified input currency to the specified target currency.
     *
     * @param  float  $amount The amount to be converted.
     * @param  string $from   The currency code of the input amount.
     * @param  string $to     The currency code of the requested output.
     * @return float          The converted currency.
     */
    public function convert($amount, $from, $to)
    {
        if (empty($from) || empty($to)) {
            throw new Exception('Invalid currency requested');
        }
        $rate_list_request = new RateListRequest($this->config);
        $rate_list = $rate_list_request->get();

        if (!isset($rate_list->rates->$from) ||
            !isset($rate_list->rates->$to)) {
            throw new Exception('Invalid currency requested');
        }

        // Converting from the base currency
        if ($rate_list->base == $from) {
            $factor = $rate_list->rates->$to;
            $result = $amount * $factor;
        } elseif ($rate_list->base == $to) {
            $factor = 1 / $rate_list->rates->$from;
            $result = $amount * $factor;
        } else {
            $amount_in_base = $this->convert($amount, $from, $rate_list->base);
            $factor = $rate_list->rates->$to;
            $result = $amount_in_base * $factor;
        }
        return $result;
    }
}
