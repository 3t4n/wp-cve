var _ioq = _ioq || [];

function L10iPageTracker(_ioq, config) {
    var ioq = _ioq;
    var io = _ioq.io;
    var $;
    var $win;
    var depthEventsSent = 0;
    this.linkClicksIgnored = [];

    this.init = function init() {
        ioq.log(ioq.name + ':pagetracker.init()');
        var ths = this;

        $ = jQuery;
        $win = $(window);

        // add callback for time intervale
        ioq.addCallback('timeInterval.30', ths.handlePageConsumedTime, this);

        // add callback to process if page has been abandoned
        ioq.addCallback('timeInterval.900', ths.handlePageAbandoned, this);

        // add beforeunload callback to trigger page time and page scroll events
        $win.on('beforeunload', function (event) {
            ths.handleUnload(event);
        });

    };

    this.handlePageConsumedTime = function () {
        ioq.log('PageTracker::handlePageConsumedTime()');
        var
          ths = this,
          scroll = ioq.get('p.scroll', {});
        // check if visitor has scrolled 90% to bottom of content
        if (this.isDeepScroll(scroll)) {
            this.sendPageConsumedEvent();
        }
        else {
            ioq.addCallback('scroll', this.handlePageConsumedScroll, this);
        }
    };

    this.handlePageConsumedScroll = function (scroll) {
        ioq.log(ioq.name + ':pagetracker.handlePageConsumedScroll()');
        //console.log(scroll.contentBottomMaxPer);
        if (this.isDeepScroll(scroll)) {
            ioq.removeCallback('scroll', this.handlePageConsumedScroll, this);
            this.sendPageConsumedEvent();
        }
    };

    this.isDeepScroll = function (scroll) {
        // test if content selector is set. I.e. contentBottomMax and bottomMax are equal if not set.
        if (scroll.contentBottomMax != scroll.bottomMax) {
            return (scroll.contentBottomMaxPer > 90);
        }
        else {
            // content selector not set. Try to make reasonable assumptions given we could have comments an other items
            // at bottom of page
            // consider true if scroll > 80 %
            if (scroll.bottomMaxPer > 80) {
                return 1;
            }
            var $comments = $('#comments.comments-area');
            if ($comments.length) {
                var d = ioq.getElementDimensions($comments);
                // recalculate bottomMaxPer using top of comments as bottom of page
                var bmp = 100 * scroll.bottomMax / d.top;
                if (bmp > 85) {
                    return 1;
                }
            }
            return 0;
        }
    };

    this.sendPageConsumedEvent = function() {
        ioq.log('PageTracker::sendPageConsumedEvent()');
        // if page has been left open for more than 30 mins, don't send event.
        if (ioq.getTimeDelta() > 3600) {
            return;
        }
        var evtDef = {
            eventCategory: 'Page consumed!',
            eventAction: '[[title]]',
            eventLabel: '[[uri]]',
            eventValue: ioq.get('c.scorings.events.pagetracker_page_consumed', 0),
            nonInteraction: false
        };
        io('event', evtDef);
    };

    this.formatPer = function formatPer(value) {
        value = value || 0;
        return Math.round(Math.min(100, Math.max(0, value)));
    };

    this.handlePageAbandoned = function handlePageAbandoned() {
        return this.sendPageDepthEvents();
    };

    this.handleUnload = function handleUnload(event) {
        // don't send page depth events if mailto or tel link click
        var linkClicks = [], lastI, lastClick;
        if (_ioq.plugins.linktracker && _ioq.isFunction(_ioq.plugins.linktracker.getLinkClicks)) {
            linkClicks = _ioq.plugins.linktracker.getLinkClicks();
        }
        if (lastI = linkClicks.length) {
            lastI--;
            lastClick = linkClicks[lastI];

            if (
              lastClick.hrefType && lastClick.timeDelta
              && (lastClick.hrefType == 'mailto' || lastClick.hrefType == 'tel')
              && (ioq.getTimeDelta() - lastClick.timeDelta < 2)
            ) {
                if (this.linkClicksIgnored.indexOf(lastI) == -1) {
                    this.linkClicksIgnored.push(lastI);
                    return;
                }
            }
        }

        this.sendPageDepthEvents();
    };

    this.sendPageDepthEvents = function () {
        // only fire depth events once per page
        if (this.depthEventsSent) {
            return;
        }
        this.depthEventsSent = 1;

        // if page has been left open for more than 30 mins, don't send event.
        if (ioq.getTimeDelta() > 3600) {
            return;
        }

        // detect if
        var td0, m, s, si, inc, scroll;
        var maxTime = 600;
        var td = ioq.getVisibleTime();

        if (td > maxTime) {
            td = maxTime;
        }
        var tdr = Math.round(td);
        var ts = [];
        if (td < 120) {
            inc = 10;
        }
        else if (td < 300) {
            inc = 30;
        }
        else if (td < 600) {
            inc = 60;
        }
        if (inc) {
            m = Math.floor(tdr / 60);
            s = tdr % 60;
            si = (inc * Math.floor(s / inc));
            ts.push((m < 10) ? '0' + m : m);
            ts.push(':');
            ts.push((si < 10) ? '0' + si : si);
            ts.push(' - ');
            td0 = tdr + inc;
            m = Math.floor(td0 / 60);
            s = td0 % 60;
            si = (inc * Math.floor(s / inc));
            ts.push((m < 10) ? '0' + m : m);
            ts.push(':');
            ts.push((si < 10) ? '0' + si : si);
            ts = ts.join('');
        }
        else {
            ts = '10:00+';
        }

        var evtDef = {
            eventCategory: 'Page time',
            eventAction: ts,
            eventLabel: '' + tdr,
            eventValue: tdr,
            nonInteraction: true,
            transport: 'beacon',
            domEventType: 'beforeunload'
            //metric8: tdr,
            //metric9: 1
        };
        io('event', evtDef);


        scroll = ioq.get('p.scroll', {});
        if (scroll.contentBottomMaxPer) {
            // make sure m is between 0 & 100
            var sd = this.formatPer(scroll.contentBottomMaxPer);
            var sdr = (Math.round(sd / 10) * 10);
            ts = '';
            if (sdr < 10) {
                ts = '~  ';
            }
            else if (sdr < 100) {
                ts = '~';
            }

            var evtDef = {
                eventCategory: 'Page scroll',
                eventAction: ts + sdr + '%',
                eventLabel: '' + sd,
                eventValue: sd,
                nonInteraction: true,
                transport: 'beacon',
                domEventType: 'beforeunload',
                metric8: 1,
                metric9: tdr,
                metric10: scroll.pageMax,
                metric11: this.formatPer(scroll.bottomMaxPer),
                metric12: this.formatPer(scroll.bottomInitPer)

                //metric13: scroll.contentMax,
                //metric14: ioq.round(this.formatPer(scroll.contentBottomInitPer), 3),
                //metric15: ioq.round(this.formatPer(scroll.contentBottomMaxPer), 3),
            };
            io('event', evtDef);
        }


        // send timing event
        io('ga.send', 'timing', 'Page visibility', 'visible', Math.round(1000 * td));

    };
    _ioq.push(['addCallback', 'domReady', this.init, this]);
    //this.init();
}

_ioq.push(['providePlugin', 'pagetracker', L10iPageTracker, {}]);