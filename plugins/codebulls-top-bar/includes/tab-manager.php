<?php 
add_action( 'manage_top_bar_settings_tabs', 'cb_top_bar_manage_top_bar_settings_tabs', 1 );
/**
 * Method that add the tabs per our plugin options page
 */
function cb_top_bar_manage_top_bar_settings_tabs(){
	global $cb_top_bar_active_tab; ?>
	<a class="nav-tab <?php echo $cb_top_bar_active_tab == 'general' || '' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'options-general.php?page=cb_top_bar&tab=general' ); ?>"><?php _e( 'General', 'cb_top_bar' ); ?> </a>
	<a class="nav-tab <?php echo $cb_top_bar_active_tab == 'content' || '' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'options-general.php?page=cb_top_bar&tab=content' ); ?>"><?php _e( 'Content', 'cb_top_bar' ); ?> </a>
	<?php
}
?>