jQuery(document).ready(function() {
	let popupPosition = function() {
		let pad = jQuery('#wpcontent').css('padding-left');

		jQuery('.trustindex-popup').css({
			right: pad,
			'margin-left': pad
		});
	};

	popupPosition();
	jQuery(window).resize(popupPosition);

	jQuery(document).on('click', 'a.trustindex-rateus', function(e) {
		//Get link, close button and url
		let link = jQuery(this),
			closeButton = link.closest('.notice').find('.notice-dismiss'),
			url = link.attr('href');

		//Hide the modal - click close button
		closeButton.click();

		//Make ajax at the background if not the rate button clicked
		if(link.attr('target') === undefined)
		{
			e.preventDefault();
			jQuery.get(url);
			return false;
		}
	});
});