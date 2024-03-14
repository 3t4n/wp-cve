jQuery(document).ready(function(){

    jQuery('.color_sepctrum').wpColorPicker();

    jQuery('ul.nav-tab-wrapper li').click(function(){
        var tab_id = jQuery(this).attr('data-tab');
        jQuery('ul.nav-tab-wrapper li').removeClass('nav-tab-active');
        jQuery('.tab-content').removeClass('current');
        jQuery(this).addClass('nav-tab-active');
        jQuery("#"+tab_id).addClass('current');
    });

    jQuery('form#your-profile').attr('enctype', 'multipart/form-data');

    if(jQuery(".enable_email_section").is(":checked")){ 
        jQuery(".email_subject_and_body_message").show();
    }else{
        jQuery(".email_subject_and_body_message").hide();
    }
    jQuery(".enable_email_section").click(function() {
        if(jQuery(this).is(":checked")) {
            jQuery(".email_subject_and_body_message").fadeIn(300);
        } else {
            jQuery(".email_subject_and_body_message").fadeOut(200);
        }
    });

    if(jQuery(".cfafwr_login_reg_change_text").is(":checked")){ 
        jQuery(".cfafwr_log_reg").show();
    }else{
        jQuery(".cfafwr_log_reg").hide();
    }
    jQuery(".cfafwr_login_reg_change_text").click(function() {
        if(jQuery(this).is(":checked")) {
            jQuery(".cfafwr_log_reg").fadeIn(300);
        } else {
            jQuery(".cfafwr_log_reg").fadeOut(200);
        }
    });

    jQuery('.custom_field_type').on('change', function() {
        if ( jQuery(this).val().indexOf('billing') !== -1 || jQuery(this).val().indexOf('shipping') !== -1 ) {
            jQuery(".custom_html_sec").fadeOut(300);
            jQuery(".cfafwr_custom_class").fadeOut(300);
            jQuery(".custom_html").fadeOut(300);
            jQuery(".field_placeholder").fadeOut(300);
        } else if ( jQuery(this).val() == 'checkbox' ) {
            jQuery(".custom_html_sec").fadeOut(300);
            jQuery(".cfafwr_custom_class").fadeIn(300);
            jQuery(".custom_html").fadeIn(300);
            jQuery(".field_placeholder").fadeOut(300);
        } else {
            jQuery(".custom_html_sec").fadeOut(300);
            jQuery(".cfafwr_custom_class").fadeIn(300);
            jQuery(".custom_html").fadeIn(300);
            jQuery(".field_placeholder").fadeIn(300);
        }
    });

    jQuery('.custom_field_type').each(function(){
        var custom_field_type = jQuery(this).val();
        if ( custom_field_type.indexOf('billing') !== -1 || custom_field_type.indexOf('shipping') !== -1 ) {
            jQuery(".custom_html_sec").hide();
            jQuery(".cfafwr_custom_class").hide();
            jQuery(".custom_html").hide();
            jQuery(".field_placeholder").hide();
        } else if ( custom_field_type == 'checkbox' ) {
            jQuery(".custom_html_sec").hide();
            jQuery(".cfafwr_custom_class").show();
            jQuery(".custom_html").show();
            jQuery(".field_placeholder").hide();
        } else {
            jQuery(".custom_html_sec").hide();
            jQuery(".cfafwr_custom_class").show();
            jQuery(".custom_html").show();
            jQuery(".field_placeholder").show();
        }        
    });


    jQuery('.cfafwr_dl_data').sortable({
        update: function( event, ui ) {
            var value = new Array();
            jQuery('ul.cfafwr_dl_data li').each(function() {
                value.push(jQuery(this).find(".cfafwr_add_new_fields_inner").attr('value'));
            });

            var cfafwr_drop_index = new Array();
            jQuery('ul.cfafwr_dl_data li').each(function() {
                cfafwr_drop_index.push(jQuery(this).find('.cfafwr_add_new_fields_inner').attr("id"));
            });
            
            jQuery.ajax({
                type :'POST',       
                url  : ajaxurl,
                data :{
                    'action'  : 'cfafwr_filed_sortable',
                    'post_meta'    : value,
                },
                success: function(result){

                }
            });
        }
    });
});