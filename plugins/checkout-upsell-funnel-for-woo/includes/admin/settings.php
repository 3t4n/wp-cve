<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VICUFFW_CHECKOUT_UPSELL_FUNNEL_Admin_Settings {
	public function __construct() {
		add_action( 'wp_ajax_viwcuf_search_product_include', array( $this, 'viwcuf_search_product_include' ) );
		add_action( 'wp_ajax_viwcuf_search_product', array( $this, 'viwcuf_search_product' ) );
		add_action( 'wp_ajax_viwcuf_search_cats', array( $this, 'viwcuf_search_cats' ) );
		add_action( 'wp_ajax_viwcuf_search_coupon', array( $this, 'viwcuf_search_coupon' ) );
		add_action( 'wp_ajax_viwcuf_search_user', array( $this, 'viwcuf_search_user' ) );
	}

	public function viwcuf_search_product_include() {
		if (!check_ajax_referer('_viwcuf_settings_ob_action','nonce', false) && !check_ajax_referer('_viwcuf_settings_us_action', 'nonce', false)){
			die();
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			die();
		}
		$keyword = isset($_REQUEST['keyword']) ? sanitize_text_field($_REQUEST['keyword']) : '';
		if ( empty( $keyword ) ) {
			die();
		}
		$arg            = array(
			'post_status'    => 'publish',
			'post_type'      => array( 'product' ),
			'posts_per_page' => 50,
			's'              => $keyword

		);
		$the_query      = new WP_Query( $arg );
		$found_products = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$prd           = wc_get_product( get_the_ID() );
				$product_id    = get_the_ID();
				$product_title = get_the_title() . '(#' . $product_id . ')';
				if ( ! $prd->is_in_stock() ) {
					continue;
				}
				if ( $prd->is_type( [ 'external', 'grouped' ] ) ) {
					continue;
				}
				if ( $prd->has_child() && $prd->is_type( 'variable' ) ) {
					$product_title    .= '(#VARIABLE)';
					$product          = array( 'id' => $product_id, 'text' => $product_title );
					$found_products[] = $product;
					$product_children = $prd->get_children();
					if ( count( $product_children ) ) {
						foreach ( $product_children as $product_child ) {
							if ( villatheme_wc_version_check() ) {
								$product = array(
									'id'   => $product_child,
									'text' => get_the_title( $product_child ) . '(#' . $product_child . ')'
								);

							} else {
								$child_wc  = wc_get_product( $product_child );
								$get_atts  = $child_wc->get_variation_attributes();
								$attr_name = array_values( $get_atts )[0];
								$product   = array(
									'id'   => $product_child,
									'text' => get_the_title() . ' - ' . $attr_name
								);

							}
							$found_products[] = $product;
						}

					}
				} else {
					$product          = array( 'id' => $product_id, 'text' => $product_title );
					$found_products[] = $product;
				}
			}
		}
		wp_reset_postdata();
		wp_send_json( $found_products );
		die;
	}

	public function viwcuf_search_product() {
		if (!check_ajax_referer('_viwcuf_settings_ob_action','nonce', false) && !check_ajax_referer('_viwcuf_settings_us_action', 'nonce', false)){
			die();
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$keyword = isset($_REQUEST['keyword']) ? sanitize_text_field($_REQUEST['keyword']) : '';
		if ( empty( $keyword ) ) {
			die();
		}
		$arg            = array(
			'post_status'    => 'publish',
			'post_type'      => array( 'product' ),
			'posts_per_page' => 50,
			's'              => $keyword

		);
		$the_query      = new WP_Query( $arg );
		$found_products = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$prd           = wc_get_product( get_the_ID() );
				$product_id    = get_the_ID();
				$product_title = get_the_title() . '(#' . $product_id . ')';
				if ( ! $prd->is_in_stock() ) {
					continue;
				}

				if ( $prd->has_child() && $prd->is_type( 'variable' ) ) {
					$product_title    .= '(#VARIABLE)';
					$product          = array( 'id' => $product_id, 'text' => $product_title );
					$found_products[] = $product;
					$product_children = $prd->get_children();
					if ( count( $product_children ) ) {
						foreach ( $product_children as $product_child ) {
							if ( villatheme_wc_version_check() ) {
								$product = array(
									'id'   => $product_child,
									'text' => get_the_title( $product_child ) . '(#' . $product_child . ')'
								);

							} else {
								$child_wc  = wc_get_product( $product_child );
								$get_atts  = $child_wc->get_variation_attributes();
								$attr_name = array_values( $get_atts )[0];
								$product   = array(
									'id'   => $product_child,
									'text' => get_the_title() . ' - ' . $attr_name
								);

							}
							$found_products[] = $product;
						}

					}
				} else {
					$product          = array( 'id' => $product_id, 'text' => $product_title );
					$found_products[] = $product;
				}
			}
		}
		wp_reset_postdata();
		wp_send_json( $found_products );
		die;
	}

	public function viwcuf_search_cats() {
		if (!check_ajax_referer('_viwcuf_settings_ob_action','nonce', false) && !check_ajax_referer('_viwcuf_settings_us_action', 'nonce', false)){
			die();
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$keyword = isset($_REQUEST['keyword']) ? sanitize_text_field($_REQUEST['keyword']) : '';
		if ( empty( $keyword ) ) {
			die();
		}
		$categories = get_terms(
			array(
				'taxonomy' => 'product_cat',
				'orderby'  => 'name',
				'order'    => 'ASC',
				'search'   => $keyword,
				'number'   => 100
			)
		);
		$items      = array();
		if ( count( $categories ) ) {
			foreach ( $categories as $category ) {
				$item    = array(
					'id'   => $category->term_id,
					'text' => $category->name
				);
				$items[] = $item;
			}
		}
		wp_send_json( $items );
		die;
	}

	public function viwcuf_search_coupon() {
		if (!check_ajax_referer('_viwcuf_settings_ob_action','nonce', false) && !check_ajax_referer('_viwcuf_settings_us_action', 'nonce', false)){
			die();
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$keyword = isset($_REQUEST['keyword']) ? sanitize_text_field($_REQUEST['keyword']) : '';
		if ( empty( $keyword ) ) {
			die();
		}
		$arg       = array(
			'post_status'    => 'publish',
			'post_type'      => array( 'shop_coupon' ),
			'posts_per_page' => 50,
			's'              => $keyword

		);
		$the_query = new WP_Query( $arg );
		$items     = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$coupon_id   = get_the_ID();
				$coupon_code = wc_get_coupon_code_by_id( $coupon_id );
				$item        = array(
					'id'   => strtolower( $coupon_code ),
					'text' => $coupon_code
				);
				$items[]     = $item;
			}
		}
		wp_reset_postdata();
		wp_send_json( $items );
		die;
	}

	public function viwcuf_search_user() {
		if (!check_ajax_referer('_viwcuf_settings_ob_action','nonce', false) && !check_ajax_referer('_viwcuf_settings_us_action', 'nonce', false)){
			die();
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$keyword = isset($_REQUEST['keyword']) ? sanitize_text_field($_REQUEST['keyword']) : '';
		if ( empty( $keyword ) ) {
			die();
		}
		$arg       = array(
			'search'         => '*' . $keyword . '*',
			'search_columns' => array( 'user_login', 'user_email', 'display_name' ),
		);
		$woo_users = get_users( $arg );
		$items     = array();
		if ( $woo_users && is_array( $woo_users ) && count( $woo_users ) ) {
			foreach ( $woo_users as $user ) {
				$items[] = array(
					'id'   => $user->ID,
					'text' => $user->display_name,
				);
			}
		}
		wp_reset_postdata();
		wp_send_json( $items );
		die;
	}

	public static function remove_other_script() {
		global $wp_scripts;
		if ( isset( $wp_scripts->registered['jquery-ui-accordion'] ) ) {
			unset( $wp_scripts->registered['jquery-ui-accordion'] );
			wp_dequeue_script( 'jquery-ui-accordion' );
		}
		if ( isset( $wp_scripts->registered['accordion'] ) ) {
			unset( $wp_scripts->registered['accordion'] );
			wp_dequeue_script( 'accordion' );
		}
		$scripts = $wp_scripts->registered;
		foreach ( $scripts as $k => $script ) {
			preg_match( '/^\/wp-/i', $script->src, $result );
			if ( count( array_filter( $result ) ) ) {
				preg_match( '/^(\/wp-content\/plugins|\/wp-content\/themes)/i', $script->src, $result1 );
				if ( count( array_filter( $result1 ) ) ) {
					wp_dequeue_script( $script->handle );
				}
			} else {
				if ( $script->handle != 'query-monitor' ) {
					wp_dequeue_script( $script->handle );
				}
			}
		}
	}

	public static function enqueue_style( $handles = array(), $srcs = array(), $des = array(), $type = 'enqueue' ) {
		if ( empty( $handles ) || empty( $srcs ) ) {
			return;
		}
		$action = $type === 'enqueue' ? 'wp_enqueue_style' : 'wp_register_style';
		foreach ( $handles as $i => $handle ) {
			if ( ! $handle || empty( $srcs[ $i ] ) ) {
				continue;
			}
			$action( $handle, VICUFFW_CHECKOUT_UPSELL_FUNNEL_CSS . $srcs[ $i ], $des[ $i ] ?? array(), VICUFFW_CHECKOUT_UPSELL_FUNNEL_VERSION );
		}
	}

	public static function enqueue_script( $handles = array(), $srcs = array(), $des = array(), $type = 'enqueue' ) {
		if ( empty( $handles ) || empty( $srcs ) ) {
			return;
		}
		$action = $type === 'enqueue' ? 'wp_enqueue_script' : 'wp_register_script';
		foreach ( $handles as $i => $handle ) {
			if ( ! $handle || empty( $srcs[ $i ] ) ) {
				continue;
			}
			$action( $handle, VICUFFW_CHECKOUT_UPSELL_FUNNEL_JS . $srcs[ $i ], $des[ $i ] ?? array( 'jquery' ), VICUFFW_CHECKOUT_UPSELL_FUNNEL_VERSION );
		}
	}

}