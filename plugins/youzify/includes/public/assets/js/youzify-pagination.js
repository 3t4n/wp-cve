( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		// Get Page Number
		function find_page_number( el ) {
			el.find( '.youzify-page-symbole' ).remove();
			return parseInt( el.text() );
		}

		// Get Posts Page
		$( document ).on( 'click', '.posts-nav-links a', function( e ) {

			e.preventDefault();

            $( 'html, body' ).animate( {
                scrollTop: $( '.youzify-posts' ).offset().top - 150
            }, 1000 );

			// Get Page Number
			var page = find_page_number( $( this ).clone() ),
				base = $( this ).closest( '.youzify-pagination' ).attr( 'data-base' );

			$.ajax( {
				url: ajaxpagination.ajaxurl,
				type: 'post',
				data: {
					action: 'youzify_pages_pagination',
					query_vars: ajaxpagination.query_vars,
					youzify_base: base,
					youzify_page: page
				},
				beforeSend: function() {
					$( '#youzify-main-posts' ).find( '.youzify-posts-page' ).remove();
					$( document ).scrollTop();
					$( '#youzify-main-posts .youzify-loading' ).show();
				},
				success: function( html ) {
					$( '#youzify-main-posts .youzify-loading' ).hide();
					$( '#youzify-main-posts' ).append( html );
				}
			})

		});

		// Get Courses Page
		$( document ).on( 'click', '.courses-nav-links a', function( e ) {

			e.preventDefault();

            $( 'html, body' ).animate( {
                scrollTop: $( '.youzify-courses' ).offset().top - 150
            }, 1000 );

			// Get Page Number
			var page = find_page_number( $( this ).clone() ),
				base = $( this ).closest( '.youzify-pagination' ).attr( 'data-base' );

			$.ajax( {
				url: ajaxpagination.ajaxurl,
				type: 'post',
				data: {
					action: 'youzify_courses_pagination',
					query_vars: ajaxpagination.query_vars,
					youzify_base: base,
					youzify_page: page
				},
				beforeSend: function() {
					$( '#youzify-main-courses' ).find( '.youzify-courses-page' ).remove();
					$( '#youzify-main-courses' ).find( '.youzify-courses-page-grid' ).remove();
					$( document ).scrollTop();
					$( '#youzify-main-courses .youzify-loading' ).show();
				},
				success: function( html ) {
					$( '#youzify-main-courses .youzify-loading' ).hide();
					$( '#youzify-main-courses' ).append( html );
					if ($('#btn-switch').hasClass('fa-th')) {
			
						$('.youzify-courses-page').removeClass('youzify-courses-page').addClass('youzify-courses-page-grid');
						$('.youzify-tab-course').removeClass('youzify-tab-course').addClass('youzify-tab-course-grid');
						$('.youzify-course-thumbnail').removeClass('youzify-course-thumbnail').addClass('youzify-course-thumbnail-grid');
						$('.youzify-no-thumbnail').removeClass('youzify-no-thumbnail').addClass('youzify-no-thumbnail-grid');
			
						// icon.removeClass('fa-th-list').addClass('fa-th');
					} else {
						$('.youzify-courses-page-grid').removeClass('youzify-courses-page-grid').addClass('youzify-courses-page');
						$('.youzify-tab-course-grid').removeClass('youzify-tab-course-grid').addClass('youzify-tab-course');
						$('.youzify-course-thumbnail-grid').removeClass('youzify-course-thumbnail-grid').addClass('youzify-course-thumbnail');
						$('.youzify-no-thumbnail-grid').removeClass('youzify-no-thumbnail-grid').addClass('youzify-no-thumbnail');
						// icon.removeClass('fa-th').addClass('fa-th-list');
					}
				}
			})

		});
		
		// Get Tutor Courses Page
		$( document ).on( 'click', '.tutor-courses-nav-links a', function( e ) {

			e.preventDefault();

            $( 'html, body' ).animate( {
                scrollTop: $( '.youzify-courses' ).offset().top - 150
            }, 1000 );

			// Get Page Number
			var page = find_page_number( $( this ).clone() ),
				base = $( this ).closest( '.youzify-pagination' ).attr( 'data-base' );

			$.ajax( {
				url: ajaxpagination.ajaxurl,
				type: 'post',
				data: {
					action: 'youzify_tutor_courses_pagination',
					query_vars: ajaxpagination.query_vars,
					youzify_base: base,
					youzify_page: page
				},
				beforeSend: function() {
					$( '#youzify-main-courses' ).find( '.youzify-courses-page' ).remove();
					$( '#youzify-main-courses' ).find( '.youzify-courses-page-grid' ).remove();
					$( document ).scrollTop();
					$( '#youzify-main-courses .youzify-loading' ).show();
				},
				success: function( html ) {
					$( '#youzify-main-courses .youzify-loading' ).hide();
					$( '#youzify-main-courses' ).append( html );
					if ($('#btn-switch').hasClass('fa-th')) {
			
						$('.youzify-courses-page').removeClass('youzify-courses-page').addClass('youzify-courses-page-grid');
						$('.youzify-tab-course').removeClass('youzify-tab-course').addClass('youzify-tab-course-grid');
						$('.youzify-course-thumbnail').removeClass('youzify-course-thumbnail').addClass('youzify-course-thumbnail-grid');
						$('.youzify-no-thumbnail').removeClass('youzify-no-thumbnail').addClass('youzify-no-thumbnail-grid');
			
						// icon.removeClass('fa-th-list').addClass('fa-th');
					} else {
						$('.youzify-courses-page-grid').removeClass('youzify-courses-page-grid').addClass('youzify-courses-page');
						$('.youzify-tab-course-grid').removeClass('youzify-tab-course-grid').addClass('youzify-tab-course');
						$('.youzify-course-thumbnail-grid').removeClass('youzify-course-thumbnail-grid').addClass('youzify-course-thumbnail');
						$('.youzify-no-thumbnail-grid').removeClass('youzify-no-thumbnail-grid').addClass('youzify-no-thumbnail');
						// icon.removeClass('fa-th').addClass('fa-th-list');
					}
				}
			})

		});

		// Get Comments Page
		$( document ).on( 'click', '.comments-nav-links a', function( e ) {

			e.preventDefault();

            $( 'html, body' ).animate( {
                scrollTop: $( '.youzify-comments' ).offset().top - 150
            }, 1000 );

			// Get Page Number
			var cpage = find_page_number( $( this ).clone() ),
				cbase = $( this ).closest( '.youzify-pagination' ).attr( 'data-base' );

			$.ajax( {
				url: ajaxpagination.ajaxurl,
				type: 'post',
				data: {
					action: 'youzify_comments_pagination',
					query_vars: ajaxpagination.query_vars,
					youzify_base: cbase,
					youzify_page: cpage
				},
				beforeSend: function() {
					$( '#youzify-main-comments' ).find( '.youzify-comments-page' ).remove();
					$( document ).scrollTop();
					$( '#youzify-main-comments .youzify-loading' ).show();
				},
				success: function( html ) {
					$( '#youzify-main-comments .youzify-loading' ).hide();
					$( '#youzify-main-comments' ).append( html );
				}
			})

		});

	});

})( jQuery );