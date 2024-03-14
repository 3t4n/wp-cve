/*----------------------------------------------------------------------
 * tr-load-team-colors: sets the css when team colors have been selected
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014-22 Mark O'Donnell (mark@shoalsummitsolutions.com)
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
 *
 *---------------------------------------------------------------------*/

 jQuery(document).ready( function( $ ){
	var team_colors = $( 'mstw-team-colors' );
	if ( team_colors.length > 0 ) {
		//console.log( 'hidden elements found ' + team_colors.length + ' ... change the css.' );
		
		team_colors.each( function( ) {
			var team_slug = this.className;
			var colors = {}; //empty object to hold colors prior to building css
			//console.log( 'mstw-team-colors for: ' + team_slug );
			//console.log( 'colors for team: ' + team );
			var color_elements = $(this).children( 'team-color' );
			//console.log( 'colors specified: ' + color_elements.length );
			color_elements.each( function( ) {
				//console.log( this.id  + ': ' + $(this).text() );
				//console.log( 'in main body ...' );
				colors[ this.id ] = $(this).text( );
				//console.log( 'team_slug= ' + team_slug );
				//console.log( 'color_name= ' + this.id );
				//console.log( 'color_hex= ' + $(this).text( ) );
				
			});
			if( color_elements.length > 0 ) {
				/*
				console.log( 'color object: ' );
				console.log( colors );
				console.log( 'bkgd-color: ' + colors['bkgd-color'] );
				console.log( 'text-color: ' + colors['text-color'] );
				console.log( 'accent-1: ' + colors['accent-1'] );
				console.log( 'accent-2: ' + colors['accent-2'] );
				*/
				
				//
				// ROSTER TABLE CSS
				//
				$( 'h1.mstw-tr-roster-title_' + team_slug).css({
						'color': colors['bkgd-color']
						});
				$( 'table.mstw-tr-table_' + team_slug + ' thead tr th').css({
						'background-color': colors['bkgd-color'],
						'color': colors['text-color']
						});
				$( 'table.mstw-tr-table_' + team_slug + ' tbody tr:nth-child(even) td').css({
						'background-color': colors['bkgd-color'],
						'color': colors['text-color']
						});
				$( 'table.mstw-tr-table_' + team_slug + ' tbody tr:nth-child(odd) td').css({
						'background-color': colors['text-color'],
						'color': colors['bkgd-color']
						});
				$( 'table.mstw-tr-table_' + team_slug + ' tbody tr:nth-child(odd) td a:link, table.mstw-tr-table_' + team_slug + ' tbody tr:nth-child(odd) td a:visited').css({
						'color': colors['bkgd-color']
						});
				$( 'table.mstw-tr-table_' + team_slug + ' tbody tr:nth-child(even) td a:link, table.mstw-tr-table_' + team_slug + ' tbody tr:nth-child(even) td a:visited').css({
						'color': colors['text-color']
						});
				$( 'table.mstw-tr-table_' + team_slug + ' tbody tr td' ).css({
						'border-top': colors['accent-1'] + ' solid 1px',
						'border-bottom': colors['accent-1'] + ' solid 1px'
						});
						
				//
				// SINGLE PLAYER CSS
				//
				/*$( 'div.player-bio_' + team_slug + ' a.hover-highlight' ).css({
					'color': colors['text-color']
					});
				$( 'div.player-bio_' + team_slug + ' a.no-hover' ).css({
					color: colors['bkgd-color']
				});*/
				$( 'h1.player-head-title_' + team_slug ).css({
					'color': colors['bkgd-color']
					});
				$( 'div.player-header_' + team_slug ).css({
						'background-color': colors['bkgd-color']
						});
				$( 'div.player-header_' + team_slug + ' #player-name-nbr' ).css({
						'color': colors['text-color']
						});		
				$( 'div.player-header_' + team_slug + ' table#player-info').css({
						'border-left-color': colors['accent-1'],
						'color': colors['text-color']
						});	
				$( 'div.player-bio_' + team_slug ).css({
						'color': colors['bkgd-color'],
						'background-color': colors['accent-2'],
						'border-color': colors['bkgd-color']
						});
				$( 'div.player-bio_' + team_slug + ' h1, div.player-bio_' + team_slug + ' h2, div.player-bio_' + team_slug + ' h3').css({
						'color': colors['bkgd-color']
						});
				$( 'div.player-bio_' + team_slug + ' a').css({
					'color': colors['bkgd-color']
					});
				// this is how we have to deal with hover in jQuery
				$( 'div.player-bio_' + team_slug + ' a:link').hover(function(){
					$(this).css('color', colors['text-color']);
				}, function(){
					$(this).css('color', colors['bkgd-color']);
						});
				
				//
				// PLAYER GALLERY CSS
				//
				$( 'h1.team-head-title_' + team_slug ).css({
						'color': colors['bkdg-color']
						});
				$( 'div.player-tile_' + team_slug ).css({
						'color': colors['text-color'],
						'background-color': colors['bkgd-color']
						});	
				$( 'div.player-name-number_' + team_slug ).css({
						'color': colors['text-color']
						});
				$( 'div.player-name-number_' + team_slug + ' .player-name a:link, div.player-name-number_' + team_slug + ' .player-name a:visited ').css({
					'color': colors['text-color']	
					});
				$( 'div.player-info-container table.player-info_' + team_slug ).css({
					'color': colors['text-color']	
					});		
						
			} //End: if( color_elements.length > 0 )
				
		} ); //End: team_colors.each( function( )

	} //End: if ( team_colors.length > 0 )
	else {
		//console.log( 'no hidden element found, nevermind.' );
	}
	
 } );
 