<?php

namespace PagOnline\Tokenizer;

use PagOnline\IgfsUtils;

/**
 * Class IgfsCgTokenizerDelete.
 */
class IgfsCgTokenizerDelete extends BaseIgfsCgTokenizer
{
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgTokenizerDeleteRequest::class;

    public $payInstrToken;
    public $billingID;

    protected function resetFields()
    {
        parent::resetFields();
        $this->payInstrToken = null;
        $this->billingID = null;
    }

    protected function checkFields()
    {
        parent::checkFields();
        if (null == $this->payInstrToken) {
            throw new IgfsMissingParException('Missing payInstrToken');
        }
    }

    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $request = $this->replaceRequest($request, '{payInstrToken}', $this->payInstrToken);
        if (null != $this->billingID) {
            $request = $this->replaceRequest($request, '{billingID}', '<billingID><![CDATA['.$this->billingID.']]></billingID>');
        } else {
            $request = $this->replaceRequest($request, '{billingID}', '');
        }

        return $request;
    }

    protected function setRequestSignature($request)
    {
        // signature dove il buffer e' cosi composto APIVERSION|TID|SHOPID|PAYINSTRTOKEN
        $fields = [
            $this->getVersion(), // APIVERSION
            $this->tid, // TID
            $this->merID, // MERID
            $this->payInstr, // PAYINSTR
            $this->shopID, // SHOPID
            $this->payInstrToken,
        ]; // PAYINSTRTOKEN
        $signature = $this->getSignature($this->kSig, // KSIGN
            $fields);
        $request = $this->replaceRequest($request, '{signature}', $signature);

        return $request;
    }

    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
    }

    protected function getResponseSignature($response)
    {
        $fields = [
            IgfsUtils::getValue($response, 'tid'), // TID
            IgfsUtils::getValue($response, 'shopID'), // SHOPID
            IgfsUtils::getValue($response, 'rc'), // RC
            IgfsUtils::getValue($response, 'errorDesc'), ]; // ERRORDESC
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC
        return $this->getSignature($this->kSig, // KSIGN
            $fields);
    }
}
