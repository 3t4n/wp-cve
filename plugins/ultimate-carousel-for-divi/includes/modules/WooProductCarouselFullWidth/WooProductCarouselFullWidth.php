<?php
namespace WPT_Ultimate_Divi_Carousel\WooProductCarouselFullWidth;

use WPT_Ultimate_Divi_Carousel\WooProductCarousel\WooProductCarousel;

class WooProductCarouselFullWidth extends WooProductCarousel
{

    public $slug       = 'et_pb_wpdt_wc_product_carousel_fw';
    public $vb_support = 'on';
    protected $container;
    protected $helper;

    public function __construct(
        $container,
        $fullwidth = false
    ) {
        $this->container = $container;
        parent::__construct($container, true);
        $this->slug = 'et_pb_wpdt_wc_product_carousel_fw';
    }

}
