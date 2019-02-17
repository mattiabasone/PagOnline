<?php

namespace PagOnline\Mpi\Requests;

final class IgfsCgMpiEnrollRequest extends BaseIgfsCgRequest
{
    const CONTENT = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.api.web.cg.igfs.apps.netsw.it/">
<soapenv:Body>
<ser:Enroll>
<request>
<apiVersion><![CDATA[{apiVersion}]]></apiVersion>
{tid}
{merID}
{payInstr}
<signature><![CDATA[{signature}]]></signature>
<shopID><![CDATA[{shopID}]]></shopID>
<amount><![CDATA[{amount}]]></amount>
<currencyCode><![CDATA[{currencyCode}]]></currencyCode>
{shopUserRef}
{pan}
{payInstrToken}
{billingID}
{expireMonth}
{expireYear}
<termURL><![CDATA[{termURL}]]></termURL>
{description}
{addInfo1}
{addInfo2}
{addInfo3}
{addInfo4}
{addInfo5}
</request>
</ser:Enroll>
</soapenv:Body>
</soapenv:Envelope>
XML;
}
