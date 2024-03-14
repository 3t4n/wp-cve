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
/******/ 	return __webpack_require__(__webpack_require__.s = 33);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/standings.js":
/*!*****************************!*\
  !*** ./src/js/standings.js ***!
  \*****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./tournamatch.js */ "./src/js/tournamatch.js");
/**
 * Ladder standings page.
 *
 * @link       https://www.tournamatch.com
 * @since      3.11.0
 *
 * @package    Tournamatch
 *
 */


(function ($) {
  'use strict';

  var options = trn_ladder_standings_options;

  function handlePromoteLink() {
    var promoteLinks = document.getElementsByClassName('trn-promote-competitor-link');
    Array.prototype.forEach.call(promoteLinks, function (promoteLink) {
      promoteLink.addEventListener('click', function (event) {
        event.preventDefault();
        var xhr = new XMLHttpRequest();
        xhr.open('POST', "".concat(options.api_url, "ladder-competitors/").concat(promoteLink.dataset.competitorId, "/promote"));
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

        xhr.onload = function () {
          if (xhr.status === 303) {
            window.location.reload();
          } else {
            var response = JSON.parse(xhr.response);
            document.getElementById('trn-promote-competitor-response').innerHTML = "<div class=\"trn-alert trn-alert-danger\"><strong>".concat(options.language.failure, "</strong>: ").concat(response.message, "</div>");
          }
        };

        xhr.send();
      }, false);
    });
  }

  function handleDeleteConfirm() {
    var links = document.getElementsByClassName('trn-remove-competitor-link');
    Array.prototype.forEach.call(links, function (link) {
      link.addEventListener('trn.confirmed.action.delete-competitor', function (event) {
        event.preventDefault();
        console.log("modal was confirmed for link ".concat(link.dataset.competitorId));
        var xhr = new XMLHttpRequest();
        xhr.open('DELETE', "".concat(options.api_url, "ladder-competitors/").concat(link.dataset.competitorId, "?admin=1"));
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

        xhr.onload = function () {
          if (xhr.status === 204) {
            window.location.reload();
          } else {
            var response = JSON.parse(xhr.response);
            document.getElementById('trn-remove-competitor-response').innerHTML = "<div class=\"trn-alert trn-alert-danger\"><strong>".concat(options.language.failure, "</strong>: ").concat(response.message, "</div>");
          }
        };

        xhr.send();
      }, false);
    });
  }

  window.addEventListener('load', function () {
    document.addEventListener('trn-html-updated', function (e) {
      handleDeleteConfirm();
      handlePromoteLink();
    });
    handleDeleteConfirm();
    handlePromoteLink();
    var default_target = options.default_target;
    var target = 0;
    var columnDefs = [{
      targets: target++,
      name: 'number',
      className: 'trn-ladder-standings-table-number',
      render: function render(data, type, row, meta) {
        return meta.row + meta.settings._iDisplayStart + 1;
      },
      orderable: false
    }, {
      targets: target++,
      name: 'name',
      className: 'trn-ladder-standings-table-name',
      render: function render(data, type, row) {
        return "<img src=\"".concat(options.flag_path).concat(row._embedded.competitor[0].flag, "\" width=\"18\" height=\"12\" title=\"").concat(row._embedded.competitor[0].flag, "\"> <a href=\"").concat(row._embedded.competitor[0].link, "\">").concat(row._embedded.competitor[0].name, "</a>");
      }
    }, {
      targets: target++,
      name: default_target,
      className: 'trn-ladder-standings-table-rating rating',
      render: function render(data, type, row) {
        return row[default_target];
      }
    }, {
      targets: target++,
      name: 'games_played',
      className: 'trn-ladder-standings-table-games-played',
      render: function render(data, type, row) {
        return row.games_played;
      }
    }, {
      targets: target++,
      name: 'wins',
      className: 'trn-ladder-standings-table-wins wins',
      render: function render(data, type, row) {
        return row.wins;
      }
    }, {
      targets: target++,
      name: 'losses',
      className: 'trn-ladder-standings-table-losses losses',
      render: function render(data, type, row) {
        return row.losses;
      }
    }];

    if (options.uses_draws) {
      columnDefs.push({
        targets: target++,
        name: 'draws',
        className: 'trn-ladder-standings-table-draws ties',
        render: function render(data, type, row) {
          return row.draws;
        }
      });
    }

    if (options.uses_scores) {
      columnDefs.push({
        targets: target++,
        name: 'goals_for',
        className: 'trn-ladder-standings-table-goals-for',
        render: function render(data, type, row) {
          return row.goals_for;
        }
      }, {
        targets: target++,
        name: 'goals_against',
        className: 'trn-ladder-standings-table-goals-against',
        render: function render(data, type, row) {
          return row.goals_against;
        }
      }, {
        targets: target++,
        name: 'goals_difference',
        className: 'trn-ladder-standings-table-goals-difference',
        render: function render(data, type, row) {
          return row.goals_delta;
        }
      });
    }

    columnDefs.push({
      targets: target++,
      name: 'win_percent',
      className: 'trn-ladder-standings-table-win-percent',
      render: function render(data, type, row) {
        return row.win_percent;
      }
    }, {
      targets: target++,
      name: 'streak',
      className: 'trn-ladder-standings-table-streak',
      render: function render(data, type, row) {
        var streakClass;

        if (0 > row.streak) {
          streakClass = "negative-streak";
        } else if (0 < row.streak) {
          streakClass = "positive-streak";
        } else {
          streakClass = "";
        }

        return "<span class=\"".concat(streakClass, "\">").concat(row.streak, "</span>");
      }
    }, {
      targets: target++,
      name: 'idle',
      className: 'trn-ladder-standings-table-idle',
      render: function render(data, type, row) {
        var idleClass;

        if (7 >= row.days_idle) {
          idleClass = "trn-ladder-active-last-7";
        } else if (14 >= row.days_idle) {
          idleClass = "trn-ladder-active-last-14";
        } else if (21 >= row.days_idle) {
          idleClass = "trn-ladder-active-last-21";
        } else {
          idleClass = "trn-ladder-inactive";
        }

        return "<span class=\"".concat(idleClass, "\">").concat(row.days_idle, "</span>");
      }
    });

    if (options.can_challenge || options.is_admin) {
      columnDefs.push({
        targets: target,
        name: 'actions',
        className: 'trn-ladder-standings-table-actions',
        render: function render(data, type, row) {
          var links = [];

          if (options.can_challenge) {
            links.push("<a href=\"".concat(options.challenge_url).concat(row.competitor_id, "\" title=\"").concat(options.language.challenge_link_title, "\"><i class=\"fa fa-crosshairs\" aria-hidden=\"true\"></i></a>"));
          }

          if (options.is_admin) {
            links.push("<a href=\"".concat(row.edit_link, "\" title=\"").concat(options.language.edit_link_title, "\"><i class=\"fa fa-edit\" aria-hidden=\"true\"></i></a>"));
            var competitor_name = "";

            if ('player' === row.competitor_type) {
              competitor_name = options.language.confirm_delete_message.format(row._embedded.competitor[0].name);
            } else {
              competitor_name = options.language.confirm_delete_message.format(row._embedded.competitor[0].name);
            }

            links.push("<a class=\"trn-remove-competitor-link trn-confirm-action-link\" href=\"#\" title=\"".concat(options.language.remove_link_title, "\" data-competitor-id=\"").concat(row.ladder_entry_id, "\" data-confirm-title=\"").concat(options.language.confirm_delete_title, "\" data-confirm-message=\"").concat(competitor_name, "\" data-modal-id=\"delete-competitor\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a>"));

            if (options.can_promote && 1 !== row.rank) {
              links.push("<a class=\"trn-promote-competitor-link\" href=\"#\" title=\"".concat(options.language.promote_link_title, "\" data-competitor-id=\"").concat(row.ladder_competitor_id, "\"><i class=\"fa fa-long-arrow-alt-up\" aria-hidden=\"true\"></i></a>"));
            }
          }

          if (links.length > 0) {
            return links.join(' ');
          } else {
            return "";
          }
        },
        orderable: false
      });
    }

    var default_direction = 'desc';
    var standings = jQuery('#ladder-standings-table').on('xhr.dt', function (e, settings, json, xhr) {
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
        url: "".concat(options.api_url, "ladder-competitors/?_wpnonce=").concat(options.rest_nonce, "&_embed&ladder_id=").concat(options.ladder_id),
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
      order: [[2, default_direction]],
      columnDefs: columnDefs,
      drawCallback: function drawCallback(settings) {
        document.dispatchEvent(new CustomEvent('trn-html-updated', {
          'detail': 'The table html has updated.'
        }));
      }
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

/***/ 33:
/*!***********************************!*\
  !*** multi ./src/js/standings.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\standings.js */"./src/js/standings.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3N0YW5kaW5ncy5qcyIsIndlYnBhY2s6Ly8vLi9zcmMvanMvdG91cm5hbWF0Y2guanMiXSwibmFtZXMiOlsiJCIsIm9wdGlvbnMiLCJ0cm5fbGFkZGVyX3N0YW5kaW5nc19vcHRpb25zIiwiaGFuZGxlUHJvbW90ZUxpbmsiLCJwcm9tb3RlTGlua3MiLCJkb2N1bWVudCIsImdldEVsZW1lbnRzQnlDbGFzc05hbWUiLCJBcnJheSIsInByb3RvdHlwZSIsImZvckVhY2giLCJjYWxsIiwicHJvbW90ZUxpbmsiLCJhZGRFdmVudExpc3RlbmVyIiwiZXZlbnQiLCJwcmV2ZW50RGVmYXVsdCIsInhociIsIlhNTEh0dHBSZXF1ZXN0Iiwib3BlbiIsImFwaV91cmwiLCJkYXRhc2V0IiwiY29tcGV0aXRvcklkIiwic2V0UmVxdWVzdEhlYWRlciIsInJlc3Rfbm9uY2UiLCJvbmxvYWQiLCJzdGF0dXMiLCJ3aW5kb3ciLCJsb2NhdGlvbiIsInJlbG9hZCIsInJlc3BvbnNlIiwiSlNPTiIsInBhcnNlIiwiZ2V0RWxlbWVudEJ5SWQiLCJpbm5lckhUTUwiLCJsYW5ndWFnZSIsImZhaWx1cmUiLCJtZXNzYWdlIiwic2VuZCIsImhhbmRsZURlbGV0ZUNvbmZpcm0iLCJsaW5rcyIsImxpbmsiLCJjb25zb2xlIiwibG9nIiwiZSIsImRlZmF1bHRfdGFyZ2V0IiwidGFyZ2V0IiwiY29sdW1uRGVmcyIsInRhcmdldHMiLCJuYW1lIiwiY2xhc3NOYW1lIiwicmVuZGVyIiwiZGF0YSIsInR5cGUiLCJyb3ciLCJtZXRhIiwic2V0dGluZ3MiLCJfaURpc3BsYXlTdGFydCIsIm9yZGVyYWJsZSIsImZsYWdfcGF0aCIsIl9lbWJlZGRlZCIsImNvbXBldGl0b3IiLCJmbGFnIiwiZ2FtZXNfcGxheWVkIiwid2lucyIsImxvc3NlcyIsInVzZXNfZHJhd3MiLCJwdXNoIiwiZHJhd3MiLCJ1c2VzX3Njb3JlcyIsImdvYWxzX2ZvciIsImdvYWxzX2FnYWluc3QiLCJnb2Fsc19kZWx0YSIsIndpbl9wZXJjZW50Iiwic3RyZWFrQ2xhc3MiLCJzdHJlYWsiLCJpZGxlQ2xhc3MiLCJkYXlzX2lkbGUiLCJjYW5fY2hhbGxlbmdlIiwiaXNfYWRtaW4iLCJjaGFsbGVuZ2VfdXJsIiwiY29tcGV0aXRvcl9pZCIsImNoYWxsZW5nZV9saW5rX3RpdGxlIiwiZWRpdF9saW5rIiwiZWRpdF9saW5rX3RpdGxlIiwiY29tcGV0aXRvcl9uYW1lIiwiY29tcGV0aXRvcl90eXBlIiwiY29uZmlybV9kZWxldGVfbWVzc2FnZSIsImZvcm1hdCIsInJlbW92ZV9saW5rX3RpdGxlIiwibGFkZGVyX2VudHJ5X2lkIiwiY29uZmlybV9kZWxldGVfdGl0bGUiLCJjYW5fcHJvbW90ZSIsInJhbmsiLCJwcm9tb3RlX2xpbmtfdGl0bGUiLCJsYWRkZXJfY29tcGV0aXRvcl9pZCIsImxlbmd0aCIsImpvaW4iLCJkZWZhdWx0X2RpcmVjdGlvbiIsInN0YW5kaW5ncyIsImpRdWVyeSIsIm9uIiwianNvbiIsInN0cmluZ2lmeSIsInJlY29yZHNUb3RhbCIsImdldFJlc3BvbnNlSGVhZGVyIiwicmVjb3Jkc0ZpbHRlcmVkIiwiZHJhdyIsIkRhdGFUYWJsZSIsInByb2Nlc3NpbmciLCJzZXJ2ZXJTaWRlIiwibGVuZ3RoTWVudSIsInRhYmxlX2xhbmd1YWdlIiwiYXV0b1dpZHRoIiwiYWpheCIsInVybCIsImxhZGRlcl9pZCIsInNlbnQiLCJwYWdlIiwiTWF0aCIsImZsb29yIiwic3RhcnQiLCJwZXJfcGFnZSIsInNlYXJjaCIsInZhbHVlIiwib3JkZXJieSIsImNvbHVtbnMiLCJvcmRlciIsImNvbHVtbiIsImRpciIsImRyYXdDYWxsYmFjayIsImRpc3BhdGNoRXZlbnQiLCJDdXN0b21FdmVudCIsInRybiIsIlRvdXJuYW1hdGNoIiwiZXZlbnRzIiwib2JqZWN0IiwicHJlZml4Iiwic3RyIiwicHJvcCIsImhhc093blByb3BlcnR5IiwiayIsInYiLCJwYXJhbSIsImVuY29kZVVSSUNvbXBvbmVudCIsImV2ZW50TmFtZSIsIkV2ZW50VGFyZ2V0IiwiaW5wdXQiLCJkYXRhQ2FsbGJhY2siLCJUb3VybmFtYXRjaF9BdXRvY29tcGxldGUiLCJzIiwiY2hhckF0IiwidG9VcHBlckNhc2UiLCJzbGljZSIsIm51bWJlciIsInJlbWFpbmRlciIsImVsZW1lbnQiLCJ0YWJzIiwicGFuZXMiLCJjbGVhckFjdGl2ZSIsInRhYiIsImNsYXNzTGlzdCIsInJlbW92ZSIsImFyaWFTZWxlY3RlZCIsInBhbmUiLCJzZXRBY3RpdmUiLCJ0YXJnZXRJZCIsInRhcmdldFRhYiIsInF1ZXJ5U2VsZWN0b3IiLCJ0YXJnZXRQYW5lSWQiLCJhZGQiLCJ0YWJDbGljayIsImN1cnJlbnRUYXJnZXQiLCJoYXNoIiwic3Vic3RyIiwidHJuX29ial9pbnN0YW5jZSIsInRhYlZpZXdzIiwiZnJvbSIsImRyb3Bkb3ducyIsImhhbmRsZURyb3Bkb3duQ2xvc2UiLCJkcm9wZG93biIsIm5leHRFbGVtZW50U2libGluZyIsInJlbW92ZUV2ZW50TGlzdGVuZXIiLCJzdG9wUHJvcGFnYXRpb24iLCJuYW1lSW5wdXQiLCJhIiwiYiIsImkiLCJ2YWwiLCJwYXJlbnQiLCJwYXJlbnROb2RlIiwidGhlbiIsImNsb3NlQWxsTGlzdHMiLCJjdXJyZW50Rm9jdXMiLCJjcmVhdGVFbGVtZW50Iiwic2V0QXR0cmlidXRlIiwiaWQiLCJhcHBlbmRDaGlsZCIsInRleHQiLCJzZWxlY3RlZElkIiwiRXZlbnQiLCJ4IiwiZ2V0RWxlbWVudHNCeVRhZ05hbWUiLCJrZXlDb2RlIiwiYWRkQWN0aXZlIiwiY2xpY2siLCJyZW1vdmVBY3RpdmUiLCJyZW1vdmVDaGlsZCIsIlN0cmluZyIsImFyZ3MiLCJhcmd1bWVudHMiLCJyZXBsYWNlIiwibWF0Y2giXSwibWFwcGluZ3MiOiI7UUFBQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7O1FBRUE7UUFD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QUFoQixDQUE0Qk4sQ0FBQyxDQUFDZCxDQUFELENBQTdCO0FBQ0g7QUFDSjtBQUNKOzs7O0tBR0w7OztBQUNBLElBQUksQ0FBQ3FCLE1BQU0sQ0FBQ3RNLFNBQVAsQ0FBaUI4RSxNQUF0QixFQUE4QjtBQUMxQndILFFBQU0sQ0FBQ3RNLFNBQVAsQ0FBaUI4RSxNQUFqQixHQUEwQixZQUFXO0FBQ2pDLFFBQU15SCxJQUFJLEdBQUdDLFNBQWI7QUFDQSxXQUFPLEtBQUtDLE9BQUwsQ0FBYSxVQUFiLEVBQXlCLFVBQVNDLEtBQVQsRUFBZ0IxRCxNQUFoQixFQUF3QjtBQUNwRCxhQUFPLE9BQU91RCxJQUFJLENBQUN2RCxNQUFELENBQVgsS0FBd0IsV0FBeEIsR0FDRHVELElBQUksQ0FBQ3ZELE1BQUQsQ0FESCxHQUVEMEQsS0FGTjtBQUlILEtBTE0sQ0FBUDtBQU1ILEdBUkQ7QUFTSCxDIiwiZmlsZSI6InN0YW5kaW5ncy5qcyIsInNvdXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwgeyBlbnVtZXJhYmxlOiB0cnVlLCBnZXQ6IGdldHRlciB9KTtcbiBcdFx0fVxuIFx0fTtcblxuIFx0Ly8gZGVmaW5lIF9fZXNNb2R1bGUgb24gZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yID0gZnVuY3Rpb24oZXhwb3J0cykge1xuIFx0XHRpZih0eXBlb2YgU3ltYm9sICE9PSAndW5kZWZpbmVkJyAmJiBTeW1ib2wudG9TdHJpbmdUYWcpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgU3ltYm9sLnRvU3RyaW5nVGFnLCB7IHZhbHVlOiAnTW9kdWxlJyB9KTtcbiBcdFx0fVxuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgJ19fZXNNb2R1bGUnLCB7IHZhbHVlOiB0cnVlIH0pO1xuIFx0fTtcblxuIFx0Ly8gY3JlYXRlIGEgZmFrZSBuYW1lc3BhY2Ugb2JqZWN0XG4gXHQvLyBtb2RlICYgMTogdmFsdWUgaXMgYSBtb2R1bGUgaWQsIHJlcXVpcmUgaXRcbiBcdC8vIG1vZGUgJiAyOiBtZXJnZSBhbGwgcHJvcGVydGllcyBvZiB2YWx1ZSBpbnRvIHRoZSBuc1xuIFx0Ly8gbW9kZSAmIDQ6IHJldHVybiB2YWx1ZSB3aGVuIGFscmVhZHkgbnMgb2JqZWN0XG4gXHQvLyBtb2RlICYgOHwxOiBiZWhhdmUgbGlrZSByZXF1aXJlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnQgPSBmdW5jdGlvbih2YWx1ZSwgbW9kZSkge1xuIFx0XHRpZihtb2RlICYgMSkgdmFsdWUgPSBfX3dlYnBhY2tfcmVxdWlyZV9fKHZhbHVlKTtcbiBcdFx0aWYobW9kZSAmIDgpIHJldHVybiB2YWx1ZTtcbiBcdFx0aWYoKG1vZGUgJiA0KSAmJiB0eXBlb2YgdmFsdWUgPT09ICdvYmplY3QnICYmIHZhbHVlICYmIHZhbHVlLl9fZXNNb2R1bGUpIHJldHVybiB2YWx1ZTtcbiBcdFx0dmFyIG5zID0gT2JqZWN0LmNyZWF0ZShudWxsKTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yKG5zKTtcbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KG5zLCAnZGVmYXVsdCcsIHsgZW51bWVyYWJsZTogdHJ1ZSwgdmFsdWU6IHZhbHVlIH0pO1xuIFx0XHRpZihtb2RlICYgMiAmJiB0eXBlb2YgdmFsdWUgIT0gJ3N0cmluZycpIGZvcih2YXIga2V5IGluIHZhbHVlKSBfX3dlYnBhY2tfcmVxdWlyZV9fLmQobnMsIGtleSwgZnVuY3Rpb24oa2V5KSB7IHJldHVybiB2YWx1ZVtrZXldOyB9LmJpbmQobnVsbCwga2V5KSk7XG4gXHRcdHJldHVybiBucztcbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiXCI7XG5cblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSAzMyk7XG4iLCIvKipcclxuICogTGFkZGVyIHN0YW5kaW5ncyBwYWdlLlxyXG4gKlxyXG4gKiBAbGluayAgICAgICBodHRwczovL3d3dy50b3VybmFtYXRjaC5jb21cclxuICogQHNpbmNlICAgICAgMy4xMS4wXHJcbiAqXHJcbiAqIEBwYWNrYWdlICAgIFRvdXJuYW1hdGNoXHJcbiAqXHJcbiAqL1xyXG5pbXBvcnQgeyB0cm4gfSBmcm9tICcuL3RvdXJuYW1hdGNoLmpzJztcclxuXHJcbihmdW5jdGlvbiAoJCkge1xyXG4gICAgJ3VzZSBzdHJpY3QnO1xyXG5cclxuICAgIGxldCBvcHRpb25zID0gdHJuX2xhZGRlcl9zdGFuZGluZ3Nfb3B0aW9ucztcclxuXHJcbiAgICBmdW5jdGlvbiBoYW5kbGVQcm9tb3RlTGluaygpIHtcclxuICAgICAgICBsZXQgcHJvbW90ZUxpbmtzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLXByb21vdGUtY29tcGV0aXRvci1saW5rJyk7XHJcbiAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbChwcm9tb3RlTGlua3MsIGZ1bmN0aW9uIChwcm9tb3RlTGluaykge1xyXG4gICAgICAgICAgICBwcm9tb3RlTGluay5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uIChldmVudCkge1xyXG4gICAgICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcclxuXHJcbiAgICAgICAgICAgICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgICAgICB4aHIub3BlbignUE9TVCcsIGAke29wdGlvbnMuYXBpX3VybH1sYWRkZXItY29tcGV0aXRvcnMvJHtwcm9tb3RlTGluay5kYXRhc2V0LmNvbXBldGl0b3JJZH0vcHJvbW90ZWApO1xyXG4gICAgICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDMwMykge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB3aW5kb3cubG9jYXRpb24ucmVsb2FkKCk7XHJcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbGV0IHJlc3BvbnNlID0gSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLXByb21vdGUtY29tcGV0aXRvci1yZXNwb25zZScpLmlubmVySFRNTCA9IGA8ZGl2IGNsYXNzPVwidHJuLWFsZXJ0IHRybi1hbGVydC1kYW5nZXJcIj48c3Ryb25nPiR7b3B0aW9ucy5sYW5ndWFnZS5mYWlsdXJlfTwvc3Ryb25nPjogJHtyZXNwb25zZS5tZXNzYWdlfTwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgICAgICB4aHIuc2VuZCgpO1xyXG4gICAgICAgICAgICB9LCBmYWxzZSk7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9XHJcblxyXG4gICAgZnVuY3Rpb24gaGFuZGxlRGVsZXRlQ29uZmlybSgpIHtcclxuICAgICAgICBsZXQgbGlua3MgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tcmVtb3ZlLWNvbXBldGl0b3ItbGluaycpO1xyXG4gICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwobGlua3MsIGZ1bmN0aW9uIChsaW5rKSB7XHJcbiAgICAgICAgICAgIGxpbmsuYWRkRXZlbnRMaXN0ZW5lcigndHJuLmNvbmZpcm1lZC5hY3Rpb24uZGVsZXRlLWNvbXBldGl0b3InLCBmdW5jdGlvbiAoZXZlbnQpIHtcclxuICAgICAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XHJcblxyXG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coYG1vZGFsIHdhcyBjb25maXJtZWQgZm9yIGxpbmsgJHtsaW5rLmRhdGFzZXQuY29tcGV0aXRvcklkfWApO1xyXG4gICAgICAgICAgICAgICAgbGV0IHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xyXG4gICAgICAgICAgICAgICAgeGhyLm9wZW4oJ0RFTEVURScsIGAke29wdGlvbnMuYXBpX3VybH1sYWRkZXItY29tcGV0aXRvcnMvJHtsaW5rLmRhdGFzZXQuY29tcGV0aXRvcklkfT9hZG1pbj0xYCk7XHJcbiAgICAgICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xyXG4gICAgICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ1gtV1AtTm9uY2UnLCBvcHRpb25zLnJlc3Rfbm9uY2UpO1xyXG4gICAgICAgICAgICAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjA0KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbi5yZWxvYWQoKTtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBsZXQgcmVzcG9uc2UgPSBKU09OLnBhcnNlKHhoci5yZXNwb25zZSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tcmVtb3ZlLWNvbXBldGl0b3ItcmVzcG9uc2UnKS5pbm5lckhUTUwgPSBgPGRpdiBjbGFzcz1cInRybi1hbGVydCB0cm4tYWxlcnQtZGFuZ2VyXCI+PHN0cm9uZz4ke29wdGlvbnMubGFuZ3VhZ2UuZmFpbHVyZX08L3N0cm9uZz46ICR7cmVzcG9uc2UubWVzc2FnZX08L2Rpdj5gO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgICAgICAgICAgeGhyLnNlbmQoKTtcclxuICAgICAgICAgICAgfSwgZmFsc2UpO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG5cclxuICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3Rybi1odG1sLXVwZGF0ZWQnLCBmdW5jdGlvbiAoZSkge1xyXG4gICAgICAgICAgICBoYW5kbGVEZWxldGVDb25maXJtKCk7XHJcbiAgICAgICAgICAgIGhhbmRsZVByb21vdGVMaW5rKCk7XHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgaGFuZGxlRGVsZXRlQ29uZmlybSgpO1xyXG4gICAgICAgIGhhbmRsZVByb21vdGVMaW5rKCk7XHJcblxyXG4gICAgICAgIGxldCBkZWZhdWx0X3RhcmdldCA9IG9wdGlvbnMuZGVmYXVsdF90YXJnZXQ7XHJcbiAgICAgICAgbGV0IHRhcmdldCA9IDA7XHJcbiAgICAgICAgbGV0IGNvbHVtbkRlZnMgPSBbXHJcbiAgICAgICAgICAgIHtcclxuICAgICAgICAgICAgICAgIHRhcmdldHM6IHRhcmdldCsrLFxyXG4gICAgICAgICAgICAgICAgbmFtZTogJ251bWJlcicsXHJcbiAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICd0cm4tbGFkZGVyLXN0YW5kaW5ncy10YWJsZS1udW1iZXInLFxyXG4gICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoZGF0YSwgdHlwZSwgcm93LCBtZXRhKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIG1ldGEucm93ICsgbWV0YS5zZXR0aW5ncy5faURpc3BsYXlTdGFydCArIDE7XHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICAgICAgb3JkZXJhYmxlOiBmYWxzZSxcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0czogdGFyZ2V0KyssXHJcbiAgICAgICAgICAgICAgICBuYW1lOiAnbmFtZScsXHJcbiAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICd0cm4tbGFkZGVyLXN0YW5kaW5ncy10YWJsZS1uYW1lJyxcclxuICAgICAgICAgICAgICAgIHJlbmRlcjogZnVuY3Rpb24gKGRhdGEsIHR5cGUsIHJvdykge1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBgPGltZyBzcmM9XCIke29wdGlvbnMuZmxhZ19wYXRofSR7cm93Ll9lbWJlZGRlZC5jb21wZXRpdG9yWzBdLmZsYWd9XCIgd2lkdGg9XCIxOFwiIGhlaWdodD1cIjEyXCIgdGl0bGU9XCIke3Jvdy5fZW1iZWRkZWQuY29tcGV0aXRvclswXS5mbGFnfVwiPiA8YSBocmVmPVwiJHtyb3cuX2VtYmVkZGVkLmNvbXBldGl0b3JbMF0ubGlua31cIj4ke3Jvdy5fZW1iZWRkZWQuY29tcGV0aXRvclswXS5uYW1lfTwvYT5gO1xyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0czogdGFyZ2V0KyssXHJcbiAgICAgICAgICAgICAgICBuYW1lOiBkZWZhdWx0X3RhcmdldCxcclxuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ3Rybi1sYWRkZXItc3RhbmRpbmdzLXRhYmxlLXJhdGluZyByYXRpbmcnLFxyXG4gICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHJvd1tkZWZhdWx0X3RhcmdldF07XHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICB7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRzOiB0YXJnZXQrKyxcclxuICAgICAgICAgICAgICAgIG5hbWU6ICdnYW1lc19wbGF5ZWQnLFxyXG4gICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAndHJuLWxhZGRlci1zdGFuZGluZ3MtdGFibGUtZ2FtZXMtcGxheWVkJyxcclxuICAgICAgICAgICAgICAgIHJlbmRlcjogZnVuY3Rpb24gKGRhdGEsIHR5cGUsIHJvdykge1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiByb3cuZ2FtZXNfcGxheWVkO1xyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0czogdGFyZ2V0KyssXHJcbiAgICAgICAgICAgICAgICBuYW1lOiAnd2lucycsXHJcbiAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICd0cm4tbGFkZGVyLXN0YW5kaW5ncy10YWJsZS13aW5zIHdpbnMnLFxyXG4gICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHJvdy53aW5zO1xyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0czogdGFyZ2V0KyssXHJcbiAgICAgICAgICAgICAgICBuYW1lOiAnbG9zc2VzJyxcclxuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ3Rybi1sYWRkZXItc3RhbmRpbmdzLXRhYmxlLWxvc3NlcyBsb3NzZXMnLFxyXG4gICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHJvdy5sb3NzZXM7XHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICB9LFxyXG4gICAgICAgIF07XHJcblxyXG4gICAgICAgIGlmIChvcHRpb25zLnVzZXNfZHJhd3MpIHtcclxuICAgICAgICAgICAgY29sdW1uRGVmcy5wdXNoKHtcclxuICAgICAgICAgICAgICAgIHRhcmdldHM6IHRhcmdldCsrLFxyXG4gICAgICAgICAgICAgICAgbmFtZTogJ2RyYXdzJyxcclxuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ3Rybi1sYWRkZXItc3RhbmRpbmdzLXRhYmxlLWRyYXdzIHRpZXMnLFxyXG4gICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHJvdy5kcmF3cztcclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgaWYgKG9wdGlvbnMudXNlc19zY29yZXMpIHtcclxuICAgICAgICAgICAgY29sdW1uRGVmcy5wdXNoKFxyXG4gICAgICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgICAgIHRhcmdldHM6IHRhcmdldCsrLFxyXG4gICAgICAgICAgICAgICAgICAgIG5hbWU6ICdnb2Fsc19mb3InLFxyXG4gICAgICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ3Rybi1sYWRkZXItc3RhbmRpbmdzLXRhYmxlLWdvYWxzLWZvcicsXHJcbiAgICAgICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiByb3cuZ29hbHNfZm9yO1xyXG4gICAgICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgICAgIHRhcmdldHM6IHRhcmdldCsrLFxyXG4gICAgICAgICAgICAgICAgICAgIG5hbWU6ICdnb2Fsc19hZ2FpbnN0JyxcclxuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICd0cm4tbGFkZGVyLXN0YW5kaW5ncy10YWJsZS1nb2Fscy1hZ2FpbnN0JyxcclxuICAgICAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uIChkYXRhLCB0eXBlLCByb3cpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHJvdy5nb2Fsc19hZ2FpbnN0O1xyXG4gICAgICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgICAgIHRhcmdldHM6IHRhcmdldCsrLFxyXG4gICAgICAgICAgICAgICAgICAgIG5hbWU6ICdnb2Fsc19kaWZmZXJlbmNlJyxcclxuICAgICAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICd0cm4tbGFkZGVyLXN0YW5kaW5ncy10YWJsZS1nb2Fscy1kaWZmZXJlbmNlJyxcclxuICAgICAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uIChkYXRhLCB0eXBlLCByb3cpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHJvdy5nb2Fsc19kZWx0YTtcclxuICAgICAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICApO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgY29sdW1uRGVmcy5wdXNoKFxyXG4gICAgICAgICAgICB7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRzOiB0YXJnZXQrKyxcclxuICAgICAgICAgICAgICAgIG5hbWU6ICd3aW5fcGVyY2VudCcsXHJcbiAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICd0cm4tbGFkZGVyLXN0YW5kaW5ncy10YWJsZS13aW4tcGVyY2VudCcsXHJcbiAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uIChkYXRhLCB0eXBlLCByb3cpIHtcclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gcm93Lndpbl9wZXJjZW50O1xyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0czogdGFyZ2V0KyssXHJcbiAgICAgICAgICAgICAgICBuYW1lOiAnc3RyZWFrJyxcclxuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ3Rybi1sYWRkZXItc3RhbmRpbmdzLXRhYmxlLXN0cmVhaycsXHJcbiAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uIChkYXRhLCB0eXBlLCByb3cpIHtcclxuICAgICAgICAgICAgICAgICAgICBsZXQgc3RyZWFrQ2xhc3M7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKDAgPiByb3cuc3RyZWFrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHN0cmVha0NsYXNzID0gYG5lZ2F0aXZlLXN0cmVha2A7XHJcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIGlmICgwIDwgcm93LnN0cmVhaykge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBzdHJlYWtDbGFzcyA9IGBwb3NpdGl2ZS1zdHJlYWtgO1xyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHN0cmVha0NsYXNzID0gYGA7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBgPHNwYW4gY2xhc3M9XCIke3N0cmVha0NsYXNzfVwiPiR7cm93LnN0cmVha308L3NwYW4+YDtcclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIHtcclxuICAgICAgICAgICAgICAgIHRhcmdldHM6IHRhcmdldCsrLFxyXG4gICAgICAgICAgICAgICAgbmFtZTogJ2lkbGUnLFxyXG4gICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAndHJuLWxhZGRlci1zdGFuZGluZ3MtdGFibGUtaWRsZScsXHJcbiAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uIChkYXRhLCB0eXBlLCByb3cpIHtcclxuICAgICAgICAgICAgICAgICAgICBsZXQgaWRsZUNsYXNzO1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICg3ID49IHJvdy5kYXlzX2lkbGUpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgaWRsZUNsYXNzID0gYHRybi1sYWRkZXItYWN0aXZlLWxhc3QtN2A7XHJcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIGlmICgxNCA+PSByb3cuZGF5c19pZGxlKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGlkbGVDbGFzcyA9IGB0cm4tbGFkZGVyLWFjdGl2ZS1sYXN0LTE0YDtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2UgaWYgKDIxID49IHJvdy5kYXlzX2lkbGUpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgaWRsZUNsYXNzID0gYHRybi1sYWRkZXItYWN0aXZlLWxhc3QtMjFgO1xyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGlkbGVDbGFzcyA9IGB0cm4tbGFkZGVyLWluYWN0aXZlYDtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGA8c3BhbiBjbGFzcz1cIiR7aWRsZUNsYXNzfVwiPiR7cm93LmRheXNfaWRsZX08L3NwYW4+YDtcclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICApO1xyXG5cclxuICAgICAgICBpZiAob3B0aW9ucy5jYW5fY2hhbGxlbmdlIHx8IG9wdGlvbnMuaXNfYWRtaW4pIHtcclxuICAgICAgICAgICAgY29sdW1uRGVmcy5wdXNoKHtcclxuICAgICAgICAgICAgICAgIHRhcmdldHM6IHRhcmdldCxcclxuICAgICAgICAgICAgICAgIG5hbWU6ICdhY3Rpb25zJyxcclxuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ3Rybi1sYWRkZXItc3RhbmRpbmdzLXRhYmxlLWFjdGlvbnMnLFxyXG4gICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IGxpbmtzID0gW107XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIGlmIChvcHRpb25zLmNhbl9jaGFsbGVuZ2UpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbGlua3MucHVzaChgPGEgaHJlZj1cIiR7b3B0aW9ucy5jaGFsbGVuZ2VfdXJsfSR7cm93LmNvbXBldGl0b3JfaWR9XCIgdGl0bGU9XCIke29wdGlvbnMubGFuZ3VhZ2UuY2hhbGxlbmdlX2xpbmtfdGl0bGV9XCI+PGkgY2xhc3M9XCJmYSBmYS1jcm9zc2hhaXJzXCIgYXJpYS1oaWRkZW49XCJ0cnVlXCI+PC9pPjwvYT5gKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIGlmIChvcHRpb25zLmlzX2FkbWluKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGxpbmtzLnB1c2goYDxhIGhyZWY9XCIke3Jvdy5lZGl0X2xpbmt9XCIgdGl0bGU9XCIke29wdGlvbnMubGFuZ3VhZ2UuZWRpdF9saW5rX3RpdGxlfVwiPjxpIGNsYXNzPVwiZmEgZmEtZWRpdFwiIGFyaWEtaGlkZGVuPVwidHJ1ZVwiPjwvaT48L2E+YCk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGxldCBjb21wZXRpdG9yX25hbWUgPSBgYDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKCdwbGF5ZXInID09PSByb3cuY29tcGV0aXRvcl90eXBlKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb21wZXRpdG9yX25hbWUgPSBvcHRpb25zLmxhbmd1YWdlLmNvbmZpcm1fZGVsZXRlX21lc3NhZ2UuZm9ybWF0KHJvdy5fZW1iZWRkZWQuY29tcGV0aXRvclswXS5uYW1lKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbXBldGl0b3JfbmFtZSA9IG9wdGlvbnMubGFuZ3VhZ2UuY29uZmlybV9kZWxldGVfbWVzc2FnZS5mb3JtYXQocm93Ll9lbWJlZGRlZC5jb21wZXRpdG9yWzBdLm5hbWUpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGxpbmtzLnB1c2goYDxhIGNsYXNzPVwidHJuLXJlbW92ZS1jb21wZXRpdG9yLWxpbmsgdHJuLWNvbmZpcm0tYWN0aW9uLWxpbmtcIiBocmVmPVwiI1wiIHRpdGxlPVwiJHtvcHRpb25zLmxhbmd1YWdlLnJlbW92ZV9saW5rX3RpdGxlfVwiIGRhdGEtY29tcGV0aXRvci1pZD1cIiR7cm93LmxhZGRlcl9lbnRyeV9pZH1cIiBkYXRhLWNvbmZpcm0tdGl0bGU9XCIke29wdGlvbnMubGFuZ3VhZ2UuY29uZmlybV9kZWxldGVfdGl0bGV9XCIgZGF0YS1jb25maXJtLW1lc3NhZ2U9XCIke2NvbXBldGl0b3JfbmFtZX1cIiBkYXRhLW1vZGFsLWlkPVwiZGVsZXRlLWNvbXBldGl0b3JcIj48aSBjbGFzcz1cImZhIGZhLXRyYXNoXCIgYXJpYS1oaWRkZW49XCJ0cnVlXCI+PC9pPjwvYT5gKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKG9wdGlvbnMuY2FuX3Byb21vdGUgJiYgMSAhPT0gcm93LnJhbmspIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgbGlua3MucHVzaChgPGEgY2xhc3M9XCJ0cm4tcHJvbW90ZS1jb21wZXRpdG9yLWxpbmtcIiBocmVmPVwiI1wiIHRpdGxlPVwiJHtvcHRpb25zLmxhbmd1YWdlLnByb21vdGVfbGlua190aXRsZX1cIiBkYXRhLWNvbXBldGl0b3ItaWQ9XCIke3Jvdy5sYWRkZXJfY29tcGV0aXRvcl9pZH1cIj48aSBjbGFzcz1cImZhIGZhLWxvbmctYXJyb3ctYWx0LXVwXCIgYXJpYS1oaWRkZW49XCJ0cnVlXCI+PC9pPjwvYT5gKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKGxpbmtzLmxlbmd0aCA+IDApIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGxpbmtzLmpvaW4oJyAnKTtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gYGA7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgICAgIG9yZGVyYWJsZTogZmFsc2UsXHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgY29uc3QgZGVmYXVsdF9kaXJlY3Rpb24gPSAnZGVzYyc7XHJcbiAgICAgICAgbGV0IHN0YW5kaW5ncyA9IGpRdWVyeSgnI2xhZGRlci1zdGFuZGluZ3MtdGFibGUnKVxyXG4gICAgICAgICAgICAub24oJ3hoci5kdCcsIGZ1bmN0aW9uIChlLCBzZXR0aW5ncywganNvbiwgeGhyKSB7XHJcbiAgICAgICAgICAgICAgICBqc29uLmRhdGEgPSBKU09OLnBhcnNlKEpTT04uc3RyaW5naWZ5KGpzb24pKTtcclxuICAgICAgICAgICAgICAgIGpzb24ucmVjb3Jkc1RvdGFsID0geGhyLmdldFJlc3BvbnNlSGVhZGVyKCdYLVdQLVRvdGFsJyk7XHJcbiAgICAgICAgICAgICAgICBqc29uLnJlY29yZHNGaWx0ZXJlZCA9IHhoci5nZXRSZXNwb25zZUhlYWRlcignVFJOLUZpbHRlcmVkJyk7XHJcbiAgICAgICAgICAgICAgICBqc29uLmxlbmd0aCA9IHhoci5nZXRSZXNwb25zZUhlYWRlcignWC1XUC1Ub3RhbFBhZ2VzJyk7XHJcbiAgICAgICAgICAgICAgICBqc29uLmRyYXcgPSB4aHIuZ2V0UmVzcG9uc2VIZWFkZXIoJ1RSTi1EcmF3Jyk7XHJcbiAgICAgICAgICAgIH0pXHJcbiAgICAgICAgICAgIC5EYXRhVGFibGUoe1xyXG4gICAgICAgICAgICAgICAgcHJvY2Vzc2luZzogdHJ1ZSxcclxuICAgICAgICAgICAgICAgIHNlcnZlclNpZGU6IHRydWUsXHJcbiAgICAgICAgICAgICAgICBsZW5ndGhNZW51OiBbWzI1LCA1MCwgMTAwLCAtMV0sIFsyNSwgNTAsIDEwMCwgJ0FsbCddXSxcclxuICAgICAgICAgICAgICAgIGxhbmd1YWdlOiBvcHRpb25zLnRhYmxlX2xhbmd1YWdlLFxyXG4gICAgICAgICAgICAgICAgYXV0b1dpZHRoOiBmYWxzZSxcclxuICAgICAgICAgICAgICAgIGFqYXg6IHtcclxuICAgICAgICAgICAgICAgICAgICB1cmw6IGAke29wdGlvbnMuYXBpX3VybH1sYWRkZXItY29tcGV0aXRvcnMvP193cG5vbmNlPSR7b3B0aW9ucy5yZXN0X25vbmNlfSZfZW1iZWQmbGFkZGVyX2lkPSR7b3B0aW9ucy5sYWRkZXJfaWR9YCxcclxuICAgICAgICAgICAgICAgICAgICB0eXBlOiAnR0VUJyxcclxuICAgICAgICAgICAgICAgICAgICBkYXRhOiBmdW5jdGlvbiAoZGF0YSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhkYXRhKVxyXG4gICAgICAgICAgICAgICAgICAgICAgICBsZXQgc2VudCA9IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGRyYXc6IGRhdGEuZHJhdyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBhZ2U6IE1hdGguZmxvb3IoZGF0YS5zdGFydCAvIGRhdGEubGVuZ3RoKSxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBlcl9wYWdlOiBkYXRhLmxlbmd0aCxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNlYXJjaDogZGF0YS5zZWFyY2gudmFsdWUsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBvcmRlcmJ5OiBgJHtkYXRhLmNvbHVtbnNbZGF0YS5vcmRlclswXS5jb2x1bW5dLm5hbWV9LiR7ZGF0YS5vcmRlclswXS5kaXJ9YFxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9O1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhzZW50KVxyXG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gc2VudDtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICAgICAgb3JkZXI6IFtbMiwgZGVmYXVsdF9kaXJlY3Rpb25dXSxcclxuICAgICAgICAgICAgICAgIGNvbHVtbkRlZnM6IGNvbHVtbkRlZnMsXHJcbiAgICAgICAgICAgICAgICBkcmF3Q2FsbGJhY2s6IGZ1bmN0aW9uKCBzZXR0aW5ncyApIHtcclxuICAgICAgICAgICAgICAgICAgICBkb2N1bWVudC5kaXNwYXRjaEV2ZW50KCBuZXcgQ3VzdG9tRXZlbnQoICd0cm4taHRtbC11cGRhdGVkJywgeyAnZGV0YWlsJzogJ1RoZSB0YWJsZSBodG1sIGhhcyB1cGRhdGVkLicgfSApKTtcclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgfSwgZmFsc2UpO1xyXG59KSh0cm4pOyIsIid1c2Ugc3RyaWN0JztcclxuY2xhc3MgVG91cm5hbWF0Y2gge1xyXG5cclxuICAgIGNvbnN0cnVjdG9yKCkge1xyXG4gICAgICAgIHRoaXMuZXZlbnRzID0ge307XHJcbiAgICB9XHJcblxyXG4gICAgcGFyYW0ob2JqZWN0LCBwcmVmaXgpIHtcclxuICAgICAgICBsZXQgc3RyID0gW107XHJcbiAgICAgICAgZm9yIChsZXQgcHJvcCBpbiBvYmplY3QpIHtcclxuICAgICAgICAgICAgaWYgKG9iamVjdC5oYXNPd25Qcm9wZXJ0eShwcm9wKSkge1xyXG4gICAgICAgICAgICAgICAgbGV0IGsgPSBwcmVmaXggPyBwcmVmaXggKyBcIltcIiArIHByb3AgKyBcIl1cIiA6IHByb3A7XHJcbiAgICAgICAgICAgICAgICBsZXQgdiA9IG9iamVjdFtwcm9wXTtcclxuICAgICAgICAgICAgICAgIHN0ci5wdXNoKCh2ICE9PSBudWxsICYmIHR5cGVvZiB2ID09PSBcIm9iamVjdFwiKSA/IHRoaXMucGFyYW0odiwgaykgOiBlbmNvZGVVUklDb21wb25lbnQoaykgKyBcIj1cIiArIGVuY29kZVVSSUNvbXBvbmVudCh2KSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuIHN0ci5qb2luKFwiJlwiKTtcclxuICAgIH1cclxuXHJcbiAgICBldmVudChldmVudE5hbWUpIHtcclxuICAgICAgICBpZiAoIShldmVudE5hbWUgaW4gdGhpcy5ldmVudHMpKSB7XHJcbiAgICAgICAgICAgIHRoaXMuZXZlbnRzW2V2ZW50TmFtZV0gPSBuZXcgRXZlbnRUYXJnZXQoZXZlbnROYW1lKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuIHRoaXMuZXZlbnRzW2V2ZW50TmFtZV07XHJcbiAgICB9XHJcblxyXG4gICAgYXV0b2NvbXBsZXRlKGlucHV0LCBkYXRhQ2FsbGJhY2spIHtcclxuICAgICAgICBuZXcgVG91cm5hbWF0Y2hfQXV0b2NvbXBsZXRlKGlucHV0LCBkYXRhQ2FsbGJhY2spO1xyXG4gICAgfVxyXG5cclxuICAgIHVjZmlyc3Qocykge1xyXG4gICAgICAgIGlmICh0eXBlb2YgcyAhPT0gJ3N0cmluZycpIHJldHVybiAnJztcclxuICAgICAgICByZXR1cm4gcy5jaGFyQXQoMCkudG9VcHBlckNhc2UoKSArIHMuc2xpY2UoMSk7XHJcbiAgICB9XHJcblxyXG4gICAgb3JkaW5hbF9zdWZmaXgobnVtYmVyKSB7XHJcbiAgICAgICAgY29uc3QgcmVtYWluZGVyID0gbnVtYmVyICUgMTAwO1xyXG5cclxuICAgICAgICBpZiAoKHJlbWFpbmRlciA8IDExKSB8fCAocmVtYWluZGVyID4gMTMpKSB7XHJcbiAgICAgICAgICAgIHN3aXRjaCAocmVtYWluZGVyICUgMTApIHtcclxuICAgICAgICAgICAgICAgIGNhc2UgMTogcmV0dXJuICdzdCc7XHJcbiAgICAgICAgICAgICAgICBjYXNlIDI6IHJldHVybiAnbmQnO1xyXG4gICAgICAgICAgICAgICAgY2FzZSAzOiByZXR1cm4gJ3JkJztcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gJ3RoJztcclxuICAgIH1cclxuXHJcbiAgICB0YWJzKGVsZW1lbnQpIHtcclxuICAgICAgICBjb25zdCB0YWJzID0gZWxlbWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tbmF2LWxpbmsnKTtcclxuICAgICAgICBjb25zdCBwYW5lcyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi10YWItcGFuZScpO1xyXG4gICAgICAgIGNvbnN0IGNsZWFyQWN0aXZlID0gKCkgPT4ge1xyXG4gICAgICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHRhYnMsICh0YWIpID0+IHtcclxuICAgICAgICAgICAgICAgIHRhYi5jbGFzc0xpc3QucmVtb3ZlKCd0cm4tbmF2LWFjdGl2ZScpO1xyXG4gICAgICAgICAgICAgICAgdGFiLmFyaWFTZWxlY3RlZCA9IGZhbHNlO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbChwYW5lcywgcGFuZSA9PiBwYW5lLmNsYXNzTGlzdC5yZW1vdmUoJ3Rybi10YWItYWN0aXZlJykpO1xyXG4gICAgICAgIH07XHJcbiAgICAgICAgY29uc3Qgc2V0QWN0aXZlID0gKHRhcmdldElkKSA9PiB7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFRhYiA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ2FbaHJlZj1cIiMnICsgdGFyZ2V0SWQgKyAnXCJdLnRybi1uYXYtbGluaycpO1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRQYW5lSWQgPSB0YXJnZXRUYWIgJiYgdGFyZ2V0VGFiLmRhdGFzZXQgJiYgdGFyZ2V0VGFiLmRhdGFzZXQudGFyZ2V0IHx8IGZhbHNlO1xyXG5cclxuICAgICAgICAgICAgaWYgKHRhcmdldFBhbmVJZCkge1xyXG4gICAgICAgICAgICAgICAgY2xlYXJBY3RpdmUoKTtcclxuICAgICAgICAgICAgICAgIHRhcmdldFRhYi5jbGFzc0xpc3QuYWRkKCd0cm4tbmF2LWFjdGl2ZScpO1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0VGFiLmFyaWFTZWxlY3RlZCA9IHRydWU7XHJcblxyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGFyZ2V0UGFuZUlkKS5jbGFzc0xpc3QuYWRkKCd0cm4tdGFiLWFjdGl2ZScpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfTtcclxuICAgICAgICBjb25zdCB0YWJDbGljayA9IChldmVudCkgPT4ge1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRUYWIgPSBldmVudC5jdXJyZW50VGFyZ2V0O1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRQYW5lSWQgPSB0YXJnZXRUYWIgJiYgdGFyZ2V0VGFiLmRhdGFzZXQgJiYgdGFyZ2V0VGFiLmRhdGFzZXQudGFyZ2V0IHx8IGZhbHNlO1xyXG5cclxuICAgICAgICAgICAgaWYgKHRhcmdldFBhbmVJZCkge1xyXG4gICAgICAgICAgICAgICAgc2V0QWN0aXZlKHRhcmdldFBhbmVJZCk7XHJcbiAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfTtcclxuXHJcbiAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbCh0YWJzLCAodGFiKSA9PiB7XHJcbiAgICAgICAgICAgIHRhYi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIHRhYkNsaWNrKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgaWYgKGxvY2F0aW9uLmhhc2gpIHtcclxuICAgICAgICAgICAgc2V0QWN0aXZlKGxvY2F0aW9uLmhhc2guc3Vic3RyKDEpKTtcclxuICAgICAgICB9IGVsc2UgaWYgKHRhYnMubGVuZ3RoID4gMCkge1xyXG4gICAgICAgICAgICBzZXRBY3RpdmUodGFic1swXS5kYXRhc2V0LnRhcmdldCk7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxufVxyXG5cclxuLy90cm4uaW5pdGlhbGl6ZSgpO1xyXG5pZiAoIXdpbmRvdy50cm5fb2JqX2luc3RhbmNlKSB7XHJcbiAgICB3aW5kb3cudHJuX29ial9pbnN0YW5jZSA9IG5ldyBUb3VybmFtYXRjaCgpO1xyXG5cclxuICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgZnVuY3Rpb24gKCkge1xyXG5cclxuICAgICAgICBjb25zdCB0YWJWaWV3cyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1uYXYnKTtcclxuXHJcbiAgICAgICAgQXJyYXkuZnJvbSh0YWJWaWV3cykuZm9yRWFjaCgodGFiKSA9PiB7XHJcbiAgICAgICAgICAgIHRybi50YWJzKHRhYik7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIGNvbnN0IGRyb3Bkb3ducyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1kcm9wZG93bi10b2dnbGUnKTtcclxuICAgICAgICBjb25zdCBoYW5kbGVEcm9wZG93bkNsb3NlID0gKCkgPT4ge1xyXG4gICAgICAgICAgICBBcnJheS5mcm9tKGRyb3Bkb3ducykuZm9yRWFjaCgoZHJvcGRvd24pID0+IHtcclxuICAgICAgICAgICAgICAgIGRyb3Bkb3duLm5leHRFbGVtZW50U2libGluZy5jbGFzc0xpc3QucmVtb3ZlKCd0cm4tc2hvdycpO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgZG9jdW1lbnQucmVtb3ZlRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGhhbmRsZURyb3Bkb3duQ2xvc2UsIGZhbHNlKTtcclxuICAgICAgICB9O1xyXG5cclxuICAgICAgICBBcnJheS5mcm9tKGRyb3Bkb3ducykuZm9yRWFjaCgoZHJvcGRvd24pID0+IHtcclxuICAgICAgICAgICAgZHJvcGRvd24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbihlKSB7XHJcbiAgICAgICAgICAgICAgICBlLnN0b3BQcm9wYWdhdGlvbigpO1xyXG4gICAgICAgICAgICAgICAgdGhpcy5uZXh0RWxlbWVudFNpYmxpbmcuY2xhc3NMaXN0LmFkZCgndHJuLXNob3cnKTtcclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBoYW5kbGVEcm9wZG93bkNsb3NlLCBmYWxzZSk7XHJcbiAgICAgICAgICAgIH0sIGZhbHNlKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICB9LCBmYWxzZSk7XHJcbn1cclxuZXhwb3J0IGxldCB0cm4gPSB3aW5kb3cudHJuX29ial9pbnN0YW5jZTtcclxuXHJcbmNsYXNzIFRvdXJuYW1hdGNoX0F1dG9jb21wbGV0ZSB7XHJcblxyXG4gICAgLy8gY3VycmVudEZvY3VzO1xyXG4gICAgLy9cclxuICAgIC8vIG5hbWVJbnB1dDtcclxuICAgIC8vXHJcbiAgICAvLyBzZWxmO1xyXG5cclxuICAgIGNvbnN0cnVjdG9yKGlucHV0LCBkYXRhQ2FsbGJhY2spIHtcclxuICAgICAgICAvLyB0aGlzLnNlbGYgPSB0aGlzO1xyXG4gICAgICAgIHRoaXMubmFtZUlucHV0ID0gaW5wdXQ7XHJcblxyXG4gICAgICAgIHRoaXMubmFtZUlucHV0LmFkZEV2ZW50TGlzdGVuZXIoXCJpbnB1dFwiLCAoKSA9PiB7XHJcbiAgICAgICAgICAgIGxldCBhLCBiLCBpLCB2YWwgPSB0aGlzLm5hbWVJbnB1dC52YWx1ZTsvL3RoaXMudmFsdWU7XHJcbiAgICAgICAgICAgIGxldCBwYXJlbnQgPSB0aGlzLm5hbWVJbnB1dC5wYXJlbnROb2RlOy8vdGhpcy5wYXJlbnROb2RlO1xyXG5cclxuICAgICAgICAgICAgLy8gbGV0IHAgPSBuZXcgUHJvbWlzZSgocmVzb2x2ZSwgcmVqZWN0KSA9PiB7XHJcbiAgICAgICAgICAgIC8vICAgICAvKiBuZWVkIHRvIHF1ZXJ5IHNlcnZlciBmb3IgbmFtZXMgaGVyZS4gKi9cclxuICAgICAgICAgICAgLy8gICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5vcGVuKCdHRVQnLCBvcHRpb25zLmFwaV91cmwgKyAncGxheWVycy8/c2VhcmNoPScgKyB2YWwgKyAnJnBlcl9wYWdlPTUnKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdDb250ZW50LVR5cGUnLCAnYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkJyk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignWC1XUC1Ob25jZScsIG9wdGlvbnMucmVzdF9ub25jZSk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIub25sb2FkID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAvLyAgICAgICAgIGlmICh4aHIuc3RhdHVzID09PSAyMDApIHtcclxuICAgICAgICAgICAgLy8gICAgICAgICAgICAgLy8gcmVzb2x2ZShKU09OLnBhcnNlKHhoci5yZXNwb25zZSkubWFwKChwbGF5ZXIpID0+IHtyZXR1cm4geyAndmFsdWUnOiBwbGF5ZXIuaWQsICd0ZXh0JzogcGxheWVyLm5hbWUgfTt9KSk7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgICAgIHJlc29sdmUoSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpLm1hcCgocGxheWVyKSA9PiB7cmV0dXJuIHBsYXllci5uYW1lO30pKTtcclxuICAgICAgICAgICAgLy8gICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAvLyAgICAgICAgICAgICByZWplY3QoKTtcclxuICAgICAgICAgICAgLy8gICAgICAgICB9XHJcbiAgICAgICAgICAgIC8vICAgICB9O1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLnNlbmQoKTtcclxuICAgICAgICAgICAgLy8gfSk7XHJcbiAgICAgICAgICAgIGRhdGFDYWxsYmFjayh2YWwpLnRoZW4oKGRhdGEpID0+IHsvL3AudGhlbigoZGF0YSkgPT4ge1xyXG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coZGF0YSk7XHJcblxyXG4gICAgICAgICAgICAgICAgLypjbG9zZSBhbnkgYWxyZWFkeSBvcGVuIGxpc3RzIG9mIGF1dG8tY29tcGxldGVkIHZhbHVlcyovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmNsb3NlQWxsTGlzdHMoKTtcclxuICAgICAgICAgICAgICAgIGlmICghdmFsKSB7IHJldHVybiBmYWxzZTt9XHJcbiAgICAgICAgICAgICAgICB0aGlzLmN1cnJlbnRGb2N1cyA9IC0xO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qY3JlYXRlIGEgRElWIGVsZW1lbnQgdGhhdCB3aWxsIGNvbnRhaW4gdGhlIGl0ZW1zICh2YWx1ZXMpOiovXHJcbiAgICAgICAgICAgICAgICBhID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcIkRJVlwiKTtcclxuICAgICAgICAgICAgICAgIGEuc2V0QXR0cmlidXRlKFwiaWRcIiwgdGhpcy5uYW1lSW5wdXQuaWQgKyBcIi1hdXRvLWNvbXBsZXRlLWxpc3RcIik7XHJcbiAgICAgICAgICAgICAgICBhLnNldEF0dHJpYnV0ZShcImNsYXNzXCIsIFwidHJuLWF1dG8tY29tcGxldGUtaXRlbXNcIik7XHJcblxyXG4gICAgICAgICAgICAgICAgLyphcHBlbmQgdGhlIERJViBlbGVtZW50IGFzIGEgY2hpbGQgb2YgdGhlIGF1dG8tY29tcGxldGUgY29udGFpbmVyOiovXHJcbiAgICAgICAgICAgICAgICBwYXJlbnQuYXBwZW5kQ2hpbGQoYSk7XHJcblxyXG4gICAgICAgICAgICAgICAgLypmb3IgZWFjaCBpdGVtIGluIHRoZSBhcnJheS4uLiovXHJcbiAgICAgICAgICAgICAgICBmb3IgKGkgPSAwOyBpIDwgZGF0YS5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICAgICAgICAgIGxldCB0ZXh0LCB2YWx1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgLyogV2hpY2ggZm9ybWF0IGRpZCB0aGV5IGdpdmUgdXMuICovXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBkYXRhW2ldID09PSAnb2JqZWN0Jykge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB0ZXh0ID0gZGF0YVtpXVsndGV4dCddO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB2YWx1ZSA9IGRhdGFbaV1bJ3ZhbHVlJ107XHJcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdGV4dCA9IGRhdGFbaV07XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlID0gZGF0YVtpXTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIC8qY2hlY2sgaWYgdGhlIGl0ZW0gc3RhcnRzIHdpdGggdGhlIHNhbWUgbGV0dGVycyBhcyB0aGUgdGV4dCBmaWVsZCB2YWx1ZToqL1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh0ZXh0LnN1YnN0cigwLCB2YWwubGVuZ3RoKS50b1VwcGVyQ2FzZSgpID09PSB2YWwudG9VcHBlckNhc2UoKSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKmNyZWF0ZSBhIERJViBlbGVtZW50IGZvciBlYWNoIG1hdGNoaW5nIGVsZW1lbnQ6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJESVZcIik7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qbWFrZSB0aGUgbWF0Y2hpbmcgbGV0dGVycyBib2xkOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuaW5uZXJIVE1MID0gXCI8c3Ryb25nPlwiICsgdGV4dC5zdWJzdHIoMCwgdmFsLmxlbmd0aCkgKyBcIjwvc3Ryb25nPlwiO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmlubmVySFRNTCArPSB0ZXh0LnN1YnN0cih2YWwubGVuZ3RoKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qaW5zZXJ0IGEgaW5wdXQgZmllbGQgdGhhdCB3aWxsIGhvbGQgdGhlIGN1cnJlbnQgYXJyYXkgaXRlbSdzIHZhbHVlOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuaW5uZXJIVE1MICs9IFwiPGlucHV0IHR5cGU9J2hpZGRlbicgdmFsdWU9J1wiICsgdmFsdWUgKyBcIic+XCI7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmRhdGFzZXQudmFsdWUgPSB2YWx1ZTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5kYXRhc2V0LnRleHQgPSB0ZXh0O1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgLypleGVjdXRlIGEgZnVuY3Rpb24gd2hlbiBzb21lb25lIGNsaWNrcyBvbiB0aGUgaXRlbSB2YWx1ZSAoRElWIGVsZW1lbnQpOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIChlKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhgaXRlbSBjbGlja2VkIHdpdGggdmFsdWUgJHtlLmN1cnJlbnRUYXJnZXQuZGF0YXNldC52YWx1ZX1gKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAvKiBpbnNlcnQgdGhlIHZhbHVlIGZvciB0aGUgYXV0b2NvbXBsZXRlIHRleHQgZmllbGQ6ICovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLm5hbWVJbnB1dC52YWx1ZSA9IGUuY3VycmVudFRhcmdldC5kYXRhc2V0LnRleHQ7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLm5hbWVJbnB1dC5kYXRhc2V0LnNlbGVjdGVkSWQgPSBlLmN1cnJlbnRUYXJnZXQuZGF0YXNldC52YWx1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAvKiBjbG9zZSB0aGUgbGlzdCBvZiBhdXRvY29tcGxldGVkIHZhbHVlcywgKG9yIGFueSBvdGhlciBvcGVuIGxpc3RzIG9mIGF1dG9jb21wbGV0ZWQgdmFsdWVzOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLmNsb3NlQWxsTGlzdHMoKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLm5hbWVJbnB1dC5kaXNwYXRjaEV2ZW50KG5ldyBFdmVudCgnY2hhbmdlJykpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYS5hcHBlbmRDaGlsZChiKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiBwcmVzc2VzIGEga2V5IG9uIHRoZSBrZXlib2FyZDoqL1xyXG4gICAgICAgIHRoaXMubmFtZUlucHV0LmFkZEV2ZW50TGlzdGVuZXIoXCJrZXlkb3duXCIsIChlKSA9PiB7XHJcbiAgICAgICAgICAgIGxldCB4ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGhpcy5uYW1lSW5wdXQuaWQgKyBcIi1hdXRvLWNvbXBsZXRlLWxpc3RcIik7XHJcbiAgICAgICAgICAgIGlmICh4KSB4ID0geC5nZXRFbGVtZW50c0J5VGFnTmFtZShcImRpdlwiKTtcclxuICAgICAgICAgICAgaWYgKGUua2V5Q29kZSA9PT0gNDApIHtcclxuICAgICAgICAgICAgICAgIC8qSWYgdGhlIGFycm93IERPV04ga2V5IGlzIHByZXNzZWQsXHJcbiAgICAgICAgICAgICAgICAgaW5jcmVhc2UgdGhlIGN1cnJlbnRGb2N1cyB2YXJpYWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50Rm9jdXMrKztcclxuICAgICAgICAgICAgICAgIC8qYW5kIGFuZCBtYWtlIHRoZSBjdXJyZW50IGl0ZW0gbW9yZSB2aXNpYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmFkZEFjdGl2ZSh4KTtcclxuICAgICAgICAgICAgfSBlbHNlIGlmIChlLmtleUNvZGUgPT09IDM4KSB7IC8vdXBcclxuICAgICAgICAgICAgICAgIC8qSWYgdGhlIGFycm93IFVQIGtleSBpcyBwcmVzc2VkLFxyXG4gICAgICAgICAgICAgICAgIGRlY3JlYXNlIHRoZSBjdXJyZW50Rm9jdXMgdmFyaWFibGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudEZvY3VzLS07XHJcbiAgICAgICAgICAgICAgICAvKmFuZCBhbmQgbWFrZSB0aGUgY3VycmVudCBpdGVtIG1vcmUgdmlzaWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5hZGRBY3RpdmUoeCk7XHJcbiAgICAgICAgICAgIH0gZWxzZSBpZiAoZS5rZXlDb2RlID09PSAxMykge1xyXG4gICAgICAgICAgICAgICAgLypJZiB0aGUgRU5URVIga2V5IGlzIHByZXNzZWQsIHByZXZlbnQgdGhlIGZvcm0gZnJvbSBiZWluZyBzdWJtaXR0ZWQsKi9cclxuICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgICAgIGlmICh0aGlzLmN1cnJlbnRGb2N1cyA+IC0xKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgLyphbmQgc2ltdWxhdGUgYSBjbGljayBvbiB0aGUgXCJhY3RpdmVcIiBpdGVtOiovXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHgpIHhbdGhpcy5jdXJyZW50Rm9jdXNdLmNsaWNrKCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgLypleGVjdXRlIGEgZnVuY3Rpb24gd2hlbiBzb21lb25lIGNsaWNrcyBpbiB0aGUgZG9jdW1lbnQ6Ki9cclxuICAgICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKGUpID0+IHtcclxuICAgICAgICAgICAgdGhpcy5jbG9zZUFsbExpc3RzKGUudGFyZ2V0KTtcclxuICAgICAgICB9KTtcclxuICAgIH1cclxuXHJcbiAgICBhZGRBY3RpdmUoeCkge1xyXG4gICAgICAgIC8qYSBmdW5jdGlvbiB0byBjbGFzc2lmeSBhbiBpdGVtIGFzIFwiYWN0aXZlXCI6Ki9cclxuICAgICAgICBpZiAoIXgpIHJldHVybiBmYWxzZTtcclxuICAgICAgICAvKnN0YXJ0IGJ5IHJlbW92aW5nIHRoZSBcImFjdGl2ZVwiIGNsYXNzIG9uIGFsbCBpdGVtczoqL1xyXG4gICAgICAgIHRoaXMucmVtb3ZlQWN0aXZlKHgpO1xyXG4gICAgICAgIGlmICh0aGlzLmN1cnJlbnRGb2N1cyA+PSB4Lmxlbmd0aCkgdGhpcy5jdXJyZW50Rm9jdXMgPSAwO1xyXG4gICAgICAgIGlmICh0aGlzLmN1cnJlbnRGb2N1cyA8IDApIHRoaXMuY3VycmVudEZvY3VzID0gKHgubGVuZ3RoIC0gMSk7XHJcbiAgICAgICAgLyphZGQgY2xhc3MgXCJhdXRvY29tcGxldGUtYWN0aXZlXCI6Ki9cclxuICAgICAgICB4W3RoaXMuY3VycmVudEZvY3VzXS5jbGFzc0xpc3QuYWRkKFwidHJuLWF1dG8tY29tcGxldGUtYWN0aXZlXCIpO1xyXG4gICAgfVxyXG5cclxuICAgIHJlbW92ZUFjdGl2ZSh4KSB7XHJcbiAgICAgICAgLyphIGZ1bmN0aW9uIHRvIHJlbW92ZSB0aGUgXCJhY3RpdmVcIiBjbGFzcyBmcm9tIGFsbCBhdXRvY29tcGxldGUgaXRlbXM6Ki9cclxuICAgICAgICBmb3IgKGxldCBpID0gMDsgaSA8IHgubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgeFtpXS5jbGFzc0xpc3QucmVtb3ZlKFwidHJuLWF1dG8tY29tcGxldGUtYWN0aXZlXCIpO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbiAgICBjbG9zZUFsbExpc3RzKGVsZW1lbnQpIHtcclxuICAgICAgICBjb25zb2xlLmxvZyhcImNsb3NlIGFsbCBsaXN0c1wiKTtcclxuICAgICAgICAvKmNsb3NlIGFsbCBhdXRvY29tcGxldGUgbGlzdHMgaW4gdGhlIGRvY3VtZW50LFxyXG4gICAgICAgICBleGNlcHQgdGhlIG9uZSBwYXNzZWQgYXMgYW4gYXJndW1lbnQ6Ki9cclxuICAgICAgICBsZXQgeCA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoXCJ0cm4tYXV0by1jb21wbGV0ZS1pdGVtc1wiKTtcclxuICAgICAgICBmb3IgKGxldCBpID0gMDsgaSA8IHgubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgaWYgKGVsZW1lbnQgIT09IHhbaV0gJiYgZWxlbWVudCAhPT0gdGhpcy5uYW1lSW5wdXQpIHtcclxuICAgICAgICAgICAgICAgIHhbaV0ucGFyZW50Tm9kZS5yZW1vdmVDaGlsZCh4W2ldKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgIH1cclxufVxyXG5cclxuLy8gRmlyc3QsIGNoZWNrcyBpZiBpdCBpc24ndCBpbXBsZW1lbnRlZCB5ZXQuXHJcbmlmICghU3RyaW5nLnByb3RvdHlwZS5mb3JtYXQpIHtcclxuICAgIFN0cmluZy5wcm90b3R5cGUuZm9ybWF0ID0gZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgY29uc3QgYXJncyA9IGFyZ3VtZW50cztcclxuICAgICAgICByZXR1cm4gdGhpcy5yZXBsYWNlKC97KFxcZCspfS9nLCBmdW5jdGlvbihtYXRjaCwgbnVtYmVyKSB7XHJcbiAgICAgICAgICAgIHJldHVybiB0eXBlb2YgYXJnc1tudW1iZXJdICE9PSAndW5kZWZpbmVkJ1xyXG4gICAgICAgICAgICAgICAgPyBhcmdzW251bWJlcl1cclxuICAgICAgICAgICAgICAgIDogbWF0Y2hcclxuICAgICAgICAgICAgICAgIDtcclxuICAgICAgICB9KTtcclxuICAgIH07XHJcbn0iXSwic291cmNlUm9vdCI6IiJ9