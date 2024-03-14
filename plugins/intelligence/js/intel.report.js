google.load('visualization', '1', {packages: ['corechart']});
google.load('visualization', '1', {packages: ['table']});
var intel_map_init = intel_map_init || [];

var _intel_reports = (function ($) {
  $( document ).ready(function() {
    init();
  });

  this.locObj;

  function init() {
    //google.load('visualization', '1', {packages: ['corechart']});
    //google.load('visualization', '1', {packages: ['table']});

    var ths = this;

    this.locObj = this.parseUrl(window.location.href);

    $('#apply-report-filter').click( function() {
      reportFilterSubmit();
    });

    $("select#timeframe").change(function(event) {
      var i, url = '';
      var a = window.location.href.split('?');
      var timeframe = $(this).find('option:selected').attr('value');
      ths.locObj.params.timeframe = $(this).find('option:selected').attr('value');
      for (i in ths.locObj.params) {
        if (url) {
          url += '&';
        }
        url += i + '=' + ths.locObj.params[i];
      }
      url = a[0] + '?' + url;
      window.location = url;
    });

    if ($('#intel-report-container').length > 0) {
      var data = {
        return_type: 'json'
      };
      var url = ('https:' == document.location.protocol) ? 'https://' : 'http://';
      //url += wp_intel.settings.intel.config.cmsHostpath + $('#intel-report-container').attr("data-q"); //?callback=?";
      url += wp_intel.settings.intel.config.cmsHostpath + 'wp-admin/admin.php?page=' + $('#intel-report-container').attr("data-page");
      url += '&q=' + $('#intel-report-container').attr("data-q");
      if ($('#intel-report-container').attr("data-query")) {
        url += '&' + $('#intel-report-container').attr("data-query");
      }
      if ($('#intel-report-container').attr("data-current-path")) {
        data.current_path = $('#intel-report-container').attr("data-current-path");
      }
      if ($('#intel-report-container').attr("data-refresh")) {
        data.refresh = 1;
      }
      if ($('#intel-report-container').attr("data-dates")) {
        data.dates = $('#intel-report-container').attr("data-dates");
      }
      jQuery.ajax();
      jQuery.getJSON(url, data, function (data) {
        $("#intel-report-container").replaceWith(data.report);
        l10iCharts.init();
        if (typeof window['_intel_googleapi_map_init'] === 'function') {
          _intel_googleapi_map_init();
        }
      });

    }
  }

  this.parseUrl = function (url) {
    var l = document.createElement('a');

    l.href = url;

    var locObj = {
      protocol: l.protocol,
      hostname: l.hostname,
      port: l.port,
      pathname: l.pathname,
      search: l.search,
      hash: l.hash
    };

    locObj = this.normalizeLocation(locObj);

    return locObj;
  };

  this.normalizeLocation = function (locObj) {
    var a, b, i;
    locObj.params = {};
    if(locObj.search.charAt(0) == '?') {
      locObj.search = locObj.search.slice(1);
    }
    a = locObj.search.split('&');
    for (i = 0; i < a.length; i++) {
      b = a[i].split('=');
      locObj.params[b[0]] = b[1];
    }
    return locObj;
  };

  this.handleTimeframeChange = function(event, ths) {
    console.log(arguments);
    console.log(ths);
  };

  var intelReport = {
    attach: function (context) {
      $('#apply-report-filter').click( function() {
        reportFilterSubmit();
      });

      if ($('#intel-report-container').length > 0) {
        var data = {
          return_type: 'json'
        };
        var url = ('https:' == document.location.protocol) ? 'https://' : 'http://';
      url += Drupal.settings.intel.config.cmsHostpath + $('#intel-report-container').attr("data-q"); //?callback=?";
        if ($('#intel-report-container').attr("data-refresh")) {
          data.refresh = 1;
        }
        if ($('#intel-report-container').attr("data-dates")) {
          data.dates = $('#intel-report-container').attr("data-dates");
        }
        jQuery.ajax();
        jQuery.getJSON(url, data, function(data) {
          $("#intel-report-container").replaceWith(data.report);
          l10iCharts.init();
        });
      }
    }
  };

  function reportFilterSubmit() {
    var loc = location.href;
    var a = loc.split("?");
    var loc = a[0];
    var query = '';
    var v = $('#page-path').val();
    if ((v != undefined) && (v != '')) {
      query += ((query != '') ? '&' : '') + 'page=' + $('#page-mode').val() + ':' + v; //encodeURIComponent(v);
    }
    v = $('#event-filter').val();
    if ((v != undefined) && (v != '')) {
      query += ((query != '') ? '&' : '') + 'event=' + v;
    }
    v = $('#referrer-type').val();
    if ((v != undefined) && (v != '')) {
      query += ((query != '') ? '&' : '') + 'referrer=' + v + ':' + $('#referrer-value').val();
    }
    v = $('#location-type').val();
    if ((v != undefined) && (v != '')) {
      query += ((query != '') ? '&' : '') + 'location=' + v + ':' + $('#location-value').val();
    }
    v = $('#visitor-type').val();
    if ((v != undefined) && (v != '')) {
      query += ((query != '') ? '&' : '') + 'visitor=' + v + ':' + $('#visitor-value').val();
    }
    v = $('#visitor-attr-type').val();
    if ((v != undefined) && (v != '')) {
      query += ((query != '') ? '&' : '') + 'visitor-attr=' + v + ':' + $('#visitor-attr-value').val();
    }
    if (query != '') {
      loc = loc + '?' + query;
    }
    window.location.href = loc;
  }

  return this;
})(jQuery);