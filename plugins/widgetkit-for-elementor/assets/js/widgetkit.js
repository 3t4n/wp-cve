jQuery(document).ready(function($){
    /**
     * mailchimp elemtn form submit
     */
    $('form.wkfe-newsletter-form-element').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: wkfelocalizesettings.ajax_url,
            type: 'post',
            data: {
                action: 'wkfe_mailchimp_ajax_form_data_receiver',
				        fields: $( 'form#wkfe-mailchimp' ).serialize(),
				        security: wkfelocalizesettings.wkfe_security_nonce
            },
            beforeSend: function() {
                $('form.wkfe-newsletter-form-element div.submit').append( '<div class="loading-ring"></div>' );
            },
            success: function( response ) {
                $('.wkfe-mailchimp-wrapper #mailchimp-status').css('margin-bottom', '20px').empty();
                $('form.wkfe-newsletter-form-element div.submit div.loading-ring').remove();
                $('.wkfe-mailchimp-wrapper #mailchimp-status').append(response);
            },
            error: function(e) {
                console.log(e.responseJSON);
                $('.wkfe-mailchimp-wrapper #mailchimp-status').empty().css('margin-bottom', '0px');
                $('form.wkfe-newsletter-form-element div.submit div.loading-ring').remove();
                $('.wkfe-mailchimp-wrapper #mailchimp-status').text(e.responseJSON.data.message);
            }
        });
    });

    /**
     * portfolio filter
     */
    if($('.border').length){
        jQuery(window).load(function(){
                $portfolio_selectors = $('.border>li>a');
                $portfolio_selectors.on('click', function(){
                    var selector = $(this).attr('data-filter');
                    return false;
                });
        });
    };
    if($('.slash').length){
        jQuery(window).load(function(){
                $portfolio_selectors = $('.slash>li>a');
                $portfolio_selectors.on('click', function(){
                    var selector = $(this).attr('data-filter');
                    return false;
                });
        });
    };
    if($('.round').length){
        jQuery(window).load(function(){
                $portfolio_selectors = $('.round>li>a');
                $portfolio_selectors.on('click', function(){
                    var selector = $(this).attr('data-filter');
                    return false;
                });
        });
    };
    [1,2,3,4].forEach(function(i) {
        if($('.hover-' + i).length){
            $('.hover-'+ i).mixItUp({
            });
        }
    });

    jQuery(document).ready(function(){
      $('#hover-1 .portfolio-item').each( function() { $(this).hoverdir(); } );
    });


/* ========================================================================= */
/*  SLider height browser js
/* ========================================================================= */

    // Hero area auto height adjustment
    $('#tgx-hero-unit .carousel-inner .item') .css({'height': (($(window).height()))+'px'});
    $(window).resize(function(){
        $('#tgx-hero-unit .carousel-inner .item') .css({'height': (($(window).height()))+'px'});
    });

    if($('.tgx-project').length){
        jQuery(".tgx-project").addClass("owl-carousel").owlCarousel({
            pagination: true,
            center: true,
            margin:100,
            dots:false,
            loop:true,
            items:2,
            nav: true,
            navClass: ['owl-carousel-left','owl-carousel-right'],
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            autoHeight : true,
            autoplay:false,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1000:{
                    items:2
                }
            }
        });
    };
    /**
     * click to tweet 
     */
    $('.wkfe-click-to-tweet .wkfe-tweet').on('click', function(){
        var siteLink = window.location.href.split('?')[0];
        var tweetText = $(this).parentsUntil(".wkfe-click-to-tweet").find('.tweet-text').text().trim();
        var tweetUrl = "https://twitter.com/share?url=" + encodeURIComponent(siteLink) +  "&text=" + encodeURIComponent(tweetText);
        window.open(tweetUrl, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=100,width=720,height=500");
    });
    /**
     * search
     */
    $('.wkfe-search .wkfe-search-form-wrapper').css('display', 'block');
    $(".wkfe-search .search-click-handler").on('click', function(e){
        var parentID = $(this).parent().attr("id");
        $(this).toggleClass('active');
        $("#"+parentID).find('.wkfe-search-form-wrapper').toggleClass('active');
        if($('.wkfe-site-social .wkfe-site-social-platform-wrapper').hasClass('active')){
        $('.wkfe-site-social .wkfe-site-social-platform-wrapper').removeClass('active');
        }
        if($('.wkfe-site-social .site-social-click-handler').hasClass('active')){
        $('.wkfe-site-social .site-social-click-handler').removeClass('active');
        }
    });

    /**
     * site social
     */
    $('.wkfe-site-social .wkfe-site-social-platform-wrapper').css('display', 'block');
    $(".wkfe-site-social .site-social-click-handler").on('click', function(e){
        var parentID = $(this).parent().attr("id");
        $(this).toggleClass('active');
        $("#"+parentID).find('.wkfe-site-social-platform-wrapper').toggleClass('active');
        if($('.wkfe-search .wkfe-search-form-wrapper').hasClass('active')){
        $('.wkfe-search .wkfe-search-form-wrapper').removeClass('active');
        }
        if($('.wkfe-search .search-click-handler').hasClass('active')){
        $('.wkfe-search .search-click-handler').removeClass('active');
        }
    });
    /**
     * contact 
     */
    $('.wkfe-contact .wkfe-contact-content-wrapper').css('display' , 'block');
    $(".wkfe-contact .contact-click-handler i").on('click', function(e){
        var parentID = "#wkfe-contact-" + $(this).parent().data("handler") ;
        $(this).parent().toggleClass('active');
        $(parentID).find('.wkfe-contact-content-wrapper').toggleClass('active');
    });

// end of wrapper function  
});


