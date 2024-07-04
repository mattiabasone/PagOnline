<?php

namespace PagOnline\PayByMail;

use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\IgfsUtils;

class IgfsCgPayByMailVerify extends BaseIgfsCgPayByMail
{
    public $mailID;

    public $tranID;
    public $status;
    public $addInfo1;
    public $addInfo2;
    public $addInfo3;
    public $addInfo4;
    public $addInfo5;
    /**
     * @var string
     */
    protected $requestNamespace = Requests\IgfsCgPayByMailVerifyRequest::class;

    /**
     * {@inheritdoc}
     */
    public function resetFields(): void
    {
        parent::resetFields();
        $this->mailID = null;
        $this->tranID = null;
        $this->status = null;
        $this->addInfo1 = null;
        $this->addInfo2 = null;
        $this->addInfo3 = null;
        $this->addInfo4 = null;
        $this->addInfo5 = null;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAdditionalRequestSignatureFields(): array
    {
        return [
            $this->mailID,
        ];
    }

    protected function checkFields()
    {
        parent::checkFields();
        if (empty($this->mailID)) {
            throw new IgfsMissingParException('Missing mailID');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function buildRequest()
    {
        $request = parent::buildRequest();
        $this->replaceRequestParameter($request, 'mailID', $this->mailID);

        return $request;
    }

    protected function parseResponseMap($response)
    {
        parent::parseResponseMap($response);
        // Opzionale
        $this->tranID = IgfsUtils::getValue($response, 'tranID');
        // Opzionale
        $this->status = IgfsUtils::getValue($response, 'status');
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
    }

    /**
     * @param array $response
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
            IgfsUtils::getValue($response, 'mailID'), // MAILID
            IgfsUtils::getValue($response, 'tranID'), // ORDERID
            IgfsUtils::getValue($response, 'status'), // STATUS
        ];

        // signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC|MAILID|STATUS
        return $this->getSignature($fields);
    }
}
