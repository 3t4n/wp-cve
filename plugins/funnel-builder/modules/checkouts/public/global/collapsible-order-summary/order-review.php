<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
$instance = wfacp_template();
add_filter( 'wp_get_attachment_image_attributes', 'WFACP_Common::remove_src_set' );
$template                             = wfacp_template();
$selected_template_slug               = $template->get_template_slug();
$selected_template_type               = $template->get_template_type();
$show_quantity_switcher               = $this->collapse_order_quantity_switcher();
$enable_delete_item                   = $this->collapse_order_delete_item();
$show_subscription_string_old_version = apply_filters( 'wfacp_show_subscription_string_old_version', false );
$hideImageCls                         = '';
$cart_image_filter                    = 'wfacp_cart_show_product_thumbnail';
if ( $this->template_type == 'elementor' || apply_filters( 'wfacp_show_product_thumbnail_collapsible_show', false ) ) {
	$cart_image_filter = 'wfacp_cart_show_product_thumbnail_collapsible';
}
$display_img  = false;
$product_data = isset( $product_data ) ? $product_data : [];
if ( is_null( WC()->cart ) || ! WC()->cart instanceof WC_Cart ) {
	return;
}
?>
<div class="wfacp_template_9_cart_item_details wfacp_min_cart_widget" data-delete-enabled="<?php echo $enable_delete_item ?>">
	<?php
	do_action( 'wfacp_before_mini_cart_html' );
	do_action( 'woocommerce_review_order_before_cart_contents' );
	?>
    <table class="wfacp_mini_cart_items wfacp_collapsible_summary shop_table woocommerce-checkout-review-order-table_layout_9 wfacp_order_sum <?php echo $instance->get_template_slug(); ?>" <?php echo WFACP_Common::get_fragments_attr() ?> >
        <tbody>
		<?php
		$wfacp_cart = WC()->cart->get_cart();
		do_action( 'woocommerce_review_order_before_cart_contents' );
		if ( empty( $wfacp_cart ) ) {
			echo "<tr><td>";
			WFACP_Common::show_cart_empty_message();
			echo "</td></tr>";
		} else {
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product               = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$yes_enable_delete_item = apply_filters( 'wfacp_mini_cart_enable_delete_item', $enable_delete_item, $cart_item, $cart_item_key );
				$enabled_delete_class   = "";
				if ( $yes_enable_delete_item === true ) {
					$enabled_delete_class = "wfacp_delete_active";
				}
				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$aero_item_key = '';
					$item_quantity = $cart_item['quantity'];
					if ( false == WFACP_Core()->public->is_checkout_override() && isset( $cart_item['_wfacp_product'] ) ) {
						$aero_item_key = $cart_item['_wfacp_product_key'];
						$temp          = WC()->session->get( 'wfacp_product_data_' . WFACP_Common::get_id() );

						if ( isset( $temp[ $aero_item_key ] ) ) {
							$is_aero_point = true;
							$product_data  = $temp[ $aero_item_key ];
							$disableQty    = '';
							$qty_step      = 1;
							if ( '' !== $cart_item_key ) {
								$qty_step      = 0;
								$item_quantity = $product_data['quantity'];
							}
						}
					}
					?>
                    <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ) . " " . $enabled_delete_class; ?> wfacp_product_row" cart_key="<?php echo $cart_item_key ?>">
                        <td class="product-name-area">
							<?php
							if ( apply_filters( $cart_image_filter, $display_img ) ) {
								$hideImageCls = 'wfacp_summary_img_true';
								$thumbnail    = WFACP_Common::get_product_image( $_product, [ 100, 100 ], $cart_item, $cart_item_key );
								$thumbnail    = apply_filters( 'wfacp_cart_image', $thumbnail, $_product );
								?>
                                <div class="product-image">
                                    <div class="wfacp-pro-thumb">
                                        <div class="wfacp-qty-ball">
                                            <div class="wfacp-qty-count">
                                                <span class="wfacp-pro-count"><?php echo $cart_item['quantity']; ?></span>
                                            </div>
                                        </div>
										<?php echo $thumbnail; ?>
                                    </div>
                                </div>
							<?php }
							?>
                            <div class="wfacp_order_summary_item_name <?php echo $hideImageCls; ?>">
								<?php
								/* second string true condition added this condition with specific server */
								ob_start();
								if ( true === wc_string_to_bool( $yes_enable_delete_item ) || "true" === $yes_enable_delete_item ) {
									$item_class = 'wfacp_mini_cart_remove_item_from_cart';

									$item_icon='<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M16.3394 9.32245C16.7434 8.94589 16.7657 8.31312 16.3891 7.90911C16.0126 7.50509 15.3798 7.48283 14.9758 7.85938L12.0497 10.5866L9.32245 7.66048C8.94589 7.25647 8.31312 7.23421 7.90911 7.61076C7.50509 7.98731 7.48283 8.62008 7.85938 9.0241L10.5866 11.9502L7.66048 14.6775C7.25647 15.054 7.23421 15.6868 7.61076 16.0908C7.98731 16.4948 8.62008 16.5171 9.0241 16.1405L11.9502 13.4133L14.6775 16.3394C15.054 16.7434 15.6868 16.7657 16.0908 16.3891C16.4948 16.0126 16.5171 15.3798 16.1405 14.9758L13.4133 12.0497L16.3394 9.32245Z" fill="currentColor"/>
  <path fill-rule="evenodd" clip-rule="evenodd" d="M1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12C23 18.0751 18.0751 23 12 23C5.92487 23 1 18.0751 1 12ZM12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21Z" fill="currentColor"/>
</svg>';

									echo sprintf( '<div class="wfacp_delete_item_wrap"> <a href="javascript:void(0)" class="%s" data-cart_key="%s" data-item-key="%s">%s</a></div>', $item_class, $cart_item_key, $aero_item_key, $item_icon );
								}
								$html = ob_get_clean();
								echo "<div class='wfacp_cart_title_sec'>";
								echo "<span class='wfacp_mini_cart_item_title'>";
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
								echo apply_filters( 'woocommerce_checkout_cart_item_quantity', '&nbsp;<strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key );
								if ( apply_filters( 'wfacp_allow_woocommerce_after_cart_item_name_mini_cart', false, $cart_item, $cart_item_key ) ) {
									/**
									 * added in 2.0.0
									 */
									do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
								}
								echo wc_get_formatted_cart_item_data( $cart_item );
								echo '</span> ';
								echo "</div>";
								if ( false == $show_subscription_string_old_version && in_array( $_product->get_type(), WFACP_Common::get_subscription_product_type() ) ) {
									echo sprintf( "<div class='wfacp_product_subs_details'>%s</div>", WFACP_Common::subscription_product_string( $_product, $product_data, $cart_item, $cart_item_key ) );
								}
								if ( $show_quantity_switcher ) {
									$is_sold_individually = false;
									if ( $_product->is_sold_individually() ) {
										$is_sold_individually = true;
									}
									$hide_quantity_switcher = false;
									if ( apply_filters( 'wfacp_display_quantity_increment', true, $cart_item, $item_quantity, $aero_item_key, $cart_item_key ) ) {
										if ( false == $is_sold_individually ) {
											$item_quantity = apply_filters( 'wfacp_item_quantity', $item_quantity, $cart_item );
											$minMax        = apply_filters( 'wfacp_cart_item_min_max_quantity', [
												'min'  => 0,
												'max'  => '',
												'step' => '1'
											], $cart_item, $aero_item_key, $cart_item_key );
											?>
                                            <div class="product-quantity">
                                                <div class="wfacp_quantity_selector" style="<?php echo ( true == $hide_quantity_switcher ) ? 'display:none;pointer-events:none;' : ''; ?>">
                                                    <div class="value-button wfacp_decrease_item" onclick="decreaseItmQty(this,'<?php echo $aero_item_key ?>')" value="Decrease Value">-</div>
                                                    <input type="number" step='<?php echo $minMax['step'] ?>' min='<?php echo $minMax['min'] ?>' max='<?php echo $minMax['max'] ?>' value="<?php echo $item_quantity; ?>" data-value="<?php echo $item_quantity; ?>" class="wfacp_mini_cart_update_qty wfacp_product_quantity_number_field" cart_key="<?php echo $cart_item_key ?>">
                                                    <div class="value-button wfacp_increase_item" onclick="increaseItmQty(this,'<?php echo $aero_item_key ?>')" value="Increase Value">+</div>
                                                </div>
                                            </div>
											<?php
										} elseif ( $is_sold_individually ) {
											?>
                                            <div class="product-quantity" style="display: none"><span>1</span></div>
											<?php
										}
									} else {
										do_action( 'wfacp_display_quantity_increment_placeholder', true, $cart_item, $item_quantity, $aero_item_key, $cart_item_key );
									}
								}
								?>
                            </div>
                        </td>
                        <td class="product-total">
							<?php
							if ( in_array( $_product->get_type(), WFACP_Common::get_subscription_product_type() ) ) {
								if ( false == $show_subscription_string_old_version ) {
									echo wc_price( WFACP_Common::get_subscription_cart_item_price( $cart_item ) );
								} else {
									echo WFACP_Common::display_subscription_price( $_product, $cart_item, $cart_item_key );
								}
							} else {
								if ( true == apply_filters( 'wfacp_woocommerce_cart_item_subtotal_except_subscription', true, $_product, $cart_item, $cart_item_key ) ) {
									echo apply_filters( 'woocommerce_cart_item_subtotal', WFACP_Common::get_product_subtotal( $_product, $cart_item ), $cart_item, $cart_item_key );
								} else {
									do_action( 'wfacp_woocommerce_cart_item_subtotal_except_subscription_placeholder', $_product, $cart_item, $cart_item_key );
								}
							}
							echo '<span class="wfacp_cart_product_name_h">' . $html . '</span>';
							?>
                        </td>
                    </tr>
					<?php
				}
			}
		}
		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
        </tbody>
    </table>
	<?php
	do_action( 'wfacp_after_mini_cart_html' );
	remove_filter( 'wp_get_attachment_image_attributes', 'WFACP_Common::remove_src_set' );
	?>
</div>