<?php

namespace PagOnline\Tests\Unit\Init;

use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\Init\IgfsCgSelector;
use PagOnline\Init\Requests\IgfsCgSelectorRequest;
use PagOnline\Tests\Unit\IgfsCgBaseTestCase;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgSelectorTest extends IgfsCgBaseTestCase
{
    protected $igfsCgClass = IgfsCgSelector::class;
    protected $igfsCgRequest = IgfsCgSelectorRequest::CONTENT;

    public function testChecksFieldsAndRaiseExceptionMissingTrType(): void
    {
        /* @var \PagOnline\Init\IgfsCgSelector $obj */
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing trType');
        $checkFieldsMethod = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->trType = null;
        $checkFieldsMethod->invoke($obj);
    }

    public function testChecksFieldsAndRaiseExceptionMissingAmount(): void
    {
        /* @var \PagOnline\Init\IgfsCgSelector $obj */
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing amount');
        $checkFieldsMethod = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->trType = 'AUTH';
        $checkFieldsMethod->invoke($obj);
    }

    public function testChecksFieldsAndRaiseExceptionMissingCurrencyCode(): void
    {
        /* @var \PagOnline\Init\IgfsCgSelector $obj */
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing currencyCode');
        $checkFieldsMethod = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->trType = 'AUTH';
        $obj->amount = 1000;
        $checkFieldsMethod->invoke($obj);
    }

    public function testChecksFieldsAndRaiseExceptionMissingLangID(): void
    {
        /* @var \PagOnline\Init\IgfsCgSelector $obj */
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing langID');
        $checkFieldsMethod = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->trType = 'AUTH';
        $obj->amount = 1000;
        $obj->currencyCode = 'EUR';
        $obj->langID = null;
        $checkFieldsMethod->invoke($obj);
    }

    public function testChecksFieldsAndRaiseExceptionMissingPayInstrToken(): void
    {
        /* @var \PagOnline\Init\IgfsCgSelector $obj */
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing payInstrToken');
        $checkFieldsMethod = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->trType = 'TOKENIZE';
        $obj->langID = 'EN';
        $checkFieldsMethod->invoke($obj);
    }

    public function testCheckFieldsAndPass(): void
    {
        /** @var IgfsCgSelector $obj */
        $obj = $this->makeIgfsCg();
        $obj->amount = 500;
        $obj->currencyCode = 'EU';
        $obj->payInstrToken = 'payInstrToken';
        $foo = $this->getClassMethod('checkFields');

        $exception = null;

        try {
            $foo->invoke($obj);
        } catch (\Exception $exception) {
        }

        $this->assertNull($exception);
    }

    public function testRaiseExceptionForMissingTrType(): void
    {
        $this->expectException(IgfsMissingParException::class);
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $foo = $this->getClassMethod('checkFields');
        $foo->invoke($obj);
    }
}
