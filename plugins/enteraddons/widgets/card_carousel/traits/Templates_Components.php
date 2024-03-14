<?php 
namespace Enteraddons\Widgets\Card_Carousel\Traits;
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

    protected static function getSettings() {
        return self::getDisplaySettings();
    }

    public static function carouselSettings() {

        $settings = self::getSettings();

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

    protected static function icon( $data ) {

        $settings = self::getSettings();

        $divider = !empty( $settings['divider_show'] ) && 'yes' == $settings['divider_show'] ? ' icon-divider' : '';

        $iconType    = $data['icon_type'];
        $altText     = \Elementor\Control_Media::get_image_alt( $data['image'] );

        echo '<div class="enteraddons-info-box-icon '.esc_attr( $iconType.$divider ).'">';

            if( $data['icon_type'] != 'img' ) {
                echo \Enteraddons\Classes\Helper::getElementorIcon( $data['icon'] );
            }else {
                echo '<img src="'.esc_url( $data['image']['url'] ).'" class="svg" alt="'.esc_attr( $altText ).'">';
            }
            
        echo '</div>';
    }

    protected static function title( $data ) {
        if( !empty( $data['title'] ) ) {
            echo '<h5>'.esc_html( $data['title'] ).'</h5>';
        }
    }
    
    protected static function description( $data ) {
        if( !empty( $data['description'] ) ) {
            echo '<p>'.esc_html( $data['description'] ).'</p>';
        }
    }

    
}