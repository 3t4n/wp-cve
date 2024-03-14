<section id="wclp_content1" class="wclp_tab_section">
	<div class="wclp_tab_inner_container">
		<div class="wclp_outer_form_table">
			<form method="post" id="wclp_setting_tab_form" class="wclp_setting_tab_form">
				<div class="accordion heading">
					<label>
						<?php esc_html_e( 'Display options', 'advanced-local-pickup-for-woocommerce' ); ?>
						<span class="submit wclp-btn">
							<div class="spinner workflow_spinner" style="float:none"></div>
							<button name="save" class="wclp-save button-primary woocommerce-save-button" type="submit" value="Save & close"><?php esc_html_e( 'Save & close', 'advanced-local-pickup-for-woocommerce' ); ?></button>
							<?php wp_nonce_field( 'wclp_setting_form_action', 'wclp_setting_form_nonce_field' ); ?>
							<input type="hidden" name="action" value="wclp_setting_form_update">
						</span>
						<span class="dashicons dashicons-arrow-right-alt2"></span>
					</label>
				</div>
				<div class="panel">
					<table class="form-table html-layout-2">
						<tbody>
							<?php $this->get_html2( $this->wclp_general_setting_fields_func() ); ?>
						</tbody>
					</table>
				</div>
				<div class="accordion heading">
					<label>
						<?php esc_html_e( 'Local pickup workflow', 'advanced-local-pickup-for-woocommerce' ); ?>
						<span class="submit wclp-btn">
							<div class="spinner workflow_spinner" style="float:none"></div>
							<button name="save" class="wclp-save button-primary woocommerce-save-button" type="submit" value="Save changes"><?php esc_html_e( 'Save & close', 'advanced-local-pickup-for-woocommerce' ); ?></button>
							<input type="hidden" name="action" value="wclp_setting_form_update">
						</span>
						<span class="dashicons dashicons-arrow-right-alt2"></span>
					</label>
				</div>
				<div class="panel">
					<table class="form-table order-status-table">
						<tbody>
							<tr valign="top" class="ready_pickup_row <?php echo ( !get_option('wclp_status_ready_pickup') ) ? 'disable_row' : ''; ?>">
								<td class="forminp">
									<span class="tgl-btn-parent" style="">
										<input type="hidden" name="wclp_status_ready_pickup" value="0">
										<input type="checkbox" id="wclp_status_ready_pickup" name="wclp_status_ready_pickup" class="tgl tgl-flat-alp" <?php echo ( get_option('wclp_status_ready_pickup') ) ? 'checked' : ''; ?> value="1"/>
										<label class="tgl-btn" for="wclp_status_ready_pickup"></label>
									</span>
								</td>
								<td class="forminp status-label-column" style="width: 130px;">
									<span class="order-label wc-ready-pickup" style="background:<?php echo esc_html(get_option('wclp_ready_pickup_status_label_color', '#8bc34a')); ?>;color:<?php echo esc_html(get_option('wclp_ready_pickup_status_label_font_color', '#fff')); ?>">
										<?php esc_html_e( 'Ready for pickup', 'advanced-local-pickup-for-woocommerce' ); ?>
									</span>
								</td>
								<td class="forminp">
									<fieldset>
										<input class="input-text regular-input " type="text" name="wclp_ready_pickup_status_label_color" id="wclp_ready_pickup_status_label_color" style="" value="<?php echo esc_html(get_option('wclp_ready_pickup_status_label_color', '#8bc34a' )); ?>" placeholder="">
										<select class="select" id="wclp_ready_pickup_status_label_font_color" name="wclp_ready_pickup_status_label_font_color">	
											<option value="#fff" <?php echo ( '#fff' == get_option('wclp_ready_pickup_status_label_font_color') ) ? 'selected' : ''; ?>><?php esc_html_e( 'Light Font', 'advanced-local-pickup-for-woocommerce' ); ?></option>
											<option value="#000" <?php echo ( '#000' == get_option('wclp_ready_pickup_status_label_font_color') ) ? 'selected' : ''; ?>><?php esc_html_e( 'Dark Font', 'advanced-local-pickup-for-woocommerce' ); ?></option>
										</select>
									</fieldset>
								</td>								
								<td class="forminp" style="text-align: right;">							
									<?php
									$wclp_enable_ready_pickup_email = get_option('woocommerce_customer_ready_pickup_order_settings');
									if (isset($wclp_enable_ready_pickup_email) && !empty($wclp_enable_ready_pickup_email)) {
										if ('yes' == $wclp_enable_ready_pickup_email['enabled'] || 1 == $wclp_enable_ready_pickup_email['enabled']) {
											$ready_pickup_checked = 'checked';
										} else {
											$ready_pickup_checked = '';									
										}
									} else {
										$ready_pickup_checked = 'checked';
									}
									?>
									<fieldset>
										<label class="send_email_label">
											<input type="hidden" name="wclp_enable_ready_pickup_email" value="0"/>
											<input type="checkbox" name="wclp_enable_ready_pickup_email" id="wclp_enable_ready_pickup_email" <?php echo esc_html($ready_pickup_checked); ?> value="1"><?php esc_html_e( 'Send Email', 'advanced-local-pickup-for-woocommerce' ); ?>
										</label>
										<a class='settings_edit' href="<?php echo esc_url(admin_url('admin.php?page=alp_customizer&email_type=ready_pickup')); ?>"><?php esc_html_e( 'Customize', 'advanced-local-pickup-for-woocommerce' ); ?></a>
									</fieldset>
								</td>
							</tr>					
							<tr valign="top" class="picked_up_row  
							<?php 
							if (!get_option('wclp_status_picked_up')) {
								echo 'disable_row';
							} 
							?>
							">
								<td class="forminp">
									<span class="tgl-btn-parent" style="">
										<input type="hidden" name="wclp_status_picked_up" value="0">
										<input type="checkbox" id="wclp_status_picked_up" name="wclp_status_picked_up" class="tgl tgl-flat-alp" 
										<?php echo ( get_option('wclp_status_picked_up') ) ? 'checked' : ''; ?> value="1"/>
										<label class="tgl-btn" for="wclp_status_picked_up"></label>
									</span>
								</td>
								<td class="forminp status-label-column" style="width: 130px;">
									<span class="order-label wc-pickup" style="background:<?php echo esc_html(get_option('wclp_pickup_status_label_color', '#2196f3')); ?>;color:<?php echo esc_html(get_option('wclp_pickup_status_label_font_color', '#fff')); ?>">
										<?php esc_html_e( 'Picked up', 'advanced-local-pickup-for-woocommerce' ); ?>
									</span>
								</td>
								<td class="forminp">
									<fieldset>
										<input class="input-text regular-input " type="text" name="wclp_pickup_status_label_color" id="wclp_pickup_status_label_color" style="" value="<?php echo esc_html(get_option('wclp_pickup_status_label_color', '#2196f3')); ?>" placeholder="">
										<select class="select" id="wclp_pickup_status_label_font_color" name="wclp_pickup_status_label_font_color">	
											<option value="#fff" <?php echo ( '#fff' == get_option('wclp_pickup_status_label_font_color') ) ? 'selected' : ''; ?>><?php esc_html_e( 'Light Font', 'advanced-local-pickup-for-woocommerce' ); ?></option>
											<option value="#000" <?php echo ( '#000' == get_option('wclp_pickup_status_label_font_color') ) ? 'selected' : ''; ?>><?php esc_html_e( 'Dark Font', 'advanced-local-pickup-for-woocommerce' ); ?></option>
										</select>
									</fieldset>
								</td>								
								<td class="forminp" style="text-align: right;">							
									<?php
									$wclp_enable_pickup_email = get_option('woocommerce_customer_pickup_order_settings');
									if (isset($wclp_enable_pickup_email) && !empty($wclp_enable_pickup_email)) {
										if ('yes' == $wclp_enable_pickup_email['enabled'] || 1 == $wclp_enable_pickup_email['enabled']) {
											$pickup_checked = 'checked';
										} else {
											$pickup_checked = '';									
										}
									} else {
										$pickup_checked = 'checked';
									}
									?>
									<fieldset>
										<label class="send_email_label">
											<input type="hidden" name="wclp_enable_pickup_email" value="0"/>
											<input type="checkbox" name="wclp_enable_pickup_email" id="wclp_enable_pickup_email" <?php echo esc_html($pickup_checked); ?> value="1"><?php esc_html_e( 'Send Email', 'advanced-local-pickup-for-woocommerce' ); ?>
										</label>
										<a class='settings_edit' href="<?php echo esc_url(admin_url('admin.php?page=alp_customizer&email_type=pickup')); ?>"><?php esc_html_e( 'Customize', 'advanced-local-pickup-for-woocommerce' ); ?></a>
									</fieldset>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				
				<div class="accordion heading premium">
					<label>
						<?php esc_html_e( 'Local Pickup Dashboard', 'advanced-local-pickup-for-woocommerce' ); ?>
						<span class="premium-label"><?php esc_html_e( 'Premium', 'advanced-local-pickup-for-woocommerce' ); ?></span>
						<span class="dashicons dashicons-arrow-right-alt2"></span>
					</label>
				</div>

				<div class="accordion heading premium">
					<label>
						<?php esc_html_e( 'Cart & Checkout Options', 'advanced-local-pickup-for-woocommerce' ); ?>
						<span class="premium-label"><?php esc_html_e( 'Premium', 'advanced-local-pickup-for-woocommerce' ); ?></span>
						<span class="dashicons dashicons-arrow-right-alt2"></span>
					</label>
				</div>

				<div class="accordion heading premium">
					<label>
						<?php esc_html_e( 'Products Catalog Options', 'advanced-local-pickup-for-woocommerce' ); ?>
						<span class="premium-label"><?php esc_html_e( 'Premium', 'advanced-local-pickup-for-woocommerce' ); ?></span>
						<span class="dashicons dashicons-arrow-right-alt2"></span>
					</label>
				</div>
			</form>
		</div>
	</div>
</section>
