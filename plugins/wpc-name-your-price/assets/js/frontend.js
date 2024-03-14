'use strict';

(function($) {
  var woonp_timeout = null;

  $(function() {
    $('.woonp-input').each(function() {
      woonp_init($(this));
    });
  });

  $(document).on('woosq_loaded', function() {
    woonp_init($('#woosq-popup .woonp-input'));
  });

  $(document).on('keyup click', '.woonp-input', function() {
    var $this = $(this);

    if (woonp_timeout != null) clearTimeout(woonp_timeout);
    woonp_timeout = setTimeout(woonp_init, 1000, $this);
  });

  function woonp_init($input) {
    var val = parseFloat($input.val());
    var min = parseFloat($input.attr('min'));
    var max = parseFloat($input.attr('max'));
    var step = parseFloat($input.attr('step'));

    if ((val !== '') && !isNaN(val)) {
      var fix = Math.pow(10, Number(woonp_decimal_places(step)) + 2);

      if ((step === '') || isNaN(step) || step <= 0) {
        step = 1;
      }

      if ((min === '') || isNaN(min) || min < 0) {
        min = step;
      }

      if (val < 0 || val < min) {
        val = min;
      }

      var remainder_before = woonp_float_remainder(
          (val * fix - min * fix) / fix, step);

      if (remainder_before > 0) {
        if (woonp_vars.rounding === 'up') {
          val = (val * fix - remainder_before * fix + step * fix) / fix;
        } else {
          val = (val * fix - remainder_before * fix) / fix;
        }
      }

      if (!isNaN(min) && (val < min)) {
        val = min;
      }

      if (!isNaN(max) && (val > max)) {
        val = max;
      }

      var remainder = woonp_float_remainder((val * fix - min * fix) / fix,
          step);

      if (remainder > 0) {
        val = (val * fix - remainder * fix) / fix;
      }

      $input.val(val.toFixed(woonp_vars.price_decimals)).trigger('change');
    }

    $(document.body).trigger('woonp_init', [$input, val, min, max, step]);
  }

  function woonp_decimal_places(num) {
    var match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);

    if (!match) {
      return 0;
    }

    return Math.max(
        0,
        // Number of digits right of decimal point.
        (match[1] ? match[1].length : 0)
        // Adjust for scientific notation.
        - (match[2] ? +match[2] : 0));
  }

  function woonp_float_remainder(val, step) {
    var valDecCount = (val.toString().split('.')[1] || '').length;
    var stepDecCount = (step.toString().split('.')[1] || '').length;
    var decCount = valDecCount > stepDecCount ? valDecCount : stepDecCount;
    var valInt = parseInt(val.toFixed(decCount).replace('.', ''));
    var stepInt = parseInt(step.toFixed(decCount).replace('.', ''));
    return (valInt % stepInt) / Math.pow(10, decCount);
  }
})(jQuery);