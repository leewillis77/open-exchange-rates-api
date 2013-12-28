<?php

namespace OpenExchangeRates\Entity;

/**
 * Response from a rate list API call
 * E.g. http://openexchangerates.org/api/latest.json
 *
 * @extends OpenExchangeRates\Entity
 */
class RateList extends Entity
{
    protected $disclaimer;
    protected $license;
    protected $timestamp;
    protected $base;
    protected $rates;
}
