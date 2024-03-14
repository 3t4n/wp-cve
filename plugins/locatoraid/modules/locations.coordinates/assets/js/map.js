(function($) {

jQuery(document).on('hc2-gmaps-loaded', function()
{
	var map_div = "hclc_map";

	var $map = jQuery('#' + map_div);
	if( ! $map.data('latitude') ){
		return false;
	}

	var map = hc2_init_gmaps( map_div );

	map.setOptions({ zoomControl: true });

	var can_edit = $map.data('edit');
	var custom_icon = $map.data('icon');

	var location_position = new google.maps.LatLng( 
		$map.data('latitude'),
		$map.data('longitude')
		);

	map.setCenter( location_position );

	var marker = new google.maps.Marker({
		map: map,
		position: location_position,
		draggable: false,
		visible:true,
	});

	if( can_edit ){
		marker.draggable = true;
	}

	if( custom_icon ){
		marker.setIcon( custom_icon );
	}

	function update_marker_position( latLng )
	{
		$map.closest('.hcj2-container').find('input[name\$=latitude]').val( latLng.lat() );
		$map.closest('.hcj2-container').find('input[name\$=longitude]').val( latLng.lng() );
	}

	if( can_edit ){
		google.maps.event.addListener( marker, 'drag', function() {
			update_marker_position( marker.getPosition() );
		});
	}
});

}());
