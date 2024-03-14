<?php
namespace WPT\DiviCarouselImages;

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

        $this['divi'] = function ($container) {
            return new Divi\Divi($container);
        };

        $this['carousel_module_fields'] = function ($container) {
            return new \WPT_Divi_Carousel_Images_Modules\DiviCarouselModule\Fields($container);
        };
    }

    /**
     * Get container instance.
     */
    public static function get_instance()
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
        // activation hook
        register_activation_hook($this['file'], [$this['bootstrap'], 'register_activation_hook']);

        //divi actions
        add_action('et_builder_ready', [$this['divi'], 'et_builder_ready'], 1);
        add_action('divi_extensions_init', [$this['divi'], 'divi_extensions_init']);

        add_action('wp_enqueue_scripts', [$this['divi'], 'enqueue_divi_module_assets']);

        $loader = $this;
        //admin menu
        add_action('admin_menu', function () use ($loader) {
            add_submenu_page(
                'et_divi_options',
                'Image Carousel Using Divi',
                'Image Carousel Using Divi',
                'manage_options',
                'image-carousel-for-divi',
                function () use ($loader) {
                    ob_start();
                    require $loader['dir'] . '/resources/views/menu.php';
                    echo ob_get_clean();
                }
            );
        },
            99
        );

    }
}
