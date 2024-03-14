<?php
function qc_lpp_get_post_types(){
	$args = array(
	   'public'   => true,
	   '_builtin' => false
	);
	
	$output = 'objects'; // 'names' or 'objects' (default: 'names')
	$operator = 'and'; // 'and' or 'or' (default: 'and')
	  
	$post_types = get_post_types( $args, $output, $operator );
	$post_type_lists = array(
		'post' => 'Posts',
		'page' => 'Pages',
	);

	return $post_type_lists;
}