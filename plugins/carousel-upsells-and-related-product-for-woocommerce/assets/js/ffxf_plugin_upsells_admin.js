var slider = document.getElementById("myRange");
var output = document.getElementById("demo");
var output_two = document.getElementById("demo_two");
output.innerHTML = slider.value; // Display the default slider value
// Update the current slider value (each time you drag the slider handle)
slider.oninput = function () {
    output.innerHTML = this.value;
    var donate = document.getElementById("donate").href = 'https://www.paypal.com/donate/?cmd=_donations&business=lushin.alexandr%40gmail.com&item_name=Plugin%20author%20support+-+Carousel+Upsells+and+Related+Product+for+Woocommerce&currency_code=USD&amount=' + output.innerHTML;
}

jQuery(document).ready(function ($) {

    function check_related() {
        if ($('#glideffxf_related_autoplay, #glideffxf_upsells_autoplay').is(':checked')) {
            $('#mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(4)').show(600).removeClass('tbro');
            $('#mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(5)').show(600).removeClass('tbro');
        } else {
            $('#mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(4)').hide(600).addClass('tbro');
            $('#mainform > div > div:nth-child(2) > table > tbody > tr:nth-child(5)').hide(600).addClass('tbro');
        }
    }

    check_related();

    $('#glideffxf_related_autoplay, #glideffxf_upsells_autoplay').click(function () {
        check_related();
    });

    function check_color_mobile() {
        if ($('#glideffxf_related_mobile_notification, #glideffxf_upsells_mobile_notification').is(':checked')) {
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(4) > tbody > tr:nth-child(12)').show(600).removeClass('tbro');
        } else {
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(4) > tbody > tr:nth-child(12)').hide(600).addClass('tbro');
        }
    }

    check_color_mobile();

    $('#glideffxf_related_mobile_notification, #glideffxf_upsells_mobile_notification').click(function () {
        check_color_mobile();
    });

    function check_related_off() {
        if ($('#glideffxf_related_no_varusel, #glideffxf_related_no_upsells').is(':checked')) {

            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(3)').hide(600);
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(4)').hide(600);
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(5)').hide(600);

            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(8)').hide(600);
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(9)').hide(600);
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(10)').hide(600);
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(11)').hide(600);
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(12)').hide(600);

            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(7)').hide(600);
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > h2:nth-child(5)').hide(600);

            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > h2:nth-child(8)').hide(600);
            $('div#glideffxf_upsells_visual-description').hide(600);
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(10)').hide(600);



            $('#glideffxf_related_visual-description').hide(600);

        } else {

            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(3)').show(600);
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(4)').show(600);
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(5)').show(600);

            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(8)').show(600);
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(9)').show(600);
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(10)').show(600);
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(11)').show(600);
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(12)').show(600);

            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(7)').show(600);
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > h2:nth-child(5)').show(600);
            $('#glideffxf_related_visual-description').show(600);

            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > h2:nth-child(8)').show(600);
            $('div#glideffxf_upsells_visual-description').show(600);
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(10)').show(600);
        }
    }

    check_related_off();

    $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table > tbody > tr:nth-child(1) > td > fieldset > label').click(function () {
        check_related_off();
    });


    // Center Mode
    window.ffxfResModalShow = function (that) {

        tb_show(ffxf_settings_locale.ffxf_Central_mode, '/?TB_inline&inlineId=ffxf_center_mode&width=772&height=600');

        $('#TB_window').removeClass('thickbox-loading');

        return false; // link
    }

    // Mobile notification
    window.ffxfMobileNotif = function (that) {

        tb_show(ffxf_settings_locale.ffxf_Mobile_notification, '/?TB_inline&inlineId=ffxf_mobile_notification&width=772&height=560');

        $('#TB_window').removeClass('thickbox-loading');

        return false; // link
    }

    // mobile center mode
    window.ffxfResModalCM = function (that) {

        tb_show(ffxf_settings_locale.ffxf_Center_mode_in_mobile, '/?TB_inline&inlineId=ffxf_mobile_center_mode&width=772&height=560');

        $('#TB_window').removeClass('thickbox-loading');

        return false; // link
    }

    // Center Mode DEMO
    window.ffxf_center_mode_DEMO = function (that) {

        tb_show(ffxf_settings_locale.ffxf_Center_Mode_DEMO, '/?TB_inline&inlineId=ffxf_center_mode_DEMO_modal&width=772&height=350');

        $('#TB_window').removeClass('thickbox-loading');

        return false; // link
    }

    $('#ffxf_center_mode_DEMO').click(function (e) {

        e.preventDefault();
        ffxf_center_mode_DEMO();


        var before = document.querySelector('#options-peek-before')
        var after = document.querySelector('#options-peek-after')

        var glide_center_mode = new Glide('#options_center_mode', {
            perView: 5,
            type: 'carousel',
            autoplay: 2000,
            peek: {
                before: before.value,
                after: after.value
            }
        })

        function peek() {
            glide_center_mode.update({
                peek: {
                    before: before.value,
                    after: after.value
                }
            })
        }

        before.addEventListener('input', peek)
        after.addEventListener('input', peek)

        glide_center_mode.mount()

    });

    // Animation DEMO
    window.ffxfDemoAnim = function (that) {

        tb_show(ffxf_settings_locale.ffxf_Demo_anim, '/?TB_inline&inlineId=ffxf_demo_anim&width=772&height=325');

        $('#TB_window').removeClass('thickbox-loading');

        return false; // link
    }

    $('#ffxfDemoAnim').click(function (e) {

        e.preventDefault();
        ffxfDemoAnim();

        var select = document.querySelector('#options-animation-timing-func-select')

        var glide = new Glide('#options-animation-timing-func', {
            animationTimingFunc: select.value,
            animationDuration: 800,
            perView: 5,
            type: 'carousel',
            autoplay: 2000

        })

        select.addEventListener('change', function (event) {
            glide.update({
                animationTimingFunc: event.target.value
            })
        })

        glide.mount()

    });

    // Animation Duration DEMO
    window.ffxfDemoAnimDuration = function (that) {

        tb_show('Animation Duration DEMO', '/?TB_inline&inlineId=ffxf_demo_Duration&width=772&height=325');

        $('#TB_window').removeClass('thickbox-loading');

        return false; // link
    }

    $('#glideffxf_animationDuration_link').click(function (e) {

        e.preventDefault();
        ffxfDemoAnimDuration();

        var input = document.querySelector('#options-animation-duration-input')

        var glide_animationDuration = new Glide('#options_animationDuration', {
            animationDuration: input.value,
            perView: 5,
            type: 'carousel',
            autoplay: 2000
        })

        input.addEventListener('input', function (event) {
            glide_animationDuration.update({
                animationDuration: event.target.value
            })
        })

        glide_animationDuration.mount()

    });

    // Gap DEMO
    window.ffxfDemoGap = function (that) {

        tb_show(ffxf_settings_locale.ffxf_Demo_gap, '/?TB_inline&inlineId=ffxf_demo_Gap&width=772&height=325');

        $('#TB_window').removeClass('thickbox-loading');

        return false; // link
    }


    $('#glideffxf_gap_link').click(function (e) {

        e.preventDefault();
        ffxfDemoGap();

        var input = document.querySelector('#options-gap-input')

        var glide_gap = new Glide('#options_Gap', {
            gap: input.value,
            perView: 5,
            type: 'carousel',
            autoplay: 2000
        })

        input.addEventListener('input', function (event) {
            glide_gap.update({
                gap: event.target.value
            })
        })

        glide_gap.mount()

    });


    function check_center_mode() {

        if ($('#glideffxf_related_center_mode, #glideffxf_upsells_center_mode').is(':checked')) {
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(7) > tbody > tr:nth-child(2)').show(600).removeClass('tbro');
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(7) > tbody > tr:nth-child(3)').show(600).removeClass('tbro');
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(7) > tbody > tr:nth-child(4)').show(600).removeClass('tbro');
        } else {
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(7) > tbody > tr:nth-child(2)').hide(600).addClass('tbro');
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(7) > tbody > tr:nth-child(3)').hide(600).addClass('tbro');
            $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(7) > tbody > tr:nth-child(4)').hide(600).addClass('tbro');
        }

    }

    check_center_mode();

    $('#glideffxf_related_center_mode, #glideffxf_upsells_center_mode').click(function () {
        check_center_mode();
    });


    $('input[value="one_"]').after('<div class="ffxf_arrows"><div><img src="' + ffxf_settings_locale.ffxf_img + 'one_left.svg" alt=""></div><div><img src="' + ffxf_settings_locale.ffxf_img + 'one_right.svg" alt=""></div></div>');
    $('input[value="two_"]').after('<div class="ffxf_arrows"><div><img src="' + ffxf_settings_locale.ffxf_img + 'two_left.svg" alt=""></div><div><img src="' + ffxf_settings_locale.ffxf_img + 'two_right.svg" alt=""></div></div>');
    $('input[value="three_"]').after('<div class="ffxf_arrows"><div><img src="' + ffxf_settings_locale.ffxf_img + 'three_left.svg" alt=""></div><div><img src="' + ffxf_settings_locale.ffxf_img + 'three_right.svg" alt=""></div></div>');
    $('input[value="four_"]').after('<div class="ffxf_arrows"><div><img src="' + ffxf_settings_locale.ffxf_img + 'four_left.svg" alt=""></div><div><img src="' + ffxf_settings_locale.ffxf_img + 'four_right.svg" alt=""></div></div>');

    $('input[value="five_"]').after('<div class="ffxf_arrows"><div><img src="' + ffxf_settings_locale.ffxf_img + 'five_left.svg" alt=""></div><div><img src="' + ffxf_settings_locale.ffxf_img + 'five_right.svg" alt=""></div></div>');
    $('input[value="six_"]').after('<div class="ffxf_arrows"><div><img src="' + ffxf_settings_locale.ffxf_img + 'six_left.svg" alt=""></div><div><img src="' + ffxf_settings_locale.ffxf_img + 'six_right.svg" alt=""></div></div>');
    $('input[value="seven_"]').after('<div class="ffxf_arrows"><div><img src="' + ffxf_settings_locale.ffxf_img + 'seven_left.svg" alt=""></div><div><img src="' + ffxf_settings_locale.ffxf_img + 'seven_right.svg" alt=""></div></div>');
    $('input[value="eight_"]').after('<div class="ffxf_arrows"><div><img src="' + ffxf_settings_locale.ffxf_img + 'eight_left.svg" alt=""></div><div><img src="' + ffxf_settings_locale.ffxf_img + 'eight_right.svg" alt=""></div></div>');

    $('input[value="nine_"]').after('<div class="ffxf_arrows"><div><img src="' + ffxf_settings_locale.ffxf_img + 'nine_left.svg" alt=""></div><div><img src="' + ffxf_settings_locale.ffxf_img + 'nine_right.svg" alt=""></div></div>');
    $('input[value="ten_"]').after('<div class="ffxf_arrows"><div><img src="' + ffxf_settings_locale.ffxf_img + 'ten_left.svg" alt=""></div><div><img src="' + ffxf_settings_locale.ffxf_img + 'ten_right.svg" alt=""></div></div>');
    $('input[value="eleven_"]').after('<div class="ffxf_arrows"><div><img src="' + ffxf_settings_locale.ffxf_img + 'eleven_left.svg" alt=""></div><div><img src="' + ffxf_settings_locale.ffxf_img + 'eleven_right.svg" alt=""></div></div>');
    $('input[value="twelve_"]').after('<div class="ffxf_arrows"><div><img src="' + ffxf_settings_locale.ffxf_img + 'twelve_left.svg" alt=""></div><div><img src="' + ffxf_settings_locale.ffxf_img + 'twelve_right.svg" alt=""></div></div>');

    $('input[value="thirteen_"]').after('<div class="ffxf_arrows"><div><img src="' + ffxf_settings_locale.ffxf_img + 'thirteen_left.svg" alt=""></div><div><img src="' + ffxf_settings_locale.ffxf_img + 'thirteen_right.svg" alt=""></div></div>');
    $('input[value="fourteen_"]').after('<div class="ffxf_arrows"><div><img src="' + ffxf_settings_locale.ffxf_img + 'fourteen_left.svg" alt=""></div><div><img src="' + ffxf_settings_locale.ffxf_img + 'fourteen_right.svg" alt=""></div></div>');
    $('input[value="fifteen_"]').after('<div class="ffxf_arrows"><div><img src="' + ffxf_settings_locale.ffxf_img + 'fifteen_left.svg" alt=""></div><div><img src="' + ffxf_settings_locale.ffxf_img + 'fifteen_right.svg" alt=""></div></div>');
    $('input[value="sixteen_"]').after('<div class="ffxf_arrows"><div><img src="' + ffxf_settings_locale.ffxf_img + 'sixteen_left.svg" alt=""></div><div><img src="' + ffxf_settings_locale.ffxf_img + 'sixteen_right.svg" alt=""></div></div>');

    var check_icons = $("#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(7) > tbody > tr:nth-child(8) > td > fieldset input[name='glideffxf_releted_navigation'], #mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(7) > tbody > tr:nth-child(8) > td > fieldset input[name='glideffxf_upsells_navigation']");
    var check_icons_checked = $("input[name='glideffxf_releted_navigation']:checked, input[name='glideffxf_upsells_navigation']:checked");
    var check_input = $("#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(7) > tbody > tr:nth-child(8) > td > fieldset .ffxf_arrows");
    var check_input_div = $(".ffxf_arrows > div");


    // color
    $(document).on('mousemove', '#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(7) > tbody > tr:nth-child(9) > td > div.iris-picker.iris-border', function () {
        var color = $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(7) > tbody > tr:nth-child(9) > td > span.colorpickpreview').attr('style');
        check_input_div.attr('style', color);
    });

    $('input#glideffxf_releted_picker').on('change', function (e) {
        var color = $('#mainform > div.wrapper_setting_ffxf > div:nth-child(2) > table:nth-child(7) > tbody > tr:nth-child(9) > td > span.colorpickpreview').attr('style');
        check_input_div.attr('style', color);
    });

    var check_color = $('input#glideffxf_releted_picker').val();
    check_input_div.css('background', check_color);

    check_icons_checked.next(".ffxf_arrows").addClass('ffxf_arrows_border');

    check_icons.change(function () {
        check_input.removeClass("ffxf_arrows_border");
        if ($(this).prop("checked")) {
            $(this).next(".ffxf_arrows").addClass('ffxf_arrows_border');
        }
    });

});

