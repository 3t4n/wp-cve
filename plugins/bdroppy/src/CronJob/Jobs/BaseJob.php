<?php
namespace BDroppy\CronJob\Jobs;


use BDroppy\Init\Core;
use BDroppy\Services\WooCommerce\WC;

abstract class BaseJob
{

    protected $core ;
    protected $loader ;
    protected $logger ;
    protected $wc ;
    protected $remote ;
    protected $config;
    protected $system;
    protected $actionName;
    protected $activeLanguages;

    public function __construct(Core $core)
    {
        $this->core     = $core;
        $this->loader   = $core->getLoader();
        $this->logger   = $core->getLogger();
        $this->remote   = $core->getRemote();
        $this->system   = $core->getSystem();
        $this->config   = $core->getConfig();
        $this->wc       = $core->getWc();

        $this->loader->addAction( $this->actionName, $this, 'run' );
    }

    abstract public function handle();

    public function run()
    {
        @ini_set( 'max_execution_time', 500 );
        @wc_set_time_limit( 500 );
        $this->activeLanguages  = $this->system->language->getActives();
        $this->handle();
    }
}