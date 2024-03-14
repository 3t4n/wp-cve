<?php

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public
 * @author     Designinvento <developers@designinvento.net>
 */
add_action('archive_search_page', 'archive_search_page_function', 10, 4);
function archive_search_page_function($public_handler, $search_args, $shortcode_atts, $map_args){
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
	if (get_query_var('page')) {
				$paged = get_query_var('page');
			} elseif (get_query_var('paged')) {
				$paged = get_query_var('paged');
			} else {
				$paged = 1;
			}

			$public_handler->is_search = true;
			
			$public_handler->template = 'partials/directory-pages/page-wrapper.php';
			
			
			if (!directorypress_has_map()){
				$public_handler->is_map_on_page = 0;
			}
			if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_main_search']) {
				$public_handler->search_form = new directorypress_search_form($public_handler->hash, $public_handler->directorypress_client, $search_args);
			}

			$default_orderby_args = array('order_by' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_orderby'], 'order' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_order']);
			
			
			array_walk_recursive($_GET, 'sanitize_text_field');
			$public_handler->args = array_merge($default_orderby_args, $_GET);
			
			$perpage = directorypress_get_input_value($shortcode_atts, 'perpage', (int)$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_number_excerpt']);

			
				$order_args = apply_filters('directorypress_order_args', array(), $default_orderby_args);
	
				$args = array(
						'post_type' => DIRECTORYPRESS_POST_TYPE,
						'post_status' => 'publish',
						'posts_per_page' => $perpage,
						'paged' => $paged,
				);
				$args = array_merge($args, $order_args);
				$args = apply_filters('directorypress_search_args', $args, array('include_categories_children' => 1), true, $public_handler->hash);
				
				$args = directorypress_set_directory_args($args, array($directorypress_object->current_directorytype->id));
				
				$args = apply_filters('directorypress_directory_query_args', $args);
				
				global $wp_filter;
				if (isset($wp_filter['pre_get_posts'])) {
					$pre_get_posts = $wp_filter['pre_get_posts'];
					unset($wp_filter['pre_get_posts']);
				}
				$public_handler->query = new WP_Query($args);
				
				// Relevanssi
				if (directorypress_is_relevanssi_search()) {
					relevanssi_do_query($public_handler->query);
				}

				$public_handler->processQuery(directorypress_has_map(), $map_args);
				if (isset($pre_get_posts))
					$wp_filter['pre_get_posts'] = $pre_get_posts;
			

			$public_handler->page_title = __('Search results', 'DIRECTORYPRESS');

			$public_handler->args['perpage'] = $perpage;

			if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_breadcrumbs']) {
				if (!$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_hide_home_link_breadcrumb'])
					$public_handler->breadcrumbs[] = '<a href="' . directorypress_directorytype_url() . '">' . __('Home', 'DIRECTORYPRESS') . '</a>';
				$public_handler->breadcrumbs[] = __('Search results', 'DIRECTORYPRESS');
			}
			$base_url_args = apply_filters('directorypress_base_url_args', array());
			$public_handler->base_url = directorypress_directorytype_url($base_url_args);
}