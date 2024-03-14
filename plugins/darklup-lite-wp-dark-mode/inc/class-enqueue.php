<?php

namespace DarklupLite;

/**
 *
 * @package    DarklupLite - WP Dark Mode
 * @version    1.0.0
 * @author
 * @Websites:
 *
 */

if (!defined('ABSPATH')) {
    die(DARKLUPLITE_ALERT_MSG);
}

/**
 * This is DarklupLite_Enqueue class
 */
if (!class_exists('DarklupLite_Enqueue')) {

    class DarklupLite_Enqueue
    {

        /**
         * DarklupLite_Enqueue constructor
         *
         * @since  1.0.0
         * @return void
         */
        public function __construct()
        {

            if (\DarklupLite\Helper::getOptionData('frontend_darkmode') == 'yes') {
                add_action('wp_enqueue_scripts', array($this, 'frontendEnqueueScripts'));
                add_action('login_enqueue_scripts', array($this, 'frontendEnqueueScripts'), 10);
                add_action('login_enqueue_scripts', array($this, 'loginEnqueueScripts'), 10);
                // add_filter('script_loader_tag', array($this, 'deferScriptInHead'), 10, 3);
            }
        }
        /**
         * Front-End enqueue scripts
         *
         * @since  1.0.0
         * @return void
         */
        public function frontendEnqueueScripts()
        {

            wp_enqueue_style('darkluplite-switch', DARKLUPLITE_DIR_URL . 'assets/css/darkluplite-switch.css', array(), DARKLUPLITE_VERSION, false);

            /********************
            Js Enqueue
            ********************/

            $colorMode = 'darklup_dynamic';
            // $getMode = 'darklup_presets';
            $getMode = Helper::getOptionData('color_modes');
            
            if($getMode !== 'darklup_dynamic'){
                $colorMode = 'darklup_presets';
                $this->addDarklupJSWithDynamicVersion('darklup_presets', $src = 'assets/es-js/presets.js', $dep = NULL, $js_footer = false);
                wp_enqueue_style('darkluplite-variables', DARKLUPLITE_DIR_URL . 'assets/css/darkluplite-variables.css', array(), DARKLUPLITE_VERSION, false);
            }else{
                $this->addDarklupJSWithDynamicVersion();
                wp_enqueue_style('darkluplite-dynamic', DARKLUPLITE_DIR_URL . 'assets/css/darkluplite-dynamic.css', array(), DARKLUPLITE_VERSION, false);
            }
            
            // Localize Variables
            $frontObj = Helper::getFrontendObject();
			wp_localize_script( $colorMode, 'frontendObject', $frontObj);
            // $DarklupJs = $this->getDarklupJs();
            $DarklupJs = Helper::getDarklupJs();
            wp_localize_script($colorMode, 'DarklupJs', $DarklupJs);
            
        }
        public function loginEnqueueScripts()
        {
            wp_enqueue_style('darkluplite-login', DARKLUPLITE_DIR_URL . 'assets/css/darkluplite-login.css', array(), DARKLUPLITE_VERSION, false);
        }
        public function getDarklupJs()
        {
            $colorPreset = Helper::getOptionData('color_preset');
            $presetColor = Color_Preset::getColorPreset($colorPreset);

            $customBg = Helper::getOptionData('custom_bg_color');
            $customBg = Helper::is_real_color($customBg);
    
            // Custom colors
            $customSecondaryBg = Helper::getOptionData('custom_secondary_bg_color');
            $customSecondaryBg = Helper::is_real_color($customSecondaryBg);
    
            $customTertiaryBg = Helper::getOptionData('custom_tertiary_bg_color');
            $customTertiaryBg = Helper::is_real_color($customTertiaryBg);
    
            $bgColor = esc_html($presetColor['background-color']);
            if($customBg) $bgColor = $customBg;
            $bgColor = Helper::hex_to_color($bgColor);

            $bgSecondaryColor = esc_html($presetColor['secondary_bg']);
            if($customSecondaryBg) $bgSecondaryColor = $customSecondaryBg;
            $bgSecondaryColor = Helper::hex_to_color($bgSecondaryColor);

            $bgTertiary = esc_html($presetColor['tertiary_bg']);
            if($customTertiaryBg) $bgTertiary = $customTertiaryBg;
            $bgTertiary = Helper::hex_to_color($bgTertiary);

            $ifBgOverlay  = Helper::getOptionData('apply_bg_overlay');
			$darklup_js = [
                'primary_bg' => $bgColor,
                'secondary_bg' => $bgSecondaryColor,
                'tertiary_bg' => $bgTertiary,
                'bg_image_dark_opacity' => '0.5',
				'exclude_element' => '',
				'apply_bg_overlay' => $ifBgOverlay,
				'exclude_bg_overlay' => '',
            ];
            return $darklup_js;
        }
        public function deferScriptInHead($tag, $handle)
        {
            if (!is_admin()) {
                if ($handle == 'darklup-lite' || $handle == 'darklup-dynamic') {
                    $tag = str_replace('></script>', ' defer></script>', $tag);
                }
            }
            return $tag;
        }
        public static function addDarklupJSWithDynamicVersion($handle = 'darklup_dynamic', $src = 'assets/es-js/index.js', $dep = [], $js_footer = false)
        {
            $dirFull = DARKLUPLITE_DIR_PATH . $src;
            $uriFull = DARKLUPLITE_DIR_URL . $src;
            $version = date("3.0.ymdGis", filemtime( $dirFull ));
            wp_enqueue_script( $handle, $uriFull, $dep , $version , $js_footer );	
        }
    }

    // Init DarklupLite_Enqueue
    $obj = new DarklupLite_Enqueue();
}