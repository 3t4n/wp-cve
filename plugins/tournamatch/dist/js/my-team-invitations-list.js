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
/******/ 	return __webpack_require__(__webpack_require__.s = 25);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/my-team-invitations-list.js":
/*!********************************************!*\
  !*** ./src/js/my-team-invitations-list.js ***!
  \********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./tournamatch.js */ "./src/js/tournamatch.js");
/**
 * Handles events for the list that displays a user's received invitations to join a team.
 *
 * @link       https://www.tournamatch.com
 * @since      3.13.0
  *
 * @package    Tournamatch
 *
 */


(function ($) {
  'use strict';

  window.addEventListener('load', function () {
    var options = trn_my_team_invitations_list_options;
    $.event('my-team-invitations').addEventListener('changed', function () {
      getTeamInvitations();
    });

    function acceptTeamInvitation(invitation_id) {
      console.log('accept');
      var xhr = new XMLHttpRequest();
      xhr.open('POST', options.api_url + 'team-invitations/' + invitation_id + '/accept');
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        var response = JSON.parse(xhr.response);

        if (xhr.status === 200) {
          $.event('my-team-invitations').dispatchEvent(new Event('changed'));
        } else {
          console.log(xhr.response);
          document.getElementById('trn-my-team-invitations-response').innerHTML = "<div class=\"trn-alert trn-alert-danger\"><strong>".concat(options.language.failure, ":</strong> ").concat(response.message, "</div>");
        }
      };

      xhr.send($.param({
        invitation_id: invitation_id
      }));
    }

    function declineTeamInvitation(invitation_id) {
      console.log('decline');
      var xhr = new XMLHttpRequest();
      xhr.open('POST', options.api_url + 'team-invitations/' + invitation_id + '/decline');
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        console.log(xhr.response);

        if (xhr.status === 200) {
          $.event('my-team-invitations').dispatchEvent(new Event('changed'));
        } else {
          console.log(xhr.response); // display error somewhere
        }
      };

      xhr.send($.param({
        invitation_id: invitation_id
      }));
    }

    function handleAcceptClick(event) {
      acceptTeamInvitation(this.dataset.invitationId);
    }

    function handleDeclineClick(event) {
      declineTeamInvitation(this.dataset.invitationId);
    }

    function addListeners() {
      console.log('adding handlers for team invitations.');
      var acceptLinks = document.getElementsByClassName('trn-accept-team-invitation-link');
      Array.prototype.forEach.call(acceptLinks, function (acceptLink) {
        console.log('add');
        acceptLink.addEventListener('click', handleAcceptClick);
      });
      var declineLinks = document.getElementsByClassName('trn-decline-team-invitation-link');
      Array.prototype.forEach.call(declineLinks, function (declineLink) {
        console.log('add');
        declineLink.addEventListener('click', handleDeclineClick);
      });
    }

    function removeListeners() {
      console.log('removing handlers for team invitations.');
      var acceptLinks = document.getElementsByClassName('trn-accept-team-invitation-link');
      Array.prototype.forEach.call(acceptLinks, function (acceptLink) {
        console.log('remove');
        acceptLink.removeEventListener('click', handleAcceptClick);
      });
      var declineLinks = document.getElementsByClassName('trn-decline-team-invitation-link');
      Array.prototype.forEach.call(declineLinks, function (declineLink) {
        console.log('remove');
        declineLink.removeEventListener('click', handleDeclineClick);
      });
    }

    function getTeamInvitations() {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', options.api_url + 'team-invitations/?_embed&' + $.param({
        user_id: options.user_id
      }));
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        //console.log(xhr);
        var content = "";

        if (xhr.status === 200) {
          var invitations = JSON.parse(xhr.response);

          if (invitations !== null && invitations.length > 0) {
            content += "<ul class=\"trn-list-unstyled\" id=\"trn-my-team-invitations-list\">";
            Array.prototype.forEach.call(invitations, function (invitation) {
              content += "<li class=\"trn-text-center\" id=\"trn-join-team-invitation-".concat(invitation.team_member_invitation_id, "\">");
              content += "<a href=\"".concat(invitation._embedded.team[0].link, "\">").concat(invitation._embedded.team[0].name, "</a> ");
              content += "<a class=\"trn-accept-team-invitation-link\" data-invitation-id=\"".concat(invitation.team_member_invitation_id, "\"><i class=\"fa fa-check trn-text-success\"></i></a> ");
              content += "<a class=\"trn-decline-team-invitation-link\" data-invitation-id=\"".concat(invitation.team_member_invitation_id, "\"><i class=\"fa fa-times trn-text-danger\"></i></a>");
              content += "</li>";
            });
            content += "</ul>";
          } else {
            content += "<p class=\"trn-text-center\">".concat(options.language.zero_invitations, "</p>");
          }
        } else {
          content += "<p class=\"trn-text-center\">".concat(options.language.error, "</p>");
        }

        removeListeners();
        document.getElementById('trn-my-team-invitations-response').nextSibling.remove();
        document.getElementById('trn-my-team-invitations-section').innerHTML += content;
        addListeners();
      };

      xhr.send();
    }

    getTeamInvitations();
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

