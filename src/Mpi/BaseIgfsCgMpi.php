<?php

namespace PagOnline\Mpi;

use PagOnline\IgfsUtils;
use PagOnline\BaseIgfsCg;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class BaseIgfsCgMpi.
 */
abstract class BaseIgfsCgMpi extends BaseIgfsCg
{
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgMpiEnrollRequest::class;

    public $shopID; // chiave messaggio

    public $xid;

    protected function resetFields()
    {
        parent::resetFields();
        $this->shopID = null;
        $this->xid = null;
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
        return 'MPIGatewayPort';
    }

    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        // Opzionale
        $this->xid = IgfsUtils::getValue($response, 'xid');
    }
}
