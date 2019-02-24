<?php

namespace PagOnline\Tran;

use SimpleXMLElement;
use PagOnline\IgfsUtils;
use PagOnline\BaseIgfsCg;
use PagOnline\XmlEntities\Entry;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class IgfsCgAuth.
 */
class IgfsCgAuth extends BaseIgfsCgTran
{
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgAuthRequest::class;

    public $shopUserRef;
    public $shopUserName;
    public $shopUserAccount;
    public $shopUserMobilePhone;
    public $shopUserIMEI;
    public $shopUserIP;
    public $trType = 'AUTH';
    public $amount;
    public $currencyCode;
    public $langID;
    public $callbackURL;
    public $pan;
    public $payInstrToken;
    public $billingID;
    public $payload;
    public $regenPayInstrToken;
    public $keepOnRegenPayInstrToken;
    public $payInstrTokenExpire;
    public $payInstrTokenUsageLimit;
    public $payInstrTokenAlg;
    public $cvv2;
    public $expireMonth;
    public $expireYear;
    public $accountName;
    public $enrStatus;
    public $authStatus;
    public $cavv;
    public $xid;
    public $level3Info;
    public $description;
    public $paymentReason;
    public $topUpID;
    public $firstTopUp;
    public $payInstrTokenAsTopUpID;
    public $promoCode;
    public $payPassData;
    public $userAgent;
    public $fingerPrint;
    public $validityExpire;

