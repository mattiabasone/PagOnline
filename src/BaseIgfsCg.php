<?php

namespace PagOnline;

use Illuminate\Support\Str;
use PagOnline\Exceptions\ConnectionException;
use PagOnline\Exceptions\IgfsException;
use PagOnline\Exceptions\IgfsMissingParException;
use PagOnline\Exceptions\IOException;
use PagOnline\Traits\HttpClient;

/**
 * Class BaseIgfsCg.
 */
abstract class BaseIgfsCg implements IgfsCgInterface
{
    use HttpClient;

    /**
     * Package version.
     *
     * @var string
     */
    public const VERSION = '2.4.1.5';

    /**
     * Signature Key.
     *
     * @var string
     */
    public $kSig;

    /**
     * Payment Gateway server url.
     *
     * @var null|string
     */
    public $serverURL;
    public $serverURLs;

    public $shopID;

    public $tid;
    public $merID;
    public $payInstr;

    public $rc;
    public $error;
    public $errorDesc;
    public $langID;

    protected static $soapBodyTag = 'Body';
    protected static $soapResponseParentTag = '';
    protected static $soapResponseTag = 'response';

    /**
     * Set the request namespace here.
     *
     * @var string
     */
    protected $requestNamespace = '';

    protected $fields2Reset = false;

    /**
     * BaseIgfsCg constructor.
     */
    public function __construct()
    {
        $this->generateHttpClient();
    }

