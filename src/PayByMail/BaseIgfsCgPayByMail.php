<?php

namespace PagOnline\PayByMail;

use PagOnline\BaseIgfsCg;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class BaseIgfsCgPayByMail.
 */
abstract class BaseIgfsCgPayByMail extends BaseIgfsCg
{
    /**
     * @throws \PagOnline\Exceptions\IgfsMissingParException
     */
    protected function checkFields()
    {
        parent::checkFields();
        if ($this->shopID == null || $this->shopID == '') {
            throw new IgfsMissingParException('Missing shopID');
        }
    }

    /**
     * @return string
     */
    protected function getServicePort()
    {
        return 'PayByMailGatewayPort';
    }
}
