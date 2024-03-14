/*
    Media Upload Button
    --------------------------------------- */

jQuery(document).ready(function ($) {
    function media_upload(button_selector) {
        var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;
        $('body').on('click', button_selector, function () {
            var button_id = $(this).attr('id');
            wp.media.editor.send.attachment = function (props, attachment) {
                if (_custom_media) {
                    $('.' + button_id + '-img').attr('src', attachment.url);
                    $('.' + button_id + '-img').show();
                    $('.' + button_id + '-url').val(attachment.url);
                    $('.remove-' + button_id).show();
                } else {
                    return _orig_send_attachment.apply($('#' + button_id), [props, attachment]);
                }
            };
            wp.media.editor.open($('#' + button_id));
            return false;
        });
    }

    media_upload('.widget-box-upload-media');
});

/*
    Change Text Of Slider
    --------------------------------------- */

(function ($) {
    $(document).ready(function () {
        $('body').on("input", '.widget-box-input-slider', function (e) {
            $(this).next().text($(this).val());
        });
    });

})(jQuery);

/*
    Color Picker For Input Text
    --------------------------------------- */

(function ($) {
    function initColorPicker(widget) {
        widget.find('.widget-box-color-picker').wpColorPicker({
            change: function (e, ui) {
                $(e.target).val(ui.color.toString());
                $(e.target).trigger('change');
            },
            clear: function (e, ui) {
                $(e.target).trigger('change');
            },
        });
    }

    function onFormUpdate(event, widget) {
        initColorPicker(widget);
    }

    $(document).on('widget-added widget-updated', onFormUpdate);

    $(document).ready(function () {
        $('#widgets-right .widget:has(.widget-box-color-picker)').each(function () {
            initColorPicker($(this));
        });
    });
}(jQuery));