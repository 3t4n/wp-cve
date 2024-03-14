/*
 *  pgn4web javascript chessboard
 *  copyright (C) 2009-2022 Paolo Casaschi
 *  see README file and http://pgn4web.casaschi.net
 *  for credits, license and more details
 */

//
// example of external javascript library enhancing pgn4web:
// lookpup player info on the FIDE website based on FIDE id or name
//

"use strict";

function openFidePlayerUrl(name, FideId) {
  var windowName = "pgn4web_FIDE_rating";
  if (FideId) { window.open("https://ratings.fide.com/profile/" + escape(FideId), windowName); }
  else if (name) { window.open("https://ratings.fide.com/advaction.phtml?radio=rating&line=desc&name=" + escape(name), windowName); }
}

function customShortcutKey_Shift_1() {
  openFidePlayerUrl(gameWhite[currentGame], customPgnHeaderTag('WhiteFideId'));
}

function customShortcutKey_Shift_2() {
  openFidePlayerUrl(gameBlack[currentGame], customPgnHeaderTag('BlackFideId'));
}

