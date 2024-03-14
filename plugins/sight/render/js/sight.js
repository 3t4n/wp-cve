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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);
__webpack_require__(3);
module.exports = __webpack_require__(4);


/***/ }),
/* 1 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utility__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2);
/**
 * Lightbox
 */


(function ($) {
  $.fn.SightLightbox = function (options) {
    var settings = $.extend({
      gallery: false
    }, options);
    var containerSelector = this;
    var imageSelector = null;
    $(containerSelector).each(function () {
      if ($(this).is('img')) {
        imageSelector = this;
      } else {
        imageSelector = $(this).find('img');
      }

      $(imageSelector).each(function () {
        var link = $(this).closest('.sight-portfolio-entry').find('.sight-portfolio-overlay-link');

        if (!$(link).is('a')) {
          return;
        }

        var imagehref = $(link).attr('href');

        if (!imagehref.match(/\.(gif|jpeg|jpg|png)/)) {
          return;
        }

        $(link).addClass('sight-image-popup');
      });

      if ($(this).closest('.elementor-element').length > 0) {
        return;
      }

      $(containerSelector).magnificPopup({
        delegate: '.sight-image-popup',
        type: 'image',
        tClose: sight_lightbox_localize.text_close + '(Esc)',
        tLoading: sight_lightbox_localize.text_loading,
        gallery: {
          enabled: settings.gallery,
          tPrev: sight_lightbox_localize.text_previous,
          tNext: sight_lightbox_localize.text_next,
          tCounter: '<span class="mfp-counter">%curr% ' + sight_lightbox_localize.text_counter + ' %total%</span>'
        },
        image: {
          titleSrc: function titleSrc(item) {
            var entry = item.el.closest('.sight-portfolio-entry');

            if ($(entry).hasClass('sight-portfolio-entry-custom')) {
              return $(entry).find('.sight-portfolio-entry__caption').text();
            } else if ($(entry).hasClass('sight-portfolio-entry-post')) {
              return $(entry).find('.sight-portfolio-entry__caption').text();
            } else {
              return $(entry).find('.sight-portfolio-entry__heading').text();
            }
          }
        }
      });
    });
  };

  function initSightLightbox() {
    $('.sight-portfolio-area-lightbox').imagesLoaded(function () {
      $('.sight-portfolio-area-lightbox').SightLightbox({
        gallery: true
      });
    });
  }

  $(document).ready(function () {
    initSightLightbox();
    $(document.body).on('post-load image-load', function () {
      initSightLightbox();
    });
  });
})(jQuery);

/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "$", function() { return $; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "$window", function() { return $window; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "$doc", function() { return $doc; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "$body", function() { return $body; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "sight", function() { return sight; });
// Create csco object.
var sight = {
  addAction: function addAction(x, y, z) {
    return;
  }
};

if ('undefined' !== typeof wp && 'undefined' !== typeof wp.hooks) {
  sight.addAction = wp.hooks.addAction;
}

if ('undefined' === typeof window.load_more_query) {
  window.load_more_query = [];
}
/**
 * Window size
 */


var $ = jQuery;
var $window = $(window);
var $doc = $(document);
var $body = $('body');
/**
 * In Viewport checker
 */

$.fn.isInViewport = function () {
  var elementTop = $(this).offset().top;
  var elementBottom = elementTop + $(this).outerHeight();
  var viewportTop = $(window).scrollTop();
  var viewportBottom = viewportTop + $(window).height();
  return elementBottom > viewportTop && elementTop < viewportBottom;
};



/***/ }),
/* 3 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utility__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2);
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

/**
 * AJAX Load More.
 *
 * Contains functions for AJAX Load More.
 */

/**
 * Insert Load More
 */

function sight_portfolio_load_more_insert(container, settings) {
  if ('none' === settings.pagination_type) {
    return;
  }

  if (Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).find('.sight-portfolio-area__pagination').length) {
    return;
  }

  Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).append('<div class="sight-portfolio-area__pagination"><button class="sight-portfolio-load-more">' + settings.translation.load_more + '</button></div>');
}
/**
 * Get next posts
 */


