<?php

namespace PagOnline\Tokenizer;

use PagOnline\IgfsUtils;
use PagOnline\Exceptions\IgfsMissingParException;

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

    /**
     * {@inheritdoc}
     */
    protected function getAdditionalRequestSignatureFields(): array
    {
        return [
            $this->payInstrToken,
        ];
    }

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
            IgfsUtils::getValue($response, 'errorDesc'), ]; // ERRORDESC
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC
        return $this->getSignature($fields);
    }
}
