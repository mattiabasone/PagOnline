<?php

namespace Tests\Unit\Init;

use PagOnline\Init\IgfsCgVerify;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgVerifyTest extends IgfsCgBaseTest
{
    protected $igfsCgClass = IgfsCgVerify::class;

    /** @test */
    public function shouldChecksFieldsAndRaiseException()
    {
        $this->expectException(IgfsMissingParException::class);
        $foo = $this->getClassMethod('checkFields');
        $obj = new $this->igfsCgClass();
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldCheckFieldsAndPass()
    {
        /** @var \PagOnline\Init\IgfsCgVerify $obj */
        $obj = $this->makeIgfsCg();
        $obj->paymentID = 'paymentId';
        $foo = $this->getClassMethod('checkFields');

        $exception = null;
        try {
            $foo->invoke($obj);
        } catch (\Exception $exception) {
        }

        $this->assertNull($exception);
    }

    /** @test */
    public function shouldRaiseExceptionForMissingShopId()
    {
        $this->expectException(IgfsMissingParException::class);
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->notifyURL = 'https://example.com/verify/';
        $obj->errorURL = 'https://example.com/error/';
        $obj->shopID = null;
        $foo = $this->getClassMethod('checkFields');
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldReturnArray()
    {
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $array = $obj->toArray();
        $this->assertIsArray($array);
    }

    /** @test */
    public function shouldReturnServicePortString()
    {
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $foo = $this->getClassMethod('getServicePort');
        $this->assertIsString(
            $foo->invoke($obj)
        );
    }
}
