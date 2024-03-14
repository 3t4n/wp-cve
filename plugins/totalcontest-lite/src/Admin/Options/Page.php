<?php

namespace TotalContest\Admin\Options;

use TotalContest\Contracts\Migrations\Contest\Migrator;
use TotalContestVendors\TotalCore\Admin\Pages\Page as AdminPageContract;
use TotalContestVendors\TotalCore\Contracts\Foundation\Environment as EnvironmentContract;
use TotalContestVendors\TotalCore\Contracts\Http\Request as RequestContract;
use TotalContestVendors\TotalCore\Helpers\Misc;
use TotalContestVendors\TotalCore\Helpers\Tracking;

/**
 * Class Page
 * @package TotalContest\Admin\Options
 */
class Page extends AdminPageContract {
	/**
	 * Options.
	 *
	 * @var array $options
	 */
	protected $options;
	/**
	 * @var Migrator[] $migrators
	 */
	protected $migrators;

	/**
	 * Page constructor.
	 *
	 * @param RequestContract $request
	 * @param EnvironmentContract $env
	 */
	public function __construct( RequestContract $request, EnvironmentContract $env, $migrators ) {
		parent::__construct( $request, $env );
		$this->migrators = $migrators;
		$this->options   = TotalContest( 'options' )->getOptions();

		if ( empty( $this->options ) ):
			$this->options = null;
		endif;
	}

