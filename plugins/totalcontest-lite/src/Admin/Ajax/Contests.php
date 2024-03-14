<?php

namespace TotalContest\Admin\Ajax;

use TotalContestVendors\TotalCore\Contracts\Http\Request;

/**
 * Class Contests
 * @package TotalContest\Admin\Ajax
 * @since   1.0.0
 */
class Contests {
	/**
	 * @var Request $request
	 */
	protected $request;
	/**
	 * @var \WP_Post|int $contest
	 */
	protected $contest;
	/**
	 * @var \WP_Post|int $submission
	 */
	protected $submission;

	/**
	 * Contests constructor.
	 *
	 * @param Request $request
	 */
	public function __construct( Request $request ) {
		$this->request    = $request;
		$this->contest    = get_post( absint( $this->request->request( 'contest', 0 ) ) );
		$this->submission = get_post( absint( $this->request->request( 'submission', 0 ) ) );

		if ( $this->contest && $this->contest->post_type == TC_CONTEST_CPT_NAME && current_user_can( 'edit_contest', $this->contest->ID ) ):
			$this->contest = $this->contest->ID;
		endif;

		if ( $this->submission && $this->submission->post_type == TC_SUBMISSION_CPT_NAME && current_user_can( 'edit_contest', $this->submission->post_parent ) ):
			$this->submission = $this->submission->ID;
		endif;
	}

	/**
	 * Add to sidebar AJAX endpoint.
	 * @action-callback wp_ajax_totalcontest_contests_add_to_sidebar
	 */
	public function addToSidebar() {
		if ( ! $this->contest || ! current_user_can( 'edit_theme_options' ) ):
			status_header( 406 );
			wp_send_json_error( esc_html__( 'Invalid Contest ID.', 'totalcontest' ) );
		endif;

		$sidebar = (string) $this->request->request( 'sidebar', null );
		if ( is_registered_sidebar( $sidebar ) ):
			// Get sidebars
			$sidebarsWidgets     = wp_get_sidebars_widgets();
			$totalcontestWidgets = array_filter( (array) get_option( 'widget_totalcontest_contest', [ '_multiwidget' => 1 ] ) );

			// Prepare the new widget
			$widgetName    = 'totalcontest_contest-' . count( $totalcontestWidgets );
			$widgetOptions = [ 'title' => get_the_title( $this->contest ), 'contest' => $this->contest, 'screen' => 'vote' ];

			// Add to widgets
			$sidebarsWidgets[ $sidebar ][]                        = $widgetName;
			$totalcontestWidgets[ count( $totalcontestWidgets ) ] = $widgetOptions;

			// Save
			update_option( "widget_totalcontest_contest", $totalcontestWidgets );
			wp_set_sidebars_widgets( $sidebarsWidgets );

			wp_send_json_success( esc_html__( 'Widget added successfully.', 'totalcontest' ) );
		else:
			status_header( 406 );
			wp_send_json_error( esc_html__( 'Invalid Sidebar ID.', 'totalcontest' ) );
		endif;
	}

	/**
	 * Approve submission AJAX endpoint.
	 * @action-callback wp_ajax_totalcontest_contests_approve_submission
	 */
	public function approveSubmission() {
		if ( ! $this->submission && current_user_can( 'publish_contest_submissions', $this->submission, wp_get_post_parent_id( $this->submission ) ) ):
			status_header( 406 );
			wp_send_json_error( esc_html__( 'Invalid Submission ID.', 'totalcontest' ) );
		endif;

		wp_publish_post( $this->submission );

		wp_send_json_success( esc_html__( 'Approved.', 'totalcontest' ) );
	}

	/**
	 * Get categories.
	 * @action-callback wp_ajax_totalcontest_contests_get_categories
	 */
	public function getCategories() {
		$terms  = get_terms( [ 'taxonomy' => TC_SUBMISSION_CATEGORY_TAX_NAME, 'hide_empty' => false, 'fields' => 'id=>name' ] );
		$result = [];
		foreach ( $terms as $termId => $termName ):
			$result[] = [ 'id' => $termId, 'name' => $termName ];
		endforeach;

		wp_send_json_success( $result );
	}
}
