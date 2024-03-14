<?php
/**
 * Searchanise search results
 *
 * @package Searchanise/SearchResults
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Searchanise search results class
 */
class Search_Results {

	const ADD_TO_CART_STATUS_SUCCESS = 'OK';
	const ADD_TO_CART_BAD_REQUEST    = 'BAD_REQUEST';
	const ADD_TO_CART_FAILED         = 'FAILED';

	const LIST_PRICE_TYPE = 'list_price';
	const MAX_PRICE_TYPE  = 'max_price';

	const REDIRECT_IF_DISABLED_SRW = true;

	/**
	 * Current language code
	 *
	 * @var string
	 */
	private $lang_code;

	/**
	 * Search results page id
	 *
	 * @var int
	 */
	private $search_results_page_id;

	/**
	 * True, if current page is Searchanise search results page
	 *
	 * @var bool
	 */
	private $is_search_results_page = false;

	/**
	 * Search results constructor
	 *
	 * @param string $lang_code Lang code.
	 */
	public function __construct( $lang_code = '' ) {
		if ( ! empty( $lang_code ) ) {
			$this->lang_code = Api::get_instance()->get_locale( $lang_code );
		}

		if (
			is_admin()
			|| (bool) filter_input( INPUT_GET, 'uxb_iframe' ) // hack for Flatsome theme UX builder.
			|| Api::get_instance()->get_module_status() != 'Y'
			|| empty( $this->lang_code )
			|| Api::get_instance()->get_api_key( $this->lang_code ) == ''
			|| ! Api::get_instance()->is_search_allowed( $this->lang_code )
		) {
			return false;
		}

		if ( self::REDIRECT_IF_DISABLED_SRW ) {
			add_action( 'template_redirect', array( $this, 'redirect_hook' ), 10, 2 );
		}

		add_action(
			'init',
			function () {
				global $post;

				// Check search results page.
				if ( is_object( $post ) ) {
					$post_id = $post->ID;
				} else {
					$post_id = isset( $_SERVER['REQUEST_URI'] ) ? url_to_postid( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) : null;
				}

				$this->search_results_page_id = Installer::create_search_results_page();
				$this->is_search_results_page = $post_id && $post_id == $this->search_results_page_id;

				if ( $this->is_search_results_page ) {
					wc_enqueue_js(
						<<<SE_SPINNER
	(function(window, undefined) {
		var sXpos = 0, sIndex = 0, sInterval = null;

		if (document.getElementById('snize_results').innerHTML != '') {
			return;
		}

		document.getElementById('snize_results').innerHTML = '<div id="snize-preload-spinner"></div>';
		sInterval = setInterval(function() {
			var spinner = document.getElementById('snize-preload-spinner');
			if (spinner) {
				document.getElementById('snize-preload-spinner').style.backgroundPosition = (- sXpos) + 'px center';
			} else {
				clearInterval(sInterval);
			}

			sXpos  += 32;
			sIndex += 1;

			if (sIndex >= 12) {
				sXpos  = 0;
				sIndex = 0;
			}
		}, 30);
	}(window));
SE_SPINNER
					);
				}
			}
		);

		add_action( 'wp_enqueue_scripts', array( $this, 'load_search_widget' ) );

		if ( Api::get_instance()->is_result_widget_enabled( $this->lang_code ) ) {
			add_filter( 'redirect_canonical', array( $this, 'disable_paged_canonical_redirect' ), 1, 10 );
			add_filter( 'pre_handle_404', array( $this, 'pre_handle404' ), 2, 10 );
		}
		add_action( 'pre_get_posts', array( $this, 'exclude_result_widget_from_search' ) );
		add_filter( 'body_class', array( $this, 'body_class' ) );
		add_action(
			'template_redirect',
			function () {
				if ( ! is_product() ) {
					return;
				}

				Api::get_instance()->set_recently_viewed_product_id( get_the_ID() );
			}
		);
	}

	/**
	 * Filters whether to short-circuit default header status handling.
	 *
	 * Returning a non-false value from the filter will short-circuit the handling
	 * and return early.
	 *
	 * @since 4.5.0
	 *
	 * @param bool     $preempt  Whether to short-circuit default header status handling. Default false.
	 * @param WP_Query $wp_query WordPress Query object.
	 *
	 * @return bool
	 */
	public function pre_handle404( $preempt, $wp_query ) {
		$page_num = (int) filter_input( INPUT_GET, 'page' );

		// Wordpress doesn't support the <!--nextpage--> pagination for posts and generates 404 error.
		// So, we have to skip this error for search results page.
		return $this->is_search_results_page && $page_num > 0 ? true : $preempt;
	}

