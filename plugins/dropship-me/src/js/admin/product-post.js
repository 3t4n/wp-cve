jQuery(function ($) {

    function showProgress() {
        $.each($('.fade-cover'), function (index, value) {
            if ($(value).attr('id') !== 'review_animate_loader') {
                $(value).remove();
            }
        });
        ADS.coverShow();
    }

    var ProductPost = {

        saveSupplierInfo : function() {

            var data = {
                ID          : $('#post_ID').val(),
                productUrl  : $('#_productUrl').val(),
                storeUrl    : $('#_storeUrl').val(),
                storeName   : $('#_storeName').val()
            };

            $.ajaxQueue( {
                url     : ajaxurl,
                data    : {
                    action : 'dm_save_adswsupplier',
                    data   : data
                },
                type    : "POST",
                success : function ( response ) {
                    ADS.notify(response);
                    ADS.coverHide();
                }
            } );
        },

        init : function(){

            var $this = this;

            $('.save_adswsupplier').on('click', function(){

                showProgress();

                $this.saveSupplierInfo();
            });
        }
    };

    ProductPost.init();
});