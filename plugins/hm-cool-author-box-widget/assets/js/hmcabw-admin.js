(function($) {

    // USE STRICT
    "use strict";

    var cabColorPicker = [
        '#cab_container_border_color',
        '#cab_container_bg_color',
        '#cab_img_border_color',
        '#cab_post_name_font_color',
        '#cab_post_title_font_color',
        '#cab_post_desc_font_color',
        '#cab_post_email_font_color',
    ];

    $.each(cabColorPicker, function(index, value) {
        $(value).wpColorPicker();
    });

    $('.hmcabw-closebtn').on('click', function() {
        this.parentElement.style.display = 'none';
    });

    $('tr#hmcabw_photograph_th').hide();
    $('form#hmcabw-general-settings-form').on('change', 'input[type=radio][name=hmcabw_author_image_selection]', function() {
        var AuthorImage = $(this).val();
        //alert(AuthorImage);

        if ('upload_image' != AuthorImage) {
            $('tr#hmcabw_photograph_th').hide();
        } else {
            $("tr#hmcabw_photograph_th").show();
        }
    });


    var AuthorImageDefault = $('input[type=radio][name=hmcabw_author_image_selection]');

    if (AuthorImageDefault.is(':checked') === false) {
        AuthorImageDefault.filter('[value=gravatar]').prop("checked", true);
        $('tr#hmcabw_photograph_th').hide();
    } else {
        var AuthorImage2 = $('input[type=radio][name=hmcabw_author_image_selection]:checked', 'form#hmcabw-general-settings-form').val();
        //alert(AuthorImage2);
        if ('upload_image' != AuthorImage2) {
            $('tr#hmcabw_photograph_th').hide();
        } else {
            $('tr#hmcabw_photograph_th').show();
        }
    }

    $('input#hmcabw-media-manager').click(function(e) {

        e.preventDefault();
        var image_frame;
        if (image_frame) {
            image_frame.open();
        }
        // Define image_frame as wp.media object
        image_frame = wp.media({
            title: 'Select Media',
            multiple: false,
            library: {
                type: 'image',
            }
        });

        image_frame.on('close', function() {
            // On close, get selections and save to the hidden input
            // plus other AJAX stuff to refresh the image preview
            var selection = image_frame.state().get('selection');
            var gallery_ids = new Array();
            var my_index = 0;
            selection.each(function(attachment) {
                gallery_ids[my_index] = attachment['id'];
                my_index++;
            });
            var ids = gallery_ids.join(",");
            $('input#hmcabw_photograph').val(ids);
            Refresh_Image(ids);
        });

        image_frame.on('open', function() {
            // On open, get the id from the hidden input
            // and select the appropiate images in the media manager
            var selection = image_frame.state().get('selection');
            var ids = jQuery('input#hmcabw_photograph').val().split(',');
            ids.forEach(function(id) {
                var attachment = wp.media.attachment(id);
                attachment.fetch();
                selection.add(attachment ? [attachment] : []);
            });

        });

        image_frame.open();
    });

    // Ajax request to refresh the image preview
    function Refresh_Image(the_id) {
        var data = {
            action: 'hmcabw_get_image',
            id: the_id
        };

        $.get(ajaxurl, data, function(response) {

            if (response.success === true) {
                //alert(response.data.image);
                $('#hmcabw-preview-image').html(response.data.image);
            }
        });
    }

})(jQuery);