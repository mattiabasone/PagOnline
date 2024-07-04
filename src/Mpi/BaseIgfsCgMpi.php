<?php

namespace PagOnline\Mpi;

use PagOnline\BaseIgfsCg;
use PagOnline\IgfsUtils;

abstract class BaseIgfsCgMpi extends BaseIgfsCg
{
    /**
     * @var mixed
     */
    public $xid;

    public function resetFields(): void
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
     * @param array $response
     */
    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        // Opzionale
        $this->xid = IgfsUtils::getValue($response, 'xid');
    }
}
