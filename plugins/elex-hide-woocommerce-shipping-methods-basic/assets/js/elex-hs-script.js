var is_edit = false;
var edit_rule_name = '';
jQuery(function () {
    jQuery('.elex-hs-chosen').chosen();
    jQuery('.tooltip').darkTooltip();
    jQuery('#elex_hs_hide_shipping_div').hide();
  

    jQuery('#elex_hs_filter_order_weight_action').change(function () {
        var dom_bet = '<input type="number" min="0" step="0.00001" style="height:28px;width:20%;vertical-align:top;" placeholder="' + elex_hs_js_texts.elex_order_weight_min_range + '" id="elex_min_order_weight_val"><input type="number" min="0" step="0.00001" style="height:28px;width:20%;vertical-align:top;" placeholder="' + elex_hs_js_texts.elex_order_weight_max_range + '" id="elex_max_order_weight_val">';
        var dom_sing = '<input type="number" min="0" step="0.00001" style="height:28px;width:20%;vertical-align:top;" placeholder="' + elex_hs_js_texts.elex_order_weight_value + '" id="elex_order_weight_val">';
        switch (jQuery(this).val()) {
            case 'between':
                jQuery("#elex_hs_filter_weight_range_text").empty();
                jQuery('#elex_hs_filter_weight_range_text').append(dom_bet);
                break;
            case 'all':
                jQuery("#elex_hs_filter_weight_range_text").empty();
                break;
            default:
                jQuery("#elex_hs_filter_weight_range_text").empty();
                jQuery('#elex_hs_filter_weight_range_text').append(dom_sing);
        }
    });
    
    jQuery('#elex_hs_filter_rule_btn').click(function () {
        if (jQuery('#elex_hs_filter_order_weight_action').val() != 'all') {
            jQuery("#elex_min_order_weight_val").removeClass("input-error");
            jQuery("#elex_max_order_weight_val").removeClass("input-error");
            jQuery("#elex_order_weight_val").removeClass("input-error");
            if (jQuery('#elex_hs_filter_order_weight_action').val() == 'between') {
                if (jQuery('#elex_min_order_weight_val').val() == '') {
                    jQuery("#elex_min_order_weight_val").addClass("input-error");
                    return;
                }
                if (jQuery('#elex_max_order_weight_val').val() == '') {
                    jQuery("#elex_max_order_weight_val").addClass("input-error");
                    return;
                }
            } else {
                if (jQuery('#elex_order_weight_val').val() == '') {
                    jQuery("#elex_order_weight_val").addClass("input-error");
                    return;
                }
            }

        }

        jQuery('#elex_hs_filter_div').hide();
        jQuery('#elex_hs_hide_shipping_div').show();
        jQuery('#elex_hs_step1').removeClass('active');
        jQuery('#elex_hs_step2').addClass('active');
    });

    jQuery('#elex_hs_back_btn').click(function () {
        jQuery('#elex_hs_filter_div').show();
        jQuery('#elex_hs_hide_shipping_div').hide();
        jQuery('#elex_hs_step2').removeClass('active');
        jQuery('#elex_hs_step1').addClass('active');
    });

    jQuery('#elex_hs_create_rule_btn').click(function () {
        var main_arr = {};
        //Shipping class
        if (jQuery('#elex_hs_filter_shipping_class').val() != null) {
            main_arr['shipping_class'] = jQuery('#elex_hs_filter_shipping_class').val();
        }

        //Order Weight
        if (jQuery('#elex_hs_filter_order_weight_action').val() != 'all') {
            main_arr['weight_action'] = jQuery('#elex_hs_filter_order_weight_action').val();
            if (jQuery('#elex_hs_filter_order_weight_action').val() == 'between') {
                main_arr['order_min_weight'] = jQuery('#elex_min_order_weight_val').val();
                main_arr['order_max_weight'] = jQuery('#elex_max_order_weight_val').val();
            } else {
                main_arr['order_weight'] = jQuery('#elex_order_weight_val').val();
            }
        }
        
        //Shipping Methods
        if (jQuery('#elex_hs_filter_shipping_methods').val() != null) {
            main_arr['filter_shipping_methods'] = jQuery('#elex_hs_filter_shipping_methods').val();
        }
        
        //Rule name
        main_arr['rule_name'] = jQuery('#elex_hs_rule_name').val();


        //Hide Shipping Methods
        if (jQuery('#elex_hs_hide_shipping_methods').val() != null) {
            main_arr['hide_shipping_methods'] = jQuery('#elex_hs_hide_shipping_methods').val();
        }
        
        if (main_arr['hide_shipping_methods'] === undefined && main_arr['hide_shipping_options'] === undefined) {
            alert('Choose at least one Shipping Method or Shipping Option to Hide.');
            return;
        }


        jQuery(".elex-hs-loader").css("display", "block");
        jQuery.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                _elex_hs_ajax_nonce: jQuery('#_elex_hs_ajax_nonce').val(),
                action: 'elex_hs_create_rule',
                rule: main_arr,
                is_edit_rule: is_edit,
                edit_rule_name: edit_rule_name
            },
            success: function (response) {
                jQuery(".elex-hs-loader").css("display", "none");
                window.location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });




    });

});

