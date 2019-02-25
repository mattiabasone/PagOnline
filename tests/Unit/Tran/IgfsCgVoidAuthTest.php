<?php

namespace PagOnline\Tests\Unit\Tran;

use PagOnline\Tran\IgfsCgVoidAuth;
use PagOnline\Tests\Unit\IgfsCgBaseTest;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\Tran\Requests\IgfsCgVoidAuthRequest;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgVoidAuthTest extends IgfsCgBaseTest
{
    protected $igfsCgClass = IgfsCgVoidAuth::class;
    protected $igfsCgRequest = IgfsCgVoidAuthRequest::CONTENT;

    protected function setIgfsRequiredParamenters(&$class)
    {
        $class->amount = 'AUTH';
        $class->refTranID = '12345678';
    }

    /** @test */
    public function shouldReturnRequestString()
    {
        $obj = new $this->igfsCgClass();
        $this->assertEquals($obj->getRequest(), $this->igfsCgRequest);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseException()
    {
        $foo = $this->getClassMethod('checkFields');
        $obj = new $this->igfsCgClass();

        $this->expectException(IgfsMissingParException::class);
        $obj->langID = null;
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldCheckFieldsAndPass()
    {
        /** @var \PagOnline\Mpi\IgfsCgMpiAuth $obj */
        $obj = $this->makeIgfsCg();
        $foo = $this->getClassMethod('checkFields');
        $this->setIgfsRequiredParamenters($obj);
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
        $obj->shopID = null;
        $foo = $this->getClassMethod('checkFields');
        $foo->invoke($obj);
    }
}
