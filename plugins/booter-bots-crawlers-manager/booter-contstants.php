<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'NO direct access!' );
}

define( 'BOOTER_SETTINGS_KEY', 'booter_settings' );
define( 'BOOTER_DIR', __DIR__ );
define( 'BOOTER_FILE', __DIR__ . '/booter-crawlers-manager.php' );
define( 'BOOTER_URL', plugin_dir_url( BOOTER_FILE ) );
define( 'BOOTER_BASEBANE', basename( BOOTER_FILE ) );
define( 'BOOTER_VERSION', '1.5.6' );
define( 'BOOTER_404_DB_TABLE', 'booter_404s' );
