jQuery(document).ready(function () {

     // select template code
    jQuery("#ras_design_popup div.ras-template-thumbnail .ras-popum-select a").on('click', function (e) {
        e.preventDefault();
        jQuery('#ras_design_popup div.ras-template-thumbnail').removeClass('ras_selected_template');
        jQuery(this).parents('div.ras-template-thumbnail').addClass('ras_selected_template');
    });

    jQuery(".ras_select_template").on('click', function (e) {
        e.preventDefault();
        
        /*var selctedFieldId = '#'+ jQuery(this).data('option');
        console.log( jQuery(selctedFieldId).val('option') );*/

        var template_name = jQuery('#template_name').val();

        jQuery("#ras_design_popup").dialog({
            title: accordion_js.choose_blog_template,
            dialogClass: 'ras_template_model',
            width: jQuery(window).width() - 100,
            height: jQuery(window).height() - 100,
            modal: true,
            draggable: false,
            resizable: false,
            create: function (e, ui) {
                var pane = jQuery(this).dialog("widget").find(".ui-dialog-buttonpane");
            },
            buttons: [{
                    text: accordion_js.set_blog_template,
                    id: "btnSetBlogTemplate",
                    click: function () {
                        var template_name = jQuery('#ras_design_popup div.ras-template-thumbnail.ras_selected_template .ras-template-thumbnail-inner').children('img').attr('src');
                       
                        if (typeof template_name === 'undefined' || template_name === null) {
                            jQuery("#ras_design_popup").dialog('close');
                            return;
                        }
                        var template_value = jQuery('#ras_design_popup div.ras-template-thumbnail.ras_selected_template .ras-template-thumbnail-inner').children('img').attr('data-value');
                        
                        document.getElementById("ras-id").innerHTML = template_value + " Template";
                        jQuery('.ras_select_template_value').val(template_value);
                        jQuery(".ras_selected_template_image > div").empty();
                        jQuery('#template_name').val(template_value);
                        jQuery(".ras_selected_template_image > div").append('<img src="' + template_name + '" alt="' + template_value.replace('_', '-') + ' Template" /><label id="ras_template_select_name">' + template_value.replace('_', '-') + ' Template</label>');

                            
                        bdAltBackground();
                        jQuery("#ras_design_popup").dialog('close');

                    }
                },
                {
                    text: accordion_js.close,
                    class: 'ras_template_close',
                    click: function () {
                        jQuery(this).dialog("close");
                    },
                }
            ],
            open: function (event, ui) {
                var template_name = jQuery('#designName').val();
                //console.log(template_name);
                jQuery('#ras_design_popup .ras-template-thumbnail').removeClass('ras_selected_template');
                jQuery('#ras_design_popup .ras-template-thumbnail').each(function () {
                    if (jQuery(this).children('.ras-template-thumbnail-inner').children('img').attr('data-value') == template_name) {
                        jQuery(this).addClass('ras_selected_template');
                    }
                });
            }
        });
        return false;
    });
});

function bdAltBackground() {
    jQuery('.postbox').each(function () {
        jQuery(this).find('ul.ras-layout-settings > li').removeClass('ras-gray');
        jQuery(this).find('ul.ras-layout-settings > li:not(.ras-hidden):odd').addClass('ras-gray');
    });
}