(()=>{function t(t){return t&&t.__esModule?t.default:t}var e,r,o,n,c,l={},a=/^(?:0|[1-9]\d*)$/,i=Object.prototype,u=i.hasOwnProperty,s=i.toString,p=i.propertyIsEnumerable,f=(e=Object.keys,r=Object,function(t){return e(r(t))}),b=Math.max,g=!p.call({valueOf:1},"valueOf");/**
 * Assigns `value` to `key` of `object` if the existing value is not equivalent
 * using [`SameValueZero`](http://ecma-international.org/ecma-262/7.0/#sec-samevaluezero)
 * for equality comparisons.
 *
 * @private
 * @param {Object} object The object to modify.
 * @param {string} key The key of the property to assign.
 * @param {*} value The value to assign.
 */function d(t,e,r){var o=t[e];u.call(t,e)&&k(o,r)&&(void 0!==r||e in t)||(t[e]=r)}/**
 * Checks if `value` is a valid array-like index.
 *
 * @private
 * @param {*} value The value to check.
 * @param {number} [length=MAX_SAFE_INTEGER] The upper bounds of a valid index.
 * @returns {boolean} Returns `true` if `value` is a valid index, else `false`.
 */function v(t,e){return!!(e=null==e?9007199254740991:e)&&("number"==typeof t||a.test(t))&&t>-1&&t%1==0&&t<e}/**
 * Checks if `value` is likely a prototype object.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a prototype, else `false`.
 */function h(t){var e=t&&t.constructor;return t===("function"==typeof e&&e.prototype||i)}/**
 * Performs a
 * [`SameValueZero`](http://ecma-international.org/ecma-262/7.0/#sec-samevaluezero)
 * comparison between two values to determine if they are equivalent.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to compare.
 * @param {*} other The other value to compare.
 * @returns {boolean} Returns `true` if the values are equivalent, else `false`.
 * @example
 *
 * var object = { 'a': 1 };
 * var other = { 'a': 1 };
 *
 * _.eq(object, object);
 * // => true
 *
 * _.eq(object, other);
 * // => false
 *
 * _.eq('a', 'a');
 * // => true
 *
 * _.eq('a', Object('a'));
 * // => false
 *
 * _.eq(NaN, NaN);
 * // => true
 */function k(t,e){return t===e||t!=t&&e!=e}/**
 * Checks if `value` is classified as an `Array` object.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an array, else `false`.
 * @example
 *
 * _.isArray([1, 2, 3]);
 * // => true
 *
 * _.isArray(document.body.children);
 * // => false
 *
 * _.isArray('abc');
 * // => false
 *
 * _.isArray(_.noop);
 * // => false
 */var y=Array.isArray;/**
 * Checks if `value` is array-like. A value is considered array-like if it's
 * not a function and has a `value.length` that's an integer greater than or
 * equal to `0` and less than or equal to `Number.MAX_SAFE_INTEGER`.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is array-like, else `false`.
 * @example
 *
 * _.isArrayLike([1, 2, 3]);
 * // => true
 *
 * _.isArrayLike(document.body.children);
 * // => true
 *
 * _.isArrayLike('abc');
 * // => true
 *
 * _.isArrayLike(_.noop);
 * // => false
 */function m(t){var e,r;return null!=t&&"number"==typeof(e=t.length)&&e>-1&&e%1==0&&e<=9007199254740991&&!("[object Function]"==(r=w(t)?s.call(t):"")||"[object GeneratorFunction]"==r)}/**
 * Checks if `value` is the
 * [language type](http://www.ecma-international.org/ecma-262/7.0/#sec-ecmascript-language-types)
 * of `Object`. (e.g. arrays, functions, objects, regexes, `new Number(0)`, and `new String('')`)
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an object, else `false`.
 * @example
 *
 * _.isObject({});
 * // => true
 *
 * _.isObject([1, 2, 3]);
 * // => true
 *
 * _.isObject(_.noop);
 * // => true
 *
 * _.isObject(null);
 * // => false
 */function w(t){var e=typeof t;return!!t&&("object"==e||"function"==e)}function j(){return(j=Object.assign?Object.assign.bind():function(t){for(var e=1;e<arguments.length;e++){var r=arguments[e];for(var o in r)Object.prototype.hasOwnProperty.call(r,o)&&(t[o]=r[o])}return t}).apply(this,arguments)}o=function(t,e){if(g||h(e)||m(e)){!/**
 * Copies properties of `source` to `object`.
 *
 * @private
 * @param {Object} source The object to copy properties from.
 * @param {Array} props The property identifiers to copy.
 * @param {Object} [object={}] The object to copy properties to.
 * @param {Function} [customizer] The function to customize copied values.
 * @returns {Object} Returns `object`.
 */function(t,e,r,o){r||(r={});for(var n=-1,c=e.length;++n<c;){var l=e[n],a=o?o(r[l],t[l],l,r,t):void 0;d(r,l,void 0===a?t[l]:a)}}(e,m(e)?/**
 * Creates an array of the enumerable property names of the array-like `value`.
 *
 * @private
 * @param {*} value The value to query.
 * @param {boolean} inherited Specify returning inherited property names.
 * @returns {Array} Returns the array of property names.
 */function(t,e){// Safari 8.1 makes `arguments.callee` enumerable in strict mode.
// Safari 9 makes `arguments.length` enumerable in strict mode.
var r=y(t)||t&&"object"==typeof t&&m(t)&&u.call(t,"callee")&&(!p.call(t,"callee")||"[object Arguments]"==s.call(t))?/**
 * The base implementation of `_.times` without support for iteratee shorthands
 * or max array length checks.
 *
 * @private
 * @param {number} n The number of times to invoke `iteratee`.
 * @param {Function} iteratee The function invoked per iteration.
 * @returns {Array} Returns the array of results.
 */function(t,e){for(var r=-1,o=Array(t);++r<t;)o[r]=e(r);return o}(t.length,String):[],o=r.length,n=!!o;for(var c in t)u.call(t,c)&&!(n&&("length"==c||v(c,o)))&&r.push(c);return r}(e):/**
 * The base implementation of `_.keys` which doesn't treat sparse arrays as dense.
 *
 * @private
 * @param {Object} object The object to query.
 * @returns {Array} Returns the array of property names.
 */function(t){if(!h(t))return f(t);var e=[];for(var r in Object(t))u.call(t,r)&&"constructor"!=r&&e.push(r);return e}(e),t);return}for(var r in e)u.call(e,r)&&d(t,r,e[r])},n=function(t,e){var r=-1,n=e.length,c=n>1?e[n-1]:void 0,l=n>2?e[2]:void 0;for(c=o.length>3&&"function"==typeof c?(n--,c):void 0,l&&/**
 * Checks if the given arguments are from an iteratee call.
 *
 * @private
 * @param {*} value The potential iteratee value argument.
 * @param {*} index The potential iteratee index or key argument.
 * @param {*} object The potential iteratee object argument.
 * @returns {boolean} Returns `true` if the arguments are from an iteratee call,
 *  else `false`.
 */function(t,e,r){if(!w(r))return!1;var o=typeof e;return("number"==o?!!(m(r)&&v(e,r.length)):"string"==o&&(e in r))&&k(r[e],t)}(e[0],e[1],l)&&(c=n<3?void 0:c,n=1),t=Object(t);++r<n;){var a=e[r];a&&o(t,a,r,c)}return t},c=b(void 0===c?n.length-1:c,0),l=function(){for(var t=arguments,e=-1,r=b(t.length-c,0),o=Array(r);++e<r;)o[e]=t[c+e];e=-1;for(var l=Array(c+1);++e<c;)l[e]=t[e];return l[c]=o,/**
 * A faster alternative to `Function#apply`, this function invokes `func`
 * with the `this` binding of `thisArg` and the arguments of `args`.
 *
 * @private
 * @param {Function} func The function to invoke.
 * @param {*} thisArg The `this` binding of `func`.
 * @param {Array} args The arguments to invoke `func` with.
 * @returns {*} Returns the result of `func`.
 */function(t,e,r){switch(r.length){case 0:return t.call(e);case 1:return t.call(e,r[0]);case 2:return t.call(e,r[0],r[1]);case 3:return t.call(e,r[0],r[1],r[2])}return t.apply(e,r)}(n,this,l)};let{createHigherOrderComponent:L}=wp.compose,{Fragment:O}=wp.element,{InspectorAdvancedControls:A}=wp.blockEditor,{TextareaControl:E}=wp.components,{addFilter:P}=wp.hooks,{__:B}=wp.i18n,C=["core/freeform","core/legacy-widget","core/widget-area"];P("blocks.registerBlockType","blockLogic/attribute",(e,r)=>(C.includes(e.name)||void 0!==e.attributes&&(// Use Lodash's assign to gracefully handle if attributes are undefined
    e.attributes=/*@__PURE__*/t(l)(e.attributes,{blockLogic:{type:"string",default:""}}),e.supports=/*@__PURE__*/t(l)(e.supports,{blockLogic:!0})),e));/**
 * Create HOC to add layout control to inspector controls of block.
 */let R=L(t=>e=>{let{attributes:r,setAttributes:o,isSelected:n}=e,{blockLogic:c}=r;return c&&(r.blockLogic=c),React.createElement(O,null,React.createElement(t,e),n&&!C.includes(e.name)&&React.createElement(A,null,React.createElement(E,{rows:"2",label:B("Block Logic","block-logic"),help:B("Add valid PHP conditional tags or PHP condition that returns true or false.","block-logic"),value:c||"",onChange:t=>o({blockLogic:t})})))},"withLogicControl");P("editor.BlockEdit","blockLogic/control",R);// add a data attribute to the block wrapper "editor-block-list__block"
// this hook only fires for the BE/Admin View
let F=L(e=>r=>{let{blockLogic:o}=r.attributes,{name:n}=r.block,c=n.replace(/\w+\//gm,"");c=c.charAt(0).toUpperCase()+c.slice(1);let a=r.wrapperProps,i={};return void 0!==o&&o&&(i=/*@__PURE__*/t(l)(i,{"data-spk-block-logic":B("Block Logic:","block-logic")+" "+o})),a=/*@__PURE__*/t(l)(a,i),React.createElement(e,j({},r,{wrapperProps:a}))},"addWrapperDataAttribute");P("editor.BlockListBlock","blockLogic/addWrapperDataAttribute",F)})();//# sourceMappingURL=block-logic.js.map

//# sourceMappingURL=block-logic.js.map
