<style>	
	html, body {
		background-color: #f7f7f7 !important;
		margin-top: 0px !important;
	}
	.col.enhanced_tracking_detail {
		margin: 30px auto;
	}
	.customize-partial-edit-shortcut-button {display: none;}
	<?php if ( $link_color ) { ?>
		div.col.enhanced_tracking_detail a {
			color: <?php echo esc_html( $link_color ); ?>;
		}
		span.accordian-arrow.down {
			border-color: <?php echo esc_html( $link_color ); ?> !important;
		}
	<?php } ?>
	<?php if ( $border_radius ) { ?>
		.col.enhanced_tracking_detail {
			border-radius: <?php echo esc_html( $border_radius ); ?>px;
		}
	<?php } ?>
	<?php if ( $border_color ) { ?>
		.col.enhanced_tracking_detail, div.est_delivery_section, div.tracking_widget_tracking_events_section, .enhanced_tracking_detail .enhanced_heading, .enhanced_tracking_detail .enhanced_content, div.last_mile_tracking_number, .enhanced_content .shipping_from_to , .enhanced_content ul.tpi_product_tracking_ul li {
			border-color: <?php echo esc_html( $border_color ); ?>;
		}
	<?php }	?>
	<?php if ( $background_color ) { ?>
		.col.enhanced_tracking_detail {
			background: <?php echo esc_html( $background_color ); ?>;
		}
	<?php } ?>
	<?php if ( $font_color ) { ?>
		body .col.enhanced_tracking_detail, body .enhanced_content label {
			color: <?php echo esc_html( $font_color ); ?>;
		}				
		span.accordian-arrow.ts-right {
			border-color: <?php echo esc_html( $font_color ); ?>;
		}
	<?php } ?>
	<?php if ( !$show_trackship_branding ) { ?>
		.enhanced_trackship_branding{display:none;}
	<?php } ?>
	<?php if ( in_array( get_option( 'user_plan' ), array( 'Free Trial', 'Free 50', 'No active plan' ) ) && 'classic' == $tracking_page_type ) { ?>
		.enhanced_trackship_branding{display:none;}
	<?php } elseif ( in_array( get_option( 'user_plan' ), array( 'Free Trial', 'Free 50', 'No active plan' ) ) ) { ?>
		.enhanced_trackship_branding{display:block !important;}
	<?php } ?>
	
