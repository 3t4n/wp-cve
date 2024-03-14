<?php class EModal_Admin_Menu {
	public function __construct()
	{
		add_action('admin_menu', array($this, 'admin_menus') );
		add_filter('emodal_admin_submenu_pages', array($this, 'modals_page'), 10);
		add_filter('emodal_admin_submenu_pages', array($this, 'themes_page'), 20);
		add_filter('emodal_admin_submenu_pages', array($this, 'settings_page'), 80);
		add_filter('emodal_admin_submenu_pages', array($this, 'addons_page'), 90);
		add_filter('emodal_admin_submenu_pages', array($this, 'help_page'), 100);
	}
	public function admin_menus()
	{
		add_menu_page(
			apply_filters('emodal_admin_menu_page_title', __(EMCORE_NAME, 'easy-modal' )), // Page Title
			apply_filters('emodal_admin_menu_menu_title', __(EMCORE_NAME, 'easy-modal' )), // Menu Title
			apply_filters('emodal_admin_menu_capability', 'edit_posts'), // Menu Capabilities
			emodal_admin_slug(), // Menu Slug
			apply_filters('emodal_admin_menu_function',array('EModal_Controller_Admin_Modals', 'render')), // Menu Page Function
			apply_filters('emodal_admin_menu_icon_url', EMCORE_URL.'/assets/images/admin/dashboard-icon.png'), // Menu Icon
			apply_filters('emodal_admin_menu_position', 1000)
		);
		foreach(apply_filters('emodal_admin_submenu_pages', array()) as $submenu_page)
		{
			extract($submenu_page);
			add_submenu_page(EMCORE_SLUG ,$page_title, $menu_title, $capability, $menu_slug, $function);
		}
	}
	public function modals_page($submenu)
	{

		$submenu[] = array(
			'page_title' => apply_filters('emodal_admin_submenu_modals_page_title', __('Modals', 'easy-modal' )),
			'menu_title' => apply_filters('emodal_admin_submenu_modals_menu_title', __('Modals', 'easy-modal' )),
			'capability' => apply_filters('emodal_admin_submenu_modals_capability', 'edit_posts'),
			'menu_slug' => emodal_admin_slug(),
			'function' => apply_filters('emodal_admin_submenu_modals_function',array('EModal_Controller_Admin_Modals', 'render'))
		);
		return $submenu;
	}
	public function themes_page($submenu)
	{
		$submenu[] = array(
			'page_title' => apply_filters('emodal_admin_submenu_themes_page_title', __('Theme', 'easy-modal' )),
			'menu_title' => apply_filters('emodal_admin_submenu_themes_menu_title', __('Theme', 'easy-modal' )),
			'capability' => apply_filters('emodal_admin_submenu_themes_capability', 'edit_themes'),
			'menu_slug' => emodal_admin_slug('themes'),
			'function' => apply_filters('emodal_admin_submenu_themes_function',array('EModal_Controller_Admin_Theme', 'render'))
		);
		return $submenu;
	}
	public function settings_page($submenu)
	{
		$submenu[] = array(
			'page_title' => apply_filters('emodal_admin_submenu_settings_page_title', __('Settings', 'easy-modal' )),
			'menu_title' => apply_filters('emodal_admin_submenu_settings_menu_title', __('Settings', 'easy-modal' )),
			'capability' => apply_filters('emodal_admin_submenu_settings_capability', 'manage_options'),
			'menu_slug' => emodal_admin_slug('settings'),
			'function' => apply_filters('emodal_admin_submenu_settings_function',array('EModal_Controller_Admin_Settings', 'render'))
		);
		return $submenu;
	}
	public function addons_page($submenu)
	{
		$submenu[] = array(
			'page_title' => apply_filters('emodal_admin_submenu_addons_page_title', __('Add Ons', 'easy-modal' )),
			'menu_title' => apply_filters('emodal_admin_submenu_addons_menu_title', __('Add Ons', 'easy-modal' )),
			'capability' => apply_filters('emodal_admin_submenu_addons_capability', 'manage_options'),
			'menu_slug' => emodal_admin_slug('addons'),
			'function' => apply_filters('emodal_admin_submenu_addons_function',array('EModal_Controller_Admin_Addons', 'render'))
		);
		return $submenu;
	}
	public function help_page($submenu)
	{
		$submenu[] = array(
			'page_title' => apply_filters('emodal_admin_submenu_help_page_title', __('Help', 'easy-modal' )),
			'menu_title' => apply_filters('emodal_admin_submenu_help_menu_title', __('Help', 'easy-modal' )),
			'capability' => apply_filters('emodal_admin_submenu_help_capability', 'edit_posts'),
			'menu_slug' => emodal_admin_slug('help'),
			'function' => apply_filters('emodal_admin_submenu_help_function',array('EModal_Controller_Admin_Help', 'render'))
		);
		return $submenu;
	}
}