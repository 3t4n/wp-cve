<?php

if(!isset($plem_price_settings[$elpm_shop_com.'_custom_import_settings'])) {
    
    $isettings = new stdClass();
    $isettings->use_custom_import      = 0;
    $isettings->use_custom_export         = 0;
    
    $isettings->onimportstatus_overide = 0;
    $isettings->notfound_setpending    = '';
    
    $isettings->first_row_header       = 1;
    $isettings->custom_import_columns  = "";
    $isettings->custom_export_columns  = "";
    $isettings->german_numbers         = 0;
    $isettings->delimiter              = ','; 
    $isettings->delimiter2             = ',';
    $isettings->allow_remote_import    = 0;
    $isettings->remote_import_ips      = ''; 
    
    $plem_price_settings[$elpm_shop_com.'_custom_import_settings'] = $isettings;
    //SAVE EMPTY OPTION SET
    $this->saveOptions();
}else {
    $isettings = $plem_price_settings[$elpm_shop_com.'_custom_import_settings'];
}

    
if(pelm_read_sanitized_request_parm('save_import_settings') && strtoupper(pelm_read_sanitized_server_parm('REQUEST_METHOD','')) === 'POST') {
      
    $isettings->use_custom_import      = pelm_read_sanitized_request_parm('use_custom_import', 0);
    $isettings->use_custom_export      = pelm_read_sanitized_request_parm('use_custom_export', 0);
    $isettings->onimportstatus_overide = pelm_read_sanitized_request_parm('onimportstatus_overide', 0);
    $isettings->notfound_setpending    = pelm_read_sanitized_request_parm('notfound_setpending', '');
    $isettings->first_row_header       = pelm_read_sanitized_request_parm('first_row_header', 1);
    $isettings->custom_import_columns  = pelm_read_sanitized_request_parm('custom_import_columns', "");
    $isettings->custom_export_columns  = pelm_read_sanitized_request_parm('custom_export_columns', "");
    $isettings->german_numbers         = pelm_read_sanitized_request_parm('german_numbers', 0);
    $isettings->delimiter              = pelm_read_sanitized_request_parm('delimiter', ",");
    $isettings->delimiter2             = pelm_read_sanitized_request_parm('delimiter2', ",");
    $isettings->allow_remote_import    = 0;
    $isettings->remote_import_ips      = "";
    
    $this->saveOptions();
}    
  
if(!$isettings->delimiter) {
    $isettings->delimiter = ',';
}

if(!$isettings->delimiter2) {
    $isettings->delimiter2 = ',';
}
  
?>
<input name="pelm_nonce_check" type="hidden" value="<?php echo esc_attr(wp_create_nonce('pelm_update_settings')); ?>" />
<input name="pelm_security" type="hidden" value="<?php echo esc_attr(pelm_get_nonce("pelm_nonce")); ?>" />

