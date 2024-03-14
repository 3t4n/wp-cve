jQuery(document).ready(function () {
    $ = jQuery.noConflict();

    $(document).on('click', '.oxi-image-notice .oxi-image-done', function () {
        var rate = $(this).closest('.oxi-image-notice');
        rate.slideUp();
        $.ajax({
            url: ajaxurl,
            data: {
                action: 'oxi_image__addons_notice'
            }
        });
    });
    function delay(callback, ms) {
        var timer = 0;
        return function () {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, ms || 0);
        };
    }

    $("input[name=oxi_image_addons_key]").on("keyup", delay(function (e) {
        var $This = $(this), $value = $This.val();
        if ($value !== $.trim($value)) {
            $value = $.trim($value);
            $This.val($.trim($value));
        }
         $('.oxi_image_addons_license_massage').html('<span class="spinner sa-spinner-open"></span>');
        $.ajax({
            url: oxiadmincommon.url,
            type: 'post',
            data: {
                action: 'oxi_image__addons_settings',
                nonce: oxiadmincommon.nonce,
                key: $value
            },
            success(data) {
                if (data) {
                    data = JSON.parse(data);
                    $('.oxi_image_addons_license_massage').html(data.massage);
                    $('.oxi_image_addons_license_text .oxi-addons-settings-massage').html(data.text);
                }
            }
        });


    }, 1000));

});