<?php

namespace PagOnline;

use SimpleXMLElement;

/**
 * Class Level3Info
 * @package PagOnline
 */
class Level3Info
{
    public $invoiceNumber;
    public $senderPostalCode;
    public $senderCountryCode;
    public $destinationName;
    public $destinationStreet;
    public $destinationStreet2;
    public $destinationStreet3;
    public $destinationCity;
    public $destinationState;
    public $destinationPostalCode;
    public $destinationCountryCode;
    public $destinationPhone;
    public $destinationFax;
    public $destinationEmail;
    public $destinationDate;
    public $billingName;
    public $billingStreet;
    public $billingStreet2;
    public $billingStreet3;
    public $billingCity;
    public $billingState;
    public $billingPostalCode;
    public $billingCountryCode;
    public $billingPhone;
    public $billingFax;
    public $billingEmail;
    public $freightAmount;
    public $taxAmount;
    public $vat;
    public $note;
    public $product;

    public function toXml($tname)
    {
        $sb = '';
        $sb .= '<'.$tname.'>';
        if (null != $this->invoiceNumber) {
            $sb .= '<invoiceNumber><![CDATA[';
            $sb .= $this->invoiceNumber;
            $sb .= ']]></invoiceNumber>';
        }
        if (null != $this->senderPostalCode) {
            $sb .= '<senderPostalCode><![CDATA[';
            $sb .= $this->senderPostalCode;
            $sb .= ']]></senderPostalCode>';
        }
        if (null != $this->senderCountryCode) {
            $sb .= '<senderCountryCode><![CDATA[';
            $sb .= $this->senderCountryCode;
            $sb .= ']]></senderCountryCode>';
        }
        if (null != $this->destinationName) {
            $sb .= '<destinationName><![CDATA[';
            $sb .= $this->destinationName;
            $sb .= ']]></destinationName>';
        }
        if (null != $this->destinationStreet) {
            $sb .= '<destinationStreet><![CDATA[';
            $sb .= $this->destinationStreet;
            $sb .= ']]></destinationStreet>';
        }
        if (null != $this->destinationStreet2) {
            $sb .= '<destinationStreet2><![CDATA[';
            $sb .= $this->destinationStreet2;
            $sb .= ']]></destinationStreet2>';
        }
        if (null != $this->destinationStreet3) {
            $sb .= '<destinationStreet3><![CDATA[';
            $sb .= $this->destinationStreet3;
            $sb .= ']]></destinationStreet3>';
        }
        if (null != $this->destinationCity) {
            $sb .= '<destinationCity><![CDATA[';
            $sb .= $this->destinationCity;
            $sb .= ']]></destinationCity>';
        }
        if (null != $this->destinationState) {
            $sb .= '<destinationState><![CDATA[';
            $sb .= $this->destinationState;
            $sb .= ']]></destinationState>';
        }
        if (null != $this->destinationPostalCode) {
            $sb .= '<destinationPostalCode><![CDATA[';
            $sb .= $this->destinationPostalCode;
            $sb .= ']]></destinationPostalCode>';
        }
        if (null != $this->destinationCountryCode) {
            $sb .= '<destinationCountryCode><![CDATA[';
            $sb .= $this->destinationCountryCode;
            $sb .= ']]></destinationCountryCode>';
        }
        if (null != $this->destinationPhone) {
            $sb .= '<destinationPhone><![CDATA[';
            $sb .= $this->destinationPhone;
            $sb .= ']]></destinationPhone>';
        }
        if (null != $this->destinationFax) {
            $sb .= '<destinationFax><![CDATA[';
            $sb .= $this->destinationFax;
            $sb .= ']]></destinationFax>';
        }
        if (null != $this->destinationEmail) {
            $sb .= '<destinationEmail><![CDATA[';
            $sb .= $this->destinationEmail;
            $sb .= ']]></destinationEmail>';
        }
        if (null != $this->destinationDate) {
            $sb .= '<destinationDate><![CDATA[';
            $sb .= IgfsUtils::formatXMLGregorianCalendar($this->destinationDate);
            $sb .= ']]></destinationDate>';
        }
        if (null != $this->billingName) {
            $sb .= '<billingName><![CDATA[';
            $sb .= $this->billingName;
            $sb .= ']]></billingName>';
        }
        if (null != $this->billingStreet) {
            $sb .= '<billingStreet><![CDATA[';
            $sb .= $this->billingStreet;
            $sb .= ']]></billingStreet>';
        }
        if (null != $this->billingStreet2) {
            $sb .= '<billingStreet2><![CDATA[';
            $sb .= $this->billingStreet2;
            $sb .= ']]></billingStreet2>';
        }
        if (null != $this->billingStreet3) {
            $sb .= '<billingStreet3><![CDATA[';
            $sb .= $this->billingStreet3;
            $sb .= ']]></billingStreet3>';
        }
        if (null != $this->billingCity) {
            $sb .= '<billingCity><![CDATA[';
            $sb .= $this->billingCity;
            $sb .= ']]></billingCity>';
        }
        if (null != $this->billingState) {
            $sb .= '<billingState><![CDATA[';
            $sb .= $this->billingState;
            $sb .= ']]></billingState>';
        }
        if (null != $this->billingPostalCode) {
            $sb .= '<billingPostalCode><![CDATA[';
            $sb .= $this->billingPostalCode;
            $sb .= ']]></billingPostalCode>';
        }
        if (null != $this->billingCountryCode) {
            $sb .= '<billingCountryCode><![CDATA[';
            $sb .= $this->billingCountryCode;
            $sb .= ']]></billingCountryCode>';
        }
        if (null != $this->billingPhone) {
            $sb .= '<billingPhone><![CDATA[';
            $sb .= $this->billingPhone;
            $sb .= ']]></billingPhone>';
        }
        if (null != $this->billingFax) {
            $sb .= '<billingFax><![CDATA[';
            $sb .= $this->billingFax;
            $sb .= ']]></billingFax>';
        }
        if (null != $this->billingEmail) {
            $sb .= '<billingEmail><![CDATA[';
            $sb .= $this->billingEmail;
            $sb .= ']]></billingEmail>';
        }
        if (null != $this->freightAmount) {
            $sb .= '<freightAmount><![CDATA[';
            $sb .= $this->freightAmount;
            $sb .= ']]></freightAmount>';
        }
        if (null != $this->taxAmount) {
            $sb .= '<taxAmount><![CDATA[';
            $sb .= $this->taxAmount;
            $sb .= ']]></taxAmount>';
        }
        if (null != $this->vat) {
            $sb .= '<vat><![CDATA[';
            $sb .= $this->vat;
            $sb .= ']]></vat>';
        }
        if (null != $this->note) {
            $sb .= '<note><![CDATA[';
            $sb .= $this->note;
            $sb .= ']]></note>';
        }
        if (null != $this->product) {
            foreach ($this->product as $item) {
                $sb .= $item->toXml('product');
            }
        }
        $sb .= '</'.$tname.'>';

        return $sb;
    }

