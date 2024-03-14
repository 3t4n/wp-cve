<?php 
namespace Enteraddons\Widgets\Lottie_Animation\Traits;
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

trait Template_1 {
    
    public static function markup_style_1() {

        $settings   = self::getSettings();

        if( 'yes' == $settings['wrapper_link'] ) {
            echo self::linkOpen(); 
        }
        ?>
            <div class="ea-lottie-animation-container">

                <?php 
                    $json_file    = !empty( $settings['source_json']['url'] ) ? $settings['source_json']['url'] : '';
                    $external_url = !empty( $settings['source_external_link']['url'] ) ? $settings['source_external_link']['url'] : '';
                    $url          = ( $settings['source_file'] === 'media_file' ) ? $json_file : $external_url;
                    $speed        = !empty( $settings['speed'] ) ? $settings['speed'] : 1;
                    $count        = !empty( $settings['count'] ) ? $settings['count'] : '';
                    $intermission = !empty( $settings['intermission'] ) ? $settings['intermission'] : 1;
                    $loop         = !empty( $settings['loop'] ) && $settings['loop'] == 'loop' ? $settings['loop'] : '';
                    $hover        = !empty( $settings['hover'] ) && $settings['hover'] == 'hover' ? $settings['hover'] : '';
                    $controls     = !empty( $settings['controls'] ) && $settings['controls'] == 'controls' ? $settings['controls'] : '';
                    $autoplay     = !empty( $settings['autoplay'] ) && $settings['autoplay'] == 'autoplay' ? $settings['autoplay'] : '';
                    $mode         = !empty( $settings['mode'] ) && $settings['mode'] == 'bounce' ? $settings['mode'] : '';
                    $direction    = !empty( $settings['direction'] ) && $settings['direction'] == 1 ? 1 : -1;      
                    
                    $attr = implode(' ', [$loop, $hover, $controls, $autoplay]);
                ?>
            
                <lottie-player class="ea-lottie-animation" direction="<?php echo esc_attr( $direction ); ?>" src="<?php echo esc_url( $url ); ?>" mode="<?php echo esc_attr( $mode ); ?>" speed="<?php echo esc_attr( $speed ); ?>"  count="<?php echo esc_attr( $count ); ?>" intermission="<?php echo esc_attr( $intermission ); ?>" <?php echo esc_attr( $attr ); ?>></lottie-player>
            </div>
        <?php
        if( 'yes' == $settings['wrapper_link'] ) {
            echo self::linkClose();
        }
    }

}