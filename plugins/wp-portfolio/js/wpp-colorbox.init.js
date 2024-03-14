jQuery(document).ready(function($) {
    $('div.website-thumbnail.wpp-lightbox > img').colorbox({
        transition: lightbox_settings.setting_lightbox_transition,
        speed: lightbox_settings.setting_lightbox_speed,
        overlayClose: lightbox_settings.setting_lightbox_overlay_close,
        escKey: lightbox_settings.setting_lightbox_esckey_close,
        initialWidth: 100,
        initialHeight: 100,
        className: "portfolio-lightbox",
        closeButton: lightbox_settings.setting_lightbox_close_button,
        title: function () {
            var lightbox_title = lightbox_settings.setting_lightbox_sitename_as_title ? $(this).closest('.portfolio-website').find('.website-name').text() : false;
            return lightbox_title;
        },
        close: lightbox_settings.setting_lightbox_close_button_text,
        html: function () {
            var html;
            switch (lightbox_settings.setting_lightbox_style) {
                case '1':
                    html = '<img src="' + $(this).attr('src') + '">';
                    break;
                case '2':
                    html = '';
                    var obj = $(this).closest('.portfolio-website').clone();
                    obj.find('div').show();
                    obj.find('.website-thumbnail').removeClass('wpp-lightbox');
                    obj.find('.website-thumbnail img').removeClass('cboxElement');
                    obj.find('.expand-button, .website-clear').remove();
                    html += obj.html();
                    break;
                default:
                    html = '<img src="' + $(this).attr('src') + '">';
                    break;
            }
            return html;
        },
        slideshow: false
    });
});
