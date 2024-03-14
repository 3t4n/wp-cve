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
/******/ 	return __webpack_require__(__webpack_require__.s = 28);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/players.js":
/*!***************************!*\
  !*** ./src/js/players.js ***!
  \***************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./tournamatch.js */ "./src/js/tournamatch.js");
/**
 * Player list page.
 *
 * @link       https://www.tournamatch.com
 * @since      3.21.0
 *
 * @package    Tournamatch
 *
 */


(function (jQuery, $) {
  'use strict';

  var options = trn_table_options;
  window.addEventListener('load', function () {
    var columnDefs = [{
      targets: 0,
      name: 'display_name',
      className: 'trn-players-table-name',
      render: function render(data, type, row) {
        return "<a href=\"".concat(row.link, "\">").concat(row.name, "</a>");
      }
    }, {
      targets: 1,
      name: 'joined_date',
      className: 'trn-players-table-joined',
      render: function render(data, type, row) {
        return row.joined_date.rendered;
      }
    }, {
      targets: 2,
      name: 'location',
      className: 'trn-players-table-location',
      render: function render(data, type, row) {
        return row.location;
      }
    }, {
      targets: 3,
      name: 'teams',
      className: 'trn-players-table-teams',
      render: function render(data, type, row) {
        return row.teams;
      }
    }];

    if (options.user_capability) {
      columnDefs.push({
        targets: 4,
        name: 'actions',
        className: 'trn-players-table-admin',
        render: function render(data, type, row) {
          return "<a href=\"".concat(row.link, "/edit\" title=\"").concat(options.language.edit_player, "\"><i class=\"fa fa-edit\"></i></a>");
        },
        orderable: false
      });
    }

    jQuery('#trn_players_list_table').on('xhr.dt', function (e, settings, json, xhr) {
      json.data = JSON.parse(JSON.stringify(json));
      json.recordsTotal = xhr.getResponseHeader('X-WP-Total');
      json.recordsFiltered = xhr.getResponseHeader('TRN-Filtered');
      json.length = xhr.getResponseHeader('X-WP-TotalPages');
      json.draw = xhr.getResponseHeader('TRN-Draw');
    }).DataTable({
      processing: true,
      serverSide: true,
      lengthMenu: [[25, 50, 100, -1], [25, 50, 100, 'All']],
      language: options['table_language'],
      autoWidth: false,
      ajax: {
        url: "".concat(options.api_url, "players/?_wpnonce=").concat(options.rest_nonce),
        type: 'GET',
        data: function data(_data) {
          console.log(_data);
          var sent = {
            draw: _data.draw,
            page: Math.floor(_data.start / _data.length),
            per_page: _data.length,
            search: _data.search.value,
            orderby: "".concat(_data.columns[_data.order[0].column].name, ".").concat(_data.order[0].dir)
          };
          console.log(sent);
          return sent;
        }
      },
      order: [[0, 'asc']],
      columnDefs: columnDefs
    });
  }, false);
})(jQuery, _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__["trn"]);

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

