<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * TrackShip for WooCommerce
 *
 * Shows tracking information in the HTML Shipment status email
 *
 * @package trackship-for-woocommerce/templates/email
 * @version 1.0
 */
if ( $tracking_items ) : 
	$track_button_Text = trackship_admin_customizer()->get_value( 'shipment_email_settings', 'track_button_Text' );
	$tracking_page_layout = trackship_admin_customizer()->get_value( 'shipment_email_settings', 'tracking_page_layout' );
	$text_align = is_rtl() ? 'right' : 'left';
	$border_color = trackship_for_woocommerce()->ts_actions->get_option_value_from_array( 'shipment_email_settings', 'border_color', '#e8e8e8');
	$link_color = trackship_for_woocommerce()->ts_actions->get_option_value_from_array( 'shipment_email_settings', 'link_color', '');
	$background_color = trackship_for_woocommerce()->ts_actions->get_option_value_from_array( 'shipment_email_settings', 'bg_color', '#fff');
	$font_color = trackship_for_woocommerce()->ts_actions->get_option_value_from_array( 'shipment_email_settings', 'font_color', '#333');
	$shipping_provider_logo = trackship_for_woocommerce()->ts_actions->get_option_value_from_array( 'shipment_email_settings', 'shipping_provider_logo', 1);
	$class = $ts4wc_preview ? 'hide' : '';
	?>
	<div class="tracking_info">
		<div class="tracking_list">
			<?php foreach ( $tracking_items as $key => $tracking_item ) { ?>
				<?php
				$ship_status = $new_status;
				$tracking_link = $tracking_item['tracking_page_link'] ?  $tracking_item['tracking_page_link'] : $tracking_item['formatted_tracking_link'];
				$show_trackship_branding = trackship_for_woocommerce()->ts_actions->get_option_value_from_array( 'shipment_email_settings', 'show_trackship_branding', 1 );
				$trackship_branding_class = $show_trackship_branding || in_array( get_option( 'user_plan' ), array( 'Free Trial', 'Free 50', 'No active plan' ) ) ? '' : 'hide';
				do_action( 'before_tracking_widget_email', $tracking_item, $order_id );
				?>
				<div class="tracking_index display-table">
					<div class="tracking_widget_email">
						<div style="display: table;width: 100%;">
							<div class="display-table-cell v-align-top" >
								<div class="shipment_status <?php echo esc_html( $ship_status ); ?>">
									<?php
									echo '<span class="' . esc_html( $ship_status ) . '">';
									esc_html_e( apply_filters( 'trackship_status_filter', $ship_status ) );
									echo '</span>';
									?>
								</div>
								<?php
								$show_est_delivery_date = apply_filters( 'show_est_delivery_date', true, $tracking_item['formatted_tracking_provider'] );
								$est_delivery_date = isset($shipment_row->est_delivery_date) ? $shipment_row->est_delivery_date : '';
								if ( $est_delivery_date && $show_est_delivery_date ) {
									echo '<p style="margin: 0;"><span class="est_delivery_date">';
									esc_html_e( 'Est. Delivery Date', 'trackship-for-woocommerce' );
									echo ': <b>';
									echo esc_html( date_i18n( 'l, M d', strtotime( $est_delivery_date ) ) );
									echo '</b>';
									echo '</span></p>';
								}
								?>
							</div>
						</div>
						<div style="display:block;"></div>
						<?php if ( !$ts4wc_preview ) { ?>
							<?php $icon_layout = 't_layout_1' == $tracking_page_layout ? '-widget.png' : '-widget-v4.png'; ?>
							<?php $icon_layout = 't_layout_3' == $tracking_page_layout ? '-widget-v2.png' : $icon_layout; ?>
							<div class="widget_progress_bar" style="width:100%;margin: 15px 0 10px;">
								<?php $widget_icon_url = trackship_for_woocommerce()->plugin_dir_url() . 'assets/images/widget-icon/' . esc_html( $ship_status ) . esc_html( $icon_layout ); ?>
								<img style="width:100%;" src="<?php echo esc_url( $widget_icon_url ); ?>">
							</div>
						<?php } elseif ( $ts4wc_preview ) { ?>
							<div class="widget_progress_bar" style="width:100%;margin: 15px 0 10px;">
								<?php $url = trackship_for_woocommerce()->plugin_dir_url() . 'assets/images/widget-icon/' . esc_html( $ship_status ); ?>
								<div><img class="t_layout_2 <?php echo 't_layout_2' != $tracking_page_layout ? 'hide' : ''; ?>" style="width:100%;" src="<?php echo esc_url( $url . '-widget-v4.png' ); ?>"></div>
								<div><img class="t_layout_3 <?php echo 't_layout_3' != $tracking_page_layout ? 'hide' : ''; ?>" style="width:100%;" src="<?php echo esc_url( $url . '-widget-v2.png' ); ?>"></div>
								<div><img class="t_layout_1 <?php echo 't_layout_1' != $tracking_page_layout ? 'hide' : ''; ?>" style="width:100%;" src="<?php echo esc_url( $url . '-widget.png' ); ?>"></div>
							</div>
						<?php } ?>
					</div>
					<div class="tracking_widget_email tracking_widget_bottom">
						<?php
						if ( $ship_status ) {
							if ( in_array( $ship_status, array( 'pending_trackship', 'pending', 'carrier_unsupported', 'unknown' ) ) ) {
								echo '<div class="shipment_status shipped" >';
								esc_html_e( 'Shipped', 'trackship-for-woocommerce' );
								echo '</div>';
							} else {
								if ( isset( $tracking_item['tracking_provider_image'] ) ) {
									?>
									<img class="ts4wc_provider_logo <?php echo !$shipping_provider_logo ? esc_attr($class) : ''; ?>" style="height:45px;width:45px;vertical-align:middle;margin-right: 10px;" src="<?php echo esc_url( $tracking_item['tracking_provider_image'] ); ?>">
								<?php } ?>
								<div class="tracking_info" style="margin:10px 0;">
									<?php echo esc_html( $tracking_item['formatted_tracking_provider'] ); ?>
									<?php if ( 'delivered' == $ship_status ) { ?>
										<?php echo esc_html( $tracking_item['tracking_number'] ); ?>
									<?php } else { ?>
										<a href="<?php echo esc_url( $tracking_link ); ?>" style="text-decoration:none"><?php echo esc_html( $tracking_item['tracking_number'] ); ?></a>
									<?php } ?>
								</div>
								<?php
							}
						}
						if ( 'delivered' != $ship_status ) {
							?>
							<div class="tracking_widget_track_button" style="float:right;"><a href="<?php echo esc_url( $tracking_link ); ?>" class="track_your_order"><?php esc_html_e( $track_button_Text ); ?></a></div>
							<div style="clear: both;display: block;"></div>
						<?php } ?>
					</div>
				</div>
				<div class="tracking_widget_email trackship_branding <?php echo esc_attr($trackship_branding_class); ?>">
					<p style="margin: 0;">
						<span style="vertical-align:middle;font-size: 14px;">Powered by <a href="https://trackship.com" title="TrackShip" target="blank">TrackShip</a></span>
					</p>
				</div>
			<?php } ?>
		</div>
	</div>
	<style>
	<?php if ( $link_color ) { ?>
		div.tracking_index.display-table .tracking_info a { color: <?php echo esc_html( $link_color ); ?>!important; }
	<?php } ?>
	<?php if ( !$ts4wc_preview  ) { ?>
		.ts4wc_provider_logo {
			display: <?php echo $shipping_provider_logo ? 'inline-block' : 'none'; ?>;
		}
		.tracking_widget_email.trackship_branding {
			display: <?php echo $show_trackship_branding || in_array( get_option( 'user_plan' ), array( 'Free Trial', 'Free 50', 'No active plan' ) ) ? 'block' : 'none'; ?>;
		}
	<?php } ?>
	#ts-email-widget-wrapper{max-width: 500px;margin: 50px auto;font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;font-size: 14px;line-height: 150%;}
	.tracker-progress-bar .progress {
		background-color: #f5f5f5;
		margin-top: 10px;
		border-radius: 5px;
		border: 1px solid #eee;
		overflow: hidden;
	}
	ul.tracking_list{padding: 0;list-style: none;}
	ul.tracking_list .tracking_list_li{margin-bottom: 5px;}
	ul.tracking_list .tracking_list_li .product_list_ul{padding-left: 10px;}
	ul.tracking_list .tracking_list_li .tracking_list_div{border-bottom:1px solid #e0e0e0;}
	.tracking_index {
		border: 1px solid #cccccc;
		margin: 20px 0;
		background: <?php echo esc_html( $background_color ); ?>;
		display:block;
		color: <?php echo esc_html( $font_color ); ?>;
		border-color: <?php echo esc_html( $border_color ); ?>;
	}
	.tracking_widget_bottom {
		border-top: 1px solid <?php echo esc_html( $border_color ); ?>;
	}
	.tracking_widget_bottom div {
		display:inline-block;
	}
	a.track_your_order {
		border-radius: <?php echo esc_html( trackship_admin_customizer()->get_value( 'shipment_email_settings', 'track_button_border_radius' ) ); ?>px;
		text-decoration: none;
		color: <?php echo esc_html( trackship_admin_customizer()->get_value( 'shipment_email_settings', 'track_button_text_color' ) ); ?>;
		background: <?php echo esc_html( trackship_admin_customizer()->get_value( 'shipment_email_settings', 'track_button_color' ) ); ?>;
		text-align: center;
		padding: 10px 15px;
		float: right;
		display:inline-block;
	}
	.tracking_widget_email {
		padding:15px;
	}
	.tracking_widget_email.trackship_branding {
		padding: 0;
		text-align: center;
		margin-bottom: 20px;
	}
	.shipment_status {font-size: 24px;margin: 10px 0;display: inline-block;color: #333;vertical-align: middle;font-weight:500;}
	.mb-0{margin:0;}
	.v-align-top{vertical-align:top;}
	@media screen and (max-width: 460px) {
		.tracking_widget_track_button {width:100%;}
		.tracking_widget_track_button a.track_your_order{width: calc(100% - 30px);}
	}
	@media screen and (min-width: 461px) {
		.display-table{display:table !important;width:100%;box-sizing: border-box;}
	}
</style>

<?php
endif;

/*
*
*/
do_action( 'after_tracking_widget_email', $order_id, $new_status );
