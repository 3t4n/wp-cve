<?php
/**
 * @package   TX WooCommerce Package
 * @author    TemplateMonster
 * @license   GPL-2.0+
 * @link      http://www.templatemonster.com/
 */

/**
 * Class for including page templates.
 *
 * @since 1.0.0
 */
class TX_WC_Compare_Wishlist_Templater {

	/**
	 * Templater macros regular expression
	 *
	 * @var string
	 */
	private $macros_regex = '/%%(.+?)%%/';

	/**
	 * Templates data to replace
	 *
	 * @var array
	 */
	private $replace_data = array();

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * Sets up needed actions/filters.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Add a filter to the template include in order to determine if the page has our template assigned and return it's path.
		add_filter( 'template_include', array( $this, 'view_template' ) );

		require_once tx_wc_compare_wishlist()->plugin_dir( 'includes/template-callbacks.php' );

		$callbacks = new TX_WC_Compare_Wishlist_Template_Callbacks();

		$data = array(
			'title'       => array( $callbacks, 'get_product_title' ),
			'image'       => array( $callbacks, 'get_product_image' ),
			'permalink'   => array( $callbacks, 'get_product_permalink' ),
			'price'       => array( $callbacks, 'get_product_price' ),
			'addtocart'   => array( $callbacks, 'get_product_add_to_cart_button' ),
			'attributes'  => array( $callbacks, 'get_product_attributes' ),
			'stockstatus' => array( $callbacks, 'get_product_stock_status' ),
		);

		/**
		 * Filters item data.
		 *
		 * @since 1.0.2
		 * @param array $data Item data.
		 * @param array $atts Attributes.
		 */
		$this->replace_data = apply_filters( 'tx_wc_compare_wishlist_templater_callbacks', $data );

	}

	/**
	 * Checks if the template is assigned to the page.
	 *
	 * @since  1.0.0
	 * @param  string $template current template name.
	 * @return string
	 */
	public function view_template( $template ) {

		$find  = array();
		$file  = '';

		return $template;
	}

	/**
	 * Returns macros regular expression.
	 *
	 * @return string
	 */
	public function macros_regex() {

		return $this->macros_regex;
	}

	public function get_replace_data() {

		return $this->replace_data;
	}

	/**
	 * Read template (static).
	 *
	 * @since  1.0.0
	 * @return bool|WP_Error|string - false on failure, stored text on success.
	 */
	public static function get_contents( $template ) {

		if ( ! function_exists( 'WP_Filesystem' ) ) {

			include_once( ABSPATH . '/wp-admin/includes/file.php' );
		}
		WP_Filesystem();

		global $wp_filesystem;

		// Check for existence.
		if ( ! $wp_filesystem->exists( $template ) ) {

			return false;
		}
		// Read the file.
		$content = $wp_filesystem->get_contents( $template );

		if ( ! $content ) {
			// Return error object.
			return new WP_Error( 'reading_error', 'Error when reading file' );
		}
		return $content;
	}

	/**
	 * Retrieve a *.tmpl file content.
	 *
	 * @since  1.0.0
	 * @param  string $template  File name.
	 * @param  string $shortcode Shortcode name.
	 * @return string
	 */
	public function get_template_by_name( $template, $shortcode ) {

		$file       = '';
		$default    = tx_wc_compare_wishlist()->plugin_dir( 'templates/shortcodes/' . $shortcode . '/default.tmpl' );
		$upload_dir = wp_upload_dir();
		$upload_dir = trailingslashit( $upload_dir['basedir'] );
		$subdir     = 'templates/shortcodes/' . $shortcode . '/' . $template;

		/**
		 * Filters a default fallback-template.
		 *
		 * @since 1.0.0
		 * @param string $content.
		 */
		$content = apply_filters( 'tx_wc_compare_wishlist_fallback_template', '' );

		if ( file_exists( $upload_dir . $subdir ) ) {

			$file = $upload_dir . $subdir;

		} elseif ( $theme_template = locate_template( array( 'woocommerce/shortcodes/' . $shortcode . '/' . $template ) ) ) {

			$file = $theme_template;

		} elseif ( file_exists( tx_wc_compare_wishlist()->plugin_dir( $subdir ) ) ) {

			$file = tx_wc_compare_wishlist()->plugin_dir( $subdir );

		} else {

			$file = $default;
		}
		if ( ! empty( $file ) ) {

			$content = self::get_contents( $file );
		}
		return $content;
	}

	/**
	 * Parse template content and replace macros with real data.
	 *
	 * @param  string $content Content to parse.
	 * @return string
	 */
	public function parse_template( $content, $atts = array() ) {

		$parsed_atts = array();

		if ( ! empty( $atts ) ) {

			foreach ( $atts as $key => $attr ) {

				$key = explode( '_', $key );

				if ( 2 == count( $key ) ) {

					$parsed_atts[$key[0]][$key[1]] = $attr;
				}
			}
		}

		$atts = $parsed_atts;

		$replace_data = $this->replace_data;

		return preg_replace_callback( $this->macros_regex(), function( $matches ) use ( $atts, $replace_data ) {

			if ( ! is_array( $matches ) || empty( $matches ) ) {

				return;
			}

			$item   = trim( $matches[0], '%%' );
			$arr    = explode( ' ', $item, 2 );
			$macros = strtolower( $arr[0] );

			if ( array_key_exists( $macros, $atts ) ) {

				$atts = isset( $arr[1] ) ? wp_parse_args( $atts[$macros], shortcode_parse_atts( $arr[1] ) ) : $atts[$macros];

			} else {

				$atts = isset( $arr[1] ) ? shortcode_parse_atts( $arr[1] ) : array();
			}
			if ( ! isset( $replace_data[ $macros ] ) ) {

				return;
			}
			$callback = $replace_data[ $macros ];

			if ( ! is_callable( $callback ) ) {

				return;
			}
			return call_user_func( $callback, $atts );

		}, $content );
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {

			self::$instance = new self;
		}
		return self::$instance;
	}
}

/**
 * Returns instance of templater class.
 *
 * @return TX_WooCommerce_Templater
 */
function tx_wc_compare_wishlist_templater() {

	return TX_WC_Compare_Wishlist_Templater::get_instance();
}

tx_wc_compare_wishlist_templater();
