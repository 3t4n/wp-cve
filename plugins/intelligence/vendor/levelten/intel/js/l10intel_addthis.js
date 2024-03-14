var _l10iq = _l10iq || [];

function L10iAddthis(_ioq, config) {
    var ioq = _ioq;
    var io = _ioq.io;
    this.waits = 0;

    this.init = function init() {
        var waits, i;

        io('log', 'L10iAddthis.init()');
        if ((typeof addthis != 'object') || (typeof addthis.addEventListener != 'function')) {

            if (this.waits < 5) {
                this.waits++;
                var delay = (this.waits >= 2) ? 2500 : 1000;
                with (this) {
                    setTimeout(function () {
                        init();
                    }, delay);
                }
            }
            return;
        }
        else {
            //addthis.addEventListener('addthis.ready', this.onReady);
            addthis.addEventListener('addthis.menu.share', this.onSocialShare);
            addthis.addEventListener('addthis.menu.follow', this.onSocialFollow);
            addthis.addEventListener('addthis.user.clickback', this.onSocialShareClickback);
            io('addCallback', 'domReady', this.onReady, this);
            //this.onReady({});
        }
    };

    this.getUser = function () {
        if (addthis.user == undefined || (typeof addthis.user.ready != 'function')) {
            return null;
        }
        return addthis.user;
    };

    this.onReady = function (evt) {
        // verify addthis returned proper user object
        if (addthis.user == undefined || (typeof addthis.user.ready != 'function')) {
            return;
        }

        addthis.user.ready(function (data) {
            var i, services = {}, count, val;
            // verify we have proper user.services object
            if (typeof addthis.user.services == 'function') {
                var s = addthis.user.services();
                if (ioq.isArray(s) && ioq.isFunction(s.toMap)) {
                    services = s.toMap();
                }
            }
            count = 0;
            val = {};
            for (i in services) {
                if (services.hasOwnProperty(i)) {
                    val[services[i]['name']] = Number(services[i]['score']);
                    count++;
                }
            }
            if (count) {
                io('set', 'v:addthis.services', val);
            }

            var geo = addthis.user.location();
            val = {};
            if (ioq.isObject(geo) && geo['country'] !== 'undefined') {
                var e = ['country', 'dma', 'lat', 'lon', 'msa', 'region', 'zip'];
                for (i = 0; i < e.length; i++) {
                    if (geo[e[i]] == undefined) {
                        continue;
                    }
                    val[e[i]] = geo[e[i]];
                }
                if (val.lat && val.lon) {
                    val.lat = parseFloat(val.lat);
                    val.lon = parseFloat(val.lon);
                    io('set', 'v:addthis.geo', val);
                    if (ioq.isFunction(ioq.hasSchema) && ioq.hasSchema('GeoCoord')) {
                        var gs = ioq.new('GeoCoord', val);
                        ioq.set('s:geo', gs, {_source: 'addthis'});
                    }
                }
            }
            var last_set = io('getFlag', 'session', 'addthis');
            var timestamp = io('getTime');
            if ((count > 0) && ((last_set == undefined) || ((timestamp - last_set) > (60 * 60 * 24)))) {
                io('setFlag', 'session', 'addthis', timestamp, true);
            }
        });
    };

    this.onSocialShare = function (evt) {
        var ignore = {
            'more': 1
        };

        if (ignore[evt.data.service]) {
            return;
        }
        var ga_event = {
            'eventCategory': "Social share click!",
            'eventAction': (typeof addthis.util.getServiceName(evt.data.service) != 'undefined') ? addthis.util.getServiceName(evt.data.service) : evt.data.service,
            'eventLabel': "[[systemAlias]]",
            'eventValue': io('get', 'config.scorings.events.addthis_social_share', 0),
            'nonInteraction': false,
            'eid': 'socialShareClick'
        };
        io('event', ga_event);
    };

    this.onSocialShareClickback = function (evt) {
        var ga_event = {
            'eventCategory': "Social share clickback!",
            'eventAction': (typeof addthis.util.getServiceName(evt.data.service) != 'undefined') ? addthis.util.getServiceName(evt.data.service) : evt.data.service,
            'eventLabel': "[[systemAlias]]",
            'eventValue': io('get', 'config.scorings.events.addthis_social_share_clickback', 0),
            'nonInteraction': false,
            'eid': 'socialShareClickback'
        };
        io('event', ga_event);
    };

    this.onSocialFollow = function (evt) {
        var ga_event = {
            'eventCategory': "Social profile click!",
            'eventAction': (typeof addthis.util.getServiceName(evt.data.service) != 'undefined') ? addthis.util.getServiceName(evt.data.service) : evt.data.service,
            'eventLabel': (evt.data.url) ? evt.data.url : "(not set)",
            'eventValue': io('get', 'config.scorings.events.addthis_social_follow', 0),
            'nonInteraction': false,
            'eid': 'socialProfileClick'
        };
        io('event', ga_event);
    };

    this.init();
}

_l10iq.push(['providePlugin', 'addthis', L10iAddthis, {}]);