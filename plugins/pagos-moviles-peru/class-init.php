<?php

if ( ! defined('ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'wp_handle_upload' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
}

class PagoMovilesPeru
{
	private $wpdb;

	public function __construct($wpdb)
	{
		$this->wpdb = $wpdb;
		$this->init_version_check();
	}

	private function init_version_check()
	{
		if (version_compare(phpversion(), '5.4', '<') || version_compare(get_bloginfo('version'), '5.5', '<')) {
			die($this->old_php_error());
		} else {
			add_action('init', array($this, 'prepare_translation'));
			add_action('plugins_loaded', array($this, 'db_check'));
		}
	}

	function prepare_database()
	{
		$table_name = PAGO_MOVILES_PERU_DB_TABLE;

		$charset_collate = $this->wpdb->get_charset_collate();

		$query = "CREATE TABLE $table_name (
		  id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
		  qrcode LONGTEXT NOT NULL,
		  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY (id)
		) $charset_collate";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		dbDelta($query);

		add_option('pago_moviles_peru_version', PAGO_MOVILES_PERU_VERSION);
	}

	function old_php_error()
	{
		$message = sprintf(esc_html__('The %2$sPago Móviles Perú%3$s plugin requires %2$sPHP 5.4+%3$s and %2$sWordPress 5.5%3$s to run properly. Your current version of PHP is %2$s%1$s%3$s, and version of WP is %2$s%4$s%3$s', 'pagos-moviles-peru'), phpversion(), '<strong>', '</strong>', get_bloginfo('version'));
		return sprintf('<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post($message));
	}

	function db_check()
	{
		if (get_site_option('pago_moviles_peru_version') != PAGO_MOVILES_PERU_VERSION) {
			$this->prepare_database();
		}
	}

	function prepare_translation()
	{
		load_plugin_textdomain('pagos-moviles-peru', FALSE, PAGO_MOVILES_PERU_TEXT_DOMAIN);
	}
}

$plugin = new PagoMovilesPeru($wpdb);
register_activation_hook(PAGO_MOVILES_PERU_URI, array($plugin, 'prepare_database'));
            
add_action( 'wp_ajax_nopriv_upload_yape_capture', 'upload_yape_capture' );
add_action( 'wp_ajax_upload_yape_capture', 'upload_yape_capture' );

function upload_yape_capture() {
	$nonce = sanitize_text_field( $_POST['nonce'] );

	if ( ! wp_verify_nonce( $nonce, 'pago_moviles_peru_yape_nonce' ) ) {
		die ( 'Busted!');
	}
	
	$uploadedfile = $_FILES['file'];
	
	$allowed = array( "image/jpeg", "image/gif", "image/png" );	
	$filetype = wp_check_filetype( basename( $uploadedfile['name'] ), $allowed );

	if ( ! isset($filetype['ext'] ) ) {
		die ( "Only jpg, gif and png files are allowed." );
	}

	$movefile = wp_handle_upload( $uploadedfile, array( 'test_form' => false ) );

	$response = ( $movefile && !isset( $movefile['error'] ) ) ? $movefile : array( 'error' => $movefile['error'] );
	echo json_encode( $response );
	
	die();
}
