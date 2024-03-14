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
/******/ 	return __webpack_require__(__webpack_require__.s = 23);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/match-details.js":
/*!*********************************!*\
  !*** ./src/js/match-details.js ***!
  \*********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./tournamatch.js */ "./src/js/tournamatch.js");
/**
 * Handles the click events for the match details page.
 *
 * @link       https://www.tournamatch.com
 * @since      3.11.0
 *
 * @package    Tournamatch
 *
 */


(function ($) {
  'use strict';

  window.addEventListener('load', function () {
    var options = trn_match_details_options;
    $.event('match').addEventListener('deleted', function () {
      window.location.href = options.redirect_link;
    });
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

/***/ 23:
/*!***************************************!*\
  !*** multi ./src/js/match-details.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\match-details.js */"./src/js/match-details.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL21hdGNoLWRldGFpbHMuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3RvdXJuYW1hdGNoLmpzIl0sIm5hbWVzIjpbIiQiLCJ3aW5kb3ciLCJhZGRFdmVudExpc3RlbmVyIiwib3B0aW9ucyIsInRybl9tYXRjaF9kZXRhaWxzX29wdGlvbnMiLCJldmVudCIsImxvY2F0aW9uIiwiaHJlZiIsInJlZGlyZWN0X2xpbmsiLCJ0cm4iLCJUb3VybmFtYXRjaCIsImV2ZW50cyIsIm9iamVjdCIsInByZWZpeCIsInN0ciIsInByb3AiLCJoYXNPd25Qcm9wZXJ0eSIsImsiLCJ2IiwicHVzaCIsInBhcmFtIiwiZW5jb2RlVVJJQ29tcG9uZW50Iiwiam9pbiIsImV2ZW50TmFtZSIsIkV2ZW50VGFyZ2V0IiwiaW5wdXQiLCJkYXRhQ2FsbGJhY2siLCJUb3VybmFtYXRjaF9BdXRvY29tcGxldGUiLCJzIiwiY2hhckF0IiwidG9VcHBlckNhc2UiLCJzbGljZSIsIm51bWJlciIsInJlbWFpbmRlciIsImVsZW1lbnQiLCJ0YWJzIiwiZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSIsInBhbmVzIiwiZG9jdW1lbnQiLCJjbGVhckFjdGl2ZSIsIkFycmF5IiwicHJvdG90eXBlIiwiZm9yRWFjaCIsImNhbGwiLCJ0YWIiLCJjbGFzc0xpc3QiLCJyZW1vdmUiLCJhcmlhU2VsZWN0ZWQiLCJwYW5lIiwic2V0QWN0aXZlIiwidGFyZ2V0SWQiLCJ0YXJnZXRUYWIiLCJxdWVyeVNlbGVjdG9yIiwidGFyZ2V0UGFuZUlkIiwiZGF0YXNldCIsInRhcmdldCIsImFkZCIsImdldEVsZW1lbnRCeUlkIiwidGFiQ2xpY2siLCJjdXJyZW50VGFyZ2V0IiwicHJldmVudERlZmF1bHQiLCJoYXNoIiwic3Vic3RyIiwibGVuZ3RoIiwidHJuX29ial9pbnN0YW5jZSIsInRhYlZpZXdzIiwiZnJvbSIsImRyb3Bkb3ducyIsImhhbmRsZURyb3Bkb3duQ2xvc2UiLCJkcm9wZG93biIsIm5leHRFbGVtZW50U2libGluZyIsInJlbW92ZUV2ZW50TGlzdGVuZXIiLCJlIiwic3RvcFByb3BhZ2F0aW9uIiwibmFtZUlucHV0IiwiYSIsImIiLCJpIiwidmFsIiwidmFsdWUiLCJwYXJlbnQiLCJwYXJlbnROb2RlIiwidGhlbiIsImRhdGEiLCJjb25zb2xlIiwibG9nIiwiY2xvc2VBbGxMaXN0cyIsImN1cnJlbnRGb2N1cyIsImNyZWF0ZUVsZW1lbnQiLCJzZXRBdHRyaWJ1dGUiLCJpZCIsImFwcGVuZENoaWxkIiwidGV4dCIsImlubmVySFRNTCIsInNlbGVjdGVkSWQiLCJkaXNwYXRjaEV2ZW50IiwiRXZlbnQiLCJ4IiwiZ2V0RWxlbWVudHNCeVRhZ05hbWUiLCJrZXlDb2RlIiwiYWRkQWN0aXZlIiwiY2xpY2siLCJyZW1vdmVBY3RpdmUiLCJyZW1vdmVDaGlsZCIsIlN0cmluZyIsImZvcm1hdCIsImFyZ3MiLCJhcmd1bWVudHMiLCJyZXBsYWNlIiwibWF0Y2giXSwibWFwcGluZ3MiOiI7UUFBQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTs7O1FBR0E7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLDBDQUEwQyxnQ0FBZ0M7UUFDMUU7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSx3REFBd0Qsa0JBQWtCO1FBQzFFO1FBQ0EsaURBQWlELGNBQWM7UUFDL0Q7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBLHlDQUF5QyxpQ0FBaUM7UUFDMUUsZ0hBQWdILG1CQUFtQixFQUFFO1FBQ3JJO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0EsMkJBQTJCLDBCQUEwQixFQUFFO1FBQ3ZELGlDQUFpQyxlQUFlO1FBQ2hEO1FB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QmtELGFBQU8sQ0FBQ0MsR0FBUixDQUFZLGlCQUFaO0FBQ0E7QUFDUjs7QUFDUSxVQUFJWSxDQUFDLEdBQUczRCxRQUFRLENBQUNGLHNCQUFULENBQWdDLHlCQUFoQyxDQUFSOztBQUNBLFdBQUssSUFBSXlDLENBQUMsR0FBRyxDQUFiLEVBQWdCQSxDQUFDLEdBQUdvQixDQUFDLENBQUNsQyxNQUF0QixFQUE4QmMsQ0FBQyxFQUEvQixFQUFtQztBQUMvQixZQUFJM0MsT0FBTyxLQUFLK0QsQ0FBQyxDQUFDcEIsQ0FBRCxDQUFiLElBQW9CM0MsT0FBTyxLQUFLLEtBQUt3QyxTQUF6QyxFQUFvRDtBQUNoRHVCLFdBQUMsQ0FBQ3BCLENBQUQsQ0FBRCxDQUFLSSxVQUFMLENBQWdCc0IsV0FBaEIsQ0FBNEJOLENBQUMsQ0FBQ3BCLENBQUQsQ0FBN0I7QUFDSDtBQUNKO0FBQ0o7Ozs7S0FHTDs7O0FBQ0EsSUFBSSxDQUFDMkIsTUFBTSxDQUFDL0QsU0FBUCxDQUFpQmdFLE1BQXRCLEVBQThCO0FBQzFCRCxRQUFNLENBQUMvRCxTQUFQLENBQWlCZ0UsTUFBakIsR0FBMEIsWUFBVztBQUNqQyxRQUFNQyxJQUFJLEdBQUdDLFNBQWI7QUFDQSxXQUFPLEtBQUtDLE9BQUwsQ0FBYSxVQUFiLEVBQXlCLFVBQVNDLEtBQVQsRUFBZ0I3RSxNQUFoQixFQUF3QjtBQUNwRCxhQUFPLE9BQU8wRSxJQUFJLENBQUMxRSxNQUFELENBQVgsS0FBd0IsV0FBeEIsR0FDRDBFLElBQUksQ0FBQzFFLE1BQUQsQ0FESCxHQUVENkUsS0FGTjtBQUlILEtBTE0sQ0FBUDtBQU1ILEdBUkQ7QUFTSCxDIiwiZmlsZSI6Im1hdGNoLWRldGFpbHMuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMjMpO1xuIiwiLyoqXHJcbiAqIEhhbmRsZXMgdGhlIGNsaWNrIGV2ZW50cyBmb3IgdGhlIG1hdGNoIGRldGFpbHMgcGFnZS5cclxuICpcclxuICogQGxpbmsgICAgICAgaHR0cHM6Ly93d3cudG91cm5hbWF0Y2guY29tXHJcbiAqIEBzaW5jZSAgICAgIDMuMTEuMFxyXG4gKlxyXG4gKiBAcGFja2FnZSAgICBUb3VybmFtYXRjaFxyXG4gKlxyXG4gKi9cclxuaW1wb3J0IHsgdHJuIH0gZnJvbSAnLi90b3VybmFtYXRjaC5qcyc7XHJcblxyXG4oZnVuY3Rpb24gKCQpIHtcclxuICAgICd1c2Ugc3RyaWN0JztcclxuXHJcbiAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICBsZXQgb3B0aW9ucyA9IHRybl9tYXRjaF9kZXRhaWxzX29wdGlvbnM7XHJcblxyXG4gICAgICAgICQuZXZlbnQoJ21hdGNoJykuYWRkRXZlbnRMaXN0ZW5lcignZGVsZXRlZCcsIGZ1bmN0aW9uKCkge1xyXG4gICAgICAgICAgICB3aW5kb3cubG9jYXRpb24uaHJlZiA9IG9wdGlvbnMucmVkaXJlY3RfbGluaztcclxuICAgICAgICB9KTtcclxuXHJcbiAgICB9LCBmYWxzZSk7XHJcbn0pKHRybik7IiwiJ3VzZSBzdHJpY3QnO1xyXG5jbGFzcyBUb3VybmFtYXRjaCB7XHJcblxyXG4gICAgY29uc3RydWN0b3IoKSB7XHJcbiAgICAgICAgdGhpcy5ldmVudHMgPSB7fTtcclxuICAgIH1cclxuXHJcbiAgICBwYXJhbShvYmplY3QsIHByZWZpeCkge1xyXG4gICAgICAgIGxldCBzdHIgPSBbXTtcclxuICAgICAgICBmb3IgKGxldCBwcm9wIGluIG9iamVjdCkge1xyXG4gICAgICAgICAgICBpZiAob2JqZWN0Lmhhc093blByb3BlcnR5KHByb3ApKSB7XHJcbiAgICAgICAgICAgICAgICBsZXQgayA9IHByZWZpeCA/IHByZWZpeCArIFwiW1wiICsgcHJvcCArIFwiXVwiIDogcHJvcDtcclxuICAgICAgICAgICAgICAgIGxldCB2ID0gb2JqZWN0W3Byb3BdO1xyXG4gICAgICAgICAgICAgICAgc3RyLnB1c2goKHYgIT09IG51bGwgJiYgdHlwZW9mIHYgPT09IFwib2JqZWN0XCIpID8gdGhpcy5wYXJhbSh2LCBrKSA6IGVuY29kZVVSSUNvbXBvbmVudChrKSArIFwiPVwiICsgZW5jb2RlVVJJQ29tcG9uZW50KHYpKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gc3RyLmpvaW4oXCImXCIpO1xyXG4gICAgfVxyXG5cclxuICAgIGV2ZW50KGV2ZW50TmFtZSkge1xyXG4gICAgICAgIGlmICghKGV2ZW50TmFtZSBpbiB0aGlzLmV2ZW50cykpIHtcclxuICAgICAgICAgICAgdGhpcy5ldmVudHNbZXZlbnROYW1lXSA9IG5ldyBFdmVudFRhcmdldChldmVudE5hbWUpO1xyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gdGhpcy5ldmVudHNbZXZlbnROYW1lXTtcclxuICAgIH1cclxuXHJcbiAgICBhdXRvY29tcGxldGUoaW5wdXQsIGRhdGFDYWxsYmFjaykge1xyXG4gICAgICAgIG5ldyBUb3VybmFtYXRjaF9BdXRvY29tcGxldGUoaW5wdXQsIGRhdGFDYWxsYmFjayk7XHJcbiAgICB9XHJcblxyXG4gICAgdWNmaXJzdChzKSB7XHJcbiAgICAgICAgaWYgKHR5cGVvZiBzICE9PSAnc3RyaW5nJykgcmV0dXJuICcnO1xyXG4gICAgICAgIHJldHVybiBzLmNoYXJBdCgwKS50b1VwcGVyQ2FzZSgpICsgcy5zbGljZSgxKTtcclxuICAgIH1cclxuXHJcbiAgICBvcmRpbmFsX3N1ZmZpeChudW1iZXIpIHtcclxuICAgICAgICBjb25zdCByZW1haW5kZXIgPSBudW1iZXIgJSAxMDA7XHJcblxyXG4gICAgICAgIGlmICgocmVtYWluZGVyIDwgMTEpIHx8IChyZW1haW5kZXIgPiAxMykpIHtcclxuICAgICAgICAgICAgc3dpdGNoIChyZW1haW5kZXIgJSAxMCkge1xyXG4gICAgICAgICAgICAgICAgY2FzZSAxOiByZXR1cm4gJ3N0JztcclxuICAgICAgICAgICAgICAgIGNhc2UgMjogcmV0dXJuICduZCc7XHJcbiAgICAgICAgICAgICAgICBjYXNlIDM6IHJldHVybiAncmQnO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiAndGgnO1xyXG4gICAgfVxyXG5cclxuICAgIHRhYnMoZWxlbWVudCkge1xyXG4gICAgICAgIGNvbnN0IHRhYnMgPSBlbGVtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1uYXYtbGluaycpO1xyXG4gICAgICAgIGNvbnN0IHBhbmVzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLXRhYi1wYW5lJyk7XHJcbiAgICAgICAgY29uc3QgY2xlYXJBY3RpdmUgPSAoKSA9PiB7XHJcbiAgICAgICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwodGFicywgKHRhYikgPT4ge1xyXG4gICAgICAgICAgICAgICAgdGFiLmNsYXNzTGlzdC5yZW1vdmUoJ3Rybi1uYXYtYWN0aXZlJyk7XHJcbiAgICAgICAgICAgICAgICB0YWIuYXJpYVNlbGVjdGVkID0gZmFsc2U7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHBhbmVzLCBwYW5lID0+IHBhbmUuY2xhc3NMaXN0LnJlbW92ZSgndHJuLXRhYi1hY3RpdmUnKSk7XHJcbiAgICAgICAgfTtcclxuICAgICAgICBjb25zdCBzZXRBY3RpdmUgPSAodGFyZ2V0SWQpID0+IHtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0VGFiID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignYVtocmVmPVwiIycgKyB0YXJnZXRJZCArICdcIl0udHJuLW5hdi1saW5rJyk7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFBhbmVJZCA9IHRhcmdldFRhYiAmJiB0YXJnZXRUYWIuZGF0YXNldCAmJiB0YXJnZXRUYWIuZGF0YXNldC50YXJnZXQgfHwgZmFsc2U7XHJcblxyXG4gICAgICAgICAgICBpZiAodGFyZ2V0UGFuZUlkKSB7XHJcbiAgICAgICAgICAgICAgICBjbGVhckFjdGl2ZSgpO1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0VGFiLmNsYXNzTGlzdC5hZGQoJ3Rybi1uYXYtYWN0aXZlJyk7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRUYWIuYXJpYVNlbGVjdGVkID0gdHJ1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0YXJnZXRQYW5lSWQpLmNsYXNzTGlzdC5hZGQoJ3Rybi10YWItYWN0aXZlJyk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9O1xyXG4gICAgICAgIGNvbnN0IHRhYkNsaWNrID0gKGV2ZW50KSA9PiB7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFRhYiA9IGV2ZW50LmN1cnJlbnRUYXJnZXQ7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFBhbmVJZCA9IHRhcmdldFRhYiAmJiB0YXJnZXRUYWIuZGF0YXNldCAmJiB0YXJnZXRUYWIuZGF0YXNldC50YXJnZXQgfHwgZmFsc2U7XHJcblxyXG4gICAgICAgICAgICBpZiAodGFyZ2V0UGFuZUlkKSB7XHJcbiAgICAgICAgICAgICAgICBzZXRBY3RpdmUodGFyZ2V0UGFuZUlkKTtcclxuICAgICAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9O1xyXG5cclxuICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHRhYnMsICh0YWIpID0+IHtcclxuICAgICAgICAgICAgdGFiLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGFiQ2xpY2spO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICBpZiAobG9jYXRpb24uaGFzaCkge1xyXG4gICAgICAgICAgICBzZXRBY3RpdmUobG9jYXRpb24uaGFzaC5zdWJzdHIoMSkpO1xyXG4gICAgICAgIH0gZWxzZSBpZiAodGFicy5sZW5ndGggPiAwKSB7XHJcbiAgICAgICAgICAgIHNldEFjdGl2ZSh0YWJzWzBdLmRhdGFzZXQudGFyZ2V0KTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcblxyXG59XHJcblxyXG4vL3Rybi5pbml0aWFsaXplKCk7XHJcbmlmICghd2luZG93LnRybl9vYmpfaW5zdGFuY2UpIHtcclxuICAgIHdpbmRvdy50cm5fb2JqX2luc3RhbmNlID0gbmV3IFRvdXJuYW1hdGNoKCk7XHJcblxyXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCBmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgIGNvbnN0IHRhYlZpZXdzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLW5hdicpO1xyXG5cclxuICAgICAgICBBcnJheS5mcm9tKHRhYlZpZXdzKS5mb3JFYWNoKCh0YWIpID0+IHtcclxuICAgICAgICAgICAgdHJuLnRhYnModGFiKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgY29uc3QgZHJvcGRvd25zID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLWRyb3Bkb3duLXRvZ2dsZScpO1xyXG4gICAgICAgIGNvbnN0IGhhbmRsZURyb3Bkb3duQ2xvc2UgPSAoKSA9PiB7XHJcbiAgICAgICAgICAgIEFycmF5LmZyb20oZHJvcGRvd25zKS5mb3JFYWNoKChkcm9wZG93bikgPT4ge1xyXG4gICAgICAgICAgICAgICAgZHJvcGRvd24ubmV4dEVsZW1lbnRTaWJsaW5nLmNsYXNzTGlzdC5yZW1vdmUoJ3Rybi1zaG93Jyk7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICBkb2N1bWVudC5yZW1vdmVFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgaGFuZGxlRHJvcGRvd25DbG9zZSwgZmFsc2UpO1xyXG4gICAgICAgIH07XHJcblxyXG4gICAgICAgIEFycmF5LmZyb20oZHJvcGRvd25zKS5mb3JFYWNoKChkcm9wZG93bikgPT4ge1xyXG4gICAgICAgICAgICBkcm9wZG93bi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uKGUpIHtcclxuICAgICAgICAgICAgICAgIGUuc3RvcFByb3BhZ2F0aW9uKCk7XHJcbiAgICAgICAgICAgICAgICB0aGlzLm5leHRFbGVtZW50U2libGluZy5jbGFzc0xpc3QuYWRkKCd0cm4tc2hvdycpO1xyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGhhbmRsZURyb3Bkb3duQ2xvc2UsIGZhbHNlKTtcclxuICAgICAgICAgICAgfSwgZmFsc2UpO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgIH0sIGZhbHNlKTtcclxufVxyXG5leHBvcnQgbGV0IHRybiA9IHdpbmRvdy50cm5fb2JqX2luc3RhbmNlO1xyXG5cclxuY2xhc3MgVG91cm5hbWF0Y2hfQXV0b2NvbXBsZXRlIHtcclxuXHJcbiAgICAvLyBjdXJyZW50Rm9jdXM7XHJcbiAgICAvL1xyXG4gICAgLy8gbmFtZUlucHV0O1xyXG4gICAgLy9cclxuICAgIC8vIHNlbGY7XHJcblxyXG4gICAgY29uc3RydWN0b3IoaW5wdXQsIGRhdGFDYWxsYmFjaykge1xyXG4gICAgICAgIC8vIHRoaXMuc2VsZiA9IHRoaXM7XHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQgPSBpbnB1dDtcclxuXHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcihcImlucHV0XCIsICgpID0+IHtcclxuICAgICAgICAgICAgbGV0IGEsIGIsIGksIHZhbCA9IHRoaXMubmFtZUlucHV0LnZhbHVlOy8vdGhpcy52YWx1ZTtcclxuICAgICAgICAgICAgbGV0IHBhcmVudCA9IHRoaXMubmFtZUlucHV0LnBhcmVudE5vZGU7Ly90aGlzLnBhcmVudE5vZGU7XHJcblxyXG4gICAgICAgICAgICAvLyBsZXQgcCA9IG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpID0+IHtcclxuICAgICAgICAgICAgLy8gICAgIC8qIG5lZWQgdG8gcXVlcnkgc2VydmVyIGZvciBuYW1lcyBoZXJlLiAqL1xyXG4gICAgICAgICAgICAvLyAgICAgbGV0IHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLm9wZW4oJ0dFVCcsIG9wdGlvbnMuYXBpX3VybCArICdwbGF5ZXJzLz9zZWFyY2g9JyArIHZhbCArICcmcGVyX3BhZ2U9NScpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMCkge1xyXG4gICAgICAgICAgICAvLyAgICAgICAgICAgICAvLyByZXNvbHZlKEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKS5tYXAoKHBsYXllcikgPT4ge3JldHVybiB7ICd2YWx1ZSc6IHBsYXllci5pZCwgJ3RleHQnOiBwbGF5ZXIubmFtZSB9O30pKTtcclxuICAgICAgICAgICAgLy8gICAgICAgICAgICAgcmVzb2x2ZShKU09OLnBhcnNlKHhoci5yZXNwb25zZSkubWFwKChwbGF5ZXIpID0+IHtyZXR1cm4gcGxheWVyLm5hbWU7fSkpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgICAgIHJlamVjdCgpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gICAgIH07XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIuc2VuZCgpO1xyXG4gICAgICAgICAgICAvLyB9KTtcclxuICAgICAgICAgICAgZGF0YUNhbGxiYWNrKHZhbCkudGhlbigoZGF0YSkgPT4gey8vcC50aGVuKChkYXRhKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhkYXRhKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmNsb3NlIGFueSBhbHJlYWR5IG9wZW4gbGlzdHMgb2YgYXV0by1jb21wbGV0ZWQgdmFsdWVzKi9cclxuICAgICAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cygpO1xyXG4gICAgICAgICAgICAgICAgaWYgKCF2YWwpIHsgcmV0dXJuIGZhbHNlO31cclxuICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudEZvY3VzID0gLTE7XHJcblxyXG4gICAgICAgICAgICAgICAgLypjcmVhdGUgYSBESVYgZWxlbWVudCB0aGF0IHdpbGwgY29udGFpbiB0aGUgaXRlbXMgKHZhbHVlcyk6Ki9cclxuICAgICAgICAgICAgICAgIGEgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiRElWXCIpO1xyXG4gICAgICAgICAgICAgICAgYS5zZXRBdHRyaWJ1dGUoXCJpZFwiLCB0aGlzLm5hbWVJbnB1dC5pZCArIFwiLWF1dG8tY29tcGxldGUtbGlzdFwiKTtcclxuICAgICAgICAgICAgICAgIGEuc2V0QXR0cmlidXRlKFwiY2xhc3NcIiwgXCJ0cm4tYXV0by1jb21wbGV0ZS1pdGVtc1wiKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmFwcGVuZCB0aGUgRElWIGVsZW1lbnQgYXMgYSBjaGlsZCBvZiB0aGUgYXV0by1jb21wbGV0ZSBjb250YWluZXI6Ki9cclxuICAgICAgICAgICAgICAgIHBhcmVudC5hcHBlbmRDaGlsZChhKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmZvciBlYWNoIGl0ZW0gaW4gdGhlIGFycmF5Li4uKi9cclxuICAgICAgICAgICAgICAgIGZvciAoaSA9IDA7IGkgPCBkYXRhLmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IHRleHQsIHZhbHVlO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAvKiBXaGljaCBmb3JtYXQgZGlkIHRoZXkgZ2l2ZSB1cy4gKi9cclxuICAgICAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGRhdGFbaV0gPT09ICdvYmplY3QnKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHRleHQgPSBkYXRhW2ldWyd0ZXh0J107XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlID0gZGF0YVtpXVsndmFsdWUnXTtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB0ZXh0ID0gZGF0YVtpXTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSBkYXRhW2ldO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICAgICAgLypjaGVjayBpZiB0aGUgaXRlbSBzdGFydHMgd2l0aCB0aGUgc2FtZSBsZXR0ZXJzIGFzIHRoZSB0ZXh0IGZpZWxkIHZhbHVlOiovXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHRleHQuc3Vic3RyKDAsIHZhbC5sZW5ndGgpLnRvVXBwZXJDYXNlKCkgPT09IHZhbC50b1VwcGVyQ2FzZSgpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qY3JlYXRlIGEgRElWIGVsZW1lbnQgZm9yIGVhY2ggbWF0Y2hpbmcgZWxlbWVudDoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcIkRJVlwiKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgLyptYWtlIHRoZSBtYXRjaGluZyBsZXR0ZXJzIGJvbGQ6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgPSBcIjxzdHJvbmc+XCIgKyB0ZXh0LnN1YnN0cigwLCB2YWwubGVuZ3RoKSArIFwiPC9zdHJvbmc+XCI7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuaW5uZXJIVE1MICs9IHRleHQuc3Vic3RyKHZhbC5sZW5ndGgpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgLyppbnNlcnQgYSBpbnB1dCBmaWVsZCB0aGF0IHdpbGwgaG9sZCB0aGUgY3VycmVudCBhcnJheSBpdGVtJ3MgdmFsdWU6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgKz0gXCI8aW5wdXQgdHlwZT0naGlkZGVuJyB2YWx1ZT0nXCIgKyB2YWx1ZSArIFwiJz5cIjtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuZGF0YXNldC52YWx1ZSA9IHZhbHVlO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmRhdGFzZXQudGV4dCA9IHRleHQ7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiB3aGVuIHNvbWVvbmUgY2xpY2tzIG9uIHRoZSBpdGVtIHZhbHVlIChESVYgZWxlbWVudCk6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKGUpID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKGBpdGVtIGNsaWNrZWQgd2l0aCB2YWx1ZSAke2UuY3VycmVudFRhcmdldC5kYXRhc2V0LnZhbHVlfWApO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8qIGluc2VydCB0aGUgdmFsdWUgZm9yIHRoZSBhdXRvY29tcGxldGUgdGV4dCBmaWVsZDogKi9cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LnZhbHVlID0gZS5jdXJyZW50VGFyZ2V0LmRhdGFzZXQudGV4dDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LmRhdGFzZXQuc2VsZWN0ZWRJZCA9IGUuY3VycmVudFRhcmdldC5kYXRhc2V0LnZhbHVlO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8qIGNsb3NlIHRoZSBsaXN0IG9mIGF1dG9jb21wbGV0ZWQgdmFsdWVzLCAob3IgYW55IG90aGVyIG9wZW4gbGlzdHMgb2YgYXV0b2NvbXBsZXRlZCB2YWx1ZXM6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cygpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LmRpc3BhdGNoRXZlbnQobmV3IEV2ZW50KCdjaGFuZ2UnKSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBhLmFwcGVuZENoaWxkKGIpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHByZXNzZXMgYSBrZXkgb24gdGhlIGtleWJvYXJkOiovXHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcihcImtleWRvd25cIiwgKGUpID0+IHtcclxuICAgICAgICAgICAgbGV0IHggPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0aGlzLm5hbWVJbnB1dC5pZCArIFwiLWF1dG8tY29tcGxldGUtbGlzdFwiKTtcclxuICAgICAgICAgICAgaWYgKHgpIHggPSB4LmdldEVsZW1lbnRzQnlUYWdOYW1lKFwiZGl2XCIpO1xyXG4gICAgICAgICAgICBpZiAoZS5rZXlDb2RlID09PSA0MCkge1xyXG4gICAgICAgICAgICAgICAgLypJZiB0aGUgYXJyb3cgRE9XTiBrZXkgaXMgcHJlc3NlZCxcclxuICAgICAgICAgICAgICAgICBpbmNyZWFzZSB0aGUgY3VycmVudEZvY3VzIHZhcmlhYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmN1cnJlbnRGb2N1cysrO1xyXG4gICAgICAgICAgICAgICAgLyphbmQgYW5kIG1ha2UgdGhlIGN1cnJlbnQgaXRlbSBtb3JlIHZpc2libGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuYWRkQWN0aXZlKHgpO1xyXG4gICAgICAgICAgICB9IGVsc2UgaWYgKGUua2V5Q29kZSA9PT0gMzgpIHsgLy91cFxyXG4gICAgICAgICAgICAgICAgLypJZiB0aGUgYXJyb3cgVVAga2V5IGlzIHByZXNzZWQsXHJcbiAgICAgICAgICAgICAgICAgZGVjcmVhc2UgdGhlIGN1cnJlbnRGb2N1cyB2YXJpYWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50Rm9jdXMtLTtcclxuICAgICAgICAgICAgICAgIC8qYW5kIGFuZCBtYWtlIHRoZSBjdXJyZW50IGl0ZW0gbW9yZSB2aXNpYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmFkZEFjdGl2ZSh4KTtcclxuICAgICAgICAgICAgfSBlbHNlIGlmIChlLmtleUNvZGUgPT09IDEzKSB7XHJcbiAgICAgICAgICAgICAgICAvKklmIHRoZSBFTlRFUiBrZXkgaXMgcHJlc3NlZCwgcHJldmVudCB0aGUgZm9ybSBmcm9tIGJlaW5nIHN1Ym1pdHRlZCwqL1xyXG4gICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzID4gLTEpIHtcclxuICAgICAgICAgICAgICAgICAgICAvKmFuZCBzaW11bGF0ZSBhIGNsaWNrIG9uIHRoZSBcImFjdGl2ZVwiIGl0ZW06Ki9cclxuICAgICAgICAgICAgICAgICAgICBpZiAoeCkgeFt0aGlzLmN1cnJlbnRGb2N1c10uY2xpY2soKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiB3aGVuIHNvbWVvbmUgY2xpY2tzIGluIHRoZSBkb2N1bWVudDoqL1xyXG4gICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICB0aGlzLmNsb3NlQWxsTGlzdHMoZS50YXJnZXQpO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG5cclxuICAgIGFkZEFjdGl2ZSh4KSB7XHJcbiAgICAgICAgLyphIGZ1bmN0aW9uIHRvIGNsYXNzaWZ5IGFuIGl0ZW0gYXMgXCJhY3RpdmVcIjoqL1xyXG4gICAgICAgIGlmICgheCkgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgIC8qc3RhcnQgYnkgcmVtb3ZpbmcgdGhlIFwiYWN0aXZlXCIgY2xhc3Mgb24gYWxsIGl0ZW1zOiovXHJcbiAgICAgICAgdGhpcy5yZW1vdmVBY3RpdmUoeCk7XHJcbiAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzID49IHgubGVuZ3RoKSB0aGlzLmN1cnJlbnRGb2N1cyA9IDA7XHJcbiAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzIDwgMCkgdGhpcy5jdXJyZW50Rm9jdXMgPSAoeC5sZW5ndGggLSAxKTtcclxuICAgICAgICAvKmFkZCBjbGFzcyBcImF1dG9jb21wbGV0ZS1hY3RpdmVcIjoqL1xyXG4gICAgICAgIHhbdGhpcy5jdXJyZW50Rm9jdXNdLmNsYXNzTGlzdC5hZGQoXCJ0cm4tYXV0by1jb21wbGV0ZS1hY3RpdmVcIik7XHJcbiAgICB9XHJcblxyXG4gICAgcmVtb3ZlQWN0aXZlKHgpIHtcclxuICAgICAgICAvKmEgZnVuY3Rpb24gdG8gcmVtb3ZlIHRoZSBcImFjdGl2ZVwiIGNsYXNzIGZyb20gYWxsIGF1dG9jb21wbGV0ZSBpdGVtczoqL1xyXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgeC5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICB4W2ldLmNsYXNzTGlzdC5yZW1vdmUoXCJ0cm4tYXV0by1jb21wbGV0ZS1hY3RpdmVcIik7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIGNsb3NlQWxsTGlzdHMoZWxlbWVudCkge1xyXG4gICAgICAgIGNvbnNvbGUubG9nKFwiY2xvc2UgYWxsIGxpc3RzXCIpO1xyXG4gICAgICAgIC8qY2xvc2UgYWxsIGF1dG9jb21wbGV0ZSBsaXN0cyBpbiB0aGUgZG9jdW1lbnQsXHJcbiAgICAgICAgIGV4Y2VwdCB0aGUgb25lIHBhc3NlZCBhcyBhbiBhcmd1bWVudDoqL1xyXG4gICAgICAgIGxldCB4ID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShcInRybi1hdXRvLWNvbXBsZXRlLWl0ZW1zXCIpO1xyXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgeC5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICBpZiAoZWxlbWVudCAhPT0geFtpXSAmJiBlbGVtZW50ICE9PSB0aGlzLm5hbWVJbnB1dCkge1xyXG4gICAgICAgICAgICAgICAgeFtpXS5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKHhbaV0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG59XHJcblxyXG4vLyBGaXJzdCwgY2hlY2tzIGlmIGl0IGlzbid0IGltcGxlbWVudGVkIHlldC5cclxuaWYgKCFTdHJpbmcucHJvdG90eXBlLmZvcm1hdCkge1xyXG4gICAgU3RyaW5nLnByb3RvdHlwZS5mb3JtYXQgPSBmdW5jdGlvbigpIHtcclxuICAgICAgICBjb25zdCBhcmdzID0gYXJndW1lbnRzO1xyXG4gICAgICAgIHJldHVybiB0aGlzLnJlcGxhY2UoL3soXFxkKyl9L2csIGZ1bmN0aW9uKG1hdGNoLCBudW1iZXIpIHtcclxuICAgICAgICAgICAgcmV0dXJuIHR5cGVvZiBhcmdzW251bWJlcl0gIT09ICd1bmRlZmluZWQnXHJcbiAgICAgICAgICAgICAgICA/IGFyZ3NbbnVtYmVyXVxyXG4gICAgICAgICAgICAgICAgOiBtYXRjaFxyXG4gICAgICAgICAgICAgICAgO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfTtcclxufSJdLCJzb3VyY2VSb290IjoiIn0=