    /**
     * Reset fields.
     */
    public function resetFields()
    {
        $this->tid = null;
        $this->merID = null;
        $this->payInstr = null;
        $this->shopID = null;
        $this->rc = null;
        $this->error = false;
        $this->errorDesc = null;
        $this->fields2Reset = false;
    }

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
     * TODO: Refactor this.
     *
     * @return bool
     */
    public function execute(): bool
    {
        try {
            $this->checkFields();
            $mapResponse = [];
            if (!empty($this->serverURL)) {
                $mapResponse = $this->executeHttp($this->serverURL);
            } else {
                $sURLs = $this->serverURLs;
                $sURL = array_shift($sURLs);
                $finished = false;
                while (!$finished) {
                    try {
                        $mapResponse = $this->executeHttp($sURL);
                        $finished = true;
                    } catch (ConnectionException $e) {
                        if (!empty($sURLs)) {
                            $sURL = array_shift($sURLs);
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
            }

            return false;
        } catch (\Throwable $e) {
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
            if ($this->rc === null) {
                $this->rc = Errors::IGFS_909; // System error
            }

            return false;
        }
    }

    /**
     * Returns public properties to array.
     *
     * @return array
     */
    public function toArray()
    {
        $propertiesArray = [];
        $publicProperties = (new \ReflectionObject($this))->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($publicProperties as $publicProperty) {
            $propertiesArray[$publicProperty->getName()] = $publicProperty->getValue($this);
        }

        return $propertiesArray;
    }

    abstract protected function getServicePort();

    /**
     * @return array
     */
    protected function getCommonRequestSignatureFields(): array
    {
        return [
            $this->getVersion(),
            $this->tid,
            $this->merID,
            $this->payInstr,
            $this->shopID,
        ];
    }

    /**
     * Get additional signature fields (request specific).
     *
     * @return array
     */
    abstract protected function getAdditionalRequestSignatureFields(): array;

    /***
     * Generates a signature
     *
     * @param $signatureFields
     *
     * @throws IgfsException
     *
     * @return string
     */
    protected function getSignature(array $signatureFields): string
    {
        try {
            $data = '';
            foreach ($signatureFields as $value) {
                $data .= (string) $value;
            }

            return base64_encode(hash_hmac('sha256', $data, $this->kSig, true));
        } catch (\Exception $e) {
            throw new IgfsException($e);
        }
    }

    /**
     * Set signature key on request.
     *
     * @param string $request
     *
     * @throws IgfsException
     *
     * @return void
     */
    protected function setRequestSignature(&$request): void
    {
        $signatureFields = array_merge(
            $this->getCommonRequestSignatureFields(),
            $this->getAdditionalRequestSignatureFields()
        );
        $signature = $this->getSignature($signatureFields);
        $this->replaceRequestParameter($request, 'signature', $signature, false);
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

        if (empty($this->kSig)) {
            throw new IgfsMissingParException('Missing kSig');
        }

        if (empty($this->tid) && (empty($this->merID) && empty($this->payInstr))) {
            throw new IgfsMissingParException('Missing tid');
        }
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
        if (!Str::endsWith($serverUrl, '/')) {
            $serverUrl .= '/';
        }

        return $serverUrl.$this->getServicePort();
    }

    /**
     * @param string $request
     * @param string $parameter
     * @param mixed  $value
     * @param bool   $wrap_cdata
     */
    protected function replaceRequestParameter(
        string &$request,
        string $parameter,
        $value = null,
        bool $wrap_cdata = true
    ) {
        $value = (string) $value;
        if ($value === '') {
            $xmlTag = '';
        } else {
            $xmlTag = "<{$parameter}>";
            $xmlTag .= $wrap_cdata ? "<![CDATA[{$value}]]>" : $value;
            $xmlTag .= "</{$parameter}>";
        }
        $request = str_replace('{'.$parameter.'}', $xmlTag, $request);
    }

    /**
     * Build request XML.
     *
     * @return mixed|string
     */
    protected function buildRequest()
    {
        $request = $this->getRequest();
        $this->replaceRequestParameter($request, 'apiVersion', $this->getVersion());
        $this->replaceRequestParameter($request, 'shopID', $this->shopID);
        $this->replaceRequestParameter($request, 'tid', $this->tid);
        $this->replaceRequestParameter($request, 'merID', $this->merID);
        $this->replaceRequestParameter($request, 'payInstr', $this->payInstr);

        return $request;
    }

    abstract protected function getResponseSignature($response);

    /**
     * @param string $response
     *
     * @return null|\SimpleXMLElement
     */
    protected function responseXmlToObject(string $response): ?\SimpleXMLElement
    {
        try {
            $dom = new \SimpleXMLElement($response, LIBXML_NOERROR, false);

            /*$responseNode = $dom->children('soap', true)->{static::$soapBodyTag}
                ->children('ns1', true)->{static::$soapResponseParentTag}
                ->children()
                ->{self::$soapResponseTag};*/
            return $dom->xpath('//response')[0];
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * @param string $response
     *
     * @return array
     */
    protected function parseResponse($response): array
    {
        $responseNode = $this->responseXmlToObject($response);
        if ($responseNode === null || $responseNode->children()->count() === 0) {
            return [];
        }
        $fields = IgfsUtils::parseResponseFields($responseNode);
        if (\count($fields) > 0) {
            $fields[self::$soapResponseTag] = $responseNode->asXML();
        }

        return $fields;
    }

    /**
     * @param array $response
     */
    protected function parseResponseMap($response)
    {
        $this->tid = IgfsUtils::getValue($response, 'tid');
        $this->rc = IgfsUtils::getValue($response, 'rc');
        if (IgfsUtils::getValue($response, 'error') === null) {
            $this->error = true;
        } else {
            $this->error = ((string) IgfsUtils::getValue($response, 'error') === 'true');
        }
        $this->errorDesc = IgfsUtils::getValue($response, 'errorDesc');
    }

    /**
     * @param array $response
     *
     * @return bool
     */
    protected function checkResponseSignature($response)
    {
        $signature = IgfsUtils::getValue($response, 'signature');
        if ($signature === null) {
            return false;
        }

        if ($signature != $this->getResponseSignature($response)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $url
     *
     * @throws IOException
     * @throws IgfsException
     *
     * @return array
     */
    protected function process($url)
    {
        $request = $this->buildRequest();
        $this->setRequestSignature($request);
        $response = $this->post($url, $request);

        if ($response === '') {
            throw new IgfsException('IGFS Response is null');
        }

        return $this->parseResponse($response);
    }

    /**
     * @return string
     */
    protected function getUniqueBoundaryValue()
    {
        return IgfsUtils::getUniqueBoundaryValue();
    }

    /**
     * Execute a POST request.
     *
     * @param string $url
     * @param string $request
     *
     * @throws ConnectionException
     *
     * @return string
     */
    private function post($url, $request): string
    {
        try {
            $response = $this->httpPost($url, $request);
        } catch (\Throwable $e) {
            throw new ConnectionException($url, $e->getMessage());
        }

        return $response->getBody()->getContents();
    }

    /***
     * @param string $url
     *
     * @throws IgfsException
     * @throws IOException
     *
     * @return array
     */
    private function executeHttp($url)
    {
        $mapResponse = $this->process(
            $this->getServerUrl($url)
        );

        if (empty($mapResponse)) {
            throw new IgfsException('Invalid IGFS Response');
        }

        return $mapResponse;
    }
}
