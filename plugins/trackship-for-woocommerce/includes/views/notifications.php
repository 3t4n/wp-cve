<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="outer_form_table ts_notifications_outer_table">
	<?php
	$late_shipments_email_enable = get_trackship_settings( 'late_shipments_email_enable' );
	$tab_type = isset( $_GET['tab'] ) ? sanitize_text_field($_GET['tab']) : '';	
	
	$ts_notifications = $this->trackship_shipment_status_notifications_data();
	$plan_class = in_array( get_option( 'user_plan' ), array( 'Free Trial', 'Free 50', 'No active plan' ) ) ? 'free_user' : '' ;
	?>
	<div class="trackship_tab_name" style="margin-top: -10px;">
		<input id="tab_email_notifications" type="radio" name="ts_notification_tabs" class="inner_tab_input" data-tab="email-notification" data-type="email" <?php echo 'checked'; ?> >
		<label for="tab_email_notifications" class="inner_tab_label ts_tabs_label inner_email_tab"><?php esc_html_e( 'Email Notifications', 'trackship-for-woocommerce' ); ?></label>

		<input id="tab_sms_notifications" type="radio" name="ts_notification_tabs" class="inner_tab_input" data-tab="sms-notification" data-type="sms" <?php echo 'sms-notification' == $tab_type ? 'checked' : ''; ?> >
		<label for="tab_sms_notifications" class="inner_tab_label ts_tabs_label inner_sms_tab"><?php esc_html_e( 'SMS Notifications', 'trackship-for-woocommerce' ); ?></label>
		
		<input id="tab_admin_notifications" type="radio" name="ts_notification_tabs" class="inner_tab_input" data-tab="admin-notification" data-type="late-email" <?php echo 'admin-notification' == $tab_type ? 'checked' : ''; ?> >
		<label for="tab_admin_notifications" class="inner_tab_label ts_tabs_label inner_admin_tab"><?php esc_html_e( 'Admin Notifications', 'trackship-for-woocommerce' ); ?></label>
	</div>
	<section class="inner_tab_section shipment-status-email-section">
		<?php $nonce = wp_create_nonce( 'tswc_shipment_status_email'); ?>
		<input type="hidden" id="tswc_shipment_status_email" name="tswc_shipment_status_email" value="<?php echo esc_attr( $nonce ); ?>" />
		<table class="form-table shipment-status-email-table">
			<tbody>
				<?php foreach ( $ts_notifications as $key => $val ) { ?>
					<?php $ast_enable_email = trackship_for_woocommerce()->ts_actions->get_option_value_from_array( $val['option_name'], $val['enable_status_name'], ''); ?>
					<tr class="<?php echo 1 == $ast_enable_email ? 'enable' : 'disable'; ?> ">
						<td class="forminp status-label-column">
							<?php $image_name = in_array( $val['slug'], array( 'failed-attempt', 'exception' ) ) ? 'failure' : $val['slug']; ?>
							<?php $image_name = 'delivered-status' == $image_name ? 'delivered' : $image_name; ?>
							<?php $image_name = 'pickup-reminder' == $image_name ? 'available-for-pickup' : $image_name; ?>
							<img src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/css/icons/<?php echo esc_html( $image_name ); ?>.png">
							<strong class="shipment-status-label <?php echo esc_html( $val['slug'] ); ?>"><?php echo esc_html( $val['title'] ); ?></strong>
							<?php if ( 'delivered' == $key ) { ?>
								<label for="all-shipment-status-<?php echo esc_html( $key ); ?>">
									<input type="hidden" name="all-shipment-status-<?php echo esc_html( $key ); ?>" value="no">
									<input name="all-shipment-status-<?php echo esc_html( $key ); ?>" type="checkbox" id="all-shipment-status-<?php echo esc_html( $key ); ?>" value="yes" <?php echo get_option( 'all-shipment-status-' . $key ) == 1 ? 'checked' : ''; ?> >
									<?php echo esc_html( $val['title2'] ); ?>
									<?php $nonce = wp_create_nonce( 'all_status_delivered'); ?>
									<input type="hidden" id="all_status_delivered" name="all_status_delivered" value="<?php echo esc_attr( $nonce ); ?>" />
								</label>
							<?php } ?>
						</td>
						<td class="forminp">
							<span class="shipment_status_toggle">
								<input type="hidden" name="<?php echo esc_html( $val['enable_status_name'] ); ?>" value="0"/>
								<input class="ast-tgl ast-tgl-flat" id="<?php echo esc_html( $val['enable_status_name'] ); ?>" name="<?php echo esc_html( $val['enable_status_name'] ); ?>" data-settings="<?php echo esc_html( $val['option_name'] ); ?>" type="checkbox" <?php echo 1 == $ast_enable_email ? 'checked' : ''; ?> value="yes"/>
								<label class="ast-tgl-btn ast-tgl-btn-green" for="<?php echo esc_html( $val['enable_status_name'] ); ?>"></label>	
							</span>
							<a class="edit_customizer_a dashicons dashicons-admin-generic" href="<?php echo esc_html( $val['customizer_url'] ); ?>"></a>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php do_action( 'after_shipment_status_email_notifications' ); ?>
	</section>
	<section class="inner_tab_section shipment-status-late-email-section">
		<form method="post" id="trackship_late_shipments_form" class="<?php echo esc_html($plan_class); ?>" action="" enctype="multipart/form-data">
			<?php wp_nonce_field( 'ts_late_shipments_email_form', 'ts_late_shipments_email_form_nonce' ); ?>
			<input type="hidden" name="action" value="ts_late_shipments_email_form_update">
			<div class="admin_notifications_div">
				<table class="form-table heading-table shipment-status-email-table">
					<tbody>
						<tr class="admin_notifications_tr late-shipment-tr <?php echo 1 == $late_shipments_email_enable ? 'enable' : 'disable'; ?> ">
							<td class="forminp status-label-column">
								<img src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/css/icons/late-shipment.png">
								<strong><?php esc_html_e('Late Shipments', 'trackship-for-woocommerce'); ?></strong>
							</td>
							<td class="forminp">
								<button name="save" class="button-primary woocommerce-save-button btn_green2 btn_large" type="submit" value="Save & close"><?php esc_html_e( 'Save & close', 'trackship-for-woocommerce' ); ?></button>
								<?php
								$array_data = array(
									'type'		=> 'tgl_checkbox',
									'class'		=> 'shipment_status_toggle',
									'settings'	=> 'late_shipments_email_settings',
								);
								?>
								<?php trackship_for_woocommerce()->html->get_tgl_checkbox( 'late_shipments_email_enable', $array_data ); ?>
								<span class="edit_customizer_a dashicons dashicons-admin-generic"></span>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="late-shipments-email-content-table admin_notifiations_content">
					<?php $this->get_settings_html( $this->get_late_shipment_data() ); ?>
				</div>
			</div>
			
			<div class="admin_notifications_div">
				<table class="form-table heading-table shipment-status-email-table">
					<tbody>
						<tr class="admin_notifications_tr exception-shipment-tr <?php echo 1 == get_trackship_settings( 'exception_admin_email_enable' ) ? 'enable' : 'disable'; ?> ">
							<td class="forminp status-label-column">
								<img src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/css/icons/failure.png">
								<strong><?php esc_html_e('Exception Shipments', 'trackship-for-woocommerce'); ?></strong>
							</td>
							<td class="forminp">
								<button name="save" class="button-primary woocommerce-save-button btn_green2 btn_large" type="submit" value="Save & close"><?php esc_html_e( 'Save & close', 'trackship-for-woocommerce' ); ?></button>
								<?php
								$array_data = array(
									'type'		=> 'tgl_checkbox',
									'class'		=> 'shipment_status_toggle',
									'settings'	=> 'exception_admin_email',
								);
								?>
								<?php trackship_for_woocommerce()->html->get_tgl_checkbox( 'exception_admin_email_enable', $array_data ); ?>
								<span class="edit_customizer_a dashicons dashicons-admin-generic"></span>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="exception-shipments-email-content-table admin_notifiations_content">
					<?php $this->get_settings_html( $this->get_exception_shipment_data() ); ?>
				</div>
			</div>

			<div class="admin_notifications_div">
				<table class="form-table heading-table shipment-status-email-table">
					<tbody>
						<tr class="admin_notifications_tr on-hold-shipment-tr <?php echo 1 == get_trackship_settings( 'on_hold_admin_email_enable' ) ? 'enable' : 'disable'; ?> ">
							<td class="forminp status-label-column">
								<img src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/css/icons/on-hold.png">
								<strong><?php esc_html_e('On Hold Shipments', 'trackship-for-woocommerce'); ?></strong>
							</td>
							<td class="forminp">
								<button name="save" class="button-primary woocommerce-save-button btn_green2 btn_large" type="submit" value="Save & close"><?php esc_html_e( 'Save & close', 'trackship-for-woocommerce' ); ?></button>
								<?php
								$array_data = array(
									'type'		=> 'tgl_checkbox',
									'class'		=> 'shipment_status_toggle',
									'settings'	=> 'on_hold_admin_email',
								);
								?>
								<?php trackship_for_woocommerce()->html->get_tgl_checkbox( 'on_hold_admin_email_enable', $array_data ); ?>
								<span class="edit_customizer_a dashicons dashicons-admin-generic"></span>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="exception-shipments-email-content-table admin_notifiations_content">
					<?php $this->get_settings_html( $this->get_on_hold_shipment_data() ); ?>
				</div>
			</div>
		</form>
	</section>
	<section class="inner_tab_section shipment-status-sms-section">
		<?php if ( ! function_exists( 'SMSWOO' ) && in_array( get_option( 'user_plan' ), array( 'Free Trial', 'Free 50', 'No active plan' ) ) ) { ?>
			<input type="hidden" class="disable_pro" name="disable_pro" value="disable_pro">
		<?php } ?>
		<?php do_action( 'shipment_status_sms_section' ); ?>
	</section>
</div>
