<?php

namespace PagOnline\Tests\Unit\Init;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\Init\IgfsCgInit;
use PagOnline\Init\Requests\IgfsCgInitRequest;
use PagOnline\Tests\Unit\IgfsCgBaseTestCase;
use PagOnline\XmlEntities\Init\InitTerminalInfo;
use PagOnline\XmlEntities\Level3Info;
use PagOnline\XmlEntities\Level3InfoProduct;
use PagOnline\XmlEntities\MandateInfo;

class IgfsCgInitTest extends IgfsCgBaseTestCase
{
    protected $igfsCgClass = IgfsCgInit::class;
    protected $igfsCgRequest = IgfsCgInitRequest::CONTENT;

    public function testChecksFieldsAndRaiseExceptionMissingTrType(): void
    {
        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing trType');

        $foo = $this->getClassMethod('checkFields');
        /** @var IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->trType = null;
        $foo->invoke($obj);
    }

    public function testChecksFieldsAndRaiseExceptionMissingLangID(): void
    {
        $this->expectException(IgfsMissingParException::class);

        $foo = $this->getClassMethod('checkFields');
        /** @var IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->trType = 'AUTH';
        $obj->langID = null;
        $foo->invoke($obj);
    }

    public function testChecksFieldsAndRaiseExceptionMissingNotifyURL(): void
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

    public function testChecksFieldsAndRaiseExceptionMissingErrorURL(): void
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

    public function testChecksFieldsAndRaiseExceptionMissingPayInstrToken(): void
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

    public function testChecksFieldsAndRaiseExceptionMissingLevel3ProductCode(): void
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

    public function testChecksFieldsAndRaiseExceptionMissingLevel3ProductDescription(): void
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

    public function testChecksFieldsAndRaiseExceptionMissingMandateInfo(): void
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

    public function testCheckFieldsAndPass(): void
    {
        /** @var IgfsCgInit $obj */
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

    public function testRaiseExceptionForMissingShopId(): void
    {
        /** @var IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $this->setIgfsRequiredParamenters($obj);
        $obj->shopID = null;
        $foo = $this->getClassMethod('checkFields');

        $this->expectException(IgfsMissingParException::class);
        $foo->invoke($obj);
    }

    public function testExecuteInitRequests(): void
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            // First
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                file_get_contents(__DIR__.'/../resources/init/success.xml')
            ),
            // Sixth
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                file_get_contents(__DIR__.'/../resources/init/success.xml')
            ),
            // Sixth
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                file_get_contents(__DIR__.'/../resources/init/success.xml')
            ),
        ]);

        $handler = HandlerStack::create($mock);

        /** @var IgfsCgInit $obj */
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

    public function testFailProcessingErrorBodyResponse(): void
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                file_get_contents(__DIR__.'/../resources/init/error.xml')
            ),
        ]);

        $handler = HandlerStack::create($mock);

        /** @var IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->setHttpClient(new Client(['handler' => $handler]));
        $this->setIgfsRequiredParamenters($obj);
        $this->assertFalse($obj->execute());
    }

    public function testFailProcessingInvalidBodyResponse(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'text/html; charset="utf-8"'],
                '<html><head></head><body></body></html>'
            ),
        ]);
        $handler = HandlerStack::create($mock);

        /** @var IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->setHttpClient(new Client(['handler' => $handler]));
        $this->setIgfsRequiredParamenters($obj);
        $this->assertFalse($obj->execute());
    }

    public function testFailProcessingEmptyBodyResponse(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                ''
            ),
        ]);
        $handler = HandlerStack::create($mock);

        /** @var IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $obj->setHttpClient(new Client(['handler' => $handler]));
        $this->setIgfsRequiredParamenters($obj);
        $this->assertFalse($obj->execute());
    }

    public function testFailProcessingUrlWithError500(): void
    {
        $mock = new MockHandler([
            new Response(500),
        ]);

        $handler = HandlerStack::create($mock);

        /** @var IgfsCgInit $obj */
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

    public function testFailCheckingMultipleUrls(): void
    {
        $mock = new MockHandler([
            new Response(401),
            new Response(403),
        ]);

        $handler = HandlerStack::create($mock);

        /** @var IgfsCgInit $obj */
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

    public function testFailProcessForMissingErrorAndSignatureTagsInResponse(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                file_get_contents(__DIR__.'/../resources/init/no_error_tag.xml')
            ),
            new Response(
                200,
                ['Content-Type' => 'text/xml; charset="utf-8"'],
                file_get_contents(__DIR__.'/../resources/init/no_signature_tag.xml')
            ),
        ]);
        $handler = HandlerStack::create($mock);

        /** @var IgfsCgInit $obj */
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

    protected function setIgfsRequiredParamenters(&$class): void
    {
        $class->notifyURL = 'https://example.com/verify/';
        $class->errorURL = 'https://example.com/error/';
    }
}
