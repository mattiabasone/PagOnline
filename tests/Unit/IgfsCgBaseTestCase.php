<?php

namespace PagOnline\Tests\Unit;

use PagOnline\Exceptions\IgfsException;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\IgfsCgInterface;

abstract class IgfsCgBaseTestCase extends IgfsTestCase
{
    protected $igfsCgClass;
    protected $igfsCgAction;

    public function testReturnRequestString(): void
    {
        $obj = new $this->igfsCgClass();
        $this->assertEquals($obj->getRequest(), $this->igfsCgRequest);
    }

    public function testResetFields(): void
    {
        $class = $this->makeIgfsCg();
        $class->resetFields();
        self::assertNull($class->tid);
        self::assertNull($class->shopID);
        self::assertEquals('EN', $class->langID);
    }

    public function testReturnServicePortString(): void
    {
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $foo = $this->getClassMethod('getServicePort');
        self::assertIsString(
            $foo->invoke($obj)
        );
    }

    public function testChecksFieldsAndRaiseExceptionMissingServerUrl(): void
    {
        $foo = $this->getClassMethod('checkFields');
        $obj = new $this->igfsCgClass();

        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing serverURL');
        $foo->invoke($obj);
    }

    public function testChecksFieldsAndRaiseExceptionMissingKSig(): void
    {
        $foo = $this->getClassMethod('checkFields');
        $obj = new $this->igfsCgClass();
        $obj->serverURL = 'http://example.org';

        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing kSig');
        $foo->invoke($obj);
    }

    public function testChecksFieldsAndRaiseExceptionMissingTid(): void
    {
        $foo = $this->getClassMethod('checkFields');
        $obj = new $this->igfsCgClass();
        $obj->serverURL = 'http://example.org';
        $obj->kSig = 'kSig';

        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing tid');
        $foo->invoke($obj);
    }

    public function testFailGeneratingSignature(): void
    {
        $this->expectException(IgfsException::class);
        $getSignatureMethod = $this->getClassMethod('getSignature');
        $obj = new $this->igfsCgClass();
        echo $getSignatureMethod->invoke($obj, [new \stdClass()]);
    }

    public function testReplaceRequestPlaceholders(): void
    {
        $replaceRequestParameterMethod = $this->getClassMethod('replaceRequestParameter');
        $obj = new $this->igfsCgClass();

        $fakeRequest = '{mySuperField}';
        $replaceRequestParameterMethod->invokeArgs($obj, [&$fakeRequest, 'mySuperField', null]);
        $this->assertIsString($fakeRequest);
        $this->assertEmpty($fakeRequest);

        $fakeRequest = '{mySuperField}';
        $replaceRequestParameterMethod->invokeArgs($obj, [&$fakeRequest, 'mySuperField', 'testValue']);
        $this->assertEquals('<mySuperField><![CDATA[testValue]]></mySuperField>', $fakeRequest);

        $fakeRequest = '{mySuperField}';
        $replaceRequestParameterMethod->invokeArgs($obj, [&$fakeRequest, 'mySuperField', 'testValue', false]);
        $this->assertEquals('<mySuperField>testValue</mySuperField>', $fakeRequest);
    }

    public function testBuildValidRequest(): void
    {
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $buildRequestMethod = $this->getClassMethod('buildRequest');
        $this->setIgfsRequiredParamenters($obj);
        $request = $buildRequestMethod->invoke($obj);
        $this->assertIsString($request);
        $this->assertGreaterThan(0, mb_strlen($request));
    }

    public function testReturnServerUrlAndServicePort(): void
    {
        /** @var \PagOnline\BaseIgfsCg $obj */
        $obj = $this->makeIgfsCg();
        $servicePortMethod = $this->getClassMethod('getServicePort');
        $serverUrlMethod = $this->getClassMethod('getServerUrl');

        $serverUrlT1 = $serverUrlMethod->invoke($obj, 'http://my-server.it/');
        $this->assertEquals('http://my-server.it/'.$servicePortMethod->invoke($obj), $serverUrlT1);

        $serverUrlT2 = $serverUrlMethod->invoke($obj, 'http://my-server.it');
        $this->assertEquals('http://my-server.it/'.$servicePortMethod->invoke($obj), $serverUrlT2);
    }

    public function testReturnArrayForAdditionalSignatureFields(): void
    {
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $foo = $this->getClassMethod('getAdditionalRequestSignatureFields');
        $additionalFieldsArray = $foo->invoke($obj);
        $this->assertIsArray($additionalFieldsArray);
    }

    public function testReturnArray(): void
    {
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $array = $obj->toArray();
        $this->assertIsArray($array);
    }

    public function testReturnArrayUniqueBoundaryValue(): void
    {
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $getUniqueBoundaryValueMethod = $this->getClassMethod('getUniqueBoundaryValue');
        $uniqueId = $getUniqueBoundaryValueMethod->invoke($obj);
        self::assertIsString($uniqueId);
        self::assertMatchesRegularExpression('([a-z0-9]{13})', $uniqueId);
    }

    /**
     * @param $name
     *
     * @throws \ReflectionException
     *
     * @return \ReflectionMethod
     */
    protected function getClassMethod($name): \ReflectionMethod
    {
        $class = new \ReflectionClass($this->igfsCgClass);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @param IgfsCgInterface $class
     */
    protected function setIgfsBaseValues(&$class): void
    {
        $class->serverURL = 'https://server.com/UNI_CG_SERVICES/services';
        $class->tid = 'UNI_MYBK';
        $class->kSig = 'UNI_TESTKEY';
        $class->shopID = '5c6fdf5d20485';
        $class->langID = 'EN';
    }

    /**
     * @param $class
     */
    protected function setIgfsRequiredParamenters(&$class)
    {
    }

    /**
     * @return IgfsCgInterface
     */
    protected function makeIgfsCg(): IgfsCgInterface
    {
        $class = new $this->igfsCgClass();
        $this->setIgfsBaseValues($class);

        return $class;
    }
}
