function rtDashboardView (name) {
    this.model;
    this.config;
    //this.view;
    this.charts = {};
    this.chartData = {};
    this.chartDivs = {
        site: 'site-stats-table',
        pages: 'pages-table',
        pageAttrs: 'page-attrs-table',
        events: 'events-table',
        eventDetails: 'event-details-table',
        ctas: 'ctas-table',
        lps: 'landingpages-table',
        ts: 'ts-table',
        tsDetails: 'ts-details-table',
        visitors: 'active-visitors-table',
        visitorTimeline: 'visitor-timeline',
        visitorDetails: 'visitor-details-report'
    }
    this.chartsEnabled = {};
    this.chartIndex = {
        pages: {},
        pageAttrs: {},
        events: {},
        eventDetails: {},
        ctas: {},
        lps: {},
        ts: {},
        tsDetails: {},
        visitors: {}
    };
    this.chartInitialized = {
        pages: false,
        pageAttrs: false,
        events: false,
        eventDetails: false,
        ctas: false,
        lps: false,
        ts: false,
        tsDetails: false,
        visitors: false
    };
    this.chartSorts = {
        pages: null,
        pageAttrs: null,
        events: null,
        eventDetails: null,
        ctas: null,
        lps: null,
        ts: null,
        tsDetails: null,
        visitors: null
    };
    this.chartSelections = {
        pages: null,
        pageAttrs: null,
        events: null,
        eventDetails: null,
        ctas: null,
        lps: null,
        ts: null,
        tsDetails: null,
        visitors: null
    };
    this.chartBumps = {
        site: {},
        pages: {},
        pageAttrs: {},
        ts: {},
        tsDetails: {},
        events: {},
        eventDetails: {},
        ctas: {},
        lps: {},
        visitors: {}
    };
    this.chartRotation = {
        pageAttrs: [
            ['ct'],
            ['ct', 'blog'],
            ['j'],
            ['j', 1],
            ['t'],
            ['a']
        ],
        tsDetails: [
            ['source'],
            ['medium'],
            ['campaign'],
            ['term']
        ],
        eventDetails: [
          ['Social share'],
          ['Social share', 'PAGES'],
          ['Social share', 'Twitter'],
          ['Comment']
        ]
    };
    this.chartRotationLabels = {
      pageAttrs: {
        a: 'Authors',
        ct: 'Content types',
        'ct.DEFAULT': '%subtype pages',
        j: 'Subjects',
        'j.DEFAULT': '%subtype pages',
        t: 'Terms'
      },
      tsDetails: {
            source: 'Source',
            medium: 'Medium',
            campaign: 'Campaign',
            term: 'Keywords'
        },
      eventDetails: {
            'Social share': 'Social shares',
            'Social share.PAGES': 'Shared pages',
            'Social share.DEFAULT': 'Shared pages (%subtype)',
            'Comment': 'Comments'
        }
    };
    this.chartCmsLinks = {
        pageAttrs: {
            a: 'admin/reports/intel/content/pa-a',
            ct: 'admin/reports/intel/content/pa-ct',
            //'ct.DEFAULT': 'admin/reports/intel/content/pa-ct-p/%subtype-key',
            j: 'admin/reports/intel/content/pa-j',
            //'j.DEFAULT': 'admin/reports/intel/content/pa-j-p/%subtype-key',
            t: 'admin/reports/intel/content/pa-t'
        },
        tsDetails: {
            source: 'admin/reports/intel/trafficsource/source',
            medium: 'admin/reports/intel/trafficsource/medium',
            campaign: 'admin/reports/intel/trafficsource/campaign',
            keyword: 'admin/reports/intel/trafficsource/keyword'
        }
    };
    this.chartRotationI = {
        tsDetails: 0,
        pageAttrs: 0,
        eventDetails: 0
    };
    this.chartActive = {
        pageAttrs: '',
        tsDetails: '',
        eventDetails: ''
    };
    this.lastSecOffsets = {};
    this.lastBuildCharts = 0;
    this.rotationCount = 0; // incrementer for when to rotate charts
    this.activeVisitor = null;
    this.selectedVisitor = null; // stores user selected visitor

    this.init = function (chartWindows) {
      for (var key in this.chartDivs) {
        if ($('#' + this.chartDivs[key]).length > 0) {
            this.chartsEnabled[key] = true;
        }
      }

      if (rtdConfig.role == 'child') {
          this.model = window.opener.rtdModel;
          this.config = window.opener.rtdConfig;
          //this.view = window.opener.rtdView;
      }
      else {
          this.model = window.rtdModel;
          this.config = window.rtdConfig;
          //this.view = window.rtdView;
      }

      // if this window is the main report, then initiate polling
      if ((rtdConfig.role == 'all') || rtdConfig.role == 'master') {
          this.pole();
      }

      $(window).on('DATAREADY', function(e, d) {
          //console.log('on.DATAREADY');
          //console.log(e);
          //console.log(d);
          rtdView.onTimelineDataReady(this, e, d);
      });


      $('#visitor-timeline').on('LOADED', function(e) {
          //console.log('on.LOADED');
          //console.log(e);
          rtdView.onTimelineLoaded(this, e);
      });

        $('#visitor-timeline').on('UPDATE', function(e) {
            //console.log('on.UPDATE');
            //console.log(e);

            rtdView.onTimelineUpdate(this, e);
        });

        $('#visitor-timeline').on("DOMSubtreeModified", '.vco-navigation', function() {
            //console.log('on.DOMSubtreeModified');
            //rtdView.onTimelineUpdate(event);
        });


        /*
      $('#visitor-timeline').delegate(".vco-timeline", "UPDATE", function (event) {
          alert('update');
          rtdView.onTimelineUpdate(event);
      });
      */

      if ((rtdConfig.screen == 'default') || (rtdConfig.screen == 'visitors')) {
          // init timeline
          var timelineData = {
              //headline: " Clickstream",
              type: "default",
              //text: "Intro body text goes here",
              //startDate: this.formatTimelineDate(curTime - 1800),
              //endDate: this.formatTimelineDate(curTime),
              date: [{
                  "startDate": this.formatTimelineDate(this.model.getTime()),
                  "endDate": this.formatTimelineDate(this.model.getTime()),
                  headline: 'Clickstream'
              }]
          };
          var data = {
              type:       'timeline',
              width:      $('#visitor-timeline').width()+2,
              height:     $('#visitor-timeline').height()+16,
              //height:     $('#timeline-report').height()+16+100,
              source:     {timeline: timelineData},
              embed_id:   'visitor-timeline',
              //hash_bookmark: true,
              start_at_slide: 0,
              customMode: 'rtd',
              start_zoom_adjust:  '-1',
              //start_at_end: true,
              debug: false

          };

          this.timeline = createStoryJS(data);
      }


    };

    this.pole = function pole() {
        rtdView.buildAll();

        window.setInterval(function () {
            rtdView.buildAll();
        }, 1000);
    };

    this.initStatsDelta = function initStatsDelta() {
        this.model.statsDelta = {
            site: {
                entrances: 0,
                pageviews: 0,
                events: 0,
                valuedEvents: 0,
                goals: 0,
                value: 0
            },
            pages: {},
            pageAttrs: {},
            events: {},
            eventDetails: {},
            ctas: {},
            lps: {},
            ts: {},
            tsDetails: {},
            visitors: {}
        };
    };

    this.chartRotationNext = 0;
    this.buildAll = function buildAll() {
        if ((typeof(sp) == 'undefined')) {
            this.buildCharts();
            this.rotateReports();
        }
    };

    this.buildCharts = function buildCharts() {
        var log = this.model.log;
        var logNew = this.model.logNew;
        this.model.logNew = {};
        // clear previous statsDelta values
        this.initStatsDelta();

        var curTime = this.model.getTime();
        var maxValue = 0;
//console.log('log'); console.log(log);
        this.haveNewData = false;
        if (!jQuery.isEmptyObject(logNew)) {
            this.haveNewData = true;
//console.log('logNew:'); console.log(logNew);
        }

        var secTime0 = curTime - 60;
        var minTime0 = curTime - 1800;

        var curDate = new Date(1000 * curTime);
        var curSeconds = curDate.getSeconds();
        var curTimeMin = curTime - curSeconds;
        //var secPer = (60 - curSeconds - 1)/60;
        var secPer = curSeconds/60;
        var lastBuildDate = new Date(1000 * this.lastBuild);
        var lastBuildSeconds = lastBuildDate.getSeconds();


        var col1Style = 'stroke-color: ' + this.config.colors['series-1'] + '; stroke-width: 2; fill-opacity: 0.4';
        var col2Style = 'stroke-color: ' + this.config.colors['series-2'] + '; stroke-width: 2; fill-opacity: 0.4';
        var col3Style = 'stroke-color: ' + this.config.colors['series-3'] + '; stroke-width: 2; fill-opacity: 0.4';
        var col4Style = 'stroke-color: ' + this.config.colors['series-4'] + '; stroke-width: 2; fill-opacity: 0.4';
        if (this.chartData.pvMin == undefined) {
            this.chartData.pvMin = new google.visualization.DataTable();
            this.chartData.pvMin.addColumn('number', 'Time');
            this.chartData.pvMin.addColumn('number', 'Pageviews');
            this.chartData.pvMin.addColumn({type: 'string', role: 'style'});
            this.chartData.pvMin.addColumn('number', 'Entrances');
            this.chartData.pvMin.addColumn({type: 'string', role: 'style'});
            for (var i = 0; i < 30; i++) {
                this.chartData.pvMin.addRow([i*60, 0, col1Style, 0, col2Style]);
            }
        }
        if (this.chartData.pvSec == undefined) {
            this.chartData.pvSec = new google.visualization.DataTable();
            this.chartData.pvSec.addColumn('number', 'Time');
            this.chartData.pvSec.addColumn('number', 'Pageviews');
            this.chartData.pvSec.addColumn('number', 'Entrances');
        }
        if (this.chartData.eMin == undefined) {
            this.chartData.eMin = new google.visualization.DataTable();
            this.chartData.eMin.addColumn('number', 'Time');
            this.chartData.eMin.addColumn('number', 'Events');
            this.chartData.eMin.addColumn({type: 'string', role: 'style'});
            this.chartData.eMin.addColumn('number', 'Valued events');
            this.chartData.eMin.addColumn({type: 'string', role: 'style'});
            this.chartData.eMin.addColumn('number', 'Goals');
            this.chartData.eMin.addColumn({type: 'string', role: 'style'});
            for (var i = 0; i < 30; i++) {
                this.chartData.eMin.addRow([i*60, 0, col1Style, 0, col3Style, 0, col4Style]);
            }
        }
        if (this.chartData.eSec == undefined) {
            this.chartData.eSec = new google.visualization.DataTable();
            this.chartData.eSec.addColumn('number', 'Time');
            this.chartData.eSec.addColumn('number', 'Valued events');
            this.chartData.eSec.addColumn('number', 'Events');
            this.chartData.eSec.addColumn('number', 'Goals');
        }
//console.log(this.chartData.eMin.toJSON());
        var rowPvMinInit = [0, 0, col1Style, 0, col2Style];
        var rowEMinInit = [0, 0, col1Style, 0, col3Style, 0, col4Style];
        if (curSeconds < lastBuildSeconds) {
            for (var i = 29; i > 0; i--) {
                for (var j = 0; j < 6; j++) {
                    if (j < 4) {
                        var value = this.chartData.pvMin.getValue(i-1, j);
                        this.chartData.pvMin.setValue(i, j, value);
                    }

                  var value = this.chartData.eMin.getValue(i-1, j);
                  this.chartData.eMin.setValue(i, j, value);
                }
            }
            for (var j = 0; j < 6; j++) {
                if (j < 4) {
                  this.chartData.pvMin.setValue(0, j, rowPvMinInit[j]);
                }
                this.chartData.eMin.setValue(0, j, rowEMinInit[j]);
            }
        }
        else {
            for (var i = 0; i < 30; i++) {
                var time = this.chartData.pvMin.getValue(i, 0);
                //min = Math.floor(min);
                if (this.lastBuild > 0) {
                    time = time + (curSeconds - lastBuildSeconds);
                }
                else {
                    time = time + curSeconds;
                }
                this.chartData.pvMin.setValue(i, 0, time);
                this.chartData.eMin.setValue(i, 0, time);
            }
        }

        // decrement time of existing rows
        var pvSecIndexes = [];
        var rows = this.chartData.pvSec.getNumberOfRows();
        for (var i = 0; i < rows; i++) {
            var value = this.chartData.pvSec.getValue(i, 0);
            if (curSeconds < lastBuildSeconds) {
              value += (curSeconds - lastBuildSeconds + 60);
            }
            else {
              value += (curSeconds - lastBuildSeconds);
            }
            if (value > 62) {
                this.chartData.pvSec.removeRow(i);
                rows--;
            }
            else {
                pvSecIndexes[value] = i;
                this.chartData.pvSec.setValue(i, 0, value);
            }
        }

        var eSecIndexes = [];
        var rows = this.chartData.eSec.getNumberOfRows();
        for (var i = 0; i < rows; i++) {
            var value = this.chartData.eSec.getValue(i, 0);
            if (curSeconds < lastBuildSeconds) {
                value += (curSeconds - lastBuildSeconds + 60);
            }
            else {
                value += (curSeconds - lastBuildSeconds);
            }
            if (value > 62) {
                this.chartData.eSec.removeRow(i);
                rows--;
            }
            else {
                eSecIndexes[value] = i;
                this.chartData.eSec.setValue(i, 0, value);
            }
        }
        //console.log(curSeconds + ', ' + lastBuildSeconds);
        //console.log(pvSecIndexes);

        var minData = [];
        var count = 0;
        for (var i in logNew) {
            var logElement = logNew[i];
            var index = 0;
            var t = parseInt(i);
            // skip if t is greaterthan the browsers current time
            if (t > curTime) {
              continue;
            }
            var counts = this.getLogElementTypeCount(logElement);
            var d = new Date(1000 * t);
            if (t >= minTime0) {
//console.log(counts);


                row = 29 - Math.floor((t - minTime0 - 1) / 60);
//console.log(row);
                if (counts.siteAdd.pageviews > 0) {
                    count = this.chartData.pvMin.getValue(row, 1);
                    this.chartData.pvMin.setValue(row, 1, (counts.siteAdd.pageviews - counts.siteAdd.entrances) + count);
                    count = this.chartData.pvMin.getValue(row, 3);
                    this.chartData.pvMin.setValue(row, 3, counts.siteAdd.entrances + count);
                }
                if (counts.siteAdd.events > 0) {
                    count = this.chartData.eMin.getValue(row, 1);
                    this.chartData.eMin.setValue(row, 1, (counts.siteAdd.events - counts.siteAdd.valuedEvents - counts.siteAdd.goals) + count);
                    count = this.chartData.eMin.getValue(row, 3);
                    this.chartData.eMin.setValue(row, 3, counts.siteAdd.valuedEvents + count);
                    count = this.chartData.eMin.getValue(row, 5);
                    this.chartData.eMin.setValue(row, 5, counts.siteAdd.goals + count);
                }
            }
            if (t >= secTime0) {
                time = curTime - t;
                // check if row exists with time index. If not, add new row, otherwise
                // update
                if (counts.siteAdd.pageviews > 0) {
                    if (pvSecIndexes[time] == undefined) {
                        this.chartData.pvSec.addRow([time, (counts.siteAdd.pageviews - counts.siteAdd.entrances), counts.siteAdd.entrances]);
                    }
                    else {
                        var row = pvSecIndexes[time];
                        count = this.chartData.pvSec.getValue(row, 1);
                        this.chartData.pvSec.setValue(row, 1, (counts.siteAdd.pageviews - counts.siteAdd.entrances) + count);
                        count = this.chartData.pvSec.getValue(row, 2);
                        this.chartData.pvSec.setValue(row, 2, counts.siteAdd.entrances + count);
                    }
                }
                if (counts.siteAdd.events > 0) {
                    if (eSecIndexes[time] == undefined) {
                        this.chartData.eSec.addRow([time, (counts.siteAdd.events - counts.siteAdd.valuedEvents - counts.siteAdd.goals), counts.siteAdd.valuedEvents, counts.siteAdd.goals]);
                    }
                    else {
                        var row = eSecIndexes[time];
                        count = this.chartData.eSec.getValue(row, 1);
                        this.chartData.eSec.setValue(row, 1, (counts.siteAdd.events - counts.siteAdd.valuedEvents - counts.siteAdd.goals) + count);
                        count = this.chartData.eSec.getValue(row, 2);
                        this.chartData.eSec.setValue(row, 2, counts.siteAdd.valuedEvents + count);
                        count = this.chartData.eSec.getValue(row, 3);
                        this.chartData.eSec.setValue(row, 3, counts.siteAdd.goals + count);
                    }
                }

            }
        }
        //console.log(this.chartData.pvMin.toJSON());
//console.log(this.model.statsDelta);
        var statsData = this.model.statsDelta;
        if ((this.lastBuild == 0)) {
           statsData = this.model.stats;
        }

        if (this.haveNewData) {
            //console.log('statsData:');console.log(statsData);
        }

        this.updateSiteStatsReport(statsData);

        this.buildPagesReport(statsData);

        this.buildPageAttrsReport(statsData);

        this.buildTsReport(statsData);

        this.buildTsDetailsReport(statsData);

        this.buildEventsReport(statsData);

        this.buildEventDetailsReport(statsData);

        this.buildCtasReport(statsData);

        this.buildLpsReport(statsData);

        this.buildVisitorsReport(statsData);

        var minDivWidth = jQuery('#chart-realtime-pageviews-min').width();
        var backgroundColor = jQuery('.pane').css('background-color');
        var fontColor = jQuery('.pane').css('color');
        var baselineColor = '#AAA';
        //console.log(minDivWidth);
        var options = {
            colors: [
              this.config.colors['series-1'],
              this.config.colors['series-2'],
              this.config.colors['series-3']
            ],
            isStacked: true,
            chartArea: {
                left: "2%",
                top: "2%",
                width: "96%",
                height: "86%"
            },
            legend: {
                position: 'in',
                alignment: 'end',
                textStyle: {
                    color: fontColor
                }
            },
            bar: {
                groupWidth: '100%'
            },
            backgroundColor: backgroundColor,
            titleTextStyle: {
                color: fontColor
            },
            vAxis: {
                gridlines: {color: '#444'},
                textPosition: 'in',
                minValue: 2,
                textStyle: {
                    color: fontColor
                },
                baselineColor: baselineColor
            },
            hAxis: {
                direction: -1,
                gridlines: {count: 6, color: backgroundColor},
                ticks: [
                    {v: 300, f:'-5 min'},
                    {v: 600, f:'-10 min'},
                    {v: 900, f:'-15 min'},
                    {v: 1200, f:'-20 min'},
                    {v: 1500, f:'-25 min'}
                ],
                viewWindow: {
                    min: -30,
                    max: 1800
                },
                textStyle: {
                    color: fontColor
                },
                baselineColor: backgroundColor
            }
        };

        options.colors = [
            this.config.colors['series-1'],
            this.config.colors['series-2']
        ];
        if (this.charts.pvMin == undefined) {
            this.charts.pvMin = new google.visualization.ColumnChart(document.getElementById('active-pageviews-timeline-min'));
        }
        this.charts.pvMin.draw(this.chartData.pvMin, options);

        options.colors = [
            this.config.colors['series-1'],
            this.config.colors['series-3'],
            this.config.colors['series-4']
        ];
        if (this.charts.eMin == undefined) {
            this.charts.eMin = new google.visualization.ColumnChart(document.getElementById('active-events-timeline-min'));
        }
        this.charts.eMin.draw(this.chartData.eMin, options);

        options.colors = [
            this.config.colors['series-1'],
            this.config.colors['series-2']
        ];
        options.bar.groupWidth = 5;
        options.legend.position = 'none';
        options.hAxis.ticks = [
            {v: 15, f:'-15 sec'},
            {v: 30, f:'-30 sec'},
            {v: 45, f:'-45 sec'}
        ];
        options.hAxis.viewWindow = {
          min: -1,
          max: 60
        }
        options.animation = {
          duration: 900
        };

        if (this.charts.pvSec == undefined) {
           this.charts.pvSec = new google.visualization.ColumnChart(document.getElementById('active-pageviews-timeline-sec'));
        }
        this.charts.pvSec.draw(this.chartData.pvSec, options);

        options.colors = [
            this.config.colors['series-1'],
            this.config.colors['series-3'],
            this.config.colors['series-4']
        ];
        if (this.charts.eSec == undefined) {
            this.charts.eSec = new google.visualization.ColumnChart(document.getElementById('active-events-timeline-sec'));
        }
        this.charts.eSec.draw(this.chartData.eSec, options);

        this.doChartBumps();

        // set last
        this.lastBuild = curTime;
    };

    this.siteStats = {
        entrances: 0,
        pageviews: 0,
        valuedEvents: 0,
        goals: 0,
        value: 0
    };
    this.updateSiteStatsReport = function(statsData, refresh) {

        var html = '';
        // if pageview or events has not changed, no need to update
        if ((statsData.site.pageviews == 0) && (statsData.site.events == 0)) {
            return;
        }
        if (statsData.site.entrances > 0) {
            this.siteStats.entrances += statsData.site.entrances;
            $('#active-entrances').text(this.siteStats.entrances);
            $('#day-entrances').text(this.siteStats.entrances);
        }
        if (statsData.site.pageviews > 0) {
            this.siteStats.pageviews += statsData.site.pageviews;
            $('#active-pageviews').text(this.siteStats.pageviews);
            $('#day-pageviews').text(this.siteStats.pageviews);
        }
        if (statsData.site.valuedEvents > 0) {
            this.siteStats.valuedEvents += statsData.site.valuedEvents;
            $('#active-valued-events').text(this.siteStats.valuedEvents);
            $('#day-valued-events').text(this.siteStats.valuedEvents);
        }
        if (statsData.site.goals > 0) {
            this.siteStats.goals += statsData.site.goals;
            $('#active-goals').text(this.siteStats.goals);
            $('#day-goals').text(this.siteStats.goals);
        }
        if (statsData.site.value > 0) {
            this.siteStats.value += statsData.site.value;
            $('#active-value').text(this.siteStats.value.toFixed(2));
            $('#day-value').text(this.siteStats.value.toFixed(2));
        }
    };

    this.formatSiteStatsElement = function (desc, value) {
        var text = '';

    }

    this.drawTableOptions = function() {
        options = {
            //showRowNumber: true,
            allowHtml: true,
            pageSize: 10,
            page: 'event',
            cssClassNames: {
                tableRow:         'table-row table-row-even',
                oddTableRow:      'table-row table-row-odd',
                headerRow:        'table-header-row',
                tableCell:        'table-cell',
                headerCell:       'table-header-cell',
                selectedTableRow: 'table-row table-row-selected'
            }
        };
        return options;
    };

    this.valueFormatter = function() {
       return new google.visualization.NumberFormat({
            fractionDigits: 2
        });
    };

    this.percentFormatter = function() {
        return new google.visualization.NumberFormat({
            fractionDigits: 1,
            suffix: '%'
        });
    };

    this.timeMSFormatter = function() {
        return new google.visualization.DateFormat({
            pattern: "m:ss"
        });
    };

    this.chartRedraw = {};

    this.buildPagesReport = function(statsData, refresh) {
        var chartKey = 'pages';
        var draw = false;
        // check if this report in in another window
        if (rtdConfig.chartWindows[chartKey] != undefined) {
            rtdConfig.chartWindows[chartKey].rtdView.buildPagesReport(statsData, refresh);
            return;
        }
        if (!this.chartsEnabled[chartKey]) {
            return;
        }
        if (this.chartData[chartKey] == undefined) {
            this.chartData[chartKey] = new google.visualization.DataTable();
            var label = ''; //'<span class="table-menu">' + this.getIcon('icon-menu', 'icon-menu') + '</span>';
            label += 'Pages' + this.getCMSLink('link-ext', this.config.settings.cmsPath + 'admin/reports/intel/content');
            this.chartData[chartKey].addColumn('string', label);
            this.chartData[chartKey].addColumn('number', 'Ent');
            this.chartData[chartKey].addColumn('number', 'Pvs');
            this.chartData[chartKey].addColumn('number', 'Val');
            draw = true;
        }

        for (var key in statsData[chartKey]) {
            var c = statsData[chartKey][key];
            draw = true;
            if (this.chartIndex[chartKey][key] == undefined) {
                this.chartIndex[chartKey][key] = this.chartData[chartKey].getNumberOfRows();
                var label = this.capstr(key) + this.getCMSLink('link-ext', key);
                this.chartData[chartKey].addRow([label, 0, 0, 0]);
            }
            var row = this.chartIndex[chartKey][key];

            count = this.chartData[chartKey].getValue(row, 1);
            this.chartData[chartKey].setValue(row, 1, c.entrances + count);

            count = this.chartData[chartKey].getValue(row, 2);
            this.chartData[chartKey].setValue(row, 2, c.pageviews + count);

            count = this.chartData[chartKey].getValue(row, 3);
            this.chartData[chartKey].setValue(row, 3, c.value + count);

            if (refresh != true) {
                if (c.pageviews > 0) {
                    this.chartBumps[chartKey][row] = 0;
                }
                if (c.value > 1) {
                    this.chartBumps[chartKey][row] = 1;
                }
            }
        }

        if (this.charts[chartKey] == undefined) {
            this.charts[chartKey] = new google.visualization.Table(document.getElementById(this.chartDivs[chartKey]));
            google.visualization.events.addListener(this.charts[chartKey], 'ready', function () {rtdView.doChartBumps(chartKey);});
        }
        if (draw) {
            var valueFormatter = this.valueFormatter();
            valueFormatter.format(this.chartData[chartKey], 3);
            this.charts[chartKey].draw(this.chartData[chartKey], this.drawTableOptions());
        }

    };

    this.buildPageAttrsReport = function(statsData, type, refresh) {
        var chartKey = 'pageAttrs';
        var draw = false;
        // check if this report in in another window
        if (rtdConfig.chartWindows[chartKey] != undefined) {
            rtdConfig.chartWindows[chartKey].rtdView.buildPageAttrsReport(statsData, refresh);
            return;
        }
        if (!this.chartsEnabled[chartKey]) {
            return;
        }
        if (type == undefined) {
          type = (this.chartActive[chartKey].length > 0) ? this.chartActive[chartKey] : this.chartRotation[chartKey][this.chartRotationI[chartKey]];
        }
        if (refresh == true) {
          this.chartData.pageAttrs = null;
          this.chartIndex.pageAttrs = {};
        }

        if (this.chartRedraw.pageAttrs != undefined) {
          draw = true;
          delete this.chartRedraw.pageAttrs;
        }

        if (this.chartData[chartKey] == undefined) {
            this.chartData[chartKey] = new google.visualization.DataTable();
            this.chartData[chartKey].addColumn('string', 'Content types');
            this.chartData[chartKey].addColumn('number', 'Ent');
            this.chartData[chartKey].addColumn('number', 'Pvs');
            this.chartData[chartKey].addColumn('number', 'Val');
            draw = true;
        }
        var labels = {};
        var data = {};
        if (statsData[chartKey][type[0]] != undefined
        //    && ()
        ) {
            data = statsData[chartKey][type[0]];
            if ((type.length == 2)) {
              if (data[type[1]] == undefined) {
                data = {};
              }
              else {
                  data = data[type[1]]._pages;
              }
            }

            lkeys = [];
            var headerLabel = 'Page attributes';
            var headerLink = '';
            if (type.length == 1) {
                lkeys.push(type[0]);
            }
            else if (type.length == 2) {
                lkeys.push(type[0] + '.' + type[1]);
                lkeys.push(type[0] + '.DEFAULT');
            }
            for (var i = 0; i < lkeys.length; i++) {
                if (this.chartRotationLabels[chartKey][lkeys[i]] != undefined) {
                    headerLabel = this.chartRotationLabels[chartKey][lkeys[i]];
                    if (this.chartCmsLinks[chartKey][lkeys[i]] != undefined) {
                        headerLink = this.chartCmsLinks[chartKey][lkeys[i]];
                    }
                    break;
                }
            }
            if (type.length > 0) {
                var rep = (this.model.attrInfo.page[type[0]].title != undefined) ? this.model.attrInfo.page[type[0]].title : type[0];
                headerLabel = headerLabel.replace("%type", rep);
            }
            if (type.length == 2) {
                var rep = (this.model.attrInfo.page[type[0]].options[type[1]] != undefined) ? this.model.attrInfo.page[type[0]].options[type[1]].title : type[1];
                headerLabel = headerLabel.replace("%subtype", rep);
            }

            if (headerLink != '') {
                headerLabel += this.getCMSLink('link-ext', this.config.settings.cmsPath + headerLink);
            }

            this.chartData.pageAttrs.setColumnLabel(0, headerLabel);

            labels = this.model.attrInfo['page'][type[0]].options;
        }

        for (var key in data) {
            var c = data[key];
            draw = true;
            if (this.chartIndex[chartKey][key] == undefined) {
                this.chartIndex[chartKey][key] = this.chartData[chartKey].getNumberOfRows();
                var label = key;
                if (labels[key] != undefined) {
                   label = labels[key].title;
                }
                else {
                    if ((type[1] == undefined)) {
                        label = '<span class="placeholder-attr-option placeholder-pa-' + type[0] + '-' + key + '">' + key + '</span>';
                        this.model.fetchAttributeOptionInfo('page', type[0], key, rtdView.updateAttributeOptionInfo);
                    }
                    /*
                    if ((type[0] == 'a') && (type[1] == undefined)) {

                    }
                    else if ((type[0] == 'ct') && (type[1] == undefined)) {
                        label = '<span class="placeholder-attr-option placeholder-pa-' + type[0] + '-' + key + '">' + key + '</span>';
                        this.model.fetchAttributeOptionInfo('page', type[0], key, rtdView.updateAttributeOptionInfo);
                    }
                    else if ((type[0] == 't') && (type[1] == undefined)) {
                        label = '<span class="placeholder-attr-option placeholder-pa-' + type[0] + '-' + key + '">' + key + '</span>';
                        this.model.fetchAttributeOptionInfo('page', type[0], key, rtdView.updateAttributeOptionInfo);
                    }
                    else if ((type[0] == 's') && (type[1] == undefined)) {
                        label = '<span class="placeholder-attr-option placeholder-pa-' + type[0] + '-' + key + '">' + key + '</span>';
                        this.model.fetchAttributeOptionInfo('page', type[0], key, rtdView.updateAttributeOptionInfo);
                    }
                    */

                }
                this.chartData[chartKey].addRow([label, 0, 0, 0]);
            }
            row = this.chartIndex[chartKey][key];
            count = this.chartData[chartKey].getValue(row, 1);
            this.chartData[chartKey].setValue(row, 1, c.entrances + count);

            count = this.chartData[chartKey].getValue(row, 2);
            this.chartData[chartKey].setValue(row, 2, c.pageviews + count);

            count = this.chartData[chartKey].getValue(row, 3);
            this.chartData[chartKey].setValue(row, 3, c.value + count);

            if (refresh != true) {
                if (c.pageviews > 0) {
                    this.chartBumps[chartKey][row] = 0;
                }
                if (c.value > 1) {
                    this.chartBumps[chartKey][row] = 1;
                }
            }
        }

        if (this.charts[chartKey] == undefined) {
            this.charts[chartKey] = new google.visualization.Table(document.getElementById(this.chartDivs['pageAttrs']));
            google.visualization.events.addListener(this.charts[chartKey], 'ready', function () {rtdView.doChartBumps('pageAttrs');});
        }
        if (draw) {
            this.drawPageAttrsReport();
        }
    };

    this.drawPageAttrsReport = function () {
        var valueFormatter = this.valueFormatter();
        valueFormatter.format(this.chartData['pageAttrs'], 3);
        this.charts['pageAttrs'].draw(this.chartData['pageAttrs'], this.drawTableOptions());
    }



    this.buildTsReport = function(statsData, refresh) {
        var chartKey = 'ts';
        var draw = false;
        // check if this report in in another window
        if (rtdConfig.chartWindows[chartKey] != undefined) {
            rtdConfig.chartWindows[chartKey].rtdView.buildTsReport(statsData, refresh);
            return;
        }

        if (!this.chartsEnabled[chartKey]) {
            return;
        }

        if (this.chartData[chartKey] == undefined) {
            this.chartData[chartKey] = new google.visualization.DataTable();
            var headerLabel = 'Traffic source';
            headerLabel += this.getCMSLink('link-ext', this.config.settings.cmsPath + 'admin/reports/intel/trafficsource');
            this.chartData[chartKey].addColumn('string', headerLabel);
            this.chartData[chartKey].addColumn('number', 'Ent');
            this.chartData[chartKey].addColumn('number', 'Pvs');
            this.chartData[chartKey].addColumn('number', 'Val');
            draw = true;
        }


        for (var key in statsData.ts.main) {
            var c = statsData.ts.main[key];

            draw = true;
            if (this.chartIndex[chartKey][key] == undefined) {
                this.chartIndex[chartKey][key] = this.chartData[chartKey].getNumberOfRows();
                this.chartData[chartKey].addRow([key, 0, 0, 0]);
            }
            var row = this.chartIndex.ts[key];

            count = this.chartData[chartKey].getValue(row, 1);
            this.chartData[chartKey].setValue(row, 1, c.entrances + count);

            count = this.chartData[chartKey].getValue(row, 2);
            this.chartData[chartKey].setValue(row, 2, c.pageviews + count);

            count = this.chartData[chartKey].getValue(row, 3);
            this.chartData[chartKey].setValue(row, 3, c.value + count);
            if (!refresh == true) {
                if (c.entrances > 0) {
                    this.chartBumps.ts[row] = 0;
                }
                if (c.value > 1) {
                    this.chartBumps.ts[row] = 1;
                }
            }
        }

        if (this.charts[chartKey] == undefined) {
            this.charts[chartKey] = new google.visualization.Table(document.getElementById(this.chartDivs[chartKey]));
            google.visualization.events.addListener(this.charts[chartKey], 'ready', function () {rtdView.doChartBumps(chartKey);});
        }
        if (draw) {
            var valueFormatter = this.valueFormatter();
            valueFormatter.format(this.chartData[chartKey], 3);

            this.charts[chartKey].draw(this.chartData[chartKey], this.drawTableOptions());
        }
    };

    this.buildTsDetailsReport = function(statsData, type, refresh) {
        var chartKey = 'tsDetails';
        var draw = false;
        // check if this report in in another window
        if (rtdConfig.chartWindows[chartKey] != undefined) {
            rtdConfig.chartWindows[chartKey].rtdView.buildTsDetailsReport(statsData, refresh);
            return;
        }
        if (!this.chartsEnabled[chartKey]) {
            return;
        }

        if (type == undefined) {
            type = (this.chartActive[chartKey].length > 0) ? this.chartActive[chartKey] : this.chartRotation[chartKey][this.chartRotationI[chartKey]];
        }
        if (refresh == true) {
            this.chartData[chartKey] = null;
            this.chartIndex[chartKey] = {};
        }

        if (this.chartRedraw[chartKey] != undefined) {
            draw = true;
            delete this.chartRedraw[chartKey];
        }

        if (this.chartData[chartKey] == undefined) {
            this.chartData[chartKey] = new google.visualization.DataTable();
            this.chartData[chartKey].addColumn('string', 'Traffic source');
            this.chartData[chartKey].addColumn('number', 'Ent');
            this.chartData[chartKey].addColumn('number', 'Pvs');
            this.chartData[chartKey].addColumn('number', 'Val');
            draw = true;
        }
        var labels = {};
        var data = {};

        var headerLabel = 'Traffic source';
        var headerLink = '';

        if (statsData.ts[type[0]] != undefined
        //    && ()
            ) {
            data = statsData.ts[type[0]];
            var lkey = type[0];
            if ((type.length == 2)) {
                data = data[type[1]]._pages;
                lkey += '.' + type[1];
            }
            var lkey = type[0];
            if (this.chartRotationLabels[chartKey][lkey] != undefined) {
                headerLabel = this.chartRotationLabels[chartKey][lkey]

            }

            if (this.chartCmsLinks[chartKey][lkey] != undefined) {
                headerLabel += this.getCMSLink('link-ext', this.config.settings.cmsPath + this.chartCmsLinks[chartKey][lkey]);
            }
            this.chartData[chartKey].setColumnLabel(0, headerLabel);
        }

        for (var key in data) {
            var c = data[key];

            draw = true;
            if (this.chartIndex[chartKey][key] == undefined) {
                this.chartIndex[chartKey][key] = this.chartData[chartKey].getNumberOfRows();
                this.chartData[chartKey].addRow([key, 0, 0, 0]);
            }
            row = this.chartIndex[chartKey][key];

            count = this.chartData[chartKey].getValue(row, 1);
            this.chartData[chartKey].setValue(row, 1, c.entrances + count);

            count = this.chartData[chartKey].getValue(row, 2);
            this.chartData[chartKey].setValue(row, 2, c.pageviews + count);

            count = this.chartData[chartKey].getValue(row, 3);
            this.chartData[chartKey].setValue(row, 3, c.value + count);

            if (!refresh == true) {
                if (c.entrances > 0) {
                    this.chartBumps.tsDetails[row] = 0;
                }
                if (c.value > 1) {
                    this.chartBumps.tsDetails[row] = 1;
                }
            }

        }

        if (this.charts[chartKey] == undefined) {
            this.charts[chartKey] = new google.visualization.Table(document.getElementById(this.chartDivs[chartKey]));
            google.visualization.events.addListener(this.charts[chartKey], 'ready', function () {rtdView.doChartBumps(chartKey);});
        }
        if (draw) {
            var valueFormatter = this.valueFormatter();
            valueFormatter.format(this.chartData[chartKey], 3);
            this.charts[chartKey].draw(this.chartData[chartKey], this.drawTableOptions());
        }
    };

    this.buildEventsReport = function(statsData, refresh) {
        var chartKey = 'events';
        var draw = false;
        // check if this report in in another window
        if (rtdConfig.chartWindows[chartKey] != undefined) {
            rtdConfig.chartWindows[chartKey].rtdView.buildEventsReport(statsData, refresh);
            return;
        }
        if (!this.chartsEnabled[chartKey]) {
            return;
        }

        if (this.chartData[chartKey] == undefined) {
            this.chartData[chartKey] = new google.visualization.DataTable();
            this.chartData[chartKey].addColumn('string', 'Event categories');
            this.chartData[chartKey].addColumn('number', 'Evts');
            this.chartData[chartKey].addColumn('number', 'Val');
            draw = true;
        }

        for (var key in statsData[chartKey]) {
            var c = statsData[chartKey][key];
            draw = true;
            if (this.chartIndex[chartKey][key] == undefined) {
                this.chartIndex[chartKey][key] = this.chartData[chartKey].getNumberOfRows();
                this.chartData[chartKey].addRow([key, 0, 0]);
            }
            var row = this.chartIndex[chartKey][key];

            count = this.chartData[chartKey].getValue(row, 1);
            this.chartData[chartKey].setValue(row, 1, c.events + count);

            count = this.chartData[chartKey].getValue(row, 2);
            this.chartData[chartKey].setValue(row, 2, c.value + count);

            if (refresh != true) {
                if (c.events > 0) {
                    this.chartBumps[chartKey][row] = 0;
                }
                if (c.value > 1) {
                    this.chartBumps[chartKey][row] = 1;
                }
                if (c.value > 10) {
                    this.chartBumps[chartKey][row] = 2;
                }
            }

        }

        if (this.charts[chartKey] == undefined) {
            this.charts[chartKey] = new google.visualization.Table(document.getElementById(this.chartDivs[chartKey]));
            google.visualization.events.addListener(this.charts[chartKey], 'ready', function () {rtdView.doChartBumps(chartKey);});
        }
        if (draw) {
            var valueFormatter = this.valueFormatter();
            valueFormatter.format(this.chartData[chartKey], 2);
            this.charts[chartKey].draw(this.chartData[chartKey], this.drawTableOptions());
        }

    };

    this.buildCtasReport = function(statsData, refresh) {
        var chartKey = 'ctas';
        var draw = false;
        // check if this report in in another window
        if (rtdConfig.chartWindows[chartKey] != undefined) {
            rtdConfig.chartWindows[chartKey].rtdView.buildCtasReport(statsData, refresh);
            return;
        }
        if (!this.chartsEnabled[chartKey]) {
            return;
        }

        if (this.chartData[chartKey] == undefined) {
            this.chartData[chartKey] = new google.visualization.DataTable();
            var headerLabel = 'Calls to action'
            headerLabel += this.getCMSLink('link-ext', this.config.settings.cmsPath + 'admin/content/cta');
            this.chartData[chartKey].addColumn('string', headerLabel);
            this.chartData[chartKey].addColumn('number', 'Imps');
            this.chartData[chartKey].addColumn('number', 'Clks');
            this.chartData[chartKey].addColumn('number', 'Clk%');
            this.chartData[chartKey].addColumn('number', 'Convs');
            this.chartData[chartKey].addColumn('number', 'Conv%');
            draw = true;
        }

        for (var key in statsData[chartKey]) {
            var c = statsData[chartKey][key];
            draw = true;
            if (this.chartIndex[chartKey][key] == undefined) {
                this.chartIndex[chartKey][key] = this.chartData[chartKey].getNumberOfRows();
                this.chartData[chartKey].addRow([key, 0, 0, 0, 0, 0]);
            }
            var row = this.chartIndex[chartKey][key];

            var countA = this.chartData[chartKey].getValue(row, 1) + c.impressions;
            this.chartData[chartKey].setValue(row, 1, countA);

            var countB = this.chartData[chartKey].getValue(row, 2) + c.clicks;
            this.chartData[chartKey].setValue(row, 2, countB);

            var per = (countA != 0) ? 100 * countB/countA : 0;
            this.chartData[chartKey].setValue(row, 3, per);

            var countC = this.chartData[chartKey].getValue(row, 2) + c.conversions;
            this.chartData[chartKey].setValue(row, 4, countC);

            var per = (countB != 0) ? 100 * countC/countB : 0;
            this.chartData[chartKey].setValue(row, 5, per);

            if (refresh != true) {
                //if (c.events > 0) {
                //    this.chartBumps[chartKey][row] = 0;
                //}
                if (c.clicks > 1) {
                    this.chartBumps[chartKey][row] = 1;
                }
                //if (c.value > 10) {
                //    this.chartBumps[chartKey][row] = 2;
                //}
            }

        }

        if (this.charts[chartKey] == undefined) {
            this.charts[chartKey] = new google.visualization.Table(document.getElementById(this.chartDivs[chartKey]));
            google.visualization.events.addListener(this.charts[chartKey], 'ready', function () {rtdView.doChartBumps(chartKey);});
        }
        if (draw) {
            var percentFormatter = this.percentFormatter();
            percentFormatter.format(this.chartData[chartKey], 3);
            this.charts[chartKey].draw(this.chartData[chartKey], this.drawTableOptions());
        }

    };

    this.buildLpsReport = function(statsData, refresh) {
        var chartKey = 'lps';
        var draw = false;
        // check if this report in in another window
        if (rtdConfig.chartWindows[chartKey] != undefined) {
            rtdConfig.chartWindows[chartKey].rtdView.buildLpsReport(statsData, refresh);
            return;
        }
        if (!this.chartsEnabled[chartKey]) {
            return;
        }

        if (this.chartData[chartKey] == undefined) {
            this.chartData[chartKey] = new google.visualization.DataTable();
            var headerLabel = 'Landing pages'
            headerLabel += this.getCMSLink('link-ext', this.config.settings.cmsPath + 'admin/reports/intel/conversion');
            this.chartData[chartKey].addColumn('string', headerLabel);
            this.chartData[chartKey].addColumn('number', 'Views');
            this.chartData[chartKey].addColumn('number', 'Convs');
            this.chartData[chartKey].addColumn('number', 'Conv%');
            draw = true;
        }

        for (var key in statsData[chartKey]) {
            var c = statsData[chartKey][key];
            draw = true;
            if (this.chartIndex[chartKey][key] == undefined) {
                this.chartIndex[chartKey][key] = this.chartData[chartKey].getNumberOfRows();
                this.chartData[chartKey].addRow([key, 0, 0, 0]);
            }
            var row = this.chartIndex[chartKey][key];

            var countA = this.chartData[chartKey].getValue(row, 1) + c.views;
            this.chartData[chartKey].setValue(row, 1, countA);

            var countB = this.chartData[chartKey].getValue(row, 2) + c.conversions;
            this.chartData[chartKey].setValue(row, 2, countB);

            var per = (countA != 0) ? 100 * countB/countA : 0;
            this.chartData[chartKey].setValue(row, 3, per);

            if (refresh != true) {
                if (c.views > 0) {
                    this.chartBumps[chartKey][row] = 0;
                }
                if (c.conversions > 1) {
                    this.chartBumps[chartKey][row] = 2;
                }
                //if (c.value > 10) {
                //    this.chartBumps[chartKey][row] = 2;
                //}
            }

        }

        if (this.charts[chartKey] == undefined) {
            this.charts[chartKey] = new google.visualization.Table(document.getElementById(this.chartDivs[chartKey]));
            google.visualization.events.addListener(this.charts[chartKey], 'ready', function () {rtdView.doChartBumps(chartKey);});
        }
        if (draw) {
            var percentFormatter = this.percentFormatter();
            percentFormatter.format(this.chartData[chartKey], 3);
            this.charts[chartKey].draw(this.chartData[chartKey], this.drawTableOptions());
        }

    };

    this.buildEventDetailsReport = function(statsData, type, refresh) {
        var chartKey = 'eventDetails';
        var draw = false;
        // check if this report in in another window
        if (rtdConfig.chartWindows[chartKey] != undefined) {
            rtdConfig.chartWindows[chartKey].rtdView.buildEventDetailsReport(statsData, refresh);
            return;
        }
        if (!this.chartsEnabled[chartKey]) {
            return;
        }

        if (type == undefined) {
            type = (this.chartActive[chartKey].length > 0) ? this.chartActive[chartKey] : this.chartRotation[chartKey][this.chartRotationI[chartKey]];
        }
        if (refresh == true) {
            this.chartData[chartKey] = null;
            this.chartIndex[chartKey] = {};
            draw = true;
        }

        if (this.chartData[chartKey] == undefined) {
            this.chartData[chartKey] = new google.visualization.DataTable();
            this.chartData[chartKey].addColumn('string', 'Events');
            this.chartData[chartKey].addColumn('number', 'Evts');
            this.chartData[chartKey].addColumn('number', 'Val');
            draw = true;
        }
        var labels = {};
        var data = {};
        if (statsData.events[type[0]] != undefined) {
            data = statsData.events[type[0]];
            if (data != undefined) {
                if ((type.length == 2)) {
                    if (type[1] == 'PAGES') {
                        data = data.pages;
                    }
                    else {
                        if (data.details[type[1]] != undefined) {
                            data = data.details[type[1]].pages;
                        }
                        else {
                            data = {};
                        }
                    }
                }
                else {
                    data = data.details;
                }

            }
        }

        for (var key in data) {
            var c = data[key];

            draw = true;

            if (this.chartIndex[chartKey][key] == undefined) {
                this.chartIndex[chartKey][key] = this.chartData[chartKey].getNumberOfRows();
                this.chartData[chartKey].addRow([key, 0, 0]);
            }
            row = this.chartIndex[chartKey][key];

            count = this.chartData[chartKey].getValue(row, 1);
            this.chartData[chartKey].setValue(row, 1, c.events + count);

            count = this.chartData[chartKey].getValue(row, 2);
            this.chartData[chartKey].setValue(row, 2, c.value + count);

            if (!refresh == true) {
                if (c.entrances > 0) {
                    this.chartBumps[chartKey][row] = 0;
                }
                if (c.value > 1) {
                    this.chartBumps[chartKey][row] = 1;
                }
            }

        }

        // create table header label
        if (draw) {
            var lkeys = [];
            var headerLabel = 'Events';
            if (type.length == 1) {
                lkeys.push(type[0]);
            }
            else if (type.length == 2) {
                lkeys.push(type[0] + '.' + type[1]);
                lkeys.push(type[0] + '.DEFAULT');
            }
            for (var i = 0; i < lkeys.length; i++) {
                if (this.chartRotationLabels[chartKey][lkeys[i]] != undefined) {
                    headerLabel = this.chartRotationLabels[chartKey][lkeys[i]];
                    break;
                }
            }
            if (type.length > 0) {
                headerLabel = headerLabel.replace("%type", type[0]);
            }
            if (type.length == 2) {
                headerLabel = headerLabel.replace("%subtype", type[1]);
            }

            this.chartData[chartKey].setColumnLabel(0, headerLabel);

        }

        if (this.charts[chartKey] == undefined) {
            this.charts[chartKey] = new google.visualization.Table(document.getElementById(this.chartDivs[chartKey]));
            google.visualization.events.addListener(this.charts[chartKey], 'ready', function () {rtdView.doChartBumps(chartKey);});
        }
        if (draw) {
            var valueFormatter = this.valueFormatter();
            valueFormatter.format(this.chartData[chartKey], 2);
            this.charts[chartKey].draw(this.chartData[chartKey], this.drawTableOptions());
        }
    };

    this.buildVisitorsReport = function(statsData, refresh) {
//console.log(statsData.visitors);
        var chartKey = 'visitors';

        // check if this report in in another window
        if (rtdConfig.chartWindows[chartKey] != undefined) {
            rtdConfig.chartWindows[chartKey].rtdView.buildVisitorsReport(statsData, refresh);
            return;
        }
        if (!this.chartsEnabled[chartKey]) {
            return;
        }

        var indexes = {
            lastHit: 7,
            pageviews:4,
            eventsgoals: 5,
            sessions: 3,
            value: 6,
            page: 2
        }
        var labels = {
            lastHit: 'Last',
            pageviews: 'Pgs',
            eventsgoals: 'Gls',
            sessions: 'Ses',
            value: 'Value',
            page: ''
        }
        if (this.chartData[chartKey] == undefined) {
            this.chartData[chartKey] = new google.visualization.DataTable();
            this.chartData[chartKey].addColumn('string', '');
            this.chartData[chartKey].addColumn('string', 'Visitors');
            this.chartData[chartKey].addColumn('number', '');
            this.chartData[chartKey].addColumn('number', '');
            this.chartData[chartKey].addColumn('number', '');
            this.chartData[chartKey].addColumn('number', '');
            this.chartData[chartKey].insertColumn(indexes['page'], 'string', '');
            this.chartData[chartKey].insertColumn(indexes['lastHit'], 'date', '');

            for (var j in indexes) {
                this.chartData[chartKey].setColumnLabel(indexes[j], labels[j]);
            }
        }

        var draw = true;
        var newVisitorData = {};
        var count;
        // update stats changes, e.g. pageviews and value.
        // time based updates done in loop below this one
        for (var key in statsData.visitors) {
           // if (!statsData.visitor.hasOwnProperty(key)


            var c = statsData.visitors[key];
//console.log(c);
            var visitor = (this.model.visitors[key] != undefined) ? this.model.visitors[key] : [];
//console.log(visitor);
            var sid = visitor.activeSession;
            var session = this.model.sessions[key + '.' + sid];
            var lastPage = session.hits[session.last];
            lastPage = this.model.log[session.last][lastPage.pageLEI];

            // if this visitor is the activeVisitor, set new...Data flag
            if (key == this.activeVisitor) {
                newActiveVisitorData = true;
            }

            draw = true;
            if (this.chartIndex[chartKey][key] == undefined) {
                this.chartIndex[chartKey][key] = this.chartData[chartKey].getNumberOfRows();
                var img = '';
                if (visitor.image == undefined) {
                    img += '<span class="placeholder-visitor placeholder-visitor-' + key + '-image">';
                    //img += '<img src="' + this.config.settings.defaultVisitorImg + '">';
                    img += '<img src="' + this.config.settings.imgPath + '/trans.gif" class="visitor-image-default">';
                    img += '</span>';
                }
                else {
                    //img = '<img src="' + visitor.image + '">';
                    img += '<img src="' + this.config.settings.imgPath + '/trans.gif" class="visitor-image-' + key + '">';


                    //img = visitor.image;
                }
                var label =  visitor.name

                if (visitor.name.substr(0, 5) == 'anon ') {
                    label = '<span class="placeholder-visitor placeholder-visitor-' + key + '-name">' + label + '</span>';
                }
                label += this.getCMSLink('link-ext', this.config.settings.cmsPath + 'visitor/' + key);
                var newRow = [img, label, 0, 0, 0, 0, 0, 0];
                newRow[indexes['lastHit']] = new Date();
                newRow[indexes['page']] = '';
//console.log(newRow);
                this.chartData[chartKey].addRow(newRow);
                // if no activeVisitor has been set, set it to the first row
                if (this.activeVisitor == -1) {
                    this.activeVisitor = 0;
                }
            }
            var row = this.chartIndex[chartKey][key];
            // track which visitors have been updated
            newVisitorData[key] = row;

            //this.chartData[chartKey].setValue(row, indexes['lastHit'], dateTimeFromLastHit);

            //count = this.chartData[chartKey].getValue(row, indexes['pageviews']);
            this.chartData[chartKey].setValue(row, indexes['pageviews'], session.pageviews);

            this.chartData[chartKey].setValue(row, indexes['eventsgoals'], (session.valuedEvents + session.goals));

            //count = this.chartData[chartKey].getValue(row, indexes['sessions']);
            this.chartData[chartKey].setValue(row, indexes['sessions'], sid);

            count = this.chartData[chartKey].getValue(row, indexes['value']);
            this.chartData[chartKey].setValue(row, indexes['value'], c.value + count);

            if ((lastPage != undefined) && (lastPage.p != undefined)) {
                var label = '<span class="pageWrapper">' + this.capstr(lastPage.p, 36) +  '</span>';
                this.chartData[chartKey].setValue(row, indexes['page'], label);
            }

            if (!refresh == true) {
                if (c.pageviews > 0) {
                    this.chartBumps.visitors[row] = 0;
                }
                if (c.value > 1) {
                    this.chartBumps.visitors[row] = 1;
                }
            }

        }

        // update time since last hit
        var rows = this.chartData[chartKey].getNumberOfRows();
        var rowAdjust = 0;
        var curTime = this.model.getTime();
        for (var key in this.chartIndex[chartKey]) {
            if (rowAdjust != 0) {
                this.chartIndex[chartKey][key] += rowAdjust;
            }
            var row = this.chartIndex[chartKey][key];
            var visitor = this.model.visitors[key];
            var sid = visitor.activeSession;
            var session = this.model.sessions[key + '.' + sid];
            var timeFromLastHit = (curTime - session.last);
//console.log(session);
//console.log(timeFromLastHit);

            // remove visitors without a hit in last 30 mins
            if (timeFromLastHit > 1800) {
                this.chartData[chartKey].removeRow(row);
                delete this.chartIndex[chartKey][key];
                rowAdjust--;
            }
            else {
                this.chartData[chartKey].setValue(row, indexes['lastHit'], new Date(1000 * timeFromLastHit));
            }
        }

        //console.log(this.chartData[chartKey].toJSON());

        if (this.charts[chartKey] == undefined) {
            this.charts[chartKey] = new google.visualization.Table(document.getElementById(this.chartDivs[chartKey]));
            google.visualization.events.addListener(this.charts[chartKey], 'ready', function () {rtdView.doChartBumps(chartKey);});
            google.visualization.events.addListener(this.charts[chartKey], 'sort', function (data) {rtdView.onChartSort(chartKey, data);});
            google.visualization.events.addListener(this.charts[chartKey], 'select', function () {rtdView.onChartSelect(chartKey);});
        }
        if (draw) {
            this.drawVisitorsReport(newVisitorData);
        }
    };

    this.onChartSelect = function (chartKey) {
        var selection = this.charts[chartKey].getSelection();
        if (selection.length == 0) {
            this.chartSelections[chartKey] = null;
            if (chartKey == 'visitors') {
                this.selectedVisitor = null;
            }
        }
        else {
            this.chartSelections[chartKey] = selection;
            if (chartKey == 'visitors') {
                this.selectedVisitor = arraySearch(selection[0].row, this.chartIndex[chartKey]);
                if ((this.selectedVisitor != false) && (this.activeVisitor != this.selectedVisitor)) {
                    this.activeVisitor = this.selectedVisitor;
                    this.buildTimeline(true);
                    this.buildVisitorDetailsReport(true);
                }
            }
        }
    }

    this.drawVisitorsReport = function (newVisitorData) {
//console.log(newVisitorData);
        var chartKey = 'visitors';
        var valueFormatter = this.valueFormatter();
        valueFormatter.format(this.chartData[chartKey], 6);

        var timeFormatter = this.timeMSFormatter();
        timeFormatter.format(this.chartData[chartKey], 7);

        var options = this.drawTableOptions();
        if (this.chartSorts[chartKey] == null) {
            this.chartSorts[chartKey] = {
                column: 7,
                ascending: true
            }
        }
        options = this.setSortOptions(chartKey, options);

        options.showRowNumber = false;
        // this table has custom styling so change table row classes
        options.cssClassNames.tableRow = 'table-row-av table-row-even';
        options.cssClassNames.oddTableRow = 'table-row-av table-row-odd';
        options.cssClassNames.selectedTableRow = 'table-row-av table-row-selected';
        this.charts[chartKey].draw(this.chartData[chartKey], options);

        // determine if activeVisitor needs to be changed and if timeline needs
        // to be updated.
        if (this.selectedVisitor != null) {
          if (newVisitorData[this.selectedVisitor]) {
              this.activeVisitor = this.selectedVisitor;
              this.buildTimeline();
          }
        }
        else {
           var sort = this.charts[chartKey].getSortInfo();
           if (sort.sortedIndexes == null) {
             return;
           }
           var topVisitor = arraySearch(sort.sortedIndexes[0], this.chartIndex[chartKey]);
           if (topVisitor != false) {
             if (topVisitor != this.activeVisitor) {
                 this.activeVisitor = topVisitor;
                 this.buildTimeline(true);
                 this.buildVisitorDetailsReport(true);
             }
             else if (newVisitorData[this.activeVisitor] != undefined) {
                 this.buildTimeline();
                 this.buildVisitorDetailsReport();
             }
           }
        }

        // select active visitor to show details for
        // if the user has selected a visitor, use that one, otherwise select
        // top visitor based on current table sort

        /*

        var activeUser = false;
        if (this.chartSelections[chartKey] != undefined) {
            activeUser = arraySearch(this.chartSelections[chartKey], this.chartIndex[chartKey]);
        }
        if () {

        }
           selection = this.charts['visitors'].getSelection();
        }
        if (userSelected != false) {

        }
        console.log(userSelected);
        console.log(selection);
        */

    };

    this.timelineNeedsIndexing = false;
    this.timelineData = {}

    this.buildTimeline = function buildTimeline(refresh) {
        var chartKey = 'visitorTimeline';

        // check if this report in in another window
        if (rtdConfig.chartWindows[chartKey] != undefined) {
            rtdConfig.chartWindows[chartKey].rtdView.buildTimeline(statsData, refresh);
            return;
        }
        if (!this.chartsEnabled[chartKey]) {
            return;
        }

        var curTime = this.model.getTime();
        var vtk = this.activeVisitor;
        if (vtk == '') {
            return;
        }
        var visitor = this.model.visitors[vtk];
        var sid = visitor.activeSession;
        var session = this.model.sessions[vtk + '.' + sid];
        var placeholders = 0;

        this.timelineData = {
            //headline: visitor.name + " Clickstream",
            type: "default",
            //text: "Intro body text goes here",
            //startDate: this.formatTimelineDate(curTime - 1800),
            //endDate: this.formatTimelineDate(curTime),
            date: []
        };

        for (var ht in session.hits) {
            var hits = session.hits[ht];
            var time = this.formatTimelineDate(ht);
            var events = [];
            var page = ''
            var headline = '';
            var text = '';
            var tag = 'events';
            var media = '';
            var caption = '';
            var classname = 'pageview';
            if (hits.pageLEI != undefined) {
                tag = 'pages';
                page = this.model.log[ht][hits.pageLEI];
            }
            for (var i = 0; i < hits.events.length; i++) {
                var event = this.model.log[ht][hits.events[i].LEI];
                events.push(event);
            }
            var paItems = [];
            if (tag == 'pages') {
                headline = page.dt;
                //text = 'value: +' + hits.value.toFixed(2);
                text = '<span class="hit-value">value: +' + hits.value.toFixed(2) + '</span>';
                text += '<div class="body">';
                if (page.ie == 1) {
                    classname = 'entrance';
                }
                for (var i in page.pa) {

                   if (this.model.attrInfo['page'][i] != undefined) {
                       var value = '';
                       if (this.model.attrInfo['page'][i].type == 'list') {
                           for (var j in page.pa[i]) {
                               value += (value.length > 0 ? ', ' : '');
                               if (this.model.attrInfo['page'][i].options[j] == undefined) {
                                   value += '<span class="placeholder-attr-option placeholder-pa-' + i + '-' + j + '">' + j + '</span>';
                                   placeholders++;
                                   this.model.fetchAttributeOptionInfo('page', i, j, rtdView.updateAttributeOptionInfo);
                                   //this.model.fetchPaInfoOption(i, j, rtdView.updatePaInfoOption, 500);
                               }
                               else {
                                    value += this.model.attrInfo['page'][i].options[j].title;
                               }
                           }
                       }
                       else {
                           if (this.model.attrInfo['page'][i].options[page.pa[i]] == undefined) {
                               value += '<span class="placeholder-attr-option placeholder-pa-' + i + '-' + page.pa[i] + '">' + page.pa[i] + '</span>';
                               placeholders++;
                               this.model.fetchAttributeOptionInfo('page', i, page.pa[i], rtdView.updateAttributeOptionInfo);
                           }
                           else {
                               value += this.model.attrInfo['page'][i].options[page.pa[i]].title;
                           }
                       }

                       if (value.length > 0) {
                           paItems.push('<label class="timeline page-attr">' + this.model.attrInfo['page'][i].title + '</label>: ' + value);
                       }
                   }
                }
                var options = {
                    title: 'Page attributes:',
                    listClass: 'pa'
                }
                text += this.themeItemList(paItems, options);

                media = "http://" + page.h + page.p;
                caption = page.p;

                if (page.va != undefined) {
                    var options = {
                        exclude: {s: true },
                        delta: true,
                        labelClass: 'timeline visitor-attr'
                    };
                    var vaItems = this.getVAItems(page.va, options);
                    var options = {
                        title: 'Visitor attributes:',
                        listClass: 'va',

                    }
                    text += '<p>' + this.themeItemList(vaItems, options) + '</p>';
                }

                var items = [];
                for(var j = 0; j < events.length; j++) {
                    var event = events[j];
                    if ((event.ec.substr(-1) != '!') && (event.ec.substr(-1) != '+')) {
                        items.push('<label class="timeline event">' + event.ec + '</label>: ' + event.ea);
                    }
                }

                if (items.length > 0) {
                    var options = {
                        title: 'Events:',
                        listClass: 'events'
                    }
                    text += '<p>' + this.themeItemList(items, options) + '</p>';
                }

                text += '</div>';


                this.timelineData.date.push({
                    startDate: time,
                    endDate: time,
                    headline: headline,
                    text: text,
                    tag: 'page',
                    classname: classname,
                    asset: {
                        media: media,
                        caption: caption
                    }
                });
            }
            for(var j = 0; j < events.length; j++) {
                var event = events[j];
                if ((events[j].ec.substr(-1) == '!') || (events[j].ec.substr(-1) == '+')) {
                    headline = events[j].ec;
                    text = '<span class="hit-value">value: +' + event.ev.toFixed(2) + '</span>';
                    text += '<div class="body">';
                    var items = [];
                    text += '<h3>Resource:</h3>';
                    text += '<p>' + events[j].ea + '</p>';

                    text += '<h3>Resource id:</h3>';
                    text += '<p>' + '<a href="/' + events[j].el + '" target="cms">' + events[j].el + '</a>' + '</p>';
                    text += '</div>';
                    media = "http://" + events[j].h + events[j].p;
                    classname = 'event';
                    if (events[j].ec.substr(-1) == '!') {
                        classname = 'valuedevent';
                    }
                    else if (events[j].ec.substr(-1) == '+') {
                        classname = 'goal';
                    }
                    media = "http://" + events[j].h + events[j].p;
                    caption = events[j].p;

                    this.timelineData.date.push({
                        startDate: time,
                        endDate: time,
                        headline: headline,
                        text: text,
                        tag: 'event',
                        classname: classname,
                        asset: {
                            media: media,
                            caption: caption
                        }
                    });
                }
                else {
                    continue;
                }
            }



            lastHit = ht;
        }

        // add start marker
        if ((session.last - session.start) < 900) {
            this.timelineData.date.push({
                startDate: this.formatTimelineDate(lastHit - 900),
                endDate: this.formatTimelineDate(lastHit - 900),
                headline: '-15 mins',
                classname: 'timemarker'
            });
        }
        /*
        this.timelineData.era = [{
            startDate: this.formatTimelineDate(lastHit - 1800),
            endDate: this.formatTimelineDate(lastHit),
            headline: 'Session'
        }];
        */



        //if (this.timelineInitialized) {
            //VMM.Timeline.build = function () {
            //    console.log('HI3                       HI3');
            //}
            VMM.Timeline.Config.source.timeline = this.timelineData;

            VMM.Timeline.Config.placeholders = placeholders;
            VMM.Timeline.Config.customUpdate = true;
            VMM.Timeline.Config.customRefresh = refresh;

            //VMM.Timeline.Config.current_slide = this.timelineData.date.length;
//console.log("current_slide=" + VMM.Timeline.Config.current_slide);
            //VMM.Timeline.Config.start_at_slide = this.timelineData.date.length-1;
//console.log("current_slide=" + VMM.Timeline.Config.current_slide + ", start_at_slide=" + VMM.Timeline.Config.start_at_slide);
            //VMM.Timeline.Config.current_slide = timelineData.date.length;
            //VMM.fireEvent(global, VMM.Timeline.Config.events.data_ready, VMM.Timeline.Config.source);
            VMM.fireEvent(global, VMM.Timeline.Config.events.data_ready, {timeline: this.timelineData});

            //VMM.Slider.setSlide(0);
            //createStimenav.setMarker(0, config.ease,config.duration);

            //VMM.Timeline.Config.duration = 1000;
            //VMM.Timeline.Config.ease = "easeInOutExpo";  // "easeInOutExpo"

            //VMM.Timeline.Config.current_slide = timelineData.date.length - 1;


            //VMM.fireEvent(global, VMM.Timeline.Config.events.slide_change, timelineData.date.length);

            //VMM.Timeline.Config.duration = 0;

            //VMM.Timeline.Config.current_slide = timelineData.date.length;
            //VMM.Timeline.Config.nav.height = 100;

            //VMM.Timeline.Config.nav.rows.current = [1, 1, 1];

            //VMM.fireEvent(global, VMM.Timeline.Config.events.data_ready, VMM.Timeline.Config.source );
            //VMM.fireEvent(global, VMM.Timeline.Config.events.slide_change, timelineData.date.length);

            return;
        //}

        var data = {
            type:       'timeline',
            width:      $('#visitor-timeline').width()+2,
            height:     $('#visitor-timeline').height()+16,
            //height:     $('#timeline-report').height()+16+100,
            source:     {timeline: this.timelineData},
            embed_id:   'visitor-timeline',
            //hash_bookmark: true,
            start_at_slide: this.timelineData.date.length-1,
            start_zoom_adjust:  '0',
            //start_at_end: true,
            debug: false

        };
        console.log(data);
        timeline = createStoryJS(data);
        //VMM.Timeline.Config.nav.height = 150;
        //VMM.fireEvent(global, VMM.Timeline.Config.events.data_ready, data.source);
        //VMM.Timeline.Config.nav.rows.current = [1, 51, 1];
        //timeline = new VMM.Timeline('storyjs-timeline');
        //timeline.init(data);
        //VMM.bindEvent(buildTimeline2);
        //VMM.bindEvent(global, rtdView.timelineOnDataReady, "DATAREADY");
        VMM.Timeline.build = function () {
            console.log('HI3                       HI3');
        }

    };

    this.onTimelineDataReady = function (that, e, d) {
//console.log('this.onTimelineDataReady');
//console.log(that);
//console.log(this);
//console.log(e);
//console.log(d);
    };

    this.onTimelineLoaded = function (that, e) {
//console.log('this.onTimelineLoaded');
//console.log(that);
//console.log(e);
//console.log(VMM.Timeline.Config);

        // pause to wait for slides to render
        if ((VMM.Timeline.Config.placeholders != undefined) && (VMM.Timeline.Config.placeholders > 0)) {
            setTimeout(function(){
                rtdView.updateTimelinePlacehoders();
                VMM.Timeline.Config.placeholders = 0;
            }, 2000);
        }
        //this.updateTimelinePlacehoders();

        return;
        if (this.timelineNeedsIndexing) {
            //console.log('this.onTimelineUpdate');
            VMM.Timeline.Config.duration = 500;
            VMM.Timeline.Config.ease = "linear";  // "easeInOutExpo"
            var count = this.timelineData.date.length-1 - VMM.Timeline.Config.current_slide
            for (var i = 0; i <= count; i++) {
                setTimeout(function(){jQuery('#visitor-timeline .nav-next').click()}, i * 500);
            }
            this.timelineNeedsIndexing = false;
        }
    };

    this.onTimelineUpdate = function (that, e) {
        rtdView.updateTimelinePlacehoders();
    };

    this.updateTimelinePlacehoders = function () {
        $objs = $('#visitor-timeline .placeholder-attr-option');
        $objs.each( function( index, element ) {
            var $element = $(element);
            var c = $element.attr('class');
            c = c.split(' ');
            var theClass = '';
            for (var i = 0; i < c.length; i++) {
                var d = c[i].split('-');
                // look for class in correct format
                if (d[0] == 'placeholder' && ((d[1] == 'va') || (d[1] == 'pa'))) {
                    var mode = (d[1] == 'va') ? 'visitor' : 'page';
                    var attrKey = d[2];
                    var optionId = d[3];
                    if (rtdView.model.attrInfo[mode] != undefined
                       && rtdView.model.attrInfo[mode][attrKey] != undefined
                       && rtdView.model.attrInfo[mode][attrKey].options[optionId] != undefined
                       && rtdView.model.attrInfo[mode][attrKey].options[optionId].title != undefined
                    ) {
                       $element.replaceWith(rtdView.model.attrInfo[mode][attrKey].options[optionId].title);
                    }
                }
            }
        });
    };

    this.getVAItems = function (va, options) {
        if (options == undefined) {
            options = {};
        }

        var labelClass = (options.labelClass != undefined) ? ' class="' + options.labelClass + '"' : '';

        var vaItems = [];
        for (var vaKey in va) {
//console.log(va);
//console.log(options);
//console.log(this.model.attrInfo['visitor']);
            // if attrInfo['visitor'] not avaialble, skip item
            if (this.model.attrInfo['visitor'][vaKey] == undefined) {
              continue;
            }
            // if include is set, skip any not included
            if ((options.include != undefined) && ((options.include[vaKey] == undefined) || (options.include[vaKey] == false))) {
                continue;
            }
            // skip any excluded items
            if ((options.exclude != undefined) && (options.exclude[vaKey] == true)) {
                continue;
            }
            var vaInfo = this.model.attrInfo['visitor'][vaKey];
//console.log(vaInfo);
            var vaValue = va[vaKey];

            if (vaInfo.type == 'flag') {
                var item = ((options.delta == true) ? '+' : '') + vaInfo.title;
                vaItems.push(item);
            }
            else if (vaInfo.type == 'scalar') {
                var item = '<label' + labelClass + '>' + vaInfo.title + '</label>: ';
                if (options.delta == true) {
                    item += ((vaValue >= 0) ? '+' : '');
                }
                item += vaValue
                vaItems.push(item);
            }
            if ((vaInfo.type == 'list') || (vaInfo.type == 'vector')) {
                var item = '<label' + labelClass + '>' + vaInfo.title + '</label>: ';
                if ((options['isSingular'] == true)) {
                    item = '';
                }
                var c = 0;
                if (vaInfo.type == 'vector') {
                    vaValue = sortObject(vaValue, -1);
                }
                for (var i in vaValue) {
                    if (vaInfo.type == 'vector') {
                        // vaValue was transformed into array via search function
                        if (!vaValue.hasOwnProperty(i)) {
                            continue;
                        }
                        var ov = vaValue[i].value;
                        var ok = vaValue[i].key;

                        item += ((c>0) ? ', ' : '');
                        if (vaInfo.options[ok] == undefined) {
                            item += '<span class="placeholder-attr-option placeholder-va-' + vaKey + '-' + ok + '">' + ok + '</span>: ';
                            this.model.fetchAttributeOptionInfo('visitor', vaKey, ok, rtdView.updateAttributeOptionInfo);
                        }
                        else {
                            if ((options['isSingular'] == true)) {
                                item += '<label' + labelClass + '>' + vaInfo.options[ok].title + '</label>: ';
                            }
                            else {
                                item += vaInfo.options[ok].title + ': ';
                            }
                        }

                        if (options.delta == true) {
                            item += ((ov >= 0) ? '+' : '');
                        }
                        item += ov;
                        if ((options['isSingular'] == true)) {
                            vaItems.push(item);
                            item = '';
                            c = -1;
                        }
                    }
                    else {
                        item += ((c>0) ? ', ' : '');
                        if (options.delta == true) {
                            item += '+';
                        }
                        item += vaInfo.options[i].title;

                    }

                    c++;
                }
                if ((options['isSingular'] != true)) {
                  vaItems.push(item);
                }
            }
        }
//console.log(va);
//console.log(vaItems);
        return vaItems;
    }

    this.formatTimelineDate = function formatTimelineDate(time) {
        var d = new Date(1000 * parseInt(time));
        //var time = d.getFullYear() + ',' + (d.getMonth()+1) + ',' + d.getDate() + ' ';
        var time =  (d.getMonth()+1) + '/' + d.getDate() + '/' + d.getFullYear()  + ' ';
        var t = d.getHours();
        time += ((''+t).length<2 ? '0' : '') + t;
        t = d.getMinutes();
        time += ':' + ((''+t).length<2 ? '0' : '') + t;
        t = d.getSeconds();
        time += ':' + ((''+t).length<2 ? '0' : '') + t;
        return time;
    };

    this.buildVisitorDetailsReport = function buildVisitorDetailsReport(refresh) {
        var chartKey = 'visitorDetails';

        // check if this report in in another window
        if (rtdConfig.chartWindows[chartKey] != undefined) {
            rtdConfig.chartWindows[chartKey].rtdView.buildVisitorDetailsReport(statsData, refresh);
            return;
        }
        if (!this.chartsEnabled[chartKey]) {
            return;
        }


        var curTime = this.model.getTime();
        var vtk = this.activeVisitor;
        if (vtk == '') {
            return;
        }
        var visitor = this.model.visitors[vtk];
        var sid = visitor.activeSession;
        var session = this.model.sessions[vtk + '.' + sid];

        var options = {
            exclude: {
                i: true,
                j: true
            }
        };
        var vaItems = this.getVAItems(visitor.va, options);

        var options = {
            //title: 'Visitor attributes:',
            listClass: 'va'
        }
        text = this.themeItemList(vaItems, options);

        $('#visitor-details-value-attrs').html(text);



        var options = {
            include: {
                j: true
            },
            isSingular: true
        };
        var vaItems = this.getVAItems(visitor.va, options);
        var options = {
            listClass: 'va-interests'
        }
        text = this.themeItemList(vaItems, options);
        $('#visitor-details-value-interests').html(text);

        /*

        text = '';
        var attrKey = 'j'
        if (visitor.va[attrKey] != undefined) {
            var items = [];
            for (var key in visitor.va[attrKey]) {
                var item = '';
                if (this.model.attrInfo['visitor'][attrKey].options[key] == undefined) {
                    item += '<span class="placeholder-attr-option placeholder-va-' + attrKey + '-' + key + '">' + key + '</span>';
                    this.model.fetchAttributeOptionInfo('visitor', attrKey, key, rtdView.updateAttributeOptionInfo);
                }
                else {
                    item += this.model.attrInfo['visitor'][attrKey].options[key].title;
                }
                item += ': ' + visitor.va[attrKey][key];
                items.push(item);
                //items.push(this.model.attrInfo['visitor'].j.options[key].title + ': ' + visitor.va.j[key]);
            }
            options.listClass = 'va-interests';
            text = this.themeItemList(items, options);
        }
        $('#visitor-details-value-interests').html(text);
*/


        if (visitor.sharing != undefined) {
            var items = [];
            for (var key in visitor.sharing) {
                var item = '';
                if (this.sharingLabels[key] == undefined) {
                    continue;
                }
                item += this.sharingLabels[key] + ': ' + visitor.sharing[key];
                items.push(item);
                //items.push(this.model.attrInfo['visitor'].j.options[key].title + ': ' + visitor.va.j[key]);
            }
            options.listClass = 'visitor-sharing';
            text = this.themeItemList(items, options);
        }
        else {
            text = '(not provided)';
        }
        $('#visitor-details-value-sharing').html(text);

        if (visitor.location != undefined
            && visitor.location.lat != undefined
            ) {
            if (this.activeMap != visitor.vtk) {
                this.activeMap = visitor.vtk;
                this.buildVisitorLocation(visitor.location);
            }
        }
        else {
            this.activeMap = '';
            $('#visitor-details-value-location').html('(not provided)');
        }

    };

    this.sharingLabels = {
        facebook: 'Facebook',
        google_plusone_share: 'Google+',
        linkedin: 'LinkedIn',
        twitter: 'Twitter',
    }

    this.activeMap = '';
    this.buildVisitorLocation = function (location) {
        location.lat = parseFloat(location.lat);
        location.lon = parseFloat(location.lon);

        var mapOptions = {
            zoom: 4,
            center: new google.maps.LatLng(location.lat, location.lon),
	        disableDefaultUI: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
	    };

        var map = new google.maps.Map(document.getElementById('visitor-details-value-location'), mapOptions);

        var circleOptions = {
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 1,
            fillColor: '#FF0000',
            fillOpacity: 0.35,
            map: map,
            center: new google.maps.LatLng(location.lat, location.lon),
            radius: 50000
        }
        locCircle = new google.maps.Circle(circleOptions);
    }

    this.themeItemList = function (items, options) {
        var text = '';
        if (!(items instanceof Array) || (items.length == 0)) {
          return text;
        }
        if (options.listType == undefined) {
            options.listType = 'ul';
        }
        if (options.listClass == undefined) {
            options.listClass = '';
        }

        if ((options.title != undefined) && (options.title.length > 0)) {
            text += '<h3>' + options.title + '</h3>';
        }

        text += '<' + options.listType + ' class="' + options.listClass + '">';
        for (var i = 0; i < items.length; i++) {
            text += '<li>' + items[i] + '</li>';
        }
        text += '</' + options.listType + '>';

        return text;
    }

    this.onChartSort = function (chartKey, data) {
        this.chartSorts[chartKey] = data;
    };

    this.setSortOptions = function (chartKey, options) {
        options.sortColumn = this.chartSorts[chartKey].column;
        options.sortAscending = this.chartSorts[chartKey].ascending;
        return options;
    };


    this.rotateReports = function() {
        if (this.rotationCount < 3) {
          this.rotationCount++;
          return;
        }
        this.rotationCount = 0;

      // algo processing to stagger chart rotation
      this.chartRotationNext++;
      var chartKeys = Object.keys(this.chartRotationI);
      if (this.chartRotationNext >= chartKeys.length) {
          this.chartRotationNext = 0;
      }
      var types = {};
      types[chartKeys[this.chartRotationNext]] = [];

      for (var key in types) {
          this.chartRotationI[key]++;

          if (this.chartRotation[key][this.chartRotationI[key]] == undefined) {
              this.chartRotationI[key] = 0;
          }
          types[key] = this.chartRotation[key][this.chartRotationI[key]];

          this.rotateReport(key, types[key], this.chartRotationI[key]);
          /*
          var $pane = jQuery('#' + this.chartDivs[key]).parent();
          var width = jQuery('#' + this.chartDivs[key]).parent().width() + 'px';

          jQuery('#' + this.chartDivs[key]).parent().animate({opacity: '0.1'}, 500, function () {
              var chartKey = $('.chart', this).attr('data-chart-key');
              var func = 'build' + String(chartKey).ucfirst() + 'Report';
//console.log(key);
//console.log(types[key]);
//console.log(func);

              rtdView[func](rtdView.model.stats, types[chartKey], true);
              $(this).animate({opacity: '1'}, 500);
          });
          */
      }

    };

    this.rotateReport = function (chartKey, chartType, chartRotationI) {
        // check if the report is in this screen
        if (rtdConfig.chartWindows[chartKey] != undefined) {
            rtdConfig.chartWindows[chartKey].rtdView.rotateReport(chartKey, chartType, chartRotationI) ;
            return;
        }
        if (!this.chartsEnabled[chartKey]) {
            return;
        }
        // this is used to set the chartRotationIndex locally if in multi screen mode
        this.chartRotationI[chartKey] = chartRotationI;

        var $pane = jQuery('#' + this.chartDivs[chartKey]).parent();
        var width = jQuery('#' + this.chartDivs[chartKey]).parent().width() + 'px';

        jQuery('#' + this.chartDivs[chartKey]).parent().animate({opacity: '0.1'}, 500, function () {
            var chartKey = $('.chart', this).attr('data-chart-key');
            var func = 'build' + String(chartKey).ucfirst() + 'Report';
//console.log(key);
//console.log(types[key]);
//console.log(func);

            rtdView[func](rtdView.model.stats, chartType, true);
            $(this).animate({opacity: '1'}, 500);
        });
    };

    this.doChartBumps = function(chartKey) {
//console.log(this.chartBumps);
//console.log(this.chartBumps[chartKey]);
        var colors = {
            0: this.config.colors['event-hit'],
            1: this.config.colors['event-valued'],
            2: this.config.colors['event-goal'],
            '-1': this.config.colors['event-subtract']
        };
        if (this.charts[chartKey] != undefined) {
            var sortInfo = this.charts[chartKey].getSortInfo();
        }
        var $tableRows = jQuery('#' + this.chartDivs[chartKey] + ' .table-row');
//console.log($tableRows);
        for (var row in this.chartBumps[chartKey]) {

            // if table has been sorted, adjust row to match sort
            if ((sortInfo != undefined) && (sortInfo.sortedIndexes != null) && (sortInfo.sortedIndexes[row] != undefined)) {
                row = sortInfo.sortedIndexes[row];
            }

            var $tableRow = $tableRows.eq(row);
            var color0 = $tableRow.css('background-color');
            var color1 =  colors[this.chartBumps[chartKey][row]];
            //$tableRows.eq(row).addClass('table-row-bump-up');
            //$tableRows.eq(row).css("background-color","yellow");

            $tableRows.eq(row).animate({backgroundColor: color1}, 250).animate({backgroundColor: color1}, 1000).animate({backgroundColor: color0}, 250);
            delete this.chartBumps[chartKey][row];
        }
    };

    this.switchReport = function (chartName, report) {
        var $reportCPane = jQuery('#' + this.chartDivs[chartName]).parent();
    };

    this.getLogElementTypeCount = function getLogElementTypeCount(element) {
console.log('getLogElementTypeCount element:');
console.log(element);
        var counts = {
          site: {
            pageviews: 0,
            entrances: 0,
            events: 0,
            valuedEvents: 0,
            goals: 0,
            value: 0
          },
          siteAdd: {
            pageviews: 0,
            entrances: 0,
            events: 0,
            valuedEvents: 0,
            goals: 0,
            value: 0
          }
        };
        // stores values that need propogated throughout sessions.
        var addValues = {};
        for (var i in element) {
            if (!element.hasOwnProperty(i) || (element[i].type == undefined)) {
                continue;
            }
            var e = element[i];
console.log(e);
            var pageKey = e.p;
            var sesKey = e.vtk + '.' + e.sid;
            var visitorKey = e.vtk;
            var t = parseInt(e.t);
            var value = 0;
//console.log('vtk=' + visitorKey);

            // construct visitor if does not exist
            if (this.model.visitors[visitorKey] == undefined) {
                this.model.visitors[visitorKey] = {
                    name: 'anon (' + e.vtk.substr(0,10) + ')',
                    sessions: {}
                }
                this.model.fetchVisitor(e.vtk, rtdView.updateVisitor);
            }
            if (this.model.visitors[visitorKey].sessions[e.sid] == undefined) {
                this.model.visitors[visitorKey].sessions[e.sid] = t;
                this.model.visitors[visitorKey].activeSession = e.sid;
            }

            // construct session if does not exist
            if (this.model.sessions[sesKey] == undefined) {
                this.model.sessions[sesKey] = {
                    vtk: e.vtk,
                    start: t,
                    last: t,
                    pageviews: 0,
                    events: 0,
                    valuedEvents: 0,
                    goals: 0,
                    ts: {},
                    hits: {}
                }
            }
            else {
                this.model.sessions[sesKey].last = t;
            }

            // add page to session struc if does not exist
            if (this.model.sessions[sesKey].hits[t] == undefined) {
                // update session pages array
                this.model.sessions[sesKey].hits[t] = {
                    value: 0,
                    events: []
                };
            }

            // if e contains traffic source, use it, otherwise get from session
            if (e.ts != undefined) {
                this.model.sessions[sesKey].ts = e.ts;
            }
            else if (this.model.sessions[sesKey] != undefined) {
                e.ts = this.model.sessions[sesKey].ts;
            }

            // initialize stats data if page page stats does not exist
            if (this.model.stats.pages[pageKey] == undefined) {
                this.model.stats.pages[pageKey] = this.getCountsArrayInit();
            }
            if (this.model.statsDelta.pages[pageKey] == undefined) {
                this.model.statsDelta.pages[pageKey] = this.getCountsArrayInit();
            }
            if (this.model.stats.visitors[visitorKey] == undefined) {
                this.model.stats.visitors[visitorKey] = this.getCountsArrayInit();
            }
            if (this.model.statsDelta.visitors[visitorKey] == undefined) {
                this.model.statsDelta.visitors[visitorKey] = this.getCountsArrayInit();
            }



            if (e.type == 'pageview') {
                counts.site.pageviews++;
                counts.siteAdd.pageviews++;
                this.model.stats.site.pageviews++;
                this.model.statsDelta.site.pageviews++;
                this.model.stats.pages[pageKey].pageviews++;
                this.model.statsDelta.pages[pageKey].pageviews++;
                this.model.stats.visitors[visitorKey].pageviews++;
                this.model.statsDelta.visitors[visitorKey].pageviews++;

                if (e.ie == 1) {
                    counts.site.entrances++;
                    counts.siteAdd.entrances++;
                    this.model.stats.site.entrances++;
                    this.model.statsDelta.site.entrances++;
                    this.model.stats.pages[pageKey].entrances++;
                    this.model.statsDelta.pages[pageKey].entrances++;
                    this.model.stats.visitors[visitorKey].entrances++;
                    this.model.statsDelta.visitors[visitorKey].entrances++;
                }

                this.model.sessions[sesKey].pageviews++;
                this.model.sessions[sesKey].hits[t].pageLEI = e.logEI;
//console.log(this.model.sessions);

                // determine value of page view
                var hitValue = this.model.scorings['additional_pages'];
                if (this.model.sessions[sesKey].pageviews == 1) {
                    hitValue = this.model.scorings['entrance'];
                }
                else if (this.model.sessions[sesKey].pageviews == 2) {
                    hitValue = this.model.scorings['stick'];
                }
                value += hitValue;

                // update page attributes entrances and page views
                // note, value is updated later
                this.updatePageAttrsStats(e.pa, pageKey, e.ie, 1, 0);

                this.updateTsStats(e.ts, pageKey, e.ie, 1, 0);

                this.updateVisitorAttrsStats(e, visitorKey, sesKey, pageKey);
            }
            else if (e.type == 'event') {
                var key = e.ec;
                if (e.ec.substr(-1) == '!') {
                    key = key.substring(0, key.length - 1);
                }
                else if (e.ec.substr(-1) == '+') {
                    key = key.substring(0, key.length - 1);
                }
                var subkey = e.ea;

                counts.site.events++;
                counts.siteAdd.events++;
                this.model.sessions[sesKey].events++;
                var eventType = 'event';
                if (e.ec.substr(-1) == '!') {
                    eventType = 'valuedEvent';
                    var ev = parseFloat(e.ev);
                    value += ev;
                    counts.site.valuedEvents++;
                    counts.siteAdd.valuedEvents++;
                }
                else if (e.ec.substr(-1) == '+') {
                    eventType = 'goal';
                    var ev = parseFloat(e.ev);
                    value += ev;
                    counts.site.goals++;
                    counts.siteAdd.goals++;
                }


                var interators = {
                  statsDelta: true,
                  stats: true
                }

                // store data in stats structs
                for (var datasrc in interators) {
                    if (this.model[datasrc].events[key] == undefined) {
                        this.model[datasrc].events[key] = this.getCountsEventArrayInit();
                        this.model[datasrc].events[key].details = {};
                        this.model[datasrc].events[key].pages = {};
                    }
                    if (this.model[datasrc].events[key].details[subkey] == undefined) {
                        this.model[datasrc].events[key].details[subkey] = this.getCountsEventArrayInit();
                        this.model[datasrc].events[key].details[subkey].pages = {};
                    }
                    if (this.model[datasrc].events[key].pages[pageKey] == undefined) {
                        this.model[datasrc].events[key].pages[pageKey] = this.getCountsEventArrayInit();
                    }
                    if (this.model[datasrc].events[key].details[subkey].pages[pageKey] == undefined) {
                        this.model[datasrc].events[key].details[subkey].pages[pageKey] = this.getCountsEventArrayInit();
                    }

                    this.model[datasrc].site.events++;
                    this.model[datasrc].events[key].events++;
                    this.model[datasrc].events[key].details[subkey].events++;
                    this.model[datasrc].events[key].pages[pageKey].events++;
                    this.model[datasrc].events[key].details[subkey].pages[pageKey].events++;
                    // if valued event
                    if (eventType == 'valuedEvent') {
                        this.model[datasrc].site.valuedEvents++;
                        this.model[datasrc].events[key].valuedEvents++;
                        this.model[datasrc].events[key].details[subkey].valuedEvents++;
                        this.model[datasrc].events[key].pages[pageKey].valuedEvents++;
                        this.model[datasrc].events[key].details[subkey].pages[pageKey].valuedEvents++;
                    }
                    else if (eventType == 'goal') {
                        this.model[datasrc].site.goals++;
                        this.model[datasrc].events[key].goals++;
                        this.model[datasrc].events[key].details[subkey].goals++;
                        this.model[datasrc].events[key].pages[pageKey].goals++;
                        this.model[datasrc].events[key].details[subkey].pages[pageKey].goals++;
                    }
                    if ((eventType == 'valuedEvent') || (eventType == 'goal')) {
                        this.model[datasrc].events[key].value += ev;
                        this.model[datasrc].events[key].details[subkey].value += ev;
                        this.model[datasrc].events[key].pages[pageKey].value += ev;
                        this.model[datasrc].events[key].details[subkey].pages[pageKey].value += ev;
                    }
                }


                // add event info to session
                this.model.sessions[sesKey].hits[t].events.push({
                    LEI: e.logEI
                })

                if (e.ec.substr(0, 3) == 'CTA') {
                    var key = e.ea;
                    if (this.model.statsDelta.ctas[key] == undefined) {
                        this.model.statsDelta.ctas[key] = this.getCountsCTAArrayInit();
                    }
                    if (e.ec == 'CTA impression') {
                        this.model.statsDelta.ctas[key].impressions++;
                    }
                    else if (e.ec.substr(4, 5) == 'click') {
                        this.model.statsDelta.ctas[key].clicks++;
                    }
                    else if (e.ec.substr(4, 10) == 'conversion') {
                        this.model.statsDelta.ctas[key].conversions++;
                    }
                }
                if (e.ec.substr(0, 12) == 'Landing page') {
                    var key = e.ea;
                    if (this.model.statsDelta.lps[key] == undefined) {
                        this.model.statsDelta.lps[key] = this.getCountsLpArrayInit();
                    }
                    if (e.ec == 'Landing page view') {
                        this.model.statsDelta.lps[key].views++;
                    }
                    else if (e.ec.substr(13, 10) == 'conversion') {
                        this.model.statsDelta.lps[key].conversions++;
                    }
                }
            }

            // add value of hits to addValue struc
            if (addValues[sesKey] == undefined) {
                addValues[sesKey] = 0;
            }
            addValues[sesKey] += value;
            this.model.stats.site.value += value;
            this.model.statsDelta.site.value += value;
            this.model.sessions[sesKey].hits[t].value += value;
        }


//console.log(addValues);
//console.log(this.model.sessions);
//console.log(this.model.log);
        // propagate value, note entrance page gets 80% of value, others 20%
        var session = {};
        var pageInstance = {};
        var entPgInstance = {};
        var value = 0;
        for (var sesKey in addValues) {
            value = addValues[sesKey];
            session = this.model.sessions[sesKey];
            var entPgInstance = null;

            for (var ht in session.hits) {
                if (!session.hits.hasOwnProperty(ht)) {
                    continue;
                }
                var hit = session.hits[ht];
//console.log(hit);
                // if hit is page view, adjust page's stats
                if (hit.pageLEI != undefined) {
                    pageInstance = this.model.log[ht][hit.pageLEI];
                    // weight pageValue based on entrance page or not
                    var pageValue = .2 * value;
                    // if entPgInstance not set, then this is the entrance page
                    if (entPgInstance == undefined) {
                      pageValue = .8 * value;
                      entPgInstance = pageInstance;
                    }

                    if (this.model.statsDelta.pages[pageInstance.p] == undefined) {
                        this.model.statsDelta.pages[pageInstance.p] = this.getCountsArrayInit();
                    }
                    this.model.stats.pages[pageInstance.p].value += pageValue;
                    this.model.statsDelta.pages[pageInstance.p].value += pageValue;
                    this.updatePageAttrsStats(pageInstance.pa, pageInstance.p, 0, 0, pageValue);
                }
            };

            // update traffic source stats. Determine the entrance page path
            // for the session to update page values under traffic sources
//console.log(entPgInstance);
            var entPage = (entPgInstance != undefined) ? entPgInstance.p : '(unknown)';
            this.updateTsStats(session.ts, entPage, 0, 0, value);

            // update visitor stats
            this.model.stats.visitors[visitorKey].value += value;
            this.model.statsDelta.visitors[visitorKey].value += value;
        }


//console.log(this.model.statsDelta);
        return counts;
    };

    this.updatePageAttrsStats = function  updatePageAttrsStats(pa, pageKey, entrances, pageviews, value) {
        for (var pai in pa) {
            var pav = pa[pai];
            var pavs = [];
            if (typeof pav === 'object') {
                for (var pi in pav) {
                    pavs.push(pi);
                }
            }
            else {
                pavs = [pav];
            }
//console.log(pavs);
            for (var pi in pavs) {
                if (!pavs.hasOwnProperty(pi)) {
                    continue;
                }
                pav = pavs[pi];

                if (this.model.statsDelta.pageAttrs[pai] == undefined) {
                    this.model.statsDelta.pageAttrs[pai] = {};
                }
                if (this.model.statsDelta.pageAttrs[pai][pav] == undefined) {
                    this.model.statsDelta.pageAttrs[pai][pav] = this.getCountsArrayInit();
                    this.model.statsDelta.pageAttrs[pai][pav]._pages = {};
                }
                if (this.model.statsDelta.pageAttrs[pai][pav]._pages[pageKey] == undefined) {
                    this.model.statsDelta.pageAttrs[pai][pav]._pages[pageKey] = this.getCountsArrayInit();
                }
                if (this.model.stats.pageAttrs[pai] == undefined) {
                    this.model.stats.pageAttrs[pai] = {};
                }
                if (this.model.stats.pageAttrs[pai][pav] == undefined) {
                    this.model.stats.pageAttrs[pai][pav] = this.getCountsArrayInit();
                    this.model.stats.pageAttrs[pai][pav]._pages = {};
                }
                if (this.model.stats.pageAttrs[pai][pav]._pages[pageKey] == undefined) {
                    this.model.stats.pageAttrs[pai][pav]._pages[pageKey] = this.getCountsArrayInit();
                }
                this.model.statsDelta.pageAttrs[pai][pav].pageviews += pageviews;
                this.model.statsDelta.pageAttrs[pai][pav]._pages[pageKey].pageviews += pageviews;
                this.model.stats.pageAttrs[pai][pav].pageviews += pageviews;
                this.model.stats.pageAttrs[pai][pav]._pages[pageKey].pageviews += pageviews;

                this.model.statsDelta.pageAttrs[pai][pav].entrances += entrances;
                this.model.statsDelta.pageAttrs[pai][pav]._pages[pageKey] += entrances;
                this.model.stats.pageAttrs[pai][pav].entrances += entrances;
                this.model.stats.pageAttrs[pai][pav]._pages[pageKey].entrances += entrances;

                this.model.statsDelta.pageAttrs[pai][pav].value += value;
                this.model.statsDelta.pageAttrs[pai][pav]._pages[pageKey] += value;
                this.model.stats.pageAttrs[pai][pav].value += value;
                this.model.stats.pageAttrs[pai][pav]._pages[pageKey].value += value;
            }
        }
    };

    this.updateVisitorAttrsStats = function  updateVisitorAttrsStats(element, visitorKey, sesKey, pageKey) {
//console.log(element.va);
        if (element.va == undefined) {
            return;
        }
        var va = element.va;

        var visitor = this.model.visitors[visitorKey];
        if (visitor.va == undefined) {
            visitor.va = {};
        }
        var va0 = visitor.va;
//console.log(va0);
//console.log(va);

        if (element.ie == 1 && (element.va0 != undefined)) {
            this.model.sessions[sesKey].va0 = element.va0;
//console.log(this.model.sessions);
            va0 = element.va0;
        }
        // va is current state of visitor's attributes. We need to first determine the
        // delta caused by the hit, then update the visitor va.

//console.log(element.va);
        var pageVa = {};
        for (var vaKey in va) {
            // if visitor is known, fetch their data
            if (vaKey == 'k' && (va0.k == undefined)) {
                this.model.fetchVisitor(visitorKey, rtdView.updateVisitor);
            }

            if (this.model.attrInfo['visitor'][vaKey] == undefined) {
                continue;
            }
            var vaInfo = this.model.attrInfo['visitor'][vaKey];
            var vaValue = va[vaKey];

            if (vaInfo.type == 'flag') {
              if (va0[vaKey] == undefined) {
                  pageVa[vaKey] = '';
              }
            }

            if (vaInfo.type == 'scalar') {
                if (va0[vaKey] == undefined) {
                    pageVa[vaKey] = vaValue;
                }
                else if (va0[vaKey] != vaValue) {
                    pageVa[vaKey] = (vaValue - va0[vaKey]);
                }
            }

            if ((vaInfo.type == 'list') || (vaInfo.type == 'vector')) {
                if (va0[vaKey] == undefined) {
                    pageVa[vaKey] = vaValue;
                }
                else {
                    for (var key in vaValue) {
                        if (va0[vaKey][key] == undefined) {
                            if (pageVa[vaKey] == undefined) {
                                pageVa[vaKey] = {};
                            }
                            pageVa[vaKey][key] = vaValue[key];
                        }
                        else if (va0[vaKey][key] != vaValue[key]) {
                            if (pageVa[vaKey] == undefined) {
                                pageVa[vaKey] = {};
                            }
                            if (vaInfo.type == 'vector' && (va0[vaKey][key] != undefined)) {
                                pageVa[vaKey][key] = (vaValue[key] - va0[vaKey][key]);
                            }
                            else {
                                pageVa[vaKey][key] = vaValue[key];
                            }

                        }
                    }
                }
            }

        }
        this.model.log[element.t][element.logEI].va = pageVa;
        this.model.visitors[visitorKey].va = va;
    };

    this.updateTsStats = function  updateTsStats(ts, pageKey, entrances, pageviews, value) {
        // build main key
        ts.main = '';
        if (ts.source != undefined) {
            ts.main += ts.source;
        }
        if (ts.medium != undefined) {
            ts.main += '/' + ts.medium;
        }
        if (ts.main == '') {
            ts.main = '(not provided)';
        }

        for (var i in ts) {
            if (!ts.hasOwnProperty(i)) {
                continue;
            }
            tsv = ts[i];
            if (this.model.stats.ts[i] == undefined) {
                this.model.stats.ts[i] = {};
            }
            if (this.model.statsDelta.ts[i] == undefined) {
                this.model.statsDelta.ts[i] = {};//this.getCountsArrayInit();
            }
            if (this.model.stats.ts[i][tsv] == undefined) {
                this.model.stats.ts[i][tsv] = this.getCountsArrayInit();
                this.model.stats.ts[i][tsv]._pages = {};
            }
            if (this.model.statsDelta.ts[i][tsv] == undefined) {
                this.model.statsDelta.ts[i][tsv] = this.getCountsArrayInit();
                this.model.statsDelta.ts[i][tsv]._pages = {};
            }
            if (this.model.stats.ts[i][tsv]._pages[pageKey] == undefined) {
                this.model.stats.ts[i][tsv]._pages[pageKey] = this.getCountsArrayInit();
            }
            if (this.model.statsDelta.ts[i][tsv]._pages[pageKey] == undefined) {
                this.model.statsDelta.ts[i][tsv]._pages[pageKey] = this.getCountsArrayInit();
            }

            this.model.statsDelta.ts[i][tsv].pageviews += pageviews;
            this.model.statsDelta.ts[i][tsv]._pages[pageKey].pageviews += pageviews;
            this.model.stats.ts[i][tsv].pageviews += pageviews;
            this.model.stats.ts[i][tsv]._pages[pageKey].pageviews += pageviews;

            this.model.statsDelta.ts[i][tsv].entrances += entrances;
            this.model.statsDelta.ts[i][tsv]._pages[pageKey] += entrances;
            this.model.stats.ts[i][tsv].entrances += entrances;
            this.model.stats.ts[i][tsv]._pages[pageKey].entrances += entrances;

            this.model.statsDelta.ts[i][tsv].value += value;
            this.model.statsDelta.ts[i][tsv]._pages[pageKey] += value;
            this.model.stats.ts[i][tsv].value += value;
            this.model.stats.ts[i][tsv]._pages[pageKey].value += value;
        }
    }

    this.getCountsArrayInit = function getCountsArrayInit() {
        return {
            entrances: 0,
            pageviews: 0,
            value: 0
        }
    };

    this.getCountsEventArrayInit = function getCountsEventArrayInit() {
        return {
            events: 0,
            valuedEvents: 0,
            goals: 0,
            value: 0
        }
    };

    this.getCountsCTAArrayInit = function getCountsCTAArrayInit() {
        return {
            impressions: 0,
            clicks: 0,
            conversions: 0
        }
    };
    this.getCountsLpArrayInit = function getCountsLpArrayInit() {
        return {
            views: 0,
            conversions: 0
        }
    };

    this.updateVisitorVar = function (vtk, varKey) {
        if (rtdConfig.chartWindows['visitors'] != undefined) {
            rtdConfig.chartWindows['visitors'].rtdView.updateVisitorVar(vtk, varKey);
            return;
        }
        if (rtdView.activeVisitor == vtk) {
            rtdView.buildVisitorDetailsReport();
        }
    };

    // TODO not sure if this function is still used
    this.updateVisitor = function (vtk) {
        if (rtdView.chartIndex['visitors'][vtk] == undefined) {
            return;
        }
        var chartKey = 'visitors';
        // check if report is in this screen
        if (rtdConfig.chartWindows[chartKey] != undefined) {
            rtdConfig.chartWindows[chartKey].rtdView.updateVisitorData(vtk) ;
            return;
        }

        var visitor = rtdView.model.visitors[vtk];

        $objs = $('#active-visitors-table .placeholder-visitor');
        $objs.each( function( index, element ) {
            var $element = $(element);
            var c = $element.attr('class');
            c = c.split(' ');
            var theClass = '';
            for (var i = 0; i < c.length; i++) {
                var d = c[i].split('-');
                // look for class in correct format
                if ((d.length == 4) && (d[0] == 'placeholder') && (d[1] == 'visitor') && (d[2] == vtk)) {
                    if (d[3] == 'name') {
                        var label = visitor.name + rtdView.getCMSLink('link-ext', rtdView.config.settings.cmsPath + 'visitor/' + vtk);
                        $element.replaceWith(label);
                        if (rtdView.chartIndex[chartKey][vtk] != undefined) {
                            var row = rtdView.chartIndex[chartKey][vtk];
                            rtdView.chartData[chartKey].setValue(row, 1, label);
                            //rtdView.drawVisitorsReport({});
                        }
                    }
                    if (d[3] == 'image' && (visitor.image != undefined)) {
                        //var img = '<img src="' + visitor.image + '">';
                        var img = '<img src="' + rtdView.config.settings.imgPath + '/trans.gif" class="visitor-image-' + vtk + '">';
                        $element.replaceWith(img);
                        if (rtdView.chartIndex[chartKey][vtk] != undefined) {
                            var row = rtdView.chartIndex[chartKey][vtk];
                            rtdView.chartData[chartKey].setValue(row, 0, img);
                            //rtdView.drawVisitorsReport({});
                        }
                    }
                }
            }
        });
    };

    this.updateAttributeOptionInfo = function(mode, attrKey, optionId, option) {
        if (rtdView.model.attrInfo[mode][attrKey].options[optionId] == undefined) {
            return;
        }
        // check if report is in this screen
        if (rtdConfig.chartWindows['pageAttrs'] != undefined) {
            rtdConfig.chartWindows['pageAttrs'].rtdView.updateAttributeOptionInfo(mode, attrKey, optionId, option);
        }
        if (rtdConfig.chartWindows['visitors'] != undefined) {
            rtdConfig.chartWindows['visitors'].rtdView.updateAttributeOptionInfo(mode, attrKey, optionId, option);
        }
        var selector = '.placeholder-';
        selector += (mode == 'visitor') ? 'va-' : 'pa-';
        selector += attrKey + '-' + optionId;
        var $temp = jQuery(selector);
//console.log(selector);
//console.log($temp);
        //jQuery(selector).replaceWith(rtdView.model.attrInfo['page'][paKey].options[key].title);
        $temp.replaceWith(option.title);
    };

    this.getCMSLink = function(icon, path) {
        //var text = '<img src="' + rtdConfig.settings.imgPath + '/url_icon.gif' + '" class="url-link">';
        var text =  this.getIcon(icon, 'ext-link');
        var options = {
            attributes: {
                target: 'cms'
            }
        };
        return this.getLink(text, path, options);
    };

    this.getIcon = function (name, classes) {
        if (classes == undefined) {
            classes = '';
        }
        else {
            classes = ' ' + classes;
        }
        return '<i class="icon-' + name + classes + '" aria-hidden="true"></i>';
    };

    this.getLink = function (text, path, options) {
        var link = '<a href="' + path + '"';
        if (options['attributes'] != undefined) {
            for (var key in options['attributes'] ) {
                link += ' ' + key + '="' + options['attributes'][key] + '"';
            }
        }
        link += '>' + text + '</a>';
        return link;
    };

    this.capstr = function (str, maxLength) {
        if (maxLength == undefined) {
            maxLength = 60;
        }
        if (str.length > maxLength) {
            str = str.substr(0, (maxLength - 3)) + '...';
        }
        return str;
    };
}

// init dashboard
jQuery(document).ready(function(){
    var build0 = VMM.Timeline.build;
    VMM.Timeline.build = function () {
        console.log('HI2                       HI2');
    }
});








