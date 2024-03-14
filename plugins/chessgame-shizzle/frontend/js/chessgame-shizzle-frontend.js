
/*
Copyright 2017 - 2023  Marcel Pol  (marcel@timelord.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


/*
 * JavaScript for Chessgame Shizzle Frontend.
 */



/*
 * View of single game.
 * Toggle headers with more info.
 *
 * @since 1.0.0
 */
jQuery(document).ready(function($) {
	jQuery( "span.cs_chessgame_meta_header" ).on( 'click', function() {

		var display = jQuery( '.cs_chessgame_meta_inside' ).css( 'display' );

		var cs_chessgame_meta = jQuery(this).parent();
		if ( display === 'none' ) {
			jQuery( cs_chessgame_meta ).find('.cs_chessgame_meta_inside').slideDown(500);
		} else if ( display === 'block' ) {
			jQuery( cs_chessgame_meta ).find('.cs_chessgame_meta_inside').slideUp(500);
		}

		return false;
	});
});

jQuery(document).ready(function($) {
	jQuery( "span.cs_chessgame_form_help_header" ).on( 'click', function() {
		jQuery(this).css( 'display', 'none' );

		var cs_chessgame_meta = jQuery(this).parent();
		jQuery( cs_chessgame_meta ).find('div.cs_chessgame_form_help_inside').slideDown(500);

		return false;
	});
});


/*
 * View of single game.
 * Download PGN file from the viewer when clicking a button.
 *
 * @since 1.0.8
 */
