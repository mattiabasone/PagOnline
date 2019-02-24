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

    protected function setIgfsRequiredParamenters(&$class)
    {
        $class->notifyURL = 'https://example.com/verify/';
        $class->errorURL = 'https://example.com/error/';
    }

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
        $this->setIgfsRequiredParamenters($obj);
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
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $this->setIgfsRequiredParamenters($obj);
        $obj->shopID = null;
        $foo = $this->getClassMethod('checkFields');

        $this->expectException(IgfsMissingParException::class);
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
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                ''
            ),
        ]);

        $handler = HandlerStack::create($mock);

        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->setHttpClient(new Client(['handler' => $handler]));
        $this->setIgfsRequiredParamenters($obj);

        // First: successful test
        $this->assertTrue($obj->execute());
        $this->assertNotNull($obj->redirectURL);

        // Second: Gateway error 500
        $obj->resetFields();
        $this->setIgfsBaseValues($obj);
        $this->setIgfsRequiredParamenters($obj);

        $this->assertFalse($obj->execute());

        // Third: Invalid body
        $obj->resetFields();
        $this->setIgfsBaseValues($obj);
        $this->setIgfsRequiredParamenters($obj);

        $this->assertFalse($obj->execute());

        // Fourth: 200 + empty body
        $obj->resetFields();
        $this->setIgfsBaseValues($obj);
        $this->setIgfsRequiredParamenters($obj);

        $this->assertFalse($obj->execute());
    }
}
