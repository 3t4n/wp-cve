<?php
#===============================================
# This template replaces the "Where" metabox in Edit Event pages.
# https://wordpress.org/plugins/stonehenge-em-osm/
# To edit this file, first copy it to /wp-content/themes/your-theme/stonehenge/
# VERSION: 4.2.0
# Version 4.2.0 added support for new location types.
#===============================================

global $EM_Event, $EM_Location, $EM_OSM;
$plugin			= $EM_OSM->plugin;
$options 		= get_option( $plugin['slug'] );
$text 			= $plugin['text'];
$required 		= apply_filters('em_required_html','<i>*</i>');

// Determine Locations Types.
$location_types = array();
if( !get_option('dbem_require_location') && !get_option('dbem_use_select_for_locations') ) {
	$location_types[0] = array(
		'selected' 		=>  $EM_Event->location_id === '0' || $EM_Event->location_id === 0,
		'description'	=> esc_html__('No Location','events-manager'),
	);
}
if( EM_Locations::is_enabled() ) {
	$location_types['location'] = array(
		'selected' 		=>  !empty($EM_Event->location_id),
		'display-class' => 'em-location-type-place',
		'description' 	=> esc_html__('Physical Location','events-manager'),
	);
}
foreach( EM_Event_Locations\Event_Locations::get_types() as $event_location_type => $EM_Event_Location_Class ) {
	if( $EM_Event_Location_Class::is_enabled() ) {
		$location_types[$EM_Event_Location_Class::$type] = array(
			'display-class' => 'em-event-location-type-'. $EM_Event_Location_Class::$type,
			'selected' 		=> $EM_Event_Location_Class::$type == $EM_Event->event_location_type,
			'description' 	=> $EM_Event_Location_Class::get_label(),
		);
	}
}

// Location Type dropdown.
?>
<div class="em-input-field em-input-field-select em-location-types <?php if( count($location_types) == 1 ) echo 'em-location-types-single'; ?>">
	<label><?php esc_html_e ( 'Location Type', 'events-manager')?></label>
	<select name="location_type" class="em-location-types-select">
		<?php foreach( $location_types as $location_type => $location_type_option ): ?>
		<option value="<?php echo esc_attr($location_type); ?>" <?php if( !empty($location_type_option['selected']) ) echo 'selected="selected"'; ?> data-display-class="<?php if( !empty($location_type_option['display-class']) ) echo esc_attr($location_type_option['display-class']); ?>">
			<?php echo esc_html($location_type_option['description']); ?>
		</option>
		<?php endforeach; ?>
	</select>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$('.em-location-types .em-location-types-select').change(function(){
				let el = $(this);
				if( el.val() == 0 ){
					$('.em-location-type').hide();
				}else{
					let location_type = el.find('option:selected').data('display-class');
					$('.em-location-type').hide();
					$('.em-location-type.'+location_type).show();
					if( location_type != 'em-location-type-place' ){
						jQuery('#em-location-reset a').trigger('click');
					}
					setTimeout(function(){ map.invalidateSize()}, 500); // OSM specific: reset the map else Australia is shown.
				}
			}).trigger('change');
		});
	</script>
</div>
<?php

$plugin['class']::load_admin_assets();
if( !is_object($EM_Location) ) {
	$EM_Location = empty($EM_Event->location_id) ? new EM_Location() : $EM_Event->get_location();
}

// If Location Select Dropdown is used.
if( get_option('dbem_use_select_for_locations') || !$EM_Event->can_manage('edit_locations','edit_others_locations') ) {
	include( stonehenge()->locate_template('location-select.php', 'stonehenge-em-osm') );
}
else { // Location Form Fields are used.
	// Show Location Form Fields.
	echo $EM_OSM->show_hidden_fields($EM_Location);
	echo '<div class="location-form-where em-location-type em-location-type-place">';
	echo 	'<div id="osm-location-data" class="osm-location-data">';
	echo 		'<table id="osm-location-table" class="form-table osm-location-table">';
	echo 			'<tbody>';
	echo 				'<tr class="osm-location-data-name">';
	echo 					'<th>'. __('Location Name:', 'events-manager') .' '. $required .'&nbsp;</th>';
	echo 					'<td><input id="location-name" type="text" name="location_name" value="'. esc_attr($EM_Location->output('#_LOCATIONNAME'), ENT_QUOTES) .'"></td>';
	echo 				'</tr><tr>';
	echo 					'<td colspan="2"><em id="osm-location-reset" style="display:none;">'. __em('You cannot edit saved locations here.') .'<br><a href="#">'. __em('Reset this form to create a location or search again.') .'</a></em></td>';
	echo 				'</tr>';
	include( stonehenge()->locate_template('location-form-fields.php', 'stonehenge-em-osm') );
	// </tbody></table> closes in the form fields, due to placement of search address button.
	echo 	'</div>';
	echo '</div>';
} // End Form Fields.


// Location URL.
?>
<div class="em-event-location-data osm-location-type-url">
	<?php
		foreach( EM_Event_Locations\Event_Locations::get_types() as $event_location_type => $EM_Event_Location_Class ) {
			if( $EM_Event_Location_Class::is_enabled() ) {
				echo '<div class="em-location-type em-event-location-type-'. esc_attr($event_location_type) .'"">';
				$EM_Event_Location_Class::load_admin_template();
				echo '</div>';
			}
		}
	?>
</div>
<?php
