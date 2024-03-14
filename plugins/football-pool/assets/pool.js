/**
 * @preserve Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/COPYING
 *
 * This file is part of Football pool.
 *
 * Football pool is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * Football pool is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with Football pool.
 * If not, see <https://www.gnu.org/licenses/>.
 */

// Minified with Closure compiler https://developers.google.com/closure/compiler/docs/gettingstarted_app

const fp_do_ajax_saves = FootballPoolAjax.do_ajax_saves === 'yes';
const fp_show_success_messages = FootballPoolAjax.show_success_messages === 'yes';
const fp_onunload_check = FootballPoolAjax.onunload_check === 'yes';

jQuery( document ).ready( function() {
	// Colorbox
	jQuery( '.fp-lightbox' ).colorbox( {
		transition: 'elastic',
		speed: 400,
		maxWidth: '95%', // Set max-width and max-height for mobile devices
		maxHeight: '95%'
	} );

	if ( fp_do_ajax_saves === true ) {
		// Preload the data value for all prediction inputs and bonus questions
		/*	jQuery( '.match input.prediction, .bonus input.bonus, .bonus textarea.bonus, ' +
				'.bonus ul.multi-select input, .bonus .multi-select select' ).each( function() {
				let $this = jQuery( this );
				$this.data( 'value', $this.val() );
			} );*/

		// Add event handler for the auto-submit for prediction input fields.
		// Inspired by https://gist.github.com/lyoshenka/9280740
		jQuery( 'form.fp-prediction-form' ).on(
			'input',
			'input.prediction',
			function() {
				FootballPool.change_prediction( jQuery( this ), 'team' );
			} );
		// Add event handler for the auto-submit of bonus questions.
		jQuery( 'form.fp-prediction-form' ).on(
			'input',
			'input.bonus, textarea.bonus, select.bonus'
			, function() {
				FootballPool.change_prediction( jQuery( this ), 'question' );
			} );

		// Make sure we do not block the onbeforeunload when doing a submit of the form (via save button).
		jQuery( document ).on(
			'submit',
			'form.fp-prediction-form',
			function() {
				FootballPool.do_submit();
			} );

		// Check if we have any unsaved changes in the forms.
		if ( fp_onunload_check ) {
			window.addEventListener( 'beforeunload', ( e) => {
				if ( FootballPool.check_submit() === false ) {
					// Check the form elements
					if ( FootballPool.check_for_unsaved_changes() === false ) {
						// Cancel the event as stated by the standard.
						e.preventDefault();
						// Chrome requires returnValue to be set.
						e.returnValue = FootballPool_i18n.unsaved_changes_message;
					}
				}
			} );
		}
	}

	// Add toast container to show messages from the prediction form
	jQuery( 'body' ).prepend( '<div id="fp-toasts"></div>' );
} );

