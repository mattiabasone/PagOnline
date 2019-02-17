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

        if (null != $this->paRes) {
            if ('' == $this->paRes) {
                throw new IgfsMissingParException('Missing paRes');
            }
        }
        if (null != $this->md) {
            if ('' == $this->md) {
                throw new IgfsMissingParException('Missing md');
            }
        }
    }

    protected function buildRequest()
    {
        $request = parent::buildRequest();

        $request = $this->replaceRequest($request, '{paRes}', $this->paRes);
        $request = $this->replaceRequest($request, '{md}', $this->md);

        return $request;
    }

    protected function setRequestSignature($request)
    {
        // signature dove il buffer e' cosi composto APIVERSION|TID|SHOPID|PARES|MD
        $fields = [
            $this->getVersion(), // APIVERSION
            $this->tid, // TID
            $this->merID, // MERID
            $this->payInstr, // PAYINSTR
            $this->shopID, // SHOPID
            $this->paRes, // PARES
            $this->md, ]; // MD
        $signature = $this->getSignature($this->kSig, // KSIGN
            $fields);
        $request = $this->replaceRequest($request, '{signature}', $signature);

        return $request;
    }

    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        $this->authStatus = IgfsUtils::getValue($response, 'authStatus');
        // Opzionale
        $this->cavv = IgfsUtils::getValue($response, 'cavv');
        // Opzionale
        $this->eci = IgfsUtils::getValue($response, 'eci');
    }

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
        return $this->getSignature($this->kSig, // KSIGN
            $fields);
    }
}
