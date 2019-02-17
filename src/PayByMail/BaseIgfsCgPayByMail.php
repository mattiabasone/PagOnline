<?php

namespace PagOnline\PayByMail;

use PagOnline\BaseIgfsCg;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class BaseIgfsCgPayByMail.
 */
abstract class BaseIgfsCgPayByMail extends BaseIgfsCg
{
    public $shopID; // chiave messaggio

    protected function resetFields()
    {
        parent::resetFields();
        $this->shopID = null;
    }

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
     * @return mixed|string
     */
    protected function buildRequest()
    {
        $request = parent::buildRequest();

        return $this->replaceRequest($request, '{shopID}', $this->shopID);
    }

    /**
     * @return string
     */
    protected function getServicePort()
    {
        return 'PayByMailGatewayPort';
    }
}
