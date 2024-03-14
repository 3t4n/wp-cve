jQuery(document).ready(function () {

     

     // select template code
    jQuery("#pbsm_popupdiv div.pbsm-template-thumbnail .pbsm-popum-select a").on('click', function (e) {
        e.preventDefault();
        jQuery('#pbsm_popupdiv div.pbsm-template-thumbnail').removeClass('pbsm_selected_template');
        jQuery(this).parents('div.pbsm-template-thumbnail').addClass('pbsm_selected_template');
    });

    jQuery(".pbsm_select_template").on('click', function (e) {
        e.preventDefault();
        
        /*var selctedFieldId = '#'+ jQuery(this).data('option');
        console.log( jQuery(selctedFieldId).val('option') );*/

        var template_name = jQuery('#template_name').val();

        jQuery("#pbsm_popupdiv").dialog({
            title: bdlite_js.choose_blog_template,
            dialogClass: 'pbsm_template_model',
            width: jQuery(window).width() - 100,
            height: jQuery(window).height() - 100,
            modal: true,
            draggable: false,
            resizable: false,
            create: function (e, ui) {
                var pane = jQuery(this).dialog("widget").find(".ui-dialog-buttonpane");
                /*jQuery("<div class='bp-div-default-style'><label><input id='bp-apply-default-style' class='bp-apply-default-style' type='checkbox'/>" + bdlite_js.default_style_template + "</label></div>").prependTo(pane);*/
            },
            buttons: [{
                    text: bdlite_js.set_blog_template,
                    id: "btnSetBlogTemplate",
                    click: function () {
                        var template_name = jQuery('#pbsm_popupdiv div.pbsm-template-thumbnail.pbsm_selected_template .pbsm-template-thumbnail-inner').children('img').attr('src');
                       
                        if (typeof template_name === 'undefined' || template_name === null) {
                            jQuery("#pbsm_popupdiv").dialog('close');
                            return;
                        }
                        var template_value = jQuery('#pbsm_popupdiv div.pbsm-template-thumbnail.pbsm_selected_template .pbsm-template-thumbnail-inner').children('img').attr('data-value');
/*==============================================================*/
//var dataContainer = jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(8)').attr("data-container");

                        
                        document.getElementById("pbsm-id").innerHTML = template_value + " Template";
                       

                        if ( 'Boxed_Slider' == template_value ) {

                            jQuery(".portfolio-wp-captions").show();
                            jQuery(".portfolio-wp-sliderControls").show();
                           
                            jQuery("#portfolio-wp-general .form-table-wrapper .form-table tr:nth-child(3)").show();//hide_navigation
                            jQuery("#portfolio-wp-general .form-table-wrapper .form-table tr:nth-child(6)").hide();//numOfImages
                            
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(3)').hide();//captionColor
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(4)').hide();//captionBgColor
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(6)').hide();//hide_description
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(8)').hide();//captionFontSize
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(9)').hide();//sliderColor
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(10)').hide();//sliderBgColor

                            //rows.filter( '[data-container="titleColor"], [data-container="titleBgColor"], [data-container="hide_title"], [data-container="titleFontSize"]' ).show();


                        } 
                        else if ( 'Caption_Slider' == template_value ){
                            jQuery(".portfolio-wp-captions").show();
                            jQuery(".portfolio-wp-sliderControls").hide();

                            jQuery("#portfolio-wp-general .form-table-wrapper .form-table tr:nth-child(3)").hide();//hide_navigation
                            jQuery("#portfolio-wp-general .form-table-wrapper .form-table tr:nth-child(6)").hide();//numOfImages
                            //rows.filter( '[data-container="hide_navigation"]' ).hide();

                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(3)').show();//captionColor
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(4)').show();//captionBgColor
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(6)').show();//hide_description
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(8)').show();//captionFontSize
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(9)').show();//sliderColor
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(10)').hide();//sliderBgColor
                        } 
                        else if ( 'Content_Slider' == template_value ){
                            jQuery(".portfolio-wp-captions").show();
                            jQuery(".portfolio-wp-sliderControls").show();
                            jQuery("#portfolio-wp-general .form-table-wrapper .form-table tr:nth-child(3)").show();//hide_navigation
                            jQuery("#portfolio-wp-general .form-table-wrapper .form-table tr:nth-child(6)").hide();//numOfImages

                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(3)').show();//captionColor
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(4)').show();//captionBgColor
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(6)').show();//hide_description
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(8)').show();//captionFontSize
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(9)').hide();//sliderColor
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(10)').show();//sliderBgColor
                        } 
                        else if ( 'Thumbnail_Slider' == template_value ){
                            jQuery(".portfolio-wp-captions").hide();
                            jQuery(".portfolio-wp-sliderControls").show();
                            
                            jQuery("#portfolio-wp-general .form-table-wrapper .form-table tr:nth-child(3)").show();//hide_navigation
                            jQuery("#portfolio-wp-general .form-table-wrapper .form-table tr:nth-child(6)").hide();//numOfImages
                        } 
                        else if ( 'Effect_Coverflow_Slider' == template_value || 'Owl_Slider' == template_value ) {

                            jQuery(".portfolio-wp-captions").hide();
                            jQuery(".portfolio-wp-sliderControls").hide();
                           
                            jQuery("#portfolio-wp-general .form-table-wrapper .form-table tr:nth-child(3)").show();//hide_navigation
                            jQuery("#portfolio-wp-general .form-table-wrapper .form-table tr:nth-child(6)").show();//numOfImages

                        }else {
                            jQuery(".portfolio-wp-captions").show();
                            jQuery(".portfolio-wp-sliderControls").show();
                            jQuery("#portfolio-wp-general .form-table-wrapper .form-table tr:nth-child(3)").show();//hide_navigation
                            jQuery("#portfolio-wp-general .form-table-wrapper .form-table tr:nth-child(6)").hide();//numOfImages

                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(3)').show();//captionColor
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(4)').show();//captionBgColor
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(6)').show();//hide_description
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(8)').show();//captionFontSize
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(9)').hide();//sliderColor
                            jQuery('#portfolio-wp-captions .form-table-wrapper .form-table tr:nth-child(10)').hide();//sliderBgColor
                        }



/*========================================================================================*/
                        jQuery('.pbsm_select_template_value').val(template_value);
                
                
                        jQuery(".pbsm_selected_template_image > div").empty();
                        jQuery('#template_name').val(template_value);
                        jQuery(".pbsm_selected_template_image > div").append('<img src="' + template_name + '" alt="' + template_value.replace('_', '-') + ' Template" /><label id="pbsm_template_select_name">' + template_value.replace('_', '-') + ' Template</label>');

                            
                        bdAltBackground();
                        jQuery("#pbsm_popupdiv").dialog('close');

                    }
                },
                {
                    text: bdlite_js.close,
                    class: 'pbsm_template_close',
                    click: function () {
                        jQuery(this).dialog("close");
                    },
                }
            ],
            open: function (event, ui) {
                var template_name = jQuery('#designName').val();
                //console.log(template_name);
                jQuery('#pbsm_popupdiv .pbsm-template-thumbnail').removeClass('pbsm_selected_template');
                jQuery('#pbsm_popupdiv .pbsm-template-thumbnail').each(function () {
                    if (jQuery(this).children('.pbsm-template-thumbnail-inner').children('img').attr('data-value') == template_name) {
                        jQuery(this).addClass('pbsm_selected_template');
                    }
                });
            }
        });
        return false;
    });
});

function bdAltBackground() {
    jQuery('.postbox').each(function () {
        jQuery(this).find('ul.pbsm-settings > li').removeClass('pbsm-gray');
        jQuery(this).find('ul.pbsm-settings > li:not(.pbsm-hidden):odd').addClass('pbsm-gray');
    });
}