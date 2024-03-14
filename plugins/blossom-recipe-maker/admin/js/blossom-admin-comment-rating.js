jQuery( document ).ready(
	function($) {

		$( document ).on(
			'mouseenter',
			'.brm-comment-form-rating .brm-rating-star',
			function() {
				$( this ).prevAll().andSelf().each(
					function() {
						$( this ).find( 'polygon' ).css( 'fill', '#ffb900' );
					}
				);
				$( this ).nextAll().each(
					function() {
						$( this ).find( 'polygon' ).css( 'fill', '' );
					}
				);
			}
		);

		$( document ).on(
			'mouseleave',
			'.brm-comment-form-rating .brm-rating-star',
			function() {
				$( this ).siblings().andSelf().each(
					function() {
						$( this ).find( 'polygon' ).css( 'fill', '' );
					}
				);
			}
		);

		$( document ).on(
			'click',
			'.brm-comment-form-rating .brm-rating-star',
			function() {
				var star       = $( this ),
				rating         = star.data( 'rating' ),
				input          = star.parents( '.brm-comment-form-rating' ).find( '#brm-comment-rating' ),
				current_rating = input.val();

				if (current_rating == rating) {
					input.val( '' );

					$( this ).siblings( '' ).andSelf().each(
						function() {
							$( this ).removeClass( 'rated' );
						}
					);
				} else {
					input.val( rating );

					$( this ).prevAll().andSelf().each(
						function() {
							$( this ).addClass( 'rated' );
						}
					);
					$( this ).nextAll().each(
						function() {
							$( this ).removeClass( 'rated' );
						}
					);
				}
			}
		);
	}
);
