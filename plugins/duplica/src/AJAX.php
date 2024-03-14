<?php
namespace Codexpert\Duplica;

use Codexpert\Plugin\Base;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage AJAX
 * @author Codexpert <hi@codexpert.io>
 */
class AJAX extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $plugin['TextDomain'];
		$this->name		= $plugin['Name'];
		$this->version	= $plugin['Version'];
	}

	public function duplicate_post() {
		$response = [
			 'status'	=> 0,
			 'message'	=>__( 'Unauthorized!', 'duplica' )
		];

		if( ! wp_verify_nonce( $_POST['_wpnonce'] ) ) {
		    wp_send_json( $response );
		}

		$old_id		= sanitize_text_field( $_POST['post_id'] );
		$old_post	= get_post( $old_id );
		$new_post	= [];

		// post data
		foreach ( $old_post as $key => $value ) {
			if( $key == 'post_type' ) {
				$value = sanitize_text_field( $_POST['post_type'] );
			}

			if( $key == 'post_status' ) {
				$value = ( $status = Helper::get_option( 'duplica_basic', 'post_status', 'inherit' ) ) == 'inherit' ? $value : $status;
			}

			if( ! in_array( $key, [ 'ID', 'post_name' ] ) ) {
				$new_post[ $key ] = $value;
			}
		}

		// post meta
		foreach ( get_post_meta( $old_id ) as $key => $value ) {
			$new_post['meta_input'][ $key ] = $value[0];
		}

		// post terms
		foreach ( get_object_taxonomies( get_post_type( $old_id ) ) as $taxo ) {
			foreach ( wp_get_object_terms( $old_id, $taxo ) as $term ) {
				$new_post['tax_input'][ $taxo ][] = $term->term_id;
			}
		}

		// create the post
		$new_id = wp_insert_post( $new_post );

		/**
		 * Makes Elementor compatible
		 * 
		 * @since 0.5
		 */
		if( '' != get_post_meta( $old_id, '_elementor_page_assets', true ) ) {
			update_post_meta( $new_id, '_elementor_page_assets', [] );
		}

		$redirect = Helper::get_option( 'duplica_basic', 'redirection', 'off' );
		
		$redirect_url = false;
		if( $redirect == 'edit' ) {
			$redirect_url = get_edit_post_link( $new_id );
		}
		elseif( $redirect == 'view' ) {
			$redirect_url = get_permalink( $new_id );
		}

		wp_send_json( [ 'status' => 1, 'redirect' => $redirect_url ] );
	}

	public function duplicate_user() {
		$response = [
			 'status'	=> 0,
			 'message'	=>__( 'Unauthorized!', 'duplica' )
		];

		if( ! wp_verify_nonce( $_GET['_wpnonce'] ) ) {
		    wp_send_json( $response );
		}

		$user_id = $this->sanitize( $_GET['user'] );

		// fetch user data
		$userdata	= (array) get_userdata( $user_id )->data;
		$user_meta = wp_list_pluck( get_user_meta( $user_id ), '0' );

		// remove ID otherwise it'll update the user instead of creating
		unset( $userdata['ID'] );

		// change username and email to make them unique
		$userdata['user_login'] = $userdata['user_login'] . '-2';
		$userdata['user_email'] = str_replace( '@', '+2@', $userdata['user_email'] );

		// create new user
		$new_user_id = wp_insert_user( $userdata );

		// update meta data
		foreach ( $user_meta as $key => $value ) {
			$value = maybe_unserialize( $value );
			update_user_meta( $new_user_id, $key, $value );
		}

		$response['status'] = 1;
		$response['user_id'] = $new_user_id;
		$response['message'] = __( 'User has been duplicated!', 'duplica' );
		
		wp_send_json( $response );
	}
}