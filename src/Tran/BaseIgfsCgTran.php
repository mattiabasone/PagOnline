<?php

namespace PagOnline\Tran;

use PagOnline\BaseIgfsCg;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\IgfsUtils;

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

    public function resetFields(): void
    {
        parent::resetFields();
        $this->addInfo1 = null;
        $this->addInfo2 = null;
        $this->addInfo3 = null;
        $this->addInfo4 = null;
        $this->addInfo5 = null;
        $this->tranID = null;
    }

    protected function checkFields(): void
    {
        parent::checkFields();
        if (empty($this->shopID)) {
            throw new IgfsMissingParException('Missing shopID');
        }
    }

    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $this->replaceRequestParameter($request, 'addInfo1', $this->addInfo1);
        $this->replaceRequestParameter($request, 'addInfo2', $this->addInfo2);
        $this->replaceRequestParameter($request, 'addInfo3', $this->addInfo3);
        $this->replaceRequestParameter($request, 'addInfo4', $this->addInfo4);
        $this->replaceRequestParameter($request, 'addInfo5', $this->addInfo5);

        return $request;
    }

    protected function getServicePort(): string
    {
        return 'PaymentTranGatewayPort';
    }

    protected function parseResponseMap($response): void
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
