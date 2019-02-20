<?php

namespace PagOnline\Init;

use PagOnline\IgfsUtils;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class IgfsCgInit.
 */
class IgfsCgInit extends BaseIgfsCgInit
{
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgInitRequest::class;

    public $shopUserRef;
    public $shopUserName;
    public $shopUserAccount;
    public $shopUserMobilePhone;
    public $shopUserIMEI;
    public $trType = 'AUTH';
    public $amount;
    public $currencyCode;
    public $langID = 'EN';
    public $notifyURL;
    public $errorURL;
    public $callbackURL;
    public $addInfo1;
    public $addInfo2;
    public $addInfo3;
    public $addInfo4;
    public $addInfo5;
    public $payInstrToken;
    public $billingID;
    public $regenPayInstrToken;
    public $keepOnRegenPayInstrToken;
    public $payInstrTokenExpire;
    public $payInstrTokenUsageLimit;
    public $payInstrTokenAlg;
    public $accountName;
    public $level3Info;
    public $mandateInfo;
    public $description;
    public $paymentReason;
    public $topUpID;
    public $firstTopUp;
    public $payInstrTokenAsTopUpID;
    public $validityExpire;
    public $minExpireMonth;
    public $minExpireYear;
    public $termInfo;

    public $paymentID;
    public $redirectURL;

