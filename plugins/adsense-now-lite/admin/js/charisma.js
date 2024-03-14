$(document).ready(function () {

  var hasTouch = false;
  window.addEventListener('touchstart', function setHasTouch() {
    hasTouch = true;
    window.removeEventListener('touchstart', setHasTouch);
  }, false);

  var msie = navigator.userAgent.match(/msie/i);
  $.browser = {};
  $.browser.msie = {};

  $('.navbar-toggle').click(function (e) {
    e.preventDefault();
    $('.nav-sm').html($('.navbar-collapse').html());
    $('.sidebar-nav').toggleClass('active');
    $(this).toggleClass('active');
  });

  var $sidebarNav = $('.sidebar-nav');

  // Hide responsive navbar on clicking outside
  $(document).mouseup(function (e) {
    if (!$sidebarNav.is(e.target) // if the target of the click isn't the container...
            && $sidebarNav.has(e.target).length === 0
            && !$('.navbar-toggle').is(e.target)
            && $('.navbar-toggle').has(e.target).length === 0
            && $sidebarNav.hasClass('active')
            )// ... nor a descendant of the container
    {
      e.stopPropagation();
      $('.navbar-toggle').click();
    }
  });

  //disbaling some functions for Internet Explorer
  if (msie) {
    $('#is-ajax').prop('checked', false);
    $('#for-is-ajax').hide();
    $('#toggle-fullscreen').hide();
    $('.login-box').find('.input-large').removeClass('span10');

  }

  //highlight current / active link
  $('ul.main-menu li a').each(function (i, a) {
    if (a.href === window.location.href ||
            a.href.replace(/\.php/, '.ezp') === window.location.href) {
      var dad = a.parentElement;
      $(dad).addClass('active');
      var grandpa = $(dad).parent().closest('li.dropdown');
      grandpa.addClass('active');
    }
  });

  $('.accordion > a, .dropdown > a').click(function (e) {
    e.preventDefault();
    var $ul = $(this).siblings('ul');
    var $li = $(this).parent();
    $li.removeClass('active');
    $ul.slideDown(function () {
      if (!hasTouch) {
        $ul.children('li:first').children('a:first')[0].click();
      }
    });
  });

  $('li.dynamic-menu').hover(function (e) {
    e.preventDefault();
    var $ul = $(this).children('ul');
    var $sisters = $(this).nextAll('li.accordion').children('ul.nav');
    $ul.slideDown();
    $sisters.slideUp();
  });

  $('.accordion li.active:first').parents('ul').show();

  //other things to do on document ready, separated for ajax calls
  docReady();
});

function ezPopUp(url, title, w, h) {
  var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
  var wTop = window.screenTop ? window.screenTop : window.screenY;
  var left = wLeft + (window.innerWidth / 2) - (w / 2);
  var top = wTop + (window.innerHeight / 2) - (h / 2);
  window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
  return true;
}

function ezReload() {
  var winHref = window.location.href;
  if (inIframe()) {
    winHref += (winHref.match(/\?/) ? '&' : '?') + 'inframe';
  }
  window.location.href = winHref;
}

