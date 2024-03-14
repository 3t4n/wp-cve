<?php
namespace WPT\UltimateDiviCarousel;

use WPTools\Pimple\Container;

/**
 * Container
 */
class Loader extends Container
{
    /**
     *
     * @var mixed
     */
    public static $instance;

    public function __construct()
    {
        parent::__construct();

        $this['bootstrap'] = function ($container) {
            return new WP\Bootstrap($container);
        };

        $this['post_types'] = function ($container) {
            return new WP\PostTypes($container);
        };

        $this['taxonomies'] = function ($container) {
            return new WP\Taxonomies($container);
        };

        $this['taxonomy_cat_form_fields'] = function ($container) {
            return new TaxonomyCategory\FormFields($container);
        };

        $this['divi'] = function ($container) {
            return new Divi\Divi($container);
        };

        $this['divi_background'] = function ($container) {
            return new Divi\Background($container);
        };

        $this['divi_animation'] = function ($container) {
            return new Divi\Animation($container);
        };

        $this['divi_post_type_query_builder'] = function ($container) {
            return new Divi\PostTypeQueryBuilder($container);
        };

        $this['divi_taxonomy_query_builder'] = function ($container) {
            return new Divi\TaxonomyQueryBuilder($container);
        };

        $this['margin_padding'] = function ($container) {
            return new Divi\MarginPadding($container);
        };

        $this['swiper_divi'] = function ($container) {
            return new Divi\Swiper($container);
        };

        $this['image_card_carousel'] = function ($container) {
            return new ImageCardCarousel\ImageCardCarousel($container);
        };

        $this['woo_featured_products'] = function ($container) {
            return new WooCommerce\FeaturedProducts($container);
        };

        $this['woo_recent_products'] = function ($container) {
            return new WooCommerce\RecentProducts($container);
        };

        $this['woo_sale_products'] = function ($container) {
            return new WooCommerce\SaleProducts($container);
        };

        $this['woo_best_selling_products'] = function ($container) {
            return new WooCommerce\BestSellingProducts($container);
        };

        $this['woo_top_rated_products'] = function ($container) {
            return new WooCommerce\TopRatedProducts($container);
        };

        $this['woo_custom_filter_products'] = function ($container) {
            return new WooCommerce\CustomFilterProducts($container);
        };

        $this['carousel_nav'] = function ($container) {
            return new Carousel\Navigation($container);
        };

    }

    /**
     * Get container instance.
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Loader();
        }

        return self::$instance;
    }

/**
 * Plugin run
 */
    public function run()
    {
        register_activation_hook($this['plugin_file'], [$this['bootstrap'], 'register_activation_hook']);

        //divi actions
        add_action('et_builder_ready', [$this['divi'], 'et_builder_ready'], 1);
        add_action('divi_extensions_init', [$this['divi'], 'divi_extensions_init']);

        add_action('wp_enqueue_scripts', [$this['divi'], 'enqueue_assets']);

        add_action('wp_print_styles', function () {
            // dequeue the empty styles
            // wp_dequeue_style('et_pb_wpt_divi_toolkit-styles');
        });

        // REST APIs
        add_action('rest_api_init', [$this['divi_post_type_query_builder'], 'rest_api_init']);

        // taxonomy related
        add_action(
            'admin_init',
            [$this['taxonomy_cat_form_fields'], 'admin_init'],
            10,
            0
        );

        // post types init
        $this['post_types']->init();

        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_media();
        });

        add_action('plugins_loaded', [$this['bootstrap'], 'on_plugins_loaded']);

    }
};
