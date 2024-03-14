jQuery( document ).ready(function() {

    jQuery(document).on('click', '#fnsf_af2_save_post', _ => af2_save_builder());

    jQuery(document).on('click', '.af2_builder_deletable_object span.af2_delete_object', function(ev) { 
        ev.stopPropagation();
        if(jQuery(this).hasClass('af2_confirm_deletion')) {
            const modalId = '#'+jQuery(this).attr('data-modalid');
            const confirm_deletion_selector = '#'+jQuery(this).attr('data-confirmationid');
            af2_open_modal(modalId);
            const delete_object = this;

            jQuery(document).on('click', confirm_deletion_selector, ev => {
                af2_close_modal();
                af2_delete_deleteable_object(delete_object); 
            });
        }
        else af2_delete_deleteable_object(this); 
    });
});

const af2_load_object_data = () => {
    af2_builder_object.sidebar_elements.forEach(sidebar_element => {
        sidebar_element.fields.forEach(field => af2_load_field_html_data(sidebar_element, field));
    });
}

const af2_load_field_html_data = (sidebar_element, field) => {
    
    let saveObjectValues = null;
    if(sidebar_element.editContentArray) saveObjectValues = af2_builder_object.af2_save_object[field.details.saveObjectId];
    else saveObjectValues = [af2_builder_object.af2_save_object[field.details.saveObjectId]];

    if(saveObjectValues == null || !Array.isArray(saveObjectValues)) return;

    if(field.details.saveObjectId == "hide_icons") {
        if(saveObjectValues[0]) {
            jQuery("#af2_answers_container").addClass("hide_icons");
        } else {
            jQuery("#af2_answers_container").removeClass("hide_icons");
        }
    }

    saveObjectValues.forEach((saveObjectValue, i) => {

        if(!field.details.html) return;

        let selector = null;
        let prefix = '#';
        if(field.details.htmlClass) prefix = '.'
        const selstr = prefix+field.details.htmlId+'[data-editcontentarrayid="'+i+'"]';
        if(sidebar_element.editContentArray) selector = jQuery(selstr).not('.af2_dragging '+selstr);
        else selector = jQuery(prefix+field.details.htmlId);

        if(selector == null || selector.length <= 0) return;

        let soValue = saveObjectValue;
        if(field.details.saveObjectIdField != null) soValue = saveObjectValue[field.details.saveObjectIdField];

        if(soValue == 'true') soValue = true;
        if(soValue == 'false') soValue = false;

        switch(field.type) {
            case 'text': {
                let value = field.details.empty_value;
                if(soValue != null && soValue.trim() != '') 
                {
                    value = soValue;
                    if(field.details.htmlPreset != null) value = field.details.htmlPreset+value;
                }
                if(field.details.htmlAttr != null) selector.attr(field.details.htmlAttr, value);
                else selector.html(value);
                break;
            }
            case 'textarea_': {
                //fallthrough
            }
            case 'textarea': {
                let value = field.details.empty_value;
                if(soValue != null && soValue.trim() != '') 
                {
                    value = soValue;
                    if(field.details.htmlPreset != null) value = field.details.htmlPreset+value;
                }
                if(field.details.htmlAttr != null) selector.attr(field.details.htmlAttr, value);
                else selector.html(value);
                break;
            }
            case 'checkbox': {
                // Do Nothing;
                break;
            }
            case 'radio': {
                // Do Nothing;
                break;
            }
            case 'select': {
                // Do Nothing;
                break;
            }
            case 'icon_image': {
                const value = soValue;
                selector.html('');
                if(field.details.empty_value != null) selector.html(field.details.empty_value);

                if(value == null || value.trim() == '') break;

                if(value.substr(0, 4) == 'http') {
                    if(field.details.icon_url == true) selector.html(value);
                    else selector.html('<img class="af2_icon_image_img" src="'+value+'">');
                } else {
                    selector.html('<i class="'+value+' af2_icon_image_icon"></i>')
                }
                break;
            }
            case 'color_picker': {
                // Do Nothing;
                break;
            }
            case 'restriction': {
                // Do Nothing;
                break;
            }
            default: {
                break;
            }
        }


        if(field.details.throwEvent != null) {
            let event = jQuery.Event(field.details.throwEvent);
            event.value = soValue;
            jQuery(selector).trigger(event);
        }

    });
}