function initXedit() {
  // My x-edit interface, with checkbox defined.
  // Assumes that the vars xeditHandler and xparams are defined in the global scope.
  if (xeditInline) {
    var xeditMode = 'inline';
  }
  else {
    var xeditMode = 'popup';
  }
  if (typeof xeditHandler !== 'undefined') {
    $('.xedit').editable({
      url: xeditHandler,
      mode: xeditMode,
      validate: function (value) {
        var validator = $(this).attr('data-validator');
        if (validator) {
          return validate[validator](value);
        }
      },
      params: function (params) {
        var validator = $(this).attr('data-validator');
        if (validator) {
          params.validator = validator;
        }
        var action = $(this).attr('data-action');
        if (action) {
          params.action = action;
        }
        $.each(xparams, function (key, value) {
          params[key] = value;
        });
        return params;
      }
    });
    var activeText, emptyText, cbWidth, cbSource;
    if (wideCB) {
      activeText = '<i class="glyphicon glyphicon-ok icon-white"></i> Active';
      emptyText = 'Disabled';
      cbWidth = 150;
      cbSource = {'1': 'Active'};
    }
    else {
      activeText = '<i class="glyphicon glyphicon-ok icon-white"></i> Yes';
      emptyText = ' <i class="glyphicon glyphicon-remove icon-white"></i>&nbsp; No ';
      cbWidth = 150;
      cbSource = {'1': 'Yes'};
    }
    $('.xedit-checkbox').width(cbWidth).editable({
      url: xeditHandler,
      type: 'checklist',
      source: cbSource,
      emptytext: emptyText,
      emptyclass: 'btn-danger',
      success: function (response, newValue) {
        if (typeof checkBoxChanged === 'function') {
          checkBoxChanged(newValue);
        }
        if (newValue === "0" || newValue.length === 0 || newValue[0] === "0") {
          $(this).removeClass('btn-success').addClass('btn-danger');
        }
        if (newValue === "1" || newValue[0] === "1") {
          $(this).removeClass('btn-danger').addClass('btn-success');
        }
        $(this).parent('').hide().show(0); // to force redraw
      },
      display: function (value, sourceData) {
        $(this).width(cbWidth);
        if (value == 1) { // Needs to be autocasting ==, not ===
          $(this).html(activeText);
        }
        else {
          $(this).html(''); // Don't know why this works!
        }
      },
      params: function (params) {
        var action = $(this).attr('data-action');
        if (action) {
          params.action = action;
        }
        $.each(xparams, function (key, value) {
          params[key] = value;
        });
        return params;
      },
      error: function (a) {
        $("#alertErrorText").html(a.responseText);
        $(".alert").show();
      }
    });
  }
  $('.xedit-new').editable({
    url: 'ajax/success.php',
    validate: function (value) {
      var validator = $(this).attr('data-validator');
      if (validator) {
        return validate[validator](value);
      }
    },
    params: function (params) {
      var validator = $(this).attr('data-validator');
      if (validator)
        params.validator = validator;
      return params;
    }
  });
  $('.xedit-checkbox-new').editable('option', 'url', 'ajax/success.php');
}

function isInternalLink(link) {
  var longHref = link.href;
  var root = location.protocol + '//' + location.host;
  return longHref.indexOf(root) >= 0 && !longHref.match(/[?&]target=/);
}

function rewritePhpExt(url) {
  if (url) {
    return url.replace(/\.php(.*)/, '.ezp$1');
  }
  else {
    return url;
  }
}

function rewriteHref(i, link) {
  if (isInternalLink(link)) {
    var parentHref = parent.document.location.href
            .replace(/[?&]inframe/, '')
            .replace(/[?&]target=.*/, '');
    var shortHref = parentHref + (parentHref.match(/\?/) ? '&' : '?') +
            'target=' + rewritePhpExt($(link).attr('href'));
    $(link).attr('href', shortHref);
    link.target = '_parent';
  }
}

