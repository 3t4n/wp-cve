( function( $ ) {
	$( document ).ready( function() {
	    $( '#tstmnls_gdpr' ).on( 'change', function() {
			if( $( this ).is( ':checked' ) ) {
				$( '#tstmnls_gdpr_link_options' ).show();
			} else {
				$( '#tstmnls_gdpr_link_options' ).hide();
			}
		} ).trigger( 'change' );

		var reviews_btn = $( '.tstmnls-all-btn' );
		if ( reviews_btn.length > 0 ) {
			var amount_of_posts = 0,
				post_id = -1,
				ajax_ready = true;

			reviews_btn.click( function() {
								
				$( 'body' ).css( 'overflow', 'hidden' ).append(
					'<div class="tstmnls-blurred-bg"><button type="button" aria-label="Close" class="tstmnls-blurred-bg-close">&#10005;</button></div>',
					'<div class="tstmnls-reviews-popup">' + $( '.tstmnls-total' ).html() + '<div class="tstmnls-all-reviews"><div class="tstmnls-loader"><div></div><div></div><div></div><div></div></div></div></div>'
				);

				post_id = $( this ).attr( 'data-post-id' );

				$.ajax( {
					type : 'POST',
					url : params.ajaxurl,
					data : {
						action: 'load_reviews',
						post_id: post_id,
					},
					success: function( response ) {
						$( response ).insertBefore( '.tstmnls-loader' );

						var scrollable_area = $( window.innerWidth > 782 ? '.tstmnls-all-reviews' : '.tstmnls-reviews-popup' );

						amount_of_posts = scrollable_area.scroll( load_more_reviews ).find( '.tstmnls-single-review' ).length;
						
						if ( scrollable_area[0].scrollHeight == scrollable_area[0].offsetHeight ) {
							scrollable_area.trigger( 'scroll' );
						}
					}
				} );
			} );

			function load_more_reviews( e ) {
				var to_bottom = e.target.scrollHeight - e.target.scrollTop - e.target.offsetHeight;

				if ( ajax_ready && to_bottom < 200 ) {
					ajax_ready = false;

					$.ajax( {
						type : 'POST',
						url : params.ajaxurl,
						data : {
							action: 'load_reviews',
							post_id: post_id,
							offset: amount_of_posts,
						},
						success: function( response ) {

							ajax_ready = !! response;

							if ( ajax_ready ) {
								$( response ).insertBefore( '.tstmnls-loader' )
							} else {
								$( '.tstmnls-loader' ).remove();
							}

							// Counting amount of posts in respose string.
							// We need to wrap the response in div tags because jquery ignores outermost elements.
							amount_of_posts += $( '.tstmnls-single-review', '<div>' + response + '</div>' ).length;
						},
					} );
				}
			}

			$( 'body' ).delegate( '.tstmnls-blurred-bg', 'click', function() {
				$( 'body' ).css( 'overflow', 'initial' );
				$( this ).remove();
				$( '.tstmnls-reviews-popup' ).remove();
				ajax_ready = true;
			} );
		}
	} );
} )( jQuery );
