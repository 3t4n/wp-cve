


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
    this.realtimeApiUrl = '';
    this.realtimeDataUrl = '';
    this.timeDelta = 0;
    this.timeDeltaCorection = 0;
    this.lastFetchTime = 0;
    this.stats = {
        site: {},
        pages: {},
        pageAttrs: {},
        ts: {},
        tsDetails: {},
        events: {},
        ctas: {},
        lps: {},
        visitors: {}
    };
    this.statsActive = {
        site: {},
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
        /*
    this.paLabels = {
        a: 'Author',
        ct: 'Content type',
        t: 'Terms',
        i: 'Page intent'
    }
    this.authors = {
        1: 'User 1',
        3: 'Tom McCracken',
        4: 'Brent Bice'
    };
    this.authors = {};
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
    this.terms = {};
    this.contentTypes = {
        'page': 'Basic page',
        'blog': 'Blog post',
        'webform': 'Webform',
        'landingpage': 'Landing page',
        'thankyou': 'Thank you page',
        'press_release': 'Press release'
    };
    this.contentTypes = {};
    */
    this.visitors = {};
    this.scorings = {
        entrance: .05,
        stick: .1,
        additional_pages: .02
    };

    this.attrOptionInfoRequested = {
        page: {},
        visitor: {}
    }
    this.attrInfo = {
        page: {},
        visitor: {}
    }
    this.attrInfo.page = {
        a: {
            title: 'Author',
            type: 'scalar',
            options: {}
        },
        ct: {
            title: 'Content type',
            type: 'scalar',
            options: {}
        },
        t: {
            title: 'Terms',
            type: 'list',
            options: {}
        },
        i: {
            title: 'Page intent',
            type: 'list',
            options: {}
        }
    }
    this.attrInfo.visitor = {
        g: {
            title: 'Groups',
            type: 'list'
        },
        i: {
            title: 'Interests',
            type: 'vector'
        },
        k: {
            title: 'Known',
            type: 'flag'
        },
        l: {
            title: 'Lead score',
            type: 'scalar'
        },
        s: {
            title: 'Score',
            type: 'scalar'
        },
        r: {
            title: 'User roles',
            type: 'list'
        }
    }

    this.init = function () {
        this.realtimeApiUrl = rtdConfig.settings.apiPath + '/';
        this.realtimeDataUrl = rtdConfig.settings.dataPath + '/';

        // if this window is the main report, then initiate polling
        if ((rtdConfig.role == 'all') || rtdConfig.role == 'master') {
            this.pole();
        }

        this.attrInfo = rtdConfig.settings.attrInfo;
    }

    this.pole = function pole() {
        // limit fetch starttime to 30 minutes ago
        this.lastFetchTime = this.getTime() - 1800;
        rtdModel.fetchAll();

        window.setInterval(function () {
            rtdModel.fetchAll();
        }, 4000);
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
                // sync with server time if timeDelta not yet set
                if (rtdModel.timeDeltaCorection == 0) {
                    rtdModel.timeDeltaCorection = parseInt(json.server_time);
                    var time = new Date().getTime();
                    rtdModel.timeDelta = parseInt(json.server_time) - Math.round(time/1000);

                    console.log('browser time: ' + Math.round(time/1000) + ', server time: ' + json.server_time);
                }
                if (json.status == 200) {
                    console.log('Log data recieved:');
                    console.log(json);
                }
                rtdModel.addToLog(json.instances, json.ids, json.last_id);
                //rtdModel.buildTimeline();
            }
        };
        //console.log(vars);
        jQuery.ajax(vars);
    };

    this.addToLog = function addToLog(data, ids, lastId) {
        var time;
        for (var i = 0; i < data.length; i++) {
            if (ids[i] < this.logLastId) {
              continue; // we got duplicate data for some reason
            }
            var e = data[i];
            if (e.t == undefined) {
                continue;
            }
            time = e.t;

            if (e.type == 'var') {
                this.addVar(e);
                continue;
            }

            // filter query params
            var p = data[i].p.split('?');
            data[i].p = p[0];
            // TODO add query inclusions via settings.queryParamsFilter

            // unserialise page attributes
            if (data[i].pa != undefined) {
                data[i].pa = this._unserializeCustomVar(data[i].pa);
            }
            // unserialise traffic source
            if (data[i].ts != undefined) {
                data[i].ts = this._unserializeCustomVar(data[i].ts);
            }
            if (data[i].va != undefined) {
                data[i].va = this._unserializeCustomVar(data[i].va);
            }
            if (data[i].va0 != undefined) {
                data[i].va0 = this._unserializeCustomVar(data[i].va0);
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
//console.log(data);
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
        // set
        this.logLastId = lastId;
        if (data.length > 0) {
            //console.log('New Data (addToLog):');
            //console.log(data);
            //console.log(this.logNew);
        }
        //console.log(this.log);
    };

    this.addVar = function (e) {
        if (e.scope == 'visitor') {
            if (this.visitors[e.vtk] == undefined) {
                this.visitors[e.vtk] = {
                    name: 'anon (' + e.vtk.substr(0,10) + ')',
                    sessions: {}
                };
            }
            if (e.namespace != undefined) {
                if (e.namespace == 'addthis') {
                    if ((e.value.geo != undefined)
                      && (e.value.geo.lat != undefined)
                      && (e.value.geo.lon != undefined)
                        ) {
                        this.visitors[e.vtk]['location'] = {
                          lat: e.value.geo.lat,
                          lon: e.value.geo.lon
                        };
                    }
                    if (e.value.services != undefined) {
                        this.visitors[e.vtk]['sharing'] = e.value.services;
                    }
                }
                rtdView.updateVisitorVar(e.vtk, 'addthis');
            }
        }
        /*
        if (e.scope == 'visitor') {
            if (this.visitors[e.vtk]['data'] == undefined) {
                this.visitors[e.vtk]['data'] = {};
            }
            var keys = '';
            if (e.namespace != undefined) {
                keys += e.namespace;
            }
            if (e.keys != undefined) {
                keys += ((keys.length > 0) ? '.' : '') + e.keys
            }
            var keysArr = keys.split('.');
            for (var j = 0; j < keysArr.length; j++) {

            }
        }
        */
    }

    this.fetchVisitor = function (vtk, callback) {
        var func = 'visitor_load_js/' + vtk;
        var time = rtdModel.getTime();
        var params = {
            vtk: vtk,
            t: time
        };
        var vars = {
            dataType: 'json',
            url: this._getRealtimeDataUrl(func, params),
            data: {},
            success: function (json){
                if (true) {
                    console.log('Visitor data recieved:');
                    console.log(json);
                }
                if (json.visitor == undefined) {
                    return;
                }
                var vtk = json.vtk;
                rtdModel.visitors[vtk].data = json.visitor;
                if (json.visitor.name == undefined) {
                    return;
                }
                rtdModel.visitors[vtk].name = json.visitor.name;
                if ((json.visitor.image != undefined) && (json.visitor.image.url != undefined)) {
                    rtdModel.visitors[vtk].image = json.visitor.image.url;

                    var css = document.createElement('style');
                    css.innerHTML = '.visitor-image-' + vtk + ' { background-image:url("' + json.visitor.image.url + '"); background-size: contain; }';
                    document.body.appendChild(css);

                    //rtdModel.visitors[vtk].image = new Image();
                    //rtdModel.visitors[vtk].image.src = json.visitor.image.url;
                }
                if (callback != undefined) {
                    callback(vtk, json.visitor);
                }
            }
        };
        //console.log(vars);
        jQuery.ajax(vars);
    };

    this.fetchAttributeOptionInfo = function (mode, attrKey, optionId, callback, timeout) {
        // check if this data has already been requested
        var time = rtdModel.getTime();
        if (this.attrOptionInfoRequested[mode] == undefined) {
            this.attrOptionInfoRequested[mode] = {};
        }
        if (this.attrOptionInfoRequested[mode][attrKey] == undefined) {
            this.attrOptionInfoRequested[mode][attrKey] = {};
        }
        if (this.attrOptionInfoRequested[mode][attrKey][optionId] != undefined) {
            return;
        }
        this.attrOptionInfoRequested[mode][attrKey][optionId] = time;
        var func = 'attribute_option_info_js/' + mode + '/' + attrKey + '/' + optionId;
        var vars = {
            dataType: 'json',
            url: this.realtimeDataUrl + func,
            data: {},
            success: function (json){
                //console.log(json);
                if (json.status != 200) {
                    return;
                }
                rtdModel.attrInfo[mode][attrKey].options[optionId] = {
                    title: json.option.title
                }
                if (callback != undefined) {
                    if (timeout == undefined) {
                        timeout = 0;
                    }
                    setTimeout(function () {callback(json.option.mode, json.option.type, json.option.id, json.option);}, timeout);
                    //callback(json.option.type, json.option.id, json.option);
                    //callback(json.option.type, json.option.id, json.option);
                }
            }
        };
//console.log(vars.url);
        jQuery.ajax(vars);
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

    this._getRealtimeDataUrl = function _getJSONUrl(func, params, data) {
        var url;
        url = this.realtimeDataUrl + func + '_js';
        var paramStr = this._encodeUrlQueryParams(params);
        if (paramStr != '') {
            url += '?' + paramStr;
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