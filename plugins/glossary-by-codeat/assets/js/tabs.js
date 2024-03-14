(function ($) {
	"use strict";
	$(function () {
		$(".glossary-settings #tabs").tabs({
			activate: function (event, ui) {
				var scrollPos = $(window).scrollTop();
				window.location.hash = $(ui.newPanel[0]).attr("id");
				$(window).scrollTop(scrollPos);
				$(window).trigger("resize");
				$("#gl-sticky").trigger("hover");
			},
		});
	});
})(jQuery);
