<?php

add_action( 'admin_init', 'cf7rfr_check_parent_plugin' );
function cf7rfr_check_parent_plugin() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
        add_action( 'admin_notices', 'cf7rfr_parent_not_active' );

        deactivate_plugins( plugin_basename( __FILE__ ) ); 

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}
function cf7rfr_parent_not_active(){
    ?>
	<div class="error">
		<p>Sorry, but <strong>Contact Form 7 - International Telephone Input</strong> requires <strong><a href="wordpress.org/plugins/contact-form-7/">Contact Form 7</a></strong>.</p>
	</div>
	<?php
}

?>