<?php
namespace MBSocial;

use \maxButtons\maxUtils as maxUtils;

class mbSocialPlugin
{
	protected static $instance = null;
	protected static $whistle = null;

	protected $plugin_url;
	protected $plugin_path;
	protected $version;

	protected $networks;
	protected $admin;

	public function __construct()
	{

		$this->plugin_url = $this->get_plugin_url();
		$this->plugin_path = $this->get_plugin_path();
		$this->version = $this->get_version();


		self::$instance = $this;
	}

	// one time run. These thingies need the instance
	public function init()
	{
		$this->loadClasses();

		if (! is_admin())
			$hook_bool = collections::setupHooks();

		//add_action('admin_menu', array($this, 'admin_menu') );
		add_action('admin_enqueue_scripts', array($this, 'admin_styles') );

		// load admin pages, after MB.
		add_filter('maxbuttons/plugin/admin_pages', array($this, 'admin_menu'));

		add_action('add_meta_boxes', array($this, 'meta_boxes'), 10, 2 );
		add_action('save_post', array($this, 'save_meta_boxes'), 10, 3 );

		add_action('wp_enqueue_scripts', array($this, 'front_scripts') );

		add_shortcode('maxsocial', array($this->admin()->namespaceit('collections'), 'shortcode') );

		add_action('maxbuttons/ajax/save_collection', array( $this->admin()->namespaceit('collections'), 'ajax_save') );
		add_action('maxbuttons/ajax/remove-collection', array( $this->admin()->namespaceit('collections'), 'ajax_remove_collection') );

		add_action('maxbuttons/ajax/refreshblock', array($this->admin()->namespaceit('collections'), 'ajax_refreshblock') );
		add_action('maxbuttons/ajax/get_presets', array($this->admin()->namespaceit('collections'), 'ajax_getpresets') );
		add_action('maxbuttons/ajax/set_preset', array($this->admin()->namespaceit('collections'), 'ajax_setpreset') );

		add_action('maxbuttons/ajax/network-settings', array($this->networks(), 'ajax_networksettings') );
		add_action('maxbuttons/ajax/save-network', array($this->networks(), 'ajax_savenetwork') );
		add_action('maxbuttons/ajax/remove-networksettings', array($this->networks(), 'ajax_removesettings'));
		add_action('maxbuttons/ajax/show-customnetworks', array($this->networks(), 'ajax_showcustomnetworks'));
		add_action('maxbuttons/ajax/import-customnetworks', array($this->networks(), 'ajax_importcustomnetworks'));

		// FRONT AJAX
		add_action('wp_ajax_mbsocial_get_count', array($this->admin()->namespaceit("collections"), "ajax_action_front"));  	 // front end for all users
		add_action('wp_ajax_nopriv_mbsocial_get_count', array($this->admin()->namespaceit("collections"), "ajax_action_front"));

		remove_action('admin_init', array(maxUtils::namespaceit('maxAdmin'), 'do_review_notice'));
		add_action('admin_init', array($this->admin(), 'do_review_notice'));
		add_action('maxbuttons/ajax/mbsocial_review_notice_status', array($this->admin(), 'save_review_notice'));

		add_action('wp_ajax_maxbuttons_social_css', array($this->admin()->namespaceit('collections'), 'outputFileCSS'));
		add_action('wp_ajax_nopriv_maxbuttons_social_css', array($this->admin()->namespaceit('collections'), 'outputFileCSS'));
		//add_filter('mb-block-paths', array($this, 'block_templates'));

	}

	/* Singleton pattern */
	public static function getInstance()
	{
		return self::$instance;
	}

	/* Loads all variable / pluggable MBSocial Classes */
	protected function loadClasses()
	{
		$paths = array( mbSocial()->get_plugin_path() . 'classes/blocks/',
						mbSocial()->get_plugin_path() . 'classes/network/',
						mbSocial()->get_plugin_path() . 'classes/styles/',
		//				mbSocial()->get_plugin_path() . 'classes/styles/',
					);
		$paths = apply_filters("mbsocial/paths", $paths ); // for addons and custom blocks / networks

		$collectionBlock = array();
		$styles = array();

		foreach($paths as $cpath)
		{

			$dir_iterator = new \RecursiveDirectoryIterator($cpath, \FilesystemIterator::SKIP_DOTS);
			$iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);

			foreach ($iterator as $fileinfo)
			{
				$file = $fileinfo->getFilename();
				if (substr($file, 0,1) == '_')  // exclude files starting with _
					continue;

				if (file_exists($cpath . $file))
				{
					 require_once( $cpath . $file);
				}


			}
		}

		// no auto-load since order matter.
	//	$style_dir = ;

