<div id="reuseb_term_metabox"></div>

<?php
$meta_query_args = array(
	'post_type' => 'reuseb_term_metabox',
	'meta_query' => array(
		array(
			'key'     => 'reuseb_taxonomy_select',
			'value'   => $taxonomy,
			'compare' => '='
		)
	)
);

$meta_query = get_posts($meta_query_args);
$form_builders = array();
foreach ($meta_query as $query) {
	$form_builders[] = get_post_meta($query->ID, 'formBuilder', true);
}

/**
 * Localize the updated data from database
 */
wp_localize_script('reuseb_term_meta_preview', 'REUSEB_ADMIN', apply_filters('reuseb_admin_generator_localize_args', array(
	'INITIAL_TERM_META' => $form_builders[0],
)));
?>
<input type="hidden" id="_reuseb_term_meta_data" name="_reuseb_term_meta_data">
