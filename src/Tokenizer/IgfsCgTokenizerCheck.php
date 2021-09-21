<?php

namespace PagOnline\Tokenizer;

use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\IgfsUtils;

class IgfsCgTokenizerCheck extends BaseIgfsCgTokenizer
{
    public $payInstrToken;
    public $billingID;

    public $maskedPan;
    public $expireMonth;
    public $expireYear;
    public $accountName;
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgTokenizerCheckRequest::class;

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
     * {@inheritdoc}
     */
    protected function getAdditionalRequestSignatureFields(): array
    {
        return [
            $this->payInstrToken,
        ];
    }

    /**
     * @throws IgfsMissingParException
     */
    protected function checkFields()
    {
        parent::checkFields();
        if ($this->payInstrToken == null) {
            throw new IgfsMissingParException('Missing payInstrToken');
        }
    }

    /**
     * @return mixed|string
     */
    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $this->replaceRequestParameter($request, 'payInstrToken', $this->payInstrToken);
        $this->replaceRequestParameter($request, 'billingID', $this->billingID);

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
     * @param array $response
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
