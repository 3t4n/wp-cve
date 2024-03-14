<?php

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public
 * @author     Designinvento <developers@designinvento.net>
 */
add_action('archive_bookmark_page', 'archive_bookmark_page_function', 10, 4);
function archive_bookmark_page_function($public_handler, $search_args, $shortcode_atts, $map_args){
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
	if (get_query_var('page')) {
				$paged = get_query_var('page');
			} elseif (get_query_var('paged')) {
				$paged = get_query_var('paged');
			} else {
				$paged = 1;
			}
	
	$public_handler->is_favourites = true;

			if (!$favourites = directorypress_bookmark_list()) {
				$favourites = array(0);
			}
			$args = array(
					'post__in' => $favourites,
					'post_type' => DIRECTORYPRESS_POST_TYPE,
					'post_status' => 'publish',
					//'meta_query' => array(array('key' => '_listing_status', 'value' => 'active')),
					'posts_per_page' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_number_excerpt'],
					'paged' => $paged,
			);
			$public_handler->query = new WP_Query($args);
			$public_handler->processQuery(directorypress_has_map());
			
			$public_handler->args['perpage'] = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_number_excerpt'];
			$public_handler->template = 'partials/directory-pages/page-wrapper.php';
			$public_handler->page_title = __('My bookmarks', 'DIRECTORYPRESS');

			if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_breadcrumbs']) {
				if (!$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_hide_home_link_breadcrumb'])
					$public_handler->breadcrumbs[] = '<a href="' . directorypress_directorytype_url() . '">' . __('Home', 'DIRECTORYPRESS') . '</a>';
				$public_handler->breadcrumbs[] = __('My bookmarks', 'DIRECTORYPRESS');
			}
			$public_handler->args['hide_order'] = 1;
}