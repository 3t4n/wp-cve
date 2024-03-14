<?php
/* wppa-bestof-widget.php
* Package: wp-photo-album-plus
*
* display the best rated photos
* Version: 8.4.03.002
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

class BestOfWidget extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_bestof_widget', 'description' => __( 'Display thumbnails or owners of top rated photos', 'wp-photo-album-plus' ) );
		parent::__construct( 'wppa_bestof_widget', __( 'WPPA+ Best Of Photos', 'wp-photo-album-plus' ), $widget_ops );
    }

	/** @see WP_Widget::widget */
    function widget( $args, $instance ) {

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'bestof' );
		wppa_bump_mocc( $this->id );
        extract( $args );
		$instance 		= wppa_parse_args( (array) $instance, $this->get_defaults() );
		$widget_title 	= apply_filters( 'widget_title', $instance['title'] );
		$cache 			= wppa_cache_widget( $instance['cache'] );
		$cachefile 		= wppa_get_widget_cache_path( $this->id );

		// Logged in only and logged out?
		if ( wppa_checked( $instance['logonly'] ) && ! is_user_logged_in() ) {
			return;
		}

		// Cache?
		if ( $cache && wppa_is_file( $cachefile ) ) {
			wppa_echo( wppa_get_contents( $cachefile ) );
			wppa_update_option( 'wppa_cache_hits', wppa_get_option( 'wppa_cache_hits', 0 ) +1 );
			wppa_echo( wppa_widget_timer( 'show', $widget_title, true ) );
			wppa( 'in_widget', false );
			return;
		}

		// Other inits
		$widget_content = '';
		$page 			= in_array( $instance['linktype'], wppa( 'links_no_page' ) ) ? '' : wppa_get_the_landing_page( 'bestof_widget_linkpage', __( 'Best Of Photos', 'wp-photo-album-plus' ) );
		$count 			= $instance['count'] ? $instance['count'] : '10';
		$sortby 		= $instance['sortby'];
		$display 		= $instance['display'];
		$period 		= $instance['period'];
		$maxratings 	= wppa_checked( $instance['maxratings'] ) ? 'yes' : '';
		$meanrat		= wppa_checked( $instance['meanrat'] ) ? 'yes' : '';
		$ratcount 		= wppa_checked( $instance['ratcount'] ) ? 'yes' : '';
		$linktype 		= $instance['linktype'];
		$size 			= wppa_opt( 'widget_width' );
		$lineheight 	= wppa_opt( 'fontsize_widget_thumb' ) * 1.5;
		$total 			= $instance['totvalue'] ? 'yes' : '';

		$widget_content = "\n".'<!-- WPPA+ BestOf Widget start -->';

		$widget_content .= wppa_bestof_html( array ( 	'page' 			=> $page,
														'count' 		=> $count,
														'sortby' 		=> $sortby,
														'display' 		=> $display,
														'period' 		=> $period,
														'maxratings' 	=> $maxratings,
														'meanrat' 		=> $meanrat,
														'ratcount' 		=> $ratcount,
														'linktype' 		=> $linktype,
														'size' 			=> $size,
														'lineheight' 	=> $lineheight,
														'totvalue' 		=> $total,
														'cache' 		=> 'no',
														) );

		$widget_content .= '<div style="clear:both" data-wppa="yes"></div>';

		// Output
		$result = "\n" . $before_widget;
		if ( ! empty( $widget_title ) ) {
			$result .= $before_title . $widget_title . $after_title;
		}
		$result .= $widget_content . $after_widget;

		wppa_echo( $result );
		wppa_echo( wppa_widget_timer( 'show', $widget_title ) );

		// Cache?
		if ( $cache ) {
			wppa_save_cache_file( ['file' => $cachefile, 'data' => $result, 'other' => 'R'] );
		}

		wppa( 'in_widget', false );
    }

    /** @see WP_Widget::update */
    function update( $new_instance, $old_instance ) {

		// Completize all parms
		$instance = wppa_parse_args( $new_instance, $this->get_defaults() );

		// Sanitize certain args
		$instance['title'] 		= strip_tags( $instance['title'] );

		wppa_remove_widget_cache( $this->id );

        return $instance;

    }

    /** @see WP_Widget::form */
    function form( $instance ) {

		// Defaults
		$instance = wppa_parse_args( (array) $instance, $this->get_defaults() );

		// Widget Title
		wppa_echo(
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) ) );

		// Max number to diaplsy
		wppa_widget_number( $this, 'count', $instance['count'], __( 'Max number of thumbnails', 'wp-photo-album-plus' ), '1', '25' );

		// What to display
		$options 	= array( 	__( 'Photos', 'wp-photo-album-plus' ),
								__( 'Owners', 'wp-photo-album-plus' ),
								);
		$values 	= array(	'photo',
								'owner',
								);

		wppa_widget_selection( $this, 'display', $instance['display'], __( 'Select photos or owners', 'wp-photo-album-plus' ), $options, $values, array(), '' );

		// Period
		$options 	= array( 	__( 'Last week', 'wp-photo-album-plus' ),
								__( 'This week', 'wp-photo-album-plus' ),
								__( 'Last month', 'wp-photo-album-plus' ),
								__( 'This month', 'wp-photo-album-plus' ),
								__( 'Last year', 'wp-photo-album-plus' ),
								__( 'This year', 'wp-photo-album-plus' ),
								);
		$values 	= array( 	'lastweek',
								'thisweek',
								'lastmonth',
								'thismonth',
								'lastyear',
								'thisyear',
								);

		wppa_widget_selection( $this, 'period', $instance['period'], __( 'Limit to ratings given during', 'wp-photo-album-plus' ), $options, $values, array(), '' );

		// Sort by
		$options 	= array( 	__( 'Number of max ratings', 'wp-photo-album-plus' ),
								__( 'Mean value', 'wp-photo-album-plus' ),
								__( 'Number of votes', 'wp-photo-album-plus' ),
								__( 'Sum of all ratings', 'wp-photo-album-plus' ),
								);
		$values 	= array( 	'maxratingcount',
								'meanrating',
								'ratingcount',
								'totvalue',
								);

		wppa_widget_selection( $this, 'sortby', $instance['sortby'], __( 'Sort by', 'wp-photo-album-plus' ), $options, $values, array(), '' );

		// Number of max ratings
		wppa_widget_checkbox( $this, 'maxratings', $instance['maxratings'], __( 'Show number of max ratings', 'wp-photo-album-plus' ) );

		// Mean rating
		wppa_widget_checkbox( $this, 'meanrat', $instance['meanrat'], __( 'Show mean rating', 'wp-photo-album-plus' ) );

		// Number of ratings
		wppa_widget_checkbox( $this, 'ratcount', $instance['ratcount'], __( 'Show number of ratings', 'wp-photo-album-plus' ) );

		// Total value
		wppa_widget_checkbox( $this, 'totvalue', $instance['totvalue'], __( 'Show the sum of all ratings', 'wp-photo-album-plus' ) );

		// Link to
		$options 	= array( 	__( '--- none ---', 'wp-photo-album-plus' ),
								__( 'The authors album(s)', 'wp-photo-album-plus' ),
								__( 'The photos in the authors album(s), thumbnails', 'wp-photo-album-plus' ),
								__( 'The photos in the authors album(s), slideshow', 'wp-photo-album-plus' ),
								__( 'All the authors photos, thumbnails', 'wp-photo-album-plus' ),
								__( 'All the authors photos, slideshow', 'wp-photo-album-plus' ),
								__( 'Lightbox single image', 'wp-photo-album-plus' ),
								);
		$values 	= array( 	'none',
								'owneralbums',
								'ownerphotos',
								'ownerphotosslide',
								'upldrphotos',
								'upldrphotosslide',
								'lightboxsingle',
								);

		wppa_widget_selection( $this, 'linktype', $instance['linktype'], __( 'Link to', 'wp-photo-album-plus' ), $options, $values, array(), '' );

		// Loggedin only
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );

		// Cache
		wppa_widget_checkbox( $this, 'cache', $instance['cache'], __( 'Cache this widget', 'wp-photo-album-plus' ) );

    }

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 	=> __( 'Best Of Photos', 'wp-photo-album-plus' ),
							'count' 	=> '10',
							'sortby' 	=> 'maxratingcount',
							'display' 	=> 'photo',
							'period' 	=> 'thisweek',
							'maxratings'=> 'no',
							'meanrat' 	=> 'no',
							'ratcount' 	=> 'no',
							'linktype' 	=> 'none',
							'totvalue' 	=> '',
							'logonly' 	=> 'no',
							'cache' 	=> '0',
							);
		return $defaults;
	}

} // class BestOfWidget

// register BestOfWidget widget
add_action('widgets_init', 'wppa_register_BestOfWidget' );

function wppa_register_BestOfWidget() {
	register_widget("BestOfWidget");
}