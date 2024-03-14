jQuery(function ($) {
  'use strict';

  var $gc_activated = $('input[type=checkbox][name=gc_activated]');
  var $gc_overlay_checkbox = $('input[name="gc_overlay_activated"]');
  var $gc_readonly_checkbox = $('input[name="gc_readonly_activated"]');
  var $gc_sync_comments = $('#graphcomment_sync_comments_checkbox');

  $gc_activated.change(function () {
    var activated = $(this).is(':checked');

    //$datepicker.parent().toggle(activated);

    $('#gc-general .hide-not-activated').toggle(activated);

    $gc_sync_comments.prop('disabled', !activated);

    if (!activated) {
      $gc_sync_comments.prop('checked', false);
    }
  });

  $gc_overlay_checkbox.change(function () {
    var activated = $(this).is(':checked');
    $('#gc-overlay-options').toggle(activated);
  });

  $gc_readonly_checkbox.change(function () {
    var activated = $(this).is(':checked');
    $('#gc-readonly-options').toggle(activated);
  });


});
