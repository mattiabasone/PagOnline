<?php

namespace PagOnline\Tests\Unit;

use PagOnline\IgfsUtils;

class IgfsUtilsTest extends IgfsTestCase
{
    public function testReturnUuid(): void
    {
        self::assertIsString(IgfsUtils::getUniqueBoundaryValue());
    }

    public function testReturnUniqueBoundaryValue(): void
    {
        $uniqueId = IgfsUtils::getUniqueBoundaryValue();
        self::assertMatchesRegularExpression('([a-z0-9]{13})', $uniqueId);
    }

    public function testParseDateFormat(): void
    {
        $dateTimeObject = IgfsUtils::parseDateFormat('2018-10-10', 'Y-m-d');
        self::assertInstanceOf(\DateTimeImmutable::class, $dateTimeObject);
    }

    public function testNotParseDateFormat(): void
    {
        $dateTimeObject = IgfsUtils::parseDateFormat('2018-10-10', 'd-m-Y');
        self::assertNull($dateTimeObject);
    }

    public function testReturnDateTimeClass(): void
    {
        $dateTime = new \DateTimeImmutable('2019-02-19 00:00:00');
        self::assertEquals($dateTime, IgfsUtils::parseXMLGregorianCalendar('19-Feb-2019 00:00:00'));
    }

    public function testNotReturnDateTimeClass(): void
    {
        self::assertNull(IgfsUtils::parseXMLGregorianCalendar('2019-02-19 00:00:00'));
        self::assertNull(IgfsUtils::parseXMLGregorianCalendar(null));
        self::assertNull(IgfsUtils::parseXMLGregorianCalendar(''));
    }

    public function testFormatTimestampToGregorianCalendar(): void
    {
        $datetimeObject = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-02-19 00:00:00');
        self::assertEquals(
            $datetimeObject->format('Y-m-d\TH:i:sP'),
            IgfsUtils::formatXMLGregorianCalendar($datetimeObject->getTimestamp())
        );
    }

    public function testNotFormatTimestampToGregorianCalendar(): void
    {
        $datetimeObject = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-02-19 00:00:00');
        self::assertNotEquals(
            $datetimeObject->format('Y-m-d\TH:i:sP'),
            IgfsUtils::formatXMLGregorianCalendar(0)
        );

        self::assertNull(IgfsUtils::formatXMLGregorianCalendar(''));
        self::assertNull(IgfsUtils::formatXMLGregorianCalendar(null));
    }

    public function testParseResponseFields(): void
    {
        $xmlString = file_get_contents(__DIR__.'/resources/base.xml');
        $dom = new \SimpleXMLElement($xmlString, LIBXML_NOERROR, false);
        $xmlArray = IgfsUtils::parseResponseFields($dom);
        self::assertIsArray($xmlArray);
        self::assertArrayHasKey('apiVersion', $xmlArray);
    }

    public function testGetValueFromArrayMap(): void
    {
        $array = [
            'key1' => 1234,
        ];

        self::assertEquals($array['key1'], IgfsUtils::getValue($array, 'key1'));
        self::assertNull(IgfsUtils::getValue($array, 'key2'));
    }
}
