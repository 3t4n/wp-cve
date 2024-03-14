jQuery(document).ready(function($) {
    $('.eic-image').hover( function() {
        $(this).find('[data-pin-log="button_pinit"]').show();
        $(this).find('.eic-image-caption-hover').show();
    }, function() {
        $(this).find('[data-pin-log="button_pinit"]').hide();
        $(this).find('.eic-image-caption-hover').hide();
    });
});

// Based on https://github.com/kumailht/responsive-elements

const responsive_breakpoint = parseInt( eic_public.responsive_breakpoint );
const responsive_layout = '1' === eic_public.responsive_layout ? true : false;

var EIC_Responsive = {
    elementsSelector: '.eic-container',
    maxRefreshRate: 5,
    init: function() {
        var self = this;
        jQuery(function() {
            self.el = {
                window: jQuery(window),
                responsive_elements: jQuery(self.elementsSelector)
            };

            self.events();
        });
    },
    checkBreakpointOfAllElements: function() {
        var self = EIC_Responsive;
        self.el.responsive_elements.each(function(i, _el) {
            var container = jQuery(_el);
            self.checkBreakpointOfElement(container);

            // Check if mobile or desktop version.
            if ( container.width() < responsive_breakpoint ) {
              container.removeClass( 'eic-container-desktop' );
              container.addClass( 'eic-container-mobile' );

              if ( responsive_layout ) {
                container.addClass( 'eic-container-mobile-regular' );
              }
            } else {
              container.addClass( 'eic-container-desktop' );
              container.removeClass( 'eic-container-mobile' );
              container.removeClass( 'eic-container-mobile-regular' );
            }
        });
    },
    checkBreakpointOfElement: function(_el) {
        var frame = _el.find('.eic-frame');

        var container_width = _el.width();
        var frame_width = frame.outerWidth();
        var orig_frame_width = frame.data('orig-width');
        var frame_ratio = frame.data('ratio');

        // Frame resize required if container is smaller or frame is smaller than original width
        if(container_width < frame_width || frame_width < orig_frame_width) {
            var new_frame_width = container_width;
            if(new_frame_width > orig_frame_width) {
                new_frame_width = orig_frame_width;
            }

            var change_ratio = new_frame_width / orig_frame_width;

            // Borders
            var orig_border = frame.data('orig-border');
            var border = Math.ceil(orig_border * change_ratio);

            // Change frame styling
            frame
                .css('width', new_frame_width + 'px')
                .css('height', new_frame_width / frame_ratio + 'px')
                .css('border-width', border + 'px');

            _el.find('.eic-image').each(function() {
                var image = jQuery(this);

                if ( responsive_layout && container_width < responsive_breakpoint ) {                  
                  // Change image styling (New)
                  image.find('img')
                      .style('position', 'static', 'important')
                      .style('width', '100%', 'important')
                      .style('height', 'auto', 'important')
                      .style('left', '0', 'important')
                      .style('top', '0', 'important');
                } else {
                  var size_x = Math.ceil(image.data('size-x') * change_ratio);
                  var size_y = Math.ceil(image.data('size-y') * change_ratio);
                  var pos_x = Math.ceil(image.data('pos-x') * change_ratio);
                  var pos_y = Math.ceil(image.data('pos-y') * change_ratio);

                  // Change image styling (Legacy)
                  image
                      .css('background-size', '' + size_x + 'px ' + size_y + 'px')
                      .css('background-position', '' + pos_x + 'px ' + pos_y + 'px')
                      .css('border-width', border + 'px');

                  // Change image styling (New)
                  image.find('img')
                      .style('position', 'absolute', 'important')
                      .style('width', '' + size_x + 'px', 'important')
                      .style('height', '' + size_y + 'px', 'important')
                      .style('left', '' + pos_x + 'px', 'important')
                      .style('top', '' + pos_y + 'px', 'important');
                }
            });
        }
    },
    events: function() {
        this.checkBreakpointOfAllElements();

        this.el.window.bind('resize', this.debounce(
            this.checkBreakpointOfAllElements, this.maxRefreshRate));
    },
    // Debounce is part of Underscore.js 1.5.2 http://underscorejs.org
    // (c) 2009-2013 Jeremy Ashkenas. Distributed under the MIT license.
    debounce: function(func, wait, immediate) {
        // Returns a function, that, as long as it continues to be invoked,
        // will not be triggered. The function will be called after it stops
        // being called for N milliseconds. If `immediate` is passed,
        // trigger the function on the leading edge, instead of the trailing.
        var result;
        var timeout = null;
        return function() {
            var context = this,
                args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) result = func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) result = func.apply(context, args);
            return result;
        };
    }
};

// Source: http://stackoverflow.com/questions/2655925/how-to-apply-important-using-css
(function($) {    
  if ($.fn.style) {
    return;
  }

  // Escape regex chars with \
  var escape = function(text) {
    return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
  };

  // For those who need them (< IE 9), add support for CSS functions
  var isStyleFuncSupported = !!CSSStyleDeclaration.prototype.getPropertyValue;
  if (!isStyleFuncSupported) {
    CSSStyleDeclaration.prototype.getPropertyValue = function(a) {
      return this.getAttribute(a);
    };
    CSSStyleDeclaration.prototype.setProperty = function(styleName, value, priority) {
      this.setAttribute(styleName, value);
      var priority = typeof priority != 'undefined' ? priority : '';
      if (priority != '') {
        // Add priority manually
        var rule = new RegExp(escape(styleName) + '\\s*:\\s*' + escape(value) +
            '(\\s*;)?', 'gmi');
        this.cssText =
            this.cssText.replace(rule, styleName + ': ' + value + ' !' + priority + ';');
      }
    };
    CSSStyleDeclaration.prototype.removeProperty = function(a) {
      return this.removeAttribute(a);
    };
    CSSStyleDeclaration.prototype.getPropertyPriority = function(styleName) {
      var rule = new RegExp(escape(styleName) + '\\s*:\\s*[^\\s]*\\s*!important(\\s*;)?',
          'gmi');
      return rule.test(this.cssText) ? 'important' : '';
    }
  }

  // The style function
  $.fn.style = function(styleName, value, priority) {
    // DOM node
    var node = this.get(0);
    // Ensure we have a DOM node
    if (typeof node == 'undefined') {
      return this;
    }
    // CSSStyleDeclaration
    var style = this.get(0).style;
    // Getter/Setter
    if (typeof styleName != 'undefined') {
      if (typeof value != 'undefined') {
        // Set style property
        priority = typeof priority != 'undefined' ? priority : '';
        style.setProperty(styleName, value, priority);
        return this;
      } else {
        // Get style property
        return style.getPropertyValue(styleName);
      }
    } else {
      // Get CSSStyleDeclaration
      return style;
    }
  };
})(jQuery);

EIC_Responsive.init();