<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Divi_builder {


	public function __construct() {
		add_action( 'init', [ $this, 'remove_action' ] );
		add_filter( 'et_builder_enabled_builder_post_type_options', function ( $options ) {
			$options[ WFACP_Common::get_post_type_slug() ] = 'on';

			return $options;
		}, 999 );
		add_action( 'wfacp_template_removed', [ $this, 'remove_meta' ] );
		add_action( 'wfacp_update_page_design', [ $this, 'enable_divi_builder' ], 10, 2 );

	}

	public function remove_action() {
			if ( ( isset( $_GET['page'] ) ) && isset( $_GET['tab'] ) && ( $_GET['tab'] == 'wfacp-wizard' && $_GET['page'] == 'wfacp' ) ) {
				remove_action( 'admin_init', 'et_theme_builder_load_portability' );
			}
	}

	public function remove_meta( $wfacp_id ) {
		if (  $wfacp_id > 0 ) {
			global $wpdb;
			$wpdb->delete( $wpdb->postmeta, [ 'meta_key' => '_et_pb_use_builder', 'post_id' => $wfacp_id ] );
		}
	}



	public function enable_divi_builder( $aero_id, $data ) {

		if ( 'divi' != isset( $data['selected_type'] ) ) {
			return;
		}
		update_post_meta( $aero_id, '_et_pb_use_builder', 'on' );
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Divi_builder(), 'Divi_builder' );

