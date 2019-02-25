<?php

namespace PagOnline\Tests\Unit;

use PagOnline\Errors;
use PHPUnit\Framework\TestCase;

/**
 * Class IgfsUtilsTest.
 */
class ErrorsTest extends TestCase
{
    /** @test */
    public function shouldReturnErrorMessage()
    {
        $this->assertIsString(Errors::getMessage(Errors::IGFS_000));
    }

    /** @test */
    public function shouldNotReturnErrorMessage()
    {
        $this->assertNull(Errors::getMessage('NONE'));
    }
}
