<?php 
namespace Enteraddons\Widgets\Advanced_Animation_Title\Traits;
/**
 * Enteraddons Advanced Animation Title template class
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
        $TitleAnimationSettings = self::TitleAnimationSettings();
        $id = self::getDisplayID();
        $id = 'ea-aat-title'.$id;

        echo '<div class="ea-aat-wrapper"  data-titleanimation-settings = "'.htmlspecialchars( $TitleAnimationSettings, ENT_QUOTES, 'UTF-8' ).'">';
        echo '<'.esc_attr( $settings['tag'] ).' id ='.esc_attr( $id ).' data-title-id = '.esc_attr( $id ).' class ="ea-aat-title'.esc_attr( $settings['text_direction'] ).'" aat-background-text = "'.esc_attr( $settings['background_text'] ).'" >';
                self::before_text(); 
                self::animation_text();
                self::after_text();
            
        echo '</'.esc_attr( $settings['tag'] ).'>';
	}
        
}