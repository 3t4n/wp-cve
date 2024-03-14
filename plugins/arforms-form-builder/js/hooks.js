this["wp"] = this["wp"] || {}; this["wp"]["hooks"] =
 (function(modules) {
 
 	var installedModules = {};

 
 	function __webpack_require__(moduleId) {

 	
 		if(installedModules[moduleId]) {
 			return installedModules[moduleId].exports;
 		}
 	
 		var module = installedModules[moduleId] = {
 			i: moduleId,
 			l: false,
 			exports: {}
 		};

 	
 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

 	
 		module.l = true;

 	
 		return module.exports;
 	}


 
 	__webpack_require__.m = modules;

 
 	__webpack_require__.c = installedModules;

 
 	__webpack_require__.d = function(exports, name, getter) {
 		if(!__webpack_require__.o(exports, name)) {
 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
 		}
 	};

 
 	__webpack_require__.r = function(exports) {
 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
 		}
 		Object.defineProperty(exports, '__esModule', { value: true });
 	};

 
 
 
 
 
 	__webpack_require__.t = function(value, mode) {
 		if(mode & 1) value = __webpack_require__(value);
 		if(mode & 8) return value;
 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
 		var ns = Object.create(null);
 		__webpack_require__.r(ns);
 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
 		return ns;
 	};

 
 	__webpack_require__.n = function(module) {
 		var getter = module && module.__esModule ?
 			function getDefault() { return module['default']; } :
 			function getModuleExports() { return module; };
 		__webpack_require__.d(getter, 'a', getter);
 		return getter;
 	};

 
 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };

 
 	__webpack_require__.p = "";


 
 	return __webpack_require__(__webpack_require__.s = 433);
 })

 ({

 18:
 (function(module, __webpack_exports__, __webpack_require__) {

"use strict";

var arrayLikeToArray = __webpack_require__(25);


function _arrayWithoutHoles(arr) {
  if (Array.isArray(arr)) return Object(arrayLikeToArray["a" ])(arr);
}
var iterableToArray = __webpack_require__(35);

var unsupportedIterableToArray = __webpack_require__(27);

function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}
 __webpack_require__.d(__webpack_exports__, "a", function() { return _toConsumableArray; });




function _toConsumableArray(arr) {
  return _arrayWithoutHoles(arr) || Object(iterableToArray["a" ])(arr) || Object(unsupportedIterableToArray["a" ])(arr) || _nonIterableSpread();
}

 }),

 25:
 (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
 __webpack_require__.d(__webpack_exports__, "a", function() { return _arrayLikeToArray; });
function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;

  for (var i = 0, arr2 = new Array(len); i < len; i++) {
    arr2[i] = arr[i];
  }

  return arr2;
}

 }),

 27:
 (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
 __webpack_require__.d(__webpack_exports__, "a", function() { return _unsupportedIterableToArray; });
 var _arrayLikeToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(25);

function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return Object(_arrayLikeToArray__WEBPACK_IMPORTED_MODULE_0__[ "a"])(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(n);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return Object(_arrayLikeToArray__WEBPACK_IMPORTED_MODULE_0__[ "a"])(o, minLen);
}

 }),

 35:
 (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
 __webpack_require__.d(__webpack_exports__, "a", function() { return _iterableToArray; });
function _iterableToArray(iter) {
  if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter);
}

 }),

 433:
 (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);


function validateNamespace(namespace) {
  if ('string' !== typeof namespace || '' === namespace) {
   
    console.error('The namespace must be a non-empty string.');
    return false;
  }

  if (!/^[a-zA-Z][a-zA-Z0-9_.\-\/]*$/.test(namespace)) {
   
    console.error('The namespace can only contain numbers, letters, dashes, periods, underscores and slashes.');
    return false;
  }

  return true;
}

 var build_module_validateNamespace = (validateNamespace);


function validateHookName(hookName) {
  if ('string' !== typeof hookName || '' === hookName) {
   
    console.error('The hook name must be a non-empty string.');
    return false;
  }

  if (/^__/.test(hookName)) {
   
    console.error('The hook name cannot begin with `__`.');
    return false;
  }

  if (!/^[a-zA-Z][a-zA-Z0-9_.-]*$/.test(hookName)) {
   
    console.error('The hook name can only contain numbers, letters, dashes, periods and underscores.');
    return false;
  }

  return true;
}

 var build_module_validateHookName = (validateHookName);







