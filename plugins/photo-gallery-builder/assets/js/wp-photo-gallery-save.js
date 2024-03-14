wp.PhotoGallery = 'undefined' === typeof( wp.PhotoGallery ) ? {} : wp.PhotoGallery;

(function( $, PhotoGallery ){

    var PhotoGallerySaveImages = {
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

            wp.PhotoGallery.Items.each( function( item ) {
                var attributes = item.getAttributes();
                images[ attributes['index'] ] = attributes;
            });

            ajaxData = { 
                '_wpnonce'  : PhotoGalleryHelper['_wpnonce'], 
                'action'    : 'photo_gallery_save_images', 
                'gallery'   : PhotoGalleryHelper['id'], 
                'images'    : images
            };
            
            $.ajax({
                method: 'POST',
                url: PhotoGalleryHelper['ajax_url'],                
                dataType: 'json',
                data: ajaxData,
            }).done(function( msg ) {
                $('#publishing-action .spinner').removeClass( 'is-active' );
                $('#publishing-action #publish').removeAttr( 'disabled' );

                if( typeof callback === "function" ) {
                    callback();
                }
            });
        },

        saveImage: function( id, callback = false ) {

            var image = wp.PhotoGallery.Items.get( id ),
            	json  = image.getAttributes();

            $('#publishing-action .spinner').addClass( 'is-active' );
            $('#publishing-action #publish').attr( 'disabled', 'disabled' );

            ajaxData = { '_wpnonce': PhotoGalleryHelper['_wpnonce'], 'action': 'photo_gallery_save_image', 'gallery': PhotoGalleryHelper['id'] };
            ajaxData['image'] = JSON.stringify( json );

            $.ajax({
                method: 'POST',
                url: PhotoGalleryHelper['ajax_url'],
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

    PhotoGallery.Save = PhotoGallerySaveImages;

}( jQuery, wp.PhotoGallery ))
