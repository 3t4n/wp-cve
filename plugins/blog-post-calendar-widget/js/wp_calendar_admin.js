function wp_calander_check_taxonomy( p_id , element ){
    var post_type = jQuery('select[name="'+element+'"] option:selected').val();
    
    jQuery.ajax({
        type:'post',
        url: wpCalancerAdminObj.ajaxurl,
        data:'action=wp_calender_get_taxonomy&post_type='+post_type,
        success: function (data){
            data = jQuery.parseJSON( data );
            if(data == 'false' ){
                jQuery('#'+p_id).hide();
                var sel_tax1 = jQuery('#'+p_id+' select[rel="taxonomy"]');
                sel_tax1.empty();
                var sel_term1 = jQuery('#'+p_id+' select[rel="term"]');
                sel_term1.empty();
            }
            else{
                jQuery('#'+p_id).show();
                var it = jQuery('#'+p_id+' select[rel="term"]');
                jQuery(it).parent('p').show();
                var sel_tax = jQuery('#'+p_id+' select[rel="taxonomy"]');
                sel_tax.empty();
                var newOptions = data;
                
                jQuery.each(newOptions, function(key, value) {
                  sel_tax.append(jQuery("<option></option>")
                     .attr("value", key).text(value));
                });
                
            }
        }
    });
}

function wp_calander_check_terms( p_id, element ){
    var taxonomy = jQuery('select[name="'+element+'"] option:selected').val();
    
    jQuery.ajax({
        type:'post',
        url: wpCalancerAdminObj.ajaxurl,
        data:'action=wp_calender_get_terms&taxonomy='+taxonomy,
        success: function (data){
            if(data == 'false' ){
                jQuery('#'+p_id).hide();
            }
            else{
                data = jQuery.parseJSON( data );
                var sel_term = jQuery('#'+p_id+' select[rel="term"]');
                sel_term.empty();
                var newOptions = data;
                jQuery.each(newOptions, function(key, value) {
                  sel_term.append(jQuery("<option></option>")
                     .attr("value", key).text(value));
                });
            }
        }
    });
}