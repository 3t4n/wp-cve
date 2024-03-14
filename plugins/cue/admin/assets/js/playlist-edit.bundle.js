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
/******/ 	return __webpack_require__(__webpack_require__.s = "./admin/assets/js/playlist-edit.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./admin/assets/js/collections/tracks.js":
/*!***********************************************!*\
  !*** ./admin/assets/js/collections/tracks.js ***!
  \***********************************************/
/*! exports provided: Tracks */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Tracks", function() { return Tracks; });
/* harmony import */ var backbone__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! backbone */ "backbone");
/* harmony import */ var backbone__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(backbone__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var cue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! cue */ "./admin/assets/js/modules/application.js");
/* harmony import */ var _models_track__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../models/track */ "./admin/assets/js/models/track.js");




const Tracks = backbone__WEBPACK_IMPORTED_MODULE_0___default.a.Collection.extend({
  model: _models_track__WEBPACK_IMPORTED_MODULE_3__["Track"],
  comparator: function (track) {
    return parseInt(track.get('order'), 10);
  },
  fetch: function () {
    return wp__WEBPACK_IMPORTED_MODULE_1___default.a.ajax.post('cue_get_playlist_tracks', {
      post_id: cue__WEBPACK_IMPORTED_MODULE_2__["settings"].postId
    }).done(tracks => this.reset(tracks));
  },
  save: function (data) {
    this.sort();
    data = Object.assign({}, data, {
      post_id: cue__WEBPACK_IMPORTED_MODULE_2__["settings"].postId,
      tracks: this.toJSON(),
      nonce: cue__WEBPACK_IMPORTED_MODULE_2__["settings"].saveNonce
    });
    return wp__WEBPACK_IMPORTED_MODULE_1___default.a.ajax.post('cue_save_playlist_tracks', data);
  }
});

/***/ }),

/***/ "./admin/assets/js/models/track.js":
/*!*****************************************!*\
  !*** ./admin/assets/js/models/track.js ***!
  \*****************************************/
/*! exports provided: Track */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Track", function() { return Track; });
/* harmony import */ var backbone__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! backbone */ "backbone");
/* harmony import */ var backbone__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(backbone__WEBPACK_IMPORTED_MODULE_0__);

