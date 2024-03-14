<?php
/*
 * Plugin : wp-geonames
 * Template : Location taxonomy
 * Last Change : wp-geonames 1.4
 * Custom This File ? : wp-content/themes/name-of-my-theme/templates/wp-geonames_location_taxonomy.php
 * Call a custom template from a plugin : add_action named "wpGeonames_location_taxonomy_tpl" that return the path of the template.
 * Hook the PHP Ajax functions : remove_action('wp_ajax_geoDataRegion', 'wpGeonames_ajax_geoDataRegion'); add_action('wp_ajax_geoDataRegion', 'name of my function');
*/
?>

	<style>
	.city<?php echo $shortcode['id3']; ?> span{color:#555;font-weight:400;width:auto;}
	.city<?php echo $shortcode['id3']; ?> span:hover{color:#000;font-weight:700;cursor:pointer;}
	</style>
	<div class="blocGeoLocate" id="bloc<?php echo $shortcode['id1']; ?>">
		<label><?php _e('Country','wpGeonames') ?></label>
		<select id="<?php echo $shortcode['id1']; ?>" name="<?php echo $shortcode['id1']; ?>" <?php echo $geoData['onChangeCountry']; ?> >
			<option value=""> - </option>
			<?php echo $geoData['selectCountry']; ?>
		
		</select>
	</div>
	<div class="blocGeoLocate" id="bloc<?php echo $shortcode['id2']; ?>">
		<label><?php _e('Region','wpGeonames') ?></label>
		<select id="<?php echo $shortcode['id2']; ?>" name="<?php echo $shortcode['id2']; ?>" style="display:none;" <?php echo $geoData['onChangeRegion']; ?> >
			<option value=""> - </option>
		</select>
	</div>
	<div class="blocGeoLocate" id="bloc<?php echo $shortcode['id3']; ?>">
		<label><?php _e('City','wpGeonames') ?></label>
		<input type="text" id="<?php echo $shortcode['id3']; ?>" name="<?php echo $shortcode['id3']; ?>" style="display:none;" />
		<div class="list<?php echo $shortcode['id3']; ?>" id="list<?php echo $shortcode['id3']; ?>"></div>
	</div>
	<div id="geomap" style="display:none;height:300px;max-width:400px"></div>
	<input type="hidden" id="<?php echo $shortcode['out']; ?>" name="<?php echo $shortcode['out']; ?>" value="" />
	<script>
	var wpgeomap,wpgeoajx;
	function geoDataRegion(){
		jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>',{'action':'geoDataRegion','country':jQuery('#<?php echo $shortcode['id1']; ?> option:selected').val()},function(data){
			var r=jQuery.parseJSON(data.substring(0,data.length-1));
			jQuery('#<?php echo $shortcode['id2']; ?>').show().empty().append('<option value="0"> - </option>');
			jQuery.each(r,function(k,v){
				jQuery('#<?php echo $shortcode['id2']; ?>').append('<option value="'+v.regionid+'">'+v.name+'</option>');
			});
		});
	}
	function geoDataCity(){
		jQuery('#<?php echo $shortcode['id3']; ?>').show().keyup(function(){
			wpgeoajx=null;
			wpgeoajx=jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>',{'action':'geoDataCity','country':jQuery('#<?php echo $shortcode['id1']; ?> option:selected').val(),'region':jQuery('#<?php echo $shortcode['id2']; ?> option:selected').val(),'city':jQuery('#<?php echo $shortcode['id3']; ?>').val(),'nbcity':<?php echo $shortcode['nbcity']; ?>},function(data){
				jQuery('#list<?php echo $shortcode['id3']; ?>').show().empty();
				var r=jQuery.parseJSON(data.substring(0,data.length-1));
				jQuery.each(r,function(k,v){
					out=JSON.stringify(v);
					out=out.replace(/"/g,'').replace('{','').replace('}','');
					jQuery('#list<?php echo $shortcode['id3']; ?>').append('<div class="city<?php echo $shortcode['id3']; ?>"><span onClick="wpGeonameCityMap(\''+v.name+'\','+v.latitude+','+v.longitude+',\''+out+'\');">'+v.name+'</span></div>');
				});
			});
		});
	}
	function wpGeonameCityMap(ci,lat,lon,out){
		jQuery('#<?php echo $shortcode['id3']; ?>').val(ci);<?php if(empty($shortcode['map'])) echo 'return;'; ?>
		
		jQuery('#geomap').show();
		if(typeof(wpgeomap)!='undefined'){
			wpgeomap.off();
			wpgeomap.remove();
		}
		wpgeomap=new L.map('geomap').setView([lat,lon],9);
		var wpgeodata=new L.TileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{minZoom:5,maxZoom:14,attribution:'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});
		wpgeomap.addLayer(wpgeodata);
		var wpgeomark=L.marker([lat,lon]).addTo(wpgeomap);
		wpgeomark.bindPopup("<b>"+ci+"</b>").openPopup();
		jQuery('#<?php echo $shortcode['out']; ?>').val(out);
	}		
	</script>
