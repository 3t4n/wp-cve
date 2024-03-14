<?php
if( !defined('ABSPATH') ) exit;
if( !class_exists('Stonehenge_Creations_Support') ) :
Class Stonehenge_Creations_Support {

	#===============================================
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		add_action('stonehenge_menu', array($this, 'add_submenu_page'), 96);
	}

	#===============================================
	public function add_submenu_page() {
		add_submenu_page(
			'stonehenge-creations',
			'Pro Support Tickets',
			'<span style="color:DeepSkyBlue;">Premium Support</span>',
			'manage_options',
			'stonehenge_support',
			array($this, 'show_this_page')
		);
	}

	#===============================================
	public function show_this_page() {
		stonehenge()->load_admin_assets();
		echo '<div class="wrap">';
		echo 	'<h1>Stonehenge Creations – Premium Support</h1>';

		$license = get_option($this->plugin['base'].'_license');
		if( @$license && @$license['license'] === 'valid' ) {
			$url = "https://www.stonehengecreations.nl/account/tickets/?k={$license['license_key']}";
			echo 	"<iframe src={$url} style='width:98%; max-width: 1024px; height: 600px; margin: 0 auto; display:block; border:1px solid MidnightBlue;'></iframe>";
		}
		echo '</div>';
	}

} // End class.
endif;
