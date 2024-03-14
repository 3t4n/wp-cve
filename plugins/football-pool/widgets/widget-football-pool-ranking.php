<?php

/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

/**
 * Widget: Ranking Widget
 */

defined( 'ABSPATH' ) or die( 'Cannot access widgets directly.' );
add_action( 'widgets_init', function() { register_widget( 'Football_Pool_Ranking_Widget' ); } );

// dummy var for translation files
$fp_translate_this = __( 'Ranking Widget', 'football-pool' );
$fp_translate_this = __( 'this widget displays the top X players in the pool.', 'football-pool' );
$fp_translate_this = __( 'standing', 'football-pool' );
$fp_translate_this = __( 'Ranking', 'football-pool' );
$fp_translate_this = __( 'Number of users to show', 'football-pool' );
$fp_translate_this = __( 'Show players from this league', 'football-pool' );

class Football_Pool_Ranking_Widget extends Football_Pool_Widget {
	protected $widget = array(
		'name' => 'Ranking Widget',
		'description' => 'this widget displays the top X players in the pool.',
		'do_wrapper' => true, 
		
		'fields' => array(
			array(
				'name' => 'Title',
				'desc' => '',
				'id' => 'title',
				'type' => 'text',
				'std' => 'standing'
			),
			array(
				'name'    => 'Ranking',
				'desc' => '',
				'id'      => 'ranking_id',
				'type'    => 'select',
				'options' => [] // get data from the database later on
			),
			array(
				'name' => 'Number of users to show',
				'desc' => '',
				'id' => 'num_users',
				'type' => 'text',
				'std' => '5'
			),
			array(
				'name'    => 'Show players from this league',
				'desc' => '',
				'id'      => 'league',
				'type'    => 'select',
				'options' => [] // get data from the database later on
			),
		)
	);

	public function initiate_widget_dynamic_fields() {
		$pool = new Football_Pool_Pool();
		// get the ranking-options from the database
		$rankings = $pool->get_rankings();
		$options = [];
		foreach ( $rankings as $ranking ) {
			$options[$ranking['id']] = Football_Pool_Utils::xssafe( $ranking['name'] );
		}
		$this->widget['fields'][1]['options'] = $options;
		// get the league-options from the database
		$leagues = $pool->get_leagues();
		$options = [];
		foreach ( $leagues as $league ) {
			$options[$league['league_id']] = Football_Pool_Utils::xssafe( $league['league_name'] );
		}
		$this->widget['fields'][3]['options'] = $options;
	}

	public function html( $title, $args, $instance ) {
		global $pool;

		extract( $args );
		
		$num_users = isset( $instance['num_users'] ) ? (int) $instance['num_users'] : 5;
		$league = ! empty( $instance['league'] ) ? $instance['league'] : FOOTBALLPOOL_LEAGUE_ALL;
		$ranking_id = ! empty( $instance['ranking_id'] ) ? $instance['ranking_id'] : FOOTBALLPOOL_RANKING_DEFAULT;
		
		if ( $title !== '' ) {
			/** @var string $before_title */
			/** @var string $after_title */
			echo $before_title, $title, $after_title;
		}
		
		$user_id = get_current_user_id();

//		$userpage = Football_Pool::get_page_link( 'user' );
		
		$ranking = $pool->get_pool_ranking_limited( $league, $num_users, $ranking_id );
		if ( count( $ranking ) > 0 ) {
			$users = [];
			foreach ( $ranking as $row ) $users[] = $row['user_id'];
			
			echo $pool->print_pool_ranking( $league, $user_id, $users, $ranking, $ranking_id, 'widget', $num_users );
		} else {
			printf( '<p>%s</p>', __( 'No match data available.', 'football-pool' ) );
		}
	}
	
	public function __construct() {
		$classname = str_replace( '_', '', get_class( $this ) );
		
		parent::__construct( 
			$classname, 
			( isset( $this->widget['name'] ) ? $this->widget['name'] : $classname ), 
			$this->widget['description']
		);
	}
}
