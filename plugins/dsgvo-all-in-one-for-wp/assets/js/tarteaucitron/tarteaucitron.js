function dsgvoaio_ga_outout() {
    let t = '<div id="analyticsDenied" class="tarteaucitronDenied" name="" onclick="tarteaucitron.userInterface.respond(this, false);" style="background-color: rgb(27, 135, 11);">           \u2713 Erlauben       </div>';
    var dummy = document.createElement("div");
    (dummy.id = "analyticsDenied"),
        (dummy.innerHTML = t.toString()),
        tarteaucitron.userInterface.respond(dummy.children[0], !1),
        jQuery(".switch_analytics").attr("onclick", "tarteaucitron.userInterface.respond(this, true);"),
        jQuery(".switch_analytics").attr("checked", !1),
        jQuery(".tarteaucitronLine .switch_key_analytics").attr("checked", !1);
}
function dsvgoaio_fbpixel_ouout() {
    let t = '<div id="facebookpixelDenied" class="tarteaucitronDenied" name="" onclick="tarteaucitron.userInterface.respond(this, false);" style="background-color: rgb(27, 135, 11);">           \u2713 Erlauben       </div>';
    var dummy = document.createElement("div");
    (dummy.id = "facebookpixelDenied"),
        (dummy.innerHTML = t.toString()),
        tarteaucitron.userInterface.respond(dummy.children[0], !1),
        jQuery(".switch_facebookpixel").attr("onclick", "tarteaucitron.userInterface.respond(this, true);"),
        jQuery(".switch_facebookpixel").attr("checked", !1),
        jQuery(".tarteaucitronLine .switch_key_facebookpixel").attr("checked", !1);
}
function mail(name, dom, tl, params) {
    var s = e(name, dom, tl);
    document.write('<a href="' + m_ + s + params + '">' + s + "</a>");
}
function dsgvoaio_cryptmail(name, dom, tl, params, display) {
    document.write('<a href="' + m_ + e(name, dom, tl) + params + '">' + display + "</a>");
}
function e(name, dom, tl) {
    var s = name + a_;
    return tl != -2 ? ((s += dom), tl >= 0 && (s += d_ + tld_[tl])) : (s += swapper(dom)), s;
}
function swapper(d) {
    var s = "";
    for (var i = 0; i < d.length; i += 2) i + 1 == d.length ? (s += d.charAt(i)) : (s += d.charAt(i + 1) + d.charAt(i));
    return s.replace(/\?/g, ".");
}
var scripts = document.getElementsByTagName("script"),
    path = scripts[scripts.length - 1].src.split("?")[0],
    tarteaucitronForceCDN = tarteaucitronForceCDN === undefined ? "" : tarteaucitronForceCDN,
    cdn = tarteaucitronForceCDN === "" ? path.split("/").slice(0, -1).join("/") + "/" : tarteaucitronForceCDN,
    alreadyLaunch = alreadyLaunch === undefined ? 0 : alreadyLaunch,
    tarteaucitronForceLanguage = tarteaucitronForceLanguage === undefined ? "" : tarteaucitronForceLanguage,
    tarteaucitronForceExpire = tarteaucitronForceExpire === undefined ? "" : tarteaucitronForceExpire,
    tarteaucitronCustomText = tarteaucitronCustomText === undefined ? "" : tarteaucitronCustomText,
    timeExipre = parms.expiretime * 86400000,
    tarteaucitronProLoadServices,
    tarteaucitronNoAdBlocker = !1,
    cookiestyle = parms.noticestyle,
    cookiebackgroundcolor = parms.backgroundcolor,
    cookietextcolor = parms.textcolor,
    cookiebuttonbackground = parms.buttonbackground,
    cookiebuttontextcolor = parms.buttontextcolor,
    cookiebuttonlinkcolor = parms.buttonlinkcolor,
    cookietext = parms.cookietext,
    cookieaccepttext = parms.cookieaccepttext,
    ablehnentxt = parms.ablehnentxt,
    ablehnentext = parms.ablehnentext,
    ablehnenurl = parms.ablehnenurl,
    ablehnenanzeigen = parms.showrejectbtn,
    btn_text_customize = parms.btn_text_customize,
    expiretime = parms.expiretime,
    notice_design = parms.notice_design,
    ga_defaultoptinout = parms.ga_defaultoptinout,
    vgwort_defaultoptinout = parms.vgwort_defaultoptinout,
	koko_defaultoptinout = parms.koko_defaultoptinout,
    adminajaxurl = parms.adminajaxurl,
    usenocookies = parms.usenocookies,
    allnames = "All Cookies",
    textcansetcookies = parms.cookietextusagebefore,
    cansetcookiestext = "Cookies:",
    nocookiesaved = parms.nocookietext,
    savedcookies = parms.cookietextusage,
    nocookietext = parms.nocookietext,
    woocommercecookies = parms.woocommercecookies,
    polylangcookie = parms.polylangcookie,
    language = parms.language,
    policytext = parms.popupagbs,
    languageswitcher = parms.languageswitcher,
    maincatname = parms.maincatname,
    showpolicyname = parms.showpolicyname,
    yeslabel = parms.yeslabel,
    nolabel = parms.nolabel,
    animation_time = Number(parms.animation_time),
    pixelevent = parms.pixelevent,
    fbpixel_content_name = parms.fbpixel_content_name,
    fbpixel_product_price = parms.fbpixel_product_price,
    fbpixel_currency = parms.fbpixel_currency,
    fbpixel_content_ids = parms.fbpixel_content_ids,
    fbpixel_content_type = parms.fbpixel_content_type,
    fbpixel_product_cat = parms.fbpixel_product_cat,
    isbuyedsendet = parms.isbuyedsendet,
    pixeleventamount = parms.pixeleventamount,
    pixeleventcurrency = parms.pixeleventcurrency,
	outgoing_text = parms.outgoing_text;
	