function createAddHook(hooks) {
  
  return function addHook(hookName, namespace, callback) {
    var priority = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 10;

    if (!build_module_validateHookName(hookName)) {
      return;
    }

    if (!build_module_validateNamespace(namespace)) {
      return;
    }

    if ('function' !== typeof callback) {
     
      console.error('The hook callback must be a function.');
      return;
    }


    if ('number' !== typeof priority) {
     
      console.error('If specified, the hook priority must be a number.');
      return;
    }

    var handler = {
      callback: callback,
      priority: priority,
      namespace: namespace
    };

    if (hooks[hookName]) {
     
      var handlers = hooks[hookName].handlers;
      var i;

      for (i = handlers.length; i > 0; i--) {
        if (priority >= handlers[i - 1].priority) {
          break;
        }
      }

      if (i === handlers.length) {
       
        handlers[i] = handler;
      } else {
       
        handlers.splice(i, 0, handler);
      }
     
     
     


      (hooks.__current || []).forEach(function (hookInfo) {
        if (hookInfo.name === hookName && hookInfo.currentIndex >= i) {
          hookInfo.currentIndex++;
        }
      });
    } else {
     
      hooks[hookName] = {
        handlers: [handler],
        runs: 0
      };
    }

    if (hookName !== 'hookAdded') {
      doAction('hookAdded', hookName, namespace, callback, priority);
    }
  };
}

 var build_module_createAddHook = (createAddHook);







function createRemoveHook(hooks, removeAll) {
  
  return function removeHook(hookName, namespace) {
    if (!build_module_validateHookName(hookName)) {
      return;
    }

    if (!removeAll && !build_module_validateNamespace(namespace)) {
      return;
    }


    if (!hooks[hookName]) {
      return 0;
    }

    var handlersRemoved = 0;

    if (removeAll) {
      handlersRemoved = hooks[hookName].handlers.length;
      hooks[hookName] = {
        runs: hooks[hookName].runs,
        handlers: []
      };
    } else {
     
      var handlers = hooks[hookName].handlers;

      var _loop = function _loop(i) {
        if (handlers[i].namespace === namespace) {
          handlers.splice(i, 1);
          handlersRemoved++;
         
         
         
         

          (hooks.__current || []).forEach(function (hookInfo) {
            if (hookInfo.name === hookName && hookInfo.currentIndex >= i) {
              hookInfo.currentIndex--;
            }
          });
        }
      };

      for (var i = handlers.length - 1; i >= 0; i--) {
        _loop(i);
      }
    }

    if (hookName !== 'hookRemoved') {
      doAction('hookRemoved', hookName, namespace);
    }

    return handlersRemoved;
  };
}

 var build_module_createRemoveHook = (createRemoveHook);


function createHasHook(hooks) {
  
  return function hasHook(hookName, namespace) {
   
    if ('undefined' !== typeof namespace) {
      return hookName in hooks && hooks[hookName].handlers.some(function (hook) {
        return hook.namespace === namespace;
      });
    }

    return hookName in hooks;
  };
}

 var build_module_createHasHook = (createHasHook);

var toConsumableArray = __webpack_require__(18);




function createRunHook(hooks, returnFirstArg) {
  
  return function runHooks(hookName) {
    if (!hooks[hookName]) {
      hooks[hookName] = {
        handlers: [],
        runs: 0
      };
    }

    hooks[hookName].runs++;
    var handlers = hooks[hookName].handlers;

    if (false) {}

    for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
      args[_key - 1] = arguments[_key];
    }

    if (!handlers || !handlers.length) {
      return returnFirstArg ? args[0] : undefined;
    }

    var hookInfo = {
      name: hookName,
      currentIndex: 0
    };

    hooks.__current.push(hookInfo);

    while (hookInfo.currentIndex < handlers.length) {
      var handler = handlers[hookInfo.currentIndex];
      var result = handler.callback.apply(null, args);

      if (returnFirstArg) {
        args[0] = result;
      }

      hookInfo.currentIndex++;
    }

    hooks.__current.pop();

    if (returnFirstArg) {
      return args[0];
    }
  };
}

 var build_module_createRunHook = (createRunHook);


function createCurrentHook(hooks) {
  
  return function currentHook() {
    if (!hooks.__current || !hooks.__current.length) {
      return null;
    }

    return hooks.__current[hooks.__current.length - 1].name;
  };
}

 var build_module_createCurrentHook = (createCurrentHook);


