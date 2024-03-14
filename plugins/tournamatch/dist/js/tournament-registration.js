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
/******/ 	return __webpack_require__(__webpack_require__.s = 54);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/admin/tournament-registration.js":
/*!*************************************************!*\
  !*** ./src/js/admin/tournament-registration.js ***!
  \*************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../tournamatch.js */ "./src/js/tournamatch.js");
/**
 * Admin manage tournament bulk registration page.
 *
 * @link       https://www.tournamatch.com
 * @since      3.17.0
 *
 * @package    Tournamatch
 *
 */


(function ($) {
  'use strict';

  window.addEventListener('load', function () {
    var options = trn_tournament_registration_options; // intialize auto complete

    $.autocomplete(document.getElementById('competitor_id'), function (val) {
      return new Promise(function (resolve, reject) {
        /* need to query server for names here. */
        var xhr = new XMLHttpRequest();
        xhr.open('GET', options.api_url + 'players/?search=' + val + '&per_page=5');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

        xhr.onload = function () {
          if (xhr.status === 200) {
            // resolve(JSON.parse(xhr.response).map((player) => {return { 'value': player.id, 'text': player.name };}));
            resolve(JSON.parse(xhr.response).map(function (player) {
              return player.name;
            }));
          } else {
            reject();
          }
        };

        xhr.send();
      });
    }); // toggle new or select

    function toggleTeams() {
      var teamSelection = document.getElementById('new_or_existing');
      var selectedValue = teamSelection.options[teamSelection.selectedIndex].value;

      if (selectedValue === 'new') {
        document.getElementById('tag_row').style.display = 'table-row';
        document.getElementById('team_tag').required = true;
        document.getElementById('name_row').style.display = 'table-row';
        document.getElementById('team_name').required = true;
        document.getElementById('existing_row').style.display = 'none';
        document.getElementById('existing_team').required = false;
      } else {
        document.getElementById('tag_row').style.display = 'none';
        document.getElementById('team_tag').required = false;
        document.getElementById('name_row').style.display = 'none';
        document.getElementById('team_name').required = false;
        document.getElementById('existing_row').style.display = 'table-row';
        document.getElementById('existing_team').required = true;
      }
    } // new or existing drop down


    if (options.competition === 'teams') {
      var teamSelection = document.getElementById('new_or_existing');
      teamSelection.addEventListener('change', function (event) {
        event.preventDefault();
        toggleTeams();
      }, false);
      toggleTeams();
      document.getElementById('competitor_id').addEventListener('change', function (e) {
        var _this = this;

        console.log("value changed to ".concat(this.value));
        var p = new Promise(function (resolve, reject) {
          var xhr = new XMLHttpRequest();
          xhr.open('GET', options.api_url + 'players/?search=' + _this.value);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

          xhr.onload = function () {
            console.log(xhr.response);

            if (xhr.status === 200) {
              var players = JSON.parse(xhr.response);

              if (players.length > 0) {
                resolve(players[0]['user_id']);
                document.getElementById('trn-tournament-register-response').innerHTML = "";
              } else {
                document.getElementById('trn-tournament-register-response').innerHTML = "<p class=\"notice notice-error\"><strong>".concat(options.language.failure, ":</strong> ").concat(options.language.no_competitor, "</p>");
              }
            } else {
              reject();
            }
          };

          xhr.send();
        });
        p.then(function (user_id) {
          getTeams(user_id);
        });
      }, false);
    } // get teams for single player


    function getTeams(user_id) {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', options.api_url + "players/".concat(user_id, "/teams"));
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        console.log(xhr);
        var content = "";

        if (xhr.status === 200) {
          var teams = JSON.parse(xhr.response);

          if (teams !== null && teams.length > 0) {
            for (var i = 0; i < teams.length; i++) {
              var team = teams[i];
              content += "<option value=\"".concat(team.team_id, "\">").concat(team.name, "</option>");
            }
          } else {
            content += "<option value=\"-1\">(".concat(options.language.zero_teams, ")</option>");
          }
        } else {
          content += "<option value=\"-1\">(".concat(options.language.zero_teams, ")</option>");
        }

        document.getElementById('existing_team').innerHTML = content;
      };

      xhr.send();
    }

    function registerCompetitor(competitionId, competitorId, competitorType) {
      console.log("registering ".concat(competitorType, " with id ").concat(competitorId, " to competition with id ").concat(competitionId));
      var xhr = new XMLHttpRequest();
      xhr.open('POST', options.api_url + 'tournament-registrations');
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        console.log(xhr);

        if (xhr.status === 201) {
          document.getElementById('trn-tournament-register-response').innerHTML = "<p class=\"notice notice-success\"><strong>".concat(options.language.success, ":</strong> ").concat(options.language.success_message, "</p>");
          document.getElementById('trn-tournament-register-form').reset();
        } else {
          document.getElementById('trn-tournament-register-response').innerHTML = "<p class=\"notice notice-error\"><strong>".concat(options.language.failure, ":</strong> ").concat(JSON.parse(xhr.response).message, "</p>");
        }
      };

      xhr.send($.param({
        tournament_id: competitionId,
        competitor_id: competitorId,
        competitor_type: competitorType
      }));
    }

    document.getElementById('trn-tournament-register-form').addEventListener('submit', function (event) {
      event.preventDefault();
      var p = new Promise(function (resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', options.api_url + 'players/?search=' + document.getElementById('competitor_id').value);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

        xhr.onload = function () {
          console.log(xhr.response);

          if (xhr.status === 200) {
            var players = JSON.parse(xhr.response);

            if (players.length > 0) {
              resolve(players[0]['user_id']);
              document.getElementById('trn-tournament-register-response').innerHTML = "";
            } else {
              document.getElementById('trn-tournament-register-response').innerHTML = "<p class=\"notice notice-error\"><strong>".concat(options.language.failure, ":</strong> ").concat(options.language.no_competitor, "</p>");
            }
          } else {
            reject();
          }
        };

        xhr.send();
      });
      p.then(function (userId) {
        if (options.competition === 'teams') {
          var _teamSelection = document.getElementById('new_or_existing');

          if (_teamSelection.value === 'new') {
            var q = new Promise(function (resolve, reject) {
              var xhr = new XMLHttpRequest();
              xhr.open('POST', options.api_url + 'teams');
              xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
              xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

              xhr.onload = function () {
                if (xhr.status === 200) {
                  resolve(JSON.parse(xhr.response).data.team_id);
                } else {
                  reject(xhr);
                }
              };

              xhr.send($.param({
                user_id: userId,
                name: document.getElementById('team_name').value,
                tag: document.getElementById('team_tag').value
              }));
            });
            q.then(function (teamId) {
              registerCompetitor(options.tournament_id, teamId, 'teams');
            });
          } else {
            registerCompetitor(options.tournament_id, document.getElementById('existing_team').value, 'teams');
          }
        } else {
          registerCompetitor(options.tournament_id, userId, 'players');
        }
      });
      console.log('submitted');
    }, false);
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

/***/ 54:
/*!*******************************************************!*\
  !*** multi ./src/js/admin/tournament-registration.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\admin\tournament-registration.js */"./src/js/admin/tournament-registration.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL2FkbWluL3RvdXJuYW1lbnQtcmVnaXN0cmF0aW9uLmpzIiwid2VicGFjazovLy8uL3NyYy9qcy90b3VybmFtYXRjaC5qcyJdLCJuYW1lcyI6WyIkIiwid2luZG93IiwiYWRkRXZlbnRMaXN0ZW5lciIsIm9wdGlvbnMiLCJ0cm5fdG91cm5hbWVudF9yZWdpc3RyYXRpb25fb3B0aW9ucyIsImF1dG9jb21wbGV0ZSIsImRvY3VtZW50IiwiZ2V0RWxlbWVudEJ5SWQiLCJ2YWwiLCJQcm9taXNlIiwicmVzb2x2ZSIsInJlamVjdCIsInhociIsIlhNTEh0dHBSZXF1ZXN0Iiwib3BlbiIsImFwaV91cmwiLCJzZXRSZXF1ZXN0SGVhZGVyIiwicmVzdF9ub25jZSIsIm9ubG9hZCIsInN0YXR1cyIsIkpTT04iLCJwYXJzZSIsInJlc3BvbnNlIiwibWFwIiwicGxheWVyIiwibmFtZSIsInNlbmQiLCJ0b2dnbGVUZWFtcyIsInRlYW1TZWxlY3Rpb24iLCJzZWxlY3RlZFZhbHVlIiwic2VsZWN0ZWRJbmRleCIsInZhbHVlIiwic3R5bGUiLCJkaXNwbGF5IiwicmVxdWlyZWQiLCJjb21wZXRpdGlvbiIsImV2ZW50IiwicHJldmVudERlZmF1bHQiLCJlIiwiY29uc29sZSIsImxvZyIsInAiLCJwbGF5ZXJzIiwibGVuZ3RoIiwiaW5uZXJIVE1MIiwibGFuZ3VhZ2UiLCJmYWlsdXJlIiwibm9fY29tcGV0aXRvciIsInRoZW4iLCJ1c2VyX2lkIiwiZ2V0VGVhbXMiLCJjb250ZW50IiwidGVhbXMiLCJpIiwidGVhbSIsInRlYW1faWQiLCJ6ZXJvX3RlYW1zIiwicmVnaXN0ZXJDb21wZXRpdG9yIiwiY29tcGV0aXRpb25JZCIsImNvbXBldGl0b3JJZCIsImNvbXBldGl0b3JUeXBlIiwic3VjY2VzcyIsInN1Y2Nlc3NfbWVzc2FnZSIsInJlc2V0IiwibWVzc2FnZSIsInBhcmFtIiwidG91cm5hbWVudF9pZCIsImNvbXBldGl0b3JfaWQiLCJjb21wZXRpdG9yX3R5cGUiLCJ1c2VySWQiLCJxIiwiZGF0YSIsInRhZyIsInRlYW1JZCIsInRybiIsIlRvdXJuYW1hdGNoIiwiZXZlbnRzIiwib2JqZWN0IiwicHJlZml4Iiwic3RyIiwicHJvcCIsImhhc093blByb3BlcnR5IiwiayIsInYiLCJwdXNoIiwiZW5jb2RlVVJJQ29tcG9uZW50Iiwiam9pbiIsImV2ZW50TmFtZSIsIkV2ZW50VGFyZ2V0IiwiaW5wdXQiLCJkYXRhQ2FsbGJhY2siLCJUb3VybmFtYXRjaF9BdXRvY29tcGxldGUiLCJzIiwiY2hhckF0IiwidG9VcHBlckNhc2UiLCJzbGljZSIsIm51bWJlciIsInJlbWFpbmRlciIsImVsZW1lbnQiLCJ0YWJzIiwiZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSIsInBhbmVzIiwiY2xlYXJBY3RpdmUiLCJBcnJheSIsInByb3RvdHlwZSIsImZvckVhY2giLCJjYWxsIiwidGFiIiwiY2xhc3NMaXN0IiwicmVtb3ZlIiwiYXJpYVNlbGVjdGVkIiwicGFuZSIsInNldEFjdGl2ZSIsInRhcmdldElkIiwidGFyZ2V0VGFiIiwicXVlcnlTZWxlY3RvciIsInRhcmdldFBhbmVJZCIsImRhdGFzZXQiLCJ0YXJnZXQiLCJhZGQiLCJ0YWJDbGljayIsImN1cnJlbnRUYXJnZXQiLCJsb2NhdGlvbiIsImhhc2giLCJzdWJzdHIiLCJ0cm5fb2JqX2luc3RhbmNlIiwidGFiVmlld3MiLCJmcm9tIiwiZHJvcGRvd25zIiwiaGFuZGxlRHJvcGRvd25DbG9zZSIsImRyb3Bkb3duIiwibmV4dEVsZW1lbnRTaWJsaW5nIiwicmVtb3ZlRXZlbnRMaXN0ZW5lciIsInN0b3BQcm9wYWdhdGlvbiIsIm5hbWVJbnB1dCIsImEiLCJiIiwicGFyZW50IiwicGFyZW50Tm9kZSIsImNsb3NlQWxsTGlzdHMiLCJjdXJyZW50Rm9jdXMiLCJjcmVhdGVFbGVtZW50Iiwic2V0QXR0cmlidXRlIiwiaWQiLCJhcHBlbmRDaGlsZCIsInRleHQiLCJzZWxlY3RlZElkIiwiZGlzcGF0Y2hFdmVudCIsIkV2ZW50IiwieCIsImdldEVsZW1lbnRzQnlUYWdOYW1lIiwia2V5Q29kZSIsImFkZEFjdGl2ZSIsImNsaWNrIiwicmVtb3ZlQWN0aXZlIiwicmVtb3ZlQ2hpbGQiLCJTdHJpbmciLCJmb3JtYXQiLCJhcmdzIiwiYXJndW1lbnRzIiwicmVwbGFjZSIsIm1hdGNoIl0sIm1hcHBpbmdzIjoiO1FBQUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7OztRQUdBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSwwQ0FBMEMsZ0NBQWdDO1FBQzFFO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0Esd0RBQXdELGtCQUFrQjtRQUMxRTtRQUNBLGlEQUFpRCxjQUFjO1FBQy9EOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQSx5Q0FBeUMsaUNBQWlDO1FBQzFFLGdIQUFnSCxtQkFBbUIsRUFBRTtRQUNySTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLDJCQUEyQiwwQkFBMEIsRUFBRTtRQUN2RCxpQ0FBaUMsZUFBZTtRQUNoRDtRQUNBO1FBQ0E7O1FBRUE7UUFDQSxzREFBc0QsK0RBQStEOztRQUVySDtRQUNBOzs7UUFHQTtRQUNBOzs7Ozs7Ozs7Ozs7O0FDbEZBO0FBQUE7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxDQUFDLFVBQVVBLENBQVYsRUFBYTtBQUNWOztBQUVBQyxRQUFNLENBQUNDLGdCQUFQLENBQXdCLE1BQXhCLEVBQWdDLFlBQVk7QUFDeEMsUUFBSUMsT0FBTyxHQUFHQyxtQ0FBZCxDQUR3QyxDQUd4Qzs7QUFDQUosS0FBQyxDQUFDSyxZQUFGLENBQWVDLFFBQVEsQ0FBQ0Ms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LFFBQVEsQ0FBQzhGLHNCQUFULENBQWdDLHlCQUFoQyxDQUFSOztBQUNBLFdBQUssSUFBSS9DLENBQUMsR0FBRyxDQUFiLEVBQWdCQSxDQUFDLEdBQUdnRyxDQUFDLENBQUMxRyxNQUF0QixFQUE4QlUsQ0FBQyxFQUEvQixFQUFtQztBQUMvQixZQUFJNkMsT0FBTyxLQUFLbUQsQ0FBQyxDQUFDaEcsQ0FBRCxDQUFiLElBQW9CNkMsT0FBTyxLQUFLLEtBQUtvQyxTQUF6QyxFQUFvRDtBQUNoRGUsV0FBQyxDQUFDaEcsQ0FBRCxDQUFELENBQUtxRixVQUFMLENBQWdCaUIsV0FBaEIsQ0FBNEJOLENBQUMsQ0FBQ2hHLENBQUQsQ0FBN0I7QUFDSDtBQUNKO0FBQ0o7Ozs7S0FHTDs7O0FBQ0EsSUFBSSxDQUFDdUcsTUFBTSxDQUFDcEQsU0FBUCxDQUFpQnFELE1BQXRCLEVBQThCO0FBQzFCRCxRQUFNLENBQUNwRCxTQUFQLENBQWlCcUQsTUFBakIsR0FBMEIsWUFBVztBQUNqQyxRQUFNQyxJQUFJLEdBQUdDLFNBQWI7QUFDQSxXQUFPLEtBQUtDLE9BQUwsQ0FBYSxVQUFiLEVBQXlCLFVBQVNDLEtBQVQsRUFBZ0JqRSxNQUFoQixFQUF3QjtBQUNwRCxhQUFPLE9BQU84RCxJQUFJLENBQUM5RCxNQUFELENBQVgsS0FBd0IsV0FBeEIsR0FDRDhELElBQUksQ0FBQzlELE1BQUQsQ0FESCxHQUVEaUUsS0FGTjtBQUlILEtBTE0sQ0FBUDtBQU1ILEdBUkQ7QUFTSCxDIiwiZmlsZSI6InRvdXJuYW1lbnQtcmVnaXN0cmF0aW9uLmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7IGVudW1lcmFibGU6IHRydWUsIGdldDogZ2V0dGVyIH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBkZWZpbmUgX19lc01vZHVsZSBvbiBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIgPSBmdW5jdGlvbihleHBvcnRzKSB7XG4gXHRcdGlmKHR5cGVvZiBTeW1ib2wgIT09ICd1bmRlZmluZWQnICYmIFN5bWJvbC50b1N0cmluZ1RhZykge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBTeW1ib2wudG9TdHJpbmdUYWcsIHsgdmFsdWU6ICdNb2R1bGUnIH0pO1xuIFx0XHR9XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCAnX19lc01vZHVsZScsIHsgdmFsdWU6IHRydWUgfSk7XG4gXHR9O1xuXG4gXHQvLyBjcmVhdGUgYSBmYWtlIG5hbWVzcGFjZSBvYmplY3RcbiBcdC8vIG1vZGUgJiAxOiB2YWx1ZSBpcyBhIG1vZHVsZSBpZCwgcmVxdWlyZSBpdFxuIFx0Ly8gbW9kZSAmIDI6IG1lcmdlIGFsbCBwcm9wZXJ0aWVzIG9mIHZhbHVlIGludG8gdGhlIG5zXG4gXHQvLyBtb2RlICYgNDogcmV0dXJuIHZhbHVlIHdoZW4gYWxyZWFkeSBucyBvYmplY3RcbiBcdC8vIG1vZGUgJiA4fDE6IGJlaGF2ZSBsaWtlIHJlcXVpcmVcbiBcdF9fd2VicGFja19yZXF1aXJlX18udCA9IGZ1bmN0aW9uKHZhbHVlLCBtb2RlKSB7XG4gXHRcdGlmKG1vZGUgJiAxKSB2YWx1ZSA9IF9fd2VicGFja19yZXF1aXJlX18odmFsdWUpO1xuIFx0XHRpZihtb2RlICYgOCkgcmV0dXJuIHZhbHVlO1xuIFx0XHRpZigobW9kZSAmIDQpICYmIHR5cGVvZiB2YWx1ZSA9PT0gJ29iamVjdCcgJiYgdmFsdWUgJiYgdmFsdWUuX19lc01vZHVsZSkgcmV0dXJuIHZhbHVlO1xuIFx0XHR2YXIgbnMgPSBPYmplY3QuY3JlYXRlKG51bGwpO1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIobnMpO1xuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkobnMsICdkZWZhdWx0JywgeyBlbnVtZXJhYmxlOiB0cnVlLCB2YWx1ZTogdmFsdWUgfSk7XG4gXHRcdGlmKG1vZGUgJiAyICYmIHR5cGVvZiB2YWx1ZSAhPSAnc3RyaW5nJykgZm9yKHZhciBrZXkgaW4gdmFsdWUpIF9fd2VicGFja19yZXF1aXJlX18uZChucywga2V5LCBmdW5jdGlvbihrZXkpIHsgcmV0dXJuIHZhbHVlW2tleV07IH0uYmluZChudWxsLCBrZXkpKTtcbiBcdFx0cmV0dXJuIG5zO1xuIFx0fTtcblxuIFx0Ly8gZ2V0RGVmYXVsdEV4cG9ydCBmdW5jdGlvbiBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIG5vbi1oYXJtb255IG1vZHVsZXNcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubiA9IGZ1bmN0aW9uKG1vZHVsZSkge1xuIFx0XHR2YXIgZ2V0dGVyID0gbW9kdWxlICYmIG1vZHVsZS5fX2VzTW9kdWxlID9cbiBcdFx0XHRmdW5jdGlvbiBnZXREZWZhdWx0KCkgeyByZXR1cm4gbW9kdWxlWydkZWZhdWx0J107IH0gOlxuIFx0XHRcdGZ1bmN0aW9uIGdldE1vZHVsZUV4cG9ydHMoKSB7IHJldHVybiBtb2R1bGU7IH07XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18uZChnZXR0ZXIsICdhJywgZ2V0dGVyKTtcbiBcdFx0cmV0dXJuIGdldHRlcjtcbiBcdH07XG5cbiBcdC8vIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbFxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5vID0gZnVuY3Rpb24ob2JqZWN0LCBwcm9wZXJ0eSkgeyByZXR1cm4gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iamVjdCwgcHJvcGVydHkpOyB9O1xuXG4gXHQvLyBfX3dlYnBhY2tfcHVibGljX3BhdGhfX1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5wID0gXCJcIjtcblxuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IDU0KTtcbiIsIi8qKlxyXG4gKiBBZG1pbiBtYW5hZ2UgdG91cm5hbWVudCBidWxrIHJlZ2lzdHJhdGlvbiBwYWdlLlxyXG4gKlxyXG4gKiBAbGluayAgICAgICBodHRwczovL3d3dy50b3VybmFtYXRjaC5jb21cclxuICogQHNpbmNlICAgICAgMy4xNy4wXHJcbiAqXHJcbiAqIEBwYWNrYWdlICAgIFRvdXJuYW1hdGNoXHJcbiAqXHJcbiAqL1xyXG5pbXBvcnQgeyB0cm4gfSBmcm9tICcuLy4uL3RvdXJuYW1hdGNoLmpzJztcclxuXHJcbihmdW5jdGlvbiAoJCkge1xyXG4gICAgJ3VzZSBzdHJpY3QnO1xyXG5cclxuICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIGxldCBvcHRpb25zID0gdHJuX3RvdXJuYW1lbnRfcmVnaXN0cmF0aW9uX29wdGlvbnM7XHJcblxyXG4gICAgICAgIC8vIGludGlhbGl6ZSBhdXRvIGNvbXBsZXRlXHJcbiAgICAgICAgJC5hdXRvY29tcGxldGUoZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2NvbXBldGl0b3JfaWQnKSwgZnVuY3Rpb24odmFsKSB7XHJcbiAgICAgICAgICAgIHJldHVybiBuZXcgUHJvbWlzZSgocmVzb2x2ZSwgcmVqZWN0KSA9PiB7XHJcbiAgICAgICAgICAgICAgICAvKiBuZWVkIHRvIHF1ZXJ5IHNlcnZlciBmb3IgbmFtZXMgaGVyZS4gKi9cclxuICAgICAgICAgICAgICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgICAgIHhoci5vcGVuKCdHRVQnLCBvcHRpb25zLmFwaV91cmwgKyAncGxheWVycy8/c2VhcmNoPScgKyB2YWwgKyAnJnBlcl9wYWdlPTUnKTtcclxuICAgICAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdDb250ZW50LVR5cGUnLCAnYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkJyk7XHJcbiAgICAgICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignWC1XUC1Ob25jZScsIG9wdGlvbnMucmVzdF9ub25jZSk7XHJcbiAgICAgICAgICAgICAgICB4aHIub25sb2FkID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh4aHIuc3RhdHVzID09PSAyMDApIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgLy8gcmVzb2x2ZShKU09OLnBhcnNlKHhoci5yZXNwb25zZSkubWFwKChwbGF5ZXIpID0+IHtyZXR1cm4geyAndmFsdWUnOiBwbGF5ZXIuaWQsICd0ZXh0JzogcGxheWVyLm5hbWUgfTt9KSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHJlc29sdmUoSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpLm1hcCgocGxheWVyKSA9PiB7cmV0dXJuIHBsYXllci5uYW1lO30pKTtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZWplY3QoKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9O1xyXG4gICAgICAgICAgICAgICAgeGhyLnNlbmQoKTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIC8vIHRvZ2dsZSBuZXcgb3Igc2VsZWN0XHJcbiAgICAgICAgZnVuY3Rpb24gdG9nZ2xlVGVhbXMoKSB7XHJcbiAgICAgICAgICAgIGxldCB0ZWFtU2VsZWN0aW9uID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ25ld19vcl9leGlzdGluZycpO1xyXG4gICAgICAgICAgICBsZXQgc2VsZWN0ZWRWYWx1ZSA9IHRlYW1TZWxlY3Rpb24ub3B0aW9uc1t0ZWFtU2VsZWN0aW9uLnNlbGVjdGVkSW5kZXhdLnZhbHVlO1xyXG5cclxuICAgICAgICAgICAgaWYgKHNlbGVjdGVkVmFsdWUgPT09ICduZXcnKSB7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndGFnX3JvdycpLnN0eWxlLmRpc3BsYXkgPSAndGFibGUtcm93JztcclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0ZWFtX3RhZycpLnJlcXVpcmVkID0gdHJ1ZTtcclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCduYW1lX3JvdycpLnN0eWxlLmRpc3BsYXkgPSAndGFibGUtcm93JztcclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0ZWFtX25hbWUnKS5yZXF1aXJlZCA9IHRydWU7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZXhpc3Rpbmdfcm93Jykuc3R5bGUuZGlzcGxheSA9ICdub25lJztcclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdleGlzdGluZ190ZWFtJykucmVxdWlyZWQgPSBmYWxzZTtcclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0YWdfcm93Jykuc3R5bGUuZGlzcGxheSA9ICdub25lJztcclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0ZWFtX3RhZycpLnJlcXVpcmVkID0gZmFsc2U7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbmFtZV9yb3cnKS5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3RlYW1fbmFtZScpLnJlcXVpcmVkID0gZmFsc2U7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZXhpc3Rpbmdfcm93Jykuc3R5bGUuZGlzcGxheSA9ICd0YWJsZS1yb3cnO1xyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2V4aXN0aW5nX3RlYW0nKS5yZXF1aXJlZCA9IHRydWU7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIC8vIG5ldyBvciBleGlzdGluZyBkcm9wIGRvd25cclxuICAgICAgICBpZiAob3B0aW9ucy5jb21wZXRpdGlvbiA9PT0gJ3RlYW1zJykge1xyXG4gICAgICAgICAgICBsZXQgdGVhbVNlbGVjdGlvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCduZXdfb3JfZXhpc3RpbmcnKTtcclxuXHJcbiAgICAgICAgICAgIHRlYW1TZWxlY3Rpb24uYWRkRXZlbnRMaXN0ZW5lcignY2hhbmdlJywgZnVuY3Rpb24gKGV2ZW50KSB7XHJcbiAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICAgICAgdG9nZ2xlVGVhbXMoKTtcclxuICAgICAgICAgICAgfSwgZmFsc2UpO1xyXG5cclxuICAgICAgICAgICAgdG9nZ2xlVGVhbXMoKTtcclxuXHJcbiAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdjb21wZXRpdG9yX2lkJykuYWRkRXZlbnRMaXN0ZW5lcignY2hhbmdlJywgZnVuY3Rpb24oZSkge1xyXG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coYHZhbHVlIGNoYW5nZWQgdG8gJHt0aGlzLnZhbHVlfWApO1xyXG4gICAgICAgICAgICAgICAgbGV0IHAgPSBuZXcgUHJvbWlzZSgocmVzb2x2ZSwgcmVqZWN0KSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xyXG4gICAgICAgICAgICAgICAgICAgIHhoci5vcGVuKCdHRVQnLCBvcHRpb25zLmFwaV91cmwgKyAncGxheWVycy8/c2VhcmNoPScgKyB0aGlzLnZhbHVlKTtcclxuICAgICAgICAgICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xyXG4gICAgICAgICAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgICAgICAgICB4aHIub25sb2FkID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyh4aHIucmVzcG9uc2UpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb25zdCBwbGF5ZXJzID0gSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmIChwbGF5ZXJzLmxlbmd0aCA+IDApIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXNvbHZlKHBsYXllcnNbMF1bJ3VzZXJfaWQnXSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Rybi10b3VybmFtZW50LXJlZ2lzdGVyLXJlc3BvbnNlJykuaW5uZXJIVE1MID0gYGA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tdG91cm5hbWVudC1yZWdpc3Rlci1yZXNwb25zZScpLmlubmVySFRNTCA9IGA8cCBjbGFzcz1cIm5vdGljZSBub3RpY2UtZXJyb3JcIj48c3Ryb25nPiR7b3B0aW9ucy5sYW5ndWFnZS5mYWlsdXJlfTo8L3N0cm9uZz4gJHtvcHRpb25zLmxhbmd1YWdlLm5vX2NvbXBldGl0b3J9PC9wPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZWplY3QoKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIH07XHJcbiAgICAgICAgICAgICAgICAgICAgeGhyLnNlbmQoKTtcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgcC50aGVuKCh1c2VyX2lkKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgZ2V0VGVhbXModXNlcl9pZCk7XHJcbiAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgfSwgZmFsc2UpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgLy8gZ2V0IHRlYW1zIGZvciBzaW5nbGUgcGxheWVyXHJcbiAgICAgICAgZnVuY3Rpb24gZ2V0VGVhbXModXNlcl9pZCkge1xyXG4gICAgICAgICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgIHhoci5vcGVuKCdHRVQnLCBvcHRpb25zLmFwaV91cmwgKyBgcGxheWVycy8ke3VzZXJfaWR9L3RlYW1zYCk7XHJcbiAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdDb250ZW50LVR5cGUnLCAnYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkJyk7XHJcbiAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKHhocik7XHJcbiAgICAgICAgICAgICAgICBsZXQgY29udGVudCA9IGBgO1xyXG4gICAgICAgICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMCkge1xyXG4gICAgICAgICAgICAgICAgICAgIGxldCB0ZWFtcyA9IEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHRlYW1zICE9PSBudWxsICYmIHRlYW1zLmxlbmd0aCA+IDApIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCB0ZWFtcy5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbGV0IHRlYW0gPSB0ZWFtc1tpXTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8b3B0aW9uIHZhbHVlPVwiJHt0ZWFtLnRlYW1faWR9XCI+JHt0ZWFtLm5hbWV9PC9vcHRpb24+YDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxvcHRpb24gdmFsdWU9XCItMVwiPigke29wdGlvbnMubGFuZ3VhZ2UuemVyb190ZWFtc30pPC9vcHRpb24+YDtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxvcHRpb24gdmFsdWU9XCItMVwiPigke29wdGlvbnMubGFuZ3VhZ2UuemVyb190ZWFtc30pPC9vcHRpb24+YDtcclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZXhpc3RpbmdfdGVhbScpLmlubmVySFRNTCA9IGNvbnRlbnQ7XHJcbiAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgICAgICB4aHIuc2VuZCgpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZnVuY3Rpb24gcmVnaXN0ZXJDb21wZXRpdG9yKGNvbXBldGl0aW9uSWQsIGNvbXBldGl0b3JJZCwgY29tcGV0aXRvclR5cGUpIHtcclxuICAgICAgICAgICAgY29uc29sZS5sb2coYHJlZ2lzdGVyaW5nICR7Y29tcGV0aXRvclR5cGV9IHdpdGggaWQgJHtjb21wZXRpdG9ySWR9IHRvIGNvbXBldGl0aW9uIHdpdGggaWQgJHtjb21wZXRpdGlvbklkfWApO1xyXG5cclxuICAgICAgICAgICAgbGV0IHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xyXG4gICAgICAgICAgICB4aHIub3BlbignUE9TVCcsIG9wdGlvbnMuYXBpX3VybCArICd0b3VybmFtZW50LXJlZ2lzdHJhdGlvbnMnKTtcclxuICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ1gtV1AtTm9uY2UnLCBvcHRpb25zLnJlc3Rfbm9uY2UpO1xyXG4gICAgICAgICAgICB4aHIub25sb2FkID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coeGhyKTtcclxuICAgICAgICAgICAgICAgIGlmICh4aHIuc3RhdHVzID09PSAyMDEpIHtcclxuICAgICAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLXRvdXJuYW1lbnQtcmVnaXN0ZXItcmVzcG9uc2UnKS5pbm5lckhUTUwgPSBgPHAgY2xhc3M9XCJub3RpY2Ugbm90aWNlLXN1Y2Nlc3NcIj48c3Ryb25nPiR7b3B0aW9ucy5sYW5ndWFnZS5zdWNjZXNzfTo8L3N0cm9uZz4gJHtvcHRpb25zLmxhbmd1YWdlLnN1Y2Nlc3NfbWVzc2FnZX08L3A+YDtcclxuICAgICAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLXRvdXJuYW1lbnQtcmVnaXN0ZXItZm9ybScpLnJlc2V0KCk7XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tdG91cm5hbWVudC1yZWdpc3Rlci1yZXNwb25zZScpLmlubmVySFRNTCA9IGA8cCBjbGFzcz1cIm5vdGljZSBub3RpY2UtZXJyb3JcIj48c3Ryb25nPiR7b3B0aW9ucy5sYW5ndWFnZS5mYWlsdXJlfTo8L3N0cm9uZz4gJHtKU09OLnBhcnNlKHhoci5yZXNwb25zZSkubWVzc2FnZX08L3A+YDtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgIHhoci5zZW5kKCQucGFyYW0oe1xyXG4gICAgICAgICAgICAgICAgdG91cm5hbWVudF9pZDogY29tcGV0aXRpb25JZCxcclxuICAgICAgICAgICAgICAgIGNvbXBldGl0b3JfaWQ6IGNvbXBldGl0b3JJZCxcclxuICAgICAgICAgICAgICAgIGNvbXBldGl0b3JfdHlwZTogY29tcGV0aXRvclR5cGUsXHJcbiAgICAgICAgICAgIH0pKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tdG91cm5hbWVudC1yZWdpc3Rlci1mb3JtJykuYWRkRXZlbnRMaXN0ZW5lcignc3VibWl0JywgZnVuY3Rpb24oZXZlbnQpIHtcclxuICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcclxuXHJcbiAgICAgICAgICAgIGxldCBwID0gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xyXG4gICAgICAgICAgICAgICAgbGV0IHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xyXG4gICAgICAgICAgICAgICAgeGhyLm9wZW4oJ0dFVCcsIG9wdGlvbnMuYXBpX3VybCArICdwbGF5ZXJzLz9zZWFyY2g9JyArIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdjb21wZXRpdG9yX2lkJykudmFsdWUpO1xyXG4gICAgICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2coeGhyLnJlc3BvbnNlKTtcclxuICAgICAgICAgICAgICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnN0IHBsYXllcnMgPSBKU09OLnBhcnNlKHhoci5yZXNwb25zZSk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAocGxheWVycy5sZW5ndGggPiAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXNvbHZlKHBsYXllcnNbMF1bJ3VzZXJfaWQnXSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLXRvdXJuYW1lbnQtcmVnaXN0ZXItcmVzcG9uc2UnKS5pbm5lckhUTUwgPSBgYDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tdG91cm5hbWVudC1yZWdpc3Rlci1yZXNwb25zZScpLmlubmVySFRNTCA9IGA8cCBjbGFzcz1cIm5vdGljZSBub3RpY2UtZXJyb3JcIj48c3Ryb25nPiR7b3B0aW9ucy5sYW5ndWFnZS5mYWlsdXJlfTo8L3N0cm9uZz4gJHtvcHRpb25zLmxhbmd1YWdlLm5vX2NvbXBldGl0b3J9PC9wPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZWplY3QoKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9O1xyXG4gICAgICAgICAgICAgICAgeGhyLnNlbmQoKTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIHAudGhlbigodXNlcklkKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBpZiAob3B0aW9ucy5jb21wZXRpdGlvbiA9PT0gJ3RlYW1zJykge1xyXG4gICAgICAgICAgICAgICAgICAgIGxldCB0ZWFtU2VsZWN0aW9uID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ25ld19vcl9leGlzdGluZycpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBpZiAoIHRlYW1TZWxlY3Rpb24udmFsdWUgPT09ICduZXcnICkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBsZXQgcSA9IG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHhoci5vcGVuKCdQT1NUJywgb3B0aW9ucy5hcGlfdXJsICsgJ3RlYW1zJyk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ1gtV1AtTm9uY2UnLCBvcHRpb25zLnJlc3Rfbm9uY2UpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJlc29sdmUoSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpLmRhdGEudGVhbV9pZCk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcmVqZWN0KHhocik7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB4aHIuc2VuZCgkLnBhcmFtKHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB1c2VyX2lkOiB1c2VySWQsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbmFtZTogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3RlYW1fbmFtZScpLnZhbHVlLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRhZzogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3RlYW1fdGFnJykudmFsdWVcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0pKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHEudGhlbigodGVhbUlkKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZWdpc3RlckNvbXBldGl0b3Iob3B0aW9ucy50b3VybmFtZW50X2lkLCB0ZWFtSWQsICd0ZWFtcycpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZWdpc3RlckNvbXBldGl0b3Iob3B0aW9ucy50b3VybmFtZW50X2lkLCBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZXhpc3RpbmdfdGVhbScpLnZhbHVlLCAndGVhbXMnKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIHJlZ2lzdGVyQ29tcGV0aXRvcihvcHRpb25zLnRvdXJuYW1lbnRfaWQsIHVzZXJJZCwgJ3BsYXllcnMnKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICBjb25zb2xlLmxvZygnc3VibWl0dGVkJyk7XHJcbiAgICAgICAgfSwgZmFsc2UpO1xyXG5cclxuICAgIH0sIGZhbHNlKTtcclxufSkodHJuKTsiLCIndXNlIHN0cmljdCc7XHJcbmNsYXNzIFRvdXJuYW1hdGNoIHtcclxuXHJcbiAgICBjb25zdHJ1Y3RvcigpIHtcclxuICAgICAgICB0aGlzLmV2ZW50cyA9IHt9O1xyXG4gICAgfVxyXG5cclxuICAgIHBhcmFtKG9iamVjdCwgcHJlZml4KSB7XHJcbiAgICAgICAgbGV0IHN0ciA9IFtdO1xyXG4gICAgICAgIGZvciAobGV0IHByb3AgaW4gb2JqZWN0KSB7XHJcbiAgICAgICAgICAgIGlmIChvYmplY3QuaGFzT3duUHJvcGVydHkocHJvcCkpIHtcclxuICAgICAgICAgICAgICAgIGxldCBrID0gcHJlZml4ID8gcHJlZml4ICsgXCJbXCIgKyBwcm9wICsgXCJdXCIgOiBwcm9wO1xyXG4gICAgICAgICAgICAgICAgbGV0IHYgPSBvYmplY3RbcHJvcF07XHJcbiAgICAgICAgICAgICAgICBzdHIucHVzaCgodiAhPT0gbnVsbCAmJiB0eXBlb2YgdiA9PT0gXCJvYmplY3RcIikgPyB0aGlzLnBhcmFtKHYsIGspIDogZW5jb2RlVVJJQ29tcG9uZW50KGspICsgXCI9XCIgKyBlbmNvZGVVUklDb21wb25lbnQodikpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiBzdHIuam9pbihcIiZcIik7XHJcbiAgICB9XHJcblxyXG4gICAgZXZlbnQoZXZlbnROYW1lKSB7XHJcbiAgICAgICAgaWYgKCEoZXZlbnROYW1lIGluIHRoaXMuZXZlbnRzKSkge1xyXG4gICAgICAgICAgICB0aGlzLmV2ZW50c1tldmVudE5hbWVdID0gbmV3IEV2ZW50VGFyZ2V0KGV2ZW50TmFtZSk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiB0aGlzLmV2ZW50c1tldmVudE5hbWVdO1xyXG4gICAgfVxyXG5cclxuICAgIGF1dG9jb21wbGV0ZShpbnB1dCwgZGF0YUNhbGxiYWNrKSB7XHJcbiAgICAgICAgbmV3IFRvdXJuYW1hdGNoX0F1dG9jb21wbGV0ZShpbnB1dCwgZGF0YUNhbGxiYWNrKTtcclxuICAgIH1cclxuXHJcbiAgICB1Y2ZpcnN0KHMpIHtcclxuICAgICAgICBpZiAodHlwZW9mIHMgIT09ICdzdHJpbmcnKSByZXR1cm4gJyc7XHJcbiAgICAgICAgcmV0dXJuIHMuY2hhckF0KDApLnRvVXBwZXJDYXNlKCkgKyBzLnNsaWNlKDEpO1xyXG4gICAgfVxyXG5cclxuICAgIG9yZGluYWxfc3VmZml4KG51bWJlcikge1xyXG4gICAgICAgIGNvbnN0IHJlbWFpbmRlciA9IG51bWJlciAlIDEwMDtcclxuXHJcbiAgICAgICAgaWYgKChyZW1haW5kZXIgPCAxMSkgfHwgKHJlbWFpbmRlciA+IDEzKSkge1xyXG4gICAgICAgICAgICBzd2l0Y2ggKHJlbWFpbmRlciAlIDEwKSB7XHJcbiAgICAgICAgICAgICAgICBjYXNlIDE6IHJldHVybiAnc3QnO1xyXG4gICAgICAgICAgICAgICAgY2FzZSAyOiByZXR1cm4gJ25kJztcclxuICAgICAgICAgICAgICAgIGNhc2UgMzogcmV0dXJuICdyZCc7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuICd0aCc7XHJcbiAgICB9XHJcblxyXG4gICAgdGFicyhlbGVtZW50KSB7XHJcbiAgICAgICAgY29uc3QgdGFicyA9IGVsZW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLW5hdi1saW5rJyk7XHJcbiAgICAgICAgY29uc3QgcGFuZXMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tdGFiLXBhbmUnKTtcclxuICAgICAgICBjb25zdCBjbGVhckFjdGl2ZSA9ICgpID0+IHtcclxuICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbCh0YWJzLCAodGFiKSA9PiB7XHJcbiAgICAgICAgICAgICAgICB0YWIuY2xhc3NMaXN0LnJlbW92ZSgndHJuLW5hdi1hY3RpdmUnKTtcclxuICAgICAgICAgICAgICAgIHRhYi5hcmlhU2VsZWN0ZWQgPSBmYWxzZTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwocGFuZXMsIHBhbmUgPT4gcGFuZS5jbGFzc0xpc3QucmVtb3ZlKCd0cm4tdGFiLWFjdGl2ZScpKTtcclxuICAgICAgICB9O1xyXG4gICAgICAgIGNvbnN0IHNldEFjdGl2ZSA9ICh0YXJnZXRJZCkgPT4ge1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRUYWIgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdhW2hyZWY9XCIjJyArIHRhcmdldElkICsgJ1wiXS50cm4tbmF2LWxpbmsnKTtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0UGFuZUlkID0gdGFyZ2V0VGFiICYmIHRhcmdldFRhYi5kYXRhc2V0ICYmIHRhcmdldFRhYi5kYXRhc2V0LnRhcmdldCB8fCBmYWxzZTtcclxuXHJcbiAgICAgICAgICAgIGlmICh0YXJnZXRQYW5lSWQpIHtcclxuICAgICAgICAgICAgICAgIGNsZWFyQWN0aXZlKCk7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRUYWIuY2xhc3NMaXN0LmFkZCgndHJuLW5hdi1hY3RpdmUnKTtcclxuICAgICAgICAgICAgICAgIHRhcmdldFRhYi5hcmlhU2VsZWN0ZWQgPSB0cnVlO1xyXG5cclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHRhcmdldFBhbmVJZCkuY2xhc3NMaXN0LmFkZCgndHJuLXRhYi1hY3RpdmUnKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH07XHJcbiAgICAgICAgY29uc3QgdGFiQ2xpY2sgPSAoZXZlbnQpID0+IHtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0VGFiID0gZXZlbnQuY3VycmVudFRhcmdldDtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0UGFuZUlkID0gdGFyZ2V0VGFiICYmIHRhcmdldFRhYi5kYXRhc2V0ICYmIHRhcmdldFRhYi5kYXRhc2V0LnRhcmdldCB8fCBmYWxzZTtcclxuXHJcbiAgICAgICAgICAgIGlmICh0YXJnZXRQYW5lSWQpIHtcclxuICAgICAgICAgICAgICAgIHNldEFjdGl2ZSh0YXJnZXRQYW5lSWQpO1xyXG4gICAgICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH07XHJcblxyXG4gICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwodGFicywgKHRhYikgPT4ge1xyXG4gICAgICAgICAgICB0YWIuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0YWJDbGljayk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIGlmIChsb2NhdGlvbi5oYXNoKSB7XHJcbiAgICAgICAgICAgIHNldEFjdGl2ZShsb2NhdGlvbi5oYXNoLnN1YnN0cigxKSk7XHJcbiAgICAgICAgfSBlbHNlIGlmICh0YWJzLmxlbmd0aCA+IDApIHtcclxuICAgICAgICAgICAgc2V0QWN0aXZlKHRhYnNbMF0uZGF0YXNldC50YXJnZXQpO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbn1cclxuXHJcbi8vdHJuLmluaXRpYWxpemUoKTtcclxuaWYgKCF3aW5kb3cudHJuX29ial9pbnN0YW5jZSkge1xyXG4gICAgd2luZG93LnRybl9vYmpfaW5zdGFuY2UgPSBuZXcgVG91cm5hbWF0Y2goKTtcclxuXHJcbiAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIGZ1bmN0aW9uICgpIHtcclxuXHJcbiAgICAgICAgY29uc3QgdGFiVmlld3MgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tbmF2Jyk7XHJcblxyXG4gICAgICAgIEFycmF5LmZyb20odGFiVmlld3MpLmZvckVhY2goKHRhYikgPT4ge1xyXG4gICAgICAgICAgICB0cm4udGFicyh0YWIpO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICBjb25zdCBkcm9wZG93bnMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tZHJvcGRvd24tdG9nZ2xlJyk7XHJcbiAgICAgICAgY29uc3QgaGFuZGxlRHJvcGRvd25DbG9zZSA9ICgpID0+IHtcclxuICAgICAgICAgICAgQXJyYXkuZnJvbShkcm9wZG93bnMpLmZvckVhY2goKGRyb3Bkb3duKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBkcm9wZG93bi5uZXh0RWxlbWVudFNpYmxpbmcuY2xhc3NMaXN0LnJlbW92ZSgndHJuLXNob3cnKTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIGRvY3VtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBoYW5kbGVEcm9wZG93bkNsb3NlLCBmYWxzZSk7XHJcbiAgICAgICAgfTtcclxuXHJcbiAgICAgICAgQXJyYXkuZnJvbShkcm9wZG93bnMpLmZvckVhY2goKGRyb3Bkb3duKSA9PiB7XHJcbiAgICAgICAgICAgIGRyb3Bkb3duLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24oZSkge1xyXG4gICAgICAgICAgICAgICAgZS5zdG9wUHJvcGFnYXRpb24oKTtcclxuICAgICAgICAgICAgICAgIHRoaXMubmV4dEVsZW1lbnRTaWJsaW5nLmNsYXNzTGlzdC5hZGQoJ3Rybi1zaG93Jyk7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgaGFuZGxlRHJvcGRvd25DbG9zZSwgZmFsc2UpO1xyXG4gICAgICAgICAgICB9LCBmYWxzZSk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgfSwgZmFsc2UpO1xyXG59XHJcbmV4cG9ydCBsZXQgdHJuID0gd2luZG93LnRybl9vYmpfaW5zdGFuY2U7XHJcblxyXG5jbGFzcyBUb3VybmFtYXRjaF9BdXRvY29tcGxldGUge1xyXG5cclxuICAgIC8vIGN1cnJlbnRGb2N1cztcclxuICAgIC8vXHJcbiAgICAvLyBuYW1lSW5wdXQ7XHJcbiAgICAvL1xyXG4gICAgLy8gc2VsZjtcclxuXHJcbiAgICBjb25zdHJ1Y3RvcihpbnB1dCwgZGF0YUNhbGxiYWNrKSB7XHJcbiAgICAgICAgLy8gdGhpcy5zZWxmID0gdGhpcztcclxuICAgICAgICB0aGlzLm5hbWVJbnB1dCA9IGlucHV0O1xyXG5cclxuICAgICAgICB0aGlzLm5hbWVJbnB1dC5hZGRFdmVudExpc3RlbmVyKFwiaW5wdXRcIiwgKCkgPT4ge1xyXG4gICAgICAgICAgICBsZXQgYSwgYiwgaSwgdmFsID0gdGhpcy5uYW1lSW5wdXQudmFsdWU7Ly90aGlzLnZhbHVlO1xyXG4gICAgICAgICAgICBsZXQgcGFyZW50ID0gdGhpcy5uYW1lSW5wdXQucGFyZW50Tm9kZTsvL3RoaXMucGFyZW50Tm9kZTtcclxuXHJcbiAgICAgICAgICAgIC8vIGxldCBwID0gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xyXG4gICAgICAgICAgICAvLyAgICAgLyogbmVlZCB0byBxdWVyeSBzZXJ2ZXIgZm9yIG5hbWVzIGhlcmUuICovXHJcbiAgICAgICAgICAgIC8vICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIub3BlbignR0VUJywgb3B0aW9ucy5hcGlfdXJsICsgJ3BsYXllcnMvP3NlYXJjaD0nICsgdmFsICsgJyZwZXJfcGFnZT01Jyk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ1gtV1AtTm9uY2UnLCBvcHRpb25zLnJlc3Rfbm9uY2UpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgLy8gICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAwKSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgICAgIC8vIHJlc29sdmUoSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpLm1hcCgocGxheWVyKSA9PiB7cmV0dXJuIHsgJ3ZhbHVlJzogcGxheWVyLmlkLCAndGV4dCc6IHBsYXllci5uYW1lIH07fSkpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgICAgICByZXNvbHZlKEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKS5tYXAoKHBsYXllcikgPT4ge3JldHVybiBwbGF5ZXIubmFtZTt9KSk7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgLy8gICAgICAgICAgICAgcmVqZWN0KCk7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgfVxyXG4gICAgICAgICAgICAvLyAgICAgfTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5zZW5kKCk7XHJcbiAgICAgICAgICAgIC8vIH0pO1xyXG4gICAgICAgICAgICBkYXRhQ2FsbGJhY2sodmFsKS50aGVuKChkYXRhKSA9PiB7Ly9wLnRoZW4oKGRhdGEpID0+IHtcclxuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKGRhdGEpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qY2xvc2UgYW55IGFscmVhZHkgb3BlbiBsaXN0cyBvZiBhdXRvLWNvbXBsZXRlZCB2YWx1ZXMqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5jbG9zZUFsbExpc3RzKCk7XHJcbiAgICAgICAgICAgICAgICBpZiAoIXZhbCkgeyByZXR1cm4gZmFsc2U7fVxyXG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50Rm9jdXMgPSAtMTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmNyZWF0ZSBhIERJViBlbGVtZW50IHRoYXQgd2lsbCBjb250YWluIHRoZSBpdGVtcyAodmFsdWVzKToqL1xyXG4gICAgICAgICAgICAgICAgYSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJESVZcIik7XHJcbiAgICAgICAgICAgICAgICBhLnNldEF0dHJpYnV0ZShcImlkXCIsIHRoaXMubmFtZUlucHV0LmlkICsgXCItYXV0by1jb21wbGV0ZS1saXN0XCIpO1xyXG4gICAgICAgICAgICAgICAgYS5zZXRBdHRyaWJ1dGUoXCJjbGFzc1wiLCBcInRybi1hdXRvLWNvbXBsZXRlLWl0ZW1zXCIpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qYXBwZW5kIHRoZSBESVYgZWxlbWVudCBhcyBhIGNoaWxkIG9mIHRoZSBhdXRvLWNvbXBsZXRlIGNvbnRhaW5lcjoqL1xyXG4gICAgICAgICAgICAgICAgcGFyZW50LmFwcGVuZENoaWxkKGEpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qZm9yIGVhY2ggaXRlbSBpbiB0aGUgYXJyYXkuLi4qL1xyXG4gICAgICAgICAgICAgICAgZm9yIChpID0gMDsgaSA8IGRhdGEubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgICAgICAgICBsZXQgdGV4dCwgdmFsdWU7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIC8qIFdoaWNoIGZvcm1hdCBkaWQgdGhleSBnaXZlIHVzLiAqL1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgZGF0YVtpXSA9PT0gJ29iamVjdCcpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdGV4dCA9IGRhdGFbaV1bJ3RleHQnXTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSBkYXRhW2ldWyd2YWx1ZSddO1xyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHRleHQgPSBkYXRhW2ldO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB2YWx1ZSA9IGRhdGFbaV07XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgICAgICAvKmNoZWNrIGlmIHRoZSBpdGVtIHN0YXJ0cyB3aXRoIHRoZSBzYW1lIGxldHRlcnMgYXMgdGhlIHRleHQgZmllbGQgdmFsdWU6Ki9cclxuICAgICAgICAgICAgICAgICAgICBpZiAodGV4dC5zdWJzdHIoMCwgdmFsLmxlbmd0aCkudG9VcHBlckNhc2UoKSA9PT0gdmFsLnRvVXBwZXJDYXNlKCkpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgLypjcmVhdGUgYSBESVYgZWxlbWVudCBmb3IgZWFjaCBtYXRjaGluZyBlbGVtZW50OiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiRElWXCIpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKm1ha2UgdGhlIG1hdGNoaW5nIGxldHRlcnMgYm9sZDoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmlubmVySFRNTCA9IFwiPHN0cm9uZz5cIiArIHRleHQuc3Vic3RyKDAsIHZhbC5sZW5ndGgpICsgXCI8L3N0cm9uZz5cIjtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgKz0gdGV4dC5zdWJzdHIodmFsLmxlbmd0aCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKmluc2VydCBhIGlucHV0IGZpZWxkIHRoYXQgd2lsbCBob2xkIHRoZSBjdXJyZW50IGFycmF5IGl0ZW0ncyB2YWx1ZToqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmlubmVySFRNTCArPSBcIjxpbnB1dCB0eXBlPSdoaWRkZW4nIHZhbHVlPSdcIiArIHZhbHVlICsgXCInPlwiO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5kYXRhc2V0LnZhbHVlID0gdmFsdWU7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuZGF0YXNldC50ZXh0ID0gdGV4dDtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHdoZW4gc29tZW9uZSBjbGlja3Mgb24gdGhlIGl0ZW0gdmFsdWUgKERJViBlbGVtZW50KToqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2coYGl0ZW0gY2xpY2tlZCB3aXRoIHZhbHVlICR7ZS5jdXJyZW50VGFyZ2V0LmRhdGFzZXQudmFsdWV9YCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLyogaW5zZXJ0IHRoZSB2YWx1ZSBmb3IgdGhlIGF1dG9jb21wbGV0ZSB0ZXh0IGZpZWxkOiAqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5uYW1lSW5wdXQudmFsdWUgPSBlLmN1cnJlbnRUYXJnZXQuZGF0YXNldC50ZXh0O1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5uYW1lSW5wdXQuZGF0YXNldC5zZWxlY3RlZElkID0gZS5jdXJyZW50VGFyZ2V0LmRhdGFzZXQudmFsdWU7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLyogY2xvc2UgdGhlIGxpc3Qgb2YgYXV0b2NvbXBsZXRlZCB2YWx1ZXMsIChvciBhbnkgb3RoZXIgb3BlbiBsaXN0cyBvZiBhdXRvY29tcGxldGVkIHZhbHVlczoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5jbG9zZUFsbExpc3RzKCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5uYW1lSW5wdXQuZGlzcGF0Y2hFdmVudChuZXcgRXZlbnQoJ2NoYW5nZScpKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGEuYXBwZW5kQ2hpbGQoYik7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgLypleGVjdXRlIGEgZnVuY3Rpb24gcHJlc3NlcyBhIGtleSBvbiB0aGUga2V5Ym9hcmQ6Ki9cclxuICAgICAgICB0aGlzLm5hbWVJbnB1dC5hZGRFdmVudExpc3RlbmVyKFwia2V5ZG93blwiLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICBsZXQgeCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHRoaXMubmFtZUlucHV0LmlkICsgXCItYXV0by1jb21wbGV0ZS1saXN0XCIpO1xyXG4gICAgICAgICAgICBpZiAoeCkgeCA9IHguZ2V0RWxlbWVudHNCeVRhZ05hbWUoXCJkaXZcIik7XHJcbiAgICAgICAgICAgIGlmIChlLmtleUNvZGUgPT09IDQwKSB7XHJcbiAgICAgICAgICAgICAgICAvKklmIHRoZSBhcnJvdyBET1dOIGtleSBpcyBwcmVzc2VkLFxyXG4gICAgICAgICAgICAgICAgIGluY3JlYXNlIHRoZSBjdXJyZW50Rm9jdXMgdmFyaWFibGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudEZvY3VzKys7XHJcbiAgICAgICAgICAgICAgICAvKmFuZCBhbmQgbWFrZSB0aGUgY3VycmVudCBpdGVtIG1vcmUgdmlzaWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5hZGRBY3RpdmUoeCk7XHJcbiAgICAgICAgICAgIH0gZWxzZSBpZiAoZS5rZXlDb2RlID09PSAzOCkgeyAvL3VwXHJcbiAgICAgICAgICAgICAgICAvKklmIHRoZSBhcnJvdyBVUCBrZXkgaXMgcHJlc3NlZCxcclxuICAgICAgICAgICAgICAgICBkZWNyZWFzZSB0aGUgY3VycmVudEZvY3VzIHZhcmlhYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmN1cnJlbnRGb2N1cy0tO1xyXG4gICAgICAgICAgICAgICAgLyphbmQgYW5kIG1ha2UgdGhlIGN1cnJlbnQgaXRlbSBtb3JlIHZpc2libGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuYWRkQWN0aXZlKHgpO1xyXG4gICAgICAgICAgICB9IGVsc2UgaWYgKGUua2V5Q29kZSA9PT0gMTMpIHtcclxuICAgICAgICAgICAgICAgIC8qSWYgdGhlIEVOVEVSIGtleSBpcyBwcmVzc2VkLCBwcmV2ZW50IHRoZSBmb3JtIGZyb20gYmVpbmcgc3VibWl0dGVkLCovXHJcbiAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgICAgICBpZiAodGhpcy5jdXJyZW50Rm9jdXMgPiAtMSkge1xyXG4gICAgICAgICAgICAgICAgICAgIC8qYW5kIHNpbXVsYXRlIGEgY2xpY2sgb24gdGhlIFwiYWN0aXZlXCIgaXRlbToqL1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh4KSB4W3RoaXMuY3VycmVudEZvY3VzXS5jbGljaygpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHdoZW4gc29tZW9uZSBjbGlja3MgaW4gdGhlIGRvY3VtZW50OiovXHJcbiAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIChlKSA9PiB7XHJcbiAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cyhlLnRhcmdldCk7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9XHJcblxyXG4gICAgYWRkQWN0aXZlKHgpIHtcclxuICAgICAgICAvKmEgZnVuY3Rpb24gdG8gY2xhc3NpZnkgYW4gaXRlbSBhcyBcImFjdGl2ZVwiOiovXHJcbiAgICAgICAgaWYgKCF4KSByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgLypzdGFydCBieSByZW1vdmluZyB0aGUgXCJhY3RpdmVcIiBjbGFzcyBvbiBhbGwgaXRlbXM6Ki9cclxuICAgICAgICB0aGlzLnJlbW92ZUFjdGl2ZSh4KTtcclxuICAgICAgICBpZiAodGhpcy5jdXJyZW50Rm9jdXMgPj0geC5sZW5ndGgpIHRoaXMuY3VycmVudEZvY3VzID0gMDtcclxuICAgICAgICBpZiAodGhpcy5jdXJyZW50Rm9jdXMgPCAwKSB0aGlzLmN1cnJlbnRGb2N1cyA9ICh4Lmxlbmd0aCAtIDEpO1xyXG4gICAgICAgIC8qYWRkIGNsYXNzIFwiYXV0b2NvbXBsZXRlLWFjdGl2ZVwiOiovXHJcbiAgICAgICAgeFt0aGlzLmN1cnJlbnRGb2N1c10uY2xhc3NMaXN0LmFkZChcInRybi1hdXRvLWNvbXBsZXRlLWFjdGl2ZVwiKTtcclxuICAgIH1cclxuXHJcbiAgICByZW1vdmVBY3RpdmUoeCkge1xyXG4gICAgICAgIC8qYSBmdW5jdGlvbiB0byByZW1vdmUgdGhlIFwiYWN0aXZlXCIgY2xhc3MgZnJvbSBhbGwgYXV0b2NvbXBsZXRlIGl0ZW1zOiovXHJcbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCB4Lmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgIHhbaV0uY2xhc3NMaXN0LnJlbW92ZShcInRybi1hdXRvLWNvbXBsZXRlLWFjdGl2ZVwiKTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcblxyXG4gICAgY2xvc2VBbGxMaXN0cyhlbGVtZW50KSB7XHJcbiAgICAgICAgY29uc29sZS5sb2coXCJjbG9zZSBhbGwgbGlzdHNcIik7XHJcbiAgICAgICAgLypjbG9zZSBhbGwgYXV0b2NvbXBsZXRlIGxpc3RzIGluIHRoZSBkb2N1bWVudCxcclxuICAgICAgICAgZXhjZXB0IHRoZSBvbmUgcGFzc2VkIGFzIGFuIGFyZ3VtZW50OiovXHJcbiAgICAgICAgbGV0IHggPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKFwidHJuLWF1dG8tY29tcGxldGUtaXRlbXNcIik7XHJcbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCB4Lmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgIGlmIChlbGVtZW50ICE9PSB4W2ldICYmIGVsZW1lbnQgIT09IHRoaXMubmFtZUlucHV0KSB7XHJcbiAgICAgICAgICAgICAgICB4W2ldLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoeFtpXSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICB9XHJcbn1cclxuXHJcbi8vIEZpcnN0LCBjaGVja3MgaWYgaXQgaXNuJ3QgaW1wbGVtZW50ZWQgeWV0LlxyXG5pZiAoIVN0cmluZy5wcm90b3R5cGUuZm9ybWF0KSB7XHJcbiAgICBTdHJpbmcucHJvdG90eXBlLmZvcm1hdCA9IGZ1bmN0aW9uKCkge1xyXG4gICAgICAgIGNvbnN0IGFyZ3MgPSBhcmd1bWVudHM7XHJcbiAgICAgICAgcmV0dXJuIHRoaXMucmVwbGFjZSgveyhcXGQrKX0vZywgZnVuY3Rpb24obWF0Y2gsIG51bWJlcikge1xyXG4gICAgICAgICAgICByZXR1cm4gdHlwZW9mIGFyZ3NbbnVtYmVyXSAhPT0gJ3VuZGVmaW5lZCdcclxuICAgICAgICAgICAgICAgID8gYXJnc1tudW1iZXJdXHJcbiAgICAgICAgICAgICAgICA6IG1hdGNoXHJcbiAgICAgICAgICAgICAgICA7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9O1xyXG59Il0sInNvdXJjZVJvb3QiOiIifQ==