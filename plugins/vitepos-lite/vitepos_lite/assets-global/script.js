/******/ (() => { // webpackBootstrap
	/******/ 	"use strict";
	var __webpack_exports__ = {};

	;// CONCATENATED MODULE: ./assets/libs/tooltip/appsbd_tooltip.js
	const ApspbdTooltip = function (options) {
		let theme  = options.theme || "dark",
		  delay    = options.delay || 0,
		  dist     = options.distance || 10,
		  dataName = options.dataName || 'data-app-tooltip';
		document.body.addEventListener(
			"mouseover",
			function (e) {
				if ( ! e.target.hasAttribute( dataName )) {
					return;
				}
				var tooltip       = document.createElement( "div" );
				tooltip.innerHTML = e.target.getAttribute( dataName );
				document.body.appendChild( tooltip );
				let pos           = e.target.getAttribute( 'data-position' ) || "center top",
				posHorizontal     = pos.split( " " )[0],
				posVertical       = pos.split( " " )[1];
				tooltip.className = "apbd-vj-tooltip " + "apbd-vj-tooltip-" + theme + " " + "apbd-vj-tooltip-pos-" + pos.replace( ' ', '-' );
				positionAt( e.target, tooltip, posHorizontal, posVertical );
			}
		);
		document.body.addEventListener(
			"mouseout",
			function (e) {
				if (e.target.hasAttribute( dataName )) {
					if (delay > 0) {
						setTimeout(
							function () {
								document.body.removeChild( document.querySelector( ".apbd-vj-tooltip" ) );
							},
							delay
						);
					} else {
						document.body.removeChild( document.querySelector( ".apbd-vj-tooltip" ) );
					}
				}
			}
		);
		/**
		 * Positions the tooltip.
		 *
		 * @param {object} parent - The trigger of the tooltip.
		 * @param {object} tooltip - The tooltip itself.
		 * @param {string} posHorizontal - Desired horizontal position of the tooltip relatively to the trigger (left/center/right)
		 * @param {string} posVertical - Desired vertical position of the tooltip relatively to the trigger (top/center/bottom)
		 */

		function positionAt(parent, tooltip, posHorizontal, posVertical) {
			var parentCoords = parent.getBoundingClientRect(),
			left,
			top;

			switch (posHorizontal) {
				case "left":
					left = parseInt( parentCoords.left ) - dist - tooltip.offsetWidth;

					if (parseInt( parentCoords.left ) - tooltip.offsetWidth < 0) {
						  left = dist;
					}

					  break;

				case "right":
					left = parentCoords.right + dist;

					if (parseInt( parentCoords.right ) + tooltip.offsetWidth > document.documentElement.clientWidth) {
						left = document.documentElement.clientWidth - tooltip.offsetWidth - dist;
					}

					  break;

				default:
				case "center":
					left = parseInt( parentCoords.left ) + (parent.offsetWidth - tooltip.offsetWidth) / 2;
			}

			switch (posVertical) {
				case "center":
					top = (parseInt( parentCoords.top ) + parseInt( parentCoords.bottom )) / 2 - tooltip.offsetHeight / 2;
				  break;

				case "bottom":
					top = parseInt( parentCoords.bottom ) + dist;
				  break;

				default:
				case "top":
					top = parseInt( parentCoords.top ) - tooltip.offsetHeight - dist;
			}

			left               = left < 0 ? parseInt( parentCoords.left ) : left;
			top                = top < 0 ? parseInt( parentCoords.bottom ) + dist : top;
			tooltip.style.left = left + "px";
			tooltip.style.top  = top + pageYOffset + "px";
		}
	};

	/* harmony default export */ const appsbd_tooltip = (ApspbdTooltip);
	;// CONCATENATED MODULE: ./assets/index.js




	(function () {
		new appsbd_tooltip(
			{
				theme: "dark",
				delay: 0,
				dataName: 'data-app-title'
			}
		);
	})();
/******/ })();
