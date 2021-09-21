<?php

namespace PagOnline\Tokenizer;

use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\IgfsUtils;

/**
 * Class IgfsCgTokenizerEnroll.
 */
class IgfsCgTokenizerEnroll extends BaseIgfsCgTokenizer
{
    public $shopUserRef;
    public $pan;
    public $expireMonth;
    public $expireYear;
    public $accountName;
    public $payInstrToken;
    public $billingID;
    public $regenPayInstrToken;
    public $keepOnRegenPayInstrToken;
    public $payInstrTokenExpire;
    public $payInstrTokenUsageLimit;
    public $payInstrTokenAlg;
    public $addInfo1;
    public $addInfo2;
    public $addInfo3;
    public $addInfo4;
    public $addInfo5;
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgTokenizerEnrollRequest::class;

    /**
     * {@inheritdoc}
     */
    public function resetFields()
    {
        parent::resetFields();
        $this->shopUserRef = null;
        $this->pan = null;
        $this->expireMonth = null;
        $this->expireYear = null;
        $this->accountName = null;
        $this->payInstrToken = null;
        $this->billingID = null;
        $this->regenPayInstrToken = null;
        $this->keepOnRegenPayInstrToken = null;
        $this->payInstrTokenExpire = null;
        $this->payInstrTokenUsageLimit = null;
        $this->payInstrTokenAlg = null;
        $this->addInfo1 = null;
        $this->addInfo2 = null;
        $this->addInfo3 = null;
        $this->addInfo4 = null;
        $this->addInfo5 = null;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAdditionalRequestSignatureFields(): array
    {
        return [
            $this->shopUserRef, // SHOPUSERREF
            $this->pan, // PAN
            $this->expireMonth, // EXPIREMONTH
            $this->expireYear, // EXPIREYEAR
            $this->payInstrToken, // PAYINSTRTOKEN
            $this->addInfo1, // UDF1
            $this->addInfo2, // UDF2
            $this->addInfo3, // UDF3
            $this->addInfo4, // UDF4
            $this->addInfo5,
        ];
    }

    /**
     * @throws IgfsMissingParException
     */
    protected function checkFields()
    {
        parent::checkFields();
        if (empty($this->pan)) {
            throw new IgfsMissingParException('Missing pan');
        }
        if (empty($this->expireMonth)) {
            throw new IgfsMissingParException('Missing expireMonth');
        }
        if (empty($this->expireYear)) {
            throw new IgfsMissingParException('Missing expireYear');
        }
        if (empty($this->payInstrToken)) {
            throw new IgfsMissingParException('Missing payInstrToken');
        }
    }

    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $this->replaceRequestParameter($request, 'shopUserRef', $this->shopUserRef);
        $this->replaceRequestParameter($request, 'pan', $this->pan);
        $this->replaceRequestParameter($request, 'expireMonth', $this->expireMonth);
        $this->replaceRequestParameter($request, 'expireYear', $this->expireYear);
        $this->replaceRequestParameter($request, 'accountName', $this->accountName);
        $this->replaceRequestParameter($request, 'payInstrToken', $this->payInstrToken);
        $this->replaceRequestParameter($request, 'billingID', $this->billingID);
        $this->replaceRequestParameter($request, 'regenPayInstrToken', $this->regenPayInstrToken);
        $this->replaceRequestParameter($request, 'keepOnRegenPayInstrToken', $this->keepOnRegenPayInstrToken);
        $this->replaceRequestParameter($request, 'payInstrTokenExpire', $this->payInstrTokenExpire);
        $this->replaceRequestParameter($request, 'payInstrTokenUsageLimit', $this->payInstrTokenUsageLimit);
        $this->replaceRequestParameter($request, 'payInstrTokenAlg', $this->payInstrTokenAlg);
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
        // Opzionale
        $this->payInstrToken = IgfsUtils::getValue($response, 'payInstrToken');
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
            IgfsUtils::getValue($response, 'payInstrToken'), ]; // PAYINSTRTOKEN
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC
        return $this->getSignature($fields);
    }
}