const Track = backbone__WEBPACK_IMPORTED_MODULE_0___default.a.Model.extend({
  defaults: {
    artist: '',
    artworkId: '',
    artworkUrl: '',
    audioId: '',
    audioUrl: '',
    format: '',
    length: '',
    title: '',
    order: 0
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

/***/ "./admin/assets/js/modules/workflows.js":
/*!**********************************************!*\
  !*** ./admin/assets/js/modules/workflows.js ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var underscore__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! underscore */ "underscore");
/* harmony import */ var underscore__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(underscore__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var cue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! cue */ "./admin/assets/js/modules/application.js");
/* harmony import */ var _views_frame_track_media__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../views/frame/track-media */ "./admin/assets/js/views/frame/track-media.js");




const {
  Attachment
} = wp__WEBPACK_IMPORTED_MODULE_1___default.a.media.model;
const {
  l10n
} = cue__WEBPACK_IMPORTED_MODULE_2__["default"];
/* harmony default export */ __webpack_exports__["default"] = ({
  frames: [],
  model: {},

  /**
   * Set a model for the current workflow.
   *
   * @param {Object} frame
   */
  setModel: function (model) {
    this.model = model;
    return this;
  },

  /**
   * Retrieve or create a frame instance for a particular workflow.
   *
   * @param {string} id Frame identifer.
   */
  get: function (id) {
    const method = '_' + id;
    let frame = this.frames[method] || null; // Always call the frame method to perform any routine set up. The
    // frame method should short-circuit before being initialized again.

    frame = this[method].call(this, frame); // Store the frame for future use.

    this.frames[method] = frame;
    return frame;
  },

  /**
   * Workflow for adding tracks to the playlist.
   *
   * @param {Object} frame
   */
  _addTracks: function (frame) {
    if (frame) {
      return frame; // Return the existing frame for this workflow.
    } // Initialize the audio frame.


    frame = new _views_frame_track_media__WEBPACK_IMPORTED_MODULE_3__["TrackMediaFrame"]({
      title: l10n.workflows.addTracks.frameTitle,
      library: {
        type: 'audio'
      },
      button: {
        text: l10n.workflows.addTracks.frameButtonText
      },
      multiple: 'add'
    }); // Set the extensions that can be uploaded.

    frame.uploader.options.uploader.plupload = {
      filters: {
        mime_types: [{
          title: l10n.workflows.addTracks.fileTypes,
          extensions: 'm4a,mp3,ogg,wma'
        }]
      }
    }; // Prevent the Embed controller scanner from changing the state.

    frame.state('embed').props.off('change:url', frame.state('embed').debouncedScan); // Insert each selected attachment as a new track model.

    frame.state('insert').on('insert', function (selection) {
      underscore__WEBPACK_IMPORTED_MODULE_0___default.a.each(selection.models, function (attachment) {
        cue__WEBPACK_IMPORTED_MODULE_2__["default"].tracks.push(attachment.toJSON().cue);
      });
    }); // Insert the embed data as a new model.

    frame.state('embed').on('select', function () {
      const embed = this.props.toJSON();
      const track = {
        audioId: '',
        audioUrl: embed.url
      };

      if ('title' in embed && '' !== embed.title) {
        track.title = embed.title;
      }

      cue__WEBPACK_IMPORTED_MODULE_2__["default"].tracks.push(track);
    });
    return frame;
  },

  /**
   * Workflow for selecting track artwork image.
   *
   * @param {Object} frame
   */
  _selectArtwork: function (frame) {
    const workflow = this; // Return existing frame for this workflow.

    if (frame) {
      return frame;
    } // Initialize the artwork frame.


    frame = wp__WEBPACK_IMPORTED_MODULE_1___default.a.media({
      title: l10n.workflows.selectArtwork.frameTitle,
      library: {
        type: 'image'
      },
      button: {
        text: l10n.workflows.selectArtwork.frameButtonText
      },
      multiple: false
    }); // Set the extensions that can be uploaded.

    frame.uploader.options.uploader.plupload = {
      filters: {
        mime_types: [{
          files: l10n.workflows.selectArtwork.fileTypes,
          extensions: 'jpg,jpeg,gif,png'
        }]
      }
    }; // Automatically select the existing artwork if possible.

    frame.on('open', function () {
      const selection = this.get('library').get('selection');
      const artworkId = workflow.model.get('artworkId');
      const attachments = [];

      if (artworkId) {
        attachments.push(Attachment.get(artworkId));
        attachments[0].fetch();
      }

      selection.reset(attachments);
    }); // Set the model's artwork ID and url properties.

    frame.state('library').on('select', function () {
      const attachment = this.get('selection').first().toJSON();
      workflow.model.set({
        artworkId: attachment.id,
        artworkUrl: attachment.sizes.cue.url
      });
    });
    return frame;
  },

  /**
   * Workflow for selecting track audio.
   *
   * @param {Object} frame
   */
  _selectAudio: function (frame) {
    const workflow = this; // Return the existing frame for this workflow.

    if (frame) {
      return frame;
    } // Initialize the audio frame.


    frame = new _views_frame_track_media__WEBPACK_IMPORTED_MODULE_3__["TrackMediaFrame"]({
      title: l10n.workflows.selectAudio.frameTitle,
      library: {
        type: 'audio'
      },
      button: {
        text: l10n.workflows.selectAudio.frameButtonText
      },
      multiple: false
    }); // Set the extensions that can be uploaded.

    frame.uploader.options.uploader.plupload = {
      filters: {
        mime_types: [{
          title: l10n.workflows.selectAudio.fileTypes,
          extensions: 'm4a,mp3,ogg,wma'
        }]
      }
    }; // Prevent the Embed controller scanner from changing the state.

    frame.state('embed').props.off('change:url', frame.state('embed').debouncedScan); // Set the frame state when opening it.

    frame.on('open', function () {
      const selection = this.get('insert').get('selection');
      const audioId = workflow.model.get('audioId');
      const audioUrl = workflow.model.get('audioUrl');
      const isEmbed = audioUrl && !audioId;
      const attachments = []; // Automatically select the existing audio file if possible.

      if (audioId) {
        attachments.push(Attachment.get(audioId));
        attachments[0].fetch();
      }

      selection.reset(attachments); // Set the embed state properties.

      if (isEmbed) {
        this.get('embed').props.set({
          url: audioUrl,
          title: workflow.model.get('title')
        });
      } else {
        this.get('embed').props.set({
          url: '',
          title: ''
        });
      } // Set the state to 'embed' if the model has an audio URL but
      // not a corresponding attachment ID.


      frame.setState(isEmbed ? 'embed' : 'insert');
    }); // Copy data from the selected attachment to the current model.

    frame.state('insert').on('insert', function (selection) {
      const attachment = selection.first().toJSON().cue;
      const data = {};

      const keys = underscore__WEBPACK_IMPORTED_MODULE_0___default.a.keys(workflow.model.attributes); // Attributes that shouldn't be updated when inserting an
      // audio attachment.


      underscore__WEBPACK_IMPORTED_MODULE_0___default.a.without(keys, ['id', 'order']); // Update these attributes if they're empty.
      // They shouldn't overwrite any data entered by the user.


      underscore__WEBPACK_IMPORTED_MODULE_0___default.a.each(keys, function (key) {
        const value = workflow.model.get(key);

        if (!value && key in attachment && value !== attachment[key]) {
          data[key] = attachment[key];
        }
      }); // Attributes that should always be replaced.


      data.audioId = attachment.audioId;
      data.audioUrl = attachment.audioUrl;
      workflow.model.set(data);
    }); // Copy the embed data to the current model.

    frame.state('embed').on('select', function () {
      const embed = this.props.toJSON();
      const data = {};
      data.audioId = '';
      data.audioUrl = embed.url;

      if ('title' in embed && '' !== embed.title) {
        data.title = embed.title;
      }

      workflow.model.set(data);
    }); // Remove an empty model if the frame is escaped.

    frame.on('escape', function () {
      const model = workflow.model.toJSON();

      if (!model.artworkUrl && !model.audioUrl) {
        workflow.model.destroy();
      }
    });
    return frame;
  }
});

/***/ }),

