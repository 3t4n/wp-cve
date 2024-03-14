(function ($) {
    'use strict';

    /**
    * SmartBill checkout changes.
    *
    * This file adds different functionalities to the checkout (woocommerce) page.
    *
    * @link   /public/js/smartbill-woocommerce-public.js
    * @author Intelligent IT SRL <vreauapi@smartbill.ro>.
    * @since  1.0.0
    */
    $(document).ready(function () {
        try {

            //Check if smartbill setting is enabled.
            if (smartbill_billing) {

                //Hide/show billing fields. 
                if ('pj' == $("#smartbill_billing_type").val()) {
                    $("#billing_company_field").css("display", 'none');
                }
                if ('pf' == $("#smartbill_billing_type").val()) {
                    $("#smartbill_billing_cif_field").css("display", 'none');
                    $("#smartbill_billing_company_name_field").css("display", 'none');
                    $("#smartbill_billing_nr_reg_com_field").css("display", 'none');
                }
                $("#smartbill_billing_type").change(function () {
                    if ('pj' == $(this).val()) {
                        $("#billing_company_field").css("display", 'none');
                        $("#smartbill_billing_cif_field").css("display", 'block');
                        $("#smartbill_billing_company_name_field").css("display", 'block');
                        $("#smartbill_billing_nr_reg_com_field").css("display", 'block');
                    } else {
                        $("#billing_company_field").css("display", 'block');
                        $("#smartbill_billing_cif_field").css("display", 'none');
                        $("#smartbill_billing_company_name_field").css("display", 'none');
                        $("#smartbill_billing_nr_reg_com_field").css("display", 'none');
                    }
                });

                //Hide/show shipping fields. 
                if ('pj' == $("#smartbill_shipping_type").val()) {
                    $("#shipping_company_field").css("display", 'none');
                }
                if ('pf' == $("#smartbill_shipping_type").val()) {
                    $("#smartbill_shipping_cif_field").css("display", 'none');
                    $("#smartbill_shipping_company_name_field").css("display", 'none');
                    $("#smartbill_shipping_nr_reg_com_field").css("display", 'none');
                }
                $("#smartbill_shipping_type").change(function () {
                    if ('pj' == $(this).val()) {
                        $("#shipping_company_field").css("display", 'none');
                        $("#smartbill_shipping_cif_field").css("display", 'block');
                        $("#smartbill_shipping_company_name_field").css("display", 'block');
                        $("#smartbill_shipping_nr_reg_com_field").css("display", 'block');
                    } else {
                        $("#shipping_company_field").css("display", 'block');
                        $("#smartbill_shipping_cif_field").css("display", 'none');
                        $("#smartbill_shipping_company_name_field").css("display", 'none');
                        $("#smartbill_shipping_nr_reg_com_field").css("display", 'none');
                    }
                });

                //Coppy values to shipping if ship to different address is enabled.
                $('#ship-to-different-address-checkbox').change(function () {
                    if ($(this).is(":checked")) {
                        if ('pj' == $('#smartbill_billing_type').find(":selected").val()) {
                            $("#shipping_company_field").css("display", 'none');
                            $("#smartbill_shipping_cif_field").css("display", 'block');
                            $("#smartbill_shipping_company_name_field").css("display", 'block');
                            $("#smartbill_shipping_nr_reg_com_field").css("display", 'block');
                        } else {
                            $("#shipping_company_field").css("display", 'block');
                            $("#smartbill_shipping_cif_field").css("display", 'none');
                            $("#smartbill_shipping_company_name_field").css("display", 'none');
                            $("#smartbill_shipping_nr_reg_com_field").css("display", 'none');
                        }

                        $("#smartbill_shipping_type option[value='" + $('#smartbill_billing_type').find(":selected").val() + "']").prop('selected', true);
                        $('#smartbill_shipping_cif').val($('#smartbill_billing_cif').val());
                        $('#smartbill_shipping_company_name').val($('#smartbill_billing_company_name').val());
                        $('#smartbill_shipping_nr_reg_com').val($('#smartbill_billing_nr_reg_com').val());
                    }
                });

            }

        } catch (error) { }

        try {
            $("#smartbill_company_details-checkbox").on("change", function () {
                $("div.smartbill_company_details").hide();
                if ($(this).is(":checked")) {
                    $("div.smartbill_company_details").slideDown();
                }
            }).trigger("change");
        } catch (ex) { }
    });
})(jQuery);
