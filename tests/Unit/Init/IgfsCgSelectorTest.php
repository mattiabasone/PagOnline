<?php

namespace PagOnline\Tests\Unit\Init;

use PagOnline\Init\IgfsCgSelector;
use PagOnline\Tests\Unit\IgfsCgBaseTest;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\Init\Requests\IgfsCgSelectorRequest;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgSelectorTest extends IgfsCgBaseTest
{
    protected $igfsCgClass = IgfsCgSelector::class;
    protected $igfsCgRequest = IgfsCgSelectorRequest::CONTENT;

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingTrType(): void
    {
        /* @var \PagOnline\Init\IgfsCgSelector $obj */
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing trType');
        $checkFieldsMethod = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->trType = null;
        $checkFieldsMethod->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingAmount(): void
    {
        /* @var \PagOnline\Init\IgfsCgSelector $obj */
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing amount');
        $checkFieldsMethod = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->trType = 'AUTH';
        $checkFieldsMethod->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingCurrencyCode(): void
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

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingLangID(): void
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

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingPayInstrToken(): void
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

    /** @test */
    public function shouldCheckFieldsAndPass(): void
    {
        /** @var \PagOnline\Init\IgfsCgSelector $obj */
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

    /** @test */
    public function shouldRaiseExceptionForMissingTrType(): void
    {
        $this->expectException(IgfsMissingParException::class);
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $foo = $this->getClassMethod('checkFields');
        $foo->invoke($obj);
    }
}
