var themify_SL_map_marker;
(function() {
'use strict';
themify_SL_map_marker = {
	init: function(cfg){
		this.map_box	= cfg['map_container'];
		this.wait_msg	= cfg['wait_msg']?cfg['wait_msg']:false;
		this.settings	= cfg['settings'];
		this.markers = cfg['markers'];
		this.marked	 = new Array();
		this.previous_window = false;
		this.post_marker = false;
		this.suggestion = cfg['suggestion'] != undefined ? cfg['suggestion'] : false;
		this.suggestion_box = cfg['suggestion_box'] != undefined ? cfg['suggestion_box'] : '';
		if (typeof google !== 'object' && typeof google.maps !== 'object'){
			this.map_box.innerHTML = "Google Map API is not loaded";
			return;
		}
		this.load_map();
		if(this.markers != undefined && this.markers.length>0){
			this.marker_cluster();
		}
	},
	load_map:function(){
		if(this.settings.map_width){
			this.map_box.style.width = this.settings.map_width;
		}
		if(this.settings.map_height){
			this.map_box.style.height = this.settings.map_height;
		}
		this.map = new google.maps.Map(this.map_box, this.settings.map_load);
		this.bounds = new google.maps.LatLngBounds();
		if(this.wait_msg){
			this.wait_msg.remove();
		}
	},
	change_map_center: function(latlng,bound){
		this.map.setCenter(latlng);
		if(bound){
			this.map.fitBounds(this.bounds);
			this.change_map_zoom(this.map.getZoom());
		}
	},
	change_map_zoom: function(zoom){
		this.map.setZoom(zoom>15?15:zoom);
	},
	add_marker : function( marker_info ) {
		if ( typeof marker_info.position === 'string' ) {
			return;
		}

		let	info_window = false,
		marker = new google.maps.Marker({
				position: marker_info.position,
				animation: google.maps.Animation.DROP,
				title: ''
		});
		
		if(marker_info.content != false && marker_info.content != undefined && marker_info.content != ''){
			info_window = this.create_info_window(marker_info.content);
			marker.addListener('click', function() {
				( themify_SL_map_marker.previous_window ) ? themify_SL_map_marker.previous_window.close() : '';
				info_window.open(themify_SL_map_marker.map, this);
				themify_SL_map_marker.previous_window = info_window;
			 });
		} else {
			marker.addListener('click', function() {
				( themify_SL_map_marker.previous_window ) ? themify_SL_map_marker.previous_window.close() : '';
			 });
		}
		marker.setMap(this.map);
		this.bounds.extend(marker.getPosition());
		const temp = {'marker':marker, 'infobox':info_window};
		this.marked.push(temp);
		return temp;
	},
	marker_cluster:function(){
		for(let i=0,len=this.markers.length;i<len;++i){
			this.marked.push(this.add_marker(this.markers[i]));
		}
		setTimeout(function(){
			themify_SL_map_marker.change_map_center(themify_SL_map_marker.bounds.getCenter(),true);
		},1000);
	},
	admin_post_marker:function(address){
		this.latlng_from_address(address,function(latlng){
				if(typeof latlng === 'object'){
					if(themify_SL_map_marker.post_marker){
						themify_SL_map_marker.post_marker.marker.setMap(null);
					}
					themify_SL_map_marker.post_marker = themify_SL_map_marker.add_marker({'position':latlng,'title':'Store Location'});
					themify_SL_map_marker.change_map_center(latlng,false);
				}
			});
	},
	create_info_window:function(content_str){
		return new google.maps.InfoWindow({
			content: content_str,
			maxWidth: 400
		});
	},
	latlng_from_address:function(address,callback){
		let geocoder = new google.maps.Geocoder(),
			LatLng	 = false;
		geocoder.geocode( { 'address': address}, function(results, status) {
			LatLng = (status == google.maps.GeocoderStatus.OK) ? results[0].geometry.location : status;
			if(typeof callback === 'function'){
				callback(LatLng);
			} else if(status == google.maps.GeocoderStatus.ZERO_RESULTS){
				alert('Please enter a valid address');
			} else if(status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT){
				alert('Your Google Map API key reached the query limit for "Google Map Geocoder"');
			}
		});
	},
	latlng_from_marker:function(marker){
		return {
				'lat':marker.position.lat(),
				'lng':marker.position.lng()
			};
	}
};
})();