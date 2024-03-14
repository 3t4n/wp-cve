 	function test() {
        var e = jQuery;
    var n = jQuery('.et_pb_wpdt_image_card_carousel_0 .wpt-image-card-title');
    var r = 'foldLeft',
        i = 'once',
        o = '1000ms',
        a = '0ms',
        s = '50%',
        c = '5%',
        u = 'ease-in-out',
        l = n.parent(".et_pb_button_module_wrapper"),
        d = e("body").hasClass("edge");
    // n.is(".et_pb_section") && "roll" === r && e(et_frontend_scripts.builderCssContainerPrefix + ", " + et_frontend_scripts.builderCssLayoutPrefix).css("overflow-x", "hidden"),
    //     (function (t) {
    //         for (var n = [], r = t.get(0).attributes, i = 0; i < r.length; i++) "data-animation-" === r[i].name.substring(0, 15) && n.push(r[i].name);
    //         e.each(n, function (e, n) {
    //             t.removeAttr(n);
    //         });
    //     })(n);
    console.log(c)
    var f = isNaN(parseInt(c)) ? 0 : 0.01 * parseInt(c);
    console.log(f)

    -1 === e.inArray(u, ["linear", "ease", "ease-in", "ease-out", "ease-in-out"]) && (u = "ease-in-out"),
        l.length > 0 && (n.removeClass("et_animated"), (n = l).addClass("et_animated")),

        n.css({ "animation-duration": o, "animation-delay": a, opacity: f, "animation-timing-function": u }),

        ("slideTop" !== r && "slideBottom" !== r) || n.css("left", "0px");


    for (var p = {}, _ = isNaN(parseInt(s)) ? 50 : parseInt(s), h = ["slide", "zoom", "flip", "fold", "roll"], v = !1, g = !1, b = 0; b < h.length; b++) {
        console.log('v', v);
        console.log('g', g);
        var m = h[b];
        console.log('m', m);
        console.log('r', r);
        if (r && r.substr(0, m.length) === m) {
            (v = m), "" !== (g = r.substr(m.length, r.length)) && (g = g.toLowerCase());
            break;
        }
    }
    if (
        (!1 !== v &&
            !1 !== g &&
            (p = (function (t, e, n) {
                var r = {};
                switch (t) {
                    case "slide":
                        switch (e) {
                            case "top":
                                var i = -2 * n;
                                r = { transform: "translate3d(0, " + i + "%, 0)" };
                                break;
                            case "right":
                                var i = 2 * n;
                                r = { transform: "translate3d(" + i + "%, 0, 0)" };
                                break;
                            case "bottom":
                                var i = 2 * n;
                                r = { transform: "translate3d(0, " + i + "%, 0)" };
                                break;
                            case "left":
                                var i = -2 * n;
                                r = { transform: "translate3d(" + i + "%, 0, 0)" };
                                break;
                            default:
                                var o = 0.01 * (100 - n);
                                r = { transform: "scale3d(" + o + ", " + o + ", " + o + ")" };
                        }
                        break;
                    case "zoom":
                        var o = 0.01 * (100 - n);
                        switch (e) {
                            case "top":
                            case "right":
                            case "bottom":
                            case "left":
                            default:
                                r = { transform: "scale3d(" + o + ", " + o + ", " + o + ")" };
                        }
                        break;
                    case "flip":
                        switch (e) {
                            case "right":
                                var a = Math.ceil(0.9 * n);
                                r = { transform: "perspective(2000px) rotateY(" + a + "deg)" };
                                break;
                            case "left":
                                var a = -1 * Math.ceil(0.9 * n);
                                r = { transform: "perspective(2000px) rotateY(" + a + "deg)" };
                                break;
                            case "top":
                            default:
                                var a = Math.ceil(0.9 * n);
                                r = { transform: "perspective(2000px) rotateX(" + a + "deg)" };
                                break;
                            case "bottom":
                                var a = -1 * Math.ceil(0.9 * n);
                                r = { transform: "perspective(2000px) rotateX(" + a + "deg)" };
                        }
                        break;
                    case "fold":
                        switch (e) {
                            case "top":
                                var a = -1 * Math.ceil(0.9 * n);
                                r = { transform: "perspective(2000px) rotateX(" + a + "deg)" };
                                break;
                            case "bottom":
                                var a = Math.ceil(0.9 * n);
                                r = { transform: "perspective(2000px) rotateX(" + a + "deg)" };
                                break;
                            case "left":
                                var a = Math.ceil(0.9 * n);
                                r = { transform: "perspective(2000px) rotateY(" + a + "deg)" };
                                break;
                            case "right":
                            default:
                                var a = -1 * Math.ceil(0.9 * n);
                                r = { transform: "perspective(2000px) rotateY(" + a + "deg)" };
                        }
                        break;
                    case "roll":
                        switch (e) {
                            case "right":
                            case "bottom":
                                var a = -1 * Math.ceil(3.6 * n);
                                r = { transform: "rotateZ(" + a + "deg)" };
                                break;
                            case "top":
                            case "left":
                            default:
                                var a = Math.ceil(3.6 * n);
                                r = { transform: "rotateZ(" + a + "deg)" };
                        }
                }
                return r;
            })(v, g, _)),
        e.isEmptyObject(p) || n.css(d ? e.extend(p, { transition: "transform 0s ease-in" }) : p),
        n.addClass("et_animated"),
        n.addClass("et_is_animating"),
        n.addClass(r),
        n.addClass(i),
        !i)
    ) {
        var y = parseInt(o),
            w = parseInt(a);
        setTimeout(function () {
            Pt(n);
        }, y + w),
            d &&
                !e.isEmptyObject(p) &&
                setTimeout(function () {
                    n.css("transition", "");
                }, y + w + 50);
    }
}
