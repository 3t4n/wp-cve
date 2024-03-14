<?php

namespace TotalContest\Admin\Submission;

use TotalContest\Contracts\Contest\Model as ContestModel;
use TotalContest\Contracts\Submission\Model as SubmissionModel;
use TotalContest\Contracts\Submission\Repository;
use TotalContest\Form\Factory;
use TotalContestVendors\TotalCore\Contracts\Foundation\Environment;
use TotalContestVendors\TotalCore\Contracts\Http\Request;
use TotalContestVendors\TotalCore\Helpers\Misc;
use TotalContestVendors\TotalCore\Helpers\Tracking;

/**
 * Class Editor
 *
 * @package TotalContest\Admin\Submission
 */
class Editor {
	/**
	 * @var ContestModel $contest
	 */
	protected $contest;
	/**
	 * @var SubmissionModel $submission
	 */
	protected $submission;
	/**
	 * @var array $settings
	 */
	protected $settings = [];
	/**
	 * @var Environment $env
	 */
	protected $env;
	/**
	 * @var Repository $submissionRepository
	 */
	protected $submissionRepository;
	/**
	 * @var Factory
	 */
	protected $formFactory;
	/**
	 * @var Request
	 */
	private $request;

	/**
	 * Editor constructor.
	 *
	 * @param  Request  $request
	 * @param  Repository  $submissionRepository
	 * @param  Factory  $formFactory
	 * @param  Environment  $env
	 */
	public function __construct( Request $request, Repository $submissionRepository, Factory $formFactory, $env ) {
		$this->request              = $request;
		$this->submissionRepository = $submissionRepository;
		$this->env                  = $env;
		$this->formFactory          = $formFactory;
		// Variables
		add_action( 'admin_enqueue_scripts', [ $this, 'prepareVariables' ] );
		// Assets
		add_action( 'admin_enqueue_scripts', [ $this, 'assets' ] );
		// Editor
		add_action( 'edit_form_after_title', [ $this, 'content' ] );
		// Actions
		add_action( 'submitpost_box', [ $this, 'actions' ] );
		// Save submission
		add_filter( 'wp_insert_post_data', [ $this, 'save' ], 10, 2 );

		// Remove WP filters
		if ( function_exists( 'wp_remove_targeted_link_rel_filters' ) ) {
			wp_remove_targeted_link_rel_filters();
		}
		// Menu
		add_filter( 'parent_file', [ $this, 'parentMenu' ] );
		add_filter( 'submenu_file', [ $this, 'subMenu' ] );
	}

	/**
	 * Prepare variables.
	 */
	public function prepareVariables() {
		$this->submission = $this->submissionRepository->getById( $GLOBALS['post']->ID );

		if ( ! $this->submission ):
			wp_die( 'It seems like this is an orphan submission.' );
		endif;

		$this->contest = $this->submission->getContest();
		if ( ! empty( $GLOBALS['post'] ) ):
			$this->settings             = wp_unslash( json_decode( $GLOBALS['post']->post_content, true ) );
			$this->settings['fields']   = is_string( $this->settings['fields'] ) ? unserialize( base64_decode( $this->settings['fields'] ) ) : $this->settings['fields'];
			$this->settings['contents'] = is_string( $this->settings['contents'] ) ? unserialize( base64_decode( $this->settings['contents'] ) ) : $this->settings['contents'];
		endif;

		foreach ( $this->contest->getFormFieldsDefinitions() as $fieldName => $definition ) {
			$this->settings['fields'][ $fieldName ] = isset( $this->settings['fields'][ $fieldName ] ) ? $this->settings['fields'][ $fieldName ] : '';
		}
	}


	/**
	 * Page content.
	 */
	public function content() {
		include_once __DIR__ . '/Views/editor.php';
	}

