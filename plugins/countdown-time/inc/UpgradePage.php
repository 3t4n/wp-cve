<?php
class CTBUpgradePage{
	public function __construct(){
		add_action( 'admin_menu', [$this, 'adminMenu'] );
		add_action( 'admin_enqueue_scripts', [$this, 'adminEnqueueScripts'] );
	}

	function adminMenu(){
		add_submenu_page(
			'edit.php?post_type=ctb',
			__( 'Countdown - Upgrade', 'countdown-time' ),
			__( 'Upgrade', 'countdown-time' ),
			'manage_options',
			'ctb-upgrade',
			[$this, 'upgradePage']
		);
	}

	function upgradePage(){ ?>
		<div id='bplUpgradePage'></div>
	<?php }

	function adminEnqueueScripts( $hook ) {
		if( strpos( $hook, 'ctb-upgrade' ) ){
			wp_enqueue_script( 'ctb-admin-upgrade', CTB_DIR_URL . 'dist/admin-upgrade.js', [ 'react', 'react-dom' ], CTB_VERSION );
			wp_set_script_translations( 'ctb-admin-upgrade', 'countdown-time', CTB_DIR_PATH . 'languages' );
		}
	}
}
new CTBUpgradePage;