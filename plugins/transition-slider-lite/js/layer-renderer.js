"use strict";

var STX = STX || {};

STX.LayerRenderer = function(params) {
    var self = this;

    var id = 0;
    this.layerScale = 0;

    this.onLayerMouseDown = params.onLayerMouseDown;
    this.onLayerMouseUp = params.onLayerMouseUp;
    this.onLayerMove = params.onLayerMove;
    this.$sliderPreviewArea = jQuery(".slider-preview-area");

    this.$wrapper = jQuery(".slider-preview-area .stx-layers");
    this.$canvas = jQuery(".slider-preview-area .stx-layers-canvas");
    this.$content = jQuery(".slider-preview-area .stx-layers-content");

    this.$wrapperStatic = jQuery(".slider-preview-area .stx-layers-static");
    this.$canvasStatic = jQuery(".slider-preview-area .stx-layers-canvas-static");
    this.$contentStatic = jQuery(".slider-preview-area .stx-layers-content-static");

    this.$preview = jQuery('.slider-preview-area')

    this.$preview.bind("mousedown", function(e) {
        if(e.button === 2) return;

        self.dragging = true;
        self.dragOriginX = e.clientX;
        self.dragOriginY = e.clientY;
    });

    this.$preview.bind("mouseup", function(e) {
        self.dragging = false;
    });


    this.styles = {};

    this.$preview.bind("mousemove", function(e) {
        if (self.dragging) {
            self.dragChangeX = e.clientX - self.dragOriginX;
            self.dragChangeY = e.clientY - self.dragOriginY;
            self.dragOriginX = e.clientX;
            self.dragOriginY = e.clientY;
            self.onLayerMove({
                x: self.dragChangeX / self.layerScale,
                y: self.dragChangeY / self.layerScale
            });
        }
    });

    document.getElementsByClassName('slider-preview-area')[0].addEventListener("mouseup", function(e) {
        self.dragging = false;
        self.onLayerMouseUp();
    });

    jQuery('.slider-preview-area').on("dragstart", function() {
        return false;
    });

    this.render = function(elements, deviceType) {
        this.elements = elements;
        this.deviceType = deviceType;

        self.clear();

        self.nodes = 0;

        if (elements) {
            self.addNodeElements(elements);
        }
    };

    this.updateLayerSize = function(options, currentSlide) {
        this.options = options;
        var o = options;
        var slide = o.slides[currentSlide];

        var layerWidth = o.layerWidth || slide.layerWidth;
        var layerHeight = o.layerHeight || slide.layerHeight;
        var layerWidthMin = o.layerWidthMin || "initial";
        var layerWidthMax = o.layerWidthMax || "initial";
        var layerHeightMin = o.layerHeightMin || "initial";
        var layerHeightMax = o.layerHeightMax || "initial";
        var layerScale = 1;
        var sliderWrapperWidth = this.$sliderPreviewArea.width();
        var sliderWrapperHeight = this.$sliderPreviewArea.height();

        var css = {
            width: layerWidth,
            height: layerHeight,
            minWidth: layerWidthMin,
            maxWidth: layerWidthMax,
            minHeight: layerHeightMin,
            maxHeight: layerHeightMax,
            "-webkit-transform": "translateX(-50%) translateY(-50%)",
            left: "50%",
            top: "50%"
        }

        this.$wrapperStatic.css(css);

        css.backgroundColor = o.layerBackground

        this.$wrapper.css(css);

        var lw = this.$wrapper.width();
        var lh = this.$wrapper.height();

        var scaleX = sliderWrapperWidth / lw;
        var scaleY = sliderWrapperHeight / lh;

        layerScale = scaleX > scaleY ? scaleY : scaleX;
        this.layerScale = layerScale;

        css = {"-webkit-transform": "scale(" + layerScale + ") translateX(-50%) translateY(-50%)"}

        this.$wrapper.css(css);
        this.$wrapperStatic.css(css);

        this.updateElementPositios();
    };

    this.updateElementPositios = function() {
        this.elements.forEach(function(element) {
            self.updateElementPosition(element);
        });
    };

    this.renderAddedElement = function(elements) {
        this.elements = elements;
        this.addNodeElement(elements[elements.length - 1]);
        this.updateElementProperties(elements[elements.length - 1]);
    };

    this.updateElement = function(index, settingName, hover) {
        this.updateElementProperties(index, settingName, hover);
    };

    this.focusElement = function(index) {
        if (this.elements && this.elements[index] && this.elements[index].node) {
            jQuery(this.elements[index].node).addClass("focused-element");
        }
    };

    this.unfocusElement = function() {
        jQuery(".focused-element").removeClass("focused-element");
    };

    this.clear = function() {
        this.$wrapper.find("td").empty();
        this.$canvas.empty();

        this.$wrapperStatic.find("td").empty();
        this.$canvasStatic.empty();
    };

    this.loadElementFont = function(el) {
        var self = this
        this.loadFont({fontFamily: el.fontFamily, fontWeight: el.fontWeight}, function(){self.updateElementPosition(el)})
    };

    this.loadFont = function(font, callback){
        var self = this;
        var fontFamily, fontWeight;

        fontFamily = font.fontFamily;
        fontWeight = font.fontWeight;

        var fontVariationsToLoad = 2;

        if (fontFamily && fontFamily != "initial") {
            if (fontFamily.startsWith('"') || fontFamily.startsWith("'")) fontFamily = fontFamily.slice(1, -1);

            WebFont.load({
                google: {
                    families: [fontFamily, fontFamily + ":" + fontWeight]
                },
                fontactive: function() {
                    --fontVariationsToLoad;
                    if (fontVariationsToLoad <= 0 ) if(callback) callback();
                },
                fontinactive: function() {
                    --fontVariationsToLoad;
                    if (fontVariationsToLoad <= 0 ) if(callback) callback();
                }
            });
        } else {
            if(callback) callback();
        }
    }

    this.updateElementProperties = function(el, settingName, hover) {
        var node = el.node,
            $node = jQuery(node),
            self = this;


        var view = this.deviceType;

        var styleIndex = 0;
        if (view == "tablet") styleIndex = 1;
        else if (view == "mobile") styleIndex = 2;

        if (hover) styleIndex = 3;

        var id = "s" + el.id;

        function cssValue(name, val) {
            return isNaN(val) || name === "fontWeight" || val === "" ? val : val + "px";
        }

        function clearCustomCSS() {
            $node.attr("style", "");
        }

        function setStyleFromEditor(id, styleIndex, settingName, settingVal) {
            document.getElementById(id).sheet.cssRules[styleIndex].style[settingName] = cssValue(settingName, settingVal);
        }

        if (settingName === undefined || settingName === 'boxShadowHorizontal' || settingName === 'boxShadowVertical' || settingName === 'boxShadowBlur' || settingName === 'boxShadowColor' || settingName === 'boxShadowSpread' || settingName === 'boxShadowPosition') {
            changeShadowCSSProperty(el);
            if(el.hover) changeShadowCSSProperty(el.hover);
            if(settingName !== undefined) settingName = "boxShadow";
        }

        if (settingName === undefined || settingName === 'textShadowHorizontal' || settingName === 'textShadowVertical' || settingName === 'textShadowBlur' || settingName === 'textShadowColor') {
            changeTextShadowCSSProperty(el);
            if(el.hover) changeTextShadowCSSProperty(el.hover);
            if(settingName !== undefined) settingName = "textShadow";
        }

        function changeShadowCSSProperty(cssObject) {
            if (cssObject.boxShadowHorizontal || cssObject.boxShadowVertical || cssObject.boxShadowBlur || cssObject.boxShadowSpread || cssObject.boxShadowColor || cssObject.boxShadowPosition) {
                cssObject.boxShadowHorizontal = cssObject.boxShadowHorizontal || '0px';
                cssObject.boxShadowVertical = cssObject.boxShadowVertical || '0px';
                cssObject.boxShadowBlur = cssObject.boxShadowBlur || '0px';
                cssObject.boxShadowSpread = cssObject.boxShadowSpread || '0px';
                cssObject.boxShadowColor = cssObject.boxShadowColor || 'rgba(0, 0, 0, 0.5)';
                cssObject.boxShadowPosition = cssObject.boxShadowPosition || '';
                cssObject['boxShadow'] = cssObject.boxShadowPosition + ' ' + cssObject.boxShadowHorizontal + ' ' + cssObject.boxShadowVertical + ' ' + cssObject.boxShadowBlur + ' ' + cssObject.boxShadowSpread + ' ' + cssObject.boxShadowColor;
            }
        }

        function changeTextShadowCSSProperty(cssObject) {
            if (cssObject.textShadowHorizontal || cssObject.textShadowVertical || cssObject.textShadowBlur || cssObject.textShadowColor) {
                cssObject.textShadowHorizontal = cssObject.textShadowHorizontal || '0px';
                cssObject.textShadowVertical = cssObject.textShadowVertical || '0px';
                cssObject.textShadowBlur = cssObject.textShadowBlur || '0px';
                cssObject.textShadowColor = cssObject.textShadowColor || 'rgba(0, 0, 0, 0.5)';
                cssObject['textShadow'] = cssObject.textShadowHorizontal + ' ' + cssObject.textShadowVertical + ' ' + cssObject.textShadowBlur + ' ' + cssObject.textShadowColor;
            }
        }

        var settingVal;

        if ((view == "mobile" || view == "tablet") && el[view]) settingVal = el[view][settingName];
        else settingVal = el[settingName];

        if (settingName === "textColor")  {
            settingName = "color";
            el.color = el.textColor;
            if(el.hover)
                el.hover.color = el.hover.textColor;
        }

        if (hover && el.hover)
			settingVal = el.hover[settingName];

        switch (settingName) {
            case "src":
                if (el.src) node.src = el.src;
                break;
            case "content":
                if (typeof el.content == "string") node.innerHTML = el.content;
                this.updateElementPosition(el);
				break;

            case "position":
            case "position.offsetX":
            case "position.offsetY":
            case "position.x":
            case "position.y":
                this.updateElementPosition(el);
                break;

            case "fontFamily":
                this.loadElementFont(el);
                document.getElementById(id).sheet.cssRules[styleIndex].style[settingName] = settingVal;
                break;

            case "fontWeight":
                this.loadElementFont(el);
                setStyleFromEditor(id, styleIndex, settingName, settingVal);
                break;

            case "fontSize":
            case "letterSpacing":
            case "lineHeight":
            case "borderWidth":
            case "borderColor":
            case "borderRadius":
            case "color":
            case "background":
            case "backgroundColor":
            case "textAlign":
            case "margin":
            case "marginLeft":
            case "marginTop":
            case "marginRight":
            case "marginBottom":
            case "padding":
            case "paddingTop":
            case "paddingLeft":
            case "paddingRight":
            case "paddingBottom":
            case "display":
            case "width":
            case "minWidth":
            case "maxWidth":
            case "height":
            case "minHeight":
            case "maxHeight":
            case "textShadow":
            case "boxShadow":
                setStyleFromEditor(id, styleIndex, settingName, settingVal);
                break;

            case "borderStyle":
                setStyleFromEditor(id, styleIndex, settingName, settingVal);
                break;

            case "mode":
                break;

            default:
                switch (el.type) {
                    case "text":
                        this.loadElementFont(el);
                        break;

                    case "button":
                        this.loadElementFont(el);
                        break;
                }

                if (el.content) node.innerHTML = el.content;
				clearCustomCSS();

                this.updateElementPosition(el);

                break;
        }

        if (el.customCSS && el.customCSS != "") $node.attr("style", $node.attr("style") + "; " + el.customCSS);
    };

    this.updateElementPosition = function(el) {
        if(!el || !el.position)
            debugger
        var view = this.deviceType;

        var node = el.node,
            $node = jQuery(node);

        var pos = el.position;
        var mode = el.mode;

        if (el[view] && el[view].mode) mode = el[view].mode;
        if (el[view] && el[view].position) pos = el[view].position;

        pos.x = pos.x || "center";
        pos.y = pos.y || "center";
        pos.offsetX = pos.offsetX || 0;
        pos.offsetY = pos.offsetY || 0;

        if (mode != "content") {
            if (pos.x === "center") $node.css({ left: "calc(50% - " + $node.outerWidth() / 2 + "px + " + pos.offsetX + "px)", right: "unset" });
            else if (pos.x === "left") $node.css({ left: pos.offsetX + "px", right: "unset" });
            else if (pos.x === "right") $node.css({ right: pos.offsetX + "px", left: "unset" });
            else node.style.setProperty("left", pos.x.toString() + "%");

            if (pos.y === "center") $node.css({ top: "calc(50% - " + $node.outerHeight() / 2 + "px - " + pos.offsetY + "px)", bottom: "unset" });
            else if (pos.y === "top") $node.css({ top: pos.offsetY + "px", bottom: "unset" });
            else if (pos.y === "bottom") $node.css({ bottom: pos.offsetY + "px", top: "unset" });
            else node.style.setProperty("top", pos.y.toString() + "%");
        }
    };

    this.updateElementMode = function(el) {
        var view = this.deviceType;
        var pos = el.position;
        var mode = el.mode;
        var $wrapper = el.static ? this.$wrapperStatic : this.$wrapper
        var $content = el.static ? this.$contentStatic : this.$content
        var $canvas = el.static ? this.$canvasStatic : this.$canvas

        if (el[view] && el[view].mode) mode = el[view].mode;
        if (el[view] && el[view].position) pos = el[view].position;

        pos.x = pos.x || "center";
        pos.y = pos.y || "center";
        pos.offsetX = pos.offsetX || 0;
        pos.offsetY = pos.offsetY || 0;

        if (mode == "content") {
            var $container = $wrapper.find(".row-" + pos.y).find(".col-" + pos.x);

            el.$node.addClass("el-" + pos.x);
            el.$node.removeClass("element-canvas").addClass("element-content");

            if (el.parent) {
                $wrapper.find("#" + el.parent).append(el.$node);
            } else if (el.node.wrapper) {
                el.node.wrapper.append(el.$node);
                $container.append(el.node.wrapper);
            } else {
                $container.append(el.$node);
            }

            if (jQuery(".el-center").length && (jQuery(".el-left").length || jQuery(".el-right").length)) $content.find("td").css("width", "33.33%");
            else $content.find("td").css("width", "auto");
        } else {
            el.$node.removeClass("element-content").addClass("element-canvas");
            el.$node.removeClass("el-" + pos.x);

            $canvas.append(el.node.wrapper);
        }

        if (el.display) el.node.wrapper.css("display", el.display);
    };

    this.addNodeElement = function(el) {
        var self = this;
        var view = this.deviceType;

        switch (el.type) {
            case "text":
                el.node = document.createElement(el.htmlTag || "p");
                break;

            case "heading":
                el.node = document.createElement(el.htmlTag || "h2");
                break;

            case "image":
                el.node = new Image();
                el.node.draggable = false;

                if (el.src) el.node.src = el.src;
                if (el.size) {
                    el.node.style.setProperty("width", el.size + "px");
                    el.node.style.setProperty("height", "auto");
                }

                el.node.onload = function() {
                    self.updateElementPosition(el);
                };
                break;

            case "video":
                var $node = jQuery('<video width="320" height="240" muted="muted" autoplay="autoplay" loop="loop" playsinline="playsinline" preload="metadata" data-aos="fade-up"><source src="' + el.src + '" type="video/mp4"></video>');
                var node = $node[0];
                el.node = node;
                if (el.width) node.width = el.width;
                if (el.height) node.height = el.height;
                node.oncanplaythrough = function() {
                    node.muted = true;
                    node.play();
                    node.pause();
                    node.play();
                    self.updateElementPosition(el);
                };

                el.node.draggable = false;

                if (el.size) {
                    el.node.style.setProperty("width", el.size + "px");
                    el.node.style.setProperty("height", "auto");
                }

                break;
            case "iframe":
                var iframe = document.createElement("iframe");
                iframe.src = el.src;
                iframe.width = "100%";
                iframe.height = "100%";
                jQuery(iframe).css("pointer-events", "none");
				jQuery(iframe).css("border", "1px dashed #dbdbdb5c");
				jQuery(iframe).css("background-size", "19px 19px");
				jQuery(iframe).css("background-image", "radial-gradient(circle, rgba(219, 219, 219, 0.36) 1px, rgb(0 0 0 / 10%) 1px)");

                var $node = jQuery("<div>" + iframe.outerHTML + "</div>");
                var node = $node[0];
                el.node = node;
                if (el.width) node.width = el.width;
                if (el.height) node.height = el.height;

                el.node.draggable = false;

                if (el.size) {
                    el.node.style.setProperty("width", el.size + "px");
                    el.node.style.setProperty("height", "auto");
                }

                break;

            case "button":
                el.node = document.createElement("a");
                el.node.classList.add("stx-layer-button");
                break;
        }

        el.$node = jQuery(el.node);

        el.node.wrapper = jQuery(document.createElement("div"));
        el.node.wrapper.append(el.$node);
        if (el.display) el.node.wrapper.css("display", el.display);

        el.mode = el.mode || "canvas";
        el.contentAnimationType = el.contentAnimationType || "animating";

        el.node.classList.add("element-" + el.mode);
        el.node.classList.add("element");

        el.node.dataset.id = self.nodes;

        self.nodes++;

        el.node.onmousedown = function(e) {
            if(e.button !== 2) self.onLayerMouseDown(this.id, e.shiftKey);
        };

        this.createStyle(el);

        this.updateElementMode(el);

        this.updateElementProperties(el);

    };

    this.addNodeElements = function(elements) {
        var self = this
        elements.forEach(function(el){
            self.addNodeElement(el)
            self.updateClasses(el)
        })
    };

    this.createStyle = function(el) {

        el.id = el.id || String(Date.now()) + parseInt(Math.random() * 100);

        el.node.id = "n" + el.id;

        var cssProperties = {
            size: "width",
            width: "width",
            height: "height",
            margin: "margin",
            padding: "padding",

            paddingTop: "padding-top",
            paddingBottom: "padding-bottom",
            paddingLeft: "padding-left",
            paddingRight: "padding-right",

            marginTop: "margin-top",
            marginBottom: "margin-bottom",
            marginLeft: "margin-left",
            marginRight: "margin-right",

            fontFamily: "font-family",
            fontSize: "font-size",
            fontWeight: "font-weight",

            lineHeight: "line-height",
            letterSpacing: "letter-spacing",
            textAlign: "text-align",
            backgroundColor: "background-color",

            borderWidth: "border-width",
            borderStyle: "border-style",
            borderColor: "border-color",
            borderRadius: "border-radius",

            textColor: "color",
            textShadow: "text-shadow",
            boxShadow: "box-shadow",

            minWidth: "min-width",
            maxWidth: "max-width"
        };

        function appendStyle(styles, id) {
            var css = document.createElement("style");
            css.type = "text/css";
            css.id = id;

            if (css.styleSheet) css.styleSheet.cssText = styles;
            else css.appendChild(document.createTextNode(styles));

            document.body.appendChild(css);
        }

        function createStyle(className, properties, customCSS, mobileSize, mobileProperties, tabletSize, tabletProperties, hoverProperties) {
            if (!properties) return "";

            var style = className + "{";
            var cssPropertyName = "";
            var arr = ["margin-top", "margin-bottom", "margin-right", "margin-left"];

            Object.keys(properties).forEach(function(property) {
                cssPropertyName = STX.Utils.camelToDash(property);
                if ("textColor" === property) cssPropertyName = "color";
                if (properties[property] !== "") style += cssPropertyName + ":" + properties[property] + ";";
                else {
                    jQuery.each(arr, function(index, value) {
                        if (cssPropertyName == arr[index]) {
                            style += cssPropertyName + ":" + "0px" + ";";
                        }
                    });
                }
            });


            style += "}";


            style += className + ".tablet";

            style += "{";

            Object.keys(tabletProperties).forEach(function(property) {
                cssPropertyName = STX.Utils.camelToDash(property);
                if ("textColor" === property) cssPropertyName = "color";
                if (tabletProperties[property] != "") style += cssPropertyName + ":" + tabletProperties[property] + ";";
            });

            style += "}";


            style += className + ".mobile";

            style += "{";

            Object.keys(mobileProperties).forEach(function(property) {
                cssPropertyName = STX.Utils.camelToDash(property);
                if ("textColor" === property) cssPropertyName = "color";
                if (mobileProperties[property] != "") style += cssPropertyName + ":" + mobileProperties[property] + ";";
            });

            style += "}";


            style += className + ":hover{";

            Object.keys(hoverProperties).forEach(function(property) {
                cssPropertyName = STX.Utils.camelToDash(property);
                if ("textColor" === property) cssPropertyName = "color";
                if (hoverProperties[property] != "") style += cssPropertyName + ":" + hoverProperties[property] + ";";
            });

            style += "}";

            return style;
        }

        function cssValue(name, val) {
            return isNaN(val) || name === "fontWeight" || val === "" ? val : val + "px";
        }

        var style = {};
        var mobileSize = 768;
        var tabletSize = 1024;
        var styleMobile = {};
        var styleTablet = {};
        var styleHover = {};

        for (var prop in cssProperties) {
            if (el[prop] !== "") {
				if(el[prop] === 0) el[prop] = el[prop].toString();
				style[cssProperties[prop]] = cssValue(prop, el[prop] || "");
			}
            if (el["mobile"] && el["mobile"][prop] != "") styleMobile[cssProperties[prop]] = cssValue(prop, el["mobile"][prop] || "");
            if (el["tablet"] && el["tablet"][prop] != "") styleTablet[cssProperties[prop]] = cssValue(prop, el["tablet"][prop] || "");
            if (el["hover"] && el["hover"][prop] != "") styleHover[cssProperties[prop]] = cssValue(prop, el["hover"][prop] || "");
        }

        if (!this.styles[el.id]) {
            var s = createStyle("#n" + el.id, style, el.customCSS, mobileSize, styleMobile, tabletSize, styleTablet, styleHover);
            appendStyle(s, "s" + el.id);
            this.styles[el.id] = s;
        }
    };

    this.setDeviceType = function(deviceType) {
        this.deviceType = deviceType;

        if (!this.elements || !this.elements.length) return;

        var self = this
        this.elements.forEach(function(el){
            self.updateClasses(el);
            self.updateElementMode(el);
            self.updateElementPosition(el);
        })
    };

    this.updateClasses = function(el) {
        el.$node.removeClass("mobile tablet desktop").addClass(this.deviceType);
    };
};
