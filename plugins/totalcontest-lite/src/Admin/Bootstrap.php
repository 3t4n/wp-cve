<?php

namespace TotalContest\Admin;

use TotalContestVendors\TotalCore\Admin\Pages\Page;
use TotalContestVendors\TotalCore\Contracts\Foundation\Environment;
use TotalContestVendors\TotalCore\Helpers\Arrays;
use TotalContestVendors\TotalCore\Helpers\Strings;
use TotalContestVendors\TotalCore\Http\Request;

/**
 * Class Bootstrap
 *
 * @package TotalContest\Admin
 */
class Bootstrap {
	/**
	 * @var array $pages
	 */
	public $pages = [];

	/**
	 * @var string|null $requestedPage
	 */
	public $requestedPage = null;

	/**
	 * @var Page|null $currentPage
	 */
	public $currentPage = null;

	/**
	 * @var Environment $env
	 */
	public $env;
	/**
	 * @var Request
	 */
	public $request;

	/**
	 * @var array
	 */
	protected $messages = [];

	/**
	 * @var array
	 */
	protected $onboarding = [];

	/**
	 * Bootstrap constructor.
	 *
	 * @param  Request  $request
	 * @param  Environment  $env
	 */
	public function __construct( Request $request, Environment $env ) {
		$this->onboarding = get_option( 'totalcontest_onboarding', [ 'status' => 'init' ] );

		$this->messages = [
			'reset-contest-success' => [ esc_html__( 'Contest has been reset', 'totalcontest' ), 'updated' ],
			'reset-contest-error'   => [ esc_html__( 'Contest reset failed', 'totalcontest' ), 'error' ],
		];

		$this->request = $request;
		$this->env     = $env;

		// TotalContest pages.
		$this->pages = [
			'dashboard'      => [
				'title'      => esc_html__( 'Dashboard', 'totalcontest' ),
				'name'       => esc_html__( 'Dashboard', 'totalcontest' ),
				'capability' => 'manage_options',
			],
			'log'            => [
				'title'      => esc_html__( 'Logs', 'totalcontest' ),
				'name'       => esc_html__( 'Logs', 'totalcontest' ),
				'capability' => 'manage_options',
			],
			'templates'      => [
				'title'      => esc_html__( 'Templates', 'totalcontest' ),
				'name'       => esc_html__( 'Templates', 'totalcontest' ),
				'capability' => 'manage_options',
			],
			'extensions'     => [
				'title'      => esc_html__( 'Extensions', 'totalcontest' ),
				'name'       => esc_html__( 'Extensions', 'totalcontest' ),
				'capability' => 'manage_options',
			],
			'options'        => [
				'title'      => esc_html__( 'Options', 'totalcontest' ),
				'name'       => esc_html__( 'Options', 'totalcontest' ),
				'capability' => 'manage_options',
			],
			
			'upgrade-to-pro' => [
				'title'      => esc_html__( 'Upgrade to Pro', 'totalcontest' ),
				'name'       => esc_html__( 'Upgrade to Pro', 'totalcontest' ),
				'capability' => 'manage_options',
			],
			
		];

		if ( Arrays::getDotNotation( $this->onboarding,
		                             'status',
		                             'init' ) === 'init' && current_user_can( 'manage_options' ) ) {
			$this->pages['onboarding'] = [
				'title'      => esc_html__( 'Onboarding', 'totalcontest' ),
				'name'       => esc_html__( 'Onboarding', 'totalcontest' ),
				'capability' => 'manage_options',
			];
		}

		// Hook into WordPress menu and screen
		add_action( 'current_screen', [ $this, 'screen' ] );
		add_action( 'admin_menu', [ $this, 'menu' ] );
		add_filter( 'admin_body_class', [ $this, 'directionClass' ] );
		add_action( 'admin_action_reset_contest', [ $this, 'resetContest' ] );
		add_action( 'admin_notices', [ $this, 'showMessages' ] );

		// Requested page
		$this->requestedPage = $request->request( 'page' );

		// Bootstrap the page, if present
		if ( $GLOBALS['pagenow'] === 'edit.php' && $request->request( 'post_type' ) === TC_CONTEST_CPT_NAME ):
			if ( array_key_exists( $this->requestedPage, $this->pages ) ):
				$this->currentPage = TotalContest( "admin.pages.{$this->requestedPage}" );
			endif;
		endif;

		// Register admin assets
		$this->registerAssets();


		/**
		 * Fires when admin is bootstrapped.
		 *
		 * @since 2.0.0
		 * @order 10
		 */
		do_action( 'totalcontest/actions/bootstrap-admin' );
	}

