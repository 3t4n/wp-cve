/* 
 *  Slider's Slides Uploading Via Media Frame
 *  v1.0.0
 */

(function ($) {
    'use strict';

    $(document).ready(function () {

        // SOC Slider File Uploads
        var soc_slider_frame;
        var $slides_id = $('#soc_slider_slides');
        var $soc_slides_container = $('#soc-slider-slide-container');
        var $soc_slides = $soc_slides_container.find('ul.soc-slides');

        $('.add_slide').on('click', 'a', function (event) {
            var $el = $(this);

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if (soc_slider_frame) {
                soc_slider_frame.open();
                return;
            }

            // Create Media Frames
            soc_slider_frame = wp.media.frames.soc_slider_frame = wp.media({
                
                // Set the title of the modal.
                title: $el.data('choose'),
                button: {
                    text: $el.data('update')
                },
                states: [
                    new wp.media.controller.Library({
                        title: $el.data('choose'),
                        filterable: 'all',
                        multiple: true
                    })
                ]
            });

            // When a slide is selected, run a callback.
            soc_slider_frame.on('select', function () {
                var selection = soc_slider_frame.state().get('selection');
                var attachment_ids = $slides_id.val();
                
                selection.map(function (attachment) {
                    attachment = attachment.toJSON();

                    if (attachment.id) {
                        attachment_ids = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
                        var attachment_slide = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                        $soc_slides.append('<li class="slide" data-attachment_id="' + attachment.id + '"><img src="' + attachment_slide + '" /><input type="text" name="caption_' + attachment.id + '"  value="'+attachment.caption+'" placeholder="Caption"><a href="#" class="delete" title="' + $el.data('delete') + '"><i class="fa fa-times" aria-hidden="true"></i> </a>  </li>');
                    }
                });

                $slides_id.val(attachment_ids);
            });

            // Finally, open the modal.
            soc_slider_frame.open();
        });
        
        // Image Ordering
        $soc_slides.sortable({
            items: 'li.slide',
            cursor: 'move',
            scrollSensitivity: 40,
            forcePlaceholderSize: true,
            forceHelperSize: false,
            helper: 'clone',
            opacity: 0.65,
            placeholder: 'soc-metabox-sortable-placeholder',
            start: function (event, ui) {
                ui.item.css('background-color', '#f6f6f6');
            },
            stop: function (event, ui) {
                ui.item.removeAttr('style');
            },
            update: function () {
                var attachment_ids = '';

                $soc_slides_container.find( 'li.slide' ).css('cursor', 'default').each(function () {
                    var attachment_id = jQuery(this).attr('data-attachment_id');
                    attachment_ids = attachment_ids + attachment_id + ',';
                });

                $slides_id.val(attachment_ids);
            }
        });

        // Remove Slides
        $soc_slides_container.on('click', 'a.delete', function () {
            $(this).closest('li.slide').remove();

            var attachment_ids = '';

            $soc_slides_container.find( 'li.slide' ).css('cursor', 'default').each(function () {
                var attachment_id = jQuery(this).attr('data-attachment_id');
                attachment_ids = attachment_ids + attachment_id + ',';
            });

            $slides_id.val(attachment_ids);

            return false;
        });
    });
})(jQuery);