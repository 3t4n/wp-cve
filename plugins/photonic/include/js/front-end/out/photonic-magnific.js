/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "../../../../../node_modules/@babel/runtime/helpers/asyncToGenerator.js":
/*!******************************************************************************!*\
  !*** ../../../../../node_modules/@babel/runtime/helpers/asyncToGenerator.js ***!
  \******************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: module */
/*! CommonJS bailout: module.exports is used directly at 39:0-14 */
/***/ ((module) => {



function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) {
  try {
    var info = gen[key](arg);
    var value = info.value;
  } catch (error) {
    reject(error);
    return;
  }

  if (info.done) {
    resolve(value);
  } else {
    Promise.resolve(value).then(_next, _throw);
  }
}

function _asyncToGenerator(fn) {
  return function () {
    var self = this,
        args = arguments;
    return new Promise(function (resolve, reject) {
      var gen = fn.apply(self, args);

      function _next(value) {
        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value);
      }

      function _throw(err) {
        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err);
      }

      _next(undefined);
    });
  };
}

module.exports = _asyncToGenerator;

/***/ }),

/***/ "../../../../../node_modules/@babel/runtime/helpers/defineProperty.js":
/*!****************************************************************************!*\
  !*** ../../../../../node_modules/@babel/runtime/helpers/defineProperty.js ***!
  \****************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: module */
/*! CommonJS bailout: module.exports is used directly at 18:0-14 */
/***/ ((module) => {



function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

module.exports = _defineProperty;

/***/ }),

/***/ "../../../../../node_modules/@babel/runtime/helpers/interopRequireDefault.js":
/*!***********************************************************************************!*\
  !*** ../../../../../node_modules/@babel/runtime/helpers/interopRequireDefault.js ***!
  \***********************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: module */
/*! CommonJS bailout: module.exports is used directly at 9:0-14 */
/***/ ((module) => {



function _interopRequireDefault(obj) {
  return obj && obj.__esModule ? obj : {
    "default": obj
  };
}

module.exports = _interopRequireDefault;

/***/ }),

/***/ "../../../../../node_modules/@babel/runtime/helpers/interopRequireWildcard.js":
/*!************************************************************************************!*\
  !*** ../../../../../node_modules/@babel/runtime/helpers/interopRequireWildcard.js ***!
  \************************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: module, __webpack_require__ */
/*! CommonJS bailout: module.exports is used directly at 57:0-14 */
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {



var _typeof = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "../../../../../node_modules/@babel/runtime/helpers/typeof.js");

function _getRequireWildcardCache() {
  if (typeof WeakMap !== "function") return null;
  var cache = new WeakMap();

  _getRequireWildcardCache = function _getRequireWildcardCache() {
    return cache;
  };

  return cache;
}

function _interopRequireWildcard(obj) {
  if (obj && obj.__esModule) {
    return obj;
  }

  if (obj === null || _typeof(obj) !== "object" && typeof obj !== "function") {
    return {
      "default": obj
    };
  }

  var cache = _getRequireWildcardCache();

  if (cache && cache.has(obj)) {
    return cache.get(obj);
  }

  var newObj = {};
  var hasPropertyDescriptor = Object.defineProperty && Object.getOwnPropertyDescriptor;

  for (var key in obj) {
    if (Object.prototype.hasOwnProperty.call(obj, key)) {
      var desc = hasPropertyDescriptor ? Object.getOwnPropertyDescriptor(obj, key) : null;

      if (desc && (desc.get || desc.set)) {
        Object.defineProperty(newObj, key, desc);
      } else {
        newObj[key] = obj[key];
      }
    }
  }

  newObj["default"] = obj;

  if (cache) {
    cache.set(obj, newObj);
  }

  return newObj;
}

module.exports = _interopRequireWildcard;

/***/ }),

/***/ "../../../../../node_modules/@babel/runtime/helpers/typeof.js":
/*!********************************************************************!*\
  !*** ../../../../../node_modules/@babel/runtime/helpers/typeof.js ***!
  \********************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: module */
/*! CommonJS bailout: module.exports is used directly at 7:4-18 */
/*! CommonJS bailout: module.exports is used directly at 11:4-18 */
/*! CommonJS bailout: module.exports is used directly at 19:0-14 */
/***/ ((module) => {



function _typeof(obj) {
  "@babel/helpers - typeof";

  if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
    module.exports = _typeof = function _typeof(obj) {
      return typeof obj;
    };
  } else {
    module.exports = _typeof = function _typeof(obj) {
      return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
    };
  }

  return _typeof(obj);
}

module.exports = _typeof;

/***/ }),

/***/ "../include/js/front-end/src/Components/Modal.js":
/*!*******************************************************!*\
  !*** ../include/js/front-end/src/Components/Modal.js ***!
  \*******************************************************/
/*! flagged exports */
/*! export Modal [provided] [no usage info] [missing usage info prevents renaming] */
/*! export __esModule [provided] [no usage info] [missing usage info prevents renaming] */
/*! other exports [not provided] [no usage info] */
/*! runtime requirements: __webpack_exports__ */
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.Modal = void 0;

/*
 * Photonic Modal
 * Used as the overlay panel
 * License: MIT
 */
var Modal = function Modal(modal, options) {
  var body = document.body; //Defaults

  var settings = Object.assign({
    modalTarget: 'photonicModal',
    closeCSS: '',
    closeFromRight: 0,
    width: '80%',
    height: '100%',
    top: '0px',
    left: '0px',
    zIndexIn: '9999',
    zIndexOut: '-9999',
    color: '#39BEB9',
    opacityIn: '1',
    opacityOut: '0',
    animationDuration: '.6s',
    overflow: 'auto'
  }, options);
  var overlay = document.querySelector('.photonicModalOverlay'),
      scrollable = document.querySelector('.photonicModalOverlayScrollable');

  if (!overlay) {
    overlay = document.createElement('div');
    overlay.className = 'photonicModalOverlay';
    scrollable = document.createElement('div');
    scrollable.className = 'photonicModalOverlayScrollable';
    overlay.appendChild(scrollable);
    body.appendChild(overlay);
  }

  var closeIcon = modal.querySelector('.photonicModalClose');

  if (!closeIcon) {
    closeIcon = document.createElement('a');
    closeIcon.className = 'photonicModalClose ' + settings.closeCSS;
    closeIcon.style.right = settings.closeFromRight;
    closeIcon.innerHTML = '&times;';
    closeIcon.setAttribute('href', '#');
    modal.insertAdjacentElement('afterbegin', closeIcon);
  }

  closeIcon = modal.querySelector('.photonicModalClose');
  var id = document.querySelector('#' + settings.modalTarget); // Default Classes
  // id.addClass('photonicModal');
  // id.addClass(settings.modalTarget+'-off');
  //Init styles

  var initStyles = {
    'width': settings.width,
    'height': settings.height,
    'top': settings.top,
    'left': settings.left,
    'background-color': settings.color,
    'overflow-y': settings.overflow,
    'z-index': settings.zIndexOut,
    'opacity': settings.opacityOut,
    '-webkit-animation-duration': settings.animationDuration,
    '-moz-animation-duration': settings.animationDuration,
    '-ms-animation-duration': settings.animationDuration,
    'animation-duration': settings.animationDuration
  };

  if (id) {
    id.classList.add('photonicModal');
    id.classList.add(settings.modalTarget + '-off');
    var style = '';

    for (var [key, value] of Object.entries(initStyles)) {
      style += "".concat(key, ": ").concat(value, "; ");
    }

    id.style.cssText += style; // initStyles.reduce((a, v, i) => a + i + ': ' + v + ';');

    open(id);
  }

  closeIcon.addEventListener('click', function (event) {
    event.preventDefault();
    document.documentElement.style.overflow = 'auto';
    document.body.style.overflow = 'auto';

    if (id.classList.contains(settings.modalTarget + '-on')) {
      id.classList.remove(settings.modalTarget + '-on');
      id.classList.add(settings.modalTarget + '-off');
    }

    if (id.classList.contains(settings.modalTarget + '-off')) {
      id.addEventListener('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', afterClose, {
        once: true
      });
    }

    id.style.overflowY = 'hidden';
    slideUp(id); // Util.slideUpDown(id.closest('.photonicModalOverlayScrollable'), 'hide');

    overlay.style.overflowY = 'hidden'; // Util.fadeOut(overlay);

    overlay.style.display = 'none';
  });

  function slideDown(element) {
    element.style.height = 'auto';
    element.style.height = "".concat(element.scrollHeight, "px");
    element.style.height = 'auto';
  }

  var slideUp = element => {
    element.style.height = 0;
    element.style.display = 'none';
  };

  function open(el) {
    document.documentElement.style.overflow = 'hidden';
    document.body.style.overflow = 'hidden';

    if (el.classList.contains(settings.modalTarget + '-off')) {
      el.classList.remove(settings.modalTarget + '-off');
      el.classList.add(settings.modalTarget + '-on');
    }

    if (el.classList.contains(settings.modalTarget + '-on')) {
      el.style.opacity = settings.opacityIn;
      el.style.zIndex = settings.zIndexIn;
    }

    overlay.style.overflowY = settings.overflow;
    overlay.style.display = 'block'; // Util.fadeIn(overlay);

    scrollable.appendChild(el);
    el.style.display = 'block';
    el.style.overflowY = settings.overflow;
    slideDown(scrollable); // Util.slideUpDown(scrollable, 'show');
  }

  function afterClose() {
    id.style.zIndex = settings.zIndexOut;
  }
};

exports.Modal = Modal;

/***/ }),

/***/ "../include/js/front-end/src/Components/Prompter.js":
/*!**********************************************************!*\
  !*** ../include/js/front-end/src/Components/Prompter.js ***!
  \**********************************************************/
/*! flagged exports */
/*! export Prompter [provided] [no usage info] [missing usage info prevents renaming] */
/*! export __esModule [provided] [no usage info] [missing usage info prevents renaming] */
/*! other exports [not provided] [no usage info] */
/*! runtime requirements: __webpack_exports__ */
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.Prompter = void 0;

/**
 * Photonic Prompter
 * Used for interactive dialogs, e.g. the Photonic password prompter.
 *
 * GPL v3.0
 */

/*
 * var myPrompter = Prompter('htmlID');
 *
 * id: The HTML id of the object
 */
class Prompter {
  constructor(id) {
    this.events = {
      onShow: new Event('onShow'),
      onConfirm: new Event('onConfirm'),
      onHide: new Event('onHide')
    };
    this.modal = document.getElementById(id);
    this.classClose = '.close';
    this.classCancel = '.cancel';
    this.classConfirm = '.confirm';
    this.btnsOpen = [];
  }
  /*
   * Prompter.show() :
   *
   * Shows the modal
   */


  show() {
    this.modal.dispatchEvent(this.events.onShow);
    this.modal.style.display = "block";
    return this;
  }
  /* Prompter.hide() :
   *
   * Hides the modal
   */


  hide() {
    this.modal.dispatchEvent(this.events.onHide);
    this.modal.style.display = "none";
    return this;
  }
  /*
  * Prompter.removeEvents() :
  *
  * Removes the events (by cloning the modal)
  */


  removeEvents() {
    var clone = this.modal.cloneNode(true);
    this.modal.parentNode.replaceChild(clone, this.modal);
    this.modal = clone;
    return this;
  }
  /*
   * Prompter.on(event, callback):
   *
   * Connect an event.
   *
   * event:
   *     - 'onShow': Called when the modal is shown (via Prompter.show() or a bound button)
   *     - 'onConfirm': Called when the modal when the user sends the data (via the element with the class '.confirm')
   *     - 'onHide': Called when the modal is hidden (via Prompter.hide() or a bound button)
   * callback: The function to call on the event
   *
   */


  on(event, callback) {
    this.modal.addEventListener(event, callback);
    return this;
  }
  /*
  * Prompter.attach() :
  *
  * Attaches the click events on the elements with classes ".confirm", ".hide", ".cancel" plus the elements to show the modal
  */


  attach() {
    var i;
    var items = [];
    var self = this;
    items = this.modal.querySelectorAll(self.classClose);

    for (i = items.length - 1; i >= 0; i--) {
      items[i].addEventListener('click', function () {
        self.hide();
      });
    }

    items = self.modal.querySelectorAll(self.classCancel);

    for (i = items.length - 1; i >= 0; i--) {
      items[i].addEventListener('click', function () {
        self.hide();
      });
    }

    items = self.modal.querySelectorAll(self.classConfirm);

    for (i = items.length - 1; i >= 0; i--) {
      items[i].addEventListener('click', function () {
        self.modal.dispatchEvent(self.events.onConfirm);
        self.hide();
      });
    }

    for (i = self.btnsOpen.length - 1; i >= 0; i--) {
      self.btnsOpen[i].addEventListener('click', function () {
        self.show();
      });
    }

    return self;
  }
  /*
   * Attach an external element that will open the modal.
   * Prompter.addOpenBtn(element)
   *
   * element: Any HTML element a button, div, span,...
   */


  addOpenBtn(element) {
    this.btnsOpen.push(element);
  }

}

exports.Prompter = Prompter;

/***/ }),

/***/ "../include/js/front-end/src/Components/Tooltip.js":
/*!*********************************************************!*\
  !*** ../include/js/front-end/src/Components/Tooltip.js ***!
  \*********************************************************/
/*! flagged exports */
/*! export Tooltip [provided] [no usage info] [missing usage info prevents renaming] */
/*! export __esModule [provided] [no usage info] [missing usage info prevents renaming] */
/*! other exports [not provided] [no usage info] */
/*! runtime requirements: __webpack_exports__ */
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.Tooltip = void 0;

/*
 * Photonic Tooltip
 * License: MIT
 */
