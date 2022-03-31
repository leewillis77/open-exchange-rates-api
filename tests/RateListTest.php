<?php

namespace OpenExchangeRates;

use OpenExchangeRates\Request\RateListRequest;
use PHPUnit\Framework\TestCase;

require('tests/bootstrap.php');

class RateListTest extends TestCase
{
    private $config;
    private $request;

    public function setUp(): void
    {
        $this->config = new Config('config/config.yml');
        $this->request = new RateListRequest($this->config);
    }

    public function tearDown(): void
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
    }
}
