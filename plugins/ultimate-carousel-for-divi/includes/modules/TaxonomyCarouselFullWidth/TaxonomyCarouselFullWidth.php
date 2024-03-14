<?php
namespace WPT_Ultimate_Divi_Carousel\TaxonomyCarouselFullWidth;

use WPT_Ultimate_Divi_Carousel\TaxonomyCarousel\TaxonomyCarousel;

class TaxonomyCarouselFullWidth extends TaxonomyCarousel
{

    public $slug       = 'et_pb_wpdt_taxonomy_carousel_fw';
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
        $this->slug = 'et_pb_wpdt_taxonomy_carousel_fw';
    }
}