	/**
	 * Enqueue assets.
	 *
	 * @return mixed
	 */
	public function assets() {
		// TotalContest
		wp_enqueue_script( 'totalcontest-admin-options' );
		wp_enqueue_style( 'totalcontest-admin-options' );

		/**
		 * Filters the list of expressions that are available through the interface to override.
		 *
		 * @param array $expressions Array of expressions.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$expressions = apply_filters(
			'totalcontest/filters/admin/options/expressions',
			[
				'contest'     => [
					'label'       => esc_html__( 'Contest', 'totalcontest' ),
					'expressions' => [
						'Home'        => [
							'translations' => [
								esc_html__( 'Home', 'totalcontest' ),
							],
						],
						'Participate' => [
							'translations' => [
								esc_html__( 'Participate', 'totalcontest' ),
							],
						],
						'Submissions' => [
							'translations' => [
								esc_html__( 'Submissions', 'totalcontest' ),
							],
						],
						'Submit'  => [
							'translations' => [
								esc_html__( 'Submit', 'totalcontest' ),
							],
						],
						'Submitting'  => [
							'translations' => [
								esc_html__( 'Submitting', 'totalcontest' ),
							],
						],
					],
				],
				'submission'  => [
					'label'       => esc_html__( 'Submission', 'totalcontest' ),
					'expressions' => [
						'Vote'                                                                                   => [
							'translations' => [
								esc_html__( 'Vote', 'totalcontest' ),
							],
						],
						'%s Vote'                                                                                => [
							'translations' => [
								esc_html__( '%s Vote', 'totalcontest' ),
								esc_html__( '%s Votes', 'totalcontest' ),
							],
						],
						'%s View'                                                                                => [
							'translations' => [
								esc_html__( '%s View', 'totalcontest' ),
								esc_html__( '%s Views', 'totalcontest' ),
							],
						],
						'Since posted'                                                                           => [
							'translations' => [
								esc_html__( 'Since posted', 'totalcontest' ),
							],
						],
						'Average rate'                                                                           => [
							'translations' => [
								esc_html__( 'Average rate', 'totalcontest' ),
							],
						],
						'This submission is awaiting moderator approval, it will published as soon as possible.' => [
							'translations' => [
								esc_html__( 'This submission is awaiting moderator approval, it will published as soon as possible.', 'totalcontest' ),
							],
						],
					],
				],
				'submissions' => [
					'label'       => esc_html__( 'Submissions', 'totalcontest' ),
					'expressions' => [
						'There are no submissions yet.' => [
							'translations' => [
								esc_html__( 'There are no submissions yet.', 'totalcontest' ),
							],
						],
						'Thank you!'                    => [
							'translations' => [
								esc_html__( 'Thank you!', 'totalcontest' ),
							],
						],
						'Winner!'                       => [
							'translations' => [
								esc_html__( 'Winner!', 'totalcontest' ),
							],
						],
						'Browse submissions'            => [
							'translations' => [
								esc_html__( 'Browse submissions', 'totalcontest' ),
							],
						],
						'Search'                        => [
							'translations' => [
								esc_html__( 'Search', 'totalcontest' ),
							],
						],
						'Date'                          => [
							'translations' => [
								esc_html__( 'Date', 'totalcontest' ),
							],
						],
						'Views'                         => [
							'translations' => [
								esc_html__( 'Views', 'totalcontest' ),
							],
						],
						'Votes'                         => [
							'translations' => [
								esc_html__( 'Votes', 'totalcontest' ),
							],
						],
						'Sort by'                       => [
							'translations' => [
								esc_html__( 'Sort by', 'totalcontest' ),
							],
						],
						'Filter by'                     => [
							'translations' => [
								esc_html__( 'Filter by', 'totalcontest' ),
							],
						],
						'Ascending'                     => [
							'translations' => [
								esc_html__( 'Ascending', 'totalcontest' ),
							],
						],
						'Descending'                    => [
							'translations' => [
								esc_html__( 'Descending', 'totalcontest' ),
							],
						],
						'Previous'                      => [
							'translations' => [
								esc_html__( 'Previous', 'totalcontest' ),
							],
						],
						'Next'                          => [
							'translations' => [
								esc_html__( 'Next', 'totalcontest' ),
							],
						],
						'Choose'                        => [
							'translations' => [
								esc_html__( 'Choose', 'totalcontest' ),
							],
						],
					],
				],
				'errors'      => [
					'label'       => esc_html__( 'Errors', 'totalcontest' ),
					'expressions' => [
						'You cannot submit new entries in this contest.' => [
							'translations' => [
								esc_html__( 'You cannot submit new entries in this contest.', 'totalcontest' ),
							],
						],
						'Something went wrong!'                          => [
							'translations' => [
								esc_html__( 'Something went wrong!', 'totalcontest' ),
							],
						],
					],
				],
				'validations' => [
					'label'       => esc_html__( 'Validations', 'totalcontest' ),
					'expressions' => [
						'{{label}} must be filled.'                                    => [
							'translations' => [
								esc_html__( '{{label}} must be filled.', 'totalcontest' ),
							],
						],
						'{{label}} must be a valid email address.'                     => [
							'translations' => [
								esc_html__( '{{label}} must be a valid email address.', 'totalcontest' ),
							],
						],
						'{{label}} must be a valid URL.'                               => [
							'translations' => [
								esc_html__( '{{label}} must be a valid URL.', 'totalcontest' ),
							],
						],
						'{{label}} is not within the supported range.'                 => [
							'translations' => [
								esc_html__( '{{label}} is not within the supported range.', 'totalcontest' ),
							],
						],
						'{{label}} does not allow this value.'                         => [
							'translations' => [
								esc_html__( '{{label}} does not allow this value.', 'totalcontest' ),
							],
						],
						'{{label}} must be a number.'                                  => [
							'translations' => [
								esc_html__( '{{label}} must be a number.', 'totalcontest' ),
							],
						],
						'{{label}} must be unique. The entered value was used before.' => [
							'translations' => [
								esc_html__( '{{label}} must be unique. The entered value was used before.', 'totalcontest' ),
							],
						],
						'{{label}} is not in an array format.'                         => [
							'translations' => [
								esc_html__( '{{label}} is not in an array format.', 'totalcontest' ),
							],
						],
						'{{label}} is not a string.'                                   => [
							'translations' => [
								esc_html__( '{{label}} is not a string.', 'totalcontest' ),
							],
						],
						'{{label}} file size must be at least %s.'                     => [
							'translations' => [
								esc_html__( '{{label}} file size must be at least %s.', 'totalcontest' ),
							],
						],
						'{{label}} file size must be less than %s.'                    => [
							'translations' => [
								esc_html__( '{{label}} file size must be less than %s.', 'totalcontest' ),
							],
						],
						'{{label}} must be at least %d characters.'                    => [
							'translations' => [
								esc_html__( '{{label}} must be at least %d characters.', 'totalcontest' ),
							],
						],
						'{{label}} must be less than %d characters.'                   => [
							'translations' => [
								esc_html__( '{{label}} must be less than %d characters.', 'totalcontest' ),
							],
						],
						'%d Characters left'                                           => [
							'translations' => [
								esc_html__( '%d Characters left', 'totalcontest' ),
							],
						],
						'Only files with these extensions are allowed: %s.'            => [
							'translations' => [
								esc_html__( 'Only files with these extensions are allowed: %s.', 'totalcontest' ),
							],
						],
						'Only %s files are accepted.'                                  => [
							'translations' => [
								esc_html__( 'Only %s files are accepted.', 'totalcontest' ),
							],
						],
						'You must upload a file.'                                      => [
							'translations' => [
								esc_html__( 'You must upload a file.', 'totalcontest' ),
							],
						],
						'Minimum length for files is: %s seconds.'                     => [
							'translations' => [
								esc_html__( 'Minimum length for files is: %s seconds.', 'totalcontest' ),
							],
						],
						'Maximum length for files is: %s seconds.'                     => [
							'translations' => [
								esc_html__( 'Maximum length for files is: %s seconds.', 'totalcontest' ),
							],
						],
						'Minimum width for images is: %s.'                             => [
							'translations' => [
								esc_html__( 'Minimum width for images is: %s.', 'totalcontest' ),
							],
						],
						'Minimum height for images is: %s.'                            => [
							'translations' => [
								esc_html__( 'Minimum height for images is: %s.', 'totalcontest' ),
							],
						],
						'Maximum width for images is: %s.'                             => [
							'translations' => [
								esc_html__( 'Maximum width for images is: %s.', 'totalcontest' ),
							],
						],
						'Maximum height for images is: %s.'                            => [
							'translations' => [
								esc_html__( 'Maximum height for images is: %s.', 'totalcontest' ),
							],
						],
						'Only links from these services are accepted: %s.'             => [
							'translations' => [
								esc_html__( 'Only links from these services are accepted: %s.', 'totalcontest' ),
							],
						],
					],
				],
			]
		);

		wp_localize_script( 'totalcontest-admin-options', 'TotalContestExpressions', $expressions );
		wp_localize_script( 'totalcontest-admin-options', 'TotalContestSavedExpressions', Misc::getJsonOption( 'totalcontest_expressions' ) );
		wp_localize_script( 'totalcontest-admin-options', 'TotalContestOptions', $this->options );
		wp_localize_script( 'totalcontest-admin-options', 'TotalContestDebugInformation', Misc::getDebugInfo() );
		wp_localize_script( 'totalcontest-admin-options', 'TotalContestMigrationPlugins', $this->migrators );
	}

	public function render() {

		/**
		 * Filters the list of tabs in options page.
		 *
		 * @param array $tabs Array of tabs [id => [label, icon, file]].
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$tabs = apply_filters(
			'totalcontest/filters/admin/options/tabs',
			[
				'general'       => [ 'label' => esc_html__( 'General', 'totalcontest' ), 'icon' => 'admin-settings' ],
				'performance'   => [ 'label' => esc_html__( 'Performance', 'totalcontest' ), 'icon' => 'performance' ],
				'services'      => [ 'label' => esc_html__( 'Services', 'totalcontest' ), 'icon' => 'cloud' ],
				'sharing'       => [ 'label' => esc_html__( 'Sharing', 'totalcontest' ), 'icon' => 'share' ],
				'advanced'      => [ 'label' => esc_html__( 'Advanced', 'totalcontest' ), 'icon' => 'admin-generic' ],
				'notifications' => [ 'label' => esc_html__( 'Notifications', 'totalcontest' ), 'icon' => 'email' ],
				'expressions'   => [ 'label' => esc_html__( 'Expressions', 'totalcontest' ), 'icon' => 'admin-site' ],
				
				'import-export' => [ 'label' => esc_html__( 'Import & Export', 'totalcontest' ), 'icon' => 'update' ],
				'debug'         => [ 'label' => esc_html__( 'Debug', 'totalcontest' ), 'icon' => 'info' ],
			]
		);

		include_once __DIR__ . '/views/index.php';
	}
}
