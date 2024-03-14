// JavaScript Document

jQuery( document ).ready(function($) {

	$('.gfee_form span.gfee_open_fields').on('click', function() {

		var form_id = $(this).data('form-id');
		
		if ($('#form_fields_' + form_id).css('display') == 'none' ) {
			$('#form_fields_' + form_id).slideDown();
		} else {
			$('#form_fields_' + form_id).slideUp();
		}
		
	});
	
	$('.btn_gfee_export_settings, .btn_gfee_import_settings').on('click', function() {
		var type = $(this).data('type');
		if ( type == 'export' ) {
			$('.gfee_export_import_settings').val( $('.gfee_export_settings').val() );
			$('.btn_gfee_import_now').css('display', 'none');
		} else {
			$('.gfee_export_import_settings').val('');
			$('.btn_gfee_import_now').css('display', 'inline-block');
		}
		$('.gfee_export_import').slideDown();
	});
	
	$('.btn_gfee_cancel_import_export').on('click', function() {
		$('.gfee_export_import').slideUp();
	});
	
	$('.btn_gfee_export_now').on('click', function() {

		var spinner = '<img class="gfee_spin" src="' + gfee_calendar_js_strings.plugin_path + 'images/wpspin_light.gif" />';;
		var html = spinner + ' Generating export...';
		$('.gfee_export_msg').html( html );
		var export_name = $('.export_name').val();

		var date_start = $('.gfee_start_date').val();
		var date_stop = $('.gfee_stop_date').val();
		jQuery.ajax({
			type: 'POST',
			data: {'action': 'gfee_manual_export', 'export_name': export_name, 'date_start': date_start, 'date_stop': date_stop },
			url: gfee_calendar_js_strings.ajaxurl,
			timeout: 15000,
			error: function() {
				var html = 'Failure: export not generated';
				$('.gfee_export_msg').html( html );
			},
			dataType: "html",
			success: function(result){
				if ( result == '' ) {
					var html = 'Failure: export not generated';
				} else {
					window.open(result);
					
					var html = '<a href="' + result + '" target="_blank">' + result + '</a>';
				}
				$('.gfee_export_msg').html( html );
			}
		});
		
	});
	
	
	$('.delete_export').on('click', function() {
		var status = confirm( 'Are you sure you want to remove this export?\n All settings will be removed!' );
		if ( status ) {
			var export_name = $('.export_name').val();
			jQuery.ajax({
				type: 'POST',
				data: {'action': 'gfee_delete_export', 'export_name': export_name },
				url: gfee_calendar_js_strings.ajaxurl,
				timeout: 15000,
				error: function() {
					var html = 'Failure: export not removed';
				},
				dataType: "html",
				success: function(result) {
					$('#export_name option').each(function(index, element) {
						if ( $(element).val() === result ) {
							$(element).remove();
						}
					});
					var export_name = $('.export_name').val('');
					window.location = '/wp-admin/admin.php?page=gf_settings&subview=gforms-export-entries';
				}
			});
		}
	});
	
	$('.btn_gfee_import_now').on('click', function() {
		var settings = $('.gfee_export_import_settings').val();

		if ( settings == '' ) {
			alert('You did not enter any settings to import.');
		} else {
			var spinner = '<img class="gfee_spin" src="' + gfee_calendar_js_strings.plugin_path + 'images/wpspin_light.gif" />';;
			var html = spinner + ' Preparing to import settings, please wait for the page to reload...';
			$('.gfee_export_import_msg').html( html );
			settings = JSON.stringify( settings );

			jQuery.ajax({
				type: 'POST',
				data: {'action': 'gfee_import_settings', 'gfee_settings': settings },
				url: gfee_calendar_js_strings.ajaxurl,
				timeout: 15000,
				error: function() {
					alert( 'Failure: settings not imported' );

				},
				dataType: "html",
				success: function(result){
					location.reload();
				}
			});
		}
	});

	$('.gfee_edit_template').on('click', function() {
		if ( $('.gfee_email_template_div').css('display') == 'none' ) {
			$('.gfee_email_template_div').slideDown();
		} else {
			$('.gfee_email_template_div').slideUp();
		}
	});

	$('.export_name').on('blur', function() {
		validate_export( this );
	});

	$('.export_name').on('change', function() {
//		validate_export( this );
	});

	$(".export_name").bind('input', function () {
		if (checkExists( $('.export_name').val() ) === true){
			$(this).trigger('blur');
		}
	});

	var checkExists = function(inputValue) {
		var x = document.getElementById("export_name");
		var i;
		var flag;
		for (i = 0; i < x.options.length; i++) {
			if(inputValue == x.options[i].value){
				flag = true;
			}
		}
		return flag;
	}

	var validate_export = function(elem) {
		var default_val = $('.export_name').attr('data-default');
			if ( default_val === $('.export_name').val() ) {
				return;
			}

		if ( $(elem).val() === '' ) {
			alert( 'You can not have a blank name for your export.' );
			$('.export_name').val( default_val );
		} else {
			console.log(default_val);
			console.log($('.export_name').val());
			var export_exists = all_exports( $(elem).val() );
			if ( export_exists === false ) {
				var result = confirm( 'Are you sure you want to create a new export?' );
				if ( result ) {
					window.location = '/wp-admin/admin.php?page=gf_settings&subview=gforms-export-entries&current_export=' + $(elem).val();
				} else {
					$('.export_name').val( default_val );
				}
			} else {
				window.location = '/wp-admin/admin.php?page=gf_settings&subview=gforms-export-entries&current_export=' + $(elem).val();
			}
		}
	};
	
	var all_exports = function( name ) {
		var status = false;
		$('#export_name option').each(function( index, elem ) {
			if ( name === $(elem).val() ) {
				status = true;
			}
		});
		return status;
	};
	
	$( '.gfee_advanced_title' ).on( 'click', function() {
		if ( $( '.gfee_advanced_options' ).css( 'display' ) === 'none' ) {
			$( '.gfee_advanced_options' ).slideDown();
		} else {
			$( '.gfee_advanced_options' ).slideUp();
		}
	});
	
	$( '.gfee_form_fields input[type=text]' ).on( 'change', function() {
		var value = $( this ).val();

		if ( 0 === value ) {
			return true;
		}

		var fld = $( this );
		var parent = $( this ).parent().parent();
		var fld_name = $( this ).attr( 'name' );
		var e_fld_name = '';
		
		parent.find( 'input[type=text]' ).each(function(index, element) {
			e_fld_name = $( element ).attr( 'name' );

			if ( fld_name === e_fld_name ) {
				return true;
			}

			if ( value === $( element ).val() && $( element ).val() !== 0 && $( element ).val() !== '0' ) {
				fld.css( 'background', '#F4DB07' );
				$( element ).css( 'background', '#FFFF99' );
				alert( 'You can not have the same column number for\nmultiple fields in the same form.\nPlease enter another number.' );
				$( fld ).val( '0' );
				$( fld ).focus();
				setTimeout( function() {
					$( element ).css( 'background', '#FFFFFF' );
					fld.css( 'background', '#FFFFFF' );
				}, 5000 );
			}

        });
	});

	$( document ).on( 'click', '#gform-settings-save', function() {
		event.preventDefault();
		var form = [];
		var name = '';
		var value = '';
		var type = '';
		var cnt = 0;
		var cnt2 = 0;
		var newForm = $( '<form>', {
			'action': '',
			'target': '_top',
			'method': 'post'
		});

		$( '#gform-settings *' ).filter(':input').each(function( i, e ) {
			++cnt;
			name = $( e ).attr( 'name' );
			value = $( e ).val();
			type = $( e ).attr( 'type' );
			if ( 'checkbox' === type ) {
				if ( false === $( e ).prop( 'checked' ) ) {
					value = '0';
				}
			}
			if ( '0' !== value ) {
				++cnt2;
				form[ name ] = value;
				newForm.append( $( '<input>', {
					'name': name,
					'value': value,
					'type': 'hidden'
				}));
			}
		});

		newForm.append( $( '<input>', {
			'name': 'total_form_fields',
			'value': cnt,
			'type': 'hidden'
		}));
		newForm.append( $( '<input>', {
			'name': 'total_form_fields_sent',
			'value': cnt2,
			'type': 'hidden'
		}));

		console.log( 'Total Form Fields: ' + cnt );
		console.log( 'Total Form Fields sent: ' + cnt2 );

		newForm.appendTo( document.body )
		newForm.submit();
	});
	
	$( document ).on( 'change', '.gfee_schedule_frequency', function() {
		var value = $( this ).val();
		if ( '' === value ) {
			$( '.gfee_file_schedule_wrap' ).css( 'background-color', 'transparent' );
			$( '.gfee_file_schedule' ).prop( 'checked', false );
			$( '.gfee_remove_file_schedule_wrap' ).css( 'background-color', '#0f0' );
			$( '.gfee_remove_file_schedule' ).prop( 'checked', true );
			setTimeout( function() {
				$( '.gfee_remove_file_schedule_wrap' ).css( 'background-color', 'transparent' );
			}, 6000 );
		} else {
			$( '.gfee_remove_file_schedule_wrap' ).css( 'background-color', 'transparent' );
			$( '.gfee_remove_file_schedule' ).prop( 'checked', false );
			$( '.gfee_file_schedule_wrap' ).css( 'background-color', '#0f0' );
			$( '.gfee_file_schedule' ).prop( 'checked', true );
			setTimeout( function() {
				$( '.gfee_file_schedule_wrap' ).css( 'background-color', 'transparent' );
			}, 6000 );
		}
	});
	
    $( document ).on( 'click', '.gfee_copy_value', function() {
        var form_id = $( this ).data( 'form-id' );
        var field = $( this ).data( 'field' );
        //= Clean up the field and remove forward slashes
        var find = '/';
        var re = new RegExp(find, 'g');
        field = field.replace(re, '-');
        
        var value = $( '.gfee_field_' + field + '_' + form_id + '_wrap input[type=text]' ).val();

        $( '.gfee_field_' + field ).val( value );
        alert( 'All fields named ' + field + ' have been updated to ' + value );
    });
    
});