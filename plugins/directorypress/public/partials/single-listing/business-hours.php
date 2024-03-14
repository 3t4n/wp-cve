<?php
/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/partials/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/

	global $wpdb;
	$field_ids = $wpdb->get_results('SELECT id, type, slug FROM '.$wpdb->prefix.'directorypress_fields');
	foreach( $field_ids as $field_id ) {
		$singlefield_id = $field_id->id;
		if(isset($listing->fields[$singlefield_id])){
			if($field_id->type == 'hours' && $listing->fields[$singlefield_id]->is_field_not_empty($listing) ){	
				echo '<div class="business-hours-wrapper" style="position:relative;">';
					echo '<div class="business-hours-header clearfix">';
						echo '<i class="far fa-clock"></i>';
						$listing->hours_field_status($singlefield_id, true);
						echo '<a class="business-hours-drop" href="#"><i class="fas fa-angle-down"></i></a>';
					echo '</div>';
					echo '<div class="business-hours-content" style="display:none;">';
						$listing->display_content_field($singlefield_id);
					echo '</div>';
				echo '</div>';
			}
		}
	}