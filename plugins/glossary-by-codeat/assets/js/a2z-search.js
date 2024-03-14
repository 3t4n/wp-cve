document.addEventListener("DOMContentLoaded", (event) => {
	var keywordInput = document.querySelector("input.gt-search");

	if (document.querySelector("input.gt-search") !== null) {
		window.currentIndex = 0;
		window.markResults = [];
		var markContent = document.querySelector(".glossary-term-list");
		window.markInstance = new Mark(markContent);
		keywordInput.addEventListener("input", function () {
			var keyword = keywordInput.value;
			window.markInstance.unmark({
				done: function () {
					window.markInstance.mark(keyword, {
						separateWordSearch: true,
						done: function () {
							window.markResults = markContent.querySelectorAll("mark");
							window.currentIndex = 0;
						},
					});
				},
			});
		});
	}

	function markMove() {
		if (
			typeof window.markResults !== "undefined" &&
			window.markResults.length
		) {
			window.currentIndex += this.className === "gt-prev" ? -1 : 1;
			if (window.currentIndex < 0) {
				window.currentIndex = window.markResults.length - 1;
			}

			if (window.currentIndex > window.markResults.length - 1) {
				window.currentIndex = 0;
			}

			window.markResults[window.currentIndex].scrollIntoView();
		}
	}

	if ( document.querySelector("button.gt-next") !== null ) {
		var rect = document.querySelector("button.gt-next").getBoundingClientRect();
		var styleSheet = document.createElement("style");
		styleSheet.innerText = 'mark[data-markjs] { scroll-margin:' + (rect.height + 40) + 'px; }'; // 40 is the top
		document.head.appendChild(styleSheet);
		document.querySelector("button.gt-next").addEventListener("click", markMove);
		document.querySelector("button.gt-prev").addEventListener("click", markMove);
	}

	var Sticky = (function () {
		"use strict";

		var Sticky = {
			element: null,
			position: 0,
			addEvents: function () {
				window.addEventListener("scroll", this.onScroll.bind(this));
			},
			init: function (element) {
				this.element = element;
				this.addEvents();
				this.position = element.offsetTop + 10;
				this.onScroll();
			},
			aboveScroll: function () {
				return this.position < window.scrollY;
			},
			onScroll: function (event) {
				if (this.aboveScroll()) {
					this.setFixed();
				} else {
					this.setStatic();
				}
			},
			setFixed: function () {
				this.element.classList.add("is-fixed");
				// not needed if added with CSS Class
				this.element.style.position = "fixed";
				if (this.element.getAttribute("data-scroll") === "scroll-bottom") {
					this.element.style.bottom = 0;
				} else {
					this.element.style.top = 0;
				}
			},
			setStatic: function () {
				this.element.classList.remove("is-fixed");
				// not needed if added with CSS Class
				this.element.style.position = "static";
				this.element.style.top = "auto";
			},
		};

		return Sticky;
	})();

	var sticky = document.querySelector(".gt-search-bar");
	if (
		sticky.getAttribute("data-scroll") === "scroll" ||
		sticky.getAttribute("data-scroll") === "scroll-bottom"
	) {
		Sticky.init(sticky);
	}
});
