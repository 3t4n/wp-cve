<?php

namespace TotalContest\Admin\Contest;

use TotalContest\Contracts\Contest\Model;
use TotalContest\Contracts\Contest\Repository as ContestRepository;
use TotalContestVendors\TotalCore\Contracts\Foundation\Environment;
use TotalContestVendors\TotalCore\Contracts\Modules\Repository as ModulesRepository;
use TotalContestVendors\TotalCore\Helpers\Arrays;
use TotalContestVendors\TotalCore\Helpers\Misc;
use TotalContestVendors\TotalCore\Helpers\Tracking;

/**
 * Class Editor
 *
 * @package TotalContest\Admin\Contest
 */
class Editor {
	/**
	 * @var Environment $env
	 */
	protected $env;
	/**
	 * @var \WP_Filesystem_Base $filesystem
	 */
	protected $filesystem;
	/**
	 * @var ModulesRepository $modulesRepository
	 */
	protected $modulesRepository;
	/**
	 * @var ContestRepository $contestRepository
	 */
	protected $contestRepository;
	/**
	 * @var Model $contest
	 */
	protected $contest;
	/**
	 * @var array $templates
	 */
	protected $templates = [];
	/**
	 * @var array $settings
	 */
	protected $settings = [];

	/**
	 * Bootstrap constructor.
	 *
	 * @param                     $env
	 * @param  \WP_Filesystem_Base  $filesystem
	 * @param  ModulesRepository  $modulesRepository
	 * @param  ContestRepository  $contestRepository
	 */
	public function __construct(
		$env,
		$filesystem,
		ModulesRepository $modulesRepository,
		ContestRepository $contestRepository
	) {
		$this->env               = $env;
		$this->filesystem        = $filesystem;
		$this->contestRepository = $contestRepository;
		$this->modulesRepository = $modulesRepository;

		// Templates
		$this->templates = $this->modulesRepository->getActiveWhere( [ 'type' => 'template' ] );
		foreach ( $this->templates as $templateId => $template ):
			foreach ( [ 'defaults', 'settings', 'preview' ] as $item ):
				$this->templates[ $templateId ][ $item ] = add_query_arg(
					[ 'action' => "totalcontest_templates_get_{$item}", 'template' => $templateId ],
					wp_nonce_url( admin_url( 'admin-ajax.php' ), 'totalcontest' )
				);
			endforeach;
		endforeach;

		// Enqueue assets
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
		// Editor
		add_action( 'edit_form_after_title', [ $this, 'content' ] );
		// Actions
		add_action( 'submitpost_box', [ $this, 'actions' ] );
		// Save contest
		add_filter( 'wp_insert_post_data', [ $this, 'save' ], 10, 2 );

		// Remove WP filters
		if ( function_exists( 'wp_remove_targeted_link_rel_filters' ) ) {
			wp_remove_targeted_link_rel_filters();
		}
		// Default blocks
		add_filter( 'totalcontest/filters/admin/contest/editor/defaults', [ $this, 'defaultBlocks' ], 10 );

		// Remove WP filters
		remove_filter( 'content_save_pre', 'wp_targeted_link_rel' );
	}

	/**
	 * @param  array  $settings
	 *
	 * @return array
	 */
	public function defaultBlocks( $settings ) {
		global $post;

		if ( $post->post_status !== 'auto-draft' ) {
			unset( $settings['contest']['submissions']['blocks']['submissions'] );
			unset( $settings['contest']['submissions']['blocks']['submission'] );
		}

		return $settings;
	}

