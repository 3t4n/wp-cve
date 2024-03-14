<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
class Dracula_Enqueue
{
    private static  $instance = null ;
    public function __construct()
    {
        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 999 );
        add_action( 'login_enqueue_scripts', array( $this, 'frontend_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
    }
    
    public function frontend_scripts()
    {
        //		wp_enqueue_style('g-f', '');
        wp_register_style(
            'dracula-frontend',
            DRACULA_ASSETS . '/css/frontend.css',
            array(),
            DRACULA_VERSION
        );
        wp_style_add_data( 'dracula-frontend', 'rtl', 'replace' );
        $custom_css = $this->get_custom_css();
        wp_add_inline_style( 'dracula-frontend', $custom_css );
        // JS Scripts
        $deps = [
            'wp-element',
            'wp-components',
            'wp-i18n',
            'wp-util'
        ];
        wp_register_script(
            'dracula-dark-mode',
            DRACULA_ASSETS . '/js/dark-mode.js',
            [],
            DRACULA_VERSION
        );
        $deps[] = 'dracula-dark-mode';
        wp_register_script(
            'dracula-frontend',
            DRACULA_ASSETS . '/js/frontend.js',
            $deps,
            DRACULA_VERSION,
            true
        );
        wp_localize_script( 'dracula-frontend', 'dracula', $this->get_localize_data() );
        $is_active = dracula_get_settings( 'frontendDarkMode', true ) && !dracula_page_excluded();
        $is_active_reading_mode = dracula_get_settings( 'readingMode' ) && !dracula_reading_mode_excluded();
        // Live Edit Scripts
        
        if ( ddm_fs()->can_use_premium_code__premium_only() || dracula_is_elementor_editor_page() ) {
            $is_live_edit = current_user_can( 'manage_options' ) && ($is_active || !empty($_GET['dracula-live-edit']));
            if ( $is_live_edit || dracula_is_elementor_editor_page() ) {
                $this->enqueue_live_edit_scripts();
            }
        }
        
        // Frontend Scripts
        
        if ( $is_active || $is_active_reading_mode ) {
            wp_enqueue_style( 'dracula-frontend' );
            wp_enqueue_script( 'dracula-frontend' );
        }
    
    }
    
    public function admin_scripts( $hook )
    {
        // Check if user can access dracula pages
        if ( !dracula_is_user_dark_mode() && !dracula_is_block_editor_page() ) {
            return;
        }
        if ( !class_exists( 'Dracula_Admin' ) ) {
            require_once DRACULA_INCLUDES . '/class-admin.php';
        }
        $admin_pages = Dracula_Admin::instance()->get_admin_pages();
        // By default, style id startWith dracula- ignored by dark mode.
        // that why we need to add dracula_ prefix to the selector where we don't want to ignore dark mode
        wp_register_style(
            'dracula_sweetalert2',
            DRACULA_ASSETS . '/vendor/sweetalert2/sweetalert2.min.css',
            [],
            DRACULA_VERSION
        );
        // Ignore toggle styles from dark mode
        wp_register_style(
            'dracula-toggle',
            DRACULA_ASSETS . '/css/toggle.css',
            array(),
            DRACULA_VERSION
        );
        wp_enqueue_style(
            'dracula_admin',
            DRACULA_ASSETS . '/css/admin.css',
            array( 'wp-components', 'dracula-toggle', 'dracula_sweetalert2' ),
            DRACULA_VERSION
        );
        wp_style_add_data( 'dracula_admin', 'rtl', 'replace' );
        // Javascript Dependencies
        $deps = [
            'wp-components',
            'wp-element',
            'wp-editor',
            'wp-util'
        ];
        wp_register_script(
            'dracula-dark-mode',
            DRACULA_ASSETS . '/js/dark-mode.js',
            [],
            DRACULA_VERSION
        );
        $deps[] = 'dracula-dark-mode';
        // If block editor page and !active return
        $block_editor_dark_mode = dracula_get_settings( 'blockEditorDarkMode', true );
        if ( !$block_editor_dark_mode && dracula_is_block_editor_page() ) {
            $deps = array_diff( $deps, [ 'dracula-dark-mode' ] );
        }
        // Analytics page scripts
        
        if ( !empty($admin_pages['analytics']) && $admin_pages['analytics'] === $hook ) {
            wp_register_script(
                'dracula-chart',
                DRACULA_ASSETS . '/vendor/chart.js',
                array( 'jquery-ui-datepicker' ),
                DRACULA_VERSION,
                true
            );
            $deps[] = 'dracula-chart';
        }
        
        wp_register_script(
            'dracula-sweetalert2',
            DRACULA_ASSETS . '/vendor/sweetalert2/sweetalert2.min.js',
            [],
            DRACULA_VERSION,
            true
        );
        $deps[] = 'dracula-sweetalert2';
        // Settings Page
        
        if ( $admin_pages['dracula'] === $hook ) {
            wp_register_script(
                'dracula-gsap',
                DRACULA_ASSETS . '/vendor/gsap.js',
                [],
                '3.12.2',
                true
            );
            $deps[] = 'dracula-gsap';
        }
        
        // Enqueue media scripts for settings and toggle builder page
        if ( in_array( $hook, [ $admin_pages['settings'], $admin_pages['toggle_builder'] ] ) ) {
            wp_enqueue_media();
        }
        // CSS Editor Scripts
        
        if ( $admin_pages['dracula'] === $hook || dracula_is_block_editor_page() || dracula_is_classic_editor_page() ) {
            wp_enqueue_script( 'wp-theme-plugin-editor' );
            wp_enqueue_style( 'wp-codemirror' );
            $cm_settings = [
                'codeEditor' => wp_enqueue_code_editor( array(
                'type'  => 'text/css',
                'theme' => 'dracula',
            ) ),
            ];
            wp_localize_script( 'dracula-admin', 'cm_settings', $cm_settings );
        }
        
        wp_enqueue_script(
            'dracula-admin',
            DRACULA_ASSETS . '/js/admin.js',
            $deps,
            DRACULA_VERSION,
            true
        );
        wp_localize_script( 'dracula-admin', 'dracula', $this->get_localize_data( $hook ) );
    }
    
    public function enqueue_live_edit_scripts()
    {
        wp_enqueue_style(
            'dracula-live-edit',
            DRACULA_ASSETS . '/css/live-edit.css',
            [
            'dashicons',
            'wp-components',
            'dracula-frontend',
            'dracula_sweetalert2',
            'wp-codemirror'
        ],
            DRACULA_VERSION
        );
        wp_enqueue_media();
        wp_enqueue_script( 'dracula-sweetalert2' );
        wp_enqueue_script( 'dracula-gsap' );
        wp_enqueue_script( 'jquery-ui-draggable' );
        wp_enqueue_script( 'wp-theme-plugin-editor' );
        $cm_settings = [
            'codeEditor' => wp_enqueue_code_editor( array(
            'type'  => 'text/css',
            'theme' => 'dracula',
        ) ),
        ];
        wp_localize_script( 'dracula-frontend', 'cm_settings', $cm_settings );
        wp_enqueue_script(
            'dracula-live-edit',
            DRACULA_ASSETS . '/js/live-edit.js',
            [ 'wp-editor', 'wp-components', 'dracula-frontend' ],
            DRACULA_VERSION,
            true
        );
    }
    
    public function get_localize_data( $hook = false )
    {
        $data = array(
            'homeUrl'    => home_url(),
            'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
            'pluginUrl'  => DRACULA_URL,
            'settings'   => dracula_get_settings(),
            'isPro'      => ddm_fs()->can_use_premium_code__premium_only(),
            'upgradeUrl' => ddm_fs()->get_upgrade_url(),
            'nonce'      => wp_create_nonce( 'dracula' ),
        );
        
        if ( is_admin() ) {
            $admin_pages = Dracula_Admin::instance()->get_admin_pages();
            
            if ( $admin_pages['dracula'] === $hook ) {
                $data['menus'] = dracula_get_menus();
                $data['userRoles'] = dracula_get_user_roles();
                $data['excludeList'] = dracula_get_exclude_list();
                $data['excludeReadingList'] = dracula_get_exclude_reading_list();
                $data['excludeTaxList'] = dracula_get_exclude_taxonomy_list();
                $data['showReviewPopup'] = current_user_can( 'manage_options' ) && 'off' != get_option( 'dracula_rating_notice' ) && 'off' != get_transient( 'dracula_rating_notice_interval' );
            }
            
            // check current user role
            global  $current_user ;
            $data['currentUserRole'] = ( !empty($current_user) && !empty($current_user->roles) ? $current_user->roles[0] : '' );
        }
        
        $is_active = dracula_get_settings( 'frontendDarkMode', true ) && !dracula_page_excluded();
        $is_live_edit = current_user_can( 'manage_options' ) && ($is_active || !empty($_GET['dracula-live-edit']));
        $is_editor = dracula_is_block_editor_page() || dracula_is_classic_editor_page() || dracula_is_elementor_editor_page();
        if ( $is_live_edit || $is_editor ) {
            $data['menus'] = dracula_get_menus();
        }
        $page = ( !empty($_GET['page']) ? sanitize_key( $_GET['page'] ) : '' );
        if ( $page == 'dracula' ) {
            $data['postTypes'] = dracula_get_post_type_list();
        }
        return $data;
    }
    
    /**
     * Custom css
     */
    public function get_custom_css()
    {
        $custom_css = '';
        // General Button
        $buttonAlignment = dracula_get_settings( 'buttonAlignment', 'start' );
        $button_variable = sprintf( '--reading-mode-button-alignment: %s !important;', $buttonAlignment );
        $custom_css .= sprintf( '.reading-mode-buttons { %s }', $button_variable );
        // Reading Mode CSS Variable
        $readingModeBGColor = dracula_get_settings( 'readingModeBGColor', '#E3F5FF' );
        $readingModeBGDarker = dracula_color_brightness( $readingModeBGColor, -30 );
        $readingModeTextColor = dracula_get_settings( 'readingModeTextColor', '#2F80ED' );
        $dracula_variable = '';
        $dracula_variable .= ( !empty($readingModeBGColor) ? sprintf( '--reading-mode-bg-color: %s;', $readingModeBGColor ) : '' );
        $dracula_variable .= ( !empty($readingModeBGColor) ? sprintf( '--reading-mode-bg-darker: %s;', $readingModeBGDarker ) : '' );
        $dracula_variable .= ( !empty($readingModeTextColor) ? sprintf( '--reading-mode-text-color: %s;', $readingModeTextColor ) : '' );
        $custom_css .= sprintf( '.reading-mode-buttons .reading-mode-button { %s }', $dracula_variable );
        // Time CSS Variable
        $timeBGColor = dracula_get_settings( 'timeBGColor' );
        $timeBGDarker = dracula_color_brightness( $timeBGColor, -30 );
        $timeTextColor = dracula_get_settings( 'timeTextColor' );
        $time_variable = '';
        $time_variable .= ( !empty($timeBGColor) ? sprintf( '--time-bg-color: %s;', $timeBGColor ) : '' );
        $time_variable .= ( !empty($timeBGColor) ? sprintf( '--time-bg-darker: %s;', $timeBGDarker ) : '' );
        $time_variable .= ( !empty($timeTextColor) ? sprintf( '--time-text-color: %s;', $timeTextColor ) : '' );
        $custom_css .= sprintf( '.reading-mode-buttons .reading-mode-time { %s }', $time_variable );
        // Progressbar CSS Variable
        $progressbar_height = dracula_get_settings( 'progressbarHeight', '7' );
        $progressbar_color = dracula_get_settings( 'progressbarColor', '#7C7EE5' );
        $progressbar_variable = '';
        $progressbar_variable .= sprintf( '--reading-mode-progress-height: %spx;', $progressbar_height );
        $progressbar_variable .= sprintf( '--reading-mode-progress-color: %s;', $progressbar_color );
        $custom_css .= sprintf( '.reading-mode-progress { %s }', $progressbar_variable );
        // Image Settings
        $invert_images = dracula_get_settings( 'invertImages', false );
        $low_brightness = dracula_get_settings( 'lowBrightnessImages', false );
        $gray_scale = dracula_get_settings( 'grayscaleImages', false );
        
        if ( $invert_images || $low_brightness || $gray_scale ) {
            $custom_css .= 'html[data-dracula-scheme="dark"] img:not(.dracula-toggle *, .dracula-ignore, .dracula-ignore * , .elementor-background-overlay, .elementor-element-overlay, .elementor-button-link, .elementor-button-link *, .elementor-widget-spacer, .elementor-widget-spacer *, .wp-block-button__link, .wp-block-button__link *){';
            $filter_css = '';
            
            if ( $invert_images ) {
                $invert_images_level = dracula_get_settings( 'invertImagesLevel', 80 ) / 100;
                $filter_css .= sprintf( 'invert(%s) ', $invert_images_level );
            }
            
            
            if ( $low_brightness ) {
                $low_brightness_level = dracula_get_settings( 'lowBrightnessLevel', 80 ) / 100;
                $filter_css .= sprintf( 'brightness(%s) ', $low_brightness_level );
            }
            
            
            if ( $gray_scale ) {
                $gray_scale_level = dracula_get_settings( 'grayscaleImagesLevel', 80 ) / 100;
                $filter_css .= sprintf( 'grayscale(%s) ', $gray_scale_level );
            }
            
            $custom_css .= sprintf( 'filter: %s; }', $filter_css );
            $custom_css .= '}';
        }
        
        // Video Settings
        $video_low_brightness = dracula_get_settings( 'lowBrightnessVideos', false );
        $video_gray_scale = dracula_get_settings( 'grayscaleVideos', false );
        
        if ( $video_low_brightness || $video_gray_scale ) {
            $custom_css .= 'html[data-dracula-scheme="dark"] video:not(.dracula-toggle *, .dracula-ignore, .dracula-ignore * ),';
            $custom_css .= 'html[data-dracula-scheme="dark"] iframe[src*="youtube.com"],';
            $custom_css .= 'html[data-dracula-scheme="dark"] iframe[src*="vimeo.com"],';
            $custom_css .= 'html[data-dracula-scheme="dark"] iframe[src*="dailymotion.com"]{';
            $filter_css = '';
            
            if ( $video_low_brightness ) {
                $video_low_brightness_level = dracula_get_settings( 'videoBrightnessLevel', 80 ) / 100;
                $filter_css .= sprintf( 'brightness(%s) ', $video_low_brightness_level );
            }
            
            
            if ( $video_gray_scale ) {
                $video_gray_scale_level = dracula_get_settings( 'grayscaleVideosLevel', 80 ) / 100;
                $filter_css .= sprintf( 'grayscale(%s) ', $video_gray_scale_level );
            }
            
            $custom_css .= sprintf( 'filter: %s; }', $filter_css );
            $custom_css .= '}';
        }
        
        //Menu toggle size css
        $menu_toggle_size = dracula_get_settings( 'menuToggleSize', 'normal' );
        $menu_toggle_selector = '.dracula-toggle-wrap.menu-item .dracula-toggle';
        if ( in_array( $menu_toggle_size, [ 'small', 'large' ] ) ) {
            $custom_css .= sprintf( '%s{ --toggle-scale: %s; }', $menu_toggle_selector, ( 'small' == $menu_toggle_size ? '.8' : '1.5' ) );
        }
        // Toggle size css
        $toggle_size = dracula_get_settings( 'toggleSize', 'normal' );
        $toggle_selector = '.dracula-toggle-wrap .dracula-toggle';
        if ( in_array( $toggle_size, [ 'small', 'large' ] ) ) {
            $custom_css .= sprintf( '%s{ --toggle-scale: %s; }', $toggle_selector, ( 'small' == $toggle_size ? '.8' : '1.5' ) );
        }
        return $custom_css;
    }
    
    public static function instance()
    {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}
Dracula_Enqueue::instance();