<div id="settings-panel" style="display:none;">
<div>
    <h2> <?php echo esc_html__("Custom Import Options", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> </h2>
    <h3><?php echo esc_html__("Full import options", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></h3>
    <table>
        <tr>
            <td>
                <label  class="note"><?php echo esc_html__("(Use following two opinions only if you are importing CSV containing full product list)", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></label>
                <br/>
                
                <label><?php echo esc_html__("For imported product override status:", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></label>
                <select disabled>
                    <option selected="selected" value="disabled" >Not available in this version</option>
                    <option value="" ><?php echo esc_html__("Do not override (use data from CSV)", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></option>
                    <option value="published" ><?php echo esc_html__("Always set 'Published'", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></option>
                    <option value="cond_published_pending" ><?php echo esc_html__("If stock > 0 set 'Published', otherwise 'Pending'", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></option>
                </select>
            </td>
        </tr>
    </table>
    
    <h3> <?php echo esc_html__("CSV custom format", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>  <label  class="note"><?php echo esc_html__("(Allows you to use CSV files exported form external programs)", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></label> </h3>
    <table>
        <tr>
            <td colspan="2" >
                <input type="checkbox" value="1" name="german_numbers" <?php echo esc_attr($isettings->german_numbers ? " checked='checked' " : ""); ?> /><label><?php echo esc_html__("Thousand separator is '.' decimal is ','", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></label>        
                <br/>
                <label><?php echo esc_html__("CSV delimiter:", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></label><input style="width:20px;" type="text" value="<?php echo esc_attr($isettings->delimiter); ?>" name="delimiter" />        
                <br/>
                <input type="checkbox" value="1" name="use_custom_import" <?php echo esc_attr($isettings->use_custom_import ? " checked='checked' " : ""); ?> /><label><?php echo esc_html__("Use custom import format", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></label>
                <br/>
                <input type="checkbox" value="1" name="first_row_header" <?php echo esc_attr($isettings->first_row_header ? " checked='checked' " : ""); ?> /><label><?php echo esc_html__("First row is header row (skip it)", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></label>        
                <br/>
                <label><?php echo esc_html__("Input columns one by one in order your CSV file will give", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>:</label>
                <br/>
                <label class="note" ><?php echo esc_html__("(You must include ID or SKU)", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>:</label>
                <br/>
                <input type="hidden" name="custom_import_columns"  />
                <select id="custom_import_columns" multiple="multiple" > 
                </select>
            </td>
        </tr>
    </table>
    
    <hr/>
    
    <h3> <?php echo esc_html__("Remote auto-import options", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> </h3>
    <table class="disabled">
        <tr>
            <td colspan="2" >
                <input disabled type="checkbox" value="1" name="allow_remote_import" <?php echo esc_attr($isettings->allow_remote_import) ? " checked='checked' " : ""; ?> /><label><?php echo esc_html__("Allow remote import", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></label>
                <br/>
                <label><?php echo esc_html__("Allow update form following IP addresses", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>:</label>
                <br/>
                <label  class="note">(<?php echo esc_html__("comma separated for multiple, leave blank for no restrictions", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>)</label>
                <br/>
                <input placeholder="Not available with this version" disabled class="full-width" type="text" value="<?php echo esc_attr($isettings->remote_import_ips); ?>" name="remote_import_ips" />
                <br/>
            </td>
        </tr>
    </table>    
    <hr/>
    <!-- CUSTOM EXPORT-->
    <!--added by Nikola-->
    <h2> <?php echo esc_html__("Custom Export Options", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?> </h2>
    <h3> <?php echo esc_html__("CSV custom export format", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>  <label  class="note"><?php echo esc_html__("(Allows you to use export CSV files with rows of your choice)", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></label> </h3>
    
    <table>
        <tr>
            <td colspan="2" >
                <label><?php echo esc_html__("CSV delimiter:", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></label><input style="width:20px;" type="text" value="<?php echo esc_attr($isettings->delimiter2); ?>" name="delimiter2" />        
                <br/>
                <input type="checkbox" value="1" name="use_custom_export" <?php if($isettings->use_custom_export) echo " checked='checked' "; else echo ""; ?> /><label><?php echo esc_html__("Use custom export format", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></label>
                <br/>
                <br/>
                <label><?php echo esc_html__("Input columns one by one in order your CSV file will export", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>:</label>
                <br/>
                <label class="note" ><?php echo esc_html__("(You must include ID or SKU)", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?>:</label>
                <br/>
                <input type="hidden" name="custom_export_columns"  />
                <select id="custom_export_columns" multiple="multiple" > 
                </select>
            </td>
        </tr>
    </table>    
    <!-- /// -->
    <script type="text/javascript">
        function pelm_showSettings(){
            jQuery('#settings-panel').show();
        }

        jQuery(document).ready(function(){
            
            jQuery('SELECT[name="onimportstatus_overide"]').val('<?php echo esc_attr($isettings->onimportstatus_overide); ?>');
            
            jQuery('#cmdSettingsSave').click(function(){
                doLoad(true);
            });
            
            jQuery('#cmdSettingsCancel').click(function(){
                jQuery('#settings-panel').hide();
            });
            
            jQuery("a#download_utility").attr("href", 'https://holest.com/bulk-product-manager-for-woo-commerce');
            
        });
    
        jQuery(window).load(function(){
             setTimeout(function(){
                 try{
                    
                    var inp = jQuery('INPUT[name="custom_import_columns"]');
                    var inp2 = jQuery('INPUT[name="custom_export_columns"]');
                    var select = jQuery('SELECT#custom_import_columns');
                    var select2 = jQuery('SELECT#custom_export_columns'); 
                    var n = 0;
                    DG.getSettings().columns.map(function(c){
                        select.append(jQuery('<option value="' + c.data + '">' + DG.getSettings().colHeaders[n] + '</option>'));
                        if(DG.getSettings().colHeaders[n] == "Category" && jQuery('#dg_wooc')[0]){
                            select2.append(jQuery('<option value="categories_names">' + DG.getSettings().colHeaders[n] + '</option>'));
                            select2.append(jQuery('<option value="categories_paths">Category path</option>'));
                        }else{
                            select2.append(jQuery('<option value="' + c.data + '">' + DG.getSettings().colHeaders[n] + '</option>'));
                        }
                        n++;
                        return c.data;
                    });
                    
                    var value = "<?php echo esc_attr($isettings->custom_import_columns); ?>".split(",");
                    var value2 = "<?php echo esc_attr($isettings->custom_export_columns); ?>".split(",");
                    var tmp = [];
                    value.map(function(v){
                        if(v){
                            tmp.push(v);    
                        }
                    });
                    value = tmp;
                    var tmp2 = [];
                    value2.map(function(v){
                        if(v){
                            tmp2.push(v);    
                        }
                    });
                    
                    value2 = tmp2;
                    inp.val(tmp.join(","));
                    inp2.val(tmp2.join(","));
                    select.chosen();
                    select.val(value);
                    select.trigger("chosen:updated");
                    var cnt = select.next(".chosen-container").find("UL.chosen-choices");
                    select2.chosen();
                    select2.val(value2);
                    select2.trigger("chosen:updated");
                    var cnt2 = select2.next(".chosen-container").find("UL.chosen-choices");
                    //order
                    for(var i = 0 ; i < value.length; i++ ){
                        var opt = select.find('option[value="'+ value[i] +'"]');
                        cnt.find("a[data-option-array-index='" + opt.index() + "']").parent().insertBefore(cnt.find(".search-field"));
                    }
                    
                    for(var i = 0 ; i < value2.length; i++ ){
                        var opt2 = select2.find('option[value="'+ value2[i] +'"]');
                        cnt2.find("a[data-option-array-index='" + opt2.index() + "']").parent().insertBefore(cnt2.find(".search-field"));
                    }
                    
                    select.change(function(){
                        setTimeout(function(){
                            var newval = [];
                            cnt.find("a[data-option-array-index]").map(function(item){
                                var ind = parseInt( jQuery(this).attr("data-option-array-index"));
                                newval.push(
                                    jQuery(select.find("option")[ind]).attr("value")
                                );
                            });
                            inp.val(newval.join(","));
                        },50);
                    });
                    
                    select2.change(function(){
                        setTimeout(function(){var newval = [];
                            cnt2.find("a[data-option-array-index]").map(function(item){
                                var ind = parseInt( jQuery(this).attr("data-option-array-index"));
                                newval.push(
                                    jQuery(select2.find("option")[ind]).attr("value")
                                );
                            });
                            inp2.val(newval.join(","));        
                        }, 50);
                    });
                    
                    jQuery(document).on('click','#custom_import_columns_chosen a.search-choice-close',function(e){
                        e.preventDefault();
                        select.trigger('change');
                    });
                    
                    jQuery(document).on('click','#custom_export_columns_chosen a.search-choice-close',function(e){
                        e.preventDefault();
                        select2.trigger('change');
                    });
                        
                 }catch(e){
                    alert(e.name + ":" + e.message);
                 }
             },2000);
        });
    </script>    
  
  <button id="cmdSettingsCancel" ><?php echo esc_html__("Cancel", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button>
  <button id="cmdSettingsSave" ><?php echo esc_html__("Save", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></button>
</div>
</div>
