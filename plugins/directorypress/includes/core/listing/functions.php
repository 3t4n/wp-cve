<?php
function directorypress_bookmark_list($is_listing_id = null){
	if (isset($_COOKIE['favourites']))
		$favourites = explode('*', $_COOKIE['favourites']);
	else
		$favourites = array();
	$favourites = array_values(array_filter($favourites));

	if ($is_listing_id)
		if (in_array($is_listing_id, $favourites))
			return true;
		else 
			return false;

	$favourites_array = array();
	foreach ($favourites AS $listing_id)
		if (is_numeric($listing_id))
		$favourites_array[] = $listing_id;
	return $favourites_array;
}

function directorypress_trim_content($limit = 35, $strip_html = true, $has_link = true, $nofollow = false, $readmore_text = false) {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if (has_excerpt()) {
		$raw_content = apply_filters('the_excerpt', get_the_excerpt());
	} elseif ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_cropped_content_as_excerpt'] && get_post()->post_content !== '') {
		$raw_content = apply_filters('the_content', get_the_content());
	} else {
		return ;
	}
	
	if (!$readmore_text) {
		$readmore_text = __('&#91;...&#93;', 'DIRECTORYPRESS');
	}

	$raw_content = str_replace(']]>', ']]&gt;', $raw_content);
	if ($strip_html) {
		$raw_content = strip_tags($raw_content);
		$pattern = get_shortcode_regex();
		// Remove shortcodes from excerpt
		$raw_content = preg_replace_callback("/$pattern/s", 'directorypress_clean_summary', $raw_content);
	}

	if (!$limit) {
		return $raw_content;
	}
	
	if ($has_link) {
		$readmore = ' <a href="'.get_permalink(get_the_ID()).'" '.(($nofollow) ? 'rel="nofollow"' : '').' class="directorypress-excerpt-link">'.$readmore_text.'</a>';
	} else {
		$readmore = ' ' . $readmore_text;
	}

	$content = explode(' ', $raw_content, $limit);
	if (count($content) >= $limit) {
		array_pop($content);
		$content = implode(" ", $content) . $readmore;
	} else {
		$content = $raw_content;
	}

	return $content;
}


function directorypress_clean_summary($m) {
	if (function_exists('su_cmpt') && su_cmpt() !== false)
	if ($m[2] == su_cmpt() . 'dropcap' || $m[2] == su_cmpt() . 'highlight' || $m[2] == su_cmpt() . 'tooltip')
		return $m[0];

	// allow [[foo]] syntax for escaping a tag
	if ($m[1] == '[' && $m[6] == ']')
		return substr($m[0], 1, -1);

	return $m[1] . $m[6];
}

function directorypress_is_reviews_allowed() {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_comments_mode'] == 'enabled' || ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_comments_mode'] == 'wp_settings' && comments_open()))
		return true;
	else 
		return false;
}

function directorypress_user_permission_to_edit_listing($listing_id) {
	if (!current_user_can('edit_others_posts')) {
		$post = get_post($listing_id);
		$current_user = wp_get_current_user();
		if ($current_user->ID != $post->post_author)
			return false;
		if ($post->post_status == 'pending'  && !is_admin())
			return false;
	}
	return true;
}

function directorypress_edit_post_url($listing_id, $context = 'display') {
	if (directorypress_user_permission_to_edit_listing($listing_id)) {
		$post = get_post($listing_id);
		$current_user = wp_get_current_user();
		if (current_user_can('edit_others_posts') && $current_user->ID != $post->post_author)
			return get_edit_post_link($listing_id, $context);
		else
			return apply_filters('directorypress_edit_post_url', get_edit_post_link($listing_id, $context), $listing_id);
	}
}


