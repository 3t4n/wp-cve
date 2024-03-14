jQuery(document).ready(function ($) {
    'use strict';


    $('.gpl-post-filter ul li a').on('click', function(e){
        e.preventDefault();

            let that = $(this),
                parent = that.closest('.gpl-post-filter'),
                wrap = that.closest('.wp-block-guten-post-layout-post-grid');

                parent.find('a').removeClass('active');
                that.addClass('active');

                $.ajax({
                    url: gpl_data.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'gpl_post_filter',
                        filtertype: parent.data('filtertype'),
                        taxonomy: that.data('taxonomy'),
                        postId: parent.data('postid'),
                        blockName: parent.data('blockname'),
                        wpnonce: gpl_data.security
                    },
                    beforeSend: function() {
                        wrap.addClass('gpl-loading');
                    },
                    success: function(data) {
                        wrap.find('.gpl-all-posts').html(data);
                    },
                    complete:function() {
                        setTimeout( function () {
                            wrap.removeClass('gpl-loading');
                        }, 500);
                    },
                    error: function() {
                        wrap.removeClass('gpl-loading');
                    },
                });

    });


    var customPostsSlider = $('.gpl-slick-slider');
    var sectionNavigation   = '';
    customPostsSlider.each(function () {
        var sectionId = '#' + $(this).attr('id');
        sectionNavigation = $(sectionId).data('navigation');
        
        $(sectionId).children('.gpl-post-slider-one').not('.slick-initialized').slick({
            customPaging: function(slider, i){
                var thumb = $(slider.$slides[i]).data('thumb');
                if( sectionNavigation === 'thumbnail' ) {
                    return ('<a><img src="' + thumb + '"/></a>');
                } else {
                    return('<button>'+i+'</button>');
                }
            },
            arrows:  $(this).data('navigation') === 'dots' || $(this).data('navigation') === 'none' || $(this).data('navigation') === 'thumbnail' ? false : true,
            dots: $(this).data('navigation') === 'arrows' || $(this).data('navigation') === 'none' ? false : true,
            infinite: true,
            speed: 500,
            slidesToShow: $(this).data('count') === 1 ? 1 : $(this).data('slidesToShow'),
            slidesToScroll: $(this).data('count') === 1 ? 1 : $(this).data('slidesToShow'),
            autoplay: $(this).data('autoplay'),
            dotsClass: $(this).data('navigation') === 'thumbnail' ? "slick-dots slick-thumb" : "slick-dots",
            autoplaySpeed: 3000,
            cssEase: "linear",
            responsive: [
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]

        });
    });
});