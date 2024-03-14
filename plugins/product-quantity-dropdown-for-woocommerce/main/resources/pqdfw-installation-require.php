<?php

/*Check PLugin Woocommerce Available or not*/
add_action('admin_init',  'PQDFW_check_plugin_state');
function PQDFW_check_plugin_state() {
  	if ( ! ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
    	set_transient( get_current_user_id() . 'pqdfwerror', 'message' );
  	}
}  		

/*Check PLugin Woocommerce not Available then show notice*/
add_action('admin_notices',  'PQDFW_show_notice');
function PQDFW_show_notice() {
    if ( get_transient( get_current_user_id() . 'pqdfwerror' ) ) {
      	deactivate_plugins( plugin_basename( PQDFW_PLUGIN_FILE ) );
      	delete_transient( get_current_user_id() . 'pqdfwerror' );
      	?>
      	<div class="error">
      		<p>
      			<?php 
      		 _e( 'This plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=woocommerce">WooCommerce</a> plugin installed and activated.','product-quantity-dropdown-for-woocommerce');
      		 ?>
      		 </p></div>
      	<?php 
    }
}
