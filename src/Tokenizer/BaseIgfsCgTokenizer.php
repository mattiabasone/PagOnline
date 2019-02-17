<?php

namespace PagOnline\Tokenizer;

use PagOnline\BaseIgfsCg;
use PagOnline\Exceptions\IgfsMissingParException;

abstract class BaseIgfsCgTokenizer extends BaseIgfsCg
{
    public $shopID; // chiave messaggio

    protected function resetFields()
    {
        parent::resetFields();
        $this->shopID = null;
    }

    protected function checkFields()
    {
        parent::checkFields();
        if (null == $this->shopID || '' == $this->shopID) {
            throw new IgfsMissingParException('Missing shopID');
        }
    }

    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $request = $this->replaceRequest($request, '{shopID}', $this->shopID);

        return $request;
    }

    protected function getServicePort()
    {
        return 'TokenizerGatewayPort';
    }
}
