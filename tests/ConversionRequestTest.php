<?php

namespace OpenExchangeRates;

use OpenExchangeRates\Request\ConversionRequest;

require('tests/bootstrap.php');

class ConversionRequestTest extends \PHPUnit_Framework_TestCase
{
    private $config;
    private $request;

    public function __construct()
    {
        $this->config = new Config('config/config.yml');
    }

    public function setUp()
    {
        $this->request = new ConversionRequest($this->config);
    }

    public function tearDown()
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
}
