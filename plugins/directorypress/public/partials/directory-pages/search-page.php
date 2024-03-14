<?php 
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	$public_handler->args['scroll'] = 0 ;
	$public_handler->args['scroller_nav_style'] = 2 ;
	if(($public_handler->args['listings_view_type'] == 'grid' && !isset($_COOKIE['directorypress_listings_view_'.$public_handler->hash])) || (isset($_COOKIE['directorypress_listings_view_'.$public_handler->hash]) && $_COOKIE['directorypress_listings_view_'.$public_handler->hash] == 'grid')){
		$listing_style_to_show = 'show_grid_style';
	}elseif(($public_handler->args['listings_view_type'] == 'grid' && !isset($_COOKIE['directorypress_listings_view_'.$public_handler->hash])) || (isset($_COOKIE['directorypress_listings_view_'.$public_handler->hash]) && $_COOKIE['directorypress_listings_view_'.$public_handler->hash] == 'list')){
		$listing_style_to_show = 'show_list_style';
	}elseif(($public_handler->args['listings_view_type'] == 'list' && !isset($_COOKIE['directorypress_listings_view_'.$public_handler->hash])) || (isset($_COOKIE['directorypress_listings_view_'.$public_handler->hash]) && $_COOKIE['directorypress_listings_view_'.$public_handler->hash] == 'list')){
		$listing_style_to_show = 'show_list_style';
	}elseif(($public_handler->args['listings_view_type'] == 'list' && !isset($_COOKIE['directorypress_listings_view_'.$public_handler->hash])) || (isset($_COOKIE['directorypress_listings_view_'.$public_handler->hash]) && $_COOKIE['directorypress_listings_view_'.$public_handler->hash] == 'grid')){
		$listing_style_to_show = 'show_grid_style';
	}
	
	
	echo '<div class="listings listing-archive directorypress-archive-search archive-style-nosidebar">';
			if(!empty($public_handler->archive_top_banner)){
				echo '<div class="archive-banner">';
					echo wp_kses_post($public_handler->archive_top_banner);
				echo '</div>';
			}
			if (directorypress_has_map() && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_map_on_excerpt']){
				echo '<div class="map-listings">';
					$public_handler->map->display(false, false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_radius_search_cycle'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_clusters'], true, true, false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_map_height'], false, 10, directorypress_map_name_selected(), false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_draw_panel'], false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_full_screen'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_wheel_zoom'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_dragging_touchscreens'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_center_map_onclick']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
				echo '</div>';
			}
			if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_main_search']){
				echo '<div class="main-search-bar">';
						  $public_handler->search_form->display(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
				if(!empty($public_handler->archive_below_search_banner)){
					echo '<div class="archive-banner">';
						echo wp_kses_post($public_handler->archive_below_search_banner);
					echo '</div>';
				}
			}
			directorypress_renderMessages();
			echo '<div class="archive-listings-wrapper">';
				directorypress_display_template('partials/listing/wrapper.php', array('public_handler' => $public_handler));
				echo '<div class="directorypress-content-wrap" id="directorypress-handler-'. esc_attr($public_handler->hash) .'" data-handler-hash="'. esc_attr($public_handler->hash) .'"></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
			if(!empty($public_handler->archive_below_listings_banner)){
				echo '<div class="archive-banner">';
					echo wp_kses_post($public_handler->archive_below_listings_banner);
				echo '</div>';
			}
	echo '</div>';
	