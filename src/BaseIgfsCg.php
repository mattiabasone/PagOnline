<?php

namespace PagOnline;

use SimpleXMLElement;
use PagOnline\Exceptions\IOException;
use PagOnline\Exceptions\IgfsException;
use PagOnline\Exceptions\ReadWriteException;
use PagOnline\Exceptions\ConnectionException;
use PagOnline\Exceptions\IgfsMissingParException;

/**
 * Class BaseIgfsCg.
 */
abstract class BaseIgfsCg implements IgfsCgInterface
{
    /**
     * Package version.
     *
     * @var string
     */
    const VERSION = '2.4.1.5';

    /**
     * Set the request namespace here.
     *
     * @var string
     */
    protected $requestNamespace = '';

    /**
     * Signature Key.
     *
     * @var string
     */
    public $kSig;

    /**
     * Payment Gateway server url.
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
     * @var
     */
    public $request;

    /**
     * BaseIgfsCg constructor.
     */
    public function __construct()
    {
        $this->resetFields();
    }

    /**
     * Reset fields.
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
     * {@inheritdoc}
     */
    public static function getVersion(): string
    {
        return self::VERSION;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest(): string
    {
        return (string) new $this->requestNamespace();
    }

    /**
     * Check required fields, if any of the required parameter is missing it'll throw an IgfsMissingParException.
     *
     * @throws IgfsMissingParException
     */
    protected function checkFields()
    {
        if (empty($this->serverURL) && (empty($this->serverURLs) || !\is_array($this->serverURLs))) {
            throw new IgfsMissingParException('Missing serverURL');
        }

        if (null == $this->kSig || '' == $this->kSig) {
            throw new IgfsMissingParException('Missing kSig');
        }

        if (empty($this->tid) && (empty($this->merID) && empty($this->payInstr))) {
            throw new IgfsMissingParException('Missing tid');
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
     * Get configured server url.
     *
     * @param string $serverUrl
     *
     * @return string
     */
    protected function getServerUrl($serverUrl)
    {
        if (!IgfsUtils::endsWith($serverUrl, '/')) {
            $serverUrl .= '/';
        }

        return $serverUrl.$this->getServicePort();
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
        $request = $this->getRequest();
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
        if (0 == \count($dom->{$root})) {
            return;
        }

        $fields = IgfsUtils::parseResponseFields($dom->{$root});
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

    /**
     * @param $response
     *
     * @return bool
     */
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

    /**
     * @param $url
     *
     * @throws \PagOnline\Exceptions\IOException
     * @throws \PagOnline\Exceptions\IgfsException
     *
     * @return array
     */
    protected function process($url)
    {
        $request = $this->buildRequest();
        if (null === $request) {
            throw new IgfsException('IGFS Request is null');
        }
        $request = $this->setRequestSignature($request);
        $response = $this->post($url, $request);

        if (null === $response) {
            throw new IgfsException('IGFS Response is null');
        }

        return $this->parseResponse($response);
    }

    /**
     * Execute a POST request.
     *
     * @param $url
     * @param $request
     *
     * @throws ConnectionException
     * @throws ReadWriteException
     *
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
     * TODO: Refactor this.
     *
     * @return bool
     */
    public function execute(): bool
    {
        try {
            $this->checkFields();
            $mapResponse = [];

            if (null !== $this->serverURL) {
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
        } catch (\Exception $e) {
            $this->resetFields();
            $this->fields2Reset = true;
            $this->error = true;
            $this->errorDesc = $e->getMessage();
            if ($e instanceof IgfsMissingParException) {
                $this->rc = Errors::IGFS_20000; // Missing data
                $this->errorDesc = $e->getMessage();
            }
            if ($e instanceof ConnectionException) {
                $this->rc = Errors::IGFS_007; // Communication error
                $this->errorDesc = $e->getMessage();
            }
            if ($e instanceof ReadWriteException) {
                $this->rc = Errors::IGFS_007; // Communication error
                $this->errorDesc = $e->getMessage();
            }
            if (null === $this->rc) {
                $this->rc = Errors::IGFS_909; // System error
            }

            return false;
        }
    }

    /**
     * TODO: unused variables.
     *
     * @param $url
     *
     * @throws IgfsException
     * @throws IOException
     *
     * @return array
     */
    private function executeHttp($url)
    {
        $url = $this->getServerUrl($url);
        try {
            $mapResponse = $this->process($url);
        } catch (IOException $e) {
            // TODO: uhm...nice
            throw $e;
        }
        if (empty($mapResponse)) {
            throw new IgfsException('Invalid IGFS Response');
        }

        return $mapResponse;
    }

    /**
     * @param $key
     * @param $fields
     *
     * @throws IgfsException
     *
     * @return string
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
