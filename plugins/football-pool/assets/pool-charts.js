/**
 * @preserve Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/COPYING
 *
 * This file is part of Football pool.
 *
 * Football pool is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * Football pool is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with Football pool.
 * If not, see <https://www.gnu.org/licenses/>.
 */

jQuery( document ).ready( function() {
	// set some default Highcharts options
	if ( typeof Highcharts !== 'undefined' ) {
		Highcharts.setOptions( {
			// no link to highcharts.com
			credits: {
				enabled: false
			}
			// Google Chart colors
			, colors: [ '#3366CC', '#DC3912', '#FF9900', '#109618', '#990099', '#0099C6', '#DD4477', 
						'#66AA00', '#B82E2E', '#316395', '#994499', '#22AA99', '#AAAA11', '#6633CC',
						'#E67300', '#8B0707', '#651067', '#329262', '#5574A6', '#3B3EAC', '#B77322',
						'#16D620', '#B91383', '#F4359E', '#9C5935', '#A9C413', '#2A778D', '#668D1C',
						'#BEA413', '#0C5922', '#743411' ]
			// // NL
			// , lang: {
				// resetZoom: "weer uitzoomen",
				// resetZoomTitle: "uitzoomen naar 1:1"
			// }
		} );
	}
	
	// user selection on the statistics page
	FootballPool.charts_user_toggle();
} );
