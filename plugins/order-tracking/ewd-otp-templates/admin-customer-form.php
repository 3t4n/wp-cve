<div id="ewd-otp-new-edit-order-screen">

	<?php $this->maybe_print_update_message(); ?>

	<form action='#' method='post' class='ewd-otp-admin-form' enctype='multipart/form-data'>

		<?php wp_nonce_field( 'ewd-otp-admin-nonce', 'ewd-otp-admin-nonce' );  ?>

		<?php echo ( ! empty( $this->customer ) ? '<input type="hidden" name="ewd_otp_customer_id" value="' . esc_attr( $this->customer->id ) . '">' : '' ); ?>

		<div class='ewd-otp-admin-add-edit-order-content'>

			<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full ewd-otp-admin-edit-product-left-full-widget-box" id="ewd-otp-admin-edit-customer-details-widget-box">

				<div class="ewd-otp-dashboard-new-widget-box-top"><?php _e( 'Customer Details', 'order-tracking' ); ?></div>
				
				<div class="ewd-otp-dashboard-new-widget-box-bottom">

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_number">
								<?php _e( 'Number', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>
							<input type='text' name="ewd_otp_number" value="<?php echo ( ! empty( $this->customer->number ) ? esc_attr( $this->customer->number ) : '' ); ?>" />
						</div>

					</div>

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_name">
								<?php _e( 'Name', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>
							<input type='text' name="ewd_otp_name" value="<?php echo ( ! empty( $this->customer->name ) ? esc_attr( $this->customer->name ) : '' ); ?>" />
						</div>

					</div>

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_email">
								<?php _e( 'Email', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>
							<input type='email' name="ewd_otp_email" value="<?php echo ( ! empty( $this->customer->email ) ? esc_attr( $this->customer->email ) : '' ); ?>" />
						</div>

					</div>

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
		
									<option value='<?php echo esc_attr( $sales_rep->id ); ?>' <?php echo ( (! empty( $this->customer->sales_rep ) and $this->customer->sales_rep == $sales_rep->id ) ? 'selected' : '' ); ?>>
										<?php echo esc_html( $sales_rep->first_name . ' ' . $sales_rep->last_name ); ?>
									</option>
		
								<?php } ?>
		
							</select>
								
						</div>

					</div>

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_wp_id">
								<?php _e( 'Customer WP Username', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>

							<?php $users = get_users( 'blog_id=' . get_current_blog_id() ); ?>

							<select name='ewd_otp_wp_id'>

								<option value='-1'><?php _e( 'None', 'order-tracking' ); ?></option>

								<?php foreach ( $users as $user ) { ?>

									<option value='<?php echo esc_attr( $user->ID ); ?>' <?php echo ( (! empty( $this->customer->wp_id ) and $this->customer->wp_id == $user->ID ) ? 'selected' : '' ); ?>>
										<?php echo esc_html( $user->user_login ); ?>
									</option>

								<?php } ?>

							</select>

							<p>
								<?php _e( 'What WordPress user, if any, is assigned to this customer?', 'order-tracking' ); ?>
							</p>
							
						</div>

					</div>

					<?php if ( function_exists( 'EWD_FEUP_Get_All_Users' ) ) { ?>

						<div class='ewd-otp-field'>

							<div class='ewd-otp-admin-label'>
		
								<label for="ewd_otp_feup_id">
									<?php _e( 'Customer FEUP Username', 'order-tracking' ); ?>
								</label>
		
							</div>
		
							<div class='ewd-otp-admin-input'>
		
								<?php $users = EWD_FEUP_Get_All_Users(); ?>
		
								<select name='ewd_otp_feup_id'>
		
									<option value='-1'><?php _e( 'None', 'order-tracking' ); ?></option>
		
									<?php foreach ( $users as $user ) { ?>
		
										<option value='<?php echo esc_attr( $user->Get_User_ID() ); ?>' <?php echo ( (! empty( $this->customer->feup_id ) and $this->customer->feup_id == $user->Get_User_ID() ) ? 'selected' : '' ); ?>>
											<?php echo esc_html( $user->Get_Username() ); ?>
										</option>
		
									<?php } ?>
		
								</select>
		
								<p>
									<?php _e( 'What FEUP user, if any, is assigned to this customer?', 'order-tracking' ); ?>
								</p>
								
							</div>

						</div>

					<?php } ?>

				</div>

			</div>

			<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full ewd-otp-admin-edit-product-left-full-widget-box" id="ewd-otp-admin-edit-customer-details-widget-box">

				<div class="ewd-otp-dashboard-new-widget-box-top"><?php _e( 'Customer Orders', 'order-tracking' ); ?></div>
				
				<div class="ewd-otp-dashboard-new-widget-box-bottom">

					<table class='wp-list-table widefat tags sorttable fields-list'>

						<thead>

							<tr>
								<th><?php _e( 'Order', 'order-tracking' ); ?></th>
								<th><?php _e( 'Status', 'order-tracking' ); ?></th>
								<th><?php _e( 'Location', 'order-tracking' ); ?></th>
								<th><?php _e( 'Updated', 'order-tracking' ); ?></th>
							</tr>

						</thead>

						<tbody>

							<?php 

								$args = array(
									'orders_per_page' 	=> -1,
									'display'			=> true
								);

								$customer_orders = ! empty( $this->customer->id ) ? $this->customer->get_customer_orders( $args ) : array();

							?>

							<?php foreach ( $customer_orders as $order ) { ?>

								<?php 

									$args = array(
										'order_id'	=> $order->id
									);

								?>
								
								<?php $edit_order_url = add_query_arg( $args, 'admin.php?page=ewd-otp-add-edit-order' ); ?>

								<tr>
									<td><a href='<?php echo esc_attr( $edit_order_url ); ?>'><?php echo esc_html( $order->name ); ?></a></td> 
									<td><?php echo esc_html( $order->status ); ?></td>
									<td><?php echo esc_html( $order->location ); ?></td>
									<td><?php echo date( $this->get_option( 'date-format' ), strtotime( $order->status_updated_fmtd ) ); ?></td>
								</tr>

							<?php } ?>

						</tbody>

						<tfoot>

							<tr>
								<th><?php _e( 'Order', 'order-tracking' ); ?></th>
								<th><?php _e( 'Status', 'order-tracking' ); ?></th>
								<th><?php _e( 'Location', 'order-tracking' ); ?></th>
								<th><?php _e( 'Updated', 'order-tracking' ); ?></th>
							</tr>

						</tfoot>

					</table>

				</div>

			</div>

		</div>

		<div class='ewd-otp-admin-add-edit-order-sidebar'>

			<input type='submit' class='button-primary ewd-otp-admin-edit-product-save-button' name='ewd_otp_admin_customer_submit' value='<?php echo ( empty( $this->customer->id ) ? __( 'Save Customer', 'order-tracking' ) : __( 'Update Customer', 'order-tracking' ) ); ?>' />

			<div class='ewd-otp-dashboard-new-widget-box ewd-widget-box-full'>
							
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

			<?php $custom_fields = $ewd_otp_controller->settings->get_customer_custom_fields(); ?>

			<!-- Custom fields, if they exist for customers -->
			<?php if ( ! empty( $custom_fields ) ) { ?>

				<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full" id="ewd-otp-admin-edit-customer-custom-fields-widget-box">
				
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

		</div>

	</form>

</div>