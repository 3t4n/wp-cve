<?php

namespace TotalContest\Widgets;

use TotalContestVendors\TotalCore\Helpers\Arrays;


/**
 * Class Submission
 * @package TotalContest\Widgets
 */
class Submission extends Base {
	public function __construct() {
		$widgetOptions = [
			'classname'   => 'totalcontest-widget-submission',
			'description' => esc_html__( 'TotalContest submission widget', 'totalcontest' ),
		];
		parent::__construct( 'totalcontest_submission', esc_html__( '[TotalContest] Submission', 'totalcontest' ), $widgetOptions );
	}

	/**
	 * @param $args
	 * @param $instance
	 */
	public function content( $args, $instance ) {
		if ( ! empty( $instance['submission'] ) ):
			$submission = TotalContest( 'submissions.repository' )->getById( $instance['submission'] );
			if ( $submission ):
				$submission->getContest()->setMenuVisibility( false );
				echo $submission->render();
			endif;
		endif;
	}

	/**
	 * @param $fields
	 * @param $instance
	 *
	 * @return mixed
	 */
	public function fields( $fields, $instance ) {
		$instance = Arrays::parse( $instance, [
			'submission' => null,
		] );
		// Contest field
		$fields['submission'] = TotalContest( 'form.field.text' )->setOptions( [
			'class' => 'widefat',
			'name'  => esc_attr( $this->get_field_name( 'submission' ) ),
			'label' => esc_html__( 'Submission ID:', 'totalcontest' ),
		] )->setValue( $instance['submission'] ?: '' );

		return $fields;
	}
}
