/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

/* global jQuery, mobx_localize, CodeMirror, ajaxurl, wpColorPickerL10n, Color */
(function( $ ) {

    $(function() {

		var $tabs  = $( '.mobx-tabs > .mobx-tab' ),
			$table = $( '#mobx-settings > .mobx-tab-content' );
		
		// Handle tabs
		$tabs.on( 'click', function(e) {

			e.preventDefault();
			e.stopPropagation();

			var tab = $(this).index(),
				codeField = $table.eq(tab).find('.CodeMirror');

			$tabs.removeClass( 'mobx-tab-active' );
			$(this).addClass( 'mobx-tab-active' );
			$table.hide();
			$table.eq(tab).show();

			// Update codeMirror size
			if ( codeField.length > 0 ) {

				codeField.each( function(i, el){
					el.CodeMirror.refresh();
				});

			}
	
		});

		// Handle typography tabs
		$( '.mobx-typography-tabs li' ).on( 'click', function(e) {

			var $this = $(this),
				tabs  = $this.closest( 'div' );

			tabs.find( 'li' ).removeClass( 'mobx-tab-active' );
			$this.addClass( 'mobx-tab-active' );
			tabs.find( 'fieldset' ).removeClass( 'mobx-fields-active' );
			tabs.find( 'fieldset' ).eq( $this.index() ).addClass( 'mobx-fields-active' );

		});

		// Handle color picker field
		$( '.mobx-color-picker' ).each( function() {

			$(this).wpColorPicker({
				color: this.value
			});

		});

		// Handle range slider field
		$( '.mobx-ui-slider' ).each( function() {

			var el = $(this);

			el.slider({ 
				range : 'min',
				min   : el.data( 'min' ),
				max   : el.data( 'max' ),
				step  : el.data( 'step' ),
				value : el.data( 'value' ),
				slide: function( e, ui ) {
					el.next().val( ui.value + el.data( 'unit' ) );
				},
				stop: function( e, ui ) {
					el.next().trigger( 'focusout' );
				}
			});

		});

		// Format value on range/number input change
		var formatValue = function( el, val ) {

			var min    = el.data( 'min' )  || parseFloat( el[0].getAttribute( 'min' ) ) || 0,
				max    = el.data( 'max' )  || parseFloat( el[0].getAttribute( 'max' ) ) || 0,
				step   = el.data( 'step' ) || 1,
				unit   = el.data( 'unit' ) || '',
				dec    = Math.floor( step ) !== step ? step.toString().split( '.' )[1].length : 0;

			val = val.replace( ',', '.' );
			val = parseFloat( val ) || min;
			val = Math.round ( ( val - min ) / step )  * step + min;
			val = Math.max( min, Math.min( max, val ) );
			val = val.toFixed( dec );

			return val + unit;
	
		};

		// On range input change
		$( '.mobx-ui-slider + input' ).on( 'input', function() {

			var $this = $(this);
			$this.prev().slider('value', parseFloat( formatValue( $this.prev(), $this.val() ) ) );

		});

		// On range input focusout
		$( '.mobx-ui-slider + input' ).focusout( function() {

			var $this = $(this);
			$this.val( formatValue( $this.prev(), $this.val() ) );

		});

		// On number input focusout
		$( '.mobx-number' ).focusout( function() {

			var $this = $(this);
			$this.val( formatValue( $this, $this.val() ) );

		});

		// Prevent submit input on Enter keydown
		// Prevent issue with auto format input
		$(window).keydown( function(e) {

			if( e.keyCode == 13 ) {

				e.preventDefault();
				return false;

			}
	
		});

		// Close popup dial
		$( '.mobx-popup-confirm, .mobx-popup-close' ).on( 'click', function() {
			$(this).closest( '.mobx-popup-holder' ).addClass( 'mobx-hide-popup');
		});

		// Handle sort field
		if ( $( '.mobx-sort-list' ).length > 0 ) {

			$( '.mobx-sort-list' ).sortable({
				connectWith: '.mobx-sort-list',
				items: '> li',
				revert: 250,
				update: function( event, ui ) {

					var $this  = $(this),
						$input = $this.find('input');

					$this[ $input.length > 0 ? 'addClass' : 'removeClass' ]('mobx-hide-msg');

					if ( $this.data( 'active' ) ) {
						$input.removeAttr( 'disabled' );
					} else {
						$input.attr( 'disabled', 'disabled' );
					}

				}

			}).disableSelection();

		}

		// Add confirmation before to reset or import settings
		$( '#modulobox input[name="reset"], #modulobox input[name="import"]' ).on( 'click', function(e) {

			var result = confirm( $(this).attr( 'name' ) === 'reset'  ? mobx_localize.reset_msg : mobx_localize.import_msg );
			if ( ! result ) {
				return false;
			}

		});

		// Remove tooltip on mousedown (to handle preventdefault from range slider & sortable)
		$( document ).on( 'mousedown', function(e) {

			if ( ! $(e.target).hasClass( 'mobx-info-desc' ) ) {
				$( '.mobx-info-desc' ).removeClass( 'mobx-show-desc' );
			}
		
		});

		// Reveal tooltip description
		$( document ).on( 'click', function(e) {

			var $this = $(e.target);

			if ( $this.hasClass( 'mobx-info-desc' ) ) {

				$( '.mobx-info-desc' ).not( $this ).removeClass( 'mobx-show-desc' );
				$this.toggleClass( 'mobx-show-desc' );

			}

		});

		// Show/Hide custom zoomToValue field
		var $zoomTo = $( '[name="modulobox[zoomTo]"]' );
		var zoomToField = function() {
			
			var method = $zoomTo.first().is(':checked') ? 'hide' : 'show';
			$( '[name="modulobox[zoomToValue]"]' ).closest( 'tr' )[ method ]();
			
		};
		zoomToField();

		// Show/Hide custom zoomToValue field on clikc
		$zoomTo.on( 'click', function() {
			zoomToField();
		});

		// Delete table row
		$( '.mobx-delete-size' ).on( 'click', function(e) {

			if ( $(this).closest( 'tbody' ).find( 'tr' ).length > 3 ) {
				$(this).parent().remove();
			}

		});
		
		// Clone and add new table row
		$( '.mobx-add-size' ).on( 'click', function(e) {

			var el = $(this).parent().prev(),
				nb = $(this).closest( 'tbody' ).find( 'tr' ).length - 1,
				tr = el.clone( true ),
				sz = tr.find( 'td:first-child label' );

			if ( nb <= 10 ) {
				sz.text( sz.text().slice( 0,-1 ) + nb );
				tr.insertAfter( el );
			}

		});

		var $mousewheel = $( '#scrollToNav, #scrollToZoom, #scrollToClose' );

		// Prevent selecting several functions for mousewheel
		$mousewheel.on( 'change', function() {
			$mousewheel.not( $(this) ).removeAttr( 'checked' );
		});

		$( '.mobx-code' ).each( function() {

			var $this = $(this);

			$this.closest( 'td' ).add( $this ).css({
				width  : '100%',
				height : '502px'
			});

			// Prevent overflow issue due to table layout
			$this.closest( 'tr' )
				.add( $this.closest( 'tbody' ) )
			    .add( $this.closest( 'table' ) )
				.css( 'display', 'block' );

			var editor = CodeMirror.fromTextArea( $this[0], {
				mode            : $this.data( 'mode' ),
				value           : $this.val(),
				height          : 400,
				width           : 800,
				theme           : 'material',
				lineNumbers     : true,
				styleActiveLine : true,
    			matchBrackets   : true,
				nonEmpty        : false,
				indentWithTabs  : true,
				indentUnit      : 4,
				scrollbarStyle  : 'simple',
				direction       : $('body').is('.rtl') ? 'rtl' : 'ltr'
			});

			editor.on( 'blur', function(e){
				$this.val(e.getValue() );
			});
		
		});

		var $upload  = $( '.mobx-upload-button, .mobx-upload-input' ),
			$filebtn = $( '.mobx-file-input' );

		// Handle upload
		$upload.on( 'click', function(e) {

			$(this).blur();
			$filebtn.trigger( 'click' );

		});

		// Set file name in text input
		$filebtn.change(function() {

			var filename = $(this).val().split( '\\' ).pop();
			$(this).next().val( filename ).attr( 'title', filename ).focus();

		});

		// Create instance of ModuloBox
		var mobx = $.ModuloBox();
		var accessibility;

		// Add media to preview gallery
		mobx.on( 'updateGalleries.modulobox', function() {
			mobx.addMedia( 'preview-gallery', mobx_localize.lightbox_media );
		});

		// Add svg icons to lightbox
		mobx.on( 'beforeAppendDOM.modulobox', function( DOM ) {

			for ( var type in this.buttons ) {

				if ( this.buttons.hasOwnProperty( type ) ) {

					if ( accessibility && accessibility[ type + 'Label'] ) {

						this.buttons[type].setAttribute( 'aria-label', accessibility[ type + 'Label'] );

						if ( accessibility.title ) {
							this.buttons[type].setAttribute( 'title', accessibility[ type + 'Label'] );	
						}

					}

				}

			}

		});

		// Load SVG icons	
		var ajax = new XMLHttpRequest();

		ajax.open( 'GET', mobx_localize.svg_icons, true );
		ajax.send();

		ajax.onload = function() {	

			var div = document.createElement( 'DIV' );
			div.style.display = 'none';
			div.innerHTML = ajax.responseText;
			document.body.insertBefore( div, document.body.childNodes[0] );

		};

		var $modalHolder  = $( '.mobx-modal-holder' ),
			$modalMessage = $( '.mobx-modal-msg' ),
			xhr;
		
		// Handle Ajax requests 
		$( document ).on( 'click', '#modulobox input[name="save"], #modulobox input[name="preview"]', function() {
	
			// prevent multiple Ajax requests
			if ( xhr && ( xhr.readyState == 3 || xhr.readyState == 2 || xhr.readyState == 1 ) ) {
				return false;
			}

			var $this = $(this),
				name  = $this.attr( 'name' ),
				data, message;
			
			if ( name === 'save' ) {

				message = mobx_localize.saving_msg;
				data    = $( '#modulobox' ).serialize() + '&action=modulobox_ajax_request&type=save_settings';

			} else if ( name === 'preview' ) {

				message = mobx_localize.previewing_msg;
				data    = $( '#modulobox' ).serialize() + '&action=modulobox_ajax_request&type=preview_lightbox';

			}

			xhr = $.ajax({
				type    : 'POST',
				url     : ajaxurl,
				data    : data,
				context : $this,
				processData   : 'application/x-www-form-urlencoded; charset=UTF-8',
				beforeSend : function() {

					$modalMessage.html( message );
					$modalHolder.removeClass('mobx-modal-success mobx-modal-error' );
					$modalHolder.addClass( 'mobx-modal-show' );

				},
				success : function( data ) {

					$modalMessage.html( data.message );
					$modalHolder.removeClass( 'mobx-modal-show' );
					$modalHolder.addClass( data.success ? 'mobx-modal-success' : 'mobx-modal-error' );
					
					if ( $this.attr( 'name' ) === 'save' && data.success && data.content ) {

						$( '.mobx-code-error[data-field="customJSBefore"]' ).html( data.content.customJSBefore.error );
						$( '.mobx-code-error[data-field="customJSAfter"]' ).html( data.content.customJSAfter.error );
	
					} else if ( $this.attr( 'name' ) === 'preview'  && data.success && data.content ) {

						accessibility = data.content.accessibility;
						// Destroy previous lightbox instance
						mobx.destroy();
						// Instantiate ModuloBox with current settings
						$.ModuloBox( data.content.options );
						// Initialize ModuloBox
						mobx.init();
						// Open preview gallery at index 0
						mobx.open( 'preview-gallery', 0 );

					}

				},
				error : function( data ) {

					$modalHolder.addClass( 'mobx-modal-error' );
					$modalMessage.html( mobx_localize.error_msg );
					$modalHolder.removeClass( 'mobx-modal-show' );
	
				}
			});

			return false;

		});

    });

})( jQuery );

