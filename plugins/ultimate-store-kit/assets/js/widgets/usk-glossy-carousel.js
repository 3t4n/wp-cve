(function ($, elementor) {
  ("use strict");
  var GlossyCarousel = function ($scope, $) {
    var $gridCarousel = $scope.find(".usk-glossy-carousel");
    if (!$gridCarousel.length) {
      return;
    }
    var $gridCarouselContainer = $gridCarousel.find(".swiper-carousel");
    var $settings = $gridCarousel.find('.usk-carousel').data("settings");
    const Swiper = elementorFrontend.utils.swiper;
      initSwiper();
      async function initSwiper() {
          var swiper = await new Swiper($gridCarouselContainer, $settings);
      };
  };
  jQuery(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction("frontend/element_ready/usk-glossy-carousel.default", GlossyCarousel);
  });
})(jQuery, window.elementorFrontend);