	/**
	 * Filters the canonical redirect URL.
	 *
	 * Returning false to this filter will cancel the redirect.
	 *
	 * @since 2.3.0
	 *
	 * @param string $redirect_url  The redirect URL.
	 * @param string $requested_url The requested URL.
	 *
	 * @return string
	 */
	public function disable_paged_canonical_redirect( $redirect_url, $requested_url ) {
		if ( $this->is_search_results_page ) {
			$page_num = (int) filter_input( INPUT_GET, 'page' );

			if ( $page_num > 0 ) {
				// Do not perform any redirection if page param in query.
				return false;
			}
		}

		return $redirect_url;
	}

	/**
	 * Adds Searchanise class to body tag in search results page
	 *
	 * @param array $classes Body classes.
	 */
	public function body_class( $classes ) {
		$classes = (array) $classes;

		if ( Api::get_instance()->is_result_widget_enabled( $this->lang_code ) ) {
			if ( $this->is_search_results_page ) {
				array_push( $classes, 'searchanise-search-results-page' );
			}
		}

		return array_unique( $classes );
	}

	/**
	 * Add to cart ajax controller
	 */
	public static function ajax_add_to_cart() {
		$response = array();
		$product_id = isset( $_REQUEST['product_id'] )
			? (int) $_REQUEST['product_id']
			: '';

		if ( ! empty( $product_id ) ) {
			/**
			 * Function for `woocommerce_add_to_cart_product_id` filter-hook.
			 *
			 * @since 1.0.0
			 *
			 * @param $item_product_id
			 */
			$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', (int) $product_id );
		}

		$quantity = ! isset( $_REQUEST['quantity'] )
			? 1
			: wc_stock_amount( (int) $_REQUEST['quantity'] );
		$variation_id = 0;

		if ( ! empty( $product_id ) && ! empty( $quantity ) ) {
			$product_data = wc_get_product( $product_id );

			if ( $product_data instanceof \WC_Product ) {
				// Select first available product variant.
				if ( $product_data instanceof \WC_Product_Variable ) {
					$variations = $product_data->get_available_variations();

					foreach ( $variations as $v ) {
						if ( $v['is_in_stock'] ) {
							$variation_id = $v['variation_id'];
							break;
						}
					}
				}

				/**
				 * Filters if an item being added to the cart passed validation checks.
				 *
				 * @since 1.0.0
				 *
				 * @param boolean $passed_validation True if the item passed validation.
				 * @param integer $product_id        Product ID being validated.
				 * @param integer $quantity          Quantity added to the cart.
				 */
				$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
				$product_status = get_post_status( $product_id );

				if ( $passed_validation && 'publish' == $product_status && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id ) ) {
					/**
					 * Function for `woocommerce_ajax_added_to_cart` action-hook.
					 *
					 * @since 1.0.0
					 *
					 * @param  $product_id
					 */
					do_action( 'woocommerce_ajax_added_to_cart', $product_id );

					$response = array(
						'status'   => self::ADD_TO_CART_STATUS_SUCCESS,
						'redirect' => wc_get_cart_url(),
					);

				} else {
					$response = array(
						'status' => self::ADD_TO_CART_FAILED,
					);
				}
			} else {
				$response = array(
					'status' => self::ADD_TO_CART_BAD_REQUEST,
				);
			}
		} else {
			// Incorrect parameters.
			$response = array(
				'status' => self::ADD_TO_CART_BAD_REQUEST,
			);
		}

