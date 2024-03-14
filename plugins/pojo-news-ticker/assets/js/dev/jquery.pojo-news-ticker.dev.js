/*!
* @author: Pojo Team
 */
/* global jQuery */

;( function( $, window, document, undefined ) {
	'use strict';
	
	var TRANSITION = {
		FADE: 'fade',
		SLIDE: 'slide',
		TYPING: 'typing'
	};
	
	var pluginName = "pojoNewsTicker",
		defaults = {
			effect: TRANSITION.FADE,
			delay: 2000,
			pauseHover: true,
			typingDelay: 50
		};

	function Plugin( element, options ) {
		this.$element = $( element );
		this.settings = $.extend( {}, defaults, options );
		this._defaults = defaults;
		this._name = pluginName;
		this.init();
	}
	
	Plugin.prototype = {
		init: function() {
			var self = this,
				$elem = self.$element,
				$children = $elem.children();
			
			self.running = true;
			
			$children.not( ':first' ).hide();

			if ( self.settings.pauseHover ) {
				$elem.on( 'mouseenter', function() {
					self.running = false;
				} ).on( 'mouseleave', function() {
						self.running = true;
					} );
			}
			
			if ( TRANSITION.TYPING === self.settings.effect ) {
				var currentContentIndex = 0,
					currentItemIndex = 0,
					timeoutAfterEndLine = 0,
					typingDelay = parseInt( self.settings.delay / self.settings.typingDelay ),
					inEndLine = false,
					items = [];
				
				$children.each( function() {
					var content = '<li class="ticker-item">' + $( this ).html() + '</li>';
					items.push( {
						html: content,
						content: $( this ).find( 'span.ticker-content' ).text()
					} );
				} );
				
				$elem.on( 'mouseenter', function() {
					currentContentIndex = items[currentItemIndex].content.length;
				} );
				
				var maxItems = items.length;
				setInterval( function() {
					if ( inEndLine ) {
						if ( timeoutAfterEndLine < typingDelay ) {
							timeoutAfterEndLine++;
							return;
						}
						timeoutAfterEndLine = currentContentIndex = 0;
						currentItemIndex++;
						if ( maxItems === currentItemIndex ) {
							currentItemIndex = 0;
						}
						inEndLine = false;
						return;
					}
					var currentContentResult = items[currentItemIndex].content.substring( 0, currentContentIndex ),
						$currentHtml = $( items[currentItemIndex].html );
					
					$currentHtml.find( 'span.ticker-content' ).text( currentContentResult );
					
					$( $elem ).html( $currentHtml );
					currentContentIndex++;
					if ( currentContentIndex > items[currentItemIndex].content.length ) {
						inEndLine = true;
					}
				}, self.settings.typingDelay );
			}
			
			setInterval( function() {
				if ( ! self.running ) {
					return;
				}
				var $children = $elem.children();
				$children.not( ":first" ).hide();
				var $currentItem = $children.eq( 0 );
				var $nextItem = $children.eq( 1 );

				if ( TRANSITION.FADE === self.settings.effect ) {
					$currentItem.fadeOut( function() {
						$nextItem.fadeIn();
						$currentItem
							.remove()
							.appendTo( $elem );
					} );
				}
				else if ( TRANSITION.SLIDE === self.settings.effect ) {
					$currentItem.slideUp();
					$nextItem.slideDown( function() {
						$currentItem
							.remove()
							.appendTo( $elem );
					} );
				}
			}, self.settings.delay );
		}
	};

	$.fn[ pluginName ] = function( options ) {
		this.each( function() {
			if ( ! $.data( this, "plugin_" + pluginName ) ) {
				$.data( this, "plugin_" + pluginName, new Plugin( this, options ) );
			}
		} );

		return this;
	};
} ) ( jQuery, window, document );