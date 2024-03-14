<div id="ewd-otp-new-edit-order-screen">

	<?php $this->maybe_print_update_message(); ?>

	<form action='#' method='post' class='ewd-otp-admin-form' enctype='multipart/form-data'>

		<?php wp_nonce_field( 'ewd-otp-admin-nonce', 'ewd-otp-admin-nonce' );  ?>

		<?php echo ( ! empty( $this->sales_rep ) ? '<input type="hidden" name="ewd_otp_sales_rep_id" value="' . esc_attr( $this->sales_rep->id ) . '">' : '' ); ?>

		<div class='ewd-otp-admin-add-edit-order-content'>

			<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full ewd-otp-admin-edit-product-left-full-widget-box" id="ewd-otp-admin-edit-sales_rep-details-widget-box">

				<div class="ewd-otp-dashboard-new-widget-box-top"><?php _e( 'Sales Rep Details', 'order-tracking' ); ?></div>
				
				<div class="ewd-otp-dashboard-new-widget-box-bottom">

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_number">
								<?php _e( 'Number', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>
							<input type='text' name="ewd_otp_number" value="<?php echo ( ! empty( $this->sales_rep->number ) ? esc_attr( $this->sales_rep->number ) : '' ); ?>" />
						</div>

					</div>

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_first_name">
								<?php _e( 'First Name', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>
							<input type='text' name="ewd_otp_first_name" value="<?php echo ( ! empty( $this->sales_rep->first_name ) ? esc_attr( $this->sales_rep->first_name ) : '' ); ?>" />
						</div>

					</div>

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_last_name">
								<?php _e( 'Last Name', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>
							<input type='text' name="ewd_otp_last_name" value="<?php echo ( ! empty( $this->sales_rep->last_name ) ? esc_attr( $this->sales_rep->last_name ) : '' ); ?>" />
						</div>

					</div>

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_email">
								<?php _e( 'Email', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>
							<input type='email' name="ewd_otp_email" value="<?php echo ( ! empty( $this->sales_rep->email ) ? esc_attr( $this->sales_rep->email ) : '' ); ?>" />
						</div>

					</div>

					<?php do_action( 'ewd_otp_post_sales_rep_information_fields', $this->sales_rep, $this ); ?>

					<div class='ewd-otp-field'>

						<div class='ewd-otp-admin-label'>

							<label for="ewd_otp_wp_id">
								<?php _e( 'Sales Rep WP Username', 'order-tracking' ); ?>
							</label>

						</div>

						<div class='ewd-otp-admin-input'>

							<?php $users = get_users( 'blog_id=' . get_current_blog_id() ); ?>

							<select name='ewd_otp_wp_id'>

								<option value='-1'><?php _e( 'None', 'order-tracking' ); ?></option>

								<?php foreach ( $users as $user ) { ?>

									<option value='<?php echo esc_attr( $user->ID ); ?>' <?php echo ( (! empty( $this->sales_rep->wp_id ) and $this->sales_rep->wp_id == $user->ID ) ? 'selected' : '' ); ?>>
										<?php echo esc_html( $user->user_login ); ?>
									</option>

								<?php } ?>

							</select>

							<p>
								<?php _e( 'What WordPress user, if any, is assigned to this sales_rep?', 'order-tracking' ); ?>
							</p>
							
						</div>

					</div>

				</div>

			</div>

			<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full ewd-otp-admin-edit-product-left-full-widget-box" id="ewd-otp-admin-edit-sales_rep-details-widget-box">

				<div class="ewd-otp-dashboard-new-widget-box-top"><?php _e( 'Sales Rep Orders', 'order-tracking' ); ?></div>
				
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

								$sales_rep_orders = ! empty( $this->sales_rep->id ) ? $this->sales_rep->get_sales_rep_orders( $args ) : array();

							?>

							<?php foreach ( $sales_rep_orders as $order ) { ?>

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

			<input type='submit' class='button-primary ewd-otp-admin-edit-product-save-button' name='ewd_otp_admin_sales_rep_submit' value='<?php echo ( empty( $this->sales_rep->id ) ? __( 'Save Sales Rep', 'order-tracking' ) : __( 'Update Sales Rep', 'order-tracking' ) ); ?>' />

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

			<?php $custom_fields = $ewd_otp_controller->settings->get_sales_rep_custom_fields(); ?>

			<!-- Custom fields, if they exist for sales reps -->
			<?php if ( ! empty( $custom_fields ) ) { ?>

				<div class="ewd-otp-dashboard-new-widget-box ewd-widget-box-full" id="ewd-otp-admin-edit-sales-rep-custom-fields-widget-box">
				
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