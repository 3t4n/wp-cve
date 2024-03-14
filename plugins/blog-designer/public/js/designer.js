jQuery(document).ready(function($) {
    if (jQuery('.masonry').length > 0) {
        setTimeout(function() {
            jQuery('.masonry').imagesLoaded(function() {
                jQuery('.masonry').masonry({
                    columnWidth: 0,
                    itemSelector: '.blog_masonry_item',
                    isResizable: true
                });
            });
        }, 500);
    }
    $(document).on('click', '.social-component .bd-social-share', function(e) {
        e.preventDefault();
        if ($(this).data('share') == 'facebook') {
            var $href = $(this).data('href');
            var $url = $(this).data('url');

            var $link = $href + '?u=' + $url;
            window.open($link, 'targetWindow', 'width=800, height=400', 'toolbar=no', 'location=0', 'status=no', 'menubar=no', 'scrollbars=yes', 'resizable=yes');
        }

        if ($(this).data('share') == 'linkedin') {
            var $href = $(this).data('href');
            var $url = $(this).data('url');

            var $link = $href + '?url=' + $url;
            window.open($link, 'targetWindow', 'width=800, height=400', 'toolbar=no', 'location=0', 'status=no', 'menubar=no', 'scrollbars=yes', 'resizable=yes');
        }

        if ($(this).data('share') == 'twitter') {
            var $href = $(this).data('href');
            var $text = $(this).data('text');
            var $url = $(this).data('url');

            var $link = $href + '?text=' + $text + '&url=' + $url;
            window.open($link, 'targetWindow', 'width=800, height=400', 'toolbar=no', 'location=0', 'status=no', 'menubar=no', 'scrollbars=yes', 'resizable=yes');
        }

        if ($(this).data('share') == 'pinterest') {
            var $href = $(this).data('href');
            var $url = $(this).data('url');
            var $media = $(this).data('media');
            var $description = $(this).data('description');

            var $link = $href + '?url=' + $url + '&media=' + $media + '&description=' + $description;
            window.open($link, 'targetWindow', 'width=800, height=400', 'toolbar=no', 'location=0', 'status=no', 'menubar=no', 'scrollbars=yes', 'resizable=yes');
        }

    });
    //For load more functionality
    jQuery(".bdp-load-more-btn").click(function() {
        var $data = jQuery(this).closest('.bdp_wrapper').find('form#bdp-load-more-hidden').serialize();
        console.log($data);
        bdp_load_more_ajax($data);
    });
    bd_get_boxy_clean_height();
    wpspw_pro_post_ticker_init();
});
jQuery(window).resize(function() {
    bd_get_boxy_clean_height();
});

function bdp_load_more_ajax($data) {
    var layout_id_class = ".bdp_wrapper";
    var paged = parseInt(jQuery(layout_id_class + ' #bdp-load-more-hidden #paged').val());
    var this_year = jQuery(layout_id_class + ' #bdp-load-more-hidden #this_year').val();
    var $timeline_year = jQuery(layout_id_class + ' #bdp-load-more-hidden #timeline_previous_year').val();
    paged = paged + 1;
    var max_num_pages = parseInt(jQuery(layout_id_class + ' #bdp-load-more-hidden #max_num_pages').val());
    jQuery(layout_id_class + ' .bdp-load-more-btn').addClass('loading');
    jQuery(layout_id_class + ' .bdp-load-more-btn').fadeOut();

    if (paged <= max_num_pages) {
        jQuery(layout_id_class + ' .loading-image').fadeIn();
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: 'action=get_loadmore_blog&' + $data,
            cache: false,
            success: function(response) {
                console.log(response);
                var jsmasonry = jQuery(layout_id_class + " .bdp-load-more-pre").find("div");

                // loop through each item to check when it animates

                if (jsmasonry.hasClass('timeline_bg_wrap')) {
                    jQuery(layout_id_class + ' div.timeline_back').append(response);
                    var only_year = jQuery(layout_id_class + ' div.timeline_back').find('.timeline_year .only_year');
                    jQuery(only_year).each(function() {
                        $timeline_year = jQuery(this).text();
                    });
                    jQuery(layout_id_class + ' #bdp-load-more-hidden #timeline_previous_year').val(jQuery.trim($timeline_year));
                } else if (jsmasonry.hasClass('glossary_cover')) {
                    jQuery(layout_id_class + ' div.glossary_cover .bdp_glossary').append(response);
                    jQuery('.masonry').imagesLoaded(function() {
                        jQuery('.masonry').masonry('reloadItems');
                        jQuery('.masonry').masonry('layout');
                    })
                } else if (jsmasonry.hasClass('boxy-clean')) {
                    jQuery(layout_id_class + ' .blog_template.boxy-clean > ul').append(response);
                    bd_get_boxy_clean_height();
                } else if (jsmasonry.hasClass('media-grid')) {
                    jQuery(layout_id_class + ' .media-grid-wrapper').append(response);
                } else if (jsmasonry.hasClass('blog-grid-box')) {
                    jQuery(layout_id_class + ' .blog_template.blog-grid-box').append(response);
                } else {
                    jQuery(layout_id_class + ' div.bdp-load-more-pre').append(response);
                }

                jQuery(layout_id_class + ' .bdp-load-more-btn').removeClass('loading');
                jQuery(layout_id_class + ' .loading-image').fadeOut();
                jQuery(layout_id_class + ' .bdp-load-more-btn').fadeIn();
                jQuery(layout_id_class + ' #bdp-load-more-hidden #paged').val(paged);
                jQuery(layout_id_class + ' .edd-no-js').hide();
                if (paged == max_num_pages)
                    jQuery(layout_id_class + ' .bdp-load-more-btn').fadeOut();
            }
        });
    }
    return false;
}

