"use strict";
/**!
 * FAQs Public Scripts
 *
 * @author AbsolutePlugins <support@absoluteplugins.com>
 * @package AbsolutePlugins
 * @version 1.0.0
 */!function(e,n,i){e(n).on("elementor/frontend/init",(function(){elementorFrontend.hooks.addAction("frontend/element_ready/absolute-faq.default",(function(n){var i=n.find(".accordion");if(n.find(".faq-tab-item").beefup({trigger:".tab-head",content:".tab-body",animation:"fade",openSingle:!0,openSpeed:400,selfClose:!1,closeSpeed:400,onOpen:function(n){e('a[href="#'+n.attr("id")+'"]').parent().addClass(this.openClass).siblings().removeClass(this.openClass)}}),i.beefup({animation:"fade",openSingle:!0,openSpeed:400,closeSpeed:400}),i.each((function(){e(this).find(".accordion").beefup({onOpen:function(e){e.find(".faq-trim-words").slideUp()},onClose:function(e){e.find(".faq-trim-words").slideDown()}})})),n.find(".faqsearch").length){var t=n.find(".faq"),o=[];t.each((function(){o.push({el:e(this),question:e(this).find("button").text().trim(),answer:e(this).find(".collapse-body p").text().trim()})}));var s=new Sifter(o);n.find(".faqsearch").on("keyup",(function(){var n=e(this).val();n.length||t.show(),n.length<3||(t.hide(),s.search(n,{fields:["question","answer"],sort:[{field:"question",direction:"asc"}]}).items.map((function(e){e.score>=.3&&o[e.id].el.show()})))}))}}))}))}(jQuery,window,window.elementorFrontend);