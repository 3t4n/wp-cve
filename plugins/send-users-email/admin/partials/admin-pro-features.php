<div class="container-fluid">
    <div class="row">

        <div class="col-md-12">
            <h2 class="text-center pt-3 pb-3 display-7 text-uppercase"><?php
				echo __( 'Feature of PRO version', 'send-users-email' );
				?></h2>
        </div>

        <div class="col-sm-4">
            <div class="card shadow">
                <img src="<?php
				echo sue_get_asset_url( 'queue.png' );
				?>" class="card-img-top" alt="Queue">
                <div class="card-body" style="text-align: justify;">
                    <h5 class="card-title text-uppercase"><?php
						echo __( 'Queue System', 'send-users-email' );
						?></h5>
                    <p class="card-text"><?php
						echo __( 'Having trouble with your email service provider due to reaching your daily or monthly limits?',
							'send-users-email' );
						?></p>
                    <p class="card-text"><?php
						echo __( 'Queue system of this plugin will send specified amount of email regularly so that you do not hit that limit.',
							'send-users-email' );
						?></p>
                    <p class="card-text"><?php
						echo __( 'For example: If your hosting/email provider only allows 300 outgoing emails per day, but you are about to send 900 emails, you can configure plugin is such a way to send only 300 email per day staying below the said limit.',
							'send-users-email' );
						?></p>
                    <p class="card-text"><?php
						echo __( 'These emails will be sent periodically using WordPress cron automatically. You will just have to send 900 emails once and plugin will take care of sending these emails periodically.',
							'send-users-email' );
						?></p>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card shadow">
                <img src="<?php
				echo sue_get_asset_url( 'template.png' );
				?>" class="card-img-top"
                     alt="Template">
                <div class="card-body" style="text-align: justify;">
                    <h5 class="card-title text-uppercase"><?php
						echo __( 'Email Template', 'send-users-email' );
						?></h5>
                    <p class="card-text"><?php
						echo __( 'Are you sending same emails content over and over again?', 'send-users-email' );
						?></p>
                    <p class="card-text"><?php
						echo __( 'Are you tired of typing same email repeatedly?', 'send-users-email' );
						?></p>
                    <p class="card-text"><?php
						echo __( 'Pro version of Send Users Email allows you to save email template and reuse it while sending emails to your user.',
							'send-users-email' );
						?></p>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card shadow">
                <img src="<?php
				echo sue_get_asset_url( 'queue.png' );
				?>" class="card-img-top" alt="Queue">
                <div class="card-body" style="text-align: justify;">
                    <h5 class="card-title text-uppercase"><?php
						echo __( 'User Group', 'send-users-email' );
						?></h5>
                    <p class="card-text"><?php
						echo __( 'Choosing individual users or sending email to role is not cutting it for you?',
							'send-users-email' );
						?></p>
                    <p class="card-text"><?php
						echo __( 'Plugin support creation of User group.', 'send-users-email' );
						?></p>
                    <p class="card-text"><?php
						echo __( 'Easily add users to a group and send emails to all users in the group at once.',
							'send-users-email' );
						?></p>
                    <p class="card-text"><?php
						echo __( 'You can use queue system while send emails to group as well.', 'send-users-email' );
						?></p>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card shadow">
                <img src="<?php
				echo sue_get_asset_url( 'email_style.png' );
				?>" class="card-img-top"
                     alt="Template">
                <div class="card-body" style="text-align: justify;">
                    <h5 class="card-title text-uppercase"><?php
						echo __( 'Email Styles', 'send-users-email' );
						?></h5>
                    <p class="card-text"><?php
						echo __( 'Having trouble crafting descent looking email style?', 'send-users-email' );
						?></p>
                    <p class="card-text"><?php
						echo __( 'Send Users Email provides you with an option to use prebuild email style.',
							'send-users-email' );
						?></p>
                    <p class="card-text"><?php
						echo __( 'Pro version of Send Users Email provides email style that is compatible with various screen sizes and you can choose different color scheme as per your need.',
							'send-users-email' );
						?></p>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card shadow">
                <img src="<?php
				echo sue_get_asset_url( 'placeholder.png' );
				?>" class="card-img-top"
                     alt="Placeholder">
                <div class="card-body" style="text-align: justify;">
                    <h5 class="card-title text-uppercase"><?php
						echo __( 'Other features', 'send-users-email' );
						?></h5>
                    <p class="card-text"><?php
						echo __( 'Pro version of Send Users Email provides some more minor feature upgrade to make your life a bit more easier.',
							'send-users-email' );
						?></p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><?php
							echo __( 'Use placeholder on email subjects for more personal touch to email you send.',
								'send-users-email' );
							?></li>
                        <li class="list-group-item"><?php
							echo __( 'Ability to send queued email at later date. Schedule email to be send in the future.',
								'send-users-email' );
							?></li>
                        <li class="list-group-item"><?php
							echo __( 'A clutter-free plugin area so that you can focus on things that matter to you.',
								'send-users-email' );
							?></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-sm-12 my-5 text-center">
            <a class="btn btn-outline-success btn-lg" href="<?php
			echo sue_fs()->get_upgrade_url();
			?>"
               role="button"><?php
				echo __( 'Upgrade to PRO', 'send-users-email' );
				?></a>
        </div>

    </div>

</div>