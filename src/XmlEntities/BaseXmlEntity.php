<?php

namespace PagOnline\XmlEntities;

use PagOnline\IgfsUtils;
use PagOnline\XmlEntities\Traits\CastProperties;
use PagOnline\XmlEntities\Traits\EntityAttributes;

abstract class BaseXmlEntity implements XmlEntityInterface
{
    use CastProperties, EntityAttributes;

    protected array $attributes = [];

    /**
     * BaseXmlEntity constructor.
     */
    public function __construct()
    {
        $this->loadAttributes();
    }

    /**
     * Get object attributes.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Format entity to Xml.
     *
     * @param string $rootNodeName
     *
     * @return string
     */
    public function toXml(string $rootNodeName): string
    {
        $body = "<{$rootNodeName}>";
        foreach ($this->attributes as $attribute) {
            if (!empty($this->{$attribute})) {
                if (!$this->isEntityAttribute($attribute)) {
                    $value = $this->castAttribute($attribute);
                    if (\is_array($value)) {
                        foreach ($value as $valueEntry) {
                            $body .= $this->attributeValueToTagString($attribute, $valueEntry);
                        }
                    } else {
                        $body .= $this->attributeValueToTagString($attribute, $value);
                    }
                } else {
                    $body .= $this->getCustomAttributeXml($attribute);
                }
            }
        }
        $body .= "</{$rootNodeName}>";

        return $body;
    }

    /**
     * @param array  $response
     * @param string $attribute
     */
    public function setAttributeFromResponse($response, $attribute): void
    {
        $value = (string) IgfsUtils::getValue($response, $attribute);
        if ($this->isDateAttribute($attribute)) {
            $tmpValue = IgfsUtils::parseXMLGregorianCalendar($value);
            if ($tmpValue !== null) {
                $value = $tmpValue->getTimestamp();
            } else {
                $value = null;
            }
        }
        $this->{$attribute} = $value;
    }

    /**
     * Generate BaseXmlEntity.
     *
     * @param string $xml
     *
     * @return null|\PagOnline\XmlEntities\XmlEntityInterface
     */
    public static function fromXml($xml): ?XmlEntityInterface
    {
        if (empty($xml)) {
            return null;
        }

        $dom = new \SimpleXMLElement($xml, LIBXML_NOERROR, false);
        if ($dom->children()->count() === 0) {
            return null;
        }

        $xmlArray = IgfsUtils::parseResponseFields($dom);
        $object = null;
        if (\count($xmlArray) > 0) {
            /** @phpstan-ignore-next-line */
            $object = new static();
            foreach ($object->getAttributes() as $attribute) {
                if (!$object->isEntityAttribute($attribute)) {
                    if ($object->getAttributeCastType($attribute) !== 'array') {
                        $object->setAttributeFromResponse($xmlArray, $attribute);
                    } else {
                        foreach ($dom->xpath($attribute) as $entry) {
                            $object->{$attribute}[] = $entry->__toString();
                        }
                    }
                } else {
                    $object->setCustomAttributeFromDom($dom, $attribute);
                }
            }
        }

        return $object;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $returnArray = [];
        foreach ($this->attributes as $attribute) {
            $returnArray[$attribute] = $this->{$attribute};
        }

        return $returnArray;
    }

    /**
     * Load attributes from public properties.
     */
    protected function loadAttributes(): void
    {
        $publicProperties = (new \ReflectionObject($this))->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($publicProperties as $publicProperty) {
            $this->attributes[] = $publicProperty->getName();
        }
    }

    /**
     * @param string $attribute
     * @param string $value
     *
     * @return string
     */
    protected function attributeValueToTagString(string $attribute, string $value): string
    {
        return "<{$attribute}><![CDATA[{$value}]]></{$attribute}>";
    }

    /**
     * @param \SimpleXMLElement $dom
     * @param string            $attribute
     */
    protected function setCustomAttributeFromDom(\SimpleXMLElement $dom, $attribute): void
    {
        if ($this->entityAttributes[$attribute]['type'] === 'array') {
            $value = [];
            foreach ($dom->xpath($attribute) as $item) {
                $value[] = $this->entityAttributes[$attribute]['namespace']::fromXml($item->asXML());
            }
        } else {
            $element = $dom->xpath($attribute);
            $value = null;
            if (\count($element) > 0) {
                $value = $this->entityAttributes[$attribute]['namespace']::fromXml($element[0]->asXML());
            }
        }
        $this->{$attribute} = $value;
    }
}
