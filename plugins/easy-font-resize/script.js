jQuery(document).ready(function ($) {
	let min_modifier = parseFloat(wpavefrsz.min_modifier) || 0.7;
	let max_modifier = parseFloat(wpavefrsz.max_modifier) || 1.3;
	let modifier = parseFloat(wpavefrsz.step_modifier) || 0.1;
	let current_modifier = 1.0;
	let zoom_level = 0;

	let minus = $('.wpavefrsz-minus');
	let plus = $('.wpavefrsz-plus');
	let reset = $('.wpavefrsz-reset');

	let initial_sizes = [];
	let flag = false;

	function change_font_size(operator, bypass_storage) {
		current_modifier = Math.round(current_modifier * 100) / 100;

		let calculated_modifier = current_modifier;

		if (operator === 'plus') {
			if (current_modifier >= max_modifier) {
				return;
			}
		}

		if (operator === 'minus') {
			if (current_modifier <= min_modifier) {
				return;
			}
		}

		if (operator === 'minus') {
			calculated_modifier -= modifier;
			zoom_level--;
		} else if (operator === 'plus') {
			calculated_modifier += modifier;
			zoom_level++;
		} else if (operator === 'reset') {
			calculated_modifier = 1.0;
			zoom_level = 0;
		}

		current_modifier = calculated_modifier;

		let e_index = 0;

		$.each(wpavefrsz.elements, function (index, item) {
			$(wpavefrsz.main_selector + ' ' + item).add(wpavefrsz.include_selectors).not(wpavefrsz.exclude_selectors).each(function () {
				let font_size = parseFloat($(this).css('font-size'));

				if (!flag) {
					initial_sizes[e_index] = font_size;
				}

				let calculated_font_size;

				if (operator === 'minus') {
					calculated_font_size = (font_size * (1.0 - modifier)).toFixed(1);
				} else if (operator === 'plus') {
					calculated_font_size = (font_size * (1.0 + modifier)).toFixed(1);
				} else if (operator === 'reset') {
					calculated_font_size = initial_sizes[e_index];
				}

				calculated_font_size += 'px';

				if (wpavefrsz.wpavefrsz_remember_font_size_enforce) {
					$(this).attr('style', 'font-size: ' + calculated_font_size + ' !important');
				} else {
					$(this).css('font-size', calculated_font_size);
				}

				e_index++;
			});
		});

		if (wpavefrsz.remember_font_size_sitewide) {
			if (typeof bypass_storage === 'undefined') {
				localStorage.setItem('wpavefrsz_zoom_level', zoom_level);
			}
		}

		flag = true;
	}

	minus.on('click', function (e) {
		e.preventDefault();

		change_font_size('minus');
	});
	plus.on('click', function (e) {
		e.preventDefault();

		change_font_size('plus');
	});
	reset.on('click', function (e) {
		e.preventDefault();

		change_font_size('reset');
	});

	if (wpavefrsz.remember_font_size_sitewide) {
		let stored_zoom_level = localStorage.getItem('wpavefrsz_zoom_level');

		if (stored_zoom_level !== 0) {
			let operator;

			if (stored_zoom_level < 0) {
				operator = 'minus';
			} else {
				operator = 'plus';
			}

			for (let i = 0; i < Math.abs(stored_zoom_level); i++) {
				change_font_size(operator, true);
			}
		}
	}
});