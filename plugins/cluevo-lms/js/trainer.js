jQuery(window).ready(function() {
  jQuery('.item-header-container.toggle').click(function() {
    var container = jQuery(this).parents('.trainer-student-item:first');
    var items = jQuery(container)
      .find('.item-data:first')
      .children();
    var height = 0;
    jQuery(items).each(function(i, c) {
      height += jQuery(c).outerHeight(true);
    });
    jQuery(container).toggleClass('collapsed');
    if (jQuery(container).hasClass('collapsed')) {
      jQuery(container)
        .find('.item-data:first')
        .css('height', 0);
      jQuery(container)
        .parents('.item-data')
        .each(function(i, el) {
          jQuery(el).css('height', jQuery(el).outerHeight(true) - height);
        });
    } else {
      jQuery(container)
        .find('.item-data:first')
        .css('height', height);
      jQuery(container)
        .parents('.item-data')
        .each(function(i, el) {
          jQuery(el).css('height', jQuery(el).outerHeight(true) + height);
        });
    }
  });
});
