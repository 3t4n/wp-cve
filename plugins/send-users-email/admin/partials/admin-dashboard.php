<div class="container-fluid">
    <div class="row sue-row sue-dashboard">

        <div class="col-sm-9">


            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title text-uppercase"><?php
						echo __( 'Dashboard', 'send-users-email' );
						?></h5>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h5 class="card-title text-uppercase"><?php
										echo __( 'Total Users', 'send-users-email' );
										?></h5>
                                    <h3 class="card-text badge bg-success"><?php
										echo esc_html( $users['total_users'] );
										?></h3>
                                </div>
                            </div>
                        </div>

						<?php
						foreach ( $users['avail_roles'] as $role => $total ) {
							?>
							<?php

							if ( $total > 0 ) {
								?>
                                <div class="col-sm-4">
                                    <div class="card shadow">
                                        <div class="card-body">
                                            <h5 class="card-title text-uppercase"><?php
												echo ucfirst( str_replace( '_', '', $role ) );
												?></h5>
                                            <h3 class="card-text badge bg-primary"><?php
												echo esc_html( $total );
												?></h3>
                                        </div>
                                    </div>
                                </div>
								<?php
							}

							?>
							<?php
						}
						?>


                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-11"></div>
                <div class="col-sm-1">
                    <div class="alert text-center" role="alert">
                        <a href="https://www.facebook.com/profile.php?id=100092426149421" target="_blank">
                            <img src="<?php
							echo sue_get_asset_url( 'facebook.png' );
							?>" alt="FB" width="25">
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-sm-3">

			<?php
			require_once SEND_USERS_EMAIL_PLUGIN_BASE_PATH . '/partials/donate.php';
			?>

			<?php

			if ( rand( 1, 3 ) == 1 ) {
				?>
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase"><?php
							echo __( 'About', 'send-users-email' );
							?></h5>
                        <p class="card-text"><?php
							echo __( 'Send email to users by selecting individual users or bulk send emails using roles.',
								'send-users-email' );
							?></p>
                    </div>
                </div>
				<?php
			}

			?>

            <div class="card shadow">
                <div class="card-body">
                    <div class="alert alert-warning shadow" role="alert">
                        <h5 class="text-uppercase mb-4"><?php
							echo __( "Please don't SPAM", 'send-users-email' );
							?></h5>
                        <p><?php
							echo __( "You don't like spam, I don't like spam, nobody likes spam.", 'send-users-email' );
							?></p>
                        <p><?php
							echo __( "Please be responsible and don't spam your users.", 'send-users-email' );
							?></p>
                        <p>
                            <strong><?php
								echo __( "With great power comes great responsibility.", 'send-users-email' );
								?></strong>
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>