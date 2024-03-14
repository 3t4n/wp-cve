/*
 * Copyright (c) 2021 frenify
 * Author: Frenify
 * This file is made for CURRENT Theme
*/

(function($){
	"use strict";
	
	var FrenifyCore = {
			
		init: function(){
			this.magnific();
			this.svg();
			this.bg_images();
			this.tooltip();
		},
		
		tooltip: function(){
			var img = $('.frenify__welcome_header .img_box');
			var tooltip = $('.fn__tooltip');
			if(img.offset().left + img.outerWidth() + tooltip.outerWidth(true,true) > $(window).width()){
				tooltip.addClass('reversed');
			}else{
				tooltip.removeClass('reversed');
			}
		},

		magnific: function(){
			$('.popup-youtube, .popup-vimeo').each(function() { // the containers for all your galleries
				$(this).magnificPopup({
					disableOn: 700,
					type: 'iframe',
					mainClass: 'mfp-fade',
					removalDelay: 160,
					preloader: false,
					fixedContentPos: false
				});
			});
		},
		
		svg: function(){
			$('img.fn__svg').each(function(){
				var e 				= $(this);
				var imgclass		= e.attr('class');
				var URL				= e.attr('src');
				$.get(URL, function(data) {
					var svg 		= $(data).find('svg');
					if(typeof imgclass !== 'undefined') {
						svg = svg.attr('class', imgclass + ' ready-svg');
					}
					svg = svg.removeAttr('xmlns:a');
					e.replaceWith(svg);
				}, 'xml');
			});
		},
		bg_images: function(){
			var data			= $('*[data-bg-img]');
			data.each(function(){
				var element			= $(this);
				var url				= element.data('bg-img');
				element.css({backgroundImage: 'url('+url+')'});
			});
		},
	};
	
	$(document).ready(function(){
		// initialization
		FrenifyCore.init();

	});
	
	$(window).on('resize', function(){
		// initialization
		FrenifyCore.tooltip();

	});
	
})(jQuery);
