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
/******/ 	return __webpack_require__(__webpack_require__.s = 38);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/teams.js":
/*!*************************!*\
  !*** ./src/js/teams.js ***!
  \*************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./tournamatch.js */ "./src/js/tournamatch.js");
/**
 * Team list page.
 *
 * @link       https://www.tournamatch.com
 * @since      3.11.0
 * @since      3.21.0 Added support for server side DataTables.
 *
 * @package    Tournamatch
 *
 */


(function (jQuery, $) {
  'use strict';

  var options = trn_teams_list_table_options;

  function handleDeleteConfirm() {
    var links = document.getElementsByClassName('trn-confirm-action-link');
    Array.prototype.forEach.call(links, function (link) {
      link.addEventListener('trn.confirmed.action.delete-team', function (event) {
        event.preventDefault();
        console.log("modal was confirmed for link ".concat(link.dataset.teamId));
        var xhr = new XMLHttpRequest();
        xhr.open('DELETE', "".concat(options.api_url, "teams/").concat(link.dataset.teamId));
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

        xhr.onload = function () {
          if (xhr.status === 204) {
            window.location.reload();
          } else {
            var response = JSON.parse(xhr.response);
            document.getElementById('trn-delete-team-response').innerHTML = "<div class=\"trn-alert trn-alert-danger\"><strong>".concat(options.language.failure, "</strong>: ").concat(response.message, "</div>");
          }
        };

        xhr.send();
      }, false);
    });
  }

  window.addEventListener('load', function () {
    document.addEventListener('trn-html-updated', function (e) {
      handleDeleteConfirm();
    });
    handleDeleteConfirm();
    var columnDefs = [{
      targets: 0,
      name: 'name',
      className: 'trn-teams-table-name',
      render: function render(data, type, row) {
        return "<a href=\"".concat(row.link, "\">").concat(row.name, "</a>");
      }
    }, {
      targets: 1,
      name: 'joined_date',
      className: 'trn-teams-table-created',
      render: function render(data, type, row) {
        return row.joined_date.rendered;
      }
    }, {
      targets: 2,
      name: 'members',
      className: 'trn-teams-table-members',
      render: function render(data, type, row) {
        return row.members;
      }
    }];

    if (options.user_capability) {
      columnDefs.push({
        targets: 3,
        name: 'admin',
        render: function render(data, type, row) {
          var message = options.language.delete_confirm.format(row.name);
          return "<a href=\"".concat(row.link, "/edit\"><i class=\"fa fa-edit\"></i></a> ") + "<a class=\"trn-delete-team-action trn-confirm-action-link\" data-team-id=\"".concat(row.team_id, "\" data-modal-id=\"delete-team\" data-confirm-title=\"").concat(options.language.delete_team, "\" data-confirm-message=\"").concat(message, "\" href=\"#\" title=\"").concat(options.language.delete_team, "\"><i class=\"fa fa-trash\"></i></a>");
        },
        orderable: false
      });
    }

    jQuery('#trn-teams-table').on('xhr.dt', function (e, settings, json, xhr) {
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
        url: "".concat(options.api_url, "teams/?_wpnonce=").concat(options.rest_nonce, "&_embed"),
        type: 'GET',
        data: function data(_data) {
          var sent = {
            draw: _data.draw,
            page: Math.floor(_data.start / _data.length),
            per_page: _data.length,
            search: _data.search.value,
            orderby: "".concat(_data.columns[_data.order[0].column].name, ".").concat(_data.order[0].dir)
          }; //console.log(sent);

          return sent;
        }
      },
      order: [[1, 'asc']],
      columnDefs: columnDefs,
      drawCallback: function drawCallback(settings) {
        document.dispatchEvent(new CustomEvent('trn-html-updated', {
          'detail': 'The table html has updated.'
        }));
      }
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

/***/ 38:
/*!*******************************!*\
  !*** multi ./src/js/teams.js ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\teams.js */"./src/js/teams.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3RlYW1zLmpzIiwid2VicGFjazovLy8uL3NyYy9qcy90b3VybmFtYXRjaC5qcyJdLCJuYW1lcyI6WyJqUXVlcnkiLCIkIiwib3B0aW9ucyIsInRybl90ZWFtc19saXN0X3RhYmxlX29wdGlvbnMiLCJoYW5kbGVEZWxldGVDb25maXJtIiwibGlua3MiLCJkb2N1bWVudCIsImdldEVsZW1lbnRzQnlDbGFzc05hbWUiLCJBcnJheSIsInByb3RvdHlwZSIsImZvckVhY2giLCJjYWxsIiwibGluayIsImFkZEV2ZW50TGlzdGVuZXIiLCJldmVudCIsInByZXZlbnREZWZhdWx0IiwiY29uc29sZSIsImxvZyIsImRhdGFzZXQiLCJ0ZWFtSWQiLCJ4aHIiLCJYTUxIdHRwUmVxdWVzdCIsIm9wZW4iLCJhcGlfdXJsIiwic2V0UmVxdWVzdEhlYWRlciIsInJlc3Rfbm9uY2UiLCJvbmxvYWQiLCJzdGF0dXMiLCJ3aW5kb3ciLCJsb2NhdGlvbiIsInJlbG9hZCIsInJlc3BvbnNlIiwiSlNPTiIsInBhcnNlIiwiZ2V0RWxlbWVudEJ5SWQiLCJpbm5lckhUTUwiLCJsYW5ndWFnZSIsImZhaWx1cmUiLCJtZXNzYWdlIiwic2VuZCIsImUiLCJjb2x1bW5EZWZzIiwidGFyZ2V0cyIsIm5hbWUiLCJjbGFzc05hbWUiLCJyZW5kZXIiLCJkYXRhIiwidHlwZSIsInJvdyIsImpvaW5lZF9kYXRlIiwicmVuZGVyZWQiLCJtZW1iZXJzIiwidXNlcl9jYXBhYmlsaXR5IiwicHVzaCIsImRlbGV0ZV9jb25maXJtIiwiZm9ybWF0IiwidGVhbV9pZCIsImRlbGV0ZV90ZWFtIiwib3JkZXJhYmxlIiwib24iLCJzZXR0aW5ncyIsImpzb24iLCJzdHJpbmdpZnkiLCJyZWNvcmRzVG90YWwiLCJnZXRSZXNwb25zZUhlYWRlciIsInJlY29yZHNGaWx0ZXJlZCIsImxlbmd0aCIsImRyYXciLCJEYXRhVGFibGUiLCJwcm9jZXNzaW5nIiwic2VydmVyU2lkZSIsImxlbmd0aE1lbnUiLCJhdXRvV2lkdGgiLCJhamF4IiwidXJsIiwic2VudCIsInBhZ2UiLCJNYXRoIiwiZmxvb3IiLCJzdGFydCIsInBlcl9wYWdlIiwic2VhcmNoIiwidmFsdWUiLCJvcmRlcmJ5IiwiY29sdW1ucyIsIm9yZGVyIiwiY29sdW1uIiwiZGlyIiwiZHJhd0NhbGxiYWNrIiwiZGlzcGF0Y2hFdmVudCIsIkN1c3RvbUV2ZW50IiwidHJuIiwiVG91cm5hbWF0Y2giLCJldmVudHMiLCJvYmplY3QiLCJwcmVmaXgiLCJzdHIiLCJwcm9wIiwiaGFzT3duUHJvcGVydHkiLCJrIiwidiIsInBhcmFtIiwiZW5jb2RlVVJJQ29tcG9uZW50Iiwiam9pbiIsImV2ZW50TmFtZSIsIkV2ZW50VGFyZ2V0IiwiaW5wdXQiLCJkYXRhQ2FsbGJhY2siLCJUb3VybmFtYXRjaF9BdXRvY29tcGxldGUiLCJzIiwiY2hhckF0IiwidG9VcHBlckNhc2UiLCJzbGljZSIsIm51bWJlciIsInJlbWFpbmRlciIsImVsZW1lbnQiLCJ0YWJzIiwicGFuZXMiLCJjbGVhckFjdGl2ZSIsInRhYiIsImNsYXNzTGlzdCIsInJlbW92ZSIsImFyaWFTZWxlY3RlZCIsInBhbmUiLCJzZXRBY3RpdmUiLCJ0YXJnZXRJZCIsInRhcmdldFRhYiIsInF1ZXJ5U2VsZWN0b3IiLCJ0YXJnZXRQYW5lSWQiLCJ0YXJnZXQiLCJhZGQiLCJ0YWJDbGljayIsImN1cnJlbnRUYXJnZXQiLCJoYXNoIiwic3Vic3RyIiwidHJuX29ial9pbnN0YW5jZSIsInRhYlZpZXdzIiwiZnJvbSIsImRyb3Bkb3ducyIsImhhbmRsZURyb3Bkb3duQ2xvc2UiLCJkcm9wZG93biIsIm5leHRFbGVtZW50U2libGluZyIsInJlbW92ZUV2ZW50TGlzdGVuZXIiLCJzdG9wUHJvcGFnYXRpb24iLCJuYW1lSW5wdXQiLCJhIiwiYiIsImkiLCJ2YWwiLCJwYXJlbnQiLCJwYXJlbnROb2RlIiwidGhlbiIsImNsb3NlQWxsTGlzdHMiLCJjdXJyZW50Rm9jdXMiLCJjcmVhdGVFbGVtZW50Iiwic2V0QXR0cmlidXRlIiwiaWQiLCJhcHBlbmRDaGlsZCIsInRleHQiLCJzZWxlY3RlZElkIiwiRXZlbnQiLCJ4IiwiZ2V0RWxlbWVudHNCeVRhZ05hbWUiLCJrZXlDb2RlIiwiYWRkQWN0aXZlIiwiY2xpY2siLCJyZW1vdmVBY3RpdmUiLCJyZW1vdmVDaGlsZCIsIlN0cmluZyIsImFyZ3MiLCJhcmd1bWVudHMiLCJyZXBsYWNlIiwibWF0Y2giXSwibWFwcGluZ3MiOiI7UUFBQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTs7O1FBR0E7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLDBDQUEwQyxnQ0FBZ0M7UUFDMUU7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSx3REFBd0Qsa0JBQWtCO1FBQzFFO1FBQ0EsaURBQWlELGNBQWM7UUFDL0Q7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBLHlDQUF5QyxpQ0FBaUM7UUFDMUUsZ0hBQWdILG1CQUFtQixFQUFFO1FBQ3JJO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0EsMkJBQTJCLDBCQUEwQixFQUFFO1FBQ3ZELGlDQUFpQyxlQUFlO1FBQ2hEO1FBQ0E7UUFDQTs7UUFFQTtRQUNBLHNEQUFzRCwrREFBK0Q7O1FBRXJIO1FBQ0E7OztRQUdBO1FBQ0E7Ozs7Ozs7Ozs7Ozs7QUNsRkE7QUFBQTtBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsQ0FBQyxVQUFVQSxNQUFWLEVBQWtCQyxDQUFsQixFQUFxQjtBQUNsQjs7QUFFQSxNQUFJQyxPQUFPLEdBQUdDLDRCQUFkOztBQUVBLFdBQVNDLG1CQUFULEdBQStCO0FBQzNC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RUFBb0Q7QUFDaERpQixXQUFDLENBQUNkLENBQUQsQ0FBRCxDQUFLRyxVQUFMLENBQWdCaUIsV0FBaEIsQ0FBNEJOLENBQUMsQ0FBQ2QsQ0FBRCxDQUE3QjtBQUNIO0FBQ0o7QUFDSjs7OztLQUdMOzs7QUFDQSxJQUFJLENBQUNxQixNQUFNLENBQUMvSixTQUFQLENBQWlCOEMsTUFBdEIsRUFBOEI7QUFDMUJpSCxRQUFNLENBQUMvSixTQUFQLENBQWlCOEMsTUFBakIsR0FBMEIsWUFBVztBQUNqQyxRQUFNa0gsSUFBSSxHQUFHQyxTQUFiO0FBQ0EsV0FBTyxLQUFLQyxPQUFMLENBQWEsVUFBYixFQUF5QixVQUFTQyxLQUFULEVBQWdCM0QsTUFBaEIsRUFBd0I7QUFDcEQsYUFBTyxPQUFPd0QsSUFBSSxDQUFDeEQsTUFBRCxDQUFYLEtBQXdCLFdBQXhCLEdBQ0R3RCxJQUFJLENBQUN4RCxNQUFELENBREgsR0FFRDJELEtBRk47QUFJSCxLQUxNLENBQVA7QUFNSCxHQVJEO0FBU0gsQyIsImZpbGUiOiJ0ZWFtcy5qcyIsInNvdXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwgeyBlbnVtZXJhYmxlOiB0cnVlLCBnZXQ6IGdldHRlciB9KTtcbiBcdFx0fVxuIFx0fTtcblxuIFx0Ly8gZGVmaW5lIF9fZXNNb2R1bGUgb24gZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yID0gZnVuY3Rpb24oZXhwb3J0cykge1xuIFx0XHRpZih0eXBlb2YgU3ltYm9sICE9PSAndW5kZWZpbmVkJyAmJiBTeW1ib2wudG9TdHJpbmdUYWcpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgU3ltYm9sLnRvU3RyaW5nVGFnLCB7IHZhbHVlOiAnTW9kdWxlJyB9KTtcbiBcdFx0fVxuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgJ19fZXNNb2R1bGUnLCB7IHZhbHVlOiB0cnVlIH0pO1xuIFx0fTtcblxuIFx0Ly8gY3JlYXRlIGEgZmFrZSBuYW1lc3BhY2Ugb2JqZWN0XG4gXHQvLyBtb2RlICYgMTogdmFsdWUgaXMgYSBtb2R1bGUgaWQsIHJlcXVpcmUgaXRcbiBcdC8vIG1vZGUgJiAyOiBtZXJnZSBhbGwgcHJvcGVydGllcyBvZiB2YWx1ZSBpbnRvIHRoZSBuc1xuIFx0Ly8gbW9kZSAmIDQ6IHJldHVybiB2YWx1ZSB3aGVuIGFscmVhZHkgbnMgb2JqZWN0XG4gXHQvLyBtb2RlICYgOHwxOiBiZWhhdmUgbGlrZSByZXF1aXJlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnQgPSBmdW5jdGlvbih2YWx1ZSwgbW9kZSkge1xuIFx0XHRpZihtb2RlICYgMSkgdmFsdWUgPSBfX3dlYnBhY2tfcmVxdWlyZV9fKHZhbHVlKTtcbiBcdFx0aWYobW9kZSAmIDgpIHJldHVybiB2YWx1ZTtcbiBcdFx0aWYoKG1vZGUgJiA0KSAmJiB0eXBlb2YgdmFsdWUgPT09ICdvYmplY3QnICYmIHZhbHVlICYmIHZhbHVlLl9fZXNNb2R1bGUpIHJldHVybiB2YWx1ZTtcbiBcdFx0dmFyIG5zID0gT2JqZWN0LmNyZWF0ZShudWxsKTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yKG5zKTtcbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KG5zLCAnZGVmYXVsdCcsIHsgZW51bWVyYWJsZTogdHJ1ZSwgdmFsdWU6IHZhbHVlIH0pO1xuIFx0XHRpZihtb2RlICYgMiAmJiB0eXBlb2YgdmFsdWUgIT0gJ3N0cmluZycpIGZvcih2YXIga2V5IGluIHZhbHVlKSBfX3dlYnBhY2tfcmVxdWlyZV9fLmQobnMsIGtleSwgZnVuY3Rpb24oa2V5KSB7IHJldHVybiB2YWx1ZVtrZXldOyB9LmJpbmQobnVsbCwga2V5KSk7XG4gXHRcdHJldHVybiBucztcbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiXCI7XG5cblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSAzOCk7XG4iLCIvKipcclxuICogVGVhbSBsaXN0IHBhZ2UuXHJcbiAqXHJcbiAqIEBsaW5rICAgICAgIGh0dHBzOi8vd3d3LnRvdXJuYW1hdGNoLmNvbVxyXG4gKiBAc2luY2UgICAgICAzLjExLjBcclxuICogQHNpbmNlICAgICAgMy4yMS4wIEFkZGVkIHN1cHBvcnQgZm9yIHNlcnZlciBzaWRlIERhdGFUYWJsZXMuXHJcbiAqXHJcbiAqIEBwYWNrYWdlICAgIFRvdXJuYW1hdGNoXHJcbiAqXHJcbiAqL1xyXG5pbXBvcnQgeyB0cm4gfSBmcm9tICcuL3RvdXJuYW1hdGNoLmpzJztcclxuXHJcbihmdW5jdGlvbiAoalF1ZXJ5LCAkKSB7XHJcbiAgICAndXNlIHN0cmljdCc7XHJcblxyXG4gICAgbGV0IG9wdGlvbnMgPSB0cm5fdGVhbXNfbGlzdF90YWJsZV9vcHRpb25zO1xyXG5cclxuICAgIGZ1bmN0aW9uIGhhbmRsZURlbGV0ZUNvbmZpcm0oKSB7XHJcbiAgICAgICAgbGV0IGxpbmtzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLWNvbmZpcm0tYWN0aW9uLWxpbmsnKTtcclxuICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKGxpbmtzLCBmdW5jdGlvbiAobGluaykge1xyXG4gICAgICAgICAgICBsaW5rLmFkZEV2ZW50TGlzdGVuZXIoJ3Rybi5jb25maXJtZWQuYWN0aW9uLmRlbGV0ZS10ZWFtJywgZnVuY3Rpb24gKGV2ZW50KSB7XHJcbiAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xyXG5cclxuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKGBtb2RhbCB3YXMgY29uZmlybWVkIGZvciBsaW5rICR7bGluay5kYXRhc2V0LnRlYW1JZH1gKTtcclxuICAgICAgICAgICAgICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgICAgIHhoci5vcGVuKCdERUxFVEUnLCBgJHtvcHRpb25zLmFwaV91cmx9dGVhbXMvJHtsaW5rLmRhdGFzZXQudGVhbUlkfWApO1xyXG4gICAgICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwNCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB3aW5kb3cubG9jYXRpb24ucmVsb2FkKCk7XHJcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbGV0IHJlc3BvbnNlID0gSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLWRlbGV0ZS10ZWFtLXJlc3BvbnNlJykuaW5uZXJIVE1MID0gYDxkaXYgY2xhc3M9XCJ0cm4tYWxlcnQgdHJuLWFsZXJ0LWRhbmdlclwiPjxzdHJvbmc+JHtvcHRpb25zLmxhbmd1YWdlLmZhaWx1cmV9PC9zdHJvbmc+OiAke3Jlc3BvbnNlLm1lc3NhZ2V9PC9kaXY+YDtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9O1xyXG5cclxuICAgICAgICAgICAgICAgIHhoci5zZW5kKCk7XHJcbiAgICAgICAgICAgIH0sIGZhbHNlKTtcclxuICAgICAgICB9KTtcclxuICAgIH1cclxuXHJcbiAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCd0cm4taHRtbC11cGRhdGVkJywgZnVuY3Rpb24oZSkge1xyXG4gICAgICAgICAgICBoYW5kbGVEZWxldGVDb25maXJtKCk7XHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgaGFuZGxlRGVsZXRlQ29uZmlybSgpO1xyXG5cclxuICAgICAgICBsZXQgY29sdW1uRGVmcyA9IFtcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0czogMCxcclxuICAgICAgICAgICAgICAgIG5hbWU6ICduYW1lJyxcclxuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ3Rybi10ZWFtcy10YWJsZS1uYW1lJyxcclxuICAgICAgICAgICAgICAgIHJlbmRlcjogZnVuY3Rpb24oZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGA8YSBocmVmPVwiJHtyb3cubGlua31cIj4ke3Jvdy5uYW1lfTwvYT5gO1xyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0czogMSxcclxuICAgICAgICAgICAgICAgIG5hbWU6ICdqb2luZWRfZGF0ZScsXHJcbiAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICd0cm4tdGVhbXMtdGFibGUtY3JlYXRlZCcsXHJcbiAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uKGRhdGEsIHR5cGUsIHJvdykge1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiByb3cuam9pbmVkX2RhdGUucmVuZGVyZWQ7XHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICB7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRzOiAyLFxyXG4gICAgICAgICAgICAgICAgbmFtZTogJ21lbWJlcnMnLFxyXG4gICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAndHJuLXRlYW1zLXRhYmxlLW1lbWJlcnMnLFxyXG4gICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbihkYXRhLCB0eXBlLCByb3cpIHtcclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gcm93Lm1lbWJlcnM7XHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICB9LFxyXG4gICAgICAgIF07XHJcblxyXG4gICAgICAgIGlmIChvcHRpb25zLnVzZXJfY2FwYWJpbGl0eSkge1xyXG4gICAgICAgICAgICBjb2x1bW5EZWZzLnB1c2goXHJcbiAgICAgICAgICAgICAgICB7XHJcbiAgICAgICAgICAgICAgICAgICAgdGFyZ2V0czogMyxcclxuICAgICAgICAgICAgICAgICAgICBuYW1lOiAnYWRtaW4nLFxyXG4gICAgICAgICAgICAgICAgICAgIHJlbmRlcjogZnVuY3Rpb24oZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnN0IG1lc3NhZ2UgPSBvcHRpb25zLmxhbmd1YWdlLmRlbGV0ZV9jb25maXJtLmZvcm1hdChyb3cubmFtZSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBgPGEgaHJlZj1cIiR7cm93Lmxpbmt9L2VkaXRcIj48aSBjbGFzcz1cImZhIGZhLWVkaXRcIj48L2k+PC9hPiBgICtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGA8YSBjbGFzcz1cInRybi1kZWxldGUtdGVhbS1hY3Rpb24gdHJuLWNvbmZpcm0tYWN0aW9uLWxpbmtcIiBkYXRhLXRlYW0taWQ9XCIke3Jvdy50ZWFtX2lkfVwiIGRhdGEtbW9kYWwtaWQ9XCJkZWxldGUtdGVhbVwiIGRhdGEtY29uZmlybS10aXRsZT1cIiR7b3B0aW9ucy5sYW5ndWFnZS5kZWxldGVfdGVhbX1cIiBkYXRhLWNvbmZpcm0tbWVzc2FnZT1cIiR7bWVzc2FnZX1cIiBocmVmPVwiI1wiIHRpdGxlPVwiJHtvcHRpb25zLmxhbmd1YWdlLmRlbGV0ZV90ZWFtfVwiPjxpIGNsYXNzPVwiZmEgZmEtdHJhc2hcIj48L2k+PC9hPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgICAgICAgICBvcmRlcmFibGU6IGZhbHNlLFxyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGpRdWVyeSgnI3Rybi10ZWFtcy10YWJsZScpXHJcbiAgICAgICAgICAgIC5vbigneGhyLmR0JywgZnVuY3Rpb24oIGUsIHNldHRpbmdzLCBqc29uLCB4aHIgKSB7XHJcbiAgICAgICAgICAgICAgICBqc29uLmRhdGEgPSBKU09OLnBhcnNlKEpTT04uc3RyaW5naWZ5KGpzb24pKTtcclxuICAgICAgICAgICAgICAgIGpzb24ucmVjb3Jkc1RvdGFsID0geGhyLmdldFJlc3BvbnNlSGVhZGVyKCdYLVdQLVRvdGFsJyk7XHJcbiAgICAgICAgICAgICAgICBqc29uLnJlY29yZHNGaWx0ZXJlZCA9IHhoci5nZXRSZXNwb25zZUhlYWRlcignVFJOLUZpbHRlcmVkJyk7XHJcbiAgICAgICAgICAgICAgICBqc29uLmxlbmd0aCA9IHhoci5nZXRSZXNwb25zZUhlYWRlcignWC1XUC1Ub3RhbFBhZ2VzJyk7XHJcbiAgICAgICAgICAgICAgICBqc29uLmRyYXcgPSB4aHIuZ2V0UmVzcG9uc2VIZWFkZXIoJ1RSTi1EcmF3Jyk7XHJcbiAgICAgICAgICAgIH0pXHJcbiAgICAgICAgICAgIC5EYXRhVGFibGUoe1xyXG4gICAgICAgICAgICAgICAgcHJvY2Vzc2luZzogdHJ1ZSxcclxuICAgICAgICAgICAgICAgIHNlcnZlclNpZGU6IHRydWUsXHJcbiAgICAgICAgICAgICAgICBsZW5ndGhNZW51OiBbWzI1LCA1MCwgMTAwLCAtMV0sIFsyNSwgNTAsIDEwMCwgJ0FsbCddXSxcclxuICAgICAgICAgICAgICAgIGxhbmd1YWdlOiBvcHRpb25zWyd0YWJsZV9sYW5ndWFnZSddLFxyXG4gICAgICAgICAgICAgICAgYXV0b1dpZHRoOiBmYWxzZSxcclxuICAgICAgICAgICAgICAgIGFqYXg6IHtcclxuICAgICAgICAgICAgICAgICAgICB1cmw6IGAke29wdGlvbnMuYXBpX3VybH10ZWFtcy8/X3dwbm9uY2U9JHtvcHRpb25zLnJlc3Rfbm9uY2V9Jl9lbWJlZGAsXHJcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ0dFVCcsXHJcbiAgICAgICAgICAgICAgICAgICAgZGF0YTogZnVuY3Rpb24oZGF0YSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBsZXQgc2VudCA9IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGRyYXc6IGRhdGEuZHJhdyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBhZ2U6IE1hdGguZmxvb3IoZGF0YS5zdGFydCAvIGRhdGEubGVuZ3RoKSxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBlcl9wYWdlOiBkYXRhLmxlbmd0aCxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNlYXJjaDogZGF0YS5zZWFyY2gudmFsdWUsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBvcmRlcmJ5OiBgJHtkYXRhLmNvbHVtbnNbZGF0YS5vcmRlclswXS5jb2x1bW5dLm5hbWV9LiR7ZGF0YS5vcmRlclswXS5kaXJ9YFxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9O1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAvL2NvbnNvbGUubG9nKHNlbnQpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gc2VudDtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICAgICAgb3JkZXI6IFtbIDEsICdhc2MnIF1dLFxyXG4gICAgICAgICAgICAgICAgY29sdW1uRGVmczogY29sdW1uRGVmcyxcclxuICAgICAgICAgICAgICAgIGRyYXdDYWxsYmFjazogZnVuY3Rpb24oIHNldHRpbmdzICkge1xyXG4gICAgICAgICAgICAgICAgICAgIGRvY3VtZW50LmRpc3BhdGNoRXZlbnQoIG5ldyBDdXN0b21FdmVudCggJ3Rybi1odG1sLXVwZGF0ZWQnLCB7ICdkZXRhaWwnOiAnVGhlIHRhYmxlIGh0bWwgaGFzIHVwZGF0ZWQuJyB9ICkpO1xyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgfSwgZmFsc2UpO1xyXG59KShqUXVlcnksIHRybik7IiwiJ3VzZSBzdHJpY3QnO1xyXG5jbGFzcyBUb3VybmFtYXRjaCB7XHJcblxyXG4gICAgY29uc3RydWN0b3IoKSB7XHJcbiAgICAgICAgdGhpcy5ldmVudHMgPSB7fTtcclxuICAgIH1cclxuXHJcbiAgICBwYXJhbShvYmplY3QsIHByZWZpeCkge1xyXG4gICAgICAgIGxldCBzdHIgPSBbXTtcclxuICAgICAgICBmb3IgKGxldCBwcm9wIGluIG9iamVjdCkge1xyXG4gICAgICAgICAgICBpZiAob2JqZWN0Lmhhc093blByb3BlcnR5KHByb3ApKSB7XHJcbiAgICAgICAgICAgICAgICBsZXQgayA9IHByZWZpeCA/IHByZWZpeCArIFwiW1wiICsgcHJvcCArIFwiXVwiIDogcHJvcDtcclxuICAgICAgICAgICAgICAgIGxldCB2ID0gb2JqZWN0W3Byb3BdO1xyXG4gICAgICAgICAgICAgICAgc3RyLnB1c2goKHYgIT09IG51bGwgJiYgdHlwZW9mIHYgPT09IFwib2JqZWN0XCIpID8gdGhpcy5wYXJhbSh2LCBrKSA6IGVuY29kZVVSSUNvbXBvbmVudChrKSArIFwiPVwiICsgZW5jb2RlVVJJQ29tcG9uZW50KHYpKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gc3RyLmpvaW4oXCImXCIpO1xyXG4gICAgfVxyXG5cclxuICAgIGV2ZW50KGV2ZW50TmFtZSkge1xyXG4gICAgICAgIGlmICghKGV2ZW50TmFtZSBpbiB0aGlzLmV2ZW50cykpIHtcclxuICAgICAgICAgICAgdGhpcy5ldmVudHNbZXZlbnROYW1lXSA9IG5ldyBFdmVudFRhcmdldChldmVudE5hbWUpO1xyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gdGhpcy5ldmVudHNbZXZlbnROYW1lXTtcclxuICAgIH1cclxuXHJcbiAgICBhdXRvY29tcGxldGUoaW5wdXQsIGRhdGFDYWxsYmFjaykge1xyXG4gICAgICAgIG5ldyBUb3VybmFtYXRjaF9BdXRvY29tcGxldGUoaW5wdXQsIGRhdGFDYWxsYmFjayk7XHJcbiAgICB9XHJcblxyXG4gICAgdWNmaXJzdChzKSB7XHJcbiAgICAgICAgaWYgKHR5cGVvZiBzICE9PSAnc3RyaW5nJykgcmV0dXJuICcnO1xyXG4gICAgICAgIHJldHVybiBzLmNoYXJBdCgwKS50b1VwcGVyQ2FzZSgpICsgcy5zbGljZSgxKTtcclxuICAgIH1cclxuXHJcbiAgICBvcmRpbmFsX3N1ZmZpeChudW1iZXIpIHtcclxuICAgICAgICBjb25zdCByZW1haW5kZXIgPSBudW1iZXIgJSAxMDA7XHJcblxyXG4gICAgICAgIGlmICgocmVtYWluZGVyIDwgMTEpIHx8IChyZW1haW5kZXIgPiAxMykpIHtcclxuICAgICAgICAgICAgc3dpdGNoIChyZW1haW5kZXIgJSAxMCkge1xyXG4gICAgICAgICAgICAgICAgY2FzZSAxOiByZXR1cm4gJ3N0JztcclxuICAgICAgICAgICAgICAgIGNhc2UgMjogcmV0dXJuICduZCc7XHJcbiAgICAgICAgICAgICAgICBjYXNlIDM6IHJldHVybiAncmQnO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiAndGgnO1xyXG4gICAgfVxyXG5cclxuICAgIHRhYnMoZWxlbWVudCkge1xyXG4gICAgICAgIGNvbnN0IHRhYnMgPSBlbGVtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1uYXYtbGluaycpO1xyXG4gICAgICAgIGNvbnN0IHBhbmVzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLXRhYi1wYW5lJyk7XHJcbiAgICAgICAgY29uc3QgY2xlYXJBY3RpdmUgPSAoKSA9PiB7XHJcbiAgICAgICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwodGFicywgKHRhYikgPT4ge1xyXG4gICAgICAgICAgICAgICAgdGFiLmNsYXNzTGlzdC5yZW1vdmUoJ3Rybi1uYXYtYWN0aXZlJyk7XHJcbiAgICAgICAgICAgICAgICB0YWIuYXJpYVNlbGVjdGVkID0gZmFsc2U7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHBhbmVzLCBwYW5lID0+IHBhbmUuY2xhc3NMaXN0LnJlbW92ZSgndHJuLXRhYi1hY3RpdmUnKSk7XHJcbiAgICAgICAgfTtcclxuICAgICAgICBjb25zdCBzZXRBY3RpdmUgPSAodGFyZ2V0SWQpID0+IHtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0VGFiID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignYVtocmVmPVwiIycgKyB0YXJnZXRJZCArICdcIl0udHJuLW5hdi1saW5rJyk7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFBhbmVJZCA9IHRhcmdldFRhYiAmJiB0YXJnZXRUYWIuZGF0YXNldCAmJiB0YXJnZXRUYWIuZGF0YXNldC50YXJnZXQgfHwgZmFsc2U7XHJcblxyXG4gICAgICAgICAgICBpZiAodGFyZ2V0UGFuZUlkKSB7XHJcbiAgICAgICAgICAgICAgICBjbGVhckFjdGl2ZSgpO1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0VGFiLmNsYXNzTGlzdC5hZGQoJ3Rybi1uYXYtYWN0aXZlJyk7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRUYWIuYXJpYVNlbGVjdGVkID0gdHJ1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0YXJnZXRQYW5lSWQpLmNsYXNzTGlzdC5hZGQoJ3Rybi10YWItYWN0aXZlJyk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9O1xyXG4gICAgICAgIGNvbnN0IHRhYkNsaWNrID0gKGV2ZW50KSA9PiB7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFRhYiA9IGV2ZW50LmN1cnJlbnRUYXJnZXQ7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFBhbmVJZCA9IHRhcmdldFRhYiAmJiB0YXJnZXRUYWIuZGF0YXNldCAmJiB0YXJnZXRUYWIuZGF0YXNldC50YXJnZXQgfHwgZmFsc2U7XHJcblxyXG4gICAgICAgICAgICBpZiAodGFyZ2V0UGFuZUlkKSB7XHJcbiAgICAgICAgICAgICAgICBzZXRBY3RpdmUodGFyZ2V0UGFuZUlkKTtcclxuICAgICAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9O1xyXG5cclxuICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHRhYnMsICh0YWIpID0+IHtcclxuICAgICAgICAgICAgdGFiLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGFiQ2xpY2spO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICBpZiAobG9jYXRpb24uaGFzaCkge1xyXG4gICAgICAgICAgICBzZXRBY3RpdmUobG9jYXRpb24uaGFzaC5zdWJzdHIoMSkpO1xyXG4gICAgICAgIH0gZWxzZSBpZiAodGFicy5sZW5ndGggPiAwKSB7XHJcbiAgICAgICAgICAgIHNldEFjdGl2ZSh0YWJzWzBdLmRhdGFzZXQudGFyZ2V0KTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcblxyXG59XHJcblxyXG4vL3Rybi5pbml0aWFsaXplKCk7XHJcbmlmICghd2luZG93LnRybl9vYmpfaW5zdGFuY2UpIHtcclxuICAgIHdpbmRvdy50cm5fb2JqX2luc3RhbmNlID0gbmV3IFRvdXJuYW1hdGNoKCk7XHJcblxyXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCBmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgIGNvbnN0IHRhYlZpZXdzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLW5hdicpO1xyXG5cclxuICAgICAgICBBcnJheS5mcm9tKHRhYlZpZXdzKS5mb3JFYWNoKCh0YWIpID0+IHtcclxuICAgICAgICAgICAgdHJuLnRhYnModGFiKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgY29uc3QgZHJvcGRvd25zID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLWRyb3Bkb3duLXRvZ2dsZScpO1xyXG4gICAgICAgIGNvbnN0IGhhbmRsZURyb3Bkb3duQ2xvc2UgPSAoKSA9PiB7XHJcbiAgICAgICAgICAgIEFycmF5LmZyb20oZHJvcGRvd25zKS5mb3JFYWNoKChkcm9wZG93bikgPT4ge1xyXG4gICAgICAgICAgICAgICAgZHJvcGRvd24ubmV4dEVsZW1lbnRTaWJsaW5nLmNsYXNzTGlzdC5yZW1vdmUoJ3Rybi1zaG93Jyk7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICBkb2N1bWVudC5yZW1vdmVFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgaGFuZGxlRHJvcGRvd25DbG9zZSwgZmFsc2UpO1xyXG4gICAgICAgIH07XHJcblxyXG4gICAgICAgIEFycmF5LmZyb20oZHJvcGRvd25zKS5mb3JFYWNoKChkcm9wZG93bikgPT4ge1xyXG4gICAgICAgICAgICBkcm9wZG93bi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uKGUpIHtcclxuICAgICAgICAgICAgICAgIGUuc3RvcFByb3BhZ2F0aW9uKCk7XHJcbiAgICAgICAgICAgICAgICB0aGlzLm5leHRFbGVtZW50U2libGluZy5jbGFzc0xpc3QuYWRkKCd0cm4tc2hvdycpO1xyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGhhbmRsZURyb3Bkb3duQ2xvc2UsIGZhbHNlKTtcclxuICAgICAgICAgICAgfSwgZmFsc2UpO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgIH0sIGZhbHNlKTtcclxufVxyXG5leHBvcnQgbGV0IHRybiA9IHdpbmRvdy50cm5fb2JqX2luc3RhbmNlO1xyXG5cclxuY2xhc3MgVG91cm5hbWF0Y2hfQXV0b2NvbXBsZXRlIHtcclxuXHJcbiAgICAvLyBjdXJyZW50Rm9jdXM7XHJcbiAgICAvL1xyXG4gICAgLy8gbmFtZUlucHV0O1xyXG4gICAgLy9cclxuICAgIC8vIHNlbGY7XHJcblxyXG4gICAgY29uc3RydWN0b3IoaW5wdXQsIGRhdGFDYWxsYmFjaykge1xyXG4gICAgICAgIC8vIHRoaXMuc2VsZiA9IHRoaXM7XHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQgPSBpbnB1dDtcclxuXHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcihcImlucHV0XCIsICgpID0+IHtcclxuICAgICAgICAgICAgbGV0IGEsIGIsIGksIHZhbCA9IHRoaXMubmFtZUlucHV0LnZhbHVlOy8vdGhpcy52YWx1ZTtcclxuICAgICAgICAgICAgbGV0IHBhcmVudCA9IHRoaXMubmFtZUlucHV0LnBhcmVudE5vZGU7Ly90aGlzLnBhcmVudE5vZGU7XHJcblxyXG4gICAgICAgICAgICAvLyBsZXQgcCA9IG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpID0+IHtcclxuICAgICAgICAgICAgLy8gICAgIC8qIG5lZWQgdG8gcXVlcnkgc2VydmVyIGZvciBuYW1lcyBoZXJlLiAqL1xyXG4gICAgICAgICAgICAvLyAgICAgbGV0IHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLm9wZW4oJ0dFVCcsIG9wdGlvbnMuYXBpX3VybCArICdwbGF5ZXJzLz9zZWFyY2g9JyArIHZhbCArICcmcGVyX3BhZ2U9NScpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMCkge1xyXG4gICAgICAgICAgICAvLyAgICAgICAgICAgICAvLyByZXNvbHZlKEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKS5tYXAoKHBsYXllcikgPT4ge3JldHVybiB7ICd2YWx1ZSc6IHBsYXllci5pZCwgJ3RleHQnOiBwbGF5ZXIubmFtZSB9O30pKTtcclxuICAgICAgICAgICAgLy8gICAgICAgICAgICAgcmVzb2x2ZShKU09OLnBhcnNlKHhoci5yZXNwb25zZSkubWFwKChwbGF5ZXIpID0+IHtyZXR1cm4gcGxheWVyLm5hbWU7fSkpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgICAgIHJlamVjdCgpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gICAgIH07XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIuc2VuZCgpO1xyXG4gICAgICAgICAgICAvLyB9KTtcclxuICAgICAgICAgICAgZGF0YUNhbGxiYWNrKHZhbCkudGhlbigoZGF0YSkgPT4gey8vcC50aGVuKChkYXRhKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhkYXRhKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmNsb3NlIGFueSBhbHJlYWR5IG9wZW4gbGlzdHMgb2YgYXV0by1jb21wbGV0ZWQgdmFsdWVzKi9cclxuICAgICAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cygpO1xyXG4gICAgICAgICAgICAgICAgaWYgKCF2YWwpIHsgcmV0dXJuIGZhbHNlO31cclxuICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudEZvY3VzID0gLTE7XHJcblxyXG4gICAgICAgICAgICAgICAgLypjcmVhdGUgYSBESVYgZWxlbWVudCB0aGF0IHdpbGwgY29udGFpbiB0aGUgaXRlbXMgKHZhbHVlcyk6Ki9cclxuICAgICAgICAgICAgICAgIGEgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiRElWXCIpO1xyXG4gICAgICAgICAgICAgICAgYS5zZXRBdHRyaWJ1dGUoXCJpZFwiLCB0aGlzLm5hbWVJbnB1dC5pZCArIFwiLWF1dG8tY29tcGxldGUtbGlzdFwiKTtcclxuICAgICAgICAgICAgICAgIGEuc2V0QXR0cmlidXRlKFwiY2xhc3NcIiwgXCJ0cm4tYXV0by1jb21wbGV0ZS1pdGVtc1wiKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmFwcGVuZCB0aGUgRElWIGVsZW1lbnQgYXMgYSBjaGlsZCBvZiB0aGUgYXV0by1jb21wbGV0ZSBjb250YWluZXI6Ki9cclxuICAgICAgICAgICAgICAgIHBhcmVudC5hcHBlbmRDaGlsZChhKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmZvciBlYWNoIGl0ZW0gaW4gdGhlIGFycmF5Li4uKi9cclxuICAgICAgICAgICAgICAgIGZvciAoaSA9IDA7IGkgPCBkYXRhLmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IHRleHQsIHZhbHVlO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAvKiBXaGljaCBmb3JtYXQgZGlkIHRoZXkgZ2l2ZSB1cy4gKi9cclxuICAgICAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGRhdGFbaV0gPT09ICdvYmplY3QnKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHRleHQgPSBkYXRhW2ldWyd0ZXh0J107XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlID0gZGF0YVtpXVsndmFsdWUnXTtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB0ZXh0ID0gZGF0YVtpXTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSBkYXRhW2ldO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICAgICAgLypjaGVjayBpZiB0aGUgaXRlbSBzdGFydHMgd2l0aCB0aGUgc2FtZSBsZXR0ZXJzIGFzIHRoZSB0ZXh0IGZpZWxkIHZhbHVlOiovXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHRleHQuc3Vic3RyKDAsIHZhbC5sZW5ndGgpLnRvVXBwZXJDYXNlKCkgPT09IHZhbC50b1VwcGVyQ2FzZSgpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qY3JlYXRlIGEgRElWIGVsZW1lbnQgZm9yIGVhY2ggbWF0Y2hpbmcgZWxlbWVudDoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcIkRJVlwiKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgLyptYWtlIHRoZSBtYXRjaGluZyBsZXR0ZXJzIGJvbGQ6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgPSBcIjxzdHJvbmc+XCIgKyB0ZXh0LnN1YnN0cigwLCB2YWwubGVuZ3RoKSArIFwiPC9zdHJvbmc+XCI7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuaW5uZXJIVE1MICs9IHRleHQuc3Vic3RyKHZhbC5sZW5ndGgpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgLyppbnNlcnQgYSBpbnB1dCBmaWVsZCB0aGF0IHdpbGwgaG9sZCB0aGUgY3VycmVudCBhcnJheSBpdGVtJ3MgdmFsdWU6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgKz0gXCI8aW5wdXQgdHlwZT0naGlkZGVuJyB2YWx1ZT0nXCIgKyB2YWx1ZSArIFwiJz5cIjtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuZGF0YXNldC52YWx1ZSA9IHZhbHVlO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmRhdGFzZXQudGV4dCA9IHRleHQ7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiB3aGVuIHNvbWVvbmUgY2xpY2tzIG9uIHRoZSBpdGVtIHZhbHVlIChESVYgZWxlbWVudCk6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKGUpID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKGBpdGVtIGNsaWNrZWQgd2l0aCB2YWx1ZSAke2UuY3VycmVudFRhcmdldC5kYXRhc2V0LnZhbHVlfWApO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8qIGluc2VydCB0aGUgdmFsdWUgZm9yIHRoZSBhdXRvY29tcGxldGUgdGV4dCBmaWVsZDogKi9cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LnZhbHVlID0gZS5jdXJyZW50VGFyZ2V0LmRhdGFzZXQudGV4dDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LmRhdGFzZXQuc2VsZWN0ZWRJZCA9IGUuY3VycmVudFRhcmdldC5kYXRhc2V0LnZhbHVlO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8qIGNsb3NlIHRoZSBsaXN0IG9mIGF1dG9jb21wbGV0ZWQgdmFsdWVzLCAob3IgYW55IG90aGVyIG9wZW4gbGlzdHMgb2YgYXV0b2NvbXBsZXRlZCB2YWx1ZXM6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cygpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LmRpc3BhdGNoRXZlbnQobmV3IEV2ZW50KCdjaGFuZ2UnKSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBhLmFwcGVuZENoaWxkKGIpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHByZXNzZXMgYSBrZXkgb24gdGhlIGtleWJvYXJkOiovXHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcihcImtleWRvd25cIiwgKGUpID0+IHtcclxuICAgICAgICAgICAgbGV0IHggPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0aGlzLm5hbWVJbnB1dC5pZCArIFwiLWF1dG8tY29tcGxldGUtbGlzdFwiKTtcclxuICAgICAgICAgICAgaWYgKHgpIHggPSB4LmdldEVsZW1lbnRzQnlUYWdOYW1lKFwiZGl2XCIpO1xyXG4gICAgICAgICAgICBpZiAoZS5rZXlDb2RlID09PSA0MCkge1xyXG4gICAgICAgICAgICAgICAgLypJZiB0aGUgYXJyb3cgRE9XTiBrZXkgaXMgcHJlc3NlZCxcclxuICAgICAgICAgICAgICAgICBpbmNyZWFzZSB0aGUgY3VycmVudEZvY3VzIHZhcmlhYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmN1cnJlbnRGb2N1cysrO1xyXG4gICAgICAgICAgICAgICAgLyphbmQgYW5kIG1ha2UgdGhlIGN1cnJlbnQgaXRlbSBtb3JlIHZpc2libGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuYWRkQWN0aXZlKHgpO1xyXG4gICAgICAgICAgICB9IGVsc2UgaWYgKGUua2V5Q29kZSA9PT0gMzgpIHsgLy91cFxyXG4gICAgICAgICAgICAgICAgLypJZiB0aGUgYXJyb3cgVVAga2V5IGlzIHByZXNzZWQsXHJcbiAgICAgICAgICAgICAgICAgZGVjcmVhc2UgdGhlIGN1cnJlbnRGb2N1cyB2YXJpYWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50Rm9jdXMtLTtcclxuICAgICAgICAgICAgICAgIC8qYW5kIGFuZCBtYWtlIHRoZSBjdXJyZW50IGl0ZW0gbW9yZSB2aXNpYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmFkZEFjdGl2ZSh4KTtcclxuICAgICAgICAgICAgfSBlbHNlIGlmIChlLmtleUNvZGUgPT09IDEzKSB7XHJcbiAgICAgICAgICAgICAgICAvKklmIHRoZSBFTlRFUiBrZXkgaXMgcHJlc3NlZCwgcHJldmVudCB0aGUgZm9ybSBmcm9tIGJlaW5nIHN1Ym1pdHRlZCwqL1xyXG4gICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzID4gLTEpIHtcclxuICAgICAgICAgICAgICAgICAgICAvKmFuZCBzaW11bGF0ZSBhIGNsaWNrIG9uIHRoZSBcImFjdGl2ZVwiIGl0ZW06Ki9cclxuICAgICAgICAgICAgICAgICAgICBpZiAoeCkgeFt0aGlzLmN1cnJlbnRGb2N1c10uY2xpY2soKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiB3aGVuIHNvbWVvbmUgY2xpY2tzIGluIHRoZSBkb2N1bWVudDoqL1xyXG4gICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICB0aGlzLmNsb3NlQWxsTGlzdHMoZS50YXJnZXQpO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG5cclxuICAgIGFkZEFjdGl2ZSh4KSB7XHJcbiAgICAgICAgLyphIGZ1bmN0aW9uIHRvIGNsYXNzaWZ5IGFuIGl0ZW0gYXMgXCJhY3RpdmVcIjoqL1xyXG4gICAgICAgIGlmICgheCkgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgIC8qc3RhcnQgYnkgcmVtb3ZpbmcgdGhlIFwiYWN0aXZlXCIgY2xhc3Mgb24gYWxsIGl0ZW1zOiovXHJcbiAgICAgICAgdGhpcy5yZW1vdmVBY3RpdmUoeCk7XHJcbiAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzID49IHgubGVuZ3RoKSB0aGlzLmN1cnJlbnRGb2N1cyA9IDA7XHJcbiAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzIDwgMCkgdGhpcy5jdXJyZW50Rm9jdXMgPSAoeC5sZW5ndGggLSAxKTtcclxuICAgICAgICAvKmFkZCBjbGFzcyBcImF1dG9jb21wbGV0ZS1hY3RpdmVcIjoqL1xyXG4gICAgICAgIHhbdGhpcy5jdXJyZW50Rm9jdXNdLmNsYXNzTGlzdC5hZGQoXCJ0cm4tYXV0by1jb21wbGV0ZS1hY3RpdmVcIik7XHJcbiAgICB9XHJcblxyXG4gICAgcmVtb3ZlQWN0aXZlKHgpIHtcclxuICAgICAgICAvKmEgZnVuY3Rpb24gdG8gcmVtb3ZlIHRoZSBcImFjdGl2ZVwiIGNsYXNzIGZyb20gYWxsIGF1dG9jb21wbGV0ZSBpdGVtczoqL1xyXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgeC5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICB4W2ldLmNsYXNzTGlzdC5yZW1vdmUoXCJ0cm4tYXV0by1jb21wbGV0ZS1hY3RpdmVcIik7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIGNsb3NlQWxsTGlzdHMoZWxlbWVudCkge1xyXG4gICAgICAgIGNvbnNvbGUubG9nKFwiY2xvc2UgYWxsIGxpc3RzXCIpO1xyXG4gICAgICAgIC8qY2xvc2UgYWxsIGF1dG9jb21wbGV0ZSBsaXN0cyBpbiB0aGUgZG9jdW1lbnQsXHJcbiAgICAgICAgIGV4Y2VwdCB0aGUgb25lIHBhc3NlZCBhcyBhbiBhcmd1bWVudDoqL1xyXG4gICAgICAgIGxldCB4ID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShcInRybi1hdXRvLWNvbXBsZXRlLWl0ZW1zXCIpO1xyXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgeC5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICBpZiAoZWxlbWVudCAhPT0geFtpXSAmJiBlbGVtZW50ICE9PSB0aGlzLm5hbWVJbnB1dCkge1xyXG4gICAgICAgICAgICAgICAgeFtpXS5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKHhbaV0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG59XHJcblxyXG4vLyBGaXJzdCwgY2hlY2tzIGlmIGl0IGlzbid0IGltcGxlbWVudGVkIHlldC5cclxuaWYgKCFTdHJpbmcucHJvdG90eXBlLmZvcm1hdCkge1xyXG4gICAgU3RyaW5nLnByb3RvdHlwZS5mb3JtYXQgPSBmdW5jdGlvbigpIHtcclxuICAgICAgICBjb25zdCBhcmdzID0gYXJndW1lbnRzO1xyXG4gICAgICAgIHJldHVybiB0aGlzLnJlcGxhY2UoL3soXFxkKyl9L2csIGZ1bmN0aW9uKG1hdGNoLCBudW1iZXIpIHtcclxuICAgICAgICAgICAgcmV0dXJuIHR5cGVvZiBhcmdzW251bWJlcl0gIT09ICd1bmRlZmluZWQnXHJcbiAgICAgICAgICAgICAgICA/IGFyZ3NbbnVtYmVyXVxyXG4gICAgICAgICAgICAgICAgOiBtYXRjaFxyXG4gICAgICAgICAgICAgICAgO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfTtcclxufSJdLCJzb3VyY2VSb290IjoiIn0=