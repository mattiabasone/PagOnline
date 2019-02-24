<?php

namespace Tests\Unit\Init;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PagOnline\Init\IgfsCgInit;
use GuzzleHttp\Handler\MockHandler;
use PagOnline\XmlEntities\Level3Info;
use PagOnline\XmlEntities\Level3InfoProduct;
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
    public function shouldChecksFieldsAndRaiseException()
    {
        $foo = $this->getClassMethod('checkFields');
        /** @var IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->trType = null;
        $obj->langID = null;
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing trType');
        $foo->invoke($obj);

        $obj->trType = 'AUTH';
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing langID');
        $foo->invoke($obj);

        $obj->langID = 'EN';
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing notifyURL');
        $foo->invoke($obj);

        $obj->notifyURL = 'http://example.org/notify';
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing errorURL');
        $foo->invoke($obj);

        $obj->errorURL = 'http://example.org/error';
        $obj->payInstrToken = '';
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing payInstrToken');
        $foo->invoke($obj);

        $obj->payInstrToken = 'Pippo';
        $obj->level3Info = new Level3Info();
        $obj->level3Info->product = [
            0 => (new Level3InfoProduct()),
        ];
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing productCode[0]');
        $foo->invoke($obj);

        $obj->level3Info->product[0]->productCode = 'productCode';
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing productDescription[0]');
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