jQuery(document).ready(function($) {
	jQuery( '#cs-chessgame-download-pgn' ).on( 'click', function() {
		var pgndata = jQuery('#pgnText_for_export').val().trim();
		jQuery( '#cs-chessgame-download-pgn-link' ).attr( 'href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(pgndata) );
		document.getElementById('cs-chessgame-download-pgn-link').click();
	});
});


/*
 * View of single game.
 * Return the CurrentFEN from an iframe to the main window.
 *
 * @since 1.1.0
 */
window.addEventListener('message', function(event) {
	if (typeof event.data == 'object' && event.data.call == 'cs-sendvalue') {
		// Do something with event.data.value;
		var value = event.data.value;
		if ( value == 'CurrentFEN' ) {
			var fen = CurrentFEN();
			event.source.postMessage({
				call:  'cs-sendvalue',
				value: fen
			}, event.origin );
		}
	}
}, false);


/*
 * View of single game.
 * Set a png image from a FEN position coming from the iframe.
 *
 * @since 1.1.0
 */
jQuery(document).ready(function($) {
	jQuery( 'input#cs-chessgame-fen-image' ).on('click', function( event ) {
		var fen      = CurrentFEN();
		var nonce    = chessgame_shizzle_frontend_script.nonce_fen;
		var ajax_url = chessgame_shizzle_frontend_script.ajax_url;
		var post_id  = chessgame_shizzle_frontend_script.post_id;

		var data = {
			url: ajax_url,
			dataType: 'binary',
			headers: {'Content-Type':'image/png','X-Requested-With':'XMLHttpRequest'},
			processData: false,
			action: 'chessgame_shizzle_fen_image_ajax',
			security: nonce,
			fen: fen,
			post_id: post_id
		};

		jQuery.post( ajax_url, data, function(response) {
			if ( response == 'no FEN code or post ID set.' ) {
				// Error or unexpected answer...

			} else {
				// We got what we wanted
				jQuery( 'img.cs-chessgame-fen-image-png' ).attr( 'src', response );
				jQuery( 'div.cs-chessgame-remove-fen-image' ).css( 'display', 'block' );
			}
		});
		event.preventDefault();
	});
});
/* Remove image again from page. */
jQuery(document).ready(function($) {

	jQuery( 'input#cs-chessgame-remove-fen-image' ).on( 'click', function( event ) {

		jQuery( 'img.cs-chessgame-fen-image-png' ).attr( 'src', '' );
		jQuery( 'div.cs-chessgame-remove-fen-image' ).css( 'display', 'none' );
		event.preventDefault();

	});
});


/*
 * View of single game.
 * Show an input field with the current FEN code.
 *
 * @since 1.2.1
 */
jQuery(document).ready(function($) {

	jQuery( 'input#cs-chessgame-gen-fen' ).on( 'click', function( event ) {

		var fen = CurrentFEN();
		jQuery( 'input#cs-chessgame-show-fen' ).attr( 'value', fen );
		jQuery( 'div.cs-chessgame-show-fen' ).css( 'display', 'block' );
		event.preventDefault();

	});
});


/*
 * View of single puzzle game.
 * Switch from puzzle text to PGN text.
 *
 * @since 1.1.7
 */
jQuery(document).ready(function($) {

	if ( jQuery( '.chessboard-wrapper' ).hasClass('cs-puzzle') ) {
		chessgame_shizzle_set_puzzle_data( 0 );
	}

});
function chessgame_shizzle_set_puzzle_data( counter ) {

	counter++;

	if ( firstStart === false ) { // startup of pgn4web has been done and we can use the CurrentPly() function.

		var white = jQuery( '.cs-puzzle #GamePuzzleTask span.cs-text-white' ).html();
		var black = jQuery( '.cs-puzzle #GamePuzzleTask span.cs-text-black' ).html();
		var title = (CurrentPly % 2 ? black : white);
		jQuery( '.cs-puzzle #GamePuzzleTask a.cs-puzzle-task' ).attr('title', title);

		var html = Math.floor((CurrentPly / 2) + 1) + (CurrentPly % 2 ? "..." : ".") + " ?";
		jQuery( '.cs-puzzle #GamePuzzleTask a.cs-puzzle-task' ).html( html );

		var iconcolor = (CurrentPly % 2 ? 'black' : 'white');
		jQuery( '.cs-puzzle #GamePuzzleTask div.cs-icon-color' ).css( 'background-color', iconcolor );

		jQuery( '.cs-puzzle #GamePuzzleTask a.cs-puzzle-task' ).on( 'click', function( event ) {

			jQuery( '#GamePuzzleTask' ).css( 'display', 'none' );
			jQuery( '#GameText' ).css( 'display', 'block' );

			GoToMove(CurrentPly + 1);

			event.preventDefault();
		});

	} else {

		if ( counter < 15 ) {
			// console.log( 'No startup yet: ' + counter );
			setTimeout(
				function() {
					chessgame_shizzle_set_puzzle_data( counter )
				}, 200 );
			}

	}

}


/*
 * Upload form.
 * Mangle data for the honeypot.
 *
 * @since 1.0.6
 */
jQuery(document).ready(function($) {
	var honeypot  = chessgame_shizzle_frontend_script.honeypot;
	var honeypot2 = chessgame_shizzle_frontend_script.honeypot2;
	var val = jQuery( '#' + honeypot ).val();
	if ( val > 0 ) {
		jQuery( '#' + honeypot2 ).val( val );
		jQuery( '#' + honeypot ).val( '' );
	}
});


/*
 * Upload form.
 * Mangle data for the form timeout.
 *
 * @since 1.0.6
 */
jQuery(document).ready(function($) {
	var timeout  = chessgame_shizzle_frontend_script.timeout;
	var timeout2 = chessgame_shizzle_frontend_script.timeout2;

	var timer  = new Number( jQuery( 'input#' + timeout ).val() );
	var timer2 = new Number( jQuery( 'input#' + timeout2 ).val() );

	var timer  = timer - 1
	var timer2 = timer2 + 1

	jQuery( 'input#' + timeout ).val( timer );
	jQuery( 'input#' + timeout2 ).val( timer2 );
});


/*
 * Upload form.
 * Select a result from a dropdown.
 *
 * @since 1.1.2
 */
jQuery(document).ready(function(){
	jQuery( "select.cs_result_ajax" ).on('change', function ( el ) {
		var result = jQuery( "option:selected", this ).val();

		if ( result !== '' ) {
			jQuery( 'input#cs_result' ).val( result );
		}
	});
});


/*
 * Upload form.
 * Use a datepicker for date.
 *
 * @since 1.1.9
 */
jQuery(document).ready(function(){

	// Only call it on normal enqueue in main page, not in iframe.
	if ( typeof jQuery.fn.datepicker === "function" ) {
		jQuery('input#cs_datetime').datepicker({ // frontend
			dateFormat: "yy.mm.dd"
		});
		jQuery('input#cs_chessgame_datetime').datepicker({ // admin
			dateFormat: "yy.mm.dd"
		});
	}

});


/*
 * Lesson: Event for clicking the buttons, and getting the filter form or search forms visible.
 *
 * @since 1.1.9
 */
jQuery(document).ready(function($) {

	jQuery( "input.cs-lesson-filters-button" ).on( 'click', function() {
		var main_div = jQuery( this ).closest( 'div.cs-lesson-form-container' );
		jQuery("div.cs-lesson-search", main_div).slideUp(300);
		var display = jQuery( 'form.cs-lesson-filters' ).css( 'display' );
		if ( display == 'none' ) {
			jQuery("form.cs-lesson-filters", main_div).slideDown(300);
		} else if ( display == 'block' ) {
			jQuery("form.cs-lesson-filters", main_div).slideUp(300);
		}
		return false;
	});

	jQuery( "input.cs-lesson-search-button" ).on( 'click', function() {
		var main_div = jQuery( this ).closest( 'div.cs-lesson-form-container' );
		jQuery("form.cs-lesson-filters", main_div).slideUp(300);
		var display = jQuery( 'div.cs-lesson-search' ).css( 'display' );
		if ( display == 'none' ) {
			jQuery("div.cs-lesson-search", main_div).slideDown(300);
		} else if ( display == 'block' ) {
			jQuery("div.cs-lesson-search", main_div).slideUp(300);
		}
		return false;
	});

});


/*
 * Lesson with extra buttons and filters.
 * Can be puzzle or regular game.
 *
 * @since 1.1.9
 */
jQuery(document).ready(function($) {

	if ( jQuery( '.cs-chessgame-lesson' ).hasClass('cs-lesson') ) {

		/* Filter form. */
		jQuery( '.cs-lesson-buttons input.cs-next-game' ).on( 'click', function( event ) {

			jQuery( '.cs-lesson-message' ).html('');

			var form = jQuery( 'form.cs-lesson-filters' );
			var ajaxurl = jQuery( 'form.cs-lesson-filters input.cs-lesson-ajaxurl' ).val();
			var defaulterror = jQuery( 'form.cs-lesson-filters input.cs-lesson-defaulterror' ).val();

			// Use an object, arrays are only indexed by integers.
			var cs_lesson_ajax_data = {
				permalink: window.location.href,
				action: 'chessgame_shizzle_lesson_ajax'
			};

			jQuery('form.cs-lesson-filters input').each(function( index, value ) {
				var val = jQuery( this ).prop('value');
				var name = jQuery( this ).attr('name');
				var type = jQuery( this ).attr('type');
				if ( type == 'checkbox' ) {
					var checked = jQuery( this ).prop('checked');
					if ( checked == true ) {
						cs_lesson_ajax_data[name] = 'on'; // Mimick standard $_POST value.
					}
				} else if ( type == 'radio' ) {
					var checked = jQuery( this ).prop('checked');
					if ( checked == true ) {
						cs_lesson_ajax_data[name] = val;
					}
				} else {
					cs_lesson_ajax_data[name] = val;
				}
			});
			jQuery('form.cs-lesson-filters textarea').each(function( index, value ) {
				var val = jQuery( this ).val();
				var name = jQuery( this ).attr('name');
				cs_lesson_ajax_data[name] = val;
			});
			jQuery( 'form.cs-lesson-filters select' ).each(function( index, value ) {
				var val = jQuery( value ).val();
				var name = jQuery( value ).attr('name');
				cs_lesson_ajax_data[name] = val;
			});

			jQuery.post( ajaxurl, cs_lesson_ajax_data, function( response ) {

				if ( chessgame_shizzle_is_json( response ) ) {
					var returndata = JSON.parse( response );

					if ( returndata['cs_post_id'] == 0 ) {
						jQuery( '.cs-lesson-message' ).html('<p class="cs-messages notice">' + returndata['cs_message'] + '</p>');
					}
					if ( returndata['cs_post_id'] > 0 ) {
						jQuery( '.cs-single-lesson-container' ).html( returndata['cs_html'] );

						var timer  = new Number( jQuery( 'input.cs-lesson-offset' ).val() );
						var timer  = timer + 1;
						jQuery( 'input.cs-lesson-offset' ).val( timer );

					}
				} else {
					jQuery( '.cs-lesson-message' ).html('<p class="cs-messages error">' + defaulterror + '</p>');
				}
			});

			event.preventDefault();
		});
		jQuery( 'form.cs-lesson-filters input.cs-lesson-filters-apply' ).on( 'click', function( event ) {

			jQuery( 'input.cs-lesson-offset' ).val( 0 );
			jQuery( '.cs-lesson-buttons input.cs-next-game' ).trigger( 'click' );
			jQuery( "input.cs-lesson-filters-button" ).trigger( 'click' );

			event.preventDefault();
		});


		/* Post ID part of the search form. */
		jQuery( '.cs-lesson-search input.cs-lesson-show-postid' ).on( 'click', function( event ) {

			jQuery( '.cs-lesson-message' ).html('');

			var main_div     = jQuery( this ).closest( 'div.cs-lesson-form-container' );
			var form         = jQuery( 'form.cs-lesson-postid' );
			var ajaxurl      = jQuery( 'form.cs-lesson-postid input.cs-lesson-ajaxurl' ).val();
			var defaulterror = jQuery( 'form.cs-lesson-postid input.cs-lesson-defaulterror' ).val();
			var postiderror  = jQuery( 'form.cs-lesson-postid input.cs-lesson-postiderror' ).val();
			var val          = jQuery( 'input.cs-lesson-search-postid' ).prop('value');

			if ( isNaN( val ) ) {
				jQuery( '.cs-lesson-message' ).html('<p class="cs-messages error">' + postiderror + '</p>');
				event.preventDefault();
				return;
			}

			// Use an object, arrays are only indexed by integers.
			var cs_lesson_ajax_data = {
				permalink: window.location.href,
				action: 'chessgame_shizzle_lesson_ajax_postid'
			};

			jQuery('form.cs-lesson-postid input').each(function( index, value ) {
				var val = jQuery( this ).prop('value');
				var name = jQuery( this ).attr('name');
				var type = jQuery( this ).attr('type');
				cs_lesson_ajax_data[name] = val;
			});

			jQuery.post( ajaxurl, cs_lesson_ajax_data, function( response ) {

				if ( chessgame_shizzle_is_json( response ) ) {
					var returndata = JSON.parse( response );

					if ( returndata['cs_post_id'] == 0 ) {
						jQuery( '.cs-lesson-message' ).html('<p class="cs-messages notice">' + returndata['cs_message'] + '</p>');
					}
					if ( returndata['cs_post_id'] > 0 ) {
						jQuery( '.cs-single-lesson-container' ).html( returndata['cs_html'] );
						jQuery("div.cs-lesson-search", main_div).slideUp(300);
					}
				} else {
					jQuery( '.cs-lesson-message' ).html('<p class="cs-messages error">' + defaulterror + '</p>');
				}
			});

			event.preventDefault();
		});
		jQuery('.cs-lesson-search input.cs-lesson-search-postid').on('keydown', function (event) {
			if ( event.which === 13 ) { // Enter key.
				jQuery( 'input.cs-lesson-show-postid' ).trigger( 'click' );
				event.preventDefault();
			}
		});

		/* Search part of the search form. */
		jQuery( '.cs-lesson-search input.cs-lesson-search-submit' ).on( 'click', function( event ) {

			jQuery( '.cs-lesson-message' ).html('');
			jQuery( 'td.cs-chessgame-search-results' ).html( '' );

			var main_div     = jQuery( this ).closest( 'div.cs-lesson-form-container' );
			var form         = jQuery( 'form.cs-lesson-search' );
			var ajaxurl      = jQuery( 'form.cs-lesson-search input.cs-lesson-ajaxurl' ).val();
			var defaulterror = jQuery( 'form.cs-lesson-search input.cs-lesson-defaulterror' ).val();
			var searcherror  = jQuery( 'form.cs-lesson-search input.cs-lesson-searcherror' ).val();
			var val          = jQuery( 'form.cs-lesson-search input.cs-lesson-search-text' ).prop('value');

			if ( typeof val == 'undefined' || val.length == 0 ) {
				jQuery( '.cs-lesson-message' ).html('<p class="cs-messages error">' + searcherror + '</p>');
				event.preventDefault();
				return;
			}

			// Use an object, arrays are only indexed by integers.
			var cs_lesson_ajax_data = {
				permalink: window.location.href,
				action: 'chessgame_shizzle_lesson_ajax_search'
			};

			jQuery('form.cs-lesson-search input').each(function( index, value ) {
				var val = jQuery( this ).prop('value');
				var name = jQuery( this ).attr('name');
				var type = jQuery( this ).attr('type');
				cs_lesson_ajax_data[name] = val;
			});

			jQuery.post( ajaxurl, cs_lesson_ajax_data, function( response ) {

				if ( chessgame_shizzle_is_json( response ) ) {
					var returndata = JSON.parse( response );

					if ( returndata['cs_post_id'] == 0 ) {
						jQuery( '.cs-lesson-message' ).html('<p class="cs-messages notice">' + returndata['cs_message'] + '</p>');
					}
					if ( returndata['cs_post_id'] > 0 ) {

						jQuery( 'td.cs-chessgame-search-results' ).html( returndata['cs_html'] );

						jQuery( '.cs-lesson-search td.cs-chessgame-search-results tr' ).on( 'click', function( event ) {
							var postid = new Number( jQuery( this ).attr( "data-cs-postid" ) );
							jQuery( 'input.cs-lesson-search-postid' ).val( postid );
							jQuery( "input.cs-lesson-show-postid" ).trigger( 'click' );
							event.preventDefault();
						});

					}
				} else {
					jQuery( '.cs-lesson-message' ).html('<p class="cs-messages error">' + defaulterror + '</p>');
				}
			});

			event.preventDefault();
		});
		jQuery( '.cs-lesson-search input.cs-lesson-search-clear' ).on( 'click', function( event ) {
			jQuery( '.cs-chessgame-search-results' ).html('');
			event.preventDefault();
		});
		jQuery('.cs-lesson-search input.cs-lesson-search-text').on('keydown', function (event) {
			if ( event.which === 13 ) { // Enter key.
				jQuery( '.cs-lesson-search input.cs-lesson-search-submit' ).trigger( 'click' );
				event.preventDefault();
			}
		});

	}

});


/*
 * Upload form.
 * Preview of single game.
 * Can be puzzle or regular game.
 *
 * @since 1.2.1
 */
jQuery(document).ready(function($) {

	jQuery( 'form#cs_new_chessgame input#chessgame_shizzle_preview' ).on( 'click', function( event ) {

		var nonce_field = chessgame_shizzle_frontend_script.nonce;
		var nonce = jQuery( 'input#' + nonce_field ).prop('value');

		var cs_puzzle = '';
		var checked = jQuery('form#cs_new_chessgame input.cs_puzzle').prop('checked');
		if ( checked === true ) {
			cs_puzzle = 'on'; // Mimick standard $_POST value.
		}

		var cs_pgn = jQuery('form#cs_new_chessgame textarea.cs_pgn').val()

		var url = chessgame_shizzle_frontend_script.preview_url;
		var oldbrowser = chessgame_shizzle_frontend_script.oldbrowser;

		if ( typeof URL !== 'function' ) {
			jQuery( 'form#cs_new_chessgame div.cs-preview' ).html( oldbrowser );
			event.preventDefault();
			return;
		}

		var preview_url = new URL( url );
		if ( typeof preview_url.searchParams.append !== 'function' ) {
			jQuery( 'form#cs_new_chessgame div.cs-preview' ).html( oldbrowser );
			event.preventDefault();
			return;
		}

		preview_url.searchParams.append( 'cs_puzzle', cs_puzzle );
		preview_url.searchParams.append( 'cs_nonce', nonce );
		preview_url.searchParams.append( 'cs_pgn', cs_pgn );

		var iframe = '<iframe src="' + preview_url + '" class="cs-iframe cs-iframe-extended cs-iframe-preview" name="cs-iframe-preview" id="cs-iframe-preview"></iframe>';

		jQuery( 'form#cs_new_chessgame div.cs-preview' ).html( iframe );
		event.preventDefault();

	});

});


/*
 * Search in simple list: Event for clicking the button, and getting the search form visible.
 *
 * @since 1.2.6
 */
jQuery(document).ready(function($) {

	jQuery( "input.cs-simple-list-search-button" ).on( 'click', function() {
		//var main_div = jQuery( this ).closest( 'div.cs-lesson-form-container' );
		//jQuery("div.cs-simple-list-search", main_div).slideUp(300);
		var display = jQuery( 'div.cs-simple-list-search' ).css( 'display' );
		if ( display == 'none' ) {
			jQuery("div.cs-simple-list-search").slideDown(300);
		} else if ( display == 'block' ) {
			jQuery("div.cs-simple-list-search").slideUp(300);
		}
		return false;
	});

});


/*
 * Search in simple list: Event for clicking the button, and getting the search form visible.
 *
 * @since 1.2.6
 */
jQuery(document).ready(function($) {

	/* Search part of the search form. */
	jQuery( '.cs-simple-list-search input.cs-simple-list-search-submit' ).on( 'click', function( event ) {

		jQuery( '.cs-simple-list-search-message' ).html('');
		jQuery( '.cs-simple-list-search-message' ).removeClass( 'error' );

		var main_div     = jQuery( this ).closest( 'div.cs-simple-list-form-container' );
		var form         = jQuery( 'form.cs-simple-list-search' );
		var ajaxurl      = jQuery( 'form.cs-simple-list-search input.cs-simple-list-search-ajaxurl' ).val();
		var defaulterror = jQuery( 'form.cs-simple-list-search input.cs-simple-list-search-defaulterror' ).val();
		var searcherror  = jQuery( 'form.cs-simple-list-search input.cs-simple-list-search-searcherror' ).val();
		var val          = jQuery( 'form.cs-simple-list-search input.cs-simple-list-search-text' ).prop('value');

		if ( typeof val == 'undefined' || val.length == 0 ) {
			jQuery( '.cs-simple-list-search-message' ).html('<p class="cs-messages error">' + searcherror + '</p>');
			jQuery( '.cs-simple-list-search-message' ).css( 'display', 'block' );
			event.preventDefault();
			return;
		}

		// Use an object, arrays are only indexed by integers.
		var cs_simple_search_ajax_data = {
			permalink: window.location.href,
			action: 'chessgame_shizzle_simple_list_search'
		};

		jQuery('form.cs-simple-list-search input').each(function( index, value ) {
			var val = jQuery( this ).prop('value');
			var name = jQuery( this ).attr('name');
			var type = jQuery( this ).attr('type');
			cs_simple_search_ajax_data[name] = val;
		});

		jQuery.post( ajaxurl, cs_simple_search_ajax_data, function( response ) {

			if ( chessgame_shizzle_is_json( response ) ) {
				var returndata = JSON.parse( response );

				if ( returndata['cs_error'] == true ) {

					jQuery( '.cs-simple-list-search-message' ).html('<p class="cs-messages notice">' + returndata['cs_message'] + '</p>');
					jQuery( '.cs-simple-list-search-message' ).addClass( 'error' );
					jQuery( '.cs-simple-list-search-message' ).css( 'display', 'block' );

				} else {

					jQuery( 'div.cs-simple-list-items' ).html( returndata['cs_html'] );
					jQuery( '.cs-simple-list-search-message' ).html('');
					jQuery( '.cs-simple-list-search-message' ).css( 'display', 'none' );

				}
			} else {

				jQuery( '.cs-simple-list-search-message' ).html('<p class="cs-messages error">' + defaulterror + '</p>');
				jQuery( '.cs-simple-list-search-message' ).addClass( 'error' );
				jQuery( '.cs-simple-list-search-message' ).css( 'display', 'block' );

			}
		});

		event.preventDefault();
	});

	jQuery('.cs-simple-list-search-text').on('keydown', function (event) {
		if ( event.which === 13 ) { // Enter key.
			jQuery( '.cs-simple-list-search-submit' ).trigger( 'click' );
			event.preventDefault();
		}
	});

});


function chessgame_shizzle_is_json( string ) {
	try {
		JSON.parse( string );
	} catch (e) {
		return false;
	}
	return true;
}
