<?php

namespace WunderAuto;

use WunderAuto\Pimple\Container;
use WunderAuto\Pimple\ServiceProviderInterface;

/**
 * Class Provider
 */
class Provider implements ServiceProviderInterface
{
    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var string
     */
    protected $dbVersion;

    /**
     * @var bool
     */
    protected $isPro;

    /**
     * Constructor
     *
     * @param string $slug
     * @param string $version
     * @param string $dbVersion
     * @param bool   $isPro
     */
    public function __construct($slug, $version, $dbVersion, $isPro)
    {
        $this->slug      = $slug;
        $this->version   = $version;
        $this->dbVersion = $dbVersion;
        $this->isPro     = $isPro;

        if (!defined('WUNDERAUTO_UPDATE_URL')) {
            define('WUNDERAUTO_UPDATE_URL', 'https://updates.wundermatics.com');
        }
    }

    /**
     * Register objects and bootstrap plugin
     *
     * @param Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple)
    {
        $this->addObjects($pimple);
        $this->boostrap($pimple);
    }

    /**
     * Instantiate objects
     *
     * @param Container $pimple
     *
     * @return void
     */
    protected function boostrap(Container $pimple)
    {
        $logger           = $pimple['logger'];
        $i18n             = $pimple['i18n'];
        $database         = $pimple['database'];
        $upgrader         = $pimple['upgrader'];
        $postTypes        = $pimple['posttypes'];
        $postTypes        = $pimple['posttypehandler'];
        $ajax             = $pimple['ajaxhandler'];
        $webhook          = $pimple['webhook'];
        $reTriggerHandler = $pimple['re-trigger-handler'];
        $notice           = $pimple['admin-notice'];

        // Front end behaviours
        $userBehaviour = $pimple['user-behaviour'];
        $frontEnd      = $pimple['frontend-behaviour'];

        if (is_admin()) {
            $admin = $pimple['admin'];
        }
    }

    /**
     * Add object definitions to the container
     *
     * @param Container $pimple
     *
     * @return void
     */
    protected function addObjects(Container $pimple)
    {
        $pimple['loader'] = function ($pimple) {
            return new \WunderAuto\Loader();
        };

        $pimple['logger'] = function ($pimple) {
            $logger = new \WunderAuto\Logger();
            $logger->register($pimple['loader']);
            return $logger;
        };

        $pimple['i18n'] = function ($pimple) {
            $i18n = new \WunderAuto\I18n();
            $i18n->register($pimple['loader']);
            return $i18n;
        };

        $pimple['database'] = function ($pimple) {
            $database = new \WunderAuto\Database($this->dbVersion);
            $database->databaseVersionCheck();
            return $database;
        };

        $pimple['upgrader'] = function ($pimple) {
            $upgrader = new \WunderAuto\Upgrader($pimple['wunderauto']);
            $upgrader->register($pimple['loader']);
            $upgrader->upgradeCheck();
            return $upgrader;
        };

        $pimple['posttypes'] = function ($pimple) {
            $workflow = new PostTypes\Workflow($this->slug);
            $workflow->register($pimple['loader']);

            $reTrigger = new PostTypes\ReTrigger($this->slug);
            $reTrigger->register($pimple['loader']);

            return [$workflow, $reTrigger];
        };

        $pimple['posttypehandler'] = function ($pimple) {
            $postTypeHandler = new PostTypes\Handler($pimple['wunderauto']);
            $postTypeHandler->register($pimple['loader']);
            return $postTypeHandler;
        };

        $pimple['ajaxhandler'] = function ($pimple) {
            $ajaxHandler = new AjaxHandler();
            $ajaxHandler->register($pimple['loader']);
            return $ajaxHandler;
        };

        $pimple['scheduler'] = function ($pimple) {
            $scheduler = new Scheduler();
            $scheduler->register($pimple['loader']);
            return $scheduler;
        };

        $pimple['re-trigger-handler'] = function ($pimple) {
            $reTriggerHandler = new ReTriggerHandler();
            $reTriggerHandler->register($pimple['loader']);
            return $reTriggerHandler;
        };

        $pimple['webhook'] = function ($pimple) {
            $webhook = new Webhook();
            $webhook->register($pimple['loader']);
        };

        $pimple['wunderauto'] = function ($pimple) {
            $wunderAuto = new \WunderAuto\WunderAuto(
                $this->slug,
                $this->version
            );
            $wunderAuto->register($pimple['loader'], $pimple['scheduler'], $this->isPro);
            return $wunderAuto;
        };

        $pimple['admin'] = function ($pimple) {
            $admin = new Admin($pimple['wunderauto'], $this->slug, $this->version);
            $admin->register($pimple['loader']);
            return $admin;
        };

        $pimple['user-behaviour'] = function ($pimple) {
            $users = new \WunderAuto\Behaviours\Users();
            $users->register($pimple['loader']);
            return $users;
        };

        $pimple['frontend-behaviour'] = function ($pimple) {
            $frontEnd = new \WunderAuto\Behaviours\FrontEnd();
            $frontEnd->register($pimple['loader']);
            return $frontEnd;
        };

        $pimple['admin-notice'] = function ($pimple) {
            $notice = new AdminNotice();
            $notice->register($pimple['loader']);
            return $notice;
        };
    }
}
