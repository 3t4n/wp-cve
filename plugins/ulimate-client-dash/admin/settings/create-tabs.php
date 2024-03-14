<?php


// Creates Dashboard Styling Tab
add_action( 'ucd_settings_tab', 'ucd_dashboard_tab', 1 );
function ucd_dashboard_tab(){
		global $ucd_active_tab; ?>
		<a class="ucd-nav-tab <?php echo $ucd_active_tab == 'dashboard-settings' || '' ? 'ucd-nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=ultimate-client-dash&tab=dashboard-settings' ); ?>"><?php _e( 'Dashboard', 'ultiamte-client-dash' ); ?> </a>
		<?php
}

// Creates Login Styling Tab
add_action( 'ucd_settings_tab', 'ucd_login_tab', 2 );
function ucd_login_tab(){
		global $ucd_active_tab; ?>
		<a class="ucd-nav-tab <?php echo $ucd_active_tab == 'login-settings' ? 'ucd-nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=ultimate-client-dash&tab=login-settings' ); ?>"><?php _e( 'Login Page', 'ultiamte-client-dash' ); ?> </a>
		<?php
}

// Creates Welcome Message Options Tab
add_action( 'ucd_settings_tab', 'ucd_message_tab', 3 );
function ucd_message_tab(){
		global $ucd_active_tab; ?>
		<a class="ucd-nav-tab <?php echo $ucd_active_tab == 'welcome-message' ? 'ucd-nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=ultimate-client-dash&tab=welcome-message' ); ?>"><?php _e( 'Welcome Message', 'ultiamte-client-dash' ); ?> </a>
		<?php
}

// Creates Client Access Tab
add_action( 'ucd_settings_tab', 'ucd_menu_items_tab', 4 );
function ucd_menu_items_tab(){
		global $ucd_active_tab; ?>
		<a class="ucd-nav-tab <?php echo $ucd_active_tab == 'menu-items' ? 'ucd-nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=ultimate-client-dash&tab=menu-items' ); ?>"><?php _e( 'Menu', 'ultiamte-client-dash' ); ?> </a>
		<?php
}

// Creates Widget Options Tab
add_action( 'ucd_settings_tab', 'ucd_widget_tab', 5 );
function ucd_widget_tab(){
		global $ucd_active_tab; ?>
		<a class="ucd-nav-tab <?php echo $ucd_active_tab == 'widget-settings' ? 'ucd-nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=ultimate-client-dash&tab=widget-settings' ); ?>"><?php _e( 'Widgets', 'ultiamte-client-dash' ); ?> </a>
		<?php
}

// Creates Landing Page Tab
add_action( 'ucd_settings_tab', 'ucd_under_construction_tab', 6 );
function ucd_under_construction_tab(){
		global $ucd_active_tab; ?>
		<a class="ucd-nav-tab <?php echo $ucd_active_tab == 'landing-page' ? 'ucd-nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=ultimate-client-dash&tab=landing-page' ); ?>"><?php _e( 'Landing Page', 'ultiamte-client-dash' ); ?> </a>
		<?php
}

// Creates Client Access Tab
add_action( 'ucd_settings_tab', 'ucd_client_tab', 7 );
function ucd_client_tab(){
		global $ucd_active_tab; ?>
		<a class="ucd-nav-tab <?php echo $ucd_active_tab == 'client-access' ? 'ucd-nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=ultimate-client-dash&tab=client-access' ); ?>"><?php _e( 'Client Access', 'ultiamte-client-dash' ); ?> </a>
		<?php
}

// Creates Analytics Options Tab
add_action( 'ucd_settings_tab', 'ucd_tracking_tab', 8 );
function ucd_tracking_tab(){
		global $ucd_active_tab; ?>
		<a class="ucd-nav-tab <?php echo $ucd_active_tab == 'tracking-and-custom-code' ? 'ucd-nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=ultimate-client-dash&tab=tracking-and-custom-code' ); ?>"><?php _e( 'Tracking/Custom Code', 'ultiamte-client-dash' ); ?> </a>
		<?php
}

// Creates Misc Options Tab
add_action( 'ucd_settings_tab', 'ucd_misc_tab', 9 );
function ucd_misc_tab(){
		global $ucd_active_tab; ?>
		<a class="ucd-nav-tab <?php echo $ucd_active_tab == 'misc' ? 'ucd-nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=ultimate-client-dash&tab=misc' ); ?>"><?php _e( 'Misc', 'ultiamte-client-dash' ); ?> </a>
		<?php
}

// Creates Shortcodes Options Tab
add_action( 'ucd_settings_tab', 'ucd_shortcodes_tab', 10 );
function ucd_shortcodes_tab(){
		global $ucd_active_tab; ?>
		<a class="ucd-nav-tab <?php echo $ucd_active_tab == 'shortcodes' ? 'ucd-nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=ultimate-client-dash&tab=shortcodes' ); ?>"><?php _e( 'Shortcodes', 'ultiamte-client-dash' ); ?> </a>
		<?php
}

// Creates Upgrade Options tab
add_action( 'ucd_settings_tab', 'ucd_upgrade_tab', 11 );
function ucd_upgrade_tab(){
    if (!is_plugin_active('ultimate-client-dash-pro/ultimate-client-dash-pro.php') ) {
		    global $ucd_active_tab; ?>
        <a class="ucd-nav-tab <?php echo $ucd_active_tab == 'upgrade' ? 'ucd-nav-tab-active' : ''; ?> ucd-upgrade-button" href="<?php echo admin_url( 'admin.php?page=ultimate-client-dash&tab=upgrade' ); ?>"><?php _e( 'Buy Pro Version', 'ultiamte-client-dash' ); ?> </a>
<?php }
}
