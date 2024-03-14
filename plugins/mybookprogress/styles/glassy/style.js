jQuery(document).ready(function() {
	jQuery('.mbp-container .mbp-book .mbp-book-progress').each(function(i, e) {
		var element = jQuery(e);
		var barbg = element.find('.mbp-book-progress-barbg');
		var bar = element.find('.mbp-book-progress-bar');
		var label = element.find('.mbp-book-progress-label');

		//shift the label over to the side of the bar if there is not enough room for it to fit fully on the bar
		if(bar.outerWidth()+label.outerWidth() > barbg.width()) {
			label.css({ left: barbg.outerWidth() });
		}
	});
});
