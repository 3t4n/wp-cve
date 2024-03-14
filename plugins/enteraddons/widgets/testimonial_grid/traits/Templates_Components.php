<?php 
namespace Enteraddons\Widgets\Testimonial_Grid\Traits;
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
    
    protected static function getSettings() {
        return self::getDisplaySettings();
    }


    public static function thumbnail( $img, $class = '' ) {

        if( !empty( $img['url'] ) ) {
            $altText = \Elementor\Control_Media::get_image_alt( $img );
            echo '<div class="ea-testimonial-thumb'.esc_attr( $class ).'"><img src="'.esc_url( $img['url'] ).'" alt="'.esc_attr( $altText ).'"></div>';
        }

    }
    
    public static function descriptions( $descriptions, $LeftQuote = '', $RightQuote = '' ) {
        //
        $getLeftQuote = '';
        if( !empty( $LeftQuote ) ) {
            $getLeftQuote = '<span class="ea-testimonial-left-quote">'.Helper::getElementorIcon( $LeftQuote ).'</span>';
        }
        //
        $getRightQuote = '';
        if( !empty( $RightQuote ) ) {
            $getRightQuote = '<span class="ea-testimonial-right-quote">'.Helper::getElementorIcon( $RightQuote ).'</span>';
        }
        
        echo'<div class="ea-testimonial-text"><p>'.Helper::allowFormattingTagHtml($getLeftQuote).esc_html( $descriptions ).Helper::allowFormattingTagHtml($getRightQuote).'</p></div>';
    }

    public static function rating( $rating ) {
        if( !empty( $rating ) && $rating != 'none' ) {
        echo '<div class="ea-testimonial-rating">';
        \Enteraddons\Classes\Helper::ratingStar( $rating );
        echo '</div>';
        }
    }

    public static function authorName( $name ) {
        echo '<p class="ea-author-name">'.esc_html( $name ).'</p>';
    }

    public static function authorDesignation( $designation ) {
        echo '<p class="ea-author-designation">'.esc_html( $designation ).'</p>';
    }

    public static function authorLocation( $location ) {
        echo '<span class="ea-author-location">'.esc_html( $location ).'</span>';
    }   
}