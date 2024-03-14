jQuery(document).ready(function() {
	jQuery('.mbp-container .mbp-book .mbp-book-progress').each(function(i, e) {
		var element = jQuery(e);
		var bar = element.find('.mbp-book-progress-bar');
		var label = element.find('.mbp-book-progress-label');

		var color = mybookprogress.get_bg_color(bar);
		var bgcolor = mybookprogress.rgb_blend(color, {r:255, g:255, b:255}, 0.5);
		var labelcolor = mybookprogress.rgb_blend(color, {r:255, g:255, b:255}, 0.8);
		var textcolor = mybookprogress.rgb_blend(color, {r:0, g:0, b:0}, 0.7);

		//change background color
		element.css({'background': 'rgba('+bgcolor.r+', '+bgcolor.g+', '+bgcolor.b+', 1)'});

		//add pointer arrow to label and change color
		label.css({
			'background': 'rgba('+labelcolor.r+', '+labelcolor.g+', '+labelcolor.b+', 1)',
			'color': 'rgba('+textcolor.r+', '+textcolor.g+', '+textcolor.b+', 1)',
		});
		if(!label.find('span').length) {
			var pointer = jQuery('<span></span>');
			pointer.css({'border-bottom-color': 'rgba('+labelcolor.r+', '+labelcolor.g+', '+labelcolor.b+', 1)'});
			label.append(pointer);
		}
	});
});
