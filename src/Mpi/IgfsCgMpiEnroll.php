<?php

namespace PagOnline\Mpi;

use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\IgfsUtils;

/**
 * Class IgfsCgMpiEnroll.
 */
class IgfsCgMpiEnroll extends BaseIgfsCgMpi
{
    public $shopUserRef;
    public $amount;
    public $currencyCode;

    public $pan;
    public $payInstrToken;
    public $billingID;
    public $expireMonth;
    public $expireYear;
    public $termURL;
    public $description;

    public $addInfo1;
    public $addInfo2;
    public $addInfo3;
    public $addInfo4;
    public $addInfo5;

    public $enrStatus;
    public $paReq;
    public $md;
    public $acsURL;
    public $acsPage;
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgMpiEnrollRequest::class;

    /**
     * Reset request fields.
     */
    public function resetFields()
    {
        parent::resetFields();
        $this->shopUserRef = null;
        $this->amount = null;
        $this->currencyCode = null;

        $this->pan = null;
        $this->payInstrToken = null;
        $this->billingID = null;
        $this->expireMonth = null;
        $this->expireYear = null;
        $this->termURL = null;
        $this->description = null;

        $this->addInfo1 = null;
        $this->addInfo2 = null;
        $this->addInfo3 = null;
        $this->addInfo4 = null;
        $this->addInfo5 = null;

        $this->enrStatus = null;
        $this->paReq = null;
        $this->md = null;
        $this->acsURL = null;
        $this->acsPage = null;
    }

    protected function getAdditionalRequestSignatureFields(): array
    {
        return [
            $this->shopUserRef, // SHOPUSERREF
            $this->amount, // AMOUNT
            $this->currencyCode, // CURRENCYCODE
            $this->pan, // PAN
            $this->payInstrToken, // PAYINSTRTOKEN
            $this->expireMonth, // EXPIREMONTH
            $this->expireYear, // EXPIREYEAR
            $this->termURL, // TERMURL
            $this->description, // DESCRIPTION
            $this->addInfo1, // UDF1
            $this->addInfo2, // UDF2
            $this->addInfo3, // UDF3
            $this->addInfo4, // UDF4
            $this->addInfo5, // UDF5
        ];
    }

    protected function checkFields()
    {
        parent::checkFields();
        if ($this->amount == null) {
            throw new IgfsMissingParException('Missing amount');
        }
        if ($this->currencyCode == null) {
            throw new IgfsMissingParException('Missing currencyCode');
        }
        if ($this->pan === null && $this->payInstrToken === null) {
            throw new IgfsMissingParException('Missing pan');
        }
        if ($this->pan !== null && $this->pan === '') {
            throw new IgfsMissingParException('Missing pan');
        }

        if ($this->payInstrToken !== null && (string) $this->payInstrToken === '') {
            // Se è stato impostato il payInstrToken verifico...
            throw new IgfsMissingParException('Missing payInstrToken');
        }

        if ($this->termURL == null) {
            throw new IgfsMissingParException('Missing termURL');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $this->replaceRequestParameter($request, 'shopUserRef', $this->shopUserRef);
        $this->replaceRequestParameter($request, 'amount', $this->amount);
        $this->replaceRequestParameter($request, 'currencyCode', $this->currencyCode);
        $this->replaceRequestParameter($request, 'pan', $this->pan);
        $this->replaceRequestParameter($request, 'payInstrToken', $this->payInstrToken);
        $this->replaceRequestParameter($request, 'billingID', $this->billingID);
        $this->replaceRequestParameter($request, 'expireMonth', $this->expireMonth);
        $this->replaceRequestParameter($request, 'expireYear', $this->expireYear);
        $this->replaceRequestParameter($request, 'termURL', $this->termURL);
        $this->replaceRequestParameter($request, 'description', $this->description);
        $this->replaceRequestParameter($request, 'addInfo1', $this->addInfo1);
        $this->replaceRequestParameter($request, 'addInfo2', $this->addInfo2);
        $this->replaceRequestParameter($request, 'addInfo3', $this->addInfo3);
        $this->replaceRequestParameter($request, 'addInfo4', $this->addInfo4);
        $this->replaceRequestParameter($request, 'addInfo5', $this->addInfo5);

        return $request;
    }

    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        $this->enrStatus = IgfsUtils::getValue($response, 'enrStatus');
        // Opzionale
        $this->paReq = IgfsUtils::getValue($response, 'paReq');
        // Opzionale
        $this->md = IgfsUtils::getValue($response, 'md');
        // Opzionale
        $this->acsURL = IgfsUtils::getValue($response, 'acsURL');
        // Opzionale
        $this->acsPage = IgfsUtils::getValue($response, 'acsPage');
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
            IgfsUtils::getValue($response, 'enrStatus'), // ENRSTATUS
            IgfsUtils::getValue($response, 'paReq'), // PAREQ
            IgfsUtils::getValue($response, 'md'), // MD
            IgfsUtils::getValue($response, 'acsURL'), // ACSURL
            IgfsUtils::getValue($response, 'acsPage'), ]; // ACSPAGE
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORCODE|ENRSTATUS|PAREQ|MD|ACSURL|ACSPAGE
        return $this->getSignature($fields);
    }
}
