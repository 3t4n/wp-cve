/*!
 * hoverIntent v1.8.1 // 2014.08.11 // jQuery v1.9.1+
 * http://cherne.net/brian/resources/jquery.hoverIntent.html
 *
 * You may use hoverIntent under the terms of the MIT license. Basically that
 * means you are free to use hoverIntent as long as this header is left intact.
 * Copyright 2007, 2014 Brian Cherne
 */

/* hoverIntent is similar to jQuery's built-in "hover" method except that
 * instead of firing the handlerIn function immediately, hoverIntent checks
 * to see if the user's mouse has slowed down (beneath the sensitivity
 * threshold) before firing the event. The handlerOut function is only
 * called after a matching handlerIn.
 *
 * // basic usage ... just like .hover()
 * .hoverIntent( handlerIn, handlerOut )
 * .hoverIntent( handlerInOut )
 *
 * // basic usage ... with event delegation!
 * .hoverIntent( handlerIn, handlerOut, selector )
 * .hoverIntent( handlerInOut, selector )
 *
 * // using a basic configuration object
 * .hoverIntent( config )
 *
 * @param  handlerIn   function OR configuration object
 * @param  handlerOut  function OR selector for delegation OR undefined
 * @param  selector    selector OR undefined
 * @author Brian Cherne <brian(at)cherne(dot)net>
 */

!function(e){"use strict";"function"==typeof define&&define.amd?define(["jquery"],e):jQuery&&!jQuery.fn.hoverIntent&&e(jQuery)}(function(e){"use strict";var t,n,i={interval:100,sensitivity:6,timeout:0},o=0,r=function(e){t=e.pageX,n=e.pageY},u=function(e,i,o,v){return Math.sqrt((o.pX-t)*(o.pX-t)+(o.pY-n)*(o.pY-n))<v.sensitivity?(i.off(o.event,r),delete o.timeoutId,o.isActive=!0,e.pageX=t,e.pageY=n,delete o.pX,delete o.pY,v.over.apply(i[0],[e])):(o.pX=t,o.pY=n,o.timeoutId=setTimeout(function(){u(e,i,o,v)},v.interval),void 0)},v=function(e,t,n,i){return delete t.data("hoverIntent")[n.id],i.apply(t[0],[e])};e.fn.hoverIntent=function(t,n,a){var s=o++,d=e.extend({},i);d=e.isPlainObject(t)?e.extend(d,t):e.isFunction(n)?e.extend(d,{over:t,out:n,selector:a}):e.extend(d,{over:t,out:t,selector:n});var f=function(t){var n=e.extend({},t),i=e(this),o=i.data("hoverIntent");o||i.data("hoverIntent",o={});var a=o[s];a||(o[s]=a={id:s}),a.timeoutId&&(a.timeoutId=clearTimeout(a.timeoutId));var f=a.event="mousemove.hoverIntent.hoverIntent"+s;if("mouseenter"===t.type){if(a.isActive)return;a.pX=n.pageX,a.pY=n.pageY,i.off(f,r).on(f,r),a.timeoutId=setTimeout(function(){u(n,i,a,d)},d.interval)}else{if(!a.isActive)return;i.off(f,r),a.timeoutId=setTimeout(function(){v(n,i,a,d.out)},d.timeout)}};return this.on({"mouseenter.hoverIntent":f,"mouseleave.hoverIntent":f},d.selector)}});
