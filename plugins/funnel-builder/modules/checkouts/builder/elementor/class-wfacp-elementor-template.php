<?php


#[AllowDynamicProperties]

 abstract class WFACP_Elementor_Template extends WFACP_Template_Common {
	public $default_setting_el = [];
	public $set_bredcrumb_data = [];
	public $stepsData = [];

	protected function __construct() {
		parent::__construct();
		add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'get_ajax_exchange_keys' ] );
		$this->url = WFACP_Core()->url( '/builder/elementor/template/views/' );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'reset_session' ] );
		add_filter( 'wfacp_forms_field', [ $this, 'hide_product_switcher' ], 10, 2 );

		add_filter( 'wfacp_cart_show_product_thumbnail', [ $this, 'display_order_summary_thumb' ], 10 );
		add_filter( 'wfacp_cart_show_product_thumbnail_collapsible', [ $this, 'display_order_summary_thumb_collapsed' ], 12 );

		//add_filter( 'wfacp_html_fields_order_summary', '__return_false' );
		add_action( 'wfacp_internal_css', [ $this, 'get_elementor_localize_data' ], 9 );

		/* Add div ID  */
		add_action( 'wfacp_before_form', [ $this, 'element_start_before_the_form' ], 9 );
		add_action( 'wfacp_after_form', [ $this, 'element_end_after_the_form' ], 9 );

		add_action( 'wfacp_checkout_preview_form_start', [ $this, 'element_start_before_the_form' ], 9 );
		add_action( 'wfacp_checkout_preview_form_end', [ $this, 'element_end_after_the_form' ], 9 );



		add_filter( 'wfacp_css_js_deque', [ $this, 'remove_theme_styling' ], 10, 4 );
		add_action( 'wp_head', [ $this, 'wfacp_header_print_in_head' ], 999 );
		add_action( 'wp_footer', [ $this, 'wfacp_footer_before_print_scripts' ], - 1 );
		add_action( 'wp_footer', [ $this, 'wfacp_footer_after_print_scripts' ], 999 );


		add_filter( 'wfacp_mini_cart_hide_coupon', [ $this, 'enable_collapsed_coupon_field' ], 10 );

		add_filter( 'wfacp_order_summary_cols_span', [ $this, 'change_col_span_for_order_summary' ] );
		add_filter( 'wfacp_order_total_cols_span', [ $this, 'change_col_span_for_order_summary' ] );

		add_filter( 'wfacp_for_mb_style', [ $this, 'get_product_switcher_mobile_style' ] );
		add_action( 'wfacp_checkout_preview_form_start', [ $this, 'add_checkout_preview_div_start' ] );
		add_action( 'wfacp_checkout_preview_form_end', [ $this, 'add_checkout_preview_div_end' ] );
		add_action( 'wp', [ $this, 'run_divi_styling' ] );

		add_action( 'wfacp_before_progress_bar', [ $this, 'before_cart_link' ] );
		add_action( 'wfacp_before_breadcrumb', [ $this, 'before_cart_link' ] );

		add_action( 'wfacp_after_next_button', [ $this, 'before_return_to_cart_link' ] );

		add_action( 'woocommerce_before_checkout_form', [ $this, 'add_form_steps' ], 999 );
		add_action( 'woocommerce_before_checkout_form', [ $this, 'display_pogress_bar' ], 999 );
		add_filter( 'woocommerce_order_button_html', [ $this, 'add_class_change_place_order' ], 11 );
		add_filter( 'wfacp_change_back_btn', [ $this, 'change_back_step_label' ], 11, 3 );

		add_filter( 'wfacp_blank_back_text', [ $this, 'add_blank_back_text' ], 11, 3 );

		add_filter( 'woocommerce_update_order_review_fragments', [ $this, 'add_fragment_coupon_sidebar' ], 99, 2 );

		/* activate flatsome theme hook */
		add_action( 'wfacp_footer_before_print_scripts', [ $this, 'activate_theme_hook' ] );


		/* Coupon button text */
		add_action( 'wfacp_collapsible_apply_coupon_button_text', [ $this, 'get_collapsible_coupon_button_text' ] );
		add_action( 'wfacp_form_apply_coupon_button_text', [ $this, 'get_form_coupon_button_text' ] );
		add_action( 'wfacp_sidebar_apply_coupon_button_text', [ $this, 'get_mini_cart_coupon_button_text' ] );

		add_filter( 'wfacp_form_coupon_widgets_enable', '__return_true' );

		/*--------------------------------Primary Color Handling -------------------------------------------*/
		add_action( 'wfacp_internal_css', [ $this, 'primary_colors' ], 10 );


	}

	public function add_fragment_coupon_sidebar( $fragments ) {

		$messages        = '';
		$success_message = sprintf( __( 'Congrats! Coupon code %s %s applied successfully.', 'funnel-builder' ), '{{coupon_code}}', '({{coupon_value}})' );

		ob_start();
		foreach ( WC()->cart->get_coupons() as $code => $coupon ) {
			$parse_message = WFACP_Product_Switcher_Merge_Tags::parse_coupon_merge_tag( $success_message, $coupon );


			$remove_link = sprintf( "<a href='%s' class='woocommerce-remove-coupon' data-coupon='%s'>%s</a>", add_query_arg( [
				'remove_coupon' => $code,
			], wc_get_checkout_url() ), $code, __( 'Remove', 'funnel-builder' ) );
			$messages    .= sprintf( '<div class="woocommerce-message1 wfacp_coupon_success">%s %s</div>', $parse_message, $remove_link );


		}
		$fragments['.wfacp_coupon_msg .woocommerce-message'] = '<div class="woocommerce-message wfacp_sucuss">' . $messages . '</div>';


		return $fragments;

	}

	public function run_divi_styling() {
		if ( function_exists( 'et_divi_add_customizer_css' ) ) {
			et_divi_add_customizer_css();
		}
	}

	public function change_col_span_for_order_summary( $colspan_attr1 ) {

		return '';
	}

	public function check_layout_9_sidebar_hide_coupon() {
		return true;
	}

	public function element_start_before_the_form() {
		$template_slug = $this->get_template_slug();
		if ( strpos( $template_slug, 'elementor' ) !== false ) {
			echo "<div id=wfacp-e-form><div id='wfacp-sec-wrapper'>";
			$this->breadcrumb_start();
			$template       = wfacp_template();
			$label_position = '';
			if ( isset( $this->form_data['wfacp_label_position'] ) ) {
				$label_position = $this->form_data['wfacp_label_position'];
			}

			if ( is_array( $this->form_data ) ) {

				$mbDevices = [ 'wfacp_collapsible_order_summary_wrap' ,$label_position];


				if ( isset( $this->form_data['enable_callapse_order_summary'] ) && "yes" === $this->form_data['enable_callapse_order_summary'] ) {

					$mbDevices[] = 'wfacp_desktop';
				}

				if ( isset( $this->form_data['enable_callapse_order_summary_tablet'] ) && "yes" === $this->form_data['enable_callapse_order_summary_tablet'] ) {

					$mbDevices[] = 'wfacp_tablet';
				}
				if ( isset( $this->form_data['enable_callapse_order_summary_mobile'] ) && "yes" === $this->form_data['enable_callapse_order_summary_mobile'] ) {
					$mbDevices[] = 'wfacp_mobile';
				}


				$deviceClass = implode( ' ', $mbDevices );

				if ( empty( $deviceClass ) ) {
					$deviceClass = 'wfacp_not_active';
				}

				if ( $this->form_data['enable_callapse_order_summary'] != 'no' ) {
					echo "<div class='" . $deviceClass . "'>";

					$template->get_mobile_mini_cart( $this->form_data );
					echo "</div>";
				}


			}


			echo "<div class='" . implode( ' ', [ 'wfacp-form', $label_position ] ) . "'>";

		}

	}

	public function element_end_after_the_form() {
		$template_slug = $this->get_template_slug();
		if ( strpos( $template_slug, 'elementor' ) !== false ) {
			echo "</div></div></div>";
		}

	}


	public function reset_session() {
		WFACP_Common::set_session( 'wfacp_order_total_widgets', [] );
		WFACP_Common::set_session( 'wfacp_min_cart_widgets', [] );
	}

	public function get_ajax_exchange_keys() {
		$keys = WFACP_Common::$exchange_keys;

		if ( ! empty( is_array( $keys ) ) && isset( $keys['elementor'] ) ) {
			$form_id         = $keys['elementor']['wfacp_form'];
			$this->form_data = WFACP_Common::get_session( $form_id );
		}
	}

	public function get_localize_data() {
		$data                               = parent::get_localize_data();
		$data['exchange_keys']['elementor'] = WFACP_Elementor::get_locals();

		return $data;

	}

	protected function get_field_css_ready( $template_slug, $field_index ) {

		if ( '' == $field_index ) {
			return '';
		}
		$field_key_index    = 'wfacp_' . $template_slug . '_' . $field_index . '_field';
		$field_custom_class = 'wfacp_' . $template_slug . '_' . $field_index . '_field_class';
		if ( isset( $this->form_data[ $field_key_index ] ) ) {

			return $this->form_data[ $field_key_index ] . ' ' . $this->form_data[ $field_custom_class ];
		}

		return '';

	}


	public function payment_heading() {
		if ( isset( $this->form_data['wfacp_payment_method_heading_text'] ) && '' !== trim( $this->form_data['wfacp_payment_method_heading_text'] ) ) {
			return trim( $this->form_data['wfacp_payment_method_heading_text'] );
		}


		return parent::payment_heading();
	}

	public function payment_sub_heading() {


		if ( isset( $this->form_data['wfacp_payment_method_subheading'] ) ) {
			return trim( $this->form_data['wfacp_payment_method_subheading'] );
		}

		return parent::payment_sub_heading();
	}

	public function get_payment_desc() {

		if ( isset( $this->form_data['text_below_placeorder_btn'] ) ) {
			return trim( $this->form_data['text_below_placeorder_btn'] );
		}

		return parent::get_payment_desc();

	}


	public function change_single_step_label( $name, $current_action ) {

		if ( isset( $this->form_data['wfacp_payment_button_1_text'] ) && '' != trim( $this->form_data['wfacp_payment_button_1_text'] ) ) {
			return trim( $this->form_data['wfacp_payment_button_1_text'] );
		}

		return $name;
	}

	public function change_two_step_label( $name, $current_action ) {
		if ( isset( $this->form_data['wfacp_payment_button_2_text'] ) && '' != trim( $this->form_data['wfacp_payment_button_2_text'] ) ) {
			return trim( $this->form_data['wfacp_payment_button_2_text'] );
		}

		return $name;
	}


	public function change_place_order_button_text( $text ) {

		if ( ! empty( $_GET['woo-paypal-return'] ) && ! empty( $_GET['token'] ) && ! empty( $_GET['PayerID'] ) ) {
			return $text;
		}

		$order_total = '';
		if ( isset( $this->form_data['enable_price_in_place_order_button'] ) && 'yes' == trim( $this->form_data['enable_price_in_place_order_button'] ) ) {
			$order_total = "&nbsp;&nbsp;" . WFACP_Common::wfacp_order_total( [] );
		}

		if ( isset( $this->form_data['wfacp_payment_place_order_text'] ) && '' != trim( $this->form_data['wfacp_payment_place_order_text'] ) ) {
			$text = trim( $this->form_data['wfacp_payment_place_order_text'] ) . $order_total;
		}
		$this->place_order_btn_text = $text;

		return $text;
	}

	public function payment_button_text() {
		if ( isset( $this->form_data['wfacp_payment_place_order_text'] ) && '' != trim( $this->form_data['wfacp_payment_place_order_text'] ) ) {
			return trim( $this->form_data['wfacp_payment_place_order_text'] );
		}

		return __( "Place order", 'woocommerce' );
	}


	public function payment_button_alignment() {
		if ( isset( $this->form_data['wfacp_form_payment_button_alignment'] ) && '' != trim( $this->form_data['wfacp_form_payment_button_alignment'] ) ) {
			return trim( $this->form_data['wfacp_form_payment_button_alignment'] );
		}

		return parent::payment_button_alignment();
	}


	public function change_back_step_label( $text, $next_action, $current_action ) {

		$i = 1;
		if ( 'third_step' == $current_action ) {
			$i = 3;
		} elseif ( 'two_step' == $current_action ) {
			$i = 2;
		}
		$key = 'payment_button_back_' . $i . '_text';


		if ( isset( $this->form_data[ $key ] ) ) {
			return trim( $this->form_data[ $key ] );
		}


		return $text;
	}

	public function add_blank_back_text( $label, $step, $current_step ) {

		$i = 1;
		if ( 'third_step' == $step ) {
			$i = 3;
		} elseif ( 'two_step' == $step ) {
			$i = 2;
		}
		$key = 'payment_button_back_' . $i . '_text';


		if ( isset( $this->form_data[ $key ] ) && $this->form_data[ $key ] == '' ) {
			return "wfacp_back_link_empty";
		}


		return $label;
	}


	public function get_mini_cart_widget( $widget_id ) {
		include WFACP_Core()->dir( 'builder/elementor/html/mini-cart/mini-cart.php' );
	}

	public function get_mini_cart_fragments( $fragments, $widget_id ) {


		ob_start();
		include WFACP_Core()->dir( 'builder/elementor/html/mini-cart/mini-cart-items.php' );
		$fragments[ '#wfacp_mini_cart_items_' . $widget_id ] = ob_get_clean();

		ob_start();
		include WFACP_Core()->dir( 'builder/elementor/html/mini-cart/mini-cart-review-totals.php' );
		$fragments[ '#wfacp_mini_cart_reviews_' . $widget_id ] = ob_get_clean();

		return $fragments;
	}

	public function get_mini_cart_coupon( $widget_id ) {
		include WFACP_Core()->dir( 'builder/elementor/html/mini-cart/form-coupon.php' );
	}


	public function get_order_total_widget( $widget_id ) {
		include WFACP_Core()->dir( 'builder/elementor/html/order-total/order-total.php' );
	}


	/*
	 * Hide product switcher if client use product switcher as widget
	 */
	public function hide_product_switcher( $fields, $key ) {

		$wfacp_id = WFACP_Common::get_id();
		if ( 'product_switching' == $key ) {
			$us_as_widget = get_post_meta( $wfacp_id, '_wfacp_el_product_switcher_us_a_widget', true );
			if ( 'yes' == $us_as_widget ) {

				$fields = [];
			}


		}

		return $fields;
	}

	public function display_order_summary_thumb( $status ) {
		if ( isset( $this->form_data['order_summary_enable_product_image'] ) && 'yes' === trim( $this->form_data['order_summary_enable_product_image'] ) ) {
			$status = true;
		}

		return $status;

	}

	public function display_order_summary_thumb_collapsed( $status ) {
		if ( isset( $this->form_data['order_summary_enable_product_image_collapsed'] ) && 'yes' === trim( $this->form_data['order_summary_enable_product_image_collapsed'] ) ) {
			$status = true;
		}

		return $status;
	}

	/* Override the order summary section */

	public function add_fragment_order_summary( $fragments ) {

		ob_start();
		include WFACP_PLUGIN_DIR . '/public/global/order-summary/order-summary.php';
		$fragments['.wfacp_order_summary'] = ob_get_clean();
		$mbDevices                         = [];

		if ( isset( $this->form_data['enable_callapse_order_summary'] ) && "yes" === $this->form_data['enable_callapse_order_summary'] ) {
			$mbDevices[] = 'wfacp_desktop';
		}

		if ( isset( $this->form_data['enable_callapse_order_summary_tablet'] ) && "yes" === $this->form_data['enable_callapse_order_summary_tablet'] ) {
			$mbDevices[] = 'wfacp_tablet';
		}
		if ( isset( $this->form_data['enable_callapse_order_summary_mobile'] ) && "yes" === $this->form_data['enable_callapse_order_summary_mobile'] ) {
			$mbDevices[] = 'wfacp_mobile';
		}
		if ( empty( $mbDevices ) ) {
			return $fragments;
		}


		return $this->add_fragment_collapsible_order_summary( $fragments );
	}

	public function get_elementor_localize_data() {
		$localData = [];
		if ( isset( $this->form_data['wfacp_make_button_sticky_on_mobile'] ) ) {
			$localData['wfacp_make_button_sticky_on_mobile'] = $this->form_data['wfacp_make_button_sticky_on_mobile'];
		}
		wp_localize_script( 'wfacp_checkout_js', 'wfacp_elementor_data', $localData );
	}

	public function set_default_setting_el() {
		$this->default_setting_el = [
			'heading' => [
				'color' => "red",
				'class' => "red",
			],
		];
	}

	public function remove_theme_styling( $bool, $path, $url, $currentEle ) {

		if ( false !== strpos( $url, '/themes/' ) ) {
			return false;
		}

		return $bool;
	}

	public function wfacp_header_print_in_head() {
		do_action( 'wfacp_header_print_in_head' );
	}

	public function wfacp_footer_before_print_scripts() {
		do_action( 'wfacp_footer_before_print_scripts' );
	}

	public function wfacp_footer_after_print_scripts() {
		do_action( 'wfacp_footer_after_print_scripts' );
	}


	public function get_mobile_mini_cart_collapsible_title() {

		if ( isset( $this->form_data['cart_collapse_title'] ) && '' !== $this->form_data['cart_collapse_title'] ) {
			return $this->form_data['cart_collapse_title'];
		}

		return parent::get_mobile_mini_cart_collapsible_title();

	}

	public function enable_collapsed_coupon_field() {
		if ( isset( $this->form_data['collapse_enable_coupon'] ) && $this->form_data['collapse_enable_coupon'] != '' ) {
			return $this->form_data['collapse_enable_coupon'];
		}

		return false;
	}

	public function collapse_enable_coupon_collapsible() {
		if ( isset( $this->form_data['collapse_enable_coupon_collapsible'] ) && $this->form_data['collapse_enable_coupon_collapsible'] != '' ) {
			return $this->form_data['collapse_enable_coupon_collapsible'];
		}

		return false;
	}

	public function collapse_order_quantity_switcher() {

		if ( isset( $this->form_data['collapse_order_quantity_switcher'] ) && $this->form_data['collapse_order_quantity_switcher'] != '' ) {
			$collapse_order_quantity_switcher = $this->form_data['collapse_order_quantity_switcher'];

			return $collapse_order_quantity_switcher;

		}

		return false;
	}

	public function collapse_order_delete_item() {

		if ( isset( $this->form_data['collapse_order_delete_item'] ) && $this->form_data['collapse_order_delete_item'] != '' ) {
			$collapse_order_delete_item = $this->form_data['collapse_order_delete_item'];

			return $collapse_order_delete_item;

		}

		return false;
	}


	public function get_mobile_mini_cart_expand_title() {
		if ( isset( $this->form_data['cart_expanded_title'] ) && '' !== $this->form_data['cart_expanded_title'] ) {
			return $this->form_data['cart_expanded_title'];
		}

		return parent::get_mobile_mini_cart_expand_title();

	}

	public function use_own_template() {
		return false;
	}


	public function breadcrumb_start() {
		$number_of_steps    = $this->get_step_count();
		$step_form_data     = [];
		$progress_form_data = [];

		if ( $this->form_data['enable_progress_bar'] == '' || $this->form_data['enable_progress_bar'] == 'no' ) {
			return;
		}

		$cls = 'wfacp_one_step';
		if ( $number_of_steps == 2 ) {
			$cls = 'wfacp_two_step';
		} elseif ( $number_of_steps == 3 ) {
			$cls = 'wfacp_three_step';
		}

		$progress_bar_type = isset( $this->form_data['select_type'] ) ? $this->form_data['select_type'] : '';
		$devices           = [ $progress_bar_type ];

		if ( isset( $this->form_data['enable_progress_bar'] ) ) {
			$devices[] = 'wfacp_desktop';
		}

		if ( isset( $this->form_data['enable_progress_bar_tablet'] ) ) {
			$devices[] = 'wfacp_tablet';
		}
		if ( isset( $this->form_data['enable_progress_bar_mobile'] ) ) {
			$devices[] = 'wfacp_mobile';
		}

		$deviceClass = implode( ' ', $devices );
		$wrapClass   = [];

		if ( ! empty( $cls ) ) {
			$wrapClass[] = $cls;
		}

		if ( empty( $deviceClass ) ) {
			$deviceClass = 'wfacp_not_active';
		}

		$wrapClass[] = $deviceClass;

		$stepWrapClass = '';
		if ( is_array( $wrapClass ) && count( $wrapClass ) > 0 ) {
			$stepWrapClass = implode( ' ', $wrapClass );
		}


		ob_start();
		echo "<div class='$stepWrapClass'>";

		for ( $i = 0; $i < $number_of_steps; $i ++ ) {


			$tab_heading_key    = '';
			$tab_subheading_key = '';

			$progress_bar_text = '';

			if ( 'tab' == $progress_bar_type ) {
				$tab_heading_key    = "step_" . $i . "_heading";
				$tab_subheading_key = "step_" . $i . "_subheading";
			}


			if ( $tab_heading_key != '' && is_array( $this->form_data ) && isset( $this->form_data[ $tab_heading_key ] ) ) {
				$step_form_data[ $i ]['heading'] = $this->form_data[ $tab_heading_key ];
			}
			if ( $tab_subheading_key != '' && is_array( $this->form_data ) && isset( $this->form_data[ $tab_subheading_key ] ) ) {
				$step_form_data[ $i ]['subheading']   = $this->form_data[ $tab_subheading_key ];
				$this->set_bredcrumb_data['tab_data'] = $step_form_data;
			}
			if ( 'tab' !== $progress_bar_type ) {
				$progress_bar_text = "step_" . $i . "_progress_bar";
			}

			if ( isset( $this->form_data['select_type'] ) && $this->form_data['select_type'] == 'bredcrumb' ) {
				$progress_bar_text = "step_" . $i . "_bredcrumb";
			}

			if ( $progress_bar_text != '' && is_array( $this->form_data ) && isset( $this->form_data[ $progress_bar_text ] ) ) {
				$progress_form_data[]                      = $this->form_data[ $progress_bar_text ];
				$this->set_bredcrumb_data['progress_data'] = $progress_form_data;
			}
		}

		if ( ( is_array( $step_form_data ) && count( $step_form_data ) > 0 ) ) {
			?>

            <div class="wfacp_form_steps">
                <div class="wfacp-payment-title wfacp-hg-by-box">
                    <div class="wfacp-payment-tab-wrapper">
						<?php
						$count          = 1;
						$count_of_steps = sizeof( $step_form_data );
						$steps          = [ 'single_step', 'two_step', 'third_step' ];

						$addfull_width = "full_width_cls";
						if ( $count_of_steps == 2 ) {
							$addfull_width = "wfacpef_two_step";
						}
						if ( $count_of_steps == 3 ) {
							$addfull_width = "wfacpef_third_step";
						}
						$active_breadcrumb = apply_filters( 'wfacp_el_bread_crumb_active_class_key', 0, $this );
						foreach ( $step_form_data as $key => $value ) {

							if ( isset( $steps[ $key ] ) ) {
								$steps_count_here = $steps[ $key ];
							}

							$active        = '';
							$bread_visited = '';
							if ( $count == 2 ) {
								$page_class = 'two_step';
							} else if ( $count == 3 ) {
								$page_class = 'third_step';
							} else {
								$page_class = 'single_step';
							}

							if ( $active_breadcrumb > $key ) {
								$bread_visited = 'visited_cls';
							}
							if ( $key == $active_breadcrumb ) {
								$active = 'wfacp-active visited_cls';
							}

							$activeClass = apply_filters( 'wfacp_embed_active_progress_bar', $active, $count, $number_of_steps );
							?>
                            <div class="wfacp-payment-tab-list <?php echo $activeClass . ' ' . $page_class . " " . $addfull_width . ' ' . $bread_visited; ?>  wfacp-tab<?php echo $count; ?>" step="<?php echo $steps_count_here; ?>">
                                <div class="wfacp-order2StepNumber"><?php echo $count; ?></div>
                                <div class="wfacp-order2StepHeaderText">
                                    <div class="wfacp-order2StepTitle wfacp-order2StepTitleS1 wfacp_tcolor"><?php echo $value['heading']; ?></div>
                                    <div class="wfacp-order2StepSubTitle wfacp-order2StepSubTitleS1 wfacp_tcolor"><?php echo $value['subheading']; ?></div>
                                </div>
                            </div>
							<?php
							$count ++;
						}
						?>
                    </div>
                </div>
            </div>
			<?php
		}

		$steps_arr = [ 'single_step', 'two_step', 'third_step' ];
		if ( 'progress_bar' == $progress_bar_type ) {
			if ( ( is_array( $progress_form_data ) && count( $progress_form_data ) > 0 ) ) {

				echo '<div class="wfacp_custom_breadcrumb wfacp_custom_breadcrumb_el">';
				echo '<div class=wfacp_steps_wrap>';
				echo '<div class=wfacp_steps_sec>';

				echo '<ul>';

				do_action( 'wfacp_before_' . $progress_bar_type, $progress_form_data );

				foreach ( $progress_form_data as $key => $value ) {
					$active = '';

					if ( $key == 0 ) {
						$active = 'wfacp_bred_active wfacp_bred_visited';
					}

					$step = ( isset( $steps_arr[ $key ] ) ) ? $steps_arr[ $key ] : '';

					$active = apply_filters( 'wfacp_layout_9_active_progress_bar', $active, $step );

					echo "<li class='wfacp_step_$key wfacp_bred $active $step' step='$step' ><a href='javascript:void(0)' class='wfacp_step_text_have' data-text='" . sanitize_title( $value ) . "'>$value</a> </li>";
				}
				do_action( 'wfacp_after_breadcrumb' );
				echo '</ul></div></div></div>';
			}
		}
		echo "</div>";
		$result = ob_get_clean();

		$this->stepsData[ $progress_bar_type ] = $result;

		if ( "progress_bar" !== $progress_bar_type ) {
			echo $result;
		}
	}

	public function add_form_steps() {

		$number_of_steps = $this->get_step_count();
		$steps_arr       = [ 'single_step', 'two_step', 'third_step' ];

		$devices = [];

		if ( $number_of_steps <= 1 || $this->form_data['enable_progress_bar'] == '' || $this->form_data['enable_progress_bar'] == 'no' ) {
			return;
		}

		if ( isset( $this->form_data['enable_progress_bar'] ) && "yes" === $this->form_data['enable_progress_bar'] ) {
			$devices[] = 'wfacp_desktop';
		}

		if ( isset( $this->form_data['enable_progress_bar_tablet'] ) && "yes" === $this->form_data['enable_progress_bar_tablet'] ) {
			$devices[] = 'wfacp_tablet';
		}
		if ( isset( $this->form_data['enable_progress_bar_mobile'] ) && "yes" === $this->form_data['enable_progress_bar_mobile'] ) {
			$devices[] = 'wfacp_mobile';
		}

		$deviceClass = implode( ' ', $devices );

		if ( empty( $deviceClass ) ) {
			$deviceClass = 'wfacp_not_active';
		}

		$select_type = $this->form_data['select_type'];

		echo "<div class='$deviceClass $select_type' >";
		if ( isset( $this->form_data['select_type'] ) && 'bredcrumb' == $this->form_data['select_type'] ) {
			if ( isset( $this->set_bredcrumb_data['progress_data'] ) && is_array( $this->set_bredcrumb_data['progress_data'] ) && $this->set_bredcrumb_data['progress_data'] > 0 ) {
				$progress_form_data = $this->set_bredcrumb_data['progress_data'];

				printf( '<div class="%s">', "wfacp_steps_wrap wfacp_breadcrumb_wrap_here" );
				echo '<div class=wfacp_steps_sec>';

				echo '<ul>';

				do_action( 'wfacp_before_breadcrumb', $progress_form_data );

				$active_breadcrumb = apply_filters( 'wfacp_el_bread_crumb_active_class_key', 0, $this );
				foreach ( $progress_form_data as $key => $value ) {
					$active        = '';
					$bread_visited = '';
					if ( $active_breadcrumb > $key ) {
						$bread_visited = 'wfacp_bred_visited';
					}
					if ( $key == $active_breadcrumb ) {
						$active = 'wfacp_bred_active wfacp_bred_visited';
					}

					$step       = ( isset( $steps_arr[ $key ] ) ) ? $steps_arr[ $key ] : '';
					$text_class = ( ! empty( $value ) ) ? 'wfacp_step_text_have' : 'wfacp_step_text_nohave';
					echo "<li class='wfacp_step_$key wfacp_bred $bread_visited $active $step' step='$step'>";
					?>
                    <a href='javascript:void(0)' class="<?php echo $text_class; ?> wfacp_breadcrumb_link" data-text="<?php echo sanitize_title( $value ); ?>"><?php echo $value; ?></a>
					<?php

					echo '</li>';
				}
				do_action( 'wfacp_after_breadcrumb' );
				echo '</ul></div></div>';
			}

		}
		echo "</div>";

	}

	public function get_product_switcher_mobile_style() {

		if ( isset( $this->form_data['product_switcher_mobile_style'] ) && $this->form_data['product_switcher_mobile_style'] != '' ) {
			return $this->form_data['product_switcher_mobile_style'];
		}

		return parent::get_product_switcher_mobile_style();
	}

	public function add_body_class( $classes ) {
		$classes   = parent::add_body_class( $classes );
		$classes[] = 'wfacp_elementor_template';

		return $classes;
	}

	/**
	 * Wrap Order preview form in Embed form div start style
	 */

	public function add_checkout_preview_div_start() {
		echo '<div id="wfacp-e-form"><div id="wfacp-sec-wrapper">';
	}

	/**
	 * Wrap Order preview form in Embed form div start style
	 */

	public function add_checkout_preview_div_end() {
		echo '</div></div>';
	}

	/**
	 * Cart Link before the step bar
	 */
	public function before_cart_link() {

		$is_global_checkout = WFACP_Core()->public->is_checkout_override();

		if ( $is_global_checkout === false ) {
			return;
		}

		if ( ! isset( $this->form_data['step_cart_link_enable'] ) || $this->form_data['step_cart_link_enable'] == 'no' ) {
			return;
		}

		if ( ! isset( $this->form_data['select_type'] ) ) {
			return;
		}

		$select_type = $this->form_data['select_type'];
		$key         = "step_cart_" . $select_type . "_link";

		if ( ! isset( $this->form_data[ $key ] ) ) {
			return;
		}

		$cartName = $this->form_data[ $key ];


		$cart_page_id = wc_get_page_id( 'cart' );
		$cartURL      = $cart_page_id ? get_permalink( $cart_page_id ) : '';

		echo "<li class='df_cart_link wfacp_bred_visited'><a href='$cartURL'>$cartName</a></li>";
	}

	public function before_return_to_cart_link( $current_action ) {

		$is_global_checkout = WFACP_Core()->public->is_checkout_override();

		if ( $is_global_checkout === false ) {
			return;
		}

		if ( ! isset( $this->form_data['step_cart_link_enable'] ) || $this->form_data['step_cart_link_enable'] === 'no' ) {
			return;
		}
		if ( ! isset( $this->form_data['return_to_cart_text'] ) || $this->form_data['return_to_cart_text'] === 'no' ) {
			return;
		}

		if ( $current_action !== 'single_step' ) {
			return;
		}

		$cart_page_id = wc_get_page_id( 'cart' );
		$cartURL      = $cart_page_id ? get_permalink( $cart_page_id ) : '';
		?>

        <div class="btm_btn_sec wfacp_back_cart_link">
            <div class="wfacp-back-btn-wrap">
                <a href="<?php echo $cartURL; ?>"><?php echo $this->form_data['return_to_cart_text']; ?></a>
            </div>
        </div>
		<?php
	}

	public function display_pogress_bar() {

		if ( isset( $this->stepsData['progress_bar'] ) ) {
			if ( isset( $this->form_data['select_type'] ) && 'progress_bar' === $this->form_data['select_type'] ) {
				echo $this->stepsData['progress_bar'];
			}
		}
	}

	public function add_class_change_place_order( $btn_html ) {


		$stepCount = $this->get_step_count();


		if ( ! empty( $_GET['woo-paypal-return'] ) && ! empty( $_GET['token'] ) && ! empty( $_GET['PayerID'] ) ) {
			return $btn_html;
		}


		$output = '';

		$key = "payment_button_back_" . $stepCount . "_text";

		$black_backbtn_cls = '';
		if ( isset( $this->form_data[ $key ] ) && $this->form_data[ $key ] == '' ) {

			$black_backbtn_cls = 'wfacp_back_link_empty';

		}

		/* Button Icon list */

		$class = $this->add_button_icon( 'place_order' );
		$this->button_icon_subheading_styling( $class, $this->current_step );

		$output .= sprintf( '<div class="wfacp-order-place-btn-wrap %s">', $black_backbtn_cls );
		$output .= $btn_html;

		if ( $stepCount > 1 ) {


			if ( ! isset( $this->form_data[ $key ] ) ) {
				return $btn_html;
			}
			$back_btn_text = $this->form_data[ $key ];


			$last_step = 'single_step';
			if ( $this->current_step == 'third_step' ) {
				$last_step = 'two_step';
			}

			if ( $back_btn_text != '' ) {
				$output .= "<div class='place_order_back_btn wfacp_none_class '><a class='wfacp_back_page_button' data-next-step='" . $last_step . "' data-current-step='" . $this->current_step . "' href='javascript:void(0)'>" . __( $back_btn_text, 'woofunnels-aero-checkout' ) . '</a> </div>';
			}

		}


		$output .= '</div>';


		return $output;
	}

	public function activate_theme_hook() {
		if ( function_exists( 'flatsome_mobile_menu' ) ) {
			add_action( 'wp_footer', 'flatsome_mobile_menu' );
		}
	}


	/* --------------------------------Coupon Button Text--------------------------------------------- */
	public function display_image_in_collapsible_order_summary() {
		return isset( $this->form_data['order_summary_enable_product_image_collapsed'] ) && 'yes' === trim( $this->form_data['order_summary_enable_product_image_collapsed'] );
	}

	public function get_collapsible_coupon_button_text() {
		if ( isset( $this->form_data['collapse_coupon_button_text'] ) && '' !== $this->form_data['collapse_coupon_button_text'] ) {
			return $this->form_data['collapse_coupon_button_text'];
		}

		return parent::get_coupon_button_text();

	}

	public function get_form_coupon_button_text() {
		if ( isset( $this->form_data['form_coupon_button_text'] ) && '' !== $this->form_data['form_coupon_button_text'] ) {
			return $this->form_data['form_coupon_button_text'];
		}

		return parent::get_coupon_button_text();

	}

	public function get_mini_cart_coupon_button_text() {
		if ( isset( $this->mini_cart_data['mini_cart_coupon_button_text'] ) && '' !== $this->mini_cart_data['mini_cart_coupon_button_text'] ) {
			return $this->mini_cart_data['mini_cart_coupon_button_text'];
		}

		return parent::get_coupon_button_text();

	}

	/* --------------------------------Place Order icons--------------------------------------- */

	public function add_button_icon( $i = 1 ) {
		$black_backbtn_cls = [ 'class' => 'bwf_button_sec', 'step' => $i ];
		$icon_position     = 'wfacp-pre-icon';


		if ( isset( $this->form_data[ 'enable_icon_with_place_order_' . $i ] ) && "yes" === $this->form_data[ 'enable_icon_with_place_order_' . $i ] ) {


			$content = 'before';
			$margin  = 'right';
			if ( $icon_position == 'wfacp-post-icon' ) {
				$content = 'after';
				$margin  = 'left';
			}

			$black_backbtn_cls['position'] = $icon_position;
			$black_backbtn_cls['content']  = $content;
			$black_backbtn_cls['margin']   = $margin;
		}

		if ( isset( $this->form_data[ 'icons_with_place_order_list_' . $i ] ) ) {

			$black_backbtn_cls['icon'] = $this->form_data[ 'icons_with_place_order_list_' . $i ];

			if ( strpos( $black_backbtn_cls['icon'], '"\"' ) == false ) {
				$black_backbtn_cls['icon'] = '"\"' . $black_backbtn_cls['icon'];
				$black_backbtn_cls['icon'] = str_replace( '"', '', $black_backbtn_cls['icon'] );
			}
		}


		/* button subheading */

		$black_backbtn_cls['button_subheading'] = '';
		if ( isset( $this->form_data[ 'step_' . $i . '_text_after_place_order' ] ) && ! empty( $this->form_data[ 'step_' . $i . '_text_after_place_order' ] ) ) {
			$black_backbtn_cls['button_subheading']          = $this->form_data[ 'step_' . $i . '_text_after_place_order' ];
			$black_backbtn_cls['button_subheading_position'] = 'after';
			if ( ! empty( $icon_position ) ) {
				if ( $icon_position == 'wfacp-pre-icon' ) {
					$black_backbtn_cls['button_subheading_position'] = 'after';
				} else {
					$black_backbtn_cls['button_subheading_position'] = 'before';
				}

			}
		}


		return $black_backbtn_cls;
	}

	public function button_icon_subheading_styling( $class, $current ) {
		$icon                       = '';
		$content                    = '';
		$margin                     = '';
		$button_subheading          = '';
		$button_subheading_position = '';


		if ( isset( $class['icon'] ) ) {
			$icon = str_replace( 'aero-', '', $class['icon'] );
		}
		if ( isset( $class['content'] ) ) {
			$content = $class['content'];
		}
		if ( isset( $class['margin'] ) ) {
			$margin = $class['margin'];
		}
		if ( isset( $class['button_subheading'] ) ) {
			$button_subheading = $class['button_subheading'];
		}
		if ( isset( $class['button_subheading_position'] ) ) {
			$button_subheading_position = $class['button_subheading_position'];
		}
		if ( isset( $class['step'] ) ) {
			$form_step = $class['step'];

		}


		if ( ! empty( $icon ) && ! empty( $current ) && ! empty( $margin ) ) {

			if ( $form_step == 'place_order' ) {
				echo "<style>";
				echo 'body #wfacp-e-form .' . $current . ' #place_order:' . $content . "{content:'$icon';font-family: 'bwf-icons' !important; display: inline-block !important;margin-$margin:8px;position: relative;text-transform: none;top:1px;}";
				echo "</style>";
			} else {
				echo "<style>";
				echo 'body #wfacp-e-form .' . $current . ' .wfacp-next-btn-wrap button:' . $content . "{content:'$icon'; font-family: 'bwf-icons' !important; display: inline-block !important;margin-$margin:8px;position: relative;text-transform: none;top:1px;}";
				echo "</style>";
			}

		}


		if ( ! empty( $button_subheading ) && ! empty( $button_subheading_position ) ) {

			$content           = $button_subheading_position;
			$button_subheading = do_shortcode( $button_subheading );
			$content1          = 'before';
			if ( $form_step == 'place_order' ) {

				echo "<style>";
				echo 'body #wfacp-e-form .' . $current . ' #place_order:' . $content1 . "{top:4px;}";
				echo '#wfacp-e-form .' . $current . ' #place_order:' . $content . "{content:'$button_subheading'; display: inline-block !important;position: relative;}";
				echo '#wfacp-e-form .' . $current . ' button#place_order' . "{display:inline-block;}";
				echo '#wfacp-e-form .' . $current . ' #place_order:' . $content . "{display: block !important;}";
				echo "</style>";

			} else {
				echo "<style>";
				echo 'body #wfacp-e-form .' . $current . ' .wfacp-next-btn-wrap button:' . $content1 . "{top:4px;}";
				echo '#wfacp-e-form .' . $current . ' .wfacp-next-btn-wrap button:' . $content . "{content:'$button_subheading';  display: inline-block !important;position: relative;}";
				echo '#wfacp-e-form .' . $current . ' .wfacp-next-btn-wrap button' . "{display:inline-block;}";
				echo '#wfacp-e-form .' . $current . ' .wfacp-next-btn-wrap button:' . $content . "{display: block !important;}";
				echo "</style>";
			}

		} else {

			if ( $form_step == 'place_order' ) {

				echo "<style>";
				echo '#wfacp-e-form .' . $current . ' #place_order' . "{-js-display: inline-flex;display: inline-flex;align-items: center;justify-content: center;}";

				echo "</style>";
			} else {

				echo "<style>";
				echo '#wfacp-e-form .' . $current . ' .wfacp-next-btn-wrap button' . "{-js-display: inline-flex;display: inline-flex;align-items: center;justify-content: center;}";
				echo "</style>";
			}

		}
	}



	/*------------------------------Primay Color Fields-------------------------------------*/
	public function primary_colors() {
		$template = wfacp_template();


		$primary_color_value = '';

		if ( isset( $template->form_data['default_primary_color'] ) && ! empty( $template->form_data['default_primary_color'] ) ) {
			$primary_color_value = $template->form_data['default_primary_color'];
		}

		if ( isset( $template->form_data['__globals__'] ) && isset( $template->form_data['__globals__']['default_primary_color'] ) && ! empty( $template->form_data['__globals__']['default_primary_color'] ) ) {
			$primary_color_value = $template->form_data['__globals__']['default_primary_color'];
		}

		if ( empty( $primary_color_value ) ) {
			return;
		}


		if ( false !== strpos( $primary_color_value, '?id=' ) ) {
			$global_args         = explode( '?id=', $primary_color_value );
			$id                  = $global_args[1];
			$primary_color_value = "var( --e-global-color-$id )";
		}

		$color_selectors = [];
		$primary_color   = [
			'{{WRAPPER}} #wfacp-e-form  #payment li.wc_payment_method input.input-radio:checked::before'                    => 'background-color:{{VALUE}};',
			'{{WRAPPER}} #wfacp-e-form  #payment.wc_payment_method input[type=radio]:checked:before'                        => 'background-color:{{VALUE}};',
			'{{WRAPPER}} #wfacp-e-form  button[type=submit]:not(.white):not(.black)'                                        => 'background-color:{{VALUE}};',
			'{{WRAPPER}} #wfacp-e-form  button[type=button]:not(.white):not(.black)'                                        => 'background-color:{{VALUE}};',
			'{{WRAPPER}} #wfacp-e-form .wfacp-coupon-section .wfacp-coupon-page .wfacp-coupon-field-btn'                    => 'background-color:{{VALUE}};',
			'{{WRAPPER}} #wfacp-e-form input[type=checkbox]:checked'                                                        => 'background-color:{{VALUE}};',
			'{{WRAPPER}} #wfacp-e-form #payment input[type=checkbox]:checked'                                               => 'background-color:{{VALUE}};',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-input-wrapper .wfacp-form-control:checked' => 'background-color:{{VALUE}};',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type=checkbox]:checked'                           => 'background-color:{{VALUE}};',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .button.button#place_order'                                         => 'background-color:{{VALUE}};',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form .button.wfacp_next_page_button'                                     => 'background-color:{{VALUE}};',
			'{{WRAPPER}} #wfacp-e-form .wfacp_main_form  .wfacp_payment #ppcp-hosted-fields .button'                        => 'background-color:{{VALUE}};',
		];


		$color_selectors['{{WRAPPER}} #wfacp-e-form .form-row:not(.woocommerce-invalid-required-field) .wfacp-form-control:not(.input-checkbox):focus']                                                                                               = 'border-color:{{VALUE}} ;';
		$color_selectors['{{WRAPPER}} #wfacp-e-form  p.form-row:not(.woocommerce-invalid-required-field) .wfacp-form-control:not(.input-checkbox):focus']                                                                                             = 'box-shadow:0 0 0 1px {{VALUE}} ;';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form .form-row:not(.woocommerce-invalid-required-field) .woocommerce-input-wrapper .select2-container .select2-selection--single .select2-selection__rendered:focus']                 = 'border-color:{{VALUE}} ;';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .form-row:not(.woocommerce-invalid-required-field) .woocommerce-input-wrapper .select2-container .select2-selection--single .select2-selection__rendered:focus']     = 'box-shadow:0 0 0 1px {{VALUE}};';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form .form-row:not(.woocommerce-invalid-required-field) .woocommerce-input-wrapper .select2-container .select2-selection--single:focus>span.select2-selection__rendered']             = 'box-shadow:0 0 0 1px {{VALUE}} ;';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #payment li.wc_payment_method input.input-radio:checked']                                                                                                            = 'border-color:{{VALUE}};';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #payment.wc_payment_method input[type=radio]:checked']                                                                                                               = 'border-color:{{VALUE}};';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type=radio]:checked']                                                                                                                                          = 'border-color:{{VALUE}};';
		$color_selectors['{{WRAPPER}} #wfacp-e-form input[type=radio]:checked']                                                                                                                                                                       = 'border-color:{{VALUE}};';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #add_payment_method #payment ul.payment_methods li input[type=radio]:checked']                                                                                       = 'border-color:{{VALUE}};';
		$color_selectors['{{WRAPPER}} #wfacp-e-form #payment ul.payment_methods li input[type=radio]:checked']                                                                                                                                        = 'border-color:{{VALUE}};';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type=radio]:checked']                                                                                                                                          = 'border-color:{{VALUE}};';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-cart #payment ul.payment_methods li input[type=radio]:checked']                                                                                         = 'border-color:{{VALUE}};';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .woocommerce-checkout #payment ul.payment_methods li input[type=radio]:checked']                                                                                     = 'border-color:{{VALUE}};';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce #wfacp_checkout_form input[type=radio]:checked']                                                                                                                     = 'border-color:{{VALUE}};';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp-form input[type=checkbox]:checked']                                                                                                                                                        = 'border-color:{{VALUE}};';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form #payment input[type=checkbox]:checked']                                                                                                                                          = 'border-color:{{VALUE}};';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form .woocommerce-input-wrapper .wfacp-form-control:checked']                                                                                                                         = 'border-color:{{VALUE}};';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form input[type=checkbox]:checked']                                                                                                                                                   = 'border-color:{{VALUE}};';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce .form-row:not(.woocommerce-invalid-required-field) .woocommerce-input-wrapper .select2-container .select2-selection--single:focus>span.select2-selection__rendered'] = 'border-color:{{VALUE}};';

		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form #payment li.wc_payment_method input.input-radio:checked']                      = 'border-width:5px;';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form #payment.wc_payment_method input[type=radio]:checked']                         = 'border-width:5px;';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form input[type=radio]:checked']                                                    = 'border-width:5px;';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form #add_payment_method #payment ul.payment_methods li input[type=radio]:checked'] = 'border-width:5px;';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form input[type=checkbox]:after']                                                   = 'display: block;';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form input[type=checkbox]:before']                                                  = 'display: none;';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form input[type=checkbox]:checked']                                                 = 'border-width: 8px;';
		$color_selectors['{{WRAPPER}} #wfacp-e-form #payment li.wc_payment_method input.input-radio:checked::before']                               = 'display:none;';
		$color_selectors['{{WRAPPER}} #wfacp-e-form #payment.wc_payment_method input[type=radio]:checked:before']                                   = 'display:none;';
		$color_selectors['{{WRAPPER}} #wfacp-e-form input[type=radio]:checked:before']                                                              = 'display:none;';
		$color_selectors['{{WRAPPER}} #wfacp-e-form .wfacp_main_form.woocommerce input[type=radio]:checked:before']                                 = 'display:none;';

		$selectors = array_merge( $primary_color, $color_selectors );


		echo "<style>";
		foreach ( $selectors as $key => $value ) {

			$key = str_replace( '{{WRAPPER}} ', 'body ', $key );



			if ( false !== strpos( $value,'{{VALUE}}' ) ) {
				echo $key.'{'.str_replace( '{{VALUE}}', $primary_color_value, $value ).'}';
			}else{
				echo $key.'{'.$value.'}';
			}


		}
		echo "</style>";


	}

}
