<?php 
	$listing = $GLOBALS['listing_id'];
	$public_handler = $GLOBALS['hash'];
	if ($listing->locations && directorypress_has_map()){
		global $DIRECTORYPRESS_ADIMN_SETTINGS, $wpdb;
		
		$field_ids = $wpdb->get_results('SELECT id, type, slug FROM '.$wpdb->prefix.'directorypress_fields');
		foreach( $field_ids as $field_id ) {
			$singlefield_id = $field_id->id;
			if($field_id->type == 'link' && ($field_id->slug == 'website') ){			
				$website_link = $singlefield_id;
			}				
		}
		echo wp_kses_post($args['before_widget']);
		if (!empty($title)){
			echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
		}
		echo '<div class=" directorypress-widget directorypress_map_widget">';
			echo '<div id="addresses-widget">';
				$listing->display_map($public_handler->hash, $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_directions'], false, false, false, false, false);
				echo '<div class="address-in-widget">';
					foreach ($listing->locations AS $location){
						echo '<i class="dicode-material-icons dicode-material-icons-map-marker-outline"></i>';
						echo wp_kses_post($location->get_full_address());
					}
				echo '</div>';
				if(!empty($website_link)){
					 if(isset($listing->fields[$website_link])){
						echo  '<div class="directorypress-website-link clearfix">';
							echo '<i class="fas fa-globe"></i>';
							echo esc_html($listing->fields[$website_link]->value['url']);
						echo '</div>';
					}
				}
				do_action('directorypress-listing-social-links', $listing);
			echo '</div>';
		echo '</div>';
		echo wp_kses_post($args['after_widget']);
	}else{
		// silent 
	}