<?php

namespace PagOnline\Tran\Requests;

use PagOnline\BaseIgfsCgRequest;

final class IgfsCgAuthRequest extends BaseIgfsCgRequest
{
    const CONTENT = <<<XML
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
{amount}
{currencyCode}
{langID}
{callbackURL}
{shopUserRef}
{shopUserName}
{shopUserAccount}
{shopUserMobilePhone}
{shopUserIMEI}
{shopUserIP}
{trType}
{pan}
{payInstrToken}
{billingID}
{payload}
{regenPayInstrToken}
{keepOnRegenPayInstrToken}
{payInstrTokenExpire}
{payInstrTokenUsageLimit}
{payInstrTokenAlg}
{cvv2}
{expireMonth}
{expireYear}
{accountName}
{addInfo1}
{addInfo2}
{addInfo3}
{addInfo4}
{addInfo5}
{enrStatus}
{authStatus}
{cavv}
{xid}
{level3Info}
{description}
{paymentReason}
{topUpID}
{firstTopUp}
{payInstrTokenAsTopUpID}
{promoCode}
{payPassData}
{userAgent}
{fingerPrint}
{validityExpire}
</request>
</ser:Auth>
</soapenv:Body>
</soapenv:Envelope>
XML;
}
