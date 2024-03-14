<?php

namespace WPDM\Elementor;

use Elementor\Elements_Manager;
use Elementor\Widgets_Manager;
use WPDM\Elementor\API\API;
use WPDM\Elementor\Widgets\AllPackagesWidget;
use WPDM\Elementor\Widgets\CategoryWidget;
use WPDM\Elementor\Widgets\DirectLinkWidget;
use WPDM\Elementor\Widgets\FrontendWidget;
use WPDM\Elementor\Widgets\LoginFormWidget;
use WPDM\Elementor\Widgets\PackagesWidget;
use WPDM\Elementor\Widgets\PackageWidget;
use WPDM\Elementor\Widgets\RegFormWidget;
use WPDM\Elementor\Widgets\SearchResultWidget;
use WPDM\Elementor\Widgets\TagWidget;
use WPDM\Elementor\Widgets\UserDashboardWidget;
use WPDM\Elementor\Widgets\UserProfileWidget;

final class Main
{

    /**
     * 
     * 
     */
    public static function getInstance()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new self;
        }
        return $instance;
    }

    /**
     * 
     * 
     */
    private function __construct()
    {
        API::getInstance();
        add_action("plugin_loaded", [$this, 'pluginLoaded']);

    }

    function pluginLoaded(){
	    load_plugin_textdomain('wpdm-elementor', dirname(__DIR__) . "/languages/", basename(__DIR__).'/languages/');
        add_action( 'elementor/init', [ $this, 'addHooks' ] );
    }


    /**
     * 
     * 
     */
    function addHooks()
    {
        add_action('elementor/elements/categories_registered', [$this, 'registerCategory'], 0);
        add_action('elementor/widgets/register', [$this, 'registerWidgets'], 99);
    }

    /**
     * 
     * 
     */
    public function registerCategory(Elements_Manager $elementsManager)
    {
        $elementsManager->add_category('wpdm', ['title' => 'Download Manager']);
    }

    /**
     * 
     * 
     */
    public function registerWidgets(Widgets_Manager $widget_manager)
    {

        require_once __DIR__.'/includes.php';


        $widget_manager->register(new PackagesWidget());

        $widget_manager->register(new PackageWidget());

        $widget_manager->register(new CategoryWidget());

        //$widget_manager->register(new TagWidget());

        $widget_manager->register(new AllPackagesWidget());

        $widget_manager->register(new SearchResultWidget());

        $widget_manager->register(new RegFormWidget());

        $widget_manager->register(new LoginFormWidget());

        $widget_manager->register(new FrontendWidget());

        $widget_manager->register(new UserDashboardWidget());

        $widget_manager->register(new DirectLinkWidget());

        //$widget_manager->register(new UserProfileWidget());

    }

}
