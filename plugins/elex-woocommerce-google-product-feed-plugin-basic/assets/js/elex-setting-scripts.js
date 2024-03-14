var temp_arr = [];
var dom3 = '';
var dom2 = '';
var attr_row_count = 0;
var prod_attr_row = 0;
var selected_google_attr = [];
var selected_product_attr = [];
var count = 1;
var selected_google_cats = [];
var google_prod_cats_pair = {};
var edit_project = false;
var edit_file = '';
var prod_attributes_options = '';
var selected_google_category = [];
var selected_product_category = [];
var product_chunk = '0';
var edit_feed_data = [];
var current_feed_id = '';
var current_saved_date_time = '';

jQuery(function () {
    jQuery("#elex_currency_conversion_code").keypress(function(event) {
        var character = String.fromCharCode(event.keyCode);
        return isValidCurrencyCode(character);     
    });

    function isValidCurrencyCode(str) {
        return !/[~`!@#$%\^&*()+=\-\[\]\\';,/{}|\\":<>\?1234567890]/g.test(str);
    }
    jQuery('.tooltip').darkTooltip();
    jQuery('.elex-gpf-multiple-chosen').chosen();
    jQuery('#elex_map_attr_nochange').hide();
    jQuery('#settings_map_category').hide();
    jQuery('#settings_map_attributes').hide();
    jQuery("#exclude_include").hide();
    jQuery("#refresh_time_field").hide();
    jQuery('#elex_select_weekly_day').hide();
    jQuery('#elex_select_monthly_day').hide();
    jQuery("#elex_cat_action").hide();
    jQuery('#elex_gpf_advanced_settings_div2').hide();
    jQuery('#elex_gpf_currency_conversion_div').hide();
    jQuery('#elex_gpf_currency_conversion_code_div').hide();
    jQuery('#elex_gpf_update_logs').hide();
    jQuery('#elex_gpf_stock_quantity').hide();
    jQuery('#elex_gpf_sold_quantity').hide();

    jQuery('#elex_gpf_advanced_settings').on('click', function(){
        jQuery('#elex_gpf_currency_conversion_div').show(500);
        jQuery("elex_gpf_currency_conversion_div").css("opacity", "1");
        jQuery('#elex_gpf_currency_conversion_code_div').show(500);
        jQuery("elex_gpf_currency_conversion_code_div").css("opacity", "1");
        jQuery('#elex_gpf_advanced_settings_div').hide();
        jQuery('#elex_gpf_advanced_settings_div2').show();
    });
    jQuery('#elex_gpf_advanced_settings2').on('click', function(){
        jQuery('#elex_gpf_currency_conversion_div').hide(300);
        jQuery("elex_gpf_currency_conversion_div").css("opacity", "1");
        jQuery('#elex_gpf_currency_conversion_code_div').hide(300);
        jQuery("elex_gpf_currency_conversion_code_div").css("opacity", "1");
        jQuery('#elex_gpf_advanced_settings_div2').hide();
        jQuery('#elex_gpf_advanced_settings_div').show();
    });

    jQuery("#elex_gpf_finish_cancel").click(function () {
        var map_cats = confirm("Do you want to cancel the generation of feed?");
              if (!map_cats == true) {
                return;
              }
              else {
                window.location.reload();
              }
    });

     jQuery("#elex_gpf_continue_to_manage_feed").click(function () {
        window.location.href = "admin.php?page=elex-product-feed-manage";
    });
    
    jQuery('#save_settings_first_page').on('click', function () {
        if (jQuery('#elex_project_title').val() == '') {
            alert('Enter Project Name');
            return;
        }
        if (jQuery('#country_of_sale').val() == '') {
            alert('Select Country');
            return;
        }
        let currency_code = jQuery("#elex_currency_conversion_code").val();
        let currency_conversion_amount = jQuery("#elex_currency_conversion").val();

        if(currency_conversion_amount <= 0 && currency_conversion_amount !== ''){
            alert("Currency Conversion amount should be more than 0");
            return;

        }
        if((currency_conversion_amount !== '' &&  currency_code === '') || (currency_code !== '' &&  currency_conversion_amount === '' ) ){
            alert ("Enter Both Currency Code and Conversion Amount ");
            return;
        }
        var project_name = jQuery('#elex_project_title').val();

        jQuery.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                _ajax_elex_gpf_nonce: jQuery('#_ajax_elex_gpf_nonce').val(),
                action: 'check_if_the_feed_exists_gf',
                project_title: project_name,
                is_edit_project :edit_project


            },
            success: function (response) {
                if(response == 'same_name') {
                    jQuery(".elex-gpf-loader").css("display", "none");
                    alert('Project already exists with the same name');
                    
                    jQuery('#elex_gpf_step2').removeClass('active');
                    jQuery('#elex_gpf_step1').addClass('active');
                    jQuery('#settings_first_section').show();
                    jQuery('#settings_map_category').hide();
                    jQuery('#settings_map_product').hide();
                    return;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
        jQuery('#elex_gpf_step1').removeClass('active');
        jQuery('#elex_gpf_step2').addClass('active');
        jQuery('#settings_first_section').hide();
        jQuery('#settings_map_category').show();
        jQuery('#settings_map_product').hide();

        if(jQuery('#elex_gpf_sub_map_category').hasClass('elex-select-category-active')){
            jQuery('#settings_map_product').hide();
            jQuery('#settings_map_category_1').show();
        }
        else{
            jQuery('#settings_map_product').show();
            jQuery('#settings_map_category_1').hide();
        }

        if(jQuery('#elex_default_google_category').val() && edit_project === false) {
            jQuery('#settings_map_category').find('.typeahead').val(jQuery('#elex_default_google_category').val());
            jQuery('#settings_map_category').find('[type=checkbox]').prop('checked', true);
        }
        else {
            if(selected_google_category != '') {
                jQuery.each(selected_google_category, function (index, google_cat) {
                    if( jQuery('#elex_google_cats_'+selected_product_category[index]).val() ) {
                        jQuery('#elex_google_cats_'+selected_product_category[index]).val(google_cat);
                    }
                    else {
                        jQuery('#elex_cat_table').find('tbody').find('.check-column input:checkbox').each(function() {
                            var unicode_value = escape( jQuery(this).val() );
                            unicode_value = unicode_value.replace(/\%/g, '').toLowerCase();
                            if(unicode_value == selected_product_category[index]) {
                                jQuery('#elex_google_cats_'+jQuery(this).val()).val(google_cat);
                            }
                        });
                    }
                    
                });
            }
            if(selected_product_category != '') {
                jQuery.each(selected_product_category, function (index, cat) {
                    if( jQuery('input[value="'+selected_product_category[index]+'"]').val() ) {
                        jQuery('input[value="'+selected_product_category[index]+'"]').prop('checked', true);
                    }
                    else {
                        jQuery('#elex_cat_table').find('tbody').find('.check-column input:checkbox').each(function() {
                            var unicode_value = escape( jQuery(this).val() );
                            unicode_value = unicode_value.replace(/\%/g, '').toLowerCase();
                            if(unicode_value == selected_product_category[index]) {
                                jQuery('input[value="'+jQuery(this).val()+'"]').prop('checked', true);
                            }
                        });
                    }
                    
                });
            }
        }
        
        //confirm the purpose of emptying these with SHASHI
        
        // selected_google_category = [];
        // selected_product_category = [];
        // selected_google_attr_product = [];


    });

    jQuery('#elex_gpf_sub_map_product').on('click', function (event) {
        event.preventDefault(); 
        jQuery('#elex_gpf_sub_map_product').addClass('elex-select-category-active');
        jQuery('#elex_gpf_sub_map_category').removeClass('elex-select-category-active');
       
        jQuery('#settings_map_product').show();
        jQuery('#settings_map_category_1').hide();
        var item_table      = jQuery('.elex_gpf_product_table'),
        item_table_body = item_table.find( 'tbody' ),
        index           = item_table_body.find( 'tr' ).length,
        row             = `
                            <td class="elex-gpf-settings-table-cat-map-left" style="width: 55%;">
                                <select class="wc-product-search elex_gpf_include_products" multiple="multiple"  style="width: 100%;height:30px" name="elex_gpf_include_products[]" data-placeholder="Search for a product..." data-action="woocommerce_json_search_products_and_variations"></select>
                            </td>
                            
                            <td class="elex-gpf-settings-table-cat-map-middle elex_google_cats_auto " style="width: 40%;">                        
                                <div class="elex_google_cats_auto" >    
                                    <input class="typeahead  elex_gpf_product_google_cat" value= "`+jQuery('#elex_default_google_category').val()+`" name="elex_gpf_product_google_cats[]"  style="width: 153%;" type="text" placeholder="Google Categories">                        
                                </div>
                            </td>
                            <td class="elex-gpf-settings-table-cat-map-right" style="width: 5%; text-align:center !important;"> 
                                <a href="javascript:void(0);"> 
                                    <span class="elex-gpf-icon elex-gpf-minus-icon elex-gpf-map-product-remove" title="Remove" style="display: inline-block;" >
                                    </span>
                                </a> 
                            </td>    
                        `;
        if(index==0){

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
        var map_product_count = jQuery('#elex_map_product_count_id').val();
        map_product_count++;
        jQuery('#elex_map_product_count_id').val(map_product_count);
        }
        
        
    });

    jQuery('#elex_gpf_sub_map_category').on('click', function (event) {
        event.preventDefault(); 
        jQuery('#elex_gpf_sub_map_product').removeClass('elex-select-category-active');
        jQuery('#elex_gpf_sub_map_category').addClass('elex-select-category-active');     
       
        jQuery('#settings_map_product').hide();
        jQuery('#settings_map_category_1').show();
    });
    jQuery('.elex-gpf-map-product-add').on('click', function (event) {

        var item_table      = jQuery('.elex_gpf_product_table'),
        item_table_body = item_table.find( 'tbody' ),
        index           = item_table_body.find( 'tr' ).length,
        row             = `
                            <td class="elex-gpf-settings-table-cat-map-left" style="width: 55%;">
                                <select class="wc-product-search elex_gpf_include_products" multiple="multiple"  style="width: 100%;height:30px" name="elex_gpf_include_products[]" data-placeholder="Search for a product..." data-action="woocommerce_json_search_products_and_variations"></select>
                            </td>
                            
                            <td class="elex-gpf-settings-table-cat-map-middle elex_google_cats_auto " style="width: 40%;">                        
                                <div class="elex_google_cats_auto" >    
                                    <input class="typeahead  elex_gpf_product_google_cat" value= "`+jQuery('#elex_default_google_category').val()+`" name="elex_gpf_product_google_cats[]"  style="width: 153%;" type="text" placeholder="Google Categories">                        
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
        var map_product_count = jQuery('#elex_map_product_count_id').val();
        map_product_count++;
        jQuery('#elex_map_product_count_id').val(map_product_count);
        
    });

    jQuery(document).on('click', '.elex-gpf-map-product-remove', function (event) {

        event.preventDefault();         
        jQuery(this).closest("tr").remove();
        var map_product_count = jQuery('#elex_map_product_count_id').val();
        map_product_count--;
        jQuery('#elex_map_product_count_id').val(map_product_count);
        
    });

    jQuery('#settings_map_category').find('.typeahead').on("input",function() {
        if(jQuery(this).val() != '') {
            jQuery(this).closest('tr').find('[type=checkbox]').prop('checked', true);
        }
        else {
            jQuery(this).closest('tr').find('[type=checkbox]').prop('checked', false);
        }
    });
    jQuery('#refresh_schedule').change(function () {
        switch(jQuery(this).val()) {
            case 'daily' :
                jQuery("#refresh_time_field").show();
                jQuery('#elex_select_weekly_day').hide();
                jQuery('#elex_select_monthly_day').hide();
                break;

            case 'weekly' :
                jQuery("#refresh_time_field").show();
                jQuery('#elex_select_weekly_day').show();
                jQuery('#elex_select_monthly_day').hide();
                break;

            case 'monthly' :
                jQuery("#refresh_time_field").show();
                jQuery('#elex_select_weekly_day').hide();
                jQuery('#elex_select_monthly_day').show();
                break;

            default :
                jQuery("#refresh_time_field").hide();
                jQuery('#elex_select_weekly_day').hide();
                jQuery('#elex_select_monthly_day').hide();
                break;
        }

        if (jQuery(this).val() != 'no_refresh') {
            jQuery("#refresh_time_field").show();
        } else {
            jQuery("#refresh_time_field").hide();
        }
    });   

    jQuery('#elex_gpf_exclude_stock').change(function () {
        if( jQuery(this).val()) {
            jQuery('#elex_gpf_stock_quantity').show();
        }
        else {
            jQuery('#elex_gpf_stock_quantity').hide();
        }

    });

    jQuery('#elex_gpf_exclude_sold_quantity').change(function () {
        if( jQuery(this).val()) {
            jQuery('#elex_gpf_sold_quantity').show();
            jQuery('#elex_gpf_sold_from_date').show();
            jQuery('#elex_gpf_sold_to_date').show();
        }
        else {
            jQuery('#elex_gpf_sold_quantity').hide();
            jQuery('#elex_gpf_sold_from_date').hide();
            jQuery('#elex_gpf_sold_to_date').hide();
        }

    });

    jQuery('#reset_settings_cat_map').on('click', function () {
        jQuery('.elex_google_cats_auto').find('input:text').val('');

    });
    jQuery('#save_settings_cat_map, #save_settings_cat_map_product').on('click', function () {
        jQuery(".elex-gpf-loader").css("display", "block");
        var country = jQuery('#country_of_sale').val();
        selected_google_attr = [];
        selected_google_cats = [];
        var selected_prod_cats = [];
        var is_google_cats  = true;

        if( jQuery(this).attr('id') != 'save_settings_cat_map_product') {
                jQuery('#elex_cat_table').find('tbody').find('.check-column input:checked').each(function() {
                    
                    if(!jQuery("#elex_google_cats_"+jQuery(this).val()).val() ){
                        is_google_cats  = false;
        
                    }
                    selected_prod_cats.push(jQuery(this).val());
                    selected_google_cats.push(jQuery("#elex_google_cats_"+jQuery(this).val()).val());
                });
        
                 if((selected_google_cats.length === 0) || (selected_prod_cats.length === 0)) {
                    alert('Please choose atleast one product category to continue');
                    jQuery(".elex-gpf-loader").css("display", "none");
                    return;
                 }
             }
            else {

            selected_google_attr_product = [];
            var selected_products = [] ;
            var selected_google_product_cats =[];
            
            selected_google_product_cats = jQuery("input[name='elex_gpf_product_google_cats[]']").map(function(){ if(jQuery(this).val()) {return jQuery(this).val()};}).get();
            
            row_count = jQuery('#elex_map_product_count_id').val();
            let selected_prods = [];
            let flag = false;
            jQuery(".elex_gpf_include_products").each(function(i) {
                if(jQuery(this).val().length !== 0) {
                    selected_products.push(jQuery(this).val());
                    let product_id = jQuery(this).val();
                    for(let i = 0 ;i < product_id.length; i++){
                            id = product_id[i];
                            if(true === selected_prods.includes(id)){
                                flag = true;
                            }
                            selected_prods.push(id);
                            
                    }
                    
                }
                
            });
            
            if(flag){
                alert("Duplicate products selected");
                jQuery('#elex_gpf_step3').removeClass('active');
                jQuery('#elex_gpf_step2').addClass('active');
                jQuery(".elex-gpf-loader").css("display", "none");
                return;
            }
                // end map product
            if(selected_products.length >= 1 && selected_products.length  == selected_google_product_cats.length){
                
                    for(i=0;i<selected_products.length;i++){
                       
                        if(typeof selected_google_attr_product[selected_google_product_cats[i]] === 'undefined') {
                            
                                selected_google_attr_product[selected_google_product_cats[i]]= selected_products[i];// your code here
                          }else{
                            
                            selected_google_attr_product[selected_google_product_cats[i]].push(selected_products[i]);
                          }
                    }
    
            }
           
        }
        if( !is_google_cats ) {
            let msg = confirm('Google category is not mapped. We recommend you to map Google Category to increase the possibility of getting the feed approved from Google. Do you want to continue without mapping Google Category?');
            if (!msg == true) {
                jQuery(".elex-gpf-loader").css("display", "none");
                return;
            }
        }
         
        jQuery.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                _ajax_elex_gpf_nonce: jQuery('#_ajax_elex_gpf_nonce').val(),
                action: 'elex_gpf_show_mapping_fields',
                country_of_sale: country,
                google_cats: selected_google_cats.concat(selected_google_product_cats),
            },
            success: function (response) {
                jQuery(".elex-gpf-loader").css("display", "none");
                
                dom3 = '';
                
                prod_attr_row = 0;
                response = JSON.parse(response);
                var edit_feed_temp_arr = edit_feed_data;
                temp_arr = response;
                var required_attr = temp_arr['required_attr'];
                var optional_attr = temp_arr['optional'];
                var product_attr = temp_arr['product_attr'];
                var saved_google_attr_names = [];
                if ( edit_feed_temp_arr.length === 0 ) {
                    attr_row_count = 0;
                    jQuery('#elex_required_attr_map').empty()
                    var dom = '<tr><td class="elex-gpf-settings-table-map-attr-left2"><h4>Google Attributes</h4></td><td class="elex-gpf-settings-table-map-attr-middle2"><h4>Set Attribute Value</h4></td></tr>';
                } else {
                    jQuery.each(edit_feed_temp_arr['required_attr'], function (google_attr_key, google_attr_value) {
                        saved_google_attr_names.push(google_attr_value['label']);
                    });
                    var dom = '';
                    var edit_feed_req_keys = Object.keys(edit_feed_temp_arr['required_attr']);
                }
                var all_req_attr_labels  = [];
                var temp_sel_google_attr = [];
                jQuery.each(required_attr, function (index, value) {
                    all_req_attr_labels.push(value['label']);
                    if ( edit_feed_temp_arr.length !== 0 ) {
                        var feed_name_attr = value['feed_name'].substr(2);
                        prod_attr_row++;
                        if( edit_feed_req_keys.indexOf(feed_name_attr) !== -1 ) {
                            var feed_name_index = edit_feed_req_keys.indexOf(feed_name_attr);
                            if (feed_name_index > -1) {
                              edit_feed_req_keys.splice(feed_name_index, 1);
                            }
                            selected_google_attr.push(index);
                            return;
                        }
                        else {
                            prod_attr_row += edit_feed_req_keys.length;
                        } 
                    }
                    
                    dom2 = '';
                    var grp_type = '';
                    
                    jQuery.each(product_attr, function (index2, value2) {
                       
                         var selected = '';
                        if (index == 'id' && index2 == 'ID') {
                            selected = 'selected';
                        }
                        else if (index == 'title' && index2 == 'post_title') {
                            selected = 'selected';
                        }
                        else if (index == 'description' && index2 == 'post_content') {
                            selected = 'selected';
                        }
                        else if (index == 'link' && index2 == 'permalink') {
                            selected = 'selected';
                        }
                        else if (index == 'availability' && index2 == '_stock_status') {
                            selected = 'selected';
                        }
                        else if (index == 'gtin' && index2 == '_elex_gpf_gtin') {
                            selected = 'selected';
                        }
                        else if (index == 'brand' && index2 == '_elex_gpf_brand') {
                            selected = 'selected';
                        }
                        else if (index == 'mpn' && index2 == '_elex_gpf_mpn') {
                            selected = 'selected';
                        }
                        else if (index == 'price' && index2 == 'price') {
                            selected = 'selected';
                        }
                        else if (index == 'image_link' && index2 == 'main_image') {
                            selected = 'selected';
                        }
                        else if (index == 'item_group_id' && index2 == 'item_group_id') {
                            selected = 'selected';
                        }
                        else if (index == 'google_product_category' && index2 == 'google_category') {
                            selected = 'selected';
                        }
                        
                        var prefill = elex_preselect_attributes(index,index2);
                        if(prefill) {
                            
                            if(product_attr[index2]['grp_type'] != grp_type) {
                                if(grp_type !='') {
                                    dom2 += '</optgroup>';
                                }
                                dom2 += '<optgroup label="' + product_attr[index2]['grp_type'] + '">';
                                grp_type = product_attr[index2]['grp_type'];
                            }
                            dom2 += '<option value=' + index2 + ' ' + selected + '>' + product_attr[index2]['label'] + '</option>';
                        }
                        if (index == 'condition' && grp_type == '') {
                            dom2 += '<optgroup label="Supported Values">';
                            dom2 += '<option value="rec_new">[new]</option><option value="rec_refurbished">[refurbished]</option><option value="rec_used">[used]</option>';
                            dom2 += '</optgroup>';
                        }
                        else if ((index == 'adult' || index == 'is_bundle') && grp_type == '') {
                            dom2 += '<optgroup label="Supported Values">';
                            dom2 += '<option value="rec_yes">[yes]</option><option value="rec_no">[no]</option>';
                            dom2 += '</optgroup>';
                        }
                        else if (index == 'age_group' && grp_type == '') {
                            dom2 += '<optgroup label="Supported Values">';
                            dom2 += '<option value="rec_newborn">[newborn]</option><option value="rec_infant">[infant]</option><option value="rec_toddler">[toddler]</option><option value="rec_kids">[kids]</option><option value="rec_adult">[adult]</option>';
                            dom2 += '</optgroup>';
                        }
                        else if (index == 'availability' && grp_type == '') {
                            dom2 += '<optgroup label="Supported Values">';
                            dom2 += '<option value="rec_in stock">[in stock]</option><option value="rec_out of stock">[out of stock]</option><option value="rec_preorder">[preorder]</option>';
                            dom2 += '</optgroup>';
                            grp_type = '-';
                        }
                        
                    });
                    dom2 += '</optgroup>';
                    dom += '<tr>';
                    dom += '<td class="elex-gpf-settings-table-map-attr-left2">' + required_attr[index]['label'] ;
                    dom += '</td>';
                    dom += '<td class="elex-gpf-settings-table-map-attr-middle2">';
                    dom += '<div id="elex_set_condition_div_' + prod_attr_row + '"></div>';
                    dom += '<div id="elex_prepend_attr_div_' + prod_attr_row + '" ><p id="default_text_display_' + prod_attr_row + '"><br><b style="font-size:20px;">Set Default Values</b></p></div>';
                    dom += '<div><select  id="sample_name2' + prod_attr_row + '" style="width:46%;">' + dom2 + '</select>';
                    dom += '<a href="javascript:void(0)" id="text_field' + prod_attr_row + '" <span class="elex-gpf-icon elex-gpf-icon-text" title="Enter a text value" onclick="elex_add_text_field(' + prod_attr_row + ')" style="display: inline-block;" ></span></a>';
                    dom += '<a href="javascript:void(0)" id="select_field' + prod_attr_row + '" <span class="elex-gpf-icon elex-gpf-icon-select" title="Select value" onclick="elex_add_select_field(' + prod_attr_row + ')" style="display: inline-block;" ></span></a>';
                    dom += '<a onclick="elex_prepend_field_fun('+prod_attr_row+')"  href="javascript:void(0);" <span class="elex-gpf-icon elex-gpf-icon-prepend" title="Prepend value" style="display: inline-block;" ></span></a>';
                    dom += ' ';
                    dom += '<a onclick="elex_append_field_fun('+prod_attr_row+')" href="javascript:void(0)"<span class="elex-gpf-icon elex-gpf-icon-append" title="Append value" style="display: inline-block;" ></span></a>';
                    dom += ' ';
                    dom += '<button onclick="elex_set_condition_fun('+prod_attr_row+')" class = "button button-primary" href="javascript:void(0)" >Set Rules</button>';
                    dom += '</div>';
                    dom += '<div id="elex_append_attr_div_' + prod_attr_row + '"></div>';
                    dom += '</td>';
                    dom += '</tr>';
                    temp_sel_google_attr.push(index);
                    if ( edit_feed_temp_arr.length === 0 ) {
                        prod_attr_row++;
                    }
                }
                );
                if( temp_sel_google_attr.length !== 0) {
                    selected_google_attr = selected_google_attr.concat(temp_sel_google_attr);
                     prod_attr_row++;
                    if(edit_feed_temp_arr.length !== 0 ) {
                        if( jQuery('#elex_optional_attr_map').html().trim().length !== 0) {
                            var confirm_text = confirm("You have changed the existing Google Category or existing country, due to which mapped Optional Google attributes in the feed may be removed based on Country of Sale or Google Category.");
                            if (! confirm_text == true) {
                                return;
                            }
                            jQuery('#elex_optional_attr_map').empty();
                        }
                    }
                }
                if ( edit_feed_temp_arr.length !== 0 ) {
                    var temp_selected_google_attr = selected_google_attr;
                    selected_google_attr = [];
                    for( var flag=0; flag < edit_feed_temp_arr['prefill_val']['google_attr'].length; flag++) {
                        var feed_name_attr = edit_feed_temp_arr['prefill_val']['google_attr'][flag];
                        var feed_name_index = temp_selected_google_attr.indexOf(feed_name_attr);
                        if (feed_name_index > -1) {
                            selected_google_attr.push(feed_name_attr);
                          temp_selected_google_attr.splice(feed_name_index, 1);
                        }
                    }
                    if(temp_selected_google_attr.length !== 0) {
                        selected_google_attr = selected_google_attr.concat(temp_selected_google_attr);
                    }
                    if(temp_sel_google_attr.length === 0) {
                        prod_attr_row = edit_feed_temp_arr['prefill_val']['google_attr'].length;
                    }
                }
                dom2 = '';
                var grp_type_all = '';
                jQuery.each(product_attr, function (index, value) {
                    if (product_attr[index]['grp_type'] != grp_type_all) {
                        if (grp_type_all != '') {
                            dom2 += '</optgroup>';
                        }
                        dom2 += '<optgroup label="' + product_attr[index]['grp_type'] + '">';
                        grp_type_all = product_attr[index]['grp_type'];
                    }
                    dom2 += '<option value=' + index + '>' + product_attr[index]['label'] + '</option>';
                });
                dom2 += '</optgroup>';
                var all_opt_attr_labels = [];
                jQuery.each(optional_attr, function (index2, value2) {
                    dom3 += '<optgroup label="' + index2 + '">';
                    jQuery.each(optional_attr[index2], function (index3, value3) {
                        all_opt_attr_labels.push(optional_attr[index2][index3]['label']);
                        dom3 += '<option value="' + index3 + '">' + optional_attr[index2][index3]['label'] + '</option>';
                    });
                    dom3 += '</optgroup>';
                });
                var previous_attr_to_be_removed = '';
                if ( edit_feed_temp_arr.length !== 0 ) {
                    jQuery.each(edit_feed_req_keys, function (rem_key_index, rem_key_value) {
                        jQuery('#elex_required_attr_map  td').each(function() {
                            if(jQuery(this).html() == edit_feed_temp_arr['required_attr'][rem_key_value]['label']) {
                                previous_attr_to_be_removed = previous_attr_to_be_removed + '"'+ jQuery(this).html()+'",';
                                jQuery(this).closest('tr').remove(); 
                            }
                        });
                    });
                }

                if(previous_attr_to_be_removed) {
                    var confirm_text = confirm('You have changed the existing Google Category or existing country, due to which mapped Google attributes '+previous_attr_to_be_removed.slice(0, -1)+' in the feed may be modified or removed based on Country of Sale or Google Category.');
                    if (! confirm_text == true) {
                        return;
                    }

                    if( temp_sel_google_attr.length !== 0) {
                        jQuery('#elex_optional_attr_map').empty();
                    }
                }
                
                jQuery('#elex_gpf_step2').removeClass('active');
                jQuery('#elex_gpf_step3').addClass('active');
                jQuery('#elex_required_attr_map  td').each(function() {
                    if( all_opt_attr_labels.indexOf(jQuery(this).html()) !== -1 && all_req_attr_labels.indexOf(jQuery(this).html()) === -1 ) {
                        jQuery(this).closest('tr').remove();
                    } else if ( edit_feed_temp_arr.length !== 0 ) {
                        if( (all_opt_attr_labels.indexOf(jQuery(this).html()) !== -1 || all_req_attr_labels.indexOf(jQuery(this).html()) !== -1) && (saved_google_attr_names.indexOf(jQuery(this).html()) === -1) ) {
                            jQuery(this).closest('tr').remove();
                        }
                    }
                });

                jQuery('#elex_required_attr_map').append(dom);
                for (var i = 0; i < prod_attr_row; i++) {
                    if (jQuery('#sample_name2' + i).attr('type') == 'text') {
                        jQuery('#select_field' + i).show();
                    }
                    else {
                        jQuery('#select_field' + i).hide();
                    }
                    jQuery('#default_text_display_'+i).hide();
                }
                // jQuery('#elex_optional_attr_map').empty();
                jQuery('#settings_map_category').hide();
                jQuery('#settings_map_attributes').show();
                jQuery('#elex_map_attr_nochange').hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    });

        
    jQuery(document).on('click','.elex-gpf-icon-remove',function() {
        var id = (jQuery(this).closest('div').attr('id'));
        jQuery(this).closest('div').remove();
        if(id.startsWith('elex_condition_line_')) {
            arr_id = id.split('-');
            arr_id = arr_id[0].split('_');
            if(jQuery('#elex_condition_lines_'+arr_id[3]+'-'+arr_id[4]+'> div').attr('id') == undefined)  {
                jQuery('#set_cond_child_div_'+arr_id[3]+'-'+arr_id[4]).remove();

                if(jQuery('#elex_set_condition_div_'+arr_id[3]+'> div').attr('id') == undefined) {
                    jQuery('#default_text_display_'+arr_id[3]).hide();
                }
            }
        }
    });

    jQuery("#settings_map_attributes").on('click', '#save_settings_attr_map_add_new', function () {
        var optional_attr_dom = '<tr><td class="elex-gpf-settings-table-map-attr-left"><select id="sample_name' + attr_row_count + '">';
        optional_attr_dom += dom3;
        optional_attr_dom += '</select></td> ';
        optional_attr_dom += '<td class="elex-gpf-settings-table-map-attr-middle">';

        optional_attr_dom += '<div id="elex_set_condition_div_' + prod_attr_row + '"></div>';
        optional_attr_dom += '<div id="elex_prepend_attr_div_' + prod_attr_row + '" ><p id="default_text_display_' + prod_attr_row + '"><br><b style="font-size:20px;">Set Default values</b></p></div>'


        optional_attr_dom += '<select id="sample_name2' + prod_attr_row + '" style="width:230px;">' + dom2 + '</select> ';
        optional_attr_dom += '<a href="javascript:void(0)" id="text_field' + prod_attr_row + '" <span class="elex-gpf-icon elex-gpf-icon-text" title="Enter a text value" onclick="elex_add_text_field(' + prod_attr_row + ')" style="display: inline-block;" ></span></a> ';
        optional_attr_dom += '<a href="javascript:void(0)" id="select_field' + prod_attr_row + '" <span class="elex-gpf-icon elex-gpf-icon-select" title="Select value" onclick="elex_add_select_field(' + prod_attr_row + ')" style="display: inline-block;" ></span></a> ';
        optional_attr_dom += '<a onclick="elex_prepend_field_fun('+prod_attr_row+')"  href="javascript:void(0);" <span class="elex-gpf-icon elex-gpf-icon-prepend" title="Prepend value" style="display: inline-block;" ></span></a> ';
        optional_attr_dom += ' ';
        optional_attr_dom += '<a onclick="elex_append_field_fun('+prod_attr_row+')" href="javascript:void(0)"<span class="elex-gpf-icon elex-gpf-icon-append" title="Append value" style="display: inline-block;" ></span></a> ';
        optional_attr_dom += ' ';
        optional_attr_dom += '<button onclick="elex_set_condition_fun('+prod_attr_row+')" class = "button button-primary" href="javascript:void(0)" >Set Rules</button> ';
        optional_attr_dom += '<a href="javascript:void(0)" id="remove-officer-button" <span class="elex-gpf-icon elex-gpf-icon-delete" title="Remove" style="display: inline-block;" ></span></a> ';
        optional_attr_dom += '</div>';
        optional_attr_dom += '<div id="elex_append_attr_div_' + prod_attr_row + '" ></div>';
        
        optional_attr_dom += '</td> </tr>';
        jQuery('#elex_optional_attr_map').append(optional_attr_dom);
        jQuery('#select_field' + prod_attr_row).hide();
        jQuery('#default_text_display_' + prod_attr_row).hide();
        attr_row_count++;
        prod_attr_row++;
    });

    jQuery('#attribute_back_button').on('click', function () {
        jQuery('#elex_gpf_step3').removeClass('active');
        jQuery('#elex_gpf_step2').addClass('active');
        jQuery("#settings_map_attributes").hide();
        jQuery("#settings_map_category").show();

        if(jQuery('#elex_gpf_sub_map_category').hasClass('elex-select-category-active')){

            jQuery('#settings_map_product').hide();
            jQuery('#settings_map_category_1').show();
        }
        else{
            jQuery('#settings_map_product').show();
            jQuery('#settings_map_category_1').hide();
        }
        
    });

    jQuery('#category_back_button, #category_back_button_product').on('click', function () {
        jQuery('#elex_gpf_step2').removeClass('active');
        jQuery('#elex_gpf_step1').addClass('active');
        jQuery("#settings_map_category").hide();
        jQuery("#settings_first_section").show();
    });
    if(selected_google_category == '' && jQuery('#elex_default_google_category').val()) {
        jQuery('#settings_map_category').find('.typeahead').val(jQuery('#elex_default_google_category').val());
        jQuery('#settings_map_category').find('[type=checkbox]').prop('checked', true);
    }
    else {
        jQuery.each(selected_google_category, function (index, google_cat) {
            jQuery('#elex_google_cats_'+selected_product_category[index]).val(google_cat);
            jQuery('input[value="'+selected_product_category[index]+'"]').attr('checked', true);
        });
    }
    selected_google_category = [];
    selected_product_category = [];

    jQuery('#exclude_back_button').on('click', function () {
        jQuery('#elex_gpf_step4').removeClass('active');
        jQuery('#elex_gpf_step3').addClass('active');
        jQuery("#exclude_include").hide();
        jQuery("#settings_map_attributes").show();
    });

    jQuery("#settings_map_attributes").on('click', '#remove-officer-button', function (e) {
        var whichtr = jQuery(this).closest("tr");
        whichtr.remove();
        attr_row_count--;
        prod_attr_row--;
    });

    jQuery('#attribute_continue, #elex_map_attr_nochange').on('click', function () {
        jQuery('#elex_gpf_step3').removeClass('active');
        jQuery('#elex_gpf_step4').addClass('active');
        if(edit_project === false){
            jQuery(".chosen-select").chosen();
            jQuery('.class_stock_status option[value=instock]').prop('selected',true);
            jQuery('.class_stock_status').trigger("chosen:updated");
        }
        jQuery('#settings_map_attributes').hide();
        jQuery('#exclude_include').show();
    });

    jQuery("#settings_map_category").on('click', '#remove_category_mapping_tr', function (e) {
        var whichtr = jQuery(this).closest("tr");
        whichtr.remove();
    });

    jQuery('#generate_feed_button').on('click', function () {
        jQuery('#exclude_include').hide();
        jQuery('#elex_gpf_update_logs').show();
        jQuery('#elex_gpf_step4').removeClass('active');
        jQuery('#elex_gpf_step5').addClass('active');
        jQuery("#exclude_include").hide();
        elex_generate_feed_fun();

    });
});

