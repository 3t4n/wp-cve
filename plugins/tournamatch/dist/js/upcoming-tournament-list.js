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
/******/ 	return __webpack_require__(__webpack_require__.s = 44);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/tournamatch.js":
/*!*******************************!*\
  !*** ./src/js/tournamatch.js ***!
  \*******************************/
/*! exports provided: trn */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "trn", function() { return trn; });


function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

var Tournamatch = /*#__PURE__*/function () {
  function Tournamatch() {
    _classCallCheck(this, Tournamatch);

    this.events = {};
  }

  _createClass(Tournamatch, [{
    key: "param",
    value: function param(object, prefix) {
      var str = [];

      for (var prop in object) {
        if (object.hasOwnProperty(prop)) {
          var k = prefix ? prefix + "[" + prop + "]" : prop;
          var v = object[prop];
          str.push(v !== null && _typeof(v) === "object" ? this.param(v, k) : encodeURIComponent(k) + "=" + encodeURIComponent(v));
        }
      }

      return str.join("&");
    }
  }, {
    key: "event",
    value: function event(eventName) {
      if (!(eventName in this.events)) {
        this.events[eventName] = new EventTarget(eventName);
      }

      return this.events[eventName];
    }
  }, {
    key: "autocomplete",
    value: function autocomplete(input, dataCallback) {
      new Tournamatch_Autocomplete(input, dataCallback);
    }
  }, {
    key: "ucfirst",
    value: function ucfirst(s) {
      if (typeof s !== 'string') return '';
      return s.charAt(0).toUpperCase() + s.slice(1);
    }
  }, {
    key: "ordinal_suffix",
    value: function ordinal_suffix(number) {
      var remainder = number % 100;

      if (remainder < 11 || remainder > 13) {
        switch (remainder % 10) {
          case 1:
            return 'st';

          case 2:
            return 'nd';

          case 3:
            return 'rd';
        }
      }

      return 'th';
    }
  }, {
    key: "tabs",
    value: function tabs(element) {
      var tabs = element.getElementsByClassName('trn-nav-link');
      var panes = document.getElementsByClassName('trn-tab-pane');

      var clearActive = function clearActive() {
        Array.prototype.forEach.call(tabs, function (tab) {
          tab.classList.remove('trn-nav-active');
          tab.ariaSelected = false;
        });
        Array.prototype.forEach.call(panes, function (pane) {
          return pane.classList.remove('trn-tab-active');
        });
      };

      var setActive = function setActive(targetId) {
        var targetTab = document.querySelector('a[href="#' + targetId + '"].trn-nav-link');
        var targetPaneId = targetTab && targetTab.dataset && targetTab.dataset.target || false;

        if (targetPaneId) {
          clearActive();
          targetTab.classList.add('trn-nav-active');
          targetTab.ariaSelected = true;
          document.getElementById(targetPaneId).classList.add('trn-tab-active');
        }
      };

      var tabClick = function tabClick(event) {
        var targetTab = event.currentTarget;
        var targetPaneId = targetTab && targetTab.dataset && targetTab.dataset.target || false;

        if (targetPaneId) {
          setActive(targetPaneId);
          event.preventDefault();
        }
      };

      Array.prototype.forEach.call(tabs, function (tab) {
        tab.addEventListener('click', tabClick);
      });

      if (location.hash) {
        setActive(location.hash.substr(1));
      } else if (tabs.length > 0) {
        setActive(tabs[0].dataset.target);
      }
    }
  }]);

  return Tournamatch;
}(); //trn.initialize();


if (!window.trn_obj_instance) {
  window.trn_obj_instance = new Tournamatch();
  window.addEventListener('load', function () {
    var tabViews = document.getElementsByClassName('trn-nav');
    Array.from(tabViews).forEach(function (tab) {
      trn.tabs(tab);
    });
    var dropdowns = document.getElementsByClassName('trn-dropdown-toggle');

    var handleDropdownClose = function handleDropdownClose() {
      Array.from(dropdowns).forEach(function (dropdown) {
        dropdown.nextElementSibling.classList.remove('trn-show');
      });
      document.removeEventListener("click", handleDropdownClose, false);
    };

    Array.from(dropdowns).forEach(function (dropdown) {
      dropdown.addEventListener('click', function (e) {
        e.stopPropagation();
        this.nextElementSibling.classList.add('trn-show');
        document.addEventListener("click", handleDropdownClose, false);
      }, false);
    });
  }, false);
}

var trn = window.trn_obj_instance;

var Tournamatch_Autocomplete = /*#__PURE__*/function () {
  // currentFocus;
  //
  // nameInput;
  //
  // self;
  function Tournamatch_Autocomplete(input, dataCallback) {
    var _this = this;

    _classCallCheck(this, Tournamatch_Autocomplete);

    // this.self = this;
    this.nameInput = input;
    this.nameInput.addEventListener("input", function () {
      var a,
          b,
          i,
          val = _this.nameInput.value; //this.value;

      var parent = _this.nameInput.parentNode; //this.parentNode;
      // let p = new Promise((resolve, reject) => {
      //     /* need to query server for names here. */
      //     let xhr = new XMLHttpRequest();
      //     xhr.open('GET', options.api_url + 'players/?search=' + val + '&per_page=5');
      //     xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      //     xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);
      //     xhr.onload = function () {
      //         if (xhr.status === 200) {
      //             // resolve(JSON.parse(xhr.response).map((player) => {return { 'value': player.id, 'text': player.name };}));
      //             resolve(JSON.parse(xhr.response).map((player) => {return player.name;}));
      //         } else {
      //             reject();
      //         }
      //     };
      //     xhr.send();
      // });

      dataCallback(val).then(function (data) {
        //p.then((data) => {
        console.log(data);
        /*close any already open lists of auto-completed values*/

        _this.closeAllLists();

        if (!val) {
          return false;
        }

        _this.currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/

        a = document.createElement("DIV");
        a.setAttribute("id", _this.nameInput.id + "-auto-complete-list");
        a.setAttribute("class", "trn-auto-complete-items");
        /*append the DIV element as a child of the auto-complete container:*/

        parent.appendChild(a);
        /*for each item in the array...*/

        for (i = 0; i < data.length; i++) {
          var text = void 0,
              value = void 0;
          /* Which format did they give us. */

          if (_typeof(data[i]) === 'object') {
            text = data[i]['text'];
            value = data[i]['value'];
          } else {
            text = data[i];
            value = data[i];
          }
          /*check if the item starts with the same letters as the text field value:*/


          if (text.substr(0, val.length).toUpperCase() === val.toUpperCase()) {
            /*create a DIV element for each matching element:*/
            b = document.createElement("DIV");
            /*make the matching letters bold:*/

            b.innerHTML = "<strong>" + text.substr(0, val.length) + "</strong>";
            b.innerHTML += text.substr(val.length);
            /*insert a input field that will hold the current array item's value:*/

            b.innerHTML += "<input type='hidden' value='" + value + "'>";
            b.dataset.value = value;
            b.dataset.text = text;
            /*execute a function when someone clicks on the item value (DIV element):*/

            b.addEventListener("click", function (e) {
              console.log("item clicked with value ".concat(e.currentTarget.dataset.value));
              /* insert the value for the autocomplete text field: */

              _this.nameInput.value = e.currentTarget.dataset.text;
              _this.nameInput.dataset.selectedId = e.currentTarget.dataset.value;
              /* close the list of autocompleted values, (or any other open lists of autocompleted values:*/

              _this.closeAllLists();

              _this.nameInput.dispatchEvent(new Event('change'));
            });
            a.appendChild(b);
          }
        }
      });
    });
    /*execute a function presses a key on the keyboard:*/

    this.nameInput.addEventListener("keydown", function (e) {
      var x = document.getElementById(_this.nameInput.id + "-auto-complete-list");
      if (x) x = x.getElementsByTagName("div");

      if (e.keyCode === 40) {
        /*If the arrow DOWN key is pressed,
         increase the currentFocus variable:*/
        _this.currentFocus++;
        /*and and make the current item more visible:*/

        _this.addActive(x);
      } else if (e.keyCode === 38) {
        //up

        /*If the arrow UP key is pressed,
         decrease the currentFocus variable:*/
        _this.currentFocus--;
        /*and and make the current item more visible:*/

        _this.addActive(x);
      } else if (e.keyCode === 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();

        if (_this.currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[_this.currentFocus].click();
        }
      }
    });
    /*execute a function when someone clicks in the document:*/

    document.addEventListener("click", function (e) {
      _this.closeAllLists(e.target);
    });
  }

  _createClass(Tournamatch_Autocomplete, [{
    key: "addActive",
    value: function addActive(x) {
      /*a function to classify an item as "active":*/
      if (!x) return false;
      /*start by removing the "active" class on all items:*/

      this.removeActive(x);
      if (this.currentFocus >= x.length) this.currentFocus = 0;
      if (this.currentFocus < 0) this.currentFocus = x.length - 1;
      /*add class "autocomplete-active":*/

      x[this.currentFocus].classList.add("trn-auto-complete-active");
    }
  }, {
    key: "removeActive",
    value: function removeActive(x) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("trn-auto-complete-active");
      }
    }
  }, {
    key: "closeAllLists",
    value: function closeAllLists(element) {
      console.log("close all lists");
      /*close all autocomplete lists in the document,
       except the one passed as an argument:*/

      var x = document.getElementsByClassName("trn-auto-complete-items");

      for (var i = 0; i < x.length; i++) {
        if (element !== x[i] && element !== this.nameInput) {
          x[i].parentNode.removeChild(x[i]);
        }
      }
    }
  }]);

  return Tournamatch_Autocomplete;
}(); // First, checks if it isn't implemented yet.


if (!String.prototype.format) {
  String.prototype.format = function () {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function (match, number) {
      return typeof args[number] !== 'undefined' ? args[number] : match;
    });
  };
}

/***/ }),

/***/ "./src/js/upcoming-tournament-list.js":
/*!********************************************!*\
  !*** ./src/js/upcoming-tournament-list.js ***!
  \********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./tournamatch.js */ "./src/js/tournamatch.js");
/**
 * Handles events for the tournament list that displays in the upcoming tournaments shortcode.
 *
 * @link       https://www.tournamatch.com
 * @since      3.13.0
  *
 * @package    Tournamatch
 *
 */


