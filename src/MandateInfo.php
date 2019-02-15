<?php

namespace PagOnline;

/**
 * Class MandateInfo
 * @package PagOnline
 */
class MandateInfo
{
    public $mandateID;
    public $contractID;
    public $sequenceType;
    public $frequency;
    public $durationStartDate;
    public $durationEndDate;
    public $firstCollectionDate;
    public $finalCollectionDate;
    public $maxAmount;

    public function __construct()
    {
    }

    public function toXml($tname)
    {
        $sb = '';
        $sb .= '<'.$tname.'>';
        if (null != $this->mandateID) {
            $sb .= '<mandateID><![CDATA[';
            $sb .= $this->mandateID;
            $sb .= ']]></mandateID>';
        }
        if (null != $this->contractID) {
            $sb .= '<contractID><![CDATA[';
            $sb .= $this->contractID;
            $sb .= ']]></contractID>';
        }
        if (null != $this->sequenceType) {
            $sb .= '<sequenceType><![CDATA[';
            $sb .= $this->sequenceType;
            $sb .= ']]></sequenceType>';
        }
        if (null != $this->frequency) {
            $sb .= '<frequency><![CDATA[';
            $sb .= $this->frequency;
            $sb .= ']]></frequency>';
        }
        if (null != $this->durationStartDate) {
            $sb .= '<durationStartDate><![CDATA[';
            $sb .= IgfsUtils::formatXMLGregorianCalendar($this->durationStartDate);
            $sb .= ']]></durationStartDate>';
        }
        if (null != $this->durationEndDate) {
            $sb .= '<durationEndDate><![CDATA[';
            $sb .= IgfsUtils::formatXMLGregorianCalendar($this->durationEndDate);
            $sb .= ']]></durationEndDate>';
        }
        if (null != $this->firstCollectionDate) {
            $sb .= '<firstCollectionDate><![CDATA[';
            $sb .= IgfsUtils::formatXMLGregorianCalendar($this->firstCollectionDate);
            $sb .= ']]></firstCollectionDate>';
        }
        if (null != $this->finalCollectionDate) {
            $sb .= '<finalCollectionDate><![CDATA[';
            $sb .= IgfsUtils::formatXMLGregorianCalendar($this->finalCollectionDate);
            $sb .= ']]></finalCollectionDate>';
        }
        if (null != $this->maxAmount) {
            $sb .= '<maxAmount><![CDATA[';
            $sb .= $this->maxAmount;
            $sb .= ']]></maxAmount>';
        }
        $sb .= '</'.$tname.'>';

        return $sb;
    }
}
