jQuery(document).ready(function () {
    'use strict';
    viwcuf_init_tab();
    jQuery('.vi-wcuf-rules-wrap').sortable({
        connectWith: ".vi-wcuf-rules-wrap",
        handle: ".vi-wcuf-accordion-info",
        cancel: ".vi-wcuf-active-wrap,.vi-wcuf-accordion-action,.title,.content",
        placeholder: "vi-wcuf-placeholder",
    });
    jQuery('.vi-wcuf-rules-wrap .vi-wcuf-accordion-rule-wrap:not(.vi-wcuf-accordion-rule-wrap-init)').each(function () {
        jQuery(this).addClass('vi-wcuf-accordion-rule-wrap-init').viwcuf_rule();
    });
});
function viwcuf_init_tab(tab_default = 'general') {
    jQuery('.vi-ui.vi-ui-main.tabular.menu .item').vi_tab({
        history: true,
        historyType: 'hash'
    });
    /*Setup tab*/
    let tabs,
        tabEvent = false,
        initialTab = tab_default,
        navSelector = '.vi-ui.vi-ui-main.menu';
    // Initializes plugin features
    jQuery.address.strict(false).wrap(true);

    if (jQuery.address.value() == '') {
        jQuery.address.history(false).value(initialTab).history(true);
    }
    // Address handler
    jQuery.address.init(function (event) {

        // Tabs setup
        tabs = jQuery('.vi-ui.vi-ui-main.menu')
            .vi_tab({
                history: true,
                historyType: 'hash'
            });

        // Enables the plugin for all the tabs
        jQuery(navSelector + ' a').on('click', function (event) {
            tabEvent = true;
            tabEvent = false;
            return true;
        });

    });
}

function viwcuf_color_picker(div) {
    jQuery(div).find('.vi-wcuf-color').each(function () {
        jQuery(this).css({backgroundColor: jQuery(this).val()});
    });
    jQuery(div).find('.vi-wcuf-color').unbind().minicolors({
        change: function (value, opacity) {
            jQuery(this).parent().find('.vi-wcuf-color').css({backgroundColor: value});
        },
        animationSpeed: 50,
        animationEasing: 'swing',
        changeDelay: 0,
        control: 'wheel',
        defaultValue: '',
        format: 'rgb',
        hide: null,
        hideSpeed: 100,
        inline: false,
        keywords: '',
        letterCase: 'lowercase',
        opacity: true,
        position: 'bottom left',
        show: null,
        showSpeed: 100,
        theme: 'default',
        swatches: []
    });
}

function viwcuf_set_value_number(div) {
    jQuery(div).find('input[type = "number"]').unbind().on('blur change', function () {
        if (!jQuery(this).val() && jQuery(this).data('wcuf_allow_empty')){
            return false;
        }
        let new_val, min = parseFloat(jQuery(this).attr('min') || 0) ,
            max = parseFloat(jQuery(this).attr('max')),
            val = parseFloat(jQuery(this).val() || 0 );
        new_val = val;
        if (min > val) {
            new_val = min;
        }
        if (max && max < val) {
            new_val = max;
        }
        jQuery(this).val(new_val);
    });
}

