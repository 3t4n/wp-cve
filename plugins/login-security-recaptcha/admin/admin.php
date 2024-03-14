<?php
defined( 'ABSPATH' ) || die();

require_once STLSR_PLUGIN_DIR_PATH . 'admin/inc/class-stlsr-admin-menu.php';
require_once STLSR_PLUGIN_DIR_PATH . 'admin/inc/class-stlsr-setting.php';

add_filter( 'plugin_action_links_' . STLSR_PLUGIN_BASENAME, array( 'STLSR_Setting', 'add_action_links' ) );

add_action( 'admin_notices', array( 'STLSR_Setting', 'redirect' ) );

add_action( 'init', array( 'STLSR_Admin_Menu', 'register_assets' ) );

add_action( 'admin_menu', array( 'STLSR_Admin_Menu', 'create_menu' ) );

add_action( 'wp_ajax_stlsr-save-captcha', array( 'STLSR_Setting', 'save_captcha' ) );

add_action( 'wp_ajax_stlsr-clear-error-logs', array( 'STLSR_Setting', 'clear_error_logs' ) );

add_action( 'wp_ajax_stlsr-reset-plugin', array( 'STLSR_Setting', 'reset_plugin' ) );
