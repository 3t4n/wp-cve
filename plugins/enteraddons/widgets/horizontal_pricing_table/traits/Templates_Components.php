<?php 
namespace Enteraddons\Widgets\Horizontal_Pricing_Table\Traits;
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
	
    // Set Settings options
    protected static function getSettings() {
        return self::getDisplaySettings();
    }

    // Image
    public static function logo() {
        $settings = self::getSettings();

        $altText  = \Elementor\Control_Media::get_image_alt( $settings['product_logo'] );
        if( $settings['icon_type'] != 'img' ) {
            echo '<div class="ea-product-logo">';
                echo \Enteraddons\Classes\Helper::getElementorIcon( $settings['pricing_icon'] );
            echo '</div>';
        } else {

            if( !empty( $settings['product_logo']['url'] ) ) {
                echo '<div class="ea-product-logo">';
                    echo '<img src="'.esc_url( $settings['product_logo']['url'] ).'" alt="'.esc_attr( $altText ).'" >';
                echo '</div>';
                }

        }
    }

    //Title
    protected static function Title() {
        $settings = self::getSettings();

        if( !empty( $settings['title'] ) ) {
            echo '<div class="ea-vp-title">'.esc_html( $settings['title'] ).'</div>';

        }  
    }

    //Product Feature
    protected static function product_feature( $feature ) {

        if ( !empty( $feature['feature_icon'] ) ) {
                echo '<span class="ea-feature-icon">'.\Enteraddons\Classes\Helper::getElementorIcon( $feature['feature_icon'] ).'</span>';
        } 
        if( !empty( $feature['product_feature'] ) ) {
            echo esc_html( $feature['product_feature'] );
        }  
    }

    // Product Price
    public static function price() {
        $settings = self::getSettings();
       
        echo '<div class="ea-product-price">';
           
            //Currency
            if( !empty( $settings['currency'] ) ) {
                if( $settings['currency'] == 'custom' && !empty( $settings['custom_currency'] )  ) {
                    $currency = $settings['custom_currency'];
                } else {
                    $currency = \Enteraddons\Classes\Helper::getCurrencySymbol($settings['currency']);
                }
                echo '<span class="ea-currency">'.esc_html( $currency ).'</span>'; 
            }
            // Price
            if( !empty( $settings['product_price'] ) ) {
                echo  esc_html( $settings['product_price'] );
            }
            
        echo '</div>';
    }

    //Duration
    protected static function duration() {
        $settings = self::getSettings();

        if( !empty( $settings['duration'] ) ) {
            echo '<div class="ea-duration">'.esc_html( $settings['duration'] ).'</div>';

        }  
    }

    //Ratting Text
    protected static function review_text() {
        $settings = self::getSettings();

        if( !empty( $settings['rating_text'] ) ) {
            echo '<p class="ratting-text">'.esc_html( $settings['rating_text'] ).'</p>';
        }  
    }

    // Ratting Star
    protected static function ratting_star() {
        $settings = self::getSettings();

        if( !empty( $settings['product_ratings'] ) ) {
            echo '<div class="ea-rating-star">';
                echo \Enteraddons\Classes\Helper::ratingStar( $settings['product_ratings'], false );
            echo'</div>';
        }
        
    }

    //Ratting Number
    protected static function ratting_number() {
        $settings = self::getSettings();

        if( !empty( $settings['product_ratings'] ) ) {
            echo '<h6>'.esc_html( $settings['product_ratings'] ).'</h6>';
        }
        
    }

    // Button
    public static function button() {
        $settings = self::getSettings();

        $label     = !empty( $settings['button_label'] ) ?  $settings['button_label'] : esc_html__( 'Buy Now', 'enteraddons' );
        echo \Enteraddons\Classes\Helper::getElementorLinkHandler( $settings['button_link'], $label, 'ea-h-pricing-btn');
    }
}