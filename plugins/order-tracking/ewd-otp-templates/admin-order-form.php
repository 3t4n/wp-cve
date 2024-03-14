<div id="ewd-otp-new-edit-order-screen">

	<?php $this->maybe_print_update_message(); ?>

	<form action='#' method='post' class='ewd-otp-admin-form' enctype='multipart/form-data'>

		<?php wp_nonce_field( 'ewd-otp-admin-nonce', 'ewd-otp-admin-nonce' );  ?>

		<?php echo ( ! empty( $this->order ) ? '<input type="hidden" name="ewd_otp_order_id" value="' . esc_attr( $this->order->id ) . '">' : '' ); ?>

		<div class='ewd-otp-admin-add-edit-order-content'>

			<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full ewd-otp-admin-edit-product-left-full-widget-box" id="ewd-otp-admin-edit-order-details-widget-box">

				<div class="ewd-otp-dashboard-new-widget-box-top"><?php _e('Order Details', 'order-tracking'); ?></div>
				
				<div class="ewd-otp-dashboard-new-widget-box-bottom">

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_name">
								<?php _e( 'Order Name', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>
							<input type='text' name="ewd_otp_name" value="<?php echo ( ! empty( $this->order->name ) ? esc_attr( $this->order->name ) : '' ); ?>" />
						</div>

					</div>

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_number">
								<?php _e( 'Order Number', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>
							<input type='text' name="ewd_otp_number" value="<?php echo ( ! empty( $this->order->number ) ? esc_attr( $this->order->number ) : '' ); ?>" />
						</div>

					</div>

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_email">
								<?php _e( 'Email', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>
							<input type='text' name="ewd_otp_email" value="<?php echo ( ! empty( $this->order->email ) ? esc_attr( $this->order->email ) : '' ); ?>" />
						</div>

					</div>

					<?php do_action( 'ewd_otp_post_order_information_fields', $this->order, $this ); ?>

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_status">
								<?php _e( 'Status', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>

							<?php $statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) ); ?>

							<select name='ewd_otp_status'>

								<?php foreach ( $statuses as $status ) { ?>

									<option value='<?php echo esc_attr( $status->status ); ?>' <?php echo ( (! empty( $this->order->status ) and $this->order->status == $status->status ) ? 'selected' : '' ); ?>>
										<?php echo esc_html( $status->status ); ?>
									</option>

								<?php } ?>

							</select>

						</div>

					</div>

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_location">
								<?php _e( 'Location', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>

							<?php $locations = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'locations' ) ); ?>

							<select name='ewd_otp_location'>

								<?php foreach ( $locations as $location ) { ?>

									<option value='<?php echo esc_attr( $location->name ); ?>' <?php echo ( (! empty( $this->order->location ) and $this->order->location == $location->name ) ? 'selected' : '' ); ?>>
										<?php echo esc_html( $location->name ); ?>
									</option>

								<?php } ?>

							</select>
							
						</div>

					</div>

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_customer">
								<?php _e( 'Customer', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>

							<?php 

								$args = array(
									'customers_per_page'	=> -1
								);

								$customers = $ewd_otp_controller->customer_manager->get_matching_customers( $args ); 

							?>

							<select name='ewd_otp_customer'>

								<option value='-1'><?php _e( 'None', 'order-tracking' ); ?></option>

								<?php foreach ( $customers as $customer ) { ?>

									<option value='<?php echo esc_attr( $customer->id ); ?>' <?php echo ( (! empty( $this->order->customer ) and $this->order->customer == $customer->id ) ? 'selected' : '' ); ?>>
										<?php echo esc_html( $customer->name ); ?>
									</option>

								<?php } ?>

							</select>
							
						</div>

					</div>

					<?php if ( current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) ) ) { ?>

						<div class='ewd-otp-field'>

							<div class='ewd-otp-admin-label'>

								<label for="ewd_otp_sales_rep">
									<?php _e( 'Sales Rep', 'order-tracking' ); ?>
								</label>
		
							</div>
		
							<div class='ewd-otp-admin-input'>
		
								<?php 
		
									$args = array(
										'sales_reps_per_page'	=> -1
									);
		
									$sales_reps = $ewd_otp_controller->sales_rep_manager->get_matching_sales_reps( $args ); 
		
								?>
		
								<select name='ewd_otp_sales_rep'>
		
									<option value='-1'><?php _e( 'None', 'order-tracking' ); ?></option>
		
									<?php foreach ( $sales_reps as $sales_rep ) { ?>
		
										<option value='<?php echo esc_attr( $sales_rep->id ); ?>' <?php echo ( (! empty( $this->order->sales_rep ) and $this->order->sales_rep == $sales_rep->id ) ? 'selected' : '' ); ?>>
											<?php echo esc_html( $sales_rep->first_name . ' ' . $sales_rep->last_name ); ?>
										</option>
		
									<?php } ?>
		
								</select>
								
							</div>

						</div>

					<?php } else { ?>

						<?php if ( ! empty( $this->sales_rep->id ) ) { ?>

							<input type='hidden' name='ewd_otp_sales_rep' value='<?php echo esc_attr( $this->sales_rep->id ); ?>' />
						<?php } ?>

					<?php } ?>

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_display">
								<?php _e( 'Show in Admin Table?', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>

							<input type='radio' name="ewd_otp_display" value="yes" <?php echo ( ( empty( $this->order->id ) or ! empty( $this->order->display ) ) ? 'checked' : '' ); ?>><?php _e( 'Yes', 'order-tracking' ); ?><br/>
							<input type='radio' name="ewd_otp_display" value="no" <?php echo ( ( ! empty( $this->order->id ) and empty( $this->order->display ) ) ? 'checked' : '' ); ?>><?php _e( 'No', 'order-tracking' ); ?><br/>

							<p>
								<?php _e( 'Should this order appear in the orders table in the admin area?', 'order-tracking' ); ?>
							</p>

						</div>
						
					</div>

				</div>

			</div>

			<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full ewd-otp-admin-edit-product-left-full-widget-box" id="ewd-otp-admin-edit-customer-details-widget-box">

				<div class="ewd-otp-dashboard-new-widget-box-top"><?php _e('Notes', 'order-tracking'); ?></div>
				
				<div class="ewd-otp-dashboard-new-widget-box-bottom">

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_public_notes">
								<?php _e( 'Public Order Notes', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>

							<textarea name="ewd_otp_public_notes"><?php echo ( ! empty( $this->order->notes_public ) ? esc_attr( $this->order->notes_public ) : '' ); ?></textarea>

							<p>
								<?php _e( 'The notes visitors will see if you\'ve included \'Notes\' on the options page.', 'order-tracking' ); ?>
							</p>

						</div>

					</div>

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_private_notes">
								<?php _e( 'Private Order Notes', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>

							<textarea name="ewd_otp_private_notes"><?php echo ( ! empty( $this->order->notes_private ) ? esc_attr( $this->order->notes_private ) : '' ); ?></textarea>

							<p>
								<?php _e( 'Visible only to admins.', 'order-tracking' ); ?>
							</p>

						</div>

					</div>

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_customer_notes">
								<?php _e( 'Customer Order Notes', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>

							<textarea name="ewd_otp_customer_notes"><?php echo ( ! empty( $this->order->customer_notes ) ? esc_attr( $this->order->customer_notes ) : '' ); ?></textarea>

							<p>
								<?php _e( 'The notes about an order posted by the customer from the front-end.', 'order-tracking' ); ?>
							</p>

						</div>

					</div>

				</div>

			</div>

			<?php if ( ! empty( $this->order ) ) { ?>

				<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full ewd-otp-admin-edit-product-left-full-widget-box" id="ewd-otp-admin-edit-customer-details-widget-box">
	
					<div class="ewd-otp-dashboard-new-widget-box-top"><?php _e( 'Statuses', 'order-tracking' ); ?></div>
					
					<div class="ewd-otp-dashboard-new-widget-box-bottom">
	
						<table class='wp-list-table widefat tags sorttable fields-list'>
	
							<thead>
	
								<tr>
									<th><?php _e( 'Delete?', 'order-tracking' ); ?></th>
									<th><?php _e( 'Order Status', 'order-tracking' ); ?></th>
									<th><?php _e( 'Order Location', 'order-tracking' ); ?></th>
									<th><?php _e( 'Status Date/Time', 'order-tracking' ); ?></th>
								</tr>
	
							</thead>
	
							<tbody>
	
								<?php foreach ( $this->order->status_history as $status_history ) { ?>
	
									<?php 
	
										$args = array(
											'action' 	=> 'delete_status',
											'status_id'	=> $status_history->id
										);
	
									?>
	
									<?php $delete_url = add_query_arg( $args ); ?>
	
									<tr>
										<td><a href='<?php echo esc_attr( $delete_url ); ?>'><?php _e( 'Delete?', 'order-tracking' ); ?></a></td> 
										<td><?php echo esc_html( $status_history->status ); ?></td>
										<td><?php echo esc_html( $status_history->location ); ?></td>
										<td><?php echo date( $this->get_option( 'date-format' ), strtotime( $status_history->updated_fmtd ) ); ?></td>
									</tr>
	
								<?php } ?>
	
							</tbody>
	
							<tfoot>
	
								<tr>
									<th><?php _e( 'Delete?', 'order-tracking' ); ?></th>
									<th><?php _e( 'Order Status', 'order-tracking' ); ?></th>
									<th><?php _e( 'Order Location', 'order-tracking' ); ?></th>
									<th><?php _e( 'Status Date/Time', 'order-tracking' ); ?></th>
								</tr>
	
							</tfoot>
	
						</table>
	
					</div>
	
				</div>
	
			</div>

		<?php } ?>

		<div class='ewd-otp-admin-add-edit-order-sidebar'>
	
			<input type='submit' class='button-primary ewd-otp-admin-edit-product-save-button' name='ewd_otp_admin_order_submit' value='<?php echo ( empty( $this->order->id ) ? __( 'Save Order', 'order-tracking' ) : __( 'Update Order', 'order-tracking' ) ); ?>' />
			
			<?php $custom_fields = $ewd_otp_controller->settings->get_order_custom_fields(); ?>

			<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full" id="ewd-otp-admin-edit-order-custom-fields-widget-box">
					
				<div class="ewd-otp-dashboard-new-widget-box-top"><?php _e( 'Order Views', 'order-tracking' ); ?></div>
					
				<div class="ewd-otp-dashboard-new-widget-box-bottom">
					
					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_customer_notes">
								<?php _e( 'Order Views', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>

							<?php echo ( ! empty( $this->order->views ) ? esc_html( $this->order->views ) : 0 ); ?>

						</div>

					</div>

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_customer_notes">
								<?php _e( 'Email Tracking Link Clicked', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>

							<?php echo ( empty( $this->order->tracking_link_clicked ) ? __( 'No', 'order-tracking' ) : __( 'Yes', 'order-tracking' ) ); ?>

						</div>

					</div>
	
				</div>
	
			</div>

			<!-- Custom fields, if they exist for orders -->
			<?php if ( ! empty( $custom_fields ) ) { ?>

				<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full" id="ewd-otp-admin-edit-order-custom-fields-widget-box">
					
					<div class="ewd-otp-dashboard-new-widget-box-top"><?php _e('Custom Fields', 'order-tracking'); ?></div>
					
					<div class="ewd-otp-dashboard-new-widget-box-bottom">
	
						<?php foreach ( $custom_fields as $custom_field ) { ?>
	
							<div class='ewd-otp-field'>
	
								<div class='ewd-otp-admin-label'>
	
									<label for="ewd_otp_custom_field">
										<?php echo esc_html( $custom_field->name ); ?>
									</label>
	
								</div>
	
								<div class='ewd-otp-admin-input'>
									<?php $this->print_admin_custom_field( $custom_field ); ?>
								</div>
	
							</div>
	
						<?php } ?>
	
					</div>
	
				</div>

			<?php } ?>

			<!-- Order Payments, if enabled -->
			<?php if ( $this->get_option( 'allow-order-payments' ) ) { ?>

				<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full ewd-otp-admin-closeable-widget-box" id="ewd-otp-admin-edit-order-payment-widget-box">
					
					<div class="ewd-otp-dashboard-new-widget-box-top"><?php _e('PayPal Payment', 'order-tracking'); ?>

						<span class="ewd-otp-admin-edit-product-down-caret">&nbsp;&nbsp;&#9660;</span>
						<span class="ewd-otp-admin-edit-product-up-caret">&nbsp;&nbsp;&#9650;</span>

					</div>

					<div class="ewd-otp-dashboard-new-widget-box-bottom">

						<div class='ewd-otp-field'>

							<div class='ewd-otp-admin-label'>

								<label for="ewd_otp_payment_price">
									<?php _e( 'Order Payment Price', 'order-tracking' ); ?>
								</label>

							</div>

							<div class='ewd-otp-admin-input'>
								<input type='text' name="ewd_otp_payment_price" value="<?php echo ( ! empty( $this->order->payment_price ) ? esc_attr( $this->order->payment_price ) : '' ); ?>" />
							</div>

						</div>

						<div class='ewd-otp-field'>

							<div class='ewd-otp-admin-label'>

								<label for="ewd_otp_payment_completed">
									<?php _e( 'Payment Completed', 'order-tracking' ); ?>
								</label>

							</div>

							<div class='ewd-otp-admin-input'>

								<input type='radio' name="ewd_otp_payment_completed" value="yes" <?php echo ( ! empty( $this->order->payment_completed ) ? 'checked' : '' ); ?>><?php _e( 'Yes', 'order-tracking' ); ?><br/>
								<input type='radio' name="ewd_otp_payment_completed" value="no" <?php echo ( empty( $this->order->payment_completed ) ? 'checked' : '' ); ?>><?php _e( 'No', 'order-tracking' ); ?><br/>

								<p>
									<?php _e( 'This field should automatically update when payment is made.', 'order-tracking' ); ?>
								</p>

							</div>

						</div>

						<div class='ewd-otp-field'>

							<div class='ewd-otp-admin-label'>

								<label for="ewd_otp_paypal_receipt_number">
									<?php _e( 'PayPal Transaction ID', 'order-tracking' ); ?>
								</label>

							</div>

							<div class='ewd-otp-admin-input'>

								<input type='text' name="ewd_otp_paypal_receipt_number" value="<?php echo ( ! empty( $this->order->paypal_receipt_number ) ? esc_attr( $this->order->paypal_receipt_number ) : '' ); ?>" />

								<p>
									<?php _e( 'The transaction ID generated by PayPal for this order (leave blank until payment is made).', 'order-tracking' ); ?>
								</p>

							</div>

						</div>

					</div>

				</div>

			<?php } ?>

			<div class='ewd-otp-dashboard-new-widget-box ewd-widget-box-full ewd-otp-admin-closeable-widget-box'>
							
				<div class="ewd-otp-dashboard-new-widget-box-top">

					<?php _e('Need Help?', 'order-tracking'); ?>

				</div>
				
				<div class="ewd-otp-dashboard-new-widget-box-bottom">
					
					<div class='ewd-otp-need-help-box'>
						
						<div class='ewd-otp-need-help-text'>Visit our Support Center for documentation and tutorials</div>
							
						<a class='ewd-otp-need-help-button' href='https://www.etoilewebdesign.com/support-center/?Plugin=OTP' target='_blank'>GET SUPPORT</a>
						
					</div>

				</div>

			</div>

		</div>

	</form>

</div>