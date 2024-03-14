


function rtDashboardModel (name) {
    this.name = name;
    this.log = {};
    this.logNew = {};
    this.logDel = {};
    this.logLastId = 0;
    this.logExpire = 86400;
    //this.logIds = {};
    this.sessions = {};
    this.visitors = {};
    this.realtimeApiUrl = '../';
    this.timeDelta = 0;
    this.lastFetchTime = 0;
    this.stats = {
        pages: {},
        pageAttrs: {},
        ts: {},
        tsDetails: {},
        events: {},
        ctas: {},
        lps: {},
        visitors: {}
    };
    this.statsDelta = {},
    this.authors = {
        1: 'User 1',
        3: 'Tom McCracken',
        4: 'Brent Bice'
    };
    this.terms = {
        1: 'Inbound marketing',
        2: 'User Experience',
        3: 'Web Design',
        4: 'conversion rates',
        5: 'inbound marketing',
        6: 'Internet Marketing',
        7: 'SEO',
        8: 'Web design',
        9: 'Online marketing',
        10: 'Uncategorized'
    };
    this.contentTypes = {
        'page': 'Basic pages',
        'blog': 'Blog posts',
        'webform': 'Webforms',
        'landingpage': 'Landing pages',
        'thankyou': 'Thank you pages',
        'press_release': 'Press releases'
    };
    this.scorings = {
        entrance: .05,
        stick: .1,
        additional_pages: .02
    };

    this.init = function () {
        // if this window is the main report, then initiate polling
        if ((rtdSetup.role == 'all') || rtdSetup.role == 'master') {
            this.pole();
        }
    }

    this.pole = function pole() {
        // limit fetch starttime to 30 minutes ago
        this.lastFetchTime = this.getTime() - 1800;
        rtdModel.fetchAll();

        window.setInterval(function () {
            rtdModel.fetchAll();
        }, 5000);
    };

    this.fetchAll = function fetchAll() {
        if ((typeof(sp) == 'undefined')) {
            this.fetchLog();
        }
    }

    this.fetchLog = function fetchLog () {
        var func = 'track/log';
        var time = this.getTime();
        var params = {
            last_id: this.logLastId,
            st: this.lastFetchTime,
            t: time - 1
        };
        this.lastFetchTime = time - 1;
        var vars = {
            dataType: 'json',
            url: this._getRealtimeApiUrl(func, params),
            data: {},
            //jsonpCallback: this.name + '.fetchLogReturn',
            success: function (json){
//console.log(json);
                rtdModel.addToLog(json.instances, json.ids, json.last_id);
                //rtdModel.buildTimeline();
            }
        };
        //console.log(vars);
        jQuery.ajax(vars);
    };

    this.addToLog = function addToLog(data, ids, lastId) {
        var time;

        for (var i in data) {
            if (ids[i] < this.logLastId) {
              continue; // we got duplicate data for some reason
            }
            var e = data[i];
            if (e.t == undefined) {
                continue;
            }
            time = e.t;
            // unserialise page attributes
            if (data[i].pa != undefined) {
                data[i].pa = this._unserializeCustomVar(data[i].pa);
            }
            // unserialise traffic source
            if (data[i].ts != undefined) {
                data[i].ts = this._unserializeCustomVar(data[i].ts);
            }
            // remove url encodeing from doc title
            data[i].dt = decodeURI(data[i].dt);
            data[i].f = decodeURI(data[i].f);

            // initialize element if does not exist
            if (this.log[time] == undefined) {
                this.log[time] = [];
            }
            this.log[time].push(data[i]);
            // add data to new log
            if (this.logNew[time] == undefined) {
                this.logNew[time] = [];
            }
            // store the index of the instance in the main log element
            data[i].logEI = this.log[time].length - 1;
            this.logNew[time].push(data[i]);
        }

        // remove any data older than 30 minutes
        var time0 = this.getTime() - this.logExpire;
        for (var t in this.log) {
            var time = parseInt(t);
            if (time >= time0) {
                break;
            }
            this.logDel[time] = this.log.t;
            delete this.log.t;
        }
        this.logLastId = lastId;
        if (data.length > 0) {
            console.log('New Data (addToLog):');
            console.log(data);
            console.log(this.logNew);
        }
        //console.log(this.log);
    }

    this.fetchLogReturn = function fetchLogReturn(data) {
        console.log(data);
    }

    /**
     * Constructs the JSON url
     * @param func
     * @param params
     * @param data
     * @return
     */
    this._getRealtimeApiUrl = function _getJSONUrl(func, params, data) {
        var url;
        //params.vtk = this.vtk;
        /*
         url = ('https:' == document.location.protocol) ? 'https:' : 'http:';
         // if func starts with //, treat as custom url request
         if (func.indexOf('//') == 0) {
         url += func + '?';
         }
         else {
         url += '//' + this.apiUrl + 'index.php?q=' + func + '&';
         }
         */
        url = this.realtimeApiUrl + 'index.php?q=' + func;
        var paramStr = this._encodeUrlQueryParams(params);
        if (paramStr != '') {
            url += '&' + paramStr;
        }

        if (data != undefined) {
            url += '&data=' + encodeURIComponent(JSON.stringify(data));
        }
        return url;
    };

    this.getTime = function getTime() {
        var time = new Date().getTime();
        return Math.round(time/1000) + this.timeDelta;
    };

    /**
     * Encodes parameters array as url query elements
     * @param params
     * @return
     */
    this._encodeUrlQueryParams = function _encodeUrlQueryParams(params) {
        var str = [], k;
        for (k in params) {
            if (params.hasOwnProperty(k)) {
                str.push(encodeURIComponent(k) + "=" + encodeURIComponent(params[k]));
            }
        }
        return str.join("&");
    };



    this._unserializeCustomVar = function (str) {
        str = decodeURIComponent(str);
        var obj = {}, a, b, i, k;
        a = str.split("&");
        for (i in a) {
            if (a.hasOwnProperty(i)) {
                b = a[i].split("=");
                if (b[0] == '') {
                    continue;
                }
                k = b[0].split('.');
                if ((k.length > 1) && (obj[k[0]] == undefined)) {
                    obj[k[0]] = {};
                }
                if (b.length == 2) {
                    if (k.length > 1) {
                      obj[k[0]][k[1]] = isNaN(b[1]) ? b[1] : Number(b[1]);
                    }
                    else {
                        obj[k[0]] = isNaN(b[1]) ? b[1] : Number(b[1]);
                    }
                }
                else {
                    if (k.length > 1) {
                        obj[k[0]][k[1]] = '';
                    }
                    else {
                        obj[k[0]] = '';
                    }
                }
            }
        }
        return obj;
    };
}

