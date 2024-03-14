/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./admin/assets/js/wp-media.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./admin/assets/js/controllers/playlists.js":
/*!**************************************************!*\
  !*** ./admin/assets/js/controllers/playlists.js ***!
  \**************************************************/
/*! exports provided: PlaylistsController */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "PlaylistsController", function() { return PlaylistsController; });
/* harmony import */ var backbone__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! backbone */ "backbone");
/* harmony import */ var backbone__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(backbone__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var cue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! cue */ "./admin/assets/js/modules/application.js");



const PlaylistsController = wp__WEBPACK_IMPORTED_MODULE_1___default.a.media.controller.State.extend({
  defaults: {
    id: 'cue-playlists',
    title: cue__WEBPACK_IMPORTED_MODULE_2__["l10n"].insertPlaylist || 'Insert Playlist',
    collection: null,
    content: 'cue-playlist-browser',
    menu: 'default',
    menuItem: {
      text: cue__WEBPACK_IMPORTED_MODULE_2__["l10n"].insertFromCue || 'Insert from Cue',
      priority: 130
    },
    selection: null,
    toolbar: 'cue-insert-playlist'
  },
  initialize: function (options) {
    const collection = options.collection || new backbone__WEBPACK_IMPORTED_MODULE_0___default.a.Collection();
    const selection = options.selection || new backbone__WEBPACK_IMPORTED_MODULE_0___default.a.Collection();
    this.set('attributes', new backbone__WEBPACK_IMPORTED_MODULE_0___default.a.Model({
      id: null,
      show_playlist: true
    }));
    this.set('collection', collection);
    this.set('selection', selection);
  }
});

/***/ }),

/***/ "./admin/assets/js/modules/application.js":
/*!************************************************!*\
  !*** ./admin/assets/js/modules/application.js ***!
  \************************************************/
/*! exports provided: default, l10n, settings */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(global) {/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "l10n", function() { return l10n; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "settings", function() { return settings; });
class Application {
  constructor() {
    this.l10n = {};
    this.settings = {};
  }

  config(settings) {
    if (settings.l10n) {
      this.l10n = Object.assign(this.l10n, settings.l10n);
      delete settings.l10n;
    }

    this.settings = Object.assign(this.settings, settings);
  }

}

global.cue = global.cue || new Application();
/* harmony default export */ __webpack_exports__["default"] = (global.cue);
const l10n = global.cue.l10n;
const settings = global.cue.settings;
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../../node_modules/webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./admin/assets/js/views/frame/content/browser/no-items.js":
/*!*****************************************************************!*\
  !*** ./admin/assets/js/views/frame/content/browser/no-items.js ***!
  \*****************************************************************/
/*! exports provided: NoItems */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "NoItems", function() { return NoItems; });
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_0__);

const NoItems = wp__WEBPACK_IMPORTED_MODULE_0___default.a.Backbone.View.extend({
  className: 'cue-playlist-browser-empty',
  tagName: 'div',
  template: wp__WEBPACK_IMPORTED_MODULE_0___default.a.template('cue-playlist-browser-empty'),
  initialize: function (options) {
    this.collection = this.collection;
    this.listenTo(this.collection, 'add remove reset', this.toggleVisibility);
  },
  render: function () {
    this.$el.html(this.template());
    return this;
  },
  toggleVisibility: function () {
    this.$el.toggleClass('is-visible', this.collection.length < 1);
  }
});

/***/ }),

/***/ "./admin/assets/js/views/frame/content/browser/playlist.js":
/*!*****************************************************************!*\
  !*** ./admin/assets/js/views/frame/content/browser/playlist.js ***!
  \*****************************************************************/
/*! exports provided: Playlist */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Playlist", function() { return Playlist; });
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_0__);

const Playlist = wp__WEBPACK_IMPORTED_MODULE_0___default.a.Backbone.View.extend({
  tagName: 'li',
  className: 'cue-playlist-browser-list-item',
  template: wp__WEBPACK_IMPORTED_MODULE_0___default.a.template('cue-playlist-browser-list-item'),
  events: {
    'click': 'resetSelection'
  },
  initialize: function (options) {
    this.controller = options.controller;
    this.model = options.model;
    this.selection = this.controller.state().get('selection');
    this.listenTo(this.selection, 'add remove reset', this.updateSelectedClass);
  },
  render: function () {
    this.$el.html(this.template(this.model.toJSON()));
    return this;
  },
  resetSelection: function () {
    if (this.selection.contains(this.model)) {
      this.selection.remove(this.model);
    } else {
      this.selection.reset(this.model);
    }
  },
  updateSelectedClass: function () {
    if (this.selection.findWhere({
      id: this.model.get('id')
    })) {
      this.$el.addClass('is-selected');
    } else {
      this.$el.removeClass('is-selected');
    }
  }
});

/***/ }),

