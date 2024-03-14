jQuery(document).ready(function($) {

    var progressBarWrap = $('<div class="htmega-rpbar-wrap"><div class="htmega-reading-progress-bar"></div></div>').appendTo('body');
    var progressBar = $('.htmega-reading-progress-bar');

    var rpbarm = {};
    if (typeof rpbar !== "undefined") {
        rpbarm = rpbar;
    }
    var fillcolor = (rpbarm['fill_color'] ) ? rpbarm['fill_color'] :  '#D43A6B';
    var bgcolor = (rpbarm['bg_color']) ? rpbarm['bg_color'] : 'transparent';
    var loading_height = (rpbarm['loading_height']) ? rpbarm['loading_height'] : 5;
    var position = (rpbarm['position']) ? rpbarm['position'] : 'top';    
    progressBarWrap.css('height', loading_height ).css('background', bgcolor).css(position, 0);
    
    $(window).on("scroll", function() {
        var scrollPercent = ($(window).scrollTop() / ($(document).height() - $(window).height())) * 100;
        progressBar.css('width', scrollPercent + '%').css('background', fillcolor);
        //progressBar.css('width', scrollPercent + '%');
    });
});


