<?php
namespace INS\Controller;

class App {

	public $layout = 1;
	public $layouts_slug = "/layouts/layout.php";
	public $layout_class = "";




	public function __construct() {

		$this->ins_layout_set_data();

		add_action( 'wp_body_open', array( $this, 'ins_layout_three' ), 10 );

		// add_filter( 'woocommerce_add_to_cart_fragments', array($this, 'ins_cart_count_fragments'), 50, 1 );

		// Ajax Cart reload After Product Add to Cart
		add_action( 'wp_ajax_nopriv_ins_ajax_cart_reload', array( $this, 'ins_ajax_cart_reload' ), 20 );
		add_action( 'wp_ajax_ins_ajax_cart_reload', array( $this, 'ins_ajax_cart_reload' ), 20 );

		// Single Product Page Ajax Add to Cart
		add_action( 'wp_ajax_nopriv_ins_ajax_cart_single', array( $this, 'ins_ajax_cart_single' ), 20 );
		add_action( 'wp_ajax_ins_ajax_cart_single', array( $this, 'ins_ajax_cart_single' ), 20 );

		// Ajax Cart Remove To cart
		add_action( 'wp_ajax_nopriv_ins_ajax_cart_item_remove', array( $this, 'ins_ajax_cart_item_remove' ), 20 );
		add_action( 'wp_ajax_ins_ajax_cart_item_remove', array( $this, 'ins_ajax_cart_item_remove' ), 20 );

		// Ajax Cart Remove To cart
		add_action( 'wp_ajax_nopriv_ins_ajax_empty_cart', array( $this, 'ins_ajax_empty_cart' ), 20 );
		add_action( 'wp_ajax_ins_ajax_empty_cart', array( $this, 'ins_ajax_empty_cart' ), 20 );

		// Ajax Update Cart
		add_action( 'wp_ajax_nopriv_ins_ajax_update_cart', array( $this, 'ins_ajax_update_cart' ) );
		add_action( 'wp_ajax_ins_ajax_update_cart', array( $this, 'ins_ajax_update_cart' ) );

		// Ajax Remove Coupon
		add_action( 'wp_ajax_nopriv_ins_ajax_remove_coupon', array( $this, 'ins_ajax_remove_coupon' ) );
		add_action( 'wp_ajax_ins_ajax_remove_coupon', array( $this, 'ins_ajax_remove_coupon' ) );


		// Ins Cart Toggle
		add_action( 'ins_cart_toggle', array( $this, 'ins_cart_toggle' ), 11 );

		// Ins Cart Toggle 
		add_action( 'ins_cart_header', array( $this, 'ins_cart_modern_header' ), 10 );

		// Ins Cart Buttons
		add_action( 'ins_cart_buttons', array( $this, 'ins_cart_buttons' ), 11 );

		// Ins Cart Toggle
		add_action( 'ins_cart_content', array( $this, 'ins_cart_content_modern' ), 10, 2 );
		add_action( 'ins_cart_content_single', array( $this, 'ins_modern_cart_only' ), 10, 2 );

	}


	public function ins_options_init() {
		$options = get_option( 'wiopt' );
		$cart_icon = ! empty( insopt( 'ins-toggle-tab' )['cart-icon'] ) ? insopt( 'ins-toggle-tab' )['cart-icon'] : 'shopping-bag';
		echo "<pre>";
		print_r( $cart_icon );
		echo "</pre>";
		die;
	}

	// Instantio Layout Set Data
	public function ins_layout_set_data() {
		$ins_layout = ! empty( insopt( 'ins-layout-options' ) ) ? insopt( 'ins-layout-options' ) : '1';

		require_once INS_INC_PATH . "/controller/icon-svg.php";

		if ( $ins_layout == 1 ) {
			$this->layout = $ins_layout;
			$this->layout_class = '';
			$this->layouts_slug = "/layouts/layout-1.php";
		} elseif ( $ins_layout == 2 ) {
			$this->layout = $ins_layout;
			$this->layout_class = 'slide ';
			$this->layouts_slug = "/layouts/layout-2.php";

		} elseif ( $ins_layout == 3 ) {
			$this->layout = $ins_layout;
			$this->layout_class = 'popup ';
			$this->layouts_slug = "/layouts/layout-3.php";
		}
	}

