<?php do_action( 'wphr_email_header', $email_heading ); ?>

<?php echo apply_filters( 'wphr_email_body', $email_body ); ?>

<?php do_action( 'wphr_email_footer' ); ?>
