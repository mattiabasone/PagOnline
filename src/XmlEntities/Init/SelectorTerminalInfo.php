<?php

namespace PagOnline\XmlEntities\Init;

use PagOnline\XmlEntities\BaseXmlEntity;

/**
 * Class SelectorTerminalInfo.
 */
class SelectorTerminalInfo extends BaseXmlEntity
{
    public $tid;
    public $description;
    public $payInstr;
    public $payInstrDescription;
    public $imgURL = [];

    /** @var array */
    protected $casts = [
        'imgURL' => 'array',
    ];
}
