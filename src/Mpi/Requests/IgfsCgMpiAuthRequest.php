<?php

namespace PagOnline\Mpi\Requests;

use PagOnline\BaseIgfsCgRequest;

/**
 * Class IgfsCgVerifyRequest.
 */
final class IgfsCgMpiAuthRequest extends BaseIgfsCgRequest
{
    public const CONTENT = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.api.web.cg.igfs.apps.netsw.it/">
<soapenv:Body>
<ser:Auth>
<request>
{apiVersion}
{tid}
{merID}
{payInstr}
{signature}
{shopID}
{paRes}
{md}
</request>
</ser:Auth>
</soapenv:Body>
</soapenv:Envelope>
XML;
}
