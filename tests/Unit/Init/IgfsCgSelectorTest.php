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
    public function shouldRaiseExceptionForMissingTrType()
    {
        $this->expectException(IgfsMissingParException::class);
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $foo = $this->getClassMethod('checkFields');
        $foo->invoke($obj);
    }
}
