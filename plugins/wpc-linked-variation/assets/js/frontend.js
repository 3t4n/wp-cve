'use strict';

(function($) {
  $(function() {
    if (wpclv_vars.tooltip_library === 'tippy') {
      tippy('.wpclv-tippy-tooltip', {
        allowHTML: true,
        interactive: true,
      });
    }
  });

  $(document).on('change', '.wpclv-terms-select', function() {
    window.location = $(this).val();
  });
})(jQuery);