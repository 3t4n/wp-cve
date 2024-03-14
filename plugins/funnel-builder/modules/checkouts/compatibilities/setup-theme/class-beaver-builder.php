<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Beaver_builder {
	public function __construct() {
		add_filter( 'fl_builder_post_types', function ( $post_types ) {
			array_push( $post_types, WFACP_Common::get_post_type_slug() );

			return $post_types;
		}, 999 );

		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
		add_action( 'wfacp_duplicate_pages', [ $this, 'beaver_duplicate_data' ], 10, 2 );
	}

	public function action() {
		$is_global_checkout = WFACP_Core()->public->is_checkout_override();
		if ( $is_global_checkout == true ) {

			add_filter( 'fl_builder_global_posts', function ( $arr ) {
				$id    = WFACP_Common::get_id();
				$arr[] = $id;

				return $arr;
			} );
		}

	}

	public function beaver_duplicate_data( $new_post_id, $post_id ) {
		$meta = get_post_meta( $post_id );
		foreach ( $meta as $key => $value ) {
			if ( false !== strpos( $key, '_fl_builder' ) ) {
				$meta_data = maybe_unserialize( $value[0] );
				update_post_meta( $new_post_id, $key, $meta_data );
			}

		}

	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Beaver_builder(), 'Beaver_builder' );
