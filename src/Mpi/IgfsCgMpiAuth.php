<?php

namespace PagOnline\Mpi;

use PagOnline\IgfsUtils;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class IgfsCgMpiAuth.
 */
class IgfsCgMpiAuth extends BaseIgfsCgMpi
{
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgMpiAuthRequest::class;

    public $paRes;
    public $md;

    public $authStatus;
    public $cavv;
    public $eci;

    /**
     * @return array
     */
    protected function getAdditionalRequestSignatureFields(): array
    {
        return [
            $this->paRes, // PARES
            $this->md, // MD
        ];
    }

    protected function resetFields()
    {
        parent::resetFields();
        $this->paRes = null;
        $this->md = null;

        $this->authStatus = null;
        $this->cavv = null;
        $this->eci = null;
    }

    protected function checkFields()
    {
        parent::checkFields();

        if (empty($this->paRes)) {
            throw new IgfsMissingParException('Missing paRes');
        }
        if (empty($this->md)) {
            throw new IgfsMissingParException('Missing md');
        }
    }

    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $request = $this->replaceRequest($request, '{paRes}', $this->paRes);

        return $this->replaceRequest($request, '{md}', $this->md);
    }

    /**
     * @param $response
     */
    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        $this->authStatus = IgfsUtils::getValue($response, 'authStatus');
        // Opzionale
        $this->cavv = IgfsUtils::getValue($response, 'cavv');
        // Opzionale
        $this->eci = IgfsUtils::getValue($response, 'eci');
    }

    /**
     * @param $response
     *
     * @throws \PagOnline\Exceptions\IgfsException
     *
     * @return string
     */
    protected function getResponseSignature($response)
    {
        $fields = [
            IgfsUtils::getValue($response, 'tid'), // TID
            IgfsUtils::getValue($response, 'shopID'), // SHOPID
            IgfsUtils::getValue($response, 'rc'), // RC
            IgfsUtils::getValue($response, 'errorDesc'), // ERRORDESC
            IgfsUtils::getValue($response, 'authStatus'), // AUTHSTATUS
            IgfsUtils::getValue($response, 'cavv'), // CAVV
            IgfsUtils::getValue($response, 'eci'),
        ]; // ECI
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORCODE|AUTHSTATUS|CAVV|ECI
        return $this->getSignature($fields);
    }
}
