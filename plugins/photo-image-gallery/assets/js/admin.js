function uxGalleryIsLodash() {
    let isLodash = false;

    // If _ is defined and the function _.forEach exists then we know underscore OR lodash are in place
    if ( 'undefined' != typeof( _ ) && 'function' == typeof( _.forEach ) ) {

        // A small sample of some of the functions that exist in lodash but not underscore
        const funcs = [ 'get', 'set', 'at', 'cloneDeep' ];

        // Simplest if assume exists to start
        isLodash  = true;

        funcs.forEach( function ( func ) {
            // If just one of the functions do not exist, then not lodash
            isLodash = ( 'function' != typeof( _[ func ] ) ) ? false : isLodash;
        } );
    }

    if ( isLodash ) {
        // We know that lodash is loaded in the _ variable
        return true;
    } else {
        // We know that lodash is NOT loaded
        return false;
    }
}


var name_changeRight = function (e) {
    document.getElementById("name").value = e.value;
}
var name_changeTop = function (e) {
    document.getElementById("uxgallery_name").value = e.value;
};

var album_name_changeTop = function (e) {
    document.getElementById("uxgallery_album_name").value = e.value;
};

jQuery(document).ready(function () {

    var uxGalleryLodashCounter = 0
    var uxGalleryLodashInterval = setInterval(function(){
        if ( uxGalleryIsLodash() ) {
            _.noConflict();
            clearInterval(uxGalleryLodashInterval);
        } else if(uxGalleryLodashCounter > 20) {
            clearInterval(uxGalleryLodashInterval);
        }
        uxGalleryLodashCounter++;
    }, 1000);


    jQuery("#gallery-images-list li .edit-image").on("click", function(){
       // alert(jQuery(this).parents("li").attr("id"));
        openModal(jQuery(this).parents("li").attr("id").replace("order_",""));
    });


    /*MODAL NAVIGATION*/
    jQuery("#modal_images_list_wrapper").on("click", ".dashicons", function(){

        if (jQuery(this).attr('data-action') == "left-change") {
            galleftChange();
        }
        if (jQuery(this).attr('data-action') == "right-change") {
            galrightChange();
        }
        if (jQuery(this).attr('data-action') == "close") {
            galclosePopup();
        }

        return false;
    });

    function openModal(elem) {
        jQuery( "body" ).append("<div class='media-modal-backdrop'></div>");
        jQuery("#modal_images_list_wrapper").addClass("open");
        jQuery("#modal_images_list_wrapper ul > li").removeClass("active");
        var elem = jQuery("#"+elem).addClass("active");
        return false;
    }
    function galclosePopup() {
        jQuery(".media-modal-backdrop").remove();
        jQuery("#modal_images_list_wrapper").removeClass("open");
        jQuery("#modal_images_list_wrapper ul > li").removeClass("active");
        return false;
    };

    function galleftChange() {
        var el =jQuery("#modal_images_list_wrapper .modal_images_list li.active");

        el.removeClass('active');
        if (el.index()==0) {
            el.parent().children(":last-child").addClass("active");
        }else{
            el.prev().addClass("active");

        }

        return false;
    };jQuery(".uxgallery_options_contents input, .uxgallery_options_contents select, .uxgallery_options_contents checkbox").attr("name","");

    function galrightChange() {

        var el =jQuery("#modal_images_list_wrapper .modal_images_list li.active");
        el.removeClass('active');

        if (el.index()==(el.parent().children().length-1)) {
            el.parent().children(":first-child").addClass("active");
        }else{
            el.next().addClass("active");
        }
        return false;

    };




    /*SEE DEMO*/
    jQuery("#ux_sl_effects").on('change', function(){
        jQuery(this).parent().find('.view_template_demo').attr('href',jQuery(this).find("option:selected").attr('data-demo'));
    });


    /*GALLERY LIST IMAGE SLIDER*/
    jQuery('.ttv_slider').each(function(){
        jQuery(this).children('a:not(:last-child)').hide();
    });
    setInterval(function () {
        jQuery('.ttv_slider').each(function(){
            if(jQuery(this).find('a').length>1) {
                jQuery(this).children(':last-child').fadeOut(1500).prev().fadeIn(1500).end().prependTo(jQuery(this));
            }
        });
    }, 4000);



    /*COPY SHORTCODE ON CLIPBOARD*/
    jQuery('.shortcode_copy_block a').on('click', function(){
        var strElem= jQuery(this).parent().find('input').select();
        document.execCommand("copy");
        jQuery(".elemcop").hide();
        jQuery(this).parent().find(".elemcop").show();
        return false;
    });


    var custom_uploader;
    jQuery('#watermark_image_btn_new').click(function (e) {
        e.preventDefault();
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose file',
            button: {
                text: 'Choose file'
            },
            multiple: false
        });
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function () {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            jQuery("#watermark_image_new").attr("src", attachment.url);
            jQuery('#img_watermark_hidden_new').attr('value', attachment.url);
        });
        custom_uploader.open();
    });

    jQuery('#lightbox_type input').change(function () {
        jQuery('#lightbox_type input').parent().parent().removeClass('active');
        jQuery(this).parent().parent().addClass('active');
        if (jQuery(this).val() == 'old_type') {
            jQuery('#lightbox-options-list').addClass('active');
            jQuery('#new-lightbox-options-list').removeClass('active');
        }
        else {
            jQuery('#lightbox-options-list').removeClass('active');
            jQuery('#new-lightbox-options-list').addClass('active');
        }
        jQuery('#lightbox_type input').prop('checked', false);
        if (!jQuery(this).prop('checked')) {
            jQuery(this).prop('checked', true);
        }
    });


    if (jQuery('#rating').val() == 'off') {
        jQuery('#modal_images_list_wrapper .like_dislike_wrapper').css('display', 'none');
        jQuery('#modal_images_list_wrapper .heart_wrapper').css('display', 'none');
        jQuery('#modal_images_list_wrapper .ratings_off').css('display', 'block');
    } else if (jQuery('#rating').val() == 'dislike') {
        jQuery('#modal_images_list_wrapper .like_dislike_wrapper').css('display', 'block');
        jQuery('#modal_images_list_wrapper .heart_wrapper').css('display', 'none');
        jQuery('#modal_images_list_wrapper .heart_wrapper').find('input').removeAttr('name');
        jQuery('#modal_images_list_wrapper .ratings_off').css('display', 'none');
    } else if (jQuery('#rating').val() == 'heart') {
        jQuery('#modal_images_list_wrapper .heart_wrapper').css('display', 'block');
        jQuery('#modal_images_list_wrapper .like_dislike_wrapper').css('display', 'none');
        jQuery('#modal_images_list_wrapper .ratings_off').css('display', 'none');
    }

    jQuery('#rating').on('change', function () {
        if (jQuery(this).val() == 'off') {
            jQuery('#modal_images_list_wrapper .like_dislike_wrapper').css('display', 'none');
            jQuery('#modal_images_list_wrapper .heart_wrapper').css('display', 'none');
            jQuery('#modal_images_list_wrapper .ratings_off').css('display', 'block');
        } else if (jQuery(this).val() == 'dislike') {
            jQuery('#modal_images_list_wrapper .like_dislike_wrapper').css('display', 'block');
            jQuery('#modal_images_list_wrapper .heart_wrapper').css('display', 'none');
            jQuery('#modal_images_list_wrapper .ratings_off').css('display', 'none');
            jQuery('#modal_images_list_wrapper .heart_wrapper').find('input').removeAttr('name');
        } else if (jQuery(this).val() == 'heart') {
            jQuery('#modal_images_list_wrapper .heart_wrapper').css('display', 'block');
            jQuery('#modal_images_list_wrapper .like_dislike_wrapper').css('display', 'none');
            jQuery('#modal_images_list_wrapper .like_dislike_wrapper').css('display', 'none');
            jQuery('#modal_images_list_wrapper .heart_wrapper').each(function () {
                var num = jQuery(this).find('input').attr('num');
                jQuery(this).find('input').attr('name', 'like_' + num);
            });
        }
    });
    var setTimeoutConst;
    jQuery('#add_new_video').on('click', function () {
        var galleryId = jQuery(this).attr('data-gallery-id');
        var addVideoNonce = jQuery(this).attr('data-gallery-add-video-nonce');
        jQuery('#uxgallery_add_videos_wrap').attr('data-gallery-id', galleryId);
        jQuery('#uxgallery_add_videos_wrap').attr('data-gallery-add-video-nonce', addVideoNonce);
    });
    jQuery('ul#gallery-images-list > li > .image-container img').on('mouseenter', function () {
        var onHoverPreview = jQuery('#img_hover_preview').prop('checked');
        if (onHoverPreview == true) {
            var imgSrc = jQuery(this).attr('src');
            jQuery('#gallery-image-zoom img').attr('src', imgSrc);
            setTimeoutConst = setTimeout(function () {
                jQuery('#gallery-image-zoom').fadeIn('3000');
            }, 500);
        }
    });

    galleryPopupSizes(jQuery('#light_box_size_fix'));
    jQuery('#light_box_size_fix').change(function () {
        galleryPopupSizes(jQuery(this));
    });
    jQuery('input[data-slider="true"]').bind("slider:changed", function (event, data) {
        jQuery(this).parent().find('span').html(parseInt(data.value) + "%");
        jQuery(this).val(parseInt(data.value));
    });
    jQuery('.ux-insert-video-button').click(function (e) {
        e.preventDefault();
        var ID1 = jQuery('#ux_add_video_input').val();
        if (ID1 == "") {
            alert("Please copy and past url form Youtube or Vimeo to insert into slider.");
            return false;
        }
        var galleryId = jQuery(this).parents('#uxgallery_add_videos_wrap').attr('data-gallery-id');
        var addVideoNonce = jQuery(this).parents('#uxgallery_add_videos_wrap').attr('data-gallery-add-video-nonce');
        var action = "admin.php?page=galleries_uxgallery&task=gallery_video&id=" + galleryId + "&closepop=1" + "&gallery_nonce_add_video=" + addVideoNonce;
        jQuery(this).parent('form').attr('action', action).submit();
    });

    jQuery('#ux_add_video_input').change(function () {
        if (jQuery(this).val().indexOf("youtube") >= 0) {
            jQuery('#add-video-popup-options > div').removeClass('active');
            jQuery('#add-video-popup-options  .youtube').addClass('active');
        } else if (jQuery(this).val().indexOf("vimeo") >= 0) {
            jQuery('#add-video-popup-options > div').removeClass('active');
            jQuery('#add-video-popup-options  .vimeo').addClass('active');
        } else {
            jQuery('#add-video-popup-options > div').removeClass('active');
            jQuery('#add-video-popup-options  .error-message').addClass('active');
        }
    });
    jQuery('.ux-editnewuploader .editimageicon').click(function (e) {
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = jQuery(this);
        var id = button.attr('id').replace('_button', '');
        _custom_media = true;
        wp.media.editor.send.attachment = function (props, attachment) {
            if (_custom_media) {
                jQuery("#" + id).val(attachment.url);
                jQuery("#save-buttom").click();
            } else {
                return _orig_send_attachment.apply(this, [props, attachment]);
            }
            ;
        };

        wp.media.editor.open(button);
        return false;
    });
    jQuery(".ux-editnewuploader").click();


    jQuery('#modal_images_list_wrapper a.remove-image, #gallery-images-list li .remove-image-container a.remove-image').on('click', function () {
        var galleryId = jQuery(this).data('gallery-id');
        var imageId = jQuery(this).data('image-id');
        var removeNonce = jQuery(this).data('nonce-value');
        jQuery('#adminForm').attr('action', 'admin.php?page=galleries_uxgallery&task=edit_cat&id=' + galleryId + '&removeslide=' + imageId + '&save_data_nonce=' + removeNonce);
        galleryImgSubmitButton('apply');
    });


    jQuery(".wp-media-buttons-icon").click(function () {
        jQuery(".attachment-filters").css("display", "none");
    });
    var _custom_media = true,
        _orig_send_attachment = wp.media.editor.send.attachment;

    jQuery('.ux-newuploader .button, .ux-newuploader a').click(function (e) {

        e.preventDefault();
        var button = jQuery(this);
        var id = button.attr('id').replace('_button', '');
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Insert Into Gallery',
            button: {
                text: 'Insert Into Gallery'
            },
            multiple: true
        });
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function () {
            attachments = custom_uploader.state().get('selection').toJSON();
            for (var key in attachments) {
                jQuery("#" + id).val(attachments[key].url + ';;;' + jQuery("#" + id).val());
            }
            jQuery("#save-buttom").click();
        });
        custom_uploader.open();
        return false;
    });

    jQuery('.add_media').on('click', function () {
        _custom_media = false;

    });
    jQuery(".wp-media-buttons-icon").click(function () {
        jQuery(".media-menu .media-menu-item").css("display", "none");
        jQuery(".media-menu-item:first").css("display", "block");
        jQuery(".separator").next().css("display", "none");
        jQuery('.attachment-filters').val('image').trigger('change');
        jQuery(".attachment-filters").css("display", "none");
    });


    jQuery('#gallery-unique-options').on('change', function () {
        jQuery('div[id^="gallery-current-options"]').each(function () {
            if (jQuery('#gallery-current-options-1').hasClass('active') || jQuery('#gallery-current-options-3').hasClass('active'))
                jQuery('li.for_slider').show();
            else
                jQuery('li.for_slider').hide();
        });
    });
    jQuery('#gallery-unique-options').change();

   /* jQuery('#gallery-unique-options').on('change', function () {
        jQuery('div[id^="gallery-current-options"]').each(function () {
            if (!jQuery(this).hasClass("active")) {
                jQuery(this).find('ul li input[name="sl_pausetime"]').attr('name', '');
            }
        });
    });*/

    /*jQuery('#gallery-unique-options').on('change', function () {
        jQuery('div[id^="gallery-current-options"]').each(function () {
            if (!jQuery(this).hasClass("active")) {
                jQuery(this).find('ul li input[name="sl_changespeed"]').attr('name', '');
            }
        });
    });*/

    jQuery(".modal_images_list >  li input").on('keyup', function () {
        jQuery(this).parents(".modal_images_list> li").addClass('submit-post');
    });
    jQuery(".modal_images_list > li textarea").on('keyup', function () {
        jQuery(this).parents(".modal_images_list > li").addClass('submit-post');
    });
    jQuery(".modal_images_list > li input").on('change', function () {
        jQuery(this).parents(".modal_images_list > li").addClass('submit-post');
    });
    jQuery('.editimageicon').click(function () {
        jQuery(this).parents(".modal_images_list > li").addClass('submit-post');
    })
    /*** </posted only submit classes> ***/

    jQuery(".images_list_sortable").sortable({
        start: function (event, ui) {
            ui.item.data('start_pos', ui.item.index());
        },
        stop: function (event, ui) {

            jQuery(".images_list_sortable > li").each(function () {
                jQuery(this).find('.order_by').val(jQuery(this).index());
            });
            var start = Math.min(ui.item.data('start_pos'), ui.item.index());
            var end = Math.max(ui.item.data('start_pos'), ui.item.index());
            for (var i = start; i <= end; i++) {
                jQuery(document.querySelectorAll(".images_list_sortable > li")[i]).addClass('highlights');
            }

        },
        change: function (event, ui) {
            var start_pos = ui.item.data('start_pos');
            var index = ui.placeholder.index();
            if (start_pos < index + 2) {
                jQuery('.images_list_sortable > li:nth-child(' + index + ')').addClass('highl' +
                    'ights');
            } else {
                jQuery('.images_list_sortable > li:eq(' + (index + 1) + ')').addClass('highlights');
            }

        },
        update: function (event, ui) {
            jQuery('#sortable li').removeClass('highlights');
        },
        revert: true
    });

    var strliID = jQuery(location).attr('hash');
    jQuery('#gallery-view-tabs li').removeClass('active');
    if (jQuery('#gallery-view-tabs li a[href="' + strliID + '"]').length > 0) {
        jQuery('#gallery-view-tabs li a[href="' + strliID + '"]').parent().addClass('active');
    } else {
        jQuery('a[href="#gallery-view-options-7"]').parent().addClass('active');
    }
    strliID = strliID.split('#').join('.');
    jQuery('#gallery-view-tabs-contents > li').removeClass('active');
    if (jQuery(strliID).length > 0) {
        jQuery(strliID).addClass('active');
    } else {
        jQuery('.gallery-view-options-7').addClass('active');
    }
    jQuery('input[data-slider="true"]').bind("slider:changed", function (event, data) {
        jQuery(this).parent().find('span').html(parseInt(data.value) + "%");
        jQuery(this).val(parseInt(data.value));
    });

    jQuery('#arrows-type input[name="params[uxgallery_slider_navigation_type]"]').change(function () {
        jQuery(this).parents('ul').find('li.active').removeClass('active');
        jQuery(this).parents('li').addClass('active');
    });
    jQuery('input[data-gallery="true"]').bind("gallery:changed", function (event, data) {
        jQuery(this).parent().find('span').html(parseInt(data.value) + "%");
        jQuery(this).val(parseInt(data.value));
    });



    jQuery('#gallery-view-tabs li a').click(function () {
        jQuery('#gallery-view-tabs > li').removeClass('active');
        jQuery(this).parent().addClass('active');
        jQuery('#gallery-view-tabs-contents > li').removeClass('active');
        var liID = jQuery(this).attr('href').split('#').join('.');
        jQuery(liID).addClass('active');
        liID = liID.replace('.', '');
        var action = jQuery('#adminForm').attr('action');
        jQuery('#adminForm').attr('action', action + "#" + liID);
    });




    jQuery('#ux_sl_effects').change(function () {

       // jQuery('.gallery-current-options').removeClass('active');
       // jQuery('#gallery-current-options-' + jQuery(this).val()).addClass('active');

        /*elastic has no raitings*/

        jQuery("#rating").removeAttr("disabled");
        if (jQuery(this).val() == 10) {
            jQuery("#rating").attr("disabled","disabled")
        }

        /*sliders*/
         if (jQuery(this).val() == 1) {
            jQuery('.slider_options').addClass('hidden');
            jQuery('.content_slider_options').removeClass('hidden');
        }
        else if (jQuery(this).val() == 3) {
            jQuery('.content_slider_options').addClass('hidden');
            jQuery('.slider_options').removeClass('hidden');
        }
        else{
            jQuery('.slider_options').addClass('hidden');
            jQuery('.content_slider_options').addClass('hidden');
        }


        /*double hide load more options*/
        if (jQuery(this).val() == 0 || jQuery(this).val() == 5 || jQuery(this).val() == 4 || jQuery(this).val() == 5 || jQuery(this).val() == 6 || jQuery(this).val() == 7 || jQuery(this).val() == 10 ) {
            jQuery('.pagination_options').removeClass('hidden');

            /*onload*/
            if (jQuery('select[name="display_type"]').val() == 2) {
                jQuery('#content_per_page_li').addClass('hidden');
            } else {
                jQuery('#content_per_page_li').removeClass('hidden');
            }

            jQuery('select[name="display_type"]').on('change', function () {
                if (jQuery(this).val() == 2) {
                    jQuery('#content_per_page_li').addClass('hidden');
                } else {
                    jQuery('#content_per_page_li').removeClass('hidden');
                }
            });
        }
        else {
            jQuery('.pagination_options').addClass('hidden');
        }




        /*

        if (jQuery(this).val() == 10) {
            jQuery('#rating').parent().addClass('hidden');
            jQuery("#display_type option[value=1]").hide();
            if (jQuery("#display_type").val() == 1) {
                jQuery("#display_type").val(0);
            }
        }
        else {
            jQuery('#rating').parent().removeClass('hidden');
            jQuery("#display_type option[value=1]").show();
        }

        if (jQuery(this).val() == 1 || jQuery(this).val() == 3) {
            jQuery('.gallery-current-options-0').hide();
        }
        else {
            jQuery('.gallery-current-options-0').show();
        }


        if (jQuery(this).val() == 10 || jQuery(this).val() == 3) {
            jQuery('#rating_inp').hide();
        }
        else {
            jQuery('#rating_inp').show();
        }*/

    });


    jQuery('#ux_sl_effects').change();
    jQuery('a[href*="remove_gallery"]').click(function () {
        if (!confirm('Are you sure you want to delete this item?'))
            return false;
    });


    hover_options("#uxgallery_album_popup_onhover_effects", [".for_popup_dark_hover", ".for_popup_blur_hover", ".for_popup_scale_hover", ".for_popup_bottom_hover", ".for_popup_elastic_hover"]);
    hover_options("#uxgallery_album_lightbox_onhover_effects", [".for_light_dark_hover", ".for_light_blur_hover", ".for_light_scale_hover", ".for_light_bottom_hover", ".for_light_elastic_hover"]);
    hover_options("#uxgallery_album_thumbnail_onhover_effects", [".for_thumbnail_dark_hover", ".for_thumbnail_blur_hover", ".for_thumbnail_scale_hover", ".for_thumbnail_bottom_hover", ".for_thumbnail_elastic_hover"]);
    // hover_options("#album_mosaic_onhover_effects", [".for_mosaic_dark_hover", ".for_mosaic_blur_hover", ".for_mosaic_scale_hover", ".for_mosaic_bottom_hover", ".for_mosaic_elastic_hover"]);
    // hover_options("#album_masonry_onhover_effects", [".for_masonry_dark_hover", ".for_masonry_blur_hover", ".for_masonry_scale_hover", ".for_masonry_bottom_hover", ".for_masonry_elastic_hover"]);

    function hover_options(effect, option) {
        var current = jQuery(effect).val();
        jQuery(option).each(function (key, val) {
            jQuery(val).hide();
        });
        jQuery(option[current]).show()

        jQuery(effect).on("change", function () {
            jQuery(option).each(function (key, val) {
                jQuery(val).hide();
            });
            var selected = jQuery(this).val();
            jQuery(option[selected]).fadeIn()
        })
    }


});
jQuery(window).resize(function () {
    galleryImgResizeAdminImages();
});
jQuery(window).load(function () {
    galleryImgResizeAdminImages();
});




