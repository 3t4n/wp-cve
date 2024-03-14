(function($) {
	"use strict";
    var WidgetJsonBasedLottiesAnimationHandler = function($scope, $) {
		var container = $scope.find('.jbafes-lotties-animation-wrapper'),            
            data_id = container.data("id"),
            container_id = document.getElementById(data_id),
            data_path = container.data("path"),
            data_loop = container.data("loop"),
            data_anim_renderer = container.data("anim_renderer"),
            data_width = container.data("width"),
            data_height = container.data("height"),
            data_playspeed = container.data("playspeed"),
            data_play_action = container.data("play_action"),
            data_la_scrollbased = container.data("la_scrollbased"),
            data_la_section_duration = container.data("la_section_duration"),
            data_la_section_offset = container.data("la_section_offset"),
            data_la_start_time = container.data("la_start_time"),
            data_la_end_time = container.data("la_end_time");
			
		var kap_lotties_animation = bodymovin.loadAnimation({
		  container: container_id,
		  animType: data_anim_renderer,
		  loop: data_loop,
		  autoplay: (data_play_action === 'autoplay') ? true : false,
		  path: data_path
		});
		
		if (data_playspeed <= 1) {
			kap_lotties_animation.setSpeed(data_playspeed);
		}
		
		var start_time = 1;
		if(data_la_start_time !='' && data_la_start_time !=undefined){
			start_time = data_la_start_time;
		}
		var end_time = kap_lotties_animation.totalFrames;
			
		
		if(data_play_action){
			if(data_play_action === 'default'){				
					kap_lotties_animation.goToAndStop(0);							
			}
			
			if(data_play_action === 'reverse_second_click'){				
				var directionMenu = 1;
				  container_id.addEventListener('click', (e) => {
				  kap_lotties_animation.setDirection(directionMenu);
				  kap_lotties_animation.play();
				  directionMenu = -directionMenu;
				});						
			}
			
			if(data_play_action === 'autoplay'){				
					kap_lotties_animation.goToAndPlay(0);				
			}
			
			if(data_play_action === 'hover'){				
				kap_lotties_animation.goToAndStop(start_time, true),
				jQuery(container_id).mouseenter(function() {
					var start_time = 1;
					if(data_la_start_time !='' && data_la_start_time !=undefined){
						start_time = data_la_start_time;
					}
					if(data_la_end_time !='' && data_la_end_time !=undefined){
						end_time = data_la_end_time;
					}else{
						end_time = kap_lotties_animation.totalFrames;
					}
					kap_lotties_animation.playSegments([start_time, end_time], !0)
				})
			}
			
			if(data_play_action === 'click'){					
				kap_lotties_animation.goToAndStop(start_time, true),
				jQuery(container_id).click(function() {
					var start_time = 1;
					if(data_la_start_time !='' && data_la_start_time !=undefined){
						start_time = data_la_start_time;
					}
					if(data_la_end_time !='' && data_la_end_time !=undefined){
						end_time = data_la_end_time;
					}else{
						end_time = kap_lotties_animation.totalFrames;
					}
					kap_lotties_animation.playSegments([start_time, end_time], !0)
				})
			}

			if(data_play_action === 'mouseoverout'){
				kap_lotties_animation.goToAndStop(start_time, true),
				jQuery(container_id).mouseenter(function() {
					var start_time = 1;
					if(data_la_start_time !='' && data_la_start_time !=undefined){
						start_time = data_la_start_time;
					}
					if(data_la_end_time !='' && data_la_end_time !=undefined){
						end_time = data_la_end_time;
					}else{
						end_time = kap_lotties_animation.totalFrames;
					}
					kap_lotties_animation.playSegments([start_time, end_time], !0)
				}),
				jQuery(container_id).mouseleave(function() {
					var new_load = kap_lotties_animation.currentRawFrame;
					kap_lotties_animation.setDirection(-1), kap_lotties_animation.goToAndPlay(new_load, !0)
				})
			}
			
			$(window).on("lotties_load_animation resize scroll", function() {
		
			if(data_play_action === 'parallax_effect'){
				
				var section_offset = container.offset().top;
					var section_duration = data_la_section_duration;
					var offset_top = data_la_section_offset;
					var all_duration = section_duration + section_offset - offset_top;
					
					var find_scroll_perc = 0;
					if( $(window).scrollTop() >= (section_offset - offset_top ) && $(window).scrollTop() <= all_duration && data_la_scrollbased == 'la_custom'){
						var scrollpercent = 100 * ($(window).scrollTop() - (section_offset - offset_top)) / (section_duration);
						var find_scroll_perc = Math.round(scrollpercent);
					}else if( $(window).scrollTop() >= (section_offset - offset_top ) && $(window).scrollTop() >= all_duration && data_la_scrollbased == 'la_custom'){
						var find_scroll_perc = Math.round(100);
					}else if(data_la_scrollbased == 'la_document'){
						var scrollpercent = 100 * $(window).scrollTop() / ($(document).height() - $(window).height());
						var find_scroll_perc = Math.round(scrollpercent);
					}
					
					var start_time = 0;
					var stop_time = kap_lotties_animation.totalFrames;
					if(data_la_start_time!='' && data_la_start_time!=undefined){
						start_time = data_la_start_time;
					}
					if(data_la_end_time!='' && data_la_end_time!=undefined){
						stop_time = data_la_end_time;
					}
					kap_lotties_animation.goToAndStop(start_time, true)
					var currframe = ((find_scroll_perc)/100 ) * (stop_time - start_time);
					if(currframe >= stop_time){
						kap_lotties_animation.goToAndStop(stop_time, true);
					}else{
						kap_lotties_animation.goToAndStop((currframe + start_time), true);
					}
			}
			}), jQuery(window).trigger("lotties_load_animation");
		}
		
	};
	 $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/jbafe_lottie_animation.default', WidgetJsonBasedLottiesAnimationHandler);
    });
})(jQuery);

/*isonscreen js*/!function(a){"use strict";a.fn.isOnScreen=function(b){var c=this.outerHeight(),d=this.outerWidth();if(!d||!c)return!1;var e=a(window),f={top:e.scrollTop(),left:e.scrollLeft()};f.right=f.left+e.width(),f.bottom=f.top+e.height();var g=this.offset();g.right=g.left+d,g.bottom=g.top+c;var h={top:f.bottom-g.top,left:f.right-g.left,bottom:g.bottom-f.top,right:g.right-f.left};return"function"==typeof b?b.call(this,h):h.top>0&&h.left>0&&h.right>0&&h.bottom>0}}(jQuery);