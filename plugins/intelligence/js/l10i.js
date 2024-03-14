/**
 * @author  Tom McCracken <tomm@levelten.net>
 * @version 0.4.0 beta release
 * @copyright 2013-2019 LevelTen Ventures
 *
 * All rights reserved. Do not use without permission.
 *
 */

// ==ClosureCompiler==
// @compilation_level SIMPLE_OPTIMIZATIONS
// @output_file_name default.js
// ==/ClosureCompiler==

//io('addCallback', 'addEvent', function(evtDef) {console.log('addEvent'); console.log(evtDef)});
//io('addCallback', 'bindEvent', function(evtDef, $obj) {console.log('bindEvent'); console.log(evtDef); console.log($obj);});
//io('addCallback', 'triggerEventAlter', function(evtDef, $obj) {console.log('triggerEventAlter'); console.log(evtDef); console.log($obj);});

// Testing
// jslint.com predev:_gaq _l10iq console window document escape unescape jQuery
// config var
//var _l10iss={"status":200,"store":"mc","apiLevel":"pro","apiUrl":"api.getlevelten.com/v1/intel/"};
var _l10iss={"status":200,"store":"mc","apiLevel":"basic","apiUrl":""};

var _l10iq = _l10iq || [];
var _ioq = _ioq || [];

if ((typeof _l10im == 'object') && (typeof _l10im.log == 'function')) {
  _l10im.log('l10i.js load');
}

// creates JSON.stringify method if does not exist in older browser version
if (window.JSON === undefined) {
  window.JSON = {
    parseJSobject: function(object) {
      var temp = '{',
        s = 0,
        i;
      for (i in object) {
        if (!object.hasOwnProperty(i)) {
          continue;
        }
        if (s) {
          temp += ',';
        }
        temp += '"' + i + '":';
        if (typeof object[i] === 'object') {
          temp += window.JSON.parseJSobject(object[i]);
        } else {
          temp += '"' + object[i] + '"';
        }
        s++;
      }
      temp += '}';
      return temp;
    },
    stringify: function(data) {
      return window.JSON.parseJSobject(data);
    }
  };
}

var _ioq = (function(q) {
  'use strict';

  /** @const */
  var ENCODE_VAR_EQ = '=';
  /** @const */
  var ENCODE_VAR_SEP = '&';

  // base property declarations
  var win = window;
  var loc = win.location;
  var doc = document;
  //var docElm = doc.documentElement;

  // define private (ths) & public (pub) objects
  var ths = {}; // container for private global variables
  ths.ver = '0.4.0';  // version of the object
  ths.apiVer = '1';
  ths.queue = q || [];

  /*************************************
   * Utility functions
   * @type {Function}
   */

  var isNull = ths.isNull = function (value) {
    return (typeof value == 'undefined' || value === null);
  };


  var isEmpty = ths.isEmpty =  function (value) {
    // if object is falsy, return true
    // objects and arrays even if empty will return true
    if (!value) {
      return true;
    }

    if (isArray(value)) {
      return value.length == 0;
    }
    else if (isStdObject(value)) {
      for (var i in value) {
        if (value.hasOwnProperty(i)) {
          return false;
        }
      }
      return true;
    }
    return false;
  };

  var isArray = ths.isArray = function (value) {
    return Object.prototype.toString.call(value) === "[object Array]";
  };

  // tests if a value is an object. Note in JS, arrays and functions are also objects.
  // so this function will return true for associative objects,
  var isObject = ths.isObject = function (value) {
    return value === Object(value);
  };

  // returns true for a json style object
  var isStdObject = ths.isStdObject = function (value) {
    return Object.prototype.toString.call(value) === "[object Object]";
  };

  // tests if an object is a jQuery object
  var is$Object = ths.is$Object = function (value) {
    return value instanceof jQuery;
  };

  // test if object is an event. We test if target or srcElement property exists which is also try for an eventDef
  // so test if a stdObject.
  var isEvent = ths.isEvent = function (value) {
    return isObject(value) && !isStdObject(value) && (value.target || value.srcElement);
  }

  var isString = ths.isString = function (value) {
    return Object.prototype.toString.call(value) === "[object String]"
  };

  var isNumber = ths.isNumber = function (value) {
    return !isString(value) && !isNaN(value);
  };

  var isNumeric = ths.isNumeric = function (value) {
    return !isNaN(parseFloat(value)) && isFinite(value);
  };

  var isFunction = ths.isFunction = function (value) {
    return typeof value == 'function';
  };

  var parseUrl = ths.parseUrl = function (url, sourceUrl, options) {
    var l = document.createElement('a');

    options = options || {};

    l.href = url;

    var locObj = {
      href: l.href,
      protocol: l.protocol,
      hostname: l.hostname,
      port: l.port,
      pathname: l.pathname,
      search: l.search,
      hash: l.hash,
      host: l.host,
      path: l.pathname + l.search
    };

    if (options.params) {
      locObj.params = parseUrlSearch(locObj.search);
    }

    return locObj;
  };

  var parseUrlSearch = ths.parseUrlSearch = function (search) {
    var a, b, i, params = {};

    if(search.charAt(0) == '?') {
      search = search.slice(1);
    }
    a = search.split('&');
    for (i = 0; i < a.length; i++) {
      b = a[i].split('=');
      params[b[0]] = b[1];
    }

    return params;
  };

  /*
  var normalizeLocation = ths.normalizeLocation = function (locObj, options) {
    var a, b, i;
    if (options.params) {
      locObj.params = {};
      if(locObj.search.charAt(0) == '?') {
        locObj.search = locObj.search.slice(1);
      }
      a = locObj.search.split('&');
      for (i = 0; i < a.length; i++) {
        b = a[i].split('=');
        locObj.params[b[0]] = b[1];
      }
    }
    return locObj;
  };
  */

  /**
   * Merges two objects. Can be used to clone an object or to merge into an existing object.
   *
   * To merge a sourceObject into a targetObject. (source data will overwrite target data):
   * objectMerge(targetObject, sourceObject)
   *
   * To clone an object:
   * var newObject = objectMerge({}, sourceObject);
   *
   * To merge and create a new object (clone):
   * var newObject = objectMerge(targetObject, sourceObject);
   *
   * @param target
   * @param source
   * @return {*} A cloned object.
   */

  var objectMerge = ths.objectMerge = function (target, source, options) {
    options = options || {};
    if (isNull(options.array)) {
      options.array = 'concat';
    }
    if (typeof target !== 'object') {
      target = {};
    }
    if (typeof source !== 'object') {
      source = {};
    }

    for (var k in source) {
      if (source.hasOwnProperty(k)) {
        var v = source[k];
        if (isStdObject(v)) {
          target[k] = objectMerge(target[k], v);
          continue;
        }
        if (isArray(target[k])) {
          if (options.array == 'concat') {
            if (isArray(v)) {
              target[k] = target[k].concat(v);
            }
            else {
              target[k].push(v);
            }
            continue;
          }
        }
        target[k] = v;
      }
    }

    return target;
  };

  var formatKey = ths.formatKey = function (value) {
    return value.replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(m, i) {
      if (+m === 0) return ""; // or if (/\s+/.test(match)) for white spaces
      return i == 0 ? m.toLowerCase() : m.toUpperCase();
    });

    /*
    value = value.toLowerCase();
    value = value.replace(/[^a-z0-9_]+/g,'_');
    return value;
    */
  };

  ths.round = function round(value, decimals) {
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
  };

  ths.debounce = function(func, wait, immediate) {
    var timeout;
    return function() {
      var context = this, args = arguments;
      var later = function() {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };
      var callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  };

  /**
   *
   * @param {*} message
   * @param {*=} status
   */
  var log = ths.log = function (message, status) {
    ths.logLog.push(message);
    if ((!ths.debug) && (!ths.settings.debug)) {
      return;
    }
    if (typeof console == 'object') {
      console.log(message);//
    }
    if ((typeof _l10im == 'object') && isFunction(_l10im.log)) {
      _l10im.log(message);
    }
  };

  // property declaration


  ths.eventQueue = [];
  ths.realtimeQueue = [];

  var loc = win.location;
  // location properties
  ths.location = ths.parseUrl(loc.href);
  ths.location.params = parseUrlSearch(loc.search);

  ths.settings = {
    pageUri: null,
    pageHost: '',
    pagePath: '',
    pageBasepath: '',
    pageTitle: document.title,
    trackAnalytics: 1,
    eventDefs: [],
    gaGoals: []
  };

  //ths.location.host + ths.location.path = ths.location.host + ths.location.path;
  //ths.settings.debug = true; // flag if debug mode is enabled
  ths.name = '_ioq';
  ths.disabled = 0; // used to prevent tracking on non browsers, e.g. bots
  ths.cookiesDisabled = 0;
  ths.localStorageDisabled = null;
  //ths.ready = false;
  ths.status = {
    session: 0, // session data initialized (stage 1)
    exec: 0,  // pushes will be executed vs held in queue (stage 2)
    send: 0,  // ga sending is enabled (stage 3)
    attrs: 0, // page, session, visitor and ext data is initialized (stage 4)
    dom: 0,  // dom is ready, events can be bind to dom (stage 5)
    stage: 0  // numeric representation of statuses that have been set
  };
  // sets when a function is safe to execute. Set value for stage:
  // 1 = should be preprocessed
  // 2 = after exec status (default)
  // 3 = after send
  // 4 = after attr
  // 5 = after dom
  ths.enableFunc = {
    setAccount: 'session',
    addCallback: 'session',
    setConfig: 'session',
    sessionInit: 'session',
    'ga.create': 'session',
    trackCta: 'dom',
    trackForm: 'dom'
  };
  ths.trackingid = '';  // primary tracking id
  ths.trackerPrefix = ''; // primary tracking prefix/name
  ths.trackingIds = {}; // list of tracking ids indexed by tracker prefixes
  ths.trackerDefs = []; // list of traker field objects used to create trackers
  ths.trackerDefsIndex = {};
  ths.trackers = {}; // list of ga tracker objects indexed by tracker prefixes
  // domain name set in ga for cookie tracking
  ths.cookieDomain = ths.location.hostname;
  ths.vtk = '';
  ths.vtkid = '';
  ths.vtkc = '';
  ths.userId = '';
  ths.sid = 0;
  ths.cookies = {
    s: ''
  };

  /*
  // jquery function holder
  ..ths.$;
  // jquery(window)
  ths.$win;
  // jquery(document)
  ths.$doc
  // jQuery content target
  ths.$content;
  */

  ths._ga = {};
  ths._gaVid = '';
  ths._gaSessionCount = 0;
  ths._gaTime = 0;
  ths._gaTracker = '';
  ths.isPageRefresh = false;
  ths.isEntrance = false;
  ths.isUnique = false;
  ths.isNewVisitor = false;
  ths.pageviewSent = null;
  ths.sessionPageCount = 0;
  ths.timeDelta = 0;
  ths.curGaTs = 0;
  var _init = false;
  var _apiLevel = '';
  ths.apiLevel = '';
  var _dataMode = 0;
  ths.dataMode = 0;
  ths.apiUrl = '';
  ths.pageData = {
    meta: {},
    analytics: {},
  };
  ths.sessionData = {
    analytics: {},
    pageviews: []
  };
  ths.visitorData = {
    analytics: {}
  };
  ths.extData = {};
  ths.callbacks = {};
  ths.errorVtkid = '023c23ba00000766a002';
  ths.utmsr = screen.width + 'x' + screen.height;
  ths.utmvp = (window.innerWidth || document.body.clientWidth) + 'x' + (window.innerHeight || document.body.clientHeight);
  ths.ssSource = '';
  ths.followups = {};
  // this array designates page and object attributes to be included in the id fields. Not all resource id object fields.
  // since things like rkv (version) and rlg (language) can change, the included only items that perminantly define
  // the resources. Not attributes like versions and language or path aliases
  ths.attrUriKeys = [
    'rs', // resource source
    'rc', // resource class
    'rt', // resource type
    'rt2', // resource sub type
    'ri', // resource identifier (url or urn format)
    'rl', // resource locator (url format)
    'rn', // resource name (urn format)
    'rk', // resource primary key value (numeric id or name)
    //'rkv', // resource revision (version) primary key value
    'rh', // resource host. Used when rl is relative so that rh+rl should form an absolute url to access the resource
    'scp' // scope of event hit (p:page, s:session, v:visitor)
    //'rlg', // resource language
  ];
  ths.storage = {
    page: {
      analytics: {
        pda:{
          struc:"dimension",
          index:10,
          format:"timeago"
        },
        ent:{
          struc:"dimension", // ent=u, ent=n, ent=s
          index:12,
          format:"single"
        }
        /*
         url:{
         struc:"dimension",
         index:6,
         format:"single"
         }
         */
      }
    },
    session: {
      analytics: {}
    },
    visitor: {
      analytics: {}
    }
  };
  for (var i = 0; i < ths.attrUriKeys.length; i++) {
    ths.storage.page.analytics[ths.attrUriKeys[i]] = {
      struc:"dimension",
      index:6
    };
  }
  ths.customVarScope = {
    'dimension1': 'page',
    'dimension2': 'session',
    'dimension3': 'visitor',
    'dimension4': 'hit',
    'dimension5': 'visitor',
    'dimension6': 'page',
    'dimension7': 'object',
    'dimension8': 'object',
    'dimension9': 'object',
    'dimension10': 'page',
    'dimension11': 'page',
    'dimension12': 'object',
    'dimension13': 'object',
    'dimension14': 'hit',
    'dimension15': 'hit'
    //'dimension16': 'visitor',
    //'dimension17': 'hit',
    //'dimension18': 'hit'
  };
  // sets any custom definitions to be sent with pageview
  ths.pageviewDef = {};
  ths.gaSend = {
    location: '',
    title: '',
    referrer: ''
  };
  ths.gaSendCustomVars = {};
  //ths.gaPushHistory = {};
  ths.eventDefs = [];
  ths.eventDefsIndex = {};
  ths.goalDefs = [];
  ths.goalDefsIndex = {};
  ths.triggerEventHistory = [];
  ths.plugins = {};
  ths.schemas = {};
  ths.logLog = [];
  ths.pushLog = [];
  ths.gaPushLog = {};

  ths.hasSchema = function(name) {
    return isStdObject(ths.schemas[name]) ? 1 : 0;
  };

  ths.setSchema = function (name, schema) {
    var k, prop, i, aProp, ak;
    if (!isStdObject(schema)) {
      schema = {};
    }
    schema.id = name;

    if (isStdObject(schema.props)) {
      schema.propsArr = [];
      schema.required = [];
      for (k in schema.props) {
        prop = schema.props[k];
        if (isString(prop)) {
          prop = {
            type: prop
          };
        }
        prop.id = k;
        if (isArray(prop.aliases)) {
          for (i = 0; i < prop.aliases.length; i++) {
            ak = prop.aliases[i];
            schema.props[ak] = prop;
          }
        }
        if (prop.required) {
          schema.required.push(k);
        }
        schema.props[k] = prop;
        schema.propsArr.push(prop);
      }
    }

    ths.schemas[name] = schema;
    if (isArray(schema.aliases)) {
      for (i = 0; i < schema.aliases.length; i++) {
        ths.schemas[schema.aliases[i]] = schema;
      }
      delete schema.aliases;
    }
  };

  ths.getSchema = function (name, defaultSchema) {
    return ths.schemas[name] || defaultSchema;
  };

  ths.is = function (type, value) {
    var def, i, propKey, j, found, aPropKey;
    if (!isStdObject(ths.schemas[type])) {
      return undefined;
    }
    def = ths.schemas[type];
    if (def.isMethod) {
      return def.isMethod(value);
    }
    if (isFunction(ths['is' + def.id])) {
      return ths['is' + def.id](value);
    }
    if (isStdObject(value) && isArray(def.required)) {
      for (i = 0; i < def.required.length; i++) {
        propKey = def.required[i];
        if (isNull(value[propKey])) {
          if (!isArray(def[propKey].aliases)) {
            return false;
          }
          found = 0;
          for (j = 0; j < def[propKey].aliases.length; j++) {
            aPropKey = def[propKey].aliases[j];
            if (!isNull(value[aPropKey])) {
              found = 1;
              break;
            }
          }
          if (!found) {
            return false;
          }
        }
      }
      return true;
    }
    /*
     if (value._schema == type) {
     return true;
     }
     */
    return false;
  };

  ths.new = function (type, params) {
    var i, j, p, pa, obj = {}; //args = [].slice.call(arguments, 1);
    var def = ths.schemas[type];
    if (!isStdObject(def)) {
      return undefined;
    }
    obj._schema = type;
    if (isStdObject(params) && isArray(def.propsArr)) {
      for (i = 0; i < def.propsArr.length; i++) {
        p = def.propsArr[i];
        if (!isNull(params[p.id])) {
          obj[p.id] = params[p.id];
        }
        else if (isArray(p.aliases)) {
          for (j = 0; j < p.aliases.length; j++) {
            pa = p.aliases[j];
            if (!isNull(params[pa])) {
              obj[p.id] = params[pa];
              break;
            }
          }
        }
      }
    }

    return obj;
  };

  // set core schema defs
  ths.setSchema('String', {
    aliases: ['Text']
  });
  ths.setSchema('Number');
  ths.setSchema('Url');
  ths.setSchema('Thing', {
    props: {
      id: 'String', // machine name
      name: 'String', // human name
      desc: {
        type: 'String',
        aliases: ['description']
      },
      altName: 'String',
      url: 'Url',
      img: 'Url'
    }
  });

  ths.setUserId = function setUserId(data) {
    if (_dataMode > 0 && isObject(data) && data.userId) {
      ths.userId = data.userId.substr(0, 20);
      ths.setVtk(data.userId.substr(0, 32));
      ths.push(['ga.set', 'userId', ths.userId]);
    }
  };

  ths.setVtk = function setVtk(vtk) {
    if (_dataMode > 0) {
      ths.vtk = vtk;
      // split vtk into check and id
      ths.vtkid = ths.vtk.substr(0, 20);
      ths.vtkc = ths.vtk.substr(20);
      ths._attachVtk();
    }
  };

  /**
   * Sets the VTK the first time it is received. Stores both in l10ivtk and GA customVar/cookie
   */
  ths._attachVtk = function _attachVtk() {
    if (_dataMode == 0) {
      return;
    }
    var c = ths.vtk;
    if (ths.userId) {
      c += '.1';
    }
    ths.setCookie('l10ivtk', c, 730);

    //ths.gaqPush(['_setCustomVar', 5, 'vtk', String(ths.vtkid), 1]);
    ths.push(['ga.set', 'dimension5', String(ths.vtkid)]);
    //ths.push(['ga.set', '&uid', String(ths.vtkid)]);
    //call[3].userId = String(ths.vtkid);

  };

  /**
   * Gets the Vtk set in GA cookie
   * @return
   */
  ths._getGAVtk = function _getGAVtk() {
    var a, b;
    a = ths._iuGC(document.cookie, '5=vtk=', ';');
    if (a !== '-') {
      b = a.split('=');
      return b[1];
    }
    return false;
  };

  ths.setStatus = function(name, state) {
    ths.log(ths.name + '.setStatus(' + name + ', ' + state + ')');
    ths.status[name] = state;
    ths.status.stage++;
    ths.triggerCallbacks('status', name, state);
    ths.triggerCallbacks('status.' + name, state);
    if (state) {
      ths.triggerCallbacks(name + 'Ready', window._ioq);
    }
  };

  ths.getStatus = function(name) {
    return ths.status[name];
  };

  /**
   * creates a callback that is called when L10iQueue is finished initializing
   * @param callback function to be called
   * @param thisArg object to use as "this"
   * @param args args to be passed to callback function
   */
  ths.ready = function (callback, thisArg) {
    ths.onReady(callback, thisArg);
  };

  ths.onReady = function (callback, thisArg) {
    ths.addCallback('execReady', callback, thisArg);
  };

  ths.cookieCheck = function () {
    // check if test cookie is not found
    // TODO: change to more rigerous check. Wanted to disable for now as it requires
    // new embed code that implements l10i_bt cookie test
    //if(!document.cookie || (document.cookie.indexOf('l10i_bt=') == -1)) {
    //if(!document.cookie) {
    //  ths.cookiesDisabled = 1;
    //}
  };

  ths.botCheck = function botCheck() {
    // check user agent for common bot descriptors
    if (/bot|googlebot|crawler|spider|robot|crawling/i.test(navigator.userAgent)) {
      ths.disabled = 1;
    }
    // common observed bot has sr=20x20 & vp=1024x768;
    if ((ths.utmsr == '20x20') && (ths.utmvp == '1024x768')) {
      ths.disabled = 1;
    }
  };

  /**
   * Returns true if L10iQueue is finished initializing
   */
  // TODO: update for universal analytics
  ths.setAccount = function (account, trackerPrefix) {
    if (trackerPrefix == undefined) {
      if (ths.trackerPrefix != '') {
        trackerPrefix = ths.trackerPrefix;
      }
      else {
        trackerPrefix = 'l10i';
      }
    }
    ths.trackingIds[trackerPrefix] = account;
    // set trackerPrefix if not already done to store index of primary tracking id
    if (ths.trackerPrefix == '') {
      ths.trackerPrefix = trackerPrefix;
      ths.trackingid = account;
    }
    //ths.gaqPush([trackerPrefix + '._setAccount', account]);
  };

  ths.constTrackerDef = function (tid, cookieDomain, name, fields) {
    var def = fields || {};

    if (isObject(name)) {
      def = name;
    }
    else {
      def.name = name;
    }
    if (isObject(cookieDomain)) {
      def = cookieDomain;
    }
    else {
      def.cookieDomain = cookieDomain;
    }
    def.tid = tid;
    if (!def.name) {
      def.name = '';
      def.prefix = '';
    }
    else {
      def.prefix = def.name + '.';
    }
    def.key = def.name || '_';
    def.enhance = def.enhance || 'all';

    return def;
  }

  ths.addTracker = function (tid, cookieDomain, name, fields) {
    var def = ths.constTrackerDef(tid, cookieDomain, name, fields);

    ths.trackerDefsIndex[def.key] = ths.trackerDefs.length;
    ths.trackerDefs.push(def);
    ths.gaPushLog[def.key] = [];

    // set trackerPrefix if not already done to store index of primary tracking id
    if (def.enhance == 'all' && ths.trackerPrefix == '') {
      ths.trackerPrefix = def.name;
      ths.trackingid = tid;
      if (def.cookieDomain && def.cookieDomain != 'auto') {
        ths.cookieDomain = def.cookieDomain;
      }
    }

    return def;
    //ths.gaqPush([trackerPrefix + '._setAccount', account]);
  };

  /*
   ths.setDomainName = function setDomainName(domainName) {
   ths.domainName = domainName;
   ths.gaqPush(['_setDomainName', domainName]);
   };
   */

  // ? deprecated
  ths.setTrackerPrefix = function setTrackerPrefix(trackerPrefix) {
    ths.trackerPrefix = trackerPrefix;
  };

  // ? Deprecated
  ths.setDebug = ths.setDebug = function setDebug(flag) {
    ths.debug = (flag) ? true : false;
  };

  ths.isDebug = function isDebug(flag) {
    return ths.debug || ths.settings.debug;
  };

  // ? Deprecated
  ths.setOptions = function setOptions(options) {
    ths.settings = options;
    if (ths.storage != undefined) {
      ths.storage = ths.storage;
    }
  };

  /**
   * Sets config.
   *
   * Used to set initial config rather than set command b/c it runs earlier making settings available to plugins
   *
   * @type {_ioq.setConfig}
   */
  ths.setConfig = ths.setConfig = function (config) {
    if (!isNull(config) && isStdObject(config)) {
      objectMerge(ths.settings, config);
    }
    ths.setStatus('config', 1);
  };

  ths.normalizeConfig = function () {
    var a;
    if (!isNull(ths.settings.storage)) {
      objectMerge(ths.storage, ths.settings.storage);
    }
    if (ths.settings.systemPath) {
      ths.settings.pageUri = ths.settings.systemPath;
      if (ths.settings.cmsHostpath) {
        a = ths.settings.cmsHostpath.split('/');
        ths.settings.pageHost = a.shift();
        ths.settings.pageBasepath = '/' + a.join('/');
      }
    }
  };

  /***********************************************************
   * Cookie management methods
   */

  /**
   * Returns cookie substring based on search terminators
   * @param l cookie string, usually set to document.cookie
   * @param n search start string
   * @param s search end string
   * @return
   */
  ths._iuGC = function(l, n, s) {
    // used to obtain a value form a string of key=value pairs
    if (!l || l == '' || !n || n == '' || !s || s == '') {return '-';}
    var i, i2, i3, c = '-';
    i = l.indexOf(n);
    i3 = n.indexOf('=') + 1;
    if (i > -1) {
      i2 = l.indexOf(s, i); if (i2 < 0) { i2 = l.length; }
      c = l.substring((i + i3), i2);
    }
    return c;
  };

  /**
   * Returns a cookie by cookie name
   * @param c_name name of cookie to returned
   */
  ths.getCookie = function (c_name, defaultReturn) {
    if (isNull(document.cookie)) {
      return '';
    }
    var value = ths._iuGC(document.cookie, c_name + '=', ';');
    if (value != '-') {
      value = decodeURIComponent(value);
      return value;
    }
    if (defaultReturn != undefined) {
      return defaultReturn;
    }
    else {
      return '';
    }
  };

  /**
   * Sets a cookie. Will create a new cookie if the name does not exist or updates exsiting cookies
   * @param c_name cookie name
   * @param value cookie value
   * @param exdays days till cookie should expire.
   */
  ths.setCookie = function (c_name, value, exdays) {
    var exdate, c_value;
    exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    c_value = encodeURIComponent(value) + ((exdays == null) ? '' : '; expires='+ exdate.toUTCString()) + ';path=/;domain=.' + ths.cookieDomain + ';';
    document.cookie = c_name + '=' + c_value;
  };

  /**
   * Deletes a cookie.
   * @param cookie name
   */
  ths.deleteCookie = ths.deleteCookie = function (c_name) {
    c_name += '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/;domain=.' + ths.cookieDomain + ';';
    document.cookie = c_name;
  };

  ths._encodeCookieElements = function(elements) {
    var str = '', i;
    for (i in elements) {
      if (elements.hasOwnProperty(i)) {
        if (!i) {
          continue;
        }
        str += i + '=' + elements[i] + '^';
      }
    }
    str = str.substring(0, str.length - 1);
    return str;
  };

  ths._decodeCookieElements = function(cookie) {
    var a, b, i, elements = {};
    a = cookie.split('^');
    for (i = 0; i < a.length; i++) {
      // split on equals
      b = a[i].split('=');
      // try on hex value for =
      if (b.length != 2) {
        b = a[i].split('%3D');
      }

      if ((b[0] != '') && (b[1] != undefined)) {
        elements[b[0]] = b[1];
      }

    }
    return elements;
  };

  /***********************************************************
   * Local storage management methods
   */

  ths.setLocalItem = function (key, value) {

    // if local storage availabity not checked, do check
    if (ths.localStorageDisabled == null) {
      ths.localStorageDisabled = ths._checkLocalStorage();
    }
    // check if local storage is not available
    if (!ths.localStorageDisabled) {
      return false;
    }
    try {
      window['localStorage'].setItem(key, value);
    } catch (e) {
      log('Local storage setItem error.');
      return false;
    }
    return true;
  };

  ths.getLocalItem = function(key, defaultValue) {

    // if local storage availabity not checked, do check
    if (ths.localStorageDisabled == null) {
      ths.localStorageDisabled = ths._checkLocalStorage();
    }
    // check if local storage is not available
    if (!ths.localStorageDisabled) {
      return null;
    }
    var value = window['localStorage'].getItem(key);
    if ((defaultValue != undefined) && (value === null) || (value === undefined)) {
      return defaultValue;
    }
    return value;
  };

  ths.removeLocalItem = function (key) {
    // if local storage availabity not checked, do check
    if (ths.localStorageDisabled == null) {
      ths.localStorageDisabled = ths._checkLocalStorage();
    }
    // check if local storage is not available
    if (!ths.localStorageDisabled) {
      return false;
    }
    try {
      window['localStorage'].removeItem(key);
    } catch (e) {
      return false;
    }
    return true;
  };

  ths._checkLocalStorage = function() {
    try {
      return 'localStorage' in window && window['localStorage'] !== null;
    } catch (e) {
      return false;
    }
  };


  /***************************************************************
   * JSON call handeling methods
   */

  /**
   * Sends JSON requests to server
   * @param func specifies the function endpoint to call
   * @param params GET paramaters to be sent to server
   * @param data POST data to be sent to the server
   * @param callback response callback. Callbacks may be string function names or a standard function
   */
  ths.getJSON = function (func, params, data, options) {
    var vars, url, script, jqxhr, jqVer;

    // check if property has access to server data
    if ((_dataMode == 0) || ths.disabled) {
      // process callbacks before returning
      ths.triggerCallbacks('send', vars, func, params, data, options);
      ths.triggerOptionCallbacks(options, 'ajaxSend', true, vars, func, params, data, options);
      if (options.failCallback) {
        options.failCallback.call(this, {}, 'unauthorized');
      }
      if (options.alwaysCallback) {
        options.alwaysCallback.call(this, {}, 'unauthorized');
      }
      return;
    }

    if (!ths.apiUrl) {
      return false;
    }

    if (isNull(options)) {
      options = {};
    }
    if (!isStdObject(options)) {
      options = {
        callback: options
      };
    }

    if (options.callback == undefined) {
      options.callback = 'getJSONCallback';
    }
    //callback = (function(data) { win._ioq.initVisitor(data); });
    ths.log(ths.name + '::getJSON (' + func + ')');
    // use jQuery if it exists
    if (typeof jQuery !== 'undefined') {
      vars = {
        dataType: 'jsonp',
        url: ths._getJSONUrl(func, params),
        data: data
      };
      if (typeof options.callback == 'string') {
        vars.success = function (data) {
          window._ioq.push([options.callback, data]);
        }
      }
      else {
        vars.success = options.callback;
      }

      jqxhr = jQuery.ajax(vars);
      if (options.doneCallback) {
        jqxhr.done(options.doneCallback);
      }
      if (options.failCallback) {
        jqxhr.fail(options.failCallback);
      }
      if (options.alwaysCallback) {
        jqxhr.always(options.alwaysCallback);
      }
      // use fallback if jQuery does not exist
    } else {
      url = ths._getJSONUrl(func, params, data);
      url += '&callback=' + callback;
      script = document.createElement('script');
      script.src = url;
      document.getElementsByTagName('head')[0].appendChild(script);
      ths.log(script.src);
    }
    // trigger callbacks
    ths.triggerCallbacks('send', vars, func, params, data, options);
    ths.triggerOptionCallbacks(options, 'ajaxSend', true, vars, func, params, data, options);

    return true;
  };

  /**
   * Constructs the JSON url
   * @param func
   * @param params
   * @param data
   * @return
   */
  ths._getJSONUrl = function (func, params, data) {
    var url;
    params.vtk = ths.vtk;
    params.tid = ths.trackingIds[ths.trackerPrefix];
    params.t = ths.getTime();
    params.sid = ths.sid;
    //params.h = window.location.hostname + (window.location.port ? ':' + window.location.port: '');
    //params.p = window.location.pathname + window.location.search;
    params.h = ths.location.host;
    params.p = ths.location.path;
    params.cs = ths.cookies.s;
    params.f = ths.getCookie('l10i_f');
    params.d = ths.domainName;

    var time = new Date().getTime();
    params.bt = Math.round(time / 1000);
    //var time = new Date();
    //params.bt2 = Math.round(time.getTime()/1000);
    //var time = Date.now();
    //params.bt3 = Math.round(time/1000);
    params.sr = screen.width + 'x' + screen.height;
    if (window.innerWidth != undefined && window.innerHeight != undefined) {
      params.vp = window.innerWidth + 'x' + window.innerHeight;
    }
    else if (document != undefined && document.body != undefined && document.body.clientWidth != undefined && document.body.clientHeight != undefined) {
      params.vp = document.body.clientWidth + 'x' + document.body.clientHeight;
    }

    params.ua = (navigator.userAgent != undefined) ? navigator.userAgent : '';

    url = ('https:' == ths.location.protocol) ? 'https:' : 'http:';
    // if func starts with //, treat as custom url request
    if (func.indexOf('//') == 0) {
      url += func + '?';
    }
    else if (ths.apiVer == 2) {
      url += '//' + ths.apiUrl + '/' + func + '?';
    }
    else {
      url += '//' + ths.apiUrl + 'index.php?q=' + func + '&';
    }
    url += ths._encodeUrlQueryParams(params);
    if (data != undefined) {
      url += '&data=' + encodeURIComponent(JSON.stringify(data));
    }
    return url;
  };



  /**
   * Encodes parameters array as url query elements
   * @param params
   * @return
   */
  ths._encodeUrlQueryParams = function (params) {
    var str = [], k;
    for (k in params) {
      if (params.hasOwnProperty(k)) {
        str.push(encodeURIComponent(k) + '=' + encodeURIComponent(params[k]));
      }
    }
    return str.join('&');
  };

  /**
   * Default JSON callback. Executed if no callback is passed to getJSON method
   * @param data data returned from server
   */
  ths.getJSONCallback = function (data) {
    ths.log(ths.name + '.getJSONCallback');
    ths.log(data);
  };

  ths.getTime = function getTime() {
    var time = new Date().getTime();
    return Math.round(time / 1000) + ths.timeDelta;
  };

  ths.getTimeDelta = function getTimeDelta() {
    return (window.performance) ? performance.now() / 1000 : (ths.getTime() - ths.initTime);
  };


  /***************************************************************
   * Push execution methods
   */

  /**
   * Used by external scripts to execute method calls.
   * Method waits to make sure vtk is set before making method calls.
   *
   * @param call array in the format [method name, arg1, arg2, argN]
   * @return the return of the called function
   */
  ths.push = function push(call) {
    // clone call and log push
    ths.pushLog.push(call.slice());

    if (ths.status.exec) {
      ths.logPush(call);
      return ths._processCall(call);
    }
    else if (call[0] == 'sessionInit') {
      ths._sessionInit(call[1]);
    }
    ths.logPush(call, 'queued');
    ths.queue.push(call);
    return null;
  };

  ths.logPush = function logPush(call, extra) {
    if (call[0] != 'log') {
      /*
       var c = '', i;
       for (i = 0; i < call.length; i++) {
       c += c ? ', ' : '';
       if (call[i] == undefined) {
       c += 'undefined';
       }
       else if (typeof call[i] == 'function') {
       c += 'function()';
       }
       else if ((typeof call[i] == 'object') && (call[i].constructor !== undefined)) {
       c += call[i].constructor.name;
       }
       else {
       c += call[i];
       }
       }
       */
      ths.log(ths.name + '.push(' + call[0] + ')' + (extra ? ' [' + extra + ']'  : ''));
      ths.log(call);
    }
  };

  ths._processPushCookie = function () {
    var p = ths.getCookie('l10i_push');
  };

  /**
   * Executes push calls. Checks to see if method exists in L10iQ, otherwise pushes to _gaq
   * @param call array in the format [methodName, arg1, arg2, argN]
   */
  ths._processCall = function _processCall(call) {
    var func, plugin, call2;

    // log push
    //ths.pushLog.push(call);

    // check if call references a function only
    if (typeof call === 'function') {
      return call();
    }
    else if (!isArray(call)) {
      return;
    }
    // check if jQuery event
    if (!isNull(call[0].data) && !isNull(call[0].data.ioCmd)) {
      if (isArray(call[0].data.ioCmd)) {
        call = call[0].data.ioCmd;
      }
      else {
        call.unshift(call[0].data.ioCmd);
      }
    }

    // check if ga function
    if (call[0].substr(0, 3) == 'ga.') {
      return ths.gaqPush(call);
    }

    // check if first arg of call references a function
    if (call[0] !== undefined) {
      func = call.shift();
      if (func.substr(0, 1) == '_') {
        func = func.substring(1);
      }

      plugin = func.split(':');
      if (plugin.length == 2) {
        // check if plugin exists
        if (!isNull(_ioq.plugins[plugin[0]])) {
          if (isFunction(_ioq.plugins[plugin[0]][plugin[1]])) {
            // ga plugins maintain the plugin object as "this", so we are doing the same thing.
            return ths.plugins[plugin[0]][plugin[1]].apply(ths.plugins[plugin[0]], call);
          }
        }
        else {
          // if plugin doesn't exist yet, queue commands for latter
          if (!ths.pluginQueues[plugin[0]]) {
            ths.pluginQueues[plugin[0]] = [];
          }
          call.unshift(func);
          ths.pluginQueues[plugin[0]].push(call);
        }

      }
      else if (isFunction(_ioq[func])) {
        return ths[func].apply(this, call);
      }
    }
  };

  /**
   * Prepares and executes _gaq push calls.
   * @param call array formated for _gaq call
   */
  ths.gaqPush = function gaqPush(call) {
    var i, td, key, prefix;
    // check if ga object exists
    if (isNull(ths.ga) || !ths.settings.trackAnalytics) {
      return false;
    }

    // this must be done here as some functions call gaqPush directly
    if (call[0].substr(0, 3) == 'ga.') {
      // strip off ga.
      call[0] = call[0].substr(3);
    }
    // set this here so ts is set before send
    if (call[0] == 'send') {
      ths._setGATimestamp();
      if (call[1] == 'pageview') {
        ths.pageviewSent = ths.curGaTs;
      }
    }

    // permissions processing
    // Free can't push any customVars
    // Basic can only push page attributes (slot 1) and session attributes (slot 2)
    // Pro can push all
    if (0 && call[0] == 'set') {
      if (!_apiLevel || (_apiLevel == 'free')) {
        return;
      }
      else if (_apiLevel != 'pro') {
        if (call[1] != 1 && call[1] != 2) {
          return;
        }
      }
    }
    // if create call, add tracker to trackingIds
    //if (call[0] == 'create') {
    //  if (ths.vtkid) {
    //    //call[3].userId = String(ths.vtkid);
    //  }
    //}


    // if prefix is on method, push as is
    var callMethod = call[0].split('.');
    if (call[0] == 'create' || callMethod.length == 2) {

      if (call[0] == 'create') {
        // get tracker def to determine prefix
        td = ths.constTrackerDef.apply(this, call.slice(1));
        key = td.key;
      }
      else {
        key = callMethod[0];
      }

      if (key == '_') {
        call[0] = callMethod[1];
      }

      ths.gaPushLog[key].push(call);

      // TODO: research if this is the best way to handle this
      if (typeof ths.ga.apply == 'function') {
        var ret = ths.ga.apply(this, call);
      }
      else {
        var ret = ths.ga.push(call);
      }
    }
    // if no prefix is given, push using all trackers
    else {
      //for (var prefix in ths.trackingIds) {
      for (i = 0; i < ths.trackerDefs.length; i++) {
        td = ths.trackerDefs[i];

        var callMod = call.slice(0); // clone array

        if (td.enhance == 'base') {
          callMod = ths._filterBaseGaqPush(callMod);
          if (!callMod) {
            continue;
          }
        }

        callMod[0] = td.prefix + call[0];

        ths.gaPushLog[td.key].push(call);
        if (typeof ths.ga.apply == 'function') {
          var ret = ths.ga.apply(this, callMod);
        }
        else {
          var ret = ths.ga.push(callMod);
        }
      }
    }

    return ret; // disable realtime tracking for now

    //if (ths.settings.trackRealtime == 1) {
    //  call[0] = callMethod;
    //  ths._realtimePush(call);
    //}
    //return ret;
  };

  ths._filterBaseGaqPush = function (call) {

    if (call[0] == 'set') {
      return 0;
    }
    else if (call[0] == 'send') {
      var hitType = isString(call[1]) ? call[1] : '';
      if (isObject(call[1]) && call[1].hitType) {
        hitType = call[1].hitType;
      }
      if (hitType == 'event') {
        if(isObject(call[1])) {
          call[1] = ths.filterGaEvent(call[1], 'base')
        }
        return call;
      }
      // don't send pageviews
      else {
        return 0;
      }
    }
  };

  ths._realtimePush = function _realtimePush(call) {
    if (ths.sid > 0) {
      ths._processRealtimeQueue();
      return ths._processRealtimeCall(call);
    }
    ths.realtimeQueue.push(call);
    return null;
  };

  ths._processRealtimeQueue = function _processRealtimeQueue() {
    for (var i = 0; i < ths.realtimeQueue.length; i++) {
      ths._processRealtimeCall(ths.realtimeQueue[i]);
    }
  };

  ths._processRealtimeCall = function _processRealtimeCall(call) {
    if (ths.settings.trackAnalytics != 1) {
      return;
    }
    var func, params = {}, data = {};
    var type = call[0];
    //if (ths.trackerPrefix) {
    //  type = type.replace(ths.trackerPrefix + '.', '');
    //}
    func = '//' + ths.settings.cmsHostpath + ths.settings.libPath + '/realtime/index.php';
    //if (type == '_trackPageview') {
    if (type == 'send' && call[1] == 'pageview') {
      params.q = 'track/pageview';
      params.pa = ths.serializeCustomVar(ths.pageData.analytics);
      params.va = ths.serializeCustomVar(ths.visitorData.analytics);
      params.ie = (ths.entrance) ? 1 : 0;
      params.iu = (ths.unique) ? 1 : 0;
      params.dt = encodeURI(document.title);
      params.r = encodeURI(document.referrer);

      var time = new Date().getTime();
      params.bt = Math.round(time / 1000);
      params.sc = screen.colorDepth;
      params.sr = screen.width + 'x' + screen.height;
      params.vp = '' + (window.innerWidth || document.body.clientWidth) + 'x' + (window.innerHeight || document.body.clientHeight);
      params.ua = (navigator.userAgent != undefined) ? navigator.userAgent : '';
      params.ul = (navigator.language != undefined) ? navigator.language : '';
      params.chs = (document.characterSet != undefined) ? document.characterSet : '';

      var keys = {
        source: 'sr',
        medium: 'md',
        term: 'tr',
        content: 'ct',
        campaign: 'cn',
        gclid: 'gclid'
      };
      if ((ths.entrance == 1) && (typeof ths.sessionData.trafficSource == 'object')) {
        params.ts = ths.serializeCustomVar(ths.sessionData.trafficSource);
      }
      // if an entrance, attach existing visitor attributes so deltas can be determined
      if (ths.entrance == 1) {
        params.va0 = ths.serializeCustomVar(ths.visitorData.analytics0);
      }

      /*
       if (ths.visitorData.addthis != undefined) {
       if (ths.visitorData.addths.geo != undefined) {
       if (ths.visitorData.addths.geo.lat != undefined) {
       params.lat = ths.visitorData.addths.geo.lat;
       params.lon = ths.visitorData.addths.geo.lon;
       }
       }
       if (ths.visitorData.addths.services != undefined) {
       params.ss = ths.serializeCustomVar(visitorData.addths.services);
       }
       }
       */

      ths.getJSON(func, params, data);
    }
    //else if (type == '_trackEvent') {
    else if (type == 'send' && call[1] == 'event') {
      params.q = 'track/event';
      params.ec = call[1];
      params.ea = call[2];
      params.el = call[3];
      params.ev = call[4];
      params.ei = (call[5]) ? 1 : 0;
      ths.getJSON(func, params, data);
    }
    else if (type == '_initSession') {
      params.q = 'track/session';
      params.utmz = encodeURI(ths.getCookie('__utmz'));
      ths.getJSON(func, params, data);
    }
  };

  ths._processRealtimeVar = function _processRealtimeVar(params, data) {
    var func = '//' + ths.settings.cmsHostpath + ths.settings.libPath + '/realtime/index.php';
    params.q = 'track/var';
    ths.getJSON(func, params, data);
  };

  /**
   * Pushes current timestamp to GA
   * @param theTime
   * @return
   */
  ths._setGATimestamp = function _setGATimestamp(theTime) {
    var pre;

    theTime = theTime || ths.getTime();
    //if (theTime == undefined) {
    //  theTime = ths.getTime();
    //}
    if (theTime != ths.curGaTs) {
      //ths.gaqPush(['_setCustomVar', 4, 'ts', String(theTime), 3]);
      //ths.gaqPush(['ga.set', 'metric2', theTime]);
      if (_dataMode != 0) {
        ths.gaqPush(['ga.set', 'dimension4', String(theTime)]);
        ths.gaqPush(['ga.set', 'metric4', theTime]);
      }
      ths.curGaTs = theTime;
    }
  };

  ths._processCookieQueue = function () {
    var i, cq = ths.getCookie('l10i_q');
    if (cq) {
      cq = JSON.parse(cq);
      if (isArray(cq)) {
        for (i = 0; i < cq.length; i++) {
          ths.push(cq[i]);
        }
      }
    }
    ths.deleteCookie('l10i_q');
  };

  /**
   * Processes all items in the queue
   * Called after object is initialized
   */
  ths._processQueue = function () {
    var saved = [], i, stage;
    for (i = 0; i < ths.queue.length; i++) {
      stage = ths.enableFunc[ths.queue[i][0]]||'exec';
      if (ths.status[stage]) {
        ths.logPush(ths.queue[i]);
        ths._processCall(ths.queue[i]);
      }
      else {
        saved.push(ths.queue[i]);
      }

    }
    ths.queue = saved;
  };

  /**
   * Used to set essential data in queue when initializing object.
   *
   *
   */
  ths._preprocessQueue = function () {
    var saved = [], i, c;

    for (i = 0; i < ths.queue.length; i++) {
      c = ths.queue[i];
      if (c[0] == 'sessionInit') {
        ths._sessionInit(c[1]);
      }
      // some io settings are determined by the ga.create. We need to copy these settings, but should requeue
      // ga.create to run latter (so userId can be set). There maybe a better way to do this.
      else if (c[0] == 'ga.create') {

        ths.addTracker.apply(this, c.slice(1));
        // alter call to be formated as just a field object reutnred by addTracker
        saved.push(c);
        //ths._processCall(ths.queue[i]);
      }
      else if (ths.enableFunc[c[0]] == 1) {
        ths._processCall(c);
      }
      else {
        saved.push(c);
      }
    }
    ths.queue = saved;
  };


  /************************************************
   * Callback management
   */

  ths.constrCallbackObj = function(def, obj, options) {
    if (isStdObject(def)) {
      return def;
    }
    def = {
      'callback': def
    };
    if (!isNull(obj)) {
      def.obj = obj;
    }
    if (isStdObject(options)) {
      if (!isNull(options.once)) {
        def.once = options.once;
      }
      if (!isNull(options.name)) {
        def.name = options.name;
      }
    }
    return def;
  };

  ths.constrCallbackArr = function(callbacks, obj, options) {
    if (!isArray(callbacks)) {
      callbacks = [callbacks];
    }
    for (i = 0; i < callbacks.length; i++) {
      if (!isStdObject(callbacks[i])) {
        callbacks[i] = ths.constrCallbackObj(callbacks[i], obj, options);
      }
    }
    return callbacks;
  };

  ths.addCallback = function (hook, callback, obj, options) {
    var cbDef, status = '';

    var callbacks = ths.getCallbacks(hook);

    cbDef = ths.constrCallbackObj(callback, obj, options);

    // [status]Ready callbacks should only be executed once
    if (hook.substr(hook.length - 5, 5) == 'Ready') {
      status = hook.substr(0, hook.length - 5);
      cbDef.once = true;
    }

    callbacks.push(cbDef);

    // if status is true, then immediately run status callbacks
    if (ths.status[status]) {
      ths.triggerCallbacks(hook, window._ioq);
    }
    return callbacks;
  };

  ths.removeCallback = function(hook, callback) {
    var callbacks, i, cb, isObj, c=0;
    if (arguments.length < 2) {
      return false;
    }
    callbacks = ths.getCallbacks(hook);

    isObj = isStdObject(callback);

    // remove specific handler
    for (i = 0; i < callbacks.length; i++) {
      cb = callbacks[i];
      if ((isObj && cb === callback) || (!isObj && (cb.callback === callback || cb.name === callback))) {
        callbacks.splice(i, 1);
        i--;
        c++;
      }
    }
    return callbacks;
  };

  ths.getCallbacks = function(hook) {
    var callbacks;
    if (isArray(hook)) {
      callbacks = hook;
    }
    else if (isString(hook)) {
      callbacks = ths.callbacks[hook] = ths.callbacks[hook] || [];
      //callbacks = ths.callbacks[hook];
    }
    if (isNull(callbacks)) {
      callbacks = [];
    }
    return callbacks;
  };

  ths.triggerCallbacks = function(hook) {
    var callbacks, cb, i, args = [].slice.call(arguments, 1);
    args.unshift(this);
    args.unshift(null);

    callbacks = ths.getCallbacks(hook);

    for (i = 0; i < callbacks.length; i++) {
      if (callbacks.hasOwnProperty(i)) {
        cb = callbacks[i];
        if (isNull(cb.callback)) {
          continue;
        }
        args[1] = cb.obj || this;
        args[0] = cb.callback;

        ths._processCallback.apply(args[1], args);
        // if callback is to be executed only once, remove the item
        if (cb.once) {
          callbacks.splice(i, 1);
          i--;
        }
      }
    }

    return callbacks;
  };

  ths.constrOptionCallbacks = function(def, hooks) {
    var i, hook;
    if (!isStdObject(def) || !isArray(hooks)) {
      return def;
    }
    for (i=0; i < hooks.length; i++) {
      hook = hooks[i] + 'Callback';
      if (!isNull(def[hook])) {
        def[hook] = ths.constrCallbackArr(def[hook]);
      }
    }
    return def;
  };

  ths.triggerOptionCallbacks = function(def, hook, construct) {
    var args = [].slice.call(arguments, 2);
    hook += 'Callback';
    args[0] = def[hook];
    if (isNull(args[0])) {
      return [];
    }
    if (construct) {
      args[0] = ths.constrCallbackArr(args[0]);
    }
    def[hook] = ths.triggerCallbacks.apply(this, args);
    return def[hook];
  };

  /**
   *
   * @param callback
   * @param obj
   * @private
   */
  ths._processCallback = function(callback, obj) {
    var a, f = callback, args = [].slice.call(arguments, 2);
    if (obj === undefined) {
      obj = this;
    }
    if (isString(f)) {
      // check if call to plugin function
      a = f.split(':');
      if (a.length == 2) {
        f = ths.plugins[a[0]][a[1]];
      }
      else {
        f= ths[callback];
      }
    }

    if (isFunction(f)) {
      return f.apply(obj, args);
    }
    else {
      ths.log('Callback function ' + callback + ' not found!');
    }
  };

  ths.setDef = function (arr, def, indexes, options) {
    var index = arr.length, key = def.key || '';
    if (key && isStdObject(indexes)) {
      if (!isNull(indexes[key])) {
        index = indexes[key];
      }
      else {
        indexes[key] = index;
      }
    }
    arr[index] = def;
  };

  ths.getDef = function (arr, key, indexes, options) {
    if (isStdObject(indexes) && !isNull(indexes[key])) {
      return arr[indexes[key]];
    }
    return;
  };

  /************************************************
   * Schema management
   */



  /************************************************
   * Data/variable management methods
   */

  /**
   * Retrieves a value from a property.
   *
   * @param {string} prop
   * @param {*} defaultValue
   * @return {*}
   */
  ths.get = function(prop, defaultValue) {
    // if no prop given, return all vars
    if (isNull(prop)) {
      return {
        config: ths.settings,
        page: ths.pageData,
        session: ths.sessionData,
        visitor: ths.visitorData,
        ext: ths.extData
      }
    }
    var propId = ths.getPropId(prop, defaultValue);

    var data, i;
    data = ths[propId.prop];

    //if (propId.namespace) {
    //  data = data[propId.namespace];
    //}
    if (data === undefined) {
      return defaultValue;
    }
    if (propId.keys.length == 0) {
      return data;
    }

    for (i = 0; i < propId.keys.length; i++) {
      if (data[propId.keys[i]] === undefined) {
        return defaultValue;
      }
      data = data[propId.keys[i]];
    }
    if (data === undefined) {
      return defaultValue;
    }
    return data;
  };

  ths.set = function(prop, value, arg2, arg3, arg4) {
    var ret = ths._setWalk(prop, value, arg2, arg3, arg4);
    ths.processFollowups();

    if (isStdObject(arg2)) {
      ths.triggerOptionCallbacks(arg2, 'set', true, prop, value, arg2);
    }

    return ret;
  };

  /**
   * Walks set prop argument to handle passing multiple settings as array or object
   * @param prop
   * @param value
   * @param arg2
   * @param arg3
   * @param arg4
   * @return {*}
   * @private
   */
  ths._setWalk = function(prop, value, arg2, arg3, arg4) {
    if (isStdObject(prop)) {
      for (var p in prop) {
        if (prop.hasOwnProperty(p)) {
          var v = prop[p];
          ths._setWalk(p, v, arg2, arg3, arg4);
        }
      }
    }
    else if (isArray(prop)) {
      for (var i; i < prop.length; i++) {
        var v, p = prop[i];
        ths._setWalk(p, v, arg2, arg3, arg4);
      }
    }
    // single property, go ahead and set the property key
    else if (typeof prop == 'string') {
      return ths._setProp(prop, value, arg2, arg3, arg4);
    }
  };

  ths._setProp = function(prop, value, arg2, arg3, arg4) {
    var propId = ths.getPropId(prop, value);

    // if prop undetermined or type is prop (props are readable but not settable)
    if (!propId.prop || propId.type == 'prop') {
      return null;
    }

    if (propId.type == 'func') {
      return ths[propId.prop](value, arg2, arg3, arg4);
    }
    else if (propId.type == 'prop') {
      // set is on a direct property of the OEI object. Pass obj instance to force pass by reference
      return ths.mergeVar(ths, propId.namespace, [propId.prop], value, arg2);
    }
    // process var
    if (propId.length == 0 && isNull(arg2)) {
      arg2 = 'merge';
    }
    var value = ths.mergeVar(ths[propId.prop], propId.namespace, propId.keys, value, arg2);

    var gaScopes = {
      'session': 1,
      'visitor': 1
    };

    // if session or visitor scope was updated, set flag to write to local
    if (gaScopes[propId.scope]) {
      ths.followups['saveLocal.' + propId.scope] = 1;
    }

    gaScopes['page'] = 1;

    // if analytics namespace was updated, set flag to write to ga attributes
    if (gaScopes[propId.scope] && (propId.namespace == 'analytics')) {
      ths.followups['syncGaCustomVar.' + propId.scope] = 1;
    }

    // if prop is set, normalize the settings
    if (propId.scope == 'config') {
      ths.followups['config.normalize'] = 1;
    }

    // trigger callbacks
    var k, obj = ths[propId.prop], name = 'set.' + propId.scope, ks = propId.keys.join('.');
    ths.triggerCallbacks(name, obj, ks, value);
    if (propId.namespace) {
      k = propId.namespace
      ks = ks.substr(k + 1);
      name += '.' + k;
      obj = obj[k];
      ths.triggerCallbacks(name, obj, ks, value);
      /*
       if (propId.namespace == 'analytics' && propId.keys.length > 1) {
       k = propId.keys[1];
       ks = ks.substr(k.length + 1);
       name += '.' + k;
       obj = obj[k];
       ths.triggerCallbacks(name, obj, ks, value);
       }
       */
    }

    return value;
  };

  ths.getPropId = function(propStr) {
    var propId = {
      prop: '',
      type: 'prop',
      scope: '',
      namespace: '',
      keys: propStr.split('.'),
      meta: {}
    };
    var a = propId.keys[0].split(':');
    if (a.length == 2) {
      propId.prop = a[0];
      propId.keys[0] = propId.namespace = a[1]
    }
    else {
      propId.prop = propId.keys.shift();
    }
    switch (propId.prop) {
      case 'pa':
      case 'sa':
      case 'va':
        propId.prop = propId.prop.substr(0, 1);
        propId.namespace = 'analytics';
        propId.keys.unshift(propId.namespace);
        break;
    }
    switch (propId.prop) {
      case 'p':
        propId.prop = 'page';
        break;
      case 's':
        propId.prop = 'session';
        break;
      case 'v':
        propId.prop = 'visitor';
        break;
      case 'e':
        propId.prop = 'ext';
        break;
      case 'c':
        propId.prop = 'config';
        break;
    }


    var scopes = {
      'config': 'settings',
      'page' : 'pageData',
      'session' : 'sessionData',
      'visitor': 'visitorData',
      'ext': 'extData'
      //'var': 'var',
      //'cookie': 'cookie',
      //'local': 'local'
    };
    if (scopes[propId.prop]) {
      // use first element of keys to define scope, remove first key
      propId.type = 'var';
      propId.scope = propId.prop;
      propId.prop = scopes[propId.scope];
      a = ths[propId.prop][propId.keys[0]];
      if (!isNull(a) && !isNull(a._updated)) {
        propId.namespace = propId.keys[0];
      }
    }
    else if (ths[propId.prop] !== undefined) {
      if (typeof ths[propId.prop] == 'function') {
        propId.type = 'func';
      }
    }
    return propId;
  };

  /**
   * Returns a variable
   * @param scope select the data scope. vals = visitor, session, scope
   * @param namespace (optional) selects the namespace of the data, e.g. analytics
   * @param keys dot deliminated string of keys for accessing a specific index in the data
   * return value of variable
   */
    // ? deprecated
  ths.getVar = function (scope, namespace, keys) {
    var data, k, i;
    data = ths[scope + 'Data'];

    if (namespace != undefined) {
      data = data[namespace];
    }
    if ((data != undefined) && (keys != undefined)) {
      k = keys.split('.');
    }
    else {
      return data;
    }
    for (i = 0; i < k.length; i++) {
      if (data[k[i]] == undefined) {
        return undefined;
      }
      data = data[k[i]];
    }
    return data;
  };

  /**
   * Sets a variable
   * @param scope select the data scope. vals = visitor, session, scope
   * @param namespace (optional) selects the namespace of the data, e.g. analytics
   * @param keys dot deliminated string of keys for accessing a specific index in the data
   * return value of variable
   */
    // ? deprecated
  ths.setVar = function (scope, namespace, keys, value, saveToLocal) {
    ths.mergeVar(ths[scope + 'Data'], namespace, keys.split('.'), value);
    // save to local by default
    if (saveToLocal || saveToLocal == undefined) {
      ths.saveVarToLocal(scope);
    }
  };

  /**
   * Deletes a variable
   * @param scope select the data scope. vals = visitor, session, scope
   * @param namespace (optional) selects the namespace of the data, e.g. analytics
   * @param keys dot deliminated string of keys for accessing a specific index in the data
   * return value of variable
   */
  ths.deleteVar = function deleteVar(scope, namespace, keys) {
    ths[scope + 'Data'] = ths.mergeVar(ths[scope + 'Data'], namespace, keys, '', 'delete');
  };

  /**
   * Saves variable to server. Can save an array of values or a single value
   * @param scope select the data scope. vals = visitor, session, scope
   * @param namespace (optional) selects the namespace of the data, e.g. analytics
   * @param keys dot deliminated string of keys for accessing a specific index in the data
   * return value of variable
   */
  ths.saveVar = function(scope, namespace, keys) {
    var json_params = {}, json_data = {}, end_point;
    ths.log('saving ' + scope);
    ths.log(ths[scope + 'Data']);
    json_data.value = ths[scope + 'Data'];
    end_point = 'vars/save';
    json_params.type = scope;
    if (namespace != undefined) {
      end_point = 'var/merge';
      json_data.value = json_data.value[namespace];
      json_params.namespace = namespace;
    }
    if (keys != undefined) {
      json_params.keys = keys;
    }
    ths.getJSON(end_point, json_params, json_data);
    if (ths.settings.track_realtime == 1) {
      ths._processRealtimeVar(json_params, json_data);
    }
  };

  /**
   * Saves page attributes.
   * @param namespace (optional) selects the namespace of the data, e.g. analytics
   * @param keys dot deliminated string of keys for accessing a specific index in the data
   */
    // ? deprecated
  ths.setPageAttr = function(namespace, keys, value) {
    ths.pageData = ths.mergeVar(ths.pageData, namespace, keys, value);
  };

  /**
   * Saves visitor attributes.
   * @param namespace (optional) selects the namespace of the data, e.g. analytics
   * @param keys dot deliminated string of keys for accessing a specific index in the data
   */
    // ? deprecated
  ths.setVisitorAttr = function(namespace, keys, value) {
    ths.visitorData = ths.mergeVar(ths.visitorData, namespace, keys, value);
  };

  /**
   * Merges a value into a specific index in a data array
   * @param scope select the data scope. vals = visitor, session, scope
   * @param namespace (optional) selects the namespace of the data, e.g. analytics
   * @param keys dot deliminated string of keys for accessing a specific index in the data
   * @param action if set to 'delete', variable index is unset
   * return value of variable
   */
  ths.mergeVar = function(theObj, namespace, keys, value, options) {
    var k, value0, value1, obj;
//console.log(theObj);
//console.log('namespace=' + namespace + ', keys=' + keys + ', value=' + value);
//console.log(value);
//console.log(options);
    // if no namespace and no keys, just set the object to the value.
    if (!namespace && keys.length == 0) {
      theObj = objectMerge(theObj, value);
      return value;
    }

    // if options arg is a string, assume it is an action
    if (isString(options)) {
      options = {
        action: options
      };
    }
    else if (!isStdObject(options)) {
      options = {};
    }

    if (value == null) {
      options.action = 'delete';
    }
    k = keys;

    if (namespace) {
      // add the namespace to the front of the keys
      if (theObj[namespace] == undefined) {
        theObj[namespace] = {};
      }
      theObj[namespace]._updated = ths.getTime();
      // save any metadata set on options
      for (var i in options) {
        if (options.hasOwnProperty(i) && i.substr(0, 1) == '_') {
          theObj[namespace][i] = options[i];
          if (i == '_last') {
            theObj[namespace]['_count'] = theObj[namespace]['_count'] || 0;
            theObj[namespace]['_count']++;
          }
        }
      }
    }
    obj = theObj;


    // delete index if action==delete
    if (options.action == 'delete') {
      if (k.length == 1) {
        delete theObj[k[0]];
      }
      else if (k.length == 2) {
        delete theObj[k[0]][k[1]];
      }
      else if (k.length == 3) {
        delete theObj[k[0]][k[1]][k[2]];
      }
      else if (k.length == 4) {
        delete theObj[k[0]][k[1]][k[2]][k[3]];
      }
      return theObj;
    }

    // inizialize index if does not exist
    if ((k.length > 0) && (obj[k[0]] == undefined)) {
      obj[k[0]] = (k.length > 1) ? {} : 0;
    }
    if ((k.length > 1) && (obj[k[0]][k[1]] == undefined)) {
      obj[k[0]][k[1]] = (k.length > 2) ? {} : 0;
    }
    if ((k.length > 2) && (obj[k[0]][k[1]][k[2]] == undefined)) {
      obj[k[0]][k[1]][k[2]] = (k.length > 3) ? {} : 0;
    }
    if ((k.length > 3) && (obj[k[0]][k[1]][k[2]][k[3]] == undefined)) {
      obj[k[0]][k[1]][k[2]][k[3]] = (k.length > 4) ? {} : 0;
    }
    value0 = value1 = 0;
    if (k.length == 0) {
      value0 = value1 = obj;
    }
    else if (k.length == 1) {
      value0 = value1 = obj[k[0]];
    }
    else if (k.length == 2) {
      value0 = value1 = obj[k[0]][k[1]];
    }
    else if (k.length == 3) {
      value0 = value1 = obj[k[0]][k[1]][k[2]];
    }
    else if (k.length == 4) {
      value0 = value1 = obj[k[0]][k[1]][k[2]][k[3]];
    }

    if (isString(value) && (value.substring(0, 1) == '=')) {
      if (value.substring(1, 2) == '+') {
        if (value.substr(-1) == '%') {
          value1 = Number(value0) * (1 + (Number(value.substring(2, value.length - 1)) / 100));
        }
        else {
          value1 = Number(value0) + Number(value.substring(2));
        }
        // round to deal with Javascript floating point precision issues
        value1 = Math.round(value1 * 100) / 100;
      }
      else if (value.substring(1, 2) == '-') {
        if (value.substr(-1) == '%') {
          value1 = Number(value0) * (1 - (Number(value.substring(2, value.length - 1)) / 100));
        }
        else {
          value1 = Number(value0) - Number(value.substring(2));
        }

        value1 = Math.round(value1 * 100) / 100;
      }
      else if (value.substring(1, 2) == '*') {
        value1 = Number(value0) * Number(value.substring(2));
        value1 = Math.round(value1 * 100) / 100;
      }
      else if (value.substring(1, 2) == '/') {
        value1 = Number(value0) / Number(value.substring(2));
        value1 = Math.round(value1 * 100) / 100;
      }
      else {
        value1 = Number(value);
      }
    }
    else {
      if (options.action == 'merge' || (namespace && keys.length == 1)) {
        if (isStdObject(value0) && isStdObject(value)) {
          value1 = objectMerge(value0, value);
        }
        else if (isArray(value0)) {
          if (isArray(value)) {
            value1 = value0.concat(value);
          }
          else {
            value1 = [].concat(value0).push(value);
          }
        }
        else {
          value1 = value;
        }
      }
      else {
        value1 = value;
      }
    }

    if (k.length == 0) {
      theObj = value1;
    }
    else if (k.length == 1) {
      theObj[k[0]] = value1;
    }
    else if (k.length == 2) {
      theObj[k[0]][k[1]] = value1;
    }
    else if (k.length == 3) {
      theObj[k[0]][k[1]][k[2]] = value1;
    }
    else if (k.length == 4) {
      theObj[k[0]][k[1]][k[2]][k[3]] = value1;
    }

    return value1;
    //return theObj;
  };



  /**
   * Extracts a variable from a cookie
   * @param c_name cookie name
   * @param keys dot deliminated string of keys for accessing a specific index in the data
   * return value of variable
   */
  ths.getVarFromCookie = function getVarFromCookie(c_name, keys) {
    var e = ths._decodeCookieElements(ths.getCookie(c_name));
    //win._ioq.log(c_name);win._ioq.log(keys);win._ioq.log(e);
    return e[keys];
  };

  /**
   * Pushes page attributes and visitor attributes vars to GA
   * @param name custom variable name. Set to 'pa' or 'va'
   */
  ths.syncGaCustomVar = function(scope) {
    var data, datasend = {}, dataformat = {}, defStruc, defIndex, struc, index, format, key, k, i, scope, c, storeDef;
    defStruc = 'dimension';
    if (scope == 'page' || scope == 'pa') {
      scope = 'page';
      data = ths.pageData.analytics;
      defIndex = 1;
    }
    else if (scope == 'session' || scope == 'sa') {
      scope = 'session';
      data = ths.sessionData.analytics;
      defIndex = 2;
    }
    else if (scope == 'visitor' || scope == 'va') {
      scope = 'visitor';
      data = ths.visitorData.analytics;
      defIndex = 3;
    }

    if (!isStdObject(data)) {
      return;
    }

    for (key in data) {
      if (!data.hasOwnProperty(key) || key.substr(0, 1) == '_') {
        continue;
      }
      storeDef = ths._getGaStorageDef(scope, key);

      k = String(storeDef.struc + storeDef.index);
      if (datasend[k] == undefined) {
        datasend[k] = {};
        dataformat[k] = {};
      }
      if (storeDef.struc == 'metric') {
        datasend[k][key] = Number(data[key]);
      }
      else {
        datasend[k][key] = data[key];
      }

      dataformat[k] = storeDef.format;
      dataformat[k] = storeDef.format;

      // if attribute is publish time, add age metric
      if (key == 'pd' && (ths.storage[scope].analytics['pda'] != undefined)) {
        var pd = data['pd'];
        pd = new Date(pd.substr(0, 4), Number(pd.substr(4, 2)) - 1, pd.substr(6, 2), pd.substr(8, 2), pd.substr(10, 2));

        var d, h = 0, m = 0, t = (ths.getTime() - pd.getTime() / 1000) / 60;
        d = Math.floor(t / 1440);
        // if age is less than a week, use hour increments
        if (d < 7) {
          h = Math.floor((t % 1440) / 60);
          // if age is less than a day, use 5 minute increments
          if (d < 1) {
            m = 5 * Math.round((t % 60) / 5);
          }
        }
        var t = '' + d + ((h < 10) ? '0' : '') + h + ((m < 10) ? '0' : '') + m;
        while (t.length < 8) {
          t = '0' + t;
        }

        var k2 = ths.storage[scope].analytics['pda']['struc'] + ths.storage[scope].analytics['pda']['index'];
        datasend[k2] = datasend[k2] || {};

        datasend[k2].pda = t;

        dataformat[k2] = 'serialized';
      }
    }

    c = '&';
    for (k in datasend) {
      data = ths.serializeCustomVar(datasend[k], dataformat[k]);
      // cookie string should use standard encoding for all vars
      // chop off leading & to avoid double &&
      if (k.substr(0, 1) == 'd') {
        if (dataformat[k] != 'serialized') {
          c += ths.serializeCustomVar(datasend[k], 'serialized').substr(1);
        }
        else {
          c += data.substr(1);
        }
      }

      ths.push(['ga.set', k, data]);

    }
    if (scope == 'session') {
      ths.setCookie('l10i_sa', c, 1);
      ths.setLocalItem('l10i_sa', c);
    }
    else if (scope == 'visitor') {
      ths.setCookie('l10i_va', c, 730);
      ths.setLocalItem('l10i_va', c);
    }

  };

  ths._getGaStorageDef = function(scope, key) {
    var def = {
      struc: 'dimension',
      index: 1,
      format: 'serialized'
    }
    if (isNull(ths.storage[scope].analytics[key])) {
      if (scope == 'session') {
        def.index = 2;
      }
      else if (scope == 'visitor') {
        def.index = 3;
      }
      return def;
    }
    def = ths.storage[scope].analytics[key];
    if (isNull(def.format)) {
      if (def.struc == 'metric') {
        def.format = 'single_number';
      }
      else {
        def.format = 'serialized';
      }
    }
    else {
      if (def.format == 'single') {
        def.format = 'single_string';
      }
    }

    return def;
  };

  ths.serializeCustomVar = function(obj, mode) {
    var str = ENCODE_VAR_SEP, i, j, k, l, s, t;
    // object properties must be sorted to make consistant string
    s = [];
    for (k in obj) {
      if (obj.hasOwnProperty(k) && k.substring(0, 1) != '_') {
        s.push(k);
      }
    }
    s.sort();
    for (i = 0; i < s.length; i++) {
      k = s[i];
      if (obj[k] !== '') {
        if (isStdObject(obj[k])) {

          t = [];
          for (l in obj[k]) {
            t.push(l);
          }
          t.sort();
          for (j = 0; j < t.length; j++) {
            l = t[j];
            if (obj[k][l] !== '') {
              if (mode == 'single_list') {
                str += l + ENCODE_VAR_EQ + obj[k][l] + ENCODE_VAR_SEP;
              }
              else {
                str += k + '.' + l + ENCODE_VAR_EQ + obj[k][l] + ENCODE_VAR_SEP;
              }
            }
            else {
              if (mode == 'single_list') {
                str += l + ENCODE_VAR_SEP;
              }
              else {
                str += k + '.' + l + ENCODE_VAR_SEP;
              }
            }
          }
        }
        else {
          if (mode == 'single_number') {
            str = Number(obj[k]);
          }
          else if (mode == 'single_string') {
            str = String(obj[k]);
          }
          else {
            str += k + ENCODE_VAR_EQ + obj[k] + ENCODE_VAR_SEP;
          }
        }
      }
      else {
        str += k + ENCODE_VAR_SEP;
      }
    }

    //win._ioq.log("Serialized var " + str);
    //win._ioq.log(obj);
    return str;
  };

  ths.unserializeCustomVar = function(str, mode) {
    str = decodeURIComponent(str);
    var obj = {}, a, b, i, k, storeDef;
    a = str.split(ENCODE_VAR_SEP);
    for (i in a) {
      if (a.hasOwnProperty(i)) {
        b = a[i].split(ENCODE_VAR_EQ);
        if (b[0] == '') {
          continue;
        }
        k = b[0].split('.');
        if ((k.length > 1) && (obj[k[0]] == undefined)) {
          obj[k[0]] = {};
        }
        if (b.length == 2) {
          if (isNumeric(b[1])) {
            b[1] = Number(b[1])
          }
          if (k.length > 1) {
            obj[k[0]][k[1]] = b[1];
          }
          else {
            obj[k[0]] = b[1];
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
    //win._ioq.log("Unserialized var " + str);
    //win._ioq.log(obj);
    return obj;
  };

  ths.saveVarToLocal = function(scope) {
    if ((scope != 'session') && (scope != 'visitor')) {
      return;
    }
    var key = 'l10i_' + scope + 'Data';

    // clone data object to remove any elements we don't want to save
    var value = objectMerge({}, ths[scope + 'Data']);
    // analytics namespace is stored seperately
    if (value['analytics'] != undefined) {
      delete value['analytics'];
    }

    if (value['analytics0'] != undefined) {
      delete value['analytics0'];
    }

    var value = JSON.stringify(value);
    ths.setLocalItem(key, value);
  };

  ths.loadVarFromLocal = function(scope) {
    if ((scope != 'session') && (scope != 'visitor')) {
      return;
    }
    var key = 'l10i_' + scope + 'Data';
    var value = ths.getLocalItem(key, '{}');
    value = JSON.parse(value);
    ths[scope + 'Data'] = objectMerge(value, ths[scope + 'Data']);
  };

  ths.processFollowups = function() {
    for (var k in ths.followups) {
      if (ths.followups.hasOwnProperty(k)) {
        var a = k.split('.');
        if (a[0] == 'syncGaCustomVar') {
          ths.syncGaCustomVar(a[1]);
        }
        if (a[0] == 'saveLocal') {
          ths.saveVarToLocal(a[1]);
        }
        if (k == 'config.normalize') {
          ths.normalizeConfig();
        }
        delete ths.followups[k];
      }
    }
  };

  /**
   * Used to set a flag
   * @param scope
   * @param keys
   * @param value
   * @param saveToCookie true, flag will be saved to cookie
   */
  ths.setFlag = function(scope, keys, value, saveToCookie) {
    var data, cookie, e, str;
    data = ths.getVar(scope);
    ths.mergeVar(data, 'flag', keys.split('.'), value);
    if (saveToCookie) {
      cookie = ths.getCookie('l10i_f');
      e = ths._decodeCookieElements(cookie);
      e[keys] = value;
      str = ths._encodeCookieElements(e);
      if (scope == 'visitor') {
        ths.setCookie('l10i_fv', str, 730);
      }
      else {
        ths.setCookie('l10i_f', str, 1);
      }
    }
  };

  /**
   * Retrieves a flag
   */
  ths.getFlag = function getFlag(scope, keys) {
    var value = ths.getVar([scope, 'flag', keys]);
    if (value == undefined) {
      value = ths.getVarFromCookie('l10i_f', keys);
    }
    return value;
  };

  /***************************************
   * Event handling
   *
   */

  ths.jQuerySelect = function(def, find) {
    var $obj;

    if (def.selector) {
      $obj = jQuery(def.selector);
    }
    else {
      $obj = jQuery(document);
    }
    if (def.selectorFilter) {
      $obj = $obj.filter(def.selectorFilter);
    }
    if (def.selectorNot) {
      $obj = $obj.not(def.selectorNot);
    }
    /*
     if (find) {
     $obj = $obj.find(find);
     }
     */

    return $obj;
  };

  ths.jQueryOn = function(def, $obj, callback) {
    var evtData = {};

    if (isNull(callback) && !isNull(def.onHandler)) {
      callback = def.onHandler;
    }
    if (isNull(callback)) {
      return false;
    }

    if (isStdObject(def.onData)) {
      evtData = def.onData;
    }
    // jQuery 1.7+ uses on function
    if (isFunction($obj.on)) {
      if (def.onSelector) {
        return $obj.on(def.onEvent, def.onSelector, evtData, callback);
      }
      else {
        return $obj.on(def.onEvent, evtData, callback);
      }
    }
    // jQuery 1.4+ uses bind and delegate
    else {
      if (isFunction($obj.bind) && !def.onSelector) {
        return $obj.bind(def.onEvent, evtData, callback);
      }
      else if (isFunction($obj.delegate) && def.onSelector) {
        return $obj.delegate(def.onSelector, def.onEvent, evtData, callback);
      }
    }
    return false;
  };

  // deprecated alias
  var constEventDef = function(evtDef) {
    return constrEventDef(evtDef);
  };

  var constrEventDef = function(evtDef) {
    var v, endchar;

    if (evtDef.const) {
      return evtDef;
    }

    if (evtDef.eventCategory) {
      evtDef.endchar = endchar = evtDef.eventCategory.charAt(evtDef.eventCategory.length - 1);
      // if mode not set in evtDef, determine the mode
      if (evtDef.mode === undefined) {
        evtDef.mode = '';
        if (endchar == '!') {
          evtDef.mode = 'valued';
        }
        else if (endchar == '+') {
          evtDef.mode = 'goal';
        }
      }

      // parse eventCategory to get eventName and goalName
      if (endchar == '+') {
        v = evtDef.eventCategory.split(':');
        if (v[1]) {
          evtDef.eventName = v[0];
          evtDef.goalName = v[1];
        }
        else {
          evtDef.goalName = v[0];
        }
        evtDef.goalName = evtDef.goalName.slice(0, -1).trim();
      }
      else if (endchar == '!') {
        evtDef.eventName = evtDef.eventCategory.slice(0, -1);
      }
      else {
        evtDef.eventName = evtDef.eventCategory;
      }
    }

    if (!evtDef.eventId && evtDef.eventName) {
      evtDef.eventId = formatKey(evtDef.eventName);
    }

    evtDef.const = 1;

    if (evtDef.key) {
      if (!ths.eventDefsIndex[evtDef.key]) {
        ths.eventDefsIndex[evtDef.key] = ths.eventDefs.length;
        ths.eventDefs.push(evtDef);
      }
    }

    //ths.settings.eventDefs.push(evtDef);

    return evtDef;
  };

  ths.event = function(a, b, c, d, e, f) {
    if (isArray(a)) {
      for (var i = 0; i < a.length; i++) {
        ths.pushEvent(a[i]);
      }
    }
    else {
      //ths.pushEvent(a, b, c, d, e, f);
      ths.pushEvent.apply(this, [].slice.call(arguments));
    }
  };

  ths.pushEvent = function(a, b, c, d, e, f) {
    var evtDef = {}, i, v, props, args = [].slice.call(arguments);
    props = [
      'eventCategory',
      'eventAction',
      'eventLabel',
      'eventValue'
    ];
    for (i = args.length - 1; i >= 0; i--) {
      v = args[i];
      if (isObject(v)) {
        // test if object is an event object
        if (isEvent(v)) {
          evtDef.event = v;
          evtDef.target = v.target || v.srcElement;
        }
        else {
          evtDef = v;
        }
      }
      else {
        evtDef[props[i]] = v;
      }
    }

    if (!evtDef.const) {
      constrEventDef(evtDef);
    }

    evtDef = ths.constrOptionCallbacks(evtDef, ['add', 'bind', 'triggerAlter', 'trigger']);

    ths.triggerEventCallbacks('add', evtDef);

    // if event needs to bind to dom, check dom is ready. Otherwise check if send is ready. If not, queue event.
    if ((evtDef.selector && ths.status.dom) || (!evtDef.selector && ths.status.send)) {
      ths.processEvent(evtDef);
    }
    else {
      ths.logEventPush(evtDef, 'queued');
      ths.eventQueue.push(evtDef);
    }

  };

  ths.logEventPush = function (evtDef, extra) {
    ths.log(ths.name + '.eventPush(' + (evtDef.key ? evtDef.key : '') + ')' + (extra ? ' [' + extra + ']' : ''));
    ths.log(evtDef);
  };

  ths.triggerEventCallbacks = function(key, evtDef) {
    var okey, i, k, cb, args = [].slice.call(arguments, 0);

    cb = key + 'Event';
    if (key == 'triggerAlter') {
      cb = 'triggerEventAlter';
    }

    args[0] = cb;
    win._ioq.triggerCallbacks.apply(this, args);

    okey = ['id', 'eid', 'eventId'];
    for (i = 0; i < okey.length; i++) {
      k = okey[i];
      if (evtDef[k]) {
        args[0] = cb + '.' + evtDef[k];
        win._ioq.triggerCallbacks.apply(this, args);
        if (evtDef.cid) {
          args[0] += '.' + evtDef.cid;
          win._ioq.triggerCallbacks.apply(this, args);
        }
      }
    }

    /*
    if (evtDef.eid) {
      args[0] += '.' + evtDef.eid;
      win._ioq.triggerCallbacks.apply(this, args);
      if (evtDef.cid) {
        args[0] += '.' + evtDef.cid;
        win._ioq.triggerCallbacks.apply(this, args);
      }
    }
    */

    // check for field callbacks included in evtDef
    okey = key + 'Callback';

    if (evtDef[okey] !== undefined) {
      args[0] = evtDef[okey];
      ths.triggerCallbacks.apply(this, args);
    }
  };


  ths.processEventQueue = function() {
//var status = ths.objectMerge({}, ths.status);
//var eventQueue = null; //ths.eventQueue.slice(0);
//console.log('processEventQueue', status, eventQueue);
//ths.log('processEventQueue', status, eventQueue);
    if (!ths.status.send) {
      return;
    }
    var s = [], q = ths.eventQueue;
    for (var i = 0; i < q.length; i++) {
      // if dom has not loaded and evtDef has onEvent, skip
      if (!ths.status.dom && q[i].onEvent) {

        s.push(q[i]);
        continue;
      }
      ths.processEvent(q[i]);
    }
    ths.eventQueue = s;
  };


  ths.processEvent = function(evtDef) {
    var $selector, $obj = {}, event = {};
    ths.logEventPush(evtDef, 'processed');
    // even if there is no onEvent or onEvent = pageview, get the obj determined by the selector so object
    // attributes can be set
    if (!isNull(evtDef.selector)) {
      $obj = ths.jQuerySelect(evtDef);
    }
    else if (!isNull(evtDef.target)) {
      $obj = evtDef.target;
      if (!($obj instanceof jQuery)) {
        $obj = jQuery($obj);
      }
    }

    //else if (isObject(this) && !this.apiVer) {
    //  $obj = jQuery(this);
    //}

    // check if dom event was attached to definition
    if (!isNull(evtDef.event)) {
      event = evtDef.event;
      // event needs to be jQuery event. Check if native event and convert if needed.
      if (event.originalEvent === undefined) {
        event = jQuery.Event(event);
      }
    }

    // trigger bindEvent callbacks if bindTarget $obj exists
    if (is$Object($obj)) {
      ths.triggerEventCallbacks('bind', evtDef, $obj);
    }

    // if evtDef has onEvent, do event bindings
    if (isNull(evtDef.onEvent) || evtDef.onEvent == 'pageview') {
      // if this is a pagerefresh, don't trigger events set to onEvent pageview

      if (evtDef.onEvent == 'pageview' && ths.isPageRefresh && !evtDef.refreshForce) {
        return;
      }

      ths.defEventHandler(evtDef, $obj, event);
    }
    else {
      if (!isFunction(evtDef.onHandler)) {
        ths.jQueryOn(evtDef, $obj, function(event) {
          ths.defEventHandler(evtDef, jQuery(this), event);
        });
      }
      else {
        ths.jQueryOn(evtDef, $obj);
      }
    }
  };

  // deprecated alias
  ths.triggerEvent = function(event) {
    ths.eventHandler(evtDef, $obj, event);
  };

  // determines the args for defEventHandler from the event returned from
  // a standard dom event
  ths.getEventArgsFromEvent = function (event, evtDef, $target, options) {

    evtDef = evtDef || {};
    options = options || {};
    if (isObject(event.data) && isObject(event.data.io)) {
      if (isObject(event.data.io.eventDef)) {
        evtDef = event.data.io.eventDef;
      }
      if (isObject(event.data.io.options)) {
        options = event.data.io.options;
      }
    }

    if (isNull($target)) {
      $target = event.currentTarget || event.target || event.srcElement || null;
      $target = !isNull($target) ? jQuery($target) : jQuery(this);
    }

    return [evtDef, $target, event, options];
  };

  // used for events triggered via jQuery
  ths.eventHandler = function(event, evtDef, $obj, options) {
    ths.getEventArgsFromEvent(event, evtDef, $obj, options);
    ths.defEventHandler(evtDef, $obj, event, options);
  };

  // deprecated alias
  ths.triggerIntelEvent = function(evtDef, $obj, event) {
    ths.defEventHandler(evtDef, $obj, event);
  };

  ths.defEventHandler = function(evtDef, $obj, event, options) {
    var op, gaEvt, i, v, a, domEventType = '';
    // for non binded events, $obj & event will be null. Set to empty object to save from testing if it is an object
    // in downline conditionals.
    $obj = $obj || {};
    event = event || {};
    options = options || {};

    ths.mark0 = 1*new Date();

    if (!evtDef.const) {
      constrEventDef(evtDef);
    }

    // create evtData as not to alter the evtDef to keep it in initial state for subsequent calls
    var evtData = objectMerge({}, evtDef);

    // trigger alter callback
    ths.triggerEventCallbacks('triggerAlter', evtData, $obj, event, options, evtDef);

    if (evtDef.exit) {
      return;
    }

    var eventName = evtData.eventName, goalName = evtData.goalName, mode = evtData.mode, goalId = evtData.goalId, endchar = evtData.endchar, useGoalValue = -1;

    if (isObject(event) && event.type) {
      domEventType = event.type;
    }
    else if (evtData.onEvent) {
      domEventType = evtData.onEvent;
    }

    // if evt def has eventCategory, setup data and push to GA
    if (evtData.eventCategory) {

      var tokenData = {
        '$obj': $obj,
        'event': event
      };

      evtData.domEventType = evtData.domEventType || domEventType;
      ths.mergeObjectCustomVars(evtData, $obj, event);

      // check for data-io-[onEvent|event.type]-value override
      if (domEventType && isFunction($obj.attr)) {
        // don't rebuild if objSettings are already set. E.g. set by calling function.
        a = evtData.objSettings = evtData.objSettings || ths.getObjSettings($obj);

        // prevent event if ignore objSetting is set
        if (a[domEventType + '-ignore'] || a[domEventType + '-untrack']) {
          return;
        }

        // override def value is set on $obj
        v = a[domEventType + '-value'];
        if (v && isNumeric(v)) {
          evtData.eventValue = parseFloat(v);
          useGoalValue = 0;
        }

        // set mode if found on $obj
        if (a[domEventType + '-mode']) {
          mode = a[domEventType + '-mode'];
          if (useGoalValue == -1) {
            useGoalValue = 1;
          }
        }
      }

      // additional event mode logic
      if (!mode && goalId) {
        mode = goalId;
      }
      // if the mode is a number treat it as a goalId
      if (isNumeric(mode)) {
        goalId = parseInt(mode);
        mode = 'goal';
      }

      // do token replacement
      if (evtData.eventCategory) {
        evtData.eventCategory = ths.replaceToken(evtData.eventCategory, tokenData);
      }

      if (!evtData.eventAction) {
        evtData.eventAction = '[[title]]';
      }
      evtData.eventAction = ths.replaceToken(evtData.eventAction, tokenData);

      if (!evtData.eventLabel) {
        evtData.eventLabel = '[[uri]]';
      }
      evtData.eventLabel = ths.replaceToken(evtData.eventLabel, tokenData);

      if (!evtData.hitType) {
        evtData.hitType = 'event';
      }

      gaEvt = ths.filterGaEvent(evtData);

      if (!gaEvt.eventValue) {
        gaEvt.eventValue = 0;
      }

      gaEvt.dimension14 = 'Event: ' + eventName;
      gaEvt.dimension15 = ':ga:event:' + ((evtData.eventId) ? evtData.eventId : eventName);

      if (mode == 'goal') {
        // determine goalName or goalId if one is missing
        if (isArray(ths.settings.gaGoals)) {
          // use goalName to determine goalId
          if (goalName) {
            for (i = 0; i < ths.settings.gaGoals.length; i++) {
              v = ths.settings.gaGoals[i];
              if (v.name == goalName) {
                //v = ths.settings.gaGoals[i].id;
                // if goal from goal name does not match goalId, clear the goal name
                if (goalId && goalId != v.id) {
                  //endchar = goalName = '';
                  goalName = '';
                }
                else {
                  goalId = v;
                  if (useGoalValue == 1 && v.value != undefined) {
                    gaEvt.eventValue = v.value;
                  }
                }
                break;
              }
            }
          }
          if (!goalName && goalId) {
            for (i = 0; i < ths.settings.gaGoals.length; i++) {
              v = ths.settings.gaGoals[i];
              if (v.id == goalId) {
                goalName = v.name;
                if (useGoalValue == 1 && v.value != undefined) {
                  gaEvt.eventValue = v.value;
                }
                break;
              }
            }
          }
        }

        // if goal name has not been appended, do it
        if (endchar != '+' && goalName) {
          gaEvt.eventCategory = eventName + ': ' + goalName + '+';
        }

        // set custom dimensions and metrics
        gaEvt.metric7 = 1; // Goal Events
        gaEvt.metric3 = gaEvt.eventValue; // Goal Events Value
        gaEvt.dimension14 = 'Goal: ' + goalName;
        gaEvt.dimension15 = ':ga:goal:' + goalId;
      }
      else if (mode == 'valued') {
        if (endchar == '+') {
          gaEvt.eventCategory = eventName;
        }
        if (endchar != '!') {
          gaEvt.eventCategory += '!';
        }
        // set custom metrics
        gaEvt.metric6 = 1; // Valued Events
        gaEvt.metric2 = gaEvt.eventValue; // Valued Events Value
      }
      else {
        // if standard event with '!' or a goal, remove it.
        if (endchar == '!' || endchar == '+') {
          gaEvt.eventCategory = eventName;
        }
        if (isNull(gaEvt.nonInteraction)) {
          gaEvt.nonInteraction = true;
        }
      }

      // standard ga event values need to be an integer
      gaEvt.eventValue = Math.round(gaEvt.eventValue);

      if (!options.test) {
        if (mode && evtData.eventValue != 0) {
          //op = (evtData.eventValue > 0) ? '=+' : '=';
          _ioq.push(['set', 'va.sc', '=+' + evtData.eventValue]);
        }

        io('ga.send', gaEvt);

        // process if event triggers sessionStick
        if (!ths.pageData.stick && !gaEvt.nonInteraction) {
          ths.handlePageStick();
        }

        // process if event triggers sessionStick
        if (!ths.sessionData.stick && !gaEvt.nonInteraction) {
          ths.handleSessionStick();
        }
      }


    }


    if (!options.test) {
      ths.triggerEventHistory.push(evtData);
    }

    ths.triggerEventCallbacks('trigger', evtData, $obj, event, options, evtDef, gaEvt);

    return {
      evtData: evtData,
      $target: $obj,
      event: event,
      options: options,
      gaEvent: gaEvt,
      eventDef: evtDef
    };
  };

  /**
   * Inspects attributes and classes on $obj to identify property overrides
   *
   * @param $obj
   * @returns {{}|*}
   */
  ths.getObjSettings = function($obj) {
    var k, kl, dk, dkl, a, v, ret;
    k = 'io-';
    kl = k.length;
    dk = 'data-' + k;
    dkl = dk.length;

    ret = {};

    if (isFunction($obj.attr)) {
      // get all attributes on $obj
      $obj.each(function () {
        jQuery.each(this.attributes, function () {
          if (this.specified) {
            if (this.name == 'class') {
              a = this.value.split(' ');
              for (i = 0; i < a.length; i++) {
                v = a[i];
                if (v.substr(0, kl) == k) {
                  v = v.substr(kl).split('--');
                  ret[v[0]] = v[1] || 1;
                }
              }
            }
            else if (this.name.substr(0, dkl) == dk) {
              ret[this.name.substr(dkl)] = this.value;
            }
          }
        });
      });
    }

    return ret;
  };

  ths.mergeObjectCustomVars = function (def, $obj, event) {

    var dim = {}, dim2 = {}, dim3 = {}, dim4 = {}, sMap = {};
    var i, k, keys, si, sk;

    // merge serialized customVars into object attributes (oa)
    def.oa = def.oa || {};
    if (isStdObject(def.customVars)) {
      if (isString(def.customVars.dimension8)) {
        i = ths.unserializeCustomVar(def.customVars.dimension8);
        if (isStdObject(i)) {
          def.oa = objectMerge(i, def.oa);
        }
      }
      if (isString(def.customVars.dimension9)) {
        i = ths.unserializeCustomVar(def.customVars.dimension9);
        if (isStdObject(i)) {
          def.oa = objectMerge(i, def.oa);
        }
      }
      if (isString(def.customVars.dimension12)) {
        i = ths.unserializeCustomVar(def.customVars.dimension12);
        if (isStdObject(i)) {
          def.oa = objectMerge(i, def.oa);
        }
      }
      if (isString(def.customVars.dimension13)) {
        i = ths.unserializeCustomVar(def.customVars.dimension13);
        if (isStdObject(i)) {
          def.oa = objectMerge(i, def.oa);
        }
      }
    }
    else {
      def.customVars = {};
    }

    // get id/name off of dom object. Check $obj is a dom object (not _ioq)
    if (isFunction($obj.attr)) {
      k = $obj.attr('name');
      if (k != '_ioq') {
        if (k) {
          def.oa.domn = k;
        }
        k = $obj.attr('id');
        if (k) {
          def.oa.domi = k;
        }
        k = $obj.attr('data-io-uri');
        if (k) {
          def.oa.domri = k;
          def.oa.ri = def.oa.ri || k;
        }
      }
    }

    if (def.domEventType) {
      def.oa.domet = def.domEventType;
    }

    if (isNull(def.oa.jqs) && def.selector) {
      def.oa.jqs = def.selector;
    }
    if (isNull(def.oa.jqsf) && def.selectorFilter) {
      def.oa.jqsf = def.selectorFilter;
    }
    if (isNull(def.oa.jqos) && def.onSelector) {
      def.oa.jqos = def.onSelector;
    }

    dim2 = objectMerge({}, def.oa);

    keys = ['jqs', 'jqsf', 'jqos', 'domn', 'domi', 'domri', 'domet'];
    for (i = 0; i < keys.length; i++) {
      k = keys[i];
      if (!isNull(dim2[k])) {
        dim[k] = dim2[k];
        delete dim2[k];
      }
    }
    if (!isEmpty(dim)) {
      def.customVars.dimension7 = ths.serializeCustomVar(dim);
    }

  // seperate secondary ids & attr from primary
    for (k in dim2) {
      si = k.charAt(0);
      if (dim2.hasOwnProperty(k) && !isNaN(si)) {
        // build map of secondary attr/ids keyed by base attr key to speed up seperation of URI keys in next
        // loop
        sk = k.substr(1);
        if (isNull(sMap[sk])) {
          sMap[sk] = [];
        }
        sMap[sk].push(si);
        dim4[k] = dim2[k];
        delete dim2[k];
      }
    }

    // seperate URI keys from other attributes. They get saved to two different dimensions
    // reset and reuse dim
    dim = {};
    for (i = 0; i < ths.attrUriKeys.length; i++) {
      k = ths.attrUriKeys[i];
      if (!isNull(dim2[k])) {
        dim[k] = dim2[k];
        delete dim2[k];
      }
      if (!isNull(sMap[k])) {
        for (si = 0; si < sMap[k].length; si++) {
          sk = sMap[k][si] + k;
          dim3[sk] = dim4[sk];
          delete dim4[sk];
        }
      }
    }

    // temp fix for setting default scope of event to page
    if (!dim['scp']) {
      dim['scp'] = 'p';
    }

    if (!isEmpty(dim)) {
      def.customVars.dimension9 = ths.serializeCustomVar(dim);
    }
    if (!isEmpty(dim2)) {
      def.customVars.dimension8 = ths.serializeCustomVar(dim2);
    }
    if (!isEmpty(dim3)) {
      def.customVars.dimension13 = ths.serializeCustomVar(dim3);
    }
    if (!isEmpty(dim4)) {
      def.customVars.dimension12 = ths.serializeCustomVar(dim4);
    }

    return def.customVars;
  };

  ths.filterGaEvent = function(evtDef0, base) {
    var evtDef = {};
    var i, v, attr, keys;
    var remove = {
      domEventType: 1,
      selector: 1,
      selectorFilter: 1,
      selectorNot: 1,
      onEvent: 1,
      onSelector: 1,
      onData: 1,
      onHandler: 1,
      ioCmd: 1,
      id: 1,
      eventId: 1,
      eid: 1,
      cid: 1,
      const: 1,
      eventName: 1,
      goalName:1,
      endchar: 1,
      addCallback: 1,
      bindCallback: 1,
      triggerCallback: 1,
      triggerAlterCallback: 1,
      customVars: 1,
      oa: 1,
      objSettings: 1,
      event: 1,
      target: 1,
      mode: 1
    };

    if (base) {
      for (i = 1; i <= 20; i++) {
        remove['dimension' + i] = 1;
        remove['metric' + i] = 1;
      }
    }

    for (i in evtDef0) {
      if (evtDef0.hasOwnProperty(i)) {
        if (!remove[i]) {
          evtDef[i] = evtDef0[i];
        }
      }
    }
    if (isStdObject(evtDef0.customVars)) {
      for (i in evtDef0.customVars) {
        if (evtDef0.customVars.hasOwnProperty(i)) {
          evtDef[i] = evtDef0.customVars[i];
          delete evtDef.customVars;
        }
      }
    }

    return evtDef;
  };

  /*
   ths.delayClick = function(evtDef, $obj, event) {
   var _href;
   event.preventDefault();
   _href = $obj.attr('href');
   setTimeout(function() {
   window.location.href = _href;
   }, 200);
   return false;
   };
   */

  // ? deprecated
  /*
   ths.trackIntelEventInnerAnchorClick = function($obj, data, event) {
   var _href;
   ths.trackIntelEvent($obj, data, event);
   event.preventDefault();
   _href = $obj.attr('href');
   setTimeout(function() {
   window.location.href = _href;
   }, 200);
   return false;
   };
   */

  ths.getVarEvent = function(scope, namespace, index) {
    if (isNull(ths[scope]) || isNull(ths[scope][namespace])) {
      return undefined;
    }
    var data = ths[scope][namespace];
    if (isNull(index)) {
      return data;
    }
    if (index === 0) {
      if (data._last) {
        data[data._last].time = data._last;
        return data[data._last];
      }
      else if (data._updated) {
        return data[data._updated];
      }
    }
  };

  ths.mergeVarEventContext = function (def) {
    var i, data = {};
    var vals = {
      'eventAction': ths.gaSend.title,
      'eventLabel': ths.gaSend.location,
      'location': ths.gaSend.location,
      'pageTitle': ths.gaSend.title,
      'pageUri': ths.settings.pageUri,
      'systemPath': ths.settings.systemPath || ''
    };
    for (i in vals) {
      if (def[i]) {
        data[i] = def[i];
      }
      else if (vals[i]) {
        data[i] = vals[i];
      }
    }
    data.customVars = ths.gaSendCustomVars;
    if (isStdObject(def.customVars)) {
      data.customVars = ths.objectMerge(data.customVars, def.customVars);
    }

    return data;
  };

  ths.replaceToken = function(str, data) {
    var a, strs;
    // tokens have format [[key]]. Determine if tokens exists by splitting on ']]'
    strs = str.split(']]');
    if (strs.length == 1) {
      return str;
    }
    for (var i = 0; i < strs.length; i++) {
      a = strs[i].split('[[');
      if (a.length == 2) {
        strs[i] = a[0] + ths.replaceTokenElement(a[1], data);
      }
    }
    return strs.join('');
  };

  ths.replaceTokenElement = function(str, data) {
    var $obj, event, key, val;
    if (!isNull(data['$obj'])) {
      $obj = data['$obj'];
    }
    else if (!isNull(data.obj)) {
      $obj = data['$obj'] = jQuery(data.obj);
    }
    else {
      $obj = jQuery(this);
    }
    if (!isNull(data.event)) {
      event = data.event;
    }

    if (str.substr(0, 5) == 'page.') {
      str =  str.substr(5);
      if (str == 'title') {
        return jQuery(document).attr('title');
      }
    }

    if (str.substr(0, 4) == 'loc.') {
      key =  str.substr(4);
      if (key == 'query') {
        key = 'search';
      }
      return _ioq.loc[key] ? _ioq.loc[key] : '';
    }

    if (str.substr(0, 4) == 'obj.') {
      key =  str.substr(4);
      if (key == 'text') {
        return $obj.text();
      }
      else if (str.substr(0, 5) == 'attr.') {
        key =  str.substr(5);
      }
      val = $obj.attr(attr);
      return val ? val : '';
    }

    if (str.substr(0, 4) == 'var.') {
      key =  str.substr(4);
      return _ioq.get(key, '');
    }

    if (str == 'title') {
      if (is$Object(data['$obj'])) {
        val = $obj.attr('data-io-title');
        if (!val) {
          val = $obj.attr('title');
        }
        if (!val) {
          val = $obj.text().trim();
          if (val.length && val.length > 160) {
            val = val.substr(0, 157) + '...';
          }
        }
      }
      else {
        val = _ioq.settings.pageTitle;
      }
      return val ? val : '';
    }

    if (str == 'uri') {
      if (is$Object(data['$obj'])) {
        val = $obj.attr('data-io-uri');
        if (!val) {
          val = $obj.attr('href');
        }
      }
      else {
        val = ths.settings.pageUri;
      }
      return val ? val : '';
    }


    if (str == 'host') {
      return _ioq.loc.hostname;
    }
    else if (str == 'path') {
      return _ioq.loc.pathname;
    }
    else if (str == 'hostpath') {
      return _ioq.loc.hostname + _ioq.loc.pathname;
    }
    else if (str == 'location') {
      return _ioq.loc.protocol + '//' + _ioq.loc.hostname + _ioq.loc.pathname;
    }
    else if (str == 'locationFull' || str == 'location_full') {
      return _ioq.loc.protocol + '//' + _ioq.loc.hostname + _ioq.loc.pathname + _ioq.loc.search;
    }
    else if (str == 'pageUri') {
      return ths.settings.pageUri;
    }
    else if (str == 'systemPath' || str == 'system_path') {
      if (ths.settings.system_path != undefined) {
        return ths.settings.system_path;
      }
      return '';
    }
    else if (str == 'systemAlias' || str == 'system_alias') {
      return ths.location.pathname.substring(1);
    }
    else if (str == 'systemAliasOrLocation' || str == 'system_alias_or_location') {
      if (ths.settings.systemPath != undefined) {
        return window.location.pathname.substring(1);
      }
      else {
        return window.location.protocol + '//' + window.location.hostname + window.location.pathname;
      }
    }
    else if (str == 'pageTitle' || str == 'page_title') {
      return jQuery(document).attr('title');
    }
    else if (str == 'title') {
      return $obj.attr('title');
    }
    else if (str == 'href') {
      return $obj.attr('href');
    }
    else if (str == 'text') {
      return $obj.text();
    }
    else {
      var v = ths.get(str);
      if (!isNull(v)) {
        return v;
      }
    }
    return str;
  };

  ths._buildAdhocDef = function(data, $obj, event, type, trackClass) {
    var loc, attrPrefix = 'data-' + type, id, name;
    id = $obj.attr('id');
    name = $obj.attr('name');
    /*
     // if object has an id or name, build jQuery selector from those.
     if (!isNull(id)) {
     data.selector = '#' + id;
     }
     else if (!isNull(name)) {
     data.selector = '[name="' + $obj.attr('id') + '"]';
     }
     */

    if ($obj.attr(attrPrefix + '-category') != undefined) {
      data.eventCategory = $obj.attr(attrPrefix + '-category');
    }
    if ($obj.attr(attrPrefix + '-action') != undefined) {
      data.eventAction = $obj.attr(attrPrefix + '-action');
    }
    else if ($obj.attr('title') != undefined) {
      data.eventAction = 'Ad-hoc: ' + $obj.attr('title');
    }
    else {
      data.eventAction = 'Ad-hoc: ' + $obj.text();
      // if no text found in link, look for images with alt attr
      if ((data.eventAction == 'Ad-hoc: ') && ($obj.children('img').length > 0)) {
        data.eventAction = 'Ad-hoc: image: ' + $obj.children('img')[0].attr('alt');
      }
    }
    if (data.eventAction.length > 46) {
      data.eventAction = data.eventAction.substring(0, 43) + '...';
    }
    if ($obj.attr(attrPrefix + '-label') != undefined) {
      data.eventLabel = $obj.attr(attrPrefix + '-label');
    }
    else {
      loc = ths.settings.pageUri;
      if (!isNull(id)) {
        loc += '#' + id;
      }
      else if (!isNull(name)) {
        loc += '#' + name;
      }
      data.eventLabel = loc;
    }
    if ($obj.attr(attrPrefix + '-value') != undefined) {
      data.eventValue = $obj.attr(attrPrefix + '-value');
    }
    else {
      data.eventValue = 0;
    }
    if (trackClass) {
      if ($obj.hasClass(trackClass + '-view')) {
        data.trackView = 1;
      }
    }

    return data;
  };

  ths.addAdhocCtaData = function(data, $obj, event) {
    data = ths._buildAdhocDef(data, $obj, event, 'cta', ths.settings.trackAdhocCtas);
    return data;
  };

  ths.getVisibleTime = function() {
    var pv = ths.pageData.visiblity;
    var v = pv.visibleSum;
    if (pv.state == 'visible') {
      v += ths.getTimeDelta() - pv.changeAt
    }
    return v;
  };

  ths.handleVisibilityChange = function() {
    if (!ths.pageData.visiblity) {
      ths.pageData.visiblity = {visibleSum: 0, hiddenSum: 0, changes: {}};
    }
    var pv = ths.pageData.visiblity,
      td = ths.getTimeDelta(),
      visState = doc.visibilityState;

    if (
      (visState != 'visible' && visState != 'hidden')
      || (visState == pv.state)
    ) {
      return;
    }

    if (visState == 'visible') {
      if (!pv.state) {
        pv.visibleSum = td;
      }
      else {
        pv.hiddenSum += td - pv.changeAt;
      }
    }
    else {
      if (!pv.state) {
        pv.hiddenSum = td;
      }
      else {
        pv.visibleSum += td - pv.changeAt;
      }

    }
    if (pv.state) {
      pv.changes[td] = visState;
    }

    pv.changeAt = td;
    pv.state = visState;
    ths.triggerCallbacks('visibilityChange', pv);
  };

  ths.timeHandled = 0;
  ths.handleTimeInterval = function handleTimeInterval () {
    if (ths.pageData.visiblity.state != 'visible') {
      return;
    }
    var time = ths.getVisibleTime(), i = ths.timeHandled, cb = 'timeInterval';
    var stime = Math.round(time);
    if (i < stime) {
      ths.triggerCallbacks(cb, stime);
      for (i++; i <= stime; i++) {
        ths.triggerCallbacks(cb + '.' + i, stime, i);
      }
      ths.timeHandled = stime;
      //console.log(ths.timeHandled);
    }
  };

  ths.handlePageStick = function handlePageStick() {
    ths.pageData.stick = 1;
    /*
    var evtDef = {
      eventCategory: 'Page stick!',
      eventAction: '[[pageTitle]]',
      eventLabel: '[[pageUri]]',
      eventValue: ths.get('c.scorings.events.pageStick', 0),
    };
    io('event', evtDef);
    */
    ths.triggerCallbacks('pageStick');
  };

  ths.handleSessionStick = function handleSessionStick() {
    ths.set('s.stick', 1); // use set function to assure save

    var s = ths.get('c.scorings.stick', 0);
    if (ths.settings.stickEvents) {
      var evtDef = {
        eventCategory: 'Session stick!',
        eventAction: '[[pageTitle]]',
        eventLabel: '[[pageUri]]',
        eventValue: s,
        nonInteraction: false,
        oa: {
          scp: 's'
        }
      };
      io('event', evtDef);
    }
    else if(s) {
      ths.set('va.sc', '=+' + s);
    }

    ths.triggerCallbacks('sessionStick');
  };

  ths.getPageHeight = function() {
    var max = Math.max(
      doc.body.offsetHeight,
      doc.body.scrollHeight
    );
    if (doc.documentElement) {
      var docElm = doc.documentElement;
      if (docElm.offsetHeight && docElm.scrollHeight) {
        max = Math.max(max, docElm.offsetHeight, docElm.scrollHeight);
      }
    }
    return max;
  };

  ths.getElementDimensions = function ($element) {
    if (!isFunction($element.offset)) {
      return {};
    }
    var offset = $element.offset();
    //var pos = ths.$content.position();
    var d = {};
    d.top = offset.top;
    d.bottom = d.top + $element.outerHeight(true),
    d.height = d.bottom - d.top;
    return d;
  };

  ths.getScrollTop = function() {
    return (jQuery) ? jQuery(win).scrollTop() : win.pageYOffset;
    //return win.pageYOffset;
  };

  ths.setPageDimensions = function() {
    var $content, offset, pos,
      d = ths.pageData.dimensions,
      pageHeight = ths.getPageHeight(),
      winHeight = win.innerHeight;

    // only set dimensions if not initialized or have changed
    if (!d || (d.pageHeight != pageHeight) || (d.winHeight != winHeight)) {
      d = {
        pageHeight: pageHeight,
        winHeight: winHeight,
        contentTop: 0,
        contentBottom: pageHeight,
        contentHeight: pageHeight
      };

      if (ths.$content) {
        var a = ths.getElementDimensions(ths.$content);
        d.contentTop = a.top;
        d.contentBottom = a.bottom;
        d.contentHeight = a.height;
        //offset = ths.$content.offset();
        //pos = ths.$content.position();

        //ths.$content.css('outline', '3px solid #33FF33');
        //d.contentTop = pos.top + offset.top;
        //d.contentTop = offset.top;
        //d.contentBottom = d.contentTop + ths.$content.outerHeight(true);
       // d.contentHeight = d.contentBottom - d.contentTop;
      }

      ths.pageData.dimensions = d;
    }

  };

  ths.handleScroll = ths.debounce(function() {
//console.log('handleScroll');

    ths.setPageDimensions();

    var v, o,
      d = ths.pageData.dimensions,
      top = ths.getScrollTop(),
      bottom = top + d.winHeight,
      bottomPer = 100 * bottom / d.pageHeight,
      contentBottom = bottom - d.contentTop,
      contentBottomPer = 100 * contentBottom / d.contentHeight;
      //scrollPer = 100 * (pos / (d.pageHeight - d.winHeight));

    v = ths.pageData.scroll || {
        pageInit: d.pageHeight,
        bottomInit: bottom,
        bottomInitPer: bottomPer,
        pageMax: d.pageHeight,
        bottomMax: bottom,
        bottomMaxPer: bottomPer,
        contentInit: d.contentHeight,
        contentBottomInit: contentBottom,
        contentBottomInitPer: contentBottomPer,
        contentMax: d.contentHeight,
        contentBottomMax: contentBottom,
        contentBottomMaxPer: bottomPer
        //changes: []
    };

//console.log(d);

    if (v.pageMax < d.pageHeight) {
      v.pageMax = d.pageHeight;
    }
    if (v.bottomMax < bottom) {
      v.bottomMax = bottom;
    }
    if (v.bottomMaxPer < bottomPer) {
      v.bottomMaxPer = bottomPer;
    }
    if (v.contentMax < d.contentHeight) {
      v.contentMax = d.contentHeight;
    }
    if (v.contentBottomMax < contentBottom) {
      v.contentBottomMax = contentBottom;
    }
    if (v.contentBottomMaxPer < contentBottomPer) {
      v.contentBottomMaxPer = contentBottomPer;
    }

    //v.changes.push({bottom: bottom, bottomPer: bottomPer});

    ths.triggerCallbacks('scroll', v);

    ths.pageData.scroll = v;
//console.log(ths.pageData.scroll);
  }, 500);


  /************************************************
   * Object init and bootstrapping
   */


  /**
   * Object Initilization Phase I: session init
   *
   * Sets session init settings (vtk, apiUrl, apiLevel, session cookie) if they are available from various sources.
   * If settings not available, request them from Api via getSessionSettings helpers.
   */
  ths.init = function init() {
    ths.log(ths.name + '.init()');
    if (_init) {
      return;
    }
    _init = true;

    var a,
      ss = win._l10iss || {};

    // check if _l10iss exists to setup initial settings
    if (!isNull(ss.apiUrl)) {
      ths.apiUrl = ss.apiUrl;
    }
    if (!isNull(ss.apiVer)) {
      ths.apiVer = ss.apiVer;
    }
    if (!isNull(ss.apiLevel)) {
      ths.apiLevel = _apiLevel = ss.apiLevel;
      if (_apiLevel == 'pro') {
        ths.dataMode = _dataMode = 1;
      }
    }

    // adds any cookie pushes to queue
    ths._processCookieQueue();
    // exec essential settings before initialization
    ths._preprocessQueue();
    ths.vtk = ths.getCookie('l10ivtk');
    a = ths.vtk.split('.');
    if (a[1]) {
      ths.userId = ths.vtk = a[0];
    }
    ths.cookies.s = ths.getCookie('l10i_s');
    ths._parseL10iS();
    // check if pro version (_dataMode>0) and session data not set in cookies
    // if not, do session init using best supported method
    if ((_dataMode > 0) && (!ths.vtk || !ths.cookies.s || (ths.vtk.substr(0, 20) == ths.errorVtkid))) {
      ths.log(ths.vtk);
      // check if this is bot, don't init session data if it is
      ths.botCheck();
      if (ths.disabled) {
        return;
      }
      // check if cookies work on this browser. If not, no point in doing
      // session init
      ths.cookieCheck();
      if (ths.cookiesDisabled) {
        ths._initObj();
        return;
      }
      // check if server side session settings are available
      // check if essential visitor session settings (cs & vtk) are in _l10iss
      if (!isNull(ss.cs)) {
        // maintain vtk in cookie if it exists
        if (ths.vtk) {
          _l10iss.vtk = ths.vtk;
        }
        ths.ssSource = '_l10iss';
        ths._sessionInit(_l10iss);
        return;
      }
      // check if session init fast feature is enabled
      else if (!isNull(ss.ssfUrl) && !isNull(ss.ptk) && !isNull(win._l10issf)) {
        _l10issf._getSessionSettingsFast(win._l10issf);
        return;
      }
      else if (ss.preventGetSession) {
        return;
      }
      // do session init using api JSON request
      ths._getSessionSettings();
      return;
    }
    else {
      // save free version session cookie if not set
      if (!ths.cookies.s) {
        ths.cookies.s = '1.0.0.0.0.0';  // set page count
        ths._parseL10iS(true);
      }

      ths.setStatus('session', 1);
      ths._initObj();
    }
  };

  /**
   * Parses session cookie and sets relevant properties
   * @param save
   * @private
   */
  ths._parseL10iS = function(save) {;
    if (ths.cookies.s) {
      var a = ths.cookies.s.split('.');
      if (a[0] === '1') {
        ths.timeDelta = parseInt(a[2]);
        _dataMode = parseInt(a[3]);
      }
      if (save) {
        ths.cookies.s = a.join('.');
        ths.setCookie('l10i_s', ths.cookies.s, 1);
      }
    }
  };

  /**
   * Loads session init settings from API
   *
   * @param doChecks
   * @private
   */
  ths._getSessionSettings = function _getSessionSettings(doChecks) {
    if (doChecks && ths.vtk && ths.cookies.s) {
      return;
    }
    ths.getJSON('session/init', {}, {}, function (data) { ths._sessionInit(data); });
  };

  /**
   * Prep logic for session init settings retrieved via AJAX or fast feature loading
   */
  ths._sessionInit = function (data) {
    ths.log(ths.name + '._sessionInit()');
    ths.log(data);
    var a;

    // check if session init has already been completed
    if (ths.status.session) {
      return;
    }
    ths.ssSource = (data.ssSource) ? data.ssSource : 'default';

    if (data.cs == undefined) {
      ths._initObj();
      return;
    }
    ths.cookies.s = data.cs;

    ths._parseL10iS(true);

    // check response error
    if (data.vtk.substr(0, 20) == ths.errorVtkid) {
      ths._setGaUtmaParams();
      ths.vtk = ths._gaVid;
    }
    else {
      ths.vtk = data.vtk;
    }
    ths.setStatus('session', 1);
    ths._initObj();
  };

  /**
   * Object Initilization Phase II: object construction
   *
   * Completes contructing the _ioq object's essential data and anlytics data structures and status
   * - determines; isPageRefresh, isNewVisitor, isEntrance, isUnique, isPageview
   * - inits var analytics namespaces
   * - processes queue
   * - updates visitor score for page hits
   * - Runs processPageview()
   */
  ths._initObj = function () {
    ths.log(ths.name + '.initObj()');
    var a,
      prevTime = 0,
      cvtk,
      v,
      curTime;

    if (_dataMode > 0) {
      a = ths.vtk.split('.');
      if (a[1]) {
        ths.userId = ths.vtk = a[0];
      }
      // split vtk into check and id
      ths.vtkid = ths.vtk.substr(0, 20);
      ths.vtkc = ths.vtk.substr(20);
      // check if GA vtk is set
      cvtk = ths.getCookie('l10ivtk', null);
      if (cvtk !== ths.vtk) {
        ths.isNewVisitor = true;
      }
    }
    // check if page refresh
    ths.initTime = curTime = ths.getTime();
    if ((ths.location.host + ths.location.path) == ths.getCookie('l10i_l')) {
      ths.isPageRefresh = true;
    }
    ths.setCookie('l10i_l', ths.location.host + ths.location.path, 1);

    // pull time of last hit
    prevTime = !ths.isNewVisitor ? parseInt(ths.getCookie('l10i_t', 0)) : 0;
    // if last hit is longer than 30 mins, set isEntrance
    if (prevTime < (curTime - 1800)) {
      ths.isEntrance = true;
      //ths.sessionData.analytics.ent = 's';
      if (_dataMode > 0) {
        ths._attachVtk();
      }
    }
    // if longer than 24 hours, then isUnique
    if (prevTime < (curTime - 86400)) {
      ths.isUnique = true;
      //ths.sessionData.analytics.ent = 'u';
    }
    //if (ths.isNewVisitor && !ths.cookiesDisabled) {
    //  ths.sessionData.analytics.ent = 'n';
    //}
    ths.setCookie('l10i_t', curTime, 30);

    // init visitordata with ga values
    // depricated classic analytics code
    a = ths.getCookie('__utmv');
    if (a) {
      a = a.split('3=va=');
      if (a.length === 2) {
        a = a[1].split('^');
        a = a[0];
        a = ths.unserializeCustomVar(a);
        if (a !== undefined) {
          ths.visitorData.analytics = a;
        }
      }
    }

    // init v.analytics and s.analytics namespaces. The rest of the vars is loaded later to minimize performance
    // impact
    a = null;
    // dont load session variables if new session
    if (ths.isEntrance) {
      ths.deleteCookie('l10i_sa');
      ths.removeLocalItem('l10i_sa');
      ths.removeLocalItem('l10i_sessionData');
    }
    else {
      a = ths.getCookie('l10i_sa', null);
      if (isNull(a)) {
        a = ths.getLocalItem('l10i_sa', null);
      }
    }
    if (a) {
      a = ths.unserializeCustomVar(a);
      ths.sessionData.analytics = a;
    }
    /*
     if (ths.entrance) {
     a = ths._getEntranceAnalytics();
     ths.sessionData.entrancePage = a.entrancePage;
     ths.sessionData.referrer = a.referrer;
     }
     */

    a = null;
    if (!ths.isNewVisitor) {
      a = ths.getCookie('l10i_va', null);
      if (isNull(a)) {
        a = ths.getLocalItem('l10i_va', null);
      }
    }
    if (a) {
      a = ths.unserializeCustomVar(a);
      ths.visitorData.analytics = a;
    }
    /*
     if (ths.isNewVisitor) {
     ths.visitorData.entrancePage = ths.sessionData.entrancePage;
     ths.visitorData.referrer = ths.sessionData.referrer;
     ths.visitorData.analytics.e1l = ths.visitorData.entrancePage.hostname + ths.visitorData.entrancePage.pathname +  ths.visitorData.entrancePage.search.replace('&', '?');
     //ths.visitorData.analytics.e1r = ths.visitorData.referrer.hostname + ths.visitorData.referrer.pathname + ths.visitorData.referrer.search.replace('&', '?');
     ths.visitorData.analytics.e1r = ths.visitorData.referrer.hostname + ths.visitorData.referrer.pathname;
     if (!ths.visitorData.analytics.e1r) {
     ths.visitorData.analytics.e1r = '(direct)';
     }
     }
     */

    // make a copy of the analytics data for comparison
    if (ths.isEntrance) {
      ths.visitorData.analytics0 = objectMerge({}, ths.visitorData.analytics);
    }
    else {
      a = ths.getLocalItem('l10i_va0', null);
      if (a) {
        a = ths.unserializeCustomVar(a);
        ths.visitorData.analytics0 = a;
      }
      else {
        ths.visitorData.analytics0 = {};
      }
    }

    ths._parseGaParams();

    ths.log(ths.name + '.vtk = ' + ths.vtk);
    //ths.ready = true;
    ths.setStatus('exec', 1);
    ths._processPluginDefs();

    // check if any pushes are stored in l10i_push cookie and append to queue
    a = ths.getCookie('l10i_push', null);
    if (a && isString(a)) {
      a = JSON.parse(a);
      if (isArray(a)) {
        for(v = 0; v < a.length; v++) {
          if (isArray(a[v])) {
            ths.push(a[v]);
          }
        }
      }
      // delete l10i_push cookie
      // process as a push, so cookie will only be deleted if push queue is processed
      ths.push(['deleteCookie', 'l10i_push']);

      //ths.deleteCookie('l10i_push');
    }

    // process queue
    ths._processQueue();

    // callback to set ga tracker when ga ready
    if (isFunction(ths.ga)) {
      ths.ga(function (tracker) {
        ths.trackers[ths.trackerPrefix] = ths.ga.getByName(ths.trackerPrefix);
        ths.setStatus('ga', 1);
        ths.initPageview();
      });
    }

    /*
    setTimeout(function(){
      ths.setStatus('ga', 1);
      ths.initPageview();

    }, 3000);
    */
  };

  ths._getEntranceAnalytics = function() {
    var d, a;
    d = {
      entrancePage: {
        protocol: ths.location.protocol,
        hostname: ths.location.host,
        pathname: ths.location.pathname,
        search: ths.location.search
      },
      referrer: {
        protocol: '',
        hostname: '',
        pathname: '',
        search: ''
      }
    };
    if (isString(document.referrer)) {
      a = document.referrer.split('//');
      if (a.length == 2) {
        d.referrer.protocol = a[0];
        a = a[1].split('?');
        if (a.length == 2) {
          d.referrer.search = '?' + a[1];
        }
        a = a[0].split('/');
        d.referrer.hostname = a.shift();
        d.referrer.pathname = '/' + a.join('/');
      }
    };
    return d;
  };

  ths._parseGaParams = function _parseGaParams() {
    var utmb = ths.getCookie('__utmb');
    if (utmb == '') {
      //setTimeout(function() { ths._processPageviewOnReady(); }, 1000);
      return;
    }

    // parse utma
    var a = ths.getCookie('__utma');

    if (a) {
      a = a.split('.');
      if (a.length === 6) {
        ths._ga.domainHash = a[0];
        ths._ga.vid = a[1];
        ths._ga.firstVisitTime = a[2];
        ths._ga.prevSessionTime = a[3];
        ths._ga.sessionTime = a[4];
        ths._ga.sessionCounter = a[5];
        ths.sessionData.sid = parseInt(a[5]);
        ths.sid = parseInt(a[5]);
      }
    }

    // parse utmz
    ths.sessionData.trafficSource = {};
    var a = ths.getCookie('__utmz');
    if (a) {
      a = a.split('.');
      if (a.length == 5) {
        ths._ga.campaignNumber = a[3];
        var keys = {
          utmcsr: 'source',
          utmcmd: 'medium',
          utmctr: 'term',
          utmcct: 'content',
          utmccn: 'campaign',
          utmgclid: 'gclid'
        };

        var b = a[4].split('|');
        for (var i in b) {
          var c = b[i].split('=');
          if (keys[c[0]] != undefined) {
            ths.sessionData.trafficSource[keys[c[0]]] = c[1];
          }
        }
      }
    }

    //if (ths.settings.trackRealtime == 1) {
      //ths._processRealtimeQueue();
    //}
  };

  ths.pageview = function(def) {
    if (def != undefined) {
      ths.pageviewDef = def;
    }
    ths.setStatus('pageview', 1);
    ths.initPageview();
  };

  /**
   * Object Initilization Phase III: pageview send
   *
   */
  ths.initPageview = function() {
    var a, key, k2, i, s, n, data, v, push, call, followups, intelEvents, method;

    if (!ths.status.ga || !ths.status.pageview || ths.status.send) {
      return;
    }

    ths.log(ths.name + '.initPageview()');

    if (ths.settings.stopPattern != undefined) {
      // exit if uri matches stop pattnern
      if (window.location.pathname.indexOf(ths.settings.stopPattern) == 0) {
        return;
      }
    }

    // initialize page.visibility
    ths.handleVisibilityChange();
    // setup trigger for visibilityChange
    document.addEventListener('visibilitychange', ths.handleVisibilityChange);

    // setup trigger for timeInterval
    win.setInterval(function(){
      _ioq.handleTimeInterval();
    }, 1000);

  /*
    call = ['ga.send', 'pageview'];
    if ((typeof _l10im == 'object') && isFunction(_l10im.markTime)) {
      _l10im.markTime('push.l10i._trackPageview');
      call.push({
        hitCallback: function () { _l10im.markTime('l10i._trackPageview');}
      });
    }
    ths.push(call);
*/



    // save gaSend data to pageData
    if (!isNull(ths.trackers[ths.trackerPrefix]) && isFunction(ths.trackers[ths.trackerPrefix].get)) {
      v = ths.trackers[ths.trackerPrefix].get('location');
      if (v) {
        // trim trailing slash if exist
        a = document.createElement('a');
        a.href = v;
        if (a.pathname.length > 1 && a.pathname.substr(-1) == '/') {
          a.pathname = a.pathname.slice(0, -1)
          v = a.href;
          // save back to ga tracker
          ths.trackers[ths.trackerPrefix].set('location', v);
        }

        ths.gaSend['location'] = v;
        // if pageUri not set using systemPath, set it to using ga location
        if (isNull(ths.settings.pageUri)) {
          s = v.split('//');
          ths.settings.pageUri = (s[1]) ? '//' + s[1] : s[0];
        }
      }

      v = ths.trackers[ths.trackerPrefix].get('page');
      if (v) {
        ths.pageviewDef.dimension15 = ths.gaSend['page'] = v;
      }
      else {
        ths.pageviewDef.dimension15 = location.pathname;
      }


      v = ths.trackers[ths.trackerPrefix].get('title');
      if (v) {
        ths.gaSend['title'] = v;
        ths.pageviewDef.dimension14 = 'Page: ' + v;
      }
      v = ths.trackers[ths.trackerPrefix].get('referrer');
      if (v) {
        ths.gaSend['referrer'] = v;
      }

      for (i in ths.customVarScope) {
        if (ths.customVarScope[i] == 'page') {
          v = ths.trackers[ths.trackerPrefix].get(i);
          if (v) {
            ths.gaSendCustomVars[i] = v;
          }
        }
      }



      // testing timing of page embedded io.set
      v = ths.trackers[ths.trackerPrefix].get('dimension6');
      if (!v) {
        io('ga.set', 'dimension6', '(not set)');
      }
    }



    if (!ths.isPageRefresh) {
      // do basic analytics scoring for visitor if scorings available
      a = 0;
      if (!isNull(ths.settings.scorings)) {
        if (ths.isEntrance && !isNull(ths.settings.scorings.entrance)) {
          a = ths.settings.scorings.entrance;
          v = io('get', 'va.sc');
          if (!v) {
            io('set', 'va.sc', 0);
          }
        }
        else if (!ths.isEntrance && !isNull(ths.settings.scorings.additional_pages)) {
          a = ths.settings.scorings.additional_pages;
        }
      }
      if (a > 0) {
        ths.pageviewDef.metric1 = a;
        ths.pageviewDef.metric5 = 1;
        io('set', 'va.sc', '=+' + a);
      }
    }

    //if (!ths.isPageRefresh) {
      // send pageview hit
      call = ['ga.send', 'pageview', ths.pageviewDef];
      if ((typeof _l10im == 'object') && isFunction(_l10im.markTime)) {
        _l10im.markTime('push.l10i._trackPageview');
        call.push({
          hitCallback: function () { _l10im.markTime('l10i._trackPageview');}
        });
      }
      ths.push(call);
      ths.gaSend['time'] = ths.curGaTs;
    //}
    //else {
      // trigger Page refresh event
    //  ths.event({
    //    eventCategory: 'Page refresh'
    //  });
    //}


    // set time on gaSend

    ths.setStatus('send', 1);
    ths._processQueue();

    // send any pageshow events (not binding via on/onEvent)
    ths.processEventQueue();

    // these are saved for last for performance reasons.
    // Don't want local disk access to delay GA pageview
    ths.loadVarFromLocal('session');
    ths.loadVarFromLocal('visitor');

    ths.initSessionData();
    ths.initVisitorData();

    // do set callbacks
    s = ['session', 'visitor'];
    for (i = 0; i < 2; i++) {
      data = ths[s[i] + 'Data'];
      n = 'set.' + s[i];
      for (key in data) {
        if (data.hasOwnProperty(key)) {
          data = data[key];
          if (isStdObject(data) && !isNull(data._updated)) {
            ths.triggerCallbacks(n + '.' + key, data, '_');
            /*
             if (key == 'analytics') {
             for (k2 in data) {
             if (data.hasOwnProperty(k2)) {
             ths.triggerCallbacks(n + '.' + key + '.' + k2, data[k2], '_');
             }
             }
             */
          }
        }
      }
    }

    if (!ths.isPageRefresh) {
      if (!isArray(ths.sessionData.pageviews)) {
        ths.sessionData.pageviews = [];
      }

      ths.sessionData.pageviews.push({
        location: ths.gaSend['location'],
        title: ths.gaSend['title'],
        time: ths.gaSend['time']
      });
      //io('set', 'session.pageviews.' + ths.pageviewSent, {'location': ths.location.href}, {'_last': ths.pageviewSent});
      if (ths.isEntrance) {
        //io('set', 'session:entrancePage', {location: ths.location.href, time: ths.pageviewSent});
        io('set', 'session:referrer', {location: document.referrer, time: ths.pageviewSent});
        if (ths.vtkid && ths.settings.fetchGaRealtime) {
          io('event', {
            eventCategory: 'Visitor session',
            eventAction: ths.vtkid,
          });
        }
      }
      if (ths.isNewVisitor) {
        io('set', 'visitor:entrancePage', {location: ths.location.href, time: ths.pageviewSent});
        io('set', 'visitor:referrer', {location: document.referrer, time: ths.pageviewSent});
      }

      if (!ths.sessionData.stick && ths.sessionData.pageviews.length > 1) {
        ths.handleSessionStick();
      }
    }
      /*
    else {
      // if pageRefresh, set gaSend time back to original page send
      a = ths.sessionData.pageviews[ths.sessionData.pageviews.length-1];
      if (a['time']) {
        ths.gaSend['time'] = a['time'];
      }
    }
    */



    // check for any unprocessed formSubmits
    /*
     if (!isNull(ths.sessionData.formSubmit) && ths.sessionData.formSubmit._unprocessed) {
     ths.processFormSubmission();
     }
     */

    ths.setStatus('attrs', 1);
    ths._processQueue();
    jQuery(document).ready(function() {
      if ((typeof _l10im == 'object') && isFunction(_l10im.log)) {
        _l10im.log('jQuery.ready');
      }
      ths.initDom(ths.eventQueue, s);
    });
  };

  /**
   * Object Initilization Phase IV: handles events requiring dom bindings
   *
   */
  ths.initDom = function(intelEvents, s) {
    var $selector, $obj, $this, def, $ = jQuery;

    ths.log(ths.name + '.initDom()');

    if (ths.isDebug()) {
      var v = ths.trackers[ths.trackerPrefix].get('dimension6');
      if (!v || v == '(not set)') {
        jQuery('div.site-content').css('outline', '3px solid #FF0000');
      }
    }

    // assure this only runs once
    if (ths.status.dom == 1) {
      return;
    }

    ths.$ = jQuery;
    ths.$win = ths.$(win);
    ths.$doc = ths.$(doc);
    if (ths.settings.contentSelector) {
      ths.$content = $(ths.settings.contentSelector);
      if (ths.$content.length != 1) {
        ths.$content = null;
      }
    }

    // init scroll values
    ths.handleScroll();
    // add trigger for scroll
    window.addEventListener('scroll', ths.handleScroll);


    ths.setStatus('dom', 1);
    ths._processQueue();
    ths.processEventQueue();
  };

  ths.initSessionData = function() {

  };

  ths.initVisitorData = function() {

  };

  ths.pluginDefs = {};
  ths.plugins = {};
  ths.pluginQueues = {};
  ths.providePlugin = function (name, constr, arg0) {
    // check method exists
    if (!isFunction(constr)) {
      return;
    }
    ths.pluginDefs[name] = ths.pluginDefs[name] || {};
    ths.pluginDefs[name].name = name;
    ths.pluginDefs[name].constr = constr;
    if (!isNull(arg0)) {
      ths.pluginDefs[name].args = [].slice.call(arguments, 2);
    }
    ths.initPlugin(ths.pluginDefs[name]);
  };

  ths.requirePlugin = function (name) {
    ths.pluginDefs[name] = ths.pluginDefs[name] || {};
    ths.pluginDefs[name].args = [].slice.call(arguments, 1);
    ths.initPlugins();
  };

  ths.initPlugin = function (def) {
    var i;
    if (!ths.status.exec) {
      return;
    }
    if (!isNull(def.constr) && !isNull(def.args)) {
      ths.plugins[def.name] = new def.constr(ths, def.name, def.args[0]);
      if (isArray(ths.pluginQueues[def.name])) {
        for(i = 0; i < ths.pluginQueues[def.name].length; i++) {
          ths._processCall(ths.pluginQueues[def.name][i]);
        }
      }
    }
    //ths.plugins[i] = new def.constr.apply(ths, def.args);
  };

  ths._processPluginDefs = function () {
    var i, def;

    for (i in ths.pluginDefs) {

      if (ths.pluginDefs.hasOwnProperty(i)) {
        def = ths.pluginDefs[i];
        if (isNull(ths.plugins[i])) {
          ths.initPlugin(def);
          //delete ths.pluginDefs[i];
        }
      }
    }
  };

  ths.hasPlugin = function(name) {
    return !isNull(ths.plugins[name]);
  };

  // setup queue
  if (isArray(_l10iq)) {
    ths.queue = ths.queue.concat(_l10iq);
  }
  _l10iq.push = function(call) {
    return ths.push(call);
  };

  ths.ioName = win.L10iObject;
  if (isArray(win[ths.ioName].q)) {
    // .q contains arguments. Convert them to true arrays.
    var a = win[ths.ioName].q, c, i;
    var b = ths.objectMerge([], ths.queue);
    for (i=0;i<a.length;i++) {
      ths.queue.push([].slice.call(a[i]));
    }

    // log pre-queued pushes
    for (i=0;i<ths.queue.length;i++) {
      c = ths.queue[i];
      ths.logPush(c, 'pre-queued');
      // clone call and push to log
      if (isFunction(c.slice)) {
        ths.pushLog.push(c.slice());
      }
    }
    //ths.queue = ths.queue.concat(win[ths.ioName].q);
  }

  ths.iot = win[ths.ioName].t;

  win[ths.ioName] = function() {
    var call = [].slice.call(arguments);
    return ths.push(call);
  };

  ths.io = win[ths.ioName];

  // if ga embed has not loaded yet, spoof it to store commands until it loads.
  if (!win.GoogleAnalyticsObject) {
    win.GoogleAnalyticsObject = 'ga';
  }

  ths.gaName = win.GoogleAnalyticsObject;
  if (!isFunction(win[ths.gaName])) {
    ths.log('SPOOF GA');
    win.ga = win.ga || function() {
        (win['ga'].q = win['ga'].q || []).push(arguments)
    };
  }
  ths.ga = win[ths.gaName];

  // use ga callback to updated fully loaded ga object
  ths.ga(function() {
    ths.ga = window[ths.gaName];
  });


  /*
   ths.ga(function(tracker) {
   //var defaultPage = tracker.get('page');
   });
   */

  return ths;
/*
  return {

    init: ths.init,
    isArray: ths.isArray,
    isEmpty: ths.isEmpty,
    isNumeric: ths.isNumeric,
    isObject: ths.isObject,
    isStdObject: ths.isStdObject,
    isString: ths.isString,
    log: ths.log,
    objectMerge: ths.objectMerge,
    parseUrl: ths.parseUrl,
    get: ths.get,
    getCookie: ths.getCookie,
    getFlag: ths.getFlag,
    getLocalItem: ths.getLocalItem,
    set: ths.set,
    setConfig: ths.setConfig,
    setCookie: ths.setCookie,
    setFlag: ths.setFlag,
    setLocalItem: ths.setLocalItem,
    deleteCookie: ths.deleteCookie,
    removeLocalItem: ths.removeLocalItem,

    getJSON: ths.getJSON,
    getJSONCallback: ths.getJSONCallback,
    getTime: ths.getTime,

    push: ths.push,

    addCallback: ths.addCallback,
    triggerCallbacks: ths.triggerCallbacks,
    onReady: ths.onReady,

    event: ths.event,
    triggerEvent: ths.triggerEvent,
    trackCta: ths.trackCta,
    trackForm: ths.trackForm,
    saveFormSubmit: ths.saveFormSubmit
  }
  */
})(_ioq);

_ioq.init();

/*


 var L10iTestPlugin = function (_ioq, config) {
 var ioq = _ioq;
 var io = _ioq.io;
 this.config = config;
 console.log("L10iTestPlugin()");
 console.log(this);
 console.log(arguments);
 _ioq.push(['addCallback', 'set', this.setCallback, this]);
 }

 L10iTestPlugin.prototype.set = function (name, value) {
 this[name] = value;
 console.log(this);
 }

 L10iTestPlugin.prototype.get = function (name) {
 return this[name];
 console.log(this);
 }

 L10iTestPlugin.prototype.getThis = function (name) {
 return this;
 }

 L10iTestPlugin.prototype.logEvent = function (event) {
 console.log(event);
 console.log(this);
 }

 L10iTestPlugin.prototype.setCallback = function (name, value) {
 console.log('setCallback()');
 console.log(name);
 console.log(this);
 }

 _ioq.push(['providePlugin', 'test', L10iTestPlugin, {debug: 1, via: 'intel'}]);

 ga('provide', 'test', L10iTestPlugin);
 ga('require', 'test', {debug: 1, via: 'ga'});


 _ioq.push(['test:set', 'ioDirect', 1]);
 ga('test:set', 'gaDirect', 1);

 setTimeout(_ioq.push(['test:set', 'ioTo', 2]), 2000);
 setTimeout(ga('test:set', 'gaTo', 2), 2000);

 setTimeout(function () { _ioq.push(['test:set', 'ioTo2', 3]); }, 4000);
 setTimeout(function () { ga('test:set', 'gaTo2', 3); }, 4000);

 jQuery(document).ready(function() {
 jQuery('h1').on('click', function (evt) {
 _ioq.push(['test:set', 'ioEvent', 4, evt]);
 ga('test:set', 'gaEvent', 4, evt);
 });
 });
 /**/

/*

 var evtDef = {};
 evtDef = {
 'eventCategory': 'H1 click',
 'eventAction': '[[text]]',
 'selector': 'h1',
 'onEvent': 'click'
 };
 io('event', evtDef);
 */
/*
 evtDef = {
 'eventCategory': 'CTA hover',
 'eventAction': '[[title]]',
 'selector': "#block-cta-sel-cta-sidebar .content",
 'selectorFilter': ':first',
 'onEvent': 'mouseover',
 'triggerCallback': function(evtDef, $obj, event) {console.log('CTA hover'); console.log($obj); console.log(event);},
 'onSelector': 'a',
 };
 io('event', evtDef);
 console.log('hi');
 */