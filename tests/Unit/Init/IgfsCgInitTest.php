<?php

namespace PagOnline\Tests\Unit\Init;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PagOnline\Init\IgfsCgInit;
use GuzzleHttp\Handler\MockHandler;
use PagOnline\XmlEntities\Level3Info;
use PagOnline\XmlEntities\MandateInfo;
use PagOnline\Tests\Unit\IgfsCgBaseTest;
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

    protected function setIgfsRequiredParamenters(&$class): void
    {
        $class->notifyURL = 'https://example.com/verify/';
        $class->errorURL = 'https://example.com/error/';
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingTrType(): void
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
    public function shouldChecksFieldsAndRaiseExceptionMissingLangID(): void
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
    public function shouldChecksFieldsAndRaiseExceptionMissingNotifyURL(): void
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
    public function shouldChecksFieldsAndRaiseExceptionMissingErrorURL(): void
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
    public function shouldChecksFieldsAndRaiseExceptionMissingPayInstrToken(): void
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
    public function shouldChecksFieldsAndRaiseExceptionMissingLevel3ProductCode(): void
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
    public function shouldChecksFieldsAndRaiseExceptionMissingLevel3ProductDescription(): void
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
    public function shouldChecksFieldsAndRaiseExceptionMissingMandateInfo(): void
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
    public function shouldCheckFieldsAndPass(): void
    {
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $this->setIgfsRequiredParamenters($obj);
        $checkFieldsMethod = $this->getClassMethod('checkFields');

        $exception = null;
        try {
            $checkFieldsMethod->invoke($obj);
        } catch (\Exception $exception) {
        }

        $this->assertNull($exception);

        $obj->level3Info = new Level3Info();
        $obj->level3Info->vat = '12345678909';

        $obj->mandateInfo = new MandateInfo();
        $obj->mandateInfo->contractID = '12343454353';
        $obj->mandateInfo->mandateID = '12343454353';

        $obj->termInfo = [new InitTerminalInfo()];
        $obj->termInfo[0]->tid = 'tid';
        $exception = null;
        try {
            $checkFieldsMethod->invoke($obj);
        } catch (\Exception $exception) {
        }

        $this->assertNull($exception);
    }

    /** @test */
    public function shouldRaiseExceptionForMissingShopId(): void
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
    public function shouldExecuteInitRequests(): void
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            // First
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                \file_get_contents(__DIR__.'/../resources/init/success.xml')
            ),
            // Sixth
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                \file_get_contents(__DIR__.'/../resources/init/success.xml')
            ),
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
        $obj->level3Info = new Level3Info();
        $obj->level3Info->vat = '12345678909';

        $obj->mandateInfo = new MandateInfo();
        $obj->mandateInfo->contractID = '12343454353';
        $obj->mandateInfo->mandateID = '12343454353';

        $obj->termInfo = [new InitTerminalInfo()];
        $obj->termInfo[0]->tid = 'tid';
        $this->assertTrue($obj->execute());
        $this->assertNotNull($obj->redirectURL);

        $obj->resetFields();
        $this->setIgfsBaseValues($obj);
        $this->setIgfsRequiredParamenters($obj);
        $obj->serverURL = null;
        $obj->serverURLs = ['https://google.com'];
        $this->assertTrue($obj->execute());

        $obj->resetFields();
        $this->setIgfsBaseValues($obj);
        $this->setIgfsRequiredParamenters($obj);
        $obj->serverURL = null;
        $obj->serverURLs = ['https://google.com', 'https://amazon.com'];
        $this->assertTrue($obj->execute());
    }

    /** @test */
    public function shouldFailProcessingErrorBodyResponse(): void
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                \file_get_contents(__DIR__.'/../resources/init/error.xml')
            ),
        ]);

        $handler = HandlerStack::create($mock);

        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->setHttpClient(new Client(['handler' => $handler]));
        $this->setIgfsRequiredParamenters($obj);
        $this->assertFalse($obj->execute());
    }

    /** @test */
    public function shouldFailProcessingInvalidBodyResponse(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'text/html; charset="utf-8"'],
                '<html><head></head><body></body></html>'
            ),
        ]);
        $handler = HandlerStack::create($mock);

        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->setHttpClient(new Client(['handler' => $handler]));
        $this->setIgfsRequiredParamenters($obj);
        $this->assertFalse($obj->execute());
    }

    /** @test */
    public function shouldFailProcessingEmptyBodyResponse(): void
    {
        $mock = new MockHandler([
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
        $this->assertFalse($obj->execute());
    }

    /** @test */
    public function shouldFailProcessingUrlWithError500(): void
    {
        $mock = new MockHandler([
            new Response(500),
        ]);

        $handler = HandlerStack::create($mock);

        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $this->setIgfsRequiredParamenters($obj);
        $obj->setHttpClient(new Client(['handler' => $handler]));
        $obj->level3Info = new Level3Info();
        $obj->level3Info->vat = '12345';
        $obj->mandateInfo = new MandateInfo();
        $obj->mandateInfo->contractID = '12345';
        $obj->termInfo = [new InitTerminalInfo()];
        $obj->termInfo[0]->tid = '12345';
        $this->assertFalse($obj->execute());
    }

    /** @test */
    public function shouldFailCheckingMultipleUrls(): void
    {
        $mock = new MockHandler([
            new Response(401),
            new Response(403),
        ]);

        $handler = HandlerStack::create($mock);

        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $this->setIgfsRequiredParamenters($obj);
        $obj->setHttpClient(new Client(['handler' => $handler]));
        $obj->setHttpAuthUser('admin');
        $obj->setHttpAuthPass('admin');
        $obj->setHttpProxy('tcp://127.0.0.1');
        $obj->setHttpVerifySsl(false);
        $obj->setCustomHttpRequestConfig([
            \GuzzleHttp\RequestOptions::ALLOW_REDIRECTS => false,
        ]);
        $obj->serverURL = null;
        $obj->serverURLs = ['https://google.com', 'https://amazon.com'];
        $this->assertFalse($obj->execute());
    }

    /** @test */
    public function shouldFailProcessForMissingErrorAndSignatureTagsInResponse(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                \file_get_contents(__DIR__.'/../resources/init/no_error_tag.xml')
            ),
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                \file_get_contents(__DIR__.'/../resources/init/no_signature_tag.xml')
            ),
        ]);
        $handler = HandlerStack::create($mock);

        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->setHttpClient(new Client(['handler' => $handler]));
        $this->setIgfsBaseValues($obj);
        $this->setIgfsRequiredParamenters($obj);
        $this->assertFalse($obj->execute());
        $this->assertTrue($obj->error);

        $obj->resetFields();
        $this->setIgfsBaseValues($obj);
        $this->setIgfsRequiredParamenters($obj);
        $this->assertFalse($obj->execute());
        $this->assertTrue($obj->error);
    }
}
