<?php

namespace PagOnline\Mpi\Requests;

use PagOnline\BaseIgfsCgRequest;

/**
 * Class IgfsCgVerifyRequest
 * @package PagOnline\Init\Requests
 */
final class IgfsCgMpiAuthRequest extends BaseIgfsCgRequest
{
    const CONTENT = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.api.web.cg.igfs.apps.netsw.it/">
<soapenv:Body>
<ser:Auth>
<request>
<apiVersion><![CDATA[{apiVersion}]]></apiVersion>
{tid}
{merID}
{payInstr}
<signature><![CDATA[{signature}]]></signature>
<shopID><![CDATA[{shopID}]]></shopID>
<paRes><![CDATA[{paRes}]]></paRes>
<md><![CDATA[{md}]]></md>
</request>
</ser:Auth>
</soapenv:Body>
</soapenv:Envelope>
XML;
}
