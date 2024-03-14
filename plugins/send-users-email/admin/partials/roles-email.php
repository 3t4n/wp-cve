<div class="container-fluid">
    <div class="row sue-row">

        <div class="col-sm-9">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title mb-4 text-uppercase text-center"><?php
						echo __( 'Send email to selected roles', 'send-users-email' );
						?></h5>


                    <form action="javascript:void(0)" id="sue-roles-email-form" method="post">

                        <div class="mb-4">
                            <label for="subject"
                                   class="form-label"><?php
								echo __( 'Email Subject', 'send-users-email' );
								?></label>
                            <input type="text" class="form-control subject" id="subject" name="subject" maxlength="200"
                                   placeholder="<?php
							       echo __( 'Email subject here', 'send-users-email' );
							       ?> <?php
							       ?>">
                        </div>

                        <div class="mb-4">
                            <div class="sue-role-email-list">
                                <label class="form-label"><?php
									echo __( 'Select Role(s)', 'send-users-email' );
									?></label>
                                <div class="row">
                                    <div class="col-md-6">
										<?php
										$roles_count = 0;
										?>
                                        <ul class="list-group">
											<?php
											foreach ( $roles as $slug => $user_count ) {
												?>
												<?php

												if ( $user_count ) {
													?>
													<?php

													if ( $roles_count % 2 == 0 ) {
														?>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <div class="form-check">
                                                                <input class="form-check-input roleCheckbox"
                                                                       name="roles[]"
                                                                       type="checkbox"
                                                                       value="<?php
																       echo esc_attr( $slug );
																       ?>"
                                                                       id="<?php
																       echo esc_attr( $slug );
																       ?>"
                                                                       style="margin-top: 7px;">
                                                                <label class="form-check-label"
                                                                       for="<?php
																       echo esc_attr( $slug );
																       ?>">
																	<?php
																	echo ucwords( str_replace( '_', ' ',
																		esc_html( $slug ) ) );
																	?>
                                                                </label>
                                                            </div>
                                                            <span class="badge bg-primary rounded-pill"><?php
																echo esc_html( $user_count );
																?></span>
                                                        </li>
														<?php
													}

													?>
													<?php
												}

												?>
												<?php
												$roles_count ++;
												?>
												<?php
											}
											?>
                                        </ul>
                                    </div>

                                    <div class="col-md-6">
										<?php
										$roles_count = 0;
										?>
                                        <ul class="list-group">
											<?php
											foreach ( $roles as $slug => $user_count ) {
												?>
												<?php

												if ( $user_count ) {
													?>
													<?php

													if ( $roles_count % 2 == 1 ) {
														?>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <div class="form-check">
                                                                <input class="form-check-input roleCheckbox"
                                                                       name="roles[]"
                                                                       type="checkbox"
                                                                       value="<?php
																       echo esc_attr( $slug );
																       ?>"
                                                                       id="<?php
																       echo esc_attr( $slug );
																       ?>"
                                                                       style="margin-top: 7px;">
                                                                <label class="form-check-label"
                                                                       for="<?php
																       echo esc_attr( $slug );
																       ?>">
																	<?php
																	echo ucwords( str_replace( '_', ' ',
																		esc_html( $slug ) ) );
																	?>
                                                                </label>
                                                            </div>
                                                            <span class="badge bg-primary rounded-pill"><?php
																echo esc_html( $user_count );
																?></span>
                                                        </li>

														<?php
													}

													?>
													<?php
												}

												?>
												<?php
												$roles_count ++;
												?>
												<?php
											}
											?>
                                        </ul>
                                    </div>
                                </div>

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
                                    <button type="submit" id="sue-roles-email-btn" class="btn btn-primary btn-block">
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
                    <h5 class="card-title text-uppercase"><?php
						echo __( 'Instruction', 'send-users-email' );
						?></h5>
                    <p class="card-text"><?php
						echo __( 'Send email to all users belonging to selected roles.', 'send-users-email' );
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