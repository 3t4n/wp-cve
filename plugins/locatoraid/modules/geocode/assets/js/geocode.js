(function($) {

jQuery(document).on('hc2-gmaps-loaded', function()
{
	var map_div = "hclc_map";
	var $map = jQuery('#' + map_div);

	var try_address = $map.data('address');

	$map.append( try_address + '<br/>' );

	hc2_geocode(
		try_address,
		handle_geocode_result
	);

	function handle_geocode_result( success, results, return_status )
	{
		if( success || (return_status == google.maps.GeocoderStatus.ZERO_RESULTS) ){
			var this_found = false;
			var this_coord = {};
			var this_results_view = '';

			switch( return_status ){
				case google.maps.GeocoderStatus.ZERO_RESULTS:
					this_found = true;
					this_coord = {
						lat: -1,
						lng: -1
					};
					this_results_view = results;
					break;

				case google.maps.GeocoderStatus.OK:
					this_found = true;
					this_coord = {
						lat: results.lat,
						lng: results.lng
					};
					this_results_view = this_coord.lat + ' / ' + this_coord.lng;
					break;

				default:
					this_results_view = results;
					break;
			}

			if( this_found ){
				$map.append( this_results_view  + '<br/>' );

			// save
				var save_url = $map.data('save-url');
				save_url = save_url
					.replace( '_LATITUDE_', this_coord.lat )
					.replace( '_LONGITUDE_', this_coord.lng )
					;

				$map.append( 'Save ' + '<br/>' );
				// $map.append( save_url + '<br/>' );
console.log( save_url );
				jQuery.ajax( save_url )
					.done( function(){
						$map.append( 'OK' + '<br/>' );

					// show on map
						var map = hc2_init_gmaps( map_div );

						var location_position = new google.maps.LatLng( 
							this_coord.lat,
							this_coord.lng
							);
						map.setCenter( location_position );

						var marker = new google.maps.Marker({
							map: map,
							position: location_position,
							draggable: false,
							visible: true,
						});
					})
					.error( function(){
						$map.append( 'ERROR' + '<br/>' );
					})
					;
			}
			else {
				var error_msg = 'Geocoding error: ' + return_status;
				$map.append( error_msg  + '<br/>' );
			}
		}
		else {
			var error_msg = 'Geocoding error: ' + return_status;
			$map.append( error_msg  + '<br/>' );
		}
	}
});

}());
