import jQueryMock from "./includes/mocks/jquery";
import InteractionEvents from "@aguidrevitch/fpo-inpage-first-interaction";
import dispatcher from "./includes/utils/dispatcher";
import delta from "./includes/utils/delta";
import elementorAnimations from "./includes/elementor/animations";
import elementorPP from "./includes/elementor/pp-menu";
import {
  addEventListener,
  removeEventListener,
  getAttribute,
  setAttribute,
  removeAttribute,
  hasAttribute,
  querySelectorAll,
  appendChild,
  removeChild,
  tagName,
  getOwnPropertyDescriptor,
  prototype,
  __lookupGetter__,
  __lookupSetter__,
  DCL,
  L,
  E
} from "./includes/literals";
import {
  w,
  d,
  c,
  ce
} from "./includes/globals";
import {
  EVENT_CSS_LOADED,
  EVENT_ELEMENT_LOADED,
  EVENT_FIRST_INTERACTION,
  EVENT_REPLAY_CAPTURED_EVENTS,
  EVENT_IMAGES_LOADED,
  EVENT_THE_END
} from "@aguidrevitch/fpo-inpage-events";
const RSC = "readystatechange", M = "message", separator = "----", S = "SCRIPT", prefix = "data-wpmeteor-", Object_defineProperty = Object.defineProperty, Object_defineProperties = Object.defineProperties, javascriptBlocked = "javascript/blocked", isJavascriptRegexp = /^\s*(application|text)\/javascript|module\s*$/i, _rAF = "requestAnimationFrame", _rIC = "requestIdleCallback", _setTimeout = "setTimeout";
const windowEventPrefix = w.constructor.name + "::";
const documentEventPrefix = d.constructor.name + "::";
const forEach = function(callback, thisArg) {
  thisArg = thisArg || w;
  for (var i2 = 0; i2 < this.length; i2++) {
    callback.call(thisArg, this[i2], i2, this);
  }
};
if ("NodeList" in w && !NodeList[prototype].forEach) {
  process.env.DEBUG && c("polyfilling NodeList.forEach");
  NodeList[prototype].forEach = forEach;
}
if ("HTMLCollection" in w && !HTMLCollection[prototype].forEach) {
  process.env.DEBUG && c("polyfilling HTMLCollection.forEach");
  HTMLCollection[prototype].forEach = forEach;
}
(() => {
  if (_wpmeteor["elementor-animations"]) {
    elementorAnimations();
  }
  if (_wpmeteor["elementor-pp"]) {
    elementorPP();
  }
})();
const reorder = [];
const defer = [];
const async = [];
let DONE = false;
const eventQueue = [];
let listeners = {};
let WindowLoaded = false;
let firedEventsCount = 0;
let rAF = d.visibilityState === "visible" ? w[_rAF] : w[_setTimeout];
let rIC = w[_rIC] || rAF;
d[addEventListener]("visibilitychange", () => {
  rAF = d.visibilityState === "visible" ? w[_rAF] : w[_setTimeout];
  rIC = w[_rIC] || rAF;
});
const nextTick = w[_setTimeout];
let createElementOverride;
const capturedAttributes = ["src", "type"];
const O = Object, definePropert = "definePropert";
O[definePropert + "y"] = (object, property, options) => {
  if (object === w && ["jQuery", "onload"].indexOf(property) >= 0 || (object === d || object === d.body) && ["readyState", "write", "writeln", "on" + RSC].indexOf(property) >= 0) {
    if (["on" + RSC, "on" + L].indexOf(property) && options.set) {
      listeners["on" + RSC] = listeners["on" + RSC] || [];
      listeners["on" + RSC].push(options.set);
    } else {
      process.env.DEBUG && ce("Denied " + (object.constructor || {}).name + " " + property + " redefinition");
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
if (process.env.DEBUG) {
  d[addEventListener](RSC, () => {
    c(delta(), separator, RSC, d.readyState);
  });
  d[addEventListener](DCL, () => {
    c(delta(), separator, DCL);
  });
  dispatcher.on(EVENT_THE_END, () => {
    c(delta(), separator, EVENT_THE_END);
    c(delta(), separator, firedEventsCount + " queued events fired");
  });
  w[addEventListener](L, () => {
    c(delta(), separator, L);
  });
}
const origAddEventListener = EventTarget[prototype][addEventListener];
const origRemoveEventListener = EventTarget[prototype][removeEventListener];
const dOrigAddEventListener = origAddEventListener.bind(d);
const dOrigRemoveEventListener = origRemoveEventListener.bind(d);
const wOrigAddEventListener = origAddEventListener.bind(w);
const wOrigRemoveEventListener = origRemoveEventListener.bind(w);
const origCreateElement = Document[prototype].createElement;
const dOrigCreateElement = origCreateElement.bind(d);
const origReadyStateGetter = d.__proto__[__lookupGetter__]("readyState").bind(d);
let readyState = "loading";
Object_defineProperty(d, "readyState", {
  get() {
    return readyState;
  },
  set(value) {
    return readyState = value;
  }
});
const hasUnfiredListeners = (eventNames) => {
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
let currentlyFiredEvent;
const firedListeners = {};
const fireQueuedEvents = (eventNames) => {
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
                process.env.DEBUG && c(delta(), "firing " + event.type + "(" + d.readyState + ") for", func[prototype] ? func[prototype].constructor : func);
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
  process.env.DEBUG && c(delta(), "enqueued document " + DCL);
  eventQueue.push([new e.constructor(DCL, e), origReadyStateGetter(), d]);
});
dOrigAddEventListener(RSC, (e) => {
  process.env.DEBUG && c(delta(), "enqueued document " + RSC);
  eventQueue.push([new e.constructor(RSC, e), origReadyStateGetter(), d]);
});
wOrigAddEventListener(DCL, (e) => {
  process.env.DEBUG && c(delta(), "enqueued window " + DCL);
  eventQueue.push([new e.constructor(DCL, e), origReadyStateGetter(), w]);
});
wOrigAddEventListener(L, (e) => {
  WindowLoaded = true;
  process.env.DEBUG && c(delta(), "enqueued window " + L);
  eventQueue.push([new e.constructor(L, e), origReadyStateGetter(), w]);
  if (!iterating) {
    fireQueuedEvents([DCL, RSC, M, L]);
  }
});
const messageListener = (e) => {
  process.env.DEBUG && c(delta(), "enqueued " + M);
  eventQueue.push([e, d.readyState, w]);
};
const origWindowOnMessageGetter = w[__lookupGetter__]("onmessage");
const origWindowOnMessageSetter = w[__lookupSetter__]("onmessage");
const restoreMessageListener = () => {
  wOrigRemoveEventListener(M, messageListener);
  (listeners[windowEventPrefix + "message"] || []).forEach((listener) => {
    wOrigAddEventListener(M, listener);
  });
  Object_defineProperty(w, "onmessage", {
    get: origWindowOnMessageGetter,
    set: origWindowOnMessageSetter
  });
  process.env.DEBUG && c(delta(), "message listener restored");
};
wOrigAddEventListener(M, messageListener);
const jQuery = new jQueryMock();
jQuery.init();
const startIterating = () => {
  if (!iterating && !DONE) {
    iterating = true;
    mayBePreloadScripts();
    d.readyState = "loading";
    nextTick(iterate);
  }
  if (!WindowLoaded) {
    wOrigAddEventListener(L, () => {
      process.env.DEBUG && c(delta(), separator, "starting iterating after window loaded");
      startIterating();
    });
  }
};
wOrigAddEventListener(EVENT_FIRST_INTERACTION, () => {
  process.env.DEBUG && c(delta(), separator, "starting iterating on first interaction");
  startIterating();
});
dispatcher.on(EVENT_IMAGES_LOADED, () => {
  process.env.DEBUG && c(delta(), separator, "starting iterating after images loaded");
  startIterating();
});
(() => {
  if (_wpmeteor.rdelay >= 0) {
    InteractionEvents.capture();
  }
})();
let scriptsToLoad = 1;
const scriptLoaded = () => {
  process.env.DEBUG && c(delta(), "scriptLoaded", scriptsToLoad - 1);
  if (!--scriptsToLoad) {
    nextTick(dispatcher.emit.bind(dispatcher, EVENT_THE_END));
  }
};
let i = 0;
let iterating = false;
const iterate = () => {
  process.env.DEBUG && c(delta(), "it", i++, reorder.length);
  const element = reorder.shift();
  if (element) {
    if (element[getAttribute](prefix + "src")) {
      if (element[hasAttribute]("async")) {
        process.env.DEBUG && c(delta(), "async", scriptsToLoad, element);
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
      process.env.DEBUG && ce("running next iteration", element, element.origtype, element.origtype == javascriptBlocked);
      nextTick(iterate);
    }
  } else {
    if (defer.length) {
      while (defer.length) {
        reorder.push(defer.shift());
        process.env.DEBUG && c(delta(), "adding deferred script", reorder.slice(-1)[0]);
      }
      mayBePreloadScripts();
      nextTick(iterate);
    } else if (hasUnfiredListeners([DCL, RSC, M])) {
      process.env.DEBUG && c(delta(), "firing unfired listeners");
      fireQueuedEvents([DCL, RSC, M]);
      nextTick(iterate);
    } else if (WindowLoaded) {
      if (hasUnfiredListeners([L, M])) {
        fireQueuedEvents([L, M]);
        nextTick(iterate);
      } else if (scriptsToLoad > 1) {
        process.env.DEBUG && c(delta(), "waiting for", scriptsToLoad - 1, "more scripts to load", reorder);
        rIC(iterate);
      } else if (async.length) {
        while (async.length) {
          reorder.push(async.shift());
          process.env.DEBUG && c(delta(), "adding async script", reorder.slice(-1)[0]);
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
const cloneScript = (el) => {
  const newElement = dOrigCreateElement(S);
  const attrs = el.attributes;
  for (var i2 = attrs.length - 1; i2 >= 0; i2--) {
    if (!attrs[i2].name.startsWith(prefix)) {
      newElement[setAttribute](attrs[i2].name, attrs[i2].value);
    }
  }
  const type = el[getAttribute](prefix + "type");
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
      process.env.DEBUG && c(delta(), `re-adding ${property} to`, el, el[property]);
      newElement[property] = el[property];
    }
  }
  return newElement;
};
const replaceScript = (el, newElement) => {
  const parentNode = el.parentNode;
  if (parentNode) {
    const newParent = parentNode.nodeType === 11 ? dOrigCreateElement(parentNode.host[tagName]) : dOrigCreateElement(parentNode[tagName]);
    newParent[appendChild](parentNode.replaceChild(newElement, el));
    if (!parentNode.isConnected) {
      process.env.DEBUG && ce("Parent for", el, " is not part of the DOM");
      return;
    }
    return el;
  }
  ce("No parent for", el);
};
const unblock = (el, callback) => {
  let src = el[getAttribute](prefix + "src");
  if (src) {
    process.env.DEBUG && c(delta(), "unblocking src", src);
    const newElement = cloneScript(el);
    const addEventListener2 = origAddEventListener ? origAddEventListener.bind(newElement) : newElement[addEventListener2].bind(newElement);
    if (el.getEventListeners) {
      el.getEventListeners().forEach(([event, listener]) => {
        process.env.DEBUG && c(delta(), "re-adding event listeners to cloned element", event, listener);
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
    process.env.DEBUG && c(delta(), "unblocked src", src, newElement);
    if ((!oldChild || el[hasAttribute]("nomodule") || type && !isJavascriptRegexp.test(type)) && callback) {
      callback();
    }
  } else if (el.origtype === javascriptBlocked) {
    process.env.DEBUG && c(delta(), "unblocking inline", el);
    replaceScript(el, cloneScript(el));
    process.env.DEBUG && c(delta(), "unblocked inline", el);
  } else {
    process.env.DEBUG && ce(delta(), "already unblocked", el);
    if (callback) {
      callback();
    }
  }
};
const removeQueuedEventListener = (name, func) => {
  const pos = (listeners[name] || []).indexOf(func);
  if (pos >= 0) {
    listeners[name][pos] = void 0;
    return true;
  }
};
const documentAddEventListener = (event, func, ...args) => {
  if ("HTMLDocument::" + DCL == currentlyFiredEvent && event === DCL && !func.toString().match(/jQueryMock/)) {
    dispatcher.on(EVENT_THE_END, d[addEventListener].bind(d, event, func, ...args));
    return;
  }
  if (func && (event === DCL || event === RSC)) {
    process.env.DEBUG && c(delta(), "enqueuing event listener", event, func);
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
const documentRemoveEventListener = (event, func, ...args) => {
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
const preconnects = {};
const preconnect = (src) => {
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
      process.env.DEBUG && c(delta(), "preconnecting", url.origin);
      preconnects[href] = true;
    }
  } catch (e) {
    process.env.DEBUG && ce(delta(), "failed to parse src for preconnect", src);
  }
};
const preloads = {};
const preloadAsScript = (src, isModule, crossorigin, fragment) => {
  var s = dOrigCreateElement("link");
  s.rel = isModule ? "modulepre" + L : "pre" + L;
  s.as = "script";
  if (crossorigin)
    s[setAttribute]("crossorigin", crossorigin);
  s.href = src;
  fragment[appendChild](s);
  preloads[src] = true;
  process.env.DEBUG && c(delta(), s.rel, src);
};
const mayBePreloadScripts = () => {
  if (_wpmeteor.preload && reorder.length) {
    const fragment = d.createDocumentFragment();
    reorder.forEach((script) => {
      const src = script[getAttribute](prefix + "src");
      if (src && !preloads[src] && !script[getAttribute](prefix + "integrity") && !script[hasAttribute]("nomodule")) {
        preloadAsScript(src, script[getAttribute](prefix + "type") == "module", script[hasAttribute]("crossorigin") && script[getAttribute]("crossorigin"), fragment);
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
      process.env.DEBUG && ce(delta(), "WARNING: the scripts should have been filtered in MutationObserver", el);
      return;
    }
    const originalAttributeGetter = el[__lookupGetter__]("type").bind(el);
    Object_defineProperty(el, "origtype", {
      get() {
        return originalAttributeGetter();
      }
    });
    if ((el[getAttribute](prefix + "src") || "").match(/\/gtm.js\?/)) {
      process.env.DEBUG && c(delta(), "delaying regex", el[getAttribute](prefix + "src"));
      async.push(el);
    } else if (el[hasAttribute]("async")) {
      process.env.DEBUG && c(delta(), "delaying async", el[getAttribute](prefix + "src"));
      async.unshift(el);
    } else if (el[hasAttribute]("defer")) {
      process.env.DEBUG && c(delta(), "delaying defer", el[getAttribute](prefix + "src"));
      defer.push(el);
    } else {
      reorder.push(el);
    }
    seenScripts.add(el);
  });
});
const createElement = function(...args) {
  const scriptElt = dOrigCreateElement(...args);
  if (!args || args[0].toUpperCase() !== S || !iterating) {
    return scriptElt;
  }
  process.env.DEBUG && c(delta(), "creating script element");
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
        process.env.DEBUG && c(delta(), "setting ", property, value);
        if (property === "type" && value && !isJavascriptRegexp.test(value)) {
          return scriptElt[setAttribute](property, value);
        }
        if (property === "src" && value) {
          originalSetAttribute("type", javascriptBlocked);
        } else if (property === "type" && value && scriptElt.origsrc) {
          originalSetAttribute("type", javascriptBlocked);
        }
        return value ? scriptElt[setAttribute](prefix + property, value) : scriptElt[removeAttribute](prefix + property);
      },
      get() {
        return scriptElt[getAttribute](prefix + property);
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
      process.env.DEBUG && c(delta(), "setting attribute", property, value);
      if (property === "type" && value && !isJavascriptRegexp.test(value)) {
        return originalSetAttribute(property, value);
      }
      if (property === "src" && value) {
        originalSetAttribute("type", javascriptBlocked);
      } else if (property === "type" && value && scriptElt.origsrc) {
        originalSetAttribute("type", javascriptBlocked);
      }
      return value ? originalSetAttribute(prefix + property, value) : scriptElt[removeAttribute](prefix + property);
    } else {
      originalSetAttribute(property, value);
    }
  };
  scriptElt[getAttribute] = function(property) {
    return capturedAttributes.indexOf(property) >= 0 ? originalGetAttribute(prefix + property) : originalGetAttribute(property);
  };
  scriptElt[hasAttribute] = function(property) {
    return capturedAttributes.indexOf(property) >= 0 ? originalHasAttribute(prefix + property) : originalHasAttribute(property);
  };
  Object_defineProperty(scriptElt, "attributes", {
    get() {
      const mock = [...originalAttributes()].filter((attr) => attr.name !== "type").map((attr) => {
        return {
          name: attr.name.match(new RegExp(prefix)) ? attr.name.replace(prefix, "") : attr.name,
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
    if (process.env.DEBUG) {
      if (value == origCreateElement) {
        process.env.DEBUG && c(delta(), "document.createElement restored to original");
      } else if (value === createElement) {
        process.env.DEBUG && c(delta(), "document.createElement overridden");
      } else {
        process.env.DEBUG && c(delta(), "document.createElement overridden by a 3rd party script");
      }
    }
    if (value !== createElement) {
      createElementOverride = value;
    }
  },
  get() {
    return createElementOverride || createElement;
  }
});
const seenScripts = /* @__PURE__ */ new Set();
const observer = new MutationObserver((mutations) => {
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
              process.env.DEBUG && c(delta(), "captured new script", node.cloneNode(true), node);
              const src = node[getAttribute](prefix + "src");
              if (seenScripts.has(node)) {
                ce("Inserted twice", node);
              }
              if (node.parentNode) {
                seenScripts.add(node);
                if ((src || "").match(/\/gtm.js\?/)) {
                  process.env.DEBUG && c(delta(), "delaying regex", node[getAttribute](prefix + "src"));
                  async.push(node);
                  preconnect(src);
                } else if (node[hasAttribute]("async")) {
                  process.env.DEBUG && c(delta(), "delaying async", node[getAttribute](prefix + "src"));
                  async.unshift(node);
                  preconnect(src);
                } else if (node[hasAttribute]("defer")) {
                  process.env.DEBUG && c(delta(), "delaying defer", node[getAttribute](prefix + "src"));
                  defer.push(node);
                  preconnect(src);
                } else {
                  if (src && !node[getAttribute](prefix + "integrity") && !node[hasAttribute]("nomodule") && !preloads[src]) {
                    c(delta(), "pre preload", reorder.length);
                    preloadAsScript(src, node[getAttribute](prefix + "type") == "module", node[hasAttribute]("crossorigin") && node[getAttribute]("crossorigin"), d.head);
                  }
                  reorder.push(node);
                }
              } else {
                process.env.DEBUG && ce("No parent node for", node, "re-adding to", target);
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
const mutationObserverOptions = {
  childList: true,
  subtree: true,
  attributes: true,
  attributeOldValue: true
};
observer.observe(d.documentElement, mutationObserverOptions);
const origAttachShadow = HTMLElement[prototype].attachShadow;
HTMLElement[prototype].attachShadow = function(options) {
  const shadowRoot = origAttachShadow.call(this, options);
  if (options.mode === "open") {
    observer.observe(shadowRoot, mutationObserverOptions);
  }
  return shadowRoot;
};
const origIFrameSrc = O[getOwnPropertyDescriptor](HTMLIFrameElement[prototype], "src");
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
dispatcher.on(EVENT_THE_END, () => {
  process.env.DEBUG && c(delta(), "THE END");
  if (!createElementOverride || createElementOverride === createElement) {
    Document[prototype].createElement = origCreateElement;
    observer.disconnect();
  } else {
    process.env.DEBUG && c(delta(), "createElement is overridden, keeping observers in place");
  }
  dispatchEvent(new CustomEvent(EVENT_REPLAY_CAPTURED_EVENTS));
  dispatchEvent(new CustomEvent(EVENT_THE_END));
});
let documentWrite = (str) => {
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
let documentWriteLn = (str) => documentWrite(str + "\n");
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
let windowAddEventListener = (event, func, ...args) => {
  if (windowEventPrefix + DCL == currentlyFiredEvent && event === DCL && !func.toString().match(/jQueryMock/)) {
    dispatcher.on(EVENT_THE_END, w[addEventListener].bind(w, event, func, ...args));
    return;
  }
  if (windowEventPrefix + L == currentlyFiredEvent && event === L) {
    dispatcher.on(EVENT_THE_END, w[addEventListener].bind(w, event, func, ...args));
    return;
  }
  if (func && (event === L || event === DCL || event === M && !DONE)) {
    process.env.DEBUG && c(delta(), "enqueuing event listener", event, func);
    const name = event === DCL ? documentEventPrefix + event : windowEventPrefix + event;
    listeners[name] = listeners[name] || [];
    listeners[name].push(func);
    if (DONE) {
      fireQueuedEvents([event]);
    }
    return;
  }
  return wOrigAddEventListener(event, func, ...args);
};
let windowRemoveEventListener = (event, func) => {
  if (event === L) {
    const name = event === DCL ? documentEventPrefix + event : windowEventPrefix + event;
    removeQueuedEventListener(name, func);
  }
  return wOrigRemoveEventListener(event, func);
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
const onHandlerOptions = (name) => {
  let handler;
  return {
    get() {
      process.env.DEBUG && c(delta(), separator, "getting " + name.toLowerCase().replace(/::/, ".") + " handler", handler);
      return handler;
    },
    set(func) {
      process.env.DEBUG && c(delta(), separator, "setting " + name.toLowerCase().replace(/::/, ".") + " handler", func);
      if (handler) {
        removeQueuedEventListener(name, func);
      }
      listeners[name] = listeners[name] || [];
      listeners[name].push(func);
      return handler = func;
    }
  };
};
wOrigAddEventListener(EVENT_ELEMENT_LOADED, (e) => {
  const { target, event } = e.detail;
  const el = target === w ? d.body : target;
  const func = el[getAttribute](prefix + "on" + event.type);
  el[removeAttribute](prefix + "on" + event.type);
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
        process.env.DEBUG && c(delta(), imagesLoadedCount + " eager images loaded");
        w[_setTimeout](dispatcher.emit.bind(dispatcher, EVENT_IMAGES_LOADED), _wpmeteor.rdelay);
      }
    };
    Array.from(d.getElementsByTagName("*")).forEach((tag) => {
      let src, style, bgUrl;
      if (tag[tagName] === "IMG") {
        let _src = tag.currentSrc || tag.src;
        if (_src && !seen[_src] && !_src.match(/^data:/i)) {
          if ((tag.loading || "").toLowerCase() !== "lazy") {
            src = _src;
            process.env.DEBUG && c(delta(), "loading image", src, "for", tag);
          } else if (intersectsViewport(tag)) {
            src = _src;
            process.env.DEBUG && c(delta(), "loading lazy image", src, "for", tag);
          }
        }
      } else if (tag[tagName] === S) {
        preconnect(tag[getAttribute](prefix + "src"));
      } else if (tag[tagName] === "LINK" && tag[getAttribute]("as") === "script" && ["pre" + L, "modulepre" + L].indexOf(tag[getAttribute]("rel")) >= 0) {
        preloads[tag[getAttribute]("href")] = true;
      } else if ((style = w.getComputedStyle(tag)) && (bgUrl = (style.backgroundImage || "").match(/^url\s*\((.*?)\)/i)) && (bgUrl || []).length) {
        const url = bgUrl[0].slice(4, -1).replace(/"/g, "");
        if (!seen[url] && !url.match(/^data:/i)) {
          src = url;
          process.env.DEBUG && c(delta(), "loading background", src, "for", tag);
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
      process.env.DEBUG && c(delta(), "fonts ready");
      imageLoadedHandler();
    });
  };
  if (_wpmeteor.rdelay === 0) {
    dOrigAddEventListener(DCL, waitForImages);
  } else {
    wOrigAddEventListener(L, waitForImages);
  }
})();
//# sourceMappingURL=public.js.map