	public function resetContest() {
		$contest = $this->request->query( 'contest', 0 );
		$url     = wp_get_referer();

		if ( check_admin_referer( 'reset_contest' ) ) {
			$this->contestCleanup( $contest );
			$url = add_query_arg( 'message', 'reset-contest-success', $url );
		} else {
			$url = add_query_arg( 'message', 'reset-contest-error', $url );
		}
		wp_redirect( $url );
		exit();
	}

	/**
	 * @param $contest_id
	 */
	public function contestCleanup( $contest_id ) {
		$contest = get_post( $contest_id );

		if ( $contest && $contest->post_type == TC_CONTEST_CPT_NAME ):
			$submissions = get_posts( [
				                          'post_type'      => TC_SUBMISSION_CPT_NAME,
				                          'posts_per_page' => - 1,
				                          'post_parent'    => $contest->ID,
			                          ] );

			foreach ( $submissions as $submission ) {
				wp_delete_post( $submission->ID, false );
			}

			TotalContest( 'log.repository' )->delete( [ 'contest_id' => $contest->ID ] );

		endif;
	}

	/**
	 * Show admin messages / admin notice hook.
	 */
	function showMessages() {
		$message = $this->request->query( 'message' );

		if ( array_key_exists( $message, $this->messages ) ) {
			list( $content, $type ) = $this->messages[ $message ];
			printf( '<div class="notice %s"><p>%s</p></div>', $type, $content );
		}
	}

	public function screen() {
		$isTotalContest       = $GLOBALS['current_screen']->post_type === TC_CONTEST_CPT_NAME || $GLOBALS['current_screen']->post_type === TC_SUBMISSION_CPT_NAME;
		$isContestEditor      = $GLOBALS['current_screen']->base === 'post' && $GLOBALS['current_screen']->post_type === TC_CONTEST_CPT_NAME;
		$isSubmissionEditor   = $GLOBALS['current_screen']->base === 'post' && $GLOBALS['current_screen']->post_type === TC_SUBMISSION_CPT_NAME;
		$isContestsListing    = $GLOBALS['current_screen']->base === 'edit' && $GLOBALS['current_screen']->post_type === TC_CONTEST_CPT_NAME;
		$isSubmissionsListing = $GLOBALS['current_screen']->base === 'edit' && $GLOBALS['current_screen']->post_type === TC_SUBMISSION_CPT_NAME;

		if ( $isContestEditor ):
			// Bootstrap contest editor
			TotalContest( 'admin.contest.editor' );

		elseif ( $isSubmissionEditor ):
			// Bootstrap submission editor
			TotalContest( 'admin.submission.editor' );

		elseif ( $isContestsListing ):
			// Contests listing
			TotalContest( 'admin.contest.listing' );

		elseif ( $isSubmissionsListing ):
			// Submission listing
			$exportAs = (string) $this->request->request( 'export', '' );
			if ( $exportAs ):
				TotalContest( 'admin.submission.listing' )->download( $exportAs );
			endif;

			TotalContest( 'admin.submission.listing' );
		endif;

		if ( $isTotalContest ):
			add_filter( 'admin_footer_text', [ $this, 'footerText' ] );
			add_filter( 'update_footer', [ $this, 'footerVersion' ] );
		endif;
	}

