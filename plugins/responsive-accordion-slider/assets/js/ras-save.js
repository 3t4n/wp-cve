wp.RespAccordionSlider = 'undefined' === typeof( wp.RespAccordionSlider ) ? {} : wp.RespAccordionSlider;

(function( $, RespAccordionSlider ){

    var RespAccordionSliderSaveImages = {
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

            wp.RespAccordionSlider.Items.each( function( item ) {
                var attributes = item.getAttributes();
                images[ attributes['index'] ] = attributes;
            });

            ajaxData = { '_wpnonce' : RespAccordionSliderHelper['_wpnonce'], 'action' : 'accordion_slider_save_images', gallery : RespAccordionSliderHelper['id'] };
            ajaxData['images'] = JSON.stringify( images );

            $.ajax({
                method: 'POST',
                url: RespAccordionSliderHelper['ajax_url'],
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

            var image = wp.RespAccordionSlider.Items.get( id ),
            	json  = image.getAttributes();

            $('#publishing-action .spinner').addClass( 'is-active' );
            $('#publishing-action #publish').attr( 'disabled', 'disabled' );

            ajaxData = { '_wpnonce': RespAccordionSliderHelper['_wpnonce'], 'action': 'accordion_slider_save_image', 'gallery': RespAccordionSliderHelper['id'] };
            ajaxData['image'] = JSON.stringify( json );

            $.ajax({
                method: 'POST',
                url: RespAccordionSliderHelper['ajax_url'],
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

    RespAccordionSlider.Save = RespAccordionSliderSaveImages;

}( jQuery, wp.RespAccordionSlider ))
