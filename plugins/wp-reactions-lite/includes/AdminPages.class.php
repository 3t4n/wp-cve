<?php

namespace WP_Reactions\Lite;

class AdminPages
{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public static function init()
    {
        return new self();
    }

    function add_admin_menu()
    {
        $icon_file = WPRA_LITE_PLUGIN_PATH . 'assets/images/admin_menu_icon.svg';
        $handle = fopen($icon_file, "r");
        $icon_svg = fread($handle, filesize($icon_file));
        fclose($handle);

        $icon_encoded = base64_encode($icon_svg);

        add_menu_page(
            __('WP Reactions', 'wpreactions-lite'),
            __('WP Reactions', 'wpreactions-lite'),
            'manage_options',
            'wpra-dashboard',
            [$this, 'dashboard_page_html'],
            'data:image/svg+xml;base64,' . $icon_encoded
        );

	    add_submenu_page(
		    'wpra-dashboard',
		    __('Dashboard', 'wpreactions-lite'),
		    __('Dashboard', 'wpreactions-lite'),
		    'manage_options',
		    'wpra-dashboard',
		    [$this, 'dashboard_page_html']
	    );

        add_submenu_page(
            'wpra-dashboard',
            __('Global Activation', 'wpreactions-lite'),
            __('Global Activation', 'wpreactions-lite'),
            'manage_options',
            'wpra-global-options',
            [$this, 'options_page_html']
        );

        add_submenu_page(
            'wpra-dashboard',
            __('Support', 'wpreactions-lite'),
            __('Support', 'wpreactions-lite'),
            'manage_options',
            'wpra-support',
            [$this, 'support_page_html']
        );

        add_submenu_page(
            'wpra-dashboard',
            __('PRO', 'wpreactions-lite'),
            __('PRO', 'wpreactions-lite'),
            'manage_options',
            'wpra-pro',
            [ $this, 'pro_page_html']
        );

    }

	function dashboard_page_html()
	{
		if (!current_user_can('manage_options')) {
			return;
		}
		Helper::getTemplate('view/admin/dashboard');
	}


	function options_page_html()
    {
        if (!current_user_can('manage_options')) {
            return;
        }
        Helper::getTemplate('view/admin/global-options');
    }

    function support_page_html()
    {
        if (!current_user_can('manage_options')) {
            return;
        }
        Helper::getTemplate('view/admin/support');
    }

    function pro_page_html()
    {
        if (!current_user_can('manage_options')) {
            return;
        }
        Helper::getTemplate('view/admin/pro');
    }

} // end of class
