<?php

namespace PagOnline\PayByMail\Requests;

use PagOnline\BaseIgfsCgRequest;

/**
 * Class IgfsCgVerifyRequest.
 */
final class IgfsCgPayByMailInitRequest extends BaseIgfsCgRequest
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
{linkType}
{amount}
{currencyCode}
{langID}
{callbackURL}
{addInfo1}
{addInfo2}
{addInfo3}
{addInfo4}
{addInfo5}
{accountName}
{level3Info}
{description}
{paymentReason}
</request>
</ser:Init>
</soapenv:Body>
</soapenv:Envelope>
XML;
}
