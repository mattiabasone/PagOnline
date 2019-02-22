<?php

namespace PagOnline\Tran;

use PagOnline\IgfsUtils;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class IgfsCgConfirm.
 */
class IgfsCgConfirm extends BaseIgfsCgTran
{
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgConfirmRequest::class;

    public $amount;
    public $refTranID;
    public $paymentReason;
    public $topUpID;

    public $pendingAmount;

    /**
     * {@inheritdoc}
     */
    protected function getAdditionalRequestSignatureFields(): array
    {
        return [
            $this->amount, // AMOUNT
            $this->refTranID, // REFORDERID
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
        $this->amount = null;
        $this->refTranID = null;
        $this->paymentReason = null;
        $this->topUpID = null;

        $this->pendingAmount = null;
    }

    protected function checkFields()
    {
        parent::checkFields();
        if (null == $this->amount) {
            throw new IgfsMissingParException('Missing amount');
        }
        if (null == $this->refTranID && null == $this->topUpID) {
            throw new IgfsMissingParException('Missing refTranID');
        }
    }

    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $request = $this->replaceRequest($request, '{amount}', $this->amount);

        if (null != $this->refTranID) {
            $request = $this->replaceRequest($request, '{refTranID}', '<refTranID><![CDATA['.$this->refTranID.']]></refTranID>');
        } else {
            $request = $this->replaceRequest($request, '{refTranID}', '');
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

        return $request;
    }

    /**
     * @param $response
     */
    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        // Opzionale
        $this->pendingAmount = IgfsUtils::getValue($response, 'pendingAmount');
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
            IgfsUtils::getValue($response, 'addInfo1'), // UDF1
            IgfsUtils::getValue($response, 'addInfo2'), // UDF2
            IgfsUtils::getValue($response, 'addInfo3'), // UDF3
            IgfsUtils::getValue($response, 'addInfo4'), // UDF4
            IgfsUtils::getValue($response, 'addInfo5'),  // UDF5
        ];
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC|ORDERID|DATE|UDF1|UDF2|UDF3|UDF4|UDF5
        return $this->getSignature($fields);
    }
}