const af2_load_input_html_data = () => {
    jQuery('.af2_edit_content_input').each((i, el) => {
        const saveObjectId = jQuery(el).data('saveobjectid');
        const saveObjectArrayId = jQuery(el).data('saveobjectarrayid');
        const saveObjectFieldId = jQuery(el).data('saveobjectfieldid');

        let val = af2_builder_object.af2_save_object[saveObjectId];

        if(saveObjectArrayId != null) {
            if(saveObjectFieldId != null) {
                val = af2_builder_object.af2_save_object[saveObjectId][saveObjectArrayId][saveObjectFieldId];
            }
            else {
                val = af2_builder_object.af2_save_object[saveObjectId][saveObjectArrayId];
            }
        }
        else {
            if(saveObjectFieldId != null) {
                val = af2_builder_object.af2_save_object[saveObjectId][saveObjectFieldId];
            }
        }

        jQuery(el).attr('value', val);
    });

    jQuery('.af2_edit_content_textarea').each((i, el) => {
        const saveObjectId = jQuery(el).data('saveobjectid');
        const val = af2_builder_object.af2_save_object[saveObjectId];
    
        jQuery(el).val(val);
    });

    jQuery('.af2_edit_content_checkbox_array_list').each((i, el) => {
        const saveObjectId = jQuery(el).data('saveobjectid');
        let values = af2_builder_object.af2_save_object[saveObjectId];

        if(values == null) {
            values = [];
            af2_builder_object.af2_save_object[saveObjectId] = [];
        }

        values.forEach(value => {
            const newVal = value == jQuery(el).data('saveobjectidvalue') ? true : false;
            jQuery(el).prop('checked', newVal);
        });
    });
    
    jQuery('.af2_edit_content_checkbox').each((i, el) => {
        const saveObjectId = jQuery(el).data('saveobjectid');
        let val = af2_builder_object.af2_save_object[saveObjectId];
        if(val == 'true') val = true;
        if(val == 'false') val = false;
    
        if(jQuery(el).data('saveobjectidvalue') != null) {
            const newVal = val == jQuery(el).data('saveobjectidvalue') ? true : false;
            jQuery(el).prop('checked', newVal);
        }
        else jQuery(el).prop('checked', val);


        const margin = jQuery(el).data('nomarginid');
        const toggle = jQuery(el).data('togglecontentid');

        if(val) jQuery('#'+margin).removeClass('no_margin');
        if(!val) jQuery('#'+margin).addClass('no_margin');

        if(val) jQuery('#'+toggle).removeClass('af2_hide');
        if(!val) jQuery('#'+toggle).addClass('af2_hide');
    });

    jQuery('.af2_edit_content_radio').each((i, el) => {
        const saveObjectId = jQuery(el).data('saveobjectid');
        let val = af2_builder_object.af2_save_object[saveObjectId];
        let saveObjectIdValue = jQuery(el).attr('value');
        const toggle = jQuery(el).data('togglecontentid');
        const toggleGroup = jQuery(el).data('togglecontentgroup');

        if(val == saveObjectIdValue) {
            jQuery(el).prop('checked', true);
            jQuery('.toggle_content_group[data-togglecontentgroup="'+toggleGroup+'"]').addClass('af2_hide');
            jQuery('#'+toggle).removeClass('af2_hide'); 
        }
    });
    
    jQuery('.af2_edit_content_select').each((i, el) => {
        const saveObjectId = jQuery(el).data('saveobjectid');
        const saveObjectArrayId = jQuery(el).data('saveobjectarrayid');
        const saveObjectFieldId = jQuery(el).data('saveobjectfieldid');

        let val = null;

        if(saveObjectArrayId != null) {
            if(saveObjectFieldId != null) {
                val = af2_builder_object.af2_save_object[saveObjectId][saveObjectArrayId][saveObjectFieldId];
            }
            else {
                val = af2_builder_object.af2_save_object[saveObjectId][saveObjectArrayId];
            }
        }
        else {
            if(saveObjectFieldId != null) {
                val = af2_builder_object.af2_save_object[saveObjectId][saveObjectFieldId];
            }
            else {
                val = af2_builder_object.af2_save_object[saveObjectId];
            }
        }



        jQuery(el).val(val).change();
    });
}

