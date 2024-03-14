<?php
function wvr_compat_get_paginate_archive_page_links( $type = 'plain', $endsize = 1, $midsize = 1 ) {
	global $wp_query, $wp_rewrite;

	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

	// Sanitize input argument values
	if ( ! in_array( $type, array( 'plain', 'list', 'array' ) ) ) $type = 'plain';
	$endsize = (int) $endsize;
	$midsize = (int) $midsize;

	$big = 999999999;       // from codex - an unlikely number, then str_replace. Makes archive no permalinks work

	if (is_search()) { // works for search on non-permalinks...
		$base = '%_%';
	} else {
		$base = str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) );
	}

	// Setup argument array for paginate_links()
	$pagination = array(
		'base' =>  $base,
		'format' => '?paged=%#%',
		'total' => $wp_query->max_num_pages,
		'current' => $current,
		'show_all' => false,
		'end_size' => $endsize,
		'mid_size' => $midsize,
		'type' => $type,
		'prev_text' => '&lt;&lt;',
		'next_text' => '&gt;&gt;'
	);

	if ( !empty($wp_query->query_vars['s']) )
			$pagination['add_args'] = array( 's' => get_query_var( 's' ) );

	return paginate_links( $pagination );
}
?>