function galleryImgFilterInputs() {

    var mainInputs = "";
    jQuery(".images_list_sortable > li.highlights").each(function () {

        jQuery(this).next().addClass('submit-post');
        jQuery(this).prev().addClass('submit-post');
        jQuery(this).addClass('submit-post');
        jQuery(this).removeClass('highlights');

        var inputs = jQuery(this).attr("id");
        var n = inputs.lastIndexOf('_');
        var res = inputs.substring(n + 1, inputs.length);
        res += ',';
        mainInputs += res;
    });

    if (jQuery("#modal_images_list_wrapper .modal_images_list > li.submit-post").length) {

        jQuery("#modal_images_list_wrapper .modal_images_list > li.submit-post").each(function () {
            var inputs = jQuery(this).attr("id");
            inputs="order_"+inputs;
            jQuery("#"+inputs).addClass('submit-post');


            var n = inputs.lastIndexOf('_');
            var res = inputs.substring(n + 1, inputs.length);
            res += ',';
            mainInputs += res;
        });

    }

    mainInputs = mainInputs.substring(0, mainInputs.length - 1);
    jQuery(".changedvalues").val(mainInputs);

    jQuery(".images_list_sortable > li").not('.submit-post').not(".album_gall").each(function () {
        jQuery(this).find('input').removeAttr('name');
        jQuery(this).find('textarea').removeAttr('name');
    });


    return mainInputs;

   /* jQuery(".images_list_sortable > li").each(function () {
        jQuery(this).find('input').removeAttr('name');
        jQuery(this).find('textarea').removeAttr('name');
        jQuery(this).find('select').removeAttr('name');
    });
    jQuery("#modal_images_list_wrapper .modal_images_list > li").each(function () {
        jQuery(this).find('input').removeAttr('name');
        jQuery(this).find('textarea').removeAttr('name');
    });*/
}

