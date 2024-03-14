"use strict";

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

(function ($) {
  'use strict';

  var styles = {};
  var campaignInnerPreviewRef = $('.hurrytimer-campaign');
  var campaignPreviewRef = $('#hurrytimer-campaign-preview');
  var headlinePreviewRef = campaignInnerPreviewRef.find('.hurrytimer-headline');
  var timerPreviewRef = campaignInnerPreviewRef.find('.hurrytimer-timer');
  var timerDigitPreviewRef = campaignInnerPreviewRef.find('.hurrytimer-timer-digit');
  var timerLabelPreviewRef = campaignInnerPreviewRef.find('.hurrytimer-timer-label');
  var timerBlockPreviewRef = campaignInnerPreviewRef.find('.hurrytimer-timer-block');
  var timerSepPreviewRef = campaignInnerPreviewRef.find('.hurrytimer-timer-sep');
  var campaignCTA = campaignInnerPreviewRef.find('.hurrytimer-button');
  /**
   * Toggle the given block visibility.
   * @param {object} toggle
   * @param {object} block
   */

  function toggleBlockVisibility(toggle, block) {
    if (toggle.is(':checked')) {
      block.removeClass('hidden');

      if ($('input[name=block_separator_visibility]').is(':checked')) {
        block.next().removeClass('hidden');
      }
    } else {
      block.addClass('hidden');
      block.next().addClass('hidden');
    }
  }
  /**
   * Change element color for the preview.
   *
   * @param {object} inputElement
   * @param {string} color
   */


  function changeColor(inputElement) {
    var color = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';

    if (typeof inputElement === 'string') {
      inputElement = $('input[name="' + inputElement + '"]');
    }

    color = color || inputElement.val();

    switch (inputElement.attr('name')) {
      case 'digit_color':
        setCSS('.hurrytimer-campaign .hurrytimer-timer-digit', timerDigitPreviewRef, 'color', color, false);
        setCSS('.hurrytimer-campaign .hurrytimer-timer-sep', timerSepPreviewRef, 'color', color);
        break;

      case 'block_border_color':
        setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'border-color', color);
        break;

      case 'block_bg_color':
        setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'background-color', color);
        break;

      case 'label_color':
        setCSS('.hurrytimer-campaign .hurrytimer-timer-label', timerLabelPreviewRef, 'color', color);
        break;

      case 'headline_color':
        setCSS('.hurrytimer-campaign  .hurrytimer-headline', headlinePreviewRef, 'color', color);
        break;

      case 'sticky_bar_bg_color':
        setCSS('.hurrytimer-sticky', $('.hurrytimer-sticky'), 'background-color', color);
        break;

      case 'call_to_action[bg_color]':
        setCSS('.hurrytimer-campaign .hurrytimer-button', campaignCTA, 'background-color', color);
        break;

      case 'call_to_action[text_color]':
        setCSS('.hurrytimer-campaign .hurrytimer-button', campaignCTA, 'color', color);
        break;

      case 'sticky_bar_close_btn_color':
        setCSS('.hurrytimer-sticky-close svg', $('.hurrytimer-sticky-close svg'), 'fill', color);
        break;
    }
  }
  /**
   * Apply CSS for live preview.
   *
   * @param {object} element
   * @param {string} property
   * @param {string} value
   * @param {boolean} apply
   */


  function setCSS(selector, element, property, value) {
    var apply = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : true;

    var _selector = element.selector || selector;

    styles = _objectSpread(_objectSpread({}, styles), {}, _defineProperty({}, _selector, _objectSpread(_objectSpread({}, styles[_selector]), {}, _defineProperty({}, property, value))));

    if (apply) {
      if ($('#hurryt-styles').length === 0) {
        $('head').append('<style id="hurryt-styles"></style>');
      }

      var css = '';

      for (var sel in styles) {
        css += " ".concat(sel, "{ ").concat(Object.entries(styles[sel]).join(';').replace(/\,/g, ':'), "}");
      }

      $('#hurryt-styles').html(css);
    }
  }

  function removeCSSProperty(element, property) {
    for (var selector in styles) {
      if (selector === element.selector) {
        delete styles[selector][property];
      }
    }
  } // ------------------------------------------------------------
  // Initialize preview items color.
  // ------------------------------------------------------------


  changeColor('digit_color');
  changeColor('label_color');
  changeColor('block_border_color');
  changeColor('block_bg_color');
  changeColor('headline_color');
  changeColor('sticky_bar_bg_color');
  changeColor('sticky_bar_close_btn_color');
  changeColor('call_to_action[bg_color]');
  changeColor('call_to_action[text_color]'); // ------------------------------------------------------------
  // Input toggle.
  // ------------------------------------------------------------

  $('.js-hurrytimer-input-toggle').each(function () {
    var input = $(this);
    input.hide();
    var toggle = $("<input \n                      type=\"hidden\" \n                      name=\"".concat(input.attr('name'), "\" \n                      value=\"no\" />\n                      <span \n                      class=\"hurrytimer-input-toggle\">\n                      </span>"));
    input.before(toggle);

    if (input.prop('checked')) {
      toggle.addClass('is-on');
    }

    toggle.on('click', function () {
      toggle.toggleClass('is-on');
      input.attr('checked', !input.prop('checked'));
      input.trigger('change');
    });
  }); // ------------------------------------------------------------
  // Datetime picker.
  // ------------------------------------------------------------

  $('.hurrytimer-datepicker').each(function () {
    var $this = $(this);
    $this.datetimepicker({
      controlType: 'select',
      dateFormat: 'yy-mm-dd',
      timeFormat: 'hh:mm TT',
      oneLine: true,
      onSelect: function onSelect(value, instance) {
        setMonthlyDayTypes($this);
      }
    });
    setMonthlyDayTypes($this);
  });

  function setMonthlyDayTypes($input) {
    if ($input.attr('name') === 'recurring_start_time') {
      var _dayOfMonth = getDayOfMonth($input.datepicker('getDate'));

      var _dayOfWeek = getDayOfWeek($input.datepicker('getDate'));

      $('#recurDayOfMonth').text(_dayOfMonth);
      $('#recurDayOfWeek').text(_dayOfWeek);
    }
  }

  function getDayOfMonth(date) {
    var dayOfMonth = false;
    var day = $.datepicker.formatDate('d', date);

    if (day == 1) {
      dayOfMonth = '1st day';
    } else if (day == 2) {
      dayOfMonth = '2nd day';
    } else if (day == 3) {
      dayOfMonth = '3rd day';
    } else {
      dayOfMonth = day + 'th day';
    }

    return dayOfMonth;
  }

  function getDayOfWeek(date) {
    var dayOfWeek = false;
    var dayName = $.datepicker.formatDate('DD', date);
    var day = $.datepicker.formatDate('d', date);
    var dayIndex = Math.ceil(day / 7);

    if (dayIndex == 1) {
      dayOfWeek = '1st';
    } else if (dayIndex == 2) {
      dayOfWeek = '2nd';
    } else if (dayIndex == 23) {
      dayOfWeek = '3rd';
    } else {
      dayOfWeek = dayIndex + 'th';
    }

    dayOfWeek += ' ' + dayName;
    return dayOfWeek;
  }

  $('.hurrytimer-timepicker').each(function () {
    $(this).timepicker({
      timeFormat: 'hh:mm TT',
      controlType: 'select',
      oneLine: true
    });
  }); // ------------------------------------------------------------
  // Handle mode toggle.
  // ------------------------------------------------------------

  function handleMode(elementRef) {
    document.querySelectorAll('.mode-settings[data-for^="hurrytMode"]').forEach(function (e) {
      e.classList.add('hidden');
    });
    document.querySelectorAll(".mode-settings[data-for=\"".concat(elementRef.attr('id'), "\"]")).forEach(function (e) {
      e.classList.remove('hidden');
    });
  }

  var toggleRecurringUntil = function toggleRecurringUntil(value) {
    if (value == 3) {
      recurringUntilElement.classList.remove('hidden');
    } else {
      recurringUntilElement.classList.add('hidden');
    }
  };

  var recurringUntilElement = document.querySelector('tr[data-for="hurrytRecurringUntil"]');
  document.querySelectorAll('input[name="recurring_until"]').forEach(function (e) {
    e.addEventListener('change', function (e) {
      return toggleRecurringEndDate(e.target.value);
    });
  });
  document.querySelectorAll('input[name="recurring_until"]:checked').forEach(function (e) {
    return toggleRecurringUntil(e.value);
  }); // Handle mode.

  $('input[name=mode]').on('change', function () {
    handleMode($(this));
  });
  handleMode($('input[name=mode]:checked')); // ------------------------------------------------------------
  // Handle products type dropdown.
  // ------------------------------------------------------------

  $('#hurrytimer-wc-products-selection-type').on('change', function () {
    var $this = $(this);
    var $selectedOption = $this.find('option:selected');
    var $label = $('.hurrytimer-products-selection-type-label');
    var $autocompleteWrap = $label.closest('.form-field');

    if ($selectedOption.data('show-autocomplete')) {
      $label.text($selectedOption.text());
      $autocompleteWrap.removeClass('hidden');
    } else {
      $autocompleteWrap.addClass('hidden');
    }
  }).change(); // Handle tabs
  // ------------------------------------------------------------

  $('.hurrytimer-tabbar a').on('click', function (e) {
    e.preventDefault();
    var $tab = $(this);
    $('.hurrytimer-tabcontent').removeClass('active');
    $($tab.attr('href')).addClass('active');
    $tab.parent().siblings().removeClass('active');
    $tab.parent().addClass('active');

    if ($tab.attr('href').indexOf('appearance') >= 0 || $tab.attr('href').indexOf('styling') >= 0) {
      $('.hurryt-fullscreen').removeClass('hidden');
    } else {
      $('.hurryt-fullscreen').addClass('hidden');
    }
  }); // ------------------------------------------------------------
  // Search for products/Categories
  // ------------------------------------------------------------

  $('.hurryt-tags-input').select2({
    tags: true,
    placeholder: 'Example: http://www.example.com/page',
    tokenSeparators: [',', ' ']
  });
  $('#hurrytimer-wc-products-selection').select2({
    placeholder: 'Search...',
    width: '500',
    minimumInputLength: 2,
    ajax: {
      url: hurrytimer_ajax_object.ajax_url,
      dataType: 'json',
      data: function data(params) {
        return {
          action: 'wcSearchProducts',
          search: params.term,
          exclude: $(this).val(),
          productsSelection: $('#hurrytimer-wc-products-selection-type').val(),
          type: 'public'
        };
      }
    }
  }); // ------------------------------------------------------------
  // Color picker
  // ------------------------------------------------------------

  $('.hurrytimer-color-input').each(function () {
    var self = $(this);
    self.wpColorPicker({
      width: 220,
      change: function change(event, ui) {
        changeColor(self, ui.color.toString());
      },
      clear: function clear() {
        changeColor(self, 'transparent');
      }
    });
  }); // ------------------------------------------------------------
  // CUSTOM CSS
  // ------------------------------------------------------------
  // ------------------------------------------------------------
  // Handle sub tabbar.
  // ------------------------------------------------------------


  $('.hurrytimer-subtabbar a').on('click', function (e) {
    e.preventDefault();
    var self = $(this);
    $('.hurrytimer-subtabcontent').each(function () {
      $(this).removeClass('active');
    });
    $(self.attr('href')).addClass('active');
    self.parent().siblings().removeClass('active');
    self.parent().addClass('active');
    if (cssEditor) cssEditor.refresh();
  }); // ------------------------------------------------------------
  // Accordion.
  // ------------------------------------------------------------

  $('.hurrytimer-accordion-heading').on('click', function () {
    var self = $(this);
    var containerElement = self.parent();

    if (containerElement.hasClass('active')) {
      containerElement.removeClass('active');
    } else {
      containerElement.addClass('active').siblings().removeClass('active');
    }
  }); // ------------------------------------------------------------
  // Enable/disable sticky bar.
  // ------------------------------------------------------------

  $('input[name=enable_sticky]').on('change', function () {
    if ($(this).is(':checked')) {
      campaignPreviewRef.addClass('hurrytimer-sticky');
      campaignInnerPreviewRef.wrap('<div class="hurrytimer-sticky-inner"></div>');

      if (campaignPreviewRef.hasClass('hurryt-preview-fullscreen')) {
        setCSS('#hurrytimer-campaign-preview', campaignPreviewRef, 'position', 'fixed', false);
        setCSS('#hurrytimer-campaign-preview', campaignPreviewRef, 'top', 0);
      }
    } else {
      campaignPreviewRef.removeClass('hurrytimer-sticky');
      campaignInnerPreviewRef.unwrap('.hurrytimer-sticky-inner');
    } // refresh dismiss button


    if ($('input[name=sticky_bar_dismissible]').is(':checked')) {
      campaignPreviewRef.find('.hurrytimer-sticky-close').show();
    } else {
      campaignPreviewRef.find('.hurrytimer-sticky-close').hide();
    }
  });
  $('input[name=sticky_bar_dismissible]').on('change', function () {
    if ($(this).is(':checked')) {
      campaignPreviewRef.find('.hurrytimer-sticky-close').show();
    } else {
      campaignPreviewRef.find('.hurrytimer-sticky-close').hide();
    }
  }); // ------------------------------------------------------------
  // Change block display.
  // ------------------------------------------------------------

  $('select[name=block_display]').on('change', function () {
    var value = $(this).val();
    var blockSize = $('input[name="block_size"]').val() + 'px';
    setCSS('.hurrytimer-campaign .hurrytimer-timer-digit', timerDigitPreviewRef, 'display', value, false);
    setCSS('.hurrytimer-campaign .hurrytimer-timer-label', timerLabelPreviewRef, 'display', value, false);
    var blockSizeInput = $(this).closest('.hurrytimer-style-control-field').siblings('.hurrytimer-field-block-size');

    if (value === 'inline') {
      setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'width', 'auto', false);
      setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'height', 'auto', false);
      setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'display', 'inline-block');
      blockSizeInput.hide();
    } else {
      blockSizeInput.show();
      setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'width', blockSize, false);
      setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'height', blockSize, false);
      setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'display', 'flex');
    }
  }).change(); // ------------------------------------------------------------
  // Set digit size.
  // ------------------------------------------------------------

  $('input[name=digit_size]').on('input keyup paste change', function () {
    var fontSize = parseInt($(this).val()) + 'px';
    setCSS('.hurrytimer-campaign .hurrytimer-timer-digit', timerDigitPreviewRef, 'font-size', fontSize, false);
    setCSS('.hurrytimer-campaign .hurrytimer-timer-sep', timerSepPreviewRef, 'font-size', fontSize);
  }).change(); // ------------------------------------------------------------
  // Set CTA text size.
  // ------------------------------------------------------------

  $('input[name="call_to_action[text_size]"]').on('input keyup paste change', function () {
    var fontSize = parseInt($(this).val()) + 'px';
    setCSS('.hurrytimer-campaign .hurrytimer-button', campaignCTA, 'font-size', fontSize);
  }).change(); // ------------------------------------------------------------
  // Set block spacing.
  // ------------------------------------------------------------

  $('input[name=block_spacing]').on('input keyup paste change', function () {
    var spacing = "".concat($(this).val(), "px");

    if ($('select[name=display]').val() === 'inline') {
      setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'margin-bottom', spacing, false);
      setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'margin-top', spacing);
    } else {
      setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'margin-left', spacing, false);
      setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'margin-right', spacing);
    }
  }).change(); // ------------------------------------------------------------
  // Set block padding.
  // ------------------------------------------------------------

  $('input[name=block_padding]').on('input keyup paste change', function () {
    var padding = parseInt($(this).val()) + 'px';
    setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'padding', padding);
  }).change(); // ------------------------------------------------------------
  // Sticky Bar Y padding.
  // ------------------------------------------------------------

  $('input[name=sticky_bar_padding]').on('input keyup paste change', function () {
    var padding = "".concat($(this).val(), "px");
    var stickyBarInner = campaignPreviewRef.find('.hurrytimer-sticky-inner');
    setCSS('#hurrytimer-campaign-preview .hurrytimer-sticky-inner', stickyBarInner, 'padding-top', padding, false);
    setCSS('#hurrytimer-campaign-preview .hurrytimer-sticky-inner', stickyBarInner, 'padding-bottom', padding);
  }).change();
  $('select[name=sticky_bar_position]').on('input keyup paste change', function () {
    if ($(this).val() === 'top') {
      removeCSSProperty(campaignPreviewRef, 'bottom');
      setCSS('#hurrytimer-campaign-preview', campaignPreviewRef, 'top', 0);
    } else {//removeCSSProperty(campaignPreviewRef, 'top');
      // setCSS(campaignPreviewRef, 'bottom', 0);
    }
  }).change();
  $('input[name=headline_spacing]').on('input keyup paste change', function () {
    var spacing = "".concat($(this).val(), "px");

    if ($('select[name=campaign_display]').val() === 'inline') {
      if ($('select[name=headline_position]').val() === hurrytimer_ajax_object.headline_pos.above_timer) {
        setCSS('.hurrytimer-campaign .hurrytimer-headline', headlinePreviewRef, 'margin-left', spacing);
        setCSS('.hurrytimer-campaign .hurrytimer-headline', headlinePreviewRef, 'margin-top', 0);
      } else {
        setCSS('.hurrytimer-campaign .hurrytimer-headline', headlinePreviewRef, 'margin-right', spacing);
        setCSS('.hurrytimer-campaign .hurrytimer-headline', headlinePreviewRef, 'margin-bottom', 0);
      }
    } else {
      if ($('select[name=headline_position]').val() === hurrytimer_ajax_object.headline_pos.above_timer) {
        setCSS('.hurrytimer-campaign .hurrytimer-headline', headlinePreviewRef, 'margin-left', 0);
        setCSS('.hurrytimer-campaign .hurrytimer-headline', headlinePreviewRef, 'margin-top', spacing);
      } else {
        setCSS('.hurrytimer-campaign .hurrytimer-headline', headlinePreviewRef, 'margin-right', 0);
        setCSS('.hurrytimer-campaign .hurrytimer-headline', headlinePreviewRef, 'margin-bottom', spacing);
      }
    }
  }).change();
  $('input[name="call_to_action[spacing]"]').on('input keyup paste change', function () {
    var spacing = "".concat($(this).val(), "px");

    if ($('select[name=campaign_display]').val() === 'inline') {
      setCSS('.hurrytimer-campaign .hurrytimer-button', campaignCTA, 'margin-right', spacing, false);
      setCSS('.hurrytimer-campaign .hurrytimer-button', campaignCTA, 'margin-left', spacing);
    } else {
      setCSS('.hurrytimer-campaign .hurrytimer-button', campaignCTA, 'margin-top', spacing, false);
      setCSS('.hurrytimer-campaign .hurrytimer-button', campaignCTA, 'margin-bottom', spacing);
    }
  }).change(); // ------------------------------------------------------------
  // Set label size.
  // ------------------------------------------------------------

  $('input[name=label_size]').on('input keyup paste change', function () {
    setCSS('.hurrytimer-campaign .hurrytimer-timer-label', timerLabelPreviewRef, 'font-size', parseInt($(this).val()) + 'px');
  }).change(); // ------------------------------------------------------------
  // Set block border width.
  // ------------------------------------------------------------

  $('input[name=block_border_width]').on('input keyup paste change', function () {
    var borderSize = parseInt($(this).val());
    var borderColor = $('input[name=block_border_color]').val() || 'transparent';
    setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'border', borderColor + ' solid ' + borderSize + 'px');
  }).change(); // ------------------------------------------------------------
  // Set block border radius.
  // ------------------------------------------------------------

  $('input[name=block_border_radius]').on('input keyup paste change', function () {
    setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'border-radius', "".concat($(this).val(), "px"));
  }).change(); // ------------------------------------------------------------
  // Set block size.
  // ------------------------------------------------------------

  $('input[name=block_size]').on('input keyup paste change', function () {
    var value = parseInt($(this).val());
    var size = value + 'px';

    if (value === 0 || $('select[name=block_display]').val() === 'inline') {
      size = 'auto';
    }

    setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'width', size, false);
    setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'height', size);
  }).change(); // ------------------------------------------------------------
  // Preview headline
  // ------------------------------------------------------------

  var _id = 'hurryt-headline',
      _config = {
    tinymce: {
      toolbar1: 'fontsizeselect forecolor backcolor bold italic link removeformat',
      fontsize_formats: '11px 12px 14px 16px 18px 24px 30px 36px 48px',
      force_br_newlines: false,
      force_p_newlines: false,
      forced_root_block: '',
      content_style: ".mce-content-body {font-size:30px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell,'Helvetica Neue',sans-serif}",
      setup: function setup(editor) {
        editor.on('init', function (e) {
          $('#' + e.target.id + '_ifr').removeAttr('title');
        });
        editor.on('input | paste | keyup | change', function () {
          var content = editor.getContent({
            format: 'raw'
          });
          headlinePreviewRef.html(content);
        });
      }
    },
    quicktags: {
      "buttons": "strong,em,link,del,ins,img"
    },
    mediaButtons: true
  };
  $(document).on('click', '#hurryt-headline-section', function () {
    if (typeof wp.editor.initialize == 'function' || typeof wp.oldEditor.initialize == 'function') {
      if ($(this).next().find('.wp-editor-wrap').length === 0) {
        if (typeof wp.editor.initialize == 'function') {
          wp.editor.initialize(_id, _config);
        } else {
          wp.oldEditor.initialize(_id, _config);
        }
      }
    }
  });
  $(document).on('input paste keyup change', '#hurryt-headline', function () {
    headlinePreviewRef.html($(this).val().replace(/(?:\r\n|\r|\n)/g, '<br>'));
  });

  if (headlinePreviewRef.length) {
    headlinePreviewRef.html($('#hurryt-headline').val().replace(/(?:\r\n|\r|\n)/g, '<br>'));
  } // ------------------------------------------------------------
  // Change headline position.
  // ------------------------------------------------------------


  $('select[name=headline_position]').on('change', function () {
    if (parseInt($(this).val()) === hurrytimer_ajax_object.headline_pos.above_timer) {
      headlinePreviewRef.after(timerPreviewRef);
    } else {
      headlinePreviewRef.before(timerPreviewRef);
    }
  }).change(); // ------------------------------------------------------------
  // Set headline size
  // ------------------------------------------------------------

  $('input[name=headline_size]').on('input keyup paste change', function () {
    setCSS('.hurrytimer-campaign .hurrytimer-headline', headlinePreviewRef, 'font-size', parseInt($(this).val()) + 'px');
  }).change(); // ------------------------------------------------------------
  // Set label case.
  // ------------------------------------------------------------

  $('select[name=label_case]').on('change', function () {
    setCSS('.hurrytimer-campaign .hurrytimer-timer-label', timerLabelPreviewRef, 'text-transform', $(this).val());
  }).change(); // ------------------------------------------------------------
  // Set CTA text.
  // ------------------------------------------------------------

  $('input[name="call_to_action[text]"]').on('change keyup paste input', function () {
    campaignCTA.text($(this).val());
  }).change(); // ------------------------------------------------------------
  // Set CTA horizontal padding
  // ------------------------------------------------------------

  $('input[name="call_to_action[x_padding]"]').on('input keyup paste change', function () {
    var padding = "".concat($(this).val(), "px");
    setCSS('.hurrytimer-campaign .hurrytimer-button', campaignCTA, 'padding-left', padding, false);
    setCSS('.hurrytimer-campaign .hurrytimer-button', campaignCTA, 'padding-right', padding);
  }).change(); // ------------------------------------------------------------
  // Set CTA border radius
  // ------------------------------------------------------------

  $('input[name="call_to_action[border_radius]"]').on('input keyup paste change', function () {
    setCSS('.hurrytimer-campaign .hurrytimer-button', campaignCTA, 'border-radius', "".concat($(this).val(), "px"));
  }).change(); // ------------------------------------------------------------
  // Set CTA vertical padding
  // ------------------------------------------------------------

  $('input[name="call_to_action[y_padding]"]').on('input keyup paste change', function () {
    var padding = parseInt($(this).val()) + 'px';
    setCSS('.hurrytimer-campaign .hurrytimer-button', campaignCTA, 'padding-top', padding, false);
    setCSS('.hurrytimer-campaign .hurrytimer-button', campaignCTA, 'padding-bottom', padding);
  }).change(); // ------------------------------------------------------------
  // Toggle block separator visibility.
  // ------------------------------------------------------------

  $('input[name=block_separator_visibility]').on('change', function () {
    var self = $(this);

    if (!self.is(':checked')) {
      timerSepPreviewRef.addClass('hidden');
      return;
    }

    timerBlockPreviewRef.each(function () {
      if ($(this).hasClass('hidden')) {
        $(this).next().addClass('hidden');
      } else {
        $(this).next().removeClass('hidden');
      }
    });
  }).change();
  $('#hurrytimer-months-visibility').on('change', function () {
    toggleBlockVisibility($(this), campaignInnerPreviewRef.find('[data-block=months]'));
  }).change(); // ------------------------------------------------------------
  // Toggle "days" block visibility.
  // ------------------------------------------------------------

  $('#hurrytimer-days-visibility').on('change', function () {
    toggleBlockVisibility($(this), campaignInnerPreviewRef.find('[data-block=days]'));
  }).change(); // ------------------------------------------------------------
  // Toggle "hours" block visibility
  // ------------------------------------------------------------

  $('#hurrytimer-hours-visibility').on('change', function () {
    toggleBlockVisibility($(this), campaignInnerPreviewRef.find('[data-block=hours]'));
  }).change(); // ---------------------------------------------------------------
  // Toggle "minutes" block visibility
  // ---------------------------------------------------------------

  $('#hurrytimer-minutes-visibility').on('change', function () {
    toggleBlockVisibility($(this), campaignInnerPreviewRef.find('[data-block=minutes]'));
  }).change(); // ---------------------------------------------------------------
  // Toggle "seconds" block visibility
  // ---------------------------------------------------------------

  $('#hurrytimer-seconds-visibility').on('change', function () {
    toggleBlockVisibility($(this), campaignInnerPreviewRef.find('[data-block=seconds]'));
  }).change(); // ---------------------------------------------------------------
  // Toggle "headline" block visibility
  // ---------------------------------------------------------------

  $('#hurrytimer-headline-visibility').on('change', function () {
    if ($(this).is(':checked')) {
      headlinePreviewRef.removeClass('hidden');
    } else {
      headlinePreviewRef.addClass('hidden');
    }
  }).change(); // ---------------------------------------------------------------
  // Toggle "labels" visibility.
  //----------------------------------------------------------------

  $('#hurrytimer-label-visibility').on('change', function () {
    if ($(this).is(':checked')) {
      timerLabelPreviewRef.removeClass('hidden');
    } else {
      timerLabelPreviewRef.addClass('hidden');
    }
  }).change(); // ---------------------------------------------------------------
  // Toggle CTA visibility.
  // ------------------------------------------------------------

  $('#hurrytimer-cta-enabled').on('change', function () {
    toggleBlockVisibility($(this), campaignInnerPreviewRef.find('.hurrytimer-button-wrap'));
  }).change(); // ---------------------------------------------------------------
  // Input slider.
  // ------------------------------------------------------------

  var blockSizeSliderElement;
  var blockSizeInputElement;
  $('.hurrytimer-input-slider').each(function () {
    var self = $(this);
    var boundInputElement = $('input[name="' + self.data('input-name') + '"]');
    var min = parseInt(boundInputElement.attr('min')) || 0;
    var max = parseInt(boundInputElement.attr('max')) || 100;

    if (boundInputElement.attr('name') === 'block_size') {
      min = parseInt($('input[name=digit_size]').val()) || min;
      blockSizeSliderElement = self;
      blockSizeInputElement = boundInputElement;
    }

    self.slider({
      slide: function slide(_, ui) {
        boundInputElement.val(ui.value);
        boundInputElement.trigger('input');

        if (boundInputElement.attr('name') === 'digit_size') {
          $('input[name=block_size]').attr('min', ui.value);
          blockSizeSliderElement.slider('option', 'min', ui.value);

          if (blockSizeInputElement.val() < ui.value) {
            blockSizeSliderElement.slider('option', 'value', ui.value);
            blockSizeInputElement.val(ui.value);
            blockSizeInputElement.trigger('input');
          }
        }
      },
      max: max,
      min: min,
      value: boundInputElement.val()
    });
  }); // ------------------------------------------------------------
  // ------------------------------------------------------------
  // Add new action
  // ------------------------------------------------------------

  $('#hurrytimer-new-action').on('click', function () {
    //removeif(pro)
    if ($('.hurrytimer-action-block').length === 1) {
      return;
    } // endremoveif(pro)


    var action = $('.hurrytimer-action-block').last().clone(true, true);
    action.find('.hurrytimer-action-block-subfields').addClass('hidden');
    var fields = action.find(':input');

    for (var i = 0; i < fields.length; i++) {
      fields[i].name = fields[i].name.replace(/actions\[(\d+)\]\[(\w+)\]/, function (fm, i, name) {
        return 'actions[' + ++i + '][' + name + ']';
      });
    }

    $(this).parent().before(action);

    if ($('.hurrytimer-action-block').length === 1) {
      $('.hurrytimer-action-block').find('.hurrytimer-delete-action').addClass('hidden');
    } else {
      $('.hurrytimer-action-block').find('.hurrytimer-delete-action').removeClass('hidden');
    }
  }); // ------------------------------------------------------------
  // Handle action selection
  // ------------------------------------------------------------

  $('#hurrytimer-actions').on('change', '.hurrytimer-action-select', function () {
    handleActionChange($(this));
  });
  $('.hurrytimer-action-select').each(function () {
    handleActionChange($(this));
  });

  function handleActionChange(element) {
    // removeIf(pro)
    if (element.find(':selected').data('pro-feat') !== undefined) {
      $('.hurryt-pro-feat').removeClass('hidden');
      return;
    } else {
      $('.hurryt-pro-feat').addClass('hidden');
    } // endRemoveIf(pro)


    var action = element.find('option:selected');

    if (+action.val() === 4 && +$('.hurrytimer-mode:checked').val() === 2) {
      element.parent().find('.hurryt-compat-info').removeClass('hidden');
    } else {
      element.parent().find('.hurryt-compat-info').addClass('hidden');
    }

    var block = element.closest('.hurrytimer-action-block');
    block.find('.hurrytimer-action-block-subfields').addClass('hidden');
    block.find('.' + action.data('subfields')).removeClass('hidden');
  } // ------------------------------------------------------------
  // Handle action deletion
  // ------------------------------------------------------------


  $('#hurrytimer-actions').on('click', '.hurrytimer-delete-action', function () {
    if ($('.hurrytimer-action-block').length === 1) return;
    $(this).closest('.hurrytimer-action-block').remove();

    if ($('.hurrytimer-action-block').length === 1) {
      $('.hurrytimer-action-block').find('.hurrytimer-delete-action').addClass('hidden');
    } else {
      $('.hurrytimer-action-block').find('.hurrytimer-delete-action').removeClass('hidden');
    }
  }); // ------------------------------------------------------------
  // Set "days" label
  // ------------------------------------------------------------

  $('input[name="labels[days]"]').on('input keyup paste', function () {
    campaignInnerPreviewRef.find('[data-block=days] .hurrytimer-timer-label').text($(this).val());
  }).trigger('input');
  $('input[name="labels[months]"]').on('input keyup paste', function () {
    campaignInnerPreviewRef.find('[data-block=months] .hurrytimer-timer-label').text($(this).val());
  }).trigger('input'); // ------------------------------------------------------------
  // Set "hours" label
  // ------------------------------------------------------------

  $('input[name="labels[hours]"]').on('input keyup paste', function () {
    campaignInnerPreviewRef.find('[data-block=hours] .hurrytimer-timer-label').text($(this).val());
  }).trigger('input'); // ------------------------------------------------------------
  // Set "minutes" label
  // ------------------------------------------------------------

  $('input[name="labels[minutes]"]').on('input keyup paste', function () {
    campaignInnerPreviewRef.find('[data-block=minutes] .hurrytimer-timer-label').text($(this).val());
  }).trigger('input'); // ------------------------------------------------------------
  // Set "seconds" label
  // ------------------------------------------------------------

  $('input[name="labels[seconds]"]').on('input keyup paste', function () {
    campaignInnerPreviewRef.find('[data-block=seconds] .hurrytimer-timer-label').text($(this).val());
  }).trigger('input'); // ------------------------------------------------------------
  // Compaign display
  // ------------------------------------------------------------

  $('select[name=campaign_display]').on('change', function () {
    var blockMarginBottom = timerBlockPreviewRef.css('margin-bottom');

    if ($(this).val() === 'inline') {
      campaignInnerPreviewRef.addClass('hurrytimer-inline');
      setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'margin-bottom', '0');
    } else {
      campaignInnerPreviewRef.removeClass('hurrytimer-inline');
      setCSS('.hurrytimer-campaign .hurrytimer-timer-block', timerBlockPreviewRef, 'margin-bottom', blockMarginBottom);
    }

    $('input[name="call_to_action[spacing]"]').change();
    $('input[name="headline_spacing"]').change();
  }).change(); // ------------------------------------------------------------
  // Compaign alignment
  // ------------------------------------------------------------

  $('select[name=campaign_align]').on('change', function () {
    var value = $(this).val();
    setCSS('.hurrytimer-campaign', campaignInnerPreviewRef, 'text-align', value);

    if ($('select[name=campaign_display]').val() === 'inline') {
      return false;
    }

    switch (value) {
      case 'left':
        setCSS('.hurrytimer-campaign .hurrytimer-timer', timerPreviewRef, 'justify-content', 'flex-start');
        break;

      case 'right':
        setCSS('.hurrytimer-campaign .hurrytimer-timer', timerPreviewRef, 'justify-content', 'flex-end');
        break;

      case 'center':
        setCSS('.hurrytimer-campaign .hurrytimer-timer', timerPreviewRef, 'justify-content', 'center');
        break;
    }
  }).change();
  $('.hurryt-fullscreen').on('click', function (e) {
    e.preventDefault();

    if ($(this).hasClass('on')) {
      campaignPreviewRef.removeClass('hurryt-preview-fullscreen');
      $(this).removeClass('on');

      if (campaignPreviewRef.hasClass('hurrytimer-sticky')) {
        setCSS('#hurrytimer-campaign-preview', campaignPreviewRef, 'position', 'relative');
      }
    } else {
      campaignPreviewRef.addClass('hurryt-preview-fullscreen');
      $(this).addClass('on');
    }
  });
  $('select[name="sticky_bar_pages[]"]').select2({
    placeholder: 'Search...'
  }); // Bind select2 to coupons select.

  $('.hurrytimer-action-wc-coupon').each(function () {
    $(this).select2({
      placeholder: 'Search coupon...',
      width: '100%',
      minimumInputLength: 2,
      ajax: {
        url: hurrytimer_ajax_object.ajax_url,
        dataType: 'json',
        data: function data(params) {
          return {
            action: 'hurrytimer/search_wc_coupon',
            search: params.term,
            exclude: $(this).val(),
            type: 'public',
            nonce: hurrytimer_ajax_object.ajax_nonce
          };
        }
      }
    });
  });
  $('select[name="sticky_bar_pages[]"]').on('change', function () {
    if ($(this).val() === null) {
      $('input[name="sticky_bar_pages[]"]').val([]);
    } else {
      $('input[name="sticky_bar_pages[]"]').val("[".concat($(this).val(), "]"));
    }
  });
  $('select[name="sticky_exclude_pages[]"]').select2({
    placeholder: 'Search...'
  });
  $('select[name="sticky_exclude_pages[]"]').on('change', function () {
    if ($(this).val() === null) {
      $('input[name="sticky_exclude_pages[]"]').val([]);
    } else {
      $('input[name="sticky_exclude_pages[]"]').val("[".concat($(this).val(), "]"));
    }
  });
  $('input[type="checkbox"][name="sticky_bar_show_on_all_pages"]').on('change', function () {
    if ($('input[type="hidden"][name="sticky_bar_show_on_all_pages"]').length === 0) {
      $(this).after('<input type="hidden" name="sticky_bar_show_on_all_pages" value="yes" />');
    }

    if ($(this).is(':checked')) {
      $('input[type="hidden"][name="sticky_bar_show_on_all_pages"]').val('yes');
      $('select[name="sticky_bar_pages[]"').attr('disabled', true);
    } else {
      $('input[type="hidden"][name="sticky_bar_show_on_all_pages"]').val('no');
      $('select[name="sticky_bar_pages[]"').attr('disabled', false);
    }
  }).change();
  $(document).on('change', '#hurrytimer-evergreen-restart', function () {
    if ($(this).val() == 4) {
      $('#hurrytimer-evergreen-restart-duration').removeClass('hidden'); // removeIf(pro)

      $('#hurrytimer-restart-after-feature-unlock').removeClass('hidden'); // endRemoveIf(pro)
    } else {
      $('#hurrytimer-evergreen-restart-duration').addClass('hidden'); // removeIf(pro)

      $('#hurrytimer-restart-after-feature-unlock').addClass('hidden'); // endRemoveIf(pro)
    }
  });
  var $selectedEvergreenRestart = $('#hurrytimer-evergreen-restart').find('option:selected');

  if ($selectedEvergreenRestart.val() == 4) {
    $('#hurrytimer-evergreen-restart-duration').removeClass('hidden');
    $('#hurrytimer-restart-after-feature-unlock').removeClass('hidden');
  } else {
    $('#hurrytimer-evergreen-restart-duration').addClass('hidden');
    $('#hurrytimer-restart-after-feature-unlock').addClass('hidden');
  } //  Display tooltips


  $('#hurrytimer-settings').tooltip({
    tooltipClass: 'hurryt-tooltip',
    content: function content() {
      return $(this).prop('title');
    },
    position: {
      my: 'center bottom-20',
      at: 'center top',
      using: function using(position, feedback) {
        $(this).css(position);
        $('<div>').addClass('arrow').addClass(feedback.vertical).addClass(feedback.horizontal).appendTo(this);
      }
    }
  }); // Toggle display

  $('.hurryt-sticky-bar-display-on').on('change', function () {
    if ($(this).val() === 'specific_pages') {
      $('.hurryt_sticky_bar_pages').removeClass('hidden');
      $('.hurryt_sticky_exclude_pages').addClass('hidden');
    } else if ($(this).val() === 'exclude_pages') {
      $('.hurryt_sticky_bar_pages').addClass('hidden');
      $('.hurryt_sticky_exclude_pages').removeClass('hidden');
    } else {
      $('.hurryt_sticky_bar_pages').addClass('hidden');
      $('.hurryt_sticky_exclude_pages').addClass('hidden');
    }
  });

  if ($('.hurryt-sticky-bar-display-on:checked').val() === 'specific_pages') {
    $('.hurryt_sticky_bar_pages').removeClass('hidden');
    $('.hurryt_sticky_exclude_pages').addClass('hidden');
  } else if ($('.hurryt-sticky-bar-display-on:checked').val() === 'exclude_pages') {
    $('.hurryt_sticky_bar_pages').addClass('hidden');
    $('.hurryt_sticky_exclude_pages').removeClass('hidden');
  } else {
    $('.hurryt_sticky_bar_pages').addClass('hidden');
    $('.hurryt_sticky_exclude_pages').addClass('hidden');
  }

  $('input[name="sticky_bar_dismissible"]').on('change', function () {
    if ($(this).is(':checked')) {
      $('input[name="sticky_bar_dismiss_timeout"]').prop('disabled', false);
    } else {
      $('input[name="sticky_bar_dismiss_timeout"]').prop('disabled', true);
    }
  }).trigger('change');
  /**
   * Reset evergreen countdown timers for all visitors
   */

  var resetAllButton = document.getElementById('hurrytResetAll');

  if (resetAllButton) {
    resetAllButton.addEventListener('click', function (e) {
      e.preventDefault();

      var _confirm = confirm('Are you sure?');

      if (_confirm) {
        window.location.href = resetAllButton.getAttribute('data-url');
      }
    });
  } // Failed to remove? retry with default options.


  var resetCurrentButton = document.getElementById('hurrytResetCurrent');

  if (resetCurrentButton) {
    resetCurrentButton.addEventListener('click', function (e) {
      e.preventDefault();
      var options = {};

      if (hurrytimer_ajax_object.COOKIEPATH) {
        options.path = hurrytimer_ajax_object.COOKIEPATH;
      }

      if (hurrytimer_ajax_object.COOKIE_DOMAIN) {
        options.domain = hurrytimer_ajax_object.COOKIE_DOMAIN;
      }

      var campaignCookieName = resetCurrentButton.getAttribute('data-cookie');
      Cookies.remove(campaignCookieName, options); // Failed to remove? retry with default options.

      if (Cookies.get(campaignCookieName)) {
        Cookies.remove(campaignCookieName);
      }

      Cookies.remove("_ht_CDT-".concat(resetCurrentButton.getAttribute('data-id'), "_dismissed"));
      window.location.href = resetCurrentButton.getAttribute('data-url');
    });
  }

  var resetAllEvergreenCampaignsButtons = document.querySelectorAll('.hurrytResetAllEvergreenCampaigns');

  if (resetAllEvergreenCampaignsButtons) {
    var _iterator = _createForOfIteratorHelper(resetAllEvergreenCampaignsButtons),
        _step;

    try {
      var _loop = function _loop() {
        var button = _step.value;
        button.addEventListener('click', function (e) {
          e.preventDefault();

          if (confirm('Are you sure?')) {
            var cookies = Cookies.get();
            var options = {};

            if (hurrytimer_ajax_object.COOKIEPATH) {
              options.path = hurrytimer_ajax_object.COOKIEPATH;
            }

            if (hurrytimer_ajax_object.COOKIE_DOMAIN) {
              options.domain = hurrytimer_ajax_object.COOKIE_DOMAIN;
            }

            for (var name in cookies) {
              if (name.startsWith(button.getAttribute('data-cookie-prefix'))) {
                Cookies.remove(name, options);

                if (Cookies.get(name)) {
                  Cookies.remove(name);
                }
              }
            }

            window.location.href = button.getAttribute('data-url');
          }
        });
      };

      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        _loop();
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
  }

  var recurringFrequencyElement = document.getElementById('hurrytRecurringFrequency');
  var recurringIntervalElement = document.getElementById('hurrytRecurringInterval');

  if (recurringFrequencyElement) {
    recurringFrequencyElement.onchange = function (e) {
      return toggleRecurringDuration(e.target.value);
    };

    toggleRecurringDuration(recurringFrequencyElement.value);
  }

  function toggleRecurringDuration(value) {
    var monthsInputElement = document.getElementById('hurrytRecurringMonths');
    var daysInputElement = document.getElementById('hurrytRecurringDays');
    var hoursInputElement = document.getElementById('hurrytRecurringHours');
    var minutesInputElement = document.getElementById('hurrytRecurringMinutes');
    var secondsInputElement = document.getElementById('hurrytRecurringSeconds');
    var pauseRecurringHoursElement = document.getElementById('hurrytRecurringPauseHours');
    var pauseRecurringMinutesElement = document.getElementById('hurrytRecurringPauseMinutes');
    var pauseRecurringDaysElement = document.getElementById('hurrytRecurringPauseDays');
    var monthsDayTypeElement = document.getElementById('hurrytRecurMonthlyDayType');

    if (value !== 'weekly' && $('input[name="recurring_days[]"]:checked').length < 7) {
      $('#hurrytimer-recurring-unselected-days-action').show();
    } else {
      $('#hurrytimer-recurring-unselected-days-action').hide();
    }

    if (value !== 'monthly') {
      document.getElementById('hurrytRecurDaysList').classList.remove('hidden');
      monthsDayTypeElement.classList.add('hidden');
      $('#ht-recurring-duration').removeClass('hidden');
      $('#ht-recurring-duration-option').addClass('hidden');
    }

    switch (value) {
      case 'minutely':
        daysInputElement.value = 0;
        hoursInputElement.value = 0;
        hoursInputElement.parentNode.classList.add('hidden');
        daysInputElement.parentNode.classList.add('hidden');
        pauseRecurringMinutesElement.parentNode.classList.remove('hidden');
        pauseRecurringHoursElement.parentNode.classList.add('hidden');
        pauseRecurringHoursElement.value = 0;
        pauseRecurringDaysElement.parentNode.classList.add('hidden');
        pauseRecurringDaysElement.value = 0;
        break;

      case 'hourly':
        daysInputElement.value = 0;
        daysInputElement.parentNode.classList.add('hidden');
        hoursInputElement.parentNode.classList.remove('hidden');
        pauseRecurringHoursElement.parentNode.classList.remove('hidden');
        pauseRecurringMinutesElement.parentNode.classList.remove('hidden');
        pauseRecurringDaysElement.parentNode.classList.add('hidden');
        pauseRecurringDaysElement.value = 0;
        break;

      case 'daily':
      case 'weekly':
        daysInputElement.parentNode.classList.remove('hidden');
        hoursInputElement.parentNode.classList.remove('hidden');
        pauseRecurringMinutesElement.parentNode.classList.remove('hidden');
        pauseRecurringHoursElement.parentNode.classList.remove('hidden');
        pauseRecurringDaysElement.parentNode.classList.remove('hidden');
        break;

      case 'monthly':
        daysInputElement.parentNode.classList.remove('hidden');
        hoursInputElement.parentNode.classList.remove('hidden');
        document.getElementById('hurrytRecurDaysList').classList.add('hidden');
        monthsDayTypeElement.classList.remove('hidden');
        $('#ht-recurring-duration-option').removeClass('hidden').addClass('hurryt-mb-3');

        if ($('input[name="recurring_duration_option"]:checked').val() === 'none') {
          $('#ht-recurring-duration').addClass('hidden');
        } else {
          $('#ht-recurring-duration').removeClass('hidden');
        }

        pauseRecurringMinutesElement.parentNode.classList.remove('hidden');
        pauseRecurringHoursElement.parentNode.classList.remove('hidden');
        pauseRecurringDaysElement.parentNode.classList.remove('hidden');
        break;

      default:
        break;
    }
  }

  $('body').on('change', 'input[name="recurring_duration_option"]', function () {
    if ($(this).val() === 'none') {
      $('#ht-recurring-duration').addClass('hidden');
    } else {
      $('#ht-recurring-duration').removeClass('hidden');
    }
  });
  $('body').on('click', '.hurryt-add-wc-condition-group', function () {
    var _self = $(this);

    _self.prop('disabled', true);

    _self.next('.spinner').addClass('is-active');

    $.get(hurrytimer_ajax_object.ajax_url, {
      action: 'add_wc_condition_group',
      nonce: hurrytimer_ajax_object.ajax_nonce
    }, function (html) {
      _self.before(html);
    }).always(function () {
      _self.prop('disabled', false);

      _self.next('.spinner').removeClass('is-active');
    });
  });
  $('body').on('click', '.hurryt-add-wc-condition', function () {
    var _self = $(this);

    _self.prop('disabled', true);

    $.get(hurrytimer_ajax_object.ajax_url, {
      action: 'add_wc_condition',
      nonce: hurrytimer_ajax_object.ajax_nonce,
      group_id: $(this).closest('.hurryt-wc-condition-group').data('group-id')
    }, function (html) {
      _self.parent().after(html);
    }).always(function () {
      _self.prop('disabled', false);
    });
  });
  $('body').on('click', '.hurryt-delete-wc-condition', function () {
    var _self = $(this);

    if (_self.closest('.hurryt-wc-condition-group').find('.hurryt-wc-condition').length === 1) {
      _self.closest('.hurryt-wc-condition-group').remove();
    } else {
      $(this).parent().remove();
    }
  });
  $('body').on('change', '.hurryt-wc-condition-key', function () {
    var _self = $(this);

    var $value = _self.parent().find('.hurryt-wc-condition-value');

    var $operator = _self.parent().find('.hurryt-wc-condition-operator');

    $value.prop('disabled', true);
    $operator.prop('disabled', true);
    $.get(hurrytimer_ajax_object.ajax_url, {
      action: 'load_wc_condition',
      nonce: hurrytimer_ajax_object.ajax_nonce,
      condition_key: _self.val(),
      group_id: _self.closest('.hurryt-wc-condition-group').data('group-id')
    }, function (html) {
      _self.parent().replaceWith(html);
    }, 'html').always(function () {
      $value.prop('disabled', false);
      $operator.prop('disabled', false);
    });
  }); // open headline tab

  $(document).on('click', '.hurryt-open-hl-tab', function (e) {
    e.preventDefault();
    var $tabs = $('.hurrytimer-tabbar li');
    $tabs.removeClass('active');
    $tabs.last().addClass('active');
    $('.hurrytimer-tabcontent').removeClass('active');
    $('#hurrytimer-tabcontent-styling').addClass('active');
    $('.hurrytimer-style-control-group').removeClass('active');
    $('.hurryt-subtab-hl').addClass('active');
    $('#hurryt-headline').focus();
  }); // removeIf(pro)

  $(document).on('click', '#hurrytUserSessionWrap', function () {
    $('#hurrytUserSessionUpgradeNotice').removeClass('hidden');
  }); // endRemoveIf(pro)

  $(document).on('click', '#hurryt-dismiss-headline-moved-notice', function () {
    $(this).parent().remove();
    Cookies.set('hurryt_headline_moved_notice_dismissed', '1', {
      expires: 365
    });
    $.post(hurrytimer_ajax_object.ajax_url, {
      action: 'hurryt_dismiss_headline_moved_notice',
      nonce: hurrytimer_ajax_object.ajax_nonce
    });
  });
  $('input[name="recurring_days[]"]').on('change', function () {
    var selected = $('input[name="recurring_days[]"]:checked').length;

    if (selected === 7) {
      $('#hurrytimer-recurring-unselected-days-action').hide();
    } else if ($('#hurrytRecurringFrequency').val() !== 'weekly') {
      $('#hurrytimer-recurring-unselected-days-action').show();
    }
  }).change();
  $(document).on('input keyup paste change', '#hurrytRecurringInterval', function () {
    $('#ht-monthly-recur-interval').text($(this).val());
  });
})(jQuery);
//# sourceMappingURL=admin.js.map
