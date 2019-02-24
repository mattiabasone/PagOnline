<?php

namespace Tests\Unit\Init;

use ReflectionClass;
use PagOnline\IgfsCgInterface;
use PHPUnit\Framework\TestCase;
use PagOnline\Exceptions\IgfsException;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class IgfsCgBaseTest.
 */
abstract class IgfsCgBaseTest extends TestCase
{
    protected $igfsCgClass;
    protected $igfsCgAction;

    /**
     * @param $name
     *
     * @throws \ReflectionException
     *
     * @return \ReflectionMethod
     */
    protected function getClassMethod($name)
    {
        $class = new ReflectionClass($this->igfsCgClass);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @param \PagOnline\IgfsCgInterface $class
     */
    protected function setIgfsBaseValues(&$class)
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
     * @return \PagOnline\IgfsCgInterface
     */
    protected function makeIgfsCg(): IgfsCgInterface
    {
        $class = new $this->igfsCgClass();
        $this->setIgfsBaseValues($class);

        return $class;
    }

    /** @test */
    public function shouldReturnRequestString()
    {
        $obj = new $this->igfsCgClass();
        $this->assertEquals($obj->getRequest(), $this->igfsCgRequest);
    }

    /** @test */
    public function resetFieldsTest()
    {
        $class = $this->makeIgfsCg();
        $class->resetFields();
        $this->assertNull($class->tid);
        $this->assertNull($class->shopID);
        $this->assertEquals($class->langID, 'EN');
    }

    /** @test */
    public function shouldReturnServicePortString()
    {
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $foo = $this->getClassMethod('getServicePort');
        $this->assertIsString(
            $foo->invoke($obj)
        );
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingServerUrl()
    {
        $foo = $this->getClassMethod('checkFields');
        $obj = new $this->igfsCgClass();

        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing serverURL');
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingKSig()
    {
        $foo = $this->getClassMethod('checkFields');
        $obj = new $this->igfsCgClass();
        $obj->serverURL = 'http://example.org';

        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing kSig');
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseExceptionMissingTid()
    {
        $foo = $this->getClassMethod('checkFields');
        $obj = new $this->igfsCgClass();
        $obj->serverURL = 'http://example.org';
        $obj->kSig = 'kSig';

        $this->expectException(IgfsMissingParException::class);
        $this->expectExceptionMessage('Missing tid');
        $foo->invoke($obj);
    }

    /** @test */
    public function shouldFailGeneratingSignature()
    {
        $this->expectException(IgfsException::class);
        $getSignatureMethod = $this->getClassMethod('getSignature');
        $obj = new $this->igfsCgClass();
        $getSignatureMethod->invoke($obj, ['123', ['123', '456']]);
    }

    /** @test */
    public function shouldReplaceRequestPlaceholders()
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

    /** @test */
    public function shouldBuildValidRequest()
    {
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $buildRequestMethod = $this->getClassMethod('buildRequest');
        $this->setIgfsRequiredParamenters($obj);
        $request = $buildRequestMethod->invoke($obj);
        $this->assertIsString($request);
        $this->assertGreaterThan(0, \mb_strlen($request));
    }

    /** @test */
    public function shouldReturnServerUrlAndServicePort()
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

    /** @test */
    public function shouldReturnArrayForAdditionalSignatureFields()
    {
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $foo = $this->getClassMethod('getAdditionalRequestSignatureFields');
        $additionalFieldsArray = $foo->invoke($obj);
        $this->assertIsArray($additionalFieldsArray);
    }

    /** @test */
    public function shouldReturnArray()
    {
        /** @var \PagOnline\Init\IgfsCgInit $obj */
        $obj = $this->makeIgfsCg();
        $array = $obj->toArray();
        $this->assertIsArray($array);
    }
}
