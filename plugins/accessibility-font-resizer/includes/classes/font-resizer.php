<?php
/**
 * Font Resizer
 * 
 * By SatelliteWP - 2019
 * https://www.satellitewp.com/en
 * 
 */

Namespace SatelliteWP\Includes\Classes;

/**
 * Font Resizer class
 */
class Font_Resizer {
	
	// Plugin directory URL
	public $plugin_dir_url = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		$basename = plugin_basename( __FILE__ );
		$plugins_dir_url = plugin_dir_url( dirname( dirname( __FILE__ ) . '../' ) );
		
		$this->plugin_dir_url = $plugins_dir_url;
	}

    /**
	 * Code to execute
	 */
	public function run() {
		$this->define_hooks();
		$this->define_admin_hooks();
  }
	
	/**
	 * Hooks on the front-end
	 */
	public function define_hooks() {
		add_action( 'wp_footer', array( $this, 'javascript_footer' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
	}

	/**
	 * Javascript files to use on the front-end
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'afr-script', $this->plugin_dir_url . '/js/script.js', array( 'jquery' ), '1.0', true );
	}

	/**
	 * Javascript to add to the footer of the site
	 */
	public function javascript_footer() {
		$text_tags = get_option( 'afr_elems' );
		$html_tags = explode( "\n", str_replace( "\r", "", $text_tags ) );
		?>
		<script>
			var afr_debug = <?php echo (get_option( 'afr_debug' ) == '1' ? 'true' : 'false' ); ?>; 
			var afr_days = <?php echo get_option( 'afr_cookie_delay' ); ?>;
			var afr_elems = <?php echo json_encode( $html_tags ); ?>;
			var afr_sizes = [];
			afr_sizes['n'] = <?php echo get_option( 'afr_normal' ); ?>;
			afr_sizes['l'] = <?php echo get_option( 'afr_large' ); ?>;
			afr_sizes['xl'] = <?php echo get_option( 'afr_xlarge' ); ?>;
		</script><?php
	}

	/**
	 * Admin hooks
	 */
	protected function define_admin_hooks() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
  }
    
	/**
	 * Admin menus
	 */
	public function admin_menu() {
		add_options_page( __( 'Font Resizer', 'swp-font-resizer' ), 
			__( 'Font Resizer', 'swp-font-resizer' ), 
			'manage_options', 
			'swp-font-resizer', 
			array( $this, 'options_page' )
			);
    }

	/**
	 * Options page
	 */
	public function options_page() {
		include( dirname( __FILE__ ) . '/../views/options-page.php' );
	}

	/**
	 * Register Settings
	 */
	public function register_settings() {
		add_settings_section(
			'swp_font_resizer',
			'',
			'',
			'swp_font_resizer'
		);

		add_settings_field(
			'afr_elems',
			__( 'HTML elements', 'swp-font-resizer' ),
			array( $this, 'elems_callback' ),
			'swp_font_resizer',
			'swp_font_resizer'
		);
		register_setting( 'swp_font_resizer', 'afr_elems', array( 'type' => 'string' ) );

		add_settings_field(
			'afr_normal',
			__( 'Normal size', 'swp-font-resizer' ),
			array( $this, 'resize_normal_callback' ),
			'swp_font_resizer',
			'swp_font_resizer'
		);
		register_setting( 'swp_font_resizer', 'afr_normal', array( 'type' => 'integer' ) );

		add_settings_field(
			'afr_large',
			__( 'Large Size', 'swp-font-resizer' ),
			array( $this, 'resize_large_callback' ),
			'swp_font_resizer',
			'swp_font_resizer'
		);
		register_setting( 'swp_font_resizer', 'afr_large', array( 'type' => 'integer' ) );

		add_settings_field(
			'afr_xlarge',
			__( 'Very large Size', 'swp-font-resizer' ),
			array( $this, 'resize_xlarge_callback' ),
			'swp_font_resizer',
			'swp_font_resizer'
		);
		register_setting( 'swp_font_resizer', 'afr_xlarge', array( 'type' => 'integer' ) );

		add_settings_field(
			'afr_cookie_delay',
			__( 'Remembrance delay', 'swp-font-resizer' ),
			array( $this, 'cookie_callback' ),
			'swp_font_resizer',
			'swp_font_resizer'
		);
		register_setting( 'swp_font_resizer', 'afr_cookie_delay', array( 'type' => 'integer' ) );

		add_settings_field(
			'afr_debug',
			__( 'Activate debug?', 'swp-font-resizer' ),
			array( $this, 'debug_callback' ),
			'swp_font_resizer',
			'swp_font_resizer'
		);
		register_setting( 'swp_font_resizer', 'afr_debug', array( 'type' => 'boolean' ) );
	}

	/*
	* Callback functions for option page.
	*/
	function elems_callback() 				{ include( dirname( __FILE__ ) . '/../views/elems.php' ); }
	function resize_normal_callback()	{ include( dirname( __FILE__ ) . '/../views/resize-normal.php' ); }
	function resize_large_callback()	{ include( dirname( __FILE__ ) . '/../views/resize-large.php' ); }
	function resize_xlarge_callback()	{ include( dirname( __FILE__ ) . '/../views/resize-xlarge.php' ); }
	function cookie_callback()				{ include( dirname( __FILE__ ) . '/../views/cookie.php' ); }
	function debug_callback()				{ include( dirname( __FILE__ ) . '/../views/debug.php' ); }

	/**
	 * Plugin activation
	 */
	public static function activate() {
		add_option( 'afr_elems',        "body\np\nli\ntd\nh1\nh2\nh3",  '', 'no' );
		add_option( 'afr_normal',       '100', '', 'no' );
		add_option( 'afr_large',        '110', '', 'no' );
		add_option( 'afr_xlarge',       '120', '', 'no' );
		add_option( 'afr_cookie_delay', '30',  '', 'no' );
		add_option( 'afr_debug', 				'yes',  '', 'no' );
	}

	/**
	 * Plugin deactivation
	 */
	public static function deactivate() {
	}

	/**
	 * Plugin uninstall
	 */
	public static function uninstall() {
		delete_option( 'afr_elems' );
		delete_option( 'afr_normal' );
		delete_option( 'afr_large' );
		delete_option( 'afr_xlarge' );
		delete_option( 'afr_cookie_delay' );
		delete_option( 'afr_debug' );
	}
}