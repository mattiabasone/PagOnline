<?php

namespace PagOnline\Tests\Unit\Tran;

use PagOnline\Tran\IgfsCgAuth;
use PagOnline\Tests\Unit\IgfsCgBaseTest;
use PagOnline\Tran\Requests\IgfsCgAuthRequest;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgAuthTest extends IgfsCgBaseTest
{
    protected $igfsCgClass = IgfsCgAuth::class;
    protected $igfsCgRequest = IgfsCgAuthRequest::CONTENT;

    protected function setIgfsRequiredParamenters(&$class)
    {
        $class->trType = 'AUTH';
        $class->amount = 500;
        $class->currencyCode = 'EUR';
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingTrType()
    {
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing trType');
        $checkFieldsMethod = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->trType = null;
        $checkFieldsMethod->invoke($obj);
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
