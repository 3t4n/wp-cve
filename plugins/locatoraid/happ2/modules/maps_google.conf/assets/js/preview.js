(function($) {

jQuery(document).on('hc2-gmaps-loaded', function()
{
	var self = this;

	var map_div = "hclc_map";
	var map = hc2_init_gmaps( map_div );
	var $map = jQuery('#' + map_div);

	var location_position = new google.maps.LatLng( 
		39.7581599,
		-104.927918
		);

	map.setCenter( location_position );

	this.$preview_button = jQuery('.hcj2-map-preview');
	this.$preview_button.on('click', function(e){
		var style_code = jQuery('.hcj2-map-style').find('textarea').val();
		if( style_code.length ){
			var validStyle = hc2_try_parse_json( style_code );
			if( validStyle ){
				map.setOptions({ styles: validStyle });
			}
			else {
				alert('Not valid!');
			}
		}
		return false;
	});
});

}());
