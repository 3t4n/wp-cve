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
/******/ 	return __webpack_require__(__webpack_require__.s = 37);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/team-roster-table.js":
/*!*************************************!*\
  !*** ./src/js/team-roster-table.js ***!
  \*************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./tournamatch.js */ "./src/js/tournamatch.js");
/**
 * Team roster table page.
 *
 * @link       https://www.tournamatch.com
 * @since      3.25.0
 *
 * @package    Tournamatch
 *
 */


(function (jQuery, $) {
  'use strict';

  var options = trn_team_roster_table_options;

  function confirmRemove(event) {
    event.preventDefault();
    var teamMemberId = this.dataset.teamMemberId;
    console.log("modal was confirmed for link ".concat(this.dataset.teamMemberId));
    var xhr = new XMLHttpRequest();
    xhr.open('DELETE', "".concat(options.api_url, "team-members/").concat(this.dataset.teamMemberId));
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

    xhr.onload = function () {
      if (xhr.status === 204) {
        $.event('team-members').dispatchEvent(new CustomEvent('changed'));
      } else {
        var response = JSON.parse(xhr.response);
        document.getElementById('trn-delete-team-member-response').innerHTML = "<div class=\"trn-alert trn-alert-danger\"><strong>".concat(options.language.failure, "</strong>: ").concat(response.message, "</div>");
      }
    };

    xhr.send();
  }

  function updateRank(teamMemberId, newRankId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', options.api_url + 'team-members/' + teamMemberId);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

    xhr.onload = function () {
      if (xhr.status === 200) {
        $.event('team-members').dispatchEvent(new CustomEvent('changed'));
      } else {
        var response = JSON.parse(xhr.response);
        document.getElementById('trn-team-roster-response').innerHTML = "<div class=\"trn-alert trn-alert-danger\"><strong>".concat(options.language.failure, "</strong>: ").concat(response.message, "</div>");
      }
    };

    xhr.send($.param({
      team_rank_id: newRankId
    }));
  }

  function rankChanged(event) {
    var newRankId = event.target.value;
    var newRankWeight = event.target.querySelector("option[value=\"".concat(newRankId, "\"]")).dataset.rankWeight;
    var oldRankId = this.dataset.currentRankId;
    var teamMemberId = this.dataset.teamMemberId;

    if (newRankId !== oldRankId) {
      if ('1' === newRankWeight && confirm(options.language.confirm_new_owner)) {
        updateRank(teamMemberId, newRankId);
      } else {
        updateRank(teamMemberId, newRankId);
      }
    }
  }

  function attachListeners() {
    var links = document.getElementsByClassName('trn-drop-player-action');
    Array.prototype.forEach.call(links, function (link) {
      link.addEventListener('trn.confirmed.action.drop-player', confirmRemove);
    });
    var ranks = document.getElementsByClassName('trn-change-rank-dropdown');
    Array.prototype.forEach.call(ranks, function (rank) {
      rank.addEventListener('change', rankChanged);
    });
  }

  window.addEventListener('load', function () {
    document.addEventListener('trn-html-updated', attachListeners);
    $.event('team-members').addEventListener('changed', function () {
      jQuery('#trn-team-roster-table').DataTable().ajax.reload();
    });
    var target = 0;
    var columnDefs = [{
      targets: target++,
      name: 'player',
      className: 'trn-team-roster-name',
      render: function render(data, type, row) {
        return "<img src=\"".concat(options.flag_directory).concat(row._embedded.player[0].flag, "\" width=\"18\" height=\"12\" title=\"").concat(row._embedded.player[0].flag, "\"> <a href=\"").concat(row._embedded.player[0].link, "\">").concat(row._embedded.player[0].name, "</a>");
      }
    }, {
      targets: target++,
      name: 'title',
      className: 'trn-team-roster-title',
      render: function render(data, type, row) {
        if (options.can_edit_roster && row._embedded.rank[0].weight != '1') {
          var html = "<select class=\"trn-form-control trn-form-control-sm trn-change-rank-dropdown\" data-current-rank-id=\"".concat(row.team_rank_id, "\" data-team-member-id=\"").concat(row.team_member_id, "\" >");
          Array.prototype.forEach.call(options.ranks, function (rank) {
            if (rank.team_rank_id == row.team_rank_id) {
              html += "<option value=\"".concat(rank.team_rank_id, "\" selected data-rank-weight=\"").concat(rank.weight, "\">").concat(rank.title, "</option>");
            } else {
              html += "<option value=\"".concat(rank.team_rank_id, "\" data-rank-weight=\"").concat(rank.weight, "\">").concat(rank.title, "</option>");
            }
          });
          html += "</select>";
          return html;
        } else {
          return row._embedded.rank[0].title;
        }
      }
    }];

    if (options.display_record) {
      columnDefs.push({
        targets: target++,
        name: 'wins',
        className: 'trn-team-roster-wins',
        render: function render(data, type, row) {
          return row.wins;
        }
      }, {
        targets: target++,
        name: 'losses',
        className: 'trn-team-roster-losses',
        render: function render(data, type, row) {
          return row.losses;
        }
      });

      if (options.uses_draws) {
        columnDefs.push({
          targets: target++,
          name: 'draws',
          className: 'trn-team-roster-draws',
          render: function render(data, type, row) {
            return row.draws;
          }
        });
      }
    }

    columnDefs.push({
      targets: target++,
      name: 'joined_date',
      className: 'trn-team-roster-joined',
      render: function render(data, type, row) {
        return row.joined_date.rendered;
      }
    }, {
      targets: target++,
      name: 'options',
      className: 'trn-team-roster-options',
      render: function render(data, type, row) {
        if (options.can_edit_roster && row._embedded.rank[0].weight != '1') {
          return "<a class=\"trn-drop-player-action trn-button trn-button-sm trn-button-secondary trn-confirm-action-link\" data-team-member-id=\"".concat(row.team_member_id, "\" data-confirm-title=\"").concat(options.language.drop_team_member, "\" data-confirm-message=\"").concat(options.language.drop_confirm.format(row._embedded.player[0].name), "\" data-modal-id=\"drop-player\" href=\"#\">").concat(options.language.drop_player, "</a>");
        } else {
          return '';
        }
      }
    });
    jQuery('#trn-team-roster-table').on('xhr.dt', function (e, settings, json, xhr) {
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
      searching: false,
      lengthChange: false,
      paging: false,
      ajax: {
        url: "".concat(options.api_url, "team-members/?team_id=").concat(options.team_id, "&_wpnonce=").concat(options.rest_nonce, "&_embed"),
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

/***/ 37:
/*!*******************************************!*\
  !*** multi ./src/js/team-roster-table.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\team-roster-table.js */"./src/js/team-roster-table.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3RlYW0tcm9zdGVyLXRhYmxlLmpzIiwid2VicGFjazovLy8uL3NyYy9qcy90b3VybmFtYXRjaC5qcyJdLCJuYW1lcyI6WyJqUXVlcnkiLCIkIiwib3B0aW9ucyIsInRybl90ZWFtX3Jvc3Rlcl90YWJsZV9vcHRpb25zIiwiY29uZmlybVJlbW92ZSIsImV2ZW50IiwicHJldmVudERlZmF1bHQiLCJ0ZWFtTWVtYmVySWQiLCJkYXRhc2V0IiwiY29uc29sZSIsImxvZyIsInhociIsIlhNTEh0dHBSZXF1ZXN0Iiwib3BlbiIsImFwaV91cmwiLCJzZXRSZXF1ZXN0SGVhZGVyIiwicmVzdF9ub25jZSIsIm9ubG9hZCIsInN0YXR1cyIsImRpc3BhdGNoRXZlbnQiLCJDdXN0b21FdmVudCIsInJlc3BvbnNlIiwiSlNPTiIsInBhcnNlIiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50QnlJZCIsImlubmVySFRNTCIsImxhbmd1YWdlIiwiZmFpbHVyZSIsIm1lc3NhZ2UiLCJzZW5kIiwidXBkYXRlUmFuayIsIm5ld1JhbmtJZCIsInBhcmFtIiwidGVhbV9yYW5rX2lkIiwicmFua0NoYW5nZWQiLCJ0YXJnZXQiLCJ2YWx1ZSIsIm5ld1JhbmtXZWlnaHQiLCJxdWVyeVNlbGVjdG9yIiwicmFua1dlaWdodCIsIm9sZFJhbmtJZCIsImN1cnJlbnRSYW5rSWQiLCJjb25maXJtIiwiY29uZmlybV9uZXdfb3duZXIiLCJhdHRhY2hMaXN0ZW5lcnMiLCJsaW5rcyIsImdldEVsZW1lbnRzQnlDbGFzc05hbWUiLCJBcnJheSIsInByb3RvdHlwZSIsImZvckVhY2giLCJjYWxsIiwibGluayIsImFkZEV2ZW50TGlzdGVuZXIiLCJyYW5rcyIsInJhbmsiLCJ3aW5kb3ciLCJEYXRhVGFibGUiLCJhamF4IiwicmVsb2FkIiwiY29sdW1uRGVmcyIsInRhcmdldHMiLCJuYW1lIiwiY2xhc3NOYW1lIiwicmVuZGVyIiwiZGF0YSIsInR5cGUiLCJyb3ciLCJmbGFnX2RpcmVjdG9yeSIsIl9lbWJlZGRlZCIsInBsYXllciIsImZsYWciLCJjYW5fZWRpdF9yb3N0ZXIiLCJ3ZWlnaHQiLCJodG1sIiwidGVhbV9tZW1iZXJfaWQiLCJ0aXRsZSIsImRpc3BsYXlfcmVjb3JkIiwicHVzaCIsIndpbnMiLCJsb3NzZXMiLCJ1c2VzX2RyYXdzIiwiZHJhd3MiLCJqb2luZWRfZGF0ZSIsInJlbmRlcmVkIiwiZHJvcF90ZWFtX21lbWJlciIsImRyb3BfY29uZmlybSIsImZvcm1hdCIsImRyb3BfcGxheWVyIiwib24iLCJlIiwic2V0dGluZ3MiLCJqc29uIiwic3RyaW5naWZ5IiwicmVjb3Jkc1RvdGFsIiwiZ2V0UmVzcG9uc2VIZWFkZXIiLCJyZWNvcmRzRmlsdGVyZWQiLCJsZW5ndGgiLCJkcmF3IiwicHJvY2Vzc2luZyIsInNlcnZlclNpZGUiLCJsZW5ndGhNZW51IiwiYXV0b1dpZHRoIiwic2VhcmNoaW5nIiwibGVuZ3RoQ2hhbmdlIiwicGFnaW5nIiwidXJsIiwidGVhbV9pZCIsInNlbnQiLCJwYWdlIiwiTWF0aCIsImZsb29yIiwic3RhcnQiLCJwZXJfcGFnZSIsInNlYXJjaCIsIm9yZGVyYnkiLCJjb2x1bW5zIiwib3JkZXIiLCJjb2x1bW4iLCJkaXIiLCJkcmF3Q2FsbGJhY2siLCJ0cm4iLCJUb3VybmFtYXRjaCIsImV2ZW50cyIsIm9iamVjdCIsInByZWZpeCIsInN0ciIsInByb3AiLCJoYXNPd25Qcm9wZXJ0eSIsImsiLCJ2IiwiZW5jb2RlVVJJQ29tcG9uZW50Iiwiam9pbiIsImV2ZW50TmFtZSIsIkV2ZW50VGFyZ2V0IiwiaW5wdXQiLCJkYXRhQ2FsbGJhY2siLCJUb3VybmFtYXRjaF9BdXRvY29tcGxldGUiLCJzIiwiY2hhckF0IiwidG9VcHBlckNhc2UiLCJzbGljZSIsIm51bWJlciIsInJlbWFpbmRlciIsImVsZW1lbnQiLCJ0YWJzIiwicGFuZXMiLCJjbGVhckFjdGl2ZSIsInRhYiIsImNsYXNzTGlzdCIsInJlbW92ZSIsImFyaWFTZWxlY3RlZCIsInBhbmUiLCJzZXRBY3RpdmUiLCJ0YXJnZXRJZCIsInRhcmdldFRhYiIsInRhcmdldFBhbmVJZCIsImFkZCIsInRhYkNsaWNrIiwiY3VycmVudFRhcmdldCIsImxvY2F0aW9uIiwiaGFzaCIsInN1YnN0ciIsInRybl9vYmpfaW5zdGFuY2UiLCJ0YWJWaWV3cyIsImZyb20iLCJkcm9wZG93bnMiLCJoYW5kbGVEcm9wZG93bkNsb3NlIiwiZHJvcGRvd24iLCJuZXh0RWxlbWVudFNpYmxpbmciLCJyZW1vdmVFdmVudExpc3RlbmVyIiwic3RvcFByb3BhZ2F0aW9uIiwibmFtZUlucHV0IiwiYSIsImIiLCJpIiwidmFsIiwicGFyZW50IiwicGFyZW50Tm9kZSIsInRoZW4iLCJjbG9zZUFsbExpc3RzIiwiY3VycmVudEZvY3VzIiwiY3JlYXRlRWxlbWVudCIsInNldEF0dHJpYnV0ZSIsImlkIiwiYXBwZW5kQ2hpbGQiLCJ0ZXh0Iiwic2VsZWN0ZWRJZCIsIkV2ZW50IiwieCIsImdldEVsZW1lbnRzQnlUYWdOYW1lIiwia2V5Q29kZSIsImFkZEFjdGl2ZSIsImNsaWNrIiwicmVtb3ZlQWN0aXZlIiwicmVtb3ZlQ2hpbGQiLCJTdHJpbmciLCJhcmdzIiwiYXJndW1lbnRzIiwicmVwbGFjZSIsIm1hdGNoIl0sIm1hcHBpbmdzIjoiO1FBQUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7OztRQUdBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSwwQ0FBMEMsZ0NBQWdDO1FBQzFFO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0Esd0RBQXdELGtCQUFrQjtRQUMxRTtRQUNBLGlEQUFpRCxjQUFjO1FBQy9EOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFD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Qyx5QkFBaEMsQ0FBUjs7QUFDQSxXQUFLLElBQUlnSSxDQUFDLEdBQUcsQ0FBYixFQUFnQkEsQ0FBQyxHQUFHYyxDQUFDLENBQUM1RixNQUF0QixFQUE4QjhFLENBQUMsRUFBL0IsRUFBbUM7QUFDL0IsWUFBSS9CLE9BQU8sS0FBSzZDLENBQUMsQ0FBQ2QsQ0FBRCxDQUFiLElBQW9CL0IsT0FBTyxLQUFLLEtBQUs0QixTQUF6QyxFQUFvRDtBQUNoRGlCLFdBQUMsQ0FBQ2QsQ0FBRCxDQUFELENBQUtHLFVBQUwsQ0FBZ0JpQixXQUFoQixDQUE0Qk4sQ0FBQyxDQUFDZCxDQUFELENBQTdCO0FBQ0g7QUFDSjtBQUNKOzs7O0tBR0w7OztBQUNBLElBQUksQ0FBQ3FCLE1BQU0sQ0FBQ25KLFNBQVAsQ0FBaUJzQyxNQUF0QixFQUE4QjtBQUMxQjZHLFFBQU0sQ0FBQ25KLFNBQVAsQ0FBaUJzQyxNQUFqQixHQUEwQixZQUFXO0FBQ2pDLFFBQU04RyxJQUFJLEdBQUdDLFNBQWI7QUFDQSxXQUFPLEtBQUtDLE9BQUwsQ0FBYSxVQUFiLEVBQXlCLFVBQVNDLEtBQVQsRUFBZ0IxRCxNQUFoQixFQUF3QjtBQUNwRCxhQUFPLE9BQU91RCxJQUFJLENBQUN2RCxNQUFELENBQVgsS0FBd0IsV0FBeEIsR0FDRHVELElBQUksQ0FBQ3ZELE1BQUQsQ0FESCxHQUVEMEQsS0FGTjtBQUlILEtBTE0sQ0FBUDtBQU1ILEdBUkQ7QUFTSCxDIiwiZmlsZSI6InRlYW0tcm9zdGVyLXRhYmxlLmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7IGVudW1lcmFibGU6IHRydWUsIGdldDogZ2V0dGVyIH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBkZWZpbmUgX19lc01vZHVsZSBvbiBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIgPSBmdW5jdGlvbihleHBvcnRzKSB7XG4gXHRcdGlmKHR5cGVvZiBTeW1ib2wgIT09ICd1bmRlZmluZWQnICYmIFN5bWJvbC50b1N0cmluZ1RhZykge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBTeW1ib2wudG9TdHJpbmdUYWcsIHsgdmFsdWU6ICdNb2R1bGUnIH0pO1xuIFx0XHR9XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCAnX19lc01vZHVsZScsIHsgdmFsdWU6IHRydWUgfSk7XG4gXHR9O1xuXG4gXHQvLyBjcmVhdGUgYSBmYWtlIG5hbWVzcGFjZSBvYmplY3RcbiBcdC8vIG1vZGUgJiAxOiB2YWx1ZSBpcyBhIG1vZHVsZSBpZCwgcmVxdWlyZSBpdFxuIFx0Ly8gbW9kZSAmIDI6IG1lcmdlIGFsbCBwcm9wZXJ0aWVzIG9mIHZhbHVlIGludG8gdGhlIG5zXG4gXHQvLyBtb2RlICYgNDogcmV0dXJuIHZhbHVlIHdoZW4gYWxyZWFkeSBucyBvYmplY3RcbiBcdC8vIG1vZGUgJiA4fDE6IGJlaGF2ZSBsaWtlIHJlcXVpcmVcbiBcdF9fd2VicGFja19yZXF1aXJlX18udCA9IGZ1bmN0aW9uKHZhbHVlLCBtb2RlKSB7XG4gXHRcdGlmKG1vZGUgJiAxKSB2YWx1ZSA9IF9fd2VicGFja19yZXF1aXJlX18odmFsdWUpO1xuIFx0XHRpZihtb2RlICYgOCkgcmV0dXJuIHZhbHVlO1xuIFx0XHRpZigobW9kZSAmIDQpICYmIHR5cGVvZiB2YWx1ZSA9PT0gJ29iamVjdCcgJiYgdmFsdWUgJiYgdmFsdWUuX19lc01vZHVsZSkgcmV0dXJuIHZhbHVlO1xuIFx0XHR2YXIgbnMgPSBPYmplY3QuY3JlYXRlKG51bGwpO1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIobnMpO1xuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkobnMsICdkZWZhdWx0JywgeyBlbnVtZXJhYmxlOiB0cnVlLCB2YWx1ZTogdmFsdWUgfSk7XG4gXHRcdGlmKG1vZGUgJiAyICYmIHR5cGVvZiB2YWx1ZSAhPSAnc3RyaW5nJykgZm9yKHZhciBrZXkgaW4gdmFsdWUpIF9fd2VicGFja19yZXF1aXJlX18uZChucywga2V5LCBmdW5jdGlvbihrZXkpIHsgcmV0dXJuIHZhbHVlW2tleV07IH0uYmluZChudWxsLCBrZXkpKTtcbiBcdFx0cmV0dXJuIG5zO1xuIFx0fTtcblxuIFx0Ly8gZ2V0RGVmYXVsdEV4cG9ydCBmdW5jdGlvbiBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIG5vbi1oYXJtb255IG1vZHVsZXNcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubiA9IGZ1bmN0aW9uKG1vZHVsZSkge1xuIFx0XHR2YXIgZ2V0dGVyID0gbW9kdWxlICYmIG1vZHVsZS5fX2VzTW9kdWxlID9cbiBcdFx0XHRmdW5jdGlvbiBnZXREZWZhdWx0KCkgeyByZXR1cm4gbW9kdWxlWydkZWZhdWx0J107IH0gOlxuIFx0XHRcdGZ1bmN0aW9uIGdldE1vZHVsZUV4cG9ydHMoKSB7IHJldHVybiBtb2R1bGU7IH07XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18uZChnZXR0ZXIsICdhJywgZ2V0dGVyKTtcbiBcdFx0cmV0dXJuIGdldHRlcjtcbiBcdH07XG5cbiBcdC8vIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbFxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5vID0gZnVuY3Rpb24ob2JqZWN0LCBwcm9wZXJ0eSkgeyByZXR1cm4gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iamVjdCwgcHJvcGVydHkpOyB9O1xuXG4gXHQvLyBfX3dlYnBhY2tfcHVibGljX3BhdGhfX1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5wID0gXCJcIjtcblxuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IDM3KTtcbiIsIi8qKlxyXG4gKiBUZWFtIHJvc3RlciB0YWJsZSBwYWdlLlxyXG4gKlxyXG4gKiBAbGluayAgICAgICBodHRwczovL3d3dy50b3VybmFtYXRjaC5jb21cclxuICogQHNpbmNlICAgICAgMy4yNS4wXHJcbiAqXHJcbiAqIEBwYWNrYWdlICAgIFRvdXJuYW1hdGNoXHJcbiAqXHJcbiAqL1xyXG5pbXBvcnQgeyB0cm4gfSBmcm9tICcuL3RvdXJuYW1hdGNoLmpzJztcclxuXHJcbihmdW5jdGlvbiAoalF1ZXJ5LCAkKSB7XHJcbiAgICAndXNlIHN0cmljdCc7XHJcblxyXG4gICAgbGV0IG9wdGlvbnMgPSB0cm5fdGVhbV9yb3N0ZXJfdGFibGVfb3B0aW9ucztcclxuXHJcbiAgICBmdW5jdGlvbiBjb25maXJtUmVtb3ZlKGV2ZW50KSB7XHJcbiAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICBjb25zdCB0ZWFtTWVtYmVySWQgPSB0aGlzLmRhdGFzZXQudGVhbU1lbWJlcklkO1xyXG5cclxuICAgICAgICBjb25zb2xlLmxvZyhgbW9kYWwgd2FzIGNvbmZpcm1lZCBmb3IgbGluayAke3RoaXMuZGF0YXNldC50ZWFtTWVtYmVySWR9YCk7XHJcbiAgICAgICAgbGV0IHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xyXG4gICAgICAgIHhoci5vcGVuKCdERUxFVEUnLCBgJHtvcHRpb25zLmFwaV91cmx9dGVhbS1tZW1iZXJzLyR7dGhpcy5kYXRhc2V0LnRlYW1NZW1iZXJJZH1gKTtcclxuICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xyXG4gICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICB4aHIub25sb2FkID0gKCkgPT4ge1xyXG4gICAgICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjA0KSB7XHJcbiAgICAgICAgICAgICAgICAkLmV2ZW50KCd0ZWFtLW1lbWJlcnMnKS5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudCgnY2hhbmdlZCcpKTtcclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgIGxldCByZXNwb25zZSA9IEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKTtcclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tZGVsZXRlLXRlYW0tbWVtYmVyLXJlc3BvbnNlJykuaW5uZXJIVE1MID0gYDxkaXYgY2xhc3M9XCJ0cm4tYWxlcnQgdHJuLWFsZXJ0LWRhbmdlclwiPjxzdHJvbmc+JHtvcHRpb25zLmxhbmd1YWdlLmZhaWx1cmV9PC9zdHJvbmc+OiAke3Jlc3BvbnNlLm1lc3NhZ2V9PC9kaXY+YDtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH07XHJcblxyXG4gICAgICAgIHhoci5zZW5kKCk7XHJcbiAgICB9XHJcblxyXG4gICAgZnVuY3Rpb24gdXBkYXRlUmFuayh0ZWFtTWVtYmVySWQsIG5ld1JhbmtJZCkge1xyXG4gICAgICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICB4aHIub3BlbignUE9TVCcsIG9wdGlvbnMuYXBpX3VybCArICd0ZWFtLW1lbWJlcnMvJyArIHRlYW1NZW1iZXJJZCk7XHJcbiAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignWC1XUC1Ob25jZScsIG9wdGlvbnMucmVzdF9ub25jZSk7XHJcbiAgICAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMCkge1xyXG4gICAgICAgICAgICAgICAgJC5ldmVudCgndGVhbS1tZW1iZXJzJykuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoJ2NoYW5nZWQnKSk7XHJcbiAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICBsZXQgcmVzcG9uc2UgPSBKU09OLnBhcnNlKHhoci5yZXNwb25zZSk7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLXRlYW0tcm9zdGVyLXJlc3BvbnNlJykuaW5uZXJIVE1MID0gYDxkaXYgY2xhc3M9XCJ0cm4tYWxlcnQgdHJuLWFsZXJ0LWRhbmdlclwiPjxzdHJvbmc+JHtvcHRpb25zLmxhbmd1YWdlLmZhaWx1cmV9PC9zdHJvbmc+OiAke3Jlc3BvbnNlLm1lc3NhZ2V9PC9kaXY+YDtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH07XHJcblxyXG4gICAgICAgIHhoci5zZW5kKCQucGFyYW0oe1xyXG4gICAgICAgICAgICB0ZWFtX3JhbmtfaWQ6IG5ld1JhbmtJZCxcclxuICAgICAgICB9KSk7XHJcbiAgICB9XHJcblxyXG4gICAgZnVuY3Rpb24gcmFua0NoYW5nZWQoZXZlbnQpIHtcclxuICAgICAgICBjb25zdCBuZXdSYW5rSWQgPSBldmVudC50YXJnZXQudmFsdWU7XHJcbiAgICAgICAgY29uc3QgbmV3UmFua1dlaWdodCA9IGV2ZW50LnRhcmdldC5xdWVyeVNlbGVjdG9yKGBvcHRpb25bdmFsdWU9XCIke25ld1JhbmtJZH1cIl1gKS5kYXRhc2V0LnJhbmtXZWlnaHQ7XHJcbiAgICAgICAgY29uc3Qgb2xkUmFua0lkID0gdGhpcy5kYXRhc2V0LmN1cnJlbnRSYW5rSWQ7XHJcbiAgICAgICAgY29uc3QgdGVhbU1lbWJlcklkID0gdGhpcy5kYXRhc2V0LnRlYW1NZW1iZXJJZDtcclxuXHJcbiAgICAgICAgaWYgKG5ld1JhbmtJZCAhPT0gb2xkUmFua0lkKSB7XHJcbiAgICAgICAgICAgIGlmICgoJzEnID09PSBuZXdSYW5rV2VpZ2h0KSAmJiBjb25maXJtKG9wdGlvbnMubGFuZ3VhZ2UuY29uZmlybV9uZXdfb3duZXIpKSB7XHJcbiAgICAgICAgICAgICAgICB1cGRhdGVSYW5rKHRlYW1NZW1iZXJJZCwgbmV3UmFua0lkKTtcclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgIHVwZGF0ZVJhbmsodGVhbU1lbWJlcklkLCBuZXdSYW5rSWQpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIGZ1bmN0aW9uIGF0dGFjaExpc3RlbmVycygpIHtcclxuICAgICAgICBsZXQgbGlua3MgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tZHJvcC1wbGF5ZXItYWN0aW9uJyk7XHJcbiAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbChsaW5rcywgZnVuY3Rpb24gKGxpbmspIHtcclxuICAgICAgICAgICAgbGluay5hZGRFdmVudExpc3RlbmVyKCd0cm4uY29uZmlybWVkLmFjdGlvbi5kcm9wLXBsYXllcicsIGNvbmZpcm1SZW1vdmUpO1xyXG4gICAgICAgIH0pO1xyXG4gICAgICAgIGxldCByYW5rcyA9IGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1jaGFuZ2UtcmFuay1kcm9wZG93bicpO1xyXG4gICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwocmFua3MsIGZ1bmN0aW9uIChyYW5rKSB7XHJcbiAgICAgICAgICAgIHJhbmsuYWRkRXZlbnRMaXN0ZW5lcignY2hhbmdlJywgcmFua0NoYW5nZWQpO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG5cclxuICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ3Rybi1odG1sLXVwZGF0ZWQnLCBhdHRhY2hMaXN0ZW5lcnMpO1xyXG5cclxuICAgICAgICAkLmV2ZW50KCd0ZWFtLW1lbWJlcnMnKS5hZGRFdmVudExpc3RlbmVyKCdjaGFuZ2VkJywgZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgICAgIGpRdWVyeSgnI3Rybi10ZWFtLXJvc3Rlci10YWJsZScpLkRhdGFUYWJsZSgpLmFqYXgucmVsb2FkKClcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgbGV0IHRhcmdldCA9IDA7XHJcbiAgICAgICAgbGV0IGNvbHVtbkRlZnMgPSBbXHJcbiAgICAgICAgICAgIHtcclxuICAgICAgICAgICAgICAgIHRhcmdldHM6IHRhcmdldCsrLFxyXG4gICAgICAgICAgICAgICAgbmFtZTogJ3BsYXllcicsXHJcbiAgICAgICAgICAgICAgICBjbGFzc05hbWU6ICd0cm4tdGVhbS1yb3N0ZXItbmFtZScsXHJcbiAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uKGRhdGEsIHR5cGUsIHJvdykge1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBgPGltZyBzcmM9XCIke29wdGlvbnMuZmxhZ19kaXJlY3Rvcnl9JHtyb3cuX2VtYmVkZGVkLnBsYXllclswXS5mbGFnfVwiIHdpZHRoPVwiMThcIiBoZWlnaHQ9XCIxMlwiIHRpdGxlPVwiJHtyb3cuX2VtYmVkZGVkLnBsYXllclswXS5mbGFnfVwiPiA8YSBocmVmPVwiJHtyb3cuX2VtYmVkZGVkLnBsYXllclswXS5saW5rfVwiPiR7cm93Ll9lbWJlZGRlZC5wbGF5ZXJbMF0ubmFtZX08L2E+YDtcclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIHtcclxuICAgICAgICAgICAgICAgIHRhcmdldHM6IHRhcmdldCsrLFxyXG4gICAgICAgICAgICAgICAgbmFtZTogJ3RpdGxlJyxcclxuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ3Rybi10ZWFtLXJvc3Rlci10aXRsZScsXHJcbiAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uIChkYXRhLCB0eXBlLCByb3cpIHtcclxuICAgICAgICAgICAgICAgICAgICBpZiAoKG9wdGlvbnMuY2FuX2VkaXRfcm9zdGVyKSAmJiAocm93Ll9lbWJlZGRlZC5yYW5rWzBdLndlaWdodCAhPSAnMScpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGxldCBodG1sID0gYDxzZWxlY3QgY2xhc3M9XCJ0cm4tZm9ybS1jb250cm9sIHRybi1mb3JtLWNvbnRyb2wtc20gdHJuLWNoYW5nZS1yYW5rLWRyb3Bkb3duXCIgZGF0YS1jdXJyZW50LXJhbmstaWQ9XCIke3Jvdy50ZWFtX3JhbmtfaWR9XCIgZGF0YS10ZWFtLW1lbWJlci1pZD1cIiR7cm93LnRlYW1fbWVtYmVyX2lkfVwiID5gO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbChvcHRpb25zLnJhbmtzLCAocmFuaykgPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHJhbmsudGVhbV9yYW5rX2lkID09IHJvdy50ZWFtX3JhbmtfaWQpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBodG1sICs9IGA8b3B0aW9uIHZhbHVlPVwiJHtyYW5rLnRlYW1fcmFua19pZH1cIiBzZWxlY3RlZCBkYXRhLXJhbmstd2VpZ2h0PVwiJHtyYW5rLndlaWdodH1cIj4ke3JhbmsudGl0bGV9PC9vcHRpb24+YDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaHRtbCArPSBgPG9wdGlvbiB2YWx1ZT1cIiR7cmFuay50ZWFtX3JhbmtfaWR9XCIgZGF0YS1yYW5rLXdlaWdodD1cIiR7cmFuay53ZWlnaHR9XCI+JHtyYW5rLnRpdGxlfTwvb3B0aW9uPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgaHRtbCArPSBgPC9zZWxlY3Q+YDtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBodG1sO1xyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiByb3cuX2VtYmVkZGVkLnJhbmtbMF0udGl0bGU7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICBdO1xyXG5cclxuICAgICAgICBpZiAob3B0aW9ucy5kaXNwbGF5X3JlY29yZCkge1xyXG4gICAgICAgICAgICBjb2x1bW5EZWZzLnB1c2goXHJcbiAgICAgICAgICAgICAgICB7XHJcbiAgICAgICAgICAgICAgICAgICAgdGFyZ2V0czogdGFyZ2V0KyssXHJcbiAgICAgICAgICAgICAgICAgICAgbmFtZTogJ3dpbnMnLFxyXG4gICAgICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ3Rybi10ZWFtLXJvc3Rlci13aW5zJyxcclxuICAgICAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uIChkYXRhLCB0eXBlLCByb3cpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIHJvdy53aW5zO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgICAgICB7XHJcbiAgICAgICAgICAgICAgICAgICAgdGFyZ2V0czogdGFyZ2V0KyssXHJcbiAgICAgICAgICAgICAgICAgICAgbmFtZTogJ2xvc3NlcycsXHJcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAndHJuLXRlYW0tcm9zdGVyLWxvc3NlcycsXHJcbiAgICAgICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiByb3cubG9zc2VzO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgICk7XHJcblxyXG4gICAgICAgICAgICBpZiAob3B0aW9ucy51c2VzX2RyYXdzKSB7XHJcbiAgICAgICAgICAgICAgICBjb2x1bW5EZWZzLnB1c2goXHJcbiAgICAgICAgICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB0YXJnZXRzOiB0YXJnZXQrKyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgbmFtZTogJ2RyYXdzJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAndHJuLXRlYW0tcm9zdGVyLWRyYXdzJyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmVuZGVyOiBmdW5jdGlvbiAoZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gcm93LmRyYXdzO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgICAgICk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGNvbHVtbkRlZnMucHVzaChcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0czogdGFyZ2V0KyssXHJcbiAgICAgICAgICAgICAgICBuYW1lOiAnam9pbmVkX2RhdGUnLFxyXG4gICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAndHJuLXRlYW0tcm9zdGVyLWpvaW5lZCcsXHJcbiAgICAgICAgICAgICAgICByZW5kZXI6IGZ1bmN0aW9uKGRhdGEsIHR5cGUsIHJvdykge1xyXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiByb3cuam9pbmVkX2RhdGUucmVuZGVyZWQ7XHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICB7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRzOiB0YXJnZXQrKyxcclxuICAgICAgICAgICAgICAgIG5hbWU6ICdvcHRpb25zJyxcclxuICAgICAgICAgICAgICAgIGNsYXNzTmFtZTogJ3Rybi10ZWFtLXJvc3Rlci1vcHRpb25zJyxcclxuICAgICAgICAgICAgICAgIHJlbmRlcjogZnVuY3Rpb24oZGF0YSwgdHlwZSwgcm93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKChvcHRpb25zLmNhbl9lZGl0X3Jvc3RlcikgJiYgKHJvdy5fZW1iZWRkZWQucmFua1swXS53ZWlnaHQgIT0gJzEnKSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gYDxhIGNsYXNzPVwidHJuLWRyb3AtcGxheWVyLWFjdGlvbiB0cm4tYnV0dG9uIHRybi1idXR0b24tc20gdHJuLWJ1dHRvbi1zZWNvbmRhcnkgdHJuLWNvbmZpcm0tYWN0aW9uLWxpbmtcIiBkYXRhLXRlYW0tbWVtYmVyLWlkPVwiJHtyb3cudGVhbV9tZW1iZXJfaWR9XCIgZGF0YS1jb25maXJtLXRpdGxlPVwiJHtvcHRpb25zLmxhbmd1YWdlLmRyb3BfdGVhbV9tZW1iZXJ9XCIgZGF0YS1jb25maXJtLW1lc3NhZ2U9XCIke29wdGlvbnMubGFuZ3VhZ2UuZHJvcF9jb25maXJtLmZvcm1hdChyb3cuX2VtYmVkZGVkLnBsYXllclswXS5uYW1lKX1cIiBkYXRhLW1vZGFsLWlkPVwiZHJvcC1wbGF5ZXJcIiBocmVmPVwiI1wiPiR7b3B0aW9ucy5sYW5ndWFnZS5kcm9wX3BsYXllcn08L2E+YDtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZXR1cm4gJyc7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICApO1xyXG5cclxuICAgICAgICBqUXVlcnkoJyN0cm4tdGVhbS1yb3N0ZXItdGFibGUnKVxyXG4gICAgICAgICAgICAub24oJ3hoci5kdCcsIGZ1bmN0aW9uKCBlLCBzZXR0aW5ncywganNvbiwgeGhyICkge1xyXG4gICAgICAgICAgICAgICAganNvbi5kYXRhID0gSlNPTi5wYXJzZShKU09OLnN0cmluZ2lmeShqc29uKSk7XHJcbiAgICAgICAgICAgICAgICBqc29uLnJlY29yZHNUb3RhbCA9IHhoci5nZXRSZXNwb25zZUhlYWRlcignWC1XUC1Ub3RhbCcpO1xyXG4gICAgICAgICAgICAgICAganNvbi5yZWNvcmRzRmlsdGVyZWQgPSB4aHIuZ2V0UmVzcG9uc2VIZWFkZXIoJ1RSTi1GaWx0ZXJlZCcpO1xyXG4gICAgICAgICAgICAgICAganNvbi5sZW5ndGggPSB4aHIuZ2V0UmVzcG9uc2VIZWFkZXIoJ1gtV1AtVG90YWxQYWdlcycpO1xyXG4gICAgICAgICAgICAgICAganNvbi5kcmF3ID0geGhyLmdldFJlc3BvbnNlSGVhZGVyKCdUUk4tRHJhdycpO1xyXG4gICAgICAgICAgICB9KVxyXG4gICAgICAgICAgICAuRGF0YVRhYmxlKHtcclxuICAgICAgICAgICAgICAgIHByb2Nlc3Npbmc6IHRydWUsXHJcbiAgICAgICAgICAgICAgICBzZXJ2ZXJTaWRlOiB0cnVlLFxyXG4gICAgICAgICAgICAgICAgbGVuZ3RoTWVudTogW1syNSwgNTAsIDEwMCwgLTFdLCBbMjUsIDUwLCAxMDAsICdBbGwnXV0sXHJcbiAgICAgICAgICAgICAgICBsYW5ndWFnZTogb3B0aW9uc1sndGFibGVfbGFuZ3VhZ2UnXSxcclxuICAgICAgICAgICAgICAgIGF1dG9XaWR0aDogZmFsc2UsXHJcbiAgICAgICAgICAgICAgICBzZWFyY2hpbmc6IGZhbHNlLFxyXG4gICAgICAgICAgICAgICAgbGVuZ3RoQ2hhbmdlOiBmYWxzZSxcclxuICAgICAgICAgICAgICAgIHBhZ2luZzogZmFsc2UsXHJcbiAgICAgICAgICAgICAgICBhamF4OiB7XHJcbiAgICAgICAgICAgICAgICAgICAgdXJsOiBgJHtvcHRpb25zLmFwaV91cmx9dGVhbS1tZW1iZXJzLz90ZWFtX2lkPSR7b3B0aW9ucy50ZWFtX2lkfSZfd3Bub25jZT0ke29wdGlvbnMucmVzdF9ub25jZX0mX2VtYmVkYCxcclxuICAgICAgICAgICAgICAgICAgICB0eXBlOiAnR0VUJyxcclxuICAgICAgICAgICAgICAgICAgICBkYXRhOiBmdW5jdGlvbihkYXRhKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGxldCBzZW50ID0ge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZHJhdzogZGF0YS5kcmF3LFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcGFnZTogTWF0aC5mbG9vcihkYXRhLnN0YXJ0IC8gZGF0YS5sZW5ndGgpLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcGVyX3BhZ2U6IGRhdGEubGVuZ3RoLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2VhcmNoOiBkYXRhLnNlYXJjaC52YWx1ZSxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG9yZGVyYnk6IGAke2RhdGEuY29sdW1uc1tkYXRhLm9yZGVyWzBdLmNvbHVtbl0ubmFtZX0uJHtkYXRhLm9yZGVyWzBdLmRpcn1gXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH07XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vY29uc29sZS5sb2coc2VudCk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBzZW50O1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgICAgICBvcmRlcjogW1sgMSwgJ2FzYycgXV0sXHJcbiAgICAgICAgICAgICAgICBjb2x1bW5EZWZzOiBjb2x1bW5EZWZzLFxyXG4gICAgICAgICAgICAgICAgZHJhd0NhbGxiYWNrOiBmdW5jdGlvbiggc2V0dGluZ3MgKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgZG9jdW1lbnQuZGlzcGF0Y2hFdmVudCggbmV3IEN1c3RvbUV2ZW50KCAndHJuLWh0bWwtdXBkYXRlZCcsIHsgJ2RldGFpbCc6ICdUaGUgdGFibGUgaHRtbCBoYXMgdXBkYXRlZC4nIH0gKSk7XHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICB9KTtcclxuXHJcbiAgICB9LCBmYWxzZSk7XHJcbn0pKGpRdWVyeSwgdHJuKTsiLCIndXNlIHN0cmljdCc7XHJcbmNsYXNzIFRvdXJuYW1hdGNoIHtcclxuXHJcbiAgICBjb25zdHJ1Y3RvcigpIHtcclxuICAgICAgICB0aGlzLmV2ZW50cyA9IHt9O1xyXG4gICAgfVxyXG5cclxuICAgIHBhcmFtKG9iamVjdCwgcHJlZml4KSB7XHJcbiAgICAgICAgbGV0IHN0ciA9IFtdO1xyXG4gICAgICAgIGZvciAobGV0IHByb3AgaW4gb2JqZWN0KSB7XHJcbiAgICAgICAgICAgIGlmIChvYmplY3QuaGFzT3duUHJvcGVydHkocHJvcCkpIHtcclxuICAgICAgICAgICAgICAgIGxldCBrID0gcHJlZml4ID8gcHJlZml4ICsgXCJbXCIgKyBwcm9wICsgXCJdXCIgOiBwcm9wO1xyXG4gICAgICAgICAgICAgICAgbGV0IHYgPSBvYmplY3RbcHJvcF07XHJcbiAgICAgICAgICAgICAgICBzdHIucHVzaCgodiAhPT0gbnVsbCAmJiB0eXBlb2YgdiA9PT0gXCJvYmplY3RcIikgPyB0aGlzLnBhcmFtKHYsIGspIDogZW5jb2RlVVJJQ29tcG9uZW50KGspICsgXCI9XCIgKyBlbmNvZGVVUklDb21wb25lbnQodikpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiBzdHIuam9pbihcIiZcIik7XHJcbiAgICB9XHJcblxyXG4gICAgZXZlbnQoZXZlbnROYW1lKSB7XHJcbiAgICAgICAgaWYgKCEoZXZlbnROYW1lIGluIHRoaXMuZXZlbnRzKSkge1xyXG4gICAgICAgICAgICB0aGlzLmV2ZW50c1tldmVudE5hbWVdID0gbmV3IEV2ZW50VGFyZ2V0KGV2ZW50TmFtZSk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiB0aGlzLmV2ZW50c1tldmVudE5hbWVdO1xyXG4gICAgfVxyXG5cclxuICAgIGF1dG9jb21wbGV0ZShpbnB1dCwgZGF0YUNhbGxiYWNrKSB7XHJcbiAgICAgICAgbmV3IFRvdXJuYW1hdGNoX0F1dG9jb21wbGV0ZShpbnB1dCwgZGF0YUNhbGxiYWNrKTtcclxuICAgIH1cclxuXHJcbiAgICB1Y2ZpcnN0KHMpIHtcclxuICAgICAgICBpZiAodHlwZW9mIHMgIT09ICdzdHJpbmcnKSByZXR1cm4gJyc7XHJcbiAgICAgICAgcmV0dXJuIHMuY2hhckF0KDApLnRvVXBwZXJDYXNlKCkgKyBzLnNsaWNlKDEpO1xyXG4gICAgfVxyXG5cclxuICAgIG9yZGluYWxfc3VmZml4KG51bWJlcikge1xyXG4gICAgICAgIGNvbnN0IHJlbWFpbmRlciA9IG51bWJlciAlIDEwMDtcclxuXHJcbiAgICAgICAgaWYgKChyZW1haW5kZXIgPCAxMSkgfHwgKHJlbWFpbmRlciA+IDEzKSkge1xyXG4gICAgICAgICAgICBzd2l0Y2ggKHJlbWFpbmRlciAlIDEwKSB7XHJcbiAgICAgICAgICAgICAgICBjYXNlIDE6IHJldHVybiAnc3QnO1xyXG4gICAgICAgICAgICAgICAgY2FzZSAyOiByZXR1cm4gJ25kJztcclxuICAgICAgICAgICAgICAgIGNhc2UgMzogcmV0dXJuICdyZCc7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuICd0aCc7XHJcbiAgICB9XHJcblxyXG4gICAgdGFicyhlbGVtZW50KSB7XHJcbiAgICAgICAgY29uc3QgdGFicyA9IGVsZW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLW5hdi1saW5rJyk7XHJcbiAgICAgICAgY29uc3QgcGFuZXMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tdGFiLXBhbmUnKTtcclxuICAgICAgICBjb25zdCBjbGVhckFjdGl2ZSA9ICgpID0+IHtcclxuICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbCh0YWJzLCAodGFiKSA9PiB7XHJcbiAgICAgICAgICAgICAgICB0YWIuY2xhc3NMaXN0LnJlbW92ZSgndHJuLW5hdi1hY3RpdmUnKTtcclxuICAgICAgICAgICAgICAgIHRhYi5hcmlhU2VsZWN0ZWQgPSBmYWxzZTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwocGFuZXMsIHBhbmUgPT4gcGFuZS5jbGFzc0xpc3QucmVtb3ZlKCd0cm4tdGFiLWFjdGl2ZScpKTtcclxuICAgICAgICB9O1xyXG4gICAgICAgIGNvbnN0IHNldEFjdGl2ZSA9ICh0YXJnZXRJZCkgPT4ge1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRUYWIgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdhW2hyZWY9XCIjJyArIHRhcmdldElkICsgJ1wiXS50cm4tbmF2LWxpbmsnKTtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0UGFuZUlkID0gdGFyZ2V0VGFiICYmIHRhcmdldFRhYi5kYXRhc2V0ICYmIHRhcmdldFRhYi5kYXRhc2V0LnRhcmdldCB8fCBmYWxzZTtcclxuXHJcbiAgICAgICAgICAgIGlmICh0YXJnZXRQYW5lSWQpIHtcclxuICAgICAgICAgICAgICAgIGNsZWFyQWN0aXZlKCk7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRUYWIuY2xhc3NMaXN0LmFkZCgndHJuLW5hdi1hY3RpdmUnKTtcclxuICAgICAgICAgICAgICAgIHRhcmdldFRhYi5hcmlhU2VsZWN0ZWQgPSB0cnVlO1xyXG5cclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHRhcmdldFBhbmVJZCkuY2xhc3NMaXN0LmFkZCgndHJuLXRhYi1hY3RpdmUnKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH07XHJcbiAgICAgICAgY29uc3QgdGFiQ2xpY2sgPSAoZXZlbnQpID0+IHtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0VGFiID0gZXZlbnQuY3VycmVudFRhcmdldDtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0UGFuZUlkID0gdGFyZ2V0VGFiICYmIHRhcmdldFRhYi5kYXRhc2V0ICYmIHRhcmdldFRhYi5kYXRhc2V0LnRhcmdldCB8fCBmYWxzZTtcclxuXHJcbiAgICAgICAgICAgIGlmICh0YXJnZXRQYW5lSWQpIHtcclxuICAgICAgICAgICAgICAgIHNldEFjdGl2ZSh0YXJnZXRQYW5lSWQpO1xyXG4gICAgICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH07XHJcblxyXG4gICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwodGFicywgKHRhYikgPT4ge1xyXG4gICAgICAgICAgICB0YWIuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0YWJDbGljayk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIGlmIChsb2NhdGlvbi5oYXNoKSB7XHJcbiAgICAgICAgICAgIHNldEFjdGl2ZShsb2NhdGlvbi5oYXNoLnN1YnN0cigxKSk7XHJcbiAgICAgICAgfSBlbHNlIGlmICh0YWJzLmxlbmd0aCA+IDApIHtcclxuICAgICAgICAgICAgc2V0QWN0aXZlKHRhYnNbMF0uZGF0YXNldC50YXJnZXQpO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbn1cclxuXHJcbi8vdHJuLmluaXRpYWxpemUoKTtcclxuaWYgKCF3aW5kb3cudHJuX29ial9pbnN0YW5jZSkge1xyXG4gICAgd2luZG93LnRybl9vYmpfaW5zdGFuY2UgPSBuZXcgVG91cm5hbWF0Y2goKTtcclxuXHJcbiAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIGZ1bmN0aW9uICgpIHtcclxuXHJcbiAgICAgICAgY29uc3QgdGFiVmlld3MgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tbmF2Jyk7XHJcblxyXG4gICAgICAgIEFycmF5LmZyb20odGFiVmlld3MpLmZvckVhY2goKHRhYikgPT4ge1xyXG4gICAgICAgICAgICB0cm4udGFicyh0YWIpO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICBjb25zdCBkcm9wZG93bnMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tZHJvcGRvd24tdG9nZ2xlJyk7XHJcbiAgICAgICAgY29uc3QgaGFuZGxlRHJvcGRvd25DbG9zZSA9ICgpID0+IHtcclxuICAgICAgICAgICAgQXJyYXkuZnJvbShkcm9wZG93bnMpLmZvckVhY2goKGRyb3Bkb3duKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBkcm9wZG93bi5uZXh0RWxlbWVudFNpYmxpbmcuY2xhc3NMaXN0LnJlbW92ZSgndHJuLXNob3cnKTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIGRvY3VtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBoYW5kbGVEcm9wZG93bkNsb3NlLCBmYWxzZSk7XHJcbiAgICAgICAgfTtcclxuXHJcbiAgICAgICAgQXJyYXkuZnJvbShkcm9wZG93bnMpLmZvckVhY2goKGRyb3Bkb3duKSA9PiB7XHJcbiAgICAgICAgICAgIGRyb3Bkb3duLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24oZSkge1xyXG4gICAgICAgICAgICAgICAgZS5zdG9wUHJvcGFnYXRpb24oKTtcclxuICAgICAgICAgICAgICAgIHRoaXMubmV4dEVsZW1lbnRTaWJsaW5nLmNsYXNzTGlzdC5hZGQoJ3Rybi1zaG93Jyk7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgaGFuZGxlRHJvcGRvd25DbG9zZSwgZmFsc2UpO1xyXG4gICAgICAgICAgICB9LCBmYWxzZSk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgfSwgZmFsc2UpO1xyXG59XHJcbmV4cG9ydCBsZXQgdHJuID0gd2luZG93LnRybl9vYmpfaW5zdGFuY2U7XHJcblxyXG5jbGFzcyBUb3VybmFtYXRjaF9BdXRvY29tcGxldGUge1xyXG5cclxuICAgIC8vIGN1cnJlbnRGb2N1cztcclxuICAgIC8vXHJcbiAgICAvLyBuYW1lSW5wdXQ7XHJcbiAgICAvL1xyXG4gICAgLy8gc2VsZjtcclxuXHJcbiAgICBjb25zdHJ1Y3RvcihpbnB1dCwgZGF0YUNhbGxiYWNrKSB7XHJcbiAgICAgICAgLy8gdGhpcy5zZWxmID0gdGhpcztcclxuICAgICAgICB0aGlzLm5hbWVJbnB1dCA9IGlucHV0O1xyXG5cclxuICAgICAgICB0aGlzLm5hbWVJbnB1dC5hZGRFdmVudExpc3RlbmVyKFwiaW5wdXRcIiwgKCkgPT4ge1xyXG4gICAgICAgICAgICBsZXQgYSwgYiwgaSwgdmFsID0gdGhpcy5uYW1lSW5wdXQudmFsdWU7Ly90aGlzLnZhbHVlO1xyXG4gICAgICAgICAgICBsZXQgcGFyZW50ID0gdGhpcy5uYW1lSW5wdXQucGFyZW50Tm9kZTsvL3RoaXMucGFyZW50Tm9kZTtcclxuXHJcbiAgICAgICAgICAgIC8vIGxldCBwID0gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xyXG4gICAgICAgICAgICAvLyAgICAgLyogbmVlZCB0byBxdWVyeSBzZXJ2ZXIgZm9yIG5hbWVzIGhlcmUuICovXHJcbiAgICAgICAgICAgIC8vICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIub3BlbignR0VUJywgb3B0aW9ucy5hcGlfdXJsICsgJ3BsYXllcnMvP3NlYXJjaD0nICsgdmFsICsgJyZwZXJfcGFnZT01Jyk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ1gtV1AtTm9uY2UnLCBvcHRpb25zLnJlc3Rfbm9uY2UpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgLy8gICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAwKSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgICAgIC8vIHJlc29sdmUoSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpLm1hcCgocGxheWVyKSA9PiB7cmV0dXJuIHsgJ3ZhbHVlJzogcGxheWVyLmlkLCAndGV4dCc6IHBsYXllci5uYW1lIH07fSkpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgICAgICByZXNvbHZlKEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKS5tYXAoKHBsYXllcikgPT4ge3JldHVybiBwbGF5ZXIubmFtZTt9KSk7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgLy8gICAgICAgICAgICAgcmVqZWN0KCk7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgfVxyXG4gICAgICAgICAgICAvLyAgICAgfTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5zZW5kKCk7XHJcbiAgICAgICAgICAgIC8vIH0pO1xyXG4gICAgICAgICAgICBkYXRhQ2FsbGJhY2sodmFsKS50aGVuKChkYXRhKSA9PiB7Ly9wLnRoZW4oKGRhdGEpID0+IHtcclxuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKGRhdGEpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qY2xvc2UgYW55IGFscmVhZHkgb3BlbiBsaXN0cyBvZiBhdXRvLWNvbXBsZXRlZCB2YWx1ZXMqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5jbG9zZUFsbExpc3RzKCk7XHJcbiAgICAgICAgICAgICAgICBpZiAoIXZhbCkgeyByZXR1cm4gZmFsc2U7fVxyXG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50Rm9jdXMgPSAtMTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmNyZWF0ZSBhIERJViBlbGVtZW50IHRoYXQgd2lsbCBjb250YWluIHRoZSBpdGVtcyAodmFsdWVzKToqL1xyXG4gICAgICAgICAgICAgICAgYSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJESVZcIik7XHJcbiAgICAgICAgICAgICAgICBhLnNldEF0dHJpYnV0ZShcImlkXCIsIHRoaXMubmFtZUlucHV0LmlkICsgXCItYXV0by1jb21wbGV0ZS1saXN0XCIpO1xyXG4gICAgICAgICAgICAgICAgYS5zZXRBdHRyaWJ1dGUoXCJjbGFzc1wiLCBcInRybi1hdXRvLWNvbXBsZXRlLWl0ZW1zXCIpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qYXBwZW5kIHRoZSBESVYgZWxlbWVudCBhcyBhIGNoaWxkIG9mIHRoZSBhdXRvLWNvbXBsZXRlIGNvbnRhaW5lcjoqL1xyXG4gICAgICAgICAgICAgICAgcGFyZW50LmFwcGVuZENoaWxkKGEpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qZm9yIGVhY2ggaXRlbSBpbiB0aGUgYXJyYXkuLi4qL1xyXG4gICAgICAgICAgICAgICAgZm9yIChpID0gMDsgaSA8IGRhdGEubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgICAgICAgICBsZXQgdGV4dCwgdmFsdWU7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIC8qIFdoaWNoIGZvcm1hdCBkaWQgdGhleSBnaXZlIHVzLiAqL1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgZGF0YVtpXSA9PT0gJ29iamVjdCcpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdGV4dCA9IGRhdGFbaV1bJ3RleHQnXTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSBkYXRhW2ldWyd2YWx1ZSddO1xyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHRleHQgPSBkYXRhW2ldO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB2YWx1ZSA9IGRhdGFbaV07XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgICAgICAvKmNoZWNrIGlmIHRoZSBpdGVtIHN0YXJ0cyB3aXRoIHRoZSBzYW1lIGxldHRlcnMgYXMgdGhlIHRleHQgZmllbGQgdmFsdWU6Ki9cclxuICAgICAgICAgICAgICAgICAgICBpZiAodGV4dC5zdWJzdHIoMCwgdmFsLmxlbmd0aCkudG9VcHBlckNhc2UoKSA9PT0gdmFsLnRvVXBwZXJDYXNlKCkpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgLypjcmVhdGUgYSBESVYgZWxlbWVudCBmb3IgZWFjaCBtYXRjaGluZyBlbGVtZW50OiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiRElWXCIpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKm1ha2UgdGhlIG1hdGNoaW5nIGxldHRlcnMgYm9sZDoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmlubmVySFRNTCA9IFwiPHN0cm9uZz5cIiArIHRleHQuc3Vic3RyKDAsIHZhbC5sZW5ndGgpICsgXCI8L3N0cm9uZz5cIjtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgKz0gdGV4dC5zdWJzdHIodmFsLmxlbmd0aCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKmluc2VydCBhIGlucHV0IGZpZWxkIHRoYXQgd2lsbCBob2xkIHRoZSBjdXJyZW50IGFycmF5IGl0ZW0ncyB2YWx1ZToqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmlubmVySFRNTCArPSBcIjxpbnB1dCB0eXBlPSdoaWRkZW4nIHZhbHVlPSdcIiArIHZhbHVlICsgXCInPlwiO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5kYXRhc2V0LnZhbHVlID0gdmFsdWU7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuZGF0YXNldC50ZXh0ID0gdGV4dDtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHdoZW4gc29tZW9uZSBjbGlja3Mgb24gdGhlIGl0ZW0gdmFsdWUgKERJViBlbGVtZW50KToqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2coYGl0ZW0gY2xpY2tlZCB3aXRoIHZhbHVlICR7ZS5jdXJyZW50VGFyZ2V0LmRhdGFzZXQudmFsdWV9YCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLyogaW5zZXJ0IHRoZSB2YWx1ZSBmb3IgdGhlIGF1dG9jb21wbGV0ZSB0ZXh0IGZpZWxkOiAqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5uYW1lSW5wdXQudmFsdWUgPSBlLmN1cnJlbnRUYXJnZXQuZGF0YXNldC50ZXh0O1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5uYW1lSW5wdXQuZGF0YXNldC5zZWxlY3RlZElkID0gZS5jdXJyZW50VGFyZ2V0LmRhdGFzZXQudmFsdWU7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLyogY2xvc2UgdGhlIGxpc3Qgb2YgYXV0b2NvbXBsZXRlZCB2YWx1ZXMsIChvciBhbnkgb3RoZXIgb3BlbiBsaXN0cyBvZiBhdXRvY29tcGxldGVkIHZhbHVlczoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5jbG9zZUFsbExpc3RzKCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5uYW1lSW5wdXQuZGlzcGF0Y2hFdmVudChuZXcgRXZlbnQoJ2NoYW5nZScpKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGEuYXBwZW5kQ2hpbGQoYik7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgLypleGVjdXRlIGEgZnVuY3Rpb24gcHJlc3NlcyBhIGtleSBvbiB0aGUga2V5Ym9hcmQ6Ki9cclxuICAgICAgICB0aGlzLm5hbWVJbnB1dC5hZGRFdmVudExpc3RlbmVyKFwia2V5ZG93blwiLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICBsZXQgeCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHRoaXMubmFtZUlucHV0LmlkICsgXCItYXV0by1jb21wbGV0ZS1saXN0XCIpO1xyXG4gICAgICAgICAgICBpZiAoeCkgeCA9IHguZ2V0RWxlbWVudHNCeVRhZ05hbWUoXCJkaXZcIik7XHJcbiAgICAgICAgICAgIGlmIChlLmtleUNvZGUgPT09IDQwKSB7XHJcbiAgICAgICAgICAgICAgICAvKklmIHRoZSBhcnJvdyBET1dOIGtleSBpcyBwcmVzc2VkLFxyXG4gICAgICAgICAgICAgICAgIGluY3JlYXNlIHRoZSBjdXJyZW50Rm9jdXMgdmFyaWFibGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudEZvY3VzKys7XHJcbiAgICAgICAgICAgICAgICAvKmFuZCBhbmQgbWFrZSB0aGUgY3VycmVudCBpdGVtIG1vcmUgdmlzaWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5hZGRBY3RpdmUoeCk7XHJcbiAgICAgICAgICAgIH0gZWxzZSBpZiAoZS5rZXlDb2RlID09PSAzOCkgeyAvL3VwXHJcbiAgICAgICAgICAgICAgICAvKklmIHRoZSBhcnJvdyBVUCBrZXkgaXMgcHJlc3NlZCxcclxuICAgICAgICAgICAgICAgICBkZWNyZWFzZSB0aGUgY3VycmVudEZvY3VzIHZhcmlhYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmN1cnJlbnRGb2N1cy0tO1xyXG4gICAgICAgICAgICAgICAgLyphbmQgYW5kIG1ha2UgdGhlIGN1cnJlbnQgaXRlbSBtb3JlIHZpc2libGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuYWRkQWN0aXZlKHgpO1xyXG4gICAgICAgICAgICB9IGVsc2UgaWYgKGUua2V5Q29kZSA9PT0gMTMpIHtcclxuICAgICAgICAgICAgICAgIC8qSWYgdGhlIEVOVEVSIGtleSBpcyBwcmVzc2VkLCBwcmV2ZW50IHRoZSBmb3JtIGZyb20gYmVpbmcgc3VibWl0dGVkLCovXHJcbiAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgICAgICBpZiAodGhpcy5jdXJyZW50Rm9jdXMgPiAtMSkge1xyXG4gICAgICAgICAgICAgICAgICAgIC8qYW5kIHNpbXVsYXRlIGEgY2xpY2sgb24gdGhlIFwiYWN0aXZlXCIgaXRlbToqL1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh4KSB4W3RoaXMuY3VycmVudEZvY3VzXS5jbGljaygpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHdoZW4gc29tZW9uZSBjbGlja3MgaW4gdGhlIGRvY3VtZW50OiovXHJcbiAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIChlKSA9PiB7XHJcbiAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cyhlLnRhcmdldCk7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9XHJcblxyXG4gICAgYWRkQWN0aXZlKHgpIHtcclxuICAgICAgICAvKmEgZnVuY3Rpb24gdG8gY2xhc3NpZnkgYW4gaXRlbSBhcyBcImFjdGl2ZVwiOiovXHJcbiAgICAgICAgaWYgKCF4KSByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgLypzdGFydCBieSByZW1vdmluZyB0aGUgXCJhY3RpdmVcIiBjbGFzcyBvbiBhbGwgaXRlbXM6Ki9cclxuICAgICAgICB0aGlzLnJlbW92ZUFjdGl2ZSh4KTtcclxuICAgICAgICBpZiAodGhpcy5jdXJyZW50Rm9jdXMgPj0geC5sZW5ndGgpIHRoaXMuY3VycmVudEZvY3VzID0gMDtcclxuICAgICAgICBpZiAodGhpcy5jdXJyZW50Rm9jdXMgPCAwKSB0aGlzLmN1cnJlbnRGb2N1cyA9ICh4Lmxlbmd0aCAtIDEpO1xyXG4gICAgICAgIC8qYWRkIGNsYXNzIFwiYXV0b2NvbXBsZXRlLWFjdGl2ZVwiOiovXHJcbiAgICAgICAgeFt0aGlzLmN1cnJlbnRGb2N1c10uY2xhc3NMaXN0LmFkZChcInRybi1hdXRvLWNvbXBsZXRlLWFjdGl2ZVwiKTtcclxuICAgIH1cclxuXHJcbiAgICByZW1vdmVBY3RpdmUoeCkge1xyXG4gICAgICAgIC8qYSBmdW5jdGlvbiB0byByZW1vdmUgdGhlIFwiYWN0aXZlXCIgY2xhc3MgZnJvbSBhbGwgYXV0b2NvbXBsZXRlIGl0ZW1zOiovXHJcbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCB4Lmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgIHhbaV0uY2xhc3NMaXN0LnJlbW92ZShcInRybi1hdXRvLWNvbXBsZXRlLWFjdGl2ZVwiKTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcblxyXG4gICAgY2xvc2VBbGxMaXN0cyhlbGVtZW50KSB7XHJcbiAgICAgICAgY29uc29sZS5sb2coXCJjbG9zZSBhbGwgbGlzdHNcIik7XHJcbiAgICAgICAgLypjbG9zZSBhbGwgYXV0b2NvbXBsZXRlIGxpc3RzIGluIHRoZSBkb2N1bWVudCxcclxuICAgICAgICAgZXhjZXB0IHRoZSBvbmUgcGFzc2VkIGFzIGFuIGFyZ3VtZW50OiovXHJcbiAgICAgICAgbGV0IHggPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKFwidHJuLWF1dG8tY29tcGxldGUtaXRlbXNcIik7XHJcbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCB4Lmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgIGlmIChlbGVtZW50ICE9PSB4W2ldICYmIGVsZW1lbnQgIT09IHRoaXMubmFtZUlucHV0KSB7XHJcbiAgICAgICAgICAgICAgICB4W2ldLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoeFtpXSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICB9XHJcbn1cclxuXHJcbi8vIEZpcnN0LCBjaGVja3MgaWYgaXQgaXNuJ3QgaW1wbGVtZW50ZWQgeWV0LlxyXG5pZiAoIVN0cmluZy5wcm90b3R5cGUuZm9ybWF0KSB7XHJcbiAgICBTdHJpbmcucHJvdG90eXBlLmZvcm1hdCA9IGZ1bmN0aW9uKCkge1xyXG4gICAgICAgIGNvbnN0IGFyZ3MgPSBhcmd1bWVudHM7XHJcbiAgICAgICAgcmV0dXJuIHRoaXMucmVwbGFjZSgveyhcXGQrKX0vZywgZnVuY3Rpb24obWF0Y2gsIG51bWJlcikge1xyXG4gICAgICAgICAgICByZXR1cm4gdHlwZW9mIGFyZ3NbbnVtYmVyXSAhPT0gJ3VuZGVmaW5lZCdcclxuICAgICAgICAgICAgICAgID8gYXJnc1tudW1iZXJdXHJcbiAgICAgICAgICAgICAgICA6IG1hdGNoXHJcbiAgICAgICAgICAgICAgICA7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9O1xyXG59Il0sInNvdXJjZVJvb3QiOiIifQ==