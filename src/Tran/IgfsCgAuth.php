<?php

namespace PagOnline\Tran;

use SimpleXMLElement;
use PagOnline\IgfsUtils;
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

    public function __construct()
    {
        parent::__construct();
    }

    protected function resetFields()
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
        if (null != $this->shopUserIP) {
            $request = $this->replaceRequest($request, '{shopUserIP}', '<shopUserIP><![CDATA['.$this->shopUserIP.']]></shopUserIP>');
        } else {
            $request = $this->replaceRequest($request, '{shopUserIP}', '');
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
        if (null != $this->langID) {
            $request = $this->replaceRequest($request, '{langID}', '<langID><![CDATA['.$this->langID.']]></langID>');
        } else {
            $request = $this->replaceRequest($request, '{langID}', '');
        }

        if (null != $this->callbackURL) {
            $request = $this->replaceRequest($request, '{callbackURL}', '<callbackURL><![CDATA['.$this->callbackURL.']]></callbackURL>');
        } else {
            $request = $this->replaceRequest($request, '{callbackURL}', '');
        }

        if (null != $this->pan) {
            $request = $this->replaceRequest($request, '{pan}', '<pan><![CDATA['.$this->pan.']]></pan>');
        } else {
            $request = $this->replaceRequest($request, '{pan}', '');
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

        if (null != $this->payload) {
            $request = $this->replaceRequest($request, '{payload}', '<payload><![CDATA['.$this->payload.']]></payload>');
        } else {
            $request = $this->replaceRequest($request, '{payload}', '');
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

        if (null != $this->cvv2) {
            $request = $this->replaceRequest($request, '{cvv2}', '<cvv2><![CDATA['.$this->cvv2.']]></cvv2>');
        } else {
            $request = $this->replaceRequest($request, '{cvv2}', '');
        }

        if (null != $this->expireMonth) {
            $request = $this->replaceRequest($request, '{expireMonth}', '<expireMonth><![CDATA['.$this->expireMonth.']]></expireMonth>');
        } else {
            $request = $this->replaceRequest($request, '{expireMonth}', '');
        }
        if (null != $this->expireYear) {
            $request = $this->replaceRequest($request, '{expireYear}', '<expireYear><![CDATA['.$this->expireYear.']]></expireYear>');
        } else {
            $request = $this->replaceRequest($request, '{expireYear}', '');
        }

        if (null != $this->accountName) {
            $request = $this->replaceRequest($request, '{accountName}', '<accountName><![CDATA['.$this->accountName.']]></accountName>');
        } else {
            $request = $this->replaceRequest($request, '{accountName}', '');
        }

        if (null != $this->enrStatus) {
            $request = $this->replaceRequest($request, '{enrStatus}', '<enrStatus><![CDATA['.$this->enrStatus.']]></enrStatus>');
        } else {
            $request = $this->replaceRequest($request, '{enrStatus}', '');
        }
        if (null != $this->authStatus) {
            $request = $this->replaceRequest($request, '{authStatus}', '<authStatus><![CDATA['.$this->authStatus.']]></authStatus>');
        } else {
            $request = $this->replaceRequest($request, '{authStatus}', '');
        }
        if (null != $this->cavv) {
            $request = $this->replaceRequest($request, '{cavv}', '<cavv><![CDATA['.$this->cavv.']]></cavv>');
        } else {
            $request = $this->replaceRequest($request, '{cavv}', '');
        }
        if (null != $this->xid) {
            $request = $this->replaceRequest($request, '{xid}', '<xid><![CDATA['.$this->xid.']]></xid>');
        } else {
            $request = $this->replaceRequest($request, '{xid}', '');
        }

        if (null != $this->level3Info) {
            $request = $this->replaceRequest($request, '{level3Info}', $this->level3Info->toXml('level3Info'));
        } else {
            $request = $this->replaceRequest($request, '{level3Info}', '');
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

        if (null != $this->promoCode) {
            $request = $this->replaceRequest($request, '{promoCode}', '<promoCode><![CDATA['.$this->promoCode.']]></promoCode>');
        } else {
            $request = $this->replaceRequest($request, '{promoCode}', '');
        }

        if (null != $this->payPassData) {
            $request = $this->replaceRequest($request, '{payPassData}', '<payPassData><![CDATA['.$this->payPassData.']]></payPassData>');
        } else {
            $request = $this->replaceRequest($request, '{payPassData}', '');
        }

        if (null != $this->userAgent) {
            $request = $this->replaceRequest($request, '{userAgent}', '<userAgent><![CDATA['.$this->userAgent.']]></userAgent>');
        } else {
            $request = $this->replaceRequest($request, '{userAgent}', '');
        }

        if (null != $this->fingerPrint) {
            $request = $this->replaceRequest($request, '{fingerPrint}', '<fingerPrint><![CDATA['.$this->fingerPrint.']]></fingerPrint>');
        } else {
            $request = $this->replaceRequest($request, '{fingerPrint}', '');
        }

        if (null != $this->validityExpire) {
            $request = $this->replaceRequest($request, '{validityExpire}', '<validityExpire><![CDATA['.IgfsUtils::formatXMLGregorianCalendar($this->validityExpire).']]></validityExpire>');
        } else {
            $request = $this->replaceRequest($request, '{validityExpire}', '');
        }

        return $request;
    }

    protected function setRequestSignature($request)
    {
        // signature dove il buffer e' cosi composto APIVERSION|TID|SHOPID|SHOPUSERREF|SHOPUSERNAME|SHOPUSERACCOUNT|SHOPUSERMOBILEPHONE|SHOPUSERIMEI|SHOPUSERIP|TRTYPE|AMOUNT|CURRENCYCODE|CALLBACKURL|PAN|PAYINSTRTOKEN|PAYLOAD|CVV2|EXPIREMONTH|EXPIREYEAR|UDF1|UDF2|UDF3|UDF4|UDF5
        $fields = [
            $this->getVersion(), // APIVERSION
            $this->tid, // TID
            $this->merID, // MERID
            $this->payInstr, // PAYINSTR
            $this->shopID, // SHOPID
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
        $signature = $this->getSignature($this->kSig, // KSIGN
            $fields);

        return $this->replaceRequest($request, '{signature}', $signature);
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
            $xml = $response[BaseIgfsCg::$RESPONSE];

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
                        $payAddData[] = Entry::fromXml($item->asXML(), 'payAddData');
                    }
                }
                $this->payAddData = $payAddData;
            }
        } catch (\Exception $e) {
            $this->payAddData = null;
        }
    }

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
            IgfsUtils::getValue($response, 'authCode'), ]; // AUTHCODE
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORCODE|ORDERID|PAYMENTID|AUTHCODE
        return $this->getSignature($this->kSig, // KSIGN
            $fields);
    }
}
