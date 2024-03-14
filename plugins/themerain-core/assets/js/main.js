const themerainCore = {
  init: function() {
    this.blockSliderInit();
    this.fancyboxInit();
  },

  blockSlider: function(slider) {
    const data = {
      columns: slider.dataset.columns,
      space: slider.dataset.space,
      autoplay: slider.dataset.autoplay,
      loop: slider.dataset.loop,
      center: slider.dataset.center
    }

    const params = {
      slidesPerView: 1,
      centeredSlides: true,
      grabCursor: true,
      autoplay: true,
      loop: true,
      keyboard: {
        enabled: true
      },
      breakpoints: {
        480: {
          slidesPerView: data.columns,
          spaceBetween: data.space
        }
      }
    }

    if (!eval(data.autoplay)) {
      delete params.autoplay;
    }

    if (!eval(data.loop)) {
      delete params.loop;
    }

    if (!eval(data.center)) {
      delete params.centeredSlides;
    }

    new Swiper(slider, params);
  },

  blockSliderInit: function() {
    const sliders = document.querySelectorAll('.themerain-block-slider .swiper-container');
    sliders.forEach(slider => this.blockSlider(slider));
  },

  fancyboxInit: function() {
    const elements = document.querySelectorAll('.entry-content a[href$=".jpg"], .entry-content a[href$=".jpeg"], .entry-content a[href$=".png"], .entry-content a[href$=".gif"], .entry-content a[href$=".mp4"], .entry-content a[href*="youtube"], .entry-content a[href*="vimeo"]');

    elements.forEach(e => {
      e.setAttribute('data-fancybox', 'gallery');
      e.setAttribute('data-no-swup', '');
    });

    Fancybox.assign('[data-fancybox]', {
      closeButton: 'top',
      Thumbs: false,
      Hash: false
    });
  }
};

themerainCore.init();

// Temporary fix for older themes
if (typeof jQuery !== 'undefined') {
  jQuery.fn.themerainBlockSlider = function() {
    themerainCore.blockSliderInit();
  }
}