	/**
	 * Front-end assets.
	 */
	public function assets() {
		if ( ! empty( $GLOBALS['post'] ) ):
			$this->contest  = $this->contestRepository->getById( $GLOBALS['post']->ID );
			$this->settings = json_decode( $GLOBALS['post']->post_content, true ) ?: [];
		endif;

		// Disable auto save
		wp_dequeue_script( 'autosave' );

		// WP Media
		wp_enqueue_media();

		// TinyMCE
		if ( ! class_exists( '_WP_Editors', false ) ):
			require ABSPATH . WPINC . '/class-wp-editor.php';
			\_WP_Editors::enqueue_scripts();
		endif;

		// TotalContest
		wp_enqueue_script( 'totalcontest-admin-contest-editor' );
		wp_enqueue_style( 'totalcontest-admin-contest-editor' );

		/**
		 * Filters the settings of contest passed to frontend controller.
		 *
		 * @param  array  $settings  Array of settings.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$settings = apply_filters( 'totalcontest/filters/admin/contest/editor/settings', $this->settings );

		/**
		 * Filters the information passed to frontend controller.
		 *
		 * @param  array  $information  Array of values [key => value].
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$information = apply_filters(
			'totalcontest/filters/admin/contest/editor/information',
			[
				'imageSizes' => get_intermediate_image_sizes(),
				'sidebars'   => $GLOBALS['wp_registered_sidebars'],
			]
		);

		/**
		 * Filters the defaults settings of contest editor.
		 *
		 * @param  array  $defaults  Array of settings.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$defaults = apply_filters(
			'totalcontest/filters/admin/contest/editor/defaults',
			TotalContest( 'contests.defaults' )
		);

		if ( ! empty( $settings['meta']['schema'] ) && version_compare( $settings['meta']['schema'], '1.1', '<' ) ):
			$defaults = Arrays::setDotNotation( $defaults, 'contest.submissions.blocks.enabled', false );
		endif;

		// Send JSON to TotalContest frontend controller
		wp_localize_script( 'totalcontest-admin-contest-editor', 'TotalContestSettings', $settings );
		wp_localize_script( 'totalcontest-admin-contest-editor', 'TotalContestDefaults', $defaults );
		wp_localize_script( 'totalcontest-admin-contest-editor', 'TotalContestInformation', $information );
		wp_localize_script( 'totalcontest-admin-contest-editor', 'TotalContestTemplates', $this->templates );
		wp_localize_script( 'totalcontest-admin-contest-editor', 'TotalContestLanguages', Misc::getSiteLanguages() );
		wp_localize_script( 'totalcontest-admin-contest-editor', 'TotalContestPresets', [
			'timeout' => [
				'30'     => esc_html__( '30 Minutes', 'totalcontest' ),
				'60'     => esc_html__( '1 Hour', 'totalcontest' ),
				'360'    => esc_html__( '6 Hours', 'totalcontest' ),
				'1440'   => esc_html__( '1 Day', 'totalcontest' ),
				'10080'  => esc_html__( '1 Week', 'totalcontest' ),
				'43800'  => esc_html__( '1 Month', 'totalcontest' ),
				'262800' => esc_html__( '6 Months', 'totalcontest' ),
				'525600' => esc_html__( '1 Year', 'totalcontest' ),
			],
		] );
	}

	/**
	 * Editor content
	 */
	public function content() {
		/**
		 * Filters tabs list in contest editor.
		 *
		 * @param  array  $tabs  Array of tabs [id => [label, icon]].
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$tabs = apply_filters(
			'totalcontest/filters/admin/contest/editor/tabs',
			[
				'form'         => [ 'label' => esc_html__( 'Fields', 'totalcontest' ), 'icon' => 'feedback' ],
				'settings'     => [ 'label' => esc_html__( 'Settings', 'totalcontest' ), 'icon' => 'admin-settings' ],
				'design'       => [ 'label' => esc_html__( 'Design', 'totalcontest' ), 'icon' => 'admin-appearance' ],
				'integration'  => [ 'label' => esc_html__( 'Integration', 'totalcontest' ), 'icon' => 'admin-generic' ],
				'translations' => [ 'label' => esc_html__( 'Translations', 'totalcontest' ), 'icon' => 'translation' ],
			],
			$this
		);
		/**
		 * Filters the list of settings tabs in contest editor.
		 *
		 * @param  array  $settingsTabs  Array of tabs [id => [label, icon, tabs => []]].
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$settingsTabs = apply_filters(
			'totalcontest/filters/admin/contest/editor/settings/tabs',
			[
				'contest'       => [
					'label' => esc_html__( 'Contest', 'totalcontest' ),
					'icon'  => 'megaphone',
					'tabs'  => [
						'submissions' => [
							'label' => esc_html__( 'Submissions', 'totalcontest' ),
							'icon'  => 'admin-settings',
						],
						'limitations' => [ 'label' => esc_html__( 'Limitations', 'totalcontest' ), 'icon' => 'lock' ],
						'frequency'   => [ 'label' => esc_html__( 'Frequency', 'totalcontest' ), 'icon' => 'backup' ],
					],
				],
				'vote'          => [
					'label' => esc_html__( 'Vote', 'totalcontest' ),
					'icon'  => 'marker',
					'tabs'  => [
						'type'        => [
							'label' => esc_html__( 'Type', 'totalcontest' ),
							'icon'  => 'admin-settings',
						],
						'limitations' => [ 'label' => esc_html__( 'Limitations', 'totalcontest' ), 'icon' => 'lock' ],
						'frequency'   => [ 'label' => esc_html__( 'Frequency', 'totalcontest' ), 'icon' => 'backup' ],
					],
				],
				'content'       => [
					'label' => esc_html__( 'Pages', 'totalcontest' ),
					'icon'  => 'admin-page',
				],
				'seo'           => [
					'label' => esc_html__( 'SEO', 'totalcontest' ),
					'icon'  => 'search',
					'tabs'  => [
						'contest'    => [ 'label' => esc_html__( 'Contest', 'totalcontest' ), 'icon' => 'laptop' ],
						'submission' => [ 'label' => esc_html__( 'Submission', 'totalcontest' ), 'icon' => 'feedback' ],
					],
				],
				'notifications' => [
					'label' => esc_html__( 'Notifications', 'totalcontest' ),
					'icon'  => 'email',
					'tabs'  => [
						'email'   => [ 'label' => esc_html__( 'Email', 'totalcontest' ), 'icon' => 'email' ],
						'push'    => [ 'label' => esc_html__( 'Push', 'totalcontest' ), 'icon' => 'format-status' ],
						'webhook' => [ 'label' => esc_html__( 'WebHook', 'totalcontest' ), 'icon' => 'admin-site' ],
					],
				],
				'customization' => [
					'label' => esc_html__( 'Customization', 'totalcontest' ),
					'icon'  => 'admin-plugins',
				],
			],
			$this
		);

		/**
		 * Filters the list of design tabs in contest editor.
		 *
		 * @param  array  $designTabs  Array of tabs [id => [label]].
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$designTabs = apply_filters(
			'totalcontest/filters/admin/editor/design/tabs',
			[
				'templates' => [ 'label' => esc_html__( 'Templates', 'totalcontest' ) ],
				'layout'    => [ 'label' => esc_html__( 'Layout', 'totalcontest' ) ],
				'colors'    => [ 'label' => esc_html__( 'Colors', 'totalcontest' ) ],
				'text'      => [ 'label' => esc_html__( 'Text', 'totalcontest' ) ],
				'advanced'  => [
					'label' => esc_html__( 'Advanced', 'totalcontest' ),
					'tabs'  => [
						'template-settings' => [ 'label' => esc_html__( 'Template Settings', 'totalcontest' ) ],
						'behaviours'        => [ 'label' => esc_html__( 'Behaviours', 'totalcontest' ) ],
						'effects'           => [ 'label' => esc_html__( 'Effects', 'totalcontest' ) ],
						'custom-css'        => [ 'label' => esc_html__( 'Custom CSS', 'totalcontest' ) ],
					],
				],
			],
			$this
		);


		/**
		 * Filters the list of integration tabs in contest editor.
		 *
		 * @param  array  $tabs  Array of tabs [id => [label, description, icon]].
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$integrationTabs = apply_filters(
			'totalcontest/filters/admin/contest/editor/integration/tabs',
			[
				'shortcode' => [
					'label'       => esc_html__( 'Shortcode', 'totalcontest' ),
					'description' => esc_html__( 'WordPress feature', 'totalcontest' ),
					'icon'        => 'editor-code',
				],
				'widget'    => [
					'label'       => esc_html__( 'Widget', 'totalcontest' ),
					'description' => esc_html__( 'WordPress feature', 'totalcontest' ),
					'icon'        => 'megaphone',
				],
				'link'      => [
					'label'       => esc_html__( 'Direct link', 'totalcontest' ),
					'description' => esc_html__( 'Standard link', 'totalcontest' ),
					'icon'        => 'admin-links',
				],
				'embed'     => [
					'label'       => esc_html__( 'Embed', 'totalcontest' ),
					'description' => esc_html__( 'External inclusion', 'totalcontest' ),
					'icon'        => 'admin-site',
				],
				'email'     => [
					'label'       => esc_html__( 'Email', 'totalcontest' ),
					'description' => esc_html__( 'Vote links', 'totalcontest' ),
					'icon'        => 'email',
				],
			],
			$this
		);


		/**
		 * Filters the list of available providers for embed fields.
		 *
		 * @param  array  $embedProviders  Array of providers.
		 *
		 * @return array
		 * @since 2.1.0
		 */
		$embedProviders = apply_filters( 'totalcontest/filters/admin/contest/editor/embed/providers', [
			'youtube',
			'facebook',
			'instagram',
			'twitter',
			'vimeo',
			'dailymotion',
			'flickr',
			'hulu',
			'scribd',
			'wordpress.tv',
			'slideshare',
			'soundcloud',
			'spotify',
			'imgur',
			'meetup.com',
			'animoto',
			'issuu',
			'mixcloud',
			'ted',
			'tumblr',
			'kickstarter',
			'cloudup',
			'reverbnation',
			'videopress',
			'reddit',
			'screencast',
			'amazon',
			'someecards',
			'crowdsignal',
		] );

		$dateTimeFormat = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );

		if ( ! current_user_can( 'edit_theme_options' ) ):
			unset( $integrationTabs['widget'] );
		endif;

		include_once __DIR__ . '/views/editor.php';
	}