	// Ins Cart Header
	public function ins_cart_modern_header() {

		$ins_single_layout = ! empty( insopt( 'ins-layout-step' ) ) ? insopt( 'ins-layout-step' ) : false;


		ob_start();
		?>
		<div class="header-wrap">
			<div class="ins-checkout-header">
				<span class="ins-checkout-header-icon">
					<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
						<g clip-path="url(#clip0_139_572)">
							<path
								d="M8.5489 9.0962L5.00006 5.54846L6.54851 4.00002L10.0962 7.54885H26.7757C26.9462 7.54884 27.1145 7.5887 27.2669 7.66525C27.4193 7.7418 27.5517 7.85292 27.6536 7.98974C27.7554 8.12657 27.8239 8.2853 27.8535 8.45328C27.8831 8.62126 27.873 8.79384 27.824 8.95723L25.1977 17.7117C25.1301 17.9372 24.9916 18.1349 24.8028 18.2755C24.6139 18.416 24.3848 18.4919 24.1493 18.4919H10.7375V20.6805H22.7749V22.8692H9.64321C9.35298 22.8692 9.07464 22.7539 8.86941 22.5486C8.66419 22.3434 8.5489 22.0651 8.5489 21.7748V9.0962ZM10.7375 9.73747V16.3033H23.3352L25.3049 9.73747H10.7375ZM10.1904 27.2464C9.75502 27.2464 9.3375 27.0734 9.02967 26.7656C8.72184 26.4578 8.5489 26.0403 8.5489 25.6049C8.5489 25.1696 8.72184 24.7521 9.02967 24.4442C9.3375 24.1364 9.75502 23.9635 10.1904 23.9635C10.6257 23.9635 11.0432 24.1364 11.351 24.4442C11.6589 24.7521 11.8318 25.1696 11.8318 25.6049C11.8318 26.0403 11.6589 26.4578 11.351 26.7656C11.0432 27.0734 10.6257 27.2464 10.1904 27.2464ZM23.322 27.2464C22.8867 27.2464 22.4692 27.0734 22.1614 26.7656C21.8535 26.4578 21.6806 26.0403 21.6806 25.6049C21.6806 25.1696 21.8535 24.7521 22.1614 24.4442C22.4692 24.1364 22.8867 23.9635 23.322 23.9635C23.7574 23.9635 24.1749 24.1364 24.4827 24.4442C24.7906 24.7521 24.9635 25.1696 24.9635 25.6049C24.9635 26.0403 24.7906 26.4578 24.4827 26.7656C24.1749 27.0734 23.7574 27.2464 23.322 27.2464Z"
								fill="#494E5C" />
						</g>
						<defs>
							<clipPath id="clip0_139_572">
								<rect width="32" height="32" rx="4" fill="white" />
							</clipPath>
						</defs>
					</svg>
				</span>
				<span class="ins-checkout-header-title">
					<?php _e( 'Your cart ', 'instantio' ) ?>
				</span>
				<span class="ins-checkout-close">
					<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
						<g clip-path="url(#clip0_139_578)">
							<path
								d="M16 14.1147L22.6 7.51467L24.4853 9.4L17.8853 16L24.4853 22.6L22.6 24.4853L16 17.8853L9.4 24.4853L7.51466 22.6L14.1147 16L7.51466 9.4L9.4 7.51467L16 14.1147Z"
								fill="#494E5C" />
						</g>
						<defs>
							<clipPath id="clip0_139_578">
								<rect width="32" height="32" rx="4" fill="white" />
							</clipPath>
						</defs>
					</svg>
				</span>
			</div>
			<?php
			if ( $ins_single_layout == false ) {
				do_action( 'ins_template_steps' );
			}
			// do_action('ins_template_steps');
			?>
		</div>
		<?php
		echo ob_get_clean();
	}

