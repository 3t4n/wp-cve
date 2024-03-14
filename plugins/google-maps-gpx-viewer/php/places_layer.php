<?php
/*
places_layer.php, V 1.04, altm, 20.09.2013
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
*/
	if(strlen(get_option('gmap_v3_gpx_proKey')) == 32){
	$places_types = Array("accounting", "airport", "amusement_park", "aquarium", "art_gallery", "atm", "bakery", "bank", "bar", "beauty_salon", "bicycle_store", "book_store", "bowling_alley", "bus_station", "cafe", "campground", "car_dealer", "car_rental", "car_repair", "car_wash", "casino", "cemetery", "church", "city_hall", "clothing_store", "convenience_store", "courthouse", "dentist", "department_store", "doctor", "electrician", "electronics_store", "embassy", "establishment", "finance", "fire_station", "florist", "food", "funeral_home", "furniture_store", "gas_station", "general_contractor", "grocery_or_supermarket", "gym", "hair_care", "hardware_store", "health", "hindu_temple", "home_goods_store", "hospital", "insurance_agency", "jewelry_store", "laundry", "lawyer", "library", "liquor_store", "local_government_office", "locksmith", "lodging", "meal_delivery", "meal_takeaway", "mosque", "movie_rental", "movie_theater", "moving_company", "museum", "night_club", "painter", "park", "parking", "pet_store", "pharmacy", "physiotherapist", "place_of_worship", "plumber", "police", "post_office", "real_estate_agency", "restaurant", "roofing_contractor", "rv_park","school",  "shoe_store", "shopping_mall", "spa", "stadium", "storage", "store", "subway_station", "synagogue", "taxi_stand", "train_station", "travel_agency", "university", "veterinary_care", "zoo");
	
	$places_types_name = Array( __('accounting', GPX_GM_PLUGIN),  __('airport', GPX_GM_PLUGIN),  __('amusement park', GPX_GM_PLUGIN),  __('aquarium', GPX_GM_PLUGIN),  __('art gallery', GPX_GM_PLUGIN), __('atm', GPX_GM_PLUGIN),  __('bakery', GPX_GM_PLUGIN),  __('bank', GPX_GM_PLUGIN),  __('bar', GPX_GM_PLUGIN),  __('beauty salon', GPX_GM_PLUGIN),  __('bicycle store', GPX_GM_PLUGIN),  __('book store', GPX_GM_PLUGIN),  __('bowling alley', GPX_GM_PLUGIN),  __('bus station', GPX_GM_PLUGIN),  __('cafe', GPX_GM_PLUGIN),  __('campground', GPX_GM_PLUGIN),  __('car dealer', GPX_GM_PLUGIN),  __('car rental', GPX_GM_PLUGIN),  __('car repair', GPX_GM_PLUGIN),  __('car wash', GPX_GM_PLUGIN),  __('casino', GPX_GM_PLUGIN),  __('cemetery', GPX_GM_PLUGIN),  __('church', GPX_GM_PLUGIN),  __('city hall', GPX_GM_PLUGIN),  __('clothing store', GPX_GM_PLUGIN),  __('convenience store', GPX_GM_PLUGIN),  __('courthouse', GPX_GM_PLUGIN),  __('dentist', GPX_GM_PLUGIN),  __('department store', GPX_GM_PLUGIN),  __('doctor', GPX_GM_PLUGIN),  __('electrician', GPX_GM_PLUGIN),  __('electronics store', GPX_GM_PLUGIN),  __('embassy', GPX_GM_PLUGIN),  __('establishment', GPX_GM_PLUGIN),  __('finance', GPX_GM_PLUGIN),  __('fire station', GPX_GM_PLUGIN),  __('florist', GPX_GM_PLUGIN),  __('food', GPX_GM_PLUGIN),  __('funeral home', GPX_GM_PLUGIN),  __('furniture store', GPX_GM_PLUGIN),  __('gas station', GPX_GM_PLUGIN),  __('general contractor', GPX_GM_PLUGIN),  __('grocery or supermarket', GPX_GM_PLUGIN),  __('gym', GPX_GM_PLUGIN),  __('hair care', GPX_GM_PLUGIN),  __('hardware store', GPX_GM_PLUGIN),  __('health', GPX_GM_PLUGIN),  __('hindu temple', GPX_GM_PLUGIN),  __('home goods store', GPX_GM_PLUGIN),  __('hospital', GPX_GM_PLUGIN),  __('insurance agency', GPX_GM_PLUGIN),  __('jewelry store', GPX_GM_PLUGIN),  __('laundry', GPX_GM_PLUGIN),  __('lawyer', GPX_GM_PLUGIN),  __('library', GPX_GM_PLUGIN),  __('liquor store', GPX_GM_PLUGIN),  __('local government office', GPX_GM_PLUGIN),  __('locksmith', GPX_GM_PLUGIN),  __('lodging', GPX_GM_PLUGIN),  __('meal delivery', GPX_GM_PLUGIN),  __('meal takeaway', GPX_GM_PLUGIN),  __('mosque', GPX_GM_PLUGIN),  __('movie rental', GPX_GM_PLUGIN),  __('movie theater', GPX_GM_PLUGIN),  __('moving company', GPX_GM_PLUGIN),  __('museum', GPX_GM_PLUGIN),  __('night club', GPX_GM_PLUGIN),  __('painter', GPX_GM_PLUGIN),  __('park', GPX_GM_PLUGIN),  __('parking', GPX_GM_PLUGIN),  __('pet store', GPX_GM_PLUGIN),  __('pharmacy', GPX_GM_PLUGIN),  __('physiotherapist', GPX_GM_PLUGIN),  __('place of worship', GPX_GM_PLUGIN),  __('plumber', GPX_GM_PLUGIN),  __('police', GPX_GM_PLUGIN),  __('post office', GPX_GM_PLUGIN),  __('real estate agency', GPX_GM_PLUGIN),  __('restaurant', GPX_GM_PLUGIN),  __('roofing contractor', GPX_GM_PLUGIN),  __('rv park', GPX_GM_PLUGIN), __('school', GPX_GM_PLUGIN),   __('shoe store', GPX_GM_PLUGIN),  __('shopping mall', GPX_GM_PLUGIN),  __('spa', GPX_GM_PLUGIN),  __('stadium', GPX_GM_PLUGIN),  __('storage', GPX_GM_PLUGIN),  __('store', GPX_GM_PLUGIN),  __('subway station', GPX_GM_PLUGIN),  __('synagogue', GPX_GM_PLUGIN),  __('taxi stand', GPX_GM_PLUGIN),  __('train station', GPX_GM_PLUGIN),  __('travel agency', GPX_GM_PLUGIN),  __('university', GPX_GM_PLUGIN),  __('veterinary care', GPX_GM_PLUGIN), __('zoo', GPX_GM_PLUGIN));		
	
	$placesSort = Array();	
	$len = count($places_types);
	for ($i = 0; $i < $len; $i++) {
		$placesSort[$places_types_name[$i]] = '<option value="'. $places_types[$i] . '">' . $places_types_name[$i]. '</option>';
	}	
	ksort($placesSort);
	
	$branche = isset($_GET["branche"]) ? $_GET["branche"] : "";	//
	$address = isset($_GET["address"]) ? $_GET["address"] : "";	//
	$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";	//
	if($address || $keyword || $branche){
		$retval .= 'google.maps.event.addListenerOnce(' . $map_id . ', "tilesloaded", function() {
			';
		if($address != ''){
			$attr['address'] = $address;
		}
		if($keyword != ''){
			$retval .= '
			jQuery("#placeSearch_'. $map_id.'").attr("value", "'. $keyword.'");
			// jQuery("#btnSearch_'. $map_id.'").click();
			var bla = new Object();
			bla.value = "'. $keyword.'";
			searchPlaces('. $map_id.', bla);
			';
		}
		if($branche != ''){
			$retval .= '
			var bla = [];
			bla[0] = "'. $branche.'";
			getPlaces('. $map_id.', bla);
			';
		}
		
		$retval .= '
		});
	';
	}
	?>		
	<div id="places_<?php echo $map_id;?>" style="display:none;">
		<div id="places_get_<?php echo $map_id;?>" class="gm_places_div"><strong><?php _e('Places search', GPX_GM_PLUGIN) ?> </strong><small class="gm_counter"> </small> 
			<input id="placeSearch_<?php echo $map_id;?>" type="text" onkeypress="if (event.keyCode == 13)searchPlaces(<?php echo $map_id;?>, this);" />
			<input onclick="changePlaces(<?php echo $map_id;?>);" type="button" alt="<?php _e('switch to branches', GPX_GM_PLUGIN) ?>" title="<?php _e('switch to branches', GPX_GM_PLUGIN) ?>" value="►" class="gm_places_change" style="float:left;" />		
			<input id="btnSearch_<?php echo $map_id;?>" onclick="searchPlaces(<?php echo $map_id;?>, document.getElementById('placeSearch_<?php echo $map_id;?>'));" type="button" value="<?php _e('search', GPX_GM_PLUGIN) ?>" /> 
			<input onclick="clearPlaces(<?php echo $map_id;?>);" type="button" value="<?php _e('remove', GPX_GM_PLUGIN) ?>" />
			<input onclick="listPlaces(<?php echo $map_id;?>);" type="button" alt="<?php _e('list all', GPX_GM_PLUGIN) ?>" title="<?php _e('list all', GPX_GM_PLUGIN) ?>" value="▼" class="gm_places_change"/>		
		</div>
		<div id="places_kat_<?php echo $map_id;?>" style="display:none;" class="gm_places_div"><strong><?php _e('Places', GPX_GM_PLUGIN) ?> </strong><small  class="gm_counter"> </small> 
			<select class="gm_places_select" id="placeID_<?php echo $map_id;?>" size="1" onchange="">
			<?php 
			$i = 0;
			foreach ($placesSort as $entry => $p_type) {
				echo $p_type;
			}
			?></select>
			<input onclick="changePlaces(<?php echo $map_id;?>);" type="button" alt="<?php _e('switch to search', GPX_GM_PLUGIN) ?>" title="<?php _e('switch to search', GPX_GM_PLUGIN) ?>" value="►" class="gm_places_change" style="float:left;" />		
			<input onclick="placeChange(<?php echo $map_id;?>, document.getElementById('placeID_<?php echo $map_id;?>'));" type="button" value="<?php _e('search', GPX_GM_PLUGIN) ?>" /> 
			<input onclick="clearPlaces(<?php echo $map_id;?>);" type="button" value="<?php _e('remove', GPX_GM_PLUGIN) ?>" />
			<input onclick="listPlaces(<?php echo $map_id;?>);" type="button" alt="<?php _e('list all', GPX_GM_PLUGIN) ?>" title="<?php _e('list all', GPX_GM_PLUGIN) ?>" value="▼" class="gm_places_change"/>		
		</div>
	</div>
