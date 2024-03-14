<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

JHtml::fetch('vaphtml.assets.googlemaps');

$city = $this->city;

$vik = VAPApplication::getInstance();

if (!$city->latitude || !$city->longitude)
{
	// display notice
	echo $vik->alert(JText::translate('VAPMANAGEEMPLOCATION16'), 'info', false, array('id' => 'city-map-warning'));
}

?>

<div id="city-googlemap" style="width:100%;height:400px;<?php echo (!empty($city->latitude) ? 'display:none;' : ''); ?>"></div>

<script>

	(function($) {
		'use strict';

		let map, marker;

		<?php
		if (!empty($city->latitude) && !empty($city->longitude))
		{
			?>
			let cityLat = <?php echo floatval($city->latitude); ?>;
			let cityLng = <?php echo floatval($city->longitude); ?>;
			<?php
		}
		else
		{
			?>
			let cityLat = '';
			let cityLng = '';
			<?php
		}
		?>

		window['evaluateCoordinatesFromCity'] = (address) => {
			if (address.length == 0) {
				return;
			}

			address += ' <?php echo addslashes($this->state->state_name . ' ' . $this->state->country_2_code); ?>';

			const geocoder = new google.maps.Geocoder();

			let coord = null;

			geocoder.geocode({address: address}, (results, status) => {
				if (status == 'OK') {
					coord = {
						lat: results[0].geometry.location.lat(),
						lng: results[0].geometry.location.lng(),
					};

					$('#vap-city-latitude').val(coord.lat);
					$('#vap-city-longitude').val(coord.lng);

					changeCityLatLng(coord.lat, coord.lng);
				}
			});
		}
		
		window['changeCityLatLng'] = (lat, lng) => {
			cityLat = lat;
			cityLng = lng;

			if (cityLat.length == 0 || cityLng.length == 0) {
				cityLat = cityLng = '';
			}

			initializeMap();
		}
		
		const initializeMap = () => {
			if (cityLat.length == 0) {
				$('#city-googlemap').hide();
				$('#city-map-warning').show();
				return;
			}

			const coord = new google.maps.LatLng(cityLat, cityLng);

			$('#city-map-warning').hide();

			if (map) {
				// map already created, just display it
				$('#city-googlemap').show();
				// and update the marker
				marker.setAnimation(google.maps.Animation.DROP);
				marker.setPosition(coord);
				map.setCenter(coord);
				return;
			}
			
			const mapProp = {
				center: coord,
				zoom: 14,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
			};
			
			map = new google.maps.Map($('#city-googlemap')[0], mapProp);

			// create marker
			marker = new google.maps.Marker({
				position: coord,
				draggable: true,
			});

			// update circle position after dragging the marker
			marker.addListener('dragend', (e) => {
		        const markerCoord = marker.getPosition();

		        $('#vap-city-latitude').val(markerCoord.lat());
		        $('#vap-city-longitude').val(markerCoord.lng());
		    });
				
			marker.setMap(map);
			
			$('#city-googlemap').show();
		}

		$(function() {
			initializeMap();
		});
	})(jQuery);
	
</script>