function directorypress_listing_view_type($listing_view, $hash) {
	global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;
	if(($listing_view == 'grid' && !isset($_COOKIE['directorypress_listings_view_'.$hash])) || (isset($_COOKIE['directorypress_listings_view_'.$hash]) && $_COOKIE['directorypress_listings_view_'.$hash] == 'grid')){
			$listing_style_to_show = 'show_grid_style';
	}elseif(($listing_view == 'grid' && !isset($_COOKIE['directorypress_listings_view_'.$hash])) || (isset($_COOKIE['directorypress_listings_view_'.$hash]) && $_COOKIE['directorypress_listings_view_'.$hash] == 'list')){
			$listing_style_to_show = 'show_list_style';
	}elseif(($listing_view == 'list' && !isset($_COOKIE['directorypress_listings_view_'.$hash])) || (isset($_COOKIE['directorypress_listings_view_'.$hash]) && $_COOKIE['directorypress_listings_view_'.$hash] == 'list')){
			$listing_style_to_show = 'show_list_style';
	}elseif(($listing_view == 'list' && !isset($_COOKIE['directorypress_listings_view_'.$hash])) || (isset($_COOKIE['directorypress_listings_view_'.$hash]) && $_COOKIE['directorypress_listings_view_'.$hash] == 'grid')){
			$listing_style_to_show = 'show_grid_style';
	}else{
			if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_views_switcher_default'] == 'grid'){
				$listing_style_to_show = 'show_grid_style';
			}else{
				$listing_style_to_show = 'show_list_style';
			}
	}
	return $listing_style_to_show;
}


function directorypress_get_listing($post) {
	$listing = new directorypress_listing;
	if ($listing->directorypress_init_lpost_listing($post)) {
		return $listing;
	}
}

function directorypress_is_listing_page() {
	global $directorypress_object;

	if (($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_MAIN_SHORTCODE)) || ($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_LISTING_SHORTCODE)) || ($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode('directorypress-listing'))) {
		if ($directorypress_directory_handler->is_single) {
			return $directorypress_directory_handler->listing;
		}
	}
}

function directorypress_is_archive_page() {
	global $directorypress_object, $post;
	if($post){
		if (($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_MAIN_SHORTCODE))) {
			if (!$directorypress_directory_handler->is_single) {
				return true;
			}
		}elseif(directorypress_is_elementor()){
			$get_settings = new \DirectoryPress_Elementor_Widget_Settings($post->ID, 'directorypress-main'); 
			if(is_null($get_settings->widget)){
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}
}

function display_average_listing_rating( $post_id = null, $decimals = 2 ) {

	if ( empty( $post_id ) ) {
		global $post;
		$post_id = $post->ID;
	}

	global $direviews_plugin;

	if ( method_exists( $direviews_plugin, 'get_average_rating' ) ) {
		$rating = $direviews_plugin->get_average_rating( $post_id, $decimals );
	}

	?>
	<a href="#comments" class="single-rating review_rate display-only" data-dirrater="<?php echo wp_kses_post($rating) ?>" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
		<span class="rating-value">(<span itemprop="reviewCount"><?php echo esc_attr(get_comments_number()); ?></span>)</span>
		<meta itemprop="ratingValue" content = "<?php echo wp_kses_post($rating); ?>">
	</a>
	<?php
}

function display_total_listing_rating( $post_id = null, $decimals = 2 ) {

	if ( empty( $post_id ) ) {
		global $post;
		$post_id = $post->ID;
	}

	global $direviews_plugin;

	if ( method_exists( $direviews_plugin, 'get_average_rating' ) ) {
		$rating = $direviews_plugin->get_average_rating( $post_id, $decimals );
	}

	//if ( empty( $rating ) ) {
	//	return;
	//} ?>
	
		<span class="rating-value"><span><?php echo get_comments_number() ?></span> <?php echo esc_html__('ratings', 'DIRECTORYPRESS'); ?></span>
	<?php
}

function get_average_listing_rating( $post_id = null, $decimals = 1 ) {

	if ( empty( $post_id ) ) {
		global $post;
		$post_id = $post->ID;
	}

	global $direviews_plugin;
	//if ( method_exists( $direviews_plugin, 'get_average_rating' ) ) {
		if($direviews_plugin->get_average_rating( $post_id, $decimals )){
			return $direviews_plugin->get_average_rating( $post_id, $decimals );
		}else{
			$default = '<i class="far fa-angry"></i>';
			return $default;
		}
	//}

	return false;
}

function directorypress_sorting_links($base_url, $defaults = array(), $return = false, $shortcode_hash = null) {
	global $directorypress_object;
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if (isset($_GET['order_by']) && $_GET['order_by']) {
		$order_by = sanitize_text_field($_GET['order_by']);
		$order = directorypress_get_input_value($_GET, 'order', 'ASC');
	} else {
		if (isset($defaults['order_by']) && $defaults['order_by']) {
			$order_by = $defaults['order_by'];
			$order = directorypress_get_input_value($defaults, 'order', 'ASC');
		} else {
			$order_by = 'post_date';
			$order = 'DESC';
		}
	}

	$ordering['array'] = array();
	$ordering['array']['rand'] = __('Random', 'DIRECTORYPRESS');
	if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_orderby_date']){
		$ordering['array']['post_date'] = __('Sort By Date', 'DIRECTORYPRESS');
	}
	if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_orderby_title']){
		if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_orderby_title_az_za']){
			$ordering['array']['az'] = __('A-Z', 'DIRECTORYPRESS');
			$ordering['array']['za'] = __('Z-A', 'DIRECTORYPRESS');
		}else{
			$ordering['array']['title'] = __('Title', 'DIRECTORYPRESS');
		
		}
	}
	
	$exact_categories = array();
	if (!empty($defaults['categories'])) {
		$exact_categories = array_filter(explode(',', $defaults['categories']));
	}
	if ($current_category = directorypress_is_category()) {
		$exact_categories[] = $current_category->term_id;
	}
	$fields = $directorypress_object->fields->get_fields_order();
	foreach ($fields AS $field) {
		if ($exact_categories && $field->categories) {
			if (array_intersect($field->categories, $exact_categories)) {
				$ordering['array'][$field->slug] = $field->name;
			}
		} else {
			$ordering['array'][$field->slug] = $field->name;
		}
	}
	
	$ordering['links'] = array();
	$ordering['struct'] = array();
	foreach ($ordering['array'] AS $field_slug=>$field_name) {
		$class = '';
		$next_order = 'DESC';
		if ($order_by == $field_slug) {
			if ($order == 'ASC') {
				$class = 'ascending';
				$next_order = 'ASC';
				$url = esc_url(add_query_arg(array('order_by' => $field_slug, 'order' => $next_order), $base_url));
			} elseif ($order == 'DESC') {
				$class = 'descending';
				$next_order = 'DESC';
				$url = esc_url(add_query_arg('order_by', $field_slug, $base_url));
			}
		} else {
			if ($field_slug == 'title') {
				$next_order = 'ASC';
				$url = esc_url(add_query_arg(array('order_by' => $field_slug, 'order' => $next_order), $base_url));
			}elseif ($field_slug == 'az') {
				//$class = 'ascending';
				$next_order = 'ASC';
				$url = esc_url(add_query_arg(array('order_by' => 'title', 'order' => $next_order), $base_url));
			}elseif ($field_slug == 'za') {
				//$class = 'descending';
				$next_order = 'DESC';
				$url = esc_url(add_query_arg(array('order_by' => 'title', 'order' => $next_order), $base_url));
			} else{
				$url = esc_url(add_query_arg('order_by', $field_slug, $base_url));
			}
		}

		$ordering['links'][$field_slug] = '<a class="' . $class . '" href="' . $url . '" rel="nofollow">' .$field_name . '</a>';
		$ordering['struct'][$field_slug] = array('class' => $class, 'url' => $url, 'field_name' => $field_name, 'order' => $next_order);
	}
	
	if ($return)
		return $ordering;
	else
		echo __('Order by: ', 'DIRECTORYPRESS') . implode(' | ', $ordering['links']);
}

