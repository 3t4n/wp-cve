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
/******/ 	return __webpack_require__(__webpack_require__.s = 49);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/admin/ladder-competitors.js":
/*!********************************************!*\
  !*** ./src/js/admin/ladder-competitors.js ***!
  \********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../tournamatch.js */ "./src/js/tournamatch.js");
/**
 * Admin manage ladder competitors page.
 *
 * @link       https://www.tournamatch.com
 * @since      4.6.0
 *
 * @package    Tournamatch
 *
 */


(function ($) {
  'use strict';

  window.addEventListener('load', function () {
    var options = trn_ladder_competitors_options; // intialize auto complete

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
                document.getElementById('trn-ladder-competitors-response').innerHTML = "";
              } else {
                document.getElementById('trn-ladder-competitors-response').innerHTML = "<p class=\"notice notice-error\"><strong>".concat(options.language.failure, ":</strong> ").concat(options.language.no_competitor, "</p>");
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
      xhr.open('POST', options.api_url + 'ladder-competitors/?admin=1');
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        console.log(xhr);

        if (xhr.status === 201) {
          document.getElementById('trn-ladder-competitors-response').innerHTML = "<p class=\"notice notice-success\"><strong>".concat(options.language.success, ":</strong> ").concat(options.language.success_message, "</p>");
          document.getElementById('trn-ladder-competitors-form').reset();
        } else {
          document.getElementById('trn-ladder-competitors-response').innerHTML = "<p class=\"notice notice-error\"><strong>".concat(options.language.failure, ":</strong> ").concat(JSON.parse(xhr.response).message, "</p>");
        }
      };

      xhr.send($.param({
        ladder_id: competitionId,
        competitor_id: competitorId,
        competitor_type: competitorType
      }));
    }

    document.getElementById('trn-ladder-competitors-form').addEventListener('submit', function (event) {
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
              document.getElementById('trn-ladder-competitors-response').innerHTML = "";
            } else {
              document.getElementById('trn-ladder-competitors-response').innerHTML = "<p class=\"notice notice-error\"><strong>".concat(options.language.failure, ":</strong> ").concat(options.language.no_competitor, "</p>");
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
              registerCompetitor(options.ladder_id, teamId, 'teams');
            });
          } else {
            registerCompetitor(options.ladder_id, document.getElementById('existing_team').value, 'teams');
          }
        } else {
          registerCompetitor(options.ladder_id, userId, 'players');
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

/***/ 49:
/*!**************************************************!*\
  !*** multi ./src/js/admin/ladder-competitors.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\admin\ladder-competitors.js */"./src/js/admin/ladder-competitors.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL2FkbWluL2xhZGRlci1jb21wZXRpdG9ycy5qcyIsIndlYnBhY2s6Ly8vLi9zcmMvanMvdG91cm5hbWF0Y2guanMiXSwibmFtZXMiOlsiJCIsIndpbmRvdyIsImFkZEV2ZW50TGlzdGVuZXIiLCJvcHRpb25zIiwidHJuX2xhZGRlcl9jb21wZXRpdG9yc19vcHRpb25zIiwiYXV0b2NvbXBsZXRlIiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50QnlJZCIsInZhbCIsIlByb21pc2UiLCJyZXNvbHZlIiwicmVqZWN0IiwieGhyIiwiWE1MSHR0cFJlcXVlc3QiLCJvcGVuIiwiYXBpX3VybCIsInNldFJlcXVlc3RIZWFkZXIiLCJyZXN0X25vbmNlIiwib25sb2FkIiwic3RhdHVzIiwiSlNPTiIsInBhcnNlIiwicmVzcG9uc2UiLCJtYXAiLCJwbGF5ZXIiLCJuYW1lIiwic2VuZCIsInRvZ2dsZVRlYW1zIiwidGVhbVNlbGVjdGlvbiIsInNlbGVjdGVkVmFsdWUiLCJzZWxlY3RlZEluZGV4IiwidmFsdWUiLCJzdHlsZSIsImRpc3BsYXkiLCJyZXF1aXJlZCIsImNvbXBldGl0aW9uIiwiZXZlbnQiLCJwcmV2ZW50RGVmYXVsdCIsImUiLCJjb25zb2xlIiwibG9nIiwicCIsInBsYXllcnMiLCJsZW5ndGgiLCJpbm5lckhUTUwiLCJsYW5ndWFnZSIsImZhaWx1cmUiLCJub19jb21wZXRpdG9yIiwidGhlbiIsInVzZXJfaWQiLCJnZXRUZWFtcyIsImNvbnRlbnQiLCJ0ZWFtcyIsImkiLCJ0ZWFtIiwidGVhbV9pZCIsInplcm9fdGVhbXMiLCJyZWdpc3RlckNvbXBldGl0b3IiLCJjb21wZXRpdGlvbklkIiwiY29tcGV0aXRvcklkIiwiY29tcGV0aXRvclR5cGUiLCJzdWNjZXNzIiwic3VjY2Vzc19tZXNzYWdlIiwicmVzZXQiLCJtZXNzYWdlIiwicGFyYW0iLCJsYWRkZXJfaWQiLCJjb21wZXRpdG9yX2lkIiwiY29tcGV0aXRvcl90eXBlIiwidXNlcklkIiwicSIsImRhdGEiLCJ0YWciLCJ0ZWFtSWQiLCJ0cm4iLCJUb3VybmFtYXRjaCIsImV2ZW50cyIsIm9iamVjdCIsInByZWZpeCIsInN0ciIsInByb3AiLCJoYXNPd25Qcm9wZXJ0eSIsImsiLCJ2IiwicHVzaCIsImVuY29kZVVSSUNvbXBvbmVudCIsImpvaW4iLCJldmVudE5hbWUiLCJFdmVudFRhcmdldCIsImlucHV0IiwiZGF0YUNhbGxiYWNrIiwiVG91cm5hbWF0Y2hfQXV0b2NvbXBsZXRlIiwicyIsImNoYXJBdCIsInRvVXBwZXJDYXNlIiwic2xpY2UiLCJudW1iZXIiLCJyZW1haW5kZXIiLCJlbGVtZW50IiwidGFicyIsImdldEVsZW1lbnRzQnlDbGFzc05hbWUiLCJwYW5lcyIsImNsZWFyQWN0aXZlIiwiQXJyYXkiLCJwcm90b3R5cGUiLCJmb3JFYWNoIiwiY2FsbCIsInRhYiIsImNsYXNzTGlzdCIsInJlbW92ZSIsImFyaWFTZWxlY3RlZCIsInBhbmUiLCJzZXRBY3RpdmUiLCJ0YXJnZXRJZCIsInRhcmdldFRhYiIsInF1ZXJ5U2VsZWN0b3IiLCJ0YXJnZXRQYW5lSWQiLCJkYXRhc2V0IiwidGFyZ2V0IiwiYWRkIiwidGFiQ2xpY2siLCJjdXJyZW50VGFyZ2V0IiwibG9jYXRpb24iLCJoYXNoIiwic3Vic3RyIiwidHJuX29ial9pbnN0YW5jZSIsInRhYlZpZXdzIiwiZnJvbSIsImRyb3Bkb3ducyIsImhhbmRsZURyb3Bkb3duQ2xvc2UiLCJkcm9wZG93biIsIm5leHRFbGVtZW50U2libGluZyIsInJlbW92ZUV2ZW50TGlzdGVuZXIiLCJzdG9wUHJvcGFnYXRpb24iLCJuYW1lSW5wdXQiLCJhIiwiYiIsInBhcmVudCIsInBhcmVudE5vZGUiLCJjbG9zZUFsbExpc3RzIiwiY3VycmVudEZvY3VzIiwiY3JlYXRlRWxlbWVudCIsInNldEF0dHJpYnV0ZSIsImlkIiwiYXBwZW5kQ2hpbGQiLCJ0ZXh0Iiwic2VsZWN0ZWRJZCIsImRpc3BhdGNoRXZlbnQiLCJFdmVudCIsIngiLCJnZXRFbGVtZW50c0J5VGFnTmFtZSIsImtleUNvZGUiLCJhZGRBY3RpdmUiLCJjbGljayIsInJlbW92ZUFjdGl2ZSIsInJlbW92ZUNoaWxkIiwiU3RyaW5nIiwiZm9ybWF0IiwiYXJncyIsImFyZ3VtZW50cyIsInJlcGxhY2UiLCJtYXRjaCJdLCJtYXBwaW5ncyI6IjtRQUFBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBOzs7UUFHQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0EsMENBQTBDLGdDQUFnQztRQUMxRTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLHdEQUF3RCxrQkFBa0I7UUFDMUU7UUFDQSxpREFBaUQsY0FBYztRQUMvRDs7UUFFQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0EseUNBQXlDLGlDQUFpQztRQUMxRSxnSEFBZ0gsbUJBQW1CLEVBQUU7UUFDckk7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSwyQkFBMkIsMEJBQTBCLEVBQUU7UUFDdkQsaUNBQWlDLGVBQWU7UUFDaEQ7UUFDQTtRQUNBOztRQUVBO1FBQ0Esc0RBQXNELCtEQUErRDs7UUFFckg7UUFDQTs7O1FBR0E7UUFDQTs7Ozs7Ozs7Ozs7OztBQ2xGQTtBQUFBO0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUEsQ0FBQyxVQUFVQSxDQUFWLEVBQWE7QUFDVjs7QUFFQUMsUUFBTSxDQUFDQyxnQkFBUCxDQUF3QixNQUF4QixFQUFnQyxZQUFZO0FBQ3hDLFFBQUlDLE9BQU8sR0FBR0MsOEJBQWQsQ0FEd0MsQ0FHeEM7O0FBQ0FKLEtBQUMsQ0FBQ0ssWUFBRixDQUFlQyxRQUFRLENBQUNDLGNBQVQsQ0FBd0IsZUFB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QkFBVCxDQUFnQyx5QkFBaEMsQ0FBUjs7QUFDQSxXQUFLLElBQUkvQyxDQUFDLEdBQUcsQ0FBYixFQUFnQkEsQ0FBQyxHQUFHZ0csQ0FBQyxDQUFDMUcsTUFBdEIsRUFBOEJVLENBQUMsRUFBL0IsRUFBbUM7QUFDL0IsWUFBSTZDLE9BQU8sS0FBS21ELENBQUMsQ0FBQ2hHLENBQUQsQ0FBYixJQUFvQjZDLE9BQU8sS0FBSyxLQUFLb0MsU0FBekMsRUFBb0Q7QUFDaERlLFdBQUMsQ0FBQ2hHLENBQUQsQ0FBRCxDQUFLcUYsVUFBTCxDQUFnQmlCLFdBQWhCLENBQTRCTixDQUFDLENBQUNoRyxDQUFELENBQTdCO0FBQ0g7QUFDSjtBQUNKOzs7O0tBR0w7OztBQUNBLElBQUksQ0FBQ3VHLE1BQU0sQ0FBQ3BELFNBQVAsQ0FBaUJxRCxNQUF0QixFQUE4QjtBQUMxQkQsUUFBTSxDQUFDcEQsU0FBUCxDQUFpQnFELE1BQWpCLEdBQTBCLFlBQVc7QUFDakMsUUFBTUMsSUFBSSxHQUFHQyxTQUFiO0FBQ0EsV0FBTyxLQUFLQyxPQUFMLENBQWEsVUFBYixFQUF5QixVQUFTQyxLQUFULEVBQWdCakUsTUFBaEIsRUFBd0I7QUFDcEQsYUFBTyxPQUFPOEQsSUFBSSxDQUFDOUQsTUFBRCxDQUFYLEtBQXdCLFdBQXhCLEdBQ0Q4RCxJQUFJLENBQUM5RCxNQUFELENBREgsR0FFRGlFLEtBRk47QUFJSCxLQUxNLENBQVA7QUFNSCxHQVJEO0FBU0gsQyIsImZpbGUiOiJsYWRkZXItY29tcGV0aXRvcnMuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gNDkpO1xuIiwiLyoqXHJcbiAqIEFkbWluIG1hbmFnZSBsYWRkZXIgY29tcGV0aXRvcnMgcGFnZS5cclxuICpcclxuICogQGxpbmsgICAgICAgaHR0cHM6Ly93d3cudG91cm5hbWF0Y2guY29tXHJcbiAqIEBzaW5jZSAgICAgIDQuNi4wXHJcbiAqXHJcbiAqIEBwYWNrYWdlICAgIFRvdXJuYW1hdGNoXHJcbiAqXHJcbiAqL1xyXG5pbXBvcnQgeyB0cm4gfSBmcm9tICcuLy4uL3RvdXJuYW1hdGNoLmpzJztcclxuXHJcbihmdW5jdGlvbiAoJCkge1xyXG4gICAgJ3VzZSBzdHJpY3QnO1xyXG5cclxuICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIGxldCBvcHRpb25zID0gdHJuX2xhZGRlcl9jb21wZXRpdG9yc19vcHRpb25zO1xyXG5cclxuICAgICAgICAvLyBpbnRpYWxpemUgYXV0byBjb21wbGV0ZVxyXG4gICAgICAgICQuYXV0b2NvbXBsZXRlKGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdjb21wZXRpdG9yX2lkJyksIGZ1bmN0aW9uKHZhbCkge1xyXG4gICAgICAgICAgICByZXR1cm4gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xyXG4gICAgICAgICAgICAgICAgLyogbmVlZCB0byBxdWVyeSBzZXJ2ZXIgZm9yIG5hbWVzIGhlcmUuICovXHJcbiAgICAgICAgICAgICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgICAgICB4aHIub3BlbignR0VUJywgb3B0aW9ucy5hcGlfdXJsICsgJ3BsYXllcnMvP3NlYXJjaD0nICsgdmFsICsgJyZwZXJfcGFnZT01Jyk7XHJcbiAgICAgICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xyXG4gICAgICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ1gtV1AtTm9uY2UnLCBvcHRpb25zLnJlc3Rfbm9uY2UpO1xyXG4gICAgICAgICAgICAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vIHJlc29sdmUoSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpLm1hcCgocGxheWVyKSA9PiB7cmV0dXJuIHsgJ3ZhbHVlJzogcGxheWVyLmlkLCAndGV4dCc6IHBsYXllci5uYW1lIH07fSkpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZXNvbHZlKEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKS5tYXAoKHBsYXllcikgPT4ge3JldHVybiBwbGF5ZXIubmFtZTt9KSk7XHJcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmVqZWN0KCk7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfTtcclxuICAgICAgICAgICAgICAgIHhoci5zZW5kKCk7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICAvLyB0b2dnbGUgbmV3IG9yIHNlbGVjdFxyXG4gICAgICAgIGZ1bmN0aW9uIHRvZ2dsZVRlYW1zKCkge1xyXG4gICAgICAgICAgICBsZXQgdGVhbVNlbGVjdGlvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCduZXdfb3JfZXhpc3RpbmcnKTtcclxuICAgICAgICAgICAgbGV0IHNlbGVjdGVkVmFsdWUgPSB0ZWFtU2VsZWN0aW9uLm9wdGlvbnNbdGVhbVNlbGVjdGlvbi5zZWxlY3RlZEluZGV4XS52YWx1ZTtcclxuXHJcbiAgICAgICAgICAgIGlmIChzZWxlY3RlZFZhbHVlID09PSAnbmV3Jykge1xyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3RhZ19yb3cnKS5zdHlsZS5kaXNwbGF5ID0gJ3RhYmxlLXJvdyc7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndGVhbV90YWcnKS5yZXF1aXJlZCA9IHRydWU7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbmFtZV9yb3cnKS5zdHlsZS5kaXNwbGF5ID0gJ3RhYmxlLXJvdyc7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndGVhbV9uYW1lJykucmVxdWlyZWQgPSB0cnVlO1xyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2V4aXN0aW5nX3JvdycpLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZXhpc3RpbmdfdGVhbScpLnJlcXVpcmVkID0gZmFsc2U7XHJcbiAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndGFnX3JvdycpLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndGVhbV90YWcnKS5yZXF1aXJlZCA9IGZhbHNlO1xyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ25hbWVfcm93Jykuc3R5bGUuZGlzcGxheSA9ICdub25lJztcclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0ZWFtX25hbWUnKS5yZXF1aXJlZCA9IGZhbHNlO1xyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2V4aXN0aW5nX3JvdycpLnN0eWxlLmRpc3BsYXkgPSAndGFibGUtcm93JztcclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdleGlzdGluZ190ZWFtJykucmVxdWlyZWQgPSB0cnVlO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICAvLyBuZXcgb3IgZXhpc3RpbmcgZHJvcCBkb3duXHJcbiAgICAgICAgaWYgKG9wdGlvbnMuY29tcGV0aXRpb24gPT09ICd0ZWFtcycpIHtcclxuICAgICAgICAgICAgbGV0IHRlYW1TZWxlY3Rpb24gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbmV3X29yX2V4aXN0aW5nJyk7XHJcblxyXG4gICAgICAgICAgICB0ZWFtU2VsZWN0aW9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsIGZ1bmN0aW9uIChldmVudCkge1xyXG4gICAgICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgICAgIHRvZ2dsZVRlYW1zKCk7XHJcbiAgICAgICAgICAgIH0sIGZhbHNlKTtcclxuXHJcbiAgICAgICAgICAgIHRvZ2dsZVRlYW1zKCk7XHJcblxyXG4gICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnY29tcGV0aXRvcl9pZCcpLmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsIGZ1bmN0aW9uKGUpIHtcclxuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKGB2YWx1ZSBjaGFuZ2VkIHRvICR7dGhpcy52YWx1ZX1gKTtcclxuICAgICAgICAgICAgICAgIGxldCBwID0gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgICAgICAgICB4aHIub3BlbignR0VUJywgb3B0aW9ucy5hcGlfdXJsICsgJ3BsYXllcnMvP3NlYXJjaD0nICsgdGhpcy52YWx1ZSk7XHJcbiAgICAgICAgICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignWC1XUC1Ob25jZScsIG9wdGlvbnMucmVzdF9ub25jZSk7XHJcbiAgICAgICAgICAgICAgICAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2coeGhyLnJlc3BvbnNlKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29uc3QgcGxheWVycyA9IEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAocGxheWVycy5sZW5ndGggPiAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcmVzb2x2ZShwbGF5ZXJzWzBdWyd1c2VyX2lkJ10pO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tbGFkZGVyLWNvbXBldGl0b3JzLXJlc3BvbnNlJykuaW5uZXJIVE1MID0gYGA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tbGFkZGVyLWNvbXBldGl0b3JzLXJlc3BvbnNlJykuaW5uZXJIVE1MID0gYDxwIGNsYXNzPVwibm90aWNlIG5vdGljZS1lcnJvclwiPjxzdHJvbmc+JHtvcHRpb25zLmxhbmd1YWdlLmZhaWx1cmV9Ojwvc3Ryb25nPiAke29wdGlvbnMubGFuZ3VhZ2Uubm9fY29tcGV0aXRvcn08L3A+YDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJlamVjdCgpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgfTtcclxuICAgICAgICAgICAgICAgICAgICB4aHIuc2VuZCgpO1xyXG4gICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICAgICBwLnRoZW4oKHVzZXJfaWQpID0+IHtcclxuICAgICAgICAgICAgICAgICAgICBnZXRUZWFtcyh1c2VyX2lkKTtcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9LCBmYWxzZSk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICAvLyBnZXQgdGVhbXMgZm9yIHNpbmdsZSBwbGF5ZXJcclxuICAgICAgICBmdW5jdGlvbiBnZXRUZWFtcyh1c2VyX2lkKSB7XHJcbiAgICAgICAgICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgeGhyLm9wZW4oJ0dFVCcsIG9wdGlvbnMuYXBpX3VybCArIGBwbGF5ZXJzLyR7dXNlcl9pZH0vdGVhbXNgKTtcclxuICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ1gtV1AtTm9uY2UnLCBvcHRpb25zLnJlc3Rfbm9uY2UpO1xyXG4gICAgICAgICAgICB4aHIub25sb2FkID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coeGhyKTtcclxuICAgICAgICAgICAgICAgIGxldCBjb250ZW50ID0gYGA7XHJcbiAgICAgICAgICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IHRlYW1zID0gSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBpZiAodGVhbXMgIT09IG51bGwgJiYgdGVhbXMubGVuZ3RoID4gMCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBmb3IgKGxldCBpID0gMDsgaSA8IHRlYW1zLmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBsZXQgdGVhbSA9IHRlYW1zW2ldO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxvcHRpb24gdmFsdWU9XCIke3RlYW0udGVhbV9pZH1cIj4ke3RlYW0ubmFtZX08L29wdGlvbj5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPG9wdGlvbiB2YWx1ZT1cIi0xXCI+KCR7b3B0aW9ucy5sYW5ndWFnZS56ZXJvX3RlYW1zfSk8L29wdGlvbj5gO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPG9wdGlvbiB2YWx1ZT1cIi0xXCI+KCR7b3B0aW9ucy5sYW5ndWFnZS56ZXJvX3RlYW1zfSk8L29wdGlvbj5gO1xyXG4gICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdleGlzdGluZ190ZWFtJykuaW5uZXJIVE1MID0gY29udGVudDtcclxuICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgIHhoci5zZW5kKCk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBmdW5jdGlvbiByZWdpc3RlckNvbXBldGl0b3IoY29tcGV0aXRpb25JZCwgY29tcGV0aXRvcklkLCBjb21wZXRpdG9yVHlwZSkge1xyXG4gICAgICAgICAgICBjb25zb2xlLmxvZyhgcmVnaXN0ZXJpbmcgJHtjb21wZXRpdG9yVHlwZX0gd2l0aCBpZCAke2NvbXBldGl0b3JJZH0gdG8gY29tcGV0aXRpb24gd2l0aCBpZCAke2NvbXBldGl0aW9uSWR9YCk7XHJcblxyXG4gICAgICAgICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgIHhoci5vcGVuKCdQT1NUJywgb3B0aW9ucy5hcGlfdXJsICsgJ2xhZGRlci1jb21wZXRpdG9ycy8/YWRtaW49MScpO1xyXG4gICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xyXG4gICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignWC1XUC1Ob25jZScsIG9wdGlvbnMucmVzdF9ub25jZSk7XHJcbiAgICAgICAgICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyh4aHIpO1xyXG4gICAgICAgICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMSkge1xyXG4gICAgICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tbGFkZGVyLWNvbXBldGl0b3JzLXJlc3BvbnNlJykuaW5uZXJIVE1MID0gYDxwIGNsYXNzPVwibm90aWNlIG5vdGljZS1zdWNjZXNzXCI+PHN0cm9uZz4ke29wdGlvbnMubGFuZ3VhZ2Uuc3VjY2Vzc306PC9zdHJvbmc+ICR7b3B0aW9ucy5sYW5ndWFnZS5zdWNjZXNzX21lc3NhZ2V9PC9wPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Rybi1sYWRkZXItY29tcGV0aXRvcnMtZm9ybScpLnJlc2V0KCk7XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tbGFkZGVyLWNvbXBldGl0b3JzLXJlc3BvbnNlJykuaW5uZXJIVE1MID0gYDxwIGNsYXNzPVwibm90aWNlIG5vdGljZS1lcnJvclwiPjxzdHJvbmc+JHtvcHRpb25zLmxhbmd1YWdlLmZhaWx1cmV9Ojwvc3Ryb25nPiAke0pTT04ucGFyc2UoeGhyLnJlc3BvbnNlKS5tZXNzYWdlfTwvcD5gO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9O1xyXG5cclxuICAgICAgICAgICAgeGhyLnNlbmQoJC5wYXJhbSh7XHJcbiAgICAgICAgICAgICAgICBsYWRkZXJfaWQ6IGNvbXBldGl0aW9uSWQsXHJcbiAgICAgICAgICAgICAgICBjb21wZXRpdG9yX2lkOiBjb21wZXRpdG9ySWQsXHJcbiAgICAgICAgICAgICAgICBjb21wZXRpdG9yX3R5cGU6IGNvbXBldGl0b3JUeXBlLFxyXG4gICAgICAgICAgICB9KSk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLWxhZGRlci1jb21wZXRpdG9ycy1mb3JtJykuYWRkRXZlbnRMaXN0ZW5lcignc3VibWl0JywgZnVuY3Rpb24oZXZlbnQpIHtcclxuICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcclxuXHJcbiAgICAgICAgICAgIGxldCBwID0gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xyXG4gICAgICAgICAgICAgICAgbGV0IHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xyXG4gICAgICAgICAgICAgICAgeGhyLm9wZW4oJ0dFVCcsIG9wdGlvbnMuYXBpX3VybCArICdwbGF5ZXJzLz9zZWFyY2g9JyArIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdjb21wZXRpdG9yX2lkJykudmFsdWUpO1xyXG4gICAgICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2coeGhyLnJlc3BvbnNlKTtcclxuICAgICAgICAgICAgICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnN0IHBsYXllcnMgPSBKU09OLnBhcnNlKHhoci5yZXNwb25zZSk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAocGxheWVycy5sZW5ndGggPiAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXNvbHZlKHBsYXllcnNbMF1bJ3VzZXJfaWQnXSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLWxhZGRlci1jb21wZXRpdG9ycy1yZXNwb25zZScpLmlubmVySFRNTCA9IGBgO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Rybi1sYWRkZXItY29tcGV0aXRvcnMtcmVzcG9uc2UnKS5pbm5lckhUTUwgPSBgPHAgY2xhc3M9XCJub3RpY2Ugbm90aWNlLWVycm9yXCI+PHN0cm9uZz4ke29wdGlvbnMubGFuZ3VhZ2UuZmFpbHVyZX06PC9zdHJvbmc+ICR7b3B0aW9ucy5sYW5ndWFnZS5ub19jb21wZXRpdG9yfTwvcD5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgcmVqZWN0KCk7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfTtcclxuICAgICAgICAgICAgICAgIHhoci5zZW5kKCk7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICBwLnRoZW4oKHVzZXJJZCkgPT4ge1xyXG4gICAgICAgICAgICAgICAgaWYgKG9wdGlvbnMuY29tcGV0aXRpb24gPT09ICd0ZWFtcycpIHtcclxuICAgICAgICAgICAgICAgICAgICBsZXQgdGVhbVNlbGVjdGlvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCduZXdfb3JfZXhpc3RpbmcnKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKCB0ZWFtU2VsZWN0aW9uLnZhbHVlID09PSAnbmV3JyApIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbGV0IHEgPSBuZXcgUHJvbWlzZSgocmVzb2x2ZSwgcmVqZWN0KSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB4aHIub3BlbignUE9TVCcsIG9wdGlvbnMuYXBpX3VybCArICd0ZWFtcycpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXNvbHZlKEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKS5kYXRhLnRlYW1faWQpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJlamVjdCh4aHIpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgeGhyLnNlbmQoJC5wYXJhbSh7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdXNlcl9pZDogdXNlcklkLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5hbWU6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0ZWFtX25hbWUnKS52YWx1ZSxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB0YWc6IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0ZWFtX3RhZycpLnZhbHVlXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9KSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBxLnRoZW4oKHRlYW1JZCkgPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmVnaXN0ZXJDb21wZXRpdG9yKG9wdGlvbnMubGFkZGVyX2lkLCB0ZWFtSWQsICd0ZWFtcycpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICByZWdpc3RlckNvbXBldGl0b3Iob3B0aW9ucy5sYWRkZXJfaWQsIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdleGlzdGluZ190ZWFtJykudmFsdWUsICd0ZWFtcycpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcmVnaXN0ZXJDb21wZXRpdG9yKG9wdGlvbnMubGFkZGVyX2lkLCB1c2VySWQsICdwbGF5ZXJzJyk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0pO1xyXG5cclxuICAgICAgICAgICAgY29uc29sZS5sb2coJ3N1Ym1pdHRlZCcpO1xyXG4gICAgICAgIH0sIGZhbHNlKTtcclxuXHJcbiAgICB9LCBmYWxzZSk7XHJcbn0pKHRybik7IiwiJ3VzZSBzdHJpY3QnO1xyXG5jbGFzcyBUb3VybmFtYXRjaCB7XHJcblxyXG4gICAgY29uc3RydWN0b3IoKSB7XHJcbiAgICAgICAgdGhpcy5ldmVudHMgPSB7fTtcclxuICAgIH1cclxuXHJcbiAgICBwYXJhbShvYmplY3QsIHByZWZpeCkge1xyXG4gICAgICAgIGxldCBzdHIgPSBbXTtcclxuICAgICAgICBmb3IgKGxldCBwcm9wIGluIG9iamVjdCkge1xyXG4gICAgICAgICAgICBpZiAob2JqZWN0Lmhhc093blByb3BlcnR5KHByb3ApKSB7XHJcbiAgICAgICAgICAgICAgICBsZXQgayA9IHByZWZpeCA/IHByZWZpeCArIFwiW1wiICsgcHJvcCArIFwiXVwiIDogcHJvcDtcclxuICAgICAgICAgICAgICAgIGxldCB2ID0gb2JqZWN0W3Byb3BdO1xyXG4gICAgICAgICAgICAgICAgc3RyLnB1c2goKHYgIT09IG51bGwgJiYgdHlwZW9mIHYgPT09IFwib2JqZWN0XCIpID8gdGhpcy5wYXJhbSh2LCBrKSA6IGVuY29kZVVSSUNvbXBvbmVudChrKSArIFwiPVwiICsgZW5jb2RlVVJJQ29tcG9uZW50KHYpKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gc3RyLmpvaW4oXCImXCIpO1xyXG4gICAgfVxyXG5cclxuICAgIGV2ZW50KGV2ZW50TmFtZSkge1xyXG4gICAgICAgIGlmICghKGV2ZW50TmFtZSBpbiB0aGlzLmV2ZW50cykpIHtcclxuICAgICAgICAgICAgdGhpcy5ldmVudHNbZXZlbnROYW1lXSA9IG5ldyBFdmVudFRhcmdldChldmVudE5hbWUpO1xyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gdGhpcy5ldmVudHNbZXZlbnROYW1lXTtcclxuICAgIH1cclxuXHJcbiAgICBhdXRvY29tcGxldGUoaW5wdXQsIGRhdGFDYWxsYmFjaykge1xyXG4gICAgICAgIG5ldyBUb3VybmFtYXRjaF9BdXRvY29tcGxldGUoaW5wdXQsIGRhdGFDYWxsYmFjayk7XHJcbiAgICB9XHJcblxyXG4gICAgdWNmaXJzdChzKSB7XHJcbiAgICAgICAgaWYgKHR5cGVvZiBzICE9PSAnc3RyaW5nJykgcmV0dXJuICcnO1xyXG4gICAgICAgIHJldHVybiBzLmNoYXJBdCgwKS50b1VwcGVyQ2FzZSgpICsgcy5zbGljZSgxKTtcclxuICAgIH1cclxuXHJcbiAgICBvcmRpbmFsX3N1ZmZpeChudW1iZXIpIHtcclxuICAgICAgICBjb25zdCByZW1haW5kZXIgPSBudW1iZXIgJSAxMDA7XHJcblxyXG4gICAgICAgIGlmICgocmVtYWluZGVyIDwgMTEpIHx8IChyZW1haW5kZXIgPiAxMykpIHtcclxuICAgICAgICAgICAgc3dpdGNoIChyZW1haW5kZXIgJSAxMCkge1xyXG4gICAgICAgICAgICAgICAgY2FzZSAxOiByZXR1cm4gJ3N0JztcclxuICAgICAgICAgICAgICAgIGNhc2UgMjogcmV0dXJuICduZCc7XHJcbiAgICAgICAgICAgICAgICBjYXNlIDM6IHJldHVybiAncmQnO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiAndGgnO1xyXG4gICAgfVxyXG5cclxuICAgIHRhYnMoZWxlbWVudCkge1xyXG4gICAgICAgIGNvbnN0IHRhYnMgPSBlbGVtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1uYXYtbGluaycpO1xyXG4gICAgICAgIGNvbnN0IHBhbmVzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLXRhYi1wYW5lJyk7XHJcbiAgICAgICAgY29uc3QgY2xlYXJBY3RpdmUgPSAoKSA9PiB7XHJcbiAgICAgICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwodGFicywgKHRhYikgPT4ge1xyXG4gICAgICAgICAgICAgICAgdGFiLmNsYXNzTGlzdC5yZW1vdmUoJ3Rybi1uYXYtYWN0aXZlJyk7XHJcbiAgICAgICAgICAgICAgICB0YWIuYXJpYVNlbGVjdGVkID0gZmFsc2U7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHBhbmVzLCBwYW5lID0+IHBhbmUuY2xhc3NMaXN0LnJlbW92ZSgndHJuLXRhYi1hY3RpdmUnKSk7XHJcbiAgICAgICAgfTtcclxuICAgICAgICBjb25zdCBzZXRBY3RpdmUgPSAodGFyZ2V0SWQpID0+IHtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0VGFiID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignYVtocmVmPVwiIycgKyB0YXJnZXRJZCArICdcIl0udHJuLW5hdi1saW5rJyk7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFBhbmVJZCA9IHRhcmdldFRhYiAmJiB0YXJnZXRUYWIuZGF0YXNldCAmJiB0YXJnZXRUYWIuZGF0YXNldC50YXJnZXQgfHwgZmFsc2U7XHJcblxyXG4gICAgICAgICAgICBpZiAodGFyZ2V0UGFuZUlkKSB7XHJcbiAgICAgICAgICAgICAgICBjbGVhckFjdGl2ZSgpO1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0VGFiLmNsYXNzTGlzdC5hZGQoJ3Rybi1uYXYtYWN0aXZlJyk7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRUYWIuYXJpYVNlbGVjdGVkID0gdHJ1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0YXJnZXRQYW5lSWQpLmNsYXNzTGlzdC5hZGQoJ3Rybi10YWItYWN0aXZlJyk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9O1xyXG4gICAgICAgIGNvbnN0IHRhYkNsaWNrID0gKGV2ZW50KSA9PiB7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFRhYiA9IGV2ZW50LmN1cnJlbnRUYXJnZXQ7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFBhbmVJZCA9IHRhcmdldFRhYiAmJiB0YXJnZXRUYWIuZGF0YXNldCAmJiB0YXJnZXRUYWIuZGF0YXNldC50YXJnZXQgfHwgZmFsc2U7XHJcblxyXG4gICAgICAgICAgICBpZiAodGFyZ2V0UGFuZUlkKSB7XHJcbiAgICAgICAgICAgICAgICBzZXRBY3RpdmUodGFyZ2V0UGFuZUlkKTtcclxuICAgICAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9O1xyXG5cclxuICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHRhYnMsICh0YWIpID0+IHtcclxuICAgICAgICAgICAgdGFiLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGFiQ2xpY2spO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICBpZiAobG9jYXRpb24uaGFzaCkge1xyXG4gICAgICAgICAgICBzZXRBY3RpdmUobG9jYXRpb24uaGFzaC5zdWJzdHIoMSkpO1xyXG4gICAgICAgIH0gZWxzZSBpZiAodGFicy5sZW5ndGggPiAwKSB7XHJcbiAgICAgICAgICAgIHNldEFjdGl2ZSh0YWJzWzBdLmRhdGFzZXQudGFyZ2V0KTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcblxyXG59XHJcblxyXG4vL3Rybi5pbml0aWFsaXplKCk7XHJcbmlmICghd2luZG93LnRybl9vYmpfaW5zdGFuY2UpIHtcclxuICAgIHdpbmRvdy50cm5fb2JqX2luc3RhbmNlID0gbmV3IFRvdXJuYW1hdGNoKCk7XHJcblxyXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCBmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgIGNvbnN0IHRhYlZpZXdzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLW5hdicpO1xyXG5cclxuICAgICAgICBBcnJheS5mcm9tKHRhYlZpZXdzKS5mb3JFYWNoKCh0YWIpID0+IHtcclxuICAgICAgICAgICAgdHJuLnRhYnModGFiKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgY29uc3QgZHJvcGRvd25zID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLWRyb3Bkb3duLXRvZ2dsZScpO1xyXG4gICAgICAgIGNvbnN0IGhhbmRsZURyb3Bkb3duQ2xvc2UgPSAoKSA9PiB7XHJcbiAgICAgICAgICAgIEFycmF5LmZyb20oZHJvcGRvd25zKS5mb3JFYWNoKChkcm9wZG93bikgPT4ge1xyXG4gICAgICAgICAgICAgICAgZHJvcGRvd24ubmV4dEVsZW1lbnRTaWJsaW5nLmNsYXNzTGlzdC5yZW1vdmUoJ3Rybi1zaG93Jyk7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICBkb2N1bWVudC5yZW1vdmVFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgaGFuZGxlRHJvcGRvd25DbG9zZSwgZmFsc2UpO1xyXG4gICAgICAgIH07XHJcblxyXG4gICAgICAgIEFycmF5LmZyb20oZHJvcGRvd25zKS5mb3JFYWNoKChkcm9wZG93bikgPT4ge1xyXG4gICAgICAgICAgICBkcm9wZG93bi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uKGUpIHtcclxuICAgICAgICAgICAgICAgIGUuc3RvcFByb3BhZ2F0aW9uKCk7XHJcbiAgICAgICAgICAgICAgICB0aGlzLm5leHRFbGVtZW50U2libGluZy5jbGFzc0xpc3QuYWRkKCd0cm4tc2hvdycpO1xyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGhhbmRsZURyb3Bkb3duQ2xvc2UsIGZhbHNlKTtcclxuICAgICAgICAgICAgfSwgZmFsc2UpO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgIH0sIGZhbHNlKTtcclxufVxyXG5leHBvcnQgbGV0IHRybiA9IHdpbmRvdy50cm5fb2JqX2luc3RhbmNlO1xyXG5cclxuY2xhc3MgVG91cm5hbWF0Y2hfQXV0b2NvbXBsZXRlIHtcclxuXHJcbiAgICAvLyBjdXJyZW50Rm9jdXM7XHJcbiAgICAvL1xyXG4gICAgLy8gbmFtZUlucHV0O1xyXG4gICAgLy9cclxuICAgIC8vIHNlbGY7XHJcblxyXG4gICAgY29uc3RydWN0b3IoaW5wdXQsIGRhdGFDYWxsYmFjaykge1xyXG4gICAgICAgIC8vIHRoaXMuc2VsZiA9IHRoaXM7XHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQgPSBpbnB1dDtcclxuXHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcihcImlucHV0XCIsICgpID0+IHtcclxuICAgICAgICAgICAgbGV0IGEsIGIsIGksIHZhbCA9IHRoaXMubmFtZUlucHV0LnZhbHVlOy8vdGhpcy52YWx1ZTtcclxuICAgICAgICAgICAgbGV0IHBhcmVudCA9IHRoaXMubmFtZUlucHV0LnBhcmVudE5vZGU7Ly90aGlzLnBhcmVudE5vZGU7XHJcblxyXG4gICAgICAgICAgICAvLyBsZXQgcCA9IG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpID0+IHtcclxuICAgICAgICAgICAgLy8gICAgIC8qIG5lZWQgdG8gcXVlcnkgc2VydmVyIGZvciBuYW1lcyBoZXJlLiAqL1xyXG4gICAgICAgICAgICAvLyAgICAgbGV0IHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLm9wZW4oJ0dFVCcsIG9wdGlvbnMuYXBpX3VybCArICdwbGF5ZXJzLz9zZWFyY2g9JyArIHZhbCArICcmcGVyX3BhZ2U9NScpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMCkge1xyXG4gICAgICAgICAgICAvLyAgICAgICAgICAgICAvLyByZXNvbHZlKEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKS5tYXAoKHBsYXllcikgPT4ge3JldHVybiB7ICd2YWx1ZSc6IHBsYXllci5pZCwgJ3RleHQnOiBwbGF5ZXIubmFtZSB9O30pKTtcclxuICAgICAgICAgICAgLy8gICAgICAgICAgICAgcmVzb2x2ZShKU09OLnBhcnNlKHhoci5yZXNwb25zZSkubWFwKChwbGF5ZXIpID0+IHtyZXR1cm4gcGxheWVyLm5hbWU7fSkpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgICAgIHJlamVjdCgpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gICAgIH07XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIuc2VuZCgpO1xyXG4gICAgICAgICAgICAvLyB9KTtcclxuICAgICAgICAgICAgZGF0YUNhbGxiYWNrKHZhbCkudGhlbigoZGF0YSkgPT4gey8vcC50aGVuKChkYXRhKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhkYXRhKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmNsb3NlIGFueSBhbHJlYWR5IG9wZW4gbGlzdHMgb2YgYXV0by1jb21wbGV0ZWQgdmFsdWVzKi9cclxuICAgICAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cygpO1xyXG4gICAgICAgICAgICAgICAgaWYgKCF2YWwpIHsgcmV0dXJuIGZhbHNlO31cclxuICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudEZvY3VzID0gLTE7XHJcblxyXG4gICAgICAgICAgICAgICAgLypjcmVhdGUgYSBESVYgZWxlbWVudCB0aGF0IHdpbGwgY29udGFpbiB0aGUgaXRlbXMgKHZhbHVlcyk6Ki9cclxuICAgICAgICAgICAgICAgIGEgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiRElWXCIpO1xyXG4gICAgICAgICAgICAgICAgYS5zZXRBdHRyaWJ1dGUoXCJpZFwiLCB0aGlzLm5hbWVJbnB1dC5pZCArIFwiLWF1dG8tY29tcGxldGUtbGlzdFwiKTtcclxuICAgICAgICAgICAgICAgIGEuc2V0QXR0cmlidXRlKFwiY2xhc3NcIiwgXCJ0cm4tYXV0by1jb21wbGV0ZS1pdGVtc1wiKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmFwcGVuZCB0aGUgRElWIGVsZW1lbnQgYXMgYSBjaGlsZCBvZiB0aGUgYXV0by1jb21wbGV0ZSBjb250YWluZXI6Ki9cclxuICAgICAgICAgICAgICAgIHBhcmVudC5hcHBlbmRDaGlsZChhKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmZvciBlYWNoIGl0ZW0gaW4gdGhlIGFycmF5Li4uKi9cclxuICAgICAgICAgICAgICAgIGZvciAoaSA9IDA7IGkgPCBkYXRhLmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IHRleHQsIHZhbHVlO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAvKiBXaGljaCBmb3JtYXQgZGlkIHRoZXkgZ2l2ZSB1cy4gKi9cclxuICAgICAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGRhdGFbaV0gPT09ICdvYmplY3QnKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHRleHQgPSBkYXRhW2ldWyd0ZXh0J107XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlID0gZGF0YVtpXVsndmFsdWUnXTtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB0ZXh0ID0gZGF0YVtpXTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSBkYXRhW2ldO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICAgICAgLypjaGVjayBpZiB0aGUgaXRlbSBzdGFydHMgd2l0aCB0aGUgc2FtZSBsZXR0ZXJzIGFzIHRoZSB0ZXh0IGZpZWxkIHZhbHVlOiovXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHRleHQuc3Vic3RyKDAsIHZhbC5sZW5ndGgpLnRvVXBwZXJDYXNlKCkgPT09IHZhbC50b1VwcGVyQ2FzZSgpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qY3JlYXRlIGEgRElWIGVsZW1lbnQgZm9yIGVhY2ggbWF0Y2hpbmcgZWxlbWVudDoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcIkRJVlwiKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgLyptYWtlIHRoZSBtYXRjaGluZyBsZXR0ZXJzIGJvbGQ6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgPSBcIjxzdHJvbmc+XCIgKyB0ZXh0LnN1YnN0cigwLCB2YWwubGVuZ3RoKSArIFwiPC9zdHJvbmc+XCI7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuaW5uZXJIVE1MICs9IHRleHQuc3Vic3RyKHZhbC5sZW5ndGgpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgLyppbnNlcnQgYSBpbnB1dCBmaWVsZCB0aGF0IHdpbGwgaG9sZCB0aGUgY3VycmVudCBhcnJheSBpdGVtJ3MgdmFsdWU6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgKz0gXCI8aW5wdXQgdHlwZT0naGlkZGVuJyB2YWx1ZT0nXCIgKyB2YWx1ZSArIFwiJz5cIjtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuZGF0YXNldC52YWx1ZSA9IHZhbHVlO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmRhdGFzZXQudGV4dCA9IHRleHQ7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiB3aGVuIHNvbWVvbmUgY2xpY2tzIG9uIHRoZSBpdGVtIHZhbHVlIChESVYgZWxlbWVudCk6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKGUpID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKGBpdGVtIGNsaWNrZWQgd2l0aCB2YWx1ZSAke2UuY3VycmVudFRhcmdldC5kYXRhc2V0LnZhbHVlfWApO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8qIGluc2VydCB0aGUgdmFsdWUgZm9yIHRoZSBhdXRvY29tcGxldGUgdGV4dCBmaWVsZDogKi9cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LnZhbHVlID0gZS5jdXJyZW50VGFyZ2V0LmRhdGFzZXQudGV4dDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LmRhdGFzZXQuc2VsZWN0ZWRJZCA9IGUuY3VycmVudFRhcmdldC5kYXRhc2V0LnZhbHVlO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8qIGNsb3NlIHRoZSBsaXN0IG9mIGF1dG9jb21wbGV0ZWQgdmFsdWVzLCAob3IgYW55IG90aGVyIG9wZW4gbGlzdHMgb2YgYXV0b2NvbXBsZXRlZCB2YWx1ZXM6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cygpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LmRpc3BhdGNoRXZlbnQobmV3IEV2ZW50KCdjaGFuZ2UnKSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBhLmFwcGVuZENoaWxkKGIpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHByZXNzZXMgYSBrZXkgb24gdGhlIGtleWJvYXJkOiovXHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcihcImtleWRvd25cIiwgKGUpID0+IHtcclxuICAgICAgICAgICAgbGV0IHggPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0aGlzLm5hbWVJbnB1dC5pZCArIFwiLWF1dG8tY29tcGxldGUtbGlzdFwiKTtcclxuICAgICAgICAgICAgaWYgKHgpIHggPSB4LmdldEVsZW1lbnRzQnlUYWdOYW1lKFwiZGl2XCIpO1xyXG4gICAgICAgICAgICBpZiAoZS5rZXlDb2RlID09PSA0MCkge1xyXG4gICAgICAgICAgICAgICAgLypJZiB0aGUgYXJyb3cgRE9XTiBrZXkgaXMgcHJlc3NlZCxcclxuICAgICAgICAgICAgICAgICBpbmNyZWFzZSB0aGUgY3VycmVudEZvY3VzIHZhcmlhYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmN1cnJlbnRGb2N1cysrO1xyXG4gICAgICAgICAgICAgICAgLyphbmQgYW5kIG1ha2UgdGhlIGN1cnJlbnQgaXRlbSBtb3JlIHZpc2libGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuYWRkQWN0aXZlKHgpO1xyXG4gICAgICAgICAgICB9IGVsc2UgaWYgKGUua2V5Q29kZSA9PT0gMzgpIHsgLy91cFxyXG4gICAgICAgICAgICAgICAgLypJZiB0aGUgYXJyb3cgVVAga2V5IGlzIHByZXNzZWQsXHJcbiAgICAgICAgICAgICAgICAgZGVjcmVhc2UgdGhlIGN1cnJlbnRGb2N1cyB2YXJpYWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50Rm9jdXMtLTtcclxuICAgICAgICAgICAgICAgIC8qYW5kIGFuZCBtYWtlIHRoZSBjdXJyZW50IGl0ZW0gbW9yZSB2aXNpYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmFkZEFjdGl2ZSh4KTtcclxuICAgICAgICAgICAgfSBlbHNlIGlmIChlLmtleUNvZGUgPT09IDEzKSB7XHJcbiAgICAgICAgICAgICAgICAvKklmIHRoZSBFTlRFUiBrZXkgaXMgcHJlc3NlZCwgcHJldmVudCB0aGUgZm9ybSBmcm9tIGJlaW5nIHN1Ym1pdHRlZCwqL1xyXG4gICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzID4gLTEpIHtcclxuICAgICAgICAgICAgICAgICAgICAvKmFuZCBzaW11bGF0ZSBhIGNsaWNrIG9uIHRoZSBcImFjdGl2ZVwiIGl0ZW06Ki9cclxuICAgICAgICAgICAgICAgICAgICBpZiAoeCkgeFt0aGlzLmN1cnJlbnRGb2N1c10uY2xpY2soKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiB3aGVuIHNvbWVvbmUgY2xpY2tzIGluIHRoZSBkb2N1bWVudDoqL1xyXG4gICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICB0aGlzLmNsb3NlQWxsTGlzdHMoZS50YXJnZXQpO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG5cclxuICAgIGFkZEFjdGl2ZSh4KSB7XHJcbiAgICAgICAgLyphIGZ1bmN0aW9uIHRvIGNsYXNzaWZ5IGFuIGl0ZW0gYXMgXCJhY3RpdmVcIjoqL1xyXG4gICAgICAgIGlmICgheCkgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgIC8qc3RhcnQgYnkgcmVtb3ZpbmcgdGhlIFwiYWN0aXZlXCIgY2xhc3Mgb24gYWxsIGl0ZW1zOiovXHJcbiAgICAgICAgdGhpcy5yZW1vdmVBY3RpdmUoeCk7XHJcbiAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzID49IHgubGVuZ3RoKSB0aGlzLmN1cnJlbnRGb2N1cyA9IDA7XHJcbiAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzIDwgMCkgdGhpcy5jdXJyZW50Rm9jdXMgPSAoeC5sZW5ndGggLSAxKTtcclxuICAgICAgICAvKmFkZCBjbGFzcyBcImF1dG9jb21wbGV0ZS1hY3RpdmVcIjoqL1xyXG4gICAgICAgIHhbdGhpcy5jdXJyZW50Rm9jdXNdLmNsYXNzTGlzdC5hZGQoXCJ0cm4tYXV0by1jb21wbGV0ZS1hY3RpdmVcIik7XHJcbiAgICB9XHJcblxyXG4gICAgcmVtb3ZlQWN0aXZlKHgpIHtcclxuICAgICAgICAvKmEgZnVuY3Rpb24gdG8gcmVtb3ZlIHRoZSBcImFjdGl2ZVwiIGNsYXNzIGZyb20gYWxsIGF1dG9jb21wbGV0ZSBpdGVtczoqL1xyXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgeC5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICB4W2ldLmNsYXNzTGlzdC5yZW1vdmUoXCJ0cm4tYXV0by1jb21wbGV0ZS1hY3RpdmVcIik7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIGNsb3NlQWxsTGlzdHMoZWxlbWVudCkge1xyXG4gICAgICAgIGNvbnNvbGUubG9nKFwiY2xvc2UgYWxsIGxpc3RzXCIpO1xyXG4gICAgICAgIC8qY2xvc2UgYWxsIGF1dG9jb21wbGV0ZSBsaXN0cyBpbiB0aGUgZG9jdW1lbnQsXHJcbiAgICAgICAgIGV4Y2VwdCB0aGUgb25lIHBhc3NlZCBhcyBhbiBhcmd1bWVudDoqL1xyXG4gICAgICAgIGxldCB4ID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShcInRybi1hdXRvLWNvbXBsZXRlLWl0ZW1zXCIpO1xyXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgeC5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICBpZiAoZWxlbWVudCAhPT0geFtpXSAmJiBlbGVtZW50ICE9PSB0aGlzLm5hbWVJbnB1dCkge1xyXG4gICAgICAgICAgICAgICAgeFtpXS5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKHhbaV0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG59XHJcblxyXG4vLyBGaXJzdCwgY2hlY2tzIGlmIGl0IGlzbid0IGltcGxlbWVudGVkIHlldC5cclxuaWYgKCFTdHJpbmcucHJvdG90eXBlLmZvcm1hdCkge1xyXG4gICAgU3RyaW5nLnByb3RvdHlwZS5mb3JtYXQgPSBmdW5jdGlvbigpIHtcclxuICAgICAgICBjb25zdCBhcmdzID0gYXJndW1lbnRzO1xyXG4gICAgICAgIHJldHVybiB0aGlzLnJlcGxhY2UoL3soXFxkKyl9L2csIGZ1bmN0aW9uKG1hdGNoLCBudW1iZXIpIHtcclxuICAgICAgICAgICAgcmV0dXJuIHR5cGVvZiBhcmdzW251bWJlcl0gIT09ICd1bmRlZmluZWQnXHJcbiAgICAgICAgICAgICAgICA/IGFyZ3NbbnVtYmVyXVxyXG4gICAgICAgICAgICAgICAgOiBtYXRjaFxyXG4gICAgICAgICAgICAgICAgO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfTtcclxufSJdLCJzb3VyY2VSb290IjoiIn0=