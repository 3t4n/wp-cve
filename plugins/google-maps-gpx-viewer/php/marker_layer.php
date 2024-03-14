<?php
/*
adr_layer.php, V 1.01, altm, 10.12.2011
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
*/
						//add custom image
						if ($attr['markerimage'] !='')
						{
							$retval .= 'image = "'. $attr['markerimage'] .'";';
						}
						$retval .= '
						var marker_' . $map_id . ' = new google.maps.Marker({
							map: ' . $map_id . ', 
							';
							if ($attr['markerimage'] !='')
							{
								$retval .= 'icon: image,';
							}
						$retval .= '
							position: '.$markerPosition . ',
							title: "' . $attr['address'] . '"
						});	';

						//infowindow
						if($attr['infowindow'] != '') 
						{
							//convert and decode html chars
							$thiscontent = '<div class="gmv3_marker">' . htmlspecialchars_decode($attr['infowindow']) .  '</div>';
							$retval .= '
							var contentString = \'' . $thiscontent . '\';
							marker_' . $map_id . '.infowindow = new google.maps.InfoWindow({
								content: contentString
							});
										
							google.maps.event.addListener(marker_' . $map_id . ', \'click\', function() {
							  marker_' . $map_id . '.infowindow.open(' . $map_id . ',marker_' . $map_id . ');
							});	';
						}
?>