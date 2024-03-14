<?php

namespace TotalContest\Widgets;

//@TODO: FIX THIS
use TotalContestVendors\TotalCore\Helpers\Arrays;

/**
 * Class Contest
 * @package TotalContest\Widgets
 */
class Contest extends Base {
	public function __construct() {
		$widgetOptions = [
			'classname'   => 'totalcontest-widget-contest',
			'description' => esc_html__( 'TotalContest contest widget', 'totalcontest' ),
		];
		parent::__construct( 'totalcontest_contest', esc_html__( '[TotalContest] Contest', 'totalcontest' ), $widgetOptions );
	}

	public function content( $args, $instance ) {
		if ( ! empty( $instance['contest'] ) ):
			$contest = TotalContest( 'contests.repository' )->getById( $instance['contest'] )->setMenuVisibility( false )->setScreen( $instance['screen'] );
			if ( $instance['screen'] === 'contest.content' ):
				$contest->setCustomPageId( $instance['pageId'] );
			endif;

			echo $contest->render();
		endif;
	}

	public function fields( $fields, $instance ) {
		$instance = Arrays::parse( $instance, [ 'contest' => null, 'screen' => 'contest.landing', 'pageId' => null ] );
		// Contest field
		foreach ( (array) get_posts( 'post_type=contest&posts_per_page=-1' ) as $post ):
			$contests[ $post->ID ] = $post->post_title;
		endforeach;
		$fields['contest'] = TotalContest( 'form.field.select' )->setOptions( [
			'class'   => 'widefat',
			'name'    => esc_attr( $this->get_field_name( 'contest' ) ),
			'label'   => esc_html__( 'Contest:', 'totalcontest' ),
			'options' => $contests,
		] )->setValue( $instance['contest'] ?: '' );

		// Screen
		$fields['screen'] = TotalContest( 'form.field.select' )->setOptions(
			[
				'class'      => 'widefat totalcontest-page-selector',
				'name'       => esc_attr( $this->get_field_name( 'screen' ) ),
				'label'      => esc_html__( 'Screen:', 'totalcontest' ),
				'options'    => [
					'contest.landing'     => esc_html__( 'Home', 'totalcontest' ),
					'contest.participate'       => esc_html__( 'Participate', 'totalcontest' ),
					'contest.submissions' => esc_html__( 'Submissions', 'totalcontest' ),
					'contest.content'     => esc_html__( 'Custom page', 'totalcontest' ),
				],
				'attributes' => [
					'onchange' => 'jQuery(this).closest(".widget-content").find(".totalcontest-pageid").toggle(jQuery(this).val() == "contest.content")',
				],
			]
		)->setValue( $instance['screen'] ?: 'contest.landing' );

		// Custom page
		$hidden           = $instance['screen'] === 'contest.content' ? '' : 'style="display: none"';
		$fields['pageId'] = TotalContest( 'form.field.text' )->setOptions( [
			'class'    => 'widefat',
			'name'     => esc_attr( $this->get_field_name( 'pageId' ) ),
			'label'    => esc_html__( 'Page ID:', 'totalcontest' ),
			'template' => '<div class="totalcontest-form-field totalcontest-column-full totalcontest-pageid" ' . $hidden . '>{{label}}{{field}}</div>',
		] )->setValue( $instance['pageId'] ?: '' );

		return $fields;
	}
}
