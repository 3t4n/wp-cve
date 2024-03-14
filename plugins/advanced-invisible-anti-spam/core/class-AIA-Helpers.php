<?php
/**
 * =======================================
 * Advanced Invisible AntiSpam Helpers
 * =======================================
 * 
 * 
 * @author Matt Keys <matt@mattkeys.me>
 */

if ( ! defined( 'AIA_PLUGIN_FILE' ) ) {
	die();
}

class AIA_Helpers
{
	private $key_name;

	public function init()
	{
		add_action( 'wp_ajax_nopriv_aia_field_update', array( $this, 'generate_key_value' ) );
		add_action( 'wp_ajax_aia_field_update', array( $this, 'generate_key_value' ) );
		add_action( 'update_aia_key', array( $this, 'update_key_name' ) );

		$this->key_name = self::get_key_name();

		if ( ! wp_next_scheduled ( 'update_aia_key' )) {
			wp_schedule_event( time(), 'hourly', 'update_aia_key' );
		}
	}

	public function generate_key_value()
	{
		echo json_encode( array(
			'field'	=> $this->key_name,
			'value'	=> wp_create_nonce( 'aia_antispam_' . $this->key_name )
		));

		exit;
	}

	static public function get_key_name()
	{
		$field_key = get_option('aia_current_key');

		if ( ! $field_key ) {
			return self::create_key_name();
		}

		return $field_key;
	}

	static public function create_key_name()
	{
		$field_key = wp_generate_password( 12, false );
		update_option( 'aia_current_key', $field_key );

		return $field_key;
	}

	public function update_key_name()
	{
		$old_key = get_option('aia_current_key');
		update_option( 'aia_previous_field_key', $old_key );

		$new_key = wp_generate_password( 12, false );
		update_option( 'aia_current_key', $new_key );
	}

	static public function cleanup()
	{
		wp_clear_scheduled_hook('update_aia_key');
		delete_option('aia_current_key');
		delete_option('aia_previous_field_key');
	}

}

add_action(	'plugins_loaded', array( new AIA_Helpers, 'init' ) );

register_activation_hook( AIA_PLUGIN_FILE, array( 'AIA_Helpers', 'create_key_name' ) );
register_deactivation_hook( AIA_PLUGIN_FILE, array( 'AIA_Helpers', 'cleanup' ) );
