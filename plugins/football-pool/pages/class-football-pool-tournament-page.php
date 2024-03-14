<?php

/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

class Football_Pool_Tournament_Page {
	public function page_content() {
		global $pool;
		$filtered_matches = apply_filters( 'footballpool_filtered_matches', $pool->matches->matches );
		$output = $pool->matches->print_matches( $filtered_matches, 'page matches-page' );
		return apply_filters( 'footballpool_matches_page_html', $output, $pool->matches->matches );
	}
}
