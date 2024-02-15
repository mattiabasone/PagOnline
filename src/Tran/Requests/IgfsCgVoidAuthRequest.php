<?php

namespace PagOnline\Tran\Requests;

use PagOnline\BaseIgfsCgRequest;

final class IgfsCgVoidAuthRequest extends BaseIgfsCgRequest
{
    public const CONTENT = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.api.web.cg.igfs.apps.netsw.it/">
<soapenv:Body>
<ser:VoidAuth>
<request>
{apiVersion}
{tid}
{merID}
{payInstr}
{signature}
{shopID}
{amount}
{refTranID}
{addInfo1}
{addInfo2}
{addInfo3}
{addInfo4}
{addInfo5}
</request>
</ser:VoidAuth>
</soapenv:Body>
</soapenv:Envelope>
XML;
}
