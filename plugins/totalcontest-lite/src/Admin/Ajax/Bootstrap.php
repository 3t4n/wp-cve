<?php

namespace TotalContest\Admin\Ajax;

use TotalContestVendors\TotalCore\Helpers\Tracking;

/**
 * Class Bootstrap
 *
 * @package TotalContest\Admin\Ajax
 */
class Bootstrap {
	/**
	 * Bootstrap constructor.
	 */
	public function __construct() {
		// Checking nonce for all AJAX actions
		if ( wp_doing_ajax() ):
			$ajaxAction = (string) TotalContest( 'http.request' )->request( 'action' );
			if ( strstr( $ajaxAction, 'totalcontest_' ) !== false ):
				$nonce = TotalContest( 'http.request' )->request( '_wpnonce' );

				if ( ! wp_verify_nonce( $nonce, 'totalcontest' ) ):
					wp_send_json_error( [ 'message' => __( 'Nonce check failed.', 'totalcontest' ) ], 401 );
					exit;
				endif;
			endif;
		endif;

		if ( current_user_can( 'manage_options' ) ):

			/**
			 * @action wp_ajax_totalcontest_nps
			 * @since  4.0.0
			 */
			add_action( 'wp_ajax_totalcontest_nps', function () {
				if ( current_user_can( 'manage_options' ) ) {
					$nps            = TotalContest( 'http.request' )->request( 'nps' );
					$nps['product'] = TotalContest( 'env' )->get( 'slug' );
					$nps['uid']     = TotalContest()->uid();
					update_option( 'totalcontest_nps', $nps );

					wp_remote_post( 'https://collect.totalsuite.net/nps', [
						'body'     => $nps,
						'blocking' => false
					] );
				}

				wp_send_json_success();
			} );

			

            /**
             * @action wp_ajax_totalcontest_onboarding
             * @since  4.0.0
             */
            add_action( 'wp_ajax_totalcontest_onboarding', function () {
                if ( current_user_can( 'manage_options' ) ) {

                    $onboarding['product'] = TotalContest( 'env' )->get( 'slug' );
                    $onboarding['uid']     = TotalContest()->uid();
                    $onboarding['date']    = date( DATE_ATOM );
                    $onboarding['data']    = TotalContest( 'http.request' )->request( 'onboarding' );

                    update_option( 'totalcontest_onboarding', $onboarding['data'] );

                    wp_remote_post( 'https://collect.totalsuite.net/onboarding', [
                        'body'     => $onboarding,
                        'blocking' => false
                    ] );
                }

                wp_send_json_success();
            } );

			/**
			 * @action wp_ajax_totalcontest_dashboard_contests_overview
			 * @since  2.0.0
			 */
			add_action( 'wp_ajax_totalcontest_dashboard_contests_overview', function () {
				TotalContest( 'admin.ajax.dashboard' )->contests();
			} );

			/**
			 * @action wp_ajax_totalcontest_blog_feed
			 * @since  2.1.2
			 */
			add_action( 'wp_ajax_totalcontest_dashboard_blog_feed', function () {
				TotalContest( 'admin.ajax.dashboard' )->blog();
			} );

			// Log
			add_action( 'wp_ajax_totalcontest_log_list', function () {
				TotalContest( 'admin.ajax.log' )->fetch();
			} );

			add_action( 'wp_ajax_totalcontest_log_download', function () {
				TotalContest( 'admin.ajax.log' )->download();
			} );

			add_action( 'wp_ajax_totalcontest_log_export', function () {
				TotalContest( 'admin.ajax.log' )->export();
			} );

			add_action( 'wp_ajax_totalcontest_log_export_status', function () {
				TotalContest( 'admin.ajax.log' )->exportStatus();
			} );

            add_action( 'wp_ajax_totalcontest_log_remove', function () {
                TotalContest( 'admin.ajax.log' )->remove();
            } );

			// Modules
			add_action( 'wp_ajax_totalcontest_modules_install_from_file', function () {
				TotalContest( 'admin.ajax.modules' )->installFromFile();
			} );
			add_action( 'wp_ajax_totalcontest_modules_install_from_store', function () {
				TotalContest( 'admin.ajax.modules' )->installFromStore();
			} );
			add_action( 'wp_ajax_totalcontest_modules_list', function () {
				TotalContest( 'admin.ajax.modules' )->fetch();
			} );
			add_action( 'wp_ajax_totalcontest_modules_update', function () {
				TotalContest( 'admin.ajax.modules' )->update();
			} );
			add_action( 'wp_ajax_totalcontest_modules_uninstall', function () {
				TotalContest( 'admin.ajax.modules' )->uninstall();
			} );
			add_action( 'wp_ajax_totalcontest_modules_activate', function () {
				TotalContest( 'admin.ajax.modules' )->activate();
			} );
			add_action( 'wp_ajax_totalcontest_modules_deactivate', function () {
				TotalContest( 'admin.ajax.modules' )->deactivate();
			} );

			// Options
			add_action( 'wp_ajax_totalcontest_options_save_options', function () {
				TotalContest( 'admin.ajax.options' )->saveOptions();
			} );
			add_action( 'wp_ajax_totalcontest_options_purge', function () {
				TotalContest( 'admin.ajax.options' )->purge();
			} );

            /**
             * @action wp_ajax_totalcontest_tracking
             * @since  4.0.0
             */
            add_action( 'wp_ajax_totalcontest_tracking_features', function () {
                $action = TotalContest( 'http.request' )->request( 'event' );
                $target = TotalContest( 'http.request' )->request( 'target' );

                wp_send_json_success();
            } );

            /**
             * @action wp_ajax_totalcontest_tracking
             * @since  4.0.0
             */
            add_action( 'wp_ajax_totalcontest_tracking_screens', function () {

                $key = TotalContest( 'env' )->get( 'tracking-key', 'totalcontest_tracking' );

                $tracking = (array) get_option( $key, [
                    'screens'  => [],
                    'features' => []
                ] );


                $tracking['screens'][] = [
                    'screen' => TotalContest( 'http.request' )->request( 'label' ),
                    'date'   => date( DATE_ATOM )
                ];

                update_option( $key, $tracking );

                wp_send_json_success();
            } );
			
		endif;

		if ( current_user_can( 'edit_contests' ) ):
			// ------------------------------
			// Contests
			// ------------------------------
			/**
			 * @action wp_ajax_totalcontest_contests_add_to_sidebar
			 * @since  2.0.0
			 */
			add_action( 'wp_ajax_totalcontest_contests_add_to_sidebar', function () {
				TotalContest( 'admin.ajax.contests' )->addToSidebar();
			} );
			/**
			 * @action wp_ajax_totalcontest_contests_get_categories
			 * @since  2.0.0
			 */
			add_action( 'wp_ajax_totalcontest_contests_get_categories', function () {
				TotalContest( 'admin.ajax.contests' )->getCategories();
			} );
		endif;

		if ( current_user_can( 'publish_contest_submissions' ) ):
			/**
			 * @action wp_ajax_totalcontest_contests_approve_submission
			 * @since  2.0.0
			 */
			add_action( 'wp_ajax_totalcontest_contests_approve_submission', function () {
				TotalContest( 'admin.ajax.contests' )->approveSubmission();
			} );
		endif;

		// ------------------------------
		// Templates
		// ------------------------------
		/**
		 * @action wp_ajax_totalcontest_templates_get_defaults
		 * @since  4.0.0
		 */
		add_action( 'wp_ajax_totalcontest_templates_get_defaults', function () {
			TotalContest( 'admin.ajax.templates' )->getDefaults();
		} );
		/**
		 * @action wp_ajax_totalcontest_templates_get_preview
		 * @since  4.0.0
		 */
		add_action( 'wp_ajax_totalcontest_templates_get_preview', function () {
			TotalContest( 'admin.ajax.templates' )->getPreview();
		} );
		/**
		 * @action wp_ajax_totalcontest_templates_get_settings
		 * @since  4.0.0
		 */
		add_action( 'wp_ajax_totalcontest_templates_get_settings', function () {
			TotalContest( 'admin.ajax.templates' )->getSettings();
		} );

		/**
		 * Fires when AJAX handlers are bootstrapped.
		 *
		 * @since 2.0.0
		 * @order 7
		 */
		do_action( 'totalcontest/actions/bootstrap-ajax' );
	}

}
