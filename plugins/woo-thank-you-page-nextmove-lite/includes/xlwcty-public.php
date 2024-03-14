<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class xlwcty
 * @package NextMove
 * @author XlPlugins
 */
class xlwcty {

	public static $extend = array();
	private static $ins = null;
	private static $_registered_entity = array(
		'active'   => array(),
		'inactive' => array(),
	);
	public $xlwcty_data = array();
	public $wp_loaded = false;
	public $xl_gtag_rendered = false;
	public $loop_thank_you_pages = array();
	public $all_thank_you_pages = array();
	public $is_mini_cart = false;
	public $deals = array();
	public $goals = array();
	public $single_thank_you_page = array();
	public $current_cart_item = null;
	public $single_product_css = array();
	public $product_obj = array();
	public $thank_you_page_goal = array();
	public $is_preview = false;
	public $header_info = array();
	public $xlwcty_is_thankyou = false;
	public $social_setting = array(
		'fb' => array(
			'appId'   => '',
			'version' => 'v2.9',
			'status'  => true,
			'cookie'  => true,
			'xfbml'   => true,
			'oauth'   => true,
		),
	);

	public function __construct() {
		/**
		 * Initiating hooks
		 */
		add_action( 'xlwcty_loaded', array( $this, 'init' ) );
	}

