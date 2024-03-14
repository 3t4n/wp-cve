(
	function( $ ) {

		const fileNameElement  = $( '#wppfm-feed-file-name' );
		const merchantsElement = $( '#wppfm-merchants-selector' );
		const googleFeedType   = $( '#wppfm-feed-types-selector' );
		const drmFeedType      = $( '#wppfm-feed-drm-types-selector' );
		const countriesElement = $( '#wppfm-countries-selector' );
		const level0Element    = $( '#lvl_0' );

		// monitor the four main feed settings and react when they change
		fileNameElement.on(
			'focusout',
			function() {
				if ( '' !== fileNameElement.val() ) {
					googleFeedType.prop( 'disabled', false );
					countriesElement.prop( 'disabled', false );
					level0Element.prop( 'disabled', false );
					if ( false === wppfm_validateFileName( fileNameElement.val() ) ) {
						fileNameElement.val( '' );
					}

					if ( '0' !== merchantsElement.val() ) {
						wppfm_showChannelInputs( merchantsElement.val(), true );
						wppfm_mainInputChanged( false );
					} else {
						wppfm_hideFeedFormMainInputs();
					}
				} else {
					googleFeedType.prop( 'disabled', true );
					countriesElement.prop( 'disabled', true );
					level0Element.prop( 'disabled', true );
				}
			}
		);

		fileNameElement.on(
			'keyup',
			function() {

				if ( '' !== fileNameElement.val() ) {
					googleFeedType.prop( 'disabled', false );
					countriesElement.prop( 'disabled', false );
					level0Element.prop( 'disabled', false );
				} else {
					googleFeedType.prop( 'disabled', true );
					countriesElement.prop( 'disabled', true );
					level0Element.prop( 'disabled', true );
				}
			}
		);

		merchantsElement.on(
			'change',
			function() {
				if ( '0' !== merchantsElement.val() && '' !== $( '#wppfm-feed-file-name' ).val() ) {
					wppfm_showChannelInputs( merchantsElement.val(), true );
					wppfm_mainInputChanged( false );
				} else {
					wppfm_hideFeedFormMainInputs();
				}
			}
		);

		googleFeedType.on(
			'change',
			function() {
				const selectedGoogleFeedType = googleFeedType.val();
				wppfm_setGoogleFeedType( selectedGoogleFeedType );
				const currentFeedTypeForm = wppfm_getUrlParameter( 'feed-type' )

				if ( '1' === selectedGoogleFeedType && 'product-feed' === currentFeedTypeForm ) {
					wppfm_mainInputChanged( false );
				} else {
					wppfm_handleSupportFeedSelection(selectedGoogleFeedType);
				}
			}
		)

		drmFeedType.on(
			'change',
			function() {
				const selectedDrmFeedType = $( '#wppfm-feed-drm-types-selector' ).val();
				wppfm_setDrmFeedTypeAttributes( selectedDrmFeedType );
				wppfm_setDrmBusinessType( selectedDrmFeedType );
			}
		)

		countriesElement.on(
			'change',
			function() {
				if ( '0' !== countriesElement.val() ) {
					level0Element.prop( 'disabled', false );
				}

				wppfm_mainInputChanged( false );
			}
		);

		$( '#wppfm-feed-language-selector' ).on(
			'change',
			function() {
				wppfm_setGoogleFeedLanguage( $( '#wppfm-feed-language-selector' ).val() );

				if ( wppfm_requiresLanguageInput ) {
					wppfm_mainInputChanged( false );
				}
			}
		);

		$( '#wppfm-feed-currency-selector' ).on(
			'change',
			function() {
				wppfm_setGoogleFeedCurrency( $( '#wppfm-feed-currency-selector' ).val() );
			}
		);

		$( '#google-feed-title-selector' ).on(
			'change',
			function() {
				wppfm_setGoogleFeedTitle( $( '#google-feed-title-selector' ).val() );
			}
		);

		$( '#google-feed-description-selector' ).on(
			'change',
			function() {
				wppfm_setGoogleFeedDescription( $( '#google-feed-description-selector' ).val() );
			}
		);

		$( '#variations' ).on(
			'change',
			function() {
				wppfm_variation_selection_changed();
			}
		);

		$( '#aggregator' ).on(
			'change',
			function() {
				wppfm_aggregatorChanged();
				wppfm_drawAttributeMappingSection(); // reset the attribute mapping
			}
		);

		level0Element.on(
			'change',
			function() {
				wppfm_mainInputChanged( true );
			}
		);

		$( '.wppfm-cat-selector' ).on(
			'change',
			function() {
				wppfm_nextCategory( this.id );
			}
		);

		$( '#wppfm-generate-feed-button-top' ).on(
			'click',
			function() {
				wppfm_generateFeed();
			}
		);

		$( '#wppfm-generate-feed-button-bottom' ).on(
			'click',
			function() {
				wppfm_generateFeed();
			}
		);

		$( '#wppfm-save-feed-button-top' ).on(
			'click',
			function() {
				wppfm_saveFeedData();
			}
		);

		$( '#wppfm-view-feed-button-top' ).on(
			'click',
			function() {
				wppfm_viewFeed( $( '#wppfm-feed-editor-page-data-storage' ).data( 'wppfmFeedUrl' ) );
			}
		);

		$( '#wppfm-view-feed-button-bottom' ).on(
			'click',
			function() {
				wppfm_viewFeed( $( '#wppfm-feed-editor-page-data-storage' ).data( 'wppfmFeedUrl' ) );
			}
		);

		$( '#wppfm-save-feed-button-bottom' ).on(
			'click',
			function() {
				wppfm_saveFeedData();
			}
		);

		$( '#days-interval' ).on(
			'change',
			function() {
				wppfm_saveUpdateSchedule();
			}
		);

		$( '#update-schedule-hours' ).on(
			'change',
			function() {
				wppfm_saveUpdateSchedule();
			}
		);

		$( '#update-schedule-minutes' ).on(
			'change',
			function() {
				wppfm_saveUpdateSchedule();
			}
		);

		$( '#update-schedule-frequency' ).on(
			'change',
			function() {
				wppfm_saveUpdateSchedule();
			}
		);

		$( '#wppfm-auto-feed-fix-mode' ).on(
			'change',
			function() {
				wppfm_auto_feed_fix_changed();
			}
		);

		$( '#wppfm-background-processing-mode' ).on(
			'change',
			function() {
				wppfm_clear_feed_process();
				wppfm_background_processing_mode_changed();
			}
		);

		$( '#wppfm-process-logging-mode' ).on(
			'change',
			function() {
				wppfm_feed_logger_status_changed();
			}
		);

		$( '#wppfm-product-identifiers' ).on(
			'change',
			function() {
				wppfm_show_product_identifiers_changed();
			}
		);

		$( '#wppfm-wpml-use-full-resolution-urls' ).on(
			'change',
			function() {
				wppfm_wpml_use_full_resolution_urls_changed();
			}
		)

		$( '#wppfm-third-party-attr-keys' ).on(
			'focusout',
			function() {
				wppfm_third_party_attributes_changed();
			}
		);

		$( '#wppfm-notice-mailaddress' ).on(
			'focusout',
			function() {
				wppfm_notice_mailaddress_changed();
			}
		);

		$( '#wppfm-clear-feed-process-button' ).on(
			'click',
			function() {
				wppfm_clear_feed_process();
			}
		);

		$( '#wppfm-reinitiate-plugin-button' ).on(
			'click',
			function() {
				wppfm_reinitiate();
			}
		);

		$( '.wppfm-category-mapping-selector' ).on( // on activation of a category selector in the Category Mapping table
			'change',
			function() {
				if ( $( this ).is( ':checked' ) ) {
					console.log( 'category ' + $( this ).val() + ' selected' );
					wppfm_activateFeedCategoryMapping( $( this ).val() );
				} else {
					console.log( 'category ' + $( this ).val() + ' deselected' );
					wppfm_deactivateFeedCategoryMapping( $( this ).val() );
				}
			}
		);

		$( '.wppfm-category-selector' ).on( // on activation of a category selector in the Category Selector table
			'change',
			function() {
				if ( $( this ).is( ':checked' ) ) {
					console.log( 'category ' + $( this ).val() + ' selected' );
					wppfm_activateFeedCategorySelection( $( this ).val() );
				} else {
					console.log( 'category ' + $( this ).val() + ' deselected' );
					wppfm_deactivateFeedCategorySelection( $( this ).val() );
				}
			}
		);

		$( '#wppfm-categories-select-all' ).on( // on activation of the 'all' selector in the Category Mapping and Category Selector table
			'change',
			function() {
				if ( $( this ).is( ':checked' ) ) {
					wppfm_activateAllFeedCategoryMapping();
				} else {
					wppfm_deactivateAllFeedCategoryMapping();
				}
			}
		);

		$( '#wppfm-accept-eula' ).on(
			'change',
			function() {
				if ( $( this ).is( ':checked' ) ) {
					$( '#wppfm-license-activate' ).prop( 'disabled', false );
				} else {
					$( '#wppfm-license-activate' ).prop( 'disabled', true );
				}
			}
		);

		//$( '.edit-output' ).click( function () { wppfm_editOutput( this.id ); } ); TODO: Check this out later. The this.id should get the id of the link but it doesn't.

		$( '#wppfm-prepare-backup' ).on(
			'click',
			function() {
				$( '#wppfm-backup-file-name' ).val( '' );
				$( '#wppfm-backup-wrapper' ).show();
			}
		);

		$( '#wppfm-make-backup-button' ).on(
			'click',
			function() {
				wppfm_backup();
			}
		);

		$( '#wppfm-cancel-backup-button' ).on(
			'click',
			function() {
				$( '#wppfm-backup-wrapper' ).hide();
			}
		);

		$( '#wppfm-backup-file-name' ).on(
			'keyup',
			function() {
				if ( '' !== $( '#wppfm-backup-file-name' ).val ) {
					$( '#wppfm-make-backup-button' ).attr( 'disabled', false );
				}
			}
		);

		$( '.wppfm-popup__close-button' ).on(
				'click',
				function() {
					$( '#wppfm-channel-info-popup' ).hide();
				}
		)

	}( jQuery )
);