var Tooltip = function Tooltip(selector, tooltip_element) {
  var tooltip, tooltipClass, elemEdges, tooltipElems;

  function create(tooltip, elm) {
    var tooltipText = elm.getAttribute('data-photonic-tooltip');

    if (tooltipText !== '') {
      elm.setAttribute('title', ''); // Blank out the regular title
      // elemEdges relative to the viewport.

      elemEdges = elm.getBoundingClientRect();
      var tooltipTextNode = document.createTextNode(tooltipText);
      tooltip.innerHTML = ''; // Reset, or upon refresh the node gets repeated

      tooltip.appendChild(tooltipTextNode); // Remove no-display + set the correct classname based on the position
      // of the elm.

      if (elemEdges.left > window.innerWidth - 100) {
        tooltip.className = 'photonic-tooltip-container tooltip-left';
      } else if (elemEdges.left + elemEdges.width / 2 < 100) {
        tooltip.className = 'photonic-tooltip-container tooltip-right';
      } else {
        tooltip.className = 'photonic-tooltip-container tooltip-center';
      }
    }
  }

  function position(tooltip, elm) {
    var tooltipText = elm.getAttribute('data-photonic-tooltip');

    if (tooltipText !== '') {
      if (elemEdges === undefined) {
        elemEdges = elm.getBoundingClientRect();
      } // 10 = arrow height


      var elm_top = elemEdges.top + elemEdges.height + window.scrollY;
      var viewport_edges = window.innerWidth - 100; // Position tooltip on the left side of the elm if the elm touches
      // the viewports right edge and elm width is < 50px.

      if (elemEdges.left + window.scrollX > viewport_edges && elemEdges.width < 50) {
        tooltip.style.left = elemEdges.left + window.scrollX - (tooltip.offsetWidth + elemEdges.width) + 'px';
        tooltip.style.top = elm.offsetTop + 'px'; // Position tooltip on the left side of the elm if the elm touches
        // the viewports right edge and elm width is > 50px.
      } else if (elemEdges.left + window.scrollX > viewport_edges && elemEdges.width > 50) {
        tooltip.style.left = elemEdges.left + window.scrollX - tooltip.offsetWidth - 20 + 'px';
        tooltip.style.top = elm.offsetTop + 'px';
      } else if (elemEdges.left + window.scrollX + elemEdges.width / 2 < 100) {
        // position tooltip on the right side of the elm.
        tooltip.style.left = elemEdges.left + window.scrollX + elemEdges.width + 20 + 'px';
        tooltip.style.top = elm.offsetTop + 'px';
      } else {
        // Position the toolbox in the center of the elm.
        var centered = elemEdges.left + window.scrollX + elemEdges.width / 2 - tooltip.offsetWidth / 2;
        tooltip.style.left = centered + 'px';
        tooltip.style.top = elm_top + 'px';
      }
    }
  }

  function show(evt) {
    create(tooltip, evt.currentTarget);
    position(tooltip, evt.currentTarget);
  }

  function hide(evt) {
    tooltip.className = tooltipClass + ' no-display';

    if (tooltip.innerText !== '') {
      tooltip.removeChild(tooltip.firstChild);
      tooltip.removeAttribute('style');
      var element = evt.currentTarget;
      element.setAttribute('title', element.getAttribute('data-photonic-tooltip'));
    }
  }

  function init() {
    tooltipElems = document.documentElement.querySelectorAll(selector);
    tooltip = document.documentElement.querySelector(tooltip_element);
    tooltipClass = tooltip_element.replace(/^\.+/g, '');

    if (tooltip === null || tooltip.length === 0) {
      tooltip = document.createElement('div');
      tooltip.className = tooltipClass + ' no-display';
      document.body.appendChild(tooltip);
    }

    tooltipElems.forEach(elm => {
      elm.removeEventListener('mouseenter', show);
      elm.removeEventListener('mouseleave', hide);
      elm.addEventListener('mouseenter', show, false);
      elm.addEventListener('mouseleave', hide, false);
    });
  }

  init();
};

exports.Tooltip = Tooltip;

/***/ }),

/***/ "../include/js/front-end/src/Core.js":
/*!*******************************************!*\
  !*** ../include/js/front-end/src/Core.js ***!
  \*******************************************/
/*! flagged exports */
/*! export Core [provided] [no usage info] [missing usage info prevents renaming] */
/*! export __esModule [provided] [no usage info] [missing usage info prevents renaming] */
/*! other exports [not provided] [no usage info] */
/*! runtime requirements: __webpack_exports__, __webpack_require__ */
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireWildcard = __webpack_require__(/*! @babel/runtime/helpers/interopRequireWildcard */ "../../../../../node_modules/@babel/runtime/helpers/interopRequireWildcard.js");

var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../../../../../node_modules/@babel/runtime/helpers/interopRequireDefault.js");

Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.Core = void 0;

var _asyncToGenerator2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "../../../../../node_modules/@babel/runtime/helpers/asyncToGenerator.js"));

var _defineProperty2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "../../../../../node_modules/@babel/runtime/helpers/defineProperty.js"));

var Util = _interopRequireWildcard(__webpack_require__(/*! ./Util */ "../include/js/front-end/src/Util.js"));

var _Tooltip = __webpack_require__(/*! ./Components/Tooltip */ "../include/js/front-end/src/Components/Tooltip.js");

var _Prompter = __webpack_require__(/*! ./Components/Prompter */ "../include/js/front-end/src/Components/Prompter.js");

class Core {}

exports.Core = Core;
(0, _defineProperty2.default)(Core, "lightboxList", []);
(0, _defineProperty2.default)(Core, "prompterList", []);
(0, _defineProperty2.default)(Core, "lightbox", void 0);
(0, _defineProperty2.default)(Core, "deep", location.hash);
(0, _defineProperty2.default)(Core, "setLightbox", lb => Core.lightbox = lb);
(0, _defineProperty2.default)(Core, "getLightbox", () => Core.lightbox);
(0, _defineProperty2.default)(Core, "setDeep", d => Core.deep = d);
(0, _defineProperty2.default)(Core, "getDeep", () => Core.deep);
(0, _defineProperty2.default)(Core, "addToLightboxList", (idx, lightbox) => Core.lightboxList[idx] = lightbox);
(0, _defineProperty2.default)(Core, "getLightboxList", () => Core.lightboxList);
(0, _defineProperty2.default)(Core, "showSpinner", () => {
  var loading = document.getElementsByClassName('photonic-loading');

  if (loading.length > 0) {
    loading = loading[0];
  } else {
    loading = document.createElement('div');
    loading.className = 'photonic-loading';
  }

  loading.style.display = 'block';
  document.body.appendChild(loading);
});
(0, _defineProperty2.default)(Core, "hideLoading", () => {
  var loading = document.getElementsByClassName('photonic-loading');

  if (loading.length > 0) {
    loading = loading[0];
    loading.style.display = 'none';
  }
});
(0, _defineProperty2.default)(Core, "initializePasswordPrompter", selector => {
  var selectorNoHash = selector.replace(/^#+/g, '');
  var prompter = new _Prompter.Prompter(selectorNoHash);
  prompter.attach();
  Core.prompterList[selector] = prompter;
  prompter.show();
});
(0, _defineProperty2.default)(Core, "moveHTML5External", () => {
  var videos = document.getElementById('photonic-html5-external-videos');

  if (!videos) {
    videos = document.createElement('div');
    videos.id = 'photonic-html5-external-videos';
    videos.style.display = 'none';
    document.body.appendChild(videos);
  }

  var current = document.querySelectorAll('.photonic-html5-external');

  if (current) {
    var cLen = current.length;

    for (var c = 0; c < cLen; c++) {
      current[c].classList.remove('photonic-html5-external');
      videos.appendChild(current[c]);
    }
  }
});
(0, _defineProperty2.default)(Core, "blankSlideupTitle", () => {
  document.querySelectorAll('.title-display-slideup-stick, .photonic-slideshow.title-display-slideup-stick').forEach(item => {
    Array.from(item.getElementsByTagName('a')).forEach(a => {
      a.setAttribute('title', '');
    });
  });
});
(0, _defineProperty2.default)(Core, "showSlideupTitle", () => {
  var titles = document.documentElement.querySelectorAll('.title-display-slideup-stick a .photonic-title');
  var len = titles.length;

  for (var i = 0; i < len; i++) {
    titles[i].style.display = 'block';
  }
});
(0, _defineProperty2.default)(Core, "waitForImages", /*#__PURE__*/function () {
  var _ref = (0, _asyncToGenerator2.default)(function* (selector) {
    var images = Core.getImagesFromSelector(selector);
    var imageUrlArray = [];
    var anchorArray = [];
    var setDimensions = false;

    if (selector instanceof Element && selector.getAttribute('data-photonic-platform') === 'instagram') {
      setDimensions = true;
    }

    images.forEach(img => {
      imageUrlArray.push(img.getAttribute('src'));
      anchorArray.push(img.parentElement);
    });
    var promiseArray = []; // create an array for promises

    var imageArray = []; // array for the images

    var _loop = function _loop(idx, imageUrl) {
      promiseArray.push(new Promise(resolve => {
        var img = new Image();

        img.onload = () => {
          if (setDimensions) {
            anchorArray[idx].setAttribute('data-pswp-width', img.naturalWidth);
            anchorArray[idx].setAttribute('data-pswp-height', img.naturalHeight);
          }

          resolve();
        };

        img.src = imageUrl;
        imageArray.push(img);
      }));
    };

    for (var [idx, imageUrl] of imageUrlArray.entries()) {
      _loop(idx, imageUrl);
    }

    yield Promise.all(promiseArray); // wait for all the images to be loaded

    return imageArray;
  });

  return function (_x) {
    return _ref.apply(this, arguments);
  };
}());
(0, _defineProperty2.default)(Core, "loadSingleImage", /*#__PURE__*/function () {
  var _ref2 = (0, _asyncToGenerator2.default)(function* (image) {
    var promiseArray = [];
    var retImage = null;
    promiseArray.push(new Promise(resolve => {
      var img = new Image();

      img.onload = () => {
        resolve();
      };

      if (image.getAttribute('data-src') !== null) {
        img.src = image.getAttribute('data-src');
      } else {
        img.src = image.getAttribute('src'); // Leave the 'src' in just as a backup. E.g. Gallery header images
      }

      retImage = img;
    }));
    yield Promise.all(promiseArray);
    return retImage;
  });

  return function (_x2) {
    return _ref2.apply(this, arguments);
  };
}());
(0, _defineProperty2.default)(Core, "watchForImages", selector => {
  var images = Core.getImagesFromSelector(selector);
  var intersectionObserver = new IntersectionObserver((items, observer) => {
    items.forEach(item => {
      if (item.isIntersecting || item.intersectionRatio > 0) {
        var image = item.target;
        image.closest('a').classList.add('photonic-image-loading');
        Core.loadSingleImage(image).then(() => {
          if (image.getAttribute('data-src') !== null && image.getAttribute('data-src') !== '') {
            image.src = image.getAttribute('data-src');
            image.setAttribute('data-src', '');
            image.setAttribute('data-loaded', 'true');
          }

          image.closest('a').classList.remove('photonic-image-loading');
          Util.fadeIn(image);
        });
        observer.unobserve(image);
      }
    });
  });
  images.forEach(image => {
    intersectionObserver.observe(image);
  });
});
(0, _defineProperty2.default)(Core, "getImagesFromSelector", selector => {
  var images = [];

  if (typeof selector === 'string') {
    document.querySelectorAll(selector).forEach(selection => {
      images = Array.from(selection.getElementsByTagName('img'));
    });
  } else if (selector instanceof Element) {
    images = Array.from(selector.getElementsByTagName('img'));
  } else if (selector instanceof NodeList) {
    selector.forEach(selection => {
      images.push(selection.querySelector('img'));
    });
  }

  return images;
});
(0, _defineProperty2.default)(Core, "standardizeTitleWidths", () => {
  var self = Core;

  var setWidths = grid => {
    grid.querySelectorAll('.photonic-thumb').forEach(item => {
      var img = item.getElementsByTagName('img');

      if (img != null) {
        img = img[0];
        var title = item.querySelector('.photonic-title-info');

        if (title) {
          title.style.width = img.width + 'px';
        }
      }
    });
  };

  document.querySelectorAll('.photonic-standard-layout.title-display-below, .photonic-standard-layout.title-display-hover-slideup-show, .photonic-standard-layout.title-display-slideup-stick').forEach(grid => {
    if (grid.classList.contains('sizes-present')) {
      self.watchForImages(grid);
      setWidths(grid);
    } else {
      self.waitForImages(grid).then(() => {
        setWidths(grid);
      });
    }
  });
});
(0, _defineProperty2.default)(Core, "sanitizeTitles", () => {
  var thumbs = document.querySelectorAll('.photonic-stream a, a.photonic-level-2-thumb');
  thumbs.forEach(thumb => {
    if (!thumb.parentNode.classList.contains('photonic-header-title')) {
      var title = thumb.getAttribute('title');
      thumb.setAttribute('title', Util.getText(title));
    }
  });
});
(0, _defineProperty2.default)(Core, "initializeTooltips", () => {
  if (document.querySelector('.title-display-tooltip a, .photonic-slideshow.title-display-tooltip img') != null) {
    (0, _Tooltip.Tooltip)('[data-photonic-tooltip]', '.photonic-tooltip-container');
  }
});
(0, _defineProperty2.default)(Core, "showRegularGrids", () => {
  document.querySelectorAll('.photonic-standard-layout').forEach(grid => {
    if (grid.classList.contains('sizes-present')) {
      Core.watchForImages(grid);
    } else {
      Core.waitForImages(grid).then(() => {
        grid.querySelectorAll('.photonic-level-1, .photonic-level-2').forEach(item => {
          item.style.display = 'inline-block';
        });
      });
    }
  });
});
(0, _defineProperty2.default)(Core, "executeCommon", () => {
  Core.moveHTML5External();
  Core.blankSlideupTitle();
  Core.standardizeTitleWidths();
  Core.sanitizeTitles();
  Core.initializeTooltips();
  Core.showRegularGrids();
});

/***/ }),

/***/ "../include/js/front-end/src/Layouts/Justified.js":
/*!********************************************************!*\
  !*** ../include/js/front-end/src/Layouts/Justified.js ***!
  \********************************************************/
