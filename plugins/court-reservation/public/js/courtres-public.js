(function ($) {
	'use strict';

	$( document ).ready(
		function () {

			$( 'body' ).on(
				'click',
				'.ui-widget-overlay',
				function () {
					$( ".ui-dialog-titlebar-close" ).trigger( 'click' );
				}
			);

			var cr_params = (typeof courtres_params !== 'undefined') ? courtres_params : null;
			if (cr_params && cr_params.cr_ids.length > 0) {
				for (var i = 0; i < cr_params.cr_ids.length; i++) {
					var cr_id = cr_params.cr_ids[i];
					dialogs( cr_id );
					actions( cr_id );
					actions2( cr_id );
				}
			}

			// init actions

			function actions3(id) {
				var $step = 0;
				// $( '#cr_calendar_1_' + id + ', #cr_calendar_2_' + id + ', #cr_calendar_3_' + id ).on(
				$( '.kalendar-dani' ).on(
					'click',
					function () {
						console.log(this);
						$(".kalendar-dani").css("color","darkgray");
						$(".kalendar-dani").css("font-weight","normal");
						$("#" + this.id).css("color","black");
						$("#" + this.id).css("font-weight","bold");
						var $cr_days = $( this );
						$cr_days.append( '<img src="' + window.courtres_params.cr_url + '/images/spinner.gif" />' );
						$cr_days.addClass( 'button--active' );
						$step = $( this ).attr("data-day");
						$.ajax(
							{
								type: "GET",
								url: courtres_params.ajax_url,
								data: {
									id: id,
									action: 'ajax_cr_navigator2',
									navigator: $cr_days.data( 'navigator' ),
									navigator_step: $step
								},
								success: function (cnt) {
									$( '#cr-reservations-' ).fadeOut(
										'slow',
										function () {
											$( this ).replaceWith( cnt );
											$( '#cr-table-' ).find( '#cr-today-my' ).html( $( '#cr-reservations-' ).data( 'navigator-my' ) );
											dialogs( id );
											$( '#cr-reservations-' ).fadeIn( 1000 );
											$cr_days.find( 'img' ).remove();
											$cr_days.removeClass( 'button--active' );
											console.log( cnt );
										}
									);
								},
								error: function (err) {
									console.error( err.responseText );
								}
							}
						);
					}
				)
			}

			function actions2(id) {
				var $step = 0;
				// $( '#cr_calendar_1_' + id + ', #cr_calendar_2_' + id + ', #cr_calendar_3_' + id ).on(
				$( '.kalendar-dani' ).on(
					'click',
					function () {
						$(".kalendar-dani").css("color","darkgray");
						$(".kalendar-dani").css("font-weight","normal");
						$("#" + this.id).css("color","black");
						$("#" + this.id).css("font-weight","bold");
						var $cr_days = $( this );
						$cr_days.append( '<img src="' + window.courtres_params.cr_url + '/images/spinner.gif" />' );
						$cr_days.addClass( 'button--active' );
						$step = $( this ).attr("data-day");
						$( '#cr-reservations-' + id + ' td' ).each(
							function (i, row) {
								// $(row).fadeOut(300*i);
								$( row ).animate(
									{
										left: '+=100',
										opacity: '0'
									},
									300 * i
								);
							}
						);

						if (id.includes("_")) { var akcija="ajax_cr_navigator_full_view"; } else { var akcija="ajax_cr_navigator"; }

						$.ajax(
							{

								type: "GET",
								url: courtres_params.ajax_url,
								data: {
									id: id,
									action: akcija,
									navigaor: $cr_days.data( 'navigator' ),
									navigator_step: $step
								},
								success: function (cnt) {
									$( '#cr-reservations-' + id ).fadeOut(
										'slow',
										function () {
											$( this ).replaceWith( cnt );
											$( '#cr-table-' + id ).find( '#cr-today-my' ).html( $( '#cr-reservations-' + id ).data( 'navigator-my' ) );
											dialogs( id );
											$( '#cr-reservations-' + id ).fadeIn( 1000 );
											$cr_days.find( 'img' ).remove();
											$cr_days.removeClass( 'button--active' );
										}
									);
								},
								error: function (err) {
									console.error( err.responseText );
								}
							}
						);
					}
				)
			}

			function actions(id) {
				// const $url_navigator = $('#cr-days-prev-' + id).data('action');
				// console.log($url_navigator);
				var $step = 0;
				$( '#cr-days-prev-' + id + ', #cr-days-prev-month-' + id + ', #cr-days-today-' + id + ', #cr-days-next-' + id + ', #cr-days-next-month-' + id ).on(
					'click',
					function () {
						var $cr_days = $( this );
						$cr_days.append( '<img src="' + window.courtres_params.cr_url + '/images/spinner.gif" />' );
						$cr_days.addClass( 'button--active' );
						if ($cr_days.data( 'navigator' ) === 'prev') {
							$step -= parseInt( $( "#cr-table-" + id ).data( 'navigator-step' ) ) || 0;
							$step  = $step < 0 ? 0 : $step;
						} else if ($cr_days.data( 'navigator' ) === 'next') {
							$step = +$step + (parseInt($("#cr-table-" + id).data('navigator-step')) || 0);
						} else if ($cr_days.data( 'navigator' ) === 'prev-month') {
							$step = +$step - (parseInt($cr_days.data('day')) || 0);
						} else if ($cr_days.data( 'navigator' ) === 'next-month') {
							$step = +$step + (parseInt($cr_days.data('day')) || 0);
						} else {
							$step = 0;
						}
						$( '#cr-reservations-' + id + ' td' ).each(
							function (i, row) {
								// $(row).fadeOut(300*i);
								$( row ).animate(
									{
										left: '+=100',
										opacity: '0'
									},
									300 * i
								);
							}
						);

						if (id.includes("_")) { var akcija="ajax_cr_navigator_full_view"; } else { var akcija="ajax_cr_navigator"; }
						$.ajax(
							{
								type: "GET",
								url: courtres_params.ajax_url,
								data: {
									id: id,
									action: akcija,
									navigaor: $cr_days.data( 'navigator' ),
									navigator_step: $step
								},
								success: function (cnt) {
									$( '#cr-reservations-' + id ).fadeOut(
										'slow',
										function () {
											$( this ).replaceWith( cnt );
											$( '#cr-table-' + id ).find( '#cr-today-my' ).html( $( '#cr-reservations-' + id ).data( 'navigator-my' ) );
											$( '#cr-reservations-' + id ).fadeIn( 1000 );
											$cr_days.find( 'img' ).remove();
											$cr_days.removeClass( 'button--active' );
											// console.log( cnt );
										}
									);
								

									$.ajax(
										{
											type: "GET",
											url: courtres_params.ajax_url,
											data: {
												id: id,
												action: 'ajax_cr_navigator_calendar',
												navigaor: $cr_days.data( 'navigator' ),
												navigator_step: $step
											},
											success: function (kalen) {
												$( '#drugi_kal_' + id ).fadeOut(
													'fast',
													function () {
														$( this ).replaceWith( kalen );
														if ($cr_days.data( 'navigator' ) === 'prev-month') { $( '#drugi_kal_' + id ).css("display", "block"); }
														if ($cr_days.data( 'navigator' ) === 'next-month') { $( '#drugi_kal_' + id ).css("display", "block"); }
														$( '.kalendar-dani' ).on(
															'click',
															function () {
															$(".kalendar-dani").css("color","darkgray");
															$(".kalendar-dani").css("font-weight","normal");
															$("#" + this.id).css("color","black");
															$("#" + this.id).css("font-weight","bold");
															var $cr_days = $( this );
															$step = $( this ).attr("data-day");

															$.ajax(
															{

																type: "GET",
																url: courtres_params.ajax_url,
																data: {
																	id: id,
																	action: akcija,
																	navigaor: $cr_days.data( 'navigator' ),
																	navigator_step: $step
																},
																success: function (cnt) {
																	$( '#cr-reservations-' + id ).fadeOut(
																	'slow',
																	function () {
																		$( this ).replaceWith( cnt );
																		$( '#cr-table-' + id ).find( '#cr-today-my' ).html( $( '#cr-reservations-' + id ).data( 'navigator-my' ) );
																		dialogs( id );
																		$( '#cr-reservations-' + id ).fadeIn( 1000 );
																		$cr_days.find( 'img' ).remove();
																		$cr_days.removeClass( 'button--active' );
																		// console.log( cnt );
																		}
																	);
																},
																error: function (err) {
																console.error( err.responseText );
																}
															});
														});
														dialogs( id );
													}
												);
											},
											error: function (err) {
												console.error( err.responseText );
											}
										}
									);

								},
								error: function (err) {
									console.error( err.responseText );
								}
							}
						);

					}
				)
			}

			// init dialogs for each shortcode
			function dialogs(id) {
				const $cr_table         = $( "#cr-table-" + id );
				const $cr_dlg_reserve   = $( "#cr-dialog-reserve-" + id );
				const $cr_frm_reserve   = $( "#cr-form-reserve-" + id );
				const $url_reserve      = $cr_frm_reserve.attr( 'action' );
				const $court_hour_close = $cr_table.attr( 'data-hour-close' );

				$cr_table.find( "table.reservations a.delete" ).click(
					function () {
						$.ajax(
							{
								type: "POST",
								url: $url_reserve,
								data: "action=add_reservation&id=" + $( this ).attr( 'data-id' ) + "&delete=true",
								success: function (msg) {
									window.location.href = window.location.href.replace( window.location.hash, "" );
								},
								error: function (err) {
									console.error( err.responseText );
								}
							}
						);
					}
				);

				$cr_table.find( "table.reservations a.reservation" ).click(
					function () {
						var d = $cr_dlg_reserve;
						d.find( '[name="day"]' ).val( $( this ).attr( 'data-day' ) );
						d.find( '[name="hour"]' ).val( $( this ).attr( 'data-hour' ) );
						d.find( '[name="date"]' ).val( $( this ).attr( 'data-date' ) );
						d.find( '#date' ).html( $( this ).attr( 'data-date-display' ) );
						d.find( '#time' ).html( $( this ).attr( 'data-time' ) );
						d.find( '[name="minstart"]' ).val( $( this ).attr( 'data-min-start' ) );
						d.find( '[name="minplayer"]' ).val( $( this ).attr( 'data-min-player' ) );
						d.find( '[name="courtid"]' ).val( $( this ).attr( 'court-id' ) );
						var halfhour = d.find( '[name="halfhour"]' ).val();

						var courtid    = $( this ).attr( 'court-id' );
						var start_h    = $( this ).attr( 'data-hour' );
						var start_m    = $( this ).attr( 'data-min-start' );
						var min_player = $( this ).attr( 'data-min-player' );
						var start_ts   = start_h * 3600 + start_m * 60;

						// Reservation Type Select
						// (RA) Adding partner-select after reservation type selected
						$( ".cr-dialog-reserve" ).find( ".type-depending-row" ).remove();
						$( ".cr-dialog-reserve" ).find( "form" )[0].reset();

						d.find( ".reservation-type-select" ).on(
							'change',
							function () {
								var selected_option = $( this ).find( 'option:selected' );
								var max_players     = $( selected_option ).data( "maxplayers" );
								var min_players     = $( selected_option ).data( "minplayers" );
								var duration_ts     = $( selected_option ).data( "duration" );
								var form_el         = $( this ).closest( '.resform' );
								var gmrh_args       = {
									court_id: courtid,
									is_halfhour: halfhour,
									start_ts: start_ts,
									duration_ts: duration_ts,
									player_counter: 0,
									max_players: max_players,
									min_players: min_players
								};

								$( form_el ).find( ".type-depending-row" ).remove();
								if ($( this ).val()) {

									if (gmrh_args.max_players > 0) {
										get_more_rows_html( gmrh_args );
									} else {
										$( '<div/>' ).html( '<div class="error">You need to setup the Max. Number of Other Players option for this type of reservations</div>' ).dialog(
											{
												dialogClass: "cr-dialog-alert",
												open: function () {
													$( "body" ).append( '<div class="cr-preloader-overlay" id="plo-error-dialog"></div>' );
													$( "#plo-error-dialog" ).css( {"z-index": $( this ).closest( ".cr-dialog-alert" ).css( "z-index" ) - 1} );
												},
												close: function () {
													$( ".cr-preloader-overlay#plo-error-dialog" ).remove();
												},
											}
										);
									}
								}
							}
						);

						d.dialog( 'open' );
						updatePartnersList( $url_reserve );
					}
				);

				var dialogButtons = {
					'cancel': {
						text: courtres_params.cr_btn_cancel, // 'Abbruch',
						click: function () {
							$( this ).dialog( 'close' );

						},
						class: 'cr-ui-button'
					},
					'save': {
						text: courtres_params.cr_btn_save, // 'Speichern',
						click: function () {
							var preloader = $( '.cr-preloader-overlay#plo-add-reserv' );
							$( preloader ).fadeIn();
							var spinner = '<img src="' + window.courtres_params.cr_url + '/images/spinner.gif" />'
							$( '#cr-ui-save' ).append( spinner );

							var validation = validate( $cr_frm_reserve );
							if (validation.is_valid) {
								$.ajax(
									{
										type: "POST",
										url: $url_reserve,
										data: $cr_frm_reserve.serialize(), // $(this).find('#cr-form-reserve').serialize(),
										success: function (msg) {
											$( preloader ).fadeOut();
											$( '#cr-ui-save' ).removeAttr( "disabled" );
											// console.log(msg);
											window.location.href = window.location.href.replace( window.location.hash, "" ); // window.location.href;
										},
										error: function (err) {
											// console.error(err.responseText);
											$( '<div/>' ).html( '<div class="error">' + err.responseText + '</div>' ).dialog(
												{
													dialogClass: "cr-dialog-alert",
													open: function () {
														// to block content under error dialog
														$( "body" ).append( '<div class="cr-preloader-overlay" id="plo-error-dialog"></div>' );
														$( "#plo-error-dialog" ).css( {"z-index": $( this ).closest( ".cr-dialog-alert" ).css( "z-index" ) - 1} );
													},
													close: function () {
														$( '#cr-ui-save' ).find( 'img' ).remove();
														$( this ).fadeOut( 600 );
														$( preloader ).fadeOut();
														$( ".cr-preloader-overlay#plo-error-dialog" ).remove();
													},
												}
											);
										}
									}
								);

							} else {
								$( '<div/>' ).html( '<div class="error">' + validation.mess + '</div>' ).dialog(
									{
										dialogClass: "cr-dialog-alert",
										open: function () {
											// to block content under error dialog
											$( "body" ).append( '<div class="cr-preloader-overlay" id="plo-error-dialog"></div>' );
											$( "#plo-error-dialog" ).css( {"z-index": $( this ).closest( ".cr-dialog-alert" ).css( "z-index" ) - 1} );
										},
										close: function () {
											$( '#cr-ui-save' ).find( 'img' ).remove();
											$( this ).fadeOut( 600 );
											$( preloader ).fadeOut();
											$( ".cr-preloader-overlay#plo-error-dialog" ).remove();
										},
									}
								);
							}
						},
						id: 'cr-ui-save',
						class: 'cr-ui-button',
						type: 'submit'
					}
				}

				$cr_dlg_reserve.dialog(
					{
						modal: true,
						minHeight: '300px',
						width: '350px',
						closeOnEscape: true,
						autoOpen: false,
						dialogClass: 'cr-dialog-reserve',
						show: function () {
							$( this ).fadeIn( 1000 );
						},
						hide: function () {
							$( this ).fadeOut( 600 );
						},
						close: function () {
							$( this ).find( ".reservation-type-select" ).off( 'change' );
						},
						open: function () {
							$( this ).closest( ".ui-dialog" )
							.find( ".ui-dialog-titlebar-close" )
							.html( '<span class="ui-button-icon ui-icon ui-icon-closethick"></span><span class="ui-button-icon-space"> </span>' );
						}
						// buttons:
					}
				);

				if ($( 'body' ).hasClass( 'logged-in' )) {
					$cr_dlg_reserve.dialog( 'option', 'buttons', dialogButtons );
				}

				// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				// if not logged in
				if ( ! $( 'body' ).hasClass( 'logged-in' )) {
					const $cr_dlg_login = $( '#cr-dialog-login-' + id );
					const $cr_frm_login = $( "#cr-form-login-" + id );
					const $url_login    = $cr_frm_login.attr( 'action' );
					// ajax login
					// Show the login dialog box on click

					$cr_dlg_reserve.find( '#cr-show-login' ).on(
						'click',
						function (e) {
							var d  = $cr_dlg_login;
							var un = $cr_dlg_reserve.find( '#playerid' ).find( ":selected" ).val();
							if (un !== '0') {
								d.find( '#username' ).val( un );
							}
							d.dialog( 'open' );
						}
					)

					$cr_dlg_login.dialog(
						{
							modal: true,
							minHeight: '300px',
							width: '350px',
							closeOnEscape: true,
							autoOpen: false,
							dialogClass: 'cr-dialog-reserve',
							show: function () {
								$( this ).fadeIn( 1000 );
							},
							hide: function () {
								$( this ).fadeOut( 600 );
							},
							open: function () {
								$( this ).closest( ".ui-dialog" )
								.find( ".ui-dialog-titlebar-close" )
								.html( '<span class="ui-button-icon ui-icon ui-icon-closethick"></span><span class="ui-button-icon-space"> </span>' );
							},
							buttons: {
								'Login': {
									text: 'Login',
									click: function () {
										$.ajax(
											{
												type: "POST",
												url: $url_login,
												data: $cr_frm_login.serialize(), // $(this).find('#cr-form-login').serialize(),
												success: function (msg) {
													// console.log(msg);
													var j = jQuery.parseJSON( msg );
													if ( ! j.loggedin) {
														$cr_dlg_login.find( '#login-error-text' ).text( j.message );
														$cr_dlg_login.find( '#login-error' ).show();
													} else {
														$cr_dlg_login.find( '#login-error-text' ).text( '' );
														$cr_dlg_login.find( '#login-error' ).hide();
														$cr_dlg_reserve.find( '#cr-show-login' ).remove();
														$cr_dlg_reserve.find( '#playerid' ).replaceWith( j.display_name );
														// $cr_dlg_reserve.find('#partnerid').find('option:contains(' + j.display_name + ')').remove();
														// $cr_dlg_reserve.find('#partnerid2').find('option:contains(' + j.display_name + ')').remove();
														// $cr_dlg_reserve.find('#partnerid3').find('option:contains(' + j.display_name + ')').remove();
														$cr_dlg_reserve.dialog( 'option', 'buttons', dialogButtons );
														$cr_dlg_login.dialog( 'close' );
													}
													// console.log(msg)
												},
												error: function (err) {
													console.error( err.responseText );
												}
											}
										);
									},
									class: 'cr-ui-button'
								}
							}
						}
					);
				}
			}

		}
	);
	// 16.03.2019, astoian - to time
	function toTimeDialog(dt) {
		var option_ui_dateformat = courtres_params.cr_option_ui_dateformat;
		var timeformat           = 'HH:mm';
		if (option_ui_dateformat === 'm.d.') {
			timeformat = 'hh:mm a';
		}
		return;
		// return moment(dt).format(timeformat);
		// var h = (dt.getHours() < 10 ? '0' : '') + dt.getHours(),
		// m = (dt.getMinutes() < 10 ? '0' : '') + dt.getMinutes();
		// return h + ':' + m;
	}
	// 01.03.2019, astoian
	// make color darker/lighter
	const RGB_Linear_Shade = function (p, c) {
		var i = parseInt,
			r = Math.round,
			a = c.split( "," )[0],
			b = c.split( "," )[1],
			c = c.split( "," )[2],
			d = c.split( "," )[3],
			// [a, b, c, d] = c.split(","),
			P = p < 0,
			t = P ? 0 : 255 * p,
			P = P ? 1 + p : 1 - p;
		return "rgb" + (d ? "a(" : "(") + r( i( a[3] == "a" ? a.slice( 5 ) : a.slice( 4 ) ) * P + t ) + "," + r( i( b ) * P + t ) + "," + r( i( c ) * P + t ) + (d ? "," + d : ")");
	};
	// make the background color darker of tooltip
	// 01.03.2019, astoian
	if ($( '.blocked' ).length) {
		var tt_darker = RGB_Linear_Shade( -0.2, $( '.blocked' ).css( 'background-color' ) );
		$( '.cr-tooltiptext' ).css( 'background-color', tt_darker );
	}

	// reservation form validation
	// RA 2020-05-08
	function is_valid(form){
		var is_valid = true;
		var data     = form.serializeObject();
		// check if at least one partner selected
		if ( $( 'form select[name="partnerid"]' ).prop( 'required' ) ) {
			if (typeof data.partnerid == "undefined" || ! (data.partnerid > 0)) {
				is_valid = false;
			}
		}
				return is_valid;
	}

	// exrended reservation form validation with errors messages
	// RA 2021-01-19
	function validate(form){
		var is_valid = true;
		var mess     = "";
		var data     = form.serializeObject();
		// console.log('data', data);

		// check if type of reservation selected
		if (typeof(data.type) == "undefined" || ! data.type) {
			is_valid = false;
			mess     = 'Select required Type';
		}

		// check partner-select
		if ( $( form ).find( '.partner-select' ).prop( 'required' ) && typeof(data['partners[]']) == "undefined" ) {
			is_valid = false;
			mess     = 'Select at least one partner';
		}

		// for tests only
		// if(is_valid){
		// is_valid = false; mess = 'All good, stop saving for tests';
		// }

		var res = {"is_valid": is_valid, "mess": mess}
		return res;
	}

	function updatePartnersList($ajax_url){
		var params = {
			"action": "get_players_select_options",
		}
		$.ajax(
			{
				type: "POST",
				url: $ajax_url,
				data: params,
				dataType: 'html',
				success: function(responce){
					// console.log('responce', responce);
					$( 'select.partner-select' ).html( responce );
				},
				error: function (jqXHR, exception) {
					console.warn( 'ajax error in updatePartnersList()' );
				},
			}
		);
	}

	// get sub tasks html
	function get_more_rows_html(args){

		// var preloader = $('.cr-preloader-overlay#plo_customer_tags');
		var container_el = $( ".cr-dialog-reserve" ).find( ".form-fields-table" );
		var preloader    = $( '.cr-preloader-overlay#plo-add-reserv' );
		$( preloader ).fadeIn();

		args.action = "get_more_rows_html";
		jQuery.ajax(
			{
				type: "POST",
				url: courtres_params.ajax_url,
				data: args,
				dataType: 'html',
				success: function(responce){
					// console.log('responce', responce);
					$( container_el ).find( ".type-depending-row" ).remove();
					$( container_el ).append( responce );
					$( container_el ).find( ".max-players-quantity" ).text( args.max_players );
					$( container_el ).find( "#player-min" ).val( args.min_players );
					$( container_el ).find( ".partner-select" ).attr( "data-max", args.max_players );
					$( container_el ).find( ".partner-select" ).attr( "data-min", args.min_players );

					if (args.min_players > 0) {
						var min_player_number = "min.: " + args.min_players;
						$( container_el ).find( "#part_min" ).text( min_player_number );
					}

					// to check quanity
					$( container_el ).find( '.partner-select' ).on(
						'change',
						function ()
						{

								var selected_options = $( this ).closest( '.partner-select' ).find( "option:selected" );

								// $(container_el).find(".min-players-quantity").text($(selected_options).length);
							if ($( selected_options ).length > args.max_players) {
								$( preloader ).fadeIn();
								$( container_el ).find( '.partner-select option' ).removeAttr( "selected" );
								// show alert
								$( '<div/>' ).html( '<div class="error">You can select upto ' + args.max_players + ' partners only</div>' ).dialog(
									{
										dialogClass: "cr-dialog-alert",
										open: function ()
									{
											// to block content under error dialog
											$( "body" ).append( '<div class="cr-preloader-overlay" id="plo-error-dialog"></div>' );
											$( "#plo-error-dialog" ).css( {"z-index": $( this ).closest( ".cr-dialog-alert" ).css( "z-index" ) - 1} );
										},
										close: function ()
									{
											$( preloader ).fadeOut();
											$( ".cr-preloader-overlay#plo-error-dialog" ).remove();
										},
										}
								);

							} else {
								$( container_el ).find( "#player-number" ).val( $( selected_options ).length );
								var names = [];
								$( selected_options ).each(
									function (index, value)
									{
											names.push( $( this ).text() );
									}
								);
								$( container_el ).find( '.partners-list' ).text( names.join( ", " ) );
							}
						}
					);
					$( preloader ).fadeOut();
				},
				error: function (jqXHR, exception) {
					$( preloader ).fadeOut();
					console.warn( 'get_player_select_html ajax error' );
				},
			}
		);
	}

	// set time select
	function set_time_select(duration){

	}

	// helper: get form values as object{name: value, ...}
	// RA 2020-05-08
	$.fn.serializeObject = function() {
		var o = {};
		var a = this.serializeArray();
		$.each(
			a,
			function() {
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


function show_options(igracici,vrijednost,rb)
{
	for(var i = 0; i<igracici.length; i++) 
	{
        	var igracic = igracici[i];

		if(igracic.innerText == vrijednost) 
		{
            		var skriveni = igracic.getAttribute('data-value');
			document.getElementById("odg_"+rb).value=skriveni;

			var list = document.getElementsByClassName("odg_"+rb);
			var n;
			for (n = 0; n < list.length; ++n) {
    				list[n].value=skriveni;
			}
            		break;
        	}
    	}
}

function makni(datalistevi,skriveni_)
{

	var polja_=document.getElementById("polja_").options;
	var polja_ociscene="";
	var polja_odabrana=[];

	for(var i = 0; i<skriveni_.length; i++) 
	{
		var igrac_key=skriveni_[i];
		var igrac_vrijednost=document.getElementById(igrac_key).value;
		if (igrac_vrijednost!="") { polja_odabrana[i]=igrac_vrijednost; }
	}


	for(var i = 0; i<polja_.length; i++) 
	{
		var polje_=polja_[i];
		var polje_value=polja_[i].getAttribute("data-value");
		if (polja_odabrana.includes(polje_value)==false)
		{
			polja_ociscene=polja_ociscene + polje_.outerHTML;
		}
	}

	for(var i = 0; i<datalistevi.length; i++) 
	{
		var datalist_key=datalistevi[i];
		document.getElementById(datalist_key).innerHTML=polja_ociscene;
	}
}

function nebitno()
{
		var poljeel=document.querySelector("#igrac2");
		poljeel.children[0]=null;

		var poljeel=document.querySelector("#igrac3");
		poljeel.children[0]=null;

	for(var i = 0; i<polja.length; i++) 
	{
		var polje=polja[i];
		console.log(polje);
		var poljeel=document.querySelector("#"+polje);
		poljeel.children[0]=null;
		console.log(poljeel);
		var poljeel2=document.querySelector("#igrac2");
		poljeel2.children[0]=null;
    	}
}
