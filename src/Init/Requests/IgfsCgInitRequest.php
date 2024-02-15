<?php

namespace PagOnline\Init\Requests;

use PagOnline\BaseIgfsCgRequest;

final class IgfsCgInitRequest extends BaseIgfsCgRequest
{
    public const CONTENT = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.api.web.cg.igfs.apps.netsw.it/">
<soapenv:Body>
<ser:Init>
<request>
{apiVersion}
{tid}
{merID}
{payInstr}
{signature}
{shopID}
{shopUserRef}
{shopUserName}
{shopUserAccount}
{shopUserMobilePhone}
{shopUserIMEI}
{trType}
{amount}
{currencyCode}
{langID}
{notifyURL}
{errorURL}
{callbackURL}
{addInfo1}
{addInfo2}
{addInfo3}
{addInfo4}
{addInfo5}
{payInstrToken}
{billingID}
{regenPayInstrToken}
{keepOnRegenPayInstrToken}
{payInstrTokenExpire}
{payInstrTokenUsageLimit}
{payInstrTokenAlg}
{accountName}
{level3Info}
{mandateInfo}
{description}
{paymentReason}
{topUpID}
{firstTopUp}
{payInstrTokenAsTopUpID}
{validityExpire}
{minExpireMonth}
{minExpireYear}
{termInfo}
</request>
</ser:Init>
</soapenv:Body>
</soapenv:Envelope>
XML;
}
