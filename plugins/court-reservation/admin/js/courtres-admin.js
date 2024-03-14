(function( $ ) {
	'use strict';

	$( document ).ready(
		function(){
			$( 'input[name="delete"]' ).click(
				// var jezik=document.getElementsByTagName('html')[0].getAttribute('lang');
				// alert ("kk");
				function(e) {
					var jezik=document.getElementsByTagName('html')[0].getAttribute('lang');
					if (jezik=="de" || jezik=="de_DE") { var poruka="Wirklich löschen?"; }
					else { var poruka="Really delete?"; }
					return confirm( poruka );
				}
			);
			// 21.05.2019, astoian - colors reset input
			$( 'input.color-input' ).on(
				'keyup',
				function() {
					// on blur, if there is no value, set the defaultText
					if ($( this ).val() == '') {
						$( this ).removeAttr( "style" );
					}
				}
			);
			// 21.05.2019, astoian - checkbox link - button
			$( "input[name=option_ui_link]" ).change(
				function() {
					if (this.checked) {
						$( "#option_ui_button_clr_tr" ).show();
						$( "#option_ui_link_clr_tr" ).hide();
					} else {
						$( "#option_ui_button_clr_tr" ).hide();
						$( "#option_ui_link_clr_tr" ).show();
					}
				}
			);
			if ( $( 'input[name="email_notify_players"]' ).is( ":checked" ) ) {
				$( '#email-conf' ).fadeIn();
			} else {
				$( '#email-conf' ).fadeOut();
			}
			$( 'input[name="email_notify_players"]' ).change(
				function(e) {
					var elem = $( '#email-conf' );
					if ($( this ).is( ":checked" )) {
						elem.fadeIn();
					} else {
						elem.fadeOut();
					}
				}
			);

			// +RA 2020-05-09 datepicker
			$( '.datepicker' ).datepicker( { dateFormat: $( '.datepicker' ).data( 'eventdateformat' ) } );

			// for arrange players in piramids
			$( ".sortable" ).sortable(
				{
					containment: "parent",
					cursor: "grabbing",
					opacity: 0.8,
					tolerance: "pointer",
					classes: {
						"ui-sortable-helper": "dragging",
					},
					update: function( event, ui ) {
						$( this ).find( "li.cr-selected-player-item" ).each(
							function( i, el ){
								$( el ).find( "input.player-sort" ).val( i );
							}
						);
					}
				}
			);
			$( ".sortable" ).disableSelection();

		}
	)

	// +RA 2020-05-31 show-hide add new custom reservation type input
	.on(
		'click',
		'button.cr-show-new-reserv-type-input',
		function(e){
			if ($( this ).hasClass( 'opened' )) {
				$( this ).closest( '.cr-new-reserv-type-block' ).find( '.cr-new-reserv-type-input-block' ).fadeOut();
				$( this ).removeClass( 'opened' );
			} else {
				$( this ).closest( '.cr-new-reserv-type-block' ).find( '.cr-new-reserv-type-input-block' ).fadeIn();
				$( this ).addClass( 'opened' );
			}
		}
	)

	// +RA 2020-05-31 add new custom reservation type
	.on(
		'click',
		'button.cr-save-new-reserv-type',
		function(e){
			e.stopPropagation();
			e.preventDefault();
			var block = $( this ).closest( ".cr-new-reserv-type-input-block" );
			var input = $( block ).find( 'input[name="custom_reservation_type"]' );
			$( input ).prop( 'disabled', true );
			var params = {
				"action": "edit_reservation_type",
				"action_type": "add",
				"reservation_type" : input.val()
			}
			$.ajax(
				{
					type: "POST",
					url: js_data.ajax_url,
					data: params,
					dataType: 'json',
					success: function(responce){
						// console.log('responce', responce);
						if (responce.errors.length) {
							alert( responce.errors.join( '; ' ) );
						} else {
							// success
							location.reload();
							return false;
						}
						$( input ).prop( 'disabled', false );
					},
					error: function (jqXHR, exception) {
						$( input ).prop( 'disabled', false );
						console.warn( 'Add new reservation type ajax error' );
					},
				}
			);
		}
	)

	// +RA 2020-05-31 delete reservation type
	.on(
		'click',
		'.cr-delete-reserv-type-link',
		function(e){
			e.stopPropagation();
			e.preventDefault();
			var block = $( this ).closest( "li" );
			$( block ).addClass( 'disabled' );
			var input  = $( block ).find( 'input[type="checkbox"]' );
			var params = {
				"action": "edit_reservation_type",
				"action_type": "delete",
				"reservation_type" : input.val()
			}
			$.ajax(
				{
					type: "POST",
					url: js_data.ajax_url,
					data: params,
					dataType: 'json',
					success: function(responce){
						// console.log('responce', responce);
						if (responce.errors.length) {
							alert( responce.errors.join( '; ' ) );
						} else {
							// success
							$( block ).remove();
						}
						$( block ).removeClass( 'disabled' );
					},
					error: function (jqXHR, exception) {
						$( block ).removeClass( 'disabled' );
						console.warn( 'Delete new reservation type ajax error' );
					},
				}
			);
		}
	)

	// +RA 2021-02-07 add player to list
	.on(
		'change',
		'.cr-piramid-player-checkbox',
		function(e){
			var checklist_el  = $( this ).closest( ".cr-players-checklist" );
			var checked_item  = $( this ).closest( ".cr-piramid-player-item" );
			var container     = $( ".cr-selected-players ul" );
			var item_template = '<li class="cr-selected-player-item ui-sortable-handle"><span class="player-name">#name#</span><input type="hidden" name="piramid[players][#player_id#][player_id]" class="player-id" value="#player_id#" /><input type="hidden" name="piramid[players][#player_id#][display_name]" class="player-name" value="#name#" /><input type="hidden" name="piramid[players][#player_id#][sort]" class="player-sort" value="#sort#" /></li>';

			if (this.checked) {
				var sort    = $( container ).find( "li.cr-selected-player-item" ).length;
				var content = item_template.replace( /#player_id#/g, $( this ).val() )
				.replace( /#name#/g, $( checked_item ).find( "label" ).text() )
				.replace( /#sort#/g, sort );
				$( container ).append( content );
			} else {
				var selected_item = $( container ).find( 'input[value="' + $( this ).val() + '"]' ).closest( ".cr-selected-player-item" );
				$( selected_item ).remove();
				$( container ).find( "li.cr-selected-player-item" ).each(
					function( i, el ){
						$( this ).find( "input.player-sort" ).val( i );
					}
				);
			}
		}
	); // $(document).ready finished

	// 2021-03-13, astoian - cr_fs freemius
	$( document ).ready(
		function () {
			if (typeof FS !== 'undefined') {
				var handler = FS.Checkout.configure(
					{
						plugin_id: '3086',
						plan_id: '4903',
						public_key: 'pk_b5c504d97853f6130b63fd7344155'
					}
				);

				$( '#cr-purchase-premium' ).on(
					'click',
					function (e) {
						handler.open(
							{
								name: 'Court Reservation Premium',
								licenses: '1',
								// You can consume the response for after purchase logic.
								success: function (response) {
									// alert(response.user.email);
								}
							}
						);
						e.preventDefault();
					}
				);
				$( '#cr-purchase-ultimate' ).on(
					'click',
					function (e) {
						handler.open(
							{
								name: 'Court Reservation Ultimate',
								licenses: '1',
								// You can consume the response for after purchase logic.
								success: function (response) {
									// alert(response.user.email);
								}
							}
						);
						e.preventDefault();
					}
				);
			}
		}
	)

})( jQuery );
