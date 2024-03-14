var _ioq = _ioq || [];

function L10iLinkTracker(_ioq, config) {
    var ioq = _ioq;
    var io = _ioq.io;

    this.hrefTypeDefs = {};
    this.hrefTypeDefs.external = {
        title: 'External link'
    };
    this.hrefTypeDefs.download = {
        title: 'Download link'
    };
    this.hrefTypeDefs.mailto = {
        title: 'Mailto link'
    };
    this.hrefTypeDefs.tel = {
        title: 'Tel link'
    };

    this.linkTypeDefs = {};
    this.linkTypeDefs.internal = {
        title: 'Internal link',
        track: 0
    };
    this.linkTypeDefs.external = {
        title: 'External link',
        track: 1
    };
    this.linkTypeDefs.download = {
        title: 'Download link',
        track: 1,
        trackFileExtension: 'zip|exe|dmg|pdfx|doc.*|xls.*|ppt.*|mp3|txt|rar|wma|mov|avi|wmv|flv|wav|png|jpg|jpeg|gif',
    };
    this.linkTypeDefs.mailto = {
        title: 'Mailto link',
        track: 1
    };
    this.linkTypeDefs.tel = {
        title: 'Tel link',
        track: 1
    };

    this.linkClicks = [];


    this.init = function init() {
        ioq.log(ioq.name + ':linktracker.init()');

        var ths = this;
        var evtDef = {
            key: 'linktracker_link_click',
            selector: 'a',
            selectorNot: '.io-link-ignore',
            onEvent: 'click',
            //onEvent: 'contextmenu',
            onHandler: function (event) { ths.handleLinkEvent(event) },
            transport: 'beacon'
        };
        io('event', evtDef);

        return;

        /*
        var $target = $('a.temp').not('.io-link-ignore');
        var evtDef = {
            onHandler: function (ed, $t, e) {
                var e2 = {};
                return ths.eventHandler({});
            }
        };
        $('a').not('.linktracker-0').on('click', ths.eventHandler);
        //$('a').on('mouseover', {eventType: 'click'}, ths.eventHandler); // for testing event sends
        if (_ioq.location.params['io-admin']) {
          $('a').not('.linktracker-0').each(function (index, value) {
            var event = {
              type: 'click'
            };
            var $target = $(this);
            var options = {
              test: 1
            };
            var ret = ths.eventHandler(event, $target, options);
            if (!_ioq.isEmpty(ret)) {
              //$target.addClass('io-event-binded io-event-binded-linktracker');
            }
          });
        }
        */
    };

    this.setLinkTypeDef = function setLinkTypeDef(name, def) {
        var i, a;
        if (ioq.isArray(name)) {
            for (var i = 0; i < name.length; i++) {
                a = name[i];
                if (a[0] && a[1] && ioq.isObject(a[1])) {
                    this.linkTypeDefs[a[0]] = a[1];
                }
            }
        }
        else {
            this.linkTypeDefs[name] = obj;
        }
    };

    this.getLinkTypeDef = function getLinkTypeDef(name, defaultValue) {
        if (name == undefined) {
            return this.linkTypeDefs;
        }
        if (this.linkTypeDefs[name] === undefined) {
            return defaultValue;
        }
        return this.linkTypeDefs[name];
    };

    this.addHrefType = function addHrefType(name, obj) {
        this.hrefTypeDefs[name] = obj;
    };

    this.removeHrefType = function removeHrefType(name) {
        if (this.hrefTypeDefs[name]) {
            delete(this.hrefTypeDefs[name]);
        }
    };

    this.getLinkClicks = function() {
        return this.linkClicks;
    };

    this.handleLinkEvent = function handleLinkEvent(event) {

        var i, v, attributes = {}, linkDef;
        var f = {
            event: event,
            hrefTypeDefs: this.hrefTypeDefs,
            linkTypeDefs: this.linkTypeDefs
        };

        v = _ioq.getEventArgsFromEvent(event);
        f.evtDef = v[0], f.$obj = v[1], f.options = v[3];

        f.eventType = event.type;
        if (!f.eventType && f.evtDef.onEvent) {
            f.eventType = f.evtDef.onEvent;
        }


        f.href = f.$obj.attr('href');

        if (!f.href) {
            return;
        }

        f.$obj.objSettings = f.$obj.objSettings || ioq.getObjSettings(f.$obj);

        if (f.$obj.objSettings['link-ignore'] || f.$obj.objSettings['link-' + f.eventType + 'ignore']) {
            return;
        }

        f.hrefType = '';
        if (!f.hrefType) {
            //var downloadPattern = /\.(zip|exe|dmg|pdf|doc.*|xls.*|ppt.*|mp3|txt|rar|wma|mov|avi|wmv|flv|wav|png|jpg|jpeg|gif)$/i;
            var downloadPattern = new RegExp('\\.(' + f.linkTypeDefs['download']['trackFileExtension'] + ')$', 'i');

            f.hrefObj = _ioq.parseUrl(f.href);

            f.hrefObj.external = (f.hrefObj.hostname != ioq.location.hostname);

            if (f.href.substr(0, 7) == 'mailto:') {
                f.hrefType = 'mailto';
            }
            else if (f.href.substr(0, 4) == 'tel:') {
                f.hrefType = 'tel';
            }
            else if (f.href.substr(0, 11) == 'javascript:') {
                f.hrefType = 'javascript';
            }
            else if (f.hrefObj.external) {
                f.hrefType = 'external';
            }
            else if (f.href.match(downloadPattern)) {
                f.hrefType = 'download';
            }
            else {
                f.hrefType = 'internal';
            }
        }

        f.linkType = f.hrefType;

        if (f.$obj.objSettings['link-type']) {
            f.linkType = f.$obj.objSettings['link-type'];
        }

        // trigger callbacks
        ioq.triggerCallbacks('handleLinkEventAlter', f);
        linkDef = f.linkTypeDefs[f.linkType] || {};
        if (linkDef.track || f.$obj.objSettings['link-track'] || f.$obj.objSettings['link-' + f.eventType + '-track']) {
            f.evtDef.eventCategory = linkDef.title;
            if (f.eventType) {
                f.evtDef.eventCategory += ' ' + f.eventType
            }
            if (linkDef.clickMode) {
                f.evtDef.mode = linkDef.clickMode;
            }
            if (linkDef.clickValue) {
                f.evtDef.eventValue = linkDef.clickValue;
            }
            // force re-construct
            delete(f.evtDef.const);
        }

        v = {
            event: f.event,
            hrefType: f.hrefType,
            timeDelta: ioq.getTimeDelta()
        };
        this.linkClicks.push(v);

        if (f.evtDef.eventCategory) {
            return _ioq.defEventHandler(f.evtDef, f.$obj, f.event, f.options);
        }
    };

    this.init();
    //_ioq.push(['addCallback', 'domReady', this.init, this]);
}

_ioq.push(['providePlugin', 'linktracker', L10iLinkTracker, {}]);