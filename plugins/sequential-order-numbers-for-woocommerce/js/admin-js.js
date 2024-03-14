(function ($){
    $(document).on('submit', '.BeRocket_Order_Numbers_submit_form', function() {
        var $form = $(this);
        setTimeout(function() {
            $form.find('.number_start_reset').prop('checked', false);
        }, 1000);
    });
    $(document).on('click', '.berocket_number_text_selector .br_type_selector_add', function(event) {
        event.preventDefault();
        var $parent = $(this).parents('.berocket_number_text_selector');
        var type = $parent.find('.br_type_selector_select').val();
        var html = $parent.find('.br_example .br_example_'+type).html();
        var name = $parent.data('name');
        var id = $parent.find('.br_added_types .br_type').length + 1;
        html = html.replace(/%name%/g, name+'['+id+']');
        $parent.find('.br_added_types').append($(html));
        br_generate_preview($parent);
    });
    $(document).on('click', '.berocket_number_text_selector .br_added_types .br_type .fa-times', function() {
        var $parent = $(this).parents('.berocket_number_text_selector');
        $(this).parents('.br_type').remove();
        br_generate_preview($parent);
    });
    $(document).on('change', '.berocket_number_text_selector .br_added_types input, .berocket_number_text_selector .br_added_types select, .berocket_number_text_selector .br_added_types textarea', function() {
        br_generate_preview($(this).parents('.berocket_number_text_selector'));
    });
    $(document).on('change', '.berocket_number_text_selector .br_type_selector_select', function() {
        $parent = $(this).parents('.berocket_number_text_selector').first();
        $parent.find('.br_fields_explanation .br_field_explanation').hide();
        $parent.find('.br_fields_explanation .br_field_'+$(this).val()).show();
    });
    $(document).ready( function(){
        $('.berocket_number_text_selector').each(function(i, o) {
            br_generate_preview($(o));
        });
        $('.br_added_types').sortable({
            handle:".fa-bars",
            placeholder: "berocket_sortable_space",
            start:function(event, ui) {
                $(ui.item).css('width', '');
                br_moved_values = [];
                $(ui.item).find('input').each(function(i, o) {
                    $(o).attr('value', $(o).val());
                });
                $(ui.item).css('width', $(ui.item).outerWidth());
            },
            stop: function(event, ui) {
                br_rename_fields($(this));
                br_generate_preview($(this).parents('.berocket_number_text_selector'));
            }
        });
    });
    function br_generate_preview($block) {
        var preview = $block.data('preview');
        if( preview ) {
            var preview_text = '';
            var $types = $block.find('.br_added_types .br_type');
            $types.each(function(i, o) {
                var type_slug = $(o).find('.br_item_type').val();
                if( typeof window['berocket_number_text_selector_'+type_slug] == 'function' ) {
                    preview_text = preview_text + window['berocket_number_text_selector_'+type_slug]($(o));
                } else {
                    preview_text = preview_text + type_slug;
                }
            });
            $(preview).text(preview_text);
        }
    }
    function br_rename_fields($block) {
        var name = $block.parents('.berocket_number_text_selector').first().data('name');
        var item_id = 1;
        $block.find('.br_type').each(function(i,o) {
            var br_moved_values = [];
            $(o).find('select, input').each(function(sel_i, sel) {
                br_moved_values[sel_i] = $(sel).val();
            });
            var cur_name = $(o).data('name');
            var new_name = name+'['+item_id+']';
            var html = $(o).html();
            cur_name = cur_name.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
            html = html.replace(new RegExp(cur_name, 'g'), new_name);
            $(o).html(html);
            $(o).data('name', new_name);
            $(o).find('select, input').each(function(sel_i, sel) {
                $(sel).val(br_moved_values[sel_i]);
            });
            item_id++;
        });
    }
    jQuery(document).ready(function() {
        jQuery( ".berocket_number_priority_list" ).sortable({axis:"y", handle:".fa-bars", placeholder: "berocket_sortable_space"});
    });
})(jQuery);
