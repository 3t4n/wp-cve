(function($) {
  'use strict';

  var hover_timeout = null;

  // click
  $(document).on('click', '.wpcsi-trigger-click', function() {
    let $this = $(this), $parent = $this.closest('.wpcsi-shoppable-image');

    if ($this.hasClass('active')) {
      $this.removeClass('active');
    } else {
      $parent.find('.wpcsi-tag').removeClass('active');
      $this.addClass('active');
    }
  });

  // hover
  $('.wpcsi-trigger-hover').hover(function() {
    // hover in
    var $this = $(this);

    if (hover_timeout != null) clearTimeout(hover_timeout);

    $this.closest('.wpcsi-shoppable-image').
        find('.wpcsi-tag').
        removeClass('active');

    if ($this.hasClass('wpcsi-tag')) {
      $this.addClass('active');
    }

    if ($this.hasClass('wpcsi-popup')) {
      $this.prev().addClass('active');
    }
  }, function() {
    // hover out
    var $this = $(this);

    hover_timeout = setTimeout(function() {
      if ($this.hasClass('wpcsi-tag')) {
        $this.removeClass('active');
      }

      if ($this.hasClass('wpcsi-popup')) {
        $this.prev().removeClass('active');
      }
    }, 300);
  });

  // click outside
  $(document).on('click', '.wpcsi-shoppable-image', function(e) {
    var $this = $(this);

    if ($(e.target).closest('.wpcsi-tag').length === 0 &&
        $(e.target).closest('.wpcsi-popup').length === 0) {
      $this.find('.wpcsi-tag:not(.wpcsi-trigger-initial)').
          removeClass('active');
    }
  });

  $('.wpcsi-product-list.style-carousel').each(function() {
    if ($(this).find('li').length > 1) {
      $(this).slick({
        infinite: true, slidesToShow: 1, slidesToScroll: 1, dots: true,
      });
    }
  });
})(jQuery);
