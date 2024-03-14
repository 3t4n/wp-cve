<?php
	global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
	$listing_style_to_show = $listing->listing_view;
	add_action('directorypress_listing_grid_thumbnail', 'directorypress_listing_grid_thumbnail', 1);
	if(!function_exists('directorypress_listing_grid_thumbnail')){
		function directorypress_listing_grid_thumbnail($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
			$listing_style_to_show = $listing->listing_view;
			
			/* == Listing Image Source == */
			
			if(isset($listing->logo_image) && !empty($listing->logo_image)){
				$image_src_array = wp_get_attachment_image_src($listing->logo_image, 'full');
				$image_src = $image_src_array[0];
			}elseif(isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url'])){
				$image_src_array = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url'];
				$image_src = $image_src_array;
			}else{
				$image_src = DIRECTORYPRESS_RESOURCES_URL.'images/no-thumbnail.jpg';
			}
			
			/* == Listing Image Dimensions == */
			$param = '';
			$width= '';
			$height= '';
			if ($DIRECTORYPRESS_ADIMN_SETTINGS['listing_image_width_height'] == 1){
					
				$width= 370;
				$height= 260;
	
			}elseif($DIRECTORYPRESS_ADIMN_SETTINGS['listing_image_width_height'] == 3){
				$width = '';
				$height = '';
			}else{
				$width = $listing->listing_image_width;
				$height = $listing->listing_image_height;
			}
				
			$param = array(
				'width' => $width,
				'height' => $height,
				'crop' => true
			);
			echo '<a class="listing-thumbnail" href="'.get_permalink().'"><img alt="'. esc_attr($listing->title()) .'" src="'. esc_url(bfi_thumb($image_src, $param)).'" width="'. esc_attr($width) .'" height="'. esc_attr($height) .'" /></a>';
		}
	}
	add_action('directorypress_listing_listview_thumbnail', 'directorypress_listing_listview_thumbnail', 1);
	if(!function_exists('directorypress_listing_listview_thumbnail')){
		function directorypress_listing_listview_thumbnail($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
			
			/* == Listing Image Source == */
			
			if(isset($listing->logo_image) && !empty($listing->logo_image)){
				$image_src_array = wp_get_attachment_image_src($listing->logo_image, 'full');
				$image_src = $image_src_array[0];
			}elseif(isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url'])){
				$image_src_array = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url'];
				$image_src = $image_src_array;
			}else{
				$image_src = DIRECTORYPRESS_RESOURCES_URL.'images/no-thumbnail.jpg';
			}
			
			/* == Listing Image Dimensions == */
			$param = '';	
			$width= $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_logo_width_listview'];
			$height= $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_logo_height_listview'];
			
			$param = array(
				'width' => $width,
				'height' => $height,
				'crop' => true
			);
			echo '<a class="listing-thumbnail" href="'. get_permalink() .'"><img alt="'. esc_attr($listing->title()) .'" src="'. esc_url(bfi_thumb($image_src, $param)) .'" width="'. esc_attr($width) .'" height="'. esc_attr($height) .'" /></a>';
		}
	}
	add_action('directorypress_listing_grid_featured_tag', 'directorypress_listing_grid_featured_tag', 1);
	if(!function_exists('directorypress_listing_grid_featured_tag')){
		function directorypress_listing_grid_featured_tag($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
			$feature_tag_style = (isset($listing->listing_has_featured_tag_style) && !empty($listing->listing_has_featured_tag_style ))? $listing->listing_has_featured_tag_style : $listing->listing_post_style;				
			
			$feature_tag = '<span class="has_featured-tag-default">'.esc_html__('Featured', 'DIRECTORYPRESS').'</span>';
			
			if ($listing->package->has_featured){
				echo wp_kses_post($feature_tag);
			}
		}
	}
	add_action('directorypress_listing_grid_status_tag', 'directorypress_listing_grid_status_tag', 1);
	if(!function_exists('directorypress_listing_grid_status_tag')){
		function directorypress_listing_grid_status_tag($listing){
			
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object, $wpdb;
			
			$field_ids = $wpdb->get_results('SELECT id, type, slug, on_exerpt_page, on_exerpt_page_list, is_field_in_line, options FROM '.$wpdb->prefix.'directorypress_fields');
			foreach( $field_ids as $field_id ) {
				$singlefield_id = $field_id->id;
				//if($field_id->on_exerpt_page){	
					if($field_id->type == 'status' && (($listing->listing_view == 'show_grid_style' && $field_id->on_exerpt_page) || ($listing->listing_view == 'show_list_style' && $field_id->on_exerpt_page_list))){			
						$listing->display_content_field($singlefield_id);
					}
				//}
			}
		}
	}
	add_action('directorypress_listing_grid_author', 'directorypress_listing_grid_author', 10, 2);
	if(!function_exists('directorypress_listing_grid_author')){
		function directorypress_listing_grid_author($listing, $size){
			
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
			
			$authorID = get_the_author_meta( 'ID', $listing->post->post_author);
			$author_nicename = get_the_author_meta('nicename', $authorID);
			$avatar_id = get_user_meta( $authorID, 'avatar_id', true );
			echo '<div class="listng-author-img">';
				if(!empty($avatar_id) && is_numeric($avatar_id)) {
					$attachment = wp_get_attachment_image_src( $avatar_id, 'full' ); 
					$src = $attachment[0];
					$params = array( 'width' => $size, 'height' => $size, 'crop' => true );
					echo "<img class='directorypress-user-avatar' src='" . esc_url(bfi_thumb($src, $params )) . "' alt='author' />";
				} else { 
					$avatar_url = get_avatar_url ( get_the_author_meta('user_email', $authorID), $size = $size );
					echo '<a href="'. esc_url(directorypress_author_page_url($authorID)) .'"><img src="'. esc_url($avatar_url) .'" alt="author" width="'. esc_attr($size) .'" height="'. esc_attr($size) .'" /></a>';		
				}
				if ( directorypress_is_user_online($authorID) ){
					echo '<span class="author-active"></span>';
				} else {
					echo '<span class="author-in-active"></span>';
				}	
			echo '</div>';
		}
	}
	add_action('directorypress_listing_grid_category', 'directorypress_listing_grid_category', 10, 1);
	if(!function_exists('directorypress_listing_grid_category')){
		function directorypress_listing_grid_category($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
			
			$parent_terms = wp_get_post_terms($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX, array('parent' => 0) );
			$parent_term = (is_array($parent_terms) || is_object($parent_terms))? array_pop($parent_terms): '';
			$parent_term_link = (!empty($parent_term))? '<a class="listing-cat" href="'. get_term_link($parent_term->slug, DIRECTORYPRESS_CATEGORIES_TAX).'" rel="tag">'. esc_html($parent_term->name) .'</a>': '';
			//$parent = (!empty($parent_term))? $parent_term->term_id : 0;
			$child_terms = wp_get_post_terms($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX, array('parent' => $parent_term->term_id ) );
			$child_term = (is_array($child_terms) || is_object($child_terms))? array_pop($child_terms): '';
			$child_term_link = (!empty($child_term))? '<a class="listing-cat" href="'.get_term_link($child_term->slug, DIRECTORYPRESS_CATEGORIES_TAX).'" rel="tag">'. esc_html($child_term->name) .'</a>': '';
			
			$seperator = (!empty($child_term))? '<span class="cat-seperator fas fa-angle-right"></span>': '';
			
			echo '<div class="cat-wrapper">';
				echo wp_kses_post($parent_term_link . $seperator . $child_term_link);
			echo '</div>';
		}
	}
	add_action('directorypress_listing_grid_category_icon', 'directorypress_listing_grid_category_icon', 10, 1);
	if(!function_exists('directorypress_listing_grid_category_icon')){
		function directorypress_listing_grid_category_icon($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS;
			$cat_icon = '';
			$cat_color = '';
			$terms = wp_get_post_terms($listing->post->ID, DIRECTORYPRESS_CATEGORIES_TAX);
			if(is_array($terms)){
				foreach ($terms AS $key=>$term){
					if($DIRECTORYPRESS_ADIMN_SETTINGS['cat_icon_type_on_listing'] == 1){
							$cat_color_set = get_listing_category_color($term->term_id);
							if(!empty($cat_color_set)){
								$cat_color = 'style="background-color:'. $cat_color_set .';"';
							}else{
								$cat_color = '';
							}
							$icon_file = get_listing_category_font_icon($term->term_id);
							$icon = '<span class="font-icon" '.$cat_color.'><span class="cat-icon '.$icon_file.'"></span></span>';	
							if($icon_file){
								$cat_icon =  $icon;
							}else{
								$cat_icon = '<span class="font-icon" '.$cat_color.'><span class="cat-icon directorypress-icon-folder-o"></span></span>';
							}
						
					}elseif($DIRECTORYPRESS_ADIMN_SETTINGS['cat_icon_type_on_listing'] == 2){
						$icon_file = get_listing_category_icon_url_for_listing($term->term_id);
						$icon = '<img class="directorypress-cat-icon" src="' . $icon_file . '" alt="listing cat" />';
						if(!empty($icon_file)){
							$cat_icon =  $icon;
						}else{
							$cat_icon = '';
						}
						
					}else{
						
						$cat_color = 'style="background-color:'.$cat_color_set.';"';
						
						$icon_file = get_listing_category_font_icon($term->term_id);
						$icon = '<span class="font-icon" '.$cat_color.'><span class="cat-icon '.$icon_file.'"></span></span>';	
						if($icon_file){
							$cat_icon =  $icon;
						}else{
							$cat_icon = '';
						}
					}
				}
			}
			
			echo wp_kses_post($cat_icon);
		}
	}
	add_action('directorypress_listing_grid_title', 'directorypress_listing_grid_title', 10, 1);
	if(!function_exists('directorypress_listing_grid_title')){
		function directorypress_listing_grid_title($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
			
			$title_limit = $DIRECTORYPRESS_ADIMN_SETTINGS['max_title_length'];
			$nofollow = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_nofollow_link']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_nofollow_link'])? 'rel="nofollow"':'';
			
			echo '<header class="directorypress-listing-title">';
				if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_exert_type'] == 'words'){
					echo '<h2><a href="'. get_permalink().'" title="'. esc_attr($listing->title()) .'" '. wp_kses_post($nofollow) .'>'.wp_trim_words($listing->title(), $title_limit, '').'</a></h2>';
				}else{
					echo '<h2><a href="'. get_permalink().'" title="'. esc_attr($listing->title()) .'" '. wp_kses_post($nofollow) .'>'.substr($listing->title(), 0, $title_limit).'</a></h2>';
				}
			echo '</header>';
		}
	}
	
	add_action('directorypress_listing_grid_inline_fields', 'directorypress_listing_grid_inline_fields', 10, 1);
	if(!function_exists('directorypress_listing_grid_inline_fields')){
		function directorypress_listing_grid_inline_fields($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object, $wpdb;
			$field_ids = $wpdb->get_results('SELECT id, type, slug, on_exerpt_page, on_exerpt_page_list, is_field_in_line, options FROM '.$wpdb->prefix.'directorypress_fields');
			$include = array(
				'select',
				'radio',
				'digit',
			);
			if(isField_on_exerpt()){
				if(isField_inLine() && isField_not_empty($listing)){
					echo '<div id="fields-block-inline'. esc_attr($listing->post->ID) .'" class="grid-fields-wrapper inline-fields clearfix" data-id="'. esc_attr($listing->post->ID) .'">';
						foreach( $field_ids as $field_id ) {
							$singlefield_id = $field_id->id;
							if((($listing->listing_view == 'show_grid_style' && $field_id->on_exerpt_page) || ($listing->listing_view == 'show_list_style' && $field_id->on_exerpt_page_list)) && $field_id->is_field_in_line){	
								if(in_array($field_id->type, $include)){			
									$listing->display_content_field($singlefield_id);
								}
							}
						}
					echo '</div>';
				}
			}
		}
	}
	add_action('directorypress_listing_grid_block_fields', 'directorypress_listing_grid_block_fields', 10, 1);
	if(!function_exists('directorypress_listing_grid_block_fields')){
		function directorypress_listing_grid_block_fields($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object, $wpdb;
			$field_ids = $wpdb->get_results('SELECT id, type, slug, on_exerpt_page, on_exerpt_page_list, is_field_in_line, options FROM '.$wpdb->prefix.'directorypress_fields');
			$exclude = array(
				'summary',
				'content',
				'address',
				'category',
				'status'
			);
			if(isField_on_exerpt()  && isField_not_empty($listing)){
				echo '<div class="grid-fields-wrapper block-fields clearfix">';
					foreach( $field_ids as $field_id ) {
						$singlefield_id = $field_id->id;
						if((($listing->listing_view == 'show_grid_style' && $field_id->on_exerpt_page) || ($listing->listing_view == 'show_list_style' && $field_id->on_exerpt_page_list)) && !$field_id->is_field_in_line){	
							if((!in_array($field_id->type, $exclude)) && ($field_id->type != 'price' && $field_id->slug != 'price')){			
								$listing->display_content_field($singlefield_id);
							}
						}
					}
				echo '</div>';
			}
		}
	}
	add_action('directorypress_listing_grid_tooltip_fields', 'directorypress_listing_grid_tooltip_fields', 10, 1);
	if(!function_exists('directorypress_listing_grid_tooltip_fields')){
		function directorypress_listing_grid_tooltip_fields($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object, $wpdb;
			$field_ids = $wpdb->get_results('SELECT id, type, slug, on_exerpt_page, on_exerpt_page_list, is_field_in_line, options FROM '.$wpdb->prefix.'directorypress_fields');
			$include = array(
				'link',
				'email',
				'text',
			);
			if(isField_on_exerpt() && isField_inLine()){
				echo '<div id="fields-block-inline-tooltip'. esc_attr($listing->post->ID) .'" class="inline-tooltip-fields clearfix" data-id="'. esc_attr($listing->post->ID) .'">';
					foreach( $field_ids as $field_id ) {
						$singlefield_id = $field_id->id;
						if(($listing->listing_view == 'show_grid_style' && $field_id->on_exerpt_page) || ($listing->listing_view == 'show_list_style' && $field_id->on_exerpt_page_list)){	
							$array = unserialize($field_id->options);
							if(isset($array['is_phone'])){
								$is_phone = $array['is_phone'];
							}else{
								$is_phone = 0;
							}
							if((in_array($field_id->type, $include)) && ($field_id->type == 'text' && $is_phone == 1)){			
								$listing->display_content_field($singlefield_id);
							}
						}
					}
				
				echo '</div>';
			}
		}
	}
	add_action('directorypress_listing_grid_summary_field', 'directorypress_listing_grid_summary_field', 10, 1);
	if(!function_exists('directorypress_listing_grid_summary_field')){
		function directorypress_listing_grid_summary_field($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object, $wpdb;
			$field_ids = $wpdb->get_results('SELECT id, type, slug, on_exerpt_page, on_exerpt_page_list, is_field_in_line, options FROM '.$wpdb->prefix.'directorypress_fields');
			
			if(isField_on_exerpt()){
				echo '<div class="grid-exerpt-field clearfix">';
					foreach( $field_ids as $field_id ) {
						$singlefield_id = $field_id->id;
						if($field_id->type == 'summary' && (($listing->listing_view == 'show_grid_style' && $field_id->on_exerpt_page == 1) || ($listing->listing_view == 'show_list_style' && $field_id->on_exerpt_page_list == 1))){	
							$listing->display_content_field($singlefield_id);
						}
					}
				echo '</div>';
			}
		}
	}
	add_action('directorypress_listing_grid_address', 'directorypress_listing_grid_address', 10, 1);
	if(!function_exists('directorypress_listing_grid_address')){
		function directorypress_listing_grid_address($listing){
			
			if($listing->locations){
				echo '<p class="listing-location">';
						do_action('location_for_grid_and_list', $listing, true);
				echo '</p>';
			}
		}
	}
	add_action('directorypress_listing_grid_price_field', 'directorypress_listing_grid_price_field', 10, 1);
	if(!function_exists('directorypress_listing_grid_price_field')){
		function directorypress_listing_grid_price_field($listing){
			global $wpdb;
			$field_ids = $wpdb->get_results('SELECT id, type, slug, on_exerpt_page, on_exerpt_page_list, is_field_in_line, options FROM '.$wpdb->prefix.'directorypress_fields');
			echo '<div class="price">';
				foreach( $field_ids as $field_id ) {
					$singlefield_id = $field_id->id;
					if($field_id->type == 'price' && ($field_id->slug == 'price' || $field_id->slug == 'Price') && (($listing->listing_view == 'show_grid_style' && $field_id->on_exerpt_page) || ($listing->listing_view == 'show_list_style' && $field_id->on_exerpt_page_list))){				
						//if($field_id->on_exerpt_page == 1){
							$listing->display_content_field($singlefield_id);
						//}
					}				
				}	
			echo '</div>';
		}
	}
	
	add_action('directorypress_listing_grid_ratting', 'directorypress_listing_grid_ratting', 10, 1);
	if(!function_exists('directorypress_listing_grid_ratting')){
		function directorypress_listing_grid_ratting($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS, $direviews_plugin;
			
			$rating = (method_exists( $direviews_plugin, 'get_average_rating' ))? $direviews_plugin->get_average_rating( $listing->post->ID) : '';
			
			if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_ratings_addon']){
				if (!empty($rating)){
					echo '<div class="listing-rating grid-rating">';
						echo '<span class="rating-numbers">'. esc_html(get_average_listing_rating()) .'</span>';
						echo '<span class="rating-stars">';
							display_average_listing_rating();
						echo '</span>';
					echo '</div>';
				}else{
					echo '<div class="listing-rating grid-rating">';
						echo '<span class="rating-numbers-empty"><i class="far fa-frown-open"></i></span>';
						echo '&nbsp;<span class="review_rate-empty"><a class="simptip simptip-position-top simptip-movable" href="'. get_permalink() .'#comments-reviews" data-tooltip="'.esc_html__('Be first to rate', 'DIRECTORYPRESS').'">'.esc_html__('Rate Now', 'DIRECTORYPRESS').'</a></span>';
					echo '</div>';
				}
			}
		}
	}
	add_action('directorypress_listing_grid_bookmark', 'directorypress_listing_grid_bookmark', 10, 2);
	if(!function_exists('directorypress_listing_grid_bookmark')){
		function directorypress_listing_grid_bookmark($listing, $style, $in_favourites_icon = 'dicode-material-icons dicode-material-icons-heart', $not_in_favourites_icon = 'dicode-material-icons dicode-material-icons-heart'){
			global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
			$style_calss = 'style'. $style;
			
			if (directorypress_bookmark_list($listing->post->ID)){
				
				$link = '<a id="'.$listing->post->ID.'" href="javascript:void(0);" class="add_to_favourites btn" data-listingid="'. $listing->post->ID .'" data-toggle="tooltip" title="'. esc_attr__('Remove Bookmarks', 'DIRECTORYPRESS').'" data-in_favourites_icon="'. esc_attr($in_favourites_icon) .'" data-not_in_favourites_icon="'. esc_attr($not_in_favourites_icon) .'">';
					$link .= '<span class="favourite-icon '. esc_attr($style_calss) .' checked '. esc_attr($in_favourites_icon) .'"></span>';
				$link .= '</a>';
				
			}else{
				$link = '<a id="'.$listing->post->ID.'" href="javascript:void(0);" class="add_to_favourites btn" data-listingid="'.$listing->post->ID.'" data-toggle="tooltip" title="'. esc_attr__('Bookmark', 'DIRECTORYPRESS').'" data-in_favourites_icon="'. esc_attr($in_favourites_icon) .'" data-not_in_favourites_icon="'. esc_attr($not_in_favourites_icon) .'">';
					$link .= '<span class="favourite-icon '. esc_attr($style_calss) .' unchecked '. esc_attr($not_in_favourites_icon) .'"></span>';
				$link .= '</a>';
			}
		
			if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_favourites_list'] && $directorypress_object->action != 'myfavourites'){
				echo wp_kses_post($link);
			}
		}
	}
	add_action('directorypress_listing_grid_views', 'directorypress_listing_grid_views', 10, 1);
	if(!function_exists('directorypress_listing_grid_views')){
		function directorypress_listing_grid_views($listing){
			global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
			
			echo '<p class="listing-views">'. sprintf(esc_html__('Views: %d', 'DIRECTORYPRESS'), (get_post_meta($listing->post->ID, '_total_clicks', true) ? get_post_meta($listing->post->ID, '_total_clicks', true) : 0)).'</p>';
		}
	}
	
	if($listing_style_to_show == 'show_grid_style'){
			if ($directorypress_object->directorypress_get_property_of_shortcode('directorypress-main', 'is_favourites') && directorypress_bookmark_list($listing->post->ID)){
				echo '<div class="directorypress-remove-from-favourites-list" data-listingid="'. esc_attr($listing->post->ID) .'" title="'.esc_attr(__('Remove from favourites list', 'DIRECTORYPRESS')).'"></div>';
			}
			if($listing->listing_post_style == 'default'){
				directorypress_display_template('partials/listing/parts/template-grid-default.php', array('listing' => $listing));
			}elseif($listing->listing_post_style == 'footer_widget'){
				directorypress_display_template('partials/listing/parts/template-widget-1.php', array('listing' => $listing));
			}else{
				directorypress_display_template('partials/listing/parts/template-grid-default.php', array('listing' => $listing));
			}
	}elseif($listing_style_to_show == 'show_list_style'){
			if ($directorypress_object->directorypress_get_property_of_shortcode('directorypress-main', 'is_favourites') && directorypress_bookmark_list($listing->post->ID)){
					echo '<div class="directorypress-remove-from-favourites-list" data-listingid="'. esc_attr($listing->post->ID) .'" title="'.esc_attr(__('Remove from favourites list', 'DIRECTORYPRESS')).'"></div>';
			}
			directorypress_display_template('partials/listing/parts/template-list-1.php', array('listing' => $listing));	
	}