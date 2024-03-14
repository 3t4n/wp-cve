<?php
/**
 * @package     VikRentItems
 * @subpackage  com_vikrentitems
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

defined('ABSPATH') or die('No script kiddies please!');

$pbaseaddress = $this->pbaseaddress;

$document = JFactory::getDocument();
JHtml::fetch('jquery.framework', true, true);
JHtml::fetch('script', VRI_SITE_URI.'resources/jquery-1.12.4.min.js');
$document->addScript('https://maps.google.com/maps/api/js?key='.VikRentItems::getGoogleMapsKey());
?>
<style>
html, body, #map-canvas {
	height: 100%;
	min-height: 500px;
	margin: 0;
	padding: 0;
}
#vripanel {
	position: absolute;
	top: 5px;
	left: 30%;
	margin-left: -180px;
	z-index: 5;
	background-color: #fff;
	padding: 5px;
	border: 1px solid #999;
}
</style>
<script type="text/javascript">
jQuery.noConflict();
var directionsDisplay;
var baseaddress = '<?php echo addslashes($pbaseaddress); ?>';
var geocoder;
function initialize() {
	directionsDisplay = new google.maps.DirectionsRenderer();
	var mybase = new google.maps.LatLng(41.869561,12.498779);
	var mapOptions = {
		zoom: 3,
		center: mybase,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
	directionsDisplay.setMap(map);
	
	geocoder = new google.maps.Geocoder();
	var location1;
	if (baseaddress.length > 0) {
		geocoder.geocode( { 'address': baseaddress}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				location1 = results[0].geometry.location;
				var marker = new google.maps.Marker({
					map: map,
					position: location1
				});
				var baselatitude = location1.lat();
				var baselongitude = location1.lng();
				if (window.parent) {
					var windoc = window.parent.document;
				} else {
					var windoc = document;
				}
				windoc.getElementById('deliverybaselat').value = baselatitude;
				windoc.getElementById('deliverybaselng').value = baselongitude;
				jQuery("#vribaseposition").text("("+baselatitude+", "+baselongitude+")");
				jQuery(".vrivalidategmapbaseaddrp").addClass("vrisuccessbg");
				jQuery('#vripanel').append('<p class="vri-valid-geoaddress-par"><i class="fas fa-check-circle"></i> <?php echo addslashes(JText::translate('VRICHECKDELADDGMAPOK')); ?></p>');
			} else {
				jQuery(".vrivalidategmapbaseaddrp").addClass("vrierrorbg");
				jQuery('#vripanel').append('<p class="vri-invalid-geoaddress-par"><i class="fas fa-times-circle"></i> <?php echo addslashes(JText::translate('VRICHECKDELADDGMAPERR2')); ?>'+status+'</p>');
			}
		});
	} else {
		jQuery(".vrivalidategmapbaseaddrp").addClass("vrierrorbg");
		jQuery('#vripanel').append('<p class="vri-invalid-geoaddress-par"><i class="fas fa-times-circle"></i> <?php echo addslashes(JText::translate('VRICHECKDELADDGMAPERR1')); ?></p>');
	}
}

google.maps.event.addDomListener(window, 'load', initialize);

</script>

<div id="vripanel">
	<p class="vrivalidategmapbaseaddrp"><?php echo JText::translate('VRICONFDELBASEADDR'); ?>: <?php echo $pbaseaddress; ?> <span id="vribaseposition"></span></p>
</div>

<div id="map-canvas"></div>