function bd_get_boxy_clean_height() {
    var divs = jQuery(".boxy-clean li.blog_wrap").not('.first_post');
    if (jQuery(window).width() > 980) {
        var column = 4;
        if (divs.hasClass('three_column')) {
            column = 3;
        } else if (divs.hasClass('two_column')) {
            column = 2;
        } else if (divs.hasClass('one_column')) {
            column = 1;
        }
    } else if (jQuery(window).width() <= 980 && jQuery(window).width() > 720) {
        var column = 4;
        if (divs.hasClass('three_column_ipad')) {
            column = 3;
        } else if (divs.hasClass('two_column_ipad')) {
            column = 2;
        } else if (divs.hasClass('one_column_ipad')) {
            column = 1;
        }
    } else if (jQuery(window).width() <= 720 && jQuery(window).width() > 480) {
        var column = 4;
        if (divs.hasClass('three_column_tablet')) {
            column = 3;
        } else if (divs.hasClass('two_column_tablet')) {
            column = 2;
        } else if (divs.hasClass('one_column_tablet')) {
            column = 1;
        }
    } else if (jQuery(window).width() <= 480) {
        var column = 4;
        if (divs.hasClass('one_column_mobile')) {
            column = 3;
        } else if (divs.hasClass('two_column_mobile')) {
            column = 2;
        } else if (divs.hasClass('three_column_mobile')) {
            column = 1;
        }
    }
    jQuery(".boxy-clean li.blog_wrap").removeAttr('style');

    for (var i = 0; i < divs.length; i += column) {
        var heights = jQuery(".boxy-clean li.blog_wrap").not('.first_post').slice(i, i + column).map(function() {
            return jQuery(this).height();
        }).get();
        var maxHeight = Math.max.apply(null, heights);
        if (screen.width > 640) {
            jQuery(".boxy-clean li.blog_wrap").not('.first_post').slice(i, i + column).css('height', maxHeight + 30);
        }
    }
}

function wpspw_pro_post_ticker_init() {

    jQuery(".blog-ticker-wrapper").each(function() {
        var bdsc = {
            "is_rtl": "0",
            "no_post_msg": "Sorry, No more post to display."
        };
        var s = jQuery(this).attr("id"),
            t = JSON.parse(jQuery(this).attr("data-conf"));
        void 0 !== s && "" != s && "undefined" != t && ("italic" == t.font_style && jQuery(this).addClass("blog-ticker-italic"), "bold" == t.font_style && jQuery(this).addClass("bd-bold"), "bold-italic" == t.font_style && jQuery(this).addClass("bd-bold blog-ticker-italic"), jQuery("#" + s).breakingNews({ direction: 1 == bdsc.is_rtl ? "rtl" : "ltr", effect: t.ticker_effect, delayTimer: parseInt(t.speed), scrollSpeed: parseInt(t.scroll_speed), borderWidth: "false" == t.border ? "0px" : "2px", radius: "2px", play: "false" != t.autoplay, stopOnHover: !0 }))
    })
}

jQuery(document).ready(function() {
    var icon_element = jQuery('.slides.design2 .mauthor .author');
    var date_element = jQuery('.slides.design2 .post-date .mdate');
    icon_element.each(function() {
        if (jQuery(this).find('i')) {
            jQuery(this).find('i').remove();
            jQuery(this).prepend('By');
        }
    });
    date_element.each(function() {
        if (jQuery(this).find('i')) {
            jQuery(this).find('i').remove();
            jQuery(this).prepend('/');
        }
    })
});
jQuery(document).ready(function() {
    var element = jQuery('.blog_template.blog-carousel');
    if (element) {
        element.parent().addClass('blog-carousel');
    }
    var element_grid_box = jQuery('.blog_template.blog-grid-box');
    var parent_element = jQuery('.bdp_wrapper');
    if (element_grid_box.length > 0) {
        parent_element.addClass('blog-grid-box');
    }
    jQuery('.blog_template.blog-carousel .slides li').each(function() {
        if (jQuery(this).find('.bdp-post-image a').length == 0) {
            jQuery(this).find('.blog_header').css('margin-top', '0px');
        }
    });
    
    if( jQuery(window).width() < 481 ) {
        jQuery('.blog_template.boxy-clean .blog_wrap.bdp_blog_template').each(function() {
            if (jQuery(this).find('.post-media img').length == 0) {
                jQuery(this).find('.author').hide();
            }
        });
    }
});