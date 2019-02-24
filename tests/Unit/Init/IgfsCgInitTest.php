<?php

namespace Tests\Unit\Init;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PagOnline\Init\IgfsCgInit;
use GuzzleHttp\Handler\MockHandler;
use PagOnline\XmlEntities\Level3Info;
use PagOnline\XmlEntities\MandateInfo;
use PagOnline\XmlEntities\Level3InfoProduct;
use PagOnline\Init\Requests\IgfsCgInitRequest;
use PagOnline\XmlEntities\Init\InitTerminalInfo;
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
    public function shouldChecksFieldsAndRaiseExceptionMissingTrType()
    {
        $foo = $this->getClassMethod('checkFields');
        /** @var IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->trType = null;
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing trType');
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingLangID()
    {
        $foo = $this->getClassMethod('checkFields');
        /** @var IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->trType = 'AUTH';
        $obj->langID = null;
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing langID');
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingNotifyURL()
    {
        $foo = $this->getClassMethod('checkFields');
        /** @var IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->trType = 'AUTH';
        $obj->langID = 'EN';
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing notifyURL');
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingErrorURL()
    {
        $foo = $this->getClassMethod('checkFields');
        /** @var IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->trType = 'AUTH';
        $obj->langID = 'EN';
        $obj->notifyURL = 'http://example.org/notify';
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing errorURL');
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingPayInstrToken()
    {
        $foo = $this->getClassMethod('checkFields');
        /** @var IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->trType = 'AUTH';
        $obj->langID = 'EN';
        $obj->notifyURL = 'http://example.org/notify';
        $obj->errorURL = 'http://example.org/error';
        $obj->payInstrToken = '';
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing payInstrToken');
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingLevel3ProductCode()
    {
        $foo = $this->getClassMethod('checkFields');
        /** @var IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->trType = 'AUTH';
        $obj->langID = 'EN';
        $obj->notifyURL = 'http://example.org/notify';
        $obj->errorURL = 'http://example.org/error';
        $obj->payInstrToken = 'Pippo';
        $obj->level3Info = new Level3Info();
        $obj->level3Info->product = [
            0 => (new Level3InfoProduct()),
        ];
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing productCode[0]');
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingLevel3ProductDescription()
    {
        $foo = $this->getClassMethod('checkFields');
        /** @var IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->trType = 'AUTH';
        $obj->langID = 'EN';
        $obj->notifyURL = 'http://example.org/notify';
        $obj->errorURL = 'http://example.org/error';
        $obj->payInstrToken = 'Pippo';
        $obj->level3Info = new Level3Info();
        $obj->level3Info->product = [
            0 => (new Level3InfoProduct()),
        ];
        $obj->level3Info->product[0]->productCode = 'productCode';
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing productDescription[0]');
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingMandateInfo()
    {
        $foo = $this->getClassMethod('checkFields');
        /** @var IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->trType = 'AUTH';
        $obj->langID = 'EN';
        $obj->notifyURL = 'http://example.org/notify';
        $obj->errorURL = 'http://example.org/error';
        $obj->payInstrToken = 'Pippo';
        $obj->level3Info = new Level3Info();
        $obj->level3Info->product = [
            0 => (new Level3InfoProduct()),
        ];
        $obj->level3Info->product[0]->productCode = 'productCode';
        $obj->level3Info->product[0]->productDescription = 'productCode';
        $obj->mandateInfo = 'Pippo';
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing mandateID');
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
            // First
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                \file_get_contents(__DIR__.'/../resources/init/success.xml')
            ),
            // Second
            new Response(500),
            // Third
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                '<html><head></head><body></body></html>'
            ),
            // Fourth
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                ''
            ),
            // Fifth
            new Response(401),
            new Response(401),
            // Sixth
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                \file_get_contents(__DIR__.'/../resources/init/success.xml')
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
        $obj->level3Info = new Level3Info();
        $obj->level3Info->vat = '12345';
        $obj->mandateInfo = new MandateInfo();
        $obj->mandateInfo->contractID = '12345';
        $obj->termInfo = [new InitTerminalInfo()];
        $obj->termInfo[0]->tid = '12345';
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

        // Fifth: trying multiple server urls and fails
        $obj->resetFields();
        $obj->setHttpAuthUser('admin');
        $obj->setHttpAuthPass('admin');
        $obj->setHttpProxy('tcp://127.0.0.1');
        $obj->setHttpVerifySsl(false);
        $this->setIgfsBaseValues($obj);
        $obj->serverURL = null;
        $obj->serverURLs = ['https://google.com', 'https://amazon.com'];
        $this->setIgfsRequiredParamenters($obj);
        $this->assertFalse($obj->execute());

        $obj->resetFields();
        $this->setIgfsBaseValues($obj);
        $obj->serverURL = null;
        $obj->serverURLs = ['https://google.com'];
        $this->setIgfsRequiredParamenters($obj);
        $this->assertFalse($obj->execute());
    }
}
