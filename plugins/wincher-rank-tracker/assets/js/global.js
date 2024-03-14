/* eslint-disable */
(function($) {
  setTimeout(function() {
    $('.wincher_upgrade_link').parent('a').on('click', function(event) {
      event.preventDefault();
      window.open(event.currentTarget.href, '_blank');
    });
  });
}(window.jQuery));
