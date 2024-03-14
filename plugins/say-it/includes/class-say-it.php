<?php
class Say_It {

	protected $loader;
	protected $plugin_name;
	protected $version;
	protected $folder;

	public function __construct() {
		if ( defined( 'SAY_IT_VERSION' ) ) {
			$this->version = SAY_IT_VERSION;
		} else {
			$this->version = '2.1.0';
		}
		$this->plugin_name = 'say-it';
		$this->folder = plugin_dir_path( dirname( __FILE__ ) );

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-say-it-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-say-it-google-tts.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-say-it-amazon-polly.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-say-it-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-say-it-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-say-it-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';
		$this->loader = new Say_It_Loader();
	}


	private function set_locale() {
		$plugin_i18n = new Say_It_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	private function get_plugin_options() {
		$defaults = array(
			'mode' => 'html5',
			'default_language' => 'en-US',
			'default_speed' => '1',
			'google_language' => 'en-US',
			'google_gender' => 'male',
			'google_speed' => '1',
			'google_custom_voice' => 'en-US-Wavenet-I',
			'amazon_polly_region' => 'us-west-2',
			'amazon_voice' => 'Kimberly',
			'tooltip_text' => 'Listen',
			'skin' => 'theme1.css'
		);
		return wp_parse_args(get_option($this->plugin_name), $defaults);
	}

	private function define_admin_hooks() {

		$plugin_admin = new Say_It_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_folder(), $this->get_plugin_options() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Save/Update our plugin options
		$this->loader->add_action('admin_init', $plugin_admin, 'options_update');

		// Add menu item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		
		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

		// Ajax actions used in gutenberg
		$this->loader->add_action( 'wp_ajax_sayit_mp3', $plugin_admin, 'sayit_get_mp3_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_sayit_mp3', $plugin_admin, 'sayit_get_mp3_ajax' );
	}

	private function define_public_hooks() {

		$plugin_public = new Say_It_Public( $this->get_plugin_name(), $this->get_version(), $this->get_plugin_options() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_shortcode( "sayit", $plugin_public, "shortcode_function", $priority = 10, $accepted_args = 2 );
		$this->loader->add_shortcode( "sayit_player", $plugin_public, "shortcode_player_function", $priority = 10, $accepted_args = 2 );
		$this->loader->add_shortcode( "sayit_mp3_player", $plugin_public, "shortcode_mp3_player_function", $priority = 10, $accepted_args = 2 );
		
		// Ajax actions
		$this->loader->add_action( 'wp_ajax_sayit_mp3', $plugin_public, 'sayit_get_mp3_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_sayit_mp3', $plugin_public, 'sayit_get_mp3_ajax' );

		// Ajax action for bulk answer
		$this->loader->add_action( 'wp_ajax_sayit_mp3_bulk', $plugin_public, 'sayit_get_mp3_ajax_bulk' );
		$this->loader->add_action( 'wp_ajax_nopriv_sayit_mp3_bulk', $plugin_public, 'sayit_get_mp3_ajax_bulk' );
	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

	public function get_folder() {
		return $this->folder;
	}

}
