<?php

namespace PagOnline\Tests\Unit\XmlEntities\Init;

use PHPUnit\Framework\TestCase;
use PagOnline\XmlEntities\Init\SelectorTerminalInfo;

/**
 * Class Level3InfoTest.
 */
class SelectorTerminalInfoTest extends TestCase
{
    public function getBaseElement(): SelectorTerminalInfo
    {
        $object = new SelectorTerminalInfo();
        $object->tid = '123456';
        $object->description = 'Lorem Ipsum description';
        $object->payInstr = 'Lorem Ipsum payInstr';
        $object->payInstrDescription = 'Lorem Ipsum payInstrDescription';
        $object->imgURL = [
            'https://via.placeholder.com/350x150',
            'https://via.placeholder.com/350x150',
            'https://via.placeholder.com/350x150',
        ];

        return $object;
    }

    /** @test */
    public function shouldLoadProperties(): void
    {
        $object = $this->getBaseElement();
        $this->assertIsArray($object->getAttributes());
        $this->assertEquals($object->toArray()['tid'], '123456');
    }

    /** @test */
    public function shouldFormatToXml(): void
    {
        /** @var \PagOnline\XmlEntities\Init\SelectorTerminalInfo $object */
        $object = SelectorTerminalInfo::fromXml(
            \file_get_contents(__DIR__.'/../../resources/selector_terminal_info.xml')
        );
        $this->assertObjectHasAttribute('description', $object);
        $this->assertEquals('Lorem Ipsum description', $object->description);
        $this->assertIsArray($object->imgURL);
    }

    /** @test */
    public function shouldReturnXmlStringWhenGeneratedFromXml(): void
    {
        /** @var \PagOnline\XmlEntities\Init\SelectorTerminalInfo $object */
        $baseXmlResource = \file_get_contents(__DIR__.'/../../resources/selector_terminal_info.xml');
        /** @var \PagOnline\XmlEntities\Level3Info $level3Info */
        $object = SelectorTerminalInfo::fromXml($baseXmlResource);
        $this->assertXmlStringEqualsXmlString($baseXmlResource, $object->toXml('SelectorTerminalinfo'));
    }
}
