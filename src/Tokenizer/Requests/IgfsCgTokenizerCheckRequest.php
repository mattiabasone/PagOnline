<?php

namespace PagOnline\Tokenizer\Requests;

use PagOnline\BaseIgfsCgRequest;

/**
 * Class IgfsCgTokenizerCheckRequest.
 */
final class IgfsCgTokenizerCheckRequest extends BaseIgfsCgRequest
{
    const CONTENT = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.api.web.cg.igfs.apps.netsw.it/">
<soapenv:Body>
<ser:Check>
<request>
<apiVersion><![CDATA[{apiVersion}]]></apiVersion>
{tid}
{merID}
{payInstr}
<signature><![CDATA[{signature}]]></signature>
<shopID><![CDATA[{shopID}]]></shopID>
<payInstrToken><![CDATA[{payInstrToken}]]></payInstrToken>
{billingID}
</request>
</ser:Check>
</soapenv:Body>
</soapenv:Envelope>
XML;
}
