/*!
 * 
 * Monarch Sidebar Minimized on Mobile
 * 
 * @author Tomáš Groulík <deeppresentation>
 * @version 1.2.5
 * @link http://www.gnu.org/licenses/gpl-2.0.txt
 * @license GPL-2.0+
 * 
 * Copyright (c) 2022 Tomáš Groulík <deeppresentation>
 * 
 * This plugin is released under GPL-2.0+ licence to be included in wordpres.org plugin repositary
 * 
 * Compiled with the help of https://wpack.io
 * A zero setup Webpack Bundler Script for WordPress
 */
(window.wpackioggMonarchSidebarMinimizedOnMobilemainJsonp=window.wpackioggMonarchSidebarMinimizedOnMobilemainJsonp||[]).push([[1],{25:function(e,o,n){n(1),e.exports=n(56)},56:function(e,o,n){"use strict";n.r(o);var t=n(17),i=n(18),a=n(3),c=n(4),s=n.n(c),d=n(6),r=n.n(d),l=n(19),p=n.n(l),g=n(20),u=n.n(g);s.a.defaults.headers.post["Content-Type"]="application/json";var h=Object(t.a)((function e(o){var n=this;Object(i.a)(this,e),Object(a.a)(this,"closeNoticeAndSaveCookieFlag",(function(e){var o=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"1",n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:30,t=$(e).closest(".dp-msmom-notice");if(t&&t.length){var i=t.attr("id");t.remove(),i&&u.a.set(i,o,{expires:n})}})),Object(a.a)(this,"activateShowTillUserReactNotices",(function(){$(".dp-notice-show-till-user-react .dp-notice__button, .dp-notice-show-till-user-react .notice-dismiss").on("click",(function(e){n.closeNoticeAndSaveCookieFlag(e.target,"1",30)}))})),Object(a.a)(this,"sendFeedbackToDeepPresentationCom",(function(e){s.a.post("https://deeppresentation.com/wp-json/dp-feedback/gg-monarch-sidebar-minimized-on-mobile/v1/".concat(e)).then((function(e){})).catch((function(e){console.log(new r.a(e))}))})),Object(a.a)(this,"activateA4R",(function(){$("#a4r-link-already-did").on("click",(function(e){e.preventDefault(),e.stopPropagation(),n.closeNoticeAndSaveCookieFlag(e.target,"1",30),n.sendFeedbackToDeepPresentationCom("already-did")})),$("#a4r-link-no-good").on("click",(function(e){e.preventDefault(),e.stopPropagation(),n.closeNoticeAndSaveCookieFlag(e.target,"1",15),n.sendFeedbackToDeepPresentationCom("no-good")})),$("#a4r-link-OK").on("click",(function(e){n.closeNoticeAndSaveCookieFlag(e.target,"1",50),n.sendFeedbackToDeepPresentationCom("ok")}))})),Object(a.a)(this,"adjustWpSettings",(function(e){s.a.post(p()(n.config.siteUrl,"/wp-json/dpmonarchsidebarminimizedonmobile/v1/update-dp-basic-options?adminator_nonce="+n.config.nonces.adminator_nonce),e,{headers:{"X-WP-Nonce":n.config.nonces.wp_rest}}).then((function(e){})).catch((function(e){console.log(new r.a(e))}))})),o.dpDebugEn&&console.log("DPNotices"),this.config=o,s.a.defaults.baseURL=this.config.siteUrl,this.activateA4R(),this.activateShowTillUserReactNotices()}));jQuery(document).ready((function(e){window.$=jQuery,new h(monarch_admin_config)}))}},[[25,0,5]]]);
//# sourceMappingURL=monarchSidebarMinAdmin-bcb6b96c.js.map