// Free Elements //
jQuery(document).on('input', '.af2_edit_content_input', function() {
    const val = jQuery(this).val();
    const saveObjectId = jQuery(this).data('saveobjectid');
    const saveObjectArrayId = jQuery(this).data('saveobjectarrayid');
    const saveObjectFieldId = jQuery(this).data('saveobjectfieldid');
    
    if(saveObjectArrayId != null) {
        if(saveObjectFieldId != null) {
            af2_builder_object.af2_save_object[saveObjectId][saveObjectArrayId][saveObjectFieldId] = val;
        }
        else {
            af2_builder_object.af2_save_object[saveObjectId][saveObjectArrayId] = val;
        }
    }
    else {
        if(saveObjectFieldId != null) {
            af2_builder_object.af2_save_object[saveObjectId][saveObjectFieldId] = val;
        }
        else {
            af2_builder_object.af2_save_object[saveObjectId] = val;
        }
    }
});

jQuery(document).on('input', '.af2_edit_content_textarea', function() {
    const val = jQuery(this).val();
    const saveObjectId = jQuery(this).data('saveobjectid');
    const saveObjectArrayId = jQuery(this).data('saveobjectarrayid');
    const saveObjectFieldId = jQuery(this).data('saveobjectfieldid');
    
    if(saveObjectArrayId != null) {
        if(saveObjectFieldId != null) {
            af2_builder_object.af2_save_object[saveObjectId][saveObjectArrayId][saveObjectFieldId] = val;
        }
        else {
            af2_builder_object.af2_save_object[saveObjectId][saveObjectArrayId] = val;
        }
    }
    else {
        if(saveObjectFieldId != null) {
            af2_builder_object.af2_save_object[saveObjectId][saveObjectFieldId] = val;
        }
        else {
            af2_builder_object.af2_save_object[saveObjectId] = val;
        }
    }
});

jQuery(document).on('click', '.af2_edit_content_radio', function() { 
    const radioName = jQuery(this).attr('name');
    const val = jQuery('input[name="'+radioName+'"]:checked').val();
    const saveObjectId = jQuery(this).data('saveobjectid');

    af2_builder_object.af2_save_object[saveObjectId] = val;

    const toggle = jQuery(this).data('togglecontentid');
    const toggleGroup = jQuery(this).data('togglecontentgroup');

    jQuery('.toggle_content_group[data-togglecontentgroup="'+toggleGroup+'"]').addClass('af2_hide');
    jQuery('#'+toggle).removeClass('af2_hide');
});

jQuery(document).on('change', '.af2_edit_content_select', function() {
    const val = jQuery(this).val();
    const saveObjectId = jQuery(this).data('saveobjectid');
    const saveObjectArrayId = jQuery(this).data('saveobjectarrayid');
    const saveObjectFieldId = jQuery(this).data('saveobjectfieldid');
    
    if(saveObjectArrayId != null) {
        if(saveObjectFieldId != null) {
            af2_builder_object.af2_save_object[saveObjectId][saveObjectArrayId][saveObjectFieldId] = val;
        }
        else {
            af2_builder_object.af2_save_object[saveObjectId][saveObjectArrayId] = val;
        }
    }
    else {
        if(saveObjectFieldId != null) {
            af2_builder_object.af2_save_object[saveObjectId][saveObjectFieldId] = val;
        }
        else {
            af2_builder_object.af2_save_object[saveObjectId] = val;
        }
    }
});

