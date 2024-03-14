<?php
namespace WPT_Ultimate_Divi_Carousel\ImageCardCarouselItemFullWidth;

use WPT_Ultimate_Divi_Carousel\ImageCardCarouselItem\ImageCardCarouselItem;

class ImageCardCarouselItemFullWidth extends ImageCardCarouselItem
{

    public $slug       = 'et_pb_wpdt_image_card_carousel_item_fw';
    public $vb_support = 'on';
    public $type       = 'child';
    protected $container;
    protected $helper;

    public function __construct(
        $container
    ) {
        $this->container = $container;
        parent::__construct($container, true);
        $this->slug = 'et_pb_wpdt_image_card_carousel_item_fw';
    }

}
