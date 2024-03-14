<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
/**
 * @var $instance WFACP_Template_Common
 */
$checkout = WC()->checkout();
if ( apply_filters( 'wfacp_skip_form_printing', false ) ) {
	return;
}
do_action( 'wfacp_outside_header' );
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );

	return;
}
$checkout->get_checkout_fields();
$instance       = wfacp_template();
$totalStepCount = $instance->get_step_count();
$stepClassName  = 'wfacp_single_step_form';
$stepMultiClass = 'wfacp_single_step_form';
if ( $totalStepCount > 1 && $totalStepCount == 2 ) {
	$stepMultiClass = "wfacp_two_step";
} else {
	$stepMultiClass = "wfacp_three_step";
}
if ( $totalStepCount > 1 ) {
	$stepClassName = 'wfacp_single_multi_form';
}
do_action( 'wfacp_before_form' );
$is_global_checkout_f = WFACP_Core()->public->is_checkout_override();
$global_cls           = "";
$form_class           = [];
if ( ! empty( $stepClassName ) ) {
	$form_class[] = $stepClassName;
}
if ( ! empty( $stepMultiClass ) ) {
	$form_class[] = $stepMultiClass;
}
if ( $is_global_checkout_f === true ) {
	$form_class[] = "wfacp_global_checkout_wrap";
}
$is_theme_builder = WFACP_Common::is_theme_builder();
?>
    <div class="wfacp_main_form woocommerce <?php echo implode( ' ', $form_class ); ?>">
		<?php $payment_needed   = false;
		$stepData               = [];
		$checkout               = WC()->checkout();
		$fieldsets              = $instance->get_fieldsets();
		$current_step           = $instance->get_current_step();
		$selected_template_slug = $instance->get_template_slug();
		$template_type          = $instance->get_template_type();
		$have_shipping_address  = $instance->have_shipping_address();
		$have_billing_address   = $instance->have_billing_address();
		$shipping_billing_index = $instance->get_shipping_billing_index();
		$already_form_field     = [];
		if ( ! $is_theme_builder ) {
			do_action( 'woocommerce_before_checkout_form_cart_notices' );
			do_action( 'woocommerce_check_cart_items' );
		}
		/**
		 * previous form_internal_css calling via include_once
		 * Now calling via  include because of bug created in order bump suddenly payment gateway hides
		 * IN order form addon we use do_shortcode at wp hook form_internal_css included once that time
		 *But when printing form in page formal_internal_css not included again due include once
		 *
		 */
		include __DIR__ . '/form_internal_css.php';
		$print_aero_form = true;
		if ( ! $is_theme_builder ) {

			WC()->cart->calculate_totals();
			if ( empty( $_POST ) && wc_notice_count( 'error' ) > 0 && apply_filters( 'wfacp_print_cart_error_notice', true, wc_notice_count( 'error' ) ) ) { // WPCS: input var ok, CSRF ok.

				remove_action( 'woocommerce_get_cart_page_permalink', [ $instance, 'change_cancel_url' ], 999 );
				wc_get_template( 'checkout/cart-errors.php', array( 'checkout' => $checkout ) );
				wc_clear_notices();
				$print_aero_form = false;
			}
		}
		$print_aero_form = apply_filters( 'wfacp_force_print_form', $print_aero_form, $instance );
		if ( true == $print_aero_form ) {
			do_action( 'woocommerce_before_checkout_form', $checkout );
			$required_messages          = [];
			$billing_country_find       = false;
			$permalink                  = get_the_permalink();
			$cart_contains_subscription = 0;
			if ( class_exists( 'WC_Subscriptions_Cart' ) && WC_Subscriptions_Cart::cart_contains_subscription() ) {
				$cart_contains_subscription = 1;
			}
			?>
            <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $permalink ); ?>" enctype="multipart/form-data" id="wfacp_checkout_form">
                <input type="hidden" name="_wfacp_post_id" class="_wfacp_post_id" value="<?php esc_html_e( WFACP_Common::get_id() ); ?>">
                <input type="hidden" name="wfacp_cart_hash" value="<?php esc_html_e( WC()->session->get( 'wfacp_cart_hash', '' ) ); ?>">
                <input type="hidden" name="wfacp_has_active_multi_checkout" id="wfacp_has_active_multi_checkout" value="">
                <input type="hidden" id="billing_shipping_index" value="<?php esc_html_e( $shipping_billing_index ); ?>">
                <input type="hidden" id="wfacp_source" name="wfacp_source" value="<?php echo esc_url( $permalink ); ?>">
                <input type="hidden" id="product_switcher_need_refresh" name="product_switcher_need_refresh" value="0">
                <input type="hidden" id="wfacp_cart_contains_subscription" name="wfacp_cart_contains_subscription" value="<?php esc_html_e( $cart_contains_subscription ); ?>">

                <input type="hidden" id="wfacp_exchange_keys" name="wfacp_exchange_keys" class="wfacp_exchange_keys" value="">
                <input type="hidden" id="wfacp_input_hidden_data" name="wfacp_input_hidden_data" class="wfacp_input_hidden_data" value="{}">
                <input type="hidden" id="wfacp_input_phone_field" name="wfacp_input_phone_field" class="wfacp_input_phone_field" value="{}">
                <input type="hidden" id="wfacp_timezone" name="wfacp_timezone" value="">
				<?php
				if ( $have_billing_address && 'billing' === $shipping_billing_index ) {
					echo '<input type="hidden" name="wfacp_billing_same_as_shipping" id="wfacp_billing_same_as_shipping" value="0">';
				}
				if ( $have_billing_address ) {
					echo '<input type="hidden" name="wfacp_billing_address_present" id="wfacp_billing_address_present" value="yes">';
				}
				if ( ! $have_shipping_address && ! $have_billing_address && class_exists( 'WC_Geolocation' ) ) {
					$default_country      = WFACP_Common::get_base_country( 'billing_country', 'geolocation' );
					$billing_country_find = true;
					echo '<input type="hidden" name="billing_country" id="billing_country" value="' . esc_html( $default_country ) . '" />';
				}

				do_action( 'wfacp_before_checkout_form_fields', $checkout );
				do_action( 'woocommerce_checkout_before_customer_details' );            // check last is empty means user want last step to payment gateway
				if ( ! isset( $fieldsets[ $current_step ] ) ) {
					$fieldsets[ $current_step ] = [];
				}
				foreach ( $fieldsets as $step => $sections ) {
					do_action( 'wfacp_template_before_step', $step, $sections );
					$last_step = '';
					if ( $current_step == $step ) {
						$last_step = 'wfacp_last_page';
					}
					?>
                    <div class="wfacp-left-panel wfacp_page <?php echo $template_type . ' ' . $step . ' ' . $last_step; ?>" data-step="<?php echo $step ?>">
						<?php
						$count_increment = 0;
						do_action( 'wfacp_form_' . $step . '_start', $step, $instance, $last_step );
						if ( ! empty( $sections ) ) {
							foreach ( $sections as $section_index => $section ) {
								if ( ! isset( $section['fields'] ) || count( $section['fields'] ) == 0 ) {
									continue;
								}
								$sizeofSectionFields = sizeof( $section['fields'] );
								$last_key_arr        = end( $section['fields'] );
								$sectionLastKey      = isset( $last_key_arr['id'] ) ? $last_key_arr['id'] : '';
								$section             = apply_filters( 'wfacp_form_section', $section, $section_index, $step );
								if ( apply_filters( 'wfacp_hide_section', false, $section, $section_index, $step ) ) {
									continue;
								}
								$fields        = $section['fields'];
								$custom_class  = 'step_' . $section_index;
								$section_class = 'form_section_' . $step . '_' . $section_index . '_' . $selected_template_slug . ' ' . $section['class'];
								if ( isset( $section['html_fields'] ) && is_array( $section['html_fields'] ) ) {
									$html_fields = $section['html_fields'];
									$class_here  = '';
									if ( array_key_exists( 'shipping_calculator', $html_fields ) ) {
										$class_here = ' wfacp_shipping_method';
									}
									if ( array_key_exists( 'product_switching', $html_fields ) ) {
										$class_here = ' wfacp_product_switcher';
									}
									if ( array_key_exists( 'order_summary', $html_fields ) ) {
										$class_here = ' wfacp_order_summary_box';
									}
									if ( array_key_exists( 'order_coupon', $html_fields ) ) {
										$class_here = ' wfacp_order_coupon_box';
									}
									if ( false == strpos( $class_here, $section_class ) ) {
										if ( isset( $section['fields'] ) && count( $section['fields'] ) == 1 ) {
											$section_class .= $class_here;
										} else {
											$class_here = '';
										}
										$section_class .= $class_here;
									}
								}
								do_action( 'wfacp_template_section_start', $step, $section_index, $section );
								do_action( 'wfacp_template_section_' . $section_index . '_' . $step . '_start', $step, $section_index, $section );
								?>
                                <div class="wfacp-section wfacp-hg-by-box <?php echo $custom_class . ' ' . $section_class ?>" data-field-count="<?php echo count( $fields ) ?>">
                                    <div class="wfacp_internal_form_wrap wfacp-comm-title <?php echo $instance->get_heading_title_class() ?>">
                                        <h2 class="wfacp_section_heading wfacp_section_title <?php echo $instance->get_heading_class(); ?>"><?php echo $section['name'] ?></h2>
										<?php
										if ( isset( $section['sub_heading'] ) && '' != $section['sub_heading'] ) {
											?>
                                            <h4 class="<?php echo $instance->get_sub_heading_class(); ?>"><?php echo $section['sub_heading'] ?></h4>
											<?php
										}
										?>
                                    </div>
									<?php do_action( 'wfacp_template_after_' . $section_index . '_' . $step . '_section_heading', $step, $section_index, $section ); ?>
                                    <div class="wfacp-comm-form-detail clearfix">
                                        <div class="wfacp-row">
											<?php
											do_action( 'wfacp_template_before_' . $section_index . '_' . $step . '_section_form_print', $step, $section_index, $section );
											do_action( 'wfacp_template_before_section', $step, $section['fields'] );
											$counterInnerFields = 1;
											foreach ( $fields as $field ) {
												$payment_needed = true;
												$key            = isset( $field['id'] ) ? $field['id'] : '';
												$field          = apply_filters( 'wfacp_forms_field', $field, $key );
												if ( empty( $field ) ) {
													continue;
												}
												if ( isset( $field['name'] ) && '' !== $field['name'] ) {
													$key = $field['name'];
												}
												if ( 'billing_email' === $key && isset( $field['placeholder'] ) && 'abc@exmple.com' === $field['placeholder'] ) {
													$field['placeholder'] = ' ';
												}
												if ( $sectionLastKey == $key ) {
													$field['class'][] = 'wfacp_last_section_fields';
												}
												if ( 'billing_country' === $key ) {
													$billing_country_find = true;
												}
												if ( isset( $field['country_field'], $fields[ $field['country_field'] ] ) ) {
													$field['country'] = $checkout->get_value( $field['country_field'] );
												}
												$field_value = $checkout->get_value( $key );
												$field_value = apply_filters( 'wfacp_default_values', $field_value, $key, $field );
												if ( in_array( $key, [ 'billing_same_as_shipping', 'shipping_same_as_billing' ] ) ) {
													$field_value = null;
												}
												do_action( 'wfacp_before_' . $key . '_field', $key, $field, $field_value );
												if ( ! is_null( $field_value ) && '' !== $field_value ) {
													$field['class'][] = 'wfacp-anim-wrap';
												}

												if ( isset( $field['is_wfacp_field'] ) && isset( $field['type'] ) && $field['type'] == 'select' ) {
													$field['class'][] = 'wfacp_drop_list';
													if ( ! isset( $field['multiselect_maximum'] ) ) {
														$field['class'][] = 'wfacp_dropdown';
													}
												}

												// Allow Blank key. We add Divider (Div wrapper for address field ) when form is not saved & served directly then address field wrapper div not closed
												if ( ! isset( $already_form_field[ $key ] ) || empty( $key ) ) {
													wfacp_form_field( $key, $field, $field_value );
													$already_form_field[ $key ] = 'yes';
												}


												if ( 'wfacp_after_' . $key . '_field' !== 'wfacp_after_order_total_field' ) {
													do_action( 'wfacp_after_' . $key . '_field', $key, $field, $field_value );
												}
												$counterInnerFields ++;
											}
											do_action( 'wfacp_template_after_section', $step, $sections, $section_index );
											do_action( 'wfacp_template_after_' . $section_index . '_' . $step . '_section_form_print', $step, $section_index, $section );
											?>
                                        </div>
                                    </div>
                                </div>
								<?php
								do_action( 'wfacp_template_section_' . $section_index . '_' . $step . '_end', $step, $section_index, $section );
								do_action( 'wfacp_template_section_end', $step, $section_index, $section );
								$count_increment ++;
							}
						}
						do_action( 'wfacp_form_' . $step . '_end', $step, $instance );
						if ( $step == $current_step ) {
							do_action( 'wfacp_template_before_payment', $step, $current_step, $instance );
							$instance->get_payment_box();
						}
						do_action( 'wfacp_template_after_step', $step, $current_step, $instance ); ?>
                    </div>
					<?php
				}
				if ( false == $billing_country_find ) {
					$default_country = WFACP_Common::get_base_country( 'billing_country', 'geolocation' );
					echo "<input type='hidden' name='billing_country' id='billing_country' value='" . esc_html( $default_country ) . "'>";
				}
				do_action( 'woocommerce_checkout_after_customer_details' );
				if ( $have_shipping_address ) {
					$temp_st    = $have_billing_address;
					$is_checked = '';
					if ( ! $temp_st || 'billing' == $shipping_billing_index ) {
						$is_checked = 'checked';
					}
					echo( "<div id='ship-to-different-address'><input id='ship-to-different-address-checkbox' class='ship_to_different_address' type='checkbox' name='ship_to_different_address' style='display:none' " . esc_attr( $is_checked ) . " ></div>" );
				}
				do_action( 'wfacp_after_checkout_form_fields', $checkout );
				if ( function_exists( 'wc_get_container' ) && class_exists( '\Automattic\WooCommerce\Internal\Orders\OrderAttributionController' ) && class_exists( 'Automattic\WooCommerce\Internal\Features\FeaturesController' ) && $container = wc_get_container() ) {
					$order_attribute_instance = $container->get( \Automattic\WooCommerce\Internal\Orders\OrderAttributionController::class );

					if ( $order_attribute_instance instanceof \Automattic\WooCommerce\Internal\Orders\OrderAttributionController ) {

						$feature_enabled = $container->get( Automattic\WooCommerce\Internal\Features\FeaturesController::class );

						if ( $feature_enabled->feature_is_enabled( 'order_attribution' ) ) {

							if ( method_exists( $order_attribute_instance, 'get_fields' ) ) {
								foreach ( $order_attribute_instance->get_fields() as $field ) {
									printf( '<input type="hidden" name="%s" form="wfacp_checkout_form" value="" />', esc_attr( $order_attribute_instance->get_prefixed_field( $field ) ) );
								}
							} elseif ( method_exists( $order_attribute_instance, 'get_field_names' ) ) {
								foreach ( $order_attribute_instance->get_field_names() as $field ) {
									printf( '<input type="hidden" name="%s" form="wfacp_checkout_form" value="" />', esc_attr( $order_attribute_instance->get_prefixed_field_name( $field ) ) );
								}
							}

						}
					}
				}
				?>
            </form>
			<?php do_action( 'woocommerce_after_checkout_form', $checkout );
		}
		?>
    </div>
<?php
do_action( 'wfacp_after_form' );
?>