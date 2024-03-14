/* jshint asi: true */

jQuery( document ).ready(function($){
	
	function is_email( email ) {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/
		return regex.test(email)
	}

	function set_cookie( name, value, exdays ) {
		if ( exdays === 0 ) {
			document.cookie = name + "=" + value + "" + ";" + "path=/;"
		} else {
			var d = new Date()
			d.setTime( d.getTime() + ( exdays*24*60*60*1000 ) )
			document.cookie = name + "=" + value + "" + ";" + "path=/;" + "expires=" + d.toUTCString()
		}
	}

	function get_cookie( name ) {
		var value = "; " + document.cookie
		var parts = value.split( "; " + name + "=" )
		
		if ( parts.length === 2 ) {
			return parts.pop().split(";").shift()
		} else {
			return false
		}
	}

	$( document ).on( 'submit', '.fca_eoi_form', function (e) {
		
		e.preventDefault()
		
		var $this = $( this )
		var $email_field = $( '[name=email]', $this )
		var $name_field = $( '[name=name]', $this )
		var name = $name_field.val()
		var email = $email_field.val()
		var list_id = $this.data( 'fca_eoi_list_id' )
		var $button = $( '[type=submit]', $this )
		var button_initial_val
		var highlight_interval = false;
		var thank_you_mode = $this.data( 'fca_eoi_thank_you_mode' )
		var thank_you_text_color = $this.data( 'fca_eoi_thank_you_text_color' )
		var thank_you_bg_color = $this.data( 'fca_eoi_thank_you_bg_color' )
		var thank_you_page = $this.data( 'fca_eoi_thank_you_page' )
		var cookie_duration = $this.data( 'fca_eoi_success_cookie_duration' )
		var subscribe_msg = $this.data( 'fca_eoi_sub_msg' )
		var has_error = false
		var form_id = $( '[name=fca_eoi_form_id]', $this ).val()
				
		// Attach tooltips
		$this.find('.tooltipstered').tooltipster('hide')
		$( '[name=email], [name=name]', $this ).not('.tooltipstered').each( function() {

			var $this = $( this )
			var tooltipWidth = $this.width() * .8

			$this.tooltipster( {
				contentAsHTML: true,
				fixedWidth: tooltipWidth,
				minWidth: tooltipWidth,
				maxWidth: tooltipWidth,
				trigger: 'none',
				theme: ['tooltipster-borderless', 'tooltipster-optin-cat']
			} )
		} )

		// Remove tooltip and tick on focus
		$( '[name=email], [name=name]', $( this ) ).focus( function() {
			var $this = $( this )
			var $button = $( '[type=submit]', $this.closest( '.fca_eoi_form' ) )
			var button_initial_val = $button.data( 'fca_eoi_initial_val' )

			// Remove any previous icon
			if( 'undefined' !== typeof( button_initial_val ) ) {
				$button.val( button_initial_val )
			}
			
			// Hide tooltip
			$( this ).tooltipster( 'hide' )
		} )

		// Save or save and get initial button value
		if( $button.data( 'fca_eoi_initial_val' ) ) {
			button_initial_val = $button.data( 'fca_eoi_initial_val' )
		} else {
			button_initial_val = $button.val()
			$button.data( 'fca_eoi_initial_val', button_initial_val )
		}
		
		// Remove any previous icon
		$button.val( button_initial_val );
		
		// Get Error Messages from hidden fields
		fcaEoiScriptData.invalid_email = $this.find('.fca_eoi_error_texts_email').val()
		fcaEoiScriptData.field_required = $this.find('.fca_eoi_error_texts_required').val()
		
		// Check email address
		if( ! email || ! is_email( email ) ) {
			$email_field.tooltipster( 'content', email ? fcaEoiScriptData.invalid_email : fcaEoiScriptData.field_required )
			$email_field.tooltipster( 'show' );
			$button.val( '✗ ' + button_initial_val );
			has_error = true;
		}

		// Check name
		if( $name_field.is(':visible') && !name ) {
			$name_field.tooltipster( 'content', fcaEoiScriptData.field_required );
			$name_field.tooltipster( 'show' );
			$button.val( '✗ ' + button_initial_val );
			has_error = true;
		}
	
		// Exit if honeypot is entered
		if( $( '[name=i_agree]', $this ).prop( 'checked' ) ) {
			return false;
		}
		
		// Exit if there is any error
		if( has_error ) {
			return false;
		}
		
		var gdpr_consent = 'unknown'
		if( fcaEoiScriptData.gdpr_checkbox ) {
			if( $this.find( '.fca_eoi_gdpr_consent' ).length === 0 ) {
				$this.find('.fca_eoi_layout_field_wrapper').hide()
				$this.find('.fca_eoi_layout_email_field_inner').hide()
				$this.find('.fca_eoi_layout_name_field_inner').hide()	
				if ( $this.closest( '.fca_eoi_form_wrapper ' ).hasClass( 'fca_eoi_layout_banner_wrapper' )  ) {
					$this.find('.fca_eoi_layout_headline_copy_wrapper')[0].style.setProperty( 'display', 'block', 'important' )
					$this.find('.fca_eoi_layout_headline_copy_wrapper').html( '<div style="font-size: 14px;">' + fcaEoiScriptData.consent_headline + '<br>' + '<label><input class="fca_eoi_gdpr_consent" value="" type="checkbox"></input>'+fcaEoiScriptData.consent_msg+'</label></div>')
				} else { 
					$this.find('.fca_eoi_layout_description_copy_wrapper')[0].style.setProperty( 'display', 'block', 'important' )
					$this.find('.fca_eoi_layout_description_copy_wrapper').html( '<div style="text-align:left;">' + fcaEoiScriptData.consent_headline + '<br><br>' + '<label><input class="fca_eoi_gdpr_consent" value="" type="checkbox"></input>'+fcaEoiScriptData.consent_msg+'</label></div>')
				}
				return false
			}
			
			gdpr_consent = $this.find( '.fca_eoi_gdpr_consent' ).prop('checked')
			
			if ( gdpr_consent == false ) {
				$this.find( '.fca_eoi_gdpr_consent' ).not('.tooltipstered').tooltipster( {
					contentAsHTML: true,
					trigger: 'none',
					theme: ['tooltipster-borderless', 'tooltipster-optin-cat']
				} ).tooltipster( 'content', fcaEoiScriptData.field_required ).tooltipster( 'show' )
				return false
			}
			
		}
		
		$button.val( subscribe_msg );
		//DISABLE BUTTON CLICK EVENT
		$button.prop('disabled', true);	

		var tz = jstz.determine() 
		
		$.ajax({
			type: 'POST',
			url: fcaEoiScriptData.ajax_url,
			data: { 
				'email': email,
				'name': name,
				'action': 'fca_eoi_subscribe',
				'list_id': list_id,
				'form_id': form_id,
				'nonce': fcaEoiScriptData.nonce,
				'timezone': tz.name(),
				'consent_granted': gdpr_consent
			}
		}).done( function( data ) {
			
			// Stop highlighting and add an icon for success/failure
			if ( data === '✓' ) {
				$button.val( data + ' ' + button_initial_val );
				
				set_cookie( 'fca_eoi_success_' + form_id, Math.floor(Date.now() / 1000), cookie_duration )
				
				//REMOVE FROM ACTIVE
				var activeOptins = JSON.parse( get_cookie( 'fca_eoi_active_optins' ) )
				if ( !Array.isArray( activeOptins ) ) {
					activeOptins = []
				}
				var index = activeOptins.indexOf( form_id )
				if ( index !== -1 ) {
					activeOptins.splice( index, 1 )
				}
				set_cookie ( 'fca_eoi_active_optins', JSON.stringify ( activeOptins ) )
	
			} else if( data === '✗ Failed to add user - denied' ) {
				$button.prop('disabled', false);
				$button.val( button_initial_val );
				$this.find( '.fca_eoi_gdpr_consent' ).tooltipster( 'show' )
			} else {
				$button.prop('disabled', false);
				$button.val( data );
			}
		
			if ( thank_you_mode === 'ajax' && data === '✓' ) {		
				clearInterval( highlight_interval )
				if ( thank_you_page && '✓' === data ) {
					tooltipWidth = $button.width()
					$button.tooltipster( {
						contentAsHTML: true,
						trigger: 'none',
						fixedWidth: tooltipWidth,
						minWidth: tooltipWidth,
						maxWidth: tooltipWidth,
						arrow: false,
						theme: ['tooltipster-borderless', 'tooltipster-optin-cat']
					} );
					
					$button.tooltipster( 'content', thank_you_page )
					$button.tooltipster( 'show' )
					$email_field.prop('disabled', true)
					$name_field.prop('disabled', true)
					
					$('.tooltipster-content').css("color", thank_you_text_color )	
					$('.tooltipster-content').css("background-color", thank_you_bg_color )	
					
				}
			} else if ( data === '✓' ) {
				window.location.href = thank_you_page
			}
		})				
	})
})