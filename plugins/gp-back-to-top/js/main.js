/**
 * Scripts for GP Back To Top Plugin
 *
 * @author Giang Peter
 */ 

(function ($) {
	$(document).ready(function() {
		var demo = $('.gp-back-to-top'),
			width = $('.gpbttb-form').find('#width'),
			height = $('.gpbttb-form').find('#height'),
			font = $('.gpbttb-form').find('#font'),
			bg_color = $('.gpbttb-form').find('#bg_color'),
			color = $('.gpbttb-form').find('#color'),
			bottom = $('.gpbttb-form').find('#bottom'),
			right = $('.gpbttb-form').find('#right');

		// updateStyle();

		function updateStyle() {
			width.val(demo.outerWidth());
			height.val(demo.outerHeight());
			font.val( demo.css('font-size').replace("px", '') );
			bg_color.val( rgb2hex(demo.css('background-color')) );
			color.val( rgb2hex(demo.css('color')) );
		}

		width.on('change', function() {
			var value = $(this).val();
			demo.css({'width': parseInt(value)+'px'});
		});

		height.on('change', function() {
			var value = $(this).val();
			demo.css({'height': parseInt(value)+'px'});
		});

		font.on('change', function() {
			var value = $(this).val();
			demo.css({'font-size': parseInt(value)+'px'});
		});

		bg_color.on('change', function() {
			var value = $(this).val();
			demo.css({'background-color': value});
		});

		color.on('change', function() {
			var value = $(this).val();
			demo.css({'color': value});
		});

		bottom.on('change', function() {
			var value = $(this).val();
			demo.css({'bottom': value+'px'});
		});

		right.on('change', function() {
			var value = $(this).val();
			demo.css({'right': value+'px'});
		});
	});
})(jQuery);