var a = [ "data-level", ".qcldichart_cov_map path", "rgba(19, 175, 240, 0.03", "</span>", "json", "load", "countries", "attr", "mouseleave", "getContext", ".qcldichart_cov_map .tooltip_nCoV", "get", "mouseenter", "confirmed", "length", "graph", "line", "removeClass", "timeline", "story", "mousemove", "active", "ready", "addClass", "offsetY", "data-recovered", "rgba(19, 175, 240, 0.6)", "#41c2f8", "each", "#ff4136", "data-cases", "keys", "$qcldicp_frontend", "addColorStop", "country", "map", "rgba(200, 200, 200, 0.05)", "css", "html", "data-confirmed", "values", '<br/><span class="val-number">', ".qcichartcovid19-graph canvas", "recovered", "data", "deaths", "index", "cases", "offsetX", "#17ac28", "data-deaths" ];

(function(b, e) {
    var f = function(g) {
        while (--g) {
            b["push"](b["shift"]());
        }
    };
    f(++e);
})(a, 274);

var b = function(c, d) {
    c = c - 0;
    var e = a[c];
    return e;
};

(function(c) {
    "use strict";
    c(document)[b("0x3")](function() {
        d[b("0x3")]();
    });
    c(window)[b("0x25")](function() {
        d["load"]();
    });
    var d = window[b("0xd")] = {
        ready: function() {
            this[b("0x10")]();
            this[b("0x2f")]();
        },
        load: function() {},
        map: function() {
            var e = covid[b("0x26")];
            for (var f = 0; f < e[b("0x2e")]; f++) {
                var g = c('.qcldichart_cov_map [title="' + e[f][b("0xf")] + '"]');
                var h = 0;
                if (g[b("0x2e")]) {
                    var j = e[f][b("0x1c")];
                    var k = e[f][b("0x1a")];
                    var l = e[f][b("0x18")];
                    if (j > 5) {
                        h = 1;
                    }
                    if (j > 100) {
                        h = 2;
                    }
                    if (j > 1e3) {
                        h = 3;
                    }
                    if (j > 1e4) {
                        h = 4;
                    }
                    if (j > 2e4) {
                        h = 5;
                    }
                    if (j > 5e4) {
                        h = 6;
                    }
                    g[b("0x27")](b("0x20"), h);
                    g[b("0x27")](b("0xb"), j);
                    g[b("0x27")](b("0x1f"), k);
                    g[b("0x27")](b("0x6"), l);
                }
            }
            var m = c(b("0x2a"));
            var n = m[b("0x27")](b("0x14"));
            var o = m[b("0x27")](b("0x1f"));
            var p = m[b("0x27")](b("0x6"));
            c(b("0x21"))[b("0x2c")](function() {
                var q = c(this)[b("0x27")](b("0xb")) ? c(this)["attr"](b("0xb")) : 0;
                var r = c(this)["attr"](b("0x1f")) ? c(this)[b("0x27")](b("0x1f")) : 0;
                var s = c(this)[b("0x27")](b("0x6")) ? c(this)[b("0x27")](b("0x6")) : 0;
                m[b("0x4")](b("0x2"));
                m[b("0x13")]('<span class="val-title">' + c(this)[b("0x27")]("title") + b("0x23") + '<br/><span class="val-number">' + n + ": " + q + b("0x23") + '<br/><span class="val-number">' + o + ": " + r + "</span>" + b("0x16") + p + ": " + s + "</span>");
            })[b("0x28")](function() {
                m[b("0x31")](b("0x2"));
            });
            c(".qcldichart_cov_map svg")["on"](b("0x1"), function(q) {
                m[b("0x12")]({
                    left: q[b("0x1d")] + 20,
                    top: q[b("0x5")]
                });
            });
        },
        graph: function() {
            c(b("0x17"))[b("0x9")](function(e, f) {
                var g = c(this)[b("0x2b")](0)[b("0x29")]("2d"), h = g["createLinearGradient"](0, 0, 0, 400);
                h[b("0xe")](0, b("0x7"));
                h[b("0xe")](1, b("0x22"));
                var i = covid[b("0x0")];
                var j = Object[b("0xc")](i[b("0x1c")]);
                var k = Object[b("0x15")](i[b("0x1c")]);
                var l = Object[b("0x15")](i[b("0x1a")]);
                var m = Object[b("0x15")](i[b("0x18")]);
                var n = c(this)[b("0x19")](b("0x2d"));
                var o = c(this)[b("0x19")](b("0x1a"));
                var p = c(this)[b("0x19")](b("0x18"));
                var q = c(this)[b("0x19")]("country");
                if (q) {
                    var r = c(this)[b("0x19")](b("0x24"));
                    k = Object[b("0x15")](r[b("0x32")]["cases"]);
                    l = Object["values"](r[b("0x32")][b("0x1a")]);
                    m = Object["values"](r["timeline"][b("0x18")]);
                    j = Object[b("0xc")](r[b("0x32")][b("0x1c")]);
                }
                new Chart(c(this), {
                    type: b("0x30"),
                    data: {
                        labels: j,
                        datasets: [ {
                            label: n,
                            borderColor: b("0x8"),
                            data: k,
                            backgroundColor: h,
                            pointRadius: 2,
                            borderWidth: 2,
                            lineTension: .1,
                            pointHoverRadius: 4
                        }, {
                            label: o,
                            borderColor: b("0xa"),
                            backgroundColor: b("0xa"),
                            data: l,
                            fill: ![],
                            lineTension: .1,
                            pointRadius: 2,
                            borderWidth: 2,
                            pointHoverRadius: 4
                        }, {
                            label: p,
                            borderColor: b("0x1e"),
                            backgroundColor: b("0x1e"),
                            data: m,
                            fill: ![],
                            lineTension: .1,
                            pointRadius: 2,
                            borderWidth: 2,
                            pointHoverRadius: 4
                        } ]
                    },
                    options: {
                        responsive: !![],
                        maintainAspectRatio: ![],
                        tooltips: {
                            position: "nearest",
                            mode: b("0x1b"),
                            intersect: ![]
                        },
                        scales: {
                            xAxes: [ {
                                gridLines: {
                                    color: b("0x11"),
                                    lineWidth: 1
                                }
                            } ],
                            yAxes: [ {
                                gridLines: {
                                    color: "rgba(200, 200, 200, 0.08)",
                                    lineWidth: 1
                                }
                            } ]
                        },
                        elements: {
                            line: {
                                tension: .4
                            }
                        }
                    }
                });
            });
        }
    };
})(jQuery);