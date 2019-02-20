<?php

namespace PagOnline\Tests\Unit\XmlEntities;

use PHPUnit\Framework\TestCase;
use PagOnline\XmlEntities\Level3Info;
use PagOnline\XmlEntities\Level3InfoProduct;

class Level3InfoTest extends TestCase
{
    /** @test */
    public function shouldReturnXmlString()
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

        $object = \simplexml_load_string($level3Info->toXml('Level3Info'));
        $this->assertNotFalse($object);
        $this->assertInstanceOf(\SimpleXMLElement::class, $object);
    }

    /** @test */
    public function shouldHaveXmlNodes()
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

        $object = \simplexml_load_string($level3Info->toXml('Level3Info'));

        $this->assertObjectHasAttribute('billingEmail', $object);
        $this->assertObjectHasAttribute('vat', $object);
        $this->assertObjectHasAttribute('product', $object);
        $this->assertObjectNotHasAttribute('note', $object);
    }
}
