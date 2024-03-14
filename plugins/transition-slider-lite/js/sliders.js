"use strict";
(function($) {
    $(document).ready(function() {
        var $loader = $(".STX-loader-container").hide();
        $(".wrap").show();

        var self = this;

        this.sliders = $.parseJSON(data.sliders);
        var arr = [];

        for (var key in this.sliders) {
            arr.push(this.sliders[key]);
        }
        this.sliders = arr;

        var modal = $(".STX-modal-window");
        var modalTitle = $(".STX-modal-window-title");
        var slider;
        var _s;

        var $templatesModal = $("#templates-modal");
        var $modalBackdrop = $(".media-modal-backdrop");
        var $body = $("body");
        var _pro = false;

        $("#import-sliders").tipsy({ gravity: "s", opacity: 1, title: function() { return "This feature is available in PRO version!" } });
        $("#import-sliders").css({
            'opacity':'0.5',
            'cursor':'not-allowed'
        });
        $(".STX-slider-export-btn").tipsy({ gravity: "s", opacity: 1, title: function() { return "This feature is available in PRO version!" } });
        $(".STX-slider-export-btn").css({
            'opacity':'0.5',
            'cursor':'not-allowed'
        });
        $(".STX-slider-export-btn a").css({
            'cursor':'not-allowed'
        });

        $(".dashicons-tablet").tipsy({ gravity: "n", opacity: 1, title: function() { return "This feature is available in PRO version!" } });
        $(".dashicons-smartphone").tipsy({ gravity: "n", opacity: 1, title: function() { return "This feature is available in PRO version!" } });
        $(".device-desktop").css("opacity", "1");

        $(".STX-designs").click(function() {
            $templatesModal.show();
            $modalBackdrop.show();
            $body.css("overflow", "hidden");
        });

        $(".media-modal-close").click(function(e) {
            $modalBackdrop.hide();
            $templatesModal.hide();
            $(".media-modal").hide();
            $body.css("overflow", "auto");
        });

        function getSelectedSliders() {
            var arr = [];
            $(".STX-edit-slider-box input:checked").each(function(index) {
                arr.push(this.name);
            });
            return arr;
        }

        function showLoader() {
            $loader.show();
        }

        function hideLoader() {
            $loader.hide();
        }

        function downloadObjectAsJson(exportObj, exportName) {
            var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(exportObj);
            var downloadAnchorNode = document.createElement("a");
            downloadAnchorNode.setAttribute("href", dataStr);
            downloadAnchorNode.setAttribute("download", exportName + ".txt");
            document.body.appendChild(downloadAnchorNode); 
            downloadAnchorNode.click();
            downloadAnchorNode.remove();
        }

        function addSlider(slider) {
            var slider_display_name;
            var url = "";
            var type;

            slider.instanceName != "" ? (slider_display_name = slider.instanceName) : (slider_display_name = slider.name);

            if (!jQuery.isEmptyObject(slider.slides)) {
                url = slider.slides.length > 0 ? slider.slides[0].thumbSrc || slider.slides[0].src : "";
            }

            if (/\.(jpg|jpeg|gif|png|webp)$/i.test(url)) {
                type = '<img title="Sort"  class="STX-image-preview" src="' + url + '">';
            } else if (/\.(mp4|ogg|ogv|webm)$/i.test(url)) {
                type = '<video title="Sort"  class="STX-video-preview" src="' + url + '"></video>';
            } else {
                type = '<div title="Sort"  class="STX-noslides"><p>No slides added</p></div>';
            }

            var markup = $(
                '<div class="STX-rect STX-isAdded STX-edit-slider-box" data-title="' +
                    slider_display_name +
                    '" data-sliderid="' +
                    slider.id +
                    '">' +
                    type +
                    '<div class="STX-box-overlay STX-box-on-hover">' +
                    '<a name="' +
                    slider.id +
                    '" class="STX-edit-link STX-btn STX-button-green STX-radius-global STX-uc STX-h5 edit" title="Edit ' +
                    slider_display_name +
                    '">Edit</a>' +
                    '<label class="STX-checkbox-container">' +
                    '<input type="checkbox" name="' +
                    slider.id +
                    '">' +
                    '<span class="STX-checkbox-checkmark"></span>' +
                    "</label>" +
                    '<div class="STX-slider-settings-btn-small btn-sm settings" name="' +
                    slider.id +
                    '" title="Settings">' +
                    '<div class="STX-slider-small-settings-wrapper">' +
                    '<div class="STX-slider-shortcode-btn-small shortcode btn-sm" name="' +
                    slider.id +
                    '"title="Copy shortcode"></div>' +
                    '<div class="STX-slider-view-btn-small view btn-sm" title="View"></div>' +
                    '<div class="STX-slider-copy-btn-small btn-sm duplicate" name="' +
                    slider.id +
                    '" title="Duplicate"></div>' +
                    "</div>" +
                    "</div>" +
                    '<div class="STX-slider-trash-btn-small trash" name="' +
                    slider.id +
                    '" title="Delete"></div>' +
                    "</div>" +
                    '<div class="STX-box-placeholder" data-align="">' +
                    '<div class="STX-box-placeholder-title">' +
                    '<a class="STX-h4">' +
                    slider_display_name +
                    "</a>" +
                    "</div>" +
                    '<div class="STX-box-placeholder-buttons">' +
                    "</div>" +
                    "</div>" +
                    "</div>"
            ).appendTo($(".STX-container"));
        }

        var keys = [];
        for (var key in this.sliders) {
            keys.push(key);
            if (typeof this.sliders[key].date == "undefined") this.sliders[key].date = "";
        }

        $("#sliders-table").empty();
        $(".STX-isAdded").remove();
        for (var i = 0; i < arr.length; i++) {
            var slider = arr[i];
            if (slider) addSlider(slider);
        }

        sortByDate(true);

        $(".edit").click(function(e) {
            e.preventDefault();
            var id = this.getAttribute("name");
            window.location = window.location.origin + window.location.pathname + "?page=transition_slider_admin&action=edit&id=" + id;
        });
        $(".duplicate").click(function(e) {
            e.preventDefault();
            var id = this.getAttribute("name");
            duplicateSlider(id);
        });
        $(".shortcode").click(function(e) {
            e.preventDefault();
            var id = this.getAttribute("name");
            var copied = "[transitionslider id='" + id + "']";
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(copied).select();
            document.execCommand("copy");
            $temp.remove();
        });
        $(".trash").click(function(e) {
            e.preventDefault();
            var id = this.getAttribute("name");
            deleteSliders([id]);
        });
        $(".undo").click(function(e) {
            e.preventDefault();
            window.location = window.location.origin + window.location.pathname + "?page=transition_slider_admin&action=undo";
        });

        $('.STX-edit-slider-box input[type="checkbox"]').change(function() {
            $('.STX-edit-slider-box[data-sliderid="' + this.name + '"]').toggleClass("selected", this.checked);
            updateHeader();
        });

        function updateHeader() {
            var selected = getSelectedSliders();

            $(".delete_all_sliders").toggle(selected.length > 0);
            $(".STX-slider-export-btn").toggle(selected.length > 0);
        }

        updateHeader();

        function addTemplateSliders(arr) {
            var $templates = $(".STX-templates");

            arr.forEach(function(template) {
                var bgUrl = window.data.stx_plugin_url + "assets/templates/" + template.name + ".jpg";

                var btnText = template.free || _pro ? "Import" : "Buy Pro"
                var btnClass = template.free || _pro ? "template-btn template-import-btn" : "template-btn template-buy-pro"

                var $template = jQuery(
                    '<div class="template">' +
                        '<div class="template-content">' +
                            '<a href="#" id="' + template.name + '" class="'+btnClass+'">' +
                                btnText +
                            '</a>' +
                            '<a href="https://transitionslider.com/templates/' + template.slug + '" target="_blank" class="template-btn template-preview-btn">Preview</a>' +
                        '</div>' +
                        '<div class="template-title-bottom">'+template.title+'</div>'+
                    "</div>")
                    .appendTo($templates)
                    .css("background", "url(" + bgUrl + ")");

                });
        }

        var templateSliders = [
            {
                name: "business",
                title: "Business",
                slug: 'business',
                free: true
            },
            {
                name: "agency",
                title: "Agency",
                slug: 'agency',
                free: true
            },
            {
                name: "innovative_multipurpose",
                title: "Innovative multipurpose",
                slug: 'innovative-multipurpose',
                free: true
            },
            {
                name: "car",
                title: "Car sales",
                slug: 'car-sales',
                free: true
            },
            {
                name: "creative_agency",
                title: "Creative agency",
                slug: 'creative-agency',
                free: true
            },
            {
                name: "full_width_responsive_slider",
                title: "Full width Responsive Slider",
                slug: 'full-width-responsive-slider',
                free: true
            },
            {
                name: "horizontal_thumbnails_slider",
                title: "Horizontal Thumbnails Slider",
                slug: 'horizontal-thumbnails-slider',
                free: true
            },
            {
                name: "vertical_thumbnails_slider",
                title: "Vertical Thumbnails Slider",
                slug: 'vertical-thumbnails-slider',
                free: true
            },
            {
                name: "fullscreen_slider_with_video_background",
                title: "Fullscreen Slider with Video Background",
                slug: 'fullscreen-slider-with-video-background',
                free: true
            },
            {
                name: "video_gallery_slider_with_horizontal_thumbnails",
                title: "Video Gallery Slider with Horizontal Thumbnails",
                slug: 'video-gallery-slider-with-horizontal-thumbnails',
                free: true
            },
            {
                name: "image_gallery_slider",
                title: "Image Gallery Slider",
                slug: 'image-gallery-slider',
                free: true
            },
			{
				name: "cinematic_lightbox_slider",
                title: "Cinematic Lightbox Slider",
                slug: 'cinematic-lightbox-slider'
			},
			{
				name: "static_app_slider",
                title: "Static App Slider",
                slug: 'static-app-slider'
			},
			{
				name: "real_estate_with_tabs",
                title: "Real estate with Tabs",
                slug: 'real-estate-with-tabs'
			},
            {
                name: "business2",
                title: "Business 2",
                slug: 'business-corporate',
            },
            {
                name: "photograph",
                title: "Photograph",
                slug: 'photograph'
            },
            {
                name: "spa",
                title: "Spa",
                slug: 'spa',
            },
            {
                name: "clothing_collection2",
                title: "Clothing collection 2",
                slug: 'clothing-collection-shop'
            },
            {
                name: "travel2",
                title: "Travel 2",
                slug: 'travel-adventure'
            },
            {
                name: "car2",
                title: "Car 2",
                slug: 'car-shop'
            },
            {
                name: "gym",
                title: "Gym",
                slug: 'gym'
            },
            {
                name: "fashion",
                title: "Fashion",
                slug: 'fashion'
            },
            {
                name: "agency2",
                title: "Agency 2",
                slug: 'agency-theme'
            },
            {
                name: "sport_news",
                title: "Sport news",
                slug: 'sport-news'
            },
            {
                name: "real_estate",
                title: "Real estate",
                slug: 'real-estate'
            },
            {
                name: "urban_shop",
                title: "Urban shop",
                slug: 'urban-shop'
            },
            {
                name: "designer_portfolio",
                title: "Designer portfolio",
                slug: 'designer-portfolio'
            },
            {
                name: "education",
                title: "Education",
                slug: 'education'
            },
            {
                name: "luxuryspa",
                title: "Luxury spa",
                slug: 'luxury-spa'
            },

            {
                name: "fitness",
                title: "Fitness",
                slug: 'fitness'
            },
            {
                name: "products",
                title: "Products sales",
                slug: 'products-sale'
            },
            {
                name: "travel",
                title: "Travel",
                slug: 'travel'
            },
            {
                name: "marketing",
                title: "Marketing",
                slug: 'marketing'
            },
            {
                name: "video_block",
                title: "Video block",
                slug: 'video-block'
            },
            {
                name: "video_hero",
                title: "Hero video",
                slug: 'hero-video'
            },
            {
                name: "furniture_stores",
                title: "Furniture stores",
                slug: 'furniture-stores'
            },
            {
                name: "clothing_collection",
                title: "Clothing collection",
                slug: 'clothing-collection'
            },
            {
                name: "christmas",
                title: "Christmas",
                slug: 'christmas'
            },
            {
                name: "creative_multipurpose",
                title: "Creative multipurpose",
                slug: 'creative-multi-purpose'
            },
            {
                name: "hotel",
                title: "Hotel",
                slug: 'hotel'
            },
            {
                name: "seo_and_digital_marketing",
                title: "SEO & digital marketing",
                slug: 'seo-digital-marketing'
            },
            {
                name: "restaurant",
                title: "Restaurant",
                slug: 'restaurant'
            },
            {
                name: "tasty_food",
                title: "Tasty food",
                slug: 'tasty-food'
            },
            {
                name: "landing_page",
                title: "Landing page",
                slug: 'landing-page'
            },
            {
                name: "landing_mockup",
                title: "Landing mockup",
                slug: 'landing-mockup'
            },
            {
                name: "photo_studio",
                title: "Photo studio",
                slug: 'photo-studio'
            },
            {
                name: "animated_layers",
                title: "Animated layers",
                slug: 'animated-layers'
            }

        ];

        addTemplateSliders(templateSliders);

        function duplicateSlider(id) {
            var data = "action=transitionslider_duplicate&security=" + window.data.stx_nonce + "&currentId=" + id;

            $.ajax({
                type: "POST",
                url: "admin-ajax.php?page=transition_slider_admin",
                data: data,

                success: function(data, textStatus, jqXHR) {
                    location.reload();
                },

                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("Status: " + textStatus);
                    alert("Error: " + errorThrown);
                }
            });
        }

        function deleteSliders(arr) {
            var msg = "";
            var data = "action=transitionslider_delete&security=" + window.data.stx_nonce;

            if (arr) {
                if (arr.length == 1) msg = "Delete slider";
                else msg = "Delete sliders";
                data += "&currentId=" + arr;
            } else {
                msg = "Delete sliders";
            }

            if (confirm(msg + ". Are you sure?")) {
                $.ajax({
                    type: "POST",
                    url: "admin-ajax.php?page=transition_slider_admin",
                    data: data,

                    success: function(data, textStatus, jqXHR) {
                        location.reload();
                    },

                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Status: " + textStatus);
                        alert("Error: " + errorThrown);
                    }
                });
            }
        }
        function getSliderOptions(id, onCompete) {
            var data = "action=transitionslider_get_slider&security=" + window.data.stx_nonce + "&currentId=" + id;

            $.ajax({
                type: "POST",
                url: "admin-ajax.php?page=transition_slider_admin",
                data: data,

                success: function(data, textStatus, jqXHR) {
                    onCompete.call(this, JSON.parse(data));
                },

                error: function(XMLHttpRequest, textStatus, errorThrown) {
                }
            });
        }

        $(".delete_all_sliders").click(function(e) {
            e.preventDefault();
            var selected = getSelectedSliders();
            deleteSliders(selected);
        });
        $(".STX-slider-export-btn").click(function(e) {
            e.preventDefault();
            });
        $(".bulkactions-apply").click(function() {
            var action = $(this)
                .parent()
                .find("select")
                .val();
            if (action != "-1") {
                var list = [];
                $(".row-checkbox").each(function() {
                    if ($(this).is(":checked")) list.push($(this).attr("name"));
                });
                if (list.length > 0) {
                    window.location = window.location.origin + window.location.pathname + "?page=transition_slider_admin&action=delete&id=" + list.join(",");
                }
            }
        });

        $(".STX-dashboard-wrapp").show();

        $(".STX-btn-menu").click(function(e) {
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
        $('.STX-btn-menu[data-form-name="general"]')
            .parent()
            .addClass("STX-nav-active");

        $(".STX-btn-topbar").click(function(e) {
            $(".STX-form-tab").hide();
            $(".options_" + $(this).attr("data-form-name")).fadeIn("fast", function() {});
        });

        $(".copy-shortcode").click(function() {
            var id = $(this).attr("id");
            var copied = "[transitionslider id='" + id + "']";
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(copied).select();
            document.execCommand("copy");
            $temp.remove();
            $(".copy-shortcode").text("Copy");
            $(this)
                .parent()
                .addClass("slider-highlightText");
            $(".copy-shortcode").removeClass("STX-copy-shortcode-highlight");
            $(this).addClass("STX-copy-shortcode-highlight");
            $(this).text("Copied!");
        });

        $(".dropdown").click(function() {
            $(this)
                .attr("tabindex", 1)
                .focus();
            $(this).toggleClass("active");
            $(this)
                .find(".dropdown-menu")
                .slideToggle(300);
        });
        $(".dropdown").focusout(function() {
            $(this).removeClass("active");
            $(this)
                .find(".dropdown-menu")
                .slideUp(300);
        });
        $(".dropdown .dropdown-menu li").click(function() {
            $(this)
                .parents(".dropdown")
                .find("span")
                .text($(this).text());
            $(this)
                .parents(".dropdown")
                .find("input")
                .attr("value", $(this).attr("id"));
            $(this)
                .parents(".dropdown")
                .find("input")
                .attr("selected", "true");
        });

        $(".dropdown-menu li").click(function() {
            var getVal = $(this)
                .parents(".dropdown")
                .find("input")
                .val();
            switch (getVal) {
                case "newestFirst":
                    sortByDate(true);
                    break;
                case "oldestFirst":
                    sortByDate();
                    break;
                case "selectAll":
                    $(".STX-checkbox-container input")
                        .prop("checked", true)
                        .trigger("change");
                    break;
                case "selectNone":
                    $(".STX-checkbox-container input")
                        .prop("checked", false)
                        .trigger("change");
                    break;
            }
        });

        addListeners();

        function sortByDate(asc) {
            self.sliders.sort(function(a, b) {
                a.date = a.date || "2000-01-01 00:00:00";
                b.date = b.date || "2000-01-01 00:00:00";
                return new Date(a.date) - new Date(b.date);
            });
            if (asc) self.sliders.reverse();
            self.sliders.forEach(function(slider) {
                $('.STX-edit-slider-box[data-sliderid="' + slider.id + '"]').appendTo($(".STX-container"));
            });
        }

        function convertStrings(obj) {
            $.each(obj, function(key, value) {
                if (typeof value == "object" || typeof value == "array") {
                    convertStrings(value);
                } else if (!isNaN(value)) {
                    if (obj[key] === "") delete obj[key];
                    else obj[key] = Number(value);
                } else if (value === "true") {
                    obj[key] = true;
                } else if (value === "false") {
                    obj[key] = false;
                }
            });
        }

        var currentSliderOptions

        function addListeners() {
            $(".view").click(function(e) {
                e.preventDefault();

                $("#preview-slider-modal").show();
                $("#slider-preview").hide();

                $body.css("overflow", "hidden");

                var id = $(this)
                    .parent()
                    .parent()
                    .attr("name");

                getSliderOptions(id, function(_s) {
                    convertStrings(_s);

                    if (_s.navigation && !_s.navigation.enable) _s.navigation = false;
                    if (_s.wheelNavigation && !_s.wheelNavigation.enable) _s.wheelNavigation = false;
                    if (_s.pagination && !_s.pagination.enable) _s.pagination = false;
                    if (_s.thumbs && !_s.thumbs.enable) _s.thumbs = false;
                    if (_s.keyboard && !_s.keyboard.enable) _s.keyboard = false;
                    if (_s.autoplay && !_s.autoplay.enable) _s.autoplay = false;
                    if (_s.shadow && _s.shadow == "off") _s.shadow = null;
                    if (_s.initialSlide) _s.initialSlide = parseInt(_s.initialSlide);
                    _s.hashNavigation = false;
                    _s.forceResponsive = false;
                    _s.forceResponsiveMobile = false;
                    _s.forceResponsiveTablet = false;

                    for (var key in _s.slides) {
                        if (_s.slides[key].elements) {
                            for (var key2 in _s.slides[key].elements) {
                                delete _s.slides[key].elements[key2].node;
                            }
                        }
                        _s.slides[key].urlTarget = _s.slides[key].urlTarget == true || _s.slides[key].urlTarget == "_blank" ? "_blank" : "_self";
                    }

                    currentSliderOptions = _s

                    if ($.isEmptyObject($("#slider-preview").data())) $("#slider-preview").transitionSlider(_s, function () {
                        $("#slider-preview").show();
                    });
                    else {
                        slider = $("#slider-preview").data("transitionSlider");
                        slider.reloadSlider(_s, function () {
                            $("#slider-preview").show();
                        });
                    }
                });
            });

            $(".settings").each(function(i, item) {
                $(item).mouseover(function() {
                    $(this)
                        .parent()
                        .find(".STX-slider-small-settings-wrapper")
                        .stop()
                        .fadeIn("fast");
                });
                $(item).mouseout(function() {
                    $(this)
                        .parent()
                        .find(".STX-slider-small-settings-wrapper")
                        .stop()
                        .fadeOut("fast");
                });
            });
        }

        function openModal(type, title) {
            modal.fadeIn("fast", function() {});

            modalTitle.text(title);
            $(".slider_preview").hide();

            modal.removeClass("previewActive");
            modal.removeClass("importActive");

            switch (type) {
                case "import":
                    modal.addClass("importActive");
                    importInput.show();
                    importText.show();
                    break;
            }
        }
        function closeModal() {
            $("#preview-slider-modal").fadeOut("fast", function() {});

            if (!$.isEmptyObject($("#slider-preview").data())) {
                slider = $("#slider-preview").data("transitionSlider");
                slider.stopSlider();
            }
        }

        function closeImportModal() {
            modal.fadeOut("fast", function() {});
        }

        $(".STX-modal-close-preview").click(function(e) {
            closeModal();
        });

        $(".STX-modal-close-btn").click(function(e) {
            closeImportModal();
        });

        $("#import-sliders").click(function(e) {
            });

        $(".template-buy-pro").click(function(e) {
            window.open("https://codecanyon.net/item/transition-slider-wordpress-plugin/23531533?ref=creativeinteractivemedia", "_blank");
        })
        $(".template-import-btn").click(function(e) {

            e.preventDefault()

            showLoader();

            $.ajax({
                dataType: "text",
                url: window.data.stx_plugin_url + "assets/templates/" + this.id + ".json",
                data: "",
                success: function(data) {
                    var ajaxUrl = "admin-ajax.php?page=transition_slider_admin";
                    var slider = data;

                    $.ajax({
                        type: "POST",

                        url: ajaxUrl,

                        data: {
                            slider: slider,
                            security: window.data.stx_nonce,
                            action: "transitionslider_import"
                        },

                        success: function(data, textStatus, jqXHR) {
                            location.reload();
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            hideLoader();
                        }
                    });
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                    hideLoader();
                }
            });
        });
    });
})(jQuery);
