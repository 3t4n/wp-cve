(function( $, undef ) {
  var _html, _css, ColorPalettes;
  
  _html = '<div class="color-palettes"></div>';
  _css = 
    '.color-palettes {display:block; position:relative;}' +
    '.color-palettes, .color-palettes * {-moz-box-sizing: content-box; -web-box-sizing:content-box; box-sizing: content-box;}' +
    'input+.color-palettes {margin-top:4px;}' +
    '.color-palettes .color-palettes-item {border-radius:3px;box-shadow:inset 0 0 5px rgba(0,0,0,.4);height:100%;float:left;margin: 0px 2px 5px 2px;} ' +
    '.color-palettes .color-palettes-item span {width: 50%; height: 100%; display: block; margin-left: 50%; border-top-right-radius: 3px; border-bottom-right-radius: 3px; box-shadow:inset -1px 0px 5px 0px rgba(0,0,0,.3);} ' +
    '.color-palettes-border {border-radius: 3px; border: 1px solid #aaa; width: 200px; background-color: #fff;}' +
    '.color-palettes-container {position:absolute;top:0;right:0;left:0;bottom:0}' +
	'.color-palettes-border .color-palettes-container{top:10px;right:10px;left:9px;bottom:10px}';
    
  ColorPalettes = {
    // These options will be used as defaults
    options: {
      color: false,
      hide: true,
      border: true,
      target: false,
      width: 200,
      palettes: true,
    },
    _color: '#ffffff',
    _palettes : ['#000', '#ffffff', '#d33', '#d93', '#ee2', '#81d742', '#1e73be', '#8224e3'],
    _initiated: false,
 
    _create: function() {
        var self = this,
            el = self.element,
            color = self.options.color || el.val();
			
		self._color = el.val();
		
        if (el.is('input')) {
          if (self.options.target) {
            self.palette = $(_html).appendTo(self.options.target);
          } else {
            self.palette = $(_html).insertAfter(el);
          }
          self._addInputListeners( el );
        } else {
          el.append(_html);
          self.palette = el.find('.color-palettes');
        }
        
        // add colors to palette
        self._addColorPalettes();
        
        if (self.options.hide) {
          self.palette.hide();
        }
        
        if (self.options.border) {
          self.palette.addClass('color-palettes-border');
        }
        
        self._paletteListeners();
        
        self.active = 'external';
        self._dimensions();
        self._change();
    },
 
    _addColorPalettes: function() {
      var container = $('<div class="color-palettes-container" />'),
          palette = $('<a class="color-palettes-item" tabindex="0" />'),
          palette_2 = $('<a class="color-palettes-item" tabindex="0"><span/></a>'),
          colors = $.isArray( this.options.palettes ) ? this.options.palettes : this._palettes;
      
      if (this.palette.find('.color-palettes-container').length) {
        container = this.palette.find('.color-palettes-container').detach().html('');
      }
      
      // add colors to palette
      $.each(colors, function(index, val) {
        
        var multiple = val.split('/');
        if (multiple.length > 1) {
          var color1 = multiple[0];
          var color2 = multiple[1];
          palette_2.clone()
            .data('color', val)
            .data('multicolor', val)
            .css('backgroundColor', color1)
            .appendTo(container)
            .height(10)
            .width(10)
            .find('span').css({backgroundColor: color2});
        } else {
          palette.clone().data('color', val)
            .css('backgroundColor', val)
            .appendTo(container)
            .height(10)
            .width(10);
        }
        
      });
      
      this.palette.append(container);
    },
    
        // update color palettes container size
    _dimensions: function(reset) {
      var self = this;
      var opts = self.options;
      var squareWidth = '100%';
      var totalPadding = 20;
      var innerWidth = opts.border ? (opts.width - totalPadding - 1) : (opts.width - 1);
      var paletteCount = $.isArray(opts.palettes) ? opts.palettes.length : self._palettes.length;
      var paletteMargin;
      var paletteWidth;
      var paletteContainerWidth;
      
      if (reset) {
        self.palette.css({width: '', height: ''});
      }
      
      squareWidth = innerWidth * (parseFloat(squareWidth) / 100);
      boxHeight = opts.border ? squareWidth + totalPadding : squareWidth;
      
      self.palette.css( { width: opts.width, height: boxHeight } );
      
      // Palette adjustments
	  palettesPerLine = 7;
	  paletteMargin = 4;
      paletteContainerWidth = squareWidth - (palettesPerLine * paletteMargin);
      paletteWidth = paletteContainerWidth / palettesPerLine;
      
      self.palette.find('.color-palettes-item').each( function( i ) {
        var margin = (i === 0) ? 0 : paletteMargin;
        $( this ).css({
          width: paletteWidth,
          height: paletteWidth
        });
      });
      
    },
    
    _addInputListeners: function (input) {
      var self = this,
          debounceTimeout = 100,
          callback = function (event) {
            var color = input.val();
            var val = input.val().replace('/^#/', '');
            input.removeClass('color-palettes-error');  
          
            if (color.error) {
              if (val !== '') {
                input.addClass('color-palettes-error');
              }
            } else {
              if (color.toString() !== self._color.toString()) {
                if (!(event.type === 'keyup' && val.match( /^[0-9a-fA-F]+$/ ))) {
                  self._setOption('color', color.toString());
                }
              }
            }
          };
  
      input.on('change', callback).on('keyup', self._debounce(callback, debounceTimeout));
      
      if (self.options.hide) {
        input.one('focus', function() {
          self.show();
        });
      }
    },
    
    _paletteListeners: function() {
      var self = this;
      
      self.palette.find('.color-palettes-container').on('click.palette', '.color-palettes-item', function(){
        self._color = $(this).data('color');
        self.active = 'external';
        self._change();
      }).on('keydown.palette', '.color-palettes-item', function(event) {
        if (!(event.keyCode === 13 || event.keyCode === 32)) {
          return true;
        }
        event.stopPropagation();
        $(this).trigger( 'click' );
      });
    },
        
    // Use the _setOption method to respond to changes to options
    _setOption: function( key, value ) {
      var self = this,
          oldValue = self.options[key],
          doDimensions = false,
          hexLessColor,
          newColor,
          method;
          
      self.options[key] = value;
      
      switch( key ) {
        case "color":
          value + '' + value;
          hexLessColor = value.replace( /^#/, '' );
		  newColor = value;
          if (newColor.error) {
            self.options[key] = oldValue;
          } else {
            self._color = newColor;
            self.options.color = self.options[key] = self._color.toString();
            self.active = 'external';
            self._change();
          }
          break;
        case "palettes":
          doDimensions = true;
          
          if (value) {
            self._addColorPalettes();
          } else {
            self.palette.find('.color-palettes-container').remove();
          }
          
          if ( !oldValue ) {
			self._paletteListeners();
		  }
          break;
        case "width":
          doDimensions = true;
          break;
        case "border":
          doDimensions = true;
		  method = value ? 'addClass' : 'removeClass';
		  self.palette[method]('color-palettes-border');
          break;
      }
 
      if ( doDimensions ) {
		self._dimensions(true);
	  }
      // In jQuery UI 1.8, you have to manually invoke the _setOption method from the base widget
      //$.Widget.prototype._setOption.apply( this, arguments );
      // In jQuery UI 1.9 and above, you use the _super method instead
      //this._super( "_setOption", key, value );
    },
 
    // Use the destroy method to clean up any modifications your widget has made to the DOM
    destroy: function() {
      // In jQuery UI 1.8, you must invoke the destroy method from the base widget
      $.Widget.prototype.destroy.call( this );
      // In jQuery UI 1.9 and above, you would define _destroy instead of destroy and not call the base method
    },
    
    _change: function() {
      var self = this,
          color = this._color;
      
      self.options.color = self._color.toString();
      
      // only run after the first time
      if ( self._initiated ) {
          self._trigger( 'change', { type: self.active }, { color: self._color } );
      }
      
      if (self.element.is(':input') && !self._color.error) {
        self.element.removeClass('color-palettes-error');
        if (self.element.val() !== self._color.toString()) {
          self.element.val(self._color.toString());
        }
      }
      
      self._initiated = true;
      self.active = true;
    },
    
    _debounce: function(func, wait, immediate) {
      var timeout, result;
      
      return function () {
        var context = this,
            args = arguments,
            later,
            callNow;
            
        later = function() {
          timeout = null;
          if (!immediate) {
            result = func.apply(context, args);
          }
        };
        
        callNow = immediate && !timeout;
        clearTimeout (timeout);
        timeout = setTimeout(later, wait);
        
        if (callNow) {
          result = func.apply(context, args);
        }
        
        return result;
      };
    },
    
    show: function() {
      this.palette.show();
    },
    
    hide: function() {
      this.palette.hide();
    },
    
    toggle: function() {
      this.palette.toggle();
    },
    
    color: function(newColor) {
      if ( newColor === true ) {
        return this._color.clone();
      } else if ( newColor === undef ) {
        return this._color.toString();
      }
      this.option('color', newColor);
    }
  };
  
  $.widget( "ui.colorpalettes", ColorPalettes);
  $('<style id="color-palettes-css">' + _css + '</style>').appendTo('head');
}( jQuery ) );

