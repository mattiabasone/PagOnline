<?php

namespace PagOnline;

use PagOnline\Exceptions\ConnectionException;
use PagOnline\Exceptions\IgfsException;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\Exceptions\ReadWriteException;

/**
 * Class BaseIgfsCg
 * @package PagOnline
 */
abstract class BaseIgfsCg
{
    const VERSION = '2.4.1.5';

    /**
     * Signature Key
     *
     * @var string
     */
    public $kSig;

    /**
     * Payment Gateway server url
     *
     * @var string|null
     */
    public $serverURL = null;
    public $serverURLs = null;
    public $cTimeout = 5000;
    public $timeout = 30000;

    public $proxy = null;

    public $httpAuthUser = null;
    public $httpAuthPass = null;

    public $tid = null;
    public $merID = null;
    public $payInstr = null;

    public $rc = null;
    public $error = null;
    public $errorDesc = null;

    protected $fields2Reset = false;
    protected $checkCert = true;

    public $installPath = null;

    /**
     * BaseIgfsCg constructor.
     */
    public function __construct()
    {
        $this->resetFields();
    }

    /**
     * Reset fields
     */
    protected function resetFields()
    {
        $this->tid = null;
        $this->merID = null;
        $this->payInstr = null;
        $this->rc = null;
        $this->error = false;
        $this->errorDesc = null;
        $this->fields2Reset = false;
    }

    abstract protected function getServicePort();

    /**
     * It returns the package version
     *
     * @return string
     */
    public static function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Check required fields, if any of the required parameter is missing it'll throw an IgfsMissingParException
     *
     * @throws IgfsMissingParException
     */
    protected function checkFields()
    {
        if (null == $this->serverURL || '' == $this->serverURL) {
            if (null == $this->serverURLs || 0 == \count($this->serverURLs)) {
                throw new IgfsMissingParException('Missing serverURL');
            }
        }
        if (null == $this->kSig || '' == $this->kSig) {
            throw new IgfsMissingParException('Missing kSig');
        }
        if (null == $this->tid || '' == $this->tid) {
            if ((null == $this->merID || '' == $this->merID) && (null == $this->payInstr || '' == $this->payInstr)) {
                throw new IgfsMissingParException('Missing tid');
            }
        }
    }

    /**
     * Disable Certification Check on SSL HandShake.
     */
    public function disableCheckSSLCert()
    {
        $this->checkCert = false;
    }

    /**
     * Get configured server url
     *
     * @param $surl
     * @return string
     */
    protected function getServerUrl($surl)
    {
        if (!IgfsUtils::endsWith($surl, '/')) {
            $surl .= '/';
        }

        return $surl.$this->getServicePort();
    }

    protected function replaceRequest($request, $find, $value)
    {
        if (null == $value) {
            $value = '';
        }

        return \str_replace($find, $value, $request);
    }

    protected function buildRequest()
    {
        $request = $this->readFromJARFile($this->getFileName());
        $request = $this->replaceRequest($request, '{apiVersion}', $this->getVersion());
        if (null != $this->tid) {
            $request = $this->replaceRequest($request, '{tid}', '<tid><![CDATA['.$this->tid.']]></tid>');
        } else {
            $request = $this->replaceRequest($request, '{tid}', '');
        }
        if (null != $this->merID) {
            $request = $this->replaceRequest($request, '{merID}', '<merID><![CDATA['.$this->merID.']]></merID>');
        } else {
            $request = $this->replaceRequest($request, '{merID}', '');
        }
        if (null != $this->request) {
            $request = $this->replaceRequest($request, '{request}', '<request><![CDATA['.$this->request.']]></request>');
        } else {
            $request = $this->replaceRequest($request, '{request}', '');
        }

        return $request;
    }

    abstract protected function getFileName();

    /**
     * TODO: We really need this?
     *
     * @param $filename
     * @return false|string
     */
    protected function readFromJARFile($filename)
    {
        if (null != $this->installPath) {
            if ('/' == \mb_substr($this->installPath, -1)) {
                return \file_get_contents($this->installPath.$filename);
            } else {
                return \file_get_contents($this->installPath.'/'.$filename);
            }
        } else {
            return \file_get_contents($filename);
        }
    }

    abstract protected function setRequestSignature($request);

    abstract protected function getResponseSignature($response);

    protected static $SOAP_ENVELOPE = 'soap:Envelope';
    protected static $SOAP_BODY = 'soap:Body';
    protected static $RESPONSE = 'response';

    protected function parseResponse($response)
    {
        $response = \str_replace('<soap:', '<', $response);
        $response = \str_replace('</soap:', '</', $response);
        $dom = new SimpleXMLElement($response, LIBXML_NOERROR, false);
        if (0 == \count($dom)) {
            return;
        }

        $tmp = \str_replace('<Body>', '', $dom->Body->asXML());
        $tmp = \str_replace('</Body>', '', $tmp);
        $dom = new SimpleXMLElement($tmp, LIBXML_NOERROR, false);
        if (0 == \count($dom)) {
            return;
        }

        $root = self::$RESPONSE;
        if (0 == \count($dom->$root)) {
            return;
        }

        $fields = IgfsUtils::parseResponseFields($dom->$root);
        if (isset($fields)) {
            $fields[self::$RESPONSE] = $response;
        }

        return $fields;
    }

    protected function parseResponseMap($response)
    {
        $this->tid = IgfsUtils::getValue($response, 'tid');
        $this->rc = IgfsUtils::getValue($response, 'rc');
        if (null == IgfsUtils::getValue($response, 'error')) {
            $this->error = true;
        } else {
            $this->error = ('true' == IgfsUtils::getValue($response, 'error'));
        }
        $this->errorDesc = IgfsUtils::getValue($response, 'errorDesc');
    }