    public $paymentID;
    public $authCode;
    public $brand;
    public $acquirerID;
    public $maskedPan;
    public $additionalFee;
    public $status;
    public $nssResult;
    public $receiptPdf;
    public $payAddData;
    public $payUserRef;

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
            $this->shopUserIP, // SHOPUSERIP
            $this->trType, // TRTYPE
            $this->amount, // AMOUNT
            $this->currencyCode, // CURRENCYCODE
            $this->callbackURL, // CALLBACKURL
            $this->pan, // PAN
            $this->payInstrToken, // PAYINSTRTOKEN
            $this->payload, // PAYLOAD
            $this->cvv2, // CVV2
            $this->expireMonth, // EXPIREMONTH
            $this->expireYear, // EXPIREYEAR
            $this->addInfo1, // UDF1
            $this->addInfo2, // UDF2
            $this->addInfo3, // UDF3
            $this->addInfo4, // UDF4
            $this->addInfo5, // UDF5
            $this->topUpID,
        ];
    }

    public function resetFields()
    {
        parent::resetFields();
        $this->shopUserRef = null;
        $this->shopUserName = null;
        $this->shopUserAccount = null;
        $this->shopUserMobilePhone = null;
        $this->shopUserIMEI = null;
        $this->shopUserIP = null;
        $this->trType = 'AUTH';
        $this->amount = null;
        $this->currencyCode = null;
        $this->langID = null;
        $this->callbackURL = null;
        $this->pan = null;
        $this->payInstrToken = null;
        $this->billingID = null;
        $this->payload = null;
        $this->regenPayInstrToken = null;
        $this->keepOnRegenPayInstrToken = null;
        $this->payInstrTokenExpire = null;
        $this->payInstrTokenUsageLimit = null;
        $this->payInstrTokenAlg = null;
        $this->cvv2 = null;
        $this->expireMonth = null;
        $this->expireYear = null;
        $this->accountName = null;
        $this->enrStatus = null;
        $this->authStatus = null;
        $this->cavv = null;
        $this->xid = null;
        $this->level3Info = null;
        $this->description = null;
        $this->paymentReason = null;
        $this->topUpID = null;
        $this->firstTopUp = null;
        $this->payInstrTokenAsTopUpID = null;
        $this->promoCode = null;
        $this->payPassData = null;
        $this->userAgent = null;
        $this->fingerPrint = null;
        $this->validityExpire = null;

        $this->paymentID = null;
        $this->authCode = null;
        $this->brand = null;
        $this->acquirerID = null;
        $this->maskedPan = null;
        $this->additionalFee = null;
        $this->status = null;
        $this->nssResult = null;
        $this->receiptPdf = null;
        $this->payAddData = null;
        $this->payUserRef = null;
    }

    protected function checkFields()
    {
        parent::checkFields();
        if (null === $this->trType) {
            throw new IgfsMissingParException('Missing trType');
        }

        if ('VERIFY' != $this->trType) {
            if (null == $this->amount) {
                throw new IgfsMissingParException('Missing amount');
            }
            if (null == $this->currencyCode) {
                throw new IgfsMissingParException('Missing currencyCode');
            }
        }
        // Disabilitato per pagopoi
        // if ($this->pan == NULL) {
        //	if ($this->payInstrToken == NULL)
        //		throw new IgfsMissingParException("Missing pan");
        // }
        if (null != $this->pan) {
            // Se è stato impostato il pan verifico...
            if ('' == $this->pan) {
                throw new IgfsMissingParException('Missing pan');
            }
        }
        if (null != $this->payInstrToken) {
            // Se è stato impostato il payInstrToken verifico...
            if ('' == $this->payInstrToken) {
                throw new IgfsMissingParException('Missing payInstrToken');
            }
        }
        if (null != $this->level3Info) {
            $i = 0;
            if (null != $this->level3Info->product) {
                foreach ($this->level3Info->product as $product) {
                    if (null == $product->productCode) {
                        throw new IgfsMissingParException('Missing productCode['.$i.']');
                    }
                    if (null == $product->productDescription) {
                        throw new IgfsMissingParException('Missing productDescription['.$i.']');
                    }
                    ++$i;
                }
            }
        }
    }

    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $this->replaceRequestParameter($request, 'shopUserRef', $this->shopUserRef);
        $this->replaceRequestParameter($request, 'shopUserName', $this->shopUserName);
        $this->replaceRequestParameter($request, 'shopUserAccount', $this->shopUserAccount);
        $this->replaceRequestParameter($request, 'shopUserMobilePhone', $this->shopUserMobilePhone);
        $this->replaceRequestParameter($request, 'shopUserIMEI', $this->shopUserIMEI);
        $this->replaceRequestParameter($request, 'shopUserIP', $this->shopUserIP);
        $this->replaceRequestParameter($request, 'trType', $this->trType);
        $this->replaceRequestParameter($request, 'amount', $this->amount);
        $this->replaceRequestParameter($request, 'currencyCode', $this->currencyCode);
        $this->replaceRequestParameter($request, 'langID', $this->langID);
        $this->replaceRequestParameter($request, 'callbackURL', $this->callbackURL);
        $this->replaceRequestParameter($request, 'pan', $this->pan);
        $this->replaceRequestParameter($request, 'payInstrToken', $this->payInstrToken);
        $this->replaceRequestParameter($request, 'billingID', $this->billingID);
        $this->replaceRequestParameter($request, 'payload', $this->payload);
        $this->replaceRequestParameter($request, 'regenPayInstrToken', $this->regenPayInstrToken);
        $this->replaceRequestParameter($request, 'keepOnRegenPayInstrToken', $this->keepOnRegenPayInstrToken);
        $this->replaceRequestParameter($request, 'payInstrTokenExpire', IgfsUtils::formatXMLGregorianCalendar($this->payInstrTokenExpire));
        $this->replaceRequestParameter($request, 'payInstrTokenUsageLimit', $this->payInstrTokenUsageLimit);
        $this->replaceRequestParameter($request, 'payInstrTokenAlg', $this->payInstrTokenAlg);
        $this->replaceRequestParameter($request, 'cvv2', $this->cvv2);
        $this->replaceRequestParameter($request, 'expireMonth', $this->expireMonth);
        $this->replaceRequestParameter($request, 'expireYear', $this->expireYear);
        $this->replaceRequestParameter($request, 'accountName', $this->accountName);
        $this->replaceRequestParameter($request, 'enrStatus', $this->enrStatus);
        $this->replaceRequestParameter($request, 'authStatus', $this->authStatus);
        $this->replaceRequestParameter($request, 'cavv', $this->cavv);
        $this->replaceRequestParameter($request, 'xid', $this->xid);
        $this->replaceRequestParameter($request, 'description', $this->description);
        $this->replaceRequestParameter($request, 'paymentReason', $this->paymentReason);
        $this->replaceRequestParameter($request, 'topUpID', $this->topUpID);
        $this->replaceRequestParameter($request, 'firstTopUp', $this->firstTopUp);
        $this->replaceRequestParameter($request, 'payInstrTokenAsTopUpID', $this->payInstrTokenAsTopUpID);
        $this->replaceRequestParameter($request, 'promoCode', $this->promoCode);
        $this->replaceRequestParameter($request, 'payPassData', $this->payPassData);
        $this->replaceRequestParameter($request, 'userAgent', $this->userAgent);
        $this->replaceRequestParameter($request, 'fingerPrint', $this->fingerPrint);
        $this->replaceRequestParameter($request, 'validityExpire', IgfsUtils::formatXMLGregorianCalendar($this->validityExpire));

        if (null != $this->level3Info) {
            $this->replaceRequestParameter($request, 'level3Info', $this->level3Info->toXml('level3Info'), false);
        } else {
            $this->replaceRequestParameter($request, 'level3Info', '');
        }

        return $request;
    }

    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        // Opzionale
        $this->paymentID = IgfsUtils::getValue($response, 'paymentID');
        // Opzionale
        $this->authCode = IgfsUtils::getValue($response, 'authCode');
        // Opzionale
        $this->brand = IgfsUtils::getValue($response, 'brand');
        // Opzionale
        $this->acquirerID = IgfsUtils::getValue($response, 'acquirerID');
        // Opzionale
        $this->maskedPan = IgfsUtils::getValue($response, 'maskedPan');
        // Opzionale
        $this->payInstrToken = IgfsUtils::getValue($response, 'payInstrToken');
        // Opzionale
        $this->additionalFee = IgfsUtils::getValue($response, 'additionalFee');
        // Opzionale
        $this->status = IgfsUtils::getValue($response, 'status');
        // Opzionale
        $this->nssResult = IgfsUtils::getValue($response, 'nssResult');
        // Opzionale
        $this->topUpID = IgfsUtils::getValue($response, 'topUpID');
        // Opzionale
        $this->payUserRef = IgfsUtils::getValue($response, 'payUserRef');
        // Opzionale
        $this->shopUserMobilePhone = IgfsUtils::getValue($response, 'shopUserMobilePhone');
        // Opzionale
        try {
            $this->receiptPdf = \base64_decode(IgfsUtils::getValue($response, 'receiptPdf'), true);
        } catch (\Exception $e) {
            $this->receiptPdf = null;
        }
        try {
            $xml = $response[BaseIgfsCg::$soapResponseTag];

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
            if (isset($xml_response['payAddData'])) {
                $payAddData = [];
                foreach ($dom->response->children() as $item) {
                    if ('payAddData' == $item->getName()) {
                        $payAddData[] = Entry::fromXml($item->asXML());
                    }
                }
                $this->payAddData = $payAddData;
            }
        } catch (\Exception $e) {
            $this->payAddData = null;
        }
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
            IgfsUtils::getValue($response, 'tranID'), // ORDERID
            IgfsUtils::getValue($response, 'date'), // TRANDATE
            IgfsUtils::getValue($response, 'paymentID'), // PAYMENTID
            IgfsUtils::getValue($response, 'authCode'), // AUTHCODE
            ];
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORCODE|ORDERID|PAYMENTID|AUTHCODE
        return $this->getSignature($fields);
    }
}
