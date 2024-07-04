<?php

namespace PagOnline\Tests\Unit\XmlEntities\Init;

use PagOnline\XmlEntities\Init\SelectorTerminalInfo;
use PHPUnit\Framework\TestCase;

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

    public function testLoadProperties(): void
    {
        $object = $this->getBaseElement();
        $this->assertIsArray($object->getAttributes());
        $this->assertEquals($object->toArray()['tid'], '123456');
    }

    public function testFormatToXml(): void
    {
        /** @var SelectorTerminalInfo $object */
        $object = SelectorTerminalInfo::fromXml(
            file_get_contents(__DIR__.'/../../resources/selector_terminal_info.xml')
        );
        self::assertObjectHasProperty('description', $object);
        $this->assertEquals('Lorem Ipsum description', $object->description);
        $this->assertIsArray($object->imgURL);
    }

    public function testReturnXmlStringWhenGeneratedFromXml(): void
    {
        /** @var SelectorTerminalInfo $object */
        $baseXmlResource = file_get_contents(__DIR__.'/../../resources/selector_terminal_info.xml');
        /** @var \PagOnline\XmlEntities\Level3Info $level3Info */
        $object = SelectorTerminalInfo::fromXml($baseXmlResource);
        $this->assertXmlStringEqualsXmlString($baseXmlResource, $object->toXml('SelectorTerminalinfo'));
    }
}
