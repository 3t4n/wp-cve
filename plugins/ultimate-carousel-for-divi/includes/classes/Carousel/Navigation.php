<?php
namespace WPT\UltimateDiviCarousel\Carousel;

/**
 * Navigation.
 */
class Navigation
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * HTML for previous button
     */
    public function previous_button_html()
    {
        $html = '<div class="swiper-button-prev swiper-nav"></div>';

        return apply_filters('uc_carousel_nav_button_html', $html, 'prev');
    }

    /**
     * HTML for previous button
     */
    public function next_button_html()
    {
        $html = '<div class="swiper-button-next swiper-nav"></div>';

        return apply_filters('uc_carousel_nav_button_html', $html, 'next');
    }

}
