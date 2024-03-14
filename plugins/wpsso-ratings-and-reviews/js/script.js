jQuery( function( $ ){

	/*
	 * The wpsso_rar_script is required to continue, so make sure the object exists.
	 */
	if ( typeof wpsso_rar_script === 'undefined' ) {

		return false;
	}

	$( 'body' )
		.on( 'init', '.wpsso-rar select#rating', function() {

			$( '.wpsso-rar select#rating' ).hide().before( '<p class="select-star"><span><a class="star-1" href="#">1</a><a class="star-2" href="#">2</a><a class="star-3" href="#">3</a><a class="star-4" href="#">4</a><a class="star-5" href="#">5</a></span></p>' );

		})
		.on( 'click', '#respond .wpsso-rar p.select-star a', function() {

			var $star       = $( this );
			var $rating     = $( this ).closest( '#respond' ).find( '.wpsso-rar select#rating' );
			var $container  = $( this ).closest( '.select-star' );

			$rating.val( $star.text() );

			$star.siblings( 'a' ).removeClass( 'active' );

			$star.addClass( 'active' );

			$container.addClass( 'selected' );

			return false;
		})
		.on( 'click', '#respond #submit', function() {

			/*
			 * Value tests are performed only on enabled input fields, so the rating select is disabled when the reply
			 * link is clicked, and reenabled when cancelled to avoid star value checks on replies.
			 */
			var $rating = $( this ).closest( '#respond' ).find( '.wpsso-rar select#rating:enabled' ), rating = $rating.val();
			var $review = $( this ).closest( '#respond' ).find( '.wpsso-rar textarea#comment:enabled' ), review = $review.val();

			if ( $rating.length > 0 && ! rating && wpsso_rar_script._rating_required ) {

				window.alert( wpsso_rar_script._required_rating_transl );

				return false;
			}

			if ( $review.length > 0 && ! review ) {

				window.alert( wpsso_rar_script._required_review_transl );

				return false;
			}
		})
		.on( 'click', 'a.comment-reply-link', function() {

			$('.wpsso-rar p.comment-form-rating select#rating').prop( 'disabled', true );

			$('.wpsso-rar p.comment-form-rating').hide();

			$('.wpsso-rar .comment-toggle-review').hide();

			$('.wpsso-rar .comment-toggle-comment').show();

		})
		.on( 'click', 'a#cancel-comment-reply-link', function() {

			$('.wpsso-rar .comment-toggle-comment').hide();

			$('.wpsso-rar .comment-toggle-review').show();

			$('.wpsso-rar p.comment-form-rating select#rating').prop( 'disabled', false );

			$('.wpsso-rar p.comment-form-rating').show();
		});

	$( '.wpsso-rar select#rating' ).trigger( 'init' );
});