// Make sure you run this code under Elementor.
jQuery( window ).on( 'elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction( 'frontend/element_ready/widgetkit-for-elementor-lottie-animation.default', function($scope,$) {


    let $animationWrapper = $scope.find('.lottie-animation-wrapper');

    if( $animationWrapper.length ) {

        let $autoplay;
        if( $animationWrapper.data('animation-play') == 'autoplay' ) {
        $autoplay = true;
        } else {
        $autoplay = false;
        }
        
        var animation = lottie.loadAnimation({
        container: $animationWrapper[0], // the dom element
        renderer: $animationWrapper.data('animation-renderer'),
        loop: $animationWrapper.data('animation-loop'),
        autoplay: $autoplay,
        path: $animationWrapper.data('animation-path'), // the animation data
        });

        // on hover
        $animationWrapper.on("mouseenter",function() {
        if( $animationWrapper.data('animation-play') == 'onhover' ) {
            animation.goToAndPlay(0);
        }
        });

        // on click
        $animationWrapper.on("click",function(){
        if( $animationWrapper.data('animation-play') == 'onclick' ) {
            animation.goToAndPlay(0);
        }
        });

        // view port based
        if( $animationWrapper.data('animation-play') == 'viewport' ) {
        function isScrolledIntoView(elem) {
            var docViewTop = $(window).scrollTop();
            var docViewBottom = docViewTop + $(window).height();

            var elemTop = $(elem).offset().top;
            var elemBottom = elemTop + $(elem).height();

            return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
        }

        if(isScrolledIntoView($animationWrapper)) {
            animation.play();
        }

        $(window).scroll(function() {    
            if(isScrolledIntoView($animationWrapper)) {
                animation.play();
            }
        });
        }
        // animation speed
        if( $animationWrapper.data('animation-speed') ) {
        animation.setSpeed(parseInt($animationWrapper.data('animation-speed')));
        }
        // animation reverse
        if( $animationWrapper.data('animation-reverse') ) {
        animation.setDirection(-1);
        }
    }
    });
} );

// Advanced Tab
(function($) {
    "use strict";
	var AdvancedTabHandler = function () {
        $('.wk-adv-tab-wrapper .wk-adv-tabs-nav li:first-child a').addClass('active');
		$('.wk-adv-tabs-nav li a').on('click',function(e) {
			e.preventDefault();
            let $parent = $(e.target).parents(".wk-adv-tab-wrapper");
            $parent.find('a').each((index, element) => {
          	    $(element).removeClass('active');
            })
            $(e.target).closest('a').addClass('active');

			// Display active tab
			let currentTab = $(e.target).closest('a').attr('href');
            $parent.find('.wk-tabs-content').hide();
			$(currentTab).show();
			return false;
		});
	}

	// Run this code under Elementor.
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/wk-advanced-tabs.default', AdvancedTabHandler); 
    });
})(jQuery);