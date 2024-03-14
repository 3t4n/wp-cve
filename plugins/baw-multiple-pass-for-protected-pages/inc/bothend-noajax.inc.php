<?php
if( !defined( 'ABSPATH' ) )
	die( 'Cheatin\' uh?' );

add_action( 'init', 'bawmpp_init', 1 );
function bawmpp_init()
{
	global $bawmpp_options;
	load_plugin_textdomain( 'bawmpp', '', dirname( plugin_basename( BAWMPP__FILE__ ) ) . '/lang' );
	$bawmpp_args = array(	'no_admin'=>false,
							'no_author'=>false,
							'no_member'=>false,
							'clone_pass'=>false
			);
	$bawmpp_options = wp_parse_args( get_option( 'bawmpp' ), $bawmpp_args );
}