/***/ "./admin/assets/js/playlist-edit.js":
/*!******************************************!*\
  !*** ./admin/assets/js/playlist-edit.js ***!
  \******************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var cue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! cue */ "./admin/assets/js/modules/application.js");
/* harmony import */ var _views_screen_edit_playlist__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./views/screen/edit-playlist */ "./admin/assets/js/views/screen/edit-playlist.js");
/* harmony import */ var _models_track__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./models/track */ "./admin/assets/js/models/track.js");
/* harmony import */ var _collections_tracks__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./collections/tracks */ "./admin/assets/js/collections/tracks.js");
/* harmony import */ var _modules_workflows__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./modules/workflows */ "./admin/assets/js/modules/workflows.js");
/* global _cueSettings */







cue__WEBPACK_IMPORTED_MODULE_2__["default"].data = _cueSettings; // Back-compat.

cue__WEBPACK_IMPORTED_MODULE_2__["default"].config(_cueSettings);
wp__WEBPACK_IMPORTED_MODULE_1___default.a.media.view.settings.post.id = cue__WEBPACK_IMPORTED_MODULE_2__["default"].data.postId;
wp__WEBPACK_IMPORTED_MODULE_1___default.a.media.view.settings.defaultProps = {};
Object.assign(cue__WEBPACK_IMPORTED_MODULE_2__["default"], {
  collection: {},
  controller: {},
  model: {},
  view: {}
});
cue__WEBPACK_IMPORTED_MODULE_2__["default"].model.Track = _models_track__WEBPACK_IMPORTED_MODULE_4__["Track"];
cue__WEBPACK_IMPORTED_MODULE_2__["default"].model.Tracks = _collections_tracks__WEBPACK_IMPORTED_MODULE_5__["Tracks"];
cue__WEBPACK_IMPORTED_MODULE_2__["default"].workflows = _modules_workflows__WEBPACK_IMPORTED_MODULE_6__["default"];
/**
 * ========================================================================
 * SETUP
 * ========================================================================
 */

jquery__WEBPACK_IMPORTED_MODULE_0___default()(function ($) {
  const tracks = new cue__WEBPACK_IMPORTED_MODULE_2__["default"].model.Tracks(cue__WEBPACK_IMPORTED_MODULE_2__["default"].data.tracks);
  cue__WEBPACK_IMPORTED_MODULE_2__["default"].tracks = tracks;
  delete cue__WEBPACK_IMPORTED_MODULE_2__["default"].data.tracks;
  const screen = new _views_screen_edit_playlist__WEBPACK_IMPORTED_MODULE_3__["EditPlaylistScreen"]({
    el: '#post',
    collection: tracks,
    l10n: cue__WEBPACK_IMPORTED_MODULE_2__["default"].l10n
  });
  screen.render();
});

