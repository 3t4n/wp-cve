(
  function($) {
    $(document).ready(function () {
      $('#wordable-plugin-settings-system-report-link').on('click', function() {
        navigator.clipboard.writeText($('#wordable-plugin-settings-system-report-text').text());

        $('#wordable-plugin-settings-system-report-link').text('Copied to clipboard!');
      })

      $('.wordable-body').fadeIn(function() {
        $('#wordable-footer').fadeIn();
      });
    });
  }
)(jQuery);
