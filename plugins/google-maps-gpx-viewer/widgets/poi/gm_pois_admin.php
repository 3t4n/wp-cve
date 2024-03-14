<?php
/*
gm_pois_admin.php, V 1.04, altm, 22.11.2013
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
*/

	$gmapklength = strlen(get_option('gmap_v3_gpx_proKey'));

?>	
<script type="text/javascript">  

	jQuery("#item_type").change(function() { 
	var t = "<?php echo get_bloginfo('wpurl')."/wp-content/plugins/".GPX_GM_PLUGIN; ?>/img/gmapIcons/" + this.value + ".png";
		jQuery("#item_type_img").attr('src',t);
	});
	// after delete or update
	function removeSelected(map){
		out = "<?php _e( 'success!', GPX_GM_PLUGIN ); ?>";
		if(map.akt_marker)
			map.akt_marker.infowindow.close();
		var del_id = jQuery('#poi_db_id').attr('value'); 
		for(var i = 0; i < map.markers.length ; i++){
			if(map.markers[i].db_id ==  del_id){
				map.markers[i].setMap(null);
				map.markers.splice(i,1);
				break;
			}
		}
		for(var i = 0; i < map.markers.length ; i++){
			map.markers[i].id = i;
		}
	}

	// delete button handler, ajax 
	function deletePoi(){
		var map = this[jQuery('#gmap_poi_action_map').attr('value')]
		if(map){
			if(map.akt_marker)
				map.akt_marker.infowindow.close();
			var input_data = jQuery('#wp_gpx_wdg_form').serialize();
			jQuery.ajax({
				type: "GET",
					data: input_data,
				url:  "<?php echo admin_url( 'admin-ajax.php' );?>?del_pois=true", 
				success: function(msg){
					jQuery('.loader').remove();
					var out = '';
					if(msg == '1' || msg == '' ) {
						out = "<?php _e( 'success!', GPX_GM_PLUGIN ); ?>";
						removeSelected(map);
						updateMarkerUI(map, null);
					}
					else {
						out = "<?php _e( 'failed!', GPX_GM_PLUGIN ); ?>";
					}
					jQuery('#gpx_poi-widget').removeClass('red_border');
					jQuery('#result').html(out);
				},
				complete:function (jqXHR, textStatus){
					/* enable for error check in loading gpx*/ 	
					// if(textStatus != "success")
						// alert('Error: ' + jqXHR.responseText + ' + ' + textStatus);			
				}    
			});
		}
		return false;
	}
	
	// position
	function PoiPositionChanged(map){
		var ret = false;
		var db_id = jQuery('#poi_db_id').attr('value'); 
		if(!db_id)
			return ret;
		for(var i = 0; i < map.markers.length ; i++){
			if(map.markers[i].db_id ==  db_id){
				if(!map.markers[i].position.equals(map.markers[i].startpos)){
					// position changed
					jQuery('#new_lat').attr('value',map.markers[i].position.lat());
					jQuery('#new_lng').attr('value',map.markers[i].position.lng());
				} else {
					jQuery('#new_lat').attr('value','');
					jQuery('#new_lng').attr('value','');				
				}
				ret = true;
			}
		}
		return ret;
	}

	// after insert check 
	function checkInput(ele){
		var map = this[jQuery('#gmap_poi_action_map').attr('value')];
		if(map){
			if(map.akt_marker)
				map.akt_marker.infowindow.close();
				
			if(PoiPositionChanged(map)){
				insertPoiDB(map);
				return false;
			}
			var isClick = jQuery('#poi_click').attr('value');
			if(isClick){
				insertPoiDB(map);
				return false;
			}

			jQuery('#result').html('<img src="<?php echo admin_url( 'images/loading.gif' ); ?>" class="loader" width="25" height="25" />').fadeIn();
			geocodePOI();
		}
		return false;
	}

	//insert or update
	function insertPoiDB(map){ 
		var input_data = jQuery('#wp_gpx_wdg_form').serialize();
		
		if (jQuery('#gmap_poi_act_map').val() > 0 && <?php echo $gmapklength ?> != 32){
			var h = jQuery('#gpx_poi-widget').height();
			jQuery('#gpx_poi-widget').children().replaceWith("<br><br><h3><?php _e( 'Learn how to add more than one POI to the map.', GPX_GM_PLUGIN ); ?>" + "</h3><br><br>" + '<a href="http://www.atlsoft.de/poi-database/"><?php _e( 'Get the full POI Database functionality!', GPX_GM_PLUGIN ); ?></a>' );
			jQuery('#gpx_poi-widget').css('border','2px solid red');
			jQuery('#gpx_poi-widget').css('padding','50px 10px');
			jQuery('#gpx_poi-widget').height(h);
			return;
		}
			
		jQuery.ajax({
			type: "POST",
			url:  "<?php echo admin_url( 'admin-ajax.php' );?>",
			data: input_data,
			success: function(msg){
				jQuery('.loader').remove();
				var obj = jQuery.parseJSON(msg);
				if (obj != 0){
					var out = '<?php _e( 'inserted', GPX_GM_PLUGIN ); ?>';
					if(obj.status == 'updated'){
						removeSelected(map);
						out = '<?php _e( 'updated', GPX_GM_PLUGIN ); ?>';
					}
					jQuery('#result').html(out);
					var m = insertPoiMap(map, obj[0])
					map.markers.push(m);
				} else
					alert('<?php _e( 'POI service failed!', GPX_GM_PLUGIN ); ?>' + '<?php _e( 'Please contact the Plugin Autor.', GPX_GM_PLUGIN ); ?>');
				updateMarkerUI(map, null);
			},
			complete:function (jqXHR, textStatus){
				/* enable this for errorcheck if fails */ 
				// if(textStatus != "success")
					// alert('Error: ' + jqXHR.responseText + ' + ' + textStatus);				
			}    
		});
		return false;
	}

	// search POI by geocode
	function geocodePOI() {
		geocoder = new google.maps.Geocoder();
		var address = jQuery("#street").attr("value") + ", " + jQuery("#city").attr("value");
		geocoder.geocode( { "address": address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				jQuery('#lat').attr('value',results[0].geometry.location.lat());
				jQuery('#lng').attr('value',results[0].geometry.location.lng());
				jQuery('#new_lat').attr('value','');
				jQuery('#new_lng').attr('value','');
				insertPoiDB();
			} else {
				jQuery('.loader').remove();
				alert("<?php _e( 'Nothing found!', GPX_GM_PLUGIN ); ?>");
			}	
		
		});			
		return false;
	}		

	</script>