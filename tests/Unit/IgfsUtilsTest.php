<?php

namespace PagOnline\Tests\Unit;

use PagOnline\IgfsUtils;
use PHPUnit\Framework\TestCase;

/**
 * Class IgfsUtilsTest.
 */
class IgfsUtilsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnUuid()
    {
        $this->assertIsString(IgfsUtils::getUniqueBoundaryValue());
    }

    /** @test */
    public function shouldReturnUniqueBoundaryValue()
    {
        $uniqueId = IgfsUtils::getUniqueBoundaryValue();
        $this->assertRegExp('([a-z0-9]{13})', $uniqueId);
    }

    /** @test */
    public function shouldParseDateFormat()
    {
        $dateTimeObject = IgfsUtils::parseDateFormat('2018-10-10', 'Y-m-d');
        $this->assertInstanceOf(\DateTimeImmutable::class, $dateTimeObject);
    }

    /** @test */
    public function shouldNotParseDateFormat()
    {
        $dateTimeObject = IgfsUtils::parseDateFormat('2018-10-10', 'd-m-Y');
        $this->assertNull($dateTimeObject);
    }

    /** @test */
    public function shouldReturnDateTimeClass()
    {
        $dateTime = new \DateTimeImmutable('2019-02-19 00:00:00');
        $this->assertEquals($dateTime, IgfsUtils::parseXMLGregorianCalendar('19-Feb-2019 00:00:00'));
    }

    /** @test */
    public function shouldNotReturnDateTimeClass()
    {
        $this->assertNull(IgfsUtils::parseXMLGregorianCalendar('2019-02-19 00:00:00'));
        $this->assertNull(IgfsUtils::parseXMLGregorianCalendar(null));
        $this->assertNull(IgfsUtils::parseXMLGregorianCalendar(''));
    }

    /** @test */
    public function shouldFormatTimestampToGregorianCalendar()
    {
        $datetimeObject = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-02-19 00:00:00');
        $this->assertEquals(
            $datetimeObject->format('Y-m-d\TH:i:sP'),
            IgfsUtils::formatXMLGregorianCalendar($datetimeObject->getTimestamp())
        );
    }

    /** @test */
    public function shouldNotFormatTimestampToGregorianCalendar()
    {
        $datetimeObject = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-02-19 00:00:00');
        $this->assertNotEquals(
            $datetimeObject->format('Y-m-d\TH:i:sP'),
            IgfsUtils::formatXMLGregorianCalendar(0)
        );

        $this->assertNull(IgfsUtils::formatXMLGregorianCalendar(''));
        $this->assertNull(IgfsUtils::formatXMLGregorianCalendar(null));
    }

    /** @test */
    public function shouldParseResponseFields()
    {
        $xmlString = \file_get_contents(__DIR__.'/resources/base.xml');
        $dom = new \SimpleXMLElement($xmlString, LIBXML_NOERROR, false);
        $xmlArray = IgfsUtils::parseResponseFields($dom);
        $this->assertIsArray($xmlArray);
        $this->assertArrayHasKey('apiVersion', $xmlArray);
    }

    /** @test */
    public function shouldGetValueFromArrayMap()
    {
        $array = [
            'key1' => 1234,
        ];

        $this->assertEquals($array['key1'], IgfsUtils::getValue($array, 'key1'));
        $this->assertNull(IgfsUtils::getValue($array, 'key2'));
    }
}
