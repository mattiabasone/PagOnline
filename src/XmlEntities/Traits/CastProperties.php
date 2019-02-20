<?php

namespace PagOnline\XmlEntities\Traits;

use PagOnline\IgfsUtils;

/**
 * Trait CastProperties.
 */
trait CastProperties
{
    /** @var array */
    protected $dates = [];

    /**
     * Check if is date attribute.
     *
     * @param string $attribute Public attribute name
     *
     * @return bool
     */
    protected function isDateAttribute($attribute): bool
    {
        return \in_array($attribute, $this->dates, true);
    }

    /**
     * Cast attribute value.
     *
     * @param string $attribute
     *
     * @return string
     */
    protected function castAttribute($attribute)
    {
        if ($this->isDateAttribute($attribute)) {
            $value = (string) IgfsUtils::formatXMLGregorianCalendar($this->{$attribute});
        } else {
            $value = (string) $this->{$attribute};
        }

        return $value;
    }
}