function directorypress_sorting_options() {
	global $directorypress_object;

	$ordering = array(
	'post_date' => __('Date', 'DIRECTORYPRESS'),
	'title' => __('Title', 'DIRECTORYPRESS'),
	'az' => __('A-Z', 'DIRECTORYPRESS'),
	'za' => __('Z-A', 'DIRECTORYPRESS'),
	'rand' => __('Random', 'DIRECTORYPRESS')
	);
	if(is_object($directorypress_object->fields)){
		$fields = $directorypress_object->fields->get_fields_order();
		foreach ($fields AS $field) {
			$ordering[$field->slug] = $field->name;
		}
	}
	$ordering = apply_filters('directorypress_default_orderby_options', $ordering);
	$ordering_items = array();
	foreach ($ordering AS $field_slug=>$field_name) {
		$ordering_items[] = array('value' => $field_slug, 'label' => $field_name);
	}
	$new_listing_ordering = array();
	foreach($ordering_items as $listItem) {
		$new_listing_ordering[$listItem['value']] = $listItem['label'];
	}
	return $new_listing_ordering;
	//return $ordering_items;
}

function directorypress_directorytypes_array_options() {
	global $directorypress_object;
	$directories = array('0' =>  __('Auto', 'DIRECTORYPRESS'));
	foreach ($directorypress_object->directorytypes->directorypress_array_of_directorytypes AS $directorytype) {
			$directories[$directorytype->id] = $directorytype->name;
	}
	return $directories;
}
function directorypress_terms_options_array($taxonomy) {
	$terms = get_terms($taxonomy, array('hide_empty' => false));
	$categories = array('0' =>  __('Select Term', 'DIRECTORYPRESS'));
	foreach ($terms AS $term) {
			$categories[$term->term_id] = $term->name;
	}
	return $categories;
}
function directorypress_categories_array_options() {
	$terms = get_terms(DIRECTORYPRESS_CATEGORIES_TAX, array('hide_empty' => false));
	$categories = array('0' =>  __('Select All', 'DIRECTORYPRESS'));
	foreach ($terms AS $term) {
			$categories[$term->term_id] = $term->name;
	}
	return $categories;
}
function directorypress_fields_array_options() {
	global $directorypress_object;
	
	$fields = array('0' =>  __('Select All', 'DIRECTORYPRESS'), 'none' =>  __('No Field', 'DIRECTORYPRESS'));
	
	foreach ($directorypress_object->search_fields->search_fields_array AS $field){
		$fields[$field->field->id] = $field->field->name;
	}	
	return $fields;
}
function directorypress_locations_array_options() {
	$terms = get_terms(DIRECTORYPRESS_LOCATIONS_TAX, array('hide_empty' => false));
	$locations = array('0' =>  __('Select All', 'DIRECTORYPRESS'));
	foreach ($terms AS $term) {
			$locations[$term->term_id] = $term->name;
	}
	return $locations;
}

