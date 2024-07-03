<?php

namespace PagOnline\Tests\Unit\Tran;

use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\Tests\Unit\IgfsCgBaseTestCase;
use PagOnline\Tran\IgfsCgVoidAuth;
use PagOnline\Tran\Requests\IgfsCgVoidAuthRequest;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgVoidAuthTest extends IgfsCgBaseTestCase
{
    protected $igfsCgClass = IgfsCgVoidAuth::class;
    protected $igfsCgRequest = IgfsCgVoidAuthRequest::CONTENT;

    public function testReturnRequestString(): void
    {
        $obj = new $this->igfsCgClass();
        $this->assertEquals($obj->getRequest(), $this->igfsCgRequest);
    }

    public function testChecksFieldsAndRaiseException(): void
    {
        $foo = $this->getClassMethod('checkFields');
        $obj = new $this->igfsCgClass();

        $this->expectException(IgfsMissingParException::class);
        $obj->langID = null;
        $foo->invoke($obj);
    }

    public function testCheckFieldsAndPass(): void
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

    public function testRaiseExceptionForMissingShopId(): void
    {
        $this->expectException(IgfsMissingParException::class);
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->shopID = null;
        $foo = $this->getClassMethod('checkFields');
        $foo->invoke($obj);
    }

    protected function setIgfsRequiredParamenters(&$class): void
    {
        $class->amount = 'AUTH';
        $class->refTranID = '12345678';
    }
}
