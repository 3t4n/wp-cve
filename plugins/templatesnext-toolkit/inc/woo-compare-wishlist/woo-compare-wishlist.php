<?php
/*
	TX WooCommerce Compare & Wishlist
*/

if ( ! defined( 'ABSPATH' ) ) {

	header( 'HTTP/1.0 404 Not Found', true, 404 );

	exit;
}

class TX_WC_Compare_Wishlist {

	/**
	 * The single instance of the class.
	 *
	 * @var TX_Woocommerce
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Trigger checks is woocoomerce active or not
	 *
	 * @since 1.0.0
	 * @var   bool
	 */
	public $has_woocommerce = null;

	/**
	 * Holder for plugin folder path
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $plugin_dir = null;

	/**
	 * Holder for plugin loader
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $loader;

	/**
	 * Holder for plugin scripts suffix
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $suffix;

	/**
	 * Main TX_WC_Compare_Wishlist Instance.
	 *
	 * Ensures only one instance of TX_Woocommerce is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see tx_wc_compare_wishlist()
	 * @return TX_WC_Compare_Wishlist - Main instance.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {

			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Sets up needed actions/filters for the theme to initialize.
	 *
	 * @since 1.0.0
	*/
	public function __construct() {

		$page_found = 83;

		define( 'TX_WC_COMPARE_WISHLIST_VERISON', '1.0.1' );
		define( 'TX_WC_COMPARE_WISHLIST_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		// Load public assets.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ), 10 );

		// Internationalize the text strings used.
		// add_action( 'plugins_loaded', array( $this, 'lang' ), 1 );

		add_action( 'plugins_loaded', array( $this, 'init' ), 0 );

		//register_activation_hook( __FILE__, array( $this, 'tx_wc_compare_wishlist_install' ) );

		$this->set_suffix();
	}

	public function set_suffix() {

		if ( is_null( $this->suffix ) ) {

			$this->suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		}
	}

	/**
	 * Loads the translation files.
	 *
	 * @since 1.0.0
	 
	function lang() {

		load_plugin_textdomain( 'tx', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	*/
	public function init() {

		include_once 'includes/templater.php';
		include_once 'includes/compare/compare.php';
		include_once 'includes/wishlist/wishlist.php';
	}

	/**
	 * Check if WooCommerce is active
	 *
	 * @since  1.0.0
	 * @return bool
	 */
	public function has_woocommerce() {

		if ( null == $this->has_woocommerce ) {

			$this->has_woocommerce = in_array(
				'woocommerce/woocommerce.php',
				apply_filters( 'active_plugins', get_option( 'active_plugins' ) )
			);
		}
		return $this->has_woocommerce;
	}

	/**
	 * Enqueue assets.
	 *
	 * @since 1.0.0
	 * @return void
	*/
	public function register_assets() {

		// TX Bootstrap Grid
		if( ! wp_style_is( 'bootstrap-grid', 'registered' ) ) {

			wp_register_style( 'bootstrap-grid', tx_wc_compare_wishlist()->plugin_url() . '/assets/css/grid.css', array() );
		}

		// TX WooCompare
		wp_register_style( 'tm-woocompare', tx_wc_compare_wishlist()->plugin_url() . '/assets/css/tm-woocompare.css', array( 'dashicons' ) );
		wp_register_script( 'tm-woocompare', tx_wc_compare_wishlist()->plugin_url() . '/assets/js/tm-woocompare' . $this->suffix . '.js', array( 'jquery' ), TX_WC_COMPARE_WISHLIST_VERISON, true );

		wp_register_style( 'tablesaw', tx_wc_compare_wishlist()->plugin_url() . '/assets/css/tablesaw.css', array() );
		wp_register_script( 'tablesaw', tx_wc_compare_wishlist()->plugin_url() . '/assets/js/tablesaw' . $this->suffix . '.js', array( 'jquery' ), TX_WC_COMPARE_WISHLIST_VERISON, true );

		wp_register_script( 'tablesaw-init', tx_wc_compare_wishlist()->plugin_url() . '/assets/js/tablesaw-init' . $this->suffix . '.js', array( 'tablesaw' ), TX_WC_COMPARE_WISHLIST_VERISON, true );

		wp_localize_script( 'tm-woocompare', 'tmWoocompare', array(
			'ajaxurl'     => admin_url( 'admin-ajax.php', is_ssl() ? 'https' : 'http' ),
			'compareText' => get_option( 'tx_woocompare_compare_text', __( 'Add to Compare', 'tx' ) ),
			'removeText'  => get_option( 'tx_woocompare_remove_text', __( 'Remove from Compare List', 'tx' ) ),
			'countFormat' => apply_filters( 'tx_compare_count_format', '<span class="compare-count">(%count%)</span>' )
		) );

		// TX WooWishlist
		wp_register_style( 'tm-woowishlist', tx_wc_compare_wishlist()->plugin_url() . '/assets/css/tm-woowishlist.css', array( 'dashicons' ) );
		wp_register_script( 'tm-woowishlist', tx_wc_compare_wishlist()->plugin_url() . '/assets/js/tm-woowishlist' . $this->suffix . '.js', array( 'jquery' ), TX_WC_COMPARE_WISHLIST_VERISON, true );

		wp_localize_script( 'tm-woowishlist', 'tmWoowishlist', array(
			'ajaxurl'   => admin_url( 'admin-ajax.php', is_ssl() ? 'https' : 'http' ),
			'addText'   => get_option( 'tx_woowishlist_wishlist_text', __( 'Add to Wishlist', 'tx' ) ),
			'addedText' => get_option( 'tx_woowishlist_added_text', __( 'Added to Wishlist', 'tx' ) )
		) );
	}

	public function plugin_url() {

		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 * @return string
	 */
	public function plugin_dir( $path = null ) {

		if ( ! $this->plugin_dir ) {

			$this->plugin_dir = trailingslashit( plugin_dir_path( __FILE__ ) );
		}
		return $this->plugin_dir . $path;
	}
	/*
	public function tx_wc_compare_wishlist_install() {

		require_once 'includes/install.php';

		TX_WC_Compare_Wishlist_Install()->init();
	}
	*/
	public function get_loader() {

		if ( is_null( $this->loader ) ) {

			$loader = '<svg width="60px" height="60px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="uil-ring-alt"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><circle cx="50" cy="50" r="40" stroke="#afafb7" fill="none" stroke-width="10" stroke-linecap="round"></circle><circle cx="50" cy="50" r="40" stroke="#5cffd6" fill="none" stroke-width="6" stroke-linecap="round"><animate attributeName="stroke-dashoffset" dur="2s" repeatCount="indefinite" from="0" to="502"></animate><animate attributeName="stroke-dasharray" dur="2s" repeatCount="indefinite" values="150.6 100.4;1 250;150.6 100.4"></animate></circle></svg>';

			$this->loader = '<div class="tm-wc-compare-wishlist-loader">' . apply_filters( 'tx_wc_compare_wishlist_loader', $loader ) . '</div>';
		}
		return $this->loader;
	}

	public function build_html_dataattributes( $atts ) {

		$data_atts = '';

		if( is_array( $atts ) && ! empty( $atts ) ) {

			foreach ( $atts as $key => $attribute ) {

				$data_atts .= ' data-' . $key . '="' . $attribute . '"';
			}
		}
		return $data_atts;
	}

	public function get_original_product_id( $id ) {

		global $sitepress;

		if( isset( $sitepress ) ) {

			$id = icl_object_id($id, 'product', true, $sitepress->get_default_language());
		}
		return $id;
	}
}

function tx_wc_compare_wishlist() {

	return TX_WC_Compare_Wishlist::instance();
}

tx_wc_compare_wishlist();