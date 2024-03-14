(function($) {
    
    "use strict";
var swiperOneCol = new Swiper(".wps_slide_el_bloc_one", {
    preloadImages: false,
    loop: true,
    grabCursor: true,
    centeredSlides: false,
    resistance: true,
    resistanceRatio: 0.6,
    speed: 2400,
    spaceBetween: 0,
    parallax: false,
    effect: "slide",
    autoplay: {
        delay: 8000,
        disableOnInteraction: false
    },
});
var swiperTwoCol = new Swiper(".wps_slide_el_bloc_two", {
    preloadImages: false,
    loop: true,
    grabCursor: true,
    centeredSlides: false,
    resistance: true,
    resistanceRatio: 0.6,
    slidesPerView: 2,
    speed: 1400,
    spaceBetween: 0,
    parallax: false,
    effect: "slide",
    active: 'active',
    autoplay: {
        delay: 8000,
        disableOnInteraction: false
    },
    breakpoints: {
        767: {
          slidesPerView: 2,
        },
        640: {
          slidesPerView: 1,
        }, 
    }
});
var swiperThreeCol = new Swiper(".wps_slide_el_bloc_three", {
    preloadImages: false,
    loop: true,
    grabCursor: true,
    centeredSlides: false,
    resistance: true,
    resistanceRatio: 0.6,
    slidesPerView: 3,
    speed: 1400,
    spaceBetween: 0,
    parallax: false,
    effect: "slide",
    active: 'active',
    autoplay: {
        delay: 8000,
        disableOnInteraction: false
    },
    breakpoints: {
        1199: {
          slidesPerView: 3,
        },
        991: {
          slidesPerView: 3,
        },
        640: {
          slidesPerView: 1,
        }, 
    }
});
var swiperFourCol = new Swiper(".wps_slide_el_bloc_four", {
    preloadImages: false,
    loop: true,
    grabCursor: true,
    centeredSlides: false,
    resistance: true,
    resistanceRatio: 0.6,
    slidesPerView: 4,
    speed: 1400,
    spaceBetween: 0,
    parallax: false,
    effect: "slide",
    active: 'active',
    autoplay: {
        delay: 8000,
        disableOnInteraction: false
    },
    breakpoints: {
        1199: {
          slidesPerView: 4,
        },
        991: {
          slidesPerView: 4,
        },
        767: {
          slidesPerView: 2,
        }, 
        575: {
          slidesPerView: 1,
        }, 
    }
});
var swiperFiveCol = new Swiper(".wps_slide_el_bloc_five", {
    preloadImages: false,
    loop: true,
    grabCursor: true,
    centeredSlides: false,
    resistance: true,
    resistanceRatio: 0.6,
    slidesPerView: 5,
    speed: 1400,
    spaceBetween: 30,
    parallax: false,
    effect: "slide",
    active: 'active',
    autoplay: {
        delay: 8000,
        disableOnInteraction: false
    },
    breakpoints: {
        1199: {
          slidesPerView: 5,
        },
        991: {
          slidesPerView: 5,
        },
        767: {
          slidesPerView: 2,
        }, 
        575: {
          slidesPerView: 1,
        }, 
    }
});
var swiperSixCol = new Swiper(".wps_slide_el_bloc_six", {
    preloadImages: false,
    loop: true,
    grabCursor: true,
    centeredSlides: false,
    resistance: true,
    resistanceRatio: 0.6,
    slidesPerView: 6,
    speed: 1400,
    spaceBetween: 30,
    parallax: false,
    effect: "slide",
    active: 'active',
    autoplay: {
        delay: 8000,
        disableOnInteraction: false
    },
    breakpoints: {
        1199: {
          slidesPerView: 6,
        },
        991: {
          slidesPerView: 6,
        },
        767: {
          slidesPerView: 2,
        }, 
        575: {
          slidesPerView: 1,
        }, 
    }
});

})(window.jQuery);