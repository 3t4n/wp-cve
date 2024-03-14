(function ($) {
	'use strict';
	var cr_params, the_player;
	var crerfsi_html; // for dialog Enter challenge result

	$( document ).ready(
		function () {
			cr_params = (typeof courtres_params !== 'undefined') ? courtres_params : null;
			// console.log('cr_params', cr_params);

			the_player = $( ".cr-piramid" ).data( "the_player" );

			$( '.datepicker' ).datepicker(
				{
					dateFormat: $( '.datepicker' ).data( 'date_format' )
				}
			);

			// accepting the challenge by direct link (from email)
			if (cr_params.user_can_accept) {
				$( ".cr-challenge-item .cr-challenge-action.accept" ).trigger( "click" );
			}

			// the dialog "To comfirm the challenge by direct link (from email) you need autorized as challenged user"
			if (cr_params.needs_authorize_as_challenged) {
				if (cr_params.accepting_challenge) {
					$( '.cr-dialog-comfirm' ).first().dialog(
						{
							modal: true,
							// title: cr_params.trans["Accepting the challenge"],
							title: "Accepting the challenge",
							open: function () {
								// var content = cr_params.trans["To comfirm the challenge you need to login as"] +
								var content = "To comfirm the challenge you need to login as" +
								" <strong>" + cr_params.accepting_challenge.challenged.wp_user.display_name + "</strong><br /><br />" +
								// '<p align="center"><a href="' + cr_params.login_href + '" class="ui-button ui-corner-all ui-widget">' + cr_params.trans["login"] + '</a></p>';
								'<p align="center"><a href="' + cr_params.login_href + '" class="ui-button ui-corner-all ui-widget">' + "login" + '</a></p>';
								$( this ).find( ".content" ).html( content );
							},
						}
					);
				}
			}

			// succesfully_accepted by direct link (from email)
			if (cr_params.is_accepted) {
				if (cr_params.accepting_challenge) {
					$( '.cr-dialog-comfirm' ).first().dialog(
						{
							modal: true,
							// title: cr_params.trans["The challenge accepted"],
							title: "The challenge accepted",
							open: function () {
								// var content = cr_params.trans["The challenge"] + '<br />' +
								var content = "The challenge" + '<br />' +
								"<strong>" + cr_params.accepting_challenge.challenger.wp_user.display_name +
								" vs. " + cr_params.accepting_challenge.challenged.wp_user.display_name + "</strong><br />" +
								// cr_params.trans["was succeffully acepted!"];
								"was succeffully acepted!";
								$( this ).find( ".content" ).html( content );
							},
							buttons: [{
								text: "Close",
								click: function () {
									$( this ).dialog( "close" );
								}
							}, ],
						}
					);
				}
			}

			crerfsi_html = $( ".cr-dialog-enter-results .cr-enter-results-form .sets-list" ).html(); // for dialog Enter challenge result
		}
	)

	// click on player to create a challenge
	.on(
		'click',
		'.cr-piramid .cr-player-item',
		function (e) {
			e.preventDefault();
			var player = $( this ).data( "player" );

			// $('.cr-dialog-create-challenge').first().dialog({
			$( '#cr-dialog-create-challenge-' + player.piramid_id ).dialog(
				{
					dialogClass: "cr-dialog-confirm",
					modal: true,
					title: "Challenge someone",
					buttons: [{
						text: "Close",
						click: function () {
							$( this ).dialog( "close" );
						}
					},
					{
						// Create the challenge
						text: "Submit",
						type: "submit",
						form: "cr-accept-challenge-form",
						click: function () {
							e.preventDefault();
							var the_dialog = $( this );
							$( this ).closest( ".ui-dialog" ).append( '<div class="cr-preloader-overlay in-relative-block" id="plo_create_challenge"><div class="cr-preloader"></div></div>' );
							var preloader = $( this ).closest( ".ui-dialog" ).find( '.cr-preloader-overlay#plo_create_challenge' );
							$( preloader ).fadeIn();

							var form      = $( this ).find( "form.cr-create-challenge-form" );
							var form_data = new FormData( $( form )[0] );
							form_data.append( 'challenged_id', player.player_id );
							form_data.append( 'action', 'create_challenge' );
							form_data.append( 'piramid_id', player.piramid_id );
							form_data.append( 'piramid_url', window.location.href );

							jQuery.ajax(
								{
									type: "POST",
									url: cr_params.ajax_url,
									processData: false,
									contentType: false,
									data: form_data,
									dataType: 'json',
									success: function (responce) {
										// console.log('responce', responce);
										if (responce.success) {
											// alert("The challeng successfully created");
											$( the_dialog ).dialog( "close" );
											window.location.href = window.location.href.replace(); // reload without history
										} else {
											alert( responce.errors.join( '; ' ) );
											$( the_dialog ).dialog( "close" );
										}
										$( preloader ).fadeOut();
									},
									error: function (jqXHR, exception) {
										$( preloader ).fadeOut();
										console.warn( 'create challenge ajax error' );
									},
								}
							);
						}
					}
					],
					open: function () {
						if (the_player) {
							$( this ).find( ".challenger-name" ).text( the_player.display_name );
						}
						if (player) {
							$( this ).find( ".challenged-name" ).text( player.display_name );
							$( this ).find( '#challenged_id' ).val( player.player_id );
							$( this ).find( '#piramid_id' ).val( player.piramid_id );
						}
					},
					close: function () {},
				}
			);
		}
	)

	// accepting the challenge
	.on(
		'click',
		'.cr-challenge-item .cr-challenge-action.accept',
		function (e) {
			e.preventDefault();
			var challenge = $( this ).closest( ".cr-challenge-item" ).data( "challenge" );
			var params    = {
				"challenge_id": challenge.id,
				"action": "accept_challenge",
				"accept_nonce": $( this ).data( "accept_nonce" )
			}
			// $('.cr-dialog-comfirm').first().dialog({
			$( '#cr-dialog-comfirm-' + challenge.piramid_id ).first().dialog(
				{
					modal: true,
					// title: cr_params.trans["You have been challenged"],
					title: "You have been challenged",
					open: function () {
						// var content = cr_params.trans["You have been challenged by"] + " <strong>" + challenge.challenger.wp_user["display_name"] + "</strong>.<br />" + cr_params.trans["Accept the challenge?"];
						var content = "You have been challenged by" + " <strong>" + challenge.challenger.wp_user["display_name"] + "</strong>.<br />" + "Accept the challenge?";
						$( this ).find( ".content" ).html( content );
					},
					buttons: [{
						text: "No",
						click: function () {
							$( this ).dialog( "close" );
						}
					},
					{
						// Accept the challenge
						text: "Yes",
						click: function () {
							e.preventDefault();
							var the_dialog = $( this );

							$( this ).closest( ".ui-dialog" ).append( '<div class="cr-preloader-overlay in-relative-block" id="plo_accept_challenge"><div class="cr-preloader"></div></div>' );
							var preloader = $( this ).closest( ".ui-dialog" ).find( '.cr-preloader-overlay#plo_accept_challenge' );
							$( preloader ).fadeIn();

							jQuery.ajax(
								{
									type: "POST",
									url: cr_params.ajax_url,
									data: params,
									dataType: 'json',
									success: function (responce) {
										// console.log('responce', responce);
										if (responce.success) {
											$( the_dialog ).dialog( "close" );
											window.location.href = window.location.href.replace(); // reload without history
										} else {
											alert( responce.errors.join( '; ' ) );
											$( the_dialog ).dialog( "close" );
										}
										$( preloader ).fadeOut();
									},
									error: function (jqXHR, exception) {

										$( preloader ).fadeOut();
										console.warn( 'accept challenge ajax error' );
									},
								}
							);
						}
					}
					],
					close: function () {},
				}
			);
		}
	)

	// scheduling the challenge
	.on(
		'click',
		'.cr-challenge-item .cr-challenge-action.schedule',
		function (e) {
			e.preventDefault();
			var challenge_id = $( this ).closest( ".cr-challenge-item" ).data( "id" );
			var challenge    = $( this ).closest( ".cr-challenge-item" ).data( "challenge" );
			// var schedule_dialog = $('.cr-dialog-schedule-challenge').first().dialog({
			$( '#cr-dialog-schedule-challenge-' + challenge.piramid_id ).first().dialog(
				{
					modal: true,
					buttons: [{
						text: "Close",
						click: function () {
							$( this ).dialog( "close" );
						}
					},
					{
						// Schedule the challenge
						text: "Submit",
						type: "submit",
						click: function () {
							e.preventDefault();
							var the_dialog = $( this );
							$( this ).closest( ".ui-dialog" ).append( '<div class="cr-preloader-overlay in-relative-block" id="plo_schedule_challenge"><div class="cr-preloader"></div></div>' );
							var preloader = $( this ).closest( ".ui-dialog" ).find( '.cr-preloader-overlay#plo_schedule_challenge' );
							$( preloader ).fadeIn();

							var form      = $( this ).find( "form.cr-schedule-challenge-form" );
							var form_data = new FormData( $( form )[0] );
							form_data.append( 'challenge_id', challenge_id );
							form_data.append( 'challenged_id', challenge.challenged_id );
							form_data.append( 'challenger_id', challenge.challenger_id );
							form_data.append( 'action', 'schedule_challenge' );

							var validation = validate_accept_challenge_form( form );
							if ( ! validation.is_valid) {
								  alert( validation.mess );
								  $( preloader ).fadeOut();
								  $( preloader ).remove();
								  return;
							}

							jQuery.ajax(
								{
									type: "POST",
									url: cr_params.ajax_url,
									processData: false,
									contentType: false,
									data: form_data,
									dataType: 'json',
									success: function (responce) {
										// console.log('responce', responce);
										if (responce.success) {
											// alert("The challenge successfully scheduled");
											$( the_dialog ).dialog( "close" );
											window.location.href = window.location.href.replace(); // reload without history
										} else {
											alert( responce.errors.join( '; ' ) );
											// $(the_dialog).dialog( "close" );
										}
										$( preloader ).fadeOut();
										$( preloader ).remove();
									},
									error: function (jqXHR, exception) {
										$( preloader ).fadeOut();
										$( preloader ).remove();
										console.warn( 'schedule challenge ajax error' );
									},
								}
							);
						}
					}
					],
					open: function () {},
					close: function () {},
				}
			);
		}
	)

	// scheduling the challenge, setup opening hours of the selected court
	.on(
		'change',
		'.cr-dialog-schedule-challenge #cr-court-select',
		function (e) {
			var dialog = $( this ).closest( ".ui-dialog" );
			$( dialog ).append( '<div class="cr-preloader-overlay in-relative-block" id="plo_schedule_challenge"><div class="cr-preloader"></div></div>' );
			var preloader = $( this ).closest( ".ui-dialog" ).find( '.cr-preloader-overlay#plo_schedule_challenge' );
			$( preloader ).fadeIn();

			var params = {
				"court_id": $( this ).val(),
				"action": "get_court"
			}

			jQuery.ajax(
				{
					type: "POST",
					url: cr_params.ajax_url,
					data: params,
					dataType: 'json',
					success: function (responce) {
						// console.log('responce', responce);
						if (responce.success) {
							var start_h = $( dialog ).find( ".select-start-h" ).val();
							$( dialog ).find( ".select-start-h" ).attr(
								{
									min: responce.court.open
								}
							);
							if (start_h < responce.court.open) {
									$( dialog ).find( ".select-start-h" ).val( responce.court.open );
							}
						} else {
							alert( responce.errors.join( '; ' ) );
						}
						$( preloader ).fadeOut();
					},
					error: function (jqXHR, exception) {
						$( preloader ).fadeOut();
						console.warn( 'get_court_opening_times ajax error' );
					},
				}
			);

		}
	)

	// deleting the challenge
	.on(
		'click',
		'.cr-challenge-item .cr-challenge-action.delete',
		function (e) {
			e.preventDefault();
			var challenge_id = $( this ).closest( ".cr-challenge-item" ).data( "id" );
			var challenge    = $( this ).closest( ".cr-challenge-item" ).data( "challenge" );
			var params       = {
				"challenge_id": challenge_id,
				"action": "delete_challenge",
				"delete_nonce": $( this ).data( "delete_nonce" )
			}
			// $('.cr-dialog-comfirm').first().dialog({
			$( '#cr-dialog-comfirm-' + challenge.piramid_id ).first().dialog(
				{
					modal: true,
					// title: cr_params.trans["Delete Challenge?"],
					title: "Delete Challenge?",
					buttons: [{
						text: "No",
						click: function () {
							$( this ).dialog( "close" );
						}
					},
					{
						// Delete the challenge
						text: "Delete",
						click: function () {
							e.preventDefault();
							var the_dialog = $( this );

							$( this ).closest( ".ui-dialog" ).append( '<div class="cr-preloader-overlay in-relative-block" id="plo_delete_challenge"><div class="cr-preloader"></div></div>' );
							var preloader = $( this ).closest( ".ui-dialog" ).find( '.cr-preloader-overlay#plo_delete_challenge' );
							$( preloader ).fadeIn();

							jQuery.ajax(
								{
									type: "POST",
									url: cr_params.ajax_url,
									data: params,
									dataType: 'json',
									success: function (responce) {
										// console.log('responce', responce);
										if (responce.success) {
											// alert("The challenge successfully deleted");
											$( the_dialog ).dialog( "close" );
											window.location.href = window.location.href.replace(); // reload without history
										} else {
											alert( responce.errors.join( '; ' ) );
											$( the_dialog ).dialog( "close" );
										}
										$( preloader ).fadeOut();
									},
									error: function (jqXHR, exception) {
										$( preloader ).fadeOut();
										console.warn( 'delete challenge ajax error' );
									},
								}
							);
						}
					}
					],
					open: function () {
						$( this ).find( ".content" ).text( "Are you sure to delete the challenge?" );
					},
					close: function () {},
				}
			);
		}
	)

	// enter challenge results
	.on(
		'click',
		'.cr-challenge-item .cr-challenge-action.record_result',
		function (e) {
			e.preventDefault();
			var challenge = $( this ).closest( ".cr-challenge-item" ).data( "challenge" );
			// console.log('challenge', challenge);

			// var enter_results_dialog = $('.cr-dialog-enter-results').first().dialog({
			$( '#cr-dialog-enter-results-' + challenge.piramid_id ).first().dialog(
				{
					dialogClass: "cr-dialog-confirm",
					modal: true,
					width: 340,
					open: function () {
						$( this ).find( ".challenger-name" ).text( challenge.challenger.wp_user.display_name );
						$( this ).find( ".challenged-name" ).text( challenge.challenged.wp_user.display_name );
						$( this ).find( "input#cr-results-winner-challenger" ).val( challenge.challenger.player_id );
						$( this ).find( "input#cr-results-winner-challenged" ).val( challenge.challenged.player_id );
						var sets_list_el  = $( this ).find( ".sets-list" );
						var set_item_html = crerfsi_html
						.replace( /#challenger_id#/g, challenge.challenger.player_id )
						.replace( /#challenged_id#/g, challenge.challenged.player_id );
						$( sets_list_el ).html( set_item_html );
					},
					buttons: [{
						text: "Close",
						click: function () {
							$( this ).dialog( "close" );
						}
					},
					{
						// Submit
						text: "Submit",
						type: "submit",
						click: function () {
							e.preventDefault();
							var the_dialog = $( this );
							$( this ).closest( ".ui-dialog" ).append( '<div class="cr-preloader-overlay in-relative-block" id="plo_enter_result"><div class="cr-preloader"></div></div>' );
							var preloader = $( this ).closest( ".ui-dialog" ).find( '.cr-preloader-overlay#plo_enter_result' );
							$( preloader ).fadeIn();

							var form      = $( this ).find( ".cr-form.cr-enter-results-form" );
							var form_data = new FormData( $( form )[0] );
							form_data.append( 'challenge_id', challenge.id );
							form_data.append( 'action', 'enter_challenge_result' );

							var validation = validate_enter_results_form( form );
							if ( ! validation.is_valid) {
								  alert( validation.mess );
								  $( preloader ).fadeOut();
								  return;
							}

							jQuery.ajax(
								{
									type: "POST",
									url: cr_params.ajax_url,
									processData: false,
									contentType: false,
									data: form_data,
									dataType: 'json',
									success: function (responce) {
										// console.log('responce', responce);
										if (responce.success) {
											alert( "The results of the challenge successfully saved" );
											$( the_dialog ).dialog( "close" );
											window.location.href = window.location.href.replace(); // reload without history
										} else {
											alert( "Faild to save the results of the challenge" );
											// $(the_dialog).dialog( "close" );
										}
										$( preloader ).fadeOut();
									},
									error: function (jqXHR, exception) {
										$( preloader ).fadeOut();
										console.warn( 'enter_challenge_result ajax error' );
									},
								}
							);
						}
					}
					],
					close: function () {},
				}
			);
		}
	);

	/*
	* Functions
	*/
	// validate Accept the challenge form
	function validate_accept_challenge_form(form) {
		var form_data = new FormData( $( form )[0] );
		var is_valid  = true;
		var mess      = new Array();

		if ( ! form_data.get( "cr_game[date]" )) {
			is_valid = false;
			// mess.push( cr_params.trans["Game Date is required"] );
			mess.push( "Game Date is required" );
		}
		if ( ! form_data.get( "cr_game[court_id]" )) {
			is_valid = false;
			// mess.push( cr_params.trans["Court is required"] );
			mess.push( "Court is required" );
		}
		if ( ! form_data.get( "cr_game[time][h]" )) {
			is_valid = false;
			// mess.push( cr_params.trans["Game Time is required"] );
			mess.push( "Game Time is required" );
		}
		var res = {
			"is_valid": is_valid,
			"mess": mess.join( ", " )
		}
		return res;
	}

	// validate Enter the challenge results form
	function validate_enter_results_form(form) {
		var form_data = new FormData( $( form )[0] );
		var is_valid  = true;
		var mess      = new Array();

		if ( ! form_data.get( "cr_results[winner]" )) {
			is_valid = false;
			// mess.push( cr_params.trans["Winner is required"] );
			mess.push( "Winner is required" );
		}

		var res = {
			"is_valid": is_valid,
			"mess": mess.join( ", " )
		}
		return res;
	}

	// helper: get form values as object{name: value, ...}
	$.fn.serializeObject = function () {
		var o = {};
		var a = this.serializeArray();
		$.each(
			a,
			function () {
				if (o[this.name]) {
					if ( ! o[this.name].push) {
						o[this.name] = [o[this.name]];
					}
					o[this.name].push( this.value || '' );
				} else {
					o[this.name] = this.value || '';
				}
			}
		);
		return o;
	};

})( jQuery );