	public function menu() {
		$slug = 'edit.php?post_type=' . TC_CONTEST_CPT_NAME;

		// Add it directly to super global because add_sub menu_page function does not support custom URL for sub menu items.
		if ( current_user_can( 'edit_contest_submissions' ) && isset( $GLOBALS['submenu'][ $slug ] ) ):
			$GLOBALS['submenu'][ $slug ][] = [
				esc_html__( 'Submissions', 'totalcontest' ),
				'edit_posts',
				'edit.php?post_type=' . TC_SUBMISSION_CPT_NAME,
			];
		endif;

		

		foreach ( $this->pages as $pageSlug => $page ):
			add_submenu_page( $slug,
			                  $page['title'],
			                  $page['name'],
			                  empty( $page['capability'] ) ? 'manage_options' : $page['capability'],
			                  $pageSlug,
			                  [ $this, 'page' ] );
		endforeach;

		add_filter( 'parent_file',
			function ( $file ) use ( $slug ) {
				if ( isset($GLOBALS['submenu'][ $slug ]) ):
					foreach ( $GLOBALS['submenu'][ $slug ] as $itemIndex => $item ):
						if ( $item[2] === 'dashboard' ):
							unset( $GLOBALS['submenu'][ $slug ][ $itemIndex ] );
							array_unshift( $GLOBALS['submenu'][ $slug ], $item );
						endif;
					endforeach;
				endif;

				$pages = array_keys( $this->pages );
				if ( isset($GLOBALS['submenu'][ $slug ]) ):
					foreach ( $GLOBALS['submenu'][ $slug ] as $index => $item ):
						if ( in_array( $item[2], $pages, true ) ):
							$GLOBALS['submenu'][ $slug ][ $index ][4] = ! empty( $GLOBALS['plugin_page'] ) && $GLOBALS['plugin_page'] === $item[2] && $GLOBALS['typenow'] === TC_CONTEST_CPT_NAME ? 'current' : '';
							$GLOBALS['submenu'][ $slug ][ $index ][2] = add_query_arg( [ 'page' => $item[2] ], $slug );
						endif;
					endforeach;
				endif;

				if ( isset($GLOBALS['current_screen']->taxonomy) && $GLOBALS['current_screen']->taxonomy === TC_SUBMISSION_CATEGORY_TAX_NAME ):
					$GLOBALS['current_screen']->post_type = TC_SUBMISSION_CPT_NAME;
				endif;

				return $file;
			} );
	}

