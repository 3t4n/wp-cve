; (function ($, window, document, undefined) {
  'use strict';

  //
  // Constants
  //
  var SP_WP_TABS = SP_WP_TABS || {};

  SP_WP_TABS.funcs = {};

  SP_WP_TABS.vars = {
    onloaded: false,
    $body: $('body'),
    $window: $(window),
    $document: $(document),
    $form_warning: null,
    is_confirm: false,
    form_modified: false,
    code_themes: [],
    is_rtl: $('body').hasClass('rtl'),
  };

  //
  // Helper Functions
  //
  SP_WP_TABS.helper = {

    //
    // Generate UID
    //
    uid: function (prefix) {
      return (prefix || '') + Math.random().toString(36).substr(2, 9);
    },

    // Quote regular expression characters
    //
    preg_quote: function (str) {
      return (str + '').replace(/(\[|\-|\])/g, "\\$1");
    },

    //
    // Reneme input names
    //
    name_nested_replace: function ($selector, field_id) {

      var checks = [];
      var regex = new RegExp('(' + SP_WP_TABS.helper.preg_quote(field_id) + ')\\[(\\d+)\\]', 'g');

      $selector.find(':radio').each(function () {
        if (this.checked || this.orginal_checked) {
          this.orginal_checked = true;
        }
      });

      $selector.each(function (index) {
        $(this).find(':input').each(function () {
          this.name = this.name.replace(regex, field_id + '[' + index + ']');
          if (this.orginal_checked) {
            this.checked = true;
          }
        });
      });

    },

    //
    // Debounce
    //
    debounce: function (callback, threshold, immediate) {
      var timeout;
      return function () {
        var context = this, args = arguments;
        var later = function () {
          timeout = null;
          if (!immediate) {
            callback.apply(context, args);
          }
        };
        var callNow = (immediate && !timeout);
        clearTimeout(timeout);
        timeout = setTimeout(later, threshold);
        if (callNow) {
          callback.apply(context, args);
        }
      };
    },

    //
    // Get a cookie
    //
    get_cookie: function (name) {

      var e, b, cookie = document.cookie, p = name + '=';

      if (!cookie) {
        return;
      }

      b = cookie.indexOf('; ' + p);

      if (b === -1) {
        b = cookie.indexOf(p);

        if (b !== 0) {
          return null;
        }
      } else {
        b += 2;
      }

      e = cookie.indexOf(';', b);

      if (e === -1) {
        e = cookie.length;
      }

      return decodeURIComponent(cookie.substring(b + p.length, e));

    },

    //
    // Set a cookie
    //
    set_cookie: function (name, value, expires, path, domain, secure) {

      var d = new Date();

      if (typeof (expires) === 'object' && expires.toGMTString) {
        expires = expires.toGMTString();
      } else if (parseInt(expires, 10)) {
        d.setTime(d.getTime() + (parseInt(expires, 10) * 1000));
        expires = d.toGMTString();
      } else {
        expires = '';
      }

      document.cookie = name + '=' + encodeURIComponent(value) +
        (expires ? '; expires=' + expires : '') +
        (path ? '; path=' + path : '') +
        (domain ? '; domain=' + domain : '') +
        (secure ? '; secure' : '');

    },

    //
    // Remove a cookie
    //
    remove_cookie: function (name, path, domain, secure) {
      SP_WP_TABS.helper.set_cookie(name, '', -1000, path, domain, secure);
    },

  };

  //
  // Custom clone for textarea and select clone() bug
  //
  $.fn.wptabspro_clone = function () {

    var base = $.fn.clone.apply(this, arguments),
      clone = this.find('select').add(this.filter('select')),
      cloned = base.find('select').add(base.filter('select'));

    for (var i = 0; i < clone.length; ++i) {
      for (var j = 0; j < clone[i].options.length; ++j) {

        if (clone[i].options[j].selected === true) {
          cloned[i].options[j].selected = true;
        }

      }
    }

    this.find(':radio').each(function () {
      this.orginal_checked = this.checked;
    });

    return base;

  };

  //
  // Expand All Options
  //
  $.fn.wptabspro_expand_all = function () {
    return this.each(function () {
      $(this).on('click', function (e) {

        e.preventDefault();
        $('.wptabspro-wrapper').toggleClass('wptabspro-show-all');
        $('.wptabspro-section').wptabspro_reload_script();
        $(this).find('.fa').toggleClass('fa-indent').toggleClass('fa-outdent');

      });
    });
  };

  //
  // Options Navigation
  //
  $.fn.wptabspro_nav_options = function () {
    return this.each(function () {

      var $nav = $(this),
        $links = $nav.find('a'),
        $hidden = $nav.closest('.wptabspro').find('.wptabspro-section-id'),
        $last_section;

      $(window).on('hashchange wptabspro.hashchange', function () {

        var hash = window.location.hash.match(new RegExp('tab=([^&]*)'));
        var slug = hash ? hash[1] : $links.first().attr('href').replace('#tab=', '');
        var $link = $('#wptabspro-tab-link-' + slug);

        if ($link.length > 0) {

          $link.closest('.wptabspro-tab-depth-0').addClass('wptabspro-tab-active').siblings().removeClass('wptabspro-tab-active');
          $links.removeClass('wptabspro-section-active');
          $link.addClass('wptabspro-section-active');

          if ($last_section !== undefined) {
            $last_section.hide();
          }

          var $section = $('#wptabspro-section-' + slug);
          $section.show();
          $section.wptabspro_reload_script();

          $hidden.val(slug);

          $last_section = $section;

        }

      }).trigger('wptabspro.hashchange');

    });
  };

  //
  // Metabox Tabs
  //
  $.fn.wptabspro_nav_metabox = function () {
    return this.each(function () {

      var $nav = $(this),
        $links = $nav.find('a'),
        unique_id = $nav.data('unique'),
        post_id = $('#post_ID').val() || 'global',
        $last_section,
        $last_link;

      $links.on('click', function (e) {

        e.preventDefault();

        var $link = $(this),
          section_id = $link.data('section');

        if ($last_link !== undefined) {
          $last_link.removeClass('wptabspro-section-active');
        }

        if ($last_section !== undefined) {
          $last_section.hide();
        }

        $link.addClass('wptabspro-section-active');

        var $section = $('#wptabspro-section-' + section_id);
        $section.show();
        $section.wptabspro_reload_script();

        SP_WP_TABS.helper.set_cookie('wptabspro-last-metabox-tab-' + post_id + '-' + unique_id, section_id);

        $last_section = $section;
        $last_link = $link;

      });

      var get_cookie = SP_WP_TABS.helper.get_cookie('wptabspro-last-metabox-tab-' + post_id + '-' + unique_id);

      if (get_cookie) {
        $nav.find('a[data-section="' + get_cookie + '"]').trigger('click');
      } else {
        $links.first('a').trigger('click');
      }

    });
  };

  //
  // Metabox Page Templates Listener
  //
  $.fn.wptabspro_page_templates = function () {
    if (this.length) {

      $(document).on('change', '.editor-page-attributes__template select, #page_template', function () {

        var maybe_value = $(this).val() || 'default';

        $('.wptabspro-page-templates').removeClass('wptabspro-show').addClass('wptabspro-hide');
        $('.wptabspro-page-' + maybe_value.toLowerCase().replace(/[^a-zA-Z0-9]+/g, '-')).removeClass('wptabspro-hide').addClass('wptabspro-show');

      });

    }
  };

  //
  // Metabox Post Formats Listener
  //
  $.fn.wptabspro_post_formats = function () {
    if (this.length) {

      $(document).on('change', '.editor-post-format select, #formatdiv input[name="post_format"]', function () {

        var maybe_value = $(this).val() || 'default';

        // Fallback for classic editor version
        maybe_value = (maybe_value === '0') ? 'default' : maybe_value;

        $('.wptabspro-post-formats').removeClass('wptabspro-show').addClass('wptabspro-hide');
        $('.wptabspro-post-format-' + maybe_value).removeClass('wptabspro-hide').addClass('wptabspro-show');

      });

    }
  };

  //
  // Search
  //
  $.fn.wptabspro_search = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('input');

      $input.on('change keyup', function () {

        var value = $(this).val(),
          $wrapper = $('.wptabspro-wrapper'),
          $section = $wrapper.find('.wptabspro-section'),
          $fields = $section.find('> .wptabspro-field:not(.hidden)'),
          $titles = $fields.find('> .wptabspro-title, .wptabspro-search-tags');

        if (value.length > 2) {

          $fields.addClass('wptabspro-hidden');
          $wrapper.addClass('wptabspro-search-all');

          $titles.each(function () {

            var $title = $(this);

            if ($title.text().match(new RegExp('.*?' + value + '.*?', 'i'))) {

              var $field = $title.closest('.wptabspro-field');

              $field.removeClass('wptabspro-hidden');
              $field.parent().wptabspro_reload_script();

            }

          });

        } else {

          $fields.removeClass('wptabspro-hidden');
          $wrapper.removeClass('wptabspro-search-all');

        }

      });

    });
  };

  //
  // Sticky Header
  //
  $.fn.wptabspro_sticky = function () {
    return this.each(function () {

      var $this = $(this),
        $window = $(window),
        $inner = $this.find('.wptabspro-header-inner'),
        padding = parseInt($inner.css('padding-left')) + parseInt($inner.css('padding-right')),
        offset = 32,
        scrollTop = 0,
        lastTop = 0,
        ticking = false,
        stickyUpdate = function () {

          var offsetTop = $this.offset().top,
            stickyTop = Math.max(offset, offsetTop - scrollTop),
            winWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

          if (stickyTop <= offset && winWidth > 782) {
            $inner.css({ width: $this.outerWidth() - padding });
            $this.css({ height: $this.outerHeight() }).addClass('wptabspro-sticky');
          } else {
            $inner.removeAttr('style');
            $this.removeAttr('style').removeClass('wptabspro-sticky');
          }

        },
        requestTick = function () {

          if (!ticking) {
            requestAnimationFrame(function () {
              stickyUpdate();
              ticking = false;
            });
          }

          ticking = true;

        },
        onSticky = function () {

          scrollTop = $window.scrollTop();
          requestTick();

        };

      $window.on('scroll resize', onSticky);

      onSticky();

    });
  };

  //
  // Dependency System
  //
  $.fn.wptabspro_dependency = function () {
    return this.each(function () {

      var $this = $(this),
        ruleset = $.wptabspro_deps.createRuleset(),
        depends = [],
        is_global = false;

      $this.children('[data-controller]').each(function () {

        var $field = $(this),
          controllers = $field.data('controller').split('|'),
          conditions = $field.data('condition').split('|'),
          values = $field.data('value').toString().split('|'),
          rules = ruleset;

        if ($field.data('depend-global')) {
          is_global = true;
        }

        $.each(controllers, function (index, depend_id) {

          var value = values[index] || '',
            condition = conditions[index] || conditions[0];

          rules = rules.createRule('[data-depend-id="' + depend_id + '"]', condition, value);

          rules.include($field);

          depends.push(depend_id);

        });

      });

      if (depends.length) {

        if (is_global) {
          $.wptabspro_deps.enable(SP_WP_TABS.vars.$body, ruleset, depends);
        } else {
          $.wptabspro_deps.enable($this, ruleset, depends);
        }

      }

    });
  };

  //
  // Field: accordion
  //
  $.fn.wptabspro_field_accordion = function () {
    return this.each(function () {

      var $titles = $(this).find('.wptabspro-accordion-title');

      $titles.on('click', function () {

        var $title = $(this),
          $icon = $title.find('.wptabspro-accordion-icon'),
          $content = $title.next();

        if ($icon.hasClass('fa-angle-right')) {
          $icon.removeClass('fa-angle-right').addClass('fa-angle-down');
        } else {
          $icon.removeClass('fa-angle-down').addClass('fa-angle-right');
        }

        if (!$content.data('opened')) {

          $content.wptabspro_reload_script();
          $content.data('opened', true);

        }

        $content.toggleClass('wptabspro-accordion-open');

      });

    });
  };

  //
  // Field: backup
  //
  $.fn.wptabspro_field_backup = function () {
    return this.each(function () {

      if (window.wp.customize === undefined) { return; }

      var base = this,
        $this = $(this),
        $body = $('body'),
        $import = $this.find('.wptabspro-import'),
        $reset = $this.find('.wptabspro-reset');

      base.notification = function (message_text) {

        if (wp.customize.notifications && wp.customize.OverlayNotification) {

          // clear if there is any saved data.
          if (!wp.customize.state('saved').get()) {
            wp.customize.state('changesetStatus').set('trash');
            wp.customize.each(function (setting) { setting._dirty = false; });
            wp.customize.state('saved').set(true);
          }

          // then show a notification overlay
          wp.customize.notifications.add(new wp.customize.OverlayNotification('wptabspro_field_backup_notification', {
            type: 'info',
            message: message_text,
            loading: true
          }));

        }

      };

      $reset.on('click', function (e) {

        e.preventDefault();

        if (SP_WP_TABS.vars.is_confirm) {

          base.notification(window.wptabspro_vars.i18n.reset_notification);

          window.wp.ajax.post('wptabspro-reset', {
            unique: $reset.data('unique'),
            nonce: $reset.data('nonce')
          })
            .done(function (response) {
              window.location.reload(true);
            })
            .fail(function (response) {
              alert(response.error);
              wp.customize.notifications.remove('wptabspro_field_backup_notification');
            });

        }

      });

      $import.on('click', function (e) {

        e.preventDefault();

        if (SP_WP_TABS.vars.is_confirm) {

          base.notification(window.wptabspro_vars.i18n.import_notification);

          window.wp.ajax.post('wptabspro-import', {
            unique: $import.data('unique'),
            nonce: $import.data('nonce'),
            data: $this.find('.wptabspro-import-data').val()
          }).done(function (response) {
            window.location.reload(true);
          }).fail(function (response) {
            alert(response.error);
            wp.customize.notifications.remove('wptabspro_field_backup_notification');
          });

        }

      });

    });
  };

  //
  // Field: background
  //
  $.fn.wptabspro_field_background = function () {
    return this.each(function () {
      $(this).find('.wptabspro--background-image').wptabspro_reload_script();
    });
  };

  //
  // Field: code_editor
  //
  $.fn.wptabspro_field_code_editor = function () {
    return this.each(function () {

      if (typeof CodeMirror !== 'function') { return; }

      var $this = $(this),
        $textarea = $this.find('textarea'),
        $inited = $this.find('.CodeMirror'),
        data_editor = $textarea.data('editor');

      if ($inited.length) {
        $inited.remove();
      }

      var interval = setInterval(function () {
        if ($this.is(':visible')) {

          var code_editor = CodeMirror.fromTextArea($textarea[0], data_editor);

          // load code-mirror theme css.
          if (data_editor.theme !== 'default' && SP_WP_TABS.vars.code_themes.indexOf(data_editor.theme) === -1) {

            var $cssLink = $('<link>');

            $('#wptabspro-codemirror-css').after($cssLink);

            $cssLink.attr({
              rel: 'stylesheet',
              id: 'wptabspro-codemirror-' + data_editor.theme + '-css',
              href: data_editor.cdnURL + '/theme/' + data_editor.theme + '.min.css',
              type: 'text/css',
              media: 'all'
            });

            SP_WP_TABS.vars.code_themes.push(data_editor.theme);

          }

          CodeMirror.modeURL = data_editor.cdnURL + '/mode/%N/%N.min.js';
          CodeMirror.autoLoadMode(code_editor, data_editor.mode);

          code_editor.on('change', function (editor, event) {
            $textarea.val(code_editor.getValue()).trigger('change');
          });

          clearInterval(interval);

        }
      });

    });
  };

  //
  // Field: fieldset
  //
  $.fn.wptabspro_field_fieldset = function () {
    return this.each(function () {
      $(this).find('.wptabspro-fieldset-content').wptabspro_reload_script();
    });
  };

  //
  // Field: group
  //
  $.fn.wptabspro_field_group = function () {
    return this.each(function () {

      var $this = $(this),
        $fieldset = $this.children('.wptabspro-fieldset'),
        $group = $fieldset.length ? $fieldset : $this,
        $wrapper = $group.children('.wptabspro-cloneable-wrapper'),
        $hidden = $group.children('.wptabspro-cloneable-hidden'),
        $max = $group.children('.wptabspro-cloneable-max'),
        $min = $group.children('.wptabspro-cloneable-min'),
        field_id = $wrapper.data('field-id'),
        unique_id = $wrapper.data('unique-id'),
        is_number = Boolean(Number($wrapper.data('title-number'))),
        max = parseInt($wrapper.data('max')),
        min = parseInt($wrapper.data('min'));

      // clear accordion arrows if multi-instance
      if ($wrapper.hasClass('ui-accordion')) {
        $wrapper.find('.ui-accordion-header-icon').remove();
      }

      var update_title_numbers = function ($selector) {
        $selector.find('.wptabspro-cloneable-title-number').each(function (index) {
          $(this).html(($(this).closest('.wptabspro-cloneable-item').index() + 1) + '.');
        });
      };

      $wrapper.accordion({
        header: '> .wptabspro-cloneable-item > .wptabspro-cloneable-title',
        collapsible: true,
        active: false,
        animate: false,
        heightStyle: 'content',
        icons: {
          'header': 'wptabspro-cloneable-header-icon fa fa-angle-right',
          'activeHeader': 'wptabspro-cloneable-header-icon fa fa-angle-down'
        },
        activate: function (event, ui) {

          var $panel = ui.newPanel;
          var $header = ui.newHeader;

          if ($panel.length && !$panel.data('opened')) {

            var $fields = $panel.children();
            var $first = $fields.first().find(':input').first();
            var $title = $header.find('.wptabspro-cloneable-value');

            $first.on('change keyup', function (event) {
              $title.text($first.val());
            });

            $panel.wptabspro_reload_script();
            $panel.data('opened', true);
            $panel.data('retry', false);

          } else if ($panel.data('retry')) {

            $panel.wptabspro_reload_script_retry();
            $panel.data('retry', false);

          }

        }
      });

      $wrapper.sortable({
        axis: 'y',
        handle: '.wptabspro-cloneable-title,.wptabspro-cloneable-sort',
        helper: 'original',
        cursor: 'move',
        placeholder: 'widget-placeholder',
        start: function (event, ui) {

          $wrapper.accordion({ active: false });
          $wrapper.sortable('refreshPositions');
          ui.item.children('.wptabspro-cloneable-content').data('retry', true);

        },
        update: function (event, ui) {

          SP_WP_TABS.helper.name_nested_replace($wrapper.children('.wptabspro-cloneable-item'), field_id);
          $wrapper.wptabspro_customizer_refresh();

          if (is_number) {
            update_title_numbers($wrapper);
          }

        },
      });

      $group.children('.wptabspro-cloneable-add').on('click', function (e) {

        e.preventDefault();

        var count = $wrapper.children('.wptabspro-cloneable-item').length;

        $min.hide();

        if (max && (count + 1) > max) {
          $max.show();
          return;
        }

        var new_field_id = unique_id + field_id + '[' + count + ']';

        var $cloned_item = $hidden.wptabspro_clone(true);

        $cloned_item.removeClass('wptabspro-cloneable-hidden');

        $cloned_item.find(':input[name!="_pseudo"]').each(function () {
          this.name = new_field_id + this.name.replace((this.name.startsWith('_nonce') ? '_nonce' : unique_id), '');
        });

        $cloned_item.find('.wptabspro-data-wrapper').each(function () {
          $(this).attr('data-unique-id', new_field_id);
        });

        $wrapper.append($cloned_item);
        $wrapper.accordion('refresh');
        $wrapper.accordion({ active: count });
        $wrapper.wptabspro_customizer_refresh();
        $wrapper.wptabspro_customizer_listen({ closest: true });

        if (is_number) {
          update_title_numbers($wrapper);
        }

      });

      var event_clone = function (e) {

        e.preventDefault();

        var count = $wrapper.children('.wptabspro-cloneable-item').length;

        $min.hide();

        if (max && (count + 1) > max) {
          $max.show();
          return;
        }

        var $this = $(this),
          $parent = $this.parent().parent(),
          $cloned_helper = $parent.children('.wptabspro-cloneable-helper').wptabspro_clone(true),
          $cloned_title = $parent.children('.wptabspro-cloneable-title').wptabspro_clone(),
          $cloned_content = $parent.children('.wptabspro-cloneable-content').wptabspro_clone(),
          cloned_regex = new RegExp('(' + SP_WP_TABS.helper.preg_quote(field_id) + ')\\[(\\d+)\\]', 'g');

        $cloned_content.find('.wptabspro-data-wrapper').each(function () {
          var $this = $(this);
          $this.attr('data-unique-id', $this.attr('data-unique-id').replace(cloned_regex, field_id + '[' + ($parent.index() + 1) + ']'));
        });

        var $cloned = $('<div class="wptabspro-cloneable-item" />');

        $cloned.append($cloned_helper);
        $cloned.append($cloned_title);
        $cloned.append($cloned_content);

        $wrapper.children().eq($parent.index()).after($cloned);

        SP_WP_TABS.helper.name_nested_replace($wrapper.children('.wptabspro-cloneable-item'), field_id);

        $wrapper.accordion('refresh');
        $wrapper.wptabspro_customizer_refresh();
        $wrapper.wptabspro_customizer_listen({ closest: true });

        if (is_number) {
          update_title_numbers($wrapper);
        }

      };

      $wrapper.children('.wptabspro-cloneable-item').children('.wptabspro-cloneable-helper').on('click', '.wptabspro-cloneable-clone', event_clone);
      $group.children('.wptabspro-cloneable-hidden').children('.wptabspro-cloneable-helper').on('click', '.wptabspro-cloneable-clone', event_clone);

      var event_remove = function (e) {

        e.preventDefault();

        var count = $wrapper.children('.wptabspro-cloneable-item').length;

        $max.hide();
        $min.hide();

        if (min && (count - 1) < min) {
          $min.show();
          return;
        }

        $(this).closest('.wptabspro-cloneable-item').remove();

        SP_WP_TABS.helper.name_nested_replace($wrapper.children('.wptabspro-cloneable-item'), field_id);

        $wrapper.wptabspro_customizer_refresh();

        if (is_number) {
          update_title_numbers($wrapper);
        }

      };

      $wrapper.children('.wptabspro-cloneable-item').children('.wptabspro-cloneable-helper').on('click', '.wptabspro-cloneable-remove', event_remove);
      $group.children('.wptabspro-cloneable-hidden').children('.wptabspro-cloneable-helper').on('click', '.wptabspro-cloneable-remove', event_remove);

    });
  };

  //
  // Field: repeater
  //
  $.fn.wptabspro_field_repeater = function () {
    return this.each(function () {

      var $this = $(this),
        $fieldset = $this.children('.wptabspro-fieldset'),
        $repeater = $fieldset.length ? $fieldset : $this,
        $wrapper = $repeater.children('.wptabspro-repeater-wrapper'),
        $hidden = $repeater.children('.wptabspro-repeater-hidden'),
        $max = $repeater.children('.wptabspro-repeater-max'),
        $min = $repeater.children('.wptabspro-repeater-min'),
        field_id = $wrapper.data('field-id'),
        unique_id = $wrapper.data('unique-id'),
        max = parseInt($wrapper.data('max')),
        min = parseInt($wrapper.data('min'));


      $wrapper.children('.wptabspro-repeater-item').children('.wptabspro-repeater-content').wptabspro_reload_script();

      $wrapper.sortable({
        axis: 'y',
        handle: '.wptabspro-repeater-sort',
        helper: 'original',
        cursor: 'move',
        placeholder: 'widget-placeholder',
        update: function (event, ui) {

          SP_WP_TABS.helper.name_nested_replace($wrapper.children('.wptabspro-repeater-item'), field_id);
          $wrapper.wptabspro_customizer_refresh();
          ui.item.wptabspro_reload_script_retry();

        }
      });

      $repeater.children('.wptabspro-repeater-add').on('click', function (e) {

        e.preventDefault();

        var count = $wrapper.children('.wptabspro-repeater-item').length;

        $min.hide();

        if (max && (count + 1) > max) {
          $max.show();
          return;
        }

        var new_field_id = unique_id + field_id + '[' + count + ']';

        var $cloned_item = $hidden.wptabspro_clone(true);

        $cloned_item.removeClass('wptabspro-repeater-hidden');

        $cloned_item.find(':input[name!="_pseudo"]').each(function () {
          this.name = new_field_id + this.name.replace((this.name.startsWith('_nonce') ? '_nonce' : unique_id), '');
        });

        $cloned_item.find('.wptabspro-data-wrapper').each(function () {
          $(this).attr('data-unique-id', new_field_id);
        });

        $wrapper.append($cloned_item);
        $cloned_item.children('.wptabspro-repeater-content').wptabspro_reload_script();
        $wrapper.wptabspro_customizer_refresh();
        $wrapper.wptabspro_customizer_listen({ closest: true });

      });

      var event_clone = function (e) {

        e.preventDefault();

        var count = $wrapper.children('.wptabspro-repeater-item').length;

        $min.hide();

        if (max && (count + 1) > max) {
          $max.show();
          return;
        }

        var $this = $(this),
          $parent = $this.parent().parent().parent(),
          $cloned_content = $parent.children('.wptabspro-repeater-content').wptabspro_clone(),
          $cloned_helper = $parent.children('.wptabspro-repeater-helper').wptabspro_clone(true),
          cloned_regex = new RegExp('(' + SP_WP_TABS.helper.preg_quote(field_id) + ')\\[(\\d+)\\]', 'g');

        $cloned_content.find('.wptabspro-data-wrapper').each(function () {
          var $this = $(this);
          $this.attr('data-unique-id', $this.attr('data-unique-id').replace(cloned_regex, field_id + '[' + ($parent.index() + 1) + ']'));
        });

        var $cloned = $('<div class="wptabspro-repeater-item" />');

        $cloned.append($cloned_content);
        $cloned.append($cloned_helper);

        $wrapper.children().eq($parent.index()).after($cloned);

        $cloned.children('.wptabspro-repeater-content').wptabspro_reload_script();

        SP_WP_TABS.helper.name_nested_replace($wrapper.children('.wptabspro-repeater-item'), field_id);

        $wrapper.wptabspro_customizer_refresh();
        $wrapper.wptabspro_customizer_listen({ closest: true });

      };

      $wrapper.children('.wptabspro-repeater-item').children('.wptabspro-repeater-helper').on('click', '.wptabspro-repeater-clone', event_clone);
      $repeater.children('.wptabspro-repeater-hidden').children('.wptabspro-repeater-helper').on('click', '.wptabspro-repeater-clone', event_clone);

      var event_remove = function (e) {

        e.preventDefault();

        var count = $wrapper.children('.wptabspro-repeater-item').length;

        $max.hide();
        $min.hide();

        if (min && (count - 1) < min) {
          $min.show();
          return;
        }

        $(this).closest('.wptabspro-repeater-item').remove();

        SP_WP_TABS.helper.name_nested_replace($wrapper.children('.wptabspro-repeater-item'), field_id);

        $wrapper.wptabspro_customizer_refresh();

      };

      $wrapper.children('.wptabspro-repeater-item').children('.wptabspro-repeater-helper').on('click', '.wptabspro-repeater-remove', event_remove);
      $repeater.children('.wptabspro-repeater-hidden').children('.wptabspro-repeater-helper').on('click', '.wptabspro-repeater-remove', event_remove);

    });
  };

  //
  // Field: slider
  //
  $.fn.wptabspro_field_slider = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('input'),
        $slider = $this.find('.wptabspro-slider-ui'),
        data = $input.data(),
        value = $input.val() || 0;

      if ($slider.hasClass('ui-slider')) {
        $slider.empty();
      }

      $slider.slider({
        range: 'min',
        value: value,
        min: data.min,
        max: data.max,
        step: data.step,
        slide: function (e, o) {
          $input.val(o.value).trigger('change');
        }
      });

      $input.on('keyup', function () {
        $slider.slider('value', $input.val());
      });

    });
  };

  //
  // Field: sortable
  //
  $.fn.wptabspro_field_sortable = function () {
    return this.each(function () {

      var $sortable = $(this).find('.wptabspro--sortable');

      $sortable.sortable({
        axis: 'y',
        helper: 'original',
        cursor: 'move',
        placeholder: 'widget-placeholder',
        update: function (event, ui) {
          $sortable.wptabspro_customizer_refresh();
        }
      });

      $sortable.find('.wptabspro--sortable-content').wptabspro_reload_script();

    });
  };

  //
  // Field: sorter
  //
  $.fn.wptabspro_field_sorter = function () {
    return this.each(function () {

      var $this = $(this),
        $enabled = $this.find('.wptabspro-enabled'),
        $has_disabled = $this.find('.wptabspro-disabled'),
        $disabled = ($has_disabled.length) ? $has_disabled : false;

      $enabled.sortable({
        connectWith: $disabled,
        placeholder: 'ui-sortable-placeholder',
        update: function (event, ui) {

          var $el = ui.item.find('input');

          if (ui.item.parent().hasClass('wptabspro-enabled')) {
            $el.attr('name', $el.attr('name').replace('disabled', 'enabled'));
          } else {
            $el.attr('name', $el.attr('name').replace('enabled', 'disabled'));
          }

          $this.wptabspro_customizer_refresh();

        }
      });

      if ($disabled) {

        $disabled.sortable({
          connectWith: $enabled,
          placeholder: 'ui-sortable-placeholder',
          update: function (event, ui) {
            $this.wptabspro_customizer_refresh();
          }
        });

      }

    });
  };

  //
  // Field: spinner
  //
  $.fn.wptabspro_field_spinner = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('input'),
        $inited = $this.find('.ui-spinner-button'),
        $unit = $input.data('unit');

      if ($inited.length) {
        $inited.remove();
      }

      $input.spinner({
        max: $input.data('max') || 100,
        min: $input.data('min') || 0,
        step: $input.data('step') || 1,
        create: function (event, ui) {
          if ($unit.length) {
            $this.find('.ui-spinner-up').after('<span class="ui-button-text-only wptabspro--unit">' + $unit + '</span>');
          }
        },
        spin: function (event, ui) {
          $input.val(ui.value).trigger('change');
        }
      });

    });
  };

  //
  // Field: switcher
  //
  $.fn.wptabspro_field_switcher = function () {
    return this.each(function () {

      var $switcher = $(this).find('.wptabspro--switcher');

      $switcher.on('click', function () {

        var value = 0;
        var $input = $switcher.find('input');

        if ($switcher.hasClass('wptabspro--active')) {
          $switcher.removeClass('wptabspro--active');
        } else {
          value = 1;
          $switcher.addClass('wptabspro--active');
        }

        $input.val(value).trigger('change');

      });

    });
  };

  //
  // Field: typography
  //
  $.fn.wptabspro_field_typography = function () {
    return this.each(function () {

      var base = this;
      var $this = $(this);
      var loaded_fonts = [];
      var webfonts = wptabspro_typography_json.webfonts;
      var googlestyles = wptabspro_typography_json.googlestyles;
      var defaultstyles = wptabspro_typography_json.defaultstyles;

      //
      //
      // Sanitize google font subset
      base.sanitize_subset = function (subset) {
        subset = subset.replace('-ext', ' Extended');
        subset = subset.charAt(0).toUpperCase() + subset.slice(1);
        return subset;
      };

      //
      //
      // Sanitize google font styles (weight and style)
      base.sanitize_style = function (style) {
        return googlestyles[style] ? googlestyles[style] : style;
      };

      //
      //
      // Load google font
      base.load_google_font = function (font_family, weight, style) {

        if (font_family && typeof WebFont === 'object') {

          weight = weight ? weight.replace('normal', '') : '';
          style = style ? style.replace('normal', '') : '';

          if (weight || style) {
            font_family = font_family + ':' + weight + style;
          }

          if (loaded_fonts.indexOf(font_family) === -1) {
            WebFont.load({ google: { families: [font_family] } });
          }

          loaded_fonts.push(font_family);

        }

      };

      //
      //
      // Append select options
      base.append_select_options = function ($select, options, condition, type, is_multi) {

        $select.find('option').not(':first').remove();

        var opts = '';

        $.each(options, function (key, value) {

          var selected;
          var name = value;

          // is_multi
          if (is_multi) {
            selected = (condition && condition.indexOf(value) !== -1) ? ' selected' : '';
          } else {
            selected = (condition && condition === value) ? ' selected' : '';
          }

          if (type === 'subset') {
            name = base.sanitize_subset(value);
          } else if (type === 'style') {
            name = base.sanitize_style(value);
          }

          opts += '<option value="' + value + '"' + selected + '>' + name + '</option>';

        });

        $select.append(opts).trigger('wptabspro.change').trigger('chosen:updated');

      };

      base.init = function () {

        //
        //
        // Constants
        var selected_styles = [];
        var $typography = $this.find('.wptabspro--typography');
        var $type = $this.find('.wptabspro--type');
        var $styles = $this.find('.wptabspro--block-font-style');
        var unit = $typography.data('unit');
        var line_height_unit = $typography.data('line-height-unit');
        var exclude_fonts = $typography.data('exclude') ? $typography.data('exclude').split(',') : [];

        //
        //
        // Chosen init
        if ($this.find('.wptabspro--chosen').length) {

          var $chosen_selects = $this.find('select');

          $chosen_selects.each(function () {

            var $chosen_select = $(this),
              $chosen_inited = $chosen_select.parent().find('.chosen-container');

            if ($chosen_inited.length) {
              $chosen_inited.remove();
            }

            $chosen_select.chosen({
              allow_single_deselect: true,
              disable_search_threshold: 15,
              width: '100%'
            });

          });

        }

        //
        //
        // Font family select
        var $font_family_select = $this.find('.wptabspro--font-family');
        var first_font_family = $font_family_select.val();

        // Clear default font family select options
        $font_family_select.find('option').not(':first-child').remove();

        var opts = '';

        $.each(webfonts, function (type, group) {

          // Check for exclude fonts
          if (exclude_fonts && exclude_fonts.indexOf(type) !== -1) { return; }

          opts += '<optgroup label="' + group.label + '">';

          $.each(group.fonts, function (key, value) {

            // use key if value is object
            value = (typeof value === 'object') ? key : value;
            var selected = (value === first_font_family) ? ' selected' : '';
            opts += '<option value="' + value + '" data-type="' + type + '"' + selected + '>' + value + '</option>';

          });

          opts += '</optgroup>';

        });

        // Append google font select options
        $font_family_select.append(opts).trigger('chosen:updated');

        //
        //
        // Font style select
        var $font_style_block = $this.find('.wptabspro--block-font-style');

        if ($font_style_block.length) {

          var $font_style_select = $this.find('.wptabspro--font-style-select');
          var first_style_value = $font_style_select.val() ? $font_style_select.val().replace(/normal/g, '') : '';

          //
          // Font Style on on change listener
          $font_style_select.on('change wptabspro.change', function (event) {

            var style_value = $font_style_select.val();

            // set a default value
            if (!style_value && selected_styles && selected_styles.indexOf('normal') === -1) {
              style_value = selected_styles[0];
            }

            // set font weight, for eg. replacing 800italic to 800
            var font_normal = (style_value && style_value !== 'italic' && style_value === 'normal') ? 'normal' : '';
            var font_weight = (style_value && style_value !== 'italic' && style_value !== 'normal') ? style_value.replace('italic', '') : font_normal;
            var font_style = (style_value && style_value.substr(-6) === 'italic') ? 'italic' : '';

            $this.find('.wptabspro--font-weight').val(font_weight);
            $this.find('.wptabspro--font-style').val(font_style);

          });

          //
          //
          // Extra font style select
          var $extra_font_style_block = $this.find('.wptabspro--block-extra-styles');

          if ($extra_font_style_block.length) {
            var $extra_font_style_select = $this.find('.wptabspro--extra-styles');
            var first_extra_style_value = $extra_font_style_select.val();
          }

        }

        //
        //
        // Subsets select
        var $subset_block = $this.find('.wptabspro--block-subset');
        if ($subset_block.length) {
          var $subset_select = $this.find('.wptabspro--subset');
          var first_subset_select_value = $subset_select.val();
          var subset_multi_select = $subset_select.data('multiple') || false;
        }

        //
        //
        // Backup font family
        var $backup_font_family_block = $this.find('.wptabspro--block-backup-font-family');

        //
        //
        // Font Family on Change Listener
        $font_family_select.on('change wptabspro.change', function (event) {

          // Hide subsets on change
          if ($subset_block.length) {
            $subset_block.addClass('hidden');
          }

          // Hide extra font style on change
          if ($extra_font_style_block.length) {
            $extra_font_style_block.addClass('hidden');
          }

          // Hide backup font family on change
          if ($backup_font_family_block.length) {
            $backup_font_family_block.addClass('hidden');
          }

          var $selected = $font_family_select.find(':selected');
          var value = $selected.val();
          var type = $selected.data('type');

          if (type && value) {

            // Show backup fonts if font type google or custom
            if ((type === 'google' || type === 'custom') && $backup_font_family_block.length) {
              $backup_font_family_block.removeClass('hidden');
            }

            // Appending font style select options
            if ($font_style_block.length) {

              // set styles for multi and normal style selectors
              var styles = defaultstyles;

              // Custom or gogle font styles
              if (type === 'google' && webfonts[type].fonts[value][0]) {
                styles = webfonts[type].fonts[value][0];
              } else if (type === 'custom' && webfonts[type].fonts[value]) {
                styles = webfonts[type].fonts[value];
              }

              selected_styles = styles;

              // Set selected style value for avoid load errors
              var set_auto_style = (styles.indexOf('normal') !== -1) ? 'normal' : styles[0];
              var set_style_value = (first_style_value && styles.indexOf(first_style_value) !== -1) ? first_style_value : set_auto_style;

              // Append style select options
              base.append_select_options($font_style_select, styles, set_style_value, 'style');

              // Clear first value
              first_style_value = false;

              // Show style select after appended
              $font_style_block.removeClass('hidden');

              // Appending extra font style select options
              if (type === 'google' && $extra_font_style_block.length && styles.length > 1) {

                // Append extra-style select options
                base.append_select_options($extra_font_style_select, styles, first_extra_style_value, 'style', true);

                // Clear first value
                first_extra_style_value = false;

                // Show style select after appended
                $extra_font_style_block.removeClass('hidden');

              }

            }

            // Appending google fonts subsets select options
            if (type === 'google' && $subset_block.length && webfonts[type].fonts[value][1]) {

              var subsets = webfonts[type].fonts[value][1];
              var set_auto_subset = (subsets.length < 2 && subsets[0] !== 'latin') ? subsets[0] : '';
              var set_subset_value = (first_subset_select_value && subsets.indexOf(first_subset_select_value) !== -1) ? first_subset_select_value : set_auto_subset;

              // check for multiple subset select
              set_subset_value = (subset_multi_select && first_subset_select_value) ? first_subset_select_value : set_subset_value;

              base.append_select_options($subset_select, subsets, set_subset_value, 'subset', subset_multi_select);

              first_subset_select_value = false;

              $subset_block.removeClass('hidden');

            }

          } else {

            // Clear Styles
            $styles.find(':input').val('');

            // Clear subsets options if type and value empty
            if ($subset_block.length) {
              $subset_select.find('option').not(':first-child').remove();
              $subset_select.trigger('chosen:updated');
            }

            // Clear font styles options if type and value empty
            if ($font_style_block.length) {
              $font_style_select.find('option').not(':first-child').remove();
              $font_style_select.trigger('chosen:updated');
            }

          }

          // Update font type input value
          $type.val(type);

        }).trigger('wptabspro.change');

        //
        //
        // Preview
        var $preview_block = $this.find('.wptabspro--block-preview');

        if ($preview_block.length) {

          var $preview = $this.find('.wptabspro--preview');

          // Set preview styles on change
          $this.on('change', SP_WP_TABS.helper.debounce(function (event) {

            $preview_block.removeClass('hidden');

            var font_family = $font_family_select.val(),
              font_weight = $this.find('.wptabspro--font-weight').val(),
              font_style = $this.find('.wptabspro--font-style').val(),
              font_size = $this.find('.wptabspro--font-size').val(),
              font_variant = $this.find('.wptabspro--font-variant').val(),
              line_height = $this.find('.wptabspro--line-height').val(),
              text_align = $this.find('.wptabspro--text-align').val(),
              text_transform = $this.find('.wptabspro--text-transform').val(),
              text_decoration = $this.find('.wptabspro--text-decoration').val(),
              text_color = $this.find('.wptabspro--color').val(),
              word_spacing = $this.find('.wptabspro--word-spacing').val(),
              letter_spacing = $this.find('.wptabspro--letter-spacing').val(),
              custom_style = $this.find('.wptabspro--custom-style').val(),
              type = $this.find('.wptabspro--type').val();

            if (type === 'google') {
              base.load_google_font(font_family, font_weight, font_style);
            }

            var properties = {};

            if (font_family) { properties.fontFamily = font_family; }
            if (font_weight) { properties.fontWeight = font_weight; }
            if (font_style) { properties.fontStyle = font_style; }
            if (font_variant) { properties.fontVariant = font_variant; }
            if (font_size) { properties.fontSize = font_size + unit; }
            if (line_height) { properties.lineHeight = line_height + line_height_unit; }
            if (letter_spacing) { properties.letterSpacing = letter_spacing + unit; }
            if (word_spacing) { properties.wordSpacing = word_spacing + unit; }
            if (text_align) { properties.textAlign = text_align; }
            if (text_transform) { properties.textTransform = text_transform; }
            if (text_decoration) { properties.textDecoration = text_decoration; }
            if (text_color) { properties.color = text_color; }

            $preview.removeAttr('style');

            // Customs style attribute
            if (custom_style) { $preview.attr('style', custom_style); }

            $preview.css(properties);

          }, 100));

          // Preview black and white backgrounds trigger
          $preview_block.on('click', function () {

            $preview.toggleClass('wptabspro--black-background');

            var $toggle = $preview_block.find('.wptabspro--toggle');

            if ($toggle.hasClass('fa-toggle-off')) {
              $toggle.removeClass('fa-toggle-off').addClass('fa-toggle-on');
            } else {
              $toggle.removeClass('fa-toggle-on').addClass('fa-toggle-off');
            }

          });

          if (!$preview_block.hasClass('hidden')) {
            $this.trigger('change');
          }

        }

      };

      base.init();

    });
  };

  //
  // Field: upload
  //
  $.fn.wptabspro_field_upload = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('input'),
        $upload_button = $this.find('.wptabspro--button'),
        $remove_button = $this.find('.wptabspro--remove'),
        $library = $upload_button.data('library') && $upload_button.data('library').split(',') || '',
        wp_media_frame;

      $input.on('change', function (e) {
        if ($input.val()) {
          $remove_button.removeClass('hidden');
        } else {
          $remove_button.addClass('hidden');
        }
      });

      $upload_button.on('click', function (e) {

        e.preventDefault();

        if (typeof window.wp === 'undefined' || !window.wp.media || !window.wp.media.gallery) {
          return;
        }

        if (wp_media_frame) {
          wp_media_frame.open();
          return;
        }

        wp_media_frame = window.wp.media({
          library: {
            type: $library
          },
        });

        wp_media_frame.on('select', function () {

          var attributes = wp_media_frame.state().get('selection').first().attributes;

          if ($library.length && $library.indexOf(attributes.subtype) === -1 && $library.indexOf(attributes.type) === -1) {
            return;
          }

          $input.val(attributes.url).trigger('change');

        });

        wp_media_frame.open();

      });

      $remove_button.on('click', function (e) {
        e.preventDefault();
        $input.val('').trigger('change');
      });

    });

  };

  //
  // Field: media
  //
  $.fn.wptabspro_field_media = function () {
    return this.each(function () {

      var $this = $(this),
        $upload_button = $this.find('.wptabspro--button'),
        $remove_button = $this.find('.wptabspro--remove'),
        $library = $upload_button.data('library') && $upload_button.data('library').split(',') || '',
        $auto_attributes = ($this.hasClass('wptabspro-assign-field-background')) ? $this.closest('.wptabspro-field-background').find('.wptabspro--auto-attributes') : false,
        wp_media_frame;

      $upload_button.on('click', function (e) {

        e.preventDefault();

        if (typeof window.wp === 'undefined' || !window.wp.media || !window.wp.media.gallery) {
          return;
        }

        if (wp_media_frame) {
          wp_media_frame.open();
          return;
        }

        wp_media_frame = window.wp.media({
          library: {
            type: $library
          }
        });

        wp_media_frame.on('select', function () {

          var thumbnail;
          var attributes = wp_media_frame.state().get('selection').first().attributes;
          var preview_size = $upload_button.data('preview-size') || 'thumbnail';

          if ($library.length && $library.indexOf(attributes.subtype) === -1 && $library.indexOf(attributes.type) === -1) {
            return;
          }

          $this.find('.wptabspro--id').val(attributes.id);
          $this.find('.wptabspro--width').val(attributes.width);
          $this.find('.wptabspro--height').val(attributes.height);
          $this.find('.wptabspro--alt').val(attributes.alt);
          $this.find('.wptabspro--title').val(attributes.title);
          $this.find('.wptabspro--description').val(attributes.description);

          if (typeof attributes.sizes !== 'undefined' && typeof attributes.sizes.thumbnail !== 'undefined' && preview_size === 'thumbnail') {
            thumbnail = attributes.sizes.thumbnail.url;
          } else if (typeof attributes.sizes !== 'undefined' && typeof attributes.sizes.full !== 'undefined') {
            thumbnail = attributes.sizes.full.url;
          } else {
            thumbnail = attributes.icon;
          }

          if ($auto_attributes) {
            $auto_attributes.removeClass('wptabspro--attributes-hidden');
          }

          $remove_button.removeClass('hidden');

          $this.find('.wptabspro--preview').removeClass('hidden');
          $this.find('.wptabspro--src').attr('src', thumbnail);
          $this.find('.wptabspro--thumbnail').val(thumbnail);
          $this.find('.wptabspro--url').val(attributes.url).trigger('change');

        });

        wp_media_frame.open();

      });

      $remove_button.on('click', function (e) {

        e.preventDefault();

        if ($auto_attributes) {
          $auto_attributes.addClass('wptabspro--attributes-hidden');
        }

        $remove_button.addClass('hidden');
        $this.find('input').val('');
        $this.find('.wptabspro--preview').addClass('hidden');
        $this.find('.wptabspro--url').trigger('change');

      });

    });

  };

  //
  // Field: wp_editor
  //
  $.fn.wptabspro_field_wp_editor = function () {
    return this.each(function () {

      if (typeof window.wp.editor === 'undefined' || typeof window.tinyMCEPreInit === 'undefined' || typeof window.tinyMCEPreInit.mceInit.wptabspro_wp_editor === 'undefined') {
        return;
      }

      var $this = $(this),
        $editor = $this.find('.wptabspro-wp-editor'),
        $textarea = $this.find('textarea');

      // If there is wp-editor remove it for avoid dupliated wp-editor conflicts.
      var $has_wp_editor = $this.find('.wp-editor-wrap').length || $this.find('.mce-container').length;

      if ($has_wp_editor) {
        $editor.empty();
        $editor.append($textarea);
        $textarea.css('display', '');
      }

      // Generate a unique id
      var uid = SP_WP_TABS.helper.uid('wptabspro-editor-');

      $textarea.attr('id', uid);

      // Get default editor settings
      var default_editor_settings = {
        tinymce: window.tinyMCEPreInit.mceInit.wptabspro_wp_editor,
        quicktags: window.tinyMCEPreInit.qtInit.wptabspro_wp_editor
      };

      // Get default editor settings
      var field_editor_settings = $editor.data('editor-settings');

      // Add on change event handle
      var editor_on_change = function (editor) {
        editor.on('change', SP_WP_TABS.helper.debounce(function () {
          editor.save();
          $textarea.trigger('change');
        }, 250));
      };

      // Callback for old wp editor
      var wpEditor = wp.oldEditor ? wp.oldEditor : wp.editor;

      if (wpEditor && wpEditor.hasOwnProperty('autop')) {
        wp.editor.autop = wpEditor.autop;
        wp.editor.removep = wpEditor.removep;
        wp.editor.initialize = wpEditor.initialize;
      }

      // Extend editor selector and on change event handler
      default_editor_settings.tinymce = $.extend({}, default_editor_settings.tinymce, { selector: '#' + uid, setup: editor_on_change });

      // Override editor tinymce settings
      if (field_editor_settings.tinymce === false) {
        default_editor_settings.tinymce = false;
        $editor.addClass('wptabspro-no-tinymce');
      }

      // Override editor quicktags settings
      if (field_editor_settings.quicktags === false) {
        default_editor_settings.quicktags = false;
        $editor.addClass('wptabspro-no-quicktags');
      }

      // Wait until :visible
      var interval = setInterval(function () {
        if ($this.is(':visible')) {
          window.wp.editor.initialize(uid, default_editor_settings);
          clearInterval(interval);
        }
      });

      // Add Media buttons
      if (field_editor_settings.media_buttons && window.wptabspro_media_buttons) {

        var $editor_buttons = $editor.find('.wp-media-buttons');

        if ($editor_buttons.length) {

          $editor_buttons.find('.wptabspro-shortcode-button').data('editor-id', uid);

        } else {

          var $media_buttons = $(window.wptabspro_media_buttons);

          $media_buttons.find('.wptabspro-shortcode-button').data('editor-id', uid);

          $editor.prepend($media_buttons);

        }

      }

    });

  };

  //
  // Confirm
  //
  $.fn.wptabspro_confirm = function () {
    return this.each(function () {
      $(this).on('click', function (e) {

        var confirm_text = $(this).data('confirm') || window.wptabspro_vars.i18n.confirm;
        var confirm_answer = confirm(confirm_text);

        if (confirm_answer) {
          SP_WP_TABS.vars.is_confirm = true;
        } else {
          e.preventDefault();
          return false;
        }

      });
    });
  };

  $.fn.serializeObject = function () {

    var obj = {};

    $.each(this.serializeArray(), function (i, o) {
      var n = o.name,
        v = o.value;

      obj[n] = obj[n] === undefined ? v
        : $.isArray(obj[n]) ? obj[n].concat(v)
          : [obj[n], v];
    });

    return obj;

  };

  //
  // Options Save
  //
  $.fn.wptabspro_save = function () {
    return this.each(function () {

      var $this = $(this),
        $buttons = $('.wptabspro-save'),
        $panel = $('.wptabspro-options'),
        flooding = false,
        timeout;

      $this.on('click', function (e) {

        if (!flooding) {

          var $text = $this.data('save'),
            $value = $this.val();

          $buttons.attr('value', $text);

          if ($this.hasClass('wptabspro-save-ajax')) {

            e.preventDefault();

            $panel.addClass('wptabspro-saving');
            $buttons.prop('disabled', true);

            window.wp.ajax.post('wptabspro_' + $panel.data('unique') + '_ajax_save', {
              data: $('#wptabspro-form').serializeJSONSP_WP_TABS()
            })
              .done(function (response) {

                // clear errors
                $('.wptabspro-error').remove();

                if (Object.keys(response.errors).length) {

                  var error_icon = '<i class="wptabspro-label-error wptabspro-error">!</i>';

                  $.each(response.errors, function (key, error_message) {

                    var $field = $('[data-depend-id="' + key + '"]'),
                      $link = $('#wptabspro-tab-link-' + ($field.closest('.wptabspro-section').index() + 1)),
                      $tab = $link.closest('.wptabspro-tab-depth-0');

                    $field.closest('.wptabspro-fieldset').append('<p class="wptabspro-text-error wptabspro-error">' + error_message + '</p>');

                    if (!$link.find('.wptabspro-error').length) {
                      $link.append(error_icon);
                    }

                    if (!$tab.find('.wptabspro-arrow .wptabspro-error').length) {
                      $tab.find('.wptabspro-arrow').append(error_icon);
                    }

                  });

                }

                $panel.removeClass('wptabspro-saving');
                $buttons.prop('disabled', false).attr('value', $value);
                flooding = false;

                SP_WP_TABS.vars.form_modified = false;
                SP_WP_TABS.vars.$form_warning.hide();

                clearTimeout(timeout);

                var $result_success = $('.wptabspro-form-success');
                $result_success.empty().append(response.notice).fadeIn('fast', function () {
                  timeout = setTimeout(function () {
                    $result_success.fadeOut('fast');
                  }, 1000);
                });

              })
              .fail(function (response) {
                alert(response.error);
              });

          } else {

            SP_WP_TABS.vars.form_modified = false;

          }

        }

        flooding = true;

      });

    });
  };

  //
  // Option Framework
  //
  $.fn.wptabspro_options = function () {
    return this.each(function () {

      var $this = $(this),
        $content = $this.find('.wptabspro-content'),
        $form_success = $this.find('.wptabspro-form-success'),
        $form_warning = $this.find('.wptabspro-form-warning'),
        $save_button = $this.find('.wptabspro-header .wptabspro-save');

      SP_WP_TABS.vars.$form_warning = $form_warning;

      // Shows a message white leaving theme options without saving
      if ($form_warning.length) {

        window.onbeforeunload = function () {
          return (SP_WP_TABS.vars.form_modified && SP_WP_TABS.vars.is_confirm === false) ? true : undefined;
        };

        $content.on('change keypress', ':input', function () {
          if (!SP_WP_TABS.vars.form_modified) {
            $form_success.hide();
            $form_warning.fadeIn('fast');
            SP_WP_TABS.vars.form_modified = true;
          }
        });

      }

      if ($form_success.hasClass('wptabspro-form-show')) {
        setTimeout(function () {
          $form_success.fadeOut('fast');
        }, 1000);
      }

      $(document).on('keydown',function (event) {
        if ((event.ctrlKey || event.metaKey) && event.which === 83) {
          $save_button.trigger('click');
          event.preventDefault();
          return false;
        }
      });

    });
  };

  //
  // Shortcode Framework
  //
  $.fn.wptabspro_shortcode = function () {

    var base = this;

    base.shortcode_parse = function (serialize, key) {

      var shortcode = '';

      $.each(serialize, function (shortcode_key, shortcode_values) {

        key = (key) ? key : shortcode_key;

        shortcode += '[' + key;

        $.each(shortcode_values, function (shortcode_tag, shortcode_value) {

          if (shortcode_tag === 'content') {

            shortcode += ']';
            shortcode += shortcode_value;
            shortcode += '[/' + key + '';

          } else {

            shortcode += base.shortcode_tags(shortcode_tag, shortcode_value);

          }

        });

        shortcode += ']';

      });

      return shortcode;

    };

    base.shortcode_tags = function (shortcode_tag, shortcode_value) {

      var shortcode = '';

      if (shortcode_value !== '') {

        if (typeof shortcode_value === 'object' && !$.isArray(shortcode_value)) {

          $.each(shortcode_value, function (sub_shortcode_tag, sub_shortcode_value) {

            // sanitize spesific key/value
            switch (sub_shortcode_tag) {

              case 'background-image':
                sub_shortcode_value = (sub_shortcode_value.url) ? sub_shortcode_value.url : '';
                break;

            }

            if (sub_shortcode_value !== '') {
              shortcode += ' ' + sub_shortcode_tag.replace('-', '_') + '="' + sub_shortcode_value.toString() + '"';
            }

          });

        } else {

          shortcode += ' ' + shortcode_tag.replace('-', '_') + '="' + shortcode_value.toString() + '"';

        }

      }

      return shortcode;

    };

    base.insertAtChars = function (_this, currentValue) {

      var obj = (typeof _this[0].name !== 'undefined') ? _this[0] : _this;

      if (obj.value.length && typeof obj.selectionStart !== 'undefined') {
        obj.focus();
        return obj.value.substring(0, obj.selectionStart) + currentValue + obj.value.substring(obj.selectionEnd, obj.value.length);
      } else {
        obj.focus();
        return currentValue;
      }

    };

    base.send_to_editor = function (html, editor_id) {

      var tinymce_editor;

      if (typeof tinymce !== 'undefined') {
        tinymce_editor = tinymce.get(editor_id);
      }

      if (tinymce_editor && !tinymce_editor.isHidden()) {
        tinymce_editor.execCommand('mceInsertContent', false, html);
      } else {
        var $editor = $('#' + editor_id);
        $editor.val(base.insertAtChars($editor, html)).trigger('change');
      }

    };

    return this.each(function () {

      var $modal = $(this),
        $load = $modal.find('.wptabspro-modal-load'),
        $content = $modal.find('.wptabspro-modal-content'),
        $insert = $modal.find('.wptabspro-modal-insert'),
        $loading = $modal.find('.wptabspro-modal-loading'),
        $select = $modal.find('select'),
        modal_id = $modal.data('modal-id'),
        nonce = $modal.data('nonce'),
        editor_id,
        target_id,
        sc_key,
        sc_name,
        sc_view,
        sc_group,
        $cloned,
        $button;

      $(document).on('click', '.wptabspro-shortcode-button[data-modal-id="' + modal_id + '"]', function (e) {

        e.preventDefault();

        $button = $(this);
        editor_id = $button.data('editor-id') || false;
        target_id = $button.data('target-id') || false;

        $modal.show();

        // single usage trigger first shortcode
        if ($modal.hasClass('wptabspro-shortcode-single') && sc_name === undefined) {
          $select.trigger('change');
        }

      });

      $select.on('change', function () {

        var $option = $(this);
        var $selected = $option.find(':selected');

        sc_key = $option.val();
        sc_name = $selected.data('shortcode');
        sc_view = $selected.data('view') || 'normal';
        sc_group = $selected.data('group') || sc_name;

        $load.empty();

        if (sc_key) {

          $loading.show();

          window.wp.ajax.post('wptabspro-get-shortcode-' + modal_id, {
            shortcode_key: sc_key,
            nonce: nonce
          })
            .done(function (response) {

              $loading.hide();

              var $appended = $(response.content).appendTo($load);

              $insert.parent().removeClass('hidden');

              $cloned = $appended.find('.wptabspro--repeat-shortcode').wptabspro_clone();

              $appended.wptabspro_reload_script();
              $appended.find('.wptabspro-fields').wptabspro_reload_script();

            });

        } else {

          $insert.parent().addClass('hidden');

        }

      });

      $insert.on('click', function (e) {

        e.preventDefault();

        if ($insert.prop('disabled') || $insert.attr('disabled')) { return; }

        var shortcode = '';
        var serialize = $modal.find('.wptabspro-field:not(.hidden)').find(':input:not(.ignore)').serializeObjectSP_WP_TABS();

        switch (sc_view) {

          case 'contents':
            var contentsObj = (sc_name) ? serialize[sc_name] : serialize;
            $.each(contentsObj, function (sc_key, sc_value) {
              var sc_tag = (sc_name) ? sc_name : sc_key;
              shortcode += '[' + sc_tag + ']' + sc_value + '[/' + sc_tag + ']';
            });
            break;

          case 'group':

            shortcode += '[' + sc_name;
            $.each(serialize[sc_name], function (sc_key, sc_value) {
              shortcode += base.shortcode_tags(sc_key, sc_value);
            });
            shortcode += ']';
            shortcode += base.shortcode_parse(serialize[sc_group], sc_group);
            shortcode += '[/' + sc_name + ']';

            break;

          case 'repeater':
            shortcode += base.shortcode_parse(serialize[sc_group], sc_group);
            break;

          default:
            shortcode += base.shortcode_parse(serialize);
            break;

        }

        shortcode = (shortcode === '') ? '[' + sc_name + ']' : shortcode;

        if (editor_id) {

          base.send_to_editor(shortcode, editor_id);

        } else {

          var $textarea = (target_id) ? $(target_id) : $button.parent().find('textarea');
          $textarea.val(base.insertAtChars($textarea, shortcode)).trigger('change');

        }

        $modal.hide();

      });

      $modal.on('click', '.wptabspro--repeat-button', function (e) {

        e.preventDefault();

        var $repeatable = $modal.find('.wptabspro--repeatable');
        var $new_clone = $cloned.wptabspro_clone();
        var $remove_btn = $new_clone.find('.wptabspro-repeat-remove');

        var $appended = $new_clone.appendTo($repeatable);

        $new_clone.find('.wptabspro-fields').wptabspro_reload_script();

        SP_WP_TABS.helper.name_nested_replace($modal.find('.wptabspro--repeat-shortcode'), sc_group);

        $remove_btn.on('click', function () {

          $new_clone.remove();

          SP_WP_TABS.helper.name_nested_replace($modal.find('.wptabspro--repeat-shortcode'), sc_group);

        });

      });

      $modal.on('click', '.wptabspro-modal-close, .wptabspro-modal-overlay', function () {
        $modal.hide();
      });

    });
  };

  //
  // WP Color Picker
  //
  if (typeof Color === 'function') {

    Color.prototype.toString = function () {

      if (this._alpha < 1) {
        return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
      }

      var hex = parseInt(this._color, 10).toString(16);

      if (this.error) { return ''; }

      if (hex.length < 6) {
        for (var i = 6 - hex.length - 1; i >= 0; i--) {
          hex = '0' + hex;
        }
      }

      return '#' + hex;

    };

  }

  SP_WP_TABS.funcs.parse_color = function (color) {

    var value = color.replace(/\s+/g, ''),
      trans = (value.indexOf('rgba') !== -1) ? parseFloat(value.replace(/^.*,(.+)\)/, '$1') * 100) : 100,
      rgba = (trans < 100) ? true : false;

    return { value: value, transparent: trans, rgba: rgba };

  };

  $.fn.wptabspro_color = function () {
    return this.each(function () {

      var $input = $(this),
        picker_color = SP_WP_TABS.funcs.parse_color($input.val()),
        palette_color = window.wptabspro_vars.color_palette.length ? window.wptabspro_vars.color_palette : true,
        $container;

      // Destroy and Reinit
      if ($input.hasClass('wp-color-picker')) {
        $input.closest('.wp-picker-container').after($input).remove();
      }

      $input.wpColorPicker({
        palettes: palette_color,
        change: function (event, ui) {

          var ui_color_value = ui.color.toString();

          $container.removeClass('wptabspro--transparent-active');
          $container.find('.wptabspro--transparent-offset').css('background-color', ui_color_value);
          $input.val(ui_color_value).trigger('change');

        },
        create: function () {

          $container = $input.closest('.wp-picker-container');

          var a8cIris = $input.data('a8cIris'),
            $transparent_wrap = $('<div class="wptabspro--transparent-wrap">' +
              '<div class="wptabspro--transparent-slider"></div>' +
              '<div class="wptabspro--transparent-offset"></div>' +
              '<div class="wptabspro--transparent-text"></div>' +
              '<div class="wptabspro--transparent-button">transparent <i class="fa fa-toggle-off"></i></div>' +
              '</div>').appendTo($container.find('.wp-picker-holder')),
            $transparent_slider = $transparent_wrap.find('.wptabspro--transparent-slider'),
            $transparent_text = $transparent_wrap.find('.wptabspro--transparent-text'),
            $transparent_offset = $transparent_wrap.find('.wptabspro--transparent-offset'),
            $transparent_button = $transparent_wrap.find('.wptabspro--transparent-button');

          if ($input.val() === 'transparent') {
            $container.addClass('wptabspro--transparent-active');
          }

          $transparent_button.on('click', function () {
            if ($input.val() !== 'transparent') {
              $input.val('transparent').trigger('change').removeClass('iris-error');
              $container.addClass('wptabspro--transparent-active');
            } else {
              $input.val(a8cIris._color.toString()).trigger('change');
              $container.removeClass('wptabspro--transparent-active');
            }
          });

          $transparent_slider.slider({
            value: picker_color.transparent,
            step: 1,
            min: 0,
            max: 100,
            slide: function (event, ui) {

              var slide_value = parseFloat(ui.value / 100);
              a8cIris._color._alpha = slide_value;
              $input.wpColorPicker('color', a8cIris._color.toString());
              $transparent_text.text((slide_value === 1 || slide_value === 0 ? '' : slide_value));

            },
            create: function () {

              var slide_value = parseFloat(picker_color.transparent / 100),
                text_value = slide_value < 1 ? slide_value : '';

              $transparent_text.text(text_value);
              $transparent_offset.css('background-color', picker_color.value);

              $container.on('click', '.wp-picker-clear', function () {

                a8cIris._color._alpha = 1;
                $transparent_text.text('');
                $transparent_slider.slider('option', 'value', 100);
                $container.removeClass('wptabspro--transparent-active');
                $input.trigger('change');

              });

              $container.on('click', '.wp-picker-default', function () {

                var default_color = SP_WP_TABS.funcs.parse_color($input.data('default-color')),
                  default_value = parseFloat(default_color.transparent / 100),
                  default_text = default_value < 1 ? default_value : '';

                a8cIris._color._alpha = default_value;
                $transparent_text.text(default_text);
                $transparent_slider.slider('option', 'value', default_color.transparent);

              });

            }
          });
        }
      });

    });
  };

  //
  // ChosenJS
  //
  $.fn.wptabspro_chosen = function () {
    return this.each(function () {

      var $this = $(this),
        $inited = $this.parent().find('.chosen-container'),
        is_sortable = $this.hasClass('wptabspro-chosen-sortable') || false,
        is_ajax = $this.hasClass('wptabspro-chosen-ajax') || false,
        is_multiple = $this.attr('multiple') || false,
        set_width = is_multiple ? '100%' : 'auto',
        set_options = $.extend({
          allow_single_deselect: true,
          disable_search_threshold: 10,
          width: set_width,
          no_results_text: window.wptabspro_vars.i18n.no_results_text,
        }, $this.data('chosen-settings'));

      if ($inited.length) {
        $inited.remove();
      }

      // Chosen ajax
      if (is_ajax) {

        var set_ajax_options = $.extend({
          data: {
            type: 'post',
            nonce: '',
          },
          allow_single_deselect: true,
          disable_search_threshold: -1,
          width: '100%',
          min_length: 2,
          type_delay: 500,
          typing_text: window.wptabspro_vars.i18n.typing_text,
          searching_text: window.wptabspro_vars.i18n.searching_text,
          no_results_text: window.wptabspro_vars.i18n.no_results_text,
        }, $this.data('chosen-settings'));

        $this.SP_WP_TABSAjaxChosen(set_ajax_options);

      } else {

        $this.chosen(set_options);

      }

      // Chosen keep options order
      if (is_multiple) {

        var $hidden_select = $this.parent().find('.wptabspro-hidden-select');
        var $hidden_value = $hidden_select.val() || [];

        $this.on('change', function (obj, result) {

          if (result && result.selected) {
            $hidden_select.append('<option value="' + result.selected + '" selected="selected">' + result.selected + '</option>');
          } else if (result && result.deselected) {
            $hidden_select.find('option[value="' + result.deselected + '"]').remove();
          }

          // Force customize refresh
          if ($hidden_select.children().length === 0 && window.wp.customize !== undefined) {
            window.wp.customize.control($hidden_select.data('customize-setting-link')).setting.set('');
          }

          $hidden_select.trigger('change');

        });

        // Chosen order abstract
        $this.SP_WP_TABSChosenOrder($hidden_value, true);

      }

      // Chosen sortable
      if (is_sortable) {

        var $chosen_container = $this.parent().find('.chosen-container');
        var $chosen_choices = $chosen_container.find('.chosen-choices');

        $chosen_choices.bind('mousedown', function (event) {
          if ($(event.target).is('span')) {
            event.stopPropagation();
          }
        });

        $chosen_choices.sortable({
          items: 'li:not(.search-field)',
          helper: 'orginal',
          cursor: 'move',
          placeholder: 'search-choice-placeholder',
          start: function (e, ui) {
            ui.placeholder.width(ui.item.innerWidth());
            ui.placeholder.height(ui.item.innerHeight());
          },
          update: function (e, ui) {

            var select_options = '';
            var chosen_object = $this.data('chosen');
            var $prev_select = $this.parent().find('.wptabspro-hidden-select');

            $chosen_choices.find('.search-choice-close').each(function () {
              var option_array_index = $(this).data('option-array-index');
              $.each(chosen_object.results_data, function (index, data) {
                if (data.array_index === option_array_index) {
                  select_options += '<option value="' + data.value + '" selected>' + data.value + '</option>';
                }
              });
            });

            $prev_select.children().remove();
            $prev_select.append(select_options);
            $prev_select.trigger('change');

          }
        });

      }

    });
  };

  //
  // Helper Checkbox Checker
  //
  $.fn.wptabspro_checkbox = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('.wptabspro--input'),
        $checkbox = $this.find('.wptabspro--checkbox');

      $checkbox.on('click', function () {
        $input.val(Number($checkbox.prop('checked'))).trigger('change');
      });

    });
  };

  //
  // Siblings
  //
  $.fn.wptabspro_siblings = function () {
    return this.each(function () {

      var $this = $(this),
        $siblings = $this.find('.wptabspro--sibling'),
        multiple = $this.data('multiple') || false;

      $siblings.on('click', function () {

        var $sibling = $(this);

        if (multiple) {

          if ($sibling.hasClass('wptabspro--active')) {
            $sibling.removeClass('wptabspro--active');
            $sibling.find('input').prop('checked', false).trigger('change');
          } else {
            $sibling.addClass('wptabspro--active');
            $sibling.find('input').prop('checked', true).trigger('change');
          }

        } else {

          $this.find('input').prop('checked', false);
          $sibling.find('input').prop('checked', true).trigger('change');
          $sibling.addClass('wptabspro--active').siblings().removeClass('wptabspro--active');

        }

      });

    });
  };

  //
  // Help Tooltip
  //
  $.fn.wptabspro_help = function () {
    return this.each(function () {

      var $this = $(this),
        $tooltip,
        offset_left,
        $class = '';

      $this.on({
        mouseenter: function () {
          // this class add with the support tooltip.
          if ($this.find('.wptabspro-support').length > 0) {
            $class = 'support-tooltip';
          }
          $tooltip = $('<div class="wptabspro-tooltip ' + $class + '"></div>').html($this.find('.wptabspro-help-text').html()).appendTo('body');
          offset_left = (SP_WP_TABS.vars.is_rtl) ? ($this.offset().left + 36) : ($this.offset().left + 36);

          $tooltip.css({
            top: $this.offset().top - (($tooltip.outerHeight() / 2) - 14),
            left: offset_left,
            textAlign: 'left',
          });

        },
        mouseleave: function () {

          if (!$tooltip.is(':hover')) {
            $tooltip.remove();
          }

        }

      });
      // Event delegation to handle tooltip removal when the cursor leaves the tooltip itself.
			$('body').on('mouseleave', '.wptabspro-tooltip', function () {
				if ($tooltip !== undefined) {
					$tooltip.remove();
				}
			});

    });
  };

  //
  // Customize Refresh
  //
  $.fn.wptabspro_customizer_refresh = function () {
    return this.each(function () {

      var $this = $(this),
        $complex = $this.closest('.wptabspro-customize-complex');

      if ($complex.length) {

        var $input = $complex.find(':input'),
          $unique = $complex.data('unique-id'),
          $option = $complex.data('option-id'),
          obj = $input.serializeObjectSP_WP_TABS(),
          data = (!$.isEmptyObject(obj)) ? obj[$unique][$option] : '',
          control = window.wp.customize.control($unique + '[' + $option + ']');

        // clear the value to force refresh.
        control.setting._value = null;

        control.setting.set(data);

      } else {

        $this.find(':input').first().trigger('change');

      }

      $(document).trigger('wptabspro-customizer-refresh', $this);

    });
  };

  //
  // Customize Listen Form Elements
  //
  $.fn.wptabspro_customizer_listen = function (options) {

    var settings = $.extend({
      closest: false,
    }, options);

    return this.each(function () {

      if (window.wp.customize === undefined) { return; }

      var $this = (settings.closest) ? $(this).closest('.wptabspro-customize-complex') : $(this),
        $input = $this.find(':input'),
        unique_id = $this.data('unique-id'),
        option_id = $this.data('option-id');

      if (unique_id === undefined) { return; }

      $input.on('change keyup', SP_WP_TABS.helper.debounce(function () {

        var obj = $this.find(':input').serializeObjectSP_WP_TABS();
        var val = (!$.isEmptyObject(obj) && obj[unique_id] && obj[unique_id][option_id]) ? obj[unique_id][option_id] : '';

        window.wp.customize.control(unique_id + '[' + option_id + ']').setting.set(val);

      }, 250));

    });
  };

  //
  // Customizer Listener for Reload JS
  //
  $(document).on('expanded', '.control-section', function () {

    var $this = $(this);

    if ($this.hasClass('open') && !$this.data('inited')) {

      var $fields = $this.find('.wptabspro-customize-field');
      var $complex = $this.find('.wptabspro-customize-complex');

      if ($fields.length) {
        $this.wptabspro_dependency();
        $fields.wptabspro_reload_script({ dependency: false });
        $complex.wptabspro_customizer_listen();
      }

      $this.data('inited', true);

    }

  });

  //
  // Window on resize
  //
  SP_WP_TABS.vars.$window.on('resize wptabspro.resize', SP_WP_TABS.helper.debounce(function (event) {

    var window_width = navigator.userAgent.indexOf('AppleWebKit/') > -1 ? SP_WP_TABS.vars.$window.width() : window.innerWidth;

    if (window_width <= 782 && !SP_WP_TABS.vars.onloaded) {
      $('.wptabspro-section').wptabspro_reload_script();
      SP_WP_TABS.vars.onloaded = true;
    }

  }, 200)).trigger('wptabspro.resize');

  //
  // Retry Plugins
  //
  $.fn.wptabspro_reload_script_retry = function () {
    return this.each(function () {

      var $this = $(this);

      if ($this.data('inited')) {
        $this.children('.wptabspro-field-wp_editor').wptabspro_field_wp_editor();
      }

    });
  };

  //
  // Reload Plugins
  //
  $.fn.wptabspro_reload_script = function (options) {

    var settings = $.extend({
      dependency: true,
    }, options);

    return this.each(function () {

      var $this = $(this);

      // Avoid for conflicts
      if (!$this.data('inited')) {

        // Field plugins
        $this.children('.wptabspro-field-accordion').wptabspro_field_accordion();
        $this.children('.wptabspro-field-backup').wptabspro_field_backup();
        $this.children('.wptabspro-field-background').wptabspro_field_background();
        $this.children('.wptabspro-field-code_editor').wptabspro_field_code_editor();
        $this.children('.wptabspro-field-fieldset').wptabspro_field_fieldset();
        $this.children('.wptabspro-field-group').wptabspro_field_group();
        $this.children('.wptabspro-field-media').wptabspro_field_media();
        $this.children('.wptabspro-field-repeater').wptabspro_field_repeater();
        $this.children('.wptabspro-field-slider').wptabspro_field_slider();
        $this.children('.wptabspro-field-sortable').wptabspro_field_sortable();
        $this.children('.wptabspro-field-sorter').wptabspro_field_sorter();
        $this.children('.wptabspro-field-spinner').wptabspro_field_spinner();
        $this.children('.wptabspro-field-switcher').wptabspro_field_switcher();
        $this.children('.wptabspro-field-typography').wptabspro_field_typography();
        $this.children('.wptabspro-field-upload').wptabspro_field_upload();
        $this.children('.wptabspro-field-wp_editor').wptabspro_field_wp_editor();

        // Field colors
        $this.children('.wptabspro-field-border').find('.wptabspro-color').wptabspro_color();
        $this.children('.wptabspro-field-background').find('.wptabspro-color').wptabspro_color();
        $this.children('.wptabspro-field-color').find('.wptabspro-color').wptabspro_color();
        $this.children('.wptabspro-field-color_group').find('.wptabspro-color').wptabspro_color();
        $this.children('.wptabspro-field-link_color').find('.wptabspro-color').wptabspro_color();
        $this.children('.wptabspro-field-typography').find('.wptabspro-color').wptabspro_color();

        // Field chosenjs
        $this.children('.wptabspro-field-select').find('.wptabspro-chosen').wptabspro_chosen();

        // Field Checkbox
        $this.children('.wptabspro-field-checkbox').find('.wptabspro-checkbox').wptabspro_checkbox();

        // Field Siblings
        $this.children('.wptabspro-field-button_set').find('.wptabspro-siblings').wptabspro_siblings();
        $this.children('.wptabspro-field-image_select').find('.wptabspro-siblings').wptabspro_siblings();
        $this.children('.wptabspro-field-palette').find('.wptabspro-siblings').wptabspro_siblings();

        // Help Tooptip
        $this.children('.wptabspro-field').find('.wptabspro-help').wptabspro_help();

        if (settings.dependency) {
          $this.wptabspro_dependency();
        }

        $this.data('inited', true);

        $(document).trigger('wptabspro-reload-script', $this);

      }

    });
  };

  //
  // Document ready and run scripts
  //
  $(document).ready(function () {

    $('.wptabspro-save').wptabspro_save();
    $('.wptabspro-options').wptabspro_options();
    $('.wptabspro-sticky-header').wptabspro_sticky();
    $('.wptabspro-nav-options').wptabspro_nav_options();
    $('.wptabspro-nav-metabox').wptabspro_nav_metabox();
    $('.wptabspro-page-templates').wptabspro_page_templates();
    $('.wptabspro-post-formats').wptabspro_post_formats();
    $('.wptabspro-shortcode').wptabspro_shortcode();
    $('.wptabspro-search').wptabspro_search();
    $('.wptabspro-confirm').wptabspro_confirm();
    $('.wptabspro-expand-all').wptabspro_expand_all();
    $('.wptabspro-onload').wptabspro_reload_script();

  });


  $('.post-type-sp_wp_tabs .column-shortcode input').on('click',function (e) {
    e.preventDefault();
    /* Get the text field */
    var copyText = $(this);
    /* Select the text field */
    copyText.select();
    document.execCommand("copy");
    jQuery(".sp_tab-after-copy-text").animate({
      opacity: 1,
      bottom: 25
    }, 300);
    setTimeout(function () {
      jQuery(".sp_tab-after-copy-text").animate({
        opacity: 0,
      }, 200);
      jQuery(".sp_tab-after-copy-text").animate({
        bottom: -100 + 'px',
      }, 0);
    }, 2000);
  });
	$('.sp-tab__shortcode-selectable').on('click',function (e) {
    e.preventDefault();
    sp_tab_copyToClipboard($(this));
    sp_tab_SelectText($(this));
    $(this).focus().select();
    jQuery(".sp_tab-after-copy-text").animate({
      opacity: 1,
      bottom: 25
    }, 300);
    setTimeout(function () {
      jQuery(".sp_tab-after-copy-text").animate({
        opacity: 0,
      }, 200);
      jQuery(".sp_tab-after-copy-text").animate({
        bottom:  -100 + 'px',
      }, 0);
    }, 2000);
  });

  function sp_tab_copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
  }
  function sp_tab_SelectText(element) {
    var r = document.createRange();
    var w = element.get(0);
    r.selectNodeContents(w);
    var sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(r);
  }
  function isValidJSONString(str) {
    try {
      JSON.parse(str);
    } catch (e) {
      return false;
    }
    return true;
  }

  // Tabs export.
  var $export_type = $('.tabs_what_export').find('input:checked').val();
  $('.tabs_what_export').on('change', function () {
    $export_type = $(this).find('input:checked').val();
  });
	$('.wp_tabs_export .wptabspro--button').on('click',function (event) {
    event.preventDefault();
    var $wp_tabs_ids = $('.tabs_post_ids select').val();
    var $wp_tabs_ids_type = 'all_shortcodes' === $export_type ? 'all_shortcodes' : $wp_tabs_ids;
    var $ex_nonce = $('#wptabspro_options_noncesp_tab_tools').val();

    if ('all_shortcodes' === $export_type || ( 'selected_shortcodes' === $export_type && $wp_tabs_ids_type.length > 0 ) ) {
      var data = {
        action: 'tabs_export_shortcode',
        tab_ids: $wp_tabs_ids_type,
        nonce: $ex_nonce,
      }
      $.post(ajaxurl, data, function (resp) {
        if (resp) {
          // Convert JSON Array to string.
          if (isValidJSONString(resp)) {
            var json = JSON.stringify(JSON.parse(resp));
          } else {
            var json = JSON.stringify(resp);
          }
          // Convert JSON string to BLOB.
          var blob = new Blob([json], { type: 'application/json' });

          var link = document.createElement('a');
          var eap_time = $.now();
          link.href = window.URL.createObjectURL(blob);
          link.download = "wp-tabs-export-" + eap_time + ".json";
          link.click();
          $('.wptabspro-form-result.wptabspro-form-success').text('Exported successfully!').show();
          setTimeout(function () {
            $('.wptabspro-form-result.wptabspro-form-success').hide().text('');
            $('.tabs_post_ids select').val('').trigger('chosen:updated');
          }, 3000);
        }
      });
    } else {
      $('.wptabspro-form-result.wptabspro-form-success').text('No tabs group selected.').show();
      setTimeout(function () {
        $('.wptabspro-form-result.wptabspro-form-success').hide().text('');
      }, 3000);
    }
  });
  // Tabs import.
	$('.wp_tabs_import button.import').on('click',function (event) {
      event.preventDefault();
      var $this = $(this),
      button_text = $this.text(),
      tab__shortcodes = $('#import').prop('files')[0];
    if ($('#import').val() != '') {
      $this.prop('disabled',true).css( 'opacity', '0.7' );
      $this.append('<span class="sp-tab-page-loading-spinner"><i class="fa fa-spinner" aria-hidden="true"></i></span>');
      var $im_nonce = $('#wptabspro_options_noncesp_tab_tools').val();
      var reader = new FileReader();
      reader.readAsText(tab__shortcodes);
      reader.onload = function (event) {
		    var unSanitize = $('.wptabspro-field-checkbox input[name="sp_tab_tools[import_unSanitize]"]').val();
        var jsonObj = JSON.stringify(event.target.result);
        $.ajax({
          url: ajaxurl,
          type: 'POST',
          data: {
            shortcode: jsonObj,
            action: 'tabs_import_shortcode',
            nonce: $im_nonce,
			      unSanitize,
          },
          success: function (resp) {
            $('.wptabspro-form-result.wptabspro-form-success').text('Imported successfully!').show();
            $this.prop('disabled',false).css( 'opacity', '1' ).text(button_text);
            setTimeout(function () {
              $('.wptabspro-form-result.wptabspro-form-success').hide().text('');
              $('#import').val('');
              window.location.replace($('#wp__tabs_link_redirect').attr('href'));
            }, 2000);
          },
					error: function(error){
            $('.wptabspro-form-result.wptabspro-form-success').text('Something went wrong!').addClass('wptabspro-import-warning').show();
            setTimeout(function () {
              $this.prop('disabled',false).css( 'opacity', '1' ).text(button_text);
              $('.wptabspro-form-result.wptabspro-form-success').hide().removeClass('wptabspro-import-warning').text('');
              $('#import').val('');
            }, 2000);
					}
        });
      }
    } else {
      $('.wptabspro-form-result.wptabspro-form-success').text('No exported json file chosen.').show();
      setTimeout(function () {
        $('.wptabspro-form-result.wptabspro-form-success').hide().text('');
      }, 3000);
    }
  });

  // Live Preview script for wp tabs.
  var preview_box = $('#sp-tab-preview-box');
  var preview_display = $('#sp_tab_live_preview').hide();
  $(document).on('click', '#sp-tab-show-preview:contains(Hide)', function (e) {
    e.preventDefault();
    var _this = $(this);
    _this.html('<i class="fa fa-eye" aria-hidden="true"></i> Show Preview');
    preview_box.html('');
    preview_display.hide();
  });

  $(document).on('click', '#sp-tab-show-preview:not(:contains(Hide))', function (e) {
    e.preventDefault();
    var _data = $('form#post').serialize();
    var _this = $(this);
    var data = {
      action: 'sp_tab_preview_meta_box',
      data: _data,
      ajax_nonce: $('#wptabspro_metabox_noncesp_tab_live_preview').val()
    };
    $.ajax({
      type: "POST",
      url: ajaxurl,
      data: data,
      error: function (response) {
        console.log(response)
      },
      success: function (response) {
        preview_display.show();
        preview_box.html(response);
        _this.html('<i class="fa fa-eye-slash" aria-hidden="true"></i> Hide Preview');
        $(document).on('keyup change', function (e) {
          e.preventDefault();
          _this.html('<i class="fa fa-refresh" aria-hidden="true"></i> Update Preview');
        });
        $("html, body").animate({ scrollTop: preview_display.offset().top - 50 }, "slow");
      }
    })
  });

  var $animationSelect = $('.wptabspro-field.sptpro-tabs-animation-type');
  // Iterate through all the animation options.
  $animationSelect.find('option').each(function() {
      var $option = $(this);
      // Check if the option text contains "(Pro)"
      if ($option.text().indexOf('(Pro)') !== -1) {
          // Disable the option.
          $option.prop('disabled', true);
      }
  });

  $(document).on('keyup change', '.sp_wp_tabs_page_tabs_settings #wptabspro-form', function (e) {
    e.preventDefault();
    var $button = $(this).find('.wptabspro-save');
    $button.css({ "background-color": "#00C263", "pointer-events": "initial" }).val('Save Settings');
  });
	$('.sp_wp_tabs_page_tabs_settings .wptabspro-save').on('click',function (e) {
    e.preventDefault();
    $(this).css({ "background-color": "#C5C5C6", "pointer-events": "none" }).val('Changes Saved');
  })

})(jQuery, window, document);
