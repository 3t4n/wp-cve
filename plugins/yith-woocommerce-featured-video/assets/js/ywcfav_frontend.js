jQuery(function ($) {

    var featured_index = $(document).find(ywcfav_params.img_class_container + '.yith_featured_content').index(),
        featured_gallery_thumbnail = $(ywcfav_params.thumbnail_gallery_class_element).get(featured_index);

    if( featured_index === 0 ) {
        $(featured_gallery_thumbnail).addClass('yith_featured_thumbnail');
    }

    $('.variations_form.cart').on('show_variation', function (e, variation) {

        setTimeout(function () {
                var featured_content = $('.yith_featured_content');

                if (featured_content.length && !featured_content.find('a.ywfav_zoom_image').length && featured_content.hasClass('flex-active-slide')) {

                    featured_content.find('.ywcfav-video-content').hide();
                    $(featured_gallery_thumbnail).removeClass('yith_featured_thumbnail');
                    init_zoom_on_image(featured_content, variation);

                }
                $(document).trigger('ywcfav_found_variation', [featured_content, variation]);
            },
            101 );
    }).on('reset_data', function (e) {
    var featured_content = $('.yith_featured_content');
    featured_content.find('.ywcfav-video-content').show();
    $(featured_gallery_thumbnail).addClass('yith_featured_thumbnail');

    remove_zoom_on_image(featured_content);
});

var init_zoom_on_image = function (target, variation) {
        var a = $('<a>'),
            img = $('<img>');
        a.attr('href', variation.image.full_src);
        a.addClass('ywfav_zoom_image');


        img.attr('src', variation.image.src);
        img.attr('height', variation.image.src_h);
        img.attr('width', variation.image.src_w);
        img.attr('srcset', variation.image.srcset);
        img.attr('sizes', variation.image.sizes);
        img.attr('title', variation.image.title);
        img.attr('data-caption', variation.image.caption);
        img.attr('alt', variation.image.alt);
        img.attr('data-src', variation.image.full_src);
        img.attr('data-large_image', variation.image.full_src);
        img.attr('data-large_image_width', variation.image.full_src_w);
        img.attr('data-large_image_height', variation.image.full_src_h);
        img.addClass('ywfav_zoom_image');
        a.append(img);
        target.append(a);
        target.trigger('zoom.destroy');

        var zoom_options = $.extend({
            touch: false
        }, ywcfav_params.zoom_options);

        if ('ontouchstart' in document.documentElement) {
            zoom_options.on = 'click';
        }
        target.zoom(zoom_options);

    },
    remove_zoom_on_image = function (target) {
        target.trigger('zoom.destroy');
        target.find('.ywfav_zoom_image').remove();
    };

})
;
