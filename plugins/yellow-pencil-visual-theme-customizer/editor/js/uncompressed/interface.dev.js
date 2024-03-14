// jQuery UI Core
!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):e(jQuery)}(function(a){var e,t,n,i;function r(e,t){var n,i,r,o=e.nodeName.toLowerCase();return"area"===o?(i=(n=e.parentNode).name,!(!e.href||!i||"map"!==n.nodeName.toLowerCase())&&(!!(r=a("img[usemap='#"+i+"']")[0])&&s(r))):(/^(input|select|textarea|button|object)$/.test(o)?!e.disabled:"a"===o&&e.href||t)&&s(e)}function s(e){return a.expr.filters.visible(e)&&!a(e).parents().addBack().filter(function(){return"hidden"===a.css(this,"visibility")}).length}a.ui=a.ui||{},a.extend(a.ui,{version:"1.11.4",keyCode:{BACKSPACE:8,COMMA:188,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,LEFT:37,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SPACE:32,TAB:9,UP:38}}),a.fn.extend({scrollParent:function(e){var t=this.css("position"),n="absolute"===t,i=e?/(auto|scroll|hidden)/:/(auto|scroll)/,r=this.parents().filter(function(){var e=a(this);return(!n||"static"!==e.css("position"))&&i.test(e.css("overflow")+e.css("overflow-y")+e.css("overflow-x"))}).eq(0);return"fixed"!==t&&r.length?r:a(this[0].ownerDocument||document)},uniqueId:(e=0,function(){return this.each(function(){this.id||(this.id="ui-id-"+ ++e)})}),removeUniqueId:function(){return this.each(function(){/^ui-id-\d+$/.test(this.id)&&a(this).removeAttr("id")})}}),a.extend(a.expr[":"],{data:a.expr.createPseudo?a.expr.createPseudo(function(t){return function(e){return!!a.data(e,t)}}):function(e,t,n){return!!a.data(e,n[3])},focusable:function(e){return r(e,!isNaN(a.attr(e,"tabindex")))},tabbable:function(e){var t=a.attr(e,"tabindex"),n=isNaN(t);return(n||0<=t)&&r(e,!n)}}),a("<a>").outerWidth(1).jquery||a.each(["Width","Height"],function(e,n){var r="Width"===n?["Left","Right"]:["Top","Bottom"],i=n.toLowerCase(),o={innerWidth:a.fn.innerWidth,innerHeight:a.fn.innerHeight,outerWidth:a.fn.outerWidth,outerHeight:a.fn.outerHeight};function s(e,t,n,i){return a.each(r,function(){t-=parseFloat(a.css(e,"padding"+this))||0,n&&(t-=parseFloat(a.css(e,"border"+this+"Width"))||0),i&&(t-=parseFloat(a.css(e,"margin"+this))||0)}),t}a.fn["inner"+n]=function(e){return void 0===e?o["inner"+n].call(this):this.each(function(){a(this).css(i,s(this,e)+"px")})},a.fn["outer"+n]=function(e,t){return"number"!=typeof e?o["outer"+n].call(this,e):this.each(function(){a(this).css(i,s(this,e,!0,t)+"px")})}}),a.fn.addBack||(a.fn.addBack=function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}),a("<a>").data("a-b","a").removeData("a-b").data("a-b")&&(a.fn.removeData=(t=a.fn.removeData,function(e){return arguments.length?t.call(this,a.camelCase(e)):t.call(this)})),a.ui.ie=!!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase()),a.fn.extend({focus:(i=a.fn.focus,function(t,n){return"number"==typeof t?this.each(function(){var e=this;setTimeout(function(){a(e).focus(),n&&n.call(e)},t)}):i.apply(this,arguments)}),disableSelection:(n="onselectstart"in document.createElement("div")?"selectstart":"mousedown",function(){return this.bind(n+".ui-disableSelection",function(e){e.preventDefault()})}),enableSelection:function(){return this.unbind(".ui-disableSelection")},zIndex:function(e){if(void 0!==e)return this.css("zIndex",e);if(this.length)for(var t,n,i=a(this[0]);i.length&&i[0]!==document;){if(("absolute"===(t=i.css("position"))||"relative"===t||"fixed"===t)&&(n=parseInt(i.css("zIndex"),10),!isNaN(n)&&0!==n))return n;i=i.parent()}return 0}}),a.ui.plugin={add:function(e,t,n){var i,r=a.ui[e].prototype;for(i in n)r.plugins[i]=r.plugins[i]||[],r.plugins[i].push([t,n[i]])},call:function(e,t,n,i){var r,o=e.plugins[t];if(o&&(i||e.element[0].parentNode&&11!==e.element[0].parentNode.nodeType))for(r=0;r<o.length;r++)e.options[o[r][0]]&&o[r][1].apply(e.element,n)}}});

// jQuery UI Widget
!function(t){"function"==typeof define&&define.amd?define(["jquery"],t):t(jQuery)}(function(h){var s,i=0,a=Array.prototype.slice;return h.cleanData=(s=h.cleanData,function(t){var e,i,n;for(n=0;null!=(i=t[n]);n++)try{(e=h._data(i,"events"))&&e.remove&&h(i).triggerHandler("remove")}catch(t){}s(t)}),h.widget=function(t,i,e){var n,s,o,r,a={},u=t.split(".")[0];return t=t.split(".")[1],n=u+"-"+t,e||(e=i,i=h.Widget),h.expr[":"][n.toLowerCase()]=function(t){return!!h.data(t,n)},h[u]=h[u]||{},s=h[u][t],o=h[u][t]=function(t,e){if(!this._createWidget)return new o(t,e);arguments.length&&this._createWidget(t,e)},h.extend(o,s,{version:e.version,_proto:h.extend({},e),_childConstructors:[]}),(r=new i).options=h.widget.extend({},r.options),h.each(e,function(e,n){function s(){return i.prototype[e].apply(this,arguments)}function o(t){return i.prototype[e].apply(this,t)}h.isFunction(n)?a[e]=function(){var t,e=this._super,i=this._superApply;return this._super=s,this._superApply=o,t=n.apply(this,arguments),this._super=e,this._superApply=i,t}:a[e]=n}),o.prototype=h.widget.extend(r,{widgetEventPrefix:s&&r.widgetEventPrefix||t},a,{constructor:o,namespace:u,widgetName:t,widgetFullName:n}),s?(h.each(s._childConstructors,function(t,e){var i=e.prototype;h.widget(i.namespace+"."+i.widgetName,o,e._proto)}),delete s._childConstructors):i._childConstructors.push(o),h.widget.bridge(t,o),o},h.widget.extend=function(t){for(var e,i,n=a.call(arguments,1),s=0,o=n.length;s<o;s++)for(e in n[s])i=n[s][e],n[s].hasOwnProperty(e)&&void 0!==i&&(h.isPlainObject(i)?t[e]=h.isPlainObject(t[e])?h.widget.extend({},t[e],i):h.widget.extend({},i):t[e]=i);return t},h.widget.bridge=function(o,e){var r=e.prototype.widgetFullName||o;h.fn[o]=function(i){var t="string"==typeof i,n=a.call(arguments,1),s=this;return t?this.each(function(){var t,e=h.data(this,r);return"instance"===i?(s=e,!1):e?h.isFunction(e[i])&&"_"!==i.charAt(0)?(t=e[i].apply(e,n))!==e&&void 0!==t?(s=t&&t.jquery?s.pushStack(t.get()):t,!1):void 0:h.error("no such method '"+i+"' for "+o+" widget instance"):h.error("cannot call methods on "+o+" prior to initialization; attempted to call method '"+i+"'")}):(n.length&&(i=h.widget.extend.apply(null,[i].concat(n))),this.each(function(){var t=h.data(this,r);t?(t.option(i||{}),t._init&&t._init()):h.data(this,r,new e(i,this))})),s}},h.Widget=function(){},h.Widget._childConstructors=[],h.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",defaultElement:"<div>",options:{disabled:!1,create:null},_createWidget:function(t,e){e=h(e||this.defaultElement||this)[0],this.element=h(e),this.uuid=i++,this.eventNamespace="."+this.widgetName+this.uuid,this.bindings=h(),this.hoverable=h(),this.focusable=h(),e!==this&&(h.data(e,this.widgetFullName,this),this._on(!0,this.element,{remove:function(t){t.target===e&&this.destroy()}}),this.document=h(e.style?e.ownerDocument:e.document||e),this.window=h(this.document[0].defaultView||this.document[0].parentWindow)),this.options=h.widget.extend({},this.options,this._getCreateOptions(),t),this._create(),this._trigger("create",null,this._getCreateEventData()),this._init()},_getCreateOptions:h.noop,_getCreateEventData:h.noop,_create:h.noop,_init:h.noop,destroy:function(){this._destroy(),this.element.unbind(this.eventNamespace).removeData(this.widgetFullName).removeData(h.camelCase(this.widgetFullName)),this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName+"-disabled ui-state-disabled"),this.bindings.unbind(this.eventNamespace),this.hoverable.removeClass("ui-state-hover"),this.focusable.removeClass("ui-state-focus")},_destroy:h.noop,widget:function(){return this.element},option:function(t,e){var i,n,s,o=t;if(0===arguments.length)return h.widget.extend({},this.options);if("string"==typeof t)if(o={},t=(i=t.split(".")).shift(),i.length){for(n=o[t]=h.widget.extend({},this.options[t]),s=0;s<i.length-1;s++)n[i[s]]=n[i[s]]||{},n=n[i[s]];if(t=i.pop(),1===arguments.length)return void 0===n[t]?null:n[t];n[t]=e}else{if(1===arguments.length)return void 0===this.options[t]?null:this.options[t];o[t]=e}return this._setOptions(o),this},_setOptions:function(t){var e;for(e in t)this._setOption(e,t[e]);return this},_setOption:function(t,e){return this.options[t]=e,"disabled"===t&&(this.widget().toggleClass(this.widgetFullName+"-disabled",!!e),e&&(this.hoverable.removeClass("ui-state-hover"),this.focusable.removeClass("ui-state-focus"))),this},enable:function(){return this._setOptions({disabled:!1})},disable:function(){return this._setOptions({disabled:!0})},_on:function(r,a,t){var u,d=this;"boolean"!=typeof r&&(t=a,a=r,r=!1),t?(a=u=h(a),this.bindings=this.bindings.add(a)):(t=a,a=this.element,u=this.widget()),h.each(t,function(t,e){function i(){if(r||!0!==d.options.disabled&&!h(this).hasClass("ui-state-disabled"))return("string"==typeof e?d[e]:e).apply(d,arguments)}"string"!=typeof e&&(i.guid=e.guid=e.guid||i.guid||h.guid++);var n=t.match(/^([\w:-]*)\s*(.*)$/),s=n[1]+d.eventNamespace,o=n[2];o?u.delegate(o,s,i):a.bind(s,i)})},_off:function(t,e){e=(e||"").split(" ").join(this.eventNamespace+" ")+this.eventNamespace,t.unbind(e).undelegate(e),this.bindings=h(this.bindings.not(t).get()),this.focusable=h(this.focusable.not(t).get()),this.hoverable=h(this.hoverable.not(t).get())},_delay:function(t,e){var i=this;return setTimeout(function(){return("string"==typeof t?i[t]:t).apply(i,arguments)},e||0)},_hoverable:function(t){this.hoverable=this.hoverable.add(t),this._on(t,{mouseenter:function(t){h(t.currentTarget).addClass("ui-state-hover")},mouseleave:function(t){h(t.currentTarget).removeClass("ui-state-hover")}})},_focusable:function(t){this.focusable=this.focusable.add(t),this._on(t,{focusin:function(t){h(t.currentTarget).addClass("ui-state-focus")},focusout:function(t){h(t.currentTarget).removeClass("ui-state-focus")}})},_trigger:function(t,e,i){var n,s,o=this.options[t];if(i=i||{},(e=h.Event(e)).type=(t===this.widgetEventPrefix?t:this.widgetEventPrefix+t).toLowerCase(),e.target=this.element[0],s=e.originalEvent)for(n in s)n in e||(e[n]=s[n]);return this.element.trigger(e,i),!(h.isFunction(o)&&!1===o.apply(this.element[0],[e].concat(i))||e.isDefaultPrevented())}},h.each({show:"fadeIn",hide:"fadeOut"},function(o,r){h.Widget.prototype["_"+o]=function(e,t,i){"string"==typeof t&&(t={effect:t});var n,s=t?!0===t||"number"==typeof t?r:t.effect||r:o;"number"==typeof(t=t||{})&&(t={duration:t}),n=!h.isEmptyObject(t),t.complete=i,t.delay&&e.delay(t.delay),n&&h.effects&&h.effects.effect[s]?e[o](t):s!==o&&e[s]?e[s](t.duration,t.easing,i):e.queue(function(t){h(this)[o](),i&&i.call(e[0]),t()})}}),h.widget});

// jQuery UI Mouse
!function(e){"function"==typeof define&&define.amd?define(["jquery","./widget"],e):e(jQuery)}(function(o){var u=!1;return o(document).mouseup(function(){u=!1}),o.widget("ui.mouse",{version:"1.11.4",options:{cancel:"input,textarea,button,select,option",distance:1,delay:0},_mouseInit:function(){var t=this;this.element.bind("mousedown."+this.widgetName,function(e){return t._mouseDown(e)}).bind("click."+this.widgetName,function(e){if(!0===o.data(e.target,t.widgetName+".preventClickEvent"))return o.removeData(e.target,t.widgetName+".preventClickEvent"),e.stopImmediatePropagation(),!1}),this.started=!1},_mouseDestroy:function(){this.element.unbind("."+this.widgetName),this._mouseMoveDelegate&&this.document.unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate)},_mouseDown:function(e){if(!u){this._mouseMoved=!1,this._mouseStarted&&this._mouseUp(e),this._mouseDownEvent=e;var t=this,s=1===e.which,i=!("string"!=typeof this.options.cancel||!e.target.nodeName)&&o(e.target).closest(this.options.cancel).length;return!(s&&!i&&this._mouseCapture(e))||(this.mouseDelayMet=!this.options.delay,this.mouseDelayMet||(this._mouseDelayTimer=setTimeout(function(){t.mouseDelayMet=!0},this.options.delay)),this._mouseDistanceMet(e)&&this._mouseDelayMet(e)&&(this._mouseStarted=!1!==this._mouseStart(e),!this._mouseStarted)?(e.preventDefault(),!0):(!0===o.data(e.target,this.widgetName+".preventClickEvent")&&o.removeData(e.target,this.widgetName+".preventClickEvent"),this._mouseMoveDelegate=function(e){return t._mouseMove(e)},this._mouseUpDelegate=function(e){return t._mouseUp(e)},this.document.bind("mousemove."+this.widgetName,this._mouseMoveDelegate).bind("mouseup."+this.widgetName,this._mouseUpDelegate),e.preventDefault(),u=!0))}},_mouseMove:function(e){if(this._mouseMoved){if(o.ui.ie&&(!document.documentMode||document.documentMode<9)&&!e.button)return this._mouseUp(e);if(!e.which)return this._mouseUp(e)}return(e.which||e.button)&&(this._mouseMoved=!0),this._mouseStarted?(this._mouseDrag(e),e.preventDefault()):(this._mouseDistanceMet(e)&&this._mouseDelayMet(e)&&(this._mouseStarted=!1!==this._mouseStart(this._mouseDownEvent,e),this._mouseStarted?this._mouseDrag(e):this._mouseUp(e)),!this._mouseStarted)},_mouseUp:function(e){return this.document.unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate),this._mouseStarted&&(this._mouseStarted=!1,e.target===this._mouseDownEvent.target&&o.data(e.target,this.widgetName+".preventClickEvent",!0),this._mouseStop(e)),u=!1},_mouseDistanceMet:function(e){return Math.max(Math.abs(this._mouseDownEvent.pageX-e.pageX),Math.abs(this._mouseDownEvent.pageY-e.pageY))>=this.options.distance},_mouseDelayMet:function(){return this.mouseDelayMet},_mouseStart:function(){},_mouseDrag:function(){},_mouseStop:function(){},_mouseCapture:function(){return!0}})});