    public static function fromXml($xml, $tname)
    {
        if ('' == $xml || null == $xml) {
            return;
        }

        $dom = new SimpleXMLElement($xml, LIBXML_NOERROR, false);
        if (0 == \count($dom)) {
            return;
        }

        $response = IgfsUtils::parseResponseFields($dom);
        $level3Info = null;
        if (isset($response) && \count($response) > 0) {
            $level3Info = new self();

            $level3Info->invoiceNumber = (IgfsUtils::getValue($response, 'invoiceNumber'));
            $level3Info->senderPostalCode = (IgfsUtils::getValue($response, 'senderPostalCode'));
            $level3Info->senderCountryCode = (IgfsUtils::getValue($response, 'senderCountryCode'));

            $level3Info->destinationName = (IgfsUtils::getValue($response, 'destinationName'));
            $level3Info->destinationStreet = (IgfsUtils::getValue($response, 'destinationStreet'));
            $level3Info->destinationStreet2 = (IgfsUtils::getValue($response, 'destinationStreet2'));
            $level3Info->destinationStreet3 = (IgfsUtils::getValue($response, 'destinationStreet3'));
            $level3Info->destinationCity = (IgfsUtils::getValue($response, 'destinationCity'));
            $level3Info->destinationState = (IgfsUtils::getValue($response, 'destinationState'));
            $level3Info->destinationPostalCode = (IgfsUtils::getValue($response, 'destinationPostalCode'));
            $level3Info->destinationCountryCode = (IgfsUtils::getValue($response, 'destinationCountryCode'));
            $level3Info->destinationPhone = (IgfsUtils::getValue($response, 'destinationPhone'));
            $level3Info->destinationFax = (IgfsUtils::getValue($response, 'destinationFax'));
            $level3Info->destinationEmail = (IgfsUtils::getValue($response, 'destinationEmail'));
            $level3Info->destinationDate = (IgfsUtils::parseXMLGregorianCalendar(IgfsUtils::getValue($response, 'destinationDate')));

            $level3Info->billingName = (IgfsUtils::getValue($response, 'billingName'));
            $level3Info->billingStreet = (IgfsUtils::getValue($response, 'billingStreet'));
            $level3Info->billingStreet2 = (IgfsUtils::getValue($response, 'billingStreet2'));
            $level3Info->billingStreet3 = (IgfsUtils::getValue($response, 'billingStreet3'));
            $level3Info->billingCity = (IgfsUtils::getValue($response, 'billingCity'));
            $level3Info->billingState = (IgfsUtils::getValue($response, 'billingState'));
            $level3Info->billingPostalCode = (IgfsUtils::getValue($response, 'billingPostalCode'));
            $level3Info->billingCountryCode = (IgfsUtils::getValue($response, 'billingCountryCode'));
            $level3Info->billingPhone = (IgfsUtils::getValue($response, 'billingPhone'));
            $level3Info->billingFax = (IgfsUtils::getValue($response, 'billingFax'));
            $level3Info->billingEmail = (IgfsUtils::getValue($response, 'billingEmail'));

            $level3Info->freightAmount = (IgfsUtils::getValue($response, 'freightAmount'));
            $level3Info->taxAmount = (IgfsUtils::getValue($response, 'taxAmount'));
            $level3Info->vat = (IgfsUtils::getValue($response, 'vat'));
            $level3Info->note = (IgfsUtils::getValue($response, 'note'));

            if (isset($response['product'])) {
                $product = [];
                foreach ($dom->children() as $item) {
                    if ('product' == $item->getName()) {
                        $product[] = Level3InfoProduct::fromXml($item->asXML(), 'product');
                    }
                }
                $level3Info->product = $product;
            }
        }

        return $level3Info;
    }
}
