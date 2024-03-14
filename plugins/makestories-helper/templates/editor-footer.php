<script>
    (function () {
        var e, r, s, i, a, o, t, n, c;
        r = navigator.platform.toUpperCase().indexOf("MAC") >= 0, window.macKeys = {
            cmdKey: !1,
            ctrlKey: !1,
            shiftKey: !1,
            altKey: !1,
            reset: function () {
                this.cmdKey = !1, this.ctrlKey = !1, this.shiftKey = !1, this.altKey = !1
            }
        }, r && (n = navigator.userAgent, c = n.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [], e = /trident/i.test(c[1]) ? {
            browser: "IE",
            version: (t = /\brv[ :]+(\d+)/g.exec(n) || [])[1] || ""
        } : "Chrome" === c[1] && null != (t = n.match(/\b(OPR|Edge)\/(\d+)/)) ? {
            browser: t.slice(1)[0].replace("OPR", "Opera"),
            version: t.slice(1)[1]
        } : (c = c[2] ? [c[1], c[2]] : [navigator.appName, navigator.appVersion, "-?"], null != (t = n.match(/version\/(\d+)/i)) && c.splice(1, 1, t[1]), {
            browser: c[0],
            version: c[1]
        }), s = "Chrome" === e.browser || "Safari" === e.browser, i = "Firefox" === e.browser, a = "Opera" === e.browser, window.onkeydown = function (e) {
            o = e.keyCode, (s || a) && (91 === o || 93 === o) || i && 224 === o ? macKeys.cmdKey = !0 : 16 === o ? macKeys.shiftKey = !0 : 17 === o ? macKeys.ctrlKey = !0 : 18 === o && (macKeys.altKey = !0)
        }, window.onkeyup = function (e) {
            o = e.keyCode, (s || a) && (91 === o || 93 === o) || i && 224 === o ? macKeys.cmdKey = !1 : 16 === o ? macKeys.shiftKey = !1 : 17 === o ? macKeys.ctrlKey = !1 : 18 === o && (macKeys.altKey = !1)
        }, window.onblur = function () {
            macKeys.reset()
        })
    })();
</script>