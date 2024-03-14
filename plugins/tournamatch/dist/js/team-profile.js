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
/******/ 	return __webpack_require__(__webpack_require__.s = 35);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/team-profile.js":
/*!********************************!*\
  !*** ./src/js/team-profile.js ***!
  \********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./tournamatch.js */ "./src/js/tournamatch.js");
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

/**
 * Team profile page.
 *
 * @link       https://www.tournamatch.com
 * @since      3.8.0
 *
 * @package    Tournamatch
 *
 */


(function ($) {
  'use strict'; // add listener for roster changed event

  window.addEventListener('load', function () {
    var options = trn_team_profile_options;
    var joinTeamButton = document.getElementById('trn-join-team-button');
    var leaveTeamButton = document.getElementById('trn-leave-team-button');
    var deleteTeamButton = document.getElementById('trn-delete-team-button');
    var editTeamButton = document.getElementById('trn-edit-team-button');

    function canJoin(userId, members) {
      var isMember = false;

      if (members !== null && members.length > 0) {
        members.forEach(function (member) {
          isMember = isMember || member.user_id === userId;
        });
      }

      return !isMember;
    }

    function canLeave(userId, members) {
      var isMember = false;
      var isOwner = false;

      if (members !== null && members.length > 0) {
        members.forEach(function (member) {
          if (member.user_id === userId) {
            isMember = true;

            if (member.team_rank_id === 1) {
              isOwner = true;
            }
          }
        });
      }

      return isMember && !isOwner;
    }

    function canDelete(userId, members) {
      var isMember = false;

      if (members !== null && members.length > 0) {
        members.forEach(function (member) {
          isMember = member.user_id === userId || isMember;
        });
      }

      return isMember && members.length === 1;
    }

    function canEdit(userId, members) {
      var isOwner = false;

      if (members !== null && members.length > 0) {
        members.forEach(function (member) {
          isOwner = member.user_id === userId && member.team_rank_id === 1 || isOwner;
        });
      }

      return isOwner;
    }

    function getCurrentUserTeamMemberId(userId, members) {
      var teamMemberId = null;

      if (members !== null && members.length > 0) {
        members.forEach(function (member) {
          if (member.user_id === userId) {
            teamMemberId = member.team_member_id;
          }
        });
      }

      return teamMemberId;
    }

    function evaluateButtonStates(members) {
      var userId = parseInt(options.current_user_id);

      if (canDelete(userId, members)) {
        deleteTeamButton.style.display = 'inline-block';
        deleteTeamButton.dataset.teamMemberId = getCurrentUserTeamMemberId(userId, members);
      } else {
        deleteTeamButton.style.display = 'none';
      }

      if (canJoin(userId, members)) {
        joinTeamButton.style.display = 'inline-block';
      } else {
        joinTeamButton.style.display = 'none';
      }

      if (canLeave(userId, members)) {
        leaveTeamButton.style.display = 'inline-block';
        leaveTeamButton.dataset.teamMemberId = getCurrentUserTeamMemberId(userId, members);
      } else {
        leaveTeamButton.style.display = 'none';
      }

      if (options.can_edit || canEdit(userId, members)) {
        editTeamButton.style.display = 'inline-block';
      } else {
        editTeamButton.style.display = 'none';
      }
    }

    function getMembers() {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', options.api_url + 'team-members/?_embed&' + $.param({
        team_id: options.team_id
      }));
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        var content = '';

        if (xhr.status === 200) {
          var response = JSON.parse(xhr.response); // let memberLinks = [];
          // if (response !== null && response.length > 0) {
          //     Array.prototype.forEach.call(response, function (member) {
          //         memberLinks.push(`<a href="../players/${member.user_id}">${member._embedded.player[0].name}</a>`);
          //     });
          //
          //     content += memberLinks.join(', ');
          // } else {
          //     content += `<p class="trn-text-center">${options.language.zero_members}</p>`;
          // }

          if (options.is_logged_in) {
            evaluateButtonStates(response);
          }
        } else {
          content += "<p class=\"text-center\">".concat(options.language.error_members, "</p>");
        }

        var memberList = document.getElementById('trn-team-members-list');

        if (memberList) {
          memberList.innerHTML = content;
        }
      };

      xhr.send();
    }

    getMembers();
    $.event('team-members').addEventListener('changed', function () {
      getMembers();
    });

    function joinTeam() {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', options.api_url + 'team-requests');
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        var response = JSON.parse(xhr.response);

        if (xhr.status === 201) {
          document.getElementById('trn-join-team-response').innerHTML = "<div class=\"trn-alert trn-alert-success\"><strong>".concat(options.language.success, "!</strong> ").concat(options.language.success_message, "</div>");
        } else {
          document.getElementById('trn-join-team-response').innerHTML = "<div class=\"trn-alert trn-alert-danger\"><strong>".concat(options.language.failure, ":</strong> ").concat(response.message, "</div>");
        }
      };

      xhr.send($.param({
        team_id: document.getElementById('trn-join-team-button').dataset.teamId,
        user_id: document.getElementById('trn-join-team-button').dataset.userId
      }));
    }

    function handleJoinTeam(event) {
      joinTeam();
    }

    function leaveTeam() {
      var xhr = new XMLHttpRequest();
      xhr.open('DELETE', options.api_url + 'team-members/' + leaveTeamButton.dataset.teamMemberId);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        if (xhr.status === 204) {
          $.event('team-members').dispatchEvent(new CustomEvent('changed', {
            detail: {
              team_member_id: leaveTeamButton.dataset.teamMemberId
            }
          }));
        } else {
          var response = JSON.parse(xhr.response);
          document.getElementById('trn-leave-team-response').innerHTML = "<div class=\"trn-alert trn-alert-danger\"><strong>".concat(options.language.failure, ":</strong> ").concat(response.message, "</div>");
        }
      };

      xhr.send();
    }

    function deleteTeam() {
      var xhr = new XMLHttpRequest();
      xhr.open('DELETE', options.api_url + 'team-members/' + deleteTeamButton.dataset.teamMemberId);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        if (xhr.status === 204) {
          window.location.href = options.teams_url;
        } else {
          var response = JSON.parse(xhr.response);
          document.getElementById('trn-leave-team-response').innerHTML = "<div class=\"trn-alert trn-alert-danger\"><strong>".concat(options.language.failure, ":</strong> ").concat(response.message, "</div>");
        }
      };

      xhr.send();
    }

    if (options.is_logged_in) {
      joinTeamButton.addEventListener('click', handleJoinTeam, false);
      leaveTeamButton.addEventListener('click', leaveTeam);
      deleteTeamButton.addEventListener('click', deleteTeam);
    }
    /*the autocomplete function takes two arguments,
     the text field element and an array of possible autocompleted values:*/


    var addForm = document.getElementById('trn-add-player-form');
    var nameInput = document.getElementById('trn-add-player-input');
    var currentFocus;

    if (options.can_add === '1') {
      addForm.addEventListener('submit', function (event) {
        event.preventDefault();
        console.log('submitted');
        var p = new Promise(function (resolve, reject) {
          var xhr = new XMLHttpRequest();
          xhr.open('GET', options.api_url + 'players/?name=' + nameInput.value);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

          xhr.onload = function () {
            console.log(JSON.parse(xhr.response)[0]['user_id']);

            if (xhr.status === 200) {
              resolve(JSON.parse(xhr.response)[0]['user_id']);
            } else {
              reject();
            }
          };

          xhr.send();
        });
        p.then(function (user_id) {
          var xhr = new XMLHttpRequest();
          xhr.open('POST', options.api_url + 'team-members/');
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

          xhr.onload = function () {
            console.log(xhr);

            if (xhr.status === 201) {
              $.event('team-members').dispatchEvent(new CustomEvent('changed', {}));
            } else {
              var message = xhr.status === 403 ? JSON.parse(xhr.response).message : options.language.failure_message;
              document.getElementById('trn-add-player-response').innerHTML = "<div class=\"trn-alert trn-alert-danger\"><strong>".concat(options.language.failure, ":</strong> ").concat(message, "</div>");
            }
          };

          xhr.send($.param({
            team_id: options.team_id,
            user_id: user_id
          }));
        });
      }, false);
      /*execute a function when someone writes in the text field:*/

      nameInput.addEventListener("input", function (e) {
        var _this = this;

        var a,
            b,
            i,
            val = this.value;
        var parent = this.parentNode;
        var p = new Promise(function (resolve, reject) {
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
        p.then(function (data) {
          console.log(data);
          /*close any already open lists of autocompleted values*/

          closeAllLists();

          if (!val) {
            return false;
          }

          currentFocus = -1;
          /*create a DIV element that will contain the items (values):*/

          a = document.createElement("DIV");
          a.setAttribute("id", _this.id + "-auto-complete-list");
          a.setAttribute("class", "trn-auto-complete-items");
          /*append the DIV element as a child of the autocomplete container:*/

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
                /* insert the value for the autocomplete text field: */
                nameInput.value = this.dataset.text;
                nameInput.dataset.selectedId = this.dataset.value;
                /* close the list of autocompleted values, (or any other open lists of autocompleted values:*/

                closeAllLists();
              });
              a.appendChild(b);
            }
          }
        });
      });
      /*execute a function presses a key on the keyboard:*/

      nameInput.addEventListener("keydown", function (e) {
        var x = document.getElementById(this.id + "-auto-complete-list");
        if (x) x = x.getElementsByTagName("div");

        if (e.keyCode === 40) {
          /*If the arrow DOWN key is pressed,
           increase the currentFocus variable:*/
          currentFocus++;
          /*and and make the current item more visible:*/

          addActive(x);
        } else if (e.keyCode === 38) {
          //up

          /*If the arrow UP key is pressed,
           decrease the currentFocus variable:*/
          currentFocus--;
          /*and and make the current item more visible:*/

          addActive(x);
        } else if (e.keyCode === 13) {
          /*If the ENTER key is pressed, prevent the form from being submitted,*/
          e.preventDefault();

          if (currentFocus > -1) {
            /*and simulate a click on the "active" item:*/
            if (x) x[currentFocus].click();
          }
        }
      });
      /*execute a function when someone clicks in the document:*/

      document.addEventListener("click", function (e) {
        closeAllLists(e.target);
      });
    }

    function addActive(x) {
      /*a function to classify an item as "active":*/
      if (!x) return false;
      /*start by removing the "active" class on all items:*/

      removeActive(x);
      if (currentFocus >= x.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = x.length - 1;
      /*add class "autocomplete-active":*/

      x[currentFocus].classList.add("trn-auto-complete-active");
    }

    function removeActive(x) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("trn-auto-complete-active");
      }
    }

    function closeAllLists(elmnt) {
      console.log("close all lists");
      /*close all autocomplete lists in the document,
       except the one passed as an argument:*/

      var x = document.getElementsByClassName("trn-auto-complete-items");

      for (var i = 0; i < x.length; i++) {
        if (elmnt !== x[i] && elmnt !== nameInput) {
          x[i].parentNode.removeChild(x[i]);
        }
      }
    }
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

