<?php
/**
 * Lasso Lite General - Ajax.
 *
 * @package Pages
 */

namespace LassoLite\Pages;

use LassoLite\Admin\Constant;

use LassoLite\Classes\Affiliate_Link;
use LassoLite\Classes\Meta_Enum;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Setting;
use LassoLite\Classes\SURL;

/**
 * Lasso General - Ajax.
 */
class Ajax {
	/**
	 * Declare "Lasso Lite ajax requests" to WordPress.
	 */
	public function register_hooks() {
		add_action( 'wp_ajax_lasso_lite_add_a_new_link', array( $this, 'lasso_lite_add_a_new_link' ) );
		add_action( 'wp_ajax_lasso_lite_get_single', array( $this, 'lasso_lite_get_single' ) );
		add_action( 'wp_ajax_lasso_lite_get_shortcode_content', array( $this, 'lasso_lite_get_shortcode_content' ) );
		add_action( 'wp_ajax_lasso_lite_get_display_html', array( $this, 'lasso_lite_get_display_html' ) );
		add_action( 'wp_ajax_lasso_lite_get_link_quick_detail', array( $this, 'lasso_lite_get_link_quick_detail' ) );
		add_action( 'wp_ajax_lasso_lite_save_link_quick_detail', array( $this, 'lasso_lite_save_link_quick_detail' ) );
		add_action( 'wp_ajax_lasso_lite_get_setup_progress', array( $this, 'lasso_lite_get_setup_progress' ) );
		add_action( 'wp_ajax_lasso_lite_save_support', array( $this, 'lasso_lite_save_support' ) );
		add_action( 'wp_ajax_lasso_lite_review_snooze', array( $this, 'lasso_lite_review_snooze' ) );
		add_action( 'wp_ajax_lasso_lite_disable_review', array( $this, 'lasso_lite_disable_review' ) );
		add_action( 'wp_ajax_lasso_lite_disable_performance', array( $this, 'lasso_lite_disable_performance' ) );
		add_action( 'wp_ajax_lasso_lite_dismiss_notice', array( $this, 'lasso_lite_dismiss_notice' ) );
	}

	/**
	 * Add a new Lasso link
	 */
	public function lasso_lite_add_a_new_link() {
		Helper::verify_access_and_nonce( true );

		$lasso_lite_affiliate_link = new Affiliate_Link();
		return $lasso_lite_affiliate_link->add_a_new_link();
	}

	/**
	 * Get display html
	 */
	public function lasso_lite_get_single() {
		Helper::verify_access_and_nonce( true );

		// phpcs:ignore
		$post    = Helper::POST();
		$page    = intval( $post['page'] ) ?? 1;
		$limit   = intval( $post['limit'] ) ?? 5;
		$keyword = $post['keyword'] ?? '';
		$list    = SURL::get_list( $keyword, $page, $limit );
		$output  = array();

		foreach ( $list as $surl ) {
			$lasso_url = Affiliate_Link::get_lasso_url( $surl->get_id() );

			$output[] = array(
				'post_id'     => $lasso_url->id,
				'title'       => $lasso_url->name,
				'permalink'   => $lasso_url->permalink,
				'link_detail' => $lasso_url->edit_link,
				'img_src'     => $lasso_url->image_src,
				'redirect'    => $lasso_url->target_url,
				'slug'        => $lasso_url->slug,
			);
		}

		$data['output']        = $output;
		$data['total']         = SURL::total( $keyword );
		$data['limit_on_page'] = $limit;
		$data['page']          = $page;

		wp_send_json_success( $data );
	}

	/**
	 * Get display html
	 */
	public function lasso_lite_get_shortcode_content() {
		Helper::verify_access_and_nonce( true );

		$shortcode = stripslashes( Helper::GET()['shortcode'] ?? '' ); // phpcs:ignore
		$html      = '';

		if ( '' !== $shortcode ) {
			$html = do_shortcode( $shortcode );
		}

		wp_send_json_success(
			array(
				'shortcode' => $shortcode,
				'html'      => $html,
			)
		);
	}

	/**
	 * Get display html
	 */
	public function lasso_lite_get_display_html() {
		Helper::verify_access_and_nonce( true );
		$html = Helper::get_display_modal_html();

		wp_send_json_success(
			array(
				'html' => $html,
			)
		);
	}