// jQuery UI Slider
!function(e){"function"==typeof define&&define.amd?define(["jquery","./core","./mouse","./widget"],e):e(jQuery)}(function(r){return r.widget("ui.slider",r.ui.mouse,{version:"1.11.4",widgetEventPrefix:"slide",options:{animate:!1,distance:0,max:100,min:0,orientation:"horizontal",range:!1,step:1,value:0,values:null,change:null,slide:null,start:null,stop:null},numPages:5,_create:function(){this._keySliding=!1,this._mouseSliding=!1,this._animateOff=!0,this._handleIndex=null,this._detectOrientation(),this._mouseInit(),this._calculateNewMax(),this.element.addClass("ui-slider ui-slider-"+this.orientation+" ui-widget ui-widget-content ui-corner-all"),this._refresh(),this._setOption("disabled",this.options.disabled),this._animateOff=!1},_refresh:function(){this._createRange(),this._createHandles(),this._setupEvents(),this._refreshValue()},_createHandles:function(){var e,t,i=this.options,s=this.element.find(".ui-slider-handle").addClass("ui-state-default ui-corner-all"),a=[];for(t=i.values&&i.values.length||1,s.length>t&&(s.slice(t).remove(),s=s.slice(0,t)),e=s.length;e<t;e++)a.push("<span class='ui-slider-handle ui-state-default ui-corner-all' tabindex='0'></span>");this.handles=s.add(r(a.join("")).appendTo(this.element)),this.handle=this.handles.eq(0),this.handles.each(function(e){r(this).data("ui-slider-handle-index",e)})},_createRange:function(){var e=this.options,t="";e.range?(!0===e.range&&(e.values?e.values.length&&2!==e.values.length?e.values=[e.values[0],e.values[0]]:r.isArray(e.values)&&(e.values=e.values.slice(0)):e.values=[this._valueMin(),this._valueMin()]),this.range&&this.range.length?this.range.removeClass("ui-slider-range-min ui-slider-range-max").css({left:"",bottom:""}):(this.range=r("<div></div>").appendTo(this.element),t="ui-slider-range ui-widget-header ui-corner-all"),this.range.addClass(t+("min"===e.range||"max"===e.range?" ui-slider-range-"+e.range:""))):(this.range&&this.range.remove(),this.range=null)},_setupEvents:function(){this._off(this.handles),this._on(this.handles,this._handleEvents),this._hoverable(this.handles),this._focusable(this.handles)},_destroy:function(){this.handles.remove(),this.range&&this.range.remove(),this.element.removeClass("ui-slider ui-slider-horizontal ui-slider-vertical ui-widget ui-widget-content ui-corner-all"),this._mouseDestroy()},_mouseCapture:function(e){var t,i,s,a,n,h,l,o=this,u=this.options;return!u.disabled&&(this.elementSize={width:this.element.outerWidth(),height:this.element.outerHeight()},this.elementOffset=this.element.offset(),t={x:e.pageX,y:e.pageY},i=this._normValueFromMouse(t),s=this._valueMax()-this._valueMin()+1,this.handles.each(function(e){var t=Math.abs(i-o.values(e));(t<s||s===t&&(e===o._lastChangedValue||o.values(e)===u.min))&&(s=t,a=r(this),n=e)}),!1!==this._start(e,n)&&(this._mouseSliding=!0,this._handleIndex=n,a.addClass("ui-state-active").focus(),h=a.offset(),l=!r(e.target).parents().addBack().is(".ui-slider-handle"),this._clickOffset=l?{left:0,top:0}:{left:e.pageX-h.left-a.width()/2,top:e.pageY-h.top-a.height()/2-(parseInt(a.css("borderTopWidth"),10)||0)-(parseInt(a.css("borderBottomWidth"),10)||0)+(parseInt(a.css("marginTop"),10)||0)},this.handles.hasClass("ui-state-hover")||this._slide(e,n,i),this._animateOff=!0))},_mouseStart:function(){return!0},_mouseDrag:function(e){var t={x:e.pageX,y:e.pageY},i=this._normValueFromMouse(t);return this._slide(e,this._handleIndex,i),!1},_mouseStop:function(e){return this.handles.removeClass("ui-state-active"),this._mouseSliding=!1,this._stop(e,this._handleIndex),this._change(e,this._handleIndex),this._handleIndex=null,this._clickOffset=null,this._animateOff=!1},_detectOrientation:function(){this.orientation="vertical"===this.options.orientation?"vertical":"horizontal"},_normValueFromMouse:function(e){var t,i,s,a;return 1<(i=("horizontal"===this.orientation?(t=this.elementSize.width,e.x-this.elementOffset.left-(this._clickOffset?this._clickOffset.left:0)):(t=this.elementSize.height,e.y-this.elementOffset.top-(this._clickOffset?this._clickOffset.top:0)))/t)&&(i=1),i<0&&(i=0),"vertical"===this.orientation&&(i=1-i),s=this._valueMax()-this._valueMin(),a=this._valueMin()+i*s,this._trimAlignValue(a)},_start:function(e,t){var i={handle:this.handles[t],value:this.value()};return this.options.values&&this.options.values.length&&(i.value=this.values(t),i.values=this.values()),this._trigger("start",e,i)},_slide:function(e,t,i){var s,a,n;this.options.values&&this.options.values.length?(s=this.values(t?0:1),2===this.options.values.length&&!0===this.options.range&&(0===t&&s<i||1===t&&i<s)&&(i=s),i!==this.values(t)&&((a=this.values())[t]=i,n=this._trigger("slide",e,{handle:this.handles[t],value:i,values:a}),s=this.values(t?0:1),!1!==n&&this.values(t,i))):i!==this.value()&&!1!==(n=this._trigger("slide",e,{handle:this.handles[t],value:i}))&&this.value(i)},_stop:function(e,t){var i={handle:this.handles[t],value:this.value()};this.options.values&&this.options.values.length&&(i.value=this.values(t),i.values=this.values()),this._trigger("stop",e,i)},_change:function(e,t){if(!this._keySliding&&!this._mouseSliding){var i={handle:this.handles[t],value:this.value()};this.options.values&&this.options.values.length&&(i.value=this.values(t),i.values=this.values()),this._lastChangedValue=t,this._trigger("change",e,i)}},value:function(e){return arguments.length?(this.options.value=this._trimAlignValue(e),this._refreshValue(),void this._change(null,0)):this._value()},values:function(e,t){var i,s,a;if(1<arguments.length)return this.options.values[e]=this._trimAlignValue(t),this._refreshValue(),void this._change(null,e);if(!arguments.length)return this._values();if(!r.isArray(e))return this.options.values&&this.options.values.length?this._values(e):this.value();for(i=this.options.values,s=e,a=0;a<i.length;a+=1)i[a]=this._trimAlignValue(s[a]),this._change(null,a);this._refreshValue()},_setOption:function(e,t){var i,s=0;switch("range"===e&&!0===this.options.range&&("min"===t?(this.options.value=this._values(0),this.options.values=null):"max"===t&&(this.options.value=this._values(this.options.values.length-1),this.options.values=null)),r.isArray(this.options.values)&&(s=this.options.values.length),"disabled"===e&&this.element.toggleClass("ui-state-disabled",!!t),this._super(e,t),e){case"orientation":this._detectOrientation(),this.element.removeClass("ui-slider-horizontal ui-slider-vertical").addClass("ui-slider-"+this.orientation),this._refreshValue(),this.handles.css("horizontal"===t?"bottom":"left","");break;case"value":this._animateOff=!0,this._refreshValue(),this._change(null,0),this._animateOff=!1;break;case"values":for(this._animateOff=!0,this._refreshValue(),i=0;i<s;i+=1)this._change(null,i);this._animateOff=!1;break;case"step":case"min":case"max":this._animateOff=!0,this._calculateNewMax(),this._refreshValue(),this._animateOff=!1;break;case"range":this._animateOff=!0,this._refresh(),this._animateOff=!1}},_value:function(){var e=this.options.value;return e=this._trimAlignValue(e)},_values:function(e){var t,i,s;if(arguments.length)return t=this.options.values[e],t=this._trimAlignValue(t);if(this.options.values&&this.options.values.length){for(i=this.options.values.slice(),s=0;s<i.length;s+=1)i[s]=this._trimAlignValue(i[s]);return i}return[]},_trimAlignValue:function(e){if(e<=this._valueMin())return this._valueMin();if(e>=this._valueMax())return this._valueMax();var t=0<this.options.step?this.options.step:1,i=(e-this._valueMin())%t,s=e-i;return 2*Math.abs(i)>=t&&(s+=0<i?t:-t),parseFloat(s.toFixed(5))},_calculateNewMax:function(){var e=this.options.max,t=this._valueMin(),i=this.options.step;e=Math.floor(+(e-t).toFixed(this._precision())/i)*i+t,this.max=parseFloat(e.toFixed(this._precision()))},_precision:function(){var e=this._precisionOf(this.options.step);return null!==this.options.min&&(e=Math.max(e,this._precisionOf(this.options.min))),e},_precisionOf:function(e){var t=e.toString(),i=t.indexOf(".");return-1===i?0:t.length-i-1},_valueMin:function(){return this.options.min},_valueMax:function(){return this.max},_refreshValue:function(){var t,i,e,s,a,n=this.options.range,h=this.options,l=this,o=!this._animateOff&&h.animate,u={};this.options.values&&this.options.values.length?this.handles.each(function(e){i=(l.values(e)-l._valueMin())/(l._valueMax()-l._valueMin())*100,u["horizontal"===l.orientation?"left":"bottom"]=i+"%",r(this).stop(1,1)[o?"animate":"css"](u,h.animate),!0===l.options.range&&("horizontal"===l.orientation?(0===e&&l.range.stop(1,1)[o?"animate":"css"]({left:i+"%"},h.animate),1===e&&l.range[o?"animate":"css"]({width:i-t+"%"},{queue:!1,duration:h.animate})):(0===e&&l.range.stop(1,1)[o?"animate":"css"]({bottom:i+"%"},h.animate),1===e&&l.range[o?"animate":"css"]({height:i-t+"%"},{queue:!1,duration:h.animate}))),t=i}):(e=this.value(),s=this._valueMin(),a=this._valueMax(),i=a!==s?(e-s)/(a-s)*100:0,u["horizontal"===this.orientation?"left":"bottom"]=i+"%",this.handle.stop(1,1)[o?"animate":"css"](u,h.animate),"min"===n&&"horizontal"===this.orientation&&this.range.stop(1,1)[o?"animate":"css"]({width:i+"%"},h.animate),"max"===n&&"horizontal"===this.orientation&&this.range[o?"animate":"css"]({width:100-i+"%"},{queue:!1,duration:h.animate}),"min"===n&&"vertical"===this.orientation&&this.range.stop(1,1)[o?"animate":"css"]({height:i+"%"},h.animate),"max"===n&&"vertical"===this.orientation&&this.range[o?"animate":"css"]({height:100-i+"%"},{queue:!1,duration:h.animate}))},_handleEvents:{keydown:function(e){var t,i,s,a=r(e.target).data("ui-slider-handle-index");switch(e.keyCode){case r.ui.keyCode.HOME:case r.ui.keyCode.END:case r.ui.keyCode.PAGE_UP:case r.ui.keyCode.PAGE_DOWN:case r.ui.keyCode.UP:case r.ui.keyCode.RIGHT:case r.ui.keyCode.DOWN:case r.ui.keyCode.LEFT:if(e.preventDefault(),!this._keySliding&&(this._keySliding=!0,r(e.target).addClass("ui-state-active"),!1===this._start(e,a)))return}switch(s=this.options.step,t=i=this.options.values&&this.options.values.length?this.values(a):this.value(),e.keyCode){case r.ui.keyCode.HOME:i=this._valueMin();break;case r.ui.keyCode.END:i=this._valueMax();break;case r.ui.keyCode.PAGE_UP:i=this._trimAlignValue(t+(this._valueMax()-this._valueMin())/this.numPages);break;case r.ui.keyCode.PAGE_DOWN:i=this._trimAlignValue(t-(this._valueMax()-this._valueMin())/this.numPages);break;case r.ui.keyCode.UP:case r.ui.keyCode.RIGHT:if(t===this._valueMax())return;i=this._trimAlignValue(t+s);break;case r.ui.keyCode.DOWN:case r.ui.keyCode.LEFT:if(t===this._valueMin())return;i=this._trimAlignValue(t-s)}this._slide(e,a,i)},keyup:function(e){var t=r(e.target).data("ui-slider-handle-index");this._keySliding&&(this._keySliding=!1,this._stop(e,t),this._change(e,t),r(e.target).removeClass("ui-state-active"))}}})});

// jQuery UI Menu
!function(e){"function"==typeof define&&define.amd?define(["jquery","./core","./widget","./position"],e):e(jQuery)}(function(o){return o.widget("ui.menu",{version:"1.11.4",defaultElement:"<ul>",delay:300,options:{icons:{submenu:"ui-icon-carat-1-e"},items:"> *",menus:"ul",position:{my:"left-1 top",at:"right top"},role:"menu",blur:null,focus:null,select:null},_create:function(){this.activeMenu=this.element,this.mouseHandled=!1,this.element.uniqueId().addClass("ui-menu ui-widget ui-widget-content").toggleClass("ui-menu-icons",!!this.element.find(".ui-icon").length).attr({role:this.options.role,tabIndex:0}),this.options.disabled&&this.element.addClass("ui-state-disabled").attr("aria-disabled","true"),this._on({"mousedown .ui-menu-item":function(e){e.preventDefault()},"click .ui-menu-item":function(e){var t=o(e.target);!this.mouseHandled&&t.not(".ui-state-disabled").length&&(this.select(e),e.isPropagationStopped()||(this.mouseHandled=!0),t.has(".ui-menu").length?this.expand(e):!this.element.is(":focus")&&o(this.document[0].activeElement).closest(".ui-menu").length&&(this.element.trigger("focus",[!0]),this.active&&1===this.active.parents(".ui-menu").length&&clearTimeout(this.timer)))},"mouseenter .ui-menu-item":function(e){if(!this.previousFilter){var t=o(e.currentTarget);t.siblings(".ui-state-active").removeClass("ui-state-active"),this.focus(e,t)}},mouseleave:"collapseAll","mouseleave .ui-menu":"collapseAll",focus:function(e,t){var i=this.active||this.element.find(this.options.items).eq(0);t||this.focus(e,i)},blur:function(e){this._delay(function(){o.contains(this.element[0],this.document[0].activeElement)||this.collapseAll(e)})},keydown:"_keydown"}),this.refresh(),this._on(this.document,{click:function(e){this._closeOnDocumentClick(e)&&this.collapseAll(e),this.mouseHandled=!1}})},_destroy:function(){this.element.removeAttr("aria-activedescendant").find(".ui-menu").addBack().removeClass("ui-menu ui-widget ui-widget-content ui-menu-icons ui-front").removeAttr("role").removeAttr("tabIndex").removeAttr("aria-labelledby").removeAttr("aria-expanded").removeAttr("aria-hidden").removeAttr("aria-disabled").removeUniqueId().show(),this.element.find(".ui-menu-item").removeClass("ui-menu-item").removeAttr("role").removeAttr("aria-disabled").removeUniqueId().removeClass("ui-state-hover").removeAttr("tabIndex").removeAttr("role").removeAttr("aria-haspopup").children().each(function(){var e=o(this);e.data("ui-menu-submenu-carat")&&e.remove()}),this.element.find(".ui-menu-divider").removeClass("ui-menu-divider ui-widget-content")},_keydown:function(e){var t,i,s,n,a=!0;switch(e.keyCode){case o.ui.keyCode.PAGE_UP:this.previousPage(e);break;case o.ui.keyCode.PAGE_DOWN:this.nextPage(e);break;case o.ui.keyCode.HOME:this._move("first","first",e);break;case o.ui.keyCode.END:this._move("last","last",e);break;case o.ui.keyCode.UP:this.previous(e);break;case o.ui.keyCode.DOWN:this.next(e);break;case o.ui.keyCode.LEFT:this.collapse(e);break;case o.ui.keyCode.RIGHT:this.active&&!this.active.is(".ui-state-disabled")&&this.expand(e);break;case o.ui.keyCode.ENTER:case o.ui.keyCode.SPACE:this._activate(e);break;case o.ui.keyCode.ESCAPE:this.collapse(e);break;default:a=!1,i=this.previousFilter||"",s=String.fromCharCode(e.keyCode),n=!1,clearTimeout(this.filterTimer),s===i?n=!0:s=i+s,t=this._filterMenuItems(s),(t=n&&-1!==t.index(this.active.next())?this.active.nextAll(".ui-menu-item"):t).length||(s=String.fromCharCode(e.keyCode),t=this._filterMenuItems(s)),t.length?(this.focus(e,t),this.previousFilter=s,this.filterTimer=this._delay(function(){delete this.previousFilter},1e3)):delete this.previousFilter}a&&e.preventDefault()},_activate:function(e){this.active.is(".ui-state-disabled")||(this.active.is("[aria-haspopup='true']")?this.expand(e):this.select(e))},refresh:function(){var e,t=this,s=this.options.icons.submenu,i=this.element.find(this.options.menus);this.element.toggleClass("ui-menu-icons",!!this.element.find(".ui-icon").length),i.filter(":not(.ui-menu)").addClass("ui-menu ui-widget ui-widget-content ui-front").hide().attr({role:this.options.role,"aria-hidden":"true","aria-expanded":"false"}).each(function(){var e=o(this),t=e.parent(),i=o("<span>").addClass("ui-menu-icon ui-icon "+s).data("ui-menu-submenu-carat",!0);t.attr("aria-haspopup","true").prepend(i),e.attr("aria-labelledby",t.attr("id"))}),(e=i.add(this.element).find(this.options.items)).not(".ui-menu-item").each(function(){var e=o(this);t._isDivider(e)&&e.addClass("ui-widget-content ui-menu-divider")}),e.not(".ui-menu-item, .ui-menu-divider").addClass("ui-menu-item").uniqueId().attr({tabIndex:-1,role:this._itemRole()}),e.filter(".ui-state-disabled").attr("aria-disabled","true"),this.active&&!o.contains(this.element[0],this.active[0])&&this.blur()},_itemRole:function(){return{menu:"menuitem",listbox:"option"}[this.options.role]},_setOption:function(e,t){"icons"===e&&this.element.find(".ui-menu-icon").removeClass(this.options.icons.submenu).addClass(t.submenu),"disabled"===e&&this.element.toggleClass("ui-state-disabled",!!t).attr("aria-disabled",t),this._super(e,t)},focus:function(e,t){var i,s;this.blur(e,e&&"focus"===e.type),this._scrollIntoView(t),this.active=t.first(),s=this.active.addClass("ui-state-focus").removeClass("ui-state-active"),this.options.role&&this.element.attr("aria-activedescendant",s.attr("id")),this.active.parent().closest(".ui-menu-item").addClass("ui-state-active"),e&&"keydown"===e.type?this._close():this.timer=this._delay(function(){this._close()},this.delay),(i=t.children(".ui-menu")).length&&e&&/^mouse/.test(e.type)&&this._startOpening(i),this.activeMenu=t.parent(),this._trigger("focus",e,{item:t})},_scrollIntoView:function(e){var t,i,s,n,a,u;this._hasScroll()&&(t=parseFloat(o.css(this.activeMenu[0],"borderTopWidth"))||0,i=parseFloat(o.css(this.activeMenu[0],"paddingTop"))||0,s=e.offset().top-this.activeMenu.offset().top-t-i,n=this.activeMenu.scrollTop(),a=this.activeMenu.height(),u=e.outerHeight(),s<0?this.activeMenu.scrollTop(n+s):a<s+u&&this.activeMenu.scrollTop(n+s-a+u))},blur:function(e,t){t||clearTimeout(this.timer),this.active&&(this.active.removeClass("ui-state-focus"),this.active=null,this._trigger("blur",e,{item:this.active}))},_startOpening:function(e){clearTimeout(this.timer),"true"===e.attr("aria-hidden")&&(this.timer=this._delay(function(){this._close(),this._open(e)},this.delay))},_open:function(e){var t=o.extend({of:this.active},this.options.position);clearTimeout(this.timer),this.element.find(".ui-menu").not(e.parents(".ui-menu")).hide().attr("aria-hidden","true"),e.show().removeAttr("aria-hidden").attr("aria-expanded","true").position(t)},collapseAll:function(t,i){clearTimeout(this.timer),this.timer=this._delay(function(){var e=i?this.element:o(t&&t.target).closest(this.element.find(".ui-menu"));e.length||(e=this.element),this._close(e),this.blur(t),this.activeMenu=e},this.delay)},_close:function(e){(e=e||(this.active?this.active.parent():this.element)).find(".ui-menu").hide().attr("aria-hidden","true").attr("aria-expanded","false").end().find(".ui-state-active").not(".ui-state-focus").removeClass("ui-state-active")},_closeOnDocumentClick:function(e){return!o(e.target).closest(".ui-menu").length},_isDivider:function(e){return!/[^\-\u2014\u2013\s]/.test(e.text())},collapse:function(e){var t=this.active&&this.active.parent().closest(".ui-menu-item",this.element);t&&t.length&&(this._close(),this.focus(e,t))},expand:function(e){var t=this.active&&this.active.children(".ui-menu ").find(this.options.items).first();t&&t.length&&(this._open(t.parent()),this._delay(function(){this.focus(e,t)}))},next:function(e){this._move("next","first",e)},previous:function(e){this._move("prev","last",e)},isFirstItem:function(){return this.active&&!this.active.prevAll(".ui-menu-item").length},isLastItem:function(){return this.active&&!this.active.nextAll(".ui-menu-item").length},_move:function(e,t,i){var s;this.active&&(s="first"===e||"last"===e?this.active["first"===e?"prevAll":"nextAll"](".ui-menu-item").eq(-1):this.active[e+"All"](".ui-menu-item").eq(0)),s&&s.length&&this.active||(s=this.activeMenu.find(this.options.items)[t]()),this.focus(i,s)},nextPage:function(e){var t,i,s;this.active?this.isLastItem()||(this._hasScroll()?(i=this.active.offset().top,s=this.element.height(),this.active.nextAll(".ui-menu-item").each(function(){return(t=o(this)).offset().top-i-s<0}),this.focus(e,t)):this.focus(e,this.activeMenu.find(this.options.items)[this.active?"last":"first"]())):this.next(e)},previousPage:function(e){var t,i,s;this.active?this.isFirstItem()||(this._hasScroll()?(i=this.active.offset().top,s=this.element.height(),this.active.prevAll(".ui-menu-item").each(function(){return 0<(t=o(this)).offset().top-i+s}),this.focus(e,t)):this.focus(e,this.activeMenu.find(this.options.items).first())):this.next(e)},_hasScroll:function(){return this.element.outerHeight()<this.element.prop("scrollHeight")},select:function(e){this.active=this.active||o(e.target).closest(".ui-menu-item");var t={item:this.active};this.active.has(".ui-menu").length||this.collapseAll(e,!0),this._trigger("select",e,t)},_filterMenuItems:function(e){var t=e.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g,"\\$&"),i=new RegExp("^"+t,"i");return this.activeMenu.find(this.options.items).filter(".ui-menu-item").filter(function(){return i.test(o.trim(o(this).text()))})}})});

