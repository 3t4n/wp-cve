(function( $ ) {
	'use strict';
	
	$(document).ready(function() {
	
		/*
		 * click handler
		 */
		$('.social-rocket-button, .social-rocket-floating-button, .social-rocket-tweet').on('click',function(e){
			
			if ( $( this ).hasClass( 'social-rocket-no-pop' ) ) {
				$( this ).find( 'a' ).removeAttr( 'target' );
				return;
			}
			
			e.preventDefault();
			
			var href = $( this ).find('a').attr( 'href' );
			var network = $(this).data('network');
			var height = 500;
			var width = 500;
			
			if ( network === 'pinterest' ) {
				var hasMedia = href.indexOf('&media=') > 0 ? true : false;
				if ( ! hasMedia ) {
					var el = document.createElement('script');
					el.setAttribute('type','text/javascript');
					el.setAttribute('charset','UTF-8');
					el.setAttribute('src','//assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);
					document.body.appendChild(el);
					return false;
				}
				height = 900;
				width = 800;
			}
			
			var top = window.screenY + (window.innerHeight - height) / 2;
			var left = window.screenX + (window.innerWidth - width) / 2;
			var windowFeatures = 'height=' + height + ',width=' + width + ',top=' + top + ',left=' + left;
			var instance = window.open( href, '_blank', windowFeatures );
			
			return false;
		});
		
		
		/*
		 * hover handler
		 */
		/*
		$('.social-rocket-button').on('mouseenter',function(){
			// reserved for future use
		});
		
		$('.social-rocket-button').on('mouseleave',function(){
			// reserved for future use
		});
		*/
		
		
		/*
		 * More button
		 */
		$( document ).on( 'click', '.social-rocket-more-button', function( e ) {
		
			e.preventDefault();
			
			var $parent;
			var $toggle = $( this );
			var $target = $toggle.next('.social-rocket-more-buttons');
			
			var togglePosition = $toggle.position();
			
			var isFloating = $toggle.closest( '.social-rocket-floating-buttons' ).length ? true : false;
			var isInline   = $toggle.closest( '.social-rocket-buttons' ).length ? true : false;
			
			var left = 0;
			var top  = 0;
			
			if ( isFloating ) {
				$parent = $toggle.closest( '.social-rocket-floating-buttons' );
				$target.css({
					'width': ( $toggle.width() + 30 ) + 'px'
				});
			}
			
			if ( isInline ) {
				$parent = $toggle.closest( '.social-rocket-buttons' );
			}
			
			if ( $target.hasClass('bottom') ) {
				left = togglePosition.left - $target.width() / 2;
				top = togglePosition.top + $toggle.height() + 9;
			} else {
				left = togglePosition.left;
				top = togglePosition.top - $target.height() - 30;
			}
			
			if ( $parent.hasClass( 'social-rocket-position-left' ) ) {
				left = left + $toggle.width() + 5;
			} else if ( $parent.hasClass( 'social-rocket-position-right' ) ) {
				left = left - $toggle.width() - 35;
			} else if ( $parent.hasClass( 'social-rocket-position-top' ) ) {
				top = $toggle.height() + 5;
			}
			
			$target.css({
				'left': left + 'px',
				'top': top + 'px'
			});
			
			$target.toggle();
			e.stopPropagation();
		
		});

		$( document ).on( 'click', function (e) {
			$( '.social-rocket-more-buttons' ).hide();
		});
		
		$( '.social-rocket-more-buttons' ).click(function ( e ) {
			e.stopPropagation();
		});
		
		
		/*
		 * vertically center left/right floating buttons
		 */
		var $floatingButtonsToCenter = $('#social-rocket-floating-buttons.social-rocket-vertical-position-center');
		
		$floatingButtonsToCenter.css('top', ( window.innerHeight - $('#social-rocket-floating-buttons').height() ) / 2 );

		$(window).on( 'resize', function() {
			$floatingButtonsToCenter.css('top', ( window.innerHeight - $('#social-rocket-floating-buttons').height() ) / 2 );
		});
		
		/*
		 * position top floating buttons if WP admin bar present
		 */
		var $floatingButtonsToPosition = $('.admin-bar .social-rocket-floating-buttons.social-rocket-position-top');
		var floatingButtonsPositionFix = function() {
			if ( $('#wpadminbar').css('position') === 'fixed' ) { 
				$floatingButtonsToPosition.css('top','');
				return;
			}
			var adminBarHeight = $('#wpadminbar').height();
			var scrollPosition = $(window).scrollTop();
			var buttonsOffset = $floatingButtonsToPosition.offset().top;
			$floatingButtonsToPosition.css('top',( adminBarHeight - scrollPosition > 0 ? adminBarHeight - scrollPosition : 0 )+'px');
		}
		if ( $floatingButtonsToPosition.length ) {
			$(window).on( 'scroll', function() {
				floatingButtonsPositionFix();
			});
			floatingButtonsPositionFix();
		}
		
		/*
		 * add padding for top/bottom floating buttons
		 */
		var $topFloatingButtons = $('.social-rocket-floating-buttons.social-rocket-position-top');
		var $bottomFloatingButtons = $('.social-rocket-floating-buttons.social-rocket-position-bottom');
		var floatButtonsPaddingFix = function() {
			var buttonBarHeight;
			if ( $topFloatingButtons.length ) {
				buttonBarHeight = $topFloatingButtons.is(':visible') ? $topFloatingButtons.height() : 0;
				$('body').css('padding-top',buttonBarHeight+'px');
			}
			if ( $bottomFloatingButtons.length ) {				
				buttonBarHeight = $bottomFloatingButtons.is(':visible') ? $bottomFloatingButtons.height() : 0;
				$('body').css('padding-bottom',buttonBarHeight+'px');
			}
		}
		if ( $topFloatingButtons.length || $bottomFloatingButtons.length ) {
			$(window).on( 'resize', function() {
				floatButtonsPaddingFix();
			});
			floatButtonsPaddingFix();
		}
	
	});
	
})( jQuery );
