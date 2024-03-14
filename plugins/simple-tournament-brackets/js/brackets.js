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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/brackets.js":
/*!****************************!*\
  !*** ./src/js/brackets.js ***!
  \****************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

/**
 * Handles rendering the content for tournament brackets.
 *
 * @link       https://www.tournamatch.com
 * @since      1.0.0
 *
 * @package    Simple Tournament Brackets
 *
 */
(function () {
  'use strict';

  var options = simple_tournament_brackets_options;

  function get_competitors(tournament_id) {
    return fetch("".concat(options.site_url, "/wp-json/wp/v2/stb-tournament/").concat(tournament_id), {
      headers: {
        "Content-Type": "application/json; charset=utf-8"
      }
    }).then(function (response) {
      return response.json();
    });
  }

  function clear(tournament_id, match_id) {
    return fetch("".concat(options.site_url, "/wp-json/simple-tournament-brackets/v1/tournament-matches/clear"), {
      headers: {
        "Content-Type": "application/json; charset=utf-8",
        "X-WP-Nonce": options.rest_nonce
      },
      method: 'POST',
      body: JSON.stringify({
        id: match_id,
        tournament_id: tournament_id
      })
    }).then(function (response) {
      return response.json();
    });
  }

  function advance(tournament_id, match_id, winner_id) {
    return fetch("".concat(options.site_url, "/wp-json/simple-tournament-brackets/v1/tournament-matches/advance"), {
      headers: {
        "Content-Type": "application/json; charset=utf-8",
        "X-WP-Nonce": options.rest_nonce
      },
      method: 'POST',
      body: JSON.stringify({
        id: match_id,
        tournament_id: tournament_id,
        winner_id: winner_id
      })
    }).then(function (response) {
      return response.json();
    });
  }

  window.addEventListener('load', function () {
    function competitorMouseOver(event) {
      var className = "competitor-".concat(event.target.dataset.competitorId);
      Array.from(document.getElementsByClassName(className)).forEach(function (item) {
        item.classList.add('simple-tournament-brackets-competitor-highlight');
      });
    }

    function competitorMouseLeave(event) {
      var className = "competitor-".concat(event.target.dataset.competitorId);
      Array.from(document.getElementsByClassName(className)).forEach(function (item) {
        item.classList.remove('simple-tournament-brackets-competitor-highlight');
      });
    }

    function calculateProgress(tournament) {
      var totalGames = tournament.competitors.length - 1;
      var finishedGames = 0;

      for (var i = tournament.competitors.length / 2; i <= tournament.competitors.length; i++) {
        if (tournament.matches[i]) {
          if (tournament.matches[i].one_id !== null) finishedGames++;
          if (tournament.matches[i].two_id !== null) finishedGames++;
        }
      }

      return finishedGames / totalGames;
    }

    function renderProgress(_float) {
      return "<div class=\"simple-tournament-brackets-progress\" style=\"width: ".concat(100 * _float, "%;\">&nbsp;</div> ");
    }

    function renderDropDown(tournament, tournament_id, match_id) {
      var content = "";
      var is_first_round = match_id < tournament.competitors.length / 2;

      if (tournament.matches[match_id] && (tournament.matches[match_id].one_id !== null || tournament.matches[match_id].two_id !== null)) {
        content += "<div class=\"dropdown\">";
        content += "<span class=\"more-details dashicons dashicons-admin-generic\"></span>";
        content += "<div class=\"dropdown-content\" >";

        if (tournament.matches[match_id] && tournament.matches[match_id].one_id !== null) {
          var one_id = tournament.matches[match_id].one_id;
          content += "<a href=\"#\" class=\"advance-competitor\" data-tournament-id=\"".concat(tournament_id, "\" data-match-id=\"").concat(match_id, "\" data-competitor-id=\"").concat(one_id, "\">").concat(options.language.advance.replace('{NAME}', tournament.competitors[one_id].name), "</a>");
        }

        if (tournament.matches[match_id] && tournament.matches[match_id].two_id !== null) {
          var two_id = tournament.matches[match_id].two_id;
          content += "<a href=\"#\" class=\"advance-competitor\" data-tournament-id=\"".concat(tournament_id, "\" data-match-id=\"").concat(match_id, "\" data-competitor-id=\"").concat(two_id, "\">").concat(options.language.advance.replace('{NAME}', tournament.competitors[two_id].name), "</a>");
        }

        if (!is_first_round) {
          content += "<a href=\"#\" class=\"clear-competitors\" data-tournament-id=\"".concat(tournament_id, "\" data-match-id=\"").concat(match_id, "\">").concat(options.language.clear, "</a>");
        }

        content += "</div>";
        content += "</div>";
      }

      return content;
    }

    function renderMatch(tournament, tournament_id, match_id, flow, can_edit_matches) {
      var content = "";
      content += "<div class=\"simple-tournament-brackets-match\">";
      content += "<div class=\"horizontal-line\"></div>";
      content += "<div class=\"simple-tournament-brackets-match-body\">";

      if (tournament.matches[match_id] && tournament.matches[match_id].one_id !== null) {
        var one_id = tournament.matches[match_id].one_id;
        var one_name = tournament.competitors[one_id] ? tournament.competitors[one_id].name : '&nbsp;';
        content += "<span class=\"simple-tournament-brackets-competitor competitor-".concat(one_id, "\" data-competitor-id=\"").concat(one_id, "\">").concat(one_name, "</span>");
      } else {
        content += "<span class=\"simple-tournament-brackets-competitor\">&nbsp;</span>";
      }

      if (tournament.matches[match_id] && tournament.matches[match_id].two_id !== null) {
        var two_id = tournament.matches[match_id] ? tournament.matches[match_id].two_id : null;
        var two_name = tournament.matches[match_id] ? tournament.competitors[two_id].name : '&nbsp;';
        content += "<span class=\"simple-tournament-brackets-competitor competitor-".concat(two_id, "\" data-competitor-id=\"").concat(two_id, "\">").concat(two_name, "</span>");
      } else {
        content += "<span class=\"simple-tournament-brackets-competitor\">&nbsp;</span>";
      }

      content += "</div>";

      if (flow) {
        if (1 === match_id % 2) {
          content += "<div class=\"bottom-half\">";
        } else {
          content += "<div class=\"top-half\">";
        }

        if (can_edit_matches) {
          content += renderDropDown(tournament, tournament_id, match_id);
        }

        content += "</div>";
      }

      content += "</div>";
      return content;
    }

    function filterRounds(origRounds, numberOfRounds) {
      var rounds = _toConsumableArray(origRounds);

      if (7 >= numberOfRounds) {
        rounds[4] = null;
      }

      if (6 >= numberOfRounds) {
        rounds[3] = null;
      }

      if (5 >= numberOfRounds) {
        rounds[5] = null;
      }

      if (4 >= numberOfRounds) {
        rounds[2] = null;
      }

      if (3 >= numberOfRounds) {
        rounds[6] = null;
      }

      if (2 >= numberOfRounds) {
        rounds[1] = null;
      }

      return rounds.filter(function (r) {
        return r !== null;
      });
    }

    function renderBrackets(tournament, container, tournament_id) {
      var content = "";
      var numberOfGames;
      var matchPaddingCount;
      var rounds = filterRounds(options.language.rounds, tournament.rounds);
      content += "<div class=\"simple-tournament-brackets-round-header-container\">";

      for (var i = 0; i <= tournament.rounds; i++) {
        content += "<span class=\"simple-tournament-brackets-round-header\">".concat(rounds[i], "</span>");
      }

      content += "</div>";
      content += renderProgress(calculateProgress(tournament));
      content += "<div class=\"simple-tournament-brackets-round-body-container\">";
      var spot = 1;
      var sumOfGames = 0;

      for (var round = 1; round <= tournament.rounds; round++) {
        numberOfGames = Math.ceil(tournament.competitors.length / Math.pow(2, round));
        matchPaddingCount = Math.pow(2, round) - 1;
        content += "<div class=\"simple-tournament-brackets-round-body\">";

        for (spot; spot <= numberOfGames + sumOfGames; spot++) {
          for (var padding = 0; padding < matchPaddingCount; padding++) {
            if (1 === spot % 2) {
              content += "<div class=\"match-half\">&nbsp;</div> ";
            } else {
              content += "<div class=\"vertical-line\">&nbsp;</div> ";
            }
          }

          content += renderMatch(tournament, tournament_id, spot - 1, round !== tournament.rounds, options.can_edit_matches);

          for (var _padding = 0; _padding < matchPaddingCount; _padding++) {
            if (round !== tournament.rounds && 1 === spot % 2) {
              content += "<div class=\"vertical-line\">&nbsp;</div> ";
            } else {
              content += "<div class=\"match-half\">&nbsp;</div> ";
            }
          }
        }

        content += "</div>";
        sumOfGames += numberOfGames;
      } // Display the last winner's spot.


      content += "<div class=\"simple-tournament-brackets-round-body\">";

      for (var _padding2 = 0; _padding2 < matchPaddingCount; _padding2++) {
        content += "<div class=\"match-half\">&nbsp;</div> ";
      }

      content += "<div class=\"simple-tournament-brackets-match\">";
      content += "<div class=\"winners-line\">";

      if (options.can_edit_matches) {
        content += renderDropDown(tournament, tournament_id, spot - 2);
      }

      content += "</div>";
      content += "<div class=\"simple-tournament-brackets-match-body\">";
      content += "<span class=\"simple-tournament-brackets-competitor\"><strong>".concat(options.language.winner, "</strong></span>");

      if (tournament.matches[spot - 1] && tournament.matches[spot - 1].one_id !== null) {
        var winner_id = tournament.matches[spot - 1].one_id;
        content += "<span class=\"simple-tournament-brackets-competitor competitor-".concat(winner_id, "\" data-competitor-id=\"").concat(winner_id, "\">").concat(tournament.competitors[winner_id].name, "</span>");
      } else {
        content += "<span class=\"simple-tournament-brackets-competitor\">&nbsp;</span>";
      }

      content += "</div>";
      content += "</div>";

      for (var _padding3 = 0; _padding3 < matchPaddingCount; _padding3++) {
        content += "<div class=\"match-half\">&nbsp;</div> ";
      }

      content += "</div>"; // End of display last winner's spot.

      content += "</div>";
      container.innerHTML = content;
      Array.from(document.getElementsByClassName('simple-tournament-brackets-competitor')).forEach(function (item) {
        item.addEventListener('mouseover', competitorMouseOver);
        item.addEventListener('mouseleave', competitorMouseLeave);
      });
      Array.from(document.getElementsByClassName('advance-competitor')).forEach(function (item) {
        item.addEventListener('click', function (e) {
          e.preventDefault();
          advance(e.target.dataset.tournamentId, e.target.dataset.matchId, e.target.dataset.competitorId).then(function () {
            location.reload();
          });
        });
      });
      Array.from(document.getElementsByClassName('clear-competitors')).forEach(function (item) {
        item.addEventListener('click', function (e) {
          e.preventDefault();
          clear(e.target.dataset.tournamentId, e.target.dataset.matchId).then(function () {
            location.reload();
          });
        });
      });
    }

    Array.from(document.getElementsByClassName('simple-tournament-brackets')).forEach(function (item) {
      get_competitors(item.dataset.tournamentId).then(function (response) {
        renderBrackets(response.stb_match_data, item, item.dataset.tournamentId);
      });
    });
  }, false);
})();

