<?php 
namespace Enteraddons\Widgets\Image_Slider\Traits;
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

trait Templates_Components {

    public static function carouselSettings() {

        $settings = self::getDisplaySettings();

        $responsive = [];
        if( !empty( $settings['slider_items_mobile'] ) || !empty( $settings['slider_items_tablet'] ) ) {
           $responsive = [ '0' => ['items' => $settings['slider_items_mobile']], '768' => [ 'items' => $settings['slider_items_tablet'] ], '1025' => ['items' => $settings['slider_items']] ]; 
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
            echo '<div class="'.esc_attr( $class ).'"><img src="'.esc_url( $img['url'] ).'" alt="'.esc_attr( $altText ).'"></div>';
        }

    }

    public static function title( $title = '' ) {
        if( !empty( $title ) ) {
            echo'<h6 class="image-title">'.esc_html( $title ).'</h6>';
        }
    }

    public static function sub_title( $subTitle = '' ) {
        if( !empty( $subTitle ) ) {
            echo '<p class="image-sub-title">'.esc_html( $subTitle ).'</p>';
        }
    }


    
}