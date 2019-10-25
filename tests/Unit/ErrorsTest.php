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
    public function shouldReturnErrorMessage(): void
    {
        $this->assertIsString(Errors::getMessage(Errors::IGFS_000));
    }

    /** @test */
    public function shouldNotReturnErrorMessage(): void
    {
        $this->assertNull(Errors::getMessage('NONE'));
    }
}
