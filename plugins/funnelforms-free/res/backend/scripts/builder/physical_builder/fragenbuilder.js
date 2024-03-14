jQuery( document ).ready(function() {

    af2_builder_object.af2_save_object.name = af2_builder_object.af2_save_object.name != null ? af2_builder_object.af2_save_object.name : '';
    af2_builder_object.af2_save_object.description = af2_builder_object.af2_save_object.description != null ? af2_builder_object.af2_save_object.description : '';
    af2_builder_object.af2_save_object.tracking_code = af2_builder_object.af2_save_object.tracking_code != null ? af2_builder_object.af2_save_object.tracking_code : '';

    const af2FragenbuilderBuildAnswerPreset = (editcontentarrayid) => {
        const type = af2_builder_object.af2_save_object['typ'];
        const preDeletion = type == 'af2_select' ? 'af2_confirm_deletion' : '';

        let preset = '';
        preset += '<div class="af2_answer_wrapper af2_builder_editable_object af2_builder_deletable_object af2_array_draggable" data-deletetriggerid="af2_answers_container" data-saveobjectid="answers" data-editcontentid="answer" data-editcontentarrayid="'+editcontentarrayid+'">';
            preset += '<span class="af2_delete_object '+preDeletion+'" data-modalid="af2_delete_answer_modal" data-confirmationid="af2_confirm_deletion"><i class="fas fa-trash"></i></span>';
            preset += '<div id="af2_answer_img" class="af2_answer_img" data-editcontentarrayid="'+editcontentarrayid+'">';
            preset += '</div>';
            preset += '<div class="af2_answer_text_wrapper"><div id="af2_answer_text" class="af2_answer_text" data-editcontentarrayid="'+editcontentarrayid+'"></div></div>';
        preset += '</div>';

        return preset;
    }

    const af2FragenbuilderBuildAnswerPresets = () => {
        let presets = '';
        af2_builder_object.af2_save_object.answers.forEach((element, index) => {
            presets += af2FragenbuilderBuildAnswerPreset(index);
        });

        return presets;
    }


    /*
    * Object Manipulation (after load - and select)
    * Check that EVERYTHING IS THERE WE NEED
    */
    const af2_switch_type_builder_manipulation = (type) => {
        const af2_valuable = af2_builder_object.af2_save_object.af2_valuable;
        const description = af2_builder_object.af2_save_object.description;
        const name = af2_builder_object.af2_save_object.name;
        const tracking_code = af2_builder_object.af2_save_object.tracking_code;
        const typ = af2_builder_object.af2_save_object.typ;

        // Af2 Select - Multiselect
        const answers = af2_builder_object.af2_save_object.answers;
        const desktop_layout = af2_builder_object.af2_save_object.desktop_layout;
        const mobile_layout = af2_builder_object.af2_save_object.mobile_layout;
        const hide_icons = af2_builder_object.af2_save_object.hide_icons;
        const condition = af2_builder_object.af2_save_object.condition;

        // Textfeld - Textbereich attributes
        const textfeld = af2_builder_object.af2_save_object.textfeld;
        const textfield_mandatory = af2_builder_object.af2_save_object.textfield_mandatory;
        const textarea = af2_builder_object.af2_save_object.textarea;
        const textarea_mandatory = af2_builder_object.af2_save_object.textarea_mandatory;
        const min_length = af2_builder_object.af2_save_object.min_length;
        const max_length = af2_builder_object.af2_save_object.max_length;
        const text_only_text = af2_builder_object.af2_save_object.text_only_text;
        const text_only_numbers = af2_builder_object.af2_save_object.text_only_numbers;
        const text_birthday = af2_builder_object.af2_save_object.text_birthday;
        


        af2_builder_object.af2_save_object = {};

        af2_builder_object.af2_save_object.af2_valuable = af2_valuable;
        af2_builder_object.af2_save_object.description = description;
        af2_builder_object.af2_save_object.name = name;
        af2_builder_object.af2_save_object.typ = typ;
        af2_builder_object.af2_save_object.tracking_code = tracking_code;

        const heading_elements = af2_builder_object.sidebar_elements.find(element => element.editContentId == 'heading');
        const layout_elements = heading_elements.fields.filter(element => element.type == 'radio');
        const icon_checkbox = heading_elements.fields.filter(element => element.type == 'checkbox');
        const desktop_layout_el = layout_elements.find(element => element.details.saveObjectId == 'desktop_layout');
        const mobile_layout_el = layout_elements.find(element => element.details.saveObjectId == 'mobile_layout');

        layout_elements.forEach(element => element.enabled = false);
        icon_checkbox.forEach(element => element.enabled = false);

        switch(type) {
            case 'af2_select': {
                layout_elements.forEach(element => element.enabled = true);
                icon_checkbox.forEach(element => element.enabled = true);
                af2_builder_object.af2_save_object.desktop_layout = desktop_layout != undefined && desktop_layout != null ? desktop_layout : desktop_layout_el.default_option;
                af2_builder_object.af2_save_object.mobile_layout = mobile_layout != undefined && mobile_layout != null ? mobile_layout : mobile_layout_el.default_option;
                af2_builder_object.af2_save_object.hide_icons = hide_icons != undefined && hide_icons != null ? hide_icons : false;
                af2_builder_object.af2_save_object.answers = answers != null && answers != '' ? answers : [{"text":af2_fragenbuilder_object.strings.antwort, "img":"fas fa-atom"}, {"text": af2_fragenbuilder_object.strings.antwort, "img":"fas fa-atom"}];
                jQuery('.af2_answers_container').prepend(af2FragenbuilderBuildAnswerPresets()); // add Answers
                break;
            }
            case 'af2_multiselect': {
                layout_elements.forEach(element => element.enabled = true);
                icon_checkbox.forEach(element => element.enabled = true);
                af2_builder_object.af2_save_object.desktop_layout = desktop_layout != undefined && desktop_layout != null ? desktop_layout : desktop_layout_el.default_option;
                af2_builder_object.af2_save_object.mobile_layout = mobile_layout != undefined && mobile_layout != null ? mobile_layout : mobile_layout_el.default_option;
                af2_builder_object.af2_save_object.hide_icons = hide_icons != undefined && hide_icons != null ? hide_icons : false;
                af2_builder_object.af2_save_object.answers = answers != null && answers != '' ? answers : [{"text":af2_fragenbuilder_object.strings.antwort, "img":"fas fa-atom"}, {"text": af2_fragenbuilder_object.strings.antwort, "img":"fas fa-atom"}];
                af2_builder_object.af2_save_object.condition = condition != null && condition.trim() != '' ? condition : null;
                jQuery('.af2_answers_container').prepend(af2FragenbuilderBuildAnswerPresets()); // add Answers
                break;
            }
            case 'af2_textfeld': {
                af2_builder_object.af2_save_object.textfeld = textfeld != undefined ? textfeld : textarea != undefined ? textarea : '';
                af2_builder_object.af2_save_object.min_length = min_length != undefined ? min_length : null;
                af2_builder_object.af2_save_object.max_length = max_length != undefined ? max_length : null;
                af2_builder_object.af2_save_object.text_only_text = text_only_text != undefined ? text_only_text : false;
                af2_builder_object.af2_save_object.text_only_numbers = text_only_numbers != undefined ? text_only_numbers : false;
                af2_builder_object.af2_save_object.text_birthday = text_birthday != undefined ? text_birthday : false;
                af2_builder_object.af2_save_object.textfield_mandatory = textfield_mandatory != undefined ? textfield_mandatory : textarea_mandatory != undefined ? textarea_mandatory : false;
                break;
            }
            case 'af2_textbereich': {
                af2_builder_object.af2_save_object.textarea = textfeld != undefined ? textfeld : textarea != undefined ? textarea : '';
                af2_builder_object.af2_save_object.min_length = min_length != undefined ? min_length : null;
                af2_builder_object.af2_save_object.max_length = max_length != undefined ? max_length : null;
                af2_builder_object.af2_save_object.text_only_text = text_only_text != undefined ? text_only_text : false;
                af2_builder_object.af2_save_object.text_only_numbers = text_only_numbers != undefined ? text_only_numbers : false;
                af2_builder_object.af2_save_object.text_birthday = text_birthday != undefined ? text_birthday : false;
                af2_builder_object.af2_save_object.textarea_mandatory = textfield_mandatory != undefined ? textfield_mandatory : textarea_mandatory != undefined ? textarea_mandatory : false;
                break;
            }
        }
    }

    // Load the Object Data
    if(af2_builder_object.af2_save_object['typ'] != null) {
        jQuery('.af2_builder_sidebar_element[data-elementid="'+af2_builder_object.af2_save_object['typ']+'"]').addClass('selected');


        jQuery('.af2_question_type_wrapper_custom_content').html(af2_fragenbuilder_object.question_types[af2_builder_object.af2_save_object['typ']]);
        
        af2_switch_type_builder_manipulation(af2_builder_object.af2_save_object['typ']);
        //af2_load_object_data();
    }
        
    // Choosing Sidebar element
    jQuery('.af2_builder_sidebar_element').on('click', function() {
        if(jQuery(this).hasClass('af2_disabled_sidebar_element')) return;
        jQuery('.af2_builder_sidebar_element.selected').removeClass('selected');
        
        jQuery(this).addClass('selected');
        const elementId = jQuery(this).data('elementid');
        
        jQuery('.af2_question_type_wrapper_custom_content').html(af2_fragenbuilder_object.question_types[elementId]);
        af2_builder_object.af2_save_object['typ'] = elementId;

        af2_switch_type_builder_manipulation(af2_builder_object.af2_save_object['typ']);
        af2_load_object_data();
    });

    // Add Answer
    jQuery(document).on('click', '#af2_answer_wrapper_add', _ => {
        af2_builder_object.af2_save_object.answers.push({"text":af2_fragenbuilder_object.strings.antwort, "img":"fas fa-atom"});
        jQuery('.af2_answers_container').find('.af2_answer_wrapper').remove();
        jQuery('.af2_answers_container').prepend(af2FragenbuilderBuildAnswerPresets());

        const dataHandler = af2_builder_object.sidebar_elements.find(element => element.editContentId == 'answer');
        dataHandler.fields.forEach(field => af2_load_field_html_data(dataHandler, field));
    });

    af2_load_object_data();
    af2_load_input_html_data();

    const unsommonSidebarByFragenbuilder = () => {
        jQuery('.af2_builder_editable_object.selected').removeClass('selected');

        const handler = jQuery('.af2_builder_sidebar.editSidebar');

        handler.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', _ => {
            jQuery('.editSidebar .af2_builder_sidebar_content_wrapper').html();
        });
        handler.addClass('hide');
        jQuery('.af2_builder_content').removeClass('no_margin');
    }
    
    // On Delete function
    jQuery(document).on('af2_deleted_deleteable_object', '.af2_answers_container', _ => {
        jQuery('.af2_answers_container').find('.af2_answer_wrapper').not('.af2_dragging').remove();
        jQuery('.af2_answers_container').prepend(af2FragenbuilderBuildAnswerPresets());
        const dataHandler = af2_builder_object.sidebar_elements.find(element => element.editContentId == 'answer');
        dataHandler.fields.forEach(field => af2_load_field_html_data(dataHandler, field));
        unsommonSidebarByFragenbuilder();
    });
    // On Delete function
    jQuery(document).on('af2_deleted_deleteable_object', '.af2_dropdown_element_container', _ => {
        jQuery('.af2_dropdown_element_container').find('.af2_dropdown_element_wrapper').remove();
        jQuery('.af2_dropdown_element_container').prepend(af2FragenbuilderBuildDropdownPresets());
        const dataHandler = af2_builder_object.sidebar_elements.find(element => element.editContentId == 'dropdown');
        dataHandler.fields.forEach(field => af2_load_field_html_data(dataHandler, field));
        unsommonSidebarByFragenbuilder();
    });

    // On Drag function
    jQuery(document).on('af2_draggin_dragable_object', '.af2_answers_container', _ => {
        jQuery('.af2_answers_container').find('.af2_answer_wrapper').not('.af2_dragging').remove();
        jQuery('.af2_answers_container').prepend(af2FragenbuilderBuildAnswerPresets());
        const dataHandler = af2_builder_object.sidebar_elements.find(element => element.editContentId == 'answer');
        dataHandler.fields.forEach(field => af2_load_field_html_data(dataHandler, field));

        const selContainer = jQuery('.af2_answers_container');
        const selElement = jQuery('.af2_answers_container').find('.af2_answer_wrapper').not('.af2_dragging');
        af2_create_array_dropzones_in(selContainer, selElement, 'af2_answer_dropzone');
    });
    jQuery(document).on('af2_dropped_dragable_object', '.af2_answers_container', _ => {
        jQuery('.af2_answers_container').find('.af2_answer_wrapper').remove();
        jQuery('.af2_answers_container').find('.af2_array_dropzone_in').remove();
        jQuery('.af2_answers_container').prepend(af2FragenbuilderBuildAnswerPresets());
        const dataHandler = af2_builder_object.sidebar_elements.find(element => element.editContentId == 'answer');
        dataHandler.fields.forEach(field => af2_load_field_html_data(dataHandler, field));
    });
    
    jQuery('#af2_create_new_question').on('click', ev => {
        af2_save_builder( _ => {
            window.location.href = af2_fragenbuilder_object.create_new_question_url;
        });
    });
});
