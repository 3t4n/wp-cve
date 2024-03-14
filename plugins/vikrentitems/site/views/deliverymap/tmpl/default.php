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


$baseaddress = VikRentItems::getDeliveryBaseAddress();
$calcunit = VikRentItems::getDeliveryCalcUnit();
$costperunit = VikRentItems::getDeliveryCostPerUnit();
$maxdistance = VikRentItems::getDeliveryMaxDistance();
$maxcost = VikRentItems::getDeliveryMaxCost();
$mapnotes = VikRentItems::getDeliveryMapNotes();
$rounddistance = VikRentItems::getDeliveryRoundDistance();
$roundcost = VikRentItems::getDeliveryRoundCost();
$currencysymb = VikRentItems::getCurrencySymb();

$pdelto = VikRequest::getString('delto', '', 'request');
$pitemquant = VikRequest::getInt('itemquant', 1, 'request');
$item = $this->item;
$overcostperunit = floatval(VikRentItems::getItemParam($item['params'], 'overdelcost'));
if (!empty($overcostperunit) && $overcostperunit > 0.00) {
	$costperunit = $overcostperunit;
}

$document = JFactory::getDocument();
$document->addScript(VRI_SITE_URI.'resources/jquery-1.12.4.min.js');
$document->addScript('https://maps.google.com/maps/api/js?key='.VikRentItems::getGoogleMapsKey());

?>
<style>
html, body, #map-canvas {
	height: 100%;
	margin: 0px;
	padding: 0px;
}
</style>

<script type="text/javascript">
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var map; 
var geocoder;
var line;
var baseaddress = '<?php echo addslashes($baseaddress); ?>';
var baselat = <?php echo VikRentItems::getDeliveryBaseLatitude(); ?>;
var baselng = <?php echo VikRentItems::getDeliveryBaseLongitude(); ?>;
var maxdistance = <?php echo empty($maxdistance) ? '0' : floatval($maxdistance); ?>;
var maxcost = <?php echo empty($maxcost) ? '0' : floatval($maxcost); ?>;
var calcunit = '<?php echo $calcunit; ?>';
var currency = '<?php echo $currencysymb; ?>';

function initialize() {
	directionsDisplay = new google.maps.DirectionsRenderer();
	var mybase = new google.maps.LatLng(baselat, baselng);
	var mapOptions = {
		zoom: 6,
		center: mybase,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
	directionsDisplay.setMap(map);
	
	geocoder = new google.maps.Geocoder();
	var location1;
	geocoder.geocode( { 'address': baseaddress}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			location1 = results[0].geometry.location;
			var marker = new google.maps.Marker({
				map: map,
				position: location1,
				title: baseaddress
			});
			var tooltip = '<div class="vrimaptooltipdiv">' + baseaddress + '</div>';
			var infowindow = new google.maps.InfoWindow({
				content: tooltip
			});
			google.maps.event.addListener(marker, 'click', function() {
				infowindow.open(map, marker);
			});
		} else {
			jQuery("#vrideliverymapesit").addClass("vrimaperr");
			jQuery("#vrideliverymapesit").text("<?php echo addslashes(JText::translate('VRIDELMAPERR1')); ?>"+status);
		}
	});
}

