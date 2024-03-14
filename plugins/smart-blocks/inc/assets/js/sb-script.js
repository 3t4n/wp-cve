jQuery(document).ready(function ($) {
    if ($(document).find('.sb-carousel-block-wrap').length > 0) {
        $.each($(document).find('.sb-carousel-block-wrap'), function (key, val) {
            var params = JSON.parse($(this).attr('data-params'));
            $(this).owlCarousel({
                loop: true,
                autoplay: JSON.parse(params.autoplay),
                autoplayTimeout: params.pause,
                nav: JSON.parse(params.nav),
                dots: JSON.parse(params.dots),
                navText: ['<i class="mdi-chevron-left"></i>', '<i class="mdi-chevron-right"></i>'],
                responsive: {
                    0: {
                        items: params.items_mobile,
                        margin: params.margin_mobile,
                        stagePadding: params.stagepadding_mobile
                    },
                    580: {
                        items: params.items_tablet,
                        margin: params.margin_tablet,
                        stagePadding: params.stagepadding_tablet
                    },
                    860: {
                        items: params.items,
                        margin: params.margin,
                        stagePadding: params.stagepadding
                    }
                }
            })
        });
    }

    if ($(document).find('.sb-ticker').length > 0) {
        $.each($(document).find('.sb-ticker'), function (key, val) {
            var params = JSON.parse($(this).find('.owl-carousel').attr('data-params'));
            $(this).find('.owl-carousel').owlCarousel({
                items: 1,
                margin: 10,
                loop: true,
                mouseDrag: false,
                autoplay: params.autoplay,
                autoplayTimeout: parseInt(params.pause) * 1000,
                nav: true,
                dots: false,
                navText: ['<i class="mdi-chevron-left"></i>', '<i class="mdi-chevron-right"></i>']
            });
        })
    }

    $('body').on('click', '.wp-block-smart-blocks.block-editor-block-list__block a', function () {
        return false;
    });

    $('#sb-fonts-frontend-css').appendTo($('head'));
    $('#sb-style-frontend-inline-css').appendTo($('head'));
});
