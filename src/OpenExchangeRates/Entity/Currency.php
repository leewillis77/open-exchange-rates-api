<?php

namespace OpenExchangeRates\Entity;

/**
 * A currency code and description.
 * E.g. http://openexchangerates.org/api/currencies.json
 *
 * @extends OpenExchangeRates\Entity
 */
class Currency extends Entity
{
    protected $code;
    protected $description;
}
