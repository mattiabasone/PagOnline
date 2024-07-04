<?php

namespace PagOnline\Tests\Unit\Init;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PagOnline\Errors;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\Init\IgfsCgVerify;
use PagOnline\Init\Requests\IgfsCgVerifyRequest;
use PagOnline\Tests\Unit\IgfsCgBaseTestCase;

class IgfsCgVerifyTest extends IgfsCgBaseTestCase
{
    protected $igfsCgClass = IgfsCgVerify::class;
    protected $igfsCgRequest = IgfsCgVerifyRequest::CONTENT;

    public function testChecksFieldsAndRaiseException(): void
    {
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing paymentID');
        $foo = $this->getClassMethod('checkFields');
        $obj = $this->makeIgfsCg();
        $foo->invoke($obj);
    }

    public function testCheckFieldsAndPass(): void
    {
        /** @var IgfsCgVerify $obj */
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

    public function testRaiseExceptionForMissingShopId(): void
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

    public function testExecuteVerifyRequests(): void
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                file_get_contents(__DIR__.'/../resources/verify/success.xml')
            ),
            new Response(500),
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                file_get_contents(__DIR__.'/../resources/verify/invalid_signature.xml')
            ),
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                file_get_contents(__DIR__.'/../resources/verify/invalid_receipt_pdf.xml')
            ),
        ]);

        $handler = HandlerStack::create($mock);

        /** @var IgfsCgVerify $obj */
        $obj = $this->makeIgfsCg();
        $obj->shopID = '5c71649051ef5';
        $obj->paymentID = '00054481661101578102';
        $obj->setHttpClient(new Client(['handler' => $handler]));

        // First test
        $this->assertTrue($obj->execute());
        $this->assertIsArray($obj->payAddData);
        $this->assertEquals($obj->payAddData[0]->key, 'myKey1');

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
