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
/******/ 	return __webpack_require__(__webpack_require__.s = 8);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/create-challenge-form.js":
/*!*****************************************!*\
  !*** ./src/js/create-challenge-form.js ***!
  \*****************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _tournamatch_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./tournamatch.js */ "./src/js/tournamatch.js");
/**
 * Handles the asynchronous behavior for the create a new challenge form.
 *
 * @link       https://www.tournamatch.com
 * @since      3.20.0
 *
 * @package    Tournamatch
 *
 */


(function ($) {
  'use strict';

  window.addEventListener('load', function () {
    var options = trn_create_challenge_form_options;
    var challengeButton = document.getElementById('trn-challenge-button');
    var matchTimeInput = document.getElementById('match_time_field');
    var challengerField = document.getElementById('trn-challenge-form-challenger');
    var challengeeField = document.getElementById('trn-challenge-form-challengee');
    var challengerGroup = document.getElementById('trn-challenge-form-challenger-group');
    var challengeeGroup = document.getElementById('trn-challenge-form-challengee-group');
    var matchTimeGroup = document.getElementById('trn-challenge-form-match-time-group');
    var challengeForm = document.getElementById('trn-create-challenge-form');
    var ladderId = options.ladder_id;
    var challengeeId = options.challengee_id;
    var ladder = options.ladder;
    $.event('ladder').addEventListener('changed', function (ladder) {
      getChallengeBuilder(ladder);
    });
    $.event('challenge-builder').addEventListener('changed', function (challengeBuilder) {
      renderChallengeForm(challengeBuilder.detail);
    });

    function getChallengeBuilder(ladderId) {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', "".concat(options.api_url, "challenge-builder/").concat(ladderId));
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        if (xhr.status === 200) {
          $.event('challenge-builder').dispatchEvent(new CustomEvent('changed', {
            detail: JSON.parse(xhr.response)
          }));
        } else {
          $.event('challenge-builder').dispatchEvent(new CustomEvent('failed', {
            detail: response.message
          }));
        }
      };

      xhr.send();
    }

    function renderChallengeForm(challengeBuilder) {
      console.log(challengeBuilder);
      renderChallengeeList(challengeBuilder.competitors);
      renderChallengerField(challengeBuilder.challenger);
      challengeeGroup.classList.remove('d-none');
      challengerGroup.classList.remove('d-none');

      if (0 < challengeBuilder.competitors.length) {
        matchTimeGroup.classList.remove('d-none');
        challengeButton.classList.remove('d-none');
        challengeButton.removeAttribute('disabled');
        matchTimeInput.removeAttribute('disabled');
      } else {
        matchTimeGroup.classList.add('d-none');
        challengeButton.classList.add('d-none');
      }

      ladderId = challengeBuilder.ladder_id;
    }

    function renderChallengerField(challenger) {
      if (1 === challenger.length) {
        challengerField.setAttribute('data-competitor-id', challenger[0].competitor_id);
        var p = document.createElement('p');
        p.innerText = challenger[0].competitor_name;
        p.classList.add('trn-form-control-static');

        while (challengerField.firstChild) {
          challengerField.removeChild(challengerField.firstChild);
        }

        challengerField.appendChild(p);
        var input = document.createElement('input');
        input.setAttribute("type", "hidden");
        input.setAttribute("name", "challenger_id");
        input.setAttribute("value", challenger[0].competitor_id);
        challengerField.appendChild(input);
      } else {
        var challengerSelect = document.createElement('select');
        challengerSelect.setAttribute("name", "challenger_id");
        challenger.forEach(function (challenger) {
          var opt = document.createElement('option');
          opt.value = challenger.competitor_id;
          opt.innerHTML = challenger.competitor_name;
          challengerSelect.appendChild(opt);
        });

        while (challengerField.firstChild) {
          challengerField.removeChild(challengerField.firstChild);
        }

        challengerField.appendChild(challengerSelect);
        challengerSelect.addEventListener('change', function (event) {
          challengerField.setAttribute('data-competitor-id', event.target.value);
        });
        challengerField.setAttribute('data-competitor-id', challenger[0].competitor_id);
        challengerField.setAttribute('value', challenger[0].competitor_id);
      }
    }

    function renderChallengeeList(challengees) {
      if (0 === challengees.length) {
        var p = document.createElement('p');
        p.innerText = options.language.no_competitors_exist;
        p.classList.add('trn-form-control-static');

        while (challengeeField.firstChild) {
          challengeeField.removeChild(challengeeField.firstChild);
        }

        challengeeField.appendChild(p);
      } else {
        var challengeeSelect = document.createElement('select');
        challengeeSelect.setAttribute("name", "challengee_id");
        challengees.forEach(function (challengee) {
          var opt = document.createElement('option');
          opt.value = challengee.competitor_id;
          opt.innerHTML = challengee.competitor_name;

          if (challengee.competitor_id === challengeeId) {
            opt.setAttribute('selected', true);
          }

          challengeeSelect.appendChild(opt);
        });

        while (challengeeField.firstChild) {
          challengeeField.removeChild(challengeeField.firstChild);
        }

        challengeeField.appendChild(challengeeSelect);
        challengeeSelect.addEventListener('change', function (event) {
          challengeeField.setAttribute('data-competitor-id', event.target.value);
        });

        if ('0' !== challengeeId) {
          challengeeField.setAttribute('data-competitor-id', challengeeId);
          challengeeField.setAttribute('value', challengeeId);
        } else {
          challengeeField.setAttribute('data-competitor-id', challengees[0].competitor_id);
          challengeeField.setAttribute('value', challengees[0].competitor_id);
        }
      }
    } // if there is no ladder set, respond to changes in the ladder drop down.


    if (ladder === null) {
      var ladderSelect = document.getElementById("ladder_id");
      ladderSelect.addEventListener('change', function (event) {
        return getChallengeBuilder(event.target.value);
      });
      challengeButton.setAttribute('disabled', true);
      matchTimeInput.setAttribute('disabled', true);
      getChallengeBuilder(ladderSelect.value);
    } else {
      // get ladder id details
      getChallengeBuilder(ladderId);
    }

    challengeForm.addEventListener('submit', function (event) {
      event.preventDefault();
      document.getElementById('trn-create-challenge-form-response').innerHTML = "";
      var d = new Date("".concat(matchTimeInput.value));
      var matchTime = document.getElementById("match_time");
      matchTime.value = d.toISOString().slice(0, 19).replace('T', ' ');
      var xhr = new XMLHttpRequest();
      xhr.open('POST', "".concat(options.api_url, "challenges"));
      xhr.setRequestHeader('X-WP-Nonce', options.rest_nonce);

      xhr.onload = function () {
        console.log(xhr.response);

        if (xhr.status === 201) {
          var _response = JSON.parse(xhr.response);

          window.location.href = _response.link;
        } else {
          var _response2 = JSON.parse(xhr.response);

          document.getElementById('trn-create-challenge-form-response').innerHTML = "<div class=\"trn-alert trn-alert-danger\"><strong>".concat(options.language.failure, "</strong>: ").concat(_response2.message, "</div>");
        }
      };

      xhr.send(new FormData(challengeForm));
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

/***/ 8:
/*!***********************************************!*\
  !*** multi ./src/js/create-challenge-form.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\create-challenge-form.js */"./src/js/create-challenge-form.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL2NyZWF0ZS1jaGFsbGVuZ2UtZm9ybS5qcyIsIndlYnBhY2s6Ly8vLi9zcmMvanMvdG91cm5hbWF0Y2guanMiXSwibmFtZXMiOlsiJCIsIndpbmRvdyIsImFkZEV2ZW50TGlzdGVuZXIiLCJvcHRpb25zIiwidHJuX2NyZWF0ZV9jaGFsbGVuZ2VfZm9ybV9vcHRpb25zIiwiY2hhbGxlbmdlQnV0dG9uIiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50QnlJZCIsIm1hdGNoVGltZUlucHV0IiwiY2hhbGxlbmdlckZpZWxkIiwiY2hhbGxlbmdlZUZpZWxkIiwiY2hhbGxlbmdlckdyb3VwIiwiY2hhbGxlbmdlZUdyb3VwIiwibWF0Y2hUaW1lR3JvdXAiLCJjaGFsbGVuZ2VGb3JtIiwibGFkZGVySWQiLCJsYWRkZXJfaWQiLCJjaGFsbGVuZ2VlSWQiLCJjaGFsbGVuZ2VlX2lkIiwibGFkZGVyIiwiZXZlbnQiLCJnZXRDaGFsbGVuZ2VCdWlsZGVyIiwiY2hhbGxlbmdlQnVpbGRlciIsInJlbmRlckNoYWxsZW5nZUZvcm0iLCJkZXRhaWwiLCJ4aHIiLCJYTUxIdHRwUmVxdWVzdCIsIm9wZW4iLCJhcGlfdXJsIiwic2V0UmVxdWVzdEhlYWRlciIsInJlc3Rfbm9uY2UiLCJvbmxvYWQiLCJzdGF0dXMiLCJkaXNwYXRjaEV2ZW50IiwiQ3VzdG9tRXZlbnQiLCJKU09OIiwicGFyc2UiLCJyZXNwb25zZSIsIm1lc3NhZ2UiLCJzZW5kIiwiY29uc29sZSIsImxvZyIsInJlbmRlckNoYWxsZW5nZWVMaXN0IiwiY29tcGV0aXRvcnMiLCJyZW5kZXJDaGFsbGVuZ2VyRmllbGQiLCJjaGFsbGVuZ2VyIiwiY2xhc3NMaXN0IiwicmVtb3ZlIiwibGVuZ3RoIiwicmVtb3ZlQXR0cmlidXRlIiwiYWRkIiwic2V0QXR0cmlidXRlIiwiY29tcGV0aXRvcl9pZCIsInAiLCJjcmVhdGVFbGVtZW50IiwiaW5uZXJUZXh0IiwiY29tcGV0aXRvcl9uYW1lIiwiZmlyc3RDaGlsZCIsInJlbW92ZUNoaWxkIiwiYXBwZW5kQ2hpbGQiLCJpbnB1dCIsImNoYWxsZW5nZXJTZWxlY3QiLCJmb3JFYWNoIiwib3B0IiwidmFsdWUiLCJpbm5lckhUTUwiLCJ0YXJnZXQiLCJjaGFsbGVuZ2VlcyIsImxhbmd1YWdlIiwibm9fY29tcGV0aXRvcnNfZXhpc3QiLCJjaGFsbGVuZ2VlU2VsZWN0IiwiY2hhbGxlbmdlZSIsImxhZGRlclNlbGVjdCIsInByZXZlbnREZWZhdWx0IiwiZCIsIkRhdGUiLCJtYXRjaFRpbWUiLCJ0b0lTT1N0cmluZyIsInNsaWNlIiwicmVwbGFjZSIsImxvY2F0aW9uIiwiaHJlZiIsImxpbmsiLCJmYWlsdXJlIiwiRm9ybURhdGEiLCJ0cm4iLCJUb3VybmFtYXRjaCIsImV2ZW50cyIsIm9iamVjdCIsInByZWZpeCIsInN0ciIsInByb3AiLCJoYXNPd25Qcm9wZXJ0eSIsImsiLCJ2IiwicHVzaCIsInBhcmFtIiwiZW5jb2RlVVJJQ29tcG9uZW50Iiwiam9pbiIsImV2ZW50TmFtZSIsIkV2ZW50VGFyZ2V0IiwiZGF0YUNhbGxiYWNrIiwiVG91cm5hbWF0Y2hfQXV0b2NvbXBsZXRlIiwicyIsImNoYXJBdCIsInRvVXBwZXJDYXNlIiwibnVtYmVyIiwicmVtYWluZGVyIiwiZWxlbWVudCIsInRhYnMiLCJnZXRFbGVtZW50c0J5Q2xhc3NOYW1lIiwicGFuZXMiLCJjbGVhckFjdGl2ZSIsIkFycmF5IiwicHJvdG90eXBlIiwiY2FsbCIsInRhYiIsImFyaWFTZWxlY3RlZCIsInBhbmUiLCJzZXRBY3RpdmUiLCJ0YXJnZXRJZCIsInRhcmdldFRhYiIsInF1ZXJ5U2VsZWN0b3IiLCJ0YXJnZXRQYW5lSWQiLCJkYXRhc2V0IiwidGFiQ2xpY2siLCJjdXJyZW50VGFyZ2V0IiwiaGFzaCIsInN1YnN0ciIsInRybl9vYmpfaW5zdGFuY2UiLCJ0YWJWaWV3cyIsImZyb20iLCJkcm9wZG93bnMiLCJoYW5kbGVEcm9wZG93bkNsb3NlIiwiZHJvcGRvd24iLCJuZXh0RWxlbWVudFNpYmxpbmciLCJyZW1vdmVFdmVudExpc3RlbmVyIiwiZSIsInN0b3BQcm9wYWdhdGlvbiIsIm5hbWVJbnB1dCIsImEiLCJiIiwiaSIsInZhbCIsInBhcmVudCIsInBhcmVudE5vZGUiLCJ0aGVuIiwiZGF0YSIsImNsb3NlQWxsTGlzdHMiLCJjdXJyZW50Rm9jdXMiLCJpZCIsInRleHQiLCJzZWxlY3RlZElkIiwiRXZlbnQiLCJ4IiwiZ2V0RWxlbWVudHNCeVRhZ05hbWUiLCJrZXlDb2RlIiwiYWRkQWN0aXZlIiwiY2xpY2siLCJyZW1vdmVBY3RpdmUiLCJTdHJpbmciLCJmb3JtYXQiLCJhcmdzIiwiYXJndW1lbnRzIiwibWF0Y2giXSwibWFwcGluZ3MiOiI7UUFBQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTs7O1FBR0E7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLDBDQUEwQyxnQ0FBZ0M7UUFDMUU7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSx3REFBd0Qsa0JBQWtCO1FBQzFFO1FBQ0EsaURBQWlELGNBQWM7UUFDL0Q7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBLHlDQUF5QyxpQ0FBaUM7UUFDMUUsZ0hBQWdILG1CQUFtQixFQUFFO1FBQ3JJO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0EsMkJBQTJCLDBCQUEwQixFQUFFO1FBQ3ZELGlDQUFpQyxlQUFlO1FBQ2hEO1FBQ0E7UUFDQTs7UUFFQTtRQUNBLHNEQUFzRCwrREFBK0Q7O1FBRXJIO1FBQ0E7OztRQUdBO1FBQ0E7Ozs7Ozs7Ozs7Ozs7QUNsRkE7QUFBQTtBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLENB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ZW5nZS1mb3JtLmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7IGVudW1lcmFibGU6IHRydWUsIGdldDogZ2V0dGVyIH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBkZWZpbmUgX19lc01vZHVsZSBvbiBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIgPSBmdW5jdGlvbihleHBvcnRzKSB7XG4gXHRcdGlmKHR5cGVvZiBTeW1ib2wgIT09ICd1bmRlZmluZWQnICYmIFN5bWJvbC50b1N0cmluZ1RhZykge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBTeW1ib2wudG9TdHJpbmdUYWcsIHsgdmFsdWU6ICdNb2R1bGUnIH0pO1xuIFx0XHR9XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCAnX19lc01vZHVsZScsIHsgdmFsdWU6IHRydWUgfSk7XG4gXHR9O1xuXG4gXHQvLyBjcmVhdGUgYSBmYWtlIG5hbWVzcGFjZSBvYmplY3RcbiBcdC8vIG1vZGUgJiAxOiB2YWx1ZSBpcyBhIG1vZHVsZSBpZCwgcmVxdWlyZSBpdFxuIFx0Ly8gbW9kZSAmIDI6IG1lcmdlIGFsbCBwcm9wZXJ0aWVzIG9mIHZhbHVlIGludG8gdGhlIG5zXG4gXHQvLyBtb2RlICYgNDogcmV0dXJuIHZhbHVlIHdoZW4gYWxyZWFkeSBucyBvYmplY3RcbiBcdC8vIG1vZGUgJiA4fDE6IGJlaGF2ZSBsaWtlIHJlcXVpcmVcbiBcdF9fd2VicGFja19yZXF1aXJlX18udCA9IGZ1bmN0aW9uKHZhbHVlLCBtb2RlKSB7XG4gXHRcdGlmKG1vZGUgJiAxKSB2YWx1ZSA9IF9fd2VicGFja19yZXF1aXJlX18odmFsdWUpO1xuIFx0XHRpZihtb2RlICYgOCkgcmV0dXJuIHZhbHVlO1xuIFx0XHRpZigobW9kZSAmIDQpICYmIHR5cGVvZiB2YWx1ZSA9PT0gJ29iamVjdCcgJiYgdmFsdWUgJiYgdmFsdWUuX19lc01vZHVsZSkgcmV0dXJuIHZhbHVlO1xuIFx0XHR2YXIgbnMgPSBPYmplY3QuY3JlYXRlKG51bGwpO1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIobnMpO1xuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkobnMsICdkZWZhdWx0JywgeyBlbnVtZXJhYmxlOiB0cnVlLCB2YWx1ZTogdmFsdWUgfSk7XG4gXHRcdGlmKG1vZGUgJiAyICYmIHR5cGVvZiB2YWx1ZSAhPSAnc3RyaW5nJykgZm9yKHZhciBrZXkgaW4gdmFsdWUpIF9fd2VicGFja19yZXF1aXJlX18uZChucywga2V5LCBmdW5jdGlvbihrZXkpIHsgcmV0dXJuIHZhbHVlW2tleV07IH0uYmluZChudWxsLCBrZXkpKTtcbiBcdFx0cmV0dXJuIG5zO1xuIFx0fTtcblxuIFx0Ly8gZ2V0RGVmYXVsdEV4cG9ydCBmdW5jdGlvbiBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIG5vbi1oYXJtb255IG1vZHVsZXNcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubiA9IGZ1bmN0aW9uKG1vZHVsZSkge1xuIFx0XHR2YXIgZ2V0dGVyID0gbW9kdWxlICYmIG1vZHVsZS5fX2VzTW9kdWxlID9cbiBcdFx0XHRmdW5jdGlvbiBnZXREZWZhdWx0KCkgeyByZXR1cm4gbW9kdWxlWydkZWZhdWx0J107IH0gOlxuIFx0XHRcdGZ1bmN0aW9uIGdldE1vZHVsZUV4cG9ydHMoKSB7IHJldHVybiBtb2R1bGU7IH07XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18uZChnZXR0ZXIsICdhJywgZ2V0dGVyKTtcbiBcdFx0cmV0dXJuIGdldHRlcjtcbiBcdH07XG5cbiBcdC8vIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbFxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5vID0gZnVuY3Rpb24ob2JqZWN0LCBwcm9wZXJ0eSkgeyByZXR1cm4gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iamVjdCwgcHJvcGVydHkpOyB9O1xuXG4gXHQvLyBfX3dlYnBhY2tfcHVibGljX3BhdGhfX1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5wID0gXCJcIjtcblxuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IDgpO1xuIiwiLyoqXHJcbiAqIEhhbmRsZXMgdGhlIGFzeW5jaHJvbm91cyBiZWhhdmlvciBmb3IgdGhlIGNyZWF0ZSBhIG5ldyBjaGFsbGVuZ2UgZm9ybS5cclxuICpcclxuICogQGxpbmsgICAgICAgaHR0cHM6Ly93d3cudG91cm5hbWF0Y2guY29tXHJcbiAqIEBzaW5jZSAgICAgIDMuMjAuMFxyXG4gKlxyXG4gKiBAcGFja2FnZSAgICBUb3VybmFtYXRjaFxyXG4gKlxyXG4gKi9cclxuaW1wb3J0IHsgdHJuIH0gZnJvbSAnLi90b3VybmFtYXRjaC5qcyc7XHJcblxyXG4oZnVuY3Rpb24gKCQpIHtcclxuICAgICd1c2Ugc3RyaWN0JztcclxuXHJcbiAgICB3aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICBjb25zdCBvcHRpb25zID0gdHJuX2NyZWF0ZV9jaGFsbGVuZ2VfZm9ybV9vcHRpb25zO1xyXG4gICAgICAgIGNvbnN0IGNoYWxsZW5nZUJ1dHRvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tY2hhbGxlbmdlLWJ1dHRvbicpO1xyXG4gICAgICAgIGNvbnN0IG1hdGNoVGltZUlucHV0ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ21hdGNoX3RpbWVfZmllbGQnKTtcclxuICAgICAgICBjb25zdCBjaGFsbGVuZ2VyRmllbGQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLWNoYWxsZW5nZS1mb3JtLWNoYWxsZW5nZXInKTtcclxuICAgICAgICBjb25zdCBjaGFsbGVuZ2VlRmllbGQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLWNoYWxsZW5nZS1mb3JtLWNoYWxsZW5nZWUnKTtcclxuICAgICAgICBjb25zdCBjaGFsbGVuZ2VyR3JvdXAgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLWNoYWxsZW5nZS1mb3JtLWNoYWxsZW5nZXItZ3JvdXAnKTtcclxuICAgICAgICBjb25zdCBjaGFsbGVuZ2VlR3JvdXAgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLWNoYWxsZW5nZS1mb3JtLWNoYWxsZW5nZWUtZ3JvdXAnKTtcclxuICAgICAgICBjb25zdCBtYXRjaFRpbWVHcm91cCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tY2hhbGxlbmdlLWZvcm0tbWF0Y2gtdGltZS1ncm91cCcpO1xyXG4gICAgICAgIGNvbnN0IGNoYWxsZW5nZUZvcm0gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndHJuLWNyZWF0ZS1jaGFsbGVuZ2UtZm9ybScpO1xyXG4gICAgICAgIGxldCBsYWRkZXJJZCA9IG9wdGlvbnMubGFkZGVyX2lkO1xyXG4gICAgICAgIGxldCBjaGFsbGVuZ2VlSWQgPSBvcHRpb25zLmNoYWxsZW5nZWVfaWQ7XHJcbiAgICAgICAgbGV0IGxhZGRlciA9IG9wdGlvbnMubGFkZGVyO1xyXG5cclxuICAgICAgICAkLmV2ZW50KCdsYWRkZXInKS5hZGRFdmVudExpc3RlbmVyKCdjaGFuZ2VkJywgZnVuY3Rpb24obGFkZGVyKSB7XHJcbiAgICAgICAgICAgIGdldENoYWxsZW5nZUJ1aWxkZXIobGFkZGVyKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgJC5ldmVudCgnY2hhbGxlbmdlLWJ1aWxkZXInKS5hZGRFdmVudExpc3RlbmVyKCdjaGFuZ2VkJywgZnVuY3Rpb24oY2hhbGxlbmdlQnVpbGRlcikge1xyXG4gICAgICAgICAgICByZW5kZXJDaGFsbGVuZ2VGb3JtKGNoYWxsZW5nZUJ1aWxkZXIuZGV0YWlsKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgZnVuY3Rpb24gZ2V0Q2hhbGxlbmdlQnVpbGRlcihsYWRkZXJJZCkge1xyXG4gICAgICAgICAgICBsZXQgeGhyID0gbmV3IFhNTEh0dHBSZXF1ZXN0KCk7XHJcbiAgICAgICAgICAgIHhoci5vcGVuKCdHRVQnLCBgJHtvcHRpb25zLmFwaV91cmx9Y2hhbGxlbmdlLWJ1aWxkZXIvJHtsYWRkZXJJZH1gKTtcclxuICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ1gtV1AtTm9uY2UnLCBvcHRpb25zLnJlc3Rfbm9uY2UpO1xyXG4gICAgICAgICAgICB4aHIub25sb2FkID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMCkge1xyXG4gICAgICAgICAgICAgICAgICAgICQuZXZlbnQoJ2NoYWxsZW5nZS1idWlsZGVyJykuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoJ2NoYW5nZWQnLCB7IGRldGFpbDogSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpIH0gKSk7XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICQuZXZlbnQoJ2NoYWxsZW5nZS1idWlsZGVyJykuZGlzcGF0Y2hFdmVudChuZXcgQ3VzdG9tRXZlbnQoJ2ZhaWxlZCcsIHsgZGV0YWlsOiByZXNwb25zZS5tZXNzYWdlIH0gKSk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgICAgICB4aHIuc2VuZCgpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZnVuY3Rpb24gcmVuZGVyQ2hhbGxlbmdlRm9ybShjaGFsbGVuZ2VCdWlsZGVyKSB7XHJcbiAgICAgICAgICAgIGNvbnNvbGUubG9nKGNoYWxsZW5nZUJ1aWxkZXIpO1xyXG4gICAgICAgICAgICByZW5kZXJDaGFsbGVuZ2VlTGlzdChjaGFsbGVuZ2VCdWlsZGVyLmNvbXBldGl0b3JzKTtcclxuICAgICAgICAgICAgcmVuZGVyQ2hhbGxlbmdlckZpZWxkKGNoYWxsZW5nZUJ1aWxkZXIuY2hhbGxlbmdlcik7XHJcbiAgICAgICAgICAgIGNoYWxsZW5nZWVHcm91cC5jbGFzc0xpc3QucmVtb3ZlKCdkLW5vbmUnKTtcclxuICAgICAgICAgICAgY2hhbGxlbmdlckdyb3VwLmNsYXNzTGlzdC5yZW1vdmUoJ2Qtbm9uZScpO1xyXG4gICAgICAgICAgICBpZiAoIDAgPCBjaGFsbGVuZ2VCdWlsZGVyLmNvbXBldGl0b3JzLmxlbmd0aCkge1xyXG4gICAgICAgICAgICAgICAgbWF0Y2hUaW1lR3JvdXAuY2xhc3NMaXN0LnJlbW92ZSgnZC1ub25lJyk7XHJcbiAgICAgICAgICAgICAgICBjaGFsbGVuZ2VCdXR0b24uY2xhc3NMaXN0LnJlbW92ZSgnZC1ub25lJyk7XHJcbiAgICAgICAgICAgICAgICBjaGFsbGVuZ2VCdXR0b24ucmVtb3ZlQXR0cmlidXRlKCdkaXNhYmxlZCcpO1xyXG4gICAgICAgICAgICAgICAgbWF0Y2hUaW1lSW5wdXQucmVtb3ZlQXR0cmlidXRlKCdkaXNhYmxlZCcpO1xyXG4gICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgbWF0Y2hUaW1lR3JvdXAuY2xhc3NMaXN0LmFkZCgnZC1ub25lJyk7XHJcbiAgICAgICAgICAgICAgICBjaGFsbGVuZ2VCdXR0b24uY2xhc3NMaXN0LmFkZCgnZC1ub25lJyk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgbGFkZGVySWQgPSBjaGFsbGVuZ2VCdWlsZGVyLmxhZGRlcl9pZDtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGZ1bmN0aW9uIHJlbmRlckNoYWxsZW5nZXJGaWVsZChjaGFsbGVuZ2VyKSB7XHJcbiAgICAgICAgICAgIGlmICggMSA9PT0gY2hhbGxlbmdlci5sZW5ndGggKSB7XHJcbiAgICAgICAgICAgICAgICBjaGFsbGVuZ2VyRmllbGQuc2V0QXR0cmlidXRlKCdkYXRhLWNvbXBldGl0b3ItaWQnLCBjaGFsbGVuZ2VyWzBdLmNvbXBldGl0b3JfaWQpO1xyXG4gICAgICAgICAgICAgICAgY29uc3QgcCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3AnKTtcclxuICAgICAgICAgICAgICAgIHAuaW5uZXJUZXh0ID0gY2hhbGxlbmdlclswXS5jb21wZXRpdG9yX25hbWU7XHJcbiAgICAgICAgICAgICAgICBwLmNsYXNzTGlzdC5hZGQoJ3Rybi1mb3JtLWNvbnRyb2wtc3RhdGljJyk7XHJcbiAgICAgICAgICAgICAgICB3aGlsZSAoY2hhbGxlbmdlckZpZWxkLmZpcnN0Q2hpbGQpIHtjaGFsbGVuZ2VyRmllbGQucmVtb3ZlQ2hpbGQoY2hhbGxlbmdlckZpZWxkLmZpcnN0Q2hpbGQpOyB9XHJcbiAgICAgICAgICAgICAgICBjaGFsbGVuZ2VyRmllbGQuYXBwZW5kQ2hpbGQocCk7XHJcblxyXG4gICAgICAgICAgICAgICAgY29uc3QgaW5wdXQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdpbnB1dCcpO1xyXG4gICAgICAgICAgICAgICAgaW5wdXQuc2V0QXR0cmlidXRlKFwidHlwZVwiLCBcImhpZGRlblwiKTtcclxuICAgICAgICAgICAgICAgIGlucHV0LnNldEF0dHJpYnV0ZShcIm5hbWVcIiwgXCJjaGFsbGVuZ2VyX2lkXCIpO1xyXG4gICAgICAgICAgICAgICAgaW5wdXQuc2V0QXR0cmlidXRlKFwidmFsdWVcIiwgY2hhbGxlbmdlclswXS5jb21wZXRpdG9yX2lkKTtcclxuICAgICAgICAgICAgICAgIGNoYWxsZW5nZXJGaWVsZC5hcHBlbmRDaGlsZChpbnB1dCk7XHJcbiAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICBjb25zdCBjaGFsbGVuZ2VyU2VsZWN0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnc2VsZWN0Jyk7XHJcbiAgICAgICAgICAgICAgICBjaGFsbGVuZ2VyU2VsZWN0LnNldEF0dHJpYnV0ZShcIm5hbWVcIiwgXCJjaGFsbGVuZ2VyX2lkXCIpO1xyXG4gICAgICAgICAgICAgICAgY2hhbGxlbmdlci5mb3JFYWNoKChjaGFsbGVuZ2VyKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgY29uc3Qgb3B0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnb3B0aW9uJyk7XHJcbiAgICAgICAgICAgICAgICAgICAgb3B0LnZhbHVlID0gY2hhbGxlbmdlci5jb21wZXRpdG9yX2lkO1xyXG4gICAgICAgICAgICAgICAgICAgIG9wdC5pbm5lckhUTUwgPSBjaGFsbGVuZ2VyLmNvbXBldGl0b3JfbmFtZTtcclxuICAgICAgICAgICAgICAgICAgICBjaGFsbGVuZ2VyU2VsZWN0LmFwcGVuZENoaWxkKG9wdCk7XHJcbiAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgIHdoaWxlIChjaGFsbGVuZ2VyRmllbGQuZmlyc3RDaGlsZCkge2NoYWxsZW5nZXJGaWVsZC5yZW1vdmVDaGlsZChjaGFsbGVuZ2VyRmllbGQuZmlyc3RDaGlsZCk7IH1cclxuICAgICAgICAgICAgICAgIGNoYWxsZW5nZXJGaWVsZC5hcHBlbmRDaGlsZChjaGFsbGVuZ2VyU2VsZWN0KTtcclxuICAgICAgICAgICAgICAgIGNoYWxsZW5nZXJTZWxlY3QuYWRkRXZlbnRMaXN0ZW5lcignY2hhbmdlJywgZnVuY3Rpb24oZXZlbnQpIHtcclxuICAgICAgICAgICAgICAgICAgICBjaGFsbGVuZ2VyRmllbGQuc2V0QXR0cmlidXRlKCdkYXRhLWNvbXBldGl0b3ItaWQnLCBldmVudC50YXJnZXQudmFsdWUpO1xyXG4gICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICAgICBjaGFsbGVuZ2VyRmllbGQuc2V0QXR0cmlidXRlKCdkYXRhLWNvbXBldGl0b3ItaWQnLCBjaGFsbGVuZ2VyWzBdLmNvbXBldGl0b3JfaWQpO1xyXG4gICAgICAgICAgICAgICAgY2hhbGxlbmdlckZpZWxkLnNldEF0dHJpYnV0ZSgndmFsdWUnLCBjaGFsbGVuZ2VyWzBdLmNvbXBldGl0b3JfaWQpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBmdW5jdGlvbiByZW5kZXJDaGFsbGVuZ2VlTGlzdChjaGFsbGVuZ2Vlcykge1xyXG4gICAgICAgICAgICBpZiAoMCA9PT0gY2hhbGxlbmdlZXMubGVuZ3RoKSB7XHJcbiAgICAgICAgICAgICAgICBjb25zdCBwID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgncCcpO1xyXG4gICAgICAgICAgICAgICAgcC5pbm5lclRleHQgPSBvcHRpb25zLmxhbmd1YWdlLm5vX2NvbXBldGl0b3JzX2V4aXN0O1xyXG4gICAgICAgICAgICAgICAgcC5jbGFzc0xpc3QuYWRkKCd0cm4tZm9ybS1jb250cm9sLXN0YXRpYycpO1xyXG4gICAgICAgICAgICAgICAgd2hpbGUgKGNoYWxsZW5nZWVGaWVsZC5maXJzdENoaWxkKSB7Y2hhbGxlbmdlZUZpZWxkLnJlbW92ZUNoaWxkKGNoYWxsZW5nZWVGaWVsZC5maXJzdENoaWxkKTsgfVxyXG4gICAgICAgICAgICAgICAgY2hhbGxlbmdlZUZpZWxkLmFwcGVuZENoaWxkKHApO1xyXG4gICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgY29uc3QgY2hhbGxlbmdlZVNlbGVjdCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3NlbGVjdCcpO1xyXG4gICAgICAgICAgICAgICAgY2hhbGxlbmdlZVNlbGVjdC5zZXRBdHRyaWJ1dGUoXCJuYW1lXCIsIFwiY2hhbGxlbmdlZV9pZFwiKTtcclxuICAgICAgICAgICAgICAgIGNoYWxsZW5nZWVzLmZvckVhY2goKGNoYWxsZW5nZWUpID0+IHtcclxuICAgICAgICAgICAgICAgICAgICBjb25zdCBvcHQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdvcHRpb24nKTtcclxuICAgICAgICAgICAgICAgICAgICBvcHQudmFsdWUgPSBjaGFsbGVuZ2VlLmNvbXBldGl0b3JfaWQ7XHJcbiAgICAgICAgICAgICAgICAgICAgb3B0LmlubmVySFRNTCA9IGNoYWxsZW5nZWUuY29tcGV0aXRvcl9uYW1lO1xyXG4gICAgICAgICAgICAgICAgICAgIGlmIChjaGFsbGVuZ2VlLmNvbXBldGl0b3JfaWQgPT09IGNoYWxsZW5nZWVJZCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBvcHQuc2V0QXR0cmlidXRlKCdzZWxlY3RlZCcsIHRydWUpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICBjaGFsbGVuZ2VlU2VsZWN0LmFwcGVuZENoaWxkKG9wdCk7XHJcbiAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgIHdoaWxlIChjaGFsbGVuZ2VlRmllbGQuZmlyc3RDaGlsZCkge2NoYWxsZW5nZWVGaWVsZC5yZW1vdmVDaGlsZChjaGFsbGVuZ2VlRmllbGQuZmlyc3RDaGlsZCk7IH1cclxuICAgICAgICAgICAgICAgIGNoYWxsZW5nZWVGaWVsZC5hcHBlbmRDaGlsZChjaGFsbGVuZ2VlU2VsZWN0KTtcclxuICAgICAgICAgICAgICAgIGNoYWxsZW5nZWVTZWxlY3QuYWRkRXZlbnRMaXN0ZW5lcignY2hhbmdlJywgZnVuY3Rpb24oZXZlbnQpIHtcclxuICAgICAgICAgICAgICAgICAgICBjaGFsbGVuZ2VlRmllbGQuc2V0QXR0cmlidXRlKCdkYXRhLWNvbXBldGl0b3ItaWQnLCBldmVudC50YXJnZXQudmFsdWUpO1xyXG4gICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICAgICBpZiAoJzAnICE9PSBjaGFsbGVuZ2VlSWQpIHtcclxuICAgICAgICAgICAgICAgICAgICBjaGFsbGVuZ2VlRmllbGQuc2V0QXR0cmlidXRlKCdkYXRhLWNvbXBldGl0b3ItaWQnLCBjaGFsbGVuZ2VlSWQpO1xyXG4gICAgICAgICAgICAgICAgICAgIGNoYWxsZW5nZWVGaWVsZC5zZXRBdHRyaWJ1dGUoJ3ZhbHVlJywgY2hhbGxlbmdlZUlkKTtcclxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY2hhbGxlbmdlZUZpZWxkLnNldEF0dHJpYnV0ZSgnZGF0YS1jb21wZXRpdG9yLWlkJywgY2hhbGxlbmdlZXNbMF0uY29tcGV0aXRvcl9pZCk7XHJcbiAgICAgICAgICAgICAgICAgICAgY2hhbGxlbmdlZUZpZWxkLnNldEF0dHJpYnV0ZSgndmFsdWUnLCBjaGFsbGVuZ2Vlc1swXS5jb21wZXRpdG9yX2lkKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgLy8gaWYgdGhlcmUgaXMgbm8gbGFkZGVyIHNldCwgcmVzcG9uZCB0byBjaGFuZ2VzIGluIHRoZSBsYWRkZXIgZHJvcCBkb3duLlxyXG4gICAgICAgIGlmIChsYWRkZXIgPT09IG51bGwpIHtcclxuICAgICAgICAgICAgY29uc3QgbGFkZGVyU2VsZWN0ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoYGxhZGRlcl9pZGApO1xyXG5cclxuICAgICAgICAgICAgbGFkZGVyU2VsZWN0LmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsIChldmVudCkgPT4gZ2V0Q2hhbGxlbmdlQnVpbGRlcihldmVudC50YXJnZXQudmFsdWUpKTtcclxuICAgICAgICAgICAgY2hhbGxlbmdlQnV0dG9uLnNldEF0dHJpYnV0ZSgnZGlzYWJsZWQnLCB0cnVlKTtcclxuICAgICAgICAgICAgbWF0Y2hUaW1lSW5wdXQuc2V0QXR0cmlidXRlKCdkaXNhYmxlZCcsIHRydWUpO1xyXG4gICAgICAgICAgICBnZXRDaGFsbGVuZ2VCdWlsZGVyKGxhZGRlclNlbGVjdC52YWx1ZSk7XHJcbiAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgLy8gZ2V0IGxhZGRlciBpZCBkZXRhaWxzXHJcbiAgICAgICAgICAgIGdldENoYWxsZW5nZUJ1aWxkZXIobGFkZGVySWQpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgY2hhbGxlbmdlRm9ybS5hZGRFdmVudExpc3RlbmVyKCdzdWJtaXQnLCBmdW5jdGlvbiAoZXZlbnQpIHtcclxuICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcclxuXHJcbiAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tY3JlYXRlLWNoYWxsZW5nZS1mb3JtLXJlc3BvbnNlJykuaW5uZXJIVE1MID0gYGA7XHJcblxyXG4gICAgICAgICAgICBsZXQgZCA9IG5ldyBEYXRlKGAke21hdGNoVGltZUlucHV0LnZhbHVlfWApO1xyXG4gICAgICAgICAgICBsZXQgbWF0Y2hUaW1lID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoYG1hdGNoX3RpbWVgKTtcclxuICAgICAgICAgICAgbWF0Y2hUaW1lLnZhbHVlID0gZC50b0lTT1N0cmluZygpLnNsaWNlKDAsIDE5KS5yZXBsYWNlKCdUJywgJyAnKTtcclxuXHJcbiAgICAgICAgICAgIGxldCB4aHIgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcclxuICAgICAgICAgICAgeGhyLm9wZW4oJ1BPU1QnLCBgJHtvcHRpb25zLmFwaV91cmx9Y2hhbGxlbmdlc2ApO1xyXG4gICAgICAgICAgICB4aHIuc2V0UmVxdWVzdEhlYWRlcignWC1XUC1Ob25jZScsIG9wdGlvbnMucmVzdF9ub25jZSk7XHJcbiAgICAgICAgICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyh4aHIucmVzcG9uc2UpO1xyXG4gICAgICAgICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMSkge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnN0IHJlc3BvbnNlID0gSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpO1xyXG4gICAgICAgICAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbi5ocmVmID0gcmVzcG9uc2UubGluaztcclxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IHJlc3BvbnNlID0gSlNPTi5wYXJzZSh4aHIucmVzcG9uc2UpO1xyXG4gICAgICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd0cm4tY3JlYXRlLWNoYWxsZW5nZS1mb3JtLXJlc3BvbnNlJykuaW5uZXJIVE1MID0gYDxkaXYgY2xhc3M9XCJ0cm4tYWxlcnQgdHJuLWFsZXJ0LWRhbmdlclwiPjxzdHJvbmc+JHtvcHRpb25zLmxhbmd1YWdlLmZhaWx1cmV9PC9zdHJvbmc+OiAke3Jlc3BvbnNlLm1lc3NhZ2V9PC9kaXY+YDtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfTtcclxuXHJcbiAgICAgICAgICAgIHhoci5zZW5kKG5ldyBGb3JtRGF0YShjaGFsbGVuZ2VGb3JtKSk7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9LCBmYWxzZSk7XHJcbn0pKHRybik7IiwiJ3VzZSBzdHJpY3QnO1xyXG5jbGFzcyBUb3VybmFtYXRjaCB7XHJcblxyXG4gICAgY29uc3RydWN0b3IoKSB7XHJcbiAgICAgICAgdGhpcy5ldmVudHMgPSB7fTtcclxuICAgIH1cclxuXHJcbiAgICBwYXJhbShvYmplY3QsIHByZWZpeCkge1xyXG4gICAgICAgIGxldCBzdHIgPSBbXTtcclxuICAgICAgICBmb3IgKGxldCBwcm9wIGluIG9iamVjdCkge1xyXG4gICAgICAgICAgICBpZiAob2JqZWN0Lmhhc093blByb3BlcnR5KHByb3ApKSB7XHJcbiAgICAgICAgICAgICAgICBsZXQgayA9IHByZWZpeCA/IHByZWZpeCArIFwiW1wiICsgcHJvcCArIFwiXVwiIDogcHJvcDtcclxuICAgICAgICAgICAgICAgIGxldCB2ID0gb2JqZWN0W3Byb3BdO1xyXG4gICAgICAgICAgICAgICAgc3RyLnB1c2goKHYgIT09IG51bGwgJiYgdHlwZW9mIHYgPT09IFwib2JqZWN0XCIpID8gdGhpcy5wYXJhbSh2LCBrKSA6IGVuY29kZVVSSUNvbXBvbmVudChrKSArIFwiPVwiICsgZW5jb2RlVVJJQ29tcG9uZW50KHYpKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gc3RyLmpvaW4oXCImXCIpO1xyXG4gICAgfVxyXG5cclxuICAgIGV2ZW50KGV2ZW50TmFtZSkge1xyXG4gICAgICAgIGlmICghKGV2ZW50TmFtZSBpbiB0aGlzLmV2ZW50cykpIHtcclxuICAgICAgICAgICAgdGhpcy5ldmVudHNbZXZlbnROYW1lXSA9IG5ldyBFdmVudFRhcmdldChldmVudE5hbWUpO1xyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm4gdGhpcy5ldmVudHNbZXZlbnROYW1lXTtcclxuICAgIH1cclxuXHJcbiAgICBhdXRvY29tcGxldGUoaW5wdXQsIGRhdGFDYWxsYmFjaykge1xyXG4gICAgICAgIG5ldyBUb3VybmFtYXRjaF9BdXRvY29tcGxldGUoaW5wdXQsIGRhdGFDYWxsYmFjayk7XHJcbiAgICB9XHJcblxyXG4gICAgdWNmaXJzdChzKSB7XHJcbiAgICAgICAgaWYgKHR5cGVvZiBzICE9PSAnc3RyaW5nJykgcmV0dXJuICcnO1xyXG4gICAgICAgIHJldHVybiBzLmNoYXJBdCgwKS50b1VwcGVyQ2FzZSgpICsgcy5zbGljZSgxKTtcclxuICAgIH1cclxuXHJcbiAgICBvcmRpbmFsX3N1ZmZpeChudW1iZXIpIHtcclxuICAgICAgICBjb25zdCByZW1haW5kZXIgPSBudW1iZXIgJSAxMDA7XHJcblxyXG4gICAgICAgIGlmICgocmVtYWluZGVyIDwgMTEpIHx8IChyZW1haW5kZXIgPiAxMykpIHtcclxuICAgICAgICAgICAgc3dpdGNoIChyZW1haW5kZXIgJSAxMCkge1xyXG4gICAgICAgICAgICAgICAgY2FzZSAxOiByZXR1cm4gJ3N0JztcclxuICAgICAgICAgICAgICAgIGNhc2UgMjogcmV0dXJuICduZCc7XHJcbiAgICAgICAgICAgICAgICBjYXNlIDM6IHJldHVybiAncmQnO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgICAgIHJldHVybiAndGgnO1xyXG4gICAgfVxyXG5cclxuICAgIHRhYnMoZWxlbWVudCkge1xyXG4gICAgICAgIGNvbnN0IHRhYnMgPSBlbGVtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1uYXYtbGluaycpO1xyXG4gICAgICAgIGNvbnN0IHBhbmVzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLXRhYi1wYW5lJyk7XHJcbiAgICAgICAgY29uc3QgY2xlYXJBY3RpdmUgPSAoKSA9PiB7XHJcbiAgICAgICAgICAgIEFycmF5LnByb3RvdHlwZS5mb3JFYWNoLmNhbGwodGFicywgKHRhYikgPT4ge1xyXG4gICAgICAgICAgICAgICAgdGFiLmNsYXNzTGlzdC5yZW1vdmUoJ3Rybi1uYXYtYWN0aXZlJyk7XHJcbiAgICAgICAgICAgICAgICB0YWIuYXJpYVNlbGVjdGVkID0gZmFsc2U7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHBhbmVzLCBwYW5lID0+IHBhbmUuY2xhc3NMaXN0LnJlbW92ZSgndHJuLXRhYi1hY3RpdmUnKSk7XHJcbiAgICAgICAgfTtcclxuICAgICAgICBjb25zdCBzZXRBY3RpdmUgPSAodGFyZ2V0SWQpID0+IHtcclxuICAgICAgICAgICAgY29uc3QgdGFyZ2V0VGFiID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignYVtocmVmPVwiIycgKyB0YXJnZXRJZCArICdcIl0udHJuLW5hdi1saW5rJyk7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFBhbmVJZCA9IHRhcmdldFRhYiAmJiB0YXJnZXRUYWIuZGF0YXNldCAmJiB0YXJnZXRUYWIuZGF0YXNldC50YXJnZXQgfHwgZmFsc2U7XHJcblxyXG4gICAgICAgICAgICBpZiAodGFyZ2V0UGFuZUlkKSB7XHJcbiAgICAgICAgICAgICAgICBjbGVhckFjdGl2ZSgpO1xyXG4gICAgICAgICAgICAgICAgdGFyZ2V0VGFiLmNsYXNzTGlzdC5hZGQoJ3Rybi1uYXYtYWN0aXZlJyk7XHJcbiAgICAgICAgICAgICAgICB0YXJnZXRUYWIuYXJpYVNlbGVjdGVkID0gdHJ1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0YXJnZXRQYW5lSWQpLmNsYXNzTGlzdC5hZGQoJ3Rybi10YWItYWN0aXZlJyk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9O1xyXG4gICAgICAgIGNvbnN0IHRhYkNsaWNrID0gKGV2ZW50KSA9PiB7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFRhYiA9IGV2ZW50LmN1cnJlbnRUYXJnZXQ7XHJcbiAgICAgICAgICAgIGNvbnN0IHRhcmdldFBhbmVJZCA9IHRhcmdldFRhYiAmJiB0YXJnZXRUYWIuZGF0YXNldCAmJiB0YXJnZXRUYWIuZGF0YXNldC50YXJnZXQgfHwgZmFsc2U7XHJcblxyXG4gICAgICAgICAgICBpZiAodGFyZ2V0UGFuZUlkKSB7XHJcbiAgICAgICAgICAgICAgICBzZXRBY3RpdmUodGFyZ2V0UGFuZUlkKTtcclxuICAgICAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9O1xyXG5cclxuICAgICAgICBBcnJheS5wcm90b3R5cGUuZm9yRWFjaC5jYWxsKHRhYnMsICh0YWIpID0+IHtcclxuICAgICAgICAgICAgdGFiLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgdGFiQ2xpY2spO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICBpZiAobG9jYXRpb24uaGFzaCkge1xyXG4gICAgICAgICAgICBzZXRBY3RpdmUobG9jYXRpb24uaGFzaC5zdWJzdHIoMSkpO1xyXG4gICAgICAgIH0gZWxzZSBpZiAodGFicy5sZW5ndGggPiAwKSB7XHJcbiAgICAgICAgICAgIHNldEFjdGl2ZSh0YWJzWzBdLmRhdGFzZXQudGFyZ2V0KTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcblxyXG59XHJcblxyXG4vL3Rybi5pbml0aWFsaXplKCk7XHJcbmlmICghd2luZG93LnRybl9vYmpfaW5zdGFuY2UpIHtcclxuICAgIHdpbmRvdy50cm5fb2JqX2luc3RhbmNlID0gbmV3IFRvdXJuYW1hdGNoKCk7XHJcblxyXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCBmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgIGNvbnN0IHRhYlZpZXdzID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLW5hdicpO1xyXG5cclxuICAgICAgICBBcnJheS5mcm9tKHRhYlZpZXdzKS5mb3JFYWNoKCh0YWIpID0+IHtcclxuICAgICAgICAgICAgdHJuLnRhYnModGFiKTtcclxuICAgICAgICB9KTtcclxuXHJcbiAgICAgICAgY29uc3QgZHJvcGRvd25zID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgndHJuLWRyb3Bkb3duLXRvZ2dsZScpO1xyXG4gICAgICAgIGNvbnN0IGhhbmRsZURyb3Bkb3duQ2xvc2UgPSAoKSA9PiB7XHJcbiAgICAgICAgICAgIEFycmF5LmZyb20oZHJvcGRvd25zKS5mb3JFYWNoKChkcm9wZG93bikgPT4ge1xyXG4gICAgICAgICAgICAgICAgZHJvcGRvd24ubmV4dEVsZW1lbnRTaWJsaW5nLmNsYXNzTGlzdC5yZW1vdmUoJ3Rybi1zaG93Jyk7XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICBkb2N1bWVudC5yZW1vdmVFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgaGFuZGxlRHJvcGRvd25DbG9zZSwgZmFsc2UpO1xyXG4gICAgICAgIH07XHJcblxyXG4gICAgICAgIEFycmF5LmZyb20oZHJvcGRvd25zKS5mb3JFYWNoKChkcm9wZG93bikgPT4ge1xyXG4gICAgICAgICAgICBkcm9wZG93bi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uKGUpIHtcclxuICAgICAgICAgICAgICAgIGUuc3RvcFByb3BhZ2F0aW9uKCk7XHJcbiAgICAgICAgICAgICAgICB0aGlzLm5leHRFbGVtZW50U2libGluZy5jbGFzc0xpc3QuYWRkKCd0cm4tc2hvdycpO1xyXG4gICAgICAgICAgICAgICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGhhbmRsZURyb3Bkb3duQ2xvc2UsIGZhbHNlKTtcclxuICAgICAgICAgICAgfSwgZmFsc2UpO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgIH0sIGZhbHNlKTtcclxufVxyXG5leHBvcnQgbGV0IHRybiA9IHdpbmRvdy50cm5fb2JqX2luc3RhbmNlO1xyXG5cclxuY2xhc3MgVG91cm5hbWF0Y2hfQXV0b2NvbXBsZXRlIHtcclxuXHJcbiAgICAvLyBjdXJyZW50Rm9jdXM7XHJcbiAgICAvL1xyXG4gICAgLy8gbmFtZUlucHV0O1xyXG4gICAgLy9cclxuICAgIC8vIHNlbGY7XHJcblxyXG4gICAgY29uc3RydWN0b3IoaW5wdXQsIGRhdGFDYWxsYmFjaykge1xyXG4gICAgICAgIC8vIHRoaXMuc2VsZiA9IHRoaXM7XHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQgPSBpbnB1dDtcclxuXHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcihcImlucHV0XCIsICgpID0+IHtcclxuICAgICAgICAgICAgbGV0IGEsIGIsIGksIHZhbCA9IHRoaXMubmFtZUlucHV0LnZhbHVlOy8vdGhpcy52YWx1ZTtcclxuICAgICAgICAgICAgbGV0IHBhcmVudCA9IHRoaXMubmFtZUlucHV0LnBhcmVudE5vZGU7Ly90aGlzLnBhcmVudE5vZGU7XHJcblxyXG4gICAgICAgICAgICAvLyBsZXQgcCA9IG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpID0+IHtcclxuICAgICAgICAgICAgLy8gICAgIC8qIG5lZWQgdG8gcXVlcnkgc2VydmVyIGZvciBuYW1lcyBoZXJlLiAqL1xyXG4gICAgICAgICAgICAvLyAgICAgbGV0IHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLm9wZW4oJ0dFVCcsIG9wdGlvbnMuYXBpX3VybCArICdwbGF5ZXJzLz9zZWFyY2g9JyArIHZhbCArICcmcGVyX3BhZ2U9NScpO1xyXG4gICAgICAgICAgICAvLyAgICAgeGhyLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi94LXd3dy1mb3JtLXVybGVuY29kZWQnKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5zZXRSZXF1ZXN0SGVhZGVyKCdYLVdQLU5vbmNlJywgb3B0aW9ucy5yZXN0X25vbmNlKTtcclxuICAgICAgICAgICAgLy8gICAgIHhoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgaWYgKHhoci5zdGF0dXMgPT09IDIwMCkge1xyXG4gICAgICAgICAgICAvLyAgICAgICAgICAgICAvLyByZXNvbHZlKEpTT04ucGFyc2UoeGhyLnJlc3BvbnNlKS5tYXAoKHBsYXllcikgPT4ge3JldHVybiB7ICd2YWx1ZSc6IHBsYXllci5pZCwgJ3RleHQnOiBwbGF5ZXIubmFtZSB9O30pKTtcclxuICAgICAgICAgICAgLy8gICAgICAgICAgICAgcmVzb2x2ZShKU09OLnBhcnNlKHhoci5yZXNwb25zZSkubWFwKChwbGF5ZXIpID0+IHtyZXR1cm4gcGxheWVyLm5hbWU7fSkpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIC8vICAgICAgICAgICAgIHJlamVjdCgpO1xyXG4gICAgICAgICAgICAvLyAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gICAgIH07XHJcbiAgICAgICAgICAgIC8vICAgICB4aHIuc2VuZCgpO1xyXG4gICAgICAgICAgICAvLyB9KTtcclxuICAgICAgICAgICAgZGF0YUNhbGxiYWNrKHZhbCkudGhlbigoZGF0YSkgPT4gey8vcC50aGVuKChkYXRhKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhkYXRhKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmNsb3NlIGFueSBhbHJlYWR5IG9wZW4gbGlzdHMgb2YgYXV0by1jb21wbGV0ZWQgdmFsdWVzKi9cclxuICAgICAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cygpO1xyXG4gICAgICAgICAgICAgICAgaWYgKCF2YWwpIHsgcmV0dXJuIGZhbHNlO31cclxuICAgICAgICAgICAgICAgIHRoaXMuY3VycmVudEZvY3VzID0gLTE7XHJcblxyXG4gICAgICAgICAgICAgICAgLypjcmVhdGUgYSBESVYgZWxlbWVudCB0aGF0IHdpbGwgY29udGFpbiB0aGUgaXRlbXMgKHZhbHVlcyk6Ki9cclxuICAgICAgICAgICAgICAgIGEgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiRElWXCIpO1xyXG4gICAgICAgICAgICAgICAgYS5zZXRBdHRyaWJ1dGUoXCJpZFwiLCB0aGlzLm5hbWVJbnB1dC5pZCArIFwiLWF1dG8tY29tcGxldGUtbGlzdFwiKTtcclxuICAgICAgICAgICAgICAgIGEuc2V0QXR0cmlidXRlKFwiY2xhc3NcIiwgXCJ0cm4tYXV0by1jb21wbGV0ZS1pdGVtc1wiKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmFwcGVuZCB0aGUgRElWIGVsZW1lbnQgYXMgYSBjaGlsZCBvZiB0aGUgYXV0by1jb21wbGV0ZSBjb250YWluZXI6Ki9cclxuICAgICAgICAgICAgICAgIHBhcmVudC5hcHBlbmRDaGlsZChhKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvKmZvciBlYWNoIGl0ZW0gaW4gdGhlIGFycmF5Li4uKi9cclxuICAgICAgICAgICAgICAgIGZvciAoaSA9IDA7IGkgPCBkYXRhLmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbGV0IHRleHQsIHZhbHVlO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAvKiBXaGljaCBmb3JtYXQgZGlkIHRoZXkgZ2l2ZSB1cy4gKi9cclxuICAgICAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGRhdGFbaV0gPT09ICdvYmplY3QnKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHRleHQgPSBkYXRhW2ldWyd0ZXh0J107XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhbHVlID0gZGF0YVtpXVsndmFsdWUnXTtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB0ZXh0ID0gZGF0YVtpXTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWUgPSBkYXRhW2ldO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICAgICAgLypjaGVjayBpZiB0aGUgaXRlbSBzdGFydHMgd2l0aCB0aGUgc2FtZSBsZXR0ZXJzIGFzIHRoZSB0ZXh0IGZpZWxkIHZhbHVlOiovXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHRleHQuc3Vic3RyKDAsIHZhbC5sZW5ndGgpLnRvVXBwZXJDYXNlKCkgPT09IHZhbC50b1VwcGVyQ2FzZSgpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIC8qY3JlYXRlIGEgRElWIGVsZW1lbnQgZm9yIGVhY2ggbWF0Y2hpbmcgZWxlbWVudDoqL1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcIkRJVlwiKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgLyptYWtlIHRoZSBtYXRjaGluZyBsZXR0ZXJzIGJvbGQ6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgPSBcIjxzdHJvbmc+XCIgKyB0ZXh0LnN1YnN0cigwLCB2YWwubGVuZ3RoKSArIFwiPC9zdHJvbmc+XCI7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuaW5uZXJIVE1MICs9IHRleHQuc3Vic3RyKHZhbC5sZW5ndGgpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgLyppbnNlcnQgYSBpbnB1dCBmaWVsZCB0aGF0IHdpbGwgaG9sZCB0aGUgY3VycmVudCBhcnJheSBpdGVtJ3MgdmFsdWU6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5pbm5lckhUTUwgKz0gXCI8aW5wdXQgdHlwZT0naGlkZGVuJyB2YWx1ZT0nXCIgKyB2YWx1ZSArIFwiJz5cIjtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGIuZGF0YXNldC52YWx1ZSA9IHZhbHVlO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBiLmRhdGFzZXQudGV4dCA9IHRleHQ7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiB3aGVuIHNvbWVvbmUgY2xpY2tzIG9uIHRoZSBpdGVtIHZhbHVlIChESVYgZWxlbWVudCk6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgYi5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKGUpID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKGBpdGVtIGNsaWNrZWQgd2l0aCB2YWx1ZSAke2UuY3VycmVudFRhcmdldC5kYXRhc2V0LnZhbHVlfWApO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8qIGluc2VydCB0aGUgdmFsdWUgZm9yIHRoZSBhdXRvY29tcGxldGUgdGV4dCBmaWVsZDogKi9cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LnZhbHVlID0gZS5jdXJyZW50VGFyZ2V0LmRhdGFzZXQudGV4dDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LmRhdGFzZXQuc2VsZWN0ZWRJZCA9IGUuY3VycmVudFRhcmdldC5kYXRhc2V0LnZhbHVlO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8qIGNsb3NlIHRoZSBsaXN0IG9mIGF1dG9jb21wbGV0ZWQgdmFsdWVzLCAob3IgYW55IG90aGVyIG9wZW4gbGlzdHMgb2YgYXV0b2NvbXBsZXRlZCB2YWx1ZXM6Ki9cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuY2xvc2VBbGxMaXN0cygpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMubmFtZUlucHV0LmRpc3BhdGNoRXZlbnQobmV3IEV2ZW50KCdjaGFuZ2UnKSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBhLmFwcGVuZENoaWxkKGIpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfSk7XHJcblxyXG4gICAgICAgIC8qZXhlY3V0ZSBhIGZ1bmN0aW9uIHByZXNzZXMgYSBrZXkgb24gdGhlIGtleWJvYXJkOiovXHJcbiAgICAgICAgdGhpcy5uYW1lSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcihcImtleWRvd25cIiwgKGUpID0+IHtcclxuICAgICAgICAgICAgbGV0IHggPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCh0aGlzLm5hbWVJbnB1dC5pZCArIFwiLWF1dG8tY29tcGxldGUtbGlzdFwiKTtcclxuICAgICAgICAgICAgaWYgKHgpIHggPSB4LmdldEVsZW1lbnRzQnlUYWdOYW1lKFwiZGl2XCIpO1xyXG4gICAgICAgICAgICBpZiAoZS5rZXlDb2RlID09PSA0MCkge1xyXG4gICAgICAgICAgICAgICAgLypJZiB0aGUgYXJyb3cgRE9XTiBrZXkgaXMgcHJlc3NlZCxcclxuICAgICAgICAgICAgICAgICBpbmNyZWFzZSB0aGUgY3VycmVudEZvY3VzIHZhcmlhYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmN1cnJlbnRGb2N1cysrO1xyXG4gICAgICAgICAgICAgICAgLyphbmQgYW5kIG1ha2UgdGhlIGN1cnJlbnQgaXRlbSBtb3JlIHZpc2libGU6Ki9cclxuICAgICAgICAgICAgICAgIHRoaXMuYWRkQWN0aXZlKHgpO1xyXG4gICAgICAgICAgICB9IGVsc2UgaWYgKGUua2V5Q29kZSA9PT0gMzgpIHsgLy91cFxyXG4gICAgICAgICAgICAgICAgLypJZiB0aGUgYXJyb3cgVVAga2V5IGlzIHByZXNzZWQsXHJcbiAgICAgICAgICAgICAgICAgZGVjcmVhc2UgdGhlIGN1cnJlbnRGb2N1cyB2YXJpYWJsZToqL1xyXG4gICAgICAgICAgICAgICAgdGhpcy5jdXJyZW50Rm9jdXMtLTtcclxuICAgICAgICAgICAgICAgIC8qYW5kIGFuZCBtYWtlIHRoZSBjdXJyZW50IGl0ZW0gbW9yZSB2aXNpYmxlOiovXHJcbiAgICAgICAgICAgICAgICB0aGlzLmFkZEFjdGl2ZSh4KTtcclxuICAgICAgICAgICAgfSBlbHNlIGlmIChlLmtleUNvZGUgPT09IDEzKSB7XHJcbiAgICAgICAgICAgICAgICAvKklmIHRoZSBFTlRFUiBrZXkgaXMgcHJlc3NlZCwgcHJldmVudCB0aGUgZm9ybSBmcm9tIGJlaW5nIHN1Ym1pdHRlZCwqL1xyXG4gICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzID4gLTEpIHtcclxuICAgICAgICAgICAgICAgICAgICAvKmFuZCBzaW11bGF0ZSBhIGNsaWNrIG9uIHRoZSBcImFjdGl2ZVwiIGl0ZW06Ki9cclxuICAgICAgICAgICAgICAgICAgICBpZiAoeCkgeFt0aGlzLmN1cnJlbnRGb2N1c10uY2xpY2soKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICAvKmV4ZWN1dGUgYSBmdW5jdGlvbiB3aGVuIHNvbWVvbmUgY2xpY2tzIGluIHRoZSBkb2N1bWVudDoqL1xyXG4gICAgICAgIGRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICB0aGlzLmNsb3NlQWxsTGlzdHMoZS50YXJnZXQpO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG5cclxuICAgIGFkZEFjdGl2ZSh4KSB7XHJcbiAgICAgICAgLyphIGZ1bmN0aW9uIHRvIGNsYXNzaWZ5IGFuIGl0ZW0gYXMgXCJhY3RpdmVcIjoqL1xyXG4gICAgICAgIGlmICgheCkgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgIC8qc3RhcnQgYnkgcmVtb3ZpbmcgdGhlIFwiYWN0aXZlXCIgY2xhc3Mgb24gYWxsIGl0ZW1zOiovXHJcbiAgICAgICAgdGhpcy5yZW1vdmVBY3RpdmUoeCk7XHJcbiAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzID49IHgubGVuZ3RoKSB0aGlzLmN1cnJlbnRGb2N1cyA9IDA7XHJcbiAgICAgICAgaWYgKHRoaXMuY3VycmVudEZvY3VzIDwgMCkgdGhpcy5jdXJyZW50Rm9jdXMgPSAoeC5sZW5ndGggLSAxKTtcclxuICAgICAgICAvKmFkZCBjbGFzcyBcImF1dG9jb21wbGV0ZS1hY3RpdmVcIjoqL1xyXG4gICAgICAgIHhbdGhpcy5jdXJyZW50Rm9jdXNdLmNsYXNzTGlzdC5hZGQoXCJ0cm4tYXV0by1jb21wbGV0ZS1hY3RpdmVcIik7XHJcbiAgICB9XHJcblxyXG4gICAgcmVtb3ZlQWN0aXZlKHgpIHtcclxuICAgICAgICAvKmEgZnVuY3Rpb24gdG8gcmVtb3ZlIHRoZSBcImFjdGl2ZVwiIGNsYXNzIGZyb20gYWxsIGF1dG9jb21wbGV0ZSBpdGVtczoqL1xyXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgeC5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICB4W2ldLmNsYXNzTGlzdC5yZW1vdmUoXCJ0cm4tYXV0by1jb21wbGV0ZS1hY3RpdmVcIik7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIGNsb3NlQWxsTGlzdHMoZWxlbWVudCkge1xyXG4gICAgICAgIGNvbnNvbGUubG9nKFwiY2xvc2UgYWxsIGxpc3RzXCIpO1xyXG4gICAgICAgIC8qY2xvc2UgYWxsIGF1dG9jb21wbGV0ZSBsaXN0cyBpbiB0aGUgZG9jdW1lbnQsXHJcbiAgICAgICAgIGV4Y2VwdCB0aGUgb25lIHBhc3NlZCBhcyBhbiBhcmd1bWVudDoqL1xyXG4gICAgICAgIGxldCB4ID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShcInRybi1hdXRvLWNvbXBsZXRlLWl0ZW1zXCIpO1xyXG4gICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgeC5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgICAgICBpZiAoZWxlbWVudCAhPT0geFtpXSAmJiBlbGVtZW50ICE9PSB0aGlzLm5hbWVJbnB1dCkge1xyXG4gICAgICAgICAgICAgICAgeFtpXS5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKHhbaV0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG59XHJcblxyXG4vLyBGaXJzdCwgY2hlY2tzIGlmIGl0IGlzbid0IGltcGxlbWVudGVkIHlldC5cclxuaWYgKCFTdHJpbmcucHJvdG90eXBlLmZvcm1hdCkge1xyXG4gICAgU3RyaW5nLnByb3RvdHlwZS5mb3JtYXQgPSBmdW5jdGlvbigpIHtcclxuICAgICAgICBjb25zdCBhcmdzID0gYXJndW1lbnRzO1xyXG4gICAgICAgIHJldHVybiB0aGlzLnJlcGxhY2UoL3soXFxkKyl9L2csIGZ1bmN0aW9uKG1hdGNoLCBudW1iZXIpIHtcclxuICAgICAgICAgICAgcmV0dXJuIHR5cGVvZiBhcmdzW251bWJlcl0gIT09ICd1bmRlZmluZWQnXHJcbiAgICAgICAgICAgICAgICA/IGFyZ3NbbnVtYmVyXVxyXG4gICAgICAgICAgICAgICAgOiBtYXRjaFxyXG4gICAgICAgICAgICAgICAgO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfTtcclxufSJdLCJzb3VyY2VSb290IjoiIn0=