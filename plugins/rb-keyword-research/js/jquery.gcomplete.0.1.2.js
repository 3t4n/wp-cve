(function (e) {
    e.fn.extend({
        gcomplete: function (t) {
            var n = e(this);
            if (n.length == 0) {
                return
            }
            var r = n.get(0).tagName.toLowerCase();
            if (r == "input" && n.attr("type") == "text") {
                var i, s, o;
                var u = "http://clients1." + rbkeyword_google + "/complete/search";
                if (location.protocol === "https:") {
                    u = "https://clients1." + rbkeyword_google + "/complete/search"
                }
                var a = "gcomplete-";
                var f = {
                    style: "default",
                    url: u,
                    query_key: "q",
                    param: {output: "json", client: "firefox"},
                    limit: 10,
                    cycle: 500,
                    effect: false,
                    oneword: false,
                    callbackUseOnlyString: false,
                    parseFunc: function (e) {
                        return e[1]
                    }
                };

                function getCurrentBrowser() {
                    // Opera 8.0+
                    var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
                    // Firefox 1.0+
                    var isFirefox = typeof InstallTrigger !== 'undefined';
                    // Safari 3.0+ "[object HTMLElementConstructor]"
                    var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) {
                        return p.toString() === "[object SafariRemoteNotification]";
                    })(!window['safari'] || (typeof safari !== 'undefined' && window['safari'].pushNotification));
                    // Internet Explorer 6-11
                    var isIE = /*@cc_on!@*/false || !!document.documentMode;
                    // Edge 20+
                    var isEdge = !isIE && !!window.StyleMedia;
                    // Chrome 1 - 79
                    var isChrome = !!window.chrome && (!!window.chrome.webstore || !!window.chrome.runtime);

                    var browser = {};
                    if (isOpera) {
                        browser.opera = true;
                    } else if (isFirefox) {
                        browser.mozilla = true;
                    }  else if (isSafari) {
                        browser.safari = true;
                    } else if (isIE) {
                        browser.ie = true;
                    } else if (isEdge) {
                        browser.edge = true;
                    } else if (isChrome) {
                        browser.chrome = true;
                    }
                    return browser;
                }

                function l() {
                    o = setInterval(h, C("cycle"));
                    var browser = getCurrentBrowser();
                    browser.mozilla ? n.keypress(L) : n.keydown(L);
                    if (typeof n.mousewheel == "function") {
                        n.mousedown(k);
                        e(window).mousewheel(A)
                    }
                }

                function c() {
                    clearInterval(o);
                    i.param[i.query_key] = "";
                    g();
                    var browser = getCurrentBrowser();
                    browser.mozilla ? n.unbind("keypress", L) : n.unbind("keydown", L);
                    if (typeof n.mousewheel == "function") {
                        n.unbind("mousedown", k);
                        e(window).unbind("mousewheel", A)
                    }
                }

                function h() {
                    // var e = n.val();
                    // if (e.length > 3 && e.replace(/ +$/, "") != i.param[i.query_key].replace(/ +$/, "")) {
                    //     p(e)
                    // } else if (e.length <= 0) {
                    //     g()
                    // }
                }

                function p(t) {
                    var n = {};
                    i.param[i.query_key] = t;
                    for (var r in i.param) {
                        n[r] = i.param[r]
                    }
                    if (C("oneword")) {
                        n[i.query_key] = t.split(" ").pop();
                        if (!n[i.query_key]) {
                            g();
                            return false
                        }
                    }
                    if (C("callbackUseOnlyString")) {
                        var s = e("#__gcompleteaccess");
                        var o = "gcompletef" + d();
                        n["callback"] = o;
                        window[o] = v;
                        if (s.size() > 0) {
                            s.attr("src", i.url + "?" + e.param(n))
                        } else {
                            e("<script />").attr("type", "text/javascript").attr("id", "__gcompleteaccess").attr("src", i.url + "?" + e.param(n)).appendTo("body")
                        }
                    } else {
                        e.get(i.url, n, v, "jsonp")
                    }
                }

                function d() {
                    var e = [];
                    var t = "abcdefghij".split("");
                    var n = (new Date).getTime().toString().split("");
                    e.push(t[Math.floor(Math.random() * t.length)]);
                    for (var r = 0, i = n.length; r < i; r++) {
                        e.push(t[n[r]])
                    }
                    return e.join("")
                }

                function v(e) {
                    try {
                        var t = C("parseFunc")(e);
                        if (t.length > 0) {
                            m(t)
                        } else {
                            g()
                        }
                    } catch (n) {
                        c()
                    }
                }

                function m(e) {
                    jQuery("#rbkeyword_keywords").empty();
                    if (e) {
                        jQuery(".rbkeyword_keyword_status").html(jQuery("#rbkeyword_search_txt").val());
                        s.empty();
                        var t, n = C("limit");
                        for (var r = 0, i = Math.min(e.length, n); r < i; r++) {
                            jQuery("#rbkeyword_body").show();
                            jQuery("#rbkeyword_keywords").append('<label class="rbkeyword_itm "><input type="checkbox" value="' + e[r] + '">' + e[r] + "</label><br>");
                            jQuery(".rbkeyword_count").html(jQuery("label.rbkeyword_itm").length + "+")
                        }
                    }
                    if (C("effect") && s.css("display") == "none") {
                        s.fadeIn(300)
                    } else {
                        s.show()
                    }
                }

                function g() {
                    if (C("effect")) {
                        s.fadeOut(100)
                    } else {
                        s.hide()
                    }
                }

                function y(t) {
                    b(s.find("dl"));
                    e(t).addClass("over").attr("rel", "select")
                }

                function b(t) {
                    e(t).removeClass("over").removeAttr("rel")
                }

                function w() {
                    var e = s.find("dl[rel=select]");
                    if (e.size()) {
                        if (C("oneword")) {
                            var t = n.val().split(" ");
                            t.pop();
                            t.push(e.data("text"));
                            t.push("");
                            n.val(t.join(" "))
                        } else {
                            n.val(e.data("text"))
                        }
                        return true
                    }
                    return false
                }

                function E(e) {
                    i = e || {};
                    for (var t in f) {
                        if (!i.hasOwnProperty(t)) {
                            i[t] = f[t]
                        }
                    }
                    i.param[i.query_key] = "";
                    n.data("_gcomp", i)
                }

                function S() {
                    s = e("<div />").addClass(a + C("style") + "-box").css({
                        position: "absolute",
                        left: x(),
                        top: T(),
                        "z-index": "9999"
                    }).insertAfter(n);
                    s.hide()
                }

                function x() {
                    return n.position().left
                }

                function T() {
                    var e = n.position().top + n.height();
                    e += N(n.css("margin-top")) + N(n.css("padding-top")) + N(n.css("border-top-width")) + N(n.css("padding-bottom")) + N(n.css("border-bottom-width"));
                    return e
                }

                function N(e) {
                    var t = Number(e.replace("px", ""));
                    return isNaN(e) ? 1 : t
                }

                function C(e) {
                    if (i.hasOwnProperty(e)) {
                        return i[e]
                    }
                    return null
                }

                function k(t) {
                    switch (t.button) {
                        case 1:
                            if (!e.browser.msie && w()) {
                                return false
                            }
                            break;
                        case 4:
                            if (e.browser.msie && w()) {
                                return false
                            }
                            break;
                        default:
                            break
                    }
                    return true
                }

                function L(e) {
                    var t = s.find("dl[rel=select]");
                    switch (e.keyCode) {
                        case 27:
                            c();
                            break;
                        case 38:
                            y(t.prev().size() ? t.prev() : s.find("dl:last"));
                            return false;
                            break;
                        case 40:
                            y(t.next().size() ? t.next() : s.find("dl:first"));
                            return false;
                            break;
                        case 13:
                            if (w()) {
                                return false
                            }
                            break;
                        default:
                            break
                    }
                    return true
                }

                function A(e, t) {
                    if (s.find("dl").size()) {
                        var n = s.find("dl[rel=select]");
                        var r = t < 0;
                        if (n.size()) {
                            y(r ? n.next().size() ? n.next() : s.find("dl:first") : n.prev().size() ? n.prev() : s.find("dl:last"))
                        } else {
                            y(r ? s.find("dl:first") : s.find("dl:last"))
                        }
                    }
                    return false
                }

                E(t);
                S();
                n.focus(function () {
                    l()
                });
                n.blur(function () {
                    c()
                })
            }
            return n
        }
    })
})(jQuery)