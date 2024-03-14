(function ($) {
    $(document).ready(function () {
        $('.ju-top-tabs .link-tab').on('click', function () {
            var href = $(this).attr('href').replace(/#/g, '');
            $('.wpms_hash').val(href);
        });

        $('.wpms-notice-dismiss').on('click', function () {
            $('.saved_infos').slideUp();
        });

        $('.tabs.ju-menu-tabs .tab a.link-tab').on('click', function () {
            var href = $(this).attr('href').replace(/#/g, '');
            window.location.hash='#' + href;
            setTimeout(function () {
                $('#' + href + ' ul.tabs').itabs();
            }, 100);
        });

        tippy('.wp-meta-seo_page_metaseo_settings .ju-setting-label', {
            animation: 'scale',
            duration: 0,
            arrow: false,
            placement: 'top',
            theme: 'metaseo-tippy tippy-rounded',
            onShow(instance) {
                instance.popper.hidden = instance.reference.dataset.tippy ? false : true;
                instance.setContent(instance.reference.dataset.tippy);
            }
        });

        $('.wpms-settings-dismiss').on('click', function() {
            $(this).parent('.save-settings-mess').hide('fade');
        });
    });
})(jQuery);

// Open graph
jQuery(function ($) {
    $('.metaseo_enable_op_markup').on('click', () => {
        if ($('#metaseo_enable_op_markup').is(':checked')) {
            $('.wpms-op-markup-source').show();
            if ($('#wpms-op-markup-source').val() === 'setDefaultImage') {
                $('.wpms-op-markup-upload').show();
            }
        } else {
            $('.wpms-op-markup-source').hide();
            $('.wpms-op-markup-upload').hide();
        }
    });

    $('#wpms-op-markup-source').on('change', () => {
        if ($('#wpms-op-markup-source').val() === 'setDefaultImage') {
            $('.wpms-op-markup-upload').show();
        } else {
            $('.wpms-op-markup-upload').hide();
        }
    });
});

// Twitter
jQuery(function ($) {
    $('.metaseo_enable_twitter_img').on('click', () => {
        if ($('#metaseo_enable_twitter_img').is(':checked')) {
            $('.wpms-twitter-img-source').show();
            if ($('#wpms-twitter-img-source').val() === 'setDefaultImage') {
                $('.wpms-twitter-img-upload').show();
            }
        } else {
            $('.wpms-twitter-img-source').hide();
            $('.wpms-twitter-img-upload').hide();
        }
    });

    $('#wpms-twitter-img-source').on('change', () => {
        if ($('#wpms-twitter-img-source').val() === 'setDefaultImage') {
            $('.wpms-twitter-img-upload').show();
        } else {
            $('.wpms-twitter-img-upload').hide();
        }
    });
});

// Upload image
'use strict';
var wpmseo_target_id;
jQuery(document).ready(function ($) {
    var wpmseo_uploader;
    $('.wpmseo_image_upload_button').on('click', function (e) {
        wpmseo_target_id = $(this).attr('id').replace(/-btn$/, '');
        e.preventDefault();
        if (wpmseo_uploader) {
            wpmseo_uploader.open();
            return;
        }
        wpmseo_uploader = wp.media.frames.file_frame = wp.media({
            title: wpmsSettingsL10n.choose_image,
            button: {text: wpmsSettingsL10n.choose_image},
            multiple: false
        });

        wpmseo_uploader.on('select', function () {
            var attachment = wpmseo_uploader.state().get('selection').first().toJSON();
            $('#' + wpmseo_target_id).val(attachment.url);
            wpmseo_uploader.close();
        });

        wpmseo_uploader.open();
    });
});
