<?php

namespace PagOnline;

/**
 * Class IgfsUtils.
 */
class IgfsUtils
{
    public const DATE_FORMATS = [
        'j-M-Y H:i:s.000P',
        'j-M-Y H:i:s.00P',
        'j-M-Y H:i:s.0P',
        'j-M-Y H:i:sP',
        'j-M-Y H:i:s.000',
        'j-M-Y H:i:s.00',
        'j-M-Y H:i:s.0',
        'j-M-Y H:i:s',
        'Y-m-d H:i:sP',
    ];

    /**
     * Get value from array map.
     *
     * @param array  $map
     * @param string $key
     *
     * @return mixed
     */
    public static function getValue($map, $key)
    {
        return $map[$key] ?? null;
    }

    /**
     * TODO: migrate to UUID?
     *
     * @return string
     */
    public static function getUniqueBoundaryValue(): string
    {
        return uniqid();
    }

    /**
     * Transform DOM nodes to associative array.
     *
     * @param \SimpleXMLElement $nodes
     *
     * @return array
     */
    public static function parseResponseFields(\SimpleXMLElement $nodes): array
    {
        $fields = [];
        foreach ($nodes->children() as $item) {
            if (\count($item) === 0) {
                $fields[$item->getName()] = trim((string) $item);
            } else {
                $fields[$item->getName()] = (string) $item->asXML();
            }
        }

        return $fields;
    }

    /**
     * Format timestamp to XML Gregorian Calendar (?).
     *
     * @param null|int $timestamp
     *
     * @return null|string
     */
    public static function formatXMLGregorianCalendar($timestamp): ?string
    {
        $integerTimestamp = (int) $timestamp;
        if ($integerTimestamp === 0) {
            return null;
        }

        $dateTimeObject = (new \DateTimeImmutable())->setTimestamp($integerTimestamp);

        return $dateTimeObject->format('Y-m-d').
            'T'.
            $dateTimeObject->format('H:i:s').
            $dateTimeObject->format('P');
    }

    /**
     * @param null|string $text
     *
     * @return null|\DateTimeImmutable
     */
    public static function parseXMLGregorianCalendar(?string $text)
    {
        if (empty($text)) {
            return null;
        }

        $date = null;
        $text = str_replace('T', ' ', $text);
        foreach (self::DATE_FORMATS as $dateFormat) {
            $date = self::parseDateFormat($text, $dateFormat);
            if ($date !== null) {
                break;
            }
        }

        return $date;
    }

    /**
     * @param string $text
     * @param string $format Date format
     *
     * @return null|\DateTimeImmutable
     */
    public static function parseDateFormat(string $text, string $format): ?\DateTimeImmutable
    {
        $date = \DateTimeImmutable::createFromFormat($format, $text);

        return $date ?: null;
    }
}
