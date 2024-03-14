/*
gmap_v3_size.js, V 1.09, altm, 22.11.2013
Author: ATLSoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
Google Maps V3 init multimap support
released under GNU General Public License
*/
	function SizeControl(controlDiv, map) {
		// Setup custom reszize control
		var controlUI = document.createElement('div');
		controlUI.title = msg_00;
		var urlfull = 'background: url(' + pluri + 'img/full.png) no-repeat;';
		var urlmin = 'background: url(' + pluri + 'img/flat.png) no-repeat;';
		jQuery(controlUI).attr("class", "gmv3_mapbtn");
		jQuery(controlUI).attr("style",urlfull);
		controlDiv.appendChild(controlUI);

		// things to remember
		var center;		
		var isFullsize = false; 
		var trans = false;
		var divmap = "div#" + map.getDiv().id;
		var oldval = jQuery(divmap).attr("style");
		var oldbody = jQuery("body").attr("style");
		var oldw = jQuery(divmap).width(); 
		var oldh = jQuery(divmap).height(); 
		var p = jQuery(divmap).offset();
		var scrolpos;		

		// Setup click event listeners: simply resize
		google.maps.event.addDomListener(controlUI, 'click', function() {
			center = map.getCenter();
			xc = Math.round(jQuery(window).width()/2);
			yc = Math.round(jQuery(window).height()/2);
			if (isFullsize){
				jQuery("div#wpadminbar").attr("style", "visibility:visible;");
				jQuery(divmap).animate({
					top: Math.round(yc - oldh/2) + 'px',
					left: Math.round(xc - oldw/2) + 'px',
					width: oldw + 'px',  
					height: oldh + 'px'
					}, 
					1000, 
					function() {
						jQuery("body").attr("style", oldbody);
						jQuery("body").css("overflow", "auto");
						jQuery("html").css("overflow", "auto"); //ie7 fix
						jQuery(divmap).attr("style", oldval); 
						jQuery(divmap).css("overflow", "auto"); //ie + chrome fix
						jQuery(divmap).css("position", "relative"); //ie + chrome fix
						jQuery(scrollToEle).scrollTop(scrolpos);
						google.maps.event.trigger(map, 'resize'); 
					}
				);				
				isFullsize = false; 
				jQuery(controlUI).attr("style",urlfull);
			} else {
				p = jQuery(divmap).offset();
				scrolpos = jQuery(scrollToEle).scrollTop();
				jQuery("div#wpadminbar").attr("style", "visibility:hidden;");
				jQuery("body").css("overflow", "hidden");
				jQuery(scrollToEle).scrollTop(0);
				jQuery("html").css("overflow", "hidden"); //ie7 fix
				jQuery(divmap).attr("style", "background-color:#DDDDDD; position:fixed; top:"+yc+"px; left:"+xc+"px; margin:0px; padding:0px; overflow:hidden; z-index:"+fszIndex+";");
				jQuery(divmap).animate({
					top:'0px',
					left:'0px',
					width:'100%',  
					height: '100%' 
					}, 
					1000, 
					function() {
						google.maps.event.trigger(map, 'resize');
					}
				);				
				isFullsize = true; 
				jQuery(controlUI).attr("style",urlmin);
			}
			trans = true;
			// Setup resize listener
			google.maps.event.addDomListener(map, 'resize', function() {
				if (trans && map.bbox){
					if(map.bbox.isEmpty())
						map.panTo(center);
					else
						map.fitBounds(map.bbox);
				} 
				trans = false;
			});
		});
	}	

	function clearOvlMap(map) {
		map.polies.splice(0,map.polies.length);
		map.markers.splice(0,map.markers.length);
		var height = jQuery('.gm_add_'+map.getDiv().id).height();
		jQuery('.gm_add_'+map.getDiv().id).remove();
		if(map.g_mToggle){
			height += jQuery('#makerL_'+map.getDiv().id).height();
			jQuery('#makerL_'+map.getDiv().id).remove();
		}
		jQuery('#holder_'+map.getDiv().id).height(jQuery('#holder_'+map.getDiv().id).height() - height);
	}

	// this hack is if a map starts hidden
	function wakeMap(map) {
		if(map){
			var center = map.getCenter()
			google.maps.event.trigger(map, 'resize');
			if(map.bbox && map.bbox.isEmpty())
					map.setCenter(center);
			else
				map.fitBounds(map.bbox);
			if(map.elevation && map.uri){
				var isGpx = (map.polies.length || map.markers.length);
				clearOvlMap(map);
				if(isGpx)
					showGPX(map, map.uri);
				else
					getKmlPath(map, map.uri);
			}
		}
	}	
	
	/*
	 * jQuery.appear
	 * http://code.google.com/p/jquery-appear/
	 *
	 * Copyright (c) 2009 Michael Hixson
	 * Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
	*/
(function(a){a.fn.appear=function(d,b){var e=a.extend({data:void 0,one:!0},b);return this.each(function(){var c=a(this);c.appeared=!1;if(d){var g=a(window),f=function(){if(c.is(":visible")){var a=g.scrollLeft(),d=g.scrollTop(),b=c.offset(),f=b.left,b=b.top;b+c.height()>=d&&b<=d+g.height()&&f+c.width()>=a&&f<=a+g.width()?c.appeared||c.trigger("appear",e.data):c.appeared=!1}else c.appeared=!1},b=function(){c.appeared=!0;if(e.one){g.unbind("scroll",f);var b=a.inArray(f,a.fn.appear.checks);0<=b&&a.fn.appear.checks.splice(b, 1)}d.apply(this,arguments)};if(e.one)c.one("appear",e.data,b);else c.bind("appear",e.data,b);g.scroll(f);a.fn.appear.checks.push(f);f()}else c.trigger("appear",e.data)})};a.extend(a.fn.appear,{checks:[],timeout:null,checkAll:function(){var d=a.fn.appear.checks.length;if(0<d)for(;d--;)a.fn.appear.checks[d]()},run:function(){a.fn.appear.timeout&&clearTimeout(a.fn.appear.timeout);a.fn.appear.timeout=setTimeout(a.fn.appear.checkAll,20)}});a.each("append prepend after before attr removeAttr addClass removeClass toggleClass remove css show hide".split(" "), function(d,b){var e=a.fn[b];e&&(a.fn[b]=function(){var b=e.apply(this,arguments);a.fn.appear.run();return b})})})(jQuery);
