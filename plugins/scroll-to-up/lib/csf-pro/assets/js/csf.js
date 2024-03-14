/**
 *
 * -----------------------------------------------------------
 *
 * Codestar Framework
 * A Lightweight and easy-to-use WordPress Options Framework
 *
 * Copyright 2015 Codestar <info@codestarlive.com>
 *
 * -----------------------------------------------------------
 *
 */
;(function ( $, window, document, undefined ) {
  'use strict';

  // caching
  var CSF     = {};
  var $body   = $('body');
  var has_rtl = $body.hasClass('rtl');

  CSF.funcs = {};
  CSF.vars  = {};

  //
  // Helper Functions
  //
  CSF.helper = {
    name_replace: function( $selector ) {

      $selector.find('.csf-cloneable-item').each( function( index ) {
        $(this).find(':input').each( function(){
          this.name = this.name.replace(/\]\[(\d+)\]/, ']['+ index +']');
        });
      });

    },
  };

  //
  // Custom clone for textarea and select clone() bug
  //
  $.fn.csf_clone = function () {

    var base   = $.fn.clone.apply(this, arguments),
        clone  = this.find('select').add(this.filter('select')),
        cloned = base.find('select').add(base.filter('select'));

    for( var i = 0; i < clone.length; ++i ) {
      for( var j = 0; j < clone[i].options.length; ++j ) {

        if( clone[i].options[j].selected === true ) {
          cloned[i].options[j].selected = true;
        }

      }
    }

    return base;

  };

  //
  // Navigation
  //
  $.fn.csf_navigation = function() {
    return this.each(function() {

      var $nav     = $(this),
          $parent  = $nav.closest('.csf'),
          $section = $parent.find('.csf-section-id'),
          $expand  = $parent.find('.csf-expand-all'),
          $tabbed;

      $nav.find('ul:first a').on('click', function (e) {

        e.preventDefault();

        var $el     = $(this),
            $next   = $el.next(),
            $target = $el.data('section');

        if( $next.is('ul') ) {

          $el.closest('li').toggleClass('csf-tab-active');

        } else {

          $tabbed = $('#csf-tab-'+$target);

          $tabbed.removeClass('hidden').siblings().addClass('hidden');

          $nav.find('a').removeClass('csf-section-active');
          $el.addClass('csf-section-active');
          $section.val($target);

          $tabbed.csf_reload_script();

        }

      });

      $expand.on('click', function (e) {

        e.preventDefault();

        $parent.find('.csf-wrapper').toggleClass('csf-show-all');
        $parent.find('.csf-section').not('.csf-onload').csf_reload_script();
        $(this).find('.fa').toggleClass('fa-eye-slash' ).toggleClass('fa-eye');

      });

    });
  };

  //
  // Search
  //
  $.fn.csf_search = function() {
    return this.each(function() {

      var $this    = $(this),
          $input   = $this.find('input');

      $input.on('change keyup', function() {

        var value    = $(this).val(),
            $wrapper = $('.csf-wrapper'),
            $section = $wrapper.find('.csf-section'),
            $fields  = $section.find('> .csf-field:not(.hidden)'),
            $titles  = $fields.find('> .csf-title, .csf-search-tags');

        if( value.length > 3 ) {

          $fields.addClass('csf-hidden');
          $wrapper.addClass('csf-search-all');

          $titles.each( function() {

            var $title = $(this);

            if( $title.text().match( new RegExp('.*?' + value + '.*?', 'i') ) ) {

              var $field = $title.closest('.csf-field');

              $field.removeClass('csf-hidden');
              $field.parent().csf_reload_script();

            }

          });

        } else {

          $fields.removeClass('csf-hidden');
          $wrapper.removeClass('csf-search-all');

        }

      });

    });
  };

  //
  // Sticky Header
  //
  $.fn.csf_sticky = function() {
    return this.each(function() {

      var $this     = $(this),
          $window   = $(window),
          $inner    = $this.find('.csf-header-inner'),
          padding   = parseInt( $inner.css('padding-left') ) + parseInt( $inner.css('padding-right') ),
          offset    = 32,
          scrollTop = 0,
          lastTop   = 0,
          ticking   = false,
          onSticky  = function() {

            scrollTop = $window.scrollTop();
            requestTick();

          },
          requestTick = function () {

            if( !ticking ) {
              requestAnimationFrame( function() {
                stickyUpdate();
                ticking = false;
              });
            }

            ticking = true;

          },
          stickyUpdate = function() {

            var offsetTop = $this.offset().top,
                stickyTop = Math.max(offset, offsetTop - scrollTop ),
                winWidth  = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

            if ( stickyTop <= offset && winWidth > 782 ) {
              $inner.css({width: $this.outerWidth()-padding});
              $this.css({height: $this.outerHeight()}).addClass( 'csf-sticky' );
            } else {
              $inner.removeAttr('style');
              $this.removeAttr('style').removeClass( 'csf-sticky' );
            }

          };

      $window.on( 'scroll resize', onSticky);

      onSticky();

    });
  };

  //
  // Dependency System
  //
  $.fn.csf_dependency = function ( param ) {
    return this.each(function () {

      var base  = this,
          $this = $(this);

      base.init = function () {

        base.ruleset = $.deps.createRuleset();

        var cfg = {
          show: function( el ) {
            el.removeClass('hidden');
          },
          hide: function( el ) {
            el.addClass('hidden');
          },
          log: false,
          checkTargets: false
        };

        if( param !== undefined ) {
          base.depSub();
        } else {
          base.depRoot();
        }

        $.deps.enable( $this, base.ruleset, cfg );

      };

      base.depRoot = function() {

        $this.each( function() {

          $(this).find('[data-controller]').each( function() {

            var $this       = $(this),
                _controller = $this.data('controller').split('|'),
                _condition  = $this.data('condition').split('|'),
                _value      = $this.data('value').toString().split('|'),
                _rules      = base.ruleset;

            $.each(_controller, function(index, element) {

              var value     = _value[index] || '',
                  condition = _condition[index] || _condition[0];

              _rules = _rules.createRule('[data-depend-id="'+ element +'"]', condition, value);
              _rules.include($this);

            });

          });

        });

      };

      base.depSub = function() {

        $this.each( function() {

          $(this).find('[data-sub-controller]').each( function() {

            var $this       = $(this),
                _controller = $this.data('sub-controller').split('|'),
                _condition  = $this.data('sub-condition').split('|'),
                _value      = $this.data('sub-value').toString().split('|'),
                _rules      = base.ruleset;

            $.each(_controller, function(index, element) {

              var value     = _value[index] || '',
                  condition = _condition[index] || _condition[0];

              _rules = _rules.createRule('[data-sub-depend-id="'+ element +'"]', condition, value);
              _rules.include($this);

            });

          });

        });

      };

      base.init();

    });
  };

  //
  // Chosen Script
  //
  $.fn.csf_chosen = function() {
    return this.each(function() {

      $(this).chosen({allow_single_deselect: true, disable_search_threshold: 15, width: parseFloat( $(this).actual('width') + 25 ) +'px'});

    });
  };

  //
  // Field Image Selector
  //
  $.fn.csf_field_image_selector = function() {
    return this.each(function() {

      $(this).find('label').on('click', function () {
        $(this).siblings().find('input').prop('checked', false);
      });

    });
  };

  //
  // Field Sorter
  //
  $.fn.csf_field_sorter = function() {
    return this.each(function() {

      var $this         = $(this),
          $enabled      = $this.find('.csf-enabled'),
          $has_disabled = $this.find('.csf-disabled'),
          $disabled     = ( $has_disabled.length ) ? $has_disabled : false;

      $enabled.sortable({
        connectWith: $disabled,
        placeholder: 'ui-sortable-placeholder',
        update: function( event, ui ) {


          var $el = ui.item.find('input');

          if( ui.item.parent().hasClass('csf-enabled') ) {
            $el.attr('name', $el.attr('name').replace('disabled', 'enabled'));
          } else {
            $el.attr('name', $el.attr('name').replace('enabled', 'disabled'));
          }

          $this.csf_customizer_refresh();

        }
      });

      if( $disabled ) {

        $disabled.sortable({
          connectWith: $enabled,
          placeholder: 'ui-sortable-placeholder'
        });

      }

    });
  };

  //
  // Field Upload
  //
  $.fn.csf_field_upload = function() {
    return this.each(function() {

      var $this      = $(this),
          $button    = $this.find('.csf-button'),
          $preview   = $this.find('.csf-image-preview'),
          $remove    = $this.find('.csf-image-remove'),
          $img       = $this.find('img'),
          $input     = $this.find('input'),
          extensions = ['jpg', 'gif', 'png', 'svg', 'jpeg'],
          wp_media_frame;

      $button.on('click', function( e ) {

        e.preventDefault();

        if ( typeof wp === 'undefined' || ! wp.media || ! wp.media.gallery ) {
          return;
        }

        if ( wp_media_frame ) {
          wp_media_frame.open();
          return;
        }

        wp_media_frame = wp.media({
          title: $button.data('frame-title'),
          library: {
            type: $button.data('upload-type')
          },
          button: {
            text: $button.data('insert-title'),
          }
        });

        wp_media_frame.on( 'select', function() {

          var attachment = wp_media_frame.state().get('selection').first();

          $input.val( attachment.attributes.url ).trigger('change');

        });

        wp_media_frame.open();

      });

      if( $preview.length ) {

        $input.on('change keyup', function() {

          var $this = $(this),
              value = $this.val(),
              ext   = value.toLowerCase().slice((value.toLowerCase().lastIndexOf('.') - 1) + 2);

          if( $.inArray( ext, extensions ) > -1 ) {
            $preview.removeClass('hidden');
            $img.attr('src', value);
          } else {
            $preview.addClass('hidden');
          }

        });

        $remove.on('click', function( e ) {

          e.preventDefault();
          $input.val('').trigger('change');
          $preview.addClass('hidden');

        });

      }

    });

  };

  //
  // Field Image
  //
  $.fn.csf_field_image = function() {
    return this.each(function() {

      var $this    = $(this),
          $button  = $this.find('.csf-button'),
          $preview = $this.find('.csf-image-preview'),
          $remove  = $this.find('.csf-image-remove'),
          $input   = $this.find('input'),
          $img     = $this.find('img'),
          wp_media_frame;

      $button.on('click', function( e ) {

        e.preventDefault();

        if ( typeof wp === 'undefined' || ! wp.media || ! wp.media.gallery ) {
          return;
        }

        if ( wp_media_frame ) {
          wp_media_frame.open();
          return;
        }

        wp_media_frame = wp.media({
          library: {
            type: 'image'
          }
        });

        wp_media_frame.on( 'select', function() {

          var attachment = wp_media_frame.state().get('selection').first().attributes;
          var thumbnail = ( typeof attachment.sizes !== 'undefined' && typeof attachment.sizes.thumbnail !== 'undefined' ) ? attachment.sizes.thumbnail.url : attachment.url;

          $preview.removeClass('hidden');
          $img.attr('src', thumbnail);
          $input.val( attachment.id ).trigger('change');

        });

        wp_media_frame.open();

      });

      $remove.on('click', function( e ) {
        e.preventDefault();
        $input.val('').trigger('change');
        $preview.addClass('hidden');
      });

    });

  };

  //
  // Field Gallery
  //
  $.fn.csf_field_gallery = function() {
    return this.each(function() {

      var $this  = $(this),
          $edit  = $this.find('.csf-edit-gallery'),
          $clear = $this.find('.csf-clear-gallery'),
          $list  = $this.find('ul'),
          $input = $this.find('input'),
          $img   = $this.find('img'),
          wp_media_frame,
          wp_media_click;

      $this.on('click', '.csf-button, .csf-edit-gallery', function( e ) {

        var $el   = $(this),
            what  = ( $el.hasClass('csf-edit-gallery') ) ? 'edit' : 'add',
            state = ( what === 'edit' ) ? 'gallery-edit' : 'gallery-library';

        e.preventDefault();

        if ( typeof wp === 'undefined' || ! wp.media || ! wp.media.gallery ) {
          return;
        }

        if ( wp_media_frame ) {
          wp_media_frame.open();
          wp_media_frame.setState(state);
          return;
        }

        wp_media_frame = wp.media({
          library: {
            type: 'image'
          },
          frame: 'post',
          state: 'gallery',
          multiple: true
        });

        wp_media_frame.on('open', function() {

          var ids = $input.val();

          if ( ids ) {

            var get_array = ids.split(',');
            var library   = wp_media_frame.state('gallery-edit').get('library');

            wp_media_frame.setState(state);

            get_array.forEach(function(id) {
              var attachment = wp.media.attachment(id);
              library.add( attachment ? [ attachment ] : [] );
            });

          }
        });

        wp_media_frame.on( 'update', function() {

          var inner  = '';
          var ids    = [];
          var images = wp_media_frame.state().get('library');

          images.each(function(attachment) {

            var attributes = attachment.attributes;
            var thumbnail  = ( typeof attributes.sizes.thumbnail !== 'undefined' ) ? attributes.sizes.thumbnail.url : attributes.url;

            inner += '<li><img src="'+ thumbnail +'"></li>';
            ids.push(attributes.id);

          });

          $input.val(ids).trigger('change');
          $list.html('').append(inner);
          $clear.removeClass('hidden');
          $edit.removeClass('hidden');

        });

        wp_media_frame.open();
        wp_media_click = what;

      });

      $clear.on('click', function( e ) {
        e.preventDefault();
        $list.html('');
        $input.val('').trigger('change');
        $clear.addClass('hidden');
        $edit.addClass('hidden');
      });

    });

  };

  //
  // Field Group
  //
  $.fn.csf_field_group = function() {
    return this.each(function() {

      var $this    = $(this),
          $wrapper = $this.find('.csf-cloneable-wrapper'),
          $data    = $this.find('.csf-cloneable-data'),
          $hidden  = $this.find('.csf-cloneable-hidden'),
          unique   = $data.data('unique-id'),
          limit    = parseInt( $data.data('limit') );

      $wrapper.accordion({
        header: '.csf-cloneable-title',
        collapsible : true,
        active: false,
        animate: false,
        heightStyle: 'content',
        icons: {
          'header': 'csf-cloneable-header-icon fa fa-angle-right',
          'activeHeader': 'csf-cloneable-header-icon fa fa-angle-down'
        },
        beforeActivate: function( event, ui ) {

          var $panel = ui.newPanel;

          if( $panel.length && !$panel.data( 'opened' ) ) {

            $panel.find('.csf-field').removeClass('csf-no-script');
            $panel.csf_reload_script('sub');
            $panel.data( 'opened', true );

          }

        }
      });

      $wrapper.sortable({
        axis: 'y',
        handle: '.csf-cloneable-title',
        helper: 'original',
        cursor: 'move',
        placeholder: 'widget-placeholder',
        start: function( event, ui ) {

          $wrapper.accordion({ active:false });
          $wrapper.sortable('refreshPositions');

        },
        stop: function( event, ui ) {

          CSF.helper.name_replace( $wrapper );
          $wrapper.csf_customizer_refresh();

        }
      });

      $this.on('click', '.csf-cloneable-add', function( e ) {

        e.preventDefault();

        var count = $wrapper.find('.csf-cloneable-item').length;

        if( limit && (count+1) > limit ) {
          $data.show();
          return;
        }

        var $cloned_item = $hidden.csf_clone().removeClass('csf-cloneable-hidden');

        $cloned_item.find(':input').each( function() {
          this.name = this.name.replace('_nonce', unique).replace('num', count);
        });

        $wrapper.append($cloned_item);
        $wrapper.accordion('refresh');
        $wrapper.accordion({active: count});
        $wrapper.csf_customizer_refresh();
        $wrapper.csf_customizer_listen(true);

      });

      $wrapper.on('click', '.csf-cloneable-clone', function( e ) {

        e.preventDefault();

        if( limit && parseInt($wrapper.find('.csf-cloneable-item').length+1) > limit ) {
          $data.show();
          return;
        }

        var $this   = $(this),
            $parent = $this.closest('.csf-cloneable-item'),
            $cloned = $parent.csf_clone().addClass('csf-cloned'),
            $childs = $wrapper.children();

        $childs.eq($parent.index()).after($cloned);

        CSF.helper.name_replace( $wrapper );

        $wrapper.accordion('refresh');
        $wrapper.csf_customizer_refresh();
        $wrapper.csf_customizer_listen(true);

      });

      $wrapper.on('click', '.csf-cloneable-remove', function(e) {

        e.preventDefault();

        $(this).closest('.csf-cloneable-item').remove();

        CSF.helper.name_replace( $wrapper );

        $wrapper.csf_customizer_refresh();

        $data.hide();

      });

    });
  };


  //
  // Field Repeater
  //
  $.fn.csf_field_repeater = function() {
    return this.each(function() {

      var $this    = $(this),
          $wrapper = $this.find('.csf-cloneable-wrapper'),
          $hidden  = $this.find('.csf-cloneable-hidden'),
          $data    = $this.find('.csf-cloneable-data'),
          unique   = $data.data('unique-id'),
          limit    = parseInt( $data.data('limit') );

      $wrapper.sortable({
        axis: 'y',
        handle: '.csf-cloneable-sort',
        helper: 'original',
        cursor: 'move',
        placeholder: 'widget-placeholder',
        stop: function( event, ui ) {

          CSF.helper.name_replace( $wrapper );
          $wrapper.csf_customizer_refresh();

        }
      });

      $this.on('click', '.csf-cloneable-add', function( e ) {

        e.preventDefault();

        var count = $wrapper.find('.csf-cloneable-item').length;

        if( limit && (count+1) > limit ) {
          $data.show();
          return;
        }

        var $cloned = $hidden.csf_clone().removeClass('csf-cloneable-hidden');

        $wrapper.append($cloned);

        $cloned.find(':input').each( function() {
          this.name = this.name.replace('_nonce', unique).replace('num', count);
        });

        $cloned.find('.csf-field').removeClass('csf-no-script');
        $cloned.csf_reload_script('sub');

        $wrapper.csf_customizer_refresh();
        $wrapper.csf_customizer_listen(true);

      });

      $wrapper.on('click', '.csf-cloneable-clone', function( e ) {

        e.preventDefault();

        if( limit && parseInt($wrapper.find('.csf-cloneable-item').length+1) > limit ) {
          $data.show();
          return;
        }

        var $this   = $(this),
            $parent = $this.closest('.csf-cloneable-item'),
            $index  = $parent.index(),
            $cloned = $parent.csf_clone(),
            $childs = $wrapper.children();

        $childs.eq($index).after($cloned);

        $cloned.find(':input').each( function() {
          this.name = this.name.replace(/\]\[(\d+)\]/, ']['+ $childs.length +']');
        });

        $cloned.addClass('csf-cloned').csf_reload_script('sub');

        CSF.helper.name_replace( $wrapper );

        $wrapper.csf_customizer_refresh();
        $wrapper.csf_customizer_listen(true);

      });

      $wrapper.on('click', '.csf-cloneable-remove', function(e) {

        e.preventDefault();

        $(this).closest('.csf-cloneable-item').remove();

        $data.hide();

        CSF.helper.name_replace( $wrapper );

        $wrapper.csf_customizer_refresh();

      });

    });
  };

  //
  // Field Icon
  //
  $.fn.csf_field_icon = function() {

    return this.each( function() {

      var $this = $(this);

      $this.on('click', '.csf-icon-add', function ( e ) {

        var $modal = $('#csf-modal-icon');

        e.preventDefault();

        $modal.show();
        $body.addClass('csf-icon-scrolling');

        CSF.vars.$icon_target = $this;

        if( !CSF.vars.icon_modal_loaded ) {

          $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
              action: 'csf-get-icons'
            },
            success: function( content ) {

              CSF.vars.icon_modal_loaded = true;

              var $load = $modal.find('.csf-modal-content').html( content );

              $load.on('click', 'a', function ( e ) {

                e.preventDefault();

                var icon = $(this).data('csf-icon');

                CSF.vars.$icon_target.find('i').removeAttr('class').addClass(icon);
                CSF.vars.$icon_target.find('input').val(icon).trigger('change');
                CSF.vars.$icon_target.find('.csf-icon-preview').removeClass('hidden');
                CSF.vars.$icon_target.find('.csf-icon-remove').removeClass('hidden');

                $modal.hide();
                $body.removeClass('csf-icon-scrolling');

              });

              $modal.on('change keyup', '.csf-icon-search', function(){

                var value  = $(this).val(),
                    $icons = $load.find('a');

                $icons.each(function() {

                  var $elem = $(this);

                  if ( $elem.data('csf-icon').search( new RegExp( value, 'i' ) ) < 0 ) {
                    $elem.hide();
                  } else {
                    $elem.show();
                  }

                });

              });

              $modal.on('click', '.csf-modal-close, .csf-modal-overlay', function() {

                $modal.hide();
                $body.removeClass('csf-icon-scrolling');

              });

            }

          });

        }

      });

      $this.on('click', '.csf-icon-remove', function ( e ) {

        e.preventDefault();

        $this.find('.csf-icon-preview').addClass('hidden');
        $this.find('input').val('').trigger('change');
        $(this).addClass('hidden');

      });

    });
  };

  //
  // Color Picker Helper
  //
  if( typeof Color === 'function' ) {

    Color.fn.toString = function () {

      if ( this._alpha < 1 ) {
        return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
      }

      var hex = parseInt( this._color, 10 ).toString( 16 );

      if ( this.error ) { return ''; }

      if ( hex.length < 6 ) {
        for (var i = 6 - hex.length - 1; i >= 0; i--) {
          hex = '0' + hex;
        }
      }

      return '#' + hex;

    };

  }

  CSF.funcs.PARSE_COLOR_VALUE = function( val ) {

    var value = val.replace(/\s+/g, ''),
        alpha = ( value.indexOf('rgba') !== -1 ) ? parseFloat( value.replace(/^.*,(.+)\)/, '$1') * 100 ) : 100,
        rgba  = ( alpha < 100 ) ? true : false;

    return { value: value, alpha: alpha, rgba: rgba };

  };

  //
  // Field Color Picker
  //
  $.fn.csf_field_colorpicker = function() {

    return this.each(function() {

      var $this     = $(this),
          $input    = $this.find('.csf-wp-color-picker'),
          $wppicker = $this.find('.wp-picker-container');

      // Destroy and Reinit
      if( $wppicker.length ) {
        $wppicker.after($input).remove();
      }

      if( $input.data('rgba') !== false ) {

        var picker = CSF.funcs.PARSE_COLOR_VALUE( $input.val() );

        $input.wpColorPicker({

          clear: function() {
            $input.trigger('keyup');
          },

          change: function( event, ui ) {

            var ui_color_value = ui.color.toString();

            $input.closest('.wp-picker-container').find('.csf-alpha-slider-offset').css('background-color', ui_color_value);
            $input.val(ui_color_value).trigger('change');

          },

          create: function() {

            var a8cIris       = $input.data('a8cIris'),
                $container    = $input.closest('.wp-picker-container'),

                $alpha_wrap   = $('<div class="csf-alpha-wrap">' +
                                  '<div class="csf-alpha-slider"></div>' +
                                  '<div class="csf-alpha-slider-offset"></div>' +
                                  '<div class="csf-alpha-text"></div>' +
                                  '</div>').appendTo( $container.find('.wp-picker-holder') ),

                $alpha_slider = $alpha_wrap.find('.csf-alpha-slider'),
                $alpha_text   = $alpha_wrap.find('.csf-alpha-text'),
                $alpha_offset = $alpha_wrap.find('.csf-alpha-slider-offset');

            $alpha_slider.slider({

              slide: function( event, ui ) {

                var slide_value = parseFloat( ui.value / 100 );

                a8cIris._color._alpha = slide_value;
                $input.wpColorPicker( 'color', a8cIris._color.toString() );
                $alpha_text.text( ( slide_value < 1 ? slide_value : '' ) );

              },

              create: function() {

                var slide_value = parseFloat( picker.alpha / 100 ),
                    alpha_text_value = slide_value < 1 ? slide_value : '';

                $alpha_text.text(alpha_text_value);
                $alpha_offset.css('background-color', picker.value);

                $container.on('click', '.wp-picker-clear', function() {

                  a8cIris._color._alpha = 1;
                  $alpha_text.text('').trigger('change');
                  $alpha_slider.slider('option', 'value', 100).trigger('slide');

                });

                $container.on('click', '.wp-picker-default', function() {

                  var default_picker = CSF.funcs.PARSE_COLOR_VALUE( $input.data('default-color') ),
                      default_value  = parseFloat( default_picker.alpha / 100 ),
                      default_text   = default_value < 1 ? default_value : '';

                  a8cIris._color._alpha = default_value;
                  $alpha_text.text(default_text);
                  $alpha_slider.slider('option', 'value', default_picker.alpha).trigger('slide');

                });

                $container.on('click', '.wp-color-result', function() {
                  $alpha_wrap.toggle();
                });

                $body.on( 'click.wpcolorpicker', function() {
                  $alpha_wrap.hide();
                });

              },

              value: picker.alpha,
              step: 1,
              min: 1,
              max: 100

            });
          }

        });

      } else {

        $input.wpColorPicker({
          clear: function() {
            $input.trigger('keyup');
          },
          change: function( event, ui ) {
            $input.val(ui.color.toString()).trigger('change');
          }
        });

      }

    });

  };

  //
  // Field Ace Editor
  //
  $.fn.csf_field_ace_editor = function() {
    return this.each(function() {

      if( typeof ace !== 'undefined' ) {

        var $this     = $(this),
            $textarea = $this.find('.csf-ace-editor-textarea'),
            options   = JSON.parse( $this.find( '.csf-ace-editor-options' ).val() ),
            editor    = ace.edit($this.find('.csf-ace-editor').attr('id'));

        // global settings of ace editor
        editor.getSession().setValue($textarea.val());

        editor.setOptions( options );

        editor.on( 'change', function( e ) {
          $textarea.val( editor.getSession().getValue() ).trigger('change');
        });

      }

    });
  };

  //
  // Field Datepicker
  //
  $.fn.csf_field_datepicker = function() {
    return this.each(function() {

      var $this   = $(this),
          $input  = $this.find('input'),
          options = JSON.parse( $this.find('.csf-datepicker-options').val() ),
          wrapper = '<div class="csf-datepicker-wrapper"></div>',
          $datepicker;

      var defaults = {
        beforeShow: function(input, inst) {
          $datepicker = $('#ui-datepicker-div');
          $datepicker.wrap(wrapper);
        },
        onClose: function(){
          var cancelInterval = setInterval( function() {
            if( $datepicker.is( ':hidden' ) ) {
              $datepicker.unwrap(wrapper);
              clearInterval(cancelInterval);
            }
          }, 100 );
        }
      };

      options = $.extend({}, options, defaults);

      $input.datepicker(options);

    });
  };

  //
  // Field Tabbed
  //
  $.fn.csf_field_tabbed = function() {
    return this.each( function() {

      var $this    = $(this),
          $links   = $this.find('.csf-tabbed-nav a'),
          $section = $this.find('.csf-tabbed-section');

      $links.on( 'click', function(e) {

       e.preventDefault();

        var $link = $(this),
            index = $link.index();

        $link.addClass('csf-tabbed-active').siblings().removeClass('csf-tabbed-active');
        $section.eq(index).removeClass('hidden').siblings().addClass('hidden');

      });

    });
  };

  //
  // Field Backup
  //
  $.fn.csf_field_backup = function() {
    return this.each( function() {

      var $this   = $(this),
          $reset  = $this.find('.csf-reset-js'),
          $import = $this.find('.csf-import-js'),
          data    = $this.find('.csf-data').data();

      $reset.on( 'click', function( e ) {

        $('.csf-options').addClass('csf-saving');

        e.preventDefault();

        $.ajax({
          type: 'POST',
          url: ajaxurl,
          data: {
            action: 'csf-reset-options',
            unique: data.unique,
            wpnonce: data.wpnonce
          },
          success: function() {
            location.reload();
          }
        });

      });

      $import.on( 'click', function( e ) {

        $('.csf-options').addClass('csf-saving');

        e.preventDefault();

        $.ajax({
          type: 'POST',
          url: ajaxurl,
          data: {
            action: 'csf-import-options',
            unique: data.unique,
            wpnonce: data.wpnonce,
            value: $this.find('.csf-import-data').val()
          },
          success: function( content ) {
            location.reload();
          }
        });

      });


    });
  };

  //
  // Confirm
  //
  $.fn.csf_confirm = function() {
    return this.each( function() {
      $(this).on('click', function( e ) {
        if ( !confirm('Are you sure?') ) {
          e.preventDefault();
        }
      });
    });
  };

  //
  // Options Save
  //
  $.fn.csf_save = function() {
    return this.each( function() {

      var $this  = $(this),
          $text  = $this.data('save'),
          $value = $this.val(),
          $ajax  = $('.csf-save-ajax'),
          $panel = $('.csf-options');

      $(document).on('keydown', function(event) {
        if (event.ctrlKey || event.metaKey) {
          if( String.fromCharCode(event.which).toLowerCase() === 's' ) {
            event.preventDefault();
            $this.trigger('click');
          }
        }
      });

      $this.on('click', function ( e ) {

        if( $ajax.length ) {

          if( typeof tinyMCE === 'object' ) {
            tinyMCE.triggerSave();
          }

          $panel.addClass('csf-saving');
          $this.prop('disabled', true).attr('value', $text);

          var serializedOptions = $('#CSF_form').serialize();

          $.post( 'options.php', serializedOptions ).error( function() {
            alert('Error, Please try again.');
          }).success( function() {
            $panel.removeClass('csf-saving');
            $this.prop('disabled', false).attr('value', $value);
          });

          e.preventDefault();

        } else {

          $this.addClass('disabled').attr('value', $text);

        }

      });

    });
  };

  //
  // Taxonomy Framework
  //
  $.fn.csf_taxonomy = function() {
    return this.each( function() {

      var $this   = $(this),
          $parent = $this.parent();

      if( $parent.attr('id') === 'addtag' ) {

        var $submit  = $parent.find('#submit'),
            $clone   = $this.find('.csf-field').csf_clone(),
            $list    = $('#the-list'),
            flooding = false;

        $submit.on( 'click', function() {

          if( !flooding ) {

            $list.on( 'DOMNodeInserted', function() {

              if( flooding ) {

                $this.empty();
                $this.html( $clone );
                $clone = $clone.csf_clone();

                $this.csf_reload_script();

                flooding = false;

              }

            });

          }

          flooding = true;

        });

      }

    });
  };

  //
  // Shortcode Framework
  //
  $.fn.csf_shortcode = function() {

    var instance = this, deploy_atts;

    instance.validate_atts = function( _atts, _this ) {

      var el_value;

      if( _this.data('check') !== undefined && deploy_atts === _atts ) { return ''; }

      deploy_atts = _atts;

      if ( _this.closest('.pseudo-field').hasClass('hidden') === true ) { return ''; }
      if ( _this.hasClass('pseudo') === true ) { return ''; }

      if( _this.is(':checkbox') || _this.is(':radio') ) {
        el_value = _this.is(':checked') ? _this.val() : '';
      } else {
        el_value = _this.val();
      }

      if( _this.data('check') !== undefined ) {
        el_value = _this.closest('.csf-field').find('input:checked').map( function() {
         return $(this).val();
        }).get();
      }

      if( el_value !== null && el_value !== undefined && el_value !== '' && el_value.length !== 0 ) {
        return ' ' + _atts + '="' + el_value + '"';
      }

      return '';

    };

    instance.insertAtChars = function ( _this, currentValue ) {

      var obj = ( typeof _this[0].name !== 'undefined' ) ? _this[0] : _this;

      if ( obj.value.length && typeof obj.selectionStart !== 'undefined' ) {
        obj.focus();
        return obj.value.substring( 0, obj.selectionStart ) + currentValue + obj.value.substring( obj.selectionEnd, obj.value.length );
      } else {
        obj.focus();
        return currentValue;
      }

    };

    instance.send_to_editor = function ( html, editor_id ) {

      var tinymce_editor;

      if ( typeof tinymce !== 'undefined' ) {
        tinymce_editor = tinymce.get( editor_id );
      }

      if ( tinymce_editor && !tinymce_editor.isHidden() ) {
        tinymce_editor.execCommand( 'mceInsertContent', false, html );
      } else {
        var $editor = $('#'+editor_id);
        $editor.val( instance.insertAtChars( $editor, html ) ).trigger('change');
      }

    };

    return this.each( function() {

      var $this     = $(this),
          $content  = $this.find('.csf-modal-content'),
          $insert   = $this.find('.csf-modal-insert'),
          $select   = $this.find('select'),
          modal_id  = $this.data('modal-id'),
          editor_id,
          sc_name,
          sc_view,
          sc_clone,
          $sc_elem;

      $('.csf-shortcode-button[data-modal-button-id="'+ modal_id +'"]').each( function() {

        var $button = $(this);

        $button.on('click', function ( e ) {

          e.preventDefault();

          $sc_elem  = $button;
          editor_id = $button.data('editor-id') || false;

          $this.show();
          $body.addClass('csf-shortcode-scrolling');

        });

      });

      $select.on( 'change', function() {

        var $elem   = $(this);
            sc_name = $elem.val();
            sc_view = $elem.find(':selected').data('view');

        if( sc_name.length ){

          $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
              action: 'csf-get-shortcode-'+modal_id,
              shortcode: sc_name
            },
            success: function( content ) {

              $content.html( content );
              $insert.parent().removeClass('hidden');

              sc_clone = $('.csf-shortcode-clone', $this).csf_clone();

              $content.csf_reload_script('sub');

            }
          });

        } else {

          $insert.parent().addClass('hidden');
          $content.html('');

        }

      });

      $insert.on('click', function ( e ) {

        e.preventDefault();

        var shortcode = '',
            ruleAttr  = 'data-atts',
            cloneAttr = 'data-clone-atts',
            cloneID   = 'data-clone-id';

        switch ( sc_view ) {

          case 'contents':

            $this.find('[' + ruleAttr + ']').each( function() {
              var _this = $(this), _atts = _this.data('atts');
              shortcode += '['+_atts+']';
              shortcode += _this.val();
              shortcode += '[/'+_atts+']';
            });

          break;

          case 'clone':

            shortcode += '[' + sc_name;

            $('[' + ruleAttr + ']', $this.find('.csf-field:not(.hidden)') ).each( function() {
              var _this_main = $(this), _this_main_atts = _this_main.data('atts');
              shortcode += instance.validate_atts( _this_main_atts, _this_main );
            });

            shortcode += ']';

            $this.find('[' + cloneID + ']').each( function() {

              var _this_clone = $(this),
                  _clone_id   = _this_clone.data('clone-id');

              shortcode += '[' + _clone_id;

              $('[' + cloneAttr + ']', _this_clone.find('.csf-field:not(.hidden)') ).each( function() {

                var _this_multiple = $(this), _atts_multiple = _this_multiple.data('clone-atts');

                if( _atts_multiple !== 'content' ) {
                  shortcode += instance.validate_atts( _atts_multiple, _this_multiple );
                } else if ( _atts_multiple === 'content' ) {
                  shortcode += ']';
                  shortcode += _this_multiple.val();
                  shortcode += '[/'+_clone_id+'';
                }
              });

              shortcode += ']';

            });

            shortcode += '[/' + sc_name + ']';

          break;

          case 'clone_duplicate':

            $this.find('[' + cloneID + ']').each( function() {

              var _this_clone = $(this),
                  _clone_id   = _this_clone.data('clone-id');

              shortcode += '[' + _clone_id;

              $('[' + cloneAttr + ']', _this_clone.find('.csf-field:not(.hidden)') ).each( function() {

                var _this_multiple = $(this),
                    _atts_multiple = _this_multiple.data('clone-atts');

                if( _atts_multiple !== 'content' ) {
                  shortcode += instance.validate_atts( _atts_multiple, _this_multiple );
                } else if ( _atts_multiple === 'content' ) {
                  shortcode += ']';
                  shortcode += _this_multiple.val();
                  shortcode += '[/'+_clone_id+'';
                }
              });

              shortcode += ']';

            });

          break;

          default:

            shortcode += '[' + sc_name;

            $('[' + ruleAttr + ']', $this.find('.csf-field:not(.hidden)') ).each( function() {

              var _this = $(this), _atts = _this.data('atts');

              if( _atts !== 'content' ) {
                shortcode += instance.validate_atts( _atts, _this );
              } else if ( _atts === 'content' ) {
                shortcode += ']';
                shortcode += _this.val();
                shortcode += '[/'+sc_name+'';
              }

            });

            shortcode += ']';

          break;

        }

        if( !editor_id ) {
          var $textarea = $sc_elem.next();
          $textarea.val( instance.insertAtChars( $textarea, shortcode ) ).trigger('change');
        } else {
          instance.send_to_editor( shortcode, editor_id );
        }

        deploy_atts = null;

        $this.hide();
        $body.removeClass('csf-shortcode-scrolling');

      });

      $content.on('click', '.csf-clone-button', function( e ) {

        e.preventDefault();

        var $cloned = sc_clone.csf_clone().addClass('csf-shortcode-cloned');

        $content.find('.csf-clone-button-wrapper').before( $cloned );

        $cloned.find(':input').attr('name', '_nonce_' + $cloned.index());

        $cloned.find('.csf-remove-clone').show().on('click', function( e ) {

          $cloned.remove();
          e.preventDefault();

        });

        // reloadPlugins
        $cloned.csf_reload_script('sub');

      });

      $this.on('click', '.csf-modal-close, .csf-modal-overlay', function() {
        $this.hide();
        $body.removeClass('csf-shortcode-scrolling');
      });

    });
  };

  //
  // Helper Tooltip
  //
  $.fn.csf_tooltip = function() {
    return this.each(function() {

      var $this = $(this),
          $tooltip,
          tooltip_left;

      $this.on({
        mouseenter: function () {

          $tooltip = $( '<div class="csf-tooltip"></div>' ).html( $this.attr( 'data-title' ) ).appendTo('body');

          tooltip_left = ( has_rtl ) ? ( $this.offset().left + 24 ) : ( $this.offset().left - $tooltip.outerWidth() );

          $tooltip.css({
            top: $this.offset().top - ( ( $tooltip.outerHeight() / 2 ) - 12 ),
            left: tooltip_left,
          });

        },
        mouseleave: function () {

          if( $tooltip !== undefined ) {
            $tooltip.remove();
          }

        }

      });

    });
  };

  //
  // Customize Refresh
  //
  $.fn.csf_customizer_refresh = function() {
    return this.each(function() {

      if( wp.customize === undefined ) { return; }

      var $this    = $(this),
          $complex = $this.closest('.csf-customize-complex'),
          $input   = $complex.find(':input'),
          $unique  = $complex.data('unique-id'),
          $option  = $complex.data('option-id'),
          obj      = $input.serializeObjectCSF(),
          data     = ( !$.isEmptyObject(obj) ) ? obj[$unique][$option] : '';

      wp.customize.control( $unique +'['+ $option +']' ).setting.set( data );

    });
  };

  //
  // Customize Listen Form Elements
  //
  $.fn.csf_customizer_listen = function( has_closest ) {
    return this.each(function() {

      if( wp.customize === undefined ) { return; }

      var $this   = ( has_closest ) ? $(this).closest('.csf-customize-complex') : $(this),
          $input  = $this.find(':input'),
          $unique = $this.data('unique-id'),
          $option = $this.data('option-id');

      $input.on('change keyup', function() {

        var obj  = $input.serializeObjectCSF();
        var data = ( !$.isEmptyObject(obj) ) ? obj[$unique][$option] : '';

        wp.customize.control( $unique +'['+ $option +']' ).setting.set( data );

      });

    });
  };

  //
  // Customizer Listener for Reload JS
  //
  $(document).on( 'expanded', '.control-section-csf', function() {

    var $this = $(this);

    if( !$this.data('inited') ) {
      $this.csf_reload_script();
      $this.find('.csf-customize-complex').csf_customizer_listen();
    }

  });

  //
  // Widgets Framework
  //
  $.fn.csf_widgets = function() {
    return this.each(function() {

      var $this   = $(this),
          $widgets = $this.find('.widget-liquid-right .widget');

      $widgets.each( function() {

        var $widget  = $(this),
            $title = $widget.find('.widget-top');

        $title.on('click', function() {
          $widget.csf_reload_script();
        });

      });

    });
  };

  //
  // Widget Listener for Reload JS
  //
  $(document).on('widget-added widget-updated', function( event, $widget ) {
    $widget.csf_reload_script();
  });

  //
  // Reload Widget Plugins
  //
  $.fn.csf_reload_script = function( has_sub ) {
    return this.each(function() {

      var $this = $(this);

      // Avoid for conflicts
      if( !$this.data('inited') ) {

        $this.find('.csf-field-image-selector').not('.csf-no-script').csf_field_image_selector();
        $this.find('.csf-field-image').not('.csf-no-script').csf_field_image();
        $this.find('.csf-field-gallery').not('.csf-no-script').csf_field_gallery();
        $this.find('.csf-field-sorter').not('.csf-no-script').csf_field_sorter();
        $this.find('.csf-field-upload').not('.csf-no-script').csf_field_upload();
        $this.find('.csf-field-color_picker').not('.csf-no-script').csf_field_colorpicker();
        $this.find('.csf-field-icon').not('.csf-no-script').csf_field_icon();
        $this.find('.csf-field-group').not('.csf-no-script').csf_field_group();
        $this.find('.csf-field-repeater').not('.csf-no-script').csf_field_repeater();
        $this.find('.csf-field-ace_editor').not('.csf-no-script').csf_field_ace_editor();
        $this.find('.csf-field-date').not('.csf-no-script').csf_field_datepicker();
        $this.find('.csf-field-tabbed').not('.csf-no-script').csf_field_tabbed();
        $this.find('.csf-field-backup').not('.csf-no-script').csf_field_backup();
        $this.find('.csf-help').not('.csf-no-script').csf_tooltip();
        $this.find('.chosen').not('.csf-no-script').csf_chosen();

        $this.csf_dependency();

        if( has_sub === 'sub' ) {
          $this.csf_dependency('sub');
        }

        $this.data('inited', true);

        $(document).trigger('csf-reload-script', [ $this ]);

      }

    });
  };

  $(document).ready( function() {

    $('.csf-save').csf_save();
    $('.csf-confirm').csf_confirm();
    $('.csf-nav').csf_navigation();
    $('.csf-search').csf_search();
    $('.csf-sticky-header').csf_sticky();
    $('.csf-taxonomy').csf_taxonomy();
    $('.csf-shortcode').csf_shortcode();
    $('.widgets-php').csf_widgets();
    $('.csf-onload').csf_reload_script();

  });

})( jQuery, window, document );
