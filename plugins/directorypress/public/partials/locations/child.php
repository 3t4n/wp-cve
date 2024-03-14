<?php global $DIRECTORYPRESS_ADIMN_SETTINGS; ?>

	<div class="directorypress-location-in-metabox-wrap clearfix">
		<div class="directorypress-location-in-metabox clearfix">
			<?php $uID = rand(1, 10000); ?>
			<input type="hidden" name="directorypress_location[<?php echo esc_attr($uID);?>]" value="1" />
			<div class="directorypress-location-metabox-dropdown">
			<?php
			if (directorypress_is_anyone_in_taxonomy(DIRECTORYPRESS_LOCATIONS_TAX)) {
				directorypress_tax_dropdowns_init(
					DIRECTORYPRESS_LOCATIONS_TAX,
					null,
					$location->selected_location,
					false,
					$locations_depths->get_names_array(),
					$locations_depths->get_selections_array(),
					$uID,
					$listing->package->selected_locations,
					false
				);
			}
			?>
			</div>
			<div class="location-input directorypress-location-input directorypress-address-line-1-wrapper" <?php if (!$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_address_line_1']): ?>style="display: none;"<?php endif; ?>>
				<label class="directorypress-submit-field-title">
					<?php
						if (!$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_address_line_2'])
							_e('Address', 'DIRECTORYPRESS');
						else
							_e('Address line 1', 'DIRECTORYPRESS');
						?>
				</label>
				<div class="has-feedback">
					<input type="text" id="address_line_<?php echo esc_attr($uID);?>" name="address_line_1[<?php echo esc_attr($uID);?>]" class="directorypress-address-line-1 form-control <?php if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_address_autocomplete']): ?>directorypress-listing-field-autocomplete<?php endif; ?>" value="<?php echo esc_attr($location->address_line_1); ?>" />
					<?php if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_address_geocode']): ?>
						<span class="directorypress-mylocation directorypress-form-control-feedback glyphicon glyphicon-screenshot"></span>
					<?php endif; ?>
				</div>
				
			</div>
			<div class="location-input directorypress-location-input directorypress-address-line-2-wrapper" <?php if (!$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_address_line_2']): ?>style="display: none;"<?php endif; ?>>
				<label class="directorypress-submit-field-title">
					<?php _e('Address line 2', 'DIRECTORYPRESS'); ?>
				</label>
				<div class="">
					<input type="text" name="address_line_2[<?php echo esc_attr($uID);?>]" class="directorypress-address-line-2 form-control" value="<?php echo esc_attr($location->address_line_2); ?>" />
				</div>
			</div>

			<div class="location-input directorypress-location-input directorypress-zip-or-postal-index-wrapper" <?php if (!$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_postal_index']): ?>style="display: none;"<?php endif; ?>>
				<label class="directorypress-submit-field-title">
					<?php _e('Zip code', 'DIRECTORYPRESS'); ?>
				</label>
				<div class="">
					<input type="text" name="zip_or_postal_index[<?php echo esc_attr($uID);?>]" class="directorypress-zip-or-postal-index form-control" value="<?php echo esc_attr($location->zip_or_postal_index); ?>" />
				</div>
			</div>

			<div class="location-input directorypress-location-input directorypress-additional-info-wrapper" <?php if (!$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_additional_info']): ?>style="display: none;"<?php endif; ?>>
				<label class="directorypress-submit-field-title">
					<?php _e('Additional info', 'DIRECTORYPRESS'); ?>
				</label>
				<div class="">
					<textarea name="additional_info[<?php echo esc_attr($uID);?>]" class="directorypress-additional-info form-control" rows="2"><?php echo esc_textarea($location->additional_info); ?></textarea>
				</div>
			</div>
			<?php if(directorypress_has_map()): ?>
				<div class="row clearfix">
					<div class="col-md-12 directorypress-manual-coords-wrapper clearfix" <?php if (!$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_manual_coords']): ?>style="display: none;"<?php endif; ?>>
						<!-- manual_coords - required in google_maps.js -->
						<div class="input-checkbox directorypress-location-input">
							<label>
								<input type="checkbox" name="manual_coords[<?php echo esc_attr($uID);?>]" value="1" class="directorypress-manual-coords" <?php if ($location->manual_coords) echo 'checked'; ?> />
								<span class="checkbox-item-name">
									<?php echo esc_html__('Enter coordinates manually', 'DIRECTORYPRESS'); ?>
								</span>
								<span class="input-checkbox-item"></span>
							</label>
						</div>
						<div class="directorypress-manual-coords-block" <?php if (!$location->manual_coords) echo 'style="display: none;"'; ?>>
							<div class="field-wrap directorypress-location-input">
								<label class="directorypress-submit-field-title"><?php _e('Latitude', 'DIRECTORYPRESS'); ?></label>
								<input type="text" name="map_coords_1[<?php echo esc_attr($uID);?>]" class="directorypress-map-coords-1 form-control" value="<?php echo esc_attr($location->map_coords_1); ?>">
							</div>
			
							<div class="field-wrap directorypress-location-input">
								<p class="directorypress-submit-field-title"><?php _e('Longitude', 'DIRECTORYPRESS'); ?></p>
								<input type="text" name="map_coords_2[<?php echo esc_attr($uID);?>]" class="directorypress-map-coords-2 form-control" value="<?php echo esc_attr($location->map_coords_2); ?>">
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="remove-address-btn" <?php if (!$delete_location_link) echo 'style="display:none;"'; ?>>
				<a href="javascript: void(0);" class="delete_location"><?php echo esc_html__('Delete Address', 'DIRECTORYPRESS'); ?></a>
			</div>
		</div>
		
	</div>