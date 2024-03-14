(function ($) {
    'use strict';

    /**
     * Time slot maker
     */
    function time_slot_maker() {
        this.init = function (field) {
            this.field = field;
            this.sortable();
            this.slot_count = 0;
            this.addFieldButton();
            this.time_picker();
            this.step_remove();
            this.loadStoredValue();
            this.clearTime();

        }

        this.sortable = function () {
            jQuery("#" + this.field + "_container").sortable();
        }

        this.time_picker = function () {
            jQuery('body').on('focus', '.slot_time_picker', function () {
                var obj = jQuery(this).timepicker({
                    interval: 5,
                    scrollbar: true,
                    dynamic: false
                });

            });
        }

        this.addFieldButton = function () {
            var parent = this;
            jQuery(".pi_add_time_slot").on('click', function () {
                var target = jQuery(this).data('slot');
                if (target == parent.field) {
                    parent.addField();
                }
            })
        }

        this.step_remove = function () {
            jQuery('body').on('click', ".pi-step-close", function () {
                var target = jQuery(this).parent().parent();
                target.remove();

            })
        }

        this.addField = function (from = "", to = "", order_limit = "") {
            var html = this.time_slot(from, to, order_limit);
            this.slot_count++;
            jQuery("#" + this.field + "_container").append(html);
        }

        this.clearTime = function () {
            jQuery("body").on('click', '.pi-slot-clear', function () {
                jQuery(this).prev('input').val("");
            });
        }

        this.time_slot = function (from, to, order_limit) {
            var from = '<input readonly type="text" class="slot_time_picker from_time form-control" name="' + this.field + '[' + this.slot_count + '][from]" value="' + from + '">';
            var to = '<input readonly type="text" class="slot_time_picker to_time form-control" name="' + this.field + '[' + this.slot_count + '][to]"  value="' + to + '">';
            var order_limit = '<input type="number" min="0" step="1" class="form-control free-version" readonly name="' + this.field + '[' + this.slot_count + '][order_limit]"  value="' + order_limit + '" placeholder="' + pisol_dtt_translation.order_limit_on_time_slot + '" title="' + pisol_dtt_translation.order_limit_on_time_slot + '">';
            var close = '<a class="btn btn-primary btn-sm pi-step-close text-light"><span class="dashicons dashicons-trash"></span></a>';
            var structure = '<div class="row align-items-center mb-2"><div class="col-3">' + from + '<span class="pi-slot-clear">x</span></div><div class="col-1">' + pisol_dtt_translation.slot_time_divider_to + '</div><div class="col-3">' + to + '<span class="pi-slot-clear">x</span></div><div class="col-3">' + order_limit + '</div><div class="col-1">' + close + '</div></div>';
            return structure;
        }

        this.loadStoredValue = function () {
            if (typeof window[this.field] !== 'undefined') {
                var stored = window[this.field];
                if (typeof stored !== 'undefined') {
                    for (var index = 0; index < stored.length; index++) {
                        this.slot_count = index;
                        //console.log(stored[index]['from']);
                        this.addField(stored[index]['from'], stored[index]['to'], stored[index]['order_limit']);
                    }
                }
            }
        }

    }

    jQuery(function ($) {

        var obj1 = new time_slot_maker();
        obj1.init("pi_general_time_slot_delivery");

        var obj2 = new time_slot_maker();
        obj2.init("pi_general_time_slot_pickup");

    });

})(jQuery);