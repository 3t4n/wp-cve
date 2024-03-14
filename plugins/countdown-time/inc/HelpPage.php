<?php
class CTBHelpPage{
	public function __construct(){
		add_action( 'admin_menu', [$this, 'adminMenu'] );
		add_action( 'admin_enqueue_scripts', [$this, 'adminEnqueueScripts'] );
	}

	function adminMenu(){
		add_submenu_page(
			'edit.php?post_type=ctb',
			__( 'Countdown - Help', 'countdown-time' ),
			__( 'Help', 'countdown-time' ),
			'manage_options',
			'ctb-help',
			[$this, 'helpPage']
		);
	}

	function helpPage(){ ?>
		<div id='bplAdminHelpPage'></div>
	<?php }

	function adminEnqueueScripts( $hook ) {
		if( strpos( $hook, 'ctb-help' ) ){
			wp_enqueue_style( 'ctb-admin-help', CTB_DIR_URL . 'dist/admin-help.css', [], CTB_VERSION );
			wp_enqueue_script( 'ctb-admin-help', CTB_DIR_URL . 'dist/admin-help.js', [ 'react', 'react-dom' ], CTB_VERSION );
			wp_set_script_translations( 'ctb-admin-help', 'countdown-time', CTB_DIR_PATH . 'languages' );
		}
	}
}
new CTBHelpPage;