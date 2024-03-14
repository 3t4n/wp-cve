"use strict";
/**!
 * Fun fact Public Scripts
 *
 * @author AbsolutePlugins <support@absoluteplugins.com>
 * @package AbsolutePlugins
 * @version 1.0.0
 */!function(n,e,t){n(e).on("elementor/frontend/init",(function(){elementorFrontend.hooks.addAction("frontend/element_ready/absolute-fun-fact.default",(function(n){var e=n.find(".fun-fact-count");e.counterUp({delay:e.data("delay"),time:e.data("time")})}))}))}(jQuery,window,window.elementorFrontend);