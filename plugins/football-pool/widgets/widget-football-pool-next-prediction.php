<?php

/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

/**
 * Widget: Countdown to next prediction Widget
 */

defined( 'ABSPATH' ) or die( 'Cannot access widgets directly.' );
add_action( 'widgets_init', function() { register_widget( 'Football_Pool_Next_Prediction_Widget' ); } );

// dummy var for translation files
$fp_translate_this = __( 'Countdown Next Prediction Widget', 'football-pool' );
$fp_translate_this = __( 'this widget displays the time that is left to predict the next match (optionally only for a given team).', 'football-pool' );
$fp_translate_this = __( 'countdown', 'football-pool' );
$fp_translate_this = __( 'Team', 'football-pool' );
$fp_translate_this = __( 'Also show when not logged in?', 'football-pool' );
$fp_translate_this = __( 'Format', 'football-pool' );
$fp_translate_this = __( 'Format string', 'football-pool' );
$fp_translate_this = __( 'see help page for more info', 'football-pool' );

class Football_Pool_Next_Prediction_Widget extends Football_Pool_Widget {
	protected $match;
	protected $widget = array(
		'name' => 'Countdown Next Prediction Widget',
		'description' => 'this widget displays the time that is left to predict the next match (optionally only for a given team).',
		'do_wrapper' => true, 
		
		'fields' => array(
			array(
				'name' => 'Title',
				'desc' => '',
				'id' => 'title',
				'type' => 'text',
				'std' => 'countdown'
			),
			array(
				'name' => 'Format',
				'desc' => '',
				'id' => 'format',
				'type' => 'select',
				'options' => [] // get data later on
			),
			array(
				'name' => 'Format string',
				'desc' => 'see help page for more info',
				'id' => 'format_string',
				'type' => 'text',
				'std' => ''
			),
			array(
				'name' => 'Team',
				'desc' => '',
				'id' => 'team_id',
				'type' => 'select',
				'options' => [] // get data from the database later on
			),
			array(
				'name' => 'Also show when not logged in?',
				'desc' => '',
				'id' => 'all_users',
				'type' => 'checkbox',
			),
		)
	);
	
	public function html( $title, $args, $instance ) {
		extract( $args );
		
		if ( ! isset( $instance['format'] ) ) $instance['format'] = 3;
		
		$teams = new Football_Pool_Teams;
		$statisticspage = Football_Pool::get_page_link( 'statistics' );
		$predictionpage = Football_Pool::get_page_link( 'pool' ) . '#match-' . $this->matches[0]['id'] . '-1';
		$predictionpage = apply_filters( 'footballpool_widget_html_next-prediction_predictionpage'
										, $predictionpage, $this->matches );
		$teampage = Football_Pool::get_page_link( 'teams' );
		
		$output = '';
		if ( $title !== '' ) {
			/** @var string $before_title */
			/** @var string $after_title */
			$output .= $before_title . $title . $after_title;
		}
		
		$countdown_date = new DateTime( Football_Pool_Utils::date_from_gmt( $this->matches[0]['play_date'] ) );
		$year  = $countdown_date->format( 'Y' );
		$month = $countdown_date->format( 'm' );
		$day   = $countdown_date->format( 'd' );
		$hour  = $countdown_date->format( 'H' );
		$min   = $countdown_date->format( 'i' );
		$sec = 0;
		
		$id = Football_Pool_Utils::get_counter_value( 'fp_countdown_id' );
		
		$extra_texts = sprintf(
			"{'pre_before':'%1\$s','post_before':'%2\$s','pre_after':'%3\$s','post_after':'%4\$s'}"
			, esc_js( _x( 'Just ', "Used in the Next prediction widget (don't forget the space at the end of the string)", 'football-pool' ) )
			, esc_js( _x( ' until', "Used in the Next prediction widget (don't forget the space at the start of the string)", 'football-pool' ) )
			, esc_js( _x( 'started ', "Used in the Next prediction widget (don't forget the space at the end of the string)", 'football-pool' ) )
			, esc_js( _x( ' ago:', "Used in the Next prediction widget (don't forget the space at the start of the string)", 'football-pool' ) )
		);
		
		if ( ! array_key_exists( 'format_string', $instance ) || $instance['format_string'] == '' ) {
			switch ( $instance['format'] ) {
				case 1:
					$format_string = '{s} {sec}';
					break;
				case 2:
					$format_string = '{d} {days}, {h} {hrs}, {m} {min}, {s} {sec}';
					break;
				case 3:
					$format_string = '{h} {hrs}, {m} {min}, {s} {sec}';
					break;
				case 4:
					$format_string = '{d} {days}, {h} {hrs}, {m} {min}';
					break;
				case 5:
					$format_string = '{h} {hrs}, {m} {min}';
					break;
			}
		} else {
			$format_string = $instance['format_string'];
		}
		
		$format_string = Football_Pool_Utils::js_string_escape( $format_string );

		/** @noinspection HtmlUnknownTarget */
		$output .= sprintf( '<div class="wrapper next-prediction-countdown"><p><a href="%1$s" title="%3$s" id="next-prediction-countdown-%2$s">&nbsp;</a></p>'
				, $predictionpage
				, $id
				, esc_attr__( 'click to enter prediction', 'football-pool' )
		);
		/** @noinspection CommaExpressionJS */
		$output .= "<script>
				FootballPool.countdown( '#next-prediction-countdown-{$id}', {$extra_texts}, {$year}, {$month}, {$day}, {$hour}, {$min}, {$sec}, {$instance['format']}, '{$format_string}' );
				window.setInterval( function() { FootballPool.countdown( '#next-prediction-countdown-{$id}', {$extra_texts}, {$year}, {$month}, {$day}, {$hour}, {$min}, {$sec}, {$instance['format']}, '{$format_string}' ); }, 1000 );
				</script>";
		
		foreach ( $this->matches as $match ) {
			if ( $teams->show_team_links ) {
				$url_home = esc_url( add_query_arg( array( 'team' => $match['home_team_id'] ), $teampage ) );
				$url_away = esc_url( add_query_arg( array( 'team' => $match['away_team_id'] ), $teampage ) );
				$team_str = '<a href="%s">%s</a>';
			} else {
				$url_home = $url_away = '';
				$team_str = '%s%s';
			}
			
			$home_team = $away_team = '';
			if ( isset( $teams->team_names[(int) $match['home_team_id']] ) ) {
				$home_team = Football_Pool_Utils::xssafe( $teams->team_names[(int) $match['home_team_id']] );
				$home_team = apply_filters( 'footballpool_widget_html_next-prediction_home_team'
											, $home_team
											, $match['home_team_id'] );
			}
			if ( isset( $teams->team_names[(int) $match['away_team_id']] ) ) {
				$away_team = Football_Pool_Utils::xssafe( $teams->team_names[(int) $match['away_team_id']] );
				$away_team = apply_filters( 'footballpool_widget_html_next-prediction_away_team'
											, $away_team
											, $match['away_team_id'] );
			}

			/** @noinspection PhpFormatFunctionParametersMismatchInspection */
			$match_line = sprintf( "<p><span class='home-team'>{$team_str}</span>
										<span> - </span><span class='away-team'>{$team_str}</span></p>"
									, $url_home
									, $home_team
									, $url_away
									, $away_team
								);
			$output     .= apply_filters( 'footballpool_widget_html_next-prediction_matchline', $match_line, $match );
		}
		
		$output .= '</div>';
		
		echo apply_filters( 'footballpool_widget_html_next-prediction', $output );
	}

