/**
 * widget.js - Contains all Widget functionality required by Photonic
 */
var photonicWidgetData;
jQuery(document).ready(function($) {
	(function(win) {
		"use strict";

		$(document).on('click', '.photonic-wizard', function(e) {
			e.preventDefault();

			var clicked = $(this);
			photonicWidgetData = $(clicked.closest('.photonic-widget').find('.photonic-shortcode')[0]);
			tb_show('Click to create gallery', clicked.attr('href'));
		});

		$(document).on('change', '.photonic-shortcode', function() {
			var $sc_field = $(this);
			var $icon = $($(this).closest('.photonic-widget').find('.photonic-wizard')[0]);
			if (top.wp !== undefined && top.wp.shortcode !== undefined) {
				var shortcode = top.wp.shortcode.next(Photonic_Widget_JS.shortcode, $sc_field.attr('value'));
				var attrs = shortcode.shortcode.attrs.named;
				if ($icon.hasClass('photonic')) {
					var $para = $icon.siblings('p');
					$para.html(Photonic_Widget_JS.edit_message);
				}
				if (attrs.type !== undefined) {
					$icon.attr('class', 'photonic-wizard ' + attrs.type);
				}
				else {
					$icon.attr('class', 'photonic-wizard wp');
				}
			}

			var $sc_display = $($(this).closest('.photonic-widget').find('.photonic-shortcode-display')[0]);
			$sc_display.html("<h4>" + Photonic_Widget_JS.current_shortcode + "</h4>\n" +
				"<code>" + $sc_field.attr('value') + "</code>\n");
		});
	}) (window);
});
