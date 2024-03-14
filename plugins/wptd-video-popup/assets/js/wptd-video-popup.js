
(function( $ ) {
	
	"use strict";
	
	/* Shortcode CSS Append */
	if( $(document).find('.wptd-inline-css').length ){
		var css_out = '';
		$(document).find( ".wptd-inline-css" ).each(function() {
			var shortcode = $( this );
			if( shortcode.attr("data-css") ){
				var shortcode_css = shortcode.attr("data-css");		
				css_out += ($).parseJSON( shortcode_css );
				//shortcode.removeAttr("data-css");
			}
		});
		if( css_out != '' ){
			$('head').append( '<style id="wptd-shortcode-styles">'+ css_out +'</style>' );
		}
	}
	
	// Normal Shortcode
	if( $(document).find('.wptd-popup-video, .wptd-popup-video').length ){
		
		var _t_class = '';
		$(document).find('.wptd-popup-video, .wptd-popup-video').on("click", function(){
			if( $(this).data("id") ){
				_t_class = $(this).data("id");
			}
		});
					
		$(document).find('.wptd-popup-video, .wptd-popup-video').magnificPopup({
			//disableOn: 700,
			type: 'iframe',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
			fixedContentPos: false,
			iframe: {
				markup: '<div class="mfp-iframe-scaler">' +
					'<div class="mfp-close"></div>' +
					'<iframe class="mfp-iframe" frameborder="0" allow="autoplay" allowfullscreen></iframe>' +
					'</div>',
				patterns: {
					youtube: {
						index: 'youtube.com/',
						id: 'v=',
						src: 'https://www.youtube.com/embed/%id%?autoplay=0'
					},
					vimeo: {
						index: 'vimeo.com/',
						id: '/',
						src: '//player.vimeo.com/video/%id%?autoplay=1&loop=1&autopause=0'
					},
				}
			},
			callbacks: {
				open: function() {
					$("body").addClass(_t_class);
				},
				close: function() {
					$("body").removeClass(_t_class);
				}
			}
		});
	}
	
	// Elementor Video popup handler
	var wptd_video_popup_handler = function( $scope, $ ) {
		if( $scope.find(".wptd-video-popup-trigger").length ){
			$scope.find( '.wptd-video-popup-trigger' ).each(function() {
				wptd_popup_fun( this, $scope.attr("data-id") );
			});		
		}
	};
	
	// Init event on elementor frontend
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/wptd_video_popup.default', wptd_video_popup_handler );	
	});
	
	// Call magnific popup
	function wptd_popup_fun( _ele, _scope_id ){
		
		/* Shortcode CSS Append */
		if( $(document).find('.wptd-video-popup-inline-css').length ){
			var css_out = ''; $("#wptd-elementor-shortcode-styles").remove();
			$(document).find( ".wptd-video-popup-inline-css" ).each(function() {
				var shortcode = $( this );
				if( shortcode.attr("data-css") ){
					var shortcode_css = shortcode.attr("data-css");		
					css_out += ($).parseJSON( shortcode_css );
					shortcode.remove();
				}
			});
			if( css_out != '' ){
				$('head').append( '<style id="wptd-elementor-shortcode-styles">'+ css_out +'</style>' );
			}
		}
		
		var _wptd_video_popup_class = '';
		$(_ele).on( "click", function(){
			_wptd_video_popup_class = 'wptd-video-popup-'+ _scope_id;
		});
		var _ele = $(_ele);
		_ele.magnificPopup({
			//disableOn: 700,
			type: 'iframe',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
			fixedContentPos: false,
			iframe: {
				markup: '<div class="mfp-iframe-scaler">' +
					'<div class="mfp-close"></div>' +
					'<iframe class="mfp-iframe" frameborder="0" allow="autoplay" allowfullscreen></iframe>' +
					'</div>',
				patterns: {
					youtube: {
						index: 'youtube.com/',
						id: 'v=',
						src: 'https://www.youtube.com/embed/%id%?autoplay=0'
					},
					vimeo: {
						index: 'vimeo.com/',
						id: '/',
						src: '//player.vimeo.com/video/%id%?autoplay=1&loop=1&autopause=0'
					},
				}
			},
			callbacks: {
				open: function() {
					$("body").addClass(_wptd_video_popup_class);
				},
				close: function() {
					$("body").removeClass(_wptd_video_popup_class);
				}
			}
		});
	}
			
})( jQuery );

