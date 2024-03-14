<?php 
namespace Enteraddons\Widgets\Testimonial_Multi_Rows\Traits;
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
    
    public static function thumbnail( $img, $class = '' ) {

        if( !empty( $img['url'] ) ) {
            $altText = \Elementor\Control_Media::get_image_alt( $img );
            echo '<div class="avatar'.esc_attr( $class ).'"><img src="'.esc_url( $img['url'] ).'" alt="'.esc_attr( $altText ).'"></div>';
        }

    }

    public static function descriptions( $descriptions, $LeftQuote = '', $RightQuote = '' ) {
        //
        $getLeftQuote = '';
        if( !empty( $LeftQuote ) ) {
            $getLeftQuote = \Enteraddons\Classes\Helper::getElementorIcon( $LeftQuote );
        } 
        //
        $getRightQuote = '';
        if( !empty( $RightQuote ) ) {
            $getRightQuote = \Enteraddons\Classes\Helper::getElementorIcon( $RightQuote );
        }
        
        echo'<p>'.$getLeftQuote.esc_html( $descriptions ).$getRightQuote.'</p>';
    }

    public static function rating( $rating ) {
        echo '<div class="rating">';
        \Enteraddons\Classes\Helper::ratingStar( $rating );
        echo '</div>';
    }

    public static function authorName( $name ) {
        echo '<h5>'.esc_html( $name ).'</h5>';
    }

    public static function authorDesignation( $designation ) {
        echo '<p class="author-designation">'.esc_html( $designation ).'</p>';
    }

    public static function authorLocation( $location ) {
        echo '<span class="author-location">'.esc_html( $location ).'</span>';
    }

    
}