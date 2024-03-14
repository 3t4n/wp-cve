<?php 
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	$public_handler->args['scroll'] = 0 ;
	$public_handler->args['scroller_nav_style'] = 2 ;
	
	echo '<div class="listings listing-archive archive-style-nosidebar">';
			if(!empty($public_handler->archive_top_banner)){
				echo '<div class="archive-banner">';
					echo wp_kses_post($public_handler->archive_top_banner);
				echo '</div>';
			}
			if (directorypress_has_map() && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_map_on_excerpt']){
				echo '<div class="map-listings">';
					if(directorypress_get_term_by_path(get_query_var('location-directorypress'))){
						$public_handler->map->display(false, false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_radius_search_cycle'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_clusters'], true, true, false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_default_map_height'], false, 10, directorypress_map_name_selected(), false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_draw_panel'], false, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_full_screen'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_wheel_zoom'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_dragging_touchscreens'], $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_center_map_onclick']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				echo '</div>';
			}
			if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_main_search']){
				echo '<div class="main-search-bar">';
					if(directorypress_get_term_by_path(get_query_var('location-directorypress'))){
						$public_handler->search_form->display(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				echo '</div>';
				if(!empty($public_handler->archive_below_search_banner)){
					echo '<div class="archive-banner">';
						echo wp_kses_post($public_handler->archive_below_search_banner);
					echo '</div>';
				}
			}
			//directorypress_renderMessages();
			if ($parent_location = directorypress_get_term_by_path(get_query_var('location-directorypress'))){
				echo '<div class="archive-locations-wrapper location-grid-wrapper clearfix">';
						directorypress_displayLocationsTable($parent_location->term_id); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
				$terms = get_terms( array(
					'taxonomy' => DIRECTORYPRESS_LOCATIONS_TAX,
					'parent' => $parent_location->term_id,
				) );
				echo '<div class="archive-banner">';
					echo wp_kses_post($public_handler->archive_below_locations_banner);
				echo '</div>';
			}
			
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