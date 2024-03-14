/**
 * Elementor live preview handler
 * 
 */
jQuery(document).ready(function($) {
    if( window.elementorFrontend ) {
        if( typeof( elementorFrontend.hooks ) != 'undefined' ) {
            var ajaxUrl = wpmagazineModulesElementorObject.ajax_url;
            var _wpnonce = wpmagazineModulesElementorObject._wpnonce;
            /**
             * Ticker elementor preview
             * 
             */
            elementorFrontend.hooks.addAction( 'frontend/element_ready/ticker.default', function( $scope, $ ) {
                var newID = $scope.find( ".cvmm-ticker-content" );
                var tickerDuration = newID.data( "duration" );
                var tickerDirection = newID.data( "direction" );
                var tickerStart = newID.data( "start" );
                var tickerpauseonHover = newID.data( "pauseonhover" );
                newID.marquee({
                    allowCss3Support: true,
                    delayBeforeStart:tickerStart,
                    duration : tickerDuration,
                    pauseOnHover : tickerpauseonHover,
                    direction: tickerDirection,
                    startVisible: true,
                    gap: 6,
                    duplicated: true
                });
            });

            /**
             * Slider elementor preview
             * 
             */
            elementorFrontend.hooks.addAction( 'frontend/element_ready/post-slider.default', function( $scope, $ ) {
                var newID = $scope.find( ".cvmm-slider-post-wrapper" );
                var blockSliderdots = newID.data( "dots" );
                var blockSliderloop = newID.data( "loop" );
                var blockSlidercontrol = newID.data( "control" );
                var blockSliderauto = newID.data( "auto" );
                var blockSlidertype = newID.data( "type" );
                var blockSliderspeed = newID.data( "speed" );
                var blockSliderautoplayspeed = newID.data( "autoplayspeed" );
                newID.slick({
                    dots: ( blockSliderdots == '1' ),
                    arrows: ( blockSlidercontrol == '1' ),
                    infinite: ( blockSliderloop == '1' ),
                    autoplay: ( blockSliderauto == '1' ),
                    fade: ( blockSlidertype == '1' ),
                    speed: blockSliderspeed,
                    autoplaySpeed: blockSliderautoplayspeed,
                    prevArrow: '<span class="slickArrow prev-icon"><i class="fas fa-chevron-left"></i></span>',
                    nextArrow: '<span class="slickArrow next-icon"><i class="fas fa-chevron-right"></i></span>',
                });
            });

            /**
             * Tiles elementor preview
             * 
             */
            elementorFrontend.hooks.addAction( 'frontend/element_ready/post-tiles.default', function( $scope, $ ) {
                var newID = $scope.find( ".cvmm-post-tiles-slider-post-wrapper" );
                var blockSliderdots = newID.data( "dots" );
                var blockSliderloop = newID.data( "loop" );
                var blockSlidercontrol = newID.data( "control" );
                var blockSliderauto = newID.data( "auto" );
                var blockSlidertype = newID.data( "type" );
                var blockSliderspeed = newID.data( "speed" );
                var blockSliderItems = newID.data( "item" );
                newID.slick({
                    dots: ( blockSliderdots == '1' ),
                    arrows: ( blockSlidercontrol == '1' ),
                    infinite: ( blockSliderloop == '1' ),
                    autoplay: ( blockSliderauto == '1' ),
                    slidesToShow: blockSliderItems,
                    fade: ( blockSlidertype == '1' ),
                    speed: blockSliderspeed,
                    margin:20
                });
            })

            /**
             * Post Carousel elementor preview
             * 
             */
            elementorFrontend.hooks.addAction( 'frontend/element_ready/post-carousel.default', function( $scope, $ ) {
                var newID = $scope.find( ".cvmm-post-carousel-wrapper" );
                var blockpostCarouseldots = newID.data( "dots" );
                var blockpostCarouselloop = newID.data( "loop" );
                var blockpostCarouselcontrol = newID.data( "control" );
                var blockpostCarouselauto = newID.data( "auto" );
                var blockpostCarouseltype = newID.data( "type" );
                var blockpostCarouselspeed = newID.data( "speed" );
                var blockpostCarouselColumn = newID.data( "column" );
                newID.slick({
                    dots: ( blockpostCarouseldots == '1' ),
                    arrows: ( blockpostCarouselcontrol == '1' ),
                    infinite: ( blockpostCarouselloop == '1' ),
                    autoplay: ( blockpostCarouselauto == '1' ),
                    fade: ( blockpostCarouseltype == '1' ),
                    speed: blockpostCarouselspeed,
                    slidesToShow: blockpostCarouselColumn,
                    responsive: [
                        {
                            breakpoint:991,
                            settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                            }
                        }
                    ],
                    prevArrow: '<span class="slickArrow prev-icon"><i class="fas fa-chevron-left"></i></span>',
                    nextArrow: '<span class="slickArrow next-icon"><i class="fas fa-chevron-right"></i></span>',
                });
            })


            /**
             * Post Carousel elementor preview
             * 
             */
            elementorFrontend.hooks.addAction( 'frontend/element_ready/post-filter.default', function( $scope, $ ) {
                var activePostsButton;
                activePostsButton = $scope.find( ".cvmm-title-posts-main-wrapper .cvmm-post-filter-cat-title-wrapper ul li" );
                activePostsButton.on( 'click', function() {
                    var _this = $( this ), postContainer = _this.parents( ".cvmm-post-filter-cat-title-wrapper" ).next( ".cvmm-post-wrapper" );
                    var term_id = _this.data( "id" );
                    var ajaxAction = 'wpmagazine_modules_lite_post_filter_load_new_posts';
                    var attributes = JSON.parse( _this.siblings( 'input[name="wpmagazine_modules_lite_post_filter_attrs"]' ).val() );
                    _this.addClass( 'active' );
                    _this.siblings().removeClass( 'active' );
                    /**
                     * Get ajax posts response
                     * 
                     */
                    $.ajax({
                        method: 'POST',
                        url: ajaxUrl,
                        data: {
                            'action': ajaxAction,
                            '_wpnonce': _wpnonce,
                            'term_id': term_id,
                            'attributes': attributes
                        },
                        beforeSend: function() {
                            postContainer.addClass( 'retrieving-posts' );
                        },
                        success: function(response) {
                            postContainer.removeClass( 'retrieving-posts' );
                            postContainer.html( response );
                        }
                    })
                });
            })

            /**
             * Post Carousel elementor preview
             * 
             */
            elementorFrontend.hooks.addAction( 'frontend/element_ready/post-masonry.default', function( $scope, $ ) {
                var masonryContainer;
                masonryContainer = $scope.find( ".cvmm-post-wrapper" );
                masonryContainer.imagesLoaded( function() {
                    masonryContainer.masonry();
                })
            })
        }
    } // Ends window.elementorFrontend
});