function directorypress_packages_array_options() {
	global $directorypress_object;
	$packages = array('0' =>  __('All', 'DIRECTORYPRESS'));
	foreach ($directorypress_object->packages->packages_array AS $package) {
		$packages[$package->id] = $package->name;
	}
	return $packages;
}

if (!function_exists('directorypress_pagination_display')) {
	function directorypress_pagination_display($query, $hash = null, $show_more_button = false, $public_handler = null) {
		global $directorypress_object;
		
		if (empty($public_handler)) {
			$directorytype = $directorypress_object->current_directorytype;
		} else {
			$directorytype = $public_handler->directorypress_get_directoytype_of_listing();
		}
		if (get_class($query) == 'WP_Query') {
			if (get_query_var('page'))
				$paged = get_query_var('page');
			elseif (get_query_var('paged'))
				$paged = get_query_var('paged');
			else
				$paged = 1;

			$total_pages = $query->max_num_pages;
			$total_lines = ceil($total_pages/10);
		
			if ($total_pages > 1){
				$current_page = max(1, $paged);
				$current_line = floor(($current_page-1)/10) + 1;
		
				$previous_page = $current_page - 1;
				$next_page = $current_page + 1;
				$previous_line_page = floor(($current_page-1)/10)*10;
				$next_line_page = ceil($current_page/10)*10 + 1;
				
				if (!$show_more_button) {
					echo '<div class="directorypress-pagination-wrapper">';
					echo '<ul class="pagination">';
					if ($total_pages > 10 && $current_page > 10)
						echo '<li class="directorypress-inactive previous_line"><a href="' . get_pagenum_link($previous_line_page) . '" title="' . esc_attr__('Previous Line', 'DIRECTORYPRESS') . '" data-page=' . esc_attr($previous_line_page) . ' data-handler-hash=' . esc_attr($hash) . '><<</a></li>' ;
			
					if ($total_pages > 3 && $current_page > 1)
						echo '<li class="directorypress-inactive previous"><a href="' . get_pagenum_link($previous_page) . '" title="' . esc_attr__('Previous Page', 'DIRECTORYPRESS') . '" data-page=' . esc_attr($previous_page) . ' data-handler-hash=' . esc_attr($hash) . '><</i></a></li>' ;
			
					$count = ($current_line-1)*10;
					$end = ($total_pages < $current_line*10) ? $total_pages : $current_line*10;
					while ($count < $end) {
						$count = $count + 1;
						if ($count == $current_page)
							echo '<li class="active"><a href="' . get_pagenum_link($count) . '">' . esc_attr($count) . '</a></li>' ;
						else
							echo '<li class="directorypress-inactive"><a href="' . get_pagenum_link($count) . '" data-page=' . esc_attr($count) . ' data-handler-hash=' . esc_attr($hash) . '>' . esc_html($count) . '</a></li>' ;
					}
			
					if ($total_pages > 3 && $current_page < $total_pages)
						echo '<li class="directorypress-inactive next"><a href="' . get_pagenum_link($next_page) . '" title="' . esc_attr__('Next Page', 'DIRECTORYPRESS') . '" data-page=' . esc_attr($next_page) . ' data-handler-hash=' . esc_attr($hash) . '>></i></a></li>' ;
			
					if ($total_pages > 10 && $current_line < $total_lines)
						echo '<li class="directorypress-inactive next_line"><a href="' . get_pagenum_link($next_line_page) . '" title="' . esc_attr__('Next Line', 'DIRECTORYPRESS') . '" data-page=' . esc_attr($next_line_page) . ' data-handler-hash=' . esc_attr($hash) . '>>></a></li>' ;
			
					echo '</ul>';
					echo '</div>';
				} else {
					if ($public_handler && $public_handler->args['scrolling_paginator'] == 1) {
						$scrolling_paginator_class = "directorypress-scrolling-paginator";
					} else {
						$scrolling_paginator_class = '';
					}
					echo '<button class="btn btn-primary btn-lg btn-block directorypress-show-more-button directorypress-new-btn-4 ' . esc_attr($scrolling_paginator_class) . '" data-handler-hash="' . esc_attr($hash) . '">' .__('Load More', 'DIRECTORYPRESS') . '</button>';
				}
			}
		}
	}
}

