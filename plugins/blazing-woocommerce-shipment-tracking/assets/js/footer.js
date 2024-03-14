(function (window, $) {
    var fields_id = {
        tracking_ship_date: "bst_tracking_shipdate",
    };

    var providers;

    $(document).ready(function () {
        $("#bst_tracking_provider").change(function () {
            $(".button.tracking_email").prop("disabled", true);
        });
        $("#bst_tracking_number").change(function () {
            $(".button.tracking_email").prop("disabled", true);
        });
    });

    $(".button.tracking_email").on("click", function () {
        var data = {
            action: "bs_ship_track_email",
            id: $("#bst_order_id").val(),
            //date_shipped:       $( 'input#date_shipped' ).val(),
            security: $("#blazing_tracking_email_nonce").val(),
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function (response) {
            alert("Got this from the server: " + response);
        });
    });

    function hide_input_and_label(id) {
        $("#" + id).hide();
        $("label[for=" + id + "]").hide();
    }

    function show_input_and_label(id) {
        $("#" + id).show();
        $("label[for=" + id + "]").show();
    }

    function set_bst_tracking_provider() {
        $("#bst_tracking_provider").change(function () {
            $.each(fields_id, function (index, item) {
                hide_input_and_label(item);
            });

            var slug = $(this).val();
            if (slug) {
                var provider = providers[slug];
                var fields = [];
                if ($.isArray(provider.required_fields)) {
                    fields = provider.required_fields;
                } else {
                    fields.push(provider.required_fields);
                }
                $.each(fields, function (index, item) {
                    if (fields_id[item]) {
                        show_input_and_label(fields_id[item]);
                    }
                });
                $("#bst_tracking_provider_name").val(provider.name);
            }
        });
    }

    function fill_meta_box(couriers_selected) {
        var selected_provider = $("#bst_tracking_provider_hidden").val();
        var couriers = [];
        var find_selected_provider = false;
        $.each(couriers_selected, function (index, courier) {
            var arr = courier.split("|");
            if (arr.length == 2) {
                couriers.push({
                    slug: arr[1],
                    name: arr[0],
                });
                if (selected_provider == arr[1]) {
                    find_selected_provider = true;
                }
            }
        });

        couriers_selected.indexOf(selected_provider) != -1;
        if (!find_selected_provider && selected_provider) {
            couriers.push({
                slug: selected_provider,
                name: selected_provider,
            });
        }

        $.each(couriers, function (key, courier) {
            var str = "<option ";
            if (
                !find_selected_provider &&
                courier["slug"] == selected_provider
            ) {
                str += 'style="display:none;" ';
            }
            str += 'value="' + courier["slug"] + '" ';
            if (courier["slug"] == selected_provider) {
                str += 'selected="selected"';
            }
            str += ">" + courier["name"] + "</option>";
            $("#bst_tracking_provider").append(str);
        });
        //		$('#bst_tracking_provider').val(selected_provider);
        // $('#bst_tracking_provider').trigger("chosen:updated");
        // $('#bst_tracking_provider_chosen').css({width: '100%'});

        providers = {};
        $.each(couriers, function (index, courier) {
            providers[courier.slug] = courier;
        });
        set_bst_tracking_provider();
        $("#bst_tracking_provider").trigger("change");
    }

    if ($("#bst_tracking_provider").length > 0) {
        $.each(fields_id, function (index, item) {
            hide_input_and_label(item);
        });

        var couriers_selected = $("#bst_couriers_selected").val();
        var couriers_selected_arr = couriers_selected
            ? couriers_selected.split(",")
            : [];
        fill_meta_box(couriers_selected_arr);
    }

    if ($("#bst_tracking_provider_name").length > 0) {
        $("#bst_tracking_provider_name").parent().hide();
    }
})(window, jQuery);
