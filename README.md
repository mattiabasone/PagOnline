## PagOnline Library

This library can be used with PagOnline Payment Gateway

[![Build Status](https://travis-ci.org/mattiabasone/PagOnline.svg?branch=master)](https://travis-ci.org/mattiabasone/PagOnline)

**Work in Progress - DO NOT USE IN PRODUCTION**

### Usage

Demo scripts can be found in `tests/demo`, example Init Request:

```php
<?php 

require __DIR__.'/vendor/autoload.php';

$init = new \PagOnline\Init\IgfsCgInit();
$init->serverURL = "https://payment-gateway-example.com/IGFS_CG_SERVICES/services";
$init->tid = "MY-TID-CODE";
$init->kSig = '1234567890987654321';
$init->timeout = 15000;
$init->shopID = 'my-transaction-id';
$init->shopUserRef = "email@example.org";
$init->trType = "AUTH";
$init->currencyCode = "EUR";
$init->amount = 500; // Amount without comma (500 = 5,00)
$init->langID = "IT";
$init->notifyURL = "http://my-domain.tld/verify.php";
$init->errorURL = "http://my-domain.tld/error.php";
$init->addInfo1 = 'myFirstAddintionalInfo';

if (!$init->execute()) {
    // Something went wrong
} else {
    // Redirect user to payment gateway
    header("location: ".$init->redirectURL);
}
```

### Laravel Package

Using Laravel auto-discovery feature you don't need to do that, anyway you can use artisan command `vendor:publish`  
to copy `pagonline.php` config file

`php artisan vendor:publish --provider="PagOnline\Laravel\PagOnlineServiceProvider"`

Set the following environment variables in your `.env` file:
- `PAGONLINE_SERVER_URL` payment gateway server url (_default: null_)
- `PAGONLINE_TIMEOUT` maximum timeout in milliseconds for completing a request (_default: 15000_)
- `PAGONLINE_TERMINAL_ID` identifier provided by the payment gateway (_default: null_)
- `PAGONLINE_SIGNATURE_KEY` signature key provided by the payment gateway (_default: null_)
- `PAGONLINE_CURRENCY_CODE` currency code (_default: EUR_)
- `PAGONLINE_LANGUAGE_ID` language code (_default: IT_)

### TODO
- [ ] Unit Tests
