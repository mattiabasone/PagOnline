<?php

namespace PagOnline\Init\Requests;

use PagOnline\BaseIgfsCgRequest;

/**
 * Class IgfsCgVerifyRequest
 * @package PagOnline\Init\Requests
 */
final class IgfsCgSelectorRequest extends BaseIgfsCgRequest
{
    const CONTENT = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.api.web.cg.igfs.apps.netsw.it/">
<soapenv:Body>
<ser:Selector>
<request>
<apiVersion><![CDATA[{apiVersion}]]></apiVersion>
{tid}
{merID}
{payInstr}
<signature><![CDATA[{signature}]]></signature>
<shopID><![CDATA[{shopID}]]></shopID>
{shopUserRef}
<trType><![CDATA[{trType}]]></trType>
{amount}
{currencyCode}
<langID><![CDATA[{langID}]]></langID>
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
