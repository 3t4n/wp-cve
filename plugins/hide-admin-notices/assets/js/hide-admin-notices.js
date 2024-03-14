/* global jQuery, hideAdminNoticesVars */
// Support for jQuery v3.6.0 for WordPress v5.9.8+.
(function ($) {
  let $document = $(document),
    $body = $('body'),
    $hanPanel = $('#hidden-admin-notices-panel'),
    $hanPanelWrap = $('#hidden-admin-notices-panel-wrap'),
    $hanToggleButton = $('#hidden-admin-notices-link'),
    $hanToggleButtonWrap = $('#hidden-admin-notices-link-wrap'),
    $screenMetaLinks = $('#screen-meta-links'),
    $wpHeaderEnd = $('.wp-header-end'),
    $wpUpdateNag = $(hideAdminNoticesVars.updateNagSelector),
    captureId = 'hidden-admin-notices-capture',
    activeBodyClass = 'hidden-admin-notices-active',
    panelActiveClass = 'hidden-admin-notices-panel-active';

  // Capture all notices because WP moves notices to after '.wp-header-end'.
  // See /wp-admin/js/common.js line #1083.
  $wpHeaderEnd.wrap('<div id="' + captureId + '">');

  // Include the update nag.
  $wpUpdateNag.detach().prependTo('#' + captureId);

  // Run after common.js.
  $(function () {
    let notices = $('#' + captureId + ' > *')
      .not('.wp-header-end')
      .not('#message');

    if (!notices.length) {
      return;
    }

    // Activate HAN.
    $body.addClass(activeBodyClass);

    // Move notices to han panel.
    notices.each(function () {
        $(this)
          .detach()
          .appendTo($hanPanel);
      }
    );

    // Copy WP default screen meta links to conserve toggle button placement when expanded.
    $screenMetaLinks.clone().appendTo($hanToggleButtonWrap);

    // Add panel toggle event.
    $hanToggleButton.on('click', function () {
      if ($hanPanel.is(':visible')) {
        $hanPanel.slideUp('fast', function () {
          $body.removeClass(panelActiveClass);
          $hanToggleButton.attr('aria-expanded', false)
          $hanPanelWrap.hide();
          $hanPanel.addClass('hidden');
        });
      } else {
        $body.addClass(panelActiveClass);
        $hanPanelWrap.show();
        $hanPanel.slideDown('fast', function () {
          $hanPanel
            .addClass('hidden')
            .trigger('focus');
          $hanToggleButton.attr('aria-expanded', true);
        });
      }
    });

    // Hide HAN panel when Screen Options or Help tab is open.
    $document.on('screen:options:open', function () {
      $hanToggleButtonWrap.fadeOut('fast', function () {
        $(this).css('visibility', 'hidden');
      })
    }).on('screen:options:close', function () {
      $hanToggleButtonWrap.fadeIn('fast', function () {
        $(this).css('visibility', '');
      })
    })
  });
})(jQuery);
