<?php

namespace PagOnline\Tokenizer;

use PagOnline\IgfsUtils;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class IgfsCgTokenizerEnroll.
 */
class IgfsCgTokenizerEnroll extends BaseIgfsCgTokenizer
{
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgTokenizerEnrollRequest::class;

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
     * @throws IgfsMissingParException
     */
    protected function checkFields()
    {
        parent::checkFields();
        if (null == $this->pan || '' == $this->pan) {
            throw new IgfsMissingParException('Missing pan');
        }
        if (null == $this->expireMonth) {
            throw new IgfsMissingParException('Missing expireMonth');
        }
        if (null == $this->expireYear) {
            throw new IgfsMissingParException('Missing expireYear');
        }
        if (empty($this->payInstrToken)) {
            throw new IgfsMissingParException('Missing payInstrToken');
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

        $request = $this->replaceRequest($request, '{pan}', $this->pan);
        $request = $this->replaceRequest($request, '{expireMonth}', $this->expireMonth);
        $request = $this->replaceRequest($request, '{expireYear}', $this->expireYear);

        if (null != $this->accountName) {
            $request = $this->replaceRequest($request, '{accountName}', '<accountName><![CDATA['.$this->accountName.']]></accountName>');
        } else {
            $request = $this->replaceRequest($request, '{accountName}', '');
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

        if (null != $this->regenPayInstrToken) {
            $request = $this->replaceRequest($request, '{regenPayInstrToken}', '<regenPayInstrToken><![CDATA['.$this->regenPayInstrToken.']]></regenPayInstrToken>');
        } else {
            $request = $this->replaceRequest($request, '{regenPayInstrToken}', '');
        }
        if (null != $this->keepOnRegenPayInstrToken) {
            $request = $this->replaceRequest($request, '{keepOnRegenPayInstrToken}', '<keepOnRegenPayInstrToken><![CDATA['.$this->keepOnRegenPayInstrToken.']]></keepOnRegenPayInstrToken>');
        } else {
            $request = $this->replaceRequest($request, '{keepOnRegenPayInstrToken}', '');
        }
        if (null != $this->payInstrTokenExpire) {
            $request = $this->replaceRequest($request, '{payInstrTokenExpire}', '<payInstrTokenExpire><![CDATA['.IgfsUtils::formatXMLGregorianCalendar($this->payInstrTokenExpire).']]></payInstrTokenExpire>');
        } else {
            $request = $this->replaceRequest($request, '{payInstrTokenExpire}', '');
        }
        if (null != $this->payInstrTokenUsageLimit) {
            $request = $this->replaceRequest($request, '{payInstrTokenUsageLimit}', '<payInstrTokenUsageLimit><![CDATA['.$this->payInstrTokenUsageLimit.']]></payInstrTokenUsageLimit>');
        } else {
            $request = $this->replaceRequest($request, '{payInstrTokenUsageLimit}', '');
        }
        if (null != $this->payInstrTokenAlg) {
            $request = $this->replaceRequest($request, '{payInstrTokenAlg}', '<payInstrTokenAlg><![CDATA['.$this->payInstrTokenAlg.']]></payInstrTokenAlg>');
        } else {
            $request = $this->replaceRequest($request, '{payInstrTokenAlg}', '');
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

    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        // Opzionale
        $this->payInstrToken = IgfsUtils::getValue($response, 'payInstrToken');
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
            IgfsUtils::getValue($response, 'payInstrToken'), ]; // PAYINSTRTOKEN
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC
        return $this->getSignature($fields);
    }
}