function sight_portfolio_ajax_get_posts(container, reload) {
  var pagination = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).find('.sight-portfolio-area__pagination');
  var loadMore = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).find('.sight-portfolio-load-more');
  var settings = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).data('settings');
  var page = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).data('page'); // Filter terms.

  var terms = [];
  Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).find('.sight-portfolio-area-filter__list li').each(function (index, elem) {
    if (Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).is('.sight-filter-active:not(.sight-filter-all)')) {
      terms.push(Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).find('a').data('filter'));
    }
  }); // Set area loading.

  Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).find('.sight-portfolio-area__main').addClass('sight-portfolio-loading'); // Set ajax settings.

  if (reload) {
    page = 1;
  }

  var data = {
    action: 'sight_portfolio_ajax_load_more',
    terms: terms,
    page: page,
    posts_per_page: settings.posts_per_page,
    query_data: settings.query_data,
    attributes: settings.attributes,
    options: settings.options,
    _ajax_nonce: settings.nonce
  }; // Set the loading state.

  Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).data('loading', true); // Set button text to Load More.

  Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(loadMore).text(settings.translation.loading); // Request Url.

  var sight_pagination_url;

  if ('ajax_restapi' === settings.type) {
    sight_pagination_url = settings.rest_url;
  } else {
    sight_pagination_url = settings.url;
  } // Send Request.


  _utility__WEBPACK_IMPORTED_MODULE_0__["$"].post(sight_pagination_url, data, function (res) {
    if (res.success) {
      // Get the posts.
      var data = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(res.data.content);
      data.imagesLoaded(function () {
        // Append new posts to list, standard and grid archives.
        if (reload) {
          Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).find('.sight-portfolio-area__main').html(data);
        } else {
          Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).find('.sight-portfolio-area__main').append(data);
        } // Entry animations.


        Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).find('.sight-portfolio-entry-request').each(function () {
          if (!Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).is('sight-portfolio-entry-animation')) {
            Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).addClass('sight-portfolio-entry-animation').animate({
              opacity: 1,
              top: 0
            }, 600);
          }
        }); // WP Post Load trigger.

        Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(window).trigger('post-load'); // Reinit Facebook widgets.

        if (Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])('#fb-root').length && 'object' === (typeof FB === "undefined" ? "undefined" : _typeof(FB))) {
          FB.XFBML.parse();
        } // Set button text to Load More.


        Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(loadMore).text(settings.translation.load_more); // Increment a page.

        page = page + 1;
        Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).data('page', page); // Set the loading state.

        Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).data('loading', false);
      }); // Remove Button on Posts End.

      if (res.data.posts_end || !data.length) {
        // Remove Load More button.
        Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(pagination).remove();
      } else {
        // Add Load More button.
        sight_portfolio_load_more_insert(container, settings);
      }

      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).find('.sight-portfolio-area__main').removeClass('sight-portfolio-loading');
    } else {
      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).find('.sight-portfolio-area__main').removeClass('sight-portfolio-loading');
    }
  }).fail(function (xhr, textStatus, e) {// console.log(xhr.responseText);
  });
}
/**
 * Initialization Load More
 */


function sight_portfolio_load_more_init(infinite, blockId) {
  Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])('.sight-portfolio-area').each(function () {
    var sight_ajax_settings;

    if (blockId) {
      var itemId = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).closest("[data-block]").attr('id');

      if (itemId !== blockId) {
        return;
      }
    } else if (Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).data('init')) {
      return;
    }

    var archive_data = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).data('items-area');

    if (archive_data) {
      sight_ajax_settings = JSON.parse(window.atob(archive_data));
    }

    if (sight_ajax_settings) {
      // Set load more settings.
      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).data('settings', sight_ajax_settings);
      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).data('page', 2);
      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).data('loading', false);
      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).data('scrollHandling', {
        allow: _utility__WEBPACK_IMPORTED_MODULE_0__["$"].parseJSON('infinite' === sight_ajax_settings.pagination_type ? true : false),
        delay: 400
      });

      if (!infinite && ('infinite' === sight_ajax_settings.pagination_type ? true : false)) {
        return;
      } // Add load more button.


      if (sight_ajax_settings.max_num_pages > 1) {
        sight_portfolio_load_more_insert(this, sight_ajax_settings);
      }
    }

    Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).data('init', true);
  });
}