	/**
	 * Getting class instance
	 * @return null|xlwcty
	 */
	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	/**
	 * Initialize hooks and setup core class to run front end functionality
	 */
	public function init() {
		/**
		 * Hook to modify order received url that matches criteria
		 */
		add_filter( 'woocommerce_get_checkout_order_received_url', array( $this, 'redirect_to_thankyou' ), 99, 2 );
		/**
		 * Hooks for data setup while loading thank you page
		 */
		add_action( 'wp', array( XLWCTY_Core()->data, 'setup_options' ), 1 );
		add_action( 'wp', array( $this, 'validate_preview' ), 1 );
		add_action( 'wp', array( $this, 'maybe_preview_load' ), 1 );
		add_action( 'wp', array( $this, 'validate_request' ), 9 );
		add_action( 'wp', array( XLWCTY_Core()->data, 'load_order_wp' ), 10 );
		add_action( 'wp', array( $this, 'validate_order' ), 11 );
		add_action( 'wp', array( XLWCTY_Core()->data, 'set_page' ), 12 );
		add_action( 'wp', array( XLWCTY_Core()->data, 'load_thankyou_metadata' ), 13 );
		add_action( 'wp', array( $this, 'is_xlwcty_page' ), 14 );
		add_action( 'parse_request', array( $this, 'maybe_set_query_var' ), 15 );
		/**
		 * Adding wc native thankyou hooks
		 */
		add_action( 'wp_footer', array( $this, 'execute_wc_thankyou_hooks' ), 1 );
		/**
		 * Enqueue necessary scripts
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'component_script' ), 9999 );

		add_action( 'wp_head', array( $this, 'enqueue_all_css' ) );
		add_action( 'wp_head', array( $this, 'xl_render_ga' ) );
		add_action( 'wp_footer', array( $this, 'print_html_header_info' ), 50 );
		add_action( 'wp_footer', array( $this, 'maybe_add_info_footer' ) );
		add_action( 'xlwcty_before_page_render', array( $this, 'register_hooks' ) );
		add_action( 'xlwcty_after_page_render', array( $this, 'de_register_hooks' ) );
		add_filter( 'xlwcty_the_content', array( 'XLWCTY_Common', 'maype_parse_merge_tags' ) );
		add_filter( 'xlwcty_the_content', 'wptexturize' );
		add_filter( 'xlwcty_the_content', 'convert_smilies', 20 );
		add_filter( 'xlwcty_the_content', 'wpautop' );
		add_filter( 'xlwcty_the_content', 'shortcode_unautop' );
		add_filter( 'xlwcty_the_content', 'prepend_attachment' );

		add_filter( 'xlwcty_parse_shortcode', 'do_shortcode', 11 );

		add_action( 'woocommerce_thankyou', array( $this, 'facebook_pixel_tracking_script' ) );

		add_filter( 'woocommerce_is_checkout', array( $this, 'declare_wc_checkout_page' ) );
		add_action( 'wp', array( $this, 'maybe_pass_no_cache_header' ), 15 );
		add_action( 'wp_footer', array( $this, 'maybe_push_script_for_map_check' ) );
		add_filter( 'woocommerce_is_order_received_page', array( $this, 'declare_wc_order_received_page' ) );

		//Detection of klarna gateways and redirect to out thankyou pages
		add_action( 'parse_request', array( $this, 'parse_request_for_thankyou' ), 1 );
		add_action( 'parse_query', array( $this, 'parse_query_for_thankyou' ), 11 );
		add_filter( 'body_class', array( $this, 'add_body_class' ), 100, 2 );

		// setting nextmove page meta in case of any theme to make page full width
		add_action( 'template_redirect', array( $this, 'maybe_set_meta_to_hide_sidebar' ), 20 );

		add_action( 'wp_head', array( $this, 'xlwcty_page_noindex' ) );

		// remove other languages options
		add_filter( 'icl_post_alternative_languages', array( $this, 'post_alternative_languages' ) );
	}

	public function post_alternative_languages( $output ) {
		if ( $this->xlwcty_is_thankyou ) {
			$output = null;
		}

		return $output;
	}

	public function enqueue_all_css() {
		$css              = XLWCTY_Component::get_css();
		$default_settings = XLWCTY_Core()->data->get_option();
		$output           = '';
		if ( is_array( $css ) && count( $css ) > 0 ) {
			ob_start();
			echo "<style>\n";
			if ( isset( $default_settings['wrap_left_right_padding'] ) && (int) $default_settings['wrap_left_right_padding'] >= 0 ) {
				echo '.xlwcty_wrap{padding:0 ' . (int) $default_settings['wrap_left_right_padding'] . 'px;}';
			}

			foreach ( $css as $comp => $comp_css ) {
				echo "/*{$comp}*/\n";
				if ( is_array( $comp_css ) && count( $comp_css ) > 0 ) {
					foreach ( $comp_css as $elem => $single_css ) {
						echo $elem . '{';
						if ( is_array( $single_css ) && count( $single_css ) > 0 ) {
							foreach ( $single_css as $css_prop => $css_val ) {
								echo $css_prop . ':' . $css_val . ';';
							}
						}
						echo "}\n";
					}
				}
			}
			echo '</style>';
			$output = ob_get_clean();
		}
		echo $output;
	}

	public function xl_render_ga() {
		$ga_ids = XLWCTY_Core()->data->get_option( 'ga_analytics_id' );
		if ( empty( $ga_ids ) ) {
			return;
		}
		$this->xli_render_gad( $ga_ids );
	}

	public function xli_render_gad( $ga_ids ) {
		$get_tracking_codes = explode( ",", $ga_ids );
		?>
        <script>
            (function (window, document, src) {
                var a = document.createElement('script'),
                    m = document.getElementsByTagName('script')[0];
                a.async = 1;
                a.src = src;
                m.parentNode.insertBefore(a, m);
            })(window, document, '//www.googletagmanager.com/gtag/js?id=<?php echo esc_js( trim( $get_tracking_codes[0] ) ); ?>');

            window.dataLayer = window.dataLayer || [];
            window.gtag = window.gtag || function gtag() {
                dataLayer.push(arguments);
            };

            gtag('js', new Date());
        </script>
		<?php
	}

	/**
	 * Setup thank-you page post and get new order-received link for the new order
	 *
	 * @param string $url
	 * @param WC_Order $order
	 *
	 * @return mixed|void Modified URL on success , default otherwise
	 */
	public function redirect_to_thankyou( $url, $order ) {
		$default_settings = XLWCTY_Core()->data->get_option();
		if ( isset( $default_settings['xlwcty_preview_mode'] ) && ( 'sandbox' == $default_settings['xlwcty_preview_mode'] ) ) {
			return $url;
		}
		$external_thankyou_url = apply_filters( 'xlwcty_redirect_to_thankyou', false, $url, $order );
		if ( false !== $external_thankyou_url ) {
			$external_thankyou_url = trim( $external_thankyou_url );
			$external_thankyou_url = wp_specialchars_decode( $external_thankyou_url );

			return $external_thankyou_url;
		} else {
			$order_id = XLWCTY_Compatibility::get_order_id( $order );
			if ( 0 != $order_id ) {
				$get_link = XLWCTY_Core()->data->setup_thankyou_post( XLWCTY_Compatibility::get_order_id( $order ), $this->is_preview )->get_page_link();
				if ( false !== $get_link ) {
					$get_link = trim( $get_link );
					$get_link = wp_specialchars_decode( $get_link );

					return ( XLWCTY_Common::prepare_single_post_url( $get_link, $order ) );
				}
			}
		}

		return $url;
	}

	public function component_script() {
		wp_enqueue_script( 'jquery' );
		if ( ! $this->is_xlwcty_page() ) {
			$localize = array(
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'version'    => XLWCTY_VERSION,
				'wc_version' => WC()->version,
			);
			wp_localize_script( 'jquery', 'xlwcty', apply_filters( 'xlwcty_localize_js_data', $localize ) );

			return;
		}

		$plugin_url = untrailingslashit( plugin_dir_url( XLWCTY_PLUGIN_FILE ) );
		$script_min = '.min';
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true ) {
			$script_min = '';
		}
		$fb_app_id                           = XLWCTY_Core()->data->get_option( 'fb_app_id' );
		$this->social_setting['fb']['appId'] = $fb_app_id;
		$google_map_api                      = XLWCTY_Core()->data->get_option( 'google_map_api' );

		wp_enqueue_script( 'xlwcty-component-script', $plugin_url . '/assets/js/xlwcty-public' . $script_min . '.js', array(), false, true );
		wp_enqueue_style( 'xlwcty-components-css', $plugin_url . '/assets/css/xlwcty-public' . $script_min . '.css', false );
		if ( is_rtl() ) {
			wp_enqueue_style( 'xlwcty-components-css-rtl', $plugin_url . '/assets/css/xlwcty-public-rtl.css', false );
		}
		wp_enqueue_style( 'xlwcty-faicon', $plugin_url . '/assets/fonts/fa.css', false );
		$localize = array(
			'ajax_url'       => admin_url( 'admin-ajax.php' ),
			'plugin_url'     => $plugin_url,
			'social'         => $this->social_setting,
			'google_map_key' => $google_map_api,
			'version'        => XLWCTY_VERSION,
			'wc_version'     => WC()->version,
			'infobubble_url' => $plugin_url . '/assets/js/xlwcty-infobubble' . $script_min . '.js',
			'cp'             => 0,
			'or'             => 0,
		);
		$order    = XLWCTY_Core()->data->get_order();
		if ( $order instanceof WC_Order ) {
			$localize['cp'] = get_the_ID();
			$localize['or'] = XLWCTY_Compatibility::get_order_id( $order );
		}
		$localize['settings']               = XLWCTY_Core()->data->get_option();
		$localize['map_errors']             = array(
			'error'          => __( 'Unable to process the request.', 'woo-thank-you-page-nextmove-lite' ),
			'over_limit'     => __( 'Google Map API quota limit reached.', 'woo-thank-you-page-nextmove-lite' ),
			'request_denied' => __( 'This API project is not authorized to use this API. Please ensure that this API is activated in the APIs Console.', 'woo-thank-you-page-nextmove-lite' ),
		);
		$localize['settings']['is_preview'] = ( true === $this->is_preview ) ? 'yes' : 'no';
		wp_localize_script( 'xlwcty-component-script', 'xlwcty', apply_filters( 'xlwcty_localize_js_data', $localize ) );
	}

	/**
	 * Checks whether its our page or not
	 * @return bool
	 */
	public function is_xlwcty_page() {
		return $this->xlwcty_is_thankyou;
	}

	/**
	 * Hooked over shortcode 'xlwcty_load'
	 * Includes layout files
	 *
	 * @param array $attrs
	 *
	 * @return string|void
	 */
	public function maybe_render_elements( $attrs = array() ) {
		if ( ! $this->is_xlwcty_page() ) {
			return;
		}
		if ( ! XLWCTY_Core()->data->get_order() instanceof WC_Order ) {
			return;
		}
		$order_id = XLWCTY_Compatibility::get_order_id( XLWCTY_Core()->data->get_order() );
		do_action( 'xlwcty_before_page_render' );
		$this->add_header_logs( sprintf( 'Order: #%s', $order_id ) );
		$this->add_header_logs( sprintf( 'Page: %s', '<a target="_blank" href="' . XLWCTY_Common::get_builder_link( XLWCTY_Core()->data->get_page() ) . '">' . get_the_title() . '</a>' ) );
		ob_start();
		$this->include_template();
		do_action( 'xlwcty_aftr_page_render' );
		do_action( 'xlwcty_nextmove_thankyou_page', $order_id );

		return ob_get_clean();
	}

	public function add_header_logs( $string ) {
		if ( ! in_array( $string, $this->header_info ) ) {
			array_push( $this->header_info, $string );
		}
	}

	/**
	 * Includes template file bases on chosen layout
	 */
	public function include_template() {
		$get_layout = XLWCTY_Core()->data->get_layout();
		if ( empty( $get_layout ) ) {
			return;
		}

		$file_data = get_file_data( plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'templates/' . $get_layout . '.php', array( 'XLWCTY Template Name' ) );
		if ( ! empty( $file_data ) ) {
			$this->add_header_logs( sprintf( 'Template: %s', $file_data[0] ) );
		}
		include plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'templates/' . $get_layout . '.php';

		if ( isset( $_REQUEST['order_id'] ) && ! empty( $_REQUEST['order_id'] ) ) {
			$order = wc_get_order( $_REQUEST['order_id'] );
			if ( $order instanceof WC_Order ) {
				$order->update_meta_data( '_xlwcty_thankyou_page', get_the_ID() );
				$order->save();
			}
		}
	}

	/**
	 * Renders a section of a layout
	 * Usually called by the templates so that specific section renders
	 *
	 * @param string $layout layout to call
	 * @param string $section section to render
	 *
	 * @return string
	 * @see xlwcty::include_template()
	 */
	public function render( $layout = 'basic', $section = 'first' ) {
		try {
			$get_layout_data = XLWCTY_Core()->data->get_layout_info();
			if ( isset( $get_layout_data[ $layout ] ) && isset( $get_layout_data[ $layout ][ $section ] ) && is_array( $get_layout_data[ $layout ][ $section ] ) ) {
				foreach ( $get_layout_data[ $layout ][ $section ] as $components ) {
					if ( isset( $components['component'] ) ) {
						XLWCTY_Components::get_components( $components['component'] )->render_view( $components['slug'] );
					} else {
						XLWCTY_Components::get_components( $components['slug'] )->render_view( $components['slug'] );
					}
				}
			}
		} catch ( Exception $ex ) {
			echo '';
		}
	}

	public function validate_request() {
		if ( is_singular( XLWCTY_Common::get_thank_you_page_post_type_slug() ) && $this->is_preview === false && ( is_null( filter_input( INPUT_GET, 'order_id' ) ) || is_null( filter_input( INPUT_GET, 'key' ) ) ) ) {
			if ( filter_input( INPUT_GET, 'permalink_check' ) === 'yes' ) {
				wp_send_json( array(
					'status' => 'success',
				) );
			}
			if ( ! isset( $_REQUEST['elementor-preview'] ) ) {
				wp_redirect( home_url() );
			}
		}
	}

	public function validate_preview() {
		if ( isset( $_REQUEST['elementor-preview'] ) ) {
			return;
		}

		global $post;
		if ( ! is_singular( XLWCTY_Common::get_thank_you_page_post_type_slug() ) || ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}
		if ( filter_input( INPUT_GET, 'order_id' ) === null ) {
			/**
			 * case where we do not get order_id
			 */
			$get_chosen_order_meta = get_post_meta( $post->ID, '_xlwcty_chosen_order_preview', true );
			if ( $get_chosen_order_meta === '' ) {
				$allowed_status = XLWCTY_Core()->data->get_option( 'allowed_order_statuses' );
				$args           = array(
					'status'    => $allowed_status,
					'post_type' => 'shop_order',
					'limit'     => 1,
				);
				$get_orders     = wc_get_orders( $args );
				if ( is_array( $get_orders ) && count( $get_orders ) === 0 ) {
					wp_die( __( 'We are unable to show preview for this thank you page.', 'woo-thank-you-page-nextmove-lite' ) );
				} else {
					$current_order = current( $get_orders );
					$link          = add_query_arg( array(
						'order_id' => XLWCTY_Compatibility::get_order_id( $current_order ),
						'key'      => XLWCTY_Compatibility::get_order_data( $current_order, 'order_key' ),
						'mode'     => 'preview',
					), get_permalink( $post ) );

					$link = apply_filters( 'xlwcty_redirect_preview_link', $link );

					wp_safe_redirect( $link );
					exit;
				}
			} else {
				$get_chosen_order = wc_get_order( $get_chosen_order_meta );
				$link             = add_query_arg( array(
					'order_id' => $get_chosen_order_meta,
					'key'      => XLWCTY_Compatibility::get_order_data( $get_chosen_order, 'order_key' ),
					'mode'     => 'preview',
				), get_permalink( $post ) );

				$link = apply_filters( 'xlwcty_redirect_preview_link', $link );

				wp_safe_redirect( $link );
				exit;
			}
		}
	}

	/**
	 * Checking query arguments and validating preview mode
	 */
	public function maybe_preview_load() {
		global $post;
		if ( is_singular( XLWCTY_Common::get_thank_you_page_post_type_slug() ) && filter_input( INPUT_GET, 'mode' ) === 'preview' ) {
			/**
			 * Allowing theme and plugins to allow preview before it checks to user capability
			 */
			$this->is_preview = apply_filters( 'xlwcty_allow_preview', $this->is_preview );
			/**
			 * Checking user capability
			 */
			if ( $this->is_preview === false && ! current_user_can( 'manage_woocommerce' ) ) {
				wp_die( 'You are not allowed to access this page. ' );
			}
			$this->is_preview = true;
		}
	}

	/**
	 * Validates current order and checks if order qualifies for the current loading
	 * loads native thank you page if order don't qualify
	 * @uses WC_Order::get_checkout_order_received_url()
	 * @uses WC_Order::post_status
	 */
	public function validate_order() {
		global $post;
		$order = XLWCTY_Core()->data->get_order();

		if ( ! $order instanceof WC_Order ) {
			return;
		}
		/**
		 * Check order key from URL so that users cannot open other's thank you page
		 */
		$order_key = XLWCTY_Compatibility::get_order_data( $order, 'order_key' );

		$check_for_empty_key = apply_filters( 'xlwcty_check_for_empty_order_key', true );
		if ( $check_for_empty_key ) {
			/** empty key than redirect to home page **/
			if ( empty( filter_input( INPUT_GET, 'key' ) ) ) {
				wp_redirect( home_url() );
				exit;
			}
		}

		if ( filter_input( INPUT_GET, 'key' ) !== $order_key ) {

			if ( XLWCTY_Common::get_thank_you_page_post_type_slug() === $post->post_type ) {
				wp_die( __( 'Unable to process your request.', 'woo-thank-you-page-nextmove-lite' ) );
			}

			XLWCTY_Core()->data->reset_order();

			return;
		}

		$current_order_status = XLWCTY_Compatibility::get_order_status( $order );

		/**
		 * Check for $this->xlwcty_is_thankyou added to redirect to thank you page only if it's NextMove thank you page or leave as it is.
		 * This check is added as it causes conflict with upstroke plugin because it changes the order status which can be the case with any third party plugin as well.
		 */
		if ( ! in_array( $current_order_status, XLWCTY_Core()->data->get_option( 'allowed_order_statuses' ), true ) && true === $this->xlwcty_is_thankyou ) {
			/**
			 * Removing our filter so that it would not modify order_received_url when we fetch it
			 */
			if ( strpos( $current_order_status, 'cancelled' ) === false ) {
				remove_filter( 'woocommerce_get_checkout_order_received_url', array(
					$this,
					'redirect_to_thankyou',
				), 99, 2 );
				$url = $order->get_checkout_order_received_url();

				wp_safe_redirect( $url );
				exit;
			}
		}
	}

	/**
	 * Hooked over `wp_footer`
	 * Trying and executing wc native thankyou hooks
	 * Payment Gateways and other plugin usually use these hooks to read order data and process
	 * Also removes native woocommerce_order_details_table() to prevent order table load
	 */
	public function execute_wc_thankyou_hooks() {
		if ( ! $this->is_xlwcty_page() ) {
			return;
		}
		if ( ! XLWCTY_Core()->data->get_order() instanceof WC_Order ) {
			return;
		}
		$order = XLWCTY_Core()->data->get_order();
		remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
		$payment_method = XLWCTY_Compatibility::get_order_data( $order, 'payment_method' )
		?>
        <div class="xlwcty_wc_thankyou" style="display: none; opacity: 0">
			<?php
			do_action( 'woocommerce_thankyou', XLWCTY_Compatibility::get_order_id( $order ) );
			do_action( "woocommerce_thankyou_{$payment_method}", XLWCTY_Compatibility::get_order_id( $order ) );
			?>
        </div>
		<?php
	}

	public function print_html_header_info() {
		ob_start();
		if ( $this->header_info && count( $this->header_info ) > 0 ) {
			foreach ( $this->header_info as $key => $info_row ) {
				?>
                <li id="wp-admin-bar-xlwcty_admin_page_node_<?php echo $key; ?>">
					<span class="ab-item">
						<?php echo $info_row; ?>
					</span>
                </li>
				<?php
			}
		}
		echo "<div class='xlwcty_header_passed' style='display: none;'>" . ob_get_clean() . '</div>';
	}

	/**
	 * Adding Script data to help in debug what campaign is ON for that product.
	 * Using WordPress way to localize a script
	 * @see WP_Scripts::localize()
	 */
	public function maybe_add_info_footer() {
		$l10n = array();
		if ( $this->header_info && count( $this->header_info ) > 0 ) {
			foreach ( (array) $this->header_info as $key => $value ) {
				if ( ! is_scalar( $value ) ) {
					continue;
				}
				$l10n[ $key ] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8' );
			}
		}
		$script = 'var xlwcty_info = ' . wp_json_encode( $l10n ) . ';';
		?>
        <script type="text/javascript">
			<?php echo $script; ?>
        </script>
		<?php
	}

	public function register_hooks() {
		add_filter( 'woocommerce_short_description', array( $this, 'woocommerce_short_desc_limit_words' ), 99 );
		add_filter( 'woocommerce_product_get_short_description', array(
			$this,
			'woocommerce_short_desc_limit_words',
		), 99 );
	}

	public function de_register_hooks() {
		remove_filter( 'woocommerce_short_description', array( $this, 'woocommerce_short_desc_limit_words' ), 99 );
		remove_filter( 'woocommerce_product_get_short_description', array(
			$this,
			'woocommerce_short_desc_limit_words',
		), 99 );
	}

	public function woocommerce_short_desc_limit_words( $excerpt ) {
		return '<p>' . wp_trim_words( $excerpt, 30 ) . '</p>';
	}

	public function add_body_class( $classes, $class ) {
		global $post, $xlwcty_is_thankyou;
		$nm_slug = XLWCTY_Common::get_thank_you_page_post_type_slug();
		if ( ! is_singular( $nm_slug ) ) {
			return $classes;
		}
		if ( false === $xlwcty_is_thankyou ) {
			return $classes;
		}
		if ( is_array( $classes ) && count( $classes ) > 0 ) {
			$post_type = 'page';
			$classes[] = $post_type;
			$classes[] = "{$post_type}-template";

			$template_slug = get_page_template_slug( $post->ID );
			if ( empty( $template_slug ) ) {
				$template_parts[0] = 'default';
			} else {
				$template_parts = explode( '/', $template_slug );
			}
			foreach ( $template_parts as $part ) {
				$classes[] = "{$post_type}-template-" . sanitize_html_class( str_replace( array(
						'.',
						'/',
					), '-', basename( $part, '.php' ) ) );
			}
			$classes[] = "{$post_type}-template-" . sanitize_html_class( str_replace( '.', '-', $template_slug ) );
		}

		return $classes;
	}

	public function facebook_pixel_tracking_script( $order_id ) {
		include __DIR__ . '/google-facebook-ecommerce.php';
	}

	public function facebook_pixel_enabled() {
		$facebook_enable = XLWCTY_Core()->data->get_option( 'enable_fb_ecom_tracking' );
		$facebook_id     = XLWCTY_Core()->data->get_option( 'ga_fb_pixel_id' );

		if ( $facebook_enable === 'on' && $facebook_id > 0 ) {
			return $facebook_id;
		}

		return false;
	}

	public function google_analytics_enabled() {
		$analytic_enable = XLWCTY_Core()->data->get_option( 'enable_ga_ecom_tracking' );
		$analytic_id     = XLWCTY_Core()->data->get_option( 'ga_analytics_id' );
		if ( $analytic_enable === 'on' && ! empty( $analytic_id ) ) {
			return $analytic_id;
		}

		return false;
	}

	public function declare_wc_checkout_page( $bool ) {
		if ( $this->is_xlwcty_page() === true ) {
			return true;
		}

		return $bool;
	}

	public function maybe_pass_no_cache_header() {
		if ( $this->is_xlwcty_page() ) {
			$this->set_nocache_constants();
			nocache_headers();
		}
	}

	/**
	 * @param $value
	 *
	 * @return mixed
	 */
	public function set_nocache_constants() {
		$this->maybe_define_constant( 'DONOTCACHEPAGE', true );
		$this->maybe_define_constant( 'DONOTCACHEOBJECT', true );
		$this->maybe_define_constant( 'DONOTCACHEDB', true );

		return null;
	}

	function maybe_define_constant( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}


	public function maybe_push_script_for_map_check() {
		if ( $this->is_xlwcty_page() === false ) {
			return;
		}
		?>
        <script>
            var xlwcty_is_google_map_failed = false;
            if (typeof gm_authFailure !== 'function ') {
                function gm_authFailure() {
                    console.log('Google map error found');
                    xlwcty_is_google_map_failed = true;
                    xlwctyCore.loadmap();
                }
            }
        </script>
		<?php
	}

	public function maybe_set_query_var( $wp_query_obj ) {
		if ( false === $this->is_xlwcty_page() ) {
			return;
		}

		$get_order_id = filter_input( INPUT_GET, 'order_id' );
		if ( $get_order_id === null ) {
			return;
		}
		$wp_query_obj->query_vars['order-received'] = $get_order_id;
		set_query_var( 'order-received', $get_order_id );
	}

	public function declare_wc_order_received_page( $bool ) {
		if ( $this->is_xlwcty_page() === true ) {
			return true;
		}

		return $bool;
	}

	public function parse_request_for_thankyou( $wp_query_obj ) {
		if ( isset( $wp_query_obj->query_vars['post_type'] ) && ( XLWCTY_Common::get_thank_you_page_post_type_slug() === $wp_query_obj->query_vars['post_type'] ) ) {
			$this->xlwcty_is_thankyou = true;
		}
	}

	public function parse_query_for_thankyou( $wp_query_obj ) {
		if ( $this->is_xlwcty_page() && $wp_query_obj->is_main_query() ) {
			$wp_query_obj->is_page   = true;
			$wp_query_obj->is_single = false;
		}
	}

	/**
	 * Perform any changes on NextMove Thank You page only
	 * xlwcty-themes-helper functions working on it.
	 */
	public function maybe_set_meta_to_hide_sidebar() {
		global $post;
		if ( $this->is_xlwcty_page() && $post instanceof WP_Post ) {
			do_action( 'nextmove_template_redirect_single_thankyou_page' );
		}
	}

	public function xlwcty_page_noindex() {
		$post_type = XLWCTY_Common::get_thank_you_page_post_type_slug();
		if ( is_singular( $post_type ) ) {
			echo "<meta name='robots' content='noindex,follow' />\n";
		}
	}
}

if ( class_exists( 'XLWCTY_Core' ) ) {
	XLWCTY_Core::register( 'public', 'xlwcty' );
}
