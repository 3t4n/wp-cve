jQuery(document).ready(function (){
    jQuery('#hq-tabs').tabs();
    jQuery('.trigger').on('click', function(e){
        e.preventDefault();
    })
    /*smooth scroll disabled*/
    jQuery( window ).on( 'elementor/frontend/init', function() {
        if ( typeof elementorFrontend === 'undefined' ) {
            return;
        }

        elementorFrontend.on( 'components:init', function() {
            elementorFrontend.utils.anchors.setSettings( 'selectors.targets', '.dummy-selector' );
        } );
    } );
});
