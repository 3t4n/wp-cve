"use strict";
/*!
 * jQuery Cookie Plugin v1.3.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):e(jQuery)}((function(e){var n=/\+/g;function o(e){return e}function i(e){return decodeURIComponent(e.replace(n," "))}function t(e){0===e.indexOf('"')&&(e=e.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return r.json?JSON.parse(e):e}catch(e){}}var r=e.cookie=function(n,c,a){if(void 0!==c){if("number"==typeof(a=e.extend({},r.defaults,a)).expires){var u=a.expires,f=a.expires=new Date;f.setDate(f.getDate()+u)}return c=r.json?JSON.stringify(c):String(c),document.cookie=[r.raw?n:encodeURIComponent(n),"=",r.raw?c:encodeURIComponent(c),a.expires?"; expires="+a.expires.toUTCString():"",a.path?"; path="+a.path:"",a.domain?"; domain="+a.domain:"",a.secure?"; secure":""].join("")}for(var s=r.raw?o:i,d=document.cookie.split("; "),p=n?void 0:{},m=0,x=d.length;m<x;m++){var l=d[m].split("="),v=s(l.shift()),g=s(l.join("="));if(n&&n===v){p=t(g);break}n||(p[v]=t(g))}return p};r.defaults={},e.removeCookie=function(n,o){return void 0!==e.cookie(n)&&(e.cookie(n,"",e.extend({},o,{expires:-1})),!0)}}));