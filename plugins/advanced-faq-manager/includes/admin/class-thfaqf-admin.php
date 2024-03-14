<?php

if(!defined('WPINC')){ die; }

if(!class_exists('THFAQF_Admin')):

class THFAQF_Admin{

    public function faq_general_setting_menu(){
        $this->faq_setting_menu();
    }

    public function enqueue_styles_and_scripts($hook_suffix){

        if( !in_array($hook_suffix, array('post.php', 'post-new.php','faq_page_thfaq-settings'))){
            return;
        }

        $screen = get_current_screen();
        if( is_object( $screen ) && !in_array( $screen->post_type, array('faq'))){
            return;
        }

        $deps = array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'wp-color-picker');
        wp_enqueue_media();
        wp_enqueue_editor();
        wp_enqueue_style ('thfaqf-admin-style', THFAQF_ASSETS_URL_ADMIN. 'css/thfaqf-admin.css');
        wp_enqueue_script('thfaqf-admin-script', THFAQF_ASSETS_URL_ADMIN. 'js/thfaqf-admin.js', $deps, THFAQF_VERSION, true);
        wp_enqueue_style('font-icon-picker-css', THFAQF_ASSETS_URL_ADMIN. 'css/font-awesome.min.css');
        wp_enqueue_script('font-icon-picker-js',THFAQF_ASSETS_URL_ADMIN.'js/fontawesome.min.js');
        wp_enqueue_style('thfaqf-admin-select2-css', THFAQF_ASSETS_URL_ADMIN. 'css/select2.min.css');
        wp_enqueue_script('thfaqf-admin-select2-js',THFAQF_ASSETS_URL_ADMIN.'js/select2.min.js',array('jquery'));
        $cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/css'));
        $script_var = array(
            'cm_settings'   => $cm_settings,
            'current_screen' => get_current_screen()->id,
        );
        wp_localize_script('thfaqf-admin-script', 'thfaqf_var', $script_var);
        wp_enqueue_script('wp-theme-plugin-editor');
        wp_enqueue_style('wp-codemirror');
    }

    public function admin_menu() {
        $page_title = __('Settings', 'advanced-faq-manager');
        $menu_title = __('Settings', 'advanced-faq-manager');
        $this->screen_id = add_submenu_page('edit.php?post_type=faq',  __('faq-Comments', 'advanced-faq-manager'),  __('FAQ Comments', 'advanced-faq-manager'), 'manage_options','edit.php?post_type=user-comment',null);
        $this->screen_id = add_submenu_page('edit.php?post_type=faq', $page_title, $menu_title, 'manage_options', 'thfaq-settings', array($this, 'output_settings'));
    }

    public function plugin_action_links($links) {
        $settings_link = '<a href="'.admin_url('edit.php?post_type=faq&page=thfaq-settings').'">'. __('Settings', 'advanced-faq-manager') .'</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    public function output_settings(){
        if(!current_user_can('manage_options')){
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }      
        $settings = THFAQF_Admin_Settings_General::instance();
        $settings->render_page();
    }
}

endif;  
        