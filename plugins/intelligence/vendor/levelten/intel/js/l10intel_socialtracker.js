var _l10iq = _l10iq || [];

function L10iSocialTracker(_ioq, config) {
    var ioq = _ioq;
    var io = _ioq.io;

    this.socialDefs = [];
    this.socialDefs.push({
        key: 'facebook',
        title: 'Facebook',
        hostname: ['facebook.com'],
        share: [
            {
                pathname: '/sharer.php'
            },
            {
                pathname: '/dialog/share'
            }
        ]
    });
    this.socialDefs.push({
        key: 'googleplus',
        title: 'Google+',
        hostname: ['plus.google.com'],
        share: [
            {
                pathname: '/share'
            }
        ]
    });
    this.socialDefs.push({
        key: 'instagram',
        title: 'Instagram',
        hostname: ['instagram.com']
    });
    this.socialDefs.push({
        key: 'linkedin',
        title: 'LinkedIn',
        hostname: ['linkedin.com'],
        share: [
            {
                pathname: '/shareArticle'
            }
        ]
    });
    this.socialDefs.push({
        key: 'pinerest',
        title: 'Pinterest',
        hostname: ['pinterest.com'],
        share: [
            {
                pathname: '/pin/create/bookmarklet'
            }
        ]
    });
    this.socialDefs.push({
        key: 'twitter',
        title: 'Twitter',
        hostname: ['twitter.com'],
        share: [
            {
                pathname: '/intent/tweet'
            }
        ]
    });
    this.socialDefs.push({
        key: 'youtube',
        title: 'YouTube',
        hostname: ['youtube.com'],
        share: [
            {
                pathname: '/intent/tweet'
            }
        ]
    });

    this.socialHostnames = {};
    this.socialNames = {};

    this.init = function init() {
        var ths = this, i, v, j, w;

        // index social defs for faster access
        for (i = 0; i < this.socialDefs.length; i++) {
            v = this.socialDefs[i];
            this.socialNames[v.key] = i;
            for (j = 0; j < v.hostname.length; j++) {
                this.socialHostnames[v.hostname[j]] = i
            }
        }

        ioq.addCallback('handleLinkEventAlter', this.handleLinkEventAlter, this);
        //$('a').not('.linktracker-0').on('click', ths.eventHandler);
        //$('a').on('mouseover', {eventType: 'click'}, ths.eventHandler); // for testing event sends
    };

    this.addSocialDef = function (name, def) {
        this.socialDefs[name] = def;
    };

    this.handleLinkEventAlter = function handleLinkEventAlter(f) {

        var action, net, def, hostname, i, v;

        // check if hrefType is external
        if(f.hrefType == 'external') {
            if (!f.hrefObj) {
                f.hrefObj = ioq.parseUrl(f.href);
            }

            // remove www. if on hostname
            hostname = (f.hrefObj.hostname.substr(0, 4) == 'www.') ? f.hrefObj.hostname.substring(4) : f.hrefObj.hostname;

            if (this.socialHostnames[hostname] != undefined) {
                var def = this.socialDefs[this.socialHostnames[hostname]];

                if (f.hrefObj.pathname.substr(-1) == '/') {
                    f.hrefObj.pathname = f.hrefObj.pathname.slice(0, -1);
                }

                var action = 'profile';
                if (def.share && ioq.isArray(def.share)) {
                    for (i = 0; i < def.share.length; i++) {
                        v = def.share[i];
                        if (v.pathname && (v.pathname == f.hrefObj.pathname)) {
                            action = 'share';
                            break;
                        }
                    }
                }
            }
        }

        // process object settings
        if (f.$obj.objSettings['social-action']) {
            action = f.$obj.objSettings['social-action'];
        }

        if (!action) {
            return f;
        }

        if (f.$obj.objSettings['social-network']) {
            net = f.$obj.objSettings['social-network'];
        }

        var eventKey = 'socialtracker_social_' + action + '_' + f.eventType;
        if (ioq.eventDefsIndex[eventKey]) {
            f.evtDef = ioq.eventDefs[ioq.eventDefsIndex[eventKey]];
            f.evtDef.eventAction = f.evtDef.socialNetwork = net || def.title;
            f.evtDef.socialAction = action;
            f.linkType = 'social';
        }
        else if (action && net) {
            f.evtDef = {};
            f.evtDef.eventCategory = 'Social ' + action + ' ' + f.eventType;
            f.evtDef.eventCategory = 'Social ' + action + ' ' + f.eventType;
            f.evtDef.eventAction = f.evtDef.socialNetwork = net;
            f.evtDef.socialAction = action;
            f.linkType = 'social';
        }
        //f.hrefType = 'social';
        //f.hrefTypeDefs.social = {
        //    title: 'Social ' + action
        //};




        //f.evtDef.triggerCallback = [{callback: 'socialtracker:eventHandler'}];


        return f;
    };

    this.eventHandlerAlter = function eventHandlerAlter (evtDef, $target, event, options) {
        var a, href, parsedHref;
        if (!evtDef.socialNetwork) {
            a = $target.attr('data-io-social-network');
            if (a) {
                evtDef.socialNetwork = a;
            }
        }
        if (!evtDef.eventAction && evtDef.socialNetwork) {
            evtDef.eventAction = evtDef.socialNetwork;
        }
    };

    this.eventHandler = function eventHandler(evtDef, $target, event, options) {
        if (evtDef.socialNetwork && evtDef.socialAction) {
            var socialDef = {
                socialNetwork: evtDef.socialNetwork,
                socialAction: evtDef.socialAction,
                socialTarget: _ioq.location.href,
                hitType: 'social'
            };
            if (!options.test) {
                io('ga.send', socialDef);
            }
        }
    };

    this.init();
}

_l10iq.push(['providePlugin', 'socialtracker', L10iSocialTracker, {}]);