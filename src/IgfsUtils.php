<?php

namespace PagOnline;

use DateTime;

/**
 * Class IgfsUtils.
 */
class IgfsUtils
{
    const DATE_FORMATS = [
        'j-M-Y H:i:s.000P',
        'j-M-Y H:i:s.00P',
        'j-M-Y H:i:s.0P',
        'j-M-Y H:i:sP',
        'j-M-Y H:i:s.000',
        'j-M-Y H:i:s.00',
        'j-M-Y H:i:s.0',
        'j-M-Y H:i:s',
    ];

    /**
     * Get value from array map.
     *
     * @param $map
     * @param $key
     *
     * @return string|null
     */
    public static function getValue($map, $key)
    {
        return isset($map[$key]) ? $map[$key] : null;
    }

    /**
     * TODO: migrate to UUID?
     *
     * @return string
     */
    public static function getUniqueBoundaryValue()
    {
        return \uniqid();
    }

    /**
     * Transform DOM nodes to associative array.
     *
     * @param $nodes
     *
     * @return array
     */
    public static function parseResponseFields(\SimpleXMLElement $nodes): array
    {
        $fields = [];
        foreach ($nodes->children() as $item) {
            if (0 == \count($item)) {
                $fields[$item->getName()] = (string) $item;
            } else {
                $fields[$item->getName()] = (string) $item->asXML();
            }
        }

        return $fields;
    }

    /**
     * @param $haystack
     * @param $needle
     * @param bool $case
     *
     * @return bool
     */
    public static function startsWith($haystack, $needle, $case = true)
    {
        if ($case) {
            return 0 === \strcmp(\mb_substr($haystack, 0, \mb_strlen($needle)), $needle);
        }

        return 0 === \strcasecmp(\mb_substr($haystack, 0, \mb_strlen($needle)), $needle);
    }

    /**
     * @param $haystack
     * @param $needle
     * @param bool $case
     *
     * @return bool
     */
    public static function endsWith($haystack, $needle, $case = true)
    {
        if ($case) {
            return 0 === \strcmp(\mb_substr($haystack, \mb_strlen($haystack) - \mb_strlen($needle)), $needle);
        }

        return 0 === \strcasecmp(\mb_substr($haystack, \mb_strlen($haystack) - \mb_strlen($needle)), $needle);
    }

    /**
     * Format timestamp to XML Gregorian Calendar (?).
     *
     * @param int $timestamp
     *
     * @return string|null
     */
    public static function formatXMLGregorianCalendar($timestamp)
    {
        try {
            $dateTimeObject = (new \DateTimeImmutable())->setTimestamp($timestamp);

            return $dateTimeObject->format('Y-m-d').
                'T'.
                $dateTimeObject->format('H:i:s').
                $dateTimeObject->format('P');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param $text
     *
     * @return \DateTime|null
     */
    public static function parseXMLGregorianCalendar(?string $text)
    {
        if (empty($text)) {
            return null;
        }

        $date = null;
        foreach (self::DATE_FORMATS as $dateFormat) {
            $date = self::parseDateFormat($text, $dateFormat);
            if (null !== $date) {
                break;
            }
        }

        return $date;
    }

    /**
     * @param $text
     * @param string $format Date format
     *
     * @return bool|\DateTime|null
     */
    private static function parseDateFormat($text, string $format): ?DateTime
    {
        try {
            $text = \str_replace('T', ' ', $text);
            $date = DateTime::createFromFormat($format, $text);

            return $date ? $date : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
