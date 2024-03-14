jQuery(document).ready(function ($) {
    $('.sp-wcsp-slider-area').each(function (index) {

        var _this = $(this),
            sp_wcsp_id = $(this).attr('id');

        if (_this.data('slider') && !$('#' + sp_wcsp_id + ' .sp-wcsp-slider-section').hasClass('swiper-container-initialized')) {
            var wcspSliderData = _this.data('slider');

            var wcspSlider = new Swiper('#' + sp_wcsp_id + ' .swiper-container', {
                // Optional parameters
                loop: wcspSliderData.infinite_loop,
                speed: wcspSliderData.standard_scroll_speed,
                autoplay: wcspSliderData.auto_play ? ({ delay: wcspSliderData.auto_play_speed, disableOnInteraction: false }) : '',
                slidesPerView: wcspSliderData.breakpoints.mobile,
                slidesPerGroup: wcspSliderData.breakpoints.mobile_scroll,
                spaceBetween: wcspSliderData.space_between_cat,
                slidesPerColumn: 1,
                autoHeight: wcspSliderData.auto_height,
                allowTouchMove: wcspSliderData.touch_swipe,
                mousewheel: wcspSliderData.mouse_wheel,
                freeMode: wcspSliderData.freeMode,
                grabCursor: true,
                allowTouchMove: wcspSliderData.mouse_draggable,
                navigation: wcspSliderData.navigation == 'show' || wcspSliderData.navigation == 'hide_mobile' ? ({
                    nextEl: '#' + sp_wcsp_id + ' .sp-wcsp-button-next',
                    prevEl: '#' + sp_wcsp_id + ' .sp-wcsp-button-prev',
                }) : false,
                pagination: wcspSliderData.pagination == 'show' ? ({
                    el: '.sp-wcsp-pagination',
                    type: 'bullets',
                    clickable: true,
                }) : {},
                breakpoints: {
                    480: {
                        slidesPerView: wcspSliderData.breakpoints.tablet,
                        slidesPerGroup: wcspSliderData.breakpoints.tablet_scroll,
                        navigation: wcspSliderData.navigation == 'show' || wcspSliderData.navigation == 'hide_mobile' ? ({
                            nextEl: '#' + sp_wcsp_id + ' .sp-wcsp-button-next',
                            prevEl: '#' + sp_wcsp_id + ' .sp-wcsp-button-prev',
                        }) : false,
                        pagination: wcspSliderData.pagination == 'show' || wcspSliderData.pagination == 'hide_mobile' ? ({
                            el: '.sp-wcsp-pagination',
                            type: 'bullets',
                            clickable: true,
                        }) : {},
                    },
                    736: {
                        slidesPerView: wcspSliderData.breakpoints.laptop,
                        slidesPerGroup: wcspSliderData.breakpoints.laptop_scroll,
                        navigation: wcspSliderData.navigation == 'show' || wcspSliderData.navigation == 'hide_mobile' ? ({
                            nextEl: '#' + sp_wcsp_id + ' .sp-wcsp-button-next',
                            prevEl: '#' + sp_wcsp_id + ' .sp-wcsp-button-prev',
                        }) : false,
                        pagination: wcspSliderData.pagination == 'show' || wcspSliderData.pagination == 'hide_mobile' ? ({
                            el: '.sp-wcsp-pagination',
                            type: 'bullets',
                            clickable: true,
                        }) : {},
                    },
                    980: {
                        slidesPerView: wcspSliderData.breakpoints.desktop,
                        slidesPerGroup: wcspSliderData.breakpoints.desktop_scroll,
                        navigation: wcspSliderData.navigation == 'show' || wcspSliderData.navigation == 'hide_mobile' ? ({
                            nextEl: '#' + sp_wcsp_id + ' .sp-wcsp-button-next',
                            prevEl: '#' + sp_wcsp_id + ' .sp-wcsp-button-prev',
                        }) : false,
                        pagination: wcspSliderData.pagination == 'show' || wcspSliderData.pagination == 'hide_mobile' ? ({
                            el: '.sp-wcsp-pagination',
                            type: 'bullets',
                            clickable: true,
                        }) : {},
                    },
                    1280: {
                        slidesPerView: wcspSliderData.large_desktop,
                        slidesPerGroup: wcspSliderData.large_desktop_scroll,
                        navigation: wcspSliderData.navigation == 'show' || wcspSliderData.navigation == 'hide_mobile' ? ({
                            nextEl: '#' + sp_wcsp_id + ' .sp-wcsp-button-next',
                            prevEl: '#' + sp_wcsp_id + ' .sp-wcsp-button-prev',
                        }) : false,
                        pagination: wcspSliderData.pagination == 'show' || wcspSliderData.pagination == 'hide_mobile' ? ({
                            el: '.sp-wcsp-pagination',
                            type: 'bullets',
                            clickable: true,
                        }) : {},
                    },
                }
            })

            if (wcspSliderData.pause_on_hover == true && wcspSliderData.auto_play == true) {
                $('#' + sp_wcsp_id + ' .swiper-container').on({
                    mouseenter: function () {
                        wcspSlider.autoplay.stop();
                    },
                    mouseleave: function () {
                        wcspSlider.autoplay.start();
                    }
                });
            }

            $(document).on('click scroll', function () {
                if (wcspSliderData.auto_play == true) {
                    wcspSlider.autoplay.start();
                }
            });
        }

    });
    	
	// Add class for gutenberg block.
	$('.sp-wcsp-slider-section').addClass('sp-wcsp-loaded');

});