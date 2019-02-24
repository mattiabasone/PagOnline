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

    public function resetFields()
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

        if (null !== $this->payInstrToken && '' === $this->payInstrToken) {
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

        if (!empty($this->mandateInfo) && empty($this->mandateInfo->mandateID)) {
            throw new IgfsMissingParException('Missing mandateID');
        }
    }

    /**
     * @return false|mixed|string
     */
    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $this->replaceRequestParameter($request, 'notifyURL', $this->notifyURL);
        $this->replaceRequestParameter($request, 'errorURL', $this->errorURL);
        $this->replaceRequestParameter($request, 'shopUserRef', $this->shopUserRef);
        $this->replaceRequestParameter($request, 'shopUserName', $this->shopUserName);
        $this->replaceRequestParameter($request, 'shopUserAccount', $this->shopUserAccount);
        $this->replaceRequestParameter($request, 'shopUserMobilePhone', $this->shopUserMobilePhone);
        $this->replaceRequestParameter($request, 'shopUserIMEI', $this->shopUserIMEI);
        $this->replaceRequestParameter($request, 'trType', $this->trType);
        $this->replaceRequestParameter($request, 'amount', $this->amount);
        $this->replaceRequestParameter($request, 'currencyCode', $this->currencyCode);
        $this->replaceRequestParameter($request, 'langID', $this->langID);
        $this->replaceRequestParameter($request, 'callbackURL', $this->callbackURL);
        $this->replaceRequestParameter($request, 'addInfo1', $this->addInfo1);
        $this->replaceRequestParameter($request, 'addInfo2', $this->addInfo2);
        $this->replaceRequestParameter($request, 'addInfo3', $this->addInfo3);
        $this->replaceRequestParameter($request, 'addInfo4', $this->addInfo4);
        $this->replaceRequestParameter($request, 'addInfo5', $this->addInfo5);
        $this->replaceRequestParameter($request, 'payInstrToken', $this->payInstrToken);
        $this->replaceRequestParameter($request, 'billingID', $this->billingID);
        $this->replaceRequestParameter($request, 'regenPayInstrToken', $this->regenPayInstrToken);
        $this->replaceRequestParameter($request, 'keepOnRegenPayInstrToken', $this->keepOnRegenPayInstrToken);
        $this->replaceRequestParameter($request, 'payInstrTokenExpire', IgfsUtils::formatXMLGregorianCalendar($this->payInstrTokenExpire));
        $this->replaceRequestParameter($request, 'payInstrTokenUsageLimit', $this->payInstrTokenUsageLimit);
        $this->replaceRequestParameter($request, 'payInstrTokenAlg', $this->payInstrTokenAlg);
        $this->replaceRequestParameter($request, 'accountName', $this->accountName);
        $this->replaceRequestParameter($request, 'description', $this->description);
        $this->replaceRequestParameter($request, 'paymentReason', $this->paymentReason);
        $this->replaceRequestParameter($request, 'topUpID', $this->topUpID);
        $this->replaceRequestParameter($request, 'firstTopUp', $this->firstTopUp);
        $this->replaceRequestParameter($request, 'payInstrTokenAsTopUpID', $this->payInstrTokenAsTopUpID);
        $this->replaceRequestParameter($request, 'validityExpire', IgfsUtils::formatXMLGregorianCalendar($this->validityExpire));
        $this->replaceRequestParameter($request, 'minExpireMonth', $this->minExpireMonth);
        $this->replaceRequestParameter($request, 'minExpireYear', $this->minExpireYear);

        if (null != $this->level3Info) {
            $this->replaceRequestParameter($request, 'level3Info', $this->level3Info->toXml('level3Info'), false);
        } else {
            $this->replaceRequestParameter($request, 'level3Info', '');
        }

        if (null != $this->mandateInfo) {
            $this->replaceRequestParameter($request, 'mandateInfo', $this->level3Info->toXml('mandateInfo'), false);
        } else {
            $this->replaceRequestParameter($request, 'mandateInfo', '');
        }

        if (null != $this->termInfo) {
            $sb = '';
            foreach ($this->termInfo as $item) {
                $sb .= $item->toXml('termInfo');
            }
            $this->replaceRequestParameter($request, 'termInfo', $sb, false);
        } else {
            $this->replaceRequestParameter($request, 'termInfo', '');
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
