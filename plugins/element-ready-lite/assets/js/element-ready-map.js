;(function ($) {

    var Element_Ready_Maps_Script = function( $scope, $ ){

        var element       = $scope.find( '.element__ready__google__map__wrap' ).eq(0);
        var settings      = element.data('options');
        var activation_id = element.attr('id');
        var wrap_id       = $( '#' + activation_id );
        var access_token  = settings.access_token;
        var style         = settings.style;
        var latitude      = settings.latitude;
        var longitude     = settings.longitude;
        var zoom          = settings.zoom;
        var minZoom       = settings.minZoom;
        var maxZoom       = settings.maxZoom;
        var scrollZoom    = settings.scrollZoom;
        var hash          = settings.hash;
        var interactive   = settings.interactive;
        var enable_marker = settings.marker;

        mapboxgl.accessToken = access_token;
        var map = new mapboxgl.Map({
            container  : activation_id,
            center     : [longitude, latitude],
            zoom       : zoom,
            minZoom    : minZoom,
            maxZoom    : maxZoom,
            scrollZoom : scrollZoom,
            interactive: interactive,
            hash       : hash,
            style      : style
        });

        if( enable_marker === true ){

            var marker_type     = settings.marker_type;
            var marker_color    = settings.marker_color;
            var marker_scale    = settings.marker_scale;
            var marker_position = settings.marker_position;
            var popup_content   = settings.popup_content;

            if( 'marker' === marker_type ){

                /*============================
                    FOR ONLY MARKER
                =============================*/
                var marker = new mapboxgl.Marker({
                    color: marker_color,
                    scale: marker_scale,
                }).setLngLat([longitude, latitude]).addTo(map);

            }else if( 'popup' === marker_type ){

                /*============================
                    FOR ONLY POPUP
                =============================*/
                var popup = new mapboxgl.Popup({ 
                    closeOnClick: false,
                    anchor      : marker_position,
                }).setLngLat([longitude, latitude])
                .setHTML(popup_content)
                .addTo(map);

            }else if( 'marker_with_popup' === marker_type ){

                /*============================
                    FOR POPUP & MARKER
                =============================*/
                var marker = new mapboxgl.Marker({
                    color: marker_color,
                    scale: marker_scale,
                }).setPopup(new mapboxgl.Popup(
                    {
                        anchor: marker_position,
                        closeOnClick:false,
                    }
                ).setHTML(popup_content))
                .setLngLat([longitude, latitude]).addTo(map);
            }
        }

    };

	$(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/Element_Ready_Maps_Widget.default', Element_Ready_Maps_Script );
    });

})(jQuery);


