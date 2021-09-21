<?php

namespace PagOnline\Mpi;

use PagOnline\Exceptions\IgfsException;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\IgfsUtils;

class IgfsCgMpiAuth extends BaseIgfsCgMpi
{
    public $paRes;
    public $md;

    public $authStatus;
    public $cavv;
    public $eci;
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgMpiAuthRequest::class;

    public function resetFields()
    {
        parent::resetFields();
        $this->paRes = null;
        $this->md = null;

        $this->authStatus = null;
        $this->cavv = null;
        $this->eci = null;
    }

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

    /**
     * {@inheritdoc}
     */
    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $this->replaceRequestParameter($request, 'paRes', $this->paRes);
        $this->replaceRequestParameter($request, 'md', $this->md);

        return $request;
    }

    /**
     * @param array $response
     */
    protected function parseResponseMap($response): void
    {
        parent::parseResponseMap($response);
        $this->authStatus = IgfsUtils::getValue($response, 'authStatus');
        // Opzionale
        $this->cavv = IgfsUtils::getValue($response, 'cavv');
        // Opzionale
        $this->eci = IgfsUtils::getValue($response, 'eci');
    }

    /**
     * @param array $response
     *
     * @throws IgfsException
     *
     * @return string
     */
    protected function getResponseSignature($response): string
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
