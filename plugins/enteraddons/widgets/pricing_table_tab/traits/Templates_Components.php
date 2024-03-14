<?php 
namespace Enteraddons\Widgets\Pricing_Table_Tab\Traits;
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
    public static function tab_switcher() {

        $settings = self::getSettings();

        echo '<div class="price-switcher">
                <div class="switch-wrap">
                    <div class="switch-single-tab">'.esc_html( $settings['tab_1st_text'] ).'</div>
                    <div class="switch-single-tab single-active">'.esc_html( $settings['tab_2nd_text'] ).'</div>
                </div>
            </div>';
    }

    public static function title( $title = '' ) {

        if( !empty( $title ) ) {
            return '<h5>'.esc_html( $title ).'</h5>';
        }
    }

    public static function price( $salePrice= '', $regularPrice= '', $duration= ''  ) {
        $settings = self::getSettings();

        // currency
        $currency = '';
        if( !empty( $settings['currency'] ) ) {
            if( $settings['currency'] == 'custom' && !empty( $settings['custom_currency'] )  ) {
                $currency = $settings['custom_currency'];
            } else {
                $currency = \Enteraddons\Classes\Helper::getCurrencySymbol($settings['currency']);
            }
        }

        $html = '';
        $html .= '<div class="price-text">';
            if( !empty( $regularPrice ) ) {
                $html .= '<span class="table-regular-price"><del>'.esc_html( $currency.$regularPrice ).'</del></span>';
            }
            $html .= '<strong class="table-price-text">';
            $html .= esc_html( $currency.$salePrice );
            $html .= '</strong>';
            if( !empty( $duration ) ) {
                $html .= '<sub class="table-duration-text">'.esc_html( $duration ).'</sub>';
            }
        $html .= '</div>';

        return $html;

    }
    public static function divider() {
        $settings = self::getSettings();
        if( $settings['is_show_divider'] != 'yes' ) {
            return;
        }
        //
        $t = !empty( $settings['divider_image']['url'] ) ? '<img src="'.esc_url( $settings['divider_image']['url'] ).'" /> ' : '';
        return '<div class="price-devider">'.apply_filters( 'pricing_tab_table_dvider', $t ).'</div>';
    }
    public static function badge() {
        $settings = self::getSettings();
        if( !empty( $settings['show_badge'] ) ) {
            $text = !empty( $settings['badge_text'] ) ? $settings['badge_text'] : '';
            return '<span class="enteraddons-price-badge '.esc_html( $settings['badge_style'] ).'">'.esc_html( $text ).'</span>';
        }
    }
    public static function tabOneContent() {

        $settings = self::getSettings();
        if( !empty( $settings['pricing_table_content_monthly'] ) ) {

            foreach( $settings['pricing_table_content_monthly'] as $tableContent ) {

                $regularPrice = !empty( $tableContent['regular_price'] ) ? $tableContent['regular_price'] : '';
                $salePrice    = !empty( $tableContent['price'] ) ? $tableContent['price'] : '';
                $duration     = !empty( $tableContent['duration'] ) ? $tableContent['duration'] : '';
                $is_active = !empty( $tableContent['is_active'] ) && $tableContent['is_active'] == 'yes' ? 'active' :'';

                echo '<div class="single-price '.esc_attr( $is_active ).'">
                        <div class="price-head">
                            '.self::title( $tableContent['title'] ).self::price( $salePrice, $regularPrice, $duration ).self::divider().'
                        </div>
                        <div class="price-body">
                            '.wp_kses_post( $tableContent['features'] ).'
                        </div>
                        <div class="btn-wrap">'.self::button( $tableContent['link'], $tableContent['btn_text'] ).'</div>
                    </div>';

            }

        }

    }
    public static function tabTwoContent() {

        $settings = self::getSettings();
        if( !empty( $settings['pricing_table_content_yearly'] ) ) {
            foreach( $settings['pricing_table_content_yearly'] as $tableContent ) {

                $regularPrice = !empty( $tableContent['regular_price'] ) ? $tableContent['regular_price'] : '';
                $salePrice    = !empty( $tableContent['price'] ) ? $tableContent['price'] : '';
                $duration     = !empty( $tableContent['duration'] ) ? $tableContent['duration'] : '';
                $is_active = !empty( $tableContent['is_active'] ) && $tableContent['is_active'] == 'yes' ? 'active' :'';

                echo '<div class="single-price '.esc_attr( $is_active ).'">
                        <div class="price-head">
                            '.self::title( $tableContent['title'] ).self::price( $salePrice, $regularPrice, $duration ).self::divider().'
                        </div>
                        <div class="price-body">
                            '.wp_kses_post( $tableContent['features'] ).'
                        </div>
                        <div class="btn-wrap">'.self::button( $tableContent['link'], $tableContent['btn_text'] ).'</div>
                    </div>';

            }
        }

    }
    public static function button( $link = '', $linkText = '' ) {
        $settings = self::getSettings();
        
            $btnText = !empty( $settings['global_btn_text'] ) ? $settings['global_btn_text'] : $linkText;
            $btnIcon = \Enteraddons\Classes\Helper::getElementorIcon( $settings['button_icon'] );
            $btnData = $btnText.$btnIcon;
            
            return \Enteraddons\Classes\Helper::getElementorLinkHandler( $link, $btnData, 'sta-price-btn' );
 
    }


}