
/**
 ** Used for date printing
 */
function GcDatePrinter() {
  var days = {
    0: 'Sunday',
    1: 'Monday',
    2: 'Tuesday',
    3: 'Wednesday',
    4: 'Thursday',
    5: 'Friday',
    6: 'Saturday'
  };
  var months = {
    0: 'January',
    1: 'February',
    2: 'March',
    3: 'April',
    4: 'May',
    5: 'June',
    6: 'July',
    7: 'August',
    8: 'September',
    9: 'October',
    10: 'November',
    11: 'December'
  };

  this.formatDate = function (d) {
    return days[d.getDay()] + ' ' + d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear()
        + ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
  };

  this.formatToday = function () {
    var d = new Date();
    return this.formatDate(d);
  };
}


jQuery(function ($) {
  'use strict';

  var $datepicker = $('#datepicker');

  var tabs = [
    {
      data_toggle: 'general',
      tab: $('#graphcomment-options-general-tab'),
      content: $('#graphcomment-general')
    },
    {
      data_toggle: 'synchronization',
      tab: $('#graphcomment-options-synchronization-tab'),
      content: $('#graphcomment-synchronization')
    },
    {
      data_toggle: 'importation',
      tab: $('#graphcomment-options-importation-tab'),
      content: $('#graphcomment-importation')
    },
  ];

  function handleTabClick() {
    var $this = $(this);
    var $tab = $this.parent();
    if (!$tab.hasClass('active')) {
      var toggleContent = $this.attr('href').replace('#', '');
      selectTab(toggleContent);
    }
  }

  function selectTab(selectedTab) {
    tabs.forEach(function (t) {
      // Search for the active tab
      if (t.tab.hasClass('active')) {
        // Deactivate it
        t.tab.removeClass('active');
        t.content.fadeOut(250, function () {
          t.content.removeClass('active');
          // Search for the tab to activate
          tabs.every(function (t_toggle) {
            if (t_toggle.data_toggle === selectedTab) {
              t_toggle.tab.addClass('active');
              t_toggle.content.fadeIn(250);
              t_toggle.content.addClass('active');
              return false;
            }
            return true;
          });
        });
        return false;
      }
      return true;
    });

  }

  function getTabID(hash) {
    return '#graphcomment-options-' + hash + '-tab';
  }

  // Init the action for the tabs clicks
  tabs.forEach(function (t) {
    $(getTabID(t.data_toggle) + ' a').click(handleTabClick);
  });

  if (window.location.hash || !$('.gc-tabs > .active').length) {
    var hash = window.location.hash.replace(/^#/, '') || 'general';
    tabs.forEach(function(t) {
      if (t.data_toggle === hash) {
        $(getTabID(t.data_toggle)).addClass('active');
        selectTab(t.data_toggle);
      }
    });
  } else {
    window.location.hash = $('.gc-tabs > .active a').attr('href').replace('#', '');
  }

  $datepicker.datepicker({dateFormat: 'yy-mm-dd'});

  $('form.gc-debug input[type=checkbox]').change(function() {
    $('form.gc-debug').submit();
  });

  $('input[type=checkbox][name=gc_activated]').change(function () {
    var activated = $(this).is(':checked');

    $datepicker.parent().toggle(activated);

    $('.graphcomment-fieldset-activation').find('.hide-not-activated').toggle(activated);

    $gc_sync_comments.prop('disabled', !activated);

    if (!activated) {
      $gc_sync_comments.prop('checked', false);
    }
  });

  $('input[type=checkbox][name=gc_overlay_activated]').change(function () {
    var activated = $(this).is(':checked');
    $('#gc-overlay-options').toggle(activated);
  });

  $('input[type=checkbox][name=gc_readonly_activated]').change(function () {
    var activated = $(this).is(':checked');
    $('#gc-readonly-options').toggle(activated);
  });

  $('.colorpicker').each(function(i, picker) {
    var $picker = $(picker);
    $picker.farbtastic($picker.next('input'));

    $picker.next('input')
      .on('focus', function() { $picker.show(); })
      .on('blur', function() { $picker.hide(); })
    ;
  });

  var $changeWebsite = $('#gc-change-website');
  $changeWebsite.click(function () {
    $('<input>').attr({
      type: 'hidden',
      name: 'gc-change-website',
      value: 'true'
    }).appendTo($changeWebsite.parents('form'));
  });

  $('#graphcomment-disconnect-button').click(function() {
    window.location.href = window.location.href.replace(/(page=graphcomment)(\-settings)?/, "$1-settings&graphcomment-disconnect=true")
  });

  if (typeof gc_logout !== 'undefined' && gc_logout === true) {
    setTimeout(function() {
      window.location.reload();
    }, 1000);
  }

  // Tooltip init
  $('[data-toggle="tooltip"]').tooltip();

  $('#graphcomment-create-website').click(function(e) {
    e.preventDefault();
    $('<input>').attr({
      type: 'hidden',
      name: 'gc-create-website',
      value: 'true'
    }).appendTo('#graphcomment-create-website-form');
    $('#gc_select_website_submit_button').trigger('click');
  });

  window.addEventListener('message', function(event) {
    var event = event.data;

    // redirect to "settings" page on website creation
    if (event.type && event.type === 'website created') {
      window.location.href = window.location.href.replace(/(page=graphcomment)(\-settings)?/, "$1-settings")
    }

    if (event.type && event.type === 'user logged') {
      var userData = event.data;
      if (window.wordpressGcUser && window.wordpressGcUser !== userData._id) {
        $('.alert-wrong-account').show();
        $('.wp-gc-account').show();
      }
    }
  });

  $('.alert-wrong-account').on('click', function() {
    $('#gc-iframe').attr('src', logoutUrl);
    $('.alert-wrong-account').hide();
  });

  var $importStatus = $('#gc-form-import .label-import');
  var $importStatusHidden = $('#gc-form-import input[name=gc-import-status]');
  var $importProgress = $('#gc-form-import .progress-bar');
  var $importNbr = $('#gc-form-import .gc-import-nbr span');

  if ($importStatusHidden.attr('value') === 'pending') {
    var intervalId = setInterval(function () {
      var data = { action: 'graphcomment_import_pending_get_advancement' };

      // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
      $.post(ajaxurl, data, function (response) {
        response = JSON.parse(response);

        if (response.status === false || $importStatusHidden.attr('value') !== 'pending') {
          clearInterval(intervalId);
          return;
        }

        $importStatus.html(response.status.toUpperCase());
        $importStatusHidden.attr('value', response.status);

        $importProgress.attr('aria-valuenow', response.nbr_imported_comment);
        $importProgress.attr('style', 'width:' + response.percent + '%');
        $importProgress.html(Math.trunc(response.percent) + '%');

        $importNbr.html(response.nbr_comment_import);

        if (response.status === 'finished') {
          $importStatus.removeClass();
          $importStatus.addClass('label label-success label-import');
          $importProgress.removeClass();
          $importProgress.addClass('progress-bar progress-bar-success');

          $('.gc-import-finished-date').html((new GcDatePrinter()).formatToday());
          $('.gc-import-finished-label').removeClass('hide');
          $('.gc-import-finished-date').removeClass('hide');
          $('.gc-import-pending-stop').remove();
        }
        else if (response.status === 'error') {
          $importStatus.removeClass();
          $importStatus.addClass('label label-danger label-import');
          $importProgress.removeClass();
          $importProgress.addClass('progress-bar progress-bar-danger');

          $('<input>').attr({
            type: 'hidden',
            name: 'gc-import-restart',
            value: 'true'
          }).appendTo('#gc-form-import');

          $('.gc-import-pending-stop').remove();
        } else if (response.status === 'pending') {}
      });
    }, 1000);
  }

  $('.gc-import-pending-stop').click(function(e) {
    $('<input>').attr({
      type: 'hidden',
      name: 'gc-import-stop',
      value: 'true'
    }).appendTo('#gc-form-import');
  });


  function graphCommentAuthSuccess() {
    document.location.reload();
  }

  window.oauthPopupClose = function(timeout) {
    if (timeout === true) {
      setTimeout(function() {
        graphCommentAuthSuccess();
      }, 1500);
    }
    else {
      graphCommentAuthSuccess();
    }
  };

  var $connectionSuccessWrap = $('#connection_success_wrap');

  if ($connectionSuccessWrap) {
    $connectionSuccessWrap.find('a').click(graphCommentAuthSuccess);
  }

  /**
   * toggle logs
   */

  $('#show-logs').on('click', function(event) {
    event.preventDefault();
    $('.gc-logs').toggle();
  });


  /**
   * Detect when user has scrolled the page
   */

  function detectIsScrolled() {
    $('body').toggleClass('gc-is-scrolled', window.pageYOffset > 5);
  }

  detectIsScrolled();
  $(window).on('scroll', detectIsScrolled);


  /**
   * Alerts closing
   */

  $('.gc-alert .gc-close').on('click', function() {
    $(this).parents('.gc-alert').remove();
  });

  /**
   * Popover
   */
  $('.popover-trigger').hover(
    function() {
      var $target = $($(this).attr('href'));
      $target.addClass('gc-popover-visible')
    },
    function() {
      var $target = $($(this).attr('href'));
      $target.removeClass('gc-popover-visible')
    }
  ).click(function(event) { event.preventDefault(); });

  /* update notif count */
  setInterval(function(e) {
    $.post({
      url: ajaxurl,
      data: { action: 'graphcomment_notif_count'},
      dataType: 'json'
    })
      .success(function (response) {
        if (response && typeof response.count !== 'undefined') {
          var count = response.count;
          $('.gc-notif-count.gc-notif-admin').parent().css('display', count ? 'inline-block' : 'none').children().text(count);

          var totalNotifsCount = count;
          var $gcNotifSettings = $('.gc-notif-count.gc-notif-settings');
          if ($gcNotifSettings.length) {
            totalNotifsCount += Number($gcNotifSettings.text() || 0);
          } else {
            totalNotifsCount += 1; // in this case user is not oAuth logged yet
          }
          $('.gc-notif-count.gc-notif-main').text(totalNotifsCount).parent().css('display', totalNotifsCount ? 'inline-block' : 'none');
          $('.gc-menu-label').toggleClass('gc-menu-label-truncate', !!totalNotifsCount);
        }
      })
      .error(function(err) {
        console.log('graphcomment-settings-notifs error', err);
      })
    ;
  }, 5 * 1000);

});
