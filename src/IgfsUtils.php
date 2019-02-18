<?php

namespace PagOnline;

use DateTime;

/**
 * Class IgfsUtils.
 */
class IgfsUtils
{
    /**
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
     * Get value from array map
     *
     * @param $map
     * @param $key
     * @return string|null
     */
    public static function getValue($map, $key)
    {
        return isset($map[$key]) ? $map[$key] : null;
    }

    /**
     * @return string
     */
    public static function getUniqueBoundaryValue()
    {
        return \uniqid();
    }

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
    public static function parseXMLGregorianCalendar($text)
    {
        if (null == $text) {
            return null;
        }
        $date = self::parseXMLGregorianCalendarTZ($text, 'j-M-Y H:i:s.000P');
        if (null == $date) {
            $date = self::parseXMLGregorianCalendarTZ($text, 'j-M-Y H:i:s.00P');
        }
        if (null == $date) {
            $date = self::parseXMLGregorianCalendarTZ($text, 'j-M-Y H:i:s.0P');
        }
        if (null == $date) {
            $date = self::parseXMLGregorianCalendarTZ($text, 'j-M-Y H:i:sP');
        }
        if (null == $date) {
            $date = self::parseXMLGregorianCalendarDT($text, 'j-M-Y H:i:s.000');
        }
        if (null == $date) {
            $date = self::parseXMLGregorianCalendarDT($text, 'j-M-Y H:i:s.00');
        }
        if (null == $date) {
            $date = self::parseXMLGregorianCalendarDT($text, 'j-M-Y H:i:s.0');
        }
        if (null == $date) {
            $date = self::parseXMLGregorianCalendarDT($text, 'j-M-Y H:i:s');
        }

        return $date;
    }

    /**
     * @param $text
     * @param $format
     *
     * @return bool|\DateTime|null
     */
    private static function parseXMLGregorianCalendarTZ($text, $format)
    {
        $count = 1;
        try {
            $tmp = \str_replace('T', ' ', $text, $count);

            return DateTime::createFromFormat($format, $tmp);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param $text
     * @param $format
     *
     * @return bool|DateTime|null
     */
    private static function parseXMLGregorianCalendarDT($text, $format)
    {
        $count = 1;
        try {
            $tmp = \str_replace('T', ' ', $text, $count);

            return DateTime::createFromFormat($format, $tmp);
        } catch (\Exception $e) {
            return null;
        }
    }
}