/**!
 * wp-color-picker-alpha
 *
 * Overwrite Automattic Iris for enabled Alpha Channel in wpColorPicker
 * Only run in input and is defined data alpha in true
 *
 * Version: 2.0
 * https://github.com/kallookoo/wp-color-picker-alpha
 * Licensed under the GPLv2 license.
 */
( function( $ ) {
	// Variable for some backgrounds ( grid )
	var image   = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAAHnlligAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHJJREFUeNpi+P///4EDBxiAGMgCCCAGFB5AADGCRBgYDh48CCRZIJS9vT2QBAggFBkmBiSAogxFBiCAoHogAKIKAlBUYTELAiAmEtABEECk20G6BOmuIl0CIMBQ/IEMkO0myiSSraaaBhZcbkUOs0HuBwDplz5uFJ3Z4gAAAABJRU5ErkJggg==',
	// html stuff for wpColorPicker copy of the original color-picker.js
		_before = '<button type="button" class="button wp-color-result" aria-expanded="false"><span class="wp-color-result-text"></span></button>',
		_after = '<div class="wp-picker-holder" />',
		_wrap = '<div class="wp-picker-container" />',
		_button = '<input type="button" class="button button-small" />',
		_wrappingLabel = '<label></label>',
		_wrappingLabelText = '<span class="screen-reader-text"></span>';

	/**
	 * Overwrite Color
	 * for enable support rbga
	 */
	Color.fn.toString = function() {
		if ( this._alpha < 1 )
			return this.toCSS( 'rgba', this._alpha ).replace( /\s+/g, '' );

		var hex = parseInt( this._color, 10 ).toString( 16 );

		if ( this.error )
			return '';

		if ( hex.length < 6 )
			hex = ( '00000' + hex ).substr( -6 );

		return '#' + hex;
	};

	/**
	 * Overwrite wpColorPicker
	 */
	$.widget( 'wp.wpColorPicker', $.wp.wpColorPicker, {
		/**
		 * @summary Creates the color picker.
		 *
		 * Creates the color picker, sets default values, css classes and wraps it all in HTML.
		 *
		 * @since 3.5.0
		 *
		 * @access private
		 *
		 * @returns {void}
		 */
		_create: function() {
			// Return early if Iris support is missing.
			if ( ! $.support.iris ) {
				return;
			}

			var self = this,
				el = self.element;

			// Override default options with options bound to the element.
			$.extend( self.options, el.data() );

			// Create a color picker which only allows adjustments to the hue.
			if ( self.options.type === 'hue' ) {
				return self._createHueOnly();
			}

			// Bind the close event.
			self.close = $.proxy( self.close, self );

			self.initialValue = el.val();

			// Add a CSS class to the input field.
			el.addClass( 'wp-color-picker' );

			/*
			 * Check if there's already a wrapping label, e.g. in the Customizer.
			 * If there's no label, add a default one to match the Customizer template.
			 */
			if ( ! el.parent( 'label' ).length ) {
				// Wrap the input field in the default label.
				el.wrap( _wrappingLabel );
				// Insert the default label text.
				self.wrappingLabelText = $( _wrappingLabelText )
					.insertBefore( el )
					.text( wpColorPickerL10n.defaultLabel );
			}

			/*
			 * At this point, either it's the standalone version or the Customizer
			 * one, we have a wrapping label to use as hook in the DOM, let's store it.
			 */
			self.wrappingLabel = el.parent();

			// Wrap the label in the main wrapper.
			self.wrappingLabel.wrap( _wrap );
			// Store a reference to the main wrapper.
			self.wrap = self.wrappingLabel.parent();
			// Set up the toggle button and insert it before the wrapping label.
			self.toggler = $( _before )
				.insertBefore( self.wrappingLabel )
				.css( { backgroundColor: self.initialValue } );
			// Set the toggle button span element text.
			self.toggler.find( '.wp-color-result-text' ).text( wpColorPickerL10n.pick );
			// Set up the Iris container and insert it after the wrapping label.
			self.pickerContainer = $( _after ).insertAfter( self.wrappingLabel );
			// Store a reference to the Clear/Default button.
			self.button = $( _button );

			// Set up the Clear/Default button.
			if ( self.options.defaultColor ) {
				self.button
					.addClass( 'wp-picker-default' )
					.val( wpColorPickerL10n.defaultString )
					.attr( 'aria-label', wpColorPickerL10n.defaultAriaLabel );
			} else {
				self.button
					.addClass( 'wp-picker-clear' )
					.val( wpColorPickerL10n.clear )
					.attr( 'aria-label', wpColorPickerL10n.clearAriaLabel );
			}

			// Wrap the wrapping label in its wrapper and append the Clear/Default button.
			self.wrappingLabel
				.wrap( '<span class="wp-picker-input-wrap hidden" />' )
				.after( self.button );

			/*
			 * The input wrapper now contains the label+input+Clear/Default button.
			 * Store a reference to the input wrapper: we'll use this to toggle
			 * the controls visibility.
			 */
			self.inputWrapper = el.closest( '.wp-picker-input-wrap' );

			/*
			 * CSS for support < 4.9
			 */
			self.toggler.css({
				'height': '24px',
				'margin': '0 6px 6px 0',
				'padding': '0 0 0 30px',
				'font-size': '11px'
			});

			self.toggler.find( '.wp-color-result-text' ).css({
				'background': '#f7f7f7',
				'border-radius': '0 2px 2px 0',
				'border-left': '1px solid #ccc',
				'color': '#555',
				'display': 'block',
				'line-height': '22px',
				'padding': '0 6px',
				'text-align': 'center'
			});

			el.iris( {
				target: self.pickerContainer,
				hide: self.options.hide,
				width: self.options.width,
				mode: self.options.mode,
				palettes: self.options.palettes,
				/**
				 * @summary Handles the onChange event if one has been defined in the options.
				 *
				 * Handles the onChange event if one has been defined in the options and additionally
				 * sets the background color for the toggler element.
				 *
				 * @since 3.5.0
				 *
				 * @param {Event} event    The event that's being called.
				 * @param {HTMLElement} ui The HTMLElement containing the color picker.
				 *
				 * @returns {void}
				 */
				change: function( event, ui ) {
					if ( self.options.alpha ) {
						self.toggler.css( {
							'background-image' : 'url(' + image + ')',
							'position' : 'relative'
						} );
						if ( self.toggler.find('span.color-alpha').length == 0 ) {
							self.toggler.append('<span class="color-alpha" />');
						}
						self.toggler.find( 'span.color-alpha' ).css( {
							'width'                     : '30px',
							'height'                    : '24px',
							'position'                  : 'absolute',
							'top'                       : 0,
							'left'                      : 0,
							'border-top-left-radius'    : '2px',
							'border-bottom-left-radius' : '2px',
							'background'                : ui.color.toString()
						} );
					} else {
						self.toggler.css( { backgroundColor : ui.color.toString() } );
					}

					if ( $.isFunction( self.options.change ) ) {
						self.options.change.call( this, event, ui );
					}
				}
			} );

			el.val( self.initialValue );
			self._addListeners();

			// Force the color picker to always be closed on initial load.
			if ( ! self.options.hide ) {
				self.toggler.click();
			}
		},
		/**
		 * @summary Binds event listeners to the color picker.
		 *
		 * @since 3.5.0
		 *
		 * @access private
		 *
		 * @returns {void}
		 */
		_addListeners: function() {
			var self = this;

			/**
			 * @summary Prevent any clicks inside this widget from leaking to the top and closing it.
			 *
			 * @since 3.5.0
			 *
			 * @param {Event} event The event that's being called.
			 *
			 * @returs {void}
			 */
			self.wrap.on( 'click.wpcolorpicker', function( event ) {
				event.stopPropagation();
			});

			/**
			 * @summary Open or close the color picker depending on the class.
			 *
			 * @since 3.5
			 */
			self.toggler.click( function(){
				if ( self.toggler.hasClass( 'wp-picker-open' ) ) {
					self.close();
				} else {
					self.open();
				}
			});

			/**
			 * @summary Checks if value is empty when changing the color in the color picker.
			 *
			 * Checks if value is empty when changing the color in the color picker.
			 * If so, the background color is cleared.
			 *
			 * @since 3.5.0
			 *
			 * @param {Event} event The event that's being called.
			 *
			 * @returns {void}
			 */
			self.element.on( 'change', function( event ) {
				// Empty or Error = clear
				if ( $( this ).val() === '' || self.element.hasClass( 'iris-error' ) ) {
					if ( self.options.alpha ) {
						self.toggler.find( 'span.color-alpha' ).css( 'backgroundColor', '' );
					} else {
						self.toggler.css( 'backgroundColor', '' );
					}

					// fire clear callback if we have one
					if ( $.isFunction( self.options.clear ) )
						self.options.clear.call( this, event );
				}
			} );

			/**
			 * @summary Enables the user to clear or revert the color in the color picker.
			 *
			 * Enables the user to either clear the color in the color picker or revert back to the default color.
			 *
			 * @since 3.5.0
			 *
			 * @param {Event} event The event that's being called.
			 *
			 * @returns {void}
			 */
			self.button.on( 'click', function( event ) {
				if ( $( this ).hasClass( 'wp-picker-clear' ) ) {
					self.element.val( '' );
					if ( self.options.alpha ) {
						self.toggler.find( 'span.color-alpha' ).css( 'backgroundColor', '' );
					} else {
						self.toggler.css( 'backgroundColor', '' );
					}

					if ( $.isFunction( self.options.clear ) )
						self.options.clear.call( this, event );

				} else if ( $( this ).hasClass( 'wp-picker-default' ) ) {
					self.element.val( self.options.defaultColor ).change();
				}
			});
		},
	});

	/**
	 * Overwrite iris
	 */
	$.widget( 'a8c.iris', $.a8c.iris, {
		_create: function() {
			this._super();

			// Global option for check is mode rbga is enabled
			this.options.alpha = this.element.data( 'alpha' ) || false;

			// Is not input disabled
			if ( ! this.element.is( ':input' ) )
				this.options.alpha = false;

			if ( typeof this.options.alpha !== 'undefined' && this.options.alpha ) {
				var self       = this,
					el         = self.element,
					_html      = '<div class="iris-strip iris-slider iris-alpha-slider"><div class="iris-slider-offset iris-slider-offset-alpha"></div></div>',
					aContainer = $( _html ).appendTo( self.picker.find( '.iris-picker-inner' ) ),
					aSlider    = aContainer.find( '.iris-slider-offset-alpha' ),
					controls   = {
						aContainer : aContainer,
						aSlider    : aSlider
					};

				if ( typeof el.data( 'custom-width' ) !== 'undefined' ) {
					self.options.customWidth = parseInt( el.data( 'custom-width' ) ) || 0;
				} else {
					self.options.customWidth = 100;
				}

				// Set default width for input reset
				self.options.defaultWidth = el.width();

				// Update width for input
				if ( self._color._alpha < 1 || self._color.toString().indexOf('rgb') != -1 )
					el.width( parseInt( self.options.defaultWidth + self.options.customWidth ) );

				// Push new controls
				$.each( controls, function( k, v ) {
					self.controls[k] = v;
				} );

				// Change size strip and add margin for sliders
				self.controls.square.css( { 'margin-right': '0' } );
				var emptyWidth   = ( self.picker.width() - self.controls.square.width() - 20 ),
					stripsMargin = ( emptyWidth / 6 ),
					stripsWidth  = ( ( emptyWidth / 2 ) - stripsMargin );

				$.each( [ 'aContainer', 'strip' ], function( k, v ) {
					self.controls[v].width( stripsWidth ).css( { 'margin-left' : stripsMargin + 'px' } );
				} );

				// Add new slider
				self._initControls();

				// For updated widget
				self._change();
			}
		},
		_initControls: function() {
			this._super();

			if ( this.options.alpha ) {
				var self     = this,
					controls = self.controls;

				controls.aSlider.slider({
					orientation : 'vertical',
					min         : 0,
					max         : 100,
					step        : 1,
					value       : parseInt( self._color._alpha * 100 ),
					slide       : function( event, ui ) {
						// Update alpha value
						self._color._alpha = parseFloat( ui.value / 100 );
						self._change.apply( self, arguments );
					}
				});
			}
		},
		_change: function() {
			this._super();

			var self = this,
				el   = self.element;

			if ( this.options.alpha ) {
				var	controls     = self.controls,
					alpha        = parseInt( self._color._alpha * 100 ),
					color        = self._color.toRgb(),
					gradient     = [
						'rgb(' + color.r + ',' + color.g + ',' + color.b + ') 0%',
						'rgba(' + color.r + ',' + color.g + ',' + color.b + ', 0) 100%'
					],
					defaultWidth = self.options.defaultWidth,
					customWidth  = self.options.customWidth,
					target       = self.picker.closest( '.wp-picker-container' ).find( '.wp-color-result' );

				// Generate background slider alpha, only for CSS3 old browser fuck!! :)
				controls.aContainer.css( { 'background' : 'linear-gradient(to bottom, ' + gradient.join( ', ' ) + '), url(' + image + ')' } );

				if ( target.hasClass( 'wp-picker-open' ) ) {
					// Update alpha value
					controls.aSlider.slider( 'value', alpha );

					/**
					 * Disabled change opacity in default slider Saturation ( only is alpha enabled )
					 * and change input width for view all value
					 */
					if ( self._color._alpha < 1 ) {
						controls.strip.attr( 'style', controls.strip.attr( 'style' ).replace( /rgba\(([0-9]+,)(\s+)?([0-9]+,)(\s+)?([0-9]+)(,(\s+)?[0-9\.]+)\)/g, 'rgb($1$3$5)' ) );
						el.width( parseInt( defaultWidth + customWidth ) );
					} else {
						el.width( defaultWidth );
					}
				}
			}

			var reset = el.data( 'reset-alpha' ) || false;

			if ( reset ) {
				self.picker.find( '.iris-palette-container' ).on( 'click.palette', '.iris-palette', function() {
					self._color._alpha = 1;
					self.active        = 'external';
					self._change();
				} );
			}
		},
		_addInputListeners: function( input ) {
			var self            = this,
				debounceTimeout = 100,
				callback        = function( event ) {
					var color = new Color( input.val() ),
						val   = input.val();

					input.removeClass( 'iris-error' );
					// we gave a bad color
					if ( color.error ) {
						// don't error on an empty input
						if ( val !== '' )
							input.addClass( 'iris-error' );
					} else {
						if ( color.toString() !== self._color.toString() ) {
							// let's not do this on keyup for hex shortcodes
							if ( ! ( event.type === 'keyup' && val.match( /^[0-9a-fA-F]{3}$/ ) ) )
								self._setOption( 'color', color.toString() );
						}
					}
				};

			input.on( 'change', callback ).on( 'keyup', self._debounce( callback, debounceTimeout ) );

			// If we initialized hidden, show on first focus. The rest is up to you.
			if ( self.options.hide ) {
				input.on( 'focus', function() {
					self.show();
				} );
			}
		}
	} );

}( jQuery ) );
