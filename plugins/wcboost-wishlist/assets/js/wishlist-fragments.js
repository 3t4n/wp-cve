/* global wcboost_wishlist_fragments_params */
jQuery( function( $ ) {
	if ( typeof wcboost_wishlist_fragments_params === 'undefined' ) {
		return false;
	}

	/**
	 * Wishlist fragments class.
	 */
	var WCBoostWishlistFragments = function() {
		this.updateFragments = this.updateFragments.bind( this );
		this.getProductIds   = this.getProductIds.bind( this );

		$( document.body )
			.on( 'wishlist_fragments_refresh wishlist_updated', { wishlistFragmentsHandler: this }, this.refreshFragments )
			.on( 'added_to_wishlist removed_from_wishlist', { wishlistFragmentsHandler: this }, this.updateFragmentsOnChanges );

		// Refresh fragments if the option is enabled.
		if ( 'yes' === wcboost_wishlist_fragments_params.refresh_on_load ) {
			$( document.body ).trigger( 'wishlist_fragments_refresh' );
		} else {
			// Refresh when page is shown after back button (Safari).
			$( window ).on( 'pageshow' , function( event ) {
				if ( event.originalEvent.persisted ) {
					$( document.body ).trigger( 'wishlist_fragments_refresh', [ true ] );
				}
			} );
		}
	}

	WCBoostWishlistFragments.prototype.refreshFragments = function( event, updateButtons ) {
		var self = event.data.wishlistFragmentsHandler;
		var data = { time: new Date().getTime() };

		if ( 'yes' === wcboost_wishlist_fragments_params.refresh_on_load || updateButtons ) {
			data.product_ids = self.getProductIds();
		}

		$.post( {
			url: woocommerce_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_wishlist_fragments' ),
			data: data,
			dataType: 'json',
			timeout: wcboost_wishlist_fragments_params.request_timeout,
			success: function( response ) {
				if ( ! response.success ) {
					$( document.body ).trigger( 'wishlist_fragments_failed' );

					return;
				}

				self.updateFragments( response.data.fragments );

				$( document.body ).trigger( 'wishlist_fragments_refreshed' );
			},
			error: function() {
				$( document.body ).trigger( 'wishlish_fragments_ajax_error' );
			}
		} );
	}

	WCBoostWishlistFragments.prototype.getProductIds = function() {
		var ids = [];

		$( '.wcboost-wishlist-button' ).each( function( index, button ) {
			ids.push( button.dataset.product_id );
		} );

		return ids;
	}

	WCBoostWishlistFragments.prototype.updateFragmentsOnChanges = function( event, $button, fragments ) {
		var self = event.data.wishlistFragmentsHandler;

		self.updateFragments( fragments );

		// Update buttons on product grid changes were made from elsewhere.
		if ( ! $button ) {
			self.refreshFragments( event, true );
		}
	}

	WCBoostWishlistFragments.prototype.updateFragments = function( fragments ) {
		$.each( fragments, function( key, value ) {
			$( key ).replaceWith( value );
		} );

		$( document.body ).trigger( 'wishlist_fragments_loaded' );
	}

	// Init wishlist fragments.
	new WCBoostWishlistFragments();
} );