	public function registerAssets() {
		// Add a dynamic part to assets URL when debugging to prevent cache.
		$assetsVersion = WP_DEBUG ? time() : $this->env['version'];
		$baseUrl       = $this->env['url'];

		wp_register_script( 'jquery-datetimepicker',
		                    "{$baseUrl}assets/dist/scripts/vendor/jquery.datetimepicker.full.min.js",
		                    [ 'jquery' ],
		                    $assetsVersion );
		wp_register_style( 'jquery-datetimepicker',
		                   "{$baseUrl}assets/dist/styles/vendor/datetimepicker.css",
		                   [],
		                   $assetsVersion );

		wp_register_script( 'angular',
		                    "{$baseUrl}assets/dist/scripts/vendor/angular.min.js",
		                    [ 'media-views' ],
		                    $assetsVersion );
		wp_register_script( 'angular-dnd-lists',
		                    "{$baseUrl}assets/dist/scripts/vendor/angular-drag-and-drop-lists.min.js",
		                    [ 'angular' ],
		                    $assetsVersion );
		wp_register_script( 'angular-resource',
		                    "{$baseUrl}assets/dist/scripts/vendor/angular-resource.min.js",
		                    [ 'angular' ],
		                    $assetsVersion );
		wp_register_script( 'angular-file-input',
		                    "{$baseUrl}assets/dist/scripts/vendor/ng-file-input.min.js",
		                    [ 'angular' ],
		                    $assetsVersion );
		wp_add_inline_script( 'angular', sprintf("var totalcontestAjaxURL = '%s'", esc_js(wp_nonce_url( admin_url( 'admin-ajax.php' ), 'totalcontest' ))) );

		wp_register_script( 'platformjs',
		                    "{$baseUrl}assets/dist/scripts/vendor/platform.js",
		                    [ 'angular' ],
		                    $assetsVersion );

		wp_register_style( 'totalcontest-admin-totalcore',
		                   "{$baseUrl}assets/dist/styles/admin-totalcore.css",
		                   [],
		                   $assetsVersion );

		wp_register_script( 'totalcontest-admin-dashboard',
		                    "{$baseUrl}assets/dist/scripts/dashboard.js",
		                    [ 'angular', 'angular-resource' ],
		                    $assetsVersion );
		wp_register_style( 'totalcontest-admin-dashboard',
		                   "{$baseUrl}assets/dist/styles/admin-dashboard.css",
		                   [ 'totalcontest-admin-totalcore' ],
		                   $assetsVersion );

		wp_register_script( 'totalcontest-admin-contest-editor',
		                    "{$baseUrl}assets/dist/scripts/contest-editor.js",
		                    [
			                    'jquery-datetimepicker',
			                    'wp-color-picker',
			                    'angular',
			                    'angular-dnd-lists',
			                    'angular-resource',
		                    ],
		                    $assetsVersion );
		wp_register_style( 'totalcontest-admin-contest-editor',
		                   "{$baseUrl}assets/dist/styles/admin-contest-editor.css",
		                   [ 'jquery-datetimepicker', 'wp-color-picker', 'totalcontest-admin-totalcore' ],
		                   $assetsVersion );

		wp_register_script( 'totalcontest-admin-submission-editor',
		                    "{$baseUrl}assets/dist/scripts/submission-editor.js",
		                    [ 'angular', 'angular-resource' ],
		                    $assetsVersion );
		wp_register_style( 'totalcontest-admin-submission-editor',
		                   "{$baseUrl}assets/dist/styles/admin-submission-editor.css",
		                   [ 'totalcontest-admin-totalcore' ],
		                   $assetsVersion );

		wp_register_style( 'totalcontest-admin-contests-listing',
		                   "{$baseUrl}assets/dist/styles/admin-contests-listing.css",
		                   [],
		                   $assetsVersion );

		wp_register_script( 'totalcontest-admin-submissions-listing',
		                    "{$baseUrl}assets/dist/scripts/submissions-listing.js",
		                    [ 'angular', 'angular-resource' ],
		                    $assetsVersion );
		wp_register_style( 'totalcontest-admin-submissions-listing',
		                   "{$baseUrl}assets/dist/styles/admin-submissions-listing.css",
		                   [ 'totalcontest-admin-totalcore' ],
		                   $assetsVersion );

		wp_register_script( 'totalcontest-admin-log',
		                    "{$baseUrl}assets/dist/scripts/log.js",
		                    [ 'angular', 'angular-resource', 'platformjs', 'jquery-datetimepicker' ],
		                    $assetsVersion );
		wp_register_style( 'totalcontest-admin-log',
		                   "{$baseUrl}assets/dist/styles/admin-log.css",
		                   [ 'totalcontest-admin-totalcore', 'jquery-datetimepicker' ],
		                   $assetsVersion );

		wp_register_script( 'totalcontest-admin-modules',
		                    "{$baseUrl}assets/dist/scripts/modules.js",
		                    [ 'angular', 'angular-resource', 'angular-file-input' ],
		                    $assetsVersion );
		wp_register_style( 'totalcontest-admin-modules',
		                   "{$baseUrl}assets/dist/styles/admin-modules.css",
		                   [ 'totalcontest-admin-totalcore' ],
		                   $assetsVersion );

		wp_register_script( 'totalcontest-admin-options',
		                    "{$baseUrl}assets/dist/scripts/options.js",
		                    [ 'angular', 'angular-resource' ],
		                    $assetsVersion );
		wp_register_style( 'totalcontest-admin-options',
		                   "{$baseUrl}assets/dist/styles/admin-options.css",
		                   [ 'totalcontest-admin-totalcore' ],
		                   $assetsVersion );

		wp_register_script( 'totalcontest-admin-onboarding',
		                    "{$baseUrl}assets/dist/scripts/onboarding.js",
		                    [ 'angular', 'jquery', 'wp-util', 'angular-resource' ],
		                    $assetsVersion );

		
		// ------------------------------
		// Upgrade to pro
		// ------------------------------
		/**
		 * @asset-script totalcontest-admin-options
		 */
		wp_register_style( 'totalcontest-admin-upgrade-to-pro',
		                   "{$baseUrl}assets/dist/styles/admin-upgrade-to-pro.css",
		                   [ 'totalcontest-admin-totalcore' ],
		                   $assetsVersion );
		
	}

	public function page() {
		echo $this->currentPage;
	}

	/**
	 * @return mixed
	 */
	public function footerText() {
		$text = wp_kses( __( '{{product}} is part of <a href="{{totalsuite}}" target="_blank">TotalSuite</a>, a suite of robust and maintained plugins for WordPress.',
		                     'totalcontest' ),
		                 [ 'a' => [ 'href' => [] ] ] );

		return Strings::template( $text,
		                          [
			                          'product'    => $this->env['name'],
			                          'totalsuite' => add_query_arg( [
				                                                         'utm_source'   => 'in-app',
				                                                         'utm_medium'   => 'footer',
				                                                         'utm_campaign' => 'totalcontest',
			                                                         ],
			                                                         $this->env['links.totalsuite'] ),
		                          ] );
	}

	/**
	 * @return string
	 */
	public function footerVersion() {
		return "{$this->env['name']} {$this->env['version']}";
	}

	/**
	 * Add direction (rtl|ltr) to body css classes.
	 *
	 * @param $classes
	 *
	 * @return string
	 */
	public function directionClass( $classes ) {
		return $classes . ( is_rtl() ? 'is-rtl' : ' is-ltr' );
	}
}
