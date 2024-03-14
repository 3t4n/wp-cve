"use strict";
(function($) {
    $(document).ready(function() {
        var $loader = $(".STX-loader-container").hide();
        $(".wrap").show();

        var json_str = data.options.replace(/&quot;/g, '"');
        var content = $(".STX-saved-notification-content");
        var notificationContentEditSlide = $(".STX-edit-slide-notification-content");
        var btnDeleteAll = $(".STX-delete-btn-wrapper");
        var msgSuccess = "Slider saved.";
        var msgNoSlides = "Slider has no slides!";
        var msgError = "Error saving slider." + "</br>" + "Please refresh page!";
        var msgDeletedSlides = "All slides deleted.";
		var previewModal = false;
        var counterForSlides = 0,
            slidesWrapper,
            slider,
            slide,
            url,
            file,
            title,
            btns_disabled,
            $form,
            o,
            responseSuccess,
            currentSlide = -1,
            currentElement = -1,
            currentSlideType,
            layerClipboard = [],
            selectedElements = [],
            animateCSSName,
            previewSlilderInstance,
            sliderOptionsChanged = false,
            colorPickers = [],
            listOfDropdownsInEditor;

        $(".slide-settings-tabs-wrapper").tabs();
        $(".element-settings-tabs-wrapper").tabs();
        $(".style-font-wrapper").tabs();

        $(".dashicons-tablet").tipsy({ gravity: "n", opacity: 1, title: function() { return "This feature is available in PRO version!" } });
        $(".dashicons-smartphone").tipsy({ gravity: "n", opacity: 1, title: function() { return "This feature is available in PRO version!" } });
        $(".STX-elements-item-iframe").tipsy({ gravity: "w", opacity: 1, title: function() { return "This feature is available in PRO version!" } });
        $(".STX-elements-item-iframe").css({
            'opacity':'0.5',
            'cursor':'not-allowed'
        });
        $(".device-desktop").css("opacity", "1");

        $(".accordion").accordion({
            heightStyle: "content",
            animate: false,
            collapsible: true,
            icons: false
        });

        $(".element-settings-tabs-wrapper").on( "tabsactivate", updateAccordion );

        $(".property-description").tipsy({ gravity: "w", opacity: 1 });

        function initTextEditor(content) {
            wp.editor.initialize("text-content", {
                tinymce: {
                    setup: function(editor) {
                        editor.on("change keyup paste", function(e) {
                            var el = getCurrentElement();
                            el.content = wp.editor.getContent("text-content");
                            updateCurrentElement("content");
                        });
                        editor.on("init", function(e) {
                            editor.setContent(content);
                        });
                    }
                },
                quicktags: true
            });
        }
        window.options = jQuery.parseJSON(json_str);

        function showLoader() {
            $loader.show();
        }

        function hideLoader() {
            $loader.hide();
        }

        function convertStrings(obj) {
            $.each(obj, function(key, value) {
                if (typeof value == "object" || typeof value == "array") {
                    convertStrings(value);
                } else if (!isNaN(value)) {
                    if (obj[key] === "") delete obj[key];
                    else if (typeof obj[key] != "boolean") obj[key] = Number(value);
                } else if (value === "true") {
                    obj[key] = true;
                } else if (value === "false") {
                    obj[key] = false;
                }
            });
        }

        convertStrings(options);

        options.slides &&
            options.slides.forEach(function(slide) {
                if (slide.layerWidth && !options.layerWidth) options.layerWidth = slide.layerWidth;
                if (slide.layerHeight && !options.layerHeight) options.layerHeight = slide.layerHeight;
            });

        options.layerWidth = options.layerWidth || "100%";
        options.layerHeight = options.layerHeight || "100%";
        options.shadow = options.shadow || "off";

		if(options.fullscreenTablet == undefined) options.fullscreenTablet = options.fullscreen;
		if(options.fullscreenMobile == undefined) options.fullscreenMobile = options.fullscreen;
		if(options.forceFullscreenMobile == undefined) options.forceFullscreenMobile = options.forceFullscreen;
		if(options.forceFullscreenTablet == undefined) options.forceFullscreenTablet = options.forceFullscreen;
		if(options.widthMobile == undefined) options.widthMobile = options.width;
		if(options.widthTablet == undefined) options.widthTablet = options.width;
		if(options.heightMobile == undefined) options.heightMobile = options.height;
		if(options.heightTablet == undefined) options.heightTablet = options.height;
		if(options.responsiveMobile == undefined) options.responsiveMobile = options.responsive;
		if(options.responsiveTablet == undefined) options.responsiveTablet = options.responsive;
		if(options.forceResponsiveMobile == undefined) options.forceResponsiveMobile = options.forceResponsive;
		if(options.forceResponsiveTablet == undefined) options.forceResponsiveTablet = options.forceResponsive;

        options.lightboxMode = options.lightboxMode || {};

        if(typeof options.lightboxModeMobile == "undefined") {
			options.lightboxModeMobile = {
				enable: options.lightboxMode.enable,
				text: options.lightboxMode.text,
				fontColor: options.lightboxMode.fontColor,
				hoverColor: options.lightboxMode.hoverColor,
				fontFamily: options.lightboxMode.fontFamily,
				fontSize: options.lightboxMode.fontSize
			}
		}
		if(typeof options.lightboxModeTablet == "undefined") {
			options.lightboxModeTablet = {
				enable: options.lightboxMode.enable,
				text: options.lightboxMode.text,
				fontColor: options.lightboxMode.fontColor,
				hoverColor: options.lightboxMode.hoverColor,
				fontFamily: options.lightboxMode.fontFamily,
				fontSize: options.lightboxMode.fontSize
			}
		}

        $form = $("#slider-options-form");

        $(".STX-button-modal-save").click(function() {
            $(".slider-save-btn").click();
            unfocusLayerElement();
        });

        $form.submit(submitCallback);

        function onElementSettingChanged(newChanges) {
            sliderOptionsChanged = newChanges;
            if (newChanges) {
                $(".STX-button-modal-save").attr("disabled", false);
                $(".STX-button-modal-close").css("background-color", "#fc4a1a");
            } else {
                $(".STX-button-modal-save").attr("disabled", true);
                $(".STX-button-modal-close").css("background-color", "#6d7a83");
            }
        }

        function submitCallback(e) {
            e.preventDefault();

            if (btns_disabled) return;
            onElementSettingChanged(false);
            enableButtons();

            var o = JSON.parse(JSON.stringify(options));
            o.slides.forEach(function(slide) {
                if (slide.elements)
                    slide.elements.forEach(function(element) {
                        delete element.$node;
                        delete element.node;
                        delete element.id;
                        delete element.index;
                    });
            });

            function deleteEmptyStrings(obj) {
                for (var key in obj) {
                    if (typeof obj[key] === "object") {
                        if (obj[key] === null || obj[key].length === 0) delete obj[key];
                        else obj[key] = deleteEmptyStrings(obj[key]);
                    } else if (obj[key] === "") delete obj[key];
                }
                return obj;
            }

            o = deleteEmptyStrings(o);

            var slider = JSON.stringify(o);

            showLoader();

            $.ajax({
                type: "POST",
                url: $form.attr("action"),
                data: {
                    id: options.id,
                    status: options.status,
                    slider: slider,
                    security: window.data.stx_nonce,
                    action: "transitionslider_save"
                },
                success: function(data, textStatus, jqXHR) {
                    changeSliderHeader($("input[name=instanceName]").val());
                    showNotification("success", msgSuccess);
                    responseSuccess = true;
                    hideLoader();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    showNotification("error", msgError);
                    hideLoader();
                }
            });
        }

        $(".slide-settings-main-header-left-button").click(function() {
            unfocusLayerElement();
            $(".slide-tab-slide a").click();
        });

        $(".slide-settings-main-header-right-button").click(function() {
            unfocusLayerElement();
            $(".slide-tab-layer a").click();
        });

        $(".STX-footer-layer-btn").click(function() {
            toggleLayerListPopup();
        });

        $(".STX-preview-btn-wrapper").click(function(e) {
            if ($(this).hasClass("btn-disabled")) return;
            $("#preview-slider-modal").show();
			previewModal = true;

            $("body").css("overflow", "hidden");

            enableButtons();

            var o = JSON.parse(JSON.stringify(options));

            if (o.navigation && !o.navigation.enable) o.navigation = false;
            if (o.wheelNavigation && !o.wheelNavigation.enable) o.wheelNavigation = false;
            if (o.pagination && !o.pagination.enable) o.pagination = false;
            if (o.thumbs && !o.thumbs.enable) o.thumbs = false;
            if (o.keyboard && !o.keyboard.enable) o.keyboard = false;
            if (o.autoplay && !o.autoplay.enable) o.autoplay = false;
            if (o.shadow && o.shadow == "off") o.shadow = null;
            if (o.initialSlide) o.initialSlide = parseInt(o.initialSlide);
            o.hashNavigation = false;
			o.forceResponsive = false;
            o.forceResponsiveMobile = false;
            o.forceResponsiveTablet = false;
			o.forceFullscreen = false;

            for (var key in o.slides) {
                if (o.slides[key].elements) {
                    for (var key2 in o.slides[key].elements) {
                        delete o.slides[key].elements[key2].node;
                    }
                }
                o.slides[key].urlTarget = o.slides[key].urlTarget == true || o.slides[key].urlTarget == "_blank" ? "_blank" : "_self";
            }

            $("#slider-preview-container")
                .empty()
                .append('<div class="sp">');

            previewSlilderInstance = $(".sp").transitionSlider(o);
        });

        changeSliderHeader(options.instanceName);

        function changeSliderHeader(heading) {
            if (options) options.instanceName ? (title = heading) : (title = "Add New Slider");
            $(".btn-slider-name").text(title);
        }

        function setDefaultValue(name, value) {
            options[name] = options[name] || value;
        }

        addOption("slides", "", "slidesArea");
        addOption("publish", "", "publishArea");

        addOption("general-settings", "instanceName", "text", "Slider name", "", "");
        addOption("general-settings", "mode", "dropdown", "Transition type", "webgl", ["webgl", "css"], "");
        addOption("general-settings", "initialSlide", "text", "Initial slide", "0", "");
        addOption("general-settings", "shadow", "radio", "Slider shadow", "off", ["off", "effect1", "effect2", "effect3", "effect4", "effect5", "effect6"], "", "");
        addOption("general-settings", "grabCursor", "checkbox", "Grab cursor", true, "", "", "");
        addOption("general-settings", "stopOnLastSlide", "checkbox", "Stop on last slide", false, "", "");
        addOption("general-settings", "showSlidesRandomOrder", "checkbox", "Display slides in random order", false, "", "");
        addOption("general-settings", "overlay", "color", "Overlay color (between layer and background)", "", "", "");
        addOption("general-settings", "parallax", "text", "Parallax factor (between 0 and 1)", "", "", "");
        addOption("general-settings", "invertColorSelectors", "text", "CSS selector for Menu (used to change menu colors on slide change)", "", "", "");
        addOption("general-settings", "preloadFirstSlide", "checkbox", "Preload first slide", false, "", "", "");


        addOption("size", "width", "textWithUnit", "Width", "1000", "px", "", "", "desktop");
        addOption("size", "height", "textWithUnit", "Height", "550", "px", "", "", "desktop");
        addOption("size", "responsive", "checkbox", "Responsive height", true, "", "hasSubitem", "", "desktop");
		addOption("size", "ratio", "text", "Aspect ratio (width / height)", "2", "", "isSubitem", "", "desktop");
        addOption("size", "forceResponsive", "checkbox", "Full width", false, "", "", "", "desktop");
		addOption("size", "fullscreen", "checkbox", "Fullscreen", false, "", "", "", "desktop");
        addOption("layer", "layerStarOnTransitionStart", "checkbox", "Layer start on transition start", false, "", "");

        addOption("layer", "layerWidth", "textWithUnit", "Width", "", ["px", "%"], "", "", "desktop");
        addOption("layer", "layerWidthMin", "textWithUnit", "Min Width", "", ["px", "%"], "", "", "desktop");
        addOption("layer", "layerWidthMax", "textWithUnit", "Max Width", "", ["px", "%"], "", "", "desktop");
        addOption("layer", "layerHeight", "textWithUnit", "Height", "", ["px", "%"], "", "", "desktop");
        addOption("layer", "layerHeightMin", "textWithUnit", "Min Height", "", ["px", "%"], "", "", "desktop");
        addOption("layer", "layerHeightMax", "textWithUnit", "Max Height", "", ["px", "%"], "", "", "desktop");
        addOption("autoplay", "autoplay.enable", "checkbox", "Enable", false, "", "");
        addOption("autoplay", "autoplay.delay", "textWithUnit", "Delay between transitions", 3000, "ms", "");
		addOption("autoplay", "autoplay.progress", "checkbox", "Show autoplay progress", false, "");
        addOption("autoplay", "autoplay.pauseOnHover", "checkbox", "Pause on mouse hover", false, "", "");
        addOption("autoplay", "autoplay.reverseDirection", "checkbox", "Reverse direction", false, "");

        addOption("buttons", "buttons.pauseVisible", "checkbox", "Pause button", false, "", "", "");
        addOption("buttons", "buttons.muteVisible", "checkbox", "Mute button", false, "", "", "");
        addOption("buttons", "resetVideos", "checkbox", "Reset video on slide change", false, "", "", "");
        addOption("buttons", "videoAutoplay", "checkbox", "Autoplay video", true, "", "", "");

        addOption("arrows", "navigation", "textOnly", "Arrows", "", "", "hasSubitem", "");
        addOption("arrows", "navigation.enable", "checkbox", "Enable", true, "", "isSubitem", "");
        addOption("arrows", "navigation.style", "radio", "Style", "effect4", ["effect1", "effect2", "effect3", "effect4", "effect5", "effect6", "effect7", "effect8", "effect9", "effect10"], "isSubitem", "hasPreview");
        addOption("arrows", "navigation", "textOnly", "Style", "", "", "hasSubitem", "");
        addOption("arrows", "navigation.color", "color", "Color", "", "", "isSubitem", "");
        addOption("arrows", "navigation.backgroundColor", "color", "Background", "", "", "isSubitem", "");
        addOption("arrows", "navigation.borderRadius", "textWithUnit", "Border radius", "", ["px", "%"], "isSubitem", "");
        addOption("arrows", "navigation.boxShadow", "textWithUnit", "Box shadow", "", "CSS value", "isSubitem", "");
        addOption("arrows", "navigation.backgroundSize", "textWithUnit", "Background size", "", "CSS value", "isSubitem", "");

        addOption("arrows", "navigation", "textOnly", "Style hover", "", "", "hasSubitem", "");
        addOption("arrows", "navigation.backgroundColorHover", "color", "Background", "", "", "isSubitem", "");
        addOption("arrows", "navigation.borderRadiusHover", "textWithUnit", "Border radius", "", ["px", "%"], "isSubitem", "");
        addOption("arrows", "navigation.boxShadowHover", "textWithUnit", "Box shadow", "", "CSS value", "isSubitem", "");
        addOption("arrows", "navigation.backgroundSizeHover", "textWithUnit", "Background size", "", "CSS value", "isSubitem", "");

        addOption("arrows", "keyboard", "textOnly", "Keyboard", "", "", "hasSubitem", "");
        addOption("arrows", "keyboard.enable", "checkbox", "Enable", true, "", "isSubitem", "");

        addOption("wheel-navigation", "wheelNavigation.enable", "checkbox", "Enable", false, "", "");
        addOption("wheel-navigation", "wheelNavigation.stopOnLast", "checkbox", "Stop on last slide", false, "");
        addOption("wheel-navigation", "wheelNavigation.interval", "textWithUnit", "Delay between wheel events", 2000, "ms", "");

        addOption("pagination", "pagination.enable", "checkbox", "Pagination", true, "", "hasSubitem", "");
        addOption("pagination", "pagination.style", "radio", "Style", "effect2", ["effect1", "effect2", "effect3", "effect4", "effect5", "effect6"], "isSubitem", "");

        addOption("pagination", "pagination.clickable", "checkbox", "Clickable", true, "", "isSubitem", "");
        addOption("pagination", "pagination.dynamicBullets", "checkbox", "Dynamic", false, "", "isSubitem", "");

        addOption("pagination", "pagination", "textOnly", "Style", "", "", "hasSubitem", "");
        addOption("pagination", "pagination.backgroundColor", "color", "Background", "", "", "isSubitem", "");
        addOption("pagination", "pagination.borderRadius", "textWithUnit", "Border radius", "", ["px", "%"], "isSubitem", "");
        addOption("pagination", "pagination.boxShadow", "textWithUnit", "Box shadow", "", "CSS value", "isSubitem", "");
        addOption("pagination", "pagination.opacity", "text", "Opacity", "", "", "isSubitem", "");
        addOption("pagination", "pagination.width", "textWithUnit", "Width", "", ["px", "%"], "isSubitem", "");
        addOption("pagination", "pagination.height", "textWithUnit", "Height", "", ["px", "%"], "isSubitem", "");
        addOption("pagination", "pagination.border", "textWithUnit", "Border", "", "CSS value", "isSubitem", "");

        addOption("pagination", "pagination", "textOnly", "Style active", "", "", "hasSubitem", "");
        addOption("pagination", "pagination.backgroundColorActive", "color", "Background", "", "", "isSubitem", "");
        addOption("pagination", "pagination.borderRadiusActive", "textWithUnit", "Border radius", "", ["px", "%"], "isSubitem", "");
        addOption("pagination", "pagination.boxShadowActive", "textWithUnit", "Box shadow", "", "CSS value", "isSubitem", "");
        addOption("pagination", "pagination.opacityActive", "text", "Opacity", "", "", "isSubitem", "");
        addOption("pagination", "pagination.widthActive", "textWithUnit", "Width", "", ["px", "%"], "isSubitem", "");
        addOption("pagination", "pagination.heightActive", "textWithUnit", "Height", "", ["px", "%"], "isSubitem", "");
        addOption("pagination", "pagination.borderActive", "textWithUnit", "Border", "", "CSS value", "isSubitem", "");

        addOption("pagination", "pagination", "textOnly", "Style hover", "", "", "hasSubitem", "");
        addOption("pagination", "pagination.backgroundColorHover", "color", "Background", "", "", "isSubitem", "");
        addOption("pagination", "pagination.borderRadiusHover", "textWithUnit", "Border radius", "", ["px", "%"], "isSubitem", "");
        addOption("pagination", "pagination.boxShadowHover", "textWithUnit", "Box shadow", "", "CSS value", "isSubitem", "");
        addOption("pagination", "pagination.opacityHover", "text", "Opacity", "", "", "isSubitem", "");
        addOption("pagination", "pagination.widthHover", "textWithUnit", "Width", "", ["px", "%"], "isSubitem", "");
        addOption("pagination", "pagination.heightHover", "textWithUnit", "Height", "", ["px", "%"], "isSubitem", "");
        addOption("pagination", "pagination.borderHover", "textWithUnit", "Border", "", "CSS value", "isSubitem", "");

        addOption("thumbs", "thumbs.enable", "checkbox", "Enable", false, "", "", "");
        addOption("thumbs", "thumbs.position", "dropdown", "Position", "bottom", ["bottom", "top", "left", "right"], "");
        addOption("thumbs", "thumbs.thumbWidth", "textWithUnit", "Thumbnail width", "100", "px", "");
        addOption("thumbs", "thumbs.thumbHeight", "textWithUnit", "Thumbnail height", "60", "px", "");
        addOption("thumbs", "thumbs.spaceAround", "textWithUnit", "Space around thumbnails", "3", "px", "");
        addOption("thumbs", "thumbs.spaceBetween", "textWithUnit", "Space between thumbnails", "3", "px", "");
        addOption("thumbs", "thumbs.background", "color", "Background", "", "", "", "");
        addOption("thumbs", "thumbs.outsideSlider", "checkbox", "Outside slider", true, "", "", "");
        addOption("thumbs", "thumbs.centered", "checkbox", "Centered", true, "", "", "");

        addOption("hash-navigation", "hashNavigation.enable", "checkbox", "Enable", false, "", "", "");

        addOption("loading", "loading.fadeEffect", "checkbox", "Fade in/out effect", true, "", "", "");
        addOption("loading", "loading.backgroundColor", "color", "Background color", "#ffffff", "", "");
        addOption("loading", "loading.color", "color", "Color", "#262626", "", "");
        addOption("loading", "loading.style", "radio", "Style", "style2", ["style1", "style2", "style3", "style4"], "", "hasPreview");

        addOption("lightbox", "lightbox.backgroundColor", "color", "Background color", "rgba(0, 0, 0, 0.95)", "", "");
        addOption("lightbox", "lightbox.closeColor", "color", "Close button color", "#ffffff", "", "");

        function addOption(section, name, type, desc, defaultValue, values, subItemType, hasPreview, hasClass) {
            var table = $("#slider-options-" + section + "");
            var tableBody = table.find("tbody");
            var row = $('<tr valign="top"  class="field-row"></tr>').appendTo(tableBody);
            var th = $('<th scope="row" class="STX-th-label">' + desc + "</th>").appendTo(row);
            var name1 = name.split(".")[0];
            var name2 = name.split(".")[1];
            var val;

            if (subItemType) $(th).addClass(subItemType);
            if (hasClass)
                $(th)
                    .parent()
                    .attr("data-type", hasClass);

            if (name2 && options.hasOwnProperty(name1) && options[name1].hasOwnProperty(name2)) val = options[name1][name2];
            else if (!name2 && options.hasOwnProperty(name1)) val = options[name1];
            else {
                val = defaultValue;
                if (name2) {
                    options[name1] = options[name1] || {};
                    options[name1][name2] = val;
                } else options[name1] = val;
            }

            switch (type) {
                case "text":
                    var td = $('<td class="STX-element"><div class="STX-form-element-text STX-element-num  STX-text-has-unit  STX-input-border-radius"></div></td>').appendTo(row);
                    var input = $('<input class="inputField" type="text" name="' + name + '"/>').appendTo(td.children());
                    input.attr("value", val);
                    break;

                case "textWithUnit":
                    var td = $('<td class="STX-element"><div class="STX-form-element-text STX-element-num  STX-text-has-unit  STX-input-border-radius"></div></td>').appendTo(row);
                    if(typeof values == 'string')
                        var input = $('<input class="inputField" type="text" name="' + name + '"/><div class="STX-text-unit STX-unit-font">' + values + "</div>").appendTo(td.children());
                    else{
                        var unit = 'px'
                        values.forEach( function(u) {
                            if(String(val).includes(u)){
                                val = val.replace(u, '')
                                unit = u
                            }
                        });
                        var input = $('<input class="inputField" type="text" name="' + name + '"/><div class="STX-text-unit STX-unit-font STX-has-units" data-units="'+values.join(',')+'" data-unit="'+unit+'"></div>').appendTo(td.children());
                    }
                    input.attr("value", val);
                    break;

                case "textOnly":
                    var td = $('<td class="STX-element"></div></td>').appendTo(row);
                    break;

                case "color":
                    var td = $('<td class="STX-td-element"></td>').appendTo(row);

                    var input = document.createElement("input");
                    input.type = "text";
                    input.className = "STX-input";
                    input.setAttribute("data-alpha", "true");
                    input.setAttribute("name", name);
                    var $input = $(input).appendTo(td);
                    $input.attr("value", val);

                    createColorPickerElement($input, val);

                    break;

                case "textarea":
                    var td = $('<td class="STX-td-element"></td>').appendTo(row);
                    var textarea = $('<textarea class="STX-input" type="text" name="' + name + '" cols=45" rows="1"></textarea>').appendTo(td);
                    textarea.attr("value", val);
                    break;

                case "checkbox":
                    var td = $('<td class="STX-td-element"></td>').appendTo(row);
                    var inputHidden = $('<input class="STX-input" type="hidden" name="' + name + '" value="false"/>').appendTo(td);
                    var input = $('<div class="STX-onoffswitch"><input type="checkbox" name="' + name + '" value="true" class="STX-onoffswitch-checkbox" id="' + name + '"><label class="STX-onoffswitch-label" for="' + name + '"><span class="STX-onoffswitch-inner"></span><span class="STX-onoffswitch-switch"></span></label></div>').appendTo(td);
                    input.attr("value", val);
                    input.find("input").prop("checked", val);

                    break;

                case "dropdown":
                    var td = $('<td class="STX-td-element"></td>').appendTo(row);

                    var dropdown = $('<div class="dropdown STX-edit-dropdown btns-dashboard-nav"><div class="select"><span></span><i aria-hidden="true" class="fa fa-chevron-down"></i><input type="hidden" name="' + name + '"></div></div>').appendTo(td);
                    var ul = $('<ul class="dropdown-menu STX-edit-dropdown-menu"></ul>').appendTo(dropdown);

                    for (var i = 0; i < values.length; i++) {
                        var li = $("<li>" + values[i] + "</li>")
                            .appendTo(ul)
                            .click(function() {
                                dropdown.find("span").text($(this).text());
                                dropdown
                                    .find("input")
                                    .val($(this).text())
                                    .trigger("change");
                            });
                    }

                    dropdown.find("span").text(val);
                    dropdown.find("input").attr("value", val);

                    break;

                case "radio":
                    var td = $('<td class="STX-td-element"></td>').appendTo(row);
                    name2 ? td.addClass(name1 + "-" + name2) : td.addClass(name1);

                    var inputHidden = $('<input class="STX-input" type="hidden" name="' + name + '" value="' + val + '"/>').appendTo(td);

                    for (var i = 0; i < values.length; i++) {
                        var $item = $('<div id="' + values[i] + '" class="item"><div class="inner ' + values[i] + '"></div></div>');

                        td.append($item);

                        if (values[i] == val) $item.addClass("selected");

                        $item.click(function() {
                            td.find(".selected").removeClass("selected");
                            $(this).addClass("selected");
                            inputHidden.attr("value", this.id).trigger("change");
                        });
                    }
                    break;

                case "publishArea":
                    tableBody.empty();
                    $(
                        '<div class="STX-publish-content">' +
                            '<div class="STX-publish-title STX-admin">Shortcode</div>' +
                            '<div class="STX-publish-text STX-admin">Copy and paste this shortcode into your posts or pages:</div>' +
                            '<div class="STX-STX-publish-shortcode">' +
                            '<div class="STX-publish-table">' +
                            '<input type="text" id="STX-shortcode-left" readonly>' +
                            '<div id="' + options.id + '" title="Copy shortcode" id="1" class="STX-shortcode-right STX-btn-copy-shortcode">COPY</div>' +
                            "</div>" +
                            "</div>" +
                            "</div>"
                    ).appendTo(tableBody);
                    $("#STX-shortcode-left").val('[transitionslider id="' + options.id + '"]');
                    $("#STX-shortcode-left").keypress(function(e) {
                        e.preventDefault();
                    });
                    break;

                case "slidesArea":
                    tableBody.empty();
                    $('<div class="STX-slides-content">' +
								'<div class="STX-slides-container ui-sortable">' +
                                    '<div class="STX-edit-slides-box STX-rect STX-h3 STX-uc">' +
                                        '<div class="STX-edit-slides-box-small-image-slide STX-create STX-btn STX-btn-l STX-button-green STX-radius-global STX-uc STX-h3">' +
                                            '<a class="add-slides-button " data-uploader-title="Add slide to slider" data-uploader-button-text="Add slide">ADD NEW SLIDE</a>' +
                                        '</div>' +
                                    '</div>' +
								'</div>' +
                            "</div>" +
						"</div>"
                    ).appendTo(tableBody);
                    break;
            }
        }

        function createColorPickerElement(jQueryInputElement, value, name) {
            jQueryInputElement.hide();

            var colorElement = document.createElement("div");
            colorElement.className = "STX-color-picker";
            $(colorElement).insertAfter(jQueryInputElement);
            var pickerButton = document.createElement("div");
            $(pickerButton).appendTo(colorElement);

            var pickr = new Pickr({
                el: pickerButton,
                useAsButton: false,
                closeWithKey: "Escape",
                theme: "monolith",
                position: "bottom-start",
                defaultRepresentation: "HEX",
                padding: 8,
                components: {
                    opacity: true,
                    hue: true,

                    interaction: {
                        input: true,
                        clear: true
                    }
                }
            })
                .on("init", function() {
                    jQueryInputElement.val(value);
                    if (value) pickr.setColor(value);
                    else pickr.setColor(null);
                    jQueryInputElement.trigger("change");
                })
                .on("change", function() {
                    jQueryInputElement.val(
                        pickr
                            .getColor()
                            .toHEXA()
                            .toString(0)
                    );
                    pickr.applyColor();
                    jQueryInputElement.trigger("change");
                })
                .on("clear", function() {
                    jQueryInputElement.val("");
                    jQueryInputElement.trigger("change");
                });
            if (name){
                colorPickers.push({
                    name: name,
                    pickr: pickr,
                    el: colorElement
                })
            }
        }

        $(".STX-edit-dropdown").click(function() {
            $(this)
                .attr("tabindex", 1)
                .focus();
            $(this).toggleClass("active");
            $(this)
                .find(".STX-edit-dropdown-menu")
                .slideToggle(300);
        });
        $(".STX-edit-dropdown").focusout(function() {
            $(this).removeClass("active");
            $(this)
                .find(".STX-edit-dropdown-menu")
                .slideUp(300);
        });
        $(".STX-edit-dropdown .STX-edit-dropdown-menu li").click(function() {
            $(this)
                .parents(".dropdown")
                .find("span")
                .text($(this).text());
            $(this)
                .parents(".dropdown")
                .find("input")
                .attr("value", $(this).text());
            $(this)
                .parents(".dropdown")
                .find("input")
                .attr("selected", "true");
        });
        $(".STX-edit-dropdown-menu li").click(function() {
            var getVal = $(this)
                .parents(".STX-edit-dropdown")
                .find("input")
                .val();
        });


        $('select[name="onClick.type"]').on("change keyup", function() {
            updateOnClickActionType($(this).val());
        });

        $("body").click(function(e) {
            var target = $(e.target);
            var formElementText = $(".STX-form-element-text");

            if (target.hasClass("inputField")) {
                if (formElementText.hasClass("focus")) formElementText.removeClass("focus");

                target.parent().addClass("focus");
                $(".STX-text-has-unit")
                    .find("STX-text-unit")
                    .addClass("focus");
                target.addClass("focus");
            } else {
                if (formElementText.hasClass("focus")) formElementText.removeClass("focus");
            }
        });

        if (options.slides) {
            enableButtons();

            for (var i = 0; i < options.slides.length; i++) {
                slide = options.slides[i];
                slidesWrapper = $("#STX-images-wrapper");

                createSlidesHtml(i, slide.thumbSrc || slide.src);
            }
            counterForSlides = $(".slide-item").length;
        } else {
            btns_disabled = true;
        }

        function onSlideReorder() {
            var newSlides = [];
            var arr = $(".slide-item").each(function(key, val) {
                newSlides[key] = options.slides[Number(val.id)];
            });

            arr.each(function(key, val) {
                val.id = key;
            });

            options.slides = newSlides;
        }

        function makeSortable() {
            $(".tabs").tabs();

            $(".ui-sortable").sortable({
                items: ".slide-item",
                opacity: 0.6,
                stop: function(event, ui) {
                    onSlideReorder();
                },
                start: function(event, ui) {}
            });
        }

        function updateOnClickActionType(val) {
            $(".on-click-type").hide();
            $("." + val).show();
        }

		var fonts = [];

        $.getJSON("https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyBmNd8k2DDxBqmU2d4B9AbPDSHfR12DJ6c", function(response) {
            for (var key in response.items) {
				fonts.push(response.items[key].family)
            }
			var fontselectOptions = { fonts: fonts }
			$('#font').fontselect(fontselectOptions);
        });

        var $editSlideModal = $("#edit-slide-modal");
        var $editSlideModalBackdrop = $(".media-modal-backdrop");

        var $prev = $(".edit-media-header").find(".STX-previous-btn-wrapper");
        var $next = $(".edit-media-header").find(".STX-next-btn-wrapper");

        $next.click(function() {
            showNextSlide();
        });

        $prev.click(function() {
            showPrevSlide();
        });

        var $slider = $(".slider-preview-area").css("minWidth", "1024px");
        var $sliderPreviewContainer = $("#slider-preview-container");

        resizeLayers();

        var deviceType, oldDeviceType;
        var devices = ["desktop", "tablet", "mobile"];

        function showPrevSlide() {
            currentSlide += options.slides.length - 1;
            currentSlide = currentSlide % options.slides.length;
            showSlide(currentSlide);
        }

        function showNextSlide() {
            currentSlide++;
            currentSlide = currentSlide % options.slides.length;
            showSlide(currentSlide);
        }

        $(".slider-options-wrappper input").change(function() {
            if (this.name) {
                var arr = this.name.split(".");
                var val = this.type == "checkbox" ? this.checked : this.value;

                if (val && this.classList.contains("unit")) val += document.getElementsByClassName("unit-" + this.name)[0].innerText;

                if (arr.length == 2) {
                    if (typeof options[arr[0]] != "object") options[arr[0]] = {};
                    options[arr[0]][arr[1]] = val;
                } else options[this.name] = val;
            }
        });

        $(".slider-options-wrappper select").change(function() {
            if (this.name) {
                var arr = this.name.split(".");
                if (arr.length == 2) {
                    if (typeof options[arr[0]] != "object") options[arr[0]] = {};
                    options[arr[0]][arr[1]] = this.value;
                } else options[this.name] = this.value;
            }
        });

        $("#tabs-slide")
            .find("input")
            .change(function() {
                if (this.name && currentSlide > -1) {
                    var arr = this.name.split(".");
                    var val = this.type == "checkbox" ? this.checked : this.value;
                    if (arr.length == 2) {
                        if (typeof options.slides[currentSlide][arr[0]] != "object") options.slides[currentSlide][arr[0]] = {};
                        options.slides[currentSlide][arr[0]][arr[1]] = val;
                    } else options.slides[currentSlide][this.name] = val;
                }
            });

        $("#tabs-slide")
            .find("select")
            .change(function() {
                if (this.name && currentSlide > -1) {
                    var arr = this.name.split(".");
                    if (arr.length == 2) {
                        if (typeof options.slides[currentSlide][arr[0]] != "object") options.slides[currentSlide][arr[0]] = {};
                        options.slides[currentSlide][arr[0]][arr[1]] = this.value;
                    } else options.slides[currentSlide][this.name] = this.value;
                }
            });

        var webglTransitionSetttings = [
            $("#setting-effect"),
            $("#setting-direction"),
            $("#setting-distance"),
            $("#setting-brightness"),
            $("#setting-blur")
        ]

        function handleModeChange(){
            webglTransitionSetttings.forEach(function(element){
                options.mode == "css" ? element.hide() : element.show()
            })
        }

        handleModeChange()

        $('input[name="mode"]').on("change", function(){
            handleModeChange()
        })

        function resetTextarea() {
            $(".element-settings textarea").val("");
        }

        var transitionOptions = {
            slide: {
                direction: [{ name: "Left", value: "left" }],
                easing: [{ name: "Default", value: "" }, { name: "Slow", value: "slow" }, { name: "Elastic", value: "elastic" }]
            },

            roll: {
                direction: [{ name: "Left", value: "left" }, { name: "Right", value: "right" }, { name: "Top", value: "top" }, { name: "Bottom", value: "bottom" }, { name: "Top left", value: "topleft" }, { name: "Top right", value: "topRight" }, { name: "Bottom left", value: "bottomlreft" }, { name: "Bottom right", value: "bottomRight" }, { name: "Random", value: "" }],
                easing: [{ name: "Default", value: "" }, { name: "Slow", value: "slow" }, { name: "Elastic", value: "elastic" }]
            },

            crossfade: {
                direction: [
                    { name: "In", value: "in" },
                    { name: "Out", value: "out" },
                    ]
            },

            line: {
                direction: [
                    { name: "Left", value: "left1" },
                    ]
            },

            stretch: {
                direction: [{ name: "Left", value: "left" }, { name: "Right", value: "right" }, { name: "Top", value: "top" }, { name: "Bottom", value: "bottom" }, { name: "Random", value: "" }],
                easing: [{ name: "Default", value: "" }, { name: "Slow", value: "slow" }, { name: "Elastic", value: "elastic" }]
            },

            warp: {
                direction: [{ name: "Left", value: "left" }, { name: "Right", value: "right" }, { name: "Top", value: "top" }, { name: "Bottom", value: "bottom" }, { name: "Random", value: "" }],
                easing: [{ name: "Default", value: "" }, { name: "Slow", value: "slow" }, { name: "Elastic", value: "elastic" }]
            },

            zoom: {
                direction: [{ name: "In", value: "in" }, { name: "Out", value: "out" }, { name: "Random", value: "" }],
                easing: [{ name: "Default", value: "" }, { name: "Slow", value: "slow" }, { name: "Elastic", value: "elastic" }]
            },

            powerzoom: {
                direction: [{ name: "In", value: "in" }, { name: "Out", value: "out" }, { name: "Random", value: "" }],
                easing: [{ name: "Default", value: "" }, { name: "Slow", value: "slow" }, { name: "Elastic", value: "elastic" }]
            },

            flash: {
                easing: [{ name: "Default", value: "" }, { name: "Fast", value: "fast" }, { name: "Slow", value: "slow" }, { name: "Elastic", value: "elastic" }]
            },
            fade: {
                easing: [{ name: "Default", value: "" }, { name: "Fast", value: "fast" }, { name: "Slow", value: "slow" }, { name: "Elastic", value: "elastic" }]
            },

            twirl: {
                direction: [{ name: "Left", value: "left" }, { name: "Right", value: "right" }, { name: "Random", value: "" }],
                easing: [{ name: "Default", value: "" }, { name: "Slow", value: "slow" }, { name: "Elastic", value: "elastic" }]
            }
        };

        addDeleteAllListeners();
        addEditListeners();
        makeSortable();

        $(".add-slides-button").click(function(e) {
            e.preventDefault();

            if (file) file.close();

            file = wp.media.frames.file = wp.media({
                title: "Edit image / video",
                button: {
                    text: "Select"
                },
                multiple: true
            });

            file.on("select", function() {
                var arr = file.state().get("selection");
                var slides = new Array();

                var existingSlides = $(".slide-item").length;
                var names = new Array();

                $(".slide-item").each(function(i, obj) {
                    names.push(parseInt($(this).attr("id")));
                });

                options.slides = options.slides || [];

                for (var i = 0; i < arr.models.length; i++) {
                    var src = arr.models[i].attributes.url;
                    var thumbSrc = arr.models[i].attributes.sizes && arr.models[i].attributes.sizes.medium.url;

                    slides.push({
                        url: src,
                        id: i
                    });

                    options.slides[counterForSlides] = {
                        src: src,
                        thumbSrc: thumbSrc
                    };

                    createSlidesHtml(counterForSlides, src);

                    counterForSlides++;
                }

                enableButtons();

                onSlideReorder();
            });

            file.open();
        });

        function addEditListeners() {
            $(".STX-modal-close-preview").click(function(e) {
                closeModal();
            });

            $("#transitionEffect").change(function(e) {
                onElementSettingChanged(true);
                options.slides[currentSlide].transitionEffect = this.value;

                var dropdowns = ["direction", "easing"];

                for (var key in dropdowns) {
                    var val = dropdowns[key];
                    delete options.slides[currentSlide][val];
                    $("#setting-" + val).hide();
                }

                delete options.slides[currentSlide].brightness;
                delete options.slides[currentSlide].distance;
                delete options.slides[currentSlide].blur;
                $("#brightness").val("");
                $("#distance").val("");
                $("#blur").val("");

                var trOptions = transitionOptions[this.value];
                for (var key in trOptions) {
                    var dropdownId = key;
                    var $dropdown = $("#" + dropdownId).empty();
                    $("#setting-" + dropdownId).show();
                    var dropdownOptions = trOptions[key];
                    for (var key2 in dropdownOptions) {
                        var obj = dropdownOptions[key2];
                        $('<option value="' + obj.value + '">' + obj.name + "</option>").appendTo($dropdown);
                    }
                    options.slides[currentSlide][key] = dropdownOptions[0].value;
                }

                });

            $("#direction").change(function(e) {
                onElementSettingChanged(true);
                options.slides[currentSlide].direction = this.value;
            });

            $("#easing").change(function(e) {
                onElementSettingChanged(true);
                options.slides[currentSlide].easing = this.value;
            });

            $("#transitionDuration").change(function(e) {
                onElementSettingChanged(true);
                options.slides[currentSlide].transitionDuration = Number(this.value);
            });

            $("#distance").change(function(e) {
                onElementSettingChanged(true);
                options.slides[currentSlide].distance = Number(this.value);
            });

            $("#brightness").change(function(e) {
                onElementSettingChanged(true);
                options.slides[currentSlide].brightness = Number(this.value);
            });

            $("#blur").change(function(e) {
                onElementSettingChanged(true);
                options.slides[currentSlide].blur = Number(this.value);
            });

            $(".STX-back-btn-wrapper, .btn-slider-name, .STX-btn-dashboard-edit").click(function(e) {
                if (sliderOptionsChanged) {
                    var msg = confirm("Changes that you made may not be saved.");

                    if (msg == true) {
                        location.reload();
                    }
                } else {
                    $(".media-modal").hide();
                    currentSlide = -1;
                    unfocusLayerElement();
                    $("body").css("overflow", "auto");

                    $("video").each(function() {
                        $(this)
                            .get(0)
                            .pause();
                    });
                    addBreadcrumbActive(
                        $(".STX-admin")
                            .find(".btn-slider-name")
                            .addClass("STX-active")
                    );
                }
            });

            $(".media-modal-close").click(function(e) {
                closeModal();
                $("body").css("overflow", "auto");

                $("video").each(function() {
                    $(this)
                        .get(0)
                        .pause();
                });
            });

            $(".STX-slide-src").click(function(e) {
                e.preventDefault();

                var btn = $(this);

                if (file) file.close();

                file = wp.media.frames.file = wp.media({
                    title: "Edit image / video",
                    button: {
                        text: "Select"
                    },
                    library: { type: ["image", "video/MP4"] },
                    multiple: false
                });

                file.on("select", function() {
                    var attachment = file
                        .state()
                        .get("selection")
                        .first()
                        .toJSON();
                    var src = attachment.url;
                    var thumbSrc;

                    var img, video, type, ext;
                    if (/\.(jpg|jpeg|gif|png|webp)$/i.test(src)) {
                        (type = img), (ext = "img");
                        thumbSrc = "medium" in attachment.sizes ? attachment.sizes.medium.url : attachment.url;
                    } else if (/\.(mp4|ogg|ogv|webm)$/i.test(src)) {
                        (type = video), (ext = "video");
                    }

                    setSlideSrc(currentSlide, src, thumbSrc);
                    onElementSettingChanged(true);

                    showSlide(currentSlide);
                });

                file.open();
            });

            $(".STX-slide-thumbSrc").click(function(e) {
                e.preventDefault();

                if (file) file.close();

                file = wp.media.frames.file = wp.media({
                    title: "Select thumbnail image",
                    button: {
                        text: "Select"
                    },
                    library: { type: ["image"] },
                    multiple: false
                });

                file.on("select", function() {
                    var attachment = file
                        .state()
                        .get("selection")
                        .first()
                        .toJSON();
                    var attachmentUrl = attachment.url;

                    var img, type, ext;
                    if (/\.(jpg|jpeg|gif|png|webp)$/i.test(attachmentUrl)) {
                        (type = img), (ext = "img");
                    }

                    setSlideThumbSrc(currentSlide, attachmentUrl);
                    onElementSettingChanged(true);
                });

                file.open();
            });

            $(".slider-apply-btn-modal").click(function(e) {
                responseSuccess = false;
                var i = setInterval(function() {
                    if (responseSuccess) {
                        clearInterval(i);
                    }
                }, 200);
            });
        }

        function setSlideThumbSrc(index, src) {
            options.slides[index].thumbSrc = src;

            if (/\.(jpg|jpeg|gif|png|webp)$/i.test(src)) {
                $(".STX-slide-thumbnail-preview").css({
                    "background-image": 'url("' + src + '")',
                    "background-size": "cover"
                });
            }
        }

        function setSlideSrc(index, src, thumbSrc) {
            options.slides[index].src = src;

            if (thumbSrc) options.slides[index].thumbSrc = thumbSrc;

            if (/\.(jpg|jpeg|gif|png|webp)$/i.test(src)) {
                $(".STX-video-preview")
                    .eq(currentSlide)
                    .hide();
                $(".STX-image-preview")
                    .eq(currentSlide)
                    .show()
                    .attr("src", src);
            } else if (/\.(mp4|ogg|ogv|webm)$/i.test(src)) {
                $(".STX-video-preview")
                    .eq(currentSlide)
                    .show()
                    .attr("src", src);
                $(".STX-image-preview")
                    .eq(currentSlide)
                    .hide();
            }
        }

        function showSlide(index) {
            var slide = options.slides[index];
            var src = slide.src,
                type;
            var thumbSrc = slide.thumbSrc;

            if (/\.(jpg|jpeg|gif|png|webp)$/i.test(src)) {
                type = "img";
            } else if (/\.(mp4|ogg|ogv|webm)$/i.test(src)) {
                type = "video";
            }

            currentSlideType = type;

            $editSlideModal.show();

            unfocusLayerElement();

            $(".video-container").empty();

            $(".STX-slide-thumbnail-preview").css({
                "background-image": 'url("' + thumbSrc + '")',
                "background-size": "cover"
            });

            if (type == "img") {
                $slider[0].style.backgroundImage = 'url("' + src + '")';

                $(".STX-slide-src-preview")
                    .show()
                    .attr("src", src);
                $(".STX-slide-src-preview-video").hide();
            } else {
                $slider[0].style.backgroundImage = "none";
                var $vid = $('<video id="edit-slide-video" class="wp-video-shortcode" src="' + src + '" preload="metadata" controls style="width: 100% "></video>').appendTo($(".video-container"));

                $(".STX-slide-src-preview-video")
                    .show()
                    .attr("src", src);
                $(".STX-slide-src-preview").hide();

                $("#edit-slide-video")[0].onloadedmetadata = function() {
                    resizeLayers();
                };
            }

            removeBreadcrumbActive();

            $editSlideModal
                .find(".media-frame-title")
                .find(".btn-slide-name")
                .text("Slide " + String(parseInt(index) + 1));

            $editSlideModal
                .find('[data-setting="url"]')
                .find("input")
                .val(src);

            addBreadcrumbActive($editSlideModal.find(".btn-slide-name"));

            var dropdowns = ["direction", "easing"];

            for (var key in dropdowns) {
                var val = dropdowns[key];
                $("#setting-" + val).hide();
            }

            var slideOptions = options.slides[index];
            var trEffect = slideOptions.transitionEffect || "";
            $("#transitionEffect").val(trEffect);

            var trOptions = transitionOptions[slideOptions.transitionEffect];
            for (var key in trOptions) {
                var dropdownId = key;
                var $dropdown = $("#" + dropdownId).empty();
                $("#setting-" + dropdownId).show();
                var dropdownOptions = trOptions[key];
                for (var key2 in dropdownOptions) {
                    var obj = dropdownOptions[key2];
                    $('<option value="' + obj.value + '">' + obj.name + "</option>").appendTo($dropdown);
                }
            }

            $("#tabs-slide input")
                .val("")
                .prop("checked", false);
            $("#tabs-slide select").val("");

            if(!slideOptions.backgroundColor)
                setColorPicker("backgroundColor.slide", null)
            else
                setColorPicker("backgroundColor.slide", slideOptions.backgroundColor)

            for (var key in slideOptions) {
                var $el = $("#tabs-slide").find("#" + key);
                var val = slideOptions[key];
                typeof val == "boolean" ? $el.prop("checked", val) : $el.val(val);
            }

            var transitionEffect = slideOptions.transitionEffect || options.transitionEffect || "slide"
            $("#transitionEffect").val(transitionEffect).trigger("change")

            clearSlideElements();
            unfocusLayerElement();

            slide.elements = slide.elements || []
            slide.elements.forEach(function(element) {
                addNavigatorElement(element);
            });
            renderLayers();

            resizeLayers();
        }

        function removeBreadcrumbActive() {
            $(".STX-breadcrumb")
                .find(".STX-active")
                .removeClass("STX-active");
        }

        function addBreadcrumbActive(element) {
            $(element).addClass("STX-active");
        }

        function clearSlideElements() {
            $(".layer-item").remove();
        }

        function getSlideId(element) {
            return element
                .parent()
                .parent()
                .attr("id");
        }

        function closeModal() {
            $("#preview-slider-modal").fadeOut("fast", function() {});
			previewModal = false;

            if (!$.isEmptyObject($(".sp").data())) {
                slider = $(".sp").data("transitionSlider");
                slider.stopSlider();
            }
        }

        function addDeleteAllListeners() {
            btnDeleteAll.click(function(e) {
                if (btns_disabled) return;

                if (confirm("Delete all slides. Are you sure?")) {
                    $(".slide-item")
                        .animate(
                            {
                                opacity: 0
                            },
                            100
                        )
                        .slideUp(200, function() {
                            $(this).remove();
                        });

                    showNotification("warning", msgDeletedSlides);

                    removeSlides();
                }
            });
        }

        function removeSlides() {
            options.slides = [];
            counterForSlides = 0;

            $("[data-slide-name]").remove();
            $("[data-slide-options-name]").remove();
        }

        function enableButtons() {
            btns_disabled = false;

            $(".STX-main-table-wrapp").fadeIn("fast");

            $(".btn-disabled").removeClass("btn-disabled");
            $(".save-input-text-disabled").removeClass("save-input-text-disabled");
        }

        $(".STX-btn-menu").click(function(e) {
            if (
                $(this)
                    .parent()
                    .hasClass("STX-nav-active")
            )
                return;

            $(".STX-btn-menu")
                .parent()
                .removeClass("STX-nav-active");
            $(this)
                .parent()
                .addClass("STX-nav-active");

            $(".STX-form-tab").hide();
            $(".options_" + $(this).attr("data-form-name")).fadeIn("fast", function() {});
        });

        $(".STX-form-tab").hide();
        $(".options_slides").show();
        $('.STX-btn-menu[data-form-name="slides"]')
            .parent()
            .addClass("STX-nav-active");

        function createSlidesHtml(i, url) {
            var slideName = "Slide " + String(i + 1);

            var $slide = $(
                '<div title="Drag to reorder" id="' +
                    i +
                    '" class="STX-edit-slides-box STX-rect slide-item" data-slide-name="Slide ' +
                    i +
                    '">' +
                    '<img title="Sort"  class="STX-image-preview slide-preview" src="' +
                    url +
                    '">' +
                    '<video title="Sort"  class="STX-video-preview" slide-preview src="' +
                    url +
                    '"></video>' +
                    '<div class="STX-box-overlay-slides STX-box-on-hover-slides">' +
                    '<a name="' +
                    i +
                    '" class="STX-edit-link slide-settings STX-btn STX-button-green STX-radius-global STX-uc STX-h5" title="Edit">Edit</a>' +
                    '<div class="STX-slide-copy-btn-small btn-sm slide-duplicate" title="Duplicate"></div>' +
                    '<div class="STX-slide-trash-btn-small remove-image" name="' +
                    i +
                    '" title="Delete slide"></div>' +
                    "</div>" +
                    '<div class="STX-box-placeholder-slide" data-align="">' +
                    '<div class="STX-box-placeholder-title">' +
                    '<div class="STX-h4">' +
                    slideName +
                    "</div>" +
                    "</div>" +
                    "</div>" +
                    "</div>"
            );

            if (/\.(jpg|jpeg|gif|png|webp)$/i.test(url)) {
                $slide.find(".STX-video-preview").hide();
                $slide.find(".STX-image-preview").show();
            } else if (/\.(mp4|ogg|ogv|webm)$/i.test(url)) {
                $slide.find(".STX-video-preview").show();
                $slide.find(".STX-image-preview").hide();
            }

            $slide.insertBefore($(".STX-create").parent());

            $slide.find(".slide-settings").click(function(e) {
                currentSlide = parseInt(getSlideId($(this)));

                $('a[data-tab="tab-general"]').click();

                showSlide(currentSlide);

                $("body").css("overflow", "hidden");
            });

            $slide.find(".slide-duplicate").click(function(e) {
                currentSlide = parseInt(getSlideId($(this)));
                var slide = options.slides[currentSlide];
                var slideClone = JSON.parse(JSON.stringify(slide));
                options.slides.push(slideClone);

                createSlidesHtml(options.slides.length - 1, slideClone.src);

                onSlideReorder();
            });

            $slide.find(".remove-image").click(function() {
                $(this)
                    .parent()
                    .parent()
                    .animate(
                        {
                            opacity: 0
                        },
                        100
                    )
                    .slideUp(200, function() {
                        $(this).remove();
                        onSlideReorder();
                    });
            });

            enableButtons();
        }

        function showNotification(type, text) {
            $(".STX-saved-notification-wrapper")
                .stop()
                .slideUp(300);

            content.html(text);
            notificationContentEditSlide.text(text);

            $(".STX-saved-notification-wrapper")
                .stop()
                .slideDown("fast", function() {
                    $(this)
                        .delay(3000)
                        .slideUp(300);
                });

            content.attr("class", "STX-saved-notification-content");
            notificationContentEditSlide.attr("class", "STX-saved-notification-content STX-edit-slide-notification-content");

            switch (type) {
                case "success":
                    content.addClass("STX-saved-notification-success");
                    notificationContentEditSlide.addClass("STX-saved-notification-success");
                    break;
                case "error":
                    content.addClass("STX-saved-notification-error");
                    notificationContentEditSlide.addClass("STX-saved-notification-error");
                    break;
                case "warning":
                    content.addClass("STX-saved-notification-warning");
                    notificationContentEditSlide.addClass("STX-saved-notification-warning");
                    break;
            }
        }

        $(".STX-btn-copy-shortcode").click(function() {
            var inputShortcode = document.getElementById("STX-shortcode-left");
            inputShortcode.select();
            inputShortcode.setSelectionRange(0, 99999);
            document.execCommand("copy");

            $(this).text("COPIED!");
            $(this).addClass("STX-copy-shortcode-highlight");

            setTimeout(function() {
                $(".STX-btn-copy-shortcode").text("COPY");
                $(".STX-btn-copy-shortcode").removeClass("STX-copy-shortcode-highlight");
            }, 1000);
        });


        var $elementSettings = $editSlideModal.find(".element-settings");

        var elementSettings = {};
        $elementSettings.find("input").each(function(index, el) {
            elementSettings[el.name] = el;
        });
        $elementSettings.find("select").each(function(index, el) {
            elementSettings[el.name] = el;
        });
        $elementSettings.find("textarea").each(function(index, el) {
            elementSettings[el.name] = el;
        });

        var $elementSettingsHover = $editSlideModal.find(".element-settings-hover");

        var elementSettingsHover = {};
        $elementSettingsHover.find("input").each(function(index, el) {
            elementSettingsHover[el.name] = el;
        });
        $elementSettingsHover.find("select").each(function(index, el) {
            elementSettingsHover[el.name] = el;
        });
        $elementSettingsHover.find("textarea").each(function(index, el) {
            elementSettingsHover[el.name] = el;
        });

        var $textElementSettings = $editSlideModal.find(".text-el");
        var $headingElementSettings = $editSlideModal.find(".heading-el");
        var $imageElementSettings = $editSlideModal.find(".img-el");
        var $buttonElementSettings = $editSlideModal.find(".btn-el");
        var $videoElementSettings = $editSlideModal.find(".video-el");
        var $iframeElementSettings = $editSlideModal.find(".iframe-el");

        var $addTextButton = $editSlideModal.find(".add-text");
        var $addHeadingButton = $editSlideModal.find(".add-heading");
        var $addImageButton = $editSlideModal.find(".add-image");
        var $addButtonButton = $editSlideModal.find(".add-button");
        var $addVideoButton = $editSlideModal.find(".add-video");
        var $addIframeButton = $editSlideModal.find(".add-iframe");

        var renderLayersDisabled = false;

        function renderLayers() {
            if (renderLayersDisabled) return;

            var all = []
            var cur = []

            options.slides[currentSlide].elements.forEach(function(el){
                if(!el.static){
                    all.push(el)
                    cur.push(el)
                }
            })

            options.slides[currentSlide].elements.forEach(function(el){
                if(el.static){
                    cur.push(el)
                }
            })

            options.slides.forEach(function(slide, index){
				if(slide.elements){
					slide.elements.forEach(function(el){
						if(el.static ) {
							all.push(el)
						}
					})
				}
            })


            options.slides[currentSlide].elements = cur

            layerRenderer.render(all, deviceType);
        }

        function updateDeviceType() {
            layerRenderer.setDeviceType(deviceType);
        }

        function updateCurrentElement(settingName, hover) {
            onElementSettingChanged(true);
            layerRenderer.updateElement(getElement(currentElement), settingName, hover);
        }

        function updateElementOffset(offset) {
            selectedElements.forEach(function(index) {
                var el = getElement(index);
                var toUpdate = el;
                if (deviceType == "mobile") {
                    el.mobile = el.mobile || {};
                    toUpdate = el.mobile;
                } else if (deviceType == "tablet") {
                    el.tablet = el.tablet || {};
                    toUpdate = el.tablet;
                }

                var o = { x: offset.x, y: offset.y };
                if (!el) return;
                if (el.mode == "content") return;
                if (el.position.x == "right") o.x *= -1;
                if (el.position.y == "center" || el.position.y == "bottom") o.y *= -1;
                toUpdate.position = toUpdate.position || {};
                toUpdate.position.offsetX = Number(toUpdate.position.offsetX || 0);
                toUpdate.position.offsetY = Number(toUpdate.position.offsetY || 0);
                toUpdate.position.offsetX += o.x;
                toUpdate.position.offsetY += o.y;
                layerRenderer.updateElement(el, "position");
                updateElementSetting("position.offsetX", parseInt(toUpdate.position.offsetX));
                updateElementSetting("position.offsetY", parseInt(toUpdate.position.offsetY));
            });
        }

        function resizeLayers() {
            if (renderLayersDisabled) return;

            if (currentSlide < 0) return;

            var self = this;
            var o = options;

            if (o.fullscreen) {
                $slider.height("100%");
            } else if (o.responsive) {
                var w = $slider.width();

                var r = o.ratio;

                var h = o.height;

                var maxHeight = o.maxHeight;
                var minHeight = o.minHeight;

                if (r) h = w / r;
                if (maxHeight && h > maxHeight) h = maxHeight;
                if (minHeight && h < minHeight) h = minHeight;

                $slider.height(h);
            } else {
                $slider.height(o.height);
            }

            layerRenderer.updateLayerSize(options, currentSlide);
        }

        function renderAddedElement() {
            layerRenderer.renderAddedElement(options.slides[currentSlide].elements);
        }

        function updateElement() {
            layerRenderer.updateElement();
        }

        $(window).resize(function() {
            resizeLayers();
        });

        $elementSettings.find("input").on("change keyup paste", function(e) {
            if (currentElement >= 0) onElementSetingChange(this);
        });

        $elementSettings.find("select").change(function() {
            if (currentElement >= 0) onElementSetingChange(this);
        });

        $elementSettingsHover.find("input").on("change keyup paste", function(e) {
            if (currentElement >= 0) onElementSetingChange(this, true);
        });

        $elementSettingsHover.find("select").change(function() {
            if (currentElement >= 0) onElementSetingChange(this, true);
        });

        function onElementSetingChange(target, hover) {
            onElementSettingChanged(true);
            if (target.name) {
                var el = getCurrentElement();
                var toUpdate = el;
                if (deviceType == "mobile") {
                    el.mobile = el.mobile || {};
                    toUpdate = el.mobile;
                } else if (deviceType == "tablet") {
                    el.tablet = el.tablet || {};
                    toUpdate = el.tablet;
                }

                if (hover) {
                    toUpdate.hover = toUpdate.hover || {};
                    toUpdate = toUpdate.hover;
                }

                var val = target.type == "checkbox" ? target.checked : target.value;

                var unitIndex = hover ? 1 : 0;
                if (val && target.classList.contains("unit")) val += document.getElementsByClassName("unit-" + target.name)[unitIndex].innerText;

                if (target.name == "position.x")
                    $('input[name="position.offsetX"]')
                        .val("")
                        .trigger("change");
                if (target.name == "position.y")
                    $('input[name="position.offsetY"]')
                        .val("")
                        .trigger("change");

                if (target.name == "mode") {
                    $('input[name="position.offsetX"]')
                        .val("")
                        .trigger("change");
                    $('input[name="position.offsetY"]')
                        .val("")
                        .trigger("change");
                }

                var arr = target.name.split(".");
                if (arr.length == 2) {
                    if (typeof toUpdate[arr[0]] != "object") toUpdate[arr[0]] = {};
                    toUpdate[arr[0]][arr[1]] = val;
                } else {
                    toUpdate[target.name] = val;
                }
            }
            updateCurrentElement(target.name, hover);
            if (target.name == "mode" || target.name == "position.x" || target.name == "position.y" || target.name == "static") renderLayers();
        }

        $('textarea[name="customCSS"]').bind("change keyup paste", function(e) {
            var el = getCurrentElement();
            el.customCSS = $(this).val();
            updateCurrentElement("customCSS");
        });

        $('textarea[name="content"]').on("change keyup paste", function(e) {
            var el = getCurrentElement();
            el.content = $(this).val();
            updateCurrentElement("content");
        });

        $("#layerWidth").bind("change paste", function(e) {
            resizeLayers();
        });

        $("#layerHeight").bind("change paste", function(e) {
            resizeLayers();
        });

        $('select[name="mode"]').bind("change", function() {
        });

        $(".animateIt").click(function() {
            animateCSSName = $(this)
                .parent()
                .find("select")
                .val();

            animateIt();
        });

        $(".animateCSS").change(function() {
            animateCSSName = this.value;
        });

        function animateIt() {
            $('a[data-id="' + currentElement + '"]')
                .addClass("animated")
                .addClass(animateCSSName);

            setTimeout(function() {
                for (var i = 0; i < options.slides[currentSlide].elements.length; i++) {
                    $('a[data-id="' + i + '"]')
                        .removeClass("animated")
                        .removeClass(animateCSSName);
                }
            }, 1000);
        }

        function createTextElement() {
            var obj = {
                type: "text",
                mode: "content",
                content: "Text",
                htmlTag: "p",
                fontSize: "16",
                fontFamily: "",
                fontWeight: "normal",
                textColor: "#FFF",
                backgroundColor: "rgba(0,0,0,0)",
                borderRadius: 0,
                position: {
                    x: "center",
                    y: "center",
                    offsetX: 0,
                    offsetY: 0
                },
                typingAnimation: {
                    speed: 50,
                    loop: false,
                    loopDelay: 750,
                    cursor: true,
                    startDelay: 0,
                    freezeAt: 0,
                    unfreezeAfter: 0
                },
                startAnimation: {
                    animation: "fadeInUp",
                    speed: 500,
                    delay: 0
                },
                endAnimation: {
                    animation: "fadeOutUp",
                    speed: 500,
                    delay: 0
                }
            };

            addLayerElement(obj);

            resetTextarea();

            $(".element-settings-tabs-wrapper").show();
            $(".slide-settings-main-menu-title").text("Element Settings");
        }

        function createHeadingElement() {
            var obj = {
                type: "heading",
                mode: "content",
                content: "Heading",
                htmlTag: "h2",
                fontSize: "26",
                fontFamily: "",
                fontWeight: "normal",
                textColor: "#FFF",
                backgroundColor: "rgba(0,0,0,0)",
                borderRadius: 0,
                position: {
                    x: "center",
                    y: "center",
                    offsetX: 0,
                    offsetY: 0
                },
                typingAnimation: {
                    speed: 50,
                    loop: false,
                    loopDelay: 750,
                    cursor: true,
                    startDelay: 0,
                    freezeAt: 0,
                    unfreezeAfter: 0
                },
                startAnimation: {
                    animation: "fadeInUp",
                    speed: 500,
                    delay: 0
                },
                endAnimation: {
                    animation: "fadeOutUp",
                    speed: 500,
                    delay: 0
                }
            };

            addLayerElement(obj);

            resetTextarea();

            $(".element-settings-tabs-wrapper").show();
            $(".slide-settings-main-menu-title").text("Element Settings");
        }
        function createButtonElement() {
            var obj = {
                type: "button",
                content: "Button",
                fontSize: 16,
                fontFamily: "Poppins",
                fontWeight: 600,
                textColor: "#ffffff",
                borderRadius: 0,
                position: {
                    x: "center",
                    y: "center",
                    offsetX: 0,
                    offsetY: 0
                },
                startAnimation: {
                    animation: "fadeInUp",
                    speed: 1200,
                    delay: 600
                },
                endAnimation: {
                    animation: "fadeOut",
                    speed: 1000
                },
                paddingTop: 16,
                paddingLeft: 57,
                paddingRight: 57,
                paddingBottom: 16,
                borderWidth: 2,
                borderStyle: "solid",
                hover: {
                    textColor: "#0d0d12",
                    backgroundColor: "#ffffff",
                    borderColor: "#ffffff"
                },
                mode: "content",
                customCSS: "-webkit-transition: all .3s ease-in-out;\n-moz-transition: all .3s ease-in-out;\n-ms-transition: all .3s ease-in-out;\n-o-transition: all .3s ease-in-out;\ntransition: all .3s ease-in-out;\nletter-spacing: .04em;\n\n\n",
                textAlign: "center",
                lineHeight: 22,
                display: "inline-block",
                borderColor: "#ffffff",
                color: "#ffffff",
                backgroundColor: "none"
            };

            addLayerElement(obj);

            resetTextarea();

            $(".STX-footer-layer-btn").addClass("STX-footer-layer-btn-active");

            $(".element-settings-tabs-wrapper").show();
            $(".slide-settings-main-menu-title").text("Element Settings");
        }

        function createImageElement() {
            var obj = {
                type: "image",
                src: "",
                position: {
                    x: "center",
                    y: "center",
                    offsetX: 0,
                    offsetY: 0
                },
                startAnimation: {
                    animation: "fadeInUp",
                    speed: 500,
                    delay: 0
                },
                endAnimation: {
                    animation: "fadeOutUp",
                    speed: 500,
                    delay: 0
                }
            };

            selectImageElement(obj);
        }

        function selectImageElement(obj) {
            if (file) file.close();

            file = wp.media.frames.file = wp.media({
                title: "Select image",
                button: {
                    text: "Select"
                },
                multiple: false
            });

            file.on("select", function() {
                var attachment = file
                    .state()
                    .get("selection")
                    .first()
                    .toJSON();
                var attachmentUrl = attachment.url;

                if (obj) {
                    obj.src = attachmentUrl;
                    addLayerElement(obj);
                    $(".STX-footer-layer-btn").addClass("STX-footer-layer-btn-active");
                    onSlideReorder();
                    $(".element-settings-tabs-wrapper").show();
                    $(".slide-settings-main-menu-title").text("Element Settings");
                } else {
                    getCurrentElement().src = attachmentUrl;
                    updateElementSettings();
                    updateCurrentElement("src");
                }
            });

            file.open();
        }

        function createVideoElement() {
            var obj = {
                type: "video",
                src: "",
                position: {
                    x: "center",
                    y: "center",
                    offsetX: 0,
                    offsetY: 0
                },
                startAnimation: {
                    animation: "fadeInUp",
                    speed: 500,
                    delay: 0
                },
                endAnimation: {
                    animation: "fadeOutUp",
                    speed: 500,
                    delay: 0
                }
            };

            selectVideoElement(obj);
        }

        function createIframeElement() {
            }

        function selectVideoElement(obj) {
            if (file) file.close();

            file = wp.media.frames.file = wp.media({
                title: "Select video",
                button: {
                    text: "Select"
                },
                multiple: false
            });

            file.on("select", function() {
                var attachment = file
                    .state()
                    .get("selection")
                    .first()
                    .toJSON();
                var attachmentUrl = attachment.url;

                if (obj) {
                    obj.src = attachmentUrl;
                    addLayerElement(obj);
                    $(".STX-footer-layer-btn").addClass("STX-footer-layer-btn-active");
                    onSlideReorder();
                    $(".element-settings-tabs-wrapper").show();
                    $(".slide-settings-main-menu-title").text("Element Settings");
                } else {
                    getCurrentElement().src = attachmentUrl;
                    updateElementSettings();
                    updateCurrentElement("src");
                }
            });

            file.open();
        }

        $(".STX-element-image-preview").click(function() {
            selectImageElement();
        });

        $(".STX-element-video-preview").click(function() {
            selectVideoElement();
        });

        generateNewSelectDropdownElements();
        generateNewColorPickerElements();

        function generateNewColorPickerElements() {
            $(".color-picker").each(function() {
                var hover = $(this).hasClass("has-hover");
                var slide = $(this).hasClass("slide-option");
                var name = $(this).attr("name");
                name = hover ? name + ".hover" : name;
                name = slide ? name + ".slide" : name;

                createColorPickerElement($(this), null, name);
            });
        }

        function generateNewSelectDropdownElements() {
            var editorElement = document.getElementById("edit-slide-modal");
            listOfDropdownsInEditor = editorElement.getElementsByTagName("select");

            function formatTransition(state) {
                if (!state.id) {
                    return state.text;
                }
                var lable = '';
                var path = window.data.stx_plugin_url + "assets/video/" + state.id + ".mp4"
                if(state.disabled) var lable = '<span class="STX-transition-video-pro">PRO</span>';
                var name = STX.Effects[state.id].name
                var $state = $('<video width="130" height="74" loop muted>' + "<source src=" + path + ' type="video/mp4" />' + "</video><p>" + name + "</p>" + lable).hover(function(event) {
                    event.preventDefault();
                    var vid = this.parentElement.firstElementChild
                    if (event.type === "mouseenter") {
                        vid.play();
                    } else if (event.type === "mouseleave") {
                        vid.currentTime = 0;
                        vid.pause();
                    }

                });

                return $state;
            }

            function formatTransitionSelection(state) {
                if (!state.id) {
                    return state.text;
                }
                var path = window.data.stx_plugin_url + "assets/video/" + state.id + ".mp4"
                var name = STX.Effects[state.id].name
                var $state = $('<video width="280" height="auto" autoplay loop muted>' + "<source src=" + path + ' type="video/mp4" />' + "</video><p>"+ name +"</p>");
                return $state;
            }

            function formatAnimationType(state) {
                if (!state.id) {
                    return state.text;
                }

                var animationName = state.id;
                if (!animationName) animationName = "fade";

                var has = function(val) {
                    return animationName.includes(val);
                };

                if (state.animationType === "startAnimation" && animationName.includes("effect")) {
                    var el;
                    var textWrapper;

                    var o = {};

                    o.duration = 1000;
                    o.delay = 500;
                    o.loop = true;

                    switch (animationName) {
                        case "effect1":
                            el = STX.Utils.htmlToElement(
                                '<h1 class="ml1">' +
                                '<span class="text-wrapper">' +
                                    '<span class="line line1"></span>' +
                                    '<span class="letters">' +
                                        state.text +
                                    '</span>' +
                                    '<span class="line line2"></span>' +
                                '</span>' +
                                '</h1>'
                            );
                            textWrapper = el.querySelector(".letters");
                            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

                            anime
                                .timeline({
                                    loop: o.loop
                                })

                                .add({
                                    targets: el.querySelectorAll(".ml1 .letter"),
                                    scale: [0.3, 1],
                                    opacity: [0, 1],
                                    translateZ: 0,
                                    easing: "easeOutExpo",
                                    duration: o.duration / 2,
                                    delay: (el, i, l) => ((o.duration / 2) / l) * (i + 1)
                                })
                                .add({
                                    targets: el.querySelectorAll(".ml1 .line"),
                                    scaleX: [0, 1],
                                    opacity: [0.5, 1],
                                    easing: "easeOutExpo",
                                    duration: o.duration / 2,
                                    offset: "-=875",
                                    delay: (el, i, l) => 80 * (l - i)
                                })
                                .add({
                                    delay: o.delay
                                });
                            break;

                        case "effect2":
                            el = STX.Utils.htmlToElement('<h1 class="ml2">'+state.text+'</h1>');

                            textWrapper = el;
                            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

                            anime
                                .timeline({
                                    loop: o.loop
                                })

                                .add({
                                    targets: el.querySelectorAll(".ml2 .letter"),
                                    scale: [4, 1],
                                    opacity: [0, 1],
                                    translateZ: 0,
                                    easing: "easeOutExpo",
                                    duration: o.duration,
                                    delay: (el, i, l) => o.duration / l * i
                                })
                                .add({
                                    delay: o.delay
                                });
                            break;
                        case "effect3":
                            el = STX.Utils.htmlToElement('<h1 class="ml3">'+state.text+'</h1>');

                            textWrapper = el;
                            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

                            anime
                                .timeline({
                                    loop: o.loop
                                })

                                .add({
                                    targets: el.querySelectorAll(".ml3 .letter"),
                                    opacity: [0,1],
                                    easing: "easeInOutQuad",
                                    duration: o.duration,
                                    delay: (el, i, l) => o.duration / l * i
                                })
                                .add({
                                    delay: o.delay
                                });
                            break;
                        case "effect4":
                            o.duration = 300;

                            el = STX.Utils.htmlToElement(
                                '<h1 class="ml4">' +
                                '<span class="letters letters-1">Eff</span>' +
                                '<span class="letters letters-2">ect</span>' +
                                '<span class="letters letters-3">4</span>' +
                                '</h1>'
                            );

                            textWrapper = el;

                            var ml4 = {};
                            ml4.opacityIn = [0,1];
                            ml4.scaleIn = [0.2, 1];
                            ml4.scaleOut = 3;
                            ml4.durationIn = o.duration;
                            ml4.durationOut = o.duration;
                            ml4.delay = o.delay;

                            var animation = anime
                                .timeline({
                                    loop: o.loop
                                });
                            animation

                                .add({
                                    targets: el.querySelectorAll('.ml4 .letters-1'),
                                    opacity: ml4.opacityIn,
                                    scale: ml4.scaleIn,
                                    duration: ml4.durationIn
                                })
                                .add({
                                    targets: el.querySelectorAll('.ml4 .letters-1'),
                                    opacity: 0,
                                    scale: ml4.scaleOut,
                                    duration: ml4.durationOut,
                                    easing: "easeInExpo",
                                    delay: ml4.delay
                                })
                                .add({
                                    targets: el.querySelectorAll('.ml4 .letters-2'),
                                    opacity: ml4.opacityIn,
                                    scale: ml4.scaleIn,
                                    duration: ml4.durationIn
                                })
                                .add({
                                    targets: el.querySelectorAll('.ml4 .letters-2'),
                                    opacity: 0,
                                    scale: ml4.scaleOut,
                                    duration: ml4.durationOut,
                                    easing: "easeInExpo",
                                    delay: ml4.delay
                                })
                                .add({
                                    targets: el.querySelectorAll('.ml4 .letters-3'),
                                    opacity: ml4.opacityIn,
                                    scale: ml4.scaleIn,
                                    duration: ml4.durationIn
                                })
                                .add({
                                    targets: el.querySelectorAll('.ml4 .letters-3'),
                                    opacity: 0,
                                    scale: ml4.scaleOut,
                                    duration: ml4.durationOut,
                                    easing: "easeInExpo",
                                    delay: ml4.delay
                                })
                                .add({
                                    delay: o.delay
                                });

                            break;
                        case "effect5":
                            el = STX.Utils.htmlToElement(
                                '<h1 class="ml6">' +
                                '<span class="text-wrapper">' +
                                '<span class="letters">'+state.text+'</span>' +
                                '</span>' +
                                "</h1>"
                            );

                            textWrapper = el.querySelector(".letters");
                            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

                            anime
                                .timeline({
                                    loop: o.loop
                                })

                                .add({
                                    targets: el.querySelectorAll('.ml6 .letter'),
                                    translateY: ["1.2em", 0],
                                    translateZ: 0,
                                    duration: o.duration,
                                    delay: (el, i, l) => o.duration / l * i
                                })
                                .add({
                                    delay: o.delay
                                });
                            break;
                        case "effect6":
                            el = STX.Utils.htmlToElement(
                                '<h1 class="ml7">' +
                                '<span class="text-wrapper">' +
                                '<span class="letters">'+state.text+'</span>' +
                                '</span>' +
                                '</h1>'
                            );

                            textWrapper = el.querySelector(".letters");
                            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

                            anime
                                .timeline({
                                    loop: o.loop
                                })

                                .add({
                                    targets: el.querySelectorAll('.ml7 .letter'),
                                    translateY: ["1.1em", 0],
                                    translateX: ["0.55em", 0],
                                    translateZ: 0,
                                    rotateZ: [180, 0],
                                    duration: o.duration,
                                    easing: "easeOutExpo",
                                    delay: (el, i, l) => o.duration / l * i
                                })
                                .add({
                                    delay: o.delay
                                });
                            break;
                        case "effect7":
                            el = STX.Utils.htmlToElement(
                                '<h1 class="ml9">' +
                                '<span class="text-wrapper">' +
                                '<span class="letters">'+state.text+'</span>' +
                                '</span>' +
                                '</h1>'
                            );

                            textWrapper = el.querySelector(".letters");
                            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

                            anime
                                .timeline({
                                    loop: o.loop
                                })

                                .add({
                                    targets: el.querySelectorAll('.ml9 .letter'),
                                    scale: [0, 1],
                                    duration: o.duration,
                                    elasticity: 600,
                                    delay: (el, i, l) => (o.duration / l) * (i+1)
                                })
                                .add({
                                    delay: o.delay
                                });
                            break;
                        case "effect8":
                            el = STX.Utils.htmlToElement(
                                '<h1 class="ml10">' +
                                '<span class="text-wrapper">' +
                                '<span class="letters">'+state.text+'</span>' +
                                '</span>' +
                                '</h1>'
                            );

                            textWrapper = el.querySelector(".letters");
                            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

                            anime
                                .timeline({
                                    loop: o.loop
                                })

                                .add({
                                    targets: el.querySelectorAll('.ml10 .letter'),
                                    rotateY: [-90, 0],
                                    duration: o.duration,
                                    delay: (el, i, l) => o.duration / l * i
                                })
                                .add({
                                    delay: o.delay
                                });
                            break;
                        case "effect9":
                            o.duration = 500;

                            el = STX.Utils.htmlToElement(
                                '<h1 class="ml11">' +
                                '<span class="text-wrapper">' +
                                '<span class="line line1"></span>' +
                                '<span class="letters">'+state.text+'</span>' +
                                '</span>' +
                                '</h1>'
                            );

                            textWrapper = el.querySelector(".letters");
                            textWrapper.innerHTML = textWrapper.textContent.replace(/([^\x00-\x80]|\w)/g, "<span class='letter'>$&</span>");
                            var textDelay = '-=' + (o.duration * 2);

                            anime
                                .timeline({
                                    loop: o.loop
                                })

                                .add({
                                    targets: el.querySelectorAll('.ml11 .line'),
                                    scaleY: [0,1],
                                    opacity: [0.5,1],
                                    easing: "easeOutExpo",
                                    duration: 700
                                })
                                .add({
                                    targets: el.querySelector('.ml11 .line'),
                                    translateX: [0, 110],
                                    easing: "easeOutExpo",
                                    duration: o.duration * 2
                                })
                                .add({
                                    targets: el.querySelectorAll('.ml11 .letter'),
                                    opacity: [0,1],
                                    easing: "easeOutExpo",
                                    duration: 600,
                                    delay: (el, i, l) => o.duration / l * (i+1)
                                }, textDelay)
                                .add({
                                    delay: o.delay
                                });
                            break;
                        case "effect10":

                            el = STX.Utils.htmlToElement('<h1 class="ml12">'+state.text+'</h1>');

                            textWrapper = el;
                            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

                            anime
                                .timeline({
                                    loop: o.loop
                                })

                                .add({
                                    targets: el.querySelectorAll(".ml12 .letter"),
                                    translateX: [40,0],
                                    translateZ: 0,
                                    opacity: [0,1],
                                    easing: "easeOutExpo",
                                    duration: o.duration,
                                    delay: (el, i, l) => 500 + (o.duration / l) * i
                                })
                                .add({
                                    delay: o.delay
                                });
                            break;
                        case "effect11":

                            el = STX.Utils.htmlToElement('<h1 class="ml13">'+state.text+'</h1>');

                            textWrapper = el;
                            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

                            anime
                                .timeline({
                                    loop: o.loop
                                })

                                .add({
                                    targets: el.querySelectorAll(".ml13 .letter"),
                                    translateY: [100,0],
                                    translateZ: 0,
                                    opacity: [0,1],
                                    easing: "easeOutExpo",
                                    duration: o.duration,
                                    delay: (el, i, l) => 300 + (o.duration / l) * i
                                })
                                .add({
                                    delay: o.delay
                                });
                            break;
                        case "effect12":
                            o.duration = 1100;

                            el = STX.Utils.htmlToElement(
                                '<h1 class="ml14">' +
                                '<span class="text-wrapper">' +
                                '<span class="letters">'+state.text+'</span>' +
                                '<span class="line"></span>' +
                                '</span>' +
                                '</span>' +
                                '</h1>'
                            );

                            textWrapper = el.querySelector(".letters");
                            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
                            var textDelay = '-=' + (o.duration / 2);

                            anime
                                .timeline({
                                    loop: o.loop
                                })

                                .add({
                                    targets: el.querySelector('.ml14 .line'),
                                    scaleX: [0,1],
                                    opacity: [0.5,1],
                                    easing: "easeInOutExpo",
                                    duration: o.duration - 200
                                })
                                .add({
                                    targets: el.querySelectorAll('.ml14 .letter'),
                                    opacity: [0,1],
                                    translateX: [40,0],
                                    translateZ: 0,
                                    scaleX: [0.3, 1],
                                    easing: "easeOutExpo",
                                    duration: o.duration - 300,
                                    delay: (el, i, l) => 150 + (o.duration / l) * i
                                }, textDelay)
                                .add({
                                    delay: o.delay
                                });
                            break;
                        case "effect13":
                            o.duration = 500;

                            el = STX.Utils.htmlToElement(
                                '<h1 class="ml15">' +
                                '<span class="word">Effect </span>' +
                                '<span class="word">13</span>' +
                                '</h1>'
                            );

                            anime
                                .timeline({
                                    loop: o.loop
                                })
                                .add({
                                    targets: el.querySelectorAll('.ml15 .word'),
                                    scale: [14,1],
                                    opacity: [0,1],
                                    easing: "easeOutCirc",
                                    duration: o.duration,
                                    delay: (el, i) => o.duration * i
                                })
                                .add({
                                    delay: o.delay
                                });
                            break;
                        case "effect14":
                            o.duration = 1400;

                            el = STX.Utils.htmlToElement('<h1 class="ml16">'+state.text+'</h1>');

                            textWrapper = el;
                            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

                            anime
                                .timeline({
                                    loop: o.loop
                                })

                                .add({
                                    targets: el.querySelectorAll('.ml16 .letter'),
                                    translateY: [-100,0],
                                    easing: "easeOutExpo",
                                    duration: o.duration,
                                    delay: (el, i, l) => (o.duration / l) * i
                                })
                                .add({
                                    delay: o.delay
                                });
                            break;
                    }
                } else {
                    var el = document.createElement('h2');
                    el.innerText = state.text;
                    el.style.transition = "all 0s";

                    var o = { targets: el };
                    o.easing = "easeOutQuad";

                    var offset = "100%";
                    var angle = 0;
                    var opacity = 1;

                    if(animationName === "bounceIn") {
                        o.easing = "spring(1, 100, 10, 0)";
                        o.scale = [0.3, 1];
                        opacity = 0;
                    } else if (has("bounce")) {
                        o.easing = "spring(.7, 100, 10, 0)";
                        offset = "1000px";
                        opacity = 0;
                    }

                    if (has("flip")) {
                        if(has("InX")) {
                            o.rotateX = ["90deg", 0];
                        } else if(has("InY")) {
                            o.rotateY = ["90deg", 0];
                        }else if(has("OutX")) {
                            o.rotateX = ["90deg"];
                        }else if(has("OutY")) {
                            o.rotateY = ["90deg"];
                        }
                        opacity = 0
                    }

                    if (has("fade") || has("zoom")) opacity = 0;

                    if (has("rotate")) {
                        if (has("UpLeft")) {
                            el.style.transformOrigin = "left bottom";
                            angle = "45deg";
                        } else if (has("UpRight")) {
                            el.style.transformOrigin = "right bottom";
                            angle = "-90deg";
                        } else if (has("DownLeft")) {
                            el.style.transformOrigin = "left bottom";
                            angle = "-45deg";
                        } else if (has("DownRight")) {
                            el.style.transformOrigin = "right bottom";
                            angle = "45deg";
                        } else {
                            el.style.transformOrigin = "center";
                            angle = "-200deg";
                        }
                        opacity = 0;
                    }

                    if (has("lightSpeed")) {
                        offset = "100%";
                        angle = "-180deg";
                    }

                    if (has("Big")) {
                        offset = "2000px";
                    }

                    if (state.animationType === "endAnimation") {
                        o.opacity = [1, opacity];

                        if (has("zoom")) {
                            o.scale = 0.3;
                        }

                        if (!has("rotate")) {
                            if (has("Left")) o.translateX = "-" + offset;
                            else if (has("Right")) o.translateX = offset;
                            else if (has("Down")) o.translateY = offset;
                            else if (has("Up")) o.translateY = "-" + offset;
                        }

                        if (has("lightSpeed")) {
                            o.translateX = offset;
                            o.skewX = angle;
                            o.easing = "spring(.4, 100, 10, 0)";
                        }

                        if (has("rotate")) o.rotateZ =  String(Number(angle.replace("deg","")) * -1 ) + "deg"

                        o.duration = 1000;
                        o.loop = true;

                        o.delay = 600

                        anime(o)

                    } else {
                        if (opacity != 1) o.opacity = [opacity, 1];

                        if (has("zoom")) {
                            o.scale = [0.3, 1];
                        }
                        if (!has("rotate")) {
                            if (has("Left")) o.translateX = ["-" + offset, 0];
                            else if (has("Right")) o.translateX = [offset, 0];
                            else if (has("Up")) o.translateY = [offset, 0];
                            else if (has("Down")) o.translateY = ["-" + offset, 0];
                        }

                        if (has("lightSpeed")) {
                            o.translateX = [offset, 0];
                            o.skewX = [angle, 0];
                            o.easing = "spring(.4, 100, 10, 0)";
                        }

                        if (has("rotate")) o.rotateZ = [angle, 0];

                        o.duration = 1000;
                        o.loop = true;

                        o.endDelay = 600

                        anime(o)
                    }
                }

                if(state.disabled) {
                    var lable = '<span class="STX-transition-video-pro">PRO</span>';
                    $(el).append(lable);
                }

                var preview = STX.Utils.htmlToElement('<div class="STX-animation-effect-wrapper"></div>');
                $(preview).append(el);
                $(preview).append(lable);
                return $(preview);
            }

            for (var i = 0; i < listOfDropdownsInEditor.length; i++) {
                switch (listOfDropdownsInEditor[i].name) {
                    case "transitionEffect":
                        $(listOfDropdownsInEditor[i]).select2stx({
                            dropdownParent: $("#edit-slide-modal"),
                            width: "100%",
                            selectionCssClass: "selection-transition-effect",
                            dropdownCssClass: "dropdown-transition-effect",
                            minimumResultsForSearch: Infinity,
                            templateResult: formatTransition,
                            templateSelection: formatTransitionSelection,
                            data: [
                                {
                                    text: "Default",
                                    children: [
                                        {
                                            id: "slide",
                                            text: "Default"
                                        }
                                    ]
                                },
                                {
                                    text: "Blur",
                                    children: [
                                        {
                                            id: "blur",
                                            text: "Blur"
                                        },
                                        {
                                            id: "blur2",
                                            text: "Blur 2",
                                            disabled: true
                                        },
                                        {
                                            id: "blur3",
                                            text: "Blur 3",
                                            disabled: true
                                        },
                                        {
                                            id: "blur4",
                                            text: "Blur 4",
                                            disabled: true
                                        },
                                        {
                                            id: "blur5",
                                            text: "Blur ",
                                            disabled: true
                                        },
                                        {
                                            id: "blur6",
                                            text: "Blur 6",
                                            disabled: true
                                        },
                                        {
                                            id: "blur7",
                                            text: "Blur 7",
                                            disabled: true
                                        }
                                    ]
                                },
                                {
                                    text: "Crossfade",
                                    children: [
                                        {
                                            id: "crossfade1",
                                            text: "Crossfade 1",
                                            disabled: true
                                        },
                                        {
                                            id: "crossfade2",
                                            text: "Crossfade 2",
                                            disabled: true
                                        },
                                        {
                                            id: "crossfade3",
                                            text: "Crossfade 3",
                                            disabled: true
                                        },
                                        {
                                            id: "crossfade4",
                                            text: "Crossfade 4",
                                            disabled: true
                                        }
                                    ]
                                },
                                {
                                    text: "Fade",
                                    children: [
                                        {
                                            id: "fade",
                                            text: "Fade 1"
                                        },
                                        {
                                            id: "fade2",
                                            text: "Fade 2"
                                        }
                                    ]
                                },
                                {
                                    text: "Line",
                                    children: [
                                        {
                                            id: "line",
                                            text: "Line 1"
                                        },
                                        {
                                            id: "line2",
                                            text: "Line 2",
                                            disabled: true
                                        },
                                        {
                                            id: "line3",
                                            text: "Line 3",
                                            disabled: true
                                        },
                                        {
                                            id: "line4",
                                            text: "Line 4",
                                            disabled: true
                                        },
                                        {
                                            id: "line5",
                                            text: "Line 5",
                                            disabled: true
                                        },
                                        {
                                            id: "line6",
                                            text: "Line 6",
                                            disabled: true
                                        },
                                        {
                                            id: "line7",
                                            text: "Line 7",
                                            disabled: true
                                        },
                                        {
                                            id: "line8",
                                            text: "Line 8",
                                            disabled: true
                                        },
                                        {
                                            id: "line9",
                                            text: "Line 9",
                                            disabled: true
                                        },
                                        {
                                            id: "line10",
                                            text: "Line 10",
                                            disabled: true
                                        }
                                    ]
                                },
                                {
                                    text: "Powerzoom",
                                    children: [
                                        {
                                            id: "powerzoom",
                                            text: "Powerzoom 1"
                                        }
                                    ]
                                },
                                {
                                    text: "Rool",
                                    children: [
                                        {
                                            id: "roll",
                                            text: "Roll 1"
                                        },
                                        {
                                            id: "roll2",
                                            text: "Roll 2",
                                            disabled: true
                                        },
                                        {
                                            id: "roll3",
                                            text: "Roll 3",
                                            disabled: true
                                        },
                                        {
                                            id: "roll4",
                                            text: "Roll 4",
                                            disabled: true
                                        },
                                        {
                                            id: "roll5",
                                            text: "Roll 5",
                                            disabled: true
                                        }
                                    ]
                                },
                                {
                                    text: "Slide",
                                    children: [
                                        {
                                            id: "slide",
                                            text: "Slide 1"
                                        }
                                    ]
                                },
                                {
                                    text: "Stretch",
                                    children: [
                                        {
                                            id: "stretch",
                                            text: "Stretch 1"
                                        }
                                    ]
                                },
                                {
                                    text: "Twirl",
                                    children: [
                                        {
                                            id: "twirl",
                                            text: "Twirl 1"
                                        }
                                    ]
                                },
                                {
                                    text: "Warp",
                                    children: [
                                        {
                                            id: "warp",
                                            text: "Warp 1"
                                        }
                                    ]
                                },
                                {
                                    text: "Zoom",
                                    children: [
                                        {
                                            id: "zoom",
                                            text: "Zoom 1"
                                        },
                                        {
                                            id: "zoom2",
                                            text: "Zoom 2",
                                            disabled: true
                                        },
                                        {
                                            id: "zoom3",
                                            text: "Zoom 3",
                                            disabled: true
                                        },
                                        {
                                            id: "zoom4",
                                            text: "Zoom 4",
                                            disabled: true
                                        },
                                        {
                                            id: "zoom5",
                                            text: "Zoom 5",
                                            disabled: true
                                        }
                                    ]
                                }
                            ]
                        });
                        break;
                    case "endAnimation.animation":
                        var animationType = "endAnimation";

                        $(listOfDropdownsInEditor[i]).select2stx({
                            dropdownParent: $("#edit-slide-modal"),
                            width: "100%",
                            selectionCssClass: "selection-transition-effect",
                            dropdownCssClass: "dropdown-transition-effect",
                            minimumResultsForSearch: Infinity,
                            templateResult: formatAnimationType,
                            templateSelection: formatAnimationType,
                            data: [
                                {
                                    text: "Fading",
                                    children: [
                                        {
                                            id: "fadeOut",
                                            text: "Fade",
                                            animationType: animationType
                                        },
                                        {
                                            id: "fadeOutDown",
                                            text: "Fade Down",
                                            animationType: animationType
                                        },
                                        {
                                            id: "fadeOutDownBig",
                                            text: "Fade Down Big",
                                            animationType: animationType
                                        },
                                        {
                                            id: "fadeOutLeft",
                                            text: "Fade Left",
                                            animationType: animationType
                                        },
                                        {
                                            id: "fadeOutLeftBig",
                                            text: "Fade Left Big",
                                            animationType: animationType
                                        },
                                        {
                                            id: "fadeOutRight",
                                            text: "Fade Right",
                                            animationType: animationType
                                        },
                                        {
                                            id: "fadeOutRightBig",
                                            text: "Fade Right Big",
                                            animationType: animationType
                                        },
                                        {
                                            id: "fadeOutUp",
                                            text: "Fade Up",
                                            animationType: animationType
                                        },
                                        {
                                            id: "fadeOutUpBig",
                                            text: "Fade Up Big",
                                            animationType: animationType
                                        }
                                    ]
                                },
                                {
                                    text: "Flippers",
                                    children: [
                                        {
                                            id: "flipOutX",
                                            text: "Flip X",
                                            animationType: animationType
                                        },
                                        {
                                            id: "flipOutY",
                                            text: "Flip Y",
                                            animationType: animationType
                                        }
                                    ]
                                },
                                {
                                    text: "Sliding",
                                    children: [
                                        {
                                            id: "slideOutDown",
                                            text: "Slide Down",
                                            animationType: animationType
                                        },
                                        {
                                            id: "slideOutLeft",
                                            text: "Slide Left",
                                            animationType: animationType
                                        },
                                        {
                                            id: "slideOutRight",
                                            text: "Slide Right",
                                            animationType: animationType
                                        },
                                        {
                                            id: "slideOutUp",
                                            text: "Slide Up",
                                            animationType: animationType
                                        }
                                    ]
                                },

                                {
                                    text: "Rotating",
                                    children: [
                                        {
                                            id: "rotateOut",
                                            text: "Rotate",
                                            animationType: animationType
                                        },
                                        {
                                            id: "rotateOutDownLeft",
                                            text: "Rotate Down Left",
                                            animationType: animationType
                                        },
                                        {
                                            id: "rotateOutDownRight",
                                            text: "Rotate Down Right",
                                            animationType: animationType
                                        },
                                        {
                                            id: "rotateOutUpLeft",
                                            text: "Rotate Up Left",
                                            animationType: animationType
                                        },
                                        {
                                            id: "rotateOutUpRight",
                                            text: "Rotate Up Right",
                                            animationType: animationType
                                        }
                                    ]
                                },
                                {
                                    text: "Zooming",
                                    children: [
                                        {
                                            id: "zoomOut",
                                            text: "Zoom",
                                            animationType: animationType
                                        },
                                        {
                                            id: "zoomOutDown",
                                            text: "Zoom Down",
                                            animationType: animationType
                                        },
                                        {
                                            id: "zoomOutLeft",
                                            text: "Zoom Left",
                                            animationType: animationType
                                        },
                                        {
                                            id: "zoomOutRight",
                                            text: "Zoom Right",
                                            animationType: animationType
                                        },
                                        {
                                            id: "zoomOutUp",
                                            text: "Zoom Up",
                                            animationType: animationType
                                        }
                                    ]
                                },
                                {
                                    text: "Attention seekers",
                                    children: [
                                        {
                                            id: "lightSpeedOut",
                                            text: "Lightspeed",
                                            animationType: animationType
                                        }
                                    ]
                                }
                            ]
                        });
                        break;

                    case "startAnimation.animation":
                        var animationType = "startAnimation";

                        $(listOfDropdownsInEditor[i]).select2stx({
                            dropdownParent: $("#edit-slide-modal"),
                            width: "100%",
                            selectionCssClass: "selection-transition-effect",
                            dropdownCssClass: "dropdown-transition-effect",
                            minimumResultsForSearch: Infinity,
                            templateResult: formatAnimationType,
                            templateSelection: formatAnimationType,
                            data: [
                                {
                                    text: "Fading",
                                    children: [
                                        {
                                            id: "fadeIn",
                                            text: "Fade",
                                            animationType: animationType
                                        },
                                        {
                                            id: "fadeInDown",
                                            text: "Fade Down",
                                            animationType: animationType
                                        },
                                        {
                                            id: "fadeInDownBig",
                                            text: "Fade Down Big",
                                            animationType: animationType
                                        },
                                        {
                                            id: "fadeInLeft",
                                            text: "Fade Left",
                                            animationType: animationType
                                        },
                                        {
                                            id: "fadeInLeftBig",
                                            text: "Fade Left Big",
                                            animationType: animationType
                                        },
                                        {
                                            id: "fadeInRight",
                                            text: "Fade Right",
                                            animationType: animationType
                                        },
                                        {
                                            id: "fadeInRightBig",
                                            text: "Fade Right Big",
                                            animationType: animationType
                                        },
                                        {
                                            id: "fadeInUp",
                                            text: "Fade Up",
                                            animationType: animationType
                                        },
                                        {
                                            id: "fadeInUpBig",
                                            text: "Fade Up Big",
                                            animationType: animationType
                                        }
                                    ]
                                },
                                {
                                    text: "Flippers",
                                    children: [
                                        {
                                            id: "flipInX",
                                            text: "Flip X",
                                            animationType: animationType
                                        },
                                        {
                                            id: "flipInY",
                                            text: "Flip Y",
                                            animationType: animationType
                                        }
                                    ]
                                },
                                {
                                    text: "Sliding",
                                    children: [
                                        {
                                            id: "slideInDown",
                                            text: "Slide Down",
                                            animationType: animationType
                                        },
                                        {
                                            id: "slideInLeft",
                                            text: "Slide Left",
                                            animationType: animationType
                                        },
                                        {
                                            id: "slideInRight",
                                            text: "Slide Right",
                                            animationType: animationType
                                        },
                                        {
                                            id: "slideInUp",
                                            text: "Slide Up",
                                            animationType: animationType
                                        }
                                    ]
                                },
                                {
                                    text: "Bouncing",
                                    children: [
                                        {
                                            id: "bounceIn",
                                            text: "Bounce",
                                            animationType: animationType
                                        },
                                        {
                                            id: "bounceInDown",
                                            text: "Bounce Down",
                                            animationType: animationType
                                        },
                                        {
                                            id: "bounceInLeft",
                                            text: "Bounce Left",
                                            animationType: animationType
                                        },
                                        {
                                            id: "bounceInRight",
                                            text: "Bounce Right",
                                            animationType: animationType
                                        },
                                        {
                                            id: "bounceInUp",
                                            text: "Bounce Up",
                                            animationType: animationType
                                        }
                                    ]
                                },
                                {
                                    text: "Rotating",
                                    children: [
                                        {
                                            id: "rotateIn",
                                            text: "Rotate",
                                            animationType: animationType
                                        },
                                        {
                                            id: "rotateInDownLeft",
                                            text: "Rotate Down Left",
                                            animationType: animationType
                                        },
                                        {
                                            id: "rotateInDownRight",
                                            text: "Rotate Down Right",
                                            animationType: animationType
                                        },
                                        {
                                            id: "rotateInUpLeft",
                                            text: "Rotate Up Left",
                                            animationType: animationType
                                        },
                                        {
                                            id: "rotateInUpRight",
                                            text: "Rotate Up Right",
                                            animationType: animationType
                                        }
                                    ]
                                },
                                {
                                    text: "Zooming",
                                    children: [
                                        {
                                            id: "zoomIn",
                                            text: "Zoom",
                                            animationType: animationType
                                        },
                                        {
                                            id: "zoomInDown",
                                            text: "Zoom Down",
                                            animationType: animationType
                                        },
                                        {
                                            id: "zoomInLeft",
                                            text: "Zoom Left",
                                            animationType: animationType
                                        },
                                        {
                                            id: "zoomInRight",
                                            text: "Zoom Right",
                                            animationType: animationType
                                        },
                                        {
                                            id: "zoomInUp",
                                            text: "Zoom Up",
                                            animationType: animationType
                                        }
                                    ]
                                },
                                {
                                    text: "Attention seekers",
                                    children: [
                                        {
                                            id: "lightSpeedIn",
                                            text: "Lightspeed",
                                            animationType: animationType
                                        }
                                    ]
                                },
                                {
                                    text: "Special",
                                    children: [
                                        {
                                            id: "effect1",
                                            text: "Effect1",
                                            animationType: animationType,
                                            disabled: true
                                        },
                                        {
                                            id: "effect2",
                                            text: "Effect2",
                                            animationType: animationType,
                                            disabled: true
                                        },
                                        {
                                            id: "effect3",
                                            text: "Effect3",
                                            animationType: animationType,
                                            disabled: true
                                        },
                                        {
                                            id: "effect4",
                                            text: "Effect4",
                                            animationType: animationType,
                                            disabled: true
                                        },
                                        {
                                            id: "effect5",
                                            text: "Effect5",
                                            animationType: animationType,
                                            disabled: true
                                        },
                                        {
                                            id: "effect6",
                                            text: "Effect6",
                                            animationType: animationType,
                                            disabled: true
                                        },
                                        {
                                            id: "effect7",
                                            text: "Effect7",
                                            animationType: animationType,
                                            disabled: true
                                        },
                                        {
                                            id: "effect8",
                                            text: "Effect8",
                                            animationType: animationType,
                                            disabled: true
                                        },
                                        {
                                            id: "effect9",
                                            text: "Effect9",
                                            animationType: animationType
                                        },
                                        {
                                            id: "effect10",
                                            text: "Effect10",
                                            animationType: animationType,
                                            disabled: true
                                        },
                                        {
                                            id: "effect11",
                                            text: "Effect11",
                                            animationType: animationType,
                                            disabled: true
                                        },
                                        {
                                            id: "effect12",
                                            text: "Effect12",
                                            animationType: animationType,
                                            disabled: true
                                        },
                                        {
                                            id: "effect13",
                                            text: "Effect13",
                                            animationType: animationType,
                                            disabled: true
                                        },
                                        {
                                            id: "effect14",
                                            text: "Effect14",
                                            animationType: animationType,
                                            disabled: true
                                        }
                                    ]
                                }
                            ]
                        });
                        break;
                }
            }
        }

        function clearEditLayerColorPickerElements() {
            colorPickers.forEach(function(picker){
                if(!picker.name.includes("slide"))
                    picker.pickr.setColor(null, true)
            })
        }

        function setColorPicker(name, color){

            colorPickers.forEach(function(colorPicker){
                if(colorPicker.name == name)
                    colorPicker.pickr.setColor(color)
            })
        }

        $addTextButton.click(createTextElement);
        $addHeadingButton.click(createHeadingElement);
        $addImageButton.click(createImageElement);
        $addVideoButton.click(createVideoElement);
        $addIframeButton.click(createIframeElement);
        $addButtonButton.click(createButtonElement);

        function selectFirstElement() {
            $(".layer-item")
                .first()
                .trigger("click");
        }

        function selectLastElement() {
            $(".layer-item")
                .last()
                .trigger("click");
        }

        function addNavigatorElement(obj) {
            var numItems = $(".layer-item").length;

            var $el = $('<li class="layer-item"><span class="layer-item-' + obj.type + '"></span>' + obj.type.toUpperCase() + '<a class="layer-item-duplicate" href="#"></a><a class="layer-item-trash" href="#"></a></li>');

            $el.attr("id", numItems).click(function(e) {
                e.preventDefault();
                e.stopPropagation();

                onLayerMouseDown($(this).index(), e.shiftKey, e.button);
            });

            $layerList.append($el);

            $el.find(".layer-item-duplicate").click(function(e) {
                e.preventDefault();
                e.stopPropagation();

                setCurrentElement(
                    $(this)
                        .parent()
                        .index()
                );

                duplicateLayerElement();
            });

            $el.find(".layer-item-trash").click(function(e) {
                e.preventDefault();
                e.stopPropagation();

                var index = $(this)
                    .parent()
                    .index();

                selectedElements = [index];

                deleteLayerElements();
            });
        }

        function addLayerElement(el) {
            options.slides[currentSlide].elements = options.slides[currentSlide].elements || [];

            setCurrentElement(options.slides[currentSlide].elements.length);

            options.slides[currentSlide].elements.push(el);

            addNavigatorElement(el);

            renderAddedElement();

            updateElementSettings(el);
        }

        function setCurrentElement(index) {
            currentElement = index;
            clearEditLayerColorPickerElements();
        }

        function updateElementSettings(obj, hover) {
            obj = obj || getCurrentElement();

            if (obj.type != "text") {
                if (tinyMCE.activeEditor) tinyMCE.activeEditor.off("change keyup paste");
                wp.editor.remove("text-content");
            }
            if (obj.src) {
                if (obj.type == "image")
                    $(".STX-element-image-preview").css({
                        "background-image": "url(" + obj.src + ")",
                        "background-size": "contain"
                    });
                else if (obj.type == "video") {
                    $(".STX-element-video-preview").attr("src", obj.src);
                }
            }

            if (deviceType == "mobile") obj = obj.mobile || {};
            else if (deviceType == "tablet") obj = obj.tablet || {};

            var $container = hover ? $elementSettingsHover : $elementSettings;

            var settings = hover ? elementSettingsHover : elementSettings;

            for (var key in settings) {
                if (settings[key].type == "radio" || settings[key].type == "checkbox") settings[key].checked = false;
                else if (settings[key]) settings[key].value = "";
            }

            for (var key in obj) {
                if (key == "hover") {
                    updateElementSettings(obj.hover, true);
                } else {
                    var val = obj[key];

                    if (typeof val != "object") {
                        if (settings[key]) {
                            if (settings[key].type == "radio") {
                                $container.find('input[name="' + key + '"][value="' + val + '"]').prop("checked", "true");
                            } else if (settings[key].type == "checkbox") {
                                settings[key].checked = val;
                            } else if (settings[key].classList.contains("color-picker")) {
                                if (
                                    $(settings[key])
                                        .next()
                                        .hasClass("STX-color-picker")
                                ) {
                                    var pickerColor = val === "" ? null : val;
                                    var name = hover ? key + '.hover' : key
                                    setColorPicker(name, pickerColor)
                                }
                            } else if (settings[key].classList.contains("unit")) {
                                if (typeof val == "string") {
                                    var units = ["px", "%", "rem", "em", "vw"];
                                    var unit = "px";
                                    units.forEach(function(u) {
                                        if (String(val).includes(u)) {
                                            val = val.replace(u, "");
                                            unit = u;
                                        }
                                    });
                                    $container.find(".unit-" + key).text(unit);
                                }
                                settings[key].value = val;
                            } else {
                                settings[key].value = val;
                            }
                        }
                    } else {
                        for (var key2 in val) {
                            var val2 = val[key2];
                            if (settings[key + "." + key2]) {
                                if (typeof val2 == "boolean") settings[key + "." + key2].checked = val2;

                                if (settings[key + "." + key2]) settings[key + "." + key2].value = val2;
                                if ($(settings[key + "." + key2]).data('select2stx')) $(settings[key + "." + key2]).val(val2).trigger("change");
                            }
                        }
                    }
                }
            }
            updateOnClickActionType(getOnClickActionType());

            if (obj.type == "heading") {
                $(".slide-settings-main-menu-title").text("Edit Heading");
                $buttonElementSettings.hide();
                $imageElementSettings.hide();
                $videoElementSettings.hide();
                $textElementSettings.hide();
                $iframeElementSettings.hide();
                $headingElementSettings.show();
            }
            if (obj.type == "text") {
                $(".slide-settings-main-menu-title").text("Edit Text");
                $buttonElementSettings.hide();
                $imageElementSettings.hide();
                $videoElementSettings.hide();
                $iframeElementSettings.hide();
                $headingElementSettings.hide();
                $textElementSettings.show();
                if (!tinyMCE.activeEditor) initTextEditor(obj.content);
                else if (obj.content && tinyMCE.activeEditor) tinyMCE.activeEditor.setContent(obj.content);
                $("#text-content").css("display", "none");
            }
            if (obj.type == "button") {
                $(".slide-settings-main-menu-title").text("Edit Button");
                $imageElementSettings.hide();
                $textElementSettings.hide();
                $videoElementSettings.hide();
                $iframeElementSettings.hide();
                $headingElementSettings.hide();
                $buttonElementSettings.show();
            }
            if (obj.type == "image") {
                $(".slide-settings-main-menu-title").text("Edit Image");
                $textElementSettings.hide();
                $buttonElementSettings.hide();
                $videoElementSettings.hide();
                $iframeElementSettings.hide();
                $headingElementSettings.hide();
                $imageElementSettings.show();
            }
            if (obj.type == "video") {
                $(".slide-settings-main-menu-title").text("Edit Video");
                $textElementSettings.hide();
                $buttonElementSettings.hide();
                $imageElementSettings.hide();
                $iframeElementSettings.hide();
                $headingElementSettings.hide();
                $videoElementSettings.show();
            }
            if (obj.type == "iframe") {
                $(".slide-settings-main-menu-title").text("Edit Iframe");
                $textElementSettings.hide();
                $buttonElementSettings.hide();
                $imageElementSettings.hide();
                $videoElementSettings.hide();
                $headingElementSettings.hide();
                $iframeElementSettings.show();
            }
            $(".element-settings-tabs-wrapper").show();

            updateAccordion();

        }

        function updateAccordion() {
            $(".accordion").each(function (index, accordion) {
                var activeSubcategory = 0;
                var indexArrayOfVisibleSubcategory = [];

                $(this).accordion("refresh");
                activeSubcategory = $(this).accordion('option', 'active');

                $($(this).children('h3')).each(function (index, subcategory) {
                    if($(this).is(':visible')) indexArrayOfVisibleSubcategory.push(index);
                });

                if(indexArrayOfVisibleSubcategory.includes(activeSubcategory)) $(this).accordion('option', 'active', activeSubcategory);
                else $(this).accordion('option', 'active', indexArrayOfVisibleSubcategory[0]);
            });
        }


        function getOnClickActionType() {
            return $('select[name="onClick.type"]').val();
        }

        function updateElementSetting(name, val, hover) {
            onElementSettingChanged(true);
            var settings = hover ? elementSettingsHover : elementSettings;
            settings[name].value = val;
        }

        function unfocusLayerElement() {
            $(".element-settings-tabs-wrapper").hide();
            $(".slide-settings-main-menu-title").text("Slide Settings");
            layerRenderer.unfocusElement();
            $(".selected-layer").removeClass("selected-layer");
            selectedElements = [];
            setCurrentElement(-1);
        }

        var $layerListPopup = $(".layer-list-popup")
            .draggable({
                handle: ".layer-list-popup-title",
                containment: ".slider-preview-wrapper"
            })
            .resizable({
                autoHide: true,
                minWidth: 200,
                maxWidth: 350,
                minHeight: 150,
                containment: ".slider-preview-wrapper"
            });

        showLayerListPopup();

        function hideLayerListPopup() {
            $layerListPopup.hide();
            $(".STX-footer-layer-btn").removeClass("STX-footer-layer-btn-active");
        }

        function showLayerListPopup() {
            $layerListPopup.show();
            $(".STX-footer-layer-btn").addClass("STX-footer-layer-btn-active");
        }

        function toggleLayerListPopup() {
            $layerListPopup.toggle();
            $(".STX-footer-layer-btn").toggleClass("STX-footer-layer-btn-active");
        }

        var $layerList = $(".layers-wrapper")
            .sortable({
                axis: "y",
                stop: function(event, ui) {
                    var newArr = [];

                    var arr = $(".layer-item").each(function(key, val) {
                        newArr[key] = options.slides[currentSlide].elements[Number(val.id)];
                    });

                    arr.each(function(key, val) {
                        val.id = key;
                    });

                    options.slides[currentSlide].elements = newArr;

                    renderLayers();
                }
            })
            .disableSelection();

        $(".layer-list-popup-close").click(function() {
            hideLayerListPopup();
        });

        $slider.click(function(e) {
            if ($(e.target).hasClass("stx-layers") || $(e.target).hasClass("slider-preview-area")) unfocusLayerElement();
        });

        function getCurrentElement() {
            return options.slides[currentSlide].elements[currentElement];
        }

        function getElement(index) {
            return options.slides[currentSlide].elements[index];
        }

        function getCurrentSlide() {
            return options.slides[currentSlide];
        }

        function getIndexByID(id, slideIndex ){
            var index = -1
			if(options.slides[slideIndex].elements){
				options.slides[slideIndex].elements.forEach(function(el, i){
					if("n" + el.id == id)
						index = i;
				})
			}
            return index;
        }

        function onLayerEditorMouseDown(id, shiftKey) {
            var index
            options.slides.forEach(function(slide, i){
                index = getIndexByID(id, i)
                if(index != -1) {
                    if(i != currentSlide){
                        currentSlide = i;
                        showSlide(currentSlide)
                    }
                    onLayerMouseDown(index, shiftKey)
                }
            })

        }

        function onLayerMouseDown(index, shiftKey) {

            setCurrentElement(index);

            if (!shiftKey) {
                $(".selected-layer").removeClass("selected-layer");
                layerRenderer.unfocusElement();
                selectedElements = [];
            }

            if (!selectedElements.includes(currentElement)) selectedElements.push(currentElement);

            $(".layer-item")
                .eq(currentElement)
                .addClass("selected-layer");

            updateElementSettings();

            layerRenderer.focusElement(currentElement);

            $(".element-settings-tabs-wrapper").show();
        }

        function onLayerMouseUp() {
            selectedElements.forEach(function(index) {
                var el = getElement(index);
                var toUpdate = el;
                if (deviceType == "mobile") {
                    el.mobile = el.mobile || {};
                    toUpdate = el.mobile;
                } else if (deviceType == "tablet") {
                    el.tablet = el.tablet || {};
                    toUpdate = el.tablet;
                }

                if (!el) return;
                if (el.mode == "content") return;
                toUpdate.position = toUpdate.position || {};
                toUpdate.position.offsetX = Number(toUpdate.position.offsetX || 0);
                toUpdate.position.offsetY = Number(toUpdate.position.offsetY || 0);
                toUpdate.position.offsetX = parseInt(toUpdate.position.offsetX);
                toUpdate.position.offsetY = parseInt(toUpdate.position.offsetY);
                layerRenderer.updateElement(el, "position");
                updateElementSetting("position.offsetX", parseInt(toUpdate.position.offsetX));
                updateElementSetting("position.offsetY", parseInt(toUpdate.position.offsetY));
            });
        }

        function onLayerMove(offset) {
            updateElementOffset(offset);
        }

        var layerRenderer = new STX.LayerRenderer({
            onLayerMouseDown: onLayerEditorMouseDown,
            onLayerMouseUp: onLayerMouseUp,
            onLayerMove: onLayerMove
        });

        $('.device[data-type="desktop"]').click();


        var $menu = $(".right-click-menu"),
            menu = $menu[0],
            menuVisible = false;

        var $btnCopy = $(".menu-option-copy").click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleMenu();

            copyLayerElements();
        });

        var $btnDuplicate = $(".menu-option-duplicate").click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleMenu();

            duplicateLayerElement();
        });

        var $btnDelate = $(".menu-option-delete").click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleMenu();

            deleteLayerElements();
        });

        var $btnPaste = $(".menu-option-paste").click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleMenu();

            pasteLayerElements();
        });

        $(".element-template").each(function() {
            var btnTemplate = {
                fontFamily: jQuery(this).css(["fontFamily"]).fontFamily,
                fontWeight: jQuery(this).css(["fontWeight"]).fontWeight
            };
            layerRenderer.loadFont(btnTemplate);
        });

        $(".element-template").click(function(e) {
            var style = this.style;
            var border = this.style.border;

            var el = getCurrentElement();
            var style = jQuery(this).css(["color", "backgroundColor", "fontSize", "fontWeight", "paddingTop", "paddingLeft", "paddingRight", "paddingBottom", "borderWidth", "borderColor", "borderStyle", "borderRadius", "fontFamily", "lineHeight", "letterSpacing", "textShadow"]);

            for (var property in style) {
                if (style.hasOwnProperty(property)) {
                    if (property === "color") el["textColor"] = style[property];
                    else el[property] = style[property];
                    updateCurrentElement(property);
                }
            }

            onLayerMouseDown(currentElement);
        });

        function copyLayerElements() {
            if (currentSlide < 0) return;

            layerClipboard = [];

            selectedElements.forEach(function(index) {
                var el = getElement(index);
                layerClipboard.push(JSON.parse(JSON.stringify(el)));
            });
        }

        function pasteLayerElements() {
            if (currentSlide < 0) return;

            var $elem = jQuery(document.activeElement);
            onElementSettingChanged(true);
            if (($elem.parents(".element-settings").length == 0) && ($elem.parent(".pcr-interaction").length == 0)) {
                layerClipboard.forEach(function(el) {
                    var clone = JSON.parse(JSON.stringify(el));
                    clone.id = null;
                    addLayerElement(clone);
                });

                $(".layer-item").each(function(key, val) {
                    val.id = key;
                });
            }
        }

        function deleteLayerElements() {
            if (currentSlide < 0) return;


            selectedElements.sort().reverse();

            selectedElements.forEach(function(index) {
                var id = options.slides[currentSlide].elements[index].id;
                var script = document.getElementById("s" + id);
                script.parentNode.removeChild(script);

                options.slides[currentSlide].elements.splice(index, 1);

                $(".layer-item")
                    .eq(index)
                    .remove();
            });

            selectedElements = [];

            $(".layer-item").each(function(key, val) {
                val.id = key;
            });

            renderLayers();
        }

        function duplicateLayerElement() {
            if (currentSlide < 0) return;

            var el = getCurrentElement();
            var clone = JSON.parse(JSON.stringify(el));
            clone.id = null;
            addLayerElement(clone);

            $(".layer-item").each(function(key, val) {
                val.id = key;
            });

            selectLastElement();
        }

        function inputFocused() {
            var type = document.activeElement.type;
            return type == "textarea" || type == "text" || type == "number";
        }

        var toggleMenu = function toggleMenu(command) {
            menu.style.display = command === "show" ? "block" : "none";
            menuVisible = !menuVisible;
        };

        var setPosition = function setPosition(_ref) {
            var top = _ref.top,
                left = _ref.left;
            menu.style.left = "".concat(left, "px");
            menu.style.top = "".concat(top, "px");
            toggleMenu("show");
        };

        window.addEventListener("click", function(e) {
            if (menuVisible) toggleMenu("hide");
        });
        window.addEventListener("contextmenu", function(e) {
            if ($(e.target.parentNode.parentNode.parentNode).hasClass("stx-layers-content")) {
                e.preventDefault();
                var origin = {
                    left: e.pageX - $slider.offset().left,
                    top: e.pageY - $slider.offset().top
                };
                setPosition(origin);

                $(".menu-option").addClass("menu-option-disabled");

                if (selectedElements.length) {
                    $btnCopy.removeClass("menu-option-disabled");
                    $btnDuplicate.removeClass("menu-option-disabled");
                    $btnDelate.removeClass("menu-option-disabled");
                }

                if (layerClipboard.length) $btnPaste.removeClass("menu-option-disabled");
            } else if ($(e.target).hasClass("element")) {
                e.preventDefault();
                var origin = {
                    left: e.pageX - $slider.offset().left,
                    top: e.pageY - $slider.offset().top
                };
                setPosition(origin);

                $(".menu-option-disabled").removeClass("menu-option-disabled");

                if (!layerClipboard.length) $btnPaste.addClass("menu-option-disabled");
            } else if ($(e.target).parents(".slider-preview-area").length) {
                e.preventDefault();
                var origin = {
                    left: e.pageX - $slider.offset().left,
                    top: e.pageY - $slider.offset().top
                };
                setPosition(origin);

                $(".menu-option").addClass("menu-option-disabled");

                if (layerClipboard.length) $btnPaste.removeClass("menu-option-disabled");
            } else {
                toggleMenu();
            }

            return false;
        });

        document.onkeydown = function(e) {
            e = e || window.event;

            if (inputFocused()) return;

            if (e.keyCode == "38") {
                if (currentElement > -1) {
                    var offset = { x: 0, y: 0 };
                    if (e.shiftKey) offset.y -= 10;
                    else offset.y -= 1;
                    updateElementOffset(offset);
                }
            } else if (e.keyCode == "40") {
                if (currentElement > -1) {
                    var offset = { x: 0, y: 0 };
                    if (e.shiftKey) offset.y += 10;
                    else offset.y += 1;
                    updateElementOffset(offset);
                }
            } else if (e.keyCode == "37" && !previewModal) {
                if (currentElement > -1) {
                    var offset = { x: 0, y: 0 };
                    if (e.shiftKey) offset.x -= 10;
                    else offset.x -= 1;
                    updateElementOffset(offset);
                } else if (currentSlide > -1) {
                    if (document.activeElement.tagName != "INPUT") showPrevSlide();
                }
            } else if (e.keyCode == "39" && !previewModal) {
                if (currentElement > -1) {
                    var offset = { x: 0, y: 0 };
                    if (e.shiftKey) offset.x += 10;
                    else offset.x += 1;
                    updateElementOffset(offset);
                } else if (currentSlide > -1) {
                    if (document.activeElement.tagName != "INPUT") showNextSlide();
                }
            } else if (e.keyCode == "46" || e.keyCode == "8") {

                deleteLayerElements();
            }
        };

        window.addEventListener("copy", function(e) {
            copyLayerElements();
        });

        window.addEventListener("paste", function() {
            pasteLayerElements();
        });

        $(".STX-has-units").each(function(el) {
            var units = this.dataset.units.split(",");
            var unit = this.dataset.unit || "px";
            var $units = $("<div>").addClass("STX-units");
            var $curr = $("<span>" + unit + "</span>").addClass("STX-current-unit");
            var $inputs = $(this)
                .parent()
                .find("input")
                .addClass("unit")
                .each(function() {
                    $curr.addClass("unit-" + this.name);
                });
            units.forEach(function(unit) {
                var $unit = $("<div>" + unit + "</div>")
                    .addClass("STX-unit")
                    .appendTo($units)
                    .click(function() {
                        $curr.text($(this).text());
                        $inputs.trigger("change");
                    });
            });
            $(this)
                .append($units)
                .append($curr)
                .on("mouseover", function() {
                    $units.show();
                })
                .on("mouseout", function() {
                    $units.hide();
                });
        });
    });
})(jQuery);

function stripslashes(str) {
    return (str + "").replace(/\\(.?)/g, function(s, n1) {
        switch (n1) {
            case "\\":
                return "\\";
            case "0":
                return "\u0000";
            case "":
                return "";
            default:
                return n1;
        }
    });
}