	/**
	 * Get a new Lasso quick link
	 */
	public function lasso_lite_get_link_quick_detail() {
		Helper::verify_access_and_nonce( true );

		$lasso_id = Helper::POST()['post_id'] ?? null; // phpcs:ignore

		if ( ! empty( $lasso_id ) ) {
			$lasso_url = Affiliate_Link::get_lasso_url( $lasso_id, true );

			wp_send_json_success(
				array(
					'success'   => true,
					'lasso_url' => $lasso_url,
				)
			);
		} else {
			wp_send_json_error( 'No affiliate link to get.' );
		}
	}

	/**
	 * Save a lasso link with basic data
	 */
	public function lasso_lite_save_link_quick_detail() {
		Helper::verify_access_and_nonce( true );

		$data         = Helper::POST(); // phpcs:ignore
		$lasso_id     = $data['lasso_id'] ?? null; // phpcs:ignore
		$lasso_post   = get_post( $lasso_id );
		$thumbnail_id = intval( $data['thumbnail_id'] ?? 0 );

		if ( $lasso_post ) {
			$lasso_lite_post = array(
				'ID'         => $lasso_post->ID,
				'post_title' => trim( $data['affiliate_name'] ),
				'meta_input' => array(
					Meta_Enum::LASSO_LITE_CUSTOM_THUMBNAIL => trim( $data['thumbnail_image_url'] ?? '' ),
					Meta_Enum::BUY_BTN_TEXT                => trim( $data['buy_btn_text'] ?? '' ),
					Meta_Enum::DESCRIPTION                 => trim( $data['description'] ?? '' ),
					Meta_Enum::BADGE_TEXT                  => trim( $data['badge_text'] ?? '' ),
				),
			);

			wp_update_post( $lasso_lite_post );

			// ? update thumbnail
			if ( $thumbnail_id > 0 ) {
				set_post_thumbnail( $lasso_id, $thumbnail_id );
				$image_url = wp_get_attachment_url( $thumbnail_id );
				update_post_meta( $lasso_id, Meta_Enum::LASSO_LITE_CUSTOM_THUMBNAIL, $image_url );
			} else {
				delete_post_thumbnail( $lasso_id );
			}

			clean_post_cache( $lasso_id ); // ? clean post cache
			wp_send_json_success(
				array(
					'success' => true,
				)
			);
		} else {
			wp_send_json_success(
				array(
					'success' => false,
					'msg'     => 'No affiliate link existed.',
				)
			);
		}
	}

	/**
	 * Get setup progress information
	 */
	public function lasso_lite_get_setup_progress() {
		wp_send_json_success( Helper::get_setup_progress_information() );
	}

	/**
	 * Do save support to open intercom chat
	 */
	public function lasso_lite_save_support() {
		Helper::verify_access_and_nonce();

		Setting::save_support();
	}

	/**
	 * Do save support to open intercom chat
	 */
	public function lasso_lite_review_snooze() {
		Helper::verify_access_and_nonce();

		$link_count = SURL::total();

		Helper::update_option( Constant::LASSO_OPTION_REVIEW_SNOOZE, '1' );
		Helper::update_option( Constant::LASSO_OPTION_REVIEW_LINK_COUNT, $link_count );

		wp_send_json_success(
			array(
				'status' => 1,
			)
		);
	}

	/**
	 * Disable Review notification
	 */
	public function lasso_lite_disable_review() {
		Helper::verify_access_and_nonce();

		Helper::update_option( Constant::LASSO_OPTION_REVIEW_ALLOW, '0' );

		wp_send_json_success(
			array(
				'status' => 1,
			)
		);
	}

	/**
	 * Disable Performance notification
	 */
	public function lasso_lite_disable_performance() {
		Helper::verify_access_and_nonce();

		Helper::update_option( Constant::LASSO_OPTION_PERFORMANCE, '0' );

		wp_send_json_success(
			array(
				'status' => 1,
			)
		);
	}

	/**
	 * Dismiss Performance promotion in the dashboard
	 */
	public function lasso_lite_dismiss_notice() {
		Helper::verify_access_and_nonce();

		Helper::update_option( Constant::LASSO_OPTION_DISMISS_PERFORMANCE_NOTICE, '1' );

		wp_send_json_success(
			array(
				'status' => 1,
			)
		);
	}
}