function directorypress_social_sharing_display($post_id, $button) {
	global $directorypress_social_services, $DIRECTORYPRESS_ADIMN_SETTINGS;
	$post_title = urlencode(get_the_title($post_id));
	$thumb_url = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), array(200, 200));
	$post_thumbnail = urlencode($thumb_url[0]);
	if (get_post_type($post_id) == DIRECTORYPRESS_POST_TYPE) {
		$listing = new directorypress_listing;
		if ($listing->directorypress_init_lpost_listing($post_id))
			$post_title = urlencode($listing->title());
	}
	$post_url = urlencode(get_permalink($post_id));

	if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_share_buttons']['enabled']) {
		$share_url = false;
		$share_counter = false;
		switch ($button) {
			case 'Facebook':
				$share_url = 'https://www.facebook.com/sharer.php?u=' . $post_url;
			break;
			case 'Twitter':
				$share_url = 'https://twitter.com/share?url=' . $post_url . '&amp;text=' . $post_title;
			break;
			case 'Digg':
				$share_url = 'https://www.digg.com/submit?url=' . $post_url;
			break;
			case 'Reddit':
				$share_url = 'https://reddit.com/submit?url=' . $post_url . '&amp;title=' . $post_title;
			break;
			case 'LinkedIn':
				$share_url = 'https://www.linkedin.com/shareArticle?mini=true&amp;url=' . $post_url;
			break;
			case 'Pinterest':
				$share_url = 'https://www.pinterest.com/pin/create/button/?url=' . $post_url . '&amp;media=' . $post_thumbnail . '&amp;description=' . $post_title;
			break;
			case 'Stumbleupon':
				$share_url = 'https://www.stumbleupon.com/submit?url=' . $post_url . '&amp;title=' . $post_title;
			break;
			case 'Tumblr':
				$share_url = 'https://www.tumblr.com/share/link?url=' . str_replace('http://', '', str_replace('https://', '', $post_url)) . '&amp;name=' . $post_title;
			break;
			case 'vk':
				$share_url = 'https://vkontakte.ru/share.php?url=' . $post_url;
			break;
			case 'Email':
				$share_url = 'mailto:?Subject=' . $post_title . '&amp;Body=' . $post_url;
			break;
			case 'Whatsapp':
				$share_url = 'https://api.whatsapp.com/send?text=' . $post_url;
			break;
			case 'Telegram':
				$share_url = 'https://t.me/share/url?url={url}&text=' . $post_url;
		}

		echo '<a href="'. esc_url($share_url) .'" data-toggle="tooltip" title="'.sprintf(__('Share on %s', 'DIRECTORYPRESS'), esc_attr($button)).'" target="_blank"><img src="'. DIRECTORYPRESS_RESOURCES_URL .'images/social/directorypress/'. esc_attr($button) .'.png" /></a>';

	}
}
add_action('location_for_grid_and_list', 'listing_location_function', 10, 2);
function listing_location_function($listing, $icon = false){

	foreach ($listing->locations AS $location){
		if($location->get_location() || $location->address_line_1){
			if($icon){
				echo '<i class="dicode-material-icons dicode-material-icons-map-marker"></i>';
			}
			echo '<span class="directorypress-location"  itemscope itemtype="http://schema.org/PostalAddress">';
			if ($location->map_coords_1 && $location->map_coords_2){
				echo '<span class="directorypress-show-on-map" data-location-id="'. esc_attr($location->id) .'">';
			}
			if($location->get_location()){
				echo wp_kses_post($location->get_location());
			}elseif($location->address_line_1){
				echo wp_kses_post($location->address_line_1);
			}
			if ($location->map_coords_1 && $location->map_coords_2){
				echo '</span>';
			}
			echo '</span>';
		}
	}
						
}
add_action('directorypress_author_verified', 'directorypress_author_verified_function');
function directorypress_author_verified_function($listing){
	$author_id = get_the_author_meta( 'ID', $listing->post->post_author);
	$status = get_user_meta($author_id, 'email_verification_status', true );
	
	if($status == 'verified'){
		echo '<span class="verified"><i class="fas fa-check"></i></span>';
	}else{
		echo '<span class="unverified"><i class="fas fa-check"></i></span>';
	}
}
function directorypress_is_bookmark_page(){
	global $directorypress_object;
	if($directorypress_object->directorypress_get_property_of_shortcode('directorypress-main', 'is_favourites')){
		return true;
	}
	return false;
}


