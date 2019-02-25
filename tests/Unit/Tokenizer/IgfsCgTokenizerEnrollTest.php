<?php

namespace PagOnline\Tests\Unit\Tokenizer;

use PagOnline\Tests\Unit\IgfsCgBaseTest;
use PagOnline\Tokenizer\IgfsCgTokenizerEnroll;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\Tokenizer\Requests\IgfsCgTokenizerEnrollRequest;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgTokenizerEnrollTest extends IgfsCgBaseTest
{
    protected $igfsCgClass = IgfsCgTokenizerEnroll::class;
    protected $igfsCgRequest = IgfsCgTokenizerEnrollRequest::CONTENT;

    protected function setIgfsRequiredParamenters(&$class)
    {
        $class->pan = '123456';
        $class->expireMonth = '01';
        $class->expireYear = '2050';
        $class->payInstrToken = 'payInstrToken';
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