jQuery(document).on('click', '.af2_edit_content_checkbox_array_list', function() {
    let val = jQuery(this).is(":checked") ? true : false;
    const saveObjectId = jQuery(this).data('saveobjectid');
    const saveObjectIdValue = jQuery(this).data('saveobjectidvalue');

    af2_builder_object.af2_save_object[saveObjectId] = Array.isArray(af2_builder_object.af2_save_object[saveObjectId]) ? af2_builder_object.af2_save_object[saveObjectId] : [];
    
    const index = af2_builder_object.af2_save_object[saveObjectId].indexOf(saveObjectIdValue);
    if(val) {
        if(index == -1) {
            af2_builder_object.af2_save_object[saveObjectId].push(saveObjectIdValue)
        }
    }
    if(!val) {
        if(index > -1) {
            af2_builder_object.af2_save_object[saveObjectId].splice(index, 1);
        }
    }
});

jQuery(document).on('click', '.af2_edit_content_checkbox', function() {
    let val = jQuery(this).is(":checked") ? true : false;
    const saveObjectId = jQuery(this).data('saveobjectid');

    if(jQuery(this).data('saveobjectidvalue') != null) {
        if(val) af2_builder_object.af2_save_object[saveObjectId] = jQuery(this).data('saveobjectidvalue');
    }
    else af2_builder_object.af2_save_object[saveObjectId] = val;
    

    const margin = jQuery(this).data('nomarginid');
    const toggle = jQuery(this).data('togglecontentid');

    if(val) jQuery('#'+margin).removeClass('no_margin');
    if(!val) jQuery('#'+margin).addClass('no_margin');

    if(val) jQuery('#'+toggle).removeClass('af2_hide');
    if(!val) jQuery('#'+toggle).addClass('af2_hide');

    // different active but always one active
    if(jQuery(this).data('radioclickgroup') != null) {
        const own = jQuery(this).is(":checked") ? true : false;
        const others = jQuery('input[type="checkbox"][data-radioclickgroup="'+jQuery(this).data('radioclickgroup')+'"]').not(this).is(':checked') ? true : false;
        if(own == others) jQuery('input[type="checkbox"][data-radioclickgroup="'+jQuery(this).data('radioclickgroup')+'"]').not(this).trigger('click');
    }
    // different active but nobody has to be active
    if(jQuery(this).data('radioclickgroupa') != null) {
        const own = jQuery(this).is(":checked") ? true : false;
        const others = jQuery('input[type="checkbox"][data-radioclickgroupa="'+jQuery(this).data('radioclickgroupa')+'"]').not(this).is(':checked') ? true : false;
        if(own && others) jQuery('input[type="checkbox"][data-radioclickgroupa="'+jQuery(this).data('radioclickgroupa')+'"]').not(this).trigger('click');
    }
});


const af2_drag_array_draggable_object = (callbackInsertData, handlerElement) => {
    let insertData = null;
        
    const dragContentId = jQuery(handlerElement).data('saveobjectid');
    const dragContentArrayId = jQuery(handlerElement).data('editcontentarrayid');
    const dragTriggerId = jQuery(handlerElement).data('deletetriggerid');

    if(!jQuery(handlerElement).hasClass('af2_no_delete')) insertData = af2_builder_object.af2_save_object[dragContentId].splice(dragContentArrayId, 1)[0];
    callbackInsertData(insertData);

    let event = jQuery.Event('af2_draggin_dragable_object');
    event.handlerElement = handlerElement;
    jQuery('#'+dragTriggerId).trigger(event);
}

const af2_active_dragging = (handlerElement) => {
    const dragTriggerId = jQuery(handlerElement).data('deletetriggerid');
    
    let event = jQuery.Event('af2_active_draggin_dragable_object');
    event.handlerElement = handlerElement;
    jQuery('#'+dragTriggerId).trigger(event);
}

const af2_drag_array_add_draggable_object = (handlerElement) => {
    let event = jQuery.Event('af2_draggin_add_dragable_object');
    event.handlerElement = handlerElement;
    jQuery('.af2_add_array_draggable_restrict').trigger(event);
}

const af2_drop_array_draggable_object = (insertData, position, handlerElement) => {
    const dragContentId = jQuery(handlerElement).data('saveobjectid');
    const dragTriggerId = jQuery(handlerElement).data('deletetriggerid');

    if(insertData != null) af2_builder_object.af2_save_object[dragContentId].splice(position, 0, insertData);
    let event = jQuery.Event('af2_dropped_dragable_object');
    event.addElement = handlerElement;
    event.position = position;
    jQuery('#'+dragTriggerId).trigger(event);
}

