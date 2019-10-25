<?php

namespace PagOnline\Init;

use PagOnline\IgfsUtils;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\XmlEntities\Init\SelectorTerminalInfo;

/**
 * Class IgfsCgSelector.
 */
class IgfsCgSelector extends BaseIgfsCgInit
{
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgSelectorRequest::class;

    public $shopUserRef;
    public $trType = 'AUTH';
    public $amount;
    public $currencyCode;
    public $langID = 'EN';
    public $addInfo1;
    public $addInfo2;
    public $addInfo3;
    public $addInfo4;
    public $addInfo5;
    public $payInstrToken;
    public $billingID;

    public $termInfo;

    /**
     * {@inheritdoc}
     */
    public function resetFields()
    {
        parent::resetFields();
        $this->shopUserRef = null;
        $this->trType = 'AUTH';
        $this->amount = null;
        $this->currencyCode = null;
        $this->langID = 'EN';
        $this->addInfo1 = null;
        $this->addInfo2 = null;
        $this->addInfo3 = null;
        $this->addInfo4 = null;
        $this->addInfo5 = null;
        $this->payInstrToken = null;
        $this->billingID = null;

        $this->termInfo = null;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAdditionalRequestSignatureFields(): array
    {
        return [
            $this->shopUserRef, // SHOPUSERREF
            $this->trType, // TRTYPE
            $this->amount, // AMOUNT
            $this->currencyCode, // CURRENCYCODE
            $this->langID, // LANGID
            $this->addInfo1, // UDF1
            $this->addInfo2, // UDF2
            $this->addInfo3, // UDF3
            $this->addInfo4, // UDF4
            $this->addInfo5, // UDF5
            $this->payInstrToken,
        ];
    }

    /**
     * @throws IgfsMissingParException
     */
    protected function checkFields()
    {
        parent::checkFields();
        if ($this->trType == null) {
            throw new IgfsMissingParException('Missing trType');
        }
        if ($this->trType != 'TOKENIZE') {
            if ($this->amount == null) {
                throw new IgfsMissingParException('Missing amount');
            }
            if ($this->currencyCode == null) {
                throw new IgfsMissingParException('Missing currencyCode');
            }
        }
        if ($this->langID == null) {
            throw new IgfsMissingParException('Missing langID');
        }

        if (empty($this->payInstrToken)) {
            throw new IgfsMissingParException('Missing payInstrToken');
        }
    }

    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $this->replaceRequestParameter($request, 'shopUserRef', $this->shopUserRef);
        $this->replaceRequestParameter($request, 'trType', $this->trType);
        $this->replaceRequestParameter($request, 'amount', $this->amount);
        $this->replaceRequestParameter($request, 'currencyCode', $this->currencyCode);
        $this->replaceRequestParameter($request, 'langID', $this->langID);
        $this->replaceRequestParameter($request, 'addInfo1', $this->addInfo1);
        $this->replaceRequestParameter($request, 'addInfo2', $this->addInfo2);
        $this->replaceRequestParameter($request, 'addInfo3', $this->addInfo3);
        $this->replaceRequestParameter($request, 'addInfo4', $this->addInfo4);
        $this->replaceRequestParameter($request, 'addInfo5', $this->addInfo5);
        $this->replaceRequestParameter($request, 'payInstrToken', $this->payInstrToken);
        $this->replaceRequestParameter($request, 'billingID', $this->billingID);

        return $request;
    }

    /**
     * @param $response
     */
    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        try {
            $responseNode = $this->responseXmlToObject($response[static::$soapResponseTag]);
            if ($responseNode === null || $responseNode->children()->count() === 0) {
                return;
            }
            $termInfos = $responseNode->xpath('//termInfo');
            if (\count($termInfos) > 0) {
                $this->termInfo = [];
                foreach ($termInfos as $item) {
                    \array_push($this->termInfo, SelectorTerminalInfo::fromXml($item->asXML()));
                }
            }
        } catch (\Exception $e) {
            $this->termInfo = null;
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
        ];
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC|PAYMENTID|REDIRECTURL
        return $this->getSignature($fields);
    }
}
