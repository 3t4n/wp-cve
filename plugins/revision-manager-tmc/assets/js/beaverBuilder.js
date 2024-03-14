( function( $, FLBuilder ) {

    var rm_tmc = {

        init:                   function() {

            if( FLBuilder ){

                FLBuilder.addHook( 'didPublishLayout', this.onRedirectToMergePosts.bind( this ) );

            }

        },

        onRedirectToMergePosts:   function() {

            window.location.href = rm_tmc_beaverBuilder.manualPostMergeUrl;

        }

    };

    rm_tmc.init();

})( jQuery, FLBuilder );