<?php

namespace PagOnline\Tran;

use PagOnline\IgfsUtils;

/**
 * Class IgfsCgCredit.
 */
class IgfsCgCredit extends BaseIgfsCgTran
{
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgCreditRequest::class;

    public $shopUserRef;
    public $amount;
    public $currencyCode;
    public $refTranID;
    public $pan;
    public $payInstrToken;
    public $billingID;
    public $expireMonth;
    public $expireYear;
    public $description;

    public $pendingAmount;

    protected function resetFields()
    {
        parent::resetFields();
        $this->shopUserRef = null;
        $this->amount = null;
        $this->currencyCode = null;
        $this->refTranID = null;
        $this->pan = null;
        $this->payInstrToken = null;
        $this->billingID = null;
        $this->expireMonth = null;
        $this->expireYear = null;
        $this->description = null;

        $this->pendingAmount = null;
    }

    protected function checkFields()
    {
        parent::checkFields();
        if (null == $this->amount) {
            throw new IgfsMissingParException('Missing amount');
        }
        if (null == $this->refTranID) {
            if (null == $this->pan) {
                if (null == $this->payInstrToken) {
                    throw new IgfsMissingParException('Missing refTranID');
                }
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

        if (null != $this->pan or null != $this->payInstrToken) {
            if (null == $this->currencyCode) {
                throw new IgfsMissingParException('Missing currencyCode');
            }
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
        if (null != $this->currencyCode) {
            $request = $this->replaceRequest($request, '{currencyCode}', '<currencyCode><![CDATA['.$this->currencyCode.']]></currencyCode>');
        } else {
            $request = $this->replaceRequest($request, '{currencyCode}', '');
        }

        if (null != $this->refTranID) {
            $request = $this->replaceRequest($request, '{refTranID}', '<refTranID><![CDATA['.$this->refTranID.']]></refTranID>');
        } else {
            $request = $this->replaceRequest($request, '{refTranID}', '');
        }

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

        if (null != $this->description) {
            $request = $this->replaceRequest($request, '{description}', '<description><![CDATA['.$this->description.']]></description>');
        } else {
            $request = $this->replaceRequest($request, '{description}', '');
        }

        return $request;
    }

    protected function setRequestSignature($request)
    {
        // signature dove il buffer e' cosi composto APIVERSION|TID|SHOPID|AMOUNT|CURRENCYCODE|REFORDERID|PAN|PAYINSTRTOKEN|EXPIREMONTH|EXPIREYEAR
        $fields = [
                $this->getVersion(), // APIVERSION
                $this->tid, // TID
                $this->merID, // MERID
                $this->payInstr, // PAYINSTR
                $this->shopID, // SHOPID
                $this->shopUserRef, // SHOPUSERREF
                $this->amount, // AMOUNT
                $this->currencyCode, // CURRENCYCODE
                $this->refTranID, // REFORDERID
                $this->pan, // PAN
                $this->payInstrToken, // PAYINSTRTOKEN
                $this->expireMonth, // EXPIREMONTH
                $this->expireYear, // EXPIREYEAR
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
        // Opzionale
        $this->pendingAmount = IgfsUtils::getValue($response, 'pendingAmount');
    }

    protected function getResponseSignature($response)
    {
        $fields = [
                IgfsUtils::getValue($response, 'tid'), // TID
                IgfsUtils::getValue($response, 'shopID'), // SHOPID
                IgfsUtils::getValue($response, 'rc'), // RC
                IgfsUtils::getValue($response, 'errorDesc'), // ERRORDESC
                IgfsUtils::getValue($response, 'tranID'), // ORDERID
                IgfsUtils::getValue($response, 'date'), // TRANDATE
                IgfsUtils::getValue($response, 'addInfo1'), // UDF1
                IgfsUtils::getValue($response, 'addInfo2'), // UDF2
                IgfsUtils::getValue($response, 'addInfo3'), // UDF3
                IgfsUtils::getValue($response, 'addInfo4'), // UDF4
                IgfsUtils::getValue($response, 'addInfo5'), ]; // UDF5
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC|ORDERID|DATE|UDF1|UDF2|UDF3|UDF4|UDF5
        return $this->getSignature($this->kSig, // KSIGN
                $fields);
    }
}