function galleryImgSubmitButton(pressbutton) {
    if (!document.getElementById('name').value) {
        alert("Name is required.");
        return;
    }

    if (!((jQuery('#ux_sl_effects').val() == 1) || (jQuery('#ux_sl_effects').val() == 3))) if (jQuery('#content_per_page').val() < 1) {
        alert("Images Per Page must be greater than 0.");
        return;
    }


    galleryImgFilterInputs();
    document.getElementById("adminForm").action = document.getElementById("adminForm").action + "&task=" + pressbutton;
    document.getElementById("adminForm").submit();
}

// special for albums
function albumImgSubmitButton(pressbutton) {
    if (!document.getElementById('name').value) {
        alert("Name is required.");
        return;
    }

    galleryImgFilterInputs();
    document.getElementById("adminForm").action = document.getElementById("adminForm").action + "&task=" + pressbutton;
    document.getElementById("adminForm").submit();
}

function galleryImgListItemTask(this_id, replace_id) {
    document.getElementById('oreder_move').value = this_id + "," + replace_id;
    document.getElementById('admin_form').submit();
}

function galleryImgDoNothing() {
    var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
    if (keyCode == 13) {
        if (!e) var e = window.event;
        e.cancelBubble = true;
        e.returnValue = false;
        if (e.stopPropagation) {
            e.stopPropagation();
            e.preventDefault();
        }
    }
}

