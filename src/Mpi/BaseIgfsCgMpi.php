<?php

namespace PagOnline\Mpi;

use PagOnline\BaseIgfsCg;
use PagOnline\IgfsUtils;

/**
 * Class BaseIgfsCgMpi.
 */
abstract class BaseIgfsCgMpi extends BaseIgfsCg
{
    /**
     * @var
     */
    public $xid;

    public function resetFields()
    {
        parent::resetFields();
        $this->xid = null;
    }

    /**
     * @return string
     */
    protected function getServicePort()
    {
        return 'MPIGatewayPort';
    }

    /**
     * @param $response
     */
    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        // Opzionale
        $this->xid = IgfsUtils::getValue($response, 'xid');
    }
}
