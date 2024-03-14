<?php 
namespace Enteraddons\Widgets\Testimonial\Traits;
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
        if( !empty( $settings['slider_items_mobile'] ) || !empty( $settings['slider_items_tablet'] ) ) {

            $items_mobile = !empty( $settings['slider_items_mobile'] ) ? $settings['slider_items_mobile'] : '';
            $items_tablet = !empty( $settings['slider_items_tablet'] ) ? $settings['slider_items_tablet'] : '';
            $items_desktop = !empty( $settings['slider_items'] ) ? $settings['slider_items'] : 3;

            //
            $mm = !empty( $settings['slider_margin_mobile'] ) ? $settings['slider_margin_mobile'] : '';
            $tablet = !empty( $settings['slider_margin_tablet'] ) ? $settings['slider_margin_tablet'] : '';
            $marginDesktop = !empty( $settings['slider_margin'] ) ? $settings['slider_margin'] : '';

           $responsive = [ '0' => ['items' => $items_mobile, 'margin' => $mm  ], '768' => [ 'items' => $items_tablet, 'margin' => $tablet ], '1025' => ['items' => $items_desktop, 'margin' => $marginDesktop] ]; 
        }

        $sliderSettings = [

            'items'         => !empty( $settings['slider_items'] ) ? $settings['slider_items'] : 3,
            'margin'        => !empty( $settings['slider_margin'] ) ? $settings['slider_margin'] : 0,
            'loop'          => !empty( $settings['slider_loop'] ) && $settings['slider_loop'] == 'yes' ? true : false,
            'smartSpeed'    => !empty( $settings['slider_smartSpeed'] ) ? $settings['slider_smartSpeed'] : 450,
            'autoplay'      => !empty( $settings['slider_autoplay'] ) && $settings['slider_autoplay'] == 'yes' ? true : false,
            'autoplayTimeout'  => !empty( $settings['slider_autoplayTimeout'] ) ? $settings['slider_autoplayTimeout'] : 8000,
            'center'        => !empty( $settings['slider_center'] ) && $settings['slider_center'] == 'yes' ? true : false,
            'animateIn'     => !empty( $settings['slider_animateIn'] ) && $settings['slider_animateIn'] == 'yes' ? true : false,
            'animateOut'    => !empty( $settings['slider_animateOut'] ) && $settings['slider_animateOut'] == 'yes' ? true : false,
            'nav'           => !empty( $settings['slider_nav'] ) && $settings['slider_nav'] == 'yes' ? true : false,
            'dots'          => !empty( $settings['slider_dots'] ) && $settings['slider_dots'] == 'yes' ? true : false,
            'mousedrag'     => !empty( $settings['slider_mouseDrag'] ) && $settings['slider_mouseDrag'] == 'yes' ? true : false,
            'autoWidth'     => !empty( $settings['slider_autoWidth'] ) && $settings['slider_autoWidth'] == 'yes' ? true : false,
            'responsive'     => $responsive

        ];

        return json_encode( $sliderSettings );

    }

    public static function dotSliderSettings() {

        $sliderDotSettings = [

        'items'         => !empty( $settings['slider_dot_items'] ) ? $settings['slider_dot_items'] : 5,
        'margin'        => !empty( $settings['slider_dot_margin'] ) ? $settings['slider_dot_margin'] : 20,
        'loop'          => !empty( $settings['slider_dot_loop'] ) && $settings['slider_dot_loop'] == 'yes' ? true : false,
        'smartSpeed'    => !empty( $settings['slider_dot_smartSpeed'] ) ? $settings['slider_dot_smartSpeed'] : 450,
        'autoplay'      => !empty( $settings['slider_dot_autoplay'] ) && $settings['slider_dot_autoplay'] == yes ? true : false,
        'autoplayTimeout'  => !empty( $settings['slider_dot_autoplayTimeout'] ) ? $settings['slider_dot_autoplayTimeout'] : 8000,
        'center'        => !empty( $settings['slider_dot_center'] ) && $settings['slider_dot_center'] == 'yes' ? true : true,
        'animateIn'     => !empty( $settings['slider_dot_animateIn'] ) && $settings['slider_dot_animateIn'] == 'yes' ? true : false,
        'animateOut'    => !empty( $settings['slider_dot_animateOut'] ) && $settings['slider_dot_animateOut'] == 'yes' ? true : false,
        'nav'           => !empty( $settings['slider_dot_nav'] ) && $settings['slider_dot_nav'] == 'yes' ? true : true,
        'dots'          => !empty( $settings['slider_dot_dots'] ) && $settings['slider_dot_dots'] == 'yes' ? true : false,
        'mousedrag'     => !empty( $settings['slider_dot_mouseDrag'] ) && $settings['slider_dot_mouseDrag'] == 'yes' ? true : false,
        'autoWidth'     => !empty( $settings['slider_dot_autoWidth'] ) && $settings['slider_dot_autoWidth'] == 'yes' ? true : false,
        'responsive'    => [ "0" => ["items" => "3"], "480" => ["items" => "5"] ]

        ];

        return json_encode( $sliderDotSettings );

    }

    public static function thumbnail( $img, $class = '' ) {

        if( !empty( $img['url'] ) ) {
            $altText = \Elementor\Control_Media::get_image_alt( $img );
            echo '<div class="enteraddons-testimonial-thumb'.esc_attr( $class ).'"><img src="'.esc_url( $img['url'] ).'" alt="'.esc_attr( $altText ).'"></div>';
        }

    }

    public static function descriptions( $descriptions, $LeftQuote = '', $RightQuote = '' ) {
        //
        $getLeftQuote = '';
        if( !empty( $LeftQuote ) ) {
            $getLeftQuote = '<span class="testimonial-left-quote">'.Helper::getElementorIcon( $LeftQuote ).'</span>';
        }
        //
        $getRightQuote = '';
        if( !empty( $RightQuote ) ) {
            $getRightQuote = '<span class="testimonial-right-quote">'.Helper::getElementorIcon( $RightQuote ).'</span>';
        }
        
        echo'<div class="enteraddons-testimonial-text"><p>'.Helper::allowFormattingTagHtml($getLeftQuote).esc_html( $descriptions ).Helper::allowFormattingTagHtml($getRightQuote).'</p></div>';
    }

    public static function rating( $rating ) {
        if( !empty( $rating ) && $rating != 'none' ) {
        echo '<div class="enteraddons-testimonial-rating">';
        \Enteraddons\Classes\Helper::ratingStar( $rating );
        echo '</div>';
        }
    }

    public static function authorName( $name ) {
        echo '<p class="author-name">'.esc_html( $name ).'</p>';
    }

    public static function authorDesignation( $designation ) {
        echo '<p class="author-designation">'.esc_html( $designation ).'</p>';
    }

    public static function authorLocation( $location ) {
        echo '<span class="author-location">'.esc_html( $location ).'</span>';
    }

    
}