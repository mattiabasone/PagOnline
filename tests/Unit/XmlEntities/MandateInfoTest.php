<?php

namespace PagOnline\Tests\Unit\XmlEntities;

use PagOnline\XmlEntities\MandateInfo;
use PHPUnit\Framework\TestCase;

class MandateInfoTest extends TestCase
{
    public function testReturnXmlString(): void
    {
        $mandate = new MandateInfo();
        $mandate->contractID = '1234567';
        $mandate->frequency = 1;
        $mandate->durationEndDate = time();
        $mandate->durationStartDate = time();
        $mandate->finalCollectionDate = time();
        $mandate->firstCollectionDate = time();

        $object = simplexml_load_string($mandate->toXml('MandateInfo'));
        self::assertNotFalse($object);
        self::assertInstanceOf(\SimpleXMLElement::class, $object);
    }

    public function testHaveXmlNodes(): void
    {
        $mandate = new MandateInfo();
        $mandate->contractID = '1234567';
        $mandate->durationEndDate = time();
        $mandate->durationStartDate = time();
        $mandate->finalCollectionDate = time();
        $mandate->firstCollectionDate = time();

        $object = simplexml_load_string($mandate->toXml('MandateInfo'));
        self::assertObjectHasProperty('contractID', $object);
        self::assertObjectHasProperty('durationEndDate', $object);
        self::assertObjectHasProperty('durationStartDate', $object);
        self::assertObjectHasProperty('firstCollectionDate', $object);
        self::assertObjectNotHasProperty('frequency', $object);
    }
}
