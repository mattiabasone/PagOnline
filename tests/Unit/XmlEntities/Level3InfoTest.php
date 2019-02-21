<?php

namespace Tests\Unit\XmlEntities;

use PHPUnit\Framework\TestCase;
use PagOnline\XmlEntities\Level3Info;
use PagOnline\XmlEntities\Level3InfoProduct;

/**
 * Class Level3InfoTest.
 */
class Level3InfoTest extends TestCase
{
    public function getBaseElement()
    {
        $level3InfoProduct = new Level3InfoProduct();
        $level3InfoProduct->amount = 10;
        $level3InfoProduct->items = 1;
        $level3InfoProduct->productCode = 'code';
        $level3InfoProduct->productDescription = 'description';

        $level3Info = new Level3Info();
        $level3Info->billingEmail = 'email@example.org';
        $level3Info->vat = 22;
        $level3Info->product = [$level3InfoProduct];

        return $level3Info;
    }

    /** @test */
    public function shouldLoadProperties()
    {
        $level3Info = $this->getBaseElement();
        $this->assertIsArray($level3Info->getAttributes());
        $this->assertEquals($level3Info->toArray()['vat'], 22);
    }

    /** @test */
    public function shouldReturnXmlString()
    {
        $level3Info = $this->getBaseElement();
        $object = \simplexml_load_string($level3Info->toXml('Level3Info'));
        $this->assertNotFalse($object);
        $this->assertInstanceOf(\SimpleXMLElement::class, $object);
    }

    /** @test */
    public function shouldHaveXmlNodes()
    {
        $level3Info = $this->getBaseElement();

        $object = \simplexml_load_string($level3Info->toXml('Level3Info'));

        $this->assertObjectHasAttribute('billingEmail', $object);
        $this->assertObjectHasAttribute('vat', $object);
        $this->assertObjectHasAttribute('product', $object);
        $this->assertObjectNotHasAttribute('note', $object);
    }

    /** @test */
    public function shouldFormatToXml()
    {
        /** @var \PagOnline\XmlEntities\Level3Info $level3Info */
        $level3Info = Level3Info::fromXml(
            \file_get_contents(__DIR__.'/../resources/level3info.xml')
        );
        $this->assertObjectHasAttribute('billingEmail', $level3Info);
        $this->assertEquals('email@example.org', $level3Info->billingEmail);
        $this->assertIsArray($level3Info->product);
    }

    /** @test */
    public function shouldReturnXmlStringWhenGeneratedFromXml()
    {
        $baseXmlResource = \file_get_contents(__DIR__.'/../resources/level3info.xml');
        /** @var \PagOnline\XmlEntities\Level3Info $level3Info */
        $level3Info = Level3Info::fromXml($baseXmlResource);
        $this->assertXmlStringEqualsXmlString($baseXmlResource, $level3Info->toXml('Level3Info'));
    }
}
