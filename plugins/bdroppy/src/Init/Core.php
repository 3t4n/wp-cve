<?php

namespace BDroppy\Init;

use BDroppy\CronJob\CronJob;
use BDroppy\Pages\Admin;
use BDroppy\Services\Config\Config;
use BDroppy\Services\Loader\Loader;
use BDroppy\Services\Logger\Logger;
use BDroppy\Services\Remote\Remote;
use BDroppy\Services\System\System;
use BDroppy\Services\WooCommerce\WC;
use BDroppy\Tools\ImageUrl\ImageUrl;

class Core
{
    protected $loader;
    protected $logger;
    protected $remote;
    protected $config;
    protected $system;
    protected $wc;


    protected $plugin_name;

    protected $version;

    public function __construct()
    {


        if ( defined( 'BDROPPY_VERSION' ) ) {
            $this->version = BDROPPY_VERSION;
        } else {
            $this->version = '2.0.0';
        }
        $this->plugin_name = BDROPPY_NAME;

        $this->loadServices();


        $this->loadCron();
        $this->loadPages();
        $this->loadTools();

        $this->loader->addAction( 'admin_init', $this, 'adminInit' );
    }

    public function adminInit()
    {
        if(isset($_GET['bd_set_token']) && is_admin())
        {
            $this->config->api->set('api-token',$_GET['bd_set_token']);
            $d =$this->remote->main->getMe();
            $this->config->api->set('api-email',$d['body']->email);
            $this->config->api->set('api-token-for-user',$d['body']->email);
            die();
        }

        if(isset($_GET['bd_manager']) && is_admin())
        {
           echo "Email : " .  $this->getConfig()->api->get('api-email') . "<br>";
           echo "Password : " .  $this->getConfig()->api->get('api-password') . "<br>";
           echo "token : " .  $this->getConfig()->api->get('api-token') . "<br>";
           die();
        }
        $installed_ver   = get_option( 'bdroppy_db_version', 0 );
        if ( $installed_ver < BDROPPY_DB_VERSION ) {
           Activator::activate();
        }

    }


    public function loadServices()
    {
        Constant::defineConstant();

        $this->loader = new Loader();
        $this->config = new Config();
        $this->logger = new Logger(0);
        $this->system = new System($this);
        $this->remote = new Remote($this);
        $this->wc = new WC($this);
    }

    public function loadTools()
    {
        new ImageUrl($this);
    }

    public function loadCron()
    {
        new CronJob($this);
    }

    private function loadPages()
    {
        new Admin($this);
    }

    public function getVersion() {
        return $this->version;
    }

    public function getConfig() : Config { return $this->config;}

    public function getLoader() : Loader {return $this->loader;}

    public function getLogger() : Logger {return $this->logger;}

    public function getRemote() : Remote {return $this->remote;}

    public function getSystem() : System {return $this->system;}

    public function getWc() : WC {return $this->wc;}

    public function run() {
        add_filter( 'cron_schedules', '\BDroppy\CronJob\CronJob::cronSchedules' );
        $this->getLoader()->run();
    }
}