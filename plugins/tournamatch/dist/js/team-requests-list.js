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
/******/ 	return __webpack_require__(__webpack_require__.s = 36);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/team-requests-list.js":
/*!**************************************!*\
  !*** ./src/js/team-requests-list.js ***!
  \**************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./tournamatch.js */ "./src/js/tournamatch.js");
/**
 * Handles events for the list that displays requests to join a team.
 *
 * @link       https://www.tournamatch.com
 * @since      3.8.0
 *
 * @package    Tournamatch
 *
 */


(function ($) {
  'use strict';

  window.addEventListener('load', function () {
    var options = trn_team_requests_list_options;

    function acceptTeamRequest(request_id) {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', options.api_url + 'team-requests/' + request_id + '/accept');
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        var response = JSON.parse(xhr.response);

        if (xhr.status === 200) {
          getTeamRequests();
          $.event('team-members').dispatchEvent(new Event('changed'));
        } else {
          console.log(xhr.response);
          document.getElementById('trn-team-requests-response').innerHTML = "<div class=\"trn-alert trn-alert-danger\"><strong>".concat(options.language.failure, ":</strong> ").concat(response.message, "</div>");
        }
      };

      xhr.send($.param({
        request_id: request_id
      }));
    }

    function declineTeamRequest(request_id) {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', options.api_url + 'team-requests/' + request_id + '/decline');
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        if (xhr.status === 200) {
          getTeamRequests();
        } else {
          console.log(xhr.response); // display error somewhere
        }
      };

      xhr.send($.param({
        request_id: request_id
      }));
    }

    function handleAcceptClick(event) {
      acceptTeamRequest(this.dataset.requestId);
    }

    function handleDeclineClick(event) {
      declineTeamRequest(this.dataset.requestId);
    } // Accept join team request links.


    function addListeners() {
      var acceptLinks = document.getElementsByClassName('trn-accept-team-request-link');
      Array.prototype.forEach.call(acceptLinks, function (acceptLink) {
        acceptLink.addEventListener('click', handleAcceptClick);
      });
      var declineLinks = document.getElementsByClassName('trn-decline-team-request-link');
      Array.prototype.forEach.call(declineLinks, function (declineLink) {
        declineLink.addEventListener('click', handleDeclineClick);
      });
    }

    function removeListeners() {
      var acceptLinks = document.getElementsByClassName('trn-accept-team-request-link');
      Array.prototype.forEach.call(acceptLinks, function (acceptLink) {
        acceptLink.removeEventListener('click', handleAcceptClick);
      });
      var declineLinks = document.getElementsByClassName('trn-decline-team-request-link');
      Array.prototype.forEach.call(declineLinks, function (declineLink) {
        declineLink.removeEventListener('click', handleDeclineClick);
      });
    }

    function getTeamRequests() {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', options.api_url + 'team-requests/?_embed&' + $.param({
        team_id: options.team_id
      }));
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        // console.log(xhr);
        var content = '';

        if (xhr.status === 200) {
          var requests = JSON.parse(xhr.response);

          if (requests !== null && requests.length > 0) {
            content += "<ul class=\"trn-list-unstyled\" id=\"trn-team-requests-list\">";
            Array.prototype.forEach.call(requests, function (request) {
              content += "<li class=\"trn-text-center\" id=\"trn-join-team-request-".concat(request.team_member_request_id, "\">");
              content += "<a href=\"".concat(request._embedded.player[0].link, "\">").concat(request._embedded.player[0].name, "</a> ");
              content += "<a class=\"trn-accept-team-request-link\" data-request-id=\"".concat(request.team_member_request_id, "\"><i class=\"fa fa-check trn-text-success\"></i></a> ");
              content += "<a class=\"trn-decline-team-request-link\" data-request-id=\"".concat(request.team_member_request_id, "\"><i class=\"fa fa-times trn-text-danger\"></i></a>");
              content += "</li>";
            });
            content += "</ul>";
          } else {
            content += "<p class=\"trn-text-center\">".concat(options.language.zero_requests, "</p>");
          }
        } else {
          content += "<p class=\"trn-text-center\">".concat(options.language.error, "</p>");
        }

        removeListeners();
        document.getElementById('trn-team-requests-section-header').nextSibling.remove();
        document.getElementById('trn-team-requests-section').innerHTML += content;
        addListeners();
      };

      xhr.send();
    }

    getTeamRequests();
  }, false);
})(_tournamatch_js__WEBPACK_IMPORTED_MODULE_0__["trn"]);

/***/ }),

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