/***/ "./admin/assets/js/views/frame/content/browser/playlists.js":
/*!******************************************************************!*\
  !*** ./admin/assets/js/views/frame/content/browser/playlists.js ***!
  \******************************************************************/
/*! exports provided: Playlists */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Playlists", function() { return Playlists; });
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _playlist__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./playlist */ "./admin/assets/js/views/frame/content/browser/playlist.js");


const Playlists = wp__WEBPACK_IMPORTED_MODULE_0___default.a.Backbone.View.extend({
  className: 'cue-playlist-browser-list',
  tagName: 'ul',
  initialize: function (options) {
    this.collection = options.controller.state().get('collection');
    this.controller = options.controller;
    this.listenTo(this.collection, 'add', this.addItem);
    this.listenTo(this.collection, 'reset', this.render);
  },
  render: function () {
    this.collection.each(this.addItem, this);
    return this;
  },
  addItem: function (model) {
    const view = new _playlist__WEBPACK_IMPORTED_MODULE_1__["Playlist"]({
      controller: this.controller,
      model: model
    }).render();
    this.$el.append(view.el);
  }
});

/***/ }),

/***/ "./admin/assets/js/views/frame/content/browser/sidebar.js":
/*!****************************************************************!*\
  !*** ./admin/assets/js/views/frame/content/browser/sidebar.js ***!
  \****************************************************************/
/*! exports provided: Sidebar */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Sidebar", function() { return Sidebar; });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_1__);


const Sidebar = wp__WEBPACK_IMPORTED_MODULE_1___default.a.Backbone.View.extend({
  className: 'cue-playlist-browser-sidebar media-sidebar',
  template: wp__WEBPACK_IMPORTED_MODULE_1___default.a.template('cue-playlist-browser-sidebar'),
  events: {
    'change [data-setting]': 'updateAttribute'
  },
  initialize: function (options) {
    this.attributes = options.controller.state().get('attributes');
  },
  render: function () {
    this.$el.html(this.template());
  },
  updateAttribute: function (e) {
    const $target = jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target);
    const attribute = $target.data('setting');
    let value = e.target.value;

    if ('checkbox' === e.target.type) {
      value = !!$target.prop('checked');
    }

    this.attributes.set(attribute, value);
  }
});

/***/ }),

/***/ "./admin/assets/js/views/frame/content/playlist-browser.js":
/*!*****************************************************************!*\
  !*** ./admin/assets/js/views/frame/content/playlist-browser.js ***!
  \*****************************************************************/
/*! exports provided: PlaylistBrowser */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "PlaylistBrowser", function() { return PlaylistBrowser; });
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _browser_no_items__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./browser/no-items */ "./admin/assets/js/views/frame/content/browser/no-items.js");
/* harmony import */ var _browser_playlists__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./browser/playlists */ "./admin/assets/js/views/frame/content/browser/playlists.js");
/* harmony import */ var _browser_sidebar__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./browser/sidebar */ "./admin/assets/js/views/frame/content/browser/sidebar.js");




const PlaylistBrowser = wp__WEBPACK_IMPORTED_MODULE_0___default.a.Backbone.View.extend({
  className: 'cue-playlist-browser',
  initialize: function (options) {
    this.collection = options.controller.state().get('collection');
    this.controller = options.controller;
    this._paged = 1;
    this._pending = false;
    this.scroll = this.scroll.bind(this);
    this.listenTo(this.collection, 'reset', this.render);

    if (!this.collection.length) {
      this.getPlaylists();
    }
  },
  render: function () {
    this.$el.off('scroll').on('scroll', this.scroll);
    this.views.add([new _browser_playlists__WEBPACK_IMPORTED_MODULE_2__["Playlists"]({
      collection: this.collection,
      controller: this.controller
    }), new _browser_sidebar__WEBPACK_IMPORTED_MODULE_3__["Sidebar"]({
      controller: this.controller
    }), new _browser_no_items__WEBPACK_IMPORTED_MODULE_1__["NoItems"]({
      collection: this.collection
    })]);
    return this;
  },
  scroll: function () {
    if (!this._pending && this.el.scrollHeight < this.el.scrollTop + this.el.clientHeight * 3) {
      this._pending = true;
      this.getPlaylists();
    }
  },
  getPlaylists: function () {
    wp__WEBPACK_IMPORTED_MODULE_0___default.a.ajax.post('cue_get_playlists', {
      paged: this._paged
    }).done(response => {
      this.collection.add(response.playlists);
      this._paged++;

      if (this._paged <= response.maxNumPages) {
        this._pending = false;
        this.scroll();
      }
    });
  }
});

/***/ }),

/***/ "./admin/assets/js/views/frame/insert-playlist.js":
/*!********************************************************!*\
  !*** ./admin/assets/js/views/frame/insert-playlist.js ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _toolbar_insert_playlist__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./toolbar/insert-playlist */ "./admin/assets/js/views/frame/toolbar/insert-playlist.js");
