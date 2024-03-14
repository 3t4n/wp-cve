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
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/brackets.js":
/*!****************************!*\
  !*** ./src/js/brackets.js ***!
  \****************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"]; if (_i == null) return; var _arr = []; var _n = true; var _d = false; var _s, _e; try { for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

/**
 * Handles rendering the content for tournament brackets.
 *
 * @link       https://www.tournamatch.com
 * @since      4.0.0
 *
 * @package    Tournamatch
 *
 */
(function () {
  'use strict';

  var options = trn_brackets_options;

  function get_competitors(tournament_id) {
    return fetch("".concat(options.site_url, "/wp-json/tournamatch/v1/tournament-competitors/?tournament_id=").concat(tournament_id, "&_embed"), {
      headers: {
        "Content-Type": "application/json; charset=utf-8"
      }
    }).then(function (response) {
      return response.json();
    });
  }

  function get_matches(tournament_id) {
    return fetch("".concat(options.site_url, "/wp-json/tournamatch/v1/matches/?competition_type=tournaments&competition_id=").concat(tournament_id, "&_embed"), {
      headers: {
        "Content-Type": "application/json; charset=utf-8"
      }
    }).then(function (response) {
      return response.json();
    });
  }

  function clear(tournament_id, match_id) {
    return fetch("".concat(options.site_url, "/wp-json/tournamatch/v1/matches/clear"), {
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
    return fetch("".concat(options.site_url, "/wp-json/tournamatch/v1/matches/advance"), {
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
      var className = "trn-brackets-competitor-".concat(event.target.dataset.competitorId);
      Array.from(document.getElementsByClassName(className)).forEach(function (item) {
        item.classList.add('trn-brackets-competitor-highlight');
      });
    }

    function competitorMouseLeave(event) {
      var className = "trn-brackets-competitor-".concat(event.target.dataset.competitorId);
      Array.from(document.getElementsByClassName(className)).forEach(function (item) {
        item.classList.remove('trn-brackets-competitor-highlight');
      });
    }

    function calculateProgress(tournament) {
      var totalGames = tournament.size - 1;
      var finishedGames = 0;

      for (var i = 1; i <= tournament.size - 1; i++) {
        if (tournament.matches[i]) {
          if (tournament.matches[i].match_status === 'confirmed') finishedGames++;
        }
      }

      return finishedGames / totalGames;
    }

    function renderProgress(_float) {
      return "<div class=\"trn-brackets-progress\" style=\"width: ".concat(100 * _float, "%;\">&nbsp;</div> ");
    }

    function renderDropDown(tournament, tournament_id, spot_id) {
      var content = "";
      var is_first_round = spot_id < tournament.size / 2;

      if (tournament.matches[spot_id] && (tournament.matches[spot_id].one_competitor_id !== null || tournament.matches[spot_id].two_competitor_id !== null)) {
        var match_id = tournament.matches[spot_id].match_id;
        content += "<div class=\"trn-brackets-dropdown\">";
        content += "<span class=\"trn-brackets-more-details dashicons dashicons-admin-generic\"></span>";
        content += "<div class=\"trn-brackets-dropdown-content\" >";

        if (tournament.matches[spot_id] && tournament.matches[spot_id].one_competitor_id !== null && tournament.matches[spot_id].one_competitor_id !== 0) {
          var one_id = tournament.matches[spot_id].one_competitor_id;
          var advance_url = options.advance_url.replace('{ID}', match_id).replace('{WINNER_ID}', one_id).replace('{NONCE}', options.advance_nonce);
          var replace_url = options.replace_url.replace('{TOURNAMENT_ID}', tournament_id).replace('{MATCH_ID}', match_id).replace('{COMPETITOR_ID}', one_id).replace('{NONCE}', options.replace_nonce);
          content += "<a href=\"".concat(advance_url, "\" class=\"advance-competitor\" data-tournament-id=\"").concat(tournament_id, "\" data-match-id=\"").concat(spot_id, "\" data-competitor-id=\"").concat(one_id, "\">").concat(options.language.advance.replace('{NAME}', tournament.competitors[one_id].name), "</a>");
          content += "<a href=\"".concat(replace_url, "\" class=\"replace-competitor\" data-tournament-id=\"").concat(tournament_id, "\" data-match-id=\"").concat(spot_id, "\" data-competitor-id=\"").concat(one_id, "\">").concat(options.language.replace.replace('{NAME}', tournament.competitors[one_id].name), "</a>");
        }

        if (tournament.matches[spot_id] && tournament.matches[spot_id].two_competitor_id !== null && tournament.matches[spot_id].two_competitor_id !== 0) {
          var two_id = tournament.matches[spot_id].two_competitor_id;

          var _advance_url = options.advance_url.replace('{ID}', match_id).replace('{WINNER_ID}', two_id).replace('{NONCE}', options.advance_nonce);

          var _replace_url = options.replace_url.replace('{TOURNAMENT_ID}', tournament_id).replace('{MATCH_ID}', match_id).replace('{COMPETITOR_ID}', two_id).replace('{NONCE}', options.replace_nonce);

          content += "<a href=\"".concat(_advance_url, "\" class=\"advance-competitor\" data-tournament-id=\"").concat(tournament_id, "\" data-match-id=\"").concat(spot_id, "\" data-competitor-id=\"").concat(two_id, "\">").concat(options.language.advance.replace('{NAME}', tournament.competitors[two_id].name), "</a>");
          content += "<a href=\"".concat(_replace_url, "\" class=\"replace-competitor\" data-tournament-id=\"").concat(tournament_id, "\" data-match-id=\"").concat(spot_id, "\" data-competitor-id=\"").concat(two_id, "\">").concat(options.language.replace.replace('{NAME}', tournament.competitors[two_id].name), "</a>");
        }

        if (!is_first_round) {
          var clear_url = options.clear_url.replace('{ID}', match_id).replace('{NONCE}', options.clear_nonce);
          content += "<a href=\"".concat(clear_url, "\" class=\"clear-competitors\" data-tournament-id=\"").concat(tournament_id, "\" data-match-id=\"").concat(spot_id, "\">").concat(options.language.clear, "</a>");
        }

        content += "</div>";
        content += "</div>";
      }

      return content;
    }

    function renderMatch(tournament, tournament_id, match_id, flow, can_edit_matches) {
      var undecided = options.undecided && options.undecided.length > 0 ? options.undecided : '&nbsp;';
      var content = "";
      content += "<div class=\"trn-brackets-match\">";
      content += "<div class=\"trn-brackets-horizontal-line\"></div>";
      content += "<div class=\"trn-brackets-match-body\">";

      if (tournament.matches[match_id] && tournament.matches[match_id].one_competitor_id !== null && tournament.matches[match_id].one_competitor_id !== 0) {
        var one_id = tournament.matches[match_id].one_competitor_id;
        var one_name = tournament.competitors[one_id] ? tournament.competitors[one_id].name : '&nbsp;';
        var competitor_url_prefix = 'players' === tournament.matches[match_id].one_competitor_type ? options.routes.players : options.routes.teams;
        var one_url = tournament.competitors[one_id] ? "".concat(options.site_url, "/").concat(competitor_url_prefix, "/").concat(one_id) : "#";
        content += "<span id=\"trn_spot_".concat(match_id, "_one\" class=\"trn-brackets-competitor trn-brackets-competitor-").concat(one_id, "\" data-competitor-id=\"").concat(one_id, "\"><a href=\"").concat(one_url, "\">").concat(one_name, "</a></span>");
      } else {
        content += "<span id=\"trn_spot_".concat(match_id, "_one\" class=\"trn-brackets-competitor\">").concat(undecided, "</span>");
      }

      if (tournament.matches[match_id] && tournament.matches[match_id].two_competitor_id !== null && tournament.matches[match_id].two_competitor_id !== 0) {
        var two_id = tournament.matches[match_id].two_competitor_id;
        var two_name = tournament.competitors[two_id] ? tournament.competitors[two_id].name : '&nbsp;';

        var _competitor_url_prefix = 'players' === tournament.matches[match_id].two_competitor_type ? options.routes.players : options.routes.teams;

        var two_url = tournament.competitors[two_id] ? "".concat(options.site_url, "/").concat(_competitor_url_prefix, "/").concat(two_id) : "#";
        content += "<span id=\"trn_spot_".concat(match_id, "_two\" class=\"trn-brackets-competitor trn-brackets-competitor-").concat(two_id, "\" data-competitor-id=\"").concat(two_id, "\"><a href=\"").concat(two_url, "\">").concat(two_name, "</a></span>");
      } else {
        content += "<span id=\"trn_spot_".concat(match_id, "_two\" class=\"trn-brackets-competitor\">").concat(undecided, "</span>");
      }

      content += "</div>";

      if (flow) {
        if (0 === match_id % 2) {
          content += "<div class=\"trn-brackets-bottom-half\">";
        } else {
          content += "<div class=\"trn-brackets-top-half\">";
        }

        if (can_edit_matches) {
          content += renderDropDown(tournament, tournament_id, match_id);
        }

        content += "</div>";
      }

      content += "</div>";
      return content;
    }

    function renderBrackets(tournament, container, tournament_id) {
      var content = "";
      var numberOfGames;
      var matchPaddingCount;
      container.dataset.trnTotalRounds = tournament.rounds;
      content += "<div class=\"trn-brackets-round-header-container\">";

      for (var i = 0; i <= tournament.rounds; i++) {
        content += "<span class=\"trn-brackets-round-header\">".concat(options.language.rounds[i], "</span>");
      }

      content += "</div>";
      content += renderProgress(calculateProgress(tournament));
      content += "<div class=\"trn-brackets-round-body-container\">";
      var spot = 1;
      var sumOfGames = 0;

      for (var round = 1; round <= tournament.rounds; round++) {
        numberOfGames = Math.ceil(tournament.size / Math.pow(2, round));
        matchPaddingCount = Math.pow(2, round) - 1;
        content += "<div class=\"trn-brackets-round-body\">";

        for (spot; spot <= numberOfGames + sumOfGames; spot++) {
          for (var padding = 0; padding < matchPaddingCount; padding++) {
            if (1 === spot % 2) {
              content += "<div class=\"trn-brackets-match-half\">&nbsp;</div> ";
            } else {
              content += "<div class=\"trn-brackets-vertical-line\">&nbsp;</div> ";
            }
          }

          content += renderMatch(tournament, tournament_id, spot, round !== tournament.rounds, options.can_edit_matches);

          for (var _padding = 0; _padding < matchPaddingCount; _padding++) {
            if (round !== tournament.rounds && 1 === spot % 2) {
              content += "<div class=\"trn-brackets-vertical-line\">&nbsp;</div> ";
            } else {
              content += "<div class=\"trn-brackets-match-half\">&nbsp;</div> ";
            }
          }
        }

        content += "</div>";
        sumOfGames += numberOfGames;
      } // Display the last winner's spot.


      content += "<div class=\"trn-brackets-round-body\">";

      for (var _padding2 = 0; _padding2 < matchPaddingCount; _padding2++) {
        content += "<div class=\"trn-brackets-match-half\">&nbsp;</div> ";
      }

      content += "<div class=\"trn-brackets-match\">";
      content += "<div class=\"trn-brackets-winners-line\">";

      if (options.can_edit_matches) {
        content += renderDropDown(tournament, tournament_id, spot - 1);
      }

      content += "</div>";
      content += "<div class=\"trn-brackets-match-body\">";
      content += "<span class=\"trn-brackets-competitor\"><strong>".concat(options.language.winner, "</strong></span>");

      if (tournament.matches[spot - 1] && tournament.matches[spot - 1].match_status === 'confirmed') {
        //if (tournament.matches[spot] && tournament.matches[spot].one_competitor_id !== null) {
        var winner_id = tournament.matches[spot - 1].one_result === 'won' ? tournament.matches[spot - 1].one_competitor_id : tournament.matches[spot - 1].two_competitor_id;
        content += "<span class=\"trn-brackets-competitor competitor-".concat(winner_id, "\" data-competitor-id=\"").concat(winner_id, "\">").concat(tournament.competitors[winner_id].name, "</span>");
      } else {
        content += "<span class=\"trn-brackets-competitor\">&nbsp;</span>";
      }

      content += "</div>";
      content += "</div>";

      for (var _padding3 = 0; _padding3 < matchPaddingCount; _padding3++) {
        content += "<div class=\"trn-brackets-match-half\">&nbsp;</div> ";
      }

      content += "</div>"; // End of display last winner's spot.

      content += "</div>";
      container.innerHTML = content;
      Array.from(document.getElementsByClassName('trn-brackets-competitor')).forEach(function (item) {
        item.addEventListener('mouseover', competitorMouseOver);
        item.addEventListener('mouseleave', competitorMouseLeave);
      }); // Array.from(document.getElementsByClassName('advance-competitor'))
      //     .forEach(
      //         (item) => {
      //             item.addEventListener('click', (e) => {
      //                 e.preventDefault();
      //                 advance(e.target.dataset.tournamentId, e.target.dataset.matchId, e.target.dataset.competitorId)
      //                     .then(() => {
      //                         location.reload();
      //                     });
      //             });
      //         }
      //     );
      //
      // Array.from(document.getElementsByClassName('clear-competitors'))
      //     .forEach(
      //         (item) => {
      //             item.addEventListener('click', (e) => {
      //                 e.preventDefault();
      //                 clear(e.target.dataset.tournamentId, e.target.dataset.matchId)
      //                     .then(() => {
      //                         location.reload();
      //                     });
      //             });
      //         }
      //     );
    }

    Array.from(document.getElementsByClassName('trn-brackets')).forEach(function (item) {
      var tournamentId = item.dataset.tournamentId;
      var tournamentSize = item.dataset.tournamentSize;
      Promise.all([get_matches(tournamentId), get_competitors(tournamentId)]).then(function (_ref) {
        var _ref2 = _slicedToArray(_ref, 2),
            matches = _ref2[0],
            competitors = _ref2[1];

        var rounds = Math.round(Math.log(tournamentSize) / Math.log(2));
        console.log(competitors);
        competitors = competitors.reduce(function (competitors, competitor) {
          return competitor.name = competitor._embedded.competitor[0].name, competitors[competitor.competitor_id] = competitor, competitors;
        }, {});
        console.log(competitors);
        console.log(matches);
        matches = matches.reduce(function (matches, match) {
          return matches[match.spot] = match, matches;
        }, {});
        console.log(matches);
        var tournament = {
          matches: matches,
          competitors: competitors,
          rounds: rounds,
          size: tournamentSize
        };
        console.log(tournament);
        renderBrackets(tournament, item, tournamentId);
      });
    });
  }, false);
})();

/***/ }),

