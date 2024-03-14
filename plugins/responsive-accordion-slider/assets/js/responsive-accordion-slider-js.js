!(function (a, b) {
    "use strict";
    b.ResponsiveAccordionSlider = {
        modules: {},
        addModule: function (a, c, f) {
            "undefined" == typeof this.modules[f] && (this.modules[f] = []), this.modules[f].push(a), "accordion" === f ? b.extend(d.prototype, c) : "panel" === f && b.extend(e.prototype, c);
        },
    };
    var c = (b.ResponsiveAccordionSlider.namespace = "ResponsiveAccordionSlider"),
        d = function (a, c) {
            (this.$accordion = b(a)),
                (this.$panelsContainer = null),
                (this.$maskContainer = null),
                (this.options = c),
                (this.settings = {}),
                (this.originalSettings = {}),
                (this.currentIndex = -1),
                (this.currentPage = 0),
                (this.totalSize = 0),
                (this.totalPanelsSize = 0),
                (this.computedOpenedPanelSize = 0),
                (this.maxComputedOpenedPanelSize = 0),
                (this.collapsedPanelSize = 0),
                (this.closedPanelSize = 0),
                (this.computedPanelDistance = 0),
                (this.panels = []),
                (this.mouseDelayTimer = 0),
                (this.openPanelAnimation = {}),
                (this.closePanelsAnimation = {}),
                (this.uniqueId = new Date().valueOf()),
                (this.breakpoints = []),
                (this.currentBreakpoint = -1),
                (this.previousVisiblePanels = -1),
                (this.isPageScrolling = !1),
                (this.positionProperty = "left"),
                (this.sizeProperty = "width"),
                (this.autoResponsiveRatio = 1),
                (this.isOverlapping = !1),
                this._init();
        };
    d.prototype = {
        _init: function () {
            var d = this;
            this.$accordion.removeClass("as-no-js"),
                (this.$maskContainer = b('<div class="as-mask"></div>').appendTo(this.$accordion)),
                (this.$panelsContainer = this.$accordion.find(".ras-panels").appendTo(this.$maskContainer)),
                0 === this.$panelsContainer.length && (this.$panelsContainer = b('<div class="ras-panels"></div>').appendTo(this.$maskContainer));
            var e = b.ResponsiveAccordionSlider.modules.accordion;
            if ("undefined" != typeof e)
                for (var f = 0; f < e.length; f++) {
                    var g = e[f] + "Defaults";
                    "undefined" != typeof this[g] ? b.extend(this.defaults, this[g]) : ((g = e[f].substring(0, 1).toLowerCase() + e[f].substring(1) + "Defaults"), "undefined" != typeof this[g] && b.extend(this.defaults, this[g]));
                }
            if (((this.settings = b.extend({}, this.defaults, this.options)), "undefined" != typeof e)) for (var h = 0; h < e.length; h++) "undefined" != typeof this["init" + e[h]] && this["init" + e[h]]();
            if (((this.originalSettings = b.extend({}, this.settings)), this.settings.shuffle === !0)) {
                var i = this.$panelsContainer.find(".ras-panel").sort(function () {
                    return 0.5 - Math.random();
                });
                this.$panelsContainer.empty().append(i);
            }
            if (
                ((this.currentIndex = this.settings.startPanel),
                -1 === this.currentIndex ? this.$accordion.addClass("as-closed") : this.$accordion.addClass("as-opened"),
                -1 !== this.settings.startPage && (this.currentPage = this.settings.startPage),
                null !== this.settings.breakpoints)
            ) {
                for (var j in this.settings.breakpoints) this.breakpoints.push({ size: parseInt(j, 10), properties: this.settings.breakpoints[j] });
                this.breakpoints = this.breakpoints.sort(function (a, b) {
                    return a.size >= b.size ? 1 : -1;
                });
            }
            if ((this._prepareRAF(), this.update(), -1 !== this.currentIndex)) {
                this.$accordion.find(".ras-panel").eq(this.currentIndex).addClass("as-opened");
                var k = { type: "panelOpen", index: this.currentIndex, previousIndex: -1 };
                this.trigger(k), b.isFunction(this.settings.panelOpen) && this.settings.panelOpen.call(this, k);
            }
            this.on("mouseenter." + c, function (a) {
                var c = { type: "accordionMouseOver" };
                d.trigger(c), b.isFunction(d.settings.accordionMouseOver) && d.settings.accordionMouseOver.call(d, c);
            }),
                this.on("mouseleave." + c, function (a) {
                    clearTimeout(d.mouseDelayTimer), d.settings.closePanelsOnMouseOut === !0 && d.closePanels();
                    var c = { type: "accordionMouseOut" };
                    d.trigger(c), b.isFunction(d.settings.accordionMouseOut) && d.settings.accordionMouseOut.call(d, c);
                }),
                b(a).on("resize." + this.uniqueId + "." + c, function () {
                    d.resize();
                }),
                this.trigger({ type: "init" }),
                b.isFunction(this.settings.init) && this.settings.init.call(this, { type: "init" });
        },
        update: function () {
            var a = this;
            if (
                ("horizontal" === this.settings.orientation
                    ? (this.$accordion.removeClass("as-vertical").addClass("as-horizontal"), (this.positionProperty = "left"), (this.sizeProperty = "width"))
                    : "vertical" === this.settings.orientation && (this.$accordion.removeClass("as-horizontal").addClass("as-vertical"), (this.positionProperty = "top"), (this.sizeProperty = "height")),
                -1 === this.settings.visiblePanels)
            )
                this.currentPage = 0;
            else if (-1 !== this.currentIndex) this.currentPage = Math.floor(this.currentIndex / this.settings.visiblePanels);
            else if (this.settings.visiblePanels !== this.previousVisiblePanels && -1 !== this.previousVisiblePanels) {
                var c = Math.round((this.currentPage * this.previousVisiblePanels) / this.settings.visiblePanels);
                this.currentPage !== c && (this.currentPage = c);
            }
            this.settings.panelDistance > 0 || this.settings.panelOverlap === !1
                ? ((this.isOverlapping = !1), this.$accordion.removeClass("as-overlap"))
                : this.settings.panelOverlap === !0 && ((this.isOverlapping = !0), this.$accordion.addClass("as-overlap")),
                this.$accordion.find("img.as-background, img.as-background-opened").css({ width: "", height: "" }),
                this._updatePanels(),
                this._updatePaginationButtons(),
                this.settings.shadow === !0 ? this.$accordion.find(".ras-panel").addClass("as-shadow") : this.settings.shadow === !1 && this.$accordion.find(".as-shadow").removeClass("as-shadow"),
                this.$panelsContainer.attr("style", ""),
                this.resize();
            var d = { type: "update" };
            a.trigger(d), b.isFunction(a.settings.update) && a.settings.update.call(a, d);
        },
        _updatePanels: function () {
            for (var a = this, d = this.panels.length - 1; d >= 0; d--)
                if (0 === this.$accordion.find('.ras-panel[data-index="' + d + '"]').length) {
                    var e = this.panels[d];
                    e.off("panelMouseOver." + c), e.off("panelMouseOut." + c), e.off("panelClick." + c), e.off("imagesComplete." + c), e.destroy(), this.panels.splice(d, 1);
                }
            this.$accordion.find(".ras-panel").each(function (c, d) {
                var e = b(d);
                "undefined" == typeof e.attr("data-init") ? a._createPanel(c, e) : (a.panels[c].setIndex(c), a.panels[c].update());
            });
        },
        _createPanel: function (a, d) {
            var f = this,
                g = b(d),
                h = new e(g, this, a);
            this.panels.splice(a, 0, h),
                h.on("panelMouseOver." + c, function (c) {
                    if (f.isPageScrolling !== !0) {
                        "hover" === f.settings.openPanelOn &&
                            (clearTimeout(f.mouseDelayTimer),
                            (f.mouseDelayTimer = setTimeout(function () {
                                f.openPanel(c.index);
                            }, f.settings.mouseDelay)));
                        var d = { type: "panelMouseOver", index: a };
                        f.trigger(d), b.isFunction(f.settings.panelMouseOver) && f.settings.panelMouseOver.call(f, d);
                    }
                }),
                h.on("panelMouseOut." + c, function (c) {
                    if (f.isPageScrolling !== !0) {
                        var d = { type: "panelMouseOut", index: a };
                        f.trigger(d), b.isFunction(f.settings.panelMouseOut) && f.settings.panelMouseOut.call(f, d);
                    }
                }),
                h.on("panelClick." + c, function (c) {
                    if (!f.$accordion.hasClass("as-swiping")) {
                        "click" === f.settings.openPanelOn && (a !== f.currentIndex ? f.openPanel(c.index) : f.closePanels());
                        var d = { type: "panelClick", index: a };
                        f.trigger(d), b.isFunction(f.settings.panelClick) && f.settings.panelClick.call(f, d);
                    }
                }),
                h.on("panelMouseDown." + c, function (c) {
                    b(this).find("a").off("click.disablePanelLink"),
                        a !== f.currentIndex &&
                            "click" === f.settings.openPanelOn &&
                            b(this)
                                .find("a")
                                .one("click.disablePanelLink", function (a) {
                                    a.preventDefault();
                                });
                }),
                h.on("imagesComplete." + c, function (a) {
                    a.index === f.currentIndex && a.contentSize !== f.computedOpenedPanelSize && f.openPanel(a.index, !0);
                });
        },
        removePanels: function () {
            b.each(this.panels, function (a, b) {
                b.off("panelMouseOver." + c), b.off("panelMouseOut." + c), b.off("panelClick." + c), b.off("imagesComplete." + c), b.destroy();
            }),
                (this.panels.length = 0);
        },
        resize: function () {
            var c = this;
            if (
                (this.$maskContainer.attr("style", ""),
                this.settings.responsive === !0
                    ? (this.$accordion.css({ width: "100%", height: this.settings.height, maxWidth: this.settings.width, maxHeight: this.settings.height }),
                      -1 === this.settings.aspectRatio && (this.settings.aspectRatio = this.settings.width / this.settings.height),
                      this.$accordion.css("height", this.$accordion.innerWidth() / this.settings.aspectRatio),
                      "auto" === this.settings.responsiveMode
                          ? ((this.autoResponsiveRatio = this.$accordion.innerWidth() / this.settings.width),
                            this.$maskContainer.css("width", this.settings.width),
                            isNaN(this.settings.height)
                                ? this.$maskContainer.css("height", Math.min(this.settings.width / this.settings.aspectRatio, (parseInt(this.settings.height, 10) / 100) * b(a).height()))
                                : this.$maskContainer.css("height", Math.min(this.settings.width / this.settings.aspectRatio, this.settings.height)),
                            this.autoResponsiveRatio < 1
                                ? this.$maskContainer.css({
                                      "-webkit-transform": "scaleX(" + this.autoResponsiveRatio + ") scaleY(" + this.autoResponsiveRatio + ")",
                                      "-ms-transform": "scaleX(" + this.autoResponsiveRatio + ") scaleY(" + this.autoResponsiveRatio + ")",
                                      transform: "scaleX(" + this.autoResponsiveRatio + ") scaleY(" + this.autoResponsiveRatio + ")",
                                      "-webkit-transform-origin": "top left",
                                      "-ms-transform-origin": "top left",
                                      "transform-origin": "top left",
                                  })
                                : this.$maskContainer.css({ "-webkit-transform": "", "-ms-transform": "", transform: "", "-webkit-transform-origin": "", "-ms-transform-origin": "", "transform-origin": "" }),
                            (this.totalSize = "horizontal" === this.settings.orientation ? this.$maskContainer.innerWidth() : this.$maskContainer.innerHeight()))
                          : (this.totalSize = "horizontal" === this.settings.orientation ? this.$accordion.innerWidth() : this.$accordion.innerHeight()))
                    : (this.$accordion.css({ width: this.settings.width, height: this.settings.height, maxWidth: "", maxHeight: "" }),
                      (this.totalSize = "horizontal" === this.settings.orientation ? this.$accordion.innerWidth() : this.$accordion.innerHeight())),
                "horizontal" === this.settings.orientation
                    ? this.$accordion.find("img.as-background, img.as-background-opened").css("height", this.$panelsContainer.innerHeight())
                    : this.$accordion.find("img.as-background, img.as-background-opened").css("width", this.$panelsContainer.innerWidth()),
                (this.computedPanelDistance = this.settings.panelDistance),
                "string" == typeof this.computedPanelDistance &&
                    (-1 !== this.computedPanelDistance.indexOf("%")
                        ? (this.computedPanelDistance = this.totalSize * (parseInt(this.computedPanelDistance, 10) / 100))
                        : -1 !== this.computedPanelDistance.indexOf("px") && (this.computedPanelDistance = parseInt(this.computedPanelDistance, 10))),
                (this.closedPanelSize = (this.totalSize - (this.getVisiblePanels() - 1) * this.computedPanelDistance) / this.getVisiblePanels()),
                (this.closedPanelSize = Math.floor(this.closedPanelSize)),
                (this.computedOpenedPanelSize = this.settings.openedPanelSize),
                "max" === this.settings.openedPanelSize &&
                    ((this.maxComputedOpenedPanelSize = this.settings.maxOpenedPanelSize),
                    "string" == typeof this.maxComputedOpenedPanelSize &&
                        (-1 !== this.maxComputedOpenedPanelSize.indexOf("%")
                            ? (this.maxComputedOpenedPanelSize = this.totalSize * (parseInt(this.maxComputedOpenedPanelSize, 10) / 100))
                            : -1 !== this.maxComputedOpenedPanelSize.indexOf("px") && (this.maxComputedOpenedPanelSize = parseInt(this.maxComputedOpenedPanelSize, 10)))),
                "string" == typeof this.computedOpenedPanelSize)
            )
                if (-1 !== this.computedOpenedPanelSize.indexOf("%")) this.computedOpenedPanelSize = this.totalSize * (parseInt(this.computedOpenedPanelSize, 10) / 100);
                else if (-1 !== this.computedOpenedPanelSize.indexOf("px")) this.computedOpenedPanelSize = parseInt(this.computedOpenedPanelSize, 10);
                else if ("max" === this.computedOpenedPanelSize && -1 !== this.currentIndex) {
                    var d = this.getPanelAt(this.currentIndex).getContentSize();
                    this.computedOpenedPanelSize = "loading" === d ? this.closedPanelSize : Math.min(d, this.maxComputedOpenedPanelSize);
                }
            if (
                ((this.collapsedPanelSize = (this.totalSize - this.computedOpenedPanelSize - (this.getVisiblePanels() - 1) * this.computedPanelDistance) / (this.getVisiblePanels() - 1)),
                (this.computedOpenedPanelSize = Math.floor(this.computedOpenedPanelSize)),
                (this.collapsedPanelSize = Math.floor(this.collapsedPanelSize)),
                (this.totalPanelsSize = this.closedPanelSize * this.getTotalPanels() + this.computedPanelDistance * (this.getTotalPanels() - 1)),
                this.$panelsContainer.css(this.sizeProperty, this.totalPanelsSize),
                (this.totalSize = this.closedPanelSize * this.getVisiblePanels() + this.computedPanelDistance * (this.getVisiblePanels() - 1)),
                "custom" === this.settings.responsiveMode || this.settings.responsive === !1
                    ? this.$accordion.css(this.sizeProperty, this.totalSize)
                    : (this.$accordion.css(this.sizeProperty, this.totalSize * this.autoResponsiveRatio), this.$maskContainer.css(this.sizeProperty, this.totalSize)),
                -1 !== this.settings.visiblePanels)
            ) {
                var e = {},
                    f = -(this.totalSize + this.computedPanelDistance) * this.currentPage;
                this.currentPage === this.getTotalPages() - 1 && (f = -(this.closedPanelSize * this.getTotalPanels() + this.computedPanelDistance * (this.getTotalPanels() - 1) - this.totalSize)),
                    (e[this.positionProperty] = f),
                    this.$panelsContainer.css(e);
            }
            var g = this.currentPage === this.getTotalPages() - 1 && this.getTotalPanels() % this.settings.visiblePanels !== 0 ? this.settings.visiblePanels - (this.getTotalPanels() % this.settings.visiblePanels) : 0;
            if (
                (b.each(this.panels, function (a, b) {
                    var d;
                    if (
                        (-1 === c.currentIndex
                            ? (d = a * (c.closedPanelSize + c.computedPanelDistance))
                            : -1 === c.settings.visiblePanels
                            ? (d = a * (c.collapsedPanelSize + c.computedPanelDistance) + (a > c.currentIndex ? c.computedOpenedPanelSize - c.collapsedPanelSize : 0))
                            : c._getPageOfPanel(a) === c.currentPage
                            ? ((d =
                                  c.currentPage * (c.totalSize + c.computedPanelDistance) +
                                  (a + g - c.currentPage * c.settings.visiblePanels) * (c.collapsedPanelSize + c.computedPanelDistance) +
                                  (a > c.currentIndex ? c.computedOpenedPanelSize - c.collapsedPanelSize : 0)),
                              c.currentPage === c.getTotalPages() - 1 && 0 !== g && (d -= (c.getTotalPages() - c.getTotalPanels() / c.settings.visiblePanels) * (c.totalSize + c.computedPanelDistance)))
                            : (d = a * (c.closedPanelSize + c.computedPanelDistance)),
                        b.setPosition(d),
                        c.isOverlapping === !1)
                    ) {
                        var e = -1 === c.currentIndex || (-1 !== c.settings.visiblePanels && c._getPageOfPanel(a) !== c.currentPage) ? c.closedPanelSize : a === c.currentIndex ? c.computedOpenedPanelSize : c.collapsedPanelSize;
                        b.setSize(e);
                    }
                }),
                null !== this.settings.breakpoints && this.breakpoints.length > 0)
            )
                if (b(a).width() > this.breakpoints[this.breakpoints.length - 1].size && -1 !== this.currentBreakpoint) (this.currentBreakpoint = -1), this._setProperties(this.originalSettings, !1);
                else
                    for (var h = 0, i = this.breakpoints.length; i > h; h++)
                        if (b(a).width() <= this.breakpoints[h].size) {
                            if (this.currentBreakpoint !== this.breakpoints[h].size) {
                                var j = { type: "breakpointReach", size: this.breakpoints[h].size, settings: this.breakpoints[h].properties };
                                c.trigger(j), b.isFunction(c.settings.breakpointReach) && c.settings.breakpointReach.call(c, j), (this.currentBreakpoint = this.breakpoints[h].size);
                                var k = b.extend({}, this.originalSettings, this.breakpoints[h].properties);
                                this._setProperties(k, !1);
                            }
                            break;
                        }
        },
        _setProperties: function (a, b) {
            for (var c in a) "visiblePanels" === c && -1 !== this.settings.visiblePanels && (this.previousVisiblePanels = this.settings.visiblePanels), (this.settings[c] = a[c]), b !== !1 && (this.originalSettings[c] = a[c]);
            this.update();
        },
        destroy: function () {
            this.$accordion.removeData("ResponsiveAccordionSlider"),
                this.$accordion.attr("style", ""),
                this.$panelsContainer.attr("style", ""),
                this.off("mouseenter." + c),
                this.off("mouseleave." + c),
                b(a).off("resize." + this.uniqueId + "." + c),
                this._stopPanelsAnimation(this.openPanelAnimation),
                this._stopPanelsAnimation(this.closePanelsAnimation);
            var d = b.ResponsiveAccordionSlider.modules.accordion;
            if ("undefined" != typeof d) for (var e = 0; e < d.length; e++) "undefined" != typeof this["destroy" + d[e]] && this["destroy" + d[e]]();
            this.removePanels(), this.$panelsContainer.appendTo(this.$accordion), this.$maskContainer.remove(), this.$accordion.find(".as-pagination-buttons").remove();
        },
        on: function (a, b) {
            return this.$accordion.on(a, b);
        },
        off: function (a) {
            return this.$accordion.off(a);
        },
        trigger: function (a) {
            return this.$accordion.triggerHandler(a);
        },
        getPanelAt: function (a) {
            return this.panels[a];
        },
        getCurrentIndex: function () {
            return this.currentIndex;
        },
        getTotalPanels: function () {
            return this.panels.length;
        },
        nextPanel: function () {
            var a = this.currentIndex >= this.getTotalPanels() - 1 ? 0 : this.currentIndex + 1;
            this.openPanel(a);
        },
        previousPanel: function () {
            var a = this.currentIndex <= 0 ? this.getTotalPanels() - 1 : this.currentIndex - 1;
            this.openPanel(a);
        },
        _animatePanels: function (b, c) {
            function d() {
                1 > f
                    ? ((f = (new Date().valueOf() - e) / c.duration), f > 1 && (f = 1), (f = 0.5 - Math.cos(f * Math.PI) / 2), c.step(f), (b.timer = a.requestAnimationFrame(d)))
                    : (c.complete(), (b.isRunning = !1), a.cancelAnimationFrame(b.timer));
            }
            var e = new Date().valueOf(),
                f = 0;
            (b.isRunning = !0), (b.timer = a.requestAnimationFrame(d));
        },
        _stopPanelsAnimation: function (b) {
            "undefined" != typeof b.isRunning && b.isRunning === !0 && ((b.isRunning = !1), a.cancelAnimationFrame(b.timer));
        },
        _prepareRAF: function () {
            if ("undefined" == typeof a.requestAnimationFrame)
                for (var b = ["webkit", "moz"], c = 0; c < b.length; c++)
                    (a.requestAnimationFrame = a[b[c] + "RequestAnimationFrame"]), (a.cancelAnimationFrame = a.cancelAnimationFrame || a[b[c] + "CancelAnimationFrame"] || a[b[c] + "CancelRequestAnimationFrame"]);
            if ("undefined" == typeof a.requestAnimationFrame) {
                var d = 0;
                (a.requestAnimationFrame = function (b, c) {
                    var e = new Date().valueOf(),
                        f = Math.max(0, 16 - (e - d)),
                        g = a.setTimeout(function () {
                            b(e + f);
                        }, f);
                    return (d = e + f), g;
                }),
                    (a.cancelAnimationFrame = function (a) {
                        clearTimeout(a);
                    });
            }
        },
        openPanel: function (a, c) {
            if (a !== this.currentIndex || c === !0) {
                this.$accordion.hasClass("as-opened") === !1 && (this.$accordion.removeClass("as-closed"), this.$accordion.addClass("as-opened"));
                var d = this.currentIndex;
                if (((this.currentIndex = a), -1 !== this.settings.visiblePanels && !(this.currentPage === this.getTotalPages() - 1 && a >= this.getTotalPanels() - this.settings.visiblePanels))) {
                    var e = Math.floor(this.currentIndex / this.settings.visiblePanels);
                    e !== this.currentPage && this.gotoPage(e), (this.currentIndex = a);
                }
                var f = this,
                    g = [],
                    h = [],
                    i = [],
                    j = [],
                    k = [],
                    l = this._getFirstPanelFromPage(),
                    m = this._getLastPanelFromPage(),
                    n = 0;
                if ((this.$accordion.find(".ras-panel.as-opened").removeClass("as-opened"), this.$accordion.find(".ras-panel").eq(this.currentIndex).addClass("as-opened"), "max" === this.settings.openedPanelSize)) {
                    var o = this.getPanelAt(this.currentIndex).getContentSize();
                    (this.computedOpenedPanelSize = "loading" === o ? this.closedPanelSize : Math.min(o, this.maxComputedOpenedPanelSize)),
                        (this.collapsedPanelSize = (this.totalSize - this.computedOpenedPanelSize - (this.getVisiblePanels() - 1) * this.computedPanelDistance) / (this.getVisiblePanels() - 1));
                }
                for (var p = l; m >= p; p++) {
                    var q = this.getPanelAt(p);
                    (j[p] = q.getPosition()),
                        (h[p] =
                            this.currentPage * (this.totalSize + this.computedPanelDistance) +
                            n * (this.collapsedPanelSize + this.computedPanelDistance) +
                            (p > this.currentIndex ? this.computedOpenedPanelSize - this.collapsedPanelSize : 0)),
                        -1 !== this.settings.visiblePanels &&
                            this.currentPage === this.getTotalPages() - 1 &&
                            (h[p] -= (this.getTotalPages() - this.getTotalPanels() / this.settings.visiblePanels) * (this.totalSize + this.computedPanelDistance)),
                        h[p] !== j[p] && k.push(p),
                        this.isOverlapping === !1 && ((i[p] = q.getSize()), (g[p] = p === this.currentIndex ? this.computedOpenedPanelSize : this.collapsedPanelSize), g[p] !== i[p] && -1 === b.inArray(p, k) && k.push(p)),
                        n++;
                }
                var r = k.length;
                this.closePanelsAnimation.page === this.currentPage && this._stopPanelsAnimation(this.closePanelsAnimation),
                    this._stopPanelsAnimation(this.openPanelAnimation),
                    (this.openPanelAnimation.page = this.currentPage),
                    this._animatePanels(this.openPanelAnimation, {
                        duration: this.settings.openPanelDuration,
                        step: function (a) {
                            for (var b = 0; r > b; b++) {
                                var c = k[b],
                                    d = f.getPanelAt(c);
                                d.setPosition(a * (h[c] - j[c]) + j[c]), f.isOverlapping === !1 && d.setSize(a * (g[c] - i[c]) + i[c]);
                            }
                        },
                        complete: function () {
                            var a = { type: "panelOpenComplete", index: f.currentIndex };
                            f.trigger(a), b.isFunction(f.settings.panelOpenComplete) && f.settings.panelOpenComplete.call(f, a);
                        },
                    });
                var s = { type: "panelOpen", index: a, previousIndex: d };
                this.trigger(s), b.isFunction(this.settings.panelOpen) && this.settings.panelOpen.call(this, s);
            }
        },
        closePanels: function () {
            var a = this.currentIndex;
            (this.currentIndex = -1),
                this.$accordion.hasClass("as-closed") === !1 && (this.$accordion.removeClass("as-opened"), this.$accordion.addClass("as-closed")),
                this.$accordion.find(".ras-panel.as-opened").removeClass("as-opened"),
                clearTimeout(this.mouseDelayTimer);
            for (var c = this, d = [], e = [], f = [], g = [], h = this._getFirstPanelFromPage(), i = this._getLastPanelFromPage(), j = 0, k = h; i >= k; k++) {
                var l = this.getPanelAt(k);
                (g[k] = l.getPosition()),
                    (e[k] = this.currentPage * (this.totalSize + this.computedPanelDistance) + j * (this.closedPanelSize + this.computedPanelDistance)),
                    -1 !== this.settings.visiblePanels &&
                        this.currentPage === this.getTotalPages() - 1 &&
                        (e[k] -= (this.getTotalPages() - this.getTotalPanels() / this.settings.visiblePanels) * (this.totalSize + this.computedPanelDistance)),
                    this.isOverlapping === !1 && ((f[k] = l.getSize()), (d[k] = this.closedPanelSize)),
                    j++;
            }
            this.openPanelAnimation.page === this.currentPage && this._stopPanelsAnimation(this.openPanelAnimation),
                this._stopPanelsAnimation(this.closePanelsAnimation),
                (this.closePanelsAnimation.page = this.currentPage),
                this._animatePanels(this.closePanelsAnimation, {
                    duration: this.settings.closePanelDuration,
                    step: function (a) {
                        for (var b = h; i >= b; b++) {
                            var j = c.getPanelAt(b);
                            j.setPosition(a * (e[b] - g[b]) + g[b]), c.isOverlapping === !1 && j.setSize(a * (d[b] - f[b]) + f[b]);
                        }
                    },
                    complete: function () {
                        var d = { type: "panelsCloseComplete", previousIndex: a };
                        c.trigger(d), b.isFunction(c.settings.panelsCloseComplete) && c.settings.panelsCloseComplete.call(c, d);
                    },
                });
            var m = { type: "panelsClose", previousIndex: a };
            this.trigger(m), b.isFunction(this.settings.panelsClose) && this.settings.panelsClose.call(this, m);
        },
        getVisiblePanels: function () {
            return -1 === this.settings.visiblePanels ? this.getTotalPanels() : this.settings.visiblePanels;
        },
        getTotalPages: function () {
            return -1 === this.settings.visiblePanels ? 1 : Math.ceil(this.getTotalPanels() / this.settings.visiblePanels);
        },
        getCurrentPage: function () {
            return -1 === this.settings.visiblePanels ? 0 : this.currentPage;
        },
        gotoPage: function (a) {
            -1 !== this.currentIndex && this.closePanels(), (this.currentPage = a), (this.isPageScrolling = !0);
            var c = this,
                d = {},
                e = -(a * this.totalSize + this.currentPage * this.computedPanelDistance);
            this.currentPage === this.getTotalPages() - 1 && (e = -(this.totalPanelsSize - this.totalSize)), (d[this.positionProperty] = e);
            var f = { type: "pageScroll", index: this.currentPage };
            this.trigger(f),
                b.isFunction(this.settings.pageScroll) && this.settings.pageScroll.call(this, f),
                this.$panelsContainer.stop().animate(d, this.settings.pageScrollDuration, this.settings.pageScrollEasing, function () {
                    c.isPageScrolling = !1;
                    var a = { type: "pageScrollComplete", index: c.currentPage };
                    c.trigger(a), b.isFunction(c.settings.pageScrollComplete) && c.settings.pageScrollComplete.call(c, a);
                });
        },
        nextPage: function () {
            var a = this.currentPage >= this.getTotalPages() - 1 ? 0 : this.currentPage + 1;
            this.gotoPage(a);
        },
        previousPage: function () {
            var a = this.currentPage <= 0 ? this.getTotalPages() - 1 : this.currentPage - 1;
            this.gotoPage(a);
        },
        _getFirstPanelFromPage: function () {
            return -1 === this.settings.visiblePanels ? 0 : this.currentPage === this.getTotalPages() - 1 && 0 !== this.currentPage ? this.getTotalPanels() - this.settings.visiblePanels : this.currentPage * this.settings.visiblePanels;
        },
        _getLastPanelFromPage: function () {
            return -1 === this.settings.visiblePanels ? this.getTotalPanels() - 1 : this.currentPage === this.getTotalPages() - 1 ? this.getTotalPanels() - 1 : (this.currentPage + 1) * this.settings.visiblePanels - 1;
        },
        _getPageOfPanel: function (a) {
            return this.currentPage === this.getTotalPages() - 1 && a >= this.getTotalPanels() - this.settings.visiblePanels ? this.getTotalPages() - 1 : Math.floor(a / this.settings.visiblePanels);
        },
        _updatePaginationButtons: function () {
            var a = this.$accordion.find(".as-pagination-buttons"),
                d = this,
                e = this.getTotalPages();
            if (1 >= e && 0 !== a.length) a.remove(), a.off("click." + c, ".as-pagination-button"), this.off("pageScroll." + c), this.$accordion.removeClass("as-has-buttons");
            else if (e > 1 && 0 === a.length) {
                a = b('<div class="as-pagination-buttons"></div>').appendTo(this.$accordion);
                for (var f = 0; f < this.getTotalPages(); f++) b('<div class="as-pagination-button"></div>').appendTo(a);
                a.on("click." + c, ".as-pagination-button", function () {
                    d.gotoPage(b(this).index());
                }),
                    a.find(".as-pagination-button").eq(this.currentPage).addClass("as-selected"),
                    this.on("pageScroll." + c, function (b) {
                        a.find(".as-selected").removeClass("as-selected"), a.find(".as-pagination-button").eq(b.index).addClass("as-selected");
                    }),
                    this.$accordion.addClass("as-has-buttons");
            } else if (e > 1 && 0 !== a.length) {
                a.empty();
                for (var g = 0; g < this.getTotalPages(); g++) b('<div class="as-pagination-button"></div>').appendTo(a);
                a.find(".as-selected").removeClass("as-selected"), a.find(".as-pagination-button").eq(this.currentPage).addClass("as-selected");
            }
        },
        defaults: {
            width: 800,
            height: 400,
            responsive: !0,
            responsiveMode: "auto",
            aspectRatio: -1,
            orientation: "horizontal",
            startPanel: -1,
            openedPanelSize: "max",
            maxOpenedPanelSize: "80%",
            openPanelOn: "hover",
            closePanelsOnMouseOut: !0,
            mouseDelay: 200,
            panelDistance: 0,
            openPanelDuration: 700,
            closePanelDuration: 700,
            pageScrollDuration: 500,
            pageScrollEasing: "swing",
            breakpoints: null,
            visiblePanels: -1,
            startPage: 0,
            shadow: !0,
            shuffle: !1,
            panelOverlap: !0,
            init: function () {},
            update: function () {},
            accordionMouseOver: function () {},
            accordionMouseOut: function () {},
            panelClick: function () {},
            panelMouseOver: function () {},
            panelMouseOut: function () {},
            panelOpen: function () {},
            panelsClose: function () {},
            pageScroll: function () {},
            panelOpenComplete: function () {},
            panelsCloseComplete: function () {},
            pageScrollComplete: function () {},
            breakpointReach: function () {},
        },
    };
    var e = function (a, b, d) {
        (this.$panel = a), (this.accordion = b), (this.settings = this.accordion.settings), (this.panelNS = "ResponsiveAccordionSliderPanel" + d + "." + c), (this.isLoading = !1), (this.isLoaded = !1), this.setIndex(d), this._init();
    };
    (e.prototype = {
        _init: function () {
            var a = this;
            this.$panel.attr("data-init", !0),
                this.on("mouseenter." + this.panelNS, function () {
                    a.trigger({ type: "panelMouseOver." + c, index: a.index });
                }),
                this.on("mouseleave." + this.panelNS, function () {
                    a.trigger({ type: "panelMouseOut." + c, index: a.index });
                }),
                this.on("click." + this.panelNS, function () {
                    a.trigger({ type: "panelClick." + c, index: a.index });
                }),
                this.on("mousedown." + this.panelNS, function () {
                    a.trigger({ type: "panelMouseDown." + c, index: a.index });
                }),
                this.update();
            var d = b.ResponsiveAccordionSlider.modules.panel;
            if ("undefined" != typeof d) for (var e = 0; e < d.length; e++) "undefined" != typeof this["init" + d[e]] && this["init" + d[e]]();
        },
        update: function () {
            (this.positionProperty = "horizontal" === this.settings.orientation ? "left" : "top"),
                (this.sizeProperty = "horizontal" === this.settings.orientation ? "width" : "height"),
                this.$panel.css({ top: "", left: "", width: "", height: "" });
        },
        destroy: function () {
            this.off("mouseenter." + this.panelNS),
                this.off("mouseleave." + this.panelNS),
                this.off("click." + this.panelNS),
                this.off("mousedown." + this.panelNS),
                this.$panel.attr("style", ""),
                this.$panel.removeAttr("data-init"),
                this.$panel.removeAttr("data-index");
            var a = b.ResponsiveAccordionSlider.modules.panel;
            if ("undefined" != typeof a) for (var c = 0; c < a.length; c++) "undefined" != typeof this["destroy" + a[c]] && this["destroy" + a[c]]();
        },
        getIndex: function () {
            return this.index;
        },
        setIndex: function (a) {
            (this.index = a), this.$panel.attr("data-index", this.index);
        },
        getPosition: function () {
            return parseInt(this.$panel.css(this.positionProperty), 10);
        },
        setPosition: function (a) {
            this.$panel.css(this.positionProperty, a);
        },
        getSize: function () {
            return parseInt(this.$panel.css(this.sizeProperty), 10);
        },
        setSize: function (a) {
            this.$panel.css(this.sizeProperty, a);
        },
        getContentSize: function () {
            if (this.isLoaded === !1 && "loading" === this.checkImagesComplete()) return "loading";
            this.$panel.find(".as-opened").css("display", "none");
            var a = "width" === this.sizeProperty ? this.$panel[0].scrollWidth : this.$panel[0].scrollHeight;
            return this.$panel.find(".as-opened").css("display", ""), a;
        },
        checkImagesComplete: function () {
            if (this.isLoading === !0) return "loading";
            var a = this,
                d = "complete";
            if (
                (this.$panel.find("img").each(function (a) {
                    var c = b(this)[0];
                    (c.complete === !1 || "undefined" != typeof b(this).attr("data-src")) && (d = "loading");
                }),
                "loading" === d)
            ) {
                this.isLoading = !0;
                var e = setInterval(function () {
                    var d = !0;
                    a.$panel.find("img").each(function (a) {
                        var c = b(this)[0];
                        (c.complete === !1 || "undefined" != typeof b(this).attr("data-src")) && (d = !1);
                    }),
                        d === !0 && ((a.isLoading = !1), (a.isLoaded = !0), clearInterval(e), a.trigger({ type: "imagesComplete." + c, index: a.index, contentSize: a.getContentSize() }));
                }, 100);
            } else this.isLoaded = !0;
            return d;
        },
        on: function (a, b) {
            return this.$panel.on(a, b);
        },
        off: function (a) {
            return this.$panel.off(a);
        },
        trigger: function (a) {
            return this.$panel.triggerHandler(a);
        },
    }),
        (a.ResponsiveAccordionSlider = d),
        (a.ResponsiveAccordionSliderPanel = e),
        (b.fn.ResponsiveAccordionSlider = function (a) {
            var c = Array.prototype.slice.call(arguments, 1);
            return this.each(function () {
                if ("undefined" == typeof b(this).data("ResponsiveAccordionSlider")) {
                    var e = new d(this, a);
                    b(this).data("ResponsiveAccordionSlider", e);
                } else if ("undefined" != typeof a) {
                    var f = b(this).data("ResponsiveAccordionSlider");
                    if ("function" == typeof f[a]) f[a].apply(f, c);
                    else if ("undefined" != typeof f.settings[a]) {
                        var g = {};
                        (g[a] = c[0]), f._setProperties(g);
                    } else "object" == typeof a ? f._setProperties(a) : b.error(a + " does not exist in ResponsiveAccordionSlider.");
                }
            });
        });
})(window, jQuery),
    (function (a, b) {
        "use strict";
        var c = b.ResponsiveAccordionSlider.namespace,
            d = {
                autoplayIndex: -1,
                autoplayTimer: null,
                isTimerRunning: !1,
                isTimerPaused: !1,
                initAutoplay: function () {
                    var a = this;
                    this.settings.autoplay === !0 && this.startAutoplay(),
                        this.on("panelOpen.Autoplay." + c, function (b) {
                            (a.autoplayIndex = b.index), a.settings.autoplay === !0 && (a.isTimerRunning === !0 && a.stopAutoplay(), a.isTimerPaused === !1 && a.startAutoplay());
                        }),
                        this.on("panelsClose.Autoplay." + c, function (b) {
                            -1 !== b.previousIndex && (a.autoplayIndex = b.previousIndex);
                        }),
                        this.on("pageScroll.Autoplay." + c, function (b) {
                            a.autoplayIndex = a._getFirstPanelFromPage() - 1;
                        }),
                        this.on("mouseenter.Autoplay." + c, function (b) {
                            a.settings.autoplay !== !0 || !a.isTimerRunning || ("pause" !== a.settings.autoplayOnHover && "stop" !== a.settings.autoplayOnHover) || (a.stopAutoplay(), (a.isTimerPaused = !0));
                        }),
                        this.on("mouseleave.Autoplay." + c, function (b) {
                            a.settings.autoplay === !0 && a.isTimerRunning === !1 && "stop" !== a.settings.autoplayOnHover && (a.startAutoplay(), (a.isTimerPaused = !1));
                        });
                },
                startAutoplay: function () {
                    var a = this;
                    (this.isTimerRunning = !0),
                        (this.autoplayTimer = setTimeout(function () {
                            -1 !== a.autoplayIndex && ((a.currentIndex = a.autoplayIndex), (a.autoplayIndex = -1)),
                                "normal" === a.settings.autoplayDirection ? a.nextPanel() : "backwards" === a.settings.autoplayDirection && a.previousPanel();
                        }, this.settings.autoplayDelay));
                },
                stopAutoplay: function () {
                    (this.isTimerRunning = !1), clearTimeout(this.autoplayTimer);
                },
                destroyAutoplay: function () {
                    clearTimeout(this.autoplayTimer), this.off("panelOpen.Autoplay." + c), this.off("pageScroll.Autoplay." + c), this.off("mouseenter.Autoplay." + c), this.off("mouseleave.Autoplay." + c);
                },
                autoplayDefaults: { autoplay: !0, autoplayDelay: 5e3, autoplayDirection: "normal", autoplayOnHover: "pause" },
            };
        b.ResponsiveAccordionSlider.addModule("Autoplay", d, "accordion");
    })(window, jQuery),
    (function (a, b) {
        "use strict";
        var c = b.ResponsiveAccordionSlider.namespace,
            d = {
                mouseWheelEventType: "",
                allowMouseWheelScroll: !0,
                initMouseWheel: function () {
                    var a = this;
                    this.settings.mouseWheel !== !1 &&
                        ("onwheel" in document
                            ? (this.mouseWheelEventType = "wheel")
                            : "onmousewheel" in document
                            ? (this.mouseWheelEventType = "mousewheel")
                            : "onDomMouseScroll" in document
                            ? (this.mouseWheelEventType = "DomMouseScroll")
                            : "onMozMousePixelScroll" in document && (this.mouseWheelEventType = "MozMousePixelScroll"),
                        this.on(this.mouseWheelEventType + "." + c, function (b) {
                            b.preventDefault();
                            var c,
                                d = b.originalEvent;
                            "undefined" != typeof d.detail && (c = d.detail),
                                "undefined" != typeof d.wheelDelta && (c = d.wheelDelta),
                                "undefined" != typeof d.deltaY && (c = -1 * d.deltaY),
                                a.allowMouseWheelScroll === !0 &&
                                    Math.abs(c) >= a.settings.mouseWheelSensitivity &&
                                    ((a.allowMouseWheelScroll = !1),
                                    setTimeout(function () {
                                        a.allowMouseWheelScroll = !0;
                                    }, 500),
                                    c <= -a.settings.mouseWheelSensitivity
                                        ? "page" === a.settings.mouseWheelTarget
                                            ? a.nextPage()
                                            : a.nextPanel()
                                        : c >= a.settings.mouseWheelSensitivity && ("page" === a.settings.mouseWheelTarget ? a.previousPage() : a.previousPanel()));
                        }));
                },
                destroyMouseWheel: function () {
                    this.off(this.mouseWheelEventType + "." + c);
                },
                mouseWheelDefaults: { mouseWheel: !0, mouseWheelSensitivity: 10, mouseWheelTarget: "panel" },
            };
        b.ResponsiveAccordionSlider.addModule("MouseWheel", d, "accordion");
    })(window, jQuery),
   
    (function (a, b) {
        "use strict";
        var c = b.ResponsiveAccordionSlider.namespace,
            d = {
                touchStartPoint: { x: 0, y: 0 },
                touchEndPoint: { x: 0, y: 0 },
                touchDistance: { x: 0, y: 0 },
                touchStartPosition: 0,
                isTouchMoving: !1,
                touchSwipeEvents: { startEvent: "", moveEvent: "", endEvent: "" },
                previousStartEvent: "",
                initTouchSwipe: function () {
                    var a = this;
                    this.settings.touchSwipe !== !1 &&
                        ((this.touchSwipeEvents.startEvent = "touchstart." + c + " mousedown." + c),
                        (this.touchSwipeEvents.moveEvent = "touchmove." + c + " mousemove." + c),
                        (this.touchSwipeEvents.endEvent = "touchend." + this.uniqueId + "." + c + " mouseup." + this.uniqueId + "." + c),
                        this.$panelsContainer.on(this.touchSwipeEvents.startEvent, b.proxy(this._onTouchStart, this)),
                        this.$panelsContainer.on("dragstart." + c, function (a) {
                            a.preventDefault();
                        }),
                        this.$panelsContainer.find("a").on("click." + c, function (b) {
                            a.$accordion.hasClass("as-swiping") && b.preventDefault();
                        }),
                        this.$panelsContainer.on("touchstart." + c, function (a) {
                            b(this).find("[data-disabledlink]").css("pointer-events", "").removeAttr("data-disabledlink");
                        }),
                        this.$panelsContainer.find("a").on("touchend." + c, function (a) {
                            b(this).parents(".ras-panel").hasClass("as-opened") === !1 && (b(this).css("pointer-events", "none"), b(this).attr("data-disabledlink", "true"));
                        }),
                        this.on("update.TouchSwipe." + c, function () {
                            a.getTotalPages() > 1 ? a.$panelsContainer.addClass("as-grab") : a.$panelsContainer.removeClass("as-grab");
                        }));
                },
                _onTouchStart: function (a) {
                    if ("mousedown" === a.type && "touchstart" === this.previousStartEvent) return void (this.previousStartEvent = a.type);
                    this.previousStartEvent = a.type;
                    var c = "undefined" != typeof a.originalEvent.touches ? a.originalEvent.touches[0] : a.originalEvent;
                    b(a.target).closest(".as-selectable").length >= 1 ||
                        ("undefined" == typeof a.originalEvent.touches && 1 === this.getTotalPages()) ||
                        ((this.touchStartPoint.x = c.pageX || c.clientX),
                        (this.touchStartPoint.y = c.pageY || c.clientY),
                        (this.touchStartPosition = parseInt(this.$panelsContainer.css(this.positionProperty), 10)),
                        (this.touchDistance.x = this.touchDistance.y = 0),
                        this.$panelsContainer.on(this.touchSwipeEvents.moveEvent, b.proxy(this._onTouchMove, this)),
                        b(document).on(this.touchSwipeEvents.endEvent, b.proxy(this._onTouchEnd, this)),
                        this.$panelsContainer.removeClass("as-grab").addClass("as-grabbing"));
                },
                _onTouchMove: function (a) {
                    var b = "undefined" != typeof a.originalEvent.touches ? a.originalEvent.touches[0] : a.originalEvent;
                    (this.isTouchMoving = !0),
                        this.$accordion.hasClass("as-swiping") === !1 && this.$accordion.addClass("as-swiping"),
                        (this.touchEndPoint.x = b.pageX || b.clientX),
                        (this.touchEndPoint.y = b.pageY || b.clientY),
                        (this.touchDistance.x = this.touchEndPoint.x - this.touchStartPoint.x),
                        (this.touchDistance.y = this.touchEndPoint.y - this.touchStartPoint.y);
                    var c = "horizontal" === this.settings.orientation ? this.touchDistance.x : this.touchDistance.y,
                        d = "horizontal" === this.settings.orientation ? this.touchDistance.y : this.touchDistance.x;
                    if (!(Math.abs(c) <= Math.abs(d) || 1 === this.getTotalPages() || (this.getTotalPages() > 1 && this.currentPage === this.getTotalPages() - 1))) {
                        a.preventDefault();
                        var e = parseInt(this.$panelsContainer.css(this.positionProperty), 10);
                        ((e >= 0 && 0 === this.currentPage) || (e <= -this.totalPanelsSize + this.totalSize && this.currentPage === this.getTotalPages() - 1)) && (c = 0.2 * c),
                            this.$panelsContainer.css(this.positionProperty, this.touchStartPosition + c);
                    }
                },
                _onTouchEnd: function (a) {
                    var c = this;
                    if (
                        (this.$panelsContainer.off(this.touchSwipeEvents.moveEvent),
                        b(document).off(this.touchSwipeEvents.endEvent),
                        this.$panelsContainer.removeClass("as-grabbing").addClass("as-grab"),
                        this.isTouchMoving === !1 || (this.isTouchMoving === !0 && Math.abs(this.touchDistance.x) < 10 && Math.abs(this.touchDistance.y) < 10))
                    ) {
                        var d = b(a.target).parents(".ras-panel").index();
                        "undefined" != typeof a.originalEvent.touches && d !== this.currentIndex && -1 !== d && "never" !== this.openPanelOn && this.openPanel(d);
                    }
                    if (
                        (this.$accordion.hasClass("as-swiping") &&
                            setTimeout(function () {
                                c.$accordion.removeClass("as-swiping");
                            }, 100),
                        this.isTouchMoving !== !1)
                    ) {
                        this.isTouchMoving = !1;
                        var e = {};
                        (e[this.positionProperty] = this.touchStartPosition),
                            "horizontal" === this.settings.orientation
                                ? this.touchDistance.x > this.settings.touchSwipeThreshold
                                    ? this.currentPage > 0
                                        ? this.previousPage()
                                        : this.$panelsContainer.stop().animate(e, 300)
                                    : -this.touchDistance.x > this.settings.touchSwipeThreshold
                                    ? this.currentPage < this.getTotalPages() - 1
                                        ? this.nextPage()
                                        : this.gotoPage(this.currentPage)
                                    : Math.abs(this.touchDistance.x) < this.settings.touchSwipeThreshold && this.$panelsContainer.stop().animate(e, 300)
                                : "vertical" === this.settings.orientation &&
                                  (this.touchDistance.y > this.settings.touchSwipeThreshold
                                      ? this.currentPage > 0
                                          ? this.previousPage()
                                          : this.$panelsContainer.stop().animate(e, 300)
                                      : -this.touchDistance.y > this.settings.touchSwipeThreshold
                                      ? this.currentPage < this.getTotalPages() - 1
                                          ? this.nextPage()
                                          : this.$panelsContainer.animate(e, 300)
                                      : Math.abs(this.touchDistance.y) < this.settings.touchSwipeThreshold && this.$panelsContainer.stop().animate(e, 300));
                    }
                },
                destroyTouchSwipe: function () {
                    this.$panelsContainer.off("dragstart." + c),
                        this.$panelsContainer.find("a").off("click." + c),
                        this.$panelsContainer.find("a").off("touchstart." + c),
                        this.$panelsContainer.off(this.touchSwipeEvents.startEvent),
                        this.$panelsContainer.off(this.touchSwipeEvents.moveEvent),
                        b(document).off(this.touchSwipeEvents.endEvent),
                        this.off("update.TouchSwipe." + c),
                        this.$panelsContainer.removeClass("as-grab");
                },
                touchSwipeDefaults: { touchSwipe: !0, touchSwipeThreshold: 50 },
            };
        b.ResponsiveAccordionSlider.addModule("TouchSwipe", d, "accordion");
    })(window, jQuery),
    (function (a, b) {
        "use strict";
        var c = b.ResponsiveAccordionSlider.namespace,
            d = a.navigator.userAgent.toLowerCase(),
            e = /(msie) ([\w.]+)/,
            f = e.exec(d) || [],
            g = f[1],
            h = {
                XMLDataAttributesMap: {
                    width: "data-width",
                    height: "data-height",
                    depth: "data-depth",
                    position: "data-position",
                    horizontal: "data-horizontal",
                    vertical: "data-vertical",
                    showTransition: "data-show-transition",
                    showOffset: "data-show-offset",
                    showDelay: "data-show-delay",
                    showDuration: "data-show-duration",
                    showEasing: "data-show-easing",
                    hideTransition: "data-hide-transition",
                    hideOffset: "data-",
                    hideDelay: "data-hide-delay",
                    hideDuration: "data-hide-duration",
                    hideEasing: "data-hide-easing",
                },
                initXML: function () {
                    null !== this.settings.XMLSource && this.updateXML();
                },
                updateXML: function () {
                    var a = this;
                    this.removePanels(),
                        this.$panelsContainer.empty(),
                        this.off("XMLReady." + c),
                        this.on("XMLReady." + c, function (c) {
                            var d = b(c.xmlData),
                                e = d.find("accordion")[0].attributes.lazyLoading;
                            "undefined" != typeof e && (e = e.nodeValue),
                                d.find("panel").each(function () {
                                    var c,
                                        d,
                                        f = b(this),
                                        g = f.find("background"),
                                        h = f.find("backgroundRetina"),
                                        i = f.find("backgroundLink"),
                                        j = f.find("backgroundOpened"),
                                        k = f.find("backgroundOpenedRetina"),
                                        l = f.find("backgroundOpenedLink"),
                                        m = f.find("layer"),
                                        n = b('<div class="ras-panel"></div>').appendTo(a.$panelsContainer);
                                    if (
                                        (i.length >= 1 &&
                                            ((c = b('<a href="' + i.text() + '"></a>')),
                                            b.each(i[0].attributes, function (a, b) {
                                                c.attr(b.nodeName, b.nodeValue);
                                            }),
                                            c.appendTo(n)),
                                        g.length >= 1)
                                    ) {
                                        var o = b('<img class="as-background"/>');
                                        "undefined" != typeof e ? o.attr({ src: e, "data-src": g.text() }) : o.attr({ src: g.text() }),
                                            h.length >= 1 && o.attr({ "data-retina": h.text() }),
                                            b.each(g[0].attributes, function (a, b) {
                                                o.attr(b.nodeName, b.nodeValue);
                                            }),
                                            o.appendTo(i.length ? c : n);
                                    }
                                    if (
                                        (l.length >= 1 &&
                                            ((d = b('<a href="' + l.text() + '"></a>')),
                                            b.each(l[0].attributes, function (a, b) {
                                                d.attr(b.nodeName, b.nodeValue);
                                            }),
                                            d.appendTo(n)),
                                        j.length >= 1)
                                    ) {
                                        var p = b('<img class="as-background-opened"/>');
                                        "undefined" != typeof e ? p.attr({ src: e, "data-src": j.text() }) : p.attr({ src: j.text() }),
                                            k.length >= 1 && p.attr({ "data-retina": k.text() }),
                                            b.each(j[0].attributes, function (a, b) {
                                                p.attr(b.nodeName, b.nodeValue);
                                            }),
                                            p.appendTo(l.length ? d : n);
                                    }
                                    m.length >= 1 &&
                                        b.each(m, function () {
                                            var c = b(this),
                                                d = "",
                                                e = "",
                                                f = n;
                                            b.each(c[0].attributes, function (c, f) {
                                                if ("style" === f.nodeName) {
                                                    var g = f.nodeValue.split(" ");
                                                    b.each(g, function (a, b) {
                                                        d += " as-" + b;
                                                    });
                                                } else e += " " + a.XMLDataAttributesMap[f.nodeName] + '="' + f.nodeValue + '"';
                                            });
                                            var g = b('<div class="as-layer' + d + '"' + e + '"></div>');
                                            if (c.find("layer").length >= 1) {
                                                var h = new Date().valueOf();
                                                c.attr("parentID", h), g.attr("class", g.attr("class") + " " + h);
                                            } else g.html(c.text());
                                            c.parent().is("layer") && (f = n.find("." + c.parent().attr("parentID"))), g.appendTo(f);
                                        });
                                }),
                                a.update();
                        }),
                        this._loadXML();
                },
                _loadXML: function () {
                    var a = this;
                    if (".xml" === this.settings.XMLSource.slice(-4))
                        b.ajax({
                            type: "GET",
                            url: this.settings.XMLSource,
                            dataType: "msie" === g ? "text" : "xml",
                            success: function (b) {
                                var d;
                                "msie" === g ? ((d = new ActiveXObject("Microsoft.XMLDOM")), (d.async = !1), d.loadXML(b)) : (d = b), a.trigger({ type: "XMLReady." + c, xmlData: d });
                            },
                        });
                    else {
                        var d = b.parseXML(this.settings.XMLSource);
                        a.trigger({ type: "XMLReady." + c, xmlData: d });
                    }
                },
                destroyXML: function () {
                    this.off("XMLReady." + c);
                },
                XMLDefaults: { XMLSource: null },
            };
       // b.AccordionSlider.addModule("XML", h, "accordion");
    })(window, jQuery);
