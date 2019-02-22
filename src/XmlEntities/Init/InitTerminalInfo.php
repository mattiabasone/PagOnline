<?php

namespace PagOnline\XmlEntities\Init;

use PagOnline\XmlEntities\BaseXmlEntity;

/**
 * Class InitTerminalInfo.
 */
class InitTerminalInfo extends BaseXmlEntity
{
    /**
     * @var string
     */
    public $tid;

    /**
     * @var string
     */
    public $payInstrToken;

    /**
     * @var string
     */
    public $billingID;
}
