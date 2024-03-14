<div class="container-fluid">
    <div class="row sue-row">

        <div class="col-sm-9">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title mb-4 text-uppercase text-center"><?php
						echo __( 'Send email to selected users', 'send-users-email' );
						?></h5>


					<?php

					if ( $total_users > 6000 ) {
						?>
                        <div class="alert alert-danger" role="alert">
							<?php
							echo __( 'You have high number of users in the system so this page might take some time to load all users. Please consider using Role Email instead.',
								'send-users-email' );
							?>
                        </div>
						<?php
					}

					?>

                    <form action="javascript:void(0)" id="sue-users-email-form" method="post">

                        <div class="mb-4">
                            <label for="subject"
                                   class="form-label"><?php
								echo __( 'Email Subject', 'send-users-email' );
								?></label>
                            <input type="text" class="form-control subject" id="subject" name="subject" maxlength="200"
                                   placeholder="<?php
							       echo __( 'Email subject here.', 'send-users-email' );
							       ?> <?php
							       ?>">
                        </div>

                        <div class="mb-4 sue-user-email-datatable-wrapper">
                            <div class="sue-user-email-datatable">
                                <label for="sue_users"
                                       class="form-label"><?php
									echo __( 'Select Users', 'send-users-email' );
									?></label>

                                <table class="table table-sm">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <strong><?php
												echo __( 'Filter users using user ID range', 'send-users-email' );
												?></strong>
                                        </td>
                                        <td><label for="minID"><?php
												echo __( 'Minimum ID', 'send-users-email' );
												?></label></td>
                                        <td><input class="form-control" type="text" id="minID" name="minID"></td>
                                        <td><label for="maxID"><?php
												echo __( 'Maximum ID', 'send-users-email' );
												?></label></td>
                                        <td><input class="form-control" type="text" id="maxID" name="maxID"></td>
                                    </tr>
                                    </tbody>
                                </table>

                                <table id="sue-user-email-tbl" class="table table-striped table-sm">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" id="sueSelectAllUsers"></th>
                                        <th><?php
											echo __( 'ID', 'send-users-email' );
											?></th>
                                        <th><?php
											echo __( 'Username', 'send-users-email' );
											?></th>
                                        <th><?php
											echo __( 'Email', 'send-users-email' );
											?></th>
                                        <th><?php
											echo __( 'Display Name', 'send-users-email' );
											?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php
									foreach ( $blog_users as $user ) {
										?>
                                        <tr>
                                            <td><input type="checkbox" class="sueUserCheck" name="users[]"
                                                       value="<?php
											           echo esc_html( $user->ID );
											           ?>"></td>
                                            <td><?php
												echo esc_html( $user->ID );
												?></td>
                                            <td><?php
												echo esc_html( $user->user_login );
												?></td>
                                            <td><?php
												echo esc_html( $user->user_email );
												?></td>
                                            <td><?php
												echo esc_html( $user->display_name );
												?></td>
                                        </tr>
										<?php
									}
									?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="sue_user_email_message"
                                   class="form-label"><?php
								echo __( 'Email Message', 'send-users-email' );
								?></label>

							<?php
							// Initialize RTE
							wp_editor( '', 'sue_user_email_message', [
								'textarea_rows' => 15,
							] );
							?>
                            <div class="message"></div>
                        </div>

                        <input type="hidden" id="_wpnonce" name="_wpnonce"
                               value="<?php
						       echo wp_create_nonce( 'sue-email-user' );
						       ?>"/>


                        <div class="row">
                            <div class="col-md-3">
                                <div class="d-grid gap-2">
                                    <button type="submit" id="sue-user-email-btn" class="btn btn-primary btn-block">
                                        <span class="dashicons dashicons-email"></span> <?php
										echo __( 'Send Message', 'send-users-email' );
										?>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-2 mt-2">
                                <div class="spinner-border text-info sue-spinner" role="status">
                                    <span class="visually-hidden"><?php
	                                    echo __( 'Loading...', 'send-users-email' );
	                                    ?></span>
                                </div>
                            </div>
                            <div class="col-md-7 mt-2">
                                <div class="progress" style="height: 20px; display: none;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                         role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        <div class="col-sm-3">

			<?php
			require_once SEND_USERS_EMAIL_PLUGIN_BASE_PATH . '/partials/donate.php';
			?>

			<?php
			require_once SEND_USERS_EMAIL_PLUGIN_BASE_PATH . '/partials/warnings.php';
			?>

            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title text-uppercase mb-4"><?php
						echo __( 'Hide columns', 'send-users-email' );
						?></h5>
					<?php
					$columns = [
						1 => 'id',
						2 => 'username',
						3 => 'email',
						4 => 'display_name',
					];
					?>
					<?php
					foreach ( $columns as $i => $column ) {
						?>
                        <div class="form-check">
                            <input data-column="<?php
							echo $i;
							?>" class="form-check-input hideUserColumn"
                                   type="checkbox" value="<?php
							echo $i;
							?>" id="<?php
							echo $i . $column;
							?>">
                            <label class="form-check-label" for="<?php
							echo $i . $column;
							?>"
                                   style="margin-top: -25px;">
								<?php
								echo ucwords( str_replace( '_', ' ', $column ) );
								?>
                            </label>
                        </div>
						<?php
					}
					?>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title text-uppercase"><?php
						echo __( 'Instruction', 'send-users-email' );
						?></h5>
                    <p class="card-text"><?php
						echo __( 'Send email to individual users by selecting them from the user list.',
							'send-users-email' );
						?></p>
                </div>
            </div>

			<?php
			// Include placeholder instructions
			require plugin_dir_path( __FILE__ ) . 'templates/placeholder-instruction.php';
			?>

        </div>

		<?php
		require_once SEND_USERS_EMAIL_PLUGIN_BASE_PATH . '/partials/toast.php';
		?>

    </div>
</div>