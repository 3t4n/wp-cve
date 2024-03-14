<?php  
if( !defined( 'ABSPATH' ) ) exit;

function ishf_get_option_header_script()
{
	return wp_unslash(get_option('insert_header_script_gk'));
}
function ishf_get_option_body_script()
{
	return wp_unslash(get_option('insert_body_script_gk'));
}
function ishf_get_option_footer_script()
{
	return wp_unslash(get_option('insert_footer_script_gk'));
}

function ishf_failure_option_msg($msg)
{
	_e('<div class="notice notice-error ishf-error-msg is-dismissible"><p>' . $msg . '</p></div>','insert-script-in-headers-and-footers');	
}
function ishf_success_option_msg($msg)
{
	_e('<div class="notice notice-success ishf-success-msg is-dismissible"><p>'. $msg . '</p></div>','insert-script-in-headers-and-footers');
}

function ishf_output($setting){
	if ( !$setting ) {
		return;
	}
	
	$meta = get_option( $setting );
	if ( empty( $meta ) ) {
		return;
	}
	if ( trim( $meta ) == '' ) {
		return;
	}

	// Output
	
	_e(html_entity_decode(wp_unslash($meta)));
}