/***/ 25:
/*!**************************************************!*\
  !*** multi ./src/js/my-team-invitations-list.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\my-team-invitations-list.js */"./src/js/my-team-invitations-list.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL215LXRlYW0taW52aXRhdGlvbnMtbGlzdC5qcyIsIndlYnBhY2s6Ly8vLi9zcmMvanMvdG91cm5hbWF0Y2guanMiXSwibmFtZXMiOlsiJCIsIndpbmRvdyIsImFkZEV2ZW50TGlzdGVuZXIiLCJvcHRpb25zIiwidHJuX215X3RlYW1faW52aXRhdGlvbnNfbGlzdF9vcHRpb25zIiwiZXZlbnQiLCJnZXRUZWFtSW52aXRhdGlvbnMiLCJhY2NlcHRUZWFtSW52aXRhdGlvbiIsImludml0YXRpb25faWQiLCJjb25zb2xlIiwibG9nIiwieGhyIiwiWE1MSHR0cFJlcXVlc3QiLCJvcGVuIiwiYXBpX3VybCIsInNldFJlcXVlc3RIZWFkZXIiLCJyZXN0X25vbmNlIiwib25sb2FkIiwicmVzcG9uc2UiLCJKU09OIiwicGFyc2UiLCJzdGF0dXMiLCJkaXNwYXRjaEV2ZW50IiwiRXZlbnQiLCJkb2N1bWVudCIsImdldEVsZW1lbnRCeUlkIiwiaW5uZXJIVE1MIiwibGFuZ3VhZ2UiLCJmYWlsdXJlIiwibWVzc2FnZSIsInNlbmQiLCJwYXJhbSIsImRlY2xpbmVUZWFtSW52aXRhdGlvbiIsImhhbmRsZUFjY2VwdENsaWNrIiwiZGF0YXNldCIsImludml0YXRpb25JZCIsImhhbmRsZURlY2xpbmVDbGljayIsImFkZExpc3RlbmVycyIsImFjY2VwdExpbmtzIiwiZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSIsIkFycmF5IiwicHJvdG90eXBlIiwiZm9yRWFjaCIsImNhbGwiLCJhY2NlcHRMaW5rIiwiZGVjbGluZUxpbmtzIiwiZGVjbGluZUxpbmsiLCJyZW1vdmVMaXN0ZW5lcnMiLCJyZW1vdmVFdmVudExpc3RlbmVyIiwidXNlcl9pZCIsImNvbnRlbnQiLCJpbnZpdGF0aW9ucyIsImxlbmd0aCIsImludml0YXRpb24iLCJ0ZWFtX21lbWJlcl9pbnZpdGF0aW9uX2lkIiwiX2VtYmVkZGVkIiwidGVhbSIsImxpbmsiLCJuYW1lIiwiemVyb19pbnZpdGF0aW9ucyIsImVycm9yIiwibmV4dFNpYmxpbmciLCJyZW1vdmUiLCJ0cm4iLCJUb3VybmFtYXRjaCIsImV2ZW50cyIsIm9iamVjdCIsInByZWZpeCIsInN0ciIsInByb3AiLCJoYXNPd25Qcm9wZXJ0eSIsImsiLCJ2IiwicHVzaCIsImVuY29kZVVSSUNvbXBvbmVudCIsImpvaW4iLCJldmVudE5hbWUiLCJFdmVudFRhcmdldCIsImlucHV0IiwiZGF0YUNhbGxiYWNrIiwiVG91cm5hbWF0Y2hfQXV0b2NvbXBsZXRlIiwicyIsImNoYXJBdCIsInRvVXBwZXJDYXNlIiwic2xpY2UiLCJudW1iZXIiLCJyZW1haW5kZXIiLCJlbGVtZW50IiwidGFicyIsInBhbmVzIiwiY2xlYXJBY3RpdmUiLCJ0YWIiLCJjbGFzc0xpc3QiLCJhcmlhU2VsZWN0ZWQiLCJwYW5lIiwic2V0QWN0aXZlIiwidGFyZ2V0SWQiLCJ0YXJnZXRUYWIiLCJxdWVyeVNlbGVjdG9yIiwidGFyZ2V0UGFuZUlkIiwidGFyZ2V0IiwiYWRkIiwidGFiQ2xpY2siLCJjdXJyZW50VGFyZ2V0IiwicHJldmVudERlZmF1bHQiLCJsb2NhdGlvbiIsImhhc2giLCJzdWJzdHIiLCJ0cm5fb2JqX2luc3RhbmNlIiwidGFiVmlld3MiLCJmcm9tIiwiZHJvcGRvd25zIiwiaGFuZGxlRHJvcGRvd25DbG9zZSIsImRyb3Bkb3duIiwibmV4dEVsZW1lbnRTaWJsaW5nIiwiZSIsInN0b3BQcm9wYWdhdGlvbiIsIm5hbWVJbnB1dCIsImEiLCJiIiwiaSIsInZhbCIsInZhbHVlIiwicGFyZW50IiwicGFyZW50Tm9kZSIsInRoZW4iLCJkYXRhIiwiY2xvc2VBbGxMaXN0cyIsImN1cnJlbnRGb2N1cyIsImNyZWF0ZUVsZW1lbnQiLCJzZXRBdHRyaWJ1dGUiLCJpZCIsImFwcGVuZENoaWxkIiwidGV4dCIsInNlbGVjdGVkSWQiLCJ4IiwiZ2V0RWxlbWVudHNCeVRhZ05hbWUiLCJrZXlDb2RlIiwiYWRkQWN0aXZlIiwiY2xpY2siLCJyZW1vdmVBY3RpdmUiLCJyZW1vdmVDaGlsZCIsIlN0cmluZyIsImZvcm1hdCIsImFyZ3MiLCJhcmd1bWVudHMiLCJyZXBsYWNlIiwibWF0Y2giXSwibWFwcGluZ3MiOiI7UUFBQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTs7O1FBR0E7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLDBDQUEwQyxnQ0FBZ0M7UUFDMUU7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSx3REFBd0Qsa0JBQWtCO1FBQzFFO1FBQ0EsaURBQWlELGNBQWM7UUFDL0Q7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBLHlDQUF5QyxpQ0FBaUM7UUFDMUUsZ0hBQWdILG1CQUFtQixFQUFFO1FBQ3JJO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0EsMkJBQTJCLDBCQUEwQixFQUFFO1FBQ3ZELGlDQUFpQyxlQUFlO1FBQ2hEO1FBQ0E7UUFDQTs7UUFFQTtRQUNBLHNEQUFzRCwrREFBK0Q7O1FBRXJIO1FBQ0E7OztRQUdBO1FBQ0E7Ozs7Ozs7Ozs7Ozs7QUNsRkE7QUFBQTtBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLENBQUMsVUFBVUEsQ0FBVixFQUFhO0FBQ1Y7O0FBRUFDLFFBQU0sQ0FBQ0MsZ0JBQVAsQ0FBd0IsTUFBeEIsRUFBZ0MsWUFBWTtBQUN4QyxRQUFNQyxPQUFPLEdBQUdDLG9DQUFoQjtBQUVBSixLQUFDLENBQUNLLEtBQUYsQ0FBUSxxQkFBUixFQUErQkgsZ0JBQS9CLENBQWdELFNBQWhELEVBQTJELFlBQVc7QUFDbEVJLHdCQUFrQjtBQUNyQixLQUZEOztBQUlBLGFBQVNDLG9CQUFULENBQThCQyxhQUE5QixFQUE2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cyIsInNvdXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwgeyBlbnVtZXJhYmxlOiB0cnVlLCBnZXQ6IGdldHRlciB9KTtcbiBcdFx0fVxuIFx0fTtcblxuIFx0Ly8gZGVmaW5lIF9fZXNNb2R1bGUgb24gZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yID0gZnVuY3Rpb24oZXhwb3J0cykge1xuIFx0XHRpZih0eXBlb2YgU3ltYm9sICE9PSAndW5kZWZpbmVkJyAmJiBTeW1ib2wudG9TdHJpbmdUYWcpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgU3ltYm9sLnRvU3RyaW5nVGFnLCB7IHZhbHVlOiAnTW9kdWxlJyB9KTtcbiBcdFx0fVxuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgJ19fZXNNb2R1bGUnLCB7IHZhbHVlOiB0cnVlIH0pO1xuIFx0fTtcblxuIFx0Ly8gY3JlYXRlIGEgZmFrZSBuYW1lc3BhY2Ugb2JqZWN0XG4gXHQvLyBtb2RlICYgMTogdmFsdWUgaXMgYSBtb2R1bGUgaWQsIHJlcXVpcmUgaXRcbiBcdC8vIG1vZGUgJiAyOiBtZXJnZSBhbGwgcHJvcGVydGllcyBvZiB2YWx1ZSBpbnRvIHRoZSBuc1xuIFx0Ly8gbW9kZSAmIDQ6IHJldHVybiB2YWx1ZSB3aGVuIGFscmVhZHkgbnMgb2JqZWN0XG4gXHQvLyBtb2RlICYgOHwxOiBiZWhhdmUgbGlrZSByZXF1aXJlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnQgPSBmdW5jdGlvbih2YWx1ZSwgbW9kZSkge1xuIFx0XHRpZihtb2RlICYgMSkgdmFsdWUgPSBfX3dlYnBhY2tfcmVxdWlyZV9fKHZhbHVlKTtcbiBcdFx0aWYobW9kZSAmIDgpIHJldHVybiB2YWx1ZTtcbiBcdFx0aWYoKG1vZGUgJiA0KSAmJiB0eXBlb2YgdmFsdWUgPT09ICdvYmplY3QnICYmIHZhbHVlICYmIHZhbHVlLl9fZXNNb2R1bGUpIHJldHVybiB2YWx1ZTtcbiBcdFx0dmFyIG5zID0gT2JqZWN0LmNyZWF0ZShudWxsKTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yKG5zKTtcbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KG5zLCAnZGVmYXVsdCcsIHsgZW51bWVyYWJsZTogdHJ1ZSwgdmFsdWU6IHZhbHVlIH0pO1xuIFx0XHRpZihtb2RlICYgMiAmJiB0eXBlb2YgdmFsdWUgIT0gJ3N0cmluZycpIGZvcih2YXIga2V5IGluIHZhbHVlKSBfX3dlYnBhY2tfcmVxdWlyZV9fLmQobnMsIGtleSwgZnVuY3Rpb24oa2V5KSB7IHJldHVybiB2YWx1ZVtrZXldOyB9LmJpbmQobnVsbCwga2V5KSk7XG4gXHRcdHJldHVybiBucztcbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiXCI7XG5cblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSAyNSk7XG4iLCIvKipcclxuICogSGFuZGxlcyBldmVudHMgZm9yIHRoZSBsaXN0IHRoYXQgZGlzcGxheXMgYSB1c2VyJ3MgcmVjZWl2ZWQgaW52aXRhdGlvbnMgdG8gam9pbiBhIHRlYW0uXHJcbiAqXHJcbiAqIEBsaW5rICAgICAgIGh0dHBzOi8vd3d3LnRvdXJuYW1hdGNoLmNvbVxyXG4gKiBAc2luY2UgICAgICAzLjEzLjBcclxuICAqXHJcbiAqIEBwYWNrYWdlICAgIFRvdXJuYW1hdGNoXHJcbiAqXHJcbiAqL1xyXG5pbXBvcnQgeyB0cm4gfSBmcm9tICcuL3RvdXJuYW1hdGNoLmpzJztcclxuXHJcbihmdW5jdGlvbiAoJCkge1xyXG4gICAgJ3VzZSBzdHJpY3QnO1xyXG5cclxuICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIGNvbnN0IG9wdGlvbnMgPSB0cm5fbXlfdGVhbV9pbnZpdGF0aW9uc19saXN0X29wdGlvbnM7XHJcblxyXG4gICAgICAgICQuZXZlbnQoJ215LXRlYW0taW52aXRhdGlvbnMnKS5hZGRFdmVudExpc3RlbmVyKCdjaGFuZ2VkJywgZnVuY3Rpb24oKSB7XHJcbiAgICAgICAgICAgIGdldFRlYW1JbnZpdGF0aW9ucygpO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICBmdW5jdGlvbiBhY2NlcHRUZWFtSW52aXRhdGlvbihpbnZpdGF0aW9uX2lkKSB7XHJcbiAgICAgICAgICAgIGNvbnNvbGUubG9nKCdhY2NlcHQnKTtcclxuICAgICAgICAgICAgbGV0IHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xyXG4gICAgICAgICAgICB4aHIub3BlbignUE9TVCcsIG9wdGlvbnMuYXBpX3VybCArICd0ZWFtLWludml0YXRpb25zLycgKyBpbnZpdGF0aW9uX2lkICsgJy9hY2NlcHQnKTtcclxuICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ1gtV1AtTm9uY2UnLCBvcHRpb25zLnJlc3Rfbm9uY2UpO1xyXG4gICAgICAgICAgICB4aHIub25sb2FkID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgbGV0IHJlc3BvbnNlID0gSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpO1xyXG4gICAgICAgICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMCkge1xyXG4gICAgICAgICAgICAgICAgICAgICQuZXZlbnQoJ215LXRlYW0taW52aXRhdGlvbnMnKS5kaXNwYXRjaEV2ZW50KG5ldyBFdmVudCgnY2hhbmdlZCcpKTtcclxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2coeGhyLnJlc3BvbnNlKTtcclxuICAgICAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLW15LXRlYW0taW52aXRhdGlvbnMtcmVzcG9uc2UnKS5pbm5lckhUTUwgPSBgPGRpdiBjbGFzcz1cInRybi1hbGVydCB0cm4tYWxlcnQtZGFuZ2VyXCI+PHN0cm9uZz4ke29wdGlvbnMubGFuZ3VhZ2UuZmFpbHVyZX06PC9zdHJvbmc+ICR7cmVzcG9uc2UubWVzc2FnZX08L2Rpdj5gO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9O1xyXG5cclxuICAgICAgICAgICAgeGhyLnNlbmQoJC5wYXJhbSh7XHJcbiAgICAgICAgICAgICAgICBpbnZpdGF0aW9uX2lkOiBpbnZpdGF0aW9uX2lkXHJcbiAgICAgICAgICAgIH0pKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIGRlY2xpbmVUZWFtSW52aXRhdGlvbihpbnZpdGF0aW9uX2lkKSB7XHJcbiAgICAgICAgICAgIGNvbnNvbGUubG9nKCdkZWNsaW5lJyk7XHJcbiAgICAgICAgICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgeGhyLm9wZW4oJ1BPU1QnLCBvcHRpb25zLmFwaV91cmwgKyAndGVhbS1pbnZpdGF0aW9ucy8nICsgaW52aXRhdGlvbl9pZCArICcvZGVjbGluZScpO1xyXG4gICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xyXG4gICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignWC1XUC1Ob25jZScsIG9wdGlvbnMucmVzdF9ub25jZSk7XHJcbiAgICAgICAgICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyh4aHIucmVzcG9uc2UpO1xyXG4gICAgICAgICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMCkge1xyXG4gICAgICAgICAgICAgICAgICAgICQuZXZlbnQoJ215LXRlYW0taW52aXRhdGlvbnMnKS5kaXNwYXRjaEV2ZW50KG5ldyBFdmVudCgnY2hhbmdlZCcpKTtcclxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2coeGhyLnJlc3BvbnNlKTtcclxuICAgICAgICAgICAgICAgICAgICAvLyBkaXNwbGF5IGVycm9yIHNvbWV3aGVyZVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9O1xyXG5cclxuICAgICAgICAgICAgeGhyLnNlbmQoJC5wYXJhbSh7XHJcbiAgICAgICAgICAgICAgICBpbnZpdGF0aW9uX2lkOiBpbnZpdGF0aW9uX2lkXHJcbiAgICAgICAgICAgIH0pKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIGhhbmRsZUFjY2VwdENsaWNrKGV2ZW50KSB7XHJcbiAgICAgICAgICAgIGFjY2VwdFRlYW1JbnZpdGF0aW9uKHRoaXMuZGF0YXNldC5pbnZpdGF0aW9uSWQpXHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBmdW5jdGlvbiBoYW5kbGVEZWNsaW5lQ2xpY2soZXZlbnQpIHtcclxuICAgICAgICAgICAgZGVjbGluZVRlYW1JbnZpdGF0aW9uKHRoaXMuZGF0YXNldC5pbnZpdGF0aW9uSWQpXHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBmdW5jdGlvbiBhZGRMaXN0ZW5lcnMoKSB7XHJcbiAgICAgICAgICAgIGNvbnNvbGUubG9nKCdhZGRpbmcgaGFuZGxlcnMgZm9yIHRlYW0gaW52aXRhdGlvbnMuJylcclxuICAgICAgICAgICAgbGV0IGFjY2VwdExpbmtzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLWFjY2VwdC10ZWFtLWludml0YXRpb24tbGluaycpO1xyXG4gICAgICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKGFjY2VwdExpbmtzLCAgZnVuY3Rpb24oYWNjZXB0TGluaykge1xyXG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coJ2FkZCcpO1xyXG4gICAgICAgICAgICAgICAgYWNjZXB0TGluay5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGhhbmRsZUFjY2VwdENsaWNrKTtcclxuICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICBsZXQgZGVjbGluZUxpbmtzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLWRlY2xpbmUtdGVhbS1pbnZpdGF0aW9uLWxpbmsnKTtcclxuICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbChkZWNsaW5lTGlua3MsIGZ1bmN0aW9uKGRlY2xpbmVMaW5rKSB7XHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZygnYWRkJyk7XHJcbiAgICAgICAgICAgICAgICBkZWNsaW5lTGluay5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGhhbmRsZURlY2xpbmVDbGljayk7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZnVuY3Rpb24gcmVtb3ZlTGlzdGVuZXJzKCkge1xyXG4gICAgICAgICAgICBjb25zb2xlLmxvZygncmVtb3ZpbmcgaGFuZGxlcnMgZm9yIHRlYW0gaW52aXRhdGlvbnMuJylcclxuICAgICAgICAgICAgbGV0IGFjY2VwdExpbmtzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLWFjY2VwdC10ZWFtLWludml0YXRpb24tbGluaycpO1xyXG4gICAgICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKGFjY2VwdExpbmtzLCAgZnVuY3Rpb24oYWNjZXB0TGluaykge1xyXG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coJ3JlbW92ZScpO1xyXG4gICAgICAgICAgICAgICAgYWNjZXB0TGluay5yZW1vdmVFdmVudExpc3RlbmVyKCdjbGljaycsIGhhbmRsZUFjY2VwdENsaWNrKTtcclxuICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICBsZXQgZGVjbGluZUxpbmtzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLWRlY2xpbmUtdGVhbS1pbnZpdGF0aW9uLWxpbmsnKTtcclxuICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbChkZWNsaW5lTGlua3MsIGZ1bmN0aW9uKGRlY2xpbmVMaW5rKSB7XHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZygncmVtb3ZlJyk7XHJcbiAgICAgICAgICAgICAgICBkZWNsaW5lTGluay5yZW1vdmVFdmVudExpc3RlbmVyKCdjbGljaycsIGhhbmRsZURlY2xpbmVDbGljayk7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZnVuY3Rpb24gZ2V0VGVhbUludml0YXRpb25zKCkge1xyXG4gICAgICAgICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgIHhoci5vcGVuKCdHRVQnLCBvcHRpb25zLmFwaV91cmwgKyAndGVhbS1pbnZpdGF0aW9ucy8/X2VtYmVkJicgKyAkLnBhcmFtKHt1c2VyX2lkOiBvcHRpb25zLnVzZXJfaWR9KSk7XHJcbiAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdDb250ZW50LVR5cGUnLCAnYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkJyk7XHJcbiAgICAgICAgICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgIC8vY29uc29sZS5sb2coeGhyKTtcclxuICAgICAgICAgICAgICAgIGxldCBjb250ZW50ID0gYGA7XHJcbiAgICAgICAgICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IGludml0YXRpb25zID0gSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBpZiAoIGludml0YXRpb25zICE9PSBudWxsICYmIGludml0YXRpb25zLmxlbmd0aCA+IDAgKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDx1bCBjbGFzcz1cInRybi1saXN0LXVuc3R5bGVkXCIgaWQ9XCJ0cm4tbXktdGVhbS1pbnZpdGF0aW9ucy1saXN0XCI+YDtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwoaW52aXRhdGlvbnMsIGZ1bmN0aW9uKGludml0YXRpb24pIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxsaSBjbGFzcz1cInRybi10ZXh0LWNlbnRlclwiIGlkPVwidHJuLWpvaW4tdGVhbS1pbnZpdGF0aW9uLSR7aW52aXRhdGlvbi50ZWFtX21lbWJlcl9pbnZpdGF0aW9uX2lkfVwiPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8YSBocmVmPVwiJHtpbnZpdGF0aW9uLl9lbWJlZGRlZC50ZWFtWzBdLmxpbmt9XCI+JHtpbnZpdGF0aW9uLl9lbWJlZGRlZC50ZWFtWzBdLm5hbWV9PC9hPiBgO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPGEgY2xhc3M9XCJ0cm4tYWNjZXB0LXRlYW0taW52aXRhdGlvbi1saW5rXCIgZGF0YS1pbnZpdGF0aW9uLWlkPVwiJHtpbnZpdGF0aW9uLnRlYW1fbWVtYmVyX2ludml0YXRpb25faWR9XCI+PGkgY2xhc3M9XCJmYSBmYS1jaGVjayB0cm4tdGV4dC1zdWNjZXNzXCI+PC9pPjwvYT4gYDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxhIGNsYXNzPVwidHJuLWRlY2xpbmUtdGVhbS1pbnZpdGF0aW9uLWxpbmtcIiBkYXRhLWludml0YXRpb24taWQ9XCIke2ludml0YXRpb24udGVhbV9tZW1iZXJfaW52aXRhdGlvbl9pZH1cIj48aSBjbGFzcz1cImZhIGZhLXRpbWVzIHRybi10ZXh0LWRhbmdlclwiPjwvaT48L2E+YDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDwvbGk+YDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8L3VsPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPHAgY2xhc3M9XCJ0cm4tdGV4dC1jZW50ZXJcIj4ke29wdGlvbnMubGFuZ3VhZ2UuemVyb19pbnZpdGF0aW9uc308L3A+YDtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxwIGNsYXNzPVwidHJuLXRleHQtY2VudGVyXCI+JHtvcHRpb25zLmxhbmd1YWdlLmVycm9yfTwvcD5gO1xyXG4gICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgIHJlbW92ZUxpc3RlbmVycygpO1xyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3Rybi1teS10ZWFtLWludml0YXRpb25zLXJlc3BvbnNlJykubmV4dFNpYmxpbmcucmVtb3ZlKCk7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLW15LXRlYW0taW52aXRhdGlvbnMtc2VjdGlvbicpLmlubmVySFRNTCArPSBjb250ZW50O1xyXG4gICAgICAgICAgICAgICAgYWRkTGlzdGVuZXJzKCk7XHJcbiAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgICAgICB4aHIuc2VuZCgpO1xyXG4gICAgICAgIH1cclxuICAgICAgICBnZXRUZWFtSW52aXRhdGlvbnMoKTtcclxuICAgIH0sIGZhbHNlKTtcclxufSkodHJuKTsiLCIndXNlIHN0cmljdCc7XHJcbmNsYXNzIFRvdXJuYW1hdGNoIHtcclxuXHJcbiAgICBjb25zdHJ1Y3RvcigpIHtcclxuICAgICAgICB0aGlzLmV2ZW50cyA9IHt9O1xyXG4gICAgfVxyXG5cclxuICAgIHBhcmFtKG9iamVjdCwgcHJlZml4KSB7XHJcbiAgICAgICAgbGV0IHN0ciA9IFtdO1xyXG4gICAgICAgIGZvciAobGV0IHByb3AgaW4gb2JqZWN0KSB7XHJcbiAgICAgICAgICAgIGlmIChvYmplY3QuaGFzT3duUHJvcGVydHkocHJvcCkpIHtcclxuICAgICAgICAgICAgICAgIGxldCBrID0gcHJlZml4ID8gcHJlZml4ICsgXCJbXCIgKyBwcm9wICsgXCJdXCIgOiBwcm9wO1xyXG4gICAgICAgICAgICAgICAgbGV0IHYgPSBvYmplY3RbcHJvcF07XHJcbiAgICAgICAgICAgICAgICBzdHIucHVzaCgodiAhPT0gbnVsbCAmJiB0eXBlb2YgdiA9PT0gXCJvYmplY3RcIikgPyB0aGlzLnBhcmFtKHYsIGspIDogZW5jb2RlVVJJQ29tcG9uZW50KGspICsgXCI9XCIgKyBlbmNvZGVVUklDb21wb25lbnQodikpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiBzdHIuam9pbihcIiZcIik7XHJcbiAgICB9XHJcblxyXG4gICAgZXZlbnQoZXZlbnROYW1lKSB7XHJcbiAgICAgICAgaWYgKCEoZXZlbnROYW1lIGluIHRoaXMuZXZlbnRzKSkge1xyXG4gICAgICAgICAgICB0aGlzLmV2ZW50c1tldmVudE5hbWVdID0gbmV3IEV2ZW50VGFyZ2V0KGV2ZW50TmFtZSk7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiB0aGlzLmV2ZW50c1tldmVudE5hbWVdO1xyXG4gICAgfVxyXG5cclxuICAgIGF1dG9jb21wbGV0ZShpbnB1dCwgZGF0YUNhbGxiYWNrKSB7XHJcbiAgICAgICAgbmV3IFRvdXJuYW1hdGNoX0F1dG9jb21wbGV0ZShpbnB1dCwgZGF0YUNhbGxiYWNrKTtcclxuICAgIH1cclxuXHJcbiAgICB1Y2ZpcnN0KHMpIHtcclxuICAgICAgICBpZiAodHlwZW9mIHMgIT09ICdzdHJpbmcnKSByZXR1cm4gJyc7XHJcbiAgICAgICAgcmV0dXJuIHMuY2hhckF0KDApLnRvVXBwZXJDYXNlKCkgKyBzLnNsaWNlKDEpO1xyXG4gICAgfVxyXG5cclxuICAgIG9yZGluYWxfc3VmZml4KG51bWJlcikge1xyXG4gICAgICAgIGNvbnN0IHJlbWFpbmRlciA9IG51bWJlciAlIDEwMDtcclxuXHJcbiAgICAgICAgaWYgKChyZW1haW5kZXIgPCAxMSkgfHwgKHJlbWFpbmRlciA+IDEzKSkge1xyXG4gICAgICAgICAgICBzd2l0Y2ggKHJlbWFpbmRlciAlIDEwKSB7XHJcbiAgICAgICAgICAgICAgICBjYXNlIDE6IHJldHVybiAnc3QnO1xyXG4gICAgICAgICAgICAgICAgY2FzZSAyOiByZXR1cm4gJ25kJztcclxuICAgICAgICAgICAgICAgIGNhc2UgMzogcmV0dXJuICdyZCc7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgcmV0dXJuICd0aCc7XHJcbiAgICB9XHJcblxyXG4gICAgdGFicyhlbGVtZW50KSB7XHJcbiAgICAgICAgY29uc3QgdGFicyA9IGVsZW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLW5hdi1saW5rJyk7XHJcbiAgICAgICAgY29uc3QgcGFuZXMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tdGFiLXBhbmUnKTtcclxuICAgICAgICBjb25zdCBjbGVhckFjdGl2ZSA9ICgpID0+IHtcclxuICAgICAgICAgICAgQXJyYXkucHJvdG90eXBlLmZvckVhY2guY2FsbCh0YWJzLCAodGFiKSA9PiB7XHJcbiAgICAgICAgICAgICAgICB0YWIuY2xhc3NMaXN0LnJlbW92ZSgndHJuLW5hdi1hY3RpdmUnKTtcclxuICAgICAgICAgICAgICAgIHRhYi5hcmlhU2VsZWN0ZWQgPSBmYWxzZTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwocGFuZXMsIHBhbmUgPT4gcGFuZS5jbGFzc0xpc3QucmVtb3ZlKCd0cm4tdGFiLWFjdGl2ZScpKTtcclxuICAgICAgICB9O1xyXG4gICAgICAgIGNvbnN0IHNldEFjdGl2ZSA9ICh0YXJnZXRJZCkgPT4ge1xyXG4gICAgICAgICAgICBjb25zdCB0YXJnZXRUYWIgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdhW2hyZWY9XCIjJyArIHRhcmdldElkICsgJ1wiXS50cm4tbmF2LWxpbmsnKTtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0UGFuZUlkID0gdGFyZ2V0VGFiICYmIHRhcmdldFRhYi5kYXRhc2V0ICYmIHRhcmdldFRhYi5kYXRhc2V0LnRhcmdldCB8fCBmYWxzZTtcclxuXHJcbiAgICAgICAgICAgIGlmICh0YXJnZXRQYW5lSWQpIHtcclxuICAgICAgICAgICAgICAgIGNsZWFyQWN0aXZlKCk7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRUYWIuY2xhc3NMaXN0LmFkZCgndHJuLW5hdi1hY3RpdmUnKTtcclxuICAgICAgICAgICAgICAgIHRhcmdldFRhYi5hcmlhU2VsZWN0ZWQgPSB0cnVlO1xyXG5cclxuICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHRhcmdldFBhbmVJZCkuY2xhc3NMaXN0LmFkZCgndHJuLXRhYi1hY3RpdmUnKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH07XHJcbiAgICAgICAgY29uc3QgdGFiQ2xpY2sgPSAoZXZlbnQpID0+IHtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0VGFiID0gZXZlbnQuY3VycmVudFRhcmdldDtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0UGFuZUlkID0gdGFyZ2V0VGFiICYmIHRhcmdldFRhYi5kYXRhc2V0ICYmIHRhcmdldFRhYi5kYXRhc2V0LnRhcmdldCB8fCBmYWxzZTtcclxuXHJcbiAgICAgICAgICAgIGlmICh0YXJnZXRQYW5lSWQpIHtcclxuICAgICAgICAgICAgICAgIHNldEFjdGl2ZSh0YXJnZXRQYW5lSWQpO1xyXG4gICAgICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH07XHJcblxyXG4gICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwodGFicywgKHRhYikgPT4ge1xyXG4gICAgICAgICAgICB0YWIuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCB0YWJDbGljayk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIGlmIChsb2NhdGlvbi5oYXNoKSB7XHJcbiAgICAgICAgICAgIHNldEFjdGl2ZShsb2NhdGlvbi5oYXNoLnN1YnN0cigxKSk7XHJcbiAgICAgICAgfSBlbHNlIGlmICh0YWJzLmxlbmd0aCA+IDApIHtcclxuICAgICAgICAgICAgc2V0QWN0aXZlKHRhYnNbMF0uZGF0YXNldC50YXJnZXQpO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbn1cclxuXHJcbi8vdHJuLmluaXRpYWxpemUoKTtcclxuaWYgKCF3aW5kb3cudHJuX29ial9pbnN0YW5jZSkge1xyXG4gICAgd2luZG93LnRybl9vYmpfaW5zdGFuY2UgPSBuZXcgVG91cm5hbWF0Y2goKTtcclxuXHJcbiAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIGZ1bmN0aW9uICgpIHtcclxuXHJcbiAgICAgICAgY29uc3QgdGFiVmlld3MgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tbmF2Jyk7XHJcblxyXG4gICAgICAgIEFycmF5LmZyb20odGFiVmlld3MpLmZvckVhY2goKHRhYikgPT4ge1xyXG4gICAgICAgICAgICB0cm4udGFicyh0YWIpO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICBjb25zdCBkcm9wZG93bnMgPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCd0cm4tZHJvcGRvd24tdG9nZ2xlJyk7XHJcbiAgICAgICAgY29uc3QgaGFuZGxlRHJvcGRvd25DbG9zZSA9ICgpID0+IHtcclxuICAgICAgICAgICAgQXJyYXkuZnJvbShkcm9wZG93bnMpLmZvckVhY2goKGRyb3Bkb3duKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBkcm9wZG93bi5uZXh0RWxlbWVudFNpYmxpbmcuY2xhc3NMaXN0LnJlbW92ZSgndHJuLXNob3cnKTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgIGRvY3VtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBoYW5kbGVEcm9wZG93bkNsb3NlLCBmYWxzZSk7XHJcbiAgICAgICAgfTtcclxuXHJcbiAgICAgICAgQXJyYXkuZnJvbShkcm9wZG93bnMpLmZvckVhY2goKGRyb3Bkb3duKSA9PiB7XHJcbiAgICAgICAgICAgIGRyb3Bkb3duLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgZnVuY3Rpb24oZSkge1xyXG4gICAgICAgICAgICAgICAgZS5zdG9wUHJvcGFnYXRpb24oKTtcclxuICAgICAgICAgICAgICAgIHRoaXMubmV4dEVsZW1lbnRTaWJsaW5nLmNsYXNzTGlzdC5hZGQoJ3Rybi1zaG93Jyk7XHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgaGFuZGxlRHJvcGRvd25DbG9zZSwgZmFsc2UpO1xyXG4gICAgICAgICAgICB9LCBmYWxzZSk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgfSwgZmFsc2UpO1xyXG59XHJcbmV4cG9ydCBsZXQgdHJuID0gd2luZG93LnRybl9vYmpfaW5zdGFuY2U7XHJcblxyXG5jbGFzcyBUb3VybmFtYXRjaF9BdXRvY29tcGxldGUge1xyXG5cclxuICAgIC8vIGN1cnJlbnRGb2N1cztcclxuICAgIC8vXHJcbiAgICAvLyBuYW1lSW5wdXQ7XHJcbiAgICAvL1xyXG4gICAgLy8gc2VsZjtcclxuXHJcbiAgICBjb25zdHJ1Y3RvcihpbnB1dCwgZGF0YUNhbGxiYWNrKSB7XHJcbiAgICAgICAgLy8gdGhpcy5zZWxmID0gdGhpcztcclxuICAgICAgICB0aGlzLm5hbWVJbnB1dCA9IGlucHV0O1xyXG5cclxuICAgICAgICB0aGlzLm5hbWVJbnB1dC5hZGRFdmVudExpc3RlbmVyKFwiaW5wdXRcIiwgKCkgPT4ge1xyXG4gICAgICAgICAgICBsZXQgYSwgYiwgaSwgdmFsID0gdGhpcy5uYW1lSW5wdXQudmFsdWU7Ly90aGlzLnZhbHVlO1xyXG4gICAgICAgICAgICBsZXQgcGFyZW50ID0gdGhpcy5uYW1lSW5wdXQucGFyZW50Tm9kZTsvL3RoaXMucGFyZW50Tm9kZTtcclxuXHJcbiAgICAgICAgICAgIC8vIGxldCBwID0gbmV3IFByb21pc2UoKHJlc29sdmUsIHJlamVjdCkgPT4ge1xyXG4gICAgICAgICAgICAvLyAgICAgLyogbmVlZCB0byBxdWVyeSBzZXJ2ZXIgZm9yIG5hbWVzIGhlcmUuICovXHJcbiAgICAgICAgICAgIC8vICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIub3BlbignR0VUJywgb3B0aW9ucy5hcGlfdXJsICsgJ3BsYXllcnMvP3NlYXJjaD0nICsgdmFsICsgJyZwZXJfcGFnZT01Jyk7XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL3gtd3d3LWZvcm0tdXJsZW5jb2RlZCcpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ1gtV1AtTm9uY2UnLCBvcHRpb25zLnJlc3Rfbm9uY2UpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgLy8gICAgICAgICBpZiAoeGhyLnN0YXR1cyA9PT0gMjAwKSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgICAgIC8vIHJlc29sdmUoSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpLm1hcCgocGxheWVyKSA9PiB7cmV0dXJuIHsgJ3ZhbHVlJzogcGxheWVyLmlkLCAndGV4dCc6IHBsYXllci5uYW1lIH07fSkpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgICAgICByZXNvbHZlKEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKS5tYXAoKHBsYXllcikgPT4ge3JldHVybiBwbGF5ZXIubmFtZTt9KSk7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgLy8gICAgICAgICAgICAgcmVqZWN0KCk7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgfVxyXG4gICAgICAgICAgICAvLyAgICAgfTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5zZW5kKCk7XHJcbiAgICAgICAgICAgIC8vIH0pO1xyXG4gICAgICAgICAgICBkYXRhQ2FsbGJhY2sodmFsKS50aGVuKChkYXRhKSA9PiB7Ly9wLnRoZW4oKGRhdGEpID0+IHtcclxuICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKGRhdGEpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qY2xvc2UgYW55IGFscmVhZHkgb3BlbiBsaXN0cyBvZiBhdXRvLWNvbXBsZXRlZCB2YWx1ZXMqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5jbG9zZUFsbExpc3RzKCk7XHJcbiAgICAgICAgICAgICAgICBpZiAoIXZhbCkgeyByZXR1cm4gZmFsc2U7fVxyXG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50Rm9jdXMgPSAtMTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmNyZWF0ZSBhIERJViBlbGVtZW50IHRoYXQgd2lsbCBjb250YWluIHRoZSBpdGVtcyAodmFsdWVzKToqL1xyXG4gICAgICAgICAgICAgICAgYSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJESVZcIik7XHJcbiAgICAgICAgICAgICAgICBhLnNldEF0dHJpYnV0ZShcImlkXCIsIHRoaXMubmFtZUlucHV0LmlkICsgXCItYXV0by1jb21wbGV0ZS1saXN0XCIpO1xyXG4gICAgICAgICAgICAgICAgYS5zZXRBdHRyaWJ1dGUoXCJjbGFzc1wiLCBcInRybi1hdXRvLWNvbXBsZXRlLWl0ZW1zXCIpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qYXBwZW5kIHRoZSBESVYgZWxlbWVudCBhcyBhIGNoaWxkIG9mIHRoZSBhdXRvLWNvbXBsZXRlIGNvbnRhaW5lcjoqL1xyXG4gICAgICAgICAgICAgICAgcGFyZW50LmFwcGVuZENoaWxkKGEpO1xyXG5cclxuICAgICAgICAgICAgICAgIC8qZm9yIGVhY2ggaXRlbSBpbiB0aGUgYXJyYXkuLi4qL1xyXG4gICAgICAgICAgICAgICAgZm9yIChpID0gMDsgaSA8IGRhdGEubGVuZ3RoOyBpKyspIHtcclxuICAgICAgICAgICAgICAgICAgICBsZXQgdGV4dCwgdmFsdWU7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIC8qIFdoaWNoIGZvcm1hdCBkaWQgdGhleSBnaXZlIHVzLiAqL1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgZGF0YVtpXSA9PT0gJ29iamVjdCcpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdGV4dCA9IGRhdGFbaV1bJ3RleHQnXTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSBkYXRhW2ldWyd2YWx1ZSddO1xyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHRleHQgPSBkYXRhW2ldO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB2YWx1ZSA9IGRhdGFbaV07XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgICAgICAvKmNoZWNrIGlmIHRoZSBpdGVtIHN0YXJ0cyB3aXRoIHRoZSBzYW1lIGxldHRlcnMgYXMgdGhlIHRleHQgZmllbGQgdmFsdWU6Ki9cclxuICAgICAgICAgICAgICAgICAgICBpZiAodGV4dC5zdWJzdHIoMCwgdmFsLmxlbmd0aCkudG9VcHBlckNhc2UoKSA9PT0gdmFsLnRvVXBwZXJDYXNlKCkpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgLypjcmVhdGUgYSBESVYgZWxlbWVudCBmb3IgZWFjaCBtYXRjaGluZyBlbGVtZW50OiovXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiRElWXCIpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKm1ha2UgdGhlIG1hdGNoaW5nIGxldHRlcnMgYm9sZDoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmlubmVySFRNTCA9IFwiPHN0cm9uZz5cIiArIHRleHQuc3Vic3RyKDAsIHZhbC5sZW5ndGgpICsgXCI8L3N0cm9uZz5cIjtcclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgKz0gdGV4dC5zdWJzdHIodmFsLmxlbmd0aCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKmluc2VydCBhIGlucHV0IGZpZWxkIHRoYXQgd2lsbCBob2xkIHRoZSBjdXJyZW50IGFycmF5IGl0ZW0ncyB2YWx1ZToqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmlubmVySFRNTCArPSBcIjxpbnB1dCB0eXBlPSdoaWRkZW4nIHZhbHVlPSdcIiArIHZhbHVlICsgXCInPlwiO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5kYXRhc2V0LnZhbHVlID0gdmFsdWU7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuZGF0YXNldC50ZXh0ID0gdGV4dDtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHdoZW4gc29tZW9uZSBjbGlja3Mgb24gdGhlIGl0ZW0gdmFsdWUgKERJViBlbGVtZW50KToqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2coYGl0ZW0gY2xpY2tlZCB3aXRoIHZhbHVlICR7ZS5jdXJyZW50VGFyZ2V0LmRhdGFzZXQudmFsdWV9YCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLyogaW5zZXJ0IHRoZSB2YWx1ZSBmb3IgdGhlIGF1dG9jb21wbGV0ZSB0ZXh0IGZpZWxkOiAqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5uYW1lSW5wdXQudmFsdWUgPSBlLmN1cnJlbnRUYXJnZXQuZGF0YXNldC50ZXh0O1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5uYW1lSW5wdXQuZGF0YXNldC5zZWxlY3RlZElkID0gZS5jdXJyZW50VGFyZ2V0LmRhdGFzZXQudmFsdWU7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLyogY2xvc2UgdGhlIGxpc3Qgb2YgYXV0b2NvbXBsZXRlZCB2YWx1ZXMsIChvciBhbnkgb3RoZXIgb3BlbiBsaXN0cyBvZiBhdXRvY29tcGxldGVkIHZhbHVlczoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5jbG9zZUFsbExpc3RzKCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5uYW1lSW5wdXQuZGlzcGF0Y2hFdmVudChuZXcgRXZlbnQoJ2NoYW5nZScpKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGEuYXBwZW5kQ2hpbGQoYik7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgLypleGVjdXRlIGEgZnVuY3Rpb24gcHJlc3NlcyBhIGtleSBvbiB0aGUga2V5Ym9hcmQ6Ki9cclxuICAgICAgICB0aGlzLm5hbWVJbnB1dC5hZGRFdmVudExpc3RlbmVyKFwia2V5ZG93blwiLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICBsZXQgeCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHRoaXMubmFtZUlucHV0LmlkICsgXCItYXV0by1jb21wbGV0ZS1saXN0XCIpO1xyXG4gICAgICAgICAgICBpZiAoeCkgeCA9IHguZ2V0RWxlbWVudHNCeVRhZ05hbWUoXCJkaXZcIik7XHJcbiAgICAgICAgICAgIGlmIChlLmtleUNvZGUgPT09IDQwKSB7XHJcbiAgICAgICAgICAgICAgICAvKklmIHRoZSBhcnJvdyBET1dOIGtleSBpcyBwcmVzc2VkLFxyXG4gICAgICAgICAgICAgICAgIGluY3JlYXNlIHRoZSBjdXJyZW50Rm9jdXMgdmFyaWFibGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudEZvY3VzKys7XHJcbiAgICAgICAgICAgICAgICAvKmFuZCBhbmQgbWFrZSB0aGUgY3VycmVudCBpdGVtIG1vcmUgdmlzaWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5hZGRBY3RpdmUoeCk7XHJcbiAgICAgICAgICAgIH0gZWxzZSBpZiAoZS5rZXlDb2RlID09PSAzOCkgeyAvL3VwXHJcbiAgICAgICAgICAgICAgICAvKklmIHRoZSBhcnJvdyBVUCBrZXkgaXMgcHJlc3NlZCxcclxuICAgICAgICAgICAgICAgICBkZWNyZWFzZSB0aGUgY3VycmVudEZvY3VzIHZhcmlhYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmN1cnJlbnRGb2N1cy0tO1xyXG4gICAgICAgICAgICAgICAgLyphbmQgYW5kIG1ha2UgdGhlIGN1cnJlbnQgaXRlbSBtb3JlIHZpc2libGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuYWRkQWN0aXZlKHgpO1xyXG4gICAgICAgICAgICB9IGVsc2UgaWYgKGUua2V5Q29kZSA9PT0gMTMpIHtcclxuICAgICAgICAgICAgICAgIC8qSWYgdGhlIEVOVEVSIGtleSBpcyBwcmVzc2VkLCBwcmV2ZW50IHRoZSBmb3JtIGZyb20gYmVpbmcgc3VibWl0dGVkLCovXHJcbiAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgICAgICBpZiAodGhpcy5jdXJyZW50Rm9jdXMgPiAtMSkge1xyXG4gICAgICAgICAgICAgICAgICAgIC8qYW5kIHNpbXVsYXRlIGEgY2xpY2sgb24gdGhlIFwiYWN0aXZlXCIgaXRlbToqL1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh4KSB4W3RoaXMuY3VycmVudEZvY3VzXS5jbGljaygpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHdoZW4gc29tZW9uZSBjbGlja3MgaW4gdGhlIGRvY3VtZW50OiovXHJcbiAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIChlKSA9PiB7XHJcbiAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cyhlLnRhcmdldCk7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9XHJcblxyXG4gICAgYWRkQWN0aXZlKHgpIHtcclxuICAgICAgICAvKmEgZnVuY3Rpb24gdG8gY2xhc3NpZnkgYW4gaXRlbSBhcyBcImFjdGl2ZVwiOiovXHJcbiAgICAgICAgaWYgKCF4KSByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgLypzdGFydCBieSByZW1vdmluZyB0aGUgXCJhY3RpdmVcIiBjbGFzcyBvbiBhbGwgaXRlbXM6Ki9cclxuICAgICAgICB0aGlzLnJlbW92ZUFjdGl2ZSh4KTtcclxuICAgICAgICBpZiAodGhpcy5jdXJyZW50Rm9jdXMgPj0geC5sZW5ndGgpIHRoaXMuY3VycmVudEZvY3VzID0gMDtcclxuICAgICAgICBpZiAodGhpcy5jdXJyZW50Rm9jdXMgPCAwKSB0aGlzLmN1cnJlbnRGb2N1cyA9ICh4Lmxlbmd0aCAtIDEpO1xyXG4gICAgICAgIC8qYWRkIGNsYXNzIFwiYXV0b2NvbXBsZXRlLWFjdGl2ZVwiOiovXHJcbiAgICAgICAgeFt0aGlzLmN1cnJlbnRGb2N1c10uY2xhc3NMaXN0LmFkZChcInRybi1hdXRvLWNvbXBsZXRlLWFjdGl2ZVwiKTtcclxuICAgIH1cclxuXHJcbiAgICByZW1vdmVBY3RpdmUoeCkge1xyXG4gICAgICAgIC8qYSBmdW5jdGlvbiB0byByZW1vdmUgdGhlIFwiYWN0aXZlXCIgY2xhc3MgZnJvbSBhbGwgYXV0b2NvbXBsZXRlIGl0ZW1zOiovXHJcbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCB4Lmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgIHhbaV0uY2xhc3NMaXN0LnJlbW92ZShcInRybi1hdXRvLWNvbXBsZXRlLWFjdGl2ZVwiKTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcblxyXG4gICAgY2xvc2VBbGxMaXN0cyhlbGVtZW50KSB7XHJcbiAgICAgICAgY29uc29sZS5sb2coXCJjbG9zZSBhbGwgbGlzdHNcIik7XHJcbiAgICAgICAgLypjbG9zZSBhbGwgYXV0b2NvbXBsZXRlIGxpc3RzIGluIHRoZSBkb2N1bWVudCxcclxuICAgICAgICAgZXhjZXB0IHRoZSBvbmUgcGFzc2VkIGFzIGFuIGFyZ3VtZW50OiovXHJcbiAgICAgICAgbGV0IHggPSBkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKFwidHJuLWF1dG8tY29tcGxldGUtaXRlbXNcIik7XHJcbiAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCB4Lmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgIGlmIChlbGVtZW50ICE9PSB4W2ldICYmIGVsZW1lbnQgIT09IHRoaXMubmFtZUlucHV0KSB7XHJcbiAgICAgICAgICAgICAgICB4W2ldLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoeFtpXSk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICB9XHJcbn1cclxuXHJcbi8vIEZpcnN0LCBjaGVja3MgaWYgaXQgaXNuJ3QgaW1wbGVtZW50ZWQgeWV0LlxyXG5pZiAoIVN0cmluZy5wcm90b3R5cGUuZm9ybWF0KSB7XHJcbiAgICBTdHJpbmcucHJvdG90eXBlLmZvcm1hdCA9IGZ1bmN0aW9uKCkge1xyXG4gICAgICAgIGNvbnN0IGFyZ3MgPSBhcmd1bWVudHM7XHJcbiAgICAgICAgcmV0dXJuIHRoaXMucmVwbGFjZSgveyhcXGQrKX0vZywgZnVuY3Rpb24obWF0Y2gsIG51bWJlcikge1xyXG4gICAgICAgICAgICByZXR1cm4gdHlwZW9mIGFyZ3NbbnVtYmVyXSAhPT0gJ3VuZGVmaW5lZCdcclxuICAgICAgICAgICAgICAgID8gYXJnc1tudW1iZXJdXHJcbiAgICAgICAgICAgICAgICA6IG1hdGNoXHJcbiAgICAgICAgICAgICAgICA7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9O1xyXG59Il0sInNvdXJjZVJvb3QiOiIifQ==