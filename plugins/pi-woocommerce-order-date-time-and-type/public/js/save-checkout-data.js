(function ($) {
    'use strict';

    function saveCheckoutField() {

        this.fields = ['pi_system_delivery_date', 'pi_delivery_time', 'billing_email', 'billing_first_name', 'billing_last_name', 'billing_phone', 'billing_company', 'shipping_first_name', 'shipping_last_name', 'shipping_company', 'order_comments', 'createaccount', 'ship-to-different-address-checkbox'];

        this.init = function () {
            this.detectChange();
            this.setData();
            this.pickupLocationChange();
        }

        this.detectChange = function () {
            var parent = this;
            jQuery(document).on("keyup keypress change", "#pi_system_delivery_date, #pi_delivery_time, #billing_email, #billing_phone, #createaccount, #ship-to-different-address-checkbox, input.input-text, textarea.input-text", function () {
                parent.getCheckoutData();
            });
        }

        this.getCheckoutData = function () {

            var data;

            var length = this.fields.length;
            for (var i = 0; i < length; i++) {
                var index = this.fields[i];
                var element = jQuery("#" + index);
                if (element.is(':checkbox')) {
                    data = jQuery("#" + index).prop("checked");
                } else if (index == 'pickup_location') {
                    if (jQuery("select[name='pickup_location']").length) {
                        data = jQuery("select[name='pickup_location']").val();
                    } else if (jQuery("input[name='pickup_location']").length) {
                        data = jQuery("input[name='pickup_location']:checked").val();
                    } else {
                        data = null;
                    }

                } else {
                    data = jQuery("#" + index).val();
                }
                if (data !== "" && data != undefined) {
                    localStorage.setItem("pisol_" + index, data);
                } else if (data == "") {
                    localStorage.removeItem("pisol_" + index);
                }
            }

        }

        this.setData = function () {


            var length = this.fields.length;
            for (var i = 0; i < length; i++) {
                var field = this.fields[i];
                this.setIndividualFields(field);
            }

        }

        this.setIndividualFields = function (field) {
            var data = localStorage.getItem("pisol_" + field);
            var element = jQuery("#" + field);
            var present_val = element.val();

            if (element.is(':checkbox') && data != null && data != 'undefined' && data != "") {
                if (data == "true") {
                    element.prop('checked', true);
                } else {
                    element.prop('checked', false);
                }
            } else {
                if (data != null && data != 'undefined' && data != "" && present_val != data) {
                    if (field != 'pi_system_delivery_date') {
                        element.val(data);
                    }
                }
            }

        }

        this.pickupLocationChange = function () {
            var parent = this;
            jQuery(document).on('change', '.pisol-location-radio, #pickup_location', function () {
                var data = null;
                if (jQuery("select[name='pickup_location']").length) {
                    data = jQuery("select[name='pickup_location']").val();
                } else if (jQuery("input[name='pickup_location']").length) {
                    data = jQuery("input[name='pickup_location']:checked").val();
                }
                if (data !== "" && data != undefined && data != null) {
                    localStorage.setItem("pisol_pickup_location", data);
                } else if (data == "") {
                    localStorage.removeItem("pisol_pickup_location");
                }
            });
        }
    }

    jQuery(function ($) {
        var saveCheckoutFieldObj = new saveCheckoutField();
        saveCheckoutFieldObj.init();
    });


})(jQuery);