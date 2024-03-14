<?php
function directorypress_is_relevanssi_search($defaults = false) {
	if (
		function_exists('relevanssi_do_query') &&
		(
				(
						!$defaults &&
						directorypress_get_input_value($_REQUEST, 'what_search')
				) ||
				($defaults && isset($defaults['what_search']) && $defaults['what_search'])
		)
	) {
		return apply_filters('directorypress_is_relevanssi_search', true, $defaults);
	}
}

function directorypress_get_search_term_id($query_var, $get_var, $default_term_id) {
	if (get_query_var($query_var) && ($category_object = directorypress_get_term_by_path(get_query_var($query_var)))) {
		$term_id = $category_object->term_id;
	} elseif (isset($_GET[$get_var]) && is_numeric($_GET[$get_var])) {
		$term_id = sanitize_text_field($_GET[$get_var]);
	} else {
		$term_id = $default_term_id;
	}
	return $term_id;
}

function directorypress_visible_search_param($param_text, $link) {
	$parse_url = parse_url($link, PHP_URL_QUERY);
	parse_str($parse_url, $parse_url_str);
	if (count($parse_url_str) == 1 && directorypress_get_input_value($parse_url_str, 'directorypress_action') == 'search') {
		$link = remove_query_arg('directorypress_action', $link);
	} elseif ($use_advanced = directorypress_get_input_value($_REQUEST, 'use_advanced')) {
		$link = add_query_arg('use_advanced', '1', $link);
	}
	
	return '<div class="directorypress-search-param"><a class="directorypress-search-param-delete" href="' . $link . '">×</a>' . $param_text . '</div>';
}