sight_portfolio_load_more_init(true, false);
_utility__WEBPACK_IMPORTED_MODULE_0__["sight"].addAction('sight.components.serverSideRender.onChange', 'sight/portfolio/loadmore', function (props) {
  if ('sight/portfolio' === props.block) {
    var blockId = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])("[id=block-".concat(props.blockProps.clientId, "]")).attr('id');
    sight_portfolio_load_more_init(false, blockId);
  }
}); // On Scroll Event.

Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(window).scroll(function () {
  Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])('.sight-portfolio-area .sight-portfolio-load-more').each(function () {
    var container = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).closest('.sight-portfolio-area'); // Vars loading.

    var loading = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).data('loading');
    var scrollHandling = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).data('scrollHandling');

    if ('undefined' === typeof scrollHandling) {
      return;
    }

    if (Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).length && !loading && scrollHandling.allow) {
      scrollHandling.allow = false;
      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).data('scrollHandling', scrollHandling);
      setTimeout(function () {
        var scrollHandling = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).data('scrollHandling');

        if ('undefined' === typeof scrollHandling) {
          return;
        }

        scrollHandling.allow = true;
        Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(container).data('scrollHandling', scrollHandling);
      }, scrollHandling.delay);
      var offset = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).offset().top - Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(window).scrollTop();

      if (4000 > offset) {
        sight_portfolio_ajax_get_posts(container);
      }
    }
  });
}); // On Click Event.

Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])('body').on('click', '.sight-portfolio-load-more', function () {
  var container = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).closest('.sight-portfolio-area');
  var loading = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).data('loading');

  if (!loading) {
    sight_portfolio_ajax_get_posts(container, false);
  }
}); // On Click Categories.

Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])('body').on('click', '.sight-portfolio-area-filter__list a', function (e) {
  var container = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).closest('.sight-portfolio-area'); // Remove active item.

  Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).parent().siblings().removeClass('sight-filter-active'); // Active item.

  Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).parent().addClass('sight-filter-active'); // Ajax.

  sight_portfolio_ajax_get_posts(container, true);
  e.preventDefault();
});

/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utility__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2);
/** ----------------------------------------------------------------------------
 * Video Background */

var sightVideoPortfolio = {};

