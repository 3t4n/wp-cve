<?php
/**
 * Plugin Name: Accordeon Menu CK
 * Plugin URI: http://www.ceikay.com/plugins/accordeon-menu-ck/
 * Description: Accordeon Menu CK shows an accordion menu in any sidebar position with multiple settings.
 * Version: 1.1.10
 * Author: Cédric KEIFLIN
 * Author URI: https://www.ceikay.com/
 * License: GPL2
 * Text Domain: accordeon-menu-ck
 * Domain Path: /language
 */

Namespace Accordeonmenuck;

defined('ABSPATH') or die;

if (! defined('CK_LOADED')) define('CK_LOADED', 1);
if (! defined('ACCORDEONMENUCK_VERSION')) define('ACCORDEONMENUCK_VERSION', '1.1.10');
if (! defined('ACCORDEONMENUCK_PLATFORM')) define('ACCORDEONMENUCK_PLATFORM', 'wordpress');
if (! defined('ACCORDEONMENUCK_PATH')) define('ACCORDEONMENUCK_PATH', dirname(__FILE__));
if (! defined('ACCORDEONMENUCK_MEDIA_PATH')) define('ACCORDEONMENUCK_MEDIA_PATH', ACCORDEONMENUCK_PATH);
if (! defined('ACCORDEONMENUCK_ADMIN_GENERAL_URL')) define('ACCORDEONMENUCK_ADMIN_GENERAL_URL', admin_url('', 'relative') . 'admin.php?page=accordeonmenuck_general');
if (! defined('ACCORDEONMENUCK_ADMIN_EDIT_STYLE_URL')) define('ACCORDEONMENUCK_ADMIN_EDIT_STYLE_URL', admin_url('', 'relative') . 'admin.php?page=accordeonmenuck_edit_style');
if (! defined('ACCORDEONMENUCK_ADMIN_EDIT_MENU_URL')) define('ACCORDEONMENUCK_ADMIN_EDIT_MENU_URL', admin_url('', 'relative') . 'admin.php?page=accordeonmenuck_edit_menu');
if (! defined('ACCORDEONMENUCK_MEDIA_URL')) define('ACCORDEONMENUCK_MEDIA_URL', plugins_url('', __FILE__));
if (! defined('ACCORDEONMENUCK_SITE_ROOT')) define('ACCORDEONMENUCK_SITE_ROOT', ABSPATH);
if (! defined('ACCORDEONMENUCK_URI_ROOT')) define('ACCORDEONMENUCK_URI_ROOT', site_url());
if (! defined('ACCORDEONMENUCK_URI_BASE')) define('ACCORDEONMENUCK_URI_BASE', admin_url('', 'relative'));
if (! defined('ACCORDEONMENUCK_PLUGIN_NAME')) define('ACCORDEONMENUCK_PLUGIN_NAME', 'accordeon-menu-ck');
if (! defined('ACCORDEONMENUCK_SETTINGS_FIELD')) define('ACCORDEONMENUCK_SETTINGS_FIELD', 'accordeon-menu-ck_options');
if (! defined('ACCORDEONMENUCK_WEBSITE')) define('ACCORDEONMENUCK_WEBSITE', 'http://www.ceikay.com/plugins/accordeon-menu-ck/');
// global vars
if (! defined('CEIKAY_MEDIA_URL')) define('CEIKAY_MEDIA_URL', 'https://media.ceikay.com');

class Accordeonmenuck {

	public $options, $fields, $default_settings;

	private static $instance;

	static function getInstance() {
		if (!isset(self::$instance))
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	function init() {
		// load the translation
		add_action('plugins_loaded', array($this, 'load_textdomain'));

		if (is_admin()) {
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 20 );
			// for a nice menu icon
			add_action('admin_head', array($this, 'set_admin_menu_image_position'), 20);
		} else {

		}

		// register the widget
		add_action('widgets_init', array($this, 'register_widget'));

		return;
	}

	public function admin_init() {
		wp_enqueue_script('jquery');
	}

	public function admin_menu() {
		if ( ! current_user_can('update_plugins') )
			return;

		$this->pagehook = add_menu_page('Accordeon Menu CK', 'Accordeon Menu CK', 'administrator', 'accordeonmenuck_general', array($this, 'render_styles'), ACCORDEONMENUCK_MEDIA_URL . '/images/admin_menu.png');

		add_submenu_page('accordeonmenuck_general', __('Accordeon Menu CK'), __('All Styles', 'accordeon-menu-ck'), 'administrator', 'accordeonmenuck_general', array($this, 'render_styles'));
		add_submenu_page('accordeonmenuck_general', __('Edit'), __('Add New', 'accordeon-menu-ck'), 'administrator', 'accordeonmenuck_edit_style', array($this, 'render_edit_style'));
	}

