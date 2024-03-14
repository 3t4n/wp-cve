(function ($) {

/**
 * Provide the summary information for the block settings vertical tabs.
 */
Drupal.behaviors.intelSettingsSummary = {
  attach: function (context) {
    if (typeof jQuery.fn.drupalSetSummary == 'undefined') {
      return;
    }

    $('fieldset#edit-intel', context).drupalSetSummary(Drupal.behaviors.intelSettingsSummary.setSummary());

    // determine if value field should be shown for existing attributes
    $('#edit-field-page-attribute-col .form-select').each(function (index, value) {
      Drupal.behaviors.intelSettingsSummary.setPageAttrDisplay.call(value);
    });
    $('#edit-field-visitor-attribute-col .form-select').each(function (index, value) {
      Drupal.behaviors.intelSettingsSummary.setVisitorAttrDisplay.call(value);
    });

    // set callbacks to determine if value field should be shown on change
    $('#edit-field-page-attribute-col .form-select').change(function (context) {
      Drupal.behaviors.intelSettingsSummary.setPageAttrDisplay.call(this);
      $('fieldset#edit-intel', context).drupalSetSummary(Drupal.behaviors.intelSettingsSummary.setSummary());
    });
    $('#edit-field-visitor-attribute-col .form-select').change(function (context) {
      Drupal.behaviors.intelSettingsSummary.setVisitorAttrDisplay.call(this);
      $('fieldset#edit-intel', context).drupalSetSummary(Drupal.behaviors.intelSettingsSummary.setSummary());
    });


  },
  setPageAttrDisplay: function () {
        console.log(this);
        var a = this.value.split('.');
        var id = this.id;
        if (a[0] in Drupal.settings.intel.page_attribute_types) {
            id = id.split('-');
            id[id.length-1] = 'value';
            id = id.join('-');
            console.log(id);
            if ((Drupal.settings.intel.page_attribute_types[a[0]] == 'item')
                || (Drupal.settings.intel.page_attribute_types[a[0]] == 'value')
                || (Drupal.settings.intel.page_attribute_types[a[0]] == 'scalar')
                || (Drupal.settings.intel.page_attribute_types[a[0]] == 'vector')) {
                $('#' + id).css('display', 'block');
            }
            else {
                $('#' + id).css('display', 'none');
            }
        }
  },
  setVisitorAttrDisplay: function () {
      console.log(this);
      var a = this.value.split('.');
      var id = this.id;
      if (a[0] in Drupal.settings.intel.visitor_attribute_types) {
          id = id.split('-');
          id[id.length-1] = 'value';
          id = id.join('-');
          console.log(id);
          if ((Drupal.settings.intel.visitor_attribute_types[a[0]] == 'item')
              || (Drupal.settings.intel.visitor_attribute_types[a[0]] == 'value')
              || (Drupal.settings.intel.visitor_attribute_types[a[0]] == 'scalar')
              || (Drupal.settings.intel.visitor_attribute_types[a[0]] == 'vector')) {
              $('#' + id).css('display', 'block');
          }
          else {
              $('#' + id).css('display', 'none');
          }
      }
  },
  setSummary: function () {
      var vals = [];
      var a, c;
      
      a = jQuery('.field-name-field-intel-event-col .field-type-list-text');
      c = a.length - 1;
      if (jQuery(a[c]).find(':selected').val() != '_none') {
        c++;
      }
      if (c > 0) {
        vals.push(c + ' ' +Drupal.t(' custom events'))
      }
      
      a = jQuery('.field-name-field-visitor-attribute-col .field-type-list-text');
      c = a.length - 1;
      if (jQuery(a[c]).find(':selected').val() != '_none') {
        c++;
      }
      if (c > 0) {
        vals.push(c + ' ' +Drupal.t(' custom visitor attr'))
      }
      
      a = jQuery('.field-name-field-page-attribute-col .field-type-list-text');
      c = a.length - 1;
      if (jQuery(a[c]).find(':selected').val() != '_none') {
        c++;
      }
      if (c > 0) {
        vals.push(c + ' ' +Drupal.t(' custom page attr'))
      }
      if (!vals.length) {
        vals.push(Drupal.t('No custom settings'));
      }
      return vals.join(', ');    
  }


};

})(jQuery);