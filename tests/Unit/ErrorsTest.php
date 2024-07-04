<?php

namespace PagOnline\Tests\Unit;

use PagOnline\Errors;
use PHPUnit\Framework\TestCase;

/**
 * Class IgfsUtilsTest.
 */
class ErrorsTest extends TestCase
{
    public function testReturnErrorMessage(): void
    {
        $this->assertIsString(Errors::getMessage(Errors::IGFS_000));
    }

    public function testNotReturnErrorMessage(): void
    {
        $this->assertNull(Errors::getMessage('NONE'));
    }
}
