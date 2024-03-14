<style>	
	html, body {
		background-color: #f7f7f7 !important;
		margin-top: 0px !important;
	}
	.col.tracking-detail {
		margin: 30px auto 100px;
	}
	.est_delivery_date {
		margin: 0 15px;
	}
	.customize-partial-edit-shortcut-button {display: none;}
	<?php if ( $link_color ) { ?>
		.col.tracking-detail .tracking_number_wrap a, .tracking_event_tab_view .view_more_class {
			color: <?php echo esc_html( $link_color ); ?>;
		}
		.heading_panel span.accordian-arrow.down {
			border-color: <?php echo esc_html( $link_color ); ?>;
		}
	<?php } ?>
	<?php if ( $border_radius ) { ?>
		.col.tracking-detail {
			border-radius: <?php echo esc_html( $border_radius ); ?>px;
		}
	<?php } ?>
	<?php if ( $border_color ) { ?>
		.col.tracking-detail{
			border: 1px solid <?php echo esc_html( $border_color ); ?>;
		}
		body .col.tracking-detail .trackship_branding, .tracking-detail .heading_panel {
			border-top: 1px solid <?php echo esc_html( $border_color ); ?>;
		}
		body .col.tracking-detail .shipment-header, body .shipment_progress_heading_div, body .tracking-detail .h4-heading, .tracking-detail .tracking_number_wrap {
			border-bottom: 1px solid <?php echo esc_html( $border_color ); ?>;
		}
	<?php }	?>
	<?php if ( $background_color ) { ?>
		body .col.tracking-detail {
			background: <?php echo esc_html( $background_color ); ?>;
		}
	<?php } ?>
	<?php if ( $font_color ) { ?>
		body .tracking-detail .shipment-content, body .tracking-detail .shipment-content h4, .shipment_status_heading {
			color: <?php echo esc_html( $font_color ); ?>;
		}
		.heading_panel span.accordian-arrow {
			border-color: <?php echo esc_html( $font_color ); ?>;
		}
	<?php } ?>
</style>

