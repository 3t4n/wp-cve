<?php
/**
 * Register plugin specific admin menu
 */

function pmlc_admin_menu() {
	global $menu, $submenu;
	
	if (current_user_can('manage_options')) { // admin management options
		
		add_menu_page(__('WP Wizard Cloak', 'pmlc_plugin'), __('Wizard Cloak', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-home', array(PMLC_Plugin::getInstance(), 'adminDispatcher'), PMLC_Plugin::ROOT_URL . '/static/img/wizard-icon.png');
		// workaround to rename 1st option to `Home`
		if (current_user_can('manage_options')) {
			$submenu['pmlc-admin-home'] = array();
			add_submenu_page('pmlc-admin-home', __('WP Wizard Cloak', 'pmlc_plugin'), __('Home', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-home', array(PMLC_Plugin::getInstance(), 'adminDispatcher'));
		}
		add_submenu_page('pmlc-admin-home', __('Create Link', 'pmlc_plugin') . ' &lsaquo; ' . __('WP Wizard Cloak', 'pmlc_plugin'), __('Create Link', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-add', array(PMLC_Plugin::getInstance(), 'adminDispatcher'));
		add_submenu_page('pmlc-admin-home', __('Manage Links', 'pmlc_plugin') . ' &lsaquo; ' . __('WP Wizard Cloak', 'pmlc_plugin'), __('Manage Links', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-links', array(PMLC_Plugin::getInstance(), 'adminDispatcher'));
		add_submenu_page('pmlc-admin-home', __('Auto-Linked Keyword Groupings', 'pmlc_plugin') . ' &lsaquo; ' . __('WP Wizard Cloak', 'pmlc_plugin'), __('Auto-Linked KWs', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-keywords', array(PMLC_Plugin::getInstance(), 'adminDispatcher'));
		add_submenu_page('pmlc-admin-home', __('Statistics', 'pmlc_plugin') . ' &lsaquo; ' . __('WP Wizard Cloak', 'pmlc_plugin'), __('Statistics', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-statistics', array(PMLC_Plugin::getInstance(), 'adminDispatcher'));
		add_submenu_page('pmlc-admin-home', __('Settings', 'pmlc_plugin') . ' &lsaquo; ' . __('WP Wizard Cloak', 'pmlc_plugin'), __('Settings', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-settings', array(PMLC_Plugin::getInstance(), 'adminDispatcher'));
		add_submenu_page('pmlc-admin-home', __('Help', 'pmlc_plugin') . ' &lsaquo; ' . __('WP Wizard Cloak', 'pmlc_plugin'), __('Help', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-help', array(PMLC_Plugin::getInstance(), 'adminDispatcher'));
		
		add_submenu_page('empty-parent', __('Edit Link', 'pmlc_plugin') . ' &lsaquo; ' . __('WP Wizard Cloak', 'pmlc_plugin'), __('Edit Link', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-edit', array(PMLC_Plugin::getInstance(), 'adminDispatcher'));
		add_submenu_page('empty-parent', __('TinyMCE', 'pmlc_plugin') . ' &lsaquo; ' . __('WP Wizard Cloak', 'pmlc_plugin'), __('TinyMCE', 'pmlc_plugin'), 'manage_options', 'pmlc-admin-tinymce', array(PMLC_Plugin::getInstance(), 'adminDispatcher'));
		
	}	
}