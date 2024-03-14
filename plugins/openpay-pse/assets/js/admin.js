jQuery(document).ready(function () {
    if(jQuery("#woocommerce_openpay_pse_sandbox").length){
        is_sandbox();

        jQuery("#woocommerce_openpay_pse_sandbox").on("change", function(e){
            is_sandbox();
        });
    }
    
    function is_sandbox(){
        jQuery(".form-table input[type=text]").each(function(e){
            var sandbox = jQuery("#woocommerce_openpay_pse_sandbox").is(':checked');
            var inputField = jQuery(this).attr("name").search("test");
            if(sandbox && inputField != -1) {
                jQuery(this).parent().parent().parent().show();
            }else if(!sandbox && inputField == -1){
                jQuery(this).parent().parent().parent().show();
            }else{
                jQuery(this).parent().parent().parent().hide();
            }
        });
    }
});