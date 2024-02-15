<?php

namespace PagOnline\Tests\Unit\Laravel;

use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use PagOnline;
use PagOnline\IgfsCgFactory;
use PagOnline\Laravel\PagOnlineServiceProvider;

class BaseLaravelTest extends OrchestraTestCase
{
    protected $poServerUrl = 'http://test.com/services';
    protected $poTimeout = 15000;
    protected $poTerminalId = '';
    protected $poSignatureKey = '';
    protected $poCurrencyCode = 'EU';
    protected $poLanguageId = 'IT';

    protected function setUp(): void
    {
        parent::setUp();
        $this->poTerminalId = Str::random(24);
        $this->poSignatureKey = Str::random(24);
    }

    /** @test */
    public function shouldCreateFacade(): void
    {
        $this->app->singleton('igfscg', function () {
            return new IgfsCgFactory();
        });
        $this->app->alias('igfscg', IgfsCgFactory::class);
        /** @var PagOnline\Init\IgfsCgInit $igfsCgInit */
        $igfsCgInit = \IgfsCg::make(PagOnline\Actions::IGFS_CG_INIT);
        $this->assertIsObject($igfsCgInit);
        $this->assertObjectHasProperty('serverURL', $igfsCgInit);
        $this->assertEquals($this->poServerUrl, $igfsCgInit->serverURL);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('pagonline.server_url', $this->poServerUrl);
        $app['config']->set('pagonline.timeout', $this->poTimeout);
        $app['config']->set('pagonline.terminal_id', $this->poTerminalId);
        $app['config']->set('pagonline.signature_key', $this->poSignatureKey);
        $app['config']->set('pagonline.currency_code', $this->poCurrencyCode);
        $app['config']->set('pagonline.language_id', $this->poLanguageId);
    }

    /**
     * Load package service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            PagOnlineServiceProvider::class,
        ];
    }

    /**
     * Load package alias.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app): array
    {
        return [
            'IgfsCg' => PagOnline\Laravel\Facades\IgfsCgFacade::class,
            'config' => \Illuminate\Config\Repository::class,
        ];
    }
}
