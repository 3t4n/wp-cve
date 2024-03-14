jQuery(document).ready(function ($) {
    let prefix = "Wcd";
    showDiscountRange = function (field) {
        let field_name = $(field).attr('name');
        let field_value = $(field).val();
        if (field_value === 'flat' || field_value === 'percentage')
            manageFields(field_name, field_value);
    };
    $("input[type=radio]:checked").each(function () {
        let field_name = $(this).attr('name');
        let field_value = $(this).val();
        if (field_value === 'flat' || field_value === 'percentage')
            manageFields(field_name, field_value);
    });

    changeTitle = function () {
        let count = 0;
        $(".cmb-group-title").each(function () {
            let discount_title = $("[name='" + prefix + "_category_discount_rules_group[" + count + "][" + prefix + "_discount_name]']").val();
            if (discount_title !== "" && typeof discount_title !== "undefined") {
                let is_enabled = $("[name='" + prefix + "_category_discount_rules_group[" + count + "][" + prefix + "_enable_discount_rule]']:checked").val();
                let enabled_text = '';
                if (parseInt(is_enabled) === 1) {
                    enabled_text = ' (Active)';
                }
                count++;
                $(this).text(discount_title + enabled_text);
            }
        });
    };
    changeTitle();

    $(document).on("click", ".cmb-remove-group-row", function () {
        setTimeout(changeTitle, 25);
    });

    function manageFields(field_name, field_value) {
        let key = field_name.replace(/\D/g, '');
        $(".cmb-repeatable-grouping").each(function () {
            let iterator = $(this).data('iterator');
            if (parseInt(key) === parseInt(iterator)) {
                if (field_value === 'flat') {
                    $(this).find('.cmb-type-text-money').hide();
                } else {
                    $(this).find('.cmb-type-text-money').show();
                }
            }
        });
    }

    resetFields = function () {
        $("input[type=radio]:checked").each(function () {
            let field_name = $(this).attr('name');
            let field_value = $(this).val();
            if (field_value === 'flat' || field_value === 'percentage')
                manageFields(field_name, field_value);
        });
    };
});