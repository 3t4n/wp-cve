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

JText::script('VAP_GET_DIRECTIONS_BTN');

?>

<div id="vap-loc-googlemap" style="width:100%;height:380px;margin-top:10px;"></div>

<script>

	(function($) {
		'use strict'

		$(function() {
			const COORDINATES = <?php echo json_encode($this->locations); ?>;
			
			// create map instance
			const map = new google.maps.Map(document.getElementById('vap-loc-googlemap'), {
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				zoom: 18,
			});

			// create marker info popup
			const infowindow = new google.maps.InfoWindow();

			let marker, i;
			
			const markerBounds = new google.maps.LatLngBounds();

			for (i = 0; i < COORDINATES.length; i++) {
				let position = new google.maps.LatLng(COORDINATES[i].lat, COORDINATES[i].lng);
				
				// create map marker
				marker = new google.maps.Marker({
					position: position,
					map:      map,
					icon:     '<?php echo VAPASSETS_URI . 'css/images/red-marker.png'; ?>',
				});
				
				// set Drop animation
				marker.setAnimation(google.maps.Animation.DROP);
				// adjust bounds for correct Zoom
				markerBounds.extend(position);

				// display popup when clicking the marker
				google.maps.event.addListener(marker, 'click', ((marker, i) => {
					return () => {

						let href;

						if (navigator.isMac() || navigator.isiOS()) {
							// iPhone or Mac, open through native Maps app
							href = 'maps://?q=' + COORDINATES[i].lat + ',' + COORDINATES[i].lng;

							// or use the code below to search by address rather than by coordinates
							// href = 'maps://?q=' + encodeURIComponent(COORDINATES[i].address);
						} else if (navigator.isAndroid()) {
							// Android device, open through native Google Maps
							href = 'geo:' + COORDINATES[i].lat + ',' + COORDINATES[i].lng;

							// or use the code below to search by address rather than by coordinates
							// href = 'geo:0,0?q=' + encodeURIComponent(COORDINATES[i].address);
						} else {
							// fallback to web Google Maps 
							href = 'https://maps.google.com/maps?q=' + COORDINATES[i].lat + ',' + COORDINATES[i].lng;

							// or use the code below to search by address rather than by coordinates
							// href = 'https://maps.google.com/maps?q=' + encodeURIComponent(COORDINATES[i].address);
						}

						// create button to get address directions
						const mapsBtn = $('<div class="vap-gm-infowindow-button"></div>')
							.append(
								$('<a class="vap-btn blue" target="_blank"></a>')
									.attr('href', href)
									.text(Joomla.JText._('VAP_GET_DIRECTIONS_BTN'))
							);

						infowindow.setContent(COORDINATES[i].label + mapsBtn[0].outerHTML);
						infowindow.open(map, marker);
					}
				})(marker, i));
			}
			
			if (COORDINATES.length > 1) {
				// fit zoom according to the registered bounds
				map.fitBounds(markerBounds);
			}

			map.setCenter(markerBounds.getCenter());
		});
	})(jQuery);
	
</script>