/***/ 36:
/*!********************************************!*\
  !*** multi ./src/js/team-requests-list.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\team-requests-list.js */"./src/js/team-requests-list.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3RlYW0tcmVxdWVzdHMtbGlzdC5qcyIsIndlYnBhY2s6Ly8vLi9zcmMvanMvdG91cm5hbWF0Y2guanMiXSwibmFtZXMiOlsiJCIsIndpbmRvdyIsImFkZEV2ZW50TGlzdGVuZXIiLCJvcHRpb25zIiwidHJuX3RlYW1fcmVxdWVzdHNfbGlzdF9vcHRpb25zIiwiYWNjZXB0VGVhbVJlcXVlc3QiLCJyZXF1ZXN0X2lkIiwieGhyIiwiWE1MSHR0cFJlcXVlc3QiLCJvcGVuIiwiYXBpX3VybCIsInNldFJlcXVlc3RIZWFkZXIiLCJyZXN0X25vbmNlIiwib25sb2FkIiwicmVzcG9uc2UiLCJKU09OIiwicGFyc2UiLCJzdGF0dXMiLCJnZXRUZWFtUmVxdWVzdHMiLCJldmVudCIsImRpc3BhdGNoRXZlbnQiLCJFdmVudCIsImNvbnNvbGUiLCJsb2ciLCJkb2N1bWVudCIsImdldEVsZW1lbnRCeUlkIiwiaW5uZXJIVE1MIiwibGFuZ3VhZ2UiLCJmYWlsdXJlIiwibWVzc2FnZSIsInNlbmQiLCJwYXJhbSIsImRlY2xpbmVUZWFtUmVxdWVzdCIsImhhbmRsZUFjY2VwdENsaWNrIiwiZGF0YXNldCIsInJlcXVlc3RJZCIsImhhbmRsZURlY2xpbmVDbGljayIsImFkZExpc3RlbmVycyIsImFjY2VwdExpbmtzIiwiZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSIsIkFycmF5IiwicHJvdG90eXBlIiwiZm9yRWFjaCIsImNhbGwiLCJhY2NlcHRMaW5rIiwiZGVjbGluZUxpbmtzIiwiZGVjbGluZUxpbmsiLCJyZW1vdmVMaXN0ZW5lcnMiLCJyZW1vdmVFdmVudExpc3RlbmVyIiwidGVhbV9pZCIsImNvbnRlbnQiLCJyZXF1ZXN0cyIsImxlbmd0aCIsInJlcXVlc3QiLCJ0ZWFtX21lbWJlcl9yZXF1ZXN0X2lkIiwiX2VtYmVkZGVkIiwicGxheWVyIiwibGluayIsIm5hbWUiLCJ6ZXJvX3JlcXVlc3RzIiwiZXJyb3IiLCJuZXh0U2libGluZyIsInJlbW92ZSIsInRybiIsIlRvdXJuYW1hdGNoIiwiZXZlbnRzIiwib2JqZWN0IiwicHJlZml4Iiwic3RyIiwicHJvcCIsImhhc093blByb3BlcnR5IiwiayIsInYiLCJwdXNoIiwiZW5jb2RlVVJJQ29tcG9uZW50Iiwiam9pbiIsImV2ZW50TmFtZSIsIkV2ZW50VGFyZ2V0IiwiaW5wdXQiLCJkYXRhQ2FsbGJhY2siLCJUb3VybmFtYXRjaF9BdXRvY29tcGxldGUiLCJzIiwiY2hhckF0IiwidG9VcHBlckNhc2UiLCJzbGljZSIsIm51bWJlciIsInJlbWFpbmRlciIsImVsZW1lbnQiLCJ0YWJzIiwicGFuZXMiLCJjbGVhckFjdGl2ZSIsInRhYiIsImNsYXNzTGlzdCIsImFyaWFTZWxlY3RlZCIsInBhbmUiLCJzZXRBY3RpdmUiLCJ0YXJnZXRJZCIsInRhcmdldFRhYiIsInF1ZXJ5U2VsZWN0b3IiLCJ0YXJnZXRQYW5lSWQiLCJ0YXJnZXQiLCJhZGQiLCJ0YWJDbGljayIsImN1cnJlbnRUYXJnZXQiLCJwcmV2ZW50RGVmYXVsdCIsImxvY2F0aW9uIiwiaGFzaCIsInN1YnN0ciIsInRybl9vYmpfaW5zdGFuY2UiLCJ0YWJWaWV3cyIsImZyb20iLCJkcm9wZG93bnMiLCJoYW5kbGVEcm9wZG93bkNsb3NlIiwiZHJvcGRvd24iLCJuZXh0RWxlbWVudFNpYmxpbmciLCJlIiwic3RvcFByb3BhZ2F0aW9uIiwibmFtZUlucHV0IiwiYSIsImIiLCJpIiwidmFsIiwidmFsdWUiLCJwYXJlbnQiLCJwYXJlbnROb2RlIiwidGhlbiIsImRhdGEiLCJjbG9zZUFsbExpc3RzIiwiY3VycmVudEZvY3VzIiwiY3JlYXRlRWxlbWVudCIsInNldEF0dHJpYnV0ZSIsImlkIiwiYXBwZW5kQ2hpbGQiLCJ0ZXh0Iiwic2VsZWN0ZWRJZCIsIngiLCJnZXRFbGVtZW50c0J5VGFnTmFtZSIsImtleUNvZGUiLCJhZGRBY3RpdmUiLCJjbGljayIsInJlbW92ZUFjdGl2ZSIsInJlbW92ZUNo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Qk4sQ0FBQyxDQUFDZixDQUFELENBQTdCO0FBQ0g7QUFDSjtBQUNKOzs7O0tBR0w7OztBQUNBLElBQUksQ0FBQ3NCLE1BQU0sQ0FBQ3JHLFNBQVAsQ0FBaUJzRyxNQUF0QixFQUE4QjtBQUMxQkQsUUFBTSxDQUFDckcsU0FBUCxDQUFpQnNHLE1BQWpCLEdBQTBCLFlBQVc7QUFDakMsUUFBTUMsSUFBSSxHQUFHQyxTQUFiO0FBQ0EsV0FBTyxLQUFLQyxPQUFMLENBQWEsVUFBYixFQUF5QixVQUFTQyxLQUFULEVBQWdCOUQsTUFBaEIsRUFBd0I7QUFDcEQsYUFBTyxPQUFPMkQsSUFBSSxDQUFDM0QsTUFBRCxDQUFYLEtBQXdCLFdBQXhCLEdBQ0QyRCxJQUFJLENBQUMzRCxNQUFELENBREgsR0FFRDhELEtBRk47QUFJSCxLQUxNLENBQVA7QUFNSCxHQVJEO0FBU0gsQyIsImZpbGUiOiJ0ZWFtLXJlcXVlc3RzLWxpc3QuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMzYpO1xuIiwiLyoqXHJcbiAqIEhhbmRsZXMgZXZlbnRzIGZvciB0aGUgbGlzdCB0aGF0IGRpc3BsYXlzIHJlcXVlc3RzIHRvIGpvaW4gYSB0ZWFtLlxyXG4gKlxyXG4gKiBAbGluayAgICAgICBodHRwczovL3d3dy50b3VybmFtYXRjaC5jb21cclxuICogQHNpbmNlICAgICAgMy44LjBcclxuICpcclxuICogQHBhY2thZ2UgICAgVG91cm5hbWF0Y2hcclxuICpcclxuICovXHJcbmltcG9ydCB7IHRybiB9IGZyb20gJy4vdG91cm5hbWF0Y2guanMnO1xyXG5cclxuKGZ1bmN0aW9uICgkKSB7XHJcbiAgICAndXNlIHN0cmljdCc7XHJcblxyXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgY29uc3Qgb3B0aW9ucyA9IHRybl90ZWFtX3JlcXVlc3RzX2xpc3Rfb3B0aW9ucztcclxuXHJcbiAgICAgICAgZnVuY3Rpb24gYWNjZXB0VGVhbVJlcXVlc3QocmVxdWVzdF9pZCkge1xyXG4gICAgICAgICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgIHhoci5vcGVuKCdQT1NUJywgb3B0aW9ucy5hcGlfdXJsICsgJ3RlYW0tcmVxdWVzdHMvJyArIHJlcXVlc3RfaWQgKyAnL2FjY2VwdCcpO1xyXG4gICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xyXG4gICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignWC1XUC1Ob25jZScsIG9wdGlvbnMucmVzdF9ub25jZSk7XHJcbiAgICAgICAgICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBsZXQgcmVzcG9uc2UgPSBKU09OLnBhcnNlKHhoci5yZXNwb25zZSk7XHJcbiAgICAgICAgICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgZ2V0VGVhbVJlcXVlc3RzKCk7XHJcbiAgICAgICAgICAgICAgICAgICAgJC5ldmVudCgndGVhbS1tZW1iZXJzJykuZGlzcGF0Y2hFdmVudChuZXcgRXZlbnQoJ2NoYW5nZWQnKSk7XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKHhoci5yZXNwb25zZSk7XHJcbiAgICAgICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Rybi10ZWFtLXJlcXVlc3RzLXJlc3BvbnNlJykuaW5uZXJIVE1MID0gYDxkaXYgY2xhc3M9XCJ0cm4tYWxlcnQgdHJuLWFsZXJ0LWRhbmdlclwiPjxzdHJvbmc+JHtvcHRpb25zLmxhbmd1YWdlLmZhaWx1cmV9Ojwvc3Ryb25nPiAke3Jlc3BvbnNlLm1lc3NhZ2V9PC9kaXY+YDtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgIHhoci5zZW5kKCQucGFyYW0oe1xyXG4gICAgICAgICAgICAgICAgcmVxdWVzdF9pZDogcmVxdWVzdF9pZFxyXG4gICAgICAgICAgICB9KSk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBmdW5jdGlvbiBkZWNsaW5lVGVhbVJlcXVlc3QocmVxdWVzdF9pZCkge1xyXG4gICAgICAgICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgIHhoci5vcGVuKCdQT1NUJywgb3B0aW9ucy5hcGlfdXJsICsgJ3RlYW0tcmVxdWVzdHMvJyArIHJlcXVlc3RfaWQgKyAnL2RlY2xpbmUnKTtcclxuICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ1gtV1AtTm9uY2UnLCBvcHRpb25zLnJlc3Rfbm9uY2UpO1xyXG4gICAgICAgICAgICB4aHIub25sb2FkID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMCkge1xyXG4gICAgICAgICAgICAgICAgICAgIGdldFRlYW1SZXF1ZXN0cygpO1xyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyh4aHIucmVzcG9uc2UpO1xyXG4gICAgICAgICAgICAgICAgICAgIC8vIGRpc3BsYXkgZXJyb3Igc29tZXdoZXJlXHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgICAgICB4aHIuc2VuZCgkLnBhcmFtKHtcclxuICAgICAgICAgICAgICAgIHJlcXVlc3RfaWQ6IHJlcXVlc3RfaWRcclxuICAgICAgICAgICAgfSkpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZnVuY3Rpb24gaGFuZGxlQWNjZXB0Q2xpY2soZXZlbnQpIHtcclxuICAgICAgICAgICAgYWNjZXB0VGVhbVJlcXVlc3QodGhpcy5kYXRhc2V0LnJlcXVlc3RJZClcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIGhhbmRsZURlY2xpbmVDbGljayhldmVudCkge1xyXG4gICAgICAgICAgICBkZWNsaW5lVGVhbVJlcXVlc3QodGhpcy5kYXRhc2V0LnJlcXVlc3RJZClcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIC8vIEFjY2VwdCBqb2luIHRlYW0gcmVxdWVzdCBsaW5rcy5cclxuICAgICAgICBmdW5jdGlvbiBhZGRMaXN0ZW5lcnMoKSB7XHJcbiAgICAgICAgICAgIGxldCBhY2NlcHRMaW5rcyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1hY2NlcHQtdGVhbS1yZXF1ZXN0LWxpbmsnKTtcclxuICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbChhY2NlcHRMaW5rcywgIGZ1bmN0aW9uKGFjY2VwdExpbmspIHtcclxuICAgICAgICAgICAgICAgIGFjY2VwdExpbmsuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBoYW5kbGVBY2NlcHRDbGljayk7XHJcbiAgICAgICAgICAgIH0pO1xyXG5cclxuICAgICAgICAgICAgbGV0IGRlY2xpbmVMaW5rcyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1kZWNsaW5lLXRlYW0tcmVxdWVzdC1saW5rJyk7XHJcbiAgICAgICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwoZGVjbGluZUxpbmtzLCBmdW5jdGlvbihkZWNsaW5lTGluaykge1xyXG4gICAgICAgICAgICAgICAgZGVjbGluZUxpbmsuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBoYW5kbGVEZWNsaW5lQ2xpY2spO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIHJlbW92ZUxpc3RlbmVycygpIHtcclxuICAgICAgICAgICAgbGV0IGFjY2VwdExpbmtzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLWFjY2VwdC10ZWFtLXJlcXVlc3QtbGluaycpO1xyXG4gICAgICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKGFjY2VwdExpbmtzLCAgZnVuY3Rpb24oYWNjZXB0TGluaykge1xyXG4gICAgICAgICAgICAgICAgYWNjZXB0TGluay5yZW1vdmVFdmVudExpc3RlbmVyKCdjbGljaycsIGhhbmRsZUFjY2VwdENsaWNrKTtcclxuICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICBsZXQgZGVjbGluZUxpbmtzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLWRlY2xpbmUtdGVhbS1yZXF1ZXN0LWxpbmsnKTtcclxuICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbChkZWNsaW5lTGlua3MsIGZ1bmN0aW9uKGRlY2xpbmVMaW5rKSB7XHJcbiAgICAgICAgICAgICAgICBkZWNsaW5lTGluay5yZW1vdmVFdmVudExpc3RlbmVyKCdjbGljaycsIGhhbmRsZURlY2xpbmVDbGljayk7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZnVuY3Rpb24gZ2V0VGVhbVJlcXVlc3RzKCkge1xyXG4gICAgICAgICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgIHhoci5vcGVuKCdHRVQnLCBvcHRpb25zLmFwaV91cmwgKyAndGVhbS1yZXF1ZXN0cy8/X2VtYmVkJicgKyAkLnBhcmFtKHt0ZWFtX2lkOiBvcHRpb25zLnRlYW1faWR9KSk7XHJcbiAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdDb250ZW50LVR5cGUnLCAnYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkJyk7XHJcbiAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgIC8vIGNvbnNvbGUubG9nKHhocik7XHJcblxyXG4gICAgICAgICAgICAgICAgbGV0IGNvbnRlbnQgPSAnJztcclxuICAgICAgICAgICAgICAgIGlmICh4aHIuc3RhdHVzID09PSAyMDApIHtcclxuICAgICAgICAgICAgICAgICAgICBsZXQgcmVxdWVzdHMgPSBKU09OLnBhcnNlKHhoci5yZXNwb25zZSk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIGlmICggcmVxdWVzdHMgIT09IG51bGwgJiYgcmVxdWVzdHMubGVuZ3RoID4gMCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8dWwgY2xhc3M9XCJ0cm4tbGlzdC11bnN0eWxlZFwiIGlkPVwidHJuLXRlYW0tcmVxdWVzdHMtbGlzdFwiPmA7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHJlcXVlc3RzLCBmdW5jdGlvbihyZXF1ZXN0KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8bGkgY2xhc3M9XCJ0cm4tdGV4dC1jZW50ZXJcIiBpZD1cInRybi1qb2luLXRlYW0tcmVxdWVzdC0ke3JlcXVlc3QudGVhbV9tZW1iZXJfcmVxdWVzdF9pZH1cIj5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPGEgaHJlZj1cIiR7cmVxdWVzdC5fZW1iZWRkZWQucGxheWVyWzBdLmxpbmt9XCI+JHtyZXF1ZXN0Ll9lbWJlZGRlZC5wbGF5ZXJbMF0ubmFtZX08L2E+IGA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8YSBjbGFzcz1cInRybi1hY2NlcHQtdGVhbS1yZXF1ZXN0LWxpbmtcIiBkYXRhLXJlcXVlc3QtaWQ9XCIke3JlcXVlc3QudGVhbV9tZW1iZXJfcmVxdWVzdF9pZH1cIj48aSBjbGFzcz1cImZhIGZhLWNoZWNrIHRybi10ZXh0LXN1Y2Nlc3NcIj48L2k+PC9hPiBgO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPGEgY2xhc3M9XCJ0cm4tZGVjbGluZS10ZWFtLXJlcXVlc3QtbGlua1wiIGRhdGEtcmVxdWVzdC1pZD1cIiR7cmVxdWVzdC50ZWFtX21lbWJlcl9yZXF1ZXN0X2lkfVwiPjxpIGNsYXNzPVwiZmEgZmEtdGltZXMgdHJuLXRleHQtZGFuZ2VyXCI+PC9pPjwvYT5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPC9saT5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDwvdWw+YDtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8cCBjbGFzcz1cInRybi10ZXh0LWNlbnRlclwiPiR7b3B0aW9ucy5sYW5ndWFnZS56ZXJvX3JlcXVlc3RzfTwvcD5gO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPHAgY2xhc3M9XCJ0cm4tdGV4dC1jZW50ZXJcIj4ke29wdGlvbnMubGFuZ3VhZ2UuZXJyb3J9PC9wPmA7XHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgcmVtb3ZlTGlzdGVuZXJzKCk7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLXRlYW0tcmVxdWVzdHMtc2VjdGlvbi1oZWFkZXInKS5uZXh0U2libGluZy5yZW1vdmUoKTtcclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tdGVhbS1yZXF1ZXN0cy1zZWN0aW9uJykuaW5uZXJIVE1MICs9IGNvbnRlbnQ7XHJcbiAgICAgICAgICAgICAgICBhZGRMaXN0ZW5lcnMoKTtcclxuICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgIHhoci5zZW5kKCk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIGdldFRlYW1SZXF1ZXN0cygpO1xyXG4gICAgfSwgZmFsc2UpO1xyXG59KSh0cm4pOyIsIid1c2Ugc3RyaWN0JztcclxuY2xhc3MgVG91cm5hbWF0Y2gge1xyXG5cclxuICAgIGNvbnN0cnVjdG9yKCkge1xyXG4gICAgICAgIHRoaXMuZXZlbnRzID0ge307XHJcbiAgICB9XHJcblxyXG4gICAgcGFyYW0ob2JqZWN0LCBwcmVmaXgpIHtcclxuICAgICAgICBsZXQgc3RyID0gW107XHJcbiAgICAgICAgZm9yIChsZXQgcHJvcCBpbiBvYmplY3QpIHtcclxuICAgICAgICAgICAgaWYgKG9iamVjdC5oYXNPd25Qcm9wZXJ0eShwcm9wKSkge1xyXG4gICAgICAgICAgICAgICAgbGV0IGsgPSBwcmVmaXggPyBwcmVmaXggKyBcIltcIiArIHByb3AgKyBcIl1cIiA6IHByb3A7XHJcbiAgICAgICAgICAgICAgICBsZXQgdiA9IG9iamVjdFtwcm9wXTtcclxuICAgICAgICAgICAgICAgIHN0ci5wdXNoKCh2ICE9PSBudWxsICYmIHR5cGVvZiB2ID09PSBcIm9iamVjdFwiKSA/IHRoaXMucGFyYW0odiwgaykgOiBlbmNvZGVVUklDb21wb25lbnQoaykgKyBcIj1cIiArIGVuY29kZVVSSUNvbXBvbmVudCh2KSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuIHN0ci5qb2luKFwiJlwiKTtcclxuICAgIH1cclxuXHJcbiAgICBldmVudChldmVudE5hbWUpIHtcclxuICAgICAgICBpZiAoIShldmVudE5hbWUgaW4gdGhpcy5ldmVudHMpKSB7XHJcbiAgICAgICAgICAgIHRoaXMuZXZlbnRzW2V2ZW50TmFtZV0gPSBuZXcgRXZlbnRUYXJnZXQoZXZlbnROYW1lKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuIHRoaXMuZXZlbnRzW2V2ZW50TmFtZV07XHJcbiAgICB9XHJcblxyXG4gICAgYXV0b2NvbXBsZXRlKGlucHV0LCBkYXRhQ2FsbGJhY2spIHtcclxuICAgICAgICBuZXcgVG91cm5hbWF0Y2hfQXV0b2NvbXBsZXRlKGlucHV0LCBkYXRhQ2FsbGJhY2spO1xyXG4gICAgfVxyXG5cclxuICAgIHVjZmlyc3Qocykge1xyXG4gICAgICAgIGlmICh0eXBlb2YgcyAhPT0gJ3N0cmluZycpIHJldHVybiAnJztcclxuICAgICAgICByZXR1cm4gcy5jaGFyQXQoMCkudG9VcHBlckNhc2UoKSArIHMuc2xpY2UoMSk7XHJcbiAgICB9XHJcblxyXG4gICAgb3JkaW5hbF9zdWZmaXgobnVtYmVyKSB7XHJcbiAgICAgICAgY29uc3QgcmVtYWluZGVyID0gbnVtYmVyICUgMTAwO1xyXG5cclxuICAgICAgICBpZiAoKHJlbWFpbmRlciA8IDExKSB8fCAocmVtYWluZGVyID4gMTMpKSB7XHJcbiAgICAgICAgICAgIHN3aXRjaCAocmVtYWluZGVyICUgMTApIHtcclxuICAgICAgICAgICAgICAgIGNhc2UgMTogcmV0dXJuICdzdCc7XHJcbiAgICAgICAgICAgICAgICBjYXNlIDI6IHJldHVybiAnbmQnO1xyXG4gICAgICAgICAgICAgICAgY2FzZSAzOiByZXR1cm4gJ3JkJztcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gJ3RoJztcclxuICAgIH1cclxuXHJcbiAgICB0YWJzKGVsZW1lbnQpIHtcclxuICAgICAgICBjb25zdCB0YWJzID0gZWxlbWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tbmF2LWxpbmsnKTtcclxuICAgICAgICBjb25zdCBwYW5lcyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi10YWItcGFuZScpO1xyXG4gICAgICAgIGNvbnN0IGNsZWFyQWN0aXZlID0gKCkgPT4ge1xyXG4gICAgICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHRhYnMsICh0YWIpID0+IHtcclxuICAgICAgICAgICAgICAgIHRhYi5jbGFzc0xpc3QucmVtb3ZlKCd0cm4tbmF2LWFjdGl2ZScpO1xyXG4gICAgICAgICAgICAgICAgdGFiLmFyaWFTZWxlY3RlZCA9IGZhbHNlO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbChwYW5lcywgcGFuZSA9PiBwYW5lLmNsYXNzTGlzdC5yZW1vdmUoJ3Rybi10YWItYWN0aXZlJykpO1xyXG4gICAgICAgIH07XHJcbiAgICAgICAgY29uc3Qgc2V0QWN0aXZlID0gKHRhcmdldElkKSA9PiB7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFRhYiA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ2FbaHJlZj1cIiMnICsgdGFyZ2V0SWQgKyAnXCJdLnRybi1uYXYtbGluaycpO1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRQYW5lSWQgPSB0YXJnZXRUYWIgJiYgdGFyZ2V0VGFiLmRhdGFzZXQgJiYgdGFyZ2V0VGFiLmRhdGFzZXQudGFyZ2V0IHx8IGZhbHNlO1xyXG5cclxuICAgICAgICAgICAgaWYgKHRhcmdldFBhbmVJZCkge1xyXG4gICAgICAgICAgICAgICAgY2xlYXJBY3RpdmUoKTtcclxuICAgICAgICAgICAgICAgIHRhcmdldFRhYi5jbGFzc0xpc3QuYWRkKCd0cm4tbmF2LWFjdGl2ZScpO1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0VGFiLmFyaWFTZWxlY3RlZCA9IHRydWU7XHJcblxyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGFyZ2V0UGFuZUlkKS5jbGFzc0xpc3QuYWRkKCd0cm4tdGFiLWFjdGl2ZScpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfTtcclxuICAgICAgICBjb25zdCB0YWJDbGljayA9IChldmVudCkgPT4ge1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRUYWIgPSBldmVudC5jdXJyZW50VGFyZ2V0O1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRQYW5lSWQgPSB0YXJnZXRUYWIgJiYgdGFyZ2V0VGFiLmRhdGFzZXQgJiYgdGFyZ2V0VGFiLmRhdGFzZXQudGFyZ2V0IHx8IGZhbHNlO1xyXG5cclxuICAgICAgICAgICAgaWYgKHRhcmdldFBhbmVJZCkge1xyXG4gICAgICAgICAgICAgICAgc2V0QWN0aXZlKHRhcmdldFBhbmVJZCk7XHJcbiAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfTtcclxuXHJcbiAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbCh0YWJzLCAodGFiKSA9PiB7XHJcbiAgICAgICAgICAgIHRhYi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRhYkNsaWNrKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgaWYgKGxvY2F0aW9uLmhhc2gpIHtcclxuICAgICAgICAgICAgc2V0QWN0aXZlKGxvY2F0aW9uLmhhc2guc3Vic3RyKDEpKTtcclxuICAgICAgICB9IGVsc2UgaWYgKHRhYnMubGVuZ3RoID4gMCkge1xyXG4gICAgICAgICAgICBzZXRBY3RpdmUodGFic1swXS5kYXRhc2V0LnRhcmdldCk7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxufVxyXG5cclxuLy90cm4uaW5pdGlhbGl6ZSgpO1xyXG5pZiAoIXdpbmRvdy50cm5fb2JqX2luc3RhbmNlKSB7XHJcbiAgICB3aW5kb3cudHJuX29ial9pbnN0YW5jZSA9IG5ldyBUb3VybmFtYXRjaCgpO1xyXG5cclxuICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgZnVuY3Rpb24gKCkge1xyXG5cclxuICAgICAgICBjb25zdCB0YWJWaWV3cyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1uYXYnKTtcclxuXHJcbiAgICAgICAgQXJyYXkuZnJvbSh0YWJWaWV3cykuZm9yRWFjaCgodGFiKSA9PiB7XHJcbiAgICAgICAgICAgIHRybi50YWJzKHRhYik7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIGNvbnN0IGRyb3Bkb3ducyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1kcm9wZG93bi10b2dnbGUnKTtcclxuICAgICAgICBjb25zdCBoYW5kbGVEcm9wZG93bkNsb3NlID0gKCkgPT4ge1xyXG4gICAgICAgICAgICBBcnJheS5mcm9tKGRyb3Bkb3ducykuZm9yRWFjaCgoZHJvcGRvd24pID0+IHtcclxuICAgICAgICAgICAgICAgIGRyb3Bkb3duLm5leHRFbGVtZW50U2libGluZy5jbGFzc0xpc3QucmVtb3ZlKCd0cm4tc2hvdycpO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgZG9jdW1lbnQucmVtb3ZlRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGhhbmRsZURyb3Bkb3duQ2xvc2UsIGZhbHNlKTtcclxuICAgICAgICB9O1xyXG5cclxuICAgICAgICBBcnJheS5mcm9tKGRyb3Bkb3ducykuZm9yRWFjaCgoZHJvcGRvd24pID0+IHtcclxuICAgICAgICAgICAgZHJvcGRvd24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbihlKSB7XHJcbiAgICAgICAgICAgICAgICBlLnN0b3BQcm9wYWdhdGlvbigpO1xyXG4gICAgICAgICAgICAgICAgdGhpcy5uZXh0RWxlbWVudFNpYmxpbmcuY2xhc3NMaXN0LmFkZCgndHJuLXNob3cnKTtcclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBoYW5kbGVEcm9wZG93bkNsb3NlLCBmYWxzZSk7XHJcbiAgICAgICAgICAgIH0sIGZhbHNlKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICB9LCBmYWxzZSk7XHJcbn1cclxuZXhwb3J0IGxldCB0cm4gPSB3aW5kb3cudHJuX29ial9pbnN0YW5jZTtcclxuXHJcbmNsYXNzIFRvdXJuYW1hdGNoX0F1dG9jb21wbGV0ZSB7XHJcblxyXG4gICAgLy8gY3VycmVudEZvY3VzO1xyXG4gICAgLy9cclxuICAgIC8vIG5hbWVJbnB1dDtcclxuICAgIC8vXHJcbiAgICAvLyBzZWxmO1xyXG5cclxuICAgIGNvbnN0cnVjdG9yKGlucHV0LCBkYXRhQ2FsbGJhY2spIHtcclxuICAgICAgICAvLyB0aGlzLnNlbGYgPSB0aGlzO1xyXG4gICAgICAgIHRoaXMubmFtZUlucHV0ID0gaW5wdXQ7XHJcblxyXG4gICAgICAgIHRoaXMubmFtZUlucHV0LmFkZEV2ZW50TGlzdGVuZXIoXCJpbnB1dFwiLCAoKSA9PiB7XHJcbiAgICAgICAgICAgIGxldCBhLCBiLCBpLCB2YWwgPSB0aGlzLm5hbWVJbnB1dC52YWx1ZTsvL3RoaXMudmFsdWU7XHJcbiAgICAgICAgICAgIGxldCBwYXJlbnQgPSB0aGlzLm5hbWVJbnB1dC5wYXJlbnROb2RlOy8vdGhpcy5wYXJlbnROb2RlO1xyXG5cclxuICAgICAgICAgICAgLy8gbGV0IHAgPSBuZXcgUHJvbWlzZSgocmVzb2x2ZSwgcmVqZWN0KSA9PiB7XHJcbiAgICAgICAgICAgIC8vICAgICAvKiBuZWVkIHRvIHF1ZXJ5IHNlcnZlciBmb3IgbmFtZXMgaGVyZS4gKi9cclxuICAgICAgICAgICAgLy8gICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5vcGVuKCdHRVQnLCBvcHRpb25zLmFwaV91cmwgKyAncGxheWVycy8/c2VhcmNoPScgKyB2YWwgKyAnJnBlcl9wYWdlPTUnKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdDb250ZW50LVR5cGUnLCAnYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkJyk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignWC1XUC1Ob25jZScsIG9wdGlvbnMucmVzdF9ub25jZSk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIub25sb2FkID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAvLyAgICAgICAgIGlmICh4aHIuc3RhdHVzID09PSAyMDApIHtcclxuICAgICAgICAgICAgLy8gICAgICAgICAgICAgLy8gcmVzb2x2ZShKU09OLnBhcnNlKHhoci5yZXNwb25zZSkubWFwKChwbGF5ZXIpID0+IHtyZXR1cm4geyAndmFsdWUnOiBwbGF5ZXIuaWQsICd0ZXh0JzogcGxheWVyLm5hbWUgfTt9KSk7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgICAgIHJlc29sdmUoSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpLm1hcCgocGxheWVyKSA9PiB7cmV0dXJuIHBsYXllci5uYW1lO30pKTtcclxuICAgICAgICAgICAgLy8gICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAvLyAgICAgICAgICAgICByZWplY3QoKTtcclxuICAgICAgICAgICAgLy8gICAgICAgICB9XHJcbiAgICAgICAgICAgIC8vICAgICB9O1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLnNlbmQoKTtcclxuICAgICAgICAgICAgLy8gfSk7XHJcbiAgICAgICAgICAgIGRhdGFDYWxsYmFjayh2YWwpLnRoZW4oKGRhdGEpID0+IHsvL3AudGhlbigoZGF0YSkgPT4ge1xyXG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coZGF0YSk7XHJcblxyXG4gICAgICAgICAgICAgICAgLypjbG9zZSBhbnkgYWxyZWFkeSBvcGVuIGxpc3RzIG9mIGF1dG8tY29tcGxldGVkIHZhbHVlcyovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmNsb3NlQWxsTGlzdHMoKTtcclxuICAgICAgICAgICAgICAgIGlmICghdmFsKSB7IHJldHVybiBmYWxzZTt9XHJcbiAgICAgICAgICAgICAgICB0aGlzLmN1cnJlbnRGb2N1cyA9IC0xO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qY3JlYXRlIGEgRElWIGVsZW1lbnQgdGhhdCB3aWxsIGNvbnRhaW4gdGhlIGl0ZW1zICh2YWx1ZXMpOiovXHJcbiAgICAgICAgICAgICAgICBhID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcIkRJVlwiKTtcclxuICAgICAgICAgICAgICAgIGEuc2V0QXR0cmlidXRlKFwiaWRcIiwgdGhpcy5uYW1lSW5wdXQuaWQgKyBcIi1hdXRvLWNvbXBsZXRlLWxpc3RcIik7XHJcbiAgICAgICAgICAgICAgICBhLnNldEF0dHJpYnV0ZShcImNsYXNzXCIsIFwidHJuLWF1dG8tY29tcGxldGUtaXRlbXNcIik7XHJcblxyXG4gICAgICAgICAgICAgICAgLyphcHBlbmQgdGhlIERJViBlbGVtZW50IGFzIGEgY2hpbGQgb2YgdGhlIGF1dG8tY29tcGxldGUgY29udGFpbmVyOiovXHJcbiAgICAgICAgICAgICAgICBwYXJlbnQuYXBwZW5kQ2hpbGQoYSk7XHJcblxyXG4gICAgICAgICAgICAgICAgLypmb3IgZWFjaCBpdGVtIGluIHRoZSBhcnJheS4uLiovXHJcbiAgICAgICAgICAgICAgICBmb3IgKGkgPSAwOyBpIDwgZGF0YS5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICAgICAgICAgIGxldCB0ZXh0LCB2YWx1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgLyogV2hpY2ggZm9ybWF0IGRpZCB0aGV5IGdpdmUgdXMuICovXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBkYXRhW2ldID09PSAnb2JqZWN0Jykge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB0ZXh0ID0gZGF0YVtpXVsndGV4dCddO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB2YWx1ZSA9IGRhdGFbaV1bJ3ZhbHVlJ107XHJcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdGV4dCA9IGRhdGFbaV07XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlID0gZGF0YVtpXTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIC8qY2hlY2sgaWYgdGhlIGl0ZW0gc3RhcnRzIHdpdGggdGhlIHNhbWUgbGV0dGVycyBhcyB0aGUgdGV4dCBmaWVsZCB2YWx1ZToqL1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh0ZXh0LnN1YnN0cigwLCB2YWwubGVuZ3RoKS50b1VwcGVyQ2FzZSgpID09PSB2YWwudG9VcHBlckNhc2UoKSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKmNyZWF0ZSBhIERJViBlbGVtZW50IGZvciBlYWNoIG1hdGNoaW5nIGVsZW1lbnQ6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJESVZcIik7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qbWFrZSB0aGUgbWF0Y2hpbmcgbGV0dGVycyBib2xkOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuaW5uZXJIVE1MID0gXCI8c3Ryb25nPlwiICsgdGV4dC5zdWJzdHIoMCwgdmFsLmxlbmd0aCkgKyBcIjwvc3Ryb25nPlwiO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmlubmVySFRNTCArPSB0ZXh0LnN1YnN0cih2YWwubGVuZ3RoKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qaW5zZXJ0IGEgaW5wdXQgZmllbGQgdGhhdCB3aWxsIGhvbGQgdGhlIGN1cnJlbnQgYXJyYXkgaXRlbSdzIHZhbHVlOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuaW5uZXJIVE1MICs9IFwiPGlucHV0IHR5cGU9J2hpZGRlbicgdmFsdWU9J1wiICsgdmFsdWUgKyBcIic+XCI7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmRhdGFzZXQudmFsdWUgPSB2YWx1ZTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5kYXRhc2V0LnRleHQgPSB0ZXh0O1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgLypleGVjdXRlIGEgZnVuY3Rpb24gd2hlbiBzb21lb25lIGNsaWNrcyBvbiB0aGUgaXRlbSB2YWx1ZSAoRElWIGVsZW1lbnQpOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIChlKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhgaXRlbSBjbGlja2VkIHdpdGggdmFsdWUgJHtlLmN1cnJlbnRUYXJnZXQuZGF0YXNldC52YWx1ZX1gKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAvKiBpbnNlcnQgdGhlIHZhbHVlIGZvciB0aGUgYXV0b2NvbXBsZXRlIHRleHQgZmllbGQ6ICovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLm5hbWVJbnB1dC52YWx1ZSA9IGUuY3VycmVudFRhcmdldC5kYXRhc2V0LnRleHQ7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLm5hbWVJbnB1dC5kYXRhc2V0LnNlbGVjdGVkSWQgPSBlLmN1cnJlbnRUYXJnZXQuZGF0YXNldC52YWx1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAvKiBjbG9zZSB0aGUgbGlzdCBvZiBhdXRvY29tcGxldGVkIHZhbHVlcywgKG9yIGFueSBvdGhlciBvcGVuIGxpc3RzIG9mIGF1dG9jb21wbGV0ZWQgdmFsdWVzOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLmNsb3NlQWxsTGlzdHMoKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLm5hbWVJbnB1dC5kaXNwYXRjaEV2ZW50KG5ldyBFdmVudCgnY2hhbmdlJykpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYS5hcHBlbmRDaGlsZChiKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiBwcmVzc2VzIGEga2V5IG9uIHRoZSBrZXlib2FyZDoqL1xyXG4gICAgICAgIHRoaXMubmFtZUlucHV0LmFkZEV2ZW50TGlzdGVuZXIoXCJrZXlkb3duXCIsIChlKSA9PiB7XHJcbiAgICAgICAgICAgIGxldCB4ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGhpcy5uYW1lSW5wdXQuaWQgKyBcIi1hdXRvLWNvbXBsZXRlLWxpc3RcIik7XHJcbiAgICAgICAgICAgIGlmICh4KSB4ID0geC5nZXRFbGVtZW50c0J5VGFnTmFtZShcImRpdlwiKTtcclxuICAgICAgICAgICAgaWYgKGUua2V5Q29kZSA9PT0gNDApIHtcclxuICAgICAgICAgICAgICAgIC8qSWYgdGhlIGFycm93IERPV04ga2V5IGlzIHByZXNzZWQsXHJcbiAgICAgICAgICAgICAgICAgaW5jcmVhc2UgdGhlIGN1cnJlbnRGb2N1cyB2YXJpYWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50Rm9jdXMrKztcclxuICAgICAgICAgICAgICAgIC8qYW5kIGFuZCBtYWtlIHRoZSBjdXJyZW50IGl0ZW0gbW9yZSB2aXNpYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmFkZEFjdGl2ZSh4KTtcclxuICAgICAgICAgICAgfSBlbHNlIGlmIChlLmtleUNvZGUgPT09IDM4KSB7IC8vdXBcclxuICAgICAgICAgICAgICAgIC8qSWYgdGhlIGFycm93IFVQIGtleSBpcyBwcmVzc2VkLFxyXG4gICAgICAgICAgICAgICAgIGRlY3JlYXNlIHRoZSBjdXJyZW50Rm9jdXMgdmFyaWFibGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudEZvY3VzLS07XHJcbiAgICAgICAgICAgICAgICAvKmFuZCBhbmQgbWFrZSB0aGUgY3VycmVudCBpdGVtIG1vcmUgdmlzaWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5hZGRBY3RpdmUoeCk7XHJcbiAgICAgICAgICAgIH0gZWxzZSBpZiAoZS5rZXlDb2RlID09PSAxMykge1xyXG4gICAgICAgICAgICAgICAgLypJZiB0aGUgRU5URVIga2V5IGlzIHByZXNzZWQsIHByZXZlbnQgdGhlIGZvcm0gZnJvbSBiZWluZyBzdWJtaXR0ZWQsKi9cclxuICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgICAgIGlmICh0aGlzLmN1cnJlbnRGb2N1cyA+IC0xKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgLyphbmQgc2ltdWxhdGUgYSBjbGljayBvbiB0aGUgXCJhY3RpdmVcIiBpdGVtOiovXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHgpIHhbdGhpcy5jdXJyZW50Rm9jdXNdLmNsaWNrKCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgLypleGVjdXRlIGEgZnVuY3Rpb24gd2hlbiBzb21lb25lIGNsaWNrcyBpbiB0aGUgZG9jdW1lbnQ6Ki9cclxuICAgICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKGUpID0+IHtcclxuICAgICAgICAgICAgdGhpcy5jbG9zZUFsbExpc3RzKGUudGFyZ2V0KTtcclxuICAgICAgICB9KTtcclxuICAgIH1cclxuXHJcbiAgICBhZGRBY3RpdmUoeCkge1xyXG4gICAgICAgIC8qYSBmdW5jdGlvbiB0byBjbGFzc2lmeSBhbiBpdGVtIGFzIFwiYWN0aXZlXCI6Ki9cclxuICAgICAgICBpZiAoIXgpIHJldHVybiBmYWxzZTtcclxuICAgICAgICAvKnN0YXJ0IGJ5IHJlbW92aW5nIHRoZSBcImFjdGl2ZVwiIGNsYXNzIG9uIGFsbCBpdGVtczoqL1xyXG4gICAgICAgIHRoaXMucmVtb3ZlQWN0aXZlKHgpO1xyXG4gICAgICAgIGlmICh0aGlzLmN1cnJlbnRGb2N1cyA+PSB4Lmxlbmd0aCkgdGhpcy5jdXJyZW50Rm9jdXMgPSAwO1xyXG4gICAgICAgIGlmICh0aGlzLmN1cnJlbnRGb2N1cyA8IDApIHRoaXMuY3VycmVudEZvY3VzID0gKHgubGVuZ3RoIC0gMSk7XHJcbiAgICAgICAgLyphZGQgY2xhc3MgXCJhdXRvY29tcGxldGUtYWN0aXZlXCI6Ki9cclxuICAgICAgICB4W3RoaXMuY3VycmVudEZvY3VzXS5jbGFzc0xpc3QuYWRkKFwidHJuLWF1dG8tY29tcGxldGUtYWN0aXZlXCIpO1xyXG4gICAgfVxyXG5cclxuICAgIHJlbW92ZUFjdGl2ZSh4KSB7XHJcbiAgICAgICAgLyphIGZ1bmN0aW9uIHRvIHJlbW92ZSB0aGUgXCJhY3RpdmVcIiBjbGFzcyBmcm9tIGFsbCBhdXRvY29tcGxldGUgaXRlbXM6Ki9cclxuICAgICAgICBmb3IgKGxldCBpID0gMDsgaSA8IHgubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgeFtpXS5jbGFzc0xpc3QucmVtb3ZlKFwidHJuLWF1dG8tY29tcGxldGUtYWN0aXZlXCIpO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbiAgICBjbG9zZUFsbExpc3RzKGVsZW1lbnQpIHtcclxuICAgICAgICBjb25zb2xlLmxvZyhcImNsb3NlIGFsbCBsaXN0c1wiKTtcclxuICAgICAgICAvKmNsb3NlIGFsbCBhdXRvY29tcGxldGUgbGlzdHMgaW4gdGhlIGRvY3VtZW50LFxyXG4gICAgICAgICBleGNlcHQgdGhlIG9uZSBwYXNzZWQgYXMgYW4gYXJndW1lbnQ6Ki9cclxuICAgICAgICBsZXQgeCA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoXCJ0cm4tYXV0by1jb21wbGV0ZS1pdGVtc1wiKTtcclxuICAgICAgICBmb3IgKGxldCBpID0gMDsgaSA8IHgubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgaWYgKGVsZW1lbnQgIT09IHhbaV0gJiYgZWxlbWVudCAhPT0gdGhpcy5uYW1lSW5wdXQpIHtcclxuICAgICAgICAgICAgICAgIHhbaV0ucGFyZW50Tm9kZS5yZW1vdmVDaGlsZCh4W2ldKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH1cclxufVxyXG5cclxuLy8gRmlyc3QsIGNoZWNrcyBpZiBpdCBpc24ndCBpbXBsZW1lbnRlZCB5ZXQuXHJcbmlmICghU3RyaW5nLnByb3RvdHlwZS5mb3JtYXQpIHtcclxuICAgIFN0cmluZy5wcm90b3R5cGUuZm9ybWF0ID0gZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgY29uc3QgYXJncyA9IGFyZ3VtZW50cztcclxuICAgICAgICByZXR1cm4gdGhpcy5yZXBsYWNlKC97KFxcZCspfS9nLCBmdW5jdGlvbihtYXRjaCwgbnVtYmVyKSB7XHJcbiAgICAgICAgICAgIHJldHVybiB0eXBlb2YgYXJnc1tudW1iZXJdICE9PSAndW5kZWZpbmVkJ1xyXG4gICAgICAgICAgICAgICAgPyBhcmdzW251bWJlcl1cclxuICAgICAgICAgICAgICAgIDogbWF0Y2hcclxuICAgICAgICAgICAgICAgIDtcclxuICAgICAgICB9KTtcclxuICAgIH07XHJcbn0iXSwic291cmNlUm9vdCI6IiJ9