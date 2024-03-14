<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/*plugin class*/
class AnimateGL
{

	public $version;
	public $path;
	public $plugin_dir_path;
	public $plugin_dir_url;
	public $ajaxurl;

	// Singleton
	private static $instance = null;

	public static function get_instance()
	{

		if (null == self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	protected function __construct()
	{
		$this->version = ANIMATE_GL_VERSION;
		$this->path = ANIMATE_GL_FILE;
		$this->plugin_dir_path = plugin_dir_path($this->path);
		$this->plugin_dir_url = plugin_dir_url($this->path);
		$this->ajaxurl = admin_url('admin-ajax.php');

		

		add_action('init', array($this, 'init'));
		add_action('admin_menu', array($this, "admin_menu"));

		if (is_admin()) {
			add_action('wp_ajax_agl_json', array($this,  'ajax_update_settings'));
			add_action('wp_ajax_nopriv_agl_json', array($this,  'ajax_update_settings'));
		}

		add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));
		add_action('enqueue_block_assets', array($this, 'enqueue_block_assets'));

		

		add_action('wp_enqueue_scripts', array($this, 'frontend_assets'));

		register_activation_hook($this->path, array($this, "plugin_activated"));

		
	}

	public function plugin_activated()
	{
		if (defined('WP_DEBUG') && WP_DEBUG) {

			error_log("Animate GL activated");
		}
	}

	public function ajax_update_settings()
	{
		check_ajax_referer('agl_nonce', 'security');

		$json = sanitize_text_field(stripslashes($_POST['json']));

		update_option("agl_json", $json);

		die();
	}


	public function init()
	{
		load_plugin_textdomain('animate-gl', false, plugin_basename(dirname(ANIMATE_GL_FILE)) . '/languages');

		if (did_action('elementor/loaded')) {
			// elementor installed and activated
			include_once(plugin_dir_path(ANIMATE_GL_FILE) . '/includes/el.php');
			AnimateGL_El::get_instance();
		}

		$version      = $this->version;

		// register scripts
		wp_register_script('agl', $this->plugin_dir_url . 'js/lib/animategl.min.js', array(), $version);
		wp_register_script('agl-editor', $this->plugin_dir_url . 'js/lib/animategl.editor.min.js', array('agl'), $version);
		wp_register_script('agl-embed', $this->plugin_dir_url . 'js/embed.js', array('agl'), $version);
		wp_register_script('agl-admin', $this->plugin_dir_url . 'js/admin.js', array('agl-embed', 'agl-editor', 'jquery'), $version);
		// register styles
		wp_register_style('agl', $this->plugin_dir_url . 'css/animategl.css', array(), $version);
		wp_register_style('agl-admin', $this->plugin_dir_url . 'css/admin.css', array(), $version);

	}

	public function frontend_assets()
	{

		// if (!wp_script_is('agl', 'enqueued')) wp_enqueue_script("agl");
		// if (!wp_style_is('agl', 'enqueued')) 
		wp_enqueue_style("agl");

		// if (!wp_script_is('agl-embed', 'enqueued')) 
		wp_enqueue_script("agl-embed");

		if (is_admin_bar_showing() && !is_admin()) {
			// if (!wp_script_is('agl-admin', 'enqueued')) 
			// 	wp_enqueue_script("agl-admin");
			// if (!wp_script_is('agl-editor', 'enqueued')) 
			// 	wp_enqueue_script( "agl-editor"); 
			// if (!wp_style_is('agl-admin', 'enqueued')) 
			// 	wp_enqueue_style("agl-admin");
		}


		$agl_nonce = wp_create_nonce("agl_nonce");
		wp_localize_script('agl', 'agl_nonce', array($agl_nonce));

		$json = get_option('agl_json');
		wp_localize_script('agl-embed', 'agl_options', array($json, $this->plugin_dir_url, $this->ajaxurl));
	}

	public function enqueue_block_assets()
	{
		wp_enqueue_script('agl-embed');
		$json = get_option('agl_json');

		wp_localize_script('agl-embed', 'agl_options', array($json));
		
		wp_enqueue_style('agl');
	}

	public function enqueue_block_editor_assets()
	{
		wp_enqueue_script(
			'agl-block-options-script',
			$this->plugin_dir_url . 'build/index.js',
			array('wp-blocks', 'wp-element'),
			$this->version,
			true, // load in footer
		);

	}

	public function admin_menu()
	{
		add_menu_page(
			'AnimateGL',
			'AnimateGL',
			"publish_posts",
			'agl_admin',
			array($this, "agl_admin"),
			'dashicons-book'
		);
	}

	public function agl_admin()
	{
		include_once('agl_admin.php');
	}

	}