jQuery.fn.viwcuf_rule = function () {
    new viwcuf_rule_init(this);
    return this;
};
var viwcuf_rule_init = function (rule) {
    this.rule = rule;
    this.init();
};
viwcuf_rule_init.prototype.init = function () {
    let rule = this.rule;
    rule.villatheme_accordion('refresh');
    rule.find('.vi-ui.dropdown:not(.vi-wcuf-dropdown-init)').addClass('vi-wcuf-dropdown-init').dropdown();
    rule.find('.vi-ui.checkbox:not(.vi-wcuf-checkbox-init)').addClass('vi-wcuf-checkbox-init').checkbox();
    rule.find('.vi-wcuf-rule-wrap .vi-wcuf-condition-wrap-wrap:not(.vi-wcuf-condition-wrap-wrap-init)').each(function () {
        jQuery(this).addClass('vi-wcuf-condition-wrap-wrap-init').viwcuf_rule_child();
    });
    this.change_value(rule);
    this.checkbox(rule);
    this.dropdown(rule);
    this.add_new(rule);
    this.remove(rule);
};
viwcuf_rule_init.prototype.change_value = function (rule) {};
viwcuf_rule_init.prototype.checkbox = function (rule) {};
viwcuf_rule_init.prototype.dropdown = function (rule) {};
viwcuf_rule_init.prototype.add_new = function (rule) {
    rule.find('.vi-wcuf-accordion-clone').unbind().on('click', function (e) {
        e.stopPropagation();
        let newRow = rule.clone(),
            $now = Date.now();
        newRow.attr('data-rule_id', $now);
        newRow.find('.vi-wcuf-rule-id').val($now);
        newRow.find('input, select').each(function (k, v) {
            let name = jQuery(v).data('wcuf_name_default') || '';
            if (name) {
                let prefix = jQuery(v).data('wcuf_prefix') || '',
                    item_index_default = jQuery(v).closest('.vi-wcuf-condition-wrap').data('wcuf_item_index') || '';
                if (!item_index_default) {
                    item_index_default = $now + k;
                    jQuery(v).closest('.vi-wcuf-condition-wrap').attr('data-wcuf_item_index', item_index_default);
                }
                name = name.replace(/{index_default}/gm, $now).replace(/{prefix_default}/gm, prefix).replace(/{item_index_default}/gm, item_index_default);
                if (jQuery(v).attr('name')) {
                    jQuery(v).attr('name', name);
                }
                jQuery(v).attr('data-wcuf_name', name);
            }
        });
        for (let i = 0; i < newRow.find('.vi-ui.dropdown').length; i++) {
            let selected = rule.find('.vi-ui.dropdown').eq(i).dropdown('get value');
            newRow.find('.vi-ui.dropdown').eq(i).dropdown('set selected', selected);
        }
        newRow.find('.vi-wcuf-condition-wrap-wrap').removeClass('vi-wcuf-condition-wrap-wrap-init');
        newRow.find('.vi-wcuf-dropdown-init').removeClass('vi-wcuf-dropdown-init');
        newRow.find('.vi-wcuf-checkbox-init').removeClass('vi-wcuf-checkbox-init');
        newRow.find('.select2').remove();
        newRow.find('.vi-wcuf-search-select2.vi-wcuf-search-select2-init').each(function (k, v) {
            let val = rule.find('.vi-wcuf-search-select2.vi-wcuf-search-select2-init').eq(k).val();
            jQuery(v).removeClass('vi-wcuf-search-select2-init').val(val).trigger('change');
        });
        newRow.viwcuf_rule();
        newRow.insertAfter(rule);
        e.stopPropagation();
    });
    rule.find('.vi-wcuf-add-condition-btn').unbind().on('click', function (e) {
        e.stopPropagation();
        jQuery(this).addClass('loading');
        let now = Date.now(), div_append, condition_index = rule.data('rule_id'),
            condition_prefix = jQuery(this).data('rule_prefix'),
            condition_type = jQuery(this).data('rule_type');
        div_append = rule.find('.vi-wcuf-rule-wrap.vi-wcuf-' + condition_type + '-rule-wrap');
        let current = rule.closest('.vi-wcuf-tab-rule').find('.vi-wcuf-rule-new-wrap .vi-wcuf-' + condition_type + '-condition-new-wrap .vi-wcuf-condition-wrap-wrap').first();
        let html = current.html(), newRow = jQuery(current).clone();
        html = html.replace(/{index}/gm, condition_index).replace(/{prefix}/gm, condition_prefix).replace(/{item_index}/gm, now);
        newRow.html(html);
        newRow.appendTo(div_append);
        div_append.find('.vi-wcuf-condition-wrap-wrap:not(.vi-wcuf-condition-wrap-wrap-init)').each(function () {
            jQuery(this).addClass('vi-wcuf-condition-wrap-wrap-init').viwcuf_rule_child();
        });
        jQuery(this).removeClass('loading');
        e.stopPropagation();
    });
};
viwcuf_rule_init.prototype.remove = function (rule) {
    rule.find('.vi-wcuf-accordion-remove').unbind().on('click', function (e) {
        if (jQuery('.vi-wcuf-accordion-remove').length === 1) {
            alert('You can not remove the last item.');
            return false;
        }
        if (confirm("Would you want to remove this?")) {
            rule.remove();
        }
        e.stopPropagation();
    });
}
jQuery.fn.viwcuf_rule_child = function () {
    new viwcuf_rule_child_init(this);
    return this;
};
var viwcuf_rule_child_init = function (condition) {
    this.condition = condition;
    this.init();
};
viwcuf_rule_child_init.prototype.init = function () {
    let self = this, condition = this.condition;
    condition.find('.vi-ui.dropdown:not(.vi-wcuf-dropdown-init)').addClass('vi-wcuf-dropdown-init').dropdown();
    condition.find('.vi-ui.checkbox:not(.vi-wcuf-checkbox-init)').addClass('vi-wcuf-checkbox-init').checkbox();
    condition.find('.vi-wcuf-condition-wrap:not(.vi-wcuf-hidden) .vi-wcuf-search-select2').each(function () {
        self.select2(condition, jQuery(this));
    });
    this.change_value(condition);
    this.dropdown(condition);
    this.remove(condition);
};
viwcuf_rule_child_init.prototype.change_value = function (condition) {};
viwcuf_rule_child_init.prototype.select2 = function (condition, select, close_on_select = false, min_input = 2,) {
    let placeholder = '', action = '', type_select2 = select.data('type_select2');
    switch (type_select2) {
        case 'product':
            placeholder = 'Please fill in your product title';
            action = select.data('pd_include') ? 'viwcuf_search_product_include' : 'viwcuf_search_product';
            break;
        case 'category':
            placeholder = 'Please fill in your category title';
            action = 'viwcuf_search_cats';
            break;
        case 'coupon':
            placeholder = 'Please fill in your coupon code';
            action = 'viwcuf_search_coupon';
            break;
        case 'country':
            placeholder = 'Please fill in the country name';
            break;
        case 'user':
            placeholder = 'Please fill in the user name';
            action = 'viwcuf_search_user';
            break;
        case 'user_role':
            placeholder = 'Please fill in the role name';
            break;
    }
    select.addClass('vi-wcuf-search-select2-init').select2(this.select2_params(placeholder, action, close_on_select, min_input));
};
viwcuf_rule_child_init.prototype.select2_params = function (placeholder = '', action = '', close_on_select = false, min_input = 2) {
    let result = {
        closeOnSelect: close_on_select,
        placeholder: placeholder,
        cache: true
    };
    if (action) {
        result['minimumInputLength'] = min_input;
        result['escapeMarkup'] = function (markup) {
            return markup;
        };
        result['ajax'] = {
            url: "admin-ajax.php?action=" + action,
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term,
                    nonce: jQuery('#_viwcuf_settings_us').val() || jQuery('#_viwcuf_settings_ob').val() || '',
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: false
        };
    }
    return result;
};
viwcuf_rule_child_init.prototype.dropdown = function (condition) {
    let self = this;
    condition.find('.vi-wcuf-condition-type').unbind().dropdown({
        onChange: function (val) {
            condition.find('.vi-wcuf-condition-wrap').addClass('vi-wcuf-hidden');
            condition.find('.vi-wcuf-condition-value-wrap-wrap input, .vi-wcuf-condition-value-wrap-wrap select').attr('name', '');
            condition.find('.vi-wcuf-condition-' + val + '-wrap').removeClass('vi-wcuf-hidden');
            condition.find('.vi-wcuf-condition-' + val + '-wrap input, .vi-wcuf-condition-' + val + '-wrap select').each(function () {
                let name = jQuery(this).data('wcuf_name');
                jQuery(this).attr('name', name);
                if (jQuery(this).hasClass('vi-wcuf-search-select2') && !jQuery(this).hasClass('vi-wcuf-search-select2-init')) {
                    self.select2(condition, jQuery(this));
                }
            });
        }
    });
    condition.find('.vi-ui.dropdown.selection').has('optgroup').each(function () {
        let $menu = jQuery('<div/>').addClass('menu');
        jQuery(this).find('optgroup').each(function () {
            $menu.append("<div class=\"vi-wcuf-dropdown-header\">" + this.label + "</div></div>");
            return jQuery(this).prop('disabled') ? jQuery(this).children().each(function () {
                return $menu.append("<div class=\"disabled item\" data-value=\"\">" + this.innerHTML + "</div>");
            }): jQuery(this).children().each(function () {
                return $menu.append("<div class=\"item\" data-value=\"" + this.value + "\">" + this.innerHTML + "</div>");
            });
        });
        return jQuery(this).find('.menu').html($menu.html());
    });
};
viwcuf_rule_child_init.prototype.remove = function (condition) {
    condition.find('.vi-wcuf-revmove-condition-btn-wrap').unbind().on('click', function (e) {
        if (confirm("Would you want to remove this?")) {
            condition.remove();
        }
        e.stopPropagation();
    });
};