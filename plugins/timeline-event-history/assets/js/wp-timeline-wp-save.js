wp.TimelineWP = 'undefined' === typeof( wp.TimelineWP ) ? {} : wp.TimelineWP;

(function( $, TimelineWP ){

    var TimelineWPSaveImages = {
        updateInterval: false,

        checkSave: function() {
            var self = this;

            $('#publishing-action .spinner').addClass( 'is-active' );
            $('#publishing-action #publish').attr( 'disabled', 'disabled' );

            if ( ! self.updateInterval ) {
                self.updateInterval = setInterval( $.proxy( self.saveImages, self), 1000);
            }else{
                clearInterval( self.updateInterval );
                self.updateInterval = setInterval( $.proxy( self.saveImages, self), 1000);
            }
        },

    	saveImages: function( callback = false ) {
            var images = [],
                self = this,
                ajaxData;

            clearInterval( self.updateInterval );

            wp.TimelineWP.Items.each( function( item ) {
                var attributes = item.getAttributes();
                images[ attributes['index'] ] = attributes;
            });

            ajaxData = { '_wpnonce' : TimelineWPHelper['_wpnonce'], 'action' : 'TimelineWP_save_images', gallery : TimelineWPHelper['id'] };
            ajaxData['images'] = JSON.stringify( images );

            $.ajax({
                method: 'POST',
                url: TimelineWPHelper['ajax_url'],
                data: ajaxData,
                dataType: 'json',
            }).done(function( msg ) {
                $('#publishing-action .spinner').removeClass( 'is-active' );
                $('#publishing-action #publish').removeAttr( 'disabled' );

                if( typeof callback === "function" ) {
                    callback();
                }
            });
        },

        saveImage: function( id, callback = false ) {

            var image = wp.TimelineWP.Items.get( id ),
            	json  = image.getAttributes();

            $('#publishing-action .spinner').addClass( 'is-active' );
            $('#publishing-action #publish').attr( 'disabled', 'disabled' );

            ajaxData = { '_wpnonce': TimelineWPHelper['_wpnonce'], 'action': 'TimelineWP_save_image', 'gallery': TimelineWPHelper['id'] };
            ajaxData['image'] = JSON.stringify( json );

            $.ajax({
                method: 'POST',
                url: TimelineWPHelper['ajax_url'],
                data: ajaxData,
                dataType: 'json',
            }).done(function( msg ) {
                $('#publishing-action .spinner').removeClass( 'is-active' );
                $('#publishing-action #publish').removeAttr( 'disabled' );

                if( typeof callback === "function" ) {
                    callback();
                }
            });
        }
    }

    TimelineWP.Save = TimelineWPSaveImages;

}( jQuery, wp.TimelineWP ))
