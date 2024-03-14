<?php
/**
 * Reamaze Admin Menus
 *
 * @author      Reamaze
 * @category    Admin
 * @package     Reamaze/Admin
 * @version     2.3.2
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('Reamaze_Admin_Menus')) :

/**
 * Reamaze_Admin_Menus Class
 */
class Reamaze_Admin_Menus {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		// Add menus
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_menu', array($this, 'app_menus'));
		add_action('admin_menu', array($this, 'settings_menu'));
		add_action('admin_menu', array($this, 'help_menu'));
	}

	/**
	 * Admin Menu
	 */
	public function admin_menu() {
	    global $reamaze;
      add_menu_page(__('Reamaze', 'reamaze'), __('Reamaze', 'reamaze'), 'manage_options', 'reamaze', null, $reamaze->plugin_url() . '/assets/images/icons/logo-mint-16.png' );
	}

	/**
   * App Menu
   */
  public function app_menus() {
    $app_page = add_submenu_page('reamaze', __('Dashboard', 'reamaze'), __('Dashboard', 'reamaze'), 'manage_options', 'reamaze', array($this, 'dashboard_page'));
  }

	/**
	 * Settings Menu
	 */
	public function settings_menu() {
		$settings_page = add_submenu_page('reamaze', __('Reamaze Settings', 'reamaze'), __('Settings', 'reamaze'), 'manage_options', 'reamaze-settings', array($this, 'settings_page'));
	}

	/**
   * Help Menu
   */
  public function help_menu() {
    $settings_page = add_submenu_page('reamaze', __('Reamaze Help', 'reamaze'), __('Need Help?', 'reamaze'), 'manage_options', 'reamaze-help', array($this, 'help_page'));
  }

  /**
   * Settings page content
   */
	public function settings_page() {
	  include_once('reamaze-admin-settings.php');
	  Reamaze_Admin_Settings::output();
	}

	/**
   * Dashboard page content
   */
  public function dashboard_page() {
    include_once('reamaze-admin-dashboard.php');
    Reamaze_Admin_Dashboard::output();
  }

  public function help_page() {
    ?>
    <div data-reamaze-embed="kb"></div>
    <?php
  }
}

endif;

return new Reamaze_Admin_Menus();
