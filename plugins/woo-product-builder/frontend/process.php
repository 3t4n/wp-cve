<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class VI_WPRODUCTBUILDER_F_FrontEnd_Process {
	protected $data;

	/**
	 * Stores chosen attributes
	 * @var array
	 */

	public function __construct() {
		$this->settings = new VI_WPRODUCTBUILDER_F_Data();

		add_action( 'init', array( $this, 'product_builder_rewrite' ), 10, 0 );
		add_filter( 'query_vars', array( $this, 'wbs_query_var' ) );

		add_action( 'wp_footer', array( $this, 'send_email_friend' ) );

		/*Add to temp cart*/
		add_action( 'wp_loaded', array( $this, 'add_to_cart' ) );
		add_action( 'wp_head', array( $this, 'remove_product' ) );

	}

	/**
	 * Process add to cart from step and review
	 */
	public function add_to_cart() {

		if ( ! isset( $_POST['_nonce'] ) || ! isset( $_POST['woopb_id'] ) || ! $_POST['woopb_id'] ) {
			return;
		}
		$post_id = sanitize_text_field(intval( $_POST['woopb_id'] ));

		if ( wp_verify_nonce( $_POST['_nonce'], '_woopb_add_to_cart' ) ) {

			$step_id      = filter_input( INPUT_GET, 'step', FILTER_SANITIZE_NUMBER_INT );
			$quantity     = filter_input( INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT );
			$product_id   = filter_input( INPUT_POST, 'woopb-add-to-cart', FILTER_SANITIZE_NUMBER_INT );
			$variation_id = filter_input( INPUT_POST, 'variation_id', FILTER_SANITIZE_NUMBER_INT );
			$step_id      = $step_id ? $step_id : 1;

			/*Check quantity is only 1*/
			if ( ! $this->get_data( $post_id, 'enable_quantity' ) ) {
				$quantity = 1;
			}

			/*Process add to Session*/
			if ( $product_id ) {

				/*Check allow add to cart multi products*/
				$enable_multi_select = 0;
				if ( $enable_multi_select ) {
					$products = $this->settings->get_products_added( $post_id, $step_id );
				} else {
					$products = array();
				}
				if ( $variation_id ) {
					$products[ $variation_id ] = $quantity;
				} else {
					$products[ $product_id ] = $quantity;
				}
				$this->settings->set_products_added( $post_id, $products, $step_id );
				if ( ! $enable_multi_select ) {
					$tabs       = $this->get_data( $post_id, 'tab_title' );
					$count_tabs = count( $tabs );
					if ( $count_tabs > $step_id ) {
						$url = add_query_arg( array( 'step' => $step_id + 1 ), get_the_permalink( $post_id ) );
					} else {
						$url = add_query_arg( array( 'woopb_preview' => 1 ), get_the_permalink( $post_id ) );
					}

					header( "Location: $url" ); /* Redirect browser */

					/* Make sure that code below does not get executed when we redirect. */
					exit;
				}
			}
		} /*Process add to WooCommerce cart*/
		elseif ( wp_verify_nonce( $_POST['_nonce'], '_woopb_add_to_woocommerce' ) ) {
			$steps = $this->settings->get_products_added( $post_id );
			if ( count( $steps ) ) {
				foreach ( $steps as $step ) {
					foreach ( $step as $product_id => $quantity ) {
						WC()->cart->add_to_cart( $product_id, $quantity );
					}
				}
			}
			if ( $this->settings->get_param('remove_session') ) {
				wc()->session->__unset( 'woopb_' . $post_id );
			}
		}
	}

	/**
	 * Method remove product ajax
	 */
	public function remove_product() {
		$step_id    = filter_input( INPUT_GET, 'stepp', FILTER_SANITIZE_NUMBER_INT );
		$product_id = filter_input( INPUT_GET, 'product_id', FILTER_SANITIZE_NUMBER_INT );
		$_nonce     = filter_input( INPUT_GET, '_nonce', FILTER_SANITIZE_STRING );

		if ( wp_verify_nonce( $_nonce, '_woopb_remove_product_step' ) && $step_id && $product_id && get_post_type() == 'woo_product_builder' ) {
			$post_id = get_the_ID();

			$this->settings->remove_product( $post_id, $product_id, $step_id );
		}

	}


	/**
	 * Method rewrite url
	 */
	public function product_builder_rewrite() {
		/*Check customer has not session*/
		if ( class_exists( 'WC_Session_Handler' ) ) {
			$session = new WC_Session_Handler();
			if ( ! $session->has_session() ) {
				$session->init();
				$session->set_customer_session_cookie( true );
			}
		}

		if ( trim( get_option( 'wpb2205_cpt_base' ) ) != '' ) {
			$main_struct_link = get_option( 'wpb2205_cpt_base' );
			add_rewrite_rule( "$main_struct_link/([^/]*)/step/([0-9]*)/?$", 'index.php?post_type=woo_product_builder&name=$matches[1]&step_builder=$matches[2]', 'top' );
		} else {
			add_rewrite_rule( "woo_product_builder/([^/]*)/step/([0-9]*)/?$", 'index.php?post_type=woo_product_builder&name=$matches[1]&step_builder=$matches[2]', 'top' );
		}

	}

	/**
	 * Method add query_var
	 */
	function wbs_query_var( $query_vars ) {
		$query_vars[] = 'step';
		$query_vars[] = 'ppaged';
		$query_vars[] = 'max_page';
		$query_vars[] = 'min_price';
		$query_vars[] = 'max_price';
		$query_vars[] = 'sort_by';
		$query_vars[] = 'rating_filter';
		$query_vars[] = 'woopb_preview';

		return $query_vars;
	}


	/**
	 * Method send chosen product for friend
	 */
	public function send_email_friend() {
		if ( ! $this->settings->enable_email() ) {
			return;
		}
		if ( ! isset( $_POST['_wpnonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'woocommerce_product_builder_send_email' ) ) {
			return;
		}
		$emailto = filter_input( INPUT_POST, 'woopb_emailto_field', FILTER_SANITIZE_EMAIL );
		if ( ! $emailto ) {
			return;
		}
		$subject = filter_input( INPUT_POST, 'woopb_subject_field', FILTER_SANITIZE_STRING );
		$content = filter_input( INPUT_POST, 'woopb_content_field', FILTER_SANITIZE_STRING );

		global $post;
		if ( empty( $post ) ) {
			return;
		}

		$post_id = $post->ID;

		$setting           = new VI_WPRODUCTBUILDER_F_Admin_Settings();
		$pre_subject       = $setting->get_option_field( 'email_subject' );
		$container_content = $setting->get_option_field( 'message_body' );
		$email_from        = $setting->get_option_field( 'email_from' );

		if ( trim( $pre_subject ) ) {
			$subject = $pre_subject . ' - ' . $subject;
		}
		$products = $this->settings->get_products_added( $post_id );
		if ( is_array( $products ) && count( array_filter( $products ) ) ) {

			$content = '<p>' . strip_tags( $content ) . '</p>';
			$content .= '<table border="0" cellpadding="10" cellspacing="0" width="100%"><thead style="background: #eee;"><tr><th width="20%" align="left"></th><th width="30%" align="center">' . esc_html__( 'Product', 'woo-product-builder' ) . '</th><th width="20%" align="left">' . esc_html__( 'Price', 'woo-product-builder' ) . '</th><th width="10%" align="left">' . esc_html__( 'Quantity', 'woo-product-builder' ) . '</th><th width="20%" align="left">' . esc_html__( 'Total', 'woo-product-builder' ) . '</th></tr></thead><tbody>';
			$index   = 1;
			$total   = 0;
			foreach ( $products as $step_id => $items ) {
				foreach ( $items as $key_id => $quantity ) {
					$get_prd  = wc_get_product( $key_id );
					$prd_name = '<a href="' . esc_url( $get_prd->get_permalink() ) . '">' . $get_prd->get_name() . '</a>';
					if ( ! empty( get_the_post_thumbnail( $key_id ) ) ) {
						$prd_thumbnail = get_the_post_thumbnail( $key_id, 'thumbnail' );
					} else {
						$prd_thumbnail = '<img src="' . wc_placeholder_img_src() . '" width="150px" height="150px" />';
					}
					$prd_price     = $get_prd->get_price();
					$format_pridce = wc_price( $prd_price );
					$content       .= '<tr><td align="left">' . $prd_thumbnail . '</td><td align="center">' . $prd_name . '</td><td align="left">' . $format_pridce . '</td><td align="left">' . $quantity . '</td><td align="left">' . wc_price( $quantity * $prd_price ) . '</td></tr>';
					$index ++;
					$total = $total + intval( $prd_price );
				}
			}
			$content      .= '</tbody>';
			$total_format = wc_price( $total );
			$content      .= '<tfoot style="background: #eee;"><tr><td></td><td></td><td></td><td>' . esc_html( 'Total:' ) . '</td><td>' . $total_format . '</td></tr></tfoot></table>';

			$arr_rpl       = array( $email_from, $subject, $content );
			$content_admin = nl2br( $container_content );

			if ( $container_content ) {
				$keya    = array(
					'{email}',
					'{subject}',
					'{message_content}'
				);
				$content = str_replace( $keya, $arr_rpl, $content_admin );
			}
			$headers = array( 'Content-Type: text/html; charset=UTF-8' );
			wp_mail( $emailto, $subject, $content, $headers );
		}
	}


	/**
	 * Get Post Meta
	 *
	 * @param $field
	 *
	 * @return bool
	 */
	private function get_data( $post_id, $field, $default = '' ) {

		if ( isset( $this->data[ $post_id ] ) && $this->data[ $post_id ] ) {
			$params = $this->data[ $post_id ];
		} else {
			$this->data[ $post_id ] = get_post_meta( $post_id, 'woopb-param', true );
			$params                 = $this->data[ $post_id ];
		}

		if ( isset( $params[ $field ] ) && $field ) {
			return $params[ $field ];
		} else {
			return $default;
		}
	}

}


