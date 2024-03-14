<?php

namespace LaStudioKitExtensions\GiveWP;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use LaStudioKitExtensions\Module_Base;

class Module extends Module_Base {

    /**
     * Module version.
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module directory path.
     *
     * @since 1.0.0
     * @access protected
     * @var string $path
     */
    protected $path;

    /**
     * Module directory URL.
     *
     * @since 1.0.0
     * @access protected
     * @var string $url.
     */
    protected $url;

    public static function is_active(){
        return class_exists('Give', false);
    }

    public function __construct()
    {
        $this->path = lastudio_kit()->plugin_path('includes/extensions/give-wp/');
        $this->url  = lastudio_kit()->plugin_url('includes/extensions/give-wp/');


	    add_action( 'elementor/widgets/register', function ($widgets_manager){
		    $widgets_manager->register( new Widgets\GiveFormGrid() );
		    $widgets_manager->register( new Widgets\GiveFormGoal() );
		    $widgets_manager->register( new Widgets\GiveFormDonate() );
	    } );
    }
}