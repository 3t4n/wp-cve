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
class TTA_Helper { 

    public static function should_load_button() {
        $should_load_button = false;
        // is_home() || is_archive() || is_front_page() || is_category()
        if(\is_single() || is_singular() ){
            $should_load_button = true;
        }
        
        $settings = self::tts_get_settings('settings');
        if(!isset($settings['tta__settings_allow_listening_for_post_types']) 
        || count($settings['tta__settings_allow_listening_for_post_types']) === 0
        || !is_array($settings['tta__settings_allow_listening_for_post_types'])
        || !in_array(self::tts_post_type(), $settings['tta__settings_allow_listening_for_post_types'])
        ) {
            $should_load_button = false;
        }

        return apply_filters('tta_should_load_button', $should_load_button);
    }
    

    /**
     * Get post type
     * 
     * @see 
     */

    public static function tts_post_type() {
        global  $post;
        return isset($post->post_type) ? $post->post_type : '';
    }


        /**
     * 
     */
    public static function remove_shortcodes( $content ) {
		if ( $content === '' ) {
			return '';
		}

		// Covers all kinds of shortcodes
		$expression = '/\[\/*[a-zA-Z1-90_| -=\'"\{\}]*\/*\]/m';

		$content = preg_replace( $expression, '', $content );

		return strip_shortcodes( $content );
	}


    /**
	 * Extends wp_strip_all_tags to fix WP_Error object passing issue
	 *
	 * @param string | WP_Error $string
	 *
	 * @return string
	 * @since 4.5.10
	 * */
	public static function tts_strip_all_tags( $string ) {

		if ( $string instanceof \WP_Error ) {
			return '';
		}

		return wp_strip_all_tags( $string );
	}


    
    /**
	 * Get Output
	 *
	 * @param $output
	 * @param $outputTypes
	 *
	 * @return array|false|int|mixed|string|string[]|null
	 */
	public static function sazitize_content( $output, $should_clean_content = false, $content_type = '' ) {

        if($should_clean_content) {
            $output = \tta_clean_content($output);
            if($content_type === 'title') {
                $output = \tta_should_add_dilimiter($output, \apply_filters('tts_sentence_delimiter', '. '));
            }
        }
        // Format Output According to output type
        $output = self::tts_strip_all_tags( html_entity_decode( $output ) );

        // Remove ShortCodes
        $output = self::remove_shortcodes( $output );
        
        /**
         * Remove the url
         * @see https://gist.github.com/madeinnordeste/e071857148084da94891
         */
        $output = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $output);
		

		return $output;
	}

    public static function  get_compatible_plugins_data() {
        $compatible_plugins_data = [];
        $datas = \apply_filters('tts_pro_plugins_data', [
                'gtranslate/gtranslate.php' => [
                    'type' => 'class',
                    'data' => [ 'gt_options', 'gt_languages','gt_switcher_wrapper', 'gt_selector', ],//  'gt_selector',], // 'gt_white_content', 'gtranslate_wrapper'],
                    'plugin' => 'gtranslate' 
                ],
                'sitepress-multilingual-cms/sitepress.php' => [
                    'type' => 'class',
                    'data' => [ ],
                    'plugin' => 'sitepress' 
                ],
        ]);

        if(!function_exists('is_plugin_active')) {
            require_once \ABSPATH . 'wp-admin/includes/pluin.php';
        }

        foreach ( $datas as $plugin_name =>  $data ){
                if(is_plugin_active($plugin_name )) {
                    $compatible_plugins_data[ $plugin_name ] = $data;
                }
         }

        return \apply_filters('tts_pro_compatible_plugins_data', $compatible_plugins_data, \get_plugins());
    }

    public static function get_language_code_from_url($url) {
        $arr = explode('lang', $url);
        $language_code = end($arr);
        $language_code = str_replace('__', '',$language_code);
        $language_code = explode('.', $language_code)[0];
        $language_code = \str_replace('_', '-', $language_code);

        return $language_code;
    }


    public static function tts_site_language($plugin_all_settings) {
        // TODO: Match with multilinguage UI and default language.
        $default_language = $plugin_all_settings['listening']['tta__listening_lang'];
        // $default_language = str_replace(['-', ' '], '_', $default_language);
        $default_language = strtolower($default_language);

        return apply_filters('tts_site_language', $default_language);
    }

    public static function tts_file_name($title, $selectedLang) {

        if (!$title) {
        $title = 'Demo Content';
        }

        $title .= "__lang__" . strtolower($selectedLang);
        $title = str_replace([' ', '-'], '_', $title);
        $title = preg_replace("/[^a-z0-9_-]/i", "", $title);

        return $title;
    }

    public static function handle_old_url($post, $new_urls, $old_url) {
        $associative_urls = [];
        if(isset($new_urls[0])) {
            $associative_urls = $new_urls[0];
        }else{
            $associative_urls = $new_urls;
        }

        if($old_url) {
            $language_code = self::get_language_code_from_url($old_url);
            if(!array_key_exists($language_code, $associative_urls)) {
                $associative_urls[$language_code] = $old_url;
                update_post_meta($post->ID, 'tts_mp3_file_urls', $associative_urls);
                delete_post_meta($post->ID, 'tts_mp3_file_url');
            }
        }

        return $associative_urls;

    }

    public static function tts_get_settings($identifier = '') {  
   
        $all_settings_data = [];
        $cached_settings = get_transient('tts_all_settings');
        if(!$cached_settings) {
            $all_settings = [
                'tta_listening_settings' => 'listening',
                'tta_settings_data' => 'settings',
                'tta_record_settings' => 'recording',
                'tta_customize_settings' => 'customize',
            ];
            
            foreach($all_settings as $settings_key => $identifier) {
                $settings = get_option($settings_key);
                $settings = ! $settings ? false : (array) $settings ;
                $all_settings_data[$identifier] = $settings;
            }

            set_transient('tts_all_settings', $all_settings_data);

        }else{
            $all_settings_data = $cached_settings;
        }

        if($identifier) {
            $specified_identifier_data = isset($all_settings_data[$identifier]) ? $all_settings_data[$identifier] : $all_settings_data;
            $all_settings_data = $specified_identifier_data;
        }
        global $post;

        return \apply_filters('tts_get_settings', $all_settings_data, $post);
    }

    public static function get_mp3_file_urls($post = '') {
        if(!$post) {
            global $post;
        }

        $mp3_file_urls = get_post_meta($post->ID, 'tts_mp3_file_urls');
        $old_url = get_post_meta($post->ID, 'tts_mp3_file_url', true);

        if(is_pro_active() && $old_url) {
            $mp3_file_urls = self::handle_old_url($post, $mp3_file_urls, $old_url);
        }

        if(isset($mp3_file_urls[0])) {
            $mp3_file_urls = $mp3_file_urls[0];
        }

        return \apply_filters('tts_mp3_file_urls', $mp3_file_urls, $post);
    }

    /**
     * Is plugin active
     */
    public static function is_pro_active() {

        if(!function_exists('is_plugin_active') ){
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $status = is_plugin_active('text-to-speech-pro/text-to-audio-pro.php');

        if($status) return true;

        $status = is_plugin_active('text-to-speech-pro-premium/text-to-audio-pro.php');

        if($status) return true;
        
        
        return is_plugin_active('text-to-audio-pro/text-to-audio-pro.php');
    }

    public static function is_audio_folder_writable() {
        $upload_dir             = wp_upload_dir();
        $base_dir               = $upload_dir['basedir'];

        if ( is_writable( $base_dir ) ) {
            return true;
        }
        return false;
    }


}