/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/frontend.scss":
/*!***************************!*\
  !*** ./src/frontend.scss ***!
  \***************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "react-dom":
/*!***************************!*\
  !*** external "ReactDOM" ***!
  \***************************/
/***/ ((module) => {

module.exports = window["ReactDOM"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*************************!*\
  !*** ./src/frontend.js ***!
  \*************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var react_dom__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react-dom */ "react-dom");
/* harmony import */ var react_dom__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react_dom__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _frontend_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./frontend.scss */ "./src/frontend.scss");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__);






const divsToUpdate = document.querySelectorAll(".toms-image-slider");
divsToUpdate.forEach(function (div) {
  const data = JSON.parse(div.querySelector("pre").innerHTML);
  if (data.imageSlider !== false) {
    react_dom__WEBPACK_IMPORTED_MODULE_2___default().render((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(TomSImageSliderFrontend, data), div);
    div.classList.remove("toms-image-slider");
  }
});
function TomSImageSliderFrontend(slides) {
  var defaultImageFromPHP = tomsSlieshowDefaultFromPHP.defaultImage;
  if (!!slides.galleryImages) {
    const [current, setCurrent] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(0);
    const [currentImg, setCurrentImg] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(slides.galleryImages[0]);
    const length = slides.galleryImages.length;
    const slideImg = slides.galleryImages;
    const nextSlide = () => {
      setCurrent(current === length - 1 ? 0 : current + 1), setCurrentImg(slideImg[current === length - 1 ? 0 : current + 1]);
      isOpen && setModalImg(slideImg[current === length - 1 ? 0 : current + 1]);
    };
    const prevSlide = () => {
      setCurrent(current === 0 ? length - 1 : current - 1), setCurrentImg(slideImg[current === 0 ? length - 1 : current - 1]);
      isOpen && setModalImg(slideImg[current === 0 ? length - 1 : current - 1]);
    };

    //自动滑动 autoplay
    const timeoutRef = (0,react__WEBPACK_IMPORTED_MODULE_1__.useRef)(null);
    function resetTimeout() {
      /*清空计时函数*/
      if (timeoutRef.current) {
        clearTimeout(timeoutRef.current);
      }
    }
    (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
      if (slides.autoPlay !== false && length >= 1) {
        resetTimeout(); /*清空计时*/

        timeoutRef.current = setTimeout(() => {
          setCurrent(current === length - 1 ? 0 : current + 1), setCurrentImg(slideImg[current === length - 1 ? 0 : current + 1]);
        }, 3600);
        return () => {
          resetTimeout(); /*返回清空计时*/
        };
      }
    }, [current]);

    //图片弹窗
    const [isOpen, setOpen] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(false);
    const openModal = () => {
      slides.sliderModal !== false && setOpen(true);
    };
    const closeModal = () => setOpen(false);
    const [modalCurrent, setModalCurrent] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(0);
    const [modalImg, setModalImg] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(slides.galleryImages[0]);
    const modalNextSlide = () => {
      setModalCurrent(modalCurrent === length - 1 ? 0 : modalCurrent + 1);
      setModalImg(slideImg[modalCurrent === length - 1 ? 0 : modalCurrent + 1]);
    };
    const modalPrevSlide = () => {
      setModalCurrent(modalCurrent === 0 ? length - 1 : modalCurrent - 1);
      setModalImg(slideImg[modalCurrent === 0 ? length - 1 : modalCurrent - 1]);
    };

    //控制显示缩略图
    const [showState, setShowState] = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)({
      shown: true
    });
    function showThumnbail() {
      return setShowState({
        shown: !showState.shown
      });
    }
    if (!Array.isArray(slides.galleryImages)) {
      /*判断图片的值是否小于0 或者是数组*/
      return null;
    }
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("section", {
      className: "toms-image-slider-preview"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "toms-image-slider-image"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "left-arrow",
      onClick: prevSlide
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
      t: "1632461798804",
      className: "icon",
      viewBox: "0 0 1024 1024",
      version: "1.1",
      xmlns: "http://www.w3.org/2000/svg",
      "p-id": "8849",
      width: "32",
      height: "32"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
      d: "M804.157345 1024H596.658663c-9.595315 0-18.790825-4.297901-24.787897-11.794241L247.229283 612.600878c-47.776672-58.771303-47.776672-142.93021 0-201.701512L571.470961 11.794241c6.097023-7.49634 15.192582-11.794241 24.787897-11.794241h206.799024c6.69673 0 10.494876 7.796193 6.196974 12.993655L436.936652 471.369839c-19.090678 23.488531-19.090678 57.172084 0 80.760566l373.417667 458.775988c4.19795 5.297413 0.499756 13.093607-6.196974 13.093607z",
      "p-id": "8850"
    }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "toms-image-slider-active-image",
      onClick: openModal
    }, !!slideImg[current] ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      src: slideImg[current],
      className: "toms-image-slider-gellery-image",
      width: "200",
      height: "200"
    }) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      src: defaultImageFromPHP,
      className: "toms-image-slider-gellery-image",
      width: "200",
      height: "200"
    })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "right-arrow",
      onClick: nextSlide
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
      t: "1632461740711",
      className: "icon",
      viewBox: "0 0 1024 1024",
      version: "1.1",
      xmlns: "http://www.w3.org/2000/svg",
      "p-id": "8565",
      width: "32",
      height: "32"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
      d: "M776.270961 612.600878l-324.641483 399.604881c-6.097023 7.49634-15.192582 11.794241-24.787897 11.794241H219.44285c-6.69673 0-10.494876-7.796193-6.196974-13.093607l373.317716-458.875939c19.090678-23.488531 19.090678-57.172084 0-80.760566L214.145437 12.993655c-4.297901-5.197462-0.499756-12.993655 6.196974-12.993655h206.799024c9.595315 0 18.790825 4.297901 24.787896 11.794241l324.34163 399.105125c47.776672 58.771303 47.776672 142.93021 0 201.701512z",
      "p-id": "8566"
    })))), slides.thumnbail !== false && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "toms-image-slider-thumnbail"
    }, slides.galleryImages.map(function (slide, index) {
      if (index >= 0) {
        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
          className: "toms-image-slider-thumnbail-image"
        }, !!slide && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
          src: slide,
          className: index === current ? 'toms-image-slider-gellery-thumnbail-image toms-image-slider-gellery-thumnbail-image-active' : 'toms-image-slider-gellery-thumnbail-image',
          width: "200",
          height: "200",
          onClick: () => {
            setCurrentImg(slide), setCurrent(index);
          }
        }));
      }
    })), isOpen && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Modal, {
      className: "toms-image-slider-modal-block",
      overlayClassName: "toms-image-slider-modal-overlay",
      onRequestClose: closeModal,
      style: {
        backgroundImage: `url(${!!slideImg[modalCurrent] ? slideImg[modalCurrent] : ''})`
      }
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "toms-image-slider-modal-block"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "modal-left-arrow",
      onClick: modalPrevSlide
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Icon, {
      icon: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
        t: "1632461688518",
        className: "icon",
        viewBox: "0 0 1024 1024",
        version: "1.1",
        xmlns: "http://www.w3.org/2000/svg",
        "p-id": "8373",
        width: "64",
        height: "64"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
        d: "M804.157345 1024H596.658663c-9.595315 0-18.790825-4.297901-24.787897-11.794241L247.229283 612.600878c-47.776672-58.771303-47.776672-142.93021 0-201.701512L571.470961 11.794241c6.097023-7.49634 15.192582-11.794241 24.787897-11.794241h206.799024c6.69673 0 10.494876 7.796193 6.196974 12.993655L436.936652 471.369839c-19.090678 23.488531-19.090678 57.172084 0 80.760566l373.417667 458.775988c4.19795 5.297413 0.499756 13.093607-6.196974 13.093607z",
        "p-id": "8374"
      }))
    })), slides.thumnbail !== false && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "toms-image-slider-modal-show-thumnbail-icon",
      onClick: showThumnbail
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Icon, {
      icon: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
        t: "1632467128148",
        className: "thumnbail-icon",
        viewBox: "0 0 1024 1024",
        version: "1.1",
        xmlns: "http://www.w3.org/2000/svg",
        "p-id": "15012",
        width: "48",
        height: "48"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
        d: "M992 96H160a32 32 0 0 0-32 32v96H32a32 32 0 0 0-32 32v640a32 32 0 0 0 32 32h832a32 32 0 0 0 32-32v-96h96a32 32 0 0 0 32-32V128a32 32 0 0 0-32-32zM832 288v506.416l-171.168-146.72a32 32 0 0 0-37.296-3.136l-137.056 82.24-206.4-235.872a32.048 32.048 0 0 0-23.008-10.912 32.192 32.192 0 0 0-23.68 9.36L64 658.752V288h768zM64 749.248l190.432-190.432 201.488 230.272a31.952 31.952 0 0 0 40.544 6.352l140.224-84.128L814.832 864H64v-114.752zM960 736h-64V256a32 32 0 0 0-32-32H192v-64h768v576z",
        "p-id": "15013"
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
        d: "M656 576c61.744 0 112-50.256 112-112s-50.256-112-112-112-112 50.256-112 112 50.256 112 112 112z m0-160c26.464 0 48 21.536 48 48s-21.536 48-48 48-48-21.536-48-48 21.536-48 48-48z",
        "p-id": "15014"
      }))
    })), showState.shown === true && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "toms-image-slider-modal-thumnbail"
    }, slides.galleryImages.map(function (slide, index) {
      if (index >= 0) {
        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
          className: "toms-image-slider-modal-thumnbail-image"
        }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
          src: slide,
          className: index === modalCurrent ? 'toms-image-slider-gellery-modal-thumnbail-image toms-image-slider-gellery-modal-thumnbail-image-active' : 'toms-image-slider-gellery-modal-thumnbail-image',
          width: "200",
          height: "200",
          onClick: () => {
            setModalImg(slide), setModalCurrent(index);
          }
        }));
      }
    }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "modal-right-arrow",
      onClick: modalNextSlide
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Icon, {
      icon: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
        t: "1632461620854",
        className: "icon",
        viewBox: "0 0 1024 1024",
        version: "1.1",
        xmlns: "http://www.w3.org/2000/svg",
        "p-id": "8135",
        width: "64",
        height: "64"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
        d: "M776.270961 612.600878l-324.641483 399.604881c-6.097023 7.49634-15.192582 11.794241-24.787897 11.794241H219.44285c-6.69673 0-10.494876-7.796193-6.196974-13.093607l373.317716-458.875939c19.090678-23.488531 19.090678-57.172084 0-80.760566L214.145437 12.993655c-4.297901-5.197462-0.499756-12.993655 6.196974-12.993655h206.799024c9.595315 0 18.790825 4.297901 24.787896 11.794241l324.34163 399.105125c47.776672 58.771303 47.776672 142.93021 0 201.701512z",
        "p-id": "8136"
      }))
    }))))));
  } else {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "toms-image-slider-empty"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      src: defaultImageFromPHP,
      className: "toms-image-slider-gellery-image",
      width: "200",
      height: "200"
    }));
  }
}
})();

/******/ })()
;
//# sourceMappingURL=frontend.js.map