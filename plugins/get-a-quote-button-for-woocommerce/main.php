<?php
/**
 * Plugin Name:       Get a Quote Button for WooCommerce
 * Plugin URI:        https://wpbean.com/plugins/
 * Description:       Get a Quote Button for WooCommerce using Contact Form 7. It can be used for requesting a quote, pre-sale questions or query.
 * Version:           1.3.7
 * Author:            wpbean
 * Author URI:        https://wpbean.com
 * Text Domain:       wpb-get-a-quote-button
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/**
 * Define constants
 */

if ( ! defined( 'WPB_GQB_FREE_INIT' ) ) {
  define( 'WPB_GQB_FREE_INIT', plugin_basename( __FILE__ ) );
}


/**
 * This version can't be activate if premium version is active
 */

if ( defined( 'WPB_GQB_PREMIUM' ) ) {
    function wpb_gqb_install_free_admin_notice() {
        ?>
	        <div class="error">
	            <p><?php esc_html_e( 'You can\'t activate the free version of Get a Quote Button while you are using the premium one.', 'wpb-get-a-quote-button' ); ?></p>
	        </div>
    	<?php
    }

    add_action( 'admin_notices', 'wpb_gqb_install_free_admin_notice' );
    deactivate_plugins( plugin_basename( __FILE__ ) );
    return;
}


/* -------------------------------------------------------------------------- */
/*                                Plugin Class                                */
/* -------------------------------------------------------------------------- */

class WPB_Get_Quote_Button {

	//  Plugin version
	public $version = '1.3.7';

	// The plugin url
	public $plugin_url;
	
	// The plugin path
	public $plugin_path;

	// The theme directory path
	public $theme_dir_path;

