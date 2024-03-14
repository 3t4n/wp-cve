'use strict';

(function($) {
  $(function() {
    wpclv_init();
    wpclv_terms_init();
  });

  // search product
  $(document).on('change', '.wpclv-product-search', function() {
    var _val = $(this).val();

    if (Array.isArray(_val)) {
      $(this).
          closest('.wpclv_link').
          find('.wpclv-products').
          val(_val.join()).trigger('change');
    } else {
      if (_val === null) {
        $(this).
            closest('.wpclv_link').
            find('.wpclv-products').
            val('').trigger('change');
      } else {
        $(this).
            closest('.wpclv_link').
            find('.wpclv-products').
            val(String(_val)).trigger('change');
      }
    }
  });

  // search category
  $(document).on('change', '.wpclv-category-search', function() {
    var _val = $(this).val();

    if (Array.isArray(_val)) {
      $(this).
          closest('.wpclv_link').
          find('.wpclv-categories').
          val(_val.join()).trigger('change');
    } else {
      if (_val === null) {
        $(this).
            closest('.wpclv_link').
            find('.wpclv-categories').
            val('').trigger('change');
      } else {
        $(this).
            closest('.wpclv_link').
            find('.wpclv-categories').
            val(String(_val)).trigger('change');
      }
    }
  });

  $(document).on('change', '.wpclv_display_checkbox', function() {
    if ($(this).prop('checked')) {
      $(this).
          closest('.wpclv-attribute').
          find('.wpclv_display_checkbox').
          not(this).
          prop('checked', false);
    }
  });

  $(document).on('change', '.wpclv-source', function() {
    wpclv_source($(this));
    wpclv_terms_init();
  });

  // search terms
  $(document).on('change', '.wpclv-terms-select', function() {
    var $this = $(this);
    var val = $this.val();
    var source = $this.closest('.wpclv_link').find('.wpclv-source').val();

    if (Array.isArray(val)) {
      $this.
          closest('.wpclv_link').
          find('.wpclv-terms-val').
          val(val.join()).trigger('change');
    } else {
      if (val === null) {
        $this.
            closest('.wpclv_link').
            find('.wpclv-terms-val').
            val('').trigger('change');
      } else {
        $this.
            closest('.wpclv_link').
            find('.wpclv-terms-val').
            val(String(val)).trigger('change');
      }
    }

    $this.data(source, val.join());
  });

  function wpclv_init() {
    $('.wpclv-attributes').sortable({
      items: '.wpclv-attribute',
      cursor: 'move',
      scrollSensitivity: 40,
      forcePlaceholderSize: true,
      forceHelperSize: false,
      helper: 'clone',
      opacity: 0.65,
    });

    $('.wpclv-source-hide').hide();

    $('.wpclv-source').each(function() {
      wpclv_source($(this));
    });
  }

  function wpclv_terms_init() {
    var $terms = $('.wpclv-terms-select');
    var source = $terms.closest('.wpclv_link').find('.wpclv-source').val();

    $terms.selectWoo({
      ajax: {
        url: ajaxurl, dataType: 'json', delay: 250, data: function(params) {
          return {
            q: params.term, action: 'wpclv_search_term', taxonomy: source,
          };
        }, processResults: function(data) {
          var options = [];
          if (data) {
            $.each(data, function(index, text) {
              options.push({id: text[0], text: text[1]});
            });
          }
          return {
            results: options,
          };
        }, cache: true,
      }, minimumInputLength: 1,
    });

    if ((typeof $terms.data(source) === 'string' ||
        $terms.data(source) instanceof String) && $terms.data(source) !== '') {
      $terms.val($terms.data(source).split(',')).change();
    } else {
      $terms.val([]).change();
    }
  }

  function wpclv_source($this) {
    var source = $this.find(':selected').val();
    var label = $this.find(':selected').text();

    $this.closest('.wpclv_link').find('.wpclv-source-hide').hide();

    if (source === 'products' || source === 'categories' || source === 'tags') {
      $this.closest('.wpclv_link').find('.wpclv-source-' + source).show();
    } else {
      $this.closest('.wpclv_link').
          find('.wpclv-source-terms-label').
          text(label);
      $this.closest('.wpclv_link').find('.wpclv-source-terms').show();
    }
  }
})(jQuery);