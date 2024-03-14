<?php 
namespace Enteraddons\Widgets\Nav_Search\Traits;
/**
 * Enteraddons team template class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Template_1 {

	public static function markup_style_1() {
        $settings = self::getSettings();

       if( !empty( $settings['search_style'] ) && $settings['search_style'] == 'style_1' ) {
            echo '<div class="ea-search-btn-wrap"><div class="ea-search-btn search-icon ea-search-icon-wrap" open-modal="search">';
                if( !empty( $settings['search_modal_icon'] ) ) {
                    echo \Enteraddons\Classes\Helper::getElementorIcon( $settings['search_modal_icon'] );
                }
            echo '</div></div>';
            self::searchModalForm();
        } else {
            self::searchForm();
        }

	}

}