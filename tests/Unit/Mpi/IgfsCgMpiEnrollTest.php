<?php

namespace Tests\Unit\Init;

use PagOnline\Mpi\IgfsCgMpiEnroll;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\Mpi\Requests\IgfsCgMpiEnrollRequest;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgMpiEnrollTest extends IgfsCgBaseTest
{
    protected $igfsCgClass = IgfsCgMpiEnroll::class;
    protected $igfsCgRequest = IgfsCgMpiEnrollRequest::CONTENT;

    protected function setIgfsRequiredParamenters(&$class)
    {
        $class->amount = 100;
        $class->currencyCode = 'EUR';
        $class->pan = 'pan';
        $class->termURL = 'termURL';
    }

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