function elex_hs_edit_copy_rule(rule_name, action) {
    jQuery(".elex-hs-loader").css("display", "block");
    jQuery.ajax({
        type: 'post',
        url: ajaxurl,
        data: {
            _elex_hs_ajax_nonce: jQuery('#_elex_hs_ajax_nonce').val(),
            action: 'elex_hs_edit_rule',
            rule_name: rule_name
        },
        success: function (response) {
            jQuery(".elex-hs-loader").css("display", "none");
            response = jQuery.parseJSON(response);
            if (response['shipping_class'] !== undefined && response['shipping_class']) {
                jQuery('#elex_hs_filter_shipping_class').val(response['shipping_class']).trigger("chosen:updated");
            } else {
                jQuery('#elex_hs_filter_shipping_class').val('').trigger("chosen:updated");
            }
            
            if (response['weight_action'] !== undefined && response['weight_action']) {
                jQuery('#elex_hs_filter_order_weight_action').val(response['weight_action']);
                if (response['weight_action'] == 'between') {
                    var dom_bet = '<input type="text"style="height:28px;width:20%;vertical-align:top;" placeholder="' + elex_hs_js_texts.elex_order_weight_min_range + '" id="elex_min_order_weight_val"><input type="text" style="height:28px;width:20%;vertical-align:top;" placeholder="' + elex_hs_js_texts.elex_order_weight_max_range + '" id="elex_max_order_weight_val">';
                    jQuery("#elex_hs_filter_weight_range_text").empty();
                    jQuery('#elex_hs_filter_weight_range_text').append(dom_bet);
                    jQuery('#elex_min_order_weight_val').val(response['order_min_weight']);
                    jQuery('#elex_max_order_weight_val').val(response['order_max_weight']);
                } else {
                    var dom_sing = '<input type="text" style="height:28px;width:20%;vertical-align:top;" placeholder="' + elex_hs_js_texts.elex_order_weight_value + '" id="elex_order_weight_val">';
                    jQuery("#elex_hs_filter_weight_range_text").empty();
                    jQuery('#elex_hs_filter_weight_range_text').append(dom_sing);
                    jQuery('#elex_order_weight_val').val(response['order_weight']);
                }
            } else {
                jQuery('#elex_hs_filter_order_weight_action').val('all');
                jQuery("#elex_hs_filter_weight_range_text").empty();

            }
           
            if (response['filter_shipping_methods'] !== undefined && response['filter_shipping_methods']) {
                jQuery('#elex_hs_filter_shipping_methods').val(response['filter_shipping_methods']).trigger("chosen:updated");

            } else {
                jQuery('#elex_hs_filter_shipping_methods').val('').trigger("chosen:updated");
            }
            
            if (response['hide_shipping_methods'] !== undefined && response['hide_shipping_methods']) {
                jQuery('#elex_hs_hide_shipping_methods').val(response['hide_shipping_methods']).trigger("chosen:updated");
            } else {
                jQuery('#elex_hs_hide_shipping_methods').val('').trigger("chosen:updated");
            }
            if (action == 'edit') {
                jQuery('#elex_hs_rule_name').val(response['rule_name']);
                is_edit = true;
                edit_rule_name = response['rule_name'];
            } else {
                is_edit = false;
                var name = 'copy of ' + response['rule_name'];
                jQuery('#elex_hs_rule_name').val(name);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }

    });

    jQuery("#elex_hs_manage_rule_div").hide();
    jQuery('#elex_hs_filter_div').show();
    jQuery(".elex-hs-all-step").show();
    jQuery('#elex_hs_step2').removeClass('active');
    jQuery('#elex_hs_step1').addClass('active');
}
function elex_hs_delete_rule(rule_name) {
    var delete_schedule = confirm("Do you want to delete the rule " + rule_name + "?");
    if (delete_schedule !== true) {
        return;
    }
    jQuery(".elex-hs-loader").css("display", "block");
    jQuery.ajax({
        type: 'post',
        url: ajaxurl,
        data: {
            _elex_hs_ajax_nonce: jQuery('#_elex_hs_ajax_nonce').val(),
            action: 'elex_hs_delete_rule',
            rule_name: rule_name
        },
        success: function (response) {
            window.location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }

    });
}