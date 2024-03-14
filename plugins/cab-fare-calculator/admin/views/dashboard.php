<?php wp_enqueue_script( 'backend-common', TBLIGHT_PLUGIN_DIR_URL . 'admin/js/backend-common.js', array(), filemtime( TBLIGHT_PATH . '/admin/js/backend-common.js' ), true ); ?>
<legend class="block-heading"><?php echo esc_html( get_admin_page_title() ); ?></legend>
<div class="landing-container">
	<div class="premium-cta clearfix">
		<p>Upgrade to <span class="highlight">premium version</span> of <span class="highlight">Taxibooking WordPress</span> to benefit from all features!</p>
		<a href="https://kanev.com/products/taxi-booking-for-wordpress" target="_blank" class="premium-cta-button button btn">
			<span class="highlight">UPGRADE</span>
			<span>to the premium version</span>
		</a>
	</div>
</div>
<div class="tblight-wrap">
	<div id="cpanel" class="clearfix">

		<div class="booking-option-button" style="text-align:center;margin:0px;">
			<div class="icon">
				<a href="<?php echo admin_url( 'admin.php?page=orders' ); ?>">
					<lord-icon animation="hover" src="<?php echo TBLIGHT_PLUGIN_DIR_URL; ?>admin/images/icons/orders.json" alt="Orders"></lord-icon>
					<span>Orders</span>
				</a>
			</div>
		</div>

		<div class="booking-option-button" style="text-align:center;margin:0px;">
			<div class="icon">
				<a href="<?php echo admin_url( 'admin.php?page=cars' ); ?>">
					<lord-icon animation="hover" src="<?php echo TBLIGHT_PLUGIN_DIR_URL; ?>admin/images/icons/vehicles.json" alt="Vehicles"></lord-icon>
					<span>Vehicles</span>
				</a>
			</div>
		</div>		

		<div class="booking-option-button" style="text-align:center;margin:0px;">
			<div class="icon">
				<a href="<?php echo admin_url( 'admin.php?page=paymentmethods' ); ?>">
					<lord-icon animation="hover" src="<?php echo TBLIGHT_PLUGIN_DIR_URL; ?>admin/images/icons/payment-methods.json" alt="Payment methods"></lord-icon>
					<span>Payment methods</span>
				</a>
			</div>
		</div>		

		<div class="booking-option-button" style="text-align:center;margin:0px;">
			<div class="icon">
				<a href="<?php echo admin_url( 'admin.php?page=configs' ); ?>">
					<lord-icon animation="hover" src="<?php echo TBLIGHT_PLUGIN_DIR_URL; ?>admin/images/icons/configs.json" alt="Configurations"></lord-icon>
					<span>Settings</span>
				</a>
			</div>
		</div>

		<div class="booking-option-button in-active" style="text-align:center;margin:0px;">
			<div class="icon">
				<a href="https://kanev.com/products/taxi-booking-for-wordpress" target="_blank" title="This feature is only available in the Pro version. To upgrade click on this.">
					<lord-icon animation="hover" src="https://cdn.lordicon.com/burmxlrn.json"></lord-icon>
					<span>POI Categories</span>
					<span class="pro-label">PRO</span>
				</a>
			</div>
		</div>

		<div class="booking-option-button in-active" style="text-align:center;margin:0px;">
			<div class="icon">
				<a href="https://kanev.com/products/taxi-booking-for-wordpress" target="_blank" title="This feature is only available in the Pro version. To upgrade click on this.">
					<lord-icon animation="hover" src="https://cdn.lordicon.com/zzcjjxew.json"></lord-icon>
					<span>Points of Interest</span>
					<span class="pro-label">PRO</span>
				</a>
			</div>
		</div>

		<div class="booking-option-button in-active" style="text-align:center;margin:0px;">
			<div class="icon">
				<a href="https://kanev.com/products/taxi-booking-for-wordpress" target="_blank" title="This feature is only available in the Pro version. To upgrade click on this.">
					<lord-icon animation="hover" src="https://cdn.lordicon.com/xtkehzkm.json"></lord-icon>
					<span>Fixed fare routes</span>
					<span class="pro-label">PRO</span>
				</a>
			</div>
		</div>		

		<div class="booking-option-button in-active" style="text-align:center;margin:0px;">
			<div class="icon">
				<a href="https://kanev.com/products/taxi-booking-for-wordpress" target="_blank" title="This feature is only available in the Pro version. To upgrade click on this.">
					<lord-icon animation="hover" src="<?php echo TBLIGHT_PLUGIN_DIR_URL; ?>admin/images/icons/custom-fields.json" alt="Custom Fields"></lord-icon>
					<span>Custom Fields</span>
					<span class="pro-label">PRO</span>
				</a>
			</div>
		</div>

		<div class="booking-option-button in-active" style="text-align:center;margin:0px;">
			<div class="icon">
				<a href="https://kanev.com/products/taxi-booking-for-wordpress" target="_blank" title="This feature is only available in the Pro version. To upgrade click on this.">
					<lord-icon animation="hover" src="<?php echo TBLIGHT_PLUGIN_DIR_URL; ?>admin/images/icons/translations.json" alt="Translations"></lord-icon>
					<span>Translations</span>
					<span class="pro-label">PRO</span>
				</a>
			</div>
		</div>		

		<div class="booking-option-button in-active" style="text-align:center;margin:0px;">
			<div class="icon">
				<a href="https://drivenot.com/" title="This feature is only available in the Pro version. To upgrade click on this.">
					<lord-icon animation="hover" src="https://cdn.lordicon.com/zfvxsplq.json" alt="Translations"></lord-icon>
					<span>Drivenot Network</span>
					<span class="pro-label">PRO</span>
				</a>
			</div>
		</div>

		<div class="booking-option-button" style="text-align:center;margin:0px;">
			<div class="icon">
				<a href="https://kanev.com/login?redirecttype=wpnewticket" target="_blank">
					<script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
					<lord-icon
						src="https://cdn.lordicon.com/pciennjk.json"
						animation="hover">
					</lord-icon>
					<span>Support Ticket</span>
				</a>
			</div>
		</div>		

	</div>
	<div class="tblight-dashboard-message">
		<div class="message-left-block">
			<h3>ShortCodes</h3>
			<p>Please copy and paste this short code <span>[taxibooking-form]</span> into a page, post or widget area of your website to show the booking form.</p>
		</div>
		<div class="message-right-block">
			<h3>Details</h3>
			<p>Taxi Booking pluing for WordPress. Your installed version is <span><?php echo esc_attr( $plugin_data['Version'] ); ?></span></p>
		</div>
	</div>
</div>