// jQuery UI Autocomplete
!function(e){"function"==typeof define&&define.amd?define(["jquery","./core","./widget","./position","./menu"],e):e(jQuery)}(function(u){return u.widget("ui.autocomplete",{version:"1.11.4",defaultElement:"<input>",options:{appendTo:null,autoFocus:!1,delay:300,minLength:1,position:{my:"left top",at:"left bottom",collision:"none"},source:null,change:null,close:null,focus:null,open:null,response:null,search:null,select:null},requestIndex:0,pending:0,_create:function(){var i,s,n,e=this.element[0].nodeName.toLowerCase(),t="textarea"===e,o="input"===e;this.isMultiLine=t||!o&&this.element.prop("isContentEditable"),this.valueMethod=this.element[t||o?"val":"text"],this.isNewMenu=!0,this.element.addClass("ui-autocomplete-input").attr("autocomplete","off"),this._on(this.element,{keydown:function(e){if(this.element.prop("readOnly"))s=n=i=!0;else{s=n=i=!1;var t=u.ui.keyCode;switch(e.keyCode){case t.PAGE_UP:i=!0,this._move("previousPage",e);break;case t.PAGE_DOWN:i=!0,this._move("nextPage",e);break;case t.UP:i=!0,this._keyEvent("previous",e);break;case t.DOWN:i=!0,this._keyEvent("next",e);break;case t.ENTER:this.menu.active&&(i=!0,e.preventDefault(),this.menu.select(e));break;case t.TAB:this.menu.active&&this.menu.select(e);break;case t.ESCAPE:this.menu.element.is(":visible")&&(this.isMultiLine||this._value(this.term),this.close(e),e.preventDefault());break;default:s=!0,this._searchTimeout(e)}}},keypress:function(e){if(i)return i=!1,void(this.isMultiLine&&!this.menu.element.is(":visible")||e.preventDefault());if(!s){var t=u.ui.keyCode;switch(e.keyCode){case t.PAGE_UP:this._move("previousPage",e);break;case t.PAGE_DOWN:this._move("nextPage",e);break;case t.UP:this._keyEvent("previous",e);break;case t.DOWN:this._keyEvent("next",e)}}},input:function(e){if(n)return n=!1,void e.preventDefault();this._searchTimeout(e)},focus:function(){this.selectedItem=null,this.previous=this._value()},blur:function(e){this.cancelBlur?delete this.cancelBlur:(clearTimeout(this.searching),this.close(e),this._change(e))}}),this._initSource(),this.menu=u("<ul>").addClass("ui-autocomplete ui-front").appendTo(this._appendTo()).menu({role:null}).hide().menu("instance"),this._on(this.menu.element,{mousedown:function(e){e.preventDefault(),this.cancelBlur=!0,this._delay(function(){delete this.cancelBlur});var i=this.menu.element[0];u(e.target).closest(".ui-menu-item").length||this._delay(function(){var t=this;this.document.one("mousedown",function(e){e.target===t.element[0]||e.target===i||u.contains(i,e.target)||t.close()})})},menufocus:function(e,t){var i,s;if(this.isNewMenu&&(this.isNewMenu=!1,e.originalEvent&&/^mouse/.test(e.originalEvent.type)))return this.menu.blur(),void this.document.one("mousemove",function(){u(e.target).trigger(e.originalEvent)});s=t.item.data("ui-autocomplete-item"),!1!==this._trigger("focus",e,{item:s})&&e.originalEvent&&/^key/.test(e.originalEvent.type)&&this._value(s.value),(i=t.item.attr("aria-label")||s.value)&&u.trim(i).length&&(this.liveRegion.children().hide(),u("<div>").text(i).appendTo(this.liveRegion))},menuselect:function(e,t){var i=t.item.data("ui-autocomplete-item"),s=this.previous;this.element[0]!==this.document[0].activeElement&&(this.element.focus(),this.previous=s,this._delay(function(){this.previous=s,this.selectedItem=i})),!1!==this._trigger("select",e,{item:i})&&this._value(i.value),this.term=this._value(),this.close(e),this.selectedItem=i}}),this.liveRegion=u("<span>",{role:"status","aria-live":"assertive","aria-relevant":"additions"}).addClass("ui-helper-hidden-accessible").appendTo(this.document[0].body),this._on(this.window,{beforeunload:function(){this.element.removeAttr("autocomplete")}})},_destroy:function(){clearTimeout(this.searching),this.element.removeClass("ui-autocomplete-input").removeAttr("autocomplete"),this.menu.element.remove(),this.liveRegion.remove()},_setOption:function(e,t){this._super(e,t),"source"===e&&this._initSource(),"appendTo"===e&&this.menu.element.appendTo(this._appendTo()),"disabled"===e&&t&&this.xhr&&this.xhr.abort()},_appendTo:function(){var e=this.options.appendTo;return(e=e&&(e.jquery||e.nodeType?u(e):this.document.find(e).eq(0)))&&e[0]||(e=this.element.closest(".ui-front")),e.length||(e=this.document[0].body),e},_initSource:function(){var i,s,n=this;u.isArray(this.options.source)?(i=this.options.source,this.source=function(e,t){t(u.ui.autocomplete.filter(i,e.term))}):"string"==typeof this.options.source?(s=this.options.source,this.source=function(e,t){n.xhr&&n.xhr.abort(),n.xhr=u.ajax({url:s,data:e,dataType:"json",success:function(e){t(e)},error:function(){t([])}})}):this.source=this.options.source},_searchTimeout:function(s){clearTimeout(this.searching),this.searching=this._delay(function(){var e=this.term===this._value(),t=this.menu.element.is(":visible"),i=s.altKey||s.ctrlKey||s.metaKey||s.shiftKey;e&&(!e||t||i)||(this.selectedItem=null,this.search(null,s))},this.options.delay)},search:function(e,t){return e=null!=e?e:this._value(),this.term=this._value(),e.length<this.options.minLength?this.close(t):!1!==this._trigger("search",t)?this._search(e):void 0},_search:function(e){this.pending++,this.element.addClass("ui-autocomplete-loading"),this.cancelSearch=!1,this.source({term:e},this._response())},_response:function(){var t=++this.requestIndex;return u.proxy(function(e){t===this.requestIndex&&this.__response(e),this.pending--,this.pending||this.element.removeClass("ui-autocomplete-loading")},this)},__response:function(e){e=e&&this._normalize(e),this._trigger("response",null,{content:e}),!this.options.disabled&&e&&e.length&&!this.cancelSearch?(this._suggest(e),this._trigger("open")):this._close()},close:function(e){this.cancelSearch=!0,this._close(e)},_close:function(e){this.menu.element.is(":visible")&&(this.menu.element.hide(),this.menu.blur(),this.isNewMenu=!0,this._trigger("close",e))},_change:function(e){this.previous!==this._value()&&this._trigger("change",e,{item:this.selectedItem})},_normalize:function(e){return e.length&&e[0].label&&e[0].value?e:u.map(e,function(e){return"string"==typeof e?{label:e,value:e}:u.extend({},e,{label:e.label||e.value,value:e.value||e.label})})},_suggest:function(e){var t=this.menu.element.empty();this._renderMenu(t,e),this.isNewMenu=!0,this.menu.refresh(),t.show(),this._resizeMenu(),t.position(u.extend({of:this.element},this.options.position)),this.options.autoFocus&&this.menu.next()},_resizeMenu:function(){var e=this.menu.element;e.outerWidth(Math.max(e.width("").outerWidth()+1,this.element.outerWidth()))},_renderMenu:function(i,e){var s=this;u.each(e,function(e,t){s._renderItemData(i,t)})},_renderItemData:function(e,t){return this._renderItem(e,t).data("ui-autocomplete-item",t)},_renderItem:function(e,t){return u("<li>").text(t.label).appendTo(e)},_move:function(e,t){if(this.menu.element.is(":visible"))return this.menu.isFirstItem()&&/^previous/.test(e)||this.menu.isLastItem()&&/^next/.test(e)?(this.isMultiLine||this._value(this.term),void this.menu.blur()):void this.menu[e](t);this.search(null,t)},widget:function(){return this.menu.element},_value:function(){return this.valueMethod.apply(this.element,arguments)},_keyEvent:function(e,t){this.isMultiLine&&!this.menu.element.is(":visible")||(this._move(e,t),t.preventDefault())}}),u.extend(u.ui.autocomplete,{escapeRegex:function(e){return e.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g,"\\$&")},filter:function(e,t){var i=new RegExp(u.ui.autocomplete.escapeRegex(t),"i");return u.grep(e,function(e){return i.test(e.label||e.value||e)})}}),u.widget("ui.autocomplete",u.ui.autocomplete,{options:{messages:{noResults:"No search results.",results:function(e){return e+(1<e?" results are":" result is")+" available, use up and down arrow keys to navigate."}}},__response:function(e){var t;this._superApply(arguments),this.options.disabled||this.cancelSearch||(t=e&&e.length?this.options.messages.results(e.length):this.options.messages.noResults,this.liveRegion.children().hide(),u("<div>").text(t).appendTo(this.liveRegion))}}),u.ui.autocomplete});

