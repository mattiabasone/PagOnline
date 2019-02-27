<?php

namespace PagOnline\XmlEntities;

use ReflectionObject;
use SimpleXMLElement;
use ReflectionProperty;
use PagOnline\IgfsUtils;
use PagOnline\XmlEntities\Traits\CastProperties;
use PagOnline\XmlEntities\Traits\EntityAttributes;

/**
 * Class BaseXmlEntity.
 */
abstract class BaseXmlEntity implements XmlEntityInterface
{
    use CastProperties, EntityAttributes;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * BaseXmlEntity constructor.
     */
    public function __construct()
    {
        $this->loadAttributes();
    }

    /**
     * Load attributes from public properties.
     */
    protected function loadAttributes(): void
    {
        $publicProperties = (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($publicProperties as $publicProperty) {
            \array_push($this->attributes, $publicProperty->getName());
        }
    }

    /**
     * Get object attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $attribute
     * @param string $value
     *
     * @return string
     */
    protected function attributeValueToTagString(string $attribute, string $value)
    {
        return "<{$attribute}><![CDATA[{$value}]]></{$attribute}>";
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
    public function setAttributeFromResponse($response, $attribute)
    {
        $value = (string) IgfsUtils::getValue($response, $attribute);
        if ($this->isDateAttribute($attribute)) {
            $tmpValue = IgfsUtils::parseXMLGregorianCalendar($value);
            if (null !== $tmpValue) {
                $value = $tmpValue->getTimestamp();
            } else {
                $value = null;
            }
        }
        $this->{$attribute} = $value;
    }

    /**
     * @param \SimpleXMLElement $dom
     * @param $attribute
     */
    protected function setCustomAttributeFromDom(SimpleXMLElement $dom, $attribute)
    {
        if ('array' === $this->entityAttributes[$attribute]['type']) {
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

    /**
     * Generate BaseXmlEntity.
     *
     * @param $xml
     *
     * @return \PagOnline\XmlEntities\XmlEntityInterface|null
     */
    public static function fromXml($xml): ?XmlEntityInterface
    {
        if (empty($xml)) {
            return null;
        }

        $dom = new SimpleXMLElement($xml, LIBXML_NOERROR, false);
        if (0 === $dom->children()->count()) {
            return null;
        }

        $xmlArray = IgfsUtils::parseResponseFields($dom);
        $object = null;
        if (\count($xmlArray) > 0) {
            $object = new static();
            foreach ($object->getAttributes() as $attribute) {
                if (!$object->isEntityAttribute($attribute)) {
                    if ('array' !== $object->getAttributeCastType($attribute)) {
                        $object->setAttributeFromResponse($xmlArray, $attribute);
                    } else {
                        foreach ($dom->xpath($attribute) as $entry) {
                            \array_push($object->{$attribute}, $entry->__toString());
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
    public function toArray()
    {
        $returnArray = [];
        foreach ($this->attributes as $attribute) {
            $returnArray[$attribute] = $this->{$attribute};
        }

        return $returnArray;
    }
}
