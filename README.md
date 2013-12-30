# PHP wrapper for [Open Exchange Rates](openexchangerates.org) API
A PHP wrapper around the [Open Exchange Rates](openexchangerates.org) API.

# Installation
* Clone the repository
* Make sure you have [composer](http://getcomposer.org/) set up and working
* Install dependencies by running `composer install`
* Copy the sample config file (`config/config.yml.sample`) to `config/config.yml`
* Update config/config.yml with your API credentials from "open exchange rates"
* API responses are cached in the cache/ subfolder - <strong>disallow access to this in your web server config</strong>.

# Notes
* Docblocks are missing in many places - patches welcome
* PHPUnit is set up, and some test cases are present. More would be welcome.
* Requires PHP 5.3 or above.

# Examples
See the examples folder for a simple example of how to use the API wrapper. This is designed to get you going, and is not a complete reference, or see the code sample below:

```php
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
```

# Disclaimer
This API is provided "as is", without warranty of any kind, express or implied, including but not limited to the warranties of merchantability, fitness for a particular purpose and noninfringement. In no event shall the the developer(s) be liable for any claim, damages or other liability, whether in an action of contract, tort or otherwise, arising from, out of or in connection with the software or the use or other dealings in the software.

Although every attempt is made to ensure quality, NO guarantees are given whatsoever of accuracy, validity, availability, or fitness for any purpose - please use at your own risk. 
