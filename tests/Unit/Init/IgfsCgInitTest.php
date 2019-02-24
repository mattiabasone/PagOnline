<?php

namespace Tests\Unit\Init;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PagOnline\Init\IgfsCgInit;
use GuzzleHttp\Handler\MockHandler;
use PagOnline\Init\Requests\IgfsCgInitRequest;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class IgfsCgInitTest.
 */
class IgfsCgInitTest extends IgfsCgBaseTest
{
    protected $igfsCgClass = IgfsCgInit::class;
    protected $igfsCgRequest = IgfsCgInitRequest::CONTENT;

    /** @test */
    public function shouldReturnRequestString()
    {
        $obj = new $this->igfsCgClass();
        $this->assertEquals($obj->getRequest(), $this->igfsCgRequest);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseException()
    {
        $this->expectException(IgfsMissingParException::class);
        $foo = $this->getClassMethod('checkFields');
        $obj = new IgfsCgInit();
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldCheckFieldsAndPass()
    {
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->notifyURL = 'https://example.com/verify/';
        $obj->errorURL = 'https://example.com/error/';
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
    public function shouldExecuteInitRequests()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                \file_get_contents(__DIR__.'/../resources/init/success.xml')
            ),
            new Response(500),
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                '<html><head></head><body></body></html>'
            ),
        ]);

        $handler = HandlerStack::create($mock);

        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->setHttpClient(new Client(['handler' => $handler]));

        $obj->notifyURL = 'http://playground.test/pagonline/tests/demo/verify.php';
        $obj->errorURL = 'http://playground.test/pagonline/tests/demo/error.php';

        // First: successful test
        $this->assertTrue($obj->execute());
        $this->assertNotNull($obj->redirectURL);

        // Second: Gateway error 500
        $obj->resetFields();
        $this->setIgfsBaseValues($obj);
        $obj->notifyURL = 'http://playground.test/pagonline/tests/demo/verify.php';
        $obj->errorURL = 'http://playground.test/pagonline/tests/demo/error.php';

        $this->assertFalse($obj->execute());

        // Third: Invalid body
        $obj->resetFields();
        $this->setIgfsBaseValues($obj);
        $obj->notifyURL = 'http://playground.test/pagonline/tests/demo/verify.php';
        $obj->errorURL = 'http://playground.test/pagonline/tests/demo/error.php';

        $this->assertFalse($obj->execute());
    }
}
