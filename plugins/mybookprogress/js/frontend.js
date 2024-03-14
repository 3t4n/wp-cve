window.mybookprogress = jQuery.extend(window.mybookprogress, {

	simple_subscribe_form: function(element) {
		var form = jQuery('<form class="mbp-subscribe-form"><input type="submit" class="mbp-subscribe-submit" value="Sign Up"><div class="mbp-subscribe-email-container"><input type="email" class="mbp-subscribe-email" placeholder="you@example.com"></div></form>');

		form.on('submit', function() {
			var submit_form = jQuery(this);
			var submit_button = submit_form.find('.mbp-subscribe-submit');
			var email_input = submit_form.find('.mbp-subscribe-email');

			if(email_input.val().indexOf("@") === -1) {
				email_input.addClass('mbp-error');
				return false;
			} else {
				email_input.removeClass('mbp-error');
			}

			submit_button.attr('disabled', 'disabled');
			email_input.attr('disabled', 'disabled');

			jQuery.post(ajaxurl,
				{
					action: 'mbp_submit_simple_subscribe_form',
					email: email_input.val()
				},
				function(data) {
					submit_form.html('<div class="mbp-subscribe-message">'+data+'</div>');
				}
			);
			return false;
		});

		jQuery(element).replaceWith(form);
		return false;
	},

	//returns an object with r, g, and b, values, integer from 0 to 255. Eg. {r: 0, g: 128: b: 255}
	get_bg_color: function(element) {
		var color = jQuery(element).css('background-color');
		var rgb = color.match(/^rgb(?:a?)\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))?\)$/);
		return {r: parseInt(rgb[1], 10), g: parseInt(rgb[2], 10), b: parseInt(rgb[3], 10)};
	},

	//color functions adapted from: http://stackoverflow.com/questions/17242144/javascript-convert-hsb-hsv-color-to-rgb-accurately

	//color is a object with h, s, and v values, floating point values from 0 to 1. Eg. {h: 0, s: 0.5, v: 1}
	//returns an object with r, g, and b, values, integer from 0 to 255. Eg. {r: 0, g: 128: b: 255}
	hsv_to_rgb: function(color) {
    	var r, g, b, i, f, p, q, t;
		h = color.h, s = color.s, v = color.v;

		i = Math.floor(h * 6);
		f = h * 6 - i;
		p = v * (1 - s);
		q = v * (1 - f * s);
		t = v * (1 - (1 - f) * s);

		switch (i % 6) {
			case 0: r = v, g = t, b = p; break;
			case 1: r = q, g = v, b = p; break;
			case 2: r = p, g = v, b = t; break;
			case 3: r = p, g = q, b = v; break;
			case 4: r = t, g = p, b = v; break;
			case 5: r = v, g = p, b = q; break;
		}

		return {
			r: Math.round(r * 255),
			g: Math.round(g * 255),
			b: Math.round(b * 255)
		};
	},

	//color is a object with r, g, and b, values, integer from 0 to 255. Eg. {r: 0, g: 128: b: 255}
	//returns an object with h, s, and v values, floating point values from 0 to 1. Eg. {h: 0, s: 0.5, v: 1}
	rgb_to_hsv: function(color) {
		var max, min, d, h, s, v;
		r = color.r, g = color.g, b = color.b;

		max = Math.max(r, g, b);
		min = Math.min(r, g, b);
		d = max - min;
		s = (max === 0 ? 0 : d / max);
		v = max / 255;

		switch(max) {
			case min: h = 0; break;
			case r: h = (g - b) + d * (g < b ? 6: 0); h /= 6 * d; break;
			case g: h = (b - r) + d * 2; h /= 6 * d; break;
			case b: h = (r - g) + d * 4; h /= 6 * d; break;
		}

		return {
			h: h,
			s: s,
			v: v
		};
	},

	//return value and color arguments are objects with r, g, and b, values, integer from 0 to 255. Eg. {r: 0, g: 128: b: 255}
	//amount is a floating point value from 0 to 1, 0 returning entirely color1 and 1 returning entirely color2
	rgb_blend: function(color1, color2, amount) {
		var inv_amount = 1.0-amount;
		return {
			r: Math.round(inv_amount*color1.r+amount*color2.r),
			g: Math.round(inv_amount*color1.g+amount*color2.g),
			b: Math.round(inv_amount*color1.b+amount*color2.b),
		};
	},

	color_is_bright: function(color) {
		var luma = 0.2126 * color.r + 0.7152 * color.g + 0.0722 * color.b;
		return luma > 128;
	},

	mybooktable_link: function(element) {
		window.open(jQuery(element).attr('data-href'), '_blank').focus();
	},
});