	/**
	 * Actions.
	 */
	public function actions() {
		$actions = [];

		if ( current_user_can( 'edit_contest', $GLOBALS['post']->post_parent ) ):
			$actions['submissions'] = [
				'label' => esc_html__( 'Contest', 'totalcontest' ),
				'icon'  => 'megaphone',
				'url'   => $this->contest->getAdminEditLink(),
			];
		endif;

		if ( current_user_can( 'manage_options' ) ):
			$actions['log'] = [
				'label' => esc_html__( 'Log', 'totalcontest' ),
				'icon'  => 'editor-table',
				'url'   => add_query_arg( [
					                          'post_type'  => TC_CONTEST_CPT_NAME,
					                          'page'       => 'log',
					                          'contest'    => $GLOBALS['post']->post_parent,
					                          'submission' => $GLOBALS['post']->ID,
				                          ],
				                          admin_url( 'edit.php' ) ),
			];
		endif;

		/**
		 * Filters the list of available action (side) in submission editor.
		 *
		 * @param  array  $actions  Array of actions [id => [label, icon, url]].
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$actions = apply_filters( 'totalcontest/filters/admin/submission/editor/actions', $actions );

		include_once __DIR__ . '/Views/actions.php';
	}

	/**
	 * Assets.
	 */
	public function assets() {
		// Disable auto save
		wp_dequeue_script( 'autosave' );

		// WP Media
		wp_enqueue_media();

		// TotalContest
		wp_enqueue_script( 'totalcontest-admin-submission-editor' );
		wp_enqueue_style( 'totalcontest-admin-submission-editor' );

		/**
		 * Filters the settings of submission passed to frontend controller.
		 *
		 * @param  array  $settings  Array of settings.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$settings = apply_filters( 'totalcontest/filters/admin/submission/editor/settings', $this->settings );

		/**
		 * Filters the information passed to frontend controller.
		 *
		 * @param  array  $information  Array of values [key => value].
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$information = apply_filters(
			'totalcontest/filters/admin/submission/editor/information',
			[
				'ratings' => $this->submission->getDetailedRatings(),
				'votes'   => $this->submission->getVotes(),
			]
		);

		// Send JSON to TotalContest frontend controller
		wp_localize_script( 'totalcontest-admin-submission-editor', 'TotalContestSettings', $settings );
		wp_localize_script( 'totalcontest-admin-submission-editor', 'TotalContestDefaults', [] );
		wp_localize_script( 'totalcontest-admin-submission-editor', 'TotalContestInformation', $information );
	}

	/**
	 * Save submission.
	 *
	 * @param $submissionArgs
	 * @param $post
	 *
	 * @return mixed
	 */
	public function save( $submissionArgs, $post ) {
		$this->prepareVariables();

		$submissionId = $post['ID'];

		if ( ! empty( $submissionArgs['post_content'] ) ):
			$settings = json_decode( wp_unslash( $submissionArgs['post_content'] ), true );

			/**
			 * Filters the settings of the submission.
			 *
			 * @param  array  $settings  Array of settings.
			 * @param  array  $submissionArgs  Array of post args.
			 * @param  int  $submissionId  Submission post ID.
			 *
			 * @return array
			 * @since 2.0.0
			 */
			$settings = apply_filters( 'totalcontest/filters/admin/submission/editor/save/settings',
			                           $settings,
			                           $submissionArgs,
			                           $submissionId,
			                           $this );

			$submissionArgs['post_content'] = json_encode( $settings, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
			// Sanitize
			if ( ! current_user_can( 'unfiltered_html' ) ):
				$submissionArgs['post_content'] = wp_kses_post( $submissionArgs['post_content'] );
			endif;
			// Add slashes
			$submissionArgs['post_content'] = wp_slash( $submissionArgs['post_content'] );

			/**
			 * Filters the arguments that are passed back to wp_update_post to save the changes.
			 *
			 * @param  array  $submissionArgs  Array of post args.
			 * @param  array  $settings  Array of settings.
			 * @param  int  $submissionId  Submission post ID.
			 *
			 * @return array
			 * @since 2.0.0
			 * @see   Check wp_update_post documentaion for more details.
			 *
			 */
			$submissionArgs = apply_filters( 'totalcontest/filters/admin/submission/editor/save/post',
			                                 $submissionArgs,
			                                 $settings,
			                                 $submissionId,
			                                 $this );
		endif;

		// Sync votes
		$scale              = $this->contest->getVoteScale();
		$meta               = [];
		$criteriaCumulative = 0;

		$votes         = absint( $this->request->post( 'totalcontest.votes' ) );
		$votesOverride = absint( $this->request->post( 'totalcontest.votes_override' ) );
		$ratings       = $this->request->post( 'totalcontest.ratings', [] );

		if ( $votes !== $votesOverride ):
			$meta['_tc_votes'] = $votesOverride;
		endif;

		foreach ( $ratings as $criterionIndex => $rating ):
			$votes              = absint( $rating['votes'] );
			$votesOverride      = absint( $rating['votes_override'] );
			$cumulative         = absint( $rating['cumulative'] );
			$cumulativeOverride = absint( $rating['cumulative_override'] );
			$criterionRate      = (float) ( $cumulativeOverride / ( $votesOverride ?: 1 ) );
			$criteriaCumulative += $criterionRate;

			if ( $votes !== $votesOverride || $cumulative !== $cumulativeOverride ):
				$meta["_tc_{$criterionIndex}:{$scale}_votes"]      = $votesOverride;
				$meta["_tc_{$criterionIndex}:{$scale}_cumulative"] = $cumulativeOverride;
				$meta["_tc_{$criterionIndex}:{$scale}_rate"]       = $criterionRate;
			endif;
		endforeach;

		if ( count( $meta ) > 2 ):
			$criteria         = $this->contest->getVoteCriteria();
			$meta['_tc_rate'] = (float) ( $criteriaCumulative / ( count( $criteria ) ?: 1 ) );
		endif;

		// Update votes and ratings
		foreach ( $meta as $key => $value ):
			update_post_meta( $submissionId, $key, $value );
		endforeach;

		// Purge global cache
		Misc::purgePluginsCache();

		// Adjust redirect url
		add_filter( 'redirect_post_location', function ( $location ) {
			$params = [
				'tab' => empty( $_POST['totalcontest_current_tab'] ) ? null : urlencode( (string) $_POST['totalcontest_current_tab'] ),
			];

			return add_query_arg( $params, $location );
		} );

		return $submissionArgs;
	}

	/**
	 * @return string
	 */
	public function parentMenu() {
		return 'edit.php?post_type=' . TC_CONTEST_CPT_NAME;
	}

	/**
	 * @return string
	 */
	public function subMenu() {
		return 'edit.php?post_type=' . TC_SUBMISSION_CPT_NAME;
	}
}
