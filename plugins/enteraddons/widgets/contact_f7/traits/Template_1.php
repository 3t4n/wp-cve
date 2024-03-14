<?php 
namespace Enteraddons\Widgets\Contact_F7\Traits;
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

		echo '<div class="enteraddons-cf7-wrap enteraddons-contact-form">';
            echo do_shortcode('[contact-form-7 id="'.absint( $settings['form_id'] ).'"]');
        echo '</div>';
	}

}