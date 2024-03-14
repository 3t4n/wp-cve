<?php
#===============================================
# This template replaces the "Where" metabox in Edit Location pages.
# https://wordpress.org/plugins/stonehenge-em-osm/
# To edit this file, first copy it to /wp-content/themes/your-theme/stonehenge/
# VERSION: 4.0.0
#===============================================

global $EM_Location, $EM_OSM;
$plugin			= $EM_OSM->plugin;
$required 		= apply_filters('em_required_html','<i>*</i>');
$location_id 	= isset($EM_Location->location_id) && !empty($EM_Location->location_id) ? $EM_Location->location_id : '';

$plugin['class']::load_admin_assets();
?>
<input type="hidden" name="_emnonce" value="<?php echo wp_create_nonce('edit_location'); ?>">
<?php echo $EM_OSM->show_hidden_fields($EM_Location); ?>
<div id="osm-location-data" class="osm-location-data">
	<table id="osm-location-table" class="form-table osm-location-table">
		<?php include( stonehenge()->locate_template('location-form-fields.php', 'stonehenge-em-osm') ); ?>
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