function createDoingHook(hooks) {
  
  return function doingHook(hookName) {
   
    if ('undefined' === typeof hookName) {
      return 'undefined' !== typeof hooks.__current[0];
    }


    return hooks.__current[0] ? hookName === hooks.__current[0].name : false;
  };
}

 var build_module_createDoingHook = (createDoingHook);





function createDidHook(hooks) {
  
  return function didHook(hookName) {
    if (!build_module_validateHookName(hookName)) {
      return;
    }

    return hooks[hookName] && hooks[hookName].runs ? hooks[hookName].runs : 0;
  };
}

 var build_module_createDidHook = (createDidHook);











function createHooks() {
  var actions = Object.create(null);
  var filters = Object.create(null);
  actions.__current = [];
  filters.__current = [];
  return {
    addAction: build_module_createAddHook(actions),
    addFilter: build_module_createAddHook(filters),
    removeAction: build_module_createRemoveHook(actions),
    removeFilter: build_module_createRemoveHook(filters),
    hasAction: build_module_createHasHook(actions),
    hasFilter: build_module_createHasHook(filters),
    removeAllActions: build_module_createRemoveHook(actions, true),
    removeAllFilters: build_module_createRemoveHook(filters, true),
    doAction: build_module_createRunHook(actions),
    applyFilters: build_module_createRunHook(filters, true),
    currentAction: build_module_createCurrentHook(actions),
    currentFilter: build_module_createCurrentHook(filters),
    doingAction: build_module_createDoingHook(actions),
    doingFilter: build_module_createDoingHook(filters),
    didAction: build_module_createDidHook(actions),
    didFilter: build_module_createDidHook(filters),
    actions: actions,
    filters: filters
  };
}

 var build_module_createHooks = (createHooks);

 __webpack_require__.d(__webpack_exports__, "addAction", function() { return addAction; });
 __webpack_require__.d(__webpack_exports__, "addFilter", function() { return addFilter; });
 __webpack_require__.d(__webpack_exports__, "removeAction", function() { return removeAction; });
 __webpack_require__.d(__webpack_exports__, "removeFilter", function() { return removeFilter; });
 __webpack_require__.d(__webpack_exports__, "hasAction", function() { return hasAction; });
 __webpack_require__.d(__webpack_exports__, "hasFilter", function() { return hasFilter; });
 __webpack_require__.d(__webpack_exports__, "removeAllActions", function() { return removeAllActions; });
 __webpack_require__.d(__webpack_exports__, "removeAllFilters", function() { return removeAllFilters; });
 __webpack_require__.d(__webpack_exports__, "doAction", function() { return doAction; });
 __webpack_require__.d(__webpack_exports__, "applyFilters", function() { return applyFilters; });
 __webpack_require__.d(__webpack_exports__, "currentAction", function() { return currentAction; });
 __webpack_require__.d(__webpack_exports__, "currentFilter", function() { return currentFilter; });
 __webpack_require__.d(__webpack_exports__, "doingAction", function() { return doingAction; });
 __webpack_require__.d(__webpack_exports__, "doingFilter", function() { return doingFilter; });
 __webpack_require__.d(__webpack_exports__, "didAction", function() { return didAction; });
 __webpack_require__.d(__webpack_exports__, "didFilter", function() { return didFilter; });
 __webpack_require__.d(__webpack_exports__, "actions", function() { return build_module_actions; });
 __webpack_require__.d(__webpack_exports__, "filters", function() { return build_module_filters; });
__webpack_require__.d(__webpack_exports__, "createHooks", function() { return build_module_createHooks; });



var _createHooks = build_module_createHooks(),
    addAction = _createHooks.addAction,
    addFilter = _createHooks.addFilter,
    removeAction = _createHooks.removeAction,
    removeFilter = _createHooks.removeFilter,
    hasAction = _createHooks.hasAction,
    hasFilter = _createHooks.hasFilter,
    removeAllActions = _createHooks.removeAllActions,
    removeAllFilters = _createHooks.removeAllFilters,
    doAction = _createHooks.doAction,
    applyFilters = _createHooks.applyFilters,
    currentAction = _createHooks.currentAction,
    currentFilter = _createHooks.currentFilter,
    doingAction = _createHooks.doingAction,
    doingFilter = _createHooks.doingFilter,
    didAction = _createHooks.didAction,
    didFilter = _createHooks.didFilter,
    build_module_actions = _createHooks.actions,
    build_module_filters = _createHooks.filters;




 })

 });