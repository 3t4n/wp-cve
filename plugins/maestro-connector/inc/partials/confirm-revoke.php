<p class="thin"><?php esc_html_e( 'Are you sure you would like to revoke access for this Web Pro?', 'maestro-connector' ); ?></p>
<div class="details bold">
	<div class='name'><span><?php esc_html_e( 'Name', 'maestro-connector' ); ?>:</span> <span><?php echo esc_html( $webpro->user->first_name ); ?> <?php echo esc_html( $webpro->user->last_name ); ?></span></div>
	<div class='email'><span><?php esc_html_e( 'Email', 'maestro-connector' ); ?>:</span> <span><?php echo esc_html( $webpro->user->user_email ); ?></span></div>
</div>
<div class="buttons">
	<a href="<?php echo esc_url( $cancel_url ); ?>" class="maestro-button secondary"><?php esc_html_e( 'No, leave them', 'maestro-connector' ); ?></a>
	<a href="<?php echo esc_url( $confirm_url ); ?>" class="maestro-button primary"><?php esc_html_e( 'Yes, revoke access', 'maestro-connector' ); ?></a>
</div>
