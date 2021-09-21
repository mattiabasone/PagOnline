<?php

namespace PagOnline\Traits;

use GuzzleHttp\Client;

/**
 * Trait HttpClient.
 */
trait HttpClient
{
    /**
     * @var int
     */
    protected $connectTimeout = 5;

    /**
     * @var int
     */
    protected $requestTimeout = 30;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var array
     */
    protected $httpCustomConfiguration = [];

    /**
     * @var string
     */
    protected $httpAuthUser = '';

    /**
     * @var string
     */
    protected $httpAuthPass = '';

    /**
     * @var string
     */
    protected $httpProxy = '';

    /**
     * @var bool
     */
    protected $httpVerifySsl = true;

    /**
     * @param array $configuration
     */
    public function setCustomHttpRequestConfig(array $configuration): void
    {
        $this->httpCustomConfiguration = $configuration;
    }

    /**
     * @param string $httpProxy
     */
    public function setHttpProxy(string $httpProxy): void
    {
        $this->httpProxy = $httpProxy;
    }

    /**
     * @param string $httpAuthUser
     */
    public function setHttpAuthUser(string $httpAuthUser): void
    {
        $this->httpAuthUser = $httpAuthUser;
    }

    /**
     * @param string $httpAuthPass
     */
    public function setHttpAuthPass(string $httpAuthPass): void
    {
        $this->httpAuthPass = $httpAuthPass;
    }

    /**
     * @param int $connectTimeout
     */
    public function setConnectTimeout(int $connectTimeout): void
    {
        $this->connectTimeout = $connectTimeout;
    }

    /**
     * @param int $requestTimeout
     */
    public function setRequestTimeout(int $requestTimeout): void
    {
        $this->requestTimeout = $requestTimeout;
    }

    /**
     * @return Client
     */
    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    /**
     * @param Client $httpClient
     */
    public function setHttpClient(Client $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Generate Http Client.
     */
    public function generateHttpClient()
    {
        $this->setHttpClient(new Client());
    }

    /**
     * @param bool $httpVerifySsl
     */
    public function setHttpVerifySsl(bool $httpVerifySsl): void
    {
        $this->httpVerifySsl = $httpVerifySsl;
    }

    /**
     * Create configuration array for Guzzle Client.
     *
     * @return array
     */
    protected function baseHttpRequestConfig()
    {
        $configuration = [
            \GuzzleHttp\RequestOptions::TIMEOUT => $this->requestTimeout,
            \GuzzleHttp\RequestOptions::CONNECT_TIMEOUT => $this->connectTimeout,
            \GuzzleHttp\RequestOptions::VERIFY => $this->httpVerifySsl,
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Content-Type' => 'text/xml; charset="utf-8"',
            ],
        ];

        if (!empty($this->httpProxy)) {
            $configuration[\GuzzleHttp\RequestOptions::PROXY] = $this->httpProxy;
        }

        if ($this->httpAuthUser !== null && $this->httpAuthPass !== null) {
            $configuration[\GuzzleHttp\RequestOptions::AUTH] = [
                $this->httpAuthUser,
                $this->httpAuthPass,
            ];
        }

        return $configuration;
    }

    /**
     * Get Http Request config.
     *
     * @return array
     */
    protected function getHttpRequestConfig()
    {
        return \array_merge($this->baseHttpRequestConfig(), $this->httpCustomConfiguration);
    }

    /**
     * Make HTTP Post request.
     *
     * @param string $url
     * @param string $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function httpPost($url, $request)
    {
        $configuration = $this->getHttpRequestConfig();
        $configuration[\GuzzleHttp\RequestOptions::BODY] = $request;

        return $this->httpClient->post($url, $configuration);
    }
}
