function krpano_ready(krpano) {
	jQuery(document).ready(function(){
		var krpanoObject = jQuery('#krpanoObject');
		var target_container = jQuery('#target_container');
		var container = '<div id="wppano_overlay"></div>';
		if(krpano.get('device.html5')){
			if( target_container.val() == '' ) 
				krpanoObject.append(container); 
			else
				jQuery(target_container.val()).append(container);
		} else {
			if( target_container.val()=='' ) 
				jQuery('#pano').append(container);
			else
				jQuery(target_container.val()).append(container);
		}
		
	});
}

(function($) {		//прервать все ajax запросы
	let xhrPool = [];
	$(document).ajaxSend(function(e, jqXHR, options) {
		xhrPool.push(jqXHR);
	});
	$(document).ajaxComplete(function(e, jqXHR, options) {
		xhrPool = $.grep(xhrPool, function(x){return x!=jqXHR});
	});
	window.abortAllMyAjaxRequests = function() {
		$.each(xhrPool, function(idx, jqXHR) {
			jqXHR.abort();
		});
	};
})(jQuery);

function wppano_GetAllHotspots(vtour_name, pano, scene) {
	window.abortAllMyAjaxRequests();
	jQuery.ajax({	
		type : "POST",
		dataType : "json",
		url : ajax.url,
		data : {action: "wppano_GetAllHotspots", vtourpath: vtour_name, pano_name: pano, scene_name: scene},
		beforeSend:function(){ 
			krpano.call("trace('ajax: Get all Hotspots...');");
		},
		success: function(response){
			if(response.type == "success") {
				var krpano_data;
				if(response.hotspots)
					for (i = 0; i < response.hotspots.length; i++) {
						krpano_data = response.hotspots[i]['data'];
						krpano.set('wp_pano.post_title', response.hotspots[i]['title']);
						krpano.set('wp_pano.post_thumbnail', response.hotspots[i]['thumbnail']);
						krpano.call("wppano_AddNewHotspot("+response.hotspots[i]['ID']+", "+krpano_data['ath']+", "+krpano_data['atv']+", '"+response.hotspots[i]['hs_style']+"')");
					}
			}
			if(response.type == "nohotspots") krpano.call("trace('Server: No hotspots in the pano');");
			var user_script_hotspotloaded = jQuery('#user_script_hotspotloaded');
			if( user_script_hotspotloaded.val() == 'yes' ) wp_pano_call_hotspotloaded(response);
		},
		error: function (xhr) {
			krpano.call("trace('Ajax error: "+xhr.responseText+"');");
		}
	});	
}

jQuery.fn.imagesLoaded = function () {
    var imgs = this.find('img[src!=""]');
    if (!imgs.length) return jQuery.Deferred().resolve().promise();
    var dfds = [];  
    imgs.each(function(){
        var dfd = jQuery.Deferred();
        dfds.push(dfd);
        var img = new Image();
        img.onload = function(){dfd.resolve();}
        img.onerror = function(){dfd.resolve();}
        img.src = this.src;
    });
    return jQuery.when.apply(jQuery, dfds);
}

function wppano_open_post(post_id) {
	var wppano_overlay = jQuery('#wppano_overlay');
	//wppano_overlay.html('<div class="wppano_wrapper" style="visibility: hidden;"></div><div class="wp-pano-ajax-progress"></div>');
	wppano_overlay.html('<div class="wppano_wrapper"></div><div class="wp-pano-ajax-progress"></div>');
	var wppano_wrapper = jQuery('.wppano_wrapper');
	wppano_wrapper.click(function (event) {
		var event = event || window.event;
		var target = event.target || event.srcElement;
		if (this == target) wppano_close_post();		
	});
	var user_script_before = jQuery('#user_script_before');
	if( user_script_before.val() == 'yes' ) wp_pano_call_before();
	wppano_overlay.fadeIn();
	jQuery.ajax({
		type : "POST",
		dataType : "html",
		url : ajax.url,
		data : {action: "wppano_GetPostContent", id:post_id},
		beforeSend:function(){
		},
		success: function(response){
			// wppano_wrapper.html(response).imagesLoaded().then(function(){
					// jQuery('.wp-pano-ajax-progress').remove();
					// wppano_wrapper.css({display: 'none', visibility: 'visible'});
					// wppano_wrapper.fadeIn();
				// });
			//wppano_wrapper.css({visibility: 'visible'});
			jQuery('.wp-pano-ajax-progress').remove();
			wppano_wrapper.html(response);
			//wppano_wrapper.fadeIn();
		},
		error: function (xhr) {
			krpano.call("trace('Ajax error: "+xhr.responseText+"');");
		}
	});	
}

function wppano_close_post() {
	var user_script_after = jQuery('#user_script_after');	
	if( user_script_after.val() == 'yes' ) wp_pano_call_after();
	jQuery('#wppano_overlay').fadeOut(400, function(){
		jQuery('#wppano_overlay').empty();
		
		});
}