	// Initializes the WPB_Get_Quote_Button() class
	public static function init(){
		static $instance = false;

		if( !$instance ){
			$instance = new WPB_Get_Quote_Button();

			add_action( 'after_setup_theme', array($instance, 'plugin_init') );
			add_action( 'activated_plugin', array($instance, 'activation_redirect') );
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $instance, 'plugin_action_links' ) );
            register_activation_hook( __FILE__, array($instance, 'activate' ) );
            register_deactivation_hook( plugin_basename( __FILE__ ), array($instance, 'wpb_gqb_lite_plugin_deactivation' ) );
		}

		return $instance;
	}

	//Initialize the plugin
	function plugin_init(){
		$this->file_includes();
		$this->init_classes();

		// Localize our plugin
		add_action( 'init', array( $this, 'localization_setup' ) );

		// Loads frontend scripts and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 999 );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		add_action( 'admin_notices', array( $this, 'wpb_gqb_pro_discount_admin_notice' ) );

		add_action( 'admin_init', array( $this, 'wpb_gqb_pro_discount_admin_notice_dismissed' ) );

		// In case any theme disable the CF7 scripts
		add_filter( 'wpcf7_load_js', '__return_true', 30 );
		add_filter( 'wpcf7_load_css', '__return_true', 30 );

		add_action( 'wp_footer', array( $this, 'force_cf7_script_loading' ) );
	}

	/**
	 * Force CF7 Script Loading
	 */
	public function force_cf7_script_loading(){
		$force_cf7_scripts 	= wpb_gqb_get_option( 'wpb_gqb_force_cf7_scripts', 'form_settings' );
		$form_id 			= wpb_gqb_get_option( 'wpb_gqb_cf7_form_id', 'form_settings' );

		if( 'on' === $force_cf7_scripts && isset($form_id) ){
			echo '<div class="wpb_gqb_hidden_cf7" style="display:none">';
			echo do_shortcode( '[contact-form-7 id="'. esc_attr($form_id) .'"]' );
			echo '</div>';
		}
	}

	/**
	 * Pro version discount
	 */
	function wpb_gqb_pro_discount_admin_notice() {
	    $user_id = get_current_user_id();
	    if ( !get_user_meta( $user_id, 'wpb_gqb_pro_discount_dismissed' ) ){
	        printf('<div class="wpb-gqb-discount-notice updated" style="padding: 30px 20px;border-left-color: #27ae60;border-left-width: 5px;margin-top: 20px;"><p style="font-size: 18px;line-height: 32px">%s <a target="_blank" href="%s">%s</a>! %s <b>%s</b></p><a href="%s">%s</a></div>', esc_html__( 'Get a 10% exclusive discount on the premium version of the', 'wpb-get-a-quote-button' ), 'https://wpbean.com/downloads/get-a-quote-button-pro-for-woocommerce-and-elementor/', esc_html__( 'Get a Quote Button for WooCommerce', 'wpb-get-a-quote-button' ), esc_html__( 'Use discount code - ', 'wpb-get-a-quote-button' ), '10PERCENTOFF', esc_url( add_query_arg( 'wpb-gqb-pro-discount-admin-notice-dismissed', 'true' ) ), esc_html__( 'Dismiss', 'wpb-get-a-quote-button' ));
	    }
	}


	function wpb_gqb_pro_discount_admin_notice_dismissed() {
	    $user_id = get_current_user_id();
	    if ( isset( $_GET['wpb-gqb-pro-discount-admin-notice-dismissed'] ) ){
	      add_user_meta( $user_id, 'wpb_gqb_pro_discount_dismissed', 'true', true );
	    }
	}

	/**
	 * Plugin Deactivation
	 */

	function wpb_gqb_lite_plugin_deactivation() {
	  $user_id = get_current_user_id();
	  if ( get_user_meta( $user_id, 'wpb_gqb_pro_discount_dismissed' ) ){
	  	delete_user_meta( $user_id, 'wpb_gqb_pro_discount_dismissed' );
	  }

	  flush_rewrite_rules();
	}





	// The plugin activation function
	public function activate(){
		update_option( 'wpb_gqb_installed', time() );
		update_option( 'wpb_gqb_version', $this->version );
	}

	// The plugin activation redirect
	function activation_redirect( $plugin ) {
	    if( $plugin == plugin_basename( __FILE__ ) ) {
	        exit( wp_redirect( admin_url( 'options-general.php?page=get-a-quote-button' ) ) );
	    }
	}

	function plugin_action_links( $links ) {
		$links[] = '<a href="'. admin_url( 'options-general.php?page=get-a-quote-button' ) .'">'. esc_html__('Settings', 'wpb-get-a-quote-button') .'</a>';
		return $links;
	 }

	// Load the required files
	function file_includes() {
		include_once dirname( __FILE__ ) . '/includes/functions.php';
		include_once dirname( __FILE__ ) . '/includes/class-shortcode.php';

		if ( is_admin() ) {
			include_once dirname( __FILE__ ) . '/includes/admin/class.settings-api.php';
			include_once dirname( __FILE__ ) . '/includes/admin/class.settings-config.php';
		}

		if ( class_exists( 'woocommerce' ) ) {
			include_once dirname( __FILE__ ) . '/includes/class-woocommerce.php';
		}
		
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            include_once dirname( __FILE__ ) . '/includes/class-ajax.php';
        }
	}

	// Initialize the classes
    public function init_classes() {
    	
    	new WPB_GQB_Shortcode_Handler();

		if ( is_admin() ) {
            new WPB_GQB_Plugin_Settings();
        }

		if ( class_exists( 'woocommerce' ) ) {
			new WPB_GQB_WooCommerce_Handler();
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            new WPB_GQB_Ajax();
        }
	}

	// Initialize plugin for localization
    public function localization_setup() {
        load_plugin_textdomain( 'wpb-get-a-quote-button', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	// Loads frontend scripts and styles
    public function enqueue_scripts() {

    	do_action('cfturnstile_enqueue_scripts');
    	
    	if( function_exists('wpcf7_script_is') ){
			if( !wpcf7_script_is() ){
	    		wpcf7_enqueue_scripts();
	    		wpcf7_enqueue_styles();
    		}
    	}

		// All styles goes here
		wp_enqueue_style( 'wpb-get-a-quote-button-sweetalert2', plugins_url( 'assets/css/sweetalert2.min.css', __FILE__ ), array(), $this->version );
		wp_enqueue_style( 'wpb-get-a-quote-button-styles', plugins_url( 'assets/css/frontend.css', __FILE__ ), array(), $this->version );

		// All scripts goes here
        wp_enqueue_script( 'wpb-get-a-quote-button-sweetalert2', plugins_url( 'assets/js/sweetalert2.all.min.js', __FILE__ ), array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'wpb-get-a-quote-button-scripts', plugins_url( 'assets/js/frontend.js', __FILE__ ), array( 'jquery', 'wp-util' ), $this->version, true );
		wp_localize_script( 'wpb-get-a-quote-button-scripts', 'WPB_GQB_Vars', array(
            'ajaxurl' 		=> admin_url( 'admin-ajax.php' ),
            'nonce'   		=> wp_create_nonce( 'wpb-get-a-quote-button-ajax' ),
		) );
		

		$btn_color       		= wpb_gqb_get_option( 'wpb_gqb_btn_color', 'btn_settings', '#ffffff' );
		$bg_color       		= wpb_gqb_get_option( 'wpb_gqb_btn_bg_color', 'btn_settings', '#17a2b8' );
		$btn_hover_color       	= wpb_gqb_get_option( 'wpb_gqb_btn_hover_color', 'btn_settings', '#ffffff' );
		$btn_bg_hover_color     = wpb_gqb_get_option( 'wpb_gqb_btn_bg_hover_color', 'btn_settings', '#138496' );
		$custom_css = "
		.wpb-get-a-quote-button-btn-default,
		.wpb-gqf-form-style-true input[type=submit],
		.wpb-gqf-form-style-true input[type=button],
		.wpb-gqf-form-style-true input[type=submit],
		.wpb-gqf-form-style-true input[type=button]{
			color: {$btn_color};
			background: {$bg_color};
		}
		.wpb-get-a-quote-button-btn-default:hover, .wpb-get-a-quote-button-btn-default:focus,
		.wpb-gqf-form-style-true input[type=submit]:hover, .wpb-gqf-form-style-true input[type=submit]:focus,
		.wpb-gqf-form-style-true input[type=button]:hover, .wpb-gqf-form-style-true input[type=button]:focus,
		.wpb-gqf-form-style-true input[type=submit]:hover,
		.wpb-gqf-form-style-true input[type=button]:hover,
		.wpb-gqf-form-style-true input[type=submit]:focus,
		.wpb-gqf-form-style-true input[type=button]:focus {
			color: {$btn_hover_color};
			background: {$btn_bg_hover_color};
		}";
				
		wp_add_inline_style( 'wpb-get-a-quote-button-styles', $custom_css );
	}

	// plugin admin notices
    public function admin_notices() {

		$cf7_form_id = wpb_gqb_get_option( 'wpb_gqb_cf7_form_id', 'form_settings' );

		if ( ! defined( 'WPCF7_PLUGIN' ) ) {
			?>
			<div class="notice notice-error is-dismissible">
				<p><b><?php esc_html_e( 'Get a Quote Button', 'wpb-get-a-quote-button' ); ?></b><?php esc_html_e( ' required ', 'wpb-get-a-quote-button' ); ?><b><a href="https://wordpress.org/plugins/contact-form-7" target="_blank"><?php esc_html_e( 'Contact Form 7', 'wpb-get-a-quote-button' ); ?></a></b><?php esc_html_e( ' plugin to work with.', 'wpb-get-a-quote-button' ); ?></p>
			</div>
			<?php
		}

		if ( ! $cf7_form_id ) {
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php esc_html_e('The Get a Quote Button needs a form to show. Please select a form', 'wpb-get-a-quote-button'); ?> <a href="<?php echo esc_url( admin_url('options-general.php?page=get-a-quote-button') ); ?>"><?php esc_html_e('here', 'wpb-get-a-quote-button'); ?></a>.</p>
			</div>
			<?php
		}
	}
}


/* -------------------------------------------------------------------------- */
/*                            Initialize the plugin                           */
/* -------------------------------------------------------------------------- */

function wpb_get_quote_button() {
    return WPB_Get_Quote_Button::init();
}

// kick it off
wpb_get_quote_button();