jQuery(document).ready(function($) {
  if (popup_showed != 'showed') {
    jQuery(function() {
      jQuery.get(GETSOCIAL_ONBOARDING_PATH, function(data) {
        jQuery("#toplevel_page_wp-share-buttons-analytics-by-getsocial-init").append(data);

        viewable();
      });
    });
  }

  function isScrolledIntoView(elem) {
    var $elem = $(elem);
    var $window = $(window);

    var docViewTop = $window.scrollTop();
    var docViewBottom = docViewTop + $window.height();

    var elemTop = $elem.offset().top;
    var elemBottom = elemTop + $elem.height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
  }

  function viewable() {

    jQuery("#toplevel_page_wp-share-buttons-analytics-by-getsocial-init").each(function(i, el) {

      var el = jQuery(el);

      if (isScrolledIntoView(el)) {
        jQuery("#getsocial-onboarding-popup").removeClass("gs-menu-hidden");
      } else {
        jQuery("#getsocial-onboarding-popup").addClass("gs-menu-hidden");
      }
    });
  }

  jQuery(window).scroll(function() {
    viewable();
  });

  jQuery(window).blur(function() {
    viewable();
  });
});
