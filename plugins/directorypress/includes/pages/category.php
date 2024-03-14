<?php

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public
 * @author     Designinvento <developers@designinvento.net>
 */
add_action('archive_category_page', 'archive_category_page_function', 10, 4);
function archive_category_page_function($public_handler, $search_args, $shortcode_atts, $map_args){
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
	if (get_query_var('page')) {
				$paged = get_query_var('page');
			} elseif (get_query_var('paged')) {
				$paged = get_query_var('paged');
			} else {
				$paged = 1;
			}
	if ($category_object = directorypress_get_term_by_path(get_query_var('category-directorypress'))) {
				$public_handler->is_category = true;
				$public_handler->category = $category_object;

				if (directorypress_has_map())
					$public_handler->is_map_on_page = 1;
				
				if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_main_search']) {
					$search_args['category'] = $category_object->term_id;
					$public_handler->search_form = new directorypress_search_form($public_handler->hash, $public_handler->directorypress_client, $search_args);
				}

				$default_orderby_args = array('order_by' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_orderby'], 'order' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_order']);

				array_walk_recursive($_GET, 'sanitize_text_field');
				$public_handler->args = array_merge($default_orderby_args, $_GET);

				$public_handler->args['categories'] = $category_object->term_id;
				
				$perpage = directorypress_get_input_value($shortcode_atts, 'perpage', (int)$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_number_excerpt']);

				
					$order_args = apply_filters('directorypress_order_args', array(), $default_orderby_args);
					$args = array(
							'tax_query' => array(
									array(
										'taxonomy' => DIRECTORYPRESS_CATEGORIES_TAX,
										'field' => 'slug',
										'terms' => $category_object->slug,
									)
							),
							'post_type' => DIRECTORYPRESS_POST_TYPE,
							'post_status' => 'publish',
							//'meta_query' => array(array('key' => '_listing_status', 'value' => 'active')),
							'posts_per_page' => $perpage,
							'paged' => $paged
					);
					$args = array_merge($args, $order_args);
					
					$args = directorypress_set_directory_args($args, array($directorypress_object->current_directorytype->id));
					
					$args = apply_filters('directorypress_directory_query_args', $args);
	
					// found some plugins those break WP_Query by injections in pre_get_posts action, so decided to remove this hook temporarily
					global $wp_filter;
					if (isset($wp_filter['pre_get_posts'])) {
						$pre_get_posts = $wp_filter['pre_get_posts'];
						unset($wp_filter['pre_get_posts']);
					}
					$public_handler->query = new WP_Query($args);
					$public_handler->processQuery($public_handler->is_map_on_page, $map_args);
					if (isset($pre_get_posts)){
						$wp_filter['pre_get_posts'] = $pre_get_posts;
					}

				$public_handler->args['perpage'] = $perpage;
				$public_handler->template = 'partials/directory-pages/page-wrapper.php';
				
				
				$public_handler->page_title = $category_object->name;

				if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_breadcrumbs']) {
					if (!$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_hide_home_link_breadcrumb'])
						$public_handler->breadcrumbs[] = '<a href="' . directorypress_directorytype_url() . '">' . __('Home', 'DIRECTORYPRESS') . '</a>';
					$public_handler->breadcrumbs = array_merge($public_handler->breadcrumbs, directorypress_get_term_parents($category_object, DIRECTORYPRESS_CATEGORIES_TAX, true, true));
				}

				$public_handler->base_url = get_term_link($category_object, DIRECTORYPRESS_CATEGORIES_TAX);
			} else {
				$public_handler->set404();
			}
}