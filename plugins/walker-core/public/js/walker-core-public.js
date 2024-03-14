(function ($) {
  "use strict";

  /**
   * All of the code for your public-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */

  let wntSlider1 = new Swiper(".wnt-featured-slider-one", {
    spaceBetween: 30,
    slidesPerView: 1,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".wnt-slider-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".wnt-slide-next",
      prevEl: ".wnt-slide-prev",
      clickable: true,
    },
  });
  let wntSliderGrid = new Swiper(".wnt-slider-with-grid", {
    spaceBetween: 30,
    slidesPerView: 1,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".wnt-slider-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".wnt-slide-next",
      prevEl: ".wnt-slide-prev",
      clickable: true,
    },
  });
  let wntSlider2 = new Swiper(".wnt-featured-slider-two", {
    spaceBetween: 30,
    slidesPerView: 1,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".wnt-slider-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".wnt-slide-next",
      prevEl: ".wnt-slide-prev",
      clickable: true,
    },
  });
  let wntSlider3 = new Swiper(".wnt-featured-slider-three", {
    spaceBetween: 30,
    slidesPerView: 1,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".wnt-slider-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".wnt-slide-next",
      prevEl: ".wnt-slide-prev",
      clickable: true,
    },
    breakpoints: {
      480: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 1,
      },
      1024: {
        slidesPerView: 2,
      },
      1180: {
        slidesPerView: 3,
      },
    },
  });
  let wntSlider4 = new Swiper(".wnt-featured-slider-four", {
    spaceBetween: 30,
    slidesPerView: 1,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".wnt-slider-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".wnt-slide-next",
      prevEl: ".wnt-slide-prev",
      clickable: true,
    },
    breakpoints: {
      480: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 1,
      },
      1024: {
        slidesPerView: 2,
      },
      1180: {
        slidesPerView: 4,
      },
    },
  });
  let wntCarousel1 = new Swiper(".wnt-carousel-one", {
    spaceBetween: 30,
    slidesPerView: 1,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".wnt-slider-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".wnt-slide-next",
      prevEl: ".wnt-slide-prev",
      clickable: true,
    },
    breakpoints: {
      480: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 1,
      },
      1024: {
        slidesPerView: 2,
      },
      1180: {
        slidesPerView: 4,
      },
    },
  });
  let wntCarousel2 = new Swiper(".wnt-carousel-two", {
    spaceBetween: 30,
    slidesPerView: 1,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".wnt-slider-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".wnt-slide-next",
      prevEl: ".wnt-slide-prev",
      clickable: true,
    },
    breakpoints: {
      480: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 1,
      },
      1024: {
        slidesPerView: 2,
      },
      1180: {
        slidesPerView: 3,
      },
    },
  });
  let wntSlider5 = new Swiper(".wnt-featured-slider-five", {
    spaceBetween: 30,
    slidesPerView: 1,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".wnt-slider-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".wnt-slide-next",
      prevEl: ".wnt-slide-prev",
      clickable: true,
    },
  });
  let wntSlider6 = new Swiper(".wnt-featured-slider-six", {
    spaceBetween: 30,
    slidesPerView: 1,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".wnt-slider-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".wnt-slide-next",
      prevEl: ".wnt-slide-prev",
      clickable: true,
    },
  });
})(jQuery);