if (notice_design == "clear") var stylegrey = "#ffffff";
else var stylegrey = "#808080";
console.log("DSGVO All in One for WordPress running...");
var tarteaucitron = {
    version: 323,
    cdn: cdn,
    user: {},
    lang: {},
    services: {},
    added: [],
    idprocessed: [],
    state: [],
    launch: [],
    parameters: {},
    isAjax: !1,
    reloadThePage: !1,
    init: function (params) {
        "use strict";
        var origOpen;
        (tarteaucitron.parameters = params),
            alreadyLaunch === 0 &&
                ((alreadyLaunch = 1),
                window.addEventListener
                    ? (window.addEventListener(
                          "load",
                          function () {
                              tarteaucitron.load(),
                                  tarteaucitron.fallback(
                                      ["tarteaucitronOpenPanel"],
                                      function (elem) {
                                          elem.addEventListener(
                                              "click",
                                              function (event) {
                                                  tarteaucitron.userInterface.openPanel(), event.preventDefault();
                                              },
                                              !1
                                          );
                                      },
                                      !0
                                  );
                          },
                          !1
                      ),
                      window.addEventListener(
                          "scroll",
                          function () {
                              var scrollPos = window.pageYOffset || document.documentElement.scrollTop,
                                  heightPosition;
                              document.getElementById("tarteaucitronAlertBig") !== null &&
                                  !tarteaucitron.highPrivacy &&
                                  document.getElementById("tarteaucitronAlertBig").style.display === "block" &&
                                  ((heightPosition = document.getElementById("tarteaucitronAlertBig").offsetHeight + "px"),
                                  scrollPos > screen.height * 2
                                      ? tarteaucitron.userInterface.respondAll(!0)
                                      : scrollPos > screen.height / 2 && (document.getElementById("tarteaucitronDisclaimerAlert").innerHTML = "<b>" + tarteaucitron.lang.alertBigScroll + "</b> " + tarteaucitron.lang.alertBig),
                                  tarteaucitron.orientation === "top" ? (document.getElementById("tarteaucitronPercentage").style.top = heightPosition) : (document.getElementById("tarteaucitronPercentage").style.bottom = heightPosition),
                                  (document.getElementById("tarteaucitronPercentage").style.width = (100 / (screen.height * 2)) * scrollPos + "%"));
                          },
                          !1
                      ),
                      window.addEventListener(
                          "keydown",
                          function (evt) {
                              evt.keyCode === 27 && tarteaucitron.userInterface.closePanel();
                          },
                          !1
                      ),
                      window.addEventListener(
                          "hashchange",
                          function () {
                              document.location.hash === tarteaucitron.hashtag && tarteaucitron.hashtag !== "" && tarteaucitron.userInterface.openPanel();
                          },
                          !1
                      ),
                      window.addEventListener(
                          "resize",
                          function () {
                              document.getElementById("tarteaucitron") !== null && document.getElementById("tarteaucitron").style.display === "block" && tarteaucitron.userInterface.jsSizing("main"),
                                  document.getElementById("tarteaucitronCookiesListContainer") !== null &&
                                      document.getElementById("tarteaucitronCookiesListContainer").style.display === "block" &&
                                      tarteaucitron.userInterface.jsSizing("cookie");
                          },
                          !1
                      ))
                    : (window.attachEvent("onload", function () {
                          tarteaucitron.load(),
                              tarteaucitron.fallback(
                                  ["tarteaucitronOpenPanel"],
                                  function (elem) {
                                      elem.attachEvent("onclick", function (event) {
                                          tarteaucitron.userInterface.openPanel(), event.preventDefault();
                                      });
                                  },
                                  !0
                              );
                      }),
                      window.attachEvent("onscroll", function () {
                          var scrollPos = window.pageYOffset || document.documentElement.scrollTop,
                              heightPosition;
                          document.getElementById("tarteaucitronAlertBig") !== null &&
                              !tarteaucitron.highPrivacy &&
                              document.getElementById("tarteaucitronAlertBig").style.display === "block" &&
                              ((heightPosition = document.getElementById("tarteaucitronAlertBig").offsetHeight + "px"),
                              scrollPos > screen.height * 2
                                  ? tarteaucitron.userInterface.respondAll(!0)
                                  : scrollPos > screen.height / 2 && (document.getElementById("tarteaucitronDisclaimerAlert").innerHTML = "<b>" + tarteaucitron.lang.alertBigScroll + "</b> " + tarteaucitron.lang.alertBig),
                              tarteaucitron.orientation === "top" ? (document.getElementById("tarteaucitronPercentage").style.top = heightPosition) : (document.getElementById("tarteaucitronPercentage").style.bottom = heightPosition),
                              (document.getElementById("tarteaucitronPercentage").style.width = (100 / (screen.height * 2)) * scrollPos + "%"));
                      }),
                      window.attachEvent("onkeydown", function (evt) {
                          evt.keyCode === 27 && tarteaucitron.userInterface.closePanel();
                      }),
                      window.attachEvent("onhashchange", function () {
                          document.location.hash === tarteaucitron.hashtag && tarteaucitron.hashtag !== "" && tarteaucitron.userInterface.openPanel();
                      }),
                      window.attachEvent("onresize", function () {
                          document.getElementById("tarteaucitron") !== null && document.getElementById("tarteaucitron").style.display === "block" && tarteaucitron.userInterface.jsSizing("main"),
                              document.getElementById("tarteaucitronCookiesListContainer") !== null && document.getElementById("tarteaucitronCookiesListContainer").style.display === "block" && tarteaucitron.userInterface.jsSizing("cookie");
                      })),
                typeof XMLHttpRequest !== "undefined" &&
                    ((origOpen = XMLHttpRequest.prototype.open),
                    (XMLHttpRequest.prototype.open = function () {
                        window.addEventListener
                            ? this.addEventListener(
                                  "load",
                                  function () {
                                      typeof tarteaucitronProLoadServices === "function" && tarteaucitronProLoadServices();
                                  },
                                  !1
                              )
                            : this.attachEvent !== void 0
                            ? this.attachEvent("onload", function () {
                                  typeof tarteaucitronProLoadServices === "function" && tarteaucitronProLoadServices();
                              })
                            : typeof tarteaucitronProLoadServices === "function" && setTimeout(tarteaucitronProLoadServices, 1000);
                        try {
                            origOpen.apply(this, arguments);
                        } catch (err) {}
                    })));
    },
    load: function () {
        "use strict";
        var cdn = tarteaucitron.cdn,
            language = tarteaucitron.getLanguage(),
            pathToLang = cdn + "lang/tarteaucitron." + language + ".js?v=" + tarteaucitron.version,
            pathToServices = cdn + "tarteaucitron.services.min.js?v=" + tarteaucitron.version,
            linkElement = document.createElement("link"),
            defaults = { adblocker: !1, hashtag: "#tarteaucitron", cookieName: "tarteaucitron", highPrivacy: !1, orientation: "top", removeCredit: !1, showAlertSmall: !0, cookieslist: !0, handleBrowserDNTRequest: !1 },
            params = tarteaucitron.parameters;
        params !== undefined && tarteaucitron.extend(defaults, params),
            (tarteaucitron.orientation = defaults.orientation),
            (tarteaucitron.hashtag = defaults.hashtag),
            (tarteaucitron.highPrivacy = defaults.highPrivacy),
            (tarteaucitron.handleBrowserDNTRequest = defaults.handleBrowserDNTRequest),
            (linkElement.rel = "stylesheet"),
            (linkElement.type = "text/css"),
            (linkElement.href = cdn + "css/tarteaucitron.css"),
            document.getElementsByTagName("head")[0].appendChild(linkElement),
            tarteaucitron.addScript(pathToLang, "", function () {
                tarteaucitronCustomText !== "" && (tarteaucitron.lang = tarteaucitron.AddOrUpdate(tarteaucitron.lang, tarteaucitronCustomText)),
                    tarteaucitron.addScript(pathToServices, "", function () {
                        var body = document.body,
                            div = document.createElement("div"),
                            html = "",
                            index,
                            orientation = "Top",
                            cat = ["essenziell", "ads", "analytic", "api", "comment", "social", "support", "video", "other"],
                            i;
                        var noticestyle = parms.noticestyle;
                        var currstatus = localStorage.getItem("dsgvoaio_respondall");
                        if (!currstatus) {
                            var currval = "false";
                            var checkedvalnow = "";
                        } else if (currstatus.indexOf("true") >= 0) {
                            var currval = "false";
                            var checkedvalnow = "";
                        } else {
                            var currval = "true";
                            var checkedvalnow = "checked";
                        }
                        for (
                            html += '<div class="dsdvo-cookie-notice ' + cookiestyle + '">',
                                html += '<div id="tarteaucitronPremium"></div>',
                                html += '<div id="tarteaucitronBack" onclick="tarteaucitron.userInterface.closePanel();"></div>',
                                html += '<div id="tarteaucitron">',
                                html += '   <div id="tarteaucitronClosePanel" onclick="tarteaucitron.userInterface.closePanel();">',
                                html += "       " + tarteaucitron.lang.close,
                                html += "   </div>",
                                html += '   <div id="tarteaucitronServices">',
                                html += '      <div class="tarteaucitronLine tarteaucitronMainLine" id="tarteaucitronMainLineOffset">',
                                html += '         <div class="tarteaucitronName">',
                                html +=
                                    "            <b><a href=\"javascript:void(0)\" onclick=\"tarteaucitron.userInterface.toggle('tarteaucitronInfo', 'tarteaucitronInfoBox');return false\">&#10011;</a> " + tarteaucitron.lang.all + "</b>",
                                html += "         </div>",
                                html += '         <div class="tarteaucitronAsk" id="tarteaucitronScrollbarAdjust">',
                                html += '            <div id="tarteaucitronAllAllowed" data-currval="' + currval + '" class="tarteaucitronAllow allswitch" onclick="tarteaucitron.userInterface.respondAll(true);">',
                                html += "               &#10003; " + tarteaucitron.lang.allow,
                                html += "            </div> ",
                                html += '            <div id="tarteaucitronAllDenied" data-currval="' + currval + '" class="tarteaucitronDeny allswitch" onclick="tarteaucitron.userInterface.respondAll(false);">',
                                html += "               &#10007; " + tarteaucitron.lang.deny,
                                html += "            </div>",
                                html += "         </div>",
                                html += "      </div>",
                                html += '      <div id="tarteaucitronInfo" class="tarteaucitronInfoBox">',
                                html += "         " + tarteaucitron.lang.disclaimer,
                                defaults.removeCredit === !1 && (html += "        <br/><br/>"),
                                html += "      </div>",
                                html += '      <div class="tarteaucitronBorder" id="tarteaucitronScrollbarParent">',
                                html += '         <div class="clear"></div>',
                                i = 0;
                            i < cat.length;
                            i += 1
                        )
                            (html += '         <div id="tarteaucitronServicesTitle_' + cat[i] + '" class="tarteaucitronHidden">'),
                                (html += '            <div class="tarteaucitronTitle">'),
                                (html +=
                                    '               <a href="#" onclick="tarteaucitron.userInterface.toggle(\'tarteaucitronDetails' + cat[i] + "', 'tarteaucitronInfoBox');return false\">&#10011;</a> " + tarteaucitron.lang[cat[i]].title),
                                (html += "            </div>"),
                                (html += '            <div id="tarteaucitronDetails' + cat[i] + '" class="tarteaucitronDetails tarteaucitronInfoBox">'),
                                (html += "               " + tarteaucitron.lang[cat[i]].details),
                                (html += "            </div>"),
                                (html += "         </div>"),
                                (html += '         <div id="tarteaucitronServices_' + cat[i] + '"></div>');
                        (html += '         <div class="tarteaucitronHidden" id="tarteaucitronScrollbarChild" style="height:20px;display:block"></div>'),
                            (html += "       </div>"),
                            (html += "   </div>"),
                            (html += "</div>"),
                            defaults.orientation === "bottom" && (orientation = "Bottom"),
                            defaults.highPrivacy
                                ? ((html += '<div id="dsgvomiddlewrap">'),
                                  (html += '<div id="tarteaucitronAlertBig" class="tarteaucitronAlertBig' + orientation + '" style="background:#' + cookiebackgroundcolor + ";color:#" + cookietextcolor + '">'),
                                  (html += '<div id="dsgvoAlertBiginner">'),
                                  (html += '<div id="tarinner">'),
                                  (html += '   <span id="tarteaucitronDisclaimerAlert">'),
                                  noticestyle == "style3"
                                      ? ((html += languageswitcher),
                                        (html += "<a href='#' onclick='tarteaucitron.userInterface.closeNotice();' ><span class='dsgvoaio dashicons dashicons-dismiss' style='font-size:18px;'></span></a>"),
                                        (html += '<div class="dsgvopopupagbs">'),
                                        (html += policytext),
                                        (html += '<div id="agbpopup_service_control"></div>'),
                                        (html += "</div>"))
                                      : noticestyle == "style2"
                                      ? ((html += languageswitcher+"<a href='#' onclick='tarteaucitron.userInterface.closeNotice();' ><span class='dsgvoaio dashicons dashicons-dismiss' style='font-size:18px;'></span></a>"), (html += "<span class='dsgvoaio_wrapnoticetext'>"+cookietext+"</span>"))
                                      : (html += cookietext),
                                  (html += "   </span>"),
                                  (html += '   <div class="dsgvonoticebtns">'),
                                  (html += '   <span id="tarteaucitronPersonalize" onclick="tarteaucitron.userInterface.respondAll(true);" style="background:#2CAA27;color:#' + cookiebuttontextcolor + '">'),
                                  (html += "&#10003; " + cookieaccepttext),
                                  (html += "   </span>"),
                                  (html += '   <span id="tarteaucitronCloseAlert" onclick="tarteaucitron.userInterface.openPanel();" style="background:#' + cookiebuttonbackground + ";color:#" + cookiebuttontextcolor + '">'),
                                  (html += "       " + btn_text_customize),
                                  (html += "   </span>"),
                                  ablehnenanzeigen == "on" &&
                                      ((html +=
                                          '   <span id="tarteaucitronCloseAlert" class="tarteaucitronCloseBtn" onclick="tarteaucitron.userInterface.respondAll(false);" style="background:#' +
                                          cookiebuttonbackground +
                                          ";color:#" +
                                          cookiebuttontextcolor +
                                          '">'),
                                      (html += "      &#10005; " + ablehnentxt),
                                      (html += "   </span>")),
                                  (html += "</div>"),
                                  (html += "</div>"),
                                  (html += "</div>"),
                                  (html += "</div>"),
                                  (html += "</div>"))
                                : ((html += '<div id="dsgvomiddlewrap">'),
                                  (html += '<div id="tarteaucitronAlertBig" class="tarteaucitronAlertBig' + orientation + '" style="background:#' + cookiebackgroundcolor + ";color:#" + cookietextcolor + '">'),
                                  (html += '<div id="dsgvoAlertBiginner">'),
                                  (html += '<div id="tarinner">'),
                                  (html += '   <span id="tarteaucitronDisclaimerAlert">'),
                                  noticestyle == "style3" && ((html += '<div class="dsgvopopupagbs">'), (html += policytext), (html += "</div>")),
                                  (html += cookietext),
                                  (html += "   </span>"),
                                  (html += '   <span id="tarteaucitronPersonalize" onclick="tarteaucitron.userInterface.respondAll(true);"  style="background:#2CAA27;color:#' + cookiebuttontextcolor + '">'),
                                  (html += "       &#10003; " + cookieaccepttext),
                                  (html += "   </span>"),
                                  (html += '   <span id="tarteaucitronCloseAlert" onclick="tarteaucitron.userInterface.openPanel();" style="background:#' + cookiebuttonbackground + ";color:#" + cookiebuttontextcolor + '">'),
                                  (html += "       " + btn_text_customize),
                                  (html += "   </span>"),
                                  ablehnenanzeigen == "on" &&
                                      ((html +=
                                          '   <span id="tarteaucitronCloseAlert" class="tarteaucitronCloseBtn" onclick="tarteaucitron.userInterface.respondAll(false);" style="background:#' +
                                          cookiebuttonbackground +
                                          ";color:#" +
                                          cookiebuttontextcolor +
                                          '">'),
                                      (html += "      &#10005; " + ablehnentxt),
                                      (html += "   </span>")),
                                  (html += "</div>"),
                                  (html += "</div>"),
                                  (html += "</div>"),
                                  (html += "</div>"),
                                  (html += '<div id="tarteaucitronPercentage"></div>')),
                            defaults.showAlertSmall === !0 &&
                                ((html += '<div id="tarteaucitronAlertSmall" class="tarteaucitronAlertSmall' + orientation + '">'),
                                (html += '   <div id="tarteaucitronManager" onclick="tarteaucitron.userInterface.openPanel();">'),
                                (html += "       " + tarteaucitron.lang.alertSmall),
                                (html += '       <div id="tarteaucitronDot">'),
                                (html += '           <span id="tarteaucitronDotGreen"></span>'),
                                (html += '           <span id="tarteaucitronDotYellow"></span>'),
                                (html += '           <span id="tarteaucitronDotRed"></span>'),
                                (html += "       </div>"),
                                defaults.cookieslist === !0
                                    ? ((html += "   </div><!-- @whitespace"),
                                      (html += '   --><div id="tarteaucitronCookiesNumber" onclick="tarteaucitron.userInterface.toggleCookiesList();">0</div>'),
                                      (html += '   <div id="tarteaucitronCookiesListContainer">'),
                                      (html += '       <div id="tarteaucitronClosePanelCookie" onclick="tarteaucitron.userInterface.closePanel();">'),
                                      (html += "           " + tarteaucitron.lang.close),
                                      (html += "       </div>"),
                                      (html += '       <div class="tarteaucitronCookiesListMain" id="tarteaucitronCookiesTitle">'),
                                      (html += '            <b id="tarteaucitronCookiesNumberBis">0 cookie</b>'),
                                      (html += "       </div>"),
                                      (html += '       <div id="tarteaucitronCookiesList"></div>'),
                                      (html += "    </div>"))
                                    : (html += "   </div>"),
                                (html += "</div>"),
                                (html += "</div>")),
                            tarteaucitron.addScript(
                                tarteaucitron.cdn + "advertising.js?v=" + tarteaucitron.version,
                                "",
                                function () {
                                    if (tarteaucitronNoAdBlocker === !0 || defaults.adblocker === !1) {
                                        if (((div.id = "tarteaucitronRoot"), body.appendChild(div, body), (div.innerHTML = html), jQuery("#dsgvo_service_control").html(html), tarteaucitron.job !== undefined))
                                            for (tarteaucitron.job = tarteaucitron.cleanArray(tarteaucitron.job), index = 0; index < tarteaucitron.job.length; index += 1) tarteaucitron.addService(tarteaucitron.job[index]);
                                        else tarteaucitron.job = [];
                                        (tarteaucitron.isAjax = !0),
                                            (tarteaucitron.job.push = function (id) {
                                                tarteaucitron.job.indexOf === void 0 &&
                                                    (tarteaucitron.job.indexOf = function (obj, start) {
                                                        var i,
                                                            j = this.length;
                                                        for (i = start || 0; i < j; i += 1) if (this[i] === obj) return i;
                                                        return -1;
                                                    }),
                                                    tarteaucitron.job.indexOf(id) === -1 && Array.prototype.push.call(this, id),
                                                    (tarteaucitron.launch[id] = !1),
                                                    tarteaucitron.addService(id);
                                            }),
                                            document.location.hash === tarteaucitron.hashtag && tarteaucitron.hashtag !== "" && tarteaucitron.userInterface.openPanel(),
                                            tarteaucitron.cookie.number(),
                                            setInterval(tarteaucitron.cookie.number, 60000);
                                    }
                                },
                                defaults.adblocker
                            ),
                            defaults.adblocker === !0 &&
                                setTimeout(function () {
                                    tarteaucitronNoAdBlocker === !1
                                        ? ((html = '<div id="dsgvomiddlewrap">'),
                                          (html += '<div id="tarteaucitronAlertBig" class="tarteaucitronAlertBig' + orientation + '" style="background:#' + cookiebackgroundcolor + ";color:#" + cookietextcolor + ';display:block;">'),
                                          (html += '<div id="tarinner">'),
                                          (html += '   <span id="tarteaucitronDisclaimerAlert">'),
                                          noticestyle == "style3" && ((html += '<div class="dsgvopopupagbs">'), (html += policytext), (html += "</div>")),
                                          (html += cookietext),
                                          (html += "   </span>"),
                                          (html += '   <span id="tarteaucitronPersonalize" onclick="location.reload();">'),
                                          (html += "       " + cookieaccepttext),
                                          (html += "   </span>"),
                                          (html += "</div>"),
                                          (html += "</div>"),
                                          (html += "</div>"),
                                          (html += '<div id="tarteaucitronPremium"></div>'),
                                          (div.id = "tarteaucitronRoot"),
                                          body.appendChild(div, body),
                                          (div.innerHTML = html),
                                          tarteaucitron.pro("!adblocker=true"))
                                        : tarteaucitron.pro("!adblocker=false");
                                }, 1500);
                    });
            }),
            jQuery(document).ready(function () {
                if (localStorage.getItem("dsgvoaio_ga_disable") == null && ga_defaultoptinout == "optout") {
                    let t = '<div id="analyticsAllowed" class="tarteaucitronAllow" name="" onclick="tarteaucitron.userInterface.respond(this, true);" style="background-color: rgb(27, 135, 11);">           \u2713 Erlauben       </div>';
                    var dummy = document.createElement("div");
                    (dummy.id = "analyticsAllowed"), (dummy.innerHTML = t.toString()), tarteaucitron.userInterface.respond(dummy.children[0], !0);
                }
                if (localStorage.getItem("dsgvoaio_vgwort_disable") == null && vgwort_defaultoptinout == "optout") {
                    let t = '<div id="vgwortAllowed" class="tarteaucitronAllow" onclick="tarteaucitron.userInterface.respond(this, true);" style="background-color: rgb(27, 135, 11);">           \u2713 Erlauben       </div>';
                    var dummy = document.createElement("div");
                    (dummy.id = "vgwortAllowed"), (dummy.innerHTML = t.toString()), tarteaucitron.userInterface.respond(dummy.children[0], !0);
                }
                if (localStorage.getItem("dsgvoaio_koko_disable") == null && koko_defaultoptinout == "optout") {
                    let t2 = '<div id="kokoAllowed" class="tarteaucitronAllow" onclick="tarteaucitron.userInterface.respond(this, true);" style="background-color: rgb(27, 135, 11);">           \u2713 Erlauben       </div>';
                    var dummy2 = document.createElement("div");
                    (dummy2.id = "kokoAllowed"), (dummy2.innerHTML = t2.toString()), tarteaucitron.userInterface.respond(dummy2.children[0], !0);
                }				
                let delete_timedate = localStorage.getItem("dsgvoaio_create");
                if (delete_timedate == null) {
                    var d = new Date(),
                        time = d.getTime(),
                        expireTime = time + timeExipre;
                    localStorage.setItem("dsgvoaio_create", expireTime);
                }
                var current_date = new Date();
                var current = current_date.getTime();
                delete_timedate !== null && delete_timedate < current && (localStorage.removeItem("dsgvoaio_create"), localStorage.removeItem("dsgvoaio"), localStorage.removeItem("dsgvoaio_respondall"));
            });
        let t = '<div id="wordpressmainAllowed" class="tarteaucitronAllow" name="" onclick="tarteaucitron.userInterface.respond(this, true);" style="background-color: rgb(27, 135, 11);">           \u2713 Erlauben       </div>';
        var dummy = document.createElement("div");
        (dummy.id = "wordpressmainAllowed"), (dummy.innerHTML = t.toString()), tarteaucitron.userInterface.respond(dummy.children[0], !0);
    },
    addService: function (serviceId) {
        "use strict";
        var html = "",
            s = tarteaucitron.services,
            service = s[serviceId],
            cookie = tarteaucitron.cookie.read(),
            hostname = document.location.hostname,
            hostRef = document.referrer.split("/")[2],
            isNavigating = hostRef === hostname ? !0 : !1,
            isAutostart = service.needConsent ? !1 : !0,
            isWaiting = cookie.indexOf(service.key + "=wait") >= 0 ? !0 : !1,
            isDenied = cookie.indexOf(service.key + "=false") >= 0 ? !0 : !1,
            isAllowed = cookie.indexOf(service.key + "=true") >= 0 ? !0 : !1,
            isResponded = cookie.indexOf(service.key + "=false") >= 0 || cookie.indexOf(service.key + "=true") >= 0 ? !0 : !1,
            isDNTRequested = navigator.doNotTrack === "1" || navigator.doNotTrack === "yes" || navigator.msDoNotTrack === "1" || window.doNotTrack === "1" ? !0 : !1;
        if (tarteaucitron.added[service.key] !== !0) {
            if (((tarteaucitron.added[service.key] = !0), isAllowed !== !0)) {
                var allowedcheck = "Allowed";
                var respondval = "true";
                var checkedval = "";
            } else {
                var allowedcheck = "Denied";
                var respondval = "false";
                var checkedval = "checked";
            }
            if (
                ((html += '<div id="' + service.key + 'Line" class="tarteaucitronLine">'),
                (html += '   <div class="tarteaucitronName">'),
                (html += "       <b>" + service.name + "</b><br/>"),
                (html += '       <span id="tacCL' + service.key + '" class="tarteaucitronListCookies"></span><br/>'),
                (html += '       <a href="' + service.uri + '" target="_blank" rel="noopener" class="dsgvoaiopollink">'),
                (html += "           " + tarteaucitron.lang.source),
                (html += "       </a>"),
                (html += "   </div>"),
                (html += '   <div class="tarteaucitronAsk">'),
                service.key != "wordpressmain")
            ) {
                if (isDenied == 0 && isAllowed == 0) var waitingclass = "dsgvoaiowaiting";
                else var waitingclass = "";
                (html +=
                    '<label class="switchdsgvoaio ' +
                    waitingclass +
                    '" style="margin:0px;"> <input type="checkbox" id="' +
                    service.key +
                    allowedcheck +
                    '" class="switchdsgvoaio-input switch_' +
                    service.key +
                    " switch_key_" +
                    service.key +
                    '" data-current="' +
                    respondval +
                    '" onclick="tarteaucitron.userInterface.respond(this, ' +
                    respondval +
                    ');" ' +
                    checkedval +
                    '> <span class="switchdsgvoaio-label" data-on="' +
                    yeslabel +
                    '" data-off="' +
                    nolabel +
                    '"></span> <span class="switchdsgvoaio-handle"></span> </label>'),
                    (html += "   </div>"),
                    (html += "</div>");
            }
            tarteaucitron.userInterface.css("tarteaucitronServicesTitle_" + service.type, "display", "block"),
                document.getElementById("tarteaucitronServices_" + service.type) !== null && (document.getElementById("tarteaucitronServices_" + service.type).innerHTML += html),
                tarteaucitron.userInterface.order(service.type);
        }
        isResponded === !1 && tarteaucitron.user.bypass === !0 && ((isAllowed = !0), tarteaucitron.cookie.create(service.key, !0)),
            (!isResponded && (isAutostart || (isNavigating && isWaiting)) && !tarteaucitron.highPrivacy) || isAllowed
                ? (isAllowed || tarteaucitron.cookie.create(service.key, !0),
                  tarteaucitron.launch[service.key] !== !0 && ((tarteaucitron.launch[service.key] = !0), service.js()),
                  (tarteaucitron.state[service.key] = !0),
                  tarteaucitron.userInterface.color(service.key, !0))
                : isDenied
                ? (typeof service.fallback === "function" && service.fallback(), (tarteaucitron.state[service.key] = !1), tarteaucitron.userInterface.color(service.key, !1))
                : !isResponded && isDNTRequested && tarteaucitron.handleBrowserDNTRequest
                ? (tarteaucitron.cookie.create(service.key, "false"), typeof service.fallback === "function" && service.fallback(), (tarteaucitron.state[service.key] = !1), tarteaucitron.userInterface.color(service.key, !1))
                : isResponded ||
                  (tarteaucitron.cookie.create(service.key, "wait"), typeof service.fallback === "function" && service.fallback(), tarteaucitron.userInterface.color(service.key, "wait"), tarteaucitron.userInterface.openAlert()),
            tarteaucitron.cookie.checkCount(service.key);
    },
    cleanArray: function cleanArray(arr) {
        "use strict";
        var i,
            len = arr.length,
            out = [],
            obj = {},
            s = tarteaucitron.services;
        for (i = 0; i < len; i += 1) obj[arr[i]] || ((obj[arr[i]] = {}), tarteaucitron.services[arr[i]] !== undefined && out.push(arr[i]));
        return (
            (out = out.sort(function (a, b) {
                return s[a].type + s[a].key > s[b].type + s[b].key ? 1 : s[a].type + s[a].key < s[b].type + s[b].key ? -1 : 0;
            })),
            out
        );
    },
    userInterface: {
        css: function (id, property, value) {
            "use strict";
            document.getElementById(id) !== null && (document.getElementById(id).style[property] = value);
        },
        hideClass: function (classname, property, value) {
            "use strict";
            var el = document.getElementsByClassName(classname);
            el !== null && jQuery(classname).hide();
        },
        writeLog: function (el, status, name, allvalue) {
            "use strict";
            function create_UUID() {
                var dt = new Date().getTime();
                var uuid = "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function (c) {
                    var r = (dt + Math.random() * 16) % 16 | 0;
                    return (dt = Math.floor(dt / 16)), (c == "x" ? r : (r & 3) | 8).toString(16);
                });
                return uuid;
            }
            var key = el;
            var state = status;
            var name = name;
            var allvalue = allvalue;
            var uniqeid = localStorage.getItem("_uniqueuid");
            uniqeid ? (uniqeid = localStorage.getItem("_uniqueuid")) : (localStorage.setItem("_uniqueuid", create_UUID()), (uniqeid = localStorage.getItem("_uniqueuid"))),
                uniqeid &&
                    jQuery.ajax({
                        type: "POST",
                        url: adminajaxurl,
                        data: { id: uniqeid.slice(0, -3) + "XXX", state: state, key: key, name: name, allvalue: allvalue, action: "dsgvoaio_write_log" },
                        success: function (result) {},
                        error: function () {},
                    });
            var uniqeid = localStorage.getItem("_uniqueuid");
        },
        respondAll: function (status) {
            "use strict";
            var s = tarteaucitron.services,
                service,
                key,
                index = 0;
            var allnames = [];
            var currentVal = jQuery("#tarteaucitronAllAllowed").data("currval");
            var currstatus = localStorage.getItem("dsgvoaio_respondall");
            for (
                currentVal || (currentVal = "false"),
                    currentVal == "false" ? jQuery("#tarteaucitronAllAllowed").data("currval", "true") : jQuery("#tarteaucitronAllAllowed").data("currval", "false"),
                    status == 1 ? localStorage.setItem("dsgvoaio_respondall", "true") : localStorage.removeItem("dsgvoaio_respondall"),
                    jQuery(".switchdsgvoaio").removeClass("dsgvoaiowaiting"),
                    index = 0;
                index < tarteaucitron.job.length;
                index += 1
            ) {
                if (((service = s[tarteaucitron.job[index]]), (key = service.key), key != "wordpressmain" && tarteaucitron.state[key] !== status)) {
                    status === !1 && tarteaucitron.launch[key] === !0 && (tarteaucitron.reloadThePage = !0),
                        tarteaucitron.launch[key] !== !0 && status === !0 && ((tarteaucitron.launch[key] = !0), tarteaucitron.services[key].js()),
                        (tarteaucitron.state[key] = status),
                        tarteaucitron.cookie.create(key, status),
                        tarteaucitron.userInterface.color(key, status);
                    var currentVal = jQuery(".switch_" + key).attr("onclick");
                    currentVal || (currentVal = "tarteaucitron.userInterface.respond(this, false);"),
                        currentVal.indexOf("true") >= 0 &&
                            localStorage.getItem("dsgvoaio_respondall") &&
                            (jQuery(".switch_" + key).prop("checked", !0), jQuery(".switch_" + key).attr("onclick", "tarteaucitron.userInterface.respond(this, false);")),
                        currentVal.indexOf("false") >= 0 &&
                            !localStorage.getItem("dsgvoaio_respondall") &&
                            (jQuery(".switch_" + key).prop("checked", !1), jQuery(".switch_" + key).attr("onclick", "tarteaucitron.userInterface.respond(this, true);"));
                }
                allnames.push(tarteaucitron.services[key].name);
            }
            if (index == 1) {
                var time = animation_time;
                jQuery("#tarteaucitronAlertBig").fadeOut(time), jQuery("#tarteaucitronAlertSmall").fadeIn(time);
            }
            var allvalue = localStorage.getItem("dsgvoaio");
            tarteaucitron.userInterface.writeLog(key, status, "All", allnames), status == 0 && (tarteaucitron.reloadThePage = !0);
        },
        hidePolicyPopup: function (key) {
            jQuery(".dsgvoaiopolicypopup_" + key).remove();
        },
        dsgvoaio_open_details: function (key, lang) {
            if (key && lang) {
                jQuery.ajax({
                    type: "POST",
                    url: adminajaxurl,
                    data: { key: key, lang: lang, action: "dsgvoaio_get_service_policy" },
                    success: function (result) {
                        jQuery(".dsgvoaiopolicypopup_" + key + " .dsgvoaio_popup_policyinner .secondinner").html(result),
                            (mainHeight = document.getElementById("tarteaucitron").offsetHeight),
                            (closeButtonHeight = document.getElementById("tarteaucitronClosePanel").offsetHeight),
                            (headerHeight = document.getElementById("tarteaucitronMainLineOffset").offsetHeight),
                            (servicesHeight = mainHeight - closeButtonHeight - headerHeight + 1),
                            jQuery(".dsgvoaio_popup_policyinner").height(servicesHeight);
                    },
                    error: function () {},
                });
                var cookie = tarteaucitron.cookie.read();
                var isServiceAllowed = cookie.indexOf(key + "=true") >= 0 ? !0 : !1;
                var isDenied = cookie.indexOf(key + "=false") >= 0 ? !0 : !1;
                var isWaiting = cookie.indexOf(key + "=wait") >= 0 ? !0 : !1;
                if (isServiceAllowed !== !0) {
                    var allowedcheck = "Allowed";
                    var respondval = "true";
                    var checkedval = "";
                } else {
                    var allowedcheck = "Denied";
                    var respondval = "false";
                    var checkedval = "checked";
                }
                if (isDenied == 0 && isServiceAllowed == 0) var waitingclass = "dsgvoaiowaiting";
                if (key != "wordpressmain")
                    var switchstring =
                        '<label class="switchdsgvoaio ' +
                        waitingclass +
                        '" style="margin:0px;"> <input type="checkbox" id="' +
                        key +
                        allowedcheck +
                        '" class="switchdsgvoaio-input switch_' +
                        key +
                        '" data-current="' +
                        respondval +
                        '" onclick="tarteaucitron.userInterface.respond(this, ' +
                        respondval +
                        ');" ' +
                        checkedval +
                        '> <span class="switchdsgvoaio-label" data-on="' +
                        yeslabel +
                        '" data-off="' +
                        nolabel +
                        '"></span> <span class="switchdsgvoaio-handle"></span> </label>';
                else var switchstring = "";
                jQuery("#tarteaucitronServices ").prepend(
                    jQuery(
                        '<div class="dsgvoaio_policypopup dsgvoaiopolicypopup_' +
                            key +
                            '" data-serviceid="' +
                            key +
                            '"><div class="dsvoaio_pol_wrap"><div class="dsgvoaio_pol_header">' +
                            switchstring +
                            '<a href="javascript:void(0)" class="dsgvo_hide_policy_popup" onclick="tarteaucitron.userInterface.hidePolicyPopup(\'' +
                            key +
                            '\');"><span class="dashicons dashicons-no-alt"></span></a></div><div class="dsgvoaio_popup_policyinner"><div class="secondinner"><div class="lds-ring-outer"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div></div></div></div></div>'
                    )
                );
            }
        },
        respond: function (el, status) {
            "use strict";
            var key = el.id.replace(new RegExp("(Eng[0-9]+|Allow|Deni)ed", "g"), "");
            jQuery("#" + key + "Line .switchdsgvoaio").removeClass("dsgvoaiowaiting"), jQuery(".dsgvoaiopolicypopup_" + key + " .switchdsgvoaio").removeClass("dsgvoaiowaiting");
            var currentVal = jQuery(".switch_" + key).attr("onclick");
            if (
                (currentVal && currentVal.indexOf("true") >= 0
                    ? (jQuery(".switch_" + key).attr("onclick", "tarteaucitron.userInterface.respond(this, false);"), jQuery(".tarteaucitronLine .switch_key_" + key).attr("checked", !0))
                    : (jQuery(".switch_" + key).attr("onclick", "tarteaucitron.userInterface.respond(this, true);"), jQuery(".tarteaucitronLine .switch_key_" + key).attr("checked", !1)),
                tarteaucitron.services[key] && tarteaucitron.userInterface.writeLog(key, status, tarteaucitron.services[key].name, ""),
                (key == "analytics" || status === !1) && localStorage.setItem("dsgvoaio_ga_disable", "true"),
                (key == "vgwort" || status === !1) && localStorage.setItem("dsgvoaio_vgwort_disable", "true"),
				(key == "kokoanalytics" || status === !1) && localStorage.setItem("dsgvoaio_koko_disable", "true"),
                tarteaucitron.state[key] === status)
            )
                return;
            status === !1 && tarteaucitron.launch[key] === !0 && (tarteaucitron.reloadThePage = !0),
                status === !0 && tarteaucitron.launch[key] !== !0 && ((tarteaucitron.launch[key] = !0), tarteaucitron.services[key] && tarteaucitron.services[key].js()),
                (tarteaucitron.state[key] = status),
                tarteaucitron.cookie.create(key, status),
                tarteaucitron.userInterface.color(key, status);
        },
        hideCookies: function (key, state) {
            "use strict";
            var key = key.id.replace("hidecookies", "");
            jQuery("#tacCL" + key).show(), jQuery("#" + key + "Line br").show(), jQuery("#" + key + "Line .tarteaucitronCookiePopup").remove();
        },
        showOutgoingMsg: function (uri) {
            "use strict";
			
			if (language == "de") {
				var to = "Weiter zu ";
			} else {
				var to = "Go to ";
			}
			
			jQuery("#dsgvoaio_olm").remove();
			jQuery(document.body).append('<div id="dsgvoaio_olm" class="dsgvoaio_modal '+notice_design+'"><div class="dsgvoaio-modal-content"> <span class="dsgvoaio-modal-close">&times;</span> <p>'+outgoing_text+'<a href="'+uri+'" target="blank" class="dsgvoaio_btn_1">'+to+uri+'</a></p> </div> </div>');
			

var modal = document.getElementById("dsgvoaio_olm");
modal.style.display = "block";
jQuery('#dsgvoaio_olm').show(animation_time);

var span = document.getElementsByClassName("dsgvoaio-modal-close")[0];


span.onclick = function() {
	modal.style.display = "none";
  jQuery('#dsgvoaio_olm').hide(animation_time);
}

window.onclick = function(event) {
  if (event.target == modal) {
	  modal.style.display = "none";
    jQuery('#dsgvoaio_olm').hide(animation_time);
  }
}        },		
        showCookies: function (key, state) {
            "use strict";
            var key = key.id.replace("showcookies", "");
            var arr = tarteaucitron.services[key].cookies,
                nb = arr.length,
                nbCurrent = 0,
                html = "",
                i,
                status = document.cookie.indexOf(key + "=true");
            status = document.cookie.indexOf(key + "=true");
            var state = localStorage.getItem("dsgvoaio");
            state.indexOf(key + "=true") >= 0 ? (status = 1) : (status = -1);
            var cookies_used = "";
            var used_cookies_text = "";
            if (status >= 0 && nb === 0) html += nocookietext;
            else if (status >= 0) {
                for (i = 0; i < nb; i += 1)
                    document.cookie.indexOf(arr[i] + "=") !== -1 &&
                        ((nbCurrent += 1),
                        (cookies_used += arr[i] + ", "),
                        tarteaucitron.cookie.owner[arr[i]] === undefined && (tarteaucitron.cookie.owner[arr[i]] = []),
                        tarteaucitron.cookie.crossIndexOf(tarteaucitron.cookie.owner[arr[i]], tarteaucitron.services[key].name) === !1 && tarteaucitron.cookie.owner[arr[i]].push(tarteaucitron.services[key].name));
                if (((cookies_used = cookies_used.replace(/,\s*$/, "")), nbCurrent > 0)) {
                    html += savedcookies + " " + nbCurrent;
                    var cookies = tarteaucitron.services[key].cookies.toString();
                    (html +=
                        "<span class='closeShowCookies' id='hidecookies" +
                        key +
                        "' onclick='tarteaucitron.userInterface.hideCookies(this, false);'><span class='dashicons dashicons-hidden'></span></span><br /><br />" +
                        textcansetcookies +
                        "<br />" +
                        cookies.replace(",", ", ")),
                        cookies_used
                            ? ((used_cookies_text = savedcookies + "&nbsp;"), (html += "<br /><br />" + used_cookies_text + "<br />" + cookies_used + ""))
                            : ((used_cookies_text = nocookiesaved), (html += "<br /><br />" + used_cookies_text)),
                        nbCurrent > 1;
                } else {
                    html += nocookiesaved;
                    var cookies = tarteaucitron.services[key].cookies.toString();
                    html +=
                        "<span class='closeShowCookies' id='hidecookies" +
                        key +
                        "' onclick='tarteaucitron.userInterface.hideCookies(this, false);'><span class='dashicons dashicons-hidden'></span></span><br /><br />" +
                        textcansetcookies +
                        "<br />" +
                        cookies.replace(",", ", ") +
                        "<br/>";
                }
            } else if (nb === 0) html += usenocookies + " <span class='closeShowCookies' id='hidecookies" + key + "' onclick='tarteaucitron.userInterface.hideCookies(this, false);'><span class='dashicons dashicons-hidden'></span></span>";
            else {
                var cookies = tarteaucitron.services[key].cookies.toString();
                (html += cansetcookiestext + " " + nb),
                    (html +=
                        "<span class='closeShowCookies' id='hidecookies" +
                        key +
                        "' onclick='tarteaucitron.userInterface.hideCookies(this, false);'><span class='dashicons dashicons-hidden'></span></span><br /><br />" +
                        textcansetcookies +
                        "<br />" +
                        cookies.replace(",", ", ") +
                        "<br />"),
                    (cookies_used = cookies_used.substring(0, cookies_used.length - 2)),
                    cookies_used
                        ? ((used_cookies_text = savedcookies + "&nbsp;"),
                          (html +=
                              "<span class='closeShowCookies' id='hidecookies" +
                              key +
                              "' onclick='tarteaucitron.userInterface.hideCookies(this, false);'><span class='dashicons dashicons-hidden'></span></span><br />" +
                              used_cookies_text +
                              "<br/>" +
                              cookies_used +
                              ""))
                        : ((used_cookies_text = nocookiesaved), (html += "<br />" + used_cookies_text + "")),
                    nb > 1;
            }
            return (
                jQuery("#tacCL" + key).hide(),
                jQuery("#" + key + "Line br").hide(),
                jQuery("<span class='tarteaucitronCookiePopup'>" + html + "<br /></p>").insertAfter("#" + key + "Line b"),
                jQuery("#" + key + "Line .tarteaucitronCookiePopup").show(),
                ""
            );
        },
        color: function (key, status) {
            "use strict";
            var gray = "#808080",
                greenDark = "#1B870B",
                greenLight = "#E6FFE2",
                redDark = "#9C1A1A",
                redLight = "#FFE2E2",
                yellowDark = "#FBDA26",
                c = "tarteaucitron",
                nbDenied = 0,
                nbPending = 0,
                nbAllowed = 0,
                sum = tarteaucitron.job.length,
                index;
            for (
                status === !0
                    ? (tarteaucitron.userInterface.css(key + "Line", "borderLeft", "5px solid " + greenDark),
                      tarteaucitron.userInterface.css(key + "Allowed", "backgroundColor", greenDark),
                      tarteaucitron.userInterface.css(key + "Denied", "backgroundColor", gray))
                    : status === !1 &&
                      (tarteaucitron.userInterface.css(key + "Line", "borderLeft", "5px solid " + redDark),
                      tarteaucitron.userInterface.css(key + "Allowed", "backgroundColor", gray),
                      tarteaucitron.userInterface.css(key + "Denied", "backgroundColor", redDark)),
                    index = 0;
                index < sum;
                index += 1
            )
                tarteaucitron.state[tarteaucitron.job[index]] === !1
                    ? (nbDenied += 1)
                    : tarteaucitron.state[tarteaucitron.job[index]] === undefined
                    ? (nbPending += 1)
                    : tarteaucitron.state[tarteaucitron.job[index]] === !0 && (nbAllowed += 1);
            tarteaucitron.userInterface.css(c + "DotGreen", "width", (100 / sum) * nbAllowed + "%"),
                tarteaucitron.userInterface.css(c + "DotYellow", "width", (100 / sum) * nbPending + "%"),
                tarteaucitron.userInterface.css(c + "DotRed", "width", (100 / sum) * nbDenied + "%"),
                nbDenied === 0 && nbPending === 0
                    ? (tarteaucitron.userInterface.css(c + "AllAllowed", "backgroundColor", greenDark), tarteaucitron.userInterface.css(c + "AllDenied", "backgroundColor", gray))
                    : nbAllowed === 1 && nbPending === 0
                    ? (tarteaucitron.userInterface.css(c + "AllAllowed", "backgroundColor", gray), tarteaucitron.userInterface.css(c + "AllDenied", "backgroundColor", redDark))
                    : (tarteaucitron.userInterface.css(c + "AllAllowed", "backgroundColor", gray), tarteaucitron.userInterface.css(c + "AllDenied", "backgroundColor", gray)),
                nbPending === 0 && tarteaucitron.userInterface.closeAlert(),
                tarteaucitron.services[key] && tarteaucitron.services[key].cookies.length > 0 && status === !1 && tarteaucitron.cookie.purge(tarteaucitron.services[key].cookies);
        },
        openPanel: function () {
            "use strict";
            var time = animation_time;
            jQuery("#tarteaucitron").fadeIn(time), jQuery("#tarteaucitronBack").fadeIn(time), jQuery("#tarteaucitronCookiesListContainer").fadeOut(time), tarteaucitron.userInterface.jsSizing("main");
        },
        closeNotice: function () {
            "use strict";
            var time = animation_time;
            jQuery("#tarteaucitronAlertBig").fadeOut(time);
        },
        redirectonreject: function () {
            "use strict";
            alert(ablehnentext), window.location.replace("http://" + ablehnenurl);
        },
        closePanel: function () {
            "use strict";
            document.location.hash === tarteaucitron.hashtag && (document.location.hash = "");
            var time = animation_time;
            jQuery("#tarteaucitron").fadeOut(time),
                jQuery("#tarteaucitronCookiesListContainer").fadeOut(time),
                tarteaucitron.fallback(
                    ["tarteaucitronInfoBox"],
                    function (elem) {
                        jQuery("#tarteaucitronInfoBox").fadeOut(time);
                    },
                    !0
                ),
                tarteaucitron.reloadThePage === !0 ? window.location.reload() : jQuery("#tarteaucitronBack").fadeOut(time);
        },
        openAlert: function () {
            "use strict";
            var time = animation_time;
            var c = "tarteaucitron";
            jQuery("#tarteaucitron" + "Percentage").fadeIn(time),
                jQuery("#tarteaucitron" + "AlertSmall").fadeOut(time),
                jQuery("#tarteaucitron" + "AlertBig").fadeIn(time),
                tarteaucitron.userInterface.css(c + "Percentage", "display", "block"),
                tarteaucitron.userInterface.css(c + "AlertSmall", "display", "none"),
                tarteaucitron.userInterface.css(c + "AlertBig", "display", "block");
        },
        closeAlert: function () {
            "use strict";
            function objectLength(obj) {
                var result = 0;
                for (var prop in obj) obj.hasOwnProperty(prop) && result++;
                return result;
            }
            var time = animation_time;
            var c = "tarteaucitron";
            objectLength(tarteaucitron.cookie.read().split("!")) == 2
                ? (tarteaucitron.userInterface.hideClass(".tarteaucitronCloseBtn", "display", "none"),
                  localStorage.getItem("dsgvoaio_respondall") == "true"
                      ? (tarteaucitron.userInterface.css(c + "Percentage", "display", "none"), tarteaucitron.userInterface.css(c + "AlertSmall", "display", "block"), tarteaucitron.userInterface.css(c + "AlertBig", "display", "none"))
                      : (tarteaucitron.userInterface.css(c + "Percentage", "display", "block"), tarteaucitron.userInterface.css(c + "AlertSmall", "display", "none"), tarteaucitron.userInterface.css(c + "AlertBig", "display", "block")))
                : (tarteaucitron.userInterface.css(c + "Percentage", "display", "none"), tarteaucitron.userInterface.css(c + "AlertSmall", "display", "block"), tarteaucitron.userInterface.css(c + "AlertBig", "display", "none")),
                tarteaucitron.userInterface.jsSizing("box");
        },
        toggleCookiesList: function () {
            "use strict";
            var div = document.getElementById("tarteaucitronCookiesListContainer");
            if (div === null) return;
            div.style.display !== "block"
                ? (tarteaucitron.cookie.number(),
                  (div.style.display = "block"),
                  tarteaucitron.userInterface.jsSizing("cookie"),
                  tarteaucitron.userInterface.css("tarteaucitron", "display", "none"),
                  tarteaucitron.userInterface.css("tarteaucitronBack", "display", "block"),
                  tarteaucitron.fallback(
                      ["tarteaucitronInfoBox"],
                      function (elem) {
                          elem.style.display = "none";
                      },
                      !0
                  ))
                : ((div.style.display = "none"), tarteaucitron.userInterface.css("tarteaucitron", "display", "none"), tarteaucitron.userInterface.css("tarteaucitronBack", "display", "none"));
        },
        toggle: function (id, closeClass) {
            "use strict";
            var div = document.getElementById(id);
            if (div === null) return;
            closeClass !== undefined &&
                tarteaucitron.fallback(
                    [closeClass],
                    function (elem) {
                        elem.id !== id && (elem.style.display = "none");
                    },
                    !0
                ),
                div.style.display !== "block" ? (div.style.display = "block") : (div.style.display = "none");
        },
        order: function (id) {
            "use strict";
            var main = document.getElementById("tarteaucitronServices_" + id),
                allDivs,
                store = [],
                i;
            if (main === null) return;
            (allDivs = main.childNodes),
                typeof Array.prototype.map === "function" &&
                    Array.prototype.map
                        .call(main.children, Object)
                        .sort(function (a, b) {
                            return tarteaucitron.services[a.id.replace(/Line/g, "")].name > tarteaucitron.services[b.id.replace(/Line/g, "")].name
                                ? 1
                                : tarteaucitron.services[a.id.replace(/Line/g, "")].name < tarteaucitron.services[b.id.replace(/Line/g, "")].name
                                ? -1
                                : 0;
                        })
                        .forEach(function (element) {
                            main.appendChild(element);
                        });
        },
        jsSizing: function (type) {
            "use strict";
            var scrollbarMarginRight = 10,
                scrollbarWidthParent,
                scrollbarWidthChild,
                servicesHeight,
                e = window,
                a = "inner",
                windowInnerHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
                mainTop,
                mainHeight,
                closeButtonHeight,
                headerHeight,
                cookiesListHeight,
                cookiesCloseHeight,
                cookiesTitleHeight,
                paddingBox,
                alertSmallHeight,
                cookiesNumberHeight;
            type === "box"
                ? document.getElementById("tarteaucitronAlertSmall") !== null &&
                  document.getElementById("tarteaucitronCookiesNumber") !== null &&
                  (tarteaucitron.userInterface.css("tarteaucitronCookiesNumber", "padding", "0px 10px"),
                  (alertSmallHeight = document.getElementById("tarteaucitronAlertSmall").offsetHeight),
                  (cookiesNumberHeight = document.getElementById("tarteaucitronCookiesNumber").offsetHeight),
                  (paddingBox = (alertSmallHeight - cookiesNumberHeight) / 2),
                  tarteaucitron.userInterface.css("tarteaucitronCookiesNumber", "padding", paddingBox + "px 10px"))
                : type === "main"
                ? (window.innerWidth === undefined && ((a = "client"), (e = document.documentElement || document.body)),
                  document.getElementById("tarteaucitron") !== null &&
                      document.getElementById("tarteaucitronClosePanel") !== null &&
                      document.getElementById("tarteaucitronMainLineOffset") !== null &&
                      (tarteaucitron.userInterface.css("tarteaucitronScrollbarParent", "height", "auto"),
                      (mainHeight = document.getElementById("tarteaucitron").offsetHeight),
                      (closeButtonHeight = document.getElementById("tarteaucitronClosePanel").offsetHeight),
                      (headerHeight = document.getElementById("tarteaucitronMainLineOffset").offsetHeight),
                      (servicesHeight = mainHeight - closeButtonHeight - headerHeight + 1),
                      tarteaucitron.userInterface.css("tarteaucitronScrollbarParent", "height", servicesHeight + "px"),
                      jQuery(".dsgvoaio_popup_policyinner").height(servicesHeight)),
                  document.getElementById("tarteaucitronScrollbarParent") !== null &&
                      document.getElementById("tarteaucitronScrollbarChild") !== null &&
                      (e[a + "Width"] <= 479 ? tarteaucitron.userInterface.css("tarteaucitronScrollbarAdjust", "marginLeft", "11px") : e[a + "Width"] <= 767 && (scrollbarMarginRight = 12),
                      (scrollbarWidthParent = document.getElementById("tarteaucitronScrollbarParent").offsetWidth),
                      (scrollbarWidthChild = document.getElementById("tarteaucitronScrollbarChild").offsetWidth),
                      tarteaucitron.userInterface.css("tarteaucitronScrollbarAdjust", "marginRight", scrollbarWidthParent - scrollbarWidthChild + scrollbarMarginRight + "px")),
                  document.getElementById("tarteaucitron") !== null &&
                      (e[a + "Width"] <= 767 ? (mainTop = 0) : (mainTop = (windowInnerHeight - document.getElementById("tarteaucitron").offsetHeight) / 2 - 21),
                      mainTop < 0 && (mainTop = 0),
                      document.getElementById("tarteaucitronMainLineOffset") !== null &&
                          document.getElementById("tarteaucitron").offsetHeight < windowInnerHeight / 2 &&
                          (mainTop -= document.getElementById("tarteaucitronMainLineOffset").offsetHeight),
                      tarteaucitron.userInterface.css("tarteaucitron", "top", mainTop + "px")))
                : type === "cookie" &&
                  (document.getElementById("tarteaucitronAlertSmall") !== null && tarteaucitron.userInterface.css("tarteaucitronCookiesListContainer", "bottom", document.getElementById("tarteaucitronAlertSmall").offsetHeight + "px"),
                  document.getElementById("tarteaucitronCookiesListContainer") !== null &&
                      (tarteaucitron.userInterface.css("tarteaucitronCookiesList", "height", "auto"),
                      (cookiesListHeight = document.getElementById("tarteaucitronCookiesListContainer").offsetHeight),
                      (cookiesCloseHeight = document.getElementById("tarteaucitronClosePanelCookie").offsetHeight),
                      (cookiesTitleHeight = document.getElementById("tarteaucitronCookiesTitle").offsetHeight),
                      tarteaucitron.userInterface.css("tarteaucitronCookiesList", "height", cookiesListHeight - cookiesCloseHeight - cookiesTitleHeight - 2 + "px")));
        },
    },
    cookie: {
        owner: {},
        create: function (key, status) {
            "use strict";
            tarteaucitronForceExpire !== "" && (timeExipre = tarteaucitronForceExpire > 365 ? 31536000000 : tarteaucitronForceExpire * 86400000);
            var d = new Date(),
                time = d.getTime(),
                expireTime = time + timeExipre,
                regex = new RegExp("!" + key + "=(wait|true|false)", "g"),
                cookie = tarteaucitron.cookie.read().replace(regex, ""),
                value = tarteaucitron.parameters.cookieName + "=" + cookie + "!" + key + "=" + status,
                domain = tarteaucitron.parameters.cookieDomain !== undefined && tarteaucitron.parameters.cookieDomain !== "" ? "domain=" + tarteaucitron.parameters.cookieDomain + ";" : "";
            tarteaucitron.cookie.read().indexOf(key + "=" + status) === -1 && tarteaucitron.pro("!" + key + "=" + status),
                localStorage.getItem("dsgvoaio_respondall") == "true"
                    ? typeof Storage !== "undefined"
                        ? (localStorage.setItem("dsgvoaio", value), localStorage.setItem("dsgvoaio_create", expireTime))
                        : console.log("Web Storage cannot be loaded...")
                    : typeof Storage !== "undefined"
                    ? (localStorage.setItem("dsgvoaio", value), localStorage.setItem("dsgvoaio_create", expireTime))
                    : console.log("Web Storage cannot be loaded..."),
                d.setTime(expireTime);
        },
        read: function () {
            "use strict";
            var nameEQ = tarteaucitron.parameters.cookieName + "=",
                ca = document.cookie.split(";"),
                i,
                c;
            return (
                localStorage.getItem("dsgvoaio_respondall") == "true" ? (c = localStorage.getItem("dsgvoaio")) : (c = localStorage.getItem("dsgvoaio")),
                (c = localStorage.getItem("dsgvoaio")),
                c ? ((c = c), c.substring(nameEQ.length, c.length)) : ""
            );
        },
        purge: function (arr) {
            "use strict";
            var i;
            for (i = 0; i < arr.length; i += 1)
                (document.cookie = arr[i] + "=; Max-Age=0"),
                    (document.cookie = arr[i] + "=; expires=Thu, 01 Jan 2000 00:00:00 GMT; path=/;"),
                    (document.cookie = arr[i] + "=; expires=Thu, 01 Jan 2000 00:00:00 GMT; path=/; domain=." + location.hostname + ";"),
                    (document.cookie = arr[i] + "=; expires=Thu, 01 Jan 2000 00:00:00 GMT; path=/; domain=." + location.hostname.split(".").slice(-2).join(".") + ";");
        },
        checkCount: function (key) {
            "use strict";
            var arr = tarteaucitron.services[key].cookies,
                nb = arr.length,
                nbCurrent = 0,
                html = "",
                i,
                status = localStorage.getItem("dsgvoaio").indexOf(key + "=true");
            var state = localStorage.getItem("dsgvoaio");
            if ((state.indexOf(key + "=true") >= 0 ? (status = 1) : (status = -1), status >= 0 && nb === 0)) html += nocookietext;
            else if (status >= 0) {
                for (i = 0; i < nb; i += 1)
                    document.cookie.indexOf(arr[i] + "=") !== -1 &&
                        ((nbCurrent += 1),
                        tarteaucitron.cookie.owner[arr[i]] === undefined && (tarteaucitron.cookie.owner[arr[i]] = []),
                        tarteaucitron.cookie.crossIndexOf(tarteaucitron.cookie.owner[arr[i]], tarteaucitron.services[key].name) === !1 && tarteaucitron.cookie.owner[arr[i]].push(tarteaucitron.services[key].name));
                nbCurrent > 0 ? ((html += savedcookies + " " + nbCurrent), nbCurrent > 1) : (html += nocookietext);
            } else nb === 0 ? (html = tarteaucitron.lang.noCookie) : ((html += cansetcookiestext + " " + nb), nb > 1);
            document.getElementById("tacCL" + key).innerHTML =
                '<a href="javascript:void(0)" onclick="tarteaucitron.userInterface.dsgvoaio_open_details(\'' +
                key +
                "', '"+
                language +"');\">" +
                showpolicyname +
                '<span class="dsgvoaioinfoicon"><span class="dashicons dashicons-visibility"></span></span></a>';
        },
        crossIndexOf: function (arr, match) {
            "use strict";
            var i;
            for (i = 0; i < arr.length; i += 1) if (arr[i] === match) return !0;
            return !1;
        },
        number: function () {
            "use strict";
            var cookies = document.cookie.split(";"),
                nb = document.cookie !== "" ? cookies.length : 0,
                html = "",
                i,
                name,
                namea,
                nameb,
                c,
                d,
                s = nb > 1 ? "s" : "",
                savedname,
                regex = /^https?\:\/\/([^\/?#]+)(?:[\/?#]|$)/i,
                regexedDomain = tarteaucitron.cdn.match(regex) !== null ? tarteaucitron.cdn.match(regex)[1] : tarteaucitron.cdn,
                host = tarteaucitron.domain !== undefined ? tarteaucitron.domain : regexedDomain;
            if (
                ((cookies = cookies.sort(function (a, b) {
                    return (
                        (namea = a.split("=", 1).toString().replace(/ /g, "")),
                        (nameb = b.split("=", 1).toString().replace(/ /g, "")),
                        (c = tarteaucitron.cookie.owner[namea] !== undefined ? tarteaucitron.cookie.owner[namea] : "0"),
                        (d = tarteaucitron.cookie.owner[nameb] !== undefined ? tarteaucitron.cookie.owner[nameb] : "0"),
                        c + a > d + b ? 1 : c + a < d + b ? -1 : 0
                    );
                })),
                document.cookie !== "")
            )
                for (i = 0; i < nb; i += 1)
                    (name = cookies[i].split("=", 1).toString().replace(/ /g, "")),
                        tarteaucitron.cookie.owner[name] !== undefined && tarteaucitron.cookie.owner[name].join(" // ") !== savedname
                            ? ((savedname = tarteaucitron.cookie.owner[name].join(" // ")),
                              (html += '<div class="tarteaucitronHidden">'),
                              (html += '     <div class="tarteaucitronTitle">'),
                              (html += "        " + tarteaucitron.cookie.owner[name].join(" // ")),
                              (html += "    </div>"),
                              (html += "</div>"))
                            : tarteaucitron.cookie.owner[name] === undefined &&
                              host !== savedname &&
                              ((savedname = host), (html += '<div class="tarteaucitronHidden">'), (html += '     <div class="tarteaucitronTitle">'), (html += "        " + host), (html += "    </div>"), (html += "</div>")),
                        (html += '<div class="tarteaucitronCookiesListMain">'),
                        (html +=
                            '    <div class="tarteaucitronCookiesListLeft"><a href="javascript:void(0)" onclick="tarteaucitron.cookie.purge([\'' +
                            cookies[i].split("=", 1) +
                            "']);tarteaucitron.cookie.number();tarteaucitron.userInterface.jsSizing('cookie');return false\"><b>&times;</b></a> <b>" +
                            name +
                            "</b>"),
                        (html += "    </div>"),
                        (html += '    <div class="tarteaucitronCookiesListRight">' + cookies[i].split("=").slice(1).join("=") + "</div>"),
                        (html += "</div>");
            else (html += '<div class="tarteaucitronCookiesListMain">'), (html += '    <div class="tarteaucitronCookiesListLeft"><b>-</b></div>'), (html += '    <div class="tarteaucitronCookiesListRight"></div>'), (html += "</div>");
            for (
                html += '<div class="tarteaucitronHidden" style="height:20px;display:block"></div>',
                    document.getElementById("tarteaucitronCookiesList") !== null && (document.getElementById("tarteaucitronCookiesList").innerHTML = html),
                    document.getElementById("tarteaucitronCookiesNumber") !== null && (document.getElementById("tarteaucitronCookiesNumber").innerHTML = nb),
                    document.getElementById("tarteaucitronCookiesNumberBis") !== null && (document.getElementById("tarteaucitronCookiesNumberBis").innerHTML = nb + " cookie" + s),
                    i = 0;
                i < tarteaucitron.job.length;
                i += 1
            )
                tarteaucitron.cookie.checkCount(tarteaucitron.job[i]);
        },
    },
    getLanguage: function () {
        "use strict";
        if (!navigator) return "en";
        var availableLanguages = "cs,en,fr,es,it,de,nl,pt,pl,ru",
            defaultLanguage = "en",
            lang = navigator.language || navigator.browserLanguage || navigator.systemLanguage || navigator.userLang || null,
            userLanguage = lang.substr(0, 2);
        return tarteaucitronForceLanguage !== "" && availableLanguages.indexOf(tarteaucitronForceLanguage) !== -1 ? tarteaucitronForceLanguage : availableLanguages.indexOf(userLanguage) === -1 ? defaultLanguage : userLanguage;
    },
    getLocale: function () {
        "use strict";
        if (!navigator) return "en_US";
        var lang = navigator.language || navigator.browserLanguage || navigator.systemLanguage || navigator.userLang || null,
            userLanguage = lang.substr(0, 2);
        return userLanguage === "fr"
            ? "fr_FR"
            : userLanguage === "en"
            ? "en_US"
            : userLanguage === "de"
            ? "de_DE"
            : userLanguage === "es"
            ? "es_ES"
            : userLanguage === "it"
            ? "it_IT"
            : userLanguage === "pt"
            ? "pt_PT"
            : userLanguage === "nl"
            ? "nl_NL"
            : "en_US";
    },
    addScript: function (url, id, callback, execute, attrName, attrVal) {
        "use strict";
        var script,
            done = !1;
        execute === !1
            ? typeof callback === "function" && callback()
            : ((script = document.createElement("script")),
              (script.type = "text/javascript"),
              (script.id = id !== undefined ? id : ""),
              (script.async = !0),
              (script.src = url),
              attrName !== undefined && attrVal !== undefined && script.setAttribute(attrName, attrVal),
              typeof callback === "function" &&
                  (script.onreadystatechange = script.onload = function () {
                      var state = script.readyState;
                      !done && (!state || /loaded|complete/.test(state)) && ((done = !0), callback());
                  }),
              document.getElementsByTagName("head")[0].appendChild(script));
    },
    makeAsync: {
        antiGhost: 0,
        buffer: "",
        init: function (url, id) {
            "use strict";
            var savedWrite = document.write,
                savedWriteln = document.writeln;
            (document.write = function (content) {
                tarteaucitron.makeAsync.buffer += content;
            }),
                (document.writeln = function (content) {
                    tarteaucitron.makeAsync.buffer += content.concat("\n");
                }),
                setTimeout(function () {
                    (document.write = savedWrite), (document.writeln = savedWriteln);
                }, 20000),
                tarteaucitron.makeAsync.getAndParse(url, id);
        },
        getAndParse: function (url, id) {
            "use strict";
            if (tarteaucitron.makeAsync.antiGhost > 9) {
                tarteaucitron.makeAsync.antiGhost = 0;
                return;
            }
            (tarteaucitron.makeAsync.antiGhost += 1),
                tarteaucitron.addScript(url, "", function () {
                    document.getElementById(id) !== null &&
                        ((document.getElementById(id).innerHTML += "<span style='display:none'>&nbsp;</span>" + tarteaucitron.makeAsync.buffer), (tarteaucitron.makeAsync.buffer = ""), tarteaucitron.makeAsync.execJS(id));
                });
        },
        execJS: function (id) {
            var i, scripts, childId, type;
            if (document.getElementById(id) === null) return;
            for (scripts = document.getElementById(id).getElementsByTagName("script"), i = 0; i < scripts.length; i += 1)
                (type = scripts[i].getAttribute("type") !== null ? scripts[i].getAttribute("type") : ""),
                    type === "" && (type = scripts[i].getAttribute("language") !== null ? scripts[i].getAttribute("language") : ""),
                    scripts[i].getAttribute("src") !== null && scripts[i].getAttribute("src") !== ""
                        ? ((childId = id + Math.floor(Math.random() * 99999999999)),
                          (document.getElementById(id).innerHTML += '<div id="' + childId + '"></div>'),
                          tarteaucitron.makeAsync.getAndParse(scripts[i].getAttribute("src"), childId))
                        : (type.indexOf("javascript") !== -1 || type === "") && eval(scripts[i].innerHTML);
        },
    },
    fallback: function (matchClass, content, noInner) {
        "use strict";
        var elems = document.getElementsByTagName("*"),
            i,
            index = 0;
        for (i in elems)
            if (elems[i] !== undefined)
                for (index = 0; index < matchClass.length; index += 1)
                    (" " + elems[i].className + " ").indexOf(" " + matchClass[index] + " ") > -1 &&
                        (typeof content === "function" ? (noInner === !0 ? content(elems[i]) : (elems[i].innerHTML = content(elems[i]))) : (elems[i].innerHTML = content));
    },
    engage: function (id) {
        "use strict";
        var html = "",
            r = Math.floor(Math.random() * 100000);
        return (
            (html += '<div class="tac_activate">'),
            (html += '   <div class="tac_float">'),
            (html += "      <b>" + tarteaucitron.services[id].name + "</b> " + tarteaucitron.lang.fallback),
            (html += '      <div class="tarteaucitronAllow" id="Eng' + r + "ed" + id + '" onclick="tarteaucitron.userInterface.respond(this, true);">'),
            (html += "          &#10003; " + tarteaucitron.lang.allow),
            (html += "       </div>"),
            (html += "   </div>"),
            (html += "</div>"),
            html
        );
    },
    extend: function (a, b) {
        "use strict";
        var prop;
        for (prop in b) b.hasOwnProperty(prop) && (a[prop] = b[prop]);
    },
    proTemp: "",
    proTimer: function () {
        "use strict";
    },
    pro: function (list) {
        "use strict";
        (tarteaucitron.proTemp += list), clearTimeout(tarteaucitron.proTimer), (tarteaucitron.proTimer = setTimeout(tarteaucitron.proPing, 2500));
    },
    proPing: function () {
        "use strict";
        if (tarteaucitron.uuid !== "" && tarteaucitron.uuid !== undefined && tarteaucitron.proTemp !== "") {
            var div = document.getElementById("tarteaucitronPremium"),
                timestamp = new Date().getTime(),
                url = "//mlfactory.de/premium.php?";
            if (div === null) return;
            (url += "domain=" + tarteaucitron.domain + "&"),
                (url += "uuid=" + tarteaucitron.uuid + "&"),
                (url += "c=" + encodeURIComponent(tarteaucitron.proTemp) + "&"),
                (url += "_" + timestamp),
                (div.innerHTML = '<img src="' + url + '" style="display:none" />'),
                (tarteaucitron.proTemp = "");
        }
        tarteaucitron.cookie.number();
    },
    AddOrUpdate: function (source, custom) {
        for (key in custom) custom[key] instanceof Object ? (source[key] = tarteaucitron.AddOrUpdate(source[key], custom[key])) : (source[key] = custom[key]);
        return source;
    },
};
jQuery(document).ready(function (event) {
    jQuery(".export_usr_datas").click(function () {
        var $this = jQuery(this);
        jQuery.ajax({ type: "POST", url: adminajaxurl, data: { action: "dsgvoaio_user_data_pdf", nonce: jQuery(".export_usr_datas").data("nonce") }, success: function (result) {}, error: function () {} });
    });
});
var tld_ = new Array();
(tld_[0] = "com"), (tld_[1] = "org"), (tld_[2] = "net"), (tld_[3] = "ws"), (tld_[4] = "info"), (tld_[10] = "co.uk"), (tld_[11] = "org.uk"), (tld_[12] = "gov.uk"), (tld_[13] = "ac.uk");
var topDom_ = 13;
var m_ = "mailto:";
var a_ = "@";
var d_ = ".";
