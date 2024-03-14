/* globals jQuery: false, ajaxurl: false, sfml: true */
(function($, d, w, undefined) {
	'use strict';

	$.extend( sfml, {
		/**
		 * Tha action used for the ajax call.
		 *
		 * @var string
		 */
		ajaxAction: 'sfml_sanitize_slug',
		/**
		 * Used to cache ajax results.
		 *
		 * @var object
		 */
		cache: {},
		/**
		 * Stores the last "cacheKey" used: it prevents displaying a late ajax result while a newer (cached) one is already displaying.
		 *
		 * @var string
		 */
		lastCacheKey: '',
		/**
		 * Timeout used when typing in slug inputs.
		 *
		 * @var object
		 */
		timeout: {},
		/**
		 * Duration for the timeout.
		 *
		 * @var int
		 */
		timeoutDuration: 500,
		/**
		 * The HTML class used for the "dynamic URL".
		 *
		 * @var string
		 */
		dynSlugClass: 'dynamic-login-url-slug',
		/**
		 * The HTML class used for the "dynamic URL".
		 *
		 * @var string
		 */
		dynErrorClass: 'dynamic-login-url-slug-error',
		/**
		 * The slug fields.
		 *
		 * @var object A jQuery object.
		 */
		fields: $( '.slug-field' ),
		/**
		 * Get an element by its id attribute.
		 *
		 * @return object An Element object.
		 */
		getElem: function( id ) {
			return d.getElementById( id );
		},
		/**
		 * Get elements by their class attribute.
		 *
		 * @return object A list of Element objects.
		 */
		getElems: function( className ) {
			return d.getElementsByClassName( className );
		},
		/**
		 * Sanitize a class.
		 *
		 * @param  string className The class(es) to sanitize.
		 * @param  string glue      String used to separate each class. Default is a space character.
		 * @return string           The sanitized class(es).
		 */
		sanitizeClass: function( className, glue ) {
			if ( undefined === glue ) {
				glue = ' ';
			}
			return className.replace( /^\s+|\s+$/g, '' ).replace( /\s+/, glue );
		},
		/**
		 * Tell if an element has a specific class.
		 *
		 * @param  object elem      The element. Can be a jQuery object or an Element object.
		 * @param  string className The class to test against.
		 * @return bool
		 */
		hasClass: function( elem, className ) {
			if ( ! elem ) {
				return false;
			}
			if ( elem instanceof jQuery ) {
				return elem.hasClass( className );
			}
			if ( elem.classList ) {
				return elem.classList.contains( className );
			}
			className = sfml.sanitizeClass( className );
			return new RegExp( '(^| )' + className + '( |$)', 'gi' ).test( elem.className );
		},
		/**
		 * Add classes) to elements.
		 *
		 * @param  object elems     A list of elements. Can be a jQuery object, a list of Element objects, or a single Element object.
		 * @param  string className The class(es) to add.
		 * @return object           The elements.
		 */
		addClass: function( elems, className ) {
			if ( elems instanceof jQuery ) {
				elems.addClass( className );
				return elems;
			}
			if ( ! elems ) {
				return elems;
			}
			if ( elems instanceof Element ) {
				elems = [ elems ];
			}
			$.each( elems, function( i, elem ) {
				if ( elem.classList ) {
					elems[ i ].classList.add( className );
				} else if ( ! sfml.hasClass( elem, className ) ) {
					elems[ i ].className = sfml.sanitizeClass( elem.className + ' ' + className );
				}
			} );
			return elems;
		},
		/**
		 * Remove class(es) from elements.
		 *
		 * @param  object elems     A list of elements. Can be a jQuery object, a list of Element objects, or a single Element object.
		 * @param  string className The class(es) to remove.
		 * @return object           The elements.
		 */
		removeClass: function( elems, className ) {
			if ( elems instanceof jQuery ) {
				elems.removeClass( className );
				return elems;
			}
			if ( ! elems ) {
				return elems;
			}
			if ( elems instanceof Element ) {
				elems = [ elems ];
			}
			$.each( elems, function( i, elem ) {
				if ( elem.classList ) {
					elems[ i ].classList.remove( className );
				} else {
					className = sfml.sanitizeClass( className, '|' );
					elems[ i ].className = sfml.sanitizeClass( elem.className.replace( new RegExp( '(^|\\b)' + className + '(\\b|$)', 'gi' ), ' ' ) );
				}
			} );
			return elems;
		},
		/**
		 * Add some text into an element.
		 *
		 * @param  object elem The element. Can be a jQuery object or an Element object.
		 * @param  string text The text.
		 * @return object      The element.
		 */
		text: function( elem, text ) {
			if ( ! elem ) {
				return elem;
			}
			if ( elem instanceof jQuery ) {
				elem.text( text );
			} else if ( undefined !== elem.textContent ) {
				elem.textContent = text;
			} else {
				elem.innerText = text;
			}
			return elem;
		},
		/**
		 * Add some html into an element.
		 *
		 * @param  object elem The element. Can be a jQuery object or an Element object.
		 * @param  string html The html.
		 * @return object      The element.
		 */
		html: function( elem, html ) {
			if ( ! elem ) {
				return elem;
			}
			if ( elem instanceof jQuery ) {
				elem.html( html );
			} else {
				elem.innerHTML = html;
			}
			return elem;
		},
		/**
		 * Get the login action from a text input.
		 *
		 * @param  object elem Text input. Can be a jQuery object or a single Element object.
		 * @return string      The login action.
		 */
		getActionFromInput: function( elem ) {
			if ( elem instanceof jQuery ) {
				return elem.attr( 'id' ).replace( 'slugs-', '' );
			}
			if ( ! elem ) {
				return '';
			}
			return elem.id.replace( 'slugs-', '' );
		},
		/**
		 * Update a slug in a "dynamic URL" element.
		 *
		 * @param  string action The login action.
		 * @param  string slug   The slug.
		 * @return object        The element.
		 */
		updateSlug: function( action, slug ) {
			var elem = sfml.getElem( sfml.dynSlugClass + '-' + action );
			return sfml.text( elem, slug );
		},
		/**
		 * Add an error message next to a "dynamic URL" element.
		 *
		 * @param  string action  The login action.
		 * @param  string message The slug.
		 * @return object         The element.
		 */
		addError: function( action, message ) {
			var elem = sfml.getElem( sfml.dynErrorClass + '-' + action );
			sfml.lastCacheKey = '';
			return sfml.html( elem, message );
		},
		/**
		 * Display the results.
		 *
		 * @param  string action The login action.
		 * @param  object result The results (like they are returned by the ajax call).
		 */
		displayResult: function( action, result ) {
			sfml.updateSlug( action, result.slugs['slugs.' + action ] );

			$.each( sfml.getElems( sfml.dynErrorClass ), function( i, elem ) {
				var fieldAction = elem.id.replace( sfml.dynErrorClass + '-' , '' );

				if ( undefined !== result.errors.forbidden[ fieldAction ] ) {
					sfml.updateSlug( fieldAction, result.slugs['slugs.' + fieldAction ] );
					sfml.html( elem, sfml.forbidden.replace( '%s', '<code>' + result.errors.forbidden[ fieldAction ] + '</code>' ) );
				} else if ( undefined !== result.errors.duplicates[ fieldAction ] ) {
					sfml.updateSlug( fieldAction, result.slugs['slugs.' + fieldAction ] );
					sfml.text( elem, sfml.duplicate );
				} else {
					sfml.text( elem, '' );
				}
			} );
		},
		/**
		 * A simple setTimeout() shorthand.
		 *
		 * @param  string callback The callback to perform.
		 * @param  string id       The setTimeout() ID.
		 */
		delay: function( callback, id ) {
			sfml.resetDelay( id );
			sfml.timeout[ id ] = w.setTimeout( callback, sfml.timeoutDuration );
		},
		/**
		 * A simple shorthand to reset a setTimeout().
		 *
		 * @param  string id The setTimeout() ID.
		 */
		resetDelay: function( id ) {
			if ( undefined !== sfml.timeout[ id ] ) {
				w.clearTimeout( sfml.timeout[ id ] );
				sfml.timeout[ id ] = undefined;
			}
		},
		/**
		 * Tell if a keybord key is "forbidden".
		 *
		 * @param  object e The event object.
		 * @return bool
		 */
		isForbiddenKey: function( e ) {
			// Enter, Alt, Escape, Left Arrow, Top Arrow, Right Arrow, Bottom Arrow.
			var keys = [ 13, 18, 27, 37, 38, 39, 40 ];
			return 'keyup' === e.type && $.inArray( e.which, keys ) !== -1;
		},
		/**
		 * Callback used when the user defines new slugs.
		 *
		 * @param  object e The event object.
		 */
		sanitizeSlugs: function( e ) {
			var elem     = this,
				data     = {},
				cacheKey = '',
				action;

			if ( sfml.isForbiddenKey( e ) ) {
				return false;
			}

			sfml.resetDelay( elem.id );

			action = sfml.getActionFromInput( elem );

			$.each( sfml.fields.serializeArray(), function( index, obj ) {
				var name     = obj.name.replace( /^sfml\[(.+)\]$/, '$1' );
				cacheKey    += name + ':' + obj.value + '|';
				data[ name ] = obj.value;
			} );

			sfml.lastCacheKey = cacheKey;

			if ( undefined !== sfml.cache[ cacheKey ] ) {
				sfml.displayResult( action, sfml.cache[ cacheKey ] );
				return true;
			}

			sfml.delay( function() {
				var params = {
					'action':   sfml.ajaxAction,
					'_wpnonce': sfml.nonce,
					'slugs':    data
				};

				if ( 'keyup' === e.type ) {
					// No need to display the spinner on the init event.
					sfml.addClass( elem, 'ui-autocomplete-loading' );
				}

				$.ajax( {
					type:     'POST',
					dataType: 'json',
					url:      ajaxurl,
					data:     params
				} )
				.done( function( r ) {
					if ( ! $.isPlainObject( r ) ) {
						sfml.addError( action, sfml.error );
						return;
					}
					if ( ! r.success ) {
						if ( 'nonce' === r.data ) {
							sfml.addError( action, sfml.errorReload );
						} else {
							sfml.addError( action, sfml.error );
						}
						return;
					}

					if ( undefined === sfml.cache[ cacheKey ] ) {
						sfml.cache[ cacheKey ] = r.data;
					}

					if ( sfml.lastCacheKey === cacheKey ) {
						sfml.displayResult( action, r.data );
					}
				} )
				.fail( function() {
					sfml.addError( action, sfml.error );
				} )
				.always( function() {
					sfml.removeClass( elem, 'ui-autocomplete-loading' );
				} );
			}, elem.id );
		},
		/**
		 * Init (because I'm sure you couldn't guess with the function name).
		 */
		init: function() {
			sfml.fields.on( 'keyup.sfml init.sfml', sfml.sanitizeSlugs ).first().trigger( 'init.sfml' );
		}
	} );

	sfml.init();

} )(jQuery, document, window);