add_action('directorypress_business_hours_status', 'directorypress_business_hours_status', 10, 2);
function directorypress_business_hours_status($listing, $time_string = false){
	global $wpdb;
	$field_ids = $wpdb->get_results('SELECT id, type, slug FROM '.$wpdb->prefix.'directorypress_fields');
	
	echo '<div class="business-hours-status">';
		foreach( $field_ids as $field_id ) {
			$singlefield_id = $field_id->id;
			if($field_id->type == 'hours'){			
				$listing->hours_field_status($singlefield_id, $time_string);
			}				
		}
	echo '</div>';	
}

add_action('directorypress_listing_submit_user_info', 'directorypress_listing_submit_user_info_function');
function directorypress_listing_submit_user_info_function($content = ''){
	if(!empty($content)){
		echo '<a data-toggle="popover" data-placement="top" data-content="'.$content.'" data-trigger="click"><i class="far fa-question-circle"></i></a>';	
	}
}

add_action('directorypress_listing_submit_admin_info', 'directorypress_listing_submit_admin_info_function');
function directorypress_listing_submit_admin_info_function($field = ''){
	global $directorypress_admin_info_strings;
	if(!empty($field) && current_user_can('administrator')){
		echo '<a class="admin_info_link" href="#" data-toggle="modal" data-target="#admin_info_modal" data-field="'. esc_attr($field).'"><i class="dicode-material-icons dicode-material-icons-information-variant"></i> '.esc_html__('Admin Docs', 'DIRECTORYPRESS').'</a>';	
	}
}

add_action('wp_ajax_directorypress_listing_submit_admin_process_function', 'directorypress_listing_submit_admin_process_function');
add_action('wp_ajax_nopriv_directorypress_listing_submit_admin_process_function', 'directorypress_listing_submit_admin_process_function');

