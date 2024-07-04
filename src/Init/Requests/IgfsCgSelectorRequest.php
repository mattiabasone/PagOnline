<?php

namespace PagOnline\Init\Requests;

use PagOnline\BaseIgfsCgRequest;

/**
 * Class IgfsCgVerifyRequest.
 */
final class IgfsCgSelectorRequest extends BaseIgfsCgRequest
{
    public const CONTENT = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.api.web.cg.igfs.apps.netsw.it/">
<soapenv:Body>
<ser:Selector>
<request>
{apiVersion}
{tid}
{merID}
{payInstr}
{signature}
{shopID}
{shopUserRef}
{trType}
{amount}
{currencyCode}
{langID}
{addInfo1}
{addInfo2}
{addInfo3}
{addInfo4}
{addInfo5}
{payInstrToken}
{billingID}
</request>
</ser:Selector>
</soapenv:Body>
</soapenv:Envelope>
XML;
}
