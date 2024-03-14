<?php

use Elementor\Plugin;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
final class LiveCopyPasteSettings {
    public function __construct() {
        add_action('init', [$this, 'live_copy_paste_callback']);
    }

    public function live_copy_paste_settings($setting_id) {
        global $live_copy_paste_settings;
        $return = '';
        if (!isset($live_copy_paste_settings['kit_settings'])) {
            $kit = Plugin::$instance->documents->get(Plugin::$instance->kits_manager->get_active_id(), false);
            $live_copy_paste_settings['kit_settings'] = $kit->get_settings();
        }

        if (isset($live_copy_paste_settings['kit_settings'][$setting_id])) {
            $return = $live_copy_paste_settings['kit_settings'][$setting_id];
        }

        return apply_filters('live_copy_paste_settings' . $setting_id, $return);
    }

    public function live_copy_paste_callback() {
        if (($this->live_copy_paste_settings('enable_magic_copy') == 'yes') && $this->live_copy_paste_settings('magic_button_specific_section') == 'yes') {
            update_option('lcp_enable_magic_copy_btn_specific_section', true);
        } else {
            update_option('lcp_enable_magic_copy_btn_specific_section', false);
        }

        if (($this->live_copy_paste_settings('enable_magic_copy') == 'yes') && $this->live_copy_paste_settings('magic_button_login_users') == 'yes') {
            update_option('lcp_enable_magic_copy_btn_login_user', true);
        } else {
            update_option('lcp_enable_magic_copy_btn_login_user', false);
        }

        if (($this->live_copy_paste_settings('enable_magic_copy') == 'yes')) {
            update_option('lcp_enable_magic_copy_btn', true);
        } else {
            update_option('lcp_enable_magic_copy_btn', false);
        }

        if ($this->live_copy_paste_settings('enable_copy_paste_btn') == 'yes') {
            update_option('lcp_enable_copy_paste_btn', true);
        } else {
            update_option('lcp_enable_copy_paste_btn', false);
        }
        // if ($this->live_copy_paste_settings('enable_duplicator') == 'yes') {
        //     update_option('lcp_enable_duplicator', true);
        // } else {
        //     update_option('lcp_enable_duplicator', false);
        // }
    }
}
new LiveCopyPasteSettings();