/*! flagged exports */
/*! export JustifiedGrid [provided] [no usage info] [missing usage info prevents renaming] */
/*! export __esModule [provided] [no usage info] [missing usage info prevents renaming] */
/*! other exports [not provided] [no usage info] */
/*! runtime requirements: __webpack_exports__, __webpack_require__ */
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireWildcard = __webpack_require__(/*! @babel/runtime/helpers/interopRequireWildcard */ "../../../../../node_modules/@babel/runtime/helpers/interopRequireWildcard.js");

Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.JustifiedGrid = void 0;

var _Core = __webpack_require__(/*! ../Core.js */ "../include/js/front-end/src/Core.js");

var Util = _interopRequireWildcard(__webpack_require__(/*! ../Util.js */ "../include/js/front-end/src/Util.js"));

/**
 * Photonic Justified Grid Layout (JS-based)
 * Photonic can render a justified grid using CSS or JS. The CSS layout is the default. The JS one is used when the sizes of the images are not available upfront.
 * The CSS layout renders quicker, but the JS layouts are a lot prettier.
 *
 * License: GPL v3.0
 */
var linearMin = arr => {
  var computed, result, x, _i, _len;

  for (_i = 0, _len = arr.length; _i < _len; _i++) {
    x = arr[_i];
    computed = x[0];

    if (!result || computed < result.computed) {
      result = {
        value: x,
        computed: computed
      };
    }
  }

  return result.value;
};

var linearPartition = (seq, k) => {
  var ans, i, j, m, n, solution, table, x, y, _i, _j, _k, _l;

  n = seq.length;

  if (k <= 0) {
    return [];
  }

  if (k > n) {
    return seq.map(x => [x]);
  }

  table = (() => {
    var _i, _results;

    _results = [];

    for (y = _i = 0; 0 <= n ? _i < n : _i > n; y = 0 <= n ? ++_i : --_i) {
      _results.push(function () {
        var _j, _results1;

        _results1 = [];

        for (x = _j = 0; 0 <= k ? _j < k : _j > k; x = 0 <= k ? ++_j : --_j) {
          _results1.push(0);
        }

        return _results1;
      }());
    }

    return _results;
  })();

  solution = (() => {
    var _i, _ref, _results;

    _results = [];

    for (y = _i = 0, _ref = n - 1; 0 <= _ref ? _i < _ref : _i > _ref; y = 0 <= _ref ? ++_i : --_i) {
      _results.push(function () {
        var _j, _ref1, _results1;

        _results1 = [];

        for (x = _j = 0, _ref1 = k - 1; 0 <= _ref1 ? _j < _ref1 : _j > _ref1; x = 0 <= _ref1 ? ++_j : --_j) {
          _results1.push(0);
        }

        return _results1;
      }());
    }

    return _results;
  })();

  for (i = _i = 0; 0 <= n ? _i < n : _i > n; i = 0 <= n ? ++_i : --_i) {
    table[i][0] = seq[i] + (i ? table[i - 1][0] : 0);
  }

  for (j = _j = 0; 0 <= k ? _j < k : _j > k; j = 0 <= k ? ++_j : --_j) {
    table[0][j] = seq[0];
  }

  for (i = _k = 1; 1 <= n ? _k < n : _k > n; i = 1 <= n ? ++_k : --_k) {
    for (j = _l = 1; 1 <= k ? _l < k : _l > k; j = 1 <= k ? ++_l : --_l) {
      m = linearMin((() => {
        var _m, _results;

        _results = [];

        for (x = _m = 0; 0 <= i ? _m < i : _m > i; x = 0 <= i ? ++_m : --_m) {
          _results.push([Math.max(table[x][j - 1], table[i][0] - table[x][0]), x]);
        }

        return _results;
      })());
      table[i][j] = m[0];
      solution[i - 1][j - 1] = m[1];
    }
  }

  n = n - 1;
  k = k - 2;
  ans = [];

  while (k >= 0) {
    ans = [(() => {
      var _m, _ref, _ref1, _results;

      _results = [];

      for (i = _m = _ref = solution[n - 1][k] + 1, _ref1 = n + 1; _ref <= _ref1 ? _m < _ref1 : _m > _ref1; i = _ref <= _ref1 ? ++_m : --_m) {
        _results.push(seq[i]);
      }

      return _results;
    })()].concat(ans);
    n = solution[n - 1][k];
    k = k - 1;
  }

  return [(() => {
    var _m, _ref, _results;

    _results = [];

    for (i = _m = 0, _ref = n + 1; 0 <= _ref ? _m < _ref : _m > _ref; i = 0 <= _ref ? ++_m : --_m) {
      _results.push(seq[i]);
    }

    return _results;
  })()].concat(ans);
};

function part(seq, k) {
  if (k <= 0) {
    return [];
  }

  while (k) {
    try {
      return linearPartition(seq, k--);
    } catch (_error) {//
    }
  }
}

var JustifiedGrid = (resized, jsLoaded, selector, lightbox) => {
  if (console !== undefined && Photonic_JS.debug_on !== '0' && Photonic_JS.debug_on !== '') console.time('Justified Grid');
  var selection = document.querySelectorAll(selector);

  if (selector == null || selection.length === 0) {
    selection = document.querySelectorAll('.photonic-random-layout');
  }

  if (!resized && selection.length > 0) {
    _Core.Core.showSpinner();
  }

  var setupTitles = () => {
    _Core.Core.blankSlideupTitle();

    _Core.Core.showSlideupTitle();

    if (!resized && !jsLoaded) {
      _Core.Core.hideLoading();
    }
  };

  selection.forEach(container => {
    // If there are some nodes for which the sizes are missing, play safe and run this in JS mode.
    // Otherwise render the gallery using CSS, and just display the images once they have downloaded.
    if (container.classList.contains('sizes-missing') || !window.CSS || !CSS.supports('color', 'var(--fake-var)')) {
      var viewportWidth = Math.floor(container.getBoundingClientRect().width),
          windowHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0),
          idealHeight = Math.max(parseInt(windowHeight / 4), Photonic_JS.tile_min_height);
      var gap = Photonic_JS.tile_spacing * 2;

      _Core.Core.waitForImages(container).then(() => {
        var photos = [],
            images = Array.from(container.getElementsByTagName('img'));
        images.forEach(image => {
          if (image.closest('.photonic-panel') !== null) {
            return;
          }

          var div = image.parentNode.parentNode;

          if (!(image.naturalHeight === 0 || image.naturalHeight === undefined || image.naturalWidth === undefined)) {
            photos.push({
              tile: div,
              aspect_ratio: image.naturalWidth / image.naturalHeight
            });
          }
        });
        var summedWidth = photos.reduce((sum, p) => sum += p.aspect_ratio * idealHeight + gap, 0);
        var rows = Math.max(Math.round(summedWidth / viewportWidth), 1),
            // At least 1 row should be shown
        weights = photos.map(p => Math.round(p.aspect_ratio * 100));
        var partition = part(weights, rows);
        var index = 0;
        var oLen = partition.length;

        for (var o = 0; o < oLen; o++) {
          var onePart = partition[o];
          var summedRatios = void 0;
          var rowBuffer = photos.slice(index, index + onePart.length);
          index = index + onePart.length;
          summedRatios = rowBuffer.reduce((sum, p) => sum += p.aspect_ratio, 0);
          var rLen = rowBuffer.length;

          for (var r = 0; r < rLen; r++) {
            var item = rowBuffer[r],
                existing = item.tile;
            existing.style.width = parseInt(viewportWidth / summedRatios * item.aspect_ratio) + "px";
            existing.style.height = parseInt(viewportWidth / summedRatios) + "px";
          }
        }

        container.querySelectorAll('.photonic-thumb, .photonic-thumb img').forEach(thumb => Util.fadeIn(thumb));
        setupTitles();
      });
    } else {
      _Core.Core.watchForImages(container);

      setupTitles();
    }

    if (lightbox && !resized) {
      if (Photonic_JS.lightbox_library === 'lightcase') {
        lightbox.initialize('.photonic-random-layout');
      } else if (['bigpicture', 'featherlight', 'glightbox'].indexOf(Photonic_JS.lightbox_library) > -1) {
        lightbox.initialize(container);
      } else if (Photonic_JS.lightbox_library === 'fancybox3') {
        lightbox.initialize('.photonic-random-layout');
      } else if (Photonic_JS.lightbox_library === 'photoswipe') {
        lightbox.initialize();
      }
    }
  });
  if (console !== undefined && Photonic_JS.debug_on !== '0' && Photonic_JS.debug_on !== '') console.timeEnd('Justified Grid');
};

exports.JustifiedGrid = JustifiedGrid;

/***/ }),

/***/ "../include/js/front-end/src/Layouts/Layout.js":
/*!*****************************************************!*\
  !*** ../include/js/front-end/src/Layouts/Layout.js ***!
  \*****************************************************/
/*! flagged exports */
/*! export __esModule [provided] [no usage info] [missing usage info prevents renaming] */
/*! export initializeLayouts [provided] [no usage info] [missing usage info prevents renaming] */
/*! other exports [not provided] [no usage info] */
/*! runtime requirements: __webpack_exports__, __webpack_require__ */
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireWildcard = __webpack_require__(/*! @babel/runtime/helpers/interopRequireWildcard */ "../../../../../node_modules/@babel/runtime/helpers/interopRequireWildcard.js");

Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.initializeLayouts = void 0;

var _Justified = __webpack_require__(/*! ./Justified */ "../include/js/front-end/src/Layouts/Justified.js");

var _Mosaic = __webpack_require__(/*! ./Mosaic */ "../include/js/front-end/src/Layouts/Mosaic.js");

var _Masonry = __webpack_require__(/*! ./Masonry */ "../include/js/front-end/src/Layouts/Masonry.js");

var Slider = _interopRequireWildcard(__webpack_require__(/*! ./Slider */ "../include/js/front-end/src/Layouts/Slider.js"));

var initializeLayouts = lightbox => {
  (0, _Justified.JustifiedGrid)(false, false, null, lightbox);
  (0, _Mosaic.Mosaic)(false, false);
  (0, _Masonry.Masonry)(false, false);
  Slider.initializeSliders();
  window.addEventListener('resize', () => {
    (0, _Justified.JustifiedGrid)(true, false, '.photonic-random-layout.sizes-missing');
    (0, _Mosaic.Mosaic)(true, false);
    (0, _Masonry.Masonry)(true, false);
  });
};

exports.initializeLayouts = initializeLayouts;

/***/ }),

/***/ "../include/js/front-end/src/Layouts/Masonry.js":
/*!******************************************************!*\
  !*** ../include/js/front-end/src/Layouts/Masonry.js ***!
  \******************************************************/
/*! flagged exports */
/*! export Masonry [provided] [no usage info] [missing usage info prevents renaming] */
/*! export __esModule [provided] [no usage info] [missing usage info prevents renaming] */
/*! other exports [not provided] [no usage info] */
/*! runtime requirements: __webpack_exports__, __webpack_require__ */
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireWildcard = __webpack_require__(/*! @babel/runtime/helpers/interopRequireWildcard */ "../../../../../node_modules/@babel/runtime/helpers/interopRequireWildcard.js");

Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.Masonry = void 0;

var _Core = __webpack_require__(/*! ../Core */ "../include/js/front-end/src/Core.js");

var Util = _interopRequireWildcard(__webpack_require__(/*! ../Util */ "../include/js/front-end/src/Util.js"));

/**
 * Photonic Masonry Layout
 * The Masonry layout is primarily controlled using CSS columns. The JS component is to facilitate responsive behaviour and breakpoints.
 *
 * License: GPL v3.0
 */
var Masonry = (resized, jsLoaded, selector) => {
  if (console !== undefined && Photonic_JS.debug_on !== '0' && Photonic_JS.debug_on !== '') console.time('Masonry');
  var selection = document.querySelectorAll(selector);

  if (selector == null || selection.length === 0) {
    selection = document.querySelectorAll('.photonic-masonry-layout');
  }

  if (!resized && selection.length > 0) {
    _Core.Core.showSpinner();
  }

  var minWidth = isNaN(Photonic_JS.masonry_min_width) || parseInt(Photonic_JS.masonry_min_width) <= 0 ? 200 : Photonic_JS.masonry_min_width;
  minWidth = parseInt(minWidth);
  selection.forEach(grid => {
    var columns = grid.getAttribute('data-photonic-gallery-columns');
    columns = isNaN(parseInt(columns)) || parseInt(columns) <= 0 ? 3 : parseInt(columns);

    var buildLayout = grid => {
      var viewportWidth = Math.floor(grid.getBoundingClientRect().width),
          idealColumns = viewportWidth / columns > minWidth ? columns : Math.floor(viewportWidth / minWidth);

      if (idealColumns !== undefined && idealColumns !== null) {
        grid.style.columnCount = idealColumns.toString();
      }

      Array.from(grid.getElementsByTagName('img')).forEach(img => {
        Util.fadeIn(img);
      });

      _Core.Core.showSlideupTitle();

      if (!resized && !jsLoaded) {
        _Core.Core.hideLoading();
      }
    };

    if (grid.classList.contains('sizes-present')) {
      _Core.Core.watchForImages(grid);

      buildLayout(grid);
    } else {
      _Core.Core.waitForImages(grid).then(() => {
        buildLayout(grid);
      });
    }
  });
  if (console !== undefined && Photonic_JS.debug_on !== '0' && Photonic_JS.debug_on !== '') console.timeEnd('Masonry');
};

exports.Masonry = Masonry;

/***/ }),

/***/ "../include/js/front-end/src/Layouts/Mosaic.js":
/*!*****************************************************!*\
  !*** ../include/js/front-end/src/Layouts/Mosaic.js ***!
  \*****************************************************/
/*! flagged exports */
/*! export Mosaic [provided] [no usage info] [missing usage info prevents renaming] */
/*! export __esModule [provided] [no usage info] [missing usage info prevents renaming] */
/*! other exports [not provided] [no usage info] */
/*! runtime requirements: __webpack_exports__, __webpack_require__ */
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireWildcard = __webpack_require__(/*! @babel/runtime/helpers/interopRequireWildcard */ "../../../../../node_modules/@babel/runtime/helpers/interopRequireWildcard.js");

Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.Mosaic = void 0;

