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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/challenges.js":
/*!******************************!*\
  !*** ./src/js/challenges.js ***!
  \******************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./tournamatch.js */ "./src/js/tournamatch.js");
/**
 * Challenge list page.
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

  var options = trn_end_scripts;

  function handleDeleteConfirm() {
    var links = document.getElementsByClassName('trn-confirm-action-link');
    Array.prototype.forEach.call(links, function (link) {
      link.addEventListener('trn.confirmed.action.delete-challenge', function (event) {
        event.preventDefault();
        console.log("modal was confirmed for link ".concat(link.dataset.challengeId));
        var xhr = new XMLHttpRequest();
        xhr.open('DELETE', "".concat(options.api_url, "challenges/").concat(link.dataset.challengeId));
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

        xhr.onload = function () {
          if (xhr.status === 204) {
            window.location.reload();
          } else {
            var response = JSON.parse(xhr.response);
            document.getElementById('trn-delete-challenge-response').innerHTML = "<div class=\"trn-alert trn-alert-danger\"><strong>".concat(options.language.failure, "</strong>: ").concat(response.message, "</div>");
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
      name: 'ladder',
      className: 'trn-challenges-table-ladder',
      render: function render(data, type, row) {
        return "<a href=\"".concat(row._embedded.ladder[0].link, "\">").concat(row._embedded.ladder[0].name, "</a>");
      }
    }, {
      targets: 1,
      name: 'challenger',
      className: 'trn-challenges-table-challenger',
      render: function render(data, type, row) {
        if (0 === row.challengee_id) {
          return options.language.hidden;
        } else {
          return "<a href=\"".concat(row._embedded.challenger[0].link, "\">").concat(row._embedded.challenger[0].name, "</a>");
        }
      }
    }, {
      targets: 2,
      name: 'challengee',
      className: 'trn-challenges-table-challengee',
      render: function render(data, type, row) {
        if (0 === row.challengee_id) {
          return options.language.open;
        } else {
          return "<a href=\"".concat(row._embedded.challengee[0].link, "\">").concat(row._embedded.challengee[0].name, "</a>");
        }
      }
    }, {
      targets: 3,
      name: 'match_time',
      className: 'trn-challenges-table-match-time',
      render: function render(data, type, row) {
        return row.match_time.rendered;
      }
    }, {
      targets: 4,
      name: 'status',
      className: 'trn-challenges-table-status',
      render: function render(data, type, row) {
        return _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__["trn"].ucfirst(row.accepted_state);
      }
    }, {
      targets: 5,
      name: 'actions',
      className: 'trn-challenges-table-actions',
      render: function render(data, type, row) {
        var content = "<a href=\"".concat(row.link, "\"><i class=\"fa fa-info\" aria-hidden=\"true\"></i></a>");

        if (options.user_capability) {
          content += " <a class=\"trn-delete-challenge-action trn-confirm-action-link\" data-challenge-id=\"".concat(row.challenge_id, "\" data-modal-id=\"delete-challenge\" data-confirm-title=\"").concat(options.language.delete_challenge, "\" data-confirm-message=\"").concat(options.language.delete_confirm.format(row.challenge_id), "\" href=\"").concat(options.challenge_url).concat(row.challenge_id, "\" title=\"").concat(options.language.delete_challenge, "\"><i class=\"fa fa-trash\"></i></a>");
        }

        return content;
      },
      orderable: false
    }];
    jQuery('#trn-challenge-list-table').on('xhr.dt', function (e, settings, json, xhr) {
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
      ajax: {
        url: "".concat(options.api_url, "challenges/?_wpnonce=").concat(options.rest_nonce, "&_embed"),
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
      order: [[3, 'asc']],
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

/***/ 2:
/*!************************************!*\
  !*** multi ./src/js/challenges.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\challenges.js */"./src/js/challenges.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL2NoYWxsZW5nZXMuanMiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3RvdXJuYW1hdGNoLmpzIl0sIm5hbWVzIjpbImpRdWVyeSIsIiQiLCJvcHRpb25zIiwidHJuX2VuZF9zY3JpcHRzIiwiaGFuZGxlRGVsZXRlQ29uZmlybSIsImxpbmtzIiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50c0J5Q2xhc3NOYW1lIiwiQXJyYXkiLCJwcm90b3R5cGUiLCJmb3JFYWNoIiwiY2FsbCIsImxpbmsiLCJhZGRFdmVudExpc3RlbmVyIiwiZXZlbnQiLCJwcmV2ZW50RGVmYXVsdCIsImNvbnNvbGUiLCJsb2ciLCJkYXRhc2V0IiwiY2hhbGxlbmdlSWQiLCJ4aHIiLCJYTUxIdHRwUmVxdWVzdCIsIm9wZW4iLCJhcGlfdXJsIiwic2V0UmVxdWVzdEhlYWRlciIsInJlc3Rfbm9uY2UiLCJvbmxvYWQiLCJzdGF0dXMiLCJ3aW5kb3ciLCJsb2NhdGlvbiIsInJlbG9hZCIsInJlc3BvbnNlIiwiSlNPTiIsInBhcnNlIiwiZ2V0RWxlbWVudEJ5SWQiLCJpbm5lckhUTUwiLCJsYW5ndWFnZSIsImZhaWx1cmUiLCJtZXNzYWdlIiwic2VuZCIsImUiLCJjb2x1bW5EZWZzIiwidGFyZ2V0cyIsIm5hbWUiLCJjbGFzc05hbWUiLCJyZW5kZXIiLCJkYXRhIiwidHlwZSIsInJvdyIsIl9lbWJlZGRlZCIsImxhZGRlciIsImNoYWxsZW5nZWVfaWQiLCJoaWRkZW4iLCJjaGFsbGVuZ2VyIiwiY2hhbGxlbmdlZSIsIm1hdGNoX3RpbWUiLCJyZW5kZXJlZCIsInRybiIsInVjZmlyc3QiLCJhY2NlcHRlZF9zdGF0ZSIsImNvbnRlbnQiLCJ1c2VyX2NhcGFiaWxpdHkiLCJjaGFsbGVuZ2VfaWQiLCJkZWxldGVfY2hhbGxlbmdlIiwiZGVsZXRlX2NvbmZpcm0iLCJmb3JtYXQiLCJjaGFsbGVuZ2VfdXJsIiwib3JkZXJhYmxlIiwib24iLCJzZXR0aW5ncyIsImpzb24iLCJzdHJpbmdpZnkiLCJyZWNvcmRzVG90YWwiLCJnZXRSZXNwb25zZUhlYWRlciIsInJlY29yZHNGaWx0ZXJlZCIsImxlbmd0aCIsImRyYXciLCJEYXRhVGFibGUiLCJwcm9jZXNzaW5nIiwic2VydmVyU2lkZSIsImxlbmd0aE1lbnUiLCJhamF4IiwidXJsIiwic2VudCIsInBhZ2UiLCJNYXRoIiwiZmxvb3IiLCJzdGFydCIsInBlcl9wYWdlIiwic2VhcmNoIiwidmFsdWUiLCJvcmRlcmJ5IiwiY29sdW1ucyIsIm9yZGVyIiwiY29sdW1uIiwiZGlyIiwiZHJhd0NhbGxiYWNrIiwiZGlzcGF0Y2hFdmVudCIsIkN1c3RvbUV2ZW50IiwiVG91cm5hbWF0Y2giLCJldmVudHMiLCJvYmplY3QiLCJwcmVmaXgiLCJzdHIiLCJwcm9wIiwiaGFzT3duUHJvcGVydHkiLCJrIiwidiIsInB1c2giLCJwYXJhbSIsImVuY29kZVVSSUNvbXBvbmVudCIsImpvaW4iLCJldmVudE5hbWUiLCJFdmVudFRhcmdldCIsImlucHV0IiwiZGF0YUNhbGxiYWNrIiwiVG91cm5hbWF0Y2hfQXV0b2NvbXBsZXRlIiwicyIsImNoYXJBdCIsInRvVXBwZXJDYXNlIiwic2xpY2UiLCJudW1iZXIiLCJyZW1haW5kZXIiLCJlbGVtZW50IiwidGFicyIsInBhbmVzIiwiY2xlYXJBY3RpdmUiLCJ0YWIiLCJjbGFzc0xpc3QiLCJyZW1vdmUiLCJhcmlhU2VsZWN0ZWQiLCJwYW5lIiwic2V0QWN0aXZlIiwidGFyZ2V0SWQiLCJ0YXJnZXRUYWIiLCJxdWVyeVNlbGVjdG9yIiwidGFyZ2V0UGFuZUlkIiwidGFyZ2V0IiwiYWRkIiwidGFiQ2xpY2siLCJjdXJyZW50VGFyZ2V0IiwiaGFzaCIsInN1YnN0ciIsInRybl9vYmpfaW5zdGFuY2UiLCJ0YWJWaWV3cyIsImZyb20iLCJkcm9wZG93bnMiLCJoYW5kbGVEcm9wZG93bkNsb3NlIiwiZHJvcGRvd24iLCJuZXh0RWxlbWVudFNpYmxpbmciLCJyZW1vdmVFdmVudExpc3RlbmVyIiwic3RvcFByb3BhZ2F0aW9uIiwibmFtZUlucHV0IiwiYSIsImIiLCJpIiwidmFsIiwicGFyZW50IiwicGFyZW50Tm9kZSIsInRoZW4iLCJjbG9zZUFsbExpc3RzIiwiY3VycmVudEZvY3VzIiwiY3JlYXRlRWxlbWVudCIsInNldEF0dHJpYnV0ZSIsImlkIiwiYXBwZW5kQ2hpbGQiLCJ0ZXh0Iiwic2VsZWN0ZWRJZCIsIkV2ZW50IiwieCIsImdldEVsZW1lbnRzQnlUYWdOYW1lIiwia2V5Q29kZSIsImFkZEFjdGl2ZSIsImNsaWNrIiwicmVtb3ZlQWN0aXZlIiwicmVtb3ZlQ2hpbGQiLCJTdHJpbmciLCJhcmdzIiwiYXJndW1lbnRzIiwicmVwbGFjZSIsIm1hdGNoIl0sIm1hcHBpbmdzIjoiO1FBQUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7OztRQUdBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSwwQ0FBMEMsZ0NBQWdDO1FBQzFFO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0Esd0RBQXdELGtCQUFrQjtRQUMxRTtRQUNBLGlEQUFpRCxjQUFjO1FBQy9EOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQSx5Q0FBeUMsaUNBQWlDO1FBQzFFLGdIQUFnSCxtQkFBbUIsRUFBRTtRQUNySTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLDJCQUEyQiwwQkFBMEIsRUFBRTtRQUN2RCxpQ0FBaUMsZUFBZTtRQUNoRDtRQUNBO1FBQ0E7O1FBRUE7UUFDQSxzREFBc0QsK0RBQStEOztRQUVySDtRQUNBOzs7UUFHQTtRQUNBOzs7Ozs7Ozs7Ozs7O0FDbEZBO0FBQUE7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtB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O0FBQ0EsV0FBSyxJQUFJb0osQ0FBQyxHQUFHLENBQWIsRUFBZ0JBLENBQUMsR0FBR2MsQ0FBQyxDQUFDOUYsTUFBdEIsRUFBOEJnRixDQUFDLEVBQS9CLEVBQW1DO0FBQy9CLFlBQUloQyxPQUFPLEtBQUs4QyxDQUFDLENBQUNkLENBQUQsQ0FBYixJQUFvQmhDLE9BQU8sS0FBSyxLQUFLNkIsU0FBekMsRUFBb0Q7QUFDaERpQixXQUFDLENBQUNkLENBQUQsQ0FBRCxDQUFLRyxVQUFMLENBQWdCaUIsV0FBaEIsQ0FBNEJOLENBQUMsQ0FBQ2QsQ0FBRCxDQUE3QjtBQUNIO0FBQ0o7QUFDSjs7OztLQUdMOzs7QUFDQSxJQUFJLENBQUNxQixNQUFNLENBQUN2SyxTQUFQLENBQWlCd0QsTUFBdEIsRUFBOEI7QUFDMUIrRyxRQUFNLENBQUN2SyxTQUFQLENBQWlCd0QsTUFBakIsR0FBMEIsWUFBVztBQUNqQyxRQUFNZ0gsSUFBSSxHQUFHQyxTQUFiO0FBQ0EsV0FBTyxLQUFLQyxPQUFMLENBQWEsVUFBYixFQUF5QixVQUFTQyxLQUFULEVBQWdCM0QsTUFBaEIsRUFBd0I7QUFDcEQsYUFBTyxPQUFPd0QsSUFBSSxDQUFDeEQsTUFBRCxDQUFYLEtBQXdCLFdBQXhCLEdBQ0R3RCxJQUFJLENBQUN4RCxNQUFELENBREgsR0FFRDJELEtBRk47QUFJSCxLQUxNLENBQVA7QUFNSCxHQVJEO0FBU0gsQyIsImZpbGUiOiJjaGFsbGVuZ2VzLmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7IGVudW1lcmFibGU6IHRydWUsIGdldDogZ2V0dGVyIH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBkZWZpbmUgX19lc01vZHVsZSBvbiBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIgPSBmdW5jdGlvbihleHBvcnRzKSB7XG4gXHRcdGlmKHR5cGVvZiBTeW1ib2wgIT09ICd1bmRlZmluZWQnICYmIFN5bWJvbC50b1N0cmluZ1RhZykge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBTeW1ib2wudG9TdHJpbmdUYWcsIHsgdmFsdWU6ICdNb2R1bGUnIH0pO1xuIFx0XHR9XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCAnX19lc01vZHVsZScsIHsgdmFsdWU6IHRydWUgfSk7XG4gXHR9O1xuXG4gXHQvLyBjcmVhdGUgYSBmYWtlIG5hbWVzcGFjZSBvYmplY3RcbiBcdC8vIG1vZGUgJiAxOiB2YWx1ZSBpcyBhIG1vZHVsZSBpZCwgcmVxdWlyZSBpdFxuIFx0Ly8gbW9kZSAmIDI6IG1lcmdlIGFsbCBwcm9wZXJ0aWVzIG9mIHZhbHVlIGludG8gdGhlIG5zXG4gXHQvLyBtb2RlICYgNDogcmV0dXJuIHZhbHVlIHdoZW4gYWxyZWFkeSBucyBvYmplY3RcbiBcdC8vIG1vZGUgJiA4fDE6IGJlaGF2ZSBsaWtlIHJlcXVpcmVcbiBcdF9fd2VicGFja19yZXF1aXJlX18udCA9IGZ1bmN0aW9uKHZhbHVlLCBtb2RlKSB7XG4gXHRcdGlmKG1vZGUgJiAxKSB2YWx1ZSA9IF9fd2VicGFja19yZXF1aXJlX18odmFsdWUpO1xuIFx0XHRpZihtb2RlICYgOCkgcmV0dXJuIHZhbHVlO1xuIFx0XHRpZigobW9kZSAmIDQpICYmIHR5cGVvZiB2YWx1ZSA9PT0gJ29iamVjdCcgJiYgdmFsdWUgJiYgdmFsdWUuX19lc01vZHVsZSkgcmV0dXJuIHZhbHVlO1xuIFx0XHR2YXIgbnMgPSBPYmplY3QuY3JlYXRlKG51bGwpO1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIobnMpO1xuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkobnMsICdkZWZhdWx0JywgeyBlbnVtZXJhYmxlOiB0cnVlLCB2YWx1ZTogdmFsdWUgfSk7XG4gXHRcdGlmKG1vZGUgJiAyICYmIHR5cGVvZiB2YWx1ZSAhPSAnc3RyaW5nJykgZm9yKHZhciBrZXkgaW4gdmFsdWUpIF9fd2VicGFja19yZXF1aXJlX18uZChucywga2V5LCBmdW5jdGlvbihrZXkpIHsgcmV0dXJuIHZhbHVlW2tleV07IH0uYmluZChudWxsLCBrZXkpKTtcbiBcdFx0cmV0dXJuIG5zO1xuIFx0fTtcblxuIFx0Ly8gZ2V0RGVmYXVsdEV4cG9ydCBmdW5jdGlvbiBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIG5vbi1oYXJtb255IG1vZHVsZXNcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubiA9IGZ1bmN0aW9uKG1vZHVsZSkge1xuIFx0XHR2YXIgZ2V0dGVyID0gbW9kdWxlICYmIG1vZHVsZS5fX2VzTW9kdWxlID9cbiBcdFx0XHRmdW5jdGlvbiBnZXREZWZhdWx0KCkgeyByZXR1cm4gbW9kdWxlWydkZWZhdWx0J107IH0gOlxuIFx0XHRcdGZ1bmN0aW9uIGdldE1vZHVsZUV4cG9ydHMoKSB7IHJldHVybiBtb2R1bGU7IH07XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18uZChnZXR0ZXIsICdhJywgZ2V0dGVyKTtcbiBcdFx0cmV0dXJuIGdldHRlcjtcbiBcdH07XG5cbiBcdC8vIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbFxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5vID0gZnVuY3Rpb24ob2JqZWN0LCBwcm9wZXJ0eSkgeyByZXR1cm4gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iamVjdCwgcHJvcGVydHkpOyB9O1xuXG4gXHQvLyBfX3dlYnBhY2tfcHVibGljX3BhdGhfX1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5wID0gXCJcIjtcblxuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IDIpO1xuIiwiLyoqXHJcbiAqIENoYWxsZW5nZSBsaXN0IHBhZ2UuXHJcbiAqXHJcbiAqIEBsaW5rICAgICAgIGh0dHBzOi8vd3d3LnRvdXJuYW1hdGNoLmNvbVxyXG4gKiBAc2luY2UgICAgICAzLjExLjBcclxuICogQHNpbmNlICAgICAgMy4yMS4wIEFkZGVkIHN1cHBvcnQgZm9yIHNlcnZlciBzaWRlIERhdGFUYWJsZXMuXHJcbiAqXHJcbiAqIEBwYWNrYWdlICAgIFRvdXJuYW1hdGNoXHJcbiAqXHJcbiAqL1xyXG5pbXBvcnQgeyB0cm4gfSBmcm9tICcuL3RvdXJuYW1hdGNoLmpzJztcclxuXHJcbihmdW5jdGlvbiAoalF1ZXJ5LCAkKSB7XHJcbiAgICAndXNlIHN0cmljdCc7XHJcblxyXG4gICAgbGV0IG9wdGlvbnMgPSB0cm5fZW5kX3NjcmlwdHM7XHJcblxyXG4gICAgZnVuY3Rpb24gaGFuZGxlRGVsZXRlQ29uZmlybSgpIHtcclxuICAgICAgICBsZXQgbGlua3MgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tY29uZmlybS1hY3Rpb24tbGluaycpO1xyXG4gICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwobGlua3MsIGZ1bmN0aW9uIChsaW5rKSB7XHJcbiAgICAgICAgICAgIGxpbmsuYWRkRXZlbnRMaXN0ZW5lcigndHJuLmNvbmZpcm1lZC5hY3Rpb24uZGVsZXRlLWNoYWxsZW5nZScsIGZ1bmN0aW9uIChldmVudCkge1xyXG4gICAgICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcclxuXHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhgbW9kYWwgd2FzIGNvbmZpcm1lZCBmb3IgbGluayAke2xpbmsuZGF0YXNldC5jaGFsbGVuZ2VJZH1gKTtcclxuICAgICAgICAgICAgICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgICAgIHhoci5vcGVuKCdERUxFVEUnLCBgJHtvcHRpb25zLmFwaV91cmx9Y2hhbGxlbmdlcy8ke2xpbmsuZGF0YXNldC5jaGFsbGVuZ2VJZH1gKTtcclxuICAgICAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdDb250ZW50LVR5cGUnLCAnYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkJyk7XHJcbiAgICAgICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignWC1XUC1Ob25jZScsIG9wdGlvbnMucmVzdF9ub25jZSk7XHJcbiAgICAgICAgICAgICAgICB4aHIub25sb2FkID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh4aHIuc3RhdHVzID09PSAyMDQpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgd2luZG93LmxvY2F0aW9uLnJlbG9hZCgpO1xyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGxldCByZXNwb25zZSA9IEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Rybi1kZWxldGUtY2hhbGxlbmdlLXJlc3BvbnNlJykuaW5uZXJIVE1MID0gYDxkaXYgY2xhc3M9XCJ0cm4tYWxlcnQgdHJuLWFsZXJ0LWRhbmdlclwiPjxzdHJvbmc+JHtvcHRpb25zLmxhbmd1YWdlLmZhaWx1cmV9PC9zdHJvbmc+OiAke3Jlc3BvbnNlLm1lc3NhZ2V9PC9kaXY+YDtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9O1xyXG5cclxuICAgICAgICAgICAgICAgIHhoci5zZW5kKCk7XHJcbiAgICAgICAgICAgIH0sIGZhbHNlKTtcclxuICAgICAgICB9KTtcclxuICAgIH1cclxuXHJcbiAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCd0cm4taHRtbC11cGRhdGVkJywgZnVuY3Rpb24oZSkge1xyXG4gICAgICAgICAgICBoYW5kbGVEZWxldGVDb25maXJtKCk7XHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgaGFuZGxlRGVsZXRlQ29uZmlybSgpO1xyXG5cclxuICAgICAgICBsZXQgY29sdW1uRGVmcyA9IFtcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0czogMCxcclxuICAgICAgICAgICAgICAgIG5hbWU6ICdsYWRkZXInLFxyXG4gICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAndHJuLWNoYWxsZW5nZXMtdGFibGUtbGFkZGVyJyxcclxuICAgICAgICAgICAgICAgIHJlbmRlcjogZnVuY3Rpb24gKGRhdGEsIHR5cGUsIHJvdykge1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBgPGEgaHJlZj1cIiR7cm93Ll9lbWJlZGRlZC5sYWRkZXJbMF0ubGlua31cIj4ke3Jvdy5fZW1iZWRkZWQubGFkZGVyWzBdLm5hbWV9PC9hPmA7XHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICB7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRzOiAxLFxyXG4gICAgICAgICAgICAgICAgbmFtZTogJ2NoYWxsZW5nZXInLFxyXG4gICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAndHJuLWNoYWxsZW5nZXMtdGFibGUtY2hhbGxlbmdlcicsXHJcbiAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uIChkYXRhLCB0eXBlLCByb3cpIHtcclxuICAgICAgICAgICAgICAgICAgICBpZiAoMCA9PT0gcm93LmNoYWxsZW5nZWVfaWQpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIG9wdGlvbnMubGFuZ3VhZ2UuaGlkZGVuO1xyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBgPGEgaHJlZj1cIiR7cm93Ll9lbWJlZGRlZC5jaGFsbGVuZ2VyWzBdLmxpbmt9XCI+JHtyb3cuX2VtYmVkZGVkLmNoYWxsZW5nZXJbMF0ubmFtZX08L2E+YDtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICB7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRzOiAyLFxyXG4gICAgICAgICAgICAgICAgbmFtZTogJ2NoYWxsZW5nZWUnLFxyXG4gICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAndHJuLWNoYWxsZW5nZXMtdGFibGUtY2hhbGxlbmdlZScsXHJcbiAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uIChkYXRhLCB0eXBlLCByb3cpIHtcclxuICAgICAgICAgICAgICAgICAgICBpZiAoMCA9PT0gcm93LmNoYWxsZW5nZWVfaWQpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIG9wdGlvbnMubGFuZ3VhZ2Uub3BlbjtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gYDxhIGhyZWY9XCIke3Jvdy5fZW1iZWRkZWQuY2hhbGxlbmdlZVswXS5saW5rfVwiPiR7cm93Ll9lbWJlZGRlZC5jaGFsbGVuZ2VlWzBdLm5hbWV9PC9hPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0czogMyxcclxuICAgICAgICAgICAgICAgIG5hbWU6ICdtYXRjaF90aW1lJyxcclxuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ3Rybi1jaGFsbGVuZ2VzLXRhYmxlLW1hdGNoLXRpbWUnLFxyXG4gICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHJvdy5tYXRjaF90aW1lLnJlbmRlcmVkO1xyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0czogNCxcclxuICAgICAgICAgICAgICAgIG5hbWU6ICdzdGF0dXMnLFxyXG4gICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAndHJuLWNoYWxsZW5nZXMtdGFibGUtc3RhdHVzJyxcclxuICAgICAgICAgICAgICAgIHJlbmRlcjogZnVuY3Rpb24gKGRhdGEsIHR5cGUsIHJvdykge1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiB0cm4udWNmaXJzdChyb3cuYWNjZXB0ZWRfc3RhdGUpO1xyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0czogNSxcclxuICAgICAgICAgICAgICAgIG5hbWU6ICdhY3Rpb25zJyxcclxuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ3Rybi1jaGFsbGVuZ2VzLXRhYmxlLWFjdGlvbnMnLFxyXG4gICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IGNvbnRlbnQgPSBgPGEgaHJlZj1cIiR7cm93Lmxpbmt9XCI+PGkgY2xhc3M9XCJmYSBmYS1pbmZvXCIgYXJpYS1oaWRkZW49XCJ0cnVlXCI+PC9pPjwvYT5gO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBpZiAoIG9wdGlvbnMudXNlcl9jYXBhYmlsaXR5ICkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGAgPGEgY2xhc3M9XCJ0cm4tZGVsZXRlLWNoYWxsZW5nZS1hY3Rpb24gdHJuLWNvbmZpcm0tYWN0aW9uLWxpbmtcIiBkYXRhLWNoYWxsZW5nZS1pZD1cIiR7cm93LmNoYWxsZW5nZV9pZH1cIiBkYXRhLW1vZGFsLWlkPVwiZGVsZXRlLWNoYWxsZW5nZVwiIGRhdGEtY29uZmlybS10aXRsZT1cIiR7b3B0aW9ucy5sYW5ndWFnZS5kZWxldGVfY2hhbGxlbmdlfVwiIGRhdGEtY29uZmlybS1tZXNzYWdlPVwiJHtvcHRpb25zLmxhbmd1YWdlLmRlbGV0ZV9jb25maXJtLmZvcm1hdChyb3cuY2hhbGxlbmdlX2lkKX1cIiBocmVmPVwiJHtvcHRpb25zLmNoYWxsZW5nZV91cmx9JHtyb3cuY2hhbGxlbmdlX2lkfVwiIHRpdGxlPVwiJHtvcHRpb25zLmxhbmd1YWdlLmRlbGV0ZV9jaGFsbGVuZ2V9XCI+PGkgY2xhc3M9XCJmYSBmYS10cmFzaFwiPjwvaT48L2E+YDtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBjb250ZW50O1xyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgICAgIG9yZGVyYWJsZTogZmFsc2UsXHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICBdO1xyXG5cclxuICAgICAgICBqUXVlcnkoJyN0cm4tY2hhbGxlbmdlLWxpc3QtdGFibGUnKVxyXG4gICAgICAgICAgICAub24oJ3hoci5kdCcsIGZ1bmN0aW9uIChlLCBzZXR0aW5ncywganNvbiwgeGhyKSB7XHJcbiAgICAgICAgICAgICAgICBqc29uLmRhdGEgPSBKU09OLnBhcnNlKEpTT04uc3RyaW5naWZ5KGpzb24pKTtcclxuICAgICAgICAgICAgICAgIGpzb24ucmVjb3Jkc1RvdGFsID0geGhyLmdldFJlc3BvbnNlSGVhZGVyKCdYLVdQLVRvdGFsJyk7XHJcbiAgICAgICAgICAgICAgICBqc29uLnJlY29yZHNGaWx0ZXJlZCA9IHhoci5nZXRSZXNwb25zZUhlYWRlcignVFJOLUZpbHRlcmVkJyk7XHJcbiAgICAgICAgICAgICAgICBqc29uLmxlbmd0aCA9IHhoci5nZXRSZXNwb25zZUhlYWRlcignWC1XUC1Ub3RhbFBhZ2VzJyk7XHJcbiAgICAgICAgICAgICAgICBqc29uLmRyYXcgPSB4aHIuZ2V0UmVzcG9uc2VIZWFkZXIoJ1RSTi1EcmF3Jyk7XHJcbiAgICAgICAgICAgIH0pXHJcbiAgICAgICAgICAgIC5EYXRhVGFibGUoe1xyXG4gICAgICAgICAgICAgICAgcHJvY2Vzc2luZzogdHJ1ZSxcclxuICAgICAgICAgICAgICAgIHNlcnZlclNpZGU6IHRydWUsXHJcbiAgICAgICAgICAgICAgICBsZW5ndGhNZW51OiBbWzI1LCA1MCwgMTAwLCAtMV0sIFsyNSwgNTAsIDEwMCwgJ0FsbCddXSxcclxuICAgICAgICAgICAgICAgIGxhbmd1YWdlOiBvcHRpb25zWyd0YWJsZV9sYW5ndWFnZSddLFxyXG4gICAgICAgICAgICAgICAgYWpheDoge1xyXG4gICAgICAgICAgICAgICAgICAgIHVybDogYCR7b3B0aW9ucy5hcGlfdXJsfWNoYWxsZW5nZXMvP193cG5vbmNlPSR7b3B0aW9ucy5yZXN0X25vbmNlfSZfZW1iZWRgLFxyXG4gICAgICAgICAgICAgICAgICAgIHR5cGU6ICdHRVQnLFxyXG4gICAgICAgICAgICAgICAgICAgIGRhdGE6IGZ1bmN0aW9uIChkYXRhKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKGRhdGEpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBsZXQgc2VudCA9IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGRyYXc6IGRhdGEuZHJhdyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBhZ2U6IE1hdGguZmxvb3IoZGF0YS5zdGFydCAvIGRhdGEubGVuZ3RoKSxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBlcl9wYWdlOiBkYXRhLmxlbmd0aCxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNlYXJjaDogZGF0YS5zZWFyY2gudmFsdWUsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBvcmRlcmJ5OiBgJHtkYXRhLmNvbHVtbnNbZGF0YS5vcmRlclswXS5jb2x1bW5dLm5hbWV9LiR7ZGF0YS5vcmRlclswXS5kaXJ9YFxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9O1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhzZW50KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHNlbnQ7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgICAgIG9yZGVyOiBbWzMsICdhc2MnXV0sXHJcbiAgICAgICAgICAgICAgICBjb2x1bW5EZWZzOiBjb2x1bW5EZWZzLFxyXG4gICAgICAgICAgICAgICAgZHJhd0NhbGxiYWNrOiBmdW5jdGlvbiggc2V0dGluZ3MgKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgZG9jdW1lbnQuZGlzcGF0Y2hFdmVudCggbmV3IEN1c3RvbUV2ZW50KCAndHJuLWh0bWwtdXBkYXRlZCcsIHsgJ2RldGFpbCc6ICdUaGUgdGFibGUgaHRtbCBoYXMgdXBkYXRlZC4nIH0gKSk7XHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICB9KTtcclxuICAgIH0sIGZhbHNlKTtcclxufShqUXVlcnksIHRybikpOyIsIid1c2Ugc3RyaWN0JztcclxuY2xhc3MgVG91cm5hbWF0Y2gge1xyXG5cclxuICAgIGNvbnN0cnVjdG9yKCkge1xyXG4gICAgICAgIHRoaXMuZXZlbnRzID0ge307XHJcbiAgICB9XHJcblxyXG4gICAgcGFyYW0ob2JqZWN0LCBwcmVmaXgpIHtcclxuICAgICAgICBsZXQgc3RyID0gW107XHJcbiAgICAgICAgZm9yIChsZXQgcHJvcCBpbiBvYmplY3QpIHtcclxuICAgICAgICAgICAgaWYgKG9iamVjdC5oYXNPd25Qcm9wZXJ0eShwcm9wKSkge1xyXG4gICAgICAgICAgICAgICAgbGV0IGsgPSBwcmVmaXggPyBwcmVmaXggKyBcIltcIiArIHByb3AgKyBcIl1cIiA6IHByb3A7XHJcbiAgICAgICAgICAgICAgICBsZXQgdiA9IG9iamVjdFtwcm9wXTtcclxuICAgICAgICAgICAgICAgIHN0ci5wdXNoKCh2ICE9PSBudWxsICYmIHR5cGVvZiB2ID09PSBcIm9iamVjdFwiKSA/IHRoaXMucGFyYW0odiwgaykgOiBlbmNvZGVVUklDb21wb25lbnQoaykgKyBcIj1cIiArIGVuY29kZVVSSUNvbXBvbmVudCh2KSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuIHN0ci5qb2luKFwiJlwiKTtcclxuICAgIH1cclxuXHJcbiAgICBldmVudChldmVudE5hbWUpIHtcclxuICAgICAgICBpZiAoIShldmVudE5hbWUgaW4gdGhpcy5ldmVudHMpKSB7XHJcbiAgICAgICAgICAgIHRoaXMuZXZlbnRzW2V2ZW50TmFtZV0gPSBuZXcgRXZlbnRUYXJnZXQoZXZlbnROYW1lKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuIHRoaXMuZXZlbnRzW2V2ZW50TmFtZV07XHJcbiAgICB9XHJcblxyXG4gICAgYXV0b2NvbXBsZXRlKGlucHV0LCBkYXRhQ2FsbGJhY2spIHtcclxuICAgICAgICBuZXcgVG91cm5hbWF0Y2hfQXV0b2NvbXBsZXRlKGlucHV0LCBkYXRhQ2FsbGJhY2spO1xyXG4gICAgfVxyXG5cclxuICAgIHVjZmlyc3Qocykge1xyXG4gICAgICAgIGlmICh0eXBlb2YgcyAhPT0gJ3N0cmluZycpIHJldHVybiAnJztcclxuICAgICAgICByZXR1cm4gcy5jaGFyQXQoMCkudG9VcHBlckNhc2UoKSArIHMuc2xpY2UoMSk7XHJcbiAgICB9XHJcblxyXG4gICAgb3JkaW5hbF9zdWZmaXgobnVtYmVyKSB7XHJcbiAgICAgICAgY29uc3QgcmVtYWluZGVyID0gbnVtYmVyICUgMTAwO1xyXG5cclxuICAgICAgICBpZiAoKHJlbWFpbmRlciA8IDExKSB8fCAocmVtYWluZGVyID4gMTMpKSB7XHJcbiAgICAgICAgICAgIHN3aXRjaCAocmVtYWluZGVyICUgMTApIHtcclxuICAgICAgICAgICAgICAgIGNhc2UgMTogcmV0dXJuICdzdCc7XHJcbiAgICAgICAgICAgICAgICBjYXNlIDI6IHJldHVybiAnbmQnO1xyXG4gICAgICAgICAgICAgICAgY2FzZSAzOiByZXR1cm4gJ3JkJztcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gJ3RoJztcclxuICAgIH1cclxuXHJcbiAgICB0YWJzKGVsZW1lbnQpIHtcclxuICAgICAgICBjb25zdCB0YWJzID0gZWxlbWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tbmF2LWxpbmsnKTtcclxuICAgICAgICBjb25zdCBwYW5lcyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi10YWItcGFuZScpO1xyXG4gICAgICAgIGNvbnN0IGNsZWFyQWN0aXZlID0gKCkgPT4ge1xyXG4gICAgICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHRhYnMsICh0YWIpID0+IHtcclxuICAgICAgICAgICAgICAgIHRhYi5jbGFzc0xpc3QucmVtb3ZlKCd0cm4tbmF2LWFjdGl2ZScpO1xyXG4gICAgICAgICAgICAgICAgdGFiLmFyaWFTZWxlY3RlZCA9IGZhbHNlO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbChwYW5lcywgcGFuZSA9PiBwYW5lLmNsYXNzTGlzdC5yZW1vdmUoJ3Rybi10YWItYWN0aXZlJykpO1xyXG4gICAgICAgIH07XHJcbiAgICAgICAgY29uc3Qgc2V0QWN0aXZlID0gKHRhcmdldElkKSA9PiB7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFRhYiA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ2FbaHJlZj1cIiMnICsgdGFyZ2V0SWQgKyAnXCJdLnRybi1uYXYtbGluaycpO1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRQYW5lSWQgPSB0YXJnZXRUYWIgJiYgdGFyZ2V0VGFiLmRhdGFzZXQgJiYgdGFyZ2V0VGFiLmRhdGFzZXQudGFyZ2V0IHx8IGZhbHNlO1xyXG5cclxuICAgICAgICAgICAgaWYgKHRhcmdldFBhbmVJZCkge1xyXG4gICAgICAgICAgICAgICAgY2xlYXJBY3RpdmUoKTtcclxuICAgICAgICAgICAgICAgIHRhcmdldFRhYi5jbGFzc0xpc3QuYWRkKCd0cm4tbmF2LWFjdGl2ZScpO1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0VGFiLmFyaWFTZWxlY3RlZCA9IHRydWU7XHJcblxyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGFyZ2V0UGFuZUlkKS5jbGFzc0xpc3QuYWRkKCd0cm4tdGFiLWFjdGl2ZScpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfTtcclxuICAgICAgICBjb25zdCB0YWJDbGljayA9IChldmVudCkgPT4ge1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRUYWIgPSBldmVudC5jdXJyZW50VGFyZ2V0O1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRQYW5lSWQgPSB0YXJnZXRUYWIgJiYgdGFyZ2V0VGFiLmRhdGFzZXQgJiYgdGFyZ2V0VGFiLmRhdGFzZXQudGFyZ2V0IHx8IGZhbHNlO1xyXG5cclxuICAgICAgICAgICAgaWYgKHRhcmdldFBhbmVJZCkge1xyXG4gICAgICAgICAgICAgICAgc2V0QWN0aXZlKHRhcmdldFBhbmVJZCk7XHJcbiAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfTtcclxuXHJcbiAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbCh0YWJzLCAodGFiKSA9PiB7XHJcbiAgICAgICAgICAgIHRhYi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRhYkNsaWNrKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgaWYgKGxvY2F0aW9uLmhhc2gpIHtcclxuICAgICAgICAgICAgc2V0QWN0aXZlKGxvY2F0aW9uLmhhc2guc3Vic3RyKDEpKTtcclxuICAgICAgICB9IGVsc2UgaWYgKHRhYnMubGVuZ3RoID4gMCkge1xyXG4gICAgICAgICAgICBzZXRBY3RpdmUodGFic1swXS5kYXRhc2V0LnRhcmdldCk7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxufVxyXG5cclxuLy90cm4uaW5pdGlhbGl6ZSgpO1xyXG5pZiAoIXdpbmRvdy50cm5fb2JqX2luc3RhbmNlKSB7XHJcbiAgICB3aW5kb3cudHJuX29ial9pbnN0YW5jZSA9IG5ldyBUb3VybmFtYXRjaCgpO1xyXG5cclxuICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgZnVuY3Rpb24gKCkge1xyXG5cclxuICAgICAgICBjb25zdCB0YWJWaWV3cyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1uYXYnKTtcclxuXHJcbiAgICAgICAgQXJyYXkuZnJvbSh0YWJWaWV3cykuZm9yRWFjaCgodGFiKSA9PiB7XHJcbiAgICAgICAgICAgIHRybi50YWJzKHRhYik7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIGNvbnN0IGRyb3Bkb3ducyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1kcm9wZG93bi10b2dnbGUnKTtcclxuICAgICAgICBjb25zdCBoYW5kbGVEcm9wZG93bkNsb3NlID0gKCkgPT4ge1xyXG4gICAgICAgICAgICBBcnJheS5mcm9tKGRyb3Bkb3ducykuZm9yRWFjaCgoZHJvcGRvd24pID0+IHtcclxuICAgICAgICAgICAgICAgIGRyb3Bkb3duLm5leHRFbGVtZW50U2libGluZy5jbGFzc0xpc3QucmVtb3ZlKCd0cm4tc2hvdycpO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgZG9jdW1lbnQucmVtb3ZlRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGhhbmRsZURyb3Bkb3duQ2xvc2UsIGZhbHNlKTtcclxuICAgICAgICB9O1xyXG5cclxuICAgICAgICBBcnJheS5mcm9tKGRyb3Bkb3ducykuZm9yRWFjaCgoZHJvcGRvd24pID0+IHtcclxuICAgICAgICAgICAgZHJvcGRvd24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbihlKSB7XHJcbiAgICAgICAgICAgICAgICBlLnN0b3BQcm9wYWdhdGlvbigpO1xyXG4gICAgICAgICAgICAgICAgdGhpcy5uZXh0RWxlbWVudFNpYmxpbmcuY2xhc3NMaXN0LmFkZCgndHJuLXNob3cnKTtcclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBoYW5kbGVEcm9wZG93bkNsb3NlLCBmYWxzZSk7XHJcbiAgICAgICAgICAgIH0sIGZhbHNlKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICB9LCBmYWxzZSk7XHJcbn1cclxuZXhwb3J0IGxldCB0cm4gPSB3aW5kb3cudHJuX29ial9pbnN0YW5jZTtcclxuXHJcbmNsYXNzIFRvdXJuYW1hdGNoX0F1dG9jb21wbGV0ZSB7XHJcblxyXG4gICAgLy8gY3VycmVudEZvY3VzO1xyXG4gICAgLy9cclxuICAgIC8vIG5hbWVJbnB1dDtcclxuICAgIC8vXHJcbiAgICAvLyBzZWxmO1xyXG5cclxuICAgIGNvbnN0cnVjdG9yKGlucHV0LCBkYXRhQ2FsbGJhY2spIHtcclxuICAgICAgICAvLyB0aGlzLnNlbGYgPSB0aGlzO1xyXG4gICAgICAgIHRoaXMubmFtZUlucHV0ID0gaW5wdXQ7XHJcblxyXG4gICAgICAgIHRoaXMubmFtZUlucHV0LmFkZEV2ZW50TGlzdGVuZXIoXCJpbnB1dFwiLCAoKSA9PiB7XHJcbiAgICAgICAgICAgIGxldCBhLCBiLCBpLCB2YWwgPSB0aGlzLm5hbWVJbnB1dC52YWx1ZTsvL3RoaXMudmFsdWU7XHJcbiAgICAgICAgICAgIGxldCBwYXJlbnQgPSB0aGlzLm5hbWVJbnB1dC5wYXJlbnROb2RlOy8vdGhpcy5wYXJlbnROb2RlO1xyXG5cclxuICAgICAgICAgICAgLy8gbGV0IHAgPSBuZXcgUHJvbWlzZSgocmVzb2x2ZSwgcmVqZWN0KSA9PiB7XHJcbiAgICAgICAgICAgIC8vICAgICAvKiBuZWVkIHRvIHF1ZXJ5IHNlcnZlciBmb3IgbmFtZXMgaGVyZS4gKi9cclxuICAgICAgICAgICAgLy8gICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5vcGVuKCdHRVQnLCBvcHRpb25zLmFwaV91cmwgKyAncGxheWVycy8/c2VhcmNoPScgKyB2YWwgKyAnJnBlcl9wYWdlPTUnKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdDb250ZW50LVR5cGUnLCAnYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkJyk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignWC1XUC1Ob25jZScsIG9wdGlvbnMucmVzdF9ub25jZSk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIub25sb2FkID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAvLyAgICAgICAgIGlmICh4aHIuc3RhdHVzID09PSAyMDApIHtcclxuICAgICAgICAgICAgLy8gICAgICAgICAgICAgLy8gcmVzb2x2ZShKU09OLnBhcnNlKHhoci5yZXNwb25zZSkubWFwKChwbGF5ZXIpID0+IHtyZXR1cm4geyAndmFsdWUnOiBwbGF5ZXIuaWQsICd0ZXh0JzogcGxheWVyLm5hbWUgfTt9KSk7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgICAgIHJlc29sdmUoSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpLm1hcCgocGxheWVyKSA9PiB7cmV0dXJuIHBsYXllci5uYW1lO30pKTtcclxuICAgICAgICAgICAgLy8gICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAvLyAgICAgICAgICAgICByZWplY3QoKTtcclxuICAgICAgICAgICAgLy8gICAgICAgICB9XHJcbiAgICAgICAgICAgIC8vICAgICB9O1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLnNlbmQoKTtcclxuICAgICAgICAgICAgLy8gfSk7XHJcbiAgICAgICAgICAgIGRhdGFDYWxsYmFjayh2YWwpLnRoZW4oKGRhdGEpID0+IHsvL3AudGhlbigoZGF0YSkgPT4ge1xyXG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coZGF0YSk7XHJcblxyXG4gICAgICAgICAgICAgICAgLypjbG9zZSBhbnkgYWxyZWFkeSBvcGVuIGxpc3RzIG9mIGF1dG8tY29tcGxldGVkIHZhbHVlcyovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmNsb3NlQWxsTGlzdHMoKTtcclxuICAgICAgICAgICAgICAgIGlmICghdmFsKSB7IHJldHVybiBmYWxzZTt9XHJcbiAgICAgICAgICAgICAgICB0aGlzLmN1cnJlbnRGb2N1cyA9IC0xO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qY3JlYXRlIGEgRElWIGVsZW1lbnQgdGhhdCB3aWxsIGNvbnRhaW4gdGhlIGl0ZW1zICh2YWx1ZXMpOiovXHJcbiAgICAgICAgICAgICAgICBhID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcIkRJVlwiKTtcclxuICAgICAgICAgICAgICAgIGEuc2V0QXR0cmlidXRlKFwiaWRcIiwgdGhpcy5uYW1lSW5wdXQuaWQgKyBcIi1hdXRvLWNvbXBsZXRlLWxpc3RcIik7XHJcbiAgICAgICAgICAgICAgICBhLnNldEF0dHJpYnV0ZShcImNsYXNzXCIsIFwidHJuLWF1dG8tY29tcGxldGUtaXRlbXNcIik7XHJcblxyXG4gICAgICAgICAgICAgICAgLyphcHBlbmQgdGhlIERJViBlbGVtZW50IGFzIGEgY2hpbGQgb2YgdGhlIGF1dG8tY29tcGxldGUgY29udGFpbmVyOiovXHJcbiAgICAgICAgICAgICAgICBwYXJlbnQuYXBwZW5kQ2hpbGQoYSk7XHJcblxyXG4gICAgICAgICAgICAgICAgLypmb3IgZWFjaCBpdGVtIGluIHRoZSBhcnJheS4uLiovXHJcbiAgICAgICAgICAgICAgICBmb3IgKGkgPSAwOyBpIDwgZGF0YS5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICAgICAgICAgIGxldCB0ZXh0LCB2YWx1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgLyogV2hpY2ggZm9ybWF0IGRpZCB0aGV5IGdpdmUgdXMuICovXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBkYXRhW2ldID09PSAnb2JqZWN0Jykge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB0ZXh0ID0gZGF0YVtpXVsndGV4dCddO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB2YWx1ZSA9IGRhdGFbaV1bJ3ZhbHVlJ107XHJcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdGV4dCA9IGRhdGFbaV07XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlID0gZGF0YVtpXTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIC8qY2hlY2sgaWYgdGhlIGl0ZW0gc3RhcnRzIHdpdGggdGhlIHNhbWUgbGV0dGVycyBhcyB0aGUgdGV4dCBmaWVsZCB2YWx1ZToqL1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh0ZXh0LnN1YnN0cigwLCB2YWwubGVuZ3RoKS50b1VwcGVyQ2FzZSgpID09PSB2YWwudG9VcHBlckNhc2UoKSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKmNyZWF0ZSBhIERJViBlbGVtZW50IGZvciBlYWNoIG1hdGNoaW5nIGVsZW1lbnQ6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJESVZcIik7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qbWFrZSB0aGUgbWF0Y2hpbmcgbGV0dGVycyBib2xkOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuaW5uZXJIVE1MID0gXCI8c3Ryb25nPlwiICsgdGV4dC5zdWJzdHIoMCwgdmFsLmxlbmd0aCkgKyBcIjwvc3Ryb25nPlwiO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmlubmVySFRNTCArPSB0ZXh0LnN1YnN0cih2YWwubGVuZ3RoKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qaW5zZXJ0IGEgaW5wdXQgZmllbGQgdGhhdCB3aWxsIGhvbGQgdGhlIGN1cnJlbnQgYXJyYXkgaXRlbSdzIHZhbHVlOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuaW5uZXJIVE1MICs9IFwiPGlucHV0IHR5cGU9J2hpZGRlbicgdmFsdWU9J1wiICsgdmFsdWUgKyBcIic+XCI7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmRhdGFzZXQudmFsdWUgPSB2YWx1ZTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5kYXRhc2V0LnRleHQgPSB0ZXh0O1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgLypleGVjdXRlIGEgZnVuY3Rpb24gd2hlbiBzb21lb25lIGNsaWNrcyBvbiB0aGUgaXRlbSB2YWx1ZSAoRElWIGVsZW1lbnQpOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIChlKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhgaXRlbSBjbGlja2VkIHdpdGggdmFsdWUgJHtlLmN1cnJlbnRUYXJnZXQuZGF0YXNldC52YWx1ZX1gKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAvKiBpbnNlcnQgdGhlIHZhbHVlIGZvciB0aGUgYXV0b2NvbXBsZXRlIHRleHQgZmllbGQ6ICovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLm5hbWVJbnB1dC52YWx1ZSA9IGUuY3VycmVudFRhcmdldC5kYXRhc2V0LnRleHQ7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLm5hbWVJbnB1dC5kYXRhc2V0LnNlbGVjdGVkSWQgPSBlLmN1cnJlbnRUYXJnZXQuZGF0YXNldC52YWx1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAvKiBjbG9zZSB0aGUgbGlzdCBvZiBhdXRvY29tcGxldGVkIHZhbHVlcywgKG9yIGFueSBvdGhlciBvcGVuIGxpc3RzIG9mIGF1dG9jb21wbGV0ZWQgdmFsdWVzOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLmNsb3NlQWxsTGlzdHMoKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLm5hbWVJbnB1dC5kaXNwYXRjaEV2ZW50KG5ldyBFdmVudCgnY2hhbmdlJykpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYS5hcHBlbmRDaGlsZChiKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiBwcmVzc2VzIGEga2V5IG9uIHRoZSBrZXlib2FyZDoqL1xyXG4gICAgICAgIHRoaXMubmFtZUlucHV0LmFkZEV2ZW50TGlzdGVuZXIoXCJrZXlkb3duXCIsIChlKSA9PiB7XHJcbiAgICAgICAgICAgIGxldCB4ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGhpcy5uYW1lSW5wdXQuaWQgKyBcIi1hdXRvLWNvbXBsZXRlLWxpc3RcIik7XHJcbiAgICAgICAgICAgIGlmICh4KSB4ID0geC5nZXRFbGVtZW50c0J5VGFnTmFtZShcImRpdlwiKTtcclxuICAgICAgICAgICAgaWYgKGUua2V5Q29kZSA9PT0gNDApIHtcclxuICAgICAgICAgICAgICAgIC8qSWYgdGhlIGFycm93IERPV04ga2V5IGlzIHByZXNzZWQsXHJcbiAgICAgICAgICAgICAgICAgaW5jcmVhc2UgdGhlIGN1cnJlbnRGb2N1cyB2YXJpYWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50Rm9jdXMrKztcclxuICAgICAgICAgICAgICAgIC8qYW5kIGFuZCBtYWtlIHRoZSBjdXJyZW50IGl0ZW0gbW9yZSB2aXNpYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmFkZEFjdGl2ZSh4KTtcclxuICAgICAgICAgICAgfSBlbHNlIGlmIChlLmtleUNvZGUgPT09IDM4KSB7IC8vdXBcclxuICAgICAgICAgICAgICAgIC8qSWYgdGhlIGFycm93IFVQIGtleSBpcyBwcmVzc2VkLFxyXG4gICAgICAgICAgICAgICAgIGRlY3JlYXNlIHRoZSBjdXJyZW50Rm9jdXMgdmFyaWFibGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudEZvY3VzLS07XHJcbiAgICAgICAgICAgICAgICAvKmFuZCBhbmQgbWFrZSB0aGUgY3VycmVudCBpdGVtIG1vcmUgdmlzaWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5hZGRBY3RpdmUoeCk7XHJcbiAgICAgICAgICAgIH0gZWxzZSBpZiAoZS5rZXlDb2RlID09PSAxMykge1xyXG4gICAgICAgICAgICAgICAgLypJZiB0aGUgRU5URVIga2V5IGlzIHByZXNzZWQsIHByZXZlbnQgdGhlIGZvcm0gZnJvbSBiZWluZyBzdWJtaXR0ZWQsKi9cclxuICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgICAgIGlmICh0aGlzLmN1cnJlbnRGb2N1cyA+IC0xKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgLyphbmQgc2ltdWxhdGUgYSBjbGljayBvbiB0aGUgXCJhY3RpdmVcIiBpdGVtOiovXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHgpIHhbdGhpcy5jdXJyZW50Rm9jdXNdLmNsaWNrKCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgLypleGVjdXRlIGEgZnVuY3Rpb24gd2hlbiBzb21lb25lIGNsaWNrcyBpbiB0aGUgZG9jdW1lbnQ6Ki9cclxuICAgICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKGUpID0+IHtcclxuICAgICAgICAgICAgdGhpcy5jbG9zZUFsbExpc3RzKGUudGFyZ2V0KTtcclxuICAgICAgICB9KTtcclxuICAgIH1cclxuXHJcbiAgICBhZGRBY3RpdmUoeCkge1xyXG4gICAgICAgIC8qYSBmdW5jdGlvbiB0byBjbGFzc2lmeSBhbiBpdGVtIGFzIFwiYWN0aXZlXCI6Ki9cclxuICAgICAgICBpZiAoIXgpIHJldHVybiBmYWxzZTtcclxuICAgICAgICAvKnN0YXJ0IGJ5IHJlbW92aW5nIHRoZSBcImFjdGl2ZVwiIGNsYXNzIG9uIGFsbCBpdGVtczoqL1xyXG4gICAgICAgIHRoaXMucmVtb3ZlQWN0aXZlKHgpO1xyXG4gICAgICAgIGlmICh0aGlzLmN1cnJlbnRGb2N1cyA+PSB4Lmxlbmd0aCkgdGhpcy5jdXJyZW50Rm9jdXMgPSAwO1xyXG4gICAgICAgIGlmICh0aGlzLmN1cnJlbnRGb2N1cyA8IDApIHRoaXMuY3VycmVudEZvY3VzID0gKHgubGVuZ3RoIC0gMSk7XHJcbiAgICAgICAgLyphZGQgY2xhc3MgXCJhdXRvY29tcGxldGUtYWN0aXZlXCI6Ki9cclxuICAgICAgICB4W3RoaXMuY3VycmVudEZvY3VzXS5jbGFzc0xpc3QuYWRkKFwidHJuLWF1dG8tY29tcGxldGUtYWN0aXZlXCIpO1xyXG4gICAgfVxyXG5cclxuICAgIHJlbW92ZUFjdGl2ZSh4KSB7XHJcbiAgICAgICAgLyphIGZ1bmN0aW9uIHRvIHJlbW92ZSB0aGUgXCJhY3RpdmVcIiBjbGFzcyBmcm9tIGFsbCBhdXRvY29tcGxldGUgaXRlbXM6Ki9cclxuICAgICAgICBmb3IgKGxldCBpID0gMDsgaSA8IHgubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgeFtpXS5jbGFzc0xpc3QucmVtb3ZlKFwidHJuLWF1dG8tY29tcGxldGUtYWN0aXZlXCIpO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbiAgICBjbG9zZUFsbExpc3RzKGVsZW1lbnQpIHtcclxuICAgICAgICBjb25zb2xlLmxvZyhcImNsb3NlIGFsbCBsaXN0c1wiKTtcclxuICAgICAgICAvKmNsb3NlIGFsbCBhdXRvY29tcGxldGUgbGlzdHMgaW4gdGhlIGRvY3VtZW50LFxyXG4gICAgICAgICBleGNlcHQgdGhlIG9uZSBwYXNzZWQgYXMgYW4gYXJndW1lbnQ6Ki9cclxuICAgICAgICBsZXQgeCA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoXCJ0cm4tYXV0by1jb21wbGV0ZS1pdGVtc1wiKTtcclxuICAgICAgICBmb3IgKGxldCBpID0gMDsgaSA8IHgubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgaWYgKGVsZW1lbnQgIT09IHhbaV0gJiYgZWxlbWVudCAhPT0gdGhpcy5uYW1lSW5wdXQpIHtcclxuICAgICAgICAgICAgICAgIHhbaV0ucGFyZW50Tm9kZS5yZW1vdmVDaGlsZCh4W2ldKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH1cclxufVxyXG5cclxuLy8gRmlyc3QsIGNoZWNrcyBpZiBpdCBpc24ndCBpbXBsZW1lbnRlZCB5ZXQuXHJcbmlmICghU3RyaW5nLnByb3RvdHlwZS5mb3JtYXQpIHtcclxuICAgIFN0cmluZy5wcm90b3R5cGUuZm9ybWF0ID0gZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgY29uc3QgYXJncyA9IGFyZ3VtZW50cztcclxuICAgICAgICByZXR1cm4gdGhpcy5yZXBsYWNlKC97KFxcZCspfS9nLCBmdW5jdGlvbihtYXRjaCwgbnVtYmVyKSB7XHJcbiAgICAgICAgICAgIHJldHVybiB0eXBlb2YgYXJnc1tudW1iZXJdICE9PSAndW5kZWZpbmVkJ1xyXG4gICAgICAgICAgICAgICAgPyBhcmdzW251bWJlcl1cclxuICAgICAgICAgICAgICAgIDogbWF0Y2hcclxuICAgICAgICAgICAgICAgIDtcclxuICAgICAgICB9KTtcclxuICAgIH07XHJcbn0iXSwic291cmNlUm9vdCI6IiJ9