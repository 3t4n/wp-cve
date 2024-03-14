'use strict';

( function ( $ ) {
	let doc = $( document );

	doc.ready( function () {
		$( ".fsp-modal-footer .share_btn" ).click( function () {
			var nodes = [];
			$( ".fsp-modal-body input[name='share_on_nodes[]']" ).each( function () {
				nodes.push( $( this ).val() );
			} );

			if ( nodes.length == 0 )
			{
				FSPoster.toast( fsp__( 'No selected account!' ), 'warning' );
				return;
			}

			FSPoster.ajax( 'share_saved_post', {
				'post_id': FSPObject.postID,
				'nodes': nodes
			}, function () {
				$( '[data-modal-close=true]' ).click();

				FSPoster.loadModal( 'share_feeds', { 'post_id': FSPObject.postID }, true );

			} );
		} );
	} );
} )( jQuery );