function vriRenderResult(totdist, totdur) {
	var durmin = 0;
	var durhours = parseInt(totdur / 3600);
	if (durhours != 0) {
		totdur = totdur - (durhours * 3600);
	}
	durmin = Math.round(totdur / 60);
	var unitdist;
	var deliverycost = 0;
	var distanceaccepted = 1;
	var kmdist = parseFloat(totdist / 1000);
	var milesdist = parseFloat(totdist * 0.000621371192);
	if (calcunit == 'km') {
		unitdist = kmdist;
	} else {
		unitdist = milesdist;
	}
<?php
if ($rounddistance == true) {
	?>
	unitdist = Math.round(unitdist);
	<?php
}
?>
	if (maxdistance > 0) {
		if (unitdist > maxdistance) {
			distanceaccepted = 0;
		}
	}
	if (!(unitdist > 0)) {
		unitdist = 1;
	}
	if (distanceaccepted == 1) {
		deliverycost = parseFloat(unitdist * <?php echo $costperunit; ?>);
	<?php
	if ($roundcost == true) {
		?>
		deliverycost = Math.round(deliverycost);
		<?php
	}
	?>
		if (maxcost > 0 && deliverycost > maxcost) {
			deliverycost = maxcost;
		}
		if (!(deliverycost > 0)) {
			deliverycost = <?php echo $costperunit; ?>;
		}
		var deliveryobj;
		var deliveryaddr = jQuery("#deliveryTo").val();
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { option: "com_vikrentitems", task: "ensuredelivery", address: deliveryaddr, distance: totdist, elemid: "<?php echo $item['id']; ?>", itemquant: "<?php echo $pitemquant; ?>", tmpl: "component" }
		}).done(function(resp){
			deliveryobj = JSON.parse(resp);
			deliverycost = deliverycost.toFixed(2);
			var esitstr = "<p class='vrimapdelivdistp'><?php echo addslashes(JText::translate('VRIDELMAPDISTANCE')); ?> "+unitdist+" "+calcunit+"</p>";
			esitstr += "<p class='vrimapdelivcostp'><?php echo addslashes(JText::translate('VRIDELMAPCOST')); ?> "+currency+" "+deliverycost+"</p>";
			jQuery("#vrideliverymapesit").removeClass("vrimaperr");
			jQuery("#vrideliverymapesit").addClass("vrimapok");
			jQuery("#vrideliverymapesit").html(esitstr);
			jQuery("#vrideliverymapcontinue").fadeIn();
			vriPopulateParent(deliveryobj.vrideliveryaddress, deliveryobj.vrideliverydistance, deliveryobj.vrideliverysessid, deliveryobj.vrideliverycost);
		}).fail(function(){
			jQuery("#vrideliverymapesit").removeClass("vrimapok");
			jQuery("#vrideliverymapesit").addClass("vrimaperr");
			jQuery("#vrideliverymapesit").text('<?php echo addslashes(JText::translate('VRIDELMAPERR5')); ?>');
			jQuery("#vrideliverymapcontinue").hide();
			vriPopulateParent(deliveryaddr, '', '', '');
		});
	} else {
		var disterrstr = '<?php echo addslashes(JText::translate('VRIDELMAPERR4')); ?>';
		disterrstr = disterrstr.replace("%destd", unitdist);
		disterrstr = disterrstr.replace("%u", calcunit);
		disterrstr = disterrstr.replace("%maxd", maxdistance);
		disterrstr = disterrstr.replace("%u", calcunit);
		jQuery("#vrideliverymapesit").removeClass("vrimapok");
		jQuery("#vrideliverymapesit").addClass("vrimaperr");
		jQuery("#vrideliverymapesit").text(disterrstr);
		jQuery("#vrideliverymapcontinue").hide();
		vriPopulateParent(document.getElementById("deliveryTo").value, '', '', '');
	}
	jQuery("#vrideliverymaploading").fadeOut();
}

