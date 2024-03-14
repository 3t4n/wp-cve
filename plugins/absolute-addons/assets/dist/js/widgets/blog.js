"use strict";
/**!
 * Blog Page Public Scripts
 *
 * @author AbsolutePlugins <support@absoluteplugins.com>
 * @package AbsolutePlugins
 * @version 1.0.5
 */jQuery(window).on("elementor/frontend/init",(function(){elementorFrontend.hooks.addAction("frontend/element_ready/absolute-blog-page.default",(function(e,t){var o=e.find(".absp-grid");o.isotope({itemSelector:".absp-col",percentPosition:!0,masonry:{columnWidth:1}}),o.imagesLoaded().progress((function(){o.isotope("layout")})),t(".absp-filter-item a",e).on("click",(function(){var e=t(this);o.isotope({filter:e.attr("data-filter")}),e.closest("ul").find("a").removeClass("is-active"),e.addClass("is-active")}))}))}));