/***/ 28:
/*!*********************************!*\
  !*** multi ./src/js/players.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\players.js */"./src/js/players.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3BsYXllcnMuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3RvdXJuYW1hdGNoLmpzIl0sIm5hbWVzIjpbImpRdWVyeSIsIiQiLCJvcHRpb25zIiwidHJuX3RhYmxlX29wdGlvbnMiLCJ3aW5kb3ciLCJhZGRFdmVudExpc3RlbmVyIiwiY29sdW1uRGVmcyIsInRhcmdldHMiLCJuYW1lIiwiY2xhc3NOYW1lIiwicmVuZGVyIiwiZGF0YSIsInR5cGUiLCJyb3ciLCJsaW5rIiwiam9pbmVkX2RhdGUiLCJyZW5kZXJlZCIsImxvY2F0aW9uIiwidGVhbXMiLCJ1c2VyX2NhcGFiaWxpdHkiLCJwdXNoIiwibGFuZ3VhZ2UiLCJlZGl0X3BsYXllciIsIm9yZGVyYWJsZSIsIm9uIiwiZSIsInNldHRpbmdzIiwianNvbiIsInhociIsIkpTT04iLCJwYXJzZSIsInN0cmluZ2lmeSIsInJlY29yZHNUb3RhbCIsImdldFJlc3BvbnNlSGVhZGVyIiwicmVjb3Jkc0ZpbHRlcmVkIiwibGVuZ3RoIiwiZHJhdyIsIkRhdGFUYWJsZSIsInByb2Nlc3NpbmciLCJzZXJ2ZXJTaWRlIiwibGVuZ3RoTWVudSIsImF1dG9XaWR0aCIsImFqYXgiLCJ1cmwiLCJhcGlfdXJsIiwicmVzdF9ub25jZSIsImNvbnNvbGUiLCJsb2ciLCJzZW50IiwicGFnZSIsIk1hdGgiLCJmbG9vciIsInN0YXJ0IiwicGVyX3BhZ2UiLCJzZWFyY2giLCJ2YWx1ZSIsIm9yZGVyYnkiLCJjb2x1bW5zIiwib3JkZXIiLCJjb2x1bW4iLCJkaXIiLCJ0cm4iLCJUb3VybmFtYXRjaCIsImV2ZW50cyIsIm9iamVjdCIsInByZWZpeCIsInN0ciIsInByb3AiLCJoYXNPd25Qcm9wZXJ0eSIsImsiLCJ2IiwicGFyYW0iLCJlbmNvZGVVUklDb21wb25lbnQiLCJqb2luIiwiZXZlbnROYW1lIiwiRXZlbnRUYXJnZXQiLCJpbnB1dCIsImRhdGFDYWxsYmFjayIsIlRvdXJuYW1hdGNoX0F1dG9jb21wbGV0ZSIsInMiLCJjaGFyQXQiLCJ0b1VwcGVyQ2FzZSIsInNsaWNlIiwibnVtYmVyIiwicmVtYWluZGVyIiwiZWxlbWVudCIsInRhYnMiLCJnZXRFbGVtZW50c0J5Q2xhc3NOYW1lIiwicGFuZXMiLCJkb2N1bWVudCIsImNsZWFyQWN0aXZlIiwiQXJyYXkiLCJwcm90b3R5cGUiLCJmb3JFYWNoIiwiY2FsbCIsInRhYiIsImNsYXNzTGlzdCIsInJlbW92ZSIsImFyaWFTZWxlY3RlZCIsInBhbmUiLCJzZXRBY3RpdmUiLCJ0YXJnZXRJZCIsInRhcmdldFRhYiIsInF1ZXJ5U2VsZWN0b3IiLCJ0YXJnZXRQYW5lSWQiLCJkYXRhc2V0IiwidGFyZ2V0IiwiYWRkIiwiZ2V0RWxlbWVudEJ5SWQiLCJ0YWJDbGljayIsImV2ZW50IiwiY3VycmVudFRhcmdldCIsInByZXZlbnREZWZhdWx0IiwiaGFzaCIsInN1YnN0ciIsInRybl9vYmpfaW5zdGFuY2UiLCJ0YWJWaWV3cyIsImZyb20iLCJkcm9wZG93bnMiLCJoYW5kbGVEcm9wZG93bkNsb3NlIiwiZHJvcGRvd24iLCJuZXh0RWxlbWVudFNpYmxpbmciLCJyZW1vdmVFdmVudExpc3RlbmVyIiwic3RvcFByb3BhZ2F0aW9uIiwibmFtZUlucHV0IiwiYSIsImIiLCJpIiwidmFsIiwicGFyZW50IiwicGFyZW50Tm9kZSIsInRoZW4iLCJjbG9zZUFsbExpc3RzIiwiY3VycmVudEZvY3VzIiwiY3JlYXRlRWxlbWVudCIsInNldEF0dHJpYnV0ZSIsImlkIiwiYXBwZW5kQ2hpbGQiLCJ0ZXh0IiwiaW5uZXJIVE1MIiwic2VsZWN0ZWRJZCIsImRpc3BhdGNoRXZlbnQiLCJFdmVudCIsIngiLCJnZXRFbGVtZW50c0J5VGFnTmFtZSIsImtleUNvZGUiLCJhZGRBY3RpdmUiLCJjbGljayIsInJlbW92ZUFjdGl2ZSIsInJlbW92ZUNoaWxkIiwiU3RyaW5nIiwiZm9ybWF0IiwiYXJncyIsImFyZ3VtZW50cyIsInJlcGxh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Y2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMjgpO1xuIiwiLyoqXHJcbiAqIFBsYXllciBsaXN0IHBhZ2UuXHJcbiAqXHJcbiAqIEBsaW5rICAgICAgIGh0dHBzOi8vd3d3LnRvdXJuYW1hdGNoLmNvbVxyXG4gKiBAc2luY2UgICAgICAzLjIxLjBcclxuICpcclxuICogQHBhY2thZ2UgICAgVG91cm5hbWF0Y2hcclxuICpcclxuICovXHJcbmltcG9ydCB7IHRybiB9IGZyb20gJy4vdG91cm5hbWF0Y2guanMnO1xyXG5cclxuKGZ1bmN0aW9uKGpRdWVyeSwgJCl7XHJcbiAgICAndXNlIHN0cmljdCc7XHJcblxyXG4gICAgY29uc3Qgb3B0aW9ucyA9IHRybl90YWJsZV9vcHRpb25zO1xyXG5cclxuICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIGxldCBjb2x1bW5EZWZzID0gW1xyXG4gICAgICAgICAgICB7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRzOiAwLFxyXG4gICAgICAgICAgICAgICAgbmFtZTogJ2Rpc3BsYXlfbmFtZScsXHJcbiAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICd0cm4tcGxheWVycy10YWJsZS1uYW1lJyxcclxuICAgICAgICAgICAgICAgIHJlbmRlcjogZnVuY3Rpb24gKGRhdGEsIHR5cGUsIHJvdykge1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBgPGEgaHJlZj1cIiR7cm93Lmxpbmt9XCI+JHtyb3cubmFtZX08L2E+YDtcclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIHtcclxuICAgICAgICAgICAgICAgIHRhcmdldHM6IDEsXHJcbiAgICAgICAgICAgICAgICBuYW1lOiAnam9pbmVkX2RhdGUnLFxyXG4gICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAndHJuLXBsYXllcnMtdGFibGUtam9pbmVkJyxcclxuICAgICAgICAgICAgICAgIHJlbmRlcjogZnVuY3Rpb24gKGRhdGEsIHR5cGUsIHJvdykge1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiByb3cuam9pbmVkX2RhdGUucmVuZGVyZWRcclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIHtcclxuICAgICAgICAgICAgICAgIHRhcmdldHM6IDIsXHJcbiAgICAgICAgICAgICAgICBuYW1lOiAnbG9jYXRpb24nLFxyXG4gICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAndHJuLXBsYXllcnMtdGFibGUtbG9jYXRpb24nLFxyXG4gICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHJvdy5sb2NhdGlvbjtcclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIHtcclxuICAgICAgICAgICAgICAgIHRhcmdldHM6IDMsXHJcbiAgICAgICAgICAgICAgICBuYW1lOiAndGVhbXMnLFxyXG4gICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAndHJuLXBsYXllcnMtdGFibGUtdGVhbXMnLFxyXG4gICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHJvdy50ZWFtcztcclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgXTtcclxuICAgICAgICBpZiAob3B0aW9ucy51c2VyX2NhcGFiaWxpdHkpIHtcclxuICAgICAgICAgICAgY29sdW1uRGVmcy5wdXNoKFxyXG4gICAgICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgICAgIHRhcmdldHM6IDQsXHJcbiAgICAgICAgICAgICAgICAgICAgbmFtZTogJ2FjdGlvbnMnLFxyXG4gICAgICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ3Rybi1wbGF5ZXJzLXRhYmxlLWFkbWluJyxcclxuICAgICAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uIChkYXRhLCB0eXBlLCByb3cpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGA8YSBocmVmPVwiJHtyb3cubGlua30vZWRpdFwiIHRpdGxlPVwiJHtvcHRpb25zLmxhbmd1YWdlLmVkaXRfcGxheWVyfVwiPjxpIGNsYXNzPVwiZmEgZmEtZWRpdFwiPjwvaT48L2E+YDtcclxuICAgICAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICAgICAgICAgIG9yZGVyYWJsZTogZmFsc2UsXHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBqUXVlcnkoJyN0cm5fcGxheWVyc19saXN0X3RhYmxlJylcclxuICAgICAgICAgICAgLm9uKCd4aHIuZHQnLCBmdW5jdGlvbiAoZSwgc2V0dGluZ3MsIGpzb24sIHhocikge1xyXG4gICAgICAgICAgICAgICAganNvbi5kYXRhID0gSlNPTi5wYXJzZShKU09OLnN0cmluZ2lmeShqc29uKSk7XHJcbiAgICAgICAgICAgICAgICBqc29uLnJlY29yZHNUb3RhbCA9IHhoci5nZXRSZXNwb25zZUhlYWRlcignWC1XUC1Ub3RhbCcpO1xyXG4gICAgICAgICAgICAgICAganNvbi5yZWNvcmRzRmlsdGVyZWQgPSB4aHIuZ2V0UmVzcG9uc2VIZWFkZXIoJ1RSTi1GaWx0ZXJlZCcpO1xyXG4gICAgICAgICAgICAgICAganNvbi5sZW5ndGggPSB4aHIuZ2V0UmVzcG9uc2VIZWFkZXIoJ1gtV1AtVG90YWxQYWdlcycpO1xyXG4gICAgICAgICAgICAgICAganNvbi5kcmF3ID0geGhyLmdldFJlc3BvbnNlSGVhZGVyKCdUUk4tRHJhdycpO1xyXG4gICAgICAgICAgICB9KVxyXG4gICAgICAgICAgICAuRGF0YVRhYmxlKHtcclxuICAgICAgICAgICAgICAgIHByb2Nlc3Npbmc6IHRydWUsXHJcbiAgICAgICAgICAgICAgICBzZXJ2ZXJTaWRlOiB0cnVlLFxyXG4gICAgICAgICAgICAgICAgbGVuZ3RoTWVudTogW1syNSwgNTAsIDEwMCwgLTFdLCBbMjUsIDUwLCAxMDAsICdBbGwnXV0sXHJcbiAgICAgICAgICAgICAgICBsYW5ndWFnZTogb3B0aW9uc1sndGFibGVfbGFuZ3VhZ2UnXSxcclxuICAgICAgICAgICAgICAgIGF1dG9XaWR0aDogZmFsc2UsXHJcbiAgICAgICAgICAgICAgICBhamF4OiB7XHJcbiAgICAgICAgICAgICAgICAgICAgdXJsOiBgJHtvcHRpb25zLmFwaV91cmx9cGxheWVycy8/X3dwbm9uY2U9JHtvcHRpb25zLnJlc3Rfbm9uY2V9YCxcclxuICAgICAgICAgICAgICAgICAgICB0eXBlOiAnR0VUJyxcclxuICAgICAgICAgICAgICAgICAgICBkYXRhOiBmdW5jdGlvbiAoZGF0YSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhkYXRhKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbGV0IHNlbnQgPSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBkcmF3OiBkYXRhLmRyYXcsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBwYWdlOiBNYXRoLmZsb29yKGRhdGEuc3RhcnQgLyBkYXRhLmxlbmd0aCksXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBwZXJfcGFnZTogZGF0YS5sZW5ndGgsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzZWFyY2g6IGRhdGEuc2VhcmNoLnZhbHVlLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgb3JkZXJieTogYCR7ZGF0YS5jb2x1bW5zW2RhdGEub3JkZXJbMF0uY29sdW1uXS5uYW1lfS4ke2RhdGEub3JkZXJbMF0uZGlyfWBcclxuICAgICAgICAgICAgICAgICAgICAgICAgfTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2coc2VudCk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBzZW50O1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgICAgICBvcmRlcjogW1swLCAnYXNjJ11dLFxyXG4gICAgICAgICAgICAgICAgY29sdW1uRGVmczogY29sdW1uRGVmcyxcclxuICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgfSwgZmFsc2UpO1xyXG59KCBqUXVlcnksIHRybiApKTtcclxuIiwiJ3VzZSBzdHJpY3QnO1xyXG5jbGFzcyBUb3VybmFtYXRjaCB7XHJcblxyXG4gICAgY29uc3RydWN0b3IoKSB7XHJcbiAgICAgICAgdGhpcy5ldmVudHMgPSB7fTtcclxuICAgIH1cclxuXHJcbiAgICBwYXJhbShvYmplY3QsIHByZWZpeCkge1xyXG4gICAgICAgIGxldCBzdHIgPSBbXTtcclxuICAgICAgICBmb3IgKGxldCBwcm9wIGluIG9iamVjdCkge1xyXG4gICAgICAgICAgICBpZiAob2JqZWN0Lmhhc093blByb3BlcnR5KHByb3ApKSB7XHJcbiAgICAgICAgICAgICAgICBsZXQgayA9IHByZWZpeCA/IHByZWZpeCArIFwiW1wiICsgcHJvcCArIFwiXVwiIDogcHJvcDtcclxuICAgICAgICAgICAgICAgIGxldCB2ID0gb2JqZWN0W3Byb3BdO1xyXG4gICAgICAgICAgICAgICAgc3RyLnB1c2goKHYgIT09IG51bGwgJiYgdHlwZW9mIHYgPT09IFwib2JqZWN0XCIpID8gdGhpcy5wYXJhbSh2LCBrKSA6IGVuY29kZVVSSUNvbXBvbmVudChrKSArIFwiPVwiICsgZW5jb2RlVVJJQ29tcG9uZW50KHYpKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gc3RyLmpvaW4oXCImXCIpO1xyXG4gICAgfVxyXG5cclxuICAgIGV2ZW50KGV2ZW50TmFtZSkge1xyXG4gICAgICAgIGlmICghKGV2ZW50TmFtZSBpbiB0aGlzLmV2ZW50cykpIHtcclxuICAgICAgICAgICAgdGhpcy5ldmVudHNbZXZlbnROYW1lXSA9IG5ldyBFdmVudFRhcmdldChldmVudE5hbWUpO1xyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gdGhpcy5ldmVudHNbZXZlbnROYW1lXTtcclxuICAgIH1cclxuXHJcbiAgICBhdXRvY29tcGxldGUoaW5wdXQsIGRhdGFDYWxsYmFjaykge1xyXG4gICAgICAgIG5ldyBUb3VybmFtYXRjaF9BdXRvY29tcGxldGUoaW5wdXQsIGRhdGFDYWxsYmFjayk7XHJcbiAgICB9XHJcblxyXG4gICAgdWNmaXJzdChzKSB7XHJcbiAgICAgICAgaWYgKHR5cGVvZiBzICE9PSAnc3RyaW5nJykgcmV0dXJuICcnO1xyXG4gICAgICAgIHJldHVybiBzLmNoYXJBdCgwKS50b1VwcGVyQ2FzZSgpICsgcy5zbGljZSgxKTtcclxuICAgIH1cclxuXHJcbiAgICBvcmRpbmFsX3N1ZmZpeChudW1iZXIpIHtcclxuICAgICAgICBjb25zdCByZW1haW5kZXIgPSBudW1iZXIgJSAxMDA7XHJcblxyXG4gICAgICAgIGlmICgocmVtYWluZGVyIDwgMTEpIHx8IChyZW1haW5kZXIgPiAxMykpIHtcclxuICAgICAgICAgICAgc3dpdGNoIChyZW1haW5kZXIgJSAxMCkge1xyXG4gICAgICAgICAgICAgICAgY2FzZSAxOiByZXR1cm4gJ3N0JztcclxuICAgICAgICAgICAgICAgIGNhc2UgMjogcmV0dXJuICduZCc7XHJcbiAgICAgICAgICAgICAgICBjYXNlIDM6IHJldHVybiAncmQnO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiAndGgnO1xyXG4gICAgfVxyXG5cclxuICAgIHRhYnMoZWxlbWVudCkge1xyXG4gICAgICAgIGNvbnN0IHRhYnMgPSBlbGVtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1uYXYtbGluaycpO1xyXG4gICAgICAgIGNvbnN0IHBhbmVzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLXRhYi1wYW5lJyk7XHJcbiAgICAgICAgY29uc3QgY2xlYXJBY3RpdmUgPSAoKSA9PiB7XHJcbiAgICAgICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwodGFicywgKHRhYikgPT4ge1xyXG4gICAgICAgICAgICAgICAgdGFiLmNsYXNzTGlzdC5yZW1vdmUoJ3Rybi1uYXYtYWN0aXZlJyk7XHJcbiAgICAgICAgICAgICAgICB0YWIuYXJpYVNlbGVjdGVkID0gZmFsc2U7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHBhbmVzLCBwYW5lID0+IHBhbmUuY2xhc3NMaXN0LnJlbW92ZSgndHJuLXRhYi1hY3RpdmUnKSk7XHJcbiAgICAgICAgfTtcclxuICAgICAgICBjb25zdCBzZXRBY3RpdmUgPSAodGFyZ2V0SWQpID0+IHtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0VGFiID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignYVtocmVmPVwiIycgKyB0YXJnZXRJZCArICdcIl0udHJuLW5hdi1saW5rJyk7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFBhbmVJZCA9IHRhcmdldFRhYiAmJiB0YXJnZXRUYWIuZGF0YXNldCAmJiB0YXJnZXRUYWIuZGF0YXNldC50YXJnZXQgfHwgZmFsc2U7XHJcblxyXG4gICAgICAgICAgICBpZiAodGFyZ2V0UGFuZUlkKSB7XHJcbiAgICAgICAgICAgICAgICBjbGVhckFjdGl2ZSgpO1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0VGFiLmNsYXNzTGlzdC5hZGQoJ3Rybi1uYXYtYWN0aXZlJyk7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRUYWIuYXJpYVNlbGVjdGVkID0gdHJ1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0YXJnZXRQYW5lSWQpLmNsYXNzTGlzdC5hZGQoJ3Rybi10YWItYWN0aXZlJyk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9O1xyXG4gICAgICAgIGNvbnN0IHRhYkNsaWNrID0gKGV2ZW50KSA9PiB7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFRhYiA9IGV2ZW50LmN1cnJlbnRUYXJnZXQ7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFBhbmVJZCA9IHRhcmdldFRhYiAmJiB0YXJnZXRUYWIuZGF0YXNldCAmJiB0YXJnZXRUYWIuZGF0YXNldC50YXJnZXQgfHwgZmFsc2U7XHJcblxyXG4gICAgICAgICAgICBpZiAodGFyZ2V0UGFuZUlkKSB7XHJcbiAgICAgICAgICAgICAgICBzZXRBY3RpdmUodGFyZ2V0UGFuZUlkKTtcclxuICAgICAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9O1xyXG5cclxuICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHRhYnMsICh0YWIpID0+IHtcclxuICAgICAgICAgICAgdGFiLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGFiQ2xpY2spO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICBpZiAobG9jYXRpb24uaGFzaCkge1xyXG4gICAgICAgICAgICBzZXRBY3RpdmUobG9jYXRpb24uaGFzaC5zdWJzdHIoMSkpO1xyXG4gICAgICAgIH0gZWxzZSBpZiAodGFicy5sZW5ndGggPiAwKSB7XHJcbiAgICAgICAgICAgIHNldEFjdGl2ZSh0YWJzWzBdLmRhdGFzZXQudGFyZ2V0KTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcblxyXG59XHJcblxyXG4vL3Rybi5pbml0aWFsaXplKCk7XHJcbmlmICghd2luZG93LnRybl9vYmpfaW5zdGFuY2UpIHtcclxuICAgIHdpbmRvdy50cm5fb2JqX2luc3RhbmNlID0gbmV3IFRvdXJuYW1hdGNoKCk7XHJcblxyXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCBmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgIGNvbnN0IHRhYlZpZXdzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLW5hdicpO1xyXG5cclxuICAgICAgICBBcnJheS5mcm9tKHRhYlZpZXdzKS5mb3JFYWNoKCh0YWIpID0+IHtcclxuICAgICAgICAgICAgdHJuLnRhYnModGFiKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgY29uc3QgZHJvcGRvd25zID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLWRyb3Bkb3duLXRvZ2dsZScpO1xyXG4gICAgICAgIGNvbnN0IGhhbmRsZURyb3Bkb3duQ2xvc2UgPSAoKSA9PiB7XHJcbiAgICAgICAgICAgIEFycmF5LmZyb20oZHJvcGRvd25zKS5mb3JFYWNoKChkcm9wZG93bikgPT4ge1xyXG4gICAgICAgICAgICAgICAgZHJvcGRvd24ubmV4dEVsZW1lbnRTaWJsaW5nLmNsYXNzTGlzdC5yZW1vdmUoJ3Rybi1zaG93Jyk7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICBkb2N1bWVudC5yZW1vdmVFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgaGFuZGxlRHJvcGRvd25DbG9zZSwgZmFsc2UpO1xyXG4gICAgICAgIH07XHJcblxyXG4gICAgICAgIEFycmF5LmZyb20oZHJvcGRvd25zKS5mb3JFYWNoKChkcm9wZG93bikgPT4ge1xyXG4gICAgICAgICAgICBkcm9wZG93bi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uKGUpIHtcclxuICAgICAgICAgICAgICAgIGUuc3RvcFByb3BhZ2F0aW9uKCk7XHJcbiAgICAgICAgICAgICAgICB0aGlzLm5leHRFbGVtZW50U2libGluZy5jbGFzc0xpc3QuYWRkKCd0cm4tc2hvdycpO1xyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGhhbmRsZURyb3Bkb3duQ2xvc2UsIGZhbHNlKTtcclxuICAgICAgICAgICAgfSwgZmFsc2UpO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgIH0sIGZhbHNlKTtcclxufVxyXG5leHBvcnQgbGV0IHRybiA9IHdpbmRvdy50cm5fb2JqX2luc3RhbmNlO1xyXG5cclxuY2xhc3MgVG91cm5hbWF0Y2hfQXV0b2NvbXBsZXRlIHtcclxuXHJcbiAgICAvLyBjdXJyZW50Rm9jdXM7XHJcbiAgICAvL1xyXG4gICAgLy8gbmFtZUlucHV0O1xyXG4gICAgLy9cclxuICAgIC8vIHNlbGY7XHJcblxyXG4gICAgY29uc3RydWN0b3IoaW5wdXQsIGRhdGFDYWxsYmFjaykge1xyXG4gICAgICAgIC8vIHRoaXMuc2VsZiA9IHRoaXM7XHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQgPSBpbnB1dDtcclxuXHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcihcImlucHV0XCIsICgpID0+IHtcclxuICAgICAgICAgICAgbGV0IGEsIGIsIGksIHZhbCA9IHRoaXMubmFtZUlucHV0LnZhbHVlOy8vdGhpcy52YWx1ZTtcclxuICAgICAgICAgICAgbGV0IHBhcmVudCA9IHRoaXMubmFtZUlucHV0LnBhcmVudE5vZGU7Ly90aGlzLnBhcmVudE5vZGU7XHJcblxyXG4gICAgICAgICAgICAvLyBsZXQgcCA9IG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpID0+IHtcclxuICAgICAgICAgICAgLy8gICAgIC8qIG5lZWQgdG8gcXVlcnkgc2VydmVyIGZvciBuYW1lcyBoZXJlLiAqL1xyXG4gICAgICAgICAgICAvLyAgICAgbGV0IHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLm9wZW4oJ0dFVCcsIG9wdGlvbnMuYXBpX3VybCArICdwbGF5ZXJzLz9zZWFyY2g9JyArIHZhbCArICcmcGVyX3BhZ2U9NScpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMCkge1xyXG4gICAgICAgICAgICAvLyAgICAgICAgICAgICAvLyByZXNvbHZlKEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKS5tYXAoKHBsYXllcikgPT4ge3JldHVybiB7ICd2YWx1ZSc6IHBsYXllci5pZCwgJ3RleHQnOiBwbGF5ZXIubmFtZSB9O30pKTtcclxuICAgICAgICAgICAgLy8gICAgICAgICAgICAgcmVzb2x2ZShKU09OLnBhcnNlKHhoci5yZXNwb25zZSkubWFwKChwbGF5ZXIpID0+IHtyZXR1cm4gcGxheWVyLm5hbWU7fSkpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgICAgIHJlamVjdCgpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gICAgIH07XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIuc2VuZCgpO1xyXG4gICAgICAgICAgICAvLyB9KTtcclxuICAgICAgICAgICAgZGF0YUNhbGxiYWNrKHZhbCkudGhlbigoZGF0YSkgPT4gey8vcC50aGVuKChkYXRhKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhkYXRhKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmNsb3NlIGFueSBhbHJlYWR5IG9wZW4gbGlzdHMgb2YgYXV0by1jb21wbGV0ZWQgdmFsdWVzKi9cclxuICAgICAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cygpO1xyXG4gICAgICAgICAgICAgICAgaWYgKCF2YWwpIHsgcmV0dXJuIGZhbHNlO31cclxuICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudEZvY3VzID0gLTE7XHJcblxyXG4gICAgICAgICAgICAgICAgLypjcmVhdGUgYSBESVYgZWxlbWVudCB0aGF0IHdpbGwgY29udGFpbiB0aGUgaXRlbXMgKHZhbHVlcyk6Ki9cclxuICAgICAgICAgICAgICAgIGEgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiRElWXCIpO1xyXG4gICAgICAgICAgICAgICAgYS5zZXRBdHRyaWJ1dGUoXCJpZFwiLCB0aGlzLm5hbWVJbnB1dC5pZCArIFwiLWF1dG8tY29tcGxldGUtbGlzdFwiKTtcclxuICAgICAgICAgICAgICAgIGEuc2V0QXR0cmlidXRlKFwiY2xhc3NcIiwgXCJ0cm4tYXV0by1jb21wbGV0ZS1pdGVtc1wiKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmFwcGVuZCB0aGUgRElWIGVsZW1lbnQgYXMgYSBjaGlsZCBvZiB0aGUgYXV0by1jb21wbGV0ZSBjb250YWluZXI6Ki9cclxuICAgICAgICAgICAgICAgIHBhcmVudC5hcHBlbmRDaGlsZChhKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmZvciBlYWNoIGl0ZW0gaW4gdGhlIGFycmF5Li4uKi9cclxuICAgICAgICAgICAgICAgIGZvciAoaSA9IDA7IGkgPCBkYXRhLmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IHRleHQsIHZhbHVlO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAvKiBXaGljaCBmb3JtYXQgZGlkIHRoZXkgZ2l2ZSB1cy4gKi9cclxuICAgICAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGRhdGFbaV0gPT09ICdvYmplY3QnKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHRleHQgPSBkYXRhW2ldWyd0ZXh0J107XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlID0gZGF0YVtpXVsndmFsdWUnXTtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB0ZXh0ID0gZGF0YVtpXTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSBkYXRhW2ldO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICAgICAgLypjaGVjayBpZiB0aGUgaXRlbSBzdGFydHMgd2l0aCB0aGUgc2FtZSBsZXR0ZXJzIGFzIHRoZSB0ZXh0IGZpZWxkIHZhbHVlOiovXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHRleHQuc3Vic3RyKDAsIHZhbC5sZW5ndGgpLnRvVXBwZXJDYXNlKCkgPT09IHZhbC50b1VwcGVyQ2FzZSgpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qY3JlYXRlIGEgRElWIGVsZW1lbnQgZm9yIGVhY2ggbWF0Y2hpbmcgZWxlbWVudDoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcIkRJVlwiKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgLyptYWtlIHRoZSBtYXRjaGluZyBsZXR0ZXJzIGJvbGQ6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgPSBcIjxzdHJvbmc+XCIgKyB0ZXh0LnN1YnN0cigwLCB2YWwubGVuZ3RoKSArIFwiPC9zdHJvbmc+XCI7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuaW5uZXJIVE1MICs9IHRleHQuc3Vic3RyKHZhbC5sZW5ndGgpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgLyppbnNlcnQgYSBpbnB1dCBmaWVsZCB0aGF0IHdpbGwgaG9sZCB0aGUgY3VycmVudCBhcnJheSBpdGVtJ3MgdmFsdWU6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgKz0gXCI8aW5wdXQgdHlwZT0naGlkZGVuJyB2YWx1ZT0nXCIgKyB2YWx1ZSArIFwiJz5cIjtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuZGF0YXNldC52YWx1ZSA9IHZhbHVlO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmRhdGFzZXQudGV4dCA9IHRleHQ7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiB3aGVuIHNvbWVvbmUgY2xpY2tzIG9uIHRoZSBpdGVtIHZhbHVlIChESVYgZWxlbWVudCk6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKGUpID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKGBpdGVtIGNsaWNrZWQgd2l0aCB2YWx1ZSAke2UuY3VycmVudFRhcmdldC5kYXRhc2V0LnZhbHVlfWApO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8qIGluc2VydCB0aGUgdmFsdWUgZm9yIHRoZSBhdXRvY29tcGxldGUgdGV4dCBmaWVsZDogKi9cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LnZhbHVlID0gZS5jdXJyZW50VGFyZ2V0LmRhdGFzZXQudGV4dDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LmRhdGFzZXQuc2VsZWN0ZWRJZCA9IGUuY3VycmVudFRhcmdldC5kYXRhc2V0LnZhbHVlO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8qIGNsb3NlIHRoZSBsaXN0IG9mIGF1dG9jb21wbGV0ZWQgdmFsdWVzLCAob3IgYW55IG90aGVyIG9wZW4gbGlzdHMgb2YgYXV0b2NvbXBsZXRlZCB2YWx1ZXM6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cygpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LmRpc3BhdGNoRXZlbnQobmV3IEV2ZW50KCdjaGFuZ2UnKSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBhLmFwcGVuZENoaWxkKGIpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHByZXNzZXMgYSBrZXkgb24gdGhlIGtleWJvYXJkOiovXHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcihcImtleWRvd25cIiwgKGUpID0+IHtcclxuICAgICAgICAgICAgbGV0IHggPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0aGlzLm5hbWVJbnB1dC5pZCArIFwiLWF1dG8tY29tcGxldGUtbGlzdFwiKTtcclxuICAgICAgICAgICAgaWYgKHgpIHggPSB4LmdldEVsZW1lbnRzQnlUYWdOYW1lKFwiZGl2XCIpO1xyXG4gICAgICAgICAgICBpZiAoZS5rZXlDb2RlID09PSA0MCkge1xyXG4gICAgICAgICAgICAgICAgLypJZiB0aGUgYXJyb3cgRE9XTiBrZXkgaXMgcHJlc3NlZCxcclxuICAgICAgICAgICAgICAgICBpbmNyZWFzZSB0aGUgY3VycmVudEZvY3VzIHZhcmlhYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmN1cnJlbnRGb2N1cysrO1xyXG4gICAgICAgICAgICAgICAgLyphbmQgYW5kIG1ha2UgdGhlIGN1cnJlbnQgaXRlbSBtb3JlIHZpc2libGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuYWRkQWN0aXZlKHgpO1xyXG4gICAgICAgICAgICB9IGVsc2UgaWYgKGUua2V5Q29kZSA9PT0gMzgpIHsgLy91cFxyXG4gICAgICAgICAgICAgICAgLypJZiB0aGUgYXJyb3cgVVAga2V5IGlzIHByZXNzZWQsXHJcbiAgICAgICAgICAgICAgICAgZGVjcmVhc2UgdGhlIGN1cnJlbnRGb2N1cyB2YXJpYWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50Rm9jdXMtLTtcclxuICAgICAgICAgICAgICAgIC8qYW5kIGFuZCBtYWtlIHRoZSBjdXJyZW50IGl0ZW0gbW9yZSB2aXNpYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmFkZEFjdGl2ZSh4KTtcclxuICAgICAgICAgICAgfSBlbHNlIGlmIChlLmtleUNvZGUgPT09IDEzKSB7XHJcbiAgICAgICAgICAgICAgICAvKklmIHRoZSBFTlRFUiBrZXkgaXMgcHJlc3NlZCwgcHJldmVudCB0aGUgZm9ybSBmcm9tIGJlaW5nIHN1Ym1pdHRlZCwqL1xyXG4gICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzID4gLTEpIHtcclxuICAgICAgICAgICAgICAgICAgICAvKmFuZCBzaW11bGF0ZSBhIGNsaWNrIG9uIHRoZSBcImFjdGl2ZVwiIGl0ZW06Ki9cclxuICAgICAgICAgICAgICAgICAgICBpZiAoeCkgeFt0aGlzLmN1cnJlbnRGb2N1c10uY2xpY2soKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiB3aGVuIHNvbWVvbmUgY2xpY2tzIGluIHRoZSBkb2N1bWVudDoqL1xyXG4gICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICB0aGlzLmNsb3NlQWxsTGlzdHMoZS50YXJnZXQpO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG5cclxuICAgIGFkZEFjdGl2ZSh4KSB7XHJcbiAgICAgICAgLyphIGZ1bmN0aW9uIHRvIGNsYXNzaWZ5IGFuIGl0ZW0gYXMgXCJhY3RpdmVcIjoqL1xyXG4gICAgICAgIGlmICgheCkgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgIC8qc3RhcnQgYnkgcmVtb3ZpbmcgdGhlIFwiYWN0aXZlXCIgY2xhc3Mgb24gYWxsIGl0ZW1zOiovXHJcbiAgICAgICAgdGhpcy5yZW1vdmVBY3RpdmUoeCk7XHJcbiAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzID49IHgubGVuZ3RoKSB0aGlzLmN1cnJlbnRGb2N1cyA9IDA7XHJcbiAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzIDwgMCkgdGhpcy5jdXJyZW50Rm9jdXMgPSAoeC5sZW5ndGggLSAxKTtcclxuICAgICAgICAvKmFkZCBjbGFzcyBcImF1dG9jb21wbGV0ZS1hY3RpdmVcIjoqL1xyXG4gICAgICAgIHhbdGhpcy5jdXJyZW50Rm9jdXNdLmNsYXNzTGlzdC5hZGQoXCJ0cm4tYXV0by1jb21wbGV0ZS1hY3RpdmVcIik7XHJcbiAgICB9XHJcblxyXG4gICAgcmVtb3ZlQWN0aXZlKHgpIHtcclxuICAgICAgICAvKmEgZnVuY3Rpb24gdG8gcmVtb3ZlIHRoZSBcImFjdGl2ZVwiIGNsYXNzIGZyb20gYWxsIGF1dG9jb21wbGV0ZSBpdGVtczoqL1xyXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgeC5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICB4W2ldLmNsYXNzTGlzdC5yZW1vdmUoXCJ0cm4tYXV0by1jb21wbGV0ZS1hY3RpdmVcIik7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIGNsb3NlQWxsTGlzdHMoZWxlbWVudCkge1xyXG4gICAgICAgIGNvbnNvbGUubG9nKFwiY2xvc2UgYWxsIGxpc3RzXCIpO1xyXG4gICAgICAgIC8qY2xvc2UgYWxsIGF1dG9jb21wbGV0ZSBsaXN0cyBpbiB0aGUgZG9jdW1lbnQsXHJcbiAgICAgICAgIGV4Y2VwdCB0aGUgb25lIHBhc3NlZCBhcyBhbiBhcmd1bWVudDoqL1xyXG4gICAgICAgIGxldCB4ID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShcInRybi1hdXRvLWNvbXBsZXRlLWl0ZW1zXCIpO1xyXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgeC5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICBpZiAoZWxlbWVudCAhPT0geFtpXSAmJiBlbGVtZW50ICE9PSB0aGlzLm5hbWVJbnB1dCkge1xyXG4gICAgICAgICAgICAgICAgeFtpXS5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKHhbaV0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG59XHJcblxyXG4vLyBGaXJzdCwgY2hlY2tzIGlmIGl0IGlzbid0IGltcGxlbWVudGVkIHlldC5cclxuaWYgKCFTdHJpbmcucHJvdG90eXBlLmZvcm1hdCkge1xyXG4gICAgU3RyaW5nLnByb3RvdHlwZS5mb3JtYXQgPSBmdW5jdGlvbigpIHtcclxuICAgICAgICBjb25zdCBhcmdzID0gYXJndW1lbnRzO1xyXG4gICAgICAgIHJldHVybiB0aGlzLnJlcGxhY2UoL3soXFxkKyl9L2csIGZ1bmN0aW9uKG1hdGNoLCBudW1iZXIpIHtcclxuICAgICAgICAgICAgcmV0dXJuIHR5cGVvZiBhcmdzW251bWJlcl0gIT09ICd1bmRlZmluZWQnXHJcbiAgICAgICAgICAgICAgICA/IGFyZ3NbbnVtYmVyXVxyXG4gICAgICAgICAgICAgICAgOiBtYXRjaFxyXG4gICAgICAgICAgICAgICAgO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfTtcclxufSJdLCJzb3VyY2VSb290IjoiIn0=