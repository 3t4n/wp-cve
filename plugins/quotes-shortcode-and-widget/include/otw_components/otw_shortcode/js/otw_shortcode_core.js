'use strict';

function otw_shortcode_tabs(selectors) {

    for (var cS = 0; cS < selectors.size(); cS++) {

        var selector = jQuery(selectors[cS]);

        var links = selector.find('ul.ui-tabs-nav>li a');

        var active_tab = 0;

        for (var cA = 0; cA < links.length; cA++) {

            if (jQuery(links[cA]).parent().hasClass('ui-tabs-active ui-state-active')) {

                active_tab = cA;
                break;
            }
        }
        for (var cA = 0; cA < links.length; cA++) {

            if (active_tab == cA) {
                jQuery(links[cA]).parent().addClass('ui-tabs-active ui-state-active');
                selector.find(jQuery(links[cA]).attr('href')).show();
            } else {
                jQuery(links[cA]).parent().removeClass('ui-tabs-active ui-state-active');
                selector.find(jQuery(links[cA]).attr('href')).hide();
            }
            ;
        }
        ;
        selector.find('ul.ui-tabs-nav>li a').on( 'click', function (event) {
            event.preventDefault();
            jQuery(this).parents('li').siblings().removeClass("ui-tabs-active ui-state-active");
            jQuery(this).parents('li').addClass("ui-tabs-active ui-state-active");
            var tab = jQuery(this).attr("href");
            jQuery(this).parents('li').parent().parent().children(".ui-widget-content").not(tab).hide();
            jQuery(this).parents('li').parent().parent().children(tab).show();
        });
    };
    
	var preselected = window.location.hash;
	if( preselected.length ){
		
		preselected = preselected.replace( /^#/, '' );
		
		var tab_links = preselected.split( '&' );
		
		for( var cT = 0; cT < tab_links.length; cT++ ){
			
			var link = jQuery( '.otw-sc-tabs [href=#' + tab_links[ cT ] + ']' );
			
			if( link.length ){
				link.click();
			};
		};
		setTimeout( function(){
			var sc_el = jQuery( '#' + tab_links[ cT - 1 ] );
			jQuery('html, body').animate({ scrollTop: sc_el.offset().top - 50 }, 500);
		}, 500 );
	};
}
;
function otw_shortcode_content_toggle(selector, closed) {

    selector.off('click');
    selector.on( 'click', function () {
        jQuery(this).toggleClass('closed').next('.toggle-content').slideToggle(350);
    });
    closed.next('.toggle-content').hide();
}
;

function otw_shortcode_accordions(accordions) {


    for (var cA = 0; cA < accordions.size(); cA++) {

        var headers = jQuery(accordions[ cA ]).find('h3.accordion-title');
        var contents = jQuery(accordions[ cA ]).find('.ui-accordion-content');

        var has_open = false;

        for (var cH = 0; cH < headers.size(); cH++) {

            if (jQuery(headers[cH]).hasClass('closed') || has_open) {
                jQuery(contents[cH]).hide();
            } else {
                has_open = true;
                jQuery(headers[cH]).addClass('ui-accordion-header-active ui-state-active');
            }
            ;
        }
        ;

        headers.off('click');
        headers.on( 'click', function () {

            jQuery(this).parent().find('h3.accordion-title').not(jQuery(this)).removeClass('ui-accordion-header-active ui-state-active');
            jQuery(this).parent().find('.ui-accordion-content').not(jQuery(this).next()).slideUp();
            jQuery(this).next().slideToggle();
            jQuery(this).toggleClass('ui-accordion-header-active ui-state-active');
        });
    }
    ;
}
;

function otw_shortcode_faq(faqs) {

    faqs.find('dl > dt').off('click');
    faqs.find('dl > dt').on( 'click', function () {
        jQuery(this).toggleClass('open-faq').next().slideToggle(350);
    });
}
;

function otw_shortcode_shadow_overlay(selectors) {

    selectors.on( 'hover', function () {
        jQuery(this).css({boxShadow: '0 0 20px 0 rgba(0,0,0,0.7) inset'});
    }, function () {
        jQuery(this).css({boxShadow: '0 0 0 0'});
    });
}
;
function otw_shortcode_testimonials(selectors) {

    selectors.find(".testimonials-prev").on( 'click', function () {
        selectors.find(".testimonials-slide.active").hide().toggleClass('active').otwPrevOrLast().animate({"opacity": "toggle"}).toggleClass('active');
    });
    selectors.find(".testimonials-next").on( 'click', function () {
        selectors.find(".testimonials-slide.active").hide().toggleClass('active').otwNextOrFirst().animate({"opacity": "toggle"}).toggleClass('active');
    });
}
;

function otw_shortcode_scroll_to_top(selectors) {

    selectors.on( 'click', function () {
        jQuery('html, body').animate({scrollTop: '0px'}, 700);
        return false;
    });
};

function otw_shortcode_sortable_table(selectors) {

	selectors.each( function(){
	
		if( ( typeof( this.sortable_inited ) == undefined ) || !this.sortable_inited ){
		
			jQuery( this ).footable({
				breakpoints: {
					phone: 480,
					tablet: 767
				}
			});
			this.sortable_inited = 1;
		}
	} );
};

function otw_shortcode_count_down(selector) {
    var time = selector.data('time');
    var newDate = new Date(time);
    selector.otw_b_countdown({until: newDate});

}

jQuery.fn.otwNextOrFirst = function (selector) {
    var next = this.next(selector);
    return (next.length) ? next : this.prevAll(selector).last();
};

jQuery.fn.otwPrevOrLast = function (selector) {
    var prev = this.prev(selector);
    return (prev.length) ? prev : this.nextAll(selector).last();
};


//function animate progress bar 
function animate_progressbar(selector){
	if( ( typeof( selector[0].inited ) == undefined ) || !selector[0].inited ){
		selector.data("origWidth", selector.width()).width(0).animate({
			width: selector.data("origWidth")
		}, 1200);
		selector[0].inited = true;
	}
}
function otw_start_animated_image(container) {
	if( container.length ){
		container.delay(1000).waypoint(function () {
			jQuery(this.element).find('.otw-b-animate-in').addClass('otw-b-animation-start');
		}, {offset: 350});
	};
}

/**
 * make client slider caroucel
 * @param {DOM} container
 * @returns {void}
 */
function otw_start_client_caroucel(container) {
    var nav = jQuery(container).data('nav');
    var autoloop = jQuery(container).data('autoloop');
    jQuery(container).owlCarousel({
        loop: true,
        margin: 10,
        nav: nav,
        autoplay: autoloop,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        navText: ['<i class="general foundicon-left-arrow"></i>', '<i class="general foundicon-right-arrow"></i>'],
        navElement: 'div',
        responsiveBaseElement: jQuery(container),
        responsive: {
            0: {
                items: 1
            },
            150: {
                items: 2
            },
            320: {
                items: 3
            },
            480: {
                items: 3
            },
            768: {
                items: 4
            },
            1000: {
                items: 5
            }
        }
    });
}

function otw_start_client_caroucel_preview(container) {
    var nav = jQuery(container).data('nav');
    var autoloop = jQuery(container).data('autoloop');

    jQuery(container).owlCarousel({
        loop: false,
        items: 1,
        margin: 10,
        nav: nav,
        autoplay: true,
        navText: ['<i class="general foundicon-left-arrow"></i>', '<i class="general foundicon-right-arrow"></i>'],
    });
}
/**
 * Init the slider for testimonials
 * @param {DOM} container
 * @returns {DOM}
 */
function otw_testimonials_start(container) {
    var nav = jQuery(container).data('nav');
    var autoloop = jQuery(container).data('autoloop');
    container.owlCarousel({
        loop: true,
        margin: 10,
        autoplay: autoloop,
        autoplayTimeout: 2000,
        smartSpeed: 1000,
        items: 1,
        navElement: 'div',
        nav: nav,
        navText: ['<i class="general foundicon-left-arrow"></i>', '<i class="general foundicon-right-arrow"></i>'],
    });
    return container;
}


/**
 * Generate gallety
 * @param {type} e
 * @returns {undefined} void
 */
function generateGallery(e) {
    var g = '.otw-b-gallery';
    var a_c = 'otw-b-active';
    var t = '.otw-b-gallery-thumbs';
    var c = '.otw-b-gallery-content-inner.otw-lightbox';
    var c_p = '.otw-b-gallery-content';
    var f = jQuery('.otw-b-gallery-thumbs li:first-child');

    var object = null;
    if (e === false || e === 'undefined') {
        object = f;
    }
    else {
        object = e;
    }

    var src_m = jQuery('a', object).attr('href');
    var src_l = jQuery('a', object).attr('data-href');

    jQuery(object).siblings().removeClass(a_c);
    jQuery(object).addClass(a_c);
    jQuery(object).parent().siblings(c_p).find('img').hide().attr('src', src_m).fadeIn(200);
    jQuery(object).parent().siblings(c_p).children(c).attr('href', src_l);
    jQuery(c).nivoLightbox();
}

function otw_shortcode_category_filter( filter_selector, content_selector ){
	
	// Clone portfolio items to get a second collection for Quicksand plugin
	var portfolioClone = jQuery( content_selector ).clone();
	
	// Attempt to call Quicksand on every click event handler
	jQuery( filter_selector + " a").on( 'click', function(e){
		
		jQuery( filter_selector + " li").removeClass("current");
		
		// Get the class attribute value of the clicked link
		var filterClass = jQuery(this).parent().attr("class");
		
		if ( filterClass == "all" ) {
			var filteredPortfolio = portfolioClone.find("li");
		} else {
			var filteredPortfolio = portfolioClone.find("li[data-type~=" + filterClass + "]");
		}
		
		// Call quicksand
		jQuery( content_selector ).quicksand( filteredPortfolio, {
			duration: 500
		});
		
		jQuery(this).parent().addClass("current");
		
		// Prevent the browser jump to the link anchor
		e.preventDefault();
	})
}
