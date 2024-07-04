<?php

namespace PagOnline\Tokenizer\Requests;

use PagOnline\BaseIgfsCgRequest;

/**
 * Class IgfsCgTokenizerEnrollRequest.
 */
final class IgfsCgTokenizerEnrollRequest extends BaseIgfsCgRequest
{
    public const CONTENT = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.api.web.cg.igfs.apps.netsw.it/">
<soapenv:Body>
<ser:Enroll>
<request>
{apiVersion}
{tid}
{merID}
{payInstr}
{signature}
{shopID}
{shopUserRef}
{pan}
{expireMonth}
{expireYear}
{accountName}
{payInstrToken}
{billingID}
{regenPayInstrToken}
{keepOnRegenPayInstrToken}
{payInstrTokenExpire}
{payInstrTokenUsageLimit}
{payInstrTokenAlg}
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
