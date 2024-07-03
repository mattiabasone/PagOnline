<?php

namespace PagOnline\Tests\Unit;

use PagOnline\Actions;
use PagOnline\Exceptions\ClassNotFoundException;
use PagOnline\IgfsCgFactory;
use PagOnline\Init\IgfsCgInit;
use PHPUnit\Framework\TestCase;

class IgfsCgFactoryTest extends TestCase
{
    public function testCreateIgfsCgClass(): void
    {
        $igfsCgInit = new IgfsCgInit();
        $this->assertEquals($igfsCgInit, IgfsCgFactory::make(Actions::IGFS_CG_INIT));
    }

    public function testRaiseClassNotFoundException(): void
    {
        $this->expectException(ClassNotFoundException::class);
        IgfsCgFactory::make('Not\Existent\Class\NameSpace');
    }
}
