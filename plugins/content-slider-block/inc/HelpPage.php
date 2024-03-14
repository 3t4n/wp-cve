<?php
class CSBHelpPage{
	public function __construct(){
		add_action( 'admin_menu', [$this, 'adminMenu'] );
		add_action( 'admin_enqueue_scripts', [$this, 'adminEnqueueScripts'] );
	}

	function adminMenu(){
		add_submenu_page(
			'edit.php?post_type=csb',
			__( 'Content Slider - Help', 'content-slider-block' ),
			__( 'Help', 'content-slider-block' ),
			'manage_options',
			'csb-help',
			[$this, 'helpPage']
		);
	}

	function helpPage(){ ?>
		<div id='bplAdminHelpPage'></div>
	<?php }

	function adminEnqueueScripts( $hook ) {
		if( strpos( $hook, 'csb-help' ) ){
			wp_enqueue_style( 'csb-admin-help', CSB_DIR_URL . 'dist/admin-help.css', [], CSB_VERSION );
			wp_enqueue_script( 'csb-admin-help', CSB_DIR_URL . 'dist/admin-help.js', [ 'react', 'react-dom' ], CSB_VERSION );
			wp_set_script_translations( 'csb-admin-help', 'content-slider-block', CSB_DIR_PATH . 'languages' );
		}
	}
}
new CSBHelpPage;