<?php
namespace WPT_Ultimate_Divi_Carousel\PostTypeCardCarouselFullWidth;

use WPT_Ultimate_Divi_Carousel\PostTypeCardCarousel\PostTypeCardCarousel;

class PostTypeCardCarouselFullWidth extends PostTypeCardCarousel
{

    public $slug       = 'et_pb_wpdt_post_type_carousel_fw';
    public $vb_support = 'on';
    protected $container;
    protected $helper;

    public function __construct(
        $container
    ) {
        $this->container = $container;
        parent::__construct($container, true);
        $this->slug = 'et_pb_wpdt_post_type_carousel_fw';
    }

}
