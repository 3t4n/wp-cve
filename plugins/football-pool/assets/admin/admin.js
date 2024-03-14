/**
 * @preserve Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

jQuery( document ).ready( function() {
	// initiate chosen lib on select boxes
	if ( jQuery().chosen ) {
		jQuery( '.fp-select.allow-single-deselect' ).not( '.no-chosen' ).chosen( {
			allow_single_deselect: true,
			hide_results_on_select: false,
			inherit_select_classes: true,
			disable_search_threshold: 10,
			no_results_text: FootballPoolAdmin.chosen_no_results_text
		} );
		jQuery( '.fp-select' ).not( '.allow-single-deselect' ).not( '.no-chosen' ).chosen( {
			disable_search_threshold: 10,
			hide_results_on_select: false,
			inherit_select_classes: true,
			no_results_text: FootballPoolAdmin.chosen_no_results_text
		} );
	}

	// current page selection on enter
	jQuery( 'body.football-pool input.current-page' ).keydown( function( e ) {
		let code = ( e.keyCode ? e.keyCode : e.which );
		if ( code === 13 ) jQuery( 'input[name="action"]' ).val( '' );
	} );
	
	// (un)select all matches in a match type
	jQuery( 'div.matchtype input:checkbox' ).click( function() {
		let matchtype_id = jQuery( this ).attr( 'id' ).replace( 'matchtype-', '' );
		if ( jQuery( this ).is( ':checked' ) ) {
			jQuery( 'div.matchtype-' + matchtype_id + ' input:checkbox' ).each( function() {
				jQuery( this ).attr( 'checked', 'checked' );
			} );
		} else {
			jQuery( 'div.matchtype-' + matchtype_id + ' input:checkbox' ).each( function() {
				jQuery( this ).removeAttr( 'checked' );
			} );
		}
	} );
	
	// show/hide row actions on tab navigation
	jQuery( 'div.row-actions span a' ).each( function() {
		jQuery( this ).focus( { el: jQuery( this ) }, function( e ) {
			jQuery( window ).keyup( { el: e.data.el }, function( e ) {
				let code = ( e.keyCode ? e.keyCode : e.which );
				if ( code === 9 ) {
					e.data.el.parent().parent().css( { left: 0 } ); 
				}
			} );
		} );
		jQuery( this ).blur( { el: jQuery( this ) }, function( e ) {
			jQuery( window ).keyup( { el: e.data.el }, function( e ) {
				let code = ( e.keyCode ? e.keyCode : e.which );
				if ( code === 9 ) {
					e.data.el.parent().parent().css( { left: -9999 } ); 
				}
			} );
		} );
	} );

	/**
	 * progressbar for help page and options page
	 *
	 * https://www.freecodecamp.org/news/back-to-top-button-and-page-progressbar-with-html-css-and-js/
	 */
	const fpBackToTopButton = document.querySelector(".fp-admin .back-to-top");

	if ( fpBackToTopButton !== null ) {
		const fpShowOnPx = 100;
		const fpPageProgressBar = document.querySelector(".fp-admin .progress-bar");

		const fpScrollContainer = () => {
			return document.documentElement || document.body;
		};

		const fpGoToTop = (e) => {
			document.body.scrollIntoView({
				behavior: "smooth"
			});
			e.preventDefault();
		};

		document.addEventListener("scroll", () => {
			// console.log("Scroll Height: ", fpScrollContainer().scrollHeight);
			// console.log("Client Height: ", fpScrollContainer().clientHeight);

			const scrolledPercentage =
				(fpScrollContainer().scrollTop /
					(fpScrollContainer().scrollHeight - fpScrollContainer().clientHeight)) * 100;

			fpPageProgressBar.style.width = `${scrolledPercentage}%`;

			if (fpScrollContainer().scrollTop > fpShowOnPx) {
				fpBackToTopButton.classList.remove("hidden");
			} else {
				fpBackToTopButton.classList.add("hidden");
			}
		});

		fpBackToTopButton.addEventListener("click", fpGoToTop); // progressbar
	}
} );

