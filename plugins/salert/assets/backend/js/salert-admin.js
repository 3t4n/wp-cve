(function($) {
    'use strict';

    $.fn.extend({
        hasClasses: function(selectors) {
            var self = this;
            for (var i in selectors) {
                if ($(self).hasClass(selectors[i]))
                    return true;
            }
            return false;
        }
    });

    //Transitation effect

    $('#transition_style').on('change', function() {
        var val = $(this).val();
        var $sel = $('#popup_template');
        if ($sel.hasClass(val)) {
            return;
        }
        if ($sel.hasClasses(['fadeInLeft', 'fadeInUp', 'fadeInRight', 'bounceInRight', 'bounceInLeft', 'bounceInUp','zoomIn','zoomInDown','zoomInLeft','zoomInRight','zoomInUp','jackInTheBox','rollIn','lightSpeedIn'])) {
            $sel.removeClass('fadeInLeft fadeInUp fadeInRight bounceInRight bounceInLeft bounceInUp zoomIn zoomInDown zoomInLeft zoomInRight zoomInUp jackInTheBox rollIn lightSpeedIn');
        }
        $sel.addClass(val);
    });

    // colorpicker
    $('.color-picker').wpColorPicker();

    //Background Color picker
    $("#popup_bgcolor").wpColorPicker(
        'option',
        'change',
        function(event, ui) {
            var color = ui.color.toString();
            var $destination = $('.popup_template');
            $destination.css('background-color', color);
        }
    );
    //on load
    var bg_color = $('#popup_bgcolor').val();
    $('.popup_template').css('background-color', bg_color);

    //bg image
    var bg_image = $('.product-imagefield input').val();
    $('.popup_template').css('background-image', 'url(' + bg_image + ')');


    //Text Color picker
    $("#popup_textcolor").wpColorPicker(
        'option',
        'change',
        function(event, ui) {
            var color = ui.color.toString();
            $('.popup_template').css('color', color);

        }
    );
    //on load
    var text_color = $('#popup_textcolor').val();
    $('.popup_template').css('color', text_color);

    //Text Font Size
    $('body').on('keyup', '#popup_font_size', function() {
        var fontSize = $(this).val();
        $('.popup-item > p').css({
            "font-size": fontSize + "px"
        });
    });
    //on load        
    var font_size = $('#popup_font_size').val();
    $('.popup-item > p').css({
        "font-size": font_size + "px"
    });

    //Text Transform
    $('#popup_text_tnsfrm').on('change', function() {
        var textTsm = $(this).val();
        $('.popup-item > p').css({
            "text-transform": textTsm
        });
    });
    //on load
    var text_tsm = $('#popup_text_tnsfrm option:selected').val();
    $('.popup-item > p').css({
        "text-transform": text_tsm
    });

    //Layout Style eg:image/ without image
    $('#template_layout').on('change', function() {
        var val = $(this).val();
        var $sel = $('#popup_template');
        if ($sel.hasClass(val)) {
            return;
        }
        if ($sel.hasClasses(['imageOnLeft', 'imageOnRight', 'textOnly'])) {
            $sel.removeClass('imageOnLeft imageOnRight textOnly');
        }
        $sel.addClass(val);

    });
    // on load
    var val = $('#template_layout option:selected').val();
    var $sel = $('#popup_template');
    if ($sel.hasClass(val)) {
        return;
    }
    if ($sel.hasClasses(['imageOnLeft', 'imageOnRight', 'textOnly'])) {
        $sel.removeClass('imageOnLeft imageOnRight textOnly');
    }
    $sel.addClass(val);

    //Image Style
    $('#image_style').on('change', function() {
        var val = $(this).val();
        var $sel = $('#popup_template');
        if ($sel.hasClass(val)) {
            return;
        }
        if ($sel.hasClasses(['square', 'circle'])) {
            $sel.removeClass('square circle');
        }
        $sel.addClass(val);

    });
    // on load
    var val = $('#image_style option:selected').val();
    var $sel = $('#popup_template');
    if ($sel.hasClass(val)) {
        return;
    }
    if ($sel.hasClasses(['square', 'circle'])) {
        $sel.removeClass('square circle');
    }
    $sel.addClass(val);

    //Layout Position eg: Bottomm Left or buttom right 
    $('#template_position').on('change', function() {
        var val = $(this).val();
        var $sel = $('.popup_position');
        if ($sel.hasClass(val)) {
            return;
        }
        if ($sel1.hasClasses(['bottomLeft', 'bottomRight', 'topLeft', 'topRight'])) {
            $sel1.removeClass('bottomLeft bottomRight topLeft topRight');
        }
        $sel.addClass(val);

    });
    //on load
    var val1 = $('#template_position option:selected').val();
    var $sel1 = $('.popup_position');
    if ($sel1.hasClass(val1)) {
        return;
    }
    if ($sel1.hasClasses(['bottomLeft', 'bottomRight', 'topLeft', 'topRight'])) {
        $sel1.removeClass('bottomLeft bottomRight topLeft topRight');
    }
    $sel1.addClass(val1);


$('body').on('click','ul.tab-wrap li.tab', function(){
    var currentPosition = $(this).attr('data-id');
    if( (currentPosition == 'general') || (currentPosition == 'display') ){
        $('.salert-save-btn-wrap').show();
    }else{
        $('.salert-save-btn-wrap').hide();
    }
});

})(jQuery);