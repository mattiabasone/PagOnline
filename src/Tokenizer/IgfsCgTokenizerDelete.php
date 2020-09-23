<?php

namespace PagOnline\Tokenizer;

use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\IgfsUtils;

/**
 * Class IgfsCgTokenizerDelete.
 */
class IgfsCgTokenizerDelete extends BaseIgfsCgTokenizer
{
    public $payInstrToken;
    public $billingID;
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgTokenizerDeleteRequest::class;

    public function resetFields()
    {
        parent::resetFields();
        $this->payInstrToken = null;
        $this->billingID = null;
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

    protected function checkFields()
    {
        parent::checkFields();
        if ($this->payInstrToken == null) {
            throw new IgfsMissingParException('Missing payInstrToken');
        }
    }

    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $this->replaceRequestParameter($request, 'payInstrToken', $this->payInstrToken);
        $this->replaceRequestParameter($request, 'billingID', $this->billingID);

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
