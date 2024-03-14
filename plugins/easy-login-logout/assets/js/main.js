;(function ($) {
	$(document).ready(function () {

		function redirect_type() {
			var $this = $(this),
				$url = $this.parents('.menu-item').find('.nav_item_options-redirect_url');

			if ($this.val() == 'custom') {
				$url.slideDown();
			} else {
				$url.slideUp();
			}
		}

		function refresh_all_items() {
			$('.nav_item_options-redirect_type select').each(redirect_type);
		}
		$(document).on('change', '.nav_item_options-redirect_type select', redirect_type).ready(refresh_all_items);

		$('.submit-add-to-menu').click(function () {
			setTimeout(refresh_all_items, 1000);
		});

	});
})(jQuery);