	/**
	 * Editors sidebar actions.
	 */
	public function actions() {
		$actions = [];

		if ( current_user_can( 'edit_contests' ) ):
			$actions['submissions'] = [
				'label' => esc_html__( 'Submissions', 'totalcontest' ),
				'icon'  => 'images-alt2',
				'url'   => add_query_arg( [ 'post_type' => TC_SUBMISSION_CPT_NAME, 'contest' => $GLOBALS['post']->ID ],
				                          admin_url( 'edit.php' ) ),
			];
		endif;

		if ( current_user_can( 'manage_options' ) ):
			$actions['log'] = [
				'label' => esc_html__( 'Log', 'totalcontest' ),
				'icon'  => 'editor-table',
				'url'   => add_query_arg( [
					                          'post_type' => TC_CONTEST_CPT_NAME,
					                          'page'      => 'log',
					                          'contest'   => $GLOBALS['post']->ID,
				                          ], admin_url( 'edit.php' ) ),
			];
		endif;

		/**
		 * Filters the list of available action (side) in contest editor.
		 *
		 * @param  array  $actions  Array of actions [id => [label, icon, url]].
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$actions = apply_filters( 'totalcontest/filters/admin/contest/editor/actions', $actions );

		include_once __DIR__ . '/views/actions.php';
	}

	/**
	 * Save contest.
	 *
	 * @param $contestArgs
	 * @param $post
	 *
	 * @return mixed
	 */
	public function save( $contestArgs, $post ) {
		$contestId = absint( $post['ID'] );

		if ( ! empty( $contestArgs['post_content'] ) ):
			$settings = json_decode( wp_unslash( $contestArgs['post_content'] ), true );
			$defaults = TotalContest( 'contests.defaults' );

			/**
			 * Filters the settings before saving the contest.
			 *
			 * @param  array  $settings  Array of settings.
			 * @param  array  $contestArgs  Array of post args.
			 * @param  int  $contestId  Contest post ID.
			 *
			 * @return array
			 * @since 2.0.0
			 */
			$settings = apply_filters( 'totalcontest/filters/before/admin/contest/editor/save/settings',
			                           $settings,
			                           $contestArgs,
			                           $contestId,
			                           $this );

			// Purge CSS cache
			if ( ! empty( $settings['presetUid'] ) ):
				$cachedFile = wp_normalize_path( $this->env['cache']['path'] . "css/{$settings['presetUid']}.css" );
				$this->filesystem->delete( $cachedFile );
			endif;

			// Validations
			$numericFields = [
				'contest.submissions.perPage',
				'contest.limitations.quota.value',
				'contest.frequency.count',
				'contest.frequency.timeout',

				'vote.scale',
				'vote.limitations.quota.value',
				'vote.frequency.count',
				'vote.frequency.perItem',
				'vote.frequency.timeout',

				'design.layout.columns',
			];

			foreach ( $numericFields as $field ):
				$value    = Arrays::getDotNotation( $settings, $field );
				$settings = Arrays::setDotNotation( $settings, $field, absint( $value ) );
			endforeach;

			// Validate limitations (date based)
			foreach ( [ 'contest', 'vote' ] as $section ):
				$timePeriodStart = Arrays::getDotNotation( $settings, "{$section}.limitations.period.start", '' );
				if ( ! (bool) strtotime( $timePeriodStart ) ):
					$settings = Arrays::setDotNotation(
						$settings,
						"{$section}.limitations.period.start",
						''
					);
				endif;
				$timePeriodEnd = Arrays::getDotNotation( $settings, "{$section}.limitations.period.end", '' );
				if ( ! (bool) strtotime( $timePeriodEnd ) ):
					$settings = Arrays::setDotNotation(
						$settings,
						"{$section}.limitations.period.end",
						''
					);
				endif;
			endforeach;

			// Fields
			$fields = (array) Arrays::getDotNotation( $settings, 'contest.form.fields', [] );
			foreach ( $fields as $fieldIndex => $field ):
				// Validate field name
				$settings = Arrays::setDotNotation(
					$settings,
					"contest.form.fields.{$fieldIndex}.name",
					sanitize_title_with_dashes( Arrays::getDotNotation( $field, 'name', uniqid( 'untitled_', false ) ),
					                            '',
					                            'save' )
				);

				// Disable file-related validations when file upload is unchecked
				if ( empty( $field['validations']['file']['enabled'] ) && in_array( $field['type'],
				                                                                    [ 'video', 'audio' ] ) ):
					$settings = Arrays::setDotNotation( $settings,
					                                    "contest.form.fields.{$fieldIndex}.validations.size.enabled",
					                                    false );
					$settings = Arrays::setDotNotation( $settings,
					                                    "contest.form.fields.{$fieldIndex}.validations.length.enabled",
					                                    false );
					$settings = Arrays::setDotNotation( $settings,
					                                    "contest.form.fields.{$fieldIndex}.validations.formats.enabled",
					                                    false );
				endif;

				// Sort validations
				$validations = Arrays::getDotNotation( $settings, "contest.form.fields.{$fieldIndex}.validations" );
				$settings    = Arrays::setDotNotation( $settings,
				                                       "contest.form.fields.{$fieldIndex}.validations",
				                                       $validations );
			endforeach;

			// Generate a UID based on design settings
			$settings['presetUid'] = md5( json_encode( $settings['design'],
			                                           JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
			// Update schema version
			$settings['meta']['schema'] = $defaults['meta']['schema'];

			/**
			 * Filters the settings after validation to be saved.
			 *
			 * @param  array  $settings  Array of settings.
			 * @param  array  $contestArgs  Array of post args.
			 * @param  int  $contestId  Contest post ID.
			 *
			 * @return array
			 * @since 2.0.0
			 */
			$settings = apply_filters( 'totalcontest/filters/admin/contest/editor/save/settings',
			                           $settings,
			                           $contestArgs,
			                           $contestId,
			                           $this );

			$contestArgs['post_content'] = json_encode( $settings, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
			// Sanitize
			if ( ! current_user_can( 'unfiltered_html' ) ):
				$contestArgs['post_content'] = wp_kses_post( $contestArgs['post_content'] );
			endif;
			// Add slashes
			$contestArgs['post_content'] = wp_slash( $contestArgs['post_content'] );

			/**
			 * Filters the arguments that are passed back to wp_update_post to save the changes.
			 *
			 * @param  array  $contestArgs  Array of post args.
			 * @param  array  $settings  Array of settings.
			 * @param  int  $contestId  Contest post ID.
			 *
			 * @return array
			 * @since 2.0.0
			 * @see   Check wp_update_post documentaion for more details.
			 *
			 */
			$contestArgs = apply_filters( 'totalcontest/filters/admin/contest/editor/save/post',
			                              $contestArgs,
			                              $settings,
			                              $contestId,
			                              $this );
		endif;

		// Purge global cache
		Misc::purgePluginsCache();

		// Adjust redirect url
		add_filter( 'redirect_post_location', function ( $location ) {
			$params = [
				'tab' => empty( $_POST['totalcontest_current_tab'] ) ? null : urlencode( (string) $_POST['totalcontest_current_tab'] ),
			];

			return add_query_arg( $params, $location );
		} );

		return $contestArgs;
	}

	/**
	 * @return Model
	 */
	public function getContest() {
		return $this->contest;
	}
}