	// Ins Toggle button
	public function ins_cart_toggle() {
		ob_start();
		$ins_toggler = insopt( 'ins-toggler' );
		$cart_icon = ! empty( insopt( 'ins-toggle-tab' )['cart-icon'] ) ? insopt( 'ins-toggle-tab' )['cart-icon'] : 'shopping-bag';
		$wi_icon_choice = ! empty( insopt( 'ins-toggle-tab' )['wi-icon-choice'] ) ? insopt( 'ins-toggle-tab' )['wi-icon-choice'] : 'icon';
		$wi_icon_choice_uploder = ! empty( insopt( 'ins-toggle-tab' )['wi-icon-choice-uploder'] ) ? insopt( 'ins-toggle-tab' )['wi-icon-choice-uploder'] : '';
		if ( $cart_icon == 'shopping-bag' ) {

			$toggle_icon = apply_filters( 'ins_get_svg_icon_pro', instantio_svg_icon( $cart_icon ) );
		} else {
			$toggle_icon = '<i class="' . $cart_icon . '"></i>';
		}
		if ( $wi_icon_choice == 'image' && $wi_icon_choice_uploder != '' ) {
			$toggle_icon = '<img src="' . $wi_icon_choice_uploder . '" alt="Icon Image">';
		}

		if ( $this->layout == 2 ) {
			$togglebtnClass = 'sidecart';
		} elseif ( $this->layout == 3 ) {
			$togglebtnClass = 'popupcart';
		}
		$icon_style = ! empty( insopt( 'ins-toggle-tab' )['cart-icon-style'] ) ? insopt( 'ins-toggle-tab' )['cart-icon-style'] : 'cart-style-1';
		$dedicated_mobile = ! empty( insopt( 'dedicated_mobile' ) ) ? insopt( 'dedicated_mobile' ) : false;
		$mobile_cart_panel = ! empty( insopt( 'mobile-cart-panel' ) ) ? insopt( 'mobile-cart-panel' ) : false;
		$dedicated_mobile_panel_class = $dedicated_mobile == true && $mobile_cart_panel == true ? ' ins-dedicated-mobile-card-panel' : '';
		if ( $this->layout == 1 || $this->layout == '' ) {
			$ins_toggler = 'tog-1';
			?>
			<a class="ins-toggle-btn <?php echo esc_attr( $ins_toggler ) ?> <?php echo esc_attr( $dedicated_mobile_panel_class ) ?> <?php echo esc_attr( $icon_style ) ?> "
				href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
				<span class="ins-cart-icon">
					<?php echo $toggle_icon ?>
				</span>

				<?php // echo insopt( 'ins-toggle-tab' )['ins-cart-emty-hide'];        ?>
				<span class="ins-items-count"><span id="ins_cart_totals" class="ins_cart_total">
						<?php echo WC()->cart->get_cart_contents_count(); ?>
					</span></span>
			</a>
			<?php
		} else {
			?>
			<div
				class="ins-click-to-show ins-toggle-btn <?php echo esc_attr( $togglebtnClass ) ?> <?php echo esc_attr( $dedicated_mobile_panel_class ) ?> <?php echo esc_attr( $icon_style ) ?>  <?php echo esc_attr( $ins_toggler ) ?>">
				<span class="ins-cart-icon">
					<?php echo $toggle_icon ?>
				</span>
				<span class="ins-items-count"><span id="ins_cart_totals" class="ins_cart_total">
						<?php echo WC()->cart->get_cart_contents_count(); ?>
					</span></span>
			</div>
			<?php
		}

		echo ob_get_clean();
	}


	// Ins Cart Buttons
	public function ins_cart_buttons() {
		ob_start();

		// Cart Button
		$on_cart_btn = isset( insopt( 'cart-btn' )['on-cart-btn'] ) ? insopt( 'cart-btn' )['on-cart-btn'] : false;
		$cart_button_text = isset( insopt( 'cart-btn' )['cart_button_text'] ) ? insopt( 'cart-btn' )['cart_button_text'] : '';
		$cart_button_url = isset( insopt( 'cart-btn' )['cart_button_url'] ) ? insopt( 'cart-btn' )['cart_button_url'] : '';

		$cart_button_text = ! empty( $cart_button_text ) && $on_cart_btn == true ? wp_strip_all_tags( __( $cart_button_text, 'instantio' ) ) : __( 'View Cart', 'instantio' );
		$cart_button_url = ! empty( $cart_button_url ) && $on_cart_btn == true ? $cart_button_url : wc_get_cart_url();

		// Cart Button Link
		$cart_button = '<a href="' . esc_url( $cart_button_url ) . '" class="view-cart active">' . esc_html( $cart_button_text ) . '</a>';

		// insopt( 'auto-tog-panel' )
		// Checkout Button
		$on_checkout_btn = isset( insopt( 'checkout-btn' )['on-checkout-btn'] ) ? insopt( 'checkout-btn' )['on-checkout-btn'] : false;

		$checkout_button_text = isset( insopt( 'checkout-btn' )['checkout_button_text'] ) ? insopt( 'checkout-btn' )['checkout_button_text'] : '';
		$checkout_button_url = isset( insopt( 'checkout-btn' )['checkout_button_url'] ) ? insopt( 'checkout-btn' )['checkout_button_url'] : '';

		$checkout_button_text = ! empty( $checkout_button_text ) && $on_checkout_btn == true ? wp_strip_all_tags( __( $checkout_button_text, 'instantio' ) ) : __( 'Checkout Now', 'instantio' );
		$checkout_button_url = ! empty( $checkout_button_url ) && $on_checkout_btn == true ? $checkout_button_url : wc_get_checkout_url();

		//  Checkout button Link
		$checkout_button = '<a href="' . esc_url( $checkout_button_url ) . '" class="checkout">' . esc_html( $checkout_button_text ) . '</a>';


		?>
		<div class="ins-cart-btns">
			<?php echo $cart_button; ?>
			<?php echo $checkout_button; ?>
		</div>
		<?php

		$html = ob_get_clean();
		echo apply_filters( 'ins_cart_buttons_pro', $html );
	}


