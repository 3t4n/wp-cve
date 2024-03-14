<?php
namespace WPT\DiviProductCarousel\Divi;

use WPT_Divi_Product_Carousel_Modules\DiviProductCarouselExtension;
use WPT_Divi_Product_Carousel_Modules\DiviProductCarouselModule\DiviProductCarouselModule;
use WPT_Divi_Product_Carousel_Modules\DiviCarouselProductItemModule\DiviCarouselProductItemModule;

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
        new DiviProductCarouselExtension($this->container);
    }

    /**
     * Load assets for the carousel image.
     */
    public function enqueue_carousel_blog_module_assets()
    {
        $jsUri = $this->container['url'] . '/resources/slick/slick.min.js';
        wp_enqueue_script('wptools-slick', $jsUri, ['jquery']);

        $cssUri = $this->container['url'] . '/resources/slick/slick.css';
        wp_enqueue_style('wptools-slick', $cssUri);

        $cssThemeUri = $this->container['url'] . '/resources/slick/slick-theme.css';
        wp_enqueue_style('wptools-slick-theme', $cssThemeUri);

        $customCSS = $this->container['url'] . '/resources/css/style.css';
        wp_enqueue_style('wptools-blog-carousel', $customCSS, ['wptools-slick-theme']);

        $slickInitJs = $this->container['url'] . '/resources/js/script.js';
        wp_enqueue_script('wptools-slick-init-product-carousel', $slickInitJs, ['wptools-slick']);
    }

    /**
     * Enqueue assets for divi modules
     */
    public function enqueue_divi_module_assets()
    {
        if (isset($_GET['et_fb']) and ($_GET['et_fb'] == '1')) {
            $this->enqueue_carousel_blog_module_assets();
        }
    }

    /**
     * ET builder ready hook
     *
     * @return [type] [description]
     */
    public function et_builder_ready()
    {
        new DiviProductCarouselModule($this->container);
        new DiviCarouselProductItemModule($this->container);
    }

    public function get_margin_array($margin_string)
    {
        $margin_values = explode('|', $margin_string);

        return [
            'margin-top'    => isset($margin_values[0]) ? $margin_values[0] : '',
            'margin-right'  => isset($margin_values[1]) ? $margin_values[1] : '',
            'margin-bottom' => isset($margin_values[2]) ? $margin_values[2] : '',
            'margin-left'   => isset($margin_values[3]) ? $margin_values[3] : '',
        ];
    }

    /**
     *
     */
    public function get_padding_array($padding_string)
    {
        $padding_values = explode('|', $padding_string);

        return [
            'padding-top'    => isset($padding_values[0]) ? $padding_values[0] : '',
            'padding-right'  => isset($padding_values[1]) ? $padding_values[1] : '',
            'padding-bottom' => isset($padding_values[2]) ? $padding_values[2] : '',
            'padding-left'   => isset($padding_values[3]) ? $padding_values[3] : '',
        ];
    }
}