/***/ }),

/***/ "./admin/assets/js/views/edit-playlist/track-list.js":
/*!***********************************************************!*\
  !*** ./admin/assets/js/views/edit-playlist/track-list.js ***!
  \***********************************************************/
/*! exports provided: TrackList */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "TrackList", function() { return TrackList; });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var underscore__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! underscore */ "underscore");
/* harmony import */ var underscore__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(underscore__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _track__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./track */ "./admin/assets/js/views/edit-playlist/track/index.js");




const TrackList = wp__WEBPACK_IMPORTED_MODULE_2___default.a.Backbone.View.extend({
  className: 'cue-tracklist',
  tagName: 'ol',
  initialize: function () {
    this.listenTo(this.collection, 'add', this.addTrack);
    this.listenTo(this.collection, 'add remove', this.updateOrder);
    this.listenTo(this.collection, 'reset', this.render);
  },
  render: function () {
    this.$el.empty();
    this.collection.each(this.addTrack, this);
    this.updateOrder();
    this.$el.sortable({
      axis: 'y',
      delay: 150,
      forceHelperSize: true,
      forcePlaceholderSize: true,
      opacity: 0.6,
      start: (e, ui) => {
        ui.placeholder.css('visibility', 'visible');
      },
      update: (e, ui) => {
        this.updateOrder();
      }
    });
    return this;
  },
  addTrack: function (track) {
    const trackView = new _track__WEBPACK_IMPORTED_MODULE_3__["Track"]({
      model: track
    });
    this.$el.append(trackView.render().el);
  },
  updateOrder: function () {
    underscore__WEBPACK_IMPORTED_MODULE_1___default.a.each(this.$el.find('.cue-track'), (item, i) => {
      const cid = jquery__WEBPACK_IMPORTED_MODULE_0___default()(item).data('cid');
      this.collection.get(cid).set('order', i);
    });
  }
});

/***/ }),

/***/ "./admin/assets/js/views/edit-playlist/track/artwork.js":
/*!**************************************************************!*\
  !*** ./admin/assets/js/views/edit-playlist/track/artwork.js ***!
  \**************************************************************/
/*! exports provided: TrackArtwork */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "TrackArtwork", function() { return TrackArtwork; });
/* harmony import */ var underscore__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! underscore */ "underscore");
/* harmony import */ var underscore__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(underscore__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _modules_workflows__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../modules/workflows */ "./admin/assets/js/modules/workflows.js");



const TrackArtwork = wp__WEBPACK_IMPORTED_MODULE_1___default.a.Backbone.View.extend({
  tagName: 'span',
  className: 'cue-track-artwork',
  template: wp__WEBPACK_IMPORTED_MODULE_1___default.a.template('cue-playlist-track-artwork'),
  events: {
    'click': 'select'
  },
  initialize: function (options) {
    this.parent = options.parent;
    this.listenTo(this.model, 'change:artworkUrl', this.render);
  },
  render: function () {
    this.$el.html(this.template(this.model.toJSON()));
    this.parent.$el.toggleClass('has-artwork', !underscore__WEBPACK_IMPORTED_MODULE_0___default.a.isEmpty(this.model.get('artworkUrl')));
    return this;
  },
  select: function () {
    _modules_workflows__WEBPACK_IMPORTED_MODULE_2__["default"].setModel(this.model).get('selectArtwork').open();
  }
});

/***/ }),

/***/ "./admin/assets/js/views/edit-playlist/track/audio.js":
/*!************************************************************!*\
  !*** ./admin/assets/js/views/edit-playlist/track/audio.js ***!
  \************************************************************/
/*! exports provided: TrackAudio */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "TrackAudio", function() { return TrackAudio; });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var mediaelementjs__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! mediaelementjs */ "mediaelementjs");
/* harmony import */ var mediaelementjs__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(mediaelementjs__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var cue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! cue */ "./admin/assets/js/modules/application.js");
/* harmony import */ var _modules_workflows__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../modules/workflows */ "./admin/assets/js/modules/workflows.js");





