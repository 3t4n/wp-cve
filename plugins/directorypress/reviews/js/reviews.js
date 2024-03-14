(function($){
	jQuery(document ).ready(function(){
		var $rates = jQuery('#new_rating_wrapper'),
			path = $rates.data('assets_path' ),
			default_rating = 4;

		if ( typeof jQuery('#new_listing_rating').attr('data-dirrater') !== 'undefined' ) {
			default_rating = jQuery('#new_listing_rating').attr('data-dirrater');
		}

		$rates.raty({
			half: false,
			target : '#new_listing_rating',
			hints: direviews.hints,
			path: path,
			targetKeep : true,
			//targetType : 'score',
			targetType : 'hint',
			//precision  : true,
			score: default_rating,
			scoreName: 'dirrater',
			click: function(rating, evt) {
				jQuery('#new_listing_rating' ).val( '' + rating );
				jQuery('#new_listing_rating option[value="' + rating + '"]' ).attr( 'selected', 'selected' );
			},
			starType : 'i'
		});

		jQuery('.review_rate' ).raty({
			readOnly: true,
			target : this,
			half: false,
			starType : 'i',
			score: function() {
				return jQuery(this).attr('data-dirrater');
			},
			scoreName: 'dirrater'
		});
	});
})(jQuery);