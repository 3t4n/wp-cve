( function( $, elementor ) {

    "use strict";

    $( window ).on( 'elementor/frontend/init', function (){
        elementor.hooks.addAction( 'frontend/element_ready/lakit-social-share.default', function ( $scope ){

            if(elementor.isEditMode()){
                return;
            }

            var elementSettings = $scope.data('settings');

            var isCustomURL = elementSettings?.share_url?.url ?? false;
            var shareLinkSettings = {};

            if (isCustomURL) {
                shareLinkSettings.url = elementSettings?.share_url?.url ?? location.href;
            } else {
                shareLinkSettings.url = location.href;
                shareLinkSettings.title = elementorFrontend.config.post.title;
                shareLinkSettings.text = elementorFrontend.config.post.excerpt;
                shareLinkSettings.image = elementorFrontend.config.post.featuredImage;
            }
            shareLinkSettings.classPrefix = 'elementor-share-btn_';

            $scope.find('.elementor-share-btn').shareLink(shareLinkSettings);
        } );
    } );

}( jQuery, window.elementorFrontend ) );