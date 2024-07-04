<?php

namespace PagOnline\Tests\Unit\Tran;

use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\Tests\Unit\IgfsCgBaseTestCase;
use PagOnline\Tran\IgfsCgCredit;
use PagOnline\Tran\Requests\IgfsCgCreditRequest;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgCreditTest extends IgfsCgBaseTestCase
{
    protected $igfsCgClass = IgfsCgCredit::class;
    protected $igfsCgRequest = IgfsCgCreditRequest::CONTENT;

    public function testChecksFieldsAndRaiseException(): void
    {
        $foo = $this->getClassMethod('checkFields');
        $obj = new $this->igfsCgClass();

        $this->expectException(IgfsMissingParException::class);
        $obj->amount = null;
        $foo->invoke($obj);

        $this->expectExceptionMessage();
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
        $class->amount = 500;
        $class->currencyCode = 'EUR';
        $class->refTranID = '12345678';
        $class->payInstrToken = 'payInstrToken';
    }
}
