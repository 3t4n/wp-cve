;(function($) {

    "use strict";

    /* ---------------------------------------------------- */
    /* Setup Ace Editor.                                    */
    /* ---------------------------------------------------- */
    ace.config.set("basePath",window.aceEditorBase);
    ace.require("ace/ext/language_tools");
    var editor = ace.edit("cssData");
    editor.getSession().setMode("ace/mode/css");
    editor.setTheme("ace/theme/twilight");
    editor.getSession().setUseWrapMode(true);
    editor.$blockScrolling = Infinity;
    editor.setShowPrintMargin(false);
    editor.session.setUseWorker(false);

    // enable autocompletion and snippets
    editor.setOptions({
        enableBasicAutocompletion: true,
        enableSnippets: false,
        enableLiveAutocompletion: true
    });


    // Set font size to editor
    editor.setOptions({
        fontSize: "17px"
    });


    // All Yellow Pencil Functions.
    window.yellow_pencil_main = function() {

            // https://github.com/rafaelcaricio/gradient-parser/
            // Copyright (c) 2014 Rafael Caricio. All rights reserved.
            var GradientParser=GradientParser||{};GradientParser.parse=function(){var result;function c(a){var c=new Error(b+": "+a);throw c.source=b,c}function d(){var a=e();return b.length>0&&c("Invalid input not EOF"),a}function e(){return t(f)}function f(){return g("linear-gradient",a.linearGradient,i)||g("repeating-linear-gradient",a.repeatingLinearGradient,i)||g("radial-gradient",a.radialGradient,l)||g("repeating-radial-gradient",a.repeatingRadialGradient,l)}function g(b,d,e){return h(d,function(d){var f=e();return f&&(F(a.comma)||c("Missing comma before color stops")),{type:b,orientation:f,colorStops:t(u)}})}function h(b,d){var e=F(b);if(e)return F(a.startCall)||c("Missing ("),result=d(e),F(a.endCall)||c("Missing )"),result}function i(){return j()||k()}function j(){return E("directional",a.sideOrCorner,1)}function k(){return E("angular",a.angleValue,1)}function l(){var c,e,d=m();return d&&(c=[],c.push(d),e=b,F(a.comma)&&(d=m(),d?c.push(d):b=e)),c}function m(){var a=n()||o();if(a)a.at=q();else{var b=p();if(b){a=b;var c=q();c&&(a.at=c)}else{var d=r();d&&(a={type:"default-radial",at:d})}}return a}function n(){var a=E("shape",/^(circle)/i,0);return a&&(a.style=D()||p()),a}function o(){var a=E("shape",/^(ellipse)/i,0);return a&&(a.style=B()||p()),a}function p(){return E("extent-keyword",a.extentKeywords,1)}function q(){if(E("position",/^at/,0)){var a=r();return a||c("Missing positioning value"),a}}function r(){var a=s();if(a.x||a.y)return{type:"position",value:a}}function s(){return{x:B(),y:B()}}function t(b){var d=b(),e=[];if(d)for(e.push(d);F(a.comma);)d=b(),d?e.push(d):c("One extra comma");return e}function u(){var a=v();return a||c("Expected color definition"),a.length=B(),a}function v(){return x()||z()||y()||w()}function w(){return E("literal",a.literalColor,0)}function x(){return E("hex",a.hexColor,1)}function y(){return h(a.rgbColor,function(){return{type:"rgb",value:t(A)}})}function z(){return h(a.rgbaColor,function(){return{type:"rgba",value:t(A)}})}function A(){return F(a.number)[1]}function B(){return E("%",a.percentageValue,1)||C()||D()}function C(){return E("position-keyword",a.positionKeywords,1)}function D(){return E("px",a.pixelValue,1)||E("em",a.emValue,1)}function E(a,b,c){var d=F(b);if(d)return{type:a,value:d[c]}}function F(a){var c,d;return d=/^[\n\r\t\s]+/.exec(b),d&&G(d[0].length),c=a.exec(b),c&&G(c[0].length),c}function G(a){b=b.substr(a)}var a={linearGradient:/^(\-(webkit|o|ms|moz)\-)?(linear\-gradient)/i,repeatingLinearGradient:/^(\-(webkit|o|ms|moz)\-)?(repeating\-linear\-gradient)/i,radialGradient:/^(\-(webkit|o|ms|moz)\-)?(radial\-gradient)/i,repeatingRadialGradient:/^(\-(webkit|o|ms|moz)\-)?(repeating\-radial\-gradient)/i,sideOrCorner:/^to (left (top|bottom)|right (top|bottom)|left|right|top|bottom)/i,extentKeywords:/^(closest\-side|closest\-corner|farthest\-side|farthest\-corner|contain|cover)/,positionKeywords:/^(left|center|right|top|bottom)/i,pixelValue:/^(-?(([0-9]*\.[0-9]+)|([0-9]+\.?)))px/,percentageValue:/^(-?(([0-9]*\.[0-9]+)|([0-9]+\.?)))\%/,emValue:/^(-?(([0-9]*\.[0-9]+)|([0-9]+\.?)))em/,angleValue:/^(-?(([0-9]*\.[0-9]+)|([0-9]+\.?)))deg/,startCall:/^\(/,endCall:/^\)/,comma:/^,/,hexColor:/^\#([0-9a-fA-F]+)/,literalColor:/^([a-zA-Z]+)/,rgbColor:/^rgb/i,rgbaColor:/^rgba/i,number:/^(([0-9]*\.[0-9]+)|([0-9]+\.?))/},b="";return function(a){return b=a.toString(),d()}}();


            /*!
            * clipboard.js v1.7.1
            * https://zenorocha.github.io/clipboard.js
            *
            * Licensed MIT © Zeno Rocha
            */
            !function(t){if("object"==typeof exports&&"undefined"!=typeof module)module.exports=t();else if("function"==typeof define&&define.amd)define([],t);else{var e;e="undefined"!=typeof window?window:"undefined"!=typeof global?global:"undefined"!=typeof self?self:this,e.Clipboard=t()}}(function(){var t,e,n;return function t(e,n,o){function i(a,c){if(!n[a]){if(!e[a]){var l="function"==typeof require&&require;if(!c&&l)return l(a,!0);if(r)return r(a,!0);var s=new Error("Cannot find module '"+a+"'");throw s.code="MODULE_NOT_FOUND",s}var u=n[a]={exports:{}};e[a][0].call(u.exports,function(t){var n=e[a][1][t];return i(n||t)},u,u.exports,t,e,n,o)}return n[a].exports}for(var r="function"==typeof require&&require,a=0;a<o.length;a++)i(o[a]);return i}({1:[function(t,e,n){function o(t,e){for(;t&&t.nodeType!==i;){if("function"==typeof t.matches&&t.matches(e))return t;t=t.parentNode}}var i=9;if("undefined"!=typeof Element&&!Element.prototype.matches){var r=Element.prototype;r.matches=r.matchesSelector||r.mozMatchesSelector||r.msMatchesSelector||r.oMatchesSelector||r.webkitMatchesSelector}e.exports=o},{}],2:[function(t,e,n){function o(t,e,n,o,r){var a=i.apply(this,arguments);return t.addEventListener(n,a,r),{destroy:function(){t.removeEventListener(n,a,r)}}}function i(t,e,n,o){return function(n){n.delegateTarget=r(n.target,e),n.delegateTarget&&o.call(t,n)}}var r=t("./closest");e.exports=o},{"./closest":1}],3:[function(t,e,n){n.node=function(t){return void 0!==t&&t instanceof HTMLElement&&1===t.nodeType},n.nodeList=function(t){var e=Object.prototype.toString.call(t);return void 0!==t&&("[object NodeList]"===e||"[object HTMLCollection]"===e)&&"length"in t&&(0===t.length||n.node(t[0]))},n.string=function(t){return"string"==typeof t||t instanceof String},n.fn=function(t){return"[object Function]"===Object.prototype.toString.call(t)}},{}],4:[function(t,e,n){function o(t,e,n){if(!t&&!e&&!n)throw new Error("Missing required arguments");if(!c.string(e))throw new TypeError("Second argument must be a String");if(!c.fn(n))throw new TypeError("Third argument must be a Function");if(c.node(t))return i(t,e,n);if(c.nodeList(t))return r(t,e,n);if(c.string(t))return a(t,e,n);throw new TypeError("First argument must be a String, HTMLElement, HTMLCollection, or NodeList")}function i(t,e,n){return t.addEventListener(e,n),{destroy:function(){t.removeEventListener(e,n)}}}function r(t,e,n){return Array.prototype.forEach.call(t,function(t){t.addEventListener(e,n)}),{destroy:function(){Array.prototype.forEach.call(t,function(t){t.removeEventListener(e,n)})}}}function a(t,e,n){return l(document.body,t,e,n)}var c=t("./is"),l=t("delegate");e.exports=o},{"./is":3,delegate:2}],5:[function(t,e,n){function o(t){var e;if("SELECT"===t.nodeName)t.focus(),e=t.value;else if("INPUT"===t.nodeName||"TEXTAREA"===t.nodeName){var n=t.hasAttribute("readonly");n||t.setAttribute("readonly",""),t.select(),t.setSelectionRange(0,t.value.length),n||t.removeAttribute("readonly"),e=t.value}else{t.hasAttribute("contenteditable")&&t.focus();var o=window.getSelection(),i=document.createRange();i.selectNodeContents(t),o.removeAllRanges(),o.addRange(i),e=o.toString()}return e}e.exports=o},{}],6:[function(t,e,n){function o(){}o.prototype={on:function(t,e,n){var o=this.e||(this.e={});return(o[t]||(o[t]=[])).push({fn:e,ctx:n}),this},once:function(t,e,n){function o(){i.off(t,o),e.apply(n,arguments)}var i=this;return o._=e,this.on(t,o,n)},emit:function(t){var e=[].slice.call(arguments,1),n=((this.e||(this.e={}))[t]||[]).slice(),o=0,i=n.length;for(o;o<i;o++)n[o].fn.apply(n[o].ctx,e);return this},off:function(t,e){var n=this.e||(this.e={}),o=n[t],i=[];if(o&&e)for(var r=0,a=o.length;r<a;r++)o[r].fn!==e&&o[r].fn._!==e&&i.push(o[r]);return i.length?n[t]=i:delete n[t],this}},e.exports=o},{}],7:[function(e,n,o){!function(i,r){if("function"==typeof t&&t.amd)t(["module","select"],r);else if(void 0!==o)r(n,e("select"));else{var a={exports:{}};r(a,i.select),i.clipboardAction=a.exports}}(this,function(t,e){"use strict";function n(t){return t&&t.__esModule?t:{default:t}}function o(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}var i=n(e),r="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},a=function(){function t(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}return function(e,n,o){return n&&t(e.prototype,n),o&&t(e,o),e}}(),c=function(){function t(e){o(this,t),this.resolveOptions(e),this.initSelection()}return a(t,[{key:"resolveOptions",value:function t(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};this.action=e.action,this.container=e.container,this.emitter=e.emitter,this.target=e.target,this.text=e.text,this.trigger=e.trigger,this.selectedText=""}},{key:"initSelection",value:function t(){this.text?this.selectFake():this.target&&this.selectTarget()}},{key:"selectFake",value:function t(){var e=this,n="rtl"==document.documentElement.getAttribute("dir");this.removeFake(),this.fakeHandlerCallback=function(){return e.removeFake()},this.fakeHandler=this.container.addEventListener("click",this.fakeHandlerCallback)||!0,this.fakeElem=document.createElement("textarea"),this.fakeElem.style.fontSize="12pt",this.fakeElem.style.border="0",this.fakeElem.style.padding="0",this.fakeElem.style.margin="0",this.fakeElem.style.position="absolute",this.fakeElem.style[n?"right":"left"]="-9999px";var o=window.pageYOffset||document.documentElement.scrollTop;this.fakeElem.style.top=o+"px",this.fakeElem.setAttribute("readonly",""),this.fakeElem.value=this.text,this.container.appendChild(this.fakeElem),this.selectedText=(0,i.default)(this.fakeElem),this.copyText()}},{key:"removeFake",value:function t(){this.fakeHandler&&(this.container.removeEventListener("click",this.fakeHandlerCallback),this.fakeHandler=null,this.fakeHandlerCallback=null),this.fakeElem&&(this.container.removeChild(this.fakeElem),this.fakeElem=null)}},{key:"selectTarget",value:function t(){this.selectedText=(0,i.default)(this.target),this.copyText()}},{key:"copyText",value:function t(){var e=void 0;try{e=document.execCommand(this.action)}catch(t){e=!1}this.handleResult(e)}},{key:"handleResult",value:function t(e){this.emitter.emit(e?"success":"error",{action:this.action,text:this.selectedText,trigger:this.trigger,clearSelection:this.clearSelection.bind(this)})}},{key:"clearSelection",value:function t(){this.trigger&&this.trigger.focus(),window.getSelection().removeAllRanges()}},{key:"destroy",value:function t(){this.removeFake()}},{key:"action",set:function t(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"copy";if(this._action=e,"copy"!==this._action&&"cut"!==this._action)throw new Error('Invalid "action" value, use either "copy" or "cut"')},get:function t(){return this._action}},{key:"target",set:function t(e){if(void 0!==e){if(!e||"object"!==(void 0===e?"undefined":r(e))||1!==e.nodeType)throw new Error('Invalid "target" value, use a valid Element');if("copy"===this.action&&e.hasAttribute("disabled"))throw new Error('Invalid "target" attribute. Please use "readonly" instead of "disabled" attribute');if("cut"===this.action&&(e.hasAttribute("readonly")||e.hasAttribute("disabled")))throw new Error('Invalid "target" attribute. You can\'t cut text from elements with "readonly" or "disabled" attributes');this._target=e}},get:function t(){return this._target}}]),t}();t.exports=c})},{select:5}],8:[function(e,n,o){!function(i,r){if("function"==typeof t&&t.amd)t(["module","./clipboard-action","tiny-emitter","good-listener"],r);else if(void 0!==o)r(n,e("./clipboard-action"),e("tiny-emitter"),e("good-listener"));else{var a={exports:{}};r(a,i.clipboardAction,i.tinyEmitter,i.goodListener),i.clipboard=a.exports}}(this,function(t,e,n,o){"use strict";function i(t){return t&&t.__esModule?t:{default:t}}function r(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function a(t,e){if(!t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!e||"object"!=typeof e&&"function"!=typeof e?t:e}function c(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function, not "+typeof e);t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,enumerable:!1,writable:!0,configurable:!0}}),e&&(Object.setPrototypeOf?Object.setPrototypeOf(t,e):t.__proto__=e)}function l(t,e){var n="data-clipboard-"+t;if(e.hasAttribute(n))return e.getAttribute(n)}var s=i(e),u=i(n),f=i(o),d="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},h=function(){function t(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}return function(e,n,o){return n&&t(e.prototype,n),o&&t(e,o),e}}(),p=function(t){function e(t,n){r(this,e);var o=a(this,(e.__proto__||Object.getPrototypeOf(e)).call(this));return o.resolveOptions(n),o.listenClick(t),o}return c(e,t),h(e,[{key:"resolveOptions",value:function t(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};this.action="function"==typeof e.action?e.action:this.defaultAction,this.target="function"==typeof e.target?e.target:this.defaultTarget,this.text="function"==typeof e.text?e.text:this.defaultText,this.container="object"===d(e.container)?e.container:document.body}},{key:"listenClick",value:function t(e){var n=this;this.listener=(0,f.default)(e,"click",function(t){return n.onClick(t)})}},{key:"onClick",value:function t(e){var n=e.delegateTarget||e.currentTarget;this.clipboardAction&&(this.clipboardAction=null),this.clipboardAction=new s.default({action:this.action(n),target:this.target(n),text:this.text(n),container:this.container,trigger:n,emitter:this})}},{key:"defaultAction",value:function t(e){return l("action",e)}},{key:"defaultTarget",value:function t(e){var n=l("target",e);if(n)return document.querySelector(n)}},{key:"defaultText",value:function t(e){return l("text",e)}},{key:"destroy",value:function t(){this.listener.destroy(),this.clipboardAction&&(this.clipboardAction.destroy(),this.clipboardAction=null)}}],[{key:"isSupported",value:function t(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:["copy","cut"],n="string"==typeof e?[e]:e,o=!!document.queryCommandSupported;return n.forEach(function(t){o=o&&!!document.queryCommandSupported(t)}),o}}]),e}(u.default);t.exports=p})},{"./clipboard-action":7,"good-listener":4,"tiny-emitter":6}]},{},[8])(8)});

            // URL param plugin
            $.urlParam=function(e){var n=new RegExp("[?&]"+e+"=([^&#]*)").exec(window.location.href);return null==n?null:decodeURI(n[1])||0};


            /* ---------------------------------------------------- */
            /* Fix multiple load problem.                           */
            /* ---------------------------------------------------- */
            if ($("body").hasClass("yellow-pencil-ready")){
                return false;
            }


            /* ---------------------------------------------------- */
            /* Windows                                              */
            /* ---------------------------------------------------- */
            window.setSelector = false;
            window.leftbarWidth = 46;
            window.separator = ' ';
            window.minCroppedSelector = false;
            window.YellowDelay = 5;
            window.Yellow2Delay = 10;
            window.parentItems = '';


            /* ---------------------------------------------------- */
            /* Setup Default Varriables                             */
            /* ---------------------------------------------------- */
            var iframe = $('#iframe').contents();
            var iframeBody = iframe.find("body");
            var body = $(document.body).add(iframeBody);
            var mainDocument = $(document).add(iframe);
            var mainBody = $(document.body);

            // puse js
            var iframejs = document.getElementById('iframe');
            var iframeContentWindow = (iframejs.contentWindow || iframejs.contentDocument);
            iframejs = iframeContentWindow.document;


            // Auto Load customize type
            if($.urlParam('wyp_load_popup') != null){

                // Load customize type iframe after a white.
                setTimeout(function(){
                    update_type_frame();
                    $("#wyp-current-page").trigger("click");
                }, 10);

            }else{

                // UX, Don't make wait the user for image upload, and customize type iframes.
                setTimeout(function(){

                    // Load customize type iframe after a white.
                    update_type_frame();

                }, 5000);

            }


            /* ---------------------------------------------------- */
            /* Adding wyp-animating class to all animating elements  */
            /* ---------------------------------------------------- */
            iframe.find(window.basic_not_selector).on('animationend webkitAnimationEnd oanimationend MSAnimationEnd',function(){

                // Stop if any yp animation tool works
                if(body.hasClass("wyp-anim-creator") || is_animation_manager()){
                    return false;
                }

                var element = $(this);

                // remove animating class.
                if(element.hasClass("wyp-animating")){
                    element.removeClass("wyp-animating");
                }

                // Set outline selected style if selected element has animating.
                if(element.hasClass("wyp-selected") && is_content_selected()){
                    body.removeClass("wyp-has-transform");
                    draw();
                }

                return false;

            });



            /* ---------------------------------------------------- */
            /* Wireframe Button                                     */
            /* ---------------------------------------------------- */
            $(".wyp-wireframe-btn").click(function(){

                // Disable picker on color close.
                mainBody.removeClass("wyp-element-picker-active");
                $(".wyp-element-picker").removeClass("active");

                body.toggleClass("wyp-wireframe-mode");
                $(".editor-panel-list > li.active > h3").trigger("click");
                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Rotate the logo every 1 min                          */
            /* ---------------------------------------------------- */
            setInterval(function(){
                $(".yellow-pencil-logo").toggleClass("wyp-logo-play");
            },80000);


            /* ---------------------------------------------------- */
            /* Check Undoable / Redoable                            */
            /* ---------------------------------------------------- */
            check_undoable_history();


            /* ---------------------------------------------------- */
            /* Animation Manager: Play                              */
            /* ---------------------------------------------------- */
            $(".wyp-anim-control-play").on("click",function(){

                if($(this).hasClass("active")){
                    return false;
                }

                body.addClass("wyp-animate-manager-playing wyp-clean-look wyp-hide-borders-now");

                // Find largest line for play/stop.
                var maxWidth = Math.max.apply( null, $( '.wyp-anim-process-inner' ).map( function (){
                    return $( this ).outerWidth( true );
                }).get() );


                var s = (parseFloat(maxWidth)/100);
                $("#wyp-animate-helper").html("@-webkit-keyframes playingBorder{from{left: 0px;}to{left:"+maxWidth+"px;}}@keyframes playingBorder{from{left: 0px;}to{left:"+maxWidth+"px;}}");

                $(".wyp-anim-playing-border").css("animation-duration",s+"s").css("-webkit-animation-duration",s+"s").addClass("active");

                $(this).addClass("active");

                var S_inMS = (s*1000);
                clear_animation_timer();

                window.animationTimer3 = setTimeout(function(){
                    $(".wyp-anim-control-pause").trigger("click");
                },S_inMS);


                // Playing over width
                $(".wyp-anim-playing-over").css("width",maxWidth+$(window).width());


                // Play animations
                the_editor_data().find('[data-rule="animation-name"]').each(function(i){

                    var data = $(this).html();

                    var selector = esc_data_selector(data);

                    selector = selector.replace(".yp_hover","").replace(".yp_focus","").replace(".yp_click","").replace(".yp_onscreen","");

                    iframe.find(selector).each(function(){
                        $(this).addClass("yp_hover yp_focus yp_click yp_onscreen");
                    });

                });

                // Counter
                //wyp-counter-min
                //wyp-counter-second
                //wyp-counter-ms
                var min = 0;
                window.animMinC = setInterval(function(){

                   // min
                   min = min+1;if(ms == 59){ms = 0;}

                   var result = min;
                   if(min < 10){
                   result = "0"+min;
                   }

                   $(".wyp-counter-min").text(result);

                },60000);

                var second = 0;
                window.animSecC = setInterval(function(){

                   // Sc
                   second = second+1;

                   var result = second;
                   if(second < 10){
                   result = "0"+second;
                   }
                   $(".wyp-counter-second").text(result);

                },1000);

                var ms = 0;
                window.animMsC = setInterval(function(){

                   // Ms
                   ms = ms+1;if(ms == 99){ms = 0;}

                   var result = ms;
                   if(ms < 10){
                   result = "0"+ms;
                   }
                   $(".wyp-counter-ms").text(result);

                },1);

            });


            /* ---------------------------------------------------- */
            /* Animation Manager: Pause                             */
            /* ---------------------------------------------------- */
            $(".wyp-anim-control-pause").on("click",function(){

                clearTimeout(window.wyp_anim_player);

                $(".wyp-anim-playing-border").removeClass("active");
                $(".wyp-anim-control-play").removeClass("active");

                    // Pause animations
                    the_editor_data().find('[data-rule="animation-name"]').each(function(i){

                        var selector = esc_data_selector($(this).html());

                        selector = selector.replace(".yp_hover","").replace(".yp_focus","").replace(".yp_click","").replace(".yp_onscreen","");

                    iframe.find(selector).each(function(){
                        $(this).removeClass("yp_hover yp_focus yp_click yp_onscreen");
                    });

                });

                body.removeClass("wyp-animate-manager-playing wyp-clean-look wyp-hide-borders-now");

                //wyp-counter-min
                //wyp-counter-second
                //wyp-counter-ms
                $(".wyp-counter-min").text("00");
                $(".wyp-counter-second").text("00");
                $(".wyp-counter-ms").text("00");
                clearInterval(window.animMinC);
                clearInterval(window.animSecC);
                clearInterval(window.animMsC);

            });


            /* ---------------------------------------------------- */
            /* Close Animation Manager                          */
            /* ---------------------------------------------------- */
            $(".wyp-anim-control-close,.wyp-visual-editor-link").on("click",function(){
                $(".animation-manager-btn").trigger("click");
            });


            /* ---------------------------------------------------- */
            /* Open Animation Manager                               */
            /* ---------------------------------------------------- */
            $(".animation-manager-btn").on("click",function(){

                // Disable picker on color close.
                mainBody.removeClass("wyp-element-picker-active");
                $(".wyp-element-picker").removeClass("active");

                 body.toggleClass("wyp-animate-manager-active");
                 $(".wyp-animate-manager").toggle();
                 $(".wyp-anim-control-pause").trigger("click");
                 if(!$(this).hasClass("active")){

                    // CSS To Data.
                    if (mainBody.hasClass("wyp-need-to-process")) {
                        process(false);
                    }

                    animation_manager();

                    // Find largest line for play/stop.
                    var maxWidth = Math.max.apply( null, $( '.wyp-anim-process-inner' ).map( function (){
                        return $( this ).outerWidth( true );
                    }).get() );

                    // Always add +$(window).width() to animate bar width on start.
                    $(".wyp-anim-process-bar-area").width(maxWidth+$(window).width());

                 }

                if($(".animation-option.active").length > 0){
                    $(".animation-option.active h3").trigger("click");
                    $(".animation-option.active").removeAttr("data-loaded");
                }

                insert_default_options();

                draw();

            });


            /* ---------------------------------------------------- */
            /* Animation Manager: delete animate                    */
            /* ---------------------------------------------------- */
            $(document).on("mouseenter", ".wyp-control-trash", function() {
                $(this).parent().tooltip('hide');
            });


            /* ---------------------------------------------------- */
            /* Animation manager: delete animate                    */
            /* ---------------------------------------------------- */
            $(document).on("click", ".wyp-control-trash", function() {

                var that = $(this);

                swal({
                  title: "You are sure?",
                  showCancelButton: true,
                  confirmButtonText: "Delete Animate",
                  closeOnConfirm: true,
                  animation: false,
                  customClass: "editor-style",
                },function(){

                    that.parent(".wyp-anim-process-bar").prev(".wyp-anim-process-bar-delay").remove();
                    that.parent(".wyp-anim-process-bar").remove();

                    body.addClass("wyp-anim-removing");

                    $(".wyp-delay-zero").each(function(){

                        var allLeft = $(".wyp-anim-process-inner").offset().left-5;
                        var left = $(this).next(".wyp-anim-process-bar").offset().left-allLeft;
                        $(this).css("left",left);

                        $(this).next(".wyp-anim-process-bar").addClass("wyp-anim-has-zero-delay");

                    });

                    update_animation_manager();

                    body.removeClass("wyp-anim-removing");

                    animation_manager();

                });

            });


            /* ---------------------------------------------------- */
            /* get selected element object                          */
            /* ---------------------------------------------------- */
            function get_selected_element(){

                return iframe.find(".wyp-selected");

            }


            /* ---------------------------------------------------- */
            /* IS Functions List                                    */
            /* ---------------------------------------------------- */
            function is_content_selected(){

                return mainBody.hasClass("wyp-content-selected");

            }

            function is_dragging(){

                return mainBody.hasClass("wyp-dragging");

            }

            function is_resizing(){

                return mainBody.hasClass("wyp-element-resizing");

            }

            function is_visual_editing(){

                return mainBody.hasClass("wyp-visual-editing");

            }

            function is_responsive_mod(){

                return mainBody.hasClass("wyp-responsive-device-mode");

            }

            function is_animate_creator(){

                return mainBody.hasClass("wyp-anim-creator");

            }

            function is_animation_manager(){

                return mainBody.hasClass("wyp-animate-manager-active");

            }


            /* ---------------------------------------------------- */
            /* Delete the editor classes by class List              */
            /* ---------------------------------------------------- */
            function get_cleaned_classes(el,oldArray){

                var resultArray = [];

                // Element Classes
                var classes = el.attr("class");

                // Is defined?
                if(isDefined(classes)){

                    // Cleaning all Yellow Pencil classes.
                    classes = class_cleaner(classes);

                    // Clean spaces
                    classes = space_cleaner(classes);

                    // If not empty
                    if(classes.length >= 1){

                        var classesArray = get_classes_array(classes);

                        // Be sure there have more class then one.
                        if(classesArray.length > 0){

                            // Each
                            for(var io = 0;io < classesArray.length; io++){

                                // Clean spaces
                                var that = space_cleaner(classesArray[io]);

                                // continue If not have this class in data
                                if(resultArray.indexOf(that) == -1 && oldArray.indexOf(that) == -1 && that.length >= 1){

                                    // Push.
                                    resultArray.push(that);

                                }

                            }

                        }else{

                            // continue If not have this class in data
                            if(resultArray.match(classes) == -1 && oldArray.indexOf(classes) == -1){

                                // Push
                                resultArray.push(classes);

                            } // If

                        } // else

                    } // not empty.

                } // IsDefined

                // return.
                return resultArray;

            }


            /* ---------------------------------------------------- */
            /* Updating Design information                          */
            /* ---------------------------------------------------- */
            function update_design_information(type){


                // Was wireframe?
                var washaveWireFrame = false;


                // Check wireframe
                if(body.hasClass("wyp-wireframe-mode")){
                    washaveWireFrame = true;
                    body.removeClass("wyp-wireframe-mode");
                }


                // Cache elements
                var elementMain = $(".info-element-general"),
                elementClasseslist = $(".info-element-class-list"),
                elementSelectorList = $(".info-element-selector-list");


                // Clean Old data
                $(".info-element-general,.info-element-class-list,.info-element-selector-list").empty();


                // Updating Section.
                if(type != 'element'){


                    // Delete Old
                    $(".info-color-scheme-list,.info-font-family-list,.info-animation-list,.info-basic-typography-list,.info-basic-size-list").empty();


                    // Get elements as variable.
                    var colorlist = $(".info-color-scheme-list"),
                    familylist = $(".info-font-family-list"),
                    animatelist = $(".info-animation-list"),
                    sizelist = $(".info-basic-size-list"),
                    typographyList = $(".info-basic-typography-list"),
                    globalclasslist = $(".info-global-class-list"),
                    globalidlist = $(".info-global-id-list");


                    // Variables
                    var maxWidth = 0,
                    maxWidthEl = null,
                    k = $(window).width();


                    // Append general elements
                    iframeBody.append("<h1 id='wyp-heading-test-level-1'></h1><h2 id='wyp-heading-test-level-2'></h2><h3 id='wyp-heading-test-level-3'></h3><h4 id='wyp-heading-test-level-4'></h4><h5 id='wyp-heading-test-level-5'></h5><h6 id='wyp-heading-test-level-6'></h6><h6 id='wyp-paragraph-test'></h6>");


                    // Font Sizes
                    var paragraphElement = iframeBody.find("#wyp-paragraph-test"),
                    bodyFontSize = (Math.round( parseFloat( iframeBody.css("fontSize") ) * 10 ) / 10),
                    paragraphFontSize = (Math.round( parseFloat( paragraphElement.css("fontSize") ) * 10 ) / 10);


                    // Font family
                    var bodyFamily = iframeBody.css("fontFamily");
                    var paragraphFamily = paragraphElement.css("fontFamily");


                    // Update typography information
                    typographyList
                    .append('<li><span class="typo-list-left">General (body)</span><span class="typo-list-right"><span>'+bodyFontSize+'px, '+get_font_name(bodyFamily)+'</span></span></li>')
                    .append('<li><span class="typo-list-left">Paragraph</span><span class="typo-list-right"><span>'+paragraphFontSize+'px, '+get_font_name(paragraphFamily)+'</span></span></li>');


                    // Delete created element. (Created only for test)
                    paragraphElement.remove();

                    var appendData = '';

                    // Update Heading tags. h1 > h6
                    for(var i = 1; i <= 6; i++){

                        var el = iframeBody.find("#wyp-heading-test-level-"+i);
                        var size = parseFloat(el.css("fontSize"));
                        size = Math.round( size * 10 ) / 10;
                        var family = el.css("fontFamily");

                        appendData += '<li><span class="typo-list-left">Heading Level '+i+'</span><span class="typo-list-right"><span>'+size+'px, '+get_font_name(family)+'</span></span></li>';

                        el.remove();

                    }

                    // append after the loop
                    typographyList.append(appendData);


                    // Each all elements for find what we need.
                    var ColoredEl = [];
                    var familyArray = [];
                    var animatedArray = [];
                    var classArray = [];
                    var idArray = [];
                    var boxSizingArray = [];

                    // Each
                    iframeBody.find(get_all_elements()).each(function(i){


                        // Element
                        var el = $(this);


                        // Find container
                        var otherWidth = el.outerWidth();

                        // 720 768 940 960 980 1030 1040 1170 1210 1268
                        if(otherWidth >= 720 && otherWidth <= 1268 && otherWidth < (k-80)){
                            if(otherWidth > maxWidth){
                                maxWidthEl = el;
                            }

                            // MaxWidth Element Founded. (Container)
                            maxWidth = Math.max(otherWidth, maxWidth);

                        }


                        // Filter font family elements.
                        var family = get_font_name(el.css("fontFamily"));
                        if(familyArray.indexOf(family) == -1){
                            familyArray.push(family);
                        }


                        // Filter colored elements.
                        var color = el.css("backgroundColor").toLowerCase().replace(/ /g,"");
                        if(color != 'transparent' && color != 'rgb(255,255,255)' && color != 'rgba(0,0,0,0)' && color != 'rgba(255,255,255,0)'){
                            ColoredEl.push(this);
                        }


                        // Get box sizing
                        if(i < 20){ // Get only on first 20 elements. no need to more.
                            var boxSizing = (el.css("boxSizing"));
                            if(isDefined(boxSizing)){

                                boxSizing  = $.trim(boxSizing);

                                if(boxSizingArray.indexOf(boxSizing) == -1){
                                    boxSizingArray.push(boxSizing);
                                }

                            }
                        }


                        // Find classes and ids
                        setTimeout(function(){

                            // If there not have any class in our list
                            if(globalclasslist.find("li").length === 0){

                                // Get Cleaned classes.
                                var arrayClassAll = get_cleaned_classes(el,classArray);

                                // Concat if not empty.
                                if(arrayClassAll.length > 0){
                                    classArray = classArray.concat(arrayClassAll);
                                }

                            }


                            // Get ID
                            // If there not have any id in our list.
                            if(globalidlist.find("li").length === 0){

                                // Get Id
                                var id = el.attr("id");

                                // is defined
                                if(isDefined(id)){

                                    // continue If not have this class in data
                                    if(idArray.indexOf(id) == -1){

                                        // Push
                                        idArray.push(id);

                                    }

                                }

                            }


                        },500);

                    });


                    // Filter animated elements.
                    the_editor_data().find("[data-rule='animation-name']").each(function(){

                        var animate = esc_data_value($(this).html());

                        if(animatedArray.indexOf(animate) == -1){
                            animatedArray.push(animate);
                        }

                    });


                    // Not adding on responsive mode.
                    var containerWidth;
                    if(is_responsive_mod() === false){

                        containerWidth = maxWidth+'px';

                    }else{
                        containerWidth = 'Unknown';
                    }


                    appendData = '';

                    // Apply colors
                    $(ColoredEl).each(function(){

                        var el = $(this);
                        var color = el.css("backgroundColor").toLowerCase().replace(/ /g,"");

                        var current = $(".info-color-scheme-list div[data-color='"+color+"']");
                        var ratio = parseFloat(100/$(ColoredEl).length);

                        if(current.length > 0){
                            var cWi = parseFloat(current.attr("data-width"));
                            current.css("width",(cWi+ratio)+"%");
                            current.attr("data-width",(cWi+ratio));
                        }else{
                            appendData += '<div data-width="'+ratio+'" data-color="'+color+'" style="width:'+ratio+'%;background-color:'+color+';"></div>';
                        }

                    });

                    colorlist.append(appendData);

                    appendData = '';

                    // Update fonts
                    $.each(familyArray,function(i,v){
                        appendData += "<li>"+v+"</li>";
                    });

                    familylist.append(appendData);


                    appendData = '';

                    // Update animations.
                    $.each(animatedArray,function(i,v){
                        appendData += "<li>"+v+"</li>";
                    });

                    animatelist.append(appendData);

                    // Hide animations heading if empty
                    if(animatedArray.length == 0){
                        $("#animations-heading").hide();
                    }else{ // show
                        $("#animations-heading").show();
                    }


                    // Append Size information to size section.
                    sizelist.append('<li><span class="typo-list-left">Box Sizing</span><span class="typo-list-right"><span>'+boxSizingArray.toString()+'</span></span></li>')
                    .append('<li><span class="typo-list-left">Container Width</span><span class="typo-list-right"><span>'+containerWidth+'</span></span></li>')
                    .append('<li><span class="typo-list-left">Document Width</span><span class="typo-list-right"><span>'+(parseInt(iframe.width())+window.leftbarWidth)+'px</span></span></li>')
                    .append('<li><span class="typo-list-left">Document Height</span><span class="typo-list-right"><span>'+iframe.height()+'px</span></span></li>');


                    // waiting a litte for high performance.
                    setTimeout(function(){

                        appendData = '';

                        // Append classes
                        $.each(classArray,function(i,v){
                            appendData += "<li>."+v+"</li>";
                        });

                        globalclasslist.append(appendData);

                        appendData = '';

                        // Append ids
                        $.each(idArray,function(i,v){
                            appendData += "<li>#"+v+"</li>";
                        });

                        globalidlist.append(appendData);

                    },1000);


                }


                // if is element Section
                if(is_content_selected()){


                    // Hide and show some sections in design information
                    $(".info-no-element-selected").hide();
                    $(".info-element-selected-section").show();
                    $("info-element-selector-section").hide();


                    // cache selected element
                    var selectedEl = get_selected_element();
                    var selectedID = selectedEl.attr("id");


                    // Getting element ID.
                    if(isDefined(selectedID)){

                        // Is valid?
                        if(selectedID !== ''){

                            // Append
                            elementMain.append('<li><span class="typo-list-left">Element ID</span><span class="typo-list-right"><span>#'+selectedID+'</span></span></li>');

                        }

                    }


                    // Append Tag name
                    elementMain.append('<li><span class="typo-list-left">Tag</span><span class="typo-list-right"><span>'+selectedEl[0].nodeName+'</span></span></li>');


                    // Append Affected count
                    elementMain.append('<li><span class="typo-list-left">Affected elements</span><span class="typo-list-right"><span>'+(parseInt(iframeBody.find(".wyp-selected-others").length)+1)+'</span></span></li>');


                    // Get class Array
                    var classSelfArray = get_cleaned_classes(selectedEl,[]);

                    var x;

                    appendData = '';

                    // Append all classes.
                    for(x = 0; x < classSelfArray.length; x++){

                        // Append
                        appendData += "<li>."+classSelfArray[x]+"</li>";

                    }

                    elementClasseslist.append(appendData);


                    // Hide class section if empty.
                    if(elementClasseslist.find("li").length === 0){
                        $(".info-element-classes-section").hide();
                    }else{
                        $(".info-element-classes-section").show();
                    }


                    // Current Selector
                    elementSelectorList.append('<li>'+get_current_selector()+'</li>');


                    // Create dom data. For show DOM HTML in Design information tool
                    var clone = selectedEl.clone();


                    // Clean basic position relative style from clone
                    if(isDefined(clone.attr("style"))){

                        // Trim Style
                        var trimCloneStyle = clone.attr("style").replace(/position:(\s*?)relative(\;?)|animation-fill-mode:(\s*?)(both|forwards|backwards|none)(\;?)/g,"");

                        // Remove Style Attr if is empty.
                        if(trimCloneStyle == ''){
                            clone.removeAttr("style");
                        }else{
                            clone.attr("style",trimCloneStyle);
                        }

                    }

                    // Remove Class Attr.
                    clone.removeAttr("class");


                    // Just add valid classes.
                    for(x = 0; x < classSelfArray.length; x++){

                        // addClass.
                        clone.addClass(classSelfArray[x]);

                    }

                    // Dom Content.
                    clone.html("...");

                    // Get.
                    var str = $("<div />").append(clone).html();

                    // Set
                    $(".info-element-dom").val(str);

                    // Box Model
                    update_box_model();


                // Show there no element selected section.
                }else{

                    $(".info-no-element-selected").show();

                    $(".info-element-selected-section").hide();

                }

                // Active wireframe if was active before open.
                // Notice: This function close wireframe for getting colors and details of the elements.
                if(washaveWireFrame === true){
                    body.addClass("wyp-wireframe-mode");
                }

                // setup tooltip and copy api
                setup_color_scheme_copy();

            }


            /* ---------------------------------------------------- */
            /* Setup copy color scheme API                          */
            /* ---------------------------------------------------- */
            function setup_color_scheme_copy(){

                // copy on click
                new Clipboard('.info-color-scheme-list div', {
                    text: function(trigger) {
                        return get_color(trigger.getAttribute('data-color'));
                    }
                });

                // destory old
                $('.info-color-scheme-list > div').tooltip("destroy");

                // color scheme copy tooltip
                $('.info-color-scheme-list > div').tooltip({
                    animation: true,
                    trigger: "manual",
                    container: ".advanced-info-box-inner",
                    html: true
                });

                $('.info-color-scheme-list > div').on("mouseenter", function(){
                    $(this).attr("data-original-title", get_color($(this).attr("data-color"))).tooltip('fixTitle').tooltip("show");
                });

                $('.info-color-scheme-list > div').on("click", function(){
                    $(this).attr("data-original-title", "Copied!").tooltip('fixTitle').tooltip("show");
                });

                $('.info-color-scheme-list > div').on("mouseleave", function(){
                    $(this).tooltip("hide");
                });

            }


            // used for box mode tooltip. can used on other elements too.
            $('.advanced-info-box-inner [data-toggle="infoTooltip"]').tooltip({
                animation: false,
                trigger: "hover",
                container: ".advanced-info-box-inner",
                html: true
            });



            /* ---------------------------------------------------- */
            /* Update Box Model                                     */
            /* ---------------------------------------------------- */
            function update_box_model(){

                // Element
                var el = get_selected_element();

                // Margin
                $(".model-margin-top").text(parseInt(el.css("marginTop")));
                $(".model-margin-right").text(parseInt(el.css("marginRight")));
                $(".model-margin-bottom").text(parseInt(el.css("marginBottom")));
                $(".model-margin-left").text(parseInt(el.css("marginLeft")));

                // Padding Model
                $(".model-padding-top").text(parseInt(el.css("paddingTop")));
                $(".model-padding-right").text(parseInt(el.css("paddingRight")));
                $(".model-padding-bottom").text(parseInt(el.css("paddingBottom")));
                $(".model-padding-left").text(parseInt(el.css("paddingLeft")));

                // Border Model
                $(".model-border-top").text(parseInt(el.css("borderTopWidth")));
                $(".model-border-right").text(parseInt(el.css("borderRightWidth")));
                $(".model-border-bottom").text(parseInt(el.css("borderBottomWidth")));
                $(".model-border-left").text(parseInt(el.css("borderLeftWidth")));

                // Size Model
                $(".model-size").text(parseInt(el.width()) + " x " + parseInt(el.height()));

            }


            /* ---------------------------------------------------- */
            /* Getting first Font Family                            */
            /* ---------------------------------------------------- */
            function get_font_name(family){

                if(family.indexOf(",") != -1){
                    family = family.split(",")[0];
                }

                family = $.trim(family).replace(/\W+/g, " ");

                return family;

            }


            /* ---------------------------------------------------- */
            /* Design information: Close                            */
            /* ---------------------------------------------------- */
            $(".advanced-close-link").on("click",function(){
                $(".advanced-info-box").hide();
                $(".info-btn").removeClass("active");
            });


            /* ---------------------------------------------------- */
            /* Design information: Advanced / Info Section          */
            /* ---------------------------------------------------- */
            $(".info-btn").on("click",function(){

                if(!$(this).hasClass("active")){

                    if($(".advance-info-btns.active").length == 0){
                        $(".element-btn").trigger("click");
                    }

                    var max = $(window).height()-$(this).offset().top;
                    $(".advanced-info-box").css({"top":$(this).offset().top+1,"height": max - (max*5/100)});
                    update_design_information('all');
                }

                $(".advanced-info-box").toggle();

            });


            /* ---------------------------------------------------- */
            /* Design information: Typography Section               */
            /* ---------------------------------------------------- */
            $(".typography-btn").on("click",function(){
                $(this).parent().find(".active").removeClass("active");
                $(this).addClass("active");
                $(".typography-content,.element-content,.advanced-content").hide();
                $(".typography-content").show();
            });


            /* ---------------------------------------------------- */
            /* Design information: Element Section                  */
            /* ---------------------------------------------------- */
            $(".element-btn").on("click",function(){
                $(this).parent().find(".active").removeClass("active");
                $(this).addClass("active");
                $(".element-content,.typography-content,.advanced-content").hide();
                $(".element-content").show();
            });


            /* ---------------------------------------------------- */
            /* Design information: Advanced Section                 */
            /* ---------------------------------------------------- */
            $(".advanced-btn").on("click",function(){
                $(this).parent().find(".active").removeClass("active");
                $(this).addClass("active");
                $(".element-content,.typography-content,.advanced-content").hide();
                $(".advanced-content").show();
            });

            /* ---------------------------------------------------- */
            /* Design information: Scroll to top on tab click       */
            /* ---------------------------------------------------- */
            $(".advance-info-btns").on("click",function(){
                $(".advanced-info-box-inner").scrollTop(0);
            });



            /* ---------------------------------------------------- */
            /* Easy resize editor panels                            */
            /* ---------------------------------------------------- */
            $(document).on("mousedown", ".ui-resizable-handle", function(){

                if($(this).parents(".wyp-anim-process-bar").length == 0){
                    mainBody.addClass("fake-layer-x-bg");
                    mainBody.append("<div class='fake-layer-x'></div>");
                    $(this).addClass("active");
                }

            });


            /* ---------------------------------------------------- */
            /* Easy resize editor panels                            */
            /* ---------------------------------------------------- */
            $(document).on("mouseup", ".ui-resizable-handle,.fake-layer-x", function(){

                if($(this).parents(".wyp-anim-process-bar").length == 0){
                    mainBody.removeClass("fake-layer-x-bg");
                    $(".fake-layer-x").remove();
                    $(".ui-resizable-handle").removeClass("active");
                }

            });



            /* ---------------------------------------------------- */
            /* Setting default width for CSS Editor                 */
            /* ---------------------------------------------------- */
            function create_cookie(name,value) {

                var expires = "";

                var date = new Date();
                date.setTime(date.getTime() + (365*24*60*60*1000));
                expires = "; expires=" + date.toUTCString();

                document.cookie = name + "=" + value + expires + "; path=/";

            }



            /* ---------------------------------------------------- */
            /* Setting default width for CSS Editor                 */
            /* ---------------------------------------------------- */
            function read_cookie(name, ifnothave) {

                var nameEQ = name + "=";
                var ca = document.cookie.split(';');

                for(var i=0;i < ca.length;i++) {
                    var c = ca[i];
                    while (c.charAt(0)==' ') c = c.substring(1,c.length);
                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
                }

                return ifnothave;

            }


            /* ---------------------------------------------------- */
            /* Setting default width for Advanced info box          */
            /* ---------------------------------------------------- */
            var defaultWidth = 288;
            if(mainBody.hasClass("wyp-fixed-right-panel")){
                defaultWidth = 300;
            }
            var number = read_cookie("rightPanelWidth", defaultWidth);
            mainBody.append("<style id='rightpanel-personalized-view'>.wyp-fixed-right-panel:not(.wyp-animate-manager-active) #iframe{width:calc(100% - "+number+"px - 46px) !important;}.editor-panel{width:"+number+"px !important;}</style>");

            /* ---------------------------------------------------- */
            /* Makes Right Panel Resizeable                         */
            /* ---------------------------------------------------- */
            $( ".editor-panel" ).resizable({

                handles: "w, e",
                minWidth: 288,
                maxWidth: 400,

                start: function( event, ui ){

                    // Set cursor
                    $(".fake-layer-x").css("cursor","e-resize");
                    hide_frame_ui(0);

                },

                resize: function( event, ui ){

                    // delete old
                    $("#rightpanel-personalized-view").remove();

                    // update
                    mainBody.append("<style id='rightpanel-personalized-view'>.wyp-fixed-right-panel:not(.wyp-animate-manager-active) #iframe{width:calc(100% - "+ui.size.width+"px - 46px) !important;}.editor-panel{width:"+ui.size.width+"px !important;}</style>");

                },

                stop: function( event, ui ){

                    // delete fake layer
                    $(".fake-layer-x").remove();

                    // cookie
                    create_cookie('rightPanelWidth', ui.size.width);
                    show_frame_ui(0);

                }

            });



            /* ---------------------------------------------------- */
            /* Setting default width for Advanced info box          */
            /* ---------------------------------------------------- */
            var number = read_cookie("advancedInfoBoxWidth", 300);
            mainBody.append("<style id='advancedinfobox-personalized-view'>.advanced-info-box {width:"+number+"px !important;}</style>");

            /* ---------------------------------------------------- */
            /* Makes Advanced info box Resizeable                   */
            /* ---------------------------------------------------- */
            $( ".advanced-info-box" ).resizable({

                handles: "e",
                minWidth: 300,
                maxWidth: ($(window).width()*70/100),

                start: function( event, ui ){

                    // Set cursor
                    $(".fake-layer-x").css("cursor","e-resize");

                },

                resize: function( event, ui ){

                    // delete old
                    $("#advancedinfobox-personalized-view").remove();

                    // update
                    mainBody.append("<style id='advancedinfobox-personalized-view'>.advanced-info-box {width:"+ui.size.width+"px !important;}</style>");

                },

                stop: function( event, ui ){

                    // delete fake layer
                    $(".fake-layer-x").remove();

                    // cookie
                    create_cookie('advancedInfoBoxWidth', ui.size.width);

                }

            });


            /* ---------------------------------------------------- */
            /* Setting default width for CSS Editor                 */
            /* ---------------------------------------------------- */
            var number = read_cookie("cssEditorWidth", 400);
            mainBody.append("<style id='csseditor-personalized-view'>#leftAreaEditor, #cssData, #cssEditorBar{width:"+number+"px !important;}body.wyp-css-editor-active .metric,body.wyp-css-editor-active .breakpoint-bar, body.wyp-css-editor-active.wyp-responsive-device-mode #iframe, body.wyp-css-editor-active:not(.wyp-responsive-device-mode) #iframe {left: "+number+"px !important;}body.wyp-css-editor-active .breakpoint-bar div{margin-left: calc("+number+"px - 56px) !important;}body.wyp-css-editor-active:not(.wyp-responsive-device-mode) #iframe {width: calc(100% - "+number+"px) !important;}body.wyp-css-editor-active .context-menu-root, body.wyp-css-editor-active .metric-top-tooltip {margin-left: "+number+"px !important;}body.wyp-css-editor-active .metric-top-border {margin-left: calc("+number+"px - 1px) !important;}body.wyp-css-editor-active .metric-left-tooltip {margin-left: calc("+number+" + 1px) !important;}</style>");



            /* ---------------------------------------------------- */
            /* Makes CSS Editor Resizeable                          */
            /* ---------------------------------------------------- */
            $( "#leftAreaEditor" ).resizable({

                handles: "e",
                maxWidth: $(window).width(),
                minWidth: 325,
                delay: 150,

                start: function( event, ui ){

                    $("#cssData").width(ui.size.width-41);
                    $("#cssEditorBar").width(ui.size.width);
                    hide_frame_ui(0);

                    // Set cursor
                    $(".fake-layer-x").css("cursor","e-resize");

                },

                resize: function( event, ui ) {

                    $("#cssData").width(ui.size.width-41);
                    $("#cssEditorBar").width(ui.size.width);

                    // delete old css
                    $("#csseditor-personalized-view").remove();

                    // update
                    var number = ui.size.width;
                    mainBody.append("<style id='csseditor-personalized-view'>#leftAreaEditor, #cssData, #cssEditorBar{width:"+number+"px !important;}body.wyp-css-editor-active .metric,body.wyp-css-editor-active .breakpoint-bar, body.wyp-css-editor-active.wyp-responsive-device-mode #iframe, body.wyp-css-editor-active:not(.wyp-responsive-device-mode) #iframe {left: "+number+"px !important;}body.wyp-css-editor-active .breakpoint-bar div{margin-left: calc("+number+"px - 56px) !important;}body.wyp-css-editor-active:not(.wyp-responsive-device-mode) #iframe {width: calc(100% - "+number+"px) !important;}body.wyp-css-editor-active .context-menu-root, body.wyp-css-editor-active .metric-top-tooltip {margin-left: "+number+"px !important;}body.wyp-css-editor-active .metric-top-border {margin-left: calc("+number+"px - 1px) !important;}body.wyp-css-editor-active .metric-left-tooltip {margin-left: calc("+number+" + 1px) !important;}</style>");
                    editor.resize();


                },

                stop: function( event, ui ){

                    $(".fake-layer-x").remove();
                    $("#cssData").width(ui.size.width-41);
                    $("#cssEditorBar").width(ui.size.width);

                    // Cookie
                    create_cookie('cssEditorWidth', ui.size.width);

                }

            });



            /* ---------------------------------------------------- */
            /* Setting default width for CSS Editor                 */
            /* ---------------------------------------------------- */
            var number = read_cookie("animManagerHeight", 287);
            mainBody.append("<style id='animmanager-personalized-view'>body.wyp-animate-manager-active #iframe{height:calc(100% - "+number+"px) !important;}.wyp-animate-manager{height:"+number+"px !important;}</style>");



            /* ---------------------------------------------------- */
            /* Makes Animation Manager Resizeable                   */
            /* ---------------------------------------------------- */
            $( ".wyp-animate-manager" ).resizable({

                handles: "n",

                minHeight: 287,
                maxHeight: ($(window).height()*70/100),

                start: function( event, ui ){

                    hide_frame_ui(0);

                    // Set cursor
                    $(".fake-layer-x").css("cursor","n-resize");

                },

                resize: function( event, ui ){

                    // delete old css
                    $("#animmanager-personalized-view").remove();

                    // update
                    mainBody.append("<style id='animmanager-personalized-view'>body.wyp-animate-manager-active #iframe{height:calc(100% - "+ui.size.height+"px) !important;}.wyp-animate-manager{height:"+ui.size.height+"px !important;}</style>");

                },

                stop: function( event, ui ){

                    $(".fake-layer-x").remove();

                    // Cookie
                    create_cookie('animManagerHeight', ui.size.height);

                }

            });



            /* ---------------------------------------------------- */
            /* Animation Manager: Generating Manager                */
            /* ---------------------------------------------------- */
            function animation_manager(){

                // as default, get all animations
                var list = '[data-rule="animation-name"]';

                // IF alredy has animations, use same order to get animations
                // so it will show animations in same order after updates.
                if($(".wyp-anim-process-bar-area").length > 0){

                    // getting current selectors
                    var listObject = [];
                    $(".wyp-anim-process-bar-area").each(function(i){

                        // current selector
                        var selector = $(this).attr("data-selector-full");

                        // current element object
                        var element = the_editor_data().find("."+get_id(selector)+"-animation-name-style");

                        // add to list
                        listObject.push(element);

                    });

                    // and this list will allow to get animations by "Selector Order".
                    list = listObject.reduce($.merge);

                }

                $(".wyp-animate-manager [data-toggle='tooltipAnim']").tooltip("destroy");

                // destory if has.
                if($(".wyp-anim-process-bar-delay.ui-resizable-e,.wyp-anim-process-bar.ui-resizable-e").length > 0){
                    $(".wyp-anim-process-bar-delay,.wyp-anim-process-bar").resizable('destroy');
                }

                $(".wyp-anim-el-column,.wyp-animate-bar").remove();

                // Update metric
                $(".wyp-anim-metric").empty();
                for(var i = 1; i < 61; i++){
                    $(".wyp-anim-metric").append('<div class="second"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><b>'+i+'s</b></div>');
                }

                the_editor_data().find(list).each(function(iX){

                    // Variables
                    var data = $(this).html();
                    var device = $(this).attr("data-size-mode");
                    var selector = esc_data_selector(data);
                    var animateName = esc_data_value(data);
                    var animateDelayOr = "0s";
                    var animateTimeOr = "1s";
                    var mode = 'yp_onscreen';

                    if(animateName == 'none'){
                        return true;
                    }

                    if(selector.indexOf("yp_hover") != -1){
                        mode = 'yp_hover';
                    }else if(selector.indexOf("yp_focus") != -1){
                        mode = 'yp_focus';
                    }else if(selector.indexOf("yp_click") != -1){
                        mode = 'yp_click';
                    }else if(selector.indexOf("yp_onscreen") != -1){
                        mode = 'yp_onscreen';
                    }

                    var deviceName =  '';
                    var deviceHTML = '';
                    var modeName = mode.replace("yp_","");

                    if(device != 'desktop'){
                        device = data.split("{")[0].replace("@media","");
                        deviceName = 'Responsive';
                    }

                    if(deviceName !== ''){
                        deviceHTML = " <label data-toggle='tooltipAnim' data-placement='right' title='This animation will only play on specific screen sizes.' class='wyp-device-responsive'>"+deviceName+"</label><span class='wyp-anim-media-details'>"+device+"</span>";
                    }

                    // Clean Selector
                    var selectorClean = selector.replace(".yp_hover","").replace(".yp_focus","").replace(".yp_click","").replace(".yp_onscreen","");

                    // Get Element Name
                    var elementName = 'Undefined';
                    if(iframe.find(selectorClean).length > 0){
                        elementName = uppercase_first_letter(get_tag_information(selectorClean)).replace(/\d+/g, '');
                    }

                    // Element Variables
                    if(the_editor_data().find("."+get_id(selector)+"-animation-duration-style[data-size-mode='"+device+"']").length > 0){
                        animateTimeOr = the_editor_data().find("."+get_id(selector)+"-animation-duration-style[data-size-mode='"+device+"']").html();
                        animateTimeOr = esc_data_value(animateTimeOr);
                    }

                    if(the_editor_data().find("."+get_id(selector)+"-animation-delay-style[data-size-mode='"+device+"']").length > 0){
                        animateDelayOr = the_editor_data().find("."+get_id(selector)+"-animation-delay-style[data-size-mode='"+device+"']").html();
                        animateDelayOr = esc_data_value(animateDelayOr);
                    }

                    var animateTime = $.trim(animateTimeOr.replace('/[^0-9\.]+/g','').replace(/ms/g,"").replace(/s/g,""));
                    var animateDelay = $.trim(animateDelayOr.replace('/[^0-9\.]+/g','').replace(/ms/g,"").replace(/s/g,""));

                    if(animateName.indexOf(",") == -1){

                        animateTime = animateTime * 100;
                        animateDelay = animateDelay * 100;

                        if(animateDelay < 10){
                            animateDelay = 10;
                        }

                    }

                    var extraClass = '';
                    if(animateDelay == 10){
                        extraClass = ' wyp-delay-zero';
                    }

                    var animateContent = "<div class='wyp-anim-process-bar-delay"+extraClass+"' data-toggle='tooltipAnim' data-placement='top' title='Delay "+parseFloat(animateDelayOr).toFixed(2)+"s' style='width:"+animateDelay+"px;'></div><div class='wyp-anim-process-bar' data-toggle='tooltipAnim' data-placement='top' title='Duration: "+parseFloat(animateTimeOr).toFixed(2)+"s' style='width:"+animateTime+"px;'><span class='animate-part-icons wyp-control-trash' data-toggle='tooltipAnim' data-placement='top' title='Delete'><span class='dashicons dashicons-trash'></span></span>"+animateName+"</div>";


                    var childAnimateDelayOr,childAnimateDelay,childAnimateTimeOr,childAnimateTime;
                    if(animateName.indexOf(",") != -1){

                        animateContent = '';

                        var prevsBeforeAppend = 0;

                        for(var i = 0; i < animateName.split(",").length; i++){

                            if(animateDelayOr.toString().indexOf(",") != -1){
                                childAnimateDelayOr = $.trim(animateDelayOr.split(",")[i]);
                            }else{
                                childAnimateDelayOr = animateDelayOr;
                            }

                            // default is 1s for child animate delay Or.
                            if(isUndefined(childAnimateDelayOr)){
                                childAnimateDelayOr = "0s";
                            }

                            if(animateDelay.toString().indexOf(",") != -1){
                                childAnimateDelay = $.trim(animateDelay.split(",")[i]);
                            }else{
                                childAnimateDelay = animateDelay;
                            }

                            // default is 1s for child animate delay.
                            if(isUndefined(childAnimateDelay)){
                                childAnimateDelay = 0;
                            }

                            if(animateTimeOr.toString().indexOf(",") != -1){
                                childAnimateTimeOr = $.trim(animateTimeOr.split(",")[i]);
                            }else{
                                childAnimateTimeOr = animateTimeOr;
                            }

                            // default is 1s for child animate time Or.
                            if(isUndefined(childAnimateTimeOr)){
                                childAnimateTimeOr = "1s";
                            }


                            if(animateTime.toString().indexOf(",") != -1){
                                childAnimateTime = $.trim(animateTime.split(",")[i]);
                            }else{
                                childAnimateTime = animateTime;
                            }

                            // default is 1s for child animate.
                            if(isUndefined(childAnimateTime)){
                                childAnimateTime = 1;
                            }

                            var childAnimate = $.trim(animateName.split(",")[i].replace(/\s+?!important/g,'').replace(/\;$/g,''));

                            childAnimateTime = childAnimateTime * 100;
                            childAnimateDelay = childAnimateDelay * 100;

                            var SmartDelayView = (childAnimateDelay-prevsBeforeAppend);
                            var smartDelayOrView = SmartDelayView/100;
                            if(SmartDelayView <= 10){
                                SmartDelayView = 10;
                                smartDelayOrView = "0s";
                            }

                            extraClass = '';
                            if(SmartDelayView == 10){
                                extraClass = ' wyp-delay-zero';
                            }

                            animateContent += "<div class='wyp-anim-process-bar-delay"+extraClass+"' data-toggle='tooltipAnim' data-placement='top' title='Delay "+parseFloat(smartDelayOrView).toFixed(2)+"s' style='width:"+SmartDelayView+"px;'></div><div class='wyp-anim-process-bar' data-toggle='tooltipAnim' data-placement='top' title='Duration: "+parseFloat(childAnimateTimeOr).toFixed(2)+"s' style='width:"+childAnimateTime+"px;'><span class='animate-part-icons wyp-control-trash' data-toggle='tooltipAnim' data-placement='top' title='Delete'><span class='dashicons dashicons-trash'></span></span>"+childAnimate+"</div>";

                            prevsBeforeAppend = childAnimateDelay+childAnimateTime;

                        }

                    }

                    // Append.
                    $(".wyp-anim-left-part-column").append("<div class='wyp-anim-el-column wyp-anim-el-column-"+get_id(selectorClean)+"' data-anim-media-size='"+device+"'><span data-toggle='tooltipAnim' data-placement='right' title='"+selectorClean+"'>"+elementName+"</span> <label>"+modeName+"</label>"+deviceHTML+"</div>");

                    $(".wyp-anim-right-part-column").append("<div class='wyp-animate-bar' id='wyp-animate-bar-"+iX+"'><div class='wyp-anim-process-bar-area' data-responsive='"+device+"' data-selector='"+selectorClean+"' data-selector-full='"+selector+"'><div class='wyp-anim-process-inner'>"+animateContent+"</div><a class='wyp-anim-add' data-toggle='tooltipAnim' data-placement='right' title='Add New Animate'></a></div>");

                });

                $(".wyp-delay-zero").each(function(){

                        var allLeft = $(".wyp-anim-process-inner").offset().left-5;
                        var left = $(this).next(".wyp-anim-process-bar").offset().left-allLeft;
                        $(this).css("left",left);

                        $(this).next(".wyp-anim-process-bar").addClass("wyp-anim-has-zero-delay");

                    });

                // Get current selector
                var Cselector = get_current_selector();
                var Lineway = $(".wyp-anim-el-column-"+get_id(Cselector)+"[data-anim-media-size='"+get_media_condition()+"']");

                // has selected element and there not have same element in manager list
                if(isDefined(Cselector) && Lineway.length === 0){

                    // Get Element Name
                    var elementName = 'Undefined';
                    if(iframe.find(Cselector).length > 0){
                        elementName = uppercase_first_letter(get_tag_information(Cselector)).replace(/\d+/g, '');
                    }

                    var deviceHTML = '';

                    if(get_media_condition() != 'desktop'){
                    deviceHTML = " <label data-toggle='tooltipAnim' data-placement='right' title='This animation will only play on specific screen sizes.' class='wyp-device-responsive'>Responsive</label><span class='wyp-anim-media-details'>"+get_media_condition()+"</span>";
                    }

                    // Bar
                    $(".wyp-anim-left-part-column").append("<div class='wyp-anim-el-column anim-active-row wyp-anim-el-column-"+get_id(Cselector)+"' data-anim-media-size='"+get_media_condition()+"'><span data-toggle='tooltipAnim' data-placement='right' title='"+Cselector+"'>"+elementName+"</span> <label>onscreen</label>"+deviceHTML+"</div>");

                    // Adding
                    $(".wyp-anim-right-part-column").append("<div class='wyp-animate-bar anim-active-row' id='wyp-animate-bar-current'><div class='wyp-anim-process-bar-area' data-responsive='"+get_media_condition()+"' data-selector='"+Cselector+"' data-selector-full='"+(Cselector+".yp_onscreen")+"'><div class='wyp-anim-process-inner'></div><a class='wyp-anim-add' data-toggle='tooltipAnim' data-placement='right' title='Add New Animate'></a></div>");

                }else{
                    Lineway.addClass("anim-active-row");
                }

                // resizable
                $( ".wyp-anim-process-bar-delay,.wyp-anim-process-bar" ).resizable({
                    handles: 'e',
                    minWidth: 10,
                    start: function() {

                        $(".wyp-anim-process-bar-delay,.wyp-anim-process-bar").not(this).tooltip("disable").tooltip("hide");

                    },
                    resize: function( event, ui ) {

                        var that = $(this);
                        var w = ui.size.width;
                        var s = parseFloat(w/100).toFixed(2);

                        var newTitle;
                        if(that.hasClass("wyp-anim-process-bar-delay")){

                            if(w == 10){
                                s = "0";
                            }
                            newTitle = "Delay: "+s;

                            // Delay zero
                            if(w <= 10){
                                that.addClass("wyp-delay-zero");
                                that.next(".wyp-anim-process-bar").addClass("wyp-anim-has-zero-delay");
                            }else if(that.hasClass("wyp-delay-zero")){
                                that.removeClass("wyp-delay-zero").css("left","0");
                                that.next(".wyp-anim-process-bar").removeClass("wyp-anim-has-zero-delay");
                            }

                        }else{

                            newTitle = "Duration: "+s;

                        }


                        $(this).parents(".wyp-animate-bar").find(".wyp-delay-zero").each(function(){

                            var allLeft = $(".wyp-anim-process-inner").offset().left-5;
                            var left = $(this).next(".wyp-anim-process-bar").offset().left-allLeft;
                            $(this).css("left",left);

                        });


                        that.attr('data-original-title', newTitle+"s").tooltip('show');

                    },
                    stop: function() {

                        update_animation_manager();
                        $(".wyp-anim-process-bar-delay,.wyp-anim-process-bar").tooltip("enable");

                    }

                });


                // Animation manager tooltip
                $('[data-toggle="tooltipAnim"]').tooltip({
                    animation: false,
                    container: ".wyp-animate-manager",
                    html: true
                });


                $("[data-toggle='tooltipAnim']").on('show.bs.tooltip', function(){
                    $("[data-toggle='tooltipAnim']").not(this).tooltip("hide");
                });

                if($(".wyp-animate-bar").length === 0){
                    $(".animation-manager-empty").show();
                }else{
                    $(".animation-manager-empty").hide();
                }

                // Find largest line for play/stop.
                var maxWidth = Math.max.apply( null, $( '.wyp-anim-process-inner' ).map( function (){
                    return $( this ).outerWidth( true );
                }).get() );

                // Always add more px to animate bar width on update.
                $(".wyp-anim-process-bar-area").width(maxWidth+$(window).width());

            }


            /* ---------------------------------------------------- */
            /* Animation Manager: Update Manager                    */
            /* ---------------------------------------------------- */
            function update_animation_manager(){

                body.addClass("wyp-animate-manager-mode");

                // Find largest line for play/stop.
                var maxWidth = Math.max.apply( null, $( '.wyp-anim-process-inner' ).map( function (){
                    return $( this ).outerWidth( true );
                }).get() );

                // Always add more px to animate bar width on update.
                $(".wyp-anim-process-bar-area").width(maxWidth+$(window).width());

                // Each all lines
                $(".wyp-animate-bar").each(function(){

                    // Get selector with mode.
                    var selector = $(this).find(".wyp-anim-process-bar-area").attr("data-selector-full");

                    // Animate name array.
                    var sMultiNames = [];

                    // Find all delays in this line.
                    var sMulti = [];
                    var sMultiDuration = [];

                    // delay
                    var delay = 0;
                    var offets = '';

                    // Get size
                    var size = $(this).find(".wyp-anim-process-bar-area").attr("data-responsive");
                    if(size == ''){
                        size = 'desktop';
                    }

                    // Each all animate bars
                    $(this).find(".wyp-anim-process-bar,.wyp-anim-process-bar-delay").each(function(){

                        // Get width.
                        var w = $(this).width();

                        // Width to Second.
                        var s = w/100;

                        // If delay and its not a multiable line.
                        if($(this).hasClass("wyp-anim-process-bar-delay") && $(this).parent().find(".wyp-anim-process-bar-delay").length == 1){

                            if(w == 10){
                                s = "0";
                            }

                            // Update one delay.
                            // append as "0s" val cos 0 is not acceptable value.
                            insert_rule(selector, "animation-delay", Math.round(s * 100) / 100 + "s", '', size);

                        // If animate bar and not a multiable line.
                        }else if($(this).hasClass("wyp-anim-process-bar") && $(this).parent().find(".wyp-anim-process-bar").length == 1){

                            // Update one duration.
                            insert_rule(selector, "animation-duration", s, 's', size);
                            insert_rule(selector, "animation-name", $(this).text(), '', size);
                            sMultiNames.push($(this).text());

                        // If multi line and its delay or animate bar.
                        }else if($(this).parent().find(".wyp-anim-process-bar-delay").length > 1 || $(this).parent().find(".wyp-anim-process-bar").length > 1){

                            // Delay.. Multi..
                            if($(this).hasClass("wyp-anim-process-bar-delay")){

                                offets = $(this).offset().left-$(this).parent(".wyp-anim-process-inner").offset().left;
                                offets = offets/100;
                                offets = Math.round(offets * 100) / 100;

                                if($(this).width() > 10){

                                    delay = $(this).width()/100;
                                    delay = Math.round(delay * 100) / 100;
                                    sMulti.push(delay+offets+"s");

                                }else{

                                    sMulti.push(offets+"s");

                                }

                            }

                            // Duration.. Multi..
                            if($(this).hasClass("wyp-anim-process-bar")){

                                var xy = $(this).width()/100;

                                sMultiDuration.push(xy+"s");
                                sMultiNames.push($(this).text());

                            }

                        }

                    });

                    // Insert multi delays.
                    if(sMulti.length > 1){
                        insert_rule(selector, "animation-delay", sMulti.toString(), '', size);
                        insert_rule(selector, "animation-duration", sMultiDuration.toString(), '', size);
                        insert_rule(selector, "animation-name", sMultiNames.toString(), '', size);

                    }else if(sMultiNames.length === 0 && body.hasClass("wyp-anim-removing")){
                        insert_rule(selector, "animation-delay", "disable", '', size);
                        insert_rule(selector, "animation-duration", "disable", '', size);
                        insert_rule(selector, "animation-name", "disable", '', size);
                    }

                    option_change();

                });

                body.removeClass("wyp-animate-manager-mode");

            }


            /* ---------------------------------------------------- */
            /* Window Click                                         */
            /* ---------------------------------------------------- */
            $(window).click(function() {

                if($(".wyp-anim-list-menu").is(":visible")){
                    $(".wyp-anim-list-menu").hide();
                }

            });


            /* ---------------------------------------------------- */
            /* Add Animation: Animation list click                  */
            /* ---------------------------------------------------- */
            $(document).on("click", ".wyp-anim-list-menu ul li", function(e) {

                // Clean old.
                get_selected_element().removeClass("yp_onscreen yp_hover yp_click yp_focus");

                var p = $(".wyp-anim-add.active");

                mainBody.addClass("wyp-animate-manager-mode");
                var selector = p.parent().attr("data-selector-full");
                var allAnimNames = [];
                var allDurations = [];
                var allDelays = [];

                // Get size
                var size = p.parents(".wyp-anim-process-bar-area").attr("data-responsive");
                if(size == ''){
                    size = 'desktop';
                }

                // If empty, so this new.
                if(p.parent().find(".wyp-anim-process-inner").is(':empty')){
                    insert_rule(selector, "animation-name", $(this).data("value"), '',size);
                }else{

                    // push older animations
                    p.parent().find(".wyp-anim-process-inner .wyp-anim-process-bar").each(function(){
                        allAnimNames.push($(this).text());
                        allDurations.push(($(this).width()/100)+"s");
                    });

                    // push older animations
                    p.parent().find(".wyp-anim-process-inner .wyp-anim-process-bar-delay").each(function(){
                        var offets = ($(this).offset().left-p.parent().find(".wyp-anim-process-inner").offset().left)/100;

                        if($(this).hasClass("wyp-delay-zero")){
                            allDelays.push(offets+"s");
                        }else{
                            allDelays.push(offets+($(this).width()/100)+"s");
                        }

                    });

                    // push new animation too
                    allAnimNames.push($(this).data("value"));
                    allDurations.push("1s");

                    var lastAnim = p.parent().find(".wyp-anim-process-inner .wyp-anim-process-bar").last();
                    var offets = ((lastAnim.offset().left+lastAnim.width())-p.parent().find(".wyp-anim-process-inner").offset().left)/100;
                    allDelays.push(offets+"s");

                    insert_rule(selector, "animation-name",allAnimNames.toString(), '',size);
                    insert_rule(selector, "animation-duration",allDurations.toString(), '',size);
                    insert_rule(selector, "animation-delay",allDelays.toString(), '',size);

                }

                mainBody.removeClass("wyp-animate-manager-mode");

                setTimeout(function(){
                    animation_manager();
                    update_animation_manager();
                },100);

            });


            /* ---------------------------------------------------- */
            /* Animation Manager: Add Animation icon                */
            /* ---------------------------------------------------- */
            $(document).on("click", ".wyp-anim-add", function(e) {

                e.stopPropagation();
                var t = $(this).offset().top;
                var l = $(this).offset().left;

                var menu = $(".wyp-anim-list-menu ul");
                $(".wyp-anim-list-menu").removeAttr("style").removeClass("wyp-anim-list-top");
                menu.empty();
                $("#wyp-animation-name-data option").each(function(){
                    var el = $(this);
                    if(el.text() != 'none'){
                        menu.append("<li data-value='"+el.attr("value")+"' data-text='"+el.data("text")+"' data-content='"+el.text()+"'>"+el.text()+"</li>");
                    }
                });

                var d = $(window).height()-t-46;

                if(d < 150){
                    $(".wyp-anim-list-menu").addClass("wyp-anim-list-top");
                }else{
                    if(menu.height() > d){
                        menu.height(d);
                    }
                }

                $(".wyp-anim-list-menu").css("left",l).css("top",t).show();

                $(".wyp-anim-list-menu").css("margin-left", "-" + parseInt(($(".wyp-anim-list-menu").width()/2) - 10) + "px");

                $(".wyp-anim-add").removeClass("active");
                $(this).addClass("active");

            });


            /* ---------------------------------------------------- */
            /* Lite version modal close                */
            /* ---------------------------------------------------- */
            $(".wyp-info-modal-close").click(function() {
                $(this).parent().parent().hide();
                $(".wyp-popup-background").hide();
            });

            /* ---------------------------------------------------- */
            /* Background Upload popup close                        */
            /* ---------------------------------------------------- */
            $(".wyp-popup-background").click(function() {
                $(this).hide();
                $(".wyp-info-modal").hide();
            });


            /* ---------------------------------------------------- */
            /* Element Inspector button: Click                      */
            /* ---------------------------------------------------- */
            $(".wyp-selector-mode").click(function() {

                if($(".wyp-ruler-btn").hasClass("active")){
                    $(".wyp-ruler-btn").trigger("click");
                    $(".wyp-selector-mode").trigger("click");
                }

                if ($(this).hasClass("active") && $(".wyp-sharp-selector-btn.active").length > 0) {
                    $(".wyp-sharp-selector-btn").removeClass("active");
                    body.removeClass("wyp-single-inspector-active");
                    iframeBody.removeClass("wyp-single-inspector-active");
                    window.singleInspector = false;
                }

                body.toggleClass("wyp-flexible-inspector-active");
                clean();

            });


            // cache
            window.scroll_width = get_scroll_bar_width();


            /* ---------------------------------------------------- */
            /* Draw Responsive Handles                              */
            /* ---------------------------------------------------- */
            function draw_responsive_handle() {

                if (is_responsive_mod() === false) {
                    return false;
                }

                // variables
                var iframeElement = $("#iframe");

                if(isUndefined(window.FrameleftOffset)){
                    var offset = iframeElement.offset();
                    window.FrameleftOffset = offset.left;
                    window.FrametopOffset = offset.top;
                }

                var w = iframeElement.width();
                var h = iframeElement.height();

                var left = window.FrameleftOffset + w;
                var top = window.FrametopOffset + h;

                $(".responsive-right-handle").css("left", left)
                .css("top", window.FrametopOffset - 2)
                .css("height", h + 2);

                $(".responsive-bottom-handle").css("left", window.FrameleftOffset)
                .css("top", top)
                .css("width", w);

            }

            // Right
            window.responsiveModeRMDown = false;
            window.SelectorDisableResizeRight = false;
            window.rulerWasActive = false;
            window.selectorWasActive = false;


            /* ---------------------------------------------------- */
            /* Responsive Right Handle                              */
            /* ---------------------------------------------------- */
            $(".responsive-right-handle").on("mousedown", function(e) {

                $('.responsive-right-handle').tooltip("hide");

                window.responsiveModeRMDown = true;
                body.addClass("wyp-clean-look wyp-responsive-resizing wyp-responsive-resizing-right wyp-hide-borders-now");

                if($(".wyp-selector-mode").hasClass("active")){
                    window.selectorWasActive = true;
                }else{
                    window.selectorWasActive = false;
                }

                if ($(".wyp-ruler-btn").hasClass("active")) {
                    window.rulerWasActive = true;
                } else {
                    window.rulerWasActive = false;
                    $(".wyp-ruler-btn").trigger("click").removeClass("active");
                }

                if ($(".wyp-selector-mode").hasClass("active") && is_content_selected() === false) {
                    $(".wyp-selector-mode").trigger("click");

                    window.SelectorDisableResizeRight = true;
                }

            });


            /* ---------------------------------------------------- */
            /* Responsive Right Handle                              */
            /* ---------------------------------------------------- */
            mainDocument.on("mousemove", function(e) {

                if (window.responsiveModeRMDown === true) {

                    var hasClass = mainBody.hasClass("wyp-css-editor-active");
                    var ww = $(window).width();

                    if (hasClass === true) {
                        e.pageX = e.pageX - read_cookie("cssEditorWidth", 400) - 10;
                    } else {
                        e.pageX = e.pageX - window.leftbarWidth - 10;
                    }

                    // Min 320
                    if (e.pageX < 320) {
                        e.pageX = 320;
                    }

                    // Max full-80 W
                    if (hasClass) {
                        if (e.pageX > ww - 80 - read_cookie("cssEditorWidth", 400)) {
                            e.pageX = ww - 80 - read_cookie("cssEditorWidth", 400);
                        }
                    } else {
                        if (e.pageX > ww - 80 - 49) {
                            e.pageX = ww - 80 - 49;
                        }
                    }

                    $("#iframe").width(e.pageX);

                    draw_responsive_handle();
                    update_responsive_size_notice();


                    $(".breakpoint-bar div").removeClass("active");

                    // add active class to current
                    $(".breakpoint-bar div").each(function(){

                        var element = $(this);

                        if(element.attr("data-breakpoint") == $("#iframe").width()){
                            element.addClass("active");
                        }

                    });

                }
            });


            /* ---------------------------------------------------- */
            /* Responsive Right Handle                              */
            /* ---------------------------------------------------- */
            mainDocument.on("mouseup", function() {

                if (window.responsiveModeRMDown === true) {

                    if(is_animation_manager()){
                        animation_manager();
                    }

                    window.responsiveModeRMDown = false;

                    if (window.SelectorDisableResizeBottom === false) {
                        draw();
                    }

                    body.removeClass("wyp-clean-look wyp-responsive-resizing wyp-responsive-resizing-right");

                    setTimeout(function() {
                        body.removeClass("wyp-hide-borders-now");
                        draw_tooltip();
                    }, 25);

                    if (window.SelectorDisableResizeRight === true) {
                        window.SelectorDisableResizeRight = false;
                    }

                    if (window.rulerWasActive === false) {
                        $(".wyp-ruler-btn").addClass("active").trigger("click");
                    }


                    if(window.selectorWasActive === true){
                        if($(".wyp-selector-mode").hasClass("active") === false){
                            $(".wyp-selector-mode").trigger("click");
                        }
                    }else{
                        if($(".wyp-selector-mode").hasClass("active")){
                            $(".wyp-selector-mode").trigger("click");
                        }
                    }

                    // Update options
                    insert_default_options();

                    setTimeout(function() {
                        $(".reset-enable").removeClass("reset-enable");
                    }, 10);

                }

            });

            // Bottom
            window.responsiveModeBMDown = false;
            window.SelectorDisableResizeBottom = false;


            /* ---------------------------------------------------- */
            /* Responsive Bottom Handle                             */
            /* ---------------------------------------------------- */
            $(".responsive-bottom-handle").on("mousedown", function() {
                window.responsiveModeBMDown = true;
                body.addClass("wyp-clean-look wyp-responsive-resizing wyp-responsive-resizing-bottom wyp-hide-borders-now");

                if($(".wyp-selector-mode").hasClass("active")){
                    window.selectorWasActive = true;
                }else{
                    window.selectorWasActive = false;
                }

                if ($(".wyp-ruler-btn").hasClass("active")) {
                    window.rulerWasActive = true;
                } else {
                    window.rulerWasActive = false;
                    $(".wyp-ruler-btn").trigger("click");
                }

                if ($(".wyp-selector-mode").hasClass("active") && is_content_selected() === false) {
                    $(".wyp-selector-mode").trigger("click");
                    window.SelectorDisableResizeBottom = true;
                }

            });


            /* ---------------------------------------------------- */
            /* Responsive Bottom Handle                             */
            /* ---------------------------------------------------- */
            mainDocument.on("mousemove", function(e) {
                if (window.responsiveModeBMDown === true) {

                    var ext = 0;
                    if(mainBody.hasClass("wyp-html-mod-active")){
                        ext = 42;
                    }

                    if ($(this).find("#iframe").length > 0) {
                        e.pageY = e.pageY - 48 - ext;
                    }

                    // Min 320
                    if (e.pageY < 320) {
                        e.pageY = 320;
                    }

                    // Max full-80 H
                    if (e.pageY > $(window).height() - 80 - 40 - ext) {
                        e.pageY = $(window).height() - 80 - 40 - ext;
                    }

                    $("#iframe").height(e.pageY);
                    draw_responsive_handle();

                    update_responsive_size_notice();

                }
            });


            /* ---------------------------------------------------- */
            /* Responsive Bottom Handle                             */
            /* ---------------------------------------------------- */
            mainDocument.on("mouseup", function() {

                if (window.responsiveModeBMDown === true) {
                    window.responsiveModeBMDown = false;

                    if (window.SelectorDisableResizeBottom === false) {
                        draw();
                    }

                    body.removeClass("wyp-clean-look wyp-responsive-resizing wyp-responsive-resizing-bottom");

                    setTimeout(function() {
                        body.removeClass("wyp-hide-borders-now");
                    }, 25);

                    if (window.SelectorDisableResizeBottom === true) {
                        $(".wyp-selector-mode").trigger("click");
                        window.SelectorDisableResizeBottom = false;
                    }

                    if (window.rulerWasActive === false) {
                        $(".wyp-ruler-btn").trigger("click");
                    }

                    if(window.selectorWasActive === true){
                        if($(".wyp-selector-mode").hasClass("active") === false){
                            $(".wyp-selector-mode").trigger("click");
                        }
                    }else{
                        if($(".wyp-selector-mode").hasClass("active")){
                            $(".wyp-selector-mode").trigger("click");
                        }
                    }

                    // Update options
                    insert_default_options();

                    setTimeout(function() {
                        $(".reset-enable").removeClass("reset-enable");
                    }, 10);

                }

            });


            // Last Key
            var lastKeyUpAt = 0;

            /* ---------------------------------------------------- */
            /* Shortcuts & Keys : KeyUp                             */
            /* ---------------------------------------------------- */
            mainDocument.on("keyup", function(e) {

                lastKeyUpAt = new Date();

                // Getting current tag name.
                var tag = e.target.tagName.toLowerCase();

                // Control
                var ctrlKey = false;
                var tagType = false;

                // Check If is CTRL Key.
                if ((e.ctrlKey === true || e.metaKey === true)) {
                    ctrlKey = true;
                }

                // Stop if this target is input or textarea.
                if (tag == 'input' || tag == 'textarea') {
                    tagType = true;
                }

                // Multi selecting support
                if(ctrlKey === false && tagType === false){
                    body.removeClass("wyp-control-key-down");
                    iframe.find(".wyp-multiple-selected").removeClass("wyp-multiple-selected");
                    iframe.find(".wyp-selected-others-multiable-box").remove();
                }

            });


            /* ---------------------------------------------------- */
            /* Shortcuts & Keys : KeyDown                           */
            /* ---------------------------------------------------- */
            mainDocument.on("keydown", function(e) {

                // get current time
                var keyDownAt = new Date();

                // Getting current tag name.
                var tag = e.target.tagName.toLowerCase();

                // Getting Target Element
                var targetElement = $(e.target);

                // Getting Keycode.
                var code = e.keyCode || e.which;

                // Control
                var ctrlKey = false;
                var shifted = e.shiftKey ? true : false;
                var tagType = false;
                var selector;

                // Check If is CTRL Key.
                if ((e.ctrlKey === true || e.metaKey === true)){
                    ctrlKey = true;
                }

                // Stop if this target is input or textarea.
                if (tag == 'input' || tag == 'textarea') {
                    tagType = true;
                }


                var targetParent;

                // Apply css manager value change
                if (code == 13 && ctrlKey === false && tagType === true && targetElement.is(".css-manager-value-input")) {

                    targetParent = targetElement.parent();
                    targetParent.removeClass("css-manager-disabled");

                    // return to default if not have data
                    if(targetElement.val().length < 1){
                        targetElement.val(targetParent.attr("data-css-manager-value")).trigger("keyup");
                    }

                    targetParent.attr("data-css-manager-value", targetElement.val());

                    // Add ; auto
                    if(targetElement.val().slice(-1) != ';'){
                        targetElement.val(targetElement.val() + ";");
                    }

                    targetElement.blur();

                    if(targetElement.val().length > 0){
                        window.CSSManagerOriginalEvt = true;
                        targetParent.find(".css-manager-checkbox").prop('checked', false).trigger("change");
                        window.CSSManagerOriginalEvt = false;
                    }

                    return false;
                }

                // restore the change with ESC on css manager value input
                if (code == 27 && ctrlKey === false && tagType === true && targetElement.is(".css-manager-value-input")) {

                    targetParent = targetElement.parent();
                    targetElement.val(targetParent.attr("data-css-manager-value")).trigger("keyup");
                    targetElement.blur();
                    return false;
                }

                // Hide. delete
                if (code == 46 && ctrlKey === false && tagType === false) {
                    insert_rule(null, "display", "none", '');
                    option_change();
                    clean();
                    gui_update();
                }

                // go parent element
                if (code == 80 && ctrlKey === false && tagType === false) {
                    if(is_content_selected()){
                        if(get_selected_element().parent().length > 0){

                            if (get_selected_element().parent()[0].nodeName.toLowerCase() != "html") {

                                // add class to parent.
                                get_selected_element().parent().addClass("wyp-will-selected");

                                // clean
                                clean();

                                // Get parent selector.
                                var parentSelector = $.trim(get_parents(iframe.find(".wyp-will-selected"), "default"));

                                // Set Selector
                                set_selector(parentSelector, null, true);

                                return false;

                            }

                        }
                    }
                }

                // ESC for custom selector.
                if (code == 27 && ctrlKey === false) {

                    // Was resizing?
                    if(is_resizing() || is_visual_editing()){
                        return false;
                    }

                    if($(".sweet-alert").css("display") == 'none' || $(".sweet-alert").length === 0){

                        if($(".wyp-popup-background").css("display") != 'none'){
                            $(".wyp-info-modal-close").trigger("click");
                            return false;
                        }

                        if ($(".wyp-button-target.active").length <= 0) {
                            $("#wyp-button-target-input").val("");
                            $(".wyp-button-target").trigger("click");
                            return false;
                        }

                    }

                }

                if(ctrlKey === false && tagType === false && shifted === true){

                    setTimeout(function() {

                        // Compare key down time with key up time
                        if (+keyDownAt > +lastKeyUpAt && is_content_selected()){

                            body.addClass("wyp-control-key-down");

                            var recentElement = iframe.find(".wyp-recent-hover-element");

                            if(recentElement.length > 0){
                                recentElement.trigger("mouseover");
                            }

                        }

                    }, 220);

                }


                // UP DOWN keys for move selected element
                if (ctrlKey === false && tagType === false){
                    if(code == 38 || code == 40 || code == 37 || code == 39){
                        if(is_content_selected() && is_dragging() === false){
                            e.preventDefault();

                            var el = get_selected_element();
                            var t = parseInt(el.css("top"));
                            var l = parseInt(el.css("left"));
                            var r = parseInt(el.css("right"));
                            var b = parseInt(el.css("bottom"));
                            var f = 1;

                            if(shifted){
                                f = 10;
                            }

                            if(code == 38){
                                t = t-f;
                            }else if(code == 40){
                                t = t+f;
                            }

                            if(code == 37){
                                l = l-f;
                            }else if(code == 39){
                                l = l+f;
                            }

                            t = t+"px";
                            l = l+"px";

                            // Insert new data. TOP BOTTOM
                            if(code == 38 || code == 40){

                                insert_rule(null, "top", t, '');

                                if (parseFloat(t) + parseFloat(b) !== 0) {
                                    insert_rule(null, "bottom", "auto", '');
                                }

                            }

                            // Insert new data. LEFT RIGHT
                            if(code == 37 || code == 39){

                                insert_rule(null, "left", l, '');

                                if (parseFloat(l) + parseFloat(r) !== 0) {
                                    insert_rule(null, "right", "auto", '');
                                }

                            }

                            var position = el.css("position");

                            if(position == 'static' || position == 'relative'){
                                insert_rule(null, "position", "relative", '');
                            }

                            if ($("#position-static").parent().hasClass("active") || $("#position-relative").parent().hasClass("active")){
                                $("#position-relative").trigger("click");
                            }

                            // Set default values for top and left options.
                            if ($("li.position-option.active").length > 0) {
                                $("#top-group,#left-group").each(function() {
                                set_default_value(get_option_id(this));
                            });
                            } else {
                                $("li.position-option").removeAttr("data-loaded"); // delete cached data.
                            }

                            option_change();

                        }
                    }
                }

                // Disable backspace key.
                if (code == 8 && ctrlKey === false && tagType === false) {
                    e.preventDefault();
                    return false;
                }

                // Z Key
                if (code == 90 && ctrlKey == true && tagType === false) {

                    e.preventDefault();

                    clearTimeout(window.historyDelay);

                    if(mainBody.hasClass("wyp-history-delay")){

                        window.historyDelay = setTimeout(function(){
                            undo_changes();
                        },220);

                    }else{
                        setTimeout(function(){
                            undo_changes();
                        },50);
                    }

                    return false;

                }


                // G Key | Toggle smart guide
                if (code == 71 && ctrlKey === true && tagType === false) {
                    e.preventDefault();

                    body.toggleClass("wyp-smart-guide-disabled");
                    return false;
                }


                // Y Key
                if (code == 89 && ctrlKey === true && tagType === false) {

                    e.preventDefault();

                    clearTimeout(window.historyDelay);

                    if(mainBody.hasClass("wyp-history-delay")){

                        window.historyDelay = setTimeout(function(){
                            redo_changes();
                        },220);

                    }else{
                        setTimeout(function(){
                            redo_changes();
                        },50);
                    }

                    return false;

                }

                // ESC
                if (code == 27 && ctrlKey === false && tagType === false) {

                    e.preventDefault();

                    // ESC close customize type window.
                    if($("#wyp-customizing-type-frame").length > 0){
                        if($("#wyp-customizing-type-frame").css("display") == 'block'){
                                $("#wyp-current-page").removeClass("active");
                                $("#wyp-customizing-type-frame").css("display", "none");
                            return false;
                        }
                    }

                    // ESC Disable picker
                    if(mainBody.hasClass("wyp-element-picker-active")){
                        mainBody.removeClass("wyp-element-picker-active");
                        $(".wyp-element-picker").removeClass("active");
                        return false;
                    }

                    // ESC hide image uplaoder
                    if($("#image_uploader").css("display") == 'block'){
                        $("#image_uploader").toggle();
                        $("#image_uploader_background").toggle();
                        return false;
                    }

                    if (mainBody.hasClass("autocomplete-active") === false && $(".iris-picker:visible").length === 0 && ($(".sweet-alert").css("display") == 'none') || $(".sweet-alert").length === 0) {

                        if (!mainBody.hasClass("css-editor-close-by-editor")) {

                            if ($("#cssEditorBar").css("display") == 'block') {
                                $(".css-editor-btn").trigger("click");
                                return false;
                            } else if ($("#context-menu-layer:visible").length > 0) {
                                $("#context-menu-layer,.context-menu-list").hide();
                                return false;
                            } else if (is_content_selected()) {
                                clean();
                                gui_update();
                                return false;
                            }

                        } else {
                            mainBody.removeClass("css-editor-close-by-editor");
                            return false;
                        }

                    } else {
                        body.removeClass("wyp-select-open");
                    }

                }

                // Space key go to selected element
                if (code == 32 && shifted === false && ctrlKey === false && tagType === false && is_content_selected()) {

                    e.preventDefault();

                    var element = get_selected_element();

                    if (iframe.find(".wyp-selected-tooltip").hasClass("wyp-fixed-tooltip") || iframe.find(".wyp-selected-tooltip").hasClass("wyp-fixed-tooltip-bottom")) {
                        var height = parseInt($(window).height() / 2);
                        var selectedHeight = parseInt(element.height() / 2);
                        var scrollPosition = selectedHeight + element.offset().top - height;
                        iframe.scrollTop(scrollPosition);
                    }

                    return false;

                }

                // Space key select hovered element
                if (code == 32 && shifted === false && tagType === false && is_content_selected() === false && $(".wyp-selector-mode").hasClass("active")) {

                    e.preventDefault();

                    if(iframe.find(".wyp-selected").length > 0){


                        if(mainBody.hasClass("wyp-single-inspector-active")){
                            selector = $.trim(get_parents(null, "sharp"));
                        }else{
                            selector = $.trim(get_parents(null, "default"));
                        }

                        set_selector(selector, get_selected_element(), true);

                    }

                    return false;

                }

                // Space key select multiple hovered element
                if (code == 32 && shifted === true && tagType === false && is_content_selected() === true && $(".wyp-selector-mode").hasClass("active")) {

                    e.preventDefault();

                    var selectorCurrent = get_current_selector();
                    var selectorNew = get_parents(iframe.find(".wyp-multiple-selected"), "sharp");
                    iframe.find(".wyp-selected-others-multiable-box").remove();
                    iframe.find(".wyp-multiple-selected").addClass("wyp-selected-others").removeClass("wyp-multiple-selected");
                    set_selector(selectorCurrent+","+selectorNew, get_selected_element(), true);

                    return false;

                }

                // R Key
                if (code == 82 && ctrlKey === false && tagType === false) {
                    e.preventDefault();
                    $(".wyp-responsive-btn").trigger("click");
                    return false;
                }

                // M Key
                if (code == 77 && ctrlKey === false && tagType === false) {
                    e.preventDefault();
                    $(".wyp-ruler-btn").trigger("click");
                    return false;
                }

                // W Key
                if (code == 87 && ctrlKey === false && tagType === false) {
                    e.preventDefault();
                    $(".wyp-wireframe-btn").trigger("click");
                    return false;
                }

                // D Key
                if (code == 68 && ctrlKey === false && tagType === false) {
                    e.preventDefault();
                    $(".info-btn").trigger("click");
                    return false;
                }

                // H Key
                if (code == 72 && ctrlKey === false && tagType === false) {
                    e.preventDefault();
                    css_editor_toggle();
                    return false;
                }

                // L Key
                if (code == 76 && ctrlKey === false && tagType === false && is_dragging() === false) {
                    e.preventDefault();
                    body.toggleClass("wyp-hide-borders-now");
                    return false;
                }

                // " Key
                if (code == 162 && ctrlKey === false && tagType === false && mainBody.hasClass("process-by-code-editor") === false) {
                    e.preventDefault();

                    if (is_animate_creator()) {
                        swal({title: "Sorry.",text: l18_cantEditor,animation: false,customClass: "editor-style-ok"});
                        return false;
                    }

                    $(".css-editor-btn").trigger("click");
                    return false;
                }

                // " For Chrome Key
                if (code == 192 && ctrlKey === false && tagType === false && mainBody.hasClass("process-by-code-editor") === false) {
                    e.preventDefault();

                    if (is_animate_creator()) {
                        swal({title: "Sorry.",text: l18_cantEditor,animation: false,customClass: "editor-style-ok"});
                        return false;
                    }

                    $(".css-editor-btn").trigger("click");
                    return false;
                }

                // F Key
                if (code == 70 && ctrlKey === false && tagType === false) {
                    e.preventDefault();
                    $(".wyp-button-target").trigger("click");
                    return false;
                }

            });


            /* ---------------------------------------------------- */
            /* Up/Down keys for prefixes                            */
            /* ---------------------------------------------------- */
            $(".wyp-css-format").keydown(function(e){

                if($(this).val() == 'xp'){
                    $(this).val("px");
                }

                var code = e.keyCode || e.which;

                if (code == 40 || code == 38) {

                    e.preventDefault();
                    // em -> % -> px
                    if ($(this).val() == 'em') {
                        $(this).val("%");
                    } else if ($(this).val() == '%') {
                        $(this).val("px");
                    } else if ($(this).val() == 'px') {
                        $(this).val("em");
                    }

                }

            });


            /* ---------------------------------------------------- */
            /* ESC Close Ace Editor                                 */
            /* ---------------------------------------------------- */
            editor.commands.addCommand({

                name: 'close',
                bindKey: {
                    win: 'ESC',
                    mac: 'ESC'
                },
                exec: function() {

                    $(".css-editor-btn").trigger("click");
                    mainBody.removeClass("process-by-code-editor").addClass("css-editor-close-by-editor");

                },

                readOnly: false

            });


            /* ---------------------------------------------------- */
            /* Disable Form submission in iframe                    */
            /* ---------------------------------------------------- */
            iframe.find("form").submit(function(e) {
                e.preventDefault();
                return false;
            });


            /* ---------------------------------------------------- */
            /* Has Redo? Has Undo?                                  */
            /* ---------------------------------------------------- */
            function check_undoable_history(){

                // Has Undo?
                if(editor.session.getUndoManager().hasUndo() === false){
                    $(".undo-btn").addClass("disabled");
                }else{
                    $(".undo-btn").removeClass("disabled");
                }

                // Has Redo?
                if(editor.session.getUndoManager().hasRedo() === false){
                    $(".redo-btn").addClass("disabled");
                }else{
                    $(".redo-btn").removeClass("disabled");
                }

            }



            /* ---------------------------------------------------- */
            /* Measuring Tool                                       */
            /* ---------------------------------------------------- */
            $(".wyp-ruler-btn").click(function() {

                if(is_content_selected() === false){
                    clean();
                }

                body.toggleClass("wyp-metric-disable");
                gui_update();

                // Disable selector mode.
                if ($(this).hasClass("active") === false) {
                    if ($(".wyp-selector-mode.active").length > 0) {
                        window.SelectorModeWasActive = true;
                        $(".wyp-selector-mode").removeClass("active");
                    }
                } else {
                    $(".wyp-selector-mode").addClass("active");
                }

                return false;
            });


            /* ---------------------------------------------------- */
            /* Single Inspector Tool                                */
            /* ---------------------------------------------------- */
            $(".wyp-sharp-selector-btn").click(function() {

                body.toggleClass("wyp-single-inspector-active");

                // Update variable
                if(body.hasClass('wyp-single-inspector-active')){
                    window.singleInspector = true;
                }else{
                    window.singleInspector = false;
                }

                if ($(".wyp-selector-mode.active").length === 0) {
                    $(".wyp-selector-mode").trigger("click");
                }
            });


            /* ---------------------------------------------------- */
            /* Up / Down keys for property input value              */
            /* ---------------------------------------------------- */
            $(".wyp-css-value").keydown(function(e) {

                var code = e.keyCode || e.which;

                if (code == 38) {
                    e.preventDefault();
                    $(this).val(parseFloat($(this).val()) + parseFloat(1));
                }

                if (code == 40) {
                    e.preventDefault();
                    $(this).val(parseFloat($(this).val()) - parseFloat(1));
                }

                if(code == 13){
                    $(this).trigger("blur");
                    return false;
                }

            });


            /* ---------------------------------------------------- */
            /* Select all value on input click                      */
            /* ---------------------------------------------------- */
            $(".wyp-after-css").click(function(){
                $(this).select();
            });



            /* ---------------------------------------------------- */
            /* Number filter for numberic properties input          */
            /* ---------------------------------------------------- */
            $(".wyp-css-value").keyup(function(e) {

                // Number only
                var numbers = $(this).val().replace(/[^0-9.,-]/g,'');

                if(numbers.length === 0){
                    numbers = 0;
                }

                // non-number only
                var prefixs = $(this).val().replace(/[0-9.,-]/g,'');

                var prefixSelector = $(this).parent().find(".wyp-css-format");

                if(prefixs.length > 0){

                    $(this).val(numbers);

                    prefixSelector.val(prefixs);

                    // Focus
                    prefixSelector.val(prefixSelector.val()).trigger("focus");

                }

            });


            /* ---------------------------------------------------- */
            /* Getting option ID                                    */
            /* ---------------------------------------------------- */
            function get_option_id(element) {
                return $(element).attr("id").replace("-group", "");
            }


            /* ---------------------------------------------------- */
            /* hasAttr Fn                                           */
            /* ---------------------------------------------------- */
            $.fn.hasAttr = function(name) {
               return this.attr(name) !== undefined;
            };

            /* ---------------------------------------------------- */
            /* getCursorPosition Fn                                 */
            /* ---------------------------------------------------- */
            $.fn.getCursorPosition = function() {
                var input = this.get(0);
                if (!input) return; // No (input) element found
                if ('selectionStart' in input) {
                    // Standard-compliant browsers
                    return input.selectionStart;
                } else if (document.selection) {
                    // IE
                    input.focus();
                    var sel = document.selection.createRange();
                    var selLen = document.selection.createRange().text.length;
                    sel.moveStart('character', -input.value.length);
                    return sel.text.length - selLen;
                }
            };


            /* ---------------------------------------------------- */
            /* Redo Changes                                         */
            /* ---------------------------------------------------- */
            function redo_changes(){

                if(!editor.session.getUndoManager().hasRedo()){
                    return false;
                }

                if(is_resizing() || is_visual_editing() || is_dragging() || mainBody.hasClass("wyp-processing-now")){
                    return false;
                }

                if (is_animate_creator()) {
                    swal({title: "Sorry.",text: l18_cantUndo,animation: false,customClass: "editor-style-ok"});
                    return false;
                }

                if (is_animation_manager()) {
                    swal({title: "Sorry.",text: l18_cantUndoAnimManager,animation: false,customClass: "editor-style-ok"});
                    return false;
                }

                editor.commands.exec("redo", editor);

                body.addClass("wyp-css-data-trigger");
                $("#cssData").trigger("keyup");

                draw();

                // make transform mode if has.
                var elx = iframeBody.find(".wyp-selected");
                body.removeClass("wyp-has-transform");
                if (check_with_parents(elx, "transform", "none", "!=") === true) {
                    body.addClass("wyp-has-transform");
                }

                check_undoable_history();

                if(is_responsive_mod()){
                    update_responsive_breakpoints();
                }

            }



            /* ---------------------------------------------------- */
            /* Undo Changes                                         */
            /* ---------------------------------------------------- */
            function undo_changes(){

                if(!editor.session.getUndoManager().hasUndo()){
                    return false;
                }

                if(is_resizing() || is_visual_editing() || is_dragging() || mainBody.hasClass("wyp-processing-now")){
                        return false;
                    }

                    if (is_animate_creator()) {
                        swal({title: "Sorry.",text: l18_cantUndo,animation: false,customClass: "editor-style-ok"});
                        return false;
                    }

                    if (is_animation_manager()) {
                        swal({title: "Sorry.",text: l18_cantUndoAnimManager,animation: false,customClass: "editor-style-ok"});
                        return false;
                    }

                    editor.commands.exec("undo", editor);

                    body.addClass("wyp-css-data-trigger");
                    $("#cssData").trigger("keyup");
                    draw();

                    // Update draggable after undo
                    var elx = iframeBody.find(".wyp-selected");
                    if(elx.length > 0){

                        if(elx.css("position") == 'static'){
                            elx.css("position","relative");
                            iframeBody.find(".wyp-selected-others").css("position","relative");
                        }

                    }

                // make transform mode if has.
                body.removeClass("wyp-has-transform");
                if (check_with_parents(elx, "transform", "none", "!=") === true) {
                    body.addClass("wyp-has-transform");
                }

                check_undoable_history();

                if(is_responsive_mod()){
                    update_responsive_breakpoints();
                }

            }


            /* ---------------------------------------------------- */
            /* IsDefined                                            */
            /* ---------------------------------------------------- */
            function isDefined(a){
                if(typeof a !== typeof undefined && a !== false && a != '' && a != ' ' && a != 'undefined' && a !== null){
                    return true;
                }else{
                    return false;
                }
            }


            /* ---------------------------------------------------- */
            /* IsUndefined                                          */
            /* ---------------------------------------------------- */
            function isUndefined(a){
                if(typeof a === typeof undefined || a === false || a === '' || a == ' ' || a == 'undefined' || a === null){
                    return true;
                }else{
                    return false;
                }
            }


            /* ---------------------------------------------------- */
            /* CSSImportant Fn                                      */
            /* ---------------------------------------------------- */
            $.fn.cssImportant = function(rule, value) {

                // Set default CSS.
                this.css(rule, value);

                // add important
                $(this).attr("style", this.attr("style").replace(rule + ": " + value, rule + ": " + value + " !important"));

            };


            /* ---------------------------------------------------- */
            /* Live Preview Button                                  */
            /* ---------------------------------------------------- */
            $(".wyp-button-live").click(function() {

                var el = $(this);
                var href = el.attr("data-href");
                el.addClass("live-btn-loading");

                if (mainBody.hasClass("yp-yellow-pencil-demo-mode")) {
                    swal({title: "Sorry.",text: l18_live_preview,type: "info",animation: false,customClass: "editor-style-ok"});
                    el.removeClass("live-btn-loading");
                    return false;
                }

                var type, allcss = '';
                iframe.find(".wyp-data-updated").each(function(){

                    // Current source type: Global, template, single.
                    type = $(this).attr("data-source-mode");

                    // Get clean CSS
                    allcss += get_clean_css(true, type);

                });

                var previewAjax = $.post( ajaxurl, {
                    action: "wyp_preview_data_save",
                    wyp_data: allcss
                });

                // Done.
                previewAjax.complete(function(data) {
                    el.removeClass("live-btn-loading");
                    window.open(href, href);
                    return false;
                });

            });


            /* ---------------------------------------------------- */
            /* Visitor view avatar                                  */
            /* ---------------------------------------------------- */
            $(".wyp-logout-btn").click(function(e){

                if (mainBody.hasClass("yp-yellow-pencil-demo-mode")) {
                    e.preventDefault();
                    swal({title: "Sorry.",text: l18_visitor_view,type: "info",animation: false,customClass: "editor-style-ok"});
                }

                $(".wyp-logout-btn").tooltip("hide");

            });


            /* ---------------------------------------------------- */
            /* Setting the Selector                                 */
            /* ---------------------------------------------------- */
            function set_selector(selector,selected, cleanm) {

                if(cleanm){
                    clean();
                }

                window.setSelector = selector;

                var element = iframe.find(get_foundable_query(selector,true,false,false));

                body.attr("data-clickable-select", selector);

                if (iframe.find(".wyp-will-selected").length > 0) {
                    iframe.find(".wyp-will-selected").trigger("mouseover").trigger("click");
                    iframe.find(".wyp-will-selected").removeClass("wyp-will-selected");
                } else if(selected !== null){
                    selected.trigger("mouseover").trigger("click");
                }else{
                    element.filter(":visible").first().trigger("mouseover").trigger("click");
                }

                if (element.length > 1) {
                    element.addClass("wyp-selected-others");
                    get_selected_element().removeClass("wyp-selected-others");
                }

                body.addClass("wyp-content-selected");

                window.orginalHeight = parseFloat(element.css("height").replace(/px/g,''));
                window.orginalWidth = parseFloat(element.css("width").replace(/px/g,''));

                if(element.css("float") == 'right'){
                    body.addClass("wyp-element-float");
                }else{
                    body.removeClass("wyp-element-float");
                }

                var ttag = element.prop("tagName").toLowerCase();
                if(ttag == 'ul' || ttag == 'ol' || ttag == 'li'){
                    body.addClass("wyp-element-list");
                    }else{
                    body.removeClass("wyp-element-list");
                }

                if($(".advanced-info-box").css("display") == 'block'){
                    update_design_information("all");
                }

                var tooltip = iframe.find(".wyp-selected-tooltip");
                tooltip.html("<small class='wyp-tooltip-small'>" + iframe.find(".wyp-selected-tooltip small").html() + "</small> " + selector);

                // Use native hover system
                if (selector.match(/:hover/g)) {

                    body.addClass("yp-selector-hover");
                    body.attr("data-wyp-selector", ":hover");
                    $(".wyp-contextmenu-hover").addClass("wyp-active-contextmenu");
                    iframe.find(".wyp-selected-tooltip span").remove();
                    selector = selector.replace(/:hover/g, "");

                    add_class_to_parents();

                }

                // Use native focus system
                if (selector.match(/:focus/g)) {

                    body.addClass("yp-selector-focus");
                    body.attr("data-wyp-selector", ":focus");
                    $(".wyp-contextmenu-focus").addClass("wyp-active-contextmenu");
                    iframe.find(".wyp-selected-tooltip span").remove();
                    selector = selector.replace(/:focus/g, "");

                    add_class_to_parents();

                }

                // Use native visited system
                if (selector.match(/:visited/g)) {

                    body.addClass("yp-selector-visited");
                    body.attr("data-wyp-selector", ":visited");
                    iframe.find(".wyp-selected-tooltip span").remove();
                    selector = selector.replace(/:visited/g, "");

                    add_class_to_parents();

                }

                // Use native visited system
                if (selector.match(/:link/g)) {

                    body.addClass("yp-selector-link");
                    body.attr("data-wyp-selector", ":link");
                    iframe.find(".wyp-selected-tooltip span").remove();
                    selector = selector.replace(/:link/g, "");

                    add_class_to_parents();

                }

                // Use native visited system
                if (selector.match(/:active/g)) {

                    body.addClass("yp-selector-active");
                    body.attr("data-wyp-selector", ":active");
                    iframe.find(".wyp-selected-tooltip span").remove();
                    selector = selector.replace(/:active/g, "");

                    add_class_to_parents();

                }

                css_editor_toggle(true); // show if hide

                body.attr("data-clickable-select", selector);

                insert_default_options();

                gui_update();

                draw();

                if(is_animation_manager()){
                    animation_manager();
                }

                // Update the element informations.
                if($(".advanced-info-box").css("display") == 'block'){
                    update_design_information("all");
                }

                window.setSelector = false;

            }


            /* ---------------------------------------------------- */
            /* Set CSS to the Ace Editor                            */
            /* ---------------------------------------------------- */
            editor.setValue(get_clean_css(true));


            /* ---------------------------------------------------- */
            /* Clean Undo Manager                                   */
            /* ---------------------------------------------------- */
            editor.getSession().setUndoManager(new ace.UndoManager());


            /* ---------------------------------------------------- */
            /* Tooltips                                             */
            /* ---------------------------------------------------- */
            $('[data-toggle="tooltipTopBottom"]').tooltip({
                animation: false,
                container: ".editor-panel",
                 template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
                html: true
            });

            $('[data-toggle="tooltip-bar"]').tooltip({
                animation: false,
                container: "body",
                html: true
            });

            $('.info-btn').on('show.bs.tooltip', function () {
                if($(this).hasClass("active")){
                    return false;
                }
            });

            $(".wyp-none-btn").tooltip({
                animation: false,
                container: '.editor-panel',
                title: l18_none
            });

            $('[data-toggle="tooltipAnimGenerator"]').tooltip({
                animation: false,
                html: true
            });

            $('[data-toggle="tooltip"]').tooltip({

                animation: false,
                container: ".editor-panel",
                html: true

            }).on('shown.bs.tooltip', function () {

                // Don't show if popover visible
                if($(".popover").length > 0){
                    $(this).tooltip("hide");
                }

            });


            /* ---------------------------------------------------- */
            /* Popovers                                             */
            /* ---------------------------------------------------- */
            $('[data-toggle="popover"]').popover({
                animation: false,
                trigger: 'hover',
                container: ".editor-panel"
            });

            $('.wyp-option-group,.wyp-advanced-option').on('shown.bs.popover', function () {

                // Don't show if popover visible
                if(parseFloat($(".popover").css("top")) < 80){
                    $(this).popover("hide");
                }

            });


            /* ---------------------------------------------------- */
            /* Setup The plugin                                     */
            /* ---------------------------------------------------- */
            body.addClass("yp-yellow-pencil");
            body.addClass("yellow-pencil-ready");


            /* ---------------------------------------------------- */
            /* Editor Panel: Draggable                              */
            /* ---------------------------------------------------- */
            $(".editor-panel").draggable({

                handle: ".editor-panel-top",
                cancel: ".editor-panel-top *",

                start: function(){

                    // create a fake layer
                    mainBody.append("<div class='fake-layer'></div>");

                    // Disable fixed right panel
                    if(mainBody.hasClass("wyp-fixed-right-panel")){

                        mainBody.removeClass("wyp-fixed-right-panel");

                        // be sure panel visible
                        css_editor_toggle(true);

                        create_cookie('FixedrightPanel', "0");

                        // Default is 300 width in fixed, set 288 when go default if width is default.
                        if($(".editor-panel").width() == 300){
                            var width = 288;
                            mainBody.append("<style id='rightpanel-personalized-view'>.wyp-fixed-right-panel:not(.wyp-animate-manager-active) #iframe{width:calc(100% - "+width+"px - 46px) !important;}.editor-panel{width:"+width+"px !important;}</style>");
                        }

                        draw();

                    }

                },
                drag: function(event, ui){

                    // Max & Min
                    ui.position.top = Math.max(-30, ui.position.top);
                    ui.position.top = Math.min($(window).height() - 30, ui.position.top);

                    // Show placeover for fixed right
                    if(ui.position.left > ($(window).width() - $(".editor-panel").width())){
                        $(".wyp-right-panel-placeholder").show();
                    }else{
                        $(".wyp-right-panel-placeholder").hide();
                    }

                    gui_update();

                },
                stop: function(event, ui){

                    $(".fake-layer").remove();

                    setTimeout(function(){
                        update_gradient_pointers();
                    },5);

                    // remove placeholder
                    $(".wyp-right-panel-placeholder").hide();

                    // Go fixed right mode.
                    if(ui.position.left > ($(window).width() - $(".editor-panel").width())){

                        mainBody.addClass("wyp-fixed-right-panel");

                        // Default is 288 width, set 300 when go fixed if width is default.
                        if($(".editor-panel").width() == 288){
                            var width = 300;
                            mainBody.append("<style id='rightpanel-personalized-view'>.wyp-fixed-right-panel:not(.wyp-animate-manager-active) #iframe{width:calc(100% - "+width+"px - 46px) !important;}.editor-panel{width:"+width+"px !important;}</style>");
                        }

                        draw();

                        create_cookie('FixedrightPanel', "1");

                        gui_update();

                    }

                }
            });



            /* ---------------------------------------------------- */
            /* Add Fixed mode by cookie                             */
            /* ---------------------------------------------------- */
            if(read_cookie("FixedrightPanel", "0") == 1){
                mainBody.addClass("wyp-fixed-right-panel");
            }



            /* ---------------------------------------------------- */
            /* Animation Generator Bar:  Draggable                  */
            /* ---------------------------------------------------- */
            $(".anim-bar").draggable({

                handle: ".anim-bar-title",

                start: function(){

                    mainBody.append("<div class='fake-layer'></div>");

                },
                drag: function(event, ui){

                    ui.position.top = Math.max( -30, ui.position.top );

                },

                stop: function() {

                    $(".anim-bar").addClass("anim-bar-dragged");

                    $(".fake-layer").remove();

                    setTimeout(function(){
                        update_gradient_pointers();
                    },5);

                }

            });



            /* ---------------------------------------------------- */
            /* Hide Borders on panel hover                          */
            /* ---------------------------------------------------- */
            $(".editor-leftbar,.editor-panel,.metric").hover(function() {
                if (is_content_selected() === false) {
                    clean();
                }
            });


            /* ---------------------------------------------------- */
            /* Align center animation generator panel               */
            /* ---------------------------------------------------- */
            function update_animate_creator_view() {
                if (!$(".anim-bar").hasClass("anim-bar-dragged")) {
                    $(".anim-bar").css("left", parseFloat($(window).width() / 2) - ($(".anim-bar").width() / 2));
                }
            }


            /* ---------------------------------------------------- */
            /* Editing Scene 10% 50% 100%                           */
            /* ---------------------------------------------------- */
            $(document).on('keydown keyup', '.scenes .scene input', function(e){

                $(this).val(number_filter($(this).val().replace(/\-/g,'')));

                if (parseFloat($(this).val()) > 100) {
                    $(this).val('100');
                }

                if (parseFloat($(this).val()) < 0) {
                    $(this).val('0');
                }

            });



            /* ---------------------------------------------------- */
            /* Remove no-click-yet class from scene                 */
            /* ---------------------------------------------------- */
            $(document).on('click', '.scenes .scene-no-click-yet', function(e){

                // Remove Class
                $(this).removeClass("scene-no-click-yet");

            });



            /* ---------------------------------------------------- */
            /* Last Scene always 100%                               */
            /* ---------------------------------------------------- */
            $(document).on('keyup keydown blur', '.scenes .scene:not(.scene-add):last input', function(e) {

                $(this).val('100');

            });


            /* ---------------------------------------------------- */
            /* First scene always 0%                                */
            /* ---------------------------------------------------- */
            $(document).on('keyup keydown blur', '.scenes .scene:first-child input', function(e) {

                $(this).val('0');

            });


            /* ---------------------------------------------------- */
            /* Creating Animation                                   */
            /* ---------------------------------------------------- */
            function wyp_create_anim(name) {

                // Variables
                var total = $(".scenes .scene").length;
                var scenesData = '';
                var i;

                // Create animation from data.
                for (i = 1; i < total; i++) {

                    scenesData = scenesData + $(".scenes .scene-" + i + " input").val() + "% {";

                    iframe.find(".wyp-anim-scenes").find(".style-scene-" + i).each(function() {
                        scenesData = scenesData + (($(this).html().match(/\{(.*?)\}/g)).toString().replace("{", "").replace("}", "")) + ";";
                    });

                    scenesData = scenesData + "}";

                }

                var scenesDataReverse = scenesData.replace(/\}/g, "}YKSYXA");
                var scenesDataReverseArray = scenesDataReverse.split("YKSYXA").reverse();

                // wait
                var watingForAdd = [];
                var added = '{';
                var x,lineData,rules,countT,count,lineAll;

                for (i = 1; i < scenesDataReverseArray.length; i++) {

                    // Anim part example data.
                    lineData = $.trim(scenesDataReverseArray[i]);
                    lineAll = $.trim(scenesDataReverseArray[i]);
                    lineData = lineData.split("{")[1].split("}")[0];

                    // If is last ie first. ie 0%, no need.
                    if (scenesDataReverseArray.length - 1 == i) {

                        for (var k = 0; k < watingForAdd.length; k++) {

                            countT = 0;

                            // Search in before
                            var crex = new RegExp("(\{|;)" + watingForAdd[k] + ":");

                            // Find current count
                            if (lineAll.match(crex) !== null) {
                                countT = parseInt(lineAll.match(crex).length);
                            }

                            if (countT === 0) {

                                var el = get_selected_element();
                                var val = el.css(watingForAdd[k]);

                                if (watingForAdd[k] == 'top' && val == 'auto') {
                                    val = "0px";
                                }

                                if (watingForAdd[k] == 'left' && val == 'auto') {
                                    val = "0px";
                                }

                                if (watingForAdd[k] == 'width' && val == 'auto') {
                                    val = el.width();
                                }

                                if (watingForAdd[k] == 'height' && val == 'auto') {
                                    val = el.height();
                                }

                                if (watingForAdd[k] == 'opacity' && val == 'auto') {
                                    val = "1";
                                }

                                if (watingForAdd[k] != 'right' && val != 'auto') {
                                    if (watingForAdd[k] != 'bottom' && val != 'auto') {
                                        var all = watingForAdd[k] + ":" + val + ";";
                                        lineAll = lineAll.toString().replace(/\}$/, '') + all + "}";
                                        scenesData = scenesData.replace("0% {", "0% {" + all);
                                        added = added + all;
                                    }
                                }

                            }

                        }

                    }

                    // Rules of this part.
                    rules = lineData.split(";");

                    // get only rules names.
                    for (x = 0; x < rules.length; x++) {
                        if (rules[x].split(":")[0] != '') {

                            var founded = rules[x].split(":")[0];
                            count = 0;

                            // Search in before
                            if (scenesData.match("{" + founded + ":") !== null) {
                                count = parseInt(scenesData.match("{" + founded + ":").length);
                            }

                            if (scenesData.match(";" + founded + ":") !== null) {
                                count = count + parseInt(scenesData.match(";" + founded + ":").length);
                            }

                            if (count < parseInt(total - 1)) {
                                watingForAdd.push(founded);
                            }

                        }
                    }

                }

                /* Adding current line data to next line datas. */
                var scenesDataNormal = scenesData.replace(/\}/g, "}TYQA");
                var scenesDataNormalArray = scenesDataNormal.split("TYQA");

                var rulesNames = [];
                var rulesValues = [];

                for (i = 0; i < scenesDataNormalArray.length; i++) {

                    // Anim part example data.
                    lineData = $.trim(scenesDataNormalArray[i]);

                    if (lineData != '' && lineData != ' ') {

                        lineData = lineData.split("{")[1].split("}")[0];

                        // Rules of this part.
                        rules = lineData.split(";");

                        // Each all rules
                        for (x = 0; x < rules.length; x++) {
                            if (rules[x].split(":")[0] != '') {

                                // Get rule name
                                var foundedName = rules[x].split(":")[0];
                                var foundedValue = rules[x].split(":")[1].split(";");

                                // Get rule value
                                if (isUndefined(foundedValue)) {
                                    foundedValue = rules[x].split(":")[1].split("}");
                                }

                                // Clean important prefix.
                                foundedValue = $.trim(foundedValue).replace(/\s+?!important/g,'').replace(/\;$/g,'');

                                // If same rule have in rulesNames, get index.
                                var index = rulesNames.indexOf(foundedName);

                                // Delete ex rule data.
                                if (index != -1) {
                                    rulesNames.splice(index, 1);
                                    rulesValues.splice(index, 1);
                                }

                                // Update with new rules.
                                rulesNames.push(foundedName);
                                rulesValues.push(foundedValue);

                            }

                        }

                        var updatedLine = "{" + lineData;

                        for (var t = 0; t < rulesNames.length; t++) {

                            var current = rulesNames[t];
                            var currentVal = rulesValues[t];

                            countT = 0;

                            // Search in this line
                            if (updatedLine.match("{" + current + ":") !== null) {
                                countT = parseInt(updatedLine.match("{" + current + ":").length);
                            }

                            if (updatedLine.match(";" + current + ":") !== null) {
                                countT = count + parseInt(updatedLine.match(";" + current + ":").length);
                            }

                            // If any rule have in rulesnames and not have in this line,
                            // so add this rule to this line.
                            if (countT < 1) {
                                updatedLine = "{" + current + ":" + currentVal + ";" + updatedLine.replace("{", "");
                            }

                        }

                        // update return value.
                        var pre = $.trim(scenesDataNormalArray[i]).split("{")[0] + "{" + lineData.replace("{", "") + "}";
                        var upNew = $.trim(scenesDataNormalArray[i]).split("{")[0] + "{" + updatedLine.replace("{", "") + "}";
                        scenesData = scenesData.replace(pre, upNew);

                    }

                }

                // Current total scenes
                total = scenesData.match(/\{/g).length;

                // Add animation name.
                scenesData = "@keyframes " + name + "{\r" + scenesData + "\r}";

                scenesData = scenesData.replace(/\}/g, "}\r");

                scenesData = scenesData.replace(";;","");

                return scenesData;

            }


            /* ---------------------------------------------------- */
            /* Play & Stop Animation                                */
            /* ---------------------------------------------------- */
            $(document).on("click", ".wyp-anim-play", function() {

                var element = $(this);

                // pause
                if(mainBody.hasClass("wyp-animate-test-playing")){

                    $(".scenes .scene-" + window.willActive + "").trigger("click");

                    element.html('<span class="dashicons dashicons-controls-play"></span>');
                    element.attr("data-original-title","Play").tooltip('fixTitle').tooltip("show");

                    body.removeClass("wyp-animate-test-playing");
                    iframe.find(".animate-test-drive").empty();
                    body.removeClass("wyp-hide-borders-now");

                    element_animation_end();

                    draw();

                    clear_animation_timer();

                    return false;

                }

                var animName = 'animationGeneratorTestAnimate';

                window.willActive = 1;

                $(".scenes .scene").each(function(i) {

                    if ($(this).hasClass("scene-active")) {
                        window.willActive = (i + 1);
                    }

                });

                if (iframe.find(".wyp-anim-scenes style").length === 0) {
                    swal({title: "Sorry.",text: l18_allScenesEmpty,animation: false,customClass: "editor-style-ok"});
                    return false;
                }

                // first scene default.
                $(".scenes .scene-1").trigger("click");

                // Generate keyframe
                var anim = wyp_create_anim(animName);

                // add webkit for test.
                anim = anim + " " + anim.replace(/@keyframes/g, '@-webkit-keyframes');

                var delay,delayWait;

                body.addClass("wyp-hide-borders-now");

                // Clean scene classes.
                var newClassList = $.trim(mainBody.attr("class").replace(/wyp-scene-[0-9]/g, ''));
                mainBody.attr("class", newClassList);

                newClassList = $.trim(iframeBody.attr("class").replace(/wyp-scene-[0-9]/g, ''));
                iframeBody.attr("class", newClassList);

                // AddClass
                body.addClass("wyp-animate-test-playing");

                // Clean
                iframe.find(".animate-test-drive").empty();

                // Animate
                iframe.find(".animate-test-drive").append("<style>" + anim + "</style>");

                // Getting duration.
                delay = $('#animation-duration-value').val();
                if ($('#animation-duration-after').val() == 's') {
                    delayWait = delay * 1000; // second to milisecond.
                } else {
                    delayWait = delay; //milisecond
                }

                // time out
                delayWait = delayWait - 10;

                // ex: 50s, for apped style.
                delay = delay + $('#animation-duration-after').val();

                // Play.
                iframe.find(".animate-test-drive").append("<style>body.wyp-animate-test-playing .wyp-selected,body.wyp-animate-test-playing .wyp-selected-others{animation-name:" + animName + " !important;animation-duration:" + delay + " !important;animation-iteration-count:1 !important;}</style>");

                // playing.
                element.html('<span class="dashicons dashicons-controls-pause"></span>');
                element.attr("data-original-title","Pause").tooltip('fixTitle').tooltip("show");

                clear_animation_timer();

                // Wait until finish. END.
                window.animationTimer4 = setTimeout(function() {

                    element.html('<span class="dashicons dashicons-controls-play"></span>');
                    element.attr("data-original-title","Play").tooltip('fixTitle');

                    if(element.is(":hover")){
                        element.tooltip('show');
                    }

                    body.removeClass("wyp-animate-test-playing");
                    iframe.find(".animate-test-drive").empty();
                    body.removeClass("wyp-hide-borders-now");

                    $(".scenes .scene-" + window.willActive + "").trigger("click");

                    element_animation_end();

                    draw();

                }, delayWait);

            });

            /* ---------------------------------------------------- */
            /* Animation name filter                                */
            /* ---------------------------------------------------- */
            $(document).on("keyup", ".editor-style-input input", function(){
                $(this).val(get_basic_id($(this).val()));
            });


            /* ---------------------------------------------------- */
            /* Save Animation                                       */
            /* ---------------------------------------------------- */
            $(".wyp-anim-save").click(function() {

                if (iframe.find(".wyp-anim-scenes style").length === 0) {
                    swal({title: "Sorry.",text: l18_allScenesEmpty,animation: false,customClass: "editor-style-ok"});
                    return false;
                }

                setTimeout(function(){

                    // update animation name.
                    var animID = get_basic_id(uppercase_first_letter(get_tag_information(get_current_selector())))+"_Animate_" + parseInt($("#wyp-animation-name-data option").length + 1);

                    $(".editor-style-input input").val(animID).trigger("focus");

                }, 20);

                swal({
                    title: "Animation name",
                    confirmButtonText: "Save Animation",
                    showCancelButton: true,
                    text: "Set a name to the animation to save.",
                    animation: false,
                    customClass: "editor-style-input"
                },function(isConfirm){

                    if(isConfirm == false){
                        return false;
                    }

                    // first scene default.
                    $(".scenes .scene-1").trigger("click");

                    var animName = $(".editor-style-input input").val();
                    var anim = wyp_create_anim(animName);

                    var posting = $.post(ajaxurl, {

                        action: "wyp_add_animation",
                        wyp_anim_data: anim,
                        wyp_anim_name: animName

                    });

                    // Add animation name
                    $("#wyp-animation-name-data").append("<option data-text='" + animName + "' data-content='" + animName + "' value='" + animName + "'>" + animName + "</option>");

                    // Get data by select
                    var data = [];
                    $("#wyp-animation-name-data option").each(function() {
                        data.push($(this).text());
                    });

                    // Autocomplete script
                    $("#wyp-animation-name").autocomplete({
                        source: data
                    });

                    // Append style
                    iframe.find(".wyp-animate-data").append("<style id='" + animName + "style'>" + anim + "</style>");
                    iframe.find(".wyp-animate-data").append("<style id='webkit-" + animName + "style'>" + anim.replace("@keyframes", "@-webkit-keyframes") + "</style>");

                    // close
                    close_animation_generator(false);

                    // apply animation to selected element
                    setTimeout(function() {
                        insert_rule(null, "animation-name", animName, '');
                        insert_rule(null, "animation-fill-mode", 'both', '');
                        $("li.animation-option").removeAttr("data-loaded");
                        $("#wyp-animation-name").val(animName).trigger("blur");
                        focus_option("animation-name");
                    }, 500);


                });

                return false;

            });


            /* ---------------------------------------------------- */
            /* Cancel Animation Generating                          */
            /* ---------------------------------------------------- */
            function close_animation_generator(disable) {

                // Clean classes.
                body.removeClass("wyp-anim-creator").removeAttr("data-anim-scene").removeClass("wyp-animate-test-playing");

                body.removeAttr("data-anim-scene");

                // Clean scene classes.
                var newClassList = $.trim(mainBody.attr("class").replace(/wyp-scene-[0-9]/g, ''));
                mainBody.attr("class", newClassList);

                newClassList = $.trim(iframeBody.attr("class").replace(/wyp-scene-[0-9]/g, ''));
                iframeBody.attr("class", newClassList);

                // Clean all scene data.
                iframe.find(".wyp-anim-scenes .scene-1,.wyp-anim-scenes .scene-2,.wyp-anim-scenes .scene-3,.wyp-anim-scenes .scene-4,.wyp-anim-scenes .scene-5,.wyp-anim-scenes .scene-6").empty();

                // Set default data again.
                insert_default_options();

                // Delete 3,4,5,6scenes.
                $(".anim-bar .scenes .scene:not(.scene-1):not(.scene-2):not(.scene-add)").each(function(){
                    $(this).remove();
                });

                // delete test data
                iframe.find(".animate-test-drive").empty();

                // show again.
                $(".scene-add").show();

                // re set default value
                if(disable){
                    insert_rule(null, "animation-name", window.animGeneratorOldAnim, '');
                    insert_rule(null, "animation-duration", window.animGeneratorOldAnimDuration, window.animGeneratorOldAnimDurationF);
                    insert_rule(null, "animation-delay", window.animGeneratorOldAnimDelay, window.animGeneratorOldAnimDelayF);
                    insert_rule(null, "animation-fill-mode", window.animGeneratorOldAnimFillMode, '');
                }

                gui_update();

                draw();

            }



            /* ---------------------------------------------------- */
            /* Focus To Option                                      */
            /* ---------------------------------------------------- */
            function focus_option(id){

                var group = $("#" + id + "-group");
                $(".wyp-this-content").hide();
                $(".editor-panel-list > li").removeClass("active");
                group.parent().prev("h3").trigger("click");
                group.parent().parent("li").show(); // be sure it visible.
                group.addClass("focus-option");

                setTimeout(function(){
                    group.removeClass("focus-option");
                }, 1600);

                gui_update();

            }



            /* ---------------------------------------------------- */
            /* Delete scene                                         */
            /* ---------------------------------------------------- */
            $(document).on("click", ".scenes .scene .scene-delete", function() {

                var current = $(this).parent().attr("data-scene").replace("scene-", "");
                var next = $(".scenes .scene").length - 1;

                // delete all
                $(".scenes .scene:not('.scene-add')").remove();

                for (var i = 1; i < next; i++) {
                    $(".scene-add").trigger("click");
                }

                if (next == 6) {
                    $(".scene-add").show();
                    update_animate_creator_view();
                }

                // Delete all styles for this scene.
                iframe.find(".wyp-anim-scenes .scene-" + current + "").empty();

                // prev active
                $(".scenes .scene-" + (current - 1) + "").trigger("click");

                return false;

            });


            /* ---------------------------------------------------- */
            /* Scene properties popover                             */
            /* ---------------------------------------------------- */
            $(document).on("mouseover", '.scene-info', function(){

                var activeScene = $(this).parent().parent().attr("data-scene");

                var content = '';

                iframe.find(".wyp-anim-scenes ." + activeScene + " style").each(function(){

                    content += "<span class='anim-info-rule'>" + $(this).attr("data-rule") + ": </span>";
                    content += "<span class='anim-info-value'>" + esc_data_value($(this).html()) + "</span>";
                    content += "<span class='anim-info-border'></span>";

                });

                $(this).tooltip("destroy");
                $(this).popover("destroy");

                if(content != ''){

                    $(this).popover({
                        animation: false,
                        title: "Scene Properties " + $(".anim-bar ." + activeScene + " input").val() + "%",
                        content: content,
                        trigger: 'hover',
                        placement: "top",
                        container: ".anim-bar",
                        html: true
                    }).popover("show");

                }else{

                    $(this).tooltip({

                        title: "No properties yet.",
                        animation: false,
                        placement: 'top',
                        trigger: 'hover',
                        container: ".anim-bar",
                        html: true
                    }).tooltip("show");

                }

            });


            /* ---------------------------------------------------- */
            /* Add Scene                                            */
            /* ---------------------------------------------------- */
            $(document).on("click", ".scenes .scene", function() {

                // if is scene add.
                if ($(this).hasClass("scene-add")) {

                    var next = $(".scenes .scene").length;

                    $(".scenes .scene-let-delete").removeClass("scene-let-delete");

                    $(".scene-add").before('<div class="scene-let-delete scene scene-' + next + '" data-scene="scene-' + next + '"><span class="dashicons dashicons-trash scene-delete"></span><p><span class="scene-info">i</span>' + l18_scene + ' ' + next + '<span><input type="text" value="100" /></span></p></div>');

                    // select added scene.
                    $(".scenes .scene-" + next + "").trigger("click");

                    $(".scene-1 input").val("0");
                    $(".scene-2 input").val("100");

                    if (next == 3) {
                        $(".scene-1 input").val("0");
                        $(".scene-2 input").val("50");
                        $(".scene-3 input").val("100");
                    }

                    if (next == 4) {
                        $(".scene-1 input").val("0");
                        $(".scene-2 input").val("33.3");
                        $(".scene-3 input").val("66.6");
                        $(".scene-4 input").val("100");
                    }

                    if (next == 5) {
                        $(".scene-1 input").val("0");
                        $(".scene-2 input").val("25");
                        $(".scene-3 input").val("50");
                        $(".scene-4 input").val("75");
                        $(".scene-5 input").val("100");
                    }

                    if (next == 6) {
                        $(".scene-1 input").val("0");
                        $(".scene-2 input").val("20");
                        $(".scene-3 input").val("40");
                        $(".scene-4 input").val("60");
                        $(".scene-5 input").val("80");
                        $(".scene-6 input").val("100");
                    }

                    if (next == 6) {
                        $(".scene-add").hide();
                    }

                    // scan current scene styles
                    var rule, style, prevStyle;
                    iframe.find(".wyp-anim-scenes .scene-" + next + " style").each(function(){

                        style = $(this);
                        rule = style.attr("data-rule");

                        // check if same rule has in previous scene
                        prevStyle = iframe.find(".wyp-anim-scenes .scene-" + parseInt(next - 1) + " style[data-rule='"+rule+"']");

                        // skip if not has
                        if(prevStyle.length == 0){
                            return true;
                        }

                        // apply previous rule style to current scene rule style
                        style.html(prevStyle.html().replace(/body\.wyp-scene-(\d+)/g, 'body.wyp-scene-' + next));

                    });

                    // scan previous scene styles

                    update_animate_creator_view();

                    return false;

                }

                // Set active class
                $(".scene-active").removeClass("scene-active");
                $(this).addClass("scene-active");

                // Update current scene.
                body.attr("data-anim-scene", $(this).attr("data-scene"));

                // Delete ex scene classes.
                var newClassList = $.trim(mainBody.attr("class").replace(/wyp-scene-[0-9]/g, ''));
                mainBody.attr("class", newClassList);

                newClassList = $.trim(iframeBody.attr("class").replace(/wyp-scene-[0-9]/g, ''));
                iframeBody.attr("class", newClassList);

                // Add new scene class.
                body.addClass("wyp-" + $(this).attr("data-scene"));

                // loop
                for (var currentVal = parseInt($(this).attr("data-scene").replace("scene-", ""));currentVal > 1; currentVal--) {
                    if (currentVal !== 0) {
                        body.addClass("wyp-scene-" + currentVal);
                    }
                }

                insert_default_options();
                $(".wyp-disable-btn.active").trigger("click");

                draw();

            });


            /* ---------------------------------------------------- */
            /* Cancel Animation Generating                          */
            /* ---------------------------------------------------- */
            $(".wyp-anim-cancel").click(function() {

                swal({
                    title: l18_closeAnim,
                    showCancelButton: true,
                    animation: false,
                    customClass: "editor-style",
                    confirmButtonText: "Yes",
                },function(isConfirm){

                    if(isConfirm){

                        swal.close();
                        close_animation_generator(true);

                    }

                });

            });



            /* ---------------------------------------------------- */
            /* Open Animation Generator                             */
            /* ---------------------------------------------------- */
            $(".wyp-add-animation-link").click(function() {

                window.animGeneratorOldAnim = $("#wyp-animation-name").val();
                window.animGeneratorOldAnimDuration = $("#wyp-animation-duration").val();
                window.animGeneratorOldAnimDelay = $("#wyp-animation-delay").val();
                window.animGeneratorOldAnimDurationF = $("#animation-duration-after").val();
                window.animGeneratorOldAnimDelayF = $("#animation-delay-after").val();
                window.animGeneratorOldAnimFillMode = $("#wyp-animation-fill-mode").val();

                if(window.animGeneratorOldAnim == 'none'){
                    window.animGeneratorOldAnim = 'disable';
                }

                insert_rule(null, 'animation-name', 'disable', '');

                if ($("#animation-duration-value").val() == '0' || $("#animation-duration-value").val() == '0.00'){
                    $("#animation-duration-value").val("1");
                    $("#animation-duration-value").trigger("blur");
                }

                 // append anim data area.
                if (iframe.find(".wyp-anim-scenes").length === 0) {

                    // Append anim style area.
                    the_editor_data().after('<div class="wyp-anim-scenes"><div class="scene-1"></div><div class="scene-2"></div><div class="scene-3"></div><div class="scene-4"></div><div class="scene-5"></div><div class="scene-6"></div></div><div class="animate-test-drive"></div>');

                }

                // close css editor
                if (mainBody.hasClass("wyp-css-editor-active")) {
                    $(".wyp-css-close-btn").trigger("click");
                }

                // Start
                body.addClass("wyp-anim-creator");

                body.addClass("wyp-scene-1");
                body.attr("data-anim-scene", "scene-1");

                $(".scene-active").removeClass("scene-active");

                $(".scenes .scene:first-child").addClass("scene-active");

                // Resize scenes area.
                update_animate_creator_view();

                // Back to list.
                $(".animation-option.active > h3").trigger("click");

                gui_update();

                return false;

            });



            /* ---------------------------------------------------- */
            /* Inline Collapse, used for transform                  */
            /* ---------------------------------------------------- */
            $(".wyp-advanced-link").click(function() {

                if ($(this).hasClass("wyp-add-animation-link")) {
                    return false;
                }

                $(".wyp-on").not(this).removeClass("wyp-on");

                $(".wyp-advanced-option").not($(this).next(".wyp-advanced-option")).hide(0);

                $(this).next(".wyp-advanced-option").toggle(0);

                $(this).toggleClass("wyp-on");

                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Updating Responsive handles on CSS editor toggle     */
            /* ---------------------------------------------------- */
            $(".leftbar-button,.wyp-css-close-btn").click(function(){
                setTimeout(function(){
                    window.FrameleftOffset = undefined;
                    draw_responsive_handle();
                },50);
            });


            /* ---------------------------------------------------- */
            /* Active Classes Left Panel                            */
            /* ---------------------------------------------------- */
            $(".leftbar-button:not(.undo-btn):not(.redo-btn):not(.css-editor-btn)").click(function(){

                if(is_animate_creator() === false){

                    $(this).toggleClass("active");
                    $(this).tooltip("hide");

                }else if($(this).hasClass("wyp-selector-mode") === false && $(this).hasClass("wyp-button-target") === false){

                    $(this).toggleClass("active");
                    $(this).tooltip("hide");

                }

            });


            /* ---------------------------------------------------- */
            /* Undo                                                 */
            /* ---------------------------------------------------- */
            $(".undo-btn").click(function() {

                clearTimeout(window.historyDelay);

                if(mainBody.hasClass("wyp-history-delay")){

                    window.historyDelay = setTimeout(function(){
                        undo_changes();
                    },220);

                }else{
                    undo_changes();
                }

            });


            /* ---------------------------------------------------- */
            /* Redo                                                 */
            /* ---------------------------------------------------- */
            $(".redo-btn").click(function() {

                clearTimeout(window.historyDelay);

                if(mainBody.hasClass("wyp-history-delay")){

                    window.historyDelay = setTimeout(function(){
                        redo_changes();
                    },220);

                }else{
                    redo_changes();
                }

            });


            /* ---------------------------------------------------- */
            /* Open Pattern section and dynamic loads               */
            /* ---------------------------------------------------- */
            $(".wyp-bg-img-btn").click(function() {

                // Show pattern section
                $(this).toggleClass("active");
                $(".wyp_background_assets").toggle();

                // get CSS
                var val = $("#wyp-background-image").val();

                // Update active pattern
                if(val.indexOf("yellow-pencil") == -1){
                    $(".wyp_bg_assets").removeClass("active");
                }else{
                    $(".wyp_bg_assets[data-url='" + val.replace(/"/g, "").replace(/'/g, "").replace(/url\(/g, "").replace(/\)/g, "") + "']").addClass("active");
                }

                // Delay
                setTimeout(function(){

                    var activePattern = 0;

                    if($(".wyp_bg_assets.active").length > 0){
                        activePattern = ($(".wyp_bg_assets.active").index()-1) * 100;
                    }

                    $(".wyp_background_assets").scrollTop(activePattern);

                    load_near_patterns(activePattern);

                },10);

                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Adds focus class to mouseenter pattern               */
            /* ---------------------------------------------------- */
            $(".wyp_bg_assets").on("mouseenter mouseover",function(){
                $(".wyp_bg_assets").removeClass("focus");
                $(this).addClass("focus");
            });


            /* ---------------------------------------------------- */
            /* Loads background patterns on scrolling               */
            /* ---------------------------------------------------- */
            $(".wyp_background_assets").on("scroll",function(){
                load_near_patterns(null);
            });


            /* ---------------------------------------------------- */
            /* Loading near patterns                                */
            /* ---------------------------------------------------- */
            function load_near_patterns(scrollTop){

                if(scrollTop == null){
                    scrollTop = $(".wyp_background_assets").scrollTop();
                }

                var start = parseInt(scrollTop/100) - 2;

                var end = start + 13;

                var element;

                for(var i = start; i < end; i++){

                    element = $(".wyp_bg_assets:nth-child("+i+")");
                    element.css("backgroundImage", "url(" + element.data("url") + ")");

                }

            }

            /* ---------------------------------------------------- */
            /* Flat color toggle                                    */
            /* ---------------------------------------------------- */
            $(".wyp-flat-colors").click(function() {

                $(this).toggleClass("active");
                $(this).parent().find(".wyp_flat_colors_area").toggle();

                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Gradient Toggle                                    */
            /* ---------------------------------------------------- */
            $(".wyp-gradient-btn").on("click", function() {

                var el = $(this);

                // if not active
                if(!el.hasClass("active")){

                    // Background image data
                    var data = $("#wyp-background-image").val();

                    // if has gradient, read it
                    if(data.indexOf("linear-gradient(") != -1){

                        // CSS to gradient tool
                        read_gradient(data);

                    }else{

                        // Read default gradient
                        read_gradient('linear-gradient(141deg, #0fb8ad 0%, #2cb5e8 100%)');

                    }

                }

                // Add active class
                el.toggleClass("active");

                // show gradient tool
                $(".wyp-gradient-section").toggle();

                // update gui
                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Meterial Colors toggle                               */
            /* ---------------------------------------------------- */
            $(".wyp-meterial-colors").click(function() {

                $(this).toggleClass("active");
                $(this).parent().find(".wyp_meterial_colors_area").toggle();

                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Nice Colors toggle                                   */
            /* ---------------------------------------------------- */
            $(".wyp-nice-colors").click(function() {

                $(this).parent().find(".wyp_nice_colors_area").toggle();
                $(this).toggleClass("active");

                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Image uploader toggle                                */
            /* ---------------------------------------------------- */
            $(".wyp-upload-btn").click(function() {

                // Get iframe contents.
                $('#image_uploader iframe').attr('src',$('#image_uploader iframe').attr('data-url'));

                $('#image_uploader iframe').attr('src', function(i, val){
                    return val;
                });

                window.send_to_editor = function(output) {

                    var imgurl = output.match(/src="(.*?)"/g);
                    var imgNew = '';

                    imgurl = imgurl.toString().replace('src="', '').replace('"', '');

                    // Always get full size.
                    if (imgurl != '') {

                        var y = imgurl.split("-").length - 1;

                        if (imgurl.split("-")[y].match(/(.*?)x(.*?)\./g) !== null) {

                            imgNew = imgurl.replace("-" + imgurl.split("-")[y], '');

                            // format
                            if (imgurl.split("-")[y].indexOf(".") != -1) {
                                imgNew = imgNew + "." + imgurl.split("-")[y].split(".")[1];
                            }

                        } else {
                            imgNew = imgurl;
                        }

                    }

                    if($(".background-option.active").length > 0){
                        $("#wyp-background-image").val(imgNew).trigger("keyup");
                    }else{
                        $("#wyp-list-style-image").val(imgNew).trigger("keyup");
                    }

                    window.send_to_editor = window.restore_send_to_editor;

                    $("#image_uploader").toggle();
                    $("#image_uploader_background").toggle();

                };

                $("#image_uploader").toggle();
                $("#image_uploader_background").toggle();

            });


            /* ---------------------------------------------------- */
            /* Close Image uploader on background click             */
            /* ---------------------------------------------------- */
            $("#image_uploader_background").click(function() {
                $("#image_uploader").toggle();
                $("#image_uploader_background").toggle();
                $('#image_uploader iframe').attr('src', function(i, val) {
                    return val;
                });
            });


            // WP Window Upload
            window.restore_send_to_editor = window.send_to_editor;


            /* ---------------------------------------------------- */
            /* Image Uploader callback                              */
            /* ---------------------------------------------------- */
            window.send_to_editor = function(html) {

                var imgurl = $('img', html).attr('src');

                 if($(".background-option.active").length > 0){
                    $("#wyp-background-image").val(imgurl);
                }else{
                    $("#wyp-list-style-image").val(imgurl);
                }

                window.send_to_editor = window.restore_send_to_editor;

                $("#image_uploader").toggle();
                $("#image_uploader_background").toggle();
                $('#image_uploader iframe').attr('src', function(i, val) {
                    return val;
                });

            };


            // Default Option Change
            window.option_changeType = 'auto';
            option_change();
            window.option_changeType = 'default';


            // Setup the title
            $("title").html("Yellow Pencil: " + iframe.find("title").html());


            // Check before exit page.
            window.onbeforeunload = confirm_exit;


            /* ---------------------------------------------------- */
            /* Alert before exit                                    */
            /* ---------------------------------------------------- */
            function confirm_exit() {

                if ($(".wyp-save-btn").hasClass("waiting-for-save")) {
                    return confirm(l18_sure);
                }

            }

            /* ---------------------------------------------------- */
            /* Save button                                          */
            /* ---------------------------------------------------- */
            $(".wyp-save-btn").on("click", function() {

                // If all changes already saved, So Stop.
                if ($(this).hasClass("wyp-disabled")) {
                    return false;
                }

                // Getting Customized page id.
                var id = window.location.href.split("&wyp_page_id=");
                id = id[1].split("&");
                id = id[0];

                // Getting Customized Post Type
                var type = window.location.href.split("&wyp_page_type=");
                type = type[1].split("&");
                type = type[0];

                // Send Ajax If Not Demo Mode.
                if (!mainBody.hasClass("yp-yellow-pencil-demo-mode")) {

                    var data = get_clean_css(true);

                    // Lite Version Checking.
                    var status = true;

                    if (mainBody.hasClass("wtfv")) {

                        if (
                            data.indexOf("font-family:") != -1 ||
                            data.match(/\s\s+color\:/g) !== null ||
                            data.indexOf("background-image:") != -1 ||
                            data.indexOf("background-color:") != -1 ||
                            data.indexOf("opacity:") != -1 ||
                            data.indexOf("width:") != -1 ||
                            data.indexOf("height:") != -1 ||
                            data.indexOf("animation-name:") != -1){

                            status = false;

                            $(".wt-save-btn").html(l18_save).removeClass("waiting-for-save").removeClass("wt-disabled");

                            $(".wyp-info-modal,.wyp-popup-background").show();

                        } else {

                            // BeforeSend
                            $(".wyp-save-btn").html(l18_saving).addClass("wyp-disabled");

                        }

                    } else {

                        // BeforeSend
                        $(".wyp-save-btn").html(l18_saving).addClass("wyp-disabled");

                    }

                    // Convert CSS To Data and save.
                    if (mainBody.hasClass("wyp-need-to-process")) {

                        if (status) {

                            process(false);

                            // wait for process end
                            setTimeout(function(){
                                wyp_ajax_save(id,type);
                            }, 100);

                            return false;
                        }

                    } else {

                        if (status) {
                            wyp_ajax_save(id,type);
                        }

                    }

                } else {

                    swal({title: "Sorry.",text: l18_demo_alert,type: "info",animation: false,customClass: "editor-style-ok"});
                    $(".wyp-save-btn").html(l18_saved).addClass("wyp-disabled").removeClass("waiting-for-save");

                }

            });


            /* ---------------------------------------------------- */
            /* Saving Changes Ajax                                  */
            /* ---------------------------------------------------- */
            function wyp_ajax_save(id, page_type){

                var type, editorData, data, allcss = '', singleSave, templateSave, globalSave;
                var index = 0, completeIndex = 0;
                iframe.find(".wyp-data-updated").each(function(){

                    // +1
                    index++;

                    // Current source type: Global, template, single.
                    type = $(this).attr("data-source-mode");

                    // Get Editor Data
                    editorData = get_editor_data(type);

                    // Get clean CSS
                    data = get_clean_css(true, type);

                    // All data styles
                    allcss += data;

                    if(type == 'single'){

                        // Single Save
                        singleSave = $.post(ajaxurl, {
                            action: "wyp_ajax_save",
                            wyp_page_id: id,
                            wyp_stype: undefined,
                            wyp_data: data,
                            wyp_editor_data: editorData
                        }).complete(function(data){
                            completeIndex++;
                        });

                    }else if(type == 'template'){

                        // Template Save
                        templateSave = $.post(ajaxurl, {
                            action: "wyp_ajax_save",
                            wyp_page_id: undefined,
                            wyp_stype: page_type,
                            wyp_data: data,
                            wyp_editor_data: editorData
                        }).complete(function(data){
                            completeIndex++;
                        });

                    }else{

                        // Global Save
                        globalSave = $.post(ajaxurl, {
                            action: "wyp_ajax_save",
                            wyp_page_id: undefined,
                            wyp_stype: undefined,
                            wyp_data: data,
                            wyp_editor_data: editorData
                        }).complete(function(data){
                            completeIndex++;
                        });

                    }


                });

                // live preview
                $.post(ajaxurl, {
                    action: "wyp_preview_data_save",
                    wyp_data: allcss
                });

                // Done.
                window.savingChecker =setInterval(function(){

                    if(completeIndex == index){
                        $(".wyp-save-btn").html(l18_saved).addClass("wyp-disabled").removeClass("waiting-for-save");
                        clearInterval(window.savingChecker);
                        iframe.find(".wyp-data-updated").removeClass("wyp-data-updated");
                        $(".customizing-type-updated").removeClass("customizing-type-updated");

                    }

                }, 200);

            }


            /* ---------------------------------------------------- */
            /* Check the CSS value with parents                     */
            /* ---------------------------------------------------- */
            function check_with_parents(element, css, value, comparison){

                var checkElements = element.add(element.parents());
                var animation_fill_mode,el,returnValue = true;

                checkElements.each(function(){

                    el = $(this);

                    animation_fill_mode = null;

                    // Be sure there not have any element animating.
                    if(el.hasClass("wyp-animating") === false){

                        // nowdays a lot website using animation on page loads.
                        // the problem is a lot animations has transfrom, opacity etc.
                        // This break the system and can't get real value.
                        // So I will fix this issue : ).
                        animation_fill_mode = el.css("animationFillMode");

                        // Disable it until we get real value.
                        if(animation_fill_mode == 'forwards' || animation_fill_mode == 'both'){

                            // Set none for animation-fill-mode and be sure. using promise.
                            $.when(el.css("animationFillMode","none")).promise().always(function() {

                                // Continue after done.
                                returnValue = check_with_parents_last(el, css, value, comparison, animation_fill_mode);

                                if(returnValue === true){
                                    return false;
                                }

                            });

                        }else{

                            // Continue to last part.
                            returnValue = check_with_parents_last(el, css, value, comparison);

                            if(returnValue === true){
                                return false;
                            }

                        }

                    }else{

                        // Continue to last part.
                        returnValue = check_with_parents_last(el, css, value, comparison);

                        if(returnValue === true){
                            return false;
                        }

                    }

                });

                return returnValue;

            }


            /* ---------------------------------------------------- */
            /* A part of check_with_parents                         */
            /* ---------------------------------------------------- */
            function check_with_parents_last(el, css, value, comparison, animation_fill_mode){

                var isVal = false;

                // Get CSS
                var cssValue = el.css(css);

                // If not none but and not have any transform.
                if(css == 'transform' && cssValue == 'matrix(1, 0, 0, 1, 0, 0)'){
                    cssValue = 'none';
                }

                if (comparison == '==') {

                    if (cssValue === value) {
                        if(animation_fill_mode !== undefined){el.css("animationFillMode",animation_fill_mode);}
                        return true;

                    }

                } else {

                    if (cssValue !== value) {
                        if(animation_fill_mode !== undefined){el.css("animationFillMode",animation_fill_mode);}
                        return true;

                    }

                }

                if(animation_fill_mode !== undefined){el.css("animationFillMode",animation_fill_mode);}
                return isVal;

            }


            /* ---------------------------------------------------- */
            /* Close contextMenu on scroll                          */
            /* ---------------------------------------------------- */
            var timerx = null;
            iframe.scroll(function() {

                if (iframe.find(".context-menu-active").length > 0) {
                    get_selected_element().contextMenu("hide");
                }

                if(timerx !== null) {
                    clearTimeout(timerx);
                }

                if(is_content_selected()){

                    // Set outline border while scrolling if its is fixed.
                    // Need to reflesh the position on scrolling and this will make feel slow the editor.
                    if (check_with_parents(get_selected_element(), "position", "fixed", "==") === true) {

                        if (!mainBody.hasClass("wyp-has-transform")){ // if not have.

                            body.addClass("wyp-has-transform"); // add

                        }else{

                            // back to normal borders and update position.
                            timerx = setTimeout(function(){

                                body.removeClass("wyp-has-transform");

                                draw();

                            },250);

                        }

                    }

                }

            });


            /* ---------------------------------------------------- */
            /* Updating tooltips on scrolling                       */
            /* ---------------------------------------------------- */
            var timer = null;
            iframe.on("scroll", iframe, function(evt){

                if(timer !== null) {
                    clearTimeout(timer);
                }

                timer = setTimeout(function(){
                    if(is_content_selected()){
                        draw_tooltip();
                    }
                }, 120);

            });


            /* ---------------------------------------------------- */
            /* Set as Background Image                              */
            /* ---------------------------------------------------- */
            $(".wyp_background_assets div").click(function() {
                $(".wyp_background_assets div.active").removeClass("active");
                $(this).parent().parent().find(".wyp-input").val($(this).data("url")).trigger("keyup");
                $(this).addClass("active");
                $("#background-repeat-group .wyp-none-btn:not(.active),#background-size-group .wyp-none-btn:not(.active)").trigger("click");
            });


            /* ---------------------------------------------------- */
            /* Set Color                                            */
            /* ---------------------------------------------------- */
            $(".wyp_flat_colors_area div,.wyp_meterial_colors_area div,.wyp_nice_colors_area div").click(function() {

                var element = $(this);
                var elementParent = element.parent();

                $(".wyp_flat_colors_area,.wyp_meterial_colors_area,.wyp_nice_colors_area").find(".active").removeClass("active");
                elementParent.parent().parent().parent().find(".wqcolorpicker").val($(this).data("color")).trigger("change");
                $(this).addClass("active");

            });


            /* ---------------------------------------------------- */
            /* Blur the editor                                      */
            /* ---------------------------------------------------- */
            function blur_editor(event){

                if(!event.originalEvent){
                    return false;
                }

                if(window.documentClick == false){
                    return false;
                }

                var el = $(event.target);

                if($("#customizing-type-list").css("display") == 'block' && el.hasClass("wyp-type-menu-link") == false){
                    if(el.parents("#customizing-type-list").length == 0){
                        $("#customizing-type-list").toggle();
                        return false;
                    }
                }

                var irisWasOpen = false;

                if (el.is(".iris-picker") === false && el.is(".iris-square-inner") === false && el.is(".iris-square-handle") === false && el.is(".iris-slider-offset") === false && el.is(".iris-slider-offset .ui-slider-handle") === false && el.is(".iris-picker-inner") === false && el.is(".wqcolorpicker") === false) {

                    // first hide iris, later gradient. not all in one click.
                    if($(".wyp-gradient-section .iris-picker:visible").length > 0){
                        irisWasOpen = true;
                    }

                    if($(".iris-picker .ui-state-active").length == 0 && $(".iris-picker .iris-dragging").length == 0){
                        $(".iris-picker").hide();
                        gui_update();
                    }

                    $(".wyp-gradient-pointer-area").removeClass("gradient-pointer-no-cursor");

                }

                if (el.is('.wyp_bg_assets') === false && el.is('.wyp-none-btn') === false && el.is('.wyp-bg-img-btn') === false && $(".wyp_background_assets:visible").length > 0) {
                    $(".wyp_background_assets").hide();
                    $(".wyp-bg-img-btn").removeClass("active");
                    gui_update();
                }

                if (el.is('.wyp-flat-c') === false && el.is('.wyp-flat-colors') === false && $(".wyp_flat_colors_area:visible").length > 0) {
                    $(".wyp_flat_colors_area").hide();
                    $(".wyp-flat-colors").removeClass("active");
                    gui_update();
                }

                if (el.is('.wyp-meterial-c') === false && el.is('.wyp-meterial-colors') === false && $(".wyp_meterial_colors_area:visible").length > 0) {
                    $(".wyp_meterial_colors_area").hide();
                    $(".wyp-meterial-colors").removeClass("active");
                    gui_update();
                }

                if (el.is('.wyp-nice-c') === false && el.is('.wyp-nice-colors') === false && $(".wyp_nice_colors_area:visible").length > 0) {
                    $(".wyp_nice_colors_area").hide();
                    $(".wyp-nice-colors").removeClass("active");
                    gui_update();
                }


                if (irisWasOpen == false && $(".iris-picker:visible").length == 0 && el.parents('.context-menu-list').length === 0 && el.is('.context-menu-layer') === false && el.is('.wyp-gradient-btn') === false && el.parents('.wyp-gradient-section').length === 0 && $(".wyp-gradient-section:visible").length > 0 && $(".wyp-gradient-pointer.ui-draggable-dragging").length == 0 && $(".wyp-gradient-orientation .ui-draggable-dragging").length == 0 && el.is("#context-menu-layer") == false && el.is(".context-menu-list") == false) {
                    $(".wyp-gradient-section").hide();
                    $(".wyp-gradient-pointer,.wyp-gradient-btn").removeClass("active");
                    gui_update();
                }

            }

            /* ---------------------------------------------------- */
            /* blur_editor on click                                 */
            /* ---------------------------------------------------- */
            $(document).on("click", blur_editor);


            /* ---------------------------------------------------- */
            /* Search Selector Tool Close                           */
            /* ---------------------------------------------------- */
            $("#wyp-target-dropdown").on("click", function(e) {
                if (e.target !== this) {
                    return;
                }

                $("#target_background").trigger("click");
            });


            /* ---------------------------------------------------- */
            /* Adds similar selectors to Search selector Tool       */
            /* ---------------------------------------------------- */
            function add_similar_selectors(selector) {

                if (selector == '' || selector == '.' || selector == '#' || selector == ' ' || selector == '  ' || selector == get_current_selector() || selector == $("#wyp-button-target-input").val()) {
                    return false;
                }

                if ($("#wyp-target-dropdown li").length < 10) {

                    if (iframe.find(selector).length === 0) {
                        return false;
                    }

                    if ($("#" + get_id(selector)).length > 0) {
                        return false;
                    }

                    var selectorOrginal = selector;

                    // Adding b tag for hover, focus etc.
                    var selectorParts;
                    if (selector.indexOf("::") != -1) {
                        selectorParts = selector.split("::");
                        selector = selectorParts[0] + "<b>::" + selectorParts[1] + "</b>";
                    } else if (selector.indexOf(":") != -1) {
                        selectorParts = selector.split(":");
                        selector = selectorParts[0] + "<b>:" + selectorParts[1] + "</b>";
                    }

                    var role = ' ';
                    if (selector.indexOf(" > ") != -1) {
                        role = ' > ';
                    }

                    selector = "<span style=\"color:#D70669\">" + selector.replace(new RegExp(role, "g"), '</span>' + role + '<span style="color:#D70669">') + "</span>";
                    selector = selector.replace(/<span style=\"(.*?)\">\#(.*?)<\/span>/g, '<span style="color:#6300FF">\#$2<\/span>');

                    $("#wyp-target-dropdown").append("<li id='" + get_id(selectorOrginal) + "'>" + selector + "</li>");

                }

            }


            /* ---------------------------------------------------- */
            /* Toggle CSS Editor                                    */
            /* ---------------------------------------------------- */
            function css_editor_toggle(status) {

                if (status === true) {

                    if (mainBody.hasClass("wyp-css-editor-active")) {
                        $(".wyp-css-close-btn").trigger("click");
                    }
                    mainBody.removeClass("wyp-clean-look");

                } else {
                    mainBody.toggleClass("wyp-clean-look");
                    if (mainBody.hasClass("wyp-css-editor-active")) {
                        mainBody.removeClass("wyp-css-editor-active");

                        var ebtn = $(".css-editor-btn");
                        ebtn.attr("data-original-title",ebtn.attr("data-title"));

                        $("#leftAreaEditor").hide();
                    }
                    gui_update();
                }

            }


            /* ---------------------------------------------------- */
            /* Creating Similar Selectors for Search Tool           */
            /* ---------------------------------------------------- */
            function create_similar_selectors() {

                var selector;

                $("#wyp-target-dropdown li").remove();

                if ($("#wyp-button-target-input").val() == '') {

                    selector = get_current_selector();

                } else {

                    selector = $("#wyp-button-target-input").val();

                }

                if (isUndefined(selector)) {
                    return false;
                }

                var max = 10;

                // adding all ids
                if (selector == '#') {
                    iframe.find("[id]").not(window.simple_not_selector).each(function(i, v){
                        if (i < max) {
                            add_similar_selectors("#" + $(this).attr('id'));
                        }else{
                            return false;
                        }
                    });
                    return false;
                }

                // adding all classes
                if (selector == '.') {
                    iframe.find("[class]").not(window.simple_not_selector).each(function(i, v) {
                        if (i < max) {
                            add_similar_selectors("." + $(this).attr('class'));
                        }else{
                            return false;
                        }
                    });
                    return false;
                }

                if (selector.indexOf("::") > -1) {
                    selector = selector.split("::")[0];
                } else if (selector.indexOf(":") > -1) {
                    selector = selector.split(":")[0];
                }

                if (selector == '  ' || selector == ' ' || selector == '') {
                    return false;
                }

                // Using prefix
                if (get_selector_array(selector).length > 0) {

                    var last = null;
                    var elsAll,rex;
                    var lastPart = get_selector_array(selector)[(get_selector_array(selector).length - 1)];
                    if (lastPart.indexOf(" ") == -1) {
                        last = lastPart;
                    }

                    if (last !== null){

                        var selectorY = $.trim(selector.replace(/\#$/g,"").replace(/\.$/g,""));

                        // adding all ids
                        if (last == '#') {
                            iframe.find(selectorY).find("[id]").not(window.simple_not_selector).each(function(i, v) {
                                if (i < max) {
                                    add_similar_selectors(selector + $(this).attr('id'));
                                }else{
                                    return false;
                                }
                            });
                            return false;
                        }

                        // adding all classes
                        if (last == '.') {
                            iframe.find(selectorY).find("[class]").not(window.simple_not_selector).each(function(i, v) {
                                if (i < max) {
                                    add_similar_selectors(selector + $(this).attr('class'));
                                }else{
                                    return false;
                                }
                            });
                            return false;
                        }

                        // For Classes
                        if (last.indexOf(".") != -1){

                            elsAll = iframe.find("[class^='" + last.replace(/\./g, '') + "']").not(window.simple_not_selector);
                            rex = new RegExp("^" + last.replace(/\./g, '') + "(.+)");

                            elsAll.each(function(){

                                var classes = $(this).attr('class').split(' ');

                                for (var i = 0; i < classes.length; i++) {

                                    var matches = rex.exec(classes[i]);

                                    if (matches !== null) {
                                        var Foundclass = matches[1];
                                        add_similar_selectors(selector + Foundclass);
                                    }

                                }

                            });

                        }

                        // For ID
                        if (last.indexOf("#") != -1){

                            elsAll = iframe.find("[id^='" + last.replace(/\#/g, '') + "']").not(window.simple_not_selector);
                            rex = new RegExp("^" + last.replace(/\#/g, '') + "(.+)");

                            elsAll.each(function(){

                                var ids = $(this).attr('id').split(' ');

                                for (var i = 0; i < ids.length; i++) {

                                    var matches = rex.exec(ids[i]);

                                    if (matches !== null) {
                                        var Foundclass = matches[1];
                                        add_similar_selectors(selector + Foundclass);
                                    }

                                }

                            });

                        }

                    }else{
                        add_similar_selectors(selector);
                    }

                }

                // Adding childrens.
                var childrens = iframe.find(selector).find("*").not(window.simple_not_selector);

                if (childrens.length === 0) {
                    return false;
                }

                childrens.each(function(i) {
                    if(i < max){
                        add_similar_selectors(selector + " " + get_best_class($(this)));
                    }else{
                        return false;
                    }
                });

            }


            /* ---------------------------------------------------- */
            /* Scrolling the page to the target element             */
            /* ---------------------------------------------------- */
            function scroll_to_element(element){

                if(element.length != 1){
                    return true;
                }

                var height = parseInt($(window).height() / 2);
                var elementHeight = parseInt(element.height() / 2);

                if (elementHeight < height) {
                var scrollPosition = elementHeight + element.offset().top - height;
                    iframe.scrollTop(scrollPosition);
                }

            }


            /* ---------------------------------------------------- */
            /* Click to similar selectors                           */
            /* ---------------------------------------------------- */
            $(document).on("click", "#wyp-target-dropdown li", function() {

                $("#wyp-button-target-input").val($(this).text().split(" |")[0]).trigger("keyup").trigger("focus");

                $(".wyp-button-target").trigger("click");

            });


            /* ---------------------------------------------------- */
            /* Open Search Selector Tool                            */
            /* ---------------------------------------------------- */
            $(".wyp-button-target").click(function(e) {

                if ($(e.target).hasClass("wyp-button-target-input")) {
                    return false;
                }

                if (iframe.find(".context-menu-active").length > 0) {
                    get_selected_element().contextMenu("hide");
                }

                var element = $(this);
                var selector;

                // if Search tool is closed
                if (!element.hasClass("active") && body.hasClass("wyp-pressed-enter-key") === false) {

                    body.addClass("wyp-target-active");
                    element.removeClass("active");

                    selector = get_current_selector();

                    if (body.attr("data-wyp-selector") == ':hover') {
                        selector = selector + ":hover";
                        add_class_to_parents();
                    }

                    if (body.attr("data-wyp-selector") == ':focus') {
                        selector = selector + ":focus";
                        add_class_to_parents();
                    }

                    if (body.attr("data-wyp-selector") == ':link') {
                        selector = selector + ":link";
                        add_class_to_parents();
                    }

                    if (body.attr("data-wyp-selector") == ':active') {
                        selector = selector + ":active";
                        add_class_to_parents();
                    }

                    if (body.attr("data-wyp-selector") == ':visited') {
                        selector = selector + ":visited";
                        add_class_to_parents();
                    }

                    if (isUndefined(selector)) {
                        selector = '.';
                    }

                    $("#wyp-button-target-input").trigger("focus").val(selector).trigger("keyup");

                    create_similar_selectors();

                } else {

                    selector = $("#wyp-button-target-input").val();

                    if (selector == '' || selector == ' ') {
                        element.addClass("active");
                        body.removeClass("wyp-target-active");
                    }

                    // Be sure hover and focus to last because just support hover&focus in last.
                    var hoverPosition = selector.match(/:hover(.*?)$/g);
                    var focusPosition = selector.match(/:focus(.*?)$/g);
                    var visitedPosition = selector.match(/:visited(.*?)$/g);
                    var activePosition = selector.match(/:active(.*?)$/g);
                    var linkPosition = selector.match(/:link(.*?)$/g);

                    if(hoverPosition !== null){
                        hoverPosition = hoverPosition.toString().trim().replace(/:hover/g,'').trim().length;
                    }else{
                        hoverPosition = 0;
                    }

                    if(focusPosition !== null){
                        focusPosition = focusPosition.toString().trim().replace(/:focus/g,'').trim().length;
                    }else{
                        focusPosition = 0;
                    }

                    if(visitedPosition !== null){
                        visitedPosition = visitedPosition.toString().trim().replace(/:visited/g,'').trim().length;
                    }else{
                        visitedPosition = 0;
                    }

                    if(activePosition !== null){
                        activePosition = activePosition.toString().trim().replace(/:active/g,'').trim().length;
                    }else{
                        activePosition = 0;
                    }

                    if(linkPosition !== null){
                        linkPosition = linkPosition.toString().trim().replace(/:link/g,'').trim().length;
                    }else{
                        linkPosition = 0;
                    }

                    var selectorNew = selector.replace(/:hover/g, '').replace(/:focus/g, '').replace(/:link/g, '').replace(/:visited/g, '').replace(/:active/g, '');

                    // Check selectors
                    var checkResult = check_selector(selectorNew, true, false, true)
                    if(checkResult != true){
                        //checkResult is a error message.
                        return false;
                    }

                    if (iframe.find(selectorNew).length > 0 && selectorNew != '*' && hoverPosition === 0 && focusPosition === 0) {

                        if (iframe.find(selector).hasClass("wyp-selected")) {
                            get_selected_element().addClass("wyp-will-selected");
                        }

                        set_selector(space_cleaner(selector), null, true);

                        // selected element
                        var selectedElement = iframe.find(selectorNew);

                        // scroll to element if not visible on screen.
                        scroll_to_element(selectedElement);

                        element.addClass("active");
                        body.removeClass("wyp-target-active");

                    } else if (selectorNew != '' && selectorNew != ' '){

                        $("#wyp-button-target-input").css("color", "red");

                        element.removeClass("active");
                        body.addClass("wyp-target-active");

                    }

                }

            });


            /* ---------------------------------------------------- */
            /* Close Search Selector Tool                           */
            /* ---------------------------------------------------- */
            $("#target_background").click(function() {

                body.removeClass("wyp-target-active");
                $("#wyp-button-target-input").val("");
                $(".wyp-button-target").trigger("click");

            });


            /* ---------------------------------------------------- */
            /* Writing to Search Selector Tool                      */
            /* ---------------------------------------------------- */
            $("#wyp-button-target-input").keyup(function(e) {

                if($(this).val().length > 1 || $(this).val() == '#' || $(this).val() == "."){
                    create_similar_selectors();
                }

                if (e.keyCode != 13){
                    $(this).attr("style", "");
                }

                // Enter
                if (e.keyCode == 13) {
                    body.addClass("wyp-pressed-enter-key");
                    $(".wyp-button-target").trigger("click");
                    body.removeClass("wyp-pressed-enter-key");
                    return false;
                }

            });


            /* ---------------------------------------------------- */
            /* If Selector is not available                         */
            /* ---------------------------------------------------- */
            $("#wyp-button-target-input").keydown(function(e) {

                if (e.keyCode != 13){
                    $(this).attr("style", "");
                }

            });


            /* ---------------------------------------------------- */
            /* Setup iris picker                                    */
            /* ---------------------------------------------------- */
            var wIris = 223;

            /* ---------------------------------------------------- */
            /* Setup inside collapse iris picker                    */
            /* ---------------------------------------------------- */
            $('.editor-panel .wqcolorpicker').cs_iris({
                hide: true,
                width: wIris
            });


            /* ---------------------------------------------------- */
            /* Breakpoint bar add active class                      */
            /* ---------------------------------------------------- */
            $(document).on("click",".breakpoint-bar div",function(e){
                $(".breakpoint-bar div").removeClass("active");
                $(this).addClass("active");
            });



            /* ---------------------------------------------------- */
            /* Class, ID hover show the element: LEAVE              */
            /* ---------------------------------------------------- */
            $(document).on("mouseout", ".info-global-id-list li,.info-global-class-list li,.info-element-class-list li", function(){
                clearTimeout(window.focusElementTimer);
                iframe.find(".wyp-element-viewer").remove();
            });



            /* ---------------------------------------------------- */
            /* Class, ID hover show the element                     */
            /* ---------------------------------------------------- */
            $(document).on("click", ".info-global-id-list li,.info-global-class-list li,.info-element-class-list li", function(){

                var selector = $(this).text();

                // 40ms delay
                window.focusElementTimer = setTimeout(function(){

                    scroll_to_element(iframe.find(selector));

                    element_focus(iframe.find(selector));

                }, 80); // delay.

            });



            /* ---------------------------------------------------- */
            /* Breakpoint bar click                                 */
            /* ---------------------------------------------------- */
            $(document).on("mouseover click",".breakpoint-bar div",function(e){

                // Breakpoint element
                var el = $(this);

                var delay = 40;

                if (mainBody.hasClass("wyp-need-to-process")) {
                    process(false);
                    delay = 70;
                }

                // 40ms delay
                window.breakpointHoverTimer = setTimeout(function(){

                    // Delete all old viewers
                    iframe.find(".wyp-element-viewer").remove();

                    // variables
                    var element_offset,element,topBoxesI,leftBoxesI,widthBoxesI,heightBoxesI,selector,elements;

                    // each all founded data
                    the_editor_data().find('[data-size-mode="'+el.attr('data-breakpoint-data')+'"]').each(function(i,v){

                        // find selectors
                        selector = get_foundable_query($(this).html().match(/\{(.*?)\{/g).toString().replace(/\{/g,""),true,true,true);

                        // element object
                        elements = iframe.find(selector);

                        // Each all founded elements
                        elements.each(function(){

                            // element object
                            element = $(this);

                            // offset
                            element_offset = element.offset();

                            // check if valid
                            if (isDefined(element_offset)) {

                                // getting element positions
                                topBoxesI = element_offset.top;
                                leftBoxesI = element_offset.left;

                                if (leftBoxesI < 0) {
                                    leftBoxesI = 0;
                                }

                                // Getting element sizes
                                widthBoxesI = element.outerWidth();
                                heightBoxesI = element.outerHeight();

                                var id = "wyp-element-viewer-"+parseInt(widthBoxesI)+"-"+parseInt(heightBoxesI)+"-"+parseInt(topBoxesI)+"-"+parseInt(leftBoxesI)+"";

                                // add the viewer
                                if(iframe.find("#"+id).length == 0){
                                    iframeBody.append("<div class='wyp-element-viewer' id='"+id+"' style='width:"+widthBoxesI+"px;height:"+heightBoxesI+"px;top:"+topBoxesI+"px;left:"+leftBoxesI+"px;'></div>");
                                }

                            } // element offset if


                        }); // elements each

                    }); // each founded breakpoint datas

                },delay); // delay.

            });


            /* ---------------------------------------------------- */
            /* Hide breakpoint on click                             */
            /* ---------------------------------------------------- */
            $(document).on("mousedown",".breakpoint-bar div",function(e){

                $(this).tooltip("hide");

            });


            /* ---------------------------------------------------- */
            /* Shows affected elements when mouseover the breakpoint*/
            /* ---------------------------------------------------- */
            $(document).on("mouseout",".breakpoint-bar div",function(e){

                clearTimeout(window.breakpointHoverTimer);
                iframe.find(".wyp-element-viewer").remove();

            });


            /* ---------------------------------------------------- */
            /* Shows the current media queries                      */
            /* ---------------------------------------------------- */
            function update_responsive_breakpoints(){

                // Bar Element
                var bar = $(".breakpoint-bar");

                bar.find("div").tooltip('destroy');

                // Empty
                bar.empty();

                // Show breakpoint information.
                if($(".media-control").attr("data-code") == 'max-width'){
                    bar.append("<span class='breakpoint-right-notice'>defined breakpoints</span>");
                }else{
                    bar.append("<span class='breakpoint-left-notice'>defined breakpoints</span>");
                }

                // Getting all media queries as array
                var queries = get_media_queries(null,true);

                // number Value
                var val = 0;

                var num = 0;

                var query;

                // Window Width
                var winWidth = $(window).width();

                // Has queries?
                if(queries.length > 0){

                    // Each all Queries
                    $.each(queries, function(index, value) {

                        // Simple query.
                        query = process_media_query(value);

                        // isset?
                        if(isDefined(query)){

                            // String
                            query = query.toString();

                            // clean < and > symbols.
                            val = parseInt(number_filter(query.replace(/\</g,"").replace(/\>/g,"")));

                            // Real number value. non proccesed.
                            // proccessed convert rem, em to PX
                            // we using NUM for find the media in data
                            num = space_cleaner(value.match(/\:(.*?)\)/g).toString().replace(/\:/g,"").replace(/\)/g,""));

                            // be sure it a simple media query.
                            if(query.indexOf(",") == -1 && query.indexOf("and") == -1){

                                // Just Min Width
                                if(query.indexOf(">") != -1 && $(".media-control").attr("data-code") == 'min-width'){

                                    // If not has
                                    if($(document).find("#min-breakpoint-"+val+"").length == 0){

                                        // Append
                                        bar.append("<div data-breakpoint='"+val+"' data-media-content='"+value+"' data-breakpoint-data='(min-width:"+num+")' class='min-width' id='min-breakpoint-"+val+"' style='left:"+(46+parseInt(val))+"px;width:"+(winWidth-(46+parseInt(val)))+"px;'>"+val+"</div>");

                                    }


                                }

                                // Just Max Width
                                if(query.indexOf("<") != -1 && $(".media-control").attr("data-code") == 'max-width'){

                                    // If not has
                                    if($(document).find("#max-breakpoint-"+val+"").length == 0){

                                        // Append
                                        bar.append("<div data-breakpoint='"+val+"' data-media-content='"+value+"' data-breakpoint-data='(max-width:"+num+")' class='max-width' id='max-breakpoint-"+val+"' style='width:"+val+"px;'>"+val+"</div>");

                                    }

                                }

                            }

                        }

                    });


                    // Sorting breakpoints by value
                    bar.find('div').sort(function(a, b) {
                        return +a.dataset.breakpoint - +b.dataset.breakpoint;
                    }).appendTo(bar);


                    // Updating Max Width Bar
                    var prevWidth = 0;
                    bar.find("div").each(function(i,v){

                        // Object
                        var element = $(this);
                        var nextElement = element.next(".max-width");

                        // Next Width
                        var nextWidth = parseFloat(nextElement.css("width"));

                        // Fix Max width format
                        if(element.hasClass("max-width")){
                            prevWidth = parseFloat(element.css("width")) + prevWidth;
                        }

                        // Updating Positions
                        nextElement.css("width",nextWidth-prevWidth);
                        nextElement.css("left",46+prevWidth);
                        element.css("z-index",i);

                    });


                    // Updating Min Width Bar
                    bar.find(".min-width").each(function(i,v){

                        // Object
                        var element = $(this);
                        var nextElement = element.nextAll(".min-width");

                        // If has next
                        if(nextElement.length > 0){

                            // Getting Breakpoint Values
                            var elementPoint = parseInt(element.attr("data-breakpoint"));
                            var nextPoint = parseInt(nextElement.attr("data-breakpoint"));

                            // Find Dif
                            var maxUntil = nextPoint-elementPoint;

                            // Updating width
                            element.css("width",maxUntil+"px");

                        }

                    });


                    // Fix small media queries
                    bar.find("div").each(function(){

                        var element = $(this);

                        if(parseInt(element.css("width")) <= 100){
                            element.css("background-image","none").css("text-align","center").css("padding","0px").css("min-width","13px");
                        }

                        if(parseInt(element.css("width")) <= 40){
                            element.css("font-size","10px");
                        }

                        // add active class to current
                        if(element.attr("data-breakpoint") == $("#iframe").width()){
                            bar.find("div").removeClass("active");
                            element.addClass("active");
                        }

                    });


                    // Adding Toolip to breakponts
                    $(".breakpoint-bar div").tooltip({

                        // Set dynamic title
                        title: function(){
                            var text = $(".media-control").text();
                            var breakpoint = $(this).attr("data-breakpoint");
                            return "<span class='smaller-text-tooltip'>" + breakpoint + "px and "+text+" screens</span>";
                        },

                        // Tooltip settings
                        animation: false,
                        delay: { show: 10, hide: 0 },
                        placement: 'bottom',
                        trigger: 'hover',
                        container: "body",
                        html: true

                    });


                }

            }


            /* ---------------------------------------------------- */
            /* Breakpoint bar click                                 */
            /* ---------------------------------------------------- */
            $(document).on("click",".breakpoint-bar div",function(e){

                $('.responsive-right-handle').tooltip("hide");

                // Getting breakpoint value
                var n = $(this).attr("data-breakpoint");

                // Go
                $("#iframe").width(n);

                // Update
                draw_responsive_handle();
                update_responsive_size_notice();
                draw();

            });


            /* ---------------------------------------------------- */
            /* Updating Responsive Size Notice                      */
            /* ---------------------------------------------------- */
            function update_responsive_size_notice() {

                if (is_responsive_mod() === false) {
                    return false;
                }

                var s = $("#iframe").width();
                var device = '';

                // Set device size.
                $(".device-size").text(s);

                if ($(".media-control").attr("data-code") == 'max-width') {

                    device = '(phones)';

                    if (s >= 375) {
                        device = '(Large phones)';
                    }

                    if (s >= 414) {
                        device = '(tablets & landscape phones)';
                    }

                    if (s >= 736) {
                        device = '(tablets)';
                    }

                    if (s >= 768) {
                        device = '(small desktops & tablets and phones)';
                    }

                    if (s >= 992) {
                        device = '(medium desktops & tablets and phones)';
                    }

                    if (s >= 1200) {
                        device = '(large desktops & tablets and phones)';
                    }

                } else {

                    device = '(phones & tablets and desktops)';

                    if (s >= 375) {
                        device = '(phones & tablets and desktops)';
                    }

                    if (s >= 414) {
                        device = '(large phones & tablets and desktops)';
                    }

                    // Not mobile.
                    if (s >= 736) {
                        device = '(landscape phones & tablets and desktops)';
                    }

                    // Not tablet
                    if (s >= 768) {
                        device = '(desktops)';
                    }

                    // Not small desktop
                    if (s >= 992) {
                        device = '(medium & large desktops)';
                    }

                    // Not medium desktop
                    if (s >= 1200) {
                        device = '(large desktops)';
                    }

                }

                // Set device name
                $(".device-name").text(device);

            }

            /* ---------------------------------------------------- */
            /* Smarrt Insert Default Values                         */
            /* ---------------------------------------------------- */
            function insert_default_options() {

                if (is_content_selected() === false) {
                    return false;
                }

                // current options
                var options = $(".editor-panel-list > li.active .wyp-option-group");

                // delete all cached data.
                $("li[data-loaded]").removeAttr("data-loaded");

                // UpData current active values.
                if (options.length > 0) {
                    options.each(function() {

                        var check = 1;
                        if ($(this).attr("id") == 'animation-duration-group' && is_animate_creator() === true) {
                            check = 0;
                        }

                        if (check == 1) {
                            set_default_value(get_option_id(this));
                        }

                    });
                }

                // cache to loaded data.
                options.parent().attr("data-loaded", "true");

            }

            /* ---------------------------------------------------- */
            /* Setup AutoComplete                                   */
            /* ---------------------------------------------------- */
            $(".input-autocomplete").each(function() {

                // Get data by select
                var data = [];
                $(this).parent().find("select option").each(function() {
                    data.push($(this).text());
                });

                var id = $(this).parent().parent().attr("data-css");

                // Autocomplete script
                $(this).autocomplete({
                    source: data,
                    delay: 0,
                    minLength: 0,
                    autoFocus: true,
                    close: function(event, ui) {

                        $(".active-autocomplete-item").removeClass("active-autocomplete-item");
                        $(this).removeClass("active");

                        setTimeout(function(){
                            mainBody.removeClass("autocomplete-active");
                        },300);

                        if ($(this).parent().find('select option:contains(' + $(this).val() + ')').length) {
                            $(this).val($(this).parent().find('select option:contains(' + $(this).val() + ')').val());
                        }

                    },
                    open: function(event, ui) {

                        window.openVal = $(this).val();

                        $(this).addClass("active");
                        mainBody.addClass("autocomplete-active");

                        var current = $(this).val();

                        var fontGoogle = null;

                        // Show more fonts as well.
                        if(id == 'font-family' || id == 'animation-name'){

                            // Find free space on screen
                            var freeSpace = ($(window).height() - $("#wyp-"+id).offset().top) - 200;

                            // already min 300
                            if(freeSpace > 300){

                                // Getting each other li
                                var li = $("#"+id+"-group .ui-autocomplete.ui-menu li").outerHeight();

                                // update free space
                                freeSpace = parseInt(freeSpace/li) * li;

                                // Remove old
                                $("#autocomplete-font-style-"+id).remove();

                                // Add new
                                mainBody.append('<style id="autocomplete-font-style-'+id+'">#'+id+'-group .ui-autocomplete.ui-menu{max-height:'+freeSpace+'px;}</style>');

                            }

                        }

                        // Getting first font family and set active if yp has this font family.
                        if (id == 'font-family') {
                            if (current.indexOf(",") != -1) {
                                var currentFont = $.trim(current.split(",")[0]);
                                currentFont = currentFont.replace(/'/g, "").replace(/"/g, "").replace(/ /g, "").toLowerCase();

                                if ($('#wyp-' + id + '-data option[data-text="' + currentFont + '"]').length > 0) {
                                    fontGoogle = $('#wyp-' + id + '-data option[data-text="' + currentFont + '"]').text();
                                }

                            }
                        }

                        if (fontGoogle === null){
                            if ($('#wyp-' + id + '-data option[value="' + current + '"]').length > 0) {
                                current = $('#wyp-' + id + '-data option[value="' + current + '"]').text();
                            }
                        } else {
                            current = fontGoogle;
                        }

                        if ($(this).parent().find(".autocomplete-div").find('li').filter(function() {
                                return $.text([this]) === current;
                            }).length == 1) {

                            $(".active-autocomplete-item").removeClass("active-autocomplete-item");
                            if ($(".active-autocomplete-item").length === 0) {

                                $(this).parent().find(".autocomplete-div").find('li').filter(function() {
                                    return $.text([this]) === current;
                                }).addClass("active-autocomplete-item");

                            }

                        }

                        // Scroll
                        if ($(".active-autocomplete-item").length > 0) {
                            $(this).parent().find(".autocomplete-div").find('li.ui-state-focus').removeClass("ui-state-focus");
                            var parentDiv = $(this).parent().find(".autocomplete-div li.active-autocomplete-item").parent();
                            var activeEl = $(this).parent().find(".autocomplete-div li.active-autocomplete-item");

                            parentDiv.scrollTop(parentDiv.scrollTop() + activeEl.position().top);
                        }

                        // Update font-weight family
                        $("#wyp-autocomplete-place-font-weight ul li").css("fontFamily", $("#wyp-font-family").val());

                        // Font Weight
                        if (id == 'font-weight') {

                            $(".autocomplete-div li").each(function() {

                                // Light 300 > 300
                                var v = Math.abs(number_filter($(this).text()));
                                $(this).css("fontWeight", v);

                            });

                        }

                        // Load fonts
                        if(id == 'font-weight' || id == 'font-family'){
                            load_near_fonts($(this).parent().find(".autocomplete-div"));
                        }

                        // Text shadow show all style
                        if (id == 'text-shadow') {
                            $("#wyp-autocomplete-place-"+id + " li").each(function() {
                                $(this).css("textShadow", $("#wyp-text-shadow-data [data-content='"+$(this).text()+"']").attr("value"));
                            });
                        }

                        // Cursor show all style
                        if (id == 'cursor') {
                            $("#wyp-autocomplete-place-"+id + " li").each(function() {
                                $(this).attr("style", "cursor:"+$("#wyp-cursor-data [data-content='"+$(this).text()+"']").attr("value")+" !important;");
                            });
                        }

                    },

                    appendTo: "#wyp-autocomplete-place-" + $(this).parent().parent().attr("id").replace("-group", "").toString()
                }).click(function() {
                    $(this).autocomplete("search", "");
                });

            });


            /* ---------------------------------------------------- */
            /* Responsive Mode                                      */
            /* ---------------------------------------------------- */
            $(".wyp-responsive-btn").click(function() {

                if (mainBody.hasClass("wyp-css-editor-active")) {
                    $(".wyp-css-close-btn").trigger("click");
                }

            });


            /* ---------------------------------------------------- */
            /* Responsive Mode                                      */
            /* ---------------------------------------------------- */
            $(".wyp-responsive-btn").click(function() {

                if ($(this).hasClass("active")) {
                    body.removeClass("wyp-responsive-device-mode");
                    $(this).addClass("active");
                    var styleAttr = $("#iframe").attr("style");
                    $("#iframe").removeAttr("style");
                    $("#iframe").attr("data-style",styleAttr);
                } else {
                    body.addClass("wyp-responsive-device-mode");
                    $(this).removeClass("active");

                    styleAttr = $("#iframe").attr("data-style");
                    $("#iframe").removeAttr("data-style");
                    $("#iframe").attr("style",styleAttr);

                }

                // Update responsive size notice
                update_responsive_size_notice();

                // draw_box must process first. Fix "margin responsive update" problem
                draw_box(".wyp-selected", 'wyp-selected-boxed');

                // draw all again now
                draw();

                // Updating animation manager if active
                if(is_animation_manager()){
                    animation_manager();
                }

                // updating breakpoints
                update_responsive_breakpoints();

                // Update active options at the last
                insert_default_options();

            });


            /* ---------------------------------------------------- */
            /* Reset Button                                         */
            /* ---------------------------------------------------- */
            $(".wyp-button-reset").click(function() {

                if (is_animate_creator()) {

                    swal({
                      title: l18_closeAnim,
                      showCancelButton: true,
                      animation: false,
                      customClass: "editor-style",
                      confirmButtonText: "Yes",
                    },function(isConfirm){

                        if(isConfirm){

                            swal.close();
                            close_animation_generator(true);

                            setTimeout(function(){
                                reset_popup();
                            }, 500);

                        }

                    });

                    return false;

                }

                reset_popup();

            });


            /* ---------------------------------------------------- */
            /* Reset Popup func                                     */
            /* ---------------------------------------------------- */
            function reset_popup(){

                l18_reset = "Managing " + "<strong>" + $("#customizing-mode").attr("data-heading") + "</strong>.";

                swal({
                  title: l18_reset,
                  text: get_css_manager_layout(),
                  showCancelButton: true,
                  confirmButtonColor: "#80A256",
                  confirmButtonText: "Apply Changes!",
                  closeOnConfirm: true,
                  animation: false,
                  customClass: 'wyp-reset-popup',
                  html: true
                }, function(isConfirm){

                    if(isConfirm && $(".css-manager-empty-notice").length == 0){

                        // Option Changed
                        option_change();

                    }else{

                        // cancel, return to default.
                        the_editor_data().html(window.backupData);

                    }

                    // remove generated table.
                    setTimeout(function(){
                        $(".wyp-reset-popup").remove();
                    }, 200);

                });

            }


            /* ---------------------------------------------------- */
            /* Install all option types                             */
            /* ---------------------------------------------------- */
            $(".wyp-slider-option").each(function() {
                slider_option(get_option_id(this), $(this).data("decimals"), $(this).data("pxv"), $(this).data("pcv"), $(this).data("emv"));
            });

            $(".wyp-radio-option").each(function() {
                radio_option(get_option_id(this));
            });

            $(".wyp-color-option").each(function() {
                color_option(get_option_id(this));
            });

            $(".wyp-input-option").each(function() {
                input_option(get_option_id(this));
            });


            /* ---------------------------------------------------- */
            /* Updating slider by input                             */
            /* ---------------------------------------------------- */
            function update_slide_by_input(element,value,prefix) {

                var elementParent = element.parent().parent().parent();
                var range;

                if(value === false){
                    value = element.parent().find(".wyp-css-value").val();
                    prefix = element.parent().find(".wyp-css-format").val();
                }

                var slide = element.parent().parent().find(".wqNoUi-target");

                // Getting format range
                range = get_format_range(prefix, elementParent);

                // min and max values
                if (range === undefined || range === false) {
                    return false;
                }

                var min = parseInt(range[0]);
                var max = parseInt(range[1]);

                if (value < min) {
                    min = parseInt(value);
                }

                if (value > max) {
                    max = value;
                }

                if (isNaN(min) === false && isNaN(max) === false && isNaN(value) === false){

                    slide.wqNoUiSlider({
                        range: {
                            'min': parseInt(min),
                            'max': parseInt(max)
                        },

                        start: value
                    }, true);

                }

            }

            /* ---------------------------------------------------- */
            /* Process CSS before open CSS editor                   */
            /* ---------------------------------------------------- */
            $("body:not(.wyp-css-editor-active) .css-editor-btn").hover(function() {
                if (!mainBody.hasClass("wyp-css-editor-active")) {
                    process(false);
                }
            });


            /* ---------------------------------------------------- */
            /* Right Click disable                                  */
            /* ---------------------------------------------------- */
            mainDocument.contextmenu(function() {
                return false;
            });


            // auto insert.
            window.disable_auto_insert = false;


            /* ---------------------------------------------------- */
            /* Update editor content and auto go target line        */
            /* ---------------------------------------------------- */
            function editor_update(){

                // No selected
                if (!is_content_selected()) {
                    editor.setValue(get_clean_css(true));
                    editor.focus();
                    editor.execCommand("gotolineend");
                } else if(window.disable_auto_insert == false){
                    insert_rule(null, 'a', 'a', '');
                    var cssData = get_clean_css(false);
                    var goToLine = cssData.split("a:a")[0].split(/\r\n|\r|\n/).length;
                    cssData = cssData.replace(/a:a !important;/g, "");
                    cssData = cssData.replace(/a:a;/g, "");
                    editor.setValue(cssData);
                    editor.resize(true);
                    setTimeout(function(){
                        editor.scrollToLine(goToLine, true, false);
                    },2);
                    editor.focus();
                    if (is_responsive_mod()) {
                        editor.gotoLine(goToLine, 2, true);
                    } else {
                        editor.gotoLine(goToLine, 1, true);
                    }
                }

            }


            /* ---------------------------------------------------- */
            /* Hide CSS Editor                                      */
            /* ---------------------------------------------------- */
            $(".css-editor-btn,.wyp-css-close-btn").click(function() {

                if(is_animation_manager()){
                    $(".animation-manager-btn.active").trigger("click");
                }

                if ($("#leftAreaEditor").css("display") == 'none') {

                    editor_update();

                    $("#cssData,#cssEditorBar,#leftAreaEditor").show();
                    mainBody.addClass("wyp-css-editor-active");

                    var ebtn = $(".css-editor-btn");
                    var title = ebtn.attr("data-original-title"); // Save
                    ebtn.attr("data-title",title); // save as data
                    ebtn.attr("data-original-title",""); // remove title

                    iframeBody.trigger("scroll");

                } else {

                    // CSS To data
                    process(true);

                    // Update breakpoint after CSS changes
                    update_responsive_breakpoints();

                }

                // Update All.
                draw();

            });


            /* ---------------------------------------------------- */
            /* Blur Custom Slider Value                             */
            /* ---------------------------------------------------- */
            $(".wyp-css-value,.wyp-css-format").on("keydown keyup",function(e){

                if(!e.originalEvent){
                    return false;
                }

                var code = e.keyCode || e.which;

                if(code != 9 && code != 17 && code != 18){

                    var element = $(this);

                    clearTimeout(window.manualChangeInputDelay);
                    window.manualChangeInputDelay = setTimeout(function(){

                        var id = element.parents(".wyp-option-group").attr("data-css");
                        var thisContent = $("#" + id + "-group").parent(".wyp-this-content");
                        var lock = thisContent.find(".lock-btn.active").length;
                        var lockedIdArray = [];

                        if(lock){
                            thisContent.find(".wyp-option-group").each(function(){
                                if($(this).attr("data-css") != id){
                                    lockedIdArray.push($(this).attr("data-css"));
                                }
                            });
                        }

                        var value = element.parent().find(".wyp-css-value").val();
                        var prefix = element.parent().find(".wyp-css-format").val();

                        // Self
                        update_slide_by_input(element,false);
                        slide_action($("#wyp-" + id), id, true, false);

                        // others
                        if(lock){

                            for(var y = 0;y < lockedIdArray.length; y++){
                                $("#" + lockedIdArray[y]+"-value").val(value);
                                $("#" + lockedIdArray[y]+"-after").val(prefix);
                                update_slide_by_input($("#" + lockedIdArray[y]+"-value"),value,prefix);
                                slide_action($("#wyp-" + lockedIdArray[y]), lockedIdArray[y], true, false);
                            }

                        }

                        option_change();

                    }, 20);

                }

            });


            /* ---------------------------------------------------- */
            /* Update Gui on load                                   */
            /* ---------------------------------------------------- */
            gui_update();


            /* ---------------------------------------------------- */
            /* Basic Sharp Selector For Editor                      */
            /* ---------------------------------------------------- */
            function get_live_selector(element){

                if(element === undefined){
                    element = get_selected_element();
                }

                // Be sure this item is valid.
                if (element[0] === undefined || element[0] === false || element[0] === null) {
                    return false;
                }

                // Tag info
                var tag = element[0].tagName.toLowerCase();

                // Getting item parents.
                var parents = element.parentsUntil("body"), selector = 'body', currentSelector;

                // Get last selector
                var lastSelector = get_best_class(element);

                // Foreach all loops.
                for (var i = parents.length - 1; i >= 0; i--) {

                    currentSelector = get_best_class(parents[i]);

                    if(/\.|#/g.test(currentSelector)){
                        currentSelector = parents[i].tagName.toLowerCase()+currentSelector;
                    }

                    selector = space_cleaner(selector).trim() + " > " + currentSelector + window.separator;

                } // Each end.

                selector = space_cleaner(selector + " > " + lastSelector + ".wyp-selected");

                return selector;

            }


            /* ---------------------------------------------------- */
            /* Single Selector                                      */
            /* ---------------------------------------------------- */
            function single_selector(selector,test) {

                var customClass = 'wyp-selected';
                if(mainBody.hasClass("wyp-control-key-down") && is_content_selected()){
                    customClass = 'wyp-multiple-selected';
                }

                var selectorArray = get_selector_array(selector);
                var i = 0;
                var indexOf = 0;
                var selectorPlus = '';

                for (i = 0; i < selectorArray.length; i++) {

                    if (i > 0) {
                        selectorPlus += window.separator + selectorArray[i];
                    } else {
                        selectorPlus += selectorArray[i];
                    }

                    if (iframe.find(selectorPlus).length > 1) {

                        iframe.find(selectorPlus).each(function(){

                            if (selectorPlus.substr(selectorPlus.length - 1) != ')') {

                                if ($(this).parent().length > 0) {

                                    indexOf = 0;

                                    $(this).parent().children().each(function() {

                                        indexOf++;

                                        if ($(this).find("."+customClass).length > 0 || $(this).hasClass((customClass))) {

                                            selectorPlus = selectorPlus + ":nth-child(" + indexOf + ")";

                                        }

                                    });

                                }

                            }

                        });

                    }

                }


                // Clean no-need nth-childs.
                if(selectorPlus.indexOf(":nth-child") != -1){

                    // Selector Array
                    selectorArray = get_selector_array(selectorPlus);

                    // Each all selector parts
                    for(i = 0; i < selectorArray.length; i++){

                        // Get previous parts of selector
                        var prevAll = get_previous_item(selectorArray,i).join(" ");

                        // Gext next parts of selector
                        var nextAll = get_next_item(selectorArray,i).join(" ");

                        // check the new selector
                        var selectorPlusNew = prevAll + window.separator + selectorArray[i].replace(/:nth-child\((.*?)\)/i,'') + window.separator + nextAll;

                        // clean
                        selectorPlusNew = space_cleaner(selectorPlusNew);

                        // Check the selector without nth-child and be sure have only 1 element.
                        if(iframe.find(selectorPlusNew).length == 1){
                            selectorArray[i] = selectorArray[i].replace(/:nth-child\((.*?)\)/i,'');
                        }

                    }

                    // Array to spin, and clean selector.
                    selectorPlus = space_cleaner(selectorArray.join(" "));

                }


                // Return the selector without use "add_children_selector" func.
                // Test parement used in get_parents func
                if(test){
                    return selectorPlus;
                }


                // Ready.
                return multiple_variation(add_children_support(selectorPlus));

            }


            /* ---------------------------------------------------- */
            /* Adds > symbol to single_selector func                */
            /* ---------------------------------------------------- */
            function add_children_support(selector){

                // Add " > " to selector. Stable V.
                var selectorArray = get_selector_array(selector);
                var newSelector = '', inSelected, thisSelector, testSelector;
                for(var i = 0; i < selectorArray.length; i++){

                    // Don't use nth-child while spin it
                    thisSelector = selectorArray[i].replace(/:nth-child\((.*?)\)/i,'');

                    // To check select in select
                    testSelector = space_cleaner($.trim(newSelector+window.separator+thisSelector+window.separator+thisSelector));

                    // Check if same selector has in the selector
                    inSelected = iframe.find(testSelector).length;

                    if (inSelected > 0){
                        newSelector = space_cleaner(newSelector) + " > " + selectorArray[i] + window.separator; // Add With '>' separator
                    }else{
                        newSelector += selectorArray[i] + window.separator; // Add with space separator
                    }

                }


                // Need trim to continue.
                newSelector = $.trim(newSelector);


                // Add > symbol to last if selector still finding more element than one.
                if(iframe.find(newSelector).length > 1){
                    newSelector = newSelector.replace(/(?=[^ ]*$)/i,' > ');
                }

                // Cleans ">" symbols from selector if not need.
                if(newSelector.indexOf(">") != -1){

                    var length = newSelector.split(">").length;
                    var elementLength = iframe.find(newSelector).length;

                    for(var i = 1; i < length; i++){

                        if(iframe.find(newSelector.replace(/ > /i,' ')).length == elementLength){
                            newSelector = newSelector.replace(/ > /i,' ');
                        }

                    }

                }

                // Return it
                return space_cleaner(newSelector);

            }


            /* ---------------------------------------------------- */
            /* Previous array item                                  */
            /* ---------------------------------------------------- */
            function get_previous_item(arr,current){

                var result = [];

                for(var i = 0; i < arr.length; i++){

                    if(i < current){

                        result.push(arr[i]);

                    }

                }

                return result;

            }


            /* ---------------------------------------------------- */
            /* Next array item                                      */
            /* ---------------------------------------------------- */
            function get_next_item(arr,current){

                var result = [];

                for(var i = 0; i < arr.length; i++){

                    if(i > current){

                        result.push(arr[i]);

                    }

                }

                return result;

            }


            /* ---------------------------------------------------- */
            /* Breakpoint Bar ContextMenu                           */
            /* ---------------------------------------------------- */
            $.contextMenu({

                events: {

                    show: function() {

                        setTimeout(function(){
                            $(".dom_contextmenu").css("top","15px");
                        },1);

                        var breakpointMedia = $(this).attr("data-breakpoint-data");

                        setTimeout(function(){

                            // has available changes for this breakpoint?
                            if(the_editor_data().find('[data-size-mode="'+breakpointMedia+'"]').length == 0){
                                $(".reset-breakpoint-menu").addClass("disabled");
                                $(".show-css-menu").addClass("disabled");
                            }else{
                                $(".reset-breakpoint-menu").removeClass("disabled");
                                $(".show-css-menu").removeClass("disabled");
                            }

                        },10);

                    }

                },

                selector: '.breakpoint-bar div',
                className: 'dom_contextmenu breakpoint-contextmenu',
                callback: function(key, options) {

                    // Reset functions
                    if(key == 'reset'){

                        // Getting data
                        var breakpointMedia = $(this).attr("data-breakpoint-data");

                        // Warning title
                        var l18_reset_media = "Do you want to reset the <strong class='bold-light'>"+breakpointMedia+"</strong> media query?";

                        // Alert
                        swal({
                          title: l18_reset_media,
                          text: "This will only reset the changes made by you, it doesn't touch the theme style files.",

                          showCancelButton: true,
                          confirmButtonColor: "#DD6B55",
                          confirmButtonText: "Reset!",
                          closeOnConfirm: true,
                          animation: false,
                          customClass: 'editor-style',
                          html: true
                        },function(){

                            // Find all changes in breakpoint size and remove
                            the_editor_data().find('[data-size-mode="'+breakpointMedia+'"]').remove();

                            // changed
                            option_change();

                            // Update
                            insert_default_options();

                            // Update view
                            draw();

                        });

                    }


                    // Show CSS functions
                    if(key == 'showCSS'){

                        // disable
                        window.disable_auto_insert = true;

                        // check css editor is active
                        if (mainBody.hasClass("wyp-css-editor-active") == false) {

                            // Open if hidden
                            $(".css-editor-btn").trigger("click");

                        }

                        // Get editor data from ace editor if open
                        cssData = editor.getValue().replace(/ |\t/g,'');

                        // enable again.
                        window.disable_auto_insert = false;

                        // Getting breakpoint query
                        var mediaQuery = $(this).attr("data-media-content").replace(/ |\t/g,'');

                        // Find media start line
                        var goToLine = cssData.split(mediaQuery)[0].split(/\r\n|\r|\n/).length;

                        // Clear whitespaces for check media query
                        var noSpaceCSS = cssData.replace(/\}\s+\}/g,'}}');

                        // is valid?
                        if(isDefined(noSpaceCSS.split(mediaQuery)[1])){

                            // Delay
                            setTimeout(function(){

                                // Scroll to line
                                editor.scrollToLine(goToLine, true, false);

                            },4);

                            // find total media line number
                            var mediaLines = noSpaceCSS.split(mediaQuery)[1].split(/\}\}/g)[0].split(/\r\n|\r|\n/).length;

                            // load range API
                            var Range = ace.require("ace/range").Range;

                            // Select the media query section
                            editor.selection.setRange(new Range(goToLine-1, 0, (goToLine+mediaLines)+1, 1));

                        }

                    }

                },
                items: {
                    "showCSS": {
                        name: "Show CSS",
                        className: "show-css-menu"
                    },
                    "reset": {
                        name: "Reset",
                        className: "reset-breakpoint-menu"
                    }
                }
            });


            /* ---------------------------------------------------- */
            /* Menu Click prepareParents                            */
            /* ---------------------------------------------------- */
            iframe.on("click", ".wyp-edit-menu", function(event){

                if(event.which == 1){
                    prepare_parents();
                }

            });


            /* ---------------------------------------------------- */
            /* Element Right Click prepareParents                   */
            /* ---------------------------------------------------- */
            iframe.on("click", ".wyp-selected", function(event){

                if(event.which == 3){
                    prepare_parents();
                }

            });


            /* ---------------------------------------------------- */
            /* Focus the element when hover parents in contextmenu  */
            /* ---------------------------------------------------- */
            $(document).on("mouseenter", ".context-menu-list.wyp-contextmenu-parent li", function(event){

                // Parent deep num
                var parentDeepNumber = $(this).prevAll("li").length;

                // target object element
                var targetElement = get_selected_element().parentsUntil("html").eq(parentDeepNumber);

                // selector
                var selector = get_parents(targetElement, "defaultNoCache");

                // Focus
                element_focus(iframe.find(selector));

            });


            /* ---------------------------------------------------- */
            /* Focus the element when hover parents in contextmenu  */
            /* ---------------------------------------------------- */
            $(document).on("mouseleave", ".context-menu-list.wyp-contextmenu-parent li", function(event){

                // delete focus
                iframe.find(".wyp-element-viewer").remove();

            });


            /* ---------------------------------------------------- */
            /* Getting parents and covert object to add contextmenu */
            /* ---------------------------------------------------- */
            function prepare_parents(){

                if(window.parentItems == ''){

                    get_selected_element().parentsUntil("html").each(function(i){
                        window.parentItems += '"parent-'+i+'" : {"name": "'+get_best_class(this)+'"}' + ",";
                    });

                    window.parentItems = $.parseJSON("{" + window.parentItems.replace(/,$/g,'').toString()+"}");

                }

            }


            /* ---------------------------------------------------- */
            /* Frame context menu options.                          */
            /* ---------------------------------------------------- */
            $.contextMenu({

                events: {

                    // Draw Again Borders, Tooltip After Contextmenu Hide.
                    hide: function(opt) {

                        draw();

                    },

                    // if contextmenu show; update some options.
                    show: function() {

                        // Disable contextmenu on animate creator.
                        if (is_animate_creator()) {
                            get_selected_element().contextMenu("hide");
                        }

                        var selector = get_current_selector();

                        var elementP = iframe.find(selector).parent();

                        if (elementP.length > 0 && elementP[0].nodeName.toLowerCase() != "html") {
                            $(".wyp-contextmenu-parent").removeClass("wyp-disable-contextmenu");
                        } else {
                            $(".wyp-contextmenu-parent").addClass("wyp-disable-contextmenu");
                        }

                        if(iframe.find(".wyp-selected-others-box").length > 0){
                            $(".wyp-contextmenu-select-it").show();
                        }else{
                            $(".wyp-contextmenu-select-it").hide();
                        }

                    }

                },

                // Open context menu only if a element selected.
                selector: 'body.wyp-content-selected .wyp-selected,body.wyp-content-selected.wyp-selected',
                callback: function(key, options) {

                    var selector = get_current_selector();

                    // Context Menu: Hover
                    if (key == "hover" || key == "focus" || key == "link" || key == "visited" || key == "active") {

                        selector = selector.replace(/:(?!hover|focus|active|link|visited)/g,"WYP_DOTTED_PREFIX");

                        if (!$(".wyp-contextmenu-" + key).hasClass("wyp-active-contextmenu")){
                            if (selector.indexOf(":") == -1) {
                                selector = selector + ":" + key;
                            } else {
                                selector = selector.split(":")[0] + ":" + key;
                            }
                        } else {
                            selector = selector.split(":")[0];
                        }

                        selector = selector.replace(/WYP_DOTTED_PREFIX/g,":");
                        set_selector(selector, get_selected_element(), true);

                    }

                    // Parent
                    if(key.indexOf("parent-") != -1){

                        // Parent number
                        var n = key.replace("parent-","");

                        // Find target
                        var targetElement = get_selected_element().parentsUntil("html").eq(n);

                        // give point
                        targetElement.addClass("wyp-will-selected");

                        // clean
                        clean();

                        // Get parent selector.
                        var parentSelector = $.trim(get_parents(iframe.find(".wyp-will-selected"), "default"));

                        // Set Selector
                        set_selector(parentSelector, null, false);

                        // delete focus
                        iframe.find(".wyp-element-viewer").addClass("wyp-element-viewer-relax-opacity").removeClass("wyp-element-viewer").fadeOut(300);

                        setTimeout(function(){
                            iframe.find(".wyp-element-viewer-relax-opacity").remove();
                        }, 400);

                    }

                    // write CSS
                    if (key == "writeCSS") {

                        if (mainBody.hasClass("wyp-css-editor-active")) {
                            $(".css-editor-btn").trigger("click");
                        }

                        $(".css-editor-btn").trigger("click");

                    }

                    // Select Just It
                    if (key == 'selectjustit') {

                        mainBody.addClass("wyp-select-just-it");

                        var currentSelector = get_current_selector();

                        if(iframe.find(currentSelector).length > 1){

                            selector = get_parents(null, "sharp");

                            var selectorPlus = single_selector(selector, false);

                            if (iframe.find(selectorPlus).length !== 0) {
                                set_selector(selectorPlus, null, true);
                            }

                        }

                        mainBody.removeClass("wyp-select-just-it");

                    }
                    /* Select just it functions end here */


                    if(key == 'resetit'){
                        reset_selected_element(false);
                    }

                    if(key == 'reset-with-childs'){
                        reset_selected_element(true);
                    }

                    // leave Selected element.
                    if (key == 'close') {
                        clean();
                        gui_update();
                    }

                    // toggle selector editor.
                    if (key == "editselector") {
                        $(".wyp-button-target").trigger("click");
                    }

                },


                // Content menu elements.
                build: function($trigger, e) {
                    return {
                        items: {
                            "hover": {
                                name: ":Hover",
                                className: "wyp-contextmenu-hover"
                            },
                            "focus": {
                                name: ":Focus",
                                className: "wyp-contextmenu-focus"
                            },
                            "sep1": "---------",
                            "parent": {
                                name: "Parent Elements",
                                className: "wyp-contextmenu-parent",
                                items:window.parentItems,
                            },
                            "editselector": {
                                name: "Edit Selector",
                                className: "wyp-contextmenu-selector-edit"
                            },
                            "sep2": "---------",
                            "selectjustit": {
                                name: "Select Only This",
                                className: "wyp-contextmenu-select-it"
                            },
                            "writeCSS": {
                                name: "Write CSS",
                                className: "wyp-contextmenu-type-css"
                            },
                            "reset": {
                                name: "Reset Element",
                                items:{
                                    "resetit": {
                                        name: "The Element",
                                        className: "wyp-contextmenu-reset-it"
                                    },
                                    "reset-with-childs": {
                                        name: "The Child Elements",
                                        className: "wyp-contextmenu-reset-childs"
                                    },
                                },
                            },
                            "close": {
                                name: "Leave",
                                className: "wyp-contextmenu-close"
                            }
                        }
                    }
                }

            });


            /* ---------------------------------------------------- */
            /* Updating Gui of the editor panel                     */
            /* ---------------------------------------------------- */
            function gui_update() {

                // Vars
                var height = 0;

                // Elements
                var panel = $(".editor-panel");
                var panel_editor = $(".editor-panel-inner");
                var top = $(".editor-panel-top");
                var footer = $(".editor-panel-footer");

                // Get Scroll Top because need to keep scrollTop
                // for fix scrolling Top problem after re-edit panel height.
                window.lastScrollTop = panel_editor.scrollTop();

                // Pixels
                var top_offset_px = panel.offset().top;
                var top_px = top.height();
                var margin_px = 18; // default top margin of panel
                var iframe_height = iframe.height();
                var window_height = $(window).height();

                // Max Possible height
                var maximum_height = window_height - (top_offset_px + top_px + margin_px);

                // Remove Style Attr
                panel_editor.removeAttr("style");

                // Get Height
                height = panel_editor.height();

                // Apply Max Height
                if(height > maximum_height){

                    height = maximum_height;
                    panel_editor.height(height);

                }

                // Hide Panel Footer in CSS Properties Section
                if($(".editor-panel-list > li.active").length > 0){
                    footer.hide();
                }else{
                    footer.show();
                }

                // If right panel is fixed - Always
                if(mainBody.hasClass("wyp-fixed-right-panel")){
                    height = window_height - (top_offset_px + top_px);
                    panel_editor.height(height);
                }

                // If right panel is fixed
                footer.css("width",panel.width() + "px");

                // Set Scroll Top
                panel_editor.scrollTop(window.lastScrollTop);

                // Auto Margin Right + Margin Scroll problem fix.
                setTimeout(function(){

                    if (iframe_height > window_height && is_responsive_mod() === false) {
                        panel.css("marginRight", 8 + get_scroll_bar_width() + "px");
                    } else {
                        panel.css("marginRight", "8px");
                    }

                },10);


            }


            /* ---------------------------------------------------- */
            /* Element picker                                       */
            /* ---------------------------------------------------- */
            $(".wyp-element-picker").click(function() {
                mainBody.toggleClass("wyp-element-picker-active");
                $(this).toggleClass("active");
            });


            /* ---------------------------------------------------- */
            /* Measuring Tool                                       */
            /* ---------------------------------------------------- */
            mainDocument.on("mousemove mousedown", function(e){

                if (mainBody.hasClass("wyp-metric-disable") === false) {

                    var x = e.pageX;
                    var y = e.pageY;
                    var cx = e.clientX;
                    var cy = e.clientY;
                    var ww = $(window).width();
                    var wh = $(window).height();

                    if (mainBody.hasClass("wyp-responsive-resizing")) {
                        y = y - 10;
                        x = x - 10;
                        cx = cx - 10;
                        cy = cy - 10;
                    }

                    if ($(this).find("#iframe").length > 0) {

                        if (is_responsive_mod()) {

                            if (mainBody.hasClass("wyp-responsive-resizing")) {

                                // Min 320 W
                                if (cx < 320 + 48) {
                                    cx = 320 + 48;
                                }

                                // Max full-80 W
                                if (cx > ww - 82) {
                                    cx = ww - 82;
                                }

                                // Min 320 H
                                if (cy < 320 + 40) {
                                    cy = 320 + 40;
                                }

                                // Max full-80 H
                                if (cy > wh - 80) {
                                    cy = wh - 80;
                                }

                            }

                            $(".metric-top-border").attr("style", "left:" + cx + "px !important;display:block;margin-left:-1px !important;");
                            $(".metric-left-border").attr("style", "top:" + cy + "px !important;");
                            $(".metric-top-tooltip").attr("style", "top:" + cy + "px !important;display:block;");
                            $(".metric-left-tooltip").attr("style", "left:" + cx + "px !important;display:block;margin-left:1px !important;");

                            if (mainBody.hasClass("wyp-responsive-resizing")) {
                                $(".metric-left-tooltip span").text(x + 10);
                                $(".metric-top-tooltip span").text(y + 10);
                            } else {
                                $(".metric-left-tooltip span").text(x);
                                $(".metric-top-tooltip span").text(y);
                            }

                        }

                    }

                    if ($(this).find("#iframe").length === 0) {

                        if (mainBody.hasClass("wyp-responsive-resizing")) {

                            // Min 320 W
                            if (cx < 320) {
                                cx = 320;
                            }

                            // Max full W
                            if (cx > ww) {
                                cx = ww;
                            }

                            // Min 320 H
                            if (cy < 320) {
                                cy = 320;
                            }

                            // Max full H
                            if (cy > wh) {
                                cy = wh;
                            }

                        }

                        $(".metric-top-border").attr("style", "left:" + cx + "px !important;display:block;");
                        $(".metric-left-border").attr("style", "top:" + cy + "px !important;margin-top:30px;");
                        $(".metric-top-tooltip").attr("style", "top:" + cy + "px !important;display:block;margin-top:32px;");
                        $(".metric-left-tooltip").attr("style", "left:" + cx + "px !important;display:block;");

                        if (mainBody.hasClass("wyp-responsive-resizing")) {
                            $(".metric-top-tooltip span").text(y + 10);
                            $(".metric-left-tooltip span").text(x + 10);
                        } else {
                            $(".metric-top-tooltip span").text(y);
                            $(".metric-left-tooltip span").text(x);
                        }

                    }

                }

            });


            /* ---------------------------------------------------- */
            /* Measuiring Tool: Hover Box                           */
            /* ---------------------------------------------------- */
            iframe.on("mousemove", function(e) {

                if (mainBody.hasClass("wyp-metric-disable") === false){

                    var element = $(e.target);

                    if (is_resizing() || is_visual_editing() || is_dragging()) {
                        element = get_selected_element();
                    }

                    if(element.hasAttr("class")){
                        if(/(^|\s+)wyp-(.*?)/g.test(element.attr("class"))){
                            element = get_selected_element();
                        }
                    }

                    // CREATE SIMPLE BOX
                    var element_offset = element.offset();

                    if (isDefined(element_offset)) {

                        var topBoxesI = element_offset.top;
                        var leftBoxesI = element_offset.left;

                        if (leftBoxesI < 0) {
                            leftBoxesI = 0;
                        }

                        var widthBoxesI = element.outerWidth();
                        var heightBoxesI = element.outerHeight();

                        // Dynamic Box
                        if (iframe.find(".hover-info-box").length === 0) {
                            iframeBody.append("<div class='hover-info-box'></div>");
                        }

                        iframe.find(".hover-info-box").css("width", widthBoxesI).css("height", heightBoxesI).css("top", topBoxesI).css("left", leftBoxesI);

                    }

                    if (isUndefined(element_offset)) {
                        return false;
                    }

                    var topBoxes = element_offset.top;
                    var leftBoxes = element_offset.left;

                    if (leftBoxes < 0) {
                        leftBoxes = 0;
                    }

                    var widthBoxes = element.outerWidth(false);
                    var heightBoxes = element.outerHeight(false);

                    var bottomBoxes = topBoxes + heightBoxes;

                    if (iframe.find(".wyp-size-handle").length === 0) {
                        iframeBody.append("<div class='wyp-size-handle'>W : <span class='ypdw'></span> px<br>H : <span class='ypdh'></span> px</div>");
                    }

                    var w = element.css("width");
                    var h = element.css("height");

                    iframe.find(".wyp-size-handle .ypdw").text(parseInt(w));
                    iframe.find(".wyp-size-handle .ypdh").text(parseInt(h));

                    leftBoxes = leftBoxes + (widthBoxes / 2);

                    iframe.find(".wyp-size-handle").css("top", bottomBoxes).css("bottom", "auto").css("left", leftBoxes).css("position", "absolute");

                    if (parseFloat(bottomBoxes) > (parseFloat($("body #iframe").height()) + parseFloat(iframe.scrollTop())) + 40) {

                        iframe.find(".wyp-size-handle").css("bottom", "10px").css("top", "auto").css("left", leftBoxes).css("position", "fixed");

                    }

                }

            });


            /* ---------------------------------------------------- */
            /* Window Resize                                        */
            /* ---------------------------------------------------- */
            $(window).resize(function(){

                setTimeout(function(){
                    gui_update();
                },5);

                setTimeout(function(){
                    update_gradient_pointers();
                },5);

            });


            /* ---------------------------------------------------- */
            /* Element Selector Box Function                        */
            /* ---------------------------------------------------- */
            iframe.on("mouseover", iframe, function(evt){

               if ($(".wyp-selector-mode.active").length > 0 && mainBody.hasClass("wyp-metric-disable")){

                    // delete live picker if avaiable.
                    if(mainBody.hasClass("wyp-element-picker-active")){
                        iframe.find("#colorPickerLiveCSS").remove();
                    }

                    // Element
                    var element = $(evt.target);

                    // Adding always class to last hovered element for some reasions.
                    iframe.find(".wyp-recent-hover-element").removeClass("wyp-recent-hover-element");

                    if (is_content_selected() === true && mainBody.hasClass("wyp-control-key-down") === false) {
                        element.addClass("wyp-recent-hover-element");
                    }

                    var elementClasses = element.attr("class");

                    // Multi selecting support
                    if (is_content_selected() === false) {
                        if (element.hasClass("wyp-selected-tooltip")) {
                            clean();
                            return false;
                        }

                        if (element.parent().length > 0) {
                            if (element.parent().hasClass("wyp-selected-tooltip")) {
                                clean();
                                return false;
                            }
                        }
                    }

                    // If not any yellow pencil element.
                    if (isDefined(elementClasses)) {
                        if (elementClasses.indexOf("wyp-selected-boxed-") > -1) {
                            return false;
                        }
                    }

                    // If colorpicker stop.
                    if (mainBody.hasClass("wyp-element-picker-active") && /(wyp-tooltip-small|wyp-selected-tooltip)/g.test(elementClasses) == false) {

                        window.pickerColor = element.css("backgroundColor");
                        window.pickerColor = window.pickerColor.toLowerCase().replace(/ /g,"");

                        if (window.pickerColor == '' || window.pickerColor == 'transparent' || window.pickerColor == 'rgba(0,0,0,0)' || window.pickerColor == 'rgba(255,255,255,0)') {

                            var bgColor = window.pickerColor;
                            element.parents().each(function() {

                                if (bgColor != 'transparent' && bgColor != '' && bgColor !== null) {
                                    window.pickerColor = $(this).css("backgroundColor");
                                    return false;
                                }

                            });

                        }

                        // Picker Rule
                        var pickerCSSRule = $(".wyp-element-picker.active").parents(".wyp-option-group").attr("data-css");

                        // insert live picker
                        insert_live_css(pickerCSSRule, get_color(window.pickerColor.toString()), "colorPickerLiveCSS");

                    }

                    var nodeName = element[0].nodeName;

                    // If element already selected, stop.
                    if (is_content_selected() === true && mainBody.hasClass("wyp-control-key-down") === false) {
                        return false;
                    }

                    // Not show if p tag and is empty.
                    if (element.html() == '&nbsp;' && element[0].nodeName == 'P') {
                        return false;
                    }

                    if (nodeName.toLowerCase() == 'html') {
                        return false;
                    }

                    // if Not Null continue.
                    if (element === null) {
                        return false;
                    }

                    // stop if not have
                    if (element.length === 0) {
                        return false;
                    }

                    // If selector disable stop.
                    if (body.hasClass("yp-selector-disabled")) {
                        return false;
                    }

                    if (is_content_selected() === false){

                        // Remove all ex data.
                        clean();

                        // Hover it
                        element.addClass("wyp-selected");

                    }

                    // Geting selector.
                    var selector;
                    if (window.setSelector === false) {
                        selector = get_parents(element, "default");
                    } else {
                        selector = window.setSelector;
                    }


                    evt.stopPropagation();
                    evt.preventDefault();

                    if (is_content_selected() === false) {

                            // transform.
                            if (check_with_parents(element, "transform", "none", "!=") === true) {
                                body.addClass("wyp-has-transform");
                            }

                            draw_box(evt.target, 'wyp-selected-boxed');

                            var selectorView = selector;

                            var selectorTag = selector.replace(/>/g, '').replace(/  /g, ' ').replace(/\:nth-child\((.*?)\)/g, '');

                            // Element Tooltip  |  Append setting icon.
                            iframeBody.append("<div class='wyp-selected-tooltip'><small class='wyp-tooltip-small'>" + get_tag_information(selectorTag) + "</small> " + $.trim(selectorView) + "</div><div class='wyp-edit-tooltip'><span class='wyp-edit-menu'></span></div>");

                            var appendSelectData = '';
                            var currentData = '';

                            // Select Others.. (using .not because will be problem when selector has "," multip selectors)
                            iframe.find(selector).not(".wyp-selected").not(".wyp-multiple-selected").each(function(i) {

                                $(this).addClass("wyp-selected-others");

                                currentData = draw_other_box(this, 'wyp-selected-others', i);

                                if (typeof currentData === 'string') {
                                    appendSelectData += currentData;
                                }

                            });

                            if(appendSelectData != ''){
                                iframeBody.append(appendSelectData);
                            }

                            draw_tooltip();

                    }else{

                        if(is_content_selected() && mainBody.hasClass("wyp-control-key-down")){

                            if(element.parents(".wyp-selected").length === 0){

                                // Clean before
                                iframe.find(".wyp-multiple-selected").removeClass("wyp-multiple-selected");

                                // Add new
                                element.addClass("wyp-multiple-selected");

                                // Draw
                                appendSelectData = draw_other_box(element, 'wyp-selected-others', "multiable");

                                if (typeof appendSelectData === 'string') {
                                    iframeBody.append(appendSelectData);
                                }

                            }

                        }

                    }

                }

            });


            /* ---------------------------------------------------- */
            /* Updating Draws                                       */
            /* ---------------------------------------------------- */
            function draw() {

                // If not visible stop.
                var element = get_selected_element();
                if (check_with_parents(element, "display", "none", "==") === true || check_with_parents(element, "opacity", "0", "==") === true || check_with_parents(element, "visibility", "hidden", "==") === true) {
                    return false;
                }

                // selected boxed.
                draw_box(".wyp-selected", 'wyp-selected-boxed');

                var appendSelectData = '';
                var currentData = '';

                // Select Others.
                iframe.find(".wyp-selected-others:not(.wyp-multiple-selected)").each(function(i) {

                    currentData = draw_other_box(this, 'wyp-selected-others', i);

                    if (typeof currentData === 'string') {
                        appendSelectData += currentData;
                    }

                });

                if(appendSelectData != ''){
                    iframeBody.append(appendSelectData);
                }

                // Tooltip
                draw_tooltip();

                // Dragger update.
                update_drag_handle_position();

            }


            /* ---------------------------------------------------- */
            /* ReSorting styles as the best                         */
            /* ---------------------------------------------------- */
            function resort_style_data_positions(){

                var styleArea = the_editor_data();

                // Sort element by selector because Created CSS Will keep all css rules in one selector.
                styleArea.find("style:not(.wyp-resorted)").each(function(){

                    var that = $(this);

                    // addClass for not sort again.
                    that.addClass("wyp-resorted");

                    // Get this selector.
                    var style = that.attr("data-style");

                    // check if there next styles that has same selector.
                    if(styleArea.find("[data-style="+style+"]").length > 1){

                        // Find all next styles that has same selector
                        styleArea.find("[data-style="+style+"]").not(this).each(function(){

                            // Cache
                            var element = $(this);

                            if(element.hasClass("wyp-resorted") === false){

                                // move from dom.
                                that.after(element);

                            }

                        });

                    }

                });

                // max-width == 9 > 1
                styleArea.find("style[data-size-mode^='(max-width:']").not("[data-size-mode*=and]").sort(function (a,b){
                    return +parseInt(b.getAttribute('data-size-mode').replace(/\D/g,'')) - +parseInt(a.getAttribute('data-size-mode').replace(/\D/g,''));
                }).appendTo(styleArea);

                // min-width == 1 > 9
                styleArea.find("style[data-size-mode^='(min-width:']").not("[data-size-mode*=and]").sort(function (a,b){
                    return +parseInt(a.getAttribute('data-size-mode').replace(/\D/g,'')) - +parseInt(b.getAttribute('data-size-mode').replace(/\D/g,''));
                }).appendTo(styleArea);

            }


            /* ---------------------------------------------------- */
            /* Getting current media query                          */
            /* ---------------------------------------------------- */
            function create_media_query_before(css) {

                if (mainBody.hasClass("process-by-code-editor")) {
                    if (mainBody.attr("data-responsive-type") !== undefined && mainBody.attr("data-responsive-type") !== false && mainBody.attr("data-responsive-type") != 'desktop') {
                        return mainBody.attr("data-responsive-type");
                    } else {
                        return '';
                    }
                }

                if (is_responsive_mod()) {

                    var w = $("#iframe").width();
                    var format = $(".media-control").attr("data-code");
                    return '@media (' + format + ':' + w + 'px){';

                } else {

                    if(isUndefined(css)){
                        return '';
                    }

                    var automedia = get_media_queries(css,false);

                    if(automedia != false && automedia != undefined){
                        mainBody.addClass("wyp-adding-auto-media");
                        return automedia+"{";
                    }else{
                        return '';
                    }


                }

            }


            /* ---------------------------------------------------- */
            /* Getting media query end                              */
            /* ---------------------------------------------------- */
            function create_media_query_after() {

                if (is_responsive_mod()) {

                    return '}';

                } else {

                    // Auto Media
                    if(mainBody.hasClass("wyp-adding-auto-media")){
                        mainBody.removeClass("wyp-adding-auto-media");
                        return '}';
                    }else{
                        return ''; // Blank
                    }

                }

            }


            /* ---------------------------------------------------- */
            /* Toggle Media max/min-width                           */
            /* ---------------------------------------------------- */
            $(".media-control").click(function() {

                var c = $(this).attr("data-code");

                if (c == 'max-width') {
                    $(this).attr("data-code", "min-width");
                    $(this).text("above");
                }

                if (c == 'min-width') {
                    $(this).attr("data-code", "max-width");
                    $(this).text("below");
                }

                update_responsive_size_notice();

                update_responsive_breakpoints();

                $(this).tooltip('fixTitle').tooltip("show");

            });


            /* ---------------------------------------------------- */
            /* Media Control Toolip                                 */
            /* ---------------------------------------------------- */
            $(".media-control").tooltip({

                // Set dynamic title
                title: function(){
                    var format = $(this).attr("data-code");

                    // opposite
                    if(format == 'max-width'){
                        format = 'min-width';
                    }else if(format == 'min-width'){
                        format = 'max-width';
                    }

                    return "Toggle media query condition as <strong>"+format+"</strong>";
                },

                // Tooltip settings
                animation: false,
                delay: { show: 10, hide: 0 },
                placement: 'top',
                trigger: 'hover',
                container: "body",
                html: true

            });


            /* ---------------------------------------------------- */
            /* use important if CSS not working without important   */
            /* ---------------------------------------------------- */
            function force_insert_rule(selector, id, value, prefix, size) {

                if (isUndefined(size)){
                    size = get_media_condition();
                }

                // Update selector
                selector = prepareSelector(selector, id);

                // Clean value
                value = value.replace(/\s+?!important/g,'').replace(/\;$/g,'');

                // Remove Style Without important.
                the_editor_data().find("." + get_id(selector) + '-' + id + '-style[data-size-mode="' + size + '"]').remove();

                // Animation name
                if (id == 'animation-name'){
                    animationName_if(selector, id, value, prefix, size);
                }

                // Checking.
                if (value == 'disable' || value == '' || value == 'undefined' || value === null) {
                    return false;
                }

                // Responsive Settings
                var mediaBefore = create_media_query_before(id);

                if(mainBody.hasClass("wyp-adding-auto-media")){
                    size = space_cleaner(mediaBefore.replace("@media","").replace("{",""));
                }

                var mediaAfter = create_media_query_after();

                if(isDefined(size) && is_animation_manager() && is_responsive_mod()){
                    mediaBefore = "@media " + size + "{";
                }

                // New Value
                var current = value + prefix;

                // Append.
                if (get_id(selector) != '') {

                    if (is_animate_creator() === true && id != 'position') {

                        iframe.find(".wyp-anim-scenes ." + get_id(body.attr("data-anim-scene") + id)).remove();

                        iframe.find(".wyp-anim-scenes ." + body.attr('data-anim-scene') + "").append('<style data-rule="' + id + '" class="style-' + body.attr("data-anim-scene") + ' scenes-' + get_id(id) + '-style">' + selector + '{' + id + ':' + current + ' !important}</style>');

                    } else {

                        the_editor_data().append('<style data-rule="' + id + '" data-size-mode="' + size + '" data-style="' + get_id(selector) + '" class="' + get_id(selector) + '-' + id + '-style wyp_current_styles">' + mediaBefore + '' + '' + selector + '{' + id + ':' + current + ' !important}' + '' + mediaAfter + '</style>');

                        resort_style_data_positions();

                    }

                }

                if(window.stopForceInsert){
                    return false;
                }

                // --- L-O-G-I-C --
                setTimeout(function(){

                    // current selector Length
                    var selectorLength = get_selector_array(selector).length;

                    // Max long selector is 12
                    if((selectorLength + 1) > 12){
                        return false;
                    }

                    // add 1 more to new Selector
                    window.minCroppedSelector = selectorLength + 1;

                    // Generate a better selector
                    var betterSelector = get_parents(iframe.find(".wyp-content-selected .wyp-selected"), window.lastParentQueryStatus);

                    // Return to default
                    window.minCroppedSelector = false;

                    // Stop if not have another selector alternative.
                    if(get_selector_array(betterSelector).length <= selectorLength){
                        return false;
                    }

                    // Check if need to important tag and apply if need.
                    check_force_rule2(
                        id, current, betterSelector, // For checks
                        [mediaBefore,id,":",current,mediaAfter], // Important Append Test
                        [betterSelector,id,value,prefix,size], // for insert_important_rule
                        [true, selector, id, size] // for remove old insert_important_rule
                    );

                    setTimeout(function(){
                        draw();
                    }, 10);

                }, window.YellowDelay);


            }


            // Typing Timer Editor
            var typingTimer;


            /* ---------------------------------------------------- */
            /* Updating Draw/Gui while keyUp                        */
            /* ---------------------------------------------------- */
            $("#cssData").on("keyup", function(e) {

                var typingTimerS = 0;
                if(e.originalEvent){
                    typingTimerS = 900;
                }

                if (body.hasClass("wyp-selectors-hide") === false && body.hasClass("wyp-css-data-trigger") === false && typingTimerS !== 0) {

                    body.addClass("wyp-selectors-hide");

                    // Opacity Selector
                    if (iframe.find(".context-menu-active").length > 0) {
                        get_selected_element().contextMenu("hide");
                    }

                    hide_frame_ui(0);

                }

                body.removeClass("wyp-css-data-trigger");

                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {

                    if (body.hasClass("wyp-selectors-hide") && $(".wqNoUi-active").length === 0 && mainBody.hasClass("autocomplete-active") === false && $(".editor-panel .tooltip").length === 0) {

                        body.removeClass("wyp-selectors-hide");

                        show_frame_ui(200);

                    }

                    insert_default_options();
                    return false;

                }, typingTimerS);

                // Append all css to iframe.
                if (iframe.find("#wyp-css-data-full").length === 0) {
                    the_editor_data().after("<style id='wyp-css-data-full'></style>");
                }

                // Need to process.
                mainBody.addClass("wyp-need-to-process");

                // Update css source.
                iframe.find("#wyp-css-data-full").html(css_editor_filter(editor.getValue()));

                // Empty data.
                the_editor_data().empty();

                // Remove ex.
                iframe.find(".wyp-live-css").remove();

                // Update
                $(".wyp-save-btn").html(l18_save).removeClass("wyp-disabled").addClass("waiting-for-save");

                // Update current data
                the_editor_data().addClass("wyp-data-updated");
                $(".active-customizing-list").addClass("customizing-type-updated");

                // Focus To Type
                if($(".wyp-type-menu-link.done").length == 0){
                    $(".wyp-type-menu-link").addClass("focus").addClass("done");
                    setTimeout(function(){
                        $(".wyp-type-menu-link").removeClass("focus");
                    }, 600);
                }

                // Update sceen.
                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Filtering user CSS by CSS Editor                     */
            /* ---------------------------------------------------- */
            function css_editor_filter(data){

                // Convert default interactives to YP style.
                data = data.replace(/(^|\s|\})(.*?):(hover|focus|active|visited|link){/g, function(match, contents, selector, hover){
                    return get_native_selector(match);
                });

                return data;

            }


            /* ---------------------------------------------------- */
            /* Process CSS                                          */
            /* ---------------------------------------------------- */
            $(".editor-panel").on("mouseover mouseout", function() {

                if (mainBody.hasClass("wyp-need-to-process")) {

                    // CSS To Data.
                    process(false);

                }

            });


            /* ---------------------------------------------------- */
            /* Define the plugin elements                           */
            /* ---------------------------------------------------- */
            window.wyp_elements = ".wyp-selected-handle,.wyp-selected-tooltip,.wyp-selected-boxed-margin-top,.wyp-selected-boxed-margin-bottom,.wyp-selected-boxed-margin-left,.wyp-selected-boxed-margin-right,.wyp-selected-boxed-top,.wyp-selected-boxed-bottom,.wyp-selected-boxed-left,.wyp-selected-boxed-right,.wyp-selected-others-box,.wyp-edit-tooltip,.wyp-selected-boxed-padding-top,.wyp-selected-boxed-padding-bottom,.wyp-selected-boxed-padding-left,.wyp-selected-boxed-padding-right,.wyp-edit-menu";


            /* ---------------------------------------------------- */
            /* Hide blue borders                                    */
            /* ---------------------------------------------------- */
            function hide_frame_ui(number) {

                if (!is_content_selected()) {
                    return false;
                }

                if (iframe.find(".wyp-selected-boxed-top").css("opacity") != 1) {
                    return false;
                }

                draw();

                iframe.find(window.wyp_elements).stop().animate({
                    opacity: 0
                }, number);

            }


            /* ---------------------------------------------------- */
            /* Show blue border                                     */
            /* ---------------------------------------------------- */
            function show_frame_ui(number) {

                if (!is_content_selected()) {
                    return false;
                }

                if(body.hasClass("wyp-force-hide-select-ui")){
                    return false;
                }

                if (iframe.find(".wyp-selected-boxed-top").css("opacity") != "0") {
                    return false;
                }

                draw();

                iframe.find(window.wyp_elements).stop().not(".wyp-selected-others-box").animate({
                    opacity: 1
                }, number);

                iframe.find(".wyp-selected-others-box").stop().animate({
                    opacity: 0.9
                }, number);

            }


            /* ---------------------------------------------------- */
            /* Hide borders on panel and animation generator bar    */
            /* ---------------------------------------------------- */
            $(".wyp-this-content,.anim-bar").bind({
                mouseenter: function() {

                    if($(".fake-layer").length > 0){
                        return false;
                    }

                    if (body.hasClass("wyp-selectors-hide") === false) {

                        body.addClass("wyp-selectors-hide");

                        // Opacity Selector
                        if (iframe.find(".context-menu-active").length > 0) {
                            get_selected_element().contextMenu("hide");
                        }

                        hide_frame_ui(200);

                    }

                },
                mouseleave: function() {

                    if($(".fake-layer").length > 0){
                        return false;
                    }

                    if (body.hasClass("wyp-selectors-hide") && $(".wqNoUi-active").length === 0 && mainBody.hasClass("autocomplete-active") === false && $(".editor-panel .tooltip").length === 0) {

                        body.removeClass("wyp-selectors-hide");

                        show_frame_ui(200);

                    }

                }
            });


            /* ---------------------------------------------------- */
            /* Iframe mouseover selection                           */
            /* ---------------------------------------------------- */
            iframe.on("mouseover", iframe, function(){

                if ($(".wqNoUi-active").length === 0 && mainBody.hasClass("autocomplete-active") === false && $(".editor-panel .tooltip").length === 0) {

                    show_frame_ui(200);

                }

            });


            /* ---------------------------------------------------- */
            /* Show borders when panel mouseleave                   */
            /* ---------------------------------------------------- */
            iframe.on("mouseleave", ".editor-panel", function(){

                if (body.hasClass("wyp-selectors-hide") && $(".wqNoUi-active").length === 0 && mainBody.hasClass("autocomplete-active") === false && $(".editor-panel .tooltip").length === 0) {

                    body.removeClass("wyp-selectors-hide");

                    show_frame_ui(200);

                }

            });


            /* ---------------------------------------------------- */
            /* Get current media condition                          */
            /* ---------------------------------------------------- */
            function get_media_condition(){

                // Default
                var size = 'desktop';

                // Is res?
                if (is_responsive_mod()) {

                    var frameWidth = $("#iframe").width();
                    var media = $(".media-control").attr("data-code");
                    size = '(' + media + ':' + frameWidth + 'px)';

                }

                return size;

            }


            /* ---------------------------------------------------- */
            /* Getting all CSS selectors in stylesheets             */
            /* ---------------------------------------------------- */
            function get_all_selectors(source){

                // Get data
                source = editor.getValue();

                // Minimize CSS
                source = get_minimized_css(source,false);

                // Stop if not have any data.
                if (source == '') {
                    return false;
                }

                // Stop if have any conflict in CSS
                if (source.split('{').length != source.split('}').length) {
                    return false;
                }

                // outing media content and delete @media group
                source = source.replace(/@media(.*?){(.*?)}}/g,"$2}");

                // Getting All CSS Selectors.
                var allSelectors = array_cleaner(source.replace(/\{(.*?)\}/g, '|BREAK|').split("|BREAK|"));

                // return
                return allSelectors;

            }


            /* ---------------------------------------------------- */
            /* Resetting selected element                           */
            /* ---------------------------------------------------- */
            function reset_selected_element(childs){

                // If not have an selected element
                if(!is_content_selected()){
                    return false;
                }

                // Selectors
                var array = get_all_selectors();

                // If not have selectors
                if(array.length <= 0){
                    return false;
                }

                // Getting curretn selector
                var currentSelectorLength = iframe.find(get_current_selector()).length;
                var find;

                // Each all selectors
                for(var i = 0; i < array.length; i++){

                    // loop selector
                    var searchSelector = get_foundable_query(array[i],true,true,true);

                    // if childs not in reset
                    if(childs === false){

                        // Finds
                        find = iframe.find(searchSelector);

                        // if target is selected element.
                        if(find.hasClass("wyp-selected") && find.length == currentSelectorLength){

                            // remove
                            the_editor_data().find("[data-style='"+get_id(get_native_selector(array[i]))+"']").remove();

                        }

                    // With childs
                    }else{

                        // Target is selected element and childs.
                        if(iframe.find(searchSelector).parents(".wyp-selected").length > 0){

                            // remove
                            the_editor_data().find("[data-style='"+get_id(get_native_selector(array[i]))+"']").remove();

                        }

                    }

                }

                // Update
                option_change();

                // Set && update
                insert_default_options();

            }


            /* ---------------------------------------------------- */
            /* Convert hover selectors to plugin's native selectors */
            /* ---------------------------------------------------- */
            function get_native_selector(selector){

                // getting seleciton
                var selection = selector.match(/:(hover|focus|active|visited|link)/g);

                // Replace selector if has selection else return selector
                if(selection != null){
                    selection = selection[0].replace(/:/g, '');
                }else{
                    return selector;
                }

                // Vars
                var before = '', after = '';

                // Dedection
                if(selection == 'hover' || selection == 'focus' || selection == 'active' || selection == 'visited' || selection == 'link'){

                    // maybe has for css_editor_filter
                    if(selector.charAt(0) == '}'){
                        before = "}";
                    }

                    // maybe has for css_editor_filter
                    if(selector.slice(-1) == '{'){
                        after = "{";
                    }

                    // No unaccepted chars
                    selector = selector.replace(/(\{|\})/g,'');

                    // vars
                    var selector0, selector1, selector2, selector3, selector4, selector5, selector6;

                    // Clean
                    selector = selector.replace(/\.wyp-selected(.*?){/g,'{');
                    selector = selector.replace(/(body)?\.yp-selector-(hover|focus|active|visited|link)/g,'');
                    selector = selector.replace(/:(hover|focus|active|visited|link)(\s+)?$/g,"");

                    // Default
                    selector0 = selector + ":" + selection;

                    // Add to element
                    selector = add_class_to_body(selector, 'yp-selector-' + selection);

                    // Add wyp-selected to last of selector.
                    selector1 = selector.replace('body.yp-selector-' + selection + ' body.yp-selector-' + selection + ' ', 'body.yp-selector-' + selection + ' ') + ".wyp-selected";

                    selector2 = selector.replace('body.yp-selector-' + selection + ' body.yp-selector-' + selection + ' ', 'body.yp-selector-' + selection + ' ') + ".wyp-selected-others";

                    selector3 = selector.replace('body.yp-selector-' + selection + ' body.yp-selector-' + selection + ' ', 'body.yp-selector-' + selection + ' ') + ".wyp-selected:"+selection;

                    selector4 = selector.replace('body.yp-selector-' + selection + ' body.yp-selector-' + selection + ' ', 'body.yp-selector-' + selection + ' ') + ".wyp-selected-others:"+selection;

                    selector5 = selector.replace('body.yp-selector-' + selection + ' body.yp-selector-' + selection + ' ', 'body.yp-selector-' + selection + ' ') + ".wyp-selected-parents";

                    selector6 = selector.replace('body.yp-selector-' + selection + ' body.yp-selector-' + selection + ' ', 'body.yp-selector-' + selection + ' ') + ".wyp-selected-parents:"+selection;

                    // Selector
                    selector = before + selector1 + "," + selector2 + "," + selector3 + "," + selector4 + "," + selector5 + "," + selector6 + "," + selector0 + after;

                }

                return selector;

            }


            /* ---------------------------------------------------- */
            /* Clean not etc sysbols                                */
            /* ---------------------------------------------------- */
            function nice_selectors(data,start){

                if(start === true){

                    // Nth child
                    data = data.replace(/:nth-child\((.*?)\)/g, '\.nth-child\.$1\.');

                    // Not
                    data = data.replace(/:not\((.*?)\)/g, '\.notYP$1YP');

                    // lang
                    data = data.replace(/:lang\((.*?)\)/g, '\.langYP$1YP');

                    // nth-last-child()
                    data = data.replace(/:nth-last-child\((.*?)\)/g, '\.nth-last-child\.$1\.');

                    // nth-last-of-type()
                    data = data.replace(/:nth-last-of-type\((.*?)\)/g, '\.nth-last-of-type\.$1\.');

                    // nth-of-type()
                    data = data.replace(/:nth-of-type\((.*?)\)/g, '\.nth-of-type\.$1\.');

                }else{

                    // Nth child
                    data = data.replace(/\.nth-child\.(.*?)\./g, ':nth-child($1)');

                    // Not
                    data = data.replace(/\.notYP(.*?)YP/g, ':not($1)');

                    // lang
                    data = data.replace(/\.langYP(.*?)YP/g, ':lang($1)');

                    // nth-last-child()
                    data = data.replace(/\.nth-last-child\.(.*?)\./g, ':nth-last-child($1)');

                    // nth-last-of-type()
                    data = data.replace(/\.nth-last-of-type\.(.*?)\./g, ':nth-last-of-type($1)');

                    // nth-of-type()
                    data = data.replace(/\.nth-of-type\.(.*?)\./g, ':nth-of-type($1)');

                }

                return data;

            }


            /* ---------------------------------------------------- */
            /* Super Basic insert any CSS rule to plugin data       */
            /* ---------------------------------------------------- */
            function get_insert_rule_basic(selector, id, value, size) {

                var appendData = '';

                if (isUndefined(size)) {
                    if (is_responsive_mod()) {
                        var frameW = $("#iframe").width();
                        var format = $(".media-control").attr("data-code");
                        size = '(' + format + ':' + frameW + 'px)';
                    } else {
                        size = 'desktop';
                    }
                }

                if (id == '-webkit-filter' || id == '-webkit-transform') {
                    return '';
                }

                // Responsive Settings
                var mediaBefore = create_media_query_before(id);

                if(mainBody.hasClass("wyp-adding-auto-media")){
                    size = space_cleaner(mediaBefore.replace("@media","").replace("{",""));
                }

                var mediaAfter = create_media_query_after();

                if(isDefined(size) && is_animation_manager() && is_responsive_mod()){
                    mediaBefore = "@media " + size + "{";
                }

                // Append default value.
                if (get_id(selector) != '' && value != 'disable'){

                    var dpt = ':';

                    // Append
                    appendData = '<style data-rule="' + id + '" data-size-mode="' + size + '" data-style="' + get_id(selector) + '" class="' + get_id(selector) + '-' + id + '-style wyp_current_styles">' + mediaBefore + '' + '' + selector + '{' + id + dpt + value + '}' + '' + mediaAfter + '</style>';

                    // Filter & transform, re-append with -webkit
                    if (id == 'filter' || id == 'transform') {
                        id = '-webkit-' + id;
                        appendData += '<style data-rule="' + id + '" data-size-mode="' + size + '" data-style="' + get_id(selector) + '" class="' + get_id(selector) + '-' + id + '-style wyp_current_styles">' + mediaBefore + '' + '' + selector + '{' + id + dpt + value + '}' + '' + mediaAfter + '</style>';
                    }

                }

                return appendData;

            }


            /* ---------------------------------------------------- */
            /* insert CSS to the plugin data                        */
            /* ---------------------------------------------------- */
            function css_to_data(type) {

                // add classses and use as flag.
                mainBody.addClass("process-by-code-editor").attr("data-responsive-type", type);

                // Source.
                var source = css_editor_filter(editor.getValue());

                // Clean "()" symbol for lets to process CSS as well.
                source = nice_selectors(source,true);

                // Clean.
                source = source.replace(/(\r\n|\n|\r)/g, "").replace(/\t/g, '');

                // Don't care rules in comment.
                source = source.replace(/\/\*(.*?)\*\//g, "");

                // clean.
                source = source.replace(/\}\s+\}/g, '}}').replace(/\s+\{/g, '{').replace(/\}\s+/g,'}');

                // clean.
                source = source.replace(/\s+\}/g, '}').replace(/\{\s+/g, '{');

                // replace bad queris
                source = filter_bad_queries(source);

                // If responsive
                if (type != 'desktop') {

                    // Media query regex. Clean everything about media.
                    var regexType = $.trim(type.replace(/\)/g, "\\)").replace(/\(/g, "\\("));
                    var re = new RegExp(regexType + "(.*?)\}\}", "g");
                    var reQ = new RegExp(regexType, "g");
                    source = source.match(re).toString();

                    source = source.replace(reQ, "");
                    source = source.toString().replace(/\}\}/g, "}");

                } else {

                    // Don't care rules in media query in non-media mode.
                    source = source.replace(/@media(.*?)\}\}/g, '');

                }

                // if no source, stop.
                if (source == '') {
                    return false;
                }

                // if have a problem in source, stop.
                if (source.split('{').length != source.split('}').length) {
                    swal({title: "Sorry.",text: "CSS Parse Error. The recent edit could not be saved, please try again.",type: "error",animation: false,customClass: "editor-style-ok"});
                    return false;
                }

                var selector,insertData = '';

                // IF Desktop; Remove All Rules. (because first call by desktop)
                if (type == 'desktop') {
                    the_editor_data().empty();
                }

                // Replace ","" after "}".
                source = source.toString().replace(/\}\,/g, "}");

                // Getting All CSS Selectors.
                var allSelectors = array_cleaner(source.replace(/\{(.*?)\}/g, '|BREAK|').split("|BREAK|"));

                // add to first.
                source = "}" + source;

                // Make } it two for get multiple selectors rules.
                source = source.replace(/\}/g,"}}");

                // Each All Selectors
                for (var i = 0; i < allSelectors.length; i++) {

                    // Get Selector.
                    selector = space_cleaner(allSelectors[i]);

                    // Valid selector
                    if (selector.indexOf("}") == -1 && selector.indexOf("{") == -1) {

                        // Clean selector with regex.
                        var selectorRegex = selector_regex(selector);

                        // Getting CSS Rules by selector.
                        var CSSRules = source.match(new RegExp("\}" + selectorRegex + '{(.*?)}', 'g'));

                        // Back up cleanen "(" symbols
                        selector = nice_selectors(selector,false);

                        if (CSSRules !== null && CSSRules != '') {

                            // Clean.
                            CSSRules = CSSRules.toString().match(/\{(.*?)\}/g).toString().replace(/\}\,\{/g, ';').replace(/\{/g, '').replace(/\}/g, '').replace(/\;\;/g, ';').split(";");

                            // Variables.
                            var ruleAll;
                            var ruleName;
                            var ruleVal;

                            // Each CSSRules.
                            for (var iq = 0; iq < CSSRules.length; iq++) {

                                ruleAll = $.trim(CSSRules[iq]);

                                if (typeof ruleAll !== undefined && ruleAll.length >= 3 && ruleAll.indexOf(":") != -1) {

                                    ruleName = ruleAll.split(":")[0];

                                    if (ruleName != '' && ruleName != 'a') {

                                        ruleVal = ruleAll.split(':').slice(1).join(':');

                                        if (ruleVal != '') {

                                            // Update
                                            insertData += get_insert_rule_basic(selector, ruleName, ruleVal, type.toString().replace(/\{/g, '').replace(/@media /g, '').replace(/@media/g, ''));

                                        }

                                    }

                                }

                            }

                        }

                    }

                }

                // insert at end.
                if(insertData != ''){
                    the_editor_data().append(insertData);
                    resort_style_data_positions();
                }

                // remove classes when end.
                mainBody.removeAttr("data-responsive-type");

            }


            /* ---------------------------------------------------- */
            /* This trigger must-have css rules for animation name  */
            /* ---------------------------------------------------- */
            function animationName_if(selector, id, value, prefix, size){

                // is selected, valid value.
                if (value != 'disable' && value != 'none' && is_content_selected() && is_animation_manager() === false){

                    // add "s" if is one animate
                    if($("#animation-duration-group").hasClass("hidden-option") === false && $("#animation-delay-group").hasClass("hidden-option") === false){

                        // Get duration from CSS
                        var duration = get_selected_element().css("animationDuration").replace(/[^0-9.,]/g, '');

                        // Get delay from CSS
                        var delay = get_selected_element().css("animationDelay").replace(/[^0-9.,]/g, '');

                        // If selected element;
                        if (get_foundable_query(selector,false,true,true) == get_current_selector().trim()){

                            // Duration
                            if(duration == "0"){
                                duration = 1;
                            }

                            // update with s prefix
                            insert_rule(selector, 'animation-duration', duration + 's', prefix, size);


                            // Delay
                            if (delay < 0) {
                                delay = 0;
                            }

                            // update with s prefix
                            insert_rule(selector, 'animation-delay', delay + 's', prefix, size);

                        }

                    }

                    // Get fill mode from CSS
                    var fillMode = get_selected_element().css("animationFillMode");

                    // FillMode
                    if (fillMode == null || fillMode == 'none') {
                        fillMode = 'both';
                    }

                    insert_rule(get_current_selector(), 'animation-fill-mode', fillMode, prefix, size);

                    // Update Options
                    set_default_value('animation-duration');
                    set_default_value('animation-delay');
                    set_default_value('animation-fill-mode');

                }

                if (value == 'bounce') {

                    if (value != 'disable' && value != 'none') {
                        insert_rule(selector, 'transform-origin', 'center bottom', prefix, size);
                    } else {
                        insert_rule(selector, 'transform-origin', value, prefix, size);
                    }

                } else if (value == 'swing') {

                    if (value != 'disable' && value != 'none') {
                        insert_rule(selector, 'transform-origin', 'top center', prefix, size);
                    } else {
                        insert_rule(selector, 'transform-origin', value, prefix, size);
                    }

                } else if (value == 'jello') {

                    if (value != 'disable' && value != 'none') {
                        insert_rule(selector, 'transform-origin', 'center', prefix, size);
                    } else {
                        insert_rule(selector, 'transform-origin', value, prefix, size);
                    }

                } else {
                    insert_rule(selector, 'transform-origin', 'disable', prefix, size);
                }

                if (value == 'flipInX') {
                    insert_rule(selector, 'backface-visibility', 'visible', prefix, size);
                } else {
                    insert_rule(selector, 'backface-visibility', 'disable', prefix, size);
                }

            }


            /* ---------------------------------------------------- */
            /* Comparison Selectors                                 */
            /* ---------------------------------------------------- */
            /* Check two if selectors makes same work               */
            function comparison_selector(selector, selectorNew){

                // Default
                var elements = iframe.find(selector);

                // Add class to test
                elements.addClass("test-selector1-group");

                // Selector New Elements
                var elementsNew = iframe.find(selectorNew).filter(".test-selector1-group");

                // remove test class
                elements.removeClass("test-selector1-group");

                // Check, be sure all selecting same elements.
                if(elements.length != elementsNew.length){
                    return false;
                }


                /* Selector Mode */
                if(/yp-selector-hover/g.test(selector) != /yp-selector-hover/g.test(selectorNew)){
                    return false;
                }

                if(/yp-selector-focus/g.test(selector) != /yp-selector-focus/g.test(selectorNew)){
                    return false;
                }

                if(/yp-selector-active/g.test(selector) != /yp-selector-active/g.test(selectorNew)){
                    return false;
                }

                if(/yp-selector-visited/g.test(selector) != /yp-selector-visited/g.test(selectorNew)){
                    return false;
                }

                if(/yp-selector-link/g.test(selector) != /yp-selector-link/g.test(selectorNew)){
                    return false;
                }


                /* Yp Scenes */
                if(/wyp-scene-1/g.test(selector) != /wyp-scene-1/g.test(selectorNew)){
                    return false;
                }

                if(/wyp-scene-2/g.test(selector) != /wyp-scene-2/g.test(selectorNew)){
                    return false;
                }

                if(/wyp-scene-3/g.test(selector) != /wyp-scene-3/g.test(selectorNew)){
                    return false;
                }

                if(/wyp-scene-4/g.test(selector) != /wyp-scene-4/g.test(selectorNew)){
                    return false;
                }

                if(/wyp-scene-5/g.test(selector) != /wyp-scene-5/g.test(selectorNew)){
                    return false;
                }

                if(/wyp-scene-6/g.test(selector) != /wyp-scene-6/g.test(selectorNew)){
                    return false;
                }


                /* Yp Selected */
                if(/wyp-selected/g.test(selector) != /wyp-selected/g.test(selectorNew)){
                    return false;
                }


                /* Animations */
                if(/yp_onscreen/g.test(selector) != /yp_onscreen/g.test(selectorNew)){
                    return false;
                }

                if(/yp_hover/g.test(selector) != /yp_hover/g.test(selectorNew)){
                    return false;
                }

                if(/yp_focus/g.test(selector) != /yp_focus/g.test(selectorNew)){
                    return false;
                }

                if(/yp_click/g.test(selector) != /yp_click/g.test(selectorNew)){
                    return false;
                }


                /* CSS Methods */
                if(/:active/g.test(selector) != /:active/g.test(selectorNew)){
                    return false;
                }

                if(/:checked/g.test(selector) != /:checked/g.test(selectorNew)){
                    return false;
                }

                if(/:disabled/g.test(selector) != /:disabled/g.test(selectorNew)){
                    return false;
                }

                if(/:empty/g.test(selector) != /:empty/g.test(selectorNew)){
                    return false;
                }

                if(/:enabled/g.test(selector) != /:enabled/g.test(selectorNew)){
                    return false;
                }

                if(/:hover/g.test(selector) != /:hover/g.test(selectorNew)){
                    return false;
                }

                if(/:focus/g.test(selector) != /:focus/g.test(selectorNew)){
                    return false;
                }

                if(/:in-range/g.test(selector) != /:in-range/g.test(selectorNew)){
                    return false;
                }

                if(/:invalid/g.test(selector) != /:invalid/g.test(selectorNew)){
                    return false;
                }

                if(/:lang/g.test(selector) != /:lang/g.test(selectorNew)){
                    return false;
                }

                if(/:link/g.test(selector) != /:link/g.test(selectorNew)){
                    return false;
                }

                if(/:optional/g.test(selector) != /:optional/g.test(selectorNew)){
                    return false;
                }

                if(/:out-of-range/g.test(selector) != /:out-of-range/g.test(selectorNew)){
                    return false;
                }

                if(/:valid/g.test(selector) != /:valid/g.test(selectorNew)){
                    return false;
                }

                if(/:visited/g.test(selector) != /:visited/g.test(selectorNew)){
                    return false;
                }

                return true;

            }


            /* ---------------------------------------------------- */
            /* Updating customizing type frame                      */
            /* ---------------------------------------------------- */
            function update_type_frame(){

                // Type frame
                var iframe = $("#wyp-customizing-type-frame");

                // Getting vars
                var page_mode = $(".active-customizing-list").attr("data-value");
                var page_id = iframe.attr("data-page-id");
                var page_type = iframe.attr("data-page-type");
                var page_visitor = iframe.attr("data-page-visitor");

                // Update visitor view param
                if(page_visitor == "true" || page_visitor == true){
                    page_visitor = "&wyp_out=true";
                }else{
                    page_visitor = "";
                }

                // Generate new URL
                var newSrc = iframe.attr('data-src') + "&wyp_page_id=" + page_id + "&wyp_page_type=" + page_type + "&wyp_mode=" + page_mode + page_visitor;

                // IF same, don't update
                if(newSrc == iframe.attr("src")){
                    return false;
                }

                // Set to SRC
                iframe.attr('src',newSrc);

            }


            /* ---------------------------------------------------- */
            /* Open Customizing Type Popup                          */
            /* ---------------------------------------------------- */
            $(document).on( "click", "#wyp-current-page", function(){

                $(this).toggleClass("active");

                var iframe = $("#wyp-customizing-type-frame");
                iframe.css("display", "block");

                update_type_frame();

                return false;

            });


            /* ---------------------------------------------------- */
            /* Open Customizing Type Menu                           */
            /* ---------------------------------------------------- */
            $(document).on( "click", "#customizing-mode", function(){

                $("#customizing-type-list").toggle();
                $("#customizing-type-list").toggleClass("active");

                $("#customizing-type-list li").each(function(){

                    var type = $(this).attr("data-value");
                    var element = $(this);

                    // If template disabled, so it uses single as view.
                    // update it.
                    if($("#customizing-type-list .type-disabled[data-value='template']").length > 0 && type == 'single'){
                        type = 'template';
                    }else if($("#customizing-type-list .type-disabled[data-value='template']").length > 0 && type == 'template'){
                        type = 'single';
                    }

                    var css = get_clean_css(true, type);

                    if(css.length > 0){
                        var bayt = get_clean_css(true, type).match(/:(.*?);/g).length;
                        element.find(".type-byte span").html(bayt + " rule");
                    }else{
                        element.find(".type-byte span").html("empty");
                    }

                });


            });


            // Show a tooltip for disabled customizing type.
            $('.type-disabled').tooltip({
                animation: false,
                title: 'This customizing type can not be used on the current page.',
                placement: 'left',
                container: "#customizing-type-list",
                html: true
            });


            /* ---------------------------------------------------- */
            /* Change Customizing Type                              */
            /* ---------------------------------------------------- */
            $(document).on( "click", "#customizing-type-list li", function(){

                // Disabled type
                if($(this).hasClass("type-disabled")){
                    return false;
                }

                // Active customizing type
                var type = $(this).attr("data-value");

                // Data type only
                var contentType = type;

                // If template disabled, so it uses single as view.
                // update it.
                if($("#customizing-type-list .type-disabled[data-value='template']").length > 0 && type == 'single'){
                    contentType = 'template';
                }

                var typeHeading = $(this).find("h6 > span").text();
                var heading = $(this).attr("data-type-heading");

                // Updating
                iframe.find(".wyp-inline-data").removeClass("wyp-styles-area");
                iframe.find('.wyp-inline-data[data-source-mode="'+contentType+'"]').addClass("wyp-styles-area");

                // Make current type active
                $(".active-customizing-list").removeClass("active-customizing-list");
                $(this).addClass("active-customizing-list");

                // Update active heading
                $("#customizing-mode .type-heading").text(typeHeading);

                // Update advanced heading
                $("#customizing-mode").attr("data-heading",heading);
                $(".css-editor-heading span").text(heading);

                // Close Menu
                $("#customizing-type-list").hide();

                // Update options
                insert_default_options();

                // Update animation manager content
                if(is_animation_manager()){
                    animation_manager();
                }

                // Update editor content live
                editor_update();

                // Update frame params
                update_type_frame();

            });



            /* ---------------------------------------------------- */
            /* Appy CSS To theme for demo                           */
            /* ---------------------------------------------------- */
            function insert_rule(selector, id, value, prefix, size) {

                if(selector === null){
                    selector = get_current_selector();
                }

                if (isUndefined(size)){
                    size = get_media_condition();
                }

                prefix = $.trim(prefix);

                if (prefix == '.s') {
                    prefix = 's';
                }

                if (prefix.indexOf("px") != -1) {
                    prefix = 'px';
                }

                var css = id;

                // Delete basic CSS.
                delete_live_css(id, false);

                // delete live css.
                iframe.find(".wyp-live-css").remove();

                // stop if empty
                if (isUndefined(value)) {
                    return false;
                }

                // toLowerCase
                id = id.toString().toLowerCase();
                css = css.toString().toLowerCase();
                prefix = prefix.toString().toLowerCase();

                if(value.length > 0){

                    var r1 = /\.00$/;
                    var r2 = /\.0$/;

                    if(r1.test(value)){
                        value = value.replace(/\.00$/g,"");
                    }

                    if(r2.test(value)){
                        value = value.replace(/\.0$/g,"");
                    }

                }

                // Value always loweCase.
                if (id != 'font-family' && id != 'background-image' && id != 'list-style-image' && id != 'animation-name' && id != 'animation-play' && id != 'filter' && id != '-webkit-filter' && id != '-webkit-transform') {
                    value = value.toString().toLowerCase();
                }

                // Stop
                if(value == 'nan' || value == 'NaN'){
                    return false;
                }

                if(id == 'a'){
                    return false;
                }

                // Updating selector
                selector = prepareSelector(selector, id);

                // Checks min height and min width and update.
                if(id == 'height' || id == 'width'){

                    // minValue & minFormat
                    var minVal = number_filter($("#min-"+id+"-value").val());
                    var minFormat = $("#min-"+id+"-after").val();

                    // if height is smaller than min-height, so update min height
                    if(parseFloat(value) < parseFloat(minVal) && prefix == minFormat){

                        // Insert min-height
                        insert_rule(selector,'min-'+id,value,prefix,size);

                        // Set default values
                        setTimeout(function(){
                            $.each(['min-'+id], function(i, v) {
                                set_default_value(v);
                            });
                        },50);

                    }

                }


                // Dont add if thi is animation rule.
                if (is_animate_creator() === true && id.indexOf('animation') != -1) {
                    return false;
                }


                if (id == 'background-color' || id == 'color' || id == 'border-color' || id == 'border-left-color' || id == 'border-right-color' || id == 'border-top-color' || id == 'border-bottom-color') {

                    var valueCheck = $.trim(value).replace("#", '');

                    if (valueCheck == 'red') {
                        value = '#FF0000';
                    } else if (valueCheck == 'white') {
                        value = '#FFFFFF';
                    } else if (valueCheck == 'blue') {
                        value = '#0000FF';
                    } else if (valueCheck == 'orange') {
                        value = '#FFA500';
                    } else if (valueCheck == 'green') {
                        value = '#008000';
                    } else if (valueCheck == 'purple') {
                        value = '#800080';
                    } else if (valueCheck == 'pink') {
                        value = '#FFC0CB';
                    } else if (valueCheck == 'black') {
                        value = '#000000';
                    } else if (valueCheck == 'brown') {
                        value = '#A52A2A';
                    } else if (valueCheck == 'yellow') {
                        value = '#FFFF00';
                    } else if (valueCheck == 'gray') {
                        value = '#808080';
                    }

                }

                // Set defaults
                if(id == 'border-width'){

                    // Set default values
                    $.each(['border-top-width','border-left-width','border-right-width','border-bottom-width'], function(i, v) {
                        set_default_value(v);
                    });

                }

                if(id == 'border-color'){

                    // Set default values
                    $.each(['border-top-color','border-left-color','border-right-color','border-bottom-color'], function(i, v) {
                        set_default_value(v);
                    });

                }

                if(id == 'border-style'){

                    // Set default values
                    $.each(['border-top-style','border-left-style','border-right-style','border-bottom-style'], function(i, v) {
                        set_default_value(v);
                    });

                }

                // When border-x-style change
                if(id.indexOf("border-") != -1 && id.indexOf("-style") != -1 && id != 'border-style'){

                    // update default value for;
                    set_default_value("border-style");

                }

                // When border-x-style change
                if(id.indexOf("border-") != -1 && id.indexOf("-color") != -1 && id != 'border-color'){

                    // update default value for;
                    set_default_value("border-color");

                }

                // When border-x-style change
                if(id.indexOf("border-") != -1 && id.indexOf("-width") != -1 && id != 'border-width'){

                    // update default value for;
                    set_default_value("border-width");

                }


                // also using in bottom.
                var duration,delay;

                // update multiple duration and delay by animation name
                if(id == 'animation-name' && $(".wyp-animate-manager-active").length === 0){

                    var animCount = 1;

                    if(value == 'none' || value == 'disable'){
                        animCount = 0;
                    }

                    if(value.indexOf(",") != -1){
                        animCount = value.split(",").length;
                    }

                    // DURATION
                    var singleDuration = get_selected_element().css("animationDuration");
                    var singleDurationAr = singleDuration.split(",");
                    var durationCount = 1;

                    if(singleDuration.indexOf(",") != -1){
                        durationCount = singleDurationAr.length;
                    }

                    if(durationCount != animCount){

                        singleDuration = [];
                        for(var i = 0; i < animCount; i++){
                            singleDuration.push(singleDurationAr[i]);
                        }

                        singleDuration = singleDuration.toString().replace(/\s+/g,'');

                        if(animCount <= 1){$("#animation-duration-group").removeClass("hidden-option");}
                        insert_rule(null, "animation-duration", singleDuration, '');

                    }

                    // DELAY
                    var singleDelay = get_selected_element().css("animationDelay");
                    var singleDelayAr = singleDelay.split(",");
                    var delayCount = 1;

                    if(singleDelay.indexOf(",") != -1){
                        delayCount = singleDelayAr.length;
                    }

                    if(delayCount != animCount){

                        singleDelay = [];
                        for(i = 0; i < animCount; i++){
                            singleDelay.push(singleDelayAr[i]);
                        }

                        singleDelay = singleDelay.toString().replace(/\s+/g,'');

                        if(animCount <= 1){$("#animation-iteration-count-group").removeClass("hidden-option");}
                        insert_rule(null, "animation-delay", singleDelay, '');

                    }

                }



                // Animation name play.
                if (id == 'animation-name' || id == 'animation-play' || id == 'animation-iteration' || id == 'animation-duration') {

                    if($(".wyp-animate-manager-active").length === 0 && value != 'none' && value != 'disable'){

                        duration = get_selected_element().css("animationDuration");
                        delay = get_selected_element().css("animationDelay");

                        // Getting right time delay if have multiple animations.
                        var newDelay = get_multiple_delay(duration,delay);

                        if(newDelay !== false){
                            delay = parseFloat(newDelay);
                        }else if(isUndefined(delay)){
                            delay = 0;
                        }else{
                            delay = parseFloat(duration_ms(delay)); // delay
                        }

                        if (isUndefined(duration)) {
                            duration = 1000;
                        } else {
                            duration = parseFloat(duration_ms(duration)); // duration
                        }

                        var waitDelay = delay + duration;

                        if(waitDelay === 0){
                            waitDelay = 1000;
                        }

                        waitDelay = waitDelay + 100;

                        // Add class.
                        body.addClass("wyp-hide-borders-now wyp-force-hide-select-ui");

                        clear_animation_timer();

                        window.animationTimer1 = setTimeout(function(){

                            // remove class.
                            body.removeClass("wyp-hide-borders-now wyp-force-hide-select-ui");

                            element_animation_end();

                            // Update.
                            draw();

                        }, waitDelay);

                    }

                }

                // Background image fix.
                if (id == 'background-image' && value != 'disable' && value != 'none' && value != '') {

                    // If not has a url
                    if (value.replace(/\s/g, "") == 'url()') {
                        value = 'disable';
                    }

                    // disable if not a gradient or non valid URL
                    if(value.indexOf("//") == -1 && value.indexOf("linear-gradient(") == -1){
                        value = 'disable';
                    }

                }

                // List Style image fix.
                if (id == 'list-style-image' && value != 'disable' && value != 'none' && value != '') {

                    // If not has a url
                    if (value.replace(/\s/g, "") == 'url()') {
                        value = 'disable';
                    }

                    // disable if URL is not valid
                    if(value.indexOf("//") == -1){
                        value = 'disable';
                    }

                }

                // adding automatic relative.
                if (id == 'top' || id == 'bottom' || id == 'left' || id == 'right') {

                    if(value != 'disable'){
                        setTimeout(function() {
                            if ($("#position-static").parent().hasClass("active") || $("#position-relative").parent().hasClass("active")){
                                $("#position-relative").trigger("click");
                            }
                        }, 5);
                    }

                }

                // Background color
                if (id == 'background-color') {
                    if ($("#wyp-background-image").val() != 'none' && $("#wyp-background-image").val() != '') {
                        force_insert_rule(selector, id, value, prefix, size);

                        return false;
                    }
                }

                // Responsive Settings
                var mediaBefore = create_media_query_before(css);

                if(mainBody.hasClass("wyp-adding-auto-media")){
                    size = space_cleaner(mediaBefore.replace("@media","").replace("{",""));
                }

                var mediaAfter = create_media_query_after();

                if(isDefined(size) && is_animation_manager() && is_responsive_mod()){
                    mediaBefore = "@media " + size + "{";
                }

                // No scale-transform etc.
                if(get_css_id(id)[1] == null){

                    // Delete same data.
                    var exStyle = the_editor_data().find("." + get_id(selector) + '-' + id + '-style[data-size-mode="' + size + '"]');
                    if (exStyle.length > 0){
                        if (esc_data_value(exStyle.html()) == value) {
                            return false;
                        } else {
                            exStyle.remove(); // else remove.
                        }
                    }

                    // Delete same data. WEBKIT
                    var exStyle = the_editor_data().find("." + get_id(selector) + '--webkit-' + id + '-style[data-size-mode="' + size + '"]');
                    if (exStyle.length > 0){
                        if (esc_data_value(exStyle.html()) == value) {
                            return false;
                        } else {
                            exStyle.remove(); // else remove.
                        }
                    }

                    // Delete same data. (difference selector, because sometimes plugin use long selector for force important)
                    the_editor_data().find('[data-rule="' + id + '"][data-size-mode="' + size + '"]').each(function(){

                        // Get CSS
                        var data = $(this).html();

                        // Get selector
                        var thisSelectorX = esc_data_selector(data);

                        // if make same thing with current selector
                        if(comparison_selector(selector, thisSelectorX)){

                            // delete styles
                            $(this).remove();
                            return false;

                        }

                    });

                    // Delete same data. (difference selector, because sometimes plugin use long selector for force important) WEBKIT
                    the_editor_data().find('[data-rule="-webkit-' + id + '"][data-size-mode="' + size + '"]').each(function(){

                        // Get CSS
                        var data = $(this).html();

                        // Get selector
                        var thisSelectorX = esc_data_selector(data);

                        // if make same thing with current selector
                        if(comparison_selector(selector, thisSelectorX)){

                            // delete styles
                            $(this).remove();
                            return false;

                        }

                    });

                    // Delete same data for anim.
                    if (is_animate_creator()) {

                        exStyle = iframe.find(".wyp-anim-scenes ." + $('body').attr('data-anim-scene') + " .scenes-" + get_id(id) + "-style");

                        if (exStyle.length > 0) {

                            if (esc_data_value(exStyle.html()) == value) {

                                return false;

                            } else {

                                var currentSceneNum = parseInt($('body').attr('data-anim-scene').replace(/scene-/g,''))+1;

                                exStyle.remove(); // else remove.

                                // delete same style from the next scenes which not added yet.
                                for(var n = currentSceneNum; n < 7; n++) {

                                    // if scene not added yet. make it.
                                    if($(".anim-bar .scenes .scene-" + n).length == 0 || $(".anim-bar .scenes .scene-" + n).hasClass("scene-no-click-yet") == true){

                                        // if next scenes not added yet, so remove it too.
                                        iframe.find(".wyp-anim-scenes .scene-" + n + " .scenes-" + get_id(id) + "-style").remove();

                                    }

                                }

                            }

                        }

                    }

                }

                // Filter
                if (id == 'filter' || id == 'transform') {

                    if (value != 'disable' && value != '' && value != 'undefined' && value !== null) {
                        insert_rule(selector, "-webkit-" + id, value, prefix, size);
                    }

                }

                // No px em etc for this options.
                if (id == 'z-index' || id == 'opacity' || id == 'blur-filter' || id == 'grayscale-filter' || id == 'brightness-filter' || id == 'contrast-filter' || id == 'hue-rotate-filter' || id == 'saturate-filter' || id == 'sepia-filter' || id.indexOf("-transform") != -1) {
                    if (id != 'text-transform' && id != '-webkit-transform') {
                        value = number_filter(value);
                        prefix = '';
                    }
                }

                // Filter Default options.
                if (id == 'blur-filter' || id == 'grayscale-filter' || id == 'brightness-filter' || id == 'contrast-filter' || id == 'hue-rotate-filter' || id == 'saturate-filter' || id == 'sepia-filter') {

                    var filterData = filter_generator(true);

                    insert_rule(selector, 'filter', filterData, '', size);

                    return false;

                }
                // Filter options end

                // Transform Settings
                if (id.indexOf("-transform") != -1 && id != 'text-transform' && id != '-webkit-transform') {

                    body.addClass("wyp-has-transform");

                    var translateData = transform_generator(true);

                    insert_rule(selector, 'transform', translateData, '', size);

                    if(translateData == 'none' || translateData == 'disable'){
                        body.removeClass("wyp-has-transform");
                    }

                    return false;

                }
                // Transform options end


                // border-type is not a CSS Rule.
                if(id == 'border-type'){
                    return false;
                }


                // Box Shadow
                if (id == 'box-shadow-inset' || id == 'box-shadow-color' || id == 'box-shadow-vertical' || id == 'box-shadow-blur-radius' || id == 'box-shadow-spread' || id == 'box-shadow-horizontal') {

                    var shadowData = box_shadow_generator(selector);

                    insert_rule(selector, 'box-shadow', shadowData, '', size);

                    return false;

                }
                // Box shadow options end


                // Animation options
                if (id == 'animation-play') {

                    var selectorTest;

                    the_editor_data().find("[data-style][data-size-mode='"+size+"']").each(function(){

                        // clean up
                        selectorTest = selector.replace(/\.(yp_onscreen|yp_hover|yp_click|yp_focus)/g, '');

                        // onscreen
                        if ($(this).data("style") == get_id(selectorTest + ".yp_onscreen")) {
                            $(this).remove();
                        }

                        // hover
                        if ($(this).data("style") == get_id(selectorTest + ".yp_hover")) {
                            $(this).remove();
                        }

                        // click
                        if ($(this).data("style") == get_id(selectorTest + ".yp_click")) {
                            $(this).remove();
                        }

                        // click
                        if ($(this).data("style") == get_id(selectorTest + ".yp_focus")) {
                            $(this).remove();
                        }

                    });

                    insert_rule(selector, 'animation-name', $("#wyp-animation-name").val(), prefix, size);

                    return false;

                }

                // Animation name
                if (id == 'animation-name'){
                    animationName_if(selector, id, value, prefix, size);
                }


                // Checking.
                if (value == 'disable' || value == '' || value == 'undefined' || value === null) {
                    $("#" + id + "-group").removeClass("reset-enable");
                    return false;
                }

                // New Value
                var current = value + prefix;

                // Clean.
                current = current.replace(/\s+?!important/g,'').replace(/\;$/g,'');

                // Append default value.
                if (get_id(selector) != '') {

                    var dpt = ':';

                    if (is_animate_creator() === true && id != 'position') {

                        iframe.find(".wyp-anim-scenes ." + get_id(body.attr("data-anim-scene") + css)).remove();

                        iframe.find(".wyp-anim-scenes ." + body.attr("data-anim-scene") + "").append('<style data-rule="' + css + '" class="style-' + body.attr("data-anim-scene") + ' scenes-' + get_id(css) + '-style">' + selector + '{' + css + dpt + current + '}</style>');

                        // update next scenes
                        var scene = 0;
                        var styleEl;
                        var selectorT = selector;
                        for(var n = parseInt(body.attr("data-anim-scene").replace("scene-",""))+1; n <= 6; n++){

                            // current scene
                            scene = "scene-"+n;

                            // get style if have
                            styleEl = iframe.find(".wyp-anim-scenes ." + scene + " .scenes-" + get_id(css) + "-style");

                            // If not has this style or has but this generated by same method
                            if(styleEl.length == 0 || styleEl.hasClass("dynamic-generated-scene")){

                                selectorT = selector.replace(/body\.wyp-scene-[0-9]/g, 'body.wyp-scene-' + n);

                                // delete old
                                iframe.find(".wyp-anim-scenes ." + scene + " style[data-rule='"+css+"']").remove();

                                // Append
                                iframe.find(".wyp-anim-scenes ." + scene + "").append('<style data-rule="' + css + '" class="dynamic-generated-scene style-' + scene + ' scenes-' + get_id(css) + '-style">' + selectorT + '{' + css + dpt + current + '}</style>');

                            }

                        }

                    } else {

                        the_editor_data().append('<style data-rule="' + css + '" data-size-mode="' + size + '" data-style="' + get_id(selector) + '" class="' + get_id(selector) + '-' + id + '-style wyp_current_styles">' + mediaBefore + '' + '' + selector + '{' + css + dpt + current + '}' + '' + mediaAfter + '</style>');

                        resort_style_data_positions();

                    }

                    draw();

                }

                // No need to important for perspective.
                if (id == 'perspective') {
                    return false;
                }

                // No need to important on keyframes
                if(mainBody.hasClass("wyp-anim-creator")){
                    return false;
                }

                // Don't use important if animation manager active
                if(mainBody.hasClass("wyp-animate-manager-mode")){
                    return false;
                }

                // Generate the most strong selector
                window.minCroppedSelector = 12;
                var betterSelector = get_parents(iframe.find(".wyp-content-selected .wyp-selected"), window.lastParentQueryStatus);
                window.minCroppedSelector = false;

                // Check if need to important tag and apply if need.
                check_force_rule2(
                    css, current, betterSelector, // For checks
                    [mediaBefore,css,dpt,current,mediaAfter], // Important Append Test
                    [selector,id,value,prefix,size], // for insert_important_rule
                    [false] // for remove old insert_important_rule
                );

            }


            /* ---------------------------------------------------- */
            /* Updating the selector as need                        */
            /* ---------------------------------------------------- */
            function prepareSelector(selector, id){


                // Animate Generator selector.
                if (is_animate_creator() === true && id != 'position') {

                    // Delete if has
                    selector = $.trim(selector.replace(/(body)?\.wyp-scene-[0-9]/g, ''));

                    // Add
                    selector = add_class_to_body(selector, "wyp-" + body.attr("data-anim-scene"));

                }


                // Set perspective id to parent
                if(id == 'perspective'){

                    if(get_selected_element().parents().length > 0){

                        // Cache current
                        var oldSelector = selector;

                        // clean cache
                        body.removeAttr("data-clickable-select");

                        // Update selector var
                        selector = $.trim(get_parents(get_selected_element().parent(), "default"));

                        // set old as cache again
                        body.attr("data-clickable-select",oldSelector);

                    }

                }


                // Updating Selector for animation properties on Animation Manager
                if (mainBody.hasClass("process-by-code-editor") === false && $(".wyp-animate-manager-active").length === 0) {

                    // Animation Properties
                    if (id == 'animation-name' || id == 'animation-duration' || id == 'animation-delay') {

                        // Clean classes
                        selector = selector.replace(/\.yp_onscreen/g, '').replace(/\.yp_hover/g, '').replace(/\.yp_focus/g, '').replace(/\.yp_click/g, '');

                        // Play class
                        var play = '';
                        play = "." + $("#wyp-animation-play").val();

                        // Getting array
                        var selectorNew = selector.split(":");

                        // Check if there have :
                        if(selectorNew.length > 0){

                            // Getting all prev selectors until last :
                            var prevSelectors = '';

                            // getting previous selectors explude last : part.
                            for(var y = 0; y < selectorNew.length-1; y++){
                                prevSelectors = prevSelectors + selectorNew[y];
                            }

                            // hover, focus same. No need to class.
                            if (selectorNew[selectorNew.length-1] == 'hover' || selectorNew[selectorNew.length-1] == 'focus') {
                                selector = prevSelectors + play + ":" + selectorNew[selectorNew.length-1];
                            }else{ // Add yp_hover class
                                selector = selector + play;
                            }

                        // default, adds only.
                        }else{
                            selector = selector + play;
                        }

                    }


                }


                // Hover, Focus, Active etc Selections.
                var selection = $('body').attr('data-wyp-selector');

                // Has Selection
                if (!isUndefined(selection)) {

                    // be sure selector not has yp_onscreen etc
                    if(/(yp_onscreen|yp_click|yp_focus|yp_hover)/g.test(selector) == false && id != 'animation-play' && id != 'animation-fill-mode'){

                        // Clean
                        selector = selector.replace(/\.wyp-selected(.*?){/g,'{');
                        selector = selector.replace(/(body)?\.yp-selector-(hover|focus|active|visited|link)/g,'');
                        selector = selector.replace(/:(hover|focus|active|visited|link)(\s+)?$/g,"");

                        // hover, focus etc
                        var selectionX = selection.replace(':', '');

                        // Default
                        var selector0 = selector + ":" + selectionX;

                        // Add to element
                        selector = add_class_to_body(selector, 'yp-selector-' + selectionX);

                        // Add wyp-selected to last of selector.
                        var selector1 = selector.replace('body.yp-selector-' + selectionX + ' body.yp-selector-' + selectionX + ' ', 'body.yp-selector-' + selectionX + ' ') + ".wyp-selected";

                        var selector2 = selector.replace('body.yp-selector-' + selectionX + ' body.yp-selector-' + selectionX + ' ', 'body.yp-selector-' + selectionX + ' ') + ".wyp-selected-others";

                        var selector3 = selector.replace('body.yp-selector-' + selectionX + ' body.yp-selector-' + selectionX + ' ', 'body.yp-selector-' + selectionX + ' ') + ".wyp-selected:"+selectionX;

                        var selector4 = selector.replace('body.yp-selector-' + selectionX + ' body.yp-selector-' + selectionX + ' ', 'body.yp-selector-' + selectionX + ' ') + ".wyp-selected-others:"+selectionX;

                        var selector5 = selector.replace('body.yp-selector-' + selectionX + ' body.yp-selector-' + selectionX + ' ', 'body.yp-selector-' + selectionX + ' ') + ".wyp-selected-parents";

                        var selector6 = selector.replace('body.yp-selector-' + selectionX + ' body.yp-selector-' + selectionX + ' ', 'body.yp-selector-' + selectionX + ' ') + ".wyp-selected-parents:"+selectionX;

                        // Selector
                        selector = selector1 + "," + selector2 + "," + selector3 + "," + selector4 + "," + selector5 + "," + selector6 + "," + selector0;

                    }

                }

                return selector;

            }


            /* ---------------------------------------------------- */
            /* Checks if CSS worked or not                          */
            /* ---------------------------------------------------- */
            function check_force_rule2(css, current, betterSelector, appendData, importantData, removeData){

                // to disable css codes from frame.css
                // It will disable some cursor and transition styles which appled to target element.
                body.addClass("wyp-important-check");

                // To fix a bug in Firefox
                var delayMode = false;

                // Cursor and transition affected by frame.css, so we need to wait on Firefox
                if(css == 'cursor' || css == 'transition'){
                    delayMode = true;
                }

                // Has a bug with display none in Firefox, so we wait
                if(css == 'display' && current == 'none'){
                    delayMode = true;
                }

                // don't wait if not Firefox
                if(navigator.userAgent.toLowerCase().indexOf('firefox') == -1 && delayMode == true){
                    delayMode = false;
                }

                // display, cursor, transition need delay on Firefox.
                if(delayMode){

                    // Find selected element and check if need to use important.
                    setTimeout(function(){
                        check_2_force_inner(css, current, betterSelector, appendData, importantData, removeData);
                    }, 5);

                }else{ // no delay

                    // Find selected element and check if need to use important.
                    check_2_force_inner(css, current, betterSelector, appendData, importantData, removeData);

                }

            }


            /* ---------------------------------------------------- */
            /* Checks if CSS worked or not: Part 2                  */
            /* ---------------------------------------------------- */
            function check_2_force_inner(css, current, betterSelector, appendData, importantData, removeData){

                // Convert for check important need.
                if(css == 'border-width'){
                    css = 'border-top-width';
                }else if(css == 'border-style'){
                    css = 'border-top-style';
                }else if(css == 'border-color'){
                    css = 'border-top-color';
                }

                // Defaults
                var computedValue;

                var element = get_selected_element();
                    var index = 0;

                    // Getting computed value "without important" tag.
                    // if triggered from insert_rule:
                    if(!removeData[0]){
                        computedValue = element.css(css);
                        window.computedValueWrong = computedValue;
                    }else{
                        // else triggered from force_insert_rule.
                        //window.computedValueWrong getting old computed value that we want change.

                        // if undefined
                        if(isUndefined(window.computedValueWrong)){
                            computedValue = element.css(css);
                        }else{
                            computedValue = window.computedValueWrong;
                        }

                    }

                    // If not same, try important
                    if(current != computedValue){

                        // append with important tag and most strong selector.
                        iframeBody.append('<style class="wyp-important-debug-' + index + '">' + appendData[0] + '' + '' + betterSelector + '{' + appendData[1] + appendData[2] + appendData[3] + '!important;}' + '' + appendData[4] + '</style>');

                        // Sleep.
                        setTimeout(function(){

                            // Get new computed value
                            var computedValueImportant = element.css(css);

                            // delete it
                            iframeBody.find(".wyp-important-debug-" + index).remove();

                            // compress computed values with important and no important. (if trigged from insert_rule func)
                            if(computedValueImportant != computedValue && removeData[0] == false){

                                // When not same, i.e when work; insert important
                                // rule with more strong selector.

                                // Use important.
                                force_insert_rule(importantData[0],importantData[1],importantData[2],importantData[3],importantData[4]);

                                // Disable important check, used for disable some styles in frame.css
                                body.removeClass("wyp-important-check");

                                return false;

                            }

                            // if trigged from force_insert_rule func
                            if(removeData[0]){

                                // Delete old
                                the_editor_data().find("." + get_id(removeData[1]) + '-' + removeData[2] + '-style[data-size-mode="' + removeData[3] + '"]').remove();

                                // if it work, not call this function again from force_insert_rule.
                                if(computedValue != computedValueImportant){
                                    window.stopForceInsert = true;
                                }

                                // Use important.
                                force_insert_rule(importantData[0],importantData[1],importantData[2],importantData[3],importantData[4]);

                                // return to default.
                                window.stopForceInsert = false;

                            }

                        }, window.YellowDelay);

                    }

                // Disable important check, used for disable some styles in frame.css
                body.removeClass("wyp-important-check");

            }


            /* ---------------------------------------------------- */
            /* Getting media queries from editor data simply        */
            /* ---------------------------------------------------- */
            function get_media_queries2(){

                // Defaults
                var queries = [], element, size;

                // Each data styles
                the_editor_data().find("style").each(function(){

                    // Element
                    element = $(this);

                    // Getting size
                    size = element.attr("data-size-mode");

                    // Getting queries
                    queries.push(size);

                });

                return array_cleaner(queries);

            }


            /* ---------------------------------------------------- */
            /* Adding Class to parents for hover, focus etc         */
            /* ---------------------------------------------------- */
            function add_class_to_parents(){

                // Get selection: hover, active etc.
                var selection = body.attr("data-wyp-selector");

                // IF selection avaiable
                if(!isUndefined(selection)){

                    // each all parents
                    get_selected_element().parentsUntil("body").each(function(){

                        // Add class
                        $(this).addClass("wyp-selected-parents");

                    });

                }

            }


            iframe.on("mouseover", ".wyp-selected-boxed-top,.wyp-selected-boxed-right,.wyp-selected-boxed-bottom,.wyp-selected-boxed-left,.wyp-selected-boxed-padding-top,.wyp-selected-boxed-padding-right,.wyp-selected-boxed-padding-bottom,.wyp-selected-boxed-padding-left,.wyp-selected-boxed-margin-top,.wyp-selected-boxed-margin-right,.wyp-selected-boxed-margin-bottom,.wyp-selected-boxed-margin-left,.wyp-selected-tooltip,.wyp-edit-tooltip", function(){

                // Selection
                var selection = body.attr("data-wyp-selector");

                // If Undefined
                if(isUndefined(selection)){

                    body.addClass("yp-selector-hover wyp-fake-hover");
                    body.attr("data-wyp-selector", ":hover");

                    add_class_to_parents();

                }

            });

            iframe.on("mouseleave", ".wyp-selected-boxed-top,.wyp-selected-boxed-right,.wyp-selected-boxed-bottom,.wyp-selected-boxed-left,.wyp-selected-boxed-padding-top,.wyp-selected-boxed-padding-right,.wyp-selected-boxed-padding-bottom,.wyp-selected-boxed-padding-left,.wyp-selected-boxed-margin-top,.wyp-selected-boxed-margin-right,.wyp-selected-boxed-margin-bottom,.wyp-selected-boxed-margin-left,.wyp-selected-tooltip,.wyp-edit-tooltip", function(){

                if(body.hasClass("wyp-fake-hover")){
                    body.removeClass("yp-selector-hover wyp-fake-hover");
                    body.removeAttr("data-wyp-selector");
                }

            });



            /* ---------------------------------------------------- */
            /* convert all styles to a obj                          */
            /* ---------------------------------------------------- */
            function get_object_data(){

                var queries = get_media_queries2(), styles, style, styleData, selector, rule, value, data = {};

                $.each(queries, function(index, size){

                    styles = the_editor_data().find("[data-size-mode='"+size+"']");

                    // Define
                    if(isUndefined(data[size])){
                        data[size] = {};
                    }

                    styles.each(function(){

                        style = $(this);

                        styleData = style.html();

                        selector = esc_data_selector(styleData);
                        rule = style.attr("data-rule");
                        value = esc_data_value(styleData);

                        // Define
                        if(typeof data[size][selector] != 'object'){
                            data[size][selector] = {rules: [], values: []};
                        }

                        data[size][selector]['rules'].push(rule);
                        data[size][selector]['values'].push(value);

                    });

                });

                return data;

            }


            /* ---------------------------------------------------- */
            /* Generating CSS Manager data in HTML                  */
            /* ---------------------------------------------------- */
            function get_css_manager_layout(){

                // clean selection
                clean();

                // Backup data
                window.backupData = the_editor_data().html();

                // Getting data
                var data = get_object_data();

                var l18_reset_text = "Select the properties you want to delete in the current stylesheet or manage the stylesheets from <a href='"+window.source_page_link+"' target='_blank'>this page</a>.";

                var emptyStyleSheetClass = '';

                // text
                if($.isEmptyObject(data)){

                    var l18_reset_text = "there are no edits applied to this stylesheet, check other stylesheets from <a href='"+window.source_page_link+"' target='_blank'>this page</a>.";

                    emptyStyleSheetClass = ' css-manager-empty-notice';

                }

                // output HTML.
                var output = '';

                // information
                output += '<p class="css-manager-paragraf'+emptyStyleSheetClass+'">'+l18_reset_text+'</p>';

                // stop if empty.
                if($.isEmptyObject(data)){

                    output += '<div class="wyp-reset-actions"><button class="confirm" style="background-color:rgb(128, 162, 86);">OK</button></div>';

                    return output;
                }

                // Manager section
                output += '<div class="wyp-manage-css-section">';

                var m = 0;
                var s = 0;

                // Each sizes
                for(var size in data) {

                    m = m + 1;

                    var sizeView = size;
                    if(size == 'desktop'){sizeView = 'all';}

                    if(size == 'desktop'){
                        var sizeTooltip = 'All screen sizes';
                    }else{
                        var sizeTooltip = process_media_query(sizeView);
                    }

                    // If multiple.
                    if(Array.isArray(sizeTooltip)){

                        if(sizeTooltip.length == 2){

                            if(sizeTooltip[0].replace(/\d+/g,'') == sizeTooltip[1].replace(/\d+/g,'')){

                                if(sizeTooltip[0].replace(/\d+/g,'') == '<'){

                                    sizeTooltip = sizeTooltip[0].replace(/\D+/g,'') + 'px and below screen sizes.';

                                }else{

                                    sizeTooltip = sizeTooltip[1].replace(/\D+/g,'') + 'px and above screen sizes.';

                                }

                            }else if(sizeTooltip[0].replace(/\d+/g,'') == '>' && sizeTooltip[1].replace(/\d+/g,'') == '<'){

                                 sizeTooltip = sizeTooltip[0].replace(/\D+/g,'') + 'px and ' + sizeTooltip[1].replace(/\D+/g,'') + 'px between screen sizes.';

                            }

                        }

                        if(Array.isArray(sizeTooltip) && sizeTooltip.length == 1){

                            if(sizeTooltip[0].replace(/\d+/g,'') == '<'){

                                sizeTooltip = sizeTooltip[0].replace(/\D+/g,'') + 'px and below screen sizes.';

                            }else{

                                sizeTooltip = sizeTooltip[0].replace(/\D+/g,'') + 'px and above screen sizes.';

                            }

                        }

                        if(sizeTooltip.indexOf("<") != -1){sizeTooltip = '';}

                    }

                    // Media heading
                    output += '<div class="css-manager-media-heading css-manager-heading"><label class="css-manager-label" for="media-checkbox-'+m+'"></label><input title="Disable all styles in this media query" class="css-manager-media-checkbox" type="checkbox" id="media-checkbox-'+m+'"><h3 data-toggle="tooltipCSSManagerLight" data-placement="right" title="' + sizeTooltip + '">@media ' + sizeView + '</h3><span class="css-manager-toggle"><span class="dashicons dashicons-arrow-up"></span></span></div>';

                     // Media group start
                    output += '<div class="css-manager-media-group css-manager-group">';

                    // Selectors
                    for(var selector in data[size]) {

                        s = s + 1;

                        // selector heading
                        output += '<div class="css-manager-selector-heading css-manager-heading"><label class="css-manager-label" for="selector-checkbox-'+m+'-'+s+'"></label><input title="Disable all styles applied with this selector" id="selector-checkbox-'+m+'-'+s+'" class="css-manager-selector-checkbox" type="checkbox"><h3 data-toggle="tooltipCSSManager" data-placement="right" title="' + selector + '">' + get_all_selector_information(selector) + '</h3><span class="css-manager-toggle"><span class="dashicons dashicons-arrow-down"></span></span></div>';

                        // Selector group start
                        output += '<div class="css-manager-selector-group css-manager-group" style="display:none;">';

                        // Getting rules and values
                        var rules = data[size][selector]['rules'];
                        var values = data[size][selector]['values'];

                        // CSS
                        for(var i = 0; i < rules.length; i++) {

                            output += '<div class="css-manager-line-group" data-css-manager-size="'+size+'" data-css-manager-selector="'+selector+'" data-css-manager-rule="'+rules[i]+'" data-css-manager-value="'+values[i]+';"><label class="css-manager-label" for="css-checkbox-'+m+'-'+s+'-'+i+'"></label><input title="Disable the property" class="css-manager-checkbox" type="checkbox" id="css-checkbox-'+m+'-'+s+'-'+i+'">' + rules[i] + ': <input class="css-manager-value-input" type="text" value="' + values[i] + ';" /></div>';

                        }

                        // selector group stop
                        output += '</div>';

                    }

                    // Media group stop
                    output += '</div>';

                }

                // Manager section
                output += '</div>';

                if(!$.isEmptyObject(data)){
                        output += '<div class="wyp-reset-actions"><button class="empty-stylesheet-btn">Empty Stylesheet</button><div class="reset-actions"><button class="cancel">Cancel</button><button class="confirm" style="background-color:rgb(128, 162, 86);">Apply Changes</button></div></div>';
                    }

                setTimeout(function(){

                    $(".css-manager-value-input").each(function(){
                        input_auto_width(this);
                    });

                    // CSS manager Tooltip
                    $('[data-toggle="tooltipCSSManager"]').tooltip({
                        animation: false,
                        container: ".css-manager-selector-heading",
                        html: true
                    });

                    // CSS manager Tooltip
                    $('[data-toggle="tooltipCSSManagerLight"]').tooltip({
                        animation: false,
                        container: ".css-manager-media-heading",
                        html: true
                    });

                    if($(".css-manager-media-heading").length == 1){
                        $(".css-manager-media-heading").trigger("click");
                    }

                    // Scrollbar
                    $('.wyp-manage-css-section').perfectScrollbar();

                    $(".wyp-reset-popup").css("margin-top",parseFloat($(".wyp-reset-popup").css("margin-top"))-parseFloat($(".wyp-manage-css-section").height()/2));

                }, 4);

                return output;

            }


            /* ---------------------------------------------------- */
            /* Getting all selector information                     */
            /* ---------------------------------------------------- */
            function get_all_selector_information(selector){

                var selector2 = get_foundable_query(selector, true, true, true);

                var len = iframe.find(selector2).length;

                if(selector2.indexOf(",") != -1 && len < 2){
                    len = 0;
                }

                var addText = '';

                if(len == 0){

                    var basicText = selector2.replace(/(\.|\#)/g,"").replace(/(\-|\_)/g," ");

                    basicText = basicText.toLowerCase();
                    basicText = basicText.replace(/\bli\b/g,"list item");
                    basicText = basicText.replace(/(\bul\b|\bol\b)/g,"list");
                    basicText = basicText.replace(/\ba\b/g,"link");
                    basicText = basicText.replace(/\bp\b/g,"paragraph");
                    basicText = basicText.replace(/(\bh1\b|\bh2\b|\bh3\b|\bh4\b|\bh5\b|\bh6\b)/g,"paragraph");
                    basicText = basicText.replace(/(\s+)?\,(\s+)?/g, ' and ');
                    basicText = basicText.replace(/:nth-child\(\d+\)/g, '');

                    if(/(yp-selector-hover|:hover|yp_hover)/g.test(selector)){
                        addText = 'hover';
                    }else if(/(yp-selector-focus|:focus|yp_focus)/g.test(selector)){
                        addText = 'focus';
                    }else if(/(yp-selector-active|:active)/g.test(selector)){
                        addText = 'active';
                    } else if(/(yp-selector-visited|:visited)/g.test(selector)){
                        addText = 'visited';
                    }else if(/(yp-selector-link|:link)/g.test(selector)){
                        addText = 'link';
                    }

                    if(/(yp_onscreen)/g.test(selector)){
                        addText = 'visible';
                    }else if(/(yp_click)/g.test(selector)){
                        addText = 'click';
                    }

                    if(addText != ''){
                        addText = " <strong>[" + addText + "]</strong>";
                    }

                    return space_cleaner(clean_duplicate_words(basicText) + addText);

                }else{ // if multiple selector. Ex: selector1,select2

                    var selectorArray = get_selector_array(selector2);

                    var text = '';

                    for(var i = 0; i < selectorArray.length; i++){
                        text += get_tag_information(selectorArray[i]) + " ";
                    }

                    text = text.toLowerCase();
                    text = text.replace(/header\s+\(level\s+\d+\)/g, 'Heading');
                    text = text.replace(/\bspan\b|\bcontainer\b|\bwrapper\b|\brow\b|\bcolumn\b/g, '');
                    text = text.replace(/\blink\b/g, 'link');
                    text = text.replace(/\bunorganized list\b/g, 'list');

                    // replace with AND multiple selectors.
                    text = text.replace(/(\s+)?\,(\s+)?/g, ' and ');


                    text = space_cleaner(clean_duplicate_words(text));

                    // if more and last char is alpha, adds s.
                    if(len > 1 && /[a-z]/i.test(text.substr(text.length - 1)) == true){
                        text += "s";
                    }

                    if(/(yp-selector-hover|:hover|yp_hover)/g.test(selector)){
                        addText = 'hover';
                    }else if(/(yp-selector-focus|:focus|yp_focus)/g.test(selector)){
                        addText = 'focus';
                    }else if(/(yp-selector-active|:active)/g.test(selector)){
                        addText = 'active';
                    } else if(/(yp-selector-visited|:visited)/g.test(selector)){
                        addText = 'visited';
                    }else if(/(yp-selector-link|:link)/g.test(selector)){
                        addText = 'link';
                    }

                    if(/(yp_onscreen)/g.test(selector)){
                        addText = 'visible';
                    }else if(/(yp_click)/g.test(selector)){
                        addText = 'click';
                    }

                    if(addText != ''){
                        addText = " <strong>[" + addText + "]</strong>";
                    }

                    return space_cleaner(text + addText);

                }

            }



            /* ---------------------------------------------------- */
            /* Cleans dublicate words from string                   */
            /* ---------------------------------------------------- */
            function clean_duplicate_words(text) {

                var value = text;
                var array = value.split(" ");
                array.sort();
                value = array.join(" ");

                do {
                    var newValue = value;
                    var value = newValue.replace(/\s(\w+\s)\1/, " $1");
                } while (value.length != newValue.length);

                value = value.replace(/^(\w+\s)\1/, "$1");
                value = value.replace(/(\s\w+)\1$/, "$1");
                var original = text.split(" ");
                var result = "";

                for (var i = 0; i < original.length; i++) {

                    if (value.indexOf(" " + original[i] + " ") != -1) {

                        result += original[i] + " ";
                        value = value.split(" " + original[i] + " ").join(" ");

                    } else if ((value.indexOf(original[i]) != -1) && (value.indexOf(" " + original[i]) == (value.length - original[i].length - 1))) {

                        result += original[i] + " ";
                        value = value.substring(0, (value.length - original[i].length - 1));

                    } else if (value.indexOf(original[i] + " ") == 0) {

                        result += original[i] + " ";
                        value = value.substring(original[i].length + 1, value.length);

                    } else if (value == original[i]) {

                        result += original[i];
                        value = "";

                    }

                }

                if (result.substring(result.length - 1, result.length) == " ") {
                    result = result.substring(0, result.length - 1);
                }

                return result;

            }



            /* ---------------------------------------------------- */
            /* CSS Manager: Set auto width to input element         */
            /* ---------------------------------------------------- */
            function input_auto_width(x){
                var l = $(x).val().length;
                if(l > 40){l = 40;}
                if(l == 0){l = 1;}
                $(x).attr('size', l);
            }


            /* ---------------------------------------------------- */
            /* CSS Manager: Basic scripts                           */
            /* ---------------------------------------------------- */
            $(document).on("keyup keydown", '.css-manager-value-input' , function(){
                input_auto_width(this);
            });

            $(document).on("focus", '.css-manager-value-input' , function(){
                $(this).parent().addClass("css-manager-line-focus");
            });

            $(document).on("blur", '.css-manager-value-input' , function(){
                $(this).parent().removeClass("css-manager-line-focus");
            });

            $(document).on("click", '.css-manager-value-input' , function(){
                $(this).select();
            });

            $(document).on("click", '[data-toggle="tooltipCSSManager"],[data-toggle="tooltipCSSManagerLight"]' , function(){
                $(this).tooltip("hide");
            });



            /* ---------------------------------------------------- */
            /* CSS Manager: Empty stylesheet                        */
            /* ---------------------------------------------------- */
            $(document).on("mouseup", ".empty-stylesheet-btn", function(e){

                // block swal normal click event.
                e.stopPropagation();
                e.preventDefault();

                swal.close();
                the_editor_data().empty();

                // Option Changed
                option_change();

                // remove generated table.
                setTimeout(function(){
                    $(".wyp-reset-popup").remove();
                }, 200);

                // block swal normal click event.
                return false;

            });


            /* ---------------------------------------------------- */
            /* CSS Manager: CSS Checkbox                            */
            /* ---------------------------------------------------- */
            $(document).on("change", '.css-manager-checkbox' , function(e){

                var selectorHeading = $(this).parent().parent().prev();

                // disabled now
                if($(this).is(":checked") == true){

                    $(this).parent().addClass("css-manager-disabled");

                    if(e.originalEvent){

                        // Each all lines
                        var allDisabled = true;
                        $(this).parent().parent().find(".css-manager-line-group").each(function(){

                            if($(this).hasClass("css-manager-disabled") == false){
                                allDisabled = false;
                                return false;
                            }

                        });

                        if(allDisabled){
                            selectorHeading.find(".css-manager-selector-checkbox").prop('checked', true).trigger("change");
                        }

                    }

                }else{

                    $(this).parent().removeClass("css-manager-disabled");

                    if(e.originalEvent){
                        if(selectorHeading.find("h3").hasClass("css-selector-disabled")){
                            selectorHeading.find(".css-manager-selector-checkbox").prop('checked', false);
                            selectorHeading.find("h3").removeClass("css-selector-disabled");
                        }
                    }

                }

                // getting data for live CSS
                if(e.originalEvent || window.CSSManagerOriginalEvt){

                    var line = $(this).parent();
                    var size = line.attr("data-css-manager-size");
                    var selector = line.attr("data-css-manager-selector");
                    var rule = line.attr("data-css-manager-rule");
                    var value = line.attr("data-css-manager-value");

                    // Live disable process
                    if($(this).is(":checked") == true){

                        // Disable value will remove the rule.
                        value = 'disable';

                    }

                    // for live preview.
                    insert_rule(selector, rule, value, null, size);

                }

            });



            /* ---------------------------------------------------- */
            /* CSS Manager: Selector Checkbox                       */
            /* ---------------------------------------------------- */
            $(document).on("change", '.css-manager-selector-checkbox' , function(e){

                var mediaHeading = $(this).parent().parent().prev();

                $(this).parent().next(".css-manager-selector-group").find(".css-manager-checkbox").prop('checked', $(this).is(":checked")).trigger("change");

                // line decaration
                if($(this).is(":checked") == true){
                    $(this).next().addClass("css-selector-disabled");

                    if(e.originalEvent){

                        // Each all lines
                        var allDisabled = true;
                        $(this).parent().parent().find(".css-manager-selector-heading h3").each(function(i){

                            if($(this).hasClass("css-selector-disabled") == false){
                                allDisabled = false;
                                return false;
                            }

                        });

                        if(allDisabled){
                            mediaHeading.find(".css-manager-media-checkbox").prop('checked', true).trigger("change");
                        }

                    }

                }else{

                    $(this).next().removeClass("css-selector-disabled");

                    if(e.originalEvent){
                        if(mediaHeading.find("h3").hasClass("css-media-disabled")){
                            mediaHeading.find(".css-manager-media-checkbox").prop('checked', false);
                            mediaHeading.find("h3").removeClass("css-media-disabled");
                        }
                    }

                }

                if(e.originalEvent){
                    css_manager_live($(this), "selector");
                }

            });


            /* ---------------------------------------------------- */
            /* CSS Manager: Media Checkbox                          */
            /* ---------------------------------------------------- */
            $(document).on("change", '.css-manager-media-checkbox' , function(e){

                $(this).parent().next(".css-manager-media-group").find(".css-manager-selector-checkbox").prop('checked', $(this).is(":checked")).trigger("change");

                // line decaration
                if($(this).is(":checked") == true){
                    $(this).next().addClass("css-media-disabled");
                }else{
                    $(this).next().removeClass("css-media-disabled");
                }

                if(e.originalEvent){
                    css_manager_live($(this), "media");
                }

            });


            /* ---------------------------------------------------- */
            /* CSS Manager: Media Heading Click                     */
            /* ---------------------------------------------------- */
            $(document).on("click", '.css-manager-heading' , function(e){

                if($(e.target).hasClass("css-manager-media-checkbox") == false && $(e.target).hasClass("css-manager-selector-checkbox") == false && $(e.target).hasClass("css-manager-label") == false){

                    var header = $(this);
                    var icon = header.find("span.dashicons");
                    var content = header.next();

                    icon.toggleClass("dashicons-arrow-up");
                    icon.toggleClass("dashicons-arrow-down");

                    content.toggle();

                    if(header.hasClass("css-manager-media-heading")){

                        content.find(".css-manager-selector-group").hide();
                        content.find("span.dashicons-arrow-up").removeClass("dashicons-arrow-up").addClass("dashicons-arrow-down");

                    }

                    $('.wyp-manage-css-section').perfectScrollbar('update');

                }

            });


            /* ---------------------------------------------------- */
            /* Updating changes live on website                     */
            /* ---------------------------------------------------- */
            function css_manager_live(element, type){

                var insertData = '',CSSgroup,selectorGroup,CSSline,selector,rule,value,size,disableStyle;

                if(type == 'selector'){

                    CSSgroup = element.parent().next().find(".css-manager-line-group");

                    // CSS group each
                    CSSgroup.each(function(){

                        // get CSS line
                        CSSline = $(this);

                        // Get settings
                        selector = CSSline.attr("data-css-manager-selector");
                        rule = CSSline.attr("data-css-manager-rule");
                        value = CSSline.attr("data-css-manager-value");
                        size = CSSline.attr("data-css-manager-size");

                        // Live disable process
                        if(CSSline.find(".css-manager-checkbox").is(":checked") == true){

                            // remove the rule.
                                disableStyle = the_editor_data().find(".wyp_current_styles." + get_id(selector) + '-' + rule + "-style[data-size-mode='"+size+"']");

                                if(disableStyle.length > 0){
                                    disableStyle.remove();
                                }

                        }else{

                            // Insert data
                            insertData += get_insert_rule_basic(selector, rule, value, size);

                        }

                    });

                }else{ // media

                    // Get selector groups
                    selectorGroup = element.parent().next().find(".css-manager-selector-group");

                    // selector each
                    selectorGroup.each(function(){

                        // Get CSS groups
                        CSSgroup = $(this).find(".css-manager-line-group");

                        // CSS group each
                        CSSgroup.each(function(){

                            // get CSS line
                            CSSline = $(this);

                            // Get settings
                            selector = CSSline.attr("data-css-manager-selector");
                            rule = CSSline.attr("data-css-manager-rule");
                            value = CSSline.attr("data-css-manager-value");
                            size = CSSline.attr("data-css-manager-size");

                            // Live disable process
                            if(CSSline.find(".css-manager-checkbox").is(":checked") == true){

                                // remove the rule.
                                disableStyle = the_editor_data().find(".wyp_current_styles." + get_id(selector) + '-' + rule + "-style[data-size-mode='"+size+"']");

                                if(disableStyle.length > 0){
                                    disableStyle.remove();
                                }

                            }else{

                                // Insert data
                                insertData += get_insert_rule_basic(selector, rule, value, size);

                            }

                        });

                    });

                }

                // insert at end.
                if(insertData != ''){
                    the_editor_data().append(insertData);
                    resort_style_data_positions();
                }

            }


            /* ---------------------------------------------------- */
            /* Scrolling and hover the target element               */
            /* ---------------------------------------------------- */
            function element_focus(element){

                element.each(function(index){

                    var el = $(this);

                    // Dynamic box
                    if (iframe.find(".wyp-element-viewer-" + index).length === 0) {
                        iframeBody.append("<div class='wyp-element-viewer wyp-element-viewer-" + index + "'></div>");
                    }

                    // size
                    var h = el.outerHeight();
                    var w = el.outerWidth();

                    // position
                    var offset = el.offset();
                    var t = offset.top;
                    var l = offset.left;

                    // Draw
                    iframe.find(".wyp-element-viewer-" + index).css("width", w).css("height", h).css("top", t).css("left", l);

                });

            }



            /* ---------------------------------------------------- */
            /* Hide blue borders on options click section           */
            /* ---------------------------------------------------- */
            $(document).on("click",".wyp-this-content",function(e){
                if (e.originalEvent) {
                    hide_frame_ui(200);
                }
            });


            // Combine values and formats as the best
            function get_format_range(prefix, group){

                // Default Pixel Range
                var range = group.data("px").split(",");

                // Update %.
                if (prefix == '%' || prefix == 'vw' || prefix == 'vh') {
                    range = group.data("pc").split(",");
                }

                // Update EM.
                if (prefix == 'em' || prefix == 'rem' || prefix == 'ex' || prefix == 'cm' || prefix == 'in' || prefix == 'pc') {
                    range = group.data("em").split(",");
                }

                // Update S.
                if (prefix == 's') {
                    range = group.data("em").split(",");
                }

                // Update MS.
                if (prefix == 'ms') {
                    range = group.data("em").split(",");
                    range[0] = parseInt(range[0]*1000);
                    range[1] = parseInt(range[1]*1000);
                }

                return range;

            }


            /* ---------------------------------------------------- */
            /* Setup Slider Option                                  */
            /* ---------------------------------------------------- */
            function slider_option(id, decimals, pxv, pcv, emv) {

                var thisContent = $("#" + id + "-group").parent(".wyp-this-content");

                var elementParent = $("#" + id + "-group");

                // Set Maximum and minimum values for custom prefixs.
                elementParent.data("px", pxv);
                elementParent.data("pc", pcv);
                elementParent.data("em", emv);

                // Get prefix
                var prefix = $("#" + id + "-group .wyp-css-format").val();

                // Getting range
                var range = get_format_range(prefix, elementParent);

                // Setup slider.
                $('#wyp-' + id).wqNoUiSlider({

                    start: [0],

                    range: {
                        'min': parseInt(range[0]),
                        'max': parseInt(range[1])
                    },

                    format: wNumb({
                        mark: '.',
                        decimals: decimals
                    })

                }).on('change', function() {

                    $(".fake-layer").remove();

                    var lock = thisContent.find(".lock-btn.active").length;
                    var lockedIdArray = [];

                    if(lock){

                        thisContent.find(".wyp-option-group").each(function(){
                            lockedIdArray.push($(this).attr("data-css"));
                        });

                        var val = $(this).val();

                        for(var y = 0;y < lockedIdArray.length; y++){
                            $('#wyp-' + lockedIdArray[y]).val(val);
                            $('#' + lockedIdArray[y] + '-after').trigger("keyup");
                            slide_action($("#wyp-" + lockedIdArray[y]), lockedIdArray[y], true, false);
                        }

                        option_change();

                    }else{
                        slide_action($(this), id, true, true);
                    }

                }).on('slide', function() {

                    // Be sure its hidden.
                    hide_frame_ui(200);

                    var lock = thisContent.find(".lock-btn.active").length;
                    var lockedIdArray = [];

                    if(lock){
                        thisContent.find(".wyp-option-group").each(function(){
                            lockedIdArray.push($(this).attr("data-css"));
                        });
                    }

                    // Get val
                    var val = $(this).val();
                    var prefix = $('#' + id+"-after").val();
                    var y;

                    val = Number((parseFloat(val)).toFixed(2));
                    var left = $("#" + id + "-group").find(".wqNoUi-origin").css("left");

                    // Update the input.
                    if(lock === 0){
                        $('#' + id + '-value').val(val);
                    }else{
                        for(y = 0;y < lockedIdArray.length; y++){
                            $('#' + lockedIdArray[y] + '-value').val(val);
                            $('#' + lockedIdArray[y] + '-after').val(prefix);
                            $('#' + lockedIdArray[y] + '-group').find(".wqNoUi-origin").css("left",left);
                        }
                    }


                    prefix = $(this).parent().find("#" + id + "-after").val();

                    // Standard.
                    if(lock === 0){
                        delete_live_css(id, false);
                        insert_live_css(id, val + prefix, false);
                    }else{
                        for(y = 0;y < lockedIdArray.length; y++){
                            delete_live_css(lockedIdArray[y], false);
                            insert_live_css(lockedIdArray[y], val + prefix, false);
                        }
                    }

                    if($(".fake-layer").length === 0){
                        mainBody.append("<div class='fake-layer'></div>");
                    }

                });

            }


            /* ---------------------------------------------------- */
            /* Slider Event                                         */
            /* ---------------------------------------------------- */
            function slide_action(element, id, $slider,changed) {

                var css = element.parent().parent().data("css");
                element.parent().parent().addClass("reset-enable");

                var val;

                if ($slider === true) {

                    val = element.val();

                    // If active, disable it.
                    element.parent().parent().find(".wyp-btn-action.active").trigger("click");

                }

                // If input is active, get value by original data.
                // noUiSlider clear all dots in normal method.
                if($("#" + css + "-value").is(":focus")){
                    val = parseFloat(element.parent().find("#" + css + "-value").val());
                }

                var css_after = element.parent().find("#" + css + "-after").val();

                // Set for demo
                insert_rule(null, id, val, css_after);

                // Option Changed
                if(changed){
                    option_change();
                }

            }


            /* ---------------------------------------------------- */
            /* ESCAPE                                               */
            /* ---------------------------------------------------- */
            function escape(s) {
                return ('' + s) /* Forces the conversion to string. */
                    .replace(/\\/g, '\\\\') /* This MUST be the 1st replacement. */
                    .replace(/\t/g, '\\t') /* These 2 replacements protect whitespaces. */
                    .replace(/\n/g, '\\n')
                    .replace(/\u00A0/g, '\\u00A0') /* Useful but not absolutely necessary. */
                    .replace(/&/g, '\\x26') /* These 5 replacements protect from HTML/XML. */
                    .replace(/'/g, '\\x27')
                    .replace(/"/g, '\\x22')
                    .replace(/</g, '\\x3C')
                    .replace(/>/g, '\\x3E');
            }


            /* ---------------------------------------------------- */
            /* Border Type: Update View                             */
            /* ---------------------------------------------------- */
            $("#wyp-border-type .wyp-radio").on("click", function(){

                var value = $("#wyp-border-type .wyp-radio.active input").val();

                $(".wyp-border-all-section,.wyp-border-top-section,.wyp-border-right-section,.wyp-border-bottom-section,.wyp-border-left-section").hide();

                $(".wyp-border-"+value+"-section").show();

                insert_default_options();

            });


            /* ---------------------------------------------------- */
            /* Getting radio value                                  */
            /* ---------------------------------------------------- */
            function radio_value(the_id, $n, data) {

                var id_prt = the_id.parent().parent();

                // for none btn
                id_prt.find(".wyp-btn-action.active").trigger("click");

                if (data == id_prt.find(".wyp-none-btn").text()) {
                    id_prt.find(".wyp-none-btn").trigger("click");
                }

                if (data == 'auto auto') {
                    data = 'auto';
                }

                if (data != '' && typeof data != 'undefined') {

                    if (data.match(/\bauto\b/g)) {
                        data = 'auto';
                    }

                    if (data.match(/\bnone\b/g)) {
                        data = 'none';
                    }

                    if ($("input[name=" + $n + "][value=" + escape(data) + "]").length > 0) {

                        the_id.find(".active").removeClass("active");

                        $("input[name=" + $n + "][value=" + escape(data) + "]").prop('checked', true).parent().addClass("active");

                    } else {

                        the_id.find(".active").removeClass("active");

                        // Disable all.
                        $("input[name=" + $n + "]").each(function() {

                            $(this).prop('checked', false);

                        });

                        id_prt.find(".wyp-none-btn:not(.active)").trigger("click");

                    }

                }

            }

            /* ---------------------------------------------------- */
            /* Radio Setup                                          */
            /* ---------------------------------------------------- */
            function radio_option(id) {

                $("#wyp-" + id + " label").on('click', function() {

                    if($(".position-option.active").length === 0){
                        if($(this).parent().hasClass("active")){
                            return false;
                        }
                    }

                    // Disable none.
                    $(this).parent().parent().parent().parent().find(".wyp-btn-action.active").removeClass("active");
                    $(this).parent().parent().parent().parent().addClass("reset-enable").css("opacity", 1);

                    $("#wyp-" + id).find(".active").removeClass("active");

                    $(this).parent().addClass("active");

                    $("#" + $(this).attr("data-for")).prop('checked', true);

                    var val = $("input[name=" + id + "]:checked").val();

                    // Set for demo
                    insert_rule(null, id, val, '');

                    // Option Changed
                    option_change();

                });

            }

            /* ---------------------------------------------------- */
            /* Check if is safe font family.                        */
            /* ---------------------------------------------------- */
            function is_safe_font(a) {

                if(isUndefined(a)){
                    return false;
                }

                var regex = /\barial\b|\barial black\b|\barial narrow\b|\barial rounded mt bold\b|\bavant garde\b|\bcalibri\b|\bcandara\b|\bcentury gothic\b|\bfranklin gothic medium\b|\bgeneva\b|\bfutura\b|\bgill sans\b|\bhelvetica neue\b|\bimpact\b|\blucida grande\b|\boptima\b|\bsegoe ui\b|\btahoma\b|\btrebuchet ms\b|\bverdana\b|\bbig caslon\b|\bbodoni mt\b|\bbook antiqua\b|\bcalisto mt\b|\bcambria\b|\bdidot\b|\bgaramond\b|\bgeorgia\b|\bgoudy old style\b|\bhoefler text\b|\blucida bright\b|\bpalatino\b|\bperpetua\b|\brockwell\b|\brockwell extra bold\b|\bbaskerville\b|\btimes new roman\b|\bconsolas\b|\bcourier new\b|\blucida console\b|\bhelveticaneue\b/;

                var data = a.toLowerCase();

                return regex.test(data);

            }


            /* ---------------------------------------------------- */
            /* Warning System                                       */
            /* ---------------------------------------------------- */

            // Margin : display inline / negative margin warnings
            $("#margin-left-group,#margin-right-group,#margin-top-group,#margin-bottom-group").on("mousemove", function(e) {

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // Destroy
                $(this).popover("destroy");

                // Show display warning
                if (get_selected_element().css("display") == "inline" || get_selected_element().css("display") == "table-cell") {

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_display_notice,
                        trigger: 'hover',
                        placement: "left",
                        container: ".editor-panel",
                        html: true
                    }).popover("show");

                // Show negative margin value warning if not responsive
                } else if($("#"+$(this).attr("data-css")+"-value").val() < 0) {

                    // don't show if orginal value
                    if($(this).hasClass("reset-enable") == false && $(this).find(".wqNoUi-active").length == 0){
                        return true;
                    }

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_negative_margin_notice,
                        trigger: 'hover',
                        placement: "left",
                        container: ".editor-panel",
                        html: true
                    }).popover("show");

                }

            });


            // List: disable list style image to use list style type
            $("#list-style-type-group").on("mousemove", function(e) {

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // Destroy
                $(this).popover("destroy");

                // list stype image has value, and none button not active
                if($("#wyp-list-style-image").val().length > 12 && $("#list-style-image-group .wyp-none-btn.active").length == 0){

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_list_notice1,
                        trigger: 'hover',
                        placement: "left",
                        container: ".editor-panel",
                        html: true
                    }).popover("show");

                }

            });


            // cursor: cursor will not work in editor mode.
            $("#cursor-group").on("mousemove", function(e) {

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // Destroy
                $(this).popover("destroy");

                // if cursor changed
                if($("#cursor-group").hasClass("reset-enable")){

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: "This change does not appear in the editor, check it with Live Preview.",
                        trigger: 'hover',
                        placement: "left",
                        container: ".editor-panel",
                        html: true
                    }).popover("show");

                }

            });


            // List: please select a list item to edit
            $("#list-style-position-group,#list-style-image-group,#list-style-type-group").on("mousemove", function(e) {

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // Destroy
                if($(this).attr("id") == 'list-style-type-group'){

                    // Destroy if there not have another warning.
                    if($("#wyp-list-style-image .wyp-none-btn.active").length == 1){
                        $(this).popover("destroy");
                    }

                }else{
                    $(this).popover("destroy");
                }

                // Get selected element tag
                var tag = get_selected_element()[0].nodeName.toLowerCase();

                // Show tag warning
                if (tag != 'li' && tag != 'ul'){

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_list_notice,
                        trigger: 'hover',
                        placement: "left",
                        container: ".editor-panel",
                        html: true
                    }).popover("show");

                }

            });


            // Width / Padding : display inline warning
            $("#scale-transform-group,#rotatex-transform-group,#rotatey-transform-group,#rotatez-transform-group,#translate-x-transform-group,#translate-y-transform-group,#skew-x-transform-group,#skew-y-transform-group,#perspective-group,#padding-left-group,#padding-right-group,#padding-top-group,#padding-bottom-group,#width-group,#height-group").on("mousemove", function(e) {

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // Destroy
                $(this).popover("destroy");

                // Display warning
                if (get_selected_element().css("display") == "inline") {

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_display_notice,
                        trigger: 'hover',
                        placement: "left",
                        container: ".editor-panel",
                        html: true
                    }).popover("show");

                }

            });


            /* ---------------------------------------------------- */
            /* Show responsive notice one time                      */
            /* ---------------------------------------------------- */
            $(".wyp-responsive-btn").click(function(){

                var resBtn = $(".wyp-responsive-btn");

                // Opened && not showed before
                if(resBtn.hasClass("active") == false && mainBody.hasClass("wyp-responsive-notice-showed") == false){

                    // 500ms wait
                    setTimeout(function(){

                        if(resBtn.hasClass("active") == false){

                            // Destory
                            $('.responsive-right-handle').tooltip("destroy");

                            // Install
                            $('.responsive-right-handle').tooltip({
                                title: l18_responsive_notice,
                                animation: true,
                                placement: 'right',
                                trigger: 'manual',
                                container: "body",
                                html: true
                            }).tooltip("show");

                            // Added: showed class
                            mainBody.addClass("wyp-responsive-notice-showed");

                        }

                    },500);

                    // Hide after 6s
                    setTimeout(function(){
                        $('.responsive-right-handle').tooltip("hide");
                    },5000);

                }else{

                    // Hide
                    $('.responsive-right-handle').tooltip("hide");

                }

            });


            /* ---------------------------------------------------- */
            /* position: top left right bottom = 120 > not good.    */
            /* ---------------------------------------------------- */
            $("#left-group,#right-group,#top-group,#bottom-group").on("mousemove", function(e) {

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // Show notice just for desktop mode.
                if(is_responsive_mod()){
                    return true;
                }

                // don't show if orginal value
                if($(this).hasClass("reset-enable") == false && $(this).find(".wqNoUi-active").length == 0){
                    return true;
                }

                // Destroy
                $(this).popover("destroy");

                // high value warning
                if($("#"+$(this).attr("id").replace("group","value")).val() >= 120) {

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_high_position_notice,
                        trigger: 'hover',
                        placement: "left",
                        container: ".editor-panel",
                        html: true
                    }).popover("show");

                }

            });


            /* ---------------------------------------------------- */
            /* Fixed and absolute not recommend                     */
            /* ---------------------------------------------------- */
            $("#position-group").on("mousemove click", function(e) {

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // Destroy
                $(this).popover("destroy");

                // Show notice just for desktop mode.
                if(is_responsive_mod()){
                    return true;
                }

                // don't show if orginal value
                if($(this).hasClass("reset-enable") == false && $(this).find(".wqNoUi-active").length == 0){
                    return true;
                }


                // fixed warning
                if($(".wyp-radio.active #position-fixed").length > 0) {

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_fixed_notice,
                        trigger: 'hover',
                        placement: "left",
                        container: ".editor-panel",
                        html: true
                    }).popover("show");

                // absolute warning
                } else if($(".wyp-radio.active #position-absolute").length > 0) {

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_absolute_notice,
                        trigger: 'hover',
                        placement: "left",
                        container: ".editor-panel",
                        html: true
                    }).popover("show");

                }

            });


            /* ---------------------------------------------------- */
            /* These features need to a background image            */
            /* ---------------------------------------------------- */
            $("#background-size-group,#background-repeat-group,#background-blend-mode-group,#background-attachment-group,#background-position-group").on("mousemove", function(e) {

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // Destroy
                $(this).popover("destroy");

                // show warning if not have a blackground image
                if ($("#wyp-background-image").val() == '' || $("#background-image-group .wyp-none-btn.active").length === 1) {

                    $(this).popover({
                        animation: false,
                        title: l18_notice,
                        content: l18_bg_img_notice_two,
                        trigger: 'hover',
                        placement: "left",
                        container: ".editor-panel",
                        html: true
                    }).popover("show");

                }

            });


            /* ---------------------------------------------------- */
            /* show a warning when open animation section.          */
            /* ---------------------------------------------------- */
            $(".animation-option").on("click", function(e){

                // Stop if not orginal
                if (!e.originalEvent) {
                    return true;
                }

                // El
                var t = $("#animation-name-group");

                // stop if not active
                if(!$(this).hasClass("active")){
                    t.popover("destroy");
                    return true;
                }

                // Display warning
                if (get_selected_element().css("display") == "inline") {

                    t.popover({
                        animation: false,
                        title: l18_warning,
                        content: l18_display_notice,
                        trigger: 'click',
                        placement: "left",
                        container: ".editor-panel",
                        html: true
                    }).popover("show");

                // else destroy
                } else {
                    t.popover("destroy");
                }

            });


            /* ---------------------------------------------------- */
            /* Destroy popover after disable                        */
            /* ---------------------------------------------------- */
            $(".wyp-disable-btn").on("click",function(){
                $(this).parents(".wyp-option-group").popover("destroy");
            });


            // Hide while editor options scrolling
            $(".editor-panel-inner").on("scroll", function(){
                $(".wyp-option-group,.wyp-advanced-option").popover("hide");
            });



            /* ---------------------------------------------------- */
            /* Select li hover                                      */
            /* ---------------------------------------------------- */
            $(".input-autocomplete").keydown(function(e) {

                var code = e.keyCode || e.which;

                if (code == 38 || code == 40) {

                    $(this).parent().find(".autocomplete-div .ui-state-focus").prev().trigger("mouseout");
                    $(this).parent().find(".autocomplete-div .ui-state-focus").trigger("mouseover");

                }

                // enter
                if (code == 13) {

                    $(this).blur();

                }

            });


            /* ---------------------------------------------------- */
            /* Blur select after select                             */
            /* ---------------------------------------------------- */
            $(document).on("click", ".autocomplete-div ul li", function() {
                $(this).parent().parent().parent().find(".ui-autocomplete-input").trigger("blur");
            });


            /* ---------------------------------------------------- */
            /* autocomplete blur                                    */
            /* ---------------------------------------------------- */
            $(".input-autocomplete").on("blur keyup", function(e) {

                if (window.openVal == $(this).val()) {
                    return false;
                }

                var id = $(this).parent().parent().data("css");

                $(".active-autocomplete-item").removeClass("active-autocomplete-item");
                $(this).removeClass("active");

                setTimeout(function(){
                    mainBody.removeClass("autocomplete-active");
                },300);

                delete_live_css(id, "#wyp-" + id + "-test-style");

                // Disable
                $(this).parent().parent().find(".wyp-btn-action.active").trigger("click");
                $("#" + id + "-group").addClass("reset-enable");

                // Font weight.
                if (id == 'font-weight') {
                    $("#wyp-font-weight").css(id, $(this).val()).css("fontFamily", $("#wyp-font-family").val());
                }

                // Font family
                if (id == 'font-family') {
                    $("#wyp-font-family").css(id, $(this).val());
                    $("#wyp-font-weight").css("fontFamily", $("#wyp-font-family").val());
                }

                // Text shadow live change.
                if (id == 'text-shadow') {
                    $("#wyp-text-shadow").css(id, $(this).val());
                }

                var val = $(this).val();

                if (id == 'font-family') {
                    if (val.indexOf(",") == -1 && val.indexOf("'") == -1 && val.indexOf('"') == -1) {
                        val = "'" + val + "'";
                    }
                }

                // Set for data
                insert_rule(null, id, val, '');

                option_change();

            });


            /* ---------------------------------------------------- */
            /* Fix Break minify issue after editor loaded:          */
            /* convert line break to space in the selectors         */
            /* ---------------------------------------------------- */
            iframe.find(".wyp-styles-area,.wyp-animate-data").each(function(){

                var content = $(this).html().replace(/(\n|(\s+))/g,' ');

                // Update Style Elements
                $(this).html(content);

            });


            /* ---------------------------------------------------- */
            /* autocomplete li hover                                */
            /* ---------------------------------------------------- */
            $(document).on("mouseover", ".autocomplete-div li", function() {

                var element = $(this);

                $(".active-autocomplete-item").removeClass("active-autocomplete-item");

                var id = element.parent().parent().attr("id").replace("wyp-autocomplete-place-", "");

                    // If not current.
                    if (!element.hasClass("ui-state-focus")) {
                        return false;
                    }

                    // If not undefined.
                    if (typeof element.parent().attr("id") == 'undefined') {
                        return false;
                    }

                    // Font weight
                    if (id == 'font-weight') {

                        delete_live_css("font-weight");
                        insert_live_css("font-weight", number_filter(element.text()).replace("-", ""));

                    }

                    // Font family
                    if (id == 'font-family') {

                        load_near_fonts(element.parent());

                        delete_live_css("font-family");

                        // Getting the right font family
                        var index = element.index(), currentOption = $("#wyp-font-family-data option").eq(index);

                        // All Font family
                        if(currentOption.length > 0){

                            insert_live_css('font-family',currentOption.val()); // 'Open Sans', sans-serif

                        // Only first font family
                        }else{

                            insert_live_css('font-family', "'" + element.text() + "'"); // 'Open Sans'

                        }

                    }


                // Font Weight
                if (id == 'font-weight') {

                    $(".autocomplete-div li").each(function() {
                        element.css("fontWeight", number_filter(element.text()).replace(/-/g, ''));
                    });

                    $(".autocomplete-div li").css("fontFamily", $("#wyp-font-family").val());
                }

            });


            /* ---------------------------------------------------- */
            /* getting multiple delay                               */
            /* ---------------------------------------------------- */
            function get_multiple_delay(duration,delay){

                if(isUndefined(duration) || isUndefined(delay)){
                    return false;
                }

                var resultDelay = 0;
                var durationArray = duration.toString().split(",");
                var delayArray = delay.toString().split(",");

                if(durationArray.length != delayArray.length){
                    return false;
                }

                if(durationArray.length <= 1){
                    return false;
                }

                var currents = 0;
                for(var i = 0; i < durationArray.length; i++){
                    if(isDefined(delayArray[i+1])){
                        currents = currents + parseFloat(duration_ms(durationArray[i]));
                        resultDelay = (parseFloat(duration_ms(delayArray[i+1])) - currents) + resultDelay;
                        currents = currents + resultDelay;
                    }
                }

                return resultDelay;

            }


            /* ---------------------------------------------------- */
            /* mouseout autocomplete div                            */
            /* ---------------------------------------------------- */
            $(document).on("mouseout", ".autocomplete-div", function() {

                delete_live_css("font-family");

            });


            /* ---------------------------------------------------- */
            /* if mouseleave, leave                                 */
            /* ---------------------------------------------------- */
            $(document).on("mouseleave", $(document), function() {

                if(body.hasClass("wyp-mouseleave")){
                    return false;
                }

                body.addClass("wyp-mouseleave");

                // remove multiple selection support.
                body.removeClass("wyp-control-key-down");
                iframe.find(".wyp-multiple-selected").removeClass("wyp-multiple-selected");
                iframe.find(".wyp-selected-others-multiable-box").remove();

                if(is_content_selected() === false){
                    clean();
                }

                // delete fake layers
                mainBody.removeClass("fake-layer-x-bg");
                $(".fake-layer-x").remove();
                $(".ui-resizable-handle").removeClass("active");

            });



            /* ---------------------------------------------------- */
            /* MouseUP clean active resizeable handle               */
            /* ---------------------------------------------------- */
            $(document).on("mouseup", $(document), function(e) {

                if($(".ui-resizable-resizing").length != 0){
                    $(".ui-resizable-handle").removeClass("active");
                }

            });



            /* ---------------------------------------------------- */
            /* If mouseenter                                        */
            /* ---------------------------------------------------- */
            $(document).on("mouseenter", $(document), function() {

                body.removeClass("wyp-mouseleave");

                // remove multiple selection support.
                body.removeClass("wyp-control-key-down");
                iframe.find(".wyp-multiple-selected").removeClass("wyp-multiple-selected");
                iframe.find(".wyp-selected-others-multiable-box").remove();

            });


            /* ---------------------------------------------------- */
            /* iframe: if mouseleave, leave                         */
            /* ---------------------------------------------------- */
            iframe.on("mouseleave", iframe, function() {

                if(body.hasClass("wyp-iframe-mouseleave")){
                    return false;
                }

                body.addClass("wyp-iframe-mouseleave");

                // remove multiple selection support.
                body.removeClass("wyp-control-key-down");
                iframe.find(".wyp-multiple-selected").removeClass("wyp-multiple-selected");
                iframe.find(".wyp-selected-others-multiable-box").remove();

            });

            /* ---------------------------------------------------- */
            /* iframe: If mouseenter                                */
            /* ---------------------------------------------------- */
            iframe.on("mouseenter", iframe, function() {

                body.removeClass("wyp-iframe-mouseleave");

            });


            /* ---------------------------------------------------- */
            /* Loading visible fonts                                */
            /* ---------------------------------------------------- */
            function load_near_fonts(t){

                var ul = $("#font-family-group .ui-autocomplete.ui-menu").outerHeight();
                var li = $("#font-family-group .ui-autocomplete.ui-menu li").outerHeight();

                var number = parseInt(ul/li)+1;

                var element = t.find(".ui-state-focus");

                if(element.length === 0){
                    element = t.find(".active-autocomplete-item");
                }

                var prev = element.prevAll().slice(0,number);
                var next = element.nextAll().slice(0,number);

                var all = prev.add(next).add(element);

               all.each(function() {

                    var element = $(this);

                    var styleAttr = element.attr("style");

                    // If has style attr, it mean this font family already loaded.
                    if (isDefined(styleAttr)){
                        return true;
                    }

                    var fontId = get_basic_id($.trim(element.text().replace(/ /g, '+')));

                    if (is_safe_font(element.text()) === false && iframe.find(".wyp-font-test-" + fontId).length === 0) {

                        iframeBody.append("<link rel='stylesheet' class='wyp-font-test-" + fontId + "'  href='https://fonts.googleapis.com/css?family=" + $.trim(element.text().replace(/ /g, '+')) + ":300italic,300,400,400italic,500,500italic,600,600italic,700,700italic' type='text/css' media='all' />");

                        // Append always to body.
                        mainBody.append("<link rel='stylesheet' class='wyp-font-test-" + fontId + "'  href='https://fonts.googleapis.com/css?family=" + $.trim(element.text().replace(/ /g, '+')) + ":300italic,300,400,400italic,500,500italic,600,600italic,700,700italic' type='text/css' media='all' />");

                    }

                    element.css("fontFamily", element.text());

                });

            }


            /* ---------------------------------------------------- */
            /* Loading fonts while autocomplete scrolling           */
            /* ---------------------------------------------------- */
            $("#wyp-autocomplete-place-font-family > ul").bind('scroll', function() {

                load_near_fonts($(this));

            });


            // Toggle options.
            $(".wf-close-btn-link").click(function(e) {
                if ($(".editor-panel-list > li.active").length > 0) {
                    e.preventDefault();
                    $(".editor-panel-list > li.active > h3").trigger("click");
                }
            });


            /* ---------------------------------------------------- */
            /* Creating live CSS because more faster. Color/Slider  */
            /* ---------------------------------------------------- */
            function insert_live_css(id, val, custom) {

                var selector = get_current_selector();

                // Set parent element as current
                if(id == 'perspective'){

                    // Cache current
                    var oldSelector = selector;

                    // clean cache
                    body.removeAttr("data-clickable-select");

                    // Update selector var
                    selector = $.trim(get_parents(get_selected_element().parent(), "default"));

                    // set old as cache again
                    body.attr("data-clickable-select",oldSelector);

                }

                // Adds relative automatics
                if(id == 'top' || id == 'left' || id == 'right' ||id == 'bottom'){

                    // If is static
                    if($(".wyp-radio.active #position-static").length > 0){

                        // Insert position relative
                        insert_rule(null, "position", "relative", '');
                        $("#position-group .wyp-radio.active").removeClass("active");
                        $("#position-relative").parent().addClass("active");

                    }

                }


                // Checks min height and min width and update.
                if(id == 'height' || id == 'width'){

                    // minValue & minFormat
                    var minVal = number_filter($("#min-"+id+"-value").val());
                    var prefix = $("#"+id+"-after").val();
                    var minFormat = $("#min-"+id+"-after").val();

                    // if height is smaller than min-height, so update min height
                    if(parseFloat(val) < parseFloat(minVal) && prefix == minFormat){

                        // Insert min-height
                        delete_live_css('min-'+id,false);
                        insert_live_css('min-'+id,val,false);

                    }

                }


                // Responsive helper
                var mediaBefore = create_media_query_before(null);
                var mediaAfter = create_media_query_after();

                // Style id
                var styleId;
                if (custom !== false && custom !== undefined) {
                    styleId = custom;
                } else {
                    styleId = "#" + id + "-live-css";
                }

                //Element
                var element = iframe.find(styleId);

                // Check
                if (element.length === 0) {

                    var idAttr = styleId.replace('#', '').replace('.', '');

                    // not use prefix (px,em,% etc)
                    if (id == 'z-index' || id == 'opacity') {
                        val = parseFloat(val);
                    }


                    // Filter Default options.
                    if (id == 'blur-filter' || id == 'grayscale-filter' || id == 'brightness-filter' || id == 'contrast-filter' || id == 'hue-rotate-filter' || id == 'saturate-filter' || id == 'sepia-filter') {

                        id = 'filter';
                        idAttr = 'filter';

                        val = filter_generator(false);

                    }
                    // Filter options end

                    // Transform Settings
                    if (id.indexOf("-transform") != -1 && id != 'text-transform' && id != '-webkit-transform') {

                        id = 'transform';
                        idAttr = 'transform';

                        val = transform_generator(false);


                    }
                    // Transform options end


                    // Box Shadow
                    if (id == 'box-shadow-inset' || id == 'box-shadow-color' || id == 'box-shadow-vertical' || id == 'box-shadow-blur-radius' || id == 'box-shadow-spread' || id == 'box-shadow-horizontal') {

                        id = 'box-shadow';
                        idAttr = 'box-shadow';

                        val = box_shadow_generator(selector);


                    }
                    // Box shadow options end


                    // Append
                    if(id == 'filter' || id == 'transform'){ // Webkit support

                        iframeBody.append("<style class='" + idAttr + " wyp-live-css' id='" + idAttr + "'>" + mediaBefore + ".wyp-selected,.wyp-selected-others," + selector + "{" + id + ":" + val + " !important;-webkit-" + id + ":" + val + " !important;}" + mediaAfter + "</style>");

                    }else{ // default

                        iframeBody.append("<style class='" + idAttr + " wyp-live-css' id='" + idAttr + "'>" + mediaBefore + ".wyp-selected,.wyp-selected-others," + selector + "{" + id + ":" + val + " !important;}" + mediaAfter + "</style>");

                    }

                }

            }


            /* ---------------------------------------------------- */
            /* Generating transform generator                       */
            /* ---------------------------------------------------- */
            function transform_generator(type){

                // Getting all other options.
                var scale = "scale(" + $.trim($("#scale-transform-value").val()) + ")";
                var rotatex = "rotateX(" + $.trim($("#rotatex-transform-value").val()) + "deg)";
                var rotatey = "rotateY(" + $.trim($("#rotatey-transform-value").val()) + "deg)";
                var rotatez = "rotateZ(" + $.trim($("#rotatez-transform-value").val()) + "deg)";
                var translateX = "translatex(" + $.trim($("#translate-x-transform-value").val()) + "px)";
                var translateY = "translatey(" + $.trim($("#translate-y-transform-value").val()) + "px)";
                var skewX = "skewx(" + $.trim($("#skew-x-transform-value").val()) + "deg)";
                var skewY = "skewy(" + $.trim($("#skew-y-transform-value").val()) + "deg)";

                // Check if disable or not
                if ($("#scale-transform-group .wyp-disable-btn").hasClass("active")) {
                    scale = '';
                }

                if ($("#rotatex-transform-group .wyp-disable-btn").hasClass("active")) {
                    rotatex = '';
                }

                if ($("#rotatey-transform-group .wyp-disable-btn").hasClass("active")) {
                    rotatey = '';
                }

                if ($("#rotatez-transform-group .wyp-disable-btn").hasClass("active")) {
                    rotatez = '';
                }

                if ($("#translate-x-transform-group .wyp-disable-btn").hasClass("active")) {
                    translateX = '';
                }

                if ($("#translate-y-transform-group .wyp-disable-btn").hasClass("active")) {
                    translateY = '';
                }

                if ($("#skew-x-transform-group .wyp-disable-btn").hasClass("active")) {
                    skewX = '';
                }

                if ($("#skew-y-transform-group .wyp-disable-btn").hasClass("active")) {
                    skewY = '';
                }

                // Dont insert if no data.
                if (scale == 'scale()' || ($("#scale-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        scale = '';
                    } else {
                        scale = 'scale(1)';
                    }

                }

                if (rotatex == 'rotateX(deg)' || ($("#rotatex-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        rotatex = '';
                    } else {
                        rotatex = 'rotateX(0deg)';
                    }

                }

                if (rotatey == 'rotateY(deg)' || ($("#rotatey-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        rotatey = '';
                    } else {
                        rotatey = 'rotateY(0deg)';
                    }

                }

                if (rotatez == 'rotateZ(deg)' || ($("#rotatez-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        rotatez = '';
                    } else {
                        rotatez = 'rotateZ(0deg)';
                    }

                }

                if (translateX == 'translatex(px)' || ($("#translate-x-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        translateX = '';
                    } else {
                        translateX = 'translatex(0px)';
                    }

                }

                if (translateY == 'translatey(px)' || ($("#translate-y-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        translateY = '';
                    } else {
                        translateY = 'translatey(0px)';
                    }

                }

                if (skewX == 'skewx(deg)' || ($("#skew-x-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        skewX = '';
                    } else {
                        skewX = 'skewx(0deg)';
                    }

                }

                if (skewY == 'skewy(deg)' || ($("#skew-y-transform-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        skewY = '';
                    } else {
                        skewY = 'skewy(0deg)';
                    }

                }

                // All data.
                var translateData = $.trim(scale + " " + rotatex + " " + rotatey + " " + rotatez + " " + translateX + " " + translateY + " " + skewX + " " + skewY);

                if (translateData === '' || translateData == ' ') {
                    translateData = 'disable';
                    body.removeClass("wyp-has-transform");
                }

                return translateData;

            }


            /* ---------------------------------------------------- */
            /* Filter generating                                    */
            /* ---------------------------------------------------- */
            function filter_generator(type){

                // Getting all other options.
                var blur = "blur(" + $.trim($("#blur-filter-value").val()) + "px)";
                var grayscale = "grayscale(" + $.trim($("#grayscale-filter-value").val()) + ")";
                var brightness = "brightness(" + $.trim($("#brightness-filter-value").val()) + ")";
                var contrast = "contrast(" + $.trim($("#contrast-filter-value").val()) + ")";
                var hueRotate = "hue-rotate(" + $.trim($("#hue-rotate-filter-value").val()) + "deg)";
                var saturate = "saturate(" + $.trim($("#saturate-filter-value").val()) + ")";
                var sepia = "sepia(" + $.trim($("#sepia-filter-value").val()) + ")";

                // Check if disable or not
                if ($("#blur-filter-group .wyp-disable-btn").hasClass("active")) {
                    blur = '';
                }

                if ($("#grayscale-filter-group .wyp-disable-btn").hasClass("active")) {
                    grayscale = '';
                }

                if ($("#brightness-filter-group .wyp-disable-btn").hasClass("active")) {
                    brightness = '';
                }

                if ($("#contrast-filter-group .wyp-disable-btn").hasClass("active")) {
                    contrast = '';
                }

                if ($("#hue-rotate-filter-group .wyp-disable-btn").hasClass("active")) {
                    hueRotate = '';
                }

                if ($("#saturate-filter-group .wyp-disable-btn").hasClass("active")) {
                    saturate = '';
                }

                if ($("#sepia-filter-group .wyp-disable-btn").hasClass("active")) {
                    sepia = '';
                }

                // Dont insert if no data.
                if (blur == 'blur(px)' || ($("#blur-filter-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        blur = '';
                    } else {
                        blur = 'blur(0px)';
                    }

                }

                if (grayscale == 'grayscale()' || ($("#grayscale-filter-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        grayscale = '';
                    } else {
                        grayscale = 'grayscale(0)';
                    }

                }

                if (brightness == 'brightness()' || ($("#brightness-filter-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        brightness = '';
                    } else {
                        brightness = 'brightness(1)';
                    }

                }

                if (contrast == 'contrast()' || ($("#contrast-filter-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        contrast = '';
                    } else {
                        contrast = 'contrast(1)';
                    }

                }

                if (hueRotate == 'hue-rotate(deg)' || ($("#hue-rotate-filter-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        hueRotate = '';
                    } else {
                        hueRotate = 'hue-rotate(0deg)';
                    }

                }

                if (saturate == 'saturate()' || ($("#saturate-filter-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        saturate = '';
                    } else {
                        saturate = 'saturate(1)';
                    }

                }

                if (sepia == 'sepia()' || ($("#sepia-filter-group").hasClass("reset-enable") === false && type === true)) {

                    if (is_animate_creator() === false) {
                        sepia = '';
                    } else {
                        sepia = 'sepia(0)';
                    }

                }

                // All data.
                var filterData = $.trim(blur + " " + brightness + " " + contrast + " " + grayscale + " " + hueRotate + " " + saturate + " " + sepia);

                if (filterData === '' || filterData == ' ') {
                    filterData = 'disable';
                }

                return filterData;

            }


            /* ---------------------------------------------------- */
            /* Box Shadow generating                                */
            /* ---------------------------------------------------- */
            function box_shadow_generator(selector){

                // Get inset option
                var inset = '';
                if ($("#box-shadow-inset-inset").parent().hasClass("active")) {
                    inset = 'inset';
                }

                // Getting all other options.
                var color = $.trim($("#wyp-box-shadow-color").val());
                var vertical = $.trim($("#wyp-box-shadow-vertical").val());
                var radius = $.trim($("#wyp-box-shadow-blur-radius").val());
                var spread = $.trim($("#wyp-box-shadow-spread").val());
                var horizontal = $.trim($("#wyp-box-shadow-horizontal").val());

                if ($("#box-shadow-color-group .wyp-disable-btn").hasClass("active") || $("#box-shadow-color-group .wyp-none-btn").hasClass("active")) {
                    color = get_color(iframe.find(selector).css("color"));
                }

                if ($("#box-shadow-vertical-group .wyp-disable-btn").hasClass("active")) {
                    vertical = '0';
                }

                if ($("#box-shadow-blur-radius-group .wyp-disable-btn").hasClass("active")) {
                    radius = '0';
                }

                if ($("#box-shadow-spread-group .wyp-disable-btn").hasClass("active")) {
                    spread = '0';
                }

                if ($("#box-shadow-horizontal-group .wyp-disable-btn").hasClass("active")) {
                    horizontal = '0';
                }

                var shadowData = $.trim(horizontal + "px " + vertical + "px " + radius + "px " + spread + "px " + color + " " + inset);

                if(horizontal == 0 && vertical == 0 && radius == 0 && spread == 0){
                    shadowData = 'none';
                }

                return shadowData;

            }


            /* ---------------------------------------------------- */
            /* Delete live CSS                                      */
            /* ---------------------------------------------------- */
            function delete_live_css(id, custom) {

                // Style id
                var styleId;
                if (custom !== false && custom !== undefined) {
                    styleId = custom;
                } else {
                    styleId = "#" + id + "-live-css";
                }

                var element = iframe.find(styleId);

                if (element.length > 0) {
                    element.remove();
                }

            }


            /* ---------------------------------------------------- */
            /* iris color picker helper                             */
            /* ---------------------------------------------------- */
            mainDocument.on("mousemove", function(){

                var element,css,val;

                if ($(".iris-dragging").length > 0) {

                    element = $(".iris-dragging").parents(".wyp-option-group");

                    css = element.data("css");
                    val = element.find(".wqcolorpicker").val();

                    if(css != 'background-image'){
                        delete_live_css(css, false);
                        insert_live_css(css, val, false);
                    }

                    if($(".fake-layer").length === 0){
                        mainBody.append("<div class='fake-layer'></div>");
                    }

                }

                if ($(".iris-slider").find(".ui-state-active").length > 0) {

                    element = $(".iris-slider").find(".ui-state-active").parents(".wyp-option-group");

                    css = element.data("css");
                    val = element.find(".wqcolorpicker").val();

                    if(css != 'background-image'){
                        delete_live_css(css, false);
                        insert_live_css(css, val, false);
                    }

                    if($(".fake-layer").length === 0){
                        mainBody.append("<div class='fake-layer'></div>");
                    }

                }

                if ($(".cs-alpha-slider").find(".ui-state-active").length > 0) {

                    element = $(".cs-alpha-slider").find(".ui-state-active").parents(".wyp-option-group");

                    css = element.data("css");
                    val = element.find(".wqcolorpicker").val();

                    if(css != 'background-image'){
                        delete_live_css(css, false);
                        insert_live_css(css, val, false);
                    }

                    if($(".fake-layer").length === 0){
                        mainBody.append("<div class='fake-layer'></div>");
                    }

                }

            });


            /* ---------------------------------------------------- */
            /* Iris color picker insert Color                       */
            /* ---------------------------------------------------- */
            mainDocument.on("mouseup", function(event) {

                var element;

                if ($(document).find(".iris-dragging").length > 0) {

                    element = $(".iris-dragging").parents(".wyp-option-group");

                    element.find(".wqcolorpicker").trigger("change");

                    $(".fake-layer").remove();

                    if(element.attr("id") == 'background-image-group'){
                        update_gradient("insert");
                    }

                } else if ($(document).find(".iris-slider .ui-state-active").length > 0) {

                    element = $(".ui-state-active").parents(".wyp-option-group");

                    element.find(".wqcolorpicker").trigger("change");

                    $(".fake-layer").remove();

                    if(element.attr("id") == 'background-image-group'){
                        update_gradient("insert");
                    }

                } else if ($(document).find(".cs-alpha-slider .ui-state-active").length > 0) {

                    element = $(".cs-alpha-slider .ui-state-active").parents(".wyp-option-group");

                    $(".fake-layer").remove();

                    if(element.attr("id") == 'background-image-group'){
                        update_gradient("insert");
                    }

                } else if($(event.target).hasClass("iris-square-handle")){

                    element = $(event.target).parents(".wyp-option-group");

                    element.find(".wqcolorpicker").trigger("change");

                    $(".fake-layer").remove();

                    if(element.attr("id") == 'background-image-group'){
                        update_gradient("insert");
                    }

                }

            });


            /* ---------------------------------------------------- */
            /* Color Event                                          */
            /* ---------------------------------------------------- */
            function color_option(id) {

                // Color picker on blur
                $("#wyp-" + id).on("blur", function() {

                    // If empty, set disable.
                    if ($(this).val() == '') {
                        return false;
                    }

                });

                // Show picker on click
                $("#wyp-" + id).on("click", function() {

                    $(this).parent().parent().find(".iris-picker").show();
                    $(this).parent().parent().parent().css("opacity", 1);
                    gui_update();

                });

                // disable to true.
                $("#" + id + "-group").find(".wyp-after a").on("click", function() {
                    $(this).parent().parent().parent().css("opacity", 1);
                });

                // Update on keyup
                $("#wyp-" + id).on("keydown keyup", function() {
                    $(this).parent().find(".wqminicolors-swatch-color").css("backgroundColor", $(this).val());
                });

                // Color picker on change
                $("#wyp-" + id).on('change', function() {

                    var css = $(this).parent().parent().parent().data("css");
                    $(this).parent().parent().parent().addClass("reset-enable");
                    var val = $(this).val();

                    if (val.indexOf("#") == -1 && val.indexOf("rgb") == -1) {
                        val = "#" + val;
                    }

                    // Disable
                    $(this).parent().parent().find(".wyp-btn-action.active").trigger("click");

                    if (val.length < 3) {
                        val = 'transparent';
                        $(this).parent().parent().find(".wyp-none-btn:not(.active)").trigger("click");
                    }

                    // Set for demo
                    delete_live_css(css, false);

                    insert_rule(null, id, val, '');

                    // Option Changed
                    option_change();

                });

            }

            /* ---------------------------------------------------- */
            /* Input Event                                          */
            /* ---------------------------------------------------- */
            function input_option(id) {

                // Keyup
                $("#wyp-" + id).on('keyup', function() {

                    $(this).parent().parent().addClass("reset-enable");

                    var val = $(this).val();

                    // Disable
                    $(this).parent().find(".wyp-btn-action.active").trigger("click");

                    if (val == 'none') {
                        $(this).parent().parent().find(".wyp-none-btn").not(".active").trigger("click");
                        $(this).val('');
                    }

                    if (val == 'disable') {
                        $(this).parent().parent().find(".wyp-disable-btn").not(".active").trigger("click");
                        $(this).val('');
                    }

                    // Background image
                    if (id == 'background-image' && val.indexOf("linear-gradient(") == -1) {

                        val = val.replace(/\)/g, '').replace(/\url\(/g, '');

                        $(this).val(val);

                        val = 'url(' + val + ')';

                        $(".wyp-background-image-show").remove();

                        var imgSrc = val.replace(/"/g, "").replace(/'/g, "").replace(/url\(/g, "").replace(/\)/g, "");

                        if (val.indexOf("yellow-pencil") == -1) {

                            if (imgSrc.indexOf("//") != -1 && imgSrc != '' && imgSrc.indexOf(".") != -1) {
                                $("#wyp-background-image").after("<img src='" + imgSrc + "' class='wyp-background-image-show' />");
                            }

                        }

                    }

                    // List Style image
                    if (id == 'list-style-image') {

                        val = val.replace(/\)/g, '').replace(/\url\(/g, '');

                        $(this).val(val);

                        val = 'url(' + val + ')';

                    }

                    // Remove active pattern if not have pattern more.
                    if(id == 'background-image' && val.indexOf("yellow-pencil") == -1){
                        $(".wyp_bg_assets.active").removeClass("active");
                    }

                    // Add
                    if(id == 'background-image' && val.indexOf("yellow-pencil") != -1){
                        $(".wyp_bg_assets.active").addClass("active");
                    }

                    // Set for demo
                    insert_rule(null, id, val, '');

                    // Option Changed
                    option_change();

                });

            }


            /* ---------------------------------------------------- */
            /* Clean data that not selected yet.                    */
            /* ---------------------------------------------------- */
            function simple_clean(){

                // Clean basic classes
                body.removeAttr("data-clickable-select").removeAttr("data-wyp-selector").removeClass("wyp-element-list wyp-element-float yp-selector-focus yp-selector-hover yp-selector-active yp-selector-link yp-selector-visited wyp-css-data-trigger wyp-content-selected wyp-body-select-just-it wyp-has-transform wyp-element-resizing wyp-element-resizing-height-top wyp-element-resizing-height-bottom wyp-element-resizing-width-left wyp-element-resizing-width-right wyp-visual-editing wyp-visual-editing-x wyp-visual-editing-y");

                // for html overflow hidden on resizing
                iframe.find("html").removeClass("wyp-element-resizing");

                // Clean classes from selected element
                iframe.find(".wyp-selected,.wyp-selected-others").removeClass("ui-draggable ui-draggable-handle ui-draggable-handle wyp-selected-has-transform");

                // Remove wyp-selected classes
                iframe.find(".wyp-selected-others,.wyp-selected").removeClass("wyp-selected-others").removeClass("wyp-selected");

                // Remove created elements
                iframe.find(".wyp-edit-menu,.wyp-edit-tooltip,.wyp-selected-handle,.wyp-selected-others-box,.wyp-selected-tooltip,.wyp-selected-boxed-top,.wyp-selected-boxed-left,.wyp-selected-boxed-right,.wyp-selected-boxed-bottom,.wyp-selected-boxed-margin-top,.wyp-selected-boxed-margin-left,.wyp-selected-boxed-margin-right,.wyp-selected-boxed-margin-bottom,.selected-just-it-span,.wyp-selected-boxed-padding-top,.wyp-selected-boxed-padding-left,.wyp-selected-boxed-padding-right,.wyp-selected-boxed-padding-bottom,.wyp-live-css,.wyp-selected-tooltip span").remove();

                // Update
                if(mainBody.hasClass("wyp-select-just-it") === false){
                    window.selectorClean = null;
                }

                // Update informations
                if($(".advanced-info-box").css("display") == 'block' && $(".element-btn").hasClass("active")){
                    $(".info-element-selected-section").hide();
                    $(".info-no-element-selected").show();
                }

                $(".wyp-disable-btn.active").removeClass("active");

                // Remove parent trigger class.
                iframeBody.find(".wyp-selected-parents").removeClass("wyp-selected-parents");

            }



            /* ---------------------------------------------------- */
            /* Gradient Generator Start                             */
            /* ---------------------------------------------------- */
            $(document).on("click", ".wyp-gradient-pointer-area", function(e) {

                // Not action if picker open
                if ($(".iris-picker:visible").length !== 0) {
                    return true;
                }

                // only blank area clicks are valid
                if ($(e.target).hasClass("wyp-gradient-pointer") || $(e.target).hasClass("wyp-gradient-pointer-color")) {
                    return false;
                }

                // gradient pointer area
                var area = $(".wyp-gradient-pointer-area");

                // Getting pointer area width
                var areaWidth = area.width();

                // Getting pointer area offset
                var areaOffset = area.offset();
                var leftOffset = areaOffset.left;

                // rel x in px
                var deg = e.pageX - leftOffset;

                // find relX in % format
                deg = parseInt(deg/areaWidth*100);

                $(".wyp-gradient-pointer").removeClass("active");

                // get color
                var color = "#FF5253";
                if($("#iris-gradient-color").val().length == 7){
                    color = $("#iris-gradient-color").val();
                }

                // pointer html Template
                var pointerTemplate = '<div class="wyp-gradient-pointer active" data-color="'+color+'" data-position="'+deg+'" style="left:'+deg+'%;"><i class="wyp-gradient-pointer-color" style="background-color:'+color+';"></i></div>';

                // Append pointer
                area.append(pointerTemplate);

                // Adds draggable support to pointers
                update_gradient_pointers();

                // Update after append
                update_gradient("insert");

            });


            /* ---------------------------------------------------- */
            /* Draggable gradient pointers                          */
            /* ---------------------------------------------------- */
            $(".wyp-gradient-orientation i").draggable({

                containment: "parent",

                start: function(e,ui){
                },

                drag: function(e,ui){

                    var ori = $(".wyp-gradient-orientation");

                    var offset = ori.offset();

                    var center_x = (offset.left) + (ori.width()/2);
                    var center_y = (offset.top) + (ori.height()/2);

                    var x = ui.offset.left;
                    var y = ui.offset.top;

                    var radians = Math.atan2(x - center_x, y - center_y);
                    var degree = (radians * (180 / Math.PI) * -1);

                    $(this).parent().attr("data-degree",parseInt(degree));

                    update_gradient("live");

                },

                stop: function(e,ui){
                    update_gradient("insert");
                }

            });


            window.blockIrıs = false;

            /* ---------------------------------------------------- */
            /* Updating gradient                                    */
            /* ---------------------------------------------------- */
            function update_gradient_pointers(){

                // gradient pointer area
                var area = $(".wyp-gradient-pointer-area");

                // Getting pointer area width
                var areaWidth = area.width();

                // Getting pointer area offset
                var areaOffset = area.offset();
                var leftOffset = areaOffset.left;
                var topOffset = areaOffset.top;

                // Draggable gradient pointers
                $(".wyp-gradient-pointer").draggable({

                    containment: [leftOffset, topOffset, (leftOffset+areaWidth), topOffset],

                    start: function(e,ui){
                        $(".wyp-gradient-pointer").removeClass("active");
                        $(this).addClass("active");

                        //Block iris. not let to open while dragging
                        window.blockIrıs = true;

                        // Hide iris if open
                        $(".wyp-gradient-section .iris-picker").hide();

                        window.gradientPointerTop = $(this).offset().top;
                        window.gradientPointerLen = $(".wyp-gradient-pointer:not(.disable)").length;

                    },

                    drag: function(e,ui){

                        // Add class to parent
                        area.addClass("gradient-pointer-no-cursor");

                        // update pointer position
                        $(this).attr("data-position",parseInt(ui.position.left/areaWidth*100));

                        // disable
                        if(window.gradientPointerLen > 2){

                            if(e.pageY < (window.gradientPointerTop-20) || e.pageY > (window.gradientPointerTop+20)){
                                $(this).addClass("disable");
                            }else{
                                $(this).removeClass("disable");
                            }

                        }

                        // Updating view and data
                        update_gradient("live");

                    },

                    stop: function(e,ui){

                        // remove class from parent
                        area.removeClass("gradient-pointer-no-cursor");

                        // update pointer position
                        $(this).attr("data-position",parseInt(ui.position.left/areaWidth*100));

                        // Updating view and data
                        update_gradient("insert");

                        window.blockIrıs = false;

                    },

                    axis: "x"

                });

            }

            /* ---------------------------------------------------- */
            /* iris picker for gradient                             */
            /* ---------------------------------------------------- */
            $("#iris-gradient-color").cs_iris({

                hide:true,
                width:wIris

            });


            /* ---------------------------------------------------- */
            /* iris color picker global callback                    */
            /* ---------------------------------------------------- */
            window.iris_global_change_callback = function(event,ui){

                // if this is gradient color picker
                if($(".wyp-gradient-section .iris-picker:visible").length > 0){

                    // Update the pointer
                    $(".wyp-gradient-pointer.active i").css("background-color",ui.color.toString());
                    $(".wyp-gradient-pointer.active").attr("data-color",ui.color.toString());

                    // insert gradint CSS before close picker
                    update_gradient("live");

                }

            }


            /* ---------------------------------------------------- */
            /* ContextMenu on gradient pointers                     */
            /* ---------------------------------------------------- */
            $(document).on("click contextmenu", ".wyp-gradient-pointer", function(e) {

                $(".wyp-gradient-pointer").removeClass("active");
                $(this).addClass("active");

            });


            /* ---------------------------------------------------- */
            /* Double click support to gradient pointers            */
            /* ---------------------------------------------------- */
            $(document).on("dblclick", ".wyp-gradient-pointer", function(e) {

                if(window.blockIrıs == true){
                    return false;
                }

                // get color
                var color = $(this).attr("data-color");

                window.gradientlastColor = color;

                $(".wyp-gradient-pointer").removeClass("active");

                $(this).addClass("active");

                $(".wyp-gradient-pointer-area").addClass("gradient-pointer-no-cursor");

                $("#iris-gradient-color").iris("color",color);

                // If rgba
                var alpha = 100;
                if(color.indexOf("rgba") != -1){

                    alpha = $.trim(color.replace(/^.*,(.+)\)/,'$1'));

                        if(alpha.indexOf(".") != -1){

                            alpha = alpha.replace("000.","").replace("00.","").replace("0.","").replace(".","");

                            if(alpha.length == 1){
                                alpha = alpha.toString()+"0";
                            }

                            alpha = alpha.replace(/^0/, "");
                        }

                    }

                // Update iris alpha.
                $(".wyp-gradient-section .iris-picker .cs-alpha-slider").slider('value',alpha);

                $(".wyp-gradient-section .iris-picker").show();

            });


            /* ---------------------------------------------------- */
            /* CSS To Gradient View                                 */
            /* ---------------------------------------------------- */
            function read_gradient(value){

                // Variables
                var gradientObject,Colortype,color,lengthType,length,lengthFormat,lengthSpace,pointerTemplate,code,direction,directionType;

                // Cleans value.
                value = value.replace(/\s+?!important/g,'').replace(/\;$/g,'').trim();

                // No direction gradient for gradient bar
                code = 'linear-gradient(to right,';

                // Be sure value is a gradient
                if(value.indexOf("linear-gradient(") == -1){
                    return false;
                }

                // Parse gradient with a javascript plugin.
                gradientObject = GradientParser.parse(value)[0];

                // getting direction type
                directionType = gradientObject.orientation.type;

                // getting direction value
                direction = gradientObject.orientation.value;

                // covert directional value to deg
                if(directionType == 'directional'){

                    // Directional value to deg
                    if(direction == 'top'){
                        direction = '0';
                    }else if(direction == 'right'){
                        direction = '90';
                    }else if(direction == 'bottom'){
                        direction = '180';
                    }else if(direction == 'left'){
                        direction = '270';
                    }else if(direction == 'top'){
                        direction = '360';
                    }

                }

                // Update direction data
                $(".wyp-gradient-orientation").attr("data-degree",direction);

                // Empty bar
                $(".wyp-gradient-pointer-area").empty();

                // Each color stops
                for(var i = 0; i < gradientObject.colorStops.length; i++){

                    if(isDefined(gradientObject.colorStops[i])){

                        // Length Type
                        lengthType = gradientObject.colorStops[i].length.type;

                        // Skip if not %
                        if(lengthType != '%'){
                            return true;
                        }

                        // Length
                        length = gradientObject.colorStops[i].length.value;
                        lengthFormat = '%';
                        lengthSpace = ' ';

                    }else{

                        // Auto length
                        length = (i*100/(gradientObject.colorStops.length-1));
                        lengthFormat = '%';
                        lengthSpace = ' ';

                    }

                    // Int
                    length = parseInt(length);

                    // Color type
                    Colortype = gradientObject.colorStops[i].type;

                    // Color
                    color = gradientObject.colorStops[i].value;

                    // CSS Color
                    if(Colortype == 'rgb' || Colortype == 'rgba'){
                        color = Colortype + "(" + color + ")";
                    }

                    if(Colortype == 'hex'){
                        color = '#' + color;
                    }

                    // Set first color as default color
                    if(i == 0){
                        $("#iris-gradient-color").val(color);
                    }

                    // add colorStops
                    code += ' ' + color + lengthSpace + length + lengthFormat;

                    // add if not last.
                    if(gradientObject.colorStops.length-1 != i){
                        code += ",";
                    }

                    // pointer html Template
                    pointerTemplate = '<div class="wyp-gradient-pointer" data-color="'+color+'" data-position="'+length+'" style="left:'+length+''+lengthFormat+';"><i class="wyp-gradient-pointer-color" style="background-color:'+color+';"></i></div>';

                    // Append pointer
                    $(".wyp-gradient-pointer-area").append(pointerTemplate);

                }

                code += ')';

                // Remove old style
                $("#gradient-bar-view-style").remove();

                // Add new
                mainBody.append('<style id="gradient-bar-view-style">.wyp-gradient-bar{background-image:'+code+';}.wyp-gradient-orientation{background-image:'+value+';}</style>');

                // Add support
                setTimeout(function(){
                    update_gradient_pointers();
                },26);

            }


            /* ---------------------------------------------------- */
            /* Gradient View to CSS Code                            */
            /* ---------------------------------------------------- */
            function update_gradient(type){

                // Getting direction
                var direction = $(".wyp-gradient-orientation").attr("data-degree") + "deg";

                if(direction == '0deg'){
                    direction = 'to top';
                }else if(direction == '90deg'){
                    direction = 'to right';
                }else if(direction == '180deg'){
                    direction = 'to bottom';
                }else if(direction == '270deg'){
                    direction = 'to left';
                }else if(direction == '360deg'){
                    direction = 'to top';
                }

                // Linear gradient
                var codeBar = 'linear-gradient(to right,';
                var code = 'linear-gradient('+direction+',';

                // Sort points by position
                $(".wyp-gradient-pointer-area .wyp-gradient-pointer:not(.disable)").sort(function(a, b) {
                    return +a.dataset.position - +b.dataset.position;
                }).appendTo(".wyp-gradient-pointer-area");

                // Each all pointers
                $(".wyp-gradient-pointer-area .wyp-gradient-pointer:not(.disable)").each(function(i){

                    // Element
                    var pointer = $(this);

                    // Getting pointer data
                    var color = pointer.attr("data-color");
                    var position = pointer.attr("data-position");

                    // Adds to CSS
                    code += ' ' + color + ' ' + parseInt(position) + '%';
                    codeBar += ' ' + color + ' ' + parseInt(position) + '%';

                    // add if not last.
                    if($(".wyp-gradient-pointer:not(.disable)").length-1 != i){
                        code += ",";
                        codeBar += ",";
                    }

                });

                code += ')';
                codeBar += ')';

                // Remove old style
                $("#gradient-bar-view-style").remove();

                // Add new
                mainBody.append('<style id="gradient-bar-view-style">.wyp-gradient-bar{background-image:'+codeBar+';}.wyp-gradient-orientation{background-image:'+code+';}</style>');

                // Update CSS
                if(type == 'live'){
                    delete_live_css("background-image",false);
                    insert_live_css("background-image",code,false);
                    $("#wyp-background-image").val(code);
                }else if(type == 'insert'){
                    delete_live_css("background-image",false);
                    $("#wyp-background-image").val(code).trigger("keyup");
                }

            }


            /* ---------------------------------------------------- */
            /* Disable right click on gradient parts                */
            /* ---------------------------------------------------- */
            $(".wyp-gradient-pointer-area,.wyp-gradient-bar").on("contextmenu", function(e){

                // right click allowed just on pointer
                if ($(e.target).hasClass("wyp-gradient-pointer") == false && $(e.target).hasClass("wyp-gradient-pointer-color") == false) {
                    return false;
                }

            });



            /* ---------------------------------------------------- */
            /* Clean previous changes, settings                     */
            /* ---------------------------------------------------- */
            function clean() {

                // Use wyp_simple_clean function for simple clean data.
                if(is_content_selected() === false){
                    simple_clean();
                    return false;
                }else{

                    // Stop if dragging
                    if (is_dragging()){
                        return false;
                    }

                    // Hide if close while playing an animate.
                    if(body.hasClass("wyp-force-hide-select-ui")){
                        body.removeClass("wyp-force-hide-select-ui wyp-hide-borders-now");
                    }

                    /* this function remove menu from selected element */
                    if (iframe.find(".context-menu-active").length > 0) {
                        get_selected_element().contextMenu("hide");
                    }

                    // destroy ex element draggable feature.
                    if (iframe.find(".wyp-selected.ui-draggable").length > 0){
                        get_selected_element().draggable("destroy");
                    }

                    // Clean lock button active classes
                    $(".lock-btn").removeClass("active");

                    // Clean popovers.
                    $("#margin-left-group,#margin-right-group,#margin-top-group,#margin-bottom-group,#padding-left-group,#padding-right-group,#padding-top-group,#padding-bottom-group,#background-color-group,#background-size-group,#background-repeat-group,#background-blend-mode-group,#background-attachment-group,#background-position-group,#box-shadow-color-group,#animation-name-group,#list-style-position-group,#list-style-image-group,#list-style-type-group").popover("destroy");

                    // close open menu
                    $(".editor-panel-list > li.active > h3").trigger("click");

                    // Dont stop playing animate
                    if(mainBody.hasClass("wyp-animate-manager-playing") === false){
                        iframe.find(".yp_onscreen,.yp_hover,.yp_click,.yp_focus").removeClass("yp_onscreen yp_hover yp_click yp_focus");
                    }

                    // Remove classes
                    $(".reset-enable").removeClass("reset-enable");

                    // Update panel
                    $(".wyp-option-group").css("opacity", "1");
                    $(".wyp-after").css("display", "block");

                    // delete cached data.
                    $("li[data-loaded]").removeAttr("data-loaded");

                    // copied by iframe click select section.
                    $(".editor-panel-list > li.active > h3").trigger("click");

                    $(".input-autocomplete").removeAttr("style");
                    $(".wyp-disable-contextmenu").removeClass("wyp-disable-contextmenu");
                    $(".wyp-active-contextmenu").removeClass("wyp-active-contextmenu");

                    // Cancel if animater active
                    if(is_animate_creator()){
                        close_animation_generator(true);
                    }

                    // Hide some elements from panel
                    $(".wyp-transform-area,.wyp-filter-area").hide();
                    $(".wyp-on").removeClass("wyp-on");

                    simple_clean();

                    // Editor Panel Resetting
                    $(".iris-picker,.wyp-border-top-section,.wyp-border-right-section,.wyp-border-bottom-section,.wyp-border-left-section").hide();
                    $(".wyp-border-all-section").show();
                    $(".wyp-gradient-pointer-area").removeClass("gradient-pointer-no-cursor");
                    $(".wyp_background_assets,.wyp-gradient-section,.wyp_nice_colors_area,.wyp_meterial_colors_area,.wyp_flat_colors_area").hide();
                    $(".wyp-bg-img-btn,.wyp-flat-colors,.wyp-meterial-colors,.wyp-nice-colors,.wyp-gradient-pointer,.wyp-gradient-btn,#border-type-group .wyp-radio.active").removeClass("active");

                    // Disable color picker.
                    mainBody.removeClass("wyp-element-picker-active");
                    $(".wyp-element-picker").removeClass("active");

                    gui_update();

                    // Stop re-parent updating.
                    window.parentItems = '';

                }

            }


            /* ---------------------------------------------------- */
            /* Data element                                         */
            /* ---------------------------------------------------- */
            function the_editor_data(){
                return iframe.find(".wyp-styles-area");
            }


            /* ---------------------------------------------------- */
            /* Getting Stylizer data                                */
            /* ---------------------------------------------------- */
            function get_editor_data(type) {
                var data = iframe.find("[data-source-mode='"+type+"']").html();
                data = data.replace(/</g, 'YP|@');
                data = data.replace(/>/g, 'YP@|');
                return data;
            }


            /* ---------------------------------------------------- */
            /* Getting cleaned CSS data                             */
            /* ---------------------------------------------------- */
            function get_clean_css(a, type) {

                // Default current
                var editorData = the_editor_data();

                // If has type, get data by type
                if(type != null){
                    editorData = iframe.find("[data-source-mode='"+type+"']");
                }

                // Getting data
                var data = get_css_by_screensize('desktop', type);

                // Convert interactives YP Styles to default for show.
                data = data.replace(/\.wyp-selected(.*?){/g,'{');
                data = data.replace(/body\.yp-selector-(.*?) (.*?){/g, "$2:$1{");

                // clean webkit filter, transform.
                data = data.replace(/(\t)?-webkit-(transform|filter):(.*?);(\n|\r)?/g, "");

                // Adding break
                data = data.replace(/\)\{/g, "){\r").replace(/\)\{/g, "){\r");

                // Clean spaces for nth-child and not.
                var ars = Array(
                    "nth-child",
                    "not",
                    "lang",
                    "nth-last-child",
                    "nth-last-of-type",
                    "nth-of-type"
                );

                for(var ai = 0; ai < ars.length; ai++){

                    // Reg
                    var k = new RegExp(ars[ai] + "\\((.*?)\\)\{\r\r", "g");

                    // Replace
                    data = data.replace(k, ars[ai] + "\($1\)\{");

                }

                if (editorData.find(".wyp_current_styles").length > 0) {

                    var mediaArray = [];

                    editorData.find(".wyp_current_styles").each(function() {
                        var v = $(this).attr("data-size-mode");

                        if ($.inArray(v, mediaArray) === -1 && v != 'desktop') {
                            mediaArray.push(v);
                        }
                    });

                    $.each(mediaArray, function(i, v) {

                        var q = get_css_by_screensize(v);

                        // Add extra tab for media query content.
                        q = "\t" + q.replace(/\r/g, '\r\t').replace(/\t$/g, '').replace(/\t$/g, '');

                        if (v == 'tablet') {
                            v = '(min-width: 768px) and (max-width: 991px)';
                        }

                        if (v == 'mobile') {
                            v = '(max-width:767px)';
                        }

                        if(isDefined(v)){
                            data = data + "\r\r@media " + v + "{\r\r" + q + "}";
                        }

                    });

                }

                if (a === true) {
                    data = data.replace(/\r\ta:a !important;/g, "");
                    data = data.replace(/a:a !important;/g, "");
                    data = data.replace(/a:a;/g, "");
                }

                // Clean first empty lines.
                data = data.replace(/^\r/g, '').replace(/^\r/g, '');
                data = data.replace(/\}\r\r\r\r@media/g, '}\r\r@media');

                return data;

            }


            /* ---------------------------------------------------- */
            /* Getting CSS styles by selector                       */
            /* ---------------------------------------------------- */
            function get_css_by_screensize(size, type) {

                // Default current
                var editorData = the_editor_data();

                // If has type, get data by type
                if(type != null){
                    editorData = iframe.find("[data-source-mode='"+type+"']");
                }

                if (editorData.find(".wyp_current_styles").length <= 0) {
                    return '';
                }

                var totalCreated, classes, selector, data;

                totalCreated = '';

                editorData.find(".wyp_current_styles:not(.wyp_step_end)[data-size-mode='" + size + "']").each(function() {

                    if (!$(this).hasClass("wyp_step_end")) {

                        selector = esc_data_selector($(this).first().html());

                        totalCreated += selector + "{\r";

                        classes = $(this).data("style");

                        editorData.find("style[data-style=" + classes + "][data-size-mode='" + size + "']").each(function() {

                            totalCreated += "\t" + esc_data_content($(this).first().html()) + ';\r';

                            $(this).addClass("wyp_step_end");

                        });

                        totalCreated += "}\r\r";

                        $(this).addClass("wyp_step_end");

                    }

                });

                iframe.find(".wyp_step_end").removeClass("wyp_step_end");

                return totalCreated;

            }


            /* ---------------------------------------------------- */
            /* Toggle background image show element                 */
            /* ---------------------------------------------------- */
            $("#background-image-group .wyp-none-btn,#background-image-group .wyp-disable-btn").click(function(e) {
                if(e.originalEvent){
                    $("#background-image-group .wyp-background-image-show").toggle();
                }
            });


            /* ---------------------------------------------------- */
            /* Fix box shadow inset option                          */
            /* ---------------------------------------------------- */
            $("#box-shadow-inset-group .wyp-disable-btn").click(function(e) {
                if(e.originalEvent){
                    $("#box-shadow-inset-inset").parent().removeClass("active");
                    $("#box-shadow-inset-no").parent().addClass("active");
                }
            });

            /* ---------------------------------------------------- */
            /* Remove disable button if inset no selected           */
            /* ---------------------------------------------------- */
            $("#box-shadow-inset-no").click(function(e) {
                if(e.originalEvent){
                    $("#box-shadow-inset-group").removeClass("reset-enable");
                }
            });


            /* ---------------------------------------------------- */
            /* Getting duration MS from CSS Duration                */
            /* ---------------------------------------------------- */
            function duration_ms(durations){

                durations = durations.toString();
                durations = durations.replace(/ms/g,"");

                // result
                var duration = 0;
                var ms;

                // Is multi durations?
                if(durations.indexOf(",") != -1){

                    var durationsArray = durations.split(",");

                    for(var i = 0; i < durationsArray.length; i++){

                        var val = durationsArray[i];

                        // Has dot?
                        if(val.indexOf(".") != -1){

                            ms = parseFloat(val).toString().split(".")[1].length;
                            val = val.replace(".","").toString();

                            if(ms == 2){
                                val = val.replace(/s/g, "0");
                            }else if(ms == 1){
                                val = val.replace(/s/g, "00");
                            }

                        }else{
                            val = val.replace(/s/g, "000");
                        }

                        duration = parseFloat(duration) + parseFloat(val);

                    }

                    return duration;

                }else{

                    // Has dot?
                    if(durations.indexOf(".") != -1){

                        ms = parseFloat(durations).toString().split(".")[1].length;
                        durations = durations.replace(".","").toString();

                        if(ms == 2){
                            durations = durations.replace(/s/g, "0");
                        }else if(ms == 1){
                            durations = durations.replace(/s/g, "00");
                        }

                    }else{
                        durations = durations.replace(/s/g, "000");
                    }

                    return durations;

                }

            }


            /* ---------------------------------------------------- */
            /* Get inserted style by selector and rule              */
            /* ---------------------------------------------------- */
            function get_applied_style_data(selector, css, options){

                // Getting default selector
                if(selector == null){
                    selector = get_current_selector();
                }

                // options
                var allowFilters = options[0];
                var check = options[1];
                var returnSize = options[2];

                // css rules
                var cssOriginal = css;
                var css2 = get_css_id(css)[1];
                css = get_css_id(css)[0];

                // Special check for Animation Generator
                if(is_animate_creator()){

                    // Get active scene
                    var activeScene = $(".scene-active").attr("data-scene");

                    // Check if there is a data in current scene by this CSS Rule.
                    var style = iframe.find(".wyp-anim-scenes ." + activeScene + " " + 'style[data-rule="'+css+'"]');

                    // get value
                    if(style.length > 0){
                        var value = esc_data_value(style.html());
                    }

                    // if check mode
                    if(check){

                        // no style
                        if(style.length == 0){

                            $("#" + cssOriginal + "-group").removeClass("reset-enable");
                            return false;

                        // have style
                        }else{

                            // Check if CSS part included to value.
                            if(css2 != null && css != 'box-shadow'){

                                // clean all empty values.
                                value = value.replace(/(scale\(1\)|rotate\(0deg\)|rotatex\(0deg\)|rotatey\(0deg\)|rotatez\(0deg\)|translatex\(0px\)|translatey\(0px\)|skewx\(0deg\)|skewy\(0deg\)|blur\(0px\)|brightness\(1\)|contrast\(1\)|grayscale\(0\)|hue-rotate\(0deg\)|saturate\(1\)|sepia\(0\))/g, '');

                                if(new RegExp(css2+"\\(", "g").test(value) == false){
                                    $("#" + cssOriginal + "-group").removeClass("reset-enable");
                                    return false;
                                }

                            }

                            // box shadow undo button checks
                            if(box_shadow_check(css, css2, selector + "{" + css + ":" + value + "}", check) != false){
                                $("#" + cssOriginal + "-group").removeClass("reset-enable");
                                return false;
                            }

                            // if pass, true.
                            return true;

                        }

                    }

                    // Stop
                    if(!value){
                        return false;
                    }

                    // deferred because Firefox bug.
                    var deferred = jQuery.Deferred();

                    // Finds which selector work and get valuewith CSS Testing.
                    if(returnSize == false){
                        deferred.resolve(value);
                    }else{
                        deferred.resolve("all");
                    }

                    // return promise.
                    return deferred.promise();

                }

                // Using this CSS to find which CSS selector works.
                var fakeCSS = 'outline';
                var fakeCSSOut = 'outline-width';

                // To get css value
                var CSSRegExp = new RegExp("(\{|\s+|\;)"+css+"(\s+)?:");

                // Getting same properties Data
                var samePropertiesData = the_editor_data().find('[data-rule="' + css + '"]');

                // Default variables
                var size, style, data, mediaData, groups, dataNew, thisSelector, find, thisValue, mediaGroups, contentRules, css2;

                var resultSizeArray = [];
                var resultDataArray = [];

                // Parsing by CSS Codes
                if(samePropertiesData.length == 0 && iframe.find("#wyp-css-data-full").length > 0){

                    // CSS Styles
                    data = iframe.find("#wyp-css-data-full").html();

                    // all data
                    mediaData = get_minimized_css(data, false);

                    // Espace media queries
                    data = get_minimized_css(data, true);

                    // CSS groups
                    groups = data.split("}");

                    // Default all
                    size = 'all';

                    // Each groups
                    $.each(groups, function(index, group){

                        group += "}";

                        // if target CSS not have.
                        if(CSSRegExp.test(group) == false){
                            return true;
                        }

                        // Getting group content
                        var content = group.match(/\{(.*?)\}$/m)[0].toString().replace(/(^\{|\}$)/g,'').trim();

                        // Getting rules
                        contentRules = array_cleaner(content.split(";"));

                        // each rules
                        $.each(contentRules, function(i,v){

                            if(v == ''){
                                return true;
                            }

                            // update value
                            if(v.split(":")[0].toString().trim() == css){

                                thisValue = v.split(":")[1].replace(/(\}|\;|\!important|\!important;)$/g,'').trim();

                                if(v.indexOf("!important") != -1){
                                    return false;
                                }

                            }

                        });

                        // igrone if has px, because computed style already gives in pixel format.
                        if(/\d+px(\s)?(\;|\!important|\})/g.test(thisValue) && allowFilters == true && css != 'transform' && css != 'filter' && css != 'box-shadow'){
                            return true;
                        }

                        // Get Selector
                        thisSelector = esc_data_selector(group);

                        // This Selector
                        find = iframe.find(thisSelector);

                        // igrone if not selecting same elements
                        if(!find.hasClass("wyp-selected")){
                            return true;
                        }

                        // Check if CSS part included to value.
                        if(css2 != null && css != 'box-shadow'){
                            if(new RegExp(css2+"\\(", "g").test(thisValue) == false){
                                return true;
                            }
                        }

                        // box shadow undo button checks
                        if(box_shadow_check(css, css2, thisSelector + "{" + css + ":" + thisValue + "}", check) != false){
                            return true;
                        }

                        // Append Value
                        resultSizeArray.push(size);

                        // Append Data
                        resultDataArray.push(thisSelector + "{" + css + ":" + thisValue + "}");

                    });

                    // Media matching section start ----
                    mediaGroups = mediaData.match(/@media(.*?){(.*?)}}/g);

                    // Checks is valid
                    if(mediaGroups != null){

                        // Each groups
                        $.each(mediaGroups, function(index, Mediagroup){

                            // Get media size
                            size = $.trim(Mediagroup.match(/@media(.*?){/g).toString().replace(/(\{|@media(\s+)?)/g,''));

                            // If media not match, igrone
                            if(!iframeContentWindow.matchMedia(size).matches){
                                return true;
                            }

                            // clean up media queries from groups
                            Mediagroup = Mediagroup.match(/\{(.*?)\}$/g)[0].toString().replace(/(^\{|\}\}$)/g,'').trim() + "}";

                            // get mediagroup's selectors
                            groups = Mediagroup.split("}");

                            // Each css groups
                            $.each(groups, function(index, group){

                                group += "}";

                                // if target CSS not have.
                                if(CSSRegExp.test(group) == false){
                                    return true;
                                }

                                // Getting group content
                                var content = group.match(/\{(.*?)\}$/m)[0].toString().replace(/(^\{|\}$)/g,'').trim();

                                // Getting rules
                                contentRules = array_cleaner(content.split(";"));

                                // each rules
                                $.each(contentRules, function(i,v){

                                    if(v == ''){
                                        return true;
                                    }

                                    // update value
                                    if(v.split(":")[0].toString().trim() == css){

                                        thisValue = v.split(":")[1].replace(/(\}|\;|\!important|\!important;)$/g,'').trim();

                                        if(v.indexOf("!important") != -1){
                                            return false;
                                        }

                                    }

                                });

                                // igrone if has px, because computed style already gives in pixel format.
                                if(/\d+px(\s)?(\;|\!important|\})/g.test(thisValue) && allowFilters == true && css != 'transform' && css != 'filter' && css != 'box-shadow'){
                                    return true;
                                }

                                // Get Selector
                                thisSelector = esc_data_selector(group);

                                // This Selector
                                find = iframe.find(thisSelector);

                                // igrone if not selecting same elements
                                if(!find.hasClass("wyp-selected")){
                                    return true;
                                }

                                // Check if CSS part included to value.
                                if(css2 != null && css != 'box-shadow'){
                                    if(new RegExp(css2+"\\(", "g").test(thisValue) == false){
                                        return true;
                                    }
                                }

                                // box shadow undo button checks
                                if(box_shadow_check(css, css2, thisSelector + "{" + css + ":" + thisValue + "}", check) != false){
                                    return true;
                                }

                                // Append Value
                                resultSizeArray.push(size);

                                // Append Data
                                resultDataArray.push(thisSelector + "{" + css + ":" + thisValue + "}");

                            });

                        });

                    }

                }else{

                    // Filtering @Media
                    samePropertiesData.each(function(index){

                        // Style Element
                        style = $(this);

                        // Style Data
                        data = style.html();

                        // This size
                        size = style.attr("data-size-mode");

                        // Covert desktop term to all.
                        if(size == 'desktop'){
                            size = 'all';
                        }

                        // If media not match, igrone
                        if(!iframeContentWindow.matchMedia(size).matches){
                            return true;
                        }

                        data = esc_data_media(data);

                        // igrone if has px, because computed style already gives in pixel format.
                        if(/\d+px(\s)?(\;|\!important|\})/g.test(data) && allowFilters == true && css != 'transform' && css != 'filter' && css != 'box-shadow'){
                            return true;
                        }

                        // this selector
                        thisSelector = esc_data_selector(data);

                        // This Selector
                        find = iframe.find(thisSelector);

                        // igrone if not selecting same elements
                        if(!find.hasClass("wyp-selected")){
                            return true;
                        }

                        if(thisSelector == esc_data_selector(resultDataArray[index])){
                            resultDataArray.pop();
                        }

                        // Check if CSS part included to value.
                        if(css2 != null && css != 'box-shadow'){
                            if(new RegExp(css2+"\\(", "g").test(esc_data_value(data)) == false){
                                return true;
                            }
                        }

                        // box shadow undo button checks
                        if(box_shadow_check(css, css2, data, check) != false){
                            return true;
                        }

                        // Append Value
                        resultSizeArray.push(size);

                        // Append Data
                        resultDataArray.push(data);

                    });

                }

                // False if not have a data.
                if(resultDataArray.length == 0){
                    return false;
                }

                // Stop here if is check
                if(check == true){

                    if(resultDataArray.length > 0){
                        return true;
                    }else{
                        return false;
                    }

                }

                // deferred because Firefox bug.
                var deferred = jQuery.Deferred();

                if(resultDataArray.length > 1){

                    // Important Tag
                    var hasImportant;

                    // CSS Specificity Calcator
                    var calcatureStyle = '<style id="wyp-calcature-style">';

                    // Each
                    $.each(resultDataArray, function(index, data){

                        // Adding Media
                        if(resultSizeArray[index] != 'all'){
                            calcatureStyle += "@media "+resultSizeArray[index]+"{";
                        }

                        if(/\!important/g.test(data)){
                            hasImportant = ' !important';
                        }else{
                            hasImportant = '';
                        }

                        // Update Style Value
                        if(/\:(.*?);/g.test(data)){
                            calcatureStyle += data.replace(/\:(.*?);/g, ":" + index + 'px solid transparent' + hasImportant + ";");
                        }else if(/\:(.*?)}/g.test(data)){
                            calcatureStyle += data.replace(/\:(.*?)}/g, ":" + index + 'px solid transparent' + hasImportant + ";}").replace("}}","}");
                        }

                        // Using Animation Delay for specificity test.
                        calcatureStyle = calcatureStyle.replace("{"+css+':','{'+fakeCSS+':');

                        // Endding Media
                        if(resultSizeArray[index] != 'all'){
                            calcatureStyle += "}";
                        }

                    });

                    // End
                    calcatureStyle += '</style>';

                    // Append
                    iframeBody.append(calcatureStyle);

                }

                // Sleep
                setTimeout(function(){

                    // Get computed Style ID
                    if(resultDataArray.length > 1){
                        var specificityId = parseInt(get_selected_element().css(fakeCSSOut));
                    }else{
                        var specificityId = 0;
                    }

                    // Finds which selector work and get valuewith CSS Testing.
                    if(returnSize == false){
                        deferred.resolve(esc_data_value(resultDataArray[specificityId]));
                    }else{
                        deferred.resolve(resultSizeArray[specificityId]);
                    }

                    // Remove Appended Style
                    if(resultDataArray.length > 1){
                        iframeBody.find("#wyp-calcature-style").remove();
                    }

                }, window.Yellow2Delay);

                // return promise.
                return deferred.promise();

            }


            // parsing box-shadow CSS to check if this default value or this value changed.
            // will show a undo button if changes.
            // we can't check box-shadow as filter or transform, box-shadow has only numbers,
            // not have scale(1) etc. so we need to parse it.
            function box_shadow_check(css, css2, data, check){

                if(css2 != null && css == 'box-shadow' && check == true){

                    var boxShadow = esc_data_value(data);

                    // No change, skip
                    if(boxShadow == 'none'){
                        return true;
                    }

                    // Box shadow color default value.
                    if (css2 == 'color') {

                        var boxShadowColor = boxShadow;

                        // Hex
                        if (boxShadowColor.indexOf("#") != -1) {

                             if (boxShadowColor.split("#")[1].indexOf("inset") == -1) {
                                boxShadowColor = $.trim(boxShadowColor.split("#")[1]);
                            } else {
                                boxShadowColor = $.trim(boxShadowColor.split("#")[1].split(' ')[0]);
                            }

                        } else {

                            if (boxShadowColor.indexOf("rgb") != -1) {
                                boxShadowColor = boxShadowColor.match(/rgb(.*?)\((.*?)\)/g).toString();
                            }

                        }

                        // default is element's text color.
                        if("#"+(boxShadowColor).trim() == get_color(get_selected_element().css("color")).trim()){
                            return true;
                        }

                    }

                    // split all box-shadow data.
                    var boxShadowNum = boxShadow.replace(/rgb(.*?)\((.*?)\) /g, '').replace(/ rgb(.*?)\((.*?)\)/g, '').replace(/inset /g, '').replace(/ inset/g, '');

                    // shadow horizontal value.
                    if (css2 == 'horizontal' && number_filter(boxShadowNum.split(" ")[0]) == 0) {
                        return true;
                    }

                    // shadow vertical value.
                    if (css2 == 'vertical' && number_filter(boxShadowNum.split(" ")[1]) == 0) {
                        return true;
                    }

                    // shadow radius value.
                    if (css2 == 'blur-radius' && number_filter(boxShadowNum.split(" ")[2]) == 0) {
                        return true;
                    }

                    // shadow spread value.
                    if (css2 == 'spread' && number_filter(boxShadowNum.split(" ")[3]) == 0) {
                        return true;
                    }

                    // if no info about inset, default is no.
                    if (css2 == 'inset' && boxShadow.indexOf("inset") == -1) {
                        return true;
                    }

                }

                return false;

            }


            /* ---------------------------------------------------- */
            /* Espace the media and out media content               */
            /* ---------------------------------------------------- */
            function esc_data_media(data){

                if(data === null || data === undefined || data === ''){
                    return false;
                }

                var content = data.replace(/\/\*(.*?)\*\//g, "");

                if(content.indexOf("@media") != -1){
                    content = content.match(/\{(.*?)\}$/g).toString().replace(/(^\{|\}$)/g,'');
                }

                content = $.trim(content);

                // return
                return content;

            }


            /* ---------------------------------------------------- */
            /* Espace CSS rule & value by CSS code                  */
            /* ---------------------------------------------------- */
            function esc_data_content(data){

                if(data === null || data === undefined || data === ''){
                    return false;
                }

                var content;

                data = data.replace(/\/\*(.*?)\*\//g, "");

                if(data.indexOf("@media") != -1){
                    content = data.split("{")[2].split("}")[0];
                }else{
                    content = data.split("{")[1].split("}")[0];
                }

                content = $.trim(content).replace(/(\s+)?\;$/g,'');

                // return
                return content;

            }


            /* ---------------------------------------------------- */
            /* Espace CSS rule value by CSS code                    */
            /* ---------------------------------------------------- */
            function esc_data_value(data){

                if(data === null || data === undefined || data === ''){
                    return false;
                }

                data = data.replace(/\/\*(.*?)\*\//g, "");

                var value;

                if(data.indexOf("@media") != -1){
                    value = data.split("{")[2].split("}")[0].split(":")[1];
                }else{
                    value = data.split("{")[1].split("}")[0].split(":")[1];
                }

                value = $.trim(value).replace(/((\s+)?\;$|(\s+)?\!important)/g,'');

                // return
                return value;

            }


            /* ---------------------------------------------------- */
            /* Espace CSS selector by CSS code                    */
            /* ---------------------------------------------------- */
            function esc_data_selector(data){

                if(data === null || data === undefined || data === ''){
                    return false;
                }

                data = data.replace(/\/\*(.*?)\*\//g, "");

                var selector = data.split("{")[0];

                if(selector.indexOf("@media") != -1){
                    selector = data.split("{")[1].split("{")[0];
                }

                return selector;

            }


            /* ---------------------------------------------------- */
            /* Get real CSS name and replace fake rules             */
            /* as scale transform. param1: real CSS name,          */
            /* param2: [scale]-transfrom. I.E "scale".              */
            /* ---------------------------------------------------- */
            function get_css_id(css){

                var cssDetail = null;

                // No webkit
                css = css.replace(/\-webkit\-/g,'');

                // Update transfrom parts
                if(css.indexOf("-transform") != -1 && css != 'text-transform'){

                    // CSS
                    cssDetail = css.replace(/-transform/g,'');
                    css = 'transform';
                    cssDetail = cssDetail.replace(/\-/g,"");

                }

                // Update filter parts
                if(css.indexOf("-filter") != -1){

                    // CSS
                    cssDetail = css.replace(/-filter/g,'');
                    css = 'filter';

                }

                // Update filter parts
                if(css.indexOf("box-shadow-") != -1){

                    // CSS
                    cssDetail = css.replace(/box-shadow-/g,'');
                    css = 'box-shadow';

                }

                return [css,cssDetail];

            }


            /* ---------------------------------------------------- */
            /* Set Default Option Value                             */
            /* ---------------------------------------------------- */
            function set_default_value(id) {

                // Get Selector
                var selector = get_current_selector();

                // Set parent element as current
                if(id == 'perspective'){

                    // Cache current
                    var oldSelector = selector;

                    // clean cache
                    body.removeAttr("data-clickable-select");

                    // Update selector var
                    selector = $.trim(get_parents(get_selected_element().parent(), "default"));

                    // set old as cache again
                    body.attr("data-clickable-select",oldSelector);

                }

                // Get Element Object
                var the_element = iframe.find(selector);

                // Adding animation classes to element
                if (id == 'animation-name' || id == 'animation-iteration-count' || id == 'animation-duration' || id == 'animation-delay'){
                    the_element.addClass("yp_onscreen yp_hover yp_click yp_focus");
                }

                // replace fake rules as scale-transfrom.
                var ruleID = get_css_id(id);

                // Get details
                var elementID = ruleID[0];
                var cssDetail = ruleID[1];

                // Current media size
                var size = get_media_condition();

                // Default
                var ypEvent = '';

                // onscreen event
                if (the_editor_data().find('[data-style="' + elementID + get_id(".yp_onscreen") + '"][data-size-mode="'+size+'"]').length > 0) {
                    ypEvent = 'yp_onscreen';
                }

                // click event
                if (the_editor_data().find('[data-style="' + elementID + get_id(".yp_click") + '"][data-size-mode="'+size+'"]').length > 0) {
                    ypEvent = 'yp_click';
                }

                // hover event
                if (the_editor_data().find('[data-style="' + elementID + get_id(".yp_hover") + '"][data-size-mode="'+size+'"]').length > 0) {
                    ypEvent = 'yp_hover';
                }

                    // Focus event
                if (the_editor_data().find('[data-style="' + elementID + get_id(".yp_focus") + '"][data-size-mode="'+size+'"]').length > 0) {
                    ypEvent = 'yp_focus';
                }

                // hover event default
                if (mainBody.hasClass("yp-selector-hover") && ypEvent == '') {
                    ypEvent = 'yp_hover';
                }

                // Focus event default
                if (mainBody.hasClass("yp-selector-focus") && ypEvent == '') {
                    ypEvent = 'yp_focus';
                }

                // default is onscreen
                if (isUndefined(ypEvent) || ypEvent == '') {
                    ypEvent = 'yp_onscreen';
                }

                // Getting value anim check.
                var valueData = '';
                var dynamicValue = get_applied_style_data(selector, id, [true, false, false]);
                if(typeof dynamicValue === 'object'){
                    dynamicValue.always(function(data){
                        valueData = data;
                    });
                }

                setTimeout(function() {

                    // remove reset-enable class if is not a fake option.
                    if(get_css_id(id)[1] == null){
                        $("#" + id + "-group").removeClass("reset-enable");
                    }

                    // Has editor style?
                    if (id == 'animation-name' || id == 'animation-iteration-count' || id == 'animation-duration' || id == 'animation-delay'){

                        if(get_applied_style_data(selector+"."+ypEvent, id, [false, true, false])){
                            $("#" + id + "-group").addClass("reset-enable");
                        }

                    }else{

                        if(get_applied_style_data(selector, id, [false, true, false])){
                            $("#" + id + "-group").addClass("reset-enable");
                        }else{
                            $("#" + id + "-group").removeClass("reset-enable");
                        }

                    }


                    // data is default value
                    var data,numberData;

                    // Getting CSS Data. (Animation play not an CSS rule.)
                    if (id != 'animation-play' && id != 'border-width' && id != 'border-color' && id != 'border-style') {
                        data = the_element.css(elementID);
                        numberData = number_filter(data);
                    }

                    // If data has at CSS editor, get it
                    if(get_applied_style_data(selector, id, [true, true, false])){
                        data = valueData;
                        numberData = number_filter(data);
                    }

                    // Not set auto for top and left
                    if(id == 'top' || id == 'left'){
                        if(data == 'auto'){
                            data = '0px';
                            numberData = 0;
                        }
                    }

                    // Border: default is ALL
                    if(id == 'border-type'){

                        // Update only if not have a active radio.
                        if($("#border-type-group").find(".wyp-radio.active").length == false){
                            data = 'all';
                        }

                    }

                    // Getting format: px, em, etc.
                    var format = alfa_filter(data).replace(/(\.|\,)/g,'');

                    // Chome return "rgba(0,0,0,0)" if no background color,
                    // its is chrome hack.
                    if (id == 'background-color' && data == 'rgba(0, 0, 0, 0)') {
                        data = 'transparent';
                    }

                    // Check border style
                    var top;
                    if(id == 'border-style'){

                        data = 'solid';

                        top = the_element.css("borderTopStyle");

                        if(top == the_element.css("borderLeftStyle") && top == the_element.css("borderRightStyle") && top == the_element.css("borderBottomStyle")){
                            data = top;
                        }

                    }

                    // Check border width
                    if(id == 'border-width'){

                        data = '0px';
                        numberData = 0;

                        top = the_element.css("borderTopWidth");

                        if(top == the_element.css("borderLeftWidth") && top == the_element.css("borderRightWidth") && top == the_element.css("borderBottomWidth")){
                            data = top;
                            numberData = number_filter(top);
                        }

                    }

                    // Check border color
                    if(id == 'border-color'){

                        data = the_element.css("color");

                        top = the_element.css("borderTopColor");

                        if(top == the_element.css("borderLeftColor") && top == the_element.css("borderRightColor") && top == the_element.css("borderBottomColor")){
                            data = top;
                        }

                    }


                    // Check if margin left/right is auto or else.
                    if(id == 'margin-left' || id == 'margin-right'){

                        var frameWidth = iframe.width();

                        var marginLeft = parseFloat(the_element.css("marginLeft"));
                        var width = parseFloat(the_element.css("width"));

                        // Full in frame with margins
                        if(frameWidth == (marginLeft * 2) + width && marginLeft > 0){

                            data = 'auto';
                            numberData = 0;

                        // Full in parent with margins
                        }else if(the_element.parent().length > 0){

                            if(parseFloat(the_element.parent().width()) == ((marginLeft * 2) + width) && marginLeft > 0){
                                data = 'auto';
                                numberData = 0;
                            }

                        }

                    }


                    // some script for custom CSS Rule: animation-play
                    if (id == 'animation-play') {
                        data = ypEvent;
                    }


                    // Play if is animation name.
                    if (id == 'animation-name' && data != 'none' && data != 'animationGeneratorTestAnimate'){

                        // Add class.
                        body.addClass("wyp-hide-borders-now wyp-force-hide-select-ui");

                        var time = the_element.css("animationDuration");
                        var timeDelay = the_element.css("animationDelay");

                        // Getting right time delay if have multiple animations.
                        var newDelay = get_multiple_delay(time,timeDelay);

                        if(newDelay !== false){
                            timeDelay = newDelay;
                        }else if(isUndefined(timeDelay)){
                            timeDelay = 0;
                        }else{
                            timeDelay = duration_ms(timeDelay); // timeDelay
                        }

                        if (isUndefined(time)){
                            time = 1000;
                        }else{
                            time = duration_ms(time); // Time
                        }

                        time = parseFloat(time) + parseFloat(timeDelay);

                        if(time === 0){
                            time = 1000;
                        }

                        time = time + 100;

                        clear_animation_timer();

                        window.animationTimer2 = setTimeout(function() {

                            element_animation_end();

                            // Update.
                            draw();

                            // remove class.
                            body.removeClass("wyp-hide-borders-now wyp-force-hide-select-ui");

                        }, time);

                    }



                    // filter = explode filter data to parts
                    if (elementID == 'filter'){

                        // Try to get css with webkit prefix.
                        if (data === null || data == 'none' || data === undefined) {
                            data = the_element.css("-webkit-filter"); // for chrome.
                        }

                        // Special default values for filter css rule.
                        if (data != 'none' && data !== null && data !== undefined) {

                            // Get brightness, blur etc from filter data.
                            data = data.match(new RegExp(cssDetail+"\\((.*?)\\)","g"));

                            // is?
                            if (isDefined(data)) {

                                // replace prefixes
                                data = data.toString().replace("deg", "").replace("hue-rotate(", "").replace(")", "");

                                // Update data
                                data = number_filter(data);

                                // Update number data
                                numberData = data;

                            }else{

                                // Set default
                                data = 'disable';
                                numberData = 0;

                            }

                        }else{

                            // Set default
                            data = 'disable';
                            numberData = 0;

                        }

                    }


                    // Font weight fix.
                    if (id == 'font-weight'){

                        if(data == 'bolder'){ data = '700'; }
                        if(data == 'bold'){ data = '600'; }
                        if(data == 'normal'){ data = '400'; }
                        if(data == 'lighter'){ data = '300'; }

                    }


                    // transform = explode transform data to parts
                    if (elementID == 'transform') {

                        // Get transfrom style from editor data.
                        data = valueData;

                        // Getting transform value from HTML Source ANIM.
                        var styleString = null;
                        if (is_animate_creator()){

                            var currentScene = parseInt(mainBody.attr("data-anim-scene").replace("scene-", ""));

                            // Check scenes by scenes for get transfrom data.
                            for(var transfromIndex = 0; transfromIndex < 6; transfromIndex++){

                                // style element
                                var styleOb = iframe.find('.scene-' + (currentScene - transfromIndex) + ' .scenes-transform-style');

                                // Get
                                if (styleOb.length > 0) {
                                    styleString = styleOb.last().html();
                                    break;
                                }

                            }

                            // Get scene transform data else default.
                            if(styleString != null){
                                data = esc_data_value(styleString);
                            }

                        } // Anim end.


                        // explode transform data
                        if (data != 'none' && data !== false && data !== undefined) {

                            // Get brightness, blur etc from filter data.
                            data = data.match(new RegExp(cssDetail+"\\((.*?)\\)","g"));

                            // is?
                            if (isDefined(data)) {

                                // String.
                                data = data.toString();

                                // Update data
                                data = number_filter(data);

                                // Update number data
                                numberData = data;

                            }else{

                                // Set default
                                data = 'disable';
                                numberData = 0;

                            }

                        }else{

                            // Set default
                            data = 'disable';
                            numberData = 0;

                        }

                    }

                    // Animation creator; don't append 0s duration.
                    if (id == "animation-duration" && is_animate_creator() === true) {
                        if (data == '0s' || data == '0ms') {
                            return false;
                        }
                    }

                    // Set auto
                    if(id == 'min-width' || id == 'min-height'){
                        if(parseFloat(data) == 0){
                            data = 'auto';
                        }
                    }

                    // Check bottom and set auto
                    if (id == 'bottom') {

                        var p = parseInt(parseFloat(the_element.css("top")) + parseFloat(the_element.css("bottom")));

                        if (p === 0 || p == parseInt($("#iframe").height() - the_element.height())) {
                            data = 'auto';
                        }

                    }

                    // Check right and set auto
                    if (id == 'right') {

                        var p = parseInt(parseFloat(the_element.css("left")) + parseFloat(the_element.css("right")));

                        if (p === 0 || p == parseInt($("#iframe").width() - the_element.width())) {
                            data = 'auto';
                        }

                    }

                    // Box Shadow.
                    if (elementID == 'box-shadow' && data != 'none' && data !== null && data !== undefined) {

                        // Box shadow color default value.
                        if (id == 'box-shadow-color') {

                            // Hex
                            if (data.indexOf("#") != -1) {
                                if (data.split("#")[1].indexOf("inset") == -1) {
                                    data = $.trim(data.split("#")[1]);
                                } else {
                                    data = $.trim(data.split("#")[1].split(' ')[0]);
                                }
                            } else {
                                if (data.indexOf("rgb") != -1) {
                                    data = data.match(/rgb(.*?)\((.*?)\)/g).toString();
                                }
                            }

                            if(/(rgb|\#)/g.test(data) == false){
                                data = "#" + data;
                            }

                        }

                        // split all box-shadow data.
                        var numbericBox = data.replace(/rgb(.*?)\((.*?)\) /g, '').replace(/ rgb(.*?)\((.*?)\)/g, '').replace(/inset /g, '').replace(/ inset/g, '');

                        // shadow horizontal value.

                        if (id == 'box-shadow-horizontal') {
                            data = numbericBox.split(" ")[0];
                            numberData = number_filter(data);
                        }

                        // shadow vertical value.
                        if (id == 'box-shadow-vertical') {
                            data = numbericBox.split(" ")[1];
                            numberData = number_filter(data);
                        }

                        // Shadow blur radius value.
                        if (id == 'box-shadow-blur-radius') {
                            data = numbericBox.split(" ")[2];
                            numberData = number_filter(data);
                        }

                        // Shadow spread value.
                        if (id == 'box-shadow-spread') {
                            data = numbericBox.split(" ")[3];
                            numberData = number_filter(data);
                        }

                    }

                    // if no info about inset, default is no.
                    if (id == 'box-shadow-inset') {

                        if (isUndefined(data)) {

                            data = 'no';

                        } else {

                            if (data.indexOf("inset") == -1) {
                                data = 'no';
                            } else {
                                data = 'inset';
                            }

                        }

                    }

                    // option element.
                    var the_option = $("#wyp-" + id);

                    // option element parent of parent.
                    var id_prt = the_option.parent().parent();

                    // if special CSS, get css by CSS data.
                    if (the_element.css(id) === undefined && iframe.find('.' + elementID + '-' + id + '-style').length > 0) {

                        data = valueData;
                        numberData = number_filter(data);

                    }

                    // IF THIS IS A SLIDER
                    if (the_option.hasClass("wqNoUi-target")){

                        // if has multi duration
                        if(id == 'animation-duration' && data.indexOf(",") != -1){
                            data = '1s'; // Reading as 1second
                            format = 's';
                            numberData = '1';
                            $("#animation-duration-group").addClass("hidden-option");
                        }else if(id == 'animation-duration'){
                            $("#animation-duration-group").removeClass("hidden-option");
                        }


                        // if has multi delay
                        if(id == 'animation-delay' && data.indexOf(",") != -1){
                            data = '0s'; // Reading as 1second
                            format = 's';
                            numberData = '0';
                            $("#animation-delay-group").addClass("hidden-option");
                        }else if(id == 'animation-delay'){
                            $("#animation-delay-group").removeClass("hidden-option");
                        }

                        // If not inline
                        if (the_element.css("display") != 'inline' || the_element.css("display").indexOf("table") != -1) {

                            // if has children and id is height
                            if (id == 'height' && the_element.children().length > 0 && the_element.children().length < 12) {

                                var elHeight = the_element.css("height");
                                var tHeight;

                                // parent is display block
                                the_element.children().each(function(){

                                    tHeight = $(this).css("height");

                                    if(elHeight == tHeight){

                                        data = 'auto';
                                        return false;

                                    }

                                });

                            }

                        }


                        // if no data, active none option.
                        if (data == 'none' || data == 'auto' || data == 'inherit' || data == 'initial'){
                            if(id_prt.find(".wyp-none-btn").hasClass("active")){
                                id_prt.find(".wyp-none-btn").trigger("click").trigger("click");
                            }else{
                                id_prt.find(".wyp-none-btn").trigger("click");
                            }
                            format = 'px';
                        } else {
                            id_prt.find(".wyp-none-btn.active").trigger("click"); // else disable none option.
                        }

                        format = $.trim(format);

                        // be sure format is valid.
                        if (format === '' || format == 'px .px' || format == 'px px') {
                            format = 'px';
                        }

                        // be sure format is valid.
                        if (format.indexOf("px") != -1) {
                            format = 'px';
                        }

                        // Default value is 1 for transform scale.
                        if (numberData == '' && id == 'scale-transform') {
                            numberData = 1;
                        }

                        // Default value is 1 for filter
                        if (numberData == '' && id == 'brightness-filter') {
                            numberData = 1;
                        }

                        // Default value is 1 for filter
                        if (numberData == '' && id == 'contrast-filter') {
                            numberData = 1;
                        }

                        // Default value is 1 for filter
                        if (numberData == '' && id == 'saturate-filter') {
                            numberData = 1;
                        }

                        // default value is 1 for opacity.
                        if (numberData == '' && id == 'opacity') {
                            numberData = 1;
                        }

                        // If no data, set zero value.
                        if (numberData == '') {
                            numberData = 0;
                        }

                        var range = id_prt.data("px").split(",");

                        var $min = parseInt(range[0]); // mininum value
                        var $max = parseInt(range[1]); // maximum value

                        // Check values.
                        if (numberData < $min) {
                            $min = numberData;
                        }

                        if (numberData > $max) {
                            $max = numberData;
                        }

                        // Speacial max and min limits for CSS size rules.
                        if (id == 'width' || id == 'max-width' || id == 'min-width' || id == 'height' || id == 'min-height' || id == 'max-height') {
                            $max = parseInt($max) + (parseInt($max) * 1.5);
                            $min = parseInt($min) + (parseInt($min) * 1.5);
                        }

                        // if width/height is same with windows width, set 100%!
                        // Note: browsers always return value in PX format.
                        if (the_element.css("display") != 'inline') {

                            // Width
                            if (id == 'width' && the_element.parent().length > 0) {

                                // is px and display block
                                if(format == 'px' && the_element.parent().css("display") != 'inline' && the_element.parent().css("display") != 'inline-flex' && the_element.parent().css("display").indexOf("table") == -1){

                                    var parentWidth = the_element.parent().width();

                                    // if width is same with parent width, so set 100%!
                                    if (parentWidth == parseInt(numberData)) {
                                        numberData = '100';
                                        format = '%';
                                    }

                                    // if width is 50% of parent width, so set 50%!
                                    if (parseInt(parentWidth/2) == (parseInt(numberData))) {
                                        numberData = '50';
                                        format = '%';
                                    }

                                    // if width is 25% of parent width, so set 25%!
                                    if (parseInt(parentWidth/4) == (parseInt(numberData))) {
                                        numberData = '25';
                                        format = '%';
                                    }

                                    // if width is 20% of parent width, so set 20%!
                                    if (parseInt(parentWidth/5) == (parseInt(numberData))) {
                                        numberData = '20';
                                        format = '%';
                                    }

                                }

                            }

                            // if  height is 100% of window height!
                            if (id == 'height' && parseInt($(window).height()) == parseInt(numberData) && format == 'px') {
                                numberData = '100';
                                format = 'vh';
                            }

                        }

                        // max and min for %.
                        if (format == '%'){
                            range = $('#' + id + '-group').attr("data-pcv").split(",");
                            $min = range[0];
                            $max = range[1];
                        }else if(format == 'em'){
                            range = $('#' + id + '-group').attr("data-emv").split(",");
                            $min = range[0];
                            $max = range[1];
                        }

                        // Raund
                        numberData = Math.floor(numberData * 100) / 100;

                        format = format.replace(/\./g,'');

                        // Just int
                        if(format == 'px'){
                            if(id == 'width' || id == 'height' || id == 'min-width' || id == 'min-height' || id == 'max-width' || id == 'max-height' || id == 'font-size' || id.indexOf("margin-") != -1 || id.indexOf("padding-") != -1 || /border(.*?)?width/g.test(a) || id.indexOf("-radius") != -1 || id.indexOf("z-index") != -1 || id == 'top' || id == 'right' || id == 'bottom' || id == 'left'){
                                numberData = parseInt(numberData);
                            }
                        }

                        the_option.wqNoUiSlider({
                            range: {
                                'min': parseInt($min),
                                'max': parseInt($max)
                            },
                            start: parseFloat(numberData)
                        }, true);

                        // Set new value.
                        the_option.val(numberData);

                        // Update the input.
                        $('#' + id + '-value').val(numberData);

                        // set format of value. px, em etc.
                        $("#" + id + "-after").val(format);

                        return false;

                        // IF THIS IS A SELECT TAG
                    } else if (the_option.hasClass("input-autocomplete")) {

                        // Checking font family settings.
                        if (id == 'font-family' && typeof data != 'undefined') {

                            data = $.trim(data.replace(/"/g, "").replace(/'/g, ""));

                        }

                        if (isDefined(data)) {

                            // Append default font family to body. only for select font family.
                            if ($(".wyp-font-test-" + get_basic_id($.trim(data.replace(/ /g, '+')))).length === 0 && id == 'font-family') {

                                // If safe font, stop.
                                if (is_safe_font(data) === false) {

                                    // Be sure its google font.
                                    if (is_google_font(data)) {

                                        // Append always to body.
                                        body.append("<link rel='stylesheet' class='wyp-font-test-" + get_basic_id($.trim(data.replace(/ /g, '+'))) + "'  href='https://fonts.googleapis.com/css?family=" + $.trim(data.replace(/ /g, '+')) + ":300italic,300,400,400italic,500,500italic,600,600italic,700,700italic' type='text/css' media='all' />");

                                    }

                                }

                            }

                            // If have data, so set!
                            if (id == 'font-family' && data.indexOf(",") == -1) {

                                // Getting value
                                var value = $("#wyp-font-family-data option").filter(function() {
                                    return $(this).text() === data;
                                }).first().attr("value");

                                // Select by value.
                                if (value !== undefined) {

                                    value = value.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                                        return letter.toUpperCase();
                                    });

                                    the_option.val(value);
                                } else {

                                    data = data.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                                        return letter.toUpperCase();
                                    });

                                    the_option.val(data);
                                }

                            } else {

                                // set value.
                                the_option.val(data);

                            }

                            if (id == 'font-family') {
                                $("#wyp-font-family,#wyp-font-weight").each(function() {
                                    $(this).css("fontFamily", data);
                                });
                            }

                        }

                        // Active none button.
                        id_prt.find(".wyp-btn-action.active").trigger("click");

                        // If data is none, auto etc, so active none button.
                        if (data == id_prt.find(".wyp-none-btn").text()) {
                            id_prt.find(".wyp-none-btn").trigger("click");
                        }

                        // If not have this data in select options, insert this data.
                        if (the_option.val() === null && data != id_prt.find(".wyp-none-btn").text() && data !== undefined) {
                            the_option.val(data);
                        }

                        return false;

                        // IF THIS IS A RADIO TAG
                    } else if (the_option.hasClass("wyp-radio-content")) {

                        // Fix background size rule.
                        if (id == 'background-size') {
                            if (data == 'auto' || data == '' || data == ' ' || data == 'auto auto') {
                                data = 'auto auto';
                            }
                        }

                        // If disable, active disable button.
                        if (data == 'disable') {
                            id_prt.find(".wyp-disable-btn").not(".active").trigger("click");
                        } else {
                            radio_value(the_option, id, data); // else Set radio value.
                        }

                        return false;

                        // IF THIS IS COLORPICKER
                    } else if (the_option.hasClass("wqcolorpicker")) {

                        // Remove active
                        $(".wyp-nice-c.active,.wyp-flat-c.active,.wyp-meterial-c.active").removeClass("active");

                        if (id == 'box-shadow-color') {
                            if (data === undefined || data === false || data == 'none' || data == '') {
                                data = the_element.css("color");
                            }
                        }

                        // Convert to rgb and set value.
                        var rgbd;
                        if (isDefined(data)) {
                            if (data.indexOf("#") == -1 && data.indexOf("rgb") != -1) {
                                rgbd = get_color(data);
                            }else if(data != 'transparent' && data != 'none' && data != 'inherit'){
                                rgbd = data;
                            }
                        }

                        // browsers return value always in rgb format.
                        the_option.val(rgbd);
                        the_option.iris('color', data);

                        // If rgba
                        var alpha = 100;
                        if(data.indexOf("rgba") != -1){
                            alpha = $.trim(data.replace(/^.*,(.+)\)/,'$1'));
                            if(alpha.indexOf(".") != -1){
                                alpha = alpha.replace("000.","").replace("00.","").replace("0.","").replace(".","");
                                if(alpha.length == 1){
                                    alpha = alpha.toString()+"0";
                                }
                                alpha = alpha.replace(/^0/, "");
                            }
                        }

                        // Update iris alpha.
                        id_prt.find(".cs-alpha-slider").slider('value',alpha);

                        // Set current color on small area.
                        the_option.parent().find(".wqminicolors-swatch-color").css("backgroundColor", rgbd);

                        // If transparent
                        if (data == 'transparent' || data == '') {
                            id_prt.find(".wyp-disable-btn.active").trigger("click");
                            id_prt.find(".wyp-none-btn:not(.active)").trigger("click");
                            the_option.parent().find(".wqminicolors-swatch-color").css("backgroundColor", "transparent");
                        } else {
                            id_prt.find(".wyp-none-btn.active").trigger("click");
                        }

                        if (id == 'box-shadow-color') {
                            $("#box-shadow-color-group .wqminicolors-swatch-color").css("backgroundColor", data);
                        }

                        return false;

                        // IF THIS IS INPUT OR TEXTAREA
                    } else if (the_option.hasClass("wyp-input") || the_option.hasClass("wyp-textarea")) {

                        // clean URL() prefix for background image. no gradients
                        if (data != 'disable' && id == "background-image" && data != window.location.href && data.indexOf("linear-gradient(") == -1) {

                            // If background-image is empty.
                            var a = $(document).find("#iframe").attr("src");
                            var b = data.replace(/"/g, "").replace(/'/g, "").replace(/url\(/g, "").replace(/\)/g, "");

                            // Cleans if no data
                            if (a == b) {
                                data = '';
                            }

                            // Cleans
                            the_option.val(data.replace(/"/g, "").replace(/'/g, "").replace(/url\(/g, "").replace(/\)/g, ""));

                            // remove class from active pattern
                            $(".wyp_bg_assets").removeClass("active");

                            // if it is a pattern
                            if (data.indexOf("yellow-pencil") != -1) {

                                // Find the pattern and add active class
                                $(".wyp_bg_assets[data-url='" + data.replace(/"/g, "").replace(/'/g, "").replace(/url\(/g, "").replace(/\)/g, "") + "']").addClass("active");

                                $(".wyp-background-image-show").remove();

                            } else { // if image

                                // Remove background image
                                $(".wyp-background-image-show").remove();

                                // Get img URL
                                var imgSrc = data.replace(/"/g, "").replace(/'/g, "").replace(/url\(/g, "").replace(/\)/g, "");

                                // check if image is valid
                                if (imgSrc.indexOf("//") != -1 && imgSrc != '' && imgSrc.indexOf(".") != -1) {

                                    // Append the view image
                                    $("#wyp-background-image").after("<img src='" + imgSrc + "' class='wyp-background-image-show' />");

                                }

                            }

                        } else {

                            // remove background image
                            $(".wyp-background-image-show").remove();

                        }



                        // clean URL() prefix for list style image.
                        if (data != 'disable' && id == "list-style-image" && data != window.location.href) {

                            // If list-style-image is empty.
                            var a = $(document).find("#iframe").attr("src");
                            var b = data.replace(/"/g, "").replace(/'/g, "").replace(/url\(/g, "").replace(/\)/g, "");

                            // Cleans if no data
                            if (a == b) {
                                data = '';
                            }

                            // Cleans
                            the_option.val(data.replace(/"/g, "").replace(/'/g, "").replace(/url\(/g, "").replace(/\)/g, ""));

                        }


                        // If is background image and has gradient
                        if(id == 'background-image' && data.indexOf("linear-gradient(") != -1){

                            // Set data
                            the_option.val(data);

                            // Update gradient tool
                            read_gradient(data);

                            // Open gradient tool
                            window.documentClick = false;

                            $(".wyp-gradient-btn:not(.active)").trigger("click");

                            setTimeout(function(){
                                window.documentClick = true;
                            },50);

                        }


                        // If no data, active none button.
                        if (data == 'none') {
                            id_prt.find(".wyp-none-btn").not(".active").trigger("click");
                            the_option.val(''); // clean value.
                        } else {
                            id_prt.find(".wyp-none-btn.active").trigger("click"); // else disable.
                        }

                        // If no data, active disable button.
                        if (data == 'disable') {
                            id_prt.find(".wyp-disable-btn").not(".active").trigger("click");
                            the_option.val('');
                        } else {
                            id_prt.find(".wyp-disable-btn.active").trigger("click"); // else disable.
                        }

                        return false;

                    }

                }, 20);

            }


            /* ---------------------------------------------------- */
            /* Check if it is an google Font                        */
            /* ---------------------------------------------------- */
            function is_google_font(font) {

                var status = false;
                $('select#wyp-font-family-data option').each(function() {
                    if ($(this).text() == font) {
                        status = true;
                        return true;
                    }
                });

                return status;

            }


            /* ---------------------------------------------------- */
            /* Converting selectors to Array                        */
            /* ---------------------------------------------------- */
            function get_selector_array(selector){

                var selectorArray = [];

                // Clean
                selector = $.trim(selector);

                // Clean multispaces
                selector = selector.replace(/\s\s+/g, ' ');

                // Clean spaces before ">,+,~" and after
                selector = selector.replace(/(\s)?(\>|\,|\+|\~)(\s)?/g, '$2');

                // Convert > to space
                selector = selector.replace(/\>/g, ' ');

                selector = $.trim(selector);

                // Check if still there have another selector
                if(selector.indexOf(" ") != -1){

                    var selectorSplit = selector.split(" ");

                    // Split with space
                    var v;
                    for(var i = 0; i < selectorSplit.length; i++){

                        // Clean
                        v = $.trim(selectorSplit[i]);

                        // Push
                        selectorArray.push(v);

                    }

                }else{

                    // Push if single.
                    selectorArray.push(selector);

                }

                var selectorArrayNew = [];

                // Add spaces again
                $.each(selectorArray,function(i,v){
                    selectorArrayNew.push(v.replace(/\~/g,' ~ ').replace(/\+/g,' + '));
                });

                return selectorArrayNew;

            }


            /* ---------------------------------------------------- */
            /* Converting Classes to Array                          */
            /* ---------------------------------------------------- */
            function get_classes_array(classes){

                var classesArray = [];

                // Clean
                classes = $.trim(classes);

                // Clean multispaces
                classes = classes.replace(/\s\s+/g, ' ');

                // Check if still there have another class
                if(classes.indexOf(" ") != -1){

                    var classessplit = classes.split(" ");

                    // Split with space
                    var v;
                    for(var i = 0; i < classessplit.length; i++){

                        // Clean
                        v = $.trim(classessplit[i]);

                        // Push
                        classesArray.push(v);

                    }

                }else{

                    // Push if single.
                    classesArray.push(classes);

                }

                return classesArray;

            }



            /* ---------------------------------------------------- */
            /* PREFERED CLASSES                                     */
            /* ---------------------------------------------------- */
            var preferedClasses = [
                'current-menu-item',
                'active(!singleInspector)',
                'current(!singleInspector)',
                'post',
                'hentry',
                'widget',
                'wp-post-image',
                '(entry|article|post)-title',
                '(entry|article|post)-content',
                '(entry|article|post)-meta',
                'comment-author-admin',
                '([a-zA-Z0-9_-]+)?item',
                'widget-title',
                'widgettitle',
                'next',
                'prev',
                'product',
                'footer',
                'header',
                'sidebar',
                'form-control',
                'footer-top',
                'copyright',
                'menu-item',
                'kc-css-([a-zA-Z0-9_-]+)?',
                'row_inner', // cos row_inner element has a lot bad classes in themify.
                'filterall'
            ];


            /* ---------------------------------------------------- */
            /* FILTERING NEVER-USE CLASSES                          */
            /* ---------------------------------------------------- */
            /* These classes will never be used in the selector.    */
            /* Use only if tag is "div" and not have a alternative. */
            /* ---------------------------------------------------- */
            var blockedClasses = [

                // Classes from a animate.css
                '([a-zA-Z0-9_-]+)?infinite([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?bounce([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?flash([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?pulse([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?rubberBand([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?shake([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?headShake([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?swing([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?tada([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?wobble([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?jello([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?hinge([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?fade([a-zA-Z0-9_-]+)?',

                '([a-zA-Z0-9_-]+)?slide([a-zA-Z0-9_-]+)?(In|in|Out|out)([a-zA-Z0-9_-]+)?(Up|up|Down|down|Left|left|Right|right)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?roll([a-zA-Z0-9_-]+)?(In|in|Out|out)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?fall([a-zA-Z0-9_-]+)?(In|in|Out|out|Up|up|Down|down|Left|left|Right|right)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?flip([a-zA-Z0-9_-]+)?(In|in|Out|out|Up|up|Down|down|Left|left|Right|right)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?lightSpeed([a-zA-Z0-9_-]+)?(In|in|Out|out)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?rotate([a-zA-Z0-9_-]+)?(In|in|Out|out)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?zoom([a-zA-Z0-9_-]+)?(In|in|Out|out)([a-zA-Z0-9_-]+)?',


                // Post Status classes
                '([a-zA-Z0-9_-]+)?publish([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?draft([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?pending([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?private([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?trash([a-zA-Z0-9_-]+)?',

                // Basic post formats
                '(standard|aside|audio|chat|gallery|image|link|quote|status|video)',

                // Some functional classes
                '([a-zA-Z0-9_-]+)?viewport([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?ltr([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?padding([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?inherit([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?margin([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?relative([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?transparent([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?visibility([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?hidden([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?active-slide([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?current-slide([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?hide([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?animated([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?draggable([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?resize([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?cloned([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?sortable([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?status([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?-spacing-yes',
                '([a-zA-Z0-9_-s]+)?-spacing-no',
                '([a-zA-Z0-9_-]+)?clearfix([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?clear([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?(clr|clfw)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?clean([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?hover([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?default_template([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?ready([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?validate([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?false([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?true([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?loading([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?loaded([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?finished([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?center([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?delay([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?enabled([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?disabled([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?ga-track([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?raw_code([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?raw_html([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?padded([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?bold([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?initialised([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?even([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?odd([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?dismissable([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?underlined([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?flippable([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?clickable([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?gutter([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?animation([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?animate([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?transition([a-zA-Z0-9_-]+)?',

                // Functional 3 party classes
                '([a-zA-Z0-9_-]+)?withbg([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?bg_layout([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-s]+)?rand',
                'mrg-(btm|top|left|right|tp|lft|rght)-([a-zA-Z0-9_-]+)',
                'is([_-])([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)none',

                // Wordpress Core
                'page([_-])item',
                '([a-zA-Z0-9_-]+)?object([_-])page',
                '([a-zA-Z0-9_-]+)?closed',
                'thread([_-])alt',
                '([a-zA-Z0-9_-]+)?([_-])has([_-])?([a-zA-Z0-9_-]+)|([a-zA-Z0-9_-]+)?([_-])?has([_-])([a-zA-Z0-9_-]+)',
                'screen([_-])reader([_-])text',
                'tag-link([a-zA-Z0-9_-]+)?',
                'post-no-media',

                // Browser Classes
                '([a-zA-Z0-9_-]+)?opera([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?firefox([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?safari([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?chrome([a-zA-Z0-9_-]+)?',

                // WooCommerce
                '([a-zA-Z0-9_-]+)?product_tag([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?product_cat([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?taxable([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?onsale([a-zA-Z0-9_-]+)?',
                'calculated_shipping',
                'currency([a-zA-Z0-9_-]+)?',
                'woocommerce-MyAccount-navigation-link--([a-zA-Z0-9_-]+)?',

                // Etc
                'img-responsive',
                'ls-active',
                'disappear',
                'appear',
                'noSwipe',
                'wow',
                'bootstrap-touchspin-down',
                'section--no',
                'cat-item',
                '([a-zA-Z0-9_-]+)?direction-ltr',
                '([a-zA-Z0-9_-]+)?show-dropdown', // it is a hover class.
                'kc-elm',
                'kc_column',
                'selected',
                'alternate_color', // enfold
                'open-mega-a', // enfold
                'sf-menu',
                'sf-arrows',

                // Bounce after tests
                '([a-zA-Z0-9_-]+)?nojquery([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?js-comp-ver([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?wpb-js-composer([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?-shd',
                '([a-zA-Z0-9_-]+)?with([_-])([a-zA-Z0-9]+)',
                '([a-zA-Z0-9_-]+)?m-t-([0-9])+([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?(serif|sans|font|webfont)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?uppercase([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?([_-])(to|from)([_-])(top|left|right|bottom)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?(cursor|pointer)([a-zA-Z0-9_-]+)?',
                '(not|no)([_-])([a-zA-Z0-9_-]+)?',
                'ajax',
                'neg-marg',
                '([a-zA-Z0-9_-]+)?video-aspect-ratio-([a-zA-Z0-9_-]+)',
                'lazy',
                'lazy-img'

            ];


            /* ---------------------------------------------------- */
            /* FILTERING UNPREFERED CLASSES                         */
            /* ---------------------------------------------------- */
            /* UnPrefered Classes, these classes is not a priority  */
            /* ---------------------------------------------------- */
            var unPreferedClasses = [

                // Logical
                '([a-zA-Z0-9_-]+)([_-])', // End with -_
                '([_-])([a-zA-Z0-9_-]+)', // start with -_
                '([a-zA-Z0-9_-]+)?([_-])([_-])([a-zA-Z0-9_-]+)?', // multiple -_ ex: bad--class--name

                // WordPress Dynamic Classes
                '([a-zA-Z0-9_-]+)?(tag|category|cat)([_-])([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?format([a-zA-Z0-9_-]+)?',
                'menu([_-])item([_-])type([_-])post([_-])type',
                'menu([_-])item([_-])object([_-])page',
                'menu([_-])item([_-])(object|type)([_-])custom',
                'widget_([a-zA-Z0-9_-]+)',
                'bg-([a-zA-Z0-9_-]+)',

                // Modern Columns.
                '([a-zA-Z0-9_-]+)?([_-])(l|m|s|xs)([_-])[0-9]+',
                '([a-zA-Z0-9_-]+)?pure([_-])([a-zA-Z0-9_-]+)?([_-])u([_-])[0-9]+([_-])[0-9]+',
                '([a-zA-Z0-9_-]+)?col([_-])([a-zA-Z0-9_-]+)?([_-])[0-9]+',
                '([a-zA-Z0-9_-]+)?col([_-])([a-zA-Z0-9_-]+)?([_-])offset([_-])[0-9]+',
                '([a-zA-Z0-9_-]+)?(medium|large|small)([_-])[0-9]+',
                '([a-zA-Z0-9_-]+)?(medium|large|small)([_-])([a-zA-Z0-9_-]+)?([_-])[0-9]+',

                // Bootstrap Classes
                '([a-zA-Z0-9_-]+)?(small|medium|large)([_-])(push|pull)([_-])[0-9]+',
                '([a-zA-Z0-9_-]+)?span[0-9]+',
                '([a-zA-Z0-9_-]+)?span([_-])[0-9]+',
                '([a-zA-Z0-9_-]+)?col([_-])[0-9]+([_-])[0-9]+',
                '([a-zA-Z0-9_-]+)?col([_-])[0-9]+',

                // Classic Grid Columns
                '(column|columns|col)',
                '([a-zA-Z0-9_-]+)(one|two|three|four|five|six|seven|eight|nine|ten|eleven|twelve)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?(one|two|three|four|five|six|seven|eight|nine|ten|eleven|twelve)([a-zA-Z0-9_-]+)',

                // Structural
                '([a-zA-Z0-9_-]+)?sticky([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?fixed([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?logged([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?print([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?visible([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?trigger([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?required([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?pull([a-zA-Z0-9_-]+)(left|right)',
                '(left|right)',
                '([a-zA-Z0-9_-]+)?([_-])(yes|no)([_-])([a-zA-Z0-9_-]+)?', // _yes_
                '([a-zA-Z0-9_-]+)?(yes|no)([_-])([a-zA-Z0-9_-]+)?', // yes_
                '([a-zA-Z0-9_-]+)?([_-])(yes|no)([a-zA-Z0-9_-]+)?', // _yes,
                '([a-zA-Z0-9_-]+)?is([_-])active([a-zA-Z0-9_-]+)?', // is_active,

                // Dynamic CSS classes.
                '([a-zA-Z0-9_-]+)?background([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?width([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?height([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?position([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?parent([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?color([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?layout([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?invert([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)style([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?scroll([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?equal([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?square([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?([_-])skin([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?skin([_-])([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?toggled([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?style([0-9_-]+)',
                '([a-zA-Z0-9_-]+)?rounded([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?radius([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?type([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?dynamic([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?first',
                '([a-zA-Z0-9_-]+)?last',
                '([a-zA-Z0-9_-]+)?text([_-])justify',
                '([a-zA-Z0-9_-]+)?row([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?border([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?align([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?effect([0-9_-]+)',
                '([a-zA-Z0-9_-]+)?dimension([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?inline-inside([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?corner-pointed([a-zA-Z0-9_-]+)?',

                // General Theme Option Classes
                '([a-zA-Z0-9_-]+)([_-])(on|off)',
                '([a-zA-Z0-9_-]+)default([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)size([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)mobile([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)desktop([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)populated([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?hide([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?show([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?parallax([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?responsive([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?intense([a-zA-Z0-9_-]+)?',

                // Unyson
                'unyson-page',
                'end',

                // Pagenavi
                'larger',
                'smaller',

                // Buddypress
                'created_group',
                'mini',
                'activity_update',

                // Force Builder
                'forge-block',
                'forge-',

                // Elementor
                'elementor-section-items-middle',

                // Themify
                'themify_builder',
                'module',

                // live composer
                'dslc-post-no-thumb',

                // Woocommerce
                'downloadable',
                'purchasable',
                'instock',

                // Others
                'above',
                'open',

                // Enfold
                'template-page',
                'alpha',
                'units',
                'flex_column_div',
                '([a-zA-Z0-9_-]+)?no-sibling([a-zA-Z0-9_-]+)?',

                // bounce after tests
                '([a-zA-Z0-9_-]+)?float([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?(blue|black|red|dark|white|light|green|yellow|purple|pink|orange|brown|gray)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?(black|dark|white|light)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?last-child([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?first-child([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)([_-])only',
                '([a-zA-Z0-9_-]+)?(text-left|text-right)([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?(round|scale|shadow|rotate|default|minimal|animsition|nimation)([a-zA-Z0-9_-]+)?',
                'woocommerce',
                'affix-top',
                'st-menu',
                'circle',
                'vc_figure',
                'vc_general',
                'waves-effect',
                'collapsed',
                'collapse'

            ];


            /* ---------------------------------------------------- */
            /* FILTERING UNPREFED CLASSES                           */
            /* ---------------------------------------------------- */
            /* This filter excluding the structural classes in the  */
            /* selector. ex: col-md-8, exclude it if no need.       */
            /* ---------------------------------------------------- */
            var unPreferedSelectors = [

                // General
                '([a-zA-Z0-9_-]+)?inner([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?overlay([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?container([a-zA-Z0-9_-]+)?',

                // new visual composer
                '([a-zA-Z0-9_-]+)?google-fonts([a-zA-Z0-9_-]+)?',

                // siteorigin
                '([a-zA-Z0-9_-]+)?fl-col-content([a-zA-Z0-9_-]+)?',

                // Enfold
                'av-content-full'

            ];

            unPreferedSelectors.concat(blockedClasses);
            unPreferedSelectors.concat(unPreferedClasses);


            /* ---------------------------------------------------- */
            /* SKIP SOME NUMMERIC CLASSES AS NORMAL                 */
            /* ---------------------------------------------------- */
            /* process these  nummeric classes as non nummeric      */
            /* ---------------------------------------------------- */
            var filterNumSkipBest = [
                '([a-zA-Z0-9_-]+)?wpcf7([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?mc4wp([a-zA-Z0-9_-]+)?'
            ];


            /* ---------------------------------------------------- */
            /* EXCLUDE BAD NUMMERIC CLASSES                         */
            /* ---------------------------------------------------- */
            /* Never uses the following nummeric classes            */
            /* ---------------------------------------------------- */
            var blockedDigitalClasses = [
                '([a-zA-Z0-9_-]+)?page([_-])item([_-])([0-9]+)',
                '(vc_|vc-)(.*?)(_|-)[a-zA-Z-0-9]{22,22}',
                'themify_builder_content-([0-9]+)',
                'themify_builder_([0-9]+)_row',
                'tb_([0-9]+)_column',
                'et_pb_image_([0-9]+)',
                '([a-zA-Z0-9_-]+)?(post|page|portfolio|product|work|port|form|video)([_-])([0-9]+)',
                '([a-zA-Z0-9_-]+)?(post|page|portfolio|product|work|port|form|video)([_-])(entry|item|id)([_-])([0-9]+)',
                '([0-9])+px',
                '([a-zA-Z0-9_-]+)?wishlist-([0-9])+',
                'wpbs-bookable-([0-9])+',
                'wpbs-day-([0-9])+',
                '([a-zA-Z0-9_-]+)?rand-([0-9])+',
                '([a-zA-Z0-9_-]+)?(ie|ie8|ie9|ie10|ie11)',
                'testimonials-items-([a-zA-Z0-9_-]+)',
                'instance-([0-9]+)'
            ];


            /* ---------------------------------------------------- */
            /* GOOD ALLOWED NUMMERIC CLASSES                        */
            /* ---------------------------------------------------- */
            /* Prefer one digital class in 2 or more.               */
            /* ---------------------------------------------------- */
            var preferedDigitalClasses = [

                // General
                'wp-image-[0-9]+',

                // Basic
                '([a-zA-Z0-9_-]+)?section([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?slide([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?button([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?image([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?row([a-zA-Z0-9_-]+)?',

                // Visual composer
                'vc_custom_([a-zA-Z0-9_-]+)?',

                // Beaver builder
                'fl-node([a-zA-Z0-9_-]+)?',
                'fl-row([a-zA-Z0-9_-]+)?',

                // Themify
                'module_row_([0-9]+)',
                'module_column_([0-9]+)',

                // Divi
                'et_pb_(section|row)_[0-9]+',

                // king composer
                'kc-css-([0-9]+)',

                // forge builder
                'forge-col[0-9]+',

                // enfold
                '(avia|av)-builder-el-([0-9]+)',

                // flatsome
                'footer-([0-9]+)'

            ];


            /* ---------------------------------------------------- */
            /* GOOD ALLOWED NUMMERIC IDS                            */
            /* ---------------------------------------------------- */
            /* YP Editor allow just the following nummeric ids      */
            /* Ex: #section-15 is a allowed nummeric id             */
            /* ---------------------------------------------------- */
            var allowedDigitalIds = [

                // General
                '([a-zA-Z0-9_-]+)?module([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?slide([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?section([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?row([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?layout([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?form([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?wrapper([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?container([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?parallax([a-zA-Z0-9_-]+)?',
                '([a-zA-Z0-9_-]+)?block([a-zA-Z0-9_-]+)?',

                // 3 party plugin allowed ids
                'layers-widget-([a-zA-Z0-9_-]+)?',
                'builder-module-([a-zA-Z0-9_-]+)?',
                'pg-([a-zA-Z0-9_-]+)?',
                'ptpb_s([a-zA-Z0-9_-]+)?',
                'billing_address_([0-9])([a-zA-Z0-9_-]+)?', // woocommerce
                'el-([a-zA-Z0-9_-]+)',
                'dslc-module-([a-zA-Z0-9_-]+)',
                'module-([0-9]){13,13}-([0-9]){4,4}', // upfront
                'wrapper-([0-9]){13,13}-([0-9]){4,4}' // upfront

            ];


            /* ---------------------------------------------------- */
            /* Blocked IDS                                          */
            /* ---------------------------------------------------- */
            var blockedIds = [

                'widget',
                "recentcomments",
                'fws_([a-zA-Z0-9_-]+)', // 3 party plugin dynamic ID
                'wrapper-[a-zA-Z-0-9]{16,16}' // headway dynamic ID

            ];


            /* ---------------------------------------------------- */
            /* Prefered tags as selector                            */
            /* ---------------------------------------------------- */
            var simpleLikedTags = [
                "h1",
                "h2",
                "h3",
                "h4",
                "h5",
                "h6",
                "p",
                "span",
                "img",
                "strong",
                "a",
                "li",
                "i",
                "ul",
                "header",
                "footer",
                "article",
                "b",
                "em",
                "code",
                "form",
                "label",
                "ol",
                "small",
                "blockquote",
                "nav"
            ];


            /* ---------------------------------------------------- */
            /* Filtering post format classes                        */
            /* ---------------------------------------------------- */
            var postFormatFilters = [

                // Don't care post formats
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*standard)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*aside)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*audio)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*chat)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*gallery)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*image)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*link)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*quote)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*status)',
                '((?=.*post)|(?=.*blog)|(?=.*content)|(?=.*entry)|(?=.*page)|(?=.*hentry))(?=.*video)',

            ];


            /* ---------------------------------------------------- */
            /* Filtering classes func                               */
            /* ---------------------------------------------------- */
            function process_class(filter,classes){

                var a;

                for(var i = 0; i < filter.length; i++){

                    // Regex
                    a = new RegExp("(\\s|^)" + filter[i] + "(\\s|$)","gi");

                    // Replace
                    classes = classes.replace(a, ' ');

                }

                return classes.trim();

            }


            /* ---------------------------------------------------- */
            /* Filtering test                                       */
            /* ---------------------------------------------------- */
            function filter_test(filter,classN){

                // Is Single Inspector
                if(window.singleInspector){

                    // if filter has 'not single inspector' regEx
                    if(/\(\!singleInspector\)/g.test(filter)){
                        return false; // because is active
                    }else{
                        filter = filter.replace(/\(\!singleInspector\)/g,'');
                    }

                }else{

                    // if filter has 'just single inspector' regEx
                    if(/\(singleInspector\)/g.test(filter)){
                        return false; // because is not active
                    }else{
                        filter = filter.replace(/\(singleInspector\)/g,'');
                    }

                }

                // Regex
                var r = new RegExp("(\\s|^)" + filter + "(\\s|$)","gi");

                // has
                if(r.test(classN)){
                    return true;
                }else{ // not have
                    return false;
                }

            }


            /* ---------------------------------------------------- */
            /* Deleting non-acceptable classes & ids                */
            /* ---------------------------------------------------- */
            function delete_bad_terms(classes){

                // list
                var classesArray = [];

                // Clean
                classes = $.trim(classes);

                // Clean multispaces
                classes = classes.replace(/\s\s+/g, ' ');

                // Check if still there have another class
                if(classes.indexOf(" ") != -1){

                    var classessplit = classes.split(" ");

                    // Split with space
                    var v;
                    for(var i = 0; i < classessplit.length; i++){

                        // Clean
                        v = $.trim(classessplit[i]);

                        // Push
                        if(/([\u2018\u2019\u201A\u201B\u2032\u2035\u201C\u201D]|\{|\}|\:|\<|\>|\(|\)|\[|\]|\~|\"|\'|\?|\\)/g.test(v) == false){
                            classesArray.push(v);
                        }

                    }

                }else{

                    // Push if single.
                    if(/([\u2018\u2019\u201A\u201B\u2032\u2035\u201C\u201D]|\{|\}|\:|\<|\>|\(|\)|\[|\]|\~|\"|\'|\?|\\)/g.test(classes) == false){
                        classesArray.push(classes);
                    }

                }

                return classesArray.join(" ");

            }


            /* ---------------------------------------------------- */
            /* Espacing some new ID and Class chars                 */
            /* ---------------------------------------------------- */
            function html5_espace_attr(value){

                if(value === null | value === undefined || typeof value == typeof undefined || value == false || value == true){
                    return value;
                }

                return delete_bad_terms(value).replace(/(@|\.|\/|!|\*|#|\+)/g,"\\$1");

            }


            /* ---------------------------------------------------- */
            /* Get Best Class Name                                  */
            /* ---------------------------------------------------- */
            /*
                 the most important function in yellow pencil scripts
                  this functions try to find most important class name
                  in classes.

                  If no class, using ID else using tag name.
             */
            window.reGetBestClass = false;
            function get_best_class($element){

                // Default Variables
                var filteredClasses = '',
                DigitalClasses = '',
                numberRex = /\d+/,
                id,tag,element,passedFilter,return_selector,goodClassFounded,digitalClassFounded,classes;

                // Cache
                element = $($element);

                // Element Classes
                classes = element.attr("class");

                // Clean The Element Classes
                if (classes !== undefined && classes !== null) {
                    classes = $.trim(class_cleaner(html5_espace_attr(classes)));
                }

                // Cache id and tagname.
                id = element.attr("id");
                tag = element[0].nodeName.toLowerCase();

                if (tag == 'body' || tag == 'html') {
                    return tag;
                }

                // If Element has ID, Return ID.
                if (isDefined(id)){

                    // trim
                    id = $.trim(html5_espace_attr(id));

                    // Check if ID has number.
                    if(numberRex.test(id)){

                        // allow just some digital ids
                        var isIdAllowed = false;

                        // This id in allowed list?
                        for(var d = 0; d < allowedDigitalIds.length; d++){

                            // yes
                            if(filter_test(allowedDigitalIds[d],id)){
                                isIdAllowed = true;
                                break;
                            }

                        }

                        // else not allow
                        if(isIdAllowed == false){
                            id = '';
                        }

                    }


                    // Blocked id checks
                    if (id != '') {

                        // Check if a blocked ID
                        for(var f = 0; f < blockedIds.length; f++){

                            if(filter_test(blockedIds[f],id)){
                                id = '';
                                break;
                            }

                        }

                    }


                    // return the available id
                    if (id != '') {
                        return '#' + id;
                    }


                }


                // If has classes.
                if (classes !== undefined && classes !== null) {

                    // Classes to array.
                    var ArrayClasses = get_classes_array(classes);

                }


                // we want never use some class names. so disabling this classes.
                if(isDefined(ArrayClasses)){

                    // Trim
                    filteredClasses = $.trim(classes);

                    // If length?
                    if(filteredClasses.length > 1){

                        filteredClasses = process_class(blockedClasses,filteredClasses);

                    }

                    // Update filtered Classes
                    filteredClasses = $.trim(filteredClasses);

                }


                var hasFilteredClasses = false;
                var hasFilteredClasses2 = false;

                // If Has Filtered classes
                if (filteredClasses != ''){

                    // yes, have.
                    hasFilteredClasses = true;

                    // Replace significant classes and keep best classes.
                    var filteredClasses2 = filteredClasses;

                    // Replace all non useful classes
                    if(isDefined(filteredClasses2)){

                        // If has
                        if(filteredClasses2.length > 1){

                            filteredClasses2 = process_class(unPreferedClasses,filteredClasses2);

                        }

                        // trim
                        filteredClasses2 = $.trim(filteredClasses2);

                    }

                }


                // If Has Filtered classes2
                if ($.trim(filteredClasses2) != ''){

                    // Yes, have.
                    hasFilteredClasses2 = true;

                }


                // Make as array.
                var filteredClassesArray = get_classes_array(filteredClasses);
                var filteredClasses2Array = get_classes_array(filteredClasses2);

                // Foreach classes and exclude nummeric classes
                if(hasFilteredClasses2){

                    var v;
                    var isNum = false;
                    for(var i = 0; i < filteredClasses2Array.length; i++){

                        // Value
                        v = filteredClasses2Array[i];

                        // default
                        isNum = false;

                        if(numberRex.test(v)){
                            isNum = true;
                        }

                        // Don't see as nummeric class
                        for(var s = 0; s < filterNumSkipBest.length; s++){

                            // skip
                            if(isNum == true && filter_test(filterNumSkipBest[s],v) == true){
                                isNum = false;
                                break;
                            }

                        }

                        // Has number
                        if(isNum){

                            passedFilter = true;

                            for(var m = 0; m < blockedDigitalClasses.length; m++){

                                // Not has page-item class | not use vc_'s dynamic class
                                if(filter_test(blockedDigitalClasses[m],v) == true){

                                    passedFilter = false;
                                    break;

                                }

                            }

                            // Added as nummeric classes
                            if(passedFilter){
                                DigitalClasses += ' ' + v;
                            }

                        }

                    }

                }

                var filteredDigitalArray = get_classes_array(DigitalClasses);


                // Clean Up FilteredClasses by digits.
                if(hasFilteredClasses){
                    var cleanedFilter = [], cleanedFilter2 = [], i;
                    for(i = 0; i < filteredClassesArray.length; i++){
                        if(!numberRex.test(filteredClassesArray[i])){
                            cleanedFilter.push(filteredClassesArray[i]);
                        }
                    }
                    filteredClassesArray = cleanedFilter;
                    filteredClasses = filteredClassesArray.join(" ");

                    if(hasFilteredClasses2){
                        for(i = 0; i < filteredClasses2Array.length; i++){
                            if(!numberRex.test(filteredClasses2Array[i])){
                                cleanedFilter2.push(filteredClasses2Array[i]);
                            }
                        }
                        filteredClasses2Array = cleanedFilter2;
                    }
                }


                // search in good class filter
                if(hasFilteredClasses2){

                    goodClassFounded = false;

                    // filtered classes
                    for (var i = 0; i < filteredClasses2Array.length; i++){

                        // good filters
                        for(var u = 0; u < preferedClasses.length; u++){

                            if(filter_test(preferedClasses[u],filteredClasses2Array[i])){

                                // if this is "post" and has hentry, prefer hentry
                                if(filteredClasses2Array[i] == 'post' && filteredClasses2Array.indexOf("hentry") != -1){
                                    break;
                                }

                                goodClassFounded = true;
                                return_selector = "." + filteredClasses2Array[i];
                                break;

                            }

                        }

                        // stop if founded
                        if(goodClassFounded){
                            break;
                        }

                    }

                }


                // Try filtered Classes V1 if not founded in v2
                if(goodClassFounded == false && hasFilteredClasses == true){

                    // filtered classes
                    for (var i = 0; i < filteredClassesArray.length; i++){

                        // good filters
                        for(var u = 0; u < preferedClasses.length; u++){

                            if(filter_test(preferedClasses[u],filteredClassesArray[i])){
                                goodClassFounded = true;
                                return_selector = "." + filteredClassesArray[i];
                                break;
                            }

                        }

                        // stop if founded
                        if(goodClassFounded){
                            break;
                        }

                    }

                }


                // Some nummeric classes is important.
                if(DigitalClasses != ''){

                    digitalClassFounded = false;

                    for(var p = 0; p < filteredDigitalArray.length; p++){

                        for(var e = 0; e < preferedDigitalClasses.length; e++){

                            // is one good nummeric? great
                            if(filter_test(preferedDigitalClasses[e],filteredDigitalArray[p]) == true){
                                digitalClassFounded = true;
                                return_selector = "." + filteredDigitalArray[p];
                                break;
                            }

                        }

                        if(digitalClassFounded){
                            break;
                        }

                    }

                }


                // use input type for selector
                if(tag == "input" && window.reGetBestClass == false){

                    var type = element.attr("type");

                    // Single Inspector
                    if(mainBody.hasClass("wyp-single-inspector-active")){

                        window.reGetBestClass = true;

                        var className = get_best_class($element);

                        if(isDefined(type)){
                            return_selector = 'input[type=' + type + ']'+className;
                        }

                        window.reGetBestClass = false;

                    // Default Inspector
                    }else{

                        if(isDefined(type)){
                            return_selector = 'input[type=' + type + ']';
                        }

                    }


                }


                // Prefered Tag
                var preferedTag = false;

                // Try find prefered tags
                for(var n = 0; n < simpleLikedTags.length; n++){

                    if(simpleLikedTags[n] == tag){
                        preferedTag = tag;
                        break;
                    }

                }


                var lastLuckNummeric = false;

                // if not have any good selector, try nummeric class which pass neverUseNum filter
                if(DigitalClasses != ''){
                    lastLuckNummeric = ai_class(filteredDigitalArray);
                }


                // Matchless Classes for single Inspector
                var matchlessClass = false;

                if(mainBody.hasClass("wyp-single-inspector-active")){

                    // Filtered 2 classes: AI
                    matchlessClass = matchless2(ai_class(filteredClasses2Array,true,false));

                    // Filtered 2 classes: NO AI
                    if(matchlessClass == false){
                        matchlessClass = matchless2(filteredClasses2Array);
                    }


                    // filtered 1 classes: AI
                    if(matchlessClass == false){
                        matchlessClass = matchless2(ai_class(filteredClassesArray,true,false));
                    }

                    // filtered 1 classes: NO AI
                    if(matchlessClass == false){
                        matchlessClass = matchless2(filteredClassesArray);
                    }

                }


                // prefered Classes
                var classPrefered = false;

                // Filtered 2 classes: AI
                var classPrefered = ai_class(filteredClasses2Array,false,false);

                // filtered 1 classes: AI
                if(classPrefered == false){
                    classPrefered = ai_class(filteredClassesArray,false,false);
                }

                // Recommended selector
                if(isDefined(return_selector)){ // Good Classes

                    return return_selector;

                // Cool matchless class for single Inspector
                }else if(matchlessClass != false){

                    return matchlessClass;

                // Try to use prefered class
                }else if(classPrefered != false){

                    return classPrefered;

                // use prefered tags
                }else if(preferedTag != false){

                    return preferedTag;

                // use a nummeric class
                }else if(lastLuckNummeric != false){

                    return lastLuckNummeric;

                // Use any tag if not id
                }else if(tag != 'div'){

                    return tag;

                // Use any class if not have alternative.
                }else if(filteredClasses != '' && filteredClasses != undefined && filteredClasses != null){

                    // Get AI class.
                    var aiClass = ai_class(filteredClassesArray);

                    // this func will use any class if not have an alternative
                    // but blocked digital classes are is dangerous, because these classes
                    // is dynamic, changes on page re-load. Use div tag but
                    // never use blocked digital class.
                    var passedBlockedDigital = true;

                    // Loops
                    for(var m = 0; m < blockedDigitalClasses.length; m++){

                        // Not has page-item class | not use vc_'s dynamic class
                        if(filter_test(blockedDigitalClasses[m],aiClass.replace(/^(\.|#)/g, '')) == true){

                            passedBlockedDigital = false;
                            break;

                        }

                    }

                    // return AI class if pass blocked digital classes
                    if(passedBlockedDigital){
                        return aiClass;
                    }else{
                        return tag; // Return tag even "div".
                    }

                }else{

                    return tag;

                }

            }


            /* ---------------------------------------------------- */
            /* Finds matchless2 Classes                             */
            /* ---------------------------------------------------- */
            function matchless2(classes){

                // ai_class returns false
                if(classes === false || classes == ''){
                    return false;
                }

                // Find
                var matchlessClasses = classes.sort(function(b, a) {
                    return iframeBody.find("."+b).length - iframeBody.find("."+a).length;
                });

                // be sure it is just 1
                if(iframeBody.find("."+matchlessClasses[0]).length == 1){

                    return "." + matchlessClasses[0];

                }

                return false;

            }


            /* ---------------------------------------------------- */
            /* Finds AI Classes                                     */
            /* ---------------------------------------------------- */
            function ai_class(classes,array,alternative){

                // 0 classes
                if(classes.length == 0){
                    return false;
                }

                // 1 classes: has alternative
                if(classes.length == 1 && alternative == true){
                    return false;
                }

                // 1 classes: no alternative
                if(array == false){

                    if(classes.length == 1 && alternative == false){

                        if(classes[0].length > 1){

                            return "." + classes[0];

                        }else{

                            return false;

                        }

                    }

                }else if(classes.length == 1 && alternative == false){

                    if(classes[0].length > 1){

                        return classes;

                    }else{

                        return false;

                    }

                }


                // Variables
                var newClasses = [],new2Classes = [],depthArray = [],tagArray = [],i,new4Classes = [];

                // Data
                var a1 = []; // just classes which has "-"
                var a2 = []; // just classes which has "_"

                // Filter: Difference parent depth and difference HTML tags
                for(i = 0; i < classes.length; i++){

                    // Arrays
                    depthArray  = [];
                    tagArray = [];

                    if(/(\,|\[|\]|\#)/g.test(classes[i])){
                        continue;
                    }

                    // Each current class
                    iframe.find("." + classes[i]).each(function(){

                        var element = $(this);

                        // Push depth len
                        depthArray.push(element.parents().length);

                        // Push tags used
                        tagArray.push(element[0].nodeName);

                    });

                    var depthArrayEquals = depthArray.every(function(v, i, a){
                        return v === a[0];
                    });

                    var tagArrayEquals = tagArray.every(function(v, i, a){
                        return v === a[0];
                    });

                    // Passed depth and tag methods
                    if(depthArrayEquals && tagArrayEquals){
                        newClasses.push(classes[i]);
                    }

                }

                // Use default if newClasses is not avaiable
                if(newClasses.length == 0){
                    newClasses = classes;
                }


                // Filter a1
                for(i = 0; i < newClasses.length; i++){

                    // just classes which has "-"
                    if (/\_/g.test(newClasses[i]) == false && /\-/g.test(newClasses[i]) == true){
                        a1.push(newClasses[i]);
                    }

                }


                // Try a2 if a1 is empty.
                if(a1.length == 0){

                    // Filter a2
                    for(i = 0; i < newClasses.length; i++){

                        // just classes which has "-"
                        if (/\-/g.test(newClasses[i]) == false && /\_/g.test(newClasses[i]) == true){
                            a2.push(newClasses[i]);
                        }

                    }

                    // a1 and a2 is empty
                    if(a2.length == 0){

                        new2Classes = newClasses;

                    }else{

                        // Use a2 if it is available
                        new2Classes = a2;

                    }

                }else{ // Use a1 if available

                    new2Classes = a1;

                }



                // Filter: 3 time repeated char classes
                for(i = 0; i < new2Classes.length; i++){

                    // If char not repeat 3 time as tessst.
                    if (/(.)\1\1/.test(new2Classes[i]) == false){
                        new4Classes.push(new2Classes[i]);
                    }

                }

                // Use non filtered classes if any class cant pass
                if(new4Classes.length == 0){
                    new4Classes = newClasses;
                }


                // Sort
                new4Classes.sort(function(a, b){
                    return b.length - a.length;
                });


                // Return format
                if(array){
                    return new4Classes;
                }else{
                    return "." + new4Classes[0];
                }

            }


            /* ---------------------------------------------------- */
            /* Getting current selector                             */
            /* ---------------------------------------------------- */
            function get_current_selector(){

                // Get current
                var parentsv = body.attr("data-clickable-select");

                var newSelector = false;

                // If has
                if (isDefined(parentsv)) {

                    // If unvalid
                    if(check_selector(parentsv,true,false, false) == false){

                        newSelector = get_parents(null, "default");

                    }else{ // if valid return

                        return parentsv;

                    }

                // If not has selector
                } else {

                    // return
                    newSelector = get_parents(null, "default");

                }

                // Replace old with new
                if(is_content_selected()){

                    if(newSelector != false){

                        if(iframe.find(".wyp-selected-others").length == 0 && iframe.find(newSelector).length > 1){
                            body.addClass("wyp-single-inspector-active");
                            newSelector = get_parents(null, "default");
                            body.removeClass("wyp-single-inspector-active");
                        }

                        set_selector(newSelector, get_selected_element(), true);
                        return newSelector;

                    }
                }

            }


            /* ---------------------------------------------------- */
            /* Finds bad queries                                    */
            /* ---------------------------------------------------- */
            function filter_bad_queries(data){
                return  data.replace(/[\u2018\u2019\u201A\u201B\u2032\u2035\u201C\u201D]/g,'');
            }


            /* ---------------------------------------------------- */
            /* Checks if selector valid                             */
            /* ---------------------------------------------------- */
            function check_selector(selector,mustHas,searchInBody,error){

                var content;
                if(searchInBody){
                    content = iframeBody;
                }else{
                    content = iframe;
                }

                try {

                    // Get element
                    var element = content.find(selector);

                    // Return false if document not have this element
                    if(mustHas == true && element.length == 0){
                        if(error){
                            return "The selector doesn't match any element on this page";
                        }else{
                            return false;
                        }
                    }else{
                        if(error){
                            return true;
                        }else{
                            return element; // return true if valid
                        }
                    }


                } catch (e) {
                    if(error){
                        return e.message;
                    }else{
                        return false;
                    }
                }

            }


            /* ---------------------------------------------------- */
            /* Getting minimized CSS. Cleaning spaces.              */
            /* ---------------------------------------------------- */
            function get_minimized_css(data,media){

                // Clean.
                data = data.replace(/(\r\n|\n|\r)/g, "").replace(/\t/g, '');

                // Don't care rules in comment.
                data = data.replace(/\/\*(.*?)\*\//g, "");

                // clean.
                data = data.replace(/\}\s+\}/g, '}}').replace(/\s+\{/g, '{');

                // clean.
                data = data.replace(/\s+\}/g, '}').replace(/\{\s+/g, '{');

                // replace queries
                data = filter_bad_queries(data);

                // Don't care rules in media query
                if(media === true){
                    data = data.replace(/@media(.*?)\}\}/g, '').replace(/@?([a-zA-Z0-9_-]+)?keyframes(.*?)\}\}/g, '').replace(/@font-face(.*?)\}\}/g, '').replace(/@import(.*?)\;/g,'').replace(/@charset(.*?)\;/g,'');
                }

                // data
                return data;

            }


            // Get defined selector controller.
            window.definedSelectorArray = [];
            window.definedSelectorArrayEnd = false;

            /* ---------------------------------------------------- */
            /* Get Defined Selectors                                */
            /* ---------------------------------------------------- */
            function get_defined_selector(){

                var data = window.definedStyleData;

                var allSelectors,i;

                // Don't search it always
                if(window.definedSelectorArray.length === 0){

                    // if no data, stop.
                    if(data == ''){
                        return false;
                    }

                    data = data.toString().replace(/\}\,/g, "}");

                    // Getting All CSS Selectors.
                    allSelectors = array_cleaner(data.replace(/\{(.*?)\}/g, '|BREAK|').split("|BREAK|"));

                }

                // Vars
                var foundedSelectors = [];
                var selector;

                // get cached selector Array
                if(window.definedSelectorArrayEnd){
                    allSelectors = window.definedSelectorArray;
                }

                if(isUndefined(allSelectors)){
                    return false;
                }


                // Filtering bad classes
                var element, passedClasses;


                // Each All Selectors
                for (i = 0; i < allSelectors.length; i++){

                    // Get Selector.
                    selector = space_cleaner(allSelectors[i]);
                    selector = space_cleaner(selector.replace(/(\{|\})/g,'').replace(/>(\.|\#|[a-zA-Z-_])/g, "> ").replace(/(\.|\#|[a-zA-Z-_])>/g, " >"));

                    // YP not like so advanced selectors.
                    if(selector.indexOf(",") != -1 || selector.indexOf("*") != -1 || selector.indexOf("/") != -1){
                        continue;
                    }

                    // skip html5 advanced terms
                    if(/([\u2018\u2019\u201A\u201B\u2032\u2035\u201C\u201D]|\{|\}|\:|\<|\>|\(|\)|\[|\]|\~|\"|\'|\?|\\)/g.test(selector) == true){
                        continue;
                    }

                    // Not basic html tag selectors.
                    if(selector.indexOf("#") == -1 && selector.indexOf(".") == -1){
                        continue;
                    }

                    // min two
                    if(get_selector_array(selector).length < 2){
                        continue;
                    }

                    element = check_selector(selector,true,true,false);

                    // be sure it valid
                    if(element === false){
                        continue;
                    }

                    // Bad EX: span.class, h1#id
                    if(/[a-zA-Z-_0-9](\.|#)/g.test(selector)){
                        continue;
                    }

                    // Cache other selectors.
                    if(window.definedSelectorArrayEnd === false){
                        window.definedSelectorArray.push(selector);
                    }

                    // Founded Selector
                    if(element.hasClass("wyp-selected")){
                        foundedSelectors.push(selector);
                    }

                }

                // Don't read again css files. cache all defined CSS selectors.
                window.definedSelectorArrayEnd = true;

                // New selectors
                var foundedNewSelectors = [];
                var o,selectorBefore,re;

                // Each all founded selectors.
                // Don't use if has non useful classes as format-link etc.
                for(i = 0; i < foundedSelectors.length; i++){

                    selectorBefore = foundedSelectors[i];
                    passedClasses = true;

                    // Check if has an useful class
                    for(o = 0; o < blockedClasses.length; o++){

                        // Regex
                        re = new RegExp("(\\s|^|\.|\#)" + blockedClasses[o] + "(\\s|$)","gi");

                        // Founded an non useful class.
                        if(re.test(selectorBefore)){
                            passedClasses = false;
                            break;
                        }

                    }

                    // Check if has an useful class
                    for(o = 0; o < blockedClasses.length; o++){

                        // Regex
                        re = new RegExp("(\\s|^|\.|\#)" + blockedClasses[o] + "(\\s|$)","gi");

                        // Founded an non useful class.
                        if(re.test(selectorBefore)){
                            passedClasses = false;
                            break;
                        }

                    }

                    // Check if has an useful class
                    for(o = 0; o < unPreferedClasses.length; o++){

                        // Regex
                        re = new RegExp("(\\s|^|\.|\#)" + unPreferedClasses[o] + "(\\s|$)","gi");

                        // Founded an non useful class.
                        if(re.test(selectorBefore)){
                            passedClasses = false;
                            break;
                        }

                    }

                    // Check if has bad class
                    for(o = 0; o < postFormatFilters.length; o++){

                        // Regex
                        re = new RegExp("(\\s|^|\.|\#)" + postFormatFilters[o] + "(\\s|$)","gi");

                        // Founded an bad class.
                        if(re.test(selectorBefore)){
                            passedClasses = false;
                            break;
                        }

                    }

                    // Successful.
                    if(passedClasses === true){
                        foundedNewSelectors.push(foundedSelectors[i]);
                    }

                }

                return foundedNewSelectors;

            }


            /* ---------------------------------------------------- */
            /* This function cropping selector until 5 class        */
            /* ---------------------------------------------------- */
            function crop_selector(selector){

                var limit = 5;

                // generate long selector as we want with: "window.minCroppedSelector"
                if(window.minCroppedSelector != false){
                    limit = window.minCroppedSelector;
                }

                // Keep selectors smart and short!
                if(get_selector_array(selector).length > 5){

                    // short Selector Ready
                    var shortSelectorReady = false;

                    // Find a founded elements
                    var foundedElements = iframe.find(selector).length;

                    // Get array from selector.
                    var shortSelector = get_selector_array(selector);

                    // Each array items
                    $.each(shortSelector,function(){

                        if(shortSelectorReady === false){

                            // Shift
                            shortSelector.shift();

                            // make it short
                            var shortSelectorString = shortSelector.toString().replace(/\,/g," ");

                            // Search
                            var foundedElShort =  iframe.find(shortSelectorString).length;

                            // Shift until make it maximum 5 item
                            if(shortSelector.length <= 5 && foundedElements == foundedElShort){
                                shortSelectorReady = true;
                                selector = shortSelectorString;
                            }

                        }

                    });

                }

                return selector;

            }


            /* ---------------------------------------------------- */
            /* This function trying all settings for the selector   */
            /* if there 10 classes in the selector                  */
            /* it will try 100 combine                              */
            /* ---------------------------------------------------- */
            function multiple_variation(selector){

                // Get current selector length
                var selectorLen = iframejs.querySelectorAll(selector).length;

                // Orginal selector
                var selectorOrginal = crop_selector(selector);

                // will keep the results in this array
                var resultArray = [];

                var last,first,cssSelector = '';

                // Need to first and last
                if(selector.indexOf(">") == -1){

                    // The Array
                    selector = get_selector_array(selector);

                    // Last element
                    last = selector[selector.length - 1];
                    selector.pop();

                    // First element
                    first = selector[0];
                    selector.shift();

                // Getting first and last in the selector which has ">" symbols.
                }else{

                    var getEnd,getStart,arr,firstReg,lastReg,centerSelector,centerMatch;
                    var type = null;

                    // Match for Gettin End
                    getEnd = selector.match(/\>(?!.*\>)(.*?)$/g).toString().replace(/(\s)?>(\s)?/, '');

                    // Match for getting start
                    getStart = selector.match(/^(.*?)\s\>/g).toString().replace(/(\s)?>(\s)?/, '');

                    // Check if > in start or end.
                    if (getEnd.indexOf(' ') == -1){
                        arr = getStart.split(" ");
                        type = "getStart";
                    } else if (getStart.indexOf(' ') == -1) {
                        arr = getEnd.split(" ");
                        type = "getEnd";
                    }

                    // get the getEnd
                    if(type == null){

                        if (getEnd.split(" ").length > getStart.split(" ").length){
                            arr = getEnd.split(" ");
                            type = "getEnd";
                        }else{ // get the getStart
                            arr = getStart.split(" ");
                            type = "getStart";
                        }

                    }

                    // cache first and last selectors and delete
                    if (arr.length - 2 > 1) {

                        // cache first & last
                        first = arr[0];
                        last = arr[arr.length - 1];

                        // RegExp for parsing
                        firstReg = new RegExp("^(.*?) > " + first, "g");
                        lastReg = new RegExp(last + " > (.*?)$", "g");

                        if(selector.match(firstReg) != null && selector.match(firstReg) != null){

                            // Check the type and update first/last
                            if (type == "getEnd") {
                                first = selector.match(firstReg).toString();
                            } else if (type == 'getStart') {
                                last = selector.match(lastReg).toString();
                            }

                            // Get just between
                            arr.pop();
                            arr.shift();

                            centerSelector = arr;

                        }

                    }

                    // Trying another method. It will get center of selector.
                    if (centerSelector == undefined){

                        // Test it
                        if (/\>(.*?)\>/g.test(selector)) {

                            // Get center
                            centerMatch = selector.match(/\>(.*?)\>/g).toString().replace(/(\s)?>(\s)?/g, '');

                            // must has a space
                            if (centerMatch.indexOf(" ") != -1) {

                                // parse
                                arr = centerMatch.split(" ");

                                // cache first and last selectors and delete
                                if (arr.length - 2 > 1) {

                                    // Cache first and last.
                                    first = arr[0];
                                    last = arr[arr.length - 1];

                                    // RegExp for parsing
                                    firstReg = new RegExp("^(.*?) > " + first, "g");
                                    lastReg = new RegExp(last + " > (.*?)$", "g");

                                    // Check the type and update first & last
                                    first = selector.match(firstReg).toString();
                                    last = selector.match(lastReg).toString();

                                    arr.pop();
                                    arr.shift();

                                    centerSelector = arr;

                                }

                            }

                        }

                    }

                    // Check if it is available.
                    if(centerSelector != undefined){
                        selector = centerSelector;
                    }else{
                        return selectorOrginal;
                    }

                } // > symbol codes end here.


                // Try to delete structural selectors
                var newSelector = [],passed,r;
                for(var i = 0; i < selector.length; i++){

                    passed = true;

                    for(var ix = 0; ix < unPreferedSelectors.length; ix++){

                        // Regex
                        r = new RegExp("(\\s|^)\." + unPreferedSelectors[ix] + "(\\s|$)","gi");

                        // has
                        if(r.test(selector[i])){
                            passed = false;
                            break;
                        }

                    }

                    if(passed){
                        newSelector.push(selector[i]);
                    }

                }


                if(isUndefined(first) || isUndefined(first)){
                    return selectorOrginal;
                }


                // just try combine first and last
                if(first.indexOf(" ") == -1 && last.indexOf(" ") == -1){
                    cssSelector = space_cleaner(first + window.separator + last);
                }


                // is valid? // first & last
                if(check_selector(cssSelector,false,false,false) && window.minCroppedSelector == false){

                    // Combine just first and last if there were a lot selector but all were structural selectors.
                    if(selector.length >= 1 && newSelector.length == 0 && iframe.find(cssSelector).length == selectorLen){
                        return cssSelector;
                    }

                }


                // Update only if high than 1
                if(newSelector.length > 1){
                    selector = newSelector;
                }


                // Must be minimum 2 class excluding
                // first and last classes in the selector.
                if(selector.length < 2){
                    return selectorOrginal;
                }


                // Prefer only classes which used in same parent depth and same tag names.
                // The class that which used in difference depth is structural class
                // The class that which used in difference tags is structural class.
                var depthArray,tagArray,ek;
                var newSelector2 = [];
                for(i = 0; i < selector.length; i++){

                    if(/.|#/g.test(selector[i])){

                        // Arrays
                        depthArray  = [];
                        tagArray = [];

                        // Each current class
                        iframe.find(selector[i]).each(function(){

                            ek = $(this);

                            // Push depth len
                            depthArray.push(ek.parents().length);

                            // Push tags used
                            tagArray.push(ek[0].nodeName);

                        });

                        var depthArrayEquals = depthArray.every(function(v, i, a){
                            return v === a[0];
                        });

                        var tagArrayEquals = tagArray.every(function(v, i, a){
                            return v === a[0];
                        });

                        // Passed depth and tag methods
                        if(depthArrayEquals && tagArrayEquals){
                            newSelector2.push(selector[i]);
                        }

                    }

                }


                // Update only if high than 1
                if(newSelector2.length > 1){
                    selector = newSelector2;
                }


                // Variables
                var nexts = selector.slice(0);
                var current,i,n,currentNext,testSelector;

                // Combine All
                for(i = 0; i < selector.length; i++){

                    current = selector[i];

                    // all selector for test
                    testSelector = first + window.separator + current + window.separator + last;

                    // Add if pass
                    if(iframejs.querySelectorAll(testSelector).length == selectorLen){
                        resultArray.push(testSelector);
                    }

                    // Shift on  each
                    nexts.shift();

                    // Use current with all other selector parts
                    for(n = 0; n < nexts.length; n++){

                        currentNext = nexts[n];

                        // all selector for test
                        testSelector = first + window.separator + current + window.separator + currentNext + window.separator + last;

                        // Add if pass
                        if(iframejs.querySelectorAll(testSelector).length == selectorLen){
                            resultArray.push(testSelector);
                        }

                    }

                }


                // There not have any variation
                if(resultArray.length == 0){
                    return selectorOrginal;
                }


                // Find the short
                resultArray.sort(function(a,b){
                    return a.length - b.length;
                });


                // Find the selector long as we want with "window.minCroppedSelector"
                if(window.minCroppedSelector != false){

                    // loop the results
                    for(var k = 0; k < resultArray.length; k++){

                        // find the longer selector
                        if(get_selector_array(resultArray[k]).length >= window.minCroppedSelector){
                            return space_cleaner(resultArray[k]);
                        }

                    }

                    return selectorOrginal;

                }


                // Return the result
                return space_cleaner(resultArray[0]);

            }


            /* ---------------------------------------------------- */
            /* Get Parents                                          */
            /* ---------------------------------------------------- */
            function get_parents(element, status){

                // If parent already has.
                var parentsv = body.attr("data-clickable-select");

                // If status default, return current data.
                if (status == 'default' && window.minCroppedSelector == false) {

                    // If defined
                    if (isDefined(parentsv)) {

                        // if valid return
                        if(check_selector(parentsv,true,false,false) != false){
                            return parentsv;
                        }

                    }

                }

                if(status == 'defaultNoCache'){
                    status = 'default';
                }


                if(element === null){
                    element = get_selected_element();
                }


                // Be sure this item is valid.
                if (element[0] === undefined || element[0] === false || element[0] === null) {
                    return false;
                }


                // Tag info
                var tag = element[0].tagName;


                // Is Single Inspector
                if(mainBody.hasClass("wyp-single-inspector-active")){
                    status = 'sharp';
                }


                // HTML tag not supported.
                if(isUndefined(tag) || tag == 'HTML'){
                    return false;
                }

                // If body, return.
                if (tag == 'BODY') {
                    return 'body';
                }


                // Getting item parents.
                var parents = element.parentsUntil("body"), selector = '', reseted, inSelected, currentSelector,previousSelector = '';


                // Get last selector
                var lastSelector = get_best_class(element);


                // Return if element selector is ID.
                if(/#/g.test(lastSelector) == true){
                    return lastSelector;
                }


                // Resets
                var resetSelectors = [], dontReset = false, dontResetLive = false;


                // Check if there is waited selector
                if(window.minCroppedSelector != false){

                    // waited selector by long.
                    if(window.minCroppedSelector >= parents.length){
                        dontReset = true;
                    }

                }


                // Foreach all loops.
                for (var i = parents.length - 1; i >= 0; i--) {

                    // Default false
                    reseted = false;

                    // Get Selector of the current parent element.
                    currentSelector = get_best_class(parents[i]);

                    // Don't reset if waited selector is long
                    dontResetLive = false;
                    if(window.minCroppedSelector != false){
                        if((i-1) <= window.minCroppedSelector){
                            dontResetLive = true;
                        }
                    }

                    // Check if this has a class or ID.
                    if(/\.|#/g.test(currentSelector) == true && dontReset == false && dontResetLive == false){

                        // Check if need or no need for generated previous selectors
                        if(iframe.find(currentSelector).length == 1){

                            // No Need to previous Selectors
                            if (status != 'sharp') {

                                // Reset old selectors
                                selector = currentSelector + window.separator;

                                resetSelectors.push(currentSelector);
                                reseted = true;

                            }else{

                                // In Single Selector reset only if no need nth-child selector.
                                if (single_selector(selector, true).indexOf("nth-child") == -1) {

                                    // Reset old selectors
                                    selector = currentSelector + window.separator;

                                    resetSelectors.push(currentSelector);
                                    reseted = true;

                                }

                            }

                        }

                    }


                    // Can't reset.
                    // Continue to add current class name/id/tag to current selector
                    if(reseted == false){

                        // Check if same selector has in the selector
                        inSelected = iframe.find(selector+window.separator+currentSelector+window.separator+currentSelector+","+selector+window.separator+previousSelector+window.separator+currentSelector).length;

                        if (status == 'default' && inSelected > 0 && space_cleaner(selector).trim() != ''){
                            selector = space_cleaner(selector).trim() + " > " + currentSelector + window.separator; // Add With '>' separator
                        }else{
                            selector += currentSelector + window.separator; // Add with space separator
                        }

                    }

                    previousSelector = currentSelector;


                } // Each end.


                // Fix google map contents
                if(selector.indexOf(".gm-style") != -1){
                    selector = '.gm-style';
                }


                // Check if same selector has in the selector
                inSelected = iframe.find(selector+window.separator+lastSelector+window.separator+lastSelector+","+selector+window.separator+currentSelector+window.separator+lastSelector).length;

                if (inSelected > 0){
                    selector = space_cleaner(selector + " > " + lastSelector); // Add With '>' separator
                }else{
                    selector = space_cleaner(selector + window.separator + lastSelector); // Add with space separator
                }


                // If there is multiple reset indexs and the selected reset is not a ID
                // so lets find the best reset selector.
                if(resetSelectors.length > 1 && selector.charAt(0) != '#'){


                    // Try to delete structural selectors
                    var newReset1 = [],passed,r;
                    for(var i = 0; i < resetSelectors.length; i++){

                        passed = true;

                        for(var ix = 0; ix < unPreferedSelectors.length; ix++){

                            // Regex
                            r = new RegExp("(\\s|^)\." + unPreferedSelectors[ix] + "(\\s|$)","gi");

                            // has
                            if(r.test(resetSelectors[i])){
                                passed = false;
                                break;
                            }

                        }

                        if(passed){
                            newReset1.push(resetSelectors[i]);
                        }

                    }


                    // Try newReset2 if newReset1 has more items than 1.
                    if(newReset1.length > 1){

                        // Prefer only classes which used in same parent depth and same tag names.
                        // The class that which used in difference depth is structural class
                        // The class that which used in difference tags is structural class.
                        var depthArray,tagArray,ek;
                        var newReset2 = [];
                        for(i = 0; i < newReset1.length; i++){

                            // Arrays
                            depthArray  = [];
                            tagArray = [];

                            // Each current class
                            iframe.find(newReset1[i]).each(function(){

                                ek = $(this);

                                // Push depth len
                                depthArray.push(ek.parents().length);

                                // Push tags used
                                tagArray.push(ek[0].nodeName);

                            });

                            var depthArrayEquals = depthArray.every(function(v, i, a){
                                return v === a[0];
                            });

                            var tagArrayEquals = tagArray.every(function(v, i, a){
                                return v === a[0];
                            });

                            // Passed depth and tag methods
                            if(depthArrayEquals && tagArrayEquals){
                                newReset2.push(newReset1[i]);
                            }

                        }

                    }


                    // empty if is undefined
                    if(isUndefined(newReset2)){
                        var newReset2 = [];
                    }


                    // null as default
                    var newFirstSelector = null;


                    // Get first selector
                    if(newReset2.length > 0){
                        newFirstSelector = newReset2[newReset2.length -1];
                    }else if(newReset1.length > 0){
                        newFirstSelector = newReset1[newReset1.length -1];
                    }


                    // if is valid
                    if(newFirstSelector != null){

                        // Get all selector exlcude first class
                        var newSelector = selector.match(/(\s)(.*?)$/g).join('').toString();

                        // Get ready the new selector
                        newSelector = newFirstSelector + newSelector;

                        // be sure it is valid
                        if(check_selector(newSelector, true,false,false)){

                            // be sure this selectors make same work
                            if(comparison_selector(selector, newSelector)){
                                // update
                                selector = newSelector;

                            }

                        }

                    } // not valid

                }


                // Last Parent Query Status
                window.lastParentQueryStatus = status;


                // Return if is single selector
                if (status == 'sharp') {
                    return single_selector(selector, false);
                }


                // Check all others elements has same nodename or not.
                if(simpleLikedTags.indexOf(tag.toLowerCase()) != -1){

                    var foundedTags = [], n;
                    iframe.find(selector).each(function(){

                        n = $(this)[0].nodeName;

                        if(foundedTags.indexOf(n) == -1){
                            foundedTags.push(n);
                        }

                    });

                    // h1.test | div.test = Use "h1"
                    if(foundedTags.length > 1){

                        selector = $.trim(selector.match(new RegExp("^(.*?)" + selector_regex(lastSelector) + "$","g")).join('').toString()) + window.separator + tag.toLowerCase();

                    // if this is a single element, use the tag.
                    }else if(is_matchless2(selector,lastSelector,tag)){

                        selector = $.trim(selector.match(new RegExp("^(.*?)(?=" + selector_regex(lastSelector) + "$)","g")).join('').toString()) + window.separator + tag.toLowerCase();

                    }

                }


                // Getting selectors by CSS files.
                if(get_selector_array(selector).length > 1 && window.minCroppedSelector == false){

                    // Get defined selectors
                    var definedSelectors = get_defined_selector();

                    // Get valid defined selectors
                    var goodDefinedSelectors = [];

                    // Check is valid
                    if(definedSelectors.length > 0){

                        // Each founded selectors
                        $.each(definedSelectors,function(qx){

                            // Find the best in defined selectors
                            if(comparison_selector(selector, definedSelectors[qx])){

                                // Push
                                goodDefinedSelectors.push(definedSelectors[qx]);

                            }

                        });

                        // There is good selectors?
                        if(goodDefinedSelectors.length > 0){

                            // Find max long selector
                            var maxSelector = goodDefinedSelectors.sort(function(a, b) {
                                return b.length - a.length;
                            });

                            // Be sure more long than 10 char
                            if(maxSelector[0].length > 10){

                                // Update
                                selector = maxSelector[0];

                            }

                        }

                    }

                }


                // remove multiple spaces
                selector = space_cleaner(selector);


                // Cleans ">" symbols from selector if not need.
                if(selector.indexOf(">") != -1){

                    var length = selector.split(">").length;
                    var elementLength = iframe.find(selector).length;

                    for(var i = 1; i < length; i++){

                        if(iframe.find(selector.replace(/ > /i,' ')).length == elementLength){
                            selector = selector.replace(/ > /i,' ');
                        }

                    }

                }


                // Ready
                selector = multiple_variation(space_cleaner(selector));


                // Use as single inspector if selector is div and more than 20
                if(/( |>)div$/g.test(selector) && iframe.find(selector).length >= 20){
                    return single_selector(selector);
                }


                // Return result.
                return selector;

            }


            /* ---------------------------------------------------- */
            /* Return true if the element in same parent or         */
            /* selector match a single element                      */
            /* ---------------------------------------------------- */
            function is_matchless2(selector, lastSelector, tag){

                var element = iframe.find(selector);
                var element2 = iframe.find($.trim(selector.match(new RegExp("^(.*?)(?=" + selector_regex(lastSelector) + "$)","g")).join('').toString()) + window.separator + tag.toLowerCase());

                // If tag and class selector length is same
                if(element.length == 1 && element2.length == 1){
                    return true;

                // continue if the element length is same
                }else if(element.length == element2.length && /.|#/g.test(lastSelector)){

                    // Arrays
                    var depthArray  = [];
                    var tagArray = [];

                    // Each current class
                    iframe.find(lastSelector).each(function(){

                        var element = $(this);

                        // Push depth len
                        depthArray.push(element.parents().length);

                        // Push tags used
                        tagArray.push(element[0].nodeName);

                    });

                    var depthArrayEquals = depthArray.every(function(v, i, a){
                        return v === a[0];
                    });

                    var tagArrayEquals = tagArray.every(function(v, i, a){
                        return v === a[0];
                    });

                    // Passed depth and tag methods
                    if(depthArrayEquals && tagArrayEquals){
                        return true;
                    }

                }

                return false;

            }


            /* ---------------------------------------------------- */
            /* Draw borders.                                        */
            /* ---------------------------------------------------- */
            function draw_box(element, classes) {

                var element_p;

                if (typeof element === 'object') {
                    element_p = $(element);
                } else { // selector string
                    element_p = iframe.find(element);
                }

                // Be sure this element have.
                if (element_p.length > 0) {

                    var marginTop = element_p.css("marginTop");
                    var marginBottom = element_p.css("marginBottom");
                    var marginLeft = element_p.css("marginLeft");
                    var marginRight = element_p.css("marginRight");

                    var paddingTop = element_p.css("paddingTop");
                    var paddingBottom = element_p.css("paddingBottom");
                    var paddingLeft = element_p.css("paddingLeft");
                    var paddingRight = element_p.css("paddingRight");

                    var marginLeftOr = marginLeft;
                    var marginRightOr = marginRight;

                    if(parseInt(paddingTop) == 0){paddingTop = "7px";}
                    if(parseInt(paddingRight) == 0){paddingRight = "5px";}
                    if(parseInt(paddingBottom) == 0){paddingBottom = "7px";}
                    if(parseInt(paddingLeft) == 0){paddingLeft = "7px";}

                    iframe.find(".wyp-zero-margin-h").removeClass("wyp-zero-margin-h");

                    if(parseInt(marginTop) == 0){
                        marginTop = "5px";
                        iframe.find(".wyp-selected-boxed-margin-top").addClass("wyp-zero-margin-h");
                    }

                    //Dynamic boxes variables
                    var element_offset = element_p.offset();
                    var topBoxes = element_offset.top;
                    var leftBoxes = element_offset.left;
                    if (leftBoxes < 0) {
                        leftBoxes = 0;
                    }

                    // Width
                    var widthBoxes = element_p.outerWidth(false);
                    var heightBoxes = element_p.outerHeight(false);
                    var bottomBoxes = topBoxes + heightBoxes;

                    // Frame Height
                    var iframeHeight = iframe.height();

                    // Show 5px Margin Bottom if element not in bottom.
                    if(parseInt(marginBottom) == 0 && Math.round(bottomBoxes) != iframeHeight && Math.round(bottomBoxes+2) != iframeHeight){
                        marginBottom = "5px";
                        iframe.find(".wyp-selected-boxed-margin-bottom").addClass("wyp-zero-margin-h");
                    }

                    // bottom element or not
                    if (Math.round(bottomBoxes) == iframeHeight || Math.round(bottomBoxes+2) == iframeHeight) {
                        body.addClass("wyp-selected-bottom");
                    }else{
                        body.removeClass("wyp-selected-bottom");
                    }

                    // Window Width
                    var iframeWidth = $("#iframe").width();
                    var scroll_width = iframeWidth - iframe.find("html").width();
                    var windowWidth = $(window).width() - window.leftbarWidth - scroll_width;

                    // Extra
                    var rightExtra = 1;
                    if (is_content_selected()) {
                        rightExtra = 2;
                    }


                    // Fix scroll problems
                    if ((leftBoxes + widthBoxes) > windowWidth) {

                        widthBoxes = windowWidth - leftBoxes - rightExtra;

                    }else if(is_responsive_mod()){

                        if ((leftBoxes + widthBoxes) > iframeWidth) {
                            widthBoxes = iframeWidth - leftBoxes - scroll_width;
                        }

                        if(iframeWidth == widthBoxes && iframe.find("html").height() > $("#iframe").height()){
                            widthBoxes = widthBoxes - scroll_width;
                        }

                    }

                    // Left in pixel to right border
                    var rightBoxes = leftBoxes + widthBoxes - rightExtra;


                    // if element full width
                    if ((leftBoxes + widthBoxes + 2) >= (iframeWidth - scroll_width)) {
                        body.addClass("wyp-full-width-selected");

                    }else{
                        body.removeClass("wyp-full-width-selected");
                    }


                    // firefox dont get marginRight if is auto, so this fix problem.
                    var isMarginAuto = false;
                    if(iframeWidth == (parseFloat(marginLeft * 2)) + widthBoxes && parseFloat(marginLeft) > 0){
                        isMarginAuto = true;
                    }else if(element_p.parent().length > 0){
                        if(parseFloat(element_p.parent().width()) == ((parseFloat(marginLeft) * 2) + widthBoxes) && parseFloat(marginLeft) > 0){
                            isMarginAuto = true;
                        }
                    }


                    // Show empty margin left/right just if there have free space (if not full width)
                    if((iframeWidth - scroll_width) > (leftBoxes + widthBoxes + 30)){

                        iframe.find(".wyp-zero-margin-w").removeClass("wyp-zero-margin-w");

                        if(parseInt(marginRight) == 0){
                            marginRight = "5px";
                            iframe.find(".wyp-selected-boxed-margin-right").addClass("wyp-zero-margin-w");
                        }

                        if(parseInt(marginLeft) == 0){
                            marginLeft = "5px";
                            iframe.find(".wyp-selected-boxed-margin-left").addClass("wyp-zero-margin-w");
                        }

                    }


                    // Margin default values
                    var marginTopText = '', marginRightText = '', marginBottomText = '', marginLeftText = '';
                    if(parseInt(marginTop) > 30){marginTopText = parseInt(marginTop)+"px";}
                    if(parseInt(marginRight) > 30){marginRightText = parseInt(marginRight)+"px";}
                    if(parseInt(marginBottom) > 30){marginBottomText = parseInt(marginBottom)+"px";}
                    if(parseInt(marginLeft) > 30){marginLeftText = parseInt(marginLeft)+"px";}


                    // Padding default values
                    var paddingTopText = '', paddingRightText = '', paddingBottomText = '', paddingLeftText = '';
                    if(parseInt(paddingTop) > 30){paddingTopText = parseInt(paddingTop)+"px";}
                    if(parseInt(paddingRight) > 30){paddingRightText = parseInt(paddingRight)+"px";}
                    if(parseInt(paddingBottom) > 30){paddingBottomText = parseInt(paddingBottom)+"px";}
                    if(parseInt(paddingLeft) > 30){paddingLeftText = parseInt(paddingLeft)+"px";}

                    // Shows Auto text
                    if(isMarginAuto){
                        marginLeftText = "Auto";
                        marginRightText = "Auto";
                    }

                    // Append border elements
                    if (heightBoxes > 1 && widthBoxes > 1) {

                        if (iframe.find("." + classes + "-top").length === 0) {

                            // Border
                            var appendBox = "<div class='" + classes + "-top'></div><div class='" + classes + "-bottom'></div><div class='" + classes + "-left'></div><div class='" + classes + "-right'></div>";

                            // margin
                            appendBox += "<div class='" + classes + "-margin-left'>"+marginLeftText+"</div><div class='" + classes + "-margin-top'>"+marginTopText+"</div><div class='" + classes + "-margin-bottom'>"+marginBottomText+"</div><div class='" + classes + "-margin-right'>"+marginRightText+"</div>";

                            // padding
                            appendBox += "<div class='" + classes + "-padding-left'>"+paddingLeftText+"</div><div class='" + classes + "-padding-top'>"+paddingTopText+"</div><div class='" + classes + "-padding-bottom'>"+paddingBottomText+"</div><div class='" + classes + "-padding-right'>"+paddingRightText+"</div>";

                            // Append
                            iframeBody.append(appendBox);

                        }else{

                            // Update margin box value
                            iframe.find("." + classes + "-margin-top").text(marginTopText);
                            iframe.find("." + classes + "-margin-right").text(marginRightText);
                            iframe.find("." + classes + "-margin-bottom").text(marginBottomText);
                            iframe.find("." + classes + "-margin-left").text(marginLeftText);

                            // Update padding box value
                            iframe.find("." + classes + "-padding-top").text(paddingTopText);
                            iframe.find("." + classes + "-padding-right").text(paddingRightText);
                            iframe.find("." + classes + "-padding-bottom").text(paddingBottomText);
                            iframe.find("." + classes + "-padding-left").text(paddingLeftText);

                        }


                        // Variables for inline CSS
                        var topTop = parseFloat(topBoxes) - parseFloat(marginTop);
                        var leftLeft = parseFloat(leftBoxes) - parseFloat(marginLeft);
                        var bottomBottom = bottomBoxes - parseFloat(paddingBottom);
                        var rightRight = rightBoxes - parseFloat(paddingRight);


                        // Box Border
                        var style = "." + classes + "-top{top:"+topBoxes+"px !important;left:"+leftBoxes+"px !important;width:"+widthBoxes+"px !important;}";
                        style += "." + classes + "-bottom{top:"+bottomBoxes+"px !important;left:"+leftBoxes+"px !important;width:"+widthBoxes+"px !important;}";
                        style += "." + classes + "-left{top:"+topBoxes+"px !important;left:"+leftBoxes+"px !important;height:"+heightBoxes+"px !important;}";
                        style += "." + classes + "-right{top:"+topBoxes+"px !important;left:"+rightBoxes+"px !important;height:"+heightBoxes+"px !important;}";


                        // Max margin right position
                        var marginRightX = parseFloat(marginRight);
                        if(((rightBoxes+2) + parseFloat(marginRight)) > (iframeWidth - scroll_width)){
                            marginRightX = (iframeWidth - scroll_width) - (rightBoxes + 2);
                        }


                        // Margin
                        style += "." + classes + "-margin-top{top:"+topTop+"px !important;left:"+(parseFloat(leftBoxes) - parseFloat(marginLeftOr))+"px !important;width:"+(parseFloat(widthBoxes) + parseFloat(marginLeftOr) + parseFloat(marginRightOr))+"px !important;height:"+parseFloat(marginTop)+"px !important;}";
                        style += "." + classes + "-margin-bottom{top:"+bottomBoxes+"px !important;left:"+(parseFloat(leftBoxes) - parseFloat(marginLeftOr))+"px !important;width:"+(parseFloat(widthBoxes) + parseFloat(marginLeftOr) + parseFloat(marginRightOr))+"px !important;height:"+parseFloat(marginBottom)+"px !important;}";
                        style += "." + classes + "-margin-left{top:"+topBoxes+"px !important;left:"+leftLeft+"px !important;width:"+parseFloat(marginLeft)+"px !important;height:"+heightBoxes+"px !important;}";
                        style += "." + classes + "-margin-right{top:"+topBoxes+"px !important;left:"+(parseFloat(rightBoxes)+2)+"px !important;width:"+parseFloat(marginRightX)+"px !important;height:"+heightBoxes+"px !important;}";


                        // Padding
                        style += "." + classes + "-padding-top{top:"+parseFloat(topBoxes)+"px !important;left:"+parseFloat(leftBoxes)+"px !important;width:"+widthBoxes+"px !important;height:"+parseFloat(paddingTop)+"px !important;}";
                        style += "." + classes + "-padding-bottom{top:"+bottomBottom+"px !important;left:"+parseFloat(leftBoxes)+"px !important;width:"+widthBoxes+"px !important;height:"+parseFloat(paddingBottom)+"px !important;}";
                        style += "." + classes + "-padding-left{top:"+parseFloat(topBoxes)+"px !important;left:"+parseFloat(leftBoxes)+"px !important;width:"+parseFloat(paddingLeft)+"px !important;height:"+parseFloat(heightBoxes)+"px !important;}";
                        style += "." + classes + "-padding-right{top:"+parseFloat(topBoxes)+"px !important;left:"+rightRight+"px !important;width:"+parseFloat(paddingRight)+"px !important;height:"+parseFloat(heightBoxes)+"px !important;}";


                        // Style#wyp-draw-box
                        var drawBoxStyle = iframeBody.find("#wyp-draw-box");

                        // Append
                        if(drawBoxStyle.length > 0){
                            drawBoxStyle.html(style);
                        }else{
                            iframeBody.append("<style id='wyp-draw-box'>"+style+"</style>");
                        }

                        if(is_resizing() == false && is_dragging() == false && is_visual_editing() == false){
                            iframe.find(".wyp-selected-handle").css("left", leftBoxes).css("top", topBoxes);
                        }

                    }

                }

            }


            /* ---------------------------------------------------- */
            /* Process media queries                                */
            /* ---------------------------------------------------- */
            function process_media_query(condition){

                var die = false;

                // Not processable
                var conRex = /\bhandheld\b|\baural\b|\bbraille\b|\bembossed\b|\bprojection\b|\btty\b|\btv\b|\bprint\b|\b3d-glasses\b/;

                if(conRex.test(condition)){
                    return null;
                }

                // not and , not acceptable
                var conRex2 = /,|\bnot\b/;
                if(conRex2.test(condition)){
                    return false;
                }

                // For replace em & and rem
                var fontSizeRotio = parseFloat(iframe.find("html").css("fontSize"));

                // replacing rem & em to PX
                condition = condition.replace(/[0-9. ]+(rem|em)/g, function(match, contents, offset, s){
                        return parseFloat(match)*fontSizeRotio+"px";
                    }
                );

                // Get all queries
                var queries = condition.match(/\((.*?)\)/g);

                var goValue = [];
                var minmaxRex = /max-width|min-width/;

                // loop queries
                $.each(queries, function(index,query){

                    // Just max and min width
                    if(minmaxRex.test(query) == false){
                        die = true;
                        return false;
                    }

                    // Cleaning
                    query = query.replace(/\(|\)|:|px|\s+/g,'');

                    // max min widths
                    query = query.replace(/min-width/g,'>');
                    query = query.replace(/max-width/g,'<');

                    goValue.push(query);

                });

                // Return
                if(die == false){
                    return goValue;
                }

            }


            /* ---------------------------------------------------- */
            /* Cleans selector for regex                            */
            /* ---------------------------------------------------- */
            function selector_regex(selector){
                return selector
                .replace(   /\\/g, "\\\\") // \
                .replace(/\./g, "\\.")  // [
                .replace(/\[/g, "\\[")  // [
                .replace(/\]/g, "\\]")  // ]
                .replace(/\(/g, "\\(")  // (
                .replace(/\)/g, "\\)")  // )
                .replace(/\^/g, "\\^")  // ^
                .replace(/\$/g, "\\$")  // $
                .replace(/\*/g, "\\*")  // *
                .replace(/\:/g, "\\:")  // :
                .replace(/\+/g, "\\+"); // +
            }



            /* ---------------------------------------------------- */
            /* Getting media queries by stylesheet files            */
            /* ---------------------------------------------------- */
            function get_media_queries(css,justCondition){

                var data = window.definedStyleData;

                var dataOther;
                var mediaSelectors;
                var mediaContent;
                var mediaList = [];

                // Adding Editor data for justCondition mode.
                if(justCondition){

                    if(iframe.find("#wyp-css-data-full").length == 0){
                        dataOther = get_clean_css();
                    }else{
                        dataOther = iframe.find("#wyp-css-data-full").html();
                    }

                    // Getting minimized data.
                    data += get_minimized_css(dataOther,false);

                }

                // if no data, stop.
                if(data == ''){
                    return false;
                }

                data = data.toString().replace(/\}\,/g, "}");

                // Getting All media Queries.
                var allMedia = data.match(/@media(.*?){(.*?)}}/g);

                // stop if no media
                if(isUndefined(allMedia)){
                    return false;
                }

                // Clean array
                allMedia = array_cleaner(allMedia);

                // Getting just media condition
                var condition = data.match(/@media(.*?){/g);

                // stop if no media
                if(isUndefined(condition)){
                    return false;
                }

                // Clean array
                condition = array_cleaner(condition);

                // Stop and return just condition.
                if(justCondition){
                    return condition;
                }

                // Each media queries
                $.each(allMedia,function(index,value){

                    // Current media CSS
                    mediaContent = value.match(/\{(.*?)\}}/g).join("").replace(/\}\}$/g,'}').replace(/^\{/g,'');

                    // GEtting all selectors inside media
                    mediaSelectors = array_cleaner(mediaContent.replace(/\{(.*?)\}/g, '|BREAK|').split("|BREAK|"));

                    // Each all selectors
                    $.each(mediaSelectors,function(index,selector){

                        // End after 50
                        if(index > 50){
                            return false;
                        }

                        // Check if there any selector matches the target element
                        if(selector != '' && selector.indexOf("*") == -1  && selector.indexOf(":") == -1 && selector.indexOf("@") == -1 && check_selector(selector,false,false,false) !== false){

                            selector = selector.trim();

                            if(iframe.find(get_foundable_query(selector,true,true,true)).hasClass("wyp-selected")){

                                var ruleAll = mediaContent.match(new RegExp(selector_regex(selector) + "(\s+)?\{(.*?)\}",'gi')).toString();

                                var rules = array_cleaner(ruleAll.match(/\{(.*?)\}/g).toString().replace(/:(.*?)(;|})(\s+)?/g,'|BREAK|').replace(/(\{|\}|\,)/g,'').split("|BREAK|"));

                                // If the current media has any selector
                                // for target element, so process the media query.
                                if(rules.indexOf(css.replace(/(min-|max-)/g,'')) != -1){

                                    mediaList.push( value.match(/\@media(.*?){/g).toString().replace("{",'') );

                                }

                            }

                        }

                    });

                });


                // Each all media list
                var mediaQueries = [];
                var foundedMedia = false;
                var processed,queryX;
                $.each(array_cleaner(mediaList),function(index,query){

                    // Processed
                    processed = process_media_query(query);

                    // Adds mediaQueries
                    mediaQueries.push( processed );

                    // Basic for check with matchMedia
                    queryX = space_cleaner(query.replace("@media",""));

                    // add if it is active
                    if(iframeContentWindow.matchMedia(queryX).matches && processed != false && processed != null){
                        foundedMedia = query;
                    }

                });

                if(foundedMedia != false){
                    return foundedMedia;
                }

                return creating_auto_media_query(mediaQueries);

            }


            /* ---------------------------------------------------- */
            /* Generate automatic media query                       */
            /* ---------------------------------------------------- */
            function creating_auto_media_query(arrMedia){

                var condition = false;
                var closestLow;

                // Current Width
                var currentWidth = $(window).width();

                var upArr = [];
                var downArr = [];

                // ARR Media
                $.each(arrMedia,function(index,value){

                    if(value != null){
                        value = value.toString();
                        if(value.indexOf("<") != -1){
                            downArr.push(value.replace(/\</g,''));
                        }else if(value.indexOf(">") != -1){
                            upArr.push(value.replace(/\>/g,''));
                        }
                    }

                });

                // High to low
                upArr = upArr.sort(function(a, b){return b-a});
                downArr = downArr.sort(function(a, b){return b-a});

                $.each(downArr, function(){
                  if (this <= currentWidth && (closestLow == null || (currentWidth - this) < (currentWidth - closestLow))) {
                    closestLow = this;
                  }
                });

                // if min-width high and max-width low than current width
                if(downArr.length > 0 && upArr.length > 0){
                    if(upArr[0] > currentWidth && downArr[0] < currentWidth){
                        condition = '@media (min-width:'+downArr[0]+'px) and (max-width:'+upArr[0]+'px)';
                    }
                }

                // if min-width and max-width high than current width
                if(downArr.length > 0 && upArr.length > 0){
                    if(upArr[0] > currentWidth && downArr[0] > currentWidth){

                        if(closestLow < currentWidth){
                            condition = '@media (max-width:'+upArr[0]+'px) and (min-width:'+closestLow+'px)';
                        }else{
                            condition = '@media (max-width:'+upArr[0]+'px)';
                        }

                    }
                }

                // if min-width and max-width high than current width
                if(downArr.length == 0 && upArr.length > 0){
                    if(upArr[0] > currentWidth){
                        condition = '@media (max-width:'+upArr[0]+'px)';
                    }
                }

                // if min-width and max-width low than current width
                if(downArr.length > 0 && upArr.length > 0){
                    if(upArr[0] < currentWidth && downArr[0] < currentWidth){
                        condition = '@media (min-width:'+downArr[0]+'px)';
                    }
                }

                // if min-width and max-width low than current width
                if(downArr.length > 0 && upArr.length == 0){
                    if(downArr[0] < currentWidth){
                        condition = '@media (min-width:'+downArr[0]+'px)';
                    }
                }

                // if min-width and max-width low than current width
                if(downArr.length > 1 && upArr.length == 0){
                    if(downArr[0] > currentWidth && closestLow < currentWidth){
                        condition = '@media (max-width:'+downArr[0]+'px) and (min-width:'+closestLow+'px)';
                    }
                }

                return condition;

            }


            /* ---------------------------------------------------- */
            /* Getting the scrollbar Width                          */
            /* ---------------------------------------------------- */
            function get_scroll_bar_width() {

                // no need on responsive mode.
                if (is_responsive_mod()) {
                    return 0;
                }

                // If no scrollbar, return zero.
                if (iframe.height() <= $(window).height() && mainBody.hasClass("wyp-metric-disable")) {
                    return 0;
                }

                var inner = document.createElement('p');
                inner.style.width = "100%";
                inner.style.height = "200px";

                var outer = document.createElement('div');
                outer.style.position = "absolute";
                outer.style.top = "0px";
                outer.style.left = "0px";
                outer.style.visibility = "hidden";
                outer.style.width = "200px";
                outer.style.height = "150px";
                outer.style.overflow = "hidden";
                outer.appendChild(inner);

                document.body.appendChild(outer);
                var w1 = inner.offsetWidth;
                outer.style.overflow = 'scroll';
                var w2 = inner.offsetWidth;
                if (w1 == w2) w2 = outer.clientWidth;

                document.body.removeChild(outer);

                return (w1 - w2);

            }


            /* ---------------------------------------------------- */
            /* Unselect multiple selected items                     */
            /* ---------------------------------------------------- */
            iframe.on("click", '.wyp-selected-others', function() {

                var el = $(this);

                var currentSelector = get_current_selector();

                if(mainBody.hasClass("wyp-control-key-down") && currentSelector.split(",").length > 0){

                    // Remove YP Classes
                    el.removeClass("wyp-selected-others wyp-recent-hover-element");

                    // Get Selector
                    var selector = get_parents(el,'sharp');

                    currentSelector = currentSelector.replace(new RegExp(","+selector_regex(selector),"g"),"");

                    var firstEl = get_selected_element();

                    set_selector(currentSelector, firstEl, true);

                    // return false to block other click function
                    return false;

                }

            });



            /* ---------------------------------------------------- */
            /* Draw other borders.                                  */
            /* ---------------------------------------------------- */
            function draw_other_box(element, classes, $i) {

                var element_p = $(element);

                var elementClasses = element_p.attr("class");

                if (element_p === null) {
                    return false;
                }

                if (element_p[0].nodeName == "HTML" || element_p[0].nodeName == "BODY") {
                    return false;
                }

                if (element_p.length === 0) {
                    return false;
                }

                // Be sure this is visible on screen
                if (element_p.css("display") == 'none' || element_p.css("visibility") == 'hidden' || element_p.css("opacity") == '0') {
                    return false;
                }

                // Not show if p tag and is empty.
                if (element_p.html() == '&nbsp;' && element_p.prop("tagName") == 'P') {
                    return false;
                }

                // Stop.
                if(mainBody.hasClass("wyp-has-transform")){
                    return false;
                }

                // not draw new box and delete last.
                if(isDefined(elementClasses)){

                    elementClasses = elementClasses.replace(/wyp-selected-others/g,'');

                    var pluginelRex = /wyp-selected|wyp-tooltip-small|wyp-edit-/;

                    if(pluginelRex.test(elementClasses) || element_p.hasClass("wyp-selected-others-box")){
                        if(iframe.find("." + classes + "-" + $i + "-box").length > 0){
                            iframe.find("." + classes + "-" + $i + "-box").remove();
                        }

                        return false;

                    }

                }

                // Stop.
                if (check_with_parents(element_p, "transform", "none", "!=") === true) {
                    element_p.addClass("wyp-selected-has-transform");
                    return false;
                }

                // Stop.
                if (check_with_parents(element_p, "display", "none", "==") === true || check_with_parents(element_p, "opacity", "0", "==") === true || check_with_parents(element_p, "visibility", "hidden", "==") === true) {
                    return false;
                }

                //Dynamic boxes variables
                var element_offset = element_p.offset();
                var topBoxes = element_offset.top;
                var leftBoxes = element_offset.left;
                var widthBoxes = element_p.outerWidth(false);
                var heightBoxes = element_p.outerHeight(false);

                if (heightBoxes > 1 && widthBoxes > 1) {

                    // Append Dynamic Box
                    if (iframe.find("." + classes + "-" + $i + "-box").length === 0) {

                        return "<div class='" + classes + "-box " + classes + "-" + $i + "-box' style='top:"+parseFloat(topBoxes)+"px !important;left:"+parseFloat(leftBoxes)+"px !important;width:"+parseFloat(widthBoxes)+"px !important;height:"+parseFloat(heightBoxes)+"px !important;'></div>";

                    }else{

                        // Update boxes
                        iframeBody.find("." + classes + "-" + $i + "-box").css("top",parseFloat(topBoxes)+"px").css("left",parseFloat(leftBoxes)+"px").css("width",parseFloat(widthBoxes)+"px").css("height",parseFloat(heightBoxes)+"px");

                    }

                }

            }


            /* ---------------------------------------------------- */
            /* Visible Height in scroll.                            */
            /* ---------------------------------------------------- */
            function get_visible_height(t) {
                var top = t.offset().top;
                var scrollTop = iframe.scrollTop();
                var height = t.outerHeight();

                if (top < scrollTop) {
                    return height - (scrollTop - top);
                } else {
                    return height;
                }

            }


            /* ---------------------------------------------------- */
            /* Draw Tooltip.                                        */
            /* ---------------------------------------------------- */
            function draw_tooltip(){

                var tooltip = iframe.find(".wyp-selected-tooltip");
                var tooltipMenu = iframe.find(".wyp-edit-tooltip");

                if (tooltip.length <= 0) {
                    return false;
                }

                // remove small tooltip class
                tooltip.removeClass("wyp-small-tooltip");

                // Hide until set position to tooltip if element still not selected.
                if (!is_content_selected()) {
                    tooltip.css("visibility", "hidden");
                    tooltipMenu.css("visibility", "hidden");
                }

                var element = get_selected_element();

                // If not visible stop.
                if (check_with_parents(element, "display", "none", "==") === true || check_with_parents(element, "opacity", "0", "==") === true || check_with_parents(element, "visibility", "hidden", "==") === true) {
                    return false;
                }

                var element_offset = element.offset();

                if (isUndefined(element_offset)) {
                    return false;
                }

                tooltip.removeClass("wyp-tooltip-bottom-outside");

                var topElement = parseFloat(element_offset.top) - 24;

                var leftElement = parseFloat(element_offset.left);

                if(leftElement < 0){
                    leftElement = 0;
                }

                tooltip.css("top", topElement).css("left", leftElement);
                tooltipMenu.css("top", topElement).css("left", leftElement);

                // If outside of bottom, show.
                if (topElement >= ($(window).height() + iframe.scrollTop() - 24)) {

                    if (!tooltip.hasClass("wyp-fixed-tooltip")) {
                        tooltip.addClass("wyp-fixed-tooltip");
                    }

                    // Update
                    topElement = ($(window).height() + iframe.scrollTop() - 24);

                    tooltip.addClass("wyp-fixed-tooltip-bottom");

                } else {

                    if (tooltip.hasClass("wyp-fixed-tooltip")) {
                        tooltip.removeClass("wyp-fixed-tooltip");
                    }

                    tooltip.removeClass("wyp-fixed-tooltip-bottom");

                }

                // If out of top, show.
                var tooltipRatio;
                if (topElement < 2 || topElement < (iframe.scrollTop() + 2)) {

                    var bottomBorder = iframe.find(".wyp-selected-boxed-bottom");

                    topElement = parseFloat(bottomBorder.css("top")) - parseFloat(get_visible_height(element));

                    tooltip.css("top", topElement);
                    tooltipMenu.css("top", topElement);

                    tooltip.addClass("wyp-fixed-tooltip");

                    tooltipRatio = (tooltip.outerHeight() * 100 / get_visible_height(element));

                    if (tooltipRatio > 10) {
                        tooltip.addClass("wyp-tooltip-bottom-outside");
                        topElement = parseFloat(bottomBorder.css("top")) - parseFloat(tooltip.outerHeight()) + tooltip.outerHeight();

                        tooltip.css("top", topElement);
                        tooltipMenu.css("top", topElement);

                    } else {
                        tooltip.removeClass("wyp-tooltip-bottom-outside");
                    }

                } else {
                    tooltip.removeClass("wyp-fixed-tooltip");
                }

                if (tooltipRatio < 11) {
                    tooltip.removeClass("wyp-tooltip-bottom-outside");
                }

                if (tooltip.hasClass("wyp-fixed-tooltip") && tooltip.hasClass("wyp-tooltip-bottom-outside") === false) {
                    tooltipMenu.addClass("wyp-fixed-edit-menu");
                } else {
                    tooltipMenu.removeClass("wyp-fixed-edit-menu");
                }

                if (tooltip.hasClass("wyp-tooltip-bottom-outside")) {
                    tooltipMenu.addClass("wyp-bottom-outside-edit-menu");
                } else {
                    tooltipMenu.removeClass("wyp-bottom-outside-edit-menu");
                }

                if (tooltip.hasClass("wyp-fixed-tooltip-bottom")) {
                    tooltipMenu.addClass("wyp-fixed-bottom-edit-menu");
                } else {
                    tooltipMenu.removeClass("wyp-fixed-bottom-edit-menu");
                }


                tooltip.css({"visibility":"visible","pointer-events":"none"});
                tooltipMenu.css({"visibility":"visible","pointer-events":"none"});

                    // If high
                    if ($("#iframe").width() - (tooltip.width() + tooltip.offset().left + 80) <= 0) {

                        // simple tooltip.
                        tooltip.addClass("wyp-small-tooltip");

                    } else { // If not high

                        // if already simple tooltip
                        if (tooltip.hasClass("wyp-small-tooltip")) {

                            // return to default.
                            tooltip.removeClass("wyp-small-tooltip");

                            // check again if need to be simple
                            if ($("#iframe").width() - (tooltip.width() + tooltip.offset().left + 80) <= 0) {

                                // make it simple.
                                tooltip.addClass("wyp-small-tooltip");

                            }

                        }

                    }

                tooltip.css({"pointer-events":"auto"});
                tooltipMenu.css({"pointer-events":"auto"});

            }


            /* ---------------------------------------------------- */
            /* Trigger mouseup event if mouseup on iframe.          */
            /* ---------------------------------------------------- */
            iframe.on("mouseup", iframe, function() {
                $(document).trigger("mouseup");
            });


            /* ---------------------------------------------------- */
            /* Adding Position:Relative before drag start           */
            /* ---------------------------------------------------- */
            iframe.on("mousedown", "body.wyp-content-selected .wyp-selected", function() {

                // cache
                var element = get_selected_element();
                var pos = element.css("position");

                if(pos == 'static'){
                    pos = 'relative';
                }

                // Get Element Style attr.
                window.styleAttr = element.attr("style");

                // make element re-positionable.
                element.css("position", pos);

                mainBody.addClass("wyp-position-added");

            });


            /* ---------------------------------------------------- */
            /* Removing position:relative after drag end            */
            /* ---------------------------------------------------- */
            $(window).on('mouseup', function(){

                // If this were dragging, let make this work in stop func of draggable.
                if(mainBody.hasClass("wyp-dragging") || mainBody.hasClass("wyp-visual-editing")){
                    return false;
                }

                // If has wyp-position-added
                if(mainBody.hasClass("wyp-position-added") == false){
                    return false;
                }

                // Back To Orginal Style Attr.
                if(window.styleAttr != ''){
                    get_selected_element().attr("style", window.styleAttr);
                }else{
                    get_selected_element().removeAttr("style");
                }

                window.styleAttr = '';

                mainBody.removeClass("wyp-position-added");

            });


            /* ---------------------------------------------------- */
            /* Installing draggable event to the element            */
            /* ---------------------------------------------------- */
            function set_draggable(element) {

                // Add drag support
                if (iframeBody.find(".wyp-selected").length > 0) {

                    var defaultStyleAttr = element.attr("style");

                    element.draggable({

                        containment: "document",
                        delay: 100,
                        start: function(e, ui) {

                            window.elDragWidth = element.outerWidth();
                            window.elDragHeight = element.outerHeight();

                            if (mainBody.hasClass("wyp-css-editor-active")) {
                                $(".css-editor-btn").trigger("click");
                            }

                            if (!is_content_selected()) {
                                return false;
                            }

                            // Close contextmenu
                            if (iframe.find(".context-menu-active").length > 0) {
                                element.contextMenu("hide");
                            }

                            element.removeClass("yp_onscreen yp_hover yp_click yp_focus");

                            // Add some classes
                            body.addClass("wyp-clean-look wyp-dragging wyp-hide-borders-now");

                            // show position tooltip
                            iframeBody.append("<div class='wyp-helper-tooltip'></div>");

                            create_smart_guides();

                            // Delete important tag from old for let to drag elements. Top left right bottom..
                            var corners = ['top','left','right','bottom'],ex;
                            for(var i = 0; i < 4; i++){

                                ex = the_editor_data().find("[data-style='"+get_id(get_current_selector())+"'][data-rule='"+corners[i]+"']");

                                if(ex.length > 0){
                                    ex.html(ex.html().replace(/\s+?!important/g,'').replace(/\;$/g,''));
                                }

                            }


                        },
                        drag: function(event, ui) {

                            if (window.elDragHeight != $(this).outerHeight()) {
                                element.css("width", window.elDragWidth + 1);
                                element.css("height", window.elDragHeight);
                            }

                            // Smart Guides. :-)

                            // tolerance.
                            var t = 6;

                            // Defaults
                            var c,f;

                            // Variables
                            var wLeft,wWidth,wTop,wHeight,otherTop,otherLeft,otherWidth,otherHeight,otherBottom,otherRight;

                            // this
                            var self = $(this);

                            // This offets
                            draw_box(".wyp-selected", 'wyp-selected-boxed');

                            var selfRW = self.outerWidth();
                            var selfTop = Math.round(parseFloat(iframeBody.find(".wyp-selected-boxed-top").css("top")));
                            var selfLeft = Math.round(parseFloat(iframeBody.find(".wyp-selected-boxed-left").css("left")));
                            var selfRight = Math.round(parseFloat(iframeBody.find(".wyp-selected-boxed-right").css("left")));
                            var selfBottom = Math.round(parseFloat(iframeBody.find(".wyp-selected-boxed-bottom").css("top")));

                            // sizes
                            var selfWidth = selfRight - selfLeft;
                            var selfHeight = selfBottom - selfTop;
                            var selfPLeft = parseFloat(self.css("left"));
                            var selfPTop = parseFloat(self.css("top"));

                            // Margin
                            var selfTopMargin = parseFloat(self.css("marginTop"));
                            var selfLeftMargin = parseFloat(self.css("marginLeft"));

                            // Bottom
                            var yBorder = iframeBody.find(".wyp-y-distance-border");
                            var xBorder = iframeBody.find(".wyp-x-distance-border");

                            xBorder.css("display", "none");
                            yBorder.css("display", "none");


                            // Search for:
                            // top in top
                            // bottom in bottom
                            // top in bottom
                            // bottom in top
                            var axsisxEl = iframeBody.find(".wyp-smart-guide-elements[data-wyp-bottom-round='" + wyp_round(selfBottom) + "']");
                            axsisxEl = axsisxEl.add(iframeBody.find(".wyp-smart-guide-elements[data-wyp-top-round='" + wyp_round(selfTop) + "']"));
                            axsisxEl = axsisxEl.add(iframeBody.find(".wyp-smart-guide-elements[data-wyp-top-round='" + wyp_round(selfBottom) + "']"));
                            axsisxEl = axsisxEl.add(iframeBody.find(".wyp-smart-guide-elements[data-wyp-bottom-round='" + wyp_round(selfTop) + "']"));

                            if (axsisxEl.length > 0) {

                                // Getting sizes
                                otherTop = parseFloat(axsisxEl.attr("data-wyp-top"));
                                otherLeft = parseFloat(axsisxEl.attr("data-wyp-left"));
                                otherWidth = parseFloat(axsisxEl.attr("data-wyp-width"));
                                otherHeight = parseFloat(axsisxEl.attr("data-wyp-height"));
                                otherBottom = parseFloat(otherTop + otherHeight);
                                otherRight = parseFloat(otherLeft + otherWidth);

                                // Calculate smart guides positions.
                                if (selfLeft > otherLeft) {
                                    wLeft = otherLeft;
                                    wWidth = selfRight - otherLeft;
                                } else {
                                    wLeft = selfLeft;
                                    wWidth = otherRight - selfLeft;
                                }

                                // TOP = TOP
                                if (axsisxEl.attr("data-wyp-top-round") == wyp_round(selfTop)) {
                                    wTop = otherTop;
                                }

                                // BOTTOM = BOTTOM
                                if (axsisxEl.attr("data-wyp-bottom-round") == wyp_round(selfBottom)) {
                                    wTop = otherBottom;
                                }

                                // BOTTOM = TOP
                                if (axsisxEl.attr("data-wyp-bottom-round") == wyp_round(selfTop)) {
                                    wTop = otherBottom;
                                }

                                // TOP = BOTTOM
                                if (axsisxEl.attr("data-wyp-top-round") == wyp_round(selfBottom)) {
                                    wTop = otherTop;
                                }

                                // controllers
                                c = (ui.offset.top + selfTopMargin) - otherTop;

                                if (c < t && c > -Math.abs(t)) {
                                    f = Math.round((otherTop - selfTop) + selfPTop);
                                    ui.position.top = f;
                                    xBorder.css({'top': wTop,'left': wLeft,'width': wWidth,'display': 'block'});
                                }

                                c = (ui.offset.top + selfTopMargin) - otherBottom + selfHeight;

                                if (c < t && c > -Math.abs(t)) {
                                    f = Math.round((otherBottom - selfBottom) + selfPTop);
                                    ui.position.top = f;
                                    xBorder.css({'top': wTop,'left': wLeft,'width': wWidth,'display': 'block'});
                                }

                                c = (ui.offset.top + selfTopMargin) - otherTop + selfHeight;

                                if (c < t && c > -Math.abs(t)) {
                                    f = Math.round((otherTop - selfBottom) + selfPTop);
                                    ui.position.top = f;
                                    xBorder.css({'top': wTop,'left': wLeft,'width': wWidth,'display': 'block'});
                                }

                                c = (ui.offset.top + selfTopMargin) - otherBottom;

                                if (c < t && c > -Math.abs(t)) {
                                    f = Math.round((otherBottom - selfTop) + selfPTop);
                                    ui.position.top = f;
                                    xBorder.css({'top': wTop,'left': wLeft,'width': wWidth,'display': 'block'});
                                }

                            }


                            // Search for:
                            // left in left
                            // right in right
                            // left in right
                            // right in left
                            var axsisyEl = iframeBody.find(".wyp-smart-guide-elements[data-wyp-right-round='" + wyp_round(selfRight) + "']");

                            axsisyEl = axsisyEl.add(iframeBody.find(".wyp-smart-guide-elements[data-wyp-left-round='" + wyp_round(selfLeft) + "']"));

                            axsisyEl = axsisyEl.add(iframeBody.find(".wyp-smart-guide-elements[data-wyp-left-round='" + wyp_round(selfRight) + "']"));

                            axsisyEl = axsisyEl.add(iframeBody.find(".wyp-smart-guide-elements[data-wyp-right-round='" + wyp_round(selfLeft) + "']"));

                            if (axsisyEl.length > 0) {

                                // Getting sizes
                                otherTop = parseFloat(axsisyEl.attr("data-wyp-top"));
                                otherLeft = parseFloat(axsisyEl.attr("data-wyp-left"));
                                otherWidth = parseFloat(axsisyEl.attr("data-wyp-width"));
                                otherHeight = parseFloat(axsisyEl.attr("data-wyp-height"));
                                otherBottom = parseFloat(otherTop + otherHeight);
                                otherRight = parseFloat(otherLeft + otherWidth);

                                // Calculate smart guides positions.
                                if (selfTop > otherTop) {
                                    wTop = otherTop;
                                    wHeight = selfBottom - otherTop;
                                } else {
                                    wTop = selfTop;
                                    wHeight = otherBottom - selfTop;
                                }

                                // LEFT = LEFT
                                if (axsisyEl.attr("data-wyp-left-round") == wyp_round(selfLeft)) {
                                    wLeft = otherLeft;
                                }

                                // RIGHT = RIGHT
                                if (axsisyEl.attr("data-wyp-right-round") == wyp_round(selfRight)) {
                                    wLeft = otherRight;
                                }

                                // RIGHT = LEFT
                                if (axsisyEl.attr("data-wyp-right-round") == wyp_round(selfLeft)) {
                                    wLeft = otherRight;
                                }

                                // LEFT = RIGHT
                                if (axsisyEl.attr("data-wyp-left-round") == wyp_round(selfRight)) {
                                    wLeft = otherLeft;
                                }

                                // controller
                                c = (ui.offset.left + selfLeftMargin) - otherLeft;

                                // Sharpening
                                if (c < t && c > -Math.abs(t)) {
                                    f = Math.round((otherLeft - selfLeft) + selfPLeft);
                                    ui.position.left = f;
                                    yBorder.css({'top': wTop,'left': wLeft,'height': wHeight,'display': 'block'});
                                }

                                // controller
                                c = (ui.offset.left + selfLeftMargin) - otherRight;

                                // Sharpening
                                if (c < t && c > -Math.abs(t)) {
                                    f = Math.round((otherRight - selfLeft) + selfPLeft);
                                    ui.position.left = f;
                                    yBorder.css({'top': wTop,'left': wLeft,'height': wHeight,'display': 'block'});
                                }

                                // controller
                                c = (ui.offset.left + selfLeftMargin) - otherRight + selfWidth;

                                // Sharpening
                                if (c < t && c > -Math.abs(t)) {
                                    f = Math.round((otherRight - selfRight) + selfPLeft);
                                    ui.position.left = f;
                                    yBorder.css({'top': wTop,'left': wLeft,'height': wHeight,'display': 'block'});
                                }

                                // controller
                                c = Math.round((ui.offset.left + selfLeftMargin) - otherLeft + selfRW);

                                // Sharpening
                                if (c < t && c > -Math.abs(t)) {
                                    f = Math.round((otherLeft - selfRight) + selfPLeft - (selfRW - selfWidth));
                                    ui.position.left = f;
                                    yBorder.css({'top': wTop,'left': wLeft,'height': wHeight,'display': 'block'});
                                }

                            }


                            if (ui.position.top == 1 || ui.position.top == -1 || ui.position.top == 2 || ui.position.top == -2) {
                                ui.position.top = 0;
                            }

                            if (ui.position.left == 1 || ui.position.left == -1 || ui.position.left == 2 || ui.position.left == -2) {
                                ui.position.left = 0;
                            }

                            // Update helper tooltip
                            if (selfTop >= 60) {
                                iframeBody.find(".wyp-helper-tooltip").css({
                                    'top': selfTop,
                                    'left': selfLeft
                                }).html("X : " + parseInt(ui.position.left) + " px<br>Y : " + parseInt(ui.position.top) + " px");
                            } else {
                                iframeBody.find(".wyp-helper-tooltip").css({
                                    'top': selfTop + selfHeight + 40 + 10,
                                    'left': selfLeft
                                }).html("X : " + parseInt(ui.position.left) + " px<br>Y : " + parseInt(ui.position.top) + " px");
                            }

                        },
                        stop: function() {

                            clean_smart_guides();

                            var delay = 1;

                            // CSS To Data.
                            if (mainBody.hasClass("wyp-need-to-process")) {
                                process(false);
                                delay = 70;
                            }

                            // Draw tooltip qiuckly
                            draw_tooltip();

                            // Wait for process.
                            setTimeout(function() {

                                var t = element.css("top");
                                var l = element.css("left");
                                var b = element.css("bottom");
                                var r = element.css("right");

                                // Back To Orginal Style Attr.
                                if(mainBody.hasClass("wyp-position-added")){

                                    if(window.styleAttr != ''){
                                        element.attr("style", window.styleAttr);
                                    }else{
                                        element.removeAttr("style");
                                    }

                                    window.styleAttr = '';
                                    mainBody.removeClass("wyp-position-added");

                                }

                                // Insert new data.
                                insert_rule(null, "top", t, '');
                                insert_rule(null, "left", l, '');

                                if (parseFloat(t) + parseFloat(b) !== 0) {
                                    insert_rule(null, "bottom", "auto", '');
                                }

                                if (parseFloat(l) + parseFloat(r) !== 0) {
                                    insert_rule(null, "right", "auto", '');
                                }

                                // Adding styles
                                if (element.css("position") == 'static') {
                                    insert_rule(null, "position", "relative", '');
                                }

                                // Updating current option list
                                insert_default_options();

                                // Remove
                                iframe.find(".wyp-selected,.wyp-selected-others").removeClass("ui-draggable-handle ui-draggable-handle");

                                // Update css.
                                option_change();

                                body.removeClass("wyp-clean-look wyp-dragging wyp-hide-borders-now");

                                draw();

                                gui_update();

                            }, delay);

                        }

                    });

                    // Go to default style attr
                    if(defaultStyleAttr != ''){
                        element.attr("style", defaultStyleAttr);
                    }else{
                        element.removeAttr("style");
                    }

                }

            }


            /* ---------------------------------------------------- */
            /* Updating drag icon position                          */
            /* ---------------------------------------------------- */
            function update_drag_handle_position() {

                // Element selected?
                if (!is_content_selected()) {
                    return false;
                }

                // element
                var element = get_selected_element();

                var handle = iframe.find(".wyp-selected-handle");

                // Add new
                if (element.height() > 20 && element.width() > 60 && handle.length === 0) {
                    iframeBody.append("<span class='wyp-selected-handle'></span>");
                }

                handle.css("left", iframe.find(".wyp-selected-boxed-right").css("left"));
                handle.css("top", iframe.find(".wyp-selected-boxed-bottom").css("top"));
                handle.css("opacity", iframe.find(".wyp-selected-boxed-bottom").css("opacity"));

            }


            window.mouseisDown = false;
            window.styleAttrBeforeChange = null;
            window.visualResizingType = null;
            window.ResizeSelectedBorder = null;
            window.elementOffsetLeft = null;
            window.elementOffsetRight = null;

            /* ---------------------------------------------------- */
            /* Getting the domain                                   */
            /* ---------------------------------------------------- */
            function get_domain(url) {
                var domain;
                if (url.indexOf("://") > -1) {
                    domain = url.split('/')[2];
                } else {
                    domain = url.split('/')[0];
                }
                domain = domain.split(':')[0];
                return $.trim(domain);
            }


            /* ---------------------------------------------------- */
            /* Getting absolute Path                                */
            /* ---------------------------------------------------- */
            var get_absolute_path = function(href){
                var link = document.createElement("a");
                link.href = href;
                return (link.protocol+"//"+link.host+link.pathname+link.search+link.hash);
            };


            // Surfing on iframe
            iframe.find('a[href]').on("click", iframe, function(evt) {

                $(this).attr("target", "_self");

                if(mainBody.hasClass("wyp-metric-disable") === false){
                    return false;
                }

                // if aim mode disable.
                if ($(".wyp-selector-mode.active").length === 0) {

                    var href = $(this).attr("href");

                    if (href == '' || href == '#' || href.indexOf("yellow-pencil-editor") != -1) {
                        return false;
                    }

                    // Get full URL
                    href = get_absolute_path(href);

                    if (href.indexOf("#noAiming") > -1) {
                        swal({title: "Sorry.",text: "This link is not an wordpress page. You can't edit this page.",animation: false,customClass: "editor-style-ok"});
                        return false;
                    }

                    if (href !== null && href != '' && href.charAt(0) != '#' && href.indexOf("javascript:") == -1 && href.indexOf("yellow_pencil=true") == -1) {

                        var link_host = get_domain(href);
                        var main_host = window.location.hostname;

                        if (link_host != main_host) {
                            swal({title: "Sorry.",text: "This is an external link. You can't edit this page.",animation: false,customClass: "editor-style-ok"});
                            return false;
                        }

                        if (href.indexOf(siteurl.split("://")[1]) == -1 || href.indexOf("wp-login.php?action=logout") != -1) {
                            swal({title: "Sorry.",text: "This link is not an wordpress page. You can't edit this page.",animation: false,customClass: "editor-style-ok"});
                            return false;
                        }

                        // https to http
                        if (location.protocol == 'http:' && href.indexOf('https:') != -1 && href.indexOf('http:') == -1) {
                            href = href.replace("https:", "http:");
                            $(this).attr("href", href);
                        }

                        // Http to https
                        if (location.protocol == 'https:' && href.indexOf('http:') != -1 && href.indexOf('https:') == -1) {
                            href = href.replace("http:", "https:");
                            $(this).attr("href", href);
                        }

                        // if selector mode not active and need to save.
                        if ($(".wyp-save-btn").hasClass("waiting-for-save")){
                            if (confirm(l18_sure) == true) {
                                $(".waiting-for-save").removeClass("waiting-for-save");
                            } else {
                                return false;
                            }
                        }

                    } else {
                        return false;
                    }

                    $("#iframe").remove();
                    body.removeClass("yellow-pencil-ready");
                    $(".loading-files").html("Page loading..");

                    // Get parent url
                    var parentURL = window.location;

                    //delete after href.
                    parentURL = parentURL.toString().split("href=")[0] + "href=";

                    // get iframe url
                    var newURL = href;
                    if (newURL.substring(0, 6) == 'about:') {
                        $(this).show();
                        return false;
                    }

                    $.get(newURL, function(data){

                        mainBody.append("<div id='wyp-load-test-admin'></div>");

                        newURL = newURL.replace(/.*?:\/\//g, ""); // delete protocol
                        newURL = newURL.replace("&yellow_pencil_frame=true", "").replace("?yellow_pencil_frame=true", "");
                        newURL = encodeURIComponent(newURL); // encode url
                        parentURL = parentURL + newURL; // update parent URL

                        window.location = parentURL;

                    });



                }

            });


            /* ---------------------------------------------------- */
            /* Element Select, Cancel Select Funcs                  */
            /* ---------------------------------------------------- */
            iframe.on("click", iframe, function(evt) {

                // Hide customize type live feature
                if($("#customizing-type-list").css("display") == 'block'){
                    $("#customizing-type-list").toggle();
                    return false;
                }

                // Click options
                if ($(".wyp-selector-mode.active").length > 0 && mainBody.hasClass("wyp-metric-disable")) {

                    if (evt.which == 1 || evt.which === undefined) {
                        evt.stopPropagation();
                        evt.preventDefault();
                    }

                    // Not clickable while animate playing
                    if(body.hasClass("wyp-animate-manager-playing")){
                        return false;
                    }

                    // Visual Edited
                    if(body.hasClass("wyp-visual-edited")){
                        body.removeClass("wyp-visual-edited");
                        return false;
                    }

                    // Resized
                    if (body.hasClass("wyp-element-resized") || body.hasClass("resize-time-delay")) {
                        body.removeClass("wyp-element-resized resize-time-delay");
                        return false;
                    }

                    // Colorpicker for all elements.
                    if (mainBody.hasClass("wyp-element-picker-active")) {

                        // CSS
                        iframe.find("#colorPickerLiveCSS").remove();
                        var pickerCSSRule = $(".wyp-element-picker.active").parents(".wyp-option-group").attr("data-css");
                        insert_rule(null, pickerCSSRule, window.pickerColor);
                        set_default_value(pickerCSSRule);

                        // Clean picker
                        $(".wyp-element-picker-active").removeClass("wyp-element-picker-active");
                        $(".wyp-element-picker.active").removeClass("active");
                        return false;

                    }

                    if ($(".wyp_flat_colors_area:visible").length !== 0) {

                        $(".wyp-flat-colors.active").each(function() {
                            $(this).trigger("click");
                        });

                        return false;

                    }

                    if ($(".wyp_meterial_colors_area:visible").length !== 0) {

                        $(".wyp-meterial-colors.active").each(function() {
                            $(this).trigger("click");
                        });

                        return false;

                    }

                    if ($(".wyp_nice_colors_area:visible").length !== 0) {

                        $(".wyp-nice-colors.active").each(function() {
                            $(this).trigger("click");
                        });

                        return false;

                    }

                    if ($(".iris-picker:visible").length !== 0) {

                        $(".iris-picker:visible").each(function() {
                            $(this).hide();
                        });

                        gui_update();

                        return false;

                    }

                    if ($(".wyp_background_assets:visible").length !== 0) {

                        $(".wyp-bg-img-btn.active").each(function() {
                            $(this).trigger("click");
                        });

                        return false;

                    }

                    if ($(".wyp-gradient-section:visible").length !== 0) {

                        $(".wyp-gradient-btn.active").each(function() {
                            $(this).trigger("click");
                        });

                        return false;

                    }

                    if (mainBody.hasClass("autocomplete-active")) {

                        $(".input-autocomplete").each(function() {
                            $(this).autocomplete("close");
                        });

                        return false;

                    }

                    if (is_content_selected() === true) {

                        // CSS To Data.
                        if (mainBody.hasClass("wyp-need-to-process")) {
                            process(false);
                        }

                        if (iframe.find(".context-menu-active").length > 0) {
                            get_selected_element().contextMenu("hide");
                            return false;
                        }

                    }

                    var element = $(evt.target);
                    var element_offset;

                    if (evt.which === undefined || evt.which == 1) {

                        if (is_content_selected() === true) {

                            if (element.hasClass("wyp-edit-menu") && element.hasClass("wyp-content-selected") === false) {
                                element_offset = element.offset();
                                var x = element_offset.left;
                                if (x === 0) {
                                    x = 1;
                                }
                                var y = element_offset.top + 26;
                                get_selected_element().contextMenu({
                                    x: x,
                                    y: y
                                });
                                return false;
                            }

                            if (element.hasClass("wyp-selected-tooltip")) {
                                $(".wyp-button-target").trigger("click");
                                return false;
                            } else if (element.parent().length > 0) {
                                if (element.parent().hasClass("wyp-selected-tooltip")) {
                                    $(".wyp-button-target").trigger("click");
                                    return false;
                                }
                            }

                            // click notting on visual margin/padding helper
                            if(element.is("[class*=wyp-selected-boxed-margin-],[class*=wyp-selected-boxed-padding-]")){
                                clearTimeout(window.visualEditDelay);
                                return false;
                            }

                        }

                    }

                    if (body.hasClass("yp-selector-disabled")) {
                        return false;
                    }

                    if (body.hasClass("wyp-disable-disable-yp")) {
                        return false;
                    }

                    var selector = get_parents(element, "default");

                    if (mainBody.hasClass("autocomplete-active") && selector == 'body') {
                        return false;
                    }

                    if (evt.which == 1 || evt.which === undefined) {

                        if (element.hasClass("wyp-selected") === false) {

                            if (is_content_selected() === true && element.parents(".wyp-selected").length != 1) {

                                if (is_animate_creator() && is_dragging() === false) {

                                    swal({
                                        title: l18_closeAnim,
                                        showCancelButton: true,
                                        animation: false,
                                        customClass: "editor-style",
                                        confirmButtonText: "Yes",
                                    },function(isConfirm){

                                        if(isConfirm){

                                            swal.close();
                                            close_animation_generator(true);

                                        }

                                    });

                                    return false;

                                }

                                // Multiable Selector
                                if(is_content_selected() && mainBody.hasClass("wyp-control-key-down")){

                                    if(element.hasClass("wyp-selected-others-box") === false){

                                        var selectorCurrent = get_current_selector();
                                        var selectorNew = get_parents(element, "sharp");
                                        iframe.find(".wyp-selected-others-multiable-box").remove();
                                        iframe.find(".wyp-multiple-selected").addClass("wyp-selected-others").removeClass("wyp-multiple-selected");
                                        set_selector(selectorCurrent+","+selectorNew, get_selected_element(), true);

                                        // Disable focus style after clicked.
                                        element.blur();

                                    }


                                    return false;

                                }

                                // remove ex
                                clean();

                                // Quick update
                                $(evt.target).trigger("mouseover");

                            }

                        } else {

                            if (is_content_selected() === false){

                                if (check_with_parents(element, "transform", "none", "!=") === true){
                                    body.addClass("wyp-has-transform");
                                }

                                // Set selector as  body attr.
                                body.attr("data-clickable-select", selector);

                                set_draggable(element);

                                // RESIZE ELEMENTS
                                window.visualResizingType = 'width';
                                window.ResizeSelectedBorder = "right";
                                window.styleAttrBeforeChange = element.attr("style");

                                element_offset = element.offset();
                                window.elementOffsetLeft = element_offset.left;
                                window.elementOffsetRight = element_offset.right;

                                element.width(parseFloat(element.width() + 10));

                                if (window.elementOffsetLeft == element_offset.left && window.elementOffsetRight != element_offset.right) {
                                    window.ResizeSelectedBorder = "right";
                                } else if (window.elementOffsetLeft != element_offset.left && window.elementOffsetRight == element_offset.right && element.css("text-align") != 'center') {
                                    window.ResizeSelectedBorder = "left";
                                } else {
                                    window.ResizeSelectedBorder = "right";
                                }

                                if (isDefined(window.styleAttrBeforeChange)) {
                                    element.attr("style", window.styleAttrBeforeChange);
                                } else {
                                    element.removeAttr("style");
                                    window.styleAttrBeforeChange = null;
                                }

                                // element selected
                                body.addClass("wyp-content-selected");

                                window.orginalHeight = parseFloat(element.css("height").replace(/px/g,''));
                                window.orginalWidth = parseFloat(element.css("width").replace(/px/g,''));

                                if(element.css("float") == 'right'){
                                    body.addClass("wyp-element-float");
                                }else{
                                    body.removeClass("wyp-element-float");
                                }

                                var ttag = element.prop("tagName").toLowerCase();
                                if(ttag == 'ul' || ttag == 'ol' || ttag == 'li'){
                                    body.addClass("wyp-element-list");
                                }else{
                                    body.removeClass("wyp-element-list");
                                }

                                css_editor_toggle(true); // show if hide

                                // Disable focus style after clicked.
                                element.blur();

                                if(is_animation_manager()){
                                    animation_manager();
                                }

                                // Update the element informations.
                                if($(".advanced-info-box").css("display") == 'block'){
                                    update_design_information("all");
                                }

                            }

                        }

                    } else {

                        var hrefAttr = $(evt.target).attr("href");

                        // If has href
                        if (isDefined(hrefAttr)) {

                            if (evt.which == 1 || evt.which === undefined) {
                                evt.stopPropagation();
                                evt.preventDefault();
                            }

                            return false;

                        }

                    }

                    draw();
                    gui_update();

                }

            });


            /* ---------------------------------------------------- */
            /* Creating smart guides while resize & dragging        */
            /* ---------------------------------------------------- */
            function create_smart_guides(){

                if(body.hasClass("wyp-smart-guide-disabled") || mainBody.hasClass("wyp-has-transform")){
                    return false;
                }

                var maxWidth = 0;
                var maxWidthEl = null;
                var k = $(window).width();

                // Smart guides: START
                var Allelements = iframeBody.find(get_all_elements(":not(li)"));

                for (var i=0; i < Allelements.length; i++){

                    // Element
                    var el = $(Allelements[i]);
                    var otherWidth = el.outerWidth();


                    // 720 768 940 960 980 1030 1040 1170 1210 1268
                    if(otherWidth >= 720 && otherWidth <= 1268 && otherWidth < (k-80)){

                        if(otherWidth > maxWidth){
                            maxWidthEl = el;
                        }

                        maxWidth = Math.max(otherWidth, maxWidth);

                    }


                    if(el.parents(".wyp-selected").length <= 0 && el.parents(".wyp-selected-others").length <= 0 && el.css("display") != 'none' && el.css("opacity") != "0" && el.css("visibility") != 'hidden' && el.height() >= 10){

                        var offset = el.offset();

                        // Getting sizes
                        var otherTop = Math.round(offset.top);
                        var otherLeft = Math.round(offset.left);
                        var otherHeight = Math.round(el.outerHeight());

                            // don't add "inner" same size elements.
                            if(iframeBody.find('[data-wyp-top="'+otherTop+'"][data-wyp-left="'+otherLeft+'"][data-wyp-width="'+otherWidth+'"][data-wyp-height="'+otherHeight+'"]').length <= 0){

                                // Saving for use on drag event.
                                // faster performance.
                                el.addClass("wyp-smart-guide-elements")
                                .attr("data-wyp-top",otherTop)
                                .attr("data-wyp-left",otherLeft)
                                .attr("data-wyp-top-round",wyp_round(otherTop))
                                .attr("data-wyp-bottom-round",wyp_round(otherTop+otherHeight))
                                .attr("data-wyp-left-round",wyp_round(otherLeft))
                                .attr("data-wyp-right-round",wyp_round(otherLeft+otherWidth))
                                .attr("data-wyp-width",otherWidth)
                                .attr("data-wyp-height",otherHeight);
                            }

                        }

                }

                // Not adding on responsive mode.
                if(maxWidthEl !== null){

                    var Pleft = maxWidthEl.offset().left;

                    if(Pleft > 50){

                        var Pright = Pleft+maxWidth;

                        if(parseInt(Pleft) == parseInt(iframe.width()-Pright)){

                            iframeBody.append("<div class='wyp-page-border-left' style='left:"+Pleft+"px;'></div><div class='wyp-page-border-right' style='left:"+Pright+"px;'></div>");

                        }

                    }

                }

                // Adding distance borders
                iframeBody.append("<div class='wyp-x-distance-border'></div><div class='wyp-y-distance-border'></div>");

            }


            /* ---------------------------------------------------- */
            /* Clean up smart guides                                */
            /* ---------------------------------------------------- */
            function clean_smart_guides(){

                iframeBody.find(".wyp-page-border-left,.wyp-page-border-right").remove();

                // Removing distance borders
                iframeBody.find(".wyp-x-distance-border,.wyp-y-distance-border,.wyp-helper-tooltip").remove();

                iframeBody.find(".wyp-smart-guide-elements").removeClass("wyp-smart-guide-elements")
                    .removeAttr("data-wyp-top")
                    .removeAttr("data-wyp-left")
                    .removeAttr("data-wyp-width")
                    .removeAttr("data-wyp-top-round")
                    .removeAttr("data-wyp-bottom-round")
                    .removeAttr("data-wyp-left-round")
                    .removeAttr("data-wyp-right-round")
                    .removeAttr("data-wyp-height");

            }


            /* ---------------------------------------------------- */
            /* Resize Start : Width                                 */
            /* ---------------------------------------------------- */
            iframe.on("mousedown", '.wyp-selected-boxed-left,.wyp-selected-boxed-right', function(event) {

            var element = $(this);

            // if float not right, left disable
            if(body.hasClass("wyp-element-float") == false && element.hasClass("wyp-selected-boxed-left")){
                return false;
            }

            body.addClass("resize-time-delay");

            var valueData = '';
            var dynamicValue = get_applied_style_data(get_current_selector(), "width", [false, false, false]);
            if(typeof dynamicValue === 'object'){
                dynamicValue.always(function(data){
                    valueData = data;
                });
            }

            clearTimeout(window.resizeDelay);
            window.resizeDelay = setTimeout(function(){

                if (is_content_selected() === false) {
                    return false;
                }

                window.visualResizingType = 'width';

                if (element.hasClass("wyp-selected-boxed-left")) {
                    window.ResizeSelectedBorder = "left";
                } else {
                    window.ResizeSelectedBorder = "right";
                }

                window.mouseisDown = true;

                var el = iframeBody.find(".wyp-selected");

                window.mouseDownX = el.offset().left;
                window.exWidthX = parseFloat(el.css("width"));
                window.exWidthY = null;
                window.currentMarginLeft = parseFloat(el.css("marginLeft"));

                window.maxData = {width: parseFloat(el.css("maxWidth")), height: parseFloat(el.css("maxHeight"))};
                window.minData = {width: parseFloat(el.css("minWidth")), height: parseFloat(el.css("minHeight"))};


                // Try to use % Percent format
                var widthPercent = calcature_smart_sizes(get_selected_element(),get_selected_element().css('width'));
                window.liveResizeWPercent = false;
                if(widthPercent.format == '%'){
                    window.liveResizeWPercent = true;
                }

                // Get saved sata from CSS editor.
                if(widthPercent.format != '%' && get_applied_style_data(get_current_selector(), "width", [false, true, false])){

                    var data = valueData;

                    if(data.indexOf("%") != -1){
                        window.liveResizeWPercent = true;
                    }

                }

                iframe.find("html").addClass("wyp-element-resizing");
                body.addClass("wyp-element-resizing wyp-clean-look");

                // Close contextmenu
                if (iframe.find(".context-menu-active").length > 0) {
                    get_selected_element().contextMenu("hide");
                }

                // show size tooltip
                iframeBody.append("<div class='wyp-helper-tooltip'></div>");

                create_smart_guides();

            },150);

            });


            /* ---------------------------------------------------- */
            /* Resize Start : Height                                */
            /* ---------------------------------------------------- */
            iframe.on("mousedown", '.wyp-selected-boxed-bottom', function(event) { // removed since 5.5.6 .wyp-selected-boxed-top

            var element = $(this);

            body.addClass("resize-time-delay");

            clearTimeout(window.resizeDelay);
            window.resizeDelay = setTimeout(function(){

                if (is_content_selected() === false) {
                    return false;
                }

                // Update variables
                window.mouseisDown = true;

                window.visualResizingType = 'height';

                if (element.hasClass("wyp-selected-boxed-top")) {
                    window.ResizeSelectedBorder = "top";
                } else {
                    window.ResizeSelectedBorder = "bottom";
                }

                var el = iframeBody.find(".wyp-selected");

                window.mouseDownY = el.offset().top;
                window.exWidthY = parseFloat(el.css("height"));
                window.exWidthX = null;
                window.currentMarginTop = parseFloat(el.css("marginTop"));

                window.maxData = {width: parseFloat(el.css("maxWidth")), height: parseFloat(el.css("maxHeight"))};
                window.minData = {width: parseFloat(el.css("minWidth")), height: parseFloat(el.css("minHeight"))};

                body.addClass("wyp-element-resizing wyp-clean-look");

                // Close contextmenu
                if (iframe.find(".context-menu-active").length > 0) {
                    get_selected_element().contextMenu("hide");
                }

                // Removing classes.
                iframe.find(get_current_selector()).removeClass("wyp_selected yp_onscreen yp_hover yp_focus yp_click");

                // show size tooltip
                iframeBody.append("<div class='wyp-helper-tooltip'></div>");

                create_smart_guides();

            },150);

            });


            /* ---------------------------------------------------- */
            /* Resize: Resizing                                     */
            /* ---------------------------------------------------- */
            iframe.on("mousemove", iframe, function(event) {

                // Record mousemoves after element selected.
                window.lastTarget = event.target;

                if (window.mouseisDown === true) {

                    var yBorder = iframeBody.find(".wyp-y-distance-border");
                    var xBorder = iframeBody.find(".wyp-x-distance-border");

                    event = event || window.event;

                    // cache
                    var element = get_selected_element();

                    var elof = element.offset();

                    // Convert display inline to inline-block.
                    if (element.css("display") == 'inline') {
                        insert_rule(null, "display", "inline-block", "");
                    }

                    var format = 'px';

                    if(window.liveResizeWPercent == true){
                        format = '%';
                    }

                    var width,smartData,height,dif;

                    // If width
                    if (window.visualResizingType == "width") {

                        if (window.ResizeSelectedBorder == 'left'){
                            width = Math.round(elof.left) + Math.round(element.outerWidth()) - Math.round(event.pageX);
                        } else {
                            width = Math.round(event.pageX) - Math.round(elof.left);
                        }


                        // Min 4px
                        if ((format == 'px' && width > 4) || (format == '%' && width > 2)) {

                            if (element.css("boxSizing") == 'content-box') {
                                width = width - Math.round(parseFloat(element.css("paddingLeft"))) - Math.round(parseFloat(element.css("paddingRight")));
                            }

                            // calcature smart sizes. 100% etc
                            smartData = calcature_smart_sizes(element,width);

                            // Update
                            width = smartData.val;
                            format = smartData.format;

                            if(window.wasLockX === false){
                                if (window.ResizeSelectedBorder == 'left'){
                                    dif = Math.round(event.pageX)-Math.round(window.mouseDownX)+window.currentMarginLeft;
                                    element.cssImportant("margin-left", dif + "px");
                                }

                                element.cssImportant("width", width + format);

                            }

                            draw_box(".wyp-selected", 'wyp-selected-boxed');

                        }

                        body.addClass("wyp-element-resizing-width-" + window.ResizeSelectedBorder);

                    } else if (window.visualResizingType == "height") { // else height

                        if (window.ResizeSelectedBorder == 'top') {
                            height = Math.round(elof.top+element.outerHeight()) - Math.round(event.pageY);
                        } else {
                            height = Math.round(event.pageY) - Math.round(elof.top);
                        }

                        // Min 4px
                        if (format == 'px' && height > 4){

                            if (element.css("boxSizing") == 'content-box') {
                                height = height - Math.round(parseFloat(element.css("paddingTop"))) - Math.round(parseFloat(element.css("paddingBottom")));
                            }

                            if(window.wasLockY === false){
                                if (window.ResizeSelectedBorder == 'top'){
                                    dif = Math.round(event.pageY)-Math.round(window.mouseDownY)+window.currentMarginTop;
                                    element.cssImportant("margin-top", dif + "px");
                                }
                                element.cssImportant("height", height + format);
                            }

                            draw_box(".wyp-selected", 'wyp-selected-boxed');

                        }

                        body.addClass("wyp-element-resizing-height-" + window.ResizeSelectedBorder);

                    }

                    var tooltipContent = '';
                    var roundedNum = 0;

                    // Update helper tooltip
                    if(window.visualResizingType == "width"){
                        if(width < 5 && format == 'px'){width = 5;}else if(width < 2){width = 2;}
                        if(format == '%'){roundedNum = Math.round(width * 10) / 10;}else{roundedNum = Math.round(width);}
                        tooltipContent = 'W : '+roundedNum + format;
                    }else{
                        if(height < 5){height = 5;}
                        roundedNum = Math.round(height);
                        tooltipContent = 'H : '+roundedNum + format;
                    }


                    // Show : initial at tooltip when resize at default value
                    if(window.visualResizingType == "height"){
                        if(parseInt(window.orginalHeight) == parseInt(height)){
                            tooltipContent = 'H : ' + "initial - " + window.orginalHeight + "px";
                        }
                    }else{
                        if(parseInt(window.orginalWidth) == parseInt(width)){
                            tooltipContent = 'W : ' + "initial - " + window.orginalWidth + "px";
                        }
                    }


                    // offsets
                    var selfTop = Math.round(parseFloat(iframeBody.find(".wyp-selected-boxed-top").css("top")));
                    var selfLeft = Math.round(parseFloat(iframeBody.find(".wyp-selected-boxed-left").css("left")));
                    var selfRight = Math.round(parseFloat(iframeBody.find(".wyp-selected-boxed-right").css("left")));
                    var selfBottom = Math.round(parseFloat(iframeBody.find(".wyp-selected-boxed-bottom").css("top")));

                    // Variables
                    var wLeft,wWidth,wTop,forceH,wHeight,forceW,otherTop,otherLeft,otherWidth,otherHeight,otherBottom,otherRight;

                    // Create smart guides for height.
                    if(window.visualResizingType == "height"){

                        xBorder.css("display","none");
                        window.wasLockY = false;

                        var axsisxEl = iframeBody.find(".wyp-smart-guide-elements[data-wyp-top-round='"+wyp_round(event.pageY)+"'],.wyp-smart-guide-elements[data-wyp-bottom-round='"+wyp_round(event.pageY)+"']").first();

                        if(axsisxEl.length > 0){

                            // Getting sizes
                            otherTop = parseFloat(axsisxEl.attr("data-wyp-top"));
                            otherLeft = parseFloat(axsisxEl.attr("data-wyp-left"));
                            otherWidth = parseFloat(axsisxEl.attr("data-wyp-width"));
                            otherHeight = parseFloat(axsisxEl.attr("data-wyp-height"));
                            otherBottom = parseFloat(otherTop+otherHeight);
                            otherRight = parseFloat(otherLeft+otherWidth);

                            // Calculate smart guides positions.
                            if(selfLeft > otherLeft){
                                wLeft = otherLeft;
                                wWidth = selfRight-wLeft;
                            }else{
                                wLeft = selfLeft;
                                wWidth = otherRight-selfLeft;
                            }

                            // Find top or bottom.
                            if(axsisxEl.attr("data-wyp-top-round") == wyp_round(event.pageY)){
                                wTop = otherTop;
                                forceH = otherTop-selfTop;
                            }else{
                                wTop = otherBottom;
                                forceH = otherBottom-selfTop;
                            }

                            if(window.ResizeSelectedBorder != 'top'){
                                element.cssImportant("height", forceH + format);
                                window.wasLockY = true;
                            }else{
                                forceH = height;
                            }

                            xBorder.css({'top':wTop,'left':wLeft,'width':wWidth,'display':'block'});

                            if(forceH < 5){forceH = 5;}
                            roundedNum = Math.round(forceH);

                            tooltipContent = 'H : '+roundedNum + format;

                        }

                        // Show : initial at tooltip when resize at default value
                        if(parseInt(window.orginalHeight) == parseInt(forceH)){
                            tooltipContent = 'H : ' + "initial - " + window.orginalHeight + "px";
                        }

                    }

                    // Create smart guides for width.
                    if(window.visualResizingType == "width"){

                        window.wasLockX = false;
                        yBorder.css("display","none");

                        var axsisyEl = iframeBody.find(".wyp-smart-guide-elements[data-wyp-left-round='"+wyp_round(event.pageX)+"'],.wyp-smart-guide-elements[data-wyp-right-round='"+wyp_round(event.pageX)+"']").first();

                        if(axsisyEl.length > 0){

                            // Getting sizes
                            otherTop = parseFloat(axsisyEl.attr("data-wyp-top"));
                            otherLeft = parseFloat(axsisyEl.attr("data-wyp-left"));
                            otherWidth = parseFloat(axsisyEl.attr("data-wyp-width"));
                            otherHeight = parseFloat(axsisyEl.attr("data-wyp-height"));
                            otherBottom = parseFloat(otherTop+otherHeight);
                            otherRight = parseFloat(otherLeft+otherWidth);

                            // Calculate smart guides positions.
                            if(selfTop > otherTop){
                                wTop = otherTop;
                                wHeight = selfBottom-otherTop;
                            }else{
                                wTop = selfTop;
                                wHeight = otherBottom-selfTop;
                            }

                            // Find top or bottom.
                            if(axsisyEl.attr("data-wyp-left-round") == wyp_round(event.pageX)){
                                wLeft = otherLeft;
                                forceW = otherLeft-selfLeft;
                            }else{
                                wLeft = otherRight;
                                forceW = otherRight-selfLeft;
                            }


                            // calcature smart sizes. 100% etc
                            smartData = calcature_smart_sizes(element,forceW);

                            // Update
                            forceW = smartData.val;
                            format = smartData.format;


                            if(window.ResizeSelectedBorder != 'left'){
                                element.cssImportant("width", forceW + format);
                                window.wasLockX = true;
                            }else{
                                forceW = width;
                            }

                            yBorder.css({'top':wTop,'left':wLeft,'height':wHeight,'display':'block'});

                            if(format == '%'){
                                if(forceW < 2){forceW = 2;}
                                roundedNum = Math.round(forceW * 10) / 10;
                            }else{
                                if(forceW < 5){forceW = 5;}
                                roundedNum = Math.round(forceW);
                            }
                            tooltipContent = 'W : '+roundedNum + format;

                        }

                        // Show : initial at tooltip when resize at default value
                        if(parseInt(window.orginalWidth) == parseInt(forceW)){
                            tooltipContent = 'W : ' + "initial - " + window.orginalWidth + "px";
                        }

                    }

                    var leftX = event.pageX + 30;
                    if(leftX + 120 >= $("#iframe").width()){
                        leftX = event.pageX - 120;
                    }

                    // Update helper tooltip
                    iframeBody.find(".wyp-helper-tooltip").css({'top':event.pageY,'left':leftX}).html(tooltipContent);


                }

            });


            /* ---------------------------------------------------- */
            /* Calcature Smart Sizes 100%, 100vh etc                */
            /* ---------------------------------------------------- */
            function calcature_smart_sizes(element,val){

                // Variable
                var result = [];

                var founded = false;

                // Check parent details.
                if(element.parent().length > 0){

                    // IF not any inline or table display
                    if(element.parent().css("display").indexOf("table") == -1 && element.parent().css("display") != 'inline' && element.parent().css("display") != 'inline-flex'){

                        var parentWidth = element.parent().width();

                        // if start width percent, use automatic percent all time while resizing.
                        if(window.liveResizeWPercent == true){

                            // Flag
                            founded = true;

                            // Update
                            result.val = Math.round((parseFloat(val)*100/parseFloat(parentWidth)) * 10 ) / 10;
                            result.format = '%';


                        }

                        // if width is same with parent width, so set 100%!
                        if (parseInt(parentWidth) == parseInt(val) && founded == false) {

                            // Flag
                            founded = true;

                            // Update
                            result.val = 100;
                            result.format = '%';

                        }

                        // if width is 50% with parent width, so set 50%!
                        if (parseInt(parentWidth/2) == parseInt(val) && founded == false) {

                            // Flag
                            founded = true;

                            // Update
                            result.val = 50;
                            result.format = '%';

                        }

                        // if width is 25% with parent width, so set 25%!
                        if (parseInt(parentWidth/4) == parseInt(val) && founded == false) {

                            // Flag
                            founded = true;

                            // Update
                            result.val = 25;
                            result.format = '%';

                        }

                        // if width is 20% with parent width, so set 20%!
                        if (parseInt(parentWidth/5) == parseInt(val) && founded == false) {

                            // Flag
                            founded = true;

                            // Update
                            result.val = 20;
                            result.format = '%';

                        }

                    }

                }

                // Return default
                if(founded === false){
                    result.val = val;
                    result.format = 'px';
                }

                return result;

            }


            /* ---------------------------------------------------- */
            /* Resize: End                                          */
            /* ---------------------------------------------------- */
            iframe.on("mouseup", iframe, function() {

                clearTimeout(window.resizeDelay);

                if (is_resizing()) {

                    clean_smart_guides();

                    // show size tooltip
                    iframeBody.find(".wyp-helper-tooltip").remove();

                    body.addClass("wyp-element-resized");

                    var delay = 1;

                    // CSS To Data.
                    if (mainBody.hasClass("wyp-need-to-process")) {
                        process(false);
                        delay = 70;
                    }

                    // Wait for process.
                    setTimeout(function() {

                        var bWidth;
                        if(window.visualResizingType == 'width'){
                            bWidth = window.exWidthX;
                        }else{
                            bWidth = window.exWidthY;
                        }

                        // cache
                        var element = get_selected_element();

                        // get result
                        var width = parseFloat(element.css(window.visualResizingType)).toString();
                        var format = 'px';
                        var widthCa = width;

                        // width 100% for screen
                        if (window.visualResizingType == 'width') {

                            // calcature smart sizes. 100% etc
                            var smartData = calcature_smart_sizes(element,width);

                            // Update
                            width = smartData.val;
                            format = smartData.format;

                        }

                        if(window.exWidthX !== null && window.ResizeSelectedBorder == 'left' && widthCa != bWidth){
                            insert_rule(null,"margin-left",parseFloat(element.css("marginLeft")),'px');
                        }

                        if(window.exWidthY !== null && window.ResizeSelectedBorder == 'top' && widthCa != bWidth){
                            insert_rule(null,"margin-top",parseFloat(element.css("marginTop")),'px');
                        }

                        //return to default
                        if (isDefined(window.styleAttrBeforeChange)) {
                            element.attr("style", window.styleAttrBeforeChange);
                        } else {
                            element.removeAttr("style");
                        }

                        // Still show changes with live css after remove Style attr
                        insert_live_css("min-height", width + "" + format);

                        // insert to data.
                        if(widthCa != bWidth){

                            // Set just min height if new value higher than old
                            if(window.visualResizingType == 'height' && widthCa > window.orginalHeight){

                                // delete inserted height and insert min height.
                                var exStyle = the_editor_data().find("." + get_id(get_current_selector()) + '-' + "height" + '-style[data-size-mode="' + get_media_condition() + '"]');
                                if (exStyle.length > 0) {exStyle.remove();}

                                // Wait for remove style attr
                                setTimeout(function(){
                                    insert_rule(null, "min-height", width, format);
                                }, 5);

                            }else{

                                insert_rule(null, window.visualResizingType, width, format);

                            }

                        }

                        iframe.find("html").removeClass("wyp-element-resizing");
                        body.removeClass("wyp-element-resizing wyp-clean-look wyp-element-resizing-height-bottom wyp-element-resizing-width-left wyp-element-resizing-width-right wyp-element-resizing-height-top");


                        // If width/height large than max width/height
                        if(window.maxData[window.visualResizingType] < width){
                            insert_rule(null, "max-"+window.visualResizingType, width, format);
                        }

                        // If width large than max width/height
                        if(window.minData[window.visualResizingType] > width){
                            insert_rule(null, "min-"+window.visualResizingType, width, format);
                        }

                        draw();

                        // Update
                        option_change();

                        // Set default values for top and left options.
                        $.each(['width','height','max-width','max-height','min-width','min-height','margin-left','margin-top'], function(i, v) {
                            set_default_value(v);
                        });

                        window.mouseisDown = false;
                        window.liveResizeWPercent = false;

                        draw();

                    }, delay);

                    setTimeout(function() {
                        body.removeClass("wyp-element-resized resize-time-delay");
                    }, 100);

                }

            });


            /* ---------------------------------------------------- */
            /* Rounding numbers                                     */
            /* ---------------------------------------------------- */
            function wyp_round(x){
                return Math.round(x / 6) * 6;
            }


            window.visualEdit = false;
            window.visualEditDelay = null;

            /* ---------------------------------------------------- */
            /* Visual Editing : Start                               */
            /* ---------------------------------------------------- */
            iframe.on("mousedown", '.wyp-selected-boxed-margin-left,.wyp-selected-boxed-margin-right,.wyp-selected-boxed-margin-top,.wyp-selected-boxed-margin-bottom,.wyp-selected-boxed-padding-left,.wyp-selected-boxed-padding-right,.wyp-selected-boxed-padding-top,.wyp-selected-boxed-padding-bottom', function(event) {

            if(event.which == 2 || event.which == 3){
                return false;
            }

            // margin/padding viewer element
            var element = $(this);

            clearTimeout(window.visualEditDelay);

            window.visualEditDelay = setTimeout(function(){

                if (is_content_selected() === false) {
                    return false;
                }

                // margin/padding viewer element class
                var classes = element.attr("class").replace(/wyp-recent-hover-element/g,'').trim();

                element.addClass("wyp-visual-active").removeClass("wyp-zero-margin-w wyp-zero-margin-h");

                // Margin || Padding
                window.visualEditType = classes.match(/boxed-[a-z]+/g).toString().replace("boxed-","");

                // Top, left, right, bottom
                window.visualEditPosition = classes.match(/boxed-(margin|padding)-[a-z]+/g).toString().replace(/boxed-|margin|padding|-/g,"");

                // continue on mousemove event
                window.visualEdit = true;

                // Cache mouse position on mousedown
                window.visualEditX = Math.round(event.pageX);
                window.visualEditY = Math.round(event.pageY);

                // ex margin-top
                var rule = window.visualEditType + "-" + window.visualEditPosition;

                // CSS property
                window.visualEditValue = get_selected_element().css(rule);

                // Cache original data
                window.visualEditValueOr = window.visualEditValue;

                // Default 5
                if(isUndefined(window.visualEditValue)){
                    window.visualEditValue = 5;

                // Int
                }else{
                    window.visualEditValue = parseInt(window.visualEditValue);
                }

                // Add class
                body.addClass("wyp-visual-editing wyp-clean-look");

                // X and Y
                if(/(left|right)/g.test(window.visualEditPosition)){
                    body.addClass("wyp-visual-editing-x");
                }else{
                    body.addClass("wyp-visual-editing-y");
                }

                // Use outline for performance
                body.addClass("wyp-has-transform");

                window.currentLiveSelector = get_live_selector();

            },150);

            });



            /* ---------------------------------------------------- */
            /* Visual Editing : Editing                             */
            /* ---------------------------------------------------- */
            iframe.on("mousemove", iframe, function(event) {

                if(window.visualEdit){

                    var dif,rule,style,format;

                    // Dif
                    if(/(left|right)/g.test(window.visualEditPosition)){

                        if(window.visualEditType == 'padding'){

                            // Negative
                            if(/left/g.test(window.visualEditPosition)){
                                dif = Math.round(event.pageX) - window.visualEditX;
                            }else{
                                dif = window.visualEditX - Math.round(event.pageX);
                            }

                        }else{

                            dif = Math.round(event.pageX) - window.visualEditX;

                        }

                        format = 'width';

                    }else{

                        dif = Math.round(event.pageY) - window.visualEditY;

                        format = 'height';

                    }

                    // All in
                    dif = dif + window.visualEditValue;

                    // min 0
                    if(dif < 0){
                        dif = 0;
                    }

                    // CSS Rule
                    rule = window.visualEditType + "-" + window.visualEditPosition;

                    style = '';


                    // variables
                    var selectedElement = get_selected_element();
                    var elementOffset = selectedElement.offset();
                    var elementLeft = elementOffset.left;
                    var elementTop = elementOffset.top;
                    var elementWidth = selectedElement.outerWidth(false);
                    var elementHeight = selectedElement.outerHeight(false);

                    var elementRight = parseFloat(elementLeft + elementWidth);
                    var elementBottom = parseFloat(elementTop+elementHeight);

                    // Update Margin Right
                    if(window.visualEditType + "-" + window.visualEditPosition == 'margin-right'){
                        style += ".wyp-selected-boxed-margin-right{ top: "+elementTop+"px !important; height:"+elementHeight+"px !important;left:"+elementRight+"px !important; }";
                    }

                    // Update Padding Right
                    if(window.visualEditType + "-" + window.visualEditPosition == 'padding-right'){
                        style += ".wyp-selected-boxed-padding-right{ top: "+elementTop+"px !important; height:"+elementHeight+"px !important;left:"+(elementRight-dif)+"px !important; }";
                    }

                    // Update Margin Left
                    if(window.visualEditType + "-" + window.visualEditPosition == 'margin-left'){
                        style += ".wyp-selected-boxed-margin-left{ top: "+elementTop+"px !important; height:"+elementHeight+"px !important;left:"+(elementLeft-dif)+"px !important; }";
                    }

                    // Update Padding Left
                    if(window.visualEditType + "-" + window.visualEditPosition == 'padding-left'){
                        style += ".wyp-selected-boxed-padding-left{ top: "+elementTop+"px !important; height:"+elementHeight+"px !important;left:"+elementLeft+"px !important; }";
                    }

                    // Need marginLeft and MarginRight value for margin top and bottom
                    if(window.visualEditType + "-" + window.visualEditPosition == 'margin-top' || window.visualEditType + "-" + window.visualEditPosition == 'margin-bottom'){
                        var marginLeft = parseFloat(selectedElement.css("margin-left"));
                        var marginRight = parseFloat(selectedElement.css("margin-right"));
                        var outlineWidth = parseFloat(elementWidth) + marginLeft + marginRight;
                        var marginOutLeft = parseFloat(elementLeft) - marginLeft;
                    }

                    // Update Margin Top
                    if(window.visualEditType + "-" + window.visualEditPosition == 'margin-top'){
                        style += ".wyp-selected-boxed-margin-top{ top: "+(elementTop-dif)+"px !important; width:"+outlineWidth+"px !important;left:"+marginOutLeft+"px !important; }";
                    }

                    // Update Padding Top
                    if(window.visualEditType + "-" + window.visualEditPosition == 'padding-top'){
                        style += ".wyp-selected-boxed-padding-top{ top: "+elementTop+"px !important; width:"+elementWidth+"px !important;left:"+elementLeft+"px !important; }";
                    }

                    // Update Margin Bottom
                    if(window.visualEditType + "-" + window.visualEditPosition == 'margin-bottom'){
                        style += ".wyp-selected-boxed-margin-bottom{ top: "+elementBottom+"px !important; width:"+outlineWidth+"px !important;left:"+marginOutLeft+"px !important; }";
                    }

                    // Update Padding Bottom
                    if(window.visualEditType + "-" + window.visualEditPosition == 'padding-bottom'){
                        style += ".wyp-selected-boxed-padding-bottom{ top: "+(elementBottom-dif)+"px !important; width:"+elementWidth+"px !important;left:"+elementLeft+"px !important; }";
                    }


                    // Int only.
                    dif = parseInt(dif);


                    // Ex .wyp-selected-boxed-padding-top:15px;
                    style += ".wyp-selected-boxed-" + window.visualEditType + "-" + window.visualEditPosition + "{ " + format + " : " + dif + "px !important; }";

                    // Set the new value to the element
                    style += "body.wyp-content-selected .wyp-selected," + window.currentLiveSelector + "{ " + rule + " : " + dif + "px !important; }";

                    // Add & Update the live CSS
                    if(iframe.find("#wyp-visual-edit-css").length == 0){

                        // Add
                        iframeBody.append("<style id='wyp-visual-edit-css'>" + style + "</style>");

                    }else{

                        // Update
                        iframe.find("#wyp-visual-edit-css").html(style);

                    }

                    // Show PX
                    iframe.find(".wyp-selected-boxed-"+window.visualEditType+"-"+window.visualEditPosition+"").html(dif+"px");

                }

            });


            /* ---------------------------------------------------- */
            /* Visual Editing : End                                 */
            /* ---------------------------------------------------- */
            iframe.on("mouseup", iframe, function() {

                if(window.visualEdit){

                    window.visualEdit = false;

                    var delay = 1;

                    // CSS To Data.
                    if (mainBody.hasClass("wyp-need-to-process")) {
                        process(false);
                        delay = 70;
                    }

                    // Add
                    body.addClass("wyp-visual-edited");

                    // Wait for process
                    setTimeout(function(){

                        // Remove
                        body.removeClass("wyp-visual-editing wyp-visual-editing-x wyp-visual-editing-y wyp-clean-look wyp-has-transform");

                        // CSS Remove
                        iframe.find("#wyp-visual-edit-css").remove();

                        // Element
                        var element = iframe.find(".wyp-selected-boxed-"+window.visualEditType+"-"+window.visualEditPosition+"");

                        // Val
                        var value = element.text();

                        // Clean HTML
                        if(parseInt(value) <= 30){
                            element.html("");
                        }

                        // Insert CSS if data not same
                        if(window.visualEditValueOr != value){

                            // Insert CSS
                            insert_rule(null, window.visualEditType + "-" + window.visualEditPosition, value);

                            // Update
                            option_change();

                            // Set default values for current property options.
                            set_default_value(window.visualEditType+"-"+window.visualEditPosition);

                            gui_update();

                        }

                    }, delay);

                    setTimeout(function(){

                        // remove active class
                        iframe.find(".wyp-visual-active").removeClass("wyp-visual-active");

                        // Update
                        body.removeClass("wyp-visual-edited");

                        // Draw
                        draw();

                    }, (delay+100));

                }

            });


            /* ---------------------------------------------------- */
            /* Doesn't getting styles while element hover           */
            /* because the editor must load only non-hover styles   */
            /* ---------------------------------------------------- */
            body.on('mousedown', '.editor-panel-list > li:not(.active)', function() {

                if (is_content_selected() === true) {

                    // Get data
                    var data = $(this).attr("data-loaded");

                    // If no data, so set.
                    if (typeof data == typeof undefined || data === false) {

                        // Set default values
                        $(this).find(".wyp-option-group").each(function() {
                            set_default_value(get_option_id(this));
                        });

                        // cache to loaded data.
                        $(this).attr("data-loaded", "true");

                    }

                }

            });


            /* ---------------------------------------------------- */
            /* ReDraw the element if hover                          */
            /* ---------------------------------------------------- */
            iframe.on("mouseout mouseover", '.wyp-selected', function() {

                if (is_content_selected() == true && is_resizing() == false && is_dragging() == false && is_visual_editing() == false) {

                    clearTimeout(window.update_drawmouseOver);
                    window.update_drawmouseOver = setTimeout(function() {
                        draw();
                    }, 50);

                }

            });


            /* ---------------------------------------------------- */
            /* Getting All ideal elements. Used by smart guides.    */
            /* ---------------------------------------------------- */
            function get_all_elements(custom){

                var selector = '*';

                var notSelectors = [
                    ".wyp-x-distance-border",
                    ".wyp-y-distance-border",
                    ".hover-info-box",
                    ".wyp-size-handle",
                    ".wyp-edit-tooltip",
                    ".wyp-edit-menu",
                    ".wyp-selected-tooltip",
                    ".wyp-tooltip-small",
                    ".wyp-helper-tooltip",
                    "[class^='wyp-selected-boxed-']",
                    "[class^='wyp-selected-others-box']",
                    "link",
                    "style",
                    "script",
                    "param",
                    "option",
                    "tr",
                    "td",
                    "th",
                    "thead",
                    "tbody",
                    "tfoot",
                    "iframe",
                    "noscript",
                    "scene-1",
                    "scene-2",
                    "scene-3",
                    "scene-4",
                    "scene-5",
                    "scene-6",
                    "wyp-anim-scenes",
                    "animate-test-drive"
                ];

                // Get classes added by editor
                var pluginClasses = window.plugin_classes_list.split("|");

                for(var x = 0; x < pluginClasses.length; x++){
                    pluginClasses[x] = "." + pluginClasses[x];
                }

                // concat
                notSelectors = notSelectors.concat(pluginClasses);

                // Adding not selectors
                for(var i = 0; i < notSelectors.length; i++){
                    selector += ":not("+notSelectors[i]+")";
                }

                // parement
                if(custom !== undefined){
                    selector += custom;
                }

                return selector;

            }


            /* ---------------------------------------------------- */
            /* None / Disable Buttons API                           */
            /* ---------------------------------------------------- */
            $(".wyp-btn-action").click(function(e) {

                // Default Variables
                var value = '', prefix = '', id, elementPP, buttonText, inputText, inputPrefix;

                // Element PP
                elementPP = $(this).parent().parent().parent();

                // Option ID: CSS Rule
                id = get_option_id(elementPP);

                // Getting button Text
                buttonText = elementPP.find(".wyp-none-btn").text();

                // Input Value
                inputText = $("#wyp-" + id).val();

                // Input Prefix
                inputPrefix = $("#" + id + "-after").val();

                // If this is None Button, setting default value of CSS Rule
                if ($(this).hasClass("wyp-none-btn")) {

                    // Shows reset icon if has value
                    if (elementPP.find(".wyp-disable-btn.active").length >= 0) {

                        // Trigger click
                        elementPP.find(".wyp-disable-btn.active").trigger("click");

                        // Make it reset-able if orginalEvent
                        if (e.originalEvent) {
                            elementPP.addClass("reset-enable");
                        }

                    }

                    // If slider
                    if (elementPP.hasClass("wyp-slider-option")) {

                        // If active
                        if ($(this).hasClass("active")) {

                            // remove active Class
                            $(this).removeClass("active");

                            // Show
                            elementPP.find(".wyp-after").show();

                            // Is Enable
                            elementPP.find(".wyp-after-disable-disable").hide();

                            // Value
                            value = inputText;
                            prefix = inputPrefix;

                        } else {

                            $(this).addClass("active");

                            // Hide
                            elementPP.find(".wyp-after").hide();

                            // Is Disable
                            elementPP.find(".wyp-after-disable-disable").show();

                            // Value
                            value = buttonText;

                        }

                        // If is radio
                    } else if (elementPP.find(".wyp-radio-content").length > 0) {

                        // If active
                        if ($(this).hasClass("active")) {

                            // remove active class
                            $(this).removeClass("active");

                            // Value
                            value = $("input[name=" + id + "]:checked").val();

                            // Add active class to checked value
                            $("input[name=" + id + "]:checked").parent().addClass("active");

                        } else {

                            // Add active Class
                            $(this).addClass("active");

                            // Remove Active class from active radios
                            elementPP.find(".wyp-radio.active").removeClass("active");

                            // Value
                            value = buttonText;

                        }

                        // If is select
                    } else if (elementPP.find("select").length > 0) {

                        // If active
                        if ($(this).hasClass("active")) {

                            // Remove active Class
                            $(this).removeClass("active");

                            // Value
                            value = inputText;

                        } else {

                            // Add active Class
                            $(this).addClass("active");

                            // Value
                            value = buttonText;

                        }

                    // Other formats
                    } else {

                        // If active
                        if ($(this).hasClass("active")) {

                            // Remove active class
                            $(this).removeClass("active");

                            // Value
                            value = inputText;

                            // Update background color
                            elementPP.find(".wqminicolors-swatch-color").css("backgroundColor",value);

                        } else {

                            // Add active class
                            $(this).addClass("active");

                            // Update background color
                            elementPP.find(".wqminicolors-swatch-color").css("backgroundColor","transparent");

                            // Value
                            value = 'transparent';

                        }

                    }

                    // Transparent value for background-image
                    if (id == 'background-image') {

                        // Auto adds URL
                        if (value.indexOf("//") != -1 && value.indexOf("linear-gradient(") == -1) {
                            value = "url(" + value + ")";
                        }

                        // transparent means none
                        if (value == 'transparent') {
                            value = 'none';
                        }

                    }

                    // None value for List style
                    if (id == 'list-style-image') {

                        // auto adds URL
                        if (value.indexOf("//") != -1) {
                            value = "url(" + value + ")";
                        }

                        // transparent mean none
                        if (value == 'transparent') {
                            value = 'none';
                        }

                    }

                } else { // disable this option

                    // Toggle active Class
                    $(this).toggleClass("active");

                }

                // Be sure it is orginalEvent
                if (e.originalEvent) {

                    // disable functions
                    if($(this).hasClass("wyp-disable-btn")){

                        // getting applied style size
                        var dynamicSize = get_applied_style_data(null, id, [false, false, true]);

                        // if valid
                        if(typeof dynamicSize === 'object'){

                            // Getting
                            dynamicSize.always(function(data){

                                if(data == 'all'){
                                    data = 'desktop';
                                }

                                // Insert disable value
                                insert_rule(null, id, 'disable', prefix, data);

                                // refresh option
                                set_default_value(id);

                                // remove reset enable class
                                elementPP.removeClass("reset-enable");

                            });

                        }

                    }else{

                        // None value
                        insert_rule(null, id, value, prefix);

                    }

                    // Trigger option change
                    option_change();

                }

                // Update panel
                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Collapse List                                        */
            /* ---------------------------------------------------- */
            $(".editor-panel-list > li > h3").click(function() {

                // Disable picker on color close.
                mainBody.removeClass("wyp-element-picker-active");
                $(".wyp-element-picker").removeClass("active");

                // Getting Element
                var element = $(this);
                var elementParent = element.parent();
                var closeBtn = $(".wyp-close-btn");
                var list = $(".editor-panel-list > li");
                var lockBtn = $(".lock-btn");

                // Toggle
                lockBtn.removeClass("active");
                list.not(elementParent).hide();
                elementParent.toggleClass("active");
                elementParent.find(".wyp-this-content").toggle();

                // Toggle
                if(elementParent.hasClass("active") == false){
                    list.show();
                }

                // Update Menu
                if ($(".wyp-close-btn.dashicons-menu").length > 0) {
                    closeBtn.removeClass("dashicons-menu").addClass("dashicons-no-alt");
                    closeBtn.tooltip('hide').attr('data-original-title', l18_close_editor).tooltip('fixTitle');
                }else{
                    closeBtn.removeClass("dashicons-no-alt").addClass("dashicons-menu");
                    closeBtn.tooltip('hide').attr('data-original-title', l18_back_to_menu).tooltip('fixTitle');
                }

                // Scroll Top
                $('.editor-panel-inner').scrollTop(0);

                // Hide Popovers
                $(".wyp-option-group,.wyp-advanced-option").popover("hide");

                // Update GUI
                gui_update();

            });


            /* ---------------------------------------------------- */
            /* Filters                                              */
            /* ---------------------------------------------------- */
            function number_filter(a) {
                if (typeof a !== "undefined" && a != '') {
                    if (a.replace(/[^\d.-]/g, '') === null || a.replace(/[^\d.-]/g, '') === undefined) {
                        return 0;
                    } else {
                        return a.replace(/[^\d.-]/g, '');
                    }
                } else {
                    return 0;
                }
            }

            function alfa_filter(a) {
                if (typeof a !== "undefined" && a != '') {
                    return a.replace(/\d/g, '').replace(".px", "px");
                } else {
                    return '';
                }
            }

            var get_basic_id = function(str) {
                if (typeof str !== "undefined" && str != '') {
                    str = str.replace(/\W+/g, "");
                    return str;
                } else {
                    return '';
                }
            };

            function get_id(str) {
                if (typeof str !== "undefined" && str != '') {

                    // \^\#\+\$\(\)\[\]\=\*\-\:\.\>\,\~\@\/\! work in process.
                    str = str.replace(/\:/g, "wyp-sym-p")
                    .replace(/\^/g, "wyp-sym-a")
                    .replace(/\#/g, "wyp-sym-c")
                    .replace(/\+/g, "wyp-sym-o")
                    .replace(/\$/g, "wyp-sym-q")
                    .replace(/\(/g, "wyp-sym-e")
                    .replace(/\)/g, "wyp-sym-s")
                    .replace(/\[/g, "wyp-sym-g")
                    .replace(/\]/g, "wyp-sym-x")
                    .replace(/\=/g, "wyp-sym-k")
                    .replace(/\*/g, "wyp-sym-n")
                    .replace(/\-/g, "wyp-sym-t")
                    .replace(/\./g, "wyp-sym-u")
                    .replace(/\>/g, "wyp-sym-l")
                    .replace(/\,/g, "wyp-sym-b")
                    .replace(/\~/g, "wyp-sym-m")
                    .replace(/\@/g, "wyp-sym-i")
                    .replace(/\//g, "wyp-sym-y")
                    .replace(/\!/g, "wyp-sym-v")
                    .replace(/[^a-zA-Z0-9_\^\#\+\$\(\)\[\]\=\*\-\:\.\>\,\~\@\/\!]/g, "");
                    return str;
                } else {
                    return '';
                }
            }

            function array_cleaner(actual) {

                var uniqueArray = actual.filter(function(item, pos) {
                    return actual.indexOf(item) == pos;
                });

                return uniqueArray;

            }

            function uppercase_first_letter(str){
                return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
            }

            function letter_repeat(str) {
                var reg = /^([a-z])\1+$/;
                var d = reg.test(str);
                return d;
            }

            function title_case(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }


            /* ---------------------------------------------------- */
            /* Getting selected element name                        */
            /* ---------------------------------------------------- */
            function get_tag_information(selectors){

                var selectorsArray = selectors.split(",");

                // If is one selector
                if(selectorsArray.length == 1){
                    return get_single_tag_information(selectors);
                }


                // Multi Selectors
                var allTagNames = [];
                var name = '';

                // Get all tag names by selectors
                for(var i = 0; i < selectorsArray.length; i++){

                    // Get tag name
                    name = get_single_tag_information(selectorsArray[i]);

                    // Push if the name not in name-list
                    if(allTagNames.indexOf(name) == -1){
                        allTagNames.push(name);
                    }

                }

                return allTagNames.toString().replace(/\,/g,", ");

            }


            /* ---------------------------------------------------- */
            /* Need it while processing stylesheet selectors        */
            /* ---------------------------------------------------- */
            function get_foundable_query(selector,css,body,animation){

                if(css === true){

                    // Hover Focus active visited link
                    selector = selector.replace(/:hover/g,'').replace(/:focus/g,'').replace(/:active/g,'').replace(/:visited/g,'').replace(/:link/g,'');

                    // After
                    selector = selector.replace(/:after/g,'').replace(/::after/g,'');

                    // Before
                    selector = selector.replace(/:before/g,'').replace(/::before/g,'');

                    // First Letter
                    selector = selector.replace(/:first-letter/g,'').replace(/::first-letter/g,'');

                    // First Line
                    selector = selector.replace(/:first-line/g,'').replace(/::first-line/g,'');

                    // Selection
                    selector = selector.replace(/:selection/g,'').replace(/::selection/g,'');

                }

                if(body === true){

                    // YP Selector Hover
                    selector = selector.replace(/body\.yp-selector-hover/g,'').replace(/\.yp-selector-hover/g,'');

                    // YP Selector Focus
                    selector = selector.replace(/body\.yp-selector-focus/g,'').replace(/\.yp-selector-focus/g,'');

                    // YP Selector active
                    selector = selector.replace(/body\.yp-selector-active/g,'').replace(/\.yp-selector-active/g,'');

                    // YP Selector visited
                    selector = selector.replace(/body\.yp-selector-visited/g,'').replace(/\.yp-selector-visited/g,'');

                    // YP Selector link
                    selector = selector.replace(/body\.yp-selector-link/g,'').replace(/\.yp-selector-link/g,'');

                }

                if(animation === true){

                    // YP Animations
                    selector = selector.replace(/.yp_onscreen/g,'').replace(/.yp_focus/g,'').replace(/.yp_hover/g,'').replace(/.yp_click/g,'');

                }

                return selector.trim();

            }


            /* ---------------------------------------------------- */
            /* Cleans multiple spaces                               */
            /* ---------------------------------------------------- */
            function space_cleaner(data){
                return $.trim(data.replace(/\s\s+/g,' '));
            }


            /* ---------------------------------------------------- */
            /* Simple Defined Element Names                         */
            /* ---------------------------------------------------- */
            function get_single_tag_information(selector){

                selector = get_foundable_query(selector,true,true,true);

                if(iframe.find(selector).length <= 0){
                    return;
                }

                var PPname,Pname;

                // tagName
                var a = iframe.find(selector)[0].nodeName;

                // length
                var length = get_selector_array(selector).length - 1;

                // Names
                var n = get_selector_array(selector)[length].toUpperCase();
                if (n.indexOf(".") != -1){
                    n = n.split(".")[1].replace(/[^\w\s]/gi, '');
                }

                // Class Names
                var className = $.trim(get_selector_array(selector)[length]);
                if (className.indexOf(".") != -1) {
                    className = className.split(".")[1];
                }

                // ID
                var id = get_selected_element().attr("id");

                if (isDefined(id)) {
                    id = id.toUpperCase().replace(/[^\w\s]/gi, '');
                }

                // Parents 1
                if (length > 1) {
                    Pname = get_selector_array(selector)[length - 1].toUpperCase();
                    if (Pname.indexOf(".") != -1) {
                        Pname = Pname.split(".")[1].replace(/[^\w\s]/gi, '');
                    }
                } else {
                    Pname = '';
                }

                // Parents 2
                if (length > 2) {
                    PPname = get_selector_array(selector)[length - 2].toUpperCase();
                    if (PPname.indexOf(".") != -1) {
                        PPname = PPname.split(".")[1].replace(/[^\w\s]/gi, '');
                    }
                } else {
                    PPname = '';
                }

                // ID
                if (id == 'TOPBAR') {
                    return l18_topbar;
                } else if (id == 'HEADER') {
                    return l18_header;
                } else if (id == 'FOOTER') {
                    return l18_footer;
                } else if (id == 'CONTENT') {
                    return l18_content;
                }

                // Parrents Class
                if (PPname == 'LOGO' || PPname == 'SITETITLE' || Pname == 'LOGO' || Pname == 'SITETITLE') {
                    return l18_logo;
                } else if (n == 'MAPCANVAS') {
                    return l18_google_map;
                } else if (Pname == 'ENTRYTITLE' && a == 'A') {
                    return l18_entry_title_link;
                } else if (Pname == 'CATLINKS' && a == 'A') {
                    return l18_category_link;
                } else if (Pname == 'TAGSLINKS' && a == 'A') {
                    return l18_tag_link;
                }

                // Current Classes
                if (n == 'WIDGET') {
                    return l18_widget;
                } else if (n == 'FA' || get_selector_array(selector)[length].toUpperCase().indexOf("FA-") >= 0) {
                    return l18_font_awesome_icon;
                } else if (n == 'SUBMIT' && a == 'INPUT') {
                    return l18_submit_button;
                } else if (n == 'MENUITEM') {
                    return l18_menu_item;
                } else if (n == 'ENTRYMETA' || n == 'ENTRYMETABOX' || n == 'POSTMETABOX') {
                    return l18_post_meta_division;
                } else if (n == 'COMMENTREPLYTITLE') {
                    return l18_comment_reply_title;
                } else if (n == 'LOGGEDINAS') {
                    return l18_login_info;
                } else if (n == 'FORMALLOWEDTAGS') {
                    return l18_allowed_tags;
                } else if (n == 'LOGO') {
                    return l18_logo;
                } else if (n == 'ENTRYTITLE' || n == 'POSTTITLE') {
                    return l18_post_title;
                } else if (n == 'COMMENTFORM') {
                    return l18_comment_form;
                } else if (n == 'WIDGETTITLE') {
                    return l18_widget_title;
                } else if (n == 'TAGCLOUD') {
                    return l18_tag_cloud;
                } else if (n == 'ROW' || n == 'VCROW') {
                    return l18_row;
                } else if (n == 'BUTTON') {
                    return l18_button;
                } else if (n == 'BTN') {
                    return l18_button;
                } else if (n == 'LEAD') {
                    return l18_lead;
                } else if (n == 'WELL') {
                    return l18_well;
                } else if (n == 'ACCORDIONTOGGLE') {
                    return l18_accordion_toggle;
                } else if (n == 'PANELBODY') {
                    return l18_accordion_content;
                } else if (n == 'ALERT') {
                    return l18_alert_division;
                } else if (n == 'FOOTERCONTENT') {
                    return l18_footer_content;
                } else if (n == 'GLOBALSECTION' || n == 'VCSSECTION') {
                    return l18_global_section;
                } else if (n == 'MORELINK') {
                    return l18_show_more_link;
                } else if (n == 'CONTAINER' || n == 'WRAPPER') {
                    return l18_wrapper;
                } else if (n == 'DEFAULTTITLE') {
                    return l18_article_title;
                } else if (n == 'MENULINK' || n == 'MENUICON' || n == 'MENUBTN' || n == 'MENUBUTTON') {
                    return l18_menu_link;
                } else if (n == 'SUBMENU') {
                    return l18_submenu;

                    // Bootstrap Columns
                } else if (n.indexOf('COLMD12') != -1 || n == 'MEDIUM12' || n == 'LARGE12' || n == 'SMALL12') {
                    return l18_column + ' 12/12';
                } else if (n.indexOf('COLMD11') != -1 || n == 'MEDIUM11' || n == 'LARGE11' || n == 'SMALL11') {
                    return l18_column + ' 11/12';
                } else if (n.indexOf('COLMD10') != -1 || n == 'MEDIUM10' || n == 'LARGE10' || n == 'SMALL10') {
                    return l18_column + ' 10/12';
                } else if (n.indexOf('COLMD9') != -1 || n == 'MEDIUM9' || n == 'LARGE9' || n == 'SMALL9') {
                    return l18_column + ' 9/12';
                } else if (n.indexOf('COLMD8') != -1 || n == 'MEDIUM8' || n == 'LARGE8' || n == 'SMALL8') {
                    return l18_column + ' 8/12';
                } else if (n.indexOf('COLMD7') != -1 || n == 'MEDIUM7' || n == 'LARGE7' || n == 'SMALL7') {
                    return l18_column + ' 7/12';
                } else if (n.indexOf('COLMD6') != -1 || n == 'MEDIUM6' || n == 'LARGE6' || n == 'SMALL6') {
                    return l18_column + ' 6/12';
                } else if (n.indexOf('COLMD5') != -1 || n == 'MEDIUM5' || n == 'LARGE5' || n == 'SMALL5') {
                    return l18_column + ' 5/12';
                } else if (n.indexOf('COLMD4') != -1 || n == 'MEDIUM4' || n == 'LARGE4' || n == 'SMALL4') {
                    return l18_column + ' 4/12';
                } else if (n.indexOf('COLMD3') != -1 || n == 'MEDIUM3' || n == 'LARGE3' || n == 'SMALL3') {
                    return l18_column + ' 3/12';
                } else if (n.indexOf('COLMD2') != -1 || n == 'MEDIUM2' || n == 'LARGE2' || n == 'SMALL2') {
                    return l18_column + ' 2/12';
                } else if (n.indexOf('COLMD1') != -1 || n == 'MEDIUM1' || n == 'LARGE1' || n == 'SMALL1') {
                    return l18_column + ' 1/12';
                } else if (n.indexOf('COLXS12') != -1) {
                    return l18_column + ' 12/12';
                } else if (n.indexOf('COLXS11') != -1) {
                    return l18_column + ' 11/12';
                } else if (n.indexOf('COLXS10') != -1) {
                    return l18_column + ' 10/12';
                } else if (n.indexOf('COLXS9') != -1) {
                    return l18_column + ' 9/12';
                } else if (n.indexOf('COLXS8') != -1) {
                    return l18_column + ' 8/12';
                } else if (n.indexOf('COLXS7') != -1) {
                    return l18_column + ' 7/12';
                } else if (n.indexOf('COLXS6') != -1) {
                    return l18_column + ' 6/12';
                } else if (n.indexOf('COLXS5') != -1) {
                    return l18_column + ' 5/12';
                } else if (n.indexOf('COLXS4') != -1) {
                    return l18_column + ' 4/12';
                } else if (n.indexOf('COLXS3') != -1) {
                    return l18_column + ' 3/12';
                } else if (n.indexOf('COLXS2') != -1) {
                    return l18_column + ' 2/12';
                } else if (n.indexOf('COLXS1') != -1) {
                    return l18_column + ' 1/12';
                } else if (n.indexOf('COLSM12') != -1) {
                    return l18_column + ' 12/12';
                } else if (n.indexOf('COLSM11') != -1) {
                    return l18_column + ' 11/12';
                } else if (n.indexOf('COLSM10') != -1) {
                    return l18_column + ' 10/12';
                } else if (n.indexOf('COLSM9') != -1) {
                    return l18_column + ' 9/12';
                } else if (n.indexOf('COLSM8') != -1) {
                    return l18_column + ' 8/12';
                } else if (n.indexOf('COLSM7') != -1) {
                    return l18_column + ' 7/12';
                } else if (n.indexOf('COLSM6') != -1) {
                    return l18_column + ' 6/12';
                } else if (n.indexOf('COLSM5') != -1) {
                    return l18_column + ' 5/12';
                } else if (n.indexOf('COLSM4') != -1) {
                    return l18_column + ' 4/12';
                } else if (n.indexOf('COLSM3') != -1) {
                    return l18_column + ' 3/12';
                } else if (n.indexOf('COLSM2') != -1) {
                    return l18_column + ' 2/12';
                } else if (n.indexOf('COLSM1') != -1) {
                    return l18_column + ' 1/12';
                } else if (n.indexOf('COLLG12') != -1) {
                    return l18_column + ' 12/12';
                } else if (n.indexOf('COLLG11') != -1) {
                    return l18_column + ' 11/12';
                } else if (n.indexOf('COLLG10') != -1) {
                    return l18_column + ' 10/12';
                } else if (n.indexOf('COLLG9') != -1) {
                    return l18_column + ' 9/12';
                } else if (n.indexOf('COLLG8') != -1) {
                    return l18_column + ' 8/12';
                } else if (n.indexOf('COLLG7') != -1) {
                    return l18_column + ' 7/12';
                } else if (n.indexOf('COLLG6') != -1) {
                    return l18_column + ' 6/12';
                } else if (n.indexOf('COLLG5') != -1) {
                    return l18_column + ' 5/12';
                } else if (n.indexOf('COLLG4') != -1) {
                    return l18_column + ' 4/12';
                } else if (n.indexOf('COLLG3') != -1) {
                    return l18_column + ' 3/12';
                } else if (n.indexOf('COLLG2') != -1) {
                    return l18_column + ' 2/12';
                } else if (n.indexOf('COLLG1') != -1) {
                    return l18_column + ' 1/12';
                } else if (n == 'POSTBODY') {
                    return l18_post_division;
                } else if (n == 'POST') {
                    return l18_post_division;
                } else if (n == 'CONTENT' || n == 'DEFAULTCONTENT') {
                    return l18_content_division;
                } else if (n == 'ENTRYTITLE') {
                    return l18_entry_title;
                } else if (n == 'ENTRYCONTENT') {
                    return l18_entry_content;
                } else if (n == 'ENTRYFOOTER') {
                    return l18_entry_footer;
                } else if (n == 'ENTRYHEADER') {
                    return l18_entry_header;
                } else if (n == 'ENTRYTIME') {
                    return l18_entry_time;
                } else if (n == 'POSTEDITLINK') {
                    return l18_post_edit_link;
                } else if (n == 'POSTTHUMBNAIL') {
                    return l18_post_thumbnail;
                } else if (n == 'THUMBNAIL') {
                    return l18_thumbnail;
                } else if (n.indexOf("ATTACHMENT") >= 0) {
                    return l18_thumbnail_image;
                } else if (n == 'EDITLINK') {
                    return l18_edit_link;
                } else if (n == 'COMMENTSLINK') {
                    return l18_comments_link_division;
                } else if (n == 'SITEDESCRIPTION') {
                    return l18_site_description;
                } else if (n == 'POSTCLEAR' || n == 'POSTBREAK') {
                    return l18_post_break;
                }

                // Smart For ID
                if (get_name_by_classes(id) !== false) {
                    return get_name_by_classes(id);
                }

                // Smart For Class
                if (get_name_by_classes(className) !== false) {
                    return get_name_by_classes(className);
                }

                // If not have name found, use clear.
                if (n.indexOf("CLEARFIX") != -1 || n.indexOf("CLEARBOTH") != -1 || n == "CLEAR") {
                    return l18_clear;
                }

                // TAG NAME START
                if (a == 'P') {
                    return l18_paragraph;
                } else if (a == 'BR') {
                    return l18_line_break;
                } else if (a == 'HR') {
                    return l18_horizontal_rule;
                } else if (a == 'A') {
                    return l18_link;
                } else if (a == 'LI') {
                    return l18_list_item;
                } else if (a == 'UL') {
                    return l18_unorganized_list;
                } else if (a == 'OL') {
                    return l18_unorganized_list;
                } else if (a == 'IMG') {
                    return l18_image;
                } else if (a == 'B') {
                    return l18_bold_tag;
                } else if (a == 'I') {
                    return l18_italic_tag;
                } else if (a == 'STRONG') {
                    return l18_strong_tag;
                } else if (a == 'Em') {
                    return l18_italic_tag;
                } else if (a == 'BLOCKQUOTE') {
                    return l18_blockquote;
                } else if (a == 'PRE') {
                    return l18_preformatted;
                } else if (a == 'TABLE') {
                    return l18_table;
                } else if (a == 'TR') {
                    return l18_table_row;
                } else if (a == 'TD') {
                    return l18_table_data;
                } else if (a == 'HEADER' || n == 'HEADER') {
                    return l18_header_division;
                } else if (a == 'FOOTER' || n == 'FOOTER') {
                    return l18_footer_division;
                } else if (a == 'SECTION' || n == 'SECTION') {
                    return l18_section;
                } else if (a == 'FORM') {
                    return l18_form_division;
                } else if (a == 'BUTTON') {
                    return l18_button;
                } else if (a == 'CENTER') {
                    return l18_centred_block;
                } else if (a == 'DL') {
                    return l18_definition_list;
                } else if (a == 'DT') {
                    return l18_definition_term;
                } else if (a == 'DD') {
                    return l18_definition_description;
                } else if (a == 'H1') {
                    return l18_header + ' (' + l18_level + ' 1)';
                } else if (a == 'H2') {
                    return l18_header + ' (' + l18_level + ' 2)';
                } else if (a == 'H3') {
                    return l18_header + ' (' + l18_level + ' 3)';
                } else if (a == 'H4') {
                    return l18_header + ' (' + l18_level + ' 4)';
                } else if (a == 'H5') {
                    return l18_header + ' (' + l18_level + ' 5)';
                } else if (a == 'H6') {
                    return l18_header + ' (' + l18_level + ' 6)';
                } else if (a == 'SMALL') {
                    return l18_smaller_text;
                } else if (a == 'TEXTAREA') {
                    return l18_text_area;
                } else if (a == 'TBODY') {
                    return l18_body_of_table;
                } else if (a == 'THEAD') {
                    return l18_head_of_table;
                } else if (a == 'TFOOT') {
                    return l18_foot_of_table;
                } else if (a == 'U') {
                    return l18_underline_text;
                } else if (a == 'SPAN') {
                    return l18_span;
                } else if (a == 'Q') {
                    return l18_quotation;
                } else if (a == 'CITE') {
                    return l18_citation;
                } else if (a == 'CODE') {
                    return l18_expract_of_code;
                } else if (a == 'NAV' || n == 'NAVIGATION' || n == 'NAVIGATIONCONTENT') {
                    return l18_navigation;
                } else if (a == 'LABEL') {
                    return l18_label;
                } else if (a == 'TIME') {
                    return l18_time;
                } else if (a == 'DIV') {
                    return l18_division;
                } else if (a == 'CAPTION') {
                    return l18_caption_of_table;
                } else if (a == 'INPUT') {
                    return l18_input;
                } else {
                    return a.toLowerCase();
                }

            }


            /* ---------------------------------------------------- */
            /* Reading nice class names                             */
            /* ---------------------------------------------------- */
            function get_name_by_classes(className) {

                if (typeof className == typeof undefined || className === false) {
                    return false;
                }

                // RegExp
                var upperCase = new RegExp('[A-Z]');
                var numbers = new RegExp('[0-9]');
                var bottomRex = /_/;
                var topRex = /-/;

                // Only - or _
                if (bottomRex.test(className) && topRex.test(className)) {
                    return false;
                }

                // max 3 -
                if (topRex.test(className)) {
                    if (className.match(/-/g).length >= 3) {
                        return false;
                    }
                }

                // max 3 _
                if (bottomRex.test(className)) {
                    if (className.match(/_/g).length >= 3) {
                        return false;
                    }
                }

                // Clean
                className = className.replace(/_/g, ' ').replace(/-/g, ' ');

                var classNames = get_classes_array(className);

                var i = 0;
                for (i = 0; i < classNames.length; i++) {
                    if (classNames[i].length < 4 || classNames[i].length > 12) {
                        return false;
                    }
                }

                // if all lowerCase
                // if not any number
                // if minimum 3 and max 20
                if (className.match(upperCase) || className.match(numbers) || className.length < 5 || className.length > 20) {
                    return false;
                }

                if (letter_repeat(className)) {
                    return false;
                }

                // For id.
                className = className.replace("#", "");

                return title_case(className);

            }


            /* ---------------------------------------------------- */
            /* Update save button                                   */
            /* ---------------------------------------------------- */
            function option_change(){

                clearTimeout(window.wyp_insert_data_delay);

                mainBody.addClass("wyp-history-delay");

                if(window.option_changeType != 'auto'){
                    $(".wyp-save-btn").html(l18_save).removeClass("wyp-disabled").addClass("waiting-for-save");
                }

                window.wyp_insert_data_delay = setTimeout(function() {

                    var data = get_clean_css(true);

                    editor.setValue(data);

                    mainBody.removeClass("wyp-history-delay");

                }, 200);

                // Update undo/redo icons
                setTimeout(function(){
                    check_undoable_history();
                },220);

                // Update breakpoints if responsive mode
                if(is_responsive_mod){
                    update_responsive_breakpoints();
                }

                // Update box model in design information box if visible
                if($(".info-btn.active").length > 0){
                    update_box_model();
                }

                // Add updated class to current data
                if(window.option_changeType != 'auto'){

                    the_editor_data().addClass("wyp-data-updated");
                    $(".active-customizing-list").addClass("customizing-type-updated");

                    // Focus To Type
                    if($(".wyp-type-menu-link.done").length == 0){
                        $(".wyp-type-menu-link").addClass("focus").addClass("done");
                        setTimeout(function(){
                            $(".wyp-type-menu-link").removeClass("focus");
                        }, 600);
                    }

                }

            }


            /* ---------------------------------------------------- */
            /* Process all and get a clean CSS                      */
            /* ---------------------------------------------------- */
            function process(close) {

                // close css editor with process..
                if (close === true) {

                    the_editor_data().find(".wyp-styles-area style[data-rule='a']").remove();

                    $("#cssData,#cssEditorBar,#leftAreaEditor").hide();
                    iframeBody.trigger("scroll");
                    mainBody.removeClass("wyp-css-editor-active");

                    $(".css-editor-btn").attr("data-original-title",$(".css-editor-btn").attr("data-title"));

                    // Update All.
                    draw();

                }

                // IF not need to process, stop here.
                if (mainBody.hasClass("wyp-need-to-process") === false || mainBody.hasClass("wyp-processing-now")) {
                    return false;
                }

                // Remove class.
                mainBody.removeClass("wyp-need-to-process");

                mainBody.addClass("wyp-processing-now");

                // Processing.
                if (mainBody.find(".wyp-processing").length === 0) {
                    mainBody.append("<div class='wyp-processing'><span></span><p>" + l18_process + "</p></div>");
                } else {
                }

                if (editor.getValue().length > 800) {
                    mainBody.find(".wyp-processing").show();
                }

                setTimeout(function() {

                    css_to_data('desktop');

                    if (editor.getValue().toString().indexOf("@media") != -1) {

                        var mediaTotal = editor.getValue().toString().replace(/(\r\n|\n|\r)/g, "").match(/@media(.*?){/g);

                        // Search medias and convert to Yellow Pencil Data
                        $.each(mediaTotal, function(index, value) {

                            // make .min the media content
                            value = get_minimized_css(value,false);

                            css_to_data(value);

                        });

                    }

                    iframe.find("#wyp-css-data-full").remove();

                    // Added from css_to_data function. must remove.
                    mainBody.removeClass("process-by-code-editor");

                    setTimeout(function() {

                        mainBody.removeClass("wyp-processing-now");
                        mainBody.find(".wyp-processing").hide();

                        // current
                        var a = get_minimized_css(editor.getValue()).replace(/\}(.*?)\{(\n+|\r+|\s+)?\}/g, '}').replace(/^(.*?)\{(\n+|\r+|\s+)?\}/g, '');

                        // new
                        var b = get_minimized_css(get_clean_css(true));

                        // update if not same
                        if(a != b){
                            editor.setValue(get_clean_css(true));
                        }

                    }, 5);

                    if(is_animation_manager()){
                        animation_manager();
                    }

                }, 50);

            }


            /* ---------------------------------------------------- */
            /* RGB To hex                                           */
            /* ---------------------------------------------------- */
            function get_color(rgb) {
                if (typeof rgb !== 'undefined') {

                    if(rgb.indexOf("rgba") != -1){
                        return rgb.replace(/\s+/g,"");
                    }

                    rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);

                    return (rgb && rgb.length === 4) ? "#" + ("0" + parseInt(rgb[1], 10).toString(16)).slice(-2) + ("0" + parseInt(rgb[2], 10).toString(16)).slice(-2) + ("0" + parseInt(rgb[3], 10).toString(16)).slice(-2) : '';

                } else {
                    return '';
                }
            }


            /* ---------------------------------------------------- */
            /* Hex To RGB                                           */
            /* ---------------------------------------------------- */
            function hex_to_rgb(hex){

                var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
                hex = hex.replace(shorthandRegex, function(m, r, g, b) {
                    return r + r + g + g + b + b;
                });

                var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);

                if(result){
                    return 'rgb('+parseInt(result[1], 16)+','+parseInt(result[2], 16)+','+parseInt(result[3], 16)+')';
                }else{
                    return null;
                }

            }


            // Long to short sorted for replacement.
            window.plugin_classes_list_sorted = window.plugin_classes_list.split("|").sort(function(a, b){return b.length - a.length;}).join("|");


            /* ---------------------------------------------------- */
            /* Clean all classes which added by the plugin.         */
            /* ---------------------------------------------------- */
            function class_cleaner(data) {

                if (isUndefined(data)) {
                    return '';
                }

                return data.replace(new RegExp(window.plugin_classes_list_sorted,"gi"), '');

            }


            /* ---------------------------------------------------- */
            /* Clear all animation timers                           */
            /* ---------------------------------------------------- */
            function clear_animation_timer(){

                clearTimeout(window.animationTimer1);
                clearTimeout(window.animationTimer2);
                clearTimeout(window.animationTimer3);
                clearTimeout(window.animationTimer4);

            }


            /* ---------------------------------------------------- */
            /* Stop the element animation.                          */
            /* ---------------------------------------------------- */
            function element_animation_end(){

                if(is_content_selected()){
                    get_selected_element().trigger("animationend webkitAnimationEnd oanimationend MSAnimationEnd");
                }

            }


            /* ---------------------------------------------------- */
            /* Adds class to body on a selector string.             */
            /* ---------------------------------------------------- */
            function add_class_to_body(selector, prefix) {

                var selectorOrginal = selector;

                // Basic
                if (selector == 'body') {
                    selector = selector + "." + prefix;
                }

                // If class added, return.
                if (selector.indexOf("body." + prefix) != -1) {
                    return selector;
                }

                var firstHTML = '';
                if (get_selector_array(selector).length > 0) {

                    var firstSelector = $.trim(get_selector_array(selector)[0]);

                    if (firstSelector.toLowerCase() == 'html') {
                        firstHTML = firstSelector;
                    }

                    if (iframe.find(firstSelector).length > 0) {
                        if (firstSelector.indexOf("#") != -1) {
                            if (iframe.find(firstSelector)[0].nodeName == 'HTML') {
                                firstHTML = firstSelector;
                            }
                        }

                        if (firstSelector.indexOf(".") != -1) {
                            if (iframe.find(firstSelector)[0].nodeName == 'HTML') {
                                firstHTML = firstSelector;
                            }
                        }
                    }

                    if (firstHTML != '') {
                        selector = get_selector_array(selector)[1];
                    }

                }

                // find body tag
                selector = selector.replace(/\bbody\./g, 'body.' + prefix + ".");
                selector = selector.replace(' body ', ' body.' + prefix + " ");

                // If class added, return.
                if (selector.indexOf("body." + prefix) != -1) {
                    if (firstHTML != '') {
                        selector = firstHTML + " " + selector;
                    }

                    return selector;
                }

                // Get all body classes.
                if (iframeBody.attr("class") !== undefined && iframeBody.attr("class") !== null) {

                    // Find element
                    var element = iframe.find(selectorOrginal);

                    if(element.length > 0){

                        if(element[0].nodeName == 'BODY'){

                            var bodyClasses = get_classes_array(iframeBody.attr("class"));

                            // Adding to next to classes.
                            for (var i = 0; i < bodyClasses.length; i++) {
                                selector = selector.replace("." + bodyClasses[i] + " ", "." + bodyClasses[i] + "." + prefix + " ");

                                if (get_selector_array(selector).length == 1 && bodyClasses[i] == selector.replace(".", "")) {
                                    selector = selector + "." + prefix;
                                }

                            }

                        }

                    }

                }

                // If class added, return.
                if (selector.indexOf("." + prefix + " ") != -1) {
                    if (firstHTML != '') {
                        selector = firstHTML + " " + selector;
                    }

                    return selector;
                }

                // If class added, return.
                if (selector.indexOf("." + prefix) != -1 && get_selector_array(selector).length == 1) {
                    if (firstHTML != '') {
                        selector = firstHTML + " " + selector;
                    }

                    return selector;
                }

                // Get body id.
                var bodyID = iframeBody.attr("id");

                selector = selector.replace("#" + bodyID + " ", "#" + bodyID + "." + prefix + " ");

                // If class added, return.
                if (selector.indexOf("." + prefix + " ") != -1) {
                    if (firstHTML != '') {
                        selector = firstHTML + " " + selector;
                    }

                    return selector;
                }

                selector = "YPIREFIX" + selector;
                selector = selector.replace(/YPIREFIXbody /g, 'body.' + prefix + " ");
                selector = selector.replace("YPIREFIX", "");

                // If class added, return.
                if (selector.indexOf("body." + prefix + " ") != -1) {
                    if (firstHTML != '') {
                        selector = firstHTML + " " + selector;
                    }

                    return selector;
                }

                if (selector.indexOf(" body ") == -1 || selector.indexOf(" body.") == -1) {
                    selector = "body." + prefix + " " + selector;
                }

                if (firstHTML != '') {
                    selector = firstHTML + " " + selector;
                }

                return selector;

            }


            /* ---------------------------------------------------- */
            /* Disable history shift mouse.                         */
            /* ---------------------------------------------------- */
            mainDocument.keydown(function(e){

                var tag = e.target.tagName.toLowerCase();

                if(tag != 'input' && tag != 'textarea'){

                    if (e.shiftKey && (e.which == '61' || e.which == '107' || e.which == '173' || e.which == '109'  || e.which == '187'  || e.which == '189')){
                            e.preventDefault();
                    }

                }

            });


            /* ---------------------------------------------------- */
            /* Disable shift + scroll event.                        */
            /* ---------------------------------------------------- */
            mainDocument.bind('mousewheel DOMMouseScroll', function (e) {
                if (e.shiftKey) {
                   e.preventDefault();
                }
            });


        }; // Yellow Pencil main function.

}(jQuery));
