<?php

namespace PagOnline\Tests\Unit;

use PagOnline;
use Illuminate\Support\Str;
use PagOnline\IgfsCgFactory;
use PagOnline\Laravel\PagOnlineServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class BaseLaravelTest extends OrchestraTestCase
{
    protected $poServerUrl = 'http://test.com/services';
    protected $poTimeout = 15000;
    protected $poTerminalId = '';
    protected $poSignatureKey = '';
    protected $poCurrencyCode = 'EU';
    protected $poLanguageId = 'IT';

    protected function setUp()
    {
        parent::setUp();
        $this->poTerminalId = Str::random(24);
        $this->poSignatureKey = Str::random(24);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
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
    protected function getPackageProviders($app)
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
    protected function getPackageAliases($app)
    {
        return [
            'IgfsCg' => \PagOnline\Laravel\Facades\IgfsCgFacade::class,
            'config' => \Illuminate\Config\Repository::class,
        ];
    }

    /** @test */
    public function shouldCreateFacade()
    {
        $this->app->singleton('igfscg', function () {
            return new IgfsCgFactory();
        });
        $this->app->alias('igfscg', IgfsCgFactory::class);
        /** @var \PagOnline\Init\IgfsCgInit $igfsCgInit */
        $igfsCgInit = \IgfsCg::make(PagOnline\Actions::IGFS_CG_INIT);
        $this->assertIsObject($igfsCgInit);
        $this->assertObjectHasAttribute('serverURL', $igfsCgInit);
        $this->assertEquals($this->poServerUrl, $igfsCgInit->serverURL);
    }
}
