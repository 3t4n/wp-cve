var _ioq = _ioq || [];

function L10iFormTracker(_ioq, config) {
    var ioq = _ioq;
    var io = _ioq.io;
    var $ = jQuery;

    var ths = {};

    this.formDefs = [];
    this.formDefsIndex = {};


    this.init = function init() {
        var ths = this;
        //$('form').not('.formtracker-0').on('submit', ths.eventHandler);
        //$('a').on('mouseover', {eventType: 'click'}, ths.eventHandler); // for testing event sends
    };

    this.trackForm = function trackForm(def) {
        if (ioq.isArray(def)) {
            for (var i = 0; i < def.length; i++) {
                this.trackFormInit(def[i]);
            }
        }
        else {
            this.trackFormInit(def);
        }
    };

    this.trackFormInit = function trackFormInit(def) {
        //console.log('trackFormInit()');
        //console.log(def);
        var ths = _ioq.plugins.formtracker;

        var $obj, $this, $onObj, enable = 1, $dataField, def2;
        var key = def.key || def.formId;

        if (ioq.isNull(def)) {
            def = {};
        }
        if (!ioq.isNull(def.landingpage) && !ioq.isNull(ioq.plugins.convtracker)) {
            def.landingpage = ioq.plugins.convtracker.trackLandingpage(def.landingpage);
        }

        if (ioq.isNull(def.selector)) {
            def.selector = 'body';
            def.onSelector = def.onSelector||'form';
        }

        def.oa = def.oa || {};
        def.oa.rc = def.oa.rc || 'form';
        if (!def.oa.rt && def.formType) {
            def.oa.rt = def.formType;
        }
        if (!def.oa.rk && def.formId) {
            def.oa.rk = def.formId;
        }
        if (!def.oa.ri) {
            def.oa.ri = def.formUri || ':' + def.formType + ':' + def.formId;
        }

        def.eventAction = def.eventAction || def.formTitle || ioq.settings.pageTitle;
        def.eventLabel = def.eventLabel || def.oa.ri;


        $obj = ioq.jQuerySelect(def);
        if (def.onSelector) {
            $onObj = $obj.find(def.onSelector);
        }
        else {
            $onObj = $obj;
        }

        // loop through each form found by the selector to check if any special fields exist to disable the onSubmit tracking.
        $onObj.each(function() {
            $this = $(this);
            if (ths.processFormSpecialFields(def, $this)) {
                enable = 0;
            }
        });

        if ($onObj.length && def.trackView) {
            delete def.trackView;
            evtDef = ioq.objectMerge({}, def);
            evtDef.eventCategory = 'Form view';
            evtDef.onEvent = 'pageview';
            evtDef.eid = 'formView';
            evtDef.nonInteraction = 1;
            io('event', evtDef);
        }
        if ($onObj.length && def.trackSubmission) {
            //delete def.trackView;
            evtDef = ioq.objectMerge({}, def);
            evtDef.eventCategory = 'Form submission';
            evtDef.onEvent = 'submission';
            evtDef.eid = 'formSubmission';
            evtDef.nonInteraction = 1;
            io('event', evtDef);
        }

        /* Disabling logic until full conversion tracking can be implemented
        if (ioq.isNull(def.onEvent)) {
            def.onEvent = 'submit';
        }

        def.triggerCallback = ths.saveFormSubmit;


        $obj = ioq.jQuerySelect(def);
        if (def.onSelector) {
            $onObj = $obj.find(def.onSelector);
        }
        else {
            $onObj = $obj;
        }

        // loop through each form found by the selector to check if any special fields exist to disable the onSubmit tracking.
        $onObj.each(function() {
            $this = $(this);
            if (ths.processFormSpecialFields(def, $this)) {
                enable = 0;
            }
        });

        if (enable) {
            def.onHandler = function (event) {
                window._ioq.push(['triggerFormSubmitEvent', def, jQuery(this), event]);
            };
            def.eid = 'formSubmit';
            io('event', def);

            def2 = ioq.objectMerge({}, def);
            def2.onEvent = 'click';
            ioq.jQueryOn(def2, $obj, function(event) {
                $(this).removeClass('l10-form-submit-processed');
            });
        }
        */

        // only set loc form cookie for pro level
        if (ioq._apiLevel == 'pro') {
            ths.setCookie('l10i_lf', ioq.settings.pageUri, 1);
        }


        if (key) {
            if (!ths.formDefsIndex[key]) {
                ths.formDefsIndex[key] = ths.formDefs.length;
                ths.formDefs.push(def);
            }
        }

        return def;

    };

    this.processFormSpecialFields = function (def, $obj, event) {
        var pf = 'io';
        if ($obj.is('form')) {
            // check if field has been set to not track
            var enabled = $obj.find('input[name="' + pf + '_track_form"]').val();
            if ((enabled == '0') || (enabled == 'false')) {
                //ths.removeCallback(def.triggerCallback, ths.saveFormSubmit);
                return true;  // jQuery.each() equivalent to "continue";
            }

            // check for data field used to store submit data rather than attaching js submit event
            var $dataField = $obj.find('input[name="' + pf + '_submit_data"]');
            if ($dataField.length == 0) {
                // TODO: this is the format of Webform's hidden fields
                $dataField = $obj.find('input[name="submitted[' + pf + '_submit_data]"]');
            }
            if ($dataField.length == 1) {
                var formSubmit = ths.constFormSubmit(null, $obj);

                formSubmit = JSON.stringify(formSubmit);

                $dataField.val(formSubmit);
                //ths.removeCallback(def.triggerCallback, ths.saveFormSubmit);
                return true;
            }
        }
        return false;
    };

    this.setFormDef = function setFormDef(name, def) {
        var i, a;
        if (ioq.isArray(name)) {
            for (var i = 0; i < name.length; i++) {
                a = name[i];
                if (a[0] && a[1] && ioq.isObject(a[1])) {
                    this.formDefs[a[0]] = a[1];
                }
            }
        }
        else {
            this.formDefs[name] = obj;
        }
    };

    this.getFormDef = function getFormDef(name, defaultValue) {
        if (name == undefined) {
            return this.linkTypeDefs;
        }
        if (this.linkTypeDefs[name] === undefined) {
            return defaultValue;
        }
        return this.linkTypeDefs[name];
    };

    this.eventHandler = function eventHandler(event) {


    };

    this.init();

}

_ioq.push(['providePlugin', 'formtracker', L10iFormTracker, {}]);