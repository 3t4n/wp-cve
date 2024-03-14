jQuery(document).ready(function ($) {

    $('.mo-control-desktop').addClass('active');

    $('.mo-responsive-options .preview-desktop').on('click', function () {
        $('.wp-full-overlay').removeClass('preview-mobile').removeClass('preview-tablet').addClass('preview-desktop');
        $('.mo-responsive-options button').removeClass('active');
        $(this).addClass('active');
        $('.mo-control-mobile, .mo-control-tablet').removeClass('active');
        $('.mo-control-desktop').addClass('active');
    });

    $('.mo-responsive-options .preview-tablet').on('click', function () {
        $('.wp-full-overlay').removeClass('preview-desktop').removeClass('preview-mobile').addClass('preview-tablet');
        $('.mo-responsive-options button').removeClass('active');
        $(this).addClass('active');
        $('.mo-control-desktop, .mo-control-mobile').removeClass('active');
        $('.mo-control-tablet').addClass('active');
    });

    $('.mo-responsive-options .preview-mobile').on('click', function () {
        $('.wp-full-overlay').removeClass('preview-desktop').removeClass('preview-tablet').addClass('preview-mobile');
        $('.mo-responsive-options button').removeClass('active');
        $(this).addClass('active');
        $('.mo-control-desktop, .mo-control-tablet').removeClass('active');
        $('.mo-control-mobile').addClass('active');
    });

});