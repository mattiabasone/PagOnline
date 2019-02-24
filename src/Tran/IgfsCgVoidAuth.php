<?php

namespace PagOnline\Tran;

use PagOnline\IgfsUtils;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class IgfsCgVoidAuth.
 */
class IgfsCgVoidAuth extends BaseIgfsCgTran
{
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgVoidAuthRequest::class;

    public $amount;
    public $refTranID;

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
        ];
    }

    public function resetFields()
    {
        parent::resetFields();
        $this->amount = null;
        $this->refTranID = null;
    }

    protected function checkFields()
    {
        parent::checkFields();
        if (null == $this->amount) {
            throw new IgfsMissingParException('Missing amount');
        }
        if (null == $this->refTranID) {
            throw new IgfsMissingParException('Missing refTranID');
        }
    }

    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $this->replaceRequestParameter($request, 'amount', $this->amount);
        $this->replaceRequestParameter($request, 'refTranID', $this->refTranID);

        return $request;
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
            IgfsUtils::getValue($response, 'addInfo5'), ]; // UDF5
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC|ORDERID|DATE|UDF1|UDF2|UDF3|UDF4|UDF5
        return $this->getSignature($fields);
    }
}
