<?php
/*
Plugin Name: List Urls
Plugin URI: http://www.graphem.ca
Description: Get a list of all the current urls of your Wordpress site
Version: 0.2
Author: Graphem Solutions Inc.
Author URI: http://www.graphem.ca
*/
namespace WPListUrls;

use WPListUrls\WPCore\admin\WPadminNotice;
use WPListUrls\WPCore\View;
use WPListUrls\WPCore\WPplugin;
use WPListUrls\ListUrls\FListUrls;
use WPListUrls\League\Csv\Reader;

require 'autoload.php';

class ListUrls extends WPplugin
{

    public static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        load_plugin_textdomain('wp-listurls', false, dirname(plugin_basename(__FILE__)).'/lang');

        parent::__construct(__FILE__, 'List Urls', 'wp-listurls');
        
        $this->setReqWpVersion("3.0");
        $this->setReqWPMsg(sprintf(__('%s Requirements failed. WP version must at least %s', 'wp-listurls'), $this->getName(), $this->reqWPVersion));
        $this->setReqPhpVersion("5.3.3");
        $this->setReqPHPMsg(sprintf(__('%s Requirements failed. PHP version must at least %s', 'wp-listurls'), $this->getName(), $this->reqPHPVersion));
               
        $this->setMainFeature(FListUrls::getInstance());
        
        parent::init();
    }
}

$ListUrls = ListUrls::getInstance();

$ListUrls->register();
