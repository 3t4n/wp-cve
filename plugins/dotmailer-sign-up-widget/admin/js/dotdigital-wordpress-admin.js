( function( $ ) {
	'use strict';

	$( '.multiselector' ).change( function() {
		const element = $( this );
		const table_body = element.closest( 'table' ).find( 'tbody' );

		$( table_body ).find( '.toggle-inputs' ).each( function() {
			const row = $( this );
			const row_inputs = $( row ).find( 'input' );
			const row_checkbox = $( row ).find( 'input[type="checkbox"]' );
			if ( element.is( ':checked' ) ) {
				row_checkbox.prop( 'checked', true );
				row_inputs
					.filter( () => ! $( element ).is( row_checkbox ) )
					.each( () => $( element ).removeAttr( 'disabled' ) );
			} else {
				row_checkbox.prop( 'checked', false );
				row_inputs
					.filter( () => ! $( element ).is( row_checkbox ) )
					.each( () => $( element ).attr( 'disabled', 'disabled' ) );
			}
		} );
	} );

	$( `input[toggle-row-inputs]` ).on( 'change', function() {
		const element = $( this );
		const row = element.closest( 'tr' );
		const checked = element.is( ':checked' );

		$( row ).find( 'input' ).each( function() {
			const input = $( this );
			if ( input.is( element.first() ) ) {
				return;
			}
			if ( checked ) {
				input.removeAttr( 'disabled' );
			}
			if ( ! checked ) {
				input.attr( 'disabled', 'disabled' );
			}
		} );
	} );

	$( 'tbody.sortable' ).sortable( {
		axis: 'y',
		cursor: 'move',
		handle: '.handle',
		helper( e, ui ) {
			ui.children().each( function() {
				$( this ).width( $( this ).width() );
			} );
			return ui;
		},
		start( event, ui ) {
			ui.placeholder.html( '<td colspan="3"></td>' );

			ui.item.css( {
				'background-color': '#f6f6f6',
				border: '1px solid #ddd',
			} );
		},
	} );

	$( '.form-group-radio' ).change( function() {
		const element = $( this );
		const group = element.closest( '.radio-selection-group' );
		const form = element.closest( 'form' );
		$( group ).attr( 'data-selected', element.val() );
		$( form ).find( '.radio-selection-group' ).each( function() {
			const radio_group = $( this );

			if ( radio_group.is( group ) ) {
				radio_group.find( 'input,select' ).each( function() {
					const input = $( this );
					input.removeAttr( 'disabled' );
				} );
				return;
			}

			radio_group.removeAttr( 'data-selected' );
			radio_group.find( 'input,select' ).each( function() {
				if ( $( this ).is( 'input[type="radio"]' ) ) {
					return;
				}
				const input = $( this );
				input.attr( 'disabled', 'disabled' );
			} );
		} );
	} );
}( jQuery ) );
