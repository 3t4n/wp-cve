jQuery(document).ready(function ($) {
    "use strict";
    
    // Add a product to compare list
    $(document).on('click', '.fami-wccp-button:not(.added), .fami-wccp-search-result-item', function (e) {
        e.preventDefault();
        var $this = $(this);
        
        if ($this.is('.processing')) {
            return false;
        }
        
        $this.addClass('processing');
        var id = $this.data('product_id');
        var include_return = '';
        if ($this.is('.fami-wccp-search-result-item')) {
            include_return = 'compare_table';
        }
        
        var data = {
            action: fami_wccp['ajax_actions']['action_add'],
            id: id,
            include_return: include_return,
            context: 'frontend'
        };
        
        $.post(fami_wccp['ajaxurl'], data, function (response) {
            
            $this.removeClass('processing').addClass('added').attr('href', response['compare_page_url']).text(fami_wccp['text']['added']);
            if (!$('.fami-wccp-products-list-wrap .fami-wccp-products-list').length) {
                $('body').append('<div class="fami-wccp-products-list-wrap"></div>');
            }
            var products_list_tmp_html = fami_wccp['template']['products_list'];
            var go_to_compare_html = '<a href="' + response['compare_page_url'] + '" class="fami-wccp-go-to-compare">' + fami_wccp['text']['compare'] + '</a>';
            products_list_tmp_html = products_list_tmp_html.replace('{{products_list}}', response['list_products_html']).replace('{{go_to_compare_page}}', go_to_compare_html);
            $('.fami-wccp-products-list-wrap').html(products_list_tmp_html);
            
            if (include_return == 'compare_table') {
                $('.fami-wccp-content-wrap').replaceWith(response['compare_table_html']);
            }
            
            $(document).trigger('fami_wccp_added_to_compare');
            
        });
        
        return false;
    });
    
    // Remove product from compare list
    $(document).on('click', '.fami-wccp-remove-product, .clear-all-compare-btn', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $thisItem = $this.closest('.compare-item');
        var response_type = 'product_list';
        if ($this.closest('.fami-wccp-col').length) {
            $thisItem = $this.closest('.fami-wccp-col');
            response_type = 'compare_table';
        }
        
        if ($this.is('.clear-all-compare-btn')) {
            $this.closest('.products-compare-list').addClass('processing');
        }
        
        if ($thisItem.is('.processing') || $this.is('.processing')) {
            return false;
        }
        
        $thisItem.addClass('processing');
        $this.addClass('processing');
        var id = $this.data('product_id');
        
        var data = {
            action: fami_wccp['ajax_actions']['action_remove'],
            id: id,
            context: 'frontend',
            response_type: response_type
        };
        
        $.post(fami_wccp['ajaxurl'], data, function (response) {
            $this.removeClass('processing');
            if ($this.is('.clear-all-compare-btn')) {
                $this.closest('.products-compare-list').removeClass('processing');
            }
            if (response_type == 'product_list') {
                $('.products-compare-list').replaceWith(response);
            }
            else {
                $('.fami-wccp-content-wrap').replaceWith(response);
            }
            $(document).trigger('fami_wccp_removed_product_from_list');
            
        });
        
        return false;
    });
    
    // Added to compare list event
    $(document).on('fami_wccp_added_to_compare', function () {
        fami_wccp_show_products_list();
    });
    
    // Add more products to comparison list (show popup)
    $(document).on('click', '.fami-wccp-add-more-product', function (e) {
        e.preventDefault();
        if (!$('body form[name="fami_wccp_search_product_form"]').length) {
            $('body').append(fami_wccp['template']['add_product_form']);
        }
        $('body').toggleClass('fami-wccp-show-popup');
        return false;
    });
    
    $(document).on('submit', 'form[name="fami_wccp_search_product_form"]', function (e) {
        e.preventDefault();
        var $thisForm = $(this);
        var search_keyword = $thisForm.find('input[name="fami_wccp_search_product"]').val();
        
        if ($thisForm.is('.processing')) {
            return false;
        }
        
        $thisForm.addClass('processing');
        
        var data = {
            action: 'fami_wccp_search_product_via_ajax',
            search_keyword: search_keyword,
            context: 'frontend'
        };
        
        $.post(fami_wccp['ajaxurl'], data, function (response) {
            
            $thisForm.removeClass('processing');
            $thisForm.find('.fami-wccp-search-results').html(response['html']);
            
        });
        return false;
    });
    
    $(document).on('change', 'input[name="fami_wccp_search_product"]', function (e) {
        var $this = $(this);
        var $thisForm = $this.closest('form');
        $thisForm.trigger('submit');
        return false;
    });
    
    // Close popup
    $(document).on('click', '.fami-wccp-close-popup', function (e) {
        e.preventDefault();
        $('body').removeClass('fami-wccp-show-popup');
        return false;
    });
    
    $(document).on('click', '.fami-wccp-popup', function (e) {
        if (!$(e.target).is('.fami-wccp-popup-inner') && !$(e.target).closest('.fami-wccp-popup-inner').length) {
            $('body').removeClass('fami-wccp-show-popup');
        }
    });
    
    // Close compare panel
    $(document).on('click', '.fami-wccp-products-list .fami-wccp-close', function (e) {
        e.preventDefault();
        $('body').removeClass('fami-wccp-show-products-list');
        return false;
    });
    
    $(document).on('click', '.fami-wccp-show-products-list', function (e) {
        console.log('ok nha aaa');
        var $comparePanel = $('.fami-wccp-products-list-wrap');
        if (!$comparePanel.is(e.target) && $comparePanel.has(e.target).length === 0) {
            $('body').removeClass('fami-wccp-show-products-list');
        }
    });
    
    // Show compare list (panel)
    function fami_wccp_show_products_list() {
        if (!$('.fami-wccp-products-list').length) {
            return;
        }
        $('body').addClass('fami-wccp-show-products-list');
    }
    
    // Compare OWL slider
    function fami_wccp_init_owl_carousel() {
        $('.fami-wccp-owl-slider').each(function () {
            var $thisSlider = $(this);
            var responsive = $thisSlider.data('responsive');
            
            if ($thisSlider.is('.faim-wccp-carousel-loaded')) {
                return;
            }
            
            if (typeof responsive == 'undefined' || typeof responsive == false) {
                responsive = {
                    0: {
                        items: 1
                    },
                    480: {
                        items: 1
                    },
                    600: {
                        items: 3
                    },
                    1024: {
                        items: 4
                    }
                }
            }
            
            $thisSlider.owlCarousel({
                loop: false,
                margin: 0,
                responsiveClass: true,
                nav: true,
                dots: false,
                responsive: responsive
            }).addClass('fami-wccp-carousel-loaded');
        });
    }
    
    fami_wccp_init_owl_carousel();
    
    // Compare slick slider
    $('.fami-wccp-slick-slider').each(function () {
        var $thisSlider = $(this);
        var responsive = $thisSlider.data('responsive');
        
        var slider_options = {
            accessibility: true,
            adaptiveHeight: false,
            arrows: true,
            asNavFor: null,
            prevArrow: '<button class="slick-prev" aria-label="Previous" type="button">&lt;</button>',
            nextArrow: '<button class="slick-next" aria-label="Next" type="button">&gt;</button>',
            autoplay: false,
            autoplaySpeed: 3000,
            centerMode: false,
            centerPadding: '50px',
            cssEase: 'ease',
            customPaging: function (slider, i) {
                return $('<button type="button" />').text(i + 1);
            },
            dots: false,
            dotsClass: 'slick-dots',
            draggable: true,
            easing: 'linear',
            edgeFriction: 0.35,
            fade: false,
            focusOnSelect: false,
            focusOnChange: false,
            infinite: true,
            initialSlide: 0,
            lazyLoad: 'ondemand',
            mobileFirst: false,
            pauseOnHover: true,
            pauseOnFocus: true,
            pauseOnDotsHover: false,
            respondTo: 'window',
            responsive: responsive,
            rows: 1,
            rtl: false,
            slide: '',
            slidesPerRow: 4,
            slidesToShow: 4,
            slidesToScroll: 4,
            speed: 500,
            swipe: true,
            swipeToSlide: false,
            touchMove: true,
            touchThreshold: 5,
            useCSS: true,
            useTransform: false,
            variableWidth: false,
            vertical: false,
            verticalSwiping: false,
            waitForAnimate: true,
            zIndex: 1000
        };
        
        $thisSlider.slick(slider_options);
        
    });
    
    // Re-init some important things after ajax
    $(document).ajaxComplete(function (event, xhr, settings) {
        fami_wccp_init_owl_carousel();
    });
    
});