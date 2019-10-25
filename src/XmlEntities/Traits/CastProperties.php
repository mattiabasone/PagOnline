<?php

namespace PagOnline\XmlEntities\Traits;

use PagOnline\IgfsUtils;

/**
 * Trait CastProperties.
 */
trait CastProperties
{
    /** @var array */
    protected $casts = [];

    /**
     * Check if is date attribute.
     *
     * @param string $attribute Public attribute name
     *
     * @return bool
     */
    public function isDateAttribute($attribute): bool
    {
        return \array_key_exists($attribute, $this->casts) && $this->casts[$attribute] === 'date';
    }

    /**
     * Get attribute cast type for simple properties.
     *
     * @param string $attribute
     *
     * @return string
     */
    protected function getAttributeCastType(string $attribute): string
    {
        return \array_key_exists($attribute, $this->casts) ? $this->casts[$attribute] : 'string';
    }

    /**
     * Cast attribute value.
     *
     * @param string $attribute
     *
     * @return string|array
     */
    public function castAttribute($attribute)
    {
        switch ($this->getAttributeCastType($attribute)) {
            case 'date':
                $value = (string) IgfsUtils::formatXMLGregorianCalendar($this->{$attribute});
                break;
            case 'array':
                $value = (array) $this->{$attribute};
                break;
            default:
                $value = (string) $this->{$attribute};
                break;
        }

        return $value;
    }
}