function docReady() {
  //prevent # links from moving to top
  $('a[href="#"][data-top!=true]').click(function (e) {
    e.preventDefault();
  });

  //notifications
  $('.noty').click(function (e) {
    e.preventDefault();
    var options = $.parseJSON($(this).attr('data-noty-options'));
    noty(options);
  });

  //tabs
  $('#myTab a:first').tab('show');
  $('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
  });

  //tooltip
  $('body').tooltip({
    selector: '[data-toggle="tooltip"]',
    html: true
  });

  //popover
  $('[data-toggle="popover"]').popover({html: true, container: 'body'});
  $('body').on('click', function (e) {
    $('[data-toggle="popover"]').each(function () {
      //the 'is' for buttons that trigger popups
      //the 'has' for icons within a button that triggers a popup
      if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
        $(this).popover('hide');
      }
    });
  });

  // lightbox
  // delegate calls to data-toggle="lightbox"
  $(document).delegate('*[data-toggle="lightbox"]', 'click', function (event) {
    event.preventDefault();
    return $(this).ekkoLightbox({
      always_show_close: true
    });
  });

  // Div iconify/maximize/close
  $('.btn-close').click(function (e) {
    e.preventDefault();
    $(this).parent().parent().parent().fadeOut();
  });
  $('.btn-minimize').click(function (e) {
    e.preventDefault();
    var $target = $(this).parent().parent().next('.box-content');
    if ($target.is(':visible'))
      $('i', $(this)).removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
    else
      $('i', $(this)).removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
    $target.slideToggle();
  });

  // Help button to use bootbox to show text
  $('body').on('click', ".btn-help", function (e) {
    e.preventDefault();
    var helpText = $(this).attr('data-help');
    if (!helpText) {
      helpText = $(this).attr('data-content');
    }
    var helpTitle = $(this).text().trim();
    if (!helpTitle) {
      helpTitle = 'Help';
    }
    bootbox.dialog({
      message: helpText,
      title: helpTitle,
      onEscape: function () {
        $(this).modal('hide');
      },
      buttons: {
        success: {
          label: "OK"
        }
      }
    });
  });

  initXedit();

  if (typeof $().colorpicker === 'function') {
    $('.colorpicker').colorpicker();
  }

  if (typeof $().dataTable === 'function') {
    var pageLength = 10;
    $('.data-table').each(function () {
      var attr = $(this).attr('data-pagination');
      if (typeof attr !== typeof undefined && attr !== false) {
        pageLength = parseInt(attr);
      }
    }).show().dataTable({
      pageLength: pageLength,
      "aaSorting": []
    }).on( 'draw.dt', function () {
      if (isInWP) {
        $(this).find('a').not("#standAloneMode, #shop, .popup, .endpoint")
                .each(rewriteHref);
      }
    });
    $('.data-table-longer').show().dataTable({
      pageLength: 20,
      aaSorting: []
    }).on( 'draw.dt', function () {
      if (isInWP) {
        $(this).find('a').not("#standAloneMode, #shop, .popup, .endpoint")
                .each(rewriteHref);
      }
    });
  }

  //Add Hover effect to menus
  $('ul.nav li.dropdown').hover(function () {
    $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn();
  }, function () {
    $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut();
  });

  $('body').on('click', ".goPro", function (e) {
    e.preventDefault();
    var product = $(this).attr('data-product');
    if (!product) {
      if (typeof getProduct === 'function') {
        product = getProduct();
      }
    }
    var url = 'http://buy.thulasidas.com/' + product;
    var title = "Get the Pro version";
    var w = 1024;
    var h = 728;
    return ezPopUp(url, title, w, h);
  });

  $('.popup').click(function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    var title = "Support Ticket";
    var w = 1024;
    var h = 728;
    return ezPopUp(url, title, w, h);
  });

  $('.popup-tall, .popup-long').click(function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    var title = "Support Ticket";
    var w = 1000;
    var h = 1024;
    return ezPopUp(url, title, w, h);
  });

  if (isInWP) {
    if (inIframe()) {
      $("#standAloneMode").show();
      $('body').find('a').not("#standAloneMode, #shop, .popup, .endpoint")
              .each(rewriteHref);
      $("#standAloneMode, #shop").each(function () {
        var shortHref = rewritePhpExt($(this).attr('href'));
        $(this).attr('href', shortHref);
      });
    }
    else {
      $("#standAloneMode").fadeOut();
      $('body').find('a').not(".popup, .endpoint").each(function () {
        if (isInternalLink(this)) {
          var shortHref = rewritePhpExt($(this).attr('href'));
          $(this).attr('href', shortHref);
        }
      });
    }
    $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
      options.url = rewritePhpExt(options.url);
    });
  }
  else {
    $("#standAloneMode").fadeOut();
  }
}

if (xeditOpenNext) {
  $('.xedit, .xedit-checkbox').on('hidden', function (e, reason) {
    if (reason === 'save' || reason === 'nochange') {
      var $next = $(this).closest('td').next().find('.xedit, .xedit-checkbox');
      if (!$next.length) {
        $next = $(this).closest('tr').next().find('td:first').find('.xedit, .xedit-checkbox');
      }
      if (!$next.length) {
        $next = $(this).closest('tr').next().find('.xedit, .xedit-checkbox');
      }
      if (true) {
        setTimeout(function () {
          $next.editable('show');
        }, 300);
      } else {
        $next.focus();
      }
    }
  });
}

