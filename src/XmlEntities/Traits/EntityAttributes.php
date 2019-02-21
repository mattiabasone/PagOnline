<?php

namespace PagOnline\XmlEntities\Traits;

use PagOnline\XmlEntities\XmlEntityInterface;

/**
 * Trait EntityAttributes.
 */
trait EntityAttributes
{
    /**
     * Attributes that are classes
     * array['attributeName']['type'] = 'scalar|array'
     * array['attributeName']['namespace'] = \PagOnline\XmlEntities\Class::class.
     *
     * @var array
     */
    protected $entityAttributes = [];

    /**
     * @param string $attribute
     *
     * @return bool
     */
    public function isEntityAttribute(string $attribute): bool
    {
        return \array_key_exists($attribute, $this->entityAttributes);
    }

    /**
     * @param string $attribute
     *
     * @return string|null
     */
    public function getCustomAttributeXml(string $attribute): ?string
    {
        if (empty($this->{$attribute})) {
            return null;
        }
        $xmlContent = '';
        if ($this->entityAttributes[$attribute]['type'] = 'array') {
            foreach ($this->{$attribute} as $item) {
                /* @var \PagOnline\XmlEntities\XmlEntityInterface $item */
                $xmlContent .= $item instanceof XmlEntityInterface ? $item->toXml($attribute) : '';
            }
        } else {
            $xmlContent .= $this->{$attribute} instanceof XmlEntityInterface ? $this->{$attribute}->toXml() : '';
        }

        return $xmlContent;
    }
}
