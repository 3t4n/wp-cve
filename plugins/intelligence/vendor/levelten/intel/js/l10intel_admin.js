var _ioq = _ioq || [];

function L10iAdmin(_ioq, config) {
    var ioq = _ioq;
    var io = _ioq.io;
    var eventTests = [];
    var eventBindReported = [];

    this.init = function init() {
        ioq.log(ioq.name + ':admin.init()');//
        if (!ioq.settings.admin) {
            return;
        }
        var ths = this;
        io('addCallback', 'bindEvent', ths.bindEventCallback);
        io('addCallback', 'triggerEventAlter', ths.triggerEventAlterCallback);
        io('addCallback', 'triggerEvent', ths.triggerEventCallback);
        io('addCallback', 'domReady', this.domReady, this);
    };

    this.domReady = function () {
        if (ioq.$content) {
            ioq.$content.css('outline', '3px dashed #44FF44');
        }
    };

    this.bindEventCallback = function bindEventCallback(evtDef, $target) {
//console.log('admin:bindEventCallback()', evtDef, $target);
        var evt = {};
        var options = {
            test: 1,
            admin: {
                bindTarget: []
            }
        };

        $target.each(function(index, value) {
            var evt = {};
            var $value = jQuery(value);

            var ret = 0;
            // check if default eventHandler is overridden
            if (evtDef.onHandler) {
                // spoof event for custom handler
                evt.data = {
                    io: {
                        options: options
                    }
                };
                evt.target = value;
                ret = evtDef.onHandler(evt);
            }
            else {
                ret = _ioq.defEventHandler(evtDef, $value, evt, options);
            }

            //if (_ioq.isObject(ret) && _ioq.isObject(ret.gaEvent) && ret.gaEvent.eventCategory) {
            //    bindTargets.push(value);
            //}
        });

        var logObj = {
            eventDef: evtDef,
            bindTarget: options.admin.bindTarget
        };
    };

    this.setBindTarget = function ($target) {
        /*
        var overlay = document.createElement('div');
        var rect = $target.get(0).getBoundingClientRect();
        overlay.className = 'io-bind-target-overlay';
        overlay.style.top = rect.top +'px';
        overlay.style.left = rect.left +'px';
        overlay.style.width = rect.width +'px';
        overlay.style.height = rect.height +'px';
        document.body.appendChild( overlay );
        console.log(overlay);
        */
        // sometimes an element does not assume the dimensions of its children. This loop will find the widest element
        // and set it to be highlighted
        var $highlight = $target;
        var $wide_child;
        var maxWidth = $target.width();
        var $children = $target.children();

        if ($children.length) {
            $wide_child = $children.each( function (index, value) {
                var $value = jQuery(value);
                if ($value.width() > maxWidth) {
                    $wide_child = $value;
                    maxWidth = $value.width();
                }
                return $wide_child;
            });
        }

        if ($wide_child && $wide_child.width() > $highlight.width()) {
            $highlight = $wide_child;
        }

        $highlight.css('outline', '3px solid #44FF44');
        //$target.css('border', '2px solid #44FF44');
        if (ioq.isDebug()) {
            $target.addClass('io-admin-bind-target');
        }
    };

    this.triggerEventAlterCallback = function triggerEventAlterCallback(trigEvt, $target, event, options, evtDef) {
        if (!options.test)  {
            options.test = 2;
        }
    };

    this.triggerEventCallback = function triggerEventCallback(trigEvt, $target, event, options, evtDef, gaEvt) {
        if (!options.test)  {
            return;
        }

        var target;
        var prevent = 0;
        if (ioq.location.params['io-admin-prevent'] && ioq.isFunction(event.preventDefault)) {
            event.preventDefault();
        }

        var logObj = {
            eventDef: evtDef
        };

        // binding stage
        if (options.test == 1) {
            if (ioq.isObject(gaEvt) && gaEvt.eventCategory) {
                if (ioq.is$Object($target)) {
                    options.admin.bindTarget.push($target.get(0));
                }
            }
            //$target.css('outline', '4px solid #33FF33');
            io('admin:setBindTarget', $target);
            return;
        }
        // trigger stage
        if (options.test == 2) {
            ds = '';
            for (i in gaEvt) {
                if (ds) {
                    ds += ',';
                }
                ds += '\n  ' + i + ': ';
                if (ioq.isString(gaEvt[i])) {
                    ds += "'" + gaEvt[i] + "'";
                }
                else {
                    ds += gaEvt[i];
                }
            }
            alert("ga.send.event: {" + ds + '\n}');
            logObj.target$ = $target;
            logObj.event = event;
            logObj.trigEvt = trigEvt;
            logObj.gaEvt = gaEvt;
            logObj.options = options;
        }

        //alert("ga.send.event: ");
    };

    _l10iq.push(['addCallback', 'configReady', this.init, this]);
}

_ioq.push(['providePlugin', 'admin', L10iAdmin, {}]);