jQuery(window).load(function () {
    let $ = jQuery;
    $('body')
        .on('click', '.atbs-tabs-wrapper .nav-item', function () {
            let activeTabId = $(this).children().attr('href').split('#')[1];
            $('.tab-content [data-tab]').css('display', 'none');
            $('.tab-content [data-tab="' + activeTabId + '"]').css('display', 'block');
        })
        .on('click', '.carousel-control-next,.carousel-control-prev', function () {
            let goForward = true;
            if ($(this).hasClass('carousel-control-prev')) goForward = false;
            let carouselId = $(this).attr('href');
            let carouselItemsBlock = $(carouselId + ' [data-type="attire-blocks/carousel-item"]');

            carouselItemsBlock.each((idx, item) => {
                item = $(item).children('.carousel-item');
                if ($(item).hasClass('active')) {
                    if (goForward) {
                        if (idx === carouselItemsBlock.length - 1) {
                            idx = 0
                        } else {
                            idx += 1;
                        }
                        makeItemActive(carouselId, idx);
                        return false;
                    } else {
                        if (idx === 0) {
                            idx = carouselItemsBlock.length - 1
                        } else {
                            idx -= 1;
                        }
                        makeItemActive(carouselId, idx);
                        return false;
                    }
                }
            });
        });


    function makeItemActive(carouselId, index) {
        let carouselItemsBlock = $(carouselId + ' [data-type="attire-blocks/carousel-item"]');
        carouselItemsBlock.each((idx, item) => {
            item = $(item).children('.carousel-item');
            if ($(item).hasClass('active')) {
                $(item).removeClass('active');
            }
            item = $(item).closest('.carousel-item');
            if (idx === index) {
                $(item).addClass('active');
                $(`${carouselId} .carousel-indicators li`).removeClass('active');
                $(`${carouselId} .carousel-indicators [data-slide-to=${index}]`).addClass('active');
            }
        });
    }
});