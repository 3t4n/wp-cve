jQuery( document ).ready( function( $ ){

    if( typeof window.elementor === 'object' ) {

        elementor.saver.on( 'page:status:change', function( newStatus ){

            if( 'publish' === newStatus ) {

                window.parent.location.href = rm_tmc_elementor.manualPostMergeUrl;

            }

        } );

    } else {

        alert( 'Something went wrong while loading Revision Manager TMC merging solution.' );

    }

} );