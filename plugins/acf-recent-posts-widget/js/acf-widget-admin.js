(function ($) {
	"use strict";
	var Site = {
		init: function () {
			Site.DatePicker();
		},
		/**
		 * Enable jQuery UI DatePicker
		 */
		DatePicker: function () {
			$('.widget-content').on('focus', '.picker', function(){
				$(this).datepicker();
			});
		}

	};

	$(document).ready(function () {
		Site.init();
	});

})(jQuery);