var _Core = __webpack_require__(/*! ../Core */ "../include/js/front-end/src/Core.js");

var Util = _interopRequireWildcard(__webpack_require__(/*! ../Util */ "../include/js/front-end/src/Util.js"));

/**
 * Photonic Mosaic Layout
 * Arranges photos similar to JetPack tiles, but without any of the limitations of JetPack tiles. JS-based, responsive, and images are not affected by resolution issues.
 *
 * License: GPL V3.0
 */
var getDistribution = (setSize, max, min) => {
  var distribution = [];
  var processed = 0;

  while (processed < setSize) {
    if (setSize - processed <= max && processed > 0) {
      distribution.push(setSize - processed);
      processed += setSize - processed;
    } else {
      var current = Math.max(Math.floor(Math.random() * max + 1), min);
      current = Math.min(current, setSize - processed);
      distribution.push(current);
      processed += current;
    }
  }

  return distribution;
};

var arrayAlternate = (array, remainder) => array.filter((value, index) => index % 2 === remainder);

var setUniformHeightsForRow = array => {
  // First, order the array by increasing height
  array.sort((a, b) => a.height - b.height);
  array[0].new_height = array[0].height;
  array[0].new_width = array[0].width;

  for (var i = 1; i < array.length; i++) {
    array[i].new_height = array[0].height;
    array[i].new_width = array[i].new_height * array[i].aspect_ratio;
  }

  var new_width = array.reduce((sum, p) => sum += p.new_width, 0);
  return {
    elements: array,
    height: array[0].new_height,
    width: new_width,
    aspect_ratio: new_width / array[0].new_height
  };
};

var finalizeTiledLayout = (components, containers) => {
  var cLength = components.length;

  for (var c = 0; c < cLength; c++) {
    var component = components[c];
    var rowY = component.y,
        otherRowHeight = 0,
        container = void 0;
    var ceLen = component.elements.length;

    for (var e = 0; e < ceLen; e++) {
      var element = component.elements[e];

      if (element.photo_position !== undefined) {
        // Component is a single image
        container = containers[element.photo_position];
        container.style.width = component.new_width + 'px';
        container.style.height = component.new_height + 'px';
        container.style.top = component.y + 'px';
        container.style.left = component.x + 'px';
      } else {
        // Component is a clique (element is a row). Widths and Heights of cliques have been calculated. But the rows in cliques need to be recalculated
        element.new_width = component.new_width;

        if (otherRowHeight === 0) {
          element.new_height = element.new_width / element.aspect_ratio;
          otherRowHeight = element.new_height;
        } else {
          element.new_height = component.new_height - otherRowHeight;
        }

        element.x = component.x;
        element.y = rowY;
        rowY += element.new_height;
        var totalWidth = element.elements.reduce((sum, p) => sum += p.new_width, 0);
        var rowX = 0;
        var eLength = element.elements.length;

        for (var i = 0; i < eLength; i++) {
          var image = element.elements[i];
          image.new_width = element.new_width * image.new_width / totalWidth;
          image.new_height = element.new_height; //image.new_width / image.aspect_ratio;

          image.x = rowX;
          rowX += image.new_width;
          container = containers[image.photo_position];
          container.style.width = Math.floor(image.new_width) + 'px';
          container.style.height = Math.floor(image.new_height) + 'px';
          container.style.top = Math.floor(element.y) + 'px';
          container.style.left = Math.floor(element.x + image.x) + 'px';
        }
      }
    }
  }
};

var doImageLayout = (grid, needToWait) => {
  var viewportWidth = Math.floor(grid.getBoundingClientRect().width),
      triggerWidth = isNaN(Photonic_JS.mosaic_trigger_width) || parseInt(Photonic_JS.mosaic_trigger_width) <= 0 ? 200 : parseInt(Photonic_JS.mosaic_trigger_width),
      maxInRow = Math.floor(viewportWidth / triggerWidth),
      minInRow = viewportWidth >= triggerWidth * 2 ? 2 : 1,
      photos = [];
  var setSize;
  var containers = [],
      images = Array.from(grid.getElementsByTagName('img'));
  images.forEach((image, position) => {
    if (image.closest('.photonic-panel') != null) {
      return;
    }

    var a = image.parentNode;
    var div = a.parentNode;
    div.setAttribute('data-photonic-photo-index', position);
    containers[position] = div;
    var height = needToWait ? image.naturalHeight : image.getAttribute('height'),
        width = needToWait ? image.naturalWidth : image.getAttribute('width');

    if (!(height === 0 || height === undefined || width === undefined)) {
      var aspectRatio = width / height;
      photos.push({
        src: image.src,
        width: width,
        height: height,
        aspect_ratio: aspectRatio,
        photo_position: position
      });
    }
  });
  setSize = photos.length;
  var distribution = getDistribution(setSize, maxInRow, minInRow); // We got our random distribution. Let's divide the photos up according to the distribution.

  var groups = [],
      startIdx = 0;
  distribution.forEach(size => {
    groups.push(photos.slice(startIdx, startIdx + size));
    startIdx += size;
  });
  var groupY = 0; // We now have our groups of photos. We need to find the optimal layout for each group.

  groups.forEach(group => {
    // First, order the group by aspect ratio
    group.sort((a, b) => a.aspect_ratio - b.aspect_ratio); // Next, pick a random layout

    var groupLayout;

    if (group.length === 1) {
      groupLayout = [1];
    } else if (group.length === 2) {
      groupLayout = [1, 1];
    } else {
      groupLayout = getDistribution(group.length, group.length - 1, 1);
    } // Now, LAYOUT, BABY!!!


    var cliqueF = 0,
        cliqueL = group.length - 1,
        cliques = [],
        indices = [];

    for (var i = 2; i <= maxInRow; i++) {
      var index = groupLayout.indexOf(i);

      while (-1 < index && cliqueF < cliqueL) {
        // Ideal Layout: one landscape, one portrait. But we will take any 2 with contrasting aspect ratios
        var clique = [],
            j = 0;

        while (j < i && cliqueF <= cliqueL) {
          clique.push(group[cliqueF++]); // One with a low aspect ratio

          j++;

          if (j < i && cliqueF <= cliqueL) {
            clique.push(group[cliqueL--]); // One with a high aspect ratio

            j++;
          }
        } // Clique is formed. Add it to the list of cliques.


        cliques.push(clique);
        indices.push(index); // Keep track of the position of the clique in the row

        index = groupLayout.indexOf(i, index + 1);
      }
    } // The ones that are not in any clique (i.e. the ones in the middle) will be given their own columns in the row.


    var remainder = group.slice(cliqueF, cliqueL + 1); // Now let's layout the cliques individually. Each clique is its own column.

    var rowLayout = [];
    cliques.forEach((clique, cliqueIdx) => {
      var toss = Math.floor(Math.random() * 2); // 0 --> Groups of smallest and largest, or 1 --> Alternating

      var oneRow, otherRow;

      if (toss === 0) {
        // Group the ones with the lowest aspect ratio together, and the ones with the highest aspect ratio together.
        // Lay one group at the top and the other at the bottom
        var wide = Math.max(Math.floor(Math.random() * (clique.length / 2 - 1)), 1);
        oneRow = clique.slice(0, wide);
        otherRow = clique.slice(wide);
      } else {
        // Group alternates together.
        // Lay one group at the top and the other at the bottom
        oneRow = arrayAlternate(clique, 0);
        otherRow = arrayAlternate(clique, 1);
      } // Make heights consistent within rows:


      oneRow = setUniformHeightsForRow(oneRow);
      otherRow = setUniformHeightsForRow(otherRow); // Now make widths consistent

      oneRow.new_width = Math.min(oneRow.width, otherRow.width);
      oneRow.new_height = oneRow.new_width / oneRow.aspect_ratio;
      otherRow.new_width = oneRow.new_width;
      otherRow.new_height = otherRow.new_width / otherRow.aspect_ratio;
      rowLayout.push({
        elements: [oneRow, otherRow],
        height: oneRow.new_height + otherRow.new_height,
        width: oneRow.new_width,
        aspect_ratio: oneRow.new_width / (oneRow.new_height + otherRow.new_height),
        element_position: indices[cliqueIdx]
      });
    });
    rowLayout.sort((a, b) => a.element_position - b.element_position);
    var orderedRowLayout = [];

    for (var position = 0; position < groupLayout.length; position++) {
      var cliqueExists = indices.indexOf(position) > -1;

      if (cliqueExists) {
        orderedRowLayout.push(rowLayout.shift());
      } else {
        var rem = remainder.shift();
        orderedRowLayout.push({
          elements: [rem],
          height: rem.height,
          width: rem.width,
          aspect_ratio: rem.aspect_ratio
        });
      }
    } // Main Row layout is fully constructed and ordered. Now we need to balance heights and widths of all cliques with the "remainder"


    var totalAspect = orderedRowLayout.reduce((sum, p) => sum += p.aspect_ratio, 0);
    var elementX = 0;
    orderedRowLayout.forEach(component => {
      component.new_width = component.aspect_ratio / totalAspect * viewportWidth;
      component.new_height = component.new_width / component.aspect_ratio;
      component.y = groupY;
      component.x = elementX;
      elementX += component.new_width;
    });
    groupY += orderedRowLayout[0].new_height;
    finalizeTiledLayout(orderedRowLayout, containers);
  });
  grid.style.height = groupY + 'px';
};

var Mosaic = (resized, jsLoaded, selector) => {
  if (console !== undefined && Photonic_JS.debug_on !== '0' && Photonic_JS.debug_on !== '') console.time('Mosaic');
  var selection = document.querySelectorAll(selector);

  if (selector == null || selection.length === 0) {
    selection = document.querySelectorAll('.photonic-mosaic-layout');
  }

  if (!resized && selection.length > 0) {
    _Core.Core.showSpinner();
  }

  selection.forEach(grid => {
    if (!grid.hasChildNodes()) {
      return;
    }

    if (grid.classList.contains('sizes-present')) {
      _Core.Core.watchForImages(grid);

      doImageLayout(grid, false);

      _Core.Core.showSlideupTitle();

      if (!resized && !jsLoaded) {
        _Core.Core.hideLoading();
      }
    } else {
      _Core.Core.waitForImages(grid).then(() => {
        if (!grid.classList.contains('sizes-present')) {
          doImageLayout(grid, true);
        }

        Array.from(grid.getElementsByTagName('img')).forEach(image => Util.fadeIn(image));

        _Core.Core.showSlideupTitle();

        if (!resized && !jsLoaded) {
          _Core.Core.hideLoading();
        }
      });
    }
  });
  if (console !== undefined && Photonic_JS.debug_on !== '0' && Photonic_JS.debug_on !== '') console.timeEnd('Mosaic');
}; //Mosaic(false);


exports.Mosaic = Mosaic;

/***/ }),

/***/ "../include/js/front-end/src/Layouts/Slider.js":
/*!*****************************************************!*\
  !*** ../include/js/front-end/src/Layouts/Slider.js ***!
  \*****************************************************/
/*! flagged exports */
/*! export Slider [provided] [no usage info] [missing usage info prevents renaming] */
/*! export __esModule [provided] [no usage info] [missing usage info prevents renaming] */
/*! export initializeSliders [provided] [no usage info] [missing usage info prevents renaming] */
/*! other exports [not provided] [no usage info] */
/*! runtime requirements: __webpack_exports__, __webpack_require__ */
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.initializeSliders = exports.Slider = void 0;

var _Core = __webpack_require__(/*! ../Core */ "../include/js/front-end/src/Core.js");

var adaptiveHeight = (slideshow, slide, splide) => {
  var options = splide.options;
  var currentlyActive = splide.index;

  if (slide.isWithin(parseInt(currentlyActive), parseInt(options.perPage) - 1)) {
    var allSlides = splide.Components.Elements.slides;
    var lastVisible = parseInt(currentlyActive) + parseInt(options.perPage);
    var visibleSlides = allSlides.slice(currentlyActive, lastVisible);
    var maxHeight = 0; // Need requestAnimationFrame, otherwise offsetHeight returns 0 for first photo

    requestAnimationFrame(() => {
      Array.prototype.forEach.call(visibleSlides, visible => {
        var visibleImage = visible.querySelector('img');
        var offsetHeight = visibleImage.offsetHeight;

        if (visibleImage && offsetHeight > maxHeight) {
          maxHeight = offsetHeight;
        }
      });
      slide.slide.style.height = "".concat(maxHeight, "px");
      var splideTrack = slideshow.querySelector('.splide__track');
      var splideTrackHeight = splideTrack ? splideTrack.offsetHeight : 0;

      if (maxHeight !== splideTrackHeight) {
        var splideList = slideshow.querySelector('.splide__list');
        splideList.style.height = "".concat(maxHeight, "px");
        splideTrack.style.height = "".concat(maxHeight, "px");
      }
    });
  }
};

var fixedHeight = (slideshow, splideObj) => {
  var maxHeight = 0,
      maxAspect = 0,
      containerWidth = slideshow.offsetWidth,
      children = slideshow.querySelectorAll('.splide__slide img');
  Array.prototype.forEach.call(children, img => {
    if (img.naturalHeight !== 0) {
      var childAspect = img.naturalWidth / img.naturalHeight;

      if (childAspect >= maxAspect) {
        maxAspect = childAspect;
        var heightFactor = img.naturalWidth > containerWidth ? containerWidth / img.naturalWidth : 1;
        var cols = parseInt(splideObj.options.perPage, 10);

        if (!isNaN(cols) && cols !== 0) {
          heightFactor = heightFactor / cols;
        }

        maxHeight = img.naturalHeight * heightFactor;
      }
    }
  });
  Array.prototype.forEach.call(children, img => {
    img.style.height = maxHeight + 'px';
  });
  Array.prototype.forEach.call(slideshow.querySelectorAll('.splide__slide, .splide__list'), slideOrList => {
    slideOrList.style.height = maxHeight + 'px';
  });
  slideshow.style.height = maxHeight + 'px';
};

