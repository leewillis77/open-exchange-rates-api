<?php

namespace OpenExchangeRates;

use OpenExchangeRates\Request\CurrenciesRequest;
use PHPUnit\Framework\TestCase;


require('tests/bootstrap.php');

class CurrenciesRequestTest extends TestCase
{
    private $config;
    private $request;

    public function setUp(): void
    {
        $this->config = new Config('config/config.yml');
        $this->request = new CurrenciesRequest($this->config);
    }

    public function tearDown(): void
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
