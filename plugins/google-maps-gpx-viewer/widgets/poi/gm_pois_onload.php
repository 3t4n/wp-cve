<?php
/*
gm_pois_onload.php, V 1.03, altm, 22.11.2013
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
*/
?>	
	<script type="text/javascript">  

	function OnLoadGpxPoiDb(map){
		map.g_seCookie = false; // no cookie		
		map.g_showCnt++;
		jQuery.ajax({
			type: "GET",
			url:  "<?php echo admin_url( 'admin-ajax.php' );?>?action=gmap_poi_action&post_id=<?php echo $post_ID; ?>&get_pois=true",
			success: function(msg){
				jQuery('.loader').remove();
				var obj = jQuery.parseJSON(msg);
				var out = "0 POIs";
				var bbox = new google.maps.LatLngBounds();
				if (obj.length){
					out = obj.length + " POIs";
					var last = null;
					jQuery(obj).each( function(){
						last = insertPoiMap(map, this)
						map.markers.push(last);
						bbox.extend(last.position);
					});
					last.selected = true;
					q = jQuery("#item_type");
					jQuery('#item_type option[value="'+last.busi+'"]').attr("selected","selected");
				} else {
					jQuery('#gmap_poi_act_map').val(0);
					jQuery('#item_type option[value="sauerland/Info"]').attr("selected","selected");
				}
				jQuery("#item_type").change();
				
				jQuery('#result').html(out).fadeIn('slow');
				fitViewport(map, bbox);
				updateMarkerUI(map, null);
				
			},
			complete:function (jqXHR, textStatus){
				/* enable for error check in loading gpx*/ 
				// if(textStatus != "success")
					// alert('Error: ' + jqXHR.responseText + ' + ' + textStatus);				
			}
		});    
		google.maps.event.addListener(map, 'click', function(e) { 
			jQuery('#gmap_poi_action_map').attr('value', map.getDiv().id);
			jQuery('#result').html("");
			updateMarkerUI(map, null);
			if(map.gmap_poi_db && jQuery('#gpx_poi-widget').is("div")) {
				jQuery('#result').html('<img src="<?php echo admin_url( 'images/loading.gif' ); ?>" class="loader" width="25" height="25" />').fadeIn();
				var geocoder = new google.maps.Geocoder();
				var latlng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
				geocoder.geocode({'latLng': latlng}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[0]) {
							var places_postal = results[0].address_components;
							var addr = new Object();
							if(places_postal){
								for (var i = 0; i < places_postal.length; i++ ) {
									addr[places_postal[i].types[0]] = places_postal[i].long_name;
								}					
							}
							// if(addr.country){
								// jQuery('#item_name').attr('value', addr.country);
							if(addr.locality)
								jQuery('#item_name').attr('value', addr.locality);
							// postal address	
							var mail_address = "";
							if(addr.route)
								mail_address += addr.route;
							if(addr.street_number)
								mail_address += ' ' + addr.street_number;
							jQuery('#street').attr('value' ,mail_address);							
							mail_address = "";
							if(addr.postal_code)
								mail_address += addr.postal_code + ' ';
							if(addr.locality)
								mail_address += addr.locality;
							jQuery('#city').attr('value', mail_address);
											
							
							jQuery('#gpx_poi-widget').addClass('red_border'); 

						} else {
							// alert('No results found');
						}
					} else {
						//alert('Geocoder failed due to: ' + status);
					}
					jQuery('.loader').remove();
				});
	
				jQuery('#poi_click').attr('value', 1); // ?
				
				jQuery('#lat').attr('value',latlng.lat());
				jQuery('#lng').attr('value',latlng.lng());
				click_marker = new google.maps.Marker({
					map: map,
					position: latlng
				});
			}			
		});  
	}
	var click_marker = null;

	//	insert from OnLoad Insert and Update
	function insertPoiMap(map, ele){
		var val = Math.abs(jQuery('#gmap_poi_act_map').val())+1;
		jQuery('#gmap_poi_act_map').val(val);
		if(map.akt_marker)
			map.akt_marker.infowindow.close();
		var item_type = '';
		jQuery('#item_type > option').each( function(){
			if(this.value == ele.item_type){
				item_type = this.value; // 
			}
		}); 
		var description = '';
		if(ele.item_url == "http://" || ele.item_url == "")
			description = '<strong>' + ele.item_name + '</strong>';
		else
			description = '<a href="' + ele.item_url + '" target="_blank"><strong>' + ele.item_name + '</strong> </a>';
		if(ele.city != "" && ele.street != ""){
			description += '<br><?php _e( 'Address', GPX_GM_PLUGIN ); ?> ' + ele.city + ', ' + ele.street;
			description += '<br>';
		}
		if(ele.contact != "")
			description += '<br>' + ele.contact;
		if(ele.description != "")
			description += '<br>' + ele.description;
		var pos = new google.maps.LatLng(ele.lat, ele.lng);
		
		var marker = new google.maps.Marker({
			icon: new google.maps.MarkerImage('<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN;?>/img/gmapIcons/'+ele.item_type+'.png',
				new google.maps.Size(32, 32),
				new google.maps.Point(0, 0),
				new google.maps.Point(12,25),
				new google.maps.Size(24, 24)
			),
			map: map,
			id: map.markers.length,
			position: pos,
			draggable: false,
			startpos: pos,
			db_id: ele.id,
			title: ele.item_name,
			home:ele.item_url,
			busi:ele.item_type,
			city:ele.city,
			street:ele.street,
			contact:ele.contact,
			descr:ele.description/* ,
			content: description */
		});
		if(map.gmap_poi_db && jQuery('#gpx_poi-widget').is("div")) { // edit marker
			marker.draggable = true;
			marker.event = google.maps.event.addListener(marker, 'dragend', function() {
				updateMarkerUI(map, this);
			});  
			marker.event = google.maps.event.addListener(marker, 'click', function() {
				updateMarkerUI(map, this);
				
			}); 
		} else {
			marker.event = google.maps.event.addListener(marker, 'click', function() {
				jQuery('#result').html("");
				updateMarkerUI(map, this);
				map.akt_marker = marker;
				marker.infowindow.open(map ,marker);
			}); 
			marker.infowindow = new google.maps.InfoWindow({
				content: description
			});
			marker.infowindow.event = google.maps.event.addListener(marker.infowindow, 'closeclick', function() {
				updateMarkerUI(map, null);
			});		
		}
		return marker;
	}

	// update marker info 
	function updateMarkerUI(map, marker){
		jQuery('#gmap_poi_action_map').attr('value', map.getDiv().id);
		if(map.akt_marker)
			map.akt_marker.infowindow.close();
		if(click_marker){
			click_marker.setMap(null);
			click_marker = null;
		}
		jQuery('#poi_click').attr('value', 0); 
		// fill form with marker values on click
		if(marker){
			jQuery('#gpx_poi-widget').addClass('red_border');
		
			jQuery('#delbutton').css('visibility','visible');
			jQuery('#lat').attr('value',marker.startpos.lat());
			jQuery('#lng').attr('value',marker.startpos.lng());
			jQuery('#item_name').attr('value',marker.title);
			jQuery('#item_url').attr('value',marker.home);
			jQuery('#city').attr('value',marker.city);
			jQuery('#street').attr('value',marker.street);
			jQuery('#contact').attr('value',marker.contact);
			jQuery('#description').attr('value',marker.descr);
			var m = marker.busi;
			jQuery('#item_type > option').each( function(){
				if(this.value == m){
					this.selected = true; // establishment select
					jQuery("#item_type").change();
				}
			}); 			
			jQuery('#poi_db_id').attr('value',marker.db_id);
		} else { // empty poi
			jQuery('#gpx_poi-widget').removeClass('red_border');
		
			jQuery('#delbutton').css('visibility','hidden');
			jQuery('#poi_db_id').attr('value','');
			jQuery('#lat').attr('value','');
			jQuery('#lng').attr('value','');
			jQuery('#item_name').attr('value','<?php _e( 'Place name', GPX_GM_PLUGIN ); ?>');
			jQuery('#item_url').attr('value','http://');
			jQuery('#city').attr('value','<?php _e( 'Zip & City', GPX_GM_PLUGIN ); ?>');
			jQuery('#street').attr('value','<?php _e( 'Street & No.', GPX_GM_PLUGIN ); ?>');
			jQuery('#contact').attr('value','<?php _e( 'Contact person, phone', GPX_GM_PLUGIN ); ?>');
			jQuery('#description').attr('value','<?php _e( 'Description', GPX_GM_PLUGIN ); ?>');
		}
	}
	
</script>
