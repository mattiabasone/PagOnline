<?php

namespace PagOnline\Tests\Unit\Mpi;

use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\Mpi\IgfsCgMpiAuth;
use PagOnline\Mpi\Requests\IgfsCgMpiAuthRequest;
use PagOnline\Tests\Unit\IgfsCgBaseTest;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgMpiAuthTest extends IgfsCgBaseTest
{
    protected $igfsCgClass = IgfsCgMpiAuth::class;
    protected $igfsCgRequest = IgfsCgMpiAuthRequest::CONTENT;

    /** @test */
    public function shouldRaiseExceptionForMissingShopId(): void
    {
        $this->expectException(IgfsMissingParException::class);
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->shopID = null;
        $foo = $this->getClassMethod('checkFields');
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingPaRes(): void
    {
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing paRes');
        $foo = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->shopID = '1231';
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingMd(): void
    {
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing md');
        $foo = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $obj->paRes = 'paRes';
        $obj->shopID = '1231';
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldCheckFieldsAndPass(): void
    {
        /** @var \PagOnline\Mpi\IgfsCgMpiAuth $obj */
        $obj = $this->makeIgfsCg();
        $foo = $this->getClassMethod('checkFields');
        $obj->paRes = 'paRes';
        $obj->md = 'md';
        $exception = null;

        try {
            $foo->invoke($obj);
        } catch (\Exception $exception) {
        }

        $this->assertNull($exception);
    }
}
