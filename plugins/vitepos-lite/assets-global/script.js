/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/global */
/******/ 	(() => {
/******/ 		__webpack_require__.g = (function() {
/******/ 			if (typeof globalThis === 'object') return globalThis;
/******/ 			try {
/******/ 				return this || new Function('return this')();
/******/ 			} catch (e) {
/******/ 				if (typeof window === 'object') return window;
/******/ 			}
/******/ 		})();
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/publicPath */
/******/ 	(() => {
/******/ 		var scriptUrl;
/******/ 		if (__webpack_require__.g.importScripts) scriptUrl = __webpack_require__.g.location + "";
/******/ 		var document = __webpack_require__.g.document;
/******/ 		if (!scriptUrl && document) {
/******/ 			if (document.currentScript)
/******/ 				scriptUrl = document.currentScript.src
/******/ 			if (!scriptUrl) {
/******/ 				var scripts = document.getElementsByTagName("script");
/******/ 				if(scripts.length) scriptUrl = scripts[scripts.length - 1].src
/******/ 			}
/******/ 		}
/******/ 		// When supporting browsers where an automatic publicPath is not supported you must specify an output.publicPath manually via configuration
/******/ 		// or pass an empty string ("") and set the __webpack_public_path__ variable from your code to use your own logic.
/******/ 		if (!scriptUrl) throw new Error("Automatic publicPath is not supported in this browser");
/******/ 		scriptUrl = scriptUrl.replace(/#.*$/, "").replace(/\?.*$/, "").replace(/\/[^\/]+$/, "/");
/******/ 		__webpack_require__.p = scriptUrl;
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};

;// CONCATENATED MODULE: ./assets/libs/tooltip/appsbd_tooltip.js
const ApspbdTooltip = function (options) {
  let theme = options.theme || "dark",
      delay = options.delay || 0,
      dist = options.distance || 10,
      dataName = options.dataName || 'data-app-tooltip';
  document.body.addEventListener("mouseover", function (e) {
    if (!e.target.hasAttribute(dataName)) return;
    var tooltip = document.createElement("div");
    tooltip.innerHTML = e.target.getAttribute(dataName);
    document.body.appendChild(tooltip);
    let pos = e.target.getAttribute('data-position') || "center top",
        posHorizontal = pos.split(" ")[0],
        posVertical = pos.split(" ")[1];
    tooltip.className = "apbd-vj-tooltip " + "apbd-vj-tooltip-" + theme + " " + "apbd-vj-tooltip-pos-" + pos.replace(' ', '-');
    positionAt(e.target, tooltip, posHorizontal, posVertical);
  });
  document.body.addEventListener("mouseout", function (e) {
    if (e.target.hasAttribute(dataName)) {
      if (delay > 0) {
        setTimeout(function () {
          document.body.removeChild(document.querySelector(".apbd-vj-tooltip"));
        }, delay);
      } else {
        document.body.removeChild(document.querySelector(".apbd-vj-tooltip"));
      }
    }
  });
  /**
   * Positions the tooltip.
   *
   * @param {object} parent - The trigger of the tooltip.
   * @param {object} tooltip - The tooltip itself.
   * @param {string} posHorizontal - Desired horizontal position of the tooltip relatively to the trigger (left/center/right)
   * @param {string} posVertical - Desired vertical position of the tooltip relatively to the trigger (top/center/bottom)
   *
   */

  function positionAt(parent, tooltip, posHorizontal, posVertical) {
    var parentCoords = parent.getBoundingClientRect(),
        left,
        top;

    switch (posHorizontal) {
      case "left":
        left = parseInt(parentCoords.left) - dist - tooltip.offsetWidth;

        if (parseInt(parentCoords.left) - tooltip.offsetWidth < 0) {
          left = dist;
        }

        break;

      case "right":
        left = parentCoords.right + dist;

        if (parseInt(parentCoords.right) + tooltip.offsetWidth > document.documentElement.clientWidth) {
          left = document.documentElement.clientWidth - tooltip.offsetWidth - dist;
        }

        break;

      default:
      case "center":
        left = parseInt(parentCoords.left) + (parent.offsetWidth - tooltip.offsetWidth) / 2;
    }

    switch (posVertical) {
      case "center":
        top = (parseInt(parentCoords.top) + parseInt(parentCoords.bottom)) / 2 - tooltip.offsetHeight / 2;
        break;

      case "bottom":
        top = parseInt(parentCoords.bottom) + dist;
        break;

      default:
      case "top":
        top = parseInt(parentCoords.top) - tooltip.offsetHeight - dist;
    }

    left = left < 0 ? parseInt(parentCoords.left) : left;
    top = top < 0 ? parseInt(parentCoords.bottom) + dist : top;
    tooltip.style.left = left + "px";
    tooltip.style.top = top + pageYOffset + "px";
  }
};

/* harmony default export */ const appsbd_tooltip = (ApspbdTooltip);
;// CONCATENATED MODULE: ./assets/icon-256x256.gif
const icon_256x256_namespaceObject = __webpack_require__.p + "./img/icon-256x256.gif";
;// CONCATENATED MODULE: ./assets/icon-256-256.png
const icon_256_256_namespaceObject = __webpack_require__.p + "./img/icon-256-256.png";
;// CONCATENATED MODULE: ./assets/index.js







(function () {
  new appsbd_tooltip({
    theme: "dark",
    delay: 0,
    dataName: 'data-app-title'
  });
})();
/******/ })()
;