<?php

namespace PagOnline\XmlEntities;

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
}
