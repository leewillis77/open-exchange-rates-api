<?php

use OpenExchangeRates\Config;
use OpenExchangeRates\Request\ConversionRequest;

require('vendor/autoload.php');

$config = new Config('config/config.yml');
$request = new ConversionRequest($config);

try {
    $response = $request->convert(100, 'USD', 'GBP');
} catch (\Exception $e) {
    die('Request exception received: '.$e->getMessage());
}
echo "100USD is " . $response . "GBP\n";
