<?php

namespace Tests\Unit;

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
