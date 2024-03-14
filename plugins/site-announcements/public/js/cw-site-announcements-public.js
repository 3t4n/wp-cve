(function( $ ) {
	'use strict';

	function cwNormalizeBar() {
		var aBar = $('.cw-announcement'),
			aBarHeight = aBar.outerHeight(),
			contentTop,
			screenWidth = $('html').width();
			if( CW.admin_bar ) {
				contentTop = $('#wpadminbar').outerHeight();
				if( screenWidth <= 600 ) {
					if( $(window).scrollTop() >= 46 ) {
						contentTop = contentTop - 46;
					}
				}
			} else {
				contentTop = 0;
			}
		
		$('.cw-announcement').css('top', contentTop);
		$('html').attr('style','margin-top:' + ( parseInt(aBarHeight) + parseInt(contentTop) ) + 'px !important' );
	}

	$(document).ready( function() {

		if( !CW.user_hidden ) {

			cwNormalizeBar();

			$('a.cw-launch-modal').on('click', function(e) {

				// var offset = $(document).scrollTop();
				// var viewportHeight = $(window).height();
				// var e = $('.cw-announcement-modal').css('top').replace(/[^-\d\.]/g, '');

				$('.cw-announcement-modal').removeClass('slideOutUp')
				$('.cw-announcement-modal').addClass('slideInDown').show();

				//$('.cw-announcement-modal').css('top', parseInt(offset) + parseInt(e) + 'px' );
				$('.cw-announcement-modal').css('top', '0' );
				$('body').addClass('cw-overlay');
				$('html').addClass('cw-overlay');
				return false;
			});

			$('.cw-modal-close').on('click', function(e) {
				$('.cw-announcement-modal').removeClass('slideInDown').addClass('slideOutUp');
				$('body').removeClass('cw-overlay');
				$('html').removeClass('cw-overlay');
				setTimeout( function() {
					$('.cw-announcement-modal').removeAttr('style');
				}, 1000 );
				return false;
			});



			$('.cw-announcement .cw-close-button').on('click', function(e) {
				var aID = $(this).parent().attr('data-announcement-id');
				var expires = false;
				if( CW.closable_duration == 0 ) {
					expires = false;
				} else if( CW.closable_duration == 1 ) {
					expires = 1/24;
				} else if( CW.closable_duration == 24 ) {
					expires = 1;
				} else if( CW.closable_duration == 48 ) {
					expires = 2;
				} else if( CW.closable_duration == 72 ) {
					expires = 3;
				} else if( CW.closable_duration == 168 ) {
					expires = 7;
				} else if( CW.closable_duration == 720 ) {
					expires = 30;
				} else if( CW.closable_duration == 8760 ) {
					expires = 365;
				} else if ( CW.closable_duration == 'forever' ) {
					// ten years, heh
					expires = 3650;
				}

				if( expires ) {
					Cookies.set('cw_hide_announcement_' + aID, 'true', { expires: expires });
				}

				$('.cw-announcement').fadeOut( 500, function() {
					$('.cw-announcement').css('height','0');
					cwNormalizeBar();
				});

			});

		}

	});

	$(window).resize( function(e) {
		if( !CW.user_hidden ) {
			cwNormalizeBar();
		}
	});

	$(window).scroll( function(e) {
		if( !CW.user_hidden ) {
			cwNormalizeBar();
		}
	});

})( jQuery );
