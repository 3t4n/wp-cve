(function($) {
    "use strict";
	
	$.fn.changeElementType = function(newType) {
        var attrs = {};
        if (!(this[0] && this[0].attributes))
            return;

        $.each(this[0].attributes, function(idx, attr) {
            attrs[attr.nodeName] = attr.nodeValue;
        });
        this.replaceWith(function() {
            return $("<" + newType + "/>", attrs).append($(this).contents());
        });
    }
    // Make sure you run this code under Elementor..
    $("#elementor-preview-iframe").on("load", function() {
		
        //for testimonial slider 
        elementorFrontend.hooks.addAction('frontend/element_ready/better-testimonial.default', function($scope) {
            $scope.find('.better-testimonial.testi-slider').each(function() {
                $(this).slick({
                    autoplay: true,
                    dots: false,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    arrows: false,
                    autoplaySpeed: 3000,
                    speed:1500,
                    fade: false,
                    pauseOnHover: false,
                    pauseOnFocus: false,
                    responsive: [{
                            breakpoint: 1199,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1,
                                infinite: true,
                                dots: false
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1,
                                infinite: true,
                                dots: false
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        },
                    ]
                });
            });

        });
        //for img-box slider
        elementorFrontend.hooks.addAction('frontend/element_ready/better-image-box-slider.default', function($scope) {
            $scope.find('.better-img-box-slider').each(function() {
                $(this).slick({
                    autoplay: true,
                    dots: true,
                    slidesToShow: 3,
                    nextArrow: '<i class="fa fa-angle-right"></i>',
                    prevArrow: '<i class="fa fa-angle-left"></i>',
                    slidesToScroll: 1,
                    arrows: true,
                    autoplaySpeed: 2000,
                    speed:1000,
                    fade: false,
                    pauseOnHover: false,
                    pauseOnFocus: false,
                });
            });

        });

        //for menu
        elementorFrontend.hooks.addAction('frontend/element_ready/better-menu.default', function($scope) {

            //remove empty href
			$scope.find(".fat-list a[href='#']").remove();
			$scope.find('.fat-list').changeElementType('ul');
			$scope.find( ".fat-list a" ).wrap( "<li></li>" );
            $scope.find(".fat-list .sub-menu").remove();
            //remove empty ul on mobile menu
            $scope.find('.fat-list ul').not(':has(li)').remove();

            $scope.find('.box-mobile').each(function() {
                $(this).find('.hamburger').on('click', function() {
                    $scope.find('.fat-nav').fadeToggle();
                    $scope.find('.fat-nav').toggleClass('active');
                    $(this).toggleClass('active');
                });
            })
            $scope.find('.menu-box ul').superfish({
                delay: 400, //delay on mouseout
                animation: {
                    opacity: 'show',
                    height: 'show'
                }, // fade-in and slide-down animation
                animationOut: {
                    opacity: 'hide',
                    height: 'hide'
                },
                speed: 200, //  animation speed
                speedOut: 200,
                autoArrows: false // disable generation of arrow mark-up
            })

        });

        //for portfolio isotope
        elementorFrontend.hooks.addAction('frontend/element_ready/better-portfolio.default', function($scope) {

            //isotope setting(portfolio)
            $scope.find('.portfolio-body').isotope();

            // filter items when filter link is clicked
            $scope.find('.port-filter a').each(function() {
                $(this).on('click', function() {
                    $scope.find('.port-filter a').removeClass("active");
                    $(this).addClass("active");

                    var selector = $(this).attr('data-filter');
                    $scope.find('.portfolio-body').isotope({
                        itemSelector: '.port-item',
                        filter: selector
                    });


                    return false;
                });
            });

        });
		
		//for portfolio masonry isotope
        elementorFrontend.hooks.addAction('frontend/element_ready/better-portfolio-masonry.default', function($scope) {

            //isotope setting(portfolio)
            $scope.find('.portfolio-body').imagesLoaded( function() {$scope.find('.portfolio-body').isotope()});;

            // filter items when filter link is clicked
            $scope.find('.port-filter a').each(function() {
                $(this).on('click', function() {
                    $scope.find('.port-filter a').removeClass("active");
                    $(this).addClass("active");

                    var selector = $(this).attr('data-filter');
                    $scope.find('.portfolio-body').isotope({
                        itemSelector: '.port-item',
                        filter: selector
                    });


                    return false;
                });
            });

        });

        elementorFrontend.hooks.addAction('frontend/element_ready/global', function($scope) {
			
			$scope.find("div[class*='elementor-widget-wp-'] h5").each(function() {
				$(this).addClass("elementor-heading-title");
			});
			
			// Video responsive
			$scope.find(".video,.audio").each(function() {
				$(this).fitVids();
			});
			
			$scope.find('.better-blog.blog-body').imagesLoaded(function() { 
			 	$scope.find('.better-blog.blog-body').isotope();
			 });

			
		});

    });

})(jQuery);