<?php
	if(file_exists ( PLUGIN_ROOT . '/php/places_layer_addon.php')){
		require_once(PLUGIN_ROOT . '/php/places_layer_addon.php');
	}
?>
<script type='text/javascript'>

	var gm_p_more = "<?php _e('more', GPX_GM_PLUGIN) ?>" + " ▼";
	var gm_p_totop = "<?php _e('top', GPX_GM_PLUGIN) ?>" + " ▲";

	function places_LoadCallback() {
		jQuery('#places_<?php echo $map_id;?>').insertAfter('#holder_<?php echo $map_id;?>');
		jQuery('#places_<?php echo $map_id;?>').css('display','block');
		<?php
		if($attr['p_search'] != ''){
		?>
			jQuery('#placeSearch_<?php echo $map_id;?>').attr('value', '<?php echo $attr['p_search'];?>');
		<?php
		}
		?>
	}
		
    function clearPlaces(map) {
		jQuery('#places_more_'+map.getDiv().id).slideUp(500);
		for(var i = 0; i < map.g_places.length ; i++){
			map.g_places[i].setMap(null);
		}
		map.g_places.splice(0,map.g_places.length);
		map.g_places_cnt = null;
		jQuery('.gm_counter').text('');
	}
      
    function placeChange(map, placeType) {
		var selected = [];
		selected.push(placeType[placeType.selectedIndex].value);
		getPlaces(map, selected);
	}
	
	function prepResult(map){
		map.bbox = map.getBounds();
		var mapId = map.getDiv().id;
		var divmap = "#" + mapId;
		var div_id = "places_" + mapId + "";
		
		// blindmap 
		if(jQuery.find('#'+div_id).length <= 0)
			jQuery('<div id='+div_id+' style="display:none;"></div>').insertAfter(divmap);
		
		div_id = "places_more_" + mapId + "";	
		if(jQuery.find('#'+div_id).length <= 0)
			jQuery('<div id='+div_id+' class="gm_places_div" style="display:none;"></div>').insertAfter('#places_' + mapId);
		else
			jQuery('#places_more_' + map.getDiv().id).slideUp(500);
		jQuery('#p_more_btn_' + map.getDiv().id).remove();
		
		var div_wait = "places_wait_" + map.getDiv().id;	
		jQuery(divmap).append('<div id="'+div_wait+'" class="gm_wait"></div>');
		jQuery('#'+div_wait).css('background','url(' + pluri + 'img/wait.gif) center no-repeat');
					
        return map_p = new google.maps.Map(document.getElementById(div_id));	
	}
	
	function placesFinish(map){
		var div_id = "#places_wait_" + map.getDiv().id;	
		jQuery(div_id).remove();
		// additional functionality in Thems template?
		if(typeof addAutoList == 'function')
			addAutoList(map);
	}

    function searchPlaces(map, search) {
		var map_p = prepResult(map);
        var request = {
			bounds: map.getBounds(),
			rankBy: google.maps.places.RankBy.PROMINENCE,
			// A term to be matched against all available fields, 
			// including but not limited to name, type, and address, 
			// as well as customer reviews and other third-party content.
			keyword: search.value
        };
        var service = new google.maps.places.PlacesService(map_p);
        service.nearbySearch(request, function( results, status, next) {
			if (status == google.maps.places.PlacesServiceStatus.OK) {
				for (var i = 0; i < results.length; i++) {
					createMarker(map, results[i]);
				}
				if(next.hasNextPage)
					next.nextPage();
				else
					placesFinish(map);
			}
			else
				placesFinish(map);
		});
	}
	
    function getPlaces(map, placeT) {
		var map_p = prepResult(map);
        var request = {
			bounds: map.getBounds(),
			rankBy: google.maps.places.RankBy.PROMINENCE,
			types: placeT
        };
        var service = new google.maps.places.PlacesService(map_p);
        service.nearbySearch(request, function( results, status, next) {
			if (status == google.maps.places.PlacesServiceStatus.OK) {
				for (var i = 0; i < results.length; i++) {
					createMarker(map, results[i]);
				}
				if(next.hasNextPage)
					next.nextPage();
				else
					placesFinish(map);
			}
			else
				placesFinish(map);
		});
   }

    function createMarker(map, place) {
		if(!map.g_places)
			map.g_places = [];
		for (var i = 0; i < map.g_places.length; i++ ) {
			// if(map.g_places[i].position.equals(place.geometry.location))
			if(map.g_places[i].id == place.id)
				return;
		}
		var marker = new google.maps.Marker({
			map: map,
			icon: new google.maps.MarkerImage(place.icon,
				new google.maps.Size(32, 32),
				new google.maps.Point(0,0),
				new google.maps.Point(0, 0),
				new google.maps.Size(20, 20)
			),
 			id: place.id, // zum test für Dubletten oder ob der Eintrag sich geändert hat
			title: place.name,
			content: place.html_attributions,
			reference: place.reference,
			position: place.geometry.location
        });
		map.g_places.push(marker);
        google.maps.event.addListener(marker, 'click', function() {
			getPlacesDetails(map, place.reference);
        });
		jQuery('.gm_counter').text(map.g_places.length);
    }
 
    function getPlacesDetails(map, ref) {
		jQuery('#places_more_'+map.getDiv().id).slideUp(500);
		// infowindow.close();
		var request = {
			reference: ref
		};
		var service = new google.maps.places.PlacesService(map);
		service.getDetails(request, function(place, status) {
			if (status == google.maps.places.PlacesServiceStatus.OK) {			
				getPlacesContent(map, place);								
				jQuery('#places_more_'+map.getDiv().id).slideDown(500);
			}
		});
	} 
	
    function changePlaces(map) {
		jQuery('#places_more_'+map.getDiv().id).slideUp(500);
		if(jQuery('#places_get_'+map.getDiv().id).css('display') == 'none'){
			jQuery('#places_kat_'+map.getDiv().id).fadeToggle('slow','linear', function () {
				jQuery('#places_get_'+map.getDiv().id).css('display', 'block');
			});
		}
		else {
			jQuery('#places_get_'+map.getDiv().id).fadeToggle('slow','linear', function () {
				jQuery('#places_kat_'+map.getDiv().id).css('display', 'block');//
			});
		}
	}

    function getPlacerDetailRequest(map, i) {
		var request = {
			reference: map.g_places[i].reference
		};
		var service = new google.maps.places.PlacesService(map);
		service.getDetails(request, function(place, status) {
			if (status == google.maps.places.PlacesServiceStatus.OK) {			
				map.g_places[i].placesDetails = place;
				getPlacesContent(map, place);		
			}
			// else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
				// setTimeout(function() {
					// jQuery('#places_more_'+map.getDiv().id).append('<div class="places_more_item">'+(i+1)+ '. ' + status + '</div>'); 
					// getPlacerDetailRequest(map, i);
				// }, 2000);
			// }
			// else 
				// jQuery('#places_more_'+map.getDiv().id).append('<div class="places_more_item">'+(i+1)+ '. ' + status + '</div>'); 
			map.g_places_cnt--;		
		});
	}	

    function listPlaces(map) {
		if(!map.g_places)
			return;

		if(!map.g_places_cnt) {
			map.g_places_cnt = map.g_places.length;
			jQuery('#places_more_'+map.getDiv().id).slideUp(500);
			jQuery('#places_more_'+map.getDiv().id).html("");
			jQuery('#places_more_'+map.getDiv().id).append('<input id="p_more_btn_'+map.getDiv().id+'" type="button" class="gm_places_change" style="width:auto !important;position:absolute;bottom:0px;right:0px;" value="'+gm_p_more+'" onclick="listPlaces('+map.getDiv().id+');" />');
		}

		if(map.g_places_cnt <= 10){
			jQuery('#p_more_btn_' + map.getDiv().id).remove();
			var fkt = "jQuery('#places_more_"+map.getDiv().id+"').slideUp(500); var m=jQuery('#"+map.getDiv().id+"').position();jQuery('"+scrollToEle+"').scrollTop(m.top-55);";
			jQuery('#places_more_'+map.getDiv().id).append('<input id="p_more_btn_'+map.getDiv().id+'" value="'+gm_p_totop+'" onclick="'+fkt+'" type="button" class="gm_places_change" style="width:auto !important;position:absolute;bottom:0px;right:0px;" />');
		}
			
		var count; 
		if(map.g_places_cnt < 10){
			count = map.g_places_cnt;
		}
		else
			count = 10;
			
		var dist = map.g_places.length - map.g_places_cnt;
		for(var i = dist;i < (dist + count);i++){
			getPlacerDetailRequest(map, i);
		}
		jQuery('#places_more_'+map.getDiv().id).slideDown(500);
		// additional functionality in Thems template?
		if(typeof addDownloadBtn == 'function')
			addDownloadBtn(map);
	}
	
	function getPlaceDetails(map, place){
		var title;
		if(place.website){
			title = '<a href="' + place.website + '" target="_blank">' + place.name+ '</a>';
		}
		else
			title = place.name;
		var hoover = "";
		if(place.types){
			for (var i = 0; i < place.types.length; i++) {
				hoover += jQuery('#placeID_'+map.getDiv().id +' option[value="' + place.types[i]+'"]').text()
				if(i < place.types.length-1)
					hoover +=  ", ";
			}
		}
		title = '<strong alt="'+hoover+'" title="'+hoover+'">'+title+'</strong> ';
		var txt = ""; var cnt = '+';
		for (var i = 0; i < place.html_attributions.length; i++){
			place.html_attributions[i] = place.html_attributions[i].replace(/<a\b[^>]*>/i,"");
			place.html_attributions[i] = place.html_attributions[i].replace(/<\/a>/i, "");

			if(i < place.html_attributions.length-1)
				txt += place.html_attributions + ', ';
			else
				txt += place.html_attributions;
			cnt += '*';
		}
		if (place.url){// try google
			title += '<small title="'+txt+'" alt="'+txt+'"> <a href="' + place.url + '" target="_blank">g'+cnt+'</a></small>';
		} else {
			title += '<small title="'+txt+'" alt="'+txt+'">n'+cnt+'</a></small>';					
		}
		
		var places_postal = place.address_components;
		var addr = new Object();
		for (var i = 0; i < places_postal.length; i++ ) {
			addr[places_postal[i].types[0]] = places_postal[i].long_name;
		}					

		var out = '';
		if(title)
			out += title+'<br />';
		// postal address	
		if(addr.route)
			out += addr.route + ' ';
		if(addr.street_number)
			out += addr.street_number + ', ';
		if(addr.country)
			out += addr.country + '-';
		if(addr.postal_code)
			out += addr.postal_code + ' ';
		if(addr.locality)
			out += addr.locality;
		out += '<br />';
		
		if(place.formatted_phone_number)
			out += 'Tel.: ' +place.formatted_phone_number + ' - ';
		if (place.international_phone_number)
			out += 'Int.: ' +place.international_phone_number + '<br />';		
		return out;
	}
	
	function getPlacesContent(map, place){
		// find index
		var idx = 0;
		for( var i = 0; i < map.g_places.length; i++){
			idx = i;
			if (map.g_places[i].id == place.id)
				break;
		}
		
		var out = getPlaceDetails(map, place);
		
		if(place.website && typeof getAddon == 'function')// additional functionality in Thems template?
			out += getAddon(map,idx);
	
		jQuery('#places_more_'+map.getDiv().id).append('<div class="places_more_item">'+(idx+1)+ '. ' + out + '</div>');	
	}

</script>
<?php } else {
?>
<script type='text/javascript'>
	function places_LoadCallback() {}
</script>
<?php 
			}
?>