"use strict";

window.Skt = window.Skt || {};

(function ($, Skt, w) {
  var $window = $(w);

  function debounce(func, wait, immediate) {
    var timeout;
    return function () {
      var context = this,
          args = arguments;

      var later = function later() {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };

      var callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  }

  $window.on("elementor/frontend/init", function () {
    if (typeof haDisplayCondition != "undefined" && haDisplayCondition.status == "true") {
      // Set user time in cookie
      var SktLocalTimeZone = new Date().toString().match(/([A-Z]+[\+-][0-9]+.*)/)[1];
      var skt_addons_elementor_secure = document.location.protocol === "https:" ? "secure" : "";
      document.cookie = "SktLocalTimeZone=" + SktLocalTimeZone + ";SameSite=Strict;" + skt_addons_elementor_secure;
    }

    var CountDown = function CountDown($scope) {
      var $item = $scope.find(".skt-countdown");
      var $countdown_item = $item.find(".skt-countdown-item");
      var $end_action = $item.data("end-action");
      var $redirect_link = $item.data("redirect-link");
      var $end_action_div = $item.find(".skt-countdown-end-action");
      var $editor_mode_on = $scope.hasClass("elementor-element-edit-mode");
      $item.countdown({
        end: function end() {
          if (("message" === $end_action || "img" === $end_action) && $end_action_div !== undefined) {
            $countdown_item.css("display", "none");
            $end_action_div.css("display", "block");
          } else if ("url" === $end_action && $redirect_link !== undefined && $editor_mode_on !== true) {
            window.location.replace($redirect_link);
          }
        }
      });
    };

    var SliderBase = elementorModules.frontend.handlers.Base.extend({
      onInit: function onInit() {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.run();
      },
      getDefaultSettings: function getDefaultSettings() {
        return {
          selectors: {
            container: ".skt-slider-container"
          }
        };
      },
      getDefaultElements: function getDefaultElements() {
        var selectors = this.getSettings("selectors");
        return {
          $container: this.$element.find(selectors.container)
        };
      },
      // onElementChange: function () {
      // 	haSwiper.destroy(true, true);
      // 	this.run();
      // },
      getReadySettings: function getReadySettings() {
        var $this = this;
        var slidesPerView = parseInt(this.getElementSettings("slides_per_view")) || 1;

        if (this.getElementSettings("thumbs_navigation") == "yes") {
          var selectorThumbs = this.elements.$container.find(".skt-slider-gallery-thumbs");
          var haGallaryThumbs = new HaSwiper(selectorThumbs[0], {
            spaceBetween: this.getElementSettings("space_between_thumbs"),
            // slidesPerView: this.getElementSettings('thumbs_per_view'),
            // loop: this.getElementSettings('infinity_loop'),
            freeMode: true,
            watchSlidesVisibility: true,
            watchSlidesProgress: true
          });
        }

        var settings = {
          direction: this.getElementSettings("slider_direction"),
          slidesPerView: slidesPerView || 1,
          spaceBetween: parseInt(this.getElementSettings("space_between_slides")) || 0,
          loop: !!(this.getElementSettings("infinity_loop") || false),
          speed: parseInt(this.getElementSettings("effect_speed")),
          effect: this.getElementSettings("slider_type") == "multiple" ? this.getElementSettings("effect_multiple") : this.getElementSettings("effect"),
          skt_addons_elementor_animation: this.getElementSettings("slider_content_animation"),
          sliderType: this.getElementSettings("slider_type")
        };

        if (this.getElementSettings("effect") == "flip") {
          settings.flipEffect = {
            limitRotation: true,
            slideShadows: true
          };
        }

        if (this.getElementSettings("effect") == "cube") {
          settings.cubeEffect = {
            shadow: true,
            slideShadows: true,
            shadowOffset: 20,
            shadowScale: 0.94
          };
        }

        if (this.getElementSettings("effect_multiple") == "coverflow") {
          settings.coverflowEffect = {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: true
          };
        }

        if (this.getElementSettings("slider_type") == "multiple") {
          var bpObj = {
            desktop: {
              slidesPerView: slidesPerView || 1,
              spaceBetween: parseInt(this.getElementSettings("space_between_slides")) || 0
            }
          };
          var breakpoints = elementorFrontend.config.responsive.breakpoints;
          $.each(breakpoints, function (idx, val) {
            if (val.is_enabled) {
              bpObj[idx] = {
                screenSize: val.value,
                slidesPerView: parseInt($this.getElementSettings("slides_per_view_" + idx)) || slidesPerView,
                spaceBetween: parseInt($this.getElementSettings("space_between_slides_" + idx)) || 0
              };
            }
          });
          settings.customBreakpoints = bpObj;
          settings.customMultiple = true;
        }

        if (this.getElementSettings("autoplay") == "yes") {
          settings.autoplay = {
            delay: this.getElementSettings("autoplay_speed"),
            disableOnInteraction: false,
            stopOnLastSlide: !(this.getElementSettings("infinity_loop") || false)
          };
        }

        if (this.getElementSettings("arrow_navigation") == "yes") {
          var selectorNext = this.elements.$container.find(".skt-slider-next");
          var selectorPrev = this.elements.$container.find(".skt-slider-prev");
          settings.navigation = {
            nextEl: selectorNext[0],
            prevEl: selectorPrev[0]
          };
        }

        if (this.getElementSettings("pagination_type") == "dots") {
          var selectorPagi = this.elements.$container.find(".skt-slider-pagination");
          settings.pagination = {
            el: selectorPagi[0],
            clickable: true
          };
        }

        if (this.getElementSettings("pagination_type") == "progressbar") {
          var selectorPagi = this.elements.$container.find(".skt-slider-pagination");
          settings.pagination = {
            el: selectorPagi[0],
            clickable: true,
            type: "progressbar"
          };
        }

        if (this.getElementSettings("pagination_type") == "numbers") {
          var selectorPagi = this.elements.$container.find(".skt-slider-pagination");
          settings.pagination = {
            el: selectorPagi[0],
            clickable: true,
            type: this.getElementSettings("number_pagination_type"),
            renderBullet: function renderBullet(index, className) {
              return '<span class="' + className + '">' + (index + 1) + "</span>";
            },
            renderFraction: function renderFraction(currentClass, totalClass) {
              return '<span class="' + currentClass + '"></span>' + "<span>/</span>" + '<span class="' + totalClass + '"></span>';
            }
          };
        }

        if (this.getElementSettings("scroll_bar") == "yes") {
          var selectorScroll = this.elements.$container.find(".skt-slider-scrollbar");
          settings.scrollbar = {
            el: selectorScroll[0],
            hide: this.getElementSettings("scroll_bar_visibility") == "true",
            draggable: true
          };
        }

        if (this.getElementSettings("thumbs_navigation") == "yes") {
          settings.thumbs = {
            swiper: haGallaryThumbs
          };
        }

        return $.extend({}, settings);
      },
      run: function run() {
        var elContainer = this.elements.$container;
        var slider = elContainer.find(".skt-slider-container");
        var readySettings = this.getReadySettings();
        var sliderObj = new HaSwiper(slider[0], readySettings);
        sliderObj.on("slideChange", function () {
          console.log("slide change");

          if (readySettings.sliderType == "multiple") {
            return;
          }

          var aI = sliderObj.activeIndex;
          var elSlide = elContainer.find(".skt-slider-slide");
          var elSlideContent = elContainer.find(".skt-slider-content");
          var currentSlide = elSlideContent.eq(aI);
          currentSlide.hide();

          if (currentSlide.length <= 0) {// elSlide.eq(aI).find('.elementor-invisible').css('visibility', 'visible');
          }

          setTimeout(function () {
            currentSlide.show();
          }, readySettings.speed);
          if (elSlide.eq(aI).find(".elementor-invisible, .animated").each(function (e, t) {
            var i = $(this).data("settings");

            if (i && (i._animation || i.animation)) {
              var n = i._animation_delay ? i._animation_delay : 0,
                  a = i._animation || i.animation;
              $(this).removeClass("elementor-invisible");
              $(this).addClass(a + " animated");
              console.log($(this));
            }
          })) ;
        });
        sliderObj.on("transitionEnd", function () {
          console.log("transitionEnd event call");
          var aI = sliderObj.activeIndex;
          var elSlide = elContainer.find(".skt-slider-slide");
          var elSlideContent = elContainer.find(".skt-slider-content");
          var currentSlide = elSlideContent.eq(aI);
          setTimeout(function () {
            if (elSlide.eq(aI).find(".animated").each(function (e, t) {
              var i = $(this).data("settings");

              if (i && (i._animation || i.animation)) {
                var n = i._animation_delay ? i._animation_delay : 0,
                    a = i._animation || i.animation; // $(this).removeClass("animated");

                $(this).removeClass(a); // $(this).addClass("elementor-invisible");
                // $(this).css("visibility", "visible");

                console.log($(this));
              }
            })) ;
          }, readySettings.speed);
        });
      }
    });
    var CarouselBase = elementorModules.frontend.handlers.Base.extend({
      onInit: function onInit() {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.run();
      },
      getDefaultSettings: function getDefaultSettings() {
        return {
          selectors: {
            container: ".skt-carousel-container"
          },
          arrows: false,
          dots: false,
          checkVisible: false,
          infinite: true,
          slidesToShow: 3,
          rows: 0,
          prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-chevron-left"></i></button>',
          nextArrow: '<button type="button" class="slick-next"><i class="fa fa-chevron-right"></i></button>'
        };
      },
      getDefaultElements: function getDefaultElements() {
        var selectors = this.getSettings("selectors");
        return {
          $container: this.findElement(selectors.container)
        };
      },
      onElementChange: function onElementChange() {
        this.elements.$container.slick("unslick");
        this.run();
      },
      getReadySettings: function getReadySettings() {
        var settings = {
          infinite: !!this.getElementSettings("loop"),
          autoplay: !!this.getElementSettings("autoplay"),
          autoplaySpeed: this.getElementSettings("autoplay_speed"),
          speed: this.getElementSettings("animation_speed"),
          centerMode: !!this.getElementSettings("center"),
          vertical: !!this.getElementSettings("vertical"),
          slidesToScroll: 1
        };

        switch (this.getElementSettings("navigation")) {
          case "arrow":
            settings.arrows = true;
            break;

          case "dots":
            settings.dots = true;
            break;

          case "both":
            settings.arrows = true;
            settings.dots = true;
            break;
        }

        settings.slidesToShow = parseInt(this.getElementSettings("slides_to_show")) || 1;
        settings.responsive = [{
          breakpoint: elementorFrontend.config.breakpoints.lg,
          settings: {
            slidesToShow: parseInt(this.getElementSettings("slides_to_show_tablet")) || settings.slidesToShow
          }
        }, {
          breakpoint: elementorFrontend.config.breakpoints.md,
          settings: {
            slidesToShow: parseInt(this.getElementSettings("slides_to_show_mobile")) || parseInt(this.getElementSettings("slides_to_show_tablet")) || settings.slidesToShow
          }
        }];
        return $.extend({}, this.getSettings(), settings);
      },
      run: function run() {
        this.elements.$container.slick(this.getReadySettings());
      }
    }); // Source Code

    var SourceCode = function SourceCode($scope) {
      var $item = $scope.find(".skt-source-code");
      var $lng_type = $item.data("lng-type");
      var $after_copy_text = $item.data("after-copy");
      var $code = $item.find("code.language-" + $lng_type);
      var $copy_btn = $scope.find(".skt-copy-code-button");
      $copy_btn.on("click", function () {
        var $temp = $("<textarea>");
        $(this).append($temp);
        $temp.val($code.text()).select();
        document.execCommand("copy");
        $temp.remove();

        if ($after_copy_text.length) {
          $(this).text($after_copy_text);
        }
      });

      if ($lng_type !== undefined && $code !== undefined) {
        Prism.highlightElement($code.get(0));
      }
    }; // Animated text


    var AnimatedText = function AnimatedText($scope) {
      var $item = $scope.find(".cd-headline"),
          $animationDelay = $item.data("animation-delay");
      sktAnimatedText({
        selector: $item,
        animationDelay: $animationDelay
      });
    }; //Instagram Feed


    var InstagramFeed = function InstagramFeed($scope) {
      var button = $scope.find(".skt-insta-load-more");
      var instagram_wrap = $scope.find(".skt-insta-default");
      button.on("click", function (e) {
        e.preventDefault();
        var $self = $(this),
            query_settings = $self.data("settings"),
            total = $self.data("total"),
            items = $scope.find(".skt-insta-item").length;
        $.ajax({
          url: SktProLocalize.ajax_url,
          type: "POST",
          data: {
            action: "skt_addons_elementor_instagram_feed_action",
            security: SktProLocalize.nonce,
            query_settings: query_settings,
            loaded_item: items
          },
          success: function success(response) {
            if (total > items) {
              $(response).appendTo(instagram_wrap);
            } else {
              $self.text("All Loaded").addClass("loaded");
              setTimeout(function () {
                $self.css({
                  display: "none"
                });
              }, 800);
            }
          },
          error: function error(_error) {}
        });
      });
    }; //Scrolling Image


    var ScrollingImage = elementorModules.frontend.handlers.Base.extend({
      onInit: function onInit() {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.wrapper = this.$element.find(".skt-scrolling-image-wrapper");
        this.run();
      },
      onElementChange: function onElementChange() {
        this.run();
      },
      run: function run() {
        var container_height = this.wrapper.innerHeight(),
            container_width = this.wrapper.innerWidth(),
            image_align = this.wrapper.data("align"),
            scroll_direction = this.wrapper.data("scroll-direction"),
            items = this.wrapper.find(".skt-scrolling-image-container"),
            single_image_box = items.find(".skt-scrolling-image-item"),
            scroll = "scroll" + image_align + scroll_direction + parseInt(container_height) + parseInt(container_width),
            duration = this.wrapper.data("duration"),
            direction = "normal",
            horizontal_align_width = 10,
            vertical_align_height = 10;
        var start = {
          transform: "translateY(" + container_height + "px)"
        },
            end = {
          transform: "translateY(-101%)"
        };

        if ("bottom" === scroll_direction || "right" === scroll_direction) {
          direction = "reverse";
        }

        if ("skt-horizontal" === image_align) {
          start = {
            transform: "translateX(" + container_width + "px)"
          };
          end = {
            transform: "translateX(-101%)"
          };
          single_image_box.each(function () {
            horizontal_align_width += $(this).outerWidth(true);
          });
          items.css({
            width: horizontal_align_width,
            display: "flex"
          });
          items.find("img").css({
            "max-width": "100%"
          });
          single_image_box.css({
            display: "inline-flex"
          });
        }

        if ("skt-vertical" === image_align) {
          single_image_box.each(function () {
            vertical_align_height += $(this).outerHeight(true);
          });
        }

        $.keyframe.define([{
          name: scroll,
          "0%": start,
          "100%": end
        }]);
        items.playKeyframe({
          name: scroll,
          duration: duration.toString() + "ms",
          timingFunction: "linear",
          delay: "0s",
          iterationCount: "infinite",
          direction: direction,
          fillMode: "none",
          complete: function complete() {}
        });
      }
    }); //Pricing Table ToolTip

    var PricingTableToolTip = function PricingTableToolTip($scope) {
      var tooltip_area = $scope.find(".skt-pricing-table-feature-tooltip");
      tooltip_area.on({
        mouseenter: function mouseenter(e) {
          var $this = $(this),
              direction = $this[0].getBoundingClientRect(),
              tooltip = $this.find(".skt-pricing-table-feature-tooltip-text"),
              tooltipW = tooltip.outerWidth(true),
              tooltipH = tooltip.outerHeight(true),
              W_width = $(window).width(),
              W_height = $(window).height();
          tooltip.css({
            left: "0",
            right: "auto",
            top: "calc(100% + 1px)",
            bottom: "auto"
          });

          if (W_width - direction.left < tooltipW && direction.right < tooltipW) {
            tooltip.css({
              left: "calc(50% - (" + tooltipW + "px/2))"
            });
          } else if (W_width - direction.left < tooltipW) {
            tooltip.css({
              left: "auto",
              right: "0"
            });
          }

          if (W_height - direction.bottom < tooltipH) {
            tooltip.css({
              top: "auto",
              bottom: "calc(100% + 1px)"
            });
          }
        }
      });
    };

    var TabHandlerBase = elementorModules.frontend.handlers.Base.extend({
      $activeContent: null,
      getDefaultSettings: function getDefaultSettings() {
        return {
          selectors: {
            tabTitle: ".skt-tab__title",
            tabContent: ".skt-tab__content"
          },
          classes: {
            active: "skt-tab--active"
          },
          showTabFn: "show",
          hideTabFn: "hide",
          toggleSelf: false,
          hidePrevious: true,
          autoExpand: true
        };
      },
      getDefaultElements: function getDefaultElements() {
        var selectors = this.getSettings("selectors");
        return {
          $tabTitles: this.findElement(selectors.tabTitle),
          $tabContents: this.findElement(selectors.tabContent)
        };
      },
      activateDefaultTab: function activateDefaultTab() {
        var settings = this.getSettings();

        if (!settings.autoExpand || "editor" === settings.autoExpand && !this.isEdit) {
          return;
        }

        var defaultActiveTab = this.getEditSettings("activeItemIndex") || 1,
            originalToggleMethods = {
          showTabFn: settings.showTabFn,
          hideTabFn: settings.hideTabFn
        }; // Toggle tabs without animation to avoid jumping

        this.setSettings({
          showTabFn: "show",
          hideTabFn: "hide"
        });
        this.changeActiveTab(defaultActiveTab); // Return back original toggle effects

        this.setSettings(originalToggleMethods);
      },
      deactivateActiveTab: function deactivateActiveTab(tabIndex) {
        var settings = this.getSettings(),
            activeClass = settings.classes.active,
            activeFilter = tabIndex ? '[data-tab="' + tabIndex + '"]' : "." + activeClass,
            $activeTitle = this.elements.$tabTitles.filter(activeFilter),
            $activeContent = this.elements.$tabContents.filter(activeFilter);
        $activeTitle.add($activeContent).removeClass(activeClass);
        $activeContent[settings.hideTabFn]();
      },
      activateTab: function activateTab(tabIndex) {
        var settings = this.getSettings(),
            activeClass = settings.classes.active,
            $requestedTitle = this.elements.$tabTitles.filter('[data-tab="' + tabIndex + '"]'),
            $requestedContent = this.elements.$tabContents.filter('[data-tab="' + tabIndex + '"]');
        $requestedTitle.add($requestedContent).addClass(activeClass);
        $requestedContent[settings.showTabFn]();
      },
      isActiveTab: function isActiveTab(tabIndex) {
        return this.elements.$tabTitles.filter('[data-tab="' + tabIndex + '"]').hasClass(this.getSettings("classes.active"));
      },
      bindEvents: function bindEvents() {
        var _this = this;

        this.elements.$tabTitles.on({
          keydown: function keydown(event) {
            if ("Enter" === event.key) {
              event.preventDefault();

              _this.changeActiveTab(event.currentTarget.getAttribute("data-tab"));
            }
          },
          click: function click(event) {
            event.preventDefault();

            _this.changeActiveTab(event.currentTarget.getAttribute("data-tab"));
          }
        });
      },
      onInit: function onInit() {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.activateDefaultTab();
      },
      onEditSettingsChange: function onEditSettingsChange(propertyName) {
        if ("activeItemIndex" === propertyName) {
          this.activateDefaultTab();
        }
      },
      changeActiveTab: function changeActiveTab(tabIndex) {
        var isActiveTab = this.isActiveTab(tabIndex),
            settings = this.getSettings();

        if ((settings.toggleSelf || !isActiveTab) && settings.hidePrevious) {
          this.deactivateActiveTab();
        }

        if (!settings.hidePrevious && isActiveTab) {
          this.deactivateActiveTab(tabIndex);
        }

        if (!isActiveTab) {
          this.activateTab(tabIndex);
        }

        $(window).trigger("resize");
      }
    }); // TimeLine

    var TimeLine = function TimeLine($scope) {
      var T_ID = $scope.data("id");
      var timeline = $scope.find(".skt-timeline-wrap");
      var dataScroll = timeline.data("scroll");
      var timeline_block = timeline.find(".skt-timeline-block");
      var event = "scroll.timelineScroll" + T_ID + " resize.timelineScroll" + T_ID;

      function scroll_tree() {
        timeline_block.each(function () {
          var block_height = $(this).outerHeight(true);
          var $offsetTop = $(this).offset().top;
          var window_middle_p = $window.scrollTop() + $window.height() / 2;

          if ($offsetTop < window_middle_p) {
            $(this).addClass("skt-timeline-scroll-tree");
          } else {
            $(this).removeClass("skt-timeline-scroll-tree");
          }

          var scroll_tree_wrap = $(this).find(".skt-timeline-tree-inner");
          var scroll_height = $window.scrollTop() - scroll_tree_wrap.offset().top + $window.outerHeight() / 2;

          if ($offsetTop < window_middle_p && timeline_block.hasClass("skt-timeline-scroll-tree")) {
            if (block_height > scroll_height) {
              scroll_tree_wrap.css({
                height: scroll_height * 1.5 + "px"
              });
            } else {
              scroll_tree_wrap.css({
                height: block_height * 1.2 + "px"
              });
            }
          } else {
            scroll_tree_wrap.css("height", "0px");
          }
        });
      }

      if ("yes" === dataScroll) {
        scroll_tree();
        $window.on(event, scroll_tree);
      } else {
        $window.off(event);
      }
    };

    var HotSpots = elementorModules.frontend.handlers.Base.extend({
      onInit: function onInit() {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.init();
      },
      bindEvents: function bindEvents() {
        if (!this.isEdit) {
          this.elements.$items.on("click.spotClick", function (e) {
            e.preventDefault();
          });
        }
      },
      getDefaultSettings: function getDefaultSettings() {
        return {
          selectors: {
            item: ".skt-hotspots__item"
          }
        };
      },
      getDefaultElements: function getDefaultElements() {
        return {
          $items: this.findElement(this.getSettings("selectors.item"))
        };
      },
      onElementChange: function onElementChange(changedProp) {
        if (!this.hasTipso()) {
          return;
        }

        if (changedProp.indexOf("tooltip_") === 0) {
          this.elements.$items.tipso("destroy");
          this.init();
        }
      },
      hasTipso: function hasTipso() {
        return $.fn["tipso"];
      },
      init: function init() {
        var position = this.getElementSettings("tooltip_position"),
            width = this.getElementSettings("tooltip_width"),
            background = this.getElementSettings("tooltip_bg_color"),
            color = this.getElementSettings("tooltip_color"),
            speed = this.getElementSettings("tooltip_speed"),
            delay = this.getElementSettings("tooltip_delay"),
            hideDelay = this.getElementSettings("tooltip_hide_delay"),
            hideArrow = this.getElementSettings("tooltip_hide_arrow"),
            hover = this.getElementSettings("tooltip_hover"),
            elementId = this.getID();

        if (!this.hasTipso()) {
          return;
        }

        this.elements.$items.each(function (index, item) {
          var $item = $(item),
              target = $item.data("target"),
              settings = $item.data("settings"),
              classes = ["skt-hotspots--" + elementId, "elementor-repeater-item-" + target];
          $item.tipso({
            color: color,
            width: width.size || 200,
            position: settings.position || position,
            speed: speed,
            delay: delay,
            showArrow: !hideArrow,
            tooltipHover: !!hover,
            hideDelay: hideDelay,
            background: background,
            useTitle: false,
            content: $("#skt-" + target).html(),
            onBeforeShow: function onBeforeShow($e, e, tooltip) {
              tooltip.tipso_bubble.addClass(classes.join(" "));
            }
          });
        });
      }
    });

    var LineChart = function LineChart($scope) {
      elementorFrontend.waypoint($scope, function () {
        var $chart = $(this),
            $container = $chart.find(".skt-line-chart-container"),
            $chart_canvas = $chart.find("#skt-line-chart"),
            settings = $container.data("settings");

        if ($container.length) {
          var chart = new Chart($chart_canvas, settings);
        }
      });
    };

    var RadarChart = function RadarChart($scope) {
      elementorFrontend.waypoint($scope, function () {
        var $chart = $(this),
            $container = $chart.find(".skt-radar-chart-container"),
            $chart_canvas = $chart.find("#skt-radar-chart"),
            settings = $container.data("settings");

        if ($container.length) {
          var chart = new Chart($chart_canvas, settings);
        }
      });
    };

    var PolarChart = function PolarChart($scope) {
      elementorFrontend.waypoint($scope, function () {
        var $chart = $(this),
            $container = $chart.find(".skt-polar-chart-container"),
            $chart_canvas = $chart.find("#skt-polar-chart"),
            settings = $container.data("settings");

        if ($container.length) {
          var chart = new Chart($chart_canvas, settings);
        }
      });
    };

    var PieChart = function PieChart($scope) {
      elementorFrontend.waypoint($scope, function () {
        var $chart = $(this),
            $container = $chart.find(".skt-pie-chart-container"),
            $chart_canvas = $chart.find("#skt-pie-chart"),
            settings = $container.data("settings");

        if ($container.length) {
          var chart = new Chart($chart_canvas, settings);
        }
      });
    };

    var StickyVideoArray = [];

    var StickyVideo = function StickyVideo($scope) {
      var $id = "#skt-sticky-video-player-" + $scope.data("id"),
          $wrap = $scope.find(".skt-sticky-video-wrap"),
          $settting = $wrap.data("skt-player"),
          $box = $wrap.find(".skt-sticky-video-box"),
          $overlay_box = $wrap.find(".skt-sticky-video-overlay"),
          $overlay_play = $overlay_box.find(".skt-sticky-video-overlay-icon"),
          $sticky_close = $wrap.find(".skt-sticky-video-close i"),
          $all_box = $(".skt-sticky-video-box"),
          event = "scroll.haStickyVideo" + $scope.data("id"),
          //event = "scroll.haStickyVideo"+$scope.data('id')+" resize.haStickyVideo"+$scope.data('id'),
      set; //console.log(StickyVideoArray);

      var option = {
        autoplay: $settting.autoplay,
        muted: $settting.muted,
        loop: $settting.loop,
        clickToPlay: false,
        hideControls: false // youtube: {
        // 	start: '30',
        // 	end: '45',
        // },

      };
      /* var playerAbc = new Plyr('#player', {
      		title: 'Example Title',
      		// autoplay: true,
      		youtube: {
      			start: '30',
      			end: '45',
      		},
      	  }); */

      var playerAbc = new Plyr($id);
      var StickyVideoObject = {
        player: playerAbc,
        event: event,
        player_box: $box
      };
      StickyVideoArray.push(StickyVideoObject); //on overlay click

      if (0 !== $overlay_box.length) {
        var $el = 0 !== $overlay_play.length ? $overlay_play : $overlay_box;
        $el.on("click", function () {
          playerAbc.play();
        });
      } //on close sticky


      $sticky_close.on("click", function () {
        $box.removeClass("sticky");
        $box.addClass("sticky-close");
        playerAbc.pause();
      }); //on Play

      playerAbc.on("play", function (e) {
        //console.log(e,StickyVideoArray);
        $overlay_box.css("display", "none");

        if ($box.hasClass("sticky-close")) {
          $box.removeClass("sticky-close");
        }

        StickyVideoArray.forEach(function (item, index) {
          //console.log( item );
          if (item.player !== playerAbc) {
            //console.log('push');
            item.player.pause();
          }

          if (item.event !== event) {
            $window.off(item.event); //console.log('event');
          }

          if (item.player_box !== $box) {
            item.player_box.removeClass("sticky"); //console.log('removeClass');
          }
        }); //$all_box.removeClass("sticky");
        //console.log($settting,$id);

        if (true === $settting.sticky) {
          $window.on(event, function () {
            var height = $box.outerHeight(true);
            var $offsetTop = $wrap.offset().top;
            var videoBoxTopPoint = $window.scrollTop();
            var videoBoxMiddlePoint = $offsetTop + height / 2;

            if (!$box.hasClass("sticky-close")) {
              if (videoBoxMiddlePoint < videoBoxTopPoint) {
                $box.addClass("sticky");
              } else {
                $box.removeClass("sticky");
              }
            }
          });
        } else {
          $window.off(event);
        }
      }); // on pause

      playerAbc.on("pause", function (e) {
        $window.off(event);
      });
      $window.on("load resize", debounce(function () {
        //console.log($box.find( 'iframe' ));
        // cannot rely on iframe here cause iframe has extra height
        var height = $box.find(".plyr").height();
        $wrap.css("min-height", height + "px");
      }, 100));
      /* var event = "scroll.timelineScroll"+T_ID+" resize.timelineScroll"+T_ID;
      	function scroll_tree() {
      	timeline_block.each(function () {
      		var block_height = $(this).outerHeight(true);
      		var $offsetTop = $(this).offset().top;
      		var window_middle_p = $window.scrollTop() + $window.height() / 2;
      		if ($offsetTop < window_middle_p) {
      			$(this).addClass("skt-timeline-scroll-tree");
      		} else {
      			$(this).removeClass("skt-timeline-scroll-tree");
      		}
      		var scroll_tree_wrap = $(this).find('.skt-timeline-tree-inner');
      		var scroll_height = ($window.scrollTop() - scroll_tree_wrap.offset().top) + ($window.outerHeight() / 2);
      			if ($offsetTop < window_middle_p && timeline_block.hasClass('skt-timeline-scroll-tree')) {
      			if (block_height > scroll_height) {
      				scroll_tree_wrap.css({"height": scroll_height * 1.5 + "px",});
      			} else {
      				scroll_tree_wrap.css({"height": block_height * 1.2 + "px",});
      			}
      		} else {
      			scroll_tree_wrap.css("height", "0px");
      		}
      	});
      }
      	if( 'yes' === dataScroll) {
      	scroll_tree();
      	$window.on(event, scroll_tree);
      }else{
      	$window.off(event);
      } */
    }; //facebook feed


    var FacebookFeed = function FacebookFeed($scope) {
      var button = $scope.find(".skt-facebook-load-more");
      var facebook_wrap = $scope.find(".skt-facebook-items");
      button.on("click", function (e) {
        e.preventDefault();
        var $self = $(this),
            query_settings = $self.data("settings"),
            total = $self.data("total"),
            items = $scope.find(".skt-facebook-item").length;
        $.ajax({
          url: SktProLocalize.ajax_url,
          type: "POST",
          data: {
            action: "skt_addons_elementor_facebook_feed_action",
            security: SktProLocalize.nonce,
            query_settings: query_settings,
            loaded_item: items
          },
          success: function success(response) {
            if (total > items) {
              $(response).appendTo(facebook_wrap);
            } else {
              $self.text("All Loaded").addClass("loaded");
              setTimeout(function () {
                $self.css({
                  display: "none"
                });
              }, 800);
            }
          },
          error: function error(_error2) {}
        });
      });
    }; //SmartPostList


    var SmartPostList = function SmartPostList($scope) {
      var filterWrap = $scope.find(".skt-spl-filter"),
          customSelect = $scope.find(".skt-spl-custom-select"),
          gridWrap = $scope.find(".skt-spl-grid-area"),
          mainWrapper = $scope.find(".skt-spl-wrapper"),
          querySettings = mainWrapper.data("settings"),
          dataOffset = mainWrapper.attr("data-offset"),
          nav = $scope.find(".skt-spl-pagination button"),
          navPrev = $scope.find(".skt-spl-pagination button.prev"),
          navNext = $scope.find(".skt-spl-pagination button.next"),
          filter = $scope.find("ul.skt-spl-filter-list li span"),
          event;
      customSelect.niceSelect();
      var select = $scope.find(".skt-spl-custom-select li");

      function afterClick(e) {
        e.preventDefault();
        var $self = $(this),
            dataFilter = filterWrap.attr("data-active"),
            dataTotalOffset = mainWrapper.attr("data-total-offset"),
            offset = "",
            termId = "";

        if (e.target.classList.contains("prev") || e.target.classList.contains("fa-angle-left")) {
          if (0 == parseInt(dataTotalOffset)) {
            navPrev.attr("disabled", true);
            return;
          }

          offset = 0 !== parseInt(dataTotalOffset) ? parseInt(dataTotalOffset) - parseInt(dataOffset) : "0";

          if (undefined !== dataFilter) {
            termId = dataFilter;
          }
        } else if (e.target.classList.contains("next") || e.target.classList.contains("fa-angle-right")) {
          offset = parseInt(dataTotalOffset) + parseInt(dataOffset);

          if (undefined !== dataFilter) {
            termId = dataFilter;
          }
        }

        if (e.target.hasAttribute("data-value")) {
          termId = e.target.dataset["value"];
          filterWrap[0].setAttribute("data-active", termId);

          if ("SPAN" === e.target.tagName) {
            filter.removeClass("skt-active");
            e.target.classList.toggle("skt-active");
          }

          offset = 0;
          navPrev.attr("disabled", true);
          navNext.removeAttr("disabled");
        }

        $.ajax({
          url: SktProLocalize.ajax_url,
          type: "POST",
          data: {
            action: "skt_addons_elementor_smart_post_list_action",
            security: SktProLocalize.nonce,
            querySettings: querySettings,
            offset: offset,
            termId: termId
          },
          success: function success(response) {
            if ($(response).length > 0) {
              gridWrap.css("height", gridWrap.outerHeight(true) + "px");
              gridWrap.html("");
              $(response).appendTo(gridWrap);
              gridWrap.css("height", "");
              mainWrapper[0].attributes["data-total-offset"].value = offset;

              if (e.target.classList.contains("prev") || e.target.classList.contains("fa-angle-left")) {
                navNext.removeAttr("disabled");
              }

              if (e.target.classList.contains("next") || e.target.classList.contains("fa-angle-right")) {
                navPrev.removeAttr("disabled");
              }
            } else {
              if (e.target.classList.contains("next") || e.target.classList.contains("fa-angle-right")) {
                navNext.attr("disabled", true);
              }
            }
          },
          error: function error(_error3) {}
        });
      }

      nav.on("click", afterClick);
      filter.on("click", afterClick);
      select.on("click", afterClick);
    }; //PostGrid


    var postGridSkins = ["classic", "hawai", "standard", "monastic", "stylica", "outbox", "crossroad"];

    var PostGrid = function PostGrid($scope) {
      var wrapper = $scope.find(".skt-pg-wrapper"),
          gridWrap = wrapper.find(".skt-pg-grid-wrap"),
          button = wrapper.find("button.skt-pg-loadmore");
      button.on("click", function (e) {
        e.preventDefault();
        var $self = $(this),
            querySettings = $self.data("settings"),
            items = wrapper.find(".skt-pg-item").length;
        $self.attr("disabled", true);
        $.ajax({
          url: SktProLocalize.ajax_url,
          type: "POST",
          data: {
            action: "sktaddonselementorextra_post_grid_action",
            security: SktProLocalize.nonce,
            querySettings: querySettings,
            loadedItem: items
          },
          beforeSend: function beforeSend() {
            $self.find(".eicon-loading").css({
              display: "inline-block"
            });
          },
          success: function success(response) {
            if (response) {
              $(response).each(function () {
                var $self = $(this); // every item

                if ($self.hasClass("skt-pg-item")) {
                  $self.addClass("skt-pg-item-loaded").appendTo(gridWrap);
                } else {
                  $self.appendTo(gridWrap);
                }
              });
            } else {
              $self.text("All Loaded").addClass("loaded");
              setTimeout(function () {
                $self.css({
                  display: "none"
                });
              }, 800);
            }

            $self.find(".eicon-loading").css({
              display: "none"
            });
            $self.removeAttr("disabled");
          },
          error: function error(_error4) {}
        });
      });
    }; // Advanced Data Table


    var DataTable = function DataTable($scope) {
      var dataTable = $scope.find(".skt-advanced-table");
      var widgetWrapper = $scope.find(".elementor-widget-container");
      var row_td = $scope.find(".skt-advanced-table__body-row-cell");
      var search = dataTable.data("search") == true ? true : false;
      var paging = dataTable.data("paging") == true ? true : false;
      var scrollX = dataTable.data("scroll-x") == undefined ? false : true;

      if (window.innerWidth <= 767) {
        var scrollX = true;
      } // DataTables.js settings


      $(dataTable).DataTable({
        searching: search,
        paging: paging,
        orderCellsTop: true,
        scrollX: scrollX
      });
      var column_th = $scope.find(".skt-advanced-table__head-column-cell");
      column_th.each(function (index, v) {
        $(column_th[index]).css("width", "");
      }); // export table button

      if (dataTable.data("export-table") == true) {
        widgetWrapper.prepend('<div class="skt-advanced-table-btn-wrap"><button class="skt-advanced-table-btn">' + dataTable.data("export-table-text") + "</button></div>");
        var dataTableBtn = $scope.find(".skt-advanced-table-btn");
        dataTableBtn.on("click", function () {
          var oldDownloadNode = document.querySelector(".skt-advanced-data-table-export");

          if (oldDownloadNode) {
            oldDownloadNode.parentNode.removeChild(oldDownloadNode);
          }

          var csv = [];
          dataTable.find("tr").each(function (index, tr) {
            var data = [];
            $(tr).find("th").each(function (index, th) {
              data.push(th.textContent);
            });
            $(tr).find("td").each(function (index, td) {
              data.push(td.textContent);
            });
            csv.push(data.join(","));
          });
          var csvFile = new Blob([csv.join("\n")], {
            type: "text/csv"
          });
          var downloadNode = document.createElement("a");
          var url = URL.createObjectURL(csvFile);
          var date = new Date();
          var options = {
            day: "2-digit",
            month: "short",
            year: "numeric",
            hour: "numeric",
            minute: "numeric",
            second: "numeric"
          };
          var formatedDate = date.toLocaleDateString("en-BD", options);
          var dateString = JSON.stringify(formatedDate.replace(/[ , :]/g, "-"));
          downloadNode.download = "advanced-data-table-" + dateString + "~" + dataTable.data("widget-id") + ".csv";
          downloadNode.setAttribute("href", url);
          downloadNode.classList.add("skt-advanced-data-table-export");
          downloadNode.style.visibility = "hidden";
          document.body.appendChild(downloadNode);
          downloadNode.click();
        });
      }
    }; // ModalPopup


    var ModalPopup = function ModalPopup($scope) {
      var boxWrapper = $scope.find(".skt-modal-popup-box-wrapper");
      var triggerType = boxWrapper.data("trigger-type");
      var trigger = $scope.find(".skt-modal-popup-trigger");
      var overlayClose = $scope.find(".skt-modal-popup-overlay");
      var iconClose = $scope.find(".skt-modal-popup-content-close");
      var modal = $scope.find(".skt-modal-popup-content-wrapper");
      var modalAnimation = modal.data("animation");
      var modalDelay = modal.data("display-delay"); // var videoSrc;

      if ("pageload" == triggerType) {
        if (!modal.hasClass("skt-modal-show")) {
          setTimeout(function () {
            modal.addClass("skt-modal-show");
            modal.find(".skt-modal-animation").addClass(modalAnimation);
          }, parseInt(modalDelay));
        }
      } else {
        trigger.on("click", function (e) {
          e.preventDefault();
          var wrapper = $(this).closest(".skt-modal-popup-box-wrapper"),
              modal = wrapper.find(".skt-modal-popup-content-wrapper"),
              modalAnimation = modal.data("animation"),
              modalContent = modal.find(".skt-modal-animation");

          if (!modal.hasClass("skt-modal-show")) {
            modal.addClass("skt-modal-show");
            modalContent.addClass(modalAnimation);
          }
        });
      }

      overlayClose.on("click", close_modal);
      iconClose.on("click", close_modal);

      function close_modal(el) {
        var wrap = $(this).closest(".skt-modal-popup-content-wrapper"),
            modalAnimation = wrap.data("animation");

        if (wrap != null && wrap.hasClass("skt-modal-show")) {
          var doSetTimeout = function doSetTimeout(target, source) {
            setTimeout(function () {
              target.attr("src", source);
            }, 500);
          };

          var nVideo = wrap.find("iframe, video");
          $.each(nVideo, function (idx, val) {
            var videoSrc = $(this).attr("src");
            $(this).attr("src", "#");
            doSetTimeout($(this), videoSrc);
          });
          wrap.removeClass("skt-modal-show");
          wrap.find("." + modalAnimation).removeClass(modalAnimation);
        }
      }
    }; //Mini Cart


    var miniCart = function miniCart($scope) {
      $scope.find(".skt-mini-cart-inner").on("click mouseenter mouseleave", function (e) {
        var cart_btn = $(this),
            on_click = cart_btn.hasClass("skt-mini-cart-on-click"),
            on_hover = cart_btn.hasClass("skt-mini-cart-on-hover"),
            popup = cart_btn.find(".skt-mini-cart-popup");

        if (popup.length == 0) {
          return;
        }

        if ("click" === e.type && on_click) {
          popup.fadeToggle();
        }

        if ("mouseenter" === e.type && on_hover) {
          popup.fadeIn();
        }

        if ("mouseleave" === e.type && on_hover) {
          popup.fadeOut();
        }
      });

      if ($scope.find(".skt-mini-cart-popup").length > 0 && $scope.find(".skt-mini-cart-on-click").length > 0) {
        $("body").on("click", function (e) {
          if ($(e.target).hasClass("skt-mini-cart-popup") || $(e.target).parents().hasClass("skt-mini-cart-popup") || $(e.target).hasClass("skt-mini-cart-button") || $(e.target).parents().hasClass("skt-mini-cart-button")) {
            return;
          } else {
            $scope.find(".skt-mini-cart-popup").removeAttr("style");
          }
        });
      }
    }; //Image Scroller


    var ImageScroller = elementorModules.frontend.handlers.Base.extend({
      onInit: function onInit() {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.wrapper = this.$element.find(".skt-image-scroller-wrapper");
        this.run();
      },
      onElementChange: function onElementChange() {
        this.run();
      },
      run: function run() {
        // var widgetID = $scope.data('id');
        // var wrapper = $scope.find('.skt-image-scroller-wrapper');
        var triggerType = this.wrapper.data("trigger-type");

        if ("hover" !== triggerType) {
          return;
        }

        var figure = this.wrapper.find(".skt-image-scroller-container");
        var scrollType = this.wrapper.data("scroll-type");
        var scrollDirection = this.wrapper.data("scroll-direction");
        var imageScroll = figure.find("img");
        var transformY = imageScroll.height() - figure.height();
        var transformX = imageScroll.width() - figure.width(); // console.log(scrollType,transformY,transformX);

        if (scrollType === "vertical" && transformY > 0) {
          if ("top" === scrollDirection) {
            mouseEvent("translateY", transformY);
          } else {
            imageScroll.css("transform", "translateY" + "( -" + transformY + "px)");
            mouseEventRevers("translateY", transformY);
          }
        }

        if (scrollType === "horizontal" && transformX > 0) {
          if ("left" === scrollDirection) {
            mouseEvent("translateX", transformX);
          } else {
            imageScroll.css("transform", "translateX" + "( -" + transformX + "px)");
            mouseEventRevers("translateX", transformX);
          }
        }

        function mouseEvent(cssProperty, value) {
          figure.on("mouseenter", function () {
            imageScroll.css("transform", cssProperty + "( -" + value + "px)");
          });
          figure.on("mouseleave", function () {
            imageScroll.css("transform", cssProperty + "( 0px)");
          });
        }

        function mouseEventRevers(cssProperty, value) {
          figure.on("mouseenter", function () {
            imageScroll.css("transform", cssProperty + "( 0px)");
          });
          figure.on("mouseleave", function () {
            imageScroll.css("transform", cssProperty + "( -" + value + "px)");
          });
        }
      }
    }); // Mega Menu

    var NavMenu = function _init($scope) {
      if ($scope.find(".skt-menu-container").length > 0) {
        var additionalDigits = $scope.find(".skt-wid-con").data("responsive-breakpoint");
        var sidebar_mousemove = $scope.find(".skt-megamenu-has");
        var tabPadding = $scope.find(".skt-menu-container").outerHeight();
        $(window).on("resize", function () {
          $scope.find(".skt-megamenu-panel").css({
            top: tabPadding
          });
        }).trigger("resize");
        sidebar_mousemove.on("mouseenter", function () {
          var q = $(this).data("vertical-menu");
          var jqel = $(this).children(".skt-megamenu-panel"); // console.log(q);

          if ($(this).hasClass("skt-dropdown-menu-full_width") && $(this).hasClass("top_position")) {
            /** @type {number} */
            var ffleft = Math.floor($(this).position().left - $(this).offset().left);
            var $sharepreview = $(this);
            $sharepreview.find(".skt-megamenu-panel").css("max-width", $(window).width());
            $(window).on("resize", function () {
              $sharepreview.find(".skt-megamenu-panel").css({
                left: ffleft + "px"
              });
            }).trigger("resize");
          }

          if (!$(this).hasClass("skt-dropdown-menu-full_width") && $(this).hasClass("top_position")) {
            $(this).on({
              mouseenter: function setup() {
                if (0 === $(".default_menu_position").length) {
                  $(this).parents(".elementor-section-wrap").addClass("default_menu_position");
                }
              },
              mouseleave: function setup() {
                if (0 !== $(".default_menu_position").length) {
                  $(this).parents(".elementor-section-wrap").removeClass("default_menu_position");
                }
              }
            });
          }

          if (q && q !== undefined) {
            // console.log("trigger 1");
            if ("string" == typeof q) {
              // console.log("trigger 2");
              if (/^[0-9]/.test(q)) {
                $(window).on("resize", function () {
                  // console.log("trigger 2-1");
                  jqel.css({
                    width: q
                  });

                  if (!($(document).width() > Number(additionalDigits))) {
                    // console.log("trigger 2-2");
                    jqel.removeAttr("style");
                  }
                }).trigger("resize");
              } else {
                // console.log("trigger 3");
                $(window).on("resize", function () {
                  // console.log("trigger 3-1");
                  jqel.css({
                    width: q + "px"
                  });

                  if (!($(document).width() > Number(additionalDigits))) {
                    // console.log("trigger 3-2");
                    jqel.removeAttr("style");
                  }
                }).trigger("resize");
              }
            } else {
              // console.log("trigger 4");
              jqel.css({
                width: q + "px"
              });
            }
          } else {
            // console.log("trigger 5");
            $(window).on("resize", function () {
              // console.log("trigger 5-1");
              jqel.css({
                width: q + "px"
              });

              if (!($(document).width() > Number(additionalDigits))) {
                // console.log("trigger 5-2");
                jqel.removeAttr("style");
              }
            }).trigger("resize");
          }
        });
        sidebar_mousemove.trigger("mouseenter");
      }
    };

    elementorFrontend.hooks.addAction("frontend/element_ready/skt-countdown.default", CountDown);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-team-carousel.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(CarouselBase, {
        $element: $scope,
        selectors: {
          container: ".skt-team-carousel-wrap"
        },
        prevArrow: '<button type="button" class="slick-prev"><i class="skti skti-arrow-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="skti skti-arrow-right"></i></button>'
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-source-code.default", SourceCode);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-animated-text.default", AnimatedText);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-logo-carousel.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(CarouselBase, {
        $element: $scope,
        selectors: {
          container: ".skt-logo-carousel-wrap"
        }
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-testimonial-carousel.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(CarouselBase, {
        $element: $scope,
        selectors: {
          container: ".skt-testimonial-carousel__wrap"
        }
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-advanced-tabs.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(TabHandlerBase, {
        $element: $scope
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-scrolling-image.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(ScrollingImage, {
        $element: $scope
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-pricing-table.default", PricingTableToolTip);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-accordion.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(TabHandlerBase, {
        $element: $scope,
        selectors: {
          tabTitle: ".skt-accordion__item-title",
          tabContent: ".skt-accordion__item-content"
        },
        classes: {
          active: "skt-accordion__item--active"
        },
        showTabFn: "slideDown",
        hideTabFn: "slideUp"
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-toggle.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(TabHandlerBase, {
        $element: $scope,
        selectors: {
          tabTitle: ".skt-toggle__item-title",
          tabContent: ".skt-toggle__item-content"
        },
        classes: {
          active: "skt-toggle__item--active"
        },
        hidePrevious: false,
        autoExpand: "editor",
        showTabFn: "slideDown",
        hideTabFn: "slideUp"
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-timeline.default", TimeLine);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-hotspots.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(HotSpots, {
        $element: $scope
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-line-chart.default", LineChart);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-pie-chart.default", PieChart);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-polar-chart.default", PolarChart);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-radar-chart.default", RadarChart);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-smart-post-list.default", SmartPostList);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-advanced-data-table.default", DataTable);

    elementorFrontend.hooks.addAction("frontend/element_ready/skt-post-carousel.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(CarouselBase, {
        $element: $scope,
        selectors: {
          container: ".skt-posts-carousel-wrapper"
        },
        prevArrow: '<button type="button" class="slick-prev"><i class="skti skti-arrow-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="skti skti-arrow-right"></i></button>'
      });
    }); // product carousel

    function initProductCarousel($scope) {
      elementorFrontend.elementsHandler.addHandler(CarouselBase, {
        $element: $scope,
        selectors: {
          container: ".skt-product-carousel-wrapper"
        },
        prevArrow: '<button type="button" class="slick-prev"><i class="skti skti-arrow-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="skti skti-arrow-right"></i></button>'
      });
    }

    function productCarouselHandlerCallback($scope) {
      initProductCarousel($scope);
      initProductQuickView($scope);

      if ($scope.find(".skt-product-carousel-wrapper").hasClass("skt-layout-modern")) {
        $scope.find(".skt-product-carousel-add-to-cart a").html('<i class="fas fa-shopping-cart"></i>');
      }
    }

    elementorFrontend.hooks.addAction("frontend/element_ready/skt-product-carousel.classic", productCarouselHandlerCallback);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-product-carousel.modern", productCarouselHandlerCallback);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-product-carousel-new.default", productCarouselHandlerCallback); // product category carousel

    var productCategoryCarouselSkins = ["classic", "minimal"];

    function initProductCategoryCarousel($scope) {
      elementorFrontend.elementsHandler.addHandler(CarouselBase, {
        $element: $scope,
        selectors: {
          container: ".skt-product-cat-carousel"
        },
        prevArrow: '<button type="button" class="slick-prev"><i class="skti skti-arrow-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="skti skti-arrow-right"></i></button>'
      });
    }

    productCategoryCarouselSkins.forEach(function (index) {
      elementorFrontend.hooks.addAction("frontend/element_ready/skt-product-category-carousel." + index, initProductCategoryCarousel);
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-product-category-carousel-new.default", initProductCategoryCarousel);
    postGridSkins.forEach(function (index) {
      elementorFrontend.hooks.addAction("frontend/element_ready/skt-post-grid." + index, PostGrid);
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-post-grid-new.default", PostGrid);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-sticky-video.default", StickyVideo);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-modal-popup.default", ModalPopup);

    function initProductQuickView($scope) {
      var $button = $scope.find(".skt-pqv-btn");

      if (!$.fn["magnificPopup"]) {
        return;
      }

      $button.magnificPopup({
        type: "ajax",
        ajax: {
          settings: {
            cache: true,
            dataType: "html"
          },
          cursor: "mfp-ajax-cur",
          tError: "The product could not be loaded."
        },
        callbacks: {
          ajaxContentAdded: function ajaxContentAdded() {
            $(this.content).addClass(this.currItem.el.data("modal-class"));
          }
        }
      });
    }

    var initOnePageNav = elementorModules.frontend.handlers.Base.extend({
      onInit: function onInit() {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.run();
      },
      onElementChange: function onElementChange(e) {
        if (e == "scroll_wheel" || e == "touch_swipe" || e == "scroll_keys") {
          this.run();
        }
      },
      getReadySettings: function getReadySettings() {
        var settings = {
          design: this.getElementSettings("select_design"),
          speed: this.getElementSettings("scrolling_speed") || 700,
          offset: this.getElementSettings("row_to_offset") || 0,
          scrollWheel: this.getElementSettings("scroll_wheel") == "yes",
          touchSwipe: this.getElementSettings("touch_swipe") == "yes",
          scrollKeys: this.getElementSettings("scroll_keys") == "yes"
        };
        return $.extend({}, this.getSettings(), settings);
      },
      scroll: function scroll(id, speed, offset) {
        $("html, body").animate({
          scrollTop: $("#" + id).offset().top - offset
        }, speed);
      },
      run: function run() {
        var $this = this;
        var settings = this.getReadySettings();
        var $navWrapper = settings.design == "skt-opn-design-default" ? this.$element.find(".skt-opn-dotted-item") : this.$element.find(".skt_addons_elementor_opn__item");
        $navWrapper.on("click", function (e) {
          e.preventDefault();
          var targetId = $(this).find("a").data("section-id");

          if ($("#" + targetId).length > 0) {
            $this.scroll(targetId, settings.speed, settings.offset);
            $navWrapper.removeClass("skt_addons_elementor_opn__item--current");
            $(this).addClass("skt_addons_elementor_opn__item--current");
          }
        });
        var sections = [];
        var navItems = {};

        if ($navWrapper.length > 0) {
          $.each($navWrapper, function (index, nav) {
            var targetId = $(this).find("a").data("section-id");
            var targetSection = $("#" + targetId);
            sections.push(targetSection[0]);
            navItems[targetId] = $(this)[0];
          });
        }

        var observerOptions = {
          root: null,
          rootMargin: "0px",
          threshold: 0.2
        };
        var currentEvent;
        var observer = new IntersectionObserver(function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              var navItem = navItems[entry.target.id];
              navItem.classList.add("skt_addons_elementor_opn__item--current");
              Object.values(navItems).forEach(function (item) {
                if (item != navItem) {
                  item.classList.remove("skt_addons_elementor_opn__item--current");
                }
              });

              if (typeof elementor === "undefined") {
                if (settings.scrollWheel && currentEvent == "scroll") {
                  $this.scroll(entry.target.id, settings.speed, settings.offset);
                }

                if (settings.scrollKeys && currentEvent == "keys") {
                  $this.scroll(entry.target.id, settings.speed, settings.offset);
                }

                if (settings.touchSwipe && currentEvent == "touch") {
                  $this.scroll(entry.target.id, settings.speed, settings.offset);
                }
              }
            }
          });
        }, observerOptions);
        $("body").on("mousewheel DOMMouseScroll", debounce(function (e) {
          if (sections.length > 0) {
            sections.forEach(function (sec) {
              if (!(sec == undefined)) {
                currentEvent = "scroll";
                observer.observe(sec);
              }
            });
          }
        }, 200));

        if (typeof elementor === "undefined") {
          if (settings.scrollKeys) {
            $("body").on("keydown", debounce(function (evt) {
              var code = parseInt(evt.keyCode); // code == 38 for arrow up key
              // code == 40 for arrow up key

              if (sections.length > 0) {
                sections.forEach(function (sec) {
                  if (!(sec == undefined)) {
                    currentEvent = "keys";
                    observer.observe(sec);
                  }
                });
              }
            }, 200));
          }

          if (settings.touchSwipe) {
            $("body").on("touchmove", debounce(function (evt) {
              if (sections.length > 0) {
                sections.forEach(function (sec) {
                  if (!(sec == undefined)) {
                    currentEvent = "touch";
                    observer.observe(sec);
                  }
                });
              }
            }, 200));
          }
        }
      }
    }); // elementorFrontend.hooks.addAction('frontend/element_ready/skt-product-grid.classic', initProductQuickView);
    // elementorFrontend.hooks.addAction('frontend/element_ready/skt-product-grid.hover', initProductQuickView);

    elementorFrontend.hooks.addAction("frontend/element_ready/skt-one-page-nav.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(initOnePageNav, {
        $element: $scope
      });
    }); // advancedSliderHandler

    elementorFrontend.hooks.addAction("frontend/element_ready/skt-advanced-slider.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(SliderBase, {
        $element: $scope,
        selectors: {
          container: ".skt-slider-widget-wrapper"
        }
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-product-grid.classic", initProductQuickView);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-product-grid.hover", initProductQuickView);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-product-grid-new.default", initProductQuickView);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-single-product.classic", initProductQuickView);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-single-product.standard", initProductQuickView);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-single-product.landscape", initProductQuickView);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-single-product-new.default", initProductQuickView);
    $(document.body).on("added_to_cart", function (event, fragment, hash, $addToCart) {
      $addToCart.parent(".skt-product-grid__btns").removeClass("skt-is--adding").addClass("skt-is--added");
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-mini-cart.default", miniCart);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-mini-cart.default", miniCart);
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-image-scroller.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(ImageScroller, {
        $element: $scope
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-nav-menu.default", NavMenu); // Off Canvas

    var $trigger;

    var OffCanvas = function OffCanvas($scope) {
      this.node = $scope;
      this.wrap = $scope.find(".skt-offcanvas-content-wrap");
      this.cart_wrap = $scope.find(".skt-offcanvas-cart-container");
      this.content = $scope.find(".skt-offcanvas-content");
      this.button = $scope.find(".skt-offcanvas-toggle");
      this.settings = this.wrap.data("settings");
      this.toggle_source = this.settings.toggle_source;
      this.id = this.settings.content_id;
      this.toggle_id = this.settings.toggle_id;
      this.toggle_class = this.settings.toggle_class;
      this.transition = this.settings.transition;
      this.esc_close = this.settings.esc_close;
      this.body_click_close = this.settings.body_click_close;
      this.links_click_close = this.settings.links_click_close;
      this.direction = this.settings.direction;
      this.buttons_position = this.settings.buttons_position;
      this.duration = 500;
      this.destroy();
      this.init();
    };

    OffCanvas.prototype = {
      id: "",
      node: "",
      wrap: "",
      content: "",
      button: "",
      settings: {},
      transition: "",
      duration: 400,
      initialized: false,
      animations: ["slide", "slide-along", "reveal", "push"],
      init: function init() {
        if (!this.wrap.length) {
          return;
        }

        $("html").addClass("skt-offcanvas-content-widget");

        if ($(".skt-offcanvas-container").length === 0) {
          var faProJs = $("#font-awesome-pro-js").length > 0 ? $("#font-awesome-pro-js").attr("src") : false;

          if (faProJs) {
            $("#font-awesome-pro-js").remove();
          }

          $("body").wrapInner('<div class="skt-offcanvas-container" />');
          this.content.insertBefore(".skt-offcanvas-container");

          if (faProJs) {
            $("body").append('<script type="text/javascript" id="font-awesome-pro-js" src="' + faProJs + '"></script>');
          }
        }

        if (this.wrap.find(".skt-offcanvas-content").length > 0) {
          if ($(".skt-offcanvas-container > .skt-offcanvas-content-" + this.id).length > 0) {
            $(".skt-offcanvas-container > .skt-offcanvas-content-" + this.id).remove();
          }

          if ($("body > .skt-offcanvas-content-" + this.id).length > 0) {
            $("body > .skt-offcanvas-content-" + this.id).remove();
          }

          $("body").prepend(this.wrap.find(".skt-offcanvas-content"));
        }

        this.bindEvents();
      },
      destroy: function destroy() {
        this.close();
        this.animations.forEach(function (animation) {
          if ($("html").hasClass("skt-offcanvas-content-" + animation)) {
            $("html").removeClass("skt-offcanvas-content-" + animation);
          }
        });

        if ($("body > .skt-offcanvas-content-" + this.id).length > 0) {//$('body > .skt-offcanvas-content-' + this.id ).remove();
        }
      },
      setTrigger: function setTrigger() {
        var $trigger = false;

        if (this.toggle_source == "element-id" && this.toggle_id != "") {
          $trigger = $("#" + this.toggle_id);
        } else if (this.toggle_source == "element-class" && this.toggle_class != "") {
          $trigger = $("." + this.toggle_class);
        } else {
          $trigger = this.node.find(".skt-offcanvas-toggle");
        }

        return $trigger;
      },
      bindEvents: function bindEvents() {
        $trigger = this.setTrigger();

        if ($trigger) {
          $trigger.on("click", $.proxy(this.toggleContent, this));
        }

        $("body").delegate(".skt-offcanvas-content .skt-offcanvas-close", "click", $.proxy(this.close, this));

        if (this.links_click_close === "yes") {
          $("body").delegate(".skt-offcanvas-content .skt-offcanvas-body a", "click", $.proxy(this.close, this));
        }

        if (this.esc_close === "yes") {
          this.closeESC();
        }

        if (this.body_click_close === "yes") {
          this.closeClick();
        }

        $(window).resize($.proxy(this._resize, this));
      },
      toggleContent: function toggleContent(e) {
        e.preventDefault();

        if (!$("html").hasClass("skt-offcanvas-content-open")) {
          this.show();
        } else {
          this.close();
        }

        this._resize();
      },
      show: function show() {
        $(".skt-offcanvas-content-" + this.id).addClass("skt-offcanvas-content-visible"); // init animation class.

        $("html").addClass("skt-offcanvas-content-" + this.transition);
        $("html").addClass("skt-offcanvas-content-" + this.direction);
        $("html").addClass("skt-offcanvas-content-open");
        $("html").addClass("skt-offcanvas-content-" + this.id + "-open");
        $("html").addClass("skt-offcanvas-content-reset");
        this.button.addClass("skt-is-active");

        this._resize();
      },
      close: function close() {
        $("html").removeClass("skt-offcanvas-content-open");
        $("html").removeClass("skt-offcanvas-content-" + this.id + "-open");
        setTimeout($.proxy(function () {
          $("html").removeClass("skt-offcanvas-content-reset");
          $("html").removeClass("skt-offcanvas-content-" + this.transition);
          $("html").removeClass("skt-offcanvas-content-" + this.direction);
          $(".skt-offcanvas-content-" + this.id).removeClass("skt-offcanvas-content-visible");
        }, this), 500);
        this.button.removeClass("skt-is-active");
      },
      closeESC: function closeESC() {
        var self = this;

        if ("" === self.settings.esc_close) {
          return;
        } // menu close on ESC key


        $(document).on("keydown", function (e) {
          if (e.keyCode === 27) {
            // ESC
            self.close();
          }
        });
      },
      closeClick: function closeClick() {
        var self = this;

        if (this.toggle_source == "element-id" && this.toggle_id != "") {
          $trigger = "#" + this.toggle_id;
        } else if (this.toggle_source == "element-class" && this.toggle_class != "") {
          $trigger = "." + this.toggle_class;
        } else {
          $trigger = ".skt-offcanvas-toggle";
        }

        $(document).on("click", function (e) {
          if ($(e.target).is(".skt-offcanvas-content") || $(e.target).parents(".skt-offcanvas-content").length > 0 || $(e.target).is(".skt-offcanvas-toggle") || $(e.target).parents(".skt-offcanvas-toggle").length > 0 || $(e.target).is($trigger) || $(e.target).parents($trigger).length > 0 || !$(e.target).is(".skt-offcanvas-container")) {
            return;
          } else {
            self.close();
          }
        });
      },
      _resize: function _resize() {
        if (!this.cart_wrap.length) {
          return;
        }

        var off_canvas = $(".skt-offcanvas-content-" + this.id);

        if (off_canvas && off_canvas.length > 0) {
          if (this.buttons_position === "bottom") {
            var winHeight = window.innerHeight;
            var offset = 0;

            if ($("body").hasClass("admin-bar")) {
              offset = 32;
            }

            winHeight = winHeight - offset;
            off_canvas.find(".skt-offcanvas-inner").css({
              height: winHeight + "px",
              top: offset
            });
            headerHeight = off_canvas.find(".skt-offcanvas-cart-header").outerHeight(true);
            wrapHeight = off_canvas.find(".skt-offcanvas-wrap").outerHeight();
            cartTotalHeight = off_canvas.find(".woocommerce-mini-cart__total").outerHeight();
            cartButtonsHeight = off_canvas.find(".woocommerce-mini-cart__buttons").outerHeight();
            cartMessageHeight = off_canvas.find(".skt-woo-menu-cart-message").outerHeight();
            itemsHeight = winHeight - (headerHeight + cartTotalHeight + cartButtonsHeight + cartMessageHeight);
            finalItemsHeight = itemsHeight - (winHeight - wrapHeight);
            finalItemsHeight += "px";
          } else {
            finalItemsHeight = "auto";
          }

          var style = '<style id="skt-woo-style-' + this.id + '">';
          style += "#" + off_canvas.attr("id") + " .woocommerce-mini-cart.cart_list {";
          style += "height: " + finalItemsHeight;
          style += "}";
          style += "</style>";

          if ($("#skt-woopack-style-" + this.id).length > 0) {
            $("#skt-woopack-style-" + this.id).remove();
          }

          $("head").append(style);
        }
      }
    };

    var initOffCanvas = function initOffCanvas($scope, $) {
      var content_wrap = $scope.find(".skt-offcanvas-content-wrap");

      if ($(content_wrap).length > 0) {
        new OffCanvas($scope);
      }
    };

    elementorFrontend.hooks.addAction("frontend/element_ready/skt-off-canvas.default", initOffCanvas);
    var Unfold = elementorModules.frontend.handlers.Base.extend({
      onInit: function onInit() {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.run();
      },
      onElementChange: function onElementChange(e) {
        if (e == "unfold_text" || e == "unfold_icon" || e == "fold_text" || e == "fold_icon" || e == "fold_height" || e == "transition_duration" || e == "trigger") {
          this.run();

          if (e == "fold_height") {
            this.$element.find(".skt-unfold-data").css("height", this.getCollapseHeight() + "px");
          }
        }
      },
      getReadySettings: function getReadySettings() {
        var settings = {
          collapse_height: this.getElementSettings("fold_height") || 50,
          collapse_height_tablet: this.getElementSettings("fold_height_tablet") || this.getElementSettings("fold_height") || 50,
          collapse_height_mobile: this.getElementSettings("fold_height_mobile") || this.getElementSettings("fold_height_tablet") || this.getElementSettings("fold_height") || 50,
          trigger: this.getElementSettings("trigger"),
          transition_duration: this.getElementSettings("transition_duration") || 500,
          collapse_text: this.getElementSettings("unfold_text"),
          collapse_icon: this.getElementSettings("unfold_icon"),
          expand_text: this.getElementSettings("fold_text"),
          expand_icon: this.getElementSettings("fold_icon")
        };
        return $.extend({}, settings);
      },
      getCollapseHeight: function getCollapseHeight() {
        var body = this.$element.parents("body");
        var unfoldSettings = this.getReadySettings();
        var collapse_height = 50;

        if (body.attr("data-elementor-device-mode") == "desktop") {
          collapse_height = unfoldSettings.collapse_height;
        }

        if (body.attr("data-elementor-device-mode") == "tablet") {
          collapse_height = unfoldSettings.collapse_height_tablet;
        }

        if (body.attr("data-elementor-device-mode") == "mobile") {
          collapse_height = unfoldSettings.collapse_height_mobile;
        }

        return collapse_height;
      },
      fold: function fold(unfoldData, button, collapse_height) {
        var unfoldSettings = this.getReadySettings();
        var html = unfoldSettings.collapse_icon ? unfoldSettings.collapse_icon.value ? '<i aria-hidden="true" class="' + unfoldSettings.collapse_icon.value + '"></i>' : "" : "";
        html += unfoldSettings.collapse_text ? "<span>" + unfoldSettings.collapse_text + "</span>" : "";
        unfoldData.css("transition-duration", unfoldSettings.transition_duration + "ms");
        unfoldData.animate({
          height: collapse_height
        }, 0);
        var timeOut = setTimeout(function () {
          button.html(html);
          clearTimeout(timeOut);
        }, unfoldSettings.transition_duration);
        unfoldData.removeClass("folded");
      },
      unfold: function unfold(unfoldData, unfoldRender, button) {
        var unfoldSettings = this.getReadySettings();
        var html = unfoldSettings.expand_icon ? unfoldSettings.expand_icon.value ? '<i aria-hidden="true" class="' + unfoldSettings.expand_icon.value + '"></i>' : "" : "";
        html += unfoldSettings.expand_text ? "<span>" + unfoldSettings.expand_text + "</span>" : "";
        unfoldData.css("transition-duration", unfoldSettings.transition_duration + "ms");
        unfoldData.animate({
          height: unfoldRender.outerHeight(true)
        }, 0);
        var timeOut = setTimeout(function () {
          button.html(html);
          clearTimeout(timeOut);
        }, unfoldSettings.transition_duration);
        unfoldData.addClass("folded");
      },
      run: function run() {
        var $this = this;
        var button = this.$element.find(".skt-unfold-btn"),
            unfoldData = this.$element.find(".skt-unfold-data"),
            unfoldRender = this.$element.find(".skt-unfold-data-render");
        var unfoldSettings = this.getReadySettings();
        var collapse_height = $this.getCollapseHeight();
        unfoldData.css("height", collapse_height + "px"); // TODO: investigate further more to compatiable with advanced tabs
        // if (collapse_height >= unfoldRender.outerHeight()) {
        // 	button.hide();
        // 	unfoldData.addClass("folded");
        // }

        if (unfoldSettings.trigger == "click") {
          button.on("click", function () {
            collapse_height = $this.getCollapseHeight();

            if (unfoldData.hasClass("folded")) {
              $this.fold(unfoldData, button, collapse_height);
            } else {
              $this.unfold(unfoldData, unfoldRender, button);
            }
          });
        } else if (unfoldSettings.trigger == "hover") {
          unfoldData.on("mouseenter", function () {
            $this.unfold(unfoldData, unfoldRender, button);
          });
          unfoldData.on("mouseleave", function () {
            collapse_height = $this.getCollapseHeight();
            $this.fold(unfoldData, button, collapse_height);
          });
        }
      }
    });
    var Maps = elementorModules.frontend.handlers.Base.extend({
      onInit: function onInit() {
        elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
        this.runMap();
      },
      defaultStyle: function defaultStyle() {
        var defaultStyles = {
          standard: "",
          silver: [{
            elementType: "geometry",
            stylers: [{
              color: "#f5f5f5"
            }]
          }, {
            elementType: "labels.icon",
            stylers: [{
              visibility: "off"
            }]
          }, {
            elementType: "labels.text.fill",
            stylers: [{
              color: "#616161"
            }]
          }, {
            elementType: "labels.text.stroke",
            stylers: [{
              color: "#f5f5f5"
            }]
          }, {
            featureType: "administrative.land_parcel",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#bdbdbd"
            }]
          }, {
            featureType: "poi",
            elementType: "geometry",
            stylers: [{
              color: "#eeeeee"
            }]
          }, {
            featureType: "poi",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#757575"
            }]
          }, {
            featureType: "poi.park",
            elementType: "geometry",
            stylers: [{
              color: "#e5e5e5"
            }]
          }, {
            featureType: "poi.park",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#9e9e9e"
            }]
          }, {
            featureType: "road",
            elementType: "geometry",
            stylers: [{
              color: "#ffffff"
            }]
          }, {
            featureType: "road.arterial",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#757575"
            }]
          }, {
            featureType: "road.highway",
            elementType: "geometry",
            stylers: [{
              color: "#dadada"
            }]
          }, {
            featureType: "road.highway",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#616161"
            }]
          }, {
            featureType: "road.local",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#9e9e9e"
            }]
          }, {
            featureType: "transit.line",
            elementType: "geometry",
            stylers: [{
              color: "#e5e5e5"
            }]
          }, {
            featureType: "transit.station",
            elementType: "geometry",
            stylers: [{
              color: "#eeeeee"
            }]
          }, {
            featureType: "water",
            elementType: "geometry",
            stylers: [{
              color: "#c9c9c9"
            }]
          }, {
            featureType: "water",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#9e9e9e"
            }]
          }],
          retro: [{
            elementType: "geometry",
            stylers: [{
              color: "#ebe3cd"
            }]
          }, {
            elementType: "labels.text.fill",
            stylers: [{
              color: "#523735"
            }]
          }, {
            elementType: "labels.text.stroke",
            stylers: [{
              color: "#f5f1e6"
            }]
          }, {
            featureType: "administrative",
            elementType: "geometry.stroke",
            stylers: [{
              color: "#c9b2a6"
            }]
          }, {
            featureType: "administrative.land_parcel",
            elementType: "geometry.stroke",
            stylers: [{
              color: "#dcd2be"
            }]
          }, {
            featureType: "administrative.land_parcel",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#ae9e90"
            }]
          }, {
            featureType: "landscape.natural",
            elementType: "geometry",
            stylers: [{
              color: "#dfd2ae"
            }]
          }, {
            featureType: "poi",
            elementType: "geometry",
            stylers: [{
              color: "#dfd2ae"
            }]
          }, {
            featureType: "poi",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#93817c"
            }]
          }, {
            featureType: "poi.park",
            elementType: "geometry.fill",
            stylers: [{
              color: "#a5b076"
            }]
          }, {
            featureType: "poi.park",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#447530"
            }]
          }, {
            featureType: "road",
            elementType: "geometry",
            stylers: [{
              color: "#f5f1e6"
            }]
          }, {
            featureType: "road.arterial",
            elementType: "geometry",
            stylers: [{
              color: "#fdfcf8"
            }]
          }, {
            featureType: "road.highway",
            elementType: "geometry",
            stylers: [{
              color: "#f8c967"
            }]
          }, {
            featureType: "road.highway",
            elementType: "geometry.stroke",
            stylers: [{
              color: "#e9bc62"
            }]
          }, {
            featureType: "road.highway.controlled_access",
            elementType: "geometry",
            stylers: [{
              color: "#e98d58"
            }]
          }, {
            featureType: "road.highway.controlled_access",
            elementType: "geometry.stroke",
            stylers: [{
              color: "#db8555"
            }]
          }, {
            featureType: "road.local",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#806b63"
            }]
          }, {
            featureType: "transit.line",
            elementType: "geometry",
            stylers: [{
              color: "#dfd2ae"
            }]
          }, {
            featureType: "transit.line",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#8f7d77"
            }]
          }, {
            featureType: "transit.line",
            elementType: "labels.text.stroke",
            stylers: [{
              color: "#ebe3cd"
            }]
          }, {
            featureType: "transit.station",
            elementType: "geometry",
            stylers: [{
              color: "#dfd2ae"
            }]
          }, {
            featureType: "water",
            elementType: "geometry.fill",
            stylers: [{
              color: "#b9d3c2"
            }]
          }, {
            featureType: "water",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#92998d"
            }]
          }],
          dark: [{
            elementType: "geometry",
            stylers: [{
              color: "#212121"
            }]
          }, {
            elementType: "labels.icon",
            stylers: [{
              visibility: "off"
            }]
          }, {
            elementType: "labels.text.fill",
            stylers: [{
              color: "#757575"
            }]
          }, {
            elementType: "labels.text.stroke",
            stylers: [{
              color: "#212121"
            }]
          }, {
            featureType: "administrative",
            elementType: "geometry",
            stylers: [{
              color: "#757575"
            }]
          }, {
            featureType: "administrative.country",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#9e9e9e"
            }]
          }, {
            featureType: "administrative.land_parcel",
            stylers: [{
              visibility: "off"
            }]
          }, {
            featureType: "administrative.locality",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#bdbdbd"
            }]
          }, {
            featureType: "poi",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#757575"
            }]
          }, {
            featureType: "poi.park",
            elementType: "geometry",
            stylers: [{
              color: "#181818"
            }]
          }, {
            featureType: "poi.park",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#616161"
            }]
          }, {
            featureType: "poi.park",
            elementType: "labels.text.stroke",
            stylers: [{
              color: "#1b1b1b"
            }]
          }, {
            featureType: "road",
            elementType: "geometry.fill",
            stylers: [{
              color: "#2c2c2c"
            }]
          }, {
            featureType: "road",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#8a8a8a"
            }]
          }, {
            featureType: "road.arterial",
            elementType: "geometry",
            stylers: [{
              color: "#373737"
            }]
          }, {
            featureType: "road.highway",
            elementType: "geometry",
            stylers: [{
              color: "#3c3c3c"
            }]
          }, {
            featureType: "road.highway.controlled_access",
            elementType: "geometry",
            stylers: [{
              color: "#4e4e4e"
            }]
          }, {
            featureType: "road.local",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#616161"
            }]
          }, {
            featureType: "transit",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#757575"
            }]
          }, {
            featureType: "water",
            elementType: "geometry",
            stylers: [{
              color: "#000000"
            }]
          }, {
            featureType: "water",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#3d3d3d"
            }]
          }],
          night: [{
            elementType: "geometry",
            stylers: [{
              color: "#242f3e"
            }]
          }, {
            elementType: "labels.text.fill",
            stylers: [{
              color: "#746855"
            }]
          }, {
            elementType: "labels.text.stroke",
            stylers: [{
              color: "#242f3e"
            }]
          }, {
            featureType: "administrative.locality",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#d59563"
            }]
          }, {
            featureType: "poi",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#d59563"
            }]
          }, {
            featureType: "poi.park",
            elementType: "geometry",
            stylers: [{
              color: "#263c3f"
            }]
          }, {
            featureType: "poi.park",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#6b9a76"
            }]
          }, {
            featureType: "road",
            elementType: "geometry",
            stylers: [{
              color: "#38414e"
            }]
          }, {
            featureType: "road",
            elementType: "geometry.stroke",
            stylers: [{
              color: "#212a37"
            }]
          }, {
            featureType: "road",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#9ca5b3"
            }]
          }, {
            featureType: "road.highway",
            elementType: "geometry",
            stylers: [{
              color: "#746855"
            }]
          }, {
            featureType: "road.highway",
            elementType: "geometry.stroke",
            stylers: [{
              color: "#1f2835"
            }]
          }, {
            featureType: "road.highway",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#f3d19c"
            }]
          }, {
            featureType: "transit",
            elementType: "geometry",
            stylers: [{
              color: "#2f3948"
            }]
          }, {
            featureType: "transit.station",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#d59563"
            }]
          }, {
            featureType: "water",
            elementType: "geometry",
            stylers: [{
              color: "#17263c"
            }]
          }, {
            featureType: "water",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#515c6d"
            }]
          }, {
            featureType: "water",
            elementType: "labels.text.stroke",
            stylers: [{
              color: "#17263c"
            }]
          }],
          aubergine: [{
            elementType: "geometry",
            stylers: [{
              color: "#1d2c4d"
            }]
          }, {
            elementType: "labels.text.fill",
            stylers: [{
              color: "#8ec3b9"
            }]
          }, {
            elementType: "labels.text.stroke",
            stylers: [{
              color: "#1a3646"
            }]
          }, {
            featureType: "administrative.country",
            elementType: "geometry.stroke",
            stylers: [{
              color: "#4b6878"
            }]
          }, {
            featureType: "administrative.land_parcel",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#64779e"
            }]
          }, {
            featureType: "administrative.province",
            elementType: "geometry.stroke",
            stylers: [{
              color: "#4b6878"
            }]
          }, {
            featureType: "landscape.man_made",
            elementType: "geometry.stroke",
            stylers: [{
              color: "#334e87"
            }]
          }, {
            featureType: "landscape.natural",
            elementType: "geometry",
            stylers: [{
              color: "#023e58"
            }]
          }, {
            featureType: "poi",
            elementType: "geometry",
            stylers: [{
              color: "#283d6a"
            }]
          }, {
            featureType: "poi",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#6f9ba5"
            }]
          }, {
            featureType: "poi",
            elementType: "labels.text.stroke",
            stylers: [{
              color: "#1d2c4d"
            }]
          }, {
            featureType: "poi.park",
            elementType: "geometry.fill",
            stylers: [{
              color: "#023e58"
            }]
          }, {
            featureType: "poi.park",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#3C7680"
            }]
          }, {
            featureType: "road",
            elementType: "geometry",
            stylers: [{
              color: "#304a7d"
            }]
          }, {
            featureType: "road",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#98a5be"
            }]
          }, {
            featureType: "road",
            elementType: "labels.text.stroke",
            stylers: [{
              color: "#1d2c4d"
            }]
          }, {
            featureType: "road.highway",
            elementType: "geometry",
            stylers: [{
              color: "#2c6675"
            }]
          }, {
            featureType: "road.highway",
            elementType: "geometry.stroke",
            stylers: [{
              color: "#255763"
            }]
          }, {
            featureType: "road.highway",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#b0d5ce"
            }]
          }, {
            featureType: "road.highway",
            elementType: "labels.text.stroke",
            stylers: [{
              color: "#023e58"
            }]
          }, {
            featureType: "transit",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#98a5be"
            }]
          }, {
            featureType: "transit",
            elementType: "labels.text.stroke",
            stylers: [{
              color: "#1d2c4d"
            }]
          }, {
            featureType: "transit.line",
            elementType: "geometry.fill",
            stylers: [{
              color: "#283d6a"
            }]
          }, {
            featureType: "transit.station",
            elementType: "geometry",
            stylers: [{
              color: "#3a4762"
            }]
          }, {
            featureType: "water",
            elementType: "geometry",
            stylers: [{
              color: "#0e1626"
            }]
          }, {
            featureType: "water",
            elementType: "labels.text.fill",
            stylers: [{
              color: "#4e6d70"
            }]
          }]
        };
        return defaultStyles;
      },
      settings: function settings(wrapper) {
        var item = document.querySelector(wrapper);
        var settings = {
          latitude: parseFloat(item.dataset.latitude),
          longitude: parseFloat(item.dataset.longitude),
          mapType: item.dataset.mapType,
          markers: item.dataset.markers,
          polylines: item.dataset.polylines,
          mapZoom: parseInt(item.dataset.mapZoom),
          mapZoomMobile: parseInt(item.dataset.mapZoomMobile),
          mapZoomTablet: parseInt(item.dataset.mapZoomTablet),
          scrollWheelZoom: item.dataset.scrollWheelZoom,
          zoomControl: item.dataset.zoomControl,
          fullscreenControl: item.dataset.fullScreen,
          mapDraggable: item.dataset.disableMapDrag,
          showLegend: item.dataset.showLegend,
          showRoute: item.dataset.showRoute,
          googleMapType: item.dataset.googleMapType,
          mapTypeControl: item.dataset.mapTypeControl,
          streetViewControl: item.dataset.streetView,
          infoOpen: item.dataset.infoOpen,
          infoOpenHover: item.dataset.infoOpenHover,
          mapStyle: item.dataset.mapStyle,
          customMapStyles: item.dataset.customMapStyle,
          defaultStyle: this.defaultStyle(),
          strokeColor: item.dataset.strokeColor,
          strokeOpacity: item.dataset.strokeOpacity,
          strokeWeight: item.dataset.strokeWeight,
          strokeFill: item.dataset.strokeFill,
          strokeFillOpacity: item.dataset.strokeFillOpacity,
          originLat: item.dataset.originLat,
          originLng: item.dataset.originLng,
          destLat: item.dataset.destLat,
          destLng: item.dataset.destLng,
          travelMode: item.dataset.travelMode
        };
        return settings;
      },
      isValidJsonString: function isValidJsonString(jsonString) {
        try {
          JSON.parse(jsonString);
        } catch (e) {
          return false;
        }

        return true;
      },
      mapInit: function mapInit(wrapper) {
        var settings = this.settings(wrapper);
        var markersOptionsArray = JSON.parse(settings.markers);
        var map;
        var directionsService = new google.maps.DirectionsService();
        var directionsRenderer = new google.maps.DirectionsRenderer();
        var finalZoomLevel = settings.mapZoom;
        window.addEventListener("resize", function () {
          if (elementorFrontend.getCurrentDeviceMode() == "mobile") {
            finalZoomLevel = settings.mapZoomMobile;
          } else if (elementorFrontend.getCurrentDeviceMode() == "tablet") {
            finalZoomLevel = settings.mapZoomTablet;
          } else {
            finalZoomLevel = settings.mapZoom;
          }
        });
        map = new google.maps.Map(document.querySelector(".skt-adv-google-map__wrapper" + wrapper), {
          center: {
            lat: settings.latitude === "" ? -34.391 : settings.latitude,
            lng: settings.longitude === "" ? 150.644 : settings.longitude
          },
          zoom: finalZoomLevel,
          mapTypeId: settings.mapType,
          zoomControl: settings.zoomControl === "yes",
          mapTypeControl: settings.mapTypeControl === "yes",
          fullscreenControl: settings.fullscreenControl === "yes",
          streetViewControl: settings.streetViewControl === "yes",
          gestureHandling: settings.scrollWheelZoom === "yes" ? "greedy" : "cooperative",
          draggable: settings.mapDraggable !== "yes",
          styles: settings.mapStyle !== "custom" ? settings.defaultStyle[settings.mapStyle] : !this.isValidJsonString(settings.customMapStyles) ? "" : JSON.parse(settings.customMapStyles)
        });

        if (settings.mapDraggable === "yes") {
          map.setOptions({
            draggableCursor: "default"
          });
        } // adding markers and info window on map


        markersOptionsArray.forEach(function (option) {
          var marker = new google.maps.Marker({
            icon: option.pin_icon.url === "" ? "" : {
              url: option.pin_icon.url,
              scaledSize: new google.maps.Size(option.rectangular_image === "yes" ? +option.rectangular_pin_size_width.size : +option.square_pin_size.size, option.rectangular_image === "yes" ? +option.rectangular_pin_size_height.size : +option.square_pin_size.size)
            },
            map: map,
            position: {
              lat: +option.pin_latitude,
              lng: +option.pin_longitude
            }
          });
          var infoWindow = new google.maps.InfoWindow({
            content: '<div class="skt-adv-google-map__marker-wrapper">' + '<div class="skt-adv-google-map__marker-title-wrapper">' + '<div class="skt-adv-google-map__marker-title" style="display: inline-block">' + option.pin_item_title + "</div>" + "</div>" + '<div class="skt-adv-google-map__marker-description-wrapper">' + '<div class="skt-adv-google-map__marker-description">' + option.pin_item_description + "</div>" + "</div>" + "</div>"
          });

          if (settings.infoOpen === "yes") {
            infoWindow.open(map, marker);
          }

          if (settings.infoOpenHover === "yes") {
            marker.addListener("mouseover", function () {
              infoWindow.open(map, marker);
            });
          } else {
            marker.addListener("click", function () {
              infoWindow.open(map, marker);
            });
          }
        }); // adding map legend

        if (settings.showLegend === "yes") {
          var legend = document.querySelector(wrapper).nextElementSibling;
          map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);
        }

        if (settings.googleMapType == "polyline") {
          var polylines_data = JSON.parse(settings.polylines);
          var polylines_arr = [];
          polylines_data.forEach(function (polyline) {
            polylines_arr.push({
              lat: parseFloat(polyline.skt_addons_elementor_gmap_polyline_lat),
              lng: parseFloat(polyline.skt_addons_elementor_gmap_polyline_lng)
            });
          });
          var polyLinePath = JSON.parse(JSON.stringify(polylines_arr));
          var polyPath = new google.maps.Polyline({
            path: polyLinePath,
            strokeColor: settings.strokeColor,
            strokeOpacity: settings.strokeOpacity,
            strokeWeight: settings.strokeWeight
          });
          polyPath.setMap(map);
        }

        if (settings.googleMapType == "polygon") {
          var polylines_data = JSON.parse(settings.polylines);
          var polylines_arr = [];
          polylines_data.forEach(function (polyline) {
            polylines_arr.push({
              lat: parseFloat(polyline.skt_addons_elementor_gmap_polyline_lat),
              lng: parseFloat(polyline.skt_addons_elementor_gmap_polyline_lng)
            });
          });
          var polyLinePath = JSON.parse(JSON.stringify(polylines_arr));
          var polygon = new google.maps.Polygon({
            path: polyLinePath,
            strokeColor: settings.strokeColor,
            strokeOpacity: settings.strokeOpacity,
            strokeWeight: settings.strokeWeight,
            fillColor: settings.strokeFill,
            fillOpacity: settings.strokeFillOpacity
          });
          polygon.setMap(map);
        }

        if (settings.googleMapType == "routes") {
          this.routeDraw(directionsService, directionsRenderer, settings);
          directionsRenderer.setMap(map);
        }

        google.maps.event.addDomListener(window, "resize", function () {
          var center = map.getCenter();
          google.maps.event.trigger(map, "resize");
          map.setCenter(center);
          map.setZoom(finalZoomLevel);
        });
      },
      routeDraw: function routeDraw(directionsService, directionsRenderer, settings) {
        var start = settings.originLat + "," + settings.originLng;
        var end = settings.destLat + "," + settings.destLng;
        directionsService.route({
          origin: {
            query: start
          },
          destination: {
            query: end
          },
          travelMode: settings.travelMode.toUpperCase()
        }, function (response, status) {
          if (status === "OK") {
            directionsRenderer.setDirections(response);
          } else {}
        });
      },
      runMap: function runMap() {
        var gmaper = document.querySelectorAll(".skt-adv-google-map__wrapper");

        for (var i = 0; i < gmaper.length; i++) {
          gmaper[i].classList.add("skt-adv-google-map__wrapper--item" + i);
          this.mapInit(".skt-adv-google-map__wrapper--item" + i);
        }
      }
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-google-map.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(Maps, {
        $element: $scope,
        selectors: {
          container: ".skt-adv-google-map__wrapper"
        }
      });
    });
    elementorFrontend.hooks.addAction("frontend/element_ready/skt-unfold.default", function ($scope) {
      elementorFrontend.elementsHandler.addHandler(Unfold, {
        $element: $scope
      });
    });
  });
})(jQuery, Skt, window);