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
/******/ 	return __webpack_require__(__webpack_require__.s = 26);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/my-team-requests-list.js":
/*!*****************************************!*\
  !*** ./src/js/my-team-requests-list.js ***!
  \*****************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./tournamatch.js */ "./src/js/tournamatch.js");
/**
 * Handles events for the list that displays a user's sent invitations to join a team.
 *
 * @link       https://www.tournamatch.com
 * @since      3.15.0
  *
 * @package    Tournamatch
 *
 */


(function ($) {
  'use strict';

  window.addEventListener('load', function () {
    var options = trn_my_team_requests_list_options;
    $.event('my-team-requests').addEventListener('changed', function () {
      getTeamRequests();
    });

    function deleteTeamRequest(request_id) {
      console.log('delete');
      var xhr = new XMLHttpRequest();
      xhr.open('DELETE', options.api_url + 'team-requests/' + request_id);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        console.log(xhr.response);

        if (xhr.status === 204) {
          $.event('my-team-requests').dispatchEvent(new Event('changed'));
        } else {
          console.log(xhr.response); // display error somewhere
        }
      };

      xhr.send();
    }

    function handleDeleteClick(event) {
      deleteTeamRequest(this.dataset.requestId);
    }

    function addListeners() {
      console.log('adding handlers for team requests.');
      var deleteLinks = document.getElementsByClassName('trn-delete-team-request-link');
      Array.prototype.forEach.call(deleteLinks, function (deleteLink) {
        console.log('add');
        deleteLink.addEventListener('click', handleDeleteClick);
      });
    }

    function removeListeners() {
      console.log('removing handlers for team requests.');
      var deleteLinks = document.getElementsByClassName('trn-decline-team-request-link');
      Array.prototype.forEach.call(deleteLinks, function (deleteLink) {
        console.log('remove');
        deleteLink.removeEventListener('click', handleDeleteClick);
      });
    }

    function getTeamRequests() {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', options.api_url + 'team-requests/?_embed&' + $.param({
        user_id: options.user_id
      }));
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        //console.log(xhr);
        var content = "";

        if (xhr.status === 200) {
          var requests = JSON.parse(xhr.response);

          if (requests !== null && requests.length > 0) {
            content += "<ul class=\"trn-list-unstyled\" id=\"trn-my-team-requests-list\">";
            Array.prototype.forEach.call(requests, function (request) {
              content += "<li class=\"trn-text-center\" id=\"trn-join-team-request-".concat(request.team_member_request_id, "\">");
              content += "<a href=\"".concat(request._embedded.team[0].link, "\">").concat(request._embedded.team[0].name, "</a> ");
              content += "<a class=\"trn-delete-team-request-link\" data-request-id=\"".concat(request.team_member_request_id, "\"><i class=\"fa fa-times trn-text-danger\"></i></a>");
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
        document.getElementById('trn-my-team-requests-response').nextSibling.remove();
        document.getElementById('trn-my-team-requests-section').innerHTML += content;
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

/***/ 26:
/*!***********************************************!*\
  !*** multi ./src/js/my-team-requests-list.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\my-team-requests-list.js */"./src/js/my-team-requests-list.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL215LXRlYW0tcmVxdWVzdHMtbGlzdC5qcyIsIndlYnBhY2s6Ly8vLi9zcmMvanMvdG91cm5hbWF0Y2guanMiXSwibmFtZXMiOlsiJCIsIndpbmRvdyIsImFkZEV2ZW50TGlzdGVuZXIiLCJvcHRpb25zIiwidHJuX215X3RlYW1fcmVxdWVzdHNfbGlzdF9vcHRpb25zIiwiZXZlbnQiLCJnZXRUZWFtUmVxdWVzdHMiLCJkZWxldGVUZWFtUmVxdWVzdCIsInJlcXVlc3RfaWQiLCJjb25zb2xlIiwibG9nIiwieGhyIiwiWE1MSHR0cFJlcXVlc3QiLCJvcGVuIiwiYXBpX3VybCIsInNldFJlcXVlc3RIZWFkZXIiLCJyZXN0X25vbmNlIiwib25sb2FkIiwicmVzcG9uc2UiLCJzdGF0dXMiLCJkaXNwYXRjaEV2ZW50IiwiRXZlbnQiLCJzZW5kIiwiaGFuZGxlRGVsZXRlQ2xpY2siLCJkYXRhc2V0IiwicmVxdWVzdElkIiwiYWRkTGlzdGVuZXJzIiwiZGVsZXRlTGlua3MiLCJkb2N1bWVudCIsImdldEVsZW1lbnRzQnlDbGFzc05hbWUiLCJBcnJheSIsInByb3RvdHlwZSIsImZvckVhY2giLCJjYWxsIiwiZGVsZXRlTGluayIsInJlbW92ZUxpc3RlbmVycyIsInJlbW92ZUV2ZW50TGlzdGVuZXIiLCJwYXJhbSIsInVzZXJfaWQiLCJjb250ZW50IiwicmVxdWVzdHMiLCJKU09OIiwicGFyc2UiLCJsZW5ndGgiLCJyZXF1ZXN0IiwidGVhbV9tZW1iZXJfcmVxdWVzdF9pZCIsIl9lbWJlZGRlZCIsInRlYW0iLCJsaW5rIiwibmFtZSIsImxhbmd1YWdlIiwiemVyb19yZXF1ZXN0cyIsImVycm9yIiwiZ2V0RWxlbWVudEJ5SWQiLCJuZXh0U2libGluZyIsInJlbW92ZSIsImlubmVySFRNTCIsInRybiIsIlRvdXJuYW1hdGNoIiwiZXZlbnRzIiwib2JqZWN0IiwicHJlZml4Iiwic3RyIiwicHJvcCIsImhhc093blByb3BlcnR5IiwiayIsInYiLCJwdXNoIiwiZW5jb2RlVVJJQ29tcG9uZW50Iiwiam9pbiIsImV2ZW50TmFtZSIsIkV2ZW50VGFyZ2V0IiwiaW5wdXQiLCJkYXRhQ2FsbGJhY2siLCJUb3VybmFtYXRjaF9BdXRvY29tcGxldGUiLCJzIiwiY2hhckF0IiwidG9VcHBlckNhc2UiLCJzbGljZSIsIm51bWJlciIsInJlbWFpbmRlciIsImVsZW1lbnQiLCJ0YWJzIiwicGFuZXMiLCJjbGVhckFjdGl2ZSIsInRhYiIsImNsYXNzTGlzdCIsImFyaWFTZWxlY3RlZCIsInBhbmUiLCJzZXRBY3RpdmUiLCJ0YXJnZXRJZCIsInRhcmdldFRhYiIsInF1ZXJ5U2VsZWN0b3IiLCJ0YXJnZXRQYW5lSWQiLCJ0YXJnZXQiLCJhZGQiLCJ0YWJDbGljayIsImN1cnJlbnRUYXJnZXQiLCJwcmV2ZW50RGVmYXVsdCIsImxvY2F0aW9uIiwiaGFzaCIsInN1YnN0ciIsInRybl9vYmpfaW5zdGFuY2UiLCJ0YWJWaWV3cyIsImZyb20iLCJkcm9wZG93bnMiLCJoYW5kbGVEcm9wZG93bkNsb3NlIiwiZHJvcGRvd24iLCJuZXh0RWxlbWVudFNpYmxpbmciLCJlIiwic3RvcFByb3BhZ2F0aW9uIiwibmFtZUlucHV0IiwiYSIsImIiLCJpIiwidmFsIiwidmFsdWUiLCJwYXJlbnQiLCJwYXJlbnROb2RlIiwidGhlbiIsImRhdGEiLCJjbG9zZUFsbExpc3RzIiwiY3VycmVudEZvY3VzIiwiY3JlYXRlRWxlbWVudCIsInNldEF0dHJpYnV0ZSIsImlkIiwiYXBwZW5kQ2hpbGQiLCJ0ZXh0Iiwic2VsZWN0ZWRJZCIsIngiLCJnZXRFbGVtZW50c0J5VGFnTmFtZSIsImtleUNvZGUiLCJhZGRBY3RpdmUiLCJjbGljayIsInJlbW92ZUFjdGl2ZSIsInJlbW92ZUNoaWxkIiwiU3RyaW5nIiwiZm9ybWF0IiwiYXJncyIsImFyZ3VtZW50cyIsInJlcGxhY2UiLCJtYXRjaCJdLCJtYXBwaW5ncyI6IjtRQUFBO1FBQ0E7O1FB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QUNwRCxhQUFPLE9BQU8yRCxJQUFJLENBQUMzRCxNQUFELENBQVgsS0FBd0IsV0FBeEIsR0FDRDJELElBQUksQ0FBQzNELE1BQUQsQ0FESCxHQUVEOEQsS0FGTjtBQUlILEtBTE0sQ0FBUDtBQU1ILEdBUkQ7QUFTSCxDIiwiZmlsZSI6Im15LXRlYW0tcmVxdWVzdHMtbGlzdC5qcyIsInNvdXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwgeyBlbnVtZXJhYmxlOiB0cnVlLCBnZXQ6IGdldHRlciB9KTtcbiBcdFx0fVxuIFx0fTtcblxuIFx0Ly8gZGVmaW5lIF9fZXNNb2R1bGUgb24gZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yID0gZnVuY3Rpb24oZXhwb3J0cykge1xuIFx0XHRpZih0eXBlb2YgU3ltYm9sICE9PSAndW5kZWZpbmVkJyAmJiBTeW1ib2wudG9TdHJpbmdUYWcpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgU3ltYm9sLnRvU3RyaW5nVGFnLCB7IHZhbHVlOiAnTW9kdWxlJyB9KTtcbiBcdFx0fVxuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgJ19fZXNNb2R1bGUnLCB7IHZhbHVlOiB0cnVlIH0pO1xuIFx0fTtcblxuIFx0Ly8gY3JlYXRlIGEgZmFrZSBuYW1lc3BhY2Ugb2JqZWN0XG4gXHQvLyBtb2RlICYgMTogdmFsdWUgaXMgYSBtb2R1bGUgaWQsIHJlcXVpcmUgaXRcbiBcdC8vIG1vZGUgJiAyOiBtZXJnZSBhbGwgcHJvcGVydGllcyBvZiB2YWx1ZSBpbnRvIHRoZSBuc1xuIFx0Ly8gbW9kZSAmIDQ6IHJldHVybiB2YWx1ZSB3aGVuIGFscmVhZHkgbnMgb2JqZWN0XG4gXHQvLyBtb2RlICYgOHwxOiBiZWhhdmUgbGlrZSByZXF1aXJlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnQgPSBmdW5jdGlvbih2YWx1ZSwgbW9kZSkge1xuIFx0XHRpZihtb2RlICYgMSkgdmFsdWUgPSBfX3dlYnBhY2tfcmVxdWlyZV9fKHZhbHVlKTtcbiBcdFx0aWYobW9kZSAmIDgpIHJldHVybiB2YWx1ZTtcbiBcdFx0aWYoKG1vZGUgJiA0KSAmJiB0eXBlb2YgdmFsdWUgPT09ICdvYmplY3QnICYmIHZhbHVlICYmIHZhbHVlLl9fZXNNb2R1bGUpIHJldHVybiB2YWx1ZTtcbiBcdFx0dmFyIG5zID0gT2JqZWN0LmNyZWF0ZShudWxsKTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yKG5zKTtcbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KG5zLCAnZGVmYXVsdCcsIHsgZW51bWVyYWJsZTogdHJ1ZSwgdmFsdWU6IHZhbHVlIH0pO1xuIFx0XHRpZihtb2RlICYgMiAmJiB0eXBlb2YgdmFsdWUgIT0gJ3N0cmluZycpIGZvcih2YXIga2V5IGluIHZhbHVlKSBfX3dlYnBhY2tfcmVxdWlyZV9fLmQobnMsIGtleSwgZnVuY3Rpb24oa2V5KSB7IHJldHVybiB2YWx1ZVtrZXldOyB9LmJpbmQobnVsbCwga2V5KSk7XG4gXHRcdHJldHVybiBucztcbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiXCI7XG5cblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSAyNik7XG4iLCIvKipcclxuICogSGFuZGxlcyBldmVudHMgZm9yIHRoZSBsaXN0IHRoYXQgZGlzcGxheXMgYSB1c2VyJ3Mgc2VudCBpbnZpdGF0aW9ucyB0byBqb2luIGEgdGVhbS5cclxuICpcclxuICogQGxpbmsgICAgICAgaHR0cHM6Ly93d3cudG91cm5hbWF0Y2guY29tXHJcbiAqIEBzaW5jZSAgICAgIDMuMTUuMFxyXG4gICpcclxuICogQHBhY2thZ2UgICAgVG91cm5hbWF0Y2hcclxuICpcclxuICovXHJcbmltcG9ydCB7IHRybiB9IGZyb20gJy4vdG91cm5hbWF0Y2guanMnO1xyXG5cclxuKGZ1bmN0aW9uICgkKSB7XHJcbiAgICAndXNlIHN0cmljdCc7XHJcblxyXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgY29uc3Qgb3B0aW9ucyA9IHRybl9teV90ZWFtX3JlcXVlc3RzX2xpc3Rfb3B0aW9ucztcclxuXHJcbiAgICAgICAgJC5ldmVudCgnbXktdGVhbS1yZXF1ZXN0cycpLmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZWQnLCBmdW5jdGlvbigpIHtcclxuICAgICAgICAgICAgZ2V0VGVhbVJlcXVlc3RzKCk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIGRlbGV0ZVRlYW1SZXF1ZXN0KHJlcXVlc3RfaWQpIHtcclxuICAgICAgICAgICAgY29uc29sZS5sb2coJ2RlbGV0ZScpO1xyXG4gICAgICAgICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgIHhoci5vcGVuKCdERUxFVEUnLCBvcHRpb25zLmFwaV91cmwgKyAndGVhbS1yZXF1ZXN0cy8nICsgcmVxdWVzdF9pZCk7XHJcbiAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdDb250ZW50LVR5cGUnLCAnYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkJyk7XHJcbiAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKHhoci5yZXNwb25zZSk7XHJcbiAgICAgICAgICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjA0KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgJC5ldmVudCgnbXktdGVhbS1yZXF1ZXN0cycpLmRpc3BhdGNoRXZlbnQobmV3IEV2ZW50KCdjaGFuZ2VkJykpO1xyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyh4aHIucmVzcG9uc2UpO1xyXG4gICAgICAgICAgICAgICAgICAgIC8vIGRpc3BsYXkgZXJyb3Igc29tZXdoZXJlXHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgICAgICB4aHIuc2VuZCgpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZnVuY3Rpb24gaGFuZGxlRGVsZXRlQ2xpY2soZXZlbnQpIHtcclxuICAgICAgICAgICAgZGVsZXRlVGVhbVJlcXVlc3QodGhpcy5kYXRhc2V0LnJlcXVlc3RJZClcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIGFkZExpc3RlbmVycygpIHtcclxuICAgICAgICAgICAgY29uc29sZS5sb2coJ2FkZGluZyBoYW5kbGVycyBmb3IgdGVhbSByZXF1ZXN0cy4nKTtcclxuICAgICAgICAgICAgbGV0IGRlbGV0ZUxpbmtzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLWRlbGV0ZS10ZWFtLXJlcXVlc3QtbGluaycpO1xyXG4gICAgICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKGRlbGV0ZUxpbmtzLCAgZnVuY3Rpb24oZGVsZXRlTGluaykge1xyXG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coJ2FkZCcpO1xyXG4gICAgICAgICAgICAgICAgZGVsZXRlTGluay5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGhhbmRsZURlbGV0ZUNsaWNrKTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBmdW5jdGlvbiByZW1vdmVMaXN0ZW5lcnMoKSB7XHJcbiAgICAgICAgICAgIGNvbnNvbGUubG9nKCdyZW1vdmluZyBoYW5kbGVycyBmb3IgdGVhbSByZXF1ZXN0cy4nKTtcclxuICAgICAgICAgICAgbGV0IGRlbGV0ZUxpbmtzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLWRlY2xpbmUtdGVhbS1yZXF1ZXN0LWxpbmsnKTtcclxuICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbChkZWxldGVMaW5rcywgZnVuY3Rpb24oZGVsZXRlTGluaykge1xyXG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coJ3JlbW92ZScpO1xyXG4gICAgICAgICAgICAgICAgZGVsZXRlTGluay5yZW1vdmVFdmVudExpc3RlbmVyKCdjbGljaycsIGhhbmRsZURlbGV0ZUNsaWNrKTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBmdW5jdGlvbiBnZXRUZWFtUmVxdWVzdHMoKSB7XHJcbiAgICAgICAgICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgeGhyLm9wZW4oJ0dFVCcsIG9wdGlvbnMuYXBpX3VybCArICd0ZWFtLXJlcXVlc3RzLz9fZW1iZWQmJyArICQucGFyYW0oe3VzZXJfaWQ6IG9wdGlvbnMudXNlcl9pZH0pKTtcclxuICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ1gtV1AtTm9uY2UnLCBvcHRpb25zLnJlc3Rfbm9uY2UpO1xyXG4gICAgICAgICAgICB4aHIub25sb2FkID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgLy9jb25zb2xlLmxvZyh4aHIpO1xyXG4gICAgICAgICAgICAgICAgbGV0IGNvbnRlbnQgPSBgYDtcclxuICAgICAgICAgICAgICAgIGlmICh4aHIuc3RhdHVzID09PSAyMDApIHtcclxuICAgICAgICAgICAgICAgICAgICBsZXQgcmVxdWVzdHMgPSBKU09OLnBhcnNlKHhoci5yZXNwb25zZSk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIGlmICggcmVxdWVzdHMgIT09IG51bGwgJiYgcmVxdWVzdHMubGVuZ3RoID4gMCApIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPHVsIGNsYXNzPVwidHJuLWxpc3QtdW5zdHlsZWRcIiBpZD1cInRybi1teS10ZWFtLXJlcXVlc3RzLWxpc3RcIj5gO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbChyZXF1ZXN0cywgZnVuY3Rpb24ocmVxdWVzdCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPGxpIGNsYXNzPVwidHJuLXRleHQtY2VudGVyXCIgaWQ9XCJ0cm4tam9pbi10ZWFtLXJlcXVlc3QtJHtyZXF1ZXN0LnRlYW1fbWVtYmVyX3JlcXVlc3RfaWR9XCI+YDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxhIGhyZWY9XCIke3JlcXVlc3QuX2VtYmVkZGVkLnRlYW1bMF0ubGlua31cIj4ke3JlcXVlc3QuX2VtYmVkZGVkLnRlYW1bMF0ubmFtZX08L2E+IGA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8YSBjbGFzcz1cInRybi1kZWxldGUtdGVhbS1yZXF1ZXN0LWxpbmtcIiBkYXRhLXJlcXVlc3QtaWQ9XCIke3JlcXVlc3QudGVhbV9tZW1iZXJfcmVxdWVzdF9pZH1cIj48aSBjbGFzcz1cImZhIGZhLXRpbWVzIHRybi10ZXh0LWRhbmdlclwiPjwvaT48L2E+YDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDwvbGk+YDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8L3VsPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPHAgY2xhc3M9XCJ0cm4tdGV4dC1jZW50ZXJcIj4ke29wdGlvbnMubGFuZ3VhZ2UuemVyb19yZXF1ZXN0c308L3A+YDtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxwIGNsYXNzPVwidHJuLXRleHQtY2VudGVyXCI+JHtvcHRpb25zLmxhbmd1YWdlLmVycm9yfTwvcD5gO1xyXG4gICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgIHJlbW92ZUxpc3RlbmVycygpO1xyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Rybi1teS10ZWFtLXJlcXVlc3RzLXJlc3BvbnNlJykubmV4dFNpYmxpbmcucmVtb3ZlKCk7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLW15LXRlYW0tcmVxdWVzdHMtc2VjdGlvbicpLmlubmVySFRNTCArPSBjb250ZW50O1xyXG4gICAgICAgICAgICAgICAgYWRkTGlzdGVuZXJzKCk7XHJcbiAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgICAgICB4aHIuc2VuZCgpO1xyXG4gICAgICAgIH1cclxuICAgICAgICBnZXRUZWFtUmVxdWVzdHMoKTtcclxuICAgIH0sIGZhbHNlKTtcclxufSkodHJuKTsiLCIndXNlIHN0cmljdCc7XHJcbmNsYXNzIFRvdXJuYW1hdGNoIHtcclxuXHJcbiAgICBjb25zdHJ1Y3RvcigpIHtcclxuICAgICAgICB0aGlzLmV2ZW50cyA9IHt9O1xyXG4gICAgfVxyXG5cclxuICAgIHBhcmFtKG9iamVjdCwgcHJlZml4KSB7XHJcbiAgICAgICAgbGV0IHN0ciA9IFtdO1xyXG4gICAgICAgIGZvciAobGV0IHByb3AgaW4gb2JqZWN0KSB7XHJcbiAgICAgICAgICAgIGlmIChvYmplY3QuaGFzT3duUHJvcGVydHkocHJvcCkpIHtcclxuICAgICAgICAgICAgICAgIGxldCBrID0gcHJlZml4ID8gcHJlZml4ICsgXCJbXCIgKyBwcm9wICsgXCJdXCIgOiBwcm9wO1xyXG4gICAgICAgICAgICAgICAgbGV0IHYgPSBvYmplY3RbcHJvcF07XHJcbiAgICAgICAgICAgICAgICBzdHIucHVzaCgodiAhPT0gbnVsbCAmJiB0eXBlb2YgdiA9PT0gXCJvYmplY3RcIikgPyB0aGlzLnBhcmFtKHYsIGspIDogZW5jb2RlVVJJQ29tcG9uZW50KGspICsgXCI9XCIgKyBlbmNvZGVVUklDb21wb25lbnQodikpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiBzdHIuam9pbihcIiZcIik7XHJcbiAgICB9XHJcblxyXG4gICAgZXZlbnQoZXZlbnROYW1lKSB7XHJcbiAgICAgICAgaWYgKCEoZXZlbnROYW1lIGluIHRoaXMuZXZlbnRzKSkge1xyXG4gICAgICAgICAgICB0aGlzLmV2ZW50c1tldmVudE5hbWVdID0gbmV3IEV2ZW50VGFyZ2V0KGV2ZW50TmFtZSk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiB0aGlzLmV2ZW50c1tldmVudE5hbWVdO1xyXG4gICAgfVxyXG5cclxuICAgIGF1dG9jb21wbGV0ZShpbnB1dCwgZGF0YUNhbGxiYWNrKSB7XHJcbiAgICAgICAgbmV3IFRvdXJuYW1hdGNoX0F1dG9jb21wbGV0ZShpbnB1dCwgZGF0YUNhbGxiYWNrKTtcclxuICAgIH1cclxuXHJcbiAgICB1Y2ZpcnN0KHMpIHtcclxuICAgICAgICBpZiAodHlwZW9mIHMgIT09ICdzdHJpbmcnKSByZXR1cm4gJyc7XHJcbiAgICAgICAgcmV0dXJuIHMuY2hhckF0KDApLnRvVXBwZXJDYXNlKCkgKyBzLnNsaWNlKDEpO1xyXG4gICAgfVxyXG5cclxuICAgIG9yZGluYWxfc3VmZml4KG51bWJlcikge1xyXG4gICAgICAgIGNvbnN0IHJlbWFpbmRlciA9IG51bWJlciAlIDEwMDtcclxuXHJcbiAgICAgICAgaWYgKChyZW1haW5kZXIgPCAxMSkgfHwgKHJlbWFpbmRlciA+IDEzKSkge1xyXG4gICAgICAgICAgICBzd2l0Y2ggKHJlbWFpbmRlciAlIDEwKSB7XHJcbiAgICAgICAgICAgICAgICBjYXNlIDE6IHJldHVybiAnc3QnO1xyXG4gICAgICAgICAgICAgICAgY2FzZSAyOiByZXR1cm4gJ25kJztcclxuICAgICAgICAgICAgICAgIGNhc2UgMzogcmV0dXJuICdyZCc7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuICd0aCc7XHJcbiAgICB9XHJcblxyXG4gICAgdGFicyhlbGVtZW50KSB7XHJcbiAgICAgICAgY29uc3QgdGFicyA9IGVsZW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLW5hdi1saW5rJyk7XHJcbiAgICAgICAgY29uc3QgcGFuZXMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tdGFiLXBhbmUnKTtcclxuICAgICAgICBjb25zdCBjbGVhckFjdGl2ZSA9ICgpID0+IHtcclxuICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbCh0YWJzLCAodGFiKSA9PiB7XHJcbiAgICAgICAgICAgICAgICB0YWIuY2xhc3NMaXN0LnJlbW92ZSgndHJuLW5hdi1hY3RpdmUnKTtcclxuICAgICAgICAgICAgICAgIHRhYi5hcmlhU2VsZWN0ZWQgPSBmYWxzZTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwocGFuZXMsIHBhbmUgPT4gcGFuZS5jbGFzc0xpc3QucmVtb3ZlKCd0cm4tdGFiLWFjdGl2ZScpKTtcclxuICAgICAgICB9O1xyXG4gICAgICAgIGNvbnN0IHNldEFjdGl2ZSA9ICh0YXJnZXRJZCkgPT4ge1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRUYWIgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdhW2hyZWY9XCIjJyArIHRhcmdldElkICsgJ1wiXS50cm4tbmF2LWxpbmsnKTtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0UGFuZUlkID0gdGFyZ2V0VGFiICYmIHRhcmdldFRhYi5kYXRhc2V0ICYmIHRhcmdldFRhYi5kYXRhc2V0LnRhcmdldCB8fCBmYWxzZTtcclxuXHJcbiAgICAgICAgICAgIGlmICh0YXJnZXRQYW5lSWQpIHtcclxuICAgICAgICAgICAgICAgIGNsZWFyQWN0aXZlKCk7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRUYWIuY2xhc3NMaXN0LmFkZCgndHJuLW5hdi1hY3RpdmUnKTtcclxuICAgICAgICAgICAgICAgIHRhcmdldFRhYi5hcmlhU2VsZWN0ZWQgPSB0cnVlO1xyXG5cclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHRhcmdldFBhbmVJZCkuY2xhc3NMaXN0LmFkZCgndHJuLXRhYi1hY3RpdmUnKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH07XHJcbiAgICAgICAgY29uc3QgdGFiQ2xpY2sgPSAoZXZlbnQpID0+IHtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0VGFiID0gZXZlbnQuY3VycmVudFRhcmdldDtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0UGFuZUlkID0gdGFyZ2V0VGFiICYmIHRhcmdldFRhYi5kYXRhc2V0ICYmIHRhcmdldFRhYi5kYXRhc2V0LnRhcmdldCB8fCBmYWxzZTtcclxuXHJcbiAgICAgICAgICAgIGlmICh0YXJnZXRQYW5lSWQpIHtcclxuICAgICAgICAgICAgICAgIHNldEFjdGl2ZSh0YXJnZXRQYW5lSWQpO1xyXG4gICAgICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH07XHJcblxyXG4gICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwodGFicywgKHRhYikgPT4ge1xyXG4gICAgICAgICAgICB0YWIuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0YWJDbGljayk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIGlmIChsb2NhdGlvbi5oYXNoKSB7XHJcbiAgICAgICAgICAgIHNldEFjdGl2ZShsb2NhdGlvbi5oYXNoLnN1YnN0cigxKSk7XHJcbiAgICAgICAgfSBlbHNlIGlmICh0YWJzLmxlbmd0aCA+IDApIHtcclxuICAgICAgICAgICAgc2V0QWN0aXZlKHRhYnNbMF0uZGF0YXNldC50YXJnZXQpO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbn1cclxuXHJcbi8vdHJuLmluaXRpYWxpemUoKTtcclxuaWYgKCF3aW5kb3cudHJuX29ial9pbnN0YW5jZSkge1xyXG4gICAgd2luZG93LnRybl9vYmpfaW5zdGFuY2UgPSBuZXcgVG91cm5hbWF0Y2goKTtcclxuXHJcbiAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIGZ1bmN0aW9uICgpIHtcclxuXHJcbiAgICAgICAgY29uc3QgdGFiVmlld3MgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tbmF2Jyk7XHJcblxyXG4gICAgICAgIEFycmF5LmZyb20odGFiVmlld3MpLmZvckVhY2goKHRhYikgPT4ge1xyXG4gICAgICAgICAgICB0cm4udGFicyh0YWIpO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICBjb25zdCBkcm9wZG93bnMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tZHJvcGRvd24tdG9nZ2xlJyk7XHJcbiAgICAgICAgY29uc3QgaGFuZGxlRHJvcGRvd25DbG9zZSA9ICgpID0+IHtcclxuICAgICAgICAgICAgQXJyYXkuZnJvbShkcm9wZG93bnMpLmZvckVhY2goKGRyb3Bkb3duKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBkcm9wZG93bi5uZXh0RWxlbWVudFNpYmxpbmcuY2xhc3NMaXN0LnJlbW92ZSgndHJuLXNob3cnKTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIGRvY3VtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBoYW5kbGVEcm9wZG93bkNsb3NlLCBmYWxzZSk7XHJcbiAgICAgICAgfTtcclxuXHJcbiAgICAgICAgQXJyYXkuZnJvbShkcm9wZG93bnMpLmZvckVhY2goKGRyb3Bkb3duKSA9PiB7XHJcbiAgICAgICAgICAgIGRyb3Bkb3duLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24oZSkge1xyXG4gICAgICAgICAgICAgICAgZS5zdG9wUHJvcGFnYXRpb24oKTtcclxuICAgICAgICAgICAgICAgIHRoaXMubmV4dEVsZW1lbnRTaWJsaW5nLmNsYXNzTGlzdC5hZGQoJ3Rybi1zaG93Jyk7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgaGFuZGxlRHJvcGRvd25DbG9zZSwgZmFsc2UpO1xyXG4gICAgICAgICAgICB9LCBmYWxzZSk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgfSwgZmFsc2UpO1xyXG59XHJcbmV4cG9ydCBsZXQgdHJuID0gd2luZG93LnRybl9vYmpfaW5zdGFuY2U7XHJcblxyXG5jbGFzcyBUb3VybmFtYXRjaF9BdXRvY29tcGxldGUge1xyXG5cclxuICAgIC8vIGN1cnJlbnRGb2N1cztcclxuICAgIC8vXHJcbiAgICAvLyBuYW1lSW5wdXQ7XHJcbiAgICAvL1xyXG4gICAgLy8gc2VsZjtcclxuXHJcbiAgICBjb25zdHJ1Y3RvcihpbnB1dCwgZGF0YUNhbGxiYWNrKSB7XHJcbiAgICAgICAgLy8gdGhpcy5zZWxmID0gdGhpcztcclxuICAgICAgICB0aGlzLm5hbWVJbnB1dCA9IGlucHV0O1xyXG5cclxuICAgICAgICB0aGlzLm5hbWVJbnB1dC5hZGRFdmVudExpc3RlbmVyKFwiaW5wdXRcIiwgKCkgPT4ge1xyXG4gICAgICAgICAgICBsZXQgYSwgYiwgaSwgdmFsID0gdGhpcy5uYW1lSW5wdXQudmFsdWU7Ly90aGlzLnZhbHVlO1xyXG4gICAgICAgICAgICBsZXQgcGFyZW50ID0gdGhpcy5uYW1lSW5wdXQucGFyZW50Tm9kZTsvL3RoaXMucGFyZW50Tm9kZTtcclxuXHJcbiAgICAgICAgICAgIC8vIGxldCBwID0gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xyXG4gICAgICAgICAgICAvLyAgICAgLyogbmVlZCB0byBxdWVyeSBzZXJ2ZXIgZm9yIG5hbWVzIGhlcmUuICovXHJcbiAgICAgICAgICAgIC8vICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIub3BlbignR0VUJywgb3B0aW9ucy5hcGlfdXJsICsgJ3BsYXllcnMvP3NlYXJjaD0nICsgdmFsICsgJyZwZXJfcGFnZT01Jyk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ1gtV1AtTm9uY2UnLCBvcHRpb25zLnJlc3Rfbm9uY2UpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgLy8gICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAwKSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgICAgIC8vIHJlc29sdmUoSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpLm1hcCgocGxheWVyKSA9PiB7cmV0dXJuIHsgJ3ZhbHVlJzogcGxheWVyLmlkLCAndGV4dCc6IHBsYXllci5uYW1lIH07fSkpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgICAgICByZXNvbHZlKEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKS5tYXAoKHBsYXllcikgPT4ge3JldHVybiBwbGF5ZXIubmFtZTt9KSk7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgLy8gICAgICAgICAgICAgcmVqZWN0KCk7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgfVxyXG4gICAgICAgICAgICAvLyAgICAgfTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5zZW5kKCk7XHJcbiAgICAgICAgICAgIC8vIH0pO1xyXG4gICAgICAgICAgICBkYXRhQ2FsbGJhY2sodmFsKS50aGVuKChkYXRhKSA9PiB7Ly9wLnRoZW4oKGRhdGEpID0+IHtcclxuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKGRhdGEpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qY2xvc2UgYW55IGFscmVhZHkgb3BlbiBsaXN0cyBvZiBhdXRvLWNvbXBsZXRlZCB2YWx1ZXMqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5jbG9zZUFsbExpc3RzKCk7XHJcbiAgICAgICAgICAgICAgICBpZiAoIXZhbCkgeyByZXR1cm4gZmFsc2U7fVxyXG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50Rm9jdXMgPSAtMTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmNyZWF0ZSBhIERJViBlbGVtZW50IHRoYXQgd2lsbCBjb250YWluIHRoZSBpdGVtcyAodmFsdWVzKToqL1xyXG4gICAgICAgICAgICAgICAgYSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJESVZcIik7XHJcbiAgICAgICAgICAgICAgICBhLnNldEF0dHJpYnV0ZShcImlkXCIsIHRoaXMubmFtZUlucHV0LmlkICsgXCItYXV0by1jb21wbGV0ZS1saXN0XCIpO1xyXG4gICAgICAgICAgICAgICAgYS5zZXRBdHRyaWJ1dGUoXCJjbGFzc1wiLCBcInRybi1hdXRvLWNvbXBsZXRlLWl0ZW1zXCIpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qYXBwZW5kIHRoZSBESVYgZWxlbWVudCBhcyBhIGNoaWxkIG9mIHRoZSBhdXRvLWNvbXBsZXRlIGNvbnRhaW5lcjoqL1xyXG4gICAgICAgICAgICAgICAgcGFyZW50LmFwcGVuZENoaWxkKGEpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qZm9yIGVhY2ggaXRlbSBpbiB0aGUgYXJyYXkuLi4qL1xyXG4gICAgICAgICAgICAgICAgZm9yIChpID0gMDsgaSA8IGRhdGEubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgICAgICAgICBsZXQgdGV4dCwgdmFsdWU7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIC8qIFdoaWNoIGZvcm1hdCBkaWQgdGhleSBnaXZlIHVzLiAqL1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgZGF0YVtpXSA9PT0gJ29iamVjdCcpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdGV4dCA9IGRhdGFbaV1bJ3RleHQnXTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSBkYXRhW2ldWyd2YWx1ZSddO1xyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHRleHQgPSBkYXRhW2ldO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB2YWx1ZSA9IGRhdGFbaV07XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgICAgICAvKmNoZWNrIGlmIHRoZSBpdGVtIHN0YXJ0cyB3aXRoIHRoZSBzYW1lIGxldHRlcnMgYXMgdGhlIHRleHQgZmllbGQgdmFsdWU6Ki9cclxuICAgICAgICAgICAgICAgICAgICBpZiAodGV4dC5zdWJzdHIoMCwgdmFsLmxlbmd0aCkudG9VcHBlckNhc2UoKSA9PT0gdmFsLnRvVXBwZXJDYXNlKCkpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgLypjcmVhdGUgYSBESVYgZWxlbWVudCBmb3IgZWFjaCBtYXRjaGluZyBlbGVtZW50OiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiRElWXCIpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKm1ha2UgdGhlIG1hdGNoaW5nIGxldHRlcnMgYm9sZDoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmlubmVySFRNTCA9IFwiPHN0cm9uZz5cIiArIHRleHQuc3Vic3RyKDAsIHZhbC5sZW5ndGgpICsgXCI8L3N0cm9uZz5cIjtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgKz0gdGV4dC5zdWJzdHIodmFsLmxlbmd0aCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKmluc2VydCBhIGlucHV0IGZpZWxkIHRoYXQgd2lsbCBob2xkIHRoZSBjdXJyZW50IGFycmF5IGl0ZW0ncyB2YWx1ZToqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmlubmVySFRNTCArPSBcIjxpbnB1dCB0eXBlPSdoaWRkZW4nIHZhbHVlPSdcIiArIHZhbHVlICsgXCInPlwiO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5kYXRhc2V0LnZhbHVlID0gdmFsdWU7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuZGF0YXNldC50ZXh0ID0gdGV4dDtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHdoZW4gc29tZW9uZSBjbGlja3Mgb24gdGhlIGl0ZW0gdmFsdWUgKERJViBlbGVtZW50KToqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2coYGl0ZW0gY2xpY2tlZCB3aXRoIHZhbHVlICR7ZS5jdXJyZW50VGFyZ2V0LmRhdGFzZXQudmFsdWV9YCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLyogaW5zZXJ0IHRoZSB2YWx1ZSBmb3IgdGhlIGF1dG9jb21wbGV0ZSB0ZXh0IGZpZWxkOiAqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5uYW1lSW5wdXQudmFsdWUgPSBlLmN1cnJlbnRUYXJnZXQuZGF0YXNldC50ZXh0O1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5uYW1lSW5wdXQuZGF0YXNldC5zZWxlY3RlZElkID0gZS5jdXJyZW50VGFyZ2V0LmRhdGFzZXQudmFsdWU7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLyogY2xvc2UgdGhlIGxpc3Qgb2YgYXV0b2NvbXBsZXRlZCB2YWx1ZXMsIChvciBhbnkgb3RoZXIgb3BlbiBsaXN0cyBvZiBhdXRvY29tcGxldGVkIHZhbHVlczoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5jbG9zZUFsbExpc3RzKCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5uYW1lSW5wdXQuZGlzcGF0Y2hFdmVudChuZXcgRXZlbnQoJ2NoYW5nZScpKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGEuYXBwZW5kQ2hpbGQoYik7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgLypleGVjdXRlIGEgZnVuY3Rpb24gcHJlc3NlcyBhIGtleSBvbiB0aGUga2V5Ym9hcmQ6Ki9cclxuICAgICAgICB0aGlzLm5hbWVJbnB1dC5hZGRFdmVudExpc3RlbmVyKFwia2V5ZG93blwiLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICBsZXQgeCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHRoaXMubmFtZUlucHV0LmlkICsgXCItYXV0by1jb21wbGV0ZS1saXN0XCIpO1xyXG4gICAgICAgICAgICBpZiAoeCkgeCA9IHguZ2V0RWxlbWVudHNCeVRhZ05hbWUoXCJkaXZcIik7XHJcbiAgICAgICAgICAgIGlmIChlLmtleUNvZGUgPT09IDQwKSB7XHJcbiAgICAgICAgICAgICAgICAvKklmIHRoZSBhcnJvdyBET1dOIGtleSBpcyBwcmVzc2VkLFxyXG4gICAgICAgICAgICAgICAgIGluY3JlYXNlIHRoZSBjdXJyZW50Rm9jdXMgdmFyaWFibGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudEZvY3VzKys7XHJcbiAgICAgICAgICAgICAgICAvKmFuZCBhbmQgbWFrZSB0aGUgY3VycmVudCBpdGVtIG1vcmUgdmlzaWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5hZGRBY3RpdmUoeCk7XHJcbiAgICAgICAgICAgIH0gZWxzZSBpZiAoZS5rZXlDb2RlID09PSAzOCkgeyAvL3VwXHJcbiAgICAgICAgICAgICAgICAvKklmIHRoZSBhcnJvdyBVUCBrZXkgaXMgcHJlc3NlZCxcclxuICAgICAgICAgICAgICAgICBkZWNyZWFzZSB0aGUgY3VycmVudEZvY3VzIHZhcmlhYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmN1cnJlbnRGb2N1cy0tO1xyXG4gICAgICAgICAgICAgICAgLyphbmQgYW5kIG1ha2UgdGhlIGN1cnJlbnQgaXRlbSBtb3JlIHZpc2libGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuYWRkQWN0aXZlKHgpO1xyXG4gICAgICAgICAgICB9IGVsc2UgaWYgKGUua2V5Q29kZSA9PT0gMTMpIHtcclxuICAgICAgICAgICAgICAgIC8qSWYgdGhlIEVOVEVSIGtleSBpcyBwcmVzc2VkLCBwcmV2ZW50IHRoZSBmb3JtIGZyb20gYmVpbmcgc3VibWl0dGVkLCovXHJcbiAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgICAgICBpZiAodGhpcy5jdXJyZW50Rm9jdXMgPiAtMSkge1xyXG4gICAgICAgICAgICAgICAgICAgIC8qYW5kIHNpbXVsYXRlIGEgY2xpY2sgb24gdGhlIFwiYWN0aXZlXCIgaXRlbToqL1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh4KSB4W3RoaXMuY3VycmVudEZvY3VzXS5jbGljaygpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHdoZW4gc29tZW9uZSBjbGlja3MgaW4gdGhlIGRvY3VtZW50OiovXHJcbiAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIChlKSA9PiB7XHJcbiAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cyhlLnRhcmdldCk7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9XHJcblxyXG4gICAgYWRkQWN0aXZlKHgpIHtcclxuICAgICAgICAvKmEgZnVuY3Rpb24gdG8gY2xhc3NpZnkgYW4gaXRlbSBhcyBcImFjdGl2ZVwiOiovXHJcbiAgICAgICAgaWYgKCF4KSByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgLypzdGFydCBieSByZW1vdmluZyB0aGUgXCJhY3RpdmVcIiBjbGFzcyBvbiBhbGwgaXRlbXM6Ki9cclxuICAgICAgICB0aGlzLnJlbW92ZUFjdGl2ZSh4KTtcclxuICAgICAgICBpZiAodGhpcy5jdXJyZW50Rm9jdXMgPj0geC5sZW5ndGgpIHRoaXMuY3VycmVudEZvY3VzID0gMDtcclxuICAgICAgICBpZiAodGhpcy5jdXJyZW50Rm9jdXMgPCAwKSB0aGlzLmN1cnJlbnRGb2N1cyA9ICh4Lmxlbmd0aCAtIDEpO1xyXG4gICAgICAgIC8qYWRkIGNsYXNzIFwiYXV0b2NvbXBsZXRlLWFjdGl2ZVwiOiovXHJcbiAgICAgICAgeFt0aGlzLmN1cnJlbnRGb2N1c10uY2xhc3NMaXN0LmFkZChcInRybi1hdXRvLWNvbXBsZXRlLWFjdGl2ZVwiKTtcclxuICAgIH1cclxuXHJcbiAgICByZW1vdmVBY3RpdmUoeCkge1xyXG4gICAgICAgIC8qYSBmdW5jdGlvbiB0byByZW1vdmUgdGhlIFwiYWN0aXZlXCIgY2xhc3MgZnJvbSBhbGwgYXV0b2NvbXBsZXRlIGl0ZW1zOiovXHJcbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCB4Lmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgIHhbaV0uY2xhc3NMaXN0LnJlbW92ZShcInRybi1hdXRvLWNvbXBsZXRlLWFjdGl2ZVwiKTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcblxyXG4gICAgY2xvc2VBbGxMaXN0cyhlbGVtZW50KSB7XHJcbiAgICAgICAgY29uc29sZS5sb2coXCJjbG9zZSBhbGwgbGlzdHNcIik7XHJcbiAgICAgICAgLypjbG9zZSBhbGwgYXV0b2NvbXBsZXRlIGxpc3RzIGluIHRoZSBkb2N1bWVudCxcclxuICAgICAgICAgZXhjZXB0IHRoZSBvbmUgcGFzc2VkIGFzIGFuIGFyZ3VtZW50OiovXHJcbiAgICAgICAgbGV0IHggPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKFwidHJuLWF1dG8tY29tcGxldGUtaXRlbXNcIik7XHJcbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCB4Lmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgIGlmIChlbGVtZW50ICE9PSB4W2ldICYmIGVsZW1lbnQgIT09IHRoaXMubmFtZUlucHV0KSB7XHJcbiAgICAgICAgICAgICAgICB4W2ldLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoeFtpXSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICB9XHJcbn1cclxuXHJcbi8vIEZpcnN0LCBjaGVja3MgaWYgaXQgaXNuJ3QgaW1wbGVtZW50ZWQgeWV0LlxyXG5pZiAoIVN0cmluZy5wcm90b3R5cGUuZm9ybWF0KSB7XHJcbiAgICBTdHJpbmcucHJvdG90eXBlLmZvcm1hdCA9IGZ1bmN0aW9uKCkge1xyXG4gICAgICAgIGNvbnN0IGFyZ3MgPSBhcmd1bWVudHM7XHJcbiAgICAgICAgcmV0dXJuIHRoaXMucmVwbGFjZSgveyhcXGQrKX0vZywgZnVuY3Rpb24obWF0Y2gsIG51bWJlcikge1xyXG4gICAgICAgICAgICByZXR1cm4gdHlwZW9mIGFyZ3NbbnVtYmVyXSAhPT0gJ3VuZGVmaW5lZCdcclxuICAgICAgICAgICAgICAgID8gYXJnc1tudW1iZXJdXHJcbiAgICAgICAgICAgICAgICA6IG1hdGNoXHJcbiAgICAgICAgICAgICAgICA7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9O1xyXG59Il0sInNvdXJjZVJvb3QiOiIifQ==