    protected function checkResponseSignature($response)
    {
        if (null == IgfsUtils::getValue($response, 'signature')) {
            return false;
        }
        $signature = IgfsUtils::getValue($response, 'signature');
        if ($signature != $this->getResponseSignature($response)) {
            return false;
        }

        return true;
    }

    protected function process($url)
    {
        // Creiamo la richiesta
        $request = $this->buildRequest();
        if (null == $request) {
            throw new IgfsException('IGFS Request is null');
        }
        // Impostiamo la signature
        $request = $this->setRequestSignature($request);
        // Inviamo la richiesta e leggiamo la risposta
        try {
            // System.out.println(request);
            $response = $this->post($url, $request);
            // System.out.println(response);
        } catch (IOException $e) {
            throw $e;
        }
        if (null == $response) {
            throw new IgfsException('IGFS Response is null');
        }
        // Parsifichiamo l'XML
        return $this->parseResponse($response);
    }

    /**
     * Execute a POST request
     * TODO: Guzzle
     *
     * @param $url
     * @param $request
     * @return bool|string
     */
    private function post($url, $request)
    {
        //open connection
        $ch = \curl_init();

        $httpHeader = ['Content-Type: text/xml; charset="utf-8"'];

        //set the url, number of POST vars, POST data
        \curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        \curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->cTimeout / 1000);
        \curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout / 1000);
        \curl_setopt($ch, CURLOPT_URL, $url);
        \curl_setopt($ch, CURLOPT_POST, 1);
        \curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (null != $this->proxy) {
            \curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
            \curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
        }
        if (!$this->checkCert) {
            \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        if (null != $this->httpAuthUser && null != $this->httpAuthPass) {
            \curl_setopt($ch, CURLOPT_USERPWD, $this->httpAuthUser.':'.$this->httpAuthPass);
        }

        // PHP <5.5.0
        \defined('CURLE_OPERATION_TIMEDOUT') || \define('CURLE_OPERATION_TIMEDOUT', CURLE_OPERATION_TIMEOUTED);

        //execute post
        $result = \curl_exec($ch);
        if (\curl_errno($ch)) {
            if (CURLE_OPERATION_TIMEDOUT == \curl_errno($ch)) {
                throw new ReadWriteException($url, \curl_error($ch));
            } else {
                throw new ConnectionException($url, \curl_error($ch));
            }
        } else {
            //close connection
            \curl_close($ch);
        }

        return $result;
    }

    /**
     * TODO: Refactor this
     *
     * @return bool
     * @throws ConnectionException
     * @throws IgfsException
     * @throws IgfsMissingParException
     */
    public function execute()
    {
        try {
            $this->checkFields();

            if (null != $this->serverURL) {
                $mapResponse = $this->executeHttp($this->serverURL);
            } else {
                $i = 0;
                $sURL = $this->serverURLs[$i];
                $finished = false;
                while (!$finished) {
                    try {
                        $mapResponse = $this->executeHttp($sURL);
                        $finished = true;
                    } catch (ConnectionException $e) {
                        ++$i;
                        if ($i < \count($this->serverURLs) && null != $this->serverURLs[$i]) {
                            $sURL = $this->serverURLs[$i];
                        } else {
                            throw $e;
                        }
                    }
                }
            }

            // Leggiamo i campi
            $this->parseResponseMap($mapResponse);
            $this->fields2Reset = true;
            if (!$this->error) {
                // Verifico la signature
                if (!$this->checkResponseSignature($mapResponse)) {
                    throw new IgfsException('Invalid IGFS Response signature');
                }

                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            $this->resetFields();
            $this->fields2Reset = true;
            $this->error = true;
            $this->errorDesc = $e->getMessage();
            if ($e instanceof IgfsMissingParException) {
                $this->rc = 'IGFS_20000'; // dati mancanti
                $this->errorDesc = $e->getMessage();
            }
            if ($e instanceof ConnectionException) {
                $this->rc = 'IGFS_007'; // errore di comunicazione
                $this->errorDesc = $e->getMessage();
            }
            if ($e instanceof ReadWriteException) {
                $this->rc = 'IGFS_007'; // errore di comunicazione
                $this->errorDesc = $e->getMessage();
            }
            if (null == $this->rc) {
                $this->rc = 'IGFS_909'; // se nessuno ha settato l'errore...
            }

            return false;
        }
    }

    /**
     * TODO: unused variables
     *
     * @param $url
     * @return array|void
     * @throws IgfsException
     */
    private function executeHttp($url)
    {
        $requestMethod = 'POST';
        // cTimeout;
        // timeout;
        $url = $this->getServerUrl($url);
        $contentType = $this->getContentType();

        try {
            $mapResponse = $this->process($url);
        } catch (IOException $e) {
            throw $e;
        }
        if (null == $mapResponse) {
            throw new IgfsException('Invalid IGFS Response');
        }

        return $mapResponse;
    }

    /**
     * TODO: Lol?
     *
     * @return string
     */
    protected function getContentType()
    {
        return 'text/xml; charset="utf-8"';
    }

    /**
     * @param $key
     * @param $fields
     * @return string
     * @throws IgfsException
     */
    protected function getSignature($key, $fields)
    {
        try {
            return IgfsUtils::getSignature($key, $fields);
        } catch (\Exception $e) {
            // TODO: this is funny
            throw new IgfsException($e);
        }
    }

    /**
     * @return string
     */
    protected function getUniqueBoundaryValue()
    {
        return IgfsUtils::getUniqueBoundaryValue();
    }
}