(function ($) {
  'use strict';

  window.addEventListener('load', function () {
    var options = trn_upcoming_tournament_list_options;
    var start = 0;

    function handleNextClick(event) {
      start += options.paginate;
      getUpcomingTournaments();
    }

    function handlePreviousClick(event) {
      start = Math.max(0, start - options.paginate);
      getUpcomingTournaments();
    } // Accept join team invitation links.


    function addListeners() {
      var nextButton = document.getElementById('trn-upcoming-tournaments-next-button');
      var previousButton = document.getElementById('trn-upcoming-tournaments-previous-button');

      if (nextButton) {
        nextButton.addEventListener('click', handleNextClick);
      }

      if (previousButton) {
        previousButton.addEventListener('click', handlePreviousClick);
      }
    }

    function removeListeners() {
      var nextButton = document.getElementById('trn-upcoming-tournaments-next-button');
      var previousButton = document.getElementById('trn-upcoming-tournaments-previous-button');

      if (nextButton) {
        nextButton.removeEventListener('click', handleNextClick);
      }

      if (previousButton) {
        previousButton.removeEventListener('click', handlePreviousClick);
      }
    }

    function getUpcomingTournaments() {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', options.api_url + 'tournaments/?' + $.param({
        game_id: options.game_id,
        start: start,
        length: options.paginate
      }));
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        //console.log(xhr.response);
        var content = "";

        if (xhr.status === 200) {
          var tournaments = JSON.parse(xhr.response);

          if (tournaments !== null && tournaments.length > 0) {
            content += "<div class=\"items-wrapper\"";
            Array.prototype.forEach.call(tournaments, function (tournament) {
              content += "<div class=\"item-wrapper\">";
              content += "  <div class=\"item-avatar\">";
              content += "    <a href=\"".concat(tournament.link, "\" title=\"").concat(options.language.view_tournament_info, "\">");
              content += "      <img src=\"".concat(tournament.avatar, "\" alt=\"").concat(tournament.game, "\">");
              content += "    </a>";
              content += "  </div>";
              content += "  <div class=\"item-info\">";
              content += "    <span class=\"item-title\">".concat(tournament.name, "</span>");
              content += "    <span class=\"item-meta\">".concat(tournament.start_date, "</span>");
              content += "    <span class=\"item-meta\">";

              if ('1' === tournament.elimination_mode) {
                content += "    <span class=\"item-meta\">".concat(options.language.one_loss, "</span>");
              } else {
                content += "    <span class=\"item-meta\">".concat(options.language.double_elimination, "</span>");
              }

              content += "    </span>";
              content += "    <span class=\"item-meta\">";

              if ('0' === tournament.from_ladder) {
                content += "<a href=\"".concat(tournament.registered_link, "\">").concat(tournament.competitors, "</a>/");

                if (0 < tournament.bracket_size) {
                  content += "".concat(tournament.bracket_size);
                } else {
                  content += "&infin;";
                }
              } else {
                content += "<a href=\"".concat(tournament.current_seeding_link, "\">").concat(options.language.current_seeding, "</a>");
              }

              content += "    </span>";
              content += "    <ul class=\"list-inline\">";
              content += "      <li class=\"list-inline-item\"><a href=\"".concat(tournament.link, "\" class=\"trn-button trn-button-sm\">").concat(options.language.more_info, "</a></li>");
              content += "      <li class=\"list-inline-item\"><a href=\"".concat(tournament.register_link, "\" class=\"trn-button trn-button-sm\" >").concat(options.language.register, "</a></li>");
              content += "    </ul>";
              content += "  </div>";
              content += "  <div class=\"trn-clearfix\"></div>";
              content += "</div>";
            });
            content += "</div>";
            content += "<div id=\"trn-upcoming-tournaments-buttons\">";
            content += "<button id=\"trn-upcoming-tournaments-previous-button\">&#60;</button>";
            content += "<button id=\"trn-upcoming-tournaments-next-button\">&#62;</button>";
            content += "</div>";
          } else {
            content += "<p class=\"trn-text-center\">".concat(options.language.zero_tournaments, "</p>");
          }
        } else {
          content += "<p class=\"trn-text-center\">".concat(options.language.error, "</p>");
        }

        removeListeners();
        document.getElementById('trn-tournament-list-shortcode').innerHTML = content;
        addListeners();
      };

      xhr.send();
    }

    getUpcomingTournaments();
  }, false);
})(_tournamatch_js__WEBPACK_IMPORTED_MODULE_0__["trn"]);

/***/ }),

