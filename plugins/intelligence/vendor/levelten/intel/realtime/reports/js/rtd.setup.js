// load google visualization libraries
google.load("visualization", "1", {packages:["corechart", 'table']});
google.setOnLoadCallback(visualizationLoaded);
function visualizationLoaded() {
    visualizationLoaded = true;
}

// init dashboard
jQuery(document).ready(function(){
    rtdSetup = new rtDashboardSetup('rtdSetup');
    rtdSetup.init();
    //doResize();

});

function rtDashboardSetup (name) {
  this.name = name;
  this.settings = {};
  this.role = getQueryStringValue('r'); // role of this window, set to all, master or child
  this.screen = getQueryStringValue('s');  // display type for this window
  this.chartWindows = {};

  this.colors = {
      'series-1': 'chart-series-1',
      'series-2': 'chart-series-2',
      'series-3': 'chart-series-3',
      'event-hit': 'event-hit',
      'event-valued': 'event-valued',
      'event-goal': 'event-goal',
      'event-subtract': 'event-subtract'
  };

  this.init = function () {
      // remove any page elements (we want a blank page to build from)
      $('body').empty();

      this.settings = rtdSettings;

      if (this.role == undefined) {
          this.role  = 'all';
      }
      if (this.screen  == undefined) {
          this.screen  = (this.role == 'master') ? 'main' : 'default';
      }

      this.doLayout();
      this.doResize();
      jQuery(window).resize(rtdSetup.doResize);

      if (this.role == 'master') {
          var winWidth = $('#dashboard').width();
          var winHeight = $('#dashboard').height();
          var options = '';
          options += 'width=' + Math.round(winWidth/4) + ',height=' + Math.round(winHeight/4);
          options += ',menubar=yes,toolbar=yes';
          var winName = 'pagesTsScreen';
          var win = window.open('?r=child&s=pagesTs', winName, options, winName);
          this.chartWindows['pages'] = win;
          this.chartWindows['pageAttrs'] = win;
          this.chartWindows['ts'] = win;
          this.chartWindows['tsDetails'] = win;
      }

      // get colors from CSS
      $to = $('body');
      for (var key in this.colors) {
          var cclass = this.colors[key];
          $div = $('<div />').appendTo($to);
          $div.addClass(cclass);

          this.colors[key] = $div.css('background-color');
      }


      rtdModel = new rtDashboardModel('rtdModel');
      rtdModel.init();

      rtdView = new rtDashboardView('rtdView');
      rtdView.init();


  };

  this.doLayout = function () {
    var $div = $('<div />').appendTo('body');
    $div.attr('id', 'dashboard');

    if (this.role == 'all') {
        this.doLayoutDefault($div);
    }
    else {
      var func = 'add' + String(this.screen).ucfirst() + 'ScreenLayout';
      this[func]($div);
    }
  };

  this.doLayoutDefault = function ($to) {
      var $div;

      $div = $('<div />').appendTo($to);
      $div.attr('id', 'top-left');
      $div.addClass('row-md-6 col-md-6');

      this.addMainScreenLayout($div);

      $div = $('<div />').appendTo($to);
      $div.attr('id', 'top-right');
      $div.addClass('row-md-6 col-md-6');

      this.addPagesTsScreenLayout($div);

      $div = $('<div />').appendTo($to);
      $div.attr('id', 'bottom-left');
      $div.addClass('row-md-6 col-md-6');

      this.addVisitorsScreenLayout($div);

      $div = $('<div />').appendTo($to);
      $div.attr('id', 'bottom-right');
      $div.addClass('row-md-6 col-md-6');

      this.addEventsScreenLayout($div);
  };

    this.addReport = function ($to, name, chartKey, rowSpan, colSpan, classes) {
        var $div;
        if (rowSpan == undefined) {
            rowSpan = 12;
        }
        if(colSpan == undefined) {
            colSpan = 12;
        }
        var $container = $('<div />').appendTo($to);
        $container.attr('id', name + '-container');
        $container.addClass('pane-container row-md-' + rowSpan + ' col-md-' + colSpan);

        var $pane = $('<div />').appendTo($container);
        $pane.addClass('pane');

        var $chart = $('<div />').appendTo($pane);
        $chart.attr('id', name);
        $chart.addClass('chart standard-chart');
        $chart.attr('data-chart-key', chartKey);

        return $chart;

/*
        if (name == 'visitor-timeline') {
            $chart.addClass('storyjs-embed sized-embed');
            var $div = $('<div />').appendTo($chart);
            $div.attr('id', 'storyjs-timeline');
            //$div.addClass('chart');
        }
*/
    };

  this.addSiteStatsReportKeyMetric = function ($to, name, value, label) {
      var $container = $('<div />').appendTo($to);
      $container.attr('id', 'site-stats-' + name);
      $container.addClass('key-metric-container');

      var $pane = $('<div />').appendTo($container);
      $pane.addClass('key-metric-pane');

      var $label = $('<div />').appendTo($pane);
      $label.addClass('key-metric-label');
      $label.text(label);

      var $valuec = $('<div />').appendTo($pane);
      $valuec.addClass('key-metric-value-container');

      var $valuep = $('<div />').appendTo($valuec);
      $valuep.addClass('key-metric-value-pane');

      var $valuea = $('<div />').appendTo($valuep);
      $valuea.attr('id', 'active-' + name);
      $valuea.addClass('key-metric-value-active');
      $valuea.text(value);

      var $cont2 = $('<div />').appendTo($valuep);
      $cont2.addClass('key-metric-container-sub');

      var $value = $('<div />').appendTo($cont2);
      $value.attr('id', 'day-' + name);
      $value.addClass('key-metric-value-day');
      $value.text(value);

      var $value = $('<div />').appendTo($cont2);
      $value.addClass('key-metric-label-sub');
      $value.text('today:');


  };

  this.addMainScreenLayout = function ($to) {
      $report = this.addReport($to, 'site-stats-table', 'siteStats', 4);

      // build site stats
      var $container = $('<div />').appendTo($report);
      $container.attr('id', 'branding');
      $container.addClass('branding-container');
      $container.text('logo');

      this.addSiteStatsReportKeyMetric($report, 'entrances', '0', 'visits');
      this.addSiteStatsReportKeyMetric($report, 'pageviews', '0', 'pageviews');
      //this.addSiteStatsReportKeyMetric($report, 'events', '0', 'events');
      this.addSiteStatsReportKeyMetric($report, 'valued-events', '0', 'val. events');
      this.addSiteStatsReportKeyMetric($report, 'goals', '0', 'goals');
      this.addSiteStatsReportKeyMetric($report, 'value', '0', 'value');

    // add active pages timeline
      var name = 'active-pageviews-timeline';

      var $container = $('<div />').appendTo($to);
      $container.attr('id', name + '-container');
      $container.addClass('pane-container row-md-4 col-md-12');

      var $pane = $('<div />').appendTo($container);
      $pane.addClass('pane');

      var $h = $('<h3 />').appendTo($pane);
      $h.addClass('row-md-2 col-md-12');
      $h.text('Pageviews');

      var $div = $('<div />').appendTo($pane);
      $div.addClass('row-md-10 col-md-12');

      var $chart = $('<div />').appendTo($div);
      $chart.attr('id', name + '-min');
      $chart.addClass('chart col-md-8 col-md-12');

      var $chart = $('<div />').appendTo($div);
      $chart.attr('id', name + '-sec');
      $chart.addClass('chart col-md-4 col-md-12');

      // add active pages timeline
      var name = 'active-events-timeline';

      var $container = $('<div />').appendTo($to);
      $container.attr('id', name + '-container');
      $container.addClass('pane-container row-md-4 col-md-12');

      var $pane = $('<div />').appendTo($container);
      $pane.addClass('pane');

      var $h = $('<h3 />').appendTo($pane);
      $h.addClass('row-md-2 col-md-12');
      $h.text('events');

      var $div = $('<div />').appendTo($pane);
      $div.addClass('row-md-10 col-md-12');

      var $chart = $('<div />').appendTo($div);
      $chart.attr('id', name + '-min');
      $chart.addClass('chart col-md-8 col-md-12');

      var $chart = $('<div />').appendTo($div);
      $chart.attr('id', name + '-sec');
      $chart.addClass('chart col-md-4 col-md-12');
  };

    this.addPagesTsScreenLayout = function ($to, inWindow) {
        this.addReport($to, 'ts-table', 'ts', 6, 6);
        this.addReport($to, 'ts-details-table', 'tsDetails', 6, 6);
        this.addReport($to, 'pages-table', 'pages', 6, 6);
        this.addReport($to, 'page-attrs-table', 'pageAttrs', 6, 6);
    };

    this.addEventsScreenLayout = function ($to) {
        this.addReport($to, 'events-table', 'events', 6, 6);
        this.addReport($to, 'event-details-table', 'eventDetails', 6, 6);
        this.addReport($to, 'ctas-table', 'ctas', 6, 6);
        this.addReport($to, 'landingpages-table', 'lps', 6, 6);
    };

    this.addVisitorsScreenLayout = function ($to) {
        this.addReport($to, 'active-visitors-table', 'visitors', 12, 4);
        this.addReport($to, 'visitor-timeline', 'visitorTimeline', 8, 8);
        this.addReport($to, 'visitor-details', 'visitorDetails', 4, 8);
    };

  this.doResize = function () {
console.log();
        // set main div to height of window
        $('#dashboard').height($(window).height());

        jQuery('[class*="row-md-"]').each(function(index) {
            var classes = $(this).attr('class');
            classes = classes.split(' ');
            var rowCount = 12;
            for(var j in classes) {
                if (classes[j].substring(0, 7) == 'row-md-') {
                    var e = classes[j].split('-');
                    rowCount = parseInt(e[2]);
                    break;
                }
            }
            var parentHeight = $(this).parent().height();
            var height = parentHeight * rowCount / 12;
            $(this).height(height);
            // set pane heights
            $('.pane', this).each(function () {
                var paneHeight = $(this).parent().height();
                var panePadding = 2 * (parseInt($(this).css('margin')) + parseInt($(this).css('border-width')));
                $(this).height(paneHeight - panePadding);
            });
            $('.chart', this).each(function () {
                var paneHeight = $(this).parent().height();
                var panePadding = 2 * (parseInt($(this).css('margin')) + parseInt($(this).css('border-width')));
                $(this).height(paneHeight - panePadding);
            });
        });

        //var paneHeight = jQuery('#pages-table-container .pane').height();
        var $chart = jQuery('#pages-table-container .chart');
        var chartHeight = $chart.height();
//console.log("pchartHeight=" + chartHeight);
        var rowHeight = (chartHeight) / 11;
        var fontSize = rowHeight/28 * 100;
        var keyMetricFontSize = fontSize * 3;

//console.log("pchartHeight=" + chartHeight + ", rowHeight=" + rowHeight + ", fontSize=" + fontSize);

        var css = document.createElement('style')
        var html = ".google-visualization-table-table tr {height: " + rowHeight + "px; font-size: " + fontSize + "%;}";
        html += "#site-stats-table .key-metric-value-active {font-size: " + keyMetricFontSize + "% !important; }";
        html += "#site-stats-table .key-metric-label { height: " + (rowHeight - 3) + "px !important; }"
        css.innerHTML = html;
        document.body.appendChild(css);

    };

}

