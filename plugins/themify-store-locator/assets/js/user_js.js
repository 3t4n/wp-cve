(function( $ ) {
'use strict';
const themify_sl_user_map = {
	init:function(container){
		this.single = false;
		this.markers = [];
		this.container = container;
		this.settings = this.map_settings();
		
		if(!this.single){
			this.get_markers();
		} else {
			this.load_map();
		}
	},
	get_markers:function(){
		const self=this,
			data={action:'themify_sl_ajax'};
		if(this.settings.category){
			data.category = this.settings.category;
		}
		$.ajax({
				type: "POST",
				url: themifyStoreLocator.ajaxurl,
				data,
				success: function(result){
					if(result[result.length-1]==0){
						result = result.substring(0, result.length - 1);
					}
					self.markers = JSON.parse(result);
					self.load_map();
				},
				error: function(){
					self.load_error();
				}
			});
	},
	map_settings: function(){
		const settings = JSON.parse(window.atob(this.container.data('settings'))),
				draggable = (settings.mobile_draggable.toLowerCase() == 'no' && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) ? false : true;
		if(settings.single_post_map){
			this.single = true;
			this.markers.push(JSON.parse(window.atob(this.container.data('single'))));
		}
		const result = {
			'map_load':{
				'center':{lat: 35.8799875, lng: 76.5151001},
				'zoom':13,
				'mapTypeId':'roadmap',
				'scrollwheel': (settings.scrollwheel.toLowerCase() == 'yes') ? true : false,
				'draggable': draggable,
				'disableDefaultUI':  (settings.map_controls.toLowerCase() == 'no') ? true : false
			},
			'map_width':settings.width,
			'map_height':settings.height
		};
		if(settings.category){
			result.category = settings.category;
		}
		return result;
	},
	load_error:function(){
		this.container.html('Faild To get Store Locations');
	},
	load_map:function(){
		this.container.append('<div class="sl_google_map_container"></div>');
		themify_SL_map_marker.init({
			'map_container': $('.sl_google_map_container').get(0),
			'wait_msg': $('span.wait_sl'),
			'settings': this.settings,
			'markers': this.markers,
			'suggestion': false
		});
	}
	};
	function callme_sl_test(){
		let container = $('.themify_SL_map_container');
		if(container.length>=1){
			container.show();
			themify_sl_user_map.init(container);
		} else {
			container = $('.tsl_store_map');
			if(container.length>=1){
				container.show();
				themify_sl_user_map.init(container);
			}
		}
	}
	
	const container = $( '.themify_SL_scripts' );
	if ( container.length >= 1 ) {
		$.getScript( themifyStoreLocator.marker_js ).done( function() {
			if ( typeof google !== 'object' || typeof google.maps !== 'object' ) {
				$.getScript( themifyStoreLocator.map_js ).done( function() {
					callme_sl_test();
				} );
			} else {
				callme_sl_test();
			}
		} );
	}
})(jQuery);