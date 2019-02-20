<?php

namespace PagOnline\XmlEntities;

use SimpleXMLElement;
use PagOnline\IgfsUtils;

/**
 * Class Level3Info.
 */
class Level3Info extends BaseXmlEntity
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

    protected $dates = [
        'destinationDate',
    ];

    /**
     * @var array
     */
    protected $entityAttributes = [
        'product' => [
            'type' => 'array',
            'namespace' => Level3InfoProduct::class,
        ],
    ];

    public static function fromXml($xml, $tname)
    {
        if (empty($xml)) {
            return null;
        }

        $dom = new SimpleXMLElement($xml, LIBXML_NOERROR, false);
        if (0 == \count($dom)) {
            return null;
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
