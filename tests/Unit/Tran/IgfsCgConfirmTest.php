<?php

namespace PagOnline\Tests\Unit\Tran;

use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\Tests\Unit\IgfsCgBaseTest;
use PagOnline\Tran\IgfsCgConfirm;
use PagOnline\Tran\Requests\IgfsCgConfirmRequest;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgConfirmTest extends IgfsCgBaseTest
{
    protected $igfsCgClass = IgfsCgConfirm::class;
    protected $igfsCgRequest = IgfsCgConfirmRequest::CONTENT;

    /** @test */
    public function shouldChecksFieldsAndRaiseException(): void
    {
        $foo = $this->getClassMethod('checkFields');
        $obj = new $this->igfsCgClass();

        $this->expectException(IgfsMissingParException::class);
        $obj->langID = null;
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldCheckFieldsAndPass(): void
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
    public function shouldRaiseExceptionForMissingShopId(): void
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
        $class->amount = 500;
        $class->refTranID = '123456789';
    }
}