// jQuery UI Draggable
!function(t){"function"==typeof define&&define.amd?define(["jquery","./core","./mouse","./widget"],t):t(jQuery)}(function(P){return P.widget("ui.draggable",P.ui.mouse,{version:"1.11.4",widgetEventPrefix:"drag",options:{addClasses:!0,appendTo:"parent",axis:!1,connectToSortable:!1,containment:!1,cursor:"auto",cursorAt:!1,grid:!1,handle:!1,helper:"original",iframeFix:!1,opacity:!1,refreshPositions:!1,revert:!1,revertDuration:500,scope:"default",scroll:!0,scrollSensitivity:20,scrollSpeed:20,snap:!1,snapMode:"both",snapTolerance:20,stack:!1,zIndex:!1,drag:null,start:null,stop:null},_create:function(){"original"===this.options.helper&&this._setPositionRelative(),this.options.addClasses&&this.element.addClass("ui-draggable"),this.options.disabled&&this.element.addClass("ui-draggable-disabled"),this._setHandleClassName(),this._mouseInit()},_setOption:function(t,e){this._super(t,e),"handle"===t&&(this._removeHandleClassName(),this._setHandleClassName())},_destroy:function(){(this.helper||this.element).is(".ui-draggable-dragging")?this.destroyOnClear=!0:(this.element.removeClass("ui-draggable ui-draggable-dragging ui-draggable-disabled"),this._removeHandleClassName(),this._mouseDestroy())},_mouseCapture:function(t){var e=this.options;return this._blurActiveElement(t),!(this.helper||e.disabled||0<P(t.target).closest(".ui-resizable-handle").length)&&(this.handle=this._getHandle(t),!!this.handle&&(this._blockFrames(!0===e.iframeFix?"iframe":e.iframeFix),!0))},_blockFrames:function(t){this.iframeBlocks=this.document.find(t).map(function(){var t=P(this);return P("<div>").css("position","absolute").appendTo(t.parent()).outerWidth(t.outerWidth()).outerHeight(t.outerHeight()).offset(t.offset())[0]})},_unblockFrames:function(){this.iframeBlocks&&(this.iframeBlocks.remove(),delete this.iframeBlocks)},_blurActiveElement:function(t){var e=this.document[0];if(this.handleElement.is(t.target))try{e.activeElement&&"body"!==e.activeElement.nodeName.toLowerCase()&&P(e.activeElement).blur()}catch(t){}},_mouseStart:function(t){var e=this.options;return this.helper=this._createHelper(t),this.helper.addClass("ui-draggable-dragging"),this._cacheHelperProportions(),P.ui.ddmanager&&(P.ui.ddmanager.current=this),this._cacheMargins(),this.cssPosition=this.helper.css("position"),this.scrollParent=this.helper.scrollParent(!0),this.offsetParent=this.helper.offsetParent(),this.hasFixedAncestor=0<this.helper.parents().filter(function(){return"fixed"===P(this).css("position")}).length,this.positionAbs=this.element.offset(),this._refreshOffsets(t),this.originalPosition=this.position=this._generatePosition(t,!1),this.originalPageX=t.pageX,this.originalPageY=t.pageY,e.cursorAt&&this._adjustOffsetFromHelper(e.cursorAt),this._setContainment(),!1===this._trigger("start",t)?(this._clear(),!1):(this._cacheHelperProportions(),P.ui.ddmanager&&!e.dropBehaviour&&P.ui.ddmanager.prepareOffsets(this,t),this._normalizeRightBottom(),this._mouseDrag(t,!0),P.ui.ddmanager&&P.ui.ddmanager.dragStart(this,t),!0)},_refreshOffsets:function(t){this.offset={top:this.positionAbs.top-this.margins.top,left:this.positionAbs.left-this.margins.left,scroll:!1,parent:this._getParentOffset(),relative:this._getRelativeOffset()},this.offset.click={left:t.pageX-this.offset.left,top:t.pageY-this.offset.top}},_mouseDrag:function(t,e){if(this.hasFixedAncestor&&(this.offset.parent=this._getParentOffset()),this.position=this._generatePosition(t,!0),this.positionAbs=this._convertPositionTo("absolute"),!e){var s=this._uiHash();if(!1===this._trigger("drag",t,s))return this._mouseUp({}),!1;this.position=s.position}return this.helper[0].style.left=this.position.left+"px",this.helper[0].style.top=this.position.top+"px",P.ui.ddmanager&&P.ui.ddmanager.drag(this,t),!1},_mouseStop:function(t){var e=this,s=!1;return P.ui.ddmanager&&!this.options.dropBehaviour&&(s=P.ui.ddmanager.drop(this,t)),this.dropped&&(s=this.dropped,this.dropped=!1),"invalid"===this.options.revert&&!s||"valid"===this.options.revert&&s||!0===this.options.revert||P.isFunction(this.options.revert)&&this.options.revert.call(this.element,s)?P(this.helper).animate(this.originalPosition,parseInt(this.options.revertDuration,10),function(){!1!==e._trigger("stop",t)&&e._clear()}):!1!==this._trigger("stop",t)&&this._clear(),!1},_mouseUp:function(t){return this._unblockFrames(),P.ui.ddmanager&&P.ui.ddmanager.dragStop(this,t),this.handleElement.is(t.target)&&this.element.focus(),P.ui.mouse.prototype._mouseUp.call(this,t)},cancel:function(){return this.helper.is(".ui-draggable-dragging")?this._mouseUp({}):this._clear(),this},_getHandle:function(t){return!this.options.handle||!!P(t.target).closest(this.element.find(this.options.handle)).length},_setHandleClassName:function(){this.handleElement=this.options.handle?this.element.find(this.options.handle):this.element,this.handleElement.addClass("ui-draggable-handle")},_removeHandleClassName:function(){this.handleElement.removeClass("ui-draggable-handle")},_createHelper:function(t){var e=this.options,s=P.isFunction(e.helper),i=s?P(e.helper.apply(this.element[0],[t])):"clone"===e.helper?this.element.clone().removeAttr("id"):this.element;return i.parents("body").length||i.appendTo("parent"===e.appendTo?this.element[0].parentNode:e.appendTo),s&&i[0]===this.element[0]&&this._setPositionRelative(),i[0]===this.element[0]||/(fixed|absolute)/.test(i.css("position"))||i.css("position","absolute"),i},_setPositionRelative:function(){/^(?:r|a|f)/.test(this.element.css("position"))||(this.element[0].style.position="relative")},_adjustOffsetFromHelper:function(t){"string"==typeof t&&(t=t.split(" ")),P.isArray(t)&&(t={left:+t[0],top:+t[1]||0}),"left"in t&&(this.offset.click.left=t.left+this.margins.left),"right"in t&&(this.offset.click.left=this.helperProportions.width-t.right+this.margins.left),"top"in t&&(this.offset.click.top=t.top+this.margins.top),"bottom"in t&&(this.offset.click.top=this.helperProportions.height-t.bottom+this.margins.top)},_isRootNode:function(t){return/(html|body)/i.test(t.tagName)||t===this.document[0]},_getParentOffset:function(){var t=this.offsetParent.offset(),e=this.document[0];return"absolute"===this.cssPosition&&this.scrollParent[0]!==e&&P.contains(this.scrollParent[0],this.offsetParent[0])&&(t.left+=this.scrollParent.scrollLeft(),t.top+=this.scrollParent.scrollTop()),this._isRootNode(this.offsetParent[0])&&(t={top:0,left:0}),{top:t.top+(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:t.left+(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)}},_getRelativeOffset:function(){if("relative"!==this.cssPosition)return{top:0,left:0};var t=this.element.position(),e=this._isRootNode(this.scrollParent[0]);return{top:t.top-(parseInt(this.helper.css("top"),10)||0)+(e?0:this.scrollParent.scrollTop()),left:t.left-(parseInt(this.helper.css("left"),10)||0)+(e?0:this.scrollParent.scrollLeft())}},_cacheMargins:function(){this.margins={left:parseInt(this.element.css("marginLeft"),10)||0,top:parseInt(this.element.css("marginTop"),10)||0,right:parseInt(this.element.css("marginRight"),10)||0,bottom:parseInt(this.element.css("marginBottom"),10)||0}},_cacheHelperProportions:function(){this.helperProportions={width:this.helper.outerWidth(),height:this.helper.outerHeight()}},_setContainment:function(){var t,e,s,i=this.options,o=this.document[0];this.relativeContainer=null,i.containment?"window"!==i.containment?"document"!==i.containment?i.containment.constructor!==Array?("parent"===i.containment&&(i.containment=this.helper[0].parentNode),(s=(e=P(i.containment))[0])&&(t=/(scroll|auto)/.test(e.css("overflow")),this.containment=[(parseInt(e.css("borderLeftWidth"),10)||0)+(parseInt(e.css("paddingLeft"),10)||0),(parseInt(e.css("borderTopWidth"),10)||0)+(parseInt(e.css("paddingTop"),10)||0),(t?Math.max(s.scrollWidth,s.offsetWidth):s.offsetWidth)-(parseInt(e.css("borderRightWidth"),10)||0)-(parseInt(e.css("paddingRight"),10)||0)-this.helperProportions.width-this.margins.left-this.margins.right,(t?Math.max(s.scrollHeight,s.offsetHeight):s.offsetHeight)-(parseInt(e.css("borderBottomWidth"),10)||0)-(parseInt(e.css("paddingBottom"),10)||0)-this.helperProportions.height-this.margins.top-this.margins.bottom],this.relativeContainer=e)):this.containment=i.containment:this.containment=[0,0,P(o).width()-this.helperProportions.width-this.margins.left,(P(o).height()||o.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top]:this.containment=[P(window).scrollLeft()-this.offset.relative.left-this.offset.parent.left,P(window).scrollTop()-this.offset.relative.top-this.offset.parent.top,P(window).scrollLeft()+P(window).width()-this.helperProportions.width-this.margins.left,P(window).scrollTop()+(P(window).height()||o.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top]:this.containment=null},_convertPositionTo:function(t,e){e=e||this.position;var s="absolute"===t?1:-1,i=this._isRootNode(this.scrollParent[0]);return{top:e.top+this.offset.relative.top*s+this.offset.parent.top*s-("fixed"===this.cssPosition?-this.offset.scroll.top:i?0:this.offset.scroll.top)*s,left:e.left+this.offset.relative.left*s+this.offset.parent.left*s-("fixed"===this.cssPosition?-this.offset.scroll.left:i?0:this.offset.scroll.left)*s}},_generatePosition:function(t,e){var s,i,o,n,r=this.options,l=this._isRootNode(this.scrollParent[0]),a=t.pageX,h=t.pageY;return l&&this.offset.scroll||(this.offset.scroll={top:this.scrollParent.scrollTop(),left:this.scrollParent.scrollLeft()}),e&&(this.containment&&(s=this.relativeContainer?(i=this.relativeContainer.offset(),[this.containment[0]+i.left,this.containment[1]+i.top,this.containment[2]+i.left,this.containment[3]+i.top]):this.containment,t.pageX-this.offset.click.left<s[0]&&(a=s[0]+this.offset.click.left),t.pageY-this.offset.click.top<s[1]&&(h=s[1]+this.offset.click.top),t.pageX-this.offset.click.left>s[2]&&(a=s[2]+this.offset.click.left),t.pageY-this.offset.click.top>s[3]&&(h=s[3]+this.offset.click.top)),r.grid&&(o=r.grid[1]?this.originalPageY+Math.round((h-this.originalPageY)/r.grid[1])*r.grid[1]:this.originalPageY,h=s?o-this.offset.click.top>=s[1]||o-this.offset.click.top>s[3]?o:o-this.offset.click.top>=s[1]?o-r.grid[1]:o+r.grid[1]:o,n=r.grid[0]?this.originalPageX+Math.round((a-this.originalPageX)/r.grid[0])*r.grid[0]:this.originalPageX,a=s?n-this.offset.click.left>=s[0]||n-this.offset.click.left>s[2]?n:n-this.offset.click.left>=s[0]?n-r.grid[0]:n+r.grid[0]:n),"y"===r.axis&&(a=this.originalPageX),"x"===r.axis&&(h=this.originalPageY)),{top:h-this.offset.click.top-this.offset.relative.top-this.offset.parent.top+("fixed"===this.cssPosition?-this.offset.scroll.top:l?0:this.offset.scroll.top),left:a-this.offset.click.left-this.offset.relative.left-this.offset.parent.left+("fixed"===this.cssPosition?-this.offset.scroll.left:l?0:this.offset.scroll.left)}},_clear:function(){this.helper.removeClass("ui-draggable-dragging"),this.helper[0]===this.element[0]||this.cancelHelperRemoval||this.helper.remove(),this.helper=null,this.cancelHelperRemoval=!1,this.destroyOnClear&&this.destroy()},_normalizeRightBottom:function(){"y"!==this.options.axis&&"auto"!==this.helper.css("right")&&(this.helper.width(this.helper.width()),this.helper.css("right","auto")),"x"!==this.options.axis&&"auto"!==this.helper.css("bottom")&&(this.helper.height(this.helper.height()),this.helper.css("bottom","auto"))},_trigger:function(t,e,s){return s=s||this._uiHash(),P.ui.plugin.call(this,t,[e,s,this],!0),/^(drag|start|stop)/.test(t)&&(this.positionAbs=this._convertPositionTo("absolute"),s.offset=this.positionAbs),P.Widget.prototype._trigger.call(this,t,e,s)},plugins:{},_uiHash:function(){return{helper:this.helper,position:this.position,originalPosition:this.originalPosition,offset:this.positionAbs}}}),P.ui.plugin.add("draggable","connectToSortable",{start:function(e,t,s){var i=P.extend({},t,{item:s.element});s.sortables=[],P(s.options.connectToSortable).each(function(){var t=P(this).sortable("instance");t&&!t.options.disabled&&(s.sortables.push(t),t.refreshPositions(),t._trigger("activate",e,i))})},stop:function(e,t,s){var i=P.extend({},t,{item:s.element});s.cancelHelperRemoval=!1,P.each(s.sortables,function(){var t=this;t.isOver?(t.isOver=0,s.cancelHelperRemoval=!0,t.cancelHelperRemoval=!1,t._storedCSS={position:t.placeholder.css("position"),top:t.placeholder.css("top"),left:t.placeholder.css("left")},t._mouseStop(e),t.options.helper=t.options._helper):(t.cancelHelperRemoval=!0,t._trigger("deactivate",e,i))})},drag:function(s,i,o){P.each(o.sortables,function(){var t=!1,e=this;e.positionAbs=o.positionAbs,e.helperProportions=o.helperProportions,e.offset.click=o.offset.click,e._intersectsWith(e.containerCache)&&(t=!0,P.each(o.sortables,function(){return this.positionAbs=o.positionAbs,this.helperProportions=o.helperProportions,this.offset.click=o.offset.click,this!==e&&this._intersectsWith(this.containerCache)&&P.contains(e.element[0],this.element[0])&&(t=!1),t})),t?(e.isOver||(e.isOver=1,o._parent=i.helper.parent(),e.currentItem=i.helper.appendTo(e.element).data("ui-sortable-item",!0),e.options._helper=e.options.helper,e.options.helper=function(){return i.helper[0]},s.target=e.currentItem[0],e._mouseCapture(s,!0),e._mouseStart(s,!0,!0),e.offset.click.top=o.offset.click.top,e.offset.click.left=o.offset.click.left,e.offset.parent.left-=o.offset.parent.left-e.offset.parent.left,e.offset.parent.top-=o.offset.parent.top-e.offset.parent.top,o._trigger("toSortable",s),o.dropped=e.element,P.each(o.sortables,function(){this.refreshPositions()}),o.currentItem=o.element,e.fromOutside=o),e.currentItem&&(e._mouseDrag(s),i.position=e.position)):e.isOver&&(e.isOver=0,e.cancelHelperRemoval=!0,e.options._revert=e.options.revert,e.options.revert=!1,e._trigger("out",s,e._uiHash(e)),e._mouseStop(s,!0),e.options.revert=e.options._revert,e.options.helper=e.options._helper,e.placeholder&&e.placeholder.remove(),i.helper.appendTo(o._parent),o._refreshOffsets(s),i.position=o._generatePosition(s,!0),o._trigger("fromSortable",s),o.dropped=!1,P.each(o.sortables,function(){this.refreshPositions()}))})}}),P.ui.plugin.add("draggable","cursor",{start:function(t,e,s){var i=P("body"),o=s.options;i.css("cursor")&&(o._cursor=i.css("cursor")),i.css("cursor",o.cursor)},stop:function(t,e,s){var i=s.options;i._cursor&&P("body").css("cursor",i._cursor)}}),P.ui.plugin.add("draggable","opacity",{start:function(t,e,s){var i=P(e.helper),o=s.options;i.css("opacity")&&(o._opacity=i.css("opacity")),i.css("opacity",o.opacity)},stop:function(t,e,s){var i=s.options;i._opacity&&P(e.helper).css("opacity",i._opacity)}}),P.ui.plugin.add("draggable","scroll",{start:function(t,e,s){s.scrollParentNotHidden||(s.scrollParentNotHidden=s.helper.scrollParent(!1)),s.scrollParentNotHidden[0]!==s.document[0]&&"HTML"!==s.scrollParentNotHidden[0].tagName&&(s.overflowOffset=s.scrollParentNotHidden.offset())},drag:function(t,e,s){var i=s.options,o=!1,n=s.scrollParentNotHidden[0],r=s.document[0];n!==r&&"HTML"!==n.tagName?(i.axis&&"x"===i.axis||(s.overflowOffset.top+n.offsetHeight-t.pageY<i.scrollSensitivity?n.scrollTop=o=n.scrollTop+i.scrollSpeed:t.pageY-s.overflowOffset.top<i.scrollSensitivity&&(n.scrollTop=o=n.scrollTop-i.scrollSpeed)),i.axis&&"y"===i.axis||(s.overflowOffset.left+n.offsetWidth-t.pageX<i.scrollSensitivity?n.scrollLeft=o=n.scrollLeft+i.scrollSpeed:t.pageX-s.overflowOffset.left<i.scrollSensitivity&&(n.scrollLeft=o=n.scrollLeft-i.scrollSpeed))):(i.axis&&"x"===i.axis||(t.pageY-P(r).scrollTop()<i.scrollSensitivity?o=P(r).scrollTop(P(r).scrollTop()-i.scrollSpeed):P(window).height()-(t.pageY-P(r).scrollTop())<i.scrollSensitivity&&(o=P(r).scrollTop(P(r).scrollTop()+i.scrollSpeed))),i.axis&&"y"===i.axis||(t.pageX-P(r).scrollLeft()<i.scrollSensitivity?o=P(r).scrollLeft(P(r).scrollLeft()-i.scrollSpeed):P(window).width()-(t.pageX-P(r).scrollLeft())<i.scrollSensitivity&&(o=P(r).scrollLeft(P(r).scrollLeft()+i.scrollSpeed)))),!1!==o&&P.ui.ddmanager&&!i.dropBehaviour&&P.ui.ddmanager.prepareOffsets(s,t)}}),P.ui.plugin.add("draggable","snap",{start:function(t,e,s){var i=s.options;s.snapElements=[],P(i.snap.constructor!==String?i.snap.items||":data(ui-draggable)":i.snap).each(function(){var t=P(this),e=t.offset();this!==s.element[0]&&s.snapElements.push({item:this,width:t.outerWidth(),height:t.outerHeight(),top:e.top,left:e.left})})},drag:function(t,e,s){var i,o,n,r,l,a,h,p,c,f,d=s.options,g=d.snapTolerance,u=e.offset.left,m=u+s.helperProportions.width,v=e.offset.top,_=v+s.helperProportions.height;for(c=s.snapElements.length-1;0<=c;c--)a=(l=s.snapElements[c].left-s.margins.left)+s.snapElements[c].width,p=(h=s.snapElements[c].top-s.margins.top)+s.snapElements[c].height,m<l-g||a+g<u||_<h-g||p+g<v||!P.contains(s.snapElements[c].item.ownerDocument,s.snapElements[c].item)?(s.snapElements[c].snapping&&s.options.snap.release&&s.options.snap.release.call(s.element,t,P.extend(s._uiHash(),{snapItem:s.snapElements[c].item})),s.snapElements[c].snapping=!1):("inner"!==d.snapMode&&(i=Math.abs(h-_)<=g,o=Math.abs(p-v)<=g,n=Math.abs(l-m)<=g,r=Math.abs(a-u)<=g,i&&(e.position.top=s._convertPositionTo("relative",{top:h-s.helperProportions.height,left:0}).top),o&&(e.position.top=s._convertPositionTo("relative",{top:p,left:0}).top),n&&(e.position.left=s._convertPositionTo("relative",{top:0,left:l-s.helperProportions.width}).left),r&&(e.position.left=s._convertPositionTo("relative",{top:0,left:a}).left)),f=i||o||n||r,"outer"!==d.snapMode&&(i=Math.abs(h-v)<=g,o=Math.abs(p-_)<=g,n=Math.abs(l-u)<=g,r=Math.abs(a-m)<=g,i&&(e.position.top=s._convertPositionTo("relative",{top:h,left:0}).top),o&&(e.position.top=s._convertPositionTo("relative",{top:p-s.helperProportions.height,left:0}).top),n&&(e.position.left=s._convertPositionTo("relative",{top:0,left:l}).left),r&&(e.position.left=s._convertPositionTo("relative",{top:0,left:a-s.helperProportions.width}).left)),!s.snapElements[c].snapping&&(i||o||n||r||f)&&s.options.snap.snap&&s.options.snap.snap.call(s.element,t,P.extend(s._uiHash(),{snapItem:s.snapElements[c].item})),s.snapElements[c].snapping=i||o||n||r||f)}}),P.ui.plugin.add("draggable","stack",{start:function(t,e,s){var i,o=s.options,n=P.makeArray(P(o.stack)).sort(function(t,e){return(parseInt(P(t).css("zIndex"),10)||0)-(parseInt(P(e).css("zIndex"),10)||0)});n.length&&(i=parseInt(P(n[0]).css("zIndex"),10)||0,P(n).each(function(t){P(this).css("zIndex",i+t)}),this.css("zIndex",i+n.length))}}),P.ui.plugin.add("draggable","zIndex",{start:function(t,e,s){var i=P(e.helper),o=s.options;i.css("zIndex")&&(o._zIndex=i.css("zIndex")),i.css("zIndex",o.zIndex)},stop:function(t,e,s){var i=s.options;i._zIndex&&P(e.helper).css("zIndex",i._zIndex)}}),P.ui.draggable});

// CSS.supports polyfill
"CSS"in window||(window.CSS={}),"supports"in window.CSS||(window.CSS._cacheSupports={},window.CSS.supports=function(a,b){var c=[a,b].toString();return c in window.CSS._cacheSupports?window.CSS._cacheSupports[c]:window.CSS._cacheSupports[c]=function(a,b){var c=document.createElement("div").style;if("undefined"==typeof b){function b(a,b){var c=a.split(b);if(1<c.length)return c.map(function(a,b,c){return 0==b%2?a+c[b+1]:""}).filter(Boolean)}var d=b(a,/([)])\s*or\s*([(])/gi);if(d)return d.some(function(a){return window.CSS.supports(a)});var e=b(a,/([)])\s*and\s*([(])/gi);if(e)return e.every(function(a){return window.CSS.supports(a)});c.cssText=a.replace("(","").replace(/[)]$/,"")}else c.cssText=a+":"+b;return!!c.length}(a,b)});

/* ---------------------------------------------------- */
/* IRIS COLORPICKER                                     */
/* ---------------------------------------------------- */
/*! Color.js - v0.9.11 - 2013-08-09
* https://github.com/Automattic/Color.js
* Copyright (c) 2013 Matt Wiebe; Licensed GPLv2 */
(function(global, undef) {

	var Color = function( color, type ) {
		if ( ! ( this instanceof Color ) )
			return new Color( color, type );

		return this._init( color, type );
	};

	Color.fn = Color.prototype = {
		_color: 0,
		_alpha: 1,
		error: false,
		// for preserving hue/sat in fromHsl().toHsl() flows
		_hsl: { h: 0, s: 0, l: 0 },
		// for preserving hue/sat in fromHsv().toHsv() flows
		_hsv: { h: 0, s: 0, v: 0 },
		// for setting hsl or hsv space - needed for .h() & .s() functions to function properly
		_hSpace: 'hsl',
		_init: function( color ) {
			var func = 'noop';
			switch ( typeof color ) {
					case 'object':
						// alpha?
						if ( color.a !== undef )
							this.a( color.a );
						func = ( color.r !== undef ) ? 'fromRgb' :
							( color.l !== undef ) ? 'fromHsl' :
							( color.v !== undef ) ? 'fromHsv' : func;
						return this[func]( color );
					case 'string':
						return this.fromCSS( color );
					case 'number':
						return this.fromInt( parseInt( color, 10 ) );
			}
			return this;
		},

		_error: function() {
			this.error = true;
			return this;
		},

		clone: function() {
			var newColor = new Color( this.toInt() ),
				copy = ['_alpha', '_hSpace', '_hsl', '_hsv', 'error'];
			for ( var i = copy.length - 1; i >= 0; i-- ) {
				newColor[ copy[i] ] = this[ copy[i] ];
			}
			return newColor;
		},

		setHSpace: function( space ) {
			this._hSpace = ( space === 'hsv' ) ? space : 'hsl';
			return this;
		},

		noop: function() {
			return this;
		},

		fromCSS: function( color ) {
			var list,
				leadingRE = /^(rgb|hs(l|v))a?\(/;
			this.error = false;

			// whitespace and semicolon trim
			color = color.replace(/^\s+/, '').replace(/\s+$/, '').replace(/;$/, '');

			if ( color.match(leadingRE) && color.match(/\)$/) ) {
				list = color.replace(/(\s|%)/g, '').replace(leadingRE, '').replace(/,?\);?$/, '').split(',');

				if ( list.length < 3 )
					return this._error();

				if ( list.length === 4 ) {
					this.a( parseFloat( list.pop() ) );
					// error state has been set to true in .a() if we passed NaN
					if ( this.error )
						return this;
				}

				for (var i = list.length - 1; i >= 0; i--) {
					list[i] = parseInt(list[i], 10);
					if ( isNaN( list[i] ) )
						return this._error();
				}

				if ( color.match(/^rgb/) ) {
					return this.fromRgb( {
						r: list[0],
						g: list[1],
						b: list[2]
					} );
				} else if ( color.match(/^hsv/) ) {
					return this.fromHsv( {
						h: list[0],
						s: list[1],
						v: list[2]
					} );
				} else {
					return this.fromHsl( {
						h: list[0],
						s: list[1],
						l: list[2]
					} );
				}
			} else {
				// must be hex amirite?
				return this.fromHex( color );
			}
		},

		fromRgb: function( rgb, preserve ) {
			if ( typeof rgb !== 'object' || rgb.r === undef || rgb.g === undef || rgb.b === undef )
				return this._error();

			this.error = false;
			return this.fromInt( parseInt( ( rgb.r << 16 ) + ( rgb.g << 8 ) + rgb.b, 10 ), preserve );
		},

		fromHex: function( color ) {
			color = color.replace(/^#/, '').replace(/^0x/, '');
			if ( color.length === 3 ) {
				color = color[0] + color[0] + color[1] + color[1] + color[2] + color[2];
			}

			// rough error checking - this is where things go squirrely the most
			this.error = ! /^[0-9A-F]{6}$/i.test( color );
			return this.fromInt( parseInt( color, 16 ) );
		},

		fromHsl: function( hsl ) {
			var r, g, b, q, p, h, s, l;

			if ( typeof hsl !== 'object' || hsl.h === undef || hsl.s === undef || hsl.l === undef )
				return this._error();

			this._hsl = hsl; // store it
			this._hSpace = 'hsl'; // implicit
			h = hsl.h / 360; s = hsl.s / 100; l = hsl.l / 100;
			if ( s === 0 ) {
				r = g = b = l; // achromatic
			}
			else {
				q = l < 0.5 ? l * ( 1 + s ) : l + s - l * s;
				p = 2 * l - q;
				r = this.hue2rgb( p, q, h + 1/3 );
				g = this.hue2rgb( p, q, h );
				b = this.hue2rgb( p, q, h - 1/3 );
			}
			return this.fromRgb( {
				r: r * 255,
				g: g * 255,
				b: b * 255
			}, true ); // true preserves hue/sat
		},

		fromHsv: function( hsv ) {
			var h, s, v, r, g, b, i, f, p, q, t;
			if ( typeof hsv !== 'object' || hsv.h === undef || hsv.s === undef || hsv.v === undef )
				return this._error();

			this._hsv = hsv; // store it
			this._hSpace = 'hsv'; // implicit

			h = hsv.h / 360; s = hsv.s / 100; v = hsv.v / 100;
			i = Math.floor( h * 6 );
			f = h * 6 - i;
			p = v * ( 1 - s );
			q = v * ( 1 - f * s );
			t = v * ( 1 - ( 1 - f ) * s );

			switch( i % 6 ) {
				case 0:
					r = v; g = t; b = p;
					break;
				case 1:
					r = q; g = v; b = p;
					break;
				case 2:
					r = p; g = v; b = t;
					break;
				case 3:
					r = p; g = q; b = v;
					break;
				case 4:
					r = t; g = p; b = v;
					break;
				case 5:
					r = v; g = p; b = q;
					break;
			}

			return this.fromRgb( {
				r: r * 255,
				g: g * 255,
				b: b * 255
			}, true ); // true preserves hue/sat

		},
		// everything comes down to fromInt
		fromInt: function( color, preserve ) {
			this._color = parseInt( color, 10 );

			if ( isNaN( this._color ) )
				this._color = 0;

			// let's coerce things
			if ( this._color > 16777215 )
				this._color = 16777215;
			else if ( this._color < 0 )
				this._color = 0;

			// let's not do weird things
			if ( preserve === undef ) {
				this._hsv.h = this._hsv.s = this._hsl.h = this._hsl.s = 0;
			}
			// EVENT GOES HERE
			return this;
		},

		hue2rgb: function( p, q, t ) {
			if ( t < 0 ) {
				t += 1;
			}
			if ( t > 1 ) {
				t -= 1;
			}
			if ( t < 1/6 ) {
				return p + ( q - p ) * 6 * t;
			}
			if ( t < 1/2 ) {
				return q;
			}
			if ( t < 2/3 ) {
				return p + ( q - p ) * ( 2/3 - t ) * 6;
			}
			return p;
		},

		toString: function() {
			var hex = parseInt( this._color, 10 ).toString( 16 );
			if ( this.error )
				return '';
			// maybe left pad it
			if ( hex.length < 6 ) {
				for (var i = 6 - hex.length - 1; i >= 0; i--) {
					hex = '0' + hex;
				}
			}
			return '#' + hex;
		},

		toCSS: function( type, alpha ) {
			type = type || 'hex';
			alpha = parseFloat( alpha || this._alpha );
			switch ( type ) {
				case 'rgb':
				case 'rgba':
					var rgb = this.toRgb();
					if ( alpha < 1 ) {
						return "rgba( " + rgb.r + ", " + rgb.g + ", " + rgb.b + ", " + alpha + " )";
					}
					else {
						return "rgb( " + rgb.r + ", " + rgb.g + ", " + rgb.b + " )";
					}
					break;
				case 'hsl':
				case 'hsla':
					var hsl = this.toHsl();
					if ( alpha < 1 ) {
						return "hsla( " + hsl.h + ", " + hsl.s + "%, " + hsl.l + "%, " + alpha + " )";
					}
					else {
						return "hsl( " + hsl.h + ", " + hsl.s + "%, " + hsl.l + "% )";
					}
					break;
				default:
					return this.toString();
			}
		},

		toRgb: function() {
			return {
				r: 255 & ( this._color >> 16 ),
				g: 255 & ( this._color >> 8 ),
				b: 255 & ( this._color )
			};
		},

		toHsl: function() {
			var rgb = this.toRgb();
			var r = rgb.r / 255, g = rgb.g / 255, b = rgb.b / 255;
			var max = Math.max( r, g, b ), min = Math.min( r, g, b );
			var h, s, l = ( max + min ) / 2;

			if ( max === min ) {
				h = s = 0; // achromatic
			} else {
				var d = max - min;
				s = l > 0.5 ? d / ( 2 - max - min ) : d / ( max + min );
				switch ( max ) {
					case r: h = ( g - b ) / d + ( g < b ? 6 : 0 );
						break;
					case g: h = ( b - r ) / d + 2;
						break;
					case b: h = ( r - g ) / d + 4;
						break;
				}
				h /= 6;
			}

			// maintain hue & sat if we've been manipulating things in the HSL space.
			h = Math.round( h * 360 );
			if ( h === 0 && this._hsl.h !== h ) {
				h = this._hsl.h;
			}
			s = Math.round( s * 100 );
			if ( s === 0 && this._hsl.s ) {
				s = this._hsl.s;
			}

			return {
				h: h,
				s: s,
				l: Math.round( l * 100 )
			};

		},

		toHsv: function() {
			var rgb = this.toRgb();
			var r = rgb.r / 255, g = rgb.g / 255, b = rgb.b / 255;
			var max = Math.max( r, g, b ), min = Math.min( r, g, b );
			var h, s, v = max;
			var d = max - min;
			s = max === 0 ? 0 : d / max;

			if ( max === min ) {
				h = s = 0; // achromatic
			} else {
				switch( max ){
					case r:
						h = ( g - b ) / d + ( g < b ? 6 : 0 );
						break;
					case g:
						h = ( b - r ) / d + 2;
						break;
					case b:
						h = ( r - g ) / d + 4;
						break;
				}
				h /= 6;
			}

			// maintain hue & sat if we've been manipulating things in the HSV space.
			h = Math.round( h * 360 );
			if ( h === 0 && this._hsv.h !== h ) {
				h = this._hsv.h;
			}
			s = Math.round( s * 100 );
			if ( s === 0 && this._hsv.s ) {
				s = this._hsv.s;
			}

			return {
				h: h,
				s: s,
				v: Math.round( v * 100 )
			};
		},

		toInt: function() {
			return this._color;
		},

		toIEOctoHex: function() {
			// AARRBBGG
			var hex = this.toString();
			var AA = parseInt( 255 * this._alpha, 10 ).toString(16);
			if ( AA.length === 1 ) {
				AA = '0' + AA;
			}
			return '#' + AA + hex.replace(/^#/, '' );
		},

		toLuminosity: function() {
			var rgb = this.toRgb();
			return 0.2126 * Math.pow( rgb.r / 255, 2.2 ) + 0.7152 * Math.pow( rgb.g / 255, 2.2 ) + 0.0722 * Math.pow( rgb.b / 255, 2.2);
		},

		getDistanceLuminosityFrom: function( color ) {
			if ( ! ( color instanceof Color ) ) {
				throw 'getDistanceLuminosityFrom requires a Color object';
			}
			var lum1 = this.toLuminosity();
			var lum2 = color.toLuminosity();
			if ( lum1 > lum2 ) {
				return ( lum1 + 0.05 ) / ( lum2 + 0.05 );
			}
			else {
				return ( lum2 + 0.05 ) / ( lum1 + 0.05 );
			}
		},

		getMaxContrastColor: function() {
			var lum = this.toLuminosity();
			var hex = ( lum >= 0.5 ) ? '000000' : 'ffffff';
			return new Color( hex );
		},

		getReadableContrastingColor: function( bgColor, minContrast ) {
			if ( ! bgColor instanceof Color ) {
				return this;
			}

			// you shouldn't use less than 5, but you might want to.
			var targetContrast = ( minContrast === undef ) ? 5 : minContrast;
			// working things
			var contrast = bgColor.getDistanceLuminosityFrom( this );
			var maxContrastColor = bgColor.getMaxContrastColor();
			var maxContrast = maxContrastColor.getDistanceLuminosityFrom( bgColor );

			// if current max contrast is less than the target contrast, we had wishful thinking.
			// still, go max
			if ( maxContrast <= targetContrast ) {
				return maxContrastColor;
			}
			// or, we might already have sufficient contrast
			else if ( contrast >= targetContrast ) {
				return this;
			}

			var incr = ( 0 === maxContrastColor.toInt() ) ? -1 : 1;
			while ( contrast < targetContrast ) {
				this.l( incr, true ); // 2nd arg turns this into an incrementer
				contrast = this.getDistanceLuminosityFrom( bgColor );
				// infininite loop prevention: you never know.
				if ( this._color === 0 || this._color === 16777215 ) {
					break;
				}
			}

			return this;

		},

		a: function( val ) {
			if ( val === undef )
				return this._alpha;

			var a = parseFloat( val );

			if ( isNaN( a ) )
				return this._error();

			this._alpha = a;
			return this;
		},

		// TRANSFORMS

		darken: function( amount ) {
			amount = amount || 5;
			return this.l( - amount, true );
		},

		lighten: function( amount ) {
			amount = amount || 5;
			return this.l( amount, true );
		},

		saturate: function( amount ) {
			amount = amount || 15;
			return this.s( amount, true );
		},

		desaturate: function( amount ) {
			amount = amount || 15;
			return this.s( - amount, true );
		},

		toGrayscale: function() {
			return this.setHSpace('hsl').s( 0 );
		},

		getComplement: function() {
			return this.h( 180, true );
		},

		getSplitComplement: function( step ) {
			step = step || 1;
			var incr = 180 + ( step * 30 );
			return this.h( incr, true );
		},

		getAnalog: function( step ) {
			step = step || 1;
			var incr = step * 30;
			return this.h( incr, true );
		},

		getTetrad: function( step ) {
			step = step || 1;
			var incr = step * 60;
			return this.h( incr, true );
		},

		getTriad: function( step ) {
			step = step || 1;
			var incr = step * 120;
			return this.h( incr, true );
		},

		_partial: function( key ) {
			var prop = shortProps[key];
			return function( val, incr ) {
				var color = this._spaceFunc('to', prop.space);

				// GETTER
				if ( val === undef )
					return color[key];

				// INCREMENT
				if ( incr === true )
					val = color[key] + val;

				// MOD & RANGE
				if ( prop.mod )
					val = val % prop.mod;
				if ( prop.range )
					val = ( val < prop.range[0] ) ? prop.range[0] : ( val > prop.range[1] ) ? prop.range[1] : val;

				// NEW VALUE
				color[key] = val;

				return this._spaceFunc('from', prop.space, color);
			};
		},

		_spaceFunc: function( dir, s, val ) {
			var space = s || this._hSpace,
				funcName = dir + space.charAt(0).toUpperCase() + space.substr(1);
			return this[funcName](val);
		}
	};

	var shortProps = {
		h: {
			mod: 360
		},
		s: {
			range: [0,100]
		},
		l: {
			space: 'hsl',
			range: [0,100]
		},
		v: {
			space: 'hsv',
			range: [0,100]
		},
		r: {
			space: 'rgb',
			range: [0,255]
		},
		g: {
			space: 'rgb',
			range: [0,255]
		},
		b: {
			space: 'rgb',
			range: [0,255]
		}
	};

	for ( var key in shortProps ) {
		if ( shortProps.hasOwnProperty( key ) )
			Color.fn[key] = Color.fn._partial(key);
	}

	// play nicely with Node + browser
	if ( typeof exports === 'object' )
		module.exports = Color;
	else
		global.Color = Color;

}(this));




/* global Color, jQuery */
(function( $, undef ){
	var _html, nonGradientIE, gradientType, vendorPrefixes, _css, Iris, UA, isIE, IEVersion;

	_html = '<div class="iris-picker"><div class="iris-picker-inner"><div class="iris-square"><a class="iris-square-value"><span class="iris-square-handle ui-slider-handle"></span></a><div class="iris-square-inner iris-square-horiz"></div><div class="iris-square-inner iris-square-vert"></div></div><div class="iris-controls"><div class="iris-color-preview"><div></div></div><div class="iris-sliders"><div class="iris-slider iris-strip"><div class="iris-slider-offset"></div></div><div class="cs-alpha-wrap"><div class="cs-alpha-slider"></div><div class="cs-alpha-slider-offset"></div></div></div></div><div class="information hex"><div class="information-item hex"><div class="input-field hex"><input class="input" type="text" /><div class="title">HEX</div></div></div><div class="information-item rgb"><div class="input-field rgb-r"><input class="input" type="text"><div class="title">R</div></div><div class="input-field rgb-g"><input class="input" type="text"><div class="title">G</div></div><div class="input-field rgb-b"><input class="input" type="text"><div class="title">B</div></div><div class="input-field rgb-a"><input class="input" type="text"><div class="title">A</div></div></div><div class="information-change"><button type="button" class="format-change-button arrow-button"></button></div></div><div class="iris-color-control flat"><div class="colors-changer"><span class="flat">Flat</span><span class="meterial">Meterial</span><span class="soft">Soft</span><span class="page">Page Colors</span><button type="button" class="format-change-palette-button arrow-button"></button></div><div class="iris-color-list"><span class="iris-color flat" data-color="#1abc9c" style="background:#1abc9c"></span><span class="iris-color flat" data-color="#2ecc71" style="background:#2ecc71"></span><span class="iris-color flat" data-color="#3498db" style="background:#3498db"></span><span class="iris-color flat" data-color="#9b59b6" style="background:#9b59b6"></span><span class="iris-color flat" data-color="#34495e" style="background:#34495e"></span><span class="iris-color flat" data-color="#16a085" style="background:#16a085"></span><span class="iris-color flat" data-color="#27ae60" style="background:#27ae60"></span><span class="iris-color flat" data-color="#2980b9" style="background:#2980b9"></span><span class="iris-color flat" data-color="#8e44ad" style="background:#8e44ad"></span><span class="iris-color flat" data-color="#2c3e50" style="background:#2c3e50"></span><span class="iris-color flat" data-color="#f1c40f" style="background:#f1c40f"></span><span class="iris-color flat" data-color="#e67e22" style="background:#e67e22"></span><span class="iris-color flat" data-color="#e74c3c" style="background:#e74c3c"></span><span class="iris-color flat" data-color="#ecf0f1" style="background:#ecf0f1"></span><span class="iris-color flat" data-color="#95a5a6" style="background:#95a5a6"></span><span class="iris-color flat" data-color="#f39c12" style="background:#f39c12"></span><span class="iris-color flat" data-color="#d35400" style="background:#d35400"></span><span class="iris-color flat" data-color="#c0392b" style="background:#c0392b"></span><span class="iris-color flat" data-color="#bdc3c7" style="background:#bdc3c7"></span><span class="iris-color flat" data-color="#7f8c8d" style="background:#7f8c8d"></span><span class="iris-color meterial" data-color="#F44336" style="background:#F44336"></span><span class="iris-color meterial" data-color="#E91E63" style="background:#E91E63"></span><span class="iris-color meterial" data-color="#9C27B0" style="background:#9C27B0"></span><span class="iris-color meterial" data-color="#673AB7" style="background:#673AB7"></span><span class="iris-color meterial" data-color="#3F51B5" style="background:#3F51B5"></span><span class="iris-color meterial" data-color="#2196F3" style="background:#2196F3"></span><span class="iris-color meterial" data-color="#03A9F4" style="background:#03A9F4"></span><span class="iris-color meterial" data-color="#00BCD4" style="background:#00BCD4"></span><span class="iris-color meterial" data-color="#009688" style="background:#009688"></span><span class="iris-color meterial" data-color="#4CAF50" style="background:#4CAF50"></span><span class="iris-color meterial" data-color="#8BC34A" style="background:#8BC34A"></span><span class="iris-color meterial" data-color="#CDDC39" style="background:#CDDC39"></span><span class="iris-color meterial" data-color="#FFEB3B" style="background:#FFEB3B"></span><span class="iris-color meterial" data-color="#FFC107" style="background:#FFC107"></span><span class="iris-color meterial" data-color="#FF9800" style="background:#FF9800"></span><span class="iris-color meterial" data-color="#FF5722" style="background:#FF5722"></span><span class="iris-color meterial" data-color="#795548" style="background:#795548"></span><span class="iris-color meterial" data-color="#9E9E9E" style="background:#9E9E9E"></span><span class="iris-color meterial" data-color="#607D8B" style="background:#607D8B"></span><span class="iris-color meterial" data-color="#BEC2C3" style="background:#BEC2C3"></span><span class="iris-color soft" data-color="#69D2E7" style="background:#69D2E7"></span><span class="iris-color soft" data-color="#A7DBD8" style="background:#A7DBD8"></span><span class="iris-color soft" data-color="#E0E4CC" style="background:#E0E4CC"></span><span class="iris-color soft" data-color="#F38630" style="background:#F38630"></span><span class="iris-color soft" data-color="#FA6900" style="background:#FA6900"></span><span class="iris-color soft" data-color="#ECD078" style="background:#ECD078"></span><span class="iris-color soft" data-color="#D95B43" style="background:#D95B43"></span><span class="iris-color soft" data-color="#C02942" style="background:#C02942"></span><span class="iris-color soft" data-color="#542437" style="background:#542437"></span><span class="iris-color soft" data-color="#53777A" style="background:#53777A"></span><span class="iris-color soft" data-color="#CFF09E" style="background:#CFF09E"></span><span class="iris-color soft" data-color="#A8DBA8" style="background:#A8DBA8"></span><span class="iris-color soft" data-color="#79BD9A" style="background:#79BD9A"></span><span class="iris-color soft" data-color="#3B8686" style="background:#3B8686"></span><span class="iris-color soft" data-color="#0B486B" style="background:#0B486B"></span><span class="iris-color soft" data-color="#556270" style="background:#556270"></span><span class="iris-color soft" data-color="#4ECDC4" style="background:#4ECDC4"></span><span class="iris-color soft" data-color="#C7F464" style="background:#C7F464"></span><span class="iris-color soft" data-color="#FF6B6B" style="background:#FF6B6B"></span><span class="iris-color soft" data-color="#C44D58" style="background:#C44D58"></span></div></div></div></div>';
	_css = '';

	// Even IE9 dosen't support gradients. Elaborate sigh.
	UA = navigator.userAgent.toLowerCase();
	isIE = navigator.appName === 'Microsoft Internet Explorer';
	IEVersion = isIE ? parseFloat( UA.match( /msie ([0-9]{1,}[\.0-9]{0,})/ )[1] ) : 0;
	nonGradientIE = ( isIE && IEVersion < 10 );
	gradientType = false;

	// we don't bother with an unprefixed version, as it has a different syntax
	vendorPrefixes = [ '-moz-', '-webkit-', '-o-', '-ms-' ];

	// Bail for IE <= 7
	if ( nonGradientIE && IEVersion <= 7 ) {
		$.fn.iris = $.noop;
		$.support.iris = false;
		return;
	}

	$.support.iris = true;

	function testGradientType() {
		var el, base,
			bgImageString = 'backgroundImage';

		if ( nonGradientIE ) {
			gradientType = 'filter';
		}
		else {
			el = $( '<div id="iris-gradtest" />' );
			base = 'linear-gradient(top,#fff,#000)';
			$.each( vendorPrefixes, function( i, val ){
				el.css( bgImageString, val + base );
				if ( el.css( bgImageString ).match( 'gradient' ) ) {
					gradientType = i;
					return false;
				}
			});
			// check for legacy webkit gradient syntax
			if ( gradientType === false ) {
				el.css( 'background', '-webkit-gradient(linear,0% 0%,0% 100%,from(#fff),to(#000))' );
				if ( el.css( bgImageString ).match( 'gradient' ) ) {
					gradientType = 'webkit';
				}
			}
			el.remove();
		}

	}

	/**
	* Only for CSS3 gradients. oldIE will use a separate function.
	*
	* Accepts as many color stops as necessary from 2nd arg on, or 2nd
	* arg can be an array of color stops
	*
	* @param  {string} origin Gradient origin - top or left, defaults to left.
	* @return {string}        Appropriate CSS3 gradient string for use in
	*/
	function createGradient( origin, stops ) {
		origin = ( origin === 'top' ) ? 'top' : 'left';
		stops = $.isArray( stops ) ? stops : Array.prototype.slice.call( arguments, 1 );
		if ( gradientType === 'webkit' ) {
			return legacyWebkitGradient( origin, stops );
		} else {
			return vendorPrefixes[ gradientType ] + 'linear-gradient(' + origin + ', ' + stops.join(', ') + ')';
		}
	}

	/**
	* Stupid gradients for a stupid browser.
	*/
	function stupidIEGradient( origin, stops ) {
		var type, self, lastIndex, filter, startPosProp, endPosProp, dimensionProp, template, html;

		origin = ( origin === 'top' ) ? 'top' : 'left';
		stops = $.isArray( stops ) ? stops : Array.prototype.slice.call( arguments, 1 );
		// 8 hex: AARRGGBB
		// GradientType: 0 vertical, 1 horizontal
		type = ( origin === 'top' ) ? 0 : 1;
		self = $( this );
		lastIndex = stops.length - 1;
		filter = 'filter';
		startPosProp = ( type === 1 ) ? 'left' : 'top';
		endPosProp = ( type === 1 ) ? 'right' : 'bottom';
		dimensionProp = ( type === 1 ) ? 'height' : 'width';
		template = '<div class="iris-ie-gradient-shim" style="position:absolute;' + dimensionProp + ':100%;' + startPosProp + ':%start%;' + endPosProp + ':%end%;' + filter + ':%filter%;" data-color:"%color%"></div>';
		html = '';
		// need a positioning context
		if ( self.css('position') === 'static' ) {
			self.css( {position: 'relative' } );
		}

		stops = fillColorStops( stops );
		$.each(stops, function( i, startColor ) {
			var endColor, endStop, filterVal;

			// we want two at a time. if we're on the last pair, bail.
			if ( i === lastIndex ) {
				return false;
			}

			endColor = stops[ i + 1 ];
			//if our pairs are at the same color stop, moving along.
			if ( startColor.stop === endColor.stop ) {
				return;
			}

			endStop = 100 - parseFloat( endColor.stop ) + '%';
			startColor.octoHex = new Color( startColor.color ).toIEOctoHex();
			endColor.octoHex = new Color( endColor.color ).toIEOctoHex();

			filterVal = 'progid:DXImageTransform.Microsoft.Gradient(GradientType=' + type + ', StartColorStr=\'' + startColor.octoHex + '\', EndColorStr=\'' + endColor.octoHex + '\')';
			html += template.replace( '%start%', startColor.stop ).replace( '%end%', endStop ).replace( '%filter%', filterVal );
		});
		self.find( '.iris-ie-gradient-shim' ).remove();
		$( html ).prependTo( self );
	}

	function legacyWebkitGradient( origin, colorList ) {
		var stops = [];
		origin = ( origin === 'top' ) ? '0% 0%,0% 100%,' : '0% 100%,100% 100%,';
		colorList = fillColorStops( colorList );
		$.each( colorList, function( i, val ){
			stops.push( 'color-stop(' + ( parseFloat( val.stop ) / 100 ) + ', ' + val.color + ')' );
		});
		return '-webkit-gradient(linear,' + origin + stops.join(',') + ')';
	}

	function fillColorStops( colorList ) {
		var colors = [],
			percs = [],
			newColorList = [],
			lastIndex = colorList.length - 1;

		$.each( colorList, function( index, val ) {
			var color = val,
				perc = false,
				match = val.match( /1?[0-9]{1,2}%$/ );

			if ( match ) {
				color = val.replace( /\s?1?[0-9]{1,2}%$/, '' );
				perc = match.shift();
			}
			colors.push( color );
			percs.push( perc );
		});

		// back fill first and last
		if ( percs[0] === false ) {
			percs[0] = '0%';
		}

		if ( percs[lastIndex] === false ) {
			percs[lastIndex] = '100%';
		}

		percs = backFillColorStops( percs );

		$.each( percs, function( i ){
			newColorList[i] = { color: colors[i], stop: percs[i] };
		});
		return newColorList;
	}

	function backFillColorStops( stops ) {
		var first = 0,
			last = stops.length - 1,
			i = 0,
			foundFirst = false,
			incr,
			steps,
			step,
			firstVal;

		if ( stops.length <= 2 || $.inArray( false, stops ) < 0 ) {
			return stops;
		}
		while ( i < stops.length - 1 ) {
			if ( ! foundFirst && stops[i] === false ) {
				first = i - 1;
				foundFirst = true;
			} else if ( foundFirst && stops[i] !== false ) {
				last = i;
				i = stops.length;
			}
			i++;
		}
		steps = last - first;
		firstVal = parseInt( stops[first].replace('%'), 10 );
		incr = ( parseFloat( stops[last].replace('%') ) - firstVal ) / steps;
		i = first + 1;
		step = 1;
		while ( i < last ) {
			stops[i] = ( firstVal + ( step * incr ) ) + '%';
			step++;
			i++;
		}
		return backFillColorStops( stops );
	}

	$.fn.gradient = function() {
		var args = arguments;
		return this.each( function() {
			// this'll be oldishIE
			if ( nonGradientIE ) {
				stupidIEGradient.apply( this, args );
			} else {
				// new hotness
				$( this ).css( 'backgroundImage', createGradient.apply( this, args ) );
			}
		});
	};

	/*
	$.fn.rainbowGradient = function( origin, args ) {
		var opts, template, i, steps;

		origin = origin || 'top';
		opts = $.extend( {}, { s: 100, l: 50 }, args );
		template = 'hsl(%h%,' + opts.s + '%,' + opts.l + '%)';
		i = 0;
		steps = [];
		while ( i <= 360 ) {
			steps.push( template.replace('%h%', i) );
			i += 30;
		}
		return this.each(function() {
			$(this).gradient( origin, steps );
		});
	};
	*/

	// the colorpicker widget def.
	Iris = {
		options: {
			color: false,
			mode: 'hsl',
			controls: {
				horiz: 's', // horizontal defaults to saturation
				vert: 'l', // vertical defaults to lightness
				strip: 'h' // right strip defaults to hue
			},
			hide: true, // hide the color picker by default
			border: true, // draw a border around the collection of UI elements
			target: false, // a DOM element / jQuery selector that the element will be appended within. Only used when called on an input.
			width: 200, // the width of the collection of UI elements
			palettes: false // show a palette of basic colors beneath the square.
		},
		_color: '',
		_palettes: [ '#000', '#fff', '#d33', '#d93', '#ee2', '#81d742', '#1e73be', '#8224e3' ],
		_inited: false,
		_defaultHSLControls: {
			horiz: 's',
			vert: 'l',
			strip: 'h'
		},
		_defaultHSVControls: {
			horiz: 'h',
			vert: 'v',
			strip: 's'
		},
		_scale: {
			h: 360,
			s: 100,
			l: 100,
			v: 100
		},
		_create: function() {
			var self = this,
				el = self.element,
				color = self.options.color || el.val();

			if ( gradientType === false ) {
				testGradientType();
			}

			if ( el.is( 'input' ) ) {
				if ( self.options.target ) {
					self.picker = $( _html ).appendTo( self.options.target );
				} else {
					self.picker = $( _html ).insertAfter( el );
				}

				self._addInputListeners( el );
			} else {
				el.append( _html );
				self.picker = el.find( '.iris-picker' );
			}

			// Browsers / Versions
			// Feature detection doesn't work for these, and $.browser is deprecated
			if ( isIE ) {
				if ( IEVersion === 9 ) {
					self.picker.addClass( 'iris-ie-9' );
				} else if ( IEVersion <= 8 ) {
					self.picker.addClass( 'iris-ie-lt9' );
				}
			} else if ( UA.indexOf('compatible') < 0 && UA.indexOf('khtml') < 0 && UA.match( /mozilla/ ) ) {
				self.picker.addClass( 'iris-mozilla' );
			}

			if ( self.options.palettes ) {
				self._addPalettes();
			}

			self._color = new Color( color ).setHSpace( self.options.mode );
			self.options.color = self._color.toString();

			// prep 'em for re-use
			self.controls = {
				square:      self.picker.find( '.iris-square' ),
				squareDrag:  self.picker.find( '.iris-square-value' ),
				horiz:       self.picker.find( '.iris-square-horiz' ),
				vert:        self.picker.find( '.iris-square-vert' ),
				strip:       self.picker.find( '.iris-strip' ),
				stripSlider: self.picker.find( '.iris-strip .iris-slider-offset' )
			};

			// small sanity check - if we chose hsv, change default controls away from hsl
			if ( self.options.mode === 'hsv' && self._has('l', self.options.controls) ) {
				self.options.controls = self._defaultHSVControls;
			} else if ( self.options.mode === 'hsl' && self._has('v', self.options.controls) ) {
				self.options.controls = self._defaultHSLControls;
			}

			// store it. HSL gets squirrely
			self.hue = self._color.h();

			if ( self.options.hide ) {
				self.picker.hide();
			}

			if ( self.options.border ) {
				self.picker.addClass( 'iris-border' );
			}

			self._initControls();
			self.active = 'external';
			self._dimensions();
			self._change();
		},
		_has: function(needle, haystack) {
			var ret = false;
			$.each(haystack, function(i,v){
				if ( needle === v ) {
					ret = true;
					// exit the loop
					return false;
				}
			});
			return ret;
		},
		_addPalettes: function () {
			var container = $( '<div class="iris-palette-container" />' ),
				palette = $( '<a class="iris-palette" tabindex="0" />' ),
				colors = $.isArray( this.options.palettes ) ? this.options.palettes : this._palettes;

			// do we have an existing container? Empty and reuse it.
			if ( this.picker.find( '.iris-palette-container' ).length ) {
				container = this.picker.find( '.iris-palette-container' ).detach().html( '' );
			}

			$.each(colors, function(index, val) {
				palette.clone().data( 'color', val )
					.css( 'backgroundColor', val ).appendTo( container )
					.height( 10 ).width( 10 );
			});

			this.picker.append(container);
		},
		_paint: function() {
			var self = this;
			self._paintDimension( 'left', 'strip' );
			self._paintDimension( 'top', 'vert' );
			self._paintDimension( 'left', 'horiz' );
		},
		_paintDimension: function( origin, control ) {
			var self = this,
				c = self._color,
				mode = self.options.mode,
				color = self._getHSpaceColor(),
				target = self.controls[ control ],
				controlOpts = self.options.controls,
				stops;

			// don't paint the active control
			if ( control === self.active || ( self.active === 'square' && control !== 'strip' ) ) {
				return;
			}

			switch ( controlOpts[ control ] ) {
				case 'h':
					if ( mode === 'hsv' ) {
						color = c.clone();
						switch ( control ) {
							case 'horiz':
								color[controlOpts.vert](100);
								break;
							case 'vert':
								color[controlOpts.horiz](100);
								break;
							case 'strip':
								color.setHSpace('hsl');
								break;
						}
						stops = color.toHsl();
					} else {
						if ( control === 'strip' ) {
							stops = { s: color.s, l: color.l };
						} else {
							stops = { s: 100, l: color.l };
						}
					}

					//target.rainbowGradient( origin, stops );
					break;
				case 's':
					if ( mode === 'hsv' ) {
						if ( control === 'vert' ) {
							stops = [ c.clone().a(0).s(0).toCSS('rgba'), c.clone().a(1).s(0).toCSS('rgba') ];
						} else if ( control === 'strip' ) {
							stops = [ c.clone().s(100).toCSS('hsl'), c.clone().s(0).toCSS('hsl') ];
						} else if ( control === 'horiz' ) {
							stops = [ '#fff', 'hsl(' + color.h + ',100%,50%)' ];
						}
					} else { // implicit mode === 'hsl'
						if ( control === 'vert' && self.options.controls.horiz === 'h' ) {
							stops = ['hsla(0, 0%, ' + color.l + '%, 0)', 'hsla(0, 0%, ' + color.l + '%, 1)'];
						} else {
							stops = ['hsl('+ color.h +',0%,50%)', 'hsl(' + color.h + ',100%,50%)'];
						}
					}


					target.gradient( origin, stops );
					break;
				case 'l':
					if ( control === 'strip' ) {
						stops = ['hsl(' + color.h + ',100%,100%)', 'hsl(' + color.h + ', ' + color.s + '%,50%)', 'hsl('+ color.h +',100%,0%)'];
					} else {
						stops = ['#fff', 'rgba(255,255,255,0) 50%', 'rgba(0,0,0,0) 50%', 'rgba(0,0,0,1)'];
					}
					target.gradient( origin, stops );
					break;
				case 'v':
						if ( control === 'strip' ) {
							stops = [ c.clone().v(100).toCSS(), c.clone().v(0).toCSS() ];
						} else {
							stops = ['rgba(0,0,0,0)', '#000'];
						}
						target.gradient( origin, stops );
					break;
				default:
					break;
			}
		},

		_getHSpaceColor: function() {
			return ( this.options.mode === 'hsv' ) ? this._color.toHsv() : this._color.toHsl();
		},

		_dimensions: function( reset ) {

		},

		_addInputListeners: function( input ) {
			var self = this,
				debounceTimeout = 100,
				callback = function( event ){
					var color = new Color( input.val() ),
						val = input.val().replace( /^#/, '' );

					input.removeClass( 'iris-error' );
					// we gave a bad color
					if ( color.error ) {
						// don't error on an empty input - we want those allowed
						if ( val !== '' ) {
							input.addClass( 'iris-error' );
						}
					} else {
						if ( color.toString() !== self._color.toString() ) {
							// let's not do this on keyup for hex shortcodes
							if ( ! ( event.type === 'keyup' && val.match( /^[0-9a-fA-F]{3}$/ ) ) ) {
								self._setOption( 'color', color.toString() );
							}
						}
					}
				};

			input.on( 'change', callback ).on( 'keyup', self._debounce( callback, debounceTimeout ) );

			// If we initialized hidden, show on first focus. The rest is up to you.
			if ( self.options.hide ) {
				input.one( 'focus', function() {
					self.show();
				});
			}
		},

		_initControls: function() {
			var self = this,
				controls = self.controls,
				square = controls.square,
				controlOpts = self.options.controls,
				stripScale = self._scale[controlOpts.strip];

			controls.stripSlider.slider({
				orientation: 'horizontal ',
				max: stripScale,
				slide: function( event, ui ) {
					self.active = 'strip';
					// "reverse" for hue.
					if ( controlOpts.strip === 'h' ) {
						ui.value = stripScale - ui.value;
					}

					self._color[controlOpts.strip]( ui.value );
					self._change.apply( self, arguments );
				}
			});

			controls.squareDrag.draggable({
				containment: controls.square.find( '.iris-square-inner' ),
				zIndex: 1000,
				cursor: 'move',
				drag: function( event, ui ) {
					self._squareDrag( event, ui );
				},
				start: function() {
					square.addClass( 'iris-dragging' );
					$(this).addClass( 'ui-state-focus' );
				},
				stop: function() {
					square.removeClass( 'iris-dragging' );
					$(this).removeClass( 'ui-state-focus' );
				}
			}).on( 'mousedown mouseup', function( event ) {
				var focusClass = 'ui-state-focus';
				event.preventDefault();
				if (event.type === 'mousedown' ) {
					self.picker.find( '.' + focusClass ).removeClass( focusClass ).blur();
					$(this).addClass( focusClass ).focus();
				} else {
					$(this).removeClass( focusClass );
				}
			}).on( 'keydown', function( event ) {
				var container = controls.square,
					draggable = controls.squareDrag,
					position = draggable.position(),
					distance = self.options.width / 100; // Distance in pixels the draggable should be moved: 1 "stop"

				// make alt key go "10"
				if ( event.altKey ) {
					distance *= 10;
				}

				// Reposition if one of the directional keys is pressed
				switch ( event.keyCode ) {
					case 37: position.left -= distance; break; // Left
					case 38: position.top  -= distance; break; // Up
					case 39: position.left += distance; break; // Right
					case 40: position.top  += distance; break; // Down
					default: return true; // Exit and bubble
				}

				// Keep draggable within container
				position.left = Math.max( 0, Math.min( position.left, container.width() ) );
				position.top =  Math.max( 0, Math.min( position.top, container.height() ) );

				draggable.css(position);
				self._squareDrag( event, { position: position });
				event.preventDefault();
			});

			// allow clicking on the square to move there and keep dragging
			square.mousedown( function( event ) {
				var squareOffset, pos;
				// only left click
				if ( event.which !== 1 ) {
					return;
				}

				// prevent bubbling from the handle: no infinite loops
				if ( ! $( event.target ).is( 'div' ) ) {
					return;
				}

				squareOffset = self.controls.square.offset();
				pos = {
						top: event.pageY - squareOffset.top,
						left: event.pageX - squareOffset.left
				};
				event.preventDefault();
				self._squareDrag( event, { position: pos } );
				event.target = self.controls.squareDrag.get(0);
				self.controls.squareDrag.css( pos ).trigger( event );
			});

			// palettes
			if ( self.options.palettes ) {
				self._paletteListeners();
			}
		},

		_paletteListeners: function() {
			var self = this;
			self.picker.find('.iris-palette-container').on('click.palette', '.iris-palette', function() {
				self._color.fromCSS( $(this).data('color') );
				self.active = 'external';
				self._change();
			}).on( 'keydown.palette', '.iris-palette', function( event ) {
				if ( ! ( event.keyCode === 13 || event.keyCode === 32 ) ) {
					return true;
				}
				event.stopPropagation();
				$( this ).click();
			});
		},

		_squareDrag: function( event, ui ) {
			var self = this,
				controlOpts = self.options.controls,
				dimensions = self._squareDimensions(),
				vertVal = Math.round( ( dimensions.h - ui.position.top ) / dimensions.h * self._scale[controlOpts.vert] ),
				horizVal = self._scale[controlOpts.horiz] - Math.round( ( dimensions.w - ui.position.left ) / dimensions.w * self._scale[controlOpts.horiz] );

			self._color[controlOpts.horiz]( horizVal )[controlOpts.vert]( vertVal );

			self.active = 'square';
			self._change.apply( self, arguments );
		},

		_setOption: function( key, value ) {
			var self = this,
				oldValue = self.options[key],
				doDimensions = false,
				hexLessColor,
				newColor,
				method;

			// ensure the new value is set. We can reset to oldValue if some check wasn't met.
			self.options[key] = value;

			switch(key) {
				case 'color':
					// cast to string in case we have a number
					value = '' + value;
					hexLessColor = value.replace( /^#/, '' );
					newColor = new Color( value ).setHSpace( self.options.mode );
					if ( newColor.error ) {
						self.options[key] = oldValue;
					} else {
						self._color = newColor;
						self.options.color = self.options[key] = self._color.toString();
						self.active = 'external';
						self._change();
					}
					break;
				case 'palettes':
					doDimensions = true;

					if ( value ) {
						self._addPalettes();
					} else {
						self.picker.find('.iris-palette-container').remove();
					}

					// do we need to add events?
					if ( ! oldValue ) {
						self._paletteListeners();
					}
					break;
				case 'width':
					doDimensions = true;
					break;
				case 'border':
					doDimensions = true;
					method = value ? 'addClass' : 'removeClass';
					self.picker[method]('iris-border');
					break;
				case 'mode':
				case 'controls':
					// if nothing's changed, let's bail, since this causes re-rendering the whole widget
					if ( oldValue === value ) {
						return;
					}

					// we're using these poorly named variables because they're already scoped.
					// method is the element that Iris was called on. oldValue will be the options
					method = self.element;
					oldValue = self.options;
					oldValue.hide = ! self.picker.is( ':visible' );
					self.destroy();
					self.picker.remove();
					return $(self.element).iris(oldValue);
			}

			// Do we need to recalc dimensions?
			if ( doDimensions ) {
				self._dimensions(true);
			}
		},

		_squareDimensions: function( forceRefresh ) {
			var square = this.controls.square,
				dimensions,
				control;

			if ( forceRefresh !== undef && square.data('dimensions') ) {
				return square.data('dimensions');
			}

			control = this.controls.squareDrag;
			dimensions = {
				w: square.width(),
				h: square.height()
			};
			square.data( 'dimensions', dimensions );
			return dimensions;
		},

		_isNonHueControl: function( active, type ) {
			if ( active === 'square' && this.options.controls.strip === 'h' ) {
				return true;
			} else if ( type === 'external' || ( type === 'h' && active === 'strip' ) ) {
				return false;
			}

			return true;
		},

		_change: function() {
			var self = this,
				controls = self.controls,
				color = self._getHSpaceColor(),
				actions = [ 'square', 'strip' ],
				controlOpts = self.options.controls,
				type = controlOpts[self.active] || 'external',
				oldHue = self.hue;

			if ( self.active === 'strip' ) {
				// take no action on any of the square sliders if we adjusted the strip
				actions = [];
			} else if ( self.active !== 'external' ) {
				// for non-strip, non-external, strip should never change
				actions.pop(); // conveniently the last item
			}

			$.each( actions, function(index, item) {
				var value, dimensions, cssObj;
				if ( item !== self.active ) {
					switch ( item ) {
						case 'strip':
							// reverse for hue
							value = ( controlOpts.strip === 'h' ) ? self._scale[controlOpts.strip] - color[controlOpts.strip] : color[controlOpts.strip];
							controls.stripSlider.slider( 'value', value );
							break;
						case 'square':
							dimensions = self._squareDimensions();
							cssObj = {
								left: color[controlOpts.horiz] / self._scale[controlOpts.horiz] * dimensions.w,
								top: dimensions.h - ( color[controlOpts.vert] / self._scale[controlOpts.vert] * dimensions.h )
							};

							self.controls.squareDrag.css( cssObj );
							break;
					}
				}
			});

			// Ensure that we don't change hue if we triggered a hue reset
			if ( color.h !== oldHue && self._isNonHueControl( self.active, type ) ) {
				self._color.h(oldHue);
			}

			// store hue for repeating above check next time
			self.hue = self._color.h();

			self.options.color = self._color.toString();

			// only run after the first time
			if ( self._inited ) {
				self._trigger( 'change', { type: self.active }, { color: self._color } );
			}

			if ( self.element.is( ':input' ) && ! self._color.error ) {
				self.element.removeClass( 'iris-error' );
				if ( self.element.val() !== self._color.toString() ) {
					self.element.val( self._color.toString() );
				}
			}

			self._paint();
			self._inited = true;
			self.active = false;
		},
		// taken from underscore.js _.debounce method
		_debounce: function( func, wait, immediate ) {
			var timeout, result;
			return function() {
				var context = this,
					args = arguments,
					later,
					callNow;

				later = function() {
					timeout = null;
					if ( ! immediate) {
						result = func.apply( context, args );
					}
				};

				callNow = immediate && !timeout;
				clearTimeout( timeout );
				timeout = setTimeout( later, wait );
				if ( callNow ) {
					result = func.apply( context, args );
				}
				return result;
			};
		},
		show: function() {
			this.picker.show();
		},
		hide: function() {
			this.picker.hide();
		},
		toggle: function() {
			this.picker.toggle();
		},
		color: function(newColor) {
			if ( newColor === true ) {
				return this._color.clone();
			} else if ( newColor === undef ) {
				return this._color.toString();
			}
			this.option('color', newColor);
		}
	};
	// initialize the widget
	$.widget( 'a8c.iris', Iris );

}( jQuery ));




/* ---------------------------------------------------- */
/* Codestar WP Color Picker v1.1.0                      */
/* ---------------------------------------------------- */
! function(t, e, r, a) {
    "use strict";
    void 0 !== typeof Color.fn.toString && (Color.fn.toString = function() {
        if (this._alpha < 1) return this.toCSS("rgba", this._alpha).replace(/\s+/g, "");
        var r = parseInt(this._color, 10).toString(16);
        if (this.error) return "";
        if (r.length < 6)
            for (var a = 6 - r.length - 1; 0 <= a; a--) r = "0" + r;
        return "#" + r
    }), t.cs_ParseColorValue = function(r) {
        var a = r.replace(/\s+/g, ""),
            i = -1 !== a.indexOf("rgba") ? parseFloat(100 * a.replace(/^.*,(.+)\)/, "$1")) : 100;
        return {
            value: a,
            alpha: i,
            rgba: i < 100
        }
    }, t.fn.cs_iris = function(r) {
        return this.each(function() {
            var n = t(this);
            if ("color" == r) return n.iris(r), !1;
            if (!1 !== n.data("rgba")) {
                var s = t.cs_ParseColorValue(n.val());
                n.iris({
                    hide: true,
                    change: function(r, a) {
                        var i = a.color.toString();
                        n.parent().find(".co-sw-co").css("background-color", i), n.next(".iris-picker").find(".cs-alpha-slider-offset").css("background-image", "linear-gradient(to right, rgba(255,255,255,0) 0%, " + i + " 100%)"), e.iris_global_change_callback(r, a)
                    },
                    create: function() {
                        var e = n.data("a8cIris"),
                            r = n.next(".iris-picker"),
                            a = n.next(".iris-picker").find(".cs-alpha-wrap"),
                            i = a.find(".cs-alpha-slider"),
                            o = a.find(".cs-alpha-slider-offset");
                        i.slider({
                            slide: function(r, a) {
                                var i = parseFloat(a.value / 100);
                                e._color._alpha = i, n.iris("color", e._color.toString())
                            },
                            change: function(r, a) {
                                r.originalEvent && n.trigger("change")
                            },
                            create: function() {
                                parseFloat(s.alpha / 100);
                                o.css("background-color", s.value)
                            },
                            value: s.alpha,
                            step: 1,
                            min: 0,
                            max: 100
                        })
                    }
                })
            } else n.iris(r)
        })
    }
}(jQuery, window, document);


/* ---------------------------------------------------- */
/* IN THE VIEW                                          */
/* ---------------------------------------------------- */
!function(e,n){"object"==typeof exports&&"undefined"!=typeof module?n(require("jquery")):"function"==typeof define&&define.amd?define(["jquery"],n):n(e.jQuery)}(this,function(e){"use strict";function n(n){var t=this;if(1===arguments.length&&"function"==typeof n&&(n=[n]),!(n instanceof Array))throw new SyntaxError("isInViewport: Argument(s) passed to .do/.run should be a function or an array of functions");return n.forEach(function(n){"function"!=typeof n?(console.warn("isInViewport: Argument(s) passed to .do/.run should be a function or an array of functions"),console.warn("isInViewport: Ignoring non-function values in array and moving on")):[].slice.call(t).forEach(function(t){return n.call(e(t))})}),this}function t(n){var t=e("<div></div>").css({width:"100%"});n.append(t);var o=n.width()-t.width();return t.remove(),o}function o(n,r){var i=n.getBoundingClientRect(),a=i.top,u=i.bottom,c=i.left,f=i.right,s=e.extend({tolerance:0,viewport:window},r),d=!1,l=s.viewport.jquery?s.viewport:e(s.viewport);l.length||(console.warn("isInViewport: The viewport selector you have provided matches no element on page."),console.warn("isInViewport: Defaulting to viewport as window"),l=e(window));var p=l.height(),w=l.width(),h=l[0].toString();if(l[0]!==window&&"[object Window]"!==h&&"[object DOMWindow]"!==h){var v=l[0].getBoundingClientRect();a-=v.top,u-=v.top,c-=v.left,f-=v.left,o.scrollBarWidth=o.scrollBarWidth||t(l),w-=o.scrollBarWidth}return s.tolerance=~~Math.round(parseFloat(s.tolerance)),s.tolerance<0&&(s.tolerance=p+s.tolerance),f<=0||c>=w?d:d=s.tolerance?a<=s.tolerance&&u>=s.tolerance:u>0&&a<=p}function r(n){if(n){var t=n.split(",");return 1===t.length&&isNaN(t[0])&&(t[1]=t[0],t[0]=void 0),{tolerance:t[0]?t[0].trim():void 0,viewport:t[1]?e(t[1].trim()):void 0}}return{}}e=e&&e.hasOwnProperty("default")?e.default:e,e.extend(e.expr.pseudos||e.expr[":"],{"in-viewport":e.expr.createPseudo?e.expr.createPseudo(function(e){return function(n){return o(n,r(e))}}):function(e,n,t){return o(e,r(t[3]))}}),e.fn.isInViewport=function(e){return this.filter(function(n,t){return o(t,e)})},e.fn.run=n});



/* ---------------------------------------------------- */
/* BOOTSTRAP TOOLTIP                                    */
/* ---------------------------------------------------- */
if("undefined"==typeof jQuery)throw new Error("Bootstrap's JavaScript requires jQuery");+function(t){"use strict";var e=t.fn.jquery.split(" ")[0].split(".");if(e[0]<2&&e[1]<9||1==e[0]&&9==e[1]&&e[2]<1)throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher")}(jQuery),+function(t){"use strict";function e(e){return this.each(function(){var i=t(this),n=i.data("bs.tooltip"),s="object"==typeof e&&e;(n||!/destroy|hide/.test(e))&&(n||i.data("bs.tooltip",n=new o(this,s)),"string"==typeof e&&n[e]())})}var o=function(t,e){this.type=null,this.options=null,this.enabled=null,this.timeout=null,this.hoverState=null,this.$element=null,this.inState=null,this.init("tooltip",t,e)};o.VERSION="3.3.5",o.TRANSITION_DURATION=150,o.DEFAULTS={animation:!0,placement:"top",selector:!1,template:'<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!1,container:!1,viewport:{selector:"body",padding:0}},o.prototype.init=function(e,o,i){if(this.enabled=!0,this.type=e,this.$element=t(o),this.options=this.getOptions(i),this.$viewport=this.options.viewport&&t(t.isFunction(this.options.viewport)?this.options.viewport.call(this,this.$element):this.options.viewport.selector||this.options.viewport),this.inState={click:!1,hover:!1,focus:!1},this.$element[0]instanceof document.constructor&&!this.options.selector)throw new Error("`selector` option must be specified when initializing "+this.type+" on the window.document object!");for(var n=this.options.trigger.split(" "),s=n.length;s--;){var r=n[s];if("click"==r)this.$element.on("click."+this.type,this.options.selector,t.proxy(this.toggle,this));else if("manual"!=r){var p="hover"==r?"mouseenter":"focusin",a="hover"==r?"mouseleave":"focusout";this.$element.on(p+"."+this.type,this.options.selector,t.proxy(this.enter,this)),this.$element.on(a+"."+this.type,this.options.selector,t.proxy(this.leave,this))}}this.options.selector?this._options=t.extend({},this.options,{trigger:"manual",selector:""}):this.fixTitle()},o.prototype.getDefaults=function(){return o.DEFAULTS},o.prototype.getOptions=function(e){return e=t.extend({},this.getDefaults(),this.$element.data(),e),e.delay&&"number"==typeof e.delay&&(e.delay={show:e.delay,hide:e.delay}),e},o.prototype.getDelegateOptions=function(){var e={},o=this.getDefaults();return this._options&&t.each(this._options,function(t,i){o[t]!=i&&(e[t]=i)}),e},o.prototype.enter=function(e){var o=e instanceof this.constructor?e:t(e.currentTarget).data("bs."+this.type);return o||(o=new this.constructor(e.currentTarget,this.getDelegateOptions()),t(e.currentTarget).data("bs."+this.type,o)),e instanceof t.Event&&(o.inState["focusin"==e.type?"focus":"hover"]=!0),o.tip().hasClass("in")||"in"==o.hoverState?void(o.hoverState="in"):(clearTimeout(o.timeout),o.hoverState="in",o.options.delay&&o.options.delay.show?void(o.timeout=setTimeout(function(){"in"==o.hoverState&&o.show()},o.options.delay.show)):o.show())},o.prototype.isInStateTrue=function(){for(var t in this.inState)if(this.inState[t])return!0;return!1},o.prototype.leave=function(e){var o=e instanceof this.constructor?e:t(e.currentTarget).data("bs."+this.type);return o||(o=new this.constructor(e.currentTarget,this.getDelegateOptions()),t(e.currentTarget).data("bs."+this.type,o)),e instanceof t.Event&&(o.inState["focusout"==e.type?"focus":"hover"]=!1),o.isInStateTrue()?void 0:(clearTimeout(o.timeout),o.hoverState="out",o.options.delay&&o.options.delay.hide?void(o.timeout=setTimeout(function(){"out"==o.hoverState&&o.hide()},o.options.delay.hide)):o.hide())},o.prototype.show=function(){var e=t.Event("show.bs."+this.type);if(this.hasContent()&&this.enabled){this.$element.trigger(e);var i=t.contains(this.$element[0].ownerDocument.documentElement,this.$element[0]);if(e.isDefaultPrevented()||!i)return;var n=this,s=this.tip(),r=this.getUID(this.type);this.setContent(),s.attr("id",r),this.$element.attr("aria-describedby",r),this.options.animation&&s.addClass("fade");var p="function"==typeof this.options.placement?this.options.placement.call(this,s[0],this.$element[0]):this.options.placement,a=/\s?auto?\s?/i,l=a.test(p);l&&(p=p.replace(a,"")||"top"),s.detach().css({top:0,left:0,display:"block"}).addClass(p).data("bs."+this.type,this),this.options.container?s.appendTo(this.options.container):s.insertAfter(this.$element),this.$element.trigger("inserted.bs."+this.type);var h=this.getPosition(),f=s[0].offsetWidth,c=s[0].offsetHeight;if(l){var u=p,d=this.getPosition(this.$viewport);p="bottom"==p&&h.bottom+c>d.bottom?"top":"top"==p&&h.top-c<d.top?"bottom":"right"==p&&h.right+f>d.width?"left":"left"==p&&h.left-f<d.left?"right":p,s.removeClass(u).addClass(p)}var v=this.getCalculatedOffset(p,h,f,c);this.applyPlacement(v,p);var g=function(){var t=n.hoverState;n.$element.trigger("shown.bs."+n.type),n.hoverState=null,"out"==t&&n.leave(n)};t.support.transition&&this.$tip.hasClass("fade")?s.one("bsTransitionEnd",g).emulateTransitionEnd(o.TRANSITION_DURATION):g()}},o.prototype.applyPlacement=function(e,o){var i=this.tip(),n=i[0].offsetWidth,s=i[0].offsetHeight,r=parseInt(i.css("margin-top"),10),p=parseInt(i.css("margin-left"),10);isNaN(r)&&(r=0),isNaN(p)&&(p=0),e.top+=r,e.left+=p,t.offset.setOffset(i[0],t.extend({using:function(t){i.css({top:Math.round(t.top),left:Math.round(t.left)})}},e),0),i.addClass("in");var a=i[0].offsetWidth,l=i[0].offsetHeight;"top"==o&&l!=s&&(e.top=e.top+s-l);var h=this.getViewportAdjustedDelta(o,e,a,l);h.left?e.left+=h.left:e.top+=h.top;var f=/top|bottom/.test(o),c=f?2*h.left-n+a:2*h.top-s+l,u=f?"offsetWidth":"offsetHeight";i.offset(e),this.replaceArrow(c,i[0][u],f)},o.prototype.replaceArrow=function(t,e,o){this.arrow().css(o?"left":"top",50*(1-t/e)+"%").css(o?"top":"left","")},o.prototype.setContent=function(){var t=this.tip(),e=this.getTitle();t.find(".tooltip-inner")[this.options.html?"html":"text"](e),t.removeClass("fade in top bottom left right")},o.prototype.hide=function(e){function i(){"in"!=n.hoverState&&s.detach(),n.$element.removeAttr("aria-describedby").trigger("hidden.bs."+n.type),e&&e()}var n=this,s=t(this.$tip),r=t.Event("hide.bs."+this.type);return this.$element.trigger(r),r.isDefaultPrevented()?void 0:(s.removeClass("in"),t.support.transition&&s.hasClass("fade")?s.one("bsTransitionEnd",i).emulateTransitionEnd(o.TRANSITION_DURATION):i(),this.hoverState=null,this)},o.prototype.fixTitle=function(){var t=this.$element;(t.attr("title")||"string"!=typeof t.attr("data-original-title"))&&t.attr("data-original-title",t.attr("title")||"").attr("title","")},o.prototype.hasContent=function(){return this.getTitle()},o.prototype.getPosition=function(e){e=e||this.$element;var o=e[0],i="BODY"==o.tagName,n=o.getBoundingClientRect();null==n.width&&(n=t.extend({},n,{width:n.right-n.left,height:n.bottom-n.top}));var s=i?{top:0,left:0}:e.offset(),r={scroll:i?document.documentElement.scrollTop||document.body.scrollTop:e.scrollTop()},p=i?{width:t(window).width(),height:t(window).height()}:null;return t.extend({},n,r,p,s)},o.prototype.getCalculatedOffset=function(t,e,o,i){return"bottom"==t?{top:e.top+e.height,left:e.left+e.width/2-o/2}:"top"==t?{top:e.top-i,left:e.left+e.width/2-o/2}:"left"==t?{top:e.top+e.height/2-i/2,left:e.left-o}:{top:e.top+e.height/2-i/2,left:e.left+e.width}},o.prototype.getViewportAdjustedDelta=function(t,e,o,i){var n={top:0,left:0};if(!this.$viewport)return n;var s=this.options.viewport&&this.options.viewport.padding||0,r=this.getPosition(this.$viewport);if(/right|left/.test(t)){var p=e.top-s-r.scroll,a=e.top+s-r.scroll+i;p<r.top?n.top=r.top-p:a>r.top+r.height&&(n.top=r.top+r.height-a)}else{var l=e.left-s,h=e.left+s+o;l<r.left?n.left=r.left-l:h>r.right&&(n.left=r.left+r.width-h)}return n},o.prototype.getTitle=function(){var t,e=this.$element,o=this.options;return t=e.attr("data-original-title")||("function"==typeof o.title?o.title.call(e[0]):o.title)},o.prototype.getUID=function(t){do t+=~~(1e6*Math.random());while(document.getElementById(t));return t},o.prototype.tip=function(){if(!this.$tip&&(this.$tip=t(this.options.template),1!=this.$tip.length))throw new Error(this.type+" `template` option must consist of exactly 1 top-level element!");return this.$tip},o.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".tooltip-arrow")},o.prototype.enable=function(){this.enabled=!0},o.prototype.disable=function(){this.enabled=!1},o.prototype.toggleEnabled=function(){this.enabled=!this.enabled},o.prototype.toggle=function(e){var o=this;e&&(o=t(e.currentTarget).data("bs."+this.type),o||(o=new this.constructor(e.currentTarget,this.getDelegateOptions()),t(e.currentTarget).data("bs."+this.type,o))),e?(o.inState.click=!o.inState.click,o.isInStateTrue()?o.enter(o):o.leave(o)):o.tip().hasClass("in")?o.leave(o):o.enter(o)},o.prototype.destroy=function(){var t=this;clearTimeout(this.timeout),this.hide(function(){t.$element.off("."+t.type).removeData("bs."+t.type),t.$tip&&t.$tip.detach(),t.$tip=null,t.$arrow=null,t.$viewport=null})};var i=t.fn.tooltip;t.fn.tooltip=e,t.fn.tooltip.Constructor=o,t.fn.tooltip.noConflict=function(){return t.fn.tooltip=i,this}}(jQuery),+function(t){"use strict";function e(e){return this.each(function(){var i=t(this),n=i.data("bs.popover"),s="object"==typeof e&&e;(n||!/destroy|hide/.test(e))&&(n||i.data("bs.popover",n=new o(this,s)),"string"==typeof e&&n[e]())})}var o=function(t,e){this.init("popover",t,e)};if(!t.fn.tooltip)throw new Error("Popover requires tooltip.js");o.VERSION="3.3.5",o.DEFAULTS=t.extend({},t.fn.tooltip.Constructor.DEFAULTS,{placement:"right",trigger:"click",content:"",template:'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'}),o.prototype=t.extend({},t.fn.tooltip.Constructor.prototype),o.prototype.constructor=o,o.prototype.getDefaults=function(){return o.DEFAULTS},o.prototype.setContent=function(){var t=this.tip(),e=this.getTitle(),o=this.getContent();t.find(".popover-title")[this.options.html?"html":"text"](e),t.find(".popover-content").children().detach().end()[this.options.html?"string"==typeof o?"html":"append":"text"](o),t.removeClass("fade top bottom left right in"),t.find(".popover-title").html()||t.find(".popover-title").hide()},o.prototype.hasContent=function(){return this.getTitle()||this.getContent()},o.prototype.getContent=function(){var t=this.$element,e=this.options;return t.attr("data-content")||("function"==typeof e.content?e.content.call(t[0]):e.content)},o.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".arrow")};var i=t.fn.popover;t.fn.popover=e,t.fn.popover.Constructor=o,t.fn.popover.noConflict=function(){return t.fn.popover=i,this}}(jQuery);


/* ---------------------------------------------------- */
/* PLURAL                                               */
/* ---------------------------------------------------- */
String.prototype.plural=function(){if(/\d/g.test(this.substr(this.length-1)))return this;var s,e={"(quiz)$":"$1zes","^(ox)$":"$1en","([m|l])ouse$":"$1ice","(matr|vert|ind)ix|ex$":"$1ices","(x|ch|ss|sh)$":"$1es","([^aeiouy]|qu)y$":"$1ies","(hive)$":"$1s","(?:([^f])fe|([lr])f)$":"$1$2ves","(shea|lea|loa|thie)f$":"$1ves",sis$:"ses","([ti])um$":"$1a","(tomat|potat|ech|her|vet)o$":"$1oes","(bu)s$":"$1ses","(alias)$":"$1es","(octop)us$":"$1i","(ax|test)is$":"$1es","(us)$":"$1es","([^s]+)$":"$1s"};for(s in e){var $=new RegExp(s,"i");if($.test(this))return this.replace($,e[s])}return this};



/* ---------------------------------------------------- */
/* URL PARAM PLUGIN                                     */
/* ---------------------------------------------------- */
$.urlParam=function(e){var n=new RegExp("[?&]"+e+"=([^&#]*)").exec(window.location.href);return null==n?null:decodeURI(n[1])||0};


/* ---------------------------------------------------- */
/* URL POLYFILL                                         */
/* ---------------------------------------------------- */
(function(t){var e=function(){try{return!!Symbol.iterator}catch(e){return false}};var r=e();var n=function(t){var e={next:function(){var e=t.shift();return{done:e===void 0,value:e}}};if(r){e[Symbol.iterator]=function(){return e}}return e};var i=function(e){return encodeURIComponent(e).replace(/%20/g,"+")};var o=function(e){return decodeURIComponent(String(e).replace(/\+/g," "))};var a=function(){var a=function(e){Object.defineProperty(this,"_entries",{writable:true,value:{}});var t=typeof e;if(t==="undefined"){}else if(t==="string"){if(e!==""){this._fromString(e)}}else if(e instanceof a){var r=this;e.forEach(function(e,t){r.append(t,e)})}else if(e!==null&&t==="object"){if(Object.prototype.toString.call(e)==="[object Array]"){for(var n=0;n<e.length;n++){var i=e[n];if(Object.prototype.toString.call(i)==="[object Array]"||i.length!==2){this.append(i[0],i[1])}else{throw new TypeError("Expected [string, any] as entry at index "+n+" of URLSearchParams's input")}}}else{for(var o in e){if(e.hasOwnProperty(o)){this.append(o,e[o])}}}}else{throw new TypeError("Unsupported input's type for URLSearchParams")}};var e=a.prototype;e.append=function(e,t){if(e in this._entries){this._entries[e].push(String(t))}else{this._entries[e]=[String(t)]}};e.delete=function(e){delete this._entries[e]};e.get=function(e){return e in this._entries?this._entries[e][0]:null};e.getAll=function(e){return e in this._entries?this._entries[e].slice(0):[]};e.has=function(e){return e in this._entries};e.set=function(e,t){this._entries[e]=[String(t)]};e.forEach=function(e,t){var r;for(var n in this._entries){if(this._entries.hasOwnProperty(n)){r=this._entries[n];for(var i=0;i<r.length;i++){e.call(t,r[i],n,this)}}}};e.keys=function(){var r=[];this.forEach(function(e,t){r.push(t)});return n(r)};e.values=function(){var t=[];this.forEach(function(e){t.push(e)});return n(t)};e.entries=function(){var r=[];this.forEach(function(e,t){r.push([t,e])});return n(r)};if(r){e[Symbol.iterator]=e.entries}e.toString=function(){var r=[];this.forEach(function(e,t){r.push(i(t)+"="+i(e))});return r.join("&")};t.URLSearchParams=a};var s=function(){try{var e=t.URLSearchParams;return new e("?a=1").toString()==="a=1"&&typeof e.prototype.set==="function"&&typeof e.prototype.entries==="function"}catch(e){return false}};if(!s()){a()}var f=t.URLSearchParams.prototype;if(typeof f.sort!=="function"){f.sort=function(){var r=this;var n=[];this.forEach(function(e,t){n.push([t,e]);if(!r._entries){r.delete(t)}});n.sort(function(e,t){if(e[0]<t[0]){return-1}else if(e[0]>t[0]){return+1}else{return 0}});if(r._entries){r._entries={}}for(var e=0;e<n.length;e++){this.append(n[e][0],n[e][1])}}}if(typeof f._fromString!=="function"){Object.defineProperty(f,"_fromString",{enumerable:false,configurable:false,writable:false,value:function(e){if(this._entries){this._entries={}}else{var r=[];this.forEach(function(e,t){r.push(t)});for(var t=0;t<r.length;t++){this.delete(r[t])}}e=e.replace(/^\?/,"");var n=e.split("&");var i;for(var t=0;t<n.length;t++){i=n[t].split("=");this.append(o(i[0]),i.length>1?o(i[1]):"")}}})}})(typeof global!=="undefined"?global:typeof window!=="undefined"?window:typeof self!=="undefined"?self:this);(function(u){var e=function(){try{var e=new u.URL("b","http://a");e.pathname="c d";return e.href==="http://a/c%20d"&&e.searchParams}catch(e){return false}};var t=function(){var t=u.URL;var e=function(e,t){if(typeof e!=="string")e=String(e);if(t&&typeof t!=="string")t=String(t);var r=document,n;if(t&&(u.location===void 0||t!==u.location.href)){t=t.toLowerCase();r=document.implementation.createHTMLDocument("");n=r.createElement("base");n.href=t;r.head.appendChild(n);try{if(n.href.indexOf(t)!==0)throw new Error(n.href)}catch(e){throw new Error("URL unable to set base "+t+" due to "+e)}}var i=r.createElement("a");i.href=e;if(n){r.body.appendChild(i);i.href=i.href}var o=r.createElement("input");o.type="url";o.value=e;if(i.protocol===":"||!/:/.test(i.href)||!o.checkValidity()&&!t){throw new TypeError("Invalid URL")}Object.defineProperty(this,"_anchorElement",{value:i});var a=new u.URLSearchParams(this.search);var s=true;var f=true;var c=this;["append","delete","set"].forEach(function(e){var t=a[e];a[e]=function(){t.apply(a,arguments);if(s){f=false;c.search=a.toString();f=true}}});Object.defineProperty(this,"searchParams",{value:a,enumerable:true});var h=void 0;Object.defineProperty(this,"_updateSearchParams",{enumerable:false,configurable:false,writable:false,value:function(){if(this.search!==h){h=this.search;if(f){s=false;this.searchParams._fromString(this.search);s=true}}}})};var r=e.prototype;var n=function(t){Object.defineProperty(r,t,{get:function(){return this._anchorElement[t]},set:function(e){this._anchorElement[t]=e},enumerable:true})};["hash","host","hostname","port","protocol"].forEach(function(e){n(e)});Object.defineProperty(r,"search",{get:function(){return this._anchorElement["search"]},set:function(e){this._anchorElement["search"]=e;this._updateSearchParams()},enumerable:true});Object.defineProperties(r,{toString:{get:function(){var e=this;return function(){return e.href}}},href:{get:function(){return this._anchorElement.href.replace(/\?$/,"")},set:function(e){this._anchorElement.href=e;this._updateSearchParams()},enumerable:true},pathname:{get:function(){return this._anchorElement.pathname.replace(/(^\/?)/,"/")},set:function(e){this._anchorElement.pathname=e},enumerable:true},origin:{get:function(){var e={"http:":80,"https:":443,"ftp:":21}[this._anchorElement.protocol];var t=this._anchorElement.port!=e&&this._anchorElement.port!=="";return this._anchorElement.protocol+"//"+this._anchorElement.hostname+(t?":"+this._anchorElement.port:"")},enumerable:true},password:{get:function(){return""},set:function(e){},enumerable:true},username:{get:function(){return""},set:function(e){},enumerable:true}});e.createObjectURL=function(e){return t.createObjectURL.apply(t,arguments)};e.revokeObjectURL=function(e){return t.revokeObjectURL.apply(t,arguments)};u.URL=e};if(!e()){t()}if(u.location!==void 0&&!("origin"in u.location)){var r=function(){return u.location.protocol+"//"+u.location.hostname+(u.location.port?":"+u.location.port:"")};try{Object.defineProperty(u.location,"origin",{get:r,enumerable:true})}catch(e){setInterval(function(){u.location.origin=r()},100)}}})(typeof global!=="undefined"?global:typeof window!=="undefined"?window:typeof self!=="undefined"?self:this);



/* ---------------------------------------------------- */
/* NODE TEXT                                            */
/* ---------------------------------------------------- */
$.fn.nodeText = function(){return this.clone().children().remove().end().text();};

// debounce + th.
(function(a){a.extend({debounce:function(a,b,c,d){3==arguments.length&&"boolean"!=typeof c&&(d=c,c=!1);var e;return function(){var f=arguments;d=d||this,c&&!e&&a.apply(d,f),clearTimeout(e),e=setTimeout(function(){c||a.apply(d,f),e=null},b)}},throttle:function(a,b,c){var d,e,f;return function(){e=arguments,f=!0,c=c||this,d||function(){f?(a.apply(c,e),f=!1,d=setTimeout(arguments.callee,b)):d=null}()}}})})(jQuery);
