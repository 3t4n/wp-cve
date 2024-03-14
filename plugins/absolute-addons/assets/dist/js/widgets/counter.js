"use strict";
/**!
 * Counter Public Scripts
 *
 * @author AbsolutePlugins <support@absoluteplugins.com>
 * @package AbsolutePlugins
 * @version 1.0.0
 */!function(t,n,e){t(n).on("elementor/frontend/init",(function(){elementorFrontend.hooks.addAction("frontend/element_ready/absolute-counter.default",(function(n){var e=n.find(".count");e.counterUp({delay:e.data("delay"),time:e.data("time")}),t(".line-progressbar").each((function(){var n=t(this);var e=0;n.waypoint((function(){(e+=1)<2&&n.LineProgressbar({percentage:n.data("percentage"),unit:n.data("unit"),animation:n.data("animation"),ShowProgressCount:n.data("showcount"),duration:n.data("duration"),radius:n.data("radius"),height:n.data("height"),width:n.data("width")})}),{offset:"100%",triggerOnce:!0})}))}))}))}(jQuery,window,window.elementorFrontend);