/***/ 1:
/*!**********************************!*\
  !*** multi ./src/js/brackets.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp\www\wordpress.dev\wp-content\plugins\tournamatch\src\js\brackets.js */"./src/js/brackets.js");


/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2pzL2JyYWNrZXRzLmpzIl0sIm5hbWVzIjpbIm9wdGlvbnMiLCJ0cm5fYnJhY2tldHNfb3B0aW9ucyIsImdldF9jb21wZXRpdG9ycyIsInRvdXJuYW1lbnRfaWQiLCJmZXRjaCIsInNpdGVfdXJsIiwiaGVhZGVycyIsInRoZW4iLCJyZXNwb25zZSIsImpzb24iLCJnZXRfbWF0Y2hlcyIsImNsZWFyIiwibWF0Y2hfaWQiLCJyZXN0X25vbmNlIiwibWV0aG9kIiwiYm9keSIsIkpTT04iLCJzdHJpbmdpZnkiLCJpZCIsImFkdmFuY2UiLCJ3aW5uZXJfaWQiLCJ3aW5kb3ciLCJhZGRFdmVudExpc3RlbmVyIiwiY29tcGV0aXRvck1vdXNlT3ZlciIsImV2ZW50IiwiY2xhc3NOYW1lIiwidGFyZ2V0IiwiZGF0YXNldCIsImNvbXBldGl0b3JJZCIsIkFycmF5IiwiZnJvbSIsImRvY3VtZW50IiwiZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSIsImZvckVhY2giLCJpdGVtIiwiY2xhc3NMaXN0IiwiYWRkIiwiY29tcGV0aXRvck1vdXNlTGVhdmUiLCJyZW1vdmUiLCJjYWxjdWxhdGVQcm9ncmVzcyIsInRvdXJuYW1lbnQiLCJ0b3RhbEdhbWVzIiwic2l6ZSIsImZpbmlzaGVkR2FtZXMiLCJpIiwibWF0Y2hlcyIsIm1hdGNoX3N0YXR1cyIsInJlbmRlclByb2dyZXNzIiwiZmxvYXQiLCJyZW5kZXJEcm9wRG93biIsInNwb3RfaWQiLCJjb250ZW50IiwiaXNfZmlyc3Rfcm91bmQiLCJvbmVfY29tcGV0aXRvcl9pZCIsInR3b19jb21wZXRpdG9yX2lkIiwib25lX2lkIiwiYWR2YW5jZV91cmwiLCJyZXBsYWNlIiwiYWR2YW5jZV9ub25jZSIsInJlcGxhY2VfdXJsIiwicmVwbGFjZV9ub25jZSIsImxhbmd1YWdlIiwiY29tcGV0aXRvcnMiLCJuYW1lIiwidHdvX2lkIiwiY2xlYXJfdXJsIiwiY2xlYXJfbm9uY2UiLCJyZW5kZXJNYXRjaCIsImZsb3ciLCJjYW5fZWRpdF9tYXRjaGVzIiwidW5kZWNpZGVkIiwibGVuZ3RoIiwib25lX25hbWUiLCJjb21wZXRpdG9yX3VybF9wcmVmaXgiLCJvbmVfY29tcGV0aXRvcl90eXBlIiwicm91dGVzIiwicGxheWVycyIsInRlYW1zIiwib25lX3VybCIsInR3b19uYW1lIiwidHdvX2NvbXBldGl0b3JfdHlwZSIsInR3b191cmwiLCJyZW5kZXJCcmFja2V0cyIsImNvbnRhaW5lciIsIm51bWJlck9mR2FtZXMiLCJtYXRjaFBhZGRpbmdDb3VudCIsInRyblRvdGFsUm91bmRzIiwicm91bmRzIiwic3BvdCIsInN1bU9mR2FtZXMiLCJyb3VuZCIsIk1hdGgiLCJjZWlsIiwicG93IiwicGFkZGluZyIsIndpbm5lciIsIm9uZV9yZXN1bHQiLCJpbm5lckhUTUwiLCJ0b3VybmFtZW50SWQiLCJ0b3VybmFtZW50U2l6ZSIsIlByb21pc2UiLCJhbGwiLCJsb2ciLCJjb25zb2xlIiwicmVkdWNlIiwiY29tcGV0aXRvciIsIl9lbWJlZGRlZCIsImNvbXBldGl0b3JfaWQiLCJtYXRjaCJdLCJtYXBwaW5ncyI6IjtRQUFBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBOzs7UUFHQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTtRQUNBO1FBQ0EsMENBQTBDLGdDQUFnQztRQUMxRTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBLHdEQUF3RCxrQkFBa0I7UUFDMUU7UUFDQSxpREFBaUQsY0FBYztRQUMvRDs7UUFFQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0EseUNBQXlDLGlDQUFpQztRQUMxRSxnSEFBZ0gsbUJBQW1CLEVBQUU7UUFDckk7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7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R0EvUUwsRUFnUkksS0FoUko7QUFrUkgsQ0FwVUQsSSIsImZpbGUiOiJicmFja2V0cy5qcyIsInNvdXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwgeyBlbnVtZXJhYmxlOiB0cnVlLCBnZXQ6IGdldHRlciB9KTtcbiBcdFx0fVxuIFx0fTtcblxuIFx0Ly8gZGVmaW5lIF9fZXNNb2R1bGUgb24gZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yID0gZnVuY3Rpb24oZXhwb3J0cykge1xuIFx0XHRpZih0eXBlb2YgU3ltYm9sICE9PSAndW5kZWZpbmVkJyAmJiBTeW1ib2wudG9TdHJpbmdUYWcpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgU3ltYm9sLnRvU3RyaW5nVGFnLCB7IHZhbHVlOiAnTW9kdWxlJyB9KTtcbiBcdFx0fVxuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgJ19fZXNNb2R1bGUnLCB7IHZhbHVlOiB0cnVlIH0pO1xuIFx0fTtcblxuIFx0Ly8gY3JlYXRlIGEgZmFrZSBuYW1lc3BhY2Ugb2JqZWN0XG4gXHQvLyBtb2RlICYgMTogdmFsdWUgaXMgYSBtb2R1bGUgaWQsIHJlcXVpcmUgaXRcbiBcdC8vIG1vZGUgJiAyOiBtZXJnZSBhbGwgcHJvcGVydGllcyBvZiB2YWx1ZSBpbnRvIHRoZSBuc1xuIFx0Ly8gbW9kZSAmIDQ6IHJldHVybiB2YWx1ZSB3aGVuIGFscmVhZHkgbnMgb2JqZWN0XG4gXHQvLyBtb2RlICYgOHwxOiBiZWhhdmUgbGlrZSByZXF1aXJlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnQgPSBmdW5jdGlvbih2YWx1ZSwgbW9kZSkge1xuIFx0XHRpZihtb2RlICYgMSkgdmFsdWUgPSBfX3dlYnBhY2tfcmVxdWlyZV9fKHZhbHVlKTtcbiBcdFx0aWYobW9kZSAmIDgpIHJldHVybiB2YWx1ZTtcbiBcdFx0aWYoKG1vZGUgJiA0KSAmJiB0eXBlb2YgdmFsdWUgPT09ICdvYmplY3QnICYmIHZhbHVlICYmIHZhbHVlLl9fZXNNb2R1bGUpIHJldHVybiB2YWx1ZTtcbiBcdFx0dmFyIG5zID0gT2JqZWN0LmNyZWF0ZShudWxsKTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5yKG5zKTtcbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KG5zLCAnZGVmYXVsdCcsIHsgZW51bWVyYWJsZTogdHJ1ZSwgdmFsdWU6IHZhbHVlIH0pO1xuIFx0XHRpZihtb2RlICYgMiAmJiB0eXBlb2YgdmFsdWUgIT0gJ3N0cmluZycpIGZvcih2YXIga2V5IGluIHZhbHVlKSBfX3dlYnBhY2tfcmVxdWlyZV9fLmQobnMsIGtleSwgZnVuY3Rpb24oa2V5KSB7IHJldHVybiB2YWx1ZVtrZXldOyB9LmJpbmQobnVsbCwga2V5KSk7XG4gXHRcdHJldHVybiBucztcbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiXCI7XG5cblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSAxKTtcbiIsIi8qKlxyXG4gKiBIYW5kbGVzIHJlbmRlcmluZyB0aGUgY29udGVudCBmb3IgdG91cm5hbWVudCBicmFja2V0cy5cclxuICpcclxuICogQGxpbmsgICAgICAgaHR0cHM6Ly93d3cudG91cm5hbWF0Y2guY29tXHJcbiAqIEBzaW5jZSAgICAgIDQuMC4wXHJcbiAqXHJcbiAqIEBwYWNrYWdlICAgIFRvdXJuYW1hdGNoXHJcbiAqXHJcbiAqL1xyXG4oZnVuY3Rpb24gKCkge1xyXG4gICAgJ3VzZSBzdHJpY3QnO1xyXG5cclxuICAgIGNvbnN0IG9wdGlvbnMgPSB0cm5fYnJhY2tldHNfb3B0aW9ucztcclxuXHJcbiAgICBmdW5jdGlvbiBnZXRfY29tcGV0aXRvcnModG91cm5hbWVudF9pZCkge1xyXG4gICAgICAgIHJldHVybiBmZXRjaChgJHtvcHRpb25zLnNpdGVfdXJsfS93cC1qc29uL3RvdXJuYW1hdGNoL3YxL3RvdXJuYW1lbnQtY29tcGV0aXRvcnMvP3RvdXJuYW1lbnRfaWQ9JHt0b3VybmFtZW50X2lkfSZfZW1iZWRgLCB7XHJcbiAgICAgICAgICAgIGhlYWRlcnM6IHtcIkNvbnRlbnQtVHlwZVwiOiBcImFwcGxpY2F0aW9uL2pzb247IGNoYXJzZXQ9dXRmLThcIn0sXHJcbiAgICAgICAgfSlcclxuICAgICAgICAgICAgLnRoZW4ocmVzcG9uc2UgPT4gcmVzcG9uc2UuanNvbigpKTtcclxuICAgIH1cclxuXHJcbiAgICBmdW5jdGlvbiBnZXRfbWF0Y2hlcyh0b3VybmFtZW50X2lkKSB7XHJcbiAgICAgICAgcmV0dXJuIGZldGNoKGAke29wdGlvbnMuc2l0ZV91cmx9L3dwLWpzb24vdG91cm5hbWF0Y2gvdjEvbWF0Y2hlcy8/Y29tcGV0aXRpb25fdHlwZT10b3VybmFtZW50cyZjb21wZXRpdGlvbl9pZD0ke3RvdXJuYW1lbnRfaWR9Jl9lbWJlZGAsIHtcclxuICAgICAgICAgICAgaGVhZGVyczoge1wiQ29udGVudC1UeXBlXCI6IFwiYXBwbGljYXRpb24vanNvbjsgY2hhcnNldD11dGYtOFwifSxcclxuICAgICAgICB9KVxyXG4gICAgICAgICAgICAudGhlbihyZXNwb25zZSA9PiByZXNwb25zZS5qc29uKCkpO1xyXG4gICAgfVxyXG5cclxuICAgIGZ1bmN0aW9uIGNsZWFyKHRvdXJuYW1lbnRfaWQsIG1hdGNoX2lkKSB7XHJcbiAgICAgICAgcmV0dXJuIGZldGNoKGAke29wdGlvbnMuc2l0ZV91cmx9L3dwLWpzb24vdG91cm5hbWF0Y2gvdjEvbWF0Y2hlcy9jbGVhcmAsIHtcclxuICAgICAgICAgICAgaGVhZGVyczoge1xyXG4gICAgICAgICAgICAgICAgXCJDb250ZW50LVR5cGVcIjogXCJhcHBsaWNhdGlvbi9qc29uOyBjaGFyc2V0PXV0Zi04XCIsXHJcbiAgICAgICAgICAgICAgICBcIlgtV1AtTm9uY2VcIjogb3B0aW9ucy5yZXN0X25vbmNlLFxyXG4gICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICBtZXRob2Q6ICdQT1NUJyxcclxuICAgICAgICAgICAgYm9keTogSlNPTi5zdHJpbmdpZnkoe1xyXG4gICAgICAgICAgICAgICAgaWQ6IG1hdGNoX2lkLFxyXG4gICAgICAgICAgICAgICAgdG91cm5hbWVudF9pZDogdG91cm5hbWVudF9pZCxcclxuICAgICAgICAgICAgfSlcclxuICAgICAgICB9KVxyXG4gICAgICAgICAgICAudGhlbihyZXNwb25zZSA9PiByZXNwb25zZS5qc29uKCkpO1xyXG4gICAgfVxyXG5cclxuICAgIGZ1bmN0aW9uIGFkdmFuY2UodG91cm5hbWVudF9pZCwgbWF0Y2hfaWQsIHdpbm5lcl9pZCkge1xyXG4gICAgICAgIHJldHVybiBmZXRjaChgJHtvcHRpb25zLnNpdGVfdXJsfS93cC1qc29uL3RvdXJuYW1hdGNoL3YxL21hdGNoZXMvYWR2YW5jZWAsIHtcclxuICAgICAgICAgICAgaGVhZGVyczoge1xyXG4gICAgICAgICAgICAgICAgXCJDb250ZW50LVR5cGVcIjogXCJhcHBsaWNhdGlvbi9qc29uOyBjaGFyc2V0PXV0Zi04XCIsXHJcbiAgICAgICAgICAgICAgICBcIlgtV1AtTm9uY2VcIjogb3B0aW9ucy5yZXN0X25vbmNlLFxyXG4gICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICBtZXRob2Q6ICdQT1NUJyxcclxuICAgICAgICAgICAgYm9keTogSlNPTi5zdHJpbmdpZnkoe1xyXG4gICAgICAgICAgICAgICAgaWQ6IG1hdGNoX2lkLFxyXG4gICAgICAgICAgICAgICAgdG91cm5hbWVudF9pZDogdG91cm5hbWVudF9pZCxcclxuICAgICAgICAgICAgICAgIHdpbm5lcl9pZDogd2lubmVyX2lkLFxyXG4gICAgICAgICAgICB9KVxyXG4gICAgICAgIH0pXHJcbiAgICAgICAgICAgIC50aGVuKHJlc3BvbnNlID0+IHJlc3BvbnNlLmpzb24oKSk7XHJcbiAgICB9XHJcblxyXG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoXHJcbiAgICAgICAgJ2xvYWQnLFxyXG4gICAgICAgIGZ1bmN0aW9uICgpIHtcclxuXHJcbiAgICAgICAgICAgIGZ1bmN0aW9uIGNvbXBldGl0b3JNb3VzZU92ZXIoZXZlbnQpIHtcclxuICAgICAgICAgICAgICAgIGNvbnN0IGNsYXNzTmFtZSA9IGB0cm4tYnJhY2tldHMtY29tcGV0aXRvci0ke2V2ZW50LnRhcmdldC5kYXRhc2V0LmNvbXBldGl0b3JJZH1gO1xyXG4gICAgICAgICAgICAgICAgQXJyYXkuZnJvbShkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKGNsYXNzTmFtZSkpXHJcbiAgICAgICAgICAgICAgICAgICAgLmZvckVhY2goXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGl0ZW0gPT4ge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaXRlbS5jbGFzc0xpc3QuYWRkKCd0cm4tYnJhY2tldHMtY29tcGV0aXRvci1oaWdobGlnaHQnKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgICk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGZ1bmN0aW9uIGNvbXBldGl0b3JNb3VzZUxlYXZlKGV2ZW50KSB7XHJcbiAgICAgICAgICAgICAgICBjb25zdCBjbGFzc05hbWUgPSBgdHJuLWJyYWNrZXRzLWNvbXBldGl0b3ItJHtldmVudC50YXJnZXQuZGF0YXNldC5jb21wZXRpdG9ySWR9YDtcclxuICAgICAgICAgICAgICAgIEFycmF5LmZyb20oZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShjbGFzc05hbWUpKVxyXG4gICAgICAgICAgICAgICAgICAgIC5mb3JFYWNoKFxyXG4gICAgICAgICAgICAgICAgICAgICAgICBpdGVtID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGl0ZW0uY2xhc3NMaXN0LnJlbW92ZSgndHJuLWJyYWNrZXRzLWNvbXBldGl0b3ItaGlnaGxpZ2h0Jyk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICApO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBmdW5jdGlvbiBjYWxjdWxhdGVQcm9ncmVzcyh0b3VybmFtZW50KSB7XHJcbiAgICAgICAgICAgICAgICBjb25zdCB0b3RhbEdhbWVzID0gdG91cm5hbWVudC5zaXplIC0gMTtcclxuICAgICAgICAgICAgICAgIGxldCBmaW5pc2hlZEdhbWVzID0gMDtcclxuXHJcbiAgICAgICAgICAgICAgICBmb3IgKGxldCBpID0gMTsgaSA8PSB0b3VybmFtZW50LnNpemUgLSAxOyBpKyspIHtcclxuICAgICAgICAgICAgICAgICAgICBpZiAodG91cm5hbWVudC5tYXRjaGVzW2ldKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmICh0b3VybmFtZW50Lm1hdGNoZXNbaV0ubWF0Y2hfc3RhdHVzID09PSAnY29uZmlybWVkJykgZmluaXNoZWRHYW1lcysrO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIHJldHVybiAoZmluaXNoZWRHYW1lcyAvIHRvdGFsR2FtZXMpO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBmdW5jdGlvbiByZW5kZXJQcm9ncmVzcyhmbG9hdCkge1xyXG4gICAgICAgICAgICAgICAgcmV0dXJuIGA8ZGl2IGNsYXNzPVwidHJuLWJyYWNrZXRzLXByb2dyZXNzXCIgc3R5bGU9XCJ3aWR0aDogJHsxMDAgKiBmbG9hdH0lO1wiPiZuYnNwOzwvZGl2PiBgO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBmdW5jdGlvbiByZW5kZXJEcm9wRG93bih0b3VybmFtZW50LCB0b3VybmFtZW50X2lkLCBzcG90X2lkKSB7XHJcbiAgICAgICAgICAgICAgICBsZXQgY29udGVudCA9IGBgO1xyXG4gICAgICAgICAgICAgICAgY29uc3QgaXNfZmlyc3Rfcm91bmQgPSBzcG90X2lkIDwgKHRvdXJuYW1lbnQuc2l6ZSAvIDIpO1xyXG5cclxuICAgICAgICAgICAgICAgIGlmICh0b3VybmFtZW50Lm1hdGNoZXNbc3BvdF9pZF0gJiYgKCh0b3VybmFtZW50Lm1hdGNoZXNbc3BvdF9pZF0ub25lX2NvbXBldGl0b3JfaWQgIT09IG51bGwpIHx8ICh0b3VybmFtZW50Lm1hdGNoZXNbc3BvdF9pZF0udHdvX2NvbXBldGl0b3JfaWQgIT09IG51bGwpKSkge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnN0IG1hdGNoX2lkID0gdG91cm5hbWVudC5tYXRjaGVzW3Nwb3RfaWRdLm1hdGNoX2lkO1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxkaXYgY2xhc3M9XCJ0cm4tYnJhY2tldHMtZHJvcGRvd25cIj5gO1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxzcGFuIGNsYXNzPVwidHJuLWJyYWNrZXRzLW1vcmUtZGV0YWlscyBkYXNoaWNvbnMgZGFzaGljb25zLWFkbWluLWdlbmVyaWNcIj48L3NwYW4+YDtcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwidHJuLWJyYWNrZXRzLWRyb3Bkb3duLWNvbnRlbnRcIiA+YDtcclxuICAgICAgICAgICAgICAgICAgICBpZiAodG91cm5hbWVudC5tYXRjaGVzW3Nwb3RfaWRdICYmIHRvdXJuYW1lbnQubWF0Y2hlc1tzcG90X2lkXS5vbmVfY29tcGV0aXRvcl9pZCAhPT0gbnVsbCAmJiB0b3VybmFtZW50Lm1hdGNoZXNbc3BvdF9pZF0ub25lX2NvbXBldGl0b3JfaWQgIT09IDApIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgY29uc3Qgb25lX2lkID0gdG91cm5hbWVudC5tYXRjaGVzW3Nwb3RfaWRdLm9uZV9jb21wZXRpdG9yX2lkO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb25zdCBhZHZhbmNlX3VybCA9IG9wdGlvbnMuYWR2YW5jZV91cmwucmVwbGFjZSgne0lEfScsIG1hdGNoX2lkKS5yZXBsYWNlKCd7V0lOTkVSX0lEfScsIG9uZV9pZCkucmVwbGFjZSgne05PTkNFfScsIG9wdGlvbnMuYWR2YW5jZV9ub25jZSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnN0IHJlcGxhY2VfdXJsID0gb3B0aW9ucy5yZXBsYWNlX3VybC5yZXBsYWNlKCd7VE9VUk5BTUVOVF9JRH0nLCB0b3VybmFtZW50X2lkKS5yZXBsYWNlKCd7TUFUQ0hfSUR9JywgbWF0Y2hfaWQpLnJlcGxhY2UoJ3tDT01QRVRJVE9SX0lEfScsIG9uZV9pZCkucmVwbGFjZSgne05PTkNFfScsIG9wdGlvbnMucmVwbGFjZV9ub25jZSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxhIGhyZWY9XCIke2FkdmFuY2VfdXJsfVwiIGNsYXNzPVwiYWR2YW5jZS1jb21wZXRpdG9yXCIgZGF0YS10b3VybmFtZW50LWlkPVwiJHt0b3VybmFtZW50X2lkfVwiIGRhdGEtbWF0Y2gtaWQ9XCIke3Nwb3RfaWR9XCIgZGF0YS1jb21wZXRpdG9yLWlkPVwiJHtvbmVfaWR9XCI+JHtvcHRpb25zLmxhbmd1YWdlLmFkdmFuY2UucmVwbGFjZSgne05BTUV9JywgdG91cm5hbWVudC5jb21wZXRpdG9yc1tvbmVfaWRdLm5hbWUpfTwvYT5gO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8YSBocmVmPVwiJHtyZXBsYWNlX3VybH1cIiBjbGFzcz1cInJlcGxhY2UtY29tcGV0aXRvclwiIGRhdGEtdG91cm5hbWVudC1pZD1cIiR7dG91cm5hbWVudF9pZH1cIiBkYXRhLW1hdGNoLWlkPVwiJHtzcG90X2lkfVwiIGRhdGEtY29tcGV0aXRvci1pZD1cIiR7b25lX2lkfVwiPiR7b3B0aW9ucy5sYW5ndWFnZS5yZXBsYWNlLnJlcGxhY2UoJ3tOQU1FfScsIHRvdXJuYW1lbnQuY29tcGV0aXRvcnNbb25lX2lkXS5uYW1lKX08L2E+YDtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKHRvdXJuYW1lbnQubWF0Y2hlc1tzcG90X2lkXSAmJiB0b3VybmFtZW50Lm1hdGNoZXNbc3BvdF9pZF0udHdvX2NvbXBldGl0b3JfaWQgIT09IG51bGwgJiYgdG91cm5hbWVudC5tYXRjaGVzW3Nwb3RfaWRdLnR3b19jb21wZXRpdG9yX2lkICE9PSAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnN0IHR3b19pZCA9IHRvdXJuYW1lbnQubWF0Y2hlc1tzcG90X2lkXS50d29fY29tcGV0aXRvcl9pZDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgY29uc3QgYWR2YW5jZV91cmwgPSBvcHRpb25zLmFkdmFuY2VfdXJsLnJlcGxhY2UoJ3tJRH0nLCBtYXRjaF9pZCkucmVwbGFjZSgne1dJTk5FUl9JRH0nLCB0d29faWQpLnJlcGxhY2UoJ3tOT05DRX0nLCBvcHRpb25zLmFkdmFuY2Vfbm9uY2UpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb25zdCByZXBsYWNlX3VybCA9IG9wdGlvbnMucmVwbGFjZV91cmwucmVwbGFjZSgne1RPVVJOQU1FTlRfSUR9JywgdG91cm5hbWVudF9pZCkucmVwbGFjZSgne01BVENIX0lEfScsIG1hdGNoX2lkKS5yZXBsYWNlKCd7Q09NUEVUSVRPUl9JRH0nLCB0d29faWQpLnJlcGxhY2UoJ3tOT05DRX0nLCBvcHRpb25zLnJlcGxhY2Vfbm9uY2UpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8YSBocmVmPVwiJHthZHZhbmNlX3VybH1cIiBjbGFzcz1cImFkdmFuY2UtY29tcGV0aXRvclwiIGRhdGEtdG91cm5hbWVudC1pZD1cIiR7dG91cm5hbWVudF9pZH1cIiBkYXRhLW1hdGNoLWlkPVwiJHtzcG90X2lkfVwiIGRhdGEtY29tcGV0aXRvci1pZD1cIiR7dHdvX2lkfVwiPiR7b3B0aW9ucy5sYW5ndWFnZS5hZHZhbmNlLnJlcGxhY2UoJ3tOQU1FfScsIHRvdXJuYW1lbnQuY29tcGV0aXRvcnNbdHdvX2lkXS5uYW1lKX08L2E+YDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPGEgaHJlZj1cIiR7cmVwbGFjZV91cmx9XCIgY2xhc3M9XCJyZXBsYWNlLWNvbXBldGl0b3JcIiBkYXRhLXRvdXJuYW1lbnQtaWQ9XCIke3RvdXJuYW1lbnRfaWR9XCIgZGF0YS1tYXRjaC1pZD1cIiR7c3BvdF9pZH1cIiBkYXRhLWNvbXBldGl0b3ItaWQ9XCIke3R3b19pZH1cIj4ke29wdGlvbnMubGFuZ3VhZ2UucmVwbGFjZS5yZXBsYWNlKCd7TkFNRX0nLCB0b3VybmFtZW50LmNvbXBldGl0b3JzW3R3b19pZF0ubmFtZSl9PC9hPmA7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIGlmICggIWlzX2ZpcnN0X3JvdW5kKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnN0IGNsZWFyX3VybCA9IG9wdGlvbnMuY2xlYXJfdXJsLnJlcGxhY2UoJ3tJRH0nLCBtYXRjaF9pZCkucmVwbGFjZSgne05PTkNFfScsIG9wdGlvbnMuY2xlYXJfbm9uY2UpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8YSBocmVmPVwiJHtjbGVhcl91cmx9XCIgY2xhc3M9XCJjbGVhci1jb21wZXRpdG9yc1wiIGRhdGEtdG91cm5hbWVudC1pZD1cIiR7dG91cm5hbWVudF9pZH1cIiBkYXRhLW1hdGNoLWlkPVwiJHtzcG90X2lkfVwiPiR7b3B0aW9ucy5sYW5ndWFnZS5jbGVhcn08L2E+YDtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPC9kaXY+YDtcclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICByZXR1cm4gY29udGVudDtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgZnVuY3Rpb24gcmVuZGVyTWF0Y2godG91cm5hbWVudCwgdG91cm5hbWVudF9pZCwgbWF0Y2hfaWQsIGZsb3csIGNhbl9lZGl0X21hdGNoZXMpIHtcclxuICAgICAgICAgICAgICAgIGNvbnN0IHVuZGVjaWRlZCA9IChvcHRpb25zLnVuZGVjaWRlZCAmJiBvcHRpb25zLnVuZGVjaWRlZC5sZW5ndGggPiAwKSA/IG9wdGlvbnMudW5kZWNpZGVkIDogJyZuYnNwOyc7XHJcbiAgICAgICAgICAgICAgICBsZXQgY29udGVudCA9IGBgO1xyXG4gICAgICAgICAgICAgICAgY29udGVudCArPSBgPGRpdiBjbGFzcz1cInRybi1icmFja2V0cy1tYXRjaFwiPmA7XHJcbiAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwidHJuLWJyYWNrZXRzLWhvcml6b250YWwtbGluZVwiPjwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwidHJuLWJyYWNrZXRzLW1hdGNoLWJvZHlcIj5gO1xyXG5cclxuXHJcblxyXG4gICAgICAgICAgICAgICAgaWYgKHRvdXJuYW1lbnQubWF0Y2hlc1ttYXRjaF9pZF0gJiYgdG91cm5hbWVudC5tYXRjaGVzW21hdGNoX2lkXS5vbmVfY29tcGV0aXRvcl9pZCAhPT0gbnVsbCAmJiB0b3VybmFtZW50Lm1hdGNoZXNbbWF0Y2hfaWRdLm9uZV9jb21wZXRpdG9yX2lkICE9PSAwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY29uc3Qgb25lX2lkID0gdG91cm5hbWVudC5tYXRjaGVzW21hdGNoX2lkXS5vbmVfY29tcGV0aXRvcl9pZDtcclxuICAgICAgICAgICAgICAgICAgICBjb25zdCBvbmVfbmFtZSA9IHRvdXJuYW1lbnQuY29tcGV0aXRvcnNbb25lX2lkXSA/IHRvdXJuYW1lbnQuY29tcGV0aXRvcnNbb25lX2lkXS5uYW1lIDogJyZuYnNwOyc7XHJcbiAgICAgICAgICAgICAgICAgICAgY29uc3QgY29tcGV0aXRvcl91cmxfcHJlZml4ID0gJ3BsYXllcnMnID09PSB0b3VybmFtZW50Lm1hdGNoZXNbbWF0Y2hfaWRdLm9uZV9jb21wZXRpdG9yX3R5cGUgPyBvcHRpb25zLnJvdXRlcy5wbGF5ZXJzIDogb3B0aW9ucy5yb3V0ZXMudGVhbXM7XHJcbiAgICAgICAgICAgICAgICAgICAgY29uc3Qgb25lX3VybCA9IHRvdXJuYW1lbnQuY29tcGV0aXRvcnNbb25lX2lkXSA/IGAke29wdGlvbnMuc2l0ZV91cmx9LyR7Y29tcGV0aXRvcl91cmxfcHJlZml4fS8ke29uZV9pZH1gIDogXCIjXCI7XHJcbiAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPHNwYW4gaWQ9XCJ0cm5fc3BvdF8ke21hdGNoX2lkfV9vbmVcIiBjbGFzcz1cInRybi1icmFja2V0cy1jb21wZXRpdG9yIHRybi1icmFja2V0cy1jb21wZXRpdG9yLSR7b25lX2lkfVwiIGRhdGEtY29tcGV0aXRvci1pZD1cIiR7b25lX2lkfVwiPjxhIGhyZWY9XCIke29uZV91cmx9XCI+JHtvbmVfbmFtZX08L2E+PC9zcGFuPmA7XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxzcGFuIGlkPVwidHJuX3Nwb3RfJHttYXRjaF9pZH1fb25lXCIgY2xhc3M9XCJ0cm4tYnJhY2tldHMtY29tcGV0aXRvclwiPiR7dW5kZWNpZGVkfTwvc3Bhbj5gO1xyXG4gICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgIGlmICh0b3VybmFtZW50Lm1hdGNoZXNbbWF0Y2hfaWRdICYmIHRvdXJuYW1lbnQubWF0Y2hlc1ttYXRjaF9pZF0udHdvX2NvbXBldGl0b3JfaWQgIT09IG51bGwgJiYgdG91cm5hbWVudC5tYXRjaGVzW21hdGNoX2lkXS50d29fY29tcGV0aXRvcl9pZCAhPT0gMCkge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnN0IHR3b19pZCA9IHRvdXJuYW1lbnQubWF0Y2hlc1ttYXRjaF9pZF0udHdvX2NvbXBldGl0b3JfaWQ7XHJcbiAgICAgICAgICAgICAgICAgICAgY29uc3QgdHdvX25hbWUgPSB0b3VybmFtZW50LmNvbXBldGl0b3JzW3R3b19pZF0gPyB0b3VybmFtZW50LmNvbXBldGl0b3JzW3R3b19pZF0ubmFtZSA6ICcmbmJzcDsnO1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnN0IGNvbXBldGl0b3JfdXJsX3ByZWZpeCA9ICdwbGF5ZXJzJyA9PT0gdG91cm5hbWVudC5tYXRjaGVzW21hdGNoX2lkXS50d29fY29tcGV0aXRvcl90eXBlID8gb3B0aW9ucy5yb3V0ZXMucGxheWVycyA6IG9wdGlvbnMucm91dGVzLnRlYW1zO1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnN0IHR3b191cmwgPSB0b3VybmFtZW50LmNvbXBldGl0b3JzW3R3b19pZF0gPyBgJHtvcHRpb25zLnNpdGVfdXJsfS8ke2NvbXBldGl0b3JfdXJsX3ByZWZpeH0vJHt0d29faWR9YCA6IFwiI1wiO1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxzcGFuIGlkPVwidHJuX3Nwb3RfJHttYXRjaF9pZH1fdHdvXCIgY2xhc3M9XCJ0cm4tYnJhY2tldHMtY29tcGV0aXRvciB0cm4tYnJhY2tldHMtY29tcGV0aXRvci0ke3R3b19pZH1cIiBkYXRhLWNvbXBldGl0b3ItaWQ9XCIke3R3b19pZH1cIj48YSBocmVmPVwiJHt0d29fdXJsfVwiPiR7dHdvX25hbWV9PC9hPjwvc3Bhbj5gO1xyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8c3BhbiBpZD1cInRybl9zcG90XyR7bWF0Y2hfaWR9X3R3b1wiIGNsYXNzPVwidHJuLWJyYWNrZXRzLWNvbXBldGl0b3JcIj4ke3VuZGVjaWRlZH08L3NwYW4+YDtcclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8L2Rpdj5gO1xyXG5cclxuICAgICAgICAgICAgICAgIGlmIChmbG93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKDAgPT09IG1hdGNoX2lkICUgMikge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwidHJuLWJyYWNrZXRzLWJvdHRvbS1oYWxmXCI+YDtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwidHJuLWJyYWNrZXRzLXRvcC1oYWxmXCI+YDtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIGlmIChjYW5fZWRpdF9tYXRjaGVzKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gcmVuZGVyRHJvcERvd24odG91cm5hbWVudCwgdG91cm5hbWVudF9pZCwgbWF0Y2hfaWQpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPC9kaXY+YDtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDwvZGl2PmA7XHJcblxyXG4gICAgICAgICAgICAgICAgcmV0dXJuIGNvbnRlbnQ7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGZ1bmN0aW9uIHJlbmRlckJyYWNrZXRzKHRvdXJuYW1lbnQsIGNvbnRhaW5lciwgdG91cm5hbWVudF9pZCkge1xyXG4gICAgICAgICAgICAgICAgbGV0IGNvbnRlbnQgPSBgYDtcclxuICAgICAgICAgICAgICAgIGxldCBudW1iZXJPZkdhbWVzO1xyXG4gICAgICAgICAgICAgICAgbGV0IG1hdGNoUGFkZGluZ0NvdW50O1xyXG5cclxuICAgICAgICAgICAgICAgIGNvbnRhaW5lci5kYXRhc2V0LnRyblRvdGFsUm91bmRzID0gdG91cm5hbWVudC5yb3VuZHM7XHJcblxyXG4gICAgICAgICAgICAgICAgY29udGVudCArPSBgPGRpdiBjbGFzcz1cInRybi1icmFja2V0cy1yb3VuZC1oZWFkZXItY29udGFpbmVyXCI+YDtcclxuICAgICAgICAgICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDw9IHRvdXJuYW1lbnQucm91bmRzOyBpKyspIHtcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8c3BhbiBjbGFzcz1cInRybi1icmFja2V0cy1yb3VuZC1oZWFkZXJcIj4ke29wdGlvbnMubGFuZ3VhZ2Uucm91bmRzW2ldfTwvc3Bhbj5gO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgY29udGVudCArPSBgPC9kaXY+YDtcclxuICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gcmVuZGVyUHJvZ3Jlc3MoY2FsY3VsYXRlUHJvZ3Jlc3ModG91cm5hbWVudCkpO1xyXG5cclxuICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxkaXYgY2xhc3M9XCJ0cm4tYnJhY2tldHMtcm91bmQtYm9keS1jb250YWluZXJcIj5gO1xyXG4gICAgICAgICAgICAgICAgbGV0IHNwb3QgPSAxO1xyXG4gICAgICAgICAgICAgICAgbGV0IHN1bU9mR2FtZXMgPSAwO1xyXG4gICAgICAgICAgICAgICAgZm9yIChsZXQgcm91bmQgPSAxOyByb3VuZCA8PSB0b3VybmFtZW50LnJvdW5kczsgcm91bmQrKykge1xyXG4gICAgICAgICAgICAgICAgICAgIG51bWJlck9mR2FtZXMgPSBNYXRoLmNlaWwodG91cm5hbWVudC5zaXplIC8gKE1hdGgucG93KDIsIHJvdW5kKSkpO1xyXG4gICAgICAgICAgICAgICAgICAgIG1hdGNoUGFkZGluZ0NvdW50ID0gTWF0aC5wb3coMiwgcm91bmQpIC0gMTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPGRpdiBjbGFzcz1cInRybi1icmFja2V0cy1yb3VuZC1ib2R5XCI+YDtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgZm9yIChzcG90OyBzcG90IDw9IChudW1iZXJPZkdhbWVzICsgc3VtT2ZHYW1lcyk7IHNwb3QrKykge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBmb3IgKGxldCBwYWRkaW5nID0gMDsgcGFkZGluZyA8IG1hdGNoUGFkZGluZ0NvdW50OyBwYWRkaW5nKyspIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmICgxID09PSBzcG90ICUgMikge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxkaXYgY2xhc3M9XCJ0cm4tYnJhY2tldHMtbWF0Y2gtaGFsZlwiPiZuYnNwOzwvZGl2PiBgO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwidHJuLWJyYWNrZXRzLXZlcnRpY2FsLWxpbmVcIj4mbmJzcDs8L2Rpdj4gYDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IHJlbmRlck1hdGNoKHRvdXJuYW1lbnQsIHRvdXJuYW1lbnRfaWQsIHNwb3QsIHJvdW5kICE9PSB0b3VybmFtZW50LnJvdW5kcywgb3B0aW9ucy5jYW5fZWRpdF9tYXRjaGVzKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgZm9yIChsZXQgcGFkZGluZyA9IDA7IHBhZGRpbmcgPCBtYXRjaFBhZGRpbmdDb3VudDsgcGFkZGluZysrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoKHJvdW5kICE9PSB0b3VybmFtZW50LnJvdW5kcykgJiYgKDEgPT09IHNwb3QgJSAyKSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxkaXYgY2xhc3M9XCJ0cm4tYnJhY2tldHMtdmVydGljYWwtbGluZVwiPiZuYnNwOzwvZGl2PiBgO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwidHJuLWJyYWNrZXRzLW1hdGNoLWhhbGZcIj4mbmJzcDs8L2Rpdj4gYDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8L2Rpdj5gO1xyXG4gICAgICAgICAgICAgICAgICAgIHN1bU9mR2FtZXMgKz0gbnVtYmVyT2ZHYW1lcztcclxuICAgICAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgICAgICAvLyBEaXNwbGF5IHRoZSBsYXN0IHdpbm5lcidzIHNwb3QuXHJcbiAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwidHJuLWJyYWNrZXRzLXJvdW5kLWJvZHlcIj5gO1xyXG4gICAgICAgICAgICAgICAgZm9yIChsZXQgcGFkZGluZyA9IDA7IHBhZGRpbmcgPCBtYXRjaFBhZGRpbmdDb3VudDsgcGFkZGluZysrKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPGRpdiBjbGFzcz1cInRybi1icmFja2V0cy1tYXRjaC1oYWxmXCI+Jm5ic3A7PC9kaXY+IGA7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwidHJuLWJyYWNrZXRzLW1hdGNoXCI+YDtcclxuICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDxkaXYgY2xhc3M9XCJ0cm4tYnJhY2tldHMtd2lubmVycy1saW5lXCI+YDtcclxuICAgICAgICAgICAgICAgIGlmIChvcHRpb25zLmNhbl9lZGl0X21hdGNoZXMpIHtcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IHJlbmRlckRyb3BEb3duKHRvdXJuYW1lbnQsIHRvdXJuYW1lbnRfaWQsIHNwb3QgLSAxKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwidHJuLWJyYWNrZXRzLW1hdGNoLWJvZHlcIj5gO1xyXG4gICAgICAgICAgICAgICAgY29udGVudCArPSBgPHNwYW4gY2xhc3M9XCJ0cm4tYnJhY2tldHMtY29tcGV0aXRvclwiPjxzdHJvbmc+JHtvcHRpb25zLmxhbmd1YWdlLndpbm5lcn08L3N0cm9uZz48L3NwYW4+YDtcclxuICAgICAgICAgICAgICAgIGlmICh0b3VybmFtZW50Lm1hdGNoZXNbc3BvdCAtIDFdICYmIHRvdXJuYW1lbnQubWF0Y2hlc1tzcG90IC0gMV0ubWF0Y2hfc3RhdHVzID09PSAnY29uZmlybWVkJykge1xyXG4gICAgICAgICAgICAgICAgLy9pZiAodG91cm5hbWVudC5tYXRjaGVzW3Nwb3RdICYmIHRvdXJuYW1lbnQubWF0Y2hlc1tzcG90XS5vbmVfY29tcGV0aXRvcl9pZCAhPT0gbnVsbCkge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnN0IHdpbm5lcl9pZCA9IHRvdXJuYW1lbnQubWF0Y2hlc1tzcG90IC0xXS5vbmVfcmVzdWx0ID09PSAnd29uJyA/IHRvdXJuYW1lbnQubWF0Y2hlc1tzcG90IC0xXS5vbmVfY29tcGV0aXRvcl9pZCA6IHRvdXJuYW1lbnQubWF0Y2hlc1tzcG90IC0xXS50d29fY29tcGV0aXRvcl9pZDtcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8c3BhbiBjbGFzcz1cInRybi1icmFja2V0cy1jb21wZXRpdG9yIGNvbXBldGl0b3ItJHt3aW5uZXJfaWR9XCIgZGF0YS1jb21wZXRpdG9yLWlkPVwiJHt3aW5uZXJfaWR9XCI+JHt0b3VybmFtZW50LmNvbXBldGl0b3JzW3dpbm5lcl9pZF0ubmFtZX08L3NwYW4+YDtcclxuICAgICAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY29udGVudCArPSBgPHNwYW4gY2xhc3M9XCJ0cm4tYnJhY2tldHMtY29tcGV0aXRvclwiPiZuYnNwOzwvc3Bhbj5gO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgY29udGVudCArPSBgPC9kaXY+YDtcclxuICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICBmb3IgKGxldCBwYWRkaW5nID0gMDsgcGFkZGluZyA8IG1hdGNoUGFkZGluZ0NvdW50OyBwYWRkaW5nKyspIHtcclxuICAgICAgICAgICAgICAgICAgICBjb250ZW50ICs9IGA8ZGl2IGNsYXNzPVwidHJuLWJyYWNrZXRzLW1hdGNoLWhhbGZcIj4mbmJzcDs8L2Rpdj4gYDtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGNvbnRlbnQgKz0gYDwvZGl2PmA7XHJcbiAgICAgICAgICAgICAgICAvLyBFbmQgb2YgZGlzcGxheSBsYXN0IHdpbm5lcidzIHNwb3QuXHJcblxyXG4gICAgICAgICAgICAgICAgY29udGVudCArPSBgPC9kaXY+YDtcclxuXHJcbiAgICAgICAgICAgICAgICBjb250YWluZXIuaW5uZXJIVE1MID0gY29udGVudDtcclxuXHJcbiAgICAgICAgICAgICAgICBBcnJheS5mcm9tKGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1icmFja2V0cy1jb21wZXRpdG9yJykpXHJcbiAgICAgICAgICAgICAgICAgICAgLmZvckVhY2goXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIChpdGVtKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpdGVtLmFkZEV2ZW50TGlzdGVuZXIoJ21vdXNlb3ZlcicsIGNvbXBldGl0b3JNb3VzZU92ZXIpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaXRlbS5hZGRFdmVudExpc3RlbmVyKCdtb3VzZWxlYXZlJywgY29tcGV0aXRvck1vdXNlTGVhdmUpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvLyBBcnJheS5mcm9tKGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ2FkdmFuY2UtY29tcGV0aXRvcicpKVxyXG4gICAgICAgICAgICAgICAgLy8gICAgIC5mb3JFYWNoKFxyXG4gICAgICAgICAgICAgICAgLy8gICAgICAgICAoaXRlbSkgPT4ge1xyXG4gICAgICAgICAgICAgICAgLy8gICAgICAgICAgICAgaXRlbS5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIChlKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAvLyAgICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICAgICAgLy8gICAgICAgICAgICAgICAgIGFkdmFuY2UoZS50YXJnZXQuZGF0YXNldC50b3VybmFtZW50SWQsIGUudGFyZ2V0LmRhdGFzZXQubWF0Y2hJZCwgZS50YXJnZXQuZGF0YXNldC5jb21wZXRpdG9ySWQpXHJcbiAgICAgICAgICAgICAgICAvLyAgICAgICAgICAgICAgICAgICAgIC50aGVuKCgpID0+IHtcclxuICAgICAgICAgICAgICAgIC8vICAgICAgICAgICAgICAgICAgICAgICAgIGxvY2F0aW9uLnJlbG9hZCgpO1xyXG4gICAgICAgICAgICAgICAgLy8gICAgICAgICAgICAgICAgICAgICB9KTtcclxuICAgICAgICAgICAgICAgIC8vICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgLy8gICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAvLyAgICAgKTtcclxuICAgICAgICAgICAgICAgIC8vXHJcbiAgICAgICAgICAgICAgICAvLyBBcnJheS5mcm9tKGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ2NsZWFyLWNvbXBldGl0b3JzJykpXHJcbiAgICAgICAgICAgICAgICAvLyAgICAgLmZvckVhY2goXHJcbiAgICAgICAgICAgICAgICAvLyAgICAgICAgIChpdGVtKSA9PiB7XHJcbiAgICAgICAgICAgICAgICAvLyAgICAgICAgICAgICBpdGVtLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgKGUpID0+IHtcclxuICAgICAgICAgICAgICAgIC8vICAgICAgICAgICAgICAgICBlLnByZXZlbnREZWZhdWx0KCk7XHJcbiAgICAgICAgICAgICAgICAvLyAgICAgICAgICAgICAgICAgY2xlYXIoZS50YXJnZXQuZGF0YXNldC50b3VybmFtZW50SWQsIGUudGFyZ2V0LmRhdGFzZXQubWF0Y2hJZClcclxuICAgICAgICAgICAgICAgIC8vICAgICAgICAgICAgICAgICAgICAgLnRoZW4oKCkgPT4ge1xyXG4gICAgICAgICAgICAgICAgLy8gICAgICAgICAgICAgICAgICAgICAgICAgbG9jYXRpb24ucmVsb2FkKCk7XHJcbiAgICAgICAgICAgICAgICAvLyAgICAgICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICAgICAgLy8gICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICAgICAvLyAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIC8vICAgICApO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBBcnJheS5mcm9tKGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3Rybi1icmFja2V0cycpKVxyXG4gICAgICAgICAgICAgICAgLmZvckVhY2goXHJcbiAgICAgICAgICAgICAgICAgICAgKGl0ZW0pID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgY29uc3QgdG91cm5hbWVudElkID0gaXRlbS5kYXRhc2V0LnRvdXJuYW1lbnRJZDtcclxuICAgICAgICAgICAgICAgICAgICAgICAgY29uc3QgdG91cm5hbWVudFNpemUgPSBpdGVtLmRhdGFzZXQudG91cm5hbWVudFNpemU7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICBQcm9taXNlLmFsbChbZ2V0X21hdGNoZXModG91cm5hbWVudElkKSwgZ2V0X2NvbXBldGl0b3JzKHRvdXJuYW1lbnRJZCldKVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLnRoZW4oKFttYXRjaGVzLCBjb21wZXRpdG9yc10pID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb25zdCByb3VuZHMgPSBNYXRoLnJvdW5kKE1hdGgubG9nKHRvdXJuYW1lbnRTaXplKSAvIE1hdGgubG9nKDIpKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2coY29tcGV0aXRvcnMpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbXBldGl0b3JzID0gY29tcGV0aXRvcnMucmVkdWNlKChjb21wZXRpdG9ycywgY29tcGV0aXRvcikgPT4gKFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgY29tcGV0aXRvci5uYW1lID0gY29tcGV0aXRvci5fZW1iZWRkZWQuY29tcGV0aXRvclswXS5uYW1lLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgY29tcGV0aXRvcnNbY29tcGV0aXRvci5jb21wZXRpdG9yX2lkXSA9IGNvbXBldGl0b3IsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb21wZXRpdG9yc1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICksIHt9KTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhjb21wZXRpdG9ycyk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKG1hdGNoZXMpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hdGNoZXMgPSBtYXRjaGVzLnJlZHVjZSgobWF0Y2hlcywgbWF0Y2gpID0+IChtYXRjaGVzW21hdGNoLnNwb3RdID0gbWF0Y2gsIG1hdGNoZXMpLCB7fSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgY29uc29sZS5sb2cobWF0Y2hlcyk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnN0IHRvdXJuYW1lbnQgPSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG1hdGNoZXM6IG1hdGNoZXMsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbXBldGl0b3JzOiBjb21wZXRpdG9ycyxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcm91bmRzOiByb3VuZHMsXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNpemU6IHRvdXJuYW1lbnRTaXplLFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKHRvdXJuYW1lbnQpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICByZW5kZXJCcmFja2V0cyh0b3VybmFtZW50LCBpdGVtLCB0b3VybmFtZW50SWQpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgKTtcclxuXHJcbiAgICAgICAgfSxcclxuICAgICAgICBmYWxzZVxyXG4gICAgKTtcclxufSkoKTtcclxuIl0sInNvdXJjZVJvb3QiOiIifQ==