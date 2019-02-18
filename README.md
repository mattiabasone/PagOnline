## PagOnline Library

This library can be used with PagOnline Payment Gateway

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
