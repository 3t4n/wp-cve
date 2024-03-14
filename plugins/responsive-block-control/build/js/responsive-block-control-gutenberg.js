(()=>{function e(e){return e&&e.__esModule?e.default:e}var t,r,n,o,l,c={},a=/^(?:0|[1-9]\d*)$/,i=Object.prototype,u=i.hasOwnProperty,s=i.toString,p=i.propertyIsEnumerable,d=(t=Object.keys,r=Object,function(e){return t(r(e))}),b=Math.max,f=!p.call({valueOf:1},"valueOf");/**
 * Assigns `value` to `key` of `object` if the existing value is not equivalent
 * using [`SameValueZero`](http://ecma-international.org/ecma-262/7.0/#sec-samevaluezero)
 * for equality comparisons.
 *
 * @private
 * @param {Object} object The object to modify.
 * @param {string} key The key of the property to assign.
 * @param {*} value The value to assign.
 */function v(e,t,r){var n=e[t];u.call(e,t)&&m(n,r)&&(void 0!==r||t in e)||(e[t]=r)}/**
 * Checks if `value` is a valid array-like index.
 *
 * @private
 * @param {*} value The value to check.
 * @param {number} [length=MAX_SAFE_INTEGER] The upper bounds of a valid index.
 * @returns {boolean} Returns `true` if `value` is a valid index, else `false`.
 */function h(e,t){return!!(t=null==t?9007199254740991:t)&&("number"==typeof e||a.test(e))&&e>-1&&e%1==0&&e<t}/**
 * Checks if `value` is likely a prototype object.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a prototype, else `false`.
 */function k(e){var t=e&&e.constructor;return e===("function"==typeof t&&t.prototype||i)}/**
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
 */function m(e,t){return e===t||e!=e&&t!=t}/**
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
 */var g=Array.isArray;/**
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
 */function y(e){var t,r;return null!=e&&"number"==typeof(t=e.length)&&t>-1&&t%1==0&&t<=9007199254740991&&!("[object Function]"==(r=j(e)?s.call(e):"")||"[object GeneratorFunction]"==r)}/**
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
 */function j(e){var t=typeof e;return!!e&&("object"==t||"function"==t)}function w(){return(w=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var n in r)Object.prototype.hasOwnProperty.call(r,n)&&(e[n]=r[n])}return e}).apply(this,arguments)}n=function(e,t){if(f||k(t)||y(t)){!/**
 * Copies properties of `source` to `object`.
 *
 * @private
 * @param {Object} source The object to copy properties from.
 * @param {Array} props The property identifiers to copy.
 * @param {Object} [object={}] The object to copy properties to.
 * @param {Function} [customizer] The function to customize copied values.
 * @returns {Object} Returns `object`.
 */function(e,t,r,n){r||(r={});for(var o=-1,l=t.length;++o<l;){var c=t[o],a=n?n(r[c],e[c],c,r,e):void 0;v(r,c,void 0===a?e[c]:a)}}(t,y(t)?/**
 * Creates an array of the enumerable property names of the array-like `value`.
 *
 * @private
 * @param {*} value The value to query.
 * @param {boolean} inherited Specify returning inherited property names.
 * @returns {Array} Returns the array of property names.
 */function(e,t){// Safari 8.1 makes `arguments.callee` enumerable in strict mode.
// Safari 9 makes `arguments.length` enumerable in strict mode.
var r=g(e)||e&&"object"==typeof e&&y(e)&&u.call(e,"callee")&&(!p.call(e,"callee")||"[object Arguments]"==s.call(e))?/**
 * The base implementation of `_.times` without support for iteratee shorthands
 * or max array length checks.
 *
 * @private
 * @param {number} n The number of times to invoke `iteratee`.
 * @param {Function} iteratee The function invoked per iteration.
 * @returns {Array} Returns the array of results.
 */function(e,t){for(var r=-1,n=Array(e);++r<e;)n[r]=t(r);return n}(e.length,String):[],n=r.length,o=!!n;for(var l in e)u.call(e,l)&&!(o&&("length"==l||h(l,n)))&&r.push(l);return r}(t):/**
 * The base implementation of `_.keys` which doesn't treat sparse arrays as dense.
 *
 * @private
 * @param {Object} object The object to query.
 * @returns {Array} Returns the array of property names.
 */function(e){if(!k(e))return d(e);var t=[];for(var r in Object(e))u.call(e,r)&&"constructor"!=r&&t.push(r);return t}(t),e);return}for(var r in t)u.call(t,r)&&v(e,r,t[r])},o=function(e,t){var r=-1,o=t.length,l=o>1?t[o-1]:void 0,c=o>2?t[2]:void 0;for(l=n.length>3&&"function"==typeof l?(o--,l):void 0,c&&/**
 * Checks if the given arguments are from an iteratee call.
 *
 * @private
 * @param {*} value The potential iteratee value argument.
 * @param {*} index The potential iteratee index or key argument.
 * @param {*} object The potential iteratee object argument.
 * @returns {boolean} Returns `true` if the arguments are from an iteratee call,
 *  else `false`.
 */function(e,t,r){if(!j(r))return!1;var n=typeof t;return("number"==n?!!(y(r)&&h(t,r.length)):"string"==n&&(t in r))&&m(r[t],e)}(t[0],t[1],c)&&(l=o<3?void 0:l,o=1),e=Object(e);++r<o;){var a=t[r];a&&n(e,a,r,l)}return e},l=b(void 0===l?o.length-1:l,0),c=function(){for(var e=arguments,t=-1,r=b(e.length-l,0),n=Array(r);++t<r;)n[t]=e[l+t];t=-1;for(var c=Array(l+1);++t<l;)c[t]=e[t];return c[l]=n,/**
 * A faster alternative to `Function#apply`, this function invokes `func`
 * with the `this` binding of `thisArg` and the arguments of `args`.
 *
 * @private
 * @param {Function} func The function to invoke.
 * @param {*} thisArg The `this` binding of `func`.
 * @param {Array} args The arguments to invoke `func` with.
 * @returns {*} Returns the result of `func`.
 */function(e,t,r){switch(r.length){case 0:return e.call(t);case 1:return e.call(t,r[0]);case 2:return e.call(t,r[0],r[1]);case 3:return e.call(t,r[0],r[1],r[2])}return e.apply(t,r)}(o,this,c)};/**
 * WordPress Dependencies
 */let{createHigherOrderComponent:O}=wp.compose,{Fragment:E}=wp.element,{InspectorControls:C}=wp.blockEditor,{PanelBody:R,PanelRow:B,ToggleControl:A}=wp.components,{addFilter:H}=wp.hooks,{__:P}=wp.i18n,{dispatch:F}=wp.data;H("blocks.registerBlockType","responsiveBlockControl/attribute",(t,r)=>("core/freeform"===t.name||// Use Lodash's assign to gracefully handle if attributes are undefined
    (t.attributes=/*@__PURE__*/e(c)(t.attributes,{responsiveBlockControl:{type:"object",default:{mobile:!1,tablet:!1,desktop:!1,wide:!1}}})),t));/**
 * Create HOC to add layout control to inspector controls of block.
 */let _=O(e=>t=>{let{clientId:r,attributes:n,isSelected:o}=t,{responsiveBlockControl:l}=n,c=e=>{let t=!l[e];delete l[e];let n=Object.assign({[e]:t},l);F("core/block-editor").updateBlockAttributes(r,{responsiveBlockControl:n})};return void 0===l&&(l=[]),React.createElement(E,null,React.createElement(e,t),o&&"core/freeform"!==t.name&&React.createElement(C,null,React.createElement(R,{title:P("Visibility","responsive-block-control"),initialOpen:!1},React.createElement(B,null,React.createElement(A,{label:P("Hide on Mobile","responsive-block-control"),checked:!!l.mobile,onChange:()=>c("mobile")})),React.createElement(B,null,React.createElement(A,{label:P("Hide on Tablet","responsive-block-control"),checked:!!l.tablet,onChange:()=>c("tablet")})),React.createElement(B,null,React.createElement(A,{label:P("Hide on Desktop","responsive-block-control"),checked:!!l.desktop,onChange:()=>c("desktop")})),React.createElement(B,null,React.createElement(A,{label:P("Hide on Wide","responsive-block-control"),checked:!!l.wide,onChange:()=>c("wide")})))))},"withVisibilityControl");H("editor.BlockEdit","responsiveBlockControl/control",_);// add a data attribute to the block wrapper "editor-block-list__block"
// this hook only fires for the BE/Admin View
let D=O(t=>r=>{let{responsiveBlockControl:n}=r.attributes,o=r.wrapperProps,l={};if(void 0!==n){let t=[];for(let[e,r]of Object.entries(n))!0===r&&t.push(e);t.length>0&&(l=/*@__PURE__*/e(c)(l,{"data-is-hidden-on":P("Hidden on","responsive-block-control")+" "+t.join(" ")}))}return 0!==Object.keys(l).length&&(o=/*@__PURE__*/e(c)(o,l)),React.createElement(t,w({},r,{wrapperProps:o}))},"addWrapperDataAttribute");H("editor.BlockListBlock","responsiveBlockControl/addWrapperDataAttribute",D)})();//# sourceMappingURL=responsive-block-control-gutenberg.js.map

//# sourceMappingURL=responsive-block-control-gutenberg.js.map