		// Redirect to product page if add to cart not possible.
		if ( self::ADD_TO_CART_STATUS_SUCCESS != $response['status'] ) {
			/**
			 * Function for `woocommerce_cart_redirect_after_error` filter-hook.
			 *
			 * @since 1.0.0
			 *
			 * @param  $permalink
			 * @param  $product_id
			 */
			$response['redirect'] = apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id );
		}

		wp_send_json( $response );
		wp_die();
	}

	/**
	 * Exclude search result page from search
	 *
	 * @param mixed $query Search query.
	 */
	public function exclude_result_widget_from_search( $query ) {
		if (
			Api::get_instance()->is_result_widget_enabled( $this->lang_code )
			&& ! $query->is_admin
			&& $query->is_search
			&& $query->is_main_query()
		) {
			$query->set( 'post__not_in', array( $this->search_results_page_id ) );
		}
	}

	/**
	 * Returns search results page url
	 *
	 * @return string
	 */
	private function get_search_result_page_url() {
		if ( ! Api::get_instance()->is_result_widget_enabled( $this->lang_code ) ) {
			return '';
		}

		$se_searchanise_url = '';

		if ( ! empty( $this->search_results_page_id ) ) {
			$se_page = get_post( $this->search_results_page_id );

			if ( ! empty( $se_page ) ) {
				$se_searchanise_url = Api::get_instance()->get_language_link( get_permalink( $se_page->ID ), $this->lang_code );
			}
		}

		/**
		 * Filters Searchanise results page
		 *
		 * @since 1.0.0
		 *
		 * @param string $se_searchanise_url Page url
		 * @param string $lang_code Lang code
		 */
		return apply_filters( 'se_get_search_results_page_url', $se_searchanise_url, $this->lang_code );
	}

	/**
	 * Returns fallback url for search results
	 *
	 * @return string
	 */
	private function get_fallback_url() {
		return Api::get_instance()->get_frontend_url(
			$this->lang_code,
			array(
				'post_type' => 'product',
				's'         => '',
			)
		);
	}

	/**
	 * Returns ajax add to cart url
	 */
	private function get_add_to_cart_url() {
		return admin_url( 'admin-ajax.php' );
	}

	/**
	 * Returns currency position
	 *
	 * @return boolean
	 */
	private function get_currency_position_after() {
		$currency_pos = get_option( 'woocommerce_currency_pos' );

		switch ( $currency_pos ) {
			case 'left':
			case 'left_space':
				return false;

			case 'right':
			case 'right_space':
				return true;

			default:
				return false;
		}
	}

	/**
	 * Loads search widget assets
	 */
	public function load_search_widget() {
		$se_searchanise_url = $this->get_search_result_page_url();
		$se_widgets_file_path = SE_BASE_DIR . '/assets/js/se-widgets.js';

		$se_options = array(
			'version'                       => SE_VERSION,
			'host'                          => is_ssl() ? str_replace( 'http://', 'https://', SE_SERVICE_URL ) : SE_SERVICE_URL,
			'api_key'                       => Api::get_instance()->get_api_key( $this->lang_code ),
			/**
			 * Searchanise decimals
			 *
			 * @since 1.0.0
			 */
			'decimals'                      => apply_filters( 'se_decimals', wc_get_price_decimals() ),
			/**
			 * Searchanise decimals separator
			 *
			 * @since 1.0.0
			 */
			'decimals_separator'            => apply_filters( 'se_decimals_separator', wc_get_price_decimal_separator() ),
			/**
			 * Searchanise thousands separator
			 *
			 * @since 1.0.0
			 */
			'thousands_separator'           => apply_filters( 'se_thousands_separator', wc_get_price_thousand_separator() ),
			/**
			 * Searchanise currency symbol
			 *
			 * @since 1.0.0
			 */
			'symbol'                        => apply_filters( 'se_currency_symbol', get_woocommerce_currency_symbol() ),
			'rate'                          => Api::get_instance()->get_currency_rate(),
			'currency_position_after'       => $this->get_currency_position_after(),
			'search_input'                  => Api::get_instance()->get_search_input_selector(),
			'results_form_path'             => $se_searchanise_url,
			'results_fallback_url'          => $this->get_fallback_url(),
			'results_add_to_cart_url'       => $this->get_add_to_cart_url(),
			'hide_out_of_stock_products'    => 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ? 'Y' : 'N',
			'cur_label_for_usergroup'       => Api::get_instance()->get_cur_label_for_prices_usergroup(),
			'list_cur_label_for_usergroup'  => Api::get_instance()->get_cur_label_for_prices_usergroup( self::LIST_PRICE_TYPE ),
			'max_cur_label_for_usergroup'   => Api::get_instance()->get_cur_label_for_prices_usergroup( self::MAX_PRICE_TYPE ),
			'usergroup_ids'                 => implode( '|', Api::get_instance()->get_current_usergroup_ids() ),
			'use_wp_jquery'                 => Api::get_instance()->is_use_wp_jquery(),
			'recentlyViewedProducts'        => Api::get_instance()->get_recently_viewed_product_ids(),
			'hideEmptyPrice'                => Api::get_instance()->get_hide_empty_price(),
		);

		// Do not include search in admin toolbar.
		wc_enqueue_js( 'jQuery("#wpadminbar").find("' . Api::get_instance()->escape_javascript( Api::get_instance()->get_search_input_selector() ) . '").addClass("snize-exclude-input")' );

		// Loading css.
		wp_enqueue_style( 'se_styles', plugins_url( SE_BASE_DIR . '/assets/css/se-styles.css' ), array(), SE_PLUGIN_VERSION, false );

		/**
		 * Searchanise load search widgets
		 *
		 * @since 1.0.0
		 */
		$se_options = apply_filters( 'se_load_search_widgets', $se_options );
		wp_register_script( 'se-widgets', plugins_url( $se_widgets_file_path ), array( 'jquery' ), SE_PLUGIN_VERSION, true );
		wp_localize_script( 'se-widgets', 'SeOptions', $se_options );
		wp_enqueue_script( 'se-widgets' );

		// Refresh shopping cart.
		wc_enqueue_js(
			"jQuery(document).on('Searchanise.AddToCartSuccess', function() {
                jQuery(document.body).trigger('wc_fragment_refresh');
            });"
		);
	}

	/**
	 * Redirect for FallBackUrl if SRW disable
	 *
	 * @return void
	 */
	public function redirect_hook() {
		if ( ! Api::get_instance()->is_result_widget_enabled( $this->lang_code ) ) {
			if ( $this->is_search_results_page ) {
				header( 'Location: ' . $this->get_fallback_url() );
				exit();
			}
		}
	}
}
