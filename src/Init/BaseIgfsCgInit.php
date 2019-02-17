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
     * Merchant identifier.
     *
     * @var string
     */
    public $shopID;

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
     * @return false|mixed|string
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
        return 'PaymentInitGatewayPort';
    }
}
