<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The template for displaying Tracking Form 
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/tracking/tracking-form.php
 * 
*/
$tracking_page_defaults = trackship_admin_customizer();
$link_color = get_trackship_settings( 'wc_ts_link_color', $tracking_page_defaults->defaults['wc_ts_link_color'] );

$bg_color = get_trackship_settings( 'wc_ts_bg_color', $tracking_page_defaults->defaults['wc_ts_bg_color'] );
$font_color = get_trackship_settings( 'wc_ts_font_color', $tracking_page_defaults->defaults['wc_ts_font_color'] );
$border_color = get_trackship_settings( 'wc_ts_border_color', $tracking_page_defaults->defaults['wc_ts_border_color'] );
$border_radius = get_trackship_settings('wc_ts_border_radius', $tracking_page_defaults->defaults['wc_ts_border_radius'] );
$show_trackship_branding = trackship_for_woocommerce()->ts_actions->get_option_value_from_array( 'shipment_email_settings', 'show_trackship_branding', 1 );
$form_button_Text = $tracking_page_defaults->get_value( 'tracking_form_settings', 'form_button_Text' );
$form_button_color = $tracking_page_defaults->get_value( 'tracking_form_settings', 'form_button_color' );
$form_button_text_color = $tracking_page_defaults->get_value( 'tracking_form_settings', 'form_button_text_color' );
$form_button_border_radius = $tracking_page_defaults->get_value( 'tracking_form_settings', 'form_button_border_radius' );
$form_tab_view = $tracking_page_defaults->get_value( 'tracking_form_settings', 'form_tab_view' );
?>
<style>
<?php if ( $bg_color ) { ?>
	.order_track_form {
		background: <?php echo esc_html( $bg_color ); ?>;
	}
<?php }	?>
<?php if ( $border_radius ) { ?>
	form.order_track_form {
		border-radius: <?php echo esc_html( $border_radius ); ?>px;
	}
<?php } ?>
<?php if ( $font_color ) { ?>
	body .search_order_form, body form.order_track_form label {
		color: <?php echo esc_html( $font_color ); ?>;
	}
<?php } ?>
<?php if ( $form_button_color ) { ?>
	.order_track_form div.search_order_form button {
		background: <?php echo esc_html( $form_button_color ); ?>;
	}
<?php } ?>
<?php if ( $border_color ) { ?>
	.order_track_form, .tracking_form_tabs, .trackship_branding {
		border-color: <?php echo esc_html( $border_color ); ?> !important;
	}
<?php } ?>
<?php if ( $form_button_text_color ) { ?>
	.order_track_form div.search_order_form button {
		color: <?php echo esc_html( $form_button_text_color ); ?>;
	}
<?php } ?>
<?php if ( $form_button_border_radius ) { ?>
	.order_track_form div.search_order_form button {
		border-radius: <?php echo esc_html( $form_button_border_radius ); ?>px;
	}
<?php } ?>
<?php if ( !$show_trackship_branding ) { ?>
	.trackship_branding {display:none;}
<?php } ?>
.order_track_form input.ts_from_input:checked + label {
	color: <?php echo esc_html( $link_color ); ?> !important;
	border-bottom: 3px solid <?php echo esc_html( $link_color ); ?>;
	margin-bottom: -2px;
}
.order_track_form {
	max-width: 800px;
	margin: 0 auto 20px;
	border: 1px solid #e0e0e0;
	min-height: 330px;
}
.track_fail_msg {
	color: red;
	padding: 0 20px 15px;
}
</style>
<div class="track-order-section">
	<form method="post" class="order_track_form">
		<div class="search_order_form">
			<?php if ( 'tracking_details' == $form_tab_view ) { ?>
				<style>
					.tracking_form_tabs, .order_id_email {display:none;}
					.search_order_form .by_tracking_number.tracking_form { display:block; }
					form.order_track_form {min-height:auto;}
					</style>
			<?php } elseif ( 'order_details' == $form_tab_view ) { ?>
				<style>
					.tracking_form_tabs, .by_tracking_number {display:none;}
					.search_order_form .order_id_email.tracking_form { display:block; }
					form.order_track_form {min-height:auto;}
					</style>
			<?php } else { ?>
				<style>
					.search_order_form .order_id_email.tracking_form { display:block; }
					.by_tracking_number {display:none;}
					form.order_track_form {min-height:auto;}
					</style>
			<?php } ?>
			<div class="tracking_form_tabs">
				<input id="for_order_number" type="radio" name="ts_tracking_form" class="ts_from_input" data-name="order_id_email" checked>
				<label for="for_order_number" class="ts_from_label for_order_number"><?php esc_html_e( 'Order Details', 'trackship-for-woocommerce' ); ?></label>
				<input id="for_tracking_number" type="radio" name="ts_tracking_form" class="ts_from_input" data-name="by_tracking_number">
				<label for="for_tracking_number" class="ts_from_label for_tracking_number"><?php esc_html_e( 'Tracking Number', 'trackship-for-woocommerce' ); ?></label>
			</div>
			<div class="order_id_email tracking_form">
				<p><?php echo esc_html( apply_filters( 'ast_tracking_page_front_text', __( 'To track your order, enter your order number and email address:', 'trackship-for-woocommerce' ) ) ); ?></p>
				<p class="form-row"><label for="order_id"><?php echo esc_html( apply_filters( 'ast_tracking_page_front_order_label', __( 'Order ID', 'trackship-for-woocommerce' ) ) ); ?></label> <input class="input-text" type="text" name="order_id" id="order_id" value="" placeholder="<?php esc_html_e( 'Order Number', 'trackship-for-woocommerce' ); ?>"></p>
				<p class="form-row"><label for="order_email"><?php echo esc_html( apply_filters( 'ast_tracking_page_front_order_email_label', __( 'Order Email', 'trackship-for-woocommerce' ) ) ); ?></label> <input class="input-text" type="text" name="order_email" id="order_email" value="" placeholder="<?php esc_html_e( 'Email address', 'trackship-for-woocommerce' ); ?>"></p>
				<p class="form-row" style="margin-bottom:0;"><button type="submit" class="button btn btn-secondary" name="track" value="Track"><?php echo esc_html( $form_button_Text ); ?></button></p>
			</div>
			<div class="by_tracking_number tracking_form">
				<p><?php echo esc_html( apply_filters( 'ast_tracking_page_traking_number_front_text', __( 'To track your order, please enter the tracking number you received in the shipping confirmation email', 'trackship-for-woocommerce' ) ) ); ?></p>
				<p class="form-row"><label for="order_tracking_number"><?php echo esc_html( apply_filters( 'tracking_page_tracking_number_label', __( 'Tracking Number', 'trackship-for-woocommerce' ) ) ); ?></label><input class="input-text" type="text" name="order_tracking_number" id="order_tracking_number" value="" placeholder="<?php esc_html_e( 'Order tracking number.', 'trackship-for-woocommerce' ); ?>"></p>
				<p class="form-row" style="margin-bottom:0;"><button type="submit" class="button btn btn-secondary" name="track" value="Track"><?php echo esc_html( $form_button_Text ); ?></button></p>
			</div>
			<div class="track_fail_msg" style="display:none;"></div>
			<div class="trackship_branding">
				<p><span><?php esc_html_e( 'Powered by ', 'trackship-for-woocommerce' ); ?></span><img src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/images/trackship-logo.png"></p>
			</div>
			<?php if ( in_array( get_option( 'user_plan' ), array( 'Free Trial', 'Free 50', 'No active plan' ) ) ) { ?>
				<style> .trackship_branding{display:block !important;} </style>
			<?php } ?>
		</div>
		<div class="clear"></div>
		<input type="hidden" name="action" value="get_tracking_info">
		<input type="hidden" name="fronted" value="yes">
		<?php wp_nonce_field( 'tracking_form' ); ?>
	</form>
</div>
