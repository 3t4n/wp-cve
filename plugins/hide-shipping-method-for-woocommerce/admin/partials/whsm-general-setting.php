<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
wp_nonce_field( 'whsm_save_general_setting_action', 'whsm_save_general_setting' );
$hide_shipping_option = get_option( 'hide_shipping_option' );
$whsm_hide_shipping_cart = get_option( 'whsm_hide_shipping_cart' );
$hide_shipping_method_list = get_option( 'hide_shipping_method_list' );
$whsm_hide_shipping_cart_checked = ( !empty($whsm_hide_shipping_cart) && 'on' === $whsm_hide_shipping_cart ? 'checked' : '' );
$whsm_admin_object = new Woo_Hide_Shipping_Methods_Admin( '', '' );
$allowed_tooltip_html = wp_kses_allowed_html( 'post' )['span'];
require_once plugin_dir_path( __FILE__ ) . 'header/plugin-header.php';
?>
<div class="whsm-section-left">
	<h2><?php 
esc_html_e( 'General Settings', 'woo-hide-shipping-methods' );
?></h2>
	<table class="table-mastersettings whsm-general-settings-tbl table-outer form-table whsm-main-table res-cl" cellpadding="0" cellspacing="0">
		<tbody>
		<tr valign="top" id="combine_default_shipping_with_forceall_td">
			<th scope="row">
				<label for="table-whattodo">
					<?php 
esc_html_e( 'When "Free Shipping" is available during Checkout', 'woo-hide-shipping-methods' );
?>
				</label>
			</th>
			<td>
				<label>
					<input type="radio" name="hide_shipping_option" id="hide_shipping"
					       value="free_shipping_available" <?php 
checked( $hide_shipping_option, 'free_shipping_available' );
?>/>
					<span
						class="date-time-text format-i18n"><?php 
esc_html_e( 'Hide all other shipping method and when "Free Shipping" available on the cart page', 'woo-hide-shipping-methods' );
?></span>
				</label>
				<br>
				<label>
					<input type="radio" name="hide_shipping_option" id="hide_shipping"
					       value="free_local_available" <?php 
checked( $hide_shipping_option, 'free_local_available' );
?>/>
					<span
						class="date-time-text format-i18n"><?php 
esc_html_e( 'Hide all other shipping method and when "Free Shipping" or "Local Pickup" available on the cart page', 'woo-hide-shipping-methods' );
?></span>
				</label>
				<br>
				<?php 
?>
						<label class="whsm-pro-feature">
							<input type="radio" name="hide_shipping_option" id="hide_shipping"
							       value="other_shipping_hide" disabled="disabled"/>
							<span
								class="date-time-text format-i18n"><?php 
esc_html_e( 'Hide specific shipping method when "Free Shipping" available on the cart page', 'woo-hide-shipping-methods' );
?><span class="whsm-pro-label"></span></span>
						</label>
						<br>
						<?php 
?>
				<label>
					<input type="radio" name="hide_shipping_option" id="hide_shipping"
					       value="advance_hide_shipping" <?php 
checked( $hide_shipping_option, 'advance_hide_shipping' );
?>/>
					<span
						class="date-time-text format-i18n"><?php 
esc_html_e( 'Conditional Hide shipping method Rules', 'woo-hide-shipping-methods' );
?></span>
					<?php 
$html = sprintf( '%s<br>%s', esc_html__( 'With this option, you can create conditional hide shipping method rules based on your business needs.', 'woo-hide-shipping-methods' ), esc_html__( 'After saving the settings, a new menu will appear called "Manage Rules".', 'woo-hide-shipping-methods' ) );
?>
					<?php 
echo  wp_kses( wc_help_tip( $html ), array(
    'span' => $allowed_tooltip_html,
) ) ;
?>
				</label>
				
			</td>
		</tr>
		<tr valign="top" id="hide_shipping_cart">
			<th scope="row">
				<label for="whsm_hide_shipping_cart">
					<?php 
esc_html_e( 'Hide all shipping methods?', 'woo-hide-shipping-methods' );
?>
				</label>
			</th>
			<td>
				<input type="checkbox" name="whsm_hide_shipping_cart" id="whsm_hide_shipping_cart"
				       value="off" <?php 
echo  esc_attr( $whsm_hide_shipping_cart_checked ) ;
?>>
				<p class="description" style="display: none;">
					<?php 
$html = esc_html__( 'Enabling this option will hide all shipping methods from the cart page.', 'woo-hide-shipping-methods' );
?>
				</p>
				<?php 
echo  wp_kses( wc_help_tip( $html ), array(
    'span' => $allowed_tooltip_html,
) ) ;
?>
			</td>
		</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" class="button button-primary" name="save_general_setting"
		       value="<?php 
esc_attr_e( 'Save Settings', 'woo-hide-shipping-methods' );
?>">
	</p>
</div>
</div>
</div>
</div>
</div>
