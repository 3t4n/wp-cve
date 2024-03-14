jQuery( document ).ready(
	function( $ ) {
		var publisherFaviconElement = $( '#wpprfm-publisher-favicon' );

		$( '#wppfm-feed-file-name' ).on(
			'focusout',
			function() {
				wppfm_mainInputChanged( false );
			}
		);

		$( '#wpprfm-publisher-name' ).on(
			'focusout',
			function() {
				wppfm_mainInputChanged( false );
			}
		);

		$( '#wpprfm-aggregator-name' ).on(
			'focusout',
			function() {
				wppfm_setGoogleFeedTitle( $( '#wpprfm-aggregator-name' ).val() );
			}
		);

		publisherFaviconElement.on(
			'focusout',
			function() {
				if ( wpprfm_validFIconUrl( publisherFaviconElement.val() ) ) {
					wpprfm_setPublisherFavicon( publisherFaviconElement.val() );
				} else {
					publisherFaviconElement.val( '' );
				}
			}
		);

		$( '#wpprfm-generate-review-feed-button-top' ).on(
			'click',
			function() {
				wpprfm_startReviewFeedGeneration();
			}
		);

		$( '#wpprfm-generate-review-feed-button-bottom' ).on(
			'click',
			function() {
				wpprfm_startReviewFeedGeneration();
			}
		);

		$( '#wpprfm-save-review-feed-button-top' ).on(
			'click',
			function() {
				wpprfm_saveFeedData();
			}
		);

		$( '#wpprfm-save-review-feed-button-bottom' ).on(
			'click',
			function() {
				wpprfm_saveFeedData();
			}
		);
	}
);
