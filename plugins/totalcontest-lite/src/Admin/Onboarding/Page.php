<?php

namespace TotalContest\Admin\Onboarding;

use TotalContestVendors\TotalCore\Admin\Pages\Page as TotalCoreAdminPage;
use TotalContestVendors\TotalCore\Contracts\Http\Request;
use TotalContestVendors\TotalCore\Foundation\Environment;
use TotalContestVendors\TotalCore\Helpers\Arrays;

/**
 * Class Page
 *
 * @package TotalContest\Admin\Onbarding
 */
class Page extends TotalCoreAdminPage {
	/**
	 * @var array
	 */
	protected $content = [];

	/**
	 * Page constructor.
	 *
	 * @param Request $request
	 * @param Environment $env
	 */
	public function __construct( Request $request, $env ) {
		parent::__construct( $request, $env );

		$this->content = [
			'welcome' => [
				'title'       => esc_html__( 'Hey mate!', 'totalcontest' ),
				'description' => wp_kses(__( 'We are delighted to see you started using TotalContest. <br> TotalContest will impress you, we promise!', 'totalcontest' ), ['br' => []]),
				'benefits'    => [
					[
						'icon'        => 'touch_app',
						'title'       => esc_html__( 'User Friendly', 'totalcontest' ),
						'description' => esc_html__( 'Contest management has been made easy via a user-friendly interface.', 'totalcontest' )
					],
					[
						'icon'        => 'style',
						'title'       => esc_html__( 'Elegant Design', 'totalcontest' ),
						'description' => esc_html__( 'Run contest with elegant design to generate more content.', 'totalcontest' )
					],
					[
						'icon'        => 'power',
						'title'       => esc_html__( 'Flexibility & Extensibility', 'totalcontest' ),
						'description' => esc_html__( 'TotalContest\'s flexibility empowers you to create unprecedented contests.', 'totalcontest' )
					]
				]
			],
			'start'   => [
				'title'       => wp_kses(__( '🎓<br>Get started', 'totalcontest' ), ['br' => []]),
				'description' => esc_html__( 'We\'ve prepared some materials for you to ease your learning curve.', 'totalcontest' ),
				'posts'       => [
					[
						'thumbnail'   => esc_attr( $this->env['url'] ) . 'assets/dist/images/onboarding/create.svg',
						'title'       => esc_html__( 'How to create a contest', 'totalcontest' ),
						'description' => esc_html__( 'Learn how to create your first contest using TotalContest.', 'totalcontest' ),
						'url'         => 'https://totalsuite.net/documentation/totalcontest/basics/create-first-contest-using-totalcontest-for-wordpress/'
					],
					[
						'thumbnail'   => esc_attr( $this->env['url'] ) . 'assets/dist/images/onboarding/integrate.svg',
						'title'       => esc_html__( 'How to integrate a contest', 'totalcontest' ),
						'description' => esc_html__( 'Learn how to integrate a contest into your website.', 'totalcontest' ),
						'url'         => 'https://totalsuite.net/documentation/totalcontest/basics/publishing-contest-using-totalcontest-wordpress/'
					],
					[
						'thumbnail'   => esc_attr( $this->env['url'] ) . 'assets/dist/images/onboarding/customize.svg',
						'title'       => esc_html__( 'How to customize the appearance of a contest', 'totalcontest' ),
						'description' => esc_html__( 'Learn how to customize the appearance of a contest to match your brand.', 'totalcontest' ),
						'url'         => 'https://totalsuite.net/documentation/totalcontest/basics/design-customization-totalcontest-wordpress/'
					],
				]
			],
			'connect' => [
				'title'       => wp_kses(__( '🤝 <br> Happy to e-meet you!', 'totalcontest' ), ['br' => []]),
				'description' => esc_html__( "Let's go beyond business, let's be friends!", 'totalcontest' ),
			],
			'addons'  => [
				'title'       => wp_kses(__( '🍒 <br> Featured add-ons', 'totalcontest' ), ['br' => []]),
				'description' => esc_html__( "Do even more with a set of powerful add-ons for TotalContest.", 'totalcontest' ),
			],
			'finish'  => [
				'title'       => wp_kses(__( '🙌<br> You almost there!', 'totalcontest' ), ['br' => []]),
				'description' => esc_html__( "We'd like to collect some anonymous usage information that will help us shape up TotalContest.", 'totalcontest' ),
			]
		];
	}

	protected function getContent( $key, $default = null ) {
		return Arrays::getDotNotation( $this->content, $key, $default );
	}

	/**
	 * Page assets.
	 */
	public function assets() {
		/**
		 * @asset-script totalcontest-admin-onboarding
		 */
		wp_enqueue_script( 'totalcontest-admin-onboarding' );

		wp_enqueue_style( 'material-font', 'https://fonts.googleapis.com/icon?family=Material+Icons' );

		wp_localize_script( 'totalcontest-admin-onboarding', 'TotalContestFeaturedModules', TotalContest( 'modules.repository' )->getAllStore() );
		wp_localize_script( 'totalcontest-admin-onboarding', 'TotalContestDashboard', ['url' => admin_url( 'edit.php?post_type=contest&page=dashboard' )] );
	}

	/**
	 * Page content.
	 */
	public function render() {
		include __DIR__ . '/views/index.php';
	}
}
