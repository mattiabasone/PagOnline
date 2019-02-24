<?php

namespace Tests\Unit\Init;

use PagOnline\PayByMail\IgfsCgPayByMailVerify;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\PayByMail\Requests\IgfsCgPayByMailVerifyRequest;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgPayByMailVerifyTest extends IgfsCgBaseTest
{
    protected $igfsCgClass = IgfsCgPayByMailVerify::class;
    protected $igfsCgRequest = IgfsCgPayByMailVerifyRequest::CONTENT;

    protected function setIgfsRequiredParamenters(&$class)
    {
        $class->mailID = 'mail@example.org';
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