	// Ins Cart Content Modern
	public function ins_cart_content_modern( $display ) {
		ob_start();

		?>
		<div class="ins-content <?php echo esc_attr( $display ) ?>">
			<div class="ins-cart-inner step-1 ins-cart-step active">
				<?php require_once apply_filters( 'ins_cart_template', INS_INC_PATH . '/templates/cart-modern.php' ); ?>

			</div>
			<?php do_action( 'ins_template_step_content' ); ?>
		</div>
		<?php
		echo ob_get_clean();
	}

	// Ins Only Modern Cart Content
	public function ins_modern_cart_only( $display ) {
		ob_start();
		?>
		<div class="ins-content <?php echo esc_attr( $display ) ?>">
			<div class="ins-cart-inner step-1 ins-cart-step active">
				<?php require_once apply_filters( 'ins_cart_template', INS_INC_PATH . '/templates/ins_single_step_cart.php' ); ?>
			</div>
		</div>
		<?php
		echo ob_get_clean();
	}

	// Ajax Cart reload After Product Add to Cart
	public function ins_ajax_cart_reload() {
		ob_start();
		// require_once apply_filters( 'ins_layout_slug', INS_INC_PATH . $this->layouts_slug );

		// checked is single step
		$ins_single_layout = ! empty( insopt( 'ins-layout-step' ) ) ? insopt( 'ins-layout-step' ) : false;

		if ( $ins_single_layout ) {
			require_once INS_TEMPLATES_PATH . '/ins_single_step_cart.php';
		} else {
			require_once INS_TEMPLATES_PATH . '/cart-modern.php';
		}
		// require_once INS_INC_PATH .  $this->layouts_slug;
		$data = ob_get_clean();

		// Override the checkout template
		if ( ! is_user_logged_in() ) {
			// Display for non-logged-in users
			ob_start();
			add_action( 'woocommerce_checkout_shipping', array( WC()->checkout(), 'checkout_form_shipping' ) );
			do_action( 'woocommerce_checkout_shipping' );
			$ins_shipping_additional = ob_get_clean();
		} else {
			// Use the default WooCommerce action for logged-in users
			ob_start();
			do_action( 'woocommerce_checkout_shipping' );
			$ins_shipping_additional = ob_get_clean();
		}

		$hide_empty = 'hide';
		$display = 'ins-show';
		if ( WC()->cart->is_empty() ) :
			$hide_empty = 'ins-show';
			$display = 'hide';
		endif;

		$ins_cart_total = WC()->cart->get_cart_contents_count();
		// $ins_checkout_load = apply_filters('ins_template_step_content', '');

		$response = array(
			// 'fragments' => apply_filters( 'ins_cart_count_fragments', array() ),
			'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() ),
			'data' => $data,
			'cart_total' => WC()->cart->get_cart_total(),
			'hide_empty' => $hide_empty,
			'display' => $display,
			'ins_cart_count' => $ins_cart_total,
			'ins_shipping_additional' => $ins_shipping_additional,
			// 'ins_checkout_load' => $ins_checkout_load,
		);

		wp_send_json_success( $response );


		wp_die();

	}

	// Ajax Single Page Add to Cart
	public function ins_ajax_cart_single() {
		$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
		$quantity = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );
		$variation_id = absint( $_POST['variation_id'] );
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
		$product_status = get_post_status( $product_id );

