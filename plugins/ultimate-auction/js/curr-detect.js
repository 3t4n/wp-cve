jQuery(document).ready(function(){
    
    var pp_stat = jQuery('.wdm_method_paypal').attr('checked');
    
    //alert(pp_stat);
    
    //jQuery('#nonpaypal').hide();
    
    var selected_curr=jQuery("#wdm_currency_id option:selected").attr('data-curr');
    
    if (selected_curr == 'npl') {
        jQuery('#nonpaypal').css('display', 'inline-block');
        jQuery('.wdm_method_paypal').attr('checked', false);
        jQuery('.wdm_method_paypal').attr('disabled', 'disabled');
    }
    
    jQuery('#wdm_currency_id').change(function(){
        
    var selected_curr=jQuery(this).find("option:selected").attr('data-curr');
    if (selected_curr == 'npl') {
       //jQuery('#nonpaypal').show();
       jQuery('#nonpaypal').css('display', 'inline-block');
        jQuery('.wdm_method_paypal').attr('checked', false);
        jQuery('.wdm_method_paypal').attr('disabled', 'disabled');
    }
    else{
        jQuery('#nonpaypal').hide();
        jQuery('.wdm_method_paypal').attr('disabled', false);
        if (pp_stat == 'checked') {
            jQuery('.wdm_method_paypal').attr('checked', true);
        }
    }
    });
    
    jQuery('#auction-settings-form').submit(function(){
        
        var selected_curr=jQuery(this).find('#wdm_currency_id option:selected').attr('data-curr');
        
        if (selected_curr == 'npl' && jQuery('.wdm_method_paypal').is(":checked" )) {
           
            //jQuery('#nonpaypal').show();
            jQuery('#nonpaypal').css('display', 'inline-block');
            jQuery('.wdm_method_paypal').attr('checked', false);
            jQuery('.wdm_method_paypal').attr('disabled', 'disabled');
            
            return false;
        }
        
        return true;
    });
});

