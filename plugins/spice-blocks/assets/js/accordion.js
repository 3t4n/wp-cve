(function ($) {
    $(document).on('mouseenter', '.spice-block-img-accordian-container .accordian-tab', function () {
      $(".spice-block-img-accordian-container .accordian-tab").removeClass("active");
      $(this).addClass("active");
    });
  $(document).on('click', '.spice-gp-accordion-wrap', function () {
    this.parentNode.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }
  })
  })(jQuery); 