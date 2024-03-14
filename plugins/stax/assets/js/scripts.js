wgxpath.install();

jQuery(document).ready(function ($) {
    var mediaFrame;

    $('body').on('click', '.galleryPopup', function (e) {
        e.preventDefault();
        var _this = $(this);
        var id = $(this).attr('data-upl-id');

        mediaFrame = wp.media.frames.mediaFrame = wp.media({
            frame: 'select',
            title: 'Select your image',
            button: {
                text: 'Insert'
            },
            multiple: false
        });

        mediaFrame.on('select', function () {
            var attachment = mediaFrame.state().get('selection').first().toJSON();
            _this.parent().parent().find('.sqImage.' + id).attr('src', attachment.url);
        });

        mediaFrame.open();
    });
});
