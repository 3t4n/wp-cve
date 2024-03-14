jQuery(document).ready(function() {
	jQuery('.mbp-container .mbp-book .mbp-book-progress').each(function(i, e) {
		var element = jQuery(e);
		var bar = element.find('.mbp-book-progress-bar');
		var label = element.find('.mbp-book-progress-label');

		var color = mybookprogress.get_bg_color(bar);

		//add glow to progress bar
		if(!bar.hasClass('mbp-book-progress-initialized')) {
			bar.css({
				'box-shadow': '0px 0px 12px 0px rgba('+color.r+', '+color.g+', '+color.b+', 1), '+bar.css('box-shadow'),
			});
			bar.addClass('mbp-book-progress-initialized');
		}

		//add pointer arrow to label
		if(!label.find('span').length) { label.append('<span></span>'); }
	});
});
