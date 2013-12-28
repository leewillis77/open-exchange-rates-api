<?php

namespace OpenExchangeRates\Collection;

/**
 * A collection of OpenExchangeRates\Entity\Currency objects.
 *
 * @extends OpenExchangeRates\Collection
 */
class CurrencyCollection extends Collection
{
    protected $entity_type = 'OpenExchangeRates\Entity\Currency';

    /**
     * We override the parent constructor to get the data into a suitable format
     * for assembling into a collection.
     * @param object $input A single object, with a property per currency.
     */
    public function __construct($input)
    {
        $input = (array)$input;
        $temp = array();
        foreach ($input as $code => $description) {
            $temp[] = array('code' => $code, 'description' => $description);
        }
        $collection = array_map(array($this, 'callback'), $temp);
        $this->collection = $collection;
        $this->position = 0;
    }

}