var Slider = slideshow => {
  if (slideshow) {
    var content = slideshow.querySelector('.photonic-slideshow-content');

    if (content) {
      _Core.Core.waitForImages(slideshow).then(() => {
        var idStr = '#' + slideshow.getAttribute('id');
        var splideThumbs = document.querySelector(idStr + '-thumbs');

        if (splideThumbs != null) {
          splideThumbs = new Splide(idStr + '-thumbs');
          splideThumbs.mount();
        }

        var splide = new Splide(idStr);
        splide.on('mounted resize', function (slide) {
          if (slideshow.classList.contains('photonic-slideshow-side-white') || slideshow.classList.contains('photonic-slideshow-start-next')) {
            fixedHeight(slideshow, splide);
          }
        });
        splide.on('visible', function (slide) {
          if (slideshow.classList.contains('photonic-slideshow-adapt-height')) {
            adaptiveHeight(slideshow, slide, splide);
          }
        });

        if (splideThumbs == null) {
          splide.mount();
        } else {
          splide.sync(splideThumbs).mount();
        }

        slideshow.querySelectorAll('img').forEach(img => {
          img.style.display = 'inline';
        });
      });
    }
  }
};

exports.Slider = Slider;

var initializeSliders = () => {
  var primarySliders = document.querySelectorAll('.photonic-slideshow');

  if (typeof Splide != "undefined") {
    primarySliders.forEach(slideshow => Slider(slideshow));
  } else if (console !== undefined && primarySliders.length > 0) {
    console.error('Splide not found! Please ensure that the Splide script is available and loaded before Photonic.');
  }
};

exports.initializeSliders = initializeSliders;

/***/ }),

/***/ "../include/js/front-end/src/Lightboxes/Lightbox.js":
/*!**********************************************************!*\
  !*** ../include/js/front-end/src/Lightboxes/Lightbox.js ***!
  \**********************************************************/
/*! flagged exports */
/*! export Lightbox [provided] [no usage info] [missing usage info prevents renaming] */
/*! export __esModule [provided] [no usage info] [missing usage info prevents renaming] */
/*! other exports [not provided] [no usage info] */
/*! runtime requirements: __webpack_exports__, __webpack_require__ */
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../../../../../node_modules/@babel/runtime/helpers/interopRequireDefault.js");

Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.Lightbox = void 0;

var _defineProperty2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "../../../../../node_modules/@babel/runtime/helpers/defineProperty.js"));

class Lightbox {
  constructor() {
    (0, _defineProperty2.default)(this, "deep", void 0);
    (0, _defineProperty2.default)(this, "lastDeep", void 0);
    this.socialIcons = "<div id='photonic-social'>" + "<a class='photonic-share-fb' href='https://www.facebook.com/sharer/sharer.php?u={photonic_share_link}&amp;title={photonic_share_title}&amp;picture={photonic_share_image}' target='_blank' title='Share on Facebook'><div class='icon-facebook'></div></a>" + "<a class='photonic-share-twitter' href='https://twitter.com/share?url={photonic_share_link}&amp;text={photonic_share_title}' target='_blank' title='Share on Twitter'><div class='icon-twitter'></div></a>" + "<a class='photonic-share-pinterest' data-pin-do='buttonPin' href='https://www.pinterest.com/pin/create/button/?url={photonic_share_link}&media={photonic_share_image}&description={photonic_share_title}' data-pin-custom='true' target='_blank' title='Share on Pinterest'><div class='icon-pinterest'></div></a>" + "</div>";
    this.videoIndex = 1;
  }

  getVideoSize(url, baseline) {
    return new Promise(resolve => {
      // create the video element
      var video = document.createElement('video'); // place a listener on it

      video.addEventListener("loadedmetadata", function () {
        // retrieve dimensions
        var height = this.videoHeight,
            width = this.videoWidth;
        var videoAspectRatio = this.videoWidth / this.videoHeight,
            baseAspectRatio = baseline.width / baseline.height;
        var newWidth, newHeight;

        if (baseAspectRatio > videoAspectRatio) {
          // Window is wider than it needs to be ... constrain by window height
          newHeight = baseline.height;
          newWidth = width * newHeight / height;
        } else {
          // Window is narrower than it needs to be ... constrain by window width
          newWidth = baseline.width;
          newHeight = height * newWidth / width;
        } // send back result


        resolve({
          height: height,
          width: width,
          newHeight: newHeight,
          newWidth: newWidth
        });
      }, false); // start download meta-data

      video.src = url;
    });
  }

  getImageSize(url, baseline) {
    return new Promise(function (resolve) {
      var image = document.createElement('img'); // place a listener on it

      image.addEventListener("load", function () {
        // retrieve dimensions
        var height = this.height,
            width = this.width,
            imageAspectRatio = this.width / this.height,
            baseAspectRatio = baseline.width / baseline.height;
        var newWidth, newHeight;

        if (baseAspectRatio > imageAspectRatio) {
          // Window is wider than it needs to be ... constrain by window height
          newHeight = baseline.height;
          newWidth = width * newHeight / height;
        } else {
          // Window is narrower than it needs to be ... constrain by window width
          newWidth = baseline.width;
          newHeight = height * newWidth / width;
        } // send back result


        resolve({
          height: height,
          width: width,
          newHeight: newHeight,
          newWidth: newWidth
        });
      }, false); // start download meta-data

      image.src = url;
    });
  }

  addSocial(selector, shareable, position) {
    if ((Photonic_JS.social_media === undefined || Photonic_JS.social_media === '') && shareable['buy'] === undefined) {
      return;
    }

    var socialEl = document.getElementById('photonic-social');

    if (socialEl !== null) {
      socialEl.parentNode.removeChild(socialEl);
    }

    var addTo;

    if (position === undefined || position === null) {
      addTo = 'beforeend';
    } else {
      addTo = position;
    }

    if (location.hash !== '') {
      var social = this.socialIcons.replace(/{photonic_share_link}/g, encodeURIComponent(shareable['url'])).replace(/{photonic_share_title}/g, encodeURIComponent(shareable['title'])).replace(/{photonic_share_image}/g, encodeURIComponent(shareable['image']));
      var selectorEl;

      if (typeof selector === 'string') {
        selectorEl = document.documentElement.querySelector(selector);
      } else {
        selectorEl = selector;
      }

      if (selectorEl !== null) {
        selectorEl.insertAdjacentHTML(addTo, social);
      }

      if (Photonic_JS.social_media === undefined || Photonic_JS.social_media === '') {
        var socialMediaIcons = document.documentElement.querySelectorAll('.photonic-share-fb, .photonic-share-twitter, .photonic-share-pinterest');
        Array.prototype.forEach.call(socialMediaIcons, function (socialIcon) {
          socialIcon.parentNode.removeChild(socialIcon);
        });
      }
    }
  }

  setHash(a) {
    if (Photonic_JS.deep_linking === undefined || Photonic_JS.deep_linking === 'none' || a === null || a === undefined) {
      return;
    }

    var hash = typeof a === 'string' ? a : a.getAttribute('data-photonic-deep');

    if (hash === undefined) {
      return;
    }

    if (typeof window.history.pushState === 'function' && Photonic_JS.deep_linking === 'yes-history') {
      window.history.pushState({}, document.title, '#' + hash);
    } else if (typeof window.history.replaceState === 'function' && Photonic_JS.deep_linking === 'no-history') {
      window.history.replaceState({}, document.title, '#' + hash);
    } else {
      document.location.hash = hash;
    }
  }

  unsetHash() {
    this.lastDeep = this.lastDeep === undefined || this.deep !== '' ? location.hash : this.lastDeep;

    if (window.history && 'replaceState' in window.history) {
      history.replaceState({}, document.title, location.href.substr(0, location.href.length - location.hash.length));
    } else {
      window.location.hash = '';
    }
  }

  changeHash(e) {
    if (e.type === 'load') {
      var hash = window.location.hash;
      hash = hash.substr(1);

      if (hash && hash !== '') {
        var allMatches = document.querySelectorAll('[data-photonic-deep="' + hash + '"]');

        if (allMatches.length > 0) {
          var thumbToClick = allMatches[0];
          var event = document.createEvent('HTMLEvents');
          event.initEvent('click', true, false);
          thumbToClick.dispatchEvent(event);
        }
      }
    } else {
      var node = this.deep;

      if (node != null) {
        if (node.length > 1) {
          if (window.location.hash && node.indexOf('#access_token=') !== -1) {
            this.unsetHash();
          } else {
            node = node.substr(1);

            var _allMatches = document.querySelectorAll('[data-photonic-deep="' + node + '"]');

            if (_allMatches.length > 0) {
              var _thumbToClick = _allMatches[0];

              var _event = document.createEvent('HTMLEvents');

              _event.initEvent('click', true, false);

              _thumbToClick.dispatchEvent(_event);

              this.setHash(node);
            }
          }
        }
      }
    }
  }

  catchYouTubeURL(url) {
    var regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*)(?:(\?t|&t|\?start|&start)=(\d+))?.*/,
        match = url.match(regExp);

    if (match && match[2].length === 11) {
      var obj = {
        url: match[2]
      };

      if (match[4] !== undefined) {
        obj.timestamp = match[4];
      }

      return obj;
    }
  }

  catchVimeoURL(url) {
    var regExp = /(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|video\/|)(\d+)(?:[a-zA-Z0-9_\-]+)?/,
        match = url.match(regExp);

    if (match) {
      return match[1];
    }
  }

  soloImages() {
    var a = document.querySelectorAll('a[href]');
    var solos = Array.from(a).filter(elem => /(\.jpg|\.jpeg|\.bmp|\.gif|\.png)/i.test(elem.getAttribute('href'))).filter(elem => !elem.classList.contains('photonic-lb'));
    solos.forEach(solo => {
      solo.classList.add("photonic-" + Photonic_JS.lightbox_library);
      solo.classList.add("photonic-" + Photonic_JS.lightbox_library + '-solo');
      solo.classList.add(Photonic_JS.lightbox_library);
    });
    return solos;
  }

  changeVideoURL(element, regular, embed, poster) {// Implemented in individual lightboxes. Empty for unsupported lightboxes
  }

  hostedVideo(a) {// Implemented in individual lightboxes. Empty for unsupported lightboxes
  }

  soloVideos() {
    var self = this;

    if (Photonic_JS.lightbox_for_videos) {
      var a = document.querySelectorAll('a[href]');
      a.forEach(function (anchor) {
        var regular, embed, poster;
        var href = anchor.getAttribute('href'),
            youTube = self.catchYouTubeURL(href),
            vimeo = self.catchVimeoURL(href);

        if (youTube !== undefined) {
          var ytSrc = youTube.url,
              ytTimeStamp = youTube.timestamp,
              regularT = '',
              embedT = '';

          if (ytTimeStamp !== undefined) {
            regularT = '&t=' + ytTimeStamp;
            embedT = '?start=' + ytTimeStamp;
          }

          regular = 'https://youtube.com/watch?v=' + ytSrc + regularT;
          embed = 'https://youtube.com/embed/' + ytSrc + embedT;
          poster = 'https://img.youtube.com/vi/' + ytSrc + '/hddefault.jpg';
        } else if (vimeo !== undefined) {
          regular = 'https://vimeo.com/' + vimeo;
          embed = 'https://player.vimeo.com/video/' + vimeo;
        }

        if (regular !== undefined) {
          anchor.classList.add(Photonic_JS.lightbox_library + "-video");
          self.changeVideoURL(anchor, regular, embed, poster);
          self.modifyAdditionalVideoProperties(anchor);
        }

        self.hostedVideo(anchor);
      });
    }
  }

  handleSolos() {
    if (Photonic_JS.lightbox_for_all) {
      this.soloImages();
    }

    this.soloVideos();

    if (Photonic_JS.deep_linking !== undefined && Photonic_JS.deep_linking !== 'none') {
      window.addEventListener('load', this.changeHash);
      window.addEventListener('hashchange', this.changeHash);
    }
  }

  initialize() {
    this.handleSolos(); // Implemented by child classes
  }

  initializeForNewContainer(containerId) {// Implemented by individual lightboxes. Empty for cases where not required
  }

  initializeForExisting() {// Implemented by child classes
  }

  modifyAdditionalVideoProperties(anchor) {// Implemented by individual lightboxes. Empty for cases where not required
  }

}

exports.Lightbox = Lightbox;

/***/ }),

/***/ "../include/js/front-end/src/Lightboxes/Magnific.js":
/*!**********************************************************!*\
  !*** ../include/js/front-end/src/Lightboxes/Magnific.js ***!
  \**********************************************************/
/*! flagged exports */
/*! export PhotonicMagnific [provided] [no usage info] [missing usage info prevents renaming] */
/*! export __esModule [provided] [no usage info] [missing usage info prevents renaming] */
/*! other exports [not provided] [no usage info] */
/*! runtime requirements: __webpack_exports__, __webpack_require__ */
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireWildcard = __webpack_require__(/*! @babel/runtime/helpers/interopRequireWildcard */ "../../../../../node_modules/@babel/runtime/helpers/interopRequireWildcard.js");

Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.PhotonicMagnific = void 0;

var _Lightbox = __webpack_require__(/*! ./Lightbox */ "../include/js/front-end/src/Lightboxes/Lightbox.js");

var Util = _interopRequireWildcard(__webpack_require__(/*! ../Util */ "../include/js/front-end/src/Util.js"));

class PhotonicMagnific extends _Lightbox.Lightbox {
  constructor($) {
    super();
    this.$ = $;

    this.$.expr[':'].parents = function (a, i, m) {
      return jQuery(a).parents(m[3]).length < 1;
    };
  }

