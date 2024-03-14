"use strict";
/**!
 * Advance Tab Public Scripts
 *
 * @author AbsolutePlugins <support@absoluteplugins.com>
 * @package AbsolutePlugins
 * @version 1.0.0
 *
 */!function(e,n,o){e(n).on("elementor/frontend/init",(function(){elementorFrontend.hooks.addAction("frontend/element_ready/absolute-advance-tab.default",(function(n){n.find(".absp-tab-item").beefup({animation:"fade",content:".absp-nav-body",scroll:!1,openSingle:!0,openSpeed:400,closeSpeed:400,onOpen:function(n){e('a[href="#'+n.attr("id")+'"]').parent().addClass(this.openClass).siblings().removeClass(this.openClass)}})}))}))}(jQuery,window,window.elementorFrontend);