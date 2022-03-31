<?php

namespace OpenExchangeRates;

use OpenExchangeRates\Request\ConversionRequest;
use PHPUnit\Framework\TestCase;

require('tests/bootstrap.php');

class ConversionRequestTest extends TestCase
{
    private $config;
    private $request;

    public function setUp(): void
    {
        $this->config = new Config('config/config.yml');
        $this->request = new ConversionRequest($this->config);
    }

    public function tearDown(): void
    {
        unset($this->request);
    }

    public function testConversionRequest()
    {
        try {
            $response = $this->request->convert(100, 'USD', 'GBP');
            $response = $this->request->convert(100, 'GBP', 'USD');
            $response = $this->request->convert(100, 'EUR', 'GBP');
        } catch (\Exception $e) {
            $this->fail('Request exception received: '.$e->getMessage());
        }
    }

    public function testLowerCaseConversionRequest()
    {
    	try {
    	    $response = $this->request->convert(100, 'USD', 'gbp');
    	} catch (\Exception $e) {
    	    $this->fail('Request exception received: '.$e->getMessage());
    	}
    	try {
    	    $response = $this->request->convert(100, 'gbp', 'USD');
    	} catch (\Exception $e) {
    	    $this->fail('Request exception received: '.$e->getMessage());
    	}
    	try {
    	    $response = $this->request->convert(100, 'eur', 'gbp');
    	} catch (\Exception $e) {
    	    $this->fail('Request exception received: '.$e->getMessage());
    	}
    }
}
