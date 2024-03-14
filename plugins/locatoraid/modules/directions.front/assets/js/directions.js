(function($) {
jQuery(document).on('click', '.lpr-directions', function()
{
	var to_url = 'https://www.google.com/maps/dir';

	var directions_from = new google.maps.LatLng( jQuery(this).data('from-lat'), jQuery(this).data('from-lng') );
	var directions_to = new google.maps.LatLng( jQuery(this).data('to-lat'), jQuery(this).data('to-lng') );

	to_url += '/' + directions_from.lat() + ',' + directions_from.lng();
	to_url += '/' + directions_to.lat() + ',' + directions_to.lng();
	window.open(to_url, '_blank');
	return false;
});
}());