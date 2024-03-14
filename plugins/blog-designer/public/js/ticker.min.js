! function(x) {
    "use strict";
    x.breakingNews = function(e, t) {
        var s = { effect: "scroll", direction: "ltr", height: 40, fontSize: "default", themeColor: "default", background: "default", borderWidth: 1, radius: 2, source: "html", play: !0, delayTimer: 4e3, scrollSpeed: 2, stopOnHover: !0, position: "auto", zIndex: 99999 },
            a = this;
        a.settings = {};

        function l() { var e; "scroll" === a.settings.effect && (e = 0, p.each(function() { e += x(this).outerWidth() }), e += 10, f.css({ width: e })) }

        function i() { "rtl" === a.settings.direction ? f.stop().animate({ marginRight: -f.find("li:first-child").outerWidth() }, 300, function() { f.find("li:first-child").insertAfter(f.find("li:last-child")), f.css({ marginRight: 0 }), w = !0 }) : f.stop().animate({ marginLeft: -f.find("li:first-child").outerWidth() }, 300, function() { f.find("li:first-child").insertAfter(f.find("li:last-child")), f.css({ marginLeft: 0 }), w = !0 }) }

        function n() { "rtl" === a.settings.direction ? (0 <= parseInt(f.css("marginRight"), 10) && (f.css({ "margin-right": -f.find("li:last-child").outerWidth() }), f.find("li:last-child").insertBefore(f.find("li:first-child"))), f.stop().animate({ marginRight: 0 }, 300, function() { w = !0 })) : (0 <= parseInt(f.css("marginLeft"), 10) && (f.css({ "margin-left": -f.find("li:last-child").outerWidth() }), f.find("li:last-child").insertBefore(f.find("li:first-child"))), f.stop().animate({ marginLeft: 0 }, 300, function() { w = !0 })) }
        var c = x(e),
            e = e,
            d = c.children(".ticker-title"),
            f = c.children(".blog-tickers").children("ul"),
            p = f.children("li"),
            r = c.children(".blog-ticker-controls"),
            g = r.find(".blog-ticker-arrow-prev").parent(),
            u = r.find(".wpos-action").parent(),
            h = r.find(".blog-ticker-arrow-next").parent(),
            m = !1,
            w = !0,
            k = f.children("li").length,
            o = 0,
            v = !1,
            y = function() {
                var e = parseFloat(f.css("marginLeft"));
                e -= a.settings.scrollSpeed / 2, f.css({ marginLeft: e }), e <= -f.find("li:first-child").outerWidth() && (f.find("li:first-child").insertAfter(f.find("li:last-child")), f.css({ marginLeft: 0 })), !1 === m && (window.requestAnimationFrame && requestAnimationFrame(y) || setTimeout(y, 16))
            },
            b = function() {
                var e = parseFloat(f.css("marginRight"));
                e -= a.settings.scrollSpeed / 2, f.css({ marginRight: e }), e <= -f.find("li:first-child").outerWidth() && (f.find("li:first-child").insertAfter(f.find("li:last-child")), f.css({ marginRight: 0 })), !1 === m && (window.requestAnimationFrame && requestAnimationFrame(b) || setTimeout(b, 16))
            },
            C = function() {
                switch (w = !0, a.settings.effect) {
                    case "typography":
                        f.find("li").hide(), f.find("li").eq(o).width(30).show(), f.find("li").eq(o).animate({ width: "100%", opacity: 1 }, 1500);
                        break;
                    case "fade":
                        f.find("li").hide(), f.find("li").eq(o).fadeIn();
                        break;
                    case "slide-down":
                        f.find("li.blog-ticker-list-active").animate({ top: 30, opacity: 0 }, 300, function() { x(this).removeClass("blog-ticker-list-active").hide() }), f.find("li").eq(o).css({ top: -30, opacity: 0 }).addClass("blog-ticker-list-active").show(), f.find("li").eq(o).animate({ top: 0, opacity: 1 }, 300);
                        break;
                    case "slide-up":
                        f.find("li.blog-ticker-list-active").animate({ top: -30, opacity: 0 }, 300, function() { x(this).removeClass("blog-ticker-list-active").hide() }), f.find("li").eq(o).css({ top: 30, opacity: 0 }).addClass("blog-ticker-list-active").show(), f.find("li").eq(o).animate({ top: 0, opacity: 1 }, 300);
                        break;
                    case "slide-left":
                        f.find("li.blog-ticker-list-active").animate({ left: "50%", opacity: 0 }, 300, function() { x(this).removeClass("blog-ticker-list-active").hide() }), f.find("li").eq(o).css({ left: -50, opacity: 0 }).addClass("blog-ticker-list-active").show(), f.find("li").eq(o).animate({ left: 0, opacity: 1 }, 300);
                        break;
                    case "slide-right":
                        f.find("li.blog-ticker-list-active").animate({ left: "-50%", opacity: 0 }, 300, function() { x(this).removeClass("blog-ticker-list-active").hide() }), f.find("li").eq(o).css({ left: "50%", opacity: 0 }).addClass("blog-ticker-list-active").show(), f.find("li").eq(o).animate({ left: 0, opacity: 1 }, 300);
                        break;
                    default:
                        f.find("li").hide(), f.find("li").eq(o).show()
                }
            },
            q = function() {
                if (m = !1, a.settings.play) switch (a.settings.effect) {
                    case "scroll":
                        ("rtl" === a.settings.direction ? b : y)();
                        break;
                    default:
                        a.pause(), v = setInterval(function() { a.next() }, a.settings.delayTimer)
                }
            };
        a.init = function() {
            if (a.settings = x.extend({}, s, t), c.addClass("blog-ticker-effect-" + a.settings.effect + " blog-ticker-direction-" + a.settings.direction), l()) {

            } else "html" === a.settings.source ? ("scroll" != a.settings.effect && C(), q()) : console.log('Please check your "source" parameter. Incorrect Value');
            var r, o;
            a.settings.play ? u.find("span").removeClass("blog-ticker-play").addClass("blog-ticker-pause") : u.find("span").removeClass("blog-ticker-pause").addClass("blog-ticker-play"), c.on("mouseleave", function(e) {
                var t = x(document.elementFromPoint(e.clientX, e.clientY)).parents(".blog-ticker-breaking-news")[0];
                x(this)[0] !== t && (!0 === a.settings.stopOnHover ? !0 === a.settings.play && a.play() : !0 === a.settings.play && !0 === m && a.play())
            }), c.on("mouseenter", function() {!0 === a.settings.stopOnHover && a.pause() }), h.on("click", function() { w && (w = !1, a.pause(), a.next()) }), g.on("click", function() { w && (w = !1, a.pause(), a.prev()) }), u.on("click", function() { w && (u.find("span").hasClass("blog-ticker-pause") ? (u.find("span").removeClass("blog-ticker-pause").addClass("blog-ticker-play"), a.stop()) : (a.settings.play = !0, u.find("span").removeClass("blog-ticker-play").addClass("blog-ticker-pause"))) }), x(window).on("resize", function() { var e; "scroll" === a.settings.effect && (e = 0, p.each(function() { e += x(this).outerWidth() }), e += 10, f.css({ width: e })) })
        }, a.pause = function() { m = !0, clearInterval(v) }, a.stop = function() { m = !0, a.settings.play = !1 }, a.play = function() { q() }, a.next = function() {
            ! function() {
                switch (a.settings.effect) {
                    case "scroll":
                        i();
                        break;
                    default:
                        k <= ++o && (o = 0), C()
                }
            }()
        }, a.prev = function() {
            ! function() {
                switch (a.settings.effect) {
                    case "scroll":
                        n();
                        break;
                    default:
                        --o < 0 && (o = k - 1), C()
                }
            }()
        }, a.init()
    }, x.fn.breakingNews = function(t) {
        return this.each(function() {
            var e;
            null == x(this).data("breakingNews") && (e = new x.breakingNews(this, t), x(this).data("breakingNews", e))
        })
    }
}(jQuery);