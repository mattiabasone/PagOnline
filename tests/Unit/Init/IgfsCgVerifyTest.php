<?php

namespace Tests\Unit\Init;

use PagOnline\Errors;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PagOnline\Init\IgfsCgVerify;
use GuzzleHttp\Handler\MockHandler;
use PagOnline\Init\Requests\IgfsCgVerifyRequest;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgVerifyTest extends IgfsCgBaseTest
{
    protected $igfsCgClass = IgfsCgVerify::class;
    protected $igfsCgRequest = IgfsCgVerifyRequest::CONTENT;

    /** @test */
    public function shouldChecksFieldsAndRaiseException()
    {
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing paymentID');
        $foo = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldCheckFieldsAndPass()
    {
        /** @var \PagOnline\Init\IgfsCgVerify $obj */
        $obj = $this->makeIgfsCg();
        $obj->paymentID = 'paymentId';
        $foo = $this->getClassMethod('checkFields');

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
        $obj->notifyURL = 'https://example.com/verify/';
        $obj->errorURL = 'https://example.com/error/';
        $obj->shopID = null;
        $foo = $this->getClassMethod('checkFields');
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldExecuteVerifyRequests()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                \file_get_contents(__DIR__.'/../resources/verify/success.xml')
            ),
            new Response(500),
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                \file_get_contents(__DIR__.'/../resources/verify/invalid_signature.xml')
            ),
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                \file_get_contents(__DIR__.'/../resources/verify/invalid_receipt_pdf.xml')
            ),
        ]);

        $handler = HandlerStack::create($mock);

        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->shopID = '5c71649051ef5';
        $obj->paymentID = '00054481661101578102';
        $obj->setHttpClient(new Client(['handler' => $handler]));

        // First test
        $this->assertTrue($obj->execute());

        // Second test
        $obj->resetFields();
        $this->setIgfsBaseValues($obj);
        $obj->shopID = '5c71649051ef5';
        $obj->paymentID = '00054481661101578102';
        $this->assertFalse($obj->execute());
        $this->assertEquals(Errors::IGFS_007, $obj->rc);

        // Third test
        $obj->resetFields();
        $this->setIgfsBaseValues($obj);
        $obj->shopID = '5c71649051ef5';
        $obj->paymentID = '00054481661101578102';

        $this->assertFalse($obj->execute());
        $this->assertEquals(Errors::IGFS_909, $obj->rc);

        // Fourth
        /* @var \PagOnline\Init\IgfsCgVerify $obj */
        $obj->resetFields();
        $this->setIgfsBaseValues($obj);
        $obj->shopID = '5c71649051ef5';
        $obj->paymentID = '00054481661101578102';
        $this->assertTrue($obj->execute());
        $this->assertNull($obj->receiptPdf);
    }
}
