<?php 
add_action('easypixels_admintabs','jn_easypixels_admintabs');

function jn_easypixels_admintabs()
{
	?>
 <h2 class="nav-tab-wrapper">
     <a href="<?php echo admin_url( 'admin.php?page=easypixels' ); ?>" class="nav-tab<?php if ('easypixels' == $_GET['page'] ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'Basic tracking' ); ?></a>
     <a href="<?php echo esc_url( add_query_arg( array( 'action' => 'CF7' ), admin_url( 'admin.php?page=CF7easytracking' ) ) ); ?>" class="nav-tab<?php if ('CF7easytracking' == $_GET['page'] ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'Contact Form' ); ?></a> 
      <a href="<?php echo esc_url( add_query_arg( array( 'action' => 'Woocommerce' ), admin_url( 'admin.php?page=WCeasytracking' ) ) ); ?>" class="nav-tab<?php if ('WCeasytracking' == $_GET['page'] ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'Woocommerce' ); ?></a> 
 </h2>
 <?php
}