  initialize(selector, group) {
    this.handleSolos();
    var self = this;
    var $ = self.$;
    $('a.photonic-magnific').each(function (i, a) {
      var $a = $(a);

      if ($a.attr('data-photonic-media-type') === 'video') {
        $a.removeClass('mfp-image');
        $a.addClass('mfp-inline');
      } else if ($a.attr('data-photonic-media-type') === 'image') {
        $a.addClass('mfp-image');
        $a.removeClass('mfp-inline');
      }
    });
    $(selector).each(function (idx, obj) {
      $(obj).magnificPopup({
        delegate: 'a.photonic-magnific',
        type: 'image',
        gallery: {
          enabled: true
        },
        image: {
          titleSrc: 'data-title'
        },
        callbacks: {
          change: function change() {
            var $content = $(this.content),
                videoId = $content.attr('id');

            if (videoId !== undefined && videoId.indexOf('photonic-video') > -1) {
              var videoURL = $content.find('video').find('source').attr('src');

              if (videoURL !== undefined) {
                self.getVideoSize(videoURL, {
                  height: window.innerHeight * 0.8,
                  width: window.innerWidth * 0.8
                }).then(function (dimensions) {
                  $content.find('video').attr({
                    height: dimensions.newHeight,
                    width: dimensions.newWidth
                  });
                });
              }
            }

            if (this.currItem.el.length > 0) {
              self.setHash(this.currItem.el[0]);
            }

            if (this.currItem.type === 'inline') {
              $(this.content).append($('<div></div>').html($(this.currItem.el).data('title')));
            }
          },
          imageLoadComplete: function imageLoadComplete() {
            var shareable = {
              'url': location.href,
              'title': Util.getText($(this.currItem.el).data('title')),
              'image': $(this.currItem.el).attr('href')
            };
            self.addSocial('.mfp-figure', shareable);
          },
          close: function close() {
            self.unsetHash();
          }
        }
      });
    });
  }

  initializeForNewContainer(selector) {
    this.initialize(selector);
  }

  changeVideoURL(element, regular, embed) {
    this.$(element).attr('href', regular);
  }

  hostedVideo(a) {
    var $ = this.$;
    var html5 = $(a).attr('href').match(new RegExp(/(\.mp4|\.webm|\.ogg)/i));
    var css = $(a).attr('class');
    css = css !== undefined && css.includes('photonic-lb');

    if (html5 !== null && !css) {
      $(a).addClass(Photonic_JS.lightbox_library + "-html5-video");
      var $videos = $('#photonic-html5-videos');
      $videos = $videos.length ? $videos : $('<div style="display:none;" id="photonic-html5-videos"></div>').appendTo(document.body);
      $videos.append('<div id="photonic-html5-video-' + this.videoIndex + '"><video controls preload="none"><source src="' + $(a).attr('href') + '" type="video/mp4">Your browser does not support HTML5 video.</video></div>');
      $(a).attr('data-html5-href', $(a).attr('href'));
      $(a).attr('href', '#photonic-html5-video-' + this.videoIndex);
      this.videoIndex++;
    }
  }

  initializeSolos() {
    var self = this;
    var $ = self.$;

    if (Photonic_JS.lightbox_for_all) {
      $('a.photonic-magnific').filter(':parents(.photonic-level-1)').each(function (idx, obj) {
        // Solo images
        $(obj).magnificPopup({
          type: 'image'
        });
      });
    }

    if (Photonic_JS.lightbox_for_videos) {
      $('.magnific-video').each(function (idx, obj) {
        $(obj).magnificPopup({
          type: 'iframe'
        });
      });
      $('.magnific-html5-video').each(function (idx, obj) {
        $(obj).magnificPopup({
          type: 'inline',
          callbacks: {
            change: function change() {
              var $content = $(this.content),
                  videoId = $content.attr('id');

              if (videoId !== undefined && videoId.indexOf('photonic-html5-video') > -1) {
                var videoURL = $content.find('video').find('source').attr('src');

                if (videoURL !== undefined) {
                  self.getVideoSize(videoURL, {
                    height: window.innerHeight * 0.8,
                    width: window.innerWidth * 0.8
                  }).then(function (dimensions) {
                    $content.find('video').attr({
                      height: dimensions.newHeight,
                      width: dimensions.newWidth
                    });
                  });
                }
              }
            }
          }
        });
      });
    }
  }

}

exports.PhotonicMagnific = PhotonicMagnific;

/***/ }),

/***/ "../include/js/front-end/src/Listeners.js":
/*!************************************************!*\
  !*** ../include/js/front-end/src/Listeners.js ***!
  \************************************************/
/*! flagged exports */
/*! export __esModule [provided] [no usage info] [missing usage info prevents renaming] */
/*! export addAllListeners [provided] [no usage info] [missing usage info prevents renaming] */
/*! export addHelperMoreButtonListener [provided] [no usage info] [missing usage info prevents renaming] */
/*! export addLazyLoadListener [provided] [no usage info] [missing usage info prevents renaming] */
/*! export addLevel2ClickListener [provided] [no usage info] [missing usage info prevents renaming] */
/*! export addLevel3ExpandListener [provided] [no usage info] [missing usage info prevents renaming] */
/*! export addMoreButtonListener [provided] [no usage info] [missing usage info prevents renaming] */
/*! export addPasswordSubmitListener [provided] [no usage info] [missing usage info prevents renaming] */
/*! export addSlideUpEnterListener [provided] [no usage info] [missing usage info prevents renaming] */
/*! export addSlideUpLeaveListener [provided] [no usage info] [missing usage info prevents renaming] */
/*! other exports [not provided] [no usage info] */
/*! runtime requirements: __webpack_exports__, __webpack_require__ */
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireWildcard = __webpack_require__(/*! @babel/runtime/helpers/interopRequireWildcard */ "../../../../../node_modules/@babel/runtime/helpers/interopRequireWildcard.js");

Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.addAllListeners = exports.addLazyLoadListener = exports.addSlideUpLeaveListener = exports.addSlideUpEnterListener = exports.addHelperMoreButtonListener = exports.addMoreButtonListener = exports.addLevel3ExpandListener = exports.addPasswordSubmitListener = exports.addLevel2ClickListener = void 0;

var _Core = __webpack_require__(/*! ./Core */ "../include/js/front-end/src/Core.js");

var Util = _interopRequireWildcard(__webpack_require__(/*! ./Util */ "../include/js/front-end/src/Util.js"));

var Requests = _interopRequireWildcard(__webpack_require__(/*! ./Requests */ "../include/js/front-end/src/Requests.js"));

var _Justified = __webpack_require__(/*! ./Layouts/Justified */ "../include/js/front-end/src/Layouts/Justified.js");

var _Mosaic = __webpack_require__(/*! ./Layouts/Mosaic */ "../include/js/front-end/src/Layouts/Mosaic.js");

var _Tooltip = __webpack_require__(/*! ./Components/Tooltip */ "../include/js/front-end/src/Components/Tooltip.js");

// .photonic-level-2-thumb:not(".gallery-page")
var addLevel2ClickListener = () => {
  document.addEventListener('click', e => {
    if (!(e.target instanceof Element) || !e.target.closest('.photonic-level-2-thumb') || Photonic_JS.lightbox_library === 'none') {
      return;
    }

    var clicked = e.target.closest('.photonic-level-2-thumb');

    if (Util.hasClass(clicked, 'gallery-page')) {
      return;
    }

    e.preventDefault();
    var container = clicked.closest('.photonic-level-2-container');
    var galleryData = JSON.parse(container.getAttribute('data-photonic'));
    var provider = galleryData['platform'],
        singular = galleryData['singular'],
        query = container.getAttribute('data-photonic-query');
    var args = {
      "panel_id": clicked.getAttribute('id'),
      "popup": galleryData['popup'],
      "photo_count": galleryData['photo-count'],
      "photo_more": galleryData['photo-more'] || '',
      "query": query
    };
    if (provider === 'google' || provider === 'zenfolio') args.thumb_size = galleryData['thumb-size'];

    if (provider === 'flickr' || provider === 'smug' || provider === 'google' || provider === 'zenfolio') {
      args.overlay_size = galleryData['overlay-size'];
      args.overlay_video_size = galleryData['overlay-video-size'];
    }

    if (provider === 'google') {
      args.overlay_crop = galleryData['overlay-crop'];
    }

    Requests.displayLevel2(provider, singular, args);
  }, false);
}; // .photonic-password-submit


exports.addLevel2ClickListener = addLevel2ClickListener;

var addPasswordSubmitListener = () => {
  document.addEventListener('click', e => {
    if (!(e.target instanceof Element) || !e.target.closest('.photonic-password-submit')) {
      return;
    }

    e.preventDefault();
    var clicked = e.target.closest('.photonic-password-submit');
    var modal = clicked.closest('.photonic-password-prompter'),
        container = clicked.closest('.photonic-level-2-container');
    var galleryData = JSON.parse(container.getAttribute('data-photonic'));
    var album_id = modal.getAttribute('id');
    var components = album_id.split('-');
    var provider = galleryData['platform'],
        singular_type = galleryData['singular'],
        album_key = components.slice(4).join('-'),
        thumb_id = "photonic-".concat(provider, "-").concat(singular_type, "-thumb-").concat(album_key),
        thumb = document.getElementById("".concat(thumb_id)),
        query = container.getAttribute('data-photonic-query');
    var password = modal.querySelector('input[name="photonic-' + provider + '-password"]');
    password = password.value;

    var prompter = _Core.Core.prompterList["#photonic-".concat(provider, "-").concat(singular_type, "-prompter-").concat(album_key)];

    if (prompter !== undefined && prompter !== null) {
      prompter.hide();
    }

    _Core.Core.showSpinner();

    var args = {
      'panel_id': thumb_id,
      "popup": galleryData['popup'],
      "photo_count": galleryData['photo-count'],
      "photo_more": galleryData['photo-more'] || '',
      "query": query
    };

    if (provider === 'smug') {
      args.password = password;
      args.overlay_size = galleryData['overlay-size'];
    } else if (provider === 'zenfolio') {
      args.password = password;
      args.realm_id = thumb.getAttribute('data-photonic-realm');
      args.thumb_size = galleryData['thumb-size'];
      args.overlay_size = galleryData['overlay-size'];
      args.overlay_video_size = galleryData['overlay-video-size'];
    }

    Requests.processRequest(provider, singular_type, album_key, args);
  }, false);
}; // a.photonic-level-3-expand


exports.addPasswordSubmitListener = addPasswordSubmitListener;

var addLevel3ExpandListener = () => {
  document.addEventListener('click', e => {
    if (!(e.target instanceof Element) || !e.target.closest('a.photonic-level-3-expand')) {
      return;
    }

    e.preventDefault();
    var current = e.target.closest('a.photonic-level-3-expand'),
        header = current.parentNode.parentNode.parentNode,
        stream = header.parentNode;

    if (current.classList.contains('photonic-level-3-expand-plus')) {
      Requests.processL3Request(current, header, {
        'view': 'collections',
        'node': current.getAttribute('data-photonic-level-3'),
        'layout': current.getAttribute('data-photonic-layout'),
        'stream': stream.getAttribute('id')
      });
    } else if (current.classList.contains('photonic-level-3-expand-up')) {
      var display = Util.next(header, '.photonic-stream');
      Util.slideUpDown(display, 'hide');
      current.classList.remove('photonic-level-3-expand-up');
      current.classList.add('photonic-level-3-expand-down');
      current.setAttribute('title', Photonic_JS.maximize_panel === undefined ? 'Show' : Photonic_JS.maximize_panel);
    } else if (current.classList.contains('photonic-level-3-expand-down')) {
      var _display = Util.next(header, '.photonic-stream'); // Util.slideDown(display);


      Util.slideUpDown(_display, 'show');
      current.classList.remove('photonic-level-3-expand-down');
      current.classList.add('photonic-level-3-expand-up');
      current.setAttribute('title', Photonic_JS.minimize_panel === undefined ? 'Hide' : Photonic_JS.minimize_panel);
    }
  }, false);
}; // a.photonic-more-button.photonic-more-dynamic


exports.addLevel3ExpandListener = addLevel3ExpandListener;

