<?php

namespace PagOnline\Tests\Unit;

use PagOnline\IgfsUtils;
use PHPUnit\Framework\TestCase;

class IgfsUtilsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnUuid()
    {
        $this->assertIsString(IgfsUtils::getUniqueBoundaryValue());
    }

    /**
     * @test
     */
    public function shouldStartWith()
    {
        $this->assertTrue(IgfsUtils::startsWith('MyString', 'My'));
    }

    /**
     * @test
     */
    public function shouldNotStartWith()
    {
        $this->assertNotTrue(IgfsUtils::startsWith('MyString', 'Pi'));
    }

    /**
     * @test
     */
    public function shouldEndsWith()
    {
        $this->assertTrue(IgfsUtils::endsWith('http://google.com/', '/'));
    }

    /**
     * @test
     */
    public function shouldNotEndsWith()
    {
        $this->assertNotTrue(IgfsUtils::endsWith('http://google.com', '/'));
    }

    /**
     * @test
     */
    public function shouldReturnDateTimeClass()
    {
        $dateTime = new \DateTime('2019-02-19 00:00:00');
        $this->assertEquals($dateTime, IgfsUtils::parseXMLGregorianCalendar('19-Feb-2019 00:00:00'));
    }

    /**
     * @test
     */
    public function shouldNotReturnDateTimeClass()
    {
        $this->assertNull(IgfsUtils::parseXMLGregorianCalendar('2019-02-19 00:00:00'));
    }
}
