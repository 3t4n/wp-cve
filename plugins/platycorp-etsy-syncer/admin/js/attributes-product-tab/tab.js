
(function( $ ) {
    $(document).ready(function() {
        var attributes = platySyncer['attributes'];
                attributes.forEach(attribute => {
                    var prop_id = attribute['property_id'];
                    var checkbox = get_attribute_checkbox(prop_id);

                    checkbox.on('change', function() {
                        if(this.checked) {
                            enable_attr(attribute);
                        }else{
                            disable_attr(attribute);
                        }
                    });

                    disable_attr(attribute);
                    init_attribute(attribute)
                    if(!attribute['enabled']) {
                        disable_attr(attribute);
                    }else {
                        enable_attr(attribute);
                    }
            })
        }
    )

    function init_attribute(attribute) {        
        var prop_id = attribute.prop_id;
        var scale_select = get_scales_select(prop_id);
        var value_ids_select = get_value_ids_select(prop_id)
        var values_input = get_values_input(prop_id);
        
        scale_select.val(attribute.scale_id);
        value_ids_select.val(attribute.value_ids);
        values_input.val(attribute.values);
    
    }

    function init_scale_select(attribute) {
        var prop_id = attribute.property_id;
        var scale_select = get_scales_select(prop_id);
        if(scale_select.length) {
            scale_select.on('change', function(value) {
                init_scale_options(attribute, value);
            })
        }
    }

    function init_scale_options(attribute, scale_id) {
        var prop_id = attribute.property_id;
        var value_ids_select = get_value_ids_select(prop_id);
        $(value_ids_select.id + " option").remove();
        var newOptions = attribute.possible_values.map(v => v.scale_id == scale_id);
        $.each(newOptions, function(key,value) {
            $el.append($("<option></option>")
               .attr("value", value).text(key));
          });

    }

    function disable_attr(attribute) {
        var prop_id = attribute.property_id;
        disable_and_hide(get_scales_select(prop_id))
        disable_and_hide(get_value_ids_select(prop_id))
        disable_and_hide(get_values_input(prop_id))
    }

    function enable_attr(attribute) {
        var prop_id = attribute.property_id;
        var scale_select = get_scales_select(prop_id);
        if(scale_select.length) {
            enable_and_show(scale_select);
            init_scale_select(attribute);
        }
        enable_value_input(attribute);
        init_attribute(attribute);
    }


    function enable_value_input(attribute) {
        var prop_id = attribute.property_id;

        var value_ids_select = get_value_ids_select(prop_id);
        if(value_ids_select.children('option').length > 0) {
            enable_and_show(value_ids_select);
            return;
        }

        enable_and_show(get_values_input(prop_id));
    }

    function disable_and_hide(input) {
        input.prop('disabled', true);
        var wrapper_id = input.attr('id') + "_field"
        $(`.${wrapper_id}`).hide()
    }

    function enable_and_show(input) {
        input.prop('disabled', false);
        var wrapper_id = input.attr('id') + "_field"
        $(`.${wrapper_id}`).show()
    }

    function get_attribute_div(prop_id) {
        return $(`#etsy-attr-${prop_id}`)
    }

    function get_attribute_checkbox(prop_id) {
        return $(`#etsy-attr-${prop_id}_enabled`)
    }

    function get_scales_select(prop_id) {
        return $(`#etsy-attr-${prop_id}_scale_id`)
    }

    function get_value_ids_select(prop_id) {
        return $(`#etsy-attr-${prop_id}_value_ids`)
    }

    function get_values_input(prop_id) {
        return $(`#etsy-attr-${prop_id}_values`)
    }"yes"




})( jQuery );