var addMoreButtonListener = () => {
  document.addEventListener('click', e => {
    if (!(e.target instanceof Element) || !e.target.closest('a.photonic-more-button.photonic-more-dynamic')) {
      return;
    }

    e.preventDefault();
    var clicked = e.target.closest('a.photonic-more-button.photonic-more-dynamic');
    var container = clicked.parentNode.querySelector('.photonic-level-1-container, .photonic-level-2-container');
    var query = container.getAttribute('data-photonic-query'),
        provider = container.getAttribute('data-photonic-platform'),
        level = container.classList.contains('photonic-level-1-container') ? 'level-1' : 'level-2',
        containerId = container.getAttribute('id');

    _Core.Core.showSpinner();

    Util.post(Photonic_JS.ajaxurl, {
      'action': 'photonic_load_more',
      'provider': provider,
      'query': query
    }, data => {
      var ret = Util.getElement(data),
          images = ret.querySelectorAll(".photonic-".concat(level)),
          more_button = ret.querySelector('.photonic-more-button'),
          one_existing = container.querySelector('a.photonic-lb');
      var anchors = [];

      if (one_existing !== null) {
        images.forEach(image => {
          var a = image.querySelector('a');

          if (a !== null) {
            a.setAttribute('rel', one_existing.getAttribute('rel'));

            if (a.getAttribute('data-fancybox') != null) {
              a.setAttribute('data-fancybox', one_existing.getAttribute('data-fancybox'));
            } else if (a.getAttribute('data-rel') != null) {
              a.setAttribute('data-rel', one_existing.getAttribute('data-rel'));
            } else if (a.getAttribute('data-strip-group') != null) {
              a.setAttribute('data-strip-group', one_existing.getAttribute('data-strip-group'));
            } else if (a.getAttribute('data-gall') != null) {
              a.setAttribute('data-gall', one_existing.getAttribute('data-gall'));
            }

            anchors.push(a);
          }
        });
      } // Can't do this above, which is only for L1


      images.forEach(image => container.appendChild(image));

      _Core.Core.moveHTML5External();

      if (images.length === 0) {
        _Core.Core.hideLoading();

        Util.fadeOut(clicked);
        clicked.remove();
      }

      var lightbox = _Core.Core.getLightbox();

      if (Photonic_JS.lightbox_library === 'imagelightbox') {
        if (one_existing != null) {
          lightbox = _Core.Core.getLightboxList()['a[rel="' + one_existing.getAttribute('rel') + '"]'];

          if (level === 'level-1') {
            lightbox.addToImageLightbox(anchors);
          }
        }
      } else if (Photonic_JS.lightbox_library === 'lightcase') {
        if (one_existing != null) {
          lightbox.initialize('a[data-rel="' + one_existing.getAttribute('data-rel') + '"]');
        }
      } else if (Photonic_JS.lightbox_library === 'lightgallery') {
        if (one_existing !== null) {
          lightbox = _Core.Core.getLightboxList()[one_existing.getAttribute('rel')];
          var galleryItems = [...lightbox.galleryItems];
          images.forEach(image => {
            var a = image.querySelector('a');
            var img = a.querySelector('img');
            var videoAttr;

            if (a.getAttribute('data-photonic-media-type') === 'video') {
              videoAttr = JSON.parse(a.getAttribute('data-video'));
            }

            galleryItems.push({
              "alt": img.getAttribute('alt'),
              "downloadUrl": a.getAttribute('data-download-url'),
              "src": a.getAttribute('data-photonic-media-type') === 'video' ? videoAttr.source[0].src : a.getAttribute('href'),
              "subHtml": a.getAttribute('data-sub-html'),
              "thumb": img.getAttribute('src')
            });
          });
          lightbox.updateSlides(galleryItems, 0);
          lightbox.refresh();
        } else {
          lightbox.initialize(container);
        }
      } else if (Photonic_JS.lightbox_library === 'venobox') {
        if (one_existing !== null) {
          var gallId = one_existing.getAttribute('data-gall');

          if (one_existing.closest('.photonic-panel')) {
            gallId = one_existing.closest('.photonic-panel').getAttribute('id');
          }

          lightbox.initialize('#' + gallId, true);
        }
      } else if (['bigpicture', 'featherlight', 'glightbox', 'spotlight'].includes(Photonic_JS.lightbox_library)) {
        lightbox.initialize(container);
      } else if (Photonic_JS.lightbox_library === 'baguettebox') {
        lightbox.initialize(null, true);
      } else if (Photonic_JS.lightbox_library === 'fancybox3') {
        if (one_existing != null) {
          lightbox.initialize(null, one_existing.getAttribute('data-fancybox'));
        }
      } else if (Photonic_JS.lightbox_library === 'photoswipe') {
        lightbox.initialize();
      }

      function doImageLayout(images) {
        var new_query = ret.querySelector('.photonic-random-layout,.photonic-standard-layout,.photonic-masonry-layout,.photonic-mosaic-layout,.modal-gallery');

        if (new_query != null) {
          container.setAttribute('data-photonic-query', new_query.getAttribute('data-photonic-query'));
        }

        if (more_button == null) {
          Util.fadeOut(clicked);
          clicked.remove();
        }

        if (Util.hasClass(container, 'photonic-mosaic-layout')) {
          (0, _Mosaic.Mosaic)(false, false, '#' + containerId);
        } else if (Util.hasClass(container, 'photonic-random-layout')) {
          (0, _Justified.JustifiedGrid)(false, false, '#' + containerId, lightbox);
        } else if (Util.hasClass(container, 'photonic-masonry-layout')) {
          images.forEach(image => {
            var img = image.querySelector('img');
            Util.fadeIn(img);
            img.style.display = 'block';
          });

          _Core.Core.hideLoading();
        } else {
          container.querySelectorAll('.photonic-' + level).forEach(el => {
            el.style.display = 'inline-block';
          });

          _Core.Core.standardizeTitleWidths();

          _Core.Core.hideLoading();
        }

        (0, _Tooltip.Tooltip)('[data-photonic-tooltip]', '.photonic-tooltip-container');
      }

      if (container.classList.contains('sizes-present')) {
        _Core.Core.watchForImages(images);

        doImageLayout(images);
      } else {
        _Core.Core.waitForImages(images).then(() => {
          doImageLayout(images);
        });
      }
    });
  });
}; // input[type="button"].photonic-helper-more


exports.addMoreButtonListener = addMoreButtonListener;

var addHelperMoreButtonListener = () => {
  document.addEventListener('click', e => {
    if (!(e.target instanceof Element) || !e.target.closest('input[type="button"].photonic-helper-more')) {
      return;
    }

    e.preventDefault();

    _Core.Core.showSpinner();

    var clicked = e.target.closest('input[type="button"].photonic-helper-more');
    var table = clicked.closest('table');
    var nextToken = clicked.getAttribute('data-photonic-token') === undefined ? null : clicked.getAttribute('data-photonic-token'),
        provider = clicked.getAttribute('data-photonic-platform'),
        accessType = clicked.getAttribute('data-photonic-access');
    var args = {
      'action': 'photonic_helper_shortcode_more',
      'provider': provider,
      'access': accessType
    };

    if (nextToken) {
      args.nextPageToken = nextToken;
    }

    if (provider === 'google') {
      Util.post(Photonic_JS.ajaxurl, args, data => {
        var ret = Util.getElement(data);
        ret = Array.from(ret.getElementsByTagName('tr'));

        if (ret.length > 0) {
          var tr = clicked.closest('tr');

          if (tr) {
            tr.remove();
          }

          ret.forEach((node, i) => {
            if (i !== 0) {
              table.appendChild(node);
            }
          });
        }

        (0, _Tooltip.Tooltip)('[data-photonic-tooltip]', '.photonic-tooltip-container');

        _Core.Core.hideLoading();
      });
    }
  });
};

exports.addHelperMoreButtonListener = addHelperMoreButtonListener;

var addSlideUpEnterListener = () => {
  document.addEventListener('mouseover', e => {
    var slideup = '.title-display-hover-slideup-show a, .photonic-slideshow.title-display-hover-slideup-show li';

    if (e.target instanceof Element && e.target.closest(slideup)) {
      var node = e.target.closest(slideup);
      var title = node.querySelector('.photonic-title');
      Util.slideUpTitle(title, 'show');
      node.setAttribute('title', '');
    }
  }, true);
};

exports.addSlideUpEnterListener = addSlideUpEnterListener;

var addSlideUpLeaveListener = () => {
  document.addEventListener('mouseout', e => {
    var slideup = '.title-display-hover-slideup-show a, .photonic-slideshow.title-display-hover-slideup-show li';

    if (e.target instanceof Element && e.target.closest(slideup)) {
      var node = e.target.closest(slideup);
      var title = node.querySelector('.photonic-title');
      Util.slideUpTitle(title, 'hide');
      node.setAttribute('title', Util.getText(node.getAttribute('data-title')));
    }
  }, true);
};

exports.addSlideUpLeaveListener = addSlideUpLeaveListener;

var addLazyLoadListener = () => {
  var buttons = document.documentElement.querySelectorAll('input.photonic-show-gallery-button');
  Array.prototype.forEach.call(buttons, button => {
    button.addEventListener('click', Requests.lazyLoad);
  });
  buttons = document.documentElement.querySelectorAll('input.photonic-js-load-button');
  Array.prototype.forEach.call(buttons, button => {
    button.addEventListener('click', Requests.lazyLoad);
    button.click();
  });
};

exports.addLazyLoadListener = addLazyLoadListener;

var addAllListeners = () => {
  addLevel2ClickListener();
  addPasswordSubmitListener();
  addLevel3ExpandListener();
  addMoreButtonListener();
  addHelperMoreButtonListener();
  addSlideUpEnterListener();
  addSlideUpLeaveListener();
  addLazyLoadListener();
};

exports.addAllListeners = addAllListeners;

/***/ }),

/***/ "../include/js/front-end/src/Requests.js":
/*!***********************************************!*\
  !*** ../include/js/front-end/src/Requests.js ***!
  \***********************************************/
/*! flagged exports */
/*! export __esModule [provided] [no usage info] [missing usage info prevents renaming] */
/*! export displayLevel2 [provided] [no usage info] [missing usage info prevents renaming] */
/*! export lazyLoad [provided] [no usage info] [missing usage info prevents renaming] */
/*! export processL3Request [provided] [no usage info] [missing usage info prevents renaming] */
/*! export processRequest [provided] [no usage info] [missing usage info prevents renaming] */
/*! other exports [not provided] [no usage info] */
/*! runtime requirements: __webpack_exports__, __webpack_require__ */
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireWildcard = __webpack_require__(/*! @babel/runtime/helpers/interopRequireWildcard */ "../../../../../node_modules/@babel/runtime/helpers/interopRequireWildcard.js");

Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.lazyLoad = exports.processL3Request = exports.displayLevel2 = exports.processRequest = void 0;

var _Core = __webpack_require__(/*! ./Core */ "../include/js/front-end/src/Core.js");

var Util = _interopRequireWildcard(__webpack_require__(/*! ./Util */ "../include/js/front-end/src/Util.js"));

var _Justified = __webpack_require__(/*! ./Layouts/Justified */ "../include/js/front-end/src/Layouts/Justified.js");

var _Masonry = __webpack_require__(/*! ./Layouts/Masonry */ "../include/js/front-end/src/Layouts/Masonry.js");

var _Mosaic = __webpack_require__(/*! ./Layouts/Mosaic */ "../include/js/front-end/src/Layouts/Mosaic.js");

var _Tooltip = __webpack_require__(/*! ./Components/Tooltip */ "../include/js/front-end/src/Components/Tooltip.js");

var _Modal = __webpack_require__(/*! ./Components/Modal */ "../include/js/front-end/src/Components/Modal.js");

var spinners = 0;

var bypassPopup = data => {
  _Core.Core.hideLoading();

  var panel;

  if (data instanceof Element) {
    panel = data;
  } else {
    panel = Util.getElement(data).firstElementChild;
  }

  Util.hide(panel);
  var images = panel.querySelectorAll('img');
  images.forEach(image => {
    if ((image.getAttribute('src') === null || image.getAttribute('src') === '') && image.getAttribute('data-src') !== null) {
      image.setAttribute('src', image.getAttribute('data-src'));
      image.removeAttribute('data-src');
    }
  });
  document.body.appendChild(panel);

  _Core.Core.moveHTML5External();

  var lightbox = _Core.Core.getLightbox();

  if (lightbox !== undefined && lightbox !== null) {
    lightbox.initializeForNewContainer('#' + panel.getAttribute('id'));
  }

  var thumbs = panel.querySelectorAll('.photonic-lb');

  if (thumbs.length > 0) {
    _Core.Core.setDeep('#' + thumbs[0].getAttribute('data-photonic-deep'));

    var evt = new MouseEvent('click', {
      bubbles: true,
      cancelable: true,
      view: window
    }); // If cancelled, don't dispatch our event

    !thumbs[0].dispatchEvent(evt);
  }
};

var displayPopup = (data, provider, popup, panelId) => {
  var safePanelId = panelId.replace('.', '\\.'); // FOR EXISTING ELEMENTS WHICH NEED SANITIZED PANELID

  var div = Util.getElement(data).firstElementChild;
  var grid = div.querySelector('.modal-gallery');

  var doImageLayout = () => {
    var popupPanel = document.querySelector('#photonic-' + provider + '-' + popup + '-' + safePanelId);

    if (popupPanel) {
      popupPanel.appendChild(div);
      Util.show(popupPanel);
    }

    (0, _Modal.Modal)(div, {
      modalTarget: 'photonic-' + provider + '-panel-' + safePanelId,
      color: '#000',
      width: Photonic_JS.gallery_panel_width + '%',
      closeFromRight: (100 - Photonic_JS.gallery_panel_width) / 2 + '%'
    });

    _Core.Core.moveHTML5External();

    var lightbox = _Core.Core.getLightbox();

    if (lightbox !== undefined && lightbox !== null) {
      lightbox.initializeForNewContainer('#' + div.getAttribute('id'));
    }

    (0, _Tooltip.Tooltip)('[data-photonic-tooltip]', '.photonic-tooltip-container');

    _Core.Core.hideLoading();
  };

  if (grid.classList.contains('sizes-present')) {
    _Core.Core.watchForImages(grid);

    doImageLayout();
  } else {
    _Core.Core.waitForImages(grid).then(() => {
      doImageLayout();
    });
  }
};

var redisplayPopupContents = (provider, panelId, panel, args) => {
  var panelEl = Util.getElement(panel);

  if ('show' === args['popup']) {
    _Core.Core.hideLoading();

    (0, _Modal.Modal)(panelEl, {
      modalTarget: 'photonic-' + provider + '-panel-' + panelId,
      color: '#000',
      width: Photonic_JS.gallery_panel_width + '%',
      closeFromRight: (100 - Photonic_JS.gallery_panel_width) / 2 + '%'
    });
  } else {
    bypassPopup(document.getElementById('photonic-' + provider + '-panel-' + panelId));
  }
};

var processRequest = (provider, type, identifier, args) => {
  args['action'] = 'photonic_display_level_2_contents';
  Util.post(Photonic_JS.ajaxurl, args, function (data) {
    if (data.substr(0, Photonic_JS.password_failed.length) === Photonic_JS.password_failed) {
      _Core.Core.hideLoading();

      var prompter = '#photonic-' + provider + '-' + type + '-prompter-' + identifier;
      var prompterDialog = _Core.Core.prompterList[prompter];

      if (prompterDialog !== undefined && prompterDialog !== null) {
        prompterDialog.show();
      }
    } else {
      if ('show' === args['popup']) {
        displayPopup(data, provider, type, identifier);
      } else {
        if (data !== '') {
          bypassPopup(data);
        } else {
          _Core.Core.hideLoading();
        }
      }
    }
  });
};

exports.processRequest = processRequest;

var displayLevel2 = (provider, type, args) => {
  var identifier = args['panel_id'].substr(('photonic-' + provider + '-' + type + '-thumb-').length);
  var panel = '#photonic-' + provider + '-panel-' + identifier;
  var existing = document.getElementById('photonic-' + provider + '-panel-' + identifier);

  if (existing == null) {
    existing = document.getElementById(args['panel_id']);

    if (existing.classList.contains('photonic-' + provider + '-passworded')) {
      _Core.Core.initializePasswordPrompter("#photonic-".concat(provider, "-").concat(type, "-prompter-").concat(identifier));
    } else {
      _Core.Core.showSpinner();

      processRequest(provider, type, identifier, args);
    }
  } else {
    _Core.Core.showSpinner();

    redisplayPopupContents(provider, identifier, panel, args);
  }
};

