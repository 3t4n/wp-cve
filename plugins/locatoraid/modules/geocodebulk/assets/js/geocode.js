(function($) {

jQuery(document).on('hc2-gmaps-loaded', function()
{
	var map_div = "hclc_map";
	var $map = jQuery('#' + map_div);
	var remain_to_process = 0;

	var json_url = $map.data('json-url');
	console.log( json_url );

	jQuery.getJSON(
		json_url,
		process_locations
		)
		.error( function(data)
		{
			alert( 'Error parsing JSON from ' + json_url );
			alert(JSON.stringify(data));
		}
	);

	function process_locations( data )
	{
		remain_to_process = data['locations'].length;
		for( var ii = 0; ii < data['locations'].length; ii++ ){
			process_location( data['locations'][ii]['id'], data['locations'][ii]['address'] );
		}
	}

	function process_location( id, try_address )
	{
		var $result_div = jQuery(
			'<div>', 
			{
				'class': 'hc-p2 hc-mr2 hc-border hc-mb2 hc-rounded hc-left',
				'style': 'width: 45%; height: 6em;'
			}
		);

		$map.append( $result_div );
		$result_div.append( try_address + '<br/>' );

		hc2_geocode(
			try_address,
			handle_geocode_result,
			id,
			$result_div
		);
	}

	function mark_location_processed( id )
	{
		remain_to_process--;
		if( remain_to_process <= 0 ){
			location.reload();
		}
	}

	function handle_geocode_result( success, results, return_status, this_id, $this_result_div )
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
				$this_result_div.append( this_results_view + '<br/>' );

			// save
				var save_url = $map.data('save-url');
				save_url = save_url
					.replace( '_ID_',			this_id )
					.replace( '_LATITUDE_',		this_coord.lat )
					.replace( '_LONGITUDE_',	this_coord.lng )
					;

				$this_result_div.append( 'Saving ... ' + '<br/>' );
				// $map.append( save_url + '<br/>' );
console.log( save_url );
				jQuery.ajax( save_url )
					.done( function(){
						$this_result_div.append( 'OK' + '<br/>' );
						mark_location_processed( this_id );
					})
					.error( function(){
						$this_result_div.append( 'ERROR' + '<br/>' );
						mark_location_processed( this_id );
					})
					;
			}
			else {
				var error_msg = 'Geocoding error: ' + return_status;
				$this_result_div.append( error_msg  + '<br/>' );
				mark_location_processed( this_id );
			}
		}
		else {
			var error_msg = 'Geocoding error: ' + return_status;
			$this_result_div.append( error_msg  + '<br/>' );
			mark_location_processed( this_id );
		}
	}
});

}());