const TrackAudio = wp__WEBPACK_IMPORTED_MODULE_2___default.a.Backbone.View.extend({
  tagName: 'span',
  className: 'cue-track-audio',
  template: wp__WEBPACK_IMPORTED_MODULE_2___default.a.template('cue-playlist-track-audio'),
  events: {
    'click .cue-track-audio-selector': 'select'
  },
  initialize: function (options) {
    this.parent = options.parent;
    this.listenTo(this.model, 'change:audioUrl', this.refresh);
    this.listenTo(this.model, 'destroy', this.cleanup);
  },
  render: function () {
    const track = this.model.toJSON();
    const playerId = this.$el.find('.mejs-audio').attr('id'); // Remove the MediaElement player object if the
    // audio file URL is empty.

    if ('' === track.audioUrl && playerId) {
      mediaelementjs__WEBPACK_IMPORTED_MODULE_1___default.a.players[playerId].remove();
    } // Render the media element.


    this.$el.html(this.template(this.model.toJSON())); // Set up MediaElement.js.

    const $mediaEl = this.$el.find('.cue-audio');

    if ($mediaEl.length) {
      // MediaElement traverses the DOM and throws an error if it
      // can't find a parent node before reaching <body>. It makes
      // sure the flash fallback won't exist within a <p> tag.
      // The view isn't attached to the DOM at this point, so an
      // error is thrown when reaching the top of the tree.
      // This hack makes it stop searching. The fake <body> tag is
      // removed in the success callback.
      // @see mediaelement-and-player.js:~1222
      $mediaEl.wrap('<body></body>');
      const playerSettings = {
        classPrefix: 'mejs-',
        defaultAudioHeight: 30,
        features: ['playpause', 'current', 'progress', 'duration'],
        pluginPath: cue__WEBPACK_IMPORTED_MODULE_3__["settings"].pluginPath,
        stretching: 'responsive',
        success: (mediaElement, domObject, t) => {
          const $fakeBody = jquery__WEBPACK_IMPORTED_MODULE_0___default()(t.container).parent(); // Remove the fake <body> tag.

          if (jquery__WEBPACK_IMPORTED_MODULE_0___default.a.nodeName($fakeBody.get(0), 'body')) {
            $fakeBody.replaceWith($fakeBody.get(0).childNodes);
          }
        },
        error: el => {
          const $el = jquery__WEBPACK_IMPORTED_MODULE_0___default()(el);
          const $parent = $el.closest('.cue-track');
          const playerId = $el.closest('.mejs-audio').attr('id'); // Remove the audio element if there is an error.

          mediaelementjs__WEBPACK_IMPORTED_MODULE_1___default.a.players[playerId].remove();
          $parent.find('audio').remove();
        }
      }; // Hack to allow .m4a files.
      // @link https://github.com/johndyer/mediaelement/issues/291

      if ('m4a' === $mediaEl.attr('src').split('.').pop()) {
        playerSettings.pluginVars = 'isvideo=true';
      }

      $mediaEl.mediaelementplayer(playerSettings);
    }

    return this;
  },
  refresh: function (e) {
    const track = this.model.toJSON();
    const playerId = this.$el.find('.mejs-audio').attr('id');
    const player = playerId ? mediaelementjs__WEBPACK_IMPORTED_MODULE_1___default.a.players[playerId] : null;

    if (player && '' !== track.audioUrl) {
      player.pause();
      player.setSrc(track.audioUrl);
    } else {
      this.render();
    }
  },
  cleanup: function () {
    const playerId = this.$el.find('.mejs-audio').attr('id');
    const player = playerId ? mediaelementjs__WEBPACK_IMPORTED_MODULE_1___default.a.players[playerId] : null;

    if (player) {
      player.remove();
    }
  },
  select: function () {
    _modules_workflows__WEBPACK_IMPORTED_MODULE_4__["default"].setModel(this.model).get('selectAudio').open();
  }
});

/***/ }),

/***/ "./admin/assets/js/views/edit-playlist/track/index.js":
/*!************************************************************!*\
  !*** ./admin/assets/js/views/edit-playlist/track/index.js ***!
  \************************************************************/
/*! exports provided: Track */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Track", function() { return Track; });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _artwork__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./artwork */ "./admin/assets/js/views/edit-playlist/track/artwork.js");
/* harmony import */ var _audio__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./audio */ "./admin/assets/js/views/edit-playlist/track/audio.js");




