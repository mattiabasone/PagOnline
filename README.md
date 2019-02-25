## PagOnline Library

This library can be used with PagOnline Payment Gateway and it can be easily integrated in Laravel.

With this package I'm trying to improve the crappy IGFS CG PHP Library provided by PagOnline Payment gateway.

[![Build Status](https://travis-ci.org/mattiabasone/PagOnline.svg?branch=master)](https://travis-ci.org/mattiabasone/PagOnline)
[![Coverage Status](https://coveralls.io/repos/github/mattiabasone/PagOnline/badge.svg)](https://coveralls.io/github/mattiabasone/PagOnline)

### Basic Usage

Demo scripts can be found in `tests/demo`, example Init Request:

```php
<?php 

require __DIR__.'/vendor/autoload.php';

$init = new \PagOnline\Init\IgfsCgInit();
$init->serverURL = "https://payment-gateway-example.com/IGFS_CG_SERVICES/services";
$init->tid = "MY-TID-CODE";
$init->kSig = '1234567890987654321';
$init->shopID = 'my-transaction-id';
$init->shopUserRef = "email@example.org";
$init->trType = "AUTH";
$init->currencyCode = "EUR";
$init->amount = 500; // Amount without comma (500 = 5,00)
$init->langID = "IT";
$init->notifyURL = "http://my-domain.tld/verify.php";
$init->errorURL = "http://my-domain.tld/error.php";
$init->addInfo1 = 'myFirstAddintionalInfo';

// if you need to edit http client parameters...
$init->setRequestTimeout(10); // Seconds
$init->setConnectTimeout(5); // Seconds
$init->setHttpProxy('tcp://some.proxy'); // Proxy server for requests
$init->setHttpAuthUser('username'); // HTTP Basic Auth username
$init->setHttpAuthPass('password'); // HTTP Basic Auth password

if (!$init->execute()) {
    // Something went wrong
} else {
    // Redirect user to payment gateway
    header("location: ".$init->redirectURL);
}
```

### Usage in Laravel

Using Laravel 5.5+, the PagOnline service provider is automagically loaded at startup. 
It loads the config file and the Factory Facade that can be used to create IgfsCg class instances.

Using Laravel auto-discovery feature you don't need to do that, anyway you can use artisan command `vendor:publish`  
to copy `pagonline.php` config file for older Laravel versions:

`php artisan vendor:publish --provider="PagOnline\Laravel\PagOnlineServiceProvider"`

You also need to register the PagOnline Service Provider (`PagOnline\Laravel\PagOnlineServiceProvider`).

#### Facade usage

```php
use IgfsCg;
use PagOnline\Actions;

[...]

class MyController 
{
    public function mySuperMethod()
    {
        $igfsCgInit = IgfsCg::make(Actions::IGFS_CG_INIT);
        // Do something
    }
}
```

#### .env file configuration
Set the following environment variables in your `.env` file:
- `PAGONLINE_SERVER_URL` payment gateway server url (_default: null_)
- `PAGONLINE_REQUEST_TIMEOUT` maximum timeout in seconds for completing a request (_default: 15_)
- `PAGONLINE_CONNECT_TIMEOUT` maximum timeout in seconds for connecting to the server (_default: 5_)
- `PAGONLINE_TERMINAL_ID` identifier provided by the payment gateway (_default: null_)
- `PAGONLINE_SIGNATURE_KEY` signature key provided by the payment gateway (_default: null_)
- `PAGONLINE_CURRENCY_CODE` currency code (_default: EUR_)
- `PAGONLINE_LANGUAGE_ID` language code (_default: IT_)

### TODO

- [ ] Unit Tests

### Contributing

If you want to contribute to this project, please use php-cs-fixer to format your code to PSR standards and rules 
specified in the configuration file `.php_cs.dist` provided in this repository.
Thank you!