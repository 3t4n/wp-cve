( function( $ ) {
	
	/**
 	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */ 
	var WidgetHelloWorldHandler = function( $scope, $ ) {
		console.log( $scope );
	};
	
	// Make sure you run this code under Elementor.
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hello-world.default', WidgetHelloWorldHandler );
	} );

	/* ===============================  YouTubePopUp  =============================== */

	$(document).ready(function() {
    	$("a.vid").YouTubePopUp();
	});

    /* ===============================  parallaxie  =============================== */

	$(document).ready(function() {
		$('.parallaxie').parallaxie({
			speed: 0.2,
			size: "cover"
		});
	});
	
	// === window When Loading === //

	$(window).on("load", function () {

		/* ===============================  thumparallax  =============================== */

		var imageUp = document.getElementsByClassName('thumparallax');
        if(imageUp.length != 0){
            new simpleParallax(imageUp, {
                delay: 1,
                scale: 1.1
            });
        }

		var imageDown = document.getElementsByClassName('thumparallax-down');
        if(imageDown.length != 0){
            new simpleParallax(imageDown, {
                orientation: 'down',
                delay: 1,
                scale: 1.1
            });
        }

	});

	/* ===============================  fixed-slider  =============================== */

	$(function () {

		"use strict";

		var slidHeight = $(".fixed-slider").outerHeight();

		$(".main-content").css({
			marginTop: slidHeight
		});

	});

	/* ===============================  fade slideshow  =============================== */

	var scrolled = $(this).scrollTop();
	$('.fixed-slider .caption , .fixed-slider .capt .parlx').css({
		'transform': 'translate3d(0, ' + -(scrolled * 0.20) + 'px, 0)',
		'opacity': 1 - scrolled / 600
	});

	/* ===============================  Wow Animation  =============================== */

	$(window).on('load', function() {
		wow = new WOW({
			animateClass: 'animated',
			offset: 100
		});
		wow.init();
	});

    var active = false;

    $('.better-list-sider .bea-related-dropdown').on('click', function () {
        active = !active;

        $('.better-list-sider').toggleClass("active");
	});

    /* ===============================  Var Background image  =============================== */

    var pageSection = $(".better-bg-img, section");
    pageSection.each(function (indx) {

        if ($(this).attr("data-background")) {
            $(this).css("background-image", "url(" + $(this).data("background") + ")");
        }
    });

	function betterAbout($scope, $) {

		/* ===============================  Var Background image  =============================== */

		var pageSection = $(".better-bg-img, section");
		pageSection.each(function (indx) {

			if ($(this).attr("data-background")) {
				$(this).css("background-image", "url(" + $(this).data("background") + ")");
			}
		});

	}
	
	jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/better-about.default', betterAbout);
	});

	function betterServices($scope, $) {
		
		var servCarsouel = new Swiper('.better-services.style-3 .swiper-container', {
			spaceBetween: 0,
			speed: 1000,
			loop: true,

			breakpoints: {
				320: {
					slidesPerView: 1,
					spaceBetween: 0
				},
				767: {
					slidesPerView: 2,
					spaceBetween: 0
				},
				991: {
					slidesPerView: 3,
					spaceBetween: 0
				},
				1024: {
					slidesPerView: 4,
					spaceBetween: 0
				}
			},

			pagination: {
				el: '.better-services.style-3 .swiper-pagination',
			},

			navigation: {
				nextEl: '.better-services.style-3 .swiper-button-next',
				prevEl: '.better-services.style-3 .swiper-button-prev'
			},
		});

	}
	
	jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/better-services.default', betterServices);
	});

	/* ===============================  tooltip  =============================== */

    $('[data-tooltip-tit]').hover(function () {
        $('<div class="better-image-tooltip-tit"></div>').text($(this).attr('data-tooltip-tit')).appendTo('body').fadeIn('slow');
    }, function () {
        $('.better-image-tooltip-tit').remove();
    }).mousemove(function (e) {
        $('.better-image-tooltip-tit').css({ top: e.pageY + 10, left: e.pageX + 20 })
    });
    $('[data-tooltip-sub]').hover(function () {
        $('<div class="better-image-tooltip-sub"></div>').text($(this).attr('data-tooltip-sub')).appendTo('body').fadeIn('slow');
    }, function () {
        $('.better-image-tooltip-sub').remove();
    }).mousemove(function (e) {
        $('.better-image-tooltip-sub').css({ top: e.pageY + 60, left: e.pageX + 20 })
    });

	function betterteam($scope, $) {

		/* ===============================  slick Carousel  =============================== */
	
		$('.better-team.style-2 .team-container').slick({
			slidesToShow: 5,
			slidesToScroll: 1,
			arrows: true,
			prevArrow: '.better-team.style-2 .prev',
			nextArrow: '.better-team.style-2 .next',
			dots: true,
			autoplay: true,
			responsive: [
				{
					breakpoint: 991,
					settings: {
						slidesToShow: 3
					}
				},
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 2
					}
				},
				{
					breakpoint: 480,
					settings: {
						slidesToShow: 1
					}
				}
			]
		});

		$('.better-team.style-5 .team-container').slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			arrows: true,
			prevArrow: '.better-team.style-5 .prev',
			nextArrow: '.better-team.style-5 .next',
			dots: false,
			autoplay: true,
			responsive: [
				{
					breakpoint: 1024,
					settings: {
						slidesToShow: 4
					}
				},
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 2
					}
				},
				{
					breakpoint: 480,
					settings: {
						slidesToShow: 1
					}
				}
			]
		});	

	}
		
	jQuery(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/team.default', betterteam);
	});

	function betterclients($scope, $) {
	
		$('.better-clients.style-1 .brands-crs').slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			arrows: false,
			dots: false,
			autoplay: true,
			responsive: [
				{
					breakpoint: 1024,
					settings: {
						slidesToShow: 4
					}
				},
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 2
					}
				},
				{
					breakpoint: 480,
					settings: {
						slidesToShow: 1
					}
				}
			]
		});

	}

	jQuery(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/better-clients.default', betterclients);
	});

	function bettervideobox($scope, $) {

		/* ===============================  YouTubePopUp  =============================== */

		$(document).ready(function() {
			$("a.vid").YouTubePopUp();
		});

	}

	jQuery(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/better-video-box.default', bettervideobox);
	});

	function bettercounter($scope, $) {

		var c4 = $('.better-counter .skill');
		var myVal = $(this).attr('data-value');

		$scope.find(".better-counter .skill").each(function () {

			$(this).circleProgress({
				startAngle: -Math.PI / 2 * 1,
				value: myVal,
				thickness: 2,
				size: 110,
				fill: { color: $(this).attr('data-color') }
			});

		});

	}

	jQuery(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/better-counter.default', bettercounter);
	});

	/* ===============================  Var Background image  =============================== */

	var pageSection = $(".better-bg-img, section");
	pageSection.each(function (indx) {

		if ($(this).attr("data-background")) {
			$(this).css("background-image", "url(" + $(this).data("background") + ")");
		}
	});

	/* ===============================  SPLITTING TEXT  =============================== */

	Splitting();
    
} )( jQuery ); 
