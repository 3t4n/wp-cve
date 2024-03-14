
	"use strict";

	SetImagePath( cs_pgn4web.imagepath );
	SetImageType("png");
	SetHighlightOption(true); // true or false
	//SetGameSelectorOptions("Select a KK game...", false, 0, 8, 0, 0, 0, 0, 10); // (head, num, chEvent, chSite, chRound, chWhite, chBlack, chResult, chDate);
	SetGameSelectorOptions(null, false, 0, 0, 0, 15, 15, 0, 10); // (head, num, chEvent, chSite, chRound, chWhite, chBlack, chResult, chDate);
	SetCommentsIntoMoveText(true);
	SetCommentsOnSeparateLines(false);
	SetAutoplayDelay(1500); // milliseconds
	SetAutostartAutoplay(false);
	SetAutoplayNextGame(false); // if set, move to the next game at the end of the current game during autoplay
	SetInitialGame(1); // number of game to be shown at load, from 1 (default); values (keep the quotes) of "first", "last", "random" are accepted; other string values assumed as PGN search string
	SetInitialVariation(0); // number for the variation to be shown at load, 0 (default) for main variation
	SetInitialHalfmove(0,false); // halfmove number to be shown at load, 0 (default) for start position; values (keep the quotes) of "start", "end", "random", "comment" (go to first comment or variation), "variation" (go to the first variation) are also accepted. Second parameter if true applies the setting to every selected game instead of startup only
	SetShortcutKeysEnabled(true);

	//SetLiveBroadcast(0, false, false, false, false); // set live broadcast; parameters are delay (refresh delay in minutes, 0 means no broadcast, default 0) alertFlag (if true, displays debug error messages, default false) demoFlag (if true starts broadcast demo mode, default false) stepFlag (if true, autoplays updates in steps, default false) endlessFlag (if true, keeps polling for new moves even after all games are finished)




/*
 * Localized text strings for buttons.
 *
 * @since 1.0.0
 */
function cs_pgn4web_strings( counter ) {

	counter++;

	if ( jQuery( 'div#GameButtons form' ).length > 0 ) {
		//console.log( counter + ' We have content...' );
		jQuery( 'input#startButton' ).attr( 'title', cs_pgn4web.startButton ); // go to game start
		jQuery( 'input#backButton' ).attr( 'title', cs_pgn4web.backButton ); // move backward
		jQuery( 'input#autoplayButton' ).attr( 'title', cs_pgn4web.autoplayButton ); // toggle autoplay (start)
		jQuery( 'input#forwardButton' ).attr( 'title', cs_pgn4web.forwardButton ); // move forward
		jQuery( 'input#endButton' ).attr( 'title', cs_pgn4web.endButton ); // go to game end
	} else {
		// Ensures it's checked at least 15 times
		if ( counter < 15 ) {
			//console.log( counter + ' not available yet: ' );
			setTimeout(
				function() {
					cs_pgn4web_strings( counter )
				}, 200 );
		}
	}
}



jQuery(document).ready(function() {

	/*
	* Initial call for localized strings.
	*/
	cs_pgn4web_strings( 0 );


	/*
	* View of single game.
	* Set focus to move inside gametext.
	*
	* @since 1.2.5
	*/
	enableAutoScrollToCurrentMove("GameText");

});
