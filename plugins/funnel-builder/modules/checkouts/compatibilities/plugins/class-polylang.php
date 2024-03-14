<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties]
class WFACP_Compatibility_With_Polylang {

	public function __construct() {

		add_action( 'wp_ajax_wfacp_add_pll_language', [ $this, 'add_pll_language' ] );
		add_filter( 'wfacp_wpml_checkout_page_id', [ $this, 'map_language_checkout' ] );

	}

	public function add_pll_language() {
		WFACP_AJAX_Controller::check_nonce();
		$resp = array( 'status' => false );
		global $polylang;

		if ( empty( $polylang ) || ! class_exists( 'PLL_Admin_Sync' ) ) {
			WFACP_AJAX_Controller::send_resp( $resp );
		}

		$from_post_id = $_POST['from_post_id'];

		$sync = new PLL_Admin_Sync( $polylang );

		if ( 0 === absint( $from_post_id ) || ! isset( $_POST['new_lang'] ) ) {
			WFACP_AJAX_Controller::send_resp( $resp );
		}
		$from_post = get_post( $from_post_id );
		$arr       = [
			'post_title' => $from_post->post_title . '_' . $_POST['new_lang'],
			'post_type'  => 'wfacp_checkout',
			'post_name'  => $from_post->post_name,
		];
		$post_id   = wp_insert_post( $arr );

		$lang = $polylang->model->get_language( sanitize_key( $_POST['new_lang'] ) );

		$sync->taxonomies->copy( $from_post_id, $post_id, $lang->slug );
		$sync->post_metas->copy( $from_post_id, $post_id, $lang->slug );
		pll_current_language();
		if ( is_sticky( $from_post_id ) ) {
			stick_post( $post_id );
		}

		$polylang->model->post->set_language( $post_id, $lang );
		$resp['language_added']      = true;
		$translations                = $polylang->model->post->get_translations( $from_post_id );
		$translations[ $lang->slug ] = $post_id;

		WFACP_Common::get_duplicate_data( $post_id, $from_post_id );
		pll_save_post_translations( $translations );
		WFACP_AJAX_Controller::send_resp( $resp );
	}

	public function map_language_checkout( $global_checkout_page_id ) {
		if ( ! function_exists( 'pll_get_post' ) ) {
			return $global_checkout_page_id;
		}
		try {
			$temp_id = pll_get_post( $global_checkout_page_id );
			WFACP_Common::remove_actions( 'template_redirect', 'PLL_Canonical', 'check_canonical_url' );
			if ( false !== $temp_id && $temp_id > 0 ) {
				$global_checkout_page_id = $temp_id;
			}
		} catch ( Exception $e ) {

		}

		return $global_checkout_page_id;
	}


}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Polylang(), 'wfacp_pll' );
