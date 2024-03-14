var dlsmb = {
    //admin_url : '',
    post_id : 0,
    //validation : false,
    text : {
        'gallery_tb_title_add' : "Add Image to Gallery"
    },
    conditional_logic : {},
    sortable_helper : null,
    tinyMCE_settings : null
};
var dlsmbDefaultDate = '';

jQuery(document).on('keydown', '.dlsmb-field-type-map #searchInput', function( e ){
    // prevent form from submitting
    if( e.which == 13 ) {
        return false;
    }
});

(function($){

    $(document).ready(function() {

        function dlsmb_get_last_css_id(repeater) {
            var allIDs = [];
            repeater.find('tbody tr').each(function () {
                var itemID = $(this).attr('id').split('-');
                allIDs.push(itemID[itemID.length - 1]);
            });
            var IDs = allIDs.filter(function (el) {
                return el.length && el == +el;
            });
            last_id = Math.max.apply(Math, IDs);
            return last_id;
        }

        function dlsmb_update_image_field(wrap) {
            var img = wrap.children('.dlsmb-preview-image').children('img');
            if (img.length) {
                wrap.children('.dlsmb-add-image').hide();
            } else {
                wrap.children('.dlsmb-input-image').val('');
                wrap.children('.dlsmb-add-image').show();
            }
        }

        function dlsmb_add_image(inputField) {
            inputField.parent().children('.dlsmb-ajax-loading').show();

            file_frame = wp.media.frames.file_frame = wp.media({
                frame:    'post',
                state:    'insert',
                multiple: false
            });

            file_frame.on( 'insert', function() {
                var attachment = file_frame.state().get( 'selection' ).first().toJSON();
                $.ajax({
                    url: ajaxurl,
                    data : {
                        action: 'dlsmb_image_data',
                        attachment_id: attachment.id
                    },
                    cache: false,
                    dataType: "json",
                    success: function(json) {
                        if (!json) return;
                        var item = json;
                        inputField.val(item.id);
                        inputField.parent().children('.dlsmb-ajax-loading').hide();

                        inputField.parent().children('.dlsmb-preview-image').html('<div class="dlsmb-image-hover"><ul><li class="dlsmb-image-hover-icon dlsmb-image-hover-edit"><a href="#" title="Edit"></a></li><li class="dlsmb-image-hover-icon dlsmb-image-hover-remove"><a href="#" title="Remove"></a></li></ul></div><img src="' + item.preview + '" height="' + item.preview_height + '" width="' + item.preview_width + '" alt="" />');
                        dlsmb_update_image_field(inputField.parent());
                    }
                });
            });
            file_frame.open();
            return false;
        }

        $('body').on('click', '.dlsmb-add-image', function() {
            dlsmb_add_image($(this).prev('.dlsmb-input-image'));
        });

        var dlsmb_image_wrap = $('.dlsmb-field-type-image .dlsmb-image-wrap');

        dlsmb_image_wrap.on('click', '.dlsmb-image-hover-remove', function () {
            inputField = $(this).parent().parent().parent().next('.dlsmb-input-image');
            inputField.val('');
            inputField.parent().children('.dlsmb-preview-image').html('');
            dlsmb_update_image_field(inputField.parent());
            return false;
        });

        dlsmb_image_wrap.on('click', '.dlsmb-image-hover-edit', function () {
            dlsmb_add_image($(this).parent().parent().parent().next('.dlsmb-input-image'));
            return false;
        });

        dlsmb_image_wrap.each(function(index) {
            dlsmb_update_image_field($(this));
        });

        function dlsmb_update_element_id_and_label(element) {
            var new_element_name = element.attr('name');
            var new_element_name_prefix = new_element_name.substr(0, new_element_name.indexOf('[') + 1);
            var new_element_name_suffix = new_element_name.substr(new_element_name.indexOf(']'));
            element.attr('name', new_element_name_prefix + row_key + new_element_name_suffix);
            // Element ID & label
            var new_element_id = element.attr('id');
            var new_element_id_split = new_element_id.split('-');
            var new_element_id_hyphenated_key = '-' + new_element_id_split[new_element_id_split.length - 2] + '-';
            var new_element_id_prefix = new_element_id.substr(0, new_element_id.indexOf(new_element_id_hyphenated_key));
            var new_element_id_suffix = new_element_id.substr(new_element_id.indexOf(new_element_id_hyphenated_key) + new_element_id_hyphenated_key.length);
            element.attr('id', new_element_id_prefix + '-' + row_key + '-' + new_element_id_suffix);
            element.closest('.dlsmb-field').children('.dlsmb-main-label').attr('for', new_element_id_prefix + '-' + row_key + '-' + new_element_id_suffix);
        }

        // Add/Remove Repeaters
        if ($('.dlsmb-field-type-repeater TBODY TR').is('*')) {
            var dlspm_repeater = $('.dlsmb-field-type-repeater');
            var row_key = dlsmb_get_last_css_id(dlspm_repeater);
            dlspm_repeater.on('click', '.dlsmb-js-add', function (e) {
                row_key = dlsmb_get_last_css_id(dlspm_repeater);
                e.preventDefault();
                var new_row_template = $(this).parent('TD').parent('TR').parent('TBODY').children('.dlsmb-blank-repeater')[0].outerHTML;
                row_key++; // Update row_keys
                var new_element = $(this).closest('TR').after(new_row_template).next('TR');
                new_element.removeClass('dlsmb-blank-repeater');
                new_element.attr('id', new_element.attr('class').split(' ')[0] + '-' + row_key); // Row ID

                new_element.find('.dlsmb-field .dlsmb-field-element').each(function () {
                    dlsmb_update_element_id_and_label($(this));
                });

                new_element.find('.dlsmb-field .dlsmb-input-image').each(function () {
                    dlsmb_update_element_id_and_label($(this));
                });

                $(this).closest('TR').find('.dlsmb-js-add').trigger('dlsmb:add-after');

                return false;
            });
            dlspm_repeater.on('click', '.dlsmb-js-remove', function () {
                $(this).closest("TR").remove();
                return false;
            });
        }

        // Sort Repeaters
        $('.dlsmb-field-type-repeater TABLE').find('TR').each(function(){
            $(this).children('TH').eq(0).before('<th class="dlsmb-sort"></th>');
            $(this).children('TD').eq(0).before('<td class="dlsmb-sort"></td>');
        });

        $('.dlsmb-field-type-repeater TABLE').sortable({
            distance: 5,
            opacity: 0.6,
            cursor: 'move',
            toleranceElement: '.dlsmb-sort',
            items: 'tbody > tr'
        });

        // Datepicker
        $("body").on('focus', '.dlsmb-datepicker', function () {
            $(this).datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                selectOtherMonths: true,
                showOtherMonths: true,
                onSelect: function (dateText, inst) {
                    if (dateText) {
                        dlsmbDefaultDate = dateText;
                        $('.dlsmb-datepicker').datepicker("option", "defaultDate", dateText);
                    }
                }
            });
        });
        // Determine default date
        $.each($('.dlsmb-datepicker'), function () {
            if ($(this).val()) {
                dlsmbDefaultDate = $(this).val();
            }
        });

        // Chosen
        $("#poststuff .chosen-select").chosen({width: "100%"});

    });

})(jQuery);