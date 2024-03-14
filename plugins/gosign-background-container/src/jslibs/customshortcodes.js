(function($)
{
	"use strict";
	$(document).ready(function() {

		var countAlgin = $("div.align-true").length;
		$("div.align-true").each(function (i) {
			if (i+1 === countAlgin) {
				var heightInner = $(this).find('.contentBlock').outerHeight();
				$(this).find('.inner-container').height(heightInner);
			}
		});
		// parallax on mouse
		var mouseonhover = $(".colorSectionFe.background-parallax-mouse").length;
		$(".colorSectionFe.background-parallax-mouse").each(function (i) {
			if (i+1 === mouseonhover) {
				var pixelToMove = 20;
				$(this).find(".outer-container").mousemove(function(e) {
					var width = $(this).innerWidth();
					var height = $(this).innerHeight();
					var newValueX = (e.pageX / width) * pixelToMove;
					var newValueY = (e.pageY / height) * pixelToMove;
					$(this).css('background-position', newValueX + '%' + ' ' + newValueY + '%');
				});
			}
		});
	});
})(jQuery);