const FootballPool = ( function( $ ) {
	const i18n = FootballPool_i18n;
	let form_submitted = false;

	// Returns true if all saves are okay.
	// Returns false if a save was not 'closed' via the set_saved_state() function.
	function check_for_unsaved_changes() {
		let all_ok = true;

		$( '.match.open input.prediction, .bonus.open input.bonus, .bonus.open textarea.bonus, ' +
			'.bonus.open ul.multi-select input, .bonus.open .multi-select select, ' +
			'.match.open .fp-joker-box' ).each(
				function() {
					const $this = $( this );
					all_ok = ( $this.data( 'saved' ) !== false ) && all_ok;

					// console.log( $this.attr( 'id' ) + ': ' + $this.data( 'saved' ) );
					// console.log( all_ok );

					// We can break out of the loop when there are unsaved changes.
					if ( all_ok === false ) return false;
				} );

		return all_ok;
	}

	function set_saved_state( el, value ) {
		el.data( 'saved', value )
	}

	function check_submit() {
		return form_submitted;
	}

	function do_submit() {
		form_submitted = true;
	}

	function change_prediction( prediction, type ) {
		const timer = prediction.data( 'timer' );
		const delay = parseInt( FootballPoolAjax.ajax_save_delay, 10 );

		if ( timer ) {
			clearTimeout( timer );
		}

		prediction.data( 'timer', setTimeout( function() {
			if ( prediction.val() !== prediction.data( 'value' ) ) {
				// Store current value in the element object
				prediction.data( 'value', prediction.val() );
				// Initiate ajax save action
				if ( type === 'team' ) {
					prediction.addClass( 'saving' );
					prediction.closest( '.match-card' ).addClass( 'saving' );
				} else {
					prediction.closest( 'div.bonus' ).addClass( 'saving' );
				}
				set_saved_state( prediction, false );

				if ( type === 'team' ) {
					change_team_prediction( prediction );
				} else {
					change_bonus_question( prediction );
				}
			}
		}, delay ) );
	}

	function change_bonus_question( question ) {
		let answer = '';
		const container = question.closest( 'div.bonus' );

		let name = question.attr( 'name' );
		let question_id = name.substring( name.indexOf( '_' ) + 1 );
		question_id = question_id.substring( question_id.indexOf( '_' ) + 1, question_id.length );
		// Strip off [] in case of checkboxes (multi-select)
		if ( question_id.indexOf( '[' ) !== -1 ) {
			question_id = question_id.substring( 0, question_id.indexOf( '[' ) );
		}

		const type = question.prop( 'type' );
		if ( type === 'text' || type === 'textarea' || type === 'select-one' ) {
			answer = question.val();
		} else if ( type === 'radio' ) {
			answer = question.val();
			question.data( 'value', null );
		} else if ( type === 'checkbox' ) {
			// Get all checked items and join with semicolon
			let answers = [];
			$( 'input[name="' + name + '"]:checked' ).each( function() {
				answers.push( $( this ).val() );
			} );
			answer = answers.join( ';' );
			question.data( 'value', answer );
		}

		$.ajax( {
			data: {
				action: 'footballpool_update_bonus_question',
				fp_question_nonce: FootballPoolAjax.fp_question_nonce,
				answer: answer,
				question: question_id,
				type: type
			},
			url: FootballPoolAjax.ajax_url,
			global: false,
			dataType: 'json',
			method: 'POST',
			success: function ( response, textStatus, jqXHR ) {
				// console.log( response );
				if ( response.return_code === false ) {
					toast( {
						type: 'error',
						message: response.msg
					} );
				} else {
					console.log( 'Question ' + question_id + ' saved!' );
					// Small animation?
					if ( fp_show_success_messages ) {
						toast( {
							type: 'success',
							message: i18n.question_saved.replace( '{id}', question_id )
						} );
					}
				}

				set_saved_state( question, true );
				clear_prediction_ajax_loader( container );
			},
			error: function ( jqXHR, textStatus, errorThrown ) {
				console.log( errorThrown );
				toast( {
					type: 'error',
					message: i18n.general_error
				} );

				clear_prediction_ajax_loader( container );
			}
		} );
	}

	function change_team_prediction( prediction ) {
		let name = prediction.attr( 'name' );
		let type = name.substring( name.indexOf( '_' ) + 1 );
		let match = type.substring( type.indexOf( '_' ) + 1, type.length );
		type = type.substring( 0, type.indexOf( '_' ) ); // home or away

		$.ajax( {
			data: {
				action: 'footballpool_update_team_prediction',
				fp_match_nonce: FootballPoolAjax.fp_match_nonce,
				prediction: prediction.val(),
				match: match,
				type: type
			},
			url: FootballPoolAjax.ajax_url,
			global: false,
			dataType: 'json',
			method: 'POST',
			success: function ( response, textStatus, jqXHR ) {
				// console.log( response );
				if ( response.return_code === false ) {
					toast( {
						type: 'error',
						message: response.msg
					} );

					// Try to restore the previous prediction
					if ( response.prev_prediction !== null ) {
						prediction.val( parseInt( response.prev_prediction, 10 ) );
					} else {
						prediction.val( '' );
					}
				} else {
					console.log( 'Match ' + match + ' saved!' );
					// Small animation?
					if ( fp_show_success_messages ) {
						toast( {
							type: 'success',
							message: i18n.match_saved.replace( '{id}', match )
						} );
					}
				}

				set_saved_state( prediction, true );
				clear_prediction_ajax_loader( prediction );
			},
			error: function ( jqXHR, textStatus, errorThrown ) {
				console.log( errorThrown );
				toast( {
					type: 'error',
					message: i18n.general_error
				} );
				clear_prediction_ajax_loader( prediction );
			}
		} );
	}

	function change_joker( id ) {
		if ( fp_do_ajax_saves === true ) {
			change_joker_ajax( id );
		} else {
			change_joker_no_ajax( id );
		}
	}

	function change_joker_no_ajax( id ) {
		let joker_val = $( "input[name='_joker']" ).val().split( ',' );
		const clicked_joker = $( '#' + id );
		// console.log(clicked_joker);
		const joker_active = clicked_joker.hasClass( 'fp-joker' );

		let changed_match = id.substring( id.indexOf( '-' ) + 1 );
		changed_match = changed_match.substring( 0, changed_match.indexOf( '-' ) );

		if ( ! joker_active ) {
			// Joker not set, so we activate it and add it to the array
			set_joker( changed_match );
			joker_val.push( changed_match );
		} else {
			// Joker set, so we clear it and remove it from the array
			clear_joker( changed_match );
			const index = joker_val.indexOf( changed_match );
			if ( index > -1 ) {
				joker_val.splice( index, 1 ); // 2nd parameter means remove one item only
			}
		}

		// Update the joker input
		// const the_form = clicked_joker.closest( 'form' );
		// $( "input[name='_joker']", the_form ).val( joker_val );
		$( "input[name='_joker']" ).val( joker_val.join() );
	}

	function change_joker_ajax( id ) {
		let joker_val = id.substring( id.indexOf( '-' ) + 1 );
		joker_val = joker_val.substring( 0, joker_val.indexOf( '-' ) );

		let clicked_joker = $( '#' + id );
		clicked_joker.addClass( 'saving' );
		clicked_joker.closest( '.match-card' ).addClass( 'saving' );

		set_saved_state( clicked_joker, false );

		$.ajax( {
			data: {
				action: 'footballpool_update_joker',
				fp_joker_nonce: FootballPoolAjax.fp_joker_nonce,
				joker: joker_val
			},
			url: FootballPoolAjax.ajax_url,
			global: false,
			dataType: 'json',
			method: 'POST',
			success: function ( response, textStatus, jqXHR ) {
				// console.log( response );
				if ( response.return_code === false ) {
					toast( {
						type: 'error',
						message: response.msg
					} );

					clear_joker_ajax_loader( joker_val );
				} else {
					if ( Object.keys( response.action ).length > 0 ) {
						$.each( response.action, function( match_id, joker_action ) {
							if ( joker_action === 'set' ) {
								set_joker( match_id );
							} else if ( joker_action === 'clear' ) {
								clear_joker( match_id );
							} else {
								console.log( 'ERROR: undefined joker action.' );
							}
						} );
					}

					set_saved_state( clicked_joker, true );

					// update the joker input
					$( "input[name='_joker']" ).val( response.joker );

					// Result message
					console.log( 'Multiplier ' + joker_val + ' saved!' );
					if ( fp_show_success_messages ) {
						toast( {
							type: 'success',
							message: i18n.match_saved.replace( '{id}', joker_val )
						} );
					}
				}
			},
			error: function ( jqXHR, textStatus, errorThrown ) {
				console.log( errorThrown );
				toast( {
					type: 'error',
					message: i18n.general_error
				} );

				clear_joker_ajax_loader( joker_val );
			}
		} );
	}

	function clear_prediction_ajax_loader( el ) {
		el.removeClass( 'saving' );
		el.closest( '.match-card' ).removeClass( 'saving' );
	}

	function clear_joker_ajax_loader( match_id ) {
		const el = $( 'div[id^="joker-' + match_id + '-"]' );
		el.removeClass( 'saving' );
		el.closest( '.match-card' ).removeClass( 'saving' );
	}

	function set_joker_state( match_id, joker_active ) {
		let to_class   = ( joker_active === true ) ? 'fp-joker' : 'fp-nojoker';
		let from_class = ( joker_active === true ) ? 'fp-nojoker' : 'fp-joker';
		$( 'div[id^="joker-' + match_id + '-"]' )
			.removeClass( from_class )
			.addClass( to_class );
		if ( fp_do_ajax_saves ) clear_joker_ajax_loader( match_id );
	}

	function set_joker( match_id ) {
		// Set all jokers on the page with the same match id
		set_joker_state( match_id, true );
	}

	function clear_joker( match_id ) {
		// Clear all jokers on the page with the same match id
		set_joker_state( match_id, false );
	}

	function update_chars( id, chars ) {
		const count_element = $( '#' + id );
		const length = count_element.val().length;
		const remaining = chars - length;
		count_element.parent().find( 'span span' ).replaceWith( '<span>' + remaining + '</span>' );
	}

	// todo: check if we can adjust for the client timezone
	function do_countdown( el, extra_text, year, month, day, hour, minute, second, format_type, format_string ) {
		const date_to = new Date( year, month - 1, day, hour, minute, second ).getTime();
		const date_now = new Date().getTime();
		let diff = Math.abs( Math.round( ( date_to - date_now ) / 1000 ) );
		
		let pre = '', post = '', counter = '';
		let d = 0, h = 0, m = 0, s = 0;
		let days, hrs, min, sec;
		
		if ( format_string === '' || format_string === null ) {
			format_string = '{d} {days}, {h} {hrs}, {m} {min}, {s} {sec}';
		}
		
		if ( extra_text === null ) {
			extra_text = {
				'pre_before' : i18n.count_pre_before,
				'post_before' : i18n.count_post_before,
				'pre_after' : i18n.count_pre_after,
				'post_after' : i18n.count_post_after
			};
		}
		
		if ( date_to < date_now ) {
			pre = extra_text.pre_after;
			post = extra_text.post_after;
		} else {
			pre = extra_text.pre_before;
			post = extra_text.post_before;
		}
		
		switch ( format_type ) {
			case 1: // only seconds
				s = diff;
				break;
			case 2: // days, hours, minutes, seconds
			case 4: // days, hours, minutes
				switch ( true ) {
					case diff > 86400:
						d = Math.floor( diff / 86400 );
						diff -= d * 86400;
					case diff > 3600:
						h = Math.floor( diff / 3600 );
						diff -= h * 3600;
					case diff > 60:
						m = Math.floor( diff / 60 );
						diff -= m * 60;
					default:
						s = diff;
				}
				break;
			case 3: // hours, minutes, seconds
			case 5: // hours, minutes
				switch ( true ) {
					case diff > 3600:
						h = Math.floor( diff / 3600 );
						diff -= h * 3600;
					case diff > 60:
						m = Math.floor( diff / 60 );
						diff -= m * 60;
					default:
						s = diff;
				}
				break;
		}
		
		// Set the correct texts for the day, hour, minute and second value
		days = ( d === 1 ? i18n.count_day : i18n.count_days );
		hrs  = ( h === 1 ? i18n.count_hour : i18n.count_hours );
		min  = ( m === 1 ? i18n.count_minute : i18n.count_minutes );
		sec  = ( s === 1 ? i18n.count_second : i18n.count_seconds );
		
		// Replace the number and text placeholders in the format string
		counter = format_string.replace( '{d}', d ).replace( '{days}', days )
								.replace( '{h}', h ).replace( '{hrs}', hrs )
								.replace( '{m}', m ).replace( '{min}', min )
								.replace( '{s}', s ).replace( '{sec}', sec );
		
		$( el ).text( pre + counter + post );
	}
	
	// based on http://www.frequency-decoder.com/2006/07/20/correctly-calculating-a-date-suffix
	// suffixes must be an array of format ["th", "st", "nd", "rd", "th"];
	function ordinal_suffix( d ) {
		let suffix = '';
		const suffixes = arguments[1] || ['th', 'st', 'nd', 'rd', 'th'];

		d = String( d );
		if ( d.substring( -( Math.min( d.length, 2 ) ) ) > 3 && d.substring( -( Math.min( d.length, 2 ) ) ) < 21 ) {
			suffix = suffixes[0];
		} else {
			suffix = suffixes[Math.min( Number( d ) % 10, 4 )];
		}

		return suffix;
	}

	function add_ordinal_suffix( d ) {
		return d + ordinal_suffix( d, arguments[1] );
	}

	function charts_user_toggle() {
		$( 'input:checkbox', '.user-selector ol' ).bind( 'click', function() {
			$( this ).parent().parent().toggleClass( 'selected' );
		} );
	}
	
	function set_max_answers( id, max ) {
		const question = '#q' + id;
		
		// Check onload
		check_max_answers( id, max );
		
		// And set the click action
		$( question + ' :checkbox' ).click( function() {
			check_max_answers( id, max )
		} );
	}

	function check_max_answers( id, max ) {
		const question = '#q' + id;
		if ( $( question + ' :checkbox:checked' ).length >= max ) {
			$( question + ' :checkbox:not(:checked)' ).attr( 'disabled', 'disabled' );
		} else {
			$( question + ' :checkbox' ).removeAttr( 'disabled' );
		}
	}

	function toast( config ) {
		const toast_defaults = {
			autoDismiss: true,
			container: '#fp-toasts',
			autoDismissDelay: 4000,
			transitionDuration: 500
		};

		config = $.extend( {}, toast_defaults, config );
		$.toast( config );
	}

	return {
		// public methods
		add_ordinal_suffix: add_ordinal_suffix,
		change_joker: change_joker,
		change_prediction: change_prediction,
		update_chars: update_chars,
		countdown: do_countdown,
		charts_user_toggle: charts_user_toggle,
		set_max_answers: set_max_answers,
		do_submit: do_submit,
		check_submit: check_submit,
		check_for_unsaved_changes: check_for_unsaved_changes
	};

} )( jQuery );
