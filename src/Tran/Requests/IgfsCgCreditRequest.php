<?php

namespace PagOnline\Tran\Requests;

use PagOnline\BaseIgfsCgRequest;

final class IgfsCgCreditRequest extends BaseIgfsCgRequest
{
    const CONTENT = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.api.web.cg.igfs.apps.netsw.it/">
<soapenv:Body>
<ser:Credit>
<request>
{apiVersion}
{tid}
{merID}
{payInstr}
{signature}
{shopID}
{shopUserRef}
{amount}
{currencyCode}
{refTranID}
{pan}
{payInstrToken}
{billingID}
{expireMonth}
{expireYear}
{addInfo1}
{addInfo2}
{addInfo3}
{addInfo4}
{addInfo5}
{description}
</request>
</ser:Credit>
</soapenv:Body>
</soapenv:Envelope>
XML;
}