function inIframe() {
  try {
    var hash;
    var q = document.URL.split('?')[1];
    if (q != undefined) {
      q = q.split('&');
      for (var i = 0; i < q.length; i++) {
        hash = q[i].split('=');
        if (hash[0] === 'inframe') {
          return true;
        }
      }
    }
    return window.self !== window.top;
  } catch (e) {
    return true;
  }
}

function flashMsg(msg, kind, noflash) {
  var id = "#alert" + kind + "Text";
  $(id).html('<strong>' + kind + '</strong>: ' + msg);
  if (typeof (noflash) === 'undefined') {
    $(id).parent().slideDown().delay(6000).slideUp();
  }
  else {
    $(id).parent().slideDown();
  }
  return $(id);
}

function hideMsg(kind) {
  var id = "#alert" + kind + "Text";
  $(id).html('');
  $(id).parent().slideUp();
  return $(id);
}

function flashError(error) {
  return flashMsg(error, 'Error');
}

function showError(error) {
  return flashMsg(error, 'Error', true);
}

function hideError() {
  return hideMsg('Error');
}

function flashWarning(warning) {
  return flashMsg(warning, 'Warning');
}

function showWarning(warning) {
  return flashMsg(warning, 'Warning', true);
}

function hideWarning() {
  return hideMsg('Warning');
}

function flashSuccess(message) {
  return flashMsg(message, 'Success');
}

function showSuccess(message) {
  return flashMsg(message, 'Success', true);
}

function hideSuccess() {
  return hideMsg('Success');
}

function flashInfo(message) {
  return flashMsg(message, 'Info');
}

function showInfo(message) {
  return flashMsg(message, 'Info', true);
}

function hideInfo() {
  return hideMsg('Info');
}

$(".close").click(function () {
  $(this).parent().slideUp();
});


function containerSelect(id) {
  containerUnselect();
  if (document.selection) {
    var range = document.body.createTextRange();
    range.moveToElementText(id);
    range.select();
  }
  else if (window.getSelection) {
    var range = document.createRange();
    range.selectNode(id);
    window.getSelection().addRange(range);
  }
}

function containerUnselect() {
  if (document.selection) {
    document.selection.empty();
  }
  else if (window.getSelection) {
    window.getSelection().removeAllRanges();
  }
}

function validate_email(s) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  if (!re.test(s)) {
    return "Bad email address";
  }
}

function validate_notNull(s) {
  if ($.trim(s) == '' || s === 'Empty') {
    return "Null value not allowed";
  }
}

function validate_number(s) {
  if (!jQuery.isNumeric(s)) {
    return "Need a number here";
  }
}

function validate_alnum(s) {
  var ss = s.replace('-', '').replace('_', '');
  var re = /^[a-zA-Z0-9]+$/;
  if (!re.test(ss) || s === 'Empty') {
    return "Please use only letters, numbers, - and _";
  }
}

function validate_url(s) {
  var re = /^([a-z]([a-z]|\d|\+|-|\.)*):(\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?((\[(|(v[\da-f]{1,}\.(([a-z]|\d|-|\.|_|~)|[!\$&'\(\)\*\+,;=]|:)+))\])|((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=])*)(:\d*)?)(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*|(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)){0})(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
  if (!re.test(s)) {
    return "Bad URL";
  }
}

function validate_filename(s) {
  var invalid=/^[^\\/:\*\?"<>\| ]+$/;
  var ext = s.replace(/^.*\./, '');
  if (ext === s || !invalid.test(s)) {
    return "Please use the format file-name.ext. No spaces. Do not forget the right extension.";
  }
}

var validate = {
  email: function (s) {
    return validate_email(s);
  },
  notNull: function (s) {
    return validate_notNull(s);
  },
  number: function (s) {
    return validate_number(s);
  },
  alnum: function (s) {
    return validate_alnum(s);
  },
  url: function (s) {
    return validate_url(s);
  },
  filename: function (s) {
    return validate_filename(s);
  }
};