		if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id ) && $product_status === 'publish' ) {

			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

			if ( get_option( 'woocommerce_cart_redirect_after_add' ) === 'yes' ) {
				wc_add_to_cart_message( array( $product_id => $quantity ), true );
			}

			$this->ins_ajax_cart_reload();

		} else {
			$data = array(
				'error' => true,
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ) );

			echo wp_send_json( $data );
		}
		wp_die();
	}

	// Ajax Cart Remove To cart
	public function ins_ajax_cart_item_remove() {
		$product_id = $_POST['product_id'];
		$variation_id = $_POST['variation_id'];

		$cart = WC()->cart->get_cart();

		foreach ( $cart as $cart_item_key => $cart_item ) {
			if ( $cart_item['product_id'] == $product_id && $cart_item['variation_id'] == $variation_id ) {

				// Remove product in the cart using  cart_item_key.
				WC()->cart->remove_cart_item( $cart_item_key );
			}
		}
		WC()->cart->calculate_totals();
		WC()->cart->maybe_set_cart_cookies();

		return $this->ins_ajax_cart_reload();


	}

	// Ajax Update Cart
	public function ins_ajax_update_cart() {

		$cart_item_keys = $_POST['cart_item_keys'];
		$product_ids = $_POST['product_ids'];
		$quantities = $_POST['quantities'];
		$coupon_code = $_POST['coupon_code'];
		$cart_updated = false;

		for ( $i = 0; $i < count( $cart_item_keys ); $i++ ) {

			WC()->cart->set_quantity( $cart_item_keys[ $i ], $quantities[ $i ], false );

			if ( $quantities[ $i ] == 0 ) {
				WC()->cart->remove_cart_item( $cart_item_keys[ $i ] );
				continue;
			}
		}
		if ( ! empty( $coupon_code ) ) {
			WC()->cart->add_discount( sanitize_text_field( $coupon_code ) );
		}

		WC()->cart->calculate_totals();
		WC()->cart->maybe_set_cart_cookies();
		$hide_empty = 'hide';
		$display = 'ins-show';
		if ( WC()->cart->is_empty() ) :
			$hide_empty = 'ins-show';
			$display = 'hide';
		endif;

		ob_start();
		// require_once apply_filters( 'ins_layout_slug', INS_INC_PATH . $this->layouts_slug ); 
		$this->ins_ajax_cart_reload();
		$cart_data = ob_get_clean();

		// Override the checkout template
		if ( ! is_user_logged_in() ) {
			// Display for non-logged-in users
			ob_start();
			add_action( 'woocommerce_checkout_shipping', array( WC()->checkout(), 'checkout_form_shipping' ) );
			do_action( 'woocommerce_checkout_shipping' );
			$ins_shipping_additional = ob_get_clean();
		} else {
			// Use the default WooCommerce action for logged-in users
			ob_start();
			do_action( 'woocommerce_checkout_shipping' );
			$ins_shipping_additional = ob_get_clean();
		}

		$data = array(
			'cart_data' => $cart_data,
			'hide_empty' => $hide_empty,
			'display' => $display,
			'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() ),
			'ins_shipping_additional' => $ins_shipping_additional,
		);

		wp_send_json( $data );
		wp_die();

	}

	// Ajax Cart Empty To cart
	public function ins_ajax_empty_cart() {

		WC()->cart->empty_cart();
		WC()->cart->calculate_totals();
		WC()->cart->maybe_set_cart_cookies();

		ob_start();

		// require_once apply_filters( 'ins_layout_slug', INS_INC_PATH . $this->layouts_slug ); 
		$this->ins_ajax_cart_reload();
		$cart_data = ob_get_clean();

		$data = array(
			'cart_data' => $cart_data,
			'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
		);

		wp_send_json( $data );
		wp_die();

	}

	// Ajax Remove Coupon
	public function ins_ajax_remove_coupon() {
		$coupon_code = $_POST['coupon'];
		WC()->cart->remove_coupon( $coupon_code );

		WC()->cart->calculate_totals();
		WC()->cart->maybe_set_cart_cookies();

		ob_start();

		require_once apply_filters( 'ins_layout_slug', INS_INC_PATH . $this->layouts_slug );
		$cart_data = ob_get_clean();

		$data = array(
			'cart_data' => $cart_data,
			'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
		);

		wp_send_json( $data );
		wp_die();

	}



	public function ins_layout_three() {
		// Get the checkout page's ID
		$checkout_page_id = wc_get_page_id( 'checkout' );

		// Get the post object for the checkout page
		$checkout_page = get_post( $checkout_page_id );

		// Get the slug of the checkout page
		$checkout_page_slug = $checkout_page->post_name;

		// Checking the pro is active or not
		$is_Pro_class = is_ins_pro_active();

		// Return if WooCommerce not active
		if ( ! class_exists( 'woocommerce' ) ) {
			return;
		}

		// Return if checkout page
		if ( class_exists( 'woocommerce' ) ) {
			if ( $is_Pro_class === false ) {
				if ( is_page( 'checkout' ) ) {
					return;
				}
			} else {
				if ( is_page( $checkout_page_slug ) || is_page( 'checkout' ) ) {
					return;
				}
			}
		}

		// Return if cart page
		if ( class_exists( 'woocommerce' ) ) {
			if ( is_page( 'cart' ) || is_cart() ) {
				return;
			}
		}

		// checked is page seleted or not
		$ins_page_selected = insopt( 'ins-page-selected' ); // Retrieve the option value

		if ( ! empty( $ins_page_selected ) && is_array( $ins_page_selected ) ) {
			// The option value is an array, so you can proceed with array operations
			$pages = array_values( $ins_page_selected );
		} else {
			// Handle the case where the option value is not an array (e.g., set a default value)
			$pages = array( 'cart', 'checkout' );
		}

		// Return is particular page seleted
		if ( class_exists( 'woocommerce' ) ) {
			if ( is_page( $pages ) ) {
				return;
			}
		}

		$toggle_position = isset( insopt( 'ins-toggle-tab' )['toggle-position'] ) ? insopt( 'ins-toggle-tab' )['toggle-position'] : 'right-bottom';

		// checked is single step
		$ins_single_layout = ! empty( insopt( 'ins-layout-step' ) ) ? insopt( 'ins-layout-step' ) : false;

		if ( ! empty( $toggle_position ) ) {
			$toggle_position = explode( '-', $toggle_position );
			$toggle_position_horizontal = $toggle_position[0];
			$toggle_position_vertical = $toggle_position[1];
		} else {
			$toggle_position_horizontal = 'right';
			$toggle_position_vertical = 'bottom';
		}
		$toggle_panel_position = isset( insopt( 'ins-toggle-panel-tab' )['toggle-panel-position'] ) ? 'panel-' . insopt( 'ins-toggle-panel-tab' )['toggle-panel-position'] : 'panel-right';
		$this->layout_class .= ! empty( $toggle_position_horizontal ) ? 'ins-hori-' . $toggle_position_horizontal . ' ' : 'ins-hori-right ';
		$this->layout_class .= ! empty( $toggle_panel_position ) ? $toggle_panel_position . ' ' : 'panel-right ';
		$this->layout_class .= ! empty( $toggle_position_vertical ) ? 'ins-var-cart-' . $toggle_position_vertical . ' ' : 'ins-var-cart-bottom ';
		$this->layout_class .= ! empty( insopt( 'ins-layout-mode' ) ) ? 'ins-layout-' . insopt( 'ins-layout-mode' ) . ' ' : '';
		$this->layout_class .= ! empty( insopt( 'ins-layout-animation' ) ) ? insopt( 'ins-layout-animation' ) . ' ' : '';
		$this->layout_class .= $ins_single_layout == true ? 'ins-single-layout-step ' : '';

		$ins_layout_class = apply_filters( 'ins_layout_class', $this->layout_class );


		// Dedicated mobile Version hook for
		do_action( 'dedicated_mobile_version' );

		ob_start();
		if ( $this->layout == 1 || $this->layout == 3 ) :
			?>
			<div class="ins-fixed-toogle <?php echo esc_attr( $this->layout_class ) ?>">
				<?php echo do_action( 'ins_cart_toggle' ); ?>
			</div>
			<?php
		endif;

		if ( $this->layout == 2 || $this->layout == 3 ) :
			?>
			<div class="ins-checkout-popup ins-checkout-modern <?php echo esc_attr( $ins_layout_class ) ?>">
				<div class="ins-checkout-overlay"></div>
				<div class="ins-checkout-layout ins-checkout-layout-3 <?php echo esc_attr( $ins_layout_class ) ?>">
					<?php
					require_once apply_filters( 'ins_layout_slug', INS_INC_PATH . $this->layouts_slug );
					?>
				</div>
			</div>
			<?php
		endif;
		$output = ob_get_clean();
		echo $output;
	}

}


?>