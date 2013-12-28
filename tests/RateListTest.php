<?php

namespace OpenExchangeRates;

use OpenExchangeRates\Request\RateListRequest;

require('tests/bootstrap.php');

class RateListTest extends \PHPUnit_Framework_TestCase
{
    private $config;
    private $request;

    public function __construct()
    {
        $this->config = new Config('config/config.yml');
    }

    public function setUp()
    {
        $this->request = new RateListRequest($this->config);
    }

    public function tearDown()
    {
        unset($this->request);
    }

    public function testRateListRequest()
    {
        try {
            $response = $this->request->get();
        } catch (\Exception $e) {
            $this->fail('Request exception received');
        }
        $this->assertNotEmpty($response);
        $this->assertInstanceOf('OpenExchangeRates\Entity\RateList', $response);
        print_r($response);
    }
}