function rtDashboardView (name) {
    this.model;
    this.charts = {};
    this.chartData = {};
    this.chartDivs = {
        pages: 'pages-table',
        pageAttrs: 'page-attrs-table',
        events: 'events-table',
        eventDetails: 'events-details-table',
        ctas: 'ctas-table',
        lps: 'landingpages-table',
        ts: 'ts-table',
        tsDetails: 'ts-details-table',
        visitors: 'active-visitors-table',
        visitorTimeline: 'visitor-timeline',
        visitorDetails: 'visitor-details'
    }
    this.chartsEnabled = {};
    this.chartIndex = {
        pages: {},
        pageAttrs: {},
        eTable: {},
        edTable: {},
        ctaTable: {},
        lpTable: {},
        ts: {},
        tsDetails: {},
        visitors: {}
    };
    this.chartBumps = {
        pages: {},
        pageAttrs: {},
        ts: {},
        tsDetails: {},
        events: {},
        ctas: {},
        lps: {},
        visitors: {}
    };
    this.chartRotation = {
        pageAttrs: [
            ['ct'],
            ['ct', 'blog'],
            ['t'],
            ['a']
        ],
        tsDetails: [
            ['source'],
            ['medium'],
            ['campaign'],
            ['term']
        ]
    };
    this.chartRotationI = {
        pageAttrs: 0,
        tsDetails: 0,
        eventDetail: 0
    };
    this.pageAttrsChartLabels = {
        a: 'Authors',
        ct: 'Content types',
        ctd: '%key pages'
    };
    this.pageAttrsActive = [];
    this.tsDetailsChartLabels = {
        source: 'Sources',
        medium: 'Mediums',
        term: 'Terms',
        campaign: 'Campaigns'
    };
    this.tsDetailsActive = [];
    this.eventDetailCharts = [
      'Social share',
      'Comment'
    ];
    this.eventDetailActive = 'Social share';
    this.statsDelta = {},
    this.lastSecOffsets = {};
    this.lastBuildCharts = 0;
    this.chartColors = [
        '#338fac',
        '#8fc545',
        '#E89B0C'
    ];

    this.rotationCount = 0;

    this.init = function (chartWindows) {
      for (var key in this.chartDivs) {
        if ($('#' + this.chartDivs[key]).lenght > 0) {
            this.chartsEnabled[key] = true;
        }
      }

      if (rtdSetup.role == 'child') {
          this.model = window.opener.rtdModel;
      }
      else {
          this.model = window.rtdModel;
      }

      // if this window is the main report, then initiate polling
      if ((rtdSetup.role == 'all') || rtdSetup.role == 'master') {
          this.pole();
      }

    }

    this.pole = function pole() {
//return;
        rtdView.buildAll();

        window.setInterval(function () {
            rtdView.buildAll();

        }, 1000);
    };

    this.initStatsDelta = function initStatsDelta() {
        this.statsDelta = {
            pages: {},
            pageAttrs: {},
            events: {},
            ctas: {},
            lps: {},
            ts: {},
            tsDetails: {},
            visitors: {}
        };
    };

    this.buildAll = function buildAll() {
        if ((typeof(sp) == 'undefined')) {
            this.buildCharts();
            if (rtdView.rotationCount > 5) {
                rtdView.rotateReports();
                rtdView.rotationCount = 0;
            }
            else {
                rtdView.rotationCount++;
            }
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
console.log('logNew'); console.log(logNew);

        var secTime0 = curTime - 60;
        var minTime0 = curTime - 1800;

        var curDate = new Date(1000 * curTime);
        var curSeconds = curDate.getSeconds();
        var curTimeMin = curTime - curSeconds;
        //var secPer = (60 - curSeconds - 1)/60;
        var secPer = curSeconds/60;
        var lastBuildDate = new Date(1000 * this.lastBuild);
        var lastBuildSeconds = lastBuildDate.getSeconds();


        var col1Style = 'stroke-color: ' + this.chartColors[0] + '; stroke-width: 2; fill-opacity: 0.4';
        var col2Style = 'stroke-color: ' + this.chartColors[1] + '; stroke-width: 2; fill-opacity: 0.4';
        var col3Style = 'stroke-color: ' + this.chartColors[2] + '; stroke-width: 2; fill-opacity: 0.4';
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
            this.chartData.eMin.addColumn('number', 'Value events');
            this.chartData.eMin.addColumn({type: 'string', role: 'style'});
            this.chartData.eMin.addColumn('number', 'Goals');
            this.chartData.eMin.addColumn({type: 'string', role: 'style'});
            for (var i = 0; i < 30; i++) {
                this.chartData.eMin.addRow([i*60, 0, col1Style, 0, col2Style, 0, col3Style]);
            }
        }
        if (this.chartData.eSec == undefined) {
            this.chartData.eSec = new google.visualization.DataTable();
            this.chartData.eSec.addColumn('number', 'Time');
            this.chartData.eSec.addColumn('number', 'Valued events');
            this.chartData.eSec.addColumn('number', 'Events');
            this.chartData.eSec.addColumn('number', 'Goals');
        }



        if (this.chartData.eTable == undefined) {
            this.chartData.eTable = new google.visualization.DataTable();
            this.chartData.eTable.addColumn('string', 'Event categories');
            this.chartData.eTable.addColumn('number', 'Evts');
            this.chartData.eTable.addColumn('number', 'Val');
        }
        if (this.chartData.edTable == undefined) {
            this.chartData.edTable = new google.visualization.DataTable();
            this.chartData.edTable.addColumn('string', 'Event details');
            this.chartData.edTable.addColumn('number', 'Evts');
            this.chartData.edTable.addColumn('number', 'Val');
        }
        if (this.chartData.ctaTable == undefined) {
            this.chartData.ctaTable = new google.visualization.DataTable();
            this.chartData.ctaTable.addColumn('string', 'Calls to action');
            this.chartData.ctaTable.addColumn('number', 'Imps');
            this.chartData.ctaTable.addColumn('number', 'Clks');
            this.chartData.ctaTable.addColumn('number', 'Clk%');
        }
        if (this.chartData.lpTable == undefined) {
            this.chartData.lpTable = new google.visualization.DataTable();
            this.chartData.lpTable.addColumn('string', 'Landing pages');
            this.chartData.lpTable.addColumn('number', 'Views');
            this.chartData.lpTable.addColumn('number', 'Convs');
            this.chartData.lpTable.addColumn('number', 'Conv%');
        }


        var rowPvMinInit = [0, 0, col1Style, 0, col2Style];
        if (curSeconds < lastBuildSeconds) {
            for (var i = 29; i > 0; i--) {
                for (var j = 0; j < 4; j++) {
                  var value = this.chartData.pvMin.getValue(i-1, j);
                  this.chartData.pvMin.setValue(i, j, value);
                  var value = this.chartData.eMin.getValue(i-1, j);
                  this.chartData.eMin.setValue(i, j, value);
                }
            }
            for (var j = 0; j < 4; j++) {
                this.chartData.pvMin.setValue(0, j, rowPvMinInit[j]);
                this.chartData.eMin.setValue(0, j, rowPvMinInit[j]);
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
            var d = new Date(1000 * t);
            if (t >= minTime0) {
                var counts = this.getLogElementTypeCount(logElement);
//console.log(counts);
                row = 29 - Math.floor((t - minTime0) / 60);
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
//console.log(this.statsDelta);
        var statsData = this.statsDelta;
        if ((this.lastBuild == 0)) {
           statsData = this.stats;
        }

        this.buildPagesReport(statsData);

        this.buildPageAttrsReport(statsData);

        this.buildTsReport(statsData);

        this.buildTsDetailsReport(statsData);

        this.buildVisitorsReport(statsData);

/*
        // event table build
        for (var key in this.statsDelta.events) {
            var c = this.statsDelta.events[key];
            if (this.chartIndex.eTable[key] == undefined) {
                this.chartIndex.eTable[key] = this.chartData.eTable.getNumberOfRows();
                this.chartData.eTable.addRow([key, 0, 0]);
            }
            row = this.chartIndex.eTable[key];
            count = this.chartData.eTable.getValue(row, 1);
            this.chartData.eTable.setValue(row, 1, c.events + count);
            count = this.chartData.eTable.getValue(row, 2);
            this.chartData.eTable.setValue(row, 2, c.value + count);
            this.chartBumps.events[row] = c.events;
        }


        if (this.statsDelta.events[this.eventDetailActive] != undefined) {
            for (var key in this.statsDelta.events[this.eventDetailActive].details) {
                var c = this.statsDelta.events[this.eventDetailActive].details[key];
                if (this.chartIndex.edTable[key] == undefined) {
                    this.chartIndex.edTable[key] = this.chartData.edTable.getNumberOfRows();
                    this.chartData.edTable.addRow([key, 0, 0]);
                }
                row = this.chartIndex.edTable[key];
                count = this.chartData.edTable.getValue(row, 1);
                this.chartData.edTable.setValue(row, 1, c.events + count);
                count = this.chartData.edTable.getValue(row, 2);
                this.chartData.edTable.setValue(row, 2, c.value + count);
                this.chartBumps.edetails[row] = c.events;
            }
        }

        // cta table build
        for (var key in this.statsDelta.ctas) {
            var c = this.statsDelta.ctas[key];
            if (this.chartIndex.ctaTable[key] == undefined) {
                this.chartIndex.ctaTable[key] = this.chartData.ctaTable.getNumberOfRows();
                this.chartData.ctaTable.addRow([key, 0, 0, 0]);
            }
            row = this.chartIndex.ctaTable[key];
            var countA = this.chartData.ctaTable.getValue(row, 1) + c.impressions;
            this.chartData.ctaTable.setValue(row, 1, countA);
            var countB = this.chartData.ctaTable.getValue(row, 2)  + c.clicks;
            this.chartData.ctaTable.setValue(row, 2, countB);
            var per = (countA != 0) ? 100 * countB/countA : 0;
            this.chartData.ctaTable.setValue(row, 3, per);
            this.chartBumps.ctas[row] = c.clicks;
        }

        // landing pages table build
        for (var key in this.statsDelta.lps) {
            var c = this.statsDelta.lps[key];
            if (this.chartIndex.lpTable[key] == undefined) {
                this.chartIndex.lpTable[key] = this.chartData.lpTable.getNumberOfRows();
                this.chartData.lpTable.addRow([key, 0, 0, 0]);
            }
            row = this.chartIndex.lpTable[key];
            var countA = this.chartData.lpTable.getValue(row, 1) + c.views;
            this.chartData.lpTable.setValue(row, 1, countA);
            var countB = this.chartData.lpTable.getValue(row, 2)  + c.conversions;
            this.chartData.lpTable.setValue(row, 2, countB);
            var per = (countA != 0) ? 100 * countB/countA : 0;
            this.chartData.lpTable.setValue(row, 3, per);
            this.chartBumps.lps[row] = c.conversions;
        }
        */

        var minDivWidth = jQuery('#chart-realtime-pageviews-min').width();
        var backgroundColor = jQuery('.pane').css('background-color');
        var fontColor = jQuery('.pane').css('color');
        var baselineColor = '#AAA';
        //console.log(minDivWidth);
        var options = {
            colors: this.chartColors,
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
                gridlines: {count: 6, color: '#333'},
                ticks: [
                    {v: 300, f:'-5 min'},
                    {v: 600, f:'-10 min'},
                    {v: 900, f:'-15 min'},
                    {v: 1200, f:'-20 min'},
                    {v: 1500, f:'-25 min'}
                ],
                /*
                 ticks: [
                 '-30',
                 '-25',
                 '-20',
                 '-15',
                 '-10',
                 '-5'
                 ],
                 */
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

        if (this.charts.pvMin == undefined) {
            this.charts.pvMin = new google.visualization.ColumnChart(document.getElementById('active-pageviews-timeline-min'));
        }
        this.charts.pvMin.draw(this.chartData.pvMin, options);

        if (this.charts.eMin == undefined) {
            this.charts.eMin = new google.visualization.ColumnChart(document.getElementById('active-events-timeline-min'));
        }
        this.charts.eMin.draw(this.chartData.eMin, options);

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

        if (this.charts.eSec == undefined) {
            this.charts.eSec = new google.visualization.ColumnChart(document.getElementById('active-events-timeline-sec'));
        }
        this.charts.eSec.draw(this.chartData.eSec, options);

        /*
        options = {
            showRowNumber: true,
            allowHtml: true,
            cssClassNames: {
              tableRow: 'table-row table-row-even',
              oddTableRow: 'table-row table-row-odd',
              headerRow: 'table-header-row',
              tableCell: 'table-cell',
              headerCell: 'table-header-cell'
            }
        };


        var valueFormatter = this.valueFormatter();
        var percentFormatter = this.percentFormatter();

        if (this.charts.eTable == undefined) {
            this.charts.eTable = new google.visualization.Table(document.getElementById('events-table'));
        }
        this.charts.eTable.draw(this.chartData.eTable, options);

        if (this.charts.edTable == undefined) {
            this.charts.edTable = new google.visualization.Table(document.getElementById('event-details-table'));
        }
        this.charts.edTable.draw(this.chartData.edTable, options);

        if (this.charts.ctaTable == undefined) {
            this.charts.ctaTable = new google.visualization.Table(document.getElementById('ctas-table'));
        }
        percentFormatter.format(this.chartData.ctaTable, 3);
        this.charts.ctaTable.draw(this.chartData.ctaTable, options);

        if (this.charts.lpTable == undefined) {
            this.charts.lpTable = new google.visualization.Table(document.getElementById('landingpages-table'));
        }
        percentFormatter.format(this.chartData.lpTable, 3);
        this.charts.lpTable.draw(this.chartData.lpTable, options);

        */

        this.doChartBumps();

        // set last
        this.lastBuild = curTime;
    };

    this.drawTableOptions = function() {
        options = {
            showRowNumber: true,
            allowHtml: true,
            cssClassNames: {
                tableRow: 'table-row table-row-even',
                oddTableRow: 'table-row table-row-odd',
                headerRow: 'table-header-row',
                tableCell: 'table-cell',
                headerCell: 'table-header-cell'
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
        if (!this.chartsEnabled[chartKey]) {
            return;
        }
        if (this.chartData.pages == undefined) {
            this.chartData.pages = new google.visualization.DataTable();
            this.chartData.pages.addColumn('string', 'Pages');
            this.chartData.pages.addColumn('number', 'Ent');
            this.chartData.pages.addColumn('number', 'Pvs');
            this.chartData.pages.addColumn('number', 'Val');
        }
        var draw = false;
        for (var key in statsData.pages) {
            var c = statsData.pages[key];
            draw = true;
            if (this.chartIndex.pages[key] == undefined) {
                this.chartIndex.pages[key] = this.chartData.pages.getNumberOfRows();
                this.chartData.pages.addRow([key, 0, 0, 0]);
            }
            var row = this.chartIndex.pages[key];

            count = this.chartData.pages.getValue(row, 1);
            this.chartData.pages.setValue(row, 1, c.entrances + count);

            count = this.chartData.pages.getValue(row, 2);
            this.chartData.pages.setValue(row, 2, c.pageviews + count);

            count = this.chartData.pages.getValue(row, 3);
            this.chartData.pages.setValue(row, 3, c.value + count);

            if (refresh != true) {
                if (c.pageviews > 0) {
                    this.chartBumps.pages[row] = 0;
                }
                if (c.value > 1) {
                    this.chartBumps.pages[row] = 1;
                }
            }
            //console.log(this.chartBumps.pages);

        }

        if (this.charts.pages == undefined) {
            this.charts.pages = new google.visualization.Table(document.getElementById(this.chartDivs['pages']));
            google.visualization.events.addListener(this.charts.pages, 'ready', function () {rtdView.doChartBumps('pages');});
        }
        if (draw) {
            var valueFormatter = this.valueFormatter();
            valueFormatter.format(this.chartData.pages, 3);
            this.charts.pages.draw(this.chartData.pages, this.drawTableOptions());
        }

    };

    this.buildPageAttrsReport = function(statsData, type, refresh) {
//console.log(statsData);
        var chartKey = 'pageAttrs';
        if (!this.chartsEnabled[chartKey]) {
            return;
        }
        if (type == undefined) {
          type = (this.pageAttrsActive.length > 0) ? this.pageAttrsActive : this.chartRotation.pageAttrs[this.chartRotationI.pageAttrs];
        }
        if (refresh == true) {
          this.chartData.pageAttrs = null;
          this.chartIndex.pageAttrs = {};
        }
        var draw = false;
        if (this.chartRedraw.pageAttrs != undefined) {
          draw = true;
          delete this.chartRedraw.pageAttrs;
        }

//console.log(statsData);
        if (this.chartData.pageAttrs == undefined) {
            this.chartData.pageAttrs = new google.visualization.DataTable();
            this.chartData.pageAttrs.addColumn('string', 'Content types');
            this.chartData.pageAttrs.addColumn('number', 'Ent');
            this.chartData.pageAttrs.addColumn('number', 'Pvs');
            this.chartData.pageAttrs.addColumn('number', 'Val');
        }
        var labels = {};
        var data = {};
        if (statsData.pageAttrs[type[0]] != undefined
        //    && ()
        ) {
            data = statsData.pageAttrs[type[0]];
            if ((type.length == 2)) {
              if (data[type[1]] == undefined) {
                data = {};
              }
              else {
                  data = data[type[1]]._pages;
              }
            }
            if (type[0] == 'a') {
                this.chartData.pageAttrs.setColumnLabel(0, 'Authors');
                labels = this.model.authors;
            }
            if (type[0] == 't') {
                this.chartData.pageAttrs.setColumnLabel(0, 'Terms');
                labels = this.model.terms;
            }
            if (type[0] == 'ct') {
                labels = this.model.contentTypes;
                if ((type.length == 2)) {
                    var label = (this.model.contentTypes[type[1]] != undefined) ? this.model.contentTypes[type[1]] : type[1];
                    this.chartData.pageAttrs.setColumnLabel(0, label);
                }
            }
        }

        for (var key in data) {
            var c = data[key];
            draw = true;
            if (this.chartIndex.pageAttrs[key] == undefined) {
                this.chartIndex.pageAttrs[key] = this.chartData.pageAttrs.getNumberOfRows();
                this.chartData.pageAttrs.addRow([((labels[key] != undefined) ? labels[key] : key), 0, 0, 0]);
            }
            row = this.chartIndex.pageAttrs[key];
            count = this.chartData.pageAttrs.getValue(row, 1);
            this.chartData.pageAttrs.setValue(row, 1, c.entrances + count);

            count = this.chartData.pageAttrs.getValue(row, 2);
            this.chartData.pageAttrs.setValue(row, 2, c.pageviews + count);

            count = this.chartData.pageAttrs.getValue(row, 3);
            this.chartData.pageAttrs.setValue(row, 3, c.value + count);

            if (refresh != true) {
                if (c.pageviews > 0) {
                    this.chartBumps.pageAttrs[row] = 0;
                }
                if (c.value > 1) {
                    this.chartBumps.pageAttrs[row] = 1;
                }
            }
        }

        if (this.charts.pageAttrs == undefined) {
            this.charts.pageAttrs = new google.visualization.Table(document.getElementById(this.chartDivs['pageAttrs']));
            google.visualization.events.addListener(this.charts.pageAttrs, 'ready', function () {rtdView.doChartBumps('pageAttrs');});
        }
        if (draw) {
          var valueFormatter = this.valueFormatter();
          valueFormatter.format(this.chartData.pageAttrs, 3);
          this.charts.pageAttrs.draw(this.chartData.pageAttrs, this.drawTableOptions());
        }
    };



    this.buildTsReport = function(statsData, refresh) {
        var chartKey = 'ts';
        if (!this.chartsEnabled[chartKey]) {
            return;
        }

        if (this.chartData[chartKey] == undefined) {
            this.chartData[chartKey] = new google.visualization.DataTable();
            this.chartData[chartKey].addColumn('string', 'Traffic source');
            this.chartData[chartKey].addColumn('number', 'Ent');
            this.chartData[chartKey].addColumn('number', 'Pvs');
            this.chartData[chartKey].addColumn('number', 'Val');
        }
        var draw = false;

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
        if (!this.chartsEnabled[chartKey]) {
            return;
        }
//console.log(statsData);
        if (type == undefined) {
            type = (this.tsDetailsActive.length > 0) ? this.tsDetailsActive : this.chartRotation.tsDetails[this.chartRotationI.tsDetails];
        }
        if (refresh == true) {
            this.chartData[chartKey] = null;
            this.chartIndex[chartKey] = {};
        }
        var draw = false;
        if (this.chartRedraw[chartKey] != undefined) {
            draw = true;
            delete this.chartRedraw[chartKey];
        }

//console.log(statsData);
        if (this.chartData[chartKey] == undefined) {
            this.chartData[chartKey] = new google.visualization.DataTable();
            this.chartData[chartKey].addColumn('string', 'Traffic source');
            this.chartData[chartKey].addColumn('number', 'Ent');
            this.chartData[chartKey].addColumn('number', 'Pvs');
            this.chartData[chartKey].addColumn('number', 'Val');
        }
        var labels = {};
        var data = {};
        if (statsData.ts[type[0]] != undefined
        //    && ()
            ) {
            data = statsData.ts[type[0]];
            var lkey = type[0];
            if ((type.length == 2)) {
                data = data[type[1]]._pages;
                lkey += '-' + type[1];
            }
            var lkey = type[0];
            if (this.tsDetailsChartLabels[lkey] != undefined) {
                this.chartData[chartKey].setColumnLabel(0, this.tsDetailsChartLabels[lkey]);
            }
        }

        var draw = false;

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

    this.activeVisitor = '';

    this.buildVisitorsReport = function(statsData, refresh) {
        var chartKey = 'visitors';
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

//console.log(this.chartData[chartKey].toJSON());

            //this.chartData[chartKey].addColumn('string', 'stats');
        }

        var draw = true;
        var newActiveVisitorData = false;
//console.log(this.model.visitors);
//console.log(this.model.sessions);
//console.log(statsData.visitors);
        var count;
        // update stats changes, e.g. pageviews and value.
        // time based updates done in loop below this one
        for (var key in statsData.visitors) {

            var c = statsData.visitors[key];
            var visitor = this.model.visitors[key];
            var sid = visitor.activeSession;
            var session = this.model.sessions[key + '.' + sid];
            var lastPage = session.hits[session.last];
            lastPage = this.model.log[session.last][lastPage.pageLEI];

            // TODO make this more elegant
            if (this.activeVisitor == '') {
                this.activeVisitor = key;
            }

            // if this visitor is the activeVisitor, set new...Data flag
            if (key == this.activeVisitor) {
                newActiveVisitorData = true;
            }

            console.log(session);
            //console.log(lastPage);

            draw = true;
            if (this.chartIndex[chartKey][key] == undefined) {
                this.chartIndex[chartKey][key] = this.chartData[chartKey].getNumberOfRows();
                var imageSrc = '../../icons/default_user.png';
                var img = '<img src="' + imageSrc + '">';
                var stats = "ts: test<br/>ent: test2<br/>pg: /blog";
                var newRow = [img, visitor.name, 0, 0, 0, 0, 0, 0];
                newRow[indexes['lastHit']] = new Date();
                newRow[indexes['page']] = '';
                this.chartData[chartKey].addRow(newRow);
            }
            var row = this.chartIndex[chartKey][key];

            //this.chartData[chartKey].setValue(row, indexes['lastHit'], dateTimeFromLastHit);

            //count = this.chartData[chartKey].getValue(row, indexes['pageviews']);
            this.chartData[chartKey].setValue(row, indexes['pageviews'], session.pageviews);

            this.chartData[chartKey].setValue(row, indexes['eventsgoals'], (session.valuedEvents + session.goals));

            //count = this.chartData[chartKey].getValue(row, indexes['sessions']);
            this.chartData[chartKey].setValue(row, indexes['sessions'], sid);

            count = this.chartData[chartKey].getValue(row, indexes['value']);
            this.chartData[chartKey].setValue(row, indexes['value'], c.value + count);

            this.chartData[chartKey].setValue(row, indexes['page'], lastPage.p);

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
        for (var key in this.chartIndex[chartKey]) {
            if (rowAdjust != 0) {
                this.chartIndex[chartKey][key] += rowAdjust;
            }
            var row = this.chartIndex[chartKey][key];
            var visitor = this.model.visitors[key];
            var sid = visitor.activeSession;
            var session = this.model.sessions[key + '.' + sid];
            var timeFromLastHit = (this.model.getTime() - session.last);

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

        if (this.charts[chartKey] == undefined) {
            this.charts[chartKey] = new google.visualization.Table(document.getElementById(this.chartDivs[chartKey]));
            google.visualization.events.addListener(this.charts[chartKey], 'ready', function () {rtdView.doChartBumps(chartKey);});
        }
        if (draw) {
            var valueFormatter = this.valueFormatter();
            valueFormatter.format(this.chartData[chartKey], indexes['value']);

            var timeFormatter = this.timeMSFormatter();
            timeFormatter.format(this.chartData[chartKey], indexes['lastHit']);

            var options = this.drawTableOptions();
            options.showRowNumber = false;
            this.charts[chartKey].draw(this.chartData[chartKey], options);
        }

        if (newActiveVisitorData) {
            this.buildTimeline();
        }

    };

    this.timelineInitialized = false;
    this.buildTimeline = function buildTimeline() {
        var curTime = this.model.getTime();
        var vtk = this.activeVisitor;
        if (vtk == '') {
            return;
        }
        var visitor = this.model.visitors[vtk];
        var sid = visitor.activeSession;
        var session = this.model.sessions[vtk + '.' + sid];
        console.log(visitor);
        console.log(sid);
        console.log(session);

        var timelineData = {
            headline: visitor.name + " Clickstream",
            type: "default",
            text: "Intro body text goes here",
            //startDate: this.formatTimelineDate(curTime - 1800),
            //endDate: this.formatTimelineDate(curTime),
            date: []
        };
        for (var ht in session.hits) {
            var hits = session.hits[ht];
            var time = this.formatTimelineDate(ht);
            var tag = 'event';
            if (hits.pageLEI != undefined) {
                tag = 'pageview';
                page = this.model.log[ht][hits.pageLEI];
            }
            timelineData.date.push({
                startDate: time,
                endDate: time,
                headline: page.dt,
                text: "test text",
                tag: tag,
                asset: {
                    media: "http://" + page.h + page.p
                }
            });
        }

        if (this.timelineInitialized) {
            VMM.Timeline.Config.source.timeline = timelineData;

            // see if this stops rebuild animation
            //VMM.Timeline.Config.duration = 0;
            //VMM.Timeline.Config.ease = "linear";  // "easeInOutExpo"
            //VMM.Timeline.Config.current_slide = timelineData.date.length;
            VMM.Timeline.Config.start_at_slide = timelineData.date.length;
            //VMM.Timeline.Config.current_slide = timelineData.date.length;
            VMM.fireEvent(global, VMM.Timeline.Config.events.data_ready, VMM.Timeline.Config.source);


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
        }

        var data = {
            type:       'timeline',
            width:      $('#visitor-timeline').width()+2,
            height:     $('#visitor-timeline').height()+16,
            //height:     $('#timeline-report').height()+16+100,
            source:     {timeline: timelineData},
            embed_id:   'visitor-timeline',
            //hash_bookmark: true,
            start_at_slide: timelineData.date.length,
            start_zoom_adjust:  '0',
            //start_at_end: true,
            debug: true
        };
        timeline = createStoryJS(data);
        //var timeline = new VMM.Timeline('timeline-report');
        //timeline.init(data);
        //VMM.bindEvent(buildTimeline2);
        VMM.bindEvent(global, rtdView.timelineOnDataReady, "DATAREADY");
        this.timelineInitialized = true;
    };

    this.timelineOnDataReady = function timelineOnDataReady() {
        console.log(this);
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



    this.rotateReports = function() {
//console.log('rotateReports');
      var charts = [
        'pageAttrs',
        'tsDetails'
      ];

      var types = {
          pageAttrs: [],
          tsDetails: []
      };
      for (var key in types) {
          this.chartRotationI[key]++;
//console.log(key);
//console.log(this.chartRotation);
//console.log(this.chartRotationI);

          if (this.chartRotation[key][this.chartRotationI[key]] == undefined) {
              this.chartRotationI[key] = 0;
          }
          types[key] = this.chartRotation[key][this.chartRotationI[key]];

          var $pane = jQuery('#' + this.chartDivs[key]).parent();
          var width = jQuery('#' + this.chartDivs[key]).parent().width() + 'px';

          jQuery('#' + this.chartDivs[key]).parent().animate({opacity: '0.1'}, 500, function () {
              var key = $('.chart', this).attr('data-chart-key');
              var func = 'build' + key.charAt(0).toUpperCase() + key.slice(1) + 'Report';
//console.log(key);
//console.log(types[key]);
//console.log(func);

              rtdView[func](rtdView.stats, types[key], true);
              $(this).animate({opacity: '1'}, 500);
          });
      }

    };

    this.doChartBumps = function(chartKey) {
//console.log(this.chartBumps);
//console.log(this.chartBumps[chartKey]);
        var colors = {
            0: '#325662',
            1: '#566C39',
            '-1': '#880000'
        };
        var $tableRows = jQuery('#' + this.chartDivs[chartKey] + ' .table-row');
//console.log($tableRows);
        for (var row in this.chartBumps[chartKey]) {
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

            var pageKey = e.p;
            var sesKey = e.vtk + '.' + e.sid;
            var visitorKey = e.vtk;
            var t = parseInt(e.t);
            var value = 0;


            // construct visitor if does not exist
            if (this.model.visitors[visitorKey] == undefined) {
                this.model.visitors[visitorKey] = {
                    name: 'anon (' + e.vtk.substr(0,10) + ')',
                    sessions: {}
                }
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
            if (this.stats.pages[pageKey] == undefined) {
                this.stats.pages[pageKey] = this.getCountsArrayInit();
            }
            if (this.statsDelta.pages[pageKey] == undefined) {
                this.statsDelta.pages[pageKey] = this.getCountsArrayInit();
            }
            if (this.stats.visitors[visitorKey] == undefined) {
                this.stats.visitors[visitorKey] = this.getCountsArrayInit();
            }
            if (this.statsDelta.visitors[visitorKey] == undefined) {
                this.statsDelta.visitors[visitorKey] = this.getCountsArrayInit();
            }



            if (e.type == 'pageview') {
                counts.site.pageviews++;
                counts.siteAdd.pageviews++;
                this.stats.pages[pageKey].pageviews++;
                this.statsDelta.pages[pageKey].pageviews++;
                this.stats.visitors[visitorKey].pageviews++;
                this.statsDelta.visitors[visitorKey].pageviews++;

                if (e.ie == 1) {
                    counts.site.entrances++;
                    counts.siteAdd.entrances++;
                    this.stats.pages[pageKey].entrances++;
                    this.statsDelta.pages[pageKey].entrances++;
                    this.stats.visitors[visitorKey].entrances++;
                    this.statsDelta.visitors[visitorKey].entrances++;
                }

                this.model.sessions[sesKey].pageviews++;
                this.model.sessions[sesKey].hits[t].pageLEI = e.logEI;
console.log(this.model.sessions);

                // determine value of page view
                if (this.model.sessions[sesKey].pageviews == 1) {
                    value += this.model.scorings['entrance'];
                }
                else if (this.model.sessions[sesKey].pageviews == 2) {
                    value += this.model.scorings['stick'];
                }
                else {
                    value += this.model.scorings['additional_pages'];
                }

                // update page attributes entrances and page views
                this.updatePageAttrsStats(e.pa, pageKey, e.ie, 1, 0);

                this.updateTsStats(e.ts, pageKey, e.ie, 1, 0);
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
                if (this.statsDelta.events[key] == undefined) {
                    this.statsDelta.events[key] = this.getCountsEventArrayInit();
                    this.statsDelta.events[key].details = {};
                }
                if (this.statsDelta.events[key].details[subkey] == undefined) {
                    this.statsDelta.events[key].details[subkey] = this.getCountsEventArrayInit();
                }
                counts.site.events++;
                counts.siteAdd.events++;
                this.statsDelta.events[key].events++;
                this.statsDelta.events[key].details[subkey].events++;
                this.model.sessions[sesKey].events++;
                // if valued event
                if (e.ec.substr(-1) == '!') {
                    var ev = parseFloat(e.ev);
                    value += ev;
                    counts.site.valuedEvents++;
                    counts.siteAdd.valuedEvents++;
                    this.statsDelta.events[key].valuedEvents++;
                    this.statsDelta.events[key].details[subkey].valuedEvents++;
                    this.statsDelta.events[key].value += ev;
                    this.statsDelta.events[key].details[subkey].value += ev;
                    this.model.sessions[sesKey].valuedEvents++;
                }
                else if (e.ec.substr(-1) == '+') {
                    var ev = parseFloat(e.ev);
                    value += ev;
                    counts.site.goals++;
                    counts.siteAdd.goals++;
                    this.statsDelta.events[key].goals++;
                    this.statsDelta.events[key].details[subkey].goals++;
                    this.statsDelta.events[key].value += ev;
                    this.statsDelta.events[key].details[subkey].value += ev;
                    this.model.sessions[sesKey].goals++;
                }

                // add event info to session
                this.model.sessions[sesKey].hits[t].events.push({
                    LEI: e.logEI
                })

                if (e.ec.substr(0, 3) == 'CTA') {
                    var key = e.ea;
                    if (this.statsDelta.ctas[key] == undefined) {
                        this.statsDelta.ctas[key] = this.getCountsCTAArrayInit();
                    }
                    if (e.ec == 'CTA impression') {
                        this.statsDelta.ctas[key].impressions++;
                    }
                    else if (e.ec.substr(4, 5) == 'click') {
                        this.statsDelta.ctas[key].clicks++;
                    }
                    else if (e.ec.substr(4, 10) == 'conversion') {
                        this.statsDelta.ctas[key].conversions++;
                    }
                }
                if (e.ec.substr(0, 12) == 'Landing page') {
                    var key = e.ea;
                    if (this.statsDelta.lps[key] == undefined) {
                        this.statsDelta.lps[key] = this.getCountsLpArrayInit();
                    }
                    if (e.ec == 'Landing page view') {
                        this.statsDelta.lps[key].views++;
                    }
                    else if (e.ec.substr(13, 10) == 'conversion') {
                        this.statsDelta.lps[key].conversions++;
                    }
                }


            }

            // add value of hits to addValue struc
            if (addValues[sesKey] == undefined) {
                addValues[sesKey] = 0;
            }
            addValues[sesKey] += value;
        }


console.log(addValues);
console.log(this.model.sessions);
console.log(this.model.log);
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
console.log(hit);
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

                    if (this.statsDelta.pages[pageInstance.p] == undefined) {
                        this.statsDelta.pages[pageInstance.p] = this.getCountsArrayInit();
                    }
                    this.stats.pages[pageInstance.p].value += pageValue;
                    this.statsDelta.pages[pageInstance.p].value += pageValue;
                    this.updatePageAttrsStats(pageInstance.pa, pageInstance.p, 0, 0, pageValue);
                }
            };

            // update traffic source stats. Determine the entrance page path
            // for the session to update page values under traffic sources
console.log(entPgInstance);
            this.updateTsStats(session.ts, entPgInstance.p, 0, 0, value);

            // update visitor stats
            this.stats.visitors[visitorKey].value += value;
            this.statsDelta.visitors[visitorKey].value += value;
        }


console.log(this.statsDelta);
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
console.log(pavs);
            for (var pi in pavs) {
                if (!pavs.hasOwnProperty(pi)) {
                    continue;
                }
                pav = pavs[pi];

                if (this.statsDelta.pageAttrs[pai] == undefined) {
                    this.statsDelta.pageAttrs[pai] = {};
                }
                if (this.statsDelta.pageAttrs[pai][pav] == undefined) {
                    this.statsDelta.pageAttrs[pai][pav] = this.getCountsArrayInit();
                    this.statsDelta.pageAttrs[pai][pav]._pages = {};
                }
                if (this.statsDelta.pageAttrs[pai][pav]._pages[pageKey] == undefined) {
                    this.statsDelta.pageAttrs[pai][pav]._pages[pageKey] = this.getCountsArrayInit();
                }
                if (this.stats.pageAttrs[pai] == undefined) {
                    this.stats.pageAttrs[pai] = {};
                }
                if (this.stats.pageAttrs[pai][pav] == undefined) {
                    this.stats.pageAttrs[pai][pav] = this.getCountsArrayInit();
                    this.stats.pageAttrs[pai][pav]._pages = {};
                }
                if (this.stats.pageAttrs[pai][pav]._pages[pageKey] == undefined) {
                    this.stats.pageAttrs[pai][pav]._pages[pageKey] = this.getCountsArrayInit();
                }
                this.statsDelta.pageAttrs[pai][pav].pageviews += pageviews;
                this.statsDelta.pageAttrs[pai][pav]._pages[pageKey].pageviews += pageviews;
                this.stats.pageAttrs[pai][pav].pageviews += pageviews;
                this.stats.pageAttrs[pai][pav]._pages[pageKey].pageviews += pageviews;

                this.statsDelta.pageAttrs[pai][pav].entrances += entrances;
                this.statsDelta.pageAttrs[pai][pav]._pages[pageKey] += entrances;
                this.stats.pageAttrs[pai][pav].entrances += entrances;
                this.stats.pageAttrs[pai][pav]._pages[pageKey].entrances += entrances;

                this.statsDelta.pageAttrs[pai][pav].value += value;
                this.statsDelta.pageAttrs[pai][pav]._pages[pageKey] += value;
                this.stats.pageAttrs[pai][pav].value += value;
                this.stats.pageAttrs[pai][pav]._pages[pageKey].value += value;
            }
        }
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
            if (this.stats.ts[i] == undefined) {
                this.stats.ts[i] = {};
            }
            if (this.statsDelta.ts[i] == undefined) {
                this.statsDelta.ts[i] = {};//this.getCountsArrayInit();
            }
            if (this.stats.ts[i][tsv] == undefined) {
                this.stats.ts[i][tsv] = this.getCountsArrayInit();
                this.stats.ts[i][tsv]._pages = {};
            }
            if (this.statsDelta.ts[i][tsv] == undefined) {
                this.statsDelta.ts[i][tsv] = this.getCountsArrayInit();
                this.statsDelta.ts[i][tsv]._pages = {};
            }
            if (this.stats.ts[i][tsv]._pages[pageKey] == undefined) {
                this.stats.ts[i][tsv]._pages[pageKey] = this.getCountsArrayInit();
            }
            if (this.statsDelta.ts[i][tsv]._pages[pageKey] == undefined) {
                this.statsDelta.ts[i][tsv]._pages[pageKey] = this.getCountsArrayInit();
            }

            this.statsDelta.ts[i][tsv].pageviews += pageviews;
            this.statsDelta.ts[i][tsv]._pages[pageKey].pageviews += pageviews;
            this.stats.ts[i][tsv].pageviews += pageviews;
            this.stats.ts[i][tsv]._pages[pageKey].pageviews += pageviews;

            this.statsDelta.ts[i][tsv].entrances += entrances;
            this.statsDelta.ts[i][tsv]._pages[pageKey] += entrances;
            this.stats.ts[i][tsv].entrances += entrances;
            this.stats.ts[i][tsv]._pages[pageKey].entrances += entrances;

            this.statsDelta.ts[i][tsv].value += value;
            this.statsDelta.ts[i][tsv]._pages[pageKey] += value;
            this.stats.ts[i][tsv].value += value;
            this.stats.ts[i][tsv]._pages[pageKey].value += value;
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
}





