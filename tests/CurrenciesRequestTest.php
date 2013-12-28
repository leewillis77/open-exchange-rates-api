<?php

namespace OpenExchangeRates;

use OpenExchangeRates\Request\CurrenciesRequest;

require('tests/bootstrap.php');

class CurrenciesRequestTest extends \PHPUnit_Framework_TestCase
{
    private $config;
    private $request;

    public function __construct()
    {
        $this->config = new Config('config/config.yml');
    }

    public function setUp()
    {
        $this->request = new CurrenciesRequest($this->config);
    }

    public function tearDown()
    {
        unset($this->request);
    }

    public function testCurrenciesRequest()
    {
        try {
            $response = $this->request->get();
        } catch (\Exception $e) {
            $this->fail('Request exception received');
        }
        $this->assertNotEmpty($response);
        $this->assertInstanceOf('OpenExchangeRates\Collection\CurrencyCollection', $response);
    }
}
