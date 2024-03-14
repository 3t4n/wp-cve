<?php

namespace TotalContest\Admin\Plugins;

class UninstallFeedback {
	public function __construct() {
		add_action( 'pre_current_active_plugins', [ $this, 'row' ] );
		add_action( 'wp_ajax_uninstall_feedback_for_' . TotalContest( 'env' )->get( 'slug' ), [ $this, 'collect' ] );
	}

	public function row() {
		$product  = TotalContest( 'env' )->get( 'slug' );
		$basename = TotalContest( 'env' )->get( 'basename' );
		include 'views/uninstall-feedback.php';
	}

	public function collect() {
		if ( current_user_can( 'manage_options' ) && wp_verify_nonce(TotalContest( 'http.request' )->request( '_wpnonce' ), 'uninstall') ) {
			$feedback            = TotalContest( 'http.request' )->request( 'feedback' );
			$feedback['product'] = TotalContest( 'env' )->get( 'slug' );
			update_option( 'totalcontest_uninstall_feedback', $feedback );

			wp_remote_post( 'https://collect.totalsuite.net/uninstall', [
				'body'     => $feedback,
				'blocking' => false
			] );
		}

		wp_send_json_success();
	}
}
