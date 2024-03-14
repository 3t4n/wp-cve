<?php global $DIRECTORYPRESS_ADIMN_SETTINGS; ?>

<script>
	(function($) {
		"use strict";
	
		$(function() {
			var location_number_allowed = <?php echo esc_attr($listing->package->location_number_allowed); ?>;
			
			$(".add_address").click(function() {
				directorypress_ajax_loader_show();
				$.ajax({
					type: "POST",
					url: directorypress_js_instance.ajaxurl,
					data: {'action': 'directorypress_add_location_in_metabox', 'post_id': <?php echo esc_attr($listing->post->ID); ?>},
					success: function(response_from_the_action_function){
						if (response_from_the_action_function != 0) {
							$("#directorypress-locations-wrapper").append(response_from_the_action_function);
							$(".remove-address-btn").show();
							if (location_number_allowed == $(".directorypress-location-in-metabox-wrap").length){
								$(".add_address").hide();
								if(directorypress_js_instance.has_map){
									directorypress_setupAutocomplete();
								}
							}
						}
					},
					complete: function() {
						directorypress_ajax_loader_hide();
					}
				});
			});
			$(document).on("click", ".delete_location", function() {
				$(this).parents(".directorypress-location-in-metabox-wrap").remove();
				if ($(".directorypress-location-in-metabox-wrap").length == 1)
					$(".remove-address-btn").hide();
	
				if(directorypress_js_instance.has_map){
					directorypress_generateMap_backend();
				}
	
				if (location_number_allowed > $(".directorypress-location-in-metabox-wrap").length)
					$(".add_address").show();
			});
	
			$(document).on("click", ".directorypress-manual-coords", function() {
	        	if ($(this).is(":checked"))
	        		$(this).parents(".directorypress-manual-coords-wrapper").find(".directorypress-manual-coords-block").show(200);
	        	else
	        		$(this).parents(".directorypress-manual-coords-wrapper").find(".directorypress-manual-coords-block").hide(200);
	        });
	
	        if (location_number_allowed > $(".directorypress-location-in-metabox-wrap").length)
				$(".add_address").show();
		});
	})(jQuery);
</script>

<div class="directorypress-locations-metabox">
	<div id="directorypress-locations-wrapper" class="form-horizontal">
		<?php
		if ($listing->locations){
			foreach ($listing->locations AS $location){
				directorypress_display_template('partials/locations/child.php', array('listing' => $listing, 'location' => $location, 'locations_depths' => $locations_depths, 'delete_location_link' => (count($listing->locations) > 1) ? true : false));
			}
		}else{
				directorypress_display_template('partials/locations/child.php', array('listing' => $listing, 'location' => new directorypress_location, 'locations_depths' => $locations_depths, 'delete_location_link' => false));
		}
		?>
	</div>
	<?php if ($listing->package->location_number_allowed > 1): ?>
		<div class="add-address-btn">	
			<?php echo '<a class="add_address" style="display: none;" href="javascript:void(0);" title="'. __('Add address', 'DIRECTORYPRESS').'" ><i class="fas fa-plus-circle"></i></a>'; ?>
		</div>
	<?php endif; ?>
	<?php if(directorypress_has_map()): ?>
		<div class="directorypress-maps-canvas-wrap">
			<div class="generate-map-btn">
				<input type="hidden" name="map_zoom" class="directorypress-map-zoom" value="<?php echo esc_attr($listing->map_zoom); ?>" />
				<input type="button" class="generate-on-map" onClick="directorypress_generateMap_backend(); return false;" value="<?php esc_attr_e('Generate map', 'DIRECTORYPRESS'); ?>" />
			</div>
			<div class="directorypress-maps-canvas" id="directorypress-maps-canvas" style="width: auto; height: 450px;"></div>
		</div>
	<?php endif; ?>
</div>