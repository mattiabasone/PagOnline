<?php

namespace PagOnline\Tests\Unit\Mpi;

use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\Mpi\IgfsCgMpiEnroll;
use PagOnline\Mpi\Requests\IgfsCgMpiEnrollRequest;
use PagOnline\Tests\Unit\IgfsCgBaseTestCase;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgMpiEnrollTest extends IgfsCgBaseTestCase
{
    protected $igfsCgClass = IgfsCgMpiEnroll::class;
    protected $igfsCgRequest = IgfsCgMpiEnrollRequest::CONTENT;

    public function testChecksFieldsAndRaiseExceptionMissingAmount(): void
    {
        $this->expectException(IgfsMissingParException::class);
        $foo = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $foo->invoke($obj);
    }

    public function testChecksFieldsAndRaiseExceptionMissingCurrencyCode(): void
    {
        $this->expectException(IgfsMissingParException::class);
        $foo = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->amount = 100;
        $foo->invoke($obj);
    }

    public function testChecksFieldsAndRaiseExceptionMissingPan(): void
    {
        $this->expectException(IgfsMissingParException::class);
        $foo = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->amount = 100;
        $obj->currencyCode = 'EUR';
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
        $class->amount = 100;
        $class->currencyCode = 'EUR';
        $class->pan = 'pan';
        $class->termURL = 'termURL';
    }
}
