<?php

class EDS_Menu_Admin {
	
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'admin_notices' ), 99 );
		add_action('admin_head', array( $this, 'our_logo_icon' ));
		add_filter( 'plugin_action_links', array( $this, 'go_pro' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'welcome' ) );
		
	}
	public function welcome() {
		$activated = get_option( '_eds_Options', false );
		if ( !$activated ) {
			wp_safe_redirect('index.php?page=eds-responsive-menu&settings-updated=true');
		}
	}
	public function admin_notices() {
		if ( !isset( $_COOKIE['qa-pro-notice'] ) ) {
			echo '<div id="dwqa-message" class="notice is-dismissible"><p>To support this plugin and get more features, <a href="http://bit.ly/2bhaycd" target="_blank">upgrade to eDS Responsive Menu Pro &rarr;</a></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
		}
		
	}
	public function our_logo_icon() {
		echo '<style>
		#toplevel_page_eds-responsive-menu .dashicons-admin-generic:before{
		content:""!important;
		background:url('.EDS_MENU_URI.'/assets/img/logo.svg) no-repeat center center;	
		}
		#EDSramework_form .eds-element,.eds-field-notice .eds-notice{
			padding:15px;	
		}
		</style>';
	}
	
	public function go_pro( $actions, $file ) {
		if ( $file == EDS_MENU_FILE) {
			$actions['eds_go_pro'] = '<a href="http://bit.ly/2bhaycd" style="color: red; font-weight: bold">Go Pro!</a>';
			$action = $actions['eds_go_pro'];
			unset( $actions['eds_go_pro'] );
			array_unshift( $actions, $action );
		}
		return $actions;
	}


}

?>
