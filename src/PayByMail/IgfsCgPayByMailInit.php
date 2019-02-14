<?php

require_once 'IGFS_CG_API/paybymail/BaseIgfsCgPayByMail.php';
require_once 'IGFS_CG_API/Level3Info.php';

class IgfsCgPayByMailInit extends BaseIgfsCgPayByMail
{
    public $shopUserRef;
    public $shopUserName;
    public $shopUserAccount;
    public $shopUserMobilePhone;
    public $shopUserIMEI;
    public $trType = 'AUTH';
    public $linkType = 'MAIL';
    public $amount;
    public $currencyCode;
    public $langID = 'EN';
    public $callbackURL;
    public $addInfo1;
    public $addInfo2;
    public $addInfo3;
    public $addInfo4;
    public $addInfo5;
    public $accountName;
    public $level3Info;
    public $description;
    public $paymentReason;

    public $mailID;
    public $linkURL;

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
        $this->trType = 'AUTH';
        $this->linkType = 'MAIL';
        $this->amount = null;
        $this->currencyCode = null;
        $this->langID = 'EN';
        $this->callbackURL = null;
        $this->addInfo1 = null;
        $this->addInfo2 = null;
        $this->addInfo3 = null;
        $this->addInfo4 = null;
        $this->addInfo5 = null;
        $this->accountName = null;
        $this->level3Info = null;
        $this->description = null;
        $this->paymentReason = null;

        $this->mailID = null;
        $this->linkURL = null;
    }

    protected function checkFields()
    {
        parent::checkFields();
        if (null == $this->trType) {
            throw new IgfsMissingParException('Missing trType');
        }
        if (null == $this->langID) {
            throw new IgfsMissingParException('Missing langID');
        }
        if (null == $this->shopUserRef) {
            throw new IgfsMissingParException('Missing shopUserRef');
        }
        if (null != $this->level3Info) {
            $i = 0;
            if (null != $this->level3Info->product) {
                foreach ($this->level3Info->product as $product) {
                    if (null == $product->productCode) {
                        throw new IgfsMissingParException('Missing productCode['.i.']');
                    }
                    if (null == $product->productDescription) {
                        throw new IgfsMissingParException('Missing productDescription['.i.']');
                    }
                }
                ++$i;
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

        $request = $this->replaceRequest($request, '{trType}', $this->trType);

        if (null != $this->linkType) {
            $request = $this->replaceRequest($request, '{linkType}', '<linkType><![CDATA['.$this->linkType.']]></linkType>');
        } else {
            $request = $this->replaceRequest($request, '{linkType}', '');
        }

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

        return $request;
    }

    protected function setRequestSignature($request)
    {
        // signature dove il buffer e' cosi composto APIVERSION|TID|SHOPID|SHOPUSERREF|SHOPUSERNAME|SHOPUSERACCOUNT|SHOPUSERMOBILEPHONE|SHOPUSERIMEI|TRTYPE|AMOUNT|CURRENCYCODE|LANGID|NOTIFYURL|ERRORURL|CALLBACKURL
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
                $this->trType, // TRTYPE
                $this->amount, // AMOUNT
                $this->currencyCode, // CURRENCYCODE
                $this->langID, // LANGID
                $this->callbackURL, // CALLBACKURL
                $this->addInfo1, // UDF1
                $this->addInfo2, // UDF2
                $this->addInfo3, // UDF3
                $this->addInfo4, // UDF4
                $this->addInfo5, ];
        $signature = $this->getSignature($this->kSig, // KSIGN
                $fields);
        $request = $this->replaceRequest($request, '{signature}', $signature);

        return $request;
    }

    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        // Opzionale
        $this->mailID = IgfsUtils::getValue($response, 'mailID');
        // Opzionale
        $this->linkURL = IgfsUtils::getValue($response, 'linkURL');
    }

    protected function getResponseSignature($response)
    {
        $fields = [
                IgfsUtils::getValue($response, 'tid'), // TID
                IgfsUtils::getValue($response, 'shopID'), // SHOPID
                IgfsUtils::getValue($response, 'rc'), // RC
                IgfsUtils::getValue($response, 'errorDesc'), // ERRORDESC
                IgfsUtils::getValue($response, 'mailID'), // MAILID
                IgfsUtils::getValue($response, 'linkURL'), ];
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC|MAILID
        return $this->getSignature($this->kSig, // KSIGN
                $fields);
    }

    protected function getFileName()
    {
        return 'IGFS_CG_API/paybymail/IgfsCgPayByMailInit.request';
    }
}
