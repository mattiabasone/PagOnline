<?php

namespace PagOnline\PayByMail;

use PagOnline\Exceptions\IgfsException;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\IgfsUtils;

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
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgPayByMailInitRequest::class;

    public function resetFields()
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

    /**
     * {@inheritdoc}
     */
    protected function getAdditionalRequestSignatureFields(): array
    {
        return [
            $this->shopUserRef, // SHOPUSERREF
            $this->shopUserName, // SHOPUSERNAME
            $this->shopUserAccount, // SHOPUSERACCOUNT
            $this->shopUserMobilePhone, // SHOPUSERMOBILEPHONE
            $this->shopUserIMEI, // SHOPUSERIMEI
            $this->trType, // TRTYPE
            $this->amount, // AMOUNT
            $this->currencyCode, // CURRENCYCODE
            $this->langID, // LANGID
            $this->callbackURL, // CALLBACKURL
            $this->addInfo1, // UDF1
            $this->addInfo2, // UDF2
            $this->addInfo3, // UDF3
            $this->addInfo4, // UDF4
            $this->addInfo5,
        ];
    }

    protected function checkFields()
    {
        parent::checkFields();
        if ($this->trType == null) {
            throw new IgfsMissingParException('Missing trType');
        }
        if ($this->langID == null) {
            throw new IgfsMissingParException('Missing langID');
        }
        if ($this->shopUserRef == null) {
            throw new IgfsMissingParException('Missing shopUserRef');
        }
        if ($this->level3Info != null) {
            $i = 0;
            if ($this->level3Info->product != null) {
                foreach ($this->level3Info->product as $product) {
                    if ($product->productCode == null) {
                        throw new IgfsMissingParException("Missing productCode[{$i}]");
                    }
                    if ($product->productDescription == null) {
                        throw new IgfsMissingParException("Missing productDescription[{$i}]");
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
        $this->replaceRequestParameter($request, 'trType', $this->trType);
        $this->replaceRequestParameter($request, 'linkType', $this->linkType);
        $this->replaceRequestParameter($request, 'amount', $this->amount);
        $this->replaceRequestParameter($request, 'currencyCode', $this->currencyCode);
        $this->replaceRequestParameter($request, 'langID', $this->langID);
        $this->replaceRequestParameter($request, 'callbackURL', $this->callbackURL);
        $this->replaceRequestParameter($request, 'addInfo1', $this->addInfo1);
        $this->replaceRequestParameter($request, 'addInfo2', $this->addInfo2);
        $this->replaceRequestParameter($request, 'addInfo3', $this->addInfo3);
        $this->replaceRequestParameter($request, 'addInfo4', $this->addInfo4);
        $this->replaceRequestParameter($request, 'addInfo5', $this->addInfo5);
        $this->replaceRequestParameter($request, 'accountName', $this->accountName);
        $this->replaceRequestParameter($request, 'description', $this->description);
        $this->replaceRequestParameter($request, 'paymentReason', $this->paymentReason);

        if ($this->level3Info != null) {
            $this->replaceRequestParameter($request, 'level3Info', $this->level3Info->toXml('level3Info'), false);
        } else {
            $this->replaceRequestParameter($request, 'level3Info', '');
        }

        return $request;
    }

    /**
     * @param array $response
     */
    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        // Opzionale
        $this->mailID = IgfsUtils::getValue($response, 'mailID');
        // Opzionale
        $this->linkURL = IgfsUtils::getValue($response, 'linkURL');
    }

    /**
     * @param array $response
     *
     * @throws IgfsException
     *
     * @return string
     */
    protected function getResponseSignature($response): string
    {
        $fields = [
            IgfsUtils::getValue($response, 'tid'), // TID
            IgfsUtils::getValue($response, 'shopID'), // SHOPID
            IgfsUtils::getValue($response, 'rc'), // RC
            IgfsUtils::getValue($response, 'errorDesc'), // ERRORDESC
            IgfsUtils::getValue($response, 'mailID'), // MAILID
            IgfsUtils::getValue($response, 'linkURL'),
        ];

        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC|MAILID
        return $this->getSignature($fields);
    }
}