function directorypress_listing_submit_admin_process_function(){
	global $directorypress_admin_info_strings;
	$field = sanitize_text_field($_POST['field']);
	echo '<p class="alert alert-info">'. esc_html__('This information is only visible to Admin', 'DIRECTORYPRESS') .'</p>';
	if(!empty($field)){
		echo '<ul>';
			if(!empty($directorypress_admin_info_strings[$field]['translation'])){
				echo '<li>'. wp_kses_post($directorypress_admin_info_strings[$field]['translation']) .'</li>';
			}
			if(!empty($directorypress_admin_info_strings[$field]['admin_setting'])){
				echo '<li>'. wp_kses_post($directorypress_admin_info_strings[$field]['admin_setting']) .'</li>';
			}
			if(!empty($directorypress_admin_info_strings[$field]['remote_tutorial_url'])){
				echo '<li>'. wp_kses_post($directorypress_admin_info_strings[$field]['remote_tutorial_url']) .'</li>';
			}
			if(!empty($directorypress_admin_info_strings[$field]['remote_tutorial_url2'])){
				echo '<li>'. wp_kses_post($directorypress_admin_info_strings[$field]['remote_tutorial_url2']) .'</li>';
			}
			if(!empty($directorypress_admin_info_strings[$field]['remote_tutorial_url3'])){
				echo '<li>'. wp_kses_post($directorypress_admin_info_strings[$field]['remote_tutorial_url3']) .'</li>';
			}
			if(!empty($directorypress_admin_info_strings[$field]['remote_tutorial_url4'])){
				echo '<li>'. wp_kses_post($directorypress_admin_info_strings[$field]['remote_tutorial_url4']) .'</li>';
			}
			if(!empty($directorypress_admin_info_strings[$field]['remote_tutorial_url5'])){
				echo '<li>'. wp_kses_post($directorypress_admin_info_strings[$field]['remote_tutorial_url5']) .'</li>';
			}
			if(!empty($directorypress_admin_info_strings[$field]['remote_tutorial_url6'])){
				echo '<li>'. wp_kses_post($directorypress_admin_info_strings[$field]['remote_tutorial_url6']) .'</li>';
			}
			if(!empty($directorypress_admin_info_strings[$field]['remote_tutorial_url7'])){
				echo '<li>'. wp_kses_post($directorypress_admin_info_strings[$field]['remote_tutorial_url7']) .'</li>';
			}
			if(!empty($directorypress_admin_info_strings[$field]['remote_tutorial_url8'])){
				echo '<li>'. wp_kses_post($directorypress_admin_info_strings[$field]['remote_tutorial_url8']) .'</li>';
			}
			if(!empty($directorypress_admin_info_strings[$field]['remote_tutorial_url9'])){
				echo '<li>'. wp_kses_post($directorypress_admin_info_strings[$field]['remote_tutorial_url9']) .'</li>';
			}
			if(!empty($directorypress_admin_info_strings[$field]['remote_tutorial_url10'])){
				echo '<li>'. wp_kses_post($directorypress_admin_info_strings[$field]['remote_tutorial_url10']) .'</li>';
			}
		echo '</ul>';
	}
	die();
}

add_action('wp_footer', 'directorypress_listing_submit_admin_info_modal_function');
add_action('admin_footer', 'directorypress_listing_submit_admin_info_modal_function');
function directorypress_listing_submit_admin_info_modal_function(){
	if(current_user_can('administrator')){
		echo '<div id="admin_info_modal" class="modal fade" role="dialog">';
			echo '<div class="modal-dialog">';
				// Modal content
				echo '<div class="modal-content">';
					echo '<div class="directorypress-modal-top-border"></div>';
					echo '<div class="modal-header">';
						echo '<button type="button" class="close" data-dismiss="modal">&times;</button>';
						echo '<h4 class="modal-title">'. esc_html__('Help Docs For Admin', 'DIRECTORYPRESS') .'</h4>';
					echo '</div>';
					echo '<div class="modal-body"></div>';
					echo '<div class="modal-footer">';
						echo '<button type="button" class="btn btn-default" data-dismiss="modal">'. esc_html__('Close', 'DIRECTORYPRESS') .'</button>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
}

add_action('directorypress_listing_submit_required_lable', 'directorypress_listing_submit_required_lable_function');
function directorypress_listing_submit_required_lable_function($field){
	if ($field->is_this_field_requirable() && $field->is_required){
		echo '<span class="lable label-danger">*</span>';
	}

}

add_action('directorypress_listing_sorting_panel', 'directorypress_listing_sorting_panel', 10, 2);
function directorypress_listing_sorting_panel($public_handler, $listing_style_to_show){
	if ($public_handler->query->found_posts && !$public_handler->args['scroll'] && (!$public_handler->args['hide_order'] || $public_handler->args['show_views_switcher'])){
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$view_swither_panel_style = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['view_switther_panel_style']))? $DIRECTORYPRESS_ADIMN_SETTINGS['view_switther_panel_style'] : 1;
		if($view_swither_panel_style == 1){ 
			$view_swither_panel_style_class = 'view_swither_panel_style1';
		}elseif($view_swither_panel_style == 2){
			$view_swither_panel_style_class = 'view_swither_panel_style2';
		}elseif($view_swither_panel_style == 3){
			$view_swither_panel_style_class = 'view_swither_panel_style3';
		}elseif($view_swither_panel_style == 4){
			$view_swither_panel_style_class = 'view_swither_panel_style4';
		}else{
			$view_swither_panel_style_class = 'view_swither_panel_style1';
		}
		
		echo '<div class="directorypress-listings-block-header switcher-panel-style-'. esc_attr($view_swither_panel_style) .' clearfix">';
			do_action('directorypress_sorting_panel_before', $public_handler);
			directorypress_found_listing_function($public_handler, $listing_style_to_show);
			if ($public_handler->args['show_views_switcher'] || !$public_handler->args['hide_order']){
				echo '<div class="'. esc_attr($view_swither_panel_style_class) .' directorypress-options-links clearfix">';
					directorypress_listing_ordering_function($public_handler);
					directorypress_listing_view_switcher_function($public_handler, $listing_style_to_show);
				echo '</div>';
			}
			do_action('directorypress_sorting_panel_after', $public_handler);
		echo '</div>';	
	}
}

