jQuery(function ($) {

    var featured_index = $(document).find(ywcfav_zoom_params.img_class_container + '.yith_featured_content').index(),
        featured_gallery_thumbnail = $(ywcfav_zoom_params.thumbnail_gallery_class_element).get(featured_index);


    $(featured_gallery_thumbnail).addClass('yith_featured_thumbnail');
    $(document).on('click', '.yith_magnifier_gallery li', function (e) {

        if (!$(this).hasClass('yith_featured_thumbnail')) {
            $(document).find('.ywcfav_hide').show();
            $(document).find('.yith_featured_content').hide();

        } else {
            $(document).find('.ywcfav_hide').hide();
            $(document).find('.yith_featured_content').show();
        }
    });

    $('.variations_form.cart').on( 'found_variation', function(e,variation){

        $(document).find('.ywcfav_hide').show();
        $(document).find('.yith_featured_content').hide();
        $(document).trigger('ywcfav_found_variation',[ $(document).find('.yith_featured_content'),variation] );

    }).on( 'reset_data', function(e){
        $(document).find('.ywcfav_hide').hide();
        $(document).find('.yith_featured_content').show();
    });

});
