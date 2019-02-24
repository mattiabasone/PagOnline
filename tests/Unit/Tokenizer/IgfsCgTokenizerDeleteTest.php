<?php

namespace Tests\Unit\Init;

use PagOnline\Tokenizer\IgfsCgTokenizerDelete;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\Tokenizer\Requests\IgfsCgTokenizerDeleteRequest;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgTokenizerDeleteTest extends IgfsCgBaseTest
{
    protected $igfsCgClass = IgfsCgTokenizerDelete::class;
    protected $igfsCgRequest = IgfsCgTokenizerDeleteRequest::CONTENT;

    protected function setIgfsRequiredParamenters(&$class)
    {
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
