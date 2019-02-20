<?php

namespace PagOnline\Tests\Unit\Init;

use ReflectionClass;
use Illuminate\Support\Str;
use PagOnline\Init\IgfsCgInit;
use PHPUnit\Framework\TestCase;
use PagOnline\Exceptions\IgfsMissingParException;

class IgfsCgInitTest extends TestCase
{
    protected function getClassMethod($class, $name)
    {
        $class = new ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @param $namespace
     */
    protected function makeIgfsCg($namespace)
    {
        $class = new $namespace();
        $class->serverURL = 'https://server.com/UNI_CG_SERVICES/services';
        $class->tid = Str::random(16);
        $class->kSig = Str::random(24);
        $class->timeout = 15000;
        $class->shopID = Str::random(24);
    }

    /** @test */
    public function shouldChecksFieldsAndRaiseException()
    {
        $this->expectException(IgfsMissingParException::class);
        $foo = $this->getClassMethod(IgfsCgInit::class, 'checkFields');
        $obj = new IgfsCgInit();
        $foo->invoke($obj);
    }
}