    protected function resetFields()
    {
        parent::resetFields();
        $this->shopUserRef = null;
        $this->shopUserName = null;
        $this->shopUserAccount = null;
        $this->shopUserMobilePhone = null;
        $this->shopUserIMEI = null;
        $this->trType = 'AUTH';
        $this->amount = null;
        $this->currencyCode = null;
        $this->langID = 'EN';
        $this->notifyURL = null;
        $this->errorURL = null;
        $this->callbackURL = null;
        $this->addInfo1 = null;
        $this->addInfo2 = null;
        $this->addInfo3 = null;
        $this->addInfo4 = null;
        $this->addInfo5 = null;
        $this->payInstrToken = null;
        $this->billingID = null;
        $this->regenPayInstrToken = null;
        $this->keepOnRegenPayInstrToken = null;
        $this->payInstrTokenExpire = null;
        $this->payInstrTokenUsageLimit = null;
        $this->payInstrTokenAlg = null;
        $this->accountName = null;
        $this->level3Info = null;
        $this->mandateInfo = null;
        $this->description = null;
        $this->paymentReason = null;
        $this->topUpID = null;
        $this->firstTopUp = null;
        $this->payInstrTokenAsTopUpID = null;
        $this->validityExpire = null;
        $this->minExpireMonth = null;
        $this->minExpireYear = null;
        $this->termInfo = null;

        $this->paymentID = null;
        $this->redirectURL = null;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAdditionalRequestSignatureFields(): array
    {
        return [
            $this->shopUserRef, // SHOPUSERREF
            $this->shopUserName, // SHOPUSERNAME
            $this->shopUserAccount, // SHOPUSERACCOUNT
            $this->shopUserMobilePhone, //SHOPUSERMOBILEPHONE
            $this->shopUserIMEI, //SHOPUSERIMEI
            $this->trType, // TRTYPE
            $this->amount, // AMOUNT
            $this->currencyCode, // CURRENCYCODE
            $this->langID, // LANGID
            $this->notifyURL, // NOTIFYURL
            $this->errorURL, // ERRORURL
            $this->callbackURL, // CALLBACKURL
            $this->addInfo1, // UDF1
            $this->addInfo2, // UDF2
            $this->addInfo3, // UDF3
            $this->addInfo4, // UDF4
            $this->addInfo5, // UDF5
            $this->payInstrToken, // PAYINSTRTOKEN
            $this->topUpID,
        ];
    }

    /**
     * @throws IgfsMissingParException
     */
    protected function checkFields()
    {
        parent::checkFields();

        if (null === $this->trType) {
            throw new IgfsMissingParException('Missing trType');
        }

        if (null === $this->langID) {
            throw new IgfsMissingParException('Missing langID');
        }

        if (null === $this->notifyURL) {
            throw new IgfsMissingParException('Missing notifyURL');
        }

        if (null === $this->errorURL) {
            throw new IgfsMissingParException('Missing errorURL');
        }

        if (null !== $this->payInstrToken && '' == $this->payInstrToken) {
            // Se è stato impostato il payInstrToken verifico...
            throw new IgfsMissingParException('Missing payInstrToken');
        }

        if (null !== $this->level3Info) {
            $i = 0;
            if (null !== $this->level3Info->product && \is_array($this->level3Info->product)) {
                foreach ($this->level3Info->product as $product) {
                    if (null === $product->productCode) {
                        throw new IgfsMissingParException("Missing productCode[{$i}]");
                    }
                    if (null === $product->productDescription) {
                        throw new IgfsMissingParException("Missing productDescription[{$i}]");
                    }
                    ++$i;
                }
            }
        }

        if (null !== $this->mandateInfo && null === $this->mandateInfo->mandateID) {
            throw new IgfsMissingParException('Missing mandateID');
        }
    }

    /**
     * @return false|mixed|string
     */
    protected function buildRequest()
    {
        $request = parent::buildRequest();
        if (null != $this->shopUserRef) {
            $request = $this->replaceRequest($request, '{shopUserRef}', '<shopUserRef><![CDATA['.$this->shopUserRef.']]></shopUserRef>');
        } else {
            $request = $this->replaceRequest($request, '{shopUserRef}', '');
        }
        if (null != $this->shopUserName) {
            $request = $this->replaceRequest($request, '{shopUserName}', '<shopUserName><![CDATA['.$this->shopUserName.']]></shopUserName>');
        } else {
            $request = $this->replaceRequest($request, '{shopUserName}', '');
        }
        if (null != $this->shopUserAccount) {
            $request = $this->replaceRequest($request, '{shopUserAccount}', '<shopUserAccount><![CDATA['.$this->shopUserAccount.']]></shopUserAccount>');
        } else {
            $request = $this->replaceRequest($request, '{shopUserAccount}', '');
        }
        if (null != $this->shopUserMobilePhone) {
            $request = $this->replaceRequest($request, '{shopUserMobilePhone}', '<shopUserMobilePhone><![CDATA['.$this->shopUserMobilePhone.']]></shopUserMobilePhone>');
        } else {
            $request = $this->replaceRequest($request, '{shopUserMobilePhone}', '');
        }
        if (null != $this->shopUserIMEI) {
            $request = $this->replaceRequest($request, '{shopUserIMEI}', '<shopUserIMEI><![CDATA['.$this->shopUserIMEI.']]></shopUserIMEI>');
        } else {
            $request = $this->replaceRequest($request, '{shopUserIMEI}', '');
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
        $request = $this->replaceRequest($request, '{notifyURL}', $this->notifyURL);
        $request = $this->replaceRequest($request, '{errorURL}', $this->errorURL);
        if (null != $this->callbackURL) {
            $request = $this->replaceRequest($request, '{callbackURL}', '<callbackURL><![CDATA['.$this->callbackURL.']]></callbackURL>');
        } else {
            $request = $this->replaceRequest($request, '{callbackURL}', '');
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

        if (null != $this->accountName) {
            $request = $this->replaceRequest($request, '{accountName}', '<accountName><![CDATA['.$this->accountName.']]></accountName>');
        } else {
            $request = $this->replaceRequest($request, '{accountName}', '');
        }

        if (null != $this->level3Info) {
            $request = $this->replaceRequest($request, '{level3Info}', $this->level3Info->toXml('level3Info'));
        } else {
            $request = $this->replaceRequest($request, '{level3Info}', '');
        }
        if (null != $this->mandateInfo) {
            $request = $this->replaceRequest($request, '{mandateInfo}', $this->mandateInfo->toXml('mandateInfo'));
        } else {
            $request = $this->replaceRequest($request, '{mandateInfo}', '');
        }
        if (null != $this->description) {
            $request = $this->replaceRequest($request, '{description}', '<description><![CDATA['.$this->description.']]></description>');
        } else {
            $request = $this->replaceRequest($request, '{description}', '');
        }
        if (null != $this->paymentReason) {
            $request = $this->replaceRequest($request, '{paymentReason}', '<paymentReason><![CDATA['.$this->paymentReason.']]></paymentReason>');
        } else {
            $request = $this->replaceRequest($request, '{paymentReason}', '');
        }

        if (null != $this->topUpID) {
            $request = $this->replaceRequest($request, '{topUpID}', '<topUpID><![CDATA['.$this->topUpID.']]></topUpID>');
        } else {
            $request = $this->replaceRequest($request, '{topUpID}', '');
        }
        if (null != $this->firstTopUp) {
            $request = $this->replaceRequest($request, '{firstTopUp}', '<firstTopUp><![CDATA['.$this->firstTopUp.']]></firstTopUp>');
        } else {
            $request = $this->replaceRequest($request, '{firstTopUp}', '');
        }
        if (null != $this->payInstrTokenAsTopUpID) {
            $request = $this->replaceRequest($request, '{payInstrTokenAsTopUpID}', '<payInstrTokenAsTopUpID><![CDATA['.$this->payInstrTokenAsTopUpID.']]></payInstrTokenAsTopUpID>');
        } else {
            $request = $this->replaceRequest($request, '{payInstrTokenAsTopUpID}', '');
        }

        if (null != $this->validityExpire) {
            $request = $this->replaceRequest($request, '{validityExpire}', '<validityExpire><![CDATA['.IgfsUtils::formatXMLGregorianCalendar($this->validityExpire).']]></validityExpire>');
        } else {
            $request = $this->replaceRequest($request, '{validityExpire}', '');
        }

        if (null != $this->minExpireMonth) {
            $request = $this->replaceRequest($request, '{minExpireMonth}', '<minExpireMonth><![CDATA['.$this->minExpireMonth.']]></minExpireMonth>');
        } else {
            $request = $this->replaceRequest($request, '{minExpireMonth}', '');
        }
        if (null != $this->minExpireYear) {
            $request = $this->replaceRequest($request, '{minExpireYear}', '<minExpireYear><![CDATA['.$this->minExpireYear.']]></minExpireYear>');
        } else {
            $request = $this->replaceRequest($request, '{minExpireYear}', '');
        }

        if (null != $this->termInfo) {
            $sb = '';
            foreach ($this->termInfo as $item) {
                $sb .= $item->toXml('termInfo');
            }
            $request = $this->replaceRequest($request, '{termInfo}', $sb);
        } else {
            $request = $this->replaceRequest($request, '{termInfo}', '');
        }

        return $request;
    }

    /**
     * @param $response
     */
    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        // Opzionale
        $this->paymentID = IgfsUtils::getValue($response, 'paymentID');
        // Opzionale
        $this->redirectURL = IgfsUtils::getValue($response, 'redirectURL');
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
            IgfsUtils::getValue($response, 'paymentID'), // PAYMENTID
            IgfsUtils::getValue($response, 'redirectURL'),  // REDIRECTURL
        ];
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC|PAYMENTID|REDIRECTURL
        return $this->getSignature($fields);
    }
}
