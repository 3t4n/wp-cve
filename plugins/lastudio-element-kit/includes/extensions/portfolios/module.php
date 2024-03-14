<?php

namespace LaStudioKitExtensions\Portfolios;

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
	    $available_extension = lastudio_kit_settings()->get_option('avaliable_extensions', []);
	    return !empty($available_extension['portfolio_content_type']) && filter_var($available_extension['portfolio_content_type'], FILTER_VALIDATE_BOOLEAN);
    }

    public function __construct()
    {
        $this->path = lastudio_kit()->plugin_path('includes/extensions/portfolios/');
        $this->url  = lastudio_kit()->plugin_url('includes/extensions/portfolios/');

		add_action( 'init', [ $this, 'register_content_type' ] );

        add_action( 'elementor/widgets/register', function ($widgets_manager){
            $widgets_manager->register( new Widgets\Portfolio() );
            $widgets_manager->register( new Widgets\Portfolio_Gallery() );
            $widgets_manager->register( new Widgets\Portfolio_Meta() );
        } );
    }

	public function register_content_type(){
		register_post_type( 'la_portfolio', apply_filters('lastudio-kit/admin/portoflio/args', [
			'labels'                => [
				'name'          => __( 'Portfolios', 'lastudio-kit' ),
				'singular_name' => __( 'Portfolio', 'lastudio-kit' ),
			],
			'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
			'taxonomies'            => [ 'post_tag' ],
			'menu_icon'             => 'dashicons-portfolio',
			'public'                => true,
			'menu_position'         => 7,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'rewrite'               => array( 'slug' => 'portfolio' )
		]));
		register_taxonomy( 'la_portfolio_category', 'la_portfolio', apply_filters('lastudio-kit/admin/portoflio_cat/args', [
			'hierarchical'      => true,
			'show_in_nav_menus' => true,
			'labels'            => array(
				'name'          => __( 'Categories', 'lastudio-kit' ),
				'singular_name' => __( 'Category', 'lastudio-kit' )
			),
			'query_var'         => true,
			'show_admin_column' => true,
			'rewrite'           => array('slug' => 'portfolio-category')
		]));
	}
}