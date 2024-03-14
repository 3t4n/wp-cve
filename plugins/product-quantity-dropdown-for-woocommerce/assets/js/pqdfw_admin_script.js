//jquery tab
jQuery(document).ready(function(){

    if(PQDFW_DATA.quantity_product_rule == "specific_product"){
            jQuery('.product_specific').show();
    }else{
            jQuery('.product_specific').hide();
    }



    jQuery("input[name='quantity_product_rule']").click(function() {

        var test =jQuery(this).val();
        if(test == "all_product"){
            jQuery('.product_specific').hide();
        }else{
            jQuery('.product_specific').show();
        }
    }); 

});