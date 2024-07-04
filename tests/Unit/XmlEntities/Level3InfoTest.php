<?php

namespace PagOnline\Tests\Unit\XmlEntities;

use PagOnline\XmlEntities\Level3Info;
use PagOnline\XmlEntities\Level3InfoProduct;
use PHPUnit\Framework\TestCase;

class Level3InfoTest extends TestCase
{
    public function getBaseElement(): Level3Info
    {
        $level3InfoProduct = new Level3InfoProduct();
        $level3InfoProduct->amount = 10;
        $level3InfoProduct->items = 1;
        $level3InfoProduct->productCode = 'code';
        $level3InfoProduct->productDescription = 'description';

        $level3Info = new Level3Info();
        $level3Info->billingEmail = 'email@example.org';
        $level3Info->vat = 22;
        $level3Info->destinationDate = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-02-19 00:00:00')->getTimestamp();
        $level3Info->product = [$level3InfoProduct];

        return $level3Info;
    }

    public function testLoadProperties(): void
    {
        $level3Info = $this->getBaseElement();
        self::assertIsArray($level3Info->getAttributes());
        self::assertEquals(22, $level3Info->toArray()['vat']);
    }

    public function testReturnXmlString(): void
    {
        $level3Info = $this->getBaseElement();
        $object = simplexml_load_string($level3Info->toXml('Level3Info'));
        $this->assertNotFalse($object);
        $this->assertInstanceOf(\SimpleXMLElement::class, $object);
    }

    public function testHaveXmlNodes(): void
    {
        $level3Info = $this->getBaseElement();

        $object = simplexml_load_string($level3Info->toXml('Level3Info'));

        self::assertObjectHasProperty('billingEmail', $object);
        self::assertObjectHasProperty('vat', $object);
        self::assertObjectHasProperty('product', $object);
        self::assertObjectNotHasProperty('note', $object);
    }

    public function testFormatToXml(): void
    {
        /** @var Level3Info $level3Info */
        $level3Info = Level3Info::fromXml(
            file_get_contents(__DIR__.'/../resources/level3info.xml')
        );
        self::assertObjectHasProperty('billingEmail', $level3Info);
        self::assertEquals('email@example.org', $level3Info->billingEmail);
        self::assertIsArray($level3Info->product);
    }

    public function testReturnXmlStringWhenGeneratedFromXml(): void
    {
        $baseXmlResource = file_get_contents(__DIR__.'/../resources/level3info.xml');
        /** @var Level3Info $level3Info */
        $level3Info = Level3Info::fromXml($baseXmlResource);
        $this->assertXmlStringEqualsXmlString($baseXmlResource, $level3Info->toXml('Level3Info'));
    }
}