const Track = wp__WEBPACK_IMPORTED_MODULE_1___default.a.Backbone.View.extend({
  tagName: 'li',
  className: 'cue-track',
  template: wp__WEBPACK_IMPORTED_MODULE_1___default.a.template('cue-playlist-track'),
  events: {
    'change [data-setting]': 'updateAttribute',
    'click .js-toggle': 'toggleOpenStatus',
    'dblclick .cue-track-title': 'toggleOpenStatus',
    'click .js-close': 'minimize',
    'click .js-remove': 'destroy'
  },
  initialize: function () {
    this.listenTo(this.model, 'change:title', this.updateTitle);
    this.listenTo(this.model, 'change', this.updateFields);
    this.listenTo(this.model, 'destroy', this.remove);
  },
  render: function () {
    this.$el.html(this.template(this.model.toJSON())).data('cid', this.model.cid);
    this.views.add('.cue-track-column-artwork', new _artwork__WEBPACK_IMPORTED_MODULE_2__["TrackArtwork"]({
      model: this.model,
      parent: this
    }));
    this.views.add('.cue-track-audio-group', new _audio__WEBPACK_IMPORTED_MODULE_3__["TrackAudio"]({
      model: this.model,
      parent: this
    }));
    return this;
  },
  minimize: function (e) {
    e.preventDefault();
    this.$el.removeClass('is-open').find('input:focus').blur();
  },
  toggleOpenStatus: function (e) {
    e.preventDefault();
    this.$el.toggleClass('is-open').find('input:focus').blur(); // Trigger a resize so the media element will fill the container.

    if (this.$el.hasClass('is-open')) {
      jquery__WEBPACK_IMPORTED_MODULE_0___default()(window).trigger('resize');
    }
  },

  /**
   * Update a model attribute when a field is changed.
   *
   * Fields with a 'data-setting="{{key}}"' attribute whose value
   * corresponds to a model attribute will be automatically synced.
   *
   * @param {Object} e Event object.
   */
  updateAttribute: function (e) {
    const attribute = jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target).data('setting');
    const value = e.target.value;

    if (this.model.get(attribute) !== value) {
      this.model.set(attribute, value);
    }
  },

  /**
   * Update a setting field when a model's attribute is changed.
   */
  updateFields: function () {
    const track = this.model.toJSON();
    const $settings = this.$el.find('[data-setting]'); // A change event shouldn't be triggered here, so it won't cause
    // the model attribute to be updated and get stuck in an
    // infinite loop.

    for (const attribute in track) {
      const value = jquery__WEBPACK_IMPORTED_MODULE_0___default()('<div/>').html(track[attribute]).text(); // Decodes HTML entities.

      $settings.filter('[data-setting="' + attribute + '"]').val(value);
    }
  },
  updateTitle: function () {
    const title = this.model.get('title');
    this.$el.find('.cue-track-title .text').text(title ? title : 'Title');
  },

  /**
   * Destroy the view's model.
   *
   * Avoid syncing to the server by triggering an event instead of
   * calling destroy() directly on the model.
   */
  destroy: function () {
    this.model.trigger('destroy', this.model);
  },
  remove: function () {
    this.$el.remove();
  }
});

/***/ }),

/***/ "./admin/assets/js/views/frame/track-media.js":
/*!****************************************************!*\
  !*** ./admin/assets/js/views/frame/track-media.js ***!
  \****************************************************/
/*! exports provided: TrackMediaFrame */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "TrackMediaFrame", function() { return TrackMediaFrame; });
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var cue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! cue */ "./admin/assets/js/modules/application.js");


