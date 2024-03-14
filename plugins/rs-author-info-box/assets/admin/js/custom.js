jQuery(document).ready(function($) {
    if (typeof wp.media !== 'undefined') {
        var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;
        $(document).on('click', '.custommedia', function(e) {
            // var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = $(this);
            var id = button.attr('id');
            _custom_media = true;
            wp.media.editor.send.attachment = function(props, attachment) {
                if (_custom_media) {
                    $('input#' + id).val(attachment.id);
                    $('span#preview' + id).css('background-image', 'url(' + attachment.url + ')');
                    $('input#' + id).trigger('change');
                } else {
                    return _orig_send_attachment.apply(this, [props, attachment]);
                };
            }
            wp.media.editor.open(button);
            return false;
        });
        $('.add_media').on('click', function() {
            _custom_media = false;
        });
        $(document).on('click', '.remove-media', function() {
            var parent = $(this).parents('p');
            parent.find('input[type="media"]').val('').trigger('change');
            parent.find('span').css('background-image', 'url()');
        });
    }
});