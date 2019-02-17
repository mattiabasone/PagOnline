<?php

namespace PagOnline\Mpi;

/**
 * Class IgfsCgMpiEnroll.
 */
class IgfsCgMpiEnroll extends BaseIgfsCgMpi
{
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgMpiEnrollRequest::class;

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

    public function __construct()
    {
        parent::__construct();
    }

    protected function resetFields()
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

    protected function checkFields()
    {
        parent::checkFields();
        if (null == $this->amount) {
            throw new IgfsMissingParException('Missing amount');
        }
        if (null == $this->currencyCode) {
            throw new IgfsMissingParException('Missing currencyCode');
        }
        if (null == $this->pan) {
            if (null == $this->payInstrToken) {
                throw new IgfsMissingParException('Missing pan');
            }
        }
        if (null != $this->pan) {
            // Se è stato impostato il pan verifico...
            if ('' == $this->pan) {
                throw new IgfsMissingParException('Missing pan');
            }
        }

        if (null != $this->payInstrToken) {
            // Se è stato impostato il payInstrToken verifico...
            if ('' == $this->payInstrToken) {
                throw new IgfsMissingParException('Missing payInstrToken');
            }
        }

        if (null == $this->termURL) {
            throw new IgfsMissingParException('Missing termURL');
        }
    }

    protected function buildRequest()
    {
        $request = parent::buildRequest();
        if (null != $this->shopUserRef) {
            $request = $this->replaceRequest($request, '{shopUserRef}', '<shopUserRef><![CDATA['.$this->shopUserRef.']]></shopUserRef>');
        } else {
            $request = $this->replaceRequest($request, '{shopUserRef}', '');
        }

        $request = $this->replaceRequest($request, '{amount}', $this->amount);
        $request = $this->replaceRequest($request, '{currencyCode}', $this->currencyCode);

        if (null != $this->pan) {
            $request = $this->replaceRequest($request, '{pan}', '<pan><![CDATA['.$this->pan.']]></pan>');
        } else {
            $request = $this->replaceRequest($request, '{pan}', '');
        }

        if (null != $this->payInstrToken) {
            $request = $this->replaceRequest($request, '{payInstrToken}', '<payInstrToken><![CDATA['.$this->payInstrToken.']]></payInstrToken>');
        } else {
            $request = $this->replaceRequest($request, '{payInstrToken}', '');
        }
        if (null != $this->billingID) {
            $request = $this->replaceRequest($request, '{billingID}', '<billingID><![CDATA['.$this->billingID.']]></billingID>');
        } else {
            $request = $this->replaceRequest($request, '{billingID}', '');
        }

        if (null != $this->expireMonth) {
            $request = $this->replaceRequest($request, '{expireMonth}', '<expireMonth><![CDATA['.$this->expireMonth.']]></expireMonth>');
        } else {
            $request = $this->replaceRequest($request, '{expireMonth}', '');
        }
        if (null != $this->expireYear) {
            $request = $this->replaceRequest($request, '{expireYear}', '<expireYear><![CDATA['.$this->expireYear.']]></expireYear>');
        } else {
            $request = $this->replaceRequest($request, '{expireYear}', '');
        }

        $request = $this->replaceRequest($request, '{termURL}', $this->termURL);

        if (null != $this->description) {
            $request = $this->replaceRequest($request, '{description}', '<description><![CDATA['.$this->description.']]></description>');
        } else {
            $request = $this->replaceRequest($request, '{description}', '');
        }

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

    protected function setRequestSignature($request)
    {
        // signature dove il buffer e' cosi composto APIVERSION|TID|SHOPID|SHOPUSERREF|AMOUNT|CURRENCYCODE|PAN|PAYINSTRTOKEN|EXPIREMONTH|EXPIREYEAR|TERMURL|DESCRIPTION|UDF1|UDF2|UDF3|UDF4|UDF5
        $fields = [
                $this->getVersion(), // APIVERSION
                $this->tid, // TID
                $this->merID, // MERID
                $this->payInstr, // PAYINSTR
                $this->shopID, // SHOPID
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
                $this->addInfo5, ]; // UDF5
        $signature = $this->getSignature($this->kSig, // KSIGN
                $fields);
        $request = $this->replaceRequest($request, '{signature}', $signature);

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
        return $this->getSignature($this->kSig, // KSIGN
                $fields);
    }

    protected function getFileName()
    {
        return 'IGFS_CG_API/mpi/IgfsCgMpiEnroll.request';
    }
}
