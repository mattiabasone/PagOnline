<?php

namespace PagOnline\Tests\Unit;

use PHPUnit\Framework\TestCase;

class IgfsTestCase extends TestCase
{
    /**
     * Compatibility function for older PHPUnit versions
     *
     * @param string $pattern
     * @param string $value
     * @return void
     */
    public function assertMatchesRegex(string $pattern, string $value): void
    {
        if (method_exists($this, 'assertMatchesRegularExpression')) {
            $this->assertMatchesRegularExpression($pattern, $value);
        } else {
            $this->assertRegExp($pattern, $value);
        }
    }

}