function wppfmAddToQueueString( idToAdd ) {
	var dataStorageElement = jQuery( '#wppfm-feed-list-page-data-storage' );

	if ( wppfmQueueStringIsEmpty() ) {
		dataStorageElement.data( 'wppfmFeedsInQueue', idToAdd.toString() );
	} else {
		dataStorageElement.data( 'wppfmFeedsInQueue', dataStorageElement.data( 'wppfmFeedsInQueue' ) + ',' + idToAdd.toString() );
	}
}

function wppfmRemoveFromQueueString( idToRemove ) {
	var dataStorageElement = jQuery( '#wppfm-feed-list-page-data-storage' );
	var currentString = dataStorageElement.data( 'wppfmFeedsInQueue' );

	if ( currentString.indexOf( ',' ) > -1 ) {
		currentString = currentString.endsWith( idToRemove ) ? currentString.replace( idToRemove, '' ) : currentString.replace( idToRemove + ',', '' );
		dataStorageElement.data( 'wppfmFeedsInQueue', currentString );
	} else {
		wppfmClearQueueString();
	}
}

function wppfmQueueStringIsEmpty() {
	return jQuery( '#wppfm-feed-list-page-data-storage' ).data( 'wppfmFeedsInQueue' ).length < 1;
}

function wppfmClearQueueString() {
	jQuery( '#wppfm-feed-list-page-data-storage' ).data( 'wppfmFeedsInQueue', '' );
}
