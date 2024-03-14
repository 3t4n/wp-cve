jQuery(document).ready(function ($) {
	function wpmlwebp_lazy_load() {
		$.each($('img'), function () {
			if ($(this).attr('data-lazysrc') && $(this).offset().top < ($(window).scrollTop() + $(window).height() + 10)) {
				$(this).attr('src', $(this).attr("data-lazysrc"));
				$(this).removeAttr('data-lazysrc');
			}
		})
	}

	wpmlwebp_lazy_load()
	$(window).scroll(wpmlwebp_lazy_load);
})