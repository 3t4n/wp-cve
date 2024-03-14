/* global jQuery */

window.handleMsFormSubmit = e => {
	e.preventDefault();
	e.target.closest('form').submit();
};

(function ($) {
	$(document).ready(function () {
		// WP admin likes to scroll to the bottom for no apparent reason
		window.scrollTo({ top: 0 });

		// Handle the tabs logic
		$('.memberspace-tab').click(function (event) {
			event.preventDefault();

			// try to set the tab in the browser URL if supported
			if ('URLSearchParams' in window) {
				const searchParams = new URLSearchParams(window.location.search);
				const tab = $(this).attr('href').substring(1);

				searchParams.set('tab', tab);
				const newRelativePathQuery = `${
					window.location.pathname
				}?${searchParams.toString()}`;
				window.history.pushState(null, '', newRelativePathQuery);
			}

			// set current tab to active
			$(this).addClass('active');
			$('.memberspace-tab').not(this).removeClass('active');

			// set current tab content to active
			const tabTarget = $(this).attr('href');
			$('.memberspace-tab-content').removeClass('active');
			$(tabTarget).addClass('active');
		});
	});
})(jQuery);
