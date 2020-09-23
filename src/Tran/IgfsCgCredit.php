<?php

namespace PagOnline\Tran;

use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\IgfsUtils;

/**
 * Class IgfsCgCredit.
 */
class IgfsCgCredit extends BaseIgfsCgTran
{
    public $shopUserRef;
    public $amount;
    public $currencyCode;
    public $refTranID;
    public $pan;
    public $payInstrToken;
    public $billingID;
    public $expireMonth;
    public $expireYear;
    public $description;

    public $pendingAmount;
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgCreditRequest::class;

    public function resetFields(): void
    {
        parent::resetFields();
        $this->shopUserRef = null;
        $this->amount = null;
        $this->currencyCode = null;
        $this->refTranID = null;
        $this->pan = null;
        $this->payInstrToken = null;
        $this->billingID = null;
        $this->expireMonth = null;
        $this->expireYear = null;
        $this->description = null;

        $this->pendingAmount = null;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAdditionalRequestSignatureFields(): array
    {
        return [
            $this->shopUserRef, // SHOPUSERREF
            $this->amount, // AMOUNT
            $this->currencyCode, // CURRENCYCODE
            $this->refTranID, // REFORDERID
            $this->pan, // PAN
            $this->payInstrToken, // PAYINSTRTOKEN
            $this->expireMonth, // EXPIREMONTH
            $this->expireYear, // EXPIREYEAR
            $this->addInfo1, // UDF1
            $this->addInfo2, // UDF2
            $this->addInfo3, // UDF3
            $this->addInfo4, // UDF4
            $this->addInfo5, // UDF5
        ];
    }

    protected function checkFields(): void
    {
        parent::checkFields();
        if ($this->amount == null) {
            throw new IgfsMissingParException('Missing amount');
        }
        if ($this->refTranID === null && $this->pan === null && $this->payInstrToken === null) {
            throw new IgfsMissingParException('Missing refTranID');
        }

        if ($this->pan !== null && $this->pan === '') {
            // Se è stato impostato il pan verifico...
            throw new IgfsMissingParException('Missing pan');
        }

        if ($this->payInstrToken !== null && $this->payInstrToken === '') {
            throw new IgfsMissingParException('Missing payInstrToken');
        }

        if (($this->pan !== null || $this->payInstrToken !== null) && $this->currencyCode === null) {
            throw new IgfsMissingParException('Missing currencyCode');
        }
    }

    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $this->replaceRequestParameter($request, 'shopUserRef', $this->shopUserRef);
        $this->replaceRequestParameter($request, 'amount', $this->amount);
        $this->replaceRequestParameter($request, 'currencyCode', $this->currencyCode);
        $this->replaceRequestParameter($request, 'refTranID', $this->refTranID);
        $this->replaceRequestParameter($request, 'pan', $this->pan);
        $this->replaceRequestParameter($request, 'payInstrToken', $this->payInstrToken);
        $this->replaceRequestParameter($request, 'billingID', $this->billingID);
        $this->replaceRequestParameter($request, 'expireMonth', $this->expireMonth);
        $this->replaceRequestParameter($request, 'expireYear', $this->expireYear);
        $this->replaceRequestParameter($request, 'description', $this->description);

        return $request;
    }

    protected function parseResponseMap($response): void
    {
        parent::parseResponseMap($response);
        // Opzionale
        $this->pendingAmount = IgfsUtils::getValue($response, 'pendingAmount');
    }

    protected function getResponseSignature($response): string
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
            IgfsUtils::getValue($response, 'addInfo5'), // UDF5
        ];
        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC|ORDERID|DATE|UDF1|UDF2|UDF3|UDF4|UDF5
        return $this->getSignature($fields);
    }
}
