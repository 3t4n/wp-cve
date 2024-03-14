<?php

namespace TotalContest\Admin\Dashboard;

use TotalContestVendors\TotalCore\Admin\Pages\Page as TotalCoreAdminPage;
use TotalContestVendors\TotalCore\Contracts\Admin\Activation;
use TotalContestVendors\TotalCore\Contracts\Http\Request;
use TotalContestVendors\TotalCore\Helpers\Arrays;
use TotalContestVendors\TotalCore\Helpers\Tracking;

/**
 * Class Page
 *
 * @package TotalContest\Admin\Dashboard
 */
class Page extends TotalCoreAdminPage {
	/**
	 * @var Activation $activation
	 */
	protected $activation;

	/**
	 * Page constructor.
	 *
	 * @param  Request  $request
	 * @param  array  $env
	 * @param  Activation  $activation
	 */
	public function __construct( Request $request, $env, Activation $activation ) {
		$onboarding = get_option( 'totalcontest_onboarding', [] );

		if ( Arrays::getDotNotation( $onboarding,
		                             'status',
		                             'init' ) === 'init' && current_user_can( 'manage_options' ) ) {
			wp_redirect( admin_url( 'edit.php?post_type=contest&page=onboarding' ) );
			exit();
		}

		parent::__construct( $request, $env );
		$this->activation = $activation;
	}

	/**
	 * Page assets.
	 */
	public function assets() {
		/**
		 * @asset-script totalcontest-admin-dashboard
		 */
		wp_enqueue_script( 'totalcontest-admin-dashboard' );
		/**
		 * @asset-style totalcontest-admin-dashboard
		 */
		wp_enqueue_style( 'totalcontest-admin-dashboard' );

		// Tweets preset
		$tweets = [
			'I\'m happy with #TotalContest plugin for #WordPress!',
			'#TotalContest is a powerful plugin for #WordPress.',
			'#TotalContest is one of the best contest plugins for #WordPress out there.',
			'You\'re looking for a contest plugin for #WordPress? You should give #TotalContest a try.',
			'I recommend #TotalContest plugin for #WordPress webmasters.',
			'Check out #TotalContest, a powerful contest plugin for #WordPress.',
			'Create closed contests and public contests easily with #TotalContest for #WordPress.',
			'Run a contest easily on your #WordPress powered website using #TotalContest.',
			'Boost user engagement with your website using #TotalContest plugin for #WordPress',
		];
		// Support
		// @TODO: Get this list from server
		$support = [
			'sections' => [
				[
					'title'       => esc_html__( 'Basics', 'totalcontest' ),
					'description' => esc_html__( 'The basics of TotalContest', 'totalcontest' ),
					'url'         => 'https://totalsuite.net/documentation/totalcontest/basics-totalcontest/',
					'links'       => [
						[
							'url'   => 'https://totalsuite.net/documentation/totalcontest/basics-totalcontest/create-first-contest-using-totalcontest/?utm_source=in-app&utm_medium=support-tab&utm_campaign=totalcontest',
							'title' => esc_html__( 'Creating a new contest', 'totalcontest' ),
						],
						[
							'url'   => 'https://totalsuite.net/documentation/totalcontest/basics-totalcontest/introduction-to-contest-editor/?utm_source=in-app&utm_medium=support-tab&utm_campaign=totalcontest',
							'title' => esc_html__( 'Introduction to contest editor', 'totalcontest' ),
						],
						[
							'url'   => 'https://totalsuite.net/documentation/totalcontest/basics-totalcontest/essential-settings-overview/?utm_source=in-app&utm_medium=support-tab&utm_campaign=totalcontest',
							'title' => esc_html__( 'Essential settings overview', 'totalcontest' ),
						],
					],
				],
				[
					'title'       => esc_html__( 'Advanced', 'totalcontest' ),
					'description' => esc_html__( 'Do more with TotalContest', 'totalcontest' ),
					'url'         => 'https://totalsuite.net/documentation/totalcontest/advanced-totalcontest/',
					'links'       => [
						[
							'url'   => 'https://totalsuite.net/documentation/totalcontest/advanced-totalcontest/participation-limitations/?utm_source=in-app&utm_medium=support-tab&utm_campaign=totalcontest',
							'title' => esc_html__( 'Participation limitations', 'totalcontest' ),
						],
						[
							'url'   => 'https://totalsuite.net/documentation/totalcontest/advanced-totalcontest/participation-frequency/?utm_source=in-app&utm_medium=support-tab&utm_campaign=totalcontest',
							'title' => esc_html__( 'Participation frequency', 'totalcontest' ),
						],
						[
							'url'   => 'https://totalsuite.net/documentation/totalcontest/advanced-totalcontest/vote-limitations/?utm_source=in-app&utm_medium=support-tab&utm_campaign=totalcontest',
							'title' => esc_html__( 'Vote limitations', 'totalcontest' ),
						],
					],
				],
			],
		];
		wp_localize_script( 'totalcontest-admin-dashboard', 'TotalContestPresets', [ 'tweets' => $tweets ] );
		wp_localize_script( 'totalcontest-admin-dashboard', 'TotalContestActivation', $this->activation->toArray() );
		wp_localize_script( 'totalcontest-admin-dashboard', 'TotalContestSupport', $support );
	}

	/**
	 * Page content.
	 */
	public function render() {
		include __DIR__ . '/views/index.php';
	}
}