</style>
<div class="preview_enhanced_tracking_widget">
	<div class="enhanced_tracking_detail col shipment_1">
		<div class="tracking_number_wrap">
			<div style="display: flex;">
				<div class="provider_image_div">
					<img decoding="async" class="provider_image" src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/images/4px.png">
				</div>
				<div class="tracking_number_div">
					<ul>
						<li>
							<span class="tracking_page_provider_name">4PX</span>
						</li>
						<li>
							<a href="https://tools.usps.com/go/TrackConfirmAction_input?qtc_tLabels1=4206621492748927005455000534499593" target="blank"><strong>304188629639</strong></a>
							<strong>304188629639</strong>
						</li>
					</ul>
				</div>
				<span class="accordian-arrow down"></span>
			</div>
		</div>
		<div class="enhanced_tracking_content">
			<div class="est_delivery_section">
				<span class="est-delivery-date <?php echo esc_html($status); ?>">
					<?php 'delivered' != $status ? esc_html_e( 'Est. Delivery Date', 'trackship-for-woocommerce' ) : esc_html_e( 'Delivered on', 'trackship-for-woocommerce' ); ?> :
					<strong>Friday, Oct 02</strong>
				</span>
				<span class="tracking_details_switch">
					<input id="enhanced_overview_1" data-type="overview" data-number="shipment_1" type="radio" name="enhanced_switch_1" class="enhanced_switch_input" checked="">
					<label for="enhanced_overview_1" class="enhanced_switch">Overview</label>

					<input id="enhanced_journey_1" data-type="journey" data-number="shipment_1" type="radio" name="enhanced_switch_1" class="enhanced_switch_input">
					<label for="enhanced_journey_1" class="enhanced_switch">Journey</label>
				</span>
			</div>
			<div class="tracking_widget_tracking_events_section">
				<div class="enhanced_overview enhanced_tracking_details enhanced_overview_1">
					<div class="heading_shipment_status <?php echo esc_html($status); ?>">
						<?php esc_html_e( apply_filters( 'trackship_status_filter', $status ) ); ?>
					</div>
					<div class="tracking_detail shipped <?php echo esc_html($status); ?>">
						<strong>October 1, 2020</strong>
						<div>Out for Delivery, Expected Delivery by 8:00pm - EAST HARTFORD, CT - EAST HARTFORD</div>
					</div>
				</div>
				<div class="enhanced_journey enhanced_tracking_details enhanced_journey_1" style="display:none;">
					<div class="tracking_detail">
						<strong>October 1, 2020</strong>
						<div>Arrived at Post Office - HARTFORD, CT - HARTFORD</div>
					</div>
					<div class="tracking_detail">
						<strong>October 1, 2020</strong>
						<div>Arrived at USPS Regional Destination Facility - SPRINGFIELD MA NETWORK DISTRIBUTION CENTER, - SPRINGFIELD MA NETWORK DISTRIBUTION CENTER</div>
					</div>
					<div class="tracking_detail">
						<strong>September 30, 2020</strong>
						<div>In Transit to Next Facility</div>
					</div>
					<div class="tracking_detail">
						<strong>September 29, 2020</strong>
						<div>USPS in possession of item - SHELDON, WI - SHELDON</div>
					</div>
				</div>
			</div>
			<div class="enhanced_shipment_details_section">
				<div data-label="enhanced_details" class="enhanced_heading">
					<span>Details</span>
					<span class="accordian-arrow ts-right"></span>
				</div>
				<div class="enhanced_content enhanced_details" style="display: none;">
					<div class="last_mile_tracking_number">
						<span>Delivery tracking Number </span> 
						<strong>5333452683184862313</strong>
					</div>
					<div class="shipping_from_to">
						<span class="shipping_from">India</span>
						<img class="shipping_to_img" src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/images/arrow.png">
						<span class="shipping_to">United states</span>
					</div>
					<div class="wc_order_id">
						<?php esc_html_e( 'View your order details', 'trackship-for-woocommerce' ); ?> 
						<a href="#" target="_blank">#2213</a>
					</div>
					<ul class="tpi_product_tracking_ul">
						<li>
							<img width="50" height="50" src="<?php echo esc_html( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/images/dummy-product-image.jpg" loading="lazy">	
							<span>
								<a target="_blank" href="#">A Study in Scarlet</a>
								x 1
							</span>
						</li>
					</ul>
				</div>
			</div>
			<div class="enhanced_notifications_section">
				<div data-label="enhanced_notifications" class="enhanced_heading">
					<span>Notifications</span>
					<span class="accordian-arrow ts-right"></span>
				</div>
				<div class="enhanced_content enhanced_notifications" style="display: none;">
					<label>
						<input type="checkbox" class="unsubscribe_emails_checkbox" name="unsubscribe_emails" data-lable="email" value="1" checked="">
						<span style="font-weight: normal;">Email notifications</span>
					</label>
					<label>
						<input type="checkbox" class="unsubscribe_sms_checkbox" name="unsubscribe_sms" data-lable="sms" value="1" checked="">
						<span style="font-weight: normal;">SMS notifications</span>
					</label>
				</div>
			</div>
		</div>
	</div>
	<div class="enhanced_trackship_branding">
		<p><span><?php esc_html_e( 'Powered by ', 'trackship-for-woocommerce' ); ?></span><a href="https://trackship.com/" title="TrackShip" target="blank"><img src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/images/trackship-logo.png"></a></p>
	</div>
</div>

