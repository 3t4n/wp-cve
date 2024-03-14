(function($, w) {
	'use strict';

	w['vapOpenPopup'] = (link) => {
		return $.fancybox.open({
			src:  link,
			type: 'iframe',
			opts: {
				iframe: {
					css: {
						width:  '95%',
						height: '95%',
					},
				},
			},
		});
	}

	w['vapOpenModalImage'] = (link, elem) => {
		var data = null;

		if (Array.isArray(link)) {
			data = [];
			// display gallery
			for (var i = 0; i < link.length; i++) {
				// extract caption
				var caption = link[i].split('/').pop();
				var match   = caption.match(/(.*?)\.[a-z0-9.]{2,}/i)

				if (match) {
					caption = match[1];
				}

				// make caption human readable
				caption = caption.replace(/[-_]+/, ' ').split(' ').filter(function(word) {
					return word.length ? true : false;
				}).map(function(word) {
					return word[0].toUpperCase() + word.substr(1);
				}).join(' ');

				data.push({
					src:  link[i],
					type: 'image',
					opts : {
						caption : caption,
						thumb   : link[i].replace(/\/media\//, '/media@small/'),
					},
				});
			}
		} else {
			// display single image
			data = {
				src:  link,
				type: 'image',
			};

			// find image tag inside the element
			let imgTag = elem ? $(elem).find('img') : null;

			if (imgTag) {
				data.opts = {
					caption: imgTag.data('caption') || imgTag.attr('title'),
				};
			}
		}

		return $.fancybox.open(data);
	}

	/**
	 * Starting from Joomla 4.0.4 the popover stopped working as
	 * expected, because the content was no more displayed. In order
	 * avoid any issues, we need to automatically init the popover 
	 * via javascript according to our needs.
	 *
	 * @param 	string  selector
	 *
	 * @return  void
	 */
	w['__vikappointments_j40_popover_init'] = (selector) => {
		$(selector).each(function() {
			$(this).popover({
				trigger:   $(this).data('trigger'),
				placement: $(this).data('placement'),
				html:      true,
				sanitize:  false,
			});
		});
	}
})(jQuery, window);