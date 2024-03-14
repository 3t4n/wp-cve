<?php
namespace WPT\DiviCarouselImages\Divi;

use WPT_Divi_Carousel_Images_Modules\DiviImageCarouselExtension;
use WPT_Divi_Carousel_Images_Modules\DiviCarouselModule\DiviCarouselModule;
use WPT_Divi_Carousel_Images_Modules\DiviCarouselItemModule\DiviCarouselItemModule;

/**
 * Divi.
 */
class Divi
{
    protected $container;

    protected $data;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Register divi extension
     *
     * @return [type] [description]
     */
    public function divi_extensions_init()
    {
        new DiviImageCarouselExtension($this->container);
    }

    /**
     * Load assets for the carousel image.
     */
    public function enqueue_carousel_image_module_assets()
    {
        $jsUri = $this->container['url'] . '/resources/slick/slick.min.js';
        wp_enqueue_script('wptools-slick', $jsUri, ['jquery']);

        $cssUri = $this->container['url'] . '/resources/slick/slick.css';
        wp_enqueue_style('wptools-slick', $cssUri);

        $cssThemeUri = $this->container['url'] . '/resources/slick/slick-theme.css';
        wp_enqueue_style('wptools-slick-theme', $cssThemeUri);

        $slickInitJs = $this->container['url'] . '/resources/js/script.js';
        wp_enqueue_script('wptools-slick-init', $slickInitJs, ['wptools-slick']);
    }

    /**
     * Enqueue assets for divi modules
     */
    public function enqueue_divi_module_assets()
    {
        if (isset($_GET['et_fb']) and ($_GET['et_fb'] == '1')) {
            $this->enqueue_carousel_image_module_assets();
        }
    }

    /**
     * ET builder ready hook
     *
     * @return [type] [description]
     */
    public function et_builder_ready()
    {
        new DiviCarouselModule($this->container);
        new DiviCarouselItemModule($this->container);
    }
}
