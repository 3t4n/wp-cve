;jQuery(document).ready(function($) {
    var sttm = {};
    if (typeof sttData !== "undefined") {
        sttm = sttData;
    }

    var icon = (sttm['buton_icon'] ) ? sttm['buton_icon'] : 'fa fa-facebook';
    var stt_icon_type = (sttm['stt_icon_type'] ) ? sttm['stt_icon_type'] : '';
    var stt_button_text = (sttm['stt_button_text'] ) ? '<span>'+sttm['stt_button_text'] + '</span>' : '';

    // icon generate
    if ('icon' == stt_icon_type && 'svg' != icon['library'] && '' != icon['value']) {
        icon = '<i class="'+icon['value']+'" aria-hidden="true"></i>';

    } else if ('icon' == stt_icon_type && 'svg' == icon['library'] && '' != icon['value']['url']) {
        icon = '<img src="'+icon['value']['url']+'" alt="icon">';

    } else if ( stt_icon_type == 'image' && '' != icon  ) {
        icon = '<img src="'+icon['url']+'" alt="icon">';

    } else {
        icon = '<svg id="svg8" clip-rule="evenodd" fill-rule="evenodd" height="25" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" width="25" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg"><path id="path2" d="m12 9.414-6.293 6.293c-.39.39-1.024.39-1.414 0s-.39-1.024 0-1.414l7-7c.39-.391 1.024-.391 1.414 0l7 7c.39.39.39 1.024 0 1.414s-1.024.39-1.414 0z"/></svg>';
    }

    var $scrollToTopButton = $('<div class="htmega-stt-wrap" id="htmegaScrollToTopBtn">'+stt_button_text+ ' ' +icon+ ' </div>').appendTo('body');

    // Global Settings
    var sttG = {};
    if (typeof stt !== "undefined") {
        sttG = stt;
    }
    var stt_color = (sttG['stt_color']) ? sttG['stt_color'] : '#ffffff';
    var stt_bg_color = (sttG['stt_bg_color']) ? sttG['stt_bg_color'] : '#000000';
    
    var stt_bg_color_hover = (sttG['stt_bg_color_hover']) ? sttG['stt_bg_color_hover'] : '#ffffff';
    var stt_color_hover = (sttG['stt_color_hover']) ? sttG['stt_color_hover'] : '#000000';
    var stt_bottom_space = (sttG['stt_bottom_space']) ? sttG['stt_bottom_space'] : '30';
    var position = (sttG['position']) ? sttG['position'] : 'bottom_right';
    var position2 = ('left' == position ) ? 'right': 'left';

    $scrollToTopButton.css('background', stt_bg_color).css('color', stt_color).css(position, '15px').css(position2, 'auto').css('bottom', stt_bottom_space + 'px');
    $(".htmega-stt-wrap svg path").css('fill', stt_color);

    // Button hover 
    $('.htmega-stt-wrap').on("mouseenter", function() {
        $(this).css("background", stt_bg_color_hover).css('color', stt_color_hover); 
        $(".htmega-stt-wrap svg path").css('fill', stt_color_hover);
    }).on("mouseleave", function() {
        $(this).css("background", stt_bg_color).css('color', stt_color); 
        $(".htmega-stt-wrap svg path").css('fill', stt_color);
    });

    $(window).on("scroll", function() {

        if ($(this).scrollTop() > 20) {
            $('.htmega-stt-wrap').css('visibility', 'visible').css('opacity',1);

        } else {
            $('.htmega-stt-wrap').css('visibility', 'hidden').css('opacity',0);
        }
    });

    $('.htmega-stt-wrap').on('click', function() {
        $('html, body').animate({ scrollTop: 0 }, 1000);
        return false;
    });

});