var FootballPoolAdmin = (function ( $ ) {
	
	let value_store = [];
	
	// score calculation handling
	let cbox,
		start_time,
		calc_timer = false,
		force_calculation_setting = 0,
		calculation_completed = false,
		calculation_cancelled = false;
	
	function force_calculation() {
		force_calculation_setting = 1;
		calculate_score_history();
	}
	
	function cancel_calculation() {
		if ( ! calculation_completed ) {
			const ajax_action = 'footballpool_calculate_scorehistory';
			calculation_cancelled = true;
			
			$.ajax( {
					data: {
						action: ajax_action,
						fp_recalc_nonce: FootballPoolAjax.fp_recalc_nonce,
						calculation_step: 'cancel_calculation',
						iteration: 1
					},
					url: ajaxurl,
					global: false,
					dataType: 'json',
					method: 'POST',
					success: function( data, textStatus, jqXHR ) {
						console.log( 'football pool calculation cancelled' );
					},
					error: function( jqXHR, textStatus, errorThrown ) {
						console.log( errorThrown );
					}
			} );
		}
	}
	
	function calculate_score_history() {
		let data = arguments[0] || 0;
		const ajax_action = 'footballpool_calculate_scorehistory';
		let bar = [];
		
		if ( data === 0 ) {
			calculation_cancelled = false;
			start_time = new Date().getTime();
			
			$.ajax( {
					data: {
						action: ajax_action,
						fp_recalc_nonce: FootballPoolAjax.fp_recalc_nonce,
						iteration: 0,
						force_calculation: force_calculation_setting
					},
					url: ajaxurl,
					global: false,
					dataType: 'json',
					method: 'POST',
					success: function( response, textStatus, jqXHR ) {
								cbox = $.colorbox( { 
													html: response.colorbox_html,
													overlayClose: false,
													escKey: true,
													arrowKey: false,
													close: FootballPoolAjax.colorbox_close,
													innerWidth: "500px",
													innerHeight: "285px"
												} );
								// bind cleanup method to colorbox
								$( document ).bind( 'cbox_cleanup', function() {
									cancel_calculation();
								} );
								$( '#fp-calc-progress' ).show();
								bar = $( '#fp-calc-progressbar' );
								if ( bar.length !== 0 ) {
									bar.progressbar();
									bar.show();
									$( '#ajax-loader' ).show();
									if ( bar.progressbar( 'option', 'max' ) != response.total_iterations ) {
										bar.progressbar( 'option', 'max', response.total_iterations );
									}
								}
								$( '#calculation-message' ).html( response.message );
								if ( ! calculation_cancelled ) calculate_score_history( response );
					},
					error: function( jqXHR, textStatus, errorThrown ) {
						clear_elapsed_time_timer();
						let error = '';
						if ( textStatus !== null ) {
							error += 'textStatus: ' + textStatus + '. ';
						}
						if ( errorThrown !== null ) {
							error += 'errorThrown: ' + errorThrown + '. ';
						}
						console.log( "Error message: " + error );
						alert( "Error message:\n" + error );
					}
			} );
		} else {
			if ( calc_timer === false ) {
				calc_timer = setInterval( function() { 
											let elapsed_time = ( new Date().getTime() ) - start_time;
											$( '#time-elapsed' ).html( format_time( elapsed_time / 1000 ) );
										}, 1000 );
			}
			
			$.ajax( {
					data: {
						action: ajax_action,
						force_calculation: data.force_calculation,
						fp_recalc_nonce: data.fp_recalc_nonce,
						total_iterations: data.total_iterations,
						iteration: data.iteration,
						sub_iteration: data.sub_iteration,
						sub_iterations: data.sub_iterations,
						calculation_step: data.calculation_step,
						user: data.user,
						ranking: data.ranking,
						match: data.match,
						question: data.question,
						prev_total_score: data.prev_total_score,
						score_order: data.score_order,
						calc_start_time: data.calc_start_time,
						calculation_steps_cache: data.calculation_steps_cache
					},
					url: ajaxurl,
					global: false,
					dataType: 'json',
					method: 'POST',
					success: function( response, textStatus, jqXHR ) {
								// console.log( response );
								if ( response === null ) {
									score_calculation_error( FootballPoolAdmin.error_message + ' Error: response was null.' );
								} else {
									calculation_completed = ( typeof response.completed !== 'undefined' 
																	&& response.completed === 1 );
									
									// calculate estimated time left
									let elapsed_time = ( new Date().getTime() ) - start_time;
									let iteration_time = elapsed_time / data.iteration;
									let estimated_total_time = data.total_iterations * iteration_time;
									let time_left_in_seconds = ( estimated_total_time - elapsed_time ) / 1000;
									if ( time_left_in_seconds < 0 ) time_left_in_seconds = 0;
									// only show time left when at least 2 iterations have passed
									if ( data.iteration > 1 ) {
										$( '#time-left' ).html( format_time( time_left_in_seconds ) );
									}
									// update progress bar and status message
									bar = $( '#fp-calc-progressbar' );
									if ( bar.length !== 0 ) {
										bar.progressbar();
										if ( bar.progressbar( 'option', 'max' ) != response.total_iterations ) {
											bar.progressbar( 'option', 'max', response.total_iterations );
										}
										bar.progressbar( 'option', 'value', response.iteration );
									}
									$( '#calculation-message' ).html( response.message );
									
									// continue or stop?
									if ( response.error === false ) {
										if ( calculation_completed ) {
											clear_elapsed_time_timer();
											$( '#time-left' ).html( format_time( 0 ) );
											$( '#ajax-loader' ).hide();
										} else {
											if ( ! calculation_cancelled ) calculate_score_history( response );
										}
									} else {
										clear_elapsed_time_timer();
										score_calculation_error( response.error );
									}
								}
					},
					error: function( jqXHR, textStatus, errorThrown ) {
						score_calculation_error( errorThrown );
					}
			} );
		}
	}
	
	function format_time( sec_num ) {
		sec_num = Math.floor( sec_num );

		let hours   = Math.floor( sec_num / 3600 ),
			minutes = Math.floor( ( sec_num - ( hours * 3600 ) ) / 60 ),
			seconds = sec_num - ( hours * 3600 ) - ( minutes * 60 );

		if ( hours   < 10 ) hours   = `0${hours}`;
		if ( minutes < 10 ) minutes = `0${minutes}`;
		if ( seconds < 10 ) seconds = `0${seconds}`;

		return `${hours}:${minutes}:${seconds}`;
	}
	
	function clear_elapsed_time_timer() {
		clearInterval( calc_timer );
		calc_timer = false;
	}
	
	function score_calculation_error() {
		let msg = arguments[0] || FootballPoolAjax.error_message;
		clear_elapsed_time_timer();
		$( '#ajax-loader' ).hide();
		$( '#fp-calc-progress' ).show();
		$( '#calculation-message' ).html( `<span class="error">${msg}</span>` );
	}
	// end score calculation handler
	
	function bulk_action_warning( id ) {
		let bulk_select = $( '#' + id ),
			msg;

		if ( bulk_select && bulk_select.prop( 'selectedIndex' ) !== 0 ) {
			msg = $( '#' + id + ' option').filter( ':selected' ).attr( 'bulk-msg' );
			if ( msg !== '' && msg !== undefined ) {
				return( confirm( msg ) );
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	function toggle_points( id ) {
		$( '#' + id + '_points' ).toggle();
	}
	
	function set_input_param( param, id, value ) {
		let param_value;
		if ( $.isArray( id ) && id.length >= 1 ) {
			$.each( id, function( i, v ) { 
				if ( v !== '' ) {
					if ( ! $.isArray( value_store[v] ) ) value_store[v] = [];
					value_store[v][param] = $( v ).attr( param );
					param_value = $.isArray( value ) ? value[i] : value;
					$( v ).attr( param, param_value );
				}
			} );
		} else {
			if ( id !== '' ) {
				value_store[id] = [ param, $( id ).attr( param ) ];
				param_value = $.isArray( value ) ? value[0] : value;
				$( id ).attr( param, param_value );
			}
		}
	}

	function restore_input_param( param, id ) {
		let param_value = '';
		if ( $.isArray( id ) && id.length >= 1 ) {
			$.each( id, function( i, v ) {
				param_value = ( typeof value_store[v][param] !== undefined ) ? value_store[v][param] : '';
				$( v ).attr( param, param_value );
			} );
		} else {
			if ( id !== '' ) {
				param_value = ( typeof value_store[id][param] !== undefined ) ? value_store[id][param] : '';
				$( id ).attr( param, param_value );
			}
		}
	}
	
	function disable_inputs( id ) {
		let check_id = arguments[1] || '';
		let readonly = false;
		if ( check_id !== '' ) {
			readonly = $( '#' + check_id ).is( ':checked' );
		}
		
		if ( $.isArray( id ) && id.length >= 1 ) {
			$.each( id, function( i, v ) { 
				if ( v !== '' ) {
					if ( check_id !== '' ) {
						if ( readonly ) {
							$( v ).attr( 'disabled', 'disabled' );
						} else {
							$( v ).removeAttr( 'disabled' );
						}
					} else {
						$( v ).attr( 'disabled', 'disabled' ); 
					}
				}
			} );
		} else if ( id !== '' ) {
			if ( check_id !== '' ) {
				if ( readonly ) {
					$( id ).attr( 'disabled', 'disabled' );
				} else {
					$( id ).removeAttr( 'disabled' );
				}
			} else {
				$( id ).attr( 'disabled', 'disabled' ); 
			}
		}
	}

	function toggle_linked_radio_options( active_id, disabled_id ) {
		let real_toggle = arguments[2] ? arguments[2] : false;

		if ( $.isArray( active_id ) && active_id.length >= 1 ) {
			$.each( active_id, function( i, v ) {
				if ( v !== '' ) {
					if ( real_toggle === true ) {
						$(v).toggle();
					} else {
						$(v).toggle( true );
					}
				}
			} );
		} else if ( active_id !== '' ) {
			if ( real_toggle === true ) {
				$( active_id ).toggle();
			} else {
				$( active_id ).toggle( true );
			}
		}
		
		if ( $.isArray( disabled_id ) && disabled_id.length >= 1 ) {
			$.each( disabled_id, function( i, v ) { 
				if ( v !== '' ) $( v ).toggle( false );
			} );
		} else if ( disabled_id !== '' ) {
			$( disabled_id ).toggle( false );
		}
	}

	return {
		// public methods
		calculate: calculate_score_history,
		force_calculation: force_calculation,
		bulk_action_warning: bulk_action_warning,
		toggle_points: toggle_points,
		set_input_param: set_input_param,
		restore_input_param: restore_input_param,
		disable_inputs: disable_inputs,
		toggle_linked_options: toggle_linked_radio_options
	};

} )( jQuery );

// jQuery Input Hints plugin
// Copyright (c) 2009 Rob Volk
// http://www.robvolk.com

jQuery( document ).ready( function() {
   jQuery( 'input[title].with-hint' ).inputHints();
});

jQuery.fn.inputHints=function() {
	// hides the input display text stored in the title on focus
	// and sets it on blur if the user hasn't changed it.

	// show the display text
	// changed (AntoineH): only for empty inputs
	jQuery(this).each(function(i) {
		if (jQuery(this).val() === '') {
			jQuery(this).val(jQuery(this).attr('title'))
				.addClass('hint');
		}
	});

	// hook up the blur & focus
	return jQuery(this).focus(function() {
		if (jQuery(this).val() == jQuery(this).attr('title'))
			jQuery(this).val('')
				.removeClass('hint');
	}).blur(function() {
		if (jQuery(this).val() == '')
			jQuery(this).val(jQuery(this).attr('title'))
				.addClass('hint');
	});
}; // jQuery Input Hints plugin