	/*	$styles = array(
			'round-style',
			'roundflip-style',
		//	'round-style-nucleo', // perphaps for square
			'square-style',
			'dropsquare-style',
			'liftsquare-style',
			'rectangle-style',
			'shiftsquare-style',
			'horizontal-style',
		);

		foreach($styles as $style)
		{
			require_once($style_dir . $style . '.php');
		}
*/
		collections::setBlocks($collectionBlock);
		//styles::setStyles($styles);
		//self::$collectionClass = $collectionClass;
		//self::$collectionBlock = $collectionBlock;
	}

	/*public function blocks_templates($paths)
	{
			$paths['social_start'] = $this->get_plugin_url . 'includes/tpl/';
			return $paths;
	} */

	public function whistle()
	{
		 if (! is_null(static::$whistle))
		 		return static::$whistle;
		 else
		 {
		 		$w = whistle::getInstance();
				static::$whistle = $w;
				return $w;
		 }
	}

	public function networks()
	{
		if (is_null($this->networks))
		{
			$this->networks = new mbSocialNetworks();
		}

		return $this->networks;
	}

	public function admin()
	{
		if ( is_null($this->admin))
		{
			$this->admin = new admin();
		}

		return $this->admin;
	}

	public function admin_menu ($admin_pages)
	{

		$page_title = __('WordPress Share Buttons', 'mb-social');
		$menu_title = __('WordPress Share Buttons', 'mb-social');
		//$capability = $maxbuttons_capabilities;
		$admin_capability = 'manage_options';
		$menu_slug = 'maxbuttons-social';
		$function = array($this, 'load_admin_page');
		$icon_url = $this->plugin_url . 'images/mb-social-icon.png';
		$submenu_function = array($this, 'load_admin_page');

		$admin_pages[] = add_menu_page($page_title, $menu_title, $admin_capability, $menu_slug, $function, $icon_url, 82);

		if (Install::isPro())
		{
			$page_title = __('Settings', 'mb-social');
			$submenu_slug = 'maxbuttons-social-settings';
			$function = array($this, 'load_settings_page');
			$admin_pages[] = add_submenu_page($menu_slug, $page_title, $page_title, $admin_capability, $submenu_slug, $function);
		}

		return $admin_pages;
	}

	public function admin_styles($hook)
	{

     	wp_register_style('mbsocial-global', $this->plugin_url . 'css/global-admin.css', array(), $this->version);

		if ( strpos($hook,'maxbuttons-social') === false )
			return;

		wp_enqueue_style('mbsocial-admin', $this->plugin_url . 'css/admin.css', array(), $this->version);

		if (Install::isPro())
		{
			$deps = array('maxbuttons-pro-init','maxbuttons-ajax', 'mb-media-button',  'jquery-ui-sortable'); // pro init
			wp_register_script('maxbuttons-mbcustom', $this->plugin_url . 'js/maxbuttons-custom.js', $deps, $this->version, true);
			wp_enqueue_script('maxbuttons-mbcustom');

			wp_register_script('mbsocial-icons', $this->plugin_url . 'js/images.js', $deps, $this->version, true);
			wp_enqueue_script('mbsocial-icons');

			$deps[] = 'mbsocial-icons';
		}
		else
		{
			$deps = array('maxbutton-js-init', 'maxbuttons-ajax', 'mb-media-button',  'jquery-ui-sortable');  // free init
		}
		wp_register_script('maxbuttons-social', $this->plugin_url . 'js/maxbuttons-social.js', $deps , $this->version, true);
		wp_enqueue_script('maxbuttons-social');


		wp_register_script('mbsocial-editor', $this->plugin_url . 'js/network-editor.js', $deps, $this->version, true);
		wp_enqueue_script('mbsocial-editor');

 		MB()->load_media_script();

		wp_register_style('nucleo_icons', $this->plugin_url . 'libraries/nucleo_icons/nucleo_icons.css', array(), $this->version);
		wp_enqueue_style('nucleo_icons');
	}

	public function front_scripts($hook)
	{
		wp_register_script('mbsocial_front', $this->plugin_url . 'js/social-front.js', array('jquery'),
			$this->version, true);

		wp_localize_script('mbsocial_front','mbsocial', array('ajaxurl' => admin_url( 'admin-ajax.php' )));

		wp_register_style('mbsocial-buttons', $this->plugin_url . 'css/buttons.css');

		//wp_register_style('nucleo_icons', $this->plugin_url . 'libraries/nucleo_icons/nucleo_icons.css', array(), $this->version);
		//wp_enqueue_style('nucleo_icons');
	}

	public function meta_boxes($post_type, $post)
	{
		include_once($this->plugin_path . 'admin/meta_boxes.php');

	}

	public function save_meta_boxes($post_id, $post, $update)
	{

		if (! isset($_POST['mbsocial_save']) )
			return;

		if (! wp_verify_nonce( $_POST['mbsocial_save'], 'save'))
			return;


		include_once($this->plugin_path . 'admin/save_meta_boxes.php');

	}

	/** Returns the full path of the plugin installation directory */
 	public function get_plugin_path()
 	{
 		return trailingslashit(plugin_dir_path(MBSOCIAL_ROOT_FILE));
 	}

 	/** Returns the full URL of the plugin installation path */
 	public function get_plugin_url()
 	{
 		return trailingslashit(plugin_dir_url(MBSOCIAL_ROOT_FILE));
 	}

 	public function get_version()
 	{
 		return MBSOCIAL_VERSION_NUM;
 	}

 	public function load_admin_page()
 	{
		if (Install::getIssue() == 'mb-wrong-version')
			include_once($this->plugin_path . 'admin/update_mb.php');
		else
			include_once($this->plugin_path . 'admin/page_editor.php');
 	}

	public function load_settings_page()
	{
		if (Install::getIssue() == 'mb-wrong-version')
			include_once($this->plugin_path . 'admin/update_mb.php');
		else
			include_once($this->plugin_path . 'admin/page_settings.php');
	}

}
