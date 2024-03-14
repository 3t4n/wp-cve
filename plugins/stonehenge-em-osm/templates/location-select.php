<?php
#===============================================
# This template creates the Drowp Down Select box for EM Locations.
# https://wordpress.org/plugins/stonehenge-em-osm/
# To edit this file, first copy it to /wp-content/themes/your-theme/stonehenge/
# VERSION: 4.2.0
# Since 4.2.0 supports location types.
#===============================================
global $EM_OSM;
echo $EM_OSM->show_hidden_fields($EM_Location);
?>
<div id="osm-location-data" class="osm-location-data em-location-type em-location-type-place">
	<table id="osm-location-table" class="osm-location-table form-table">
		<tr class="em-location-data-select">
			<td><strong><?php echo __em('Location:'); ?></strong><br>
				<select name="location_id" id='location-select-id' size="1">
					<?php
					// Set first option.
					$no_location 	= __em('No Location');
					$disabled 		= get_option('dbem_require_location', true) ? 'disabled' : '';
					$first			= get_option('dbem_require_location', true) ? '- '. __wp('Select') .' -' : $no_location;
					$saved 			= !empty($EM_Event->location_id) ? $EM_Event->location_id : get_option('dbem_default_location');
					?>
					<option value="0" title="0,0" balloon="<?php echo esc_attr($no_location, ENT_QUOTES); ?>" data-marker="<?php echo esc_attr($options['marker'], ENT_QUOTES); ?>" data-maptile="<?php echo esc_attr($options['type'], ENT_QUOTES); ?>" <?php echo esc_attr($disabled, ENT_QUOTES); ?>><?php echo esc_attr($first, ENT_QUOTES); ?></option>
					<?php
					// Fetch Locations from the database.
					$ddm_args 			= array( 'private' => $EM_Event->can_manage('read_private_locations') );
					$ddm_args['owner']	= is_user_logged_in() && !current_user_can('read_others_locations') ? get_current_user_id() : false;
					$EM_Locations 		= EM_Locations::get( $ddm_args );
					foreach( $EM_Locations as $EM_Location ) {
						// Use output() to correctly process html entities (Let EM do all the work)
						$id 		= esc_attr( $EM_Location->output("#_LOCATIONID"), ENT_QUOTES);
						$latitude	= esc_attr( $EM_Location->output("#_LOCATIONLATITUDE"), ENT_QUOTES);
						$longitude	= esc_attr( $EM_Location->output("#_LOCATIONLONGITUDE"), ENT_QUOTES);
						$name 		= esc_attr( $EM_Location->output("#_LOCATIONNAME"), ENT_QUOTES);
						$balloon 	= esc_attr( $EM_Location->output("<strong>#_LOCATIONNAME</strong><br>#_LOCATIONADDRESS, #_LOCATIONTOWN"), ENT_QUOTES);
						$selected 	= ($id === $saved) ? "selected='selected'" : "";
						$maptiles 	= $EM_OSM->get_location_tiles( $EM_Location );
						$marker 	= $EM_OSM->get_location_marker( $EM_Location );
						echo sprintf('<option value="%1$s" title="%2$s,%3$s" balloon="%4$s" data-marker="%5$s" data-maptile="%6$s" %7$s>%8$s</option>',
							$id, $latitude, $longitude, $balloon, $marker, $maptiles, $selected, $name);
					} // End foreach()
					?>
				</select>
			</td>
		</tr>
	</table>
	<?php echo $EM_OSM->admin_map($EM_Location); ?>
	<br style="clear:both;">
</div>
