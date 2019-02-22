<?php

namespace PagOnline\Tokenizer;

use PagOnline\IgfsUtils;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class IgfsCgTokenizerCheck.
 */
class IgfsCgTokenizerCheck extends BaseIgfsCgTokenizer
{
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgTokenizerCheckRequest::class;

    public $payInstrToken;
    public $billingID;

    public $maskedPan;
    public $expireMonth;
    public $expireYear;
    public $accountName;

    /**
     * {@inheritdoc}
     */
    protected function getAdditionalRequestSignatureFields(): array
    {
        return [
            $this->payInstrToken,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function resetFields()
    {
        parent::resetFields();
        $this->payInstrToken = null;
        $this->billingID = null;

        $this->maskedPan = null;
        $this->expireMonth = null;
        $this->expireYear = null;
        $this->accountName = null;
    }

    /**
     * @throws IgfsMissingParException
     */
    protected function checkFields()
    {
        parent::checkFields();
        if (null == $this->payInstrToken) {
            throw new IgfsMissingParException('Missing payInstrToken');
        }
    }

    /**
     * @return mixed|string
     */
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

    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        // Opzionale
        $this->maskedPan = IgfsUtils::getValue($response, 'maskedPan');
        // Opzionale
        $this->expireMonth = IgfsUtils::getValue($response, 'expireMonth');
        // Opzionale
        $this->expireYear = IgfsUtils::getValue($response, 'expireYear');
        // Opzionale
        $this->accountName = IgfsUtils::getValue($response, 'accountName');
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
        ];
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC
        return $this->getSignature($fields);
    }
}
