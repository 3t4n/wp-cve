<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if( !class_exists('Stonehenge_EM_OSM_Metabox') ) :
Class Stonehenge_EM_OSM_Metabox extends Stonehenge_EM_OSM_Admin {


	#===============================================
	public static function edit_location() {
		global $EM_Location, $post;
		$plugin			= $this->plugin;
		$options 		= get_option( $plugin['slug'] );
		$required 		= apply_filters('em_required_html','<i>*</i>');
		$location_id 	= isset($EM_Location->location_id) && !empty($EM_Location->location_id) ? $EM_Location->location_id : '';

		$plugin['class']::load_admin_assets();
		?>
		<input type="hidden" name="_emnonce" value="<?php echo wp_create_nonce('edit_location'); ?>">
		<?php echo $this->show_hidden_fields($location_id); ?>
		<div id="osm-location-data" class="osm-location-data">
			<table id="osm-location-table" class="form-table osm-location-table">
				<?php include('location-form-fields.php'); ?>
			<br style="clear:both;">
		</div>
		<script>
		// Make marker draggable.
		marker.dragging.enable();
		marker.on('dragend', function(e) {
			jQuery('#location-latitude').val( marker.getLatLng().lat );
			jQuery('#location-longitude').val( marker.getLatLng().lng );
		});
		</script>
		<?php
	}


	#===============================================
	public static function edit_event( $EM_Event ) {
		global $EM_Event, $EM_Location;
		$plugin			= $this->plugin;
		$options 		= get_option( $plugin['slug'] );
		$text 			= $plugin['text'];
		$required 		= apply_filters('em_required_html','<i>*</i>');
		$location_id 	= isset($EM_Event->location_id) && !empty($EM_Event->location_id) ? $EM_Event->location_id : '';
		$EM_Location	= !is_object($EM_Location) && !empty($location_id) ? $EM_Event->get_location() : new EM_Location();

		$plugin['class']::load_admin_assets();
		echo $this->show_hidden_fields($location_id);

		// If Location Select Dropdown is used.
		if( get_option('dbem_use_select_for_locations') || !$EM_Event->can_manage('edit_locations','edit_others_locations') ) {
			include('location-select.php');
		}
		else { // Location Form Fields are used.
			// If Location is not required, show the checkbox.
			if( !get_option('dbem_require_location') && !get_option('dbem_use_select_for_locations') ) {
				?>
				<div class="osm-location-data-no-location">
					<p><label for='no-location'><input type="checkbox" name="no_location" id="no-location" value="1" <?php if( $EM_Event->location_id === '0' || $EM_Event->location_id === 0 ) echo 'checked="checked"'; ?> />&nbsp; <?php echo __em('This event does not have a physical location.'); ?></label></p>
					<br style="clear:both;">
				</div>
				<?php
			}
			// Show Location Form Fields.
			?>
			<div class="location-form-where">
				<div id="osm-location-data" class="osm-location-data">
					<table id="osm-location-table" class="form-table osm-location-table">
					<tbody>
						<tr class="osm-location-data-name">
							<th><?php _e('Location Name:', 'events-manager')?> <?php echo $required; ?>&nbsp;</th>
							<td><input id="location-name" type="text" name="location_name" value="<?php echo esc_attr($EM_Location->output('#_LOCATIONNAME'), ENT_QUOTES); ?>"></td>
						</tr>
						<tr>
							<td colspan="2">
								<em id="osm-location-reset" style="display:none;"><?php echo __em('You cannot edit saved locations here.'); ?><br><a href="#"><?php echo __em('Reset this form to create a location or search again.')?></a></em>
							</td>
						</tr>
						<?php include('location-form-fields.php'); ?>
					</tbody>
				</div>
			</div>
		<?php
		} // End Form Fields.
	}

} // End class.
endif;