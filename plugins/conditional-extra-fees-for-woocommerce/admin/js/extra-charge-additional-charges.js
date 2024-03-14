(function ($) {
    'use strict';
    jQuery(function ($) {

        function hideShowField(parent, child, show_hide) {
            var $ = jQuery;
            $(parent).on('change', function () {
                if (show_hide == 'show') {
                    if ($(parent).is(":checked")) {
                        $(child).fadeIn();
                    } else {
                        $(child).fadeOut();
                    }
                } else {
                    if ($(parent).is(":checked")) {
                        $(child).fadeOut();
                    } else {
                        $(child).fadeIn();
                    }
                }
            });
            jQuery(parent).trigger("change");
        }

        hideShowField('#pi_enable_additional_charges', '#additional-charges-container', 'show');

        hideShowField('#pi_enable_additional_charges_cart_weight', '#additional_charges_cart_weight_container', 'show');

        hideShowField('#pi_enable_additional_charges_cart_subtotal', '#additional_charges_cart_subtotal_container', 'show');

        hideShowField('#pi_enable_additional_charges_cart_quantity', '#additional_charges_cart_quantity_container', 'show');

        hideShowField('#pi_enable_additional_charges_product_quantity', '#additional_charges_product_quantity_container', 'show');

        hideShowField('#pi_enable_additional_charges_category_quantity', '#additional_charges_category_quantity_container', 'show');

        hideShowField('#pi_enable_additional_charges_shippingclass_quantity', '#additional_charges_shippingclass_quantity_container', 'show');

        hideShowField('#pi_enable_additional_charges_product_subtotal', '#additional_charges_product_subtotal_container', 'show');

        hideShowField('#pi_enable_additional_charges_category_subtotal', '#additional_charges_category_subtotal_container', 'show');

        hideShowField('#pi_enable_additional_charges_shippingclass_subtotal', '#additional_charges_shippingclass_subtotal_container', 'show');

        hideShowField('#pi_enable_additional_charges_product_weight', '#additional_charges_product_weight_container', 'show');

        hideShowField('#pi_enable_additional_charges_category_weight', '#additional_charges_category_weight_container', 'show');

        hideShowField('#pi_enable_additional_charges_shippingclass_weight', '#additional_charges_shippingclass_weight_container', 'show');

        function tabManager() {
            this.init = function () {
                this.tabClick();
            }

            this.tabClick = function () {
                var parent = this;
                $(document).on('click', '.additional-charges-tab', function () {
                    parent.removeAll();
                    $(this).addClass('pi-active-tab');
                    var target = $(this).data('target');
                    $(target).addClass('pi-active-tab');
                });
            }

            this.removeAll = function () {
                $('.additional-charges-tab').removeClass('pi-active-tab');
                $('.additional-charges-tab-content').removeClass('pi-active-tab');
            }

        }
        var tabManager_obj = new tabManager();
        tabManager_obj.init();

        function dynamicRow() {
            this.init = function (button, template, table, saved_rule_count, group, master_group, slug) {
                this.button = button;
                this.template = template;
                this.table = table;
                this.count = window[saved_rule_count];
                this.group = group;
                this.master_group = master_group;
                this.slug = slug;
                this.addEvent();
                this.removeRule();
                this.groupChangeEvent();
                this.masterGroupChangeEvent();

                this.validateSubmit();

                this.onfocusValidation();
            }

            this.onfocusValidation = function () {
                var parent = this;
                $(this.table).on('change focus unfocus', 'input, select', function () {

                    var val = $(this).val();
                    if (val == "" && $(this).prop('required') && !$(this).prop('disabled')) {
                        $(this).addClass('pi-error');
                    } else {
                        $(this).removeClass('pi-error');
                    }

                    var tab_valid = parent.tabValid();
                    if (!tab_valid) {
                        parent.tagError(true);
                    } else {
                        parent.tagError(false);
                    }
                });
            }

            this.tabValid = function () {
                var return_val = true;
                $('input, select', this.table).each(function () {
                    var val = $(this).val();
                    if ((val == "" || val == null || val == undefined) && $(this).prop('required') && !$(this).prop('disabled')) {
                        $(this).addClass('pi-error');
                        return_val = false;
                    }
                });
                return return_val;
            }

            this.validateSubmit = function () {
                var parent = this;
                $(document).on('click', '#pi-efrs-new-shipping-method-form', function (e) {
                    var return_val = parent.tabValid();

                    if (!return_val) {
                        parent.tagError(true);
                        e.preventDefault();
                    } else {
                        parent.tagError(false);
                    }
                });
            }

            this.tagError = function (toggle) {
                if (toggle) {
                    $('.badge', '#add-charges-tab-' + this.slug).remove();
                    $('#add-charges-tab-' + this.slug).addClass('pi-tab-error').append(' <span class="badge badge-light">Error</span>');
                } else {
                    $('.badge', '#add-charges-tab-' + this.slug).remove();
                    $('#add-charges-tab-' + this.slug).removeClass('pi-tab-error');
                }
            }

            this.groupChangeEvent = function () {
                var parent = this;
                $(document).on('click', this.group, function () {
                    parent.groupToggle();

                    var tab_valid = parent.tabValid();
                    if (!tab_valid) {
                        parent.tagError(true);
                    } else {
                        parent.tagError(false);
                    }
                });
                $(this.group).trigger('change');
            }

            this.masterGroupChangeEvent = function () {
                var parent = this;
                $(document).on('click', this.master_group, function () {
                    parent.masterGroupToggle();
                });
                $(this.master_group).trigger('change');
            }

            this.masterGroupToggle = function () {
                var val = $(this.master_group).is(":checked");
                if (val) {
                    this.disableEnable(true);
                } else {
                    this.disableEnable(false);
                }
            }

            this.groupToggle = function () {
                var val = $(this.group).is(":checked");
                if (val) {
                    this.disableEnable(true);
                } else {
                    this.disableEnable(false);
                }
            }

            this.disableEnable = function (action) {
                if (action) {
                    $('input, select', this.table).prop('disabled', false);
                } else {
                    $('input, select', this.table).prop('disabled', true);
                }
            }

            this.removeRule = function () {
                var parent = this;
                $(document).on('click', '.delete-additional-charges', function () {
                    $(this).parent().parent().remove();
                    var tab_valid = parent.tabValid();
                    if (!tab_valid) {
                        parent.tagError(true);
                    } else {
                        parent.tagError(false);
                    }
                });
            }


            this.addEvent = function () {
                var parent = this;
                $(document).on('click', this.button, function () {
                    var template = parent.getTemplate();
                    jQuery('tbody', parent.table).append(template.clone());
                    parent.count++;
                    dynamicFields();
                });
            }

            this.getTemplate = function () {
                var content = $(this.template).html().trim();
                var content = content.replace(/{{count}}/g, this.count);
                return $(content);
            }
        }

        var dynamicRow_cart_weight = new dynamicRow();
        dynamicRow_cart_weight.init('#add_cart_weight_charges_range', '#cart_weight_charges_template', '#cart_weight_charges_table', 'pi_cart_weight_charges_count', '#pi_enable_additional_charges_cart_weight', '#pi_enable_additional_charges', 'weight');

        var dynamicRow_cart_subtotal = new dynamicRow();
        dynamicRow_cart_subtotal.init('#add_cart_subtotal_charges_range', '#cart_subtotal_charges_template', '#cart_subtotal_charges_table', 'pi_cart_subtotal_charges_count', '#pi_enable_additional_charges_cart_subtotal', '#pi_enable_additional_charges', 'subtotal');

        var dynamicRow_cart_quantity = new dynamicRow();
        dynamicRow_cart_quantity.init('#add_cart_quantity_charges_range', '#cart_quantity_charges_template', '#cart_quantity_charges_table', 'pi_cart_quantity_charges_count', '#pi_enable_additional_charges_cart_quantity', '#pi_enable_additional_charges', 'cart_quantity');

        var dynamicRow_product_quantity = new dynamicRow();
        dynamicRow_product_quantity.init('#add_product_quantity_charges_range', '#product_quantity_charges_template', '#product_quantity_charges_table', 'pi_product_quantity_charges_count', '#pi_enable_additional_charges_product_quantity', '#pi_enable_additional_charges', 'product_quantity');

        var dynamicRow_category_quantity = new dynamicRow();
        dynamicRow_category_quantity.init('#add_category_quantity_charges_range', '#category_quantity_charges_template', '#category_quantity_charges_table', 'pi_category_quantity_charges_count', '#pi_enable_additional_charges_category_quantity', '#pi_enable_additional_charges', 'category_quantity');

        var dynamicRow_shippingclass_quantity = new dynamicRow();
        dynamicRow_shippingclass_quantity.init('#add_shippingclass_quantity_charges_range', '#shippingclass_quantity_charges_template', '#shippingclass_quantity_charges_table', 'pi_shippingclass_quantity_charges_count', '#pi_enable_additional_charges_shippingclass_quantity', '#pi_enable_additional_charges', 'shippingclass_quantity');

        var dynamicRow_product_subtotal = new dynamicRow();
        dynamicRow_product_subtotal.init('#add_product_subtotal_charges_range', '#product_subtotal_charges_template', '#product_subtotal_charges_table', 'pi_product_subtotal_charges_count', '#pi_enable_additional_charges_product_subtotal', '#pi_enable_additional_charges', 'product_subtotal');

        var dynamicRow_category_subtotal = new dynamicRow();
        dynamicRow_category_subtotal.init('#add_category_subtotal_charges_range', '#category_subtotal_charges_template', '#category_subtotal_charges_table', 'pi_category_subtotal_charges_count', '#pi_enable_additional_charges_category_subtotal', '#pi_enable_additional_charges', 'category_subtotal');

        var dynamicRow_shippingclass_subtotal = new dynamicRow();
        dynamicRow_shippingclass_subtotal.init('#add_shippingclass_subtotal_charges_range', '#shippingclass_subtotal_charges_template', '#shippingclass_subtotal_charges_table', 'pi_shippingclass_subtotal_charges_count', '#pi_enable_additional_charges_shippingclass_subtotal', '#pi_enable_additional_charges', 'shippingclass_subtotal');

        var dynamicRow_product_weight = new dynamicRow();
        dynamicRow_product_weight.init('#add_product_weight_charges_range', '#product_weight_charges_template', '#product_weight_charges_table', 'pi_product_weight_charges_count', '#pi_enable_additional_charges_product_weight', '#pi_enable_additional_charges', 'product_weight');

        var dynamicRow_category_weight = new dynamicRow();
        dynamicRow_category_weight.init('#add_category_weight_charges_range', '#category_weight_charges_template', '#category_weight_charges_table', 'pi_category_weight_charges_count', '#pi_enable_additional_charges_category_weight', '#pi_enable_additional_charges', 'category_weight');

        var dynamicRow_shippingclass_weight = new dynamicRow();
        dynamicRow_shippingclass_weight.init('#add_shippingclass_weight_charges_range', '#shippingclass_weight_charges_template', '#shippingclass_weight_charges_table', 'pi_shippingclass_weight_charges_count', '#pi_enable_additional_charges_shippingclass_weight', '#pi_enable_additional_charges', 'shippingclass_weight');

        function dynamicFields() {
            jQuery(".pi_extra_charge_dynamic_value").selectWoo({
                width: '100%',
                ajax: {
                    url: window.ajaxurl,
                    dataType: 'json',
                    type: "GET",
                    delay: 250,
                    data: function (params) {
                        return {
                            keyword: params.term,
                            action: "pi_cefw_extra_charge_dynamic_value_" + jQuery(this).data("get")
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };

                    },
                }
            });
        }
        dynamicFields();

    });
})(jQuery);