const af2_create_array_dropzones_in = (container, elements, bonusClass) => {
    // Append last 
    container.prepend(af2_create_array_dropzone_html(0, bonusClass));

    elements.each((i, el) => {
        jQuery(el).after(af2_create_array_dropzone_html(i+1, bonusClass));
    });
}

const af2_create_array_dropzone_html = (arrayid, bonusClass) => {
    let content = '';
    content += '<div class="af2_array_dropzone_in '+bonusClass+'" data-arrayid="'+arrayid+'">';
    content += '</div>';
    return content;
}

const af2_dropped_line = (dragElement, dropElement, x1, x2, y1, y2, eventSelector) => {
    let event = jQuery.Event('af2_dropped_line');
    event.dragElement = dragElement;
    event.dropElement = dropElement;
    event.x1 = x1;
    event.y1 = y1;
    event.x2 = x2;
    event.y2 = y2;
    jQuery(eventSelector).trigger(event);
};

const af2_delete_deleteable_object = (dom_element) => {
    const handlerElement = jQuery(dom_element).closest('.af2_builder_deletable_object');

    const deleteContentId = jQuery(handlerElement).data('saveobjectid');
    const deleteContentArrayId = jQuery(handlerElement).data('editcontentarrayid');
    const deleteTriggerId = jQuery(handlerElement).data('deletetriggerid');

    if(deleteContentArrayId == null && !handlerElement.hasClass('af2_no_delete')) return;
    if(!handlerElement.hasClass('af2_no_delete')) af2_builder_object.af2_save_object[deleteContentId].splice([deleteContentArrayId], 1);

    let event = jQuery.Event('af2_deleted_deleteable_object');
    event.deleteContentArrayId = deleteContentArrayId;
    event.element = handlerElement;
    jQuery('#'+deleteTriggerId).trigger(event);
}

const af2_save_builder = (callback, show_modal_messages) => {

    let data = new Object();
    data.post_id = af2_builder_object.post_id;
    data.content = af2_builder_object.af2_save_object;
    data.page = af2_builder_object.page;
    data = JSON.stringify(data);

    jQuery('.af2_error_object').removeClass('af2_error_object');

    af2_create_toast('af2_toast_wrapper', af2_builder_object.strings.speichern, 'af2_info', false);
    jQuery.ajax({
        url: af2_builder_object.ajax_url,
        type: "POST",
        data: {
            'action' : 'fnsf_af2_save_post',
            nonce : af2_builder_object.nonce,
            'json' : data
        },
        success: (msg) => {
            if(callback!= null) {
                if(show_modal_messages) af2_clear_toast('af2_toast_wrapper', af2_save_modal_messages, msg);
                else af2_clear_toast('af2_toast_wrapper');
                callback();
            }
            else af2_clear_toast('af2_toast_wrapper', af2_save_modal_messages, msg);
        },
        error: (jqXHR, error, errorThrown) => {
            // console.log("error", jqXHR, error, errorThrown);
            af2_clear_toast('af2_toast_wrapper', _ => {
                jQuery('#af2_save_modal .af2_modal_content').html('');
                af2_create_toast('af2_toast_wrapper', af2_builder_object.strings.support, 'af2_error');
            });
        }
    });
};


const af2_save_modal_messages = (messages) => {
    if(messages != null && messages != '') messages = JSON.parse(messages);
    
    let errorCollection = [];

    jQuery('#af2_save_modal .af2_modal_content').html('');

    messages.forEach(el => {

        if(el.type == 'af2_error') {
            errorCollection.push(el);
            jQuery('#af2_save_modal .af2_modal_content').append('<p>'+el.label+'</p>');
            if(el.error_object != null) jQuery(el.error_object).addClass('af2_error_object');
        }

        else af2_create_toast('af2_toast_wrapper', el.label, el.type);
    });


    if(errorCollection.length > 0) af2_create_toast('af2_toast_wrapper', af2_builder_object.strings.error, 'af2_error', true, af2_open_modal, '#af2_save_modal');
}
