( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		// Display Search Box.
    	$( document ).on( 'click', '.youzify-video-lightbox', function( e ) {
    		e.preventDefault();
    		if ( ! $( 'body' ).hasClass( 'youzify-media-lightbox-loaded' ) ) {
    			$( 'body' ).addClass( 'youzify-media-lightbox-loaded' );
    			$( '<script/>', { rel: 'text/javascript', src: Youzify.assets + 'js/youzify-media-lightbox.min.js' } ).appendTo( 'head' );
    			$( this ).trigger( 'click' );
    		}
		});


		// Get Page Number
		function youzify_media_find_page_number( el ) {
			el.find( '.youzify-page-symbole' ).remove();
			return parseInt( el.text() );
		}

		// Media Widget Filter.
		$( document ).on( 'click', '.youzify-media-filter .youzify-filter-content', function( e ) {

			var button = $( this );

			if ( $( '.youzify-media-filter .youzify-filter-content.loading')[0] || button.hasClass( 'youzify-current-filter' ) ) {
				return;
			}


			// Get Data.
			var parent = button.closest( '.youzify-media' );
			var type = button.data( 'type' );

			if ( parent.find( '.youzify-media-group-' + type )[0] ) {
				// Set New Current Tab & Remove Loading Icon.
				parent.find( '.youzify-media-filter .youzify-filter-content' ).removeClass( 'youzify-current-filter' );
				button.removeClass( 'loading' ).addClass( 'youzify-current-filter' );
				parent.find( 'div[data-active="true"]' ).fadeOut( 100, function() {
					$( this ).attr( 'data-active', false );
					parent.find( '.youzify-media-group-' + type ).attr( 'data-active', true ).fadeIn();
				} );
				return;
			}

			var main_content = parent.find( '.youzify-media-widget' );


			$.ajax( {
				url: Youzify.ajax_url,
				type: 'post',
				data: {
					action: 'youzify_media_pagination',
					data : button.data(),
				},
				beforeSend: function() {
					button.addClass( 'loading' );
				},
				success: function( html ) {

				var	$c = $( '<div class="youzify-media-group-' + type + '" data-active="true"></div>' ).append( '<div class="youzify-media-widget-content">' + html + '</div>' );
				var view_all  = $c.find( '.youzify-media-view-all' ).clone();
					$c.find( '.youzify-media-view-all' ).remove();
					// Set New Current Tab & Remove Loading Icon.
					parent.find( '.youzify-media-filter .youzify-filter-content' ).removeClass( 'youzify-current-filter' );
					button.removeClass( 'loading' ).addClass( 'youzify-current-filter' );
						$c.append( view_all );
						parent.find( 'div[data-active="true"]' ).fadeOut( 100, function(){
						$( this ).attr( 'data-active', false );
						main_content.append( $c );
					});

				}
			});

		});

		// Get Comments Page
		$( document ).on( 'click', '.youzify-media .youzify-pagination a', function( e ) {

			e.preventDefault();

			var button = $( this ),
				pagination = button.closest( '.youzify-pagination' ),
				main_content = button.closest( '.youzify-media' ).find( '.youzify-media-items' );

			$.ajax( {
				url: Youzify.ajax_url,
				type: 'post',
				data: {
					action: 'youzify_media_pagination',
					data : $( this ).closest( '.youzify-pagination' ).data(),
					page: youzify_media_find_page_number( button.clone() ),
				},
				beforeSend: function() {
					var button_clone = button.clone().html( '<i class="fas fa-spinner fa-spin"></i>' );
					button.hide( 0, function(){
						button_clone.insertAfter( $( this ) );
					});
				},
				success: function( html ) {

		            $( 'html, body' ).animate( {
		                scrollTop: main_content.offset().top - 150
		            }, 1000 );

					main_content.html( html ).fadeIn();
				}
			});

		});

	});

})( jQuery );