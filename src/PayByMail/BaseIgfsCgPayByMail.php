<?php

require_once 'IGFS_CG_API/BaseIgfsCg.php';

abstract class BaseIgfsCgPayByMail extends BaseIgfsCg
{
    public $shopID; // chiave messaggio

    public function __construct()
    {
        parent::__construct();
    }

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
        return 'PayByMailGatewayPort';
    }
}
