<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Fami_Woocompare_Frontend' ) ) {
	class Fami_Woocompare_Frontend {
		
		/**
		 * The list of products inside the comparison table
		 *
		 * @var array
		 * @since 1.0.0
		 */
		public $products_list = array();
		
		/**
		 * The name of cookie name
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $cookie_name = 'fami_wc_compare_list';
		
		/**
		 * The action used to add the product to compare list
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $action_add = 'fami_wccp_add_product';
		
		/**
		 * The action used to add the product to compare list
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $action_remove = 'fami_wccp_remove_product';
		
		/**
		 * The action used to reload the compare list widget
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $action_reload = 'fami_wccp_reload_product';
		
		/**
		 * The action used to view the table comparison
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $action_view = 'fami_wccp_view_compare_page';
		
		/**
		 * The standard fields
		 *
		 * @var array
		 * @since 1.0.0
		 */
		public $default_fields = array();
		
		public $action_names = array();
		
		/**
		 * Constructor
		 *
		 * @return Fami_Woocompare_Frontend
		 * @since 1.0.0
		 */
		public function __construct() {
			
			$all_settings        = Fami_Woocompare_Helper::get_all_settings();
			$single_product_hook = trim( $all_settings['single_product_hook'] );
			$products_loop_hook  = trim( $all_settings['products_loop_hook'] );
			
			add_filter( 'template_include', array( $this, 'template_redirect' ) );
			
			add_action( 'init', array( $this, 'init_params' ), 1 );
			
			if ( $all_settings['show_in_single_product'] == 'yes' && $single_product_hook != '' ) {
				add_action( $single_product_hook, array( $this, 'add_compare_link' ), 65 );
			}
			
			if ( $all_settings['show_in_products_list'] == 'yes' && $products_loop_hook != '' ) {
				add_action( $products_loop_hook, array( $this, 'add_compare_link' ), 65 );
			}
			
			add_action( 'init', array( $this, 'populate_products_list' ), 10 );
			add_action( 'init', array( $this, 'add_product_to_compare_action' ), 15 );
			
			// AJAX
			add_action( 'wp_ajax_' . $this->action_add, array( $this, 'add_product_to_compare_ajax' ) );
			add_action( 'wp_ajax_' . $this->action_remove, array( $this, 'remove_product_from_compare_ajax' ) );
			add_action( 'wp_ajax_' . $this->action_reload, array( $this, 'reload_widget_list_ajax' ) );
			add_action( 'wp_ajax_fami_wccp_search_product_via_ajax', array( $this, 'search_product_via_ajax' ) );
			
			// AJAX no priv
			add_action( 'wp_ajax_nopriv_' . $this->action_add, array( $this, 'add_product_to_compare_ajax' ) );
			add_action( 'wp_ajax_nopriv_' . $this->action_remove, array( $this, 'remove_product_from_compare_ajax' ) );
			add_action( 'wp_ajax_nopriv_' . $this->action_reload, array( $this, 'reload_widget_list_ajax' ) );
			add_action( 'wp_ajax_nopriv_fami_wccp_search_product_via_ajax', array( $this, 'search_product_via_ajax' ) );
			
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
			
			return $this;
		}
		
		public function init_params() {
			global $sitepress;
			
			$lang = isset( $_REQUEST['lang'] ) ? Fami_Woocompare_Helper::clean( $_REQUEST['lang'] ) : false;
			
			if ( defined( 'ICL_LANGUAGE_CODE' ) && $lang && isset( $sitepress ) ) {
				$sitepress->switch_lang( $lang, true );
			}
			
			// set coookiename
			if ( is_multisite() ) {
				$this->cookie_name .= '_' . get_current_blog_id();
			}
			
			// populate default fields for the comparison table
			$this->default_fields = Fami_Woocompare_Helper::default_selected_compare_fields();
			
			$this->action_names = array(
				'action_add'    => $this->action_add,
				'action_remove' => $this->action_remove,
				'action_reload' => $this->action_reload,
				'action_view'   => $this->action_view,
				'cookie_name'   => $this->cookie_name
			);
		}
		
		public function frontend_scripts( $hook ) {
			$all_settings     = Fami_Woocompare_Helper::get_all_settings();
			$compare_slider   = $all_settings['compare_slider'];
			$enqueue_owl_js   = $all_settings['enqueue_owl_js'] == 'yes';
			$enqueue_slick_js = $all_settings['enqueue_slick_js'] == 'yes';
			
			if ( $compare_slider == 'owl' ) {
				if ( $enqueue_owl_js ) {
					wp_enqueue_style( 'owl-carousel', FAMI_WCP_URL . 'assets/vendors/owl/assets/owl.carousel.css' );
				}
			} else {
				if ( $enqueue_slick_js ) {
					wp_enqueue_style( 'slick', FAMI_WCP_URL . 'assets/vendors/slick/slick.css' );
				}
			}
			wp_enqueue_style( 'fwcc-flaticon', FAMI_WCP_URL . 'assets/vendors/fwcc-flaticon/fwcc-flaticon.css' );
			wp_enqueue_style( 'fami-wccp-frontend', FAMI_WCP_URL . 'assets/css/frontend.css' );
			
			if ( $compare_slider == 'owl' ) {
				if ( $enqueue_owl_js ) {
					wp_enqueue_script( 'owl-carousel', FAMI_WCP_URL . 'assets/vendors/owl/owl.carousel.min.js', array( 'jquery' ), null );
				}
			} else {
				if ( $enqueue_slick_js ) {
					wp_enqueue_script( 'slick', FAMI_WCP_URL . 'assets/vendors/slick/slick.min.js', array( 'jquery' ), null );
				}
			}
			wp_enqueue_script( 'fami-wccp-frontend', FAMI_WCP_URL . 'assets/js/frontend.js', array( 'jquery' ), null );
			
			ob_start();
			Fami_Woocompare_Helper::get_template_part( 'compare', 'products-list' );
			$products_list_tmp = ob_get_clean();
			
			ob_start();
			Fami_Woocompare_Helper::get_template_part( 'add-products', 'form' );
			$add_product_form = ob_get_clean();
			
			$fami_wccp_args = array(
				'ajaxurl'      => admin_url( 'admin-ajax.php' ),
				'security'     => wp_create_nonce( 'fami_wccp_nonce' ),
				'ajax_actions' => $this->action_names,
				'text'         => array(
					'added'   => esc_html__( 'Added to compare', 'fami-woocommerce-compare' ),
					'compare' => esc_html__( 'Compare', 'fami-woocommerce-compare' )
				),
				'template'     => array(
					'products_list'    => $products_list_tmp,
					'add_product_form' => $add_product_form
				)
			);
			
			wp_localize_script( 'fami-wccp-frontend', 'fami_wccp', $fami_wccp_args );
		}
		
		public function template_redirect( $original_template ) {
			if ( is_page() ) {
				$page_for_compare        = Fami_Woocompare_Helper::get_page( 'compare' );
				$page_id                 = get_the_ID();
				$pages_exclude           = array();
				$blog_page_id            = get_option( 'page_for_posts', 0 ); // Blog page
				$front_page_id           = get_option( 'page_on_front', 0 ); // Front page
				$page_for_privacy_policy = get_option( 'wp_page_for_privacy_policy', 0 );
				
				$pages_exclude[] = $blog_page_id;
				$pages_exclude[] = $front_page_id;
				$pages_exclude[] = $page_for_privacy_policy;
				if ( class_exists( 'WooCommerce' ) ) {
					$myaccount_page_id = wc_get_page_id( 'myaccount' );
					$shop_page_id      = wc_get_page_id( 'shop' );
					$cart_page_id      = wc_get_page_id( 'cart' );
					$checkout_page_id  = wc_get_page_id( 'checkout' );
					$terms_page_id     = wc_get_page_id( 'terms' );
					
					$pages_exclude[] = $myaccount_page_id;
					$pages_exclude[] = $shop_page_id;
					$pages_exclude[] = $cart_page_id;
					$pages_exclude[] = $checkout_page_id;
					$pages_exclude[] = $terms_page_id;
				}
				
				if ( in_array( $page_id, $pages_exclude ) ) {
					return $original_template;
				}
				
				if ( $page_id == $page_for_compare ) {
					return Fami_Woocompare_Helper::locate_template( 'compare.php' );
				}
			}
			
			return $original_template;
		}
		
		public function search_product_via_ajax() {
			
			$response = array(
				'html' => ''
			);
			if ( ! isset( $_REQUEST['search_keyword'] ) || ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != 'fami_wccp_search_product_via_ajax' ) {
				wp_send_json( $response );
				die();
			}
			
			$search_keyword = Fami_Woocompare_Helper::clean( $_REQUEST['search_keyword'] );
			
			$args     = array(
				's'     => $search_keyword,
				'limit' => 6
			);
			$products = wc_get_products( $args );
			
			if ( $products ) {
				$img_w = 90;
				$img_h = 90;
				if ( ! has_image_size( 'fami_img_size_90x90' ) ) {
					$img_w = 150;
					$img_h = 150;
				}
				foreach ( $products as $product ) {
					$product_id       = intval( $product->get_id() );
					$thumb            = Fami_Woocompare_Helper::resize_image( get_post_thumbnail_id( $product_id ), null, $img_w, $img_h, true, true, false );
					$response['html'] .= '<a href="' . esc_url( get_permalink( $product_id ) ) . '" title="' . esc_attr__( 'Add to compare', 'fami-woocommerce-compare' ) . '" class="fami-wccp-search-result-item" data-product_id="' . $product_id . '"><img width="' . esc_attr( $thumb['width'] ) . '" height="' . esc_attr( $thumb['height'] ) . '" src="' . esc_url( $thumb['url'] ) . '" /> <span class="product-name">' . $product->get_name() . '</span></a>';
					
				}
			}
			
			wp_send_json( $response );
			die();
		}
		
		/**
		 * The action called by the query string
		 */
		public function add_product_to_compare_action() {
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX || ! isset( $_REQUEST['id'] ) || ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_add ) {
				return;
			}
			
			$product_id = intval( $_REQUEST['id'] );
			$product    = $this->wc_get_product( $product_id );
			
			// don't add the product if doesn't exist
			if ( isset( $product->id ) && ! in_array( $product_id, $this->products_list ) ) {
				$this->add_product_to_compare( $product_id );
			}
			
			wp_redirect( esc_url( remove_query_arg( array( 'id', 'action' ) ) ) );
			exit();
		}
		
		/**
		 * The action called by AJAX
		 */
		public function add_product_to_compare_ajax() {
			
			if ( ! isset( $_REQUEST['id'] ) || ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_add ) {
				die();
			}
			
			$product_id     = intval( $_REQUEST['id'] );
			$include_return = Fami_Woocompare_Helper::clean( $_REQUEST['include_return'] );
			$product        = $this->wc_get_product( $product_id );
			
			// Don't add the product if doesn't exist
			if ( isset( $product->id ) && ! in_array( $product_id, $this->products_list ) ) {
				$this->add_product_to_compare( $product_id );
			}
			
			$response = array(
				'compare_page_url'   => $this->compare_page_url(),
				'list_products_html' => $this->list_products_html()
			);
			
			if ( $include_return == 'compare_table' ) {
				$response['compare_table_html'] = $this->compare_table_html();
			}
			
			wp_send_json( $response );
			die();
		}
		
		/**
		 * Add a product in the products comparison table
		 *
		 * @param int $product_id product ID to add in the comparison table
		 */
		public function add_product_to_compare( $product_id ) {
			$this->products_list[] = $product_id;
			setcookie( $this->cookie_name, json_encode( $this->products_list ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
		}
		
		/**
		 * Remove a product from the comparison table
		 *
		 * @param $product_id The product ID to remove from the comparison table
		 */
		public function remove_product_from_compare( $product_id ) {
			
			if ( $product_id == 'all' ) {
				$this->products_list = array();
			} else {
				foreach ( $this->products_list as $k => $id ) {
					if ( intval( $product_id ) == $id ) {
						unset( $this->products_list[ $k ] );
					}
				}
			}
			
			setcookie( $this->cookie_name, json_encode( array_values( $this->products_list ) ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
			
		}
		
		/**
		 * The action called by the query string
		 */
		public function remove_product_from_compare_action() {
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX || ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_remove ) {
				return;
			}
			
			$this->remove_product_from_compare( $_REQUEST['id'] );
			
			// redirect
			$redirect = esc_url( remove_query_arg( array( 'id', 'action' ) ) );
			
			if ( isset( $_REQUEST['redirect'] ) && $_REQUEST['redirect'] == 'view' ) {
				$redirect = esc_url( remove_query_arg( 'redirect', add_query_arg( 'action', $this->action_view, $redirect ) ) );
			}
			
			wp_redirect( $redirect );
			exit();
		}
		
		/**
		 * The action called by AJAX
		 */
		public function remove_product_from_compare_ajax() {
			
			if ( ! isset( $_REQUEST['id'] ) || ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_remove ) {
				die();
			}
			
			$lang = isset( $_REQUEST['lang'] ) ? Fami_Woocompare_Helper::clean( $_REQUEST['lang'] ) : false;
			
			$this->remove_product_from_compare( $_REQUEST['id'] );
			
			header( 'Content-Type: text/html; charset=utf-8' );
			
			$response_type = isset( $_REQUEST['response_type'] ) ? Fami_Woocompare_Helper::clean( $_REQUEST['response_type'] ) : '';
			
			if ( $response_type == 'product_list' ) {
				echo $this->list_products_html( $lang );
			} else {
				echo $this->compare_table_html();
			}
			
			die();
		}
		
		/**
		 * Return the list of widget table, used in AJAX
		 */
		public function reload_widget_list_ajax() {
			
			if ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != $this->action_reload ) {
				die();
			}
			
			$lang = isset( $_REQUEST['lang'] ) ? Fami_Woocompare_Helper::clean( $_REQUEST['lang'] ) : false;
			
			echo $this->list_products_html( $lang );
			die();
		}
		
		/**
		 * Populate the compare product list
		 */
		public function populate_products_list() {
			
			global $sitepress;
			
			/**
			 * WPML Support
			 */
			$lang = isset( $_REQUEST['lang'] ) ? Fami_Woocompare_Helper::clean( $_REQUEST['lang'] ) : false;
			
			// get cookie val
			$the_list = isset( $_COOKIE[ $this->cookie_name ] ) ? json_decode( $_COOKIE[ $this->cookie_name ] ) : array();
			
			// switch lang for WPML
			if ( defined( 'ICL_LANGUAGE_CODE' ) && $lang && isset( $sitepress ) ) {
				$sitepress->switch_lang( $lang, true );
			}
			
			foreach ( $the_list as $product_id ) {
				if ( function_exists( 'wpml_object_id_filter' ) ) {
					$product_id_translated = wpml_object_id_filter( $product_id, 'product', false );
					// get all product of current lang
					if ( $product_id_translated !== $product_id ) {
						continue;
					}
				}
				
				// check for deleted|private products
				$product = wc_get_product( $product_id );
				if ( ! $product ) {
					continue;
				}
				
				$this->products_list[] = $product_id;
			}
			
			return $this->products_list;
		}
		
		/**
		 * Return the array with all products and all attributes values
		 *
		 * @param mixed $products
		 *
		 * @return array The complete list of products with all attributes value
		 */
		public function get_products_list( $products = array() ) {
			$list = array();
			
			if ( empty( $products ) ) {
				$products = $this->products_list;
				if ( ! $products ) {
					$products = $this->populate_products_list();
				}
			}
			
			$fields = Fami_Woocompare_Helper::get_selected_compare_fields_with_texts();
			
			foreach ( $products as $product_id ) {
				
				/**
				 * @type object $product /WC_Product
				 */
				$product = $this->wc_get_product( $product_id );
				
				if ( ! $product ) {
					continue;
				}
				
				$product->fields = array();
				
				// custom attributes
				foreach ( $fields as $field => $name ) {
					
					switch ( $field ) {
						case 'title':
							$product->fields[ $field ] = $product->get_title();
							break;
						case 'price':
							$product->fields[ $field ] = $product->get_price_html();
							break;
						case 'image':
							$product->fields[ $field ] = intval( get_post_thumbnail_id( $product_id ) );
							break;
						case 'description':
							$description               = apply_filters( 'woocommerce_short_description', $product->get_short_description() );
							$product->fields[ $field ] = apply_filters( 'fami_wccp_woocompare_products_description', $description );
							break;
						case 'stock':
							$availability = $product->get_availability();
							if ( empty( $availability['availability'] ) ) {
								$availability['availability'] = esc_html__( 'In stock', 'fami-woocommerce-compare' );
							}
							$product->fields[ $field ] = sprintf( '<span>%s</span>', esc_html( $availability['availability'] ) );
							break;
						case 'sku':
							$sku = $product->get_sku();
							! $sku && $sku = '-';
							$product->fields[ $field ] = $sku;
							break;
						case 'weight':
							if ( $weight = $product->get_weight() ) {
								$weight = wc_format_localized_decimal( $weight ) . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) );
							} else {
								$weight = '-';
							}
							$product->fields[ $field ] = sprintf( '<span>%s</span>', esc_html( $weight ) );
							break;
						case 'dimensions':
							$dimensions = function_exists( 'wc_format_dimensions' ) ? wc_format_dimensions( $product->get_dimensions( false ) ) : $product->get_dimensions();
							! $dimensions && $dimensions = '-';
							
							$product->fields[ $field ] = sprintf( '<span>%s</span>', esc_html( $dimensions ) );
							break;
						default:
							if ( taxonomy_exists( $field ) ) {
								$product->fields[ $field ] = array();
								$terms                     = get_the_terms( $product_id, $field );
								if ( ! empty( $terms ) ) {
									foreach ( $terms as $term ) {
										$term                        = sanitize_term( $term, $field );
										$product->fields[ $field ][] = $term->name;
									}
								}
								$product->fields[ $field ] = implode( ', ', $product->fields[ $field ] );
							}
							break;
					}
				}
				
				$list[ $product_id ] = $product;
			}
			
			return $list;
		}
		
		/**
		 * The list of products as HTML list
		 */
		public function list_products_html( $lang = false ) {
			/**
			 * WPML Suppot:  Localize Ajax Call
			 */
			global $sitepress;
			
			if ( defined( 'ICL_LANGUAGE_CODE' ) && $lang && isset( $sitepress ) ) {
				$sitepress->switch_lang( $lang, true );
			}
			
			$all_settings = Fami_Woocompare_Helper::get_all_settings();
			
			$html = '';
			
			if ( empty( $this->products_list ) ) {
				$html .= '<div class="list_empty">' . esc_html__( 'No products to compare', 'fami-woocommerce-compare' ) . '</div>';
			} else {
				foreach ( $this->products_list as $product_id ) {
					/**
					 * @type object $product /WC_Product
					 */
					$product = $this->wc_get_product( $product_id );
					if ( ! $product ) {
						continue;
					}
					$thumb_hover = Fami_Woocompare_Helper::resize_image( get_post_thumbnail_id( $product_id ), 'null', $all_settings['compare_img_size_w'], $all_settings['compare_img_size_h'], true, true, false );
					$thumb       = Fami_Woocompare_Helper::resize_image( get_post_thumbnail_id( $product_id ), 'null', $all_settings['panel_img_size_w'], $all_settings['panel_img_size_h'], true, true, false );
					$html        .= '<div class="compare-item">
								<a href="' . get_permalink( $product_id ) . '" class="thumb-hover">
									<img width="' . esc_attr( $thumb_hover['width'] ) . '" height="' . esc_attr( $thumb_hover['height'] ) . '" src="' . esc_url( $thumb_hover['url'] ) . '" />
									<span class="product-title" >' . $product->get_title() . '</span>
								</a>
								<a href="' . get_permalink( $product_id ) . '" class="thumb">
									<img width="' . esc_attr( $thumb['width'] ) . '" height="' . esc_attr( $thumb['height'] ) . '" src="' . esc_url( $thumb['url'] ) . '" />
								</a>
	                            <a href="' . esc_url( $this->remove_product_url( $product_id ) ) . '"
		                           data-product_id="' . esc_attr( $product_id ) . '" class="fami-wccp-remove-product"
		                           title="' . esc_attr__( 'Remove', 'fami-woocommerce-compare' ) . '">x</a>
		                    </div>';
				}
			}
			
			if ( $html != '' ) {
				$html = '<div class="products-compare-list">' . $html . '</div>';
			}
			
			return $html;
		}
		
		/**
		 *  Add the link to compare
		 */
		public function add_compare_link( $product_id = 0, $args = array() ) {
			extract( $args );
			
			if ( ! $product_id ) {
				global $product;
				$product_id = ! is_null( $product ) ? $product->get_id() : 0;
			}
			
			// return if product doesn't exist
			if ( ! $product_id ) {
				return;
			}
			
			printf( '<a href="%s" class="%s" data-product_id="%d" rel="nofollow">%s</a>', $this->add_product_url( $product_id ), 'fami-wccp-button', $product_id, esc_html__( 'Add to compare', 'fami-woocommerce-compare' ) );
		}
		
		/**
		 * The URL to remove the product into the comparison table
		 *
		 * @param string $product_id The ID of the product to remove
		 *
		 * @return string The url to remove the product in the comparison table
		 */
		public function remove_product_url( $product_id ) {
			$url_args = array(
				'action' => $this->action_remove,
				'id'     => $product_id
			);
			
			return esc_url_raw( add_query_arg( $url_args, site_url() ) );
		}
		
		/**
		 * The URL to add the product into the comparison table
		 *
		 * @param int $product_id ID of the product to add
		 *
		 * @return string The url to add the product in the comparison table
		 */
		public function add_product_url( $product_id ) {
			$url_args = array(
				'action' => $this->action_add,
				'id'     => $product_id
			);
			
			$lang = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : false;
			if ( $lang ) {
				$url_args['lang'] = $lang;
			}
			
			return apply_filters( 'fami_wccp_add_product_url', esc_url_raw( add_query_arg( $url_args, site_url() ) ), $this->action_add );
		}
		
		/**
		 * The URL of product comparison table
		 *
		 * @param bool | int $product_id
		 *
		 * @return string The url to add the product in the comparison table
		 */
		public function compare_page_url() {
			
			$page_url = Fami_Woocompare_Helper::get_page_link( 'compare' );
			$url_args = array();
			$lang     = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : false;
			if ( $lang ) {
				$url_args['lang'] = $lang;
			}
			
			if ( $page_url == '' ) {
				$url_args = array(
					'action' => $this->action_view
				);
				$page_url = esc_url_raw( add_query_arg( $url_args, remove_query_arg( 'wc-ajax' ) ) );
			} else {
				$page_url = esc_url_raw( add_query_arg( $url_args, $page_url ) );
			}
			
			return $page_url;
		}
		
		/**
		 * Render the maintenance page
		 *
		 */
		public function compare_table_html() {
			$html = '';
			ob_start();
			Fami_Woocompare_Helper::get_template( 'compare-table.php', array( 'products_list' => $this->products_list ) );
			$html .= ob_get_clean();
			
			return $html;
		}
		
		public function wc_get_product( $product_id ) {
			$wc_get_product = function_exists( 'wc_get_product' ) ? 'wc_get_product' : 'get_product';
			
			return $wc_get_product( $product_id );
		}
		
	}
}