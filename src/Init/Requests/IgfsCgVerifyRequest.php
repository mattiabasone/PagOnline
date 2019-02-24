<?php

namespace PagOnline\Init\Requests;

use PagOnline\BaseIgfsCgRequest;

/**
 * Class IgfsCgVerifyRequest.
 */
final class IgfsCgVerifyRequest extends BaseIgfsCgRequest
{
    const CONTENT = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.api.web.cg.igfs.apps.netsw.it/">
<soapenv:Body>
<ser:Verify>
<request>
{apiVersion}
{tid}
{merID}
{payInstr}
{signature}
{shopID}
{paymentID}
{refTranID}
</request>
</ser:Verify>
</soapenv:Body>
</soapenv:Envelope>
XML;
}
