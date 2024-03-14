<?php
function notice_message($msg, $type) { //A simple success message
    if ($type == 'success') {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( $msg, 'ssl-fixer' ); ?></p>
        </div>
        <?php
    } else if ($type == 'error') {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e( $msg, 'ssl-fixer' ); ?></p>
        </div>
        <?php
    }
}

function activation_notice() {
    if (get_option('ssl_activation') != 'true') {
		if (get_transient('ssl_fixer_activation')) {
			notice_message( __( 'To enforce your SSL certificate please go to Plugins -> SSL Fixer -> Fix SSL button', 'ssl-fixer' ), 'success');
		}
		delete_transient('ssl_fixer_activation');
	}
}
?>