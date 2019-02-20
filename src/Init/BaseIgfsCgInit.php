<?php

namespace PagOnline\Init;

use PagOnline\BaseIgfsCg;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class BaseIgfsCgInit.
 */
abstract class BaseIgfsCgInit extends BaseIgfsCg
{
    /**
     * @throws \PagOnline\Exceptions\IgfsMissingParException
     */
    protected function checkFields()
    {
        parent::checkFields();
        if (null == $this->shopID || '' == $this->shopID) {
            throw new IgfsMissingParException('Missing shopID');
        }
    }

    /**
     * @return string
     */
    protected function getServicePort()
    {
        return 'PaymentInitGatewayPort';
    }
}
