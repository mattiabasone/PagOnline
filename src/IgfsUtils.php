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
     * Creates signature for requests.
     *
     * @param $ksig
     * @param $fields
     *
     * @return string
     */
    public static function getSignature($ksig, $fields)
    {
        $data = '';
        foreach ($fields as $value) {
            $data .= $value;
        }

        return \base64_encode(\hash_hmac('sha256', $data, $ksig, true));
    }

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
     * TODO: migrateto UUID?
     *
     * @return string
     */
    public static function getUniqueBoundaryValue()
    {
        return \uniqid();
    }

    /**
     * @param $nodes
     *
     * @return array
     */
    public static function parseResponseFields($nodes)
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
     * @param $odt
     *
     * @return string|null
     */
    public static function formatXMLGregorianCalendar($odt)
    {
        try {
            $format1 = \date('Y-m-d', $odt);
            // FIX MILLISECOND
            // CXF FORMATTA I MS senza 0 in coda
            $format2 = \date('H:i:s', $odt);
            $format3 = \date('P', $odt);
            $sb = '';
            $sb .= $format1;
            $sb .= 'T';
            $sb .= $format2;
            $sb .= $format3;

            return $sb;
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
     * @param $format
     *
     * @return bool|\DateTime|null
     */
    private static function parseDateFormat($text, $format): ?DateTime
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
