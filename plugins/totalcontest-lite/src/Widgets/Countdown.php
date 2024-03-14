<?php

namespace TotalContest\Widgets;

use TotalContestVendors\TotalCore\Helpers\Arrays;


/**
 * Class Countdown
 * @package TotalContest\Widgets
 */
class Countdown extends Base {
	public function __construct() {
		$widgetOptions = [
			'classname'   => 'totalcontest-widget-countdown',
			'description' => esc_html__( 'TotalContest contest countdown widget', 'totalcontest' ),
		];
		parent::__construct( 'totalcontest_countdown', esc_html__( '[TotalContest] Contest countdown', 'totalcontest' ), $widgetOptions );
	}

	public function content( $args, $instance ) {
		if ( ! empty( $instance['contest'] ) ):
			$contest = TotalContest( 'contests.repository' )->getById( $instance['contest'] );
			$type    = $instance['type'];
			$format  = $instance['format'];
			$until   = $instance['until'];

			if ( $until === 'start' ):
				$interval = $contest->getTimeLeftToStart( $type );
			elseif ( $until === 'end' ):
				$interval = $contest->getTimeLeftToEnd( $type );
			endif;

			if ( isset( $interval ) && $interval instanceof \DateInterval ):
				echo (string) $interval->format( $format );
			endif;
		endif;
	}

	public function fields( $fields, $instance ) {
		$instance = Arrays::parse( $instance, [
			'contest' => null,
			'type'    => 'vote',
			'format'  => '%a days and %h hours',
			'until'   => 'start',
		] );
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

		// Type
		$fields['type'] = TotalContest( 'form.field.select' )->setOptions( [
			'class'   => 'widefat',
			'name'    => esc_attr( $this->get_field_name( 'type' ) ),
			'label'   => esc_html__( 'Type:', 'totalcontest' ),
			'options' => [
				'contest' => esc_html__( 'Contest', 'totalcontest' ),
				'vote'    => esc_html__( 'Voting', 'totalcontest' ),
			],
		] )->setValue( $instance['type'] ?: 'contest' );

		// Format
		$fields['format'] = TotalContest( 'form.field.text' )->setOptions( [
			'class' => 'widefat',
			'name'  => esc_attr( $this->get_field_name( 'format' ) ),
			'label' => esc_html__( 'Format:', 'totalcontest' ),
		] )->setValue( $instance['format'] ?: '%a days and %h hours' );

		// Until
		$fields['until'] = TotalContest( 'form.field.select' )->setOptions( [
			'class'   => 'widefat',
			'name'    => esc_attr( $this->get_field_name( 'until' ) ),
			'label'   => esc_html__( 'Until:', 'totalcontest' ),
			'options' => [
				'start' => esc_html__( 'Start', 'totalcontest' ),
				'end'   => esc_html__( 'End', 'totalcontest' ),
			],
		] )->setValue( $instance['until'] ?: 'start' );

		return $fields;
	}
}
