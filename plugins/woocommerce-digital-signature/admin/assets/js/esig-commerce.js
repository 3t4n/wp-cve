/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



(function ($) {

    // next step click from sif pop

    // gravity add to document button clicked 
    $("#esig-insert-woo-tag").click(function () {

        //var form_id= $('input[name="esig_gf_form_id"]').val() ;

        var tagValue = $('select[name="esig-woocommerce-tag"]').val();
        
        var return_text ;
        // 
        if(tagValue === "woo-shortcode"){
            return_text = '[esig-woo-order-details]';
        }
        else {
            return_text = '{{' + tagValue + '}}';
        }
        esig_sif_admin_controls.insertContent(return_text);

        tb_remove();
    });


    $('#select-woo-form-list').click(function () {

        $(".chosen-drop").show(0, function () {
            $(this).parents("div").css("overflow", "visible");
        });

    });

    var default_value = $("#esign_woo_logic").val();
    
    if (default_value == "after_checkout") {
        $("#esign_woo_after_checkout_logic").show();
    }

    $("#esign_woo_logic").change(function () {
        var this_value = $(this).val();
        if (this_value == "after_checkout") {
            $("#esign_woo_after_checkout_logic").show();
        }
        if (this_value == "before_checkout") {
            $("#esign_woo_after_checkout_logic").hide();
        }
    });

    /* $('#esign_woo_sign_logic').change(function () {
     
     var selected = $(this).val();
     if (selected == "after_checkout") {
     $("#esig-agreement-required-box").hide();
     } else {
     $("#esig-agreement-required-box").show();
     }
     });*/

    /* $('#esign_woo_logic').change(function () {
     
     var selected = $(this).val();
     if (selected == "after_checkout") {
     
     $("#esig_agreement_required").attr("checked", false);
     $("#esig_agreement_required").attr("disabled", true);
     //$('#esig_agreement_required').attr('readonly', true);
     } else {
     $("#esig_agreement_required").removeAttr("disabled");
     }
     });*/

    $('#esig-woo-unsigned-agreement-send').click(function (e) {
        e.preventDefault();
        if ($("#esig-woo-unsigned-agreement-send").hasClass("already-created")) {
            return false;
        }
        var orderId = $("#esig_woo_order_id").val();

        $.post(wc_enhanced_select_params.ajax_url + "?action=esig_create_order_agreement", {esig_woo_order: orderId, esig_woo_nonce: esig_woo_params.esig_woo_order_nonce}).done(function (data) {
            if (data == "success") {
                $("#esig-woo-unsigned-agreement-send").addClass("already-created");
                $("#esig-woo-unsigned-agreement-send").html("Sucessfully sent");
            }
        });

    });


})(jQuery);