/***/ 35:
/*!**************************************!*\
  !*** multi ./src/js/team-profile.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\team-profile.js */"./src/js/team-profile.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL3RlYW0tcHJvZmlsZS5qcyIsIndlYnBhY2s6Ly8vLi9zcmMvanMvdG91cm5hbWF0Y2guanMiXSwibmFtZXMiOlsiJCIsIndpbmRvdyIsImFkZEV2ZW50TGlzdGVuZXIiLCJvcHRpb25zIiwidHJuX3RlYW1fcHJvZmlsZV9vcHRpb25zIiwiam9pblRlYW1CdXR0b24iLCJkb2N1bWVudCIsImdldEVsZW1lbnRCeUlkIiwibGVhdmVUZWFtQnV0dG9uIiwiZGVsZXRlVGVhbUJ1dHRvbiIsImVkaXRUZWFtQnV0dG9uIiwiY2FuSm9pbiIsInVzZXJJZCIsIm1lbWJlcnMiLCJpc01lbWJlciIsImxlbmd0aCIsImZvckVhY2giLCJtZW1iZXIiLCJ1c2VyX2lkIiwiY2FuTGVhdmUiLCJpc093bmVyIiwidGVhbV9yYW5rX2lkIiwiY2FuRGVsZXRlIiwiY2FuRWRpdCIsImdldEN1cnJlbnRVc2VyVGVhbU1lbWJlcklkIiwidGVhbU1lbWJlcklkIiwidGVhbV9tZW1iZXJfaWQiLCJldmFsdWF0ZUJ1dHRvblN0YXRlcyIsInBhcnNlSW50IiwiY3VycmVudF91c2VyX2lkIiwic3R5bGUiLCJkaXNwbGF5IiwiZGF0YXNldCIsImNhbl9lZGl0IiwiZ2V0TWVtYmVycyIsInhociIsIlhNTEh0dHBSZXF1ZXN0Iiwib3BlbiIsImFwaV91cmwiLCJwYXJhbSIsInRlYW1faWQiLCJzZXRSZXF1ZXN0SGVhZGVyIiwicmVzdF9ub25jZSIsIm9ubG9hZCIsImNvbnRlbnQiLCJzdGF0dXMiLCJyZXNwb25zZSIsIkpTT04iLCJwYXJzZSIsImlzX2xvZ2dlZF9pbiIsImxhbmd1YWdlIiwiZXJyb3JfbWVtYmVycyIsIm1lbWJlckxpc3QiLCJpbm5lckhUTUwiLCJzZW5kIiwiZXZlbnQiLCJqb2luVGVhbSIsInN1Y2Nlc3MiLCJzdWNjZXNzX21lc3NhZ2UiLCJmYWlsdXJlIiwibWVzc2FnZSIsInRlYW1JZCIsImhhbmRsZUpvaW5UZWFtIiwibGVhdmVUZWFtIiwiZGlzcGF0Y2hFdmVudCIsIkN1c3RvbUV2ZW50IiwiZGV0YWlsIiwiZGVsZXRlVGVhbSIsImxvY2F0aW9uIiwiaHJlZiIsInRlYW1zX3VybCIsImFkZEZvcm0iLCJuYW1lSW5wdXQiLCJjdXJyZW50Rm9jdXMiLCJjYW5fYWRkIiwicHJldmVudERlZmF1bHQiLCJjb25zb2xlIiwibG9nIiwicCIsIlByb21pc2UiLCJyZXNvbHZlIiwicmVqZWN0IiwidmFsdWUiLCJ0aGVuIiwiZmFpbHVyZV9tZXNzYWdlIiwiZSIsImEiLCJiIiwiaSIsInZhbCIsInBhcmVudCIsInBhcmVudE5vZGUiLCJtYXAiLCJwbGF5ZXIiLCJuYW1lIiwiZGF0YSIsImNsb3NlQWxsTGlzdHMiLCJjcmVhdGVFbGVtZW50Iiwic2V0QXR0cmlidXRlIiwiaWQiLCJhcHBlbmRDaGlsZCIsInRleHQiLCJzdWJzdHIiLCJ0b1VwcGVyQ2FzZSIsInNlbGVjdGVkSWQiLCJ4IiwiZ2V0RWxlbWVudHNCeVRhZ05hbWUiLCJrZXlDb2RlIiwiYWRkQWN0aXZlIiwiY2xpY2siLCJ0YXJnZXQiLCJyZW1vdmVBY3RpdmUiLCJjbGFzc0xpc3QiLCJhZGQiLCJyZW1vdmUiLCJlbG1udCIsImdldEVsZW1lbnRzQnlDbGFzc05hbWUiLCJyZW1vdmVDaGlsZCIsInRybiIsIlRvdXJuYW1hdGNoIiwiZXZlbnRzIiwib2JqZWN0IiwicHJlZml4Iiwic3RyIiwicHJvcCIsImhhc093blByb3BlcnR5IiwiayIsInYiLCJwdXNoIiwiZW5jb2RlVVJJQ29tcG9uZW50Iiwiam9pbiIsImV2ZW50TmFtZSIsIkV2ZW50VGFyZ2V0IiwiaW5wdXQiLCJkYXRhQ2FsbGJhY2siLCJUb3VybmFtYXRjaF9BdXRvY29tcGxldGUiLCJzIiwiY2hhckF0Iiwic2xpY2UiLCJudW1iZXIiLCJyZW1haW5kZXIiLCJlbGVtZW50IiwidGFicyIsInBhbmVzIiwiY2xlYXJBY3RpdmUiLCJBcnJheSIsInByb3RvdHlwZSIsImNhbGwiLCJ0YWIiLCJhcmlhU2VsZWN0ZWQiLCJwYW5lIiwic2V0QWN0aXZlIiwidGFyZ2V0SWQiLCJ0YXJnZXRUYWIiLCJxdWVyeVNlbGVjdG9yIiwidGFyZ2V0UGFuZUlkIiwidGFiQ2xpY2siLCJjdXJyZW50VGFyZ2V0IiwiaGFzaCIsInRybl9vYmpfaW5zdGFuY2UiLCJ0YWJWaWV3cyIsImZyb20iLCJkcm9wZG93bnMiLCJoYW5kbGVEcm9wZG93bkNsb3NlIiwiZHJvcGRvd24iLCJuZXh0RWxlbWVudFNpYmxpbmciLCJyZW1vdmVFdmVudExpc3RlbmVyIiwic3RvcFByb3BhZ2F0aW9uIiwiRXZlbnQiLCJTdHJpbmciLCJmb3JtYXQiLCJhcmdzIiwiYXJndW1lbnRzIiwicmVwbGFjZSIsIm1hdGNoIl0sIm1hcHBpbmdzIjoiO1FBQUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7OztRQUdBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSwwQ0FBMEMsZ0NBQWdDO1FBQzFFO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0Esd0RBQXdELGtCQUFrQjtRQUMxRTtRQUNBLGlEQUFpRCxjQUFjO1FBQy9EOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQSx5Q0FBeUMsaUNBQWlDO1FBQzFFLGdIQUFnSCxtQkFBbUIsRUFBRTtRQUNySTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLDJCQUEyQiwwQkFBMEIsRUFBRTtRQUN2RCxpQ0FBaUMsZUFBZTtRQUNoRDtRQUNBO1FBQ0E7O1FBRUE7UUFDQSxzREFBc0QsK0RBQStEOztRQUVySDtRQUNBOzs7UUFHQTtRQUNBOzs7Ozs7Ozs7Ozs7Ozs7OztBQ2xGQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxD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QWhCLENBQTRCWixDQUFDLENBQUNqQixDQUFELENBQTdCO0FBQ0g7QUFDSjtBQUNKOzs7O0tBR0w7OztBQUNBLElBQUksQ0FBQ2lGLE1BQU0sQ0FBQ3ZCLFNBQVAsQ0FBaUJ3QixNQUF0QixFQUE4QjtBQUMxQkQsUUFBTSxDQUFDdkIsU0FBUCxDQUFpQndCLE1BQWpCLEdBQTBCLFlBQVc7QUFDakMsUUFBTUMsSUFBSSxHQUFHQyxTQUFiO0FBQ0EsV0FBTyxLQUFLQyxPQUFMLENBQWEsVUFBYixFQUF5QixVQUFTQyxLQUFULEVBQWdCbkMsTUFBaEIsRUFBd0I7QUFDcEQsYUFBTyxPQUFPZ0MsSUFBSSxDQUFDaEMsTUFBRCxDQUFYLEtBQXdCLFdBQXhCLEdBQ0RnQyxJQUFJLENBQUNoQyxNQUFELENBREgsR0FFRG1DLEtBRk47QUFJSCxLQUxNLENBQVA7QUFNSCxHQVJEO0FBU0gsQyIsImZpbGUiOiJ0ZWFtLXByb2ZpbGUuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMzUpO1xuIiwiLyoqXHJcbiAqIFRlYW0gcHJvZmlsZSBwYWdlLlxyXG4gKlxyXG4gKiBAbGluayAgICAgICBodHRwczovL3d3dy50b3VybmFtYXRjaC5jb21cclxuICogQHNpbmNlICAgICAgMy44LjBcclxuICpcclxuICogQHBhY2thZ2UgICAgVG91cm5hbWF0Y2hcclxuICpcclxuICovXHJcbmltcG9ydCB7IHRybiB9IGZyb20gJy4vdG91cm5hbWF0Y2guanMnO1xyXG5cclxuKGZ1bmN0aW9uICgkKSB7XHJcbiAgICAndXNlIHN0cmljdCc7XHJcblxyXG4gICAgLy8gYWRkIGxpc3RlbmVyIGZvciByb3N0ZXIgY2hhbmdlZCBldmVudFxyXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgbGV0IG9wdGlvbnMgPSB0cm5fdGVhbV9wcm9maWxlX29wdGlvbnM7XHJcbiAgICAgICAgY29uc3Qgam9pblRlYW1CdXR0b24gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLWpvaW4tdGVhbS1idXR0b24nKTtcclxuICAgICAgICBjb25zdCBsZWF2ZVRlYW1CdXR0b24gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLWxlYXZlLXRlYW0tYnV0dG9uJyk7XHJcbiAgICAgICAgY29uc3QgZGVsZXRlVGVhbUJ1dHRvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tZGVsZXRlLXRlYW0tYnV0dG9uJyk7XHJcbiAgICAgICAgY29uc3QgZWRpdFRlYW1CdXR0b24gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLWVkaXQtdGVhbS1idXR0b24nKTtcclxuXHJcbiAgICAgICAgZnVuY3Rpb24gY2FuSm9pbih1c2VySWQsIG1lbWJlcnMpIHtcclxuICAgICAgICAgICAgbGV0IGlzTWVtYmVyID0gZmFsc2U7XHJcbiAgICAgICAgICAgIGlmICgobWVtYmVycyAhPT0gbnVsbCkgJiYgKG1lbWJlcnMubGVuZ3RoID4gMCkpIHtcclxuICAgICAgICAgICAgICAgIG1lbWJlcnMuZm9yRWFjaCgobWVtYmVyKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgaXNNZW1iZXIgPSBpc01lbWJlciB8fCAobWVtYmVyLnVzZXJfaWQgPT09IHVzZXJJZCk7XHJcbiAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICByZXR1cm4gIWlzTWVtYmVyO1xyXG4gICAgICAgIH1cclxuXHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIGNhbkxlYXZlKHVzZXJJZCwgbWVtYmVycykge1xyXG4gICAgICAgICAgICBsZXQgaXNNZW1iZXIgPSBmYWxzZTtcclxuICAgICAgICAgICAgbGV0IGlzT3duZXIgPSBmYWxzZTtcclxuICAgICAgICAgICAgaWYgKChtZW1iZXJzICE9PSBudWxsKSAmJiAobWVtYmVycy5sZW5ndGggPiAwKSkge1xyXG4gICAgICAgICAgICAgICAgbWVtYmVycy5mb3JFYWNoKChtZW1iZXIpID0+IHtcclxuICAgICAgICAgICAgICAgICAgICBpZiAobWVtYmVyLnVzZXJfaWQgPT09IHVzZXJJZCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBpc01lbWJlciA9IHRydWU7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChtZW1iZXIudGVhbV9yYW5rX2lkID09PSAxKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpc093bmVyID0gdHJ1ZTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIHJldHVybiBpc01lbWJlciAmJiAhaXNPd25lcjtcclxuICAgICAgICB9XHJcblxyXG5cclxuICAgICAgICBmdW5jdGlvbiBjYW5EZWxldGUodXNlcklkLCBtZW1iZXJzKSB7XHJcbiAgICAgICAgICAgIGxldCBpc01lbWJlciA9IGZhbHNlO1xyXG4gICAgICAgICAgICBpZiAoKG1lbWJlcnMgIT09IG51bGwpICYmIChtZW1iZXJzLmxlbmd0aCA+IDApKSB7XHJcbiAgICAgICAgICAgICAgICBtZW1iZXJzLmZvckVhY2goKG1lbWJlcikgPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgIGlzTWVtYmVyID0gKG1lbWJlci51c2VyX2lkID09PSB1c2VySWQpIHx8IGlzTWVtYmVyO1xyXG4gICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgcmV0dXJuIGlzTWVtYmVyICYmIChtZW1iZXJzLmxlbmd0aCA9PT0gMSk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBmdW5jdGlvbiBjYW5FZGl0KHVzZXJJZCwgbWVtYmVycykge1xyXG4gICAgICAgICAgICBsZXQgaXNPd25lciA9IGZhbHNlO1xyXG4gICAgICAgICAgICBpZiAoKG1lbWJlcnMgIT09IG51bGwpICYmIChtZW1iZXJzLmxlbmd0aCA+IDApKSB7XHJcbiAgICAgICAgICAgICAgICBtZW1iZXJzLmZvckVhY2goKG1lbWJlcikgPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgIGlzT3duZXIgPSAoKG1lbWJlci51c2VyX2lkID09PSB1c2VySWQpICYmIChtZW1iZXIudGVhbV9yYW5rX2lkID09PSAxKSkgfHwgaXNPd25lcjtcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIHJldHVybiBpc093bmVyO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZnVuY3Rpb24gZ2V0Q3VycmVudFVzZXJUZWFtTWVtYmVySWQodXNlcklkLCBtZW1iZXJzKSB7XHJcbiAgICAgICAgICAgIGxldCB0ZWFtTWVtYmVySWQgPSBudWxsO1xyXG4gICAgICAgICAgICBpZiAoKG1lbWJlcnMgIT09IG51bGwpICYmIChtZW1iZXJzLmxlbmd0aCA+IDApKSB7XHJcbiAgICAgICAgICAgICAgICBtZW1iZXJzLmZvckVhY2goKG1lbWJlcikgPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgIGlmIChtZW1iZXIudXNlcl9pZCA9PT0gdXNlcklkKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHRlYW1NZW1iZXJJZCA9IG1lbWJlci50ZWFtX21lbWJlcl9pZDtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICByZXR1cm4gdGVhbU1lbWJlcklkO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZnVuY3Rpb24gZXZhbHVhdGVCdXR0b25TdGF0ZXMobWVtYmVycykge1xyXG4gICAgICAgICAgICBjb25zdCB1c2VySWQgPSBwYXJzZUludChvcHRpb25zLmN1cnJlbnRfdXNlcl9pZCk7XHJcblxyXG4gICAgICAgICAgICBpZiAoY2FuRGVsZXRlKHVzZXJJZCwgbWVtYmVycykpIHtcclxuICAgICAgICAgICAgICAgIGRlbGV0ZVRlYW1CdXR0b24uc3R5bGUuZGlzcGxheSA9ICdpbmxpbmUtYmxvY2snO1xyXG4gICAgICAgICAgICAgICAgZGVsZXRlVGVhbUJ1dHRvbi5kYXRhc2V0LnRlYW1NZW1iZXJJZCA9IGdldEN1cnJlbnRVc2VyVGVhbU1lbWJlcklkKHVzZXJJZCwgbWVtYmVycyk7XHJcbiAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICBkZWxldGVUZWFtQnV0dG9uLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChjYW5Kb2luKHVzZXJJZCwgbWVtYmVycykpIHtcclxuICAgICAgICAgICAgICAgIGpvaW5UZWFtQnV0dG9uLnN0eWxlLmRpc3BsYXkgPSAnaW5saW5lLWJsb2NrJztcclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgIGpvaW5UZWFtQnV0dG9uLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmIChjYW5MZWF2ZSh1c2VySWQsIG1lbWJlcnMpKSB7XHJcbiAgICAgICAgICAgICAgICBsZWF2ZVRlYW1CdXR0b24uc3R5bGUuZGlzcGxheSA9ICdpbmxpbmUtYmxvY2snO1xyXG4gICAgICAgICAgICAgICAgbGVhdmVUZWFtQnV0dG9uLmRhdGFzZXQudGVhbU1lbWJlcklkID0gZ2V0Q3VycmVudFVzZXJUZWFtTWVtYmVySWQodXNlcklkLCBtZW1iZXJzKTtcclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgIGxlYXZlVGVhbUJ1dHRvbi5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBpZiAob3B0aW9ucy5jYW5fZWRpdCB8fCBjYW5FZGl0KHVzZXJJZCwgbWVtYmVycykpIHtcclxuICAgICAgICAgICAgICAgIGVkaXRUZWFtQnV0dG9uLnN0eWxlLmRpc3BsYXkgPSAnaW5saW5lLWJsb2NrJztcclxuICAgICAgICAgICAgfSBlbHNle1xyXG4gICAgICAgICAgICAgICAgZWRpdFRlYW1CdXR0b24uc3R5bGUuZGlzcGxheSA9ICdub25lJztcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZnVuY3Rpb24gZ2V0TWVtYmVycygpIHtcclxuICAgICAgICAgICAgbGV0IHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xyXG4gICAgICAgICAgICB4aHIub3BlbignR0VUJywgb3B0aW9ucy5hcGlfdXJsICsgJ3RlYW0tbWVtYmVycy8/X2VtYmVkJicgKyAkLnBhcmFtKHt0ZWFtX2lkOiBvcHRpb25zLnRlYW1faWR9KSk7XHJcbiAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdDb250ZW50LVR5cGUnLCAnYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkJyk7XHJcbiAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgIGxldCBjb250ZW50ID0gJyc7XHJcbiAgICAgICAgICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IHJlc3BvbnNlID0gSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpO1xyXG4gICAgICAgICAgICAgICAgICAgIC8vIGxldCBtZW1iZXJMaW5rcyA9IFtdO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAvLyBpZiAocmVzcG9uc2UgIT09IG51bGwgJiYgcmVzcG9uc2UubGVuZ3RoID4gMCkge1xyXG4gICAgICAgICAgICAgICAgICAgIC8vICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHJlc3BvbnNlLCBmdW5jdGlvbiAobWVtYmVyKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgLy8gICAgICAgICBtZW1iZXJMaW5rcy5wdXNoKGA8YSBocmVmPVwiLi4vcGxheWVycy8ke21lbWJlci51c2VyX2lkfVwiPiR7bWVtYmVyLl9lbWJlZGRlZC5wbGF5ZXJbMF0ubmFtZX08L2E+YCk7XHJcbiAgICAgICAgICAgICAgICAgICAgLy8gICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgICAgIC8vXHJcbiAgICAgICAgICAgICAgICAgICAgLy8gICAgIGNvbnRlbnQgKz0gbWVtYmVyTGlua3Muam9pbignLCAnKTtcclxuICAgICAgICAgICAgICAgICAgICAvLyB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIC8vICAgICBjb250ZW50ICs9IGA8cCBjbGFzcz1cInRybi10ZXh0LWNlbnRlclwiPiR7b3B0aW9ucy5sYW5ndWFnZS56ZXJvX21lbWJlcnN9PC9wPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgLy8gfVxyXG4gICAgICAgICAgICAgICAgICAgIGlmIChvcHRpb25zLmlzX2xvZ2dlZF9pbikge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBldmFsdWF0ZUJ1dHRvblN0YXRlcyhyZXNwb25zZSk7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8cCBjbGFzcz1cInRleHQtY2VudGVyXCI+JHtvcHRpb25zLmxhbmd1YWdlLmVycm9yX21lbWJlcnN9PC9wPmA7XHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgY29uc3QgbWVtYmVyTGlzdCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tdGVhbS1tZW1iZXJzLWxpc3QnKTtcclxuICAgICAgICAgICAgICAgIGlmIChtZW1iZXJMaXN0KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbWVtYmVyTGlzdC5pbm5lckhUTUwgPSBjb250ZW50O1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9O1xyXG5cclxuICAgICAgICAgICAgeGhyLnNlbmQoKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgZ2V0TWVtYmVycygpO1xyXG5cclxuICAgICAgICAkLmV2ZW50KCd0ZWFtLW1lbWJlcnMnKS5hZGRFdmVudExpc3RlbmVyKCdjaGFuZ2VkJywgZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgICAgIGdldE1lbWJlcnMoKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgZnVuY3Rpb24gam9pblRlYW0oKSB7XHJcbiAgICAgICAgICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgeGhyLm9wZW4oJ1BPU1QnLCBvcHRpb25zLmFwaV91cmwgKyAndGVhbS1yZXF1ZXN0cycpO1xyXG4gICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xyXG4gICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignWC1XUC1Ob25jZScsIG9wdGlvbnMucmVzdF9ub25jZSk7XHJcbiAgICAgICAgICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBsZXQgcmVzcG9uc2UgPSBKU09OLnBhcnNlKHhoci5yZXNwb25zZSk7XHJcbiAgICAgICAgICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAxKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Rybi1qb2luLXRlYW0tcmVzcG9uc2UnKS5pbm5lckhUTUwgPSBgPGRpdiBjbGFzcz1cInRybi1hbGVydCB0cm4tYWxlcnQtc3VjY2Vzc1wiPjxzdHJvbmc+JHtvcHRpb25zLmxhbmd1YWdlLnN1Y2Nlc3N9ITwvc3Ryb25nPiAke29wdGlvbnMubGFuZ3VhZ2Uuc3VjY2Vzc19tZXNzYWdlfTwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tam9pbi10ZWFtLXJlc3BvbnNlJykuaW5uZXJIVE1MID0gYDxkaXYgY2xhc3M9XCJ0cm4tYWxlcnQgdHJuLWFsZXJ0LWRhbmdlclwiPjxzdHJvbmc+JHtvcHRpb25zLmxhbmd1YWdlLmZhaWx1cmV9Ojwvc3Ryb25nPiAke3Jlc3BvbnNlLm1lc3NhZ2V9PC9kaXY+YDtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgIHhoci5zZW5kKCQucGFyYW0oe1xyXG4gICAgICAgICAgICAgICAgdGVhbV9pZDogZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Rybi1qb2luLXRlYW0tYnV0dG9uJykuZGF0YXNldC50ZWFtSWQsXHJcbiAgICAgICAgICAgICAgICB1c2VyX2lkOiBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLWpvaW4tdGVhbS1idXR0b24nKS5kYXRhc2V0LnVzZXJJZCxcclxuICAgICAgICAgICAgfSkpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZnVuY3Rpb24gaGFuZGxlSm9pblRlYW0oZXZlbnQpIHtcclxuICAgICAgICAgICAgam9pblRlYW0oKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIGxlYXZlVGVhbSgpIHtcclxuICAgICAgICAgICAgbGV0IHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xyXG4gICAgICAgICAgICB4aHIub3BlbignREVMRVRFJywgb3B0aW9ucy5hcGlfdXJsICsgJ3RlYW0tbWVtYmVycy8nICsgbGVhdmVUZWFtQnV0dG9uLmRhdGFzZXQudGVhbU1lbWJlcklkKTtcclxuICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ1gtV1AtTm9uY2UnLCBvcHRpb25zLnJlc3Rfbm9uY2UpO1xyXG4gICAgICAgICAgICB4aHIub25sb2FkID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwNCkge1xyXG4gICAgICAgICAgICAgICAgICAgICQuZXZlbnQoJ3RlYW0tbWVtYmVycycpLmRpc3BhdGNoRXZlbnQobmV3IEN1c3RvbUV2ZW50KCdjaGFuZ2VkJywgeyBkZXRhaWw6IHsgdGVhbV9tZW1iZXJfaWQ6IGxlYXZlVGVhbUJ1dHRvbi5kYXRhc2V0LnRlYW1NZW1iZXJJZCB9IH0gKSk7XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIGxldCByZXNwb25zZSA9IEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKTtcclxuICAgICAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLWxlYXZlLXRlYW0tcmVzcG9uc2UnKS5pbm5lckhUTUwgPSBgPGRpdiBjbGFzcz1cInRybi1hbGVydCB0cm4tYWxlcnQtZGFuZ2VyXCI+PHN0cm9uZz4ke29wdGlvbnMubGFuZ3VhZ2UuZmFpbHVyZX06PC9zdHJvbmc+ICR7cmVzcG9uc2UubWVzc2FnZX08L2Rpdj5gO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9O1xyXG5cclxuICAgICAgICAgICAgeGhyLnNlbmQoKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIGRlbGV0ZVRlYW0oKSB7XHJcbiAgICAgICAgICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgeGhyLm9wZW4oJ0RFTEVURScsIG9wdGlvbnMuYXBpX3VybCArICd0ZWFtLW1lbWJlcnMvJyArIGRlbGV0ZVRlYW1CdXR0b24uZGF0YXNldC50ZWFtTWVtYmVySWQpO1xyXG4gICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xyXG4gICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignWC1XUC1Ob25jZScsIG9wdGlvbnMucmVzdF9ub25jZSk7XHJcbiAgICAgICAgICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjA0KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgd2luZG93LmxvY2F0aW9uLmhyZWYgPSBvcHRpb25zLnRlYW1zX3VybDtcclxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IHJlc3BvbnNlID0gSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpO1xyXG4gICAgICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tbGVhdmUtdGVhbS1yZXNwb25zZScpLmlubmVySFRNTCA9IGA8ZGl2IGNsYXNzPVwidHJuLWFsZXJ0IHRybi1hbGVydC1kYW5nZXJcIj48c3Ryb25nPiR7b3B0aW9ucy5sYW5ndWFnZS5mYWlsdXJlfTo8L3N0cm9uZz4gJHtyZXNwb25zZS5tZXNzYWdlfTwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgICAgICB4aHIuc2VuZCgpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgaWYgKG9wdGlvbnMuaXNfbG9nZ2VkX2luKSB7XHJcbiAgICAgICAgICAgIGpvaW5UZWFtQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgaGFuZGxlSm9pblRlYW0sIGZhbHNlKTtcclxuICAgICAgICAgICAgbGVhdmVUZWFtQnV0dG9uLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgbGVhdmVUZWFtKTtcclxuICAgICAgICAgICAgZGVsZXRlVGVhbUJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGRlbGV0ZVRlYW0pO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgLyp0aGUgYXV0b2NvbXBsZXRlIGZ1bmN0aW9uIHRha2VzIHR3byBhcmd1bWVudHMsXHJcbiAgICAgICAgIHRoZSB0ZXh0IGZpZWxkIGVsZW1lbnQgYW5kIGFuIGFycmF5IG9mIHBvc3NpYmxlIGF1dG9jb21wbGV0ZWQgdmFsdWVzOiovXHJcbiAgICAgICAgY29uc3QgYWRkRm9ybSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tYWRkLXBsYXllci1mb3JtJyk7XHJcbiAgICAgICAgY29uc3QgbmFtZUlucHV0ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Rybi1hZGQtcGxheWVyLWlucHV0Jyk7XHJcblxyXG4gICAgICAgIGxldCBjdXJyZW50Rm9jdXM7XHJcblxyXG4gICAgICAgIGlmIChvcHRpb25zLmNhbl9hZGQgPT09ICcxJykge1xyXG4gICAgICAgICAgICBhZGRGb3JtLmFkZEV2ZW50TGlzdGVuZXIoJ3N1Ym1pdCcsIGZ1bmN0aW9uKGV2ZW50KSB7XHJcbiAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xyXG5cclxuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKCdzdWJtaXR0ZWQnKTtcclxuXHJcbiAgICAgICAgICAgICAgICBsZXQgcCA9IG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpID0+IHtcclxuICAgICAgICAgICAgICAgICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgICAgICAgICAgeGhyLm9wZW4oJ0dFVCcsIG9wdGlvbnMuYXBpX3VybCArICdwbGF5ZXJzLz9uYW1lPScgKyBuYW1lSW5wdXQudmFsdWUpO1xyXG4gICAgICAgICAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdDb250ZW50LVR5cGUnLCAnYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkJyk7XHJcbiAgICAgICAgICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ1gtV1AtTm9uY2UnLCBvcHRpb25zLnJlc3Rfbm9uY2UpO1xyXG4gICAgICAgICAgICAgICAgICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKVswXVsndXNlcl9pZCddKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcmVzb2x2ZShKU09OLnBhcnNlKHhoci5yZXNwb25zZSlbMF1bJ3VzZXJfaWQnXSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZWplY3QoKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIH07XHJcbiAgICAgICAgICAgICAgICAgICAgeGhyLnNlbmQoKTtcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgcC50aGVuKCh1c2VyX2lkKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xyXG4gICAgICAgICAgICAgICAgICAgIHhoci5vcGVuKCdQT1NUJywgb3B0aW9ucy5hcGlfdXJsICsgJ3RlYW0tbWVtYmVycy8nKTtcclxuICAgICAgICAgICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xyXG4gICAgICAgICAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgICAgICAgICB4aHIub25sb2FkID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyh4aHIpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAxKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkLmV2ZW50KCd0ZWFtLW1lbWJlcnMnKS5kaXNwYXRjaEV2ZW50KG5ldyBDdXN0b21FdmVudCgnY2hhbmdlZCcsIHsgfSApKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnN0IG1lc3NhZ2UgPSAoIHhoci5zdGF0dXMgPT09IDQwMyApID8gSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpLm1lc3NhZ2UgOiBvcHRpb25zLmxhbmd1YWdlLmZhaWx1cmVfbWVzc2FnZTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tYWRkLXBsYXllci1yZXNwb25zZScpLmlubmVySFRNTCA9IGA8ZGl2IGNsYXNzPVwidHJuLWFsZXJ0IHRybi1hbGVydC1kYW5nZXJcIj48c3Ryb25nPiR7b3B0aW9ucy5sYW5ndWFnZS5mYWlsdXJlfTo8L3N0cm9uZz4gJHttZXNzYWdlfTwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICB9O1xyXG4gICAgICAgICAgICAgICAgICAgIHhoci5zZW5kKCQucGFyYW0oe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB0ZWFtX2lkOiBvcHRpb25zLnRlYW1faWQsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHVzZXJfaWQ6IHVzZXJfaWQsXHJcbiAgICAgICAgICAgICAgICAgICAgfSkpO1xyXG4gICAgICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICB9LCBmYWxzZSk7XHJcblxyXG4gICAgICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiB3aGVuIHNvbWVvbmUgd3JpdGVzIGluIHRoZSB0ZXh0IGZpZWxkOiovXHJcbiAgICAgICAgICAgIG5hbWVJbnB1dC5hZGRFdmVudExpc3RlbmVyKFwiaW5wdXRcIiwgZnVuY3Rpb24oZSkge1xyXG4gICAgICAgICAgICAgICAgbGV0IGEsIGIsIGksIHZhbCA9IHRoaXMudmFsdWU7XHJcbiAgICAgICAgICAgICAgICBsZXQgcGFyZW50ID0gdGhpcy5wYXJlbnROb2RlO1xyXG5cclxuICAgICAgICAgICAgICAgIGxldCBwID0gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgIC8qIG5lZWQgdG8gcXVlcnkgc2VydmVyIGZvciBuYW1lcyBoZXJlLiAqL1xyXG4gICAgICAgICAgICAgICAgICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgICAgICAgICB4aHIub3BlbignR0VUJywgb3B0aW9ucy5hcGlfdXJsICsgJ3BsYXllcnMvP3NlYXJjaD0nICsgdmFsICsgJyZwZXJfcGFnZT01Jyk7XHJcbiAgICAgICAgICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignWC1XUC1Ob25jZScsIG9wdGlvbnMucmVzdF9ub25jZSk7XHJcbiAgICAgICAgICAgICAgICAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLy8gcmVzb2x2ZShKU09OLnBhcnNlKHhoci5yZXNwb25zZSkubWFwKChwbGF5ZXIpID0+IHtyZXR1cm4geyAndmFsdWUnOiBwbGF5ZXIuaWQsICd0ZXh0JzogcGxheWVyLm5hbWUgfTt9KSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXNvbHZlKEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKS5tYXAoKHBsYXllcikgPT4ge3JldHVybiBwbGF5ZXIubmFtZTt9KSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZWplY3QoKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIH07XHJcbiAgICAgICAgICAgICAgICAgICAgeGhyLnNlbmQoKTtcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgcC50aGVuKChkYXRhKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2coZGF0YSk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIC8qY2xvc2UgYW55IGFscmVhZHkgb3BlbiBsaXN0cyBvZiBhdXRvY29tcGxldGVkIHZhbHVlcyovXHJcbiAgICAgICAgICAgICAgICAgICAgY2xvc2VBbGxMaXN0cygpO1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICghdmFsKSB7IHJldHVybiBmYWxzZTt9XHJcbiAgICAgICAgICAgICAgICAgICAgY3VycmVudEZvY3VzID0gLTE7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIC8qY3JlYXRlIGEgRElWIGVsZW1lbnQgdGhhdCB3aWxsIGNvbnRhaW4gdGhlIGl0ZW1zICh2YWx1ZXMpOiovXHJcbiAgICAgICAgICAgICAgICAgICAgYSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJESVZcIik7XHJcbiAgICAgICAgICAgICAgICAgICAgYS5zZXRBdHRyaWJ1dGUoXCJpZFwiLCB0aGlzLmlkICsgXCItYXV0by1jb21wbGV0ZS1saXN0XCIpO1xyXG4gICAgICAgICAgICAgICAgICAgIGEuc2V0QXR0cmlidXRlKFwiY2xhc3NcIiwgXCJ0cm4tYXV0by1jb21wbGV0ZS1pdGVtc1wiKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgLyphcHBlbmQgdGhlIERJViBlbGVtZW50IGFzIGEgY2hpbGQgb2YgdGhlIGF1dG9jb21wbGV0ZSBjb250YWluZXI6Ki9cclxuICAgICAgICAgICAgICAgICAgICBwYXJlbnQuYXBwZW5kQ2hpbGQoYSk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIC8qZm9yIGVhY2ggaXRlbSBpbiB0aGUgYXJyYXkuLi4qL1xyXG4gICAgICAgICAgICAgICAgICAgIGZvciAoaSA9IDA7IGkgPCBkYXRhLmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGxldCB0ZXh0LCB2YWx1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qIFdoaWNoIGZvcm1hdCBkaWQgdGhleSBnaXZlIHVzLiAqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGRhdGFbaV0gPT09ICdvYmplY3QnKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0ZXh0ID0gZGF0YVtpXVsndGV4dCddO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSBkYXRhW2ldWyd2YWx1ZSddO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGV4dCA9IGRhdGFbaV07XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YWx1ZSA9IGRhdGFbaV07XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qY2hlY2sgaWYgdGhlIGl0ZW0gc3RhcnRzIHdpdGggdGhlIHNhbWUgbGV0dGVycyBhcyB0aGUgdGV4dCBmaWVsZCB2YWx1ZToqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAodGV4dC5zdWJzdHIoMCwgdmFsLmxlbmd0aCkudG9VcHBlckNhc2UoKSA9PT0gdmFsLnRvVXBwZXJDYXNlKCkpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8qY3JlYXRlIGEgRElWIGVsZW1lbnQgZm9yIGVhY2ggbWF0Y2hpbmcgZWxlbWVudDoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJESVZcIik7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAvKm1ha2UgdGhlIG1hdGNoaW5nIGxldHRlcnMgYm9sZDoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgPSBcIjxzdHJvbmc+XCIgKyB0ZXh0LnN1YnN0cigwLCB2YWwubGVuZ3RoKSArIFwiPC9zdHJvbmc+XCI7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBiLmlubmVySFRNTCArPSB0ZXh0LnN1YnN0cih2YWwubGVuZ3RoKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAvKmluc2VydCBhIGlucHV0IGZpZWxkIHRoYXQgd2lsbCBob2xkIHRoZSBjdXJyZW50IGFycmF5IGl0ZW0ncyB2YWx1ZToqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgKz0gXCI8aW5wdXQgdHlwZT0naGlkZGVuJyB2YWx1ZT0nXCIgKyB2YWx1ZSArIFwiJz5cIjtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBiLmRhdGFzZXQudmFsdWUgPSB2YWx1ZTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGIuZGF0YXNldC50ZXh0ID0gdGV4dDtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiB3aGVuIHNvbWVvbmUgY2xpY2tzIG9uIHRoZSBpdGVtIHZhbHVlIChESVYgZWxlbWVudCk6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGIuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGZ1bmN0aW9uIChlKSB7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8qIGluc2VydCB0aGUgdmFsdWUgZm9yIHRoZSBhdXRvY29tcGxldGUgdGV4dCBmaWVsZDogKi9cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBuYW1lSW5wdXQudmFsdWUgPSB0aGlzLmRhdGFzZXQudGV4dDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBuYW1lSW5wdXQuZGF0YXNldC5zZWxlY3RlZElkID0gdGhpcy5kYXRhc2V0LnZhbHVlO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvKiBjbG9zZSB0aGUgbGlzdCBvZiBhdXRvY29tcGxldGVkIHZhbHVlcywgKG9yIGFueSBvdGhlciBvcGVuIGxpc3RzIG9mIGF1dG9jb21wbGV0ZWQgdmFsdWVzOiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgY2xvc2VBbGxMaXN0cygpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBhLmFwcGVuZENoaWxkKGIpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIH0pO1xyXG5cclxuICAgICAgICAgICAgLypleGVjdXRlIGEgZnVuY3Rpb24gcHJlc3NlcyBhIGtleSBvbiB0aGUga2V5Ym9hcmQ6Ki9cclxuICAgICAgICAgICAgbmFtZUlucHV0LmFkZEV2ZW50TGlzdGVuZXIoXCJrZXlkb3duXCIsIGZ1bmN0aW9uKGUpIHtcclxuICAgICAgICAgICAgICAgIGxldCB4ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGhpcy5pZCArIFwiLWF1dG8tY29tcGxldGUtbGlzdFwiKTtcclxuICAgICAgICAgICAgICAgIGlmICh4KSB4ID0geC5nZXRFbGVtZW50c0J5VGFnTmFtZShcImRpdlwiKTtcclxuICAgICAgICAgICAgICAgIGlmIChlLmtleUNvZGUgPT09IDQwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgLypJZiB0aGUgYXJyb3cgRE9XTiBrZXkgaXMgcHJlc3NlZCxcclxuICAgICAgICAgICAgICAgICAgICAgaW5jcmVhc2UgdGhlIGN1cnJlbnRGb2N1cyB2YXJpYWJsZToqL1xyXG4gICAgICAgICAgICAgICAgICAgIGN1cnJlbnRGb2N1cysrO1xyXG4gICAgICAgICAgICAgICAgICAgIC8qYW5kIGFuZCBtYWtlIHRoZSBjdXJyZW50IGl0ZW0gbW9yZSB2aXNpYmxlOiovXHJcbiAgICAgICAgICAgICAgICAgICAgYWRkQWN0aXZlKHgpO1xyXG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmIChlLmtleUNvZGUgPT09IDM4KSB7IC8vdXBcclxuICAgICAgICAgICAgICAgICAgICAvKklmIHRoZSBhcnJvdyBVUCBrZXkgaXMgcHJlc3NlZCxcclxuICAgICAgICAgICAgICAgICAgICAgZGVjcmVhc2UgdGhlIGN1cnJlbnRGb2N1cyB2YXJpYWJsZToqL1xyXG4gICAgICAgICAgICAgICAgICAgIGN1cnJlbnRGb2N1cy0tO1xyXG4gICAgICAgICAgICAgICAgICAgIC8qYW5kIGFuZCBtYWtlIHRoZSBjdXJyZW50IGl0ZW0gbW9yZSB2aXNpYmxlOiovXHJcbiAgICAgICAgICAgICAgICAgICAgYWRkQWN0aXZlKHgpO1xyXG4gICAgICAgICAgICAgICAgfSBlbHNlIGlmIChlLmtleUNvZGUgPT09IDEzKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgLypJZiB0aGUgRU5URVIga2V5IGlzIHByZXNzZWQsIHByZXZlbnQgdGhlIGZvcm0gZnJvbSBiZWluZyBzdWJtaXR0ZWQsKi9cclxuICAgICAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKGN1cnJlbnRGb2N1cyA+IC0xKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qYW5kIHNpbXVsYXRlIGEgY2xpY2sgb24gdGhlIFwiYWN0aXZlXCIgaXRlbToqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoeCkgeFtjdXJyZW50Rm9jdXNdLmNsaWNrKCk7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHdoZW4gc29tZW9uZSBjbGlja3MgaW4gdGhlIGRvY3VtZW50OiovXHJcbiAgICAgICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBmdW5jdGlvbiAoZSkge1xyXG4gICAgICAgICAgICAgICAgY2xvc2VBbGxMaXN0cyhlLnRhcmdldCk7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZnVuY3Rpb24gYWRkQWN0aXZlKHgpIHtcclxuICAgICAgICAgICAgLyphIGZ1bmN0aW9uIHRvIGNsYXNzaWZ5IGFuIGl0ZW0gYXMgXCJhY3RpdmVcIjoqL1xyXG4gICAgICAgICAgICBpZiAoIXgpIHJldHVybiBmYWxzZTtcclxuICAgICAgICAgICAgLypzdGFydCBieSByZW1vdmluZyB0aGUgXCJhY3RpdmVcIiBjbGFzcyBvbiBhbGwgaXRlbXM6Ki9cclxuICAgICAgICAgICAgcmVtb3ZlQWN0aXZlKHgpO1xyXG4gICAgICAgICAgICBpZiAoY3VycmVudEZvY3VzID49IHgubGVuZ3RoKSBjdXJyZW50Rm9jdXMgPSAwO1xyXG4gICAgICAgICAgICBpZiAoY3VycmVudEZvY3VzIDwgMCkgY3VycmVudEZvY3VzID0gKHgubGVuZ3RoIC0gMSk7XHJcbiAgICAgICAgICAgIC8qYWRkIGNsYXNzIFwiYXV0b2NvbXBsZXRlLWFjdGl2ZVwiOiovXHJcbiAgICAgICAgICAgIHhbY3VycmVudEZvY3VzXS5jbGFzc0xpc3QuYWRkKFwidHJuLWF1dG8tY29tcGxldGUtYWN0aXZlXCIpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZnVuY3Rpb24gcmVtb3ZlQWN0aXZlKHgpIHtcclxuICAgICAgICAgICAgLyphIGZ1bmN0aW9uIHRvIHJlbW92ZSB0aGUgXCJhY3RpdmVcIiBjbGFzcyBmcm9tIGFsbCBhdXRvY29tcGxldGUgaXRlbXM6Ki9cclxuICAgICAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCB4Lmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICB4W2ldLmNsYXNzTGlzdC5yZW1vdmUoXCJ0cm4tYXV0by1jb21wbGV0ZS1hY3RpdmVcIik7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIGNsb3NlQWxsTGlzdHMoZWxtbnQpIHtcclxuICAgICAgICAgICAgY29uc29sZS5sb2coXCJjbG9zZSBhbGwgbGlzdHNcIik7XHJcbiAgICAgICAgICAgIC8qY2xvc2UgYWxsIGF1dG9jb21wbGV0ZSBsaXN0cyBpbiB0aGUgZG9jdW1lbnQsXHJcbiAgICAgICAgICAgICBleGNlcHQgdGhlIG9uZSBwYXNzZWQgYXMgYW4gYXJndW1lbnQ6Ki9cclxuICAgICAgICAgICAgbGV0IHggPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKFwidHJuLWF1dG8tY29tcGxldGUtaXRlbXNcIik7XHJcbiAgICAgICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgeC5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICAgICAgaWYgKGVsbW50ICE9PSB4W2ldICYmIGVsbW50ICE9PSBuYW1lSW5wdXQpIHtcclxuICAgICAgICAgICAgICAgICAgICB4W2ldLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoeFtpXSk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcblxyXG4gICAgfSwgZmFsc2UpO1xyXG59KSh0cm4pO1xyXG4iLCIndXNlIHN0cmljdCc7XHJcbmNsYXNzIFRvdXJuYW1hdGNoIHtcclxuXHJcbiAgICBjb25zdHJ1Y3RvcigpIHtcclxuICAgICAgICB0aGlzLmV2ZW50cyA9IHt9O1xyXG4gICAgfVxyXG5cclxuICAgIHBhcmFtKG9iamVjdCwgcHJlZml4KSB7XHJcbiAgICAgICAgbGV0IHN0ciA9IFtdO1xyXG4gICAgICAgIGZvciAobGV0IHByb3AgaW4gb2JqZWN0KSB7XHJcbiAgICAgICAgICAgIGlmIChvYmplY3QuaGFzT3duUHJvcGVydHkocHJvcCkpIHtcclxuICAgICAgICAgICAgICAgIGxldCBrID0gcHJlZml4ID8gcHJlZml4ICsgXCJbXCIgKyBwcm9wICsgXCJdXCIgOiBwcm9wO1xyXG4gICAgICAgICAgICAgICAgbGV0IHYgPSBvYmplY3RbcHJvcF07XHJcbiAgICAgICAgICAgICAgICBzdHIucHVzaCgodiAhPT0gbnVsbCAmJiB0eXBlb2YgdiA9PT0gXCJvYmplY3RcIikgPyB0aGlzLnBhcmFtKHYsIGspIDogZW5jb2RlVVJJQ29tcG9uZW50KGspICsgXCI9XCIgKyBlbmNvZGVVUklDb21wb25lbnQodikpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiBzdHIuam9pbihcIiZcIik7XHJcbiAgICB9XHJcblxyXG4gICAgZXZlbnQoZXZlbnROYW1lKSB7XHJcbiAgICAgICAgaWYgKCEoZXZlbnROYW1lIGluIHRoaXMuZXZlbnRzKSkge1xyXG4gICAgICAgICAgICB0aGlzLmV2ZW50c1tldmVudE5hbWVdID0gbmV3IEV2ZW50VGFyZ2V0KGV2ZW50TmFtZSk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiB0aGlzLmV2ZW50c1tldmVudE5hbWVdO1xyXG4gICAgfVxyXG5cclxuICAgIGF1dG9jb21wbGV0ZShpbnB1dCwgZGF0YUNhbGxiYWNrKSB7XHJcbiAgICAgICAgbmV3IFRvdXJuYW1hdGNoX0F1dG9jb21wbGV0ZShpbnB1dCwgZGF0YUNhbGxiYWNrKTtcclxuICAgIH1cclxuXHJcbiAgICB1Y2ZpcnN0KHMpIHtcclxuICAgICAgICBpZiAodHlwZW9mIHMgIT09ICdzdHJpbmcnKSByZXR1cm4gJyc7XHJcbiAgICAgICAgcmV0dXJuIHMuY2hhckF0KDApLnRvVXBwZXJDYXNlKCkgKyBzLnNsaWNlKDEpO1xyXG4gICAgfVxyXG5cclxuICAgIG9yZGluYWxfc3VmZml4KG51bWJlcikge1xyXG4gICAgICAgIGNvbnN0IHJlbWFpbmRlciA9IG51bWJlciAlIDEwMDtcclxuXHJcbiAgICAgICAgaWYgKChyZW1haW5kZXIgPCAxMSkgfHwgKHJlbWFpbmRlciA+IDEzKSkge1xyXG4gICAgICAgICAgICBzd2l0Y2ggKHJlbWFpbmRlciAlIDEwKSB7XHJcbiAgICAgICAgICAgICAgICBjYXNlIDE6IHJldHVybiAnc3QnO1xyXG4gICAgICAgICAgICAgICAgY2FzZSAyOiByZXR1cm4gJ25kJztcclxuICAgICAgICAgICAgICAgIGNhc2UgMzogcmV0dXJuICdyZCc7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuICd0aCc7XHJcbiAgICB9XHJcblxyXG4gICAgdGFicyhlbGVtZW50KSB7XHJcbiAgICAgICAgY29uc3QgdGFicyA9IGVsZW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLW5hdi1saW5rJyk7XHJcbiAgICAgICAgY29uc3QgcGFuZXMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tdGFiLXBhbmUnKTtcclxuICAgICAgICBjb25zdCBjbGVhckFjdGl2ZSA9ICgpID0+IHtcclxuICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbCh0YWJzLCAodGFiKSA9PiB7XHJcbiAgICAgICAgICAgICAgICB0YWIuY2xhc3NMaXN0LnJlbW92ZSgndHJuLW5hdi1hY3RpdmUnKTtcclxuICAgICAgICAgICAgICAgIHRhYi5hcmlhU2VsZWN0ZWQgPSBmYWxzZTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwocGFuZXMsIHBhbmUgPT4gcGFuZS5jbGFzc0xpc3QucmVtb3ZlKCd0cm4tdGFiLWFjdGl2ZScpKTtcclxuICAgICAgICB9O1xyXG4gICAgICAgIGNvbnN0IHNldEFjdGl2ZSA9ICh0YXJnZXRJZCkgPT4ge1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRUYWIgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdhW2hyZWY9XCIjJyArIHRhcmdldElkICsgJ1wiXS50cm4tbmF2LWxpbmsnKTtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0UGFuZUlkID0gdGFyZ2V0VGFiICYmIHRhcmdldFRhYi5kYXRhc2V0ICYmIHRhcmdldFRhYi5kYXRhc2V0LnRhcmdldCB8fCBmYWxzZTtcclxuXHJcbiAgICAgICAgICAgIGlmICh0YXJnZXRQYW5lSWQpIHtcclxuICAgICAgICAgICAgICAgIGNsZWFyQWN0aXZlKCk7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRUYWIuY2xhc3NMaXN0LmFkZCgndHJuLW5hdi1hY3RpdmUnKTtcclxuICAgICAgICAgICAgICAgIHRhcmdldFRhYi5hcmlhU2VsZWN0ZWQgPSB0cnVlO1xyXG5cclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHRhcmdldFBhbmVJZCkuY2xhc3NMaXN0LmFkZCgndHJuLXRhYi1hY3RpdmUnKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH07XHJcbiAgICAgICAgY29uc3QgdGFiQ2xpY2sgPSAoZXZlbnQpID0+IHtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0VGFiID0gZXZlbnQuY3VycmVudFRhcmdldDtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0UGFuZUlkID0gdGFyZ2V0VGFiICYmIHRhcmdldFRhYi5kYXRhc2V0ICYmIHRhcmdldFRhYi5kYXRhc2V0LnRhcmdldCB8fCBmYWxzZTtcclxuXHJcbiAgICAgICAgICAgIGlmICh0YXJnZXRQYW5lSWQpIHtcclxuICAgICAgICAgICAgICAgIHNldEFjdGl2ZSh0YXJnZXRQYW5lSWQpO1xyXG4gICAgICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH07XHJcblxyXG4gICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwodGFicywgKHRhYikgPT4ge1xyXG4gICAgICAgICAgICB0YWIuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0YWJDbGljayk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIGlmIChsb2NhdGlvbi5oYXNoKSB7XHJcbiAgICAgICAgICAgIHNldEFjdGl2ZShsb2NhdGlvbi5oYXNoLnN1YnN0cigxKSk7XHJcbiAgICAgICAgfSBlbHNlIGlmICh0YWJzLmxlbmd0aCA+IDApIHtcclxuICAgICAgICAgICAgc2V0QWN0aXZlKHRhYnNbMF0uZGF0YXNldC50YXJnZXQpO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbn1cclxuXHJcbi8vdHJuLmluaXRpYWxpemUoKTtcclxuaWYgKCF3aW5kb3cudHJuX29ial9pbnN0YW5jZSkge1xyXG4gICAgd2luZG93LnRybl9vYmpfaW5zdGFuY2UgPSBuZXcgVG91cm5hbWF0Y2goKTtcclxuXHJcbiAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIGZ1bmN0aW9uICgpIHtcclxuXHJcbiAgICAgICAgY29uc3QgdGFiVmlld3MgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tbmF2Jyk7XHJcblxyXG4gICAgICAgIEFycmF5LmZyb20odGFiVmlld3MpLmZvckVhY2goKHRhYikgPT4ge1xyXG4gICAgICAgICAgICB0cm4udGFicyh0YWIpO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICBjb25zdCBkcm9wZG93bnMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tZHJvcGRvd24tdG9nZ2xlJyk7XHJcbiAgICAgICAgY29uc3QgaGFuZGxlRHJvcGRvd25DbG9zZSA9ICgpID0+IHtcclxuICAgICAgICAgICAgQXJyYXkuZnJvbShkcm9wZG93bnMpLmZvckVhY2goKGRyb3Bkb3duKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBkcm9wZG93bi5uZXh0RWxlbWVudFNpYmxpbmcuY2xhc3NMaXN0LnJlbW92ZSgndHJuLXNob3cnKTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIGRvY3VtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBoYW5kbGVEcm9wZG93bkNsb3NlLCBmYWxzZSk7XHJcbiAgICAgICAgfTtcclxuXHJcbiAgICAgICAgQXJyYXkuZnJvbShkcm9wZG93bnMpLmZvckVhY2goKGRyb3Bkb3duKSA9PiB7XHJcbiAgICAgICAgICAgIGRyb3Bkb3duLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24oZSkge1xyXG4gICAgICAgICAgICAgICAgZS5zdG9wUHJvcGFnYXRpb24oKTtcclxuICAgICAgICAgICAgICAgIHRoaXMubmV4dEVsZW1lbnRTaWJsaW5nLmNsYXNzTGlzdC5hZGQoJ3Rybi1zaG93Jyk7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgaGFuZGxlRHJvcGRvd25DbG9zZSwgZmFsc2UpO1xyXG4gICAgICAgICAgICB9LCBmYWxzZSk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgfSwgZmFsc2UpO1xyXG59XHJcbmV4cG9ydCBsZXQgdHJuID0gd2luZG93LnRybl9vYmpfaW5zdGFuY2U7XHJcblxyXG5jbGFzcyBUb3VybmFtYXRjaF9BdXRvY29tcGxldGUge1xyXG5cclxuICAgIC8vIGN1cnJlbnRGb2N1cztcclxuICAgIC8vXHJcbiAgICAvLyBuYW1lSW5wdXQ7XHJcbiAgICAvL1xyXG4gICAgLy8gc2VsZjtcclxuXHJcbiAgICBjb25zdHJ1Y3RvcihpbnB1dCwgZGF0YUNhbGxiYWNrKSB7XHJcbiAgICAgICAgLy8gdGhpcy5zZWxmID0gdGhpcztcclxuICAgICAgICB0aGlzLm5hbWVJbnB1dCA9IGlucHV0O1xyXG5cclxuICAgICAgICB0aGlzLm5hbWVJbnB1dC5hZGRFdmVudExpc3RlbmVyKFwiaW5wdXRcIiwgKCkgPT4ge1xyXG4gICAgICAgICAgICBsZXQgYSwgYiwgaSwgdmFsID0gdGhpcy5uYW1lSW5wdXQudmFsdWU7Ly90aGlzLnZhbHVlO1xyXG4gICAgICAgICAgICBsZXQgcGFyZW50ID0gdGhpcy5uYW1lSW5wdXQucGFyZW50Tm9kZTsvL3RoaXMucGFyZW50Tm9kZTtcclxuXHJcbiAgICAgICAgICAgIC8vIGxldCBwID0gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xyXG4gICAgICAgICAgICAvLyAgICAgLyogbmVlZCB0byBxdWVyeSBzZXJ2ZXIgZm9yIG5hbWVzIGhlcmUuICovXHJcbiAgICAgICAgICAgIC8vICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIub3BlbignR0VUJywgb3B0aW9ucy5hcGlfdXJsICsgJ3BsYXllcnMvP3NlYXJjaD0nICsgdmFsICsgJyZwZXJfcGFnZT01Jyk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ1gtV1AtTm9uY2UnLCBvcHRpb25zLnJlc3Rfbm9uY2UpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgLy8gICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAwKSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgICAgIC8vIHJlc29sdmUoSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpLm1hcCgocGxheWVyKSA9PiB7cmV0dXJuIHsgJ3ZhbHVlJzogcGxheWVyLmlkLCAndGV4dCc6IHBsYXllci5uYW1lIH07fSkpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgICAgICByZXNvbHZlKEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKS5tYXAoKHBsYXllcikgPT4ge3JldHVybiBwbGF5ZXIubmFtZTt9KSk7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgLy8gICAgICAgICAgICAgcmVqZWN0KCk7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgfVxyXG4gICAgICAgICAgICAvLyAgICAgfTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5zZW5kKCk7XHJcbiAgICAgICAgICAgIC8vIH0pO1xyXG4gICAgICAgICAgICBkYXRhQ2FsbGJhY2sodmFsKS50aGVuKChkYXRhKSA9PiB7Ly9wLnRoZW4oKGRhdGEpID0+IHtcclxuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKGRhdGEpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qY2xvc2UgYW55IGFscmVhZHkgb3BlbiBsaXN0cyBvZiBhdXRvLWNvbXBsZXRlZCB2YWx1ZXMqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5jbG9zZUFsbExpc3RzKCk7XHJcbiAgICAgICAgICAgICAgICBpZiAoIXZhbCkgeyByZXR1cm4gZmFsc2U7fVxyXG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50Rm9jdXMgPSAtMTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmNyZWF0ZSBhIERJViBlbGVtZW50IHRoYXQgd2lsbCBjb250YWluIHRoZSBpdGVtcyAodmFsdWVzKToqL1xyXG4gICAgICAgICAgICAgICAgYSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJESVZcIik7XHJcbiAgICAgICAgICAgICAgICBhLnNldEF0dHJpYnV0ZShcImlkXCIsIHRoaXMubmFtZUlucHV0LmlkICsgXCItYXV0by1jb21wbGV0ZS1saXN0XCIpO1xyXG4gICAgICAgICAgICAgICAgYS5zZXRBdHRyaWJ1dGUoXCJjbGFzc1wiLCBcInRybi1hdXRvLWNvbXBsZXRlLWl0ZW1zXCIpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qYXBwZW5kIHRoZSBESVYgZWxlbWVudCBhcyBhIGNoaWxkIG9mIHRoZSBhdXRvLWNvbXBsZXRlIGNvbnRhaW5lcjoqL1xyXG4gICAgICAgICAgICAgICAgcGFyZW50LmFwcGVuZENoaWxkKGEpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qZm9yIGVhY2ggaXRlbSBpbiB0aGUgYXJyYXkuLi4qL1xyXG4gICAgICAgICAgICAgICAgZm9yIChpID0gMDsgaSA8IGRhdGEubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgICAgICAgICBsZXQgdGV4dCwgdmFsdWU7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIC8qIFdoaWNoIGZvcm1hdCBkaWQgdGhleSBnaXZlIHVzLiAqL1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgZGF0YVtpXSA9PT0gJ29iamVjdCcpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdGV4dCA9IGRhdGFbaV1bJ3RleHQnXTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSBkYXRhW2ldWyd2YWx1ZSddO1xyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHRleHQgPSBkYXRhW2ldO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB2YWx1ZSA9IGRhdGFbaV07XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgICAgICAvKmNoZWNrIGlmIHRoZSBpdGVtIHN0YXJ0cyB3aXRoIHRoZSBzYW1lIGxldHRlcnMgYXMgdGhlIHRleHQgZmllbGQgdmFsdWU6Ki9cclxuICAgICAgICAgICAgICAgICAgICBpZiAodGV4dC5zdWJzdHIoMCwgdmFsLmxlbmd0aCkudG9VcHBlckNhc2UoKSA9PT0gdmFsLnRvVXBwZXJDYXNlKCkpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgLypjcmVhdGUgYSBESVYgZWxlbWVudCBmb3IgZWFjaCBtYXRjaGluZyBlbGVtZW50OiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiRElWXCIpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKm1ha2UgdGhlIG1hdGNoaW5nIGxldHRlcnMgYm9sZDoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmlubmVySFRNTCA9IFwiPHN0cm9uZz5cIiArIHRleHQuc3Vic3RyKDAsIHZhbC5sZW5ndGgpICsgXCI8L3N0cm9uZz5cIjtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgKz0gdGV4dC5zdWJzdHIodmFsLmxlbmd0aCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKmluc2VydCBhIGlucHV0IGZpZWxkIHRoYXQgd2lsbCBob2xkIHRoZSBjdXJyZW50IGFycmF5IGl0ZW0ncyB2YWx1ZToqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmlubmVySFRNTCArPSBcIjxpbnB1dCB0eXBlPSdoaWRkZW4nIHZhbHVlPSdcIiArIHZhbHVlICsgXCInPlwiO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5kYXRhc2V0LnZhbHVlID0gdmFsdWU7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuZGF0YXNldC50ZXh0ID0gdGV4dDtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHdoZW4gc29tZW9uZSBjbGlja3Mgb24gdGhlIGl0ZW0gdmFsdWUgKERJViBlbGVtZW50KToqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2coYGl0ZW0gY2xpY2tlZCB3aXRoIHZhbHVlICR7ZS5jdXJyZW50VGFyZ2V0LmRhdGFzZXQudmFsdWV9YCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLyogaW5zZXJ0IHRoZSB2YWx1ZSBmb3IgdGhlIGF1dG9jb21wbGV0ZSB0ZXh0IGZpZWxkOiAqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5uYW1lSW5wdXQudmFsdWUgPSBlLmN1cnJlbnRUYXJnZXQuZGF0YXNldC50ZXh0O1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5uYW1lSW5wdXQuZGF0YXNldC5zZWxlY3RlZElkID0gZS5jdXJyZW50VGFyZ2V0LmRhdGFzZXQudmFsdWU7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLyogY2xvc2UgdGhlIGxpc3Qgb2YgYXV0b2NvbXBsZXRlZCB2YWx1ZXMsIChvciBhbnkgb3RoZXIgb3BlbiBsaXN0cyBvZiBhdXRvY29tcGxldGVkIHZhbHVlczoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5jbG9zZUFsbExpc3RzKCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5uYW1lSW5wdXQuZGlzcGF0Y2hFdmVudChuZXcgRXZlbnQoJ2NoYW5nZScpKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGEuYXBwZW5kQ2hpbGQoYik7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgLypleGVjdXRlIGEgZnVuY3Rpb24gcHJlc3NlcyBhIGtleSBvbiB0aGUga2V5Ym9hcmQ6Ki9cclxuICAgICAgICB0aGlzLm5hbWVJbnB1dC5hZGRFdmVudExpc3RlbmVyKFwia2V5ZG93blwiLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICBsZXQgeCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHRoaXMubmFtZUlucHV0LmlkICsgXCItYXV0by1jb21wbGV0ZS1saXN0XCIpO1xyXG4gICAgICAgICAgICBpZiAoeCkgeCA9IHguZ2V0RWxlbWVudHNCeVRhZ05hbWUoXCJkaXZcIik7XHJcbiAgICAgICAgICAgIGlmIChlLmtleUNvZGUgPT09IDQwKSB7XHJcbiAgICAgICAgICAgICAgICAvKklmIHRoZSBhcnJvdyBET1dOIGtleSBpcyBwcmVzc2VkLFxyXG4gICAgICAgICAgICAgICAgIGluY3JlYXNlIHRoZSBjdXJyZW50Rm9jdXMgdmFyaWFibGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudEZvY3VzKys7XHJcbiAgICAgICAgICAgICAgICAvKmFuZCBhbmQgbWFrZSB0aGUgY3VycmVudCBpdGVtIG1vcmUgdmlzaWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5hZGRBY3RpdmUoeCk7XHJcbiAgICAgICAgICAgIH0gZWxzZSBpZiAoZS5rZXlDb2RlID09PSAzOCkgeyAvL3VwXHJcbiAgICAgICAgICAgICAgICAvKklmIHRoZSBhcnJvdyBVUCBrZXkgaXMgcHJlc3NlZCxcclxuICAgICAgICAgICAgICAgICBkZWNyZWFzZSB0aGUgY3VycmVudEZvY3VzIHZhcmlhYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmN1cnJlbnRGb2N1cy0tO1xyXG4gICAgICAgICAgICAgICAgLyphbmQgYW5kIG1ha2UgdGhlIGN1cnJlbnQgaXRlbSBtb3JlIHZpc2libGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuYWRkQWN0aXZlKHgpO1xyXG4gICAgICAgICAgICB9IGVsc2UgaWYgKGUua2V5Q29kZSA9PT0gMTMpIHtcclxuICAgICAgICAgICAgICAgIC8qSWYgdGhlIEVOVEVSIGtleSBpcyBwcmVzc2VkLCBwcmV2ZW50IHRoZSBmb3JtIGZyb20gYmVpbmcgc3VibWl0dGVkLCovXHJcbiAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgICAgICBpZiAodGhpcy5jdXJyZW50Rm9jdXMgPiAtMSkge1xyXG4gICAgICAgICAgICAgICAgICAgIC8qYW5kIHNpbXVsYXRlIGEgY2xpY2sgb24gdGhlIFwiYWN0aXZlXCIgaXRlbToqL1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh4KSB4W3RoaXMuY3VycmVudEZvY3VzXS5jbGljaygpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHdoZW4gc29tZW9uZSBjbGlja3MgaW4gdGhlIGRvY3VtZW50OiovXHJcbiAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIChlKSA9PiB7XHJcbiAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cyhlLnRhcmdldCk7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9XHJcblxyXG4gICAgYWRkQWN0aXZlKHgpIHtcclxuICAgICAgICAvKmEgZnVuY3Rpb24gdG8gY2xhc3NpZnkgYW4gaXRlbSBhcyBcImFjdGl2ZVwiOiovXHJcbiAgICAgICAgaWYgKCF4KSByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgLypzdGFydCBieSByZW1vdmluZyB0aGUgXCJhY3RpdmVcIiBjbGFzcyBvbiBhbGwgaXRlbXM6Ki9cclxuICAgICAgICB0aGlzLnJlbW92ZUFjdGl2ZSh4KTtcclxuICAgICAgICBpZiAodGhpcy5jdXJyZW50Rm9jdXMgPj0geC5sZW5ndGgpIHRoaXMuY3VycmVudEZvY3VzID0gMDtcclxuICAgICAgICBpZiAodGhpcy5jdXJyZW50Rm9jdXMgPCAwKSB0aGlzLmN1cnJlbnRGb2N1cyA9ICh4Lmxlbmd0aCAtIDEpO1xyXG4gICAgICAgIC8qYWRkIGNsYXNzIFwiYXV0b2NvbXBsZXRlLWFjdGl2ZVwiOiovXHJcbiAgICAgICAgeFt0aGlzLmN1cnJlbnRGb2N1c10uY2xhc3NMaXN0LmFkZChcInRybi1hdXRvLWNvbXBsZXRlLWFjdGl2ZVwiKTtcclxuICAgIH1cclxuXHJcbiAgICByZW1vdmVBY3RpdmUoeCkge1xyXG4gICAgICAgIC8qYSBmdW5jdGlvbiB0byByZW1vdmUgdGhlIFwiYWN0aXZlXCIgY2xhc3MgZnJvbSBhbGwgYXV0b2NvbXBsZXRlIGl0ZW1zOiovXHJcbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCB4Lmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgIHhbaV0uY2xhc3NMaXN0LnJlbW92ZShcInRybi1hdXRvLWNvbXBsZXRlLWFjdGl2ZVwiKTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcblxyXG4gICAgY2xvc2VBbGxMaXN0cyhlbGVtZW50KSB7XHJcbiAgICAgICAgY29uc29sZS5sb2coXCJjbG9zZSBhbGwgbGlzdHNcIik7XHJcbiAgICAgICAgLypjbG9zZSBhbGwgYXV0b2NvbXBsZXRlIGxpc3RzIGluIHRoZSBkb2N1bWVudCxcclxuICAgICAgICAgZXhjZXB0IHRoZSBvbmUgcGFzc2VkIGFzIGFuIGFyZ3VtZW50OiovXHJcbiAgICAgICAgbGV0IHggPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKFwidHJuLWF1dG8tY29tcGxldGUtaXRlbXNcIik7XHJcbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCB4Lmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgIGlmIChlbGVtZW50ICE9PSB4W2ldICYmIGVsZW1lbnQgIT09IHRoaXMubmFtZUlucHV0KSB7XHJcbiAgICAgICAgICAgICAgICB4W2ldLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoeFtpXSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICB9XHJcbn1cclxuXHJcbi8vIEZpcnN0LCBjaGVja3MgaWYgaXQgaXNuJ3QgaW1wbGVtZW50ZWQgeWV0LlxyXG5pZiAoIVN0cmluZy5wcm90b3R5cGUuZm9ybWF0KSB7XHJcbiAgICBTdHJpbmcucHJvdG90eXBlLmZvcm1hdCA9IGZ1bmN0aW9uKCkge1xyXG4gICAgICAgIGNvbnN0IGFyZ3MgPSBhcmd1bWVudHM7XHJcbiAgICAgICAgcmV0dXJuIHRoaXMucmVwbGFjZSgveyhcXGQrKX0vZywgZnVuY3Rpb24obWF0Y2gsIG51bWJlcikge1xyXG4gICAgICAgICAgICByZXR1cm4gdHlwZW9mIGFyZ3NbbnVtYmVyXSAhPT0gJ3VuZGVmaW5lZCdcclxuICAgICAgICAgICAgICAgID8gYXJnc1tudW1iZXJdXHJcbiAgICAgICAgICAgICAgICA6IG1hdGNoXHJcbiAgICAgICAgICAgICAgICA7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9O1xyXG59Il0sInNvdXJjZVJvb3QiOiIifQ==