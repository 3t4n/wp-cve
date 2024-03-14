/**
 *	Attendance Manager JavaScript Functions
 */

jQuery( document ).ready( function( $ ) {

	$( 'form[id$="_scheduler"] input[name^="attmgr_off"]' ).on( 'click', function() {
		var name = $( this ).attr( 'name' ).replace( /off/g, 'post' );
		var target = 'select[name^="' + name + '"]';
		if ( $( this ).prop( 'checked' ) ) {
			$( target ).attr( 'disabled', 'disabled' );
		} else {
			$( target ).removeAttr( 'disabled' );
		}
	})

	var list = new Array();
	$( 'form[id$="_scheduler"] select[name$="starttime]"]' ).eq(0).children().each( function() {
		list.push( { value: $( this ).val(), text: $( this ).text() } );
	});
	$( 'form[id$="_scheduler"] select[name$="endtime]"]' ).each( function() {
		var name = $( this ).attr( 'name' );
		var endtime = 'select[name="' + name + '"]';
		var starttime = endtime.replace( /endtime/g, 'starttime' );
		var selected = $( endtime + ' option:selected' ).val();

		if ( $( starttime ).val() != '' ) {
			$( endtime ).children().remove();
			$.each( list, function( key, obj ) {
				if( obj['value'] > $( starttime ).val() ) {
					$( endtime ).append( $( '<option>' ).val( obj['value'] ).text( obj['text'] ) );
				}
			});
			if ( selected != '' ) {
				$( endtime + ' option[value="' + selected + '"]' ).prop( 'selected', true );
			} else {
				$( endtime ).prepend( $( '<option>' ).val( '' ).text( '' ) );
				$( endtime + ' option:first-child' ).prop( 'selected', true );
			}
		}
	});

	$( 'form[id$="_scheduler"] select[name$="starttime]"]' ).on( 'change', function() {
		var starttime = $( this );
		var name = starttime.attr( 'name' ).replace( /starttime/g, 'endtime' );
		var endtime = 'select[name="' + name + '"]';
		var selected = $( endtime + ' option:selected' ).val();

		$( endtime ).children().remove();
		$.each( list, function( key, obj ) {
			if( obj['value'] > starttime.val() ) {
				$( endtime ).append( $( '<option>' ).val( obj['value'] ).text( obj['text'] ) );
			}
		});
		if ( selected == '' ) {
			$( endtime + ' option:last-child' ).prop( 'selected', true );
		} else if ( $( endtime + ' option[value="' + selected + '"]' ).length == 0 ) {
			$( endtime + ' option:first-child' ).prop( 'selected', true );
		} else {
			$( endtime + ' option[value="' + selected + '"]' ).prop( 'selected', true );
		}
	})

	$( 'form[id$="_scheduler"] select[name$="endtime]"]' ).on( 'change', function() {
		var endtime = $( this );
		var name = endtime.attr( 'name' ).replace( /endtime/g, 'starttime' );
		var starttime = 'select[name="' + name + '"]';
		var end_selected = endtime.val();
		var start_selected = $( starttime + ' option:selected' ).val();

		if ( end_selected != '' && start_selected == '' ) {
			$( starttime + ' option' ).eq(1).prop( 'selected', true );
		}
	})
})