const {
  MediaFrame,
  Toolbar
} = wp__WEBPACK_IMPORTED_MODULE_0___default.a.media.view;
const {
  Post: PostFrame,
  Select: SelectFrame
} = MediaFrame;
const TrackMediaFrame = PostFrame.extend({
  createStates: function () {
    const options = this.options;
    this.states.add([new wp__WEBPACK_IMPORTED_MODULE_0___default.a.media.controller.Library({
      id: 'insert',
      title: options.title,
      priority: 20,
      toolbar: 'main-insert',
      filterable: 'uploaded',
      library: wp__WEBPACK_IMPORTED_MODULE_0___default.a.media.query(options.library),
      multiple: options.multiple ? 'reset' : false,
      editable: false,
      allowLocalEdits: true,
      displaySettings: false,
      displayUserSettings: false
    }), new wp__WEBPACK_IMPORTED_MODULE_0___default.a.media.controller.Embed({
      title: cue__WEBPACK_IMPORTED_MODULE_1__["l10n"].addFromUrl,
      menuItem: {
        text: cue__WEBPACK_IMPORTED_MODULE_1__["l10n"].addFromUrl,
        priority: 120
      },
      type: 'link'
    })]);
  },
  bindHandlers: function () {
    SelectFrame.prototype.bindHandlers.apply(this, arguments);
    this.on('toolbar:create:main-insert', this.createToolbar, this);
    this.on('toolbar:create:main-embed', this.mainEmbedToolbar, this);
    this.on('menu:render:default', this.mainMenu, this);
    this.on('content:render:embed', this.embedContent, this);
    this.on('content:render:edit-selection', this.editSelectionContent, this);
    this.on('toolbar:render:main-insert', this.mainInsertToolbar, this);
  },
  mainInsertToolbar: function (view) {
    this.selectionStatusToolbar(view);
    view.set('insert', {
      style: 'primary',
      priority: 80,
      text: this.options.button.text,
      requires: {
        selection: true
      },
      click: () => {
        const state = this.state();
        const selection = state.get('selection');
        this.close();
        state.trigger('insert', selection).reset();
      }
    });
  },
  mainEmbedToolbar: function (toolbar) {
    toolbar.view = new Toolbar.Embed({
      controller: this,
      text: this.options.button.text
    });
  }
});

/***/ }),

/***/ "./admin/assets/js/views/screen/button/add-tracks.js":
/*!***********************************************************!*\
  !*** ./admin/assets/js/views/screen/button/add-tracks.js ***!
  \***********************************************************/
/*! exports provided: AddTracksButton */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "AddTracksButton", function() { return AddTracksButton; });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _modules_workflows__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../modules/workflows */ "./admin/assets/js/modules/workflows.js");



const AddTracksButton = wp__WEBPACK_IMPORTED_MODULE_1___default.a.Backbone.View.extend({
  id: 'add-tracks',
  tagName: 'p',
  events: {
    'click .button': 'click'
  },
  initialize: function (options) {
    this.l10n = options.l10n;
  },
  render: function () {
    const $button = jquery__WEBPACK_IMPORTED_MODULE_0___default()('<a />', {
      text: this.l10n.addTracks
    }).addClass('button button-secondary');
    this.$el.html($button);
    return this;
  },
  click: function (e) {
    e.preventDefault();
    _modules_workflows__WEBPACK_IMPORTED_MODULE_2__["default"].get('addTracks').open();
  }
});

/***/ }),

/***/ "./admin/assets/js/views/screen/edit-playlist.js":
/*!*******************************************************!*\
  !*** ./admin/assets/js/views/screen/edit-playlist.js ***!
  \*******************************************************/
/*! exports provided: EditPlaylistScreen */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "EditPlaylistScreen", function() { return EditPlaylistScreen; });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! wp */ "wp");
/* harmony import */ var wp__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(wp__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _button_add_tracks__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./button/add-tracks */ "./admin/assets/js/views/screen/button/add-tracks.js");
/* harmony import */ var _edit_playlist_track_list__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../edit-playlist/track-list */ "./admin/assets/js/views/edit-playlist/track-list.js");




const EditPlaylistScreen = wp__WEBPACK_IMPORTED_MODULE_1___default.a.Backbone.View.extend({
  saved: false,
  events: {
    'click #publish': 'buttonClick',
    'click #save-post': 'buttonClick'
  },
  initialize: function (options) {
    this.l10n = options.l10n;
  },
  render: function () {
    this.views.add('#cue-playlist-editor .cue-panel-body', [new _button_add_tracks__WEBPACK_IMPORTED_MODULE_2__["AddTracksButton"]({
      collection: this.collection,
      l10n: this.l10n
    }), new _edit_playlist_track_list__WEBPACK_IMPORTED_MODULE_3__["TrackList"]({
      collection: this.collection
    })]);
    return this;
  },
  buttonClick: function (e) {
    const $button = jquery__WEBPACK_IMPORTED_MODULE_0___default()(e.target);

    if (!this.saved) {
      this.collection.save().done(data => {
        this.saved = true;
        $button.click();
      });
    }

    return this.saved;
  }
});

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

/***/ "mediaelementjs":
/*!***********************!*\
  !*** external "mejs" ***!
  \***********************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = mejs;

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
//# sourceMappingURL=playlist-edit.bundle.js.map
