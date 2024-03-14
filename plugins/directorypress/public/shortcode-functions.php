<?php

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public
 * @author     Designinvento <developers@designinvento.net>
 */
add_action('display_listing_at_archive', 'display_listing_at_archive_function', 10, 4);
function display_listing_at_archive_function($public_handler, $search_args, $shortcode_atts, $map_args){
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
	$public_handler->is_home = true;
			$public_handler->hash = md5(time());
			if (directorypress_has_map())
				$public_handler->is_map_on_page = 1;

			if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_main_search']) {
				$public_handler->search_form = new directorypress_search_form($public_handler->hash, $public_handler->directorypress_client, $search_args);
			}

			$default_orderby_args = array('order_by' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_orderby'], 'order' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_order']);

			array_walk_recursive($_GET, 'sanitize_text_field');
			$public_handler->args = array_merge($default_orderby_args, $_GET);
			
			$perpage = directorypress_get_input_value($shortcode_atts, 'perpage', (int)$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_number_index']);
			if (get_query_var('page')) {
				$paged = get_query_var('page');
			} elseif (get_query_var('paged')) {
				$paged = get_query_var('paged');
			} else {
				$paged = 1;
			}
			if (!$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_ajax_initial_load'] && ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_on_index'] || $public_handler->is_map_on_page)) {
				$order_args = apply_filters('directorypress_order_args', array(), $default_orderby_args);

				$args = array(
						'post_type' => DIRECTORYPRESS_POST_TYPE,
						'post_status' => 'publish',
						//'meta_query' => array(array('key' => '_listing_status', 'value' => 'active')),
						'posts_per_page' => $perpage,
						'paged' => $paged,
				);
				$args = array_merge($args, $order_args);
				
				$args = directorypress_set_directory_args($args, array($directorypress_object->current_directorytype->id));
				
				$args = apply_filters('directorypress_directory_query_args', $args);
				
				$public_handler->query = new WP_Query($args);
				//var_dump($public_handler->query->request);
				$public_handler->processQuery($public_handler->is_map_on_page, $map_args);
			} else {
				$public_handler->do_initial_load = false;
				if ($public_handler->is_map_on_page) {
					$public_handler->map = new directorypress_maps($map_args, $public_handler->directorypress_client);
					$public_handler->map->set_unique_id($public_handler->hash);
				}
			}

			$base_url_args = apply_filters('directorypress_base_url_args', array());
			$public_handler->base_url = directorypress_directorytype_url($base_url_args);

			$public_handler->args['perpage'] = $perpage;
			$public_handler->template = 'partials/archive/index-archive.php';
			$public_handler->page_title = get_post($directorypress_object->directorypress_archive_page_id)->post_title;
}