<?php

namespace PagOnline\XmlEntities;

use ReflectionObject;
use ReflectionProperty;
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
            if (null !== $this->{$attribute}) {
                if (!$this->isEntityAttribute($attribute)) {
                    $value = $this->castAttribute($attribute);
                    $body .= "<{$attribute}><![CDATA[{$value}]]></{$attribute}>";
                } else {
                    $body .= $this->getCustomAttributeXml($attribute);
                }
            }
        }
        $body .= "</{$rootNodeName}>";

        return $body;
    }
}
