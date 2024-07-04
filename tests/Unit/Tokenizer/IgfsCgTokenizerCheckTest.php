<?php

namespace PagOnline\Tests\Unit\Tokenizer;

use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\Tests\Unit\IgfsCgBaseTestCase;
use PagOnline\Tokenizer\IgfsCgTokenizerCheck;
use PagOnline\Tokenizer\Requests\IgfsCgTokenizerCheckRequest;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgTokenizerCheckTest extends IgfsCgBaseTestCase
{
    protected $igfsCgClass = IgfsCgTokenizerCheck::class;
    protected $igfsCgRequest = IgfsCgTokenizerCheckRequest::CONTENT;

    public function testChecksFieldsAndRaiseExceptionMissingPayInstrToken(): void
    {
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing payInstrToken');
        $foo = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
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

    /**
     * @param $class
     */
    protected function setIgfsRequiredParamenters(&$class): void
    {
        $class->payInstrToken = 'payInstrToken';
    }
}