	public function initiate_widget_dynamic_fields() {
		$teams = new Football_Pool_Teams;
		// format options
		$this->widget['fields'][1]['options'] = array(
			2 => sprintf( '%s, %s, %s, %s'
				, __( 'days', 'football-pool' )
				, __( 'hours', 'football-pool' )
				, __( 'minutes', 'football-pool' )
				, __( 'seconds', 'football-pool' )
			),
			4 => sprintf( '%s, %s, %s'
				, __( 'days', 'football-pool' )
				, __( 'hours', 'football-pool' )
				, __( 'minutes', 'football-pool' )
			),
			3 => sprintf( '%s, %s, %s'
				, __( 'hours', 'football-pool' )
				, __( 'minutes', 'football-pool' )
				, __( 'seconds', 'football-pool' )
			),
			5 => sprintf( '%s, %s'
				, __( 'hours', 'football-pool' )
				, __( 'minutes', 'football-pool' )
			),
			1 => __( 'only seconds', 'football-pool' )
		);
		// get the team options from the database
		$teams = $teams->team_names;
		$options = [];
		$options[0] = '';
		foreach ( $teams as $team_id => $team_name ) {
			$options[$team_id] = Football_Pool_Utils::xssafe( $team_name );
		}
		$this->widget['fields'][3]['options'] = $options;
	}

	public function __construct() {
		$classname = str_replace( '_', '', get_class( $this ) );
		parent::__construct( 
			$classname, 
			( isset( $this->widget['name'] ) ? $this->widget['name'] : $classname ), 
			$this->widget['description']
		);
	}
	
	public function widget( $args, $instance ) {
		global $pool;
		// only for logged in users?
		if ( isset( $instance['all_users'] ) && $instance['all_users'] != 'on' && ! is_user_logged_in() ) return;
		
		if ( isset( $instance['team_id'] ) && $instance['team_id'] > 0 ) {
			$next_matches = $pool->matches->get_next_match( null, $instance['team_id'] );
		} else {
			$next_matches = $pool->matches->get_next_match();
		}
		// do not output a widget if there is no next match
		if ( $next_matches !== false ) {
			$this->matches = $next_matches;
			
			//initializing variables
			$this->widget['number'] = $this->number;
			if ( isset( $instance['title'] ) )
				$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
			else
				$title = '';
			
			$do_wrapper = ( !isset( $this->widget['do_wrapper'] ) || $this->widget['do_wrapper'] );
			
			if ( $do_wrapper ) 
				echo $args['before_widget'];
			
			$this->widget_html( $title, $args, $instance );
				
			if ( $do_wrapper ) 
				echo $args['after_widget'];
		}
	}
}
