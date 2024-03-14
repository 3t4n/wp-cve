<?php
class CSBUpgradePage{
	public function __construct(){
		add_action( 'admin_menu', [$this, 'adminMenu'] );
		add_action( 'admin_enqueue_scripts', [$this, 'adminEnqueueScripts'] );
	}

	function adminMenu(){
		add_submenu_page(
			'edit.php?post_type=csb',
			__( 'Content Slider - Upgrade', 'content-slider-block' ),
			__( 'Upgrade', 'content-slider-block' ),
			'manage_options',
			'csb-upgrade',
			[$this, 'upgradePage']
		);
	}

	function upgradePage(){ ?>
		<div id='bplUpgradePage'></div>
	<?php }

	function adminEnqueueScripts( $hook ) {
		if( strpos( $hook, 'csb-upgrade' ) ){
			wp_enqueue_script( 'csb-admin-upgrade', CSB_DIR_URL . 'dist/admin-upgrade.js', [ 'react', 'react-dom' ], CSB_VERSION );
			wp_set_script_translations( 'csb-admin-upgrade', 'content-slider-block', CSB_DIR_PATH . 'languages' );
		}
	}
}
new CSBUpgradePage;