function directorypress_listing_view_switcher_function($public_handler, $listing_style_to_show){
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	
	$grid_icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_grid_icon']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_grid_icon']))? $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_grid_icon'] : 'fas fa-th';
	$list_icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_list_icon']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_list_icon']))? $DIRECTORYPRESS_ADIMN_SETTINGS['sorting_panel_switcher_button_list_icon'] : 'fas fa-bars';
	
	if ($listing_style_to_show == 'show_list_style'){
		$btn_list_classes = 'active';
		$btn_grid_classes = 'in-active';
	}else{
		$btn_list_classes = 'in-active';
		$btn_grid_classes = 'active';
	}
	if ($public_handler->args['show_views_switcher']){
		echo '<div id="view-btn-'. esc_attr($public_handler->hash) .'" class="directorypress-views-links pull-right">';
			echo '<div class="btn-group" role="group">';
				echo '<a class="directorypress-list-view-btn '. esc_attr($btn_list_classes) .'" href="javascript: void(0);" title="'. esc_attr__('List View', 'DIRECTORYPRESS').'" data-handler-hash="'. esc_attr($public_handler->hash) .'">';
					echo '<span class="'. esc_attr($list_icon) .'" aria-hidden="true"></span>';
				echo '</a>';
				echo '<a class="directorypress-grid-view-btn '. esc_attr($btn_grid_classes) .'" href="javascript: void(0);" title="'. esc_attr__('Grid View', 'DIRECTORYPRESS').'" data-handler-hash="'. esc_attr($public_handler->hash) .'" data-columns="'. esc_attr($public_handler->args['listings_view_grid_columns']).'">';
					echo '<span class="'. esc_attr($grid_icon) .'" aria-hidden="true"></span>';
				echo '</a>';
				do_action('directorypress_sorting_panel_buttons_after', $public_handler);
			echo '</div>';
		echo '</div>';
	}
}

function directorypress_listing_ordering_function($public_handler){
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	$order_by_txt = '';
	
	if(!$public_handler->args['hide_order']){
		$ordering = directorypress_sorting_links($public_handler->base_url, $public_handler->args, true, $public_handler->hash);
		if ($ordering['struct']){
			echo '<div class="directorypress-orderby-links btn-group" role="group">';
				echo '<select class="directorypress-sorting directorypress-select2">';
					foreach ($ordering['struct'] AS $field_slug=>$link){
						if($link['class']){
							$selected = 'selected';
						}else{
							$selected = '';
						}
						echo '<option class="" value="'. esc_url($link['url']).'" data-handler-hash="'. esc_attr($public_handler->hash) .'" data-orderby="'. esc_attr($field_slug) .'" data-order="'. esc_attr($link['order']) .'" '. esc_attr($selected) .'>'. esc_html($link['field_name']) .'</option>';
					}
				echo '</select>';
			echo '</div>';
		}
	}
}
function directorypress_found_listing_function($public_handler){
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	$view_swither_panel_style = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['view_switther_panel_style']))? $DIRECTORYPRESS_ADIMN_SETTINGS['view_switther_panel_style'] : 1;
	if($view_swither_panel_style == 4){
		if (!$public_handler->args['hide_count'] && !$public_handler->args['scroll']){
			echo '<div class="directorypress-found-listings">';
				echo sprintf(esc_html__('%s Result Found', "DIRECTORYPRESS"), esc_attr($public_handler->query->found_posts));
			echo '</div>';
		}
	}else{
		if (!$public_handler->args['hide_count'] && !$public_handler->args['scroll']){
			echo '<div class="directorypress-found-listings">';
				echo esc_html__('Result Found', "DIRECTORYPRESS").'<span class="badge">'. esc_attr($public_handler->query->found_posts) .'</span>';
			echo '</div>';
		}
	}
}