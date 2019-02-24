<?php

namespace PagOnline\Init;

use PagOnline\IgfsUtils;
use PagOnline\XmlEntities\Entry;
use PagOnline\XmlEntities\Level3Info;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class IgfsCgVerify.
 */
class IgfsCgVerify extends BaseIgfsCgInit
{
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgVerifyRequest::class;

    public $paymentID;
    public $refTranID;

    public $tranID;
    public $authCode;
    public $enrStatus;
    public $authStatus;
    public $brand;
    public $acquirerID;
    public $maskedPan;
    public $addInfo1;
    public $addInfo2;
    public $addInfo3;
    public $addInfo4;
    public $addInfo5;
    public $payInstrToken;
    public $expireMonth;
    public $expireYear;
    public $level3Info;
    public $additionalFee;
    public $status;
    public $accountName;
    public $nssResult;
    public $topUpID;
    public $receiptPdf;
    public $payAddData;
    public $payUserRef;
    public $shopUserMobilePhone;

    /**
     * {@inheritdoc}
     */
    protected function getAdditionalRequestSignatureFields(): array
    {
        return [
            $this->paymentID,
            $this->refTranID,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function resetFields()
    {
        parent::resetFields();
        $this->paymentID = null;
        $this->refTranID = null;

        $this->tranID = null;
        $this->authCode = null;
        $this->enrStatus = null;
        $this->authStatus = null;
        $this->brand = null;
        $this->acquirerID = null;
        $this->maskedPan = null;
        $this->addInfo1 = null;
        $this->addInfo2 = null;
        $this->addInfo3 = null;
        $this->addInfo4 = null;
        $this->addInfo5 = null;
        $this->payInstrToken = null;
        $this->expireMonth = null;
        $this->expireYear = null;
        $this->level3Info = null;
        $this->additionalFee = null;
        $this->status = null;
        $this->accountName = null;
        $this->nssResult = null;
        $this->topUpID = null;
        $this->receiptPdf = null;
        $this->payAddData = null;
        $this->payUserRef = null;
        $this->shopUserMobilePhone = null;
    }

    protected function checkFields()
    {
        parent::checkFields();
        if (empty($this->paymentID)) {
            throw new IgfsMissingParException('Missing paymentID');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $this->replaceRequestParameter($request, 'paymentID', $this->paymentID);
        $this->replaceRequestParameter($request, 'refTranID', $this->refTranID);

        return $request;
    }

    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        // Opzionale
        $this->tranID = IgfsUtils::getValue($response, 'tranID');
        // Opzionale
        $this->authCode = IgfsUtils::getValue($response, 'authCode');
        // Opzionale
        $this->enrStatus = IgfsUtils::getValue($response, 'enrStatus');
        // Opzionale
        $this->authStatus = IgfsUtils::getValue($response, 'authStatus');
        // Opzionale
        $this->brand = IgfsUtils::getValue($response, 'brand');
        // Opzionale
        $this->acquirerID = IgfsUtils::getValue($response, 'acquirerID');
        // Opzionale
        $this->maskedPan = IgfsUtils::getValue($response, 'maskedPan');
        // Opzionale
        $this->addInfo1 = IgfsUtils::getValue($response, 'addInfo1');
        // Opzionale
        $this->addInfo2 = IgfsUtils::getValue($response, 'addInfo2');
        // Opzionale
        $this->addInfo3 = IgfsUtils::getValue($response, 'addInfo3');
        // Opzionale
        $this->addInfo4 = IgfsUtils::getValue($response, 'addInfo4');
        // Opzionale
        $this->addInfo5 = IgfsUtils::getValue($response, 'addInfo5');
        // Opzionale
        $this->payInstrToken = IgfsUtils::getValue($response, 'payInstrToken');
        // Opzionale
        $this->expireMonth = IgfsUtils::getValue($response, 'expireMonth');
        // Opzionale
        $this->expireYear = IgfsUtils::getValue($response, 'expireYear');
        // Opzionale
        $this->level3Info = Level3Info::fromXml(IgfsUtils::getValue($response, 'level3Info'));
        // Opzionale
        $this->additionalFee = IgfsUtils::getValue($response, 'additionalFee');
        // Opzionale
        $this->status = IgfsUtils::getValue($response, 'status');
        // Opzionale
        $this->accountName = IgfsUtils::getValue($response, 'accountName');
        // Opzionale
        $this->nssResult = IgfsUtils::getValue($response, 'nssResult');
        // Opzionale
        $this->topUpID = IgfsUtils::getValue($response, 'topUpID');
        // Opzionale
        $this->payUserRef = IgfsUtils::getValue($response, 'payUserRef');
        // Opzionale
        $this->shopUserMobilePhone = IgfsUtils::getValue($response, 'shopUserMobilePhone');
        // Opzionale
        $this->receiptPdf = \base64_decode(IgfsUtils::getValue($response, 'receiptPdf'), true);
        if (\is_bool($this->receiptPdf)) {
            $this->receiptPdf = null;
        }

        $responseNode = $this->responseXmlToObject($response[static::$soapResponseTag]);
        if (null === $responseNode) {
            return;
        }

        $xml_response = IgfsUtils::parseResponseFields($responseNode);
        if (isset($xml_response['payAddData'])) {
            $this->payAddData = [];
            foreach ($responseNode->xpath('//payAddData') as $item) {
                \array_push($this->payAddData, Entry::fromXml($item->asXML()));
            }
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
            IgfsUtils::getValue($response, 'paymentID'), // PAYMENTID
            IgfsUtils::getValue($response, 'tranID'), // ORDERID
            IgfsUtils::getValue($response, 'authCode'), // AUTHCODE
            IgfsUtils::getValue($response, 'enrStatus'), // ENRSTATUS
            IgfsUtils::getValue($response, 'authStatus'), // AUTHSTATUS
        ];
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC|PAYMENTID|REDIRECTURL
        return $this->getSignature($fields);
    }
}
