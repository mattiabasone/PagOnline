<?php

namespace PagOnline\XmlEntities;

/**
 * Class MandateInfo.
 */
class MandateInfo extends BaseXmlEntity
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

    /**
     * @var array
     */
    protected $dates = [
        'durationStartDate',
        'durationEndDate',
        'firstCollectionDate',
        'finalCollectionDate',
    ];
}