/***/ 44:
/*!**************************************************!*\
  !*** multi ./src/js/upcoming-tournament-list.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\upcoming-tournament-list.js */"./src/js/upcoming-tournament-list.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3RvdXJuYW1hdGNoLmpzIiwid2VicGFjazovLy8uL3NyYy9qcy91cGNvbWluZy10b3VybmFtZW50LWxpc3QuanMiXSwibmFtZXMiOlsiVG91cm5hbWF0Y2giLCJldmVudHMiLCJvYmplY3QiLCJwcmVmaXgiLCJzdHIiLCJwcm9wIiwiaGFzT3duUHJvcGVydHkiLCJrIiwidiIsInB1c2giLCJwYXJhbSIsImVuY29kZVVSSUNvbXBvbmVudCIsImpvaW4iLCJldmVudE5hbWUiLCJFdmVudFRhcmdldCIsImlucHV0IiwiZGF0YUNhbGxiYWNrIiwiVG91cm5hbWF0Y2hfQXV0b2NvbXBsZXRlIiwicyIsImNoYXJBdCIsInRvVXBwZXJDYXNlIiwic2xpY2UiLCJudW1iZXIiLCJyZW1haW5kZXIiLCJlbGVtZW50IiwidGFicyIsImdldEVsZW1lbnRzQnlDbGFzc05hbWUiLCJwYW5lcyIsImRvY3VtZW50IiwiY2xlYXJBY3RpdmUiLCJBcnJheSIsInByb3RvdHlwZSIsImZvckVhY2giLCJjYWxsIiwidGFiIiwiY2xhc3NMaXN0IiwicmVtb3ZlIiwiYXJpYVNlbGVjdGVkIiwicGFuZSIsInNldEFjdGl2ZSIsInRhcmdldElkIiwidGFyZ2V0VGFiIiwicXVlcnlTZWxlY3RvciIsInRhcmdldFBhbmVJZCIsImRhdGFzZXQiLCJ0YXJnZXQiLCJhZGQiLCJnZXRFbGVtZW50QnlJZCIsInRhYkNsaWNrIiwiZXZlbnQiLCJjdXJyZW50VGFyZ2V0IiwicHJldmVudERlZmF1bHQiLCJhZGRFdmVudExpc3RlbmVyIiwibG9jYXRpb24iLCJoYXNoIiwic3Vic3RyIiwibGVuZ3RoIiwid2luZG93IiwidHJuX29ial9pbnN0YW5jZSIsInRhYlZpZXdzIiwiZnJvbSIsInRybiIsImRyb3Bkb3ducyIsImhhbmRsZURyb3Bkb3duQ2xvc2UiLCJkcm9wZG93biIsIm5leHRFbGVtZW50U2libGluZyIsInJlbW92ZUV2ZW50TGlzdGVuZXIiLCJlIiwic3RvcFByb3BhZ2F0aW9uIiwibmFtZUlucHV0IiwiYSIsImIiLCJpIiwidmFsIiwidmFsdWUiLCJwYXJlbnQiLCJwYXJlbnROb2RlIiwidGhlbiIsImRhdGEiLCJjb25zb2xlIiwibG9nIiwiY2xvc2VBbGxMaXN0cyIsImN1cnJlbnRGb2N1cyIsImNyZWF0ZUVsZW1lbnQiLCJzZXRBdHRyaWJ1dGUiLCJpZCIsImFwcGVuZENoaWxkIiwidGV4dCIsImlubmVySFRNTCIsInNlbGVjdGVkSWQiLCJkaXNwYXRjaEV2ZW50IiwiRXZlbnQiLCJ4IiwiZ2V0RWxlbWVudHNCeVRhZ05hbWUiLCJrZXlDb2RlIiwiYWRkQWN0aXZlIiwiY2xpY2siLCJyZW1vdmVBY3RpdmUiLCJyZW1vdmVDaGlsZCIsIlN0cmluZyIsImZvcm1hdCIsImFyZ3MiLCJhcmd1bWVudHMiLCJyZXBsYWNlIiwibWF0Y2giLCIkIiwib3B0aW9ucyIsInRybl91cGNvbWluZ190b3VybmFtZW50X2xpc3Rfb3B0aW9ucyIsInN0YXJ0IiwiaGFuZGxlTmV4dENsaWNrIiwicGFnaW5hdGUiLCJnZXRVcGNvbWluZ1RvdXJuYW1lbnRzIiwiaGFuZGxlUHJldmlvdXNDbGljayIsIk1hdGgiLCJtYXgiLCJhZGRMaXN0ZW5lcnMiLCJuZXh0QnV0dG9uIiwicHJldmlvdXNCdXR0b24iLCJyZW1vdmVMaXN0ZW5lcnMiLCJ4aHIiLCJYTUxIdHRwUmVxdWVzdCIsIm9wZW4iLCJhcGlfdXJsIiwiZ2FtZV9pZCIsInNldFJlcXVlc3RIZWFkZXIiLCJyZXN0X25vbmNlIiwib25sb2FkIiwiY29udGVudCIsInN0YXR1cyIsInRvdXJuYW1lbnRzIiwiSlNPTiIsInBhcnNlIiwicmVzcG9uc2UiLCJ0b3VybmFtZW50IiwibGluayIsImxhbmd1YWdlIiwidmlld190b3VybmFtZW50X2luZm8iLCJhdmF0YXIiLCJnYW1lIiwibmFtZSIsInN0YXJ0X2RhdGUiLCJlbGltaW5hdGlvbl9tb2RlIiwib25lX2xvc3MiLCJkb3VibGVfZWxpbWluYXRpb24iLCJmcm9tX2xhZGRlciIsInJlZ2lzdGVyZWRfbGluayIsImNvbXBldGl0b3JzIiwiYnJhY2tldF9zaXplIiwiY3VycmVudF9zZWVkaW5nX2xpbmsiLCJjdXJyZW50X3NlZWRpbmciLCJtb3JlX2luZm8iLCJyZWdpc3Rlcl9saW5rIiwicmVnaXN0ZXIiLCJ6ZXJvX3RvdXJuYW1lbnRzIiwiZXJyb3IiLCJzZW5kIl0sIm1hcHBpbmdzIjoiO1FBQUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7OztRQUdBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSwwQ0FBMEMsZ0NBQWdDO1FBQzFFO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0Esd0RBQXdELGtCQUFrQjtRQUMxRTtRQUNBLGlEQUFpRCxjQUFjO1FBQy9EOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQSx5Q0FBeUMsaUNBQWlDO1FBQzFFLGdIQUFnSCxtQkFBbUIsRUFBRTtRQUNySTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLDJCQUEyQiwwQkFBMEIsRUFBRTtRQUN2RCxpQ0FBaUMsZUFBZTtRQUNoRDtRQUNBO1FBQ0E7O1FBRUE7UUFDQSxzREFBc0QsK0RBQStEOztRQUVySDtRQUNBOzs7UUFHQTtRQUNBOzs7Ozs7Ozs7Ozs7O0FDbEZBO0FBQUE7QUFBYTs7Ozs7Ozs7OztJQUNQQSxXO0FBRUYseUJBQWM7QUFBQTs7QUFDVixTQUFLQyxNQUFMLEdBQWMsRUFBZDtBQUNIOzs7O1dBRUQsZUFBTUMsTUFBTixFQUFjQyxNQUFkLEVBQXNCO0FBQ2xCLFVBQUlDLEdBQUcsR0FBRyxFQUFWOztBQUNBLFdBQUssSUFBSUMsSUFBVCxJQUFpQkgsTUFBakIsRUFBeUI7QUFDckIsWUFBSUEsTUFBTSxDQUFDSSxjQUFQLENBQXNCRCxJQUF0QixDQUFKLEVBQWlDO0FBQzdCLGNBQUlFLENBQUMsR0FBR0osTUFBTSxHQUFHQSxNQUFNLEdBQUcsR0FBVCxHQUFlRSxJQUFmLEdBQXNCLEdBQXpCLEdBQStCQSxJQUE3QztBQUNBLGNBQUlHLENBQUMsR0FBR04sTUFBTSxDQUFDRyxJQUFELENBQWQ7QUFDQUQsYUFBRyxDQUFDSyxJQUFKLENBQVVELENBQUMsS0FBSyxJQUFOLElBQWMsUUFBT0EsQ0FBUCxNQUFhLFFBQTVCLEdBQXdDLEtBQUtFLEtBQUwsQ0FBV0YsQ0FBWCxFQUFjRCxDQUFkLENBQXhDLEdBQTJESSxrQkFBa0IsQ0FBQ0osQ0FBRCxDQUFsQixHQUF3QixHQUF4QixHQUE4Qkksa0JBQWtCLENBQUNILENBQUQsQ0FBcEg7QUFDSDtBQUNKOztBQUNELGFBQU9KLEdBQUcsQ0FBQ1EsSUFBSixDQUFTLEdBQVQsQ0FBUDtBQUNIOzs7V0FFRCxlQUFNQyxTQUFOLEVBQWlCO0FBQ2IsVUFBSSxFQUFFQSxTQUFTLElBQUksS0FBS1osTUFBcEIsQ0FBSixFQUFpQztBQUM3QixhQUFLQSxNQUFMLENBQVlZLFNBQVosSUFBeUIsSUFBSUMsV0FBSixDQUFnQkQsU0FBaEIsQ0FBekI7QUFDSDs7QUFDRCxhQUFPLEtBQUtaLE1BQUwsQ0FBWVksU0FBWixDQUFQO0FBQ0g7OztXQUVELHNCQUFhRSxLQUFiLEVBQW9CQyxZQUFwQixFQUFrQztBQUM5QixVQUFJQyx3QkFBSixDQUE2QkYsS0FBN0IsRUFBb0NDLFlBQXBDO0FBQ0g7OztXQUVELGlCQUFRRSxDQUFSLEVBQVc7QUFDUCxVQUFJLE9BQU9BLENBQVAsS0FBYSxRQUFqQixFQUEyQixPQUFPLEVBQVA7QUFDM0IsYUFBT0EsQ0FBQyxDQUFDQyxNQUFGLENBQVMsQ0FBVCxFQUFZQyxXQUFaLEtBQTRCRixDQUFDLENBQUNHLEtBQUYsQ0FBUSxDQUFSLENBQW5DO0FBQ0g7OztXQUVELHdCQUFlQyxNQUFmLEVBQXVCO0FBQ25CLFVBQU1DLFNBQVMsR0FBR0QsTUFBTSxHQUFHLEdBQTNCOztBQUVBLFVBQUtDLFNBQVMsR0FBRyxFQUFiLElBQXFCQSxTQUFTLEdBQUcsRUFBckMsRUFBMEM7QUFDdEMsZ0JBQVFBLFNBQVMsR0FBRyxFQUFwQjtBQUNJLGVBQUssQ0FBTDtBQUFRLG1CQUFPLElBQVA7O0FBQ1IsZUFBSyxDQUFMO0FBQVEsbUJBQU8sSUFBUDs7QUFDUixlQUFLLENBQUw7QUFBUSxtQkFBTyxJQUFQO0FBSFo7QUFLSDs7QUFDRCxhQUFPLElBQVA7QUFDSDs7O1dBRUQsY0FBS0MsT0FBTCxFQUFjO0FBQ1YsVUFBTUMsSUFBSSxHQUFHRCxPQUFPLENBQUNFLHNCQUFSLENBQStCLGNBQS9CLENBQWI7QUFDQSxVQUFNQyxLQUFLLEdBQUdDLFFBQVEsQ0FBQ0Ysc0JBQVQsQ0FBZ0MsY0FBaEMsQ0FBZDs7QUFDQSxVQUFNRyxXQUFXLEdBQUcsU0FBZEEsV0FBYyxHQUFNO0FBQ3RCQyxhQUFLLENBQUNDLFNBQU4sQ0FBZ0JDLE9BQWhCLENBQXdCQyxJQUF4QixDQUE2QlIsSUFBN0IsRUFBbUMsVUFBQ1MsR0FBRCxFQUFTO0FBQ3hDQSxhQUFHLENBQUNDLFNBQUosQ0FBY0MsTUFBZCxDQUFxQixnQkFBckI7QUFDQUYsYUFBRyxDQUFDRyxZQUFKLEdBQW1CLEtBQW5CO0FBQ0gsU0FIRDtBQUlBUCxhQUFLLENBQUNDLFNBQU4sQ0FBZ0JDLE9BQWhCLENBQXdCQyxJQUF4QixDQUE2Qk4sS0FBN0IsRUFBb0MsVUFBQVcsSUFBSTtBQUFBLGlCQUFJQSxJQUFJLENBQUNILFNBQUwsQ0FBZUMsTUFBZixDQUFzQixnQkFBdEIsQ0FBSjtBQUFBLFNBQXhDO0FBQ0gsT0FORDs7QUFPQSxVQUFNRyxTQUFTLEdBQUcsU0FBWkEsU0FBWSxDQUFDQyxRQUFELEVBQWM7QUFDNUIsWUFBTUMsU0FBUyxHQUFHYixRQUFRLENBQUNjLGFBQVQsQ0FBdUIsY0FBY0YsUUFBZCxHQUF5QixpQkFBaEQsQ0FBbEI7QUFDQSxZQUFNRyxZQUFZLEdBQUdGLFNBQVMsSUFBSUEsU0FBUyxDQUFDRyxPQUF2QixJQUFrQ0gsU0FBUyxDQUFDRyxPQUFWLENBQWtCQyxNQUFwRCxJQUE4RCxLQUFuRjs7QUFFQSxZQUFJRixZQUFKLEVBQWtCO0FBQ2RkLHFCQUFXO0FBQ1hZLG1CQUFTLENBQUNOLFNBQVYsQ0FBb0JXLEdBQXBCLENBQXdCLGdCQUF4QjtBQUNBTCxtQkFBUyxDQUFDSixZQUFWLEdBQXlCLElBQXpCO0FBRUFULGtCQUFRLENBQUNtQixjQUFULENBQXdCSixZQUF4QixFQUFzQ1IsU0FBdEMsQ0FBZ0RXLEdBQWhELENBQW9ELGdCQUFwRDtBQUNIO0FBQ0osT0FYRDs7QUFZQSxVQUFNRSxRQUFRLEdBQUcsU0FBWEEsUUFBVyxDQUFDQyxLQUFELEVBQVc7QUFDeEIsWUFBTVIsU0FBUyxHQUFHUSxLQUFLLENBQUNDLGFBQXhCO0FBQ0EsWUFBTVAsWUFBWSxHQUFHRixTQUFTLElBQUlBLFNBQVMsQ0FBQ0csT0FBdkIsSUFBa0NILFNBQVMsQ0FBQ0csT0FBVixDQUFrQkMsTUFBcEQsSUFBOEQsS0FBbkY7O0FBRUEsWUFBSUYsWUFBSixFQUFrQjtBQUNkSixtQkFBUyxDQUFDSSxZQUFELENBQVQ7QUFDQU0sZUFBSyxDQUFDRSxjQUFOO0FBQ0g7QUFDSixPQVJEOztBQVVBckIsV0FBSyxDQUFDQyxTQUFOLENBQWdCQyxPQUFoQixDQUF3QkMsSUFBeEIsQ0FBNkJSLElBQTdCLEVBQW1DLFVBQUNTLEdBQUQsRUFBUztBQUN4Q0EsV0FBRyxDQUFDa0IsZ0JBQUosQ0FBcUIsT0FBckIsRUFBOEJKLFFBQTlCO0FBQ0gsT0FGRDs7QUFJQSxVQUFJSyxRQUFRLENBQUNDLElBQWIsRUFBbUI7QUFDZmYsaUJBQVMsQ0FBQ2MsUUFBUSxDQUFDQyxJQUFULENBQWNDLE1BQWQsQ0FBcUIsQ0FBckIsQ0FBRCxDQUFUO0FBQ0gsT0FGRCxNQUVPLElBQUk5QixJQUFJLENBQUMrQixNQUFMLEdBQWMsQ0FBbEIsRUFBcUI7QUFDeEJqQixpQkFBUyxDQUFDZCxJQUFJLENBQUMsQ0FBRCxDQUFKLENBQVFtQixPQUFSLENBQWdCQyxNQUFqQixDQUFUO0FBQ0g7QUFDSjs7OztLQUlMOzs7QUFDQSxJQUFJLENBQUNZLE1BQU0sQ0FBQ0MsZ0JBQVosRUFBOEI7QUFDMUJELFFBQU0sQ0FBQ0MsZ0JBQVAsR0FBMEIsSUFBSTFELFdBQUosRUFBMUI7QUFFQXlELFFBQU0sQ0FBQ0wsZ0JBQVAsQ0FBd0IsTUFBeEIsRUFBZ0MsWUFBWTtBQUV4QyxRQUFNTyxRQUFRLEdBQUcvQixRQUFRLENBQUNGLHNCQUFULENBQWdDLFNBQWhDLENBQWpCO0FBRUFJLFNBQUssQ0FBQzhCLElBQU4sQ0FBV0QsUUFBWCxFQUFxQjNCLE9BQXJCLENBQTZCLFVBQUNFLEdBQUQsRUFBUztBQUNsQzJCLFNBQUcsQ0FBQ3BDLElBQUosQ0FBU1MsR0FBVDtBQUNILEtBRkQ7QUFJQSxRQUFNNEIsU0FBUyxHQUFHbEMsUUFBUSxDQUFDRixzQkFBVCxDQUFnQyxxQkFBaEMsQ0FBbEI7O0FBQ0EsUUFBTXFDLG1CQUFtQixHQUFHLFNBQXRCQSxtQkFBc0IsR0FBTTtBQUM5QmpDLFdBQUssQ0FBQzhCLElBQU4sQ0FBV0UsU0FBWCxFQUFzQjlCLE9BQXRCLENBQThCLFVBQUNnQyxRQUFELEVBQWM7QUFDeENBLGdCQUFRLENBQUNDLGtCQUFULENBQTRCOUIsU0FBNUIsQ0FBc0NDLE1BQXRDLENBQTZDLFVBQTdDO0FBQ0gsT0FGRDtBQUdBUixjQUFRLENBQUNzQyxtQkFBVCxDQUE2QixPQUE3QixFQUFzQ0gsbUJBQXRDLEVBQTJELEtBQTNEO0FBQ0gsS0FMRDs7QUFPQWpDLFNBQUssQ0FBQzhCLElBQU4sQ0FBV0UsU0FBWCxFQUFzQjlCLE9BQXRCLENBQThCLFVBQUNnQyxRQUFELEVBQWM7QUFDeENBLGNBQVEsQ0FBQ1osZ0JBQVQsQ0FBMEIsT0FBMUIsRUFBbUMsVUFBU2UsQ0FBVCxFQUFZO0FBQzNDQSxTQUFDLENBQUNDLGVBQUY7QUFDQSxhQUFLSCxrQkFBTCxDQUF3QjlCLFNBQXhCLENBQWtDVyxHQUFsQyxDQUFzQyxVQUF0QztBQUNBbEIsZ0JBQVEsQ0FBQ3dCLGdCQUFULENBQTBCLE9BQTFCLEVBQW1DVyxtQkFBbkMsRUFBd0QsS0FBeEQ7QUFDSCxPQUpELEVBSUcsS0FKSDtBQUtILEtBTkQ7QUFRSCxHQXhCRCxFQXdCRyxLQXhCSDtBQXlCSDs7QUFDTSxJQUFJRixHQUFHLEdBQUdKLE1BQU0sQ0FBQ0MsZ0JBQWpCOztJQUVEekMsd0I7QUFFRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBRUEsb0NBQVlGLEtBQVosRUFBbUJDLFlBQW5CLEVBQWlDO0FBQUE7O0FBQUE7O0FBQzdCO0FBQ0EsU0FBS3FELFNBQUwsR0FBaUJ0RCxLQUFqQjtBQUVBLFNBQUtzRCxTQUFMLENBQWVqQixnQkFBZixDQUFnQyxPQUFoQyxFQUF5QyxZQUFNO0FBQzNDLFVBQUlrQixDQUFKO0FBQUEsVUFBT0MsQ0FBUDtBQUFBLFVBQVVDLENBQVY7QUFBQSxVQUFhQyxHQUFHLEdBQUcsS0FBSSxDQUFDSixTQUFMLENBQWVLLEtBQWxDLENBRDJDLENBQ0g7O0FBQ3hDLFVBQUlDLE1BQU0sR0FBRyxLQUFJLENBQUNOLFNBQUwsQ0FBZU8sVUFBNUIsQ0FGMkMsQ0FFSjtBQUV2QztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFDQTVELGtCQUFZLENBQUN5RCxHQUFELENBQVosQ0FBa0JJLElBQWxCLENBQXVCLFVBQUNDLElBQUQsRUFBVTtBQUFDO0FBQzlCQyxlQUFPLENBQUNDLEdBQVIsQ0FBWUYsSUFBWjtBQUVBOztBQUNBLGFBQUksQ0FBQ0csYUFBTDs7QUFDQSxZQUFJLENBQUNSLEdBQUwsRUFBVTtBQUFFLGlCQUFPLEtBQVA7QUFBYzs7QUFDMUIsYUFBSSxDQUFDUyxZQUFMLEdBQW9CLENBQUMsQ0FBckI7QUFFQTs7QUFDQVosU0FBQyxHQUFHMUMsUUFBUSxDQUFDdUQsYUFBVCxDQUF1QixLQUF2QixDQUFKO0FBQ0FiLFNBQUMsQ0FBQ2MsWUFBRixDQUFlLElBQWYsRUFBcUIsS0FBSSxDQUFDZixTQUFMLENBQWVnQixFQUFmLEdBQW9CLHFCQUF6QztBQUNBZixTQUFDLENBQUNjLFlBQUYsQ0FBZSxPQUFmLEVBQXdCLHlCQUF4QjtBQUVBOztBQUNBVCxjQUFNLENBQUNXLFdBQVAsQ0FBbUJoQixDQUFuQjtBQUVBOztBQUNBLGFBQUtFLENBQUMsR0FBRyxDQUFULEVBQVlBLENBQUMsR0FBR00sSUFBSSxDQUFDdEIsTUFBckIsRUFBNkJnQixDQUFDLEVBQTlCLEVBQWtDO0FBQzlCLGNBQUllLElBQUksU0FBUjtBQUFBLGNBQVViLEtBQUssU0FBZjtBQUVBOztBQUNBLGNBQUksUUFBT0ksSUFBSSxDQUFDTixDQUFELENBQVgsTUFBbUIsUUFBdkIsRUFBaUM7QUFDN0JlLGdCQUFJLEdBQUdULElBQUksQ0FBQ04sQ0FBRCxDQUFKLENBQVEsTUFBUixDQUFQO0FBQ0FFLGlCQUFLLEdBQUdJLElBQUksQ0FBQ04sQ0FBRCxDQUFKLENBQVEsT0FBUixDQUFSO0FBQ0gsV0FIRCxNQUdPO0FBQ0hlLGdCQUFJLEdBQUdULElBQUksQ0FBQ04sQ0FBRCxDQUFYO0FBQ0FFLGlCQUFLLEdBQUdJLElBQUksQ0FBQ04sQ0FBRCxDQUFaO0FBQ0g7QUFFRDs7O0FBQ0EsY0FBSWUsSUFBSSxDQUFDaEMsTUFBTCxDQUFZLENBQVosRUFBZWtCLEdBQUcsQ0FBQ2pCLE1BQW5CLEVBQTJCcEMsV0FBM0IsT0FBNkNxRCxHQUFHLENBQUNyRCxXQUFKLEVBQWpELEVBQW9FO0FBQ2hFO0FBQ0FtRCxhQUFDLEdBQUczQyxRQUFRLENBQUN1RCxhQUFULENBQXVCLEtBQXZCLENBQUo7QUFDQTs7QUFDQVosYUFBQyxDQUFDaUIsU0FBRixHQUFjLGFBQWFELElBQUksQ0FBQ2hDLE1BQUwsQ0FBWSxDQUFaLEVBQWVrQixHQUFHLENBQUNqQixNQUFuQixDQUFiLEdBQTBDLFdBQXhEO0FBQ0FlLGFBQUMsQ0FBQ2lCLFNBQUYsSUFBZUQsSUFBSSxDQUFDaEMsTUFBTCxDQUFZa0IsR0FBRyxDQUFDakIsTUFBaEIsQ0FBZjtBQUVBOztBQUNBZSxhQUFDLENBQUNpQixTQUFGLElBQWUsaUNBQWlDZCxLQUFqQyxHQUF5QyxJQUF4RDtBQUVBSCxhQUFDLENBQUMzQixPQUFGLENBQVU4QixLQUFWLEdBQWtCQSxLQUFsQjtBQUNBSCxhQUFDLENBQUMzQixPQUFGLENBQVUyQyxJQUFWLEdBQWlCQSxJQUFqQjtBQUVBOztBQUNBaEIsYUFBQyxDQUFDbkIsZ0JBQUYsQ0FBbUIsT0FBbkIsRUFBNEIsVUFBQ2UsQ0FBRCxFQUFPO0FBQy9CWSxxQkFBTyxDQUFDQyxHQUFSLG1DQUF1Q2IsQ0FBQyxDQUFDakIsYUFBRixDQUFnQk4sT0FBaEIsQ0FBd0I4QixLQUEvRDtBQUVBOztBQUNBLG1CQUFJLENBQUNMLFNBQUwsQ0FBZUssS0FBZixHQUF1QlAsQ0FBQyxDQUFDakIsYUFBRixDQUFnQk4sT0FBaEIsQ0FBd0IyQyxJQUEvQztBQUNBLG1CQUFJLENBQUNsQixTQUFMLENBQWV6QixPQUFmLENBQXVCNkMsVUFBdkIsR0FBb0N0QixDQUFDLENBQUNqQixhQUFGLENBQWdCTixPQUFoQixDQUF3QjhCLEtBQTVEO0FBRUE7O0FBQ0EsbUJBQUksQ0FBQ08sYUFBTDs7QUFFQSxtQkFBSSxDQUFDWixTQUFMLENBQWVxQixhQUFmLENBQTZCLElBQUlDLEtBQUosQ0FBVSxRQUFWLENBQTdCO0FBQ0gsYUFYRDtBQVlBckIsYUFBQyxDQUFDZ0IsV0FBRixDQUFjZixDQUFkO0FBQ0g7QUFDSjtBQUNKLE9BM0REO0FBNERILEtBaEZEO0FBa0ZBOztBQUNBLFNBQUtGLFNBQUwsQ0FBZWpCLGdCQUFmLENBQWdDLFNBQWhDLEVBQTJDLFVBQUNlLENBQUQsRUFBTztBQUM5QyxVQUFJeUIsQ0FBQyxHQUFHaEUsUUFBUSxDQUFDbUIsY0FBVCxDQUF3QixLQUFJLENBQUNzQixTQUFMLENBQWVnQixFQUFmLEdBQW9CLHFCQUE1QyxDQUFSO0FBQ0EsVUFBSU8sQ0FBSixFQUFPQSxDQUFDLEdBQUdBLENBQUMsQ0FBQ0Msb0JBQUYsQ0FBdUIsS0FBdkIsQ0FBSjs7QUFDUCxVQUFJMUIsQ0FBQyxDQUFDMkIsT0FBRixLQUFjLEVBQWxCLEVBQXNCO0FBQ2xCO0FBQ2hCO0FBQ2dCLGFBQUksQ0FBQ1osWUFBTDtBQUNBOztBQUNBLGFBQUksQ0FBQ2EsU0FBTCxDQUFlSCxDQUFmO0FBQ0gsT0FORCxNQU1PLElBQUl6QixDQUFDLENBQUMyQixPQUFGLEtBQWMsRUFBbEIsRUFBc0I7QUFBRTs7QUFDM0I7QUFDaEI7QUFDZ0IsYUFBSSxDQUFDWixZQUFMO0FBQ0E7O0FBQ0EsYUFBSSxDQUFDYSxTQUFMLENBQWVILENBQWY7QUFDSCxPQU5NLE1BTUEsSUFBSXpCLENBQUMsQ0FBQzJCLE9BQUYsS0FBYyxFQUFsQixFQUFzQjtBQUN6QjtBQUNBM0IsU0FBQyxDQUFDaEIsY0FBRjs7QUFDQSxZQUFJLEtBQUksQ0FBQytCLFlBQUwsR0FBb0IsQ0FBQyxDQUF6QixFQUE0QjtBQUN4QjtBQUNBLGNBQUlVLENBQUosRUFBT0EsQ0FBQyxDQUFDLEtBQUksQ0FBQ1YsWUFBTixDQUFELENBQXFCYyxLQUFyQjtBQUNWO0FBQ0o7QUFDSixLQXZCRDtBQXlCQTs7QUFDQXBFLFlBQVEsQ0FBQ3dCLGdCQUFULENBQTBCLE9BQTFCLEVBQW1DLFVBQUNlLENBQUQsRUFBTztBQUN0QyxXQUFJLENBQUNjLGFBQUwsQ0FBbUJkLENBQUMsQ0FBQ3RCLE1BQXJCO0FBQ0gsS0FGRDtBQUdIOzs7O1dBRUQsbUJBQVUrQyxDQUFWLEVBQWE7QUFDVDtBQUNBLFVBQUksQ0FBQ0EsQ0FBTCxFQUFRLE9BQU8sS0FBUDtBQUNSOztBQUNBLFdBQUtLLFlBQUwsQ0FBa0JMLENBQWxCO0FBQ0EsVUFBSSxLQUFLVixZQUFMLElBQXFCVSxDQUFDLENBQUNwQyxNQUEzQixFQUFtQyxLQUFLMEIsWUFBTCxHQUFvQixDQUFwQjtBQUNuQyxVQUFJLEtBQUtBLFlBQUwsR0FBb0IsQ0FBeEIsRUFBMkIsS0FBS0EsWUFBTCxHQUFxQlUsQ0FBQyxDQUFDcEMsTUFBRixHQUFXLENBQWhDO0FBQzNCOztBQUNBb0MsT0FBQyxDQUFDLEtBQUtWLFlBQU4sQ0FBRCxDQUFxQi9DLFNBQXJCLENBQStCVyxHQUEvQixDQUFtQywwQkFBbkM7QUFDSDs7O1dBRUQsc0JBQWE4QyxDQUFiLEVBQWdCO0FBQ1o7QUFDQSxXQUFLLElBQUlwQixDQUFDLEdBQUcsQ0FBYixFQUFnQkEsQ0FBQyxHQUFHb0IsQ0FBQyxDQUFDcEMsTUFBdEIsRUFBOEJnQixDQUFDLEVBQS9CLEVBQW1DO0FBQy9Cb0IsU0FBQyxDQUFDcEIsQ0FBRCxDQUFELENBQUtyQyxTQUFMLENBQWVDLE1BQWYsQ0FBc0IsMEJBQXRCO0FBQ0g7QUFDSjs7O1dBRUQsdUJBQWNaLE9BQWQsRUFBdUI7QUFDbkJ1RCxhQUFPLENBQUNDLEdBQVIsQ0FBWSxpQkFBWjtBQUNBO0FBQ1I7O0FBQ1EsVUFBSVksQ0FBQyxHQUFHaEUsUUFBUSxDQUFDRixzQkFBVCxDQUFnQyx5QkFBaEMsQ0FBUjs7QUFDQSxXQUFLLElBQUk4QyxDQUFDLEdBQUcsQ0FBYixFQUFnQkEsQ0FBQyxHQUFHb0IsQ0FBQyxDQUFDcEMsTUFBdEIsRUFBOEJnQixDQUFDLEVBQS9CLEVBQW1DO0FBQy9CLFlBQUloRCxPQUFPLEtBQUtvRSxDQUFDLENBQUNwQixDQUFELENBQWIsSUFBb0JoRCxPQUFPLEtBQUssS0FBSzZDLFNBQXpDLEVBQW9EO0FBQ2hEdUIsV0FBQyxDQUFDcEIsQ0FBRCxDQUFELENBQUtJLFVBQUwsQ0FBZ0JzQixXQUFoQixDQUE0Qk4sQ0FBQyxDQUFDcEIsQ0FBRCxDQUE3QjtBQUNIO0FBQ0o7QUFDSjs7OztLQUdMOzs7QUFDQSxJQUFJLENBQUMyQixNQUFNLENBQUNwRSxTQUFQLENBQWlCcUUsTUFBdEIsRUFBOEI7QUFDMUJELFFBQU0sQ0FBQ3BFLFNBQVAsQ0FBaUJxRSxNQUFqQixHQUEwQixZQUFXO0FBQ2pDLFFBQU1DLElBQUksR0FBR0MsU0FBYjtBQUNBLFdBQU8sS0FBS0MsT0FBTCxDQUFhLFVBQWIsRUFBeUIsVUFBU0MsS0FBVCxFQUFnQmxGLE1BQWhCLEVBQXdCO0FBQ3BELGFBQU8sT0FBTytFLElBQUksQ0FBQy9FLE1BQUQsQ0FBWCxLQUF3QixXQUF4QixHQUNEK0UsSUFBSSxDQUFDL0UsTUFBRCxDQURILEdBRURrRixLQUZOO0FBSUgsS0FMTSxDQUFQO0FBTUgsR0FSRDtBQVNILEM7Ozs7Ozs7Ozs7OztBQ3JTRDtBQUFBO0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsQ0FBQyxVQUFVQyxDQUFWLEVBQWE7QUFDVjs7QUFFQWhELFFBQU0sQ0FBQ0wsZ0JBQVAsQ0FBd0IsTUFBeEIsRUFBZ0MsWUFBWTtBQUN4QyxRQUFNc0QsT0FBTyxHQUFHQyxvQ0FBaEI7QUFDQSxRQUFJQyxLQUFLLEdBQUcsQ0FBWjs7QUFFQSxhQUFTQyxlQUFULENBQXlCNUQsS0FBekIsRUFBZ0M7QUFDNUIyRCxXQUFLLElBQUlGLE9BQU8sQ0FBQ0ksUUFBakI7QUFDQUMsNEJBQXNCO0FBQ3pCOztBQUNELGFBQVNDLG1CQUFULENBQTZCL0QsS0FBN0IsRUFBb0M7QUFDaEMyRCxXQUFLLEdBQUdLLElBQUksQ0FBQ0MsR0FBTCxDQUFTLENBQVQsRUFBWU4sS0FBSyxHQUFHRixPQUFPLENBQUNJLFFBQTVCLENBQVI7QUFDQUMsNEJBQXNCO0FBQ3pCLEtBWHVDLENBYXhDOzs7QUFDQSxhQUFTSSxZQUFULEdBQXdCO0FBQ3BCLFVBQUlDLFVBQVUsR0FBR3hGLFFBQVEsQ0FBQ21CLGNBQVQsQ0FBd0Isc0NBQXhCLENBQWpCO0FBQ0EsVUFBSXNFLGNBQWMsR0FBR3pGLFFBQVEsQ0FBQ21CLGNBQVQsQ0FBd0IsMENBQXhCLENBQXJCOztBQUVBLFVBQUlxRSxVQUFKLEVBQWdCO0FBQ1pBLGtCQUFVLENBQUNoRSxnQkFBWCxDQUE0QixPQUE1QixFQUFxQ3lELGVBQXJDO0FBQ0g7O0FBQ0QsVUFBSVEsY0FBSixFQUFvQjtBQUNoQkEsc0JBQWMsQ0FBQ2pFLGdCQUFmLENBQWdDLE9BQWhDLEVBQXlDNEQsbUJBQXpDO0FBQ0g7QUFDSjs7QUFFRCxhQUFTTSxlQUFULEdBQTJCO0FBQ3ZCLFVBQUlGLFVBQVUsR0FBR3hGLFFBQVEsQ0FBQ21CLGNBQVQsQ0FBd0Isc0NBQXhCLENBQWpCO0FBQ0EsVUFBSXNFLGNBQWMsR0FBR3pGLFFBQVEsQ0FBQ21CLGNBQVQsQ0FBd0IsMENBQXhCLENBQXJCOztBQUVBLFVBQUlxRSxVQUFKLEVBQWdCO0FBQ1pBLGtCQUFVLENBQUNsRCxtQkFBWCxDQUErQixPQUEvQixFQUF3QzJDLGVBQXhDO0FBQ0g7O0FBQ0QsVUFBSVEsY0FBSixFQUFvQjtBQUNoQkEsc0JBQWMsQ0FBQ25ELG1CQUFmLENBQW1DLE9BQW5DLEVBQTRDOEMsbUJBQTVDO0FBQ0g7QUFDSjs7QUFFRCxhQUFTRCxzQkFBVCxHQUFrQztBQUM5QixVQUFJUSxHQUFHLEdBQUcsSUFBSUMsY0FBSixFQUFWO0FBQ0FELFNBQUcsQ0FBQ0UsSUFBSixDQUFTLEtBQVQsRUFBZ0JmLE9BQU8sQ0FBQ2dCLE9BQVIsR0FBa0IsZUFBbEIsR0FBb0NqQixDQUFDLENBQUMvRixLQUFGLENBQVE7QUFBQ2lILGVBQU8sRUFBRWpCLE9BQU8sQ0FBQ2lCLE9BQWxCO0FBQTJCZixhQUFLLEVBQUVBLEtBQWxDO0FBQXlDcEQsY0FBTSxFQUFFa0QsT0FBTyxDQUFDSTtBQUF6RCxPQUFSLENBQXBEO0FBQ0FTLFNBQUcsQ0FBQ0ssZ0JBQUosQ0FBcUIsY0FBckIsRUFBcUMsbUNBQXJDO0FBQ0FMLFNBQUcsQ0FBQ0ssZ0JBQUosQ0FBcUIsWUFBckIsRUFBbUNsQixPQUFPLENBQUNtQixVQUEzQzs7QUFDQU4sU0FBRyxDQUFDTyxNQUFKLEdBQWEsWUFBWTtBQUNyQjtBQUNBLFlBQUlDLE9BQU8sS0FBWDs7QUFDQSxZQUFJUixHQUFHLENBQUNTLE1BQUosS0FBZSxHQUFuQixFQUF3QjtBQUNwQixjQUFJQyxXQUFXLEdBQUdDLElBQUksQ0FBQ0MsS0FBTCxDQUFXWixHQUFHLENBQUNhLFFBQWYsQ0FBbEI7O0FBRUEsY0FBS0gsV0FBVyxLQUFLLElBQWhCLElBQXdCQSxXQUFXLENBQUN6RSxNQUFaLEdBQXFCLENBQWxELEVBQXNEO0FBQ2xEdUUsbUJBQU8sa0NBQVA7QUFFQWpHLGlCQUFLLENBQUNDLFNBQU4sQ0FBZ0JDLE9BQWhCLENBQXdCQyxJQUF4QixDQUE2QmdHLFdBQTdCLEVBQTBDLFVBQVNJLFVBQVQsRUFBcUI7QUFFM0ROLHFCQUFPLGtDQUFQO0FBQ0FBLHFCQUFPLG1DQUFQO0FBQ0FBLHFCQUFPLDRCQUFvQk0sVUFBVSxDQUFDQyxJQUEvQix3QkFBK0M1QixPQUFPLENBQUM2QixRQUFSLENBQWlCQyxvQkFBaEUsUUFBUDtBQUNBVCxxQkFBTywrQkFBdUJNLFVBQVUsQ0FBQ0ksTUFBbEMsc0JBQWtESixVQUFVLENBQUNLLElBQTdELFFBQVA7QUFDQVgscUJBQU8sY0FBUDtBQUNBQSxxQkFBTyxjQUFQO0FBQ0FBLHFCQUFPLGlDQUFQO0FBQ0FBLHFCQUFPLDZDQUFvQ00sVUFBVSxDQUFDTSxJQUEvQyxZQUFQO0FBQ0FaLHFCQUFPLDRDQUFtQ00sVUFBVSxDQUFDTyxVQUE5QyxZQUFQO0FBQ0FiLHFCQUFPLG9DQUFQOztBQUNBLGtCQUFLLFFBQVFNLFVBQVUsQ0FBQ1EsZ0JBQXhCLEVBQTJDO0FBQ3ZDZCx1QkFBTyw0Q0FBbUNyQixPQUFPLENBQUM2QixRQUFSLENBQWlCTyxRQUFwRCxZQUFQO0FBQ0gsZUFGRCxNQUVPO0FBQ0hmLHVCQUFPLDRDQUFtQ3JCLE9BQU8sQ0FBQzZCLFFBQVIsQ0FBaUJRLGtCQUFwRCxZQUFQO0FBQ0g7O0FBQ0RoQixxQkFBTyxpQkFBUDtBQUNBQSxxQkFBTyxvQ0FBUDs7QUFDQSxrQkFBSyxRQUFRTSxVQUFVLENBQUNXLFdBQXhCLEVBQXNDO0FBQ2xDakIsdUJBQU8sd0JBQWdCTSxVQUFVLENBQUNZLGVBQTNCLGdCQUErQ1osVUFBVSxDQUFDYSxXQUExRCxVQUFQOztBQUNBLG9CQUFLLElBQUliLFVBQVUsQ0FBQ2MsWUFBcEIsRUFBbUM7QUFDL0JwQix5QkFBTyxjQUFPTSxVQUFVLENBQUNjLFlBQWxCLENBQVA7QUFDSCxpQkFGRCxNQUVPO0FBQ0hwQix5QkFBTyxhQUFQO0FBQ0g7QUFDSixlQVBELE1BT087QUFDSEEsdUJBQU8sd0JBQWdCTSxVQUFVLENBQUNlLG9CQUEzQixnQkFBb0QxQyxPQUFPLENBQUM2QixRQUFSLENBQWlCYyxlQUFyRSxTQUFQO0FBQ0g7O0FBQ0R0QixxQkFBTyxpQkFBUDtBQUNBQSxxQkFBTyxvQ0FBUDtBQUNBQSxxQkFBTyw2REFBbURNLFVBQVUsQ0FBQ0MsSUFBOUQsbURBQXdHNUIsT0FBTyxDQUFDNkIsUUFBUixDQUFpQmUsU0FBekgsY0FBUDtBQUNBdkIscUJBQU8sNkRBQW1ETSxVQUFVLENBQUNrQixhQUE5RCxvREFBa0g3QyxPQUFPLENBQUM2QixRQUFSLENBQWlCaUIsUUFBbkksY0FBUDtBQUNBekIscUJBQU8sZUFBUDtBQUNBQSxxQkFBTyxjQUFQO0FBQ0FBLHFCQUFPLDBDQUFQO0FBQ0FBLHFCQUFPLFlBQVA7QUFDSCxhQXJDRDtBQXVDQUEsbUJBQU8sWUFBUDtBQUNBQSxtQkFBTyxtREFBUDtBQUNBQSxtQkFBTyw0RUFBUDtBQUNBQSxtQkFBTyx3RUFBUDtBQUNBQSxtQkFBTyxZQUFQO0FBQ0gsV0EvQ0QsTUErQ087QUFDSEEsbUJBQU8sMkNBQWtDckIsT0FBTyxDQUFDNkIsUUFBUixDQUFpQmtCLGdCQUFuRCxTQUFQO0FBQ0g7QUFDSixTQXJERCxNQXFETztBQUNIMUIsaUJBQU8sMkNBQWtDckIsT0FBTyxDQUFDNkIsUUFBUixDQUFpQm1CLEtBQW5ELFNBQVA7QUFDSDs7QUFFRHBDLHVCQUFlO0FBQ2YxRixnQkFBUSxDQUFDbUIsY0FBVCxDQUF3QiwrQkFBeEIsRUFBeUR5QyxTQUF6RCxHQUFxRXVDLE9BQXJFO0FBQ0FaLG9CQUFZO0FBQ2YsT0EvREQ7O0FBaUVBSSxTQUFHLENBQUNvQyxJQUFKO0FBQ0g7O0FBRUQ1QywwQkFBc0I7QUFDekIsR0FoSEQsRUFnSEcsS0FoSEg7QUFpSEgsQ0FwSEQsRUFvSEdsRCxtREFwSEgsRSIsImZpbGUiOiJ1cGNvbWluZy10b3VybmFtZW50LWxpc3QuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gNDQpO1xuIiwiJ3VzZSBzdHJpY3QnO1xyXG5jbGFzcyBUb3VybmFtYXRjaCB7XHJcblxyXG4gICAgY29uc3RydWN0b3IoKSB7XHJcbiAgICAgICAgdGhpcy5ldmVudHMgPSB7fTtcclxuICAgIH1cclxuXHJcbiAgICBwYXJhbShvYmplY3QsIHByZWZpeCkge1xyXG4gICAgICAgIGxldCBzdHIgPSBbXTtcclxuICAgICAgICBmb3IgKGxldCBwcm9wIGluIG9iamVjdCkge1xyXG4gICAgICAgICAgICBpZiAob2JqZWN0Lmhhc093blByb3BlcnR5KHByb3ApKSB7XHJcbiAgICAgICAgICAgICAgICBsZXQgayA9IHByZWZpeCA/IHByZWZpeCArIFwiW1wiICsgcHJvcCArIFwiXVwiIDogcHJvcDtcclxuICAgICAgICAgICAgICAgIGxldCB2ID0gb2JqZWN0W3Byb3BdO1xyXG4gICAgICAgICAgICAgICAgc3RyLnB1c2goKHYgIT09IG51bGwgJiYgdHlwZW9mIHYgPT09IFwib2JqZWN0XCIpID8gdGhpcy5wYXJhbSh2LCBrKSA6IGVuY29kZVVSSUNvbXBvbmVudChrKSArIFwiPVwiICsgZW5jb2RlVVJJQ29tcG9uZW50KHYpKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gc3RyLmpvaW4oXCImXCIpO1xyXG4gICAgfVxyXG5cclxuICAgIGV2ZW50KGV2ZW50TmFtZSkge1xyXG4gICAgICAgIGlmICghKGV2ZW50TmFtZSBpbiB0aGlzLmV2ZW50cykpIHtcclxuICAgICAgICAgICAgdGhpcy5ldmVudHNbZXZlbnROYW1lXSA9IG5ldyBFdmVudFRhcmdldChldmVudE5hbWUpO1xyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gdGhpcy5ldmVudHNbZXZlbnROYW1lXTtcclxuICAgIH1cclxuXHJcbiAgICBhdXRvY29tcGxldGUoaW5wdXQsIGRhdGFDYWxsYmFjaykge1xyXG4gICAgICAgIG5ldyBUb3VybmFtYXRjaF9BdXRvY29tcGxldGUoaW5wdXQsIGRhdGFDYWxsYmFjayk7XHJcbiAgICB9XHJcblxyXG4gICAgdWNmaXJzdChzKSB7XHJcbiAgICAgICAgaWYgKHR5cGVvZiBzICE9PSAnc3RyaW5nJykgcmV0dXJuICcnO1xyXG4gICAgICAgIHJldHVybiBzLmNoYXJBdCgwKS50b1VwcGVyQ2FzZSgpICsgcy5zbGljZSgxKTtcclxuICAgIH1cclxuXHJcbiAgICBvcmRpbmFsX3N1ZmZpeChudW1iZXIpIHtcclxuICAgICAgICBjb25zdCByZW1haW5kZXIgPSBudW1iZXIgJSAxMDA7XHJcblxyXG4gICAgICAgIGlmICgocmVtYWluZGVyIDwgMTEpIHx8IChyZW1haW5kZXIgPiAxMykpIHtcclxuICAgICAgICAgICAgc3dpdGNoIChyZW1haW5kZXIgJSAxMCkge1xyXG4gICAgICAgICAgICAgICAgY2FzZSAxOiByZXR1cm4gJ3N0JztcclxuICAgICAgICAgICAgICAgIGNhc2UgMjogcmV0dXJuICduZCc7XHJcbiAgICAgICAgICAgICAgICBjYXNlIDM6IHJldHVybiAncmQnO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiAndGgnO1xyXG4gICAgfVxyXG5cclxuICAgIHRhYnMoZWxlbWVudCkge1xyXG4gICAgICAgIGNvbnN0IHRhYnMgPSBlbGVtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1uYXYtbGluaycpO1xyXG4gICAgICAgIGNvbnN0IHBhbmVzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLXRhYi1wYW5lJyk7XHJcbiAgICAgICAgY29uc3QgY2xlYXJBY3RpdmUgPSAoKSA9PiB7XHJcbiAgICAgICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwodGFicywgKHRhYikgPT4ge1xyXG4gICAgICAgICAgICAgICAgdGFiLmNsYXNzTGlzdC5yZW1vdmUoJ3Rybi1uYXYtYWN0aXZlJyk7XHJcbiAgICAgICAgICAgICAgICB0YWIuYXJpYVNlbGVjdGVkID0gZmFsc2U7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHBhbmVzLCBwYW5lID0+IHBhbmUuY2xhc3NMaXN0LnJlbW92ZSgndHJuLXRhYi1hY3RpdmUnKSk7XHJcbiAgICAgICAgfTtcclxuICAgICAgICBjb25zdCBzZXRBY3RpdmUgPSAodGFyZ2V0SWQpID0+IHtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0VGFiID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignYVtocmVmPVwiIycgKyB0YXJnZXRJZCArICdcIl0udHJuLW5hdi1saW5rJyk7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFBhbmVJZCA9IHRhcmdldFRhYiAmJiB0YXJnZXRUYWIuZGF0YXNldCAmJiB0YXJnZXRUYWIuZGF0YXNldC50YXJnZXQgfHwgZmFsc2U7XHJcblxyXG4gICAgICAgICAgICBpZiAodGFyZ2V0UGFuZUlkKSB7XHJcbiAgICAgICAgICAgICAgICBjbGVhckFjdGl2ZSgpO1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0VGFiLmNsYXNzTGlzdC5hZGQoJ3Rybi1uYXYtYWN0aXZlJyk7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRUYWIuYXJpYVNlbGVjdGVkID0gdHJ1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0YXJnZXRQYW5lSWQpLmNsYXNzTGlzdC5hZGQoJ3Rybi10YWItYWN0aXZlJyk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9O1xyXG4gICAgICAgIGNvbnN0IHRhYkNsaWNrID0gKGV2ZW50KSA9PiB7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFRhYiA9IGV2ZW50LmN1cnJlbnRUYXJnZXQ7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFBhbmVJZCA9IHRhcmdldFRhYiAmJiB0YXJnZXRUYWIuZGF0YXNldCAmJiB0YXJnZXRUYWIuZGF0YXNldC50YXJnZXQgfHwgZmFsc2U7XHJcblxyXG4gICAgICAgICAgICBpZiAodGFyZ2V0UGFuZUlkKSB7XHJcbiAgICAgICAgICAgICAgICBzZXRBY3RpdmUodGFyZ2V0UGFuZUlkKTtcclxuICAgICAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9O1xyXG5cclxuICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHRhYnMsICh0YWIpID0+IHtcclxuICAgICAgICAgICAgdGFiLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGFiQ2xpY2spO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICBpZiAobG9jYXRpb24uaGFzaCkge1xyXG4gICAgICAgICAgICBzZXRBY3RpdmUobG9jYXRpb24uaGFzaC5zdWJzdHIoMSkpO1xyXG4gICAgICAgIH0gZWxzZSBpZiAodGFicy5sZW5ndGggPiAwKSB7XHJcbiAgICAgICAgICAgIHNldEFjdGl2ZSh0YWJzWzBdLmRhdGFzZXQudGFyZ2V0KTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcblxyXG59XHJcblxyXG4vL3Rybi5pbml0aWFsaXplKCk7XHJcbmlmICghd2luZG93LnRybl9vYmpfaW5zdGFuY2UpIHtcclxuICAgIHdpbmRvdy50cm5fb2JqX2luc3RhbmNlID0gbmV3IFRvdXJuYW1hdGNoKCk7XHJcblxyXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCBmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgIGNvbnN0IHRhYlZpZXdzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLW5hdicpO1xyXG5cclxuICAgICAgICBBcnJheS5mcm9tKHRhYlZpZXdzKS5mb3JFYWNoKCh0YWIpID0+IHtcclxuICAgICAgICAgICAgdHJuLnRhYnModGFiKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgY29uc3QgZHJvcGRvd25zID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLWRyb3Bkb3duLXRvZ2dsZScpO1xyXG4gICAgICAgIGNvbnN0IGhhbmRsZURyb3Bkb3duQ2xvc2UgPSAoKSA9PiB7XHJcbiAgICAgICAgICAgIEFycmF5LmZyb20oZHJvcGRvd25zKS5mb3JFYWNoKChkcm9wZG93bikgPT4ge1xyXG4gICAgICAgICAgICAgICAgZHJvcGRvd24ubmV4dEVsZW1lbnRTaWJsaW5nLmNsYXNzTGlzdC5yZW1vdmUoJ3Rybi1zaG93Jyk7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICBkb2N1bWVudC5yZW1vdmVFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgaGFuZGxlRHJvcGRvd25DbG9zZSwgZmFsc2UpO1xyXG4gICAgICAgIH07XHJcblxyXG4gICAgICAgIEFycmF5LmZyb20oZHJvcGRvd25zKS5mb3JFYWNoKChkcm9wZG93bikgPT4ge1xyXG4gICAgICAgICAgICBkcm9wZG93bi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uKGUpIHtcclxuICAgICAgICAgICAgICAgIGUuc3RvcFByb3BhZ2F0aW9uKCk7XHJcbiAgICAgICAgICAgICAgICB0aGlzLm5leHRFbGVtZW50U2libGluZy5jbGFzc0xpc3QuYWRkKCd0cm4tc2hvdycpO1xyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGhhbmRsZURyb3Bkb3duQ2xvc2UsIGZhbHNlKTtcclxuICAgICAgICAgICAgfSwgZmFsc2UpO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgIH0sIGZhbHNlKTtcclxufVxyXG5leHBvcnQgbGV0IHRybiA9IHdpbmRvdy50cm5fb2JqX2luc3RhbmNlO1xyXG5cclxuY2xhc3MgVG91cm5hbWF0Y2hfQXV0b2NvbXBsZXRlIHtcclxuXHJcbiAgICAvLyBjdXJyZW50Rm9jdXM7XHJcbiAgICAvL1xyXG4gICAgLy8gbmFtZUlucHV0O1xyXG4gICAgLy9cclxuICAgIC8vIHNlbGY7XHJcblxyXG4gICAgY29uc3RydWN0b3IoaW5wdXQsIGRhdGFDYWxsYmFjaykge1xyXG4gICAgICAgIC8vIHRoaXMuc2VsZiA9IHRoaXM7XHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQgPSBpbnB1dDtcclxuXHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcihcImlucHV0XCIsICgpID0+IHtcclxuICAgICAgICAgICAgbGV0IGEsIGIsIGksIHZhbCA9IHRoaXMubmFtZUlucHV0LnZhbHVlOy8vdGhpcy52YWx1ZTtcclxuICAgICAgICAgICAgbGV0IHBhcmVudCA9IHRoaXMubmFtZUlucHV0LnBhcmVudE5vZGU7Ly90aGlzLnBhcmVudE5vZGU7XHJcblxyXG4gICAgICAgICAgICAvLyBsZXQgcCA9IG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpID0+IHtcclxuICAgICAgICAgICAgLy8gICAgIC8qIG5lZWQgdG8gcXVlcnkgc2VydmVyIGZvciBuYW1lcyBoZXJlLiAqL1xyXG4gICAgICAgICAgICAvLyAgICAgbGV0IHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLm9wZW4oJ0dFVCcsIG9wdGlvbnMuYXBpX3VybCArICdwbGF5ZXJzLz9zZWFyY2g9JyArIHZhbCArICcmcGVyX3BhZ2U9NScpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMCkge1xyXG4gICAgICAgICAgICAvLyAgICAgICAgICAgICAvLyByZXNvbHZlKEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKS5tYXAoKHBsYXllcikgPT4ge3JldHVybiB7ICd2YWx1ZSc6IHBsYXllci5pZCwgJ3RleHQnOiBwbGF5ZXIubmFtZSB9O30pKTtcclxuICAgICAgICAgICAgLy8gICAgICAgICAgICAgcmVzb2x2ZShKU09OLnBhcnNlKHhoci5yZXNwb25zZSkubWFwKChwbGF5ZXIpID0+IHtyZXR1cm4gcGxheWVyLm5hbWU7fSkpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgICAgIHJlamVjdCgpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gICAgIH07XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIuc2VuZCgpO1xyXG4gICAgICAgICAgICAvLyB9KTtcclxuICAgICAgICAgICAgZGF0YUNhbGxiYWNrKHZhbCkudGhlbigoZGF0YSkgPT4gey8vcC50aGVuKChkYXRhKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhkYXRhKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmNsb3NlIGFueSBhbHJlYWR5IG9wZW4gbGlzdHMgb2YgYXV0by1jb21wbGV0ZWQgdmFsdWVzKi9cclxuICAgICAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cygpO1xyXG4gICAgICAgICAgICAgICAgaWYgKCF2YWwpIHsgcmV0dXJuIGZhbHNlO31cclxuICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudEZvY3VzID0gLTE7XHJcblxyXG4gICAgICAgICAgICAgICAgLypjcmVhdGUgYSBESVYgZWxlbWVudCB0aGF0IHdpbGwgY29udGFpbiB0aGUgaXRlbXMgKHZhbHVlcyk6Ki9cclxuICAgICAgICAgICAgICAgIGEgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiRElWXCIpO1xyXG4gICAgICAgICAgICAgICAgYS5zZXRBdHRyaWJ1dGUoXCJpZFwiLCB0aGlzLm5hbWVJbnB1dC5pZCArIFwiLWF1dG8tY29tcGxldGUtbGlzdFwiKTtcclxuICAgICAgICAgICAgICAgIGEuc2V0QXR0cmlidXRlKFwiY2xhc3NcIiwgXCJ0cm4tYXV0by1jb21wbGV0ZS1pdGVtc1wiKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmFwcGVuZCB0aGUgRElWIGVsZW1lbnQgYXMgYSBjaGlsZCBvZiB0aGUgYXV0by1jb21wbGV0ZSBjb250YWluZXI6Ki9cclxuICAgICAgICAgICAgICAgIHBhcmVudC5hcHBlbmRDaGlsZChhKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmZvciBlYWNoIGl0ZW0gaW4gdGhlIGFycmF5Li4uKi9cclxuICAgICAgICAgICAgICAgIGZvciAoaSA9IDA7IGkgPCBkYXRhLmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IHRleHQsIHZhbHVlO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAvKiBXaGljaCBmb3JtYXQgZGlkIHRoZXkgZ2l2ZSB1cy4gKi9cclxuICAgICAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGRhdGFbaV0gPT09ICdvYmplY3QnKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHRleHQgPSBkYXRhW2ldWyd0ZXh0J107XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlID0gZGF0YVtpXVsndmFsdWUnXTtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB0ZXh0ID0gZGF0YVtpXTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSBkYXRhW2ldO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICAgICAgLypjaGVjayBpZiB0aGUgaXRlbSBzdGFydHMgd2l0aCB0aGUgc2FtZSBsZXR0ZXJzIGFzIHRoZSB0ZXh0IGZpZWxkIHZhbHVlOiovXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHRleHQuc3Vic3RyKDAsIHZhbC5sZW5ndGgpLnRvVXBwZXJDYXNlKCkgPT09IHZhbC50b1VwcGVyQ2FzZSgpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qY3JlYXRlIGEgRElWIGVsZW1lbnQgZm9yIGVhY2ggbWF0Y2hpbmcgZWxlbWVudDoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcIkRJVlwiKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgLyptYWtlIHRoZSBtYXRjaGluZyBsZXR0ZXJzIGJvbGQ6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgPSBcIjxzdHJvbmc+XCIgKyB0ZXh0LnN1YnN0cigwLCB2YWwubGVuZ3RoKSArIFwiPC9zdHJvbmc+XCI7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuaW5uZXJIVE1MICs9IHRleHQuc3Vic3RyKHZhbC5sZW5ndGgpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgLyppbnNlcnQgYSBpbnB1dCBmaWVsZCB0aGF0IHdpbGwgaG9sZCB0aGUgY3VycmVudCBhcnJheSBpdGVtJ3MgdmFsdWU6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgKz0gXCI8aW5wdXQgdHlwZT0naGlkZGVuJyB2YWx1ZT0nXCIgKyB2YWx1ZSArIFwiJz5cIjtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuZGF0YXNldC52YWx1ZSA9IHZhbHVlO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmRhdGFzZXQudGV4dCA9IHRleHQ7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiB3aGVuIHNvbWVvbmUgY2xpY2tzIG9uIHRoZSBpdGVtIHZhbHVlIChESVYgZWxlbWVudCk6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKGUpID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKGBpdGVtIGNsaWNrZWQgd2l0aCB2YWx1ZSAke2UuY3VycmVudFRhcmdldC5kYXRhc2V0LnZhbHVlfWApO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8qIGluc2VydCB0aGUgdmFsdWUgZm9yIHRoZSBhdXRvY29tcGxldGUgdGV4dCBmaWVsZDogKi9cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LnZhbHVlID0gZS5jdXJyZW50VGFyZ2V0LmRhdGFzZXQudGV4dDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LmRhdGFzZXQuc2VsZWN0ZWRJZCA9IGUuY3VycmVudFRhcmdldC5kYXRhc2V0LnZhbHVlO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8qIGNsb3NlIHRoZSBsaXN0IG9mIGF1dG9jb21wbGV0ZWQgdmFsdWVzLCAob3IgYW55IG90aGVyIG9wZW4gbGlzdHMgb2YgYXV0b2NvbXBsZXRlZCB2YWx1ZXM6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cygpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LmRpc3BhdGNoRXZlbnQobmV3IEV2ZW50KCdjaGFuZ2UnKSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBhLmFwcGVuZENoaWxkKGIpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHByZXNzZXMgYSBrZXkgb24gdGhlIGtleWJvYXJkOiovXHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcihcImtleWRvd25cIiwgKGUpID0+IHtcclxuICAgICAgICAgICAgbGV0IHggPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0aGlzLm5hbWVJbnB1dC5pZCArIFwiLWF1dG8tY29tcGxldGUtbGlzdFwiKTtcclxuICAgICAgICAgICAgaWYgKHgpIHggPSB4LmdldEVsZW1lbnRzQnlUYWdOYW1lKFwiZGl2XCIpO1xyXG4gICAgICAgICAgICBpZiAoZS5rZXlDb2RlID09PSA0MCkge1xyXG4gICAgICAgICAgICAgICAgLypJZiB0aGUgYXJyb3cgRE9XTiBrZXkgaXMgcHJlc3NlZCxcclxuICAgICAgICAgICAgICAgICBpbmNyZWFzZSB0aGUgY3VycmVudEZvY3VzIHZhcmlhYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmN1cnJlbnRGb2N1cysrO1xyXG4gICAgICAgICAgICAgICAgLyphbmQgYW5kIG1ha2UgdGhlIGN1cnJlbnQgaXRlbSBtb3JlIHZpc2libGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuYWRkQWN0aXZlKHgpO1xyXG4gICAgICAgICAgICB9IGVsc2UgaWYgKGUua2V5Q29kZSA9PT0gMzgpIHsgLy91cFxyXG4gICAgICAgICAgICAgICAgLypJZiB0aGUgYXJyb3cgVVAga2V5IGlzIHByZXNzZWQsXHJcbiAgICAgICAgICAgICAgICAgZGVjcmVhc2UgdGhlIGN1cnJlbnRGb2N1cyB2YXJpYWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50Rm9jdXMtLTtcclxuICAgICAgICAgICAgICAgIC8qYW5kIGFuZCBtYWtlIHRoZSBjdXJyZW50IGl0ZW0gbW9yZSB2aXNpYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmFkZEFjdGl2ZSh4KTtcclxuICAgICAgICAgICAgfSBlbHNlIGlmIChlLmtleUNvZGUgPT09IDEzKSB7XHJcbiAgICAgICAgICAgICAgICAvKklmIHRoZSBFTlRFUiBrZXkgaXMgcHJlc3NlZCwgcHJldmVudCB0aGUgZm9ybSBmcm9tIGJlaW5nIHN1Ym1pdHRlZCwqL1xyXG4gICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzID4gLTEpIHtcclxuICAgICAgICAgICAgICAgICAgICAvKmFuZCBzaW11bGF0ZSBhIGNsaWNrIG9uIHRoZSBcImFjdGl2ZVwiIGl0ZW06Ki9cclxuICAgICAgICAgICAgICAgICAgICBpZiAoeCkgeFt0aGlzLmN1cnJlbnRGb2N1c10uY2xpY2soKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiB3aGVuIHNvbWVvbmUgY2xpY2tzIGluIHRoZSBkb2N1bWVudDoqL1xyXG4gICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICB0aGlzLmNsb3NlQWxsTGlzdHMoZS50YXJnZXQpO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG5cclxuICAgIGFkZEFjdGl2ZSh4KSB7XHJcbiAgICAgICAgLyphIGZ1bmN0aW9uIHRvIGNsYXNzaWZ5IGFuIGl0ZW0gYXMgXCJhY3RpdmVcIjoqL1xyXG4gICAgICAgIGlmICgheCkgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgIC8qc3RhcnQgYnkgcmVtb3ZpbmcgdGhlIFwiYWN0aXZlXCIgY2xhc3Mgb24gYWxsIGl0ZW1zOiovXHJcbiAgICAgICAgdGhpcy5yZW1vdmVBY3RpdmUoeCk7XHJcbiAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzID49IHgubGVuZ3RoKSB0aGlzLmN1cnJlbnRGb2N1cyA9IDA7XHJcbiAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzIDwgMCkgdGhpcy5jdXJyZW50Rm9jdXMgPSAoeC5sZW5ndGggLSAxKTtcclxuICAgICAgICAvKmFkZCBjbGFzcyBcImF1dG9jb21wbGV0ZS1hY3RpdmVcIjoqL1xyXG4gICAgICAgIHhbdGhpcy5jdXJyZW50Rm9jdXNdLmNsYXNzTGlzdC5hZGQoXCJ0cm4tYXV0by1jb21wbGV0ZS1hY3RpdmVcIik7XHJcbiAgICB9XHJcblxyXG4gICAgcmVtb3ZlQWN0aXZlKHgpIHtcclxuICAgICAgICAvKmEgZnVuY3Rpb24gdG8gcmVtb3ZlIHRoZSBcImFjdGl2ZVwiIGNsYXNzIGZyb20gYWxsIGF1dG9jb21wbGV0ZSBpdGVtczoqL1xyXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgeC5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICB4W2ldLmNsYXNzTGlzdC5yZW1vdmUoXCJ0cm4tYXV0by1jb21wbGV0ZS1hY3RpdmVcIik7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIGNsb3NlQWxsTGlzdHMoZWxlbWVudCkge1xyXG4gICAgICAgIGNvbnNvbGUubG9nKFwiY2xvc2UgYWxsIGxpc3RzXCIpO1xyXG4gICAgICAgIC8qY2xvc2UgYWxsIGF1dG9jb21wbGV0ZSBsaXN0cyBpbiB0aGUgZG9jdW1lbnQsXHJcbiAgICAgICAgIGV4Y2VwdCB0aGUgb25lIHBhc3NlZCBhcyBhbiBhcmd1bWVudDoqL1xyXG4gICAgICAgIGxldCB4ID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShcInRybi1hdXRvLWNvbXBsZXRlLWl0ZW1zXCIpO1xyXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgeC5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICBpZiAoZWxlbWVudCAhPT0geFtpXSAmJiBlbGVtZW50ICE9PSB0aGlzLm5hbWVJbnB1dCkge1xyXG4gICAgICAgICAgICAgICAgeFtpXS5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKHhbaV0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG59XHJcblxyXG4vLyBGaXJzdCwgY2hlY2tzIGlmIGl0IGlzbid0IGltcGxlbWVudGVkIHlldC5cclxuaWYgKCFTdHJpbmcucHJvdG90eXBlLmZvcm1hdCkge1xyXG4gICAgU3RyaW5nLnByb3RvdHlwZS5mb3JtYXQgPSBmdW5jdGlvbigpIHtcclxuICAgICAgICBjb25zdCBhcmdzID0gYXJndW1lbnRzO1xyXG4gICAgICAgIHJldHVybiB0aGlzLnJlcGxhY2UoL3soXFxkKyl9L2csIGZ1bmN0aW9uKG1hdGNoLCBudW1iZXIpIHtcclxuICAgICAgICAgICAgcmV0dXJuIHR5cGVvZiBhcmdzW251bWJlcl0gIT09ICd1bmRlZmluZWQnXHJcbiAgICAgICAgICAgICAgICA/IGFyZ3NbbnVtYmVyXVxyXG4gICAgICAgICAgICAgICAgOiBtYXRjaFxyXG4gICAgICAgICAgICAgICAgO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfTtcclxufSIsIi8qKlxyXG4gKiBIYW5kbGVzIGV2ZW50cyBmb3IgdGhlIHRvdXJuYW1lbnQgbGlzdCB0aGF0IGRpc3BsYXlzIGluIHRoZSB1cGNvbWluZyB0b3VybmFtZW50cyBzaG9ydGNvZGUuXHJcbiAqXHJcbiAqIEBsaW5rICAgICAgIGh0dHBzOi8vd3d3LnRvdXJuYW1hdGNoLmNvbVxyXG4gKiBAc2luY2UgICAgICAzLjEzLjBcclxuICAqXHJcbiAqIEBwYWNrYWdlICAgIFRvdXJuYW1hdGNoXHJcbiAqXHJcbiAqL1xyXG5pbXBvcnQgeyB0cm4gfSBmcm9tICcuL3RvdXJuYW1hdGNoLmpzJztcclxuXHJcbihmdW5jdGlvbiAoJCkge1xyXG4gICAgJ3VzZSBzdHJpY3QnO1xyXG5cclxuICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIGNvbnN0IG9wdGlvbnMgPSB0cm5fdXBjb21pbmdfdG91cm5hbWVudF9saXN0X29wdGlvbnM7XHJcbiAgICAgICAgbGV0IHN0YXJ0ID0gMDtcclxuXHJcbiAgICAgICAgZnVuY3Rpb24gaGFuZGxlTmV4dENsaWNrKGV2ZW50KSB7XHJcbiAgICAgICAgICAgIHN0YXJ0ICs9IG9wdGlvbnMucGFnaW5hdGU7XHJcbiAgICAgICAgICAgIGdldFVwY29taW5nVG91cm5hbWVudHMoKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgZnVuY3Rpb24gaGFuZGxlUHJldmlvdXNDbGljayhldmVudCkge1xyXG4gICAgICAgICAgICBzdGFydCA9IE1hdGgubWF4KDAsIHN0YXJ0IC0gb3B0aW9ucy5wYWdpbmF0ZSk7XHJcbiAgICAgICAgICAgIGdldFVwY29taW5nVG91cm5hbWVudHMoKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIC8vIEFjY2VwdCBqb2luIHRlYW0gaW52aXRhdGlvbiBsaW5rcy5cclxuICAgICAgICBmdW5jdGlvbiBhZGRMaXN0ZW5lcnMoKSB7XHJcbiAgICAgICAgICAgIGxldCBuZXh0QnV0dG9uID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Rybi11cGNvbWluZy10b3VybmFtZW50cy1uZXh0LWJ1dHRvbicpO1xyXG4gICAgICAgICAgICBsZXQgcHJldmlvdXNCdXR0b24gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLXVwY29taW5nLXRvdXJuYW1lbnRzLXByZXZpb3VzLWJ1dHRvbicpO1xyXG5cclxuICAgICAgICAgICAgaWYgKG5leHRCdXR0b24pIHtcclxuICAgICAgICAgICAgICAgIG5leHRCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBoYW5kbGVOZXh0Q2xpY2spO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGlmIChwcmV2aW91c0J1dHRvbikge1xyXG4gICAgICAgICAgICAgICAgcHJldmlvdXNCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBoYW5kbGVQcmV2aW91c0NsaWNrKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZnVuY3Rpb24gcmVtb3ZlTGlzdGVuZXJzKCkge1xyXG4gICAgICAgICAgICBsZXQgbmV4dEJ1dHRvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tdXBjb21pbmctdG91cm5hbWVudHMtbmV4dC1idXR0b24nKTtcclxuICAgICAgICAgICAgbGV0IHByZXZpb3VzQnV0dG9uID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Rybi11cGNvbWluZy10b3VybmFtZW50cy1wcmV2aW91cy1idXR0b24nKTtcclxuXHJcbiAgICAgICAgICAgIGlmIChuZXh0QnV0dG9uKSB7XHJcbiAgICAgICAgICAgICAgICBuZXh0QnV0dG9uLnJlbW92ZUV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgaGFuZGxlTmV4dENsaWNrKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICBpZiAocHJldmlvdXNCdXR0b24pIHtcclxuICAgICAgICAgICAgICAgIHByZXZpb3VzQnV0dG9uLnJlbW92ZUV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgaGFuZGxlUHJldmlvdXNDbGljayk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIGdldFVwY29taW5nVG91cm5hbWVudHMoKSB7XHJcbiAgICAgICAgICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgeGhyLm9wZW4oJ0dFVCcsIG9wdGlvbnMuYXBpX3VybCArICd0b3VybmFtZW50cy8/JyArICQucGFyYW0oe2dhbWVfaWQ6IG9wdGlvbnMuZ2FtZV9pZCwgc3RhcnQ6IHN0YXJ0LCBsZW5ndGg6IG9wdGlvbnMucGFnaW5hdGV9KSk7XHJcbiAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdDb250ZW50LVR5cGUnLCAnYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkJyk7XHJcbiAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgIC8vY29uc29sZS5sb2coeGhyLnJlc3BvbnNlKTtcclxuICAgICAgICAgICAgICAgIGxldCBjb250ZW50ID0gYGA7XHJcbiAgICAgICAgICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IHRvdXJuYW1lbnRzID0gSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBpZiAoIHRvdXJuYW1lbnRzICE9PSBudWxsICYmIHRvdXJuYW1lbnRzLmxlbmd0aCA+IDAgKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxkaXYgY2xhc3M9XCJpdGVtcy13cmFwcGVyXCJgO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbCh0b3VybmFtZW50cywgZnVuY3Rpb24odG91cm5hbWVudCkge1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxkaXYgY2xhc3M9XCJpdGVtLXdyYXBwZXJcIj5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgICA8ZGl2IGNsYXNzPVwiaXRlbS1hdmF0YXJcIj5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgICAgIDxhIGhyZWY9XCIke3RvdXJuYW1lbnQubGlua31cIiB0aXRsZT1cIiR7b3B0aW9ucy5sYW5ndWFnZS52aWV3X3RvdXJuYW1lbnRfaW5mb31cIj5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgICAgICAgPGltZyBzcmM9XCIke3RvdXJuYW1lbnQuYXZhdGFyfVwiIGFsdD1cIiR7dG91cm5hbWVudC5nYW1lfVwiPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGAgICAgPC9hPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGAgIDwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGAgIDxkaXYgY2xhc3M9XCJpdGVtLWluZm9cIj5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgICAgIDxzcGFuIGNsYXNzPVwiaXRlbS10aXRsZVwiPiR7dG91cm5hbWVudC5uYW1lfTwvc3Bhbj5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgICAgIDxzcGFuIGNsYXNzPVwiaXRlbS1tZXRhXCI+JHt0b3VybmFtZW50LnN0YXJ0X2RhdGV9PC9zcGFuPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGAgICAgPHNwYW4gY2xhc3M9XCJpdGVtLW1ldGFcIj5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKCAnMScgPT09IHRvdXJuYW1lbnQuZWxpbWluYXRpb25fbW9kZSApIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGAgICAgPHNwYW4gY2xhc3M9XCJpdGVtLW1ldGFcIj4ke29wdGlvbnMubGFuZ3VhZ2Uub25lX2xvc3N9PC9zcGFuPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYCAgICA8c3BhbiBjbGFzcz1cIml0ZW0tbWV0YVwiPiR7b3B0aW9ucy5sYW5ndWFnZS5kb3VibGVfZWxpbWluYXRpb259PC9zcGFuPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGAgICAgPC9zcGFuPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGAgICAgPHNwYW4gY2xhc3M9XCJpdGVtLW1ldGFcIj5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKCAnMCcgPT09IHRvdXJuYW1lbnQuZnJvbV9sYWRkZXIgKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPGEgaHJlZj1cIiR7dG91cm5hbWVudC5yZWdpc3RlcmVkX2xpbmt9XCI+JHt0b3VybmFtZW50LmNvbXBldGl0b3JzfTwvYT4vYDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoIDAgPCB0b3VybmFtZW50LmJyYWNrZXRfc2l6ZSApIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgJHt0b3VybmFtZW50LmJyYWNrZXRfc2l6ZX1gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYCZpbmZpbjtgO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPGEgaHJlZj1cIiR7dG91cm5hbWVudC5jdXJyZW50X3NlZWRpbmdfbGlua31cIj4ke29wdGlvbnMubGFuZ3VhZ2UuY3VycmVudF9zZWVkaW5nfTwvYT5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgICAgIDwvc3Bhbj5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgICAgIDx1bCBjbGFzcz1cImxpc3QtaW5saW5lXCI+YDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYCAgICAgIDxsaSBjbGFzcz1cImxpc3QtaW5saW5lLWl0ZW1cIj48YSBocmVmPVwiJHt0b3VybmFtZW50Lmxpbmt9XCIgY2xhc3M9XCJ0cm4tYnV0dG9uIHRybi1idXR0b24tc21cIj4ke29wdGlvbnMubGFuZ3VhZ2UubW9yZV9pbmZvfTwvYT48L2xpPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGAgICAgICA8bGkgY2xhc3M9XCJsaXN0LWlubGluZS1pdGVtXCI+PGEgaHJlZj1cIiR7dG91cm5hbWVudC5yZWdpc3Rlcl9saW5rfVwiIGNsYXNzPVwidHJuLWJ1dHRvbiB0cm4tYnV0dG9uLXNtXCIgPiR7b3B0aW9ucy5sYW5ndWFnZS5yZWdpc3Rlcn08L2E+PC9saT5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgICAgIDwvdWw+YDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYCAgPC9kaXY+YDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYCAgPGRpdiBjbGFzcz1cInRybi1jbGVhcmZpeFwiPjwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8L2Rpdj5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxkaXYgaWQ9XCJ0cm4tdXBjb21pbmctdG91cm5hbWVudHMtYnV0dG9uc1wiPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxidXR0b24gaWQ9XCJ0cm4tdXBjb21pbmctdG91cm5hbWVudHMtcHJldmlvdXMtYnV0dG9uXCI+JiM2MDs8L2J1dHRvbj5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8YnV0dG9uIGlkPVwidHJuLXVwY29taW5nLXRvdXJuYW1lbnRzLW5leHQtYnV0dG9uXCI+JiM2Mjs8L2J1dHRvbj5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8L2Rpdj5gO1xyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxwIGNsYXNzPVwidHJuLXRleHQtY2VudGVyXCI+JHtvcHRpb25zLmxhbmd1YWdlLnplcm9fdG91cm5hbWVudHN9PC9wPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8cCBjbGFzcz1cInRybi10ZXh0LWNlbnRlclwiPiR7b3B0aW9ucy5sYW5ndWFnZS5lcnJvcn08L3A+YDtcclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICByZW1vdmVMaXN0ZW5lcnMoKTtcclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tdG91cm5hbWVudC1saXN0LXNob3J0Y29kZScpLmlubmVySFRNTCA9IGNvbnRlbnQ7XHJcbiAgICAgICAgICAgICAgICBhZGRMaXN0ZW5lcnMoKTtcclxuICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgIHhoci5zZW5kKCk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBnZXRVcGNvbWluZ1RvdXJuYW1lbnRzKCk7XHJcbiAgICB9LCBmYWxzZSk7XHJcbn0pKHRybik7Il0sInNvdXJjZVJvb3QiOiIifQ==