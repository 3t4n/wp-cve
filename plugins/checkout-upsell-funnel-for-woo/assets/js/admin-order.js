jQuery(document).ready(function () {
    'use strict';
    jQuery('.tab:not(.vi-wcuf-tab-rule)').each(function () {
        let tab = jQuery(this);
        tab.find('.vi-ui.checkbox').unbind().checkbox();
        tab.find('.vi-ui.dropdown').unbind().dropdown();
        tab.find('.vi-ui.accordion').villatheme_accordion('refresh');
        tab.find('input[type="checkbox"]').unbind().on('change', function () {
            if (jQuery(this).prop('checked')) {
                jQuery(this).parent().find('input[type="hidden"]').val('1');
            } else {
                jQuery(this).parent().find('input[type="hidden"]').val('');
            }
        });
    });
    //before save settings
    jQuery('.vi-wcuf-save').on('click', function () {
        jQuery(this).addClass('loading');
        jQuery('.vi-wcuf-accordion-wrap').removeClass('vi-wcuf-accordion-wrap-warning');
        let nameArr = jQuery('input[name="ob_names[]"]');
        let z, v;
        for (z = 0; z < nameArr.length; z++) {
            if (!nameArr.eq(z).val()) {
                alert('Name cannot be empty!');
                jQuery('.vi-wcuf-accordion-' + z).addClass('vi-wcuf-accordion-wrap-warning');
                jQuery('.vi-wcuf-save').removeClass('loading');
                return false;
            }
        }

        for (z = 0; z < nameArr.length - 1; z++) {
            for (v = z + 1; v < nameArr.length; v++) {
                if (nameArr.eq(z).val() === nameArr.eq(v).val()) {
                    alert("Names are unique!");
                    jQuery('.vi-wcuf-accordion-' + v).addClass('vi-wcuf-accordion-wrap-warning');
                    jQuery('.vi-wcuf-save').removeClass('loading');
                    return false;
                }
            }
        }
        jQuery(this).attr('type', 'submit');
    });
});
viwcuf_rule_init.prototype.change_value = function (rule) {
    rule.find('.vi-wcuf-pd-condition-ob_product.vi-wcuf-search-select2:not(.vi-wcuf-search-select2-init)').each(function () {
        viwcuf_rule_child_init.prototype.select2(rule, jQuery(this), true);
    });
    rule.find('.vi-wcuf-ob_names').unbind().on('keyup', function () {
        jQuery(this).closest('.vi-wcuf-accordion-wrap').find('.vi-wcuf-accordion-name').html(jQuery(this).val());
    });
    viwcuf_set_value_number(rule);
    viwcuf_color_picker(rule);
};
viwcuf_rule_init.prototype.checkbox = function (rule) {
    rule.find('input[type="checkbox"]').unbind().on('change', function () {
        if (jQuery(this).prop('checked')) {
            jQuery(this).parent().find('input[type="hidden"]').val('1');
        } else {
            jQuery(this).parent().find('input[type="hidden"]').val('');
        }
    });
};
viwcuf_rule_init.prototype.dropdown = function (rule) {
    rule.find('.vi-wcuf-ob_discount_type').dropdown({
        onChange: function (val) {
            val = parseInt(val);
            rule.find('.vi-wcuf-discount-amount-notice').addClass('vi-wcuf-hidden');
            rule.find('.vi-wcuf-discount-amount-notice.vi-wcuf-discount-amount-notice-'+val).removeClass('vi-wcuf-hidden');
            if (val) {
                let max = val===1 || val === 3 ? 100 : '';
                jQuery(this).parent().find('.vi-wcuf-ob_discount_amount').removeClass('vi-wcuf-hidden').attr('max',max);
            } else {
                jQuery(this).parent().find('.vi-wcuf-ob_discount_amount').addClass('vi-wcuf-hidden');
            }
        }
    });
};