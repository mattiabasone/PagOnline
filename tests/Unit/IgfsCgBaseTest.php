<?php

namespace Tests\Unit\Init;

use ReflectionClass;
use PagOnline\IgfsCgInterface;
use PHPUnit\Framework\TestCase;

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
     * @return \PagOnline\IgfsCgInterface
     */
    protected function makeIgfsCg(): IgfsCgInterface
    {
        $class = new $this->igfsCgClass();
        $this->setIgfsBaseValues($class);

        return $class;
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
}
