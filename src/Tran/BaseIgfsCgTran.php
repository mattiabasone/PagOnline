<?php

namespace PagOnline\Tran;

use PagOnline\IgfsUtils;
use PagOnline\BaseIgfsCg;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class BaseIgfsCgTran.
 */
abstract class BaseIgfsCgTran extends BaseIgfsCg
{
    public $addInfo1;
    public $addInfo2;
    public $addInfo3;
    public $addInfo4;
    public $addInfo5;

    public $tranID;

    protected function resetFields()
    {
        parent::resetFields();
        $this->addInfo1 = null;
        $this->addInfo2 = null;
        $this->addInfo3 = null;
        $this->addInfo4 = null;
        $this->addInfo5 = null;
        $this->tranID = null;
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
        if (null != $this->addInfo1) {
            $request = $this->replaceRequest($request, '{addInfo1}', '<addInfo1><![CDATA['.$this->addInfo1.']]></addInfo1>');
        } else {
            $request = $this->replaceRequest($request, '{addInfo1}', '');
        }
        if (null != $this->addInfo2) {
            $request = $this->replaceRequest($request, '{addInfo2}', '<addInfo2><![CDATA['.$this->addInfo2.']]></addInfo2>');
        } else {
            $request = $this->replaceRequest($request, '{addInfo2}', '');
        }
        if (null != $this->addInfo3) {
            $request = $this->replaceRequest($request, '{addInfo3}', '<addInfo3><![CDATA['.$this->addInfo3.']]></addInfo3>');
        } else {
            $request = $this->replaceRequest($request, '{addInfo3}', '');
        }
        if (null != $this->addInfo4) {
            $request = $this->replaceRequest($request, '{addInfo4}', '<addInfo4><![CDATA['.$this->addInfo4.']]></addInfo4>');
        } else {
            $request = $this->replaceRequest($request, '{addInfo4}', '');
        }
        if (null != $this->addInfo5) {
            $request = $this->replaceRequest($request, '{addInfo5}', '<addInfo5><![CDATA['.$this->addInfo5.']]></addInfo5>');
        } else {
            $request = $this->replaceRequest($request, '{addInfo5}', '');
        }

        return $request;
    }

    protected function getServicePort()
    {
        return 'PaymentTranGatewayPort';
    }

    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        // Opzionale
        $this->tranID = IgfsUtils::getValue($response, 'tranID');
        // Opzionale
        $this->addInfo1 = IgfsUtils::getValue($response, 'addInfo1');
        // Opzionale
        $this->addInfo2 = IgfsUtils::getValue($response, 'addInfo2');
        // Opzionale
        $this->addInfo3 = IgfsUtils::getValue($response, 'addInfo3');
        // Opzionale
        $this->addInfo4 = IgfsUtils::getValue($response, 'addInfo4');
        // Opzionale
        $this->addInfo5 = IgfsUtils::getValue($response, 'addInfo5');
    }
}
