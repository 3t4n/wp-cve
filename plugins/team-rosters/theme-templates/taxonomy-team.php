<?php
/**
 * The template for displaying Team Archive pages using the Team Rosters plugin.
 * This will create a 'gallery view' of the team.
 *
  *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014-23 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.

 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program. If not, see <http://www.gnu.org/licenses/>.
 *-------------------------------------------------------------------------*/
  
	get_header( ); 
	
	$siteURL = "//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	
	$parsedURL = parse_url( $siteURL, PHP_URL_QUERY );
	
	parse_str( $parsedURL, $atts );
	
	//
	// the roster type comes from the shortcode args; defaults to 'custom'
	//
	if ( array_key_exists( 'roster_type', $atts ) ) {
		$roster_type = ( mstw_tr_is_valid_roster_type( $atts['roster_type'] ) ) 
						 ? $atts['roster_type'] : 'custom';
	} else {
		$roster_type = 'custom';
	}

	// Get the settings from the admin page
	$options = get_option( 'mstw_tr_options' );

	// merge them with the defaults, so every setting has a value
	$args = wp_parse_args( $options, mstw_tr_get_defaults( ) );
	
	// then merge the parameters passed to the shortcode 
	$attribs = shortcode_atts( $args, $atts );
	
	// if a specific roster_type is specified, it takes priority over all
	// including the other shortcode args
	if( 'custom' != $roster_type ) {
		$fields = mstw_tr_get_fields_by_roster_type( $roster_type );
		//mstw_tr_log_msg( ' $fields' );
		//mstw_tr_log_msg( $fields );
		$attribs = wp_parse_args( $fields, $attribs );
	}
	
	
				
	// Set the roster format based on the page args & plugin settings 
	/*
	$roster_type = ( isset( $_GET['roster_type'] ) && $_GET['roster_type'] != '' ) ? 
						$_GET['roster_type'] : 
						$options['roster_type'];
						*/

	// Get the settings for the roster format
	//$settings = mstw_tr_get_fields_by_roster_type( $roster_type ); 

	// The roster type settings trump all other settings
	//$options = wp_parse_args( $settings, $options );

	// figure out the team name - for the title (if shown) and for team-based styles
	$uri_array = explode( '/', $_SERVER['REQUEST_URI'] );	
	$team_slug = $uri_array[sizeof( $uri_array )-2];
	$term = get_term_by( 'slug', $team_slug, 'mstw_tr_team' );
	$team_name = $term->name;
	?>

	<section id="primary">
	<div id="content-player-gallery" role="main" >

	<header class="page-header page-header_<?php echo $team_slug ?>">
		<?php echo "<h1 class='team-head-title team-head-title_$team_slug'>$team_name</h1>"; ?>
	</header>

	<?php	
	//echo mstw_tr_build_gallery( $team_slug, $roster_type, $options );
	echo mstw_tr_build_gallery( $team_slug, $roster_type, $attribs );
	?>

	</div><!-- #content -->
	</section><!-- #primary -->

	<?php //get_sidebar(); ?>
	<?php get_footer(); ?>