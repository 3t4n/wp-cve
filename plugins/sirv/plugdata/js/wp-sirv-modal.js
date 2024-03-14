jQuery(function($){

    $(document).ready(function(){

        function renderSirvModalWindow(){
            if(!modal_object){
                console.log('Modal data did not find. Please try reload page.');
                return false;
            }

            if(modal_object.isNotEmptySirvOptions == '1'){
                    //$('body').on('click', '.sirv-modal-click, .sirv-g-add-media', function () {
                    $('body').on('click', '.sirv-modal-click', function () {
                        window.isAddMedia = true;
                        window['bPopup'] = $('.sirv-modal').bPopup({
                            position: ['auto', 'auto'],
                            zIndex: 9999999,
                            //contentContainer:'.modal-content',
                            loadUrl: modal_object.media_add_url,
                            loadCallback: function(){
                                $('.loading-ajax').show();
                                getContentFromSirv(window.sirvGetPath());
                            }
                        });
                    });

                    $('body').on('click', '.sirv-add-image-modal-click', function () {
                        //window.isAddMedia = true;
                        let $modalBlock = $('.sirv-modal').length > 1 ? $($('.sirv-modal')[0]) : $('.sirv-modal');
                        window['bPopup'] = $modalBlock.bPopup({
                            position: ['auto', 'auto'],
                            zIndex: 9999999,
                            //contentContainer:'.modal-content',
                            loadUrl: modal_object.featured_image_url,
                            loadCallback: function(){
                                $('.loading-ajax').show();
                                getContentFromSirv();
                            }
                        });
                    });

                $('body').on('click', '.sirv-woo-add-media', function () {
                        //window.isAddMedia = true;
                        window.sirvProductID = $(this).attr('data-id');
                        let $modalBlock = $('.sirv-modal').length > 1 ? $($('.sirv-modal')[0]) : $('.sirv-modal');
                        window['bPopup'] = $modalBlock.bPopup({
                            position: ['auto', 'auto'],
                            zIndex: 9999999,
                            //contentContainer:'.modal-content',
                            loadUrl: modal_object.woo_media_add_url,
                            loadCallback: function(){
                                $('.loading-ajax').show();
                                getContentFromSirv();
                            }
                        });
                    });

                $('body').on('click', '.sirv-woo-add-product-image', function () {
                        //window.isAddMedia = true;
                        window.sirvProductID = $(this).attr('data-id');
                        let $modalBlock = $('.sirv-modal').length > 1 ? $($('.sirv-modal')[0]) : $('.sirv-modal');
                        window['bPopup'] = $modalBlock.bPopup({
                            position: ['auto', 'auto'],
                            zIndex: 9999999,
                            //contentContainer:'.modal-content',
                            loadUrl: modal_object.woo_set_product_image_url,
                            loadCallback: function(){
                                $('.loading-ajax').show();
                                getContentFromSirv();
                            }
                        });
                    });
            }else{
                $('body').on('click', '.sirv-modal-click, .sirv-add-image-modal-click, .sirv-woo-add-media', function () {
                    window['bPopup'] = $('.sirv-modal').bPopup({
                        position: ['auto', 'auto'],
                        zIndex: 9999999,
                        //contentContainer:'.modal-content',
                        loadUrl: modal_object.login_error_url,
                    });
                });
            }
        }


        window['renderSirvModalWindowWithParams'] = function(id=null, isEditGallery=false,isShortcodesVisible=false, isAddMoreImages=false,  onCloseFunc){
            if(!modal_object){
                console.log('Modal data did not find. Please try reload page.');
                return false;
            }

            if(modal_object.isNotEmptySirvOptions == '1'){
                if(isShortcodesVisible) window.isAddMedia = true;
                window.bPopup = $('.sirv-modal').bPopup({
                    position: ['auto', 'auto'],
                    zIndex: 9999999,
                    loadUrl: modal_object.media_add_url,
                    loadCallback: function(){
                        if(isEditGallery){
                            $('.insert').addClass('edit-gallery');
                            $('.insert').attr('data-shortcode-id', id);
                            sirvEditGallery(id);
                        }else{
                            if(isAddMoreImages){
                                $('.sirv-non-selected-overlay').show();

                            }

                            $('.loading-ajax').show();
                            getContentFromSirv(window.sirvGetPath());
                        }
                    },
                    onClose: function(){
                        onCloseFunc();
                    }
                });
            }else{
                window.bPopup = $('.sirv-modal').bPopup({
                    position: ['auto', 'auto'],
                    zIndex: 9999999,
                    loadUrl: modal_object.login_error_url,
                });
            }
        }

        //-------------------------------------------initialization-----------------------------------------------------------------
        renderSirvModalWindow();
    });
});
