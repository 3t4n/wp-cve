function elex_edit_file(feed_id, text) {
    if(text == undefined) {
        text = '';
        edit_project = true;
        edit_file = feed_id;
    }
    jQuery(".elex-gpf-loader").css("display", "block");
    jQuery.ajax({
        type: 'post',
        url: ajaxurl,
        data: {
            _ajax_elex_gpf_manage_feed_nonce: jQuery('#_ajax_elex_gpf_manage_feed_nonce').val(),
            action: 'elex_gpf_manage_feed_edit_file',
            file_to_edit: feed_id
        },
        success: function (response) {
            jQuery('#settings_first_section').show();
            jQuery('#elex_manage_feed').hide();
            jQuery('.pagination').hide();
            jQuery('#elex_gpf_start_page_edit_text').show();
            jQuery('#elex_gpf_map_category_edit_text').show();
            dom3 = '';
            dom2 = '';
            attr_row_count = 0;
            prod_attr_row = 0;
            response = JSON.parse(response);
            temp_arr = response;
            var required_attr = temp_arr['required_attr'];
            var optional_attr = temp_arr['optional'];
            var product_attr = temp_arr['product_attr'];
            selected_google_cats = temp_arr['prefill_val']['sel_google_cats'];
            edit_feed_data = response;

             prod_attr_option = '';
            jQuery.each(product_attr, function (prod_index, prod_value) {
                if(prod_index == 'ID') {
                    prod_attr_option += '<optgroup label = "General">';
                }
                prod_attr_option += '<option value="'+prod_index+'">'+prod_value['label']+'</option>'; 

                if(prod_index == '_virtual') {
                    prod_attr_option += '</optgroup><optgroup label = "Inventory">';
                }
                if(prod_index == '_backorders') {
                    prod_attr_option += '</optgroup><optgroup label = "Shipping">';
                }
                if(prod_index == '_weight') {
                    prod_attr_option += '</optgroup><optgroup label = "Advanced">';
                }
                if(prod_index == 'google_category') {
                    prod_attr_option += '</optgroup><optgroup label = "Meta Values">';
                }

            });
             prod_attr_option += '</optgroup>';

            var dom = '';
            jQuery('#elex_project_title').val(text + temp_arr['prefill_val']['name']);
            jQuery('#elex_project_description').val(text + temp_arr['prefill_val']['description']);
			jQuery('#elex_default_google_category').val(temp_arr['prefill_val']['default_category_chosen']);
            jQuery('#country_of_sale').val(temp_arr['prefill_val']['sale_country']);
            jQuery('#refresh_schedule').val(temp_arr['prefill_val']['refresh_schedule']); 
            switch (temp_arr['prefill_val']['refresh_schedule']) {
                case 'weekly' :
                    jQuery('#elex_select_weekly_day').show();
                    jQuery("#refresh_time_field").show();
                    jQuery("#elex_weekly_days").val(temp_arr['prefill_val']['refresh_days']).trigger("chosen:updated");
                    jQuery('#refresh_hour').val(temp_arr['prefill_val']['refresh_hour']);
                    break;
                case 'monthly' :
                    jQuery('#elex_select_monthly_day').show();
                    jQuery("#refresh_time_field").show();
                    jQuery("#elex_monthly_days").val(temp_arr['prefill_val']['refresh_days']).trigger("chosen:updated");
                    jQuery('#refresh_hour').val(temp_arr['prefill_val']['refresh_hour']);
                    break;
                case 'daily' :
                    jQuery("#refresh_time_field").show();
                    jQuery('#refresh_hour').val(temp_arr['prefill_val']['refresh_hour']);
                    break;
                default :
                    jQuery("#refresh_time_field").hide();
                    jQuery('#elex_select_monthly_day').hide();
                    jQuery('#elex_select_weekly_day').hide();
                    break;
            }
            if(temp_arr['prefill_val']['exclude_ids']) {
                 jQuery.ajax({
                type: 'post',
                url: ajaxurl,
                data: {
                    _ajax_elex_gpf_manage_feed_nonce: jQuery('#_ajax_elex_gpf_manage_feed_nonce').val(),
                    action: 'elex_gpf_get_exclude_prod_option',
                    exclude_prod_ids: temp_arr['prefill_val']['exclude_ids']
                },
                success: function (response) {
                    response = JSON.parse(response);
                    jQuery('#elex_exclude_products').append(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });

            }

            if(temp_arr['prefill_val']['stock_check']) {
                jQuery("#elex_gpf_stock_quantity").show();
                jQuery('#elex_gpf_exclude_stock').val(temp_arr['prefill_val']['stock_check']);
                jQuery('#elex_gpf_stock_quantity').val(temp_arr['prefill_val']['stock_quantity']);
            }
            if(temp_arr['prefill_val']['prod_sold_check']) {
                jQuery("#elex_gpf_sold_quantity").show();
                jQuery('#elex_gpf_exclude_sold_quantity').val(temp_arr['prefill_val']['prod_sold_check']);
                jQuery('#elex_gpf_sold_quantity').val(temp_arr['prefill_val']['sold_quantity']);
            }
            if(temp_arr['prefill_val']['stock_status']) {
                let status = temp_arr['prefill_val']['stock_status'];
                   
                    jQuery.each(status,function(index,value){
                        jQuery(".chosen-select").chosen();
                        jQuery('.class_stock_status option[value='+value+']').prop('selected',true);
                        jQuery('.class_stock_status').trigger("chosen:updated");
    
                    });
                }
    

            jQuery('#elex_gpf_vendors').val(temp_arr['prefill_val']['prod_vendor']).trigger("chosen:updated");

            jQuery('#feed_file_type').val(temp_arr['prefill_val']['feed_file_type']);
            if (temp_arr['prefill_val']['autoset_identifier_exists'] == 'true') {
                jQuery('#autoset_identifier_exists').prop("checked", true);
            }
            if (temp_arr['prefill_val']['featured'] == 'true') {
                jQuery('#include_featured').prop("checked", true);
            }
             if (temp_arr['prefill_val']['currency_conversion']) {
                jQuery('#elex_gpf_advanced_settings_div').hide();
                jQuery('#elex_gpf_advanced_settings_div2').show();
                jQuery('#elex_gpf_advanced_div').show();
                jQuery('#elex_gpf_currency_conversion_div').show();
                jQuery('#elex_currency_conversion').val(temp_arr['prefill_val']['currency_conversion']);
                jQuery('#elex_gpf_currency_conversion_code_div').show();
                jQuery('#elex_currency_conversion_code').val(temp_arr['prefill_val']['currency_conversion_code']);
            }
            var map_count = 0;
            var dom_title = '<tr><td class="elex-gpf-settings-table-map-attr-left2"><h4>Google Attributes</h4></td><td class="elex-gpf-settings-table-map-attr-middle2"><h4>Set Attribute Value</h4></td></tr>';
            jQuery('#elex_required_attr_map').append(dom_title);
            jQuery.each(required_attr, function (index, value) {
                // if( temp_arr['prefill_val']['prod_attr'] ) {
                    if( temp_arr['prefill_val']['prod_attr'] && temp_arr['prefill_val']['prod_attr'][map_count].length != undefined) {
                    dom = '';
                    var req_attr_dom = '';
                    var grp_type = ''
                    jQuery.each(product_attr, function (pr_index, value) {
                        if (product_attr[pr_index]['grp_type'] != grp_type) {
                            if (grp_type != '') {
                                req_attr_dom += '</optgroup>';
                            }
                            req_attr_dom += '<optgroup label="' + product_attr[pr_index]['grp_type'] + '">';
                            grp_type = product_attr[pr_index]['grp_type'];
                        }

                        var selected = '';
                        if (temp_arr['prefill_val']['prod_attr'][map_count] == pr_index) {
                            selected = 'selected';
                        }
                        req_attr_dom += '<option value=' + pr_index + ' ' + selected + ' >' + product_attr[pr_index]['label'] + '</option>';
                        var rec_selected = '';
                        if (index == 'condition' && grp_type == '') {
                            req_attr_dom += '<optgroup label="Recommended Values">';
                            if (temp_arr['prefill_val']['prod_attr'][map_count] == 'rec_new') {
                                rec_selected = 'selected';
                            }
                            req_attr_dom += '<option value="rec_new" ' + rec_selected + '>[new]</option>';
                            rec_selected = '';
                            if (temp_arr['prefill_val']['prod_attr'][map_count] == 'rec_refurbished') {
                                rec_selected = 'selected';
                            }
                            req_attr_dom += '<option value="rec_refurbished" ' + rec_selected + '>[refurbished]</option>';
                            rec_selected = '';
                            if (temp_arr['prefill_val']['prod_attr'][map_count] == 'rec_used') {
                                rec_selected = 'selected';
                            }
                            req_attr_dom += '<option value="rec_used" ' + rec_selected + '>[used]</option>'
                            req_attr_dom += '</optgroup>';
                        } else if ((index == 'adult' || index == 'is_bundle') && grp_type == '') {
                            req_attr_dom += '<optgroup label="Recommended Values">';
                            if (temp_arr['prefill_val']['prod_attr'][map_count] == 'rec_yes') {
                                rec_selected = 'selected';
                            }
                            req_attr_dom += '<option value="rec_yes" ' + rec_selected + '>[yes]</option>';
                            rec_selected = '';
                            if (temp_arr['prefill_val']['prod_attr'][map_count] == 'rec_no') {
                                rec_selected = 'selected';
                            }
                            req_attr_dom += '<option value="rec_no" ' + rec_selected + '>[no]</option>';
                            req_attr_dom += '</optgroup>';
                        } else if (index == 'age_group' && grp_type == '') {
                            req_attr_dom += '<optgroup label="Recommended Values">';
                            
                            if (temp_arr['prefill_val']['prod_attr'][map_count] == 'rec_newborn') {
                                rec_selected = 'selected';
                            }
                            req_attr_dom += '<option value="rec_newborn" ' + rec_selected + '>[newborn]</option>';
                            rec_selected = '';
                            if (temp_arr['prefill_val']['prod_attr'][map_count] == 'rec_infant') {
                                rec_selected = 'selected';
                            }
                            req_attr_dom += '<option value="rec_infant" ' + rec_selected + '>[infant]</option>';
                            rec_selected = '';
                            if (temp_arr['prefill_val']['prod_attr'][map_count] == 'rec_toddler') {
                                rec_selected = 'selected';
                            }
                            req_attr_dom += '<option value="rec_toddler" ' + rec_selected + '>[toddler]</option>';
                            rec_selected = '';
                            if (temp_arr['prefill_val']['prod_attr'][map_count] == 'rec_kids') {
                                rec_selected = 'selected';
                            }
                            req_attr_dom += '<option value="rec_kids" ' + rec_selected + '>[kids]</option>';
                            rec_selected = '';
                            if (temp_arr['prefill_val']['prod_attr'][map_count] == 'rec_adult') {
                                rec_selected = 'selected';
                            }
                            req_attr_dom += '<option value="rec_adult" ' + rec_selected + '>[adult]</option>';
                            req_attr_dom += '</optgroup>';
                        } else if (index == 'availability' && grp_type == '') {
                            req_attr_dom += '<optgroup label="Recommended Values">';
                            if (temp_arr['prefill_val']['prod_attr'][map_count] == 'rec_in stock') {
                                rec_selected = 'selected';
                            }
                            req_attr_dom += '<option value="rec_in stock" ' + rec_selected + '>[in stock]</option>';
                            rec_selected = '';
                            if (temp_arr['prefill_val']['prod_attr'][map_count] == 'rec_out of stock') {
                                rec_selected = 'selected';
                            }
                            req_attr_dom += '<option value="rec_out of stock" ' + rec_selected + '>[out of stock]</option>';
                            rec_selected = '';
                            if (temp_arr['prefill_val']['prod_attr'][map_count] == 'rec_preorder') {
                                rec_selected = 'selected';
                            }
                            req_attr_dom += '<option value="rec_preorder" ' + rec_selected + '>[preorder]</option>';
                            req_attr_dom += '<option value="rec_in stock">[in stock]</option><option value="rec_out of stock">[out of stock]</option><option value="rec_preorder">[preorder]</option>';
                            req_attr_dom += '</optgroup>';
                            grp_type = '-';
                        }
                    });
                    req_attr_dom += '</optgroup>';
                    var req_product_attr_field = '';
                    req_product_attr_field += '<div id="elex_set_condition_div_' + prod_attr_row + '"></div>';
                    req_product_attr_field += '<div id="elex_prepend_attr_div_' + prod_attr_row + '" ></div>';
                    if (temp_arr['prefill_val']['prod_attr'][map_count].startsWith("elex_text_val")) {
                        req_product_attr_field += '<div><input type="text"  id="sample_name2' + prod_attr_row + '" style="width:46%;">';

                    } else {
                        req_product_attr_field += '<div><select  id="sample_name2' + prod_attr_row + '" style="width:46%;">' + req_attr_dom + '</select>';
                    }
                    dom += '<tr ><td class="elex-gpf-settings-table-map-attr-left2">' + required_attr[index]['label'] + '</td><td class="elex-gpf-settings-table-map-attr-middle2">' + req_product_attr_field ;
                    dom += '<a href="javascript:void(0)" id="text_field' + prod_attr_row + '" <span class="elex-gpf-icon elex-gpf-icon-text" title="Enter a text value" onclick="elex_add_text_field(' + prod_attr_row + ')"  style="display: inline-block;" ></span></a>'; 
                    dom += '<a href="javascript:void(0)" id="select_field' + prod_attr_row + '" <span class="elex-gpf-icon elex-gpf-icon-select" title="Select value" onclick="elex_add_select_field(' + prod_attr_row + ')"  style="display: inline-block;" ></span> </a>'
                    dom += '<a onclick="elex_prepend_field_fun('+prod_attr_row+')"  href="javascript:void(0);" <span class="elex-gpf-icon elex-gpf-icon-prepend" title="Prepend" style="display: inline-block;" ></span></a>';
                    dom += ' ';
                    dom += '<a onclick="elex_append_field_fun('+prod_attr_row+')" href="javascript:void(0)"<span class="elex-gpf-icon elex-gpf-icon-append" title="Append value" style="display: inline-block;" ></span></a>';
                    dom += ' ';
                    dom += '<button onclick="elex_set_condition_fun('+prod_attr_row+')" class = "button button-primary" href="javascript:void(0)" >Set Rules</button>';
                    dom += '</div>';
                    dom += '<div id="elex_append_attr_div_' + prod_attr_row + '"></div>';

                    dom += '</td></tr>';
                    jQuery('#elex_required_attr_map').append(dom);
                    if (jQuery('#sample_name2' + prod_attr_row).attr('type') == 'text') {
                        jQuery('#text_field' + prod_attr_row).hide();
                        var prefill_text_val = temp_arr['prefill_val']['prod_attr'][map_count].replace("elex_text_val", "");
                        jQuery('#sample_name2' + prod_attr_row).val(prefill_text_val);
                    } else {
                        jQuery('#select_field' + prod_attr_row).hide();
                    }

                    elex_gpf_prefill_conditions(temp_arr,prod_attr_row,prod_attr_option);

                    selected_google_attr.push(index);
                    prod_attr_row++;
                    map_count++;
                }
            });

            if (temp_arr['prefill_val']['prod_attr'].length > map_count) {
                for (var i = map_count; i < temp_arr['prefill_val']['prod_attr'].length; i++) {
                    jQuery.each(optional_attr, function (index2, value2) {

                        dom3 += '<optgroup label="' + index2 + '">';
                        jQuery.each(optional_attr[index2], function (index3, value3) {
                            var selected = '';
                            if (temp_arr['prefill_val']['google_attr'][i] == index3) {
                                selected = 'selected';
                            }
                            dom3 += '<option value="' + index3 + '" ' + selected + ' >' + optional_attr[index2][index3]['label'] + '</option>';
                        });
                        dom3 += '</optgroup>';
                    });
                    var opt_attr_dom= '';
                    var opt_grp_type = '';
                    jQuery.each(product_attr, function (index, value) {
                        
                        if (product_attr[index]['grp_type'] != opt_grp_type) {
                        if (opt_grp_type != '') {
                            opt_attr_dom += '</optgroup>';
                        }
                        opt_attr_dom += '<optgroup label="' + product_attr[index]['grp_type'] + '">';
                        opt_grp_type = product_attr[index]['grp_type'];
                    }
                    opt_attr_dom += '</optgroup>';
                        
                        var selected = '';
                        if (temp_arr['prefill_val']['prod_attr'][i] == index) {
                            selected = 'selected';
                        }
                        opt_attr_dom += '<option value=' + index + ' ' + selected + ' >' + product_attr[index]['label'] + '</option>';
                    });
                    var opt_product_attr_field = '';
                    if (temp_arr['prefill_val']['prod_attr'][i].startsWith("elex_text_val")) {
                        opt_product_attr_field = '<input type="text"  id="sample_name2' + prod_attr_row + '" style="width:230px;">';

                    } else {
                        opt_product_attr_field = '<select id="sample_name2' + prod_attr_row + '" style="width:230px;">' + opt_attr_dom + '</select>';
                    }
                    var optional_attr_dom = '<tr><td class="elex-gpf-settings-table-map-attr-left"><select id="sample_name' + attr_row_count + '">';
                    optional_attr_dom += dom3;
                    optional_attr_dom += '</select></td>';
                   
                    optional_attr_dom += '<td class="elex-gpf-settings-table-map-attr-middle">';
                    optional_attr_dom += '<div id="elex_set_condition_div_' + prod_attr_row + '"></div>';
                    optional_attr_dom += '<div id="elex_prepend_attr_div_' + prod_attr_row + '" ></div>'
                    optional_attr_dom += '<div>'+opt_product_attr_field;
                    optional_attr_dom += '<a href="javascript:void(0)" id="text_field' + prod_attr_row + '" <span class="elex-gpf-icon elex-gpf-icon-text" title="Enter a text value" onclick="elex_add_text_field(' + prod_attr_row + ')" style="display: inline-block;" ></span>'
                    optional_attr_dom += '<a href="javascript:void(0)" id="select_field' + prod_attr_row + '" <span class="elex-gpf-icon elex-gpf-icon-select" title="Select value" onclick="elex_add_select_field(' + prod_attr_row + ')"  style="display: inline-block;" ></span></a>';
                    optional_attr_dom += '<a onclick="elex_prepend_field_fun('+prod_attr_row+')"  href="javascript:void(0);" <span class="elex-gpf-icon elex-gpf-icon-prepend" title="Prepend value" style="display: inline-block;" ></span></a>';
                    optional_attr_dom += ' ';
                    optional_attr_dom += '<a onclick="elex_append_field_fun('+prod_attr_row+')" href="javascript:void(0)"<span class="elex-gpf-icon elex-gpf-icon-append" title="Append value" style="display: inline-block;" ></span></a>';
                    optional_attr_dom += ' ';
                    optional_attr_dom += '<button onclick="elex_set_condition_fun('+prod_attr_row+')" class = "button button-primary" href="javascript:void(0)" >Set Rules</button>';
                    optional_attr_dom += '<a href="javascript:void(0)" id="remove-officer-button" <span class="elex-gpf-icon elex-gpf-icon-delete" title="Remove" style="display: inline-block;" ></span></a>'; 
                    optional_attr_dom += '</div>';
                    optional_attr_dom += '<div id="elex_append_attr_div_' + prod_attr_row + '" ></div>';
                    optional_attr_dom += '</td> </tr>';
                    jQuery('#elex_optional_attr_map').append(optional_attr_dom);
                    if (jQuery('#sample_name2' + prod_attr_row).attr('type') == 'text') {
                        jQuery('#text_field' + prod_attr_row).hide();
                        var prefill_text_val = temp_arr['prefill_val']['prod_attr'][i].replace("elex_text_val", "");
                        jQuery('#sample_name2' + prod_attr_row).val(prefill_text_val);
                    } else {
                        jQuery('#select_field' + prod_attr_row).hide();
                    }
                    elex_gpf_prefill_conditions(temp_arr,prod_attr_row,prod_attr_option);

                    attr_row_count++;
                    prod_attr_row++;
                }
            }
            jQuery.each(temp_arr['prefill_val']['categories_choosen'], function (index, value) {
                selected_google_category = temp_arr['prefill_val']['sel_google_cats'];
                selected_product_category = temp_arr['prefill_val']['categories_choosen'];
                
            });
            dom3 = '';
            dom2 = '';
            jQuery.each(optional_attr, function (index2, value2) {
                dom3 += '<optgroup label="' + index2 + '">';
                jQuery.each(optional_attr[index2], function (index3, value3) {
                    dom3 += '<option value="' + index3 + '">' + optional_attr[index2][index3]['label'] + '</option>';
                });
                dom3 += '</optgroup>';
            });
            var grp_type = '';
            jQuery.each(product_attr, function (index, value) {
                if (product_attr[index]['grp_type'] != grp_type) {
                    if (grp_type != '') {
                        dom2 += '</optgroup>';
                    }
                    dom2 += '<optgroup label="' + product_attr[index]['grp_type'] + '">';
                    grp_type = product_attr[index]['grp_type'];
                }
                dom2 += '</optgroup>';
                var selected = '';
                if (temp_arr['prefill_val']['prod_attr'][i] == index) {
                    selected = 'selected';
                }
                dom2 += '<option value=' + index + ' ' + selected + ' >' + product_attr[index]['label'] + '</option>';
            });
            if(temp_arr['prefill_val']['selected_products'] && temp_arr['prefill_val']['selected_google_product_cats'] ){
                var edit_selected_products= temp_arr['prefill_val']['selected_products'];
                var edit_selected_google_product_cats =temp_arr['prefill_val']['selected_google_product_cats'];
                    
                for(i=0;i<edit_selected_google_product_cats.length;i++){
                    var item_table      = jQuery('#elex_cat_table_product' ).closest( 'table.elex_gpf_product_table' ),
                    item_table_body = item_table.find( 'tbody' ),
                    index           = item_table_body.find( 'tr' ).length,
                    row             = `
                                        <td class="elex-gpf-settings-table-cat-map-left" style="width: 55%;">
                                            <select class="wc-product-search elex_gpf_include_products" multiple="multiple"  style="width: 100%;height:30px" name="elex_gpf_include_products[]" data-placeholder=" Search for a product..." data-action="woocommerce_json_search_products_and_variations"></select>
                                        </td>
                                        
                                        <td class="elex-gpf-settings-table-cat-map-middle elex_google_cats_auto " style="width: 40%;">                        
                                            <div class="elex_google_cats_auto" >    
                                                <input class="typeahead  elex_gpf_product_google_cat"  name="elex_gpf_product_google_cats[]"  style="width: 153%;" type="text" placeholder="Google Categories">                        
                                            </div>
                                        </td>
                                        <td class="elex-gpf-settings-table-cat-map-right" style="width: 5%; text-align:center !important;"> 
                                            <a href="javascript:void(0);"> 
                                                <span class="elex-gpf-icon elex-gpf-minus-icon elex-gpf-map-product-remove" title="Remove" style="display: inline-block;" >
                                                </span>
                                            </a> 
                                        </td>    
                                    `;
                    
                    item_table_body.append( '<tr>' + row + '</tr>' );
                    jQuery( document.body ).trigger( 'wc-enhanced-select-init' );
                    jQuery('.elex_google_cats_auto .typeahead').typeahead({
                        hint: false,
                        highlight: false,
                        minLength: 1
                    },
                    {
                        name: 'google_attr',
                        source: substringMatcher(google_prod_category)
                    });
                }
                j=0;                
                jQuery(".elex_gpf_include_products").each(function(i) {                       
                      var obj = jQuery(this);
                        jQuery.ajax({
                            type: 'post',
                            url: ajaxurl,
                            data: {
                                _ajax_elex_gpf_manage_feed_nonce: jQuery('#_ajax_elex_gpf_manage_feed_nonce').val(),
                                action: 'elex_gpf_get_exclude_prod_option',
                                exclude_prod_ids: edit_selected_products[j]
                            },
                            success: function (response) {
                                response = JSON.parse(response);
                                obj.append(response);
                               
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(textStatus, errorThrown);
                            }
                        });
                    
                    j++;
                    
                });
                k=0;
                jQuery(".elex_gpf_product_google_cat").each(function(i) {
                    jQuery(this).val(edit_selected_google_product_cats[k]);
                    k++;
                });
                jQuery('#elex_gpf_sub_map_category').removeClass('elex-select-category-active');
                jQuery('#elex_gpf_sub_map_product').addClass('elex-select-category-active');
            }
            jQuery(".elex-gpf-loader").css("display", "none");
            jQuery(".elex-gpf-steps-navigator").show();
            jQuery('#elex_settings_nochange').show();
            jQuery('#elex_map_cat_nochange').show();
            jQuery('#elex_map_attr_nochange').show();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}

function elex_gpf_prefill_conditions(temp_arr,prod_attr_row,prod_attr_option) {
    if(temp_arr['prefill_val']['conditions'] != undefined) {
        if(temp_arr['prefill_val']['conditions'][prod_attr_row] != undefined) {
            jQuery.each(temp_arr['prefill_val']['conditions'][prod_attr_row], function (cond_index, cond_val) {
               
                elex_set_condition_fun(prod_attr_row,prod_attr_option);
                jQuery('#set_cond_select_operation_'+prod_attr_row+'-'+cond_index+'_option').val(cond_val[1]);

                if(cond_val[2].startsWith('elex_text_val')) {
                    jQuery('#select_prod_attr_for_cond_'+prod_attr_row+'-'+cond_index+'_product_attr').replaceWith('<input type="text"  id="select_prod_attr_for_cond_'+prod_attr_row+'-'+cond_index+'_product_attr" style="width:150px;">');
                    cond_val[2] = cond_val[2].replace("elex_text_val", "");
                    jQuery('#text_field' +prod_attr_row+'-'+cond_index).hide();
                    jQuery('#select_field' +prod_attr_row+'-'+cond_index).show();
                } else {
                    jQuery('#text_field' +prod_attr_row+'-'+cond_index).show();
                    jQuery('#select_field' +prod_attr_row+'-'+cond_index).hide();
                }


                    jQuery('#select_prod_attr_for_cond_'+prod_attr_row+'-'+cond_index+'_product_attr').val(cond_val[2]);

                jQuery.each(cond_val[0], function (set_condition_index, set_condition_val) {
                    if(set_condition_index != 0) {
                    
                        append_data = '';
                        append_data += elex_get_condition_parameters(prod_attr_row,cond_index,prod_attr_option);
                        jQuery('#elex_condition_lines_'+prod_attr_row+'-'+cond_index).append(append_data);
                    }
                    jQuery('#elex_condition_line_'+prod_attr_row+'_'+cond_index+'-'+set_condition_index+'_product_attr').val(set_condition_val[0]);
                        jQuery('#elex_condition_line_'+prod_attr_row+'_'+cond_index+'-'+set_condition_index+'_elex_condition_options').val(set_condition_val[1]);
                        jQuery('#elex_condition_line_'+prod_attr_row+'_'+cond_index+'-'+set_condition_index+'_text_value').val(set_condition_val[2]);
                    

                });

                if(cond_val[3] != undefined && cond_val[3] != '') {
                    jQuery.each(cond_val[3], function (prepend_cond_index, prepend_cond_val) {
                        elex_prepend_prod_attr_for_condition(prod_attr_row,cond_index,prod_attr_option);

                        jQuery('#select_prod_attr_prepend_for_cond_'+prod_attr_row+'_'+cond_index+'-'+prepend_cond_index+'_product_attr').val(prepend_cond_val[0]);
                        jQuery('#select_prod_attr_prepend_for_cond_'+prod_attr_row+'_'+cond_index+'-'+prepend_cond_index+'_elex_delimeter_options').val(prepend_cond_val[1]);

                    });

                }

                if(cond_val[4] != undefined && cond_val[4] != '') {
                    jQuery.each(cond_val[4], function (append_cond_index, append_cond_val) {
                        elex_append_prod_attr_for_condition(prod_attr_row,cond_index,prod_attr_option);

                        jQuery('#select_prod_attr_append_for_cond_'+prod_attr_row+'_'+cond_index+'-'+append_cond_index+'_product_attr').val(append_cond_val[0]);
                        jQuery('#select_prod_attr_append_for_cond_'+prod_attr_row+'_'+cond_index+'-'+append_cond_index+'_elex_delimeter_options').val(append_cond_val[1]);

                    });

                }

            });
        }

    }

    if(temp_arr['prefill_val']['prepend_attr'] != undefined) {
        if(temp_arr['prefill_val']['prepend_attr'][prod_attr_row] != undefined) {

            jQuery.each(temp_arr['prefill_val']['prepend_attr'][prod_attr_row], function (prepend_attr_index, prepend_attr_value) {
                elex_prepend_field_fun(prod_attr_row,prod_attr_option);

                jQuery('#elex_prepend_attr_child_div_'+prod_attr_row+'-'+prepend_attr_index+'_product_attr').val(prepend_attr_value[0]);
                jQuery('#elex_prepend_attr_child_div_'+prod_attr_row+'-'+prepend_attr_index+'_elex_delimeter_options').val(prepend_attr_value[1]);
            });

        }
    }
    if(temp_arr['prefill_val']['append_attr'] != undefined) {
        if(temp_arr['prefill_val']['append_attr'][prod_attr_row] != undefined) {
            jQuery.each(temp_arr['prefill_val']['append_attr'][prod_attr_row], function (append_attr_index, append_attr_value) {
                elex_append_field_fun(prod_attr_row,prod_attr_option);

                jQuery('#elex_append_attr_child_div_'+prod_attr_row+'-'+append_attr_index+'_product_attr').val(append_attr_value[0]);
                jQuery('#elex_append_attr_child_div_'+prod_attr_row+'-'+append_attr_index+'_elex_delimeter_options').val(append_attr_value[1]);
            });
        }
    }
}