(function($){

	FLBuilder.registerModuleHelper(
		'tnit-contact-form',
		{

			init: function()
		{
				// Toggle reCAPTCHA display.
				this._toggleReCaptcha();

				$( 'select[name=recaptcha_toggle]' ).on( 'change', $.proxy( this._toggleReCaptcha, this ) );
				$( 'input[name=recaptcha_site_key]' ).on( 'change', $.proxy( this._toggleReCaptcha, this ) );
				$( 'select[name=recaptcha_theme]' ).on( 'change', $.proxy( this._toggleReCaptcha, this ) );
				$( 'input[name=btn_bg_color]' ).on( 'change', this._previewButtonBackground );

				// Render reCAPTCHA after layout rendered via AJAX.
				if ( window.onLoadFLReCaptcha ) {
					$( FLBuilder._contentClass ).on( 'fl-builder.layout-rendered', onLoadFLReCaptcha );
				}
			},

			/**
			 * Custom preview method for reCAPTCHA settings
			 *
			 * @param  object event  The event type of where this method been called
			 * @since 1.0.0
			 */
			_toggleReCaptcha: function(event)
		{
				var form    = $( '.fl-builder-settings' ),
				nodeId      = form.attr( 'data-node' ),
				toggle      = form.find( 'select[name=recaptcha_toggle]' ),
				captchaKey  = form.find( 'input[name=recaptcha_site_key]' ).val(),
				captType    = 'normal',
				theme		= form.find( 'select[name=recaptcha_theme]' ).val(),
				reCaptcha 	= $( '.fl-node-' + nodeId ).find( '.fl-grecaptcha' ),
				reCaptchaId = nodeId + '-fl-grecaptcha',
				target		= typeof event !== 'undefined' ? $( event.currentTarget ) : null,
				inputEvent	= target != null && typeof target.attr( 'name' ) !== typeof undefined && target.attr( 'name' ) === 'recaptcha_site_key',
				selectEvent	= target != null && typeof target.attr( 'name' ) !== typeof undefined && target.attr( 'name' ) === 'recaptcha_toggle',
				typeEvent	= target != null && typeof target.attr( 'name' ) !== typeof undefined && target.attr( 'name' ) === 'recaptcha_validate_type',
				themeEvent	= target != null && typeof target.attr( 'name' ) !== typeof undefined && target.attr( 'name' ) === 'recaptcha_theme',
				scriptTag 	= $( '<script>' ),
				isRender 	= false;

				// Add library if not exists.
				if ( 0 === $( 'script#g-recaptcha-api' ).length ) {
					scriptTag
					.attr( 'src', 'https://www.google.com/recaptcha/api.js?onload=onLoadFLReCaptcha&render=explicit' )
					.attr( 'type', 'text/javascript' )
					.attr( 'id', 'g-recaptcha-api' )
					.attr( 'async', 'async' )
					.attr( 'defer', 'defer' )
					.appendTo( 'body' );
				}

				if ( 'show' === toggle.val() && captchaKey.length ) {

					// reCAPTCHA is not yet exists.
					if ( 0 === reCaptcha.length ) {
						isRender = true;
					}
					// If reCAPTCHA element exists, then reset reCAPTCHA if existing key does not matched with the input value.
					else if ( ( inputEvent || selectEvent || typeEvent || themeEvent ) && ( reCaptcha.data( 'sitekey' ) !== captchaKey || reCaptcha.data( 'validate' ) !== captType || reCaptcha.data( 'theme' ) !== theme )
					) {
						reCaptcha.parent().remove();
						isRender = true;
					} else {
						reCaptcha.parent().show();
					}

					if ( isRender ) {
						this._renderReCaptcha( nodeId, reCaptchaId, captchaKey, captType, theme );
					}
				} else if ( 'show' === toggle.val() && captchaKey.length === 0 && reCaptcha.length > 0 ) {
					reCaptcha.parent().remove();
				} else if ( 'hide' === toggle.val() && reCaptcha.length > 0 ) {
					reCaptcha.parent().hide();
				}
			},

			/**
			 * Render Google reCAPTCHA
			 *
			 * @param  string nodeId  		The current node ID
			 * @param  string reCaptchaId  	The element ID to render reCAPTCHA
			 * @param  string reCaptchaKey  The reCAPTCHA Key
			 * @param  string reCaptType  	Checkbox or invisible
			 * @param  string theme         Light or dark
			 * @since 1.0.0
			 */
			_renderReCaptcha: function( nodeId, reCaptchaId, reCaptchaKey, reCaptType, theme )
		{
				var captchaField = $( '<div class="fl-input-group fl-recaptcha">' ),
				captchaElement   = $( '<div id="' + reCaptchaId + '" class="fl-grecaptcha">' ),
				widgetID;

				captchaElement.attr( 'data-sitekey', reCaptchaKey );
				captchaElement.attr( 'data-validate', reCaptType );
				captchaElement.attr( 'data-theme', theme );

				// Append recaptcha element to an appended element.
				captchaField
				.html( captchaElement )
				.insertBefore( $( '.fl-node-' + nodeId ).find( '.tnit-contact-form > .tnit-btn-holder' ) );

				widgetID = grecaptcha.render(
					reCaptchaId,
					{
						sitekey : reCaptchaKey,
						size    : reCaptType,
						theme	: theme
					}
				);
				captchaElement.attr( 'data-widgetid', widgetID );
			},

		}
	);

})( jQuery );
