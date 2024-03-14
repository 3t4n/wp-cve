<?php
namespace WPListUrls\ListUrls;

use WPListUrls\WPCore\WPfeature;
use WPListUrls\WPCore\WPscript;
use WPListUrls\WPCore\WPscriptTheme;
use WPListUrls\WPCore\WPstyleTheme;
use WPListUrls\WPCore\WPoption;
use WPListUrls\WPCore\WPpostMeta;
use WPListUrls\WPCore\WPpage;
use WPListUrls\WPCore\View;
use WPListUrls\WPCore\WPshortcode;
use WPListUrls\WPCore\WPscriptTemplate;
use WPListUrls\WPCore\admin\WPsubmenuPage;
use WPListUrls\ListUrls\ListUrls;


class FListUrls extends WPfeature
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
        parent::__construct('wp-listurls', 'listurls');
    }    
    

    public function init()
    {
        if (is_admin()) {
            $this->initAdmin();
        } else {
            $this->initTheme();
        }
    }


    public function initAdmin()
    {
        $listUrlsView = new View($this->getViewsPath().'list-urls.php');

        $this->hook(
            new WPsubmenuPage(
                $listUrlsView,
                'tools.php',
                'List Urls',
                'List Urls',
                'wp-listurls',
                'manage_options'
            )
        );

        $WPListUrls = new ListUrls();
    }

    public function initTheme()
    {
        //
    }

    public function install()
    {
        //$this->subsFeature->install();
    }
}
