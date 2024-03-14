;
"use strict";
(function ($) {
    var $windowElement = $(window);
    var customDebounceFunction = function(customFunction, delayTime, isImmediate) {
      var timeoutReference;
    
      return function() {
        var contextReference = this;
        var argsArray = arguments;
    
        var later = function() {
          timeoutReference = null;
          if (!isImmediate) {
            customFunction.apply(contextReference, argsArray);
          }
        };
    
        var callNow = isImmediate && !timeoutReference;
        clearTimeout(timeoutReference);
        timeoutReference = setTimeout(later, delayTime);
    
        if (callNow) {
          customFunction.apply(contextReference, argsArray);
        }
      };
    };

  $windowElement.on('elementor/frontend/init', function () {
    var ModuleHandler = elementorModules.frontend.handlers.Base,
      FloatingEffectsHandler;
    FloatingEffectsHandler = ModuleHandler.extend({
      bindEvents: function bindEvents() {
        this.run();
      },
      getDefaultSettings: function getDefaultSettings() {
        var $easing = (this.getSettingsValue('easing')) ? this.getSettingsValue('easing') : 'easeInOutSine';
        if ($easing === 'steps') {
            $easing = 'steps(' + this.getSettingsValue('ease_step') + ')';
        }
        return {
          direction: (this.getSettingsValue('direction')) ? this.getSettingsValue('direction') : 'alternate',
          easing: $easing,
          loop: (this.getSettingsValue('loop') === 'default') ? true : this.getSettingsValue('loop_number'),
          targets: this.findElement('.elementor-widget-container').get(0)
        };
      },
      onElementChange: customDebounceFunction(function (prop) {
        if (prop.indexOf('htmega_fe') !== -1) {
          this.anime && this.anime.restart();
          this.run();
        }
      }, 400),
      getSettingsValue: function getSettingsValue(key) {
        return this.getElementSettings('htmega_fe_' + key);
      },
      run: function run() {
        var settings = this.getDefaultSettings();
        if (this.getSettingsValue('translate_toggle')) {
          if (this.getSettingsValue('translate_x.size') || this.getSettingsValue('translate_x.sizes.to')) {
            settings.translateX = {
              value: [this.getSettingsValue('translate_x.sizes.from') || 0, this.getSettingsValue('translate_x.size') || this.getSettingsValue('translate_x.sizes.to')],
              duration: this.getSettingsValue('translate_duration.size'),
              delay: this.getSettingsValue('translate_delay.size') || 0
            };
          }
          if (this.getSettingsValue('translate_y.size') || this.getSettingsValue('translate_y.sizes.to')) {
            settings.translateY = {
              value: [this.getSettingsValue('translate_y.sizes.from') || 0, this.getSettingsValue('translate_y.size') || this.getSettingsValue('translate_y.sizes.to')],
              duration: this.getSettingsValue('translate_duration.size'),
              delay: this.getSettingsValue('translate_delay.size') || 0
            };
          }
        }
        if (this.getSettingsValue('rotate_toggle')) {
          if (this.getSettingsValue('rotate_x.size') || this.getSettingsValue('rotate_x.sizes.to')) {
            settings.rotateX = {
              value: [this.getSettingsValue('rotate_x.sizes.from') || 0, this.getSettingsValue('rotate_x.size') || this.getSettingsValue('rotate_x.sizes.to')],
              duration: this.getSettingsValue('rotate_duration.size'),
              delay: this.getSettingsValue('rotate_delay.size') || 0
            };
          }
          if (this.getSettingsValue('rotate_y.size') || this.getSettingsValue('rotate_y.sizes.to')) {
            settings.rotateY = {
              value: [this.getSettingsValue('rotate_y.sizes.from') || 0, this.getSettingsValue('rotate_y.size') || this.getSettingsValue('rotate_y.sizes.to')],
              duration: this.getSettingsValue('rotate_duration.size'),
              delay: this.getSettingsValue('rotate_delay.size') || 0
            };
          }
          if (this.getSettingsValue('rotate_z.size') || this.getSettingsValue('rotate_z.sizes.to')) {
            settings.rotateZ = {
              value: [this.getSettingsValue('rotate_z.sizes.from') || 0, this.getSettingsValue('rotate_z.size') || this.getSettingsValue('rotate_z.sizes.to')],
              duration: this.getSettingsValue('rotate_duration.size'),
              delay: this.getSettingsValue('rotate_delay.size') || 0
            };
          }
        }
        if (this.getSettingsValue('scale_toggle')) {
          if (this.getSettingsValue('scale_x.size') || this.getSettingsValue('scale_x.sizes.to')) {
            settings.scaleX = {
              value: [this.getSettingsValue('scale_x.sizes.from') || 0, this.getSettingsValue('scale_x.size') || this.getSettingsValue('scale_x.sizes.to')],
              duration: this.getSettingsValue('scale_duration.size'),
              delay: this.getSettingsValue('scale_delay.size') || 0
            };
          }
          if (this.getSettingsValue('scale_y.size') || this.getSettingsValue('scale_y.sizes.to')) {
            settings.scaleY = {
              value: [this.getSettingsValue('scale_y.sizes.from') || 0, this.getSettingsValue('scale_y.size') || this.getSettingsValue('scale_y.sizes.to')],
              duration: this.getSettingsValue('scale_duration.size'),
              delay: this.getSettingsValue('scale_delay.size') || 0
            };
          }
        }

        if (this.getSettingsValue('skew_toggle')) {
          if (this.getSettingsValue('skew_x.size') || this.getSettingsValue('skew_x.sizes.to')) {
            settings.skewX = {
              value: [this.getSettingsValue('skew_x.sizes.from') || 0, this.getSettingsValue('skew_x.size') || this.getSettingsValue('skew_x.sizes.to')],
              duration: this.getSettingsValue('skew_duration.size'),
              delay: this.getSettingsValue('skew_delay.size') || 0
            };
          }
          if (this.getSettingsValue('skew_y.size') || this.getSettingsValue('skew_y.sizes.to')) {
            settings.skewY = {
              value: [this.getSettingsValue('skew_y.sizes.from') || 0, this.getSettingsValue('skew_y.size') || this.getSettingsValue('skew_y.sizes.to')],
              duration: this.getSettingsValue('skew_duration.size'),
              delay: this.getSettingsValue('skew_delay.size') || 0
            };
          }
        }
        // style 
        if (this.getSettingsValue('style_toggle')) {
          if (this.getSettingsValue('opacity_toggle')) {
            settings.opacity = {
                value: [this.getSettingsValue('opacity.sizes.from') || 0, this.getSettingsValue('opacity.size') || this.getSettingsValue('opacity.sizes.to')],
                'duration': this.getSettingsValue('opacity_duration.size'),
                'delay': this.getSettingsValue('opacity_delay.size') || 0,
            };
          }

          if (this.getSettingsValue('bg_color_toggle')) {
            settings.backgroundColor = {
                value: [this.getSettingsValue('bg_color_from'), this.getSettingsValue('bg_color_to')],
                'duration': this.getSettingsValue('color_duration.size'),
                'delay': this.getSettingsValue('color_delay.size') || 0,
            };
          }
        }
        // Filter
        if (this.getSettingsValue('filters_toggle')) {
          if (this.getSettingsValue('blur_toggle')) {
            settings.filter = {
                value: [
                  "blur(" + (this.getSettingsValue('blur.sizes.from') || 0) + "px)",
                  "blur(" + (this.getSettingsValue('blur.sizes.to') || 0) + "px)"
                ],
                'duration': this.getSettingsValue('blur_duration.size'),
                'delay': this.getSettingsValue('blur_delay.size') || 0,
            };
          }
          if (this.getSettingsValue('contrast_toggle')) {
            settings.filter = {
                value: [
                  "contrast(" + (this.getSettingsValue('contrast.sizes.from') || 0) + "%)",
                  "contrast(" + (this.getSettingsValue('contrast.sizes.to') || 0) + "%)"
                ],
                'duration': this.getSettingsValue('contrast_duration.size'),
                'delay': this.getSettingsValue('contrast_delay.size') || 0,
            };
          }
          if (this.getSettingsValue('grayscale_toggle')) {
            settings.filter = {
                value: [
                  "grayscale(" + (this.getSettingsValue('grayscale.sizes.from') || 0) + "%)",
                  "grayscale(" + (this.getSettingsValue('grayscale.sizes.to') || 0) + "%)"
                ],
                'duration': this.getSettingsValue('grayscale_duration.size'),
                'delay': this.getSettingsValue('grayscale_delay.size') || 0,
            };
          }
          if (this.getSettingsValue('hue_toggle')) {
            settings.filter = {
                value: [
                  "hue-rotate(" + (this.getSettingsValue('hue.sizes.from') || 0) + "deg)",
                  "hue-rotate(" + (this.getSettingsValue('hue.sizes.to') || 0) + "deg)"
                ],
                'duration': this.getSettingsValue('hue_duration.size'),
                'delay': this.getSettingsValue('hue_delay.size') || 0,
            };
          }
          if (this.getSettingsValue('brightness_toggle')) {
            settings.filter = {
                value: [
                  "brightness(" + (this.getSettingsValue('brightness.sizes.from') || 0) + "%)",
                  "brightness(" + (this.getSettingsValue('brightness.sizes.to') || 0) + "%)"
                ],
                'duration': this.getSettingsValue('brightness_duration.size'),
                'delay': this.getSettingsValue('brightness_delay.size') || 0,
            };
          }
          if (this.getSettingsValue('saturation_toggle')) {
            settings.filter = {
                value: [
                  "saturate(" + (this.getSettingsValue('saturation.sizes.from') || 0) + "%)",
                  "saturate(" + (this.getSettingsValue('saturation.sizes.to') || 0) + "%)"
                ],
                'duration': this.getSettingsValue('saturation_duration.size'),
                'delay': this.getSettingsValue('saturation_delay.size') || 0,
            };
          }
        }

        if (this.getSettingsValue('translate_toggle') || this.getSettingsValue('rotate_toggle') || this.getSettingsValue('scale_toggle') || this.getSettingsValue('skew_toggle') || this.getSettingsValue('style_toggle') || this.getSettingsValue('filters_toggle')) {
          this.findElement('.elementor-widget-container').css('will-change', 'transform, opacity, background-color');
          this.anime = window.anime && window.anime(settings);
        }
      }
    });
    elementorFrontend.hooks.addAction('frontend/element_ready/widget', function ($scope) {
      elementorFrontend.elementsHandler.addHandler(FloatingEffectsHandler, {
        $element: $scope
      });
    });
  });
})(jQuery);
