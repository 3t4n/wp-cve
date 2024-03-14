jQuery( document ).ready( function( $ ) {
	// Added: new sidebar
	$( '#informationdiv' ).clone().insertAfter( '#informationdiv' ).html( cf7ea_ajax_object.pro_features_list );
	// Added: Mail body suggestion list button.
	var CF7Body = $( '#wpcf7-mail-body, #wpcf7-mail-2-body' );
	CF7Body.before( '<span class="dashicons dashicons-list-view"></span>' );
	// initalise the dialog
	$( '#cf7-email-preview, #cf7-email-suggestion-list' ).dialog( {
		title: 'My Dialog',
		dialogClass: 'wp-dialog',
		autoOpen: false,
		draggable: false,
		width: 'auto',
		modal: true,
		resizable: false,
		closeOnEscape: true,
		position: {
			my: 'center',
			at: 'center',
			of: window
		},
		open: function() {
			// close dialog by clicking the overlay behind it.
			$( '.ui-widget-overlay' ).bind( 'click', function() {
				$( '#cf7-email-preview' ).dialog( 'close' );
			} )
 		 },
	  	create: function() {
			// style fix for WordPress admin.
			$( '.ui-dialog-titlebar-close' ).addClass( 'ui-button' );
		},
	} );

	// Template quick preview.
	$( '.lightbox-gallery' ).on( 'click', 'a', function( event ) {
		event.preventDefault();
		var _this = $( this );
		var PreviewImage = $( '#cf7-email-preview' ).find( 'img' );
		PreviewImage.attr( 'src', _this.attr( 'href' ) );
		$( '.ui-dialog-title' ).text( _this.parents( 'li' ).find( 'label' ).text() );
		$( '#cf7-email-preview' ).dialog( 'open' );
	} );

	// Shortcode quick preview.
	$( document ).on( 'click', 'span.dashicons-list-view', function( e ) {
		$( '.ui-dialog-title' ).text( 'Special Mail Tags' );
		if ( ! $( 'textarea#wpcf7-additional-settings' ).val().includes( 'subscribers_only: true' ) ) {
			$( 'a[href="#cf7_user_related"]' ).hide();
		} else {
			$( 'a[href="#cf7_user_related"]' ).show();
		}
		$( '#cf7-email-suggestion-list' ).dialog( 'open' );
	} );
	// Click to copy.
	$( document ).on( 'click', '.shortcode-list code, span.mailtag', function( e ) {
		e.preventDefault();
		var Element = this;
		var Range = document.createRange();
		var Selection = window.getSelection();
		Range.selectNodeContents( Element );
		Selection.removeAllRanges();
		Selection.addRange( Range );
		document.execCommand( 'copy' );
	} );

	// Click on shortcode tab.
	$( '#cf7-email-suggestion-list' ).on( 'click', '.nav-tab', function( e ) {
		e.preventDefault();
		var _thisNav = $( this );
		$( '.nav-tab-active' ).removeClass( 'nav-tab-active' );
		$( '.shortcode-list' ).hide();
		_thisNav.addClass( 'nav-tab-active' );
		$( '#' + _thisNav.attr( 'href' ).replace( '#', '' ) ).show();
	} );

	// Radio button click.
	var $radioButtons = $( '.cf7ea-template-list input[type="radio"]' );
	$radioButtons.click( function() {
		$radioButtons.each( function() {
			$( this ).parents( 'li' ).toggleClass( 'cf7ea-template-selected', this.checked );
		} );
	} );
	// Select templates.
	$( '.cf7ea-wrap .selecte_template' ).click( function( e ) {
		e.preventDefault();
		if( $( this ).parents( 'li' ).find( '.cf7ea_email_template' ).is( ':checked' ) ) {
			var template_name = $( this ).parents( 'li' ).find( '.cf7ea_email_template' ).val();
			var template_type = '';

			if ( $( this ).parents( '.cf7ea-template-list' ).hasClass( 'admin_templates' ) ){
				template_type = 'admin';
			}else{
				template_type = 'thank_you';
			}
			var admin_template_data = {
				action: 'cf7_email_add_on_add_admin_template',
				template_name: template_name,
				template_type: template_type,
				nonce: cf7ea_ajax_object.nonce,
			}
			$.post( cf7ea_ajax_object.ajax_url + window.location.search,admin_template_data,
				function( response ) {
					var cf7ea_response = $.parseJSON( response );
					if ( cf7ea_response.result == '1' ) {
						if( cf7ea_response.template_type == 'admin' ) {
							$( '#contact-form-editor #mail-panel #wpcf7-mail-body' ).val( '' );
							$( '#contact-form-editor #mail-panel #wpcf7-mail-body' ).val( cf7ea_response.message );
							if( ! $( '#contact-form-editor #mail-panel #wpcf7-mail-use-html' ).is( ':checked' ) ) {
								$( '#contact-form-editor #mail-panel  #wpcf7-mail-use-html' ).trigger( 'click' );
							}
							$( 'input[name=wpcf7-save]' ).removeAttr( 'disabled', 'disabled' );
							$( '#mail-panel-tab a' ).trigger( 'click' );
						}else {
							if( ! $( '#wpcf7-mail-2-active' ).is( ':checked' ) ) {
								$( '#wpcf7-mail-2-active' ).trigger( 'click' );
							}
							$( '#contact-form-editor #mail-panel #wpcf7-mail-2-body' ).val( '' );
							$( '#contact-form-editor #mail-panel #wpcf7-mail-2-body' ).val( cf7ea_response.message );
							if( ! $( '#contact-form-editor #mail-panel  #wpcf7-mail-2-use-html' ).is( ':checked' ) ) {
								$( '#contact-form-editor #mail-panel  #wpcf7-mail-2-use-html' ).trigger( 'click' );
							}
							$( 'input[name=wpcf7-save]' ).removeAttr( 'disabled', 'disabled' );
							$( '#mail-panel-tab a' ).trigger( 'click' );
						}
					}
				} );
		}
	} );
	// Template change
	$( '.cf7ea_email_template' ).change( function() {
		if( $( this ).parents( 'li' ).hasClass( 'cf7ea-template-active' ) ) {
			$( 'input[name=wpcf7-save]' ).removeAttr( 'disabled', 'disabled' );
		} else {
			$( 'input[name=wpcf7-save]' ).attr( 'disabled', 'disabled' );
		}
	} );
});