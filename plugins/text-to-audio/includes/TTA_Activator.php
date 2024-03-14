<?php
namespace TTA;

/**
 * Fired during plugin activation
 *
 * @link       http://azizulhasan.com
 * @since      1.0.0
 *
 * @package    TTA
 * @subpackage TTA/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    TTA
 * @subpackage TTA/includes
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 */
class TTA_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate($renew_all_settings = false) {
        /**
         * Customization settings.
         */
        if( $renew_all_settings || !get_option('tta_customize_settings')){
            update_option('tta_customize_settings', array
            (
                "backgroundColor" => "#184c53",
                "color" => "#ffffff",
                "width" => "100",
                'custom_css' => '',
                'tta_play_btn_shortcode' => '[tta_listen_btn]',
            ));

        }
        
        /**
         * Text To Audio settings.
         */
        if( $renew_all_settings || !get_option('tta_settings_data')){
            update_option('tta_settings_data', array
                (
                    'tta__settings_enable_button_add'=> true,
                    "tta__settings_allow_listening_for_post_types" => ['post'],
                    "tta__settings_display_btn_icon" => '',
            ));
        }



        /**
         * Listening settings.
         */
        if( $renew_all_settings || !get_option('tta_listening_settings')){
                    update_option('tta_listening_settings', array
            (
                "tta__listening_voice" => "Microsoft Mark - English (United States)",
                "tta__listening_pitch" => 1,
                "tta__listening_rate" => 1,
                "tta__listening_volume" => 1,
                "tta__listening_lang" => "en-US",
            ));
        }



        /**
         * Recording settings.
         */
        if( $renew_all_settings || !get_option('tta_record_settings')){
             update_option('tta_record_settings', array
            (
                "is_record_continously" => true,
                "tta__recording__lang" => "en-US",
                "tta__sentence_delimiter" => ".",
            ));
        }



        // Button listen text.
        $listen_text =  __( "Listen", 'text-to-audio' ) ;
        $pause_text =  __( 'Pause', 'text-to-audio' ) ;
        $resume_text =  __( 'Resume', 'text-to-audio' ) ;
        $replay_text =  __( 'Replay', 'text-to-audio' ) ;
        $start_text =  __( 'Start', 'text-to-audio' ) ;
        $stop_text = __( 'Stop', 'text-to-audio' ) ;

        if( $renew_all_settings || !get_option('tta__button_text_arr')){
            update_option( 'tta__button_text_arr', [
                'listen_text' => $listen_text,
                'pause_text' => $pause_text,
                'resume_text' => $resume_text,
                'replay_text' => $replay_text,
                'start_text' => $start_text,
                'stop_text' => $stop_text,
            ]);
        }

        if(get_transient('tts_all_settings')) {
            \delete_transient('tts_all_settings');
        }

    }

}
