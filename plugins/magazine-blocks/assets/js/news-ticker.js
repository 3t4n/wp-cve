(function ($) {
	"use strict";
	$.fn.MzbTicker = function (options) {
		/*Merge options and default options*/
		const opts = $.extend({}, $.fn.MzbTicker.defaults, options);

		/*Functions Scope*/
		let thisTicker = $(this),
			intervalID,
			timeoutID,
			isPause = false;

		/*Always wrap, used in many place*/
		thisTicker.wrap("<div class='mzbticker-wrap'></div>");

		/*Wrap is always relative*/
		thisTicker.parent().css({
			position: "relative",
		});
		/*Hide expect first*/
		thisTicker.children("li").not(":first").hide();

		/*Lets init*/
		init();
		function init() {
			switch (opts.type) {
				case "vertical":
				case "horizontal":
					vertiZontal();
					break;

				default:
					break;
			}
		}

		/*Vertical - horizontal
		 * **Do not change code lines*/
		function vertiZontal(prevNext = false) {
			let speed = opts.speed,
				autoplay = opts.autoplay,
				direction = opts.direction;

			if (prevNext) {
				speed = 0;
				autoplay = 0;
				clearInterval(intervalID);
				intervalID = false;
			}

			function play() {
				if (isPause) {
					clearInterval(intervalID);
					intervalID = false;
					return false;
				}
				let dChild, eqType, mType, mVal;

				dChild = thisTicker.find("li:first");
				if (direction === "up" || direction === "right") {
					eqType = "-=";
				} else {
					eqType = "+=";
				}
				if (opts.type === "horizontal") {
					mType = "left";
					mVal = dChild.outerWidth(true);
				} else {
					mType = "margin-top";
					mVal = dChild.outerHeight(true);
				}
				if (prevNext === "prev") {
					thisTicker.find("li:last").detach().prependTo(thisTicker);
				} else {
					dChild.detach().appendTo(thisTicker);
				}

				thisTicker.find("li").css({
					opacity: "0",
					display: "none",
				});
				thisTicker.find("li:first").css({
					opacity: "1",
					position: "absolute",
					display: "block",
					width: "100%",
					[mType]: eqType + mVal + "px",
				});
				thisTicker
					.find("li:first")
					.animate({ [mType]: "0px" }, speed, function () {
						clearInterval(intervalID);
						intervalID = false;
						vertiZontal();
					});
			}
			if (intervalID) {
				return false;
			}
			intervalID = setInterval(play, autoplay);
		}

		/*Type-Writer
		 * **Do not change code lines*/
		function typeWriter(prevNext = false) {
			if (isPause) {
				return false;
			}
			if (prevNext) {
				clearInterval(intervalID);
				intervalID = false;

				clearTimeout(timeoutID);
				timeoutID = false;

				if (prevNext === "prev") {
					thisTicker.find("li:last").detach().prependTo(thisTicker);
				} else {
					thisTicker.find("li:first").detach().appendTo(thisTicker);
				}
			}

			let speed = opts.speed,
				autoplay = opts.autoplay,
				typeEl = thisTicker.find("li:first"),
				wrapEl = typeEl.children(),
				count = 0;

			if (typeEl.attr("data-text")) {
				wrapEl.text(typeEl.attr("data-text"));
			}

			const allText = typeEl.text();

			thisTicker.find("li").css({
				opacity: "0",
				display: "none",
			});

			function tNext() {
				thisTicker.find("li:first").detach().appendTo(thisTicker);

				clearTimeout(timeoutID);
				timeoutID = false;

				typeWriter();
			}

			function type() {
				count++;
				const typeText = allText.substring(0, count);
				if (!typeEl.attr("data-text")) {
					typeEl.attr("data-text", allText);
				}

				if (count <= allText.length) {
					wrapEl.text(typeText);
					typeEl.css({
						opacity: "1",
						display: "block",
					});
				} else {
					clearInterval(intervalID);
					intervalID = false;
					timeoutID = setTimeout(tNext, autoplay);
				}
			}
			if (!intervalID) {
				intervalID = setInterval(type, speed);
			}
		}
	};

	// plugin defaults - added as a property on our plugin function
	$.fn.MzbTicker.defaults = {
		/*Note: Marquee only take speed not autoplay*/
		type: "horizontal" /*vertical/horizontal/marquee/typewriter*/,
		autoplay: 2000 /*true/false/number*/ /*For vertical/horizontal 4000*/ /*For typewriter 2000*/,
		speed: 50 /*true/false/number*/ /*For vertical/horizontal 600*/ /*For marquee 0.05*/ /*For typewriter 50*/,
		direction:
			"up" /*up/down/left/right*/ /*For vertical up/down*/ /*For horizontal/marquee right/left*/ /*For typewriter direction doesnot work*/,
		pauseOnFocus: true,
		pauseOnHover: true,
		controls: {
			prev: "" /*Can be used for vertical/horizontal/typewriter*/ /*not work for marquee*/,
			next: "" /*Can be used for vertical/horizontal/typewriter*/ /*not work for marquee*/,
			toggle: "" /*Can be used for vertical/horizontal/marquee/typewriter*/,
		},
	};
})(jQuery);

(function ($) {
	"use strict";
	$(".mzb-news-ticker-list").each(function () {
		$(this).MzbTicker({
			type: "vertical",
			direction: "up",
			autoplay: 2000,
			speed: 1000,
		});
	});
})(jQuery);
