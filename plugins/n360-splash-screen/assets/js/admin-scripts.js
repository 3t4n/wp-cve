jQuery(document).ready(function ($) {

    $('.my-color-field').wpColorPicker();

    $('.upload-btn').click(function () {

        var target = $(this).attr('data-target');

        var gallery_window = wp.media( {
            title: 'Select Image',
            library: { type: 'image' },
            multiple: false,
            button: { text: 'Use this image' }
        });

        gallery_window.on('select', function () {
            var user_selection = gallery_window.state().get('selection').first().toJSON();

            console.log(user_selection);
            console.log(user_selection.url);

            $(target).val(user_selection.url);
        });

        gallery_window.open();

    });
});
