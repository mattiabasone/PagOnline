<?php

namespace PagOnline\Tests\Unit\XmlEntities;

use PagOnline\XmlEntities\MandateInfo;
use PHPUnit\Framework\TestCase;

/**
 * Class IgfsCgFactoryTest.
 */
class MandateInfoTest extends TestCase
{
    /** @test */
    public function shouldReturnXmlString(): void
    {
        $mandate = new MandateInfo();
        $mandate->contractID = '1234567';
        $mandate->frequency = 1;
        $mandate->durationEndDate = \time();
        $mandate->durationStartDate = \time();
        $mandate->finalCollectionDate = \time();
        $mandate->firstCollectionDate = \time();

        $object = \simplexml_load_string($mandate->toXml('MandateInfo'));
        $this->assertNotFalse($object);
        $this->assertInstanceOf(\SimpleXMLElement::class, $object);
    }

    /** @test */
    public function shouldHaveXmlNodes(): void
    {
        $mandate = new MandateInfo();
        $mandate->contractID = '1234567';
        $mandate->durationEndDate = \time();
        $mandate->durationStartDate = \time();
        $mandate->finalCollectionDate = \time();
        $mandate->firstCollectionDate = \time();

        $object = \simplexml_load_string($mandate->toXml('MandateInfo'));
        $this->assertObjectHasAttribute('contractID', $object);
        $this->assertObjectHasAttribute('durationEndDate', $object);
        $this->assertObjectHasAttribute('durationStartDate', $object);
        $this->assertObjectHasAttribute('firstCollectionDate', $object);
        $this->assertObjectNotHasAttribute('frequency', $object);
    }
}