(function () {
  var $this;
  var YTdeferredDone = false;
  var initAPI = false;
  var process = false;
  var contex = [];
  var players = [];
  var attrs = []; // Create deferred object

  var YTdeferred = _utility__WEBPACK_IMPORTED_MODULE_0__["$"].Deferred();

  if (typeof window.onYouTubePlayerAPIReady !== 'undefined') {
    if (typeof window.sightYTAPIReady === 'undefined') {
      window.sightYTAPIReady = [];
    }

    window.sightYTAPIReady.push(window.onYouTubePlayerAPIReady);
  }

  window.onYouTubePlayerAPIReady = function () {
    if (typeof window.sightYTAPIReady !== 'undefined') {
      if (window.sightYTAPIReady.length) {
        window.sightYTAPIReady.pop()();
      }
    } // Resolve when youtube callback is called
    // passing YT as a parameter.


    YTdeferred.resolve(window.YT);
  }; // Whenever youtube callback was called = deferred resolved
  // your custom function will be executed with YT as an argument.


  YTdeferred.done(function (YT) {
    YTdeferredDone = true;
  }); // Embedding youtube iframe api.

  function embedYoutubeAPI() {
    if ('function' === typeof window.onYTReady) {
      return;
    }

    var tag = document.createElement('script');
    tag.src = 'https://www.youtube.com/iframe_api';
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
  }

  _utility__WEBPACK_IMPORTED_MODULE_0__["$"].fn.contexObject = function (id, type) {
    if ('string' === typeof id) {
      id = "[data-id=\"".concat(id, "\"]");
    } else {
      id = this;
    }

    if ('wrap' === type) {
      return Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(id).closest('.sight-portfolio-video-wrap');
    } else if ('container' === type) {
      return Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(id).closest('.sight-portfolio-video-container');
    } else if ('inner' === type) {
      return Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(id).closest('.sight-portfolio-video-wrap').find('.sight-portfolio-video-inner');
    } else {
      return Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(id);
    }
  }; // Object Video Portfolio.


  sightVideoPortfolio = {
    /** Initialize */
    init: function init(e) {
      if (Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])('body').hasClass('wp-admin')) {
        return;
      }

      $this = sightVideoPortfolio; // Init events.

      $this.events(e);
    },
    // Video rescale.
    rescaleVideoBackground: function rescaleVideoBackground() {
      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])('.sight-portfolio-video-init').each(function () {
        var w = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).parent().width();
        var h = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).parent().height();
        var hideControl = 400;
        var id = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).attr('data-id');

        if (w / h > 16 / 9) {
          if ('youtube' === Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).parent().data('video-mode')) {
            players[id].setSize(w, w / 16 * 9 + hideControl);
          } else {
            players[id].width(w);
            players[id].height(w / 16 * 9 + hideControl);
          }
        } else {
          if ('youtube' === Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).parent().data('video-mode')) {
            players[id].setSize(h / 9 * 16, h + hideControl);
          } else {
            players[id].width(h / 9 * 16);
            players[id].height(h + hideControl);
          }
        }
      });
    },
    // Init video background.
    initVideoBackground: function initVideoBackground(mode, object) {
      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])('.sight-portfolio-video-inner').each(function () {
        // The mode.
        if (!Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).parent().is("[data-video-mode=\"".concat(mode, "\"]"))) {
          return;
        } // The state.


        var isInit = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).hasClass('sight-portfolio-video-init');
        var id = null; // Generate unique ID.

        if (!isInit) {
          id = Math.random().toString(36).substr(2, 9);
        } else {
          id = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).attr('data-id');
        } // Create contex.


        contex[id] = this; // The monitor.

        var isInView = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(contex[id]).isInViewport(); // The actived.

        var isActive = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(contex[id]).hasClass('active'); // Get video attrs.

        var youtubeID = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(contex[id]).parent().data('youtube-id');
        var videoType = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(contex[id]).parent().data('video-type');
        var videoStart = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(contex[id]).parent().data('video-start');
        var videoEnd = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(contex[id]).parent().data('video-end'); // Initialization.

        if (isInView && !isInit) {
          // Add init class.
          Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(contex[id]).addClass('sight-portfolio-video-init'); // Add unique ID.

          Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(contex[id]).attr('data-id', id); // Check video mode.

          if ('youtube' === mode) {
            // Check video id.
            if (typeof youtubeID === 'undefined' || !youtubeID) {
              return;
            } // Video attrs.


            attrs[id] = {
              'videoId': youtubeID,
              'startSeconds': videoStart,
              'endSeconds': videoEnd,
              'suggestedQuality': 'hd720'
            }; // Creating a player.

            players[id] = new YT.Player(contex[id], {
              playerVars: {
                autoplay: 0,
                autohide: 1,
                modestbranding: 1,
                rel: 0,
                showinfo: 0,
                controls: 0,
                disablekb: 1,
                enablejsapi: 0,
                iv_load_policy: 3,
                playsinline: 1,
                loop: 1
              },
              events: {
                'onReady': function onReady() {
                  players[id].cueVideoById(attrs[id]);
                  players[id].mute();

                  if ('always' === videoType) {
                    $this.playVideo(id);
                  }
                },
                'onStateChange': function onStateChange(e) {
                  if (e.data === 1) {
                    Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).contexObject(id).closest('.sight-portfolio-video-wrap').addClass('sight-portfolio-video-bg-init');
                    Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).contexObject(id).addClass('active');
                  } else if (e.data === 0) {
                    players[id].seekTo(attrs[id].startSeconds);
                  } else if (e.data === 5) {
                    players[id].videoReady = true;
                  }
                }
              }
            });
          } else {
            // Creating a player.
            players[id] = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(contex[id]); // Ready play.

            players[id].on('canplay', function () {
              players[id].videoReady = true;

              if (true !== players[id].start) {
                players[id].start = true;
                this.currentTime = videoStart ? videoStart : 0;

                if ('always' === videoType) {
                  $this.playVideo(id);
                }
              }
            }); // Play.

            players[id].on('play', function () {
              players[id].parents('.sight-portfolio-video-wrap').addClass('sight-portfolio-video-bg-init');
              players[id].addClass('active');
            }); // Ended.

            players[id].on('timeupdate', function () {
              if (videoEnd && this.currentTime >= videoEnd) {
                players[id].trigger('pause');
                this.currentTime = videoStart;
                players[id].trigger('play');
              }
            });
            players[id].trigger('load');
          }

          $this.rescaleVideoBackground();
        } // Pause and play.


        if ('always' === videoType && isActive && isInit && !$this.getVideoUpause(id)) {
          if (isInView) {
            $this.playVideo(id);
          }

          if (!isInView) {
            $this.pauseVideo(id);
          }
        }
      });
    },
    // Construct video background.
    constructVideoBackground: function constructVideoBackground(object) {
      if (Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])('body').hasClass('wp-admin')) {
        return;
      }

      if (process) {
        return;
      }

      process = true; // Smart init API.

      if (!initAPI) {
        var elements = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])('[data-video-mode="youtube"][data-youtube-id]');

        if (elements.length) {
          embedYoutubeAPI();
          initAPI = true;
        }
      }

      if (!initAPI) {
        process = false;
      } // Play Video.


      $this.initVideoBackground('local', object);

      if (initAPI && YTdeferredDone) {
        $this.initVideoBackground('youtube', object);
      }

      process = false;
    },
    // Get video type.
    getVideoType: function getVideoType(id) {
      return Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).contexObject(id, 'container').data('video-type');
    },
    // Get video mode.
    getVideoMode: function getVideoMode(id) {
      return Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).contexObject(id, 'container').data('video-mode');
    },
    // Get video state.
    getVideoState: function getVideoState(id) {
      return players[id].videoState;
    },
    // Get video ready.
    getVideoReady: function getVideoReady(id) {
      return players[id].videoReady ? players[id].videoReady : false;
    },
    // Get video upause.
    getVideoUpause: function getVideoUpause(id) {
      return players[id].videoUpause ? players[id].videoUpause : false;
    },
    // Get video volume.
    getVideoVolume: function getVideoVolume(id) {
      return players[id].videoVolume ? players[id].videoVolume : 'mute';
    },
    // Change video upause.
    changeVideoUpause: function changeVideoUpause(id, val) {
      players[id].videoUpause = val;
    },
    // Play video.
    playVideo: function playVideo(id) {
      if ('play' === players[id].videoState) {
        return;
      }

      if (!players[id].videoReady) {
        return setTimeout(function () {
          $this.playVideo(id);
        }, 100);
      }

      if ('youtube' === $this.getVideoMode(id)) {
        players[id].playVideo();
      } else {
        players[id].trigger('play');
      } // Change control.


      var control = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).contexObject(id, 'wrap').find('.sight-portfolio-player-state');
      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(control).removeClass('sight-portfolio-player-pause');
      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(control).addClass('sight-portfolio-player-play'); // Set state.

      players[id].videoState = 'play';
    },
    // Pause video.
    pauseVideo: function pauseVideo(id) {
      if ('pause' === players[id].videoState) {
        return;
      }

      if (!players[id].videoReady) {
        return;
      }

      if ('youtube' === $this.getVideoMode(id)) {
        players[id].pauseVideo();
      } else {
        players[id].trigger('pause');
      } // Change control.


      var control = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).contexObject(id, 'wrap').find('.sight-portfolio-player-state');
      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(control).removeClass('sight-portfolio-player-play');
      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(control).addClass('sight-portfolio-player-pause'); // Set state.

      players[id].videoState = 'pause';
    },
    // Unmute video.
    unmuteVideo: function unmuteVideo(id) {
      if (!players[id].videoReady) {
        return;
      }

      if ('youtube' === $this.getVideoMode(id)) {
        players[id].unMute();
      } else {
        players[id].prop('muted', false);
      } // Change control.


      var control = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).contexObject(id, 'wrap').find('.sight-portfolio-player-volume');
      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(control).removeClass('sight-portfolio-player-mute');
      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(control).addClass('sight-portfolio-player-unmute'); // Set state.

      players[id].videoVolume = 'unmute';
    },
    // Mute video.
    muteVideo: function muteVideo(id) {
      if (!players[id].videoReady) {
        return;
      }

      if ('youtube' === $this.getVideoMode(id)) {
        players[id].mute();
      } else {
        players[id].prop('muted', true);
      } // Change control.


      var control = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).contexObject(id, 'wrap').find('.sight-portfolio-player-volume');
      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(control).removeClass('sight-portfolio-player-unmute');
      Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(control).addClass('sight-portfolio-player-mute'); // Set state.

      players[id].videoVolume = 'muted';
    },
    // Toogle video state.
    toogleVideoState: function toogleVideoState(id) {
      if ('play' === $this.getVideoState(id)) {
        $this.pauseVideo(id);
      } else {
        $this.playVideo(id);
      }
    },
    // Toogle video volume.
    toogleVideoVolume: function toogleVideoVolume(id) {
      if ('unmute' === $this.getVideoVolume(id)) {
        $this.muteVideo(id);
      } else {
        $this.unmuteVideo(id);
      }
    },

    /** Events */
    events: function events(e) {
      // State Control.
      _utility__WEBPACK_IMPORTED_MODULE_0__["$doc"].on('click', '.sight-portfolio-player-state', function () {
        var id = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).contexObject(false, 'inner').attr('data-id');
        $this.toogleVideoState(id);

        if ('play' === $this.getVideoState(id)) {
          $this.changeVideoUpause(id, false);
        } else {
          $this.changeVideoUpause(id, true);
        }
      }); // Stop Control.

      _utility__WEBPACK_IMPORTED_MODULE_0__["$doc"].on('click', '.sight-portfolio-player-stop', function () {
        var id = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).contexObject(false, 'inner').attr('data-id');

        if ('play' === $this.getVideoState(id)) {
          $this.changeVideoUpause(id, true);
        }

        $this.pauseVideo(id);
      }); // Volume Control.

      _utility__WEBPACK_IMPORTED_MODULE_0__["$doc"].on('click', '.sight-portfolio-player-volume', function () {
        var id = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).contexObject(false, 'inner').attr('data-id');
        $this.toogleVideoVolume(id);
      }); // Mouseover.

      _utility__WEBPACK_IMPORTED_MODULE_0__["$doc"].on('mouseover mousemove', '.sight-portfolio-entry__thumbnail', function () {
        var id = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).contexObject(false, 'inner').attr('data-id');

        if ('hover' === $this.getVideoType(id)) {
          $this.playVideo(id);
        }
      }); // Mouseout.

      _utility__WEBPACK_IMPORTED_MODULE_0__["$doc"].on('mouseout', '.sight-portfolio-entry__thumbnail', function () {
        var id = Object(_utility__WEBPACK_IMPORTED_MODULE_0__["$"])(this).contexObject(false, 'inner').attr('data-id');

        if ('hover' === $this.getVideoType(id)) {
          $this.pauseVideo(id);
        }
      }); // Document scroll.

      _utility__WEBPACK_IMPORTED_MODULE_0__["$window"].on('load scroll resize scrollstop', function () {
        $this.constructVideoBackground();
      }); // Document ready.

      _utility__WEBPACK_IMPORTED_MODULE_0__["$doc"].ready(function () {
        $this.constructVideoBackground();
      }); // Post load.

      _utility__WEBPACK_IMPORTED_MODULE_0__["$body"].on('post-load', function () {
        $this.constructVideoBackground();
      }); // Document resize.

      _utility__WEBPACK_IMPORTED_MODULE_0__["$window"].on('resize', function () {
        $this.rescaleVideoBackground();
      }); // Init.

      $this.constructVideoBackground();
    }
  };
})(); // Initialize.


sightVideoPortfolio.init();

/***/ })
/******/ ]);