/* harmony import */ var _content_playlist_browser__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./content/playlist-browser */ "./admin/assets/js/views/frame/content/playlist-browser.js");
/* harmony import */ var _controllers_playlists__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../controllers/playlists */ "./admin/assets/js/controllers/playlists.js");




const {
  Post: PostFrame
} = wp__WEBPACK_IMPORTED_MODULE_0___default.a.media.view.MediaFrame;
/* harmony default export */ __webpack_exports__["default"] = (PostFrame.extend({
  createStates: function () {
    PostFrame.prototype.createStates.apply(this, arguments);
    this.states.add(new _controllers_playlists__WEBPACK_IMPORTED_MODULE_3__["PlaylistsController"]({}));
  },
  bindHandlers: function () {
    PostFrame.prototype.bindHandlers.apply(this, arguments);
    this.on('content:create:cue-playlist-browser', this.createCueContent, this);
    this.on('toolbar:create:cue-insert-playlist', this.createCueToolbar, this);
  },
  createCueContent: function (content) {
    content.view = new _content_playlist_browser__WEBPACK_IMPORTED_MODULE_2__["PlaylistBrowser"]({
      controller: this
    });
  },
  createCueToolbar: function (toolbar) {
    toolbar.view = new _toolbar_insert_playlist__WEBPACK_IMPORTED_MODULE_1__["InsertPlaylistToolbar"]({
      controller: this
    });
  }
}));

/***/ }),

/***/ "./admin/assets/js/views/frame/toolbar/insert-playlist.js":
/*!****************************************************************!*\
  !*** ./admin/assets/js/views/frame/toolbar/insert-playlist.js ***!
  \****************************************************************/
/*! exports provided: InsertPlaylistToolbar */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "InsertPlaylistToolbar", function() { return InsertPlaylistToolbar; });
/* harmony import */ var underscore__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! underscore */ "underscore");
/* harmony import */ var underscore__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(underscore__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_1__);


const {
  l10n,
  Toolbar
} = wp__WEBPACK_IMPORTED_MODULE_1___default.a.media.view;
const InsertPlaylistToolbar = Toolbar.extend({
  initialize: function (options) {
    this.controller = options.controller;
    this.insert = this.insert.bind(this); // This is a button.

    this.options.items = underscore__WEBPACK_IMPORTED_MODULE_0___default.a.defaults(this.options.items || {}, {
      insert: {
        text: l10n.insertIntoPost || 'Insert into post',
        style: 'primary',
        priority: 80,
        requires: {
          selection: true
        },
        click: this.insert
      }
    });
    Toolbar.prototype.initialize.apply(this, arguments);
  },
  insert: function () {
    const state = this.controller.state();
    const attributes = state.get('attributes').toJSON();
    const selection = state.get('selection').first();
    attributes.id = selection.get('id');

    underscore__WEBPACK_IMPORTED_MODULE_0___default.a.pick(attributes, 'id', 'theme', 'width', 'show_playlist');

    if (!attributes.show_playlist) {
      attributes.show_playlist = '0';
    } else {
      delete attributes.show_playlist;
    }

    const html = wp__WEBPACK_IMPORTED_MODULE_1___default.a.shortcode.string({
      tag: 'cue',
      type: 'single',
      attrs: attributes
    });
    wp__WEBPACK_IMPORTED_MODULE_1___default.a.media.editor.insert(html);
    this.controller.close();
  }
});

/***/ }),

/***/ "./admin/assets/js/wp-media.js":
/*!*************************************!*\
  !*** ./admin/assets/js/wp-media.js ***!
  \*************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var cue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! cue */ "./admin/assets/js/modules/application.js");
/* harmony import */ var _views_frame_insert_playlist__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./views/frame/insert-playlist */ "./admin/assets/js/views/frame/insert-playlist.js");
/* global _cueMediaSettings */



cue__WEBPACK_IMPORTED_MODULE_1__["default"].config(_cueMediaSettings);
wp__WEBPACK_IMPORTED_MODULE_0___default.a.media.view.MediaFrame.Post = _views_frame_insert_playlist__WEBPACK_IMPORTED_MODULE_2__["default"];

/***/ }),

/***/ "./node_modules/webpack/buildin/global.js":
/*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || new Function("return this")();
} catch (e) {
	// This works if the window reference is available
	if (typeof window === "object") g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),

/***/ "backbone":
/*!***************************!*\
  !*** external "Backbone" ***!
  \***************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = Backbone;

/***/ }),

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = jQuery;

/***/ }),

/***/ "underscore":
/*!********************!*\
  !*** external "_" ***!
  \********************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = _;

/***/ }),

/***/ "wp":
/*!*********************!*\
  !*** external "wp" ***!
  \*********************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp;

/***/ })

/******/ });
//# sourceMappingURL=wp-media.bundle.js.map
