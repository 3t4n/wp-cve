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

/**
 * *************************************************************************
 * This template needs optimaztion (Refactor -> algorithm)
 * *************************************************************************
 * Dark_Inline_CSS class
 */
class Dark_Inline_CSS
{

    /**
     * Dark_Inline_CSS constructor
     * @since  1.0.0
     * @return void
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueueStyle']);
        add_action('login_enqueue_scripts', [__CLASS__, 'enqueueStyle']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'adminEnqueueStyle']);
    }

    /**
     * Admin enqueue style
     *
     * @since  1.0.0
     * @return void
     */
    public static function adminEnqueueStyle()
    {

        $backendDarkmode = Helper::getOptionData('backend_darkmode');
        if (!$backendDarkmode) {
            return;
        }
        wp_enqueue_style('darkluplite-dark-style', DARKLUPLITE_DIR_URL . 'assets/css/dark-style.css');
        self::adminAddStyle();
    }

    /**
     * Enqueue style
     *
     * @since  1.0.0
     * @return void
     */
    public static function enqueueStyle()
    {
        wp_enqueue_style('darkluplite-dark-style', DARKLUPLITE_DIR_URL . 'assets/css/dark-style.css');
        self::addStyle();
    }
    /**
     * Add Front-End inline css
     *
     * @since  1.0.0
     * @return void
     */
    public static function addStyle()
    {
        $css = self::inlineCss();
        $js = self::inlineJs();
        wp_add_inline_style('darkluplite-dark-style', $css);
        // wp_add_inline_script('jquery', $js);
        $colorMode = 'darklup_dynamic';
        // $getMode = Helper::getOptionData('full_color_settings');
        $getMode = Helper::getOptionData('color_modes');
        if($getMode !== 'darklup_dynamic'){
            $colorMode = 'darklup_presets';
        }
        wp_add_inline_script($colorMode, $js, 'before');
        // wp_add_inline_script('darklup_dynamic', $js, 'before');
    }
    /**
     *
     *
     * @since  1.0.0
     * @return void
     */
    public static function adminAddStyle()
    {
        $css = self::commonInlineCss('admin_');
        $js = self::inlineAdminJs();
        wp_add_inline_style('darkluplite-dark-style', $css);
        wp_add_inline_script('jquery', $js);
    }
    public static function inlineAdminJs()
    {
        $inline_js = "";
        $backendDarkModeSettingsEnabled = Helper::getOptionData('backend_darkmode');
        $enableKeyboardShortcut = Helper::getOptionData('keyboard_shortcut');

        if ($backendDarkModeSettingsEnabled) {
            $inline_js .= "let isBackendDarkLiteModeSettingsEnabled = true;";
        } else {
            $inline_js .= "let isBackendDarkLiteModeSettingsEnabled = false;";
        }

        if ($enableKeyboardShortcut) {
            $inline_js .= "let isKeyShortDarkModeEnabled = true;";
        } else {
            $inline_js .= "let isKeyShortDarkModeEnabled = false;";
        }

        return $inline_js;
    }
    public static function inlineJs()
    {
        $enableOS = Helper::getOptionData('enable_os_switcher');
        $enableKeyboardShortcut = Helper::getOptionData('keyboard_shortcut');
        $defaultDarkMode  = Helper::getOptionData('default_dark_mode');
        $inline_js = "";
        
        if ($enableOS) {
            $inline_js .= "let isOSDarkModeEnabled = true;";
        } else {
            $inline_js .= "let isOSDarkModeEnabled = false;";
        }

        if ($enableKeyboardShortcut) {
            $inline_js .= "let isKeyShortDarkModeEnabled = true;";
        } else {
            $inline_js .= "let isKeyShortDarkModeEnabled = false;";
        }

        if( $defaultDarkMode ) {
            $inline_js .= "let isDefaultDarkModeEnabled = true;";
        }else{
            $inline_js .= "let isDefaultDarkModeEnabled = false;";
        }

        return $inline_js;
    }
    public static function inlineCss()
    {

        //  Switch Position In Desktop
        $marginUnit = 'px';
        $switchMarginUnit 	  = Helper::getOptionData('switch_margin_unit');
        if($switchMarginUnit == 'percent') $marginUnit = '%';
        $topMargin = Helper::getOptionData('switch_top_margin').$marginUnit;
        $bottomMargin = Helper::getOptionData('switch_bottom_margin').$marginUnit;
        $leftMargin = Helper::getOptionData('switch_left_margin').$marginUnit;
        $rightMargin = Helper::getOptionData('switch_right_margin').$marginUnit;

        // Switch button width and height for desktop
        $switch_size_base_width = Helper::getOptionData('switch_size_base_width');
        $switch_size_base_height = Helper::getOptionData('switch_size_base_height');
        $switch_size_base_width =  empty( $switch_size_base_width) ? "100" : $switch_size_base_width;
        $switch_size_base_height =  empty( $switch_size_base_height) ? "40" : $switch_size_base_height;
        // Switch button Icon width and height for desktop
        $switch_icon_size_width_height = '';
        $switch_size_icon_width = Helper::getOptionData('floating_switch_icon_width');
        $switch_size_icon_height = Helper::getOptionData('floating_switch_icon_height');
        if(!empty( $switch_size_icon_width)){
            $switch_icon_size_width_height .= "--darkluplite-btn-icon-width: {$switch_size_icon_width}px;";
        }
        if(!empty( $switch_size_icon_height)){
            $switch_icon_size_width_height .= "--darkluplite-btn-icon-height: {$switch_size_icon_height}px;";
        }
        
        $commonCss = self::commonInlineCss();
        
        $inlinecss = "
        $commonCss
        :root {
            --darkluplite-btn-height: {$switch_size_base_height}px;
            --darkluplite-btn-width: {$switch_size_base_width}px;
            {$switch_icon_size_width_height}
        }

		.darkluplite-desktop-switcher {
			top: {$topMargin} !important;
			bottom: {$bottomMargin} !important;
			left: {$leftMargin} !important;
			right: {$rightMargin} !important;
		}
		";
        return $inlinecss;
    }
    public static function PrevInlineCss()
    {

        //  Switch Position In Desktop
        $marginUnit = 'px';
        $switchMarginUnit 	  = Helper::getOptionData('switch_margin_unit');
        if($switchMarginUnit == 'percent') $marginUnit = '%';
        $topMargin = Helper::getOptionData('switch_top_margin').$marginUnit;
        $bottomMargin = Helper::getOptionData('switch_bottom_margin').$marginUnit;
        $leftMargin = Helper::getOptionData('switch_left_margin').$marginUnit;
        $rightMargin = Helper::getOptionData('switch_right_margin').$marginUnit;

        // Preset Color
        $colorPreset = Helper::getOptionData('color_preset');
        $presetColor = Color_Preset::getColorPreset($colorPreset);
        
        // Get user provided custom data
        $bgColor = esc_html($presetColor['background-color']);
        $customBg = Helper::getOptionData('custom_bg_color');
        $customBg = Helper::is_real_color($customBg);
        if($customBg) $bgColor = $customBg;
        
        $bgSecondaryColor = esc_html($presetColor['secondary_bg']);
        $customSecondaryBg = Helper::getOptionData('custom_secondary_bg_color');
        $customSecondaryBg = Helper::is_real_color($customSecondaryBg);
        if($customSecondaryBg) $bgSecondaryColor = $customSecondaryBg;
        
        $bgTertiary = esc_html($presetColor['tertiary_bg']);
        $customTertiaryBg = Helper::getOptionData('custom_tertiary_bg_color');
        $customTertiaryBg = Helper::is_real_color($customTertiaryBg);
        if($customTertiaryBg) $bgTertiary = $customTertiaryBg;
        
        $color = esc_html($presetColor['color']);
        $customColor = Helper::getOptionData('custom_text_color');
        $customColor = Helper::is_real_color($customColor);
        if($customColor) $color = $customColor;

        $anchorColor = esc_html($presetColor['anchor-color']);
        $anchorHoverColor = esc_html($presetColor['anchor-hover-color']);
        $inputBgColor = esc_html($presetColor['input-bg-color']);
        $borderColor = esc_html($presetColor['border-color']);
        $btnBgColor = esc_html($presetColor['btn-bg-color']);
        $btnColor = esc_html($presetColor['color']);
        $boxShadow = esc_html($presetColor['color']);

        $imgFilter = esc_html('brightness(85%)');
        $bgImgFilter = esc_html('brightness(90%) grayscale(5%)');
        $inlineSvgFilter = esc_html('brightness(90%) grayscale(5%) invert(90%)');

        // Switch button width and height for desktop
        $switch_size_base_width = Helper::getOptionData('switch_size_base_width');
        $switch_size_base_height = Helper::getOptionData('switch_size_base_height');
        $switch_size_base_width =  empty( $switch_size_base_width) ? "100" : $switch_size_base_width;
        $switch_size_base_height =  empty( $switch_size_base_height) ? "40" : $switch_size_base_height;
        // Switch button Icon width and height for desktop
        $switch_icon_size_width_height = '';
        $switch_size_icon_width = Helper::getOptionData('floating_switch_icon_width');
        $switch_size_icon_height = Helper::getOptionData('floating_switch_icon_height');
        if(!empty( $switch_size_icon_width)){
            $switch_icon_size_width_height .= "--darkluplite-btn-icon-width: {$switch_size_icon_width}px;";
        }
        if(!empty( $switch_size_icon_height)){
            $switch_icon_size_width_height .= "--darkluplite-btn-icon-height: {$switch_size_icon_height}px;";
        }


        $darklup_image_effects  = Helper::getOptionData('darkluplite_image_effects');
        $darklup_image_effects = !empty($darklup_image_effects) ? $darklup_image_effects : 'no';

        $imgGrayscale  = Helper::getOptionData('image_grayscale');
        $imgGrayscale  = !empty($imgGrayscale) ? $imgGrayscale : '0';
        $imgBrightness = Helper::getOptionData('image_brightness');
        $imgBrightness = !empty($imgBrightness) ? $imgBrightness : '1';
        $imgContrast   = Helper::getOptionData('image_contrast');
        $imgContrast   = !empty($imgContrast ) ? $imgContrast  : '1';
        $imgOpacity    = Helper::getOptionData('image_opacity');
        $imgOpacity    = !empty($imgOpacity ) ? $imgOpacity  : '1';
        $imgSepia      = Helper::getOptionData('image_sepia');
        $imgSepia      = !empty($imgSepia ) ? $imgSepia  : '0';

        $inlinecss = "
        :root {
            --wpc-darkluplite--bg: $bgColor;
            --wpc-darkluplite--secondary-bg: $bgSecondaryColor;
            --wpc-darkluplite--tertiary-bg: $bgTertiary;
            --wpc-darkluplite--text-color: $color;
            --wpc-darkluplite--link-color: $anchorColor;
            --wpc-darkluplite--link-hover-color: $anchorHoverColor;
            --wpc-darkluplite--input-bg: $inputBgColor;
            --wpc-darkluplite--input-text-color: $color;
            --wpc-darkluplite--input-placeholder-color: $color;
            --wpc-darkluplite--border-color: $borderColor;
            --wpc-darkluplite--btn-bg: $btnBgColor;
            --wpc-darkluplite--btn-text-color: $btnColor;
            --wpc-darkluplite--img-filter: $imgFilter;
            --wpc-darkluplite--bg-img-filter: $bgImgFilter;
            --wpc-darkluplite--svg-filter: $inlineSvgFilter;
            --wpc-darkluplite--box-shadow: $boxShadow;
            --darkluplite-btn-width: {$switch_size_base_width}px;
            --darkluplite-btn-height: {$switch_size_base_height}px;
            --darkluplite-dynamic-color: rgb(237 237 237);
            --darkluplite-dynamic-border-color: #74747469;
            --darkluplite-dynamic-sudo-color: #ddd;
            --darkluplite-dynamic-link-color: rgb(237 237 237);
            --darkluplite-dynamic-link-hover-color: rgb(237 237 237);
            --darkluplite-dynamic-btn-text-color: rgb(237 237 237);
            {$switch_icon_size_width_height}
        }

		.darkluplite-desktop-switcher {
			top: {$topMargin} !important;
			bottom: {$bottomMargin} !important;
			left: {$leftMargin} !important;
			right: {$rightMargin} !important;
		}
		";

		if($darklup_image_effects == "yes"){
            $inlinecss .= "html.darkluplite-dark-mode-enabled img {
                                filter: grayscale({$imgGrayscale}) opacity({$imgOpacity}) sepia({$imgSepia}) brightness({$imgBrightness}) contrast({$imgContrast}) !important;
                            }";
        }

