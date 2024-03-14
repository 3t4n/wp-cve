(function( $ ) {

	'use strict';

	var SofttemplateTemplatesType = {

		errorClass: 'softtemplate-template-types-popup__error',

		init: function() {

			var self = this;

			$( document )
				.on( 'click.SofttemplateTemplatesType', '.page-title-action', self.openPopup )
				.on( 'click.SofttemplateTemplatesType', '.softtemplate-template-types-popup__overlay', self.closePopup )
				.on( 'click.SofttemplateTemplatesType', '#templates_type_submit', self.validateForm )
				.on( 'change.SofttemplateTemplatesType', '#template_type', self.changeType );

		},

		openPopup: function( event ) {
			event.preventDefault();
			$( '.softtemplate-template-types-popup' ).addClass( 'softtemplate-template-types-popup-active' );
		},

		closePopup: function() {
			$( '.softtemplate-template-types-popup' ).removeClass( 'softtemplate-template-types-popup-active' );
		},

		changeType: function() {

			var $this = $( this ),
				value = $this.find( 'option:selected' ).val();

			if ( '' !== value ) {
				$this.removeClass( SofttemplateTemplatesType.errorClass );
			}

		},

		validateForm: function() {

			var $this = $( this ),
				$form = $this.closest( '#templates_type_form' ),
				$type = $form.find( '#template_type' ),
				$name = $form.find( '#template_name' ),
				type  = $type.find( 'option:selected' ).val();

			$type.removeClass( SofttemplateTemplatesType.errorClass );

			if ( '' !== type ) {
				$form.submit();
			} else {
				$type.addClass( SofttemplateTemplatesType.errorClass );
			}

		}

	};

	SofttemplateTemplatesType.init();

})( jQuery );