/***/ }),

/***/ 0:
/*!**********************************!*\
  !*** multi ./src/js/brackets.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\brackets\wp-content\plugins\simple-tournament-brackets\src\js\brackets.js */"./src/js/brackets.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL2JyYWNrZXRzLmpzIl0sIm5hbWVzIjpbIm9wdGlvbnMiLCJzaW1wbGVfdG91cm5hbWVudF9icmFja2V0c19vcHRpb25zIiwiZ2V0X2NvbXBldGl0b3JzIiwidG91cm5hbWVudF9pZCIsImZldGNoIiwic2l0ZV91cmwiLCJoZWFkZXJzIiwidGhlbiIsInJlc3BvbnNlIiwianNvbiIsImNsZWFyIiwibWF0Y2hfaWQiLCJyZXN0X25vbmNlIiwibWV0aG9kIiwiYm9keSIsIkpTT04iLCJzdHJpbmdpZnkiLCJpZCIsImFkdmFuY2UiLCJ3aW5uZXJfaWQiLCJ3aW5kb3ciLCJhZGRFdmVudExpc3RlbmVyIiwiY29tcGV0aXRvck1vdXNlT3ZlciIsImV2ZW50IiwiY2xhc3NOYW1lIiwidGFyZ2V0IiwiZGF0YXNldCIsImNvbXBldGl0b3JJZCIsIkFycmF5IiwiZnJvbSIsImRvY3VtZW50IiwiZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSIsImZvckVhY2giLCJpdGVtIiwiY2xhc3NMaXN0IiwiYWRkIiwiY29tcGV0aXRvck1vdXNlTGVhdmUiLCJyZW1vdmUiLCJjYWxjdWxhdGVQcm9ncmVzcyIsInRvdXJuYW1lbnQiLCJ0b3RhbEdhbWVzIiwiY29tcGV0aXRvcnMiLCJsZW5ndGgiLCJmaW5pc2hlZEdhbWVzIiwiaSIsIm1hdGNoZXMiLCJvbmVfaWQiLCJ0d29faWQiLCJyZW5kZXJQcm9ncmVzcyIsImZsb2F0IiwicmVuZGVyRHJvcERvd24iLCJjb250ZW50IiwiaXNfZmlyc3Rfcm91bmQiLCJsYW5ndWFnZSIsInJlcGxhY2UiLCJuYW1lIiwicmVuZGVyTWF0Y2giLCJmbG93IiwiY2FuX2VkaXRfbWF0Y2hlcyIsIm9uZV9uYW1lIiwidHdvX25hbWUiLCJmaWx0ZXJSb3VuZHMiLCJvcmlnUm91bmRzIiwibnVtYmVyT2ZSb3VuZHMiLCJyb3VuZHMiLCJmaWx0ZXIiLCJyIiwicmVuZGVyQnJhY2tldHMiLCJjb250YWluZXIiLCJudW1iZXJPZkdhbWVzIiwibWF0Y2hQYWRkaW5nQ291bnQiLCJzcG90Iiwic3VtT2ZHYW1lcyIsInJvdW5kIiwiTWF0aCIsImNlaWwiLCJwb3ciLCJwYWRkaW5nIiwid2lubmVyIiwiaW5uZXJIVE1MIiwiZSIsInByZXZlbnREZWZhdWx0IiwidG91cm5hbWVudElkIiwibWF0Y2hJZCIsImxvY2F0aW9uIiwicmVsb2FkIiwic3RiX21hdGNoX2RhdGEiXSwibWFwcGluZ3MiOiI7UUFBQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTs7O1FBR0E7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLDBDQUEwQyxnQ0FBZ0M7UUFDMUU7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQSx3REFBd0Qsa0JBQWtCO1FBQzFFO1FBQ0EsaURBQWlELGNBQWM7UUFDL0Q7O1FBRUE7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBLHlDQUF5QyxpQ0FBaUM7UUFDMUUsZ0hBQWdILG1CQUFtQixFQUFFO1FBQ3JJO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0EsMkJBQTJCLDBCQUEwQixFQUFFO1FBQ3ZELGlDQUFpQyxlQUFlO1FBQ2hEO1FBQ0E7UUFDQTs7UUFFQTtRQUNBLHNEQUFzRCwrREFBK0Q7O1FBRXJIO1FBQ0E7OztRQUdBO1FBQ0E7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQ2xGQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxDQUFDLFlBQVk7QUFDVDs7QUFFQSxNQUFNQSxPQUFPLEdBQUdDLGtDQUFoQjs7QUFFQSxXQUFTQyxlQUFULENBQXlCQyxhQUF6QixFQUF3QztBQUNwQyxXQUFPQyxLQUFLLFdBQUlKLE9BQU8sQ0FBQ0ss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TCxDQUFhd0QsWUFBN0MsQ0FBZDtBQUNILE9BSEw7QUFJSCxLQVBUO0FBVUgsR0E5UEwsRUErUEksS0EvUEo7QUFpUUgsQ0E1U0QsSSIsImZpbGUiOiJicmFja2V0cy5qcyIsInNvdXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwgeyBlbnVtZXJhYmxlOiB0cnVlLCBnZXQ6IGdldHRlciB9KTtcbiBcdFx0fVxuIFx0fTtcblxuIFx0Ly8gZGVmaW5lIF9fZXNNb2R1bGUgb24gZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yID0gZnVuY3Rpb24oZXhwb3J0cykge1xuIFx0XHRpZih0eXBlb2YgU3ltYm9sICE9PSAndW5kZWZpbmVkJyAmJiBTeW1ib2wudG9TdHJpbmdUYWcpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgU3ltYm9sLnRvU3RyaW5nVGFnLCB7IHZhbHVlOiAnTW9kdWxlJyB9KTtcbiBcdFx0fVxuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgJ19fZXNNb2R1bGUnLCB7IHZhbHVlOiB0cnVlIH0pO1xuIFx0fTtcblxuIFx0Ly8gY3JlYXRlIGEgZmFrZSBuYW1lc3BhY2Ugb2JqZWN0XG4gXHQvLyBtb2RlICYgMTogdmFsdWUgaXMgYSBtb2R1bGUgaWQsIHJlcXVpcmUgaXRcbiBcdC8vIG1vZGUgJiAyOiBtZXJnZSBhbGwgcHJvcGVydGllcyBvZiB2YWx1ZSBpbnRvIHRoZSBuc1xuIFx0Ly8gbW9kZSAmIDQ6IHJldHVybiB2YWx1ZSB3aGVuIGFscmVhZHkgbnMgb2JqZWN0XG4gXHQvLyBtb2RlICYgOHwxOiBiZWhhdmUgbGlrZSByZXF1aXJlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnQgPSBmdW5jdGlvbih2YWx1ZSwgbW9kZSkge1xuIFx0XHRpZihtb2RlICYgMSkgdmFsdWUgPSBfX3dlYnBhY2tfcmVxdWlyZV9fKHZhbHVlKTtcbiBcdFx0aWYobW9kZSAmIDgpIHJldHVybiB2YWx1ZTtcbiBcdFx0aWYoKG1vZGUgJiA0KSAmJiB0eXBlb2YgdmFsdWUgPT09ICdvYmplY3QnICYmIHZhbHVlICYmIHZhbHVlLl9fZXNNb2R1bGUpIHJldHVybiB2YWx1ZTtcbiBcdFx0dmFyIG5zID0gT2JqZWN0LmNyZWF0ZShudWxsKTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yKG5zKTtcbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KG5zLCAnZGVmYXVsdCcsIHsgZW51bWVyYWJsZTogdHJ1ZSwgdmFsdWU6IHZhbHVlIH0pO1xuIFx0XHRpZihtb2RlICYgMiAmJiB0eXBlb2YgdmFsdWUgIT0gJ3N0cmluZycpIGZvcih2YXIga2V5IGluIHZhbHVlKSBfX3dlYnBhY2tfcmVxdWlyZV9fLmQobnMsIGtleSwgZnVuY3Rpb24oa2V5KSB7IHJldHVybiB2YWx1ZVtrZXldOyB9LmJpbmQobnVsbCwga2V5KSk7XG4gXHRcdHJldHVybiBucztcbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiXCI7XG5cblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSAwKTtcbiIsIi8qKlxyXG4gKiBIYW5kbGVzIHJlbmRlcmluZyB0aGUgY29udGVudCBmb3IgdG91cm5hbWVudCBicmFja2V0cy5cclxuICpcclxuICogQGxpbmsgICAgICAgaHR0cHM6Ly93d3cudG91cm5hbWF0Y2guY29tXHJcbiAqIEBzaW5jZSAgICAgIDEuMC4wXHJcbiAqXHJcbiAqIEBwYWNrYWdlICAgIFNpbXBsZSBUb3VybmFtZW50IEJyYWNrZXRzXHJcbiAqXHJcbiAqL1xyXG4oZnVuY3Rpb24gKCkge1xyXG4gICAgJ3VzZSBzdHJpY3QnO1xyXG5cclxuICAgIGNvbnN0IG9wdGlvbnMgPSBzaW1wbGVfdG91cm5hbWVudF9icmFja2V0c19vcHRpb25zO1xyXG5cclxuICAgIGZ1bmN0aW9uIGdldF9jb21wZXRpdG9ycyh0b3VybmFtZW50X2lkKSB7XHJcbiAgICAgICAgcmV0dXJuIGZldGNoKGAke29wdGlvbnMuc2l0ZV91cmx9L3dwLWpzb24vd3AvdjIvc3RiLXRvdXJuYW1lbnQvJHt0b3VybmFtZW50X2lkfWAsIHtcclxuICAgICAgICAgICAgaGVhZGVyczoge1wiQ29udGVudC1UeXBlXCI6IFwiYXBwbGljYXRpb24vanNvbjsgY2hhcnNldD11dGYtOFwifSxcclxuICAgICAgICB9KVxyXG4gICAgICAgICAgICAudGhlbihyZXNwb25zZSA9PiByZXNwb25zZS5qc29uKCkpO1xyXG4gICAgfVxyXG5cclxuICAgIGZ1bmN0aW9uIGNsZWFyKHRvdXJuYW1lbnRfaWQsIG1hdGNoX2lkKSB7XHJcbiAgICAgICAgcmV0dXJuIGZldGNoKGAke29wdGlvbnMuc2l0ZV91cmx9L3dwLWpzb24vc2ltcGxlLXRvdXJuYW1lbnQtYnJhY2tldHMvdjEvdG91cm5hbWVudC1tYXRjaGVzL2NsZWFyYCwge1xyXG4gICAgICAgICAgICBoZWFkZXJzOiB7XHJcbiAgICAgICAgICAgICAgICBcIkNvbnRlbnQtVHlwZVwiOiBcImFwcGxpY2F0aW9uL2pzb247IGNoYXJzZXQ9dXRmLThcIixcclxuICAgICAgICAgICAgICAgIFwiWC1XUC1Ob25jZVwiOiBvcHRpb25zLnJlc3Rfbm9uY2UsXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIG1ldGhvZDogJ1BPU1QnLFxyXG4gICAgICAgICAgICBib2R5OiBKU09OLnN0cmluZ2lmeSh7XHJcbiAgICAgICAgICAgICAgICBpZDogbWF0Y2hfaWQsXHJcbiAgICAgICAgICAgICAgICB0b3VybmFtZW50X2lkOiB0b3VybmFtZW50X2lkLFxyXG4gICAgICAgICAgICB9KVxyXG4gICAgICAgIH0pXHJcbiAgICAgICAgICAgIC50aGVuKHJlc3BvbnNlID0+IHJlc3BvbnNlLmpzb24oKSk7XHJcbiAgICB9XHJcblxyXG4gICAgZnVuY3Rpb24gYWR2YW5jZSh0b3VybmFtZW50X2lkLCBtYXRjaF9pZCwgd2lubmVyX2lkKSB7XHJcbiAgICAgICAgcmV0dXJuIGZldGNoKGAke29wdGlvbnMuc2l0ZV91cmx9L3dwLWpzb24vc2ltcGxlLXRvdXJuYW1lbnQtYnJhY2tldHMvdjEvdG91cm5hbWVudC1tYXRjaGVzL2FkdmFuY2VgLCB7XHJcbiAgICAgICAgICAgIGhlYWRlcnM6IHtcclxuICAgICAgICAgICAgICAgIFwiQ29udGVudC1UeXBlXCI6IFwiYXBwbGljYXRpb24vanNvbjsgY2hhcnNldD11dGYtOFwiLFxyXG4gICAgICAgICAgICAgICAgXCJYLVdQLU5vbmNlXCI6IG9wdGlvbnMucmVzdF9ub25jZSxcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAgbWV0aG9kOiAnUE9TVCcsXHJcbiAgICAgICAgICAgIGJvZHk6IEpTT04uc3RyaW5naWZ5KHtcclxuICAgICAgICAgICAgICAgIGlkOiBtYXRjaF9pZCxcclxuICAgICAgICAgICAgICAgIHRvdXJuYW1lbnRfaWQ6IHRvdXJuYW1lbnRfaWQsXHJcbiAgICAgICAgICAgICAgICB3aW5uZXJfaWQ6IHdpbm5lcl9pZCxcclxuICAgICAgICAgICAgfSlcclxuICAgICAgICB9KVxyXG4gICAgICAgICAgICAudGhlbihyZXNwb25zZSA9PiByZXNwb25zZS5qc29uKCkpO1xyXG4gICAgfVxyXG5cclxuICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKFxyXG4gICAgICAgICdsb2FkJyxcclxuICAgICAgICBmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgICAgICAgICBmdW5jdGlvbiBjb21wZXRpdG9yTW91c2VPdmVyKGV2ZW50KSB7XHJcbiAgICAgICAgICAgICAgICBjb25zdCBjbGFzc05hbWUgPSBgY29tcGV0aXRvci0ke2V2ZW50LnRhcmdldC5kYXRhc2V0LmNvbXBldGl0b3JJZH1gO1xyXG4gICAgICAgICAgICAgICAgQXJyYXkuZnJvbShkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKGNsYXNzTmFtZSkpXHJcbiAgICAgICAgICAgICAgICAgICAgLmZvckVhY2goXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGl0ZW0gPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaXRlbS5jbGFzc0xpc3QuYWRkKCdzaW1wbGUtdG91cm5hbWVudC1icmFja2V0cy1jb21wZXRpdG9yLWhpZ2hsaWdodCcpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgKTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgZnVuY3Rpb24gY29tcGV0aXRvck1vdXNlTGVhdmUoZXZlbnQpIHtcclxuICAgICAgICAgICAgICAgIGNvbnN0IGNsYXNzTmFtZSA9IGBjb21wZXRpdG9yLSR7ZXZlbnQudGFyZ2V0LmRhdGFzZXQuY29tcGV0aXRvcklkfWA7XHJcbiAgICAgICAgICAgICAgICBBcnJheS5mcm9tKGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoY2xhc3NOYW1lKSlcclxuICAgICAgICAgICAgICAgICAgICAuZm9yRWFjaChcclxuICAgICAgICAgICAgICAgICAgICAgICAgaXRlbSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpdGVtLmNsYXNzTGlzdC5yZW1vdmUoJ3NpbXBsZS10b3VybmFtZW50LWJyYWNrZXRzLWNvbXBldGl0b3ItaGlnaGxpZ2h0Jyk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICApO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBmdW5jdGlvbiBjYWxjdWxhdGVQcm9ncmVzcyh0b3VybmFtZW50KSB7XHJcbiAgICAgICAgICAgICAgICBjb25zdCB0b3RhbEdhbWVzID0gdG91cm5hbWVudC5jb21wZXRpdG9ycy5sZW5ndGggLSAxO1xyXG4gICAgICAgICAgICAgICAgbGV0IGZpbmlzaGVkR2FtZXMgPSAwO1xyXG5cclxuICAgICAgICAgICAgICAgIGZvciAobGV0IGkgPSAodG91cm5hbWVudC5jb21wZXRpdG9ycy5sZW5ndGggLyAyKTsgaSA8PSB0b3VybmFtZW50LmNvbXBldGl0b3JzLmxlbmd0aDsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHRvdXJuYW1lbnQubWF0Y2hlc1tpXSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAodG91cm5hbWVudC5tYXRjaGVzW2ldLm9uZV9pZCAhPT0gbnVsbCkgZmluaXNoZWRHYW1lcysrO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAodG91cm5hbWVudC5tYXRjaGVzW2ldLnR3b19pZCAhPT0gbnVsbCkgZmluaXNoZWRHYW1lcysrO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIHJldHVybiAoZmluaXNoZWRHYW1lcyAvIHRvdGFsR2FtZXMpO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBmdW5jdGlvbiByZW5kZXJQcm9ncmVzcyhmbG9hdCkge1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuIGA8ZGl2IGNsYXNzPVwic2ltcGxlLXRvdXJuYW1lbnQtYnJhY2tldHMtcHJvZ3Jlc3NcIiBzdHlsZT1cIndpZHRoOiAkezEwMCAqIGZsb2F0fSU7XCI+Jm5ic3A7PC9kaXY+IGA7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGZ1bmN0aW9uIHJlbmRlckRyb3BEb3duKHRvdXJuYW1lbnQsIHRvdXJuYW1lbnRfaWQsIG1hdGNoX2lkKSB7XHJcbiAgICAgICAgICAgICAgICBsZXQgY29udGVudCA9IGBgO1xyXG4gICAgICAgICAgICAgICAgY29uc3QgaXNfZmlyc3Rfcm91bmQgPSBtYXRjaF9pZCA8ICh0b3VybmFtZW50LmNvbXBldGl0b3JzLmxlbmd0aCAvIDIpO1xyXG5cclxuICAgICAgICAgICAgICAgIGlmICh0b3VybmFtZW50Lm1hdGNoZXNbbWF0Y2hfaWRdICYmICgodG91cm5hbWVudC5tYXRjaGVzW21hdGNoX2lkXS5vbmVfaWQgIT09IG51bGwpIHx8ICh0b3VybmFtZW50Lm1hdGNoZXNbbWF0Y2hfaWRdLnR3b19pZCAhPT0gbnVsbCkpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPGRpdiBjbGFzcz1cImRyb3Bkb3duXCI+YDtcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8c3BhbiBjbGFzcz1cIm1vcmUtZGV0YWlscyBkYXNoaWNvbnMgZGFzaGljb25zLWFkbWluLWdlbmVyaWNcIj48L3NwYW4+YDtcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwiZHJvcGRvd24tY29udGVudFwiID5gO1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh0b3VybmFtZW50Lm1hdGNoZXNbbWF0Y2hfaWRdICYmIHRvdXJuYW1lbnQubWF0Y2hlc1ttYXRjaF9pZF0ub25lX2lkICE9PSBudWxsKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnN0IG9uZV9pZCA9IHRvdXJuYW1lbnQubWF0Y2hlc1ttYXRjaF9pZF0ub25lX2lkO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8YSBocmVmPVwiI1wiIGNsYXNzPVwiYWR2YW5jZS1jb21wZXRpdG9yXCIgZGF0YS10b3VybmFtZW50LWlkPVwiJHt0b3VybmFtZW50X2lkfVwiIGRhdGEtbWF0Y2gtaWQ9XCIke21hdGNoX2lkfVwiIGRhdGEtY29tcGV0aXRvci1pZD1cIiR7b25lX2lkfVwiPiR7b3B0aW9ucy5sYW5ndWFnZS5hZHZhbmNlLnJlcGxhY2UoJ3tOQU1FfScsIHRvdXJuYW1lbnQuY29tcGV0aXRvcnNbb25lX2lkXS5uYW1lKX08L2E+YDtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHRvdXJuYW1lbnQubWF0Y2hlc1ttYXRjaF9pZF0gJiYgdG91cm5hbWVudC5tYXRjaGVzW21hdGNoX2lkXS50d29faWQgIT09IG51bGwpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgY29uc3QgdHdvX2lkID0gdG91cm5hbWVudC5tYXRjaGVzW21hdGNoX2lkXS50d29faWQ7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxhIGhyZWY9XCIjXCIgY2xhc3M9XCJhZHZhbmNlLWNvbXBldGl0b3JcIiBkYXRhLXRvdXJuYW1lbnQtaWQ9XCIke3RvdXJuYW1lbnRfaWR9XCIgZGF0YS1tYXRjaC1pZD1cIiR7bWF0Y2hfaWR9XCIgZGF0YS1jb21wZXRpdG9yLWlkPVwiJHt0d29faWR9XCI+JHtvcHRpb25zLmxhbmd1YWdlLmFkdmFuY2UucmVwbGFjZSgne05BTUV9JywgdG91cm5hbWVudC5jb21wZXRpdG9yc1t0d29faWRdLm5hbWUpfTwvYT5gO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICBpZiAoICFpc19maXJzdF9yb3VuZCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8YSBocmVmPVwiI1wiIGNsYXNzPVwiY2xlYXItY29tcGV0aXRvcnNcIiBkYXRhLXRvdXJuYW1lbnQtaWQ9XCIke3RvdXJuYW1lbnRfaWR9XCIgZGF0YS1tYXRjaC1pZD1cIiR7bWF0Y2hfaWR9XCI+JHtvcHRpb25zLmxhbmd1YWdlLmNsZWFyfTwvYT5gO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPC9kaXY+YDtcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8L2Rpdj5gO1xyXG4gICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgIHJldHVybiBjb250ZW50O1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBmdW5jdGlvbiByZW5kZXJNYXRjaCh0b3VybmFtZW50LCB0b3VybmFtZW50X2lkLCBtYXRjaF9pZCwgZmxvdywgY2FuX2VkaXRfbWF0Y2hlcykge1xyXG4gICAgICAgICAgICAgICAgbGV0IGNvbnRlbnQgPSBgYDtcclxuICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxkaXYgY2xhc3M9XCJzaW1wbGUtdG91cm5hbWVudC1icmFja2V0cy1tYXRjaFwiPmA7XHJcbiAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwiaG9yaXpvbnRhbC1saW5lXCI+PC9kaXY+YDtcclxuICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxkaXYgY2xhc3M9XCJzaW1wbGUtdG91cm5hbWVudC1icmFja2V0cy1tYXRjaC1ib2R5XCI+YDtcclxuXHJcbiAgICAgICAgICAgICAgICBpZiAodG91cm5hbWVudC5tYXRjaGVzW21hdGNoX2lkXSAmJiB0b3VybmFtZW50Lm1hdGNoZXNbbWF0Y2hfaWRdLm9uZV9pZCAhPT0gbnVsbCkge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnN0IG9uZV9pZCA9IHRvdXJuYW1lbnQubWF0Y2hlc1ttYXRjaF9pZF0ub25lX2lkO1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnN0IG9uZV9uYW1lID0gdG91cm5hbWVudC5jb21wZXRpdG9yc1tvbmVfaWRdID8gdG91cm5hbWVudC5jb21wZXRpdG9yc1tvbmVfaWRdLm5hbWUgOiAnJm5ic3A7JztcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8c3BhbiBjbGFzcz1cInNpbXBsZS10b3VybmFtZW50LWJyYWNrZXRzLWNvbXBldGl0b3IgY29tcGV0aXRvci0ke29uZV9pZH1cIiBkYXRhLWNvbXBldGl0b3ItaWQ9XCIke29uZV9pZH1cIj4ke29uZV9uYW1lfTwvc3Bhbj5gO1xyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8c3BhbiBjbGFzcz1cInNpbXBsZS10b3VybmFtZW50LWJyYWNrZXRzLWNvbXBldGl0b3JcIj4mbmJzcDs8L3NwYW4+YDtcclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICBpZiAodG91cm5hbWVudC5tYXRjaGVzW21hdGNoX2lkXSAmJiB0b3VybmFtZW50Lm1hdGNoZXNbbWF0Y2hfaWRdLnR3b19pZCAhPT0gbnVsbCkge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnN0IHR3b19pZCA9IHRvdXJuYW1lbnQubWF0Y2hlc1ttYXRjaF9pZF0gPyB0b3VybmFtZW50Lm1hdGNoZXNbbWF0Y2hfaWRdLnR3b19pZCA6IG51bGw7XHJcbiAgICAgICAgICAgICAgICAgICAgY29uc3QgdHdvX25hbWUgPSB0b3VybmFtZW50Lm1hdGNoZXNbbWF0Y2hfaWRdID8gdG91cm5hbWVudC5jb21wZXRpdG9yc1t0d29faWRdLm5hbWUgOiAnJm5ic3A7JztcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8c3BhbiBjbGFzcz1cInNpbXBsZS10b3VybmFtZW50LWJyYWNrZXRzLWNvbXBldGl0b3IgY29tcGV0aXRvci0ke3R3b19pZH1cIiBkYXRhLWNvbXBldGl0b3ItaWQ9XCIke3R3b19pZH1cIj4ke3R3b19uYW1lfTwvc3Bhbj5gO1xyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8c3BhbiBjbGFzcz1cInNpbXBsZS10b3VybmFtZW50LWJyYWNrZXRzLWNvbXBldGl0b3JcIj4mbmJzcDs8L3NwYW4+YDtcclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8L2Rpdj5gO1xyXG5cclxuICAgICAgICAgICAgICAgIGlmIChmbG93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKDEgPT09IG1hdGNoX2lkICUgMikge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwiYm90dG9tLWhhbGZcIj5gO1xyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxkaXYgY2xhc3M9XCJ0b3AtaGFsZlwiPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgICAgICBpZiAoY2FuX2VkaXRfbWF0Y2hlcykge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IHJlbmRlckRyb3BEb3duKHRvdXJuYW1lbnQsIHRvdXJuYW1lbnRfaWQsIG1hdGNoX2lkKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8L2Rpdj5gO1xyXG5cclxuICAgICAgICAgICAgICAgIHJldHVybiBjb250ZW50O1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBmdW5jdGlvbiBmaWx0ZXJSb3VuZHMob3JpZ1JvdW5kcywgbnVtYmVyT2ZSb3VuZHMpIHtcclxuICAgICAgICAgICAgICAgIGNvbnN0IHJvdW5kcyA9IFsuLi5vcmlnUm91bmRzXTtcclxuXHJcbiAgICAgICAgICAgICAgICBpZiAoIDcgPj0gbnVtYmVyT2ZSb3VuZHMgKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcm91bmRzWzRdID0gbnVsbDtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGlmICggNiA+PSBudW1iZXJPZlJvdW5kcyApIHtcclxuICAgICAgICAgICAgICAgICAgICByb3VuZHNbM10gPSBudWxsO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgaWYgKCA1ID49IG51bWJlck9mUm91bmRzICkge1xyXG4gICAgICAgICAgICAgICAgICAgIHJvdW5kc1s1XSA9IG51bGw7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBpZiAoIDQgPj0gbnVtYmVyT2ZSb3VuZHMgKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgcm91bmRzWzJdID0gbnVsbDtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGlmICggMyA+PSBudW1iZXJPZlJvdW5kcyApIHtcclxuICAgICAgICAgICAgICAgICAgICByb3VuZHNbNl0gPSBudWxsO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgaWYgKCAyID49IG51bWJlck9mUm91bmRzICkge1xyXG4gICAgICAgICAgICAgICAgICAgIHJvdW5kc1sxXSA9IG51bGw7XHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIHJvdW5kcy5maWx0ZXIoKHIpID0+IHIgIT09IG51bGwpO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBmdW5jdGlvbiByZW5kZXJCcmFja2V0cyh0b3VybmFtZW50LCBjb250YWluZXIsIHRvdXJuYW1lbnRfaWQpIHtcclxuICAgICAgICAgICAgICAgIGxldCBjb250ZW50ID0gYGA7XHJcbiAgICAgICAgICAgICAgICBsZXQgbnVtYmVyT2ZHYW1lcztcclxuICAgICAgICAgICAgICAgIGxldCBtYXRjaFBhZGRpbmdDb3VudDtcclxuICAgICAgICAgICAgICAgIGxldCByb3VuZHMgPSBmaWx0ZXJSb3VuZHMob3B0aW9ucy5sYW5ndWFnZS5yb3VuZHMsIHRvdXJuYW1lbnQucm91bmRzKTtcclxuXHJcbiAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwic2ltcGxlLXRvdXJuYW1lbnQtYnJhY2tldHMtcm91bmQtaGVhZGVyLWNvbnRhaW5lclwiPmA7XHJcbiAgICAgICAgICAgICAgICBmb3IgKGxldCBpID0gMDsgaSA8PSB0b3VybmFtZW50LnJvdW5kczsgaSsrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPHNwYW4gY2xhc3M9XCJzaW1wbGUtdG91cm5hbWVudC1icmFja2V0cy1yb3VuZC1oZWFkZXJcIj4ke3JvdW5kc1tpXX08L3NwYW4+YDtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICBjb250ZW50ICs9IHJlbmRlclByb2dyZXNzKGNhbGN1bGF0ZVByb2dyZXNzKHRvdXJuYW1lbnQpKTtcclxuXHJcbiAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwic2ltcGxlLXRvdXJuYW1lbnQtYnJhY2tldHMtcm91bmQtYm9keS1jb250YWluZXJcIj5gO1xyXG4gICAgICAgICAgICAgICAgbGV0IHNwb3QgPSAxO1xyXG4gICAgICAgICAgICAgICAgbGV0IHN1bU9mR2FtZXMgPSAwO1xyXG4gICAgICAgICAgICAgICAgZm9yIChsZXQgcm91bmQgPSAxOyByb3VuZCA8PSB0b3VybmFtZW50LnJvdW5kczsgcm91bmQrKykge1xyXG4gICAgICAgICAgICAgICAgICAgIG51bWJlck9mR2FtZXMgPSBNYXRoLmNlaWwodG91cm5hbWVudC5jb21wZXRpdG9ycy5sZW5ndGggLyAoTWF0aC5wb3coMiwgcm91bmQpKSk7XHJcbiAgICAgICAgICAgICAgICAgICAgbWF0Y2hQYWRkaW5nQ291bnQgPSBNYXRoLnBvdygyLCByb3VuZCkgLSAxO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwic2ltcGxlLXRvdXJuYW1lbnQtYnJhY2tldHMtcm91bmQtYm9keVwiPmA7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIGZvciAoc3BvdDsgc3BvdCA8PSAobnVtYmVyT2ZHYW1lcyArIHN1bU9mR2FtZXMpOyBzcG90KyspIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgZm9yIChsZXQgcGFkZGluZyA9IDA7IHBhZGRpbmcgPCBtYXRjaFBhZGRpbmdDb3VudDsgcGFkZGluZysrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoMSA9PT0gc3BvdCAlIDIpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwibWF0Y2gtaGFsZlwiPiZuYnNwOzwvZGl2PiBgO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwidmVydGljYWwtbGluZVwiPiZuYnNwOzwvZGl2PiBgO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gcmVuZGVyTWF0Y2godG91cm5hbWVudCwgdG91cm5hbWVudF9pZCwgc3BvdCAtIDEsIHJvdW5kICE9PSB0b3VybmFtZW50LnJvdW5kcywgb3B0aW9ucy5jYW5fZWRpdF9tYXRjaGVzKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgZm9yIChsZXQgcGFkZGluZyA9IDA7IHBhZGRpbmcgPCBtYXRjaFBhZGRpbmdDb3VudDsgcGFkZGluZysrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoKHJvdW5kICE9PSB0b3VybmFtZW50LnJvdW5kcykgJiYgKDEgPT09IHNwb3QgJSAyKSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxkaXYgY2xhc3M9XCJ2ZXJ0aWNhbC1saW5lXCI+Jm5ic3A7PC9kaXY+IGA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxkaXYgY2xhc3M9XCJtYXRjaC1oYWxmXCI+Jm5ic3A7PC9kaXY+IGA7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPC9kaXY+YDtcclxuICAgICAgICAgICAgICAgICAgICBzdW1PZkdhbWVzICs9IG51bWJlck9mR2FtZXM7XHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgLy8gRGlzcGxheSB0aGUgbGFzdCB3aW5uZXIncyBzcG90LlxyXG4gICAgICAgICAgICAgICAgY29udGVudCArPSBgPGRpdiBjbGFzcz1cInNpbXBsZS10b3VybmFtZW50LWJyYWNrZXRzLXJvdW5kLWJvZHlcIj5gO1xyXG4gICAgICAgICAgICAgICAgZm9yIChsZXQgcGFkZGluZyA9IDA7IHBhZGRpbmcgPCBtYXRjaFBhZGRpbmdDb3VudDsgcGFkZGluZysrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPGRpdiBjbGFzcz1cIm1hdGNoLWhhbGZcIj4mbmJzcDs8L2Rpdj4gYDtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxkaXYgY2xhc3M9XCJzaW1wbGUtdG91cm5hbWVudC1icmFja2V0cy1tYXRjaFwiPmA7XHJcbiAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwid2lubmVycy1saW5lXCI+YDtcclxuICAgICAgICAgICAgICAgIGlmIChvcHRpb25zLmNhbl9lZGl0X21hdGNoZXMpIHtcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IHJlbmRlckRyb3BEb3duKHRvdXJuYW1lbnQsIHRvdXJuYW1lbnRfaWQsIHNwb3QgLSAyKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwic2ltcGxlLXRvdXJuYW1lbnQtYnJhY2tldHMtbWF0Y2gtYm9keVwiPmA7XHJcbiAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8c3BhbiBjbGFzcz1cInNpbXBsZS10b3VybmFtZW50LWJyYWNrZXRzLWNvbXBldGl0b3JcIj48c3Ryb25nPiR7b3B0aW9ucy5sYW5ndWFnZS53aW5uZXJ9PC9zdHJvbmc+PC9zcGFuPmA7XHJcbiAgICAgICAgICAgICAgICBpZiAodG91cm5hbWVudC5tYXRjaGVzW3Nwb3QgLSAxXSAmJiB0b3VybmFtZW50Lm1hdGNoZXNbc3BvdCAtIDFdLm9uZV9pZCAhPT0gbnVsbCkge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnN0IHdpbm5lcl9pZCA9IHRvdXJuYW1lbnQubWF0Y2hlc1tzcG90IC0gMV0ub25lX2lkO1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxzcGFuIGNsYXNzPVwic2ltcGxlLXRvdXJuYW1lbnQtYnJhY2tldHMtY29tcGV0aXRvciBjb21wZXRpdG9yLSR7d2lubmVyX2lkfVwiIGRhdGEtY29tcGV0aXRvci1pZD1cIiR7d2lubmVyX2lkfVwiPiR7dG91cm5hbWVudC5jb21wZXRpdG9yc1t3aW5uZXJfaWRdLm5hbWV9PC9zcGFuPmA7XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxzcGFuIGNsYXNzPVwic2ltcGxlLXRvdXJuYW1lbnQtYnJhY2tldHMtY29tcGV0aXRvclwiPiZuYnNwOzwvc3Bhbj5gO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgY29udGVudCArPSBgPC9kaXY+YDtcclxuICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICBmb3IgKGxldCBwYWRkaW5nID0gMDsgcGFkZGluZyA8IG1hdGNoUGFkZGluZ0NvdW50OyBwYWRkaW5nKyspIHtcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwibWF0Y2gtaGFsZlwiPiZuYnNwOzwvZGl2PiBgO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgY29udGVudCArPSBgPC9kaXY+YDtcclxuICAgICAgICAgICAgICAgIC8vIEVuZCBvZiBkaXNwbGF5IGxhc3Qgd2lubmVyJ3Mgc3BvdC5cclxuXHJcbiAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8L2Rpdj5gO1xyXG5cclxuICAgICAgICAgICAgICAgIGNvbnRhaW5lci5pbm5lckhUTUwgPSBjb250ZW50O1xyXG5cclxuICAgICAgICAgICAgICAgIEFycmF5LmZyb20oZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgnc2ltcGxlLXRvdXJuYW1lbnQtYnJhY2tldHMtY29tcGV0aXRvcicpKVxyXG4gICAgICAgICAgICAgICAgICAgIC5mb3JFYWNoKFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAoaXRlbSkgPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaXRlbS5hZGRFdmVudExpc3RlbmVyKCdtb3VzZW92ZXInLCBjb21wZXRpdG9yTW91c2VPdmVyKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGl0ZW0uYWRkRXZlbnRMaXN0ZW5lcignbW91c2VsZWF2ZScsIGNvbXBldGl0b3JNb3VzZUxlYXZlKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgICk7XHJcblxyXG4gICAgICAgICAgICAgICAgQXJyYXkuZnJvbShkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCdhZHZhbmNlLWNvbXBldGl0b3InKSlcclxuICAgICAgICAgICAgICAgICAgICAuZm9yRWFjaChcclxuICAgICAgICAgICAgICAgICAgICAgICAgKGl0ZW0pID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGl0ZW0uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoZSkgPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBhZHZhbmNlKGUudGFyZ2V0LmRhdGFzZXQudG91cm5hbWVudElkLCBlLnRhcmdldC5kYXRhc2V0Lm1hdGNoSWQsIGUudGFyZ2V0LmRhdGFzZXQuY29tcGV0aXRvcklkKVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAudGhlbigoKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBsb2NhdGlvbi5yZWxvYWQoKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgICk7XHJcblxyXG4gICAgICAgICAgICAgICAgQXJyYXkuZnJvbShkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCdjbGVhci1jb21wZXRpdG9ycycpKVxyXG4gICAgICAgICAgICAgICAgICAgIC5mb3JFYWNoKFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAoaXRlbSkgPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaXRlbS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIChlKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNsZWFyKGUudGFyZ2V0LmRhdGFzZXQudG91cm5hbWVudElkLCBlLnRhcmdldC5kYXRhc2V0Lm1hdGNoSWQpXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC50aGVuKCgpID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxvY2F0aW9uLnJlbG9hZCgpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgKTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgQXJyYXkuZnJvbShkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCdzaW1wbGUtdG91cm5hbWVudC1icmFja2V0cycpKVxyXG4gICAgICAgICAgICAgICAgLmZvckVhY2goXHJcbiAgICAgICAgICAgICAgICAgICAgKGl0ZW0pID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgZ2V0X2NvbXBldGl0b3JzKGl0ZW0uZGF0YXNldC50b3VybmFtZW50SWQpXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAudGhlbigocmVzcG9uc2UpID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICByZW5kZXJCcmFja2V0cyhyZXNwb25zZS5zdGJfbWF0Y2hfZGF0YSwgaXRlbSwgaXRlbS5kYXRhc2V0LnRvdXJuYW1lbnRJZCk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICApO1xyXG5cclxuICAgICAgICB9LFxyXG4gICAgICAgIGZhbHNlXHJcbiAgICApO1xyXG59KSgpO1xyXG4iXSwic291cmNlUm9vdCI6IiJ9