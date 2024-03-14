<?php 
namespace Enteraddons\Widgets\Vertical_Testimonial\Traits;
/**
 * Enteraddons template class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */
use \Enteraddons\Classes\Helper;
trait Templates_Components {

    public static function carouselSettings() {
        $settings = self::getDisplaySettings();

        $responsive = [];

        $verticalSliderSettings = [
            'slides_to_show'    => !empty( $settings['slides_to_show'] ) ? $settings['slides_to_show'] : 3,
            'autoplay'          => !empty( $settings['auto_play'] ) && $settings['auto_play'] == 'yes' ? true : false,
            'auto_play_speed'   => !empty( $settings['auto_play_speed'] ) ? $settings['auto_play_speed'] : 5000,
            'clicked_slide'     => !empty( $settings['clicked_slide'] ) && $settings['clicked_slide'] == 'yes' ? true : false,
            'mousewheel_control' => !empty( $settings['mousewheel_control'] ) && $settings['mousewheel_control'] == 'yes' ? true : false,
            'loop'      => !empty( $settings['loop'] ) && $settings['loop'] == 'yes' ? true : false,
            'centered_slides'        => !empty( $settings['centered_slides'] ) && $settings['centered_slides'] == 'yes' ? true : false,
            'slide_nav'        => !empty( $settings['slide_nav'] ) && $settings['slide_nav'] == 'yes' ? true : false,
            'responsive'        => $responsive,
        ];

        return json_encode( $verticalSliderSettings );
    }

    protected static function star( $ratings ) {
        if( !empty( $ratings['ratings'] ) ) {
            echo '<div class="feedback-rating">'.\Enteraddons\Classes\Helper::ratingStar( $ratings['ratings'], false ).'</div>';
        } 
    }

    public static function clientName( $name ) {
        echo '<p class="customer-name">'.esc_html( $name['client_name'] ).'</p>';
    }

    public static function clientReview( $review ) {
        echo '<div class="feedback-details"><p>'.esc_html( $review['client_review'] ).'</p></div>';
    }

    public static function thumbnail( $img ) {
        if( !empty( $img['client_image']['url'] ) ) {
            $altText = \Elementor\Control_Media::get_image_alt( $img );
            echo '<div class="customer-image"><img src="'.esc_url( $img['client_image']['url'] ).'" alt="'.esc_attr( $altText ).'"></div>';
        }
    }
    
}