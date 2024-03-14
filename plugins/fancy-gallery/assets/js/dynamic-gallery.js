(function ($) {

    function addImage($dynamic_images, $image_template, image) {
        let
            $new_image = $image_template.clone(),
            $img = $new_image.find('img.image'),
            $attachment_id = $new_image.find('.image-attachment-id'),
            thumbnail = image.sizes.thumbnail || image.sizes.medium || image.sizes.full;

        $new_image
            .attr('data-image-id', image.id)
            .data('image', image);

        $img.attr({
            'src': thumbnail.url,
            'width': thumbnail.width,
            'height': thumbnail.height
        });
        $attachment_id.val(image.id);
        $new_image.appendTo($dynamic_images).show();

        reindexImages($dynamic_images);

        $dynamic_images.sortable('refresh');
    }

    function removeImage($image) {
    }

    function reindexImages($dynamic_images) {
        $dynamic_images.find('.image-template').each(function (index, image) {
            let
                $image = $(image),
                $index = $image.find('.image-index');

            $index.text(++index);
        });
    }

    function isImageAdded($dynamic_images, image) {
        let
            selector = '.image-template[data-image-id=' + image.id + ']',
            found = $dynamic_images.find(selector).length;
        
        return found;
    }

    function showSelectImageDialog($dynamic_images, $image_template) {
        // Create a new media dialog and open it
        let wp_media_dialog = new wp.media({
            title: wp.media.view.l10n.addToGalleryTitle,
            library: { type: 'image' },
            button: { text: wp.media.view.l10n.addToGallery },
            multiple: true
        }).open();

        // When a file was selected, grab the choosen attachments
        wp_media_dialog.on('select', function () {
            let arr_attachment = wp_media_dialog.state().get('selection').toJSON();
            for (index in arr_attachment) {
                let attachment = arr_attachment[index];
                if (!isImageAdded($dynamic_images, attachment)) {
                    addImage($dynamic_images, $image_template, attachment);
                }
            }
        });
    }

    function showEditImageDialog($dynamic_images, $image_template, image) {
        let
            $image = $(image).parents('.image-template:first').first(),
            attachment_id = $image.find('input.image-attachment-id:first').val(),
            gallery_id = $('input#post_ID').val(),
            attachment = new wp.media.model.Attachment.get(attachment_id),
            frame = wp.media({
                title: wp.media.view.l10n.editGalleryTitle,
                button: { text: wp.media.view.l10n.apply },
                library: { type: 'image', uploadedTo: gallery_id, orderby: 'menuOrder', order: 'ASC' },
                router: false
            }).open();

        frame.$el
            .find('.media-frame-router, .media-toolbar').hide().end()
            .find('.media-frame-content').css({ 'top': '50px', 'bottom': 0 }).end()
            .find('.attachments-browser .attachments').css('top', '5px').end()
            .find('.ui-sortable').sortable('destroy');

        // Preselect the attachment
        frame.state().get('selection').add(attachment);

        /*
        frame.on('close', function(){
          let
            library = this.get('library'),
            images = library.get('library');
  
          $dynamic_images.empty();
  
          images.forEach(function(image, index){
            addImage($dynamic_images, $image_template, image.attributes);
          });
        });
        */
    }

    $('.dynamic-gallery').each(function (index, wrapper) {
        let
            $wrapper = $(wrapper),
            $image_template = $wrapper.find('.image-template:first').hide(),
            $add_button = $wrapper.find('.add-image'),
            $dynamic_images = $wrapper.find('.dynamic-images'),
            pre_defined_images = [];

        $dynamic_images.sortable({
            update: function (event, ui) {
                reindexImages($dynamic_images);
            }
        });

        $add_button.on('click', function (event) {
            showSelectImageDialog($dynamic_images, $image_template);
        });

        $wrapper.on('click', 'button.delete-image', function (event) {
            if (confirm(DynamicGallery.warn_remove_image)) {
                let $image = $(this).parents('.image-template:first').first();
                $image.fadeOut(500, function () {
                    $(this).remove();
                    reindexImages($dynamic_images);
                    $dynamic_images.sortable('refresh');
                });
            }
        });

        $wrapper.on('click', 'img.image', function (event) {
            showEditImageDialog($dynamic_images, $image_template, this);
        });

        // Load predefined images
        $wrapper.find('param').each(function () {
            pre_defined_images.push($(this).data('image-attachment-id'));
        });

        if (pre_defined_images.length) {
            let query_args = {
                post__in: pre_defined_images,
                orderby: 'post__in',
                posts_per_page: -1,
                cache_results: false
            };

            wp.media.query(query_args).more().done(function () {
                this.forEach(function (image) {
                    addImage($dynamic_images, $image_template, image.attributes);
                });
            });
        }

    });

}(jQuery));
