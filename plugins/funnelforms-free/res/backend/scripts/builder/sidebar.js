jQuery( document ).ready(function() {

    jQuery(document).on('click', '.unsummonEditSidebar', _ => unsummonEditSidebar() );
    jQuery(document).on('click', '.af2_builder_workspace', _ => unsummonEditSidebar() );

    jQuery(document).on('click', '.af2_builder_editable_object', function(ev) {
        if(jQuery(this).hasClass('af2_dragging')) return;
        ev.stopPropagation();
        summonEditSidebar(jQuery(this));
    });
    jQuery(document).on('click', '.af2_builder_editable_object .af2_savediv', function(ev) {
        const el = jQuery(this).parents('.af2_builder_editable_object');
        if(jQuery(el).hasClass('af2_dragging')) return;
        ev.stopPropagation();
        summonEditSidebar(jQuery(el));
    });


    const summonEditSidebar = (handler) => {
        jQuery('.editSidebar .af2_builder_sidebar_content_wrapper').html('');
        jQuery('.af2_builder_editable_object.selected').removeClass('selected');
        handler.addClass('selected');

        af2FillSidebar(handler);

        jQuery('.af2_builder_sidebar.editSidebar').removeClass('hide');
        jQuery('.af2_builder_content').addClass('no_margin');
    }

    const unsummonEditSidebar = () => {
        jQuery('.af2_builder_editable_object.selected').removeClass('selected');

        const handler = jQuery('.af2_builder_sidebar.editSidebar');

        handler.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', _ => {
            jQuery('.editSidebar .af2_builder_sidebar_content_wrapper').html();
        });
        handler.addClass('hide');
        jQuery('.af2_builder_content').removeClass('no_margin');
    }

    const af2FillSidebar = (handler) => {
        const editContentId = handler.data('editcontentid');

        const dataHandler = af2_builder_object.sidebar_elements.find(element => element.editContentId == editContentId);

        const editContentArrayId = dataHandler.editContentArray ? handler.data('editcontentarrayid') : null;

        af2CreateSidebarObjects(dataHandler, editContentArrayId);
    }

    const af2CreateSidebarObjects = (dataHandler, editContentArrayId) => {
        dataHandler.fields.forEach((element, i) => {

            if(element.conditioned) {
                if((element.depending_field == null) && element.enabled == false) return '';
                if(element.depending_field != null) {
                    let searchedField = element.depending_field;
                    let searchedValues = element.depending_values;

                    let selector = af2_builder_object.af2_save_object[element.details.saveObjectId];
                    if(dataHandler.editContentArray && editContentArrayId != null) selector = selector[editContentArrayId];
                    if(!searchedValues.includes(selector[searchedField])) return '';
                }
            } 

            const operator = af2CreateSidebarOperator(dataHandler, i, editContentArrayId);
            const sidebarObject = af2CreateSidebarObject(element, operator);

            jQuery('.editSidebar .af2_builder_sidebar_content_wrapper').append(sidebarObject);
        });
    }

    const af2CreateSidebarObject = (element, operator) => {
        let sidebarObject = '';

        let disabled = element.disabled != null ? element.disabled : false;
        
        let disabledClass = disabled ? 'af2_disabled_sidebar_element' : '';

        sidebarObject += '<div class="af2_builder_sidebar_content af2_builder_edit_sidebar_content af2_builder_sidebar_element '+disabledClass+'">';
        if(disabled) sidebarObject += '<div class="af2_pro_sign"><i class="fas fa-star"></i>'+af2_builder_object.strings.pro+'</div>';

        let requiredText = element.required ? ': *' : ':';
        

        switch(element.type) {
            case 'label': {
                sidebarObject += '<div class="af2_builder_sidebar_content_heading_wrapper" style="margin-bottom: 0;">';
                sidebarObject += '<i class="'+element.icon+'"></i>';
                sidebarObject += '<h5 class="af2_builder_sidebar_content_heading">'+element.label+'</h5>';
                sidebarObject += '</div>';
                break;
            }
            case 'text': {
                //Fallthrough
            }
            case 'textarea_': {
                //Fallthrough
            }
            case 'textarea': {
                sidebarObject += '<div class="af2_builder_sidebar_content_heading_wrapper">';
                sidebarObject += '<i class="'+element.icon+'"></i>';
                sidebarObject += '<h5 class="af2_builder_sidebar_content_heading">'+element.label+requiredText+'</h5>';
                sidebarObject += '</div>';
                sidebarObject += '<div class="af2_builder_sidebar_content_operator">';
                sidebarObject += '<div class="custom_builder_content_card_box_content">';
                sidebarObject += operator;
                sidebarObject += '</div>';
                sidebarObject += '</div>';
                break;
            }
            case 'checkbox': {
                sidebarObject += '<div class="af2_builder_sidebar_content_operator">';
                sidebarObject += operator;
                sidebarObject += '</div>';
                break;
            }
            case 'radio': {
                sidebarObject += '<div class="af2_builder_sidebar_content_heading_wrapper">';
                sidebarObject += '<i class="'+element.icon+'"></i>';
                sidebarObject += '<h5 class="af2_builder_sidebar_content_heading">'+element.label+'</h5>';
                sidebarObject += '</div>';
                sidebarObject += '<div class="af2_builder_sidebar_content_operator af2_builder_sidebar_content_operator_radio_type">';
                sidebarObject += operator;
                sidebarObject += '</div>';
                break;
            }
            case 'icon_image': {
                let requiredText = element.required ? ' *:' : ':';
                sidebarObject += '<div class="af2_builder_sidebar_content_heading_wrapper">';
                sidebarObject += '<i class="'+element.icon+'"></i>';
                sidebarObject += '<h5 class="af2_builder_sidebar_content_heading">'+element.label+requiredText+'</h5>';
                sidebarObject += '</div>';
                sidebarObject += '<div class="af2_builder_sidebar_content_operator">';
                sidebarObject += '<div class="custom_builder_content_card_box_content icon_image">';
                sidebarObject += operator;
                sidebarObject += '</div>';
                sidebarObject += '</div>';
                break;
            }
            case 'color_picker': {
                let requiredText = element.required ? ' *:' : ':';
                sidebarObject += '<div class="af2_builder_sidebar_content_heading_wrapper">';
                sidebarObject += '<i class="'+element.icon+'"></i>';
                sidebarObject += '<h5 class="af2_builder_sidebar_content_heading">'+element.label+requiredText+'</h5>';
                sidebarObject += '</div>';
                sidebarObject += '<div class="af2_builder_sidebar_content_operator">';
                sidebarObject += '<div class="custom_builder_content_card_box_content">';
                sidebarObject += operator;
                sidebarObject += '</div>';
                sidebarObject += '</div>';
                break;
            }
            case 'select': {
                sidebarObject += '<div class="af2_builder_sidebar_content_heading_wrapper">';
                sidebarObject += '<i class="'+element.icon+'"></i>';
                sidebarObject += '<h5 class="af2_builder_sidebar_content_heading">'+element.label+requiredText+'</h5>';
                sidebarObject += '</div>';
                sidebarObject += '<div class="af2_builder_sidebar_content_operator">';
                sidebarObject += '<div class="custom_builder_content_card_box_content">';
                sidebarObject += operator;
                sidebarObject += '</div>';
                sidebarObject += '</div>';
                break;
            }
            case 'restriction': {
                sidebarObject += '<div class="af2_builder_sidebar_content_heading_wrapper">';
                sidebarObject += '<i class="'+element.icon+'"></i>';
                sidebarObject += '<h5 class="af2_builder_sidebar_content_heading">'+element.label+'</h5>';
                sidebarObject += '</div>';
                sidebarObject += '<div class="af2_builder_sidebar_content_operator">';
                sidebarObject += operator;
                sidebarObject += '</div>';
                break;
            }
            default: {
                break;
            }
        }

        sidebarObject += '</div>';

        return sidebarObject;
    }

    const af2CreateSidebarOperator = (dataHandler, fieldnum, editContentArrayId) => {
        const editContentId = 'data-editcontentid="'+dataHandler.editContentId+'"';
        const data_editContentArrayId = editContentArrayId != null ? 'data-editcontentarrayid="'+editContentArrayId+'"' : '';
        const fieldNumber = 'data-fieldnumber="'+fieldnum+'"';

        const element = dataHandler.fields[fieldnum];
        const type = element.type;
        const editDetails = element.details;

        const disabled = element.disabled != null ? element.disabled : false;

        let value = null;
        let valuableOperator = null;
        if(editContentArrayId == null) valuableOperator = af2_builder_object.af2_save_object[editDetails.saveObjectId];
        else valuableOperator = af2_builder_object.af2_save_object[editDetails.saveObjectId][editContentArrayId];

        if(editDetails.saveObjectIdField != null) valuableOperator = valuableOperator[editDetails.saveObjectIdField]

        value = valuableOperator != null ? valuableOperator : '';

        let operator = '';

        switch(type) {
            case 'text': {
                value = value.toString().replaceAll('"', '&quot;');
                operator += '<input type="text" class="af2_sidebar_builder_content_text_edit" placeholder="'+element.placeholder+'"'+editContentId+' '+data_editContentArrayId+' '+fieldNumber+' value="'+value+'">';
                break;
            }
            case 'textarea_': {
                const readonly = disabled ? 'readonly' : '';
                value = value.toString().replaceAll('"', '&quot;');
                operator += '<textarea '+readonly+' class="af2_sidebar_builder_content_textarea_edit_" placeholder="'+element.placeholder+'" '+editContentId+' '+data_editContentArrayId+' '+fieldNumber+'>'+value+'</textarea>';
                break;
            }
            case 'textarea': {
                const readonly = disabled ? 'readonly' : '';
                value = value.toString().replaceAll('"', '&quot;');
                operator += '<textarea '+readonly+' class="af2_sidebar_builder_content_textarea_edit" placeholder="'+element.placeholder+'" '+editContentId+' '+data_editContentArrayId+' '+fieldNumber+'>'+value+'</textarea>';
                break;
            }
            case 'checkbox': {
                const checked = value == true || value == 'true' ? 'checked' : '';

                operator += '<div class="af2_sidebar_checkbox_operator">';

                let radio_group = element.radio_group != null ? 'data-radiogroup="'+element.radio_group+'"' : '';

                const own_id = element.details.saveObjectId+'_'+fieldnum;

                operator += '<input id="checkbox_'+own_id+'" class="af2_sidebar_builder_content_checkbox_edit" type="checkbox" '+radio_group+' '+editContentId+' '+data_editContentArrayId+' '+fieldNumber+' '+checked+'>';
                operator += '<label for="checkbox_'+own_id+'">'+element.label+'</label>';

                operator += '</div>';
                break;
            }
            case 'radio': {
                element.options.forEach(option => {
                    operator += '<div class="af2_sidebar_radio_operator">';
                    const option_label = option.label;
                    const option_value = option.value;
                    const checked = value == option_value ? 'checked' : '';

                    const ownId = element.details.saveObjectId+'_'+option_value;

                    operator += '<input id="'+ownId+'" type="radio" name="'+element.details.saveObjectId+'" class="af2_sidebar_builder_content_radio_edit" value="'+option_value+'" '+editContentId+' '+data_editContentArrayId+' '+fieldNumber+' '+checked+' >';
                    operator += '<label for="'+ownId+'">'+option_label+'</label>';
                    operator += '</div>';
                });
                break;
            }
            case 'icon_image': {
                if(element.show_preview) {
                    operator += '<div id="af2_choose_media_show_'+element.details.saveObjectId+'" class="af2_show_media_preview">';
                    if(value == null || value.trim() == '') {}
                    else {
                        if(value.substr(0, 4) == 'http') {
                            if(element.details.icon_url == true) {
                                operator += value;
                            }
                            else  {
                                operator += '<img class="af2_show_media_preview_img" src="'+value+'">';
                            }
                        } else {
                            operator += '<i class="'+value+' af2_show_media_preview_icon"></i>';
                        }
                    }
                    operator += '</div>';
                }
                if(element.enable_media) operator += '<div id="af2_choose_media_'+element.details.saveObjectId+'" class="af2_btn af2_btn_primary af2_sidebar_builder_content_choose_media" data-mediashowid="af2_choose_media_show_'+element.details.saveObjectId+'" '+editContentId+' '+data_editContentArrayId+' '+fieldNumber+'>'+element.label_buttons.image+'</div>';
                if(element.enable_icon) operator += '<div id="af2_choose_icon_'+element.details.saveObjectId+'" class="af2_btn af2_btn_primary af2_sidebar_builder_content_choose_icon" data-mediashowid="af2_choose_media_show_'+element.details.saveObjectId+'" '+editContentId+' '+data_editContentArrayId+' '+fieldNumber+'>'+element.label_buttons.icon+'</div>';
                if(element.enable_remove) operator += '<div id="af2_delete_icon_'+element.details.saveObjectId+'" class="af2_btn af2_btn_primary af2_sidebar_builder_content_delete_icon" data-mediashowid="af2_choose_media_show_'+element.details.saveObjectId+'" '+editContentId+' '+data_editContentArrayId+' '+fieldNumber+'>'+element.label_buttons.remove+'</div>';
                if(element.enable_reset) operator += '<div id="af2_reset_icon_'+element.details.saveObjectId+'" data-resetvalue="'+element.reset_value+'" class="af2_btn af2_btn_primary af2_sidebar_builder_content_reset_icon" data-mediashowid="af2_choose_media_show_'+element.details.saveObjectId+'" '+editContentId+' '+data_editContentArrayId+' '+fieldNumber+'>'+element.label_buttons.reset+'</div>';
                break;
            }
            case 'color_picker': {
                const own_id_addition = '_'+editDetails.saveObjectId + '_' + editDetails.saveObjectIdField;
                const colorizer_id = 'colorizer' + own_id_addition;
                const color_input_id = 'color_input' + own_id_addition;
                const color_preview_id = 'color_preview' + own_id_addition;
                if(value == null || value == '') value = 'rgba(0,0,0,1)';
                operator += '<div id="'+colorizer_id+'" class="af2_sidebar_builder_content_colorizer">';
                    operator += '<input id="'+color_input_id+'" data-colorizerid="'+colorizer_id+'" data-colorpreviewid="'+color_preview_id+'" class="af2_sidebar_builder_content_color_edit" type="text" value="'+value+'" '+editContentId+' '+fieldNumber+'>';
                    operator += '<div id="'+color_preview_id+'" class="af2_sidebar_builder_content_color_preview" style="background-color: '+value+';">';
                operator += '</div>';
                break;
            }
            case 'select': {
                const select_value = value != '' ? value : element.default_value;

                operator += '<select id="select_"'+element.details.saveObjectId+'" class="af2_sidebar_builder_content_select_edit" value="'+select_value+'" '+editContentId+' '+data_editContentArrayId+' '+fieldNumber+'>';
                
                element.select_values.forEach(el => {
                    const option_selected = select_value == el['value'] ? 'selected' : '';
                    operator += '<option value="'+el['value']+'" '+option_selected+'>';
                    operator += el['label'];
                    operator += '</option>';
                });
                
                operator += '</select>';
                break;
            }
            case 'restriction': {
                const options = element.options;

                const id_add = element.details.saveObjectId;

                operator += '<div id="af2_restriction_buttons_'+id_add+'" class="af2_restriction_buttons">';
                    operator += '<div id="af2_restriction_all_activate_'+id_add+'" data-saveobjectid="'+element.details.saveObjectId+'" class="af2_btn af2_btn_primary af2_sidebar_builder_content_restriction_option_activate" '+editContentId+' '+data_editContentArrayId+' '+fieldNumber+'>'+element.label_all_activate+'</div>';
                    operator += '<div id="af2_restriction_all_deactivate_'+id_add+'" data-saveobjectid="'+element.details.saveObjectId+'" class="af2_btn af2_btn_primary af2_sidebar_builder_content_restriction_option_deactivate" '+editContentId+' '+data_editContentArrayId+' '+fieldNumber+'>'+element.label_all_deactivate+'</div>';
                operator += '</div>';

                operator += '<div class="af2_restriction_options">';

                options.forEach(option => {

                    const restrictions = af2_builder_object.af2_save_object[element.details.saveObjectId];

                    let checked = 'checked';
                    if(restrictions.includes(option)) checked = '';

                    operator += '<div class="af2_restriction_option">';
                    operator += '<input type="checkbox" id="af2_restriction_option_'+id_add+'_'+option+'" data-saveobjectid="'+element.details.saveObjectId+'" class="af2_sidebar_builder_content_restriction_option" data-optionvalue="'+option+'" '+editContentId+' '+data_editContentArrayId+' '+fieldNumber+' '+checked+'>';
                    operator += '<label for="af2_restriction_option_'+id_add+'_'+option+'"">'+option+'</label>'
                    operator += '</div>'; 
                });
                
                operator += '</div>'; 
            }
            default: {
                break;
            }
        }

        return operator;
    }

    jQuery(document).on('input', '.af2_sidebar_builder_content_text_edit', function() { af2_make_sidebar_input_content_change(this) });
    jQuery(document).on('input', '.af2_sidebar_builder_content_textarea_edit_', function() { af2_make_sidebar_input_content_change(this) });
    jQuery(document).on('input', '.af2_sidebar_builder_content_textarea_edit', function() { af2_make_sidebar_input_content_change(this) });
    jQuery(document).on('click', '.af2_sidebar_builder_content_checkbox_edit', function() { af2_make_sidebar_checkbox_content_change(this) });
    jQuery(document).on('click', '.af2_sidebar_builder_content_choose_media', function() { mediaUploaderStart(jQuery(this).attr('id')) });
    jQuery(document).on('click', '.af2_sidebar_builder_content_choose_icon', function() { af2_open_modal('#af2_fontawesome_iconpicker', {'id': 'af2_iconpicker_save', 'data_attribute': 'objectid', 'data_value': jQuery(this).attr('id')}) });
    jQuery(document).on('image_picked', '.af2_sidebar_builder_content_choose_media', ev => af2_make_sidebar_image_icon_content_change(ev) );
    jQuery(document).on('icon_picked', '.af2_sidebar_builder_content_choose_icon', ev => af2_make_sidebar_image_icon_content_change(ev) );
    jQuery(document).on('click', '.af2_sidebar_builder_content_delete_icon',  function() { af2_make_sidebar_image_icon_content_change({ object_id: jQuery(this).attr('id'), value: null }); });
    jQuery(document).on('click', '.af2_sidebar_builder_content_reset_icon',  function() { af2_make_sidebar_image_icon_content_change({ object_id: jQuery(this).attr('id'), value: jQuery(this).data('resetvalue') }); });
    jQuery(document).on('click', '.af2_sidebar_builder_content_radio_edit', function() { af2_make_sidebar_radio_content_change(this) });
    jQuery(document).on('change', '.af2_sidebar_builder_content_select_edit', function() { af2_make_sidebar_select_content_change(this) });

    jQuery(document).on('click', '.af2_sidebar_builder_content_restriction_option_activate', function() { af2_make_sidebar_restriction_content_change(this) } );
    jQuery(document).on('click', '.af2_sidebar_builder_content_restriction_option_deactivate', function() { af2_make_sidebar_restriction_content_change(this) } );
    jQuery(document).on('click', '.af2_sidebar_builder_content_restriction_option', function() { af2_make_sidebar_restriction_content_change(this) } );


    jQuery(document).on('focus', '.af2_sidebar_builder_content_color_edit', function(e) {
        jQuery('.gn8-colorize-toolbox').remove();
        const target = jQuery(e.currentTarget);
        let data = {
            "id": null,
            "container": jQuery('#'+target.data('colorizerid'))[0],
            "value": target.attr('value')
        }
        let colorizer = new Gn8Colorize(data);
        colorizer.init().then(
            success => {
                af2_make_sidebar_color_content_change(target, success.rgb);
            }, error => {
                // console.log(error);
            }
        )
    });
    
    /*jQuery(document).on('focusout', '.af2_sidebar_builder_content_color_edit', function(e) {
        if( jQuery(e.target).parent().find('.gn8-colorize-toolbox').length ){
            jQuery(e.target).parent().find('.gn8-colorize-toolbox').remove();
        }        
    });*/


    jQuery(document).on('keydown', '.af2_sidebar_builder_content_textarea_edit_', e => {
        if (e.keyCode == 13)
        {
            e.preventDefault();
        }
    });

    const af2_make_sidebar_input_content_change = (dom_element) => {
       
        let { editContentId, editContentArrayId, fieldNumber, dataHandler, saveObjectEditId, saveObjectEditFieldId, saveObjectEditSpreadFields } = af2GetValuesToSaveObject(dom_element);

        const val =  jQuery(dom_element).val();

        af2SetValueToSaveObject(editContentArrayId, saveObjectEditId, saveObjectEditFieldId, saveObjectEditSpreadFields, val);

        af2_load_field_html_data(dataHandler, dataHandler.fields[fieldNumber]);
    }

    const af2_make_sidebar_checkbox_content_change = (dom_element) => {
        
        let { editContentId, editContentArrayId, fieldNumber, dataHandler, saveObjectEditId, saveObjectEditFieldId, saveObjectEditSpreadFields } = af2GetValuesToSaveObject(dom_element);
        
        const checked = jQuery(dom_element).is(":checked");
        const val = checked ? true : false;

        af2SetValueToSaveObject(editContentArrayId, saveObjectEditId, saveObjectEditFieldId, saveObjectEditSpreadFields, val);

        if(jQuery(dom_element).data('radiogroup') != null) {
            const radio_group = jQuery(dom_element).data('radiogroup');
            const id = jQuery(dom_element).attr('id');
            jQuery('input[data-radiogroup='+radio_group+']').not(jQuery('#'+id)).each((i, el) => {
                
                let { editContentId_, editContentArrayId_, fieldNumber_, dataHandler_, saveObjectEditId_, saveObjectEditFieldId_, saveObjectEditSpreadFields_ } = af2GetValuesToSaveObject(el, true);

                af2SetValueToSaveObject(editContentArrayId_, saveObjectEditId_, saveObjectEditFieldId_, saveObjectEditSpreadFields_, false);

                jQuery(el).prop('checked', false);
            });
        }
        
        af2_load_field_html_data(dataHandler, dataHandler.fields[fieldNumber]);
    }

    const af2_make_sidebar_image_icon_content_change = (ev) => {
        const dom_element = '#'+ev.object_id;

        const showid = jQuery(dom_element).data('mediashowid');

        let { editContentId, editContentArrayId, fieldNumber, dataHandler, saveObjectEditId, saveObjectEditFieldId, saveObjectEditSpreadFields } = af2GetValuesToSaveObject(dom_element);

        const val =  ev.value;
        
        if(dataHandler.fields[fieldNumber].show_preview == true) {
            if(val == null || val.trim() == '') { jQuery('#'+showid).html(''); }
            else {
                if(val.substr(0, 4) == 'http') {
                    if(dataHandler.fields[fieldNumber].details.icon_url == true) jQuery('#'+showid).html(val);
                    else jQuery('#'+showid).html('<img class="af2_show_media_preview_img" src="'+val+'">');
                } else {
                    jQuery('#'+showid).html('<i class="'+val+' af2_show_media_preview_icon"></i>')
                }
            }
        }

        af2SetValueToSaveObject(editContentArrayId, saveObjectEditId, saveObjectEditFieldId, saveObjectEditSpreadFields, val);

        af2_load_field_html_data(dataHandler, dataHandler.fields[fieldNumber]);
    }

    jQuery('.af2_builder_sidebar_element').on('click', _ => unsummonEditSidebar());

    const af2_make_sidebar_radio_content_change = (dom_element) => {
        let { editContentId, editContentArrayId, fieldNumber, dataHandler, saveObjectEditId, saveObjectEditFieldId, saveObjectEditSpreadFields } = af2GetValuesToSaveObject(dom_element);
        
        const val = jQuery(dom_element).val();

        af2SetValueToSaveObject(editContentArrayId, saveObjectEditId, saveObjectEditFieldId, saveObjectEditSpreadFields, val);
    }
    
    const af2_make_sidebar_select_content_change = (dom_element) => {
        let { editContentId, editContentArrayId, fieldNumber, dataHandler, saveObjectEditId, saveObjectEditFieldId, saveObjectEditSpreadFields } = af2GetValuesToSaveObject(dom_element);
        
        const val = jQuery(dom_element).val();

        af2SetValueToSaveObject(editContentArrayId, saveObjectEditId, saveObjectEditFieldId, saveObjectEditSpreadFields, val);
    }

    const af2_make_sidebar_color_content_change = (dom_element, val) => {
        let { editContentId, editContentArrayId, fieldNumber, dataHandler, saveObjectEditId, saveObjectEditFieldId, saveObjectEditSpreadFields } = af2GetValuesToSaveObject(dom_element);
        
        dom_element.attr('value', val);

        jQuery('#'+dom_element.data('colorpreviewid')).css('background-color', val);
        

        af2SetValueToSaveObject(editContentArrayId, saveObjectEditId, saveObjectEditFieldId, saveObjectEditSpreadFields, val);
    }

    const af2_make_sidebar_restriction_content_change = (dom_element) => {
        
        let { editContentId, editContentArrayId, fieldNumber, dataHandler, saveObjectEditId, saveObjectEditFieldId, saveObjectEditSpreadFields } = af2GetValuesToSaveObject(dom_element);
        
        let value = [];

        if(jQuery(dom_element).hasClass('af2_sidebar_builder_content_restriction_option_activate')) {
            value = [];
            const saveObjectId = jQuery('.af2_sidebar_builder_content_restriction_option[data-saveobjectid="'+saveObjectEditId+'"]').prop('checked', true);
        }
        else if(jQuery(dom_element).hasClass('af2_sidebar_builder_content_restriction_option_deactivate')) {
            value = dataHandler.fields[fieldNumber].options;
            const saveObjectId = jQuery('.af2_sidebar_builder_content_restriction_option[data-saveobjectid="'+saveObjectEditId+'"]').prop('checked', false);
        }
        else {
            const checked = jQuery(dom_element).is(":checked");
            const val = checked ? true : false;

            value = af2_builder_object.af2_save_object[saveObjectEditId];

            const newVal = jQuery(dom_element).data('optionvalue');
            if(!val) {
                if(!value.includes(newVal)) value.push(newVal);
            }
            else {
                value = value.filter(element => element != newVal);
            }
        }

        af2SetValueToSaveObject(editContentArrayId, saveObjectEditId, saveObjectEditFieldId, saveObjectEditSpreadFields, value);

        af2_load_field_html_data(dataHandler, dataHandler.fields[fieldNumber]);
    }


    const af2GetValuesToSaveObject = (dom_element, underscore) => {

        const editContentId = jQuery(dom_element).data('editcontentid');
        const editContentArrayId = jQuery(dom_element).data('editcontentarrayid');
        const fieldNumber = parseInt(jQuery(dom_element).data('fieldnumber'));

        const dataHandler = af2_builder_object.sidebar_elements.find(element => element.editContentId == editContentId);

        const saveObjectEditId = dataHandler.fields[fieldNumber].details.saveObjectId;
        const saveObjectEditFieldId = dataHandler.fields[fieldNumber].details.saveObjectIdField;

        const saveObjectEditSpreadFields = dataHandler.fields[fieldNumber].details.saveObjectIdSpreadFields;

        return underscore ? {
        'editContentId_' : editContentId,
        'editContentArrayId_' : editContentArrayId,
        'fieldNumber_' : fieldNumber,
        'dataHandler_' : dataHandler,
        'saveObjectEditId_' : saveObjectEditId,
        'saveObjectEditFieldId_' : saveObjectEditFieldId,
        'saveObjectEditSpreadFields_' : saveObjectEditSpreadFields
        } : {
        'editContentId' : editContentId,
        'editContentArrayId' : editContentArrayId,
        'fieldNumber' : fieldNumber,
        'dataHandler' : dataHandler,
        'saveObjectEditId' : saveObjectEditId,
        'saveObjectEditFieldId' : saveObjectEditFieldId,
        'saveObjectEditSpreadFields': saveObjectEditSpreadFields
        };
    }

    const af2SetValueToSaveObject = (editContentArrayId, saveObjectEditId, saveObjectEditFieldId, saveObjectEditSpreadFields, val) => {
        if(editContentArrayId == null) {
            if(saveObjectEditFieldId != null) {
                af2_builder_object.af2_save_object[saveObjectEditId][saveObjectEditFieldId] = val;

                if(saveObjectEditSpreadFields != null && saveObjectEditSpreadFields.length > 0) {
                    saveObjectEditSpreadFields.forEach(element => {
                        af2_builder_object.af2_save_object[saveObjectEditId][element] = val;
                    });
                }
            }
            else {
                af2_builder_object.af2_save_object[saveObjectEditId] = val;
            }
        }
        else {
            if(saveObjectEditFieldId != null) {
                af2_builder_object.af2_save_object[saveObjectEditId][editContentArrayId][saveObjectEditFieldId] = val;


                if(saveObjectEditSpreadFields != null && saveObjectEditSpreadFields.length > 0) {
                    saveObjectEditSpreadFields.forEach(element => {
                        af2_builder_object.af2_save_object[saveObjectEditId][editContentArrayId][element] = val;
                    });
                }
            }
            else {
                af2_builder_object.af2_save_object[saveObjectEditId][editContentArrayId] = val;
            }
        }
    }
});