        return $inlinecss;
        // return $colorMode . ' Cute';
    }
    /**
     * Admin inline css
     *
     * @since  1.0.0
     * @return void
     */
    public static function PrevAdminInlineCss()
    {

        $colorPreset = Helper::getOptionData('admin_color_preset');
        $presetColor = Color_Preset::getColorPreset($colorPreset);
        
        $bgColor = esc_html($presetColor['background-color']);
        $customBg = Helper::getOptionData('admin_custom_bg_color');
        $customBg = Helper::is_real_color($customBg);
        if($customBg) $bgColor = $customBg;
        
        $customSecondaryBg = Helper::getOptionData('admin_custom_secondary_bg_color');
        $customSecondaryBg = Helper::is_real_color($customSecondaryBg);
        $bgSecondaryColor = esc_html($presetColor['secondary_bg']);
        if($customSecondaryBg) $bgSecondaryColor = $customSecondaryBg;
        
        $customTertiaryBg = Helper::getOptionData('admin_custom_tertiary_bg_color');
        $customTertiaryBg = Helper::is_real_color($customTertiaryBg);
        $bgTertiary = esc_html($presetColor['tertiary_bg']);
        if($customTertiaryBg) $bgTertiary = $customTertiaryBg;
        
        $customColor = Helper::getOptionData('admin_custom_text_color');
        $customColor = Helper::is_real_color($customColor);
        $color = esc_html($presetColor['color']);
        if($customColor) $color = $customColor;

        $anchorColor = esc_html($presetColor['anchor-color']);
        $anchorHoverColor = esc_html($presetColor['anchor-hover-color']);
        $inputBgColor = esc_html($presetColor['input-bg-color']);
        $borderColor = esc_html($presetColor['border-color']);
        $btnBgColor = esc_html($presetColor['btn-bg-color']);
        $btnColor = esc_html($presetColor['color']);
        $boxShadow = esc_html($presetColor['color']);

        $imgFilter = esc_html('brightness(85%)');
        $bgImgFilter = esc_html('brightness(90%) grayscale(5%)');
        $inlineSvgFilter = esc_html('brightness(90%) grayscale(5%) invert(90%)');

        $darklup_image_effects  = Helper::getOptionData('darkluplite_image_effects');
        $darklup_image_effects = !empty($darklup_image_effects) ? $darklup_image_effects : 'yes';

        $imgGrayscale  = Helper::getOptionData('image_grayscale');
        $imgGrayscale  = !empty($imgGrayscale) ? $imgGrayscale : '0';
        $imgBrightness = Helper::getOptionData('image_brightness');
        $imgBrightness = !empty($imgBrightness) ? $imgBrightness : '1';
        $imgContrast   = Helper::getOptionData('image_contrast');
        $imgContrast   = !empty($imgContrast ) ? $imgContrast  : '1';
        $imgOpacity    = Helper::getOptionData('image_opacity');
        $imgOpacity    = !empty($imgOpacity ) ? $imgOpacity  : '1';
        $imgSepia      = Helper::getOptionData('image_sepia');
        $imgSepia      = !empty($imgSepia ) ? $imgSepia  : '0';


        $inlinecss = "

        :root {
            --wpc-darkluplite--bg: $bgColor;
            --wpc-darkluplite--secondary-bg: $bgSecondaryColor;
            --wpc-darkluplite--tertiary-bg: $bgTertiary;
            --wpc-darkluplite--text-color: $color;
            --wpc-darkluplite--link-color: $anchorColor;
            --wpc-darkluplite--link-hover-color: $anchorHoverColor;
            --wpc-darkluplite--input-bg: $inputBgColor;
            --wpc-darkluplite--input-text-color: $color;
            --wpc-darkluplite--input-placeholder-color: $color;
            --wpc-darkluplite--border-color: $borderColor;
            --wpc-darkluplite--btn-bg: $btnBgColor;
            --wpc-darkluplite--btn-text-color: $btnColor;
            --wpc-darkluplite--img-filter: $imgFilter;
            --wpc-darkluplite--bg-img-filter: $bgImgFilter;
            --wpc-darkluplite--svg-filter: $inlineSvgFilter;
            --wpc-darkluplite--box-shadow: $boxShadow;
            --darkluplite-dynamic-color: rgb(237 237 237);
            --darkluplite-dynamic-border-color: #74747469;
            --darkluplite-dynamic-sudo-color: #ddd;
            --darkluplite-dynamic-link-color: rgb(237 237 237);
            --darkluplite-dynamic-link-hover-color: rgb(237 237 237);
            --darkluplite-dynamic-btn-text-color: rgb(237 237 237);
        }

		";
		if($darklup_image_effects == "yes"){
            $inlinecss .= ".darkluplite-image-effects-preview img {
                                filter: grayscale({$imgGrayscale}) opacity({$imgOpacity}) sepia({$imgSepia}) brightness({$imgBrightness}) contrast({$imgContrast}) !important;
                            }";
        }
        return $inlinecss;
    }
    public static function commonInlineCss($admin='')
    {
        $colorPreset = Helper::getOptionData($admin. 'color_preset');
        $presetColor = Color_Preset::getColorPreset($colorPreset);
        
        $bgColor = esc_html($presetColor['background-color']);
        $customBg = Helper::getOptionData($admin. 'custom_bg_color');
        $customBg = Helper::is_real_color($customBg);
        if($customBg) $bgColor = $customBg;
        
        $customSecondaryBg = Helper::getOptionData($admin. 'custom_secondary_bg_color');
        $customSecondaryBg = Helper::is_real_color($customSecondaryBg);
        $bgSecondaryColor = esc_html($presetColor['secondary_bg']);
        if($customSecondaryBg) $bgSecondaryColor = $customSecondaryBg;
        
        $customTertiaryBg = Helper::getOptionData($admin. 'custom_tertiary_bg_color');
        $customTertiaryBg = Helper::is_real_color($customTertiaryBg);
        $bgTertiary = esc_html($presetColor['tertiary_bg']);
        if($customTertiaryBg) $bgTertiary = $customTertiaryBg;
        
        $customColor = Helper::getOptionData($admin. 'custom_text_color');
        $customColor = Helper::is_real_color($customColor);
        $color = esc_html($presetColor['color']);
        if($customColor) $color = $customColor;

        $anchorColor = esc_html($presetColor['anchor-color']);
        $anchorHoverColor = esc_html($presetColor['anchor-hover-color']);
        $inputBgColor = esc_html($presetColor['input-bg-color']);
        $borderColor = esc_html($presetColor['border-color']);
        $btnBgColor = esc_html($presetColor['btn-bg-color']);
        $btnColor = esc_html($presetColor['color']);
        $boxShadow = esc_html($presetColor['color']);

        $imgFilter = esc_html('brightness(85%)');
        $bgImgFilter = esc_html('brightness(90%) grayscale(5%)');
        $inlineSvgFilter = esc_html('brightness(90%) grayscale(5%) invert(90%)');

        $darklup_image_effects  = Helper::getOptionData('darkluplite_image_effects');
        $darklup_image_effects = !empty($darklup_image_effects) ? $darklup_image_effects : 'yes';

        $imgGrayscale  = Helper::getOptionData('image_grayscale');
        $imgGrayscale  = !empty($imgGrayscale) ? $imgGrayscale : '0';
        $imgBrightness = Helper::getOptionData('image_brightness');
        $imgBrightness = !empty($imgBrightness) ? $imgBrightness : '1';
        $imgContrast   = Helper::getOptionData('image_contrast');
        $imgContrast   = !empty($imgContrast ) ? $imgContrast  : '1';
        $imgOpacity    = Helper::getOptionData('image_opacity');
        $imgOpacity    = !empty($imgOpacity ) ? $imgOpacity  : '1';
        $imgSepia      = Helper::getOptionData('image_sepia');
        $imgSepia      = !empty($imgSepia ) ? $imgSepia  : '0';

        $inlinecss = "
        :root {
            --wpc-darkluplite--bg: $bgColor;
            --wpc-darkluplite--secondary-bg: $bgSecondaryColor;
            --wpc-darkluplite--tertiary-bg: $bgTertiary;
            --wpc-darkluplite--text-color: $color;
            --wpc-darkluplite--link-color: $anchorColor;
            --wpc-darkluplite--link-hover-color: $anchorHoverColor;
            --wpc-darkluplite--input-bg: $inputBgColor;
            --wpc-darkluplite--input-text-color: $color;
            --wpc-darkluplite--input-placeholder-color: $color;
            --wpc-darkluplite--border-color: $borderColor;
            --wpc-darkluplite--btn-bg: $btnBgColor;
            --wpc-darkluplite--btn-text-color: $btnColor;
            --wpc-darkluplite--img-filter: $imgFilter;
            --wpc-darkluplite--bg-img-filter: $bgImgFilter;
            --wpc-darkluplite--svg-filter: $inlineSvgFilter;
            --wpc-darkluplite--box-shadow: $boxShadow;
            --darkluplite-dynamic-color: rgb(237 237 237);
            --darkluplite-dynamic-border-color: #74747469;
            --darkluplite-dynamic-sudo-color: #ddd;
            --darkluplite-dynamic-link-color: rgb(237 237 237);
            --darkluplite-dynamic-link-hover-color: rgb(237 237 237);
            --darkluplite-dynamic-btn-text-color: rgb(237 237 237);
        }

		";
		if($darklup_image_effects == "yes"){
            $inlinecss .= "html.darkluplite-dark-mode-enabled img, .darkluplite-image-effects-preview img {
                filter: grayscale({$imgGrayscale}) opacity({$imgOpacity}) sepia({$imgSepia}) brightness({$imgBrightness}) contrast({$imgContrast}) !important; }";
        }
        return $inlinecss;
    }
}

// Init Dark Inline CSS obj
new Dark_Inline_CSS();