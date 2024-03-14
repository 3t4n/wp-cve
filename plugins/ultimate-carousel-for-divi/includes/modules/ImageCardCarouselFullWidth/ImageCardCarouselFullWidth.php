<?php
namespace WPT_Ultimate_Divi_Carousel\ImageCardCarouselFullWidth;

use WPT_Ultimate_Divi_Carousel\ImageCardCarousel\ImageCardCarousel;

class ImageCardCarouselFullWidth extends ImageCardCarousel
{

    public $slug       = 'et_pb_wpdt_image_card_carousel_fw';
    public $child_slug = 'et_pb_wpdt_image_card_carousel_item_fw';
    public $vb_support = 'on';
    protected $container;
    protected $helper;

    protected $module_credits = [
        'module_uri' => 'https://wptools.app/',
        'author'     => 'WP Tools',
        'author_uri' => 'https://wptools.app/',
    ];

    public function __construct(
        $container
    ) {
        $this->container = $container;
        parent::__construct($container, true);
        $this->slug       = 'et_pb_wpdt_image_card_carousel_fw';
        $this->child_slug = 'et_pb_wpdt_image_card_carousel_item_fw';
    }

};