	/**
	 * Set some styles for the admin menu icon
	 */
	function set_admin_menu_image_position() {
		?>
		<style type="text/css">#toplevel_page_accordeonmenuck_general .wp-menu-image img { padding: 13px 0 0 !important; }</style>
		<?php
	}

	function load_textdomain() {
		load_plugin_textdomain( 'accordeon-menu-ck', false, dirname( plugin_basename( __FILE__ ) ) . '/language/'  );
	}

	function register_widget() {
		wp_enqueue_script('jquery');
		require_once('helpers/widget.php');
		register_widget('Accordeonmenuck_Widget');
	}

	private function callHelpers() {
		// include the classes
		require_once ACCORDEONMENUCK_PATH . '/helpers/ckfof.php';
		require_once ACCORDEONMENUCK_PATH . '/helpers/ckfilterinput.php';
		require_once ACCORDEONMENUCK_PATH . '/helpers/ckparams.php';
		require_once ACCORDEONMENUCK_PATH . '/helpers/ckinput.php';
		require_once ACCORDEONMENUCK_PATH . '/helpers/ckpath.php';
		require_once ACCORDEONMENUCK_PATH . '/helpers/ckfile.php';
		require_once ACCORDEONMENUCK_PATH . '/helpers/ckfolder.php';
		require_once ACCORDEONMENUCK_PATH . '/helpers/cktext.php';
		require_once ACCORDEONMENUCK_PATH . '/helpers/ckfields.php';
		require_once ACCORDEONMENUCK_PATH . '/helpers/helper.php';
		require_once ACCORDEONMENUCK_PATH . '/helpers/ckinterfacelight.php';
		require_once ACCORDEONMENUCK_PATH . '/helpers/ckstyles.php';
		require_once ACCORDEONMENUCK_PATH . '/helpers/ckcontroller.php';
		require_once ACCORDEONMENUCK_PATH . '/helpers/ckmodel.php';
	}

	public function render_page($view, $layout = 'default') {

		$this->callHelpers();

		$this->input = new CKInput();
		$tasks = $this->input->get('task', '', 'cmd');
		if ($tasks) {
			$tasks = explode('.', $tasks);
			if (count($tasks) == 2) {
				$controllerName = $tasks[0];
				$controllerClassName = '\Accordeonmenuck\CKController' . ucfirst($tasks[0]);
				$task = $tasks[1];
				require_once ACCORDEONMENUCK_PATH . '/controllers/' . $controllerName . '.php';
				$controller = new $controllerClassName();
				$controller->$task();
			} else {
				$task = $tasks[0];
				$controller = new CKController();
				$controller->$task();
			}
		}

		// load the view
		$layout = $this->input->get('layout', $layout, 'cmd');
		$view = $this->input->get('view', $view, 'cmd');
		require_once ACCORDEONMENUCK_PATH . '/helpers/ckview.php';
		require_once ACCORDEONMENUCK_PATH . '/views/' . $view . '/view.html.php';
		$className = '\Accordeonmenuck\CKView' . ucfirst($view);
		$classInstance = new $className();
		$classInstance->display($layout);
	}

	function render_styles() {
		$this->render_page('styles');
	}

	function render_edit_style() {
		$this->render_page('style', 'edit');
	}

	function render_help() {
		$this->render_page('help');
	}

	function render_about() {
		$this->render_page('about');
	}

	function render_edit_menu() {
		$this->render_page('menu', 'edit');
	}
}

// if we go into the edition interface, we redirect and kill
if ( isset($_REQUEST['page']) 
		&& $_REQUEST['page'] === 'accordeonmenuck_edit_style'
		&& isset($_REQUEST['task']) && substr($_REQUEST['task'], 0, 10) === 'style.ajax'
		) {
		add_action('admin_init', '\Accordeonmenuck\accordeonmenuck_edition_init', 20);
}

function accordeonmenuck_edition_init() {
	// get the template creator class
	$Accordeonmenuck = Accordeonmenuck::getInstance();
	$Accordeonmenuck->render_page('style');
	die();
}

// load the process
$Accordeonmenuck = Accordeonmenuck::getInstance();
$Accordeonmenuck->init();

// to create and manage the database
require_once( WP_PLUGIN_DIR . '/accordeon-menu-ck/helpers/sql.php' );
register_activation_hook( __FILE__, 'accordeonmenuck_sql_install' );
register_activation_hook( __FILE__, 'accordeonmenuck_sql_install_data' );