function galleryPopupSizes(checkbox) {
    if (checkbox.is(':checked')) {
        jQuery('.lightbox-options-block .not-fixed-size').css({'display': 'none'});
        jQuery('.lightbox-options-block .fixed-size').css({'display': 'block'});
    } else {
        jQuery('.lightbox-options-block .fixed-size').css({'display': 'none'});
        jQuery('.lightbox-options-block .not-fixed-size').css({'display': 'block'});
    }
}

function galleryImgResizeAdminImages() {
    jQuery('ul#gallery-images-list > li > .image-container .list-img-wrapper img').each(function () {
        var imhHeight = jQuery(this).prop('naturalHeight');
        var imhWidth = jQuery(this).prop('naturalWidth');
        var parentWidth = jQuery(this).parent().width();
        var parentHeight = jQuery(this).parent().height();
        var imgRatio = imhWidth / imhHeight;
        var parentRatio = parentWidth / parentHeight;
        if (imgRatio <= parentRatio) {
            jQuery(this).css({
                position: "relative",
                width: '100%',
                top: '50%',
                transform: 'translateY(-50%)',
                height: 'auto',
                left: '0'
            });
        } else {
            jQuery(this).css({
                position: "relative",
                height: '100%',
                left: '50%',
                transform: 'translateX(-50%)',
                width: 'auto',
                top: '0'
            });
        }
    });
}