exports.displayLevel2 = displayLevel2;

var processL3Request = (clicked, header, args) => {
  args['action'] = 'photonic_display_level_3_contents';

  _Core.Core.showSpinner();

  var lightbox = _Core.Core.getLightbox();

  Util.post(Photonic_JS.ajaxurl, args, function (data) {
    var insert = Util.getElement(data);

    if (header) {
      var layout = insert.querySelector('.photonic-level-2-container');
      var container = header.parentNode;
      var returnedStream = insert.firstElementChild;
      var collectionId = args.node.substr('flickr-collection-'.length);
      returnedStream.setAttribute('id', args.stream + '-' + collectionId);
      container.insertBefore(returnedStream, header.nextSibling);

      if (layout.classList.contains('photonic-random-layout')) {
        (0, _Justified.JustifiedGrid)(false, false, null, lightbox);
      } else if (layout.classList.contains('photonic-mosaic-layout')) {
        (0, _Mosaic.Mosaic)(false, false);
      } else if (layout.classList.contains('photonic-masonry-layout')) {
        (0, _Masonry.Masonry)(false, false);
      }

      var level2 = returnedStream.querySelectorAll('.photonic-level-2');
      level2.forEach(function (item) {
        item.style.display = 'inline-block';
      });
      (0, _Tooltip.Tooltip)('[data-photonic-tooltip]', '.photonic-tooltip-container');
      clicked.classList.remove('photonic-level-3-expand-plus');
      clicked.classList.add('photonic-level-3-expand-up');
      clicked.setAttribute('title', Photonic_JS.minimize_panel === undefined ? 'Hide' : Photonic_JS.minimize_panel);
    }

    _Core.Core.hideLoading();
  });
};

exports.processL3Request = processL3Request;

var lazyLoad = evt => {
  spinners++;

  _Core.Core.showSpinner();

  var clicked = evt.currentTarget;
  var shortcode = clicked.getAttribute('data-photonic-shortcode');
  var args = {
    'action': 'photonic_lazy_load',
    'shortcode': shortcode
  };

  var countdownSpinners = () => {
    spinners--;

    if (spinners <= 0) {
      _Core.Core.hideLoading();
    }
  };

  Util.post(Photonic_JS.ajaxurl, args, data => {
    var div = document.createElement('div');
    div.innerHTML = data;
    div = div.firstElementChild;

    if (div) {
      var divId = div.getAttribute('id');
      var divClass = divId.substring(0, divId.lastIndexOf('-'));
      var streams = document.documentElement.querySelectorAll('.' + divClass);
      var max = 0;
      streams.forEach(stream => {
        var streamId = stream.getAttribute('id');
        streamId = streamId.substring(streamId.lastIndexOf('-') + 1);
        streamId = parseInt(streamId, 10);
        max = Math.max(max, streamId);
      });
      max = max + 1;
      var regex = new RegExp(divId, 'gi');
      div.innerHTML = data.replace(regex, divClass + '-' + max).replace('photonic-slideshow-' + divId.substring(divId.lastIndexOf('-') + 1), 'photonic-slideshow-' + max);
      div = div.firstElementChild; // Level 2 elements get their own ids, which need to be readjusted because the back-end always assigns them a gallery_index of 1

      div.querySelectorAll('figure.photonic-level-2').forEach(figure => {
        if (figure.getAttribute('id') != null) {
          var figId = figure.getAttribute('id');
          var modId = figId.substring(0, figId.lastIndexOf('-') + 1) + max; // Replace last part of id with the "max"

          figure.setAttribute('id', modId);
          var anchor = figure.querySelector('a');

          if (anchor.getAttribute('id') != null) {
            var anchorId = anchor.getAttribute('id');
            var modAnchorId = anchorId.substring(0, anchorId.lastIndexOf('-') + 1) + max; // Replace last part of id with the "max"

            anchor.setAttribute('id', modAnchorId);
          }

          var prompter = figure.querySelector('.photonic-password-prompter');

          if (prompter != null && prompter.getAttribute('id') != null) {
            var prompterId = prompter.getAttribute('id');
            var modPrompterId = prompterId.substring(0, prompterId.lastIndexOf('-') + 1) + max; // Replace last part of id with the "max"

            prompter.setAttribute('id', modPrompterId);
          }
        }
      });
      clicked.insertAdjacentElement('afterend', div);
      var newDivId = divClass + '-' + max;

      var lightbox = _Core.Core.getLightbox();

      if (lightbox !== undefined && lightbox !== null) {
        lightbox.initializeForNewContainer('#' + div.getAttribute('id'));
      }

      if (document.querySelectorAll('#' + newDivId + ' .photonic-random-layout').length > 0) {
        (0, _Justified.JustifiedGrid)(false, true, '#' + newDivId + ' .photonic-random-layout', lightbox);
        spinners--;
      } else if (document.querySelectorAll('#' + newDivId + ' .photonic-masonry-layout').length > 0) {
        (0, _Masonry.Masonry)(false, true, '#' + newDivId + ' .photonic-masonry-layout');
        spinners--;
      } else if (document.querySelectorAll('#' + newDivId + ' .photonic-mosaic-layout').length > 0) {
        (0, _Mosaic.Mosaic)(false, true, '#' + newDivId + ' .photonic-mosaic-layout');
        spinners--;
      } // Slider(document.querySelector('#photonic-slideshow-' + max));


      if (div.classList.contains('sizes-present') || div.querySelector('.sizes-present') !== null) {
        _Core.Core.watchForImages(div);

        _Core.Core.standardizeTitleWidths();

        countdownSpinners();
      } else {
        _Core.Core.waitForImages(div).then(() => {
          var standard = document.documentElement.querySelectorAll('#' + newDivId + ' .photonic-standard-layout .photonic-level-1, ' + '#' + newDivId + ' .photonic-standard-layout .photonic-level-2');
          standard.forEach(image => {
            image.style.display = 'inline-block';
          });

          _Core.Core.standardizeTitleWidths();

          countdownSpinners();
        });
      }

      _Core.Core.moveHTML5External();

      clicked.parentNode.removeChild(clicked);
      (0, _Tooltip.Tooltip)('[data-photonic-tooltip]', '.photonic-tooltip-container');

      if (spinners <= 0) {
        _Core.Core.hideLoading();
      }
    }
  });
};

exports.lazyLoad = lazyLoad;

/***/ }),

/***/ "../include/js/front-end/src/Util.js":
/*!*******************************************!*\
  !*** ../include/js/front-end/src/Util.js ***!
  \*******************************************/
/*! flagged exports */
/*! export __esModule [provided] [no usage info] [missing usage info prevents renaming] */
/*! export fadeIn [provided] [no usage info] [missing usage info prevents renaming] */
/*! export fadeOut [provided] [no usage info] [missing usage info prevents renaming] */
/*! export get [provided] [no usage info] [missing usage info prevents renaming] */
/*! export getElement [provided] [no usage info] [missing usage info prevents renaming] */
/*! export getText [provided] [no usage info] [missing usage info prevents renaming] */
/*! export hasClass [provided] [no usage info] [missing usage info prevents renaming] */
/*! export hide [provided] [no usage info] [missing usage info prevents renaming] */
/*! export next [provided] [no usage info] [missing usage info prevents renaming] */
/*! export post [provided] [no usage info] [missing usage info prevents renaming] */
/*! export show [provided] [no usage info] [missing usage info prevents renaming] */
/*! export slideUpDown [provided] [no usage info] [missing usage info prevents renaming] */
/*! export slideUpTitle [provided] [no usage info] [missing usage info prevents renaming] */
/*! other exports [not provided] [no usage info] */
/*! runtime requirements: __webpack_exports__ */
/***/ ((__unused_webpack_module, exports) => {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.hide = exports.show = exports.fadeOut = exports.fadeIn = exports.slideUpTitle = exports.slideUpDown = exports.getText = exports.getElement = exports.next = exports.get = exports.post = exports.hasClass = void 0;

// Utilities for Photonic
var hasClass = (element, className) => {
  if (element.classList) {
    return element.classList.contains(className);
  } else {
    return new RegExp('(^| )' + className + '( |$)', 'gi').test(element.className);
  }
};

exports.hasClass = hasClass;

function ajax(method, url, args, callback) {
  var xhr = new XMLHttpRequest();
  xhr.open(method, url);

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var data = xhr.responseText;
        callback(data);
      }
    }
  };

  var form = new FormData();

  for (var [key, value] of Object.entries(args)) {
    form.append(key, value);
  }

  xhr.send(form);
}

var post = (url, args, callback) => {
  ajax('POST', url, args, callback);
};

exports.post = post;

var get = (url, args, callback) => {
  ajax('GET', url, args, callback);
};

exports.get = get;

var next = (elem, selector) => {
  var sibling = elem.nextElementSibling;
  if (!selector) return sibling;

  while (sibling) {
    if (sibling.matches(selector)) return sibling;
    sibling = sibling.nextElementSibling;
  }
};

exports.next = next;

var getElement = value => {
  var parser = new DOMParser();
  var doc = parser.parseFromString(value, 'text/html');
  return doc.body;
};

exports.getElement = getElement;

var getText = value => {
  var txt = document.createElement("div");
  txt.innerHTML = value;
  return txt.innerText;
};

exports.getText = getText;

var slideUpDown = (element, state) => {
  if (element != null && element.classList) {
    if (!element.classList.contains('photonic-can-slide')) {
      element.classList.add('photonic-can-slide');
    }

    if ('show' === state) {
      element.classList.remove('photonic-can-slide-hide');
      element.style.height = "".concat(element.scrollHeight, "px");
    } else {
      element.classList.add('photonic-can-slide-hide');
      element.style.height = 0;
    }
  }
};

exports.slideUpDown = slideUpDown;

var slideUpTitle = (element, state) => {
  if (element && element.classList) {
    if ('show' === state) {
      var currentPadding = 0;

      if (element.offsetHeight) {
        currentPadding = parseInt(getComputedStyle(element).paddingTop.slice(0, -2)) * 2;
      }

      element.style.height = element.scrollHeight + 6 - currentPadding + 'px';
      element.classList.add('slideup-show');
    } else {
      element.style.height = '';
      element.classList.remove('slideup-show');
    }
  }
};

exports.slideUpTitle = slideUpTitle;

var fadeIn = el => {
  if (!hasClass(el, 'fade-in')) {
    el.style.display = 'block';
    el.style.visibility = 'visible';
    el.classList.add('fade-in');
  }
};

exports.fadeIn = fadeIn;

var fadeOut = (el, duration) => {
  var s = el.style,
      step = 25 / (duration || 500);
  s.opacity = s.opacity || 1;

  (function fade() {
    s.opacity -= step;

    if (s.opacity < 0) {
      s.display = "none";
      el.classList.remove('fade-in');
    } else {
      setTimeout(fade, 25);
    }
  })();
}; // get the default display style of an element


exports.fadeOut = fadeOut;

var defaultDisplay = tag => {
  var iframe = document.createElement('iframe');
  iframe.setAttribute('frameborder', 0);
  iframe.setAttribute('width', 0);
  iframe.setAttribute('height', 0);
  document.documentElement.appendChild(iframe);
  var doc = (iframe.contentWindow || iframe.contentDocument).document; // IE support

  doc.write();
  doc.close();
  var testEl = doc.createElement(tag);
  doc.documentElement.appendChild(testEl);
  var display = (window.getComputedStyle ? getComputedStyle(testEl, null) : testEl.currentStyle).display;
  iframe.parentNode.removeChild(iframe);
  return display;
}; // actual show/hide function used by show() and hide() below


var showHide = (el, show) => {
  var value = el.getAttribute('data-olddisplay'),
      display = el.style.display,
      computedDisplay = (window.getComputedStyle ? getComputedStyle(el, null) : el.currentStyle).display;

  if (show) {
    if (!value && display === 'none') el.style.display = '';
    if (el.style.display === '' && computedDisplay === 'none') value = value || defaultDisplay(el.nodeName);
  } else {
    if (display && display !== 'none' || !(computedDisplay === 'none')) el.setAttribute('data-olddisplay', computedDisplay === 'none' ? display : computedDisplay);
  }

  if (!show || el.style.display === 'none' || el.style.display === '') el.style.display = show ? value || '' : 'none';
}; // helper functions


var show = el => showHide(el, true);

exports.show = show;

var hide = el => showHide(el);

exports.hide = hide;

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		if(__webpack_module_cache__[moduleId]) {
/******/ 			return __webpack_module_cache__[moduleId].exports;
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
(() => {
/*!*******************************************************!*\
  !*** ../include/js/front-end/src/Entries/Magnific.js ***!
  \*******************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: __webpack_require__ */


var _interopRequireWildcard = __webpack_require__(/*! @babel/runtime/helpers/interopRequireWildcard */ "../../../../../node_modules/@babel/runtime/helpers/interopRequireWildcard.js");

var _Core = __webpack_require__(/*! ../Core */ "../include/js/front-end/src/Core.js");

var _Magnific = __webpack_require__(/*! ../Lightboxes/Magnific */ "../include/js/front-end/src/Lightboxes/Magnific.js");

var Listeners = _interopRequireWildcard(__webpack_require__(/*! ../Listeners */ "../include/js/front-end/src/Listeners.js"));

var Layout = _interopRequireWildcard(__webpack_require__(/*! ../Layouts/Layout */ "../include/js/front-end/src/Layouts/Layout.js"));

jQuery(document).ready(function ($) {
  var lightbox = new _Magnific.PhotonicMagnific($);

  _Core.Core.setLightbox(lightbox);

  lightbox.initialize('.photonic-standard-layout, .photonic-random-layout, .photonic-mosaic-layout, .photonic-masonry-layout');
  lightbox.initializeSolos();

  _Core.Core.executeCommon();

  Listeners.addAllListeners();
  Layout.initializeLayouts(lightbox);
});
})();

/******/ })()
;