<?php

namespace PagOnline\Init;

use PagOnline\BaseIgfsCg;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\IgfsUtils;
use SimpleXMLElement;

/**
 * Class IgfsCgSelector
 * @package PagOnline\Init
 */
class IgfsCgSelector extends BaseIgfsCgInit
{
    public $shopUserRef;
    public $trType = 'AUTH';
    public $amount;
    public $currencyCode;
    public $langID = 'EN';
    public $addInfo1;
    public $addInfo2;
    public $addInfo3;
    public $addInfo4;
    public $addInfo5;
    public $payInstrToken;
    public $billingID;

    public $termInfo;

    protected function resetFields()
    {
        parent::resetFields();
        $this->shopUserRef = null;
        $this->trType = 'AUTH';
        $this->amount = null;
        $this->currencyCode = null;
        $this->langID = 'EN';
        $this->addInfo1 = null;
        $this->addInfo2 = null;
        $this->addInfo3 = null;
        $this->addInfo4 = null;
        $this->addInfo5 = null;
        $this->payInstrToken = null;
        $this->billingID = null;

        $this->termInfo = null;
    }

    protected function checkFields()
    {
        parent::checkFields();
        if (null == $this->trType) {
            throw new IgfsMissingParException('Missing trType');
        }
        if ('TOKENIZE' != $this->trType) {
            if (null == $this->amount) {
                throw new IgfsMissingParException('Missing amount');
            }
            if (null == $this->currencyCode) {
                throw new IgfsMissingParException('Missing currencyCode');
            }
        }
        if (null == $this->langID) {
            throw new IgfsMissingParException('Missing langID');
        }
        if (null != $this->payInstrToken) {
            // Se è stato impostato il payInstrToken verifico...
            if ('' == $this->payInstrToken) {
                throw new IgfsMissingParException('Missing payInstrToken');
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

        $request = $this->replaceRequest($request, '{trType}', $this->trType);
        if (null != $this->amount) {
            $request = $this->replaceRequest($request, '{amount}', '<amount><![CDATA['.$this->amount.']]></amount>');
        } else {
            $request = $this->replaceRequest($request, '{amount}', '');
        }
        if (null != $this->currencyCode) {
            $request = $this->replaceRequest($request, '{currencyCode}', '<currencyCode><![CDATA['.$this->currencyCode.']]></currencyCode>');
        } else {
            $request = $this->replaceRequest($request, '{currencyCode}', '');
        }
        $request = $this->replaceRequest($request, '{langID}', $this->langID);

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

        return $request;
    }

    protected function setRequestSignature($request)
    {
        // signature dove il buffer e' cosi composto APIVERSION|TID|SHOPID|SHOPUSERREF|TRTYPE|AMOUNT|CURRENCYCODE|LANGID|UDF1|UDF2|UDF3|UDF4|UDF5|PAYINSTRTOKEN
        $fields = [
            $this->getVersion(), // APIVERSION
            $this->tid, // TID
            $this->merID, // MERID
            $this->payInstr, // PAYINSTR
            $this->shopID, // SHOPID
            $this->shopUserRef, // SHOPUSERREF
            $this->trType, // TRTYPE
            $this->amount, // AMOUNT
            $this->currencyCode, // CURRENCYCODE
            $this->langID, // LANGID
            $this->addInfo1, // UDF1
            $this->addInfo2, // UDF2
            $this->addInfo3, // UDF3
            $this->addInfo4, // UDF4
            $this->addInfo5, // UDF5
            $this->payInstrToken, ]; // PAYINSTRTOKEN
        $signature = $this->getSignature($this->kSig, // KSIGN
            $fields);

        return $this->replaceRequest($request, '{signature}', $signature);
    }

    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        try {
            $xml = $response[BaseIgfsCg::$RESPONSE];

            $xml = \str_replace('<soap:', '<', $xml);
            $xml = \str_replace('</soap:', '</', $xml);
            $dom = new SimpleXMLElement($xml, LIBXML_NOERROR, false);
            if (0 == \count($dom)) {
                return;
            }

            $tmp = \str_replace('<Body>', '', $dom->Body->asXML());
            $tmp = \str_replace('</Body>', '', $tmp);
            $dom = new SimpleXMLElement($tmp, LIBXML_NOERROR, false);
            if (0 == \count($dom)) {
                return;
            }

            $xml_response = IgfsUtils::parseResponseFields($dom->response);
            if (isset($xml_response['termInfo'])) {
                $termInfo = [];
                foreach ($dom->response->children() as $item) {
                    if ('termInfo' == $item->getName()) {
                        $termInfo[] = SelectorTerminalInfo::fromXml($item->asXML(), 'termInfo');
                    }
                }
                $this->termInfo = $termInfo;
            }
        } catch (\Exception $e) {
            $this->termInfo = null;
        }
    }

    protected function getResponseSignature($response)
    {
        $fields = [
            IgfsUtils::getValue($response, 'tid'), // TID
            IgfsUtils::getValue($response, 'shopID'), // SHOPID
            IgfsUtils::getValue($response, 'rc'), // RC
            IgfsUtils::getValue($response, 'errorDesc'),
        ]; // ERRORDESC
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC|PAYMENTID|REDIRECTURL
        return $this->getSignature($this->kSig, // KSIGN
            $fields);
    }

    protected function getFileName()
    {
        return 'IGFS_CG_API/init/IgfsCgSelector.request';
    }
}