function calcRoute() {
	jQuery("#vrideliverymaploading").fadeIn();
	var end = document.getElementById("deliveryTo").value;
	var waypts = [];
	var request = {
		origin: baseaddress,
		destination: end,
		waypoints: waypts,
		optimizeWaypoints: true,
		//unitSystem: google.maps.UnitSystem.METRIC,
		travelMode: google.maps.TravelMode.DRIVING
	};
	directionsService.route(request, function(response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(response);
			var route = response.routes[0];
			var totalDistance = 0;
			var totalDuration = parseInt(0);
			for (var i = 0; i < route.legs.length; i++) {
				totalDistance += route.legs[i].distance.value;
				totalDuration = parseInt(totalDuration) + parseInt(route.legs[i].duration.value);
			}
			geocoder = new google.maps.Geocoder();
			var location1;
			var location2;
			geocoder.geocode( { 'address': baseaddress}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					location1 = results[0].geometry.location;
					var marker = new google.maps.Marker({
						map: map,
						position: location1
					});
					geocoder.geocode( { 'address': end}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							location2 = results[0].geometry.location;
							vriRenderResult(totalDistance, totalDuration);
						} else {
							jQuery("#vrideliverymaploading").fadeOut();
							jQuery("#vrideliverymapesit").addClass("vrimaperr");
							jQuery("#vrideliverymapesit").text('<?php echo addslashes(JText::translate('VRIDELMAPERR2')); ?>(' + status + ')');
						}
					});
				}
			});
		} else {
			jQuery("#vrideliverymapesit").addClass("vrimaperr");
			if (status == google.maps.GeocoderStatus.NOT_FOUND || status == google.maps.GeocoderStatus.ZERO_RESULTS) {
				jQuery("#vrideliverymapesit").text('<?php echo addslashes(JText::translate('VRIDELMAPERR3')); ?>(' + status + ')');
			} else {
				jQuery("#vrideliverymapesit").text('<?php echo addslashes(JText::translate('VRIDELMAPERR2')); ?>(' + status + ')');
			}
			jQuery("#vrideliverymaploading").fadeOut();
		}
	});
}
	
google.maps.event.addDomListener(window, 'load', initialize);
	
jQuery('document').ready(function(){
	jQuery('#vricalculate').click(function(){
		calcRoute();
	});
});

function vriPopulateParent(addr, dist, sessid, cost) {
	window.parent.document.getElementById("deliveryaddressinp").value = addr;
	window.parent.document.getElementById("deliverydistanceinp").value = dist;
	window.parent.document.getElementById("deliverysessionval").value = sessid;
	if (addr.toString().length > 0 && sessid.toString().length > 0 && cost.toString().length > 0) {
		window.parent.document.getElementById("vrideliveryaddress").innerHTML = addr;
		window.parent.document.getElementById("vrideliverydistance").innerHTML = dist+" "+calcunit;
		window.parent.document.getElementById("vrideliverycost").innerHTML = cost.toFixed(2)+" "+currency;
		window.parent.document.getElementById("vrideliverycont").style.display = 'block';
	} else {
		window.parent.document.getElementById("vrideliveryaddress").innerHTML = '';
		window.parent.document.getElementById("vrideliverydistance").innerHTML = '';
		window.parent.document.getElementById("vrideliverycost").innerHTML = '';
		window.parent.document.getElementById("vrideliverycont").style.display = 'none';
	}
}

function vriCloseDeliveryMap() {
	parent.jQuery.fancybox.close();
}

</script>

<div id="vripanel">
<?php
if (is_array($item)) {
	?>
	<span class="vrimapitemtitle"><?php echo $item['name']; ?></span>
	<?php
}
?>
	<div class="vrideliveryinsaddr">
		<label for="deliveryTo"><?php echo JText::translate('VRIDELIVERYADDRESSENTER'); ?></label>
		<input type="text" name="deliveryTo" id="deliveryTo" value="<?php echo $pdelto; ?>" size="30"/>
		<span id="vricalculate"><?php echo JText::translate('VRIDELIVERYVALIDATEADDRESS'); ?></span>
	</div>
<?php
if (!empty($mapnotes)) {
	?>
	<div class="vrimapnotes">
		<?php echo $mapnotes; ?>
	</div>
	<?php
}
?>
	<div id="vrideliverymapesit"></div>
	<div id="vrideliverymaploading">
		<img src="<?php echo VRI_SITE_URI;?>resources/images/map_loader.gif"/>
	</div>
	<div id="vrideliverymapcontinue">
		<button type="button" class="btn booknow" onclick="vriCloseDeliveryMap();"><i class="fas fa-check-circle"></i> <?php echo JText::translate('VRIDELIVERYCONTINUEBTN'); ?></button>
	</div>
</div>

<div id="map-canvas"></div>

<script type="text/javascript">
jQuery('#deliveryTo').on('keydown', function(e) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if (code == 13) {
       calcRoute();
    }
});
<?php
if (!empty($pdelto)) {
	?>
jQuery('document').ready(function(){
	jQuery('#deliveryTo').focus();
});
	<?php
}
?>
</script>