function elex_generate_feed_fun() {
    jQuery("#elex_gpf_logs_loader").html('<img src="./images/loading.gif">');
        var project_name = jQuery('#elex_project_title').val();
        var project_desc = jQuery('#elex_project_description').val();
        var ids_to_exclude = jQuery('#elex_exclude_products').val();
        
        if(product_chunk == '0') {   
            for (var j = 0; j < attr_row_count; j++) {
                if (jQuery('#sample_name' + j).val() != undefined) {
                    selected_google_attr.push(jQuery('#sample_name' + j).val());
                }
            }
        }
         var cond = {};
         var prepend_value_to_prod_attr = {};
         var append_value_to_prod_attr = {};

        {   
            for (var j = 0; j < prod_attr_row; j++) {

                if (jQuery('#sample_name2' + j).val() != undefined) {
                    var temp_arr3 = [];
                        var child_count = 0;
                    jQuery('#elex_set_condition_div_'+j+' > div').map(function() {
                        
                        var temp_arr2 = [];
                       
                        jQuery('#'+this.id+' > div').map(function() {

                        if((this.id).startsWith('elex_condition_lines_')) {
                            var temp = 0;
                            var sample_arr = [];
                            jQuery('#'+this.id+' > div').map(function() {
                                var temp_arr = [];
                                temp_arr[0] = jQuery('#'+this.id+'_product_attr').val();
                                temp_arr[1] = jQuery('#'+this.id+'_elex_condition_options').val();
                                temp_arr[2] = jQuery('#'+this.id+'_text_value').val();
                                sample_arr[temp] = temp_arr;

                                temp++;
                            });
                            temp_arr2['0'] = sample_arr;
                        }
                        else if((this.id).startsWith('set_cond_select_operation_')) {
                            temp_arr2['1'] = jQuery('#'+this.id+'_option').val();
                        }

                        
                        else if((this.id).startsWith('select_prod_attr_for_cond_')) {
                            var prefix = '';
                            if(jQuery('#'+this.id+'_product_attr').attr('type') == 'text') {
                                prefix = 'elex_text_val';
                            }
                            temp_arr2['2'] = prefix + jQuery('#'+this.id+'_product_attr').val();
                                jQuery('#'+this.id+' > div').map(function() {
                                    if((this.id).startsWith('select_prod_attr_prepend_conditions_')) {
                                        var temp = 0;
                                        var sample_arr = [];
                                        jQuery('#'+this.id+' > div').map(function() {
                                            var temp_arr = [];
                                            temp_arr['0'] = jQuery('#'+this.id+'_product_attr').val();
                                            temp_arr['1'] = jQuery('#'+this.id+'_elex_delimeter_options').val();
                                            sample_arr[temp] = temp_arr;
                                            temp++;
                                        });
                                        temp_arr2['3'] = sample_arr;
                                    }
                                    else if((this.id).startsWith('select_prod_attr_append_conditions_')) {
                                        var temp = 0;
                                        var sample_arr = [];
                                        jQuery('#'+this.id+' > div').map(function() {
                                            var temp_arr = [];
                                            temp_arr['0'] = jQuery('#'+this.id+'_product_attr').val();
                                            temp_arr['1'] = jQuery('#'+this.id+'_elex_delimeter_options').val();
                                            sample_arr[temp] = temp_arr;
                                            temp++;
                                        });
                                        temp_arr2['4'] = sample_arr;
                                    }
                                });
                                
                        }

                    });
                         temp_arr3[child_count] = temp_arr2;

                        child_count++;

                    });
                            cond[j] = temp_arr3;

                            var temp_arr = [];
                             var temp = 0;
                            jQuery('#elex_prepend_attr_div_'+j+' > div').map(function() {
                                var sample_arr = [];
                                sample_arr[0] = jQuery('#'+this.id+'_product_attr').val();
                                sample_arr[1] = jQuery('#'+this.id+'_elex_delimeter_options').val();
                                temp_arr[temp] = sample_arr;
                                temp++;
                            });
                            prepend_value_to_prod_attr[j] = temp_arr;

                            var temp_arr = [];
                             var temp = 0;
                            jQuery('#elex_append_attr_div_'+j+' > div').map(function() {
                                var sample_arr = [];
                                sample_arr[0] = jQuery('#'+this.id+'_product_attr').val();
                                sample_arr[1] = jQuery('#'+this.id+'_elex_delimeter_options').val();
                                temp_arr[temp] = sample_arr;
                                temp++;
                            });
                            append_value_to_prod_attr[j] = temp_arr;
                }


                if (jQuery('#sample_name2' + j).val() != undefined) {
                    var prefix = '';
                    if (jQuery('#sample_name2' + j).attr('type') == 'text') {
                        prefix = 'elex_text_val';
                    }
                    if(product_chunk == '0')  {
                        selected_product_attr.push(prefix + jQuery('#sample_name2' + j).val());
                    }
                }
            }
        }
        var default_category_chosen = jQuery('#elex_default_google_category').val();
        var file_type = jQuery('#feed_file_type').val();

        //Schedule
        var refresh_type = jQuery('#refresh_schedule').val();
        var refresh_days = '';
        if(refresh_type == 'weekly') {
            refresh_days = jQuery('#elex_weekly_days').val();
        }
        else if(refresh_type == 'monthly') {
            refresh_days = jQuery('#elex_monthly_days').val();
        }

        var refresh_hours = jQuery('#refresh_hour').val();

        var include_var = false;
        var enable_identifier_exists = false;
        if (jQuery("#autoset_identifier_exists").prop("checked")) {
            enable_identifier_exists = true;
        }
        var enable_featured = false;
        if (jQuery("#include_featured").prop("checked")) {
            enable_featured = true;
        }
        var currency_conversion_code = jQuery("#elex_currency_conversion_code").val();
        var currency_conversion_amount = jQuery("#elex_currency_conversion").val();

        var stock_check_cond = jQuery("#elex_gpf_exclude_stock").val();
        var number_of_stock = jQuery("#elex_gpf_stock_quantity").val();

        var sold_check_cond = jQuery("#elex_gpf_exclude_sold_quantity").val();
        var number_of_prod_sold = jQuery("#elex_gpf_sold_quantity").val();

        var vendors = jQuery('#elex_gpf_vendors').val();
        
        var country = jQuery('#country_of_sale').val();
        var selected_prod_cats = [];
        jQuery('#elex_cat_table').find('tbody').find('.check-column input:checked').each(function() {
            selected_prod_cats.push(jQuery(this).val())
        });
        selected_google_attr_product= [];
        var selected_products = [] ;
        var selected_google_product_cats =[];        
        selected_google_product_cats = jQuery("input[name='elex_gpf_product_google_cats[]']").map(function(){return jQuery(this).val();}).get();        
        row_count = jQuery('#elex_map_product_count_id').val();        
        jQuery(".elex_gpf_include_products").each(function(i) {
            selected_products.push(jQuery(this).val());            
        });
        let stock_status = jQuery("#stock_status").val();

        jQuery.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                _ajax_elex_gpf_nonce: jQuery('#_ajax_elex_gpf_nonce').val(),
                action: 'elex_gpf_generate_feed',
                project_title: project_name,
                description : project_desc,
                sel_google_cats: selected_google_cats,
                categories_choosen: selected_prod_cats,
                default_category_chosen: default_category_chosen,
                google_attr: selected_google_attr,
                selected_products:selected_products,
                selected_google_product_cats:selected_google_product_cats,                
                sale_country: country,
                prod_attr: selected_product_attr,
                exclude_ids: ids_to_exclude,
                refresh_schedule: refresh_type,
                feed_file_type : file_type,
                refresh_hour: refresh_hours,
                refresh_days : refresh_days,
                include_variation: include_var,
                is_edit_project :edit_project,
                file_to_edit : edit_file,
                autoset_identifier_exists : enable_identifier_exists,
                conditions:cond,
                prepend_attr : prepend_value_to_prod_attr,
                append_attr : append_value_to_prod_attr,
                chunk : product_chunk,
                featured : enable_featured,
                currency_conversion : currency_conversion_amount,
                currency_conversion_code : currency_conversion_code,
                stock_check : stock_check_cond,
                stock_quantity : number_of_stock,
                prod_sold_check : sold_check_cond,
                sold_quantity : number_of_prod_sold,
                prod_vendor : vendors,
                stock_status : stock_status,


            },
            success: function (response) {
                if(response == 'same_name') {
                    jQuery(".elex-gpf-loader").css("display", "none");
                    alert('Project already exists with the same name');
                    jQuery('#elex_gpf_update_logs').hide();
                    jQuery('#exclude_include').show();
                    jQuery('#elex_gpf_step5').removeClass('active');
                    jQuery('#elex_gpf_step4').addClass('active');
                    return;
                }
                if(response == 'need_to_generate_feed') {
                    var d = new Date();
        d = d.toUTCString();
                    jQuery("#elex_gpf_logs_val").append("<b>" + d + "</b> " + (
          product_chunk + 1) * 100 + " products updated,<br><br>");
                    product_chunk++;
                    jQuery("#elex_gpf_logs_loader").html('<img src="./images/loading.gif">');
                    elex_generate_feed_fun();
                }
                else {
                    response = JSON.parse(response);
                    if(response['status'] == 'done'){
                        current_feed_id         = response['feed_id'];
                        current_saved_date_time = response['current_time'];
                        if(response['simple_excluded'] == 'yes') {
                            jQuery('#elex_gpf_view_excl_simple').show();
                        }
                        jQuery('#elex_gpf_continue_to_manage_feed').show();
                        jQuery('#elex_gpf_finish_cancel').hide();
                        jQuery('#elex_gpf_update_finished').hide();
                        jQuery("#elex_gpf_logs_loader").html('<h1>Feed has been generated successfully</h1>');
                        jQuery('#elex_gpf_log_heading').hide();
                        jQuery('#elex_gpf_continue_to_view_feed').show();
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
}
function elex_return_unique_value_array (value, index, self) {
    return self.indexOf(value) === index;
}

function elex_copy_file(file) {
    elex_edit_file(file, 'Copy of ');
}

function elex_show_reports(id, file_name) {
    jQuery(".elex-gpf-loader").css("display", "block");
    jQuery.ajax({
        type: 'post',
        url: ajaxurl,
        data: {
            _ajax_elex_gpf_manage_feed_nonce: jQuery('#_ajax_elex_gpf_manage_feed_nonce').val(),
            action: 'elex_gpf_get_reports',
            feed_id: id,
            meta_key: 'report_data'
        },
        success: function (response) {
            var feed_data = JSON.parse(response);
            var category_data = [];
            var total_simple = [];
            var simple_excluded = [];
            var location = (window.location+'').replace('search','').replace('feed_page','');
            jQuery.each(Object.keys(feed_data).reverse(), function (index, date_time) {
                product_type_count = feed_data[date_time];
                if(product_type_count['excluded_simple'] ||  product_type_count['total_simple']) {
                    var temp_cat = {};
                    var temp_total_simple = {};
                    var temp_simple_excluded = {};
                    temp_cat['label'] = date_time;
                    category_data.push(temp_cat);

                    temp_total_simple['value'] = product_type_count['total_simple'] ? product_type_count['total_simple'] : '';
                    total_simple.push(temp_total_simple);

                    temp_simple_excluded['value'] = product_type_count['excluded_simple'] ? product_type_count['excluded_simple'] : '';
                    temp_simple_excluded['link'] = "F-drill-"+ (location.replace('elex-product-feed-manage','elex-excluded-products')) + "&feed_id="+ id + "&date=" +date_time + "&type=simple";
                    simple_excluded.push(temp_simple_excluded);

                }

            });
            

            const dataSource= {
                chart: {
                  // caption: "Products",
                  bgColor: "#e6fafa",

                  
                  xaxisname: "Date & Time",
                  xAxisNameFontSize: 13,
                  yaxisname: "Number of Products",
                  yAxisNameFontSize: 13,
                  showsum: "0",
                  plotHighlightEffect: "fadeout|color=#7f7f7f, alpha=60",
                  usePlotGradientColor: "0",
                  legendNumRows : "2",
                  showvalues: "1",
                  theme: "fusion"
                },
                categories: [
                  {
                    category: category_data
                  }
                ],
                dataset: [
                  {
                    dataset: [
                      {
                        seriesname: "Simple Products included in the Feed", color: "#11b4f5", plottooltext: "$seriesname - $dataValue",
                        data: total_simple
                      },
                    ]
                  },
                  {
                    dataset: [
                      {
                        seriesname: "Simple Products excluded from the Feed", color: "#ceeaf5", plottooltext: "$seriesname - $dataValue",
                        data: simple_excluded
                      },
                    ]
                  }
                ]
              };

            FusionCharts.ready(function() {
              var myChart = new FusionCharts({
                type: "scrollmsstackedcolumn2d",
                renderAt: "chartContainer",
                width: "100%",
                height: "100%",
                dataFormat: "json",
                dataSource
              }).render();
            });


                
                jQuery("#dialogBox").dialog({
                    open: function(event,ui) {
                        jQuery(".ui-widget-overlay").bind("click", function(event,ui) {         
                            jQuery("#dialogBox").dialog("close");
                        });
                    },
                    closeOnEscape: true,
                    draggable: true,
                    resizable: false,
                    title: "Report of "+file_name+" ",
                    width: 900,
                    modal: true,
                    show: 500
                });
                jQuery(".ui-widget-overlay").css({"background-color": "#111111"});
                jQuery(".ui-widget-header").css({"background-color": "#c1c1fe"});

            var element = document.getElementById("chartContainer");
              var offsetForChartToDisplay = element.offsetTop + 150;
              
              function scrollFunction() {
                if( (document.documentElement.scrollTop+window.innerHeight) > offsetForChartToDisplay) {
                        window.removeEventListener("scroll", scrollFunction);
                }
              }
              
              window.addEventListener("scroll", scrollFunction)
              jQuery(".elex-gpf-loader").css("display", "none");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });

}
function elex_pause_schedule(file) {
    jQuery(".elex-gpf-loader").css("display", "block");
    jQuery.ajax({
        type: 'post',
        url: ajaxurl,
        data: {
            _ajax_elex_gpf_manage_feed_nonce: jQuery('#_ajax_elex_gpf_manage_feed_nonce').val(),
            action: 'elex_gpf_pause_schedule',
            file: file,
            feed_action : 'pause'
        },
        success: function (response) {
            window.location.href = "admin.php?page=elex-product-feed-manage";
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}

function elex_play_schedule(file) {
    jQuery(".elex-gpf-loader").css("display", "block");
    jQuery.ajax({
        type: 'post',
        url: ajaxurl,
        data: {
            _ajax_elex_gpf_manage_feed_nonce: jQuery('#_ajax_elex_gpf_manage_feed_nonce').val(),
            action: 'elex_gpf_pause_schedule',
            file: file,
            feed_action : 'play'
        },
        success: function (response) {
            window.location.href = "admin.php?page=elex-product-feed-manage";
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}

function elex_add_text_field(attr_count) {
    jQuery("#text_field" + attr_count).hide();
    jQuery("#select_field" + attr_count).show();
    jQuery('#sample_name2' + attr_count).replaceWith('<input type="text"  id="sample_name2' + attr_count + '" style="width:230px;">');
}

function elex_add_select_field(attr_count) {
    jQuery("#select_field" + attr_count).hide();
    jQuery("#text_field" + attr_count).show();
    jQuery('#sample_name2' + attr_count).replaceWith('<select  id="sample_name2' + attr_count + '" style="width:230px;">' + dom2 + '</select>');
}

function elex_preselect_attributes(index, index2) {
    var prod_attr = ['ID', '_sku', 'price', 'price_incl_tax', '_regular_price', '_sale_price', 'post_title', 'review_comment', 'review_count', 'post_content', 'post_excerpt', '_backorders', '_height', '_width', '_length', '_weight', 'main_image', 'item_group_id', '_manage_stock', 'menu_order', 'post_author', 'prod_category', 'product_tags', 'product_type', 'permalink', 'wc_currency', '_virtual', '_tax_class', '_tax_status', '_sold_individually', '_stock', '_stock_status', '_elex_gpf_brand', '_elex_gpf_mpn', '_elex_gpf_gtin', 'google_category'];
    var req_attr = [];
    switch (index) {
        case 'id':
            req_attr = ['ID', '_sku'];
            break;
        case 'price':
            req_attr = ['price', 'price_incl_tax', '_regular_price', '_sale_price'];
            break;
        case 'title':
            req_attr = ['post_title', 'post_content', 'post_excerpt', 'post_author'];
            break;
        case 'description':
            req_attr = ['post_title', 'post_content', 'post_excerpt', 'post_author'];
            break;
        case 'link':
            req_attr = ['permalink'];
            break;
        case 'availability':
            req_attr = ['_stock_status'];
            break;
        case 'image_link':
            req_attr = ['main_image'];
            break;
        case 'gtin':
            req_attr = ['_elex_gpf_gtin'];
            break;
        case 'brand':
            req_attr = ['_elex_gpf_brand'];
            break;
        case 'mpn':
            req_attr = ['_elex_gpf_mpn'];
            break;
        case 'item_group_id':
            req_attr = ['item_group_id'];
            break;
        case 'google_product_category':
            req_attr = ['google_category'];
            break;
        default:
            return true;
    }
    jQuery.each(req_attr, function (key, value2) {
                prod_attr = jQuery.grep(prod_attr, function (value) {
                    return value != value2;
                });
            });
            if (prod_attr.indexOf(index2) !== -1) {
                return false;
            } else {
                return true;
            }
}


function elex_append_field_fun(row_count,prod_attr_option) {
    if(prod_attr_option == undefined) {
        prod_attr_option = dom2;
    }
    var child_id = 0;
    if(jQuery('#elex_append_attr_div_'+row_count+'> div').attr('id') != undefined)  {
        child_id = parseInt((jQuery('#elex_append_attr_div_'+row_count+'> div:last-child').attr('id')).split('-')[1])+1;
    }
    var append_data = '<div id="elex_append_attr_child_div_'+row_count+'-'+child_id+'" style="padding: 1% 0px 0px 0px;">';
        append_data += elex_get_delimeters('elex_append_attr_child_div_'+row_count+'-'+child_id+'');
        append_data += '<select style="width:25%;" id="elex_append_attr_child_div_'+row_count+'-'+child_id+'_product_attr">'+prod_attr_option+'</select> ';
        append_data += '<a href="javascript:void(0);" <span class="elex-gpf-icon elex-gpf-icon-remove" title="Remove" style="display: inline-block;" ></span></a>';
        append_data += '</div>';

    jQuery('#elex_append_attr_div_'+row_count).append(append_data);
}

function elex_prepend_field_fun(row_count,prod_attr_option) {
    if(prod_attr_option == undefined) {
        prod_attr_option = dom2;
    }
    var child_id = 0;
    if(jQuery('#elex_prepend_attr_div_'+row_count+'> div').attr('id') != undefined)  {
        child_id = parseInt((jQuery('#elex_prepend_attr_div_'+row_count+'> div:last-child').attr('id')).split('-')[1])+1;
    }
    
    var prepend_data = '<div id="elex_prepend_attr_child_div_'+row_count+'-'+child_id+'" style="padding: 1% 0px 0px 0px;">';
        prepend_data += '<select style="width:25%;" id="elex_prepend_attr_child_div_'+row_count+'-'+child_id+'_product_attr">'+prod_attr_option+'</select>';
        prepend_data += elex_get_delimeters('elex_prepend_attr_child_div_'+row_count+'-'+child_id+'');
        prepend_data += '<a href="javascript:void(0);" <span class="elex-gpf-icon elex-gpf-icon-remove" title="Remove" style="display: inline-block;" ></span></a>';
        prepend_data += '</div>';
    jQuery('#elex_prepend_attr_div_'+row_count).append(prepend_data);
}

function elex_set_condition_fun(row_count,prod_attr_option) {
    var child_id = 0;
    if(prod_attr_option != undefined) {
    prod_attributes_options = prod_attr_option;
}
else {
    prod_attributes_options = dom2;
}
jQuery('#default_text_display_'+row_count).show();

    
    if(jQuery('#elex_set_condition_div_'+row_count+'> div').attr('id') != undefined)  {
        child_id = parseInt((jQuery('#elex_set_condition_div_'+row_count+'> div:last-child').attr('id')).split('-')[1])+1;
    }

    var set_cond = '<div id="set_cond_child_div_'+row_count+'-'+child_id+'" style="padding: 1% 0px 0px 1%;">';
        set_cond += '<div id = "elex_condition_lines_'+row_count+'-'+child_id+'">';
        set_cond += elex_get_condition_parameters (row_count,child_id,prod_attributes_options);
        set_cond += '</div>';
        set_cond += '<br><div id="select_prod_attr_for_cond_'+row_count+'-'+child_id+'"><b style="font-size:15px">Set Values for Condition '+(child_id+1)+'</b>';
        set_cond += '<div id="select_prod_attr_prepend_conditions_'+row_count+'-'+child_id+'"></div>';
        set_cond += '<br><select style="width:25%;" id="select_prod_attr_for_cond_'+row_count+'-'+child_id+'_product_attr">'+prod_attributes_options+'</select>';
        
        set_cond += '<a href="javascript:void(0)" id="text_field' +row_count+'-'+child_id+'" <span class="elex-gpf-icon elex-gpf-icon-text" title="Enter a text value" onclick="elex_add_text_field_in_cond('+row_count+','+child_id+')" style="display: inline-block;" ></span></a> ';
        set_cond += '<a href="javascript:void(0)" id="select_field' +row_count+'-'+child_id+'" <span class="elex-gpf-icon elex-gpf-icon-select" title="Select value" onclick="elex_add_select_field_in_cond('+row_count+','+child_id+')" style="display: inline-block;" ></span></a> ';
        
        set_cond += '<a onclick = "elex_prepend_prod_attr_for_condition('+row_count+','+child_id+')" href="javascript:void(0);" <span class="elex-gpf-icon elex-gpf-icon-prepend" title="Prepend value" style="display: inline-block;" ></span></a> ';
        set_cond += '<a onclick = "elex_append_prod_attr_for_condition('+row_count+','+child_id+')" href="javascript:void(0);" <span class="elex-gpf-icon elex-gpf-icon-append" title="Append value" style="display: inline-block;" ></span></a><br>'
        set_cond += '<div id="select_prod_attr_append_conditions_'+row_count+'-'+child_id+'"></div>';
        set_cond += '</div>';
        set_cond += '</div>';

    jQuery('#elex_set_condition_div_'+row_count).append(set_cond);
    jQuery('#select_field' +row_count+'-'+child_id).hide();
    var prepend_data = "<div id = 'set_cond_select_operation_"+row_count+"-"+child_id+"'>";
    if(child_id != 0) {
        prepend_data += '<br><br>';
    }
    prepend_data += '<b style="font-size:20px;">Rule '+(child_id+1)+'</b> <a href="javascript:void(0);" onclick ="elex_add_more_conditions('+row_count+','+child_id+')" <span class="elex-gpf-icon elex-gpf-icon-add" title="Add new condition" style="display: inline-block;" ></span></a><select title="Choose the operator to execute the condition." id = "set_cond_select_operation_'+row_count+'-'+child_id+'_option" style="float:right;"><option>AND</option><option>OR</option></select></div><br>';
    jQuery('#set_cond_child_div_'+row_count+'-'+child_id).prepend(prepend_data);
}

function elex_add_text_field_in_cond (row_count,child_id) {

   jQuery('#text_field' +row_count+'-'+child_id).hide();
    jQuery('#select_field' +row_count+'-'+child_id).show();
    jQuery('#select_prod_attr_for_cond_'+row_count+'-'+child_id+'_product_attr').replaceWith('<input type="text"  id="select_prod_attr_for_cond_'+row_count+'-'+child_id+'_product_attr" style="width:150px;">');
}

function elex_add_select_field_in_cond (row_count, child_id) {
     jQuery('#text_field' +row_count+'-'+child_id).show();
    jQuery('#select_field' +row_count+'-'+child_id).hide();
    jQuery('#select_prod_attr_for_cond_'+row_count+'-'+child_id+'_product_attr').replaceWith('<select  id="select_prod_attr_for_cond_'+row_count+'-'+child_id+'_product_attr" style="width:150px;">' + prod_attributes_options + '</select>');
}

function elex_get_condition_parameters (row_count,child_id,prod_attributes_options) {
    var cond = '';
    if(prod_attributes_options == undefined) {
        prod_attributes_options = dom2;
    }
    var next_id = 0;
    if(jQuery('#elex_condition_lines_'+row_count+'-'+child_id+'> div').attr('id') !== undefined){
        next_id = parseInt((jQuery('#elex_condition_lines_'+row_count+'-'+child_id+'> div:last-child').attr('id')).split('-')[1])+1;
    }
    cond += '<div id="elex_condition_line_'+row_count+'_'+child_id+'-'+next_id+'"><select style="width:25%;" id="elex_condition_line_'+row_count+'_'+child_id+'-'+next_id+'_product_attr">'+prod_attributes_options+'</select>';
    cond += '<select style="width:25%;" id="elex_condition_line_'+row_count+'_'+child_id+'-'+next_id+'_elex_condition_options">';
    cond += '<optgroup label="String">';
    cond += '<option value="contains">Contains</option>';
    cond += '<option value="string_equals">Equals</option>';
    cond += '<option value="starts_with">Starts with</option>';
    cond += '<option value="ends_with">Ends with</option>';
    cond += '</optgroup>';
    cond += '<optgroup label="Arithmatic">';
    cond += '<option value="less_than">Less than</option>';
    cond += '<option value="less_than_equal">Less than or equal</option>';
    cond += '<option value="greater_than">Greater than</option>';
    cond += '<option value="greater_than_equal">Greater than or equal</option>';
    cond += '<option value="arith_equals">Equals</option>';
    cond += '</optgroup>';
    cond += '</select>';
    cond += '<input id="elex_condition_line_'+row_count+'_'+child_id+'-'+next_id+'_text_value" type="text" style="width:25%;" /> <a href="javascript:void(0);" <span class="elex-gpf-icon elex-gpf-icon-remove" title="Remove condition" style="display: inline-block;" ></span></a><br></div>';

    return cond;
}
function elex_prepend_prod_attr_for_condition (row_count,child_id,prod_attr_option) {
    if(prod_attr_option == undefined) {
        prod_attr_option = dom2;
    }
    var prepend_data = '';
    var next_id = 0;
    if(jQuery('#select_prod_attr_prepend_conditions_'+row_count+'-'+child_id+'> div').attr('id') !== undefined){
        next_id = parseInt((jQuery('#select_prod_attr_prepend_conditions_'+row_count+'-'+child_id+'> div:last-child').attr('id')).split('-')[1])+1;
    }

    prepend_data += '<div id="select_prod_attr_prepend_for_cond_'+row_count+'_'+child_id+'-'+next_id+'" style="padding: 1% 0px 0px 0px;"><select style="width:25%;" id="select_prod_attr_prepend_for_cond_'+row_count+'_'+child_id+'-'+next_id+'_product_attr">'+prod_attr_option+'</select>'+elex_get_delimeters('select_prod_attr_prepend_for_cond_'+row_count+'_'+child_id+'-'+next_id+'')+'';
    prepend_data += '<a href="javascript:void(0);" <span class="elex-gpf-icon elex-gpf-icon-remove" title="Remove" style="display: inline-block;" ></span></a>';
    prepend_data += '</div>';
    jQuery("#select_prod_attr_prepend_conditions_"+row_count+'-'+child_id).append(prepend_data);

}
function elex_append_prod_attr_for_condition (row_count,child_id,prod_attr_option) {
    if(prod_attr_option == undefined) {
        prod_attr_option = dom2;
    }
    var append_data = '';
    var next_id = 0;
    if(jQuery('#select_prod_attr_append_conditions_'+row_count+'-'+child_id+'> div').attr('id') !== undefined){
        next_id = parseInt((jQuery('#select_prod_attr_append_conditions_'+row_count+'-'+child_id+'> div:last-child').attr('id')).split('-')[1])+1;
    }
    append_data += '<div id="select_prod_attr_append_for_cond_'+row_count+'_'+child_id+'-'+next_id+'" style="padding: 1% 0px 0px 0px;">'+elex_get_delimeters('select_prod_attr_append_for_cond_'+row_count+'_'+child_id+'-'+next_id+'')+'<select style="width:25%;" id="select_prod_attr_append_for_cond_'+row_count+'_'+child_id+'-'+next_id+'_product_attr">'+prod_attr_option+'</select>';
    append_data += '<a href="javascript:void(0);" <span class="elex-gpf-icon elex-gpf-icon-remove" title="Remove" style="display: inline-block;" ></span></a>';
    append_data += '</div>';
    jQuery("#select_prod_attr_append_conditions_"+row_count+'-'+child_id).append(append_data)

}

function elex_get_delimeters (id) {
    var cond = '';
    cond += '<select style="width:25%;" id="'+id+'_elex_delimeter_options">';
    cond += '<option value="" >- Delimeters -</option>';
    cond += '<option value="space">Space</option>';
    cond += '<option value="comma">Comma</option>';
    cond += '<option value="dot">Dot</option>';
    cond += '<option value="less_than">Less than</option>';
    cond += '<option value="greater_than">Greater than</option>';
    cond += '<option value="equals">Equals</option>';
    cond += '<option value="double_equals">Double equals</option>';
    cond += '<option value="semicolon">Semicolon</option>';
    cond += '<option value="pipe">Pipe</option>';
    cond += '<option value="backslash">Backslash</option>';
    cond += '<option value="forward_slash">Forward slash</option>';
    
    cond += '</select>';

    return cond;
}

function elex_add_more_conditions(row_count,child_id) {
    var append_data = '';
    append_data += elex_get_condition_parameters(row_count,child_id);
    jQuery('#elex_condition_lines_'+row_count+'-'+child_id).append(append_data);
}
 function elex_gpf_view_feed_fun(file_path) {
    var file      = (jQuery('#elex_project_title').val()).replaceAll(' ', '_');
    var file_type = jQuery('#feed_file_type').val();
    var feed_url  = file_path + file + '.' + file_type;
    window.open(feed_url);
 }
 function elex_gpf_view_excluded_prods_fun(prod_type) {
    window.open("admin.php?page=elex-excluded-products" + "&feed_id="+ current_feed_id + "&date=" +current_saved_date_time + "&type=" + prod_type);
 }