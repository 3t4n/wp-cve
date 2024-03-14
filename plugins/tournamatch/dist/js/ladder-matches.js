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
/******/ 	return __webpack_require__(__webpack_require__.s = 19);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/ladder-matches.js":
/*!**********************************!*\
  !*** ./src/js/ladder-matches.js ***!
  \**********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./tournamatch.js */ "./src/js/tournamatch.js");
/**
 * Handles client scripting for the ladder matches page.
 *
 * @link       https://www.tournamatch.com
 * @since      3.11.0
 * @since      3.21.0 Added support for server side DataTables.
 *
 * @package    Tournamatch
 *
 */


(function ($, trn) {
  var options = trn_ladder_matches_options;

  function handleDeleteConfirm() {
    var links = document.getElementsByClassName('trn-confirm-action-link');
    Array.prototype.forEach.call(links, function (link) {
      link.addEventListener('trn.confirmed.action.delete-match', function (event) {
        event.preventDefault();
        console.log("modal was confirmed for link ".concat(link.dataset.matchId));
        var xhr = new XMLHttpRequest();
        xhr.open('DELETE', "".concat(options.api_url, "matches/").concat(link.dataset.matchId));
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

        xhr.onload = function () {
          if (xhr.status === 204) {
            window.location.reload();
          } else {
            var response = JSON.parse(xhr.response);
            document.getElementById('trn-delete-match-response').innerHTML = "<div class=\"trn-alert trn-alert-danger\"><strong>".concat(options.language.failure, "</strong>: ").concat(response.message, "</div>");
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
      name: 'result',
      className: 'trn-ladder-matches-table-result',
      render: function render(data, type, row) {
        return row.match_result;
      },
      orderable: false
    }, {
      targets: 1,
      name: 'match_date',
      className: 'trn-ladder-matches-table-date',
      render: function render(data, type, row) {
        return row.match_date.rendered;
      }
    }, {
      targets: 2,
      name: 'details',
      className: 'trn-challenges-table-status',
      render: function render(data, type, row) {
        var links = [];
        links.push("<a href=\"".concat(row.link, "\" title=\"").concat(options.language.view_match_details, "\"><i class=\"fa fa-info\"></i></a>"));

        if (options.user_capability) {
          links.push("<a href=\"".concat(options.ladder_edit).concat(row.match_id, "\" title=\"").concat(options.language.edit_match, "\"><i class=\"fa fa-edit\"></i></a>"));
          links.push("<a class=\"trn-confirm-action-link trn-delete-match-action\" data-match-id=\"".concat(row.match_id, "\" data-confirm-title=\"").concat(options.language.delete_match, "\" data-confirm-message=\"").concat(options.language.delete_confirm.format(row.match_id), "\" data-modal-id=\"delete-match\" href=\"#\" title=\"").concat(options.language.delete_match, "\"><i class=\"fa fa-times\"></i></a>"));
        }

        return links.join(" ");
      },
      orderable: false
    }];
    $('#trn-ladder-matches-table').on('xhr.dt', function (e, settings, json, xhr) {
      json.data = JSON.parse(JSON.stringify(json));
      json.recordsTotal = xhr.getResponseHeader('X-WP-Total');
      json.recordsFiltered = xhr.getResponseHeader('TRN-Filtered');
      json.length = xhr.getResponseHeader('X-WP-TotalPages');
      json.draw = xhr.getResponseHeader('TRN-Draw');
    }).DataTable({
      processing: true,
      serverSide: true,
      lengthMenu: [[25, 50, 100, -1], [25, 50, 100, 'All']],
      language: options.table_language,
      autoWidth: false,
      ajax: {
        url: "".concat(options.api_url, "matches/?competition_type=ladders&competition_id=").concat(options.ladder_id, "&_wpnonce=").concat(options.rest_nonce, "&_embed"),
        type: 'GET',
        data: function data(_data) {
          var sent = {
            draw: _data.draw,
            page: Math.floor(_data.start / _data.length),
            per_page: _data.length,
            search: _data.search.value,
            orderby: "".concat(_data.columns[_data.order[0].column].name, ".").concat(_data.order[0].dir)
          };
          return sent;
        }
      },
      order: [[1, 'desc']],
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

/***/ 19:
/*!****************************************!*\
  !*** multi ./src/js/ladder-matches.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\ladder-matches.js */"./src/js/ladder-matches.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL2xhZGRlci1tYXRjaGVzLmpzIiwid2VicGFjazovLy8uL3NyYy9qcy90b3VybmFtYXRjaC5qcyJdLCJuYW1lcyI6WyIkIiwidHJuIiwib3B0aW9ucyIsInRybl9sYWRkZXJfbWF0Y2hlc19vcHRpb25zIiwiaGFuZGxlRGVsZXRlQ29uZmlybSIsImxpbmtzIiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50c0J5Q2xhc3NOYW1lIiwiQXJyYXkiLCJwcm90b3R5cGUiLCJmb3JFYWNoIiwiY2FsbCIsImxpbmsiLCJhZGRFdmVudExpc3RlbmVyIiwiZXZlbnQiLCJwcmV2ZW50RGVmYXVsdCIsImNvbnNvbGUiLCJsb2ciLCJkYXRhc2V0IiwibWF0Y2hJZCIsInhociIsIlhNTEh0dHBSZXF1ZXN0Iiwib3BlbiIsImFwaV91cmwiLCJzZXRSZXF1ZXN0SGVhZGVyIiwicmVzdF9ub25jZSIsIm9ubG9hZCIsInN0YXR1cyIsIndpbmRvdyIsImxvY2F0aW9uIiwicmVsb2FkIiwicmVzcG9uc2UiLCJKU09OIiwicGFyc2UiLCJnZXRFbGVtZW50QnlJZCIsImlubmVySFRNTCIsImxhbmd1YWdlIiwiZmFpbHVyZSIsIm1lc3NhZ2UiLCJzZW5kIiwiZSIsImNvbHVtbkRlZnMiLCJ0YXJnZXRzIiwibmFtZSIsImNsYXNzTmFtZSIsInJlbmRlciIsImRhdGEiLCJ0eXBlIiwicm93IiwibWF0Y2hfcmVzdWx0Iiwib3JkZXJhYmxlIiwibWF0Y2hfZGF0ZSIsInJlbmRlcmVkIiwicHVzaCIsInZpZXdfbWF0Y2hfZGV0YWlscyIsInVzZXJfY2FwYWJpbGl0eSIsImxhZGRlcl9lZGl0IiwibWF0Y2hfaWQiLCJlZGl0X21hdGNoIiwiZGVsZXRlX21hdGNoIiwiZGVsZXRlX2NvbmZpcm0iLCJmb3JtYXQiLCJqb2luIiwib24iLCJzZXR0aW5ncyIsImpzb24iLCJzdHJpbmdpZnkiLCJyZWNvcmRzVG90YWwiLCJnZXRSZXNwb25zZUhlYWRlciIsInJlY29yZHNGaWx0ZXJlZCIsImxlbmd0aCIsImRyYXciLCJEYXRhVGFibGUiLCJwcm9jZXNzaW5nIiwic2VydmVyU2lkZSIsImxlbmd0aE1lbnUiLCJ0YWJsZV9sYW5ndWFnZSIsImF1dG9XaWR0aCIsImFqYXgiLCJ1cmwiLCJsYWRkZXJfaWQiLCJzZW50IiwicGFnZSIsIk1hdGgiLCJmbG9vciIsInN0YXJ0IiwicGVyX3BhZ2UiLCJzZWFyY2giLCJ2YWx1ZSIsIm9yZGVyYnkiLCJjb2x1bW5zIiwib3JkZXIiLCJjb2x1bW4iLCJkaXIiLCJkcmF3Q2FsbGJhY2siLCJkaXNwYXRjaEV2ZW50IiwiQ3VzdG9tRXZlbnQiLCJqUXVlcnkiLCJUb3VybmFtYXRjaCIsImV2ZW50cyIsIm9iamVjdCIsInByZWZpeCIsInN0ciIsInByb3AiLCJoYXNPd25Qcm9wZXJ0eSIsImsiLCJ2IiwicGFyYW0iLCJlbmNvZGVVUklDb21wb25lbnQiLCJldmVudE5hbWUiLCJFdmVudFRhcmdldCIsImlucHV0IiwiZGF0YUNhbGxiYWNrIiwiVG91cm5hbWF0Y2hfQXV0b2NvbXBsZXRlIiwicyIsImNoYXJBdCIsInRvVXBwZXJDYXNlIiwic2xpY2UiLCJudW1iZXIiLCJyZW1haW5kZXIiLCJlbGVtZW50IiwidGFicyIsInBhbmVzIiwiY2xlYXJBY3RpdmUiLCJ0YWIiLCJjbGFzc0xpc3QiLCJyZW1vdmUiLCJhcmlhU2VsZWN0ZWQiLCJwYW5lIiwic2V0QWN0aXZlIiwidGFyZ2V0SWQiLCJ0YXJnZXRUYWIiLCJxdWVyeVNlbGVjdG9yIiwidGFyZ2V0UGFuZUlkIiwidGFyZ2V0IiwiYWRkIiwidGFiQ2xpY2siLCJjdXJyZW50VGFyZ2V0IiwiaGFzaCIsInN1YnN0ciIsInRybl9vYmpfaW5zdGFuY2UiLCJ0YWJWaWV3cyIsImZyb20iLCJkcm9wZG93bnMiLCJoYW5kbGVEcm9wZG93bkNsb3NlIiwiZHJvcGRvd24iLCJuZXh0RWxlbWVudFNpYmxpbmciLCJyZW1vdmVFdmVudExpc3RlbmVyIiwic3RvcFByb3BhZ2F0aW9uIiwibmFtZUlucHV0IiwiYSIsImIiLCJpIiwidmFsIiwicGFyZW50IiwicGFyZW50Tm9kZSIsInRoZW4iLCJjbG9zZUFsbExpc3RzIiwiY3VycmVudEZvY3VzIiwiY3JlYXRlRWxlbWVudCIsInNldEF0dHJpYnV0ZSIsImlkIiwiYXBwZW5kQ2hpbGQiLCJ0ZXh0Iiwic2VsZWN0ZWRJZCIsIkV2ZW50IiwieCIsImdldEVsZW1lbnRzQnlUYWdOYW1lIiwia2V5Q29kZSIsImFkZEFjdGl2ZSIsImNsaWNrIiwicmVtb3ZlQWN0aXZlIiwicmVtb3ZlQ2hpbGQiLCJTdHJpbmciLCJhcmdzIiwiYXJndW1lbnRzIiwicmVwbGFjZSIsIm1hdGNoIl0sIm1hcHBpbmdzIjoiO1FBQUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7OztRQUdBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSwwQ0FBMEMsZ0NBQWdDO1FBQzFFO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0Esd0RBQXdELGtCQUFrQjtRQUMxRTtRQUNBLGlEQUFpRCxjQUFjO1FBQy9EOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQSx5Q0FBeUMsaUNBQWlDO1FBQzFFLGdIQUFnSCxtQkFBbUIsRUFBRTtRQUNySTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLDJCQUEyQiwwQkFBMEIsRUFBRTtRQUN2RCxpQ0FBaUMsZUFBZTtRQUNoRDtRQUNBO1FBQ0E7O1FBRUE7UUFDQSxzREFBc0QsK0RBQStEOztRQUVySDtRQUNBOzs7UUFHQTtRQUNBOzs7Ozs7Ozs7Ozs7O0FDbEZBO0FBQUE7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVDLFdBQVVBLENB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YixFQUFnQkEsQ0FBQyxHQUFHYyxDQUFDLENBQUNoRyxNQUF0QixFQUE4QmtGLENBQUMsRUFBL0IsRUFBbUM7QUFDL0IsWUFBSWhDLE9BQU8sS0FBSzhDLENBQUMsQ0FBQ2QsQ0FBRCxDQUFiLElBQW9CaEMsT0FBTyxLQUFLLEtBQUs2QixTQUF6QyxFQUFvRDtBQUNoRGlCLFdBQUMsQ0FBQ2QsQ0FBRCxDQUFELENBQUtHLFVBQUwsQ0FBZ0JpQixXQUFoQixDQUE0Qk4sQ0FBQyxDQUFDZCxDQUFELENBQTdCO0FBQ0g7QUFDSjtBQUNKOzs7O0tBR0w7OztBQUNBLElBQUksQ0FBQ3FCLE1BQU0sQ0FBQ3BLLFNBQVAsQ0FBaUJvRCxNQUF0QixFQUE4QjtBQUMxQmdILFFBQU0sQ0FBQ3BLLFNBQVAsQ0FBaUJvRCxNQUFqQixHQUEwQixZQUFXO0FBQ2pDLFFBQU1pSCxJQUFJLEdBQUdDLFNBQWI7QUFDQSxXQUFPLEtBQUtDLE9BQUwsQ0FBYSxVQUFiLEVBQXlCLFVBQVNDLEtBQVQsRUFBZ0IzRCxNQUFoQixFQUF3QjtBQUNwRCxhQUFPLE9BQU93RCxJQUFJLENBQUN4RCxNQUFELENBQVgsS0FBd0IsV0FBeEIsR0FDRHdELElBQUksQ0FBQ3hELE1BQUQsQ0FESCxHQUVEMkQsS0FGTjtBQUlILEtBTE0sQ0FBUDtBQU1ILEdBUkQ7QUFTSCxDIiwiZmlsZSI6ImxhZGRlci1tYXRjaGVzLmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7IGVudW1lcmFibGU6IHRydWUsIGdldDogZ2V0dGVyIH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBkZWZpbmUgX19lc01vZHVsZSBvbiBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIgPSBmdW5jdGlvbihleHBvcnRzKSB7XG4gXHRcdGlmKHR5cGVvZiBTeW1ib2wgIT09ICd1bmRlZmluZWQnICYmIFN5bWJvbC50b1N0cmluZ1RhZykge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBTeW1ib2wudG9TdHJpbmdUYWcsIHsgdmFsdWU6ICdNb2R1bGUnIH0pO1xuIFx0XHR9XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCAnX19lc01vZHVsZScsIHsgdmFsdWU6IHRydWUgfSk7XG4gXHR9O1xuXG4gXHQvLyBjcmVhdGUgYSBmYWtlIG5hbWVzcGFjZSBvYmplY3RcbiBcdC8vIG1vZGUgJiAxOiB2YWx1ZSBpcyBhIG1vZHVsZSBpZCwgcmVxdWlyZSBpdFxuIFx0Ly8gbW9kZSAmIDI6IG1lcmdlIGFsbCBwcm9wZXJ0aWVzIG9mIHZhbHVlIGludG8gdGhlIG5zXG4gXHQvLyBtb2RlICYgNDogcmV0dXJuIHZhbHVlIHdoZW4gYWxyZWFkeSBucyBvYmplY3RcbiBcdC8vIG1vZGUgJiA4fDE6IGJlaGF2ZSBsaWtlIHJlcXVpcmVcbiBcdF9fd2VicGFja19yZXF1aXJlX18udCA9IGZ1bmN0aW9uKHZhbHVlLCBtb2RlKSB7XG4gXHRcdGlmKG1vZGUgJiAxKSB2YWx1ZSA9IF9fd2VicGFja19yZXF1aXJlX18odmFsdWUpO1xuIFx0XHRpZihtb2RlICYgOCkgcmV0dXJuIHZhbHVlO1xuIFx0XHRpZigobW9kZSAmIDQpICYmIHR5cGVvZiB2YWx1ZSA9PT0gJ29iamVjdCcgJiYgdmFsdWUgJiYgdmFsdWUuX19lc01vZHVsZSkgcmV0dXJuIHZhbHVlO1xuIFx0XHR2YXIgbnMgPSBPYmplY3QuY3JlYXRlKG51bGwpO1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIobnMpO1xuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkobnMsICdkZWZhdWx0JywgeyBlbnVtZXJhYmxlOiB0cnVlLCB2YWx1ZTogdmFsdWUgfSk7XG4gXHRcdGlmKG1vZGUgJiAyICYmIHR5cGVvZiB2YWx1ZSAhPSAnc3RyaW5nJykgZm9yKHZhciBrZXkgaW4gdmFsdWUpIF9fd2VicGFja19yZXF1aXJlX18uZChucywga2V5LCBmdW5jdGlvbihrZXkpIHsgcmV0dXJuIHZhbHVlW2tleV07IH0uYmluZChudWxsLCBrZXkpKTtcbiBcdFx0cmV0dXJuIG5zO1xuIFx0fTtcblxuIFx0Ly8gZ2V0RGVmYXVsdEV4cG9ydCBmdW5jdGlvbiBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIG5vbi1oYXJtb255IG1vZHVsZXNcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubiA9IGZ1bmN0aW9uKG1vZHVsZSkge1xuIFx0XHR2YXIgZ2V0dGVyID0gbW9kdWxlICYmIG1vZHVsZS5fX2VzTW9kdWxlID9cbiBcdFx0XHRmdW5jdGlvbiBnZXREZWZhdWx0KCkgeyByZXR1cm4gbW9kdWxlWydkZWZhdWx0J107IH0gOlxuIFx0XHRcdGZ1bmN0aW9uIGdldE1vZHVsZUV4cG9ydHMoKSB7IHJldHVybiBtb2R1bGU7IH07XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18uZChnZXR0ZXIsICdhJywgZ2V0dGVyKTtcbiBcdFx0cmV0dXJuIGdldHRlcjtcbiBcdH07XG5cbiBcdC8vIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbFxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5vID0gZnVuY3Rpb24ob2JqZWN0LCBwcm9wZXJ0eSkgeyByZXR1cm4gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iamVjdCwgcHJvcGVydHkpOyB9O1xuXG4gXHQvLyBfX3dlYnBhY2tfcHVibGljX3BhdGhfX1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5wID0gXCJcIjtcblxuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IDE5KTtcbiIsIi8qKlxyXG4gKiBIYW5kbGVzIGNsaWVudCBzY3JpcHRpbmcgZm9yIHRoZSBsYWRkZXIgbWF0Y2hlcyBwYWdlLlxyXG4gKlxyXG4gKiBAbGluayAgICAgICBodHRwczovL3d3dy50b3VybmFtYXRjaC5jb21cclxuICogQHNpbmNlICAgICAgMy4xMS4wXHJcbiAqIEBzaW5jZSAgICAgIDMuMjEuMCBBZGRlZCBzdXBwb3J0IGZvciBzZXJ2ZXIgc2lkZSBEYXRhVGFibGVzLlxyXG4gKlxyXG4gKiBAcGFja2FnZSAgICBUb3VybmFtYXRjaFxyXG4gKlxyXG4gKi9cclxuaW1wb3J0IHsgdHJuIH0gZnJvbSAnLi90b3VybmFtYXRjaC5qcyc7XHJcblxyXG4oZnVuY3Rpb24gKCQsIHRybikge1xyXG4gICAgbGV0IG9wdGlvbnMgPSB0cm5fbGFkZGVyX21hdGNoZXNfb3B0aW9ucztcclxuXHJcbiAgICBmdW5jdGlvbiBoYW5kbGVEZWxldGVDb25maXJtKCkge1xyXG4gICAgICAgIGxldCBsaW5rcyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1jb25maXJtLWFjdGlvbi1saW5rJyk7XHJcbiAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbChsaW5rcywgZnVuY3Rpb24gKGxpbmspIHtcclxuICAgICAgICAgICAgbGluay5hZGRFdmVudExpc3RlbmVyKCd0cm4uY29uZmlybWVkLmFjdGlvbi5kZWxldGUtbWF0Y2gnLCBmdW5jdGlvbiAoZXZlbnQpIHtcclxuICAgICAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XHJcblxyXG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coYG1vZGFsIHdhcyBjb25maXJtZWQgZm9yIGxpbmsgJHtsaW5rLmRhdGFzZXQubWF0Y2hJZH1gKTtcclxuICAgICAgICAgICAgICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgICAgIHhoci5vcGVuKCdERUxFVEUnLCBgJHtvcHRpb25zLmFwaV91cmx9bWF0Y2hlcy8ke2xpbmsuZGF0YXNldC5tYXRjaElkfWApO1xyXG4gICAgICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwNCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB3aW5kb3cubG9jYXRpb24ucmVsb2FkKCk7XHJcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbGV0IHJlc3BvbnNlID0gSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLWRlbGV0ZS1tYXRjaC1yZXNwb25zZScpLmlubmVySFRNTCA9IGA8ZGl2IGNsYXNzPVwidHJuLWFsZXJ0IHRybi1hbGVydC1kYW5nZXJcIj48c3Ryb25nPiR7b3B0aW9ucy5sYW5ndWFnZS5mYWlsdXJlfTwvc3Ryb25nPjogJHtyZXNwb25zZS5tZXNzYWdlfTwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgICAgICB4aHIuc2VuZCgpO1xyXG4gICAgICAgICAgICB9LCBmYWxzZSk7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9XHJcblxyXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcigndHJuLWh0bWwtdXBkYXRlZCcsIGZ1bmN0aW9uKGUpIHtcclxuICAgICAgICAgICAgaGFuZGxlRGVsZXRlQ29uZmlybSgpO1xyXG4gICAgICAgIH0pO1xyXG4gICAgICAgIGhhbmRsZURlbGV0ZUNvbmZpcm0oKTtcclxuXHJcbiAgICAgICAgbGV0IGNvbHVtbkRlZnMgPSBbXHJcbiAgICAgICAgICAgIHtcclxuICAgICAgICAgICAgICAgIHRhcmdldHM6IDAsXHJcbiAgICAgICAgICAgICAgICBuYW1lOiAncmVzdWx0JyxcclxuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ3Rybi1sYWRkZXItbWF0Y2hlcy10YWJsZS1yZXN1bHQnLFxyXG4gICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHJvdy5tYXRjaF9yZXN1bHQ7XHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICAgICAgb3JkZXJhYmxlOiBmYWxzZSxcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0czogMSxcclxuICAgICAgICAgICAgICAgIG5hbWU6ICdtYXRjaF9kYXRlJyxcclxuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ3Rybi1sYWRkZXItbWF0Y2hlcy10YWJsZS1kYXRlJyxcclxuICAgICAgICAgICAgICAgIHJlbmRlcjogZnVuY3Rpb24gKGRhdGEsIHR5cGUsIHJvdykge1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiByb3cubWF0Y2hfZGF0ZS5yZW5kZXJlZDtcclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIHtcclxuICAgICAgICAgICAgICAgIHRhcmdldHM6IDIsXHJcbiAgICAgICAgICAgICAgICBuYW1lOiAnZGV0YWlscycsXHJcbiAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICd0cm4tY2hhbGxlbmdlcy10YWJsZS1zdGF0dXMnLFxyXG4gICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IGxpbmtzID0gW107XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIGxpbmtzLnB1c2goYDxhIGhyZWY9XCIke3Jvdy5saW5rfVwiIHRpdGxlPVwiJHtvcHRpb25zLmxhbmd1YWdlLnZpZXdfbWF0Y2hfZGV0YWlsc31cIj48aSBjbGFzcz1cImZhIGZhLWluZm9cIj48L2k+PC9hPmApO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBpZiAob3B0aW9ucy51c2VyX2NhcGFiaWxpdHkpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbGlua3MucHVzaChgPGEgaHJlZj1cIiR7b3B0aW9ucy5sYWRkZXJfZWRpdH0ke3Jvdy5tYXRjaF9pZH1cIiB0aXRsZT1cIiR7b3B0aW9ucy5sYW5ndWFnZS5lZGl0X21hdGNofVwiPjxpIGNsYXNzPVwiZmEgZmEtZWRpdFwiPjwvaT48L2E+YCk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGxpbmtzLnB1c2goYDxhIGNsYXNzPVwidHJuLWNvbmZpcm0tYWN0aW9uLWxpbmsgdHJuLWRlbGV0ZS1tYXRjaC1hY3Rpb25cIiBkYXRhLW1hdGNoLWlkPVwiJHtyb3cubWF0Y2hfaWR9XCIgZGF0YS1jb25maXJtLXRpdGxlPVwiJHtvcHRpb25zLmxhbmd1YWdlLmRlbGV0ZV9tYXRjaH1cIiBkYXRhLWNvbmZpcm0tbWVzc2FnZT1cIiR7b3B0aW9ucy5sYW5ndWFnZS5kZWxldGVfY29uZmlybS5mb3JtYXQocm93Lm1hdGNoX2lkKX1cIiBkYXRhLW1vZGFsLWlkPVwiZGVsZXRlLW1hdGNoXCIgaHJlZj1cIiNcIiB0aXRsZT1cIiR7b3B0aW9ucy5sYW5ndWFnZS5kZWxldGVfbWF0Y2h9XCI+PGkgY2xhc3M9XCJmYSBmYS10aW1lc1wiPjwvaT48L2E+YCk7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gbGlua3Muam9pbihgIGApO1xyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgICAgIG9yZGVyYWJsZTogZmFsc2UsXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgXTtcclxuXHJcbiAgICAgICAgJCgnI3Rybi1sYWRkZXItbWF0Y2hlcy10YWJsZScpXHJcbiAgICAgICAgICAgIC5vbigneGhyLmR0JywgZnVuY3Rpb24gKGUsIHNldHRpbmdzLCBqc29uLCB4aHIpIHtcclxuICAgICAgICAgICAgICAgIGpzb24uZGF0YSA9IEpTT04ucGFyc2UoSlNPTi5zdHJpbmdpZnkoanNvbikpO1xyXG4gICAgICAgICAgICAgICAganNvbi5yZWNvcmRzVG90YWwgPSB4aHIuZ2V0UmVzcG9uc2VIZWFkZXIoJ1gtV1AtVG90YWwnKTtcclxuICAgICAgICAgICAgICAgIGpzb24ucmVjb3Jkc0ZpbHRlcmVkID0geGhyLmdldFJlc3BvbnNlSGVhZGVyKCdUUk4tRmlsdGVyZWQnKTtcclxuICAgICAgICAgICAgICAgIGpzb24ubGVuZ3RoID0geGhyLmdldFJlc3BvbnNlSGVhZGVyKCdYLVdQLVRvdGFsUGFnZXMnKTtcclxuICAgICAgICAgICAgICAgIGpzb24uZHJhdyA9IHhoci5nZXRSZXNwb25zZUhlYWRlcignVFJOLURyYXcnKTtcclxuICAgICAgICAgICAgfSlcclxuICAgICAgICAgICAgLkRhdGFUYWJsZSh7XHJcbiAgICAgICAgICAgICAgICBwcm9jZXNzaW5nOiB0cnVlLFxyXG4gICAgICAgICAgICAgICAgc2VydmVyU2lkZTogdHJ1ZSxcclxuICAgICAgICAgICAgICAgIGxlbmd0aE1lbnU6IFtbMjUsIDUwLCAxMDAsIC0xXSwgWzI1LCA1MCwgMTAwLCAnQWxsJ11dLFxyXG4gICAgICAgICAgICAgICAgbGFuZ3VhZ2U6IG9wdGlvbnMudGFibGVfbGFuZ3VhZ2UsXHJcbiAgICAgICAgICAgICAgICBhdXRvV2lkdGg6IGZhbHNlLFxyXG4gICAgICAgICAgICAgICAgYWpheDoge1xyXG4gICAgICAgICAgICAgICAgICAgIHVybDogYCR7b3B0aW9ucy5hcGlfdXJsfW1hdGNoZXMvP2NvbXBldGl0aW9uX3R5cGU9bGFkZGVycyZjb21wZXRpdGlvbl9pZD0ke29wdGlvbnMubGFkZGVyX2lkfSZfd3Bub25jZT0ke29wdGlvbnMucmVzdF9ub25jZX0mX2VtYmVkYCxcclxuICAgICAgICAgICAgICAgICAgICB0eXBlOiAnR0VUJyxcclxuICAgICAgICAgICAgICAgICAgICBkYXRhOiBmdW5jdGlvbiAoZGF0YSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBsZXQgc2VudCA9IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGRyYXc6IGRhdGEuZHJhdyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBhZ2U6IE1hdGguZmxvb3IoZGF0YS5zdGFydCAvIGRhdGEubGVuZ3RoKSxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBlcl9wYWdlOiBkYXRhLmxlbmd0aCxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNlYXJjaDogZGF0YS5zZWFyY2gudmFsdWUsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBvcmRlcmJ5OiBgJHtkYXRhLmNvbHVtbnNbZGF0YS5vcmRlclswXS5jb2x1bW5dLm5hbWV9LiR7ZGF0YS5vcmRlclswXS5kaXJ9YFxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9O1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gc2VudDtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICAgICAgb3JkZXI6IFtbMSwgJ2Rlc2MnXV0sXHJcbiAgICAgICAgICAgICAgICBjb2x1bW5EZWZzOiBjb2x1bW5EZWZzLFxyXG4gICAgICAgICAgICAgICAgZHJhd0NhbGxiYWNrOiBmdW5jdGlvbiggc2V0dGluZ3MgKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgZG9jdW1lbnQuZGlzcGF0Y2hFdmVudCggbmV3IEN1c3RvbUV2ZW50KCAndHJuLWh0bWwtdXBkYXRlZCcsIHsgJ2RldGFpbCc6ICdUaGUgdGFibGUgaHRtbCBoYXMgdXBkYXRlZC4nIH0gKSk7XHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICB9KTtcclxuICAgIH0sIGZhbHNlKTtcclxufShqUXVlcnksIHRybikpOyIsIid1c2Ugc3RyaWN0JztcclxuY2xhc3MgVG91cm5hbWF0Y2gge1xyXG5cclxuICAgIGNvbnN0cnVjdG9yKCkge1xyXG4gICAgICAgIHRoaXMuZXZlbnRzID0ge307XHJcbiAgICB9XHJcblxyXG4gICAgcGFyYW0ob2JqZWN0LCBwcmVmaXgpIHtcclxuICAgICAgICBsZXQgc3RyID0gW107XHJcbiAgICAgICAgZm9yIChsZXQgcHJvcCBpbiBvYmplY3QpIHtcclxuICAgICAgICAgICAgaWYgKG9iamVjdC5oYXNPd25Qcm9wZXJ0eShwcm9wKSkge1xyXG4gICAgICAgICAgICAgICAgbGV0IGsgPSBwcmVmaXggPyBwcmVmaXggKyBcIltcIiArIHByb3AgKyBcIl1cIiA6IHByb3A7XHJcbiAgICAgICAgICAgICAgICBsZXQgdiA9IG9iamVjdFtwcm9wXTtcclxuICAgICAgICAgICAgICAgIHN0ci5wdXNoKCh2ICE9PSBudWxsICYmIHR5cGVvZiB2ID09PSBcIm9iamVjdFwiKSA/IHRoaXMucGFyYW0odiwgaykgOiBlbmNvZGVVUklDb21wb25lbnQoaykgKyBcIj1cIiArIGVuY29kZVVSSUNvbXBvbmVudCh2KSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuIHN0ci5qb2luKFwiJlwiKTtcclxuICAgIH1cclxuXHJcbiAgICBldmVudChldmVudE5hbWUpIHtcclxuICAgICAgICBpZiAoIShldmVudE5hbWUgaW4gdGhpcy5ldmVudHMpKSB7XHJcbiAgICAgICAgICAgIHRoaXMuZXZlbnRzW2V2ZW50TmFtZV0gPSBuZXcgRXZlbnRUYXJnZXQoZXZlbnROYW1lKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuIHRoaXMuZXZlbnRzW2V2ZW50TmFtZV07XHJcbiAgICB9XHJcblxyXG4gICAgYXV0b2NvbXBsZXRlKGlucHV0LCBkYXRhQ2FsbGJhY2spIHtcclxuICAgICAgICBuZXcgVG91cm5hbWF0Y2hfQXV0b2NvbXBsZXRlKGlucHV0LCBkYXRhQ2FsbGJhY2spO1xyXG4gICAgfVxyXG5cclxuICAgIHVjZmlyc3Qocykge1xyXG4gICAgICAgIGlmICh0eXBlb2YgcyAhPT0gJ3N0cmluZycpIHJldHVybiAnJztcclxuICAgICAgICByZXR1cm4gcy5jaGFyQXQoMCkudG9VcHBlckNhc2UoKSArIHMuc2xpY2UoMSk7XHJcbiAgICB9XHJcblxyXG4gICAgb3JkaW5hbF9zdWZmaXgobnVtYmVyKSB7XHJcbiAgICAgICAgY29uc3QgcmVtYWluZGVyID0gbnVtYmVyICUgMTAwO1xyXG5cclxuICAgICAgICBpZiAoKHJlbWFpbmRlciA8IDExKSB8fCAocmVtYWluZGVyID4gMTMpKSB7XHJcbiAgICAgICAgICAgIHN3aXRjaCAocmVtYWluZGVyICUgMTApIHtcclxuICAgICAgICAgICAgICAgIGNhc2UgMTogcmV0dXJuICdzdCc7XHJcbiAgICAgICAgICAgICAgICBjYXNlIDI6IHJldHVybiAnbmQnO1xyXG4gICAgICAgICAgICAgICAgY2FzZSAzOiByZXR1cm4gJ3JkJztcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gJ3RoJztcclxuICAgIH1cclxuXHJcbiAgICB0YWJzKGVsZW1lbnQpIHtcclxuICAgICAgICBjb25zdCB0YWJzID0gZWxlbWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tbmF2LWxpbmsnKTtcclxuICAgICAgICBjb25zdCBwYW5lcyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi10YWItcGFuZScpO1xyXG4gICAgICAgIGNvbnN0IGNsZWFyQWN0aXZlID0gKCkgPT4ge1xyXG4gICAgICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHRhYnMsICh0YWIpID0+IHtcclxuICAgICAgICAgICAgICAgIHRhYi5jbGFzc0xpc3QucmVtb3ZlKCd0cm4tbmF2LWFjdGl2ZScpO1xyXG4gICAgICAgICAgICAgICAgdGFiLmFyaWFTZWxlY3RlZCA9IGZhbHNlO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbChwYW5lcywgcGFuZSA9PiBwYW5lLmNsYXNzTGlzdC5yZW1vdmUoJ3Rybi10YWItYWN0aXZlJykpO1xyXG4gICAgICAgIH07XHJcbiAgICAgICAgY29uc3Qgc2V0QWN0aXZlID0gKHRhcmdldElkKSA9PiB7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFRhYiA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ2FbaHJlZj1cIiMnICsgdGFyZ2V0SWQgKyAnXCJdLnRybi1uYXYtbGluaycpO1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRQYW5lSWQgPSB0YXJnZXRUYWIgJiYgdGFyZ2V0VGFiLmRhdGFzZXQgJiYgdGFyZ2V0VGFiLmRhdGFzZXQudGFyZ2V0IHx8IGZhbHNlO1xyXG5cclxuICAgICAgICAgICAgaWYgKHRhcmdldFBhbmVJZCkge1xyXG4gICAgICAgICAgICAgICAgY2xlYXJBY3RpdmUoKTtcclxuICAgICAgICAgICAgICAgIHRhcmdldFRhYi5jbGFzc0xpc3QuYWRkKCd0cm4tbmF2LWFjdGl2ZScpO1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0VGFiLmFyaWFTZWxlY3RlZCA9IHRydWU7XHJcblxyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGFyZ2V0UGFuZUlkKS5jbGFzc0xpc3QuYWRkKCd0cm4tdGFiLWFjdGl2ZScpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfTtcclxuICAgICAgICBjb25zdCB0YWJDbGljayA9IChldmVudCkgPT4ge1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRUYWIgPSBldmVudC5jdXJyZW50VGFyZ2V0O1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRQYW5lSWQgPSB0YXJnZXRUYWIgJiYgdGFyZ2V0VGFiLmRhdGFzZXQgJiYgdGFyZ2V0VGFiLmRhdGFzZXQudGFyZ2V0IHx8IGZhbHNlO1xyXG5cclxuICAgICAgICAgICAgaWYgKHRhcmdldFBhbmVJZCkge1xyXG4gICAgICAgICAgICAgICAgc2V0QWN0aXZlKHRhcmdldFBhbmVJZCk7XHJcbiAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfTtcclxuXHJcbiAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbCh0YWJzLCAodGFiKSA9PiB7XHJcbiAgICAgICAgICAgIHRhYi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRhYkNsaWNrKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgaWYgKGxvY2F0aW9uLmhhc2gpIHtcclxuICAgICAgICAgICAgc2V0QWN0aXZlKGxvY2F0aW9uLmhhc2guc3Vic3RyKDEpKTtcclxuICAgICAgICB9IGVsc2UgaWYgKHRhYnMubGVuZ3RoID4gMCkge1xyXG4gICAgICAgICAgICBzZXRBY3RpdmUodGFic1swXS5kYXRhc2V0LnRhcmdldCk7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxufVxyXG5cclxuLy90cm4uaW5pdGlhbGl6ZSgpO1xyXG5pZiAoIXdpbmRvdy50cm5fb2JqX2luc3RhbmNlKSB7XHJcbiAgICB3aW5kb3cudHJuX29ial9pbnN0YW5jZSA9IG5ldyBUb3VybmFtYXRjaCgpO1xyXG5cclxuICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgZnVuY3Rpb24gKCkge1xyXG5cclxuICAgICAgICBjb25zdCB0YWJWaWV3cyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1uYXYnKTtcclxuXHJcbiAgICAgICAgQXJyYXkuZnJvbSh0YWJWaWV3cykuZm9yRWFjaCgodGFiKSA9PiB7XHJcbiAgICAgICAgICAgIHRybi50YWJzKHRhYik7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIGNvbnN0IGRyb3Bkb3ducyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1kcm9wZG93bi10b2dnbGUnKTtcclxuICAgICAgICBjb25zdCBoYW5kbGVEcm9wZG93bkNsb3NlID0gKCkgPT4ge1xyXG4gICAgICAgICAgICBBcnJheS5mcm9tKGRyb3Bkb3ducykuZm9yRWFjaCgoZHJvcGRvd24pID0+IHtcclxuICAgICAgICAgICAgICAgIGRyb3Bkb3duLm5leHRFbGVtZW50U2libGluZy5jbGFzc0xpc3QucmVtb3ZlKCd0cm4tc2hvdycpO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgZG9jdW1lbnQucmVtb3ZlRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGhhbmRsZURyb3Bkb3duQ2xvc2UsIGZhbHNlKTtcclxuICAgICAgICB9O1xyXG5cclxuICAgICAgICBBcnJheS5mcm9tKGRyb3Bkb3ducykuZm9yRWFjaCgoZHJvcGRvd24pID0+IHtcclxuICAgICAgICAgICAgZHJvcGRvd24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbihlKSB7XHJcbiAgICAgICAgICAgICAgICBlLnN0b3BQcm9wYWdhdGlvbigpO1xyXG4gICAgICAgICAgICAgICAgdGhpcy5uZXh0RWxlbWVudFNpYmxpbmcuY2xhc3NMaXN0LmFkZCgndHJuLXNob3cnKTtcclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBoYW5kbGVEcm9wZG93bkNsb3NlLCBmYWxzZSk7XHJcbiAgICAgICAgICAgIH0sIGZhbHNlKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICB9LCBmYWxzZSk7XHJcbn1cclxuZXhwb3J0IGxldCB0cm4gPSB3aW5kb3cudHJuX29ial9pbnN0YW5jZTtcclxuXHJcbmNsYXNzIFRvdXJuYW1hdGNoX0F1dG9jb21wbGV0ZSB7XHJcblxyXG4gICAgLy8gY3VycmVudEZvY3VzO1xyXG4gICAgLy9cclxuICAgIC8vIG5hbWVJbnB1dDtcclxuICAgIC8vXHJcbiAgICAvLyBzZWxmO1xyXG5cclxuICAgIGNvbnN0cnVjdG9yKGlucHV0LCBkYXRhQ2FsbGJhY2spIHtcclxuICAgICAgICAvLyB0aGlzLnNlbGYgPSB0aGlzO1xyXG4gICAgICAgIHRoaXMubmFtZUlucHV0ID0gaW5wdXQ7XHJcblxyXG4gICAgICAgIHRoaXMubmFtZUlucHV0LmFkZEV2ZW50TGlzdGVuZXIoXCJpbnB1dFwiLCAoKSA9PiB7XHJcbiAgICAgICAgICAgIGxldCBhLCBiLCBpLCB2YWwgPSB0aGlzLm5hbWVJbnB1dC52YWx1ZTsvL3RoaXMudmFsdWU7XHJcbiAgICAgICAgICAgIGxldCBwYXJlbnQgPSB0aGlzLm5hbWVJbnB1dC5wYXJlbnROb2RlOy8vdGhpcy5wYXJlbnROb2RlO1xyXG5cclxuICAgICAgICAgICAgLy8gbGV0IHAgPSBuZXcgUHJvbWlzZSgocmVzb2x2ZSwgcmVqZWN0KSA9PiB7XHJcbiAgICAgICAgICAgIC8vICAgICAvKiBuZWVkIHRvIHF1ZXJ5IHNlcnZlciBmb3IgbmFtZXMgaGVyZS4gKi9cclxuICAgICAgICAgICAgLy8gICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5vcGVuKCdHRVQnLCBvcHRpb25zLmFwaV91cmwgKyAncGxheWVycy8/c2VhcmNoPScgKyB2YWwgKyAnJnBlcl9wYWdlPTUnKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdDb250ZW50LVR5cGUnLCAnYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkJyk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignWC1XUC1Ob25jZScsIG9wdGlvbnMucmVzdF9ub25jZSk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIub25sb2FkID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAvLyAgICAgICAgIGlmICh4aHIuc3RhdHVzID09PSAyMDApIHtcclxuICAgICAgICAgICAgLy8gICAgICAgICAgICAgLy8gcmVzb2x2ZShKU09OLnBhcnNlKHhoci5yZXNwb25zZSkubWFwKChwbGF5ZXIpID0+IHtyZXR1cm4geyAndmFsdWUnOiBwbGF5ZXIuaWQsICd0ZXh0JzogcGxheWVyLm5hbWUgfTt9KSk7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgICAgIHJlc29sdmUoSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpLm1hcCgocGxheWVyKSA9PiB7cmV0dXJuIHBsYXllci5uYW1lO30pKTtcclxuICAgICAgICAgICAgLy8gICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAvLyAgICAgICAgICAgICByZWplY3QoKTtcclxuICAgICAgICAgICAgLy8gICAgICAgICB9XHJcbiAgICAgICAgICAgIC8vICAgICB9O1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLnNlbmQoKTtcclxuICAgICAgICAgICAgLy8gfSk7XHJcbiAgICAgICAgICAgIGRhdGFDYWxsYmFjayh2YWwpLnRoZW4oKGRhdGEpID0+IHsvL3AudGhlbigoZGF0YSkgPT4ge1xyXG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coZGF0YSk7XHJcblxyXG4gICAgICAgICAgICAgICAgLypjbG9zZSBhbnkgYWxyZWFkeSBvcGVuIGxpc3RzIG9mIGF1dG8tY29tcGxldGVkIHZhbHVlcyovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmNsb3NlQWxsTGlzdHMoKTtcclxuICAgICAgICAgICAgICAgIGlmICghdmFsKSB7IHJldHVybiBmYWxzZTt9XHJcbiAgICAgICAgICAgICAgICB0aGlzLmN1cnJlbnRGb2N1cyA9IC0xO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qY3JlYXRlIGEgRElWIGVsZW1lbnQgdGhhdCB3aWxsIGNvbnRhaW4gdGhlIGl0ZW1zICh2YWx1ZXMpOiovXHJcbiAgICAgICAgICAgICAgICBhID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcIkRJVlwiKTtcclxuICAgICAgICAgICAgICAgIGEuc2V0QXR0cmlidXRlKFwiaWRcIiwgdGhpcy5uYW1lSW5wdXQuaWQgKyBcIi1hdXRvLWNvbXBsZXRlLWxpc3RcIik7XHJcbiAgICAgICAgICAgICAgICBhLnNldEF0dHJpYnV0ZShcImNsYXNzXCIsIFwidHJuLWF1dG8tY29tcGxldGUtaXRlbXNcIik7XHJcblxyXG4gICAgICAgICAgICAgICAgLyphcHBlbmQgdGhlIERJViBlbGVtZW50IGFzIGEgY2hpbGQgb2YgdGhlIGF1dG8tY29tcGxldGUgY29udGFpbmVyOiovXHJcbiAgICAgICAgICAgICAgICBwYXJlbnQuYXBwZW5kQ2hpbGQoYSk7XHJcblxyXG4gICAgICAgICAgICAgICAgLypmb3IgZWFjaCBpdGVtIGluIHRoZSBhcnJheS4uLiovXHJcbiAgICAgICAgICAgICAgICBmb3IgKGkgPSAwOyBpIDwgZGF0YS5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICAgICAgICAgIGxldCB0ZXh0LCB2YWx1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgLyogV2hpY2ggZm9ybWF0IGRpZCB0aGV5IGdpdmUgdXMuICovXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBkYXRhW2ldID09PSAnb2JqZWN0Jykge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB0ZXh0ID0gZGF0YVtpXVsndGV4dCddO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB2YWx1ZSA9IGRhdGFbaV1bJ3ZhbHVlJ107XHJcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdGV4dCA9IGRhdGFbaV07XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlID0gZGF0YVtpXTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIC8qY2hlY2sgaWYgdGhlIGl0ZW0gc3RhcnRzIHdpdGggdGhlIHNhbWUgbGV0dGVycyBhcyB0aGUgdGV4dCBmaWVsZCB2YWx1ZToqL1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh0ZXh0LnN1YnN0cigwLCB2YWwubGVuZ3RoKS50b1VwcGVyQ2FzZSgpID09PSB2YWwudG9VcHBlckNhc2UoKSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKmNyZWF0ZSBhIERJViBlbGVtZW50IGZvciBlYWNoIG1hdGNoaW5nIGVsZW1lbnQ6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJESVZcIik7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qbWFrZSB0aGUgbWF0Y2hpbmcgbGV0dGVycyBib2xkOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuaW5uZXJIVE1MID0gXCI8c3Ryb25nPlwiICsgdGV4dC5zdWJzdHIoMCwgdmFsLmxlbmd0aCkgKyBcIjwvc3Ryb25nPlwiO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmlubmVySFRNTCArPSB0ZXh0LnN1YnN0cih2YWwubGVuZ3RoKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qaW5zZXJ0IGEgaW5wdXQgZmllbGQgdGhhdCB3aWxsIGhvbGQgdGhlIGN1cnJlbnQgYXJyYXkgaXRlbSdzIHZhbHVlOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuaW5uZXJIVE1MICs9IFwiPGlucHV0IHR5cGU9J2hpZGRlbicgdmFsdWU9J1wiICsgdmFsdWUgKyBcIic+XCI7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmRhdGFzZXQudmFsdWUgPSB2YWx1ZTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5kYXRhc2V0LnRleHQgPSB0ZXh0O1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgLypleGVjdXRlIGEgZnVuY3Rpb24gd2hlbiBzb21lb25lIGNsaWNrcyBvbiB0aGUgaXRlbSB2YWx1ZSAoRElWIGVsZW1lbnQpOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIChlKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhgaXRlbSBjbGlja2VkIHdpdGggdmFsdWUgJHtlLmN1cnJlbnRUYXJnZXQuZGF0YXNldC52YWx1ZX1gKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAvKiBpbnNlcnQgdGhlIHZhbHVlIGZvciB0aGUgYXV0b2NvbXBsZXRlIHRleHQgZmllbGQ6ICovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLm5hbWVJbnB1dC52YWx1ZSA9IGUuY3VycmVudFRhcmdldC5kYXRhc2V0LnRleHQ7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLm5hbWVJbnB1dC5kYXRhc2V0LnNlbGVjdGVkSWQgPSBlLmN1cnJlbnRUYXJnZXQuZGF0YXNldC52YWx1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAvKiBjbG9zZSB0aGUgbGlzdCBvZiBhdXRvY29tcGxldGVkIHZhbHVlcywgKG9yIGFueSBvdGhlciBvcGVuIGxpc3RzIG9mIGF1dG9jb21wbGV0ZWQgdmFsdWVzOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLmNsb3NlQWxsTGlzdHMoKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLm5hbWVJbnB1dC5kaXNwYXRjaEV2ZW50KG5ldyBFdmVudCgnY2hhbmdlJykpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYS5hcHBlbmRDaGlsZChiKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiBwcmVzc2VzIGEga2V5IG9uIHRoZSBrZXlib2FyZDoqL1xyXG4gICAgICAgIHRoaXMubmFtZUlucHV0LmFkZEV2ZW50TGlzdGVuZXIoXCJrZXlkb3duXCIsIChlKSA9PiB7XHJcbiAgICAgICAgICAgIGxldCB4ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGhpcy5uYW1lSW5wdXQuaWQgKyBcIi1hdXRvLWNvbXBsZXRlLWxpc3RcIik7XHJcbiAgICAgICAgICAgIGlmICh4KSB4ID0geC5nZXRFbGVtZW50c0J5VGFnTmFtZShcImRpdlwiKTtcclxuICAgICAgICAgICAgaWYgKGUua2V5Q29kZSA9PT0gNDApIHtcclxuICAgICAgICAgICAgICAgIC8qSWYgdGhlIGFycm93IERPV04ga2V5IGlzIHByZXNzZWQsXHJcbiAgICAgICAgICAgICAgICAgaW5jcmVhc2UgdGhlIGN1cnJlbnRGb2N1cyB2YXJpYWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50Rm9jdXMrKztcclxuICAgICAgICAgICAgICAgIC8qYW5kIGFuZCBtYWtlIHRoZSBjdXJyZW50IGl0ZW0gbW9yZSB2aXNpYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmFkZEFjdGl2ZSh4KTtcclxuICAgICAgICAgICAgfSBlbHNlIGlmIChlLmtleUNvZGUgPT09IDM4KSB7IC8vdXBcclxuICAgICAgICAgICAgICAgIC8qSWYgdGhlIGFycm93IFVQIGtleSBpcyBwcmVzc2VkLFxyXG4gICAgICAgICAgICAgICAgIGRlY3JlYXNlIHRoZSBjdXJyZW50Rm9jdXMgdmFyaWFibGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudEZvY3VzLS07XHJcbiAgICAgICAgICAgICAgICAvKmFuZCBhbmQgbWFrZSB0aGUgY3VycmVudCBpdGVtIG1vcmUgdmlzaWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5hZGRBY3RpdmUoeCk7XHJcbiAgICAgICAgICAgIH0gZWxzZSBpZiAoZS5rZXlDb2RlID09PSAxMykge1xyXG4gICAgICAgICAgICAgICAgLypJZiB0aGUgRU5URVIga2V5IGlzIHByZXNzZWQsIHByZXZlbnQgdGhlIGZvcm0gZnJvbSBiZWluZyBzdWJtaXR0ZWQsKi9cclxuICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgICAgIGlmICh0aGlzLmN1cnJlbnRGb2N1cyA+IC0xKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgLyphbmQgc2ltdWxhdGUgYSBjbGljayBvbiB0aGUgXCJhY3RpdmVcIiBpdGVtOiovXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHgpIHhbdGhpcy5jdXJyZW50Rm9jdXNdLmNsaWNrKCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgLypleGVjdXRlIGEgZnVuY3Rpb24gd2hlbiBzb21lb25lIGNsaWNrcyBpbiB0aGUgZG9jdW1lbnQ6Ki9cclxuICAgICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKGUpID0+IHtcclxuICAgICAgICAgICAgdGhpcy5jbG9zZUFsbExpc3RzKGUudGFyZ2V0KTtcclxuICAgICAgICB9KTtcclxuICAgIH1cclxuXHJcbiAgICBhZGRBY3RpdmUoeCkge1xyXG4gICAgICAgIC8qYSBmdW5jdGlvbiB0byBjbGFzc2lmeSBhbiBpdGVtIGFzIFwiYWN0aXZlXCI6Ki9cclxuICAgICAgICBpZiAoIXgpIHJldHVybiBmYWxzZTtcclxuICAgICAgICAvKnN0YXJ0IGJ5IHJlbW92aW5nIHRoZSBcImFjdGl2ZVwiIGNsYXNzIG9uIGFsbCBpdGVtczoqL1xyXG4gICAgICAgIHRoaXMucmVtb3ZlQWN0aXZlKHgpO1xyXG4gICAgICAgIGlmICh0aGlzLmN1cnJlbnRGb2N1cyA+PSB4Lmxlbmd0aCkgdGhpcy5jdXJyZW50Rm9jdXMgPSAwO1xyXG4gICAgICAgIGlmICh0aGlzLmN1cnJlbnRGb2N1cyA8IDApIHRoaXMuY3VycmVudEZvY3VzID0gKHgubGVuZ3RoIC0gMSk7XHJcbiAgICAgICAgLyphZGQgY2xhc3MgXCJhdXRvY29tcGxldGUtYWN0aXZlXCI6Ki9cclxuICAgICAgICB4W3RoaXMuY3VycmVudEZvY3VzXS5jbGFzc0xpc3QuYWRkKFwidHJuLWF1dG8tY29tcGxldGUtYWN0aXZlXCIpO1xyXG4gICAgfVxyXG5cclxuICAgIHJlbW92ZUFjdGl2ZSh4KSB7XHJcbiAgICAgICAgLyphIGZ1bmN0aW9uIHRvIHJlbW92ZSB0aGUgXCJhY3RpdmVcIiBjbGFzcyBmcm9tIGFsbCBhdXRvY29tcGxldGUgaXRlbXM6Ki9cclxuICAgICAgICBmb3IgKGxldCBpID0gMDsgaSA8IHgubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgeFtpXS5jbGFzc0xpc3QucmVtb3ZlKFwidHJuLWF1dG8tY29tcGxldGUtYWN0aXZlXCIpO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbiAgICBjbG9zZUFsbExpc3RzKGVsZW1lbnQpIHtcclxuICAgICAgICBjb25zb2xlLmxvZyhcImNsb3NlIGFsbCBsaXN0c1wiKTtcclxuICAgICAgICAvKmNsb3NlIGFsbCBhdXRvY29tcGxldGUgbGlzdHMgaW4gdGhlIGRvY3VtZW50LFxyXG4gICAgICAgICBleGNlcHQgdGhlIG9uZSBwYXNzZWQgYXMgYW4gYXJndW1lbnQ6Ki9cclxuICAgICAgICBsZXQgeCA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoXCJ0cm4tYXV0by1jb21wbGV0ZS1pdGVtc1wiKTtcclxuICAgICAgICBmb3IgKGxldCBpID0gMDsgaSA8IHgubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgaWYgKGVsZW1lbnQgIT09IHhbaV0gJiYgZWxlbWVudCAhPT0gdGhpcy5uYW1lSW5wdXQpIHtcclxuICAgICAgICAgICAgICAgIHhbaV0ucGFyZW50Tm9kZS5yZW1vdmVDaGlsZCh4W2ldKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH1cclxufVxyXG5cclxuLy8gRmlyc3QsIGNoZWNrcyBpZiBpdCBpc24ndCBpbXBsZW1lbnRlZCB5ZXQuXHJcbmlmICghU3RyaW5nLnByb3RvdHlwZS5mb3JtYXQpIHtcclxuICAgIFN0cmluZy5wcm90b3R5cGUuZm9ybWF0ID0gZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgY29uc3QgYXJncyA9IGFyZ3VtZW50cztcclxuICAgICAgICByZXR1cm4gdGhpcy5yZXBsYWNlKC97KFxcZCspfS9nLCBmdW5jdGlvbihtYXRjaCwgbnVtYmVyKSB7XHJcbiAgICAgICAgICAgIHJldHVybiB0eXBlb2YgYXJnc1tudW1iZXJdICE9PSAndW5kZWZpbmVkJ1xyXG4gICAgICAgICAgICAgICAgPyBhcmdzW251bWJlcl1cclxuICAgICAgICAgICAgICAgIDogbWF0Y2hcclxuICAgICAgICAgICAgICAgIDtcclxuICAgICAgICB9KTtcclxuICAgIH07XHJcbn0iXSwic291cmNlUm9vdCI6IiJ9