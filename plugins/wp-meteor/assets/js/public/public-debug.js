(() => {
  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/utils/delta.js
  var delta_default = () => Math.round(performance.now()) / 1e3;

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/literals.js
  var addEventListener = "addEventListener";
  var removeEventListener = "removeEventListener";
  var getAttribute = "getAttribute";
  var setAttribute = "setAttribute";
  var removeAttribute = "removeAttribute";
  var hasAttribute = "hasAttribute";
  var querySelector = "querySelector";
  var querySelectorAll = querySelector + "All";
  var appendChild = "appendChild";
  var removeChild = "removeChild";
  var createElement = "createElement";
  var tagName = "tagName";
  var getOwnPropertyDescriptor = "getOwnPropertyDescriptor";
  var prototype = "prototype";
  var __lookupGetter__ = "__lookupGetter__";
  var __lookupSetter__ = "__lookupSetter__";
  var DCL = "DOMContentLoaded";
  var L = "load";
  var E = "error";

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/globals.js
  var w = window;
  var d = document;
  var de = d.documentElement;
  var c = true ? console.log : () => {
  };
  var ce = console.error;

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/mocks/jquery.js
  var mocked = true;
  var jQueryMock = class {
    constructor() {
      this.known = [];
    }
    init() {
      let Mock;
      let Mock$;
      const override = (jQuery2, symbol) => {
        if (mocked && jQuery2 && jQuery2.fn && !jQuery2.__wpmeteor) {
          c(delta_default(), "new " + symbol + " detected", jQuery2.__wpmeteor, jQuery2);
          const enqueue = function(func) {
            c(delta_default(), "enqueued jQuery(func)", func);
            d[addEventListener](DCL, (e) => {
              c(delta_default(), "running enqueued jQuery function", func);
              func.call(d, jQuery2, e, "jQueryMock");
            });
            return this;
          };
          this.known.push([jQuery2, jQuery2.fn.ready, jQuery2.fn.init.prototype.ready]);
          jQuery2.fn.ready = enqueue;
          jQuery2.fn.init.prototype.ready = enqueue;
          jQuery2.__wpmeteor = true;
        }
        return jQuery2;
      };
      if (window.jQuery || window.$) {
        ce(delta_default(), "WARNING: JQUERY WAS INSTALLED BEFORE WP-METEOR, PROBABLY FROM A CHROME EXTENSION");
      }
      Object.defineProperty(window, "jQuery", {
        get() {
          return Mock;
        },
        set(jQuery2) {
          Mock = override(jQuery2, "jQuery");
        }
        // configurable: true
      });
      Object.defineProperty(window, "$", {
        get() {
          return Mock$;
        },
        set($) {
          Mock$ = override($, "$");
        }
        // configurable: true
      });
    }
    unmock() {
      this.known.forEach(([jQuery2, oldReady, oldPrototypeReady]) => {
        c(delta_default(), "unmocking jQuery", jQuery2);
        jQuery2.fn.ready = oldReady;
        jQuery2.fn.init.prototype.ready = oldPrototypeReady;
      });
      mocked = false;
    }
  };

  // node_modules/@aguidrevitch/fpo-inpage-first-interaction/src/browser/utils/delta.mjs
  var delta_default2 = () => Math.round(performance.now()) / 1e3;

  // node_modules/@aguidrevitch/fpo-inpage-first-interaction/src/browser/utils/console.mjs
  var c2 = true ? console.log : () => {
  };
  var console_default = c2;

  // node_modules/@aguidrevitch/fpo-inpage-events/src/index.mjs
  var EVENT_FIRST_INTERACTION = "fpo:first-interaction";
  var EVENT_REPLAY_CAPTURED_EVENTS = "fpo:replay-captured-events";
  var EVENT_ELEMENT_LOADED = "fpo:element-loaded";
  var EVENT_IMAGES_LOADED = "fpo:images-loaded";
  var EVENT_THE_END = "fpo:the-end";

  // node_modules/@aguidrevitch/fpo-inpage-first-interaction/src/browser/index.mjs
  var EVENT_CLICK = "click";
  var w2 = window;
  var wOrigAddEventListener = w2.addEventListener.bind(w2);
  var wOrigRemoveEventListener = w2.removeEventListener.bind(w2);
  var ra = "removeAttribute";
  var ga = "getAttribute";
  var sa = "setAttribute";
  var passiveEvents = ["touchstart", "touchmove", "touchend", "touchcancel", "keydown", "wheel"];
  var activeEvents = ["mouseover", "mouseout", EVENT_CLICK];
  var captureEvents = ["touchstart", "touchend", "touchcancel", "mouseover", "mouseout", EVENT_CLICK];
  var prefix = "data-wpmeteor-";
  var separator = "----";
  var dispatchEvent2 = "dispatchEvent";
  var synteticCick = (e) => {
    console_default(delta_default2(), "creating syntetic click event for", e);
    const event = new MouseEvent(EVENT_CLICK, {
      view: e.view,
      bubbles: true,
      cancelable: true
    });
    Object.defineProperty(event, "target", { writable: false, value: e.target });
    return event;
  };
  var InteractionEvents = class {
    static capture() {
      let firstInteractionFired = false;
      const capturedEvents = [];
      const captureEvent = (e) => {
        if (e.target && dispatchEvent2 in e.target) {
          if (!e.isTrusted) {
            console_default(delta_default2(), "the event is not trusted, configuration issues, not recording", e.type, e.target);
            console_default(delta_default2(), "please double check if first interaction listener was installed before wp-meteor");
            return;
          }
          if (e.cancelable && !passiveEvents.includes(e.type)) {
            try {
              e.preventDefault();
            } catch {
            }
          }
          e.stopImmediatePropagation();
          if (e.type === EVENT_CLICK) {
            console_default(delta_default2(), "captured", e.type, e.target);
            capturedEvents.push(synteticCick(e));
          } else if (captureEvents.includes(e.type)) {
            console_default(delta_default2(), "captured", e.type, e.target);
            capturedEvents.push(e);
          }
          e.target[sa](prefix + e.type, true);
          if (!firstInteractionFired) {
            firstInteractionFired = true;
            w2[dispatchEvent2](new CustomEvent(EVENT_FIRST_INTERACTION));
          }
        }
      };
      w2.addEventListener(EVENT_REPLAY_CAPTURED_EVENTS, () => {
        console_default(delta_default2(), separator, "got " + EVENT_REPLAY_CAPTURED_EVENTS);
        console_default(delta_default2(), separator, "removing event listeners");
        activeEvents.forEach((event) => wOrigRemoveEventListener(event, captureEvent, { passive: false, capture: true }));
        passiveEvents.forEach((event) => wOrigRemoveEventListener(event, captureEvent, { passive: true, capture: true }));
        let e;
        while (e = capturedEvents.shift()) {
          var target = e.target;
          if (target[ga](prefix + "touchstart") && target[ga](prefix + "touchend") && !target[ga](prefix + EVENT_CLICK)) {
            if (target[ga](prefix + "touchmove")) {
              console_default(delta_default2(), `touchmove happened, so not dispatching ${EVENT_CLICK} to `, e.target);
            } else {
              capturedEvents.push(synteticCick(e));
            }
            target[ra](prefix + "touchstart");
            target[ra](prefix + "touchend");
          } else {
            target[ra](prefix + e.type);
          }
          console_default(delta_default2(), " dispatching " + e.type + " to ", e.target);
          target[dispatchEvent2](e);
        }
      });
      console_default(delta_default2(), separator, "installing first interaction event listeners");
      activeEvents.forEach((event) => wOrigAddEventListener(event, captureEvent, { passive: false, capture: true }));
      passiveEvents.forEach((event) => wOrigAddEventListener(event, captureEvent, { passive: true, capture: true }));
    }
  };
  var browser_default = InteractionEvents;

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/utils/event-emitter.js
  var EventEmitter = class {
    constructor() {
      this.l = [];
    }
    emit(name, data = null) {
      this.l[name] && this.l[name].forEach((l) => l(data));
    }
    on(name, callback) {
      this.l[name] ||= [];
      this.l[name].push(callback);
    }
    off(name, callback) {
      this.l[name] = (this.l[name] || []).filter((c3) => c3 !== callback);
    }
    /*
    once(name, callback) {
        const closure = () => {
            this.off(closure);
            callback();
        }
        this.l[name] ||= [];
        this.l[name].push(closure);
    }
    */
  };

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/utils/dispatcher.js
  var dispatcher_default = new EventEmitter();

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/elementor/device-mode.js
  var $deviceMode = d[createElement]("span");
  $deviceMode[setAttribute]("id", "elementor-device-mode");
  $deviceMode[setAttribute]("class", "elementor-screen-only");
  var attached = false;
  var device_mode_default = () => {
    if (!attached) {
      d.body[appendChild]($deviceMode);
    }
    return getComputedStyle($deviceMode, ":after").content.replace(/"/g, "");
  };

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/elementor/animations.js
  var getClass = (el) => {
    return el[getAttribute]("class") || "";
  };
  var setClass = (el, value) => {
    return el[setAttribute]("class", value);
  };
  var animations_default = () => {
    w[addEventListener](L, function() {
      const mode = device_mode_default();
      const vw = Math.max(de.clientWidth || 0, w.innerWidth || 0);
      const vh = Math.max(de.clientHeight || 0, w.innerHeight || 0);
      const keys = ["_animation_" + mode, "animation_" + mode, "_animation", "_animation", "animation"];
      Array.from(d[querySelectorAll](".elementor-invisible")).forEach((el) => {
        const viewportOffset = el.getBoundingClientRect();
        if (viewportOffset.top + w.scrollY <= vh && viewportOffset.left + w.scrollX < vw) {
          try {
            const settings = JSON.parse(el[getAttribute]("data-settings"));
            if (settings.trigger_source) {
              return;
            }
            const animationDelay = settings._animation_delay || settings.animation_delay || 0;
            let animation, key;
            for (var i2 = 0; i2 < keys.length; i2++) {
              if (settings[keys[i2]]) {
                key = keys[i2];
                animation = settings[key];
                break;
              }
            }
            if (animation) {
              c(delta_default(), "animating with" + animation, el);
              const oldClass = getClass(el);
              const newClass = animation === "none" ? oldClass : oldClass + " animated " + animation;
              const animate = () => {
                setClass(el, newClass.replace(/\belementor-invisible\b/, ""));
                keys.forEach((key2) => delete settings[key2]);
                el[setAttribute]("data-settings", JSON.stringify(settings));
              };
              let timeout = setTimeout(animate, animationDelay);
              dispatcher_default.on("fi", () => {
                clearTimeout(timeout);
                setClass(el, getClass(el).replace(new RegExp("\\b" + animation + "\\b"), ""));
              });
            }
          } catch (e) {
            console.error(e);
          }
        }
      });
    });
  };

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/includes/elementor/pp-menu.js
  var inmega = "data-in-mega_smartmenus";
  var pp_menu_default = () => {
    const div = d[createElement]("div");
    div.innerHTML = '<span class="sub-arrow --wp-meteor"><i class="fa" aria-hidden="true"></i></span>';
    const placeholder = div.firstChild;
    const prevAll = (el) => {
      const result = [];
      while (el = el.previousElementSibling)
        result.push(el);
      return result;
    };
    d[addEventListener](DCL, function() {
      Array.from(d[querySelectorAll](".pp-advanced-menu ul")).forEach((ul) => {
        if (ul[getAttribute](inmega)) {
          return;
        } else if ((ul[getAttribute]("class") || "").match(/\bmega-menu\b/)) {
          ul[querySelectorAll]("ul").forEach((ul2) => {
            ul2[setAttribute](inmega, true);
          });
        }
        let prev = prevAll(ul);
        let a = prev.filter((el) => el).filter((el) => el[tagName] === "A").pop();
        if (!a) {
          a = prev.map((el) => Array.from(el[querySelectorAll]("a"))).filter((el) => el).flat().pop();
        }
        if (a) {
          const span = placeholder.cloneNode(true);
          a[appendChild](span);
          const observer2 = new MutationObserver((mutations) => {
            mutations.forEach(({ addedNodes }) => {
              addedNodes.forEach((node) => {
                if (node.nodeType === 1 && "SPAN" === node[tagName]) {
                  try {
                    a[removeChild](span);
                  } catch {
                  }
                }
              });
            });
          });
          observer2.observe(a, { childList: true });
        }
      });
    });
  };

  // node_modules/@aguidrevitch/fpo-inpage-wp-meteor/src/browser/public.js
  var RSC = "readystatechange";
  var M = "message";
  var separator2 = "----";
  var S = "SCRIPT";
  var prefix2 = "data-wpmeteor-";
  var Object_defineProperty = Object.defineProperty;
  var Object_defineProperties = Object.defineProperties;
  var javascriptBlocked = "javascript/blocked";
  var isJavascriptRegexp = /^\s*(application|text)\/javascript|module\s*$/i;
  var _rAF = "requestAnimationFrame";
  var _rIC = "requestIdleCallback";
  var _setTimeout = "setTimeout";
  var windowEventPrefix = w.constructor.name + "::";
  var documentEventPrefix = d.constructor.name + "::";
  var forEach = function(callback, thisArg) {
    thisArg = thisArg || w;
    for (var i2 = 0; i2 < this.length; i2++) {
      callback.call(thisArg, this[i2], i2, this);
    }
  };
  if ("NodeList" in w && !NodeList[prototype].forEach) {
    c("polyfilling NodeList.forEach");
    NodeList[prototype].forEach = forEach;
  }
  if ("HTMLCollection" in w && !HTMLCollection[prototype].forEach) {
    c("polyfilling HTMLCollection.forEach");
    HTMLCollection[prototype].forEach = forEach;
  }
  (() => {
    if (_wpmeteor["elementor-animations"]) {
      animations_default();
    }
    if (_wpmeteor["elementor-pp"]) {
      pp_menu_default();
    }
  })();
  var reorder = [];
  var defer = [];
  var async = [];
  var DONE = false;
  var eventQueue = [];
  var listeners = {};
  var WindowLoaded = false;
  var firedEventsCount = 0;
  var rAF = d.visibilityState === "visible" ? w[_rAF] : w[_setTimeout];
  var rIC = w[_rIC] || rAF;
  d[addEventListener]("visibilitychange", () => {
    rAF = d.visibilityState === "visible" ? w[_rAF] : w[_setTimeout];
    rIC = w[_rIC] || rAF;
  });
  var nextTick = w[_setTimeout];
  var createElementOverride;
  var capturedAttributes = ["src", "type"];
  var O = Object;
  var definePropert = "definePropert";
  O[definePropert + "y"] = (object, property, options) => {
    if (object === w && ["jQuery", "onload"].indexOf(property) >= 0 || (object === d || object === d.body) && ["readyState", "write", "writeln", "on" + RSC].indexOf(property) >= 0) {
      if (["on" + RSC, "on" + L].indexOf(property) && options.set) {
        listeners["on" + RSC] = listeners["on" + RSC] || [];
        listeners["on" + RSC].push(options.set);
      } else {
        ce("Denied " + (object.constructor || {}).name + " " + property + " redefinition");
      }
      return object;
    } else if (object instanceof HTMLScriptElement && capturedAttributes.indexOf(property) >= 0) {
      if (!object[property + "Getters"]) {
        object[property + "Getters"] = [];
        object[property + "Setters"] = [];
        Object_defineProperty(object, property, {
          set(value) {
            object[property + "Setters"].forEach((setter) => setter.call(object, value));
          },
          get() {
            return object[property + "Getters"].slice(-1)[0]();
          }
        });
      }
      if (options.get) {
        object[property + "Getters"].push(options.get);
      }
      if (options.set) {
        object[property + "Setters"].push(options.set);
      }
      return object;
    }
    return Object_defineProperty(object, property, options);
  };
  O[definePropert + "ies"] = (object, properties) => {
    for (let i2 in properties) {
      O[definePropert + "y"](object, i2, properties[i2]);
    }
    for (let sym of Object.getOwnPropertySymbols(properties)) {
      O[definePropert + "y"](object, sym, properties[sym]);
    }
    return object;
  };
  if (true) {
    d[addEventListener](RSC, () => {
      c(delta_default(), separator2, RSC, d.readyState);
    });
    d[addEventListener](DCL, () => {
      c(delta_default(), separator2, DCL);
    });
    dispatcher_default.on(EVENT_THE_END, () => {
      c(delta_default(), separator2, EVENT_THE_END);
      c(delta_default(), separator2, firedEventsCount + " queued events fired");
    });
    w[addEventListener](L, () => {
      c(delta_default(), separator2, L);
    });
  }
  var origAddEventListener = EventTarget[prototype][addEventListener];
  var origRemoveEventListener = EventTarget[prototype][removeEventListener];
  var dOrigAddEventListener = origAddEventListener.bind(d);
  var dOrigRemoveEventListener = origRemoveEventListener.bind(d);
  var wOrigAddEventListener2 = origAddEventListener.bind(w);
  var wOrigRemoveEventListener2 = origRemoveEventListener.bind(w);
  var origCreateElement = Document[prototype].createElement;
  var dOrigCreateElement = origCreateElement.bind(d);
  var origReadyStateGetter = d.__proto__[__lookupGetter__]("readyState").bind(d);
  var readyState = "loading";
  Object_defineProperty(d, "readyState", {
    get() {
      return readyState;
    },
    set(value) {
      return readyState = value;
    }
  });
  var hasUnfiredListeners = (eventNames) => {
    return eventQueue.filter(([event, , context], j) => {
      if (eventNames.indexOf(event.type) < 0) {
        return;
      }
      if (!context) {
        context = event.target;
      }
      try {
        const name = context.constructor.name + "::" + event.type;
        for (let i2 = 0; i2 < listeners[name].length; i2++) {
          if (listeners[name][i2]) {
            const listenerKey = name + "::" + j + "::" + i2;
            if (!firedListeners[listenerKey]) {
              return true;
            }
          }
        }
      } catch (e) {
      }
    }).length;
  };
  var currentlyFiredEvent;
  var firedListeners = {};
  var fireQueuedEvents = (eventNames) => {
    eventQueue.forEach(([event, readyState2, context], j) => {
      if (eventNames.indexOf(event.type) < 0) {
        return;
      }
      if (!context) {
        context = event.target;
      }
      try {
        const name = context.constructor.name + "::" + event.type;
        if ((listeners[name] || []).length) {
          for (let i2 = 0; i2 < listeners[name].length; i2++) {
            const func = listeners[name][i2];
            if (func) {
              const listenerKey = name + "::" + j + "::" + i2;
              if (!firedListeners[listenerKey]) {
                firedListeners[listenerKey] = true;
                d.readyState = readyState2;
                currentlyFiredEvent = name;
                try {
                  firedEventsCount++;
                  c(delta_default(), "firing " + event.type + "(" + d.readyState + ") for", func[prototype] ? func[prototype].constructor : func);
                  if (!func[prototype] || func[prototype].constructor === func) {
                    func.bind(context)(event);
                  } else {
                    func(event);
                  }
                } catch (e) {
                  ce(e, func);
                }
                currentlyFiredEvent = null;
              }
            }
          }
        }
      } catch (e) {
        ce(e);
      }
    });
  };
  dOrigAddEventListener(DCL, (e) => {
    c(delta_default(), "enqueued document " + DCL);
    eventQueue.push([new e.constructor(DCL, e), origReadyStateGetter(), d]);
  });
  dOrigAddEventListener(RSC, (e) => {
    c(delta_default(), "enqueued document " + RSC);
    eventQueue.push([new e.constructor(RSC, e), origReadyStateGetter(), d]);
  });
  wOrigAddEventListener2(DCL, (e) => {
    c(delta_default(), "enqueued window " + DCL);
    eventQueue.push([new e.constructor(DCL, e), origReadyStateGetter(), w]);
  });
  wOrigAddEventListener2(L, (e) => {
    WindowLoaded = true;
    c(delta_default(), "enqueued window " + L);
    eventQueue.push([new e.constructor(L, e), origReadyStateGetter(), w]);
    if (!iterating) {
      fireQueuedEvents([DCL, RSC, M, L]);
    }
  });
  var messageListener = (e) => {
    c(delta_default(), "enqueued " + M);
    eventQueue.push([e, d.readyState, w]);
  };
  var origWindowOnMessageGetter = w[__lookupGetter__]("onmessage");
  var origWindowOnMessageSetter = w[__lookupSetter__]("onmessage");
  var restoreMessageListener = () => {
    wOrigRemoveEventListener2(M, messageListener);
    (listeners[windowEventPrefix + "message"] || []).forEach((listener) => {
      wOrigAddEventListener2(M, listener);
    });
    Object_defineProperty(w, "onmessage", {
      get: origWindowOnMessageGetter,
      set: origWindowOnMessageSetter
    });
    c(delta_default(), "message listener restored");
  };
  wOrigAddEventListener2(M, messageListener);
  var jQuery = new jQueryMock();
  jQuery.init();
  var startIterating = () => {
    if (!iterating && !DONE) {
      iterating = true;
      mayBePreloadScripts();
      d.readyState = "loading";
      nextTick(iterate);
    }
    if (!WindowLoaded) {
      wOrigAddEventListener2(L, () => {
        c(delta_default(), separator2, "starting iterating after window loaded");
        startIterating();
      });
    }
  };
  wOrigAddEventListener2(EVENT_FIRST_INTERACTION, () => {
    c(delta_default(), separator2, "starting iterating on first interaction");
    startIterating();
  });
  dispatcher_default.on(EVENT_IMAGES_LOADED, () => {
    c(delta_default(), separator2, "starting iterating after images loaded");
    startIterating();
  });
  (() => {
    if (_wpmeteor.rdelay >= 0) {
      browser_default.capture();
    }
  })();
  var scriptsToLoad = 1;
  var scriptLoaded = () => {
    c(delta_default(), "scriptLoaded", scriptsToLoad - 1);
    if (!--scriptsToLoad) {
      nextTick(dispatcher_default.emit.bind(dispatcher_default, EVENT_THE_END));
    }
  };
  var i = 0;
  var iterating = false;
  var iterate = () => {
    c(delta_default(), "it", i++, reorder.length);
    const element = reorder.shift();
    if (element) {
      if (element[getAttribute](prefix2 + "src")) {
        if (element[hasAttribute]("async")) {
          c(delta_default(), "async", scriptsToLoad, element);
          scriptsToLoad++;
          unblock(element, scriptLoaded);
          nextTick(iterate);
        } else {
          unblock(element, nextTick.bind(null, iterate));
        }
      } else if (element.origtype == javascriptBlocked) {
        unblock(element);
        nextTick(iterate);
      } else {
        ce("running next iteration", element, element.origtype, element.origtype == javascriptBlocked);
        nextTick(iterate);
      }
    } else {
      if (defer.length) {
        while (defer.length) {
          reorder.push(defer.shift());
          c(delta_default(), "adding deferred script", reorder.slice(-1)[0]);
        }
        mayBePreloadScripts();
        nextTick(iterate);
      } else if (hasUnfiredListeners([DCL, RSC, M])) {
        c(delta_default(), "firing unfired listeners");
        fireQueuedEvents([DCL, RSC, M]);
        nextTick(iterate);
      } else if (WindowLoaded) {
        if (hasUnfiredListeners([L, M])) {
          fireQueuedEvents([L, M]);
          nextTick(iterate);
        } else if (scriptsToLoad > 1) {
          c(delta_default(), "waiting for", scriptsToLoad - 1, "more scripts to load", reorder);
          rIC(iterate);
        } else if (async.length) {
          while (async.length) {
            reorder.push(async.shift());
            c(delta_default(), "adding async script", reorder.slice(-1)[0]);
          }
          mayBePreloadScripts();
          nextTick(iterate);
        } else {
          if (w.RocketLazyLoadScripts) {
            try {
              RocketLazyLoadScripts.run();
            } catch (e) {
              ce(e);
            }
          }
          d.readyState = "complete";
          restoreMessageListener();
          jQuery.unmock();
          iterating = false;
          DONE = true;
          w[_setTimeout](scriptLoaded);
        }
      } else {
        iterating = false;
      }
    }
  };
  var cloneScript = (el) => {
    const newElement = dOrigCreateElement(S);
    const attrs = el.attributes;
    for (var i2 = attrs.length - 1; i2 >= 0; i2--) {
      if (!attrs[i2].name.startsWith(prefix2)) {
        newElement[setAttribute](attrs[i2].name, attrs[i2].value);
      }
    }
    const type = el[getAttribute](prefix2 + "type");
    if (type) {
      newElement.type = type;
    } else {
      newElement.type = "text/javascript";
    }
    if ((el.textContent || "").match(/^\s*class RocketLazyLoadScripts/)) {
      newElement.textContent = el.textContent.replace(/^\s*class\s*RocketLazyLoadScripts/, "window.RocketLazyLoadScripts=class").replace("RocketLazyLoadScripts.run();", "");
    } else {
      newElement.textContent = el.textContent;
    }
    for (const property of ["onload", "onerror", "onreadystatechange"]) {
      if (el[property]) {
        c(delta_default(), `re-adding ${property} to`, el, el[property]);
        newElement[property] = el[property];
      }
    }
    return newElement;
  };
  var replaceScript = (el, newElement) => {
    const parentNode = el.parentNode;
    if (parentNode) {
      const newParent = parentNode.nodeType === 11 ? dOrigCreateElement(parentNode.host[tagName]) : dOrigCreateElement(parentNode[tagName]);
      newParent[appendChild](parentNode.replaceChild(newElement, el));
      if (!parentNode.isConnected) {
        ce("Parent for", el, " is not part of the DOM");
        return;
      }
      return el;
    }
    ce("No parent for", el);
  };
  var unblock = (el, callback) => {
    let src = el[getAttribute](prefix2 + "src");
    if (src) {
      c(delta_default(), "unblocking src", src);
      const newElement = cloneScript(el);
      const addEventListener2 = origAddEventListener ? origAddEventListener.bind(newElement) : newElement[addEventListener2].bind(newElement);
      if (el.getEventListeners) {
        el.getEventListeners().forEach(([event, listener]) => {
          c(delta_default(), "re-adding event listeners to cloned element", event, listener);
          addEventListener2(event, listener);
        });
      }
      if (callback) {
        addEventListener2(L, callback);
        addEventListener2(E, callback);
      }
      newElement.src = src;
      const oldChild = replaceScript(el, newElement);
      const type = newElement[getAttribute]("type");
      c(delta_default(), "unblocked src", src, newElement);
      if ((!oldChild || el[hasAttribute]("nomodule") || type && !isJavascriptRegexp.test(type)) && callback) {
        callback();
      }
    } else if (el.origtype === javascriptBlocked) {
      c(delta_default(), "unblocking inline", el);
      replaceScript(el, cloneScript(el));
      c(delta_default(), "unblocked inline", el);
    } else {
      ce(delta_default(), "already unblocked", el);
      if (callback) {
        callback();
      }
    }
  };
  var removeQueuedEventListener = (name, func) => {
    const pos = (listeners[name] || []).indexOf(func);
    if (pos >= 0) {
      listeners[name][pos] = void 0;
      return true;
    }
  };
  var documentAddEventListener = (event, func, ...args) => {
    if ("HTMLDocument::" + DCL == currentlyFiredEvent && event === DCL && !func.toString().match(/jQueryMock/)) {
      dispatcher_default.on(EVENT_THE_END, d[addEventListener].bind(d, event, func, ...args));
      return;
    }
    if (func && (event === DCL || event === RSC)) {
      c(delta_default(), "enqueuing event listener", event, func);
      const name = documentEventPrefix + event;
      listeners[name] = listeners[name] || [];
      listeners[name].push(func);
      if (DONE) {
        fireQueuedEvents([event]);
      }
      return;
    }
    return dOrigAddEventListener(event, func, ...args);
  };
  var documentRemoveEventListener = (event, func, ...args) => {
    if (event === DCL) {
      const name = documentEventPrefix + event;
      removeQueuedEventListener(name, func);
    }
    return dOrigRemoveEventListener(event, func, ...args);
  };
  Object_defineProperties(d, {
    [addEventListener]: {
      get() {
        return documentAddEventListener;
      },
      set() {
        return documentAddEventListener;
      }
    },
    [removeEventListener]: {
      get() {
        return documentRemoveEventListener;
      },
      set() {
        return documentRemoveEventListener;
      }
    }
  });
  var preconnects = {};
  var preconnect = (src) => {
    if (!src)
      return;
    try {
      if (src.match(/^\/\/\w+/))
        src = d.location.protocol + src;
      const url = new URL(src);
      const href = url.origin;
      if (href && !preconnects[href] && d.location.host !== url.host) {
        const s = dOrigCreateElement("link");
        s.rel = "preconnect";
        s.href = href;
        d.head[appendChild](s);
        c(delta_default(), "preconnecting", url.origin);
        preconnects[href] = true;
      }
    } catch (e) {
      ce(delta_default(), "failed to parse src for preconnect", src);
    }
  };
  var preloads = {};
  var preloadAsScript = (src, isModule, crossorigin, fragment) => {
    var s = dOrigCreateElement("link");
    s.rel = isModule ? "modulepre" + L : "pre" + L;
    s.as = "script";
    if (crossorigin)
      s[setAttribute]("crossorigin", crossorigin);
    s.href = src;
    fragment[appendChild](s);
    preloads[src] = true;
    c(delta_default(), s.rel, src);
  };
  var mayBePreloadScripts = () => {
    if (_wpmeteor.preload && reorder.length) {
      const fragment = d.createDocumentFragment();
      reorder.forEach((script) => {
        const src = script[getAttribute](prefix2 + "src");
        if (src && !preloads[src] && !script[getAttribute](prefix2 + "integrity") && !script[hasAttribute]("nomodule")) {
          preloadAsScript(src, script[getAttribute](prefix2 + "type") == "module", script[hasAttribute]("crossorigin") && script[getAttribute]("crossorigin"), fragment);
        }
      });
      rAF(d.head[appendChild].bind(d.head, fragment));
    }
  };
  dOrigAddEventListener(DCL, () => {
    const treorder = [...reorder];
    reorder.length = 0;
    [...d[querySelectorAll]("script[type='" + javascriptBlocked + "']"), ...treorder].forEach((el) => {
      if (seenScripts.has(el)) {
        ce(delta_default(), "WARNING: the scripts should have been filtered in MutationObserver", el);
        return;
      }
      const originalAttributeGetter = el[__lookupGetter__]("type").bind(el);
      Object_defineProperty(el, "origtype", {
        get() {
          return originalAttributeGetter();
        }
      });
      if ((el[getAttribute](prefix2 + "src") || "").match(/\/gtm.js\?/)) {
        c(delta_default(), "delaying regex", el[getAttribute](prefix2 + "src"));
        async.push(el);
      } else if (el[hasAttribute]("async")) {
        c(delta_default(), "delaying async", el[getAttribute](prefix2 + "src"));
        async.unshift(el);
      } else if (el[hasAttribute]("defer")) {
        c(delta_default(), "delaying defer", el[getAttribute](prefix2 + "src"));
        defer.push(el);
      } else {
        reorder.push(el);
      }
      seenScripts.add(el);
    });
  });
  var createElement2 = function(...args) {
    const scriptElt = dOrigCreateElement(...args);
    if (!args || args[0].toUpperCase() !== S || !iterating) {
      return scriptElt;
    }
    c(delta_default(), "creating script element");
    const originalSetAttribute = scriptElt[setAttribute].bind(scriptElt);
    const originalGetAttribute = scriptElt[getAttribute].bind(scriptElt);
    const originalHasAttribute = scriptElt[hasAttribute].bind(scriptElt);
    const originalAttributes = scriptElt[__lookupGetter__]("attributes").bind(scriptElt);
    const eventListeners = [];
    scriptElt.getEventListeners = () => {
      return eventListeners;
    };
    capturedAttributes.forEach((property) => {
      const originalAttributeGetter = scriptElt[__lookupGetter__](property).bind(scriptElt);
      O[definePropert + "y"](scriptElt, property, {
        set(value) {
          c(delta_default(), "setting ", property, value);
          if (property === "type" && value && !isJavascriptRegexp.test(value)) {
            return scriptElt[setAttribute](property, value);
          }
          if (property === "src" && value) {
            originalSetAttribute("type", javascriptBlocked);
          } else if (property === "type" && value && scriptElt.origsrc) {
            originalSetAttribute("type", javascriptBlocked);
          }
          return value ? scriptElt[setAttribute](prefix2 + property, value) : scriptElt[removeAttribute](prefix2 + property);
        },
        get() {
          return scriptElt[getAttribute](prefix2 + property);
        }
      });
      Object_defineProperty(scriptElt, "orig" + property, {
        get() {
          return originalAttributeGetter();
        }
      });
    });
    scriptElt[addEventListener] = function(event, handler) {
      eventListeners.push([event, handler]);
    };
    scriptElt[setAttribute] = function(property, value) {
      if (capturedAttributes.includes(property)) {
        c(delta_default(), "setting attribute", property, value);
        if (property === "type" && value && !isJavascriptRegexp.test(value)) {
          return originalSetAttribute(property, value);
        }
        if (property === "src" && value) {
          originalSetAttribute("type", javascriptBlocked);
        } else if (property === "type" && value && scriptElt.origsrc) {
          originalSetAttribute("type", javascriptBlocked);
        }
        return value ? originalSetAttribute(prefix2 + property, value) : scriptElt[removeAttribute](prefix2 + property);
      } else {
        originalSetAttribute(property, value);
      }
    };
    scriptElt[getAttribute] = function(property) {
      return capturedAttributes.indexOf(property) >= 0 ? originalGetAttribute(prefix2 + property) : originalGetAttribute(property);
    };
    scriptElt[hasAttribute] = function(property) {
      return capturedAttributes.indexOf(property) >= 0 ? originalHasAttribute(prefix2 + property) : originalHasAttribute(property);
    };
    Object_defineProperty(scriptElt, "attributes", {
      get() {
        const mock = [...originalAttributes()].filter((attr) => attr.name !== "type").map((attr) => {
          return {
            name: attr.name.match(new RegExp(prefix2)) ? attr.name.replace(prefix2, "") : attr.name,
            value: attr.value
          };
        });
        return mock;
      }
    });
    return scriptElt;
  };
  Object.defineProperty(Document[prototype], "createElement", {
    set(value) {
      if (true) {
        if (value == origCreateElement) {
          c(delta_default(), "document.createElement restored to original");
        } else if (value === createElement2) {
          c(delta_default(), "document.createElement overridden");
        } else {
          c(delta_default(), "document.createElement overridden by a 3rd party script");
        }
      }
      if (value !== createElement2) {
        createElementOverride = value;
      }
    },
    get() {
      return createElementOverride || createElement2;
    }
  });
  var seenScripts = /* @__PURE__ */ new Set();
  var observer = new MutationObserver((mutations) => {
    if (iterating) {
      mutations.forEach(({ removedNodes, addedNodes, target }) => {
        removedNodes.forEach((node) => {
          if (node.nodeType === 1 && S === node[tagName] && "origtype" in node) {
            seenScripts.delete(node);
          }
        });
        addedNodes.forEach((node) => {
          if (node.nodeType === 1) {
            if (S === node[tagName]) {
              if ("origtype" in node) {
                c(delta_default(), "captured new script", node.cloneNode(true), node);
                const src = node[getAttribute](prefix2 + "src");
                if (seenScripts.has(node)) {
                  ce("Inserted twice", node);
                }
                if (node.parentNode) {
                  seenScripts.add(node);
                  if ((src || "").match(/\/gtm.js\?/)) {
                    c(delta_default(), "delaying regex", node[getAttribute](prefix2 + "src"));
                    async.push(node);
                    preconnect(src);
                  } else if (node[hasAttribute]("async")) {
                    c(delta_default(), "delaying async", node[getAttribute](prefix2 + "src"));
                    async.unshift(node);
                    preconnect(src);
                  } else if (node[hasAttribute]("defer")) {
                    c(delta_default(), "delaying defer", node[getAttribute](prefix2 + "src"));
                    defer.push(node);
                    preconnect(src);
                  } else {
                    if (src && !node[getAttribute](prefix2 + "integrity") && !node[hasAttribute]("nomodule") && !preloads[src]) {
                      c(delta_default(), "pre preload", reorder.length);
                      preloadAsScript(src, node[getAttribute](prefix2 + "type") == "module", node[hasAttribute]("crossorigin") && node[getAttribute]("crossorigin"), d.head);
                    }
                    reorder.push(node);
                  }
                } else {
                  ce("No parent node for", node, "re-adding to", target);
                  node[addEventListener](L, (e) => e.target.parentNode[removeChild](e.target));
                  node[addEventListener](E, (e) => e.target.parentNode[removeChild](e.target));
                  target[appendChild](node);
                }
              } else {
              }
            } else if ("LINK" === node[tagName] && node[getAttribute]("as") === "script") {
              preloads[node[getAttribute]("href")] = true;
            }
          }
        });
      });
    }
  });
  var mutationObserverOptions = {
    childList: true,
    subtree: true,
    attributes: true,
    // attributeFilter: ['src', 'type'],
    attributeOldValue: true
  };
  observer.observe(d.documentElement, mutationObserverOptions);
  var origAttachShadow = HTMLElement[prototype].attachShadow;
  HTMLElement[prototype].attachShadow = function(options) {
    const shadowRoot = origAttachShadow.call(this, options);
    if (options.mode === "open") {
      observer.observe(shadowRoot, mutationObserverOptions);
    }
    return shadowRoot;
  };
  var origIFrameSrc = O[getOwnPropertyDescriptor](HTMLIFrameElement[prototype], "src");
  Object_defineProperty(HTMLIFrameElement[prototype], "src", {
    get() {
      if (this.dataset.fpoSrc) {
        return this.dataset.fpoSrc;
      }
      return origIFrameSrc.get.call(this);
    },
    set(value) {
      delete this.dataset.fpoSrc;
      origIFrameSrc.set.call(this, value);
    }
  });
  dispatcher_default.on(EVENT_THE_END, () => {
    c(delta_default(), "THE END");
    if (!createElementOverride || createElementOverride === createElement2) {
      Document[prototype].createElement = origCreateElement;
      observer.disconnect();
    } else {
      c(delta_default(), "createElement is overridden, keeping observers in place");
    }
    dispatchEvent(new CustomEvent(EVENT_REPLAY_CAPTURED_EVENTS));
    dispatchEvent(new CustomEvent(EVENT_THE_END));
  });
  var documentWrite = (str) => {
    let parent, currentScript;
    if (!d.currentScript || !d.currentScript.parentNode) {
      parent = d.body;
      currentScript = parent.lastChild;
    } else {
      currentScript = d.currentScript;
      parent = currentScript.parentNode;
    }
    try {
      const df = dOrigCreateElement("div");
      df.innerHTML = str;
      Array.from(df.childNodes).forEach((node) => {
        if (node.nodeName === S) {
          parent.insertBefore(cloneScript(node), currentScript);
        } else {
          parent.insertBefore(node, currentScript);
        }
      });
    } catch (e) {
      ce(e);
    }
  };
  var documentWriteLn = (str) => documentWrite(str + "\n");
  Object_defineProperties(d, {
    "write": {
      get() {
        return documentWrite;
      },
      set(func) {
        return documentWrite = func;
      }
    },
    "writeln": {
      get() {
        return documentWriteLn;
      },
      set(func) {
        return documentWriteLn = func;
      }
    }
  });
  var windowAddEventListener = (event, func, ...args) => {
    if (windowEventPrefix + DCL == currentlyFiredEvent && event === DCL && !func.toString().match(/jQueryMock/)) {
      dispatcher_default.on(EVENT_THE_END, w[addEventListener].bind(w, event, func, ...args));
      return;
    }
    if (windowEventPrefix + L == currentlyFiredEvent && event === L) {
      dispatcher_default.on(EVENT_THE_END, w[addEventListener].bind(w, event, func, ...args));
      return;
    }
    if (func && (event === L || event === DCL || event === M && !DONE)) {
      c(delta_default(), "enqueuing event listener", event, func);
      const name = event === DCL ? documentEventPrefix + event : windowEventPrefix + event;
      listeners[name] = listeners[name] || [];
      listeners[name].push(func);
      if (DONE) {
        fireQueuedEvents([event]);
      }
      return;
    }
    return wOrigAddEventListener2(event, func, ...args);
  };
  var windowRemoveEventListener = (event, func) => {
    if (event === L) {
      const name = event === DCL ? documentEventPrefix + event : windowEventPrefix + event;
      removeQueuedEventListener(name, func);
    }
    return wOrigRemoveEventListener2(event, func);
  };
  Object_defineProperties(w, {
    [addEventListener]: {
      get() {
        return windowAddEventListener;
      },
      set() {
        return windowAddEventListener;
      }
    },
    [removeEventListener]: {
      get() {
        return windowRemoveEventListener;
      },
      set() {
        return windowRemoveEventListener;
      }
    }
  });
  var onHandlerOptions = (name) => {
    let handler;
    return {
      get() {
        c(delta_default(), separator2, "getting " + name.toLowerCase().replace(/::/, ".") + " handler", handler);
        return handler;
      },
      set(func) {
        c(delta_default(), separator2, "setting " + name.toLowerCase().replace(/::/, ".") + " handler", func);
        if (handler) {
          removeQueuedEventListener(name, func);
        }
        listeners[name] = listeners[name] || [];
        listeners[name].push(func);
        return handler = func;
      }
      // rocket-loader from CloudFlare tries to override onload so we will let him
      // configurable: true,
    };
  };
  wOrigAddEventListener2(EVENT_ELEMENT_LOADED, (e) => {
    const { target, event } = e.detail;
    const el = target === w ? d.body : target;
    const func = el[getAttribute](prefix2 + "on" + event.type);
    el[removeAttribute](prefix2 + "on" + event.type);
    try {
      const f = new Function("event", func);
      if (target === w) {
        w[addEventListener](L, f.bind(target, event));
      } else {
        f.call(target, event);
      }
    } catch (err) {
      console.err(err);
    }
  });
  {
    const options = onHandlerOptions(windowEventPrefix + L);
    Object_defineProperty(w, "onload", options);
    dOrigAddEventListener(DCL, () => {
      Object_defineProperty(d.body, "onload", options);
    });
  }
  Object_defineProperty(d, "onreadystatechange", onHandlerOptions(documentEventPrefix + RSC));
  Object_defineProperty(w, "onmessage", onHandlerOptions(windowEventPrefix + M));
  (() => {
    const wheight = w.innerHeight;
    const wwidth = w.innerWidth;
    const intersectsViewport = (el) => {
      let extras = {
        "4g": 1250,
        "3g": 2500,
        "2g": 2500
      };
      const extra = extras[(navigator.connection || {}).effectiveType] || 0;
      const rect = el.getBoundingClientRect();
      const viewport = {
        top: -1 * wheight - extra,
        left: -1 * wwidth - extra,
        bottom: wheight + extra,
        right: wwidth + extra
      };
      if (rect.left >= viewport.right || rect.right <= viewport.left)
        return false;
      if (rect.top >= viewport.bottom || rect.bottom <= viewport.top)
        return false;
      return true;
    };
    const waitForImages = (reallyWait = true) => {
      let imagesToLoad = 1;
      let imagesLoadedCount = -1;
      const seen = {};
      const imageLoadedHandler = () => {
        imagesLoadedCount++;
        if (!--imagesToLoad) {
          c(delta_default(), imagesLoadedCount + " eager images loaded");
          w[_setTimeout](dispatcher_default.emit.bind(dispatcher_default, EVENT_IMAGES_LOADED), _wpmeteor.rdelay);
        }
      };
      Array.from(d.getElementsByTagName("*")).forEach((tag) => {
        let src, style, bgUrl;
        if (tag[tagName] === "IMG") {
          let _src = tag.currentSrc || tag.src;
          if (_src && !seen[_src] && !_src.match(/^data:/i)) {
            if ((tag.loading || "").toLowerCase() !== "lazy") {
              src = _src;
              c(delta_default(), "loading image", src, "for", tag);
            } else if (intersectsViewport(tag)) {
              src = _src;
              c(delta_default(), "loading lazy image", src, "for", tag);
            }
          }
        } else if (tag[tagName] === S) {
          preconnect(tag[getAttribute](prefix2 + "src"));
        } else if (tag[tagName] === "LINK" && tag[getAttribute]("as") === "script" && ["pre" + L, "modulepre" + L].indexOf(tag[getAttribute]("rel")) >= 0) {
          preloads[tag[getAttribute]("href")] = true;
        } else if ((style = w.getComputedStyle(tag)) && (bgUrl = (style.backgroundImage || "").match(/^url\s*\((.*?)\)/i)) && (bgUrl || []).length) {
          const url = bgUrl[0].slice(4, -1).replace(/"/g, "");
          if (!seen[url] && !url.match(/^data:/i)) {
            src = url;
            c(delta_default(), "loading background", src, "for", tag);
          }
        }
        if (src) {
          seen[src] = true;
          const temp = new Image();
          if (reallyWait) {
            imagesToLoad++;
            temp[addEventListener](L, imageLoadedHandler);
            temp[addEventListener](E, imageLoadedHandler);
          }
          temp.src = src;
        }
      });
      d.fonts.ready.then(() => {
        c(delta_default(), "fonts ready");
        imageLoadedHandler();
      });
    };
    if (_wpmeteor.rdelay === 0) {
      dOrigAddEventListener(DCL, waitForImages);
    } else {
      wOrigAddEventListener2(L, waitForImages);
    }
  })();
})();
//0.1.47
//# sourceMappingURL=public-debug.js.map
