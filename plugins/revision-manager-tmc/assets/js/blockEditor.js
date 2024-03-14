jQuery( document ).ready( function( $ ){

    var isActionLocked = false;

    wp.data.subscribe( function(){

        if( wp.data.select('core/editor').isSavingPost() && ! wp.data.select('core/editor').isAutosavingPost() ){

            if( ! wp.data.select('core/editor').isCurrentPostPublished() && ! isActionLocked ){

                isActionLocked = true;  //  Do not allow running this thing multiple times.

                //  Repeat checking what is happening.
                var rm_tmc_publishPostInterval = setInterval( function(){

                    if( ! wp.data.select('core/editor').isSavingPost() && wp.data.select('core/editor').didPostSaveRequestSucceed() ){

                        clearInterval( rm_tmc_publishPostInterval );

                        if( wp.data.select('core/editor').isCurrentPostPublished() ){
                            $( 'body' ).addClass( 'rm_tmc-locked' );
                            window.location.href = rm_tmc_blockEditor.manualPostMergeUrl;
                        } else {
                            isActionLocked = false;
                        }

                    }

                }, 500 );

            }

        }

    } );

} );