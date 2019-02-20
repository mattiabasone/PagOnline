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
    protected function isEntityAttribute(string $attribute): bool
    {
        return \array_key_exists($attribute, $this->entityAttributes);
    }

    /**
     * @param string $attribute
     *
     * @return string|null
     */
    protected function getCustomAttributeXml(string $attribute): ?string
    {
        if (empty($this->{$attribute})) {
            return null;
        }
        $xmlContent = '';
        if ('array' === $this->entityAttributes[$attribute]['type']) {
            foreach ($this->{$attribute} as $item) {
                /* @var \PagOnline\XmlEntities\XmlEntityInterface $item */
                $xmlContent .= $item instanceof XmlEntityInterface ? $item->toXml('product') : '';
            }
        } else {
            $xmlContent .= $this->{$attribute} instanceof XmlEntityInterface ? $this->{$attribute}->toXml() : '';
        }

        return $xmlContent;
    }
}