<div class="tracking-detail col ">
	<div class="shipment-content">
		<div class="tracking-header">
			<div class="tracking_number_wrap">
				
				<div class="provider_image_div" style="">
					<img class="provider_image" src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/images/4px.png?v=3.1.1">
				</div>
				<div class="tracking_number_div">
					<ul>
						<li>
						<span>4PX</span> 
						<a href="http://track.4px.com/query/304188629639" target="blank"><strong>304188629639</strong></a>
						<strong>304188629639</strong>
						</li>
						<li class="last_mile_tracking_number">
							<span>Delivery tracking Number </span> 
							<strong style="display:inline-block;">5333452683184862313</strong>
						</li>
					</ul>
				</div>
				<span class="wc_order_id">
					<a href="#" target="_blank">
						<?php /* translators: %s: search 14696 */ ?>
						<?php printf( esc_html( '#%d' ), 14696 ); ?>
					</a>
				</span>
			</div>
			
			<div class="shipment_status_heading <?php echo esc_html($status); ?>"><?php esc_html_e( apply_filters( 'trackship_status_filter', $status ) ); ?></div>
			<div class="shipping_from_to">
				<span class="shipping_from">India</span>
				<img class="shipping_to_img" src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/images/arrow.png">
				<span class="shipping_to">United states</span>
			</div>
			<span class="est_delivery_date">Est. Delivery Date: <strong>Thursday, Oct 01</strong></span>	
		</div>
		<?php
		if ( in_array( $tracking_page_layout, array( 't_layout_1', 't_layout_3' ) ) ) {
			$width = '0';
		} else {
			if ( in_array( $status, array( 'in_transit', 'on_hold', 'failure' ) ) ) {
				$width = '30%';
			} elseif ( in_array( $status, array( 'out_for_delivery', 'available_for_pickup', 'return_to_sender', 'exception' ) ) ) {
				$width = '60%';			
			} elseif ( 'delivered' == $status ) {
				$width = '100%';				
			} else {
				$width = '0';
			}
		}
		?>
		<div class="tracker-progress-bar <?php echo in_array( $tracking_page_layout, array( 't_layout_1', 't_layout_3' ) ) ? 'tracking_icon_layout ' : 'tracking_progress_layout'; ?> <?php echo esc_html( $tracking_page_layout ); ?>">
			<div class="progress <?php echo esc_html($status); ?>">
				<div class="progress-bar <?php echo esc_html($status); ?>" style="width: <?php esc_html_e( $width ); ?>;"></div>
				<div class="progress-icon icon1"></div>
				<div class="progress-icon icon2"></div>
				<div class="progress-icon icon3"></div>
				<div class="progress-icon icon4"></div>
			</div>
		</div>
		<div class="tracking_event_tab_view">
			<div data-label="tracking_events_details" class="heading_panel tracking_detail_label <?php echo 1 != $hide_tracking_events ? 'checked' : 'hide'; ?>" style="<?php echo 1 == $hide_tracking_events ? 'display:none' : ''; ?>">
				Shipment Progress
				<span class="accordian-arrow ts-right"></span>
			</div>
			<div class="content_panel tracking-details tracking_events_details">
				<div class="preview_tracking_events <?php echo 0 != $hide_tracking_events ? 'hide' : ''; ?> tracking_events_0">
					<div class="tracking_details_by_date">			
						<ul class="timeline">
							<li>
								<strong>October 1, 2020 07:59</strong>
								<p>Out for Delivery, Expected Delivery by 8:00pm - EAST HARTFORD, CT - <span>EAST HARTFORD</span></p>
							</li>
							<li>
								<strong>October 1, 2020 07:48</strong>
								<p>Arrived at Post Office - HARTFORD, CT - <span>HARTFORD</span></p>
							</li> 
							<li>
								<strong>October 1, 2020 00:10</strong>
								<p>Arrived at USPS Regional Destination Facility - SPRINGFIELD MA NETWORK DISTRIBUTION CENTER,  - <span>SPRINGFIELD MA NETWORK DISTRIBUTION CENTER</span></p>
							</li>
							<li>
								<strong>September 30, 2020 00:00</strong>
								<p>In Transit to Next Facility<span></span></p>
							</li>   
							<li>
								<strong>September 29, 2020 13:12</strong>
								<p>USPS in possession of item - SHELDON, WI - <span>SHELDON</span></p>
							</li>
						</ul>							
					</div>
				</div>
				<div class="preview_tracking_events <?php echo 2 != $hide_tracking_events ? 'hide' : ''; ?> tracking_events_2">
					<div class="tracking_details_by_date">
						<ul class="timeline new-details">
							<li>
								<strong>October 1, 2020 07:59</strong>
								<p>Out for Delivery, Expected Delivery by 8:00pm - EAST HARTFORD, CT - <span>EAST HARTFORD</span></p>
							</li>
							<li>
								<strong>October 1, 2020 07:48</strong>
								<p>Arrived at Post Office - HARTFORD, CT - <span>HARTFORD</span></p>
							</li> 				
						</ul>			
						<ul class="timeline old-details" style="display:none;">
							<li>
								<strong>October 1, 2020 00:10</strong>
								<p>Arrived at USPS Regional Destination Facility - SPRINGFIELD MA NETWORK DISTRIBUTION CENTER,  - <span>SPRINGFIELD MA NETWORK DISTRIBUTION CENTER</span></p>
							</li>
							<li>
								<strong>September 30, 2020 00:00</strong>
								<p>In Transit to Next Facility<span></span></p>
							</li>   
							<li>
								<strong>September 29, 2020 13:12</strong>
								<p>USPS in possession of item - SHELDON, WI - <span>SHELDON</span></p>
							</li>
						</ul>							
					</div>
					<a class="view_old_details view_more_class" href="javaScript:void(0);" style="display: inline;"><?php esc_html_e( 'view more', 'trackship-for-woocommerce' ); ?></a>
					<a class="hide_old_details view_more_class" href="javaScript:void(0);" style="display: none;"><?php esc_html_e( 'view less', 'trackship-for-woocommerce' ); ?></a>
				</div>
			</div>
			<div data-label="product_details" class="heading_panel">
				Items in this shipment
				<span class="accordian-arrow ts-right"></span>
			</div>
			<div class="content_panel product_details">
				<ul class="tpi_product_tracking_ul">
					<li>
						<img width="50" height="50" src="<?php echo esc_html( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/images/dummy-product-image.jpg" loading="lazy">	
						<span>
							<a target="_blank" href="#">A Study in Scarlet</a>
							 x 1
						</span>
					</li>
					<li>
						<img width="50" height="50" src="<?php echo esc_html( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/images/dummy-product-image.jpg" loading="lazy">	
						<span>
							<a target="_blank" href="#">A Study in Scarlet</a>
							 x 1
						</span>
					</li>
				</ul>
			</div>
			<div data-label="shipment_status_notifications" class="heading_panel">
				Notifications
				<span class="accordian-arrow ts-right"></span>
			</div>
			<div class="content_panel shipment_status_notifications">
				<label>
					<input type="checkbox" class="unsubscribe_emails_checkbox" name="unsubscribe_emails" value="1" checked>
					<span style="font-weight: normal;"><?php esc_html_e( 'Email Notifications', 'trackship-for-woocommerce' ); ?></span>
				</label>
				<label>
					<input type="checkbox" class="unsubscribe_sms_checkbox" name="unsubscribe_sms" data-lable="sms" value="1" checked="">
					<span style="font-weight: normal;">SMS notifications</span>
				</label>
			</div>
		</div>
	</div>
	<div class="trackship_branding" >
		<p>
			<span><?php esc_html_e( 'Powered by ', 'trackship-for-woocommerce' ); ?></span>
			<a href="https://trackship.com" title="TrackShip" target="blank"><img src="<?php echo esc_html( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/images/trackship-logo.png"></a>
		</p>
	</div>
</div>
<style>
<?php if ( !in_array( $tracking_page_layout, array( 't_layout_1', 't_layout_3' ) ) ) { ?>
	.progress-icon{display:none;}
<?php } ?>
<?php if ( $hide_from_to ) { ?>
	.shipping_from_to{display:none;}
<?php } ?>
<?php if ( $hide_last_mile ) { ?>
	.last_mile_tracking_number{display:none;}
<?php } ?>	
<?php if ( !$show_trackship_branding ) { ?>
	.trackship_branding{display:none;}
<?php } ?>
<?php if (in_array( get_option( 'user_plan' ), array( 'Free Trial', 'Free 50', 'No active plan' ) ) ) { ?>
	.trackship_branding{display:block !important;}
<?php } ?>
<?php if ( $hide_tracking_provider_image ) { ?>
	.provider_image_div{display:none;}
<?php } ?>
<?php if ( $ts_link_to_carrier ) { ?>
.tracking_number_div ul li > strong{display:none;}
<?php } else { ?>
.tracking_number_div ul li > a{display:none;}
<?php } ?>
</style>
