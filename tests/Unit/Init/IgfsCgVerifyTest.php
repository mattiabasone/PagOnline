<?php

namespace Tests\Unit\Init;

use PagOnline\Actions;
use PagOnline\Init\IgfsCgVerify;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgVerifyTest extends IgfsCgBaseTest
{
    const IGFSCG_CLASS = IgfsCgVerify::class;
    const IGFSCG_ACTION = Actions::IGFS_CG_VERIFY;

    /** @test */
    public function shouldChecksFieldsAndRaiseException()
    {
        $this->expectException(IgfsMissingParException::class);
        $foo = $this->getClassMethod('checkFields');
        $obj = new IgfsCgVerify();
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldCheckFieldsAndPass()
    {
        /** @var \PagOnline\Init\IgfsCgVerify $obj */
        $obj = $this->makeIgfsCg(static::IGFSCG_ACTION);
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
        $obj = $this->makeIgfsCg(static::IGFSCG_ACTION);
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
        $obj = $this->makeIgfsCg(static::IGFSCG_ACTION);
        $array = $obj->toArray();
        $this->assertIsArray($array);
    }

    /** @test */
    public function shouldReturnServicePortString()
    {
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg(static::IGFSCG_ACTION);
        $foo = $this->getClassMethod('getServicePort');
        $this->assertIsString(